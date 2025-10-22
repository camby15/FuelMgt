<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\CrmLeads;
use App\Models\Customer;
use App\Models\Opportunity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CrmDashboardController extends Controller
{
    /**
     * Display the CRM dashboard
     */
    public function index()
    {
        try {
            // Check authentication with multiple guards
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isDefaultAuth = Auth::check();
            $isSubUser = Auth::guard('sub_user')->check();
            
            if (!$isCompanySubUser && !$isDefaultAuth && !$isSubUser) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            // If no company ID in session, try to set it from authenticated user
            if (!$companyId) {
                if ($isCompanySubUser) {
                    $subUser = Auth::guard('company_sub_user')->user();
                    $companyId = $subUser->company_id;
                    Session::put('selected_company_id', $companyId);
                    Log::info('Set company ID from company_sub_user', ['company_id' => $companyId]);
                } elseif ($isSubUser) {
                    $subUser = Auth::guard('sub_user')->user();
                    $companyId = $subUser->company_id;
                    Session::put('selected_company_id', $companyId);
                    Log::info('Set company ID from sub_user', ['company_id' => $companyId]);
                } elseif ($isDefaultAuth) {
                    $user = Auth::user();
                    if ($user->companyProfile) {
                        $companyId = $user->id;
                        Session::put('selected_company_id', $companyId);
                        Log::info('Set company ID from default auth user', ['company_id' => $companyId]);
                    }
                }
            }
            
            if (!$companyId) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Company session expired. Please login again.');
            }

            // Get leads
            $leads = CrmLeads::where('company_id', $companyId)
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->get();

            // Get opportunities
            $opportunities = Opportunity::where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->get();

            // Get customers
            $customers = Customer::where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate opportunity stats
            $openOpportunities = $opportunities->whereNotIn('stage', ['Closed Won', 'Closed Lost']);
            $totalOpportunities = $opportunities->count();
            $closedOpportunities = $opportunities->whereIn('stage', ['Closed Won', 'Closed Lost'])->count();
            $wonOpportunities = $opportunities->where('stage', 'Closed Won')->count();
            
            $winRate = $closedOpportunities > 0 ? round(($wonOpportunities / $closedOpportunities) * 100, 1) : 0;
            $avgDealSize = $totalOpportunities > 0 ? round($opportunities->sum('amount') / $totalOpportunities, 2) : 0;

            $opportunityStats = [
                'total_value' => $opportunities->sum('amount'),
                'open_opportunities' => $openOpportunities->count(),
                'open_opportunities_percentage' => $totalOpportunities > 0 
                    ? round(($openOpportunities->count() / $totalOpportunities) * 100, 1) 
                    : 0,
                'win_rate' => $winRate,
                'avg_deal_size' => $avgDealSize
            ];

            return view('company.CRM.crm', [
                'leads' => $leads,
                'customers' => $customers,
                'opportunities' => $opportunities,
                'opportunityStats' => $opportunityStats,
                'activeTab' => 'leads' // Always default to leads tab
            ]);

        } catch (\Exception $e) {
            Log::error('Error in CRM Dashboard', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'company_id' => $companyId ?? 'Not set'
            ]);
            
            return view('company.CRM.crm', [
                'leads' => collect(),
                'customers' => collect(),
                'opportunities' => collect(),
                'opportunityStats' => [
                    'total_value' => 0,
                    'open_opportunities' => 0,
                    'open_opportunities_percentage' => 0,
                    'win_rate' => 0,
                    'avg_deal_size' => 0
                ],
                'activeTab' => 'leads' // Always default to leads tab even on error
            ]);
        }
    }
}
