<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CompanyUserRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use App\Mail\OtpMail;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CompanyUserController extends Controller
{
    /**
     * Display the company dashboard.
     */
    public function index(): View|RedirectResponse
    {
        // Check if sub-user is authenticated
        if (Auth::guard('sub_user')->check()) {
            $subUser = Auth::guard('sub_user')->user();
            $company = $subUser->company()->with('companyProfile')->first();
            
            return view('company.index', [
                'user' => $subUser,
                'company' => $company->companyProfile ?? null,
                'page_title' => 'Company Dashboard',
                'mode' => session('mode', ''),
                'demo' => session('demo', ''),
                'is_sub_user' => true
            ]);
        }
        
        // Check if regular user is authenticated
        if (!Auth::check()) {
            return redirect()
                ->route('auth.login')
                ->with('error', 'Please login to continue');
        }

        $user = Auth::user();
        $companyProfile = $user->companyProfile;

        return view('company.index', [
            'user' => $user,
            'company' => $companyProfile,
            'page_title' => 'Company Dashboard',
            'mode' => session('mode', ''),
            'demo' => session('demo', ''),
            'is_sub_user' => false
        ]);
    }

    /**
     * Register a company user.
     */
    public function register(CompanyUserRequest $request)
    {
        try {
            // Validate the request (this is done automatically by CompanyUserRequest)
            $validated = $request->validated();
    
            // Create the user
            $user = User::create([
                'fullname' => $validated['company_name'], // Store company name as the user's fullname
                'personal_email' => $validated['primary_email'],
                'phone_number' => $validated['company_phone'],
                'pin_code' => Hash::make($validated['pin_code']),
            ]);
    
            // Create the company profile
            $user->companyProfile()->create([
                'company_name' => $validated['company_name'],
                'company_email' => $validated['company_email'],
                'company_phone' => $validated['company_phone'],
                'primary_email' => $validated['primary_email'],
                'pin_code' => Hash::make($validated['pin_code']),
            ]);
    
            // Log the user in
            // Auth::login($user);
    
            // Flash a success message for SweetAlert
            // session()->flash(
            //    'success',
             //   'Your company account has been created successfully! Welcome ' .
             //       $validated['company_name']
            // );

            
                
        // Generate OTP
        $otp = random_int(100000, 999999);                               // Generate a random 6-digit OTP
        Session::put('otp', $otp);                                       // Store OTP in session
        Session::put('user_id', $user->id);                             // Store user ID in session
         
        // Send OTP via email using queue
         if (filter_var($request->contact, FILTER_VALIDATE_EMAIL)) {
           Mail::to($user->personal_email)->queue(new OtpMail($user, $otp));
      
        } else {
            // Send OTP via SMS
            Http::get('https://sms.shrinqghana.com/sms/api', [
                'action' => 'send-sms',
                'api_key' => 'SWpvdEx1bGtNSXl2Tk9JT0ZxdG0=',
                'to' => $user->phone_number,
                'from' => 'SHRINQ',
                'sms' => "Your OTP is: $otp",
            ]);
        }

        return redirect()
            ->route('auth.token')
            ->with('success', 'Account Created Successfully. An OTP has been sent.');


        } catch (\Exception $e) {
            // If the exception isn't validation, use a generic error message
            return redirect()
                ->back()
                ->with('error', 'Registration failed. Please try again.');
        }
    }
}
