<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
{
    /**
     * Get performance statistics
     */
    public function getStats(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id', 1); // Default to company ID 1 for testing
            Log::info('PerformanceController@getStats: Company ID from session: ' . $companyId);

            // For testing purposes, if no company is selected, use company ID 1
            if (!$companyId) {
                $companyId = 1;
                Log::info('PerformanceController@getStats: No company ID in session, defaulting to 1');
            }

            // Initialize stats with zeros
            $stats = [
                'pending_appraisals' => 0,
                'average_performance' => 0,
                'self_assessments' => 0,
                'performance_trends' => 0,
                'total_employees' => 0,
                'completed_reviews' => 0,
                'by_rating' => []
            ];

            try {
                // Get performance counts safely
                $stats['pending_appraisals'] = Performance::where('company_id', $companyId)
                    ->where('status', 'pending')->count();
                
                $stats['completed_reviews'] = Performance::where('company_id', $companyId)
                    ->where('status', 'completed')->count();

                // Average performance score
                $avgScore = Performance::where('company_id', $companyId)
                    ->where('status', 'completed')
                    ->avg('overall_score');
                $stats['average_performance'] = round($avgScore ?: 0, 1);

                // Self assessments count
                $stats['self_assessments'] = Performance::where('company_id', $companyId)
                    ->where('type', 'self')->count();

                // Total employees
                $stats['total_employees'] = Employee::where('company_id', $companyId)->count();

                // Performance trends (last 6 months)
                $sixMonthsAgo = now()->subMonths(6);
                $stats['performance_trends'] = Performance::where('company_id', $companyId)
                    ->where('created_at', '>=', $sixMonthsAgo)
                    ->where('status', 'completed')
                    ->count();

                // Performance by rating
                $stats['by_rating'] = Performance::where('company_id', $companyId)
                    ->where('status', 'completed')
                    ->select('overall_rating', DB::raw('count(*) as count'))
                    ->groupBy('overall_rating')
                    ->get()
                    ->pluck('count', 'overall_rating');
            } catch (\Exception $e) {
                Log::error('Error getting performance stats: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Performance statistics retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch performance stats: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch performance statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get performance reviews list
     */
    public function index(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id', 1); // Default to company ID 1 for testing
            Log::info('PerformanceController@index: Company ID from session: ' . $companyId);

            // For testing purposes, if no company is selected, use company ID 1
            if (!$companyId) {
                $companyId = 1;
                Log::info('PerformanceController@index: No company ID in session, defaulting to 1');
            }

            $query = Performance::with(['employee.personalInfo', 'employee.employmentInfo', 'reviewer'])
                ->where('company_id', $companyId);

            // Apply filters
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }

            if ($request->has('search') && $request->search) {
                $query->whereHas('employee.personalInfo', function($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search . '%')
                      ->orWhere('last_name', 'like', '%' . $request->search . '%');
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $performances = $query->orderBy('created_at', 'desc')->paginate($perPage);

            Log::info('PerformanceController@index: Found ' . $performances->total() . ' performance reviews for company ID ' . $companyId);

            return response()->json([
                'success' => true,
                'data' => $performances->items(),
                'pagination' => [
                    'current_page' => $performances->currentPage(),
                    'last_page' => $performances->lastPage(),
                    'per_page' => $performances->perPage(),
                    'total' => $performances->total()
                ],
                'message' => 'Performance reviews retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch performance reviews: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch performance reviews.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new performance review
     */
    public function store(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id', 1); // Default to company ID 1 for testing

            // For testing purposes, if no company is selected, use company ID 1
            if (!$companyId) {
                $companyId = 1;
            }

            $validator = \Validator::make($request->all(), [
                'employee_id' => 'required|exists:employees,id',
                'type' => 'required|string|in:self,manager,peer,360',
                'review_period_start' => 'required|date',
                'review_period_end' => 'required|date|after:review_period_start',
                'goals' => 'required|string',
                'achievements' => 'required|string',
                'areas_for_improvement' => 'required|string',
                'overall_score' => 'required|numeric|min:1|max:5',
                'overall_rating' => 'required|string|in:excellent,good,satisfactory,needs_improvement,poor',
                'status' => 'required|string|in:draft,pending,completed,cancelled',
                'kpis' => 'nullable|array',
                'kpis.*.name' => 'required|string',
                'kpis.*.weight' => 'required|integer|min:0|max:100',
                'kpis.*.enabled' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $performance = Performance::create([
                'company_id' => $companyId,
                'employee_id' => $request->employee_id,
                'type' => $request->type,
                'review_period_start' => $request->review_period_start,
                'review_period_end' => $request->review_period_end,
                'goals' => $request->goals,
                'achievements' => $request->achievements,
                'areas_for_improvement' => $request->areas_for_improvement,
                'overall_score' => $request->overall_score,
                'overall_rating' => $request->overall_rating,
                'status' => $request->status,
                'reviewer_id' => Auth::id(),
                'notes' => $request->notes,
                'kpis' => $request->kpis
            ]);

            return response()->json([
                'success' => true,
                'data' => $performance,
                'message' => 'Performance review created successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to create performance review: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create performance review.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific performance review
     */
    public function show($id)
    {
        try {
            $companyId = Session::get('selected_company_id', 1); // Default to company ID 1 for testing

            // For testing purposes, if no company is selected, use company ID 1
            if (!$companyId) {
                $companyId = 1;
            }

            $performance = Performance::with(['employee.personalInfo', 'employee.employmentInfo', 'reviewer'])
                ->where('company_id', $companyId)
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $performance,
                'message' => 'Performance review retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch performance review: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Performance review not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update a performance review
     */
    public function update(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id', 1); // Default to company ID 1 for testing

            // For testing purposes, if no company is selected, use company ID 1
            if (!$companyId) {
                $companyId = 1;
            }

            $performance = Performance::where('company_id', $companyId)->findOrFail($id);

            $validator = \Validator::make($request->all(), [
                'type' => 'sometimes|required|string|in:self,manager,peer,360',
                'goals' => 'sometimes|required|string',
                'achievements' => 'sometimes|required|string',
                'areas_for_improvement' => 'sometimes|required|string',
                'overall_score' => 'sometimes|required|numeric|min:1|max:5',
                'overall_rating' => 'sometimes|required|string|in:excellent,good,satisfactory,needs_improvement,poor',
                'status' => 'sometimes|required|string|in:draft,pending,completed,cancelled',
                'notes' => 'nullable|string',
                'review_period_start' => 'nullable|date',
                'review_period_end' => 'nullable|date|after_or_equal:review_period_start',
                'reviewer_id' => 'nullable|integer|exists:users,id',
                'kpis' => 'nullable|array',
                'kpis.*.name' => 'required|string',
                'kpis.*.weight' => 'required|integer|min:0|max:100',
                'kpis.*.enabled' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $performance->update($request->only([
                'type', 'goals', 'achievements', 'areas_for_improvement', 
                'overall_score', 'overall_rating', 'status', 'notes',
                'review_period_start', 'review_period_end', 'reviewer_id', 'kpis'
            ]));

            return response()->json([
                'success' => true,
                'data' => $performance,
                'message' => 'Performance review updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update performance review: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update performance review.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search employees for Select2 dropdown
     */
    public function searchEmployees(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            if (!$companyId) {
                $companyId = 1;
            }

            $search = $request->get('q', '');
            $page = $request->get('page', 1);
            $perPage = 10;

            $query = Employee::with(['personalInfo', 'employmentInfo'])
                ->where('company_id', $companyId)
                ->where('status', 'active');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->whereHas('personalInfo', function($subQ) use ($search) {
                        $subQ->where('first_name', 'like', '%' . $search . '%')
                             ->orWhere('last_name', 'like', '%' . $search . '%');
                    })->orWhere('staff_id', 'like', '%' . $search . '%');
                });
            }

            $employees = $query->paginate($perPage, ['*'], 'page', $page);

            $results = $employees->map(function($employee) {
                $personalInfo = $employee->personalInfo;
                $employmentInfo = $employee->employmentInfo;
                $fullName = $personalInfo ? 
                    trim($personalInfo->first_name . ' ' . $personalInfo->last_name) : 
                    'No Name';
                
                return [
                    'id' => $employee->id,
                    'text' => $fullName . ' (' . $employee->staff_id . ')',
                    'staff_id' => $employee->staff_id,
                    'full_name' => $fullName,
                    'department' => $employmentInfo ? $employmentInfo->department : null
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $results,
                'results' => $results,
                'pagination' => [
                    'more' => $employees->hasMorePages()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to search employees: " . $e->getMessage());
            return response()->json([
                'results' => [],
                'pagination' => ['more' => false]
            ]);
        }
    }

    /**
     * Delete a performance review
     */
    public function destroy($id)
    {
        try {
            $companyId = Session::get('selected_company_id', 1); // Default to company ID 1 for testing

            // For testing purposes, if no company is selected, use company ID 1
            if (!$companyId) {
                $companyId = 1;
            }

            $performance = Performance::where('company_id', $companyId)->findOrFail($id);
            $performance->delete();

            return response()->json([
                'success' => true,
                'message' => 'Performance review deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to delete performance review: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete performance review.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
