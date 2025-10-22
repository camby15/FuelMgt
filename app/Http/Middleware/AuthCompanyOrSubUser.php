<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};

class AuthCompanyOrSubUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authChecks = [
            'default_auth' => Auth::check(),
            'company_sub_user' => Auth::guard('company_sub_user')->check(),
            'sub_user' => Auth::guard('sub_user')->check(),
        ];
        
        Log::info('AuthCompanyOrSubUser middleware check', [
            'url' => $request->url(),
            'auth_checks' => $authChecks,
            'session_id' => session()->getId()
        ]);
        
        // Check if regular user is authenticated
        if (Auth::check()) {
            Log::info('AuthCompanyOrSubUser: Default auth passed');
            return $next($request);
        }
        
        // Check if company sub-user is authenticated
        if (Auth::guard('company_sub_user')->check()) {
            $subUser = Auth::guard('company_sub_user')->user();
            
            // Check if sub-user is still active
            if (!$subUser->status) {
                Auth::guard('company_sub_user')->logout();
                Log::warning('AuthCompanyOrSubUser: Company sub-user deactivated');
                return redirect()->route('auth.login')->with('error', 'Your account has been deactivated.');
            }
            
            Log::info('AuthCompanyOrSubUser: Company sub-user auth passed');
            return $next($request);
        }
        
        // Check if sub-user is authenticated
        if (Auth::guard('sub_user')->check()) {
            $subUser = Auth::guard('sub_user')->user();
            
            // Check if sub-user is still active
            if (!$subUser->status) {
                Auth::guard('sub_user')->logout();
                Log::warning('AuthCompanyOrSubUser: Sub-user deactivated');
                return redirect()->route('auth.login')->with('error', 'Your account has been deactivated.');
            }
            
            Log::info('AuthCompanyOrSubUser: Sub-user auth passed');
            return $next($request);
        }

        // Neither authenticated, redirect to login
        Log::warning('AuthCompanyOrSubUser: No authentication found, redirecting to login');
        return redirect()->route('auth.login')->with('error', 'Please login to continue.');
    }
}
