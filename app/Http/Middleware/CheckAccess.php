<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the session variable 'access_granted' is set
        if (!$request->session()->has('access_granted')) {
            // Redirect to the welcome page
            return redirect()
                ->route('root')
                ->with(
                    'error',
                    'You must use the Get Started button to access this page.'
                );
        }

        return $next($request);
    }
}
