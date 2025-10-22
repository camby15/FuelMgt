<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OpportunityController extends Controller
{
    /**
     * Display a paginated list of all opportunities
     */
    public function index()
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // Log the company ID for debugging
            Log::info('Company ID for Opportunities', [
                'company_id' => $companyId
            ]);
    
            $opportunities = Opportunity::where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->paginate(5);
    
            // Extensive logging for opportunities
            Log::info('Opportunities Debug', [
                'total_count' => $opportunities->count(),
                'stages' => $opportunities->pluck('stage')->unique()->toArray(),
                'amounts' => $opportunities->pluck('amount')->toArray(),
                'opportunities_data' => $opportunities->map(function($opp) {
                    return [
                        'id' => $opp->id,
                        'name' => $opp->name,
                        'stage' => $opp->stage,
                        'amount' => $opp->amount
                    ];
                })->toArray()
            ]);
    
            // Calculate Opportunity Statistics
            $totalOpportunities = $opportunities->count();
            $openOpportunities = $opportunities->whereNotIn('stage', ['Closed Won', 'Closed Lost']);
            
            $winRate = $this->calculateWinRate($opportunities);
            $avgDealSize = $this->calculateAverageDealSize($opportunities);

            $opportunityStats = [
                'total_value' => $opportunities->sum('amount'),
                'open_opportunities' => $openOpportunities->count(),
                'open_opportunities_percentage' => $totalOpportunities > 0 
                    ? round(($openOpportunities->count() / $totalOpportunities) * 100, 1) 
                    : 0,
                'win_rate' => is_null($winRate) ? 0 : $winRate,
                'avg_deal_size' => is_null($avgDealSize) ? 0 : $avgDealSize
            ];
    
            // Log calculated stats with more detail
            Log::info('Opportunity Stats Calculation', [
                'total_opportunities' => $totalOpportunities,
                'open_opportunities_count' => $opportunityStats['open_opportunities'],
                'open_opportunities_percentage' => $opportunityStats['open_opportunities_percentage'],
                'win_rate' => $opportunityStats['win_rate'],
                'avg_deal_size' => $opportunityStats['avg_deal_size']
            ]);
    
            return response()->json([
                'error' => false,
                'message' => 'Opportunities fetched successfully',
                'data' => $opportunities->items(), // Leads data
                'pagination' => [
                    'total' => $opportunities->total(),
                    'per_page' => $opportunities->perPage(), 
                    'current_page' => $opportunities->currentPage(),
                    'last_page' => $opportunities->lastPage(),
                    'next_page_url' => $opportunities->nextPageUrl(),
                    'prev_page_url' => $opportunities->previousPageUrl()
                ]
            ]);
    
            // Calculate Opportunity Statistics
            $totalOpportunities = $opportunities->count();
            $openOpportunities = $opportunities->whereNotIn('stage', ['Closed Won', 'Closed Lost']);
            
            $winRate = $this->calculateWinRate($opportunities);
            $avgDealSize = $this->calculateAverageDealSize($opportunities);

        } catch (\Exception $e) {
            // Detailed error logging
            Log::error('Error in Opportunity Index Method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'company_id' => $companyId ?? 'Not set'
            ]);
            
            return view('company.CRM.opportunity', [
                'opportunities' => collect(),
                'opportunityStats' => [
                    'total_value' => 0,
                    'open_opportunities' => 0,
                    'open_opportunities_percentage' => 0,
                    'win_rate' => 0,
                    'avg_deal_size' => 0
                ],
                'activeTab' => 'opportunities'
            ]);
        }
    }

    /**
     * Calculate the win rate from opportunities
     */
    private function calculateWinRate($opportunities)
    {
        $closedOpportunities = $opportunities->whereIn('stage', ['Closed Won', 'Closed Lost'])->count();
        $wonOpportunities = $opportunities->where('stage', 'Closed Won')->count();
        
        return $closedOpportunities > 0 
            ? round(($wonOpportunities / $closedOpportunities) * 100, 1)
            : 0;
    }

    /**
     * Calculate average deal size from opportunities
     */
    private function calculateAverageDealSize($opportunities)
    {
        $totalAmount = $opportunities->sum('amount');
        $totalCount = $opportunities->count();
        
        return $totalCount > 0 
            ? round($totalAmount / $totalCount, 2)
            : 0;
    }

    /**
     * Show the form for creating a new opportunity
     */
    public function create()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            return view('company.CRM.create-opportunity');
        } catch (\Exception $e) {
            Log::error('Error preparing opportunity creation: ' . $e->getMessage());
            return back()->with('error', 'Unable to prepare opportunity creation');
        }
    }

    /**
     * Store a newly created opportunity in the database
     */
    public function store(Request $request)
    {
        $requestId = $request->header('X-Request-ID') ?? uniqid();
        $submissionTime = $request->header('X-Submission-Time');

        Log::info('Opportunity Store Request Received', [
            'request_id' => $requestId,
            'submission_time' => $submissionTime,
            'timestamp' => now(),
            'request_data' => $request->all(),
            'headers' => $request->headers->all(),
            'user_id' => auth()->id(),
            'company_id' => session('selected_company_id')
        ]);

        // Generate a unique hash for this submission based on the data
        $submissionHash = md5(json_encode([
            'name' => $request->name,
            'account' => $request->account,
            'stage' => $request->stage,
            'amount' => $request->amount,
            'expected_revenue' => $request->expected_revenue,
            'close_date' => $request->close_date,
            'probability' => $request->probability,
            'description' => $request->description,
            'company_id' => session('selected_company_id'),
            'user_id' => auth()->id()
        ]));

        // Check if this exact submission exists in cache
        if (Cache::has($submissionHash)) {
            $cachedSubmission = Cache::get($submissionHash);
            Log::warning('Duplicate submission detected', [
                'request_id' => $requestId,
                'submission_hash' => $submissionHash,
                'original_timestamp' => $cachedSubmission['timestamp'],
                'original_request_id' => $cachedSubmission['request_id']
            ]);
            return response()->json([
                'message' => 'This opportunity has already been submitted.',
                'duplicate' => true
            ], 422);
        }

        // Check if this request ID has been processed
        $requestKey = "opportunity_request_{$requestId}";
        if (Cache::has($requestKey)) {
            Log::warning('Duplicate request ID detected', [
                'request_id' => $requestId,
                'original_data' => Cache::get($requestKey)
            ]);
            return response()->json([
                'message' => 'This request has already been processed.',
                'duplicate' => true
            ], 422);
        }

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account' => 'required|string|max:255',
            'stage' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'expected_revenue' => 'required|numeric',
            'close_date' => 'required|date',
            'probability' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string'
        ]);

        Log::info('Opportunity Data Validated', [
            'validated_data' => $validated,
            'request_id' => $requestId
        ]);

        try {
            // Store submission in cache with a longer duration
            Cache::put($submissionHash, [
                'timestamp' => now(),
                'request_id' => $requestId
            ], now()->addHours(24)); // Keep for 24 hours to prevent duplicates

            // Store request ID in cache
            Cache::put($requestKey, [
                'timestamp' => now(),
                'data' => $request->all()
            ], now()->addHours(24));

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'account' => 'required|string|max:255',
                'stage' => 'required|string',
                'amount' => 'required|numeric',
                'close_date' => 'required|date',
                'probability' => 'required|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                Log::warning('Opportunity validation failed', [
                    'request_id' => $requestId,
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $opportunity = new Opportunity();
            $opportunity->name = $request->name;
            $opportunity->account = $request->account;
            $opportunity->stage = $request->stage;
            $opportunity->amount = $request->amount;
            $opportunity->expected_revenue = $request->expected_revenue;
            $opportunity->close_date = $request->close_date;
            $opportunity->probability = $request->probability;
            $opportunity->description = $request->description;
            $opportunity->company_id = session('selected_company_id');
            $opportunity->user_id = auth()->id();
            $opportunity->save();

            // Recalculate stats after adding new opportunity
            $opportunities = Opportunity::where('company_id', session('selected_company_id'))
                ->orderBy('created_at', 'desc')
                ->get();

            $totalOpportunities = $opportunities->count();
            $openOpportunities = $opportunities->whereNotIn('stage', ['Closed Won', 'Closed Lost']);
            
            $winRate = $this->calculateWinRate($opportunities);
            $avgDealSize = $this->calculateAverageDealSize($opportunities);

            $opportunityStats = [
                'total_value' => $opportunities->sum('amount'),
                'open_opportunities' => $openOpportunities->count(),
                'open_opportunities_percentage' => $totalOpportunities > 0 
                    ? round(($openOpportunities->count() / $totalOpportunities) * 100, 1) 
                    : 0,
                'win_rate' => is_null($winRate) ? 0 : $winRate,
                'avg_deal_size' => is_null($avgDealSize) ? 0 : $avgDealSize
            ];

            Log::info('Opportunity created successfully', [
                'request_id' => $requestId,
                'opportunity_id' => $opportunity->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Opportunity created successfully',
                'opportunity' => $opportunity,
                'stats' => $opportunityStats
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create opportunity', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Only remove the cache entries if we failed before creating the opportunity
            Cache::forget($submissionHash);
            Cache::forget($requestKey);

            return response()->json([
                'message' => 'Failed to create opportunity. Please try again.'
            ], 500);
        }
    }

    /**
     * Display detailed information about a specific opportunity
     */
    public function show($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $opportunity = Opportunity::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            return view('company.CRM.show-opportunity', compact('opportunity'));
        } catch (\Exception $e) {
            Log::error('Error fetching opportunity details: ' . $e->getMessage());
            return back()->with('error', 'Opportunity not found');
        }
    }

    /**
     * Show the form for editing an existing opportunity
     */
    public function edit($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // Log additional debugging information
            Log::info('Edit Opportunity Request', [
                'id' => $id,
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            $opportunity = Opportunity::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();
    
            // Log the full opportunity details
            Log::info('Opportunity Details for Edit', [
                'id' => $opportunity->id,
                'name' => $opportunity->name,
                'account' => $opportunity->account,
                'stage' => $opportunity->stage,
                'probability' => $opportunity->probability,
                'total_value' => $opportunity->total_value,
                'close_date' => $opportunity->close_date
            ]);

            // Return a consistent JSON response with all necessary fields
            return response()->json([
                'id' => $opportunity->id,
                'name' => $opportunity->name,
                'account' => $opportunity->account,
                'stage' => $opportunity->stage,
                'probability' => $opportunity->probability,
                'total_value' => $opportunity->total_value,
                'close_date' => $opportunity->close_date,
            ]);
        } catch (\Exception $e) {
            // More comprehensive error logging
            Log::error('Error fetching opportunity for edit', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $id,
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);
            return response()->json(['error' => 'Unable to fetch opportunity', 'details' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified opportunity
     */
    public function update(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // Find the opportunity
            $opportunity = Opportunity::where('company_id', $companyId)
                ->where('id', $id)
                ->firstOrFail();

            // Validate request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'account' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'expected_revenue' => 'nullable|numeric|min:0',
                'close_date' => 'required|date',
                'stage' => 'required|in:Prospecting,Qualification,Proposal,Negotiation,Closed Won,Closed Lost',
                'probability' => 'required|numeric|min:0|max:100',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update the opportunity
            $opportunity->update([
                'name' => $request->name,
                'account' => $request->account,
                'amount' => $request->amount,
                'expected_revenue' => $request->expected_revenue,
                'close_date' => $request->close_date,
                'stage' => $request->stage,
                'probability' => $request->probability,
                'description' => $request->description
            ]);

            // Get updated opportunities for stats
            $opportunities = Opportunity::where('company_id', $companyId)->get();
            
            // Calculate updated stats
            $totalOpportunities = $opportunities->count();
            $openOpportunities = $opportunities->whereNotIn('stage', ['Closed Won', 'Closed Lost']);
            
            $stats = [
                'total_value' => $opportunities->sum('amount'),
                'open_opportunities' => $openOpportunities->count(),
                'open_opportunities_percentage' => $totalOpportunities > 0 
                    ? round(($openOpportunities->count() / $totalOpportunities) * 100, 1) 
                    : 0,
                'win_rate' => $this->calculateWinRate($opportunities),
                'avg_deal_size' => $this->calculateAverageDealSize($opportunities)
            ];

            return response()->json([
                'success' => true,
                'message' => 'Opportunity updated successfully',
                'opportunity' => $opportunity,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating opportunity', [
                'error' => $e->getMessage(),
                'opportunity_id' => $id,
                'company_id' => $companyId ?? 'Not set'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update opportunity'
            ], 500);
        }
    }

    /**
     * Update opportunity status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            $opportunity = Opportunity::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            $opportunity->update([
                'status' => $request->input('status', false),
                'stage' => $request->input('stage', $opportunity->stage)
            ]);

            return redirect()->route('company.opportunities.index')
                ->with('success', 'Opportunity status updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating opportunity status: ' . $e->getMessage());
            return back()->with('error', 'Unable to update opportunity status');
        }
    }

    /**
     * Export opportunities to CSV or Excel
     */
    public function export(Request $request)
    {
        try {
            $format = $request->input('format', 'csv');
            $companyId = Session::get('selected_company_id');

            // Get opportunities
            $opportunities = Opportunity::where('company_id', $companyId)->get();

            // Prepare the data for export
            $exportData = [];
            
            // Add headers
            $exportData[] = [
                'Name',
                'Account',
                'Stage',
                'Amount',
                'Expected Revenue',
                'Close Date',
                'Probability',
                'Created At'
            ];

            // Add data rows
            foreach ($opportunities as $opportunity) {
                $exportData[] = [
                    $opportunity->name,
                    $opportunity->account,
                    $opportunity->stage,
                    number_format($opportunity->amount, 2),
                    number_format($opportunity->expected_revenue ?? 0, 2),
                    $opportunity->close_date ? $opportunity->close_date->format('M d, Y') : 'N/A',
                    $opportunity->probability . '%',
                    $opportunity->created_at->format('M d, Y H:i')
                ];
            }

            if ($format === 'csv') {
                $output = fopen('php://temp', 'w');
                foreach ($exportData as $row) {
                    fputcsv($output, $row);
                }
                rewind($output);
                $csv = stream_get_contents($output);
                fclose($output);

                return response($csv)
                    ->header('Content-Type', 'text/csv; charset=UTF-8')
                    ->header('Content-Disposition', 'attachment; filename="opportunities.csv"');
            }

            return response()->json([
                'success' => false,
                'message' => 'Unsupported export format'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Error exporting opportunities', [
                'error' => $e->getMessage(),
                'company_id' => $companyId ?? 'Not set'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to export opportunities'
            ], 500);
        }
    }

    /**
     * Remove the specified opportunity from storage.
     */
    public function destroy($id)
    {
        try {
            $opportunity = Opportunity::where('id', $id)
                ->where('company_id', session('selected_company_id'))
                ->firstOrFail();

            $opportunity->delete();

            Log::info('Opportunity deleted successfully', [
                'opportunity_id' => $id,
                'company_id' => session('selected_company_id'),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Opportunity deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting opportunity', [
                'opportunity_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete opportunity'
            ], 500);
        }
    }

    /**
     * Export to CSV
     */
    private function exportToCSV($opportunities)
    {
        $filename = 'opportunities_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = ['Name', 'Account', 'Stage', 'Amount', 'Expected Revenue', 'Close Date', 'Probability'];

        $callback = function() use ($opportunities, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($opportunities as $opportunity) {
                fputcsv($file, [
                    $opportunity->name,
                    $opportunity->account,
                    $opportunity->stage,
                    $opportunity->amount,
                    $opportunity->expected_revenue,
                    $opportunity->close_date,
                    $opportunity->probability
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to Excel
     */
    private function exportToExcel($opportunities)
    {
        // Implement Excel export logic using a library like PhpSpreadsheet
        // This is a placeholder and would need to be implemented
        throw new \Exception('Excel export not implemented');
    }
}