<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthCompanyUser
{
   public function handle($request, Closure $next)
    {
        Log::info('Auth guards:', [
    'web' => Auth::guard('web')->check(),
    'subuser' => Auth::guard('subuser')->check(),
    'current_user' => Auth::guard('web')->user() ?? Auth::guard('subuser')->user(),
]);
        if (Auth::guard('web')->check() || Auth::guard('subuser')->check()) {
            return $next($request);
        }

        return redirect()->route('auth.login')->with('error', 'You must be logged in to access this page.');
    }
}
