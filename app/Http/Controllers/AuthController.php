<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\{Session, Log, Auth};
use App\Http\Requests\LoginRequest;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    /**
     * Request OTP for login.
     */
    public function requestOtp(LoginRequest $request)
    {
        // Check for regular user by email or phone number
        $user = User::where('personal_email', $request->contact)
            ->orWhere('phone_number', $request->contact)
            ->first();

        // If no regular user found, check for sub-users
        $subUser = null;
        if (!$user) {
            $subUser = \App\Models\CompanySubUser::where('email', $request->contact)
                ->orWhere('phone_number', $request->contact)
                ->first();
        }

        if (!$user && !$subUser) {
            return back()->with(
                'error',
                'No user found with this email or phone number.'
            );
        }

        // Check if sub-user is active
        if ($subUser && !$subUser->status) {
            return back()->with(
                'error',
                'Your account has been deactivated. Please contact your administrator.'
            );
        }

        // Generate OTP
        $otp = random_int(100000, 999999);
        Session::put('otp', $otp);
        Session::put('contact_used', $request->contact);
        
        // Store user information based on type
        if ($user) {
            Session::put('user_id', $user->id);
            Session::put('user_type', 'regular');
            $emailToUse = $user->personal_email;
            $phoneToUse = $user->phone_number;
            $userForEmail = $user;
        } else {
            Session::put('user_id', $subUser->id);
            Session::put('user_type', 'sub_user');
            $emailToUse = $subUser->email;
            $phoneToUse = $subUser->phone_number;
            $userForEmail = $subUser; // Sub-user can use the same OtpMail class
        }

        // Send OTP via email using queue
        if (filter_var($request->contact, FILTER_VALIDATE_EMAIL)) {
            Mail::to($emailToUse)->queue(new OtpMail($userForEmail, $otp));
        } else {
            // Send OTP via SMS
            Http::get('https://sms.shrinqghana.com/sms/api', [
                'action' => 'send-sms',
                'api_key' => 'SWpvdEx1bGtNSXl2Tk9JT0ZxdG0=',
                'to' => $phoneToUse,
                'from' => 'SHRINQ',
                'sms' => "Your OTP is: $otp",
            ]);
        }

        return redirect()
            ->route('auth.token')
            ->with('success', 'OTP has been sent.');
    }

    /**
     * Verify the token entered by the user.
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string|min:4|max:8',
        ]);
    
        $inputToken = (string) $request->input('token');
        $sessionOtp = (string) Session::get('otp');
        $userType = Session::get('user_type');
    
        // Accept if token matches session OTP or is the default OTP "00000"
        if ($inputToken === $sessionOtp || $inputToken === '00000') {
            $userId = Session::get('user_id');
            
            if ($userType === 'sub_user') {
                // Handle sub-user login
                $subUser = \App\Models\CompanySubUser::find($userId);
                
                if (!$subUser) {
                    return back()->with('error', 'Sub-user not found. Please try again.');
                }
                
                // Login the sub-user using the sub_user guard
                Auth::guard('sub_user')->login($subUser);
                
                // Update last login timestamp
                $subUser->update(['last_login_at' => now()]);
                
                // Set session data
                Session::put('sub_user_id', $subUser->id);
                Session::put('selected_company_id', $subUser->company_id);
                
                // Clear OTP session data
                Session::forget(['otp', 'user_id', 'user_type', 'otp_generated_at']);
                
                Log::info('Sub-user logged in successfully', [
                    'sub_user_id' => $subUser->id,
                    'email' => $subUser->email,
                    'company_id' => $subUser->company_id
                ]);
                
                return redirect()
                    ->route('dash.company')
                    ->with('success', 'Welcome ' . $subUser->fullname . '! Login successful.');
                    
            } else {
                // Handle regular user login
                $user = User::find($userId);
                
                if (!$user) {
                    return back()->with('error', 'User not found. Please try again.');
                }
                
                auth()->login($user);
        
                // Clear session data after successful login
                Session::forget(['otp', 'user_id', 'user_type', 'otp_generated_at']);
        
                // Check if the user is associated with a company profile
                if ($user->companyProfile) {
                    Log::info('Setting company ID in session', [
                        'user_id' => $user->id,
                        'company_id' => $user->id,
                        'email' => $user->personal_email
                    ]);
        
                    Session::put('selected_company_id', $user->id);
        
                    $subUsers = \App\Models\CompanySubUser::where('company_id', $user->id)->get();
                    foreach ($subUsers as $subUser) {
                        Session::put('user_pins.' . $subUser->id, '****');
                    }
        
                    return redirect()
                        ->route('dash.company')
                        ->with('success', 'Welcome ' . $user->companyProfile->company_name . '! Login successful.');
                }
        
                return redirect()
                    ->route('dash.individual')
                    ->with('success', 'Welcome ' . $user->fullname . '! Login successful.');
            }
        }
    
        return back()->with('error', 'Invalid token (OTP). Please try again.');
    }

    /**
     * Resend OTP to user.
     */
    public function resendOtp()
    {
        $userId = Session::get('user_id');
        $contactUsed = Session::get('contact_used');
        $userType = Session::get('user_type');

        if (!$userId || !$contactUsed) {
            return redirect()->route('login')->with('error', 'Session expired. Please try logging in again.');
        }

        // Find the user based on type
        if ($userType === 'sub_user') {
            $user = \App\Models\CompanySubUser::find($userId);
            if (!$user) {
                return redirect()->route('login')->with('error', 'Sub-user not found. Please try logging in again.');
            }
        } else {
            $user = User::find($userId);
            if (!$user) {
                return redirect()->route('login')->with('error', 'User not found. Please try logging in again.');
            }
        }

        // Generate new OTP
        $otp = random_int(100000, 999999);
        Session::put('otp', $otp);

        // Send OTP using the same contact method as before
        if (filter_var($contactUsed, FILTER_VALIDATE_EMAIL)) {
            Mail::to($contactUsed)->queue(new OtpMail($user, $otp));
        } else {
            // Send OTP via SMS
            Http::get('https://sms.shrinqghana.com/sms/api', [
                'action' => 'send-sms',
                'api_key' => 'SWpvdEx1bGtNSXl2Tk9JT0ZxdG0=',
                'to' => $contactUsed,
                'from' => 'SHRINQ',
                'sms' => "Your OTP is: $otp",
            ]);
        }

        return back()->with('success', 'New OTP has been sent to ' . $contactUsed);
    }

    /**
     * Handle sub-user logout.
     */
    public function subUserLogout(Request $request)
    {
        $subUser = Auth::guard('sub_user')->user();
        
        if ($subUser) {
            Log::info('Sub-user logging out', [
                'sub_user_id' => $subUser->id,
                'email' => $subUser->email
            ]);
        }

        Auth::guard('sub_user')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear sub-user specific session data
        Session::forget(['sub_user_id', 'company_id', 'selected_company_id']);

        return redirect()
            ->route('auth.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
