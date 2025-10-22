<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\CompanySubUser;
use App\Models\User;
use App\Mail\SubUserMail;
use App\Http\Requests\UsersRequests\{StoreCompanySubUserRequest, UpdateCompanySubUserRequest};
use Illuminate\Support\Facades\{Hash, Log, Auth, Session, DB, Validator, Http, Mail};
use Illuminate\Validation\Rule;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class CompanySubUserController extends Controller
{
    /**
     * Display a listing of the sub users.
     */
    public function index(): View|RedirectResponse
    {
        try {
            // Check authentication with multiple guards
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isDefaultAuth = Auth::check();
            $isSubUser = Auth::guard('sub_user')->check();
            
            if (!$isCompanySubUser && !$isDefaultAuth && !$isSubUser) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            // If no company ID in session, try to set it from authenticated user
            if (!$companyId) {
                if ($isCompanySubUser) {
                    $subUser = Auth::guard('company_sub_user')->user();
                    $companyId = $subUser->company_id;
                    Session::put('selected_company_id', $companyId);
                    Log::info('Set company ID from company_sub_user', ['company_id' => $companyId]);
                } elseif ($isSubUser) {
                    $subUser = Auth::guard('sub_user')->user();
                    $companyId = $subUser->company_id;
                    Session::put('selected_company_id', $companyId);
                    Log::info('Set company ID from sub_user', ['company_id' => $companyId]);
                } elseif ($isDefaultAuth) {
                    $user = Auth::user();
                    if ($user->companyProfile) {
                        $companyId = $user->id;
                        Session::put('selected_company_id', $companyId);
                        Log::info('Set company ID from default auth user', ['company_id' => $companyId]);
                    }
                }
            }
            
            if (!$companyId) {
                Log::warning('No company ID in session');
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Company session expired. Please login again.');
            }

            // Get the company user
            $companyUser = User::with('companyProfile')->find($companyId);

            // Get all sub users for this company
            $users = CompanySubUser::where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->get();

            // Get or generate PINs for each user
            foreach ($users as $user) {
                if (!Session::has('user_pins.' . $user->id)) {
                    // For existing users, generate a new PIN
                    $newPin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                    
                    // Update the PIN in the database
                    $user->update([
                        'pin_code' => Hash::make($newPin)
                    ]);
                    
                    // Store the unhashed PIN in session
                    Session::put('user_pins.' . $user->id, $newPin);
                }
                
                // Attach the unhashed PIN to the user object
                $user->unhashed_pin = Session::get('user_pins.' . $user->id);
            }

            return view('company.user-management.users', [
                'users' => $users,
                'company' => $companyUser->companyProfile
            ]);

        } catch (\Exception $e) {
            Log::error('Error in index method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error loading users. Please try again.');
        }
    }

    /**
     * Store a newly created sub user.
     */
    public function store(Request $request)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Company session expired. Please login again.');
            }

            // Check if phone exists for another user
            $existingPhone = CompanySubUser::where('phone_number', $request->phone_number)
                ->where('company_id', $companyId)
                ->first();

            if ($existingPhone) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['phone_number' => 'This phone number is already registered for another sub-user.']);
            }

            // Validate request
            $validated = $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('company_sub_users')->where(function ($query) use ($companyId) {
                        return $query->where('company_id', $companyId);
                    })
                ],
                'phone_number' => [
                    'required',
                    'string'
                ],
                'pin_code' => 'required|string|min:4|max:4',
                'role' => 'required|string|in:sub_user,limited_user'
            ]);

            // Create the sub user
            $subUser = new CompanySubUser();
            $subUser->company_id = $companyId;
            $subUser->fullname = $validated['fullname'];
            $subUser->email = $validated['email'];
            $subUser->phone_number = $validated['phone_number'];
            $originalPin = $validated['pin_code'];
            $subUser->pin_code = Hash::make($validated['pin_code']);
            $subUser->role = $validated['role'];
            
            if ($subUser->save()) {
                // Store the original PIN in session
                Session::put('user_pins.' . $subUser->id, $originalPin);
                
                // Get company name
                $company = User::with('companyProfile')->find($companyId);
                $companyName = $company->companyProfile->company_name ?? 'Company';
                
                return redirect()
                    ->route('company-sub-users.index')
                    ->with('success', "New sub user created successfully for {$companyName}");
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create user. Please try again.');

        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating sub user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified sub user.
     */
    public function update(Request $request, CompanySubUser $companySubUser)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()
                    ->back()
                    ->with('error', 'Company ID not found. Please try again.');
            }

            // Validate request
            $validated = $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('company_sub_users')->where(function ($query) use ($companyId) {
                        return $query->where('company_id', $companyId);
                    })->ignore($companySubUser->id)
                ],
                'phone_number' => [
                    'required',
                    'string',
                    Rule::unique('company_sub_users')->where(function ($query) use ($companyId) {
                        return $query->where('company_id', $companyId);
                    })->ignore($companySubUser->id)
                ],
                'role' => 'required|string|in:sub_user,limited_user'
            ]);

            // Check if email exists for another user
            $existingEmail = CompanySubUser::where('email', $request->email)
                ->where('company_id', $companyId)
                ->where('id', '!=', $companySubUser->id)
                ->first();

            if ($existingEmail) {
                return redirect()
                    ->back()
                    ->with('error', 'This email is already registered for another sub-user.')
                    ->withInput();
            }

            // Check if phone exists for another user
            $existingPhone = CompanySubUser::where('phone_number', $request->phone_number)
                ->where('company_id', $companyId)
                ->where('id', '!=', $companySubUser->id)
                ->first();

            if ($existingPhone) {
                return redirect()
                    ->back()
                    ->with('error', 'This phone number is already registered for another sub-user.')
                    ->withInput();
            }

            // Update the user
            $companySubUser->update([
                'fullname' => $validated['fullname'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'role' => $validated['role']
            ]);

            // Generate new PIN if not in session
            if (!Session::has('user_pins.' . $companySubUser->id)) {
                $newPin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                
                // Update the PIN in the database
                $companySubUser->update([
                    'pin_code' => Hash::make($newPin)
                ]);
                
                // Store the unhashed PIN in session
                Session::put('user_pins.' . $companySubUser->id, $newPin);
            }

            return redirect()
                ->route('company-sub-users.index')
                ->with('success', 'User updated successfully');

        } catch (\Exception $e) {
            Log::error('Error updating sub user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    /**
     * Remove the specified sub user.
     */
    public function destroy(CompanySubUser $companySubUser)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId || $companyId !== $companySubUser->company_id) {
                return redirect()
                    ->back()
                    ->with('error', 'Unauthorized access');
            }

            // Store the name for the success message
            $userName = $companySubUser->fullname;

            // Delete the user
            if ($companySubUser->delete()) {
                return redirect()
                    ->route('company-sub-users.index')
                    ->with('success', "User {$userName} has been deleted successfully");
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to delete user. Please try again.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the lock status of the specified sub user.
     */
    public function toggleLock(CompanySubUser $companySubUser)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            Log::info('Toggling lock status of sub user', [
                'company_id_from_session' => $companyId,
                'auth_user_id' => Auth::id(),
                'sub_user_id' => $companySubUser->id
            ]);
            
            if (!$companyId || $companyId !== $companySubUser->company_id) {
                Log::warning('Unauthorized access to toggle lock status of sub user');
                return redirect()
                    ->back()
                    ->with('error', 'Unauthorized access');
            }

            $companySubUser->update(['status' => !$companySubUser->status]);
            $action = $companySubUser->status ? 'unlocked' : 'locked';
            
            Log::info('Sub user lock status toggled successfully', [
                'sub_user_id' => $companySubUser->id,
                'company_id' => $companyId,
                'new_status' => $action
            ]);
            
            return redirect()
                ->route('company-sub-users.index')
                ->with('success', "User has been {$action} successfully!");

        } catch (\Exception $e) {
            Log::error('Error toggling lock status of sub user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to update user status');
        }
    }

    /**
     * Reset the password for the specified sub user.
     */
    public function resetPassword(CompanySubUser $companySubUser)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            Log::info('Resetting password of sub user', [
                'company_id_from_session' => $companyId,
                'auth_user_id' => Auth::id(),
                'sub_user_id' => $companySubUser->id
            ]);
            
            if (!$companyId || $companyId !== $companySubUser->company_id) {
                Log::warning('Unauthorized access to reset password of sub user');
                return redirect()
                    ->back()
                    ->with('error', 'Unauthorized access');
            }

            $newPin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            
            $companySubUser->update([
                'pin_code' => Hash::make($newPin)
            ]);

            Log::info('Sub user password reset successfully', [
                'sub_user_id' => $companySubUser->id,
                'company_id' => $companyId,
                'new_pin' => $newPin
            ]);

            Session::put('user_pins.'.$companySubUser->id, $newPin);

            return redirect()
                ->route('company-sub-users.index')
                ->with('success', "User PIN has been reset successfully");

        } catch (\Exception $e) {
            Log::error('Error resetting password of sub user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to reset PIN');
        }
    }

    /**
     * Get the PIN for the specified sub user.
     */
    public function getPin(CompanySubUser $companySubUser)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId || $companyId !== $companySubUser->company_id) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Get PIN from session or generate a new one
            if (!Session::has('user_pins.'.$companySubUser->id)) {
                $newPin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                $companySubUser->update([
                    'pin_code' => Hash::make($newPin)
                ]);
                Session::put('user_pins.'.$companySubUser->id, $newPin);
            }

            $pin = Session::get('user_pins.'.$companySubUser->id);
            
            // If pin is '****' or empty, generate a new one
            if ($pin === '****' || empty($pin)) {
                $newPin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                
                $companySubUser->update([
                    'pin_code' => Hash::make($newPin)
                ]);

                Session::put('user_pins.'.$companySubUser->id, $newPin);
                $pin = $newPin;
            }

            return response()->json([
                'pin' => $pin
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting PIN for sub user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Failed to get PIN'], 500);
        }
    }

    /**
     * Show the form for editing the specified sub user.
     */
    public function edit(CompanySubUser $companySubUser = null)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Please login to continue');
            }
    
            $companyId = Session::get('selected_company_id');
            
            // Fetch all users for the company
            $users = CompanySubUser::where('company_id', $companyId)->get();
    
            // Validate the user belongs to the company if passed
            $user = ($companySubUser && $companyId === $companySubUser->company_id) 
                ? $companySubUser 
                : null;
    
            return view('company.user-management.users', compact('users', 'user'));
    
        } catch (\Exception $e) {
            Log::error('Error showing edit form for sub user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return redirect()
                ->back()
                ->with('error', 'Failed to load edit form');
        }
    }

    /**
     * Send user details via SMS.
     */
    public function sendSms(CompanySubUser $companySubUser)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId || $companyId !== $companySubUser->company_id) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Get PIN from session or generate a new one
            if (!Session::has('user_pins.'.$companySubUser->id)) {
                $newPin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                $companySubUser->update([
                    'pin_code' => Hash::make($newPin)
                ]);
                Session::put('user_pins.'.$companySubUser->id, $newPin);
            }

            $pin = Session::get('user_pins.'.$companySubUser->id);

            // If pin is '****' or empty, generate a new one
            if ($pin === '****' || empty($pin)) {
                $newPin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                $companySubUser->update([
                    'pin_code' => Hash::make($newPin)
                ]);
                Session::put('user_pins.'.$companySubUser->id, $newPin);
                $pin = $newPin;
            }

            // Prepare message
            $message = "Your Stak account details:\n";
            $message .= "Email: {$companySubUser->email}\n";
            $message .= "Phone: {$companySubUser->phone_number}\n";
            $message .= "PIN: {$pin}";

            // Send SMS
            $response = Http::get('https://sms.shrinqghana.com/sms/api', [
                'action' => 'send-sms',
                'api_key' => 'SWpvdEx1bGtNSXl2Tk9JT0ZxdG0=',
                'to' => $companySubUser->phone_number,
                'from' => 'SHRINQ',
                'sms' => $message,
            ]);

            Log::info('SMS sent to sub user', [
                'user_id' => $companySubUser->id,
                'phone' => $companySubUser->phone_number,
                'response' => $response->json()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SMS sent successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending SMS to sub user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to send SMS'
            ], 500);
        }
    }

    /**
     * Send user details via email.
     */
    public function sendEmail(CompanySubUser $companySubUser)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId || $companyId !== $companySubUser->company_id) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Check if user has email
            if (empty($companySubUser->email)) {
                Log::error('Cannot send email: User has no email address', ['user_id' => $companySubUser->id]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'User has no email address configured.'
                ], 400);
            }

            // Get PIN from session or generate a new one
            if (!Session::has('user_pins.'.$companySubUser->id)) {
                $newPin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                $companySubUser->update([
                    'pin_code' => Hash::make($newPin)
                ]);
                Session::put('user_pins.'.$companySubUser->id, $newPin);
            }

            $pin = Session::get('user_pins.'.$companySubUser->id);
            
            // If pin is '****' or empty or hashed, generate a new one
            if ($pin === '****' || empty($pin) || strlen($pin) > 4) {
                $newPin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                $companySubUser->update([
                    'pin_code' => Hash::make($newPin)
                ]);
                Session::put('user_pins.'.$companySubUser->id, $newPin);
                $pin = $newPin;
            }

            // Log the attempt
            Log::info('Attempting to send email to user', [
                'user_id' => $companySubUser->id,
                'email' => $companySubUser->email
            ]);
            
            // Send email using queue with original PIN
            Mail::to($companySubUser->email)->queue(new SubUserMail($companySubUser, $pin));

            // Log success
            Log::info('Email queued successfully', [
                'user_id' => $companySubUser->id,
                'email' => $companySubUser->email
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Email delivered successfully.'
            ]);
        } catch (\Exception $e) {
            // Log detailed error information
            Log::error('Failed to send email', [
                'user_id' => $companySubUser->id,
                'email' => $companySubUser->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send email. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a CSV template for bulk user upload
     */
    public function downloadTemplate()
    {
        // Log the method call for debugging
        // Log::info('Download Template method called');

        // Create a temporary file
        $filename = 'Sub Users Bulk Upload Template.csv';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);

        // Open the file for writing
        $handle = fopen($tempFile, 'w');

        // Write the header row
        $headers = ['Fullname', 'Email', 'Phone Number', 'Pin', 'Role'];
        fputcsv($handle, $headers);

        // Write an example row (optional)
        $exampleRow = [
            'John Doe',
            'john.doe@example.com',
            '+1234567890',
            '1234',
            'user'
        ];
        fputcsv($handle, $exampleRow);

        // Close the file
        fclose($handle);

        // Log the file path for debugging
        //Log::info('Temp file created: ' . $tempFile);

        // Return the file for download
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Bulk upload users from a CSV file
     */
    public function bulkUpload(Request $request): RedirectResponse
    {
        // Validate the uploaded file
        $request->validate([
            'bulk_upload_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        // Check if user is authenticated
        if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
            return redirect()->back()->with('error', 'Please login to continue');
        }

        // Get the company ID from the session
        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            return redirect()->back()->with('error', 'Company session expired');
        }

        try {
            // Open the uploaded file
            $file = $request->file('bulk_upload_file');
            $handle = fopen($file->getPathname(), 'r');
            
            // Read the header row
            $headers = fgetcsv($handle);
            
            // Validate headers
            $requiredHeaders = ['Fullname', 'Email', 'Phone Number', 'Pin', 'Role'];
            foreach ($requiredHeaders as $requiredHeader) {
                if (!in_array($requiredHeader, $headers)) {
                    return redirect()->back()->with('error', "Missing required column: $requiredHeader");
                }
            }

            // Prepare tracking variables
            $successCount = 0;
            $failedUsers = [];

            // Process each row
            while (($row = fgetcsv($handle)) !== false) {
                // Create an associative array from the row
                $userData = array_combine($headers, $row);

                // Validate user data
                $validator = Validator::make($userData, [
                    'Fullname' => 'required|string|max:255',
                    'Email' => 'required|email|unique:company_sub_users,email',
                    'Phone Number' => 'required|unique:company_sub_users,phone_number',
                    'Pin' => 'required|digits:4',
                    'Role' => 'in:admin,user,manager'
                ]);

                // If validation fails, log the error and continue
                if ($validator->fails()) {
                    $failedUsers[] = [
                        'data' => $userData,
                        'errors' => $validator->errors()->all()
                    ];
                    continue;
                }

                // Create the user
                $subUser = new CompanySubUser();
                $subUser->company_id = $companyId;
                $subUser->fullname = $userData['Fullname'];
                $subUser->email = $userData['Email'];
                $subUser->phone_number = $userData['Phone Number'];
                $subUser->role = $userData['Role'] ?? 'user';
                $subUser->status = true;
                $subUser->pin_code = Hash::make($userData['Pin']);
                $subUser->save();

                $successCount++;
            }

            // Close the file
            fclose($handle);

            // Prepare response message
            $message = "Successfully imported $successCount users.";
            if (!empty($failedUsers)) {
                $message .= " " . count($failedUsers) . " users failed to import.";
                // Optional: Log failed imports for admin review
                Log::warning('Bulk user import partial failure', ['failed_imports' => $failedUsers]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Bulk upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Bulk upload failed: ' . $e->getMessage());
        }
    }
}