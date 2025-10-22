<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperadminMiddleware
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
        // Use the super_admin guard!
        if (!Auth::guard('super_admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('superAdmin.login');
        }

        // If you want to check for a role field, use:
        $user = Auth::guard('super_admin')->user();
        if (isset($user->role) && $user->role !== 'superadmin') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Superadmin access required.'], 403);
            }
            return redirect()->route('home')->with('error', 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}