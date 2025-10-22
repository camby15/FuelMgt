<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\crm_sales;
use App\Models\CrmSalesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Models\SalesTimeline; 


class CrmSalesController extends Controller
{
   

    // {
    //     try {
    //         if (!Auth::check()) {
    //             return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
    //         }
    
    //         $companyId = Session::get('selected_company_id');
    //         if (!$companyId) {
    //             return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
    //         }
    
    //         $userId = Auth::id();
    //         $query = crm_sales::where('company_id', $companyId)
    //                           ->where('user_id', $userId);
    
    //         // Apply Date Filters
    //         if ($request->has('start_date') && $request->has('end_date')) {
    //             $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    //         }
    
    //         // Apply Search Filter
    //         if ($request->has('search') && !empty($request->search)) {
    //             $query->where('deal_name', 'LIKE', '%' . $request->search . '%');
    //         }
    
    //         $sales = $query->orderBy('created_at', 'DESC')->paginate(5);
    
    //         return response()->json($sales);
    //     } catch (\Exception $e) {
    //         Log::error('Error fetching sales data', ['error' => $e->getMessage()]);
    //         return response()->json(['success' => false, 'error' => 'Internal Server Error'], 500);
    //     }
    // }

    public function index(Request $request)
{
    try {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }

        $userId = Auth::id();
        $query = crm_sales::where('company_id', $companyId)
                          ->where('user_id', $userId);

        // Apply Date Filters only if both are provided
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (!empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('deal_name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('stage', 'LIKE', '%' . $request->search . '%');
            });
        }


        // Apply Search Filter
        // if (!empty($request->search)) {
        //     $query->where('deal_name', 'LIKE', '%' . $request->search . '%');
        // }

        // Fetch results with pagination
        $sales = $query->orderBy('created_at', 'DESC')->paginate(5);

