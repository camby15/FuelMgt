<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiagnoseDownloadTemplate
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
        // Log detailed request information
        Log::info('DiagnoseDownloadTemplate Middleware', [
            'path' => $request->path(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'is_ajax' => $request->ajax(),
            'headers' => $request->headers->all(),
            'input' => $request->all(),
            'user' => auth()->check() ? [
                'id' => auth()->id(),
                'email' => auth()->user()->email,
                'roles' => auth()->user()->roles->pluck('name')->toArray()
            ] : 'No User'
        ]);

        // Additional authentication checks
        if (!auth()->check()) {
            Log::warning('Unauthenticated download template attempt');
            return response()->json([
                'error' => 'Authentication Required',
                'message' => 'Please log in to download the template'
            ], 401);
        }

        return $next($request);
    }
}
