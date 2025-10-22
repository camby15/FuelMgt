<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthSubUser
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
        if (!Auth::guard('sub_user')->check()) {
            return redirect()->route('auth.login')->with('error', 'Please login to continue.');
        }

        // Check if sub-user is still active
        $subUser = Auth::guard('sub_user')->user();
        if (!$subUser->status) {
            Auth::guard('sub_user')->logout();
            return redirect()->route('auth.login')->with('error', 'Your account has been deactivated.');
        }

        return $next($request);
    }
}
