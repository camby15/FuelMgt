<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyProgram;
use App\Models\CustomerTier;
use App\Models\Reward;
use App\Models\Redemption;
use App\Models\Referral;
use App\Models\CustomerPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;

class LoyaltyProgramController extends Controller
{
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
            $query = LoyaltyProgram::withCount(['customerPoints as members', 'tiers', 'rewards'])
                ->where('company_id', $companyId)
                ->where('user_id', $userId);

            // Apply filters
            if ($request->filled('search')) {
                $query->where('name', 'like', '%'.$request->search.'%');
            }

            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            if ($request->filled('type')) {
                $query->where('program_type', $request->type);
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('start_date', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            }

            $programs = $query->orderBy('created_at', 'DESC')->paginate($request->per_page ?? 5);

            return response()->json([
                'success' => true, 
                'data' => $programs,
                'stats' => [
                    'total_programs' => $programs->total(),
                    'active_programs' => LoyaltyProgram::where('company_id', $companyId)
                        ->where('user_id', $userId)
                        ->where('status', 'active')
                        ->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch loyalty programs: '.$e->getMessage());
            return response()->json([
                'success' => false, 
                'error' => 'Failed to fetch programs',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
// public function store(Request $request)
// {
//     try {
//         $companyId = Session::get('selected_company_id');
//         if (!$companyId) {
//             return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
//         }

//         // Try to get from either 'customer_category' or 'customer_category[]'
//         $rawCategories = $request->input('customer_category') ?? $request->all()['customer_category'] ?? [];

     
//         // Ensure it's always an array
//         $customerCategories = is_array($rawCategories) ? $rawCategories : [$rawCategories];

//         $validator = Validator::make(array_merge($request->all(), [
//             'customer_category' => $customerCategories
//         ]), [
//             'name' => 'required|string|max:255',
//             'program_type' => 'required|in:points,tier,hybrid',
//             'customer_category' => 'required|array|min:1',
//               'customer_category.*' => 'in:standard,VIP,HVC',
//             'description' => 'nullable|string',
//             'start_date' => 'required|date|after_or_equal:today',
//             'end_date' => 'nullable|date|after:start_date',
//             'points' => 'nullable|numeric|min:0',
//             'currency_value' => 'required|numeric|min:0.01',
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'success' => false,
//                 'errors' => $validator->errors()
//             ], 422);
//         }

//         dd($request->input('customer_category'));

//           $customerType = $request->input('customer_category');

//         $program = LoyaltyProgram::create([
//             'company_id' => $companyId,
//             'user_id' => Auth::id(),
//             'name' => $request->name,
//             'program_type' => $request->program_type,
//             'customer_type' => $customerType,
//             'description' => $request->description,
//             'start_date' => $request->start_date,
//             'end_date' => $request->end_date,
//             'points' => $request->points ?? 0,
//             'currency_value' => $request->currency_value,
//             'status' => $request->status ?? 'active',
//             'is_active' => ($request->status ?? 'active') === 'active'
//         ]);

//         return response()->json([
//             'success' => true,
//             'data' => $program,
//             'message' => 'Loyalty program created successfully'
//         ], 201);

//     } catch (\Exception $e) {
//         Log::error('Failed to create loyalty program: '.$e->getMessage());
//         return response()->json([
//             'success' => false,
//             'error' => 'Failed to create program',
//             'details' => config('app.debug') ? $e->getMessage() : null,
//             'input_data' => [
//                 'received_categories' => $request->input('customer_category') ?? $request->input('customer_category[]'),
//                 'processed_for_db' => $customerCategories ?? null
//             ]
//         ], 500);
//     }
// }



public function store(Request $request)
{
    try {
        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }

        // Get customer_category (ensure it's always an array)
        $rawCategories = $request->input('customer_category') ?? [];
        $customerCategories = is_array($rawCategories) ? $rawCategories : [$rawCategories];

        // Validate the request
        $validator = Validator::make(array_merge($request->all(), [
            'customer_category' => $customerCategories
        ]), [
            'name' => 'required|string|max:255',
            'program_type' => 'required|in:points,tier,hybrid',
            'customer_category' => 'required|array|min:1',
            'customer_category.*' => 'in:standard,VIP,HVC',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'points' => 'nullable|numeric|min:0',
            'currency_value' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create and save the program
        $program = LoyaltyProgram::create([
            'company_id' => $companyId,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'program_type' => $request->program_type,
            'customer_type' => $customerCategories, // casted to JSON in model
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'points' => $request->points ?? 0,
            'currency_value' => $request->currency_value,
            'status' => $request->status ?? 'active',
            'is_active' => ($request->status ?? 'active') === 'active'
        ]);

        return response()->json([
            'success' => true,
            'data' => $program,
            'message' => 'Loyalty program created successfully'
        ], 201);

    } catch (\Exception $e) {
        Log::error('Failed to create loyalty program: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'error' => 'Failed to create program',
            'details' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}



    public function show($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }
    
            $program = LoyaltyProgram::with(['tiers', 'rewards'])
                ->where('company_id', $companyId)
                ->where('user_id', Auth::id())
                ->findOrFail($id);
    
            // Calculate stats with proper relationships
            $stats = [
                'total_members' => CustomerPoint::where('loyalty_program_id', $program->id)
                    ->where('company_id', $companyId)
                    ->count(),
                    
                'active_members' => CustomerPoint::where('loyalty_program_id', $program->id)
                    ->where('company_id', $companyId)
                    ->where('points_balance', '>', 0)
                    ->count(),
                    
                'points_issued' => CustomerPoint::where('loyalty_program_id', $program->id)
                    ->where('company_id', $companyId)
                    ->sum('points_earned'),
                    
                'points_redeemed' => CustomerPoint::where('loyalty_program_id', $program->id)
                    ->where('company_id', $companyId)
                    ->sum('points_redeemed'),
                    
                'redemption_rate' => CustomerPoint::where('loyalty_program_id', $program->id)
                    ->where('company_id', $companyId)
                    ->sum('points_earned') > 0 ? 
                    round((CustomerPoint::where('loyalty_program_id', $program->id)
                        ->where('company_id', $companyId)
                        ->sum('points_redeemed') / 
                        CustomerPoint::where('loyalty_program_id', $program->id)
                        ->where('company_id', $companyId)
                        ->sum('points_earned')) * 100, 2) . '%' : '0%'
            ];
    
            return response()->json([
                'success' => true,
                'data' => $program,
                'stats' => $stats
            ]);
    
        } catch (\Exception $e) {
            Log::error('Failed to fetch loyalty program: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Program not found',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 404);
        }
    }

// public function update(Request $request, $id)
//     {
//         try {
//             $companyId = Session::get('selected_company_id');
//             if (!$companyId) {
//                 return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
//             }

//             $program = LoyaltyProgram::where('company_id', $companyId)
//                 ->where('user_id', Auth::id())
//                 ->findOrFail($id);

//             $validator = Validator::make($request->all(), [
//                 'name' => 'sometimes|required|string|max:255',
//                 'program_type' => 'sometimes|required|in:points,tier,hybrid',
//                 'description' => 'nullable|string',
//                 'start_date' => 'sometimes|required|date',
//                 'end_date' => 'nullable|date|after:start_date',
//                 'currency_ratio' => 'sometimes|required|numeric|min:0.01',
//                 'status' => 'sometimes|required|in:active,inactive,draft'
//             ]);

//             if ($validator->fails()) {
//                 return response()->json([
//                     'success' => false,
//                     'errors' => $validator->errors()
//                 ], 422);
//             }
           
            
//             $program->update([
//                 'name' => $request->name ?? $program->name,
//                 'program_type' => $request->program_type ?? $program->program_type,
//                 'description' => $request->description ?? $program->description,
//                 'start_date' => $request->start_date ?? $program->start_date,
//                 'end_date' => $request->end_date ?? $program->end_date,
//                 'currency_ratio' => $request->currency_ratio ?? $program->currency_ratio,
//                 'status' => $request->status ?? $program->status,
//                 'is_active' => isset($request->status) ? $request->status === 'active' : $program->is_active
//             ]);

//             return response()->json([
//                 'success' => true,
//                 'data' => $program,
//                 'message' => 'Loyalty program updated successfully'
//             ]);

//         } catch (\Exception $e) {
//             Log::error('Failed to update loyalty program: '.$e->getMessage());
//             return response()->json([
//                 'success' => false,
//                 'error' => 'Failed to update program',
//                 'details' => config('app.debug') ? $e->getMessage() : null
//             ], 500);
//         }
//     }

    

// public function update(Request $request, $id)
// {
//     try {
//         $companyId = Session::get('selected_company_id');
//         if (!$companyId) {
//             return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
//         }

//         $program = LoyaltyProgram::where('company_id', $companyId)
//             ->where('user_id', Auth::id())
//             ->findOrFail($id);

//         // Get customer_category (ensure it's always an array)
//         $rawCategories = $request->input('customer_category') ?? []; // Changed from edit_customers_category
//         $customerCategories = is_array($rawCategories) ? $rawCategories : [$rawCategories];

//         $validator = Validator::make(array_merge($request->all(), [
//             'customer_category' => $customerCategories // Changed from edit_customers_category
//         ]), [
//             'name' => 'required|string|max:255',
//             'program_type' => 'required|in:points,tier,hybrid',
//             'customer_category' => 'required|array|min:1', // Changed from edit_customers_category
//             'customer_category.*' => 'in:standard,VIP,HVC', // Changed from edit_customers_category.*
//             'description' => 'nullable|string',
//             'start_date' => 'required|date',
//             'end_date' => 'nullable|date|after:start_date',
//             'edit_points' => 'required|numeric|min:1', // Keep as edit_points to match frontend
//             'edit_currency_value' => 'required|numeric|min:0.01', // Keep as edit_currency_value
//             'status' => 'required|in:active,inactive'
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'success' => false,
//                 'errors' => $validator->errors()
//             ], 422);
//         }
        
//         $program->update([
//             'name' => $request->name,
//             'program_type' => $request->program_type,
//             'customer_type' => $customerCategories,
//             'description' => $request->description,
//             'start_date' => $request->start_date,
//             'end_date' => $request->end_date,
//             'points' => $request->edit_points, // Map from edit_points to points
//             'currency_value' => $request->edit_currency_value, // Map from edit_currency_value
//             'status' => $request->status,
//             'is_active' => $request->status === 'active'
//         ]);

//         return response()->json([
//             'success' => true,
//             'data' => $program,
//             'message' => 'Loyalty program updated successfully'
//         ]);

//     } catch (\Exception $e) {
//         Log::error('Failed to update loyalty program: '.$e->getMessage());
//         return response()->json([
//             'success' => false,
//             'error' => 'Failed to update program',
//             'details' => config('app.debug') ? $e->getMessage() : null
//         ], 500);
//     }
// }



public function update(Request $request, $id)
{
    try {
        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }

        $program = LoyaltyProgram::where('company_id', $companyId)
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Get customer categories (ensure array)
        $customerCategories = (array)$request->input('customer_category', []);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'program_type' => 'required|in:points,tier,hybrid',
            'customer_category' => 'required|array|min:1',
            'customer_category.*' => 'required|in:standard,VIP,HVC',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'edit_points' => 'required|numeric|min:1',
            'edit_currency_value' => 'required|numeric|min:0.01',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $program->update([
            'name' => $request->name,
            'program_type' => $request->program_type,
            'customer_type' => $customerCategories,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'points' => $request->edit_points,
            'currency_value' => $request->edit_currency_value,
            'status' => $request->status,
            'is_active' => $request->status === 'active'
        ]);

        return response()->json([
            'success' => true,
            'data' => $program,
            'message' => 'Loyalty program updated successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to update loyalty program: '.$e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to update program',
            'details' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}




public function destroy($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $program = LoyaltyProgram::where('company_id', $companyId)
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            // Soft delete the program
            $program->delete();

            return response()->json([
                'success' => true,
                'message' => 'Loyalty program deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete loyalty program: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete program',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    


    
    public function stats(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Company session expired'
                ], 401);
            }
    
            // Default zero values
            $stats = [
                'active_members' => 0,
                'points_redeemed' => 0,
                'active_programs' => 0,
                'clv_increase' => 0
            ];
    
            // Only query if data exists
            if (CustomerPoint::where('company_id', $companyId)->exists()) {
                $stats['active_members'] = CustomerPoint::where('company_id', $companyId)
                    ->where('points_balance', '>', 0)
                    ->count();
                
                $stats['points_redeemed'] = CustomerPoint::where('company_id', $companyId)
                    ->sum('points_redeemed');
            }
    
            $stats['active_programs'] = LoyaltyProgram::where('company_id', $companyId)
                ->where('status', 'active')
                ->count();
    
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Stats retrieved successfully'
            ]);
    
        } catch (\Exception $e) {
            Log::error("Stats Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load statistics',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    public function segmentation()
{
    try {
        $companyId = Session::get('selected_company_id');
        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }

        // Initialize with zero values
        $segmentation = [
            'platinum' => 0,
            'active_redeemers' => 0,
            'at_risk' => 0
        ];

        // Only query if data exists
        if (CustomerPoint::where('company_id', $companyId)->exists()) {
            $segmentation = [
                'platinum' => CustomerPoint::where('company_id', $companyId)
                    ->where('points_balance', '>', 1000)
                    ->count(),
                'active_redeemers' => CustomerPoint::where('company_id', $companyId)
                    ->where('points_redeemed', '>', 0)
                    ->count(),
                'at_risk' => CustomerPoint::where('company_id', $companyId)
                    ->where('last_activity', '<', now()->subDays(90))
                    ->count()
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $segmentation
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to fetch customer segmentation: '.$e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to fetch segmentation',
            'details' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
 }



 public function importCustomers(Request $request)
{
    DB::beginTransaction();
    try {
        $companyId = Session::get('selected_company_id');
        $userId = Auth::id();
        
        if (!$companyId) {
            return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt',
            'program_id' => 'required|exists:loyalty_progr
            
            ms,id,company_id,'.$companyId
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $import = new CustomersImport($companyId, $userId, $request->program_id);
        Excel::import($import, $request->file('file'));

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Customers imported successfully',
            'stats' => [
                'total' => $import->getRowCount(),
                'successful' => $import->getSuccessCount(),
                'failed' => $import->getFailedCount(),
               'file' => [
    'name' => $request->file('file')->getClientOriginalName(),
    'content' => file_get_contents($request->file('file')->getRealPath())
]
                
            ],
            'failures' => $import->getFailures()
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Failed to import customers: '.$e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to import customers',
            'details' => $e->getMessage() // Always show error in development
        ], 500);
    }
}

  

    public function generateReport(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'error' => 'Company session expired'], 403);
            }

            $programId = $request->program_id;
            $type = $request->type ?? 'summary';

            // Generate report based on type
            switch ($type) {
                case 'detailed':
                    $report = $this->generateDetailedReport($companyId, $programId);
                    break;
                case 'redemptions':
                    $report = $this->generateRedemptionsReport($companyId, $programId);
                    break;
                default:
                    $report = $this->generateSummaryReport($companyId, $programId);
            }

            return response()->json([
                'success' => true,
                'data' => $report
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to generate report: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate report',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    protected function generateSummaryReport($companyId, $programId = null)
    {
        $query = LoyaltyProgram::where('company_id', $companyId);

        if ($programId) {
            $query->where('id', $programId);
        }

        $programs = $query->withCount(['customerPoints as members', 'rewards', 'redemptions'])
            ->get();

        return [
            'total_programs' => $programs->count(),
            'active_programs' => $programs->where('status', 'active')->count(),
            'total_members' => $programs->sum('members'),
            'total_rewards' => $programs->sum('rewards_count'),
            'total_redemptions' => $programs->sum('redemptions_count')
        ];
    }

    protected function generateDetailedReport($companyId, $programId = null)
    {
        // Implement detailed report logic
        // This would include more granular data about each program
        return [];
    }

    protected function generateRedemptionsReport($companyId, $programId = null)
    {
        // Implement redemptions report logic
        // This would focus on redemption rates, popular rewards, etc.
        return [];
    }
}