<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckScreenLock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // List of routes that should bypass the lock screen
        $allowedRoutes = [
            'lock-screen',
            'verify-pin',
            'lock.logout',
            'auth.login',
            'logout',
            'company-sub-users.*'
        ];

        // Skip middleware for allowed routes
        foreach ($allowedRoutes as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        // If screen is locked, redirect to lock screen
        if (Session::get('screen_locked', false)) {
            return redirect()->route('lock-screen');
        }

        return $next($request);
    }
}
