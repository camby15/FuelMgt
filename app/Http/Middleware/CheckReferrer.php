<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckReferrer
{
    public function handle(Request $request, Closure $next)
    {
        // Allowed referrers for local development
        $allowedReferrers = ['localhost', '127.0.0.1'];

        $referer = $request->headers->get('referer');

        // Parse the host from the referer URL
        $refererHost = $referer ? parse_url($referer, PHP_URL_HOST) : null;

        // Redirect to the welcome page if unauthorized access is detected
        if ($refererHost && !in_array($refererHost, $allowedReferrers)) {
            return redirect()
                ->route('root')
                ->with('error', 'Unauthorized access.'); // Redirect to the welcome page
        }

        return $next($request);
    }
}
