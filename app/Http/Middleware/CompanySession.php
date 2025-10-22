<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Session, Auth, Log};

class CompanySession
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
        $hasCompanyId = Session::has('selected_company_id');
        $currentCompanyId = Session::get('selected_company_id');
        
        Log::info('CompanySession middleware check', [
            'url' => $request->url(),
            'has_company_id' => $hasCompanyId,
            'current_company_id' => $currentCompanyId,
            'session_id' => session()->getId()
        ]);
        
        if (!$hasCompanyId) {
            // Try to set company_id from authenticated user
            $companyId = null;
            
            if (Auth::guard('company_sub_user')->check()) {
                $subUser = Auth::guard('company_sub_user')->user();
                $companyId = $subUser->company_id;
                Log::info('CompanySession: Set company_id from company_sub_user', ['company_id' => $companyId]);
            } elseif (Auth::guard('sub_user')->check()) {
                $subUser = Auth::guard('sub_user')->user();
                $companyId = $subUser->company_id;
                Log::info('CompanySession: Set company_id from sub_user', ['company_id' => $companyId]);
            } elseif (Auth::check()) {
                $user = Auth::user();
                if ($user->companyProfile) {
                    $companyId = $user->id;
                    Log::info('CompanySession: Set company_id from default auth user', ['company_id' => $companyId]);
                }
            }
            
            if ($companyId) {
                Session::put('selected_company_id', $companyId);
                Log::info('CompanySession: Company ID set successfully', ['company_id' => $companyId]);
            } else {
                Log::warning('CompanySession: No company ID found, redirecting to login');
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Company session expired. Please login again.'
                    ], 401);
                }
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }
        }

        Log::info('CompanySession: Company session check passed');
        return $next($request);
    }
}
