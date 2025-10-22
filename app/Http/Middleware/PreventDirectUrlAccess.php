<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Middleware to prevent direct URL access to authentication pages
 * Users must click "Get Started" button to gain access to these pages
 */
class PreventDirectUrlAccess
{
    /**
     * Handle an incoming request.
     * Checks if the user has been granted access through proper navigation
     * or if they are already authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check two conditions:
        // 1. User is not authenticated (not logged in)
        // 2. User hasn't clicked "Get Started" (no access_granted session)
        if (!Auth::check() && !Session::has('access_granted')) {
            // Redirect to welcome page with error message
            return redirect()->route('root')
                           ->with('error', 'Please use the proper navigation to access this page.');
        }

        // Allow access if either condition is met
        return $next($request);
    }
}
