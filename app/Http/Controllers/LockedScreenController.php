<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyPinRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LockedScreenController extends Controller
{
    /**
     * Show the lock screen page.
     */
    public function showLockScreen()
    {
        // Check if regular user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $userType = 'regular';
        }
        // Check if sub-user is authenticated
        elseif (Auth::guard('sub_user')->check()) {
            $user = Auth::guard('sub_user')->user();
            $userType = 'sub_user';
        }
        else {
            return redirect()->route('auth.login');
        }
        
        // Set screen lock session with user type
        Session::put('screen_locked', true);
        Session::put('locked_user_type', $userType);
        
        return view('authentications.lock-screen', compact('user', 'userType'));
    }

    /**
     * Handle the lock screen PIN verification.
     */
    public function verifyPin(VerifyPinRequest $request)
    {
        $inputPin = $request->input('pin');
        $userType = Session::get('locked_user_type', 'regular');
        
        // Get the appropriate user based on type
        if ($userType === 'sub_user') {
            $user = Auth::guard('sub_user')->user();
            if (!$user) {
                return redirect()->route('auth.login')->with('error', 'Session expired. Please login again.');
            }
        } else {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('auth.login')->with('error', 'Session expired. Please login again.');
            }
        }

        // Debug information
        Log::info('Pin Verification Attempt', [
            'input_pin' => $inputPin,
            'user_id' => $user->id,
            'user_type' => $userType,
            'user_name' => $user->fullname,
            'has_company_profile' => ($userType === 'regular' && $user->companyProfile) ? 'Yes' : 'No',
            'stored_pin' => $user->pin_code // This will show hashed pin
        ]);

        // Check if the provided PIN matches the user's stored PIN
        if (Hash::check($inputPin, $user->pin_code)) {
            // Remove screen lock session
            Session::forget(['screen_locked', 'locked_user_type']);
            
            // Determine redirect route based on user type
            if ($userType === 'sub_user') {
                $redirectRoute = 'dash.company'; // Sub-users always go to company dashboard
            } else {
                // Regular users: check if they have a company profile
                $isCompany = $user->companyProfile !== null;
                $redirectRoute = $isCompany ? 'dash.company' : 'dash.individual';
            }
            
            Log::info('Pin Verification Success - Redirecting', [
                'user_id' => $user->id,
                'user_type' => $userType,
                'redirect_route' => $redirectRoute
            ]);

            // Redirect to appropriate dashboard
            return redirect()->route($redirectRoute)
                           ->with('success', 'Welcome back!');
        }

        Log::info('Pin Verification Failed', [
            'user_id' => $user->id,
            'user_type' => $userType,
            'input_pin' => $inputPin
        ]);

        return back()->withErrors(['pin' => 'Invalid PIN provided.']);
    }

    /**
     * Log out the user and redirect to login.
     */
    public function logout()
    {
        $userType = Session::get('locked_user_type', 'regular');
        
        // Logout based on user type
        if ($userType === 'sub_user') {
            Auth::guard('sub_user')->logout();
        } else {
            Auth::logout();
        }
        
        Session::flush();
        return redirect()->route('auth.login');
    }
}
