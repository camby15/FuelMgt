<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\Superuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SuperAdmin\SuperuserResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SuperuserController extends Controller
{
    /**
     * Display a listing of the superusers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $perPage = request('per_page', 10); // Default to 10 items per page
            $users = Superuser::paginate($perPage);
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $users->items(),
                    'pagination' => [
                        'total' => $users->total(),
                        'per_page' => $users->perPage(),
                        'current_page' => $users->currentPage(),
                        'last_page' => $users->lastPage(),
                        'next_page_url' => $users->nextPageUrl(),
                        'prev_page_url' => $users->previousPageUrl(),
                        'from' => $users->firstItem(),
                        'to' => $users->lastItem()
                    ]
                ]);
            }
            
            return view('superAdmin.superusers', ['users' => $users]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching users: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch users: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to load users. Please try again.');
        }
    }

    /**
     * Show the form for creating a new superuser.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('superAdmin.superusers'); // Form is already in the blade
    }

    /**
     * Store a newly created superuser in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:superusers',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:Admin,Manager,User',
            'status' => 'required|in:Active,Inactive',
            'avatar' => 'nullable|image|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $superuser = new Superuser();
            $superuser->name = $request->name;
            $superuser->email = $request->email;
            $superuser->password = Hash::make($request->password);
            $superuser->role = $request->role;
            $superuser->status = $request->status;

            // Handle avatar upload if present
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $superuser->avatar = $path;
            }

            $superuser->save();

            return response()->json([
                'success' => true,
                'message' => 'Superuser created successfully',
                'superuser' => $superuser
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating superuser: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified cb88 superuser.
     *
     * @param  \App\Models\SuperAdmin\Superuser  $superuser
     * @return \Illuminate\Http\Response
     */
    public function show(Superuser $user)
    {
        return view('superAdmin.superusers', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified superuser.
     *
     * @param  \App\Models\SuperAdmin\Superuser  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Superuser $user)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                    'status' => $user->status
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching user data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load user data'
            ], 500);
        }
    }

    /**
     * Update the specified superuser in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SuperAdmin\Superuser  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Superuser $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:superusers,email,' . $user->id,
            'role' => 'required|in:Admin,Manager,User',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;

            // Handle avatar upload if provided
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified superuser from storage.
     *
     * @param  \App\Models\SuperAdmin\Superuser  $superuser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Superuser $user)
    {
        try {
            // Prevent deleting self
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account.'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user. Please try again.'
            ], 500);
        }
    }

    /**
     * Toggle the status of a superuser.
     *
     * @param  \App\Models\SuperAdmin\Superuser  $superuser
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Superuser $user, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Active,Inactive'
        ]);

        $user->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'status' => $user->status,
            'message' => 'Status updated successfully'
        ]);
    }

    /**
     * Update the role of a superuser.
     *
     * @param  \App\Models\SuperAdmin\Superuser  $superuser
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Superuser $user, Request $request)
    {
        $request->validate([
            'role' => 'required|in:Admin,Manager,User'
        ]);

        try {
            $user->update(['role' => $request->role]);
            
            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating role: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update role'
            ], 500);
        }
    }

    /**
     * Import users from CSV file
     */
    public function import(Request $request)
    {
        try {
            $validated = $request->validate([
                'import_file' => 'required|file|mimes:csv,txt|max:2048'
            ]);

            // Read and validate CSV
            $file = $request->file('import_file');
            $csvData = array_map('str_getcsv', file($file->getRealPath()));
            
            if (count($csvData) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'The CSV file is empty'
                ], 422);
            }

            // Get and validate headers
            $headers = array_map('trim', array_shift($csvData));
            $requiredHeaders = ['Name', 'Email', 'Role', 'Status'];
            
            $missingHeaders = array_diff($requiredHeaders, $headers);
            if (!empty($missingHeaders)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required columns: ' . implode(', ', $missingHeaders)
                ], 422);
            }

            // Process in transaction
            return DB::transaction(function () use ($csvData, $headers, $requiredHeaders) {
                $imported = 0;
                $skipped = 0;
                $errors = [];
                $emails = [];

                foreach ($csvData as $index => $row) {
                    if (count($row) !== count($headers)) {
                        $skipped++;
                        $errors[] = "Row " . ($index + 2) . ": Column count mismatch";
                        continue;
                    }

                    $data = array_combine($headers, array_map('trim', $row));
                    
                    // Skip empty rows
                    if (empty(array_filter($data))) {
                        $skipped++;
                        continue;
                    }

                    // Validate row data
                    $validator = Validator::make($data, [
                        'Name' => 'required|string|max:255',
                        'Email' => [
                            'required',
                            'email:rfc,dns',
                            'unique:superusers,email',
                            function ($attribute, $value, $fail) use (&$emails, $index) {
                                if (in_array($value, $emails)) {
                                    $fail("Duplicate email found at row " . ($index + 2));
                                }
                                $emails[] = $value;
                            }
                        ],
                        'Role' => ['required', Rule::in(['admin', 'user', 'manager'])],
                        'Status' => ['required', Rule::in(['Active', 'Inactive'])],
                    ]);

                    if ($validator->fails()) {
                        $skipped++;
                        $errors = array_merge($errors, array_map(
                            fn($error) => "Row " . ($index + 2) . ": " . $error,
                            $validator->errors()->all()
                        ));
                        continue;
                    }

                    // Create superuser
                    Superuser::create([
                        'name' => $data['Name'],
                        'email' => $data['Email'],
                        'role' => strtolower($data['Role']),
                        'status' => $data['Status'] === 'Active' ? 1 : 0,
                        'password' => Hash::make(Str::random(12)),
                        'email_verified_at' => now(),
                    ]);

                    $imported++;
                }

                $response = [
                    'success' => true,
                    'message' => "Successfully imported $imported superusers.",
                    'stats' => [
                        'imported' => $imported,
                        'skipped' => $skipped,
                        'total' => count($csvData)
                    ]
                ];

                if ($skipped > 0) {
                    $response['message'] .= " $skipped rows were skipped due to errors.";
                    $response['errors'] = array_slice($errors, 0, 10); // Return first 10 errors
                    if (count($errors) > 10) {
                        $response['message'] .= ' (showing first 10 errors)';
                    }
                }

                return response()->json($response);

            }); // End transaction

        } catch (\Exception $e) {
            \Log::error('Error importing superusers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred during import. Please try again.'
            ], 500);
        }
    }
    /**
     * Download CSV template for importing users
     */
    public function downloadTemplate()
    {
        try {
            $filename = 'superusers_import_template_' . date('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() {
                $handle = fopen('php://output', 'w');
                
                // Add BOM for Excel compatibility
                fwrite($handle, "\xEF\xBB\xBF");
                
                // Headers
                fputcsv($handle, ['Name', 'Email', 'Role', 'Status']);
                
                // Example data
                fputcsv($handle, ['John Doe', 'john@example.com', 'user', 'Active']);
                fputcsv($handle, ['Jane Smith', 'jane@example.com', 'admin', 'Active']);
                
                
                
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            \Log::error('Error downloading template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate template. Please try again.'
            ], 500);
        }
    }

    /**
     * Export users to CSV
     */
    public function export()
    {
        try {
            $users = Superuser::all();
            $filename = 'users_export_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($users) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fputs($file, "\xEF\xBB\xBF");
                
                // Headers
                fputcsv($file, ['Name', 'Email', 'Role', 'Status', 'Created At']);
                
                // Data
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->name,
                        $user->email,
                        $user->role,
                        $user->status ? 'Active' : 'Inactive',
                        $user->created_at->format('Y-m-d H:i')
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

            } catch (\Exception $e) {
                \Log::error('Export failed: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate export'
                ], 500);
            }
    }

    
}