        return response()->json($sales);
    } catch (\Exception $e) {
        Log::error('Error fetching sales data', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'error' => 'Internal Server Error'], 500);
    }
}
    
    
    public function create()
    {
        return response()->json(['message' => 'Display form for creating sales']);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }
    
        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }
    
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'error' => 'Invalid user'], 401);
        }
    
        $request->validate([
            'deal_name' => 'required|string',
            "deal_company" => 'required|string',
            'deal_value' => 'required|numeric',
            'sales_manager_id' => 'nullable|integer',
            'stage' => 'required|string',
            'expected_close_date' => 'required|date',
            'probability' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string',
            'category_id' => 'required|integer',
           'sales_commission' => 'required|numeric',

        ]);

        try {
            $crmSale = crm_sales::create([
                'deal_name' => $request->deal_name,
                'deal_company' => $request->deal_company,
                'deal_value' => $request->deal_value,
                'sales_manager_id' => $request->sales_manager_id,
                'stage' => $request->stage,
                'sales_commission' => $request->sales_commission,
                'category_id' => $request->category_id,
                'expected_close_date' => $request->expected_close_date,
                'probability' => $request->probability,
                'description' => $request->description,
                'company_id' => $companyId,
                'user_id' => $userId, 
            ]);
    
            Log::info('New Sale Created', ['sale_id' => $crmSale->id, 'company_id' => $companyId, 'user_id' => $userId]);

            // Create a timeline entry for the new sale
           
              SalesTimeline::create([
                'user_id' => $userId,
                'company_id' => $companyId,
                'sales_id' => $crmSale->id,
                'timeline_action' => 'created',
                'timeline_values' => json_encode($crmSale->toArray())
            ]);

            // Log the creation of the timeline entry
            Log::info('Sales Timeline Created', ['sale_id' => $crmSale->id, 'company_id' => $companyId, 'user_id' => $userId]);
  
            return response()->json(['success' => true, 'message' => 'Sale created successfully', 'data' => $crmSale], 201);
        } catch (\Exception $e) {
            Log::error('Error creating sale', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'Failed to create sale'], 500);
        }
    }

    public function show($id)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
    }

    $companyId = Session::get('selected_company_id');
    if (!$companyId) {
        return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
    }

    $userId = Auth::id();
    if (!$userId) {
        return response()->json(['success' => false, 'error' => 'Invalid user'], 401);
    }

    $sale = crm_sales::where('id', $id)
        ->where('company_id', $companyId)
        ->where('user_id', $userId)
        ->first();

    if (!$sale) {
        return response()->json(['success' => false, 'error' => 'Sale not found or unauthorized'], 404);
    }

    // Fetch timeline entries for the sale
    $timeline = SalesTimeline::where('sales_id', $id)
        ->where('company_id', $companyId)
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'success' => true,
        'sale' => $sale,
        'timeline' => $timeline,
    ]);
}


    public function edit($id)
    {
        return response()->json(['message' => "Display edit form for sale ID $id"]);
    }
    

    public function update(Request $request, $id)
{
    try {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }

        $userId = Auth::id();
        $crmSale = crm_sales::where('id', $id)
                            ->where('company_id', $companyId)
                            ->where('user_id', $userId)
                            ->first();

        if (!$crmSale) {
            return response()->json(['success' => false, 'error' => 'Sale not found or unauthorized'], 404);
        }

        $request->validate([
            'deal_name' => 'required|string',
            'deal_value' => 'required|numeric',
            'sales_manager_id' => 'nullable|integer',
            'stage' => 'required|string',
            'expected_close_date' => 'nullable|date',
            'probability' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        // Compare and collect changes
        $changes = [];
        $fieldsToCheck = ['deal_name', 'deal_value','sales_manager_id','stage', 'expected_close_date', 'probability', 'description'];

        foreach ($fieldsToCheck as $field) {
            $newValue = $request->$field;
            $oldValue = $crmSale->$field;

            if ($newValue != $oldValue) {
                // $changes[] = "Changed '$field' from '$oldValue' to '$newValue'";
                $changes[] = "✏️ Updated '$field': '$oldValue' ➜ '$newValue'";

            }
        }

        // Update the sale
        $crmSale->update($request->only($fieldsToCheck));

        // Add to sales timeline if changes occurred
        if (!empty($changes)) {
            SalesTimeline::create([
                'user_id' => $userId,
                'company_id' => $companyId,
                'sales_id' => $crmSale->id,
                'timeline_action' => 'Updated Sale',
                'timeline_vales' => implode('; ', $changes),
            ]);
        }

        Log::info('Sale Updated', ['sale_id' => $crmSale->id, 'changes' => $changes]);

        return response()->json([
            'success' => true,
            'message' => 'Sale updated successfully',
            'data' => $crmSale
        ], 200);

    } catch (\Exception $e) {
        Log::error('Error updating sale', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'error' => 'Failed to update sale'], 500);
    }
}


    public function destroy($id)
    {
        $crmSale = crm_sales::findOrFail($id);
        $crmSale->delete(); 
        return response()->json(['message' => 'Sale deleted successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $crmSale = crm_sales::findOrFail($id);
        $crmSale->update(['status' => $request->status]);

        return response()->json(['message' => 'Sale status updated successfully']);
    }

 

    
    public function export(Request $request)
    {
        try {
            // Authentication and validation checks
            if (!Auth::check()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }
    
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }
    
            $userId = Auth::id();
            if (!$userId) {
                return response()->json(['success' => false, 'error' => 'Invalid user'], 401);
            }
    
            // Build query
            $query = crm_sales::where('company_id', $companyId)
                              ->where('user_id', $userId);
    
            // Date filters
            if ($request->filled('start_date')) {
                $query->where('created_at', '>=', Carbon::parse($request->start_date)->startOfDay());
            }
    
            if ($request->filled('end_date')) {
                $query->where('created_at', '<=', Carbon::parse($request->end_date)->endOfDay());
            }
    
            // Search filter
            if ($request->filled('search')) {
                $query->where('deal_name', 'LIKE', '%' . $request->search . '%');
            }
    
            $salesData = $query->orderBy('created_at', 'DESC')->get();
    
            if ($salesData->isEmpty()) {
                return response()->json(['success' => false, 'error' => 'No sales data found'], 404);
            }
    
            $exportFormat = strtolower($request->export_format ?? 'xlsx');
            $fileName = 'sales_export_' . now()->format('Ymd_His');
    
            // Transform data for export
            $exportData = $salesData->map(function ($item) {
                return [
                    'Deal Name' => $item->deal_name,
                    'Deal Value' => $item->deal_value,
                    'Stage' => $item->stage,
                    'Expected Close Date' => $item->expected_close_date 
                        ? Carbon::parse($item->expected_close_date)->format('Y-m-d')
                        : '',
                    'Probability' => $item->probability,
                    'Description' => Str::limit($item->description, 100), // Limit description length
                    'Created At' => $item->created_at 
                        ? Carbon::parse($item->created_at)->format('Y-m-d H:i:s')
                        : ''
                ];
            });
    
            // Generate and return export file
            $fastExcel = new FastExcel($exportData);
    
            if ($exportFormat === 'csv') {
                return $fastExcel->download("{$fileName}.csv");
            } elseif ($exportFormat === 'xlsx') {
                return $fastExcel->download("{$fileName}.xlsx");
            }
    
            return response()->json(['success' => false, 'error' => 'Invalid export format'], 400);
    
        } catch (\Exception $e) {
            Log::error("Export failed: " . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
                'company_id' => Session::get('selected_company_id')
            ]);
            return response()->json(['success' => false, 'error' => 'Export failed. Please try again.'], 500);
        }
    }
    public function getSalesCategory(Request $request)
    {
        try {
            $categories = crmSalesCategory::select('id', 'name')
                            ->orderBy('name')
                            ->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $categories,
                'message' => 'Sales categories retrieved successfully'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch sales categories: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve sales categories',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
   public function saleCategory(Request $request)
{
    try {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $companyId = Session::get('selected_company_id');
        $userId = Auth::id();

        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }

        // Get total sales count for this user/company
        $totalSales = crm_sales::where('company_id', $companyId)
                            ->where('user_id', $userId)
                            ->count();

        // Get sales grouped by category with counts
        $salesByCategory = crm_sales::select('category_id')
            ->selectRaw('COUNT(*) as count')
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->groupBy('category_id')
            ->get();

        // Get all possible categories
        $allCategories = CrmSalesCategory::all();

        $result = [];
        
        // Calculate percentage for each category
        foreach ($allCategories as $category) {
            $categorySales = $salesByCategory->firstWhere('category_id', $category->id);
            $count = $categorySales ? $categorySales->count : 0;
            $percentage = $totalSales > 0 ? round(($count / $totalSales) * 100, 2) : 0;

            $result[] = [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'sales_count' => $count,
                'percentage' => $percentage,
                'percentage_display' => $percentage . '%'
            ];
        }

        // Sort by percentage (descending)
        usort($result, function($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });

        // Add ranking
        foreach ($result as $index => &$item) {
            $item['rank'] = $index + 1;
        }

        return response()->json([
            'success' => true,
            'data' => $result,
            'total_sales' => $totalSales,
            'message' => 'Sales by category retrieved successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Error in saleCategory: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to retrieve category stats',
            'message' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}



public function getDashboardStats(Request $request)
{
    try {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $companyId = Session::get('selected_company_id');
        $userId = Auth::id();

        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }

        // Determine date range based on filter
        $dateRange = $this->getDateRange($request->period);
        
        $baseQuery = crm_sales::where('company_id', $companyId)
                            ->where('user_id', $userId);

        if ($dateRange['start'] && $dateRange['end']) {
            $baseQuery->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        // Get previous period data for comparison
        $previousPeriodData = $this->getPreviousPeriodStats($request->period, $companyId, $userId);

        // 1. Total Sales Card
        $totalSalesValue = $baseQuery->sum('deal_value');
        $salesChange = $this->calculateChange($totalSalesValue, $previousPeriodData['totalValue']);

        // 2. Conversion Rate Card
        $wonDealsCount = (clone $baseQuery)->where('stage', 'closed_won')->count();
        $totalDealsCount = $baseQuery->count();
        $conversionRate = $totalDealsCount > 0 ? ($wonDealsCount / $totalDealsCount) * 100 : 0;
        $conversionRateChange = $this->calculateChange(
            $conversionRate, 
            $previousPeriodData['totalDealsCount'] > 0 
                ? ($previousPeriodData['wonDealsCount'] / $previousPeriodData['totalDealsCount']) * 100 
                : 0
        );

        // 3. Average Deal Size Card
        $avgDealSize = $totalDealsCount > 0 ? $totalSalesValue / $totalDealsCount : 0;
        $avgDealSizeChange = $this->calculateChange(
            $avgDealSize,
            $previousPeriodData['totalDealsCount'] > 0 
                ? $previousPeriodData['totalValue'] / $previousPeriodData['totalDealsCount'] 
                : 0
        );

        // 4. Pipeline Value Card (all non-won deals)
        $pipelineValue = (clone $baseQuery)->where('stage', '!=', 'won')->sum('deal_value');
        $pipelineValueChange = $this->calculateChange($pipelineValue, $previousPeriodData['pipelineValue']);

        return response()->json([
            'success' => true,
            'data' => [
                'totalSales' => [
                    'value' => $totalSalesValue,
                    'formattedValue' => $this->formatValue($totalSalesValue),
                    'change' => $salesChange,
                ],
                'conversionRate' => [
                    'value' => $conversionRate,
                    'formattedValue' => round($conversionRate, 2) . '%',
                    'change' => $conversionRateChange,
                ],
                'avgDealSize' => [
                    'value' => $avgDealSize,
                    'formattedValue' => $this->formatValue($avgDealSize),
                    'change' => $avgDealSizeChange,
                ],
                'pipelineValue' => [
                    'value' => $pipelineValue,
                    'formattedValue' => $this->formatValue($pipelineValue),
                    'change' => $pipelineValueChange,
                ]
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error fetching dashboard stats: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to retrieve dashboard statistics',
            'message' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}

// Helper methods
protected function getDateRange($period)
{
    $today = Carbon::today();
    $startDate = null;
    $endDate = null;

    switch ($period) {
        case 'today':
            $startDate = $today->copy()->startOfDay();
            $endDate = $today->copy()->endOfDay();
            break;
        case 'yesterday':
            $startDate = $today->copy()->subDay()->startOfDay();
            $endDate = $today->copy()->subDay()->endOfDay();
            break;
        case 'last7':
            $startDate = $today->copy()->subDays(7)->startOfDay();
            $endDate = $today->copy()->endOfDay();
            break;
        case 'last28':
            $startDate = $today->copy()->subDays(28)->startOfDay();
            $endDate = $today->copy()->endOfDay();
            break;
        case 'thismonth':
            $startDate = $today->copy()->startOfMonth();
            $endDate = $today->copy()->endOfMonth();
            break;
        case 'lastmonth':
            $startDate = $today->copy()->subMonth()->startOfMonth();
            $endDate = $today->copy()->subMonth()->endOfMonth();
            break;
        case 'last3months':
            $startDate = $today->copy()->subMonths(3)->startOfMonth();
            $endDate = $today->copy()->endOfMonth();
            break;
        case 'custom':
            if (request()->has('custom_start') && request()->has('custom_end')) {
                $startDate = Carbon::parse(request('custom_start'))->startOfDay();
                $endDate = Carbon::parse(request('custom_end'))->endOfDay();
            }
            break;
        default: // All time
            break;
    }

    return ['start' => $startDate, 'end' => $endDate];
}

protected function getPreviousPeriodStats($period, $companyId, $userId)
{
    $today = Carbon::today();
    $range = $this->getComparisonDateRange($period);

    $query = crm_sales::where('company_id', $companyId)
                    ->where('user_id', $userId);

    if ($range['start'] && $range['end']) {
        $query->whereBetween('created_at', [$range['start'], $range['end']]);
    }

    $wonDealsCount = (clone $query)->where('stage', 'won')->count();
    $pipelineValue = (clone $query)->where('stage', '!=', 'won')->sum('deal_value');

    return [
        'totalValue' => $query->sum('deal_value'),
        'totalDealsCount' => $query->count(),
        'wonDealsCount' => $wonDealsCount,
        'pipelineValue' => $pipelineValue
    ];
}

protected function calculateChange($current, $previous)
{
    if ($previous == 0) {
        return ['percentage' => 0, 'trend' => 'neutral'];
    }

    $percentage = (($current - $previous) / $previous) * 100;
    return [
        'percentage' => round(abs($percentage), 2),
        'trend' => $percentage >= 0 ? 'up' : 'down'
    ];
}

protected function formatValue($value)
{
    $symbol = \App\Helpers\CurrencyHelper::symbol();
    $rates = \App\Helpers\CurrencyHelper::getRates();
    $c = strtoupper(session('currency', 'GHS'));
    $rate = $rates[$c] ?? 1;
    
    $convertedValue = $value / $rate;
    
    if ($convertedValue >= 1000000) {
        return $symbol . number_format($convertedValue / 1000000, 3) . 'M';
    } elseif ($convertedValue >= 1000) {
        return $symbol . number_format($convertedValue / 1000, 3) . 'K';
    }
    return $symbol . number_format($convertedValue, 2);
}

protected function getComparisonDateRange($period)
{
    $today = Carbon::today();
    $startDate = null;
    $endDate = null;

    switch ($period) {
        case 'today':
            // Compare with yesterday
            $startDate = $today->copy()->subDay()->startOfDay();
            $endDate = $today->copy()->subDay()->endOfDay();
            break;
        case 'yesterday':
            // Compare with day before yesterday
            $startDate = $today->copy()->subDays(2)->startOfDay();
            $endDate = $today->copy()->subDays(2)->endOfDay();
            break;
        case 'last7':
            // Previous 7 days before the current period
            $startDate = $today->copy()->subDays(14)->startOfDay();
            $endDate = $today->copy()->subDays(8)->endOfDay();
            break;
        case 'last28':
            // Previous 28 days before the current period
            $startDate = $today->copy()->subDays(56)->startOfDay();
            $endDate = $today->copy()->subDays(29)->endOfDay();
            break;
        case 'thismonth':
            // Last month
            $startDate = $today->copy()->subMonth()->startOfMonth();
            $endDate = $today->copy()->subMonth()->endOfMonth();
            break;
        case 'lastmonth':
            // Month before last month
            $startDate = $today->copy()->subMonths(2)->startOfMonth();
            $endDate = $today->copy()->subMonths(2)->endOfMonth();
            break;
        case 'last3months':
            // The 3 months before the current period
            $startDate = $today->copy()->subMonths(6)->startOfMonth();
            $endDate = $today->copy()->subMonths(4)->endOfMonth();
            break;
        case 'custom':
            // For custom, compare with same duration before the custom period
            if (request()->has('custom_start') && request()->has('custom_end')) {
                $duration = Carbon::parse(request('custom_start'))->diffInDays(Carbon::parse(request('custom_end')));
                $startDate = Carbon::parse(request('custom_start'))->subDays($duration + 1)->startOfDay();
                $endDate = Carbon::parse(request('custom_start'))->subDay()->endOfDay();
            }
            break;
        default: // For all time, compare with same period last year
            $startDate = $today->copy()->subYear()->startOfYear();
            $endDate = $today->copy()->subYear()->endOfYear();
            break;
    }

    return [
        'start' => $startDate,
        'end' => $endDate
    ];
}
}
