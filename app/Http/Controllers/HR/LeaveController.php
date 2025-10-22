<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Get all leaves for the company
     */
    public function index(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $query = Leave::with(['employee.personalInfo', 'approver', 'rejector'])
                ->where('company_id', $companyId)
                ->whereNull('deleted_at');

            // Apply filters
            if ($request->has('status') && $request->status !== 'all') {
                $query->byStatus($request->status);
            }

            if ($request->has('type') && $request->type !== 'all') {
                $query->byType($request->type);
            }

            if ($request->has('date_from') && $request->has('date_to')) {
                $query->byDateRange($request->date_from, $request->date_to);
            }

            if ($request->has('employee_id') && $request->employee_id) {
                $query->where('employee_id', $request->employee_id);
            }

            // Search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->whereHas('employee.personalInfo', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%");
                });
            }

            $leaves = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $leaves,
                'message' => 'Leaves retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch leaves',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new leave request
     */
    public function store(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|exists:employees,id',
                'leave_type' => 'required|in:annual,sick,personal,maternity,paternity,emergency,bereavement',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check for overlapping leaves
            $overlappingLeave = Leave::where('company_id', $companyId)
                ->where('employee_id', $request->employee_id)
                ->where('status', '!=', 'rejected')
                ->where('status', '!=', 'cancelled')
                ->where(function($q) use ($request) {
                    $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function($subQ) use ($request) {
                          $subQ->where('start_date', '<=', $request->start_date)
                               ->where('end_date', '>=', $request->end_date);
                      });
                })
                ->first();

            if ($overlappingLeave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request overlaps with existing approved or pending leave.',
                    'overlapping_leave' => $overlappingLeave
                ], 409);
            }

            // Calculate total days
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $totalDays = $startDate->diffInDays($endDate) + 1;

            $leave = Leave::create([
                'company_id' => $companyId,
                'employee_id' => $request->employee_id,
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $totalDays,
                'reason' => $request->reason,
                'status' => Leave::STATUS_PENDING,
                'created_by' => Auth::id()
            ]);

            $leave->load(['employee.personalInfo', 'creator']);

            return response()->json([
                'success' => true,
                'data' => $leave,
                'message' => 'Leave request submitted successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to create leave request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a leave request
     */
    public function update(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $leave = Leave::where('company_id', $companyId)->findOrFail($id);

            // Only allow updates for pending leaves
            if ($leave->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending leave requests can be updated.'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'leave_type' => 'sometimes|in:annual,sick,personal,maternity,paternity,emergency,bereavement',
                'start_date' => 'sometimes|date|after_or_equal:today',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
                'reason' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->only(['leave_type', 'start_date', 'end_date', 'reason']);
            $updateData['updated_by'] = Auth::id();

            // Recalculate total days if dates changed
            if ($request->has('start_date') || $request->has('end_date')) {
                $startDate = Carbon::parse($request->start_date ?? $leave->start_date);
                $endDate = Carbon::parse($request->end_date ?? $leave->end_date);
                $updateData['total_days'] = $startDate->diffInDays($endDate) + 1;
            }

            $leave->update($updateData);
            $leave->load(['employee.personalInfo', 'creator', 'updater']);

            return response()->json([
                'success' => true,
                'data' => $leave,
                'message' => 'Leave request updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update leave request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a leave request
     */
    public function approve(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $leave = Leave::where('company_id', $companyId)->findOrFail($id);

            if ($leave->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending leave requests can be approved.'
                ], 400);
            }

            $leave->update([
                'status' => Leave::STATUS_APPROVED,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'updated_by' => Auth::id()
            ]);

            $leave->load(['employee.personalInfo', 'approver']);

            return response()->json([
                'success' => true,
                'data' => $leave,
                'message' => 'Leave request approved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to approve leave request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a leave request
     */
    public function reject(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $leave = Leave::where('company_id', $companyId)->findOrFail($id);

            if ($leave->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending leave requests can be rejected.'
                ], 400);
            }

            $leave->update([
                'status' => Leave::STATUS_REJECTED,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason,
                'updated_by' => Auth::id()
            ]);

            $leave->load(['employee.personalInfo', 'rejector']);

            return response()->json([
                'success' => true,
                'data' => $leave,
                'message' => 'Leave request rejected successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to reject leave request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a leave request
     */
    public function cancel(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $leave = Leave::where('company_id', $companyId)->findOrFail($id);

            if ($leave->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request is already cancelled.'
                ], 400);
            }

            if ($leave->status === Leave::STATUS_REJECTED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel a rejected leave request.'
                ], 400);
            }

            $leave->update([
                'status' => Leave::STATUS_CANCELLED,
                'updated_by' => Auth::id()
            ]);

            $leave->load(['employee.personalInfo', 'updater']);

            return response()->json([
                'success' => true,
                'data' => $leave,
                'message' => 'Leave request cancelled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to cancel leave request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get leave statistics
     */
    public function getStats(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // Log the request for debugging
            Log::info('Leave Stats Request', [
                'company_id' => $companyId,
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            // Test if we can connect to the database
            try {
                $testQuery = Leave::count();
                Log::info('Database connection test successful', ['total_leaves' => $testQuery]);
            } catch (\Exception $e) {
                Log::error('Database connection failed: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Database connection error.'
                ], 500);
            }
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            // Initialize stats with zeros
            $stats = [
                'pending' => 0,
                'approved' => 0,
                'rejected' => 0,
                'cancelled' => 0,
                'total' => 0,
                'this_month' => 0,
                'team_on_leave' => 0,
                'total_employees' => 0,
                'leave_balance' => 15,
                'by_type' => []
            ];

            try {
                // Get leave counts safely
                $stats['pending'] = Leave::where('company_id', $companyId)->where('status', 'pending')->count();
                $stats['approved'] = Leave::where('company_id', $companyId)->where('status', 'approved')->count();
                $stats['rejected'] = Leave::where('company_id', $companyId)->where('status', 'rejected')->count();
                $stats['cancelled'] = Leave::where('company_id', $companyId)->where('status', 'cancelled')->count();
                $stats['total'] = Leave::where('company_id', $companyId)->count();
            } catch (\Exception $e) {
                Log::error('Error getting leave counts: ' . $e->getMessage());
                // Keep zeros if there's an error
            }

            try {
                // Monthly stats
                $currentMonth = now()->format('Y-m');
                $stats['this_month'] = Leave::where('company_id', $companyId)
                    ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$currentMonth])
                    ->count();

                // Team on leave (currently approved leaves)
                $stats['team_on_leave'] = Leave::where('company_id', $companyId)
                    ->where('status', 'approved')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->count();

                // Total employees in company
                $stats['total_employees'] = Employee::where('company_id', $companyId)->count();

                // Leave type breakdown
                $stats['by_type'] = Leave::where('company_id', $companyId)
                    ->select('leave_type', DB::raw('count(*) as count'))
                    ->groupBy('leave_type')
                    ->get()
                    ->pluck('count', 'leave_type');
            } catch (\Exception $e) {
                Log::error('Error getting additional stats: ' . $e->getMessage());
                // Keep default values if there's an error
            }

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Leave statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch leave statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get leave calendar events with pagination
     */
    public function getCalendarEvents(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // Debug logging
            Log::info('Calendar Events Request', [
                'company_id' => $companyId,
                'start' => $request->input('start'),
                'end' => $request->input('end'),
                'user_id' => Auth::id()
            ]);
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $start = $request->input('start', now()->startOfMonth()->format('Y-m-d'));
            $end = $request->input('end', now()->endOfMonth()->format('Y-m-d'));
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);

            // Get all leaves for calendar events (no pagination for calendar)
            $leaves = Leave::with(['employee.personalInfo'])
                ->where('company_id', $companyId)
                ->where('status', '!=', 'cancelled')
                ->where('status', '!=', 'rejected')
                ->whereBetween('start_date', [$start, $end])
                ->get();

            $events = $leaves->map(function($leave) {
                $totalDays = $leave->start_date->diffInDays($leave->end_date) + 1;
                $employeeName = $leave->employee && $leave->employee->personalInfo ? 
                    $leave->employee->personalInfo->first_name . ' ' . $leave->employee->personalInfo->last_name : 
                    'Unknown Employee';
                
                return [
                    'id' => $leave->id,
                    'title' => $employeeName . ' - ' . ucfirst($leave->leave_type),
                    'start' => $leave->start_date->format('Y-m-d'),
                    'end' => $leave->end_date->addDay()->format('Y-m-d'), // FullCalendar end date is exclusive
                    'className' => $this->getEventClassName($leave),
                    'extendedProps' => [
                        'leave_type' => $leave->leave_type,
                        'status' => $leave->status,
                        'employee_name' => $employeeName,
                        'total_days' => $totalDays,
                        'reason' => $leave->reason
                    ]
                ];
            });

            // Get paginated leaves for table
            $paginatedLeaves = Leave::with(['employee.personalInfo'])
                ->where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            $leaveData = $paginatedLeaves->map(function($leave) {
                $employeeName = $leave->employee && $leave->employee->personalInfo ? 
                    $leave->employee->personalInfo->first_name . ' ' . $leave->employee->personalInfo->last_name : 
                    'Unknown Employee';
                
                return [
                    'id' => $leave->id,
                    'employee_name' => $employeeName,
                    'leave_type' => $leave->leave_type,
                    'start_date' => $leave->start_date->format('Y-m-d'),
                    'end_date' => $leave->end_date->format('Y-m-d'),
                    'status' => $leave->status,
                    'reason' => $leave->reason,
                    'created_at' => $leave->created_at->format('Y-m-d H:i:s')
                ];
            });

            return response()->json([
                'success' => true,
                'events' => $events,
                'leaveData' => $leaveData,
                'pagination' => [
                    'current_page' => $paginatedLeaves->currentPage(),
                    'last_page' => $paginatedLeaves->lastPage(),
                    'per_page' => $paginatedLeaves->perPage(),
                    'total' => $paginatedLeaves->total(),
                    'from' => $paginatedLeaves->firstItem(),
                    'to' => $paginatedLeaves->lastItem(),
                    'has_more_pages' => $paginatedLeaves->hasMorePages()
                ],
                'message' => 'Calendar events and leave data retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch calendar events',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get event CSS class name based on leave type and status
     */
    private function getEventClassName($leave)
    {
        $typeClasses = [
            'annual' => 'bg-soft-primary text-primary',
            'sick' => 'bg-soft-danger text-danger',
            'personal' => 'bg-soft-info text-info',
            'maternity' => 'bg-soft-success text-success',
            'paternity' => 'bg-soft-warning text-warning',
            'emergency' => 'bg-soft-secondary text-secondary',
            'bereavement' => 'bg-soft-dark text-dark'
        ];

        $statusClasses = [
            'pending' => 'border-warning',
            'approved' => 'border-success',
            'rejected' => 'border-danger',
            'cancelled' => 'border-secondary'
        ];

        $typeClass = $typeClasses[$leave->leave_type] ?? 'bg-soft-secondary text-secondary';
        $statusClass = $statusClasses[$leave->status] ?? 'border-secondary';

        return $typeClass . ' ' . $statusClass;
    }

    /**
     * Delete a leave request (soft delete)
     */
    public function destroy($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $leave = Leave::where('company_id', $companyId)->findOrFail($id);

            // Only allow deletion of pending or cancelled leaves
            if (!in_array($leave->status, [Leave::STATUS_PENDING, Leave::STATUS_CANCELLED])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending or cancelled leave requests can be deleted.'
                ], 400);
            }

            $leave->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leave request deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete leave request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified leave request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'No company selected.'], 400);
            }

            $leave = Leave::with(['employee.personalInfo', 'approver'])
                        ->where('company_id', $companyId)
                        ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $leave,
                'message' => 'Leave request retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch leave request: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Leave request not found or failed to retrieve.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get leave balance for current employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaveBalance(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'No company selected.'], 400);
            }

            // Get current user's employee record
            $employee = Employee::where('company_id', $companyId)
                              ->where('user_id', Auth::id())
                              ->first();

            if (!$employee) {
                // If no employee record found, return default values
                return response()->json([
                    'success' => true,
                    'data' => [
                        'annual_total' => 21,
                        'annual_used' => 0,
                        'annual_remaining' => 21,
                        'pending_requests' => 0,
                        'employee_id' => null,
                    ],
                    'message' => 'Leave balance retrieved successfully (default values).'
                ]);
            }

            // Calculate leave balance (this is a simplified version)
            // In a real system, this would be based on leave policies and accrual rules
            $currentYear = now()->year;
            
            // Get approved leaves for current year
            $usedLeaves = Leave::where('company_id', $companyId)
                             ->where('employee_id', $employee->id)
                             ->where('status', 'approved')
                             ->whereYear('start_date', $currentYear)
                             ->get()
                             ->sum(function ($leave) {
                                 return $leave->start_date->diffInDays($leave->end_date) + 1;
                             });

            // Default leave allocation (this should come from leave policies)
            $totalAnnualLeaves = 21; // 21 days per year
            $remainingLeaves = max(0, $totalAnnualLeaves - $usedLeaves);

            // Get pending leaves
            $pendingLeaves = Leave::where('company_id', $companyId)
                                ->where('employee_id', $employee->id)
                                ->where('status', 'pending')
                                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'annual_total' => $totalAnnualLeaves,
                    'annual_used' => $usedLeaves,
                    'annual_remaining' => $remainingLeaves,
                    'pending_requests' => $pendingLeaves,
                    'employee_id' => $employee->id,
                ],
                'message' => 'Leave balance retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch leave balance: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch leave balance.', 'error' => $e->getMessage()], 500);
        }
    }
}
