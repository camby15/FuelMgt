<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\IndividualUserRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use App\Mail\OtpMail;
use Illuminate\View\View;

class IndividualUserController extends Controller
{
    /**
     * Register an individual user.
     */
    public function register(IndividualUserRequest $request)
    {
        try {
            // Validate the request
            $validated = $request->validated();

            // Create the user
            $user = User::create([
                'fullname' => $validated['fullname'],
                'personal_email' => $validated['personal_email'],
                'phone_number' => $validated['phone_number'],
                'pin_code' => Hash::make($validated['pin_code']),
            ]);

            // Log the user in
            // Auth::login($user);

            // Flash a success message for SweetAlert
            // session()->flash(
            //    'success',
            //    'Your individual account has been created successfully! Welcome ' .
            //        $validated['fullname']
            //);

                     
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
            // SweetAlert error message
            return redirect()
                ->back()
                ->with('error', 'Registration failed. Please try again.');
        }
    }

    /**
     * Display the individual dashboard.
     */
    public function index(): View
    {
        if (!Auth::check()) {
            return redirect()
                ->route('auth.login')
                ->with('error', 'Please login to continue');
        }

        $user = Auth::user();

        return view('individual.index', [
            'user' => $user,
            'page_title' => 'Individual Dashboard',
            'mode' => session('mode', ''),
            'demo' => session('demo', '')
        ]);
    }
}
