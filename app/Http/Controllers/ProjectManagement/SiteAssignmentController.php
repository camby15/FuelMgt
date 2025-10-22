<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\SiteAssignment;
use App\Models\ProjectManagement\AssignmentHistory;
use App\Models\ProjectManagement\HomeConnectionCustomer;
use App\Models\Customer;
use App\Models\TeamParing;
use Illuminate\Http\{Request, JsonResponse, RedirectResponse};
use Illuminate\Support\Facades\{Auth, Session, DB, Validator, Log};
use Illuminate\View\View;

class SiteAssignmentController extends Controller
{
    /**
     * Display a listing of the site assignments.
     */
    public function index(): View|RedirectResponse
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
                Log::warning('No company ID in session');
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Company session expired. Please login again.');
            }

            // Get the company user - handle different auth guards
            if ($isDefaultAuth) {
                $companyUser = Auth::user();
                $companyProfile = $companyUser->companyProfile;
            } elseif ($isCompanySubUser) {
                $companyUser = Auth::guard('company_sub_user')->user();
                $companyProfile = null;
            } elseif ($isSubUser) {
                $companyUser = Auth::guard('sub_user')->user();
                $companyProfile = null;
            } else {
                $companyUser = null;
                $companyProfile = null;
            }

            // Get active assignments with relationships
            $assignments = SiteAssignment::where('company_id', $companyId)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->with(['customer', 'team.teamMembers', 'assignedBy', 'resolvedBy'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Get sites (customers) for dropdown - using home_connection_customers
            $sites = HomeConnectionCustomer::where('company_id', $companyId)
                ->select('id', 'customer_name as name', 'location as address', 'contact_number as phone')
                ->orderBy('customer_name')
                ->get();

            // Get teams for dropdown
            $teams = TeamParing::where('company_id', $companyId)
                ->with(['teamMembers'])
                ->select('id', 'team_name', 'team_code', 'team_status', 'team_location')
                ->orderBy('team_name')
                ->get();

            // Get assignments with issues
            $issues = SiteAssignment::where('company_id', $companyId)
                ->where('has_issue', true)
                ->where('issue_status', '!=', SiteAssignment::ISSUE_STATUS_RESOLVED)
                ->with(['customer', 'team', 'assignedBy'])
                ->orderBy('issue_reported_at', 'desc')
                ->get();

            // Get assignment history
            $history = SiteAssignment::where('company_id', $companyId)
                ->where('status', SiteAssignment::STATUS_COMPLETED)
                ->with(['customer', 'team'])
                ->orderBy('completed_date', 'desc')
                ->limit(20)
                ->get();

            return view('company.ProjectManagement.components.home-connection-tabs.site-assignment', [
                'company' => $companyProfile,
                'assignments' => $assignments,
                'sites' => $sites,
                'teams' => $teams,
                'issues' => $issues,
                'history' => $history,
                'companyId' => $companyId,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in SiteAssignmentController index method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error loading site assignment page. Please try again.');
        }
    }
    /**
     * Store a newly created site assignment
     */
    public function store(Request $request): RedirectResponse
    {
        // Log incoming request
        Log::info('=== Site Assignment Store Request ===', $request->all());
        
        try {
            $companyId = Session::get('selected_company_id');
            $userId = $this->getAuthenticatedUserId();
            
            Log::info('Company ID: ' . $companyId . ', User ID: ' . $userId);
            
            // Check if company exists
            $companyExists = \App\Models\CompanyProfile::where('id', $companyId)->exists();
            Log::info('Company exists: ' . ($companyExists ? 'YES' : 'NO'));
            
            if (!$companyId || !$companyExists) {
                return redirect()
                    ->back()
                    ->with('error', 'Invalid company session. Company ID: ' . $companyId);
            }
            
            // Validate - accept both site_id and customer_id
            $customerId = $request->input('customer_id') ?? $request->input('site_id');
            
            $request->validate([
                'team_id' => 'required|exists:team_paring,id',
                'priority' => 'required|in:low,medium,high,critical'
            ]);
            
            // Validate customer_id separately
            if (!$customerId || !HomeConnectionCustomer::where('id', $customerId)->exists()) {
                return redirect()
                    ->back()
                    ->with('error', 'Invalid customer selected.');
            }
            
            Log::info('Validation passed');
            
            DB::beginTransaction();
            
            // Create assignment
            $assignment = new SiteAssignment();
            $assignment->company_id = $companyId;
            $assignment->customer_id = $customerId;
            $assignment->team_id = $request->team_id;
            $assignment->assigned_by = $userId;
            $assignment->assignment_title = $request->assignment_title ?? 'New Assignment';
            $assignment->description = $request->description;
            $assignment->status = 'pending';
            $assignment->priority = $request->priority;
            $assignment->assigned_date = $request->assigned_date ?? now();
            $assignment->has_issue = false;
            
            Log::info('About to save assignment');
            $assignment->save();
            Log::info('Assignment saved successfully with ID: ' . $assignment->id);
            
            // Update customer status to "Schedule"
            $customer = HomeConnectionCustomer::find($customerId);
            if ($customer) {
                $customer->status = 'Schedule';
                $customer->save();
                Log::info('Customer status updated to Schedule for customer ID: ' . $customerId);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Assignment created successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== Assignment Creation Error ===');
            Log::error('Message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Store bulk site assignments
     */
    public function bulkStore(Request $request): RedirectResponse
    {
        // Log incoming request
        Log::info('=== Bulk Site Assignment Store Request ===', $request->all());
        
        try {
            $companyId = Session::get('selected_company_id');
            $userId = $this->getAuthenticatedUserId();
            
            Log::info('Company ID: ' . $companyId . ', User ID: ' . $userId);
            
            // Check if company exists
            $companyExists = \App\Models\CompanyProfile::where('id', $companyId)->exists();
            Log::info('Company exists: ' . ($companyExists ? 'YES' : 'NO'));
            
            if (!$companyId || !$companyExists) {
                return redirect()
                    ->back()
                    ->with('error', 'Invalid company session. Company ID: ' . $companyId);
            }
            
            // Validate
            $request->validate([
                'customer_ids' => 'required|string',
                'team_id' => 'required|exists:team_paring,id',
                'priority' => 'required|in:low,medium,high,critical',
                'assigned_date' => 'required|date'
            ]);
            
            // Parse customer IDs from JSON string
            $customerIds = json_decode($request->customer_ids, true);
            
            if (!is_array($customerIds) || empty($customerIds)) {
                return redirect()
                    ->back()
                    ->with('error', 'No customers selected for bulk scheduling.');
            }
            
            Log::info('Parsed customer IDs', ['count' => count($customerIds), 'ids' => $customerIds]);
            
            DB::beginTransaction();
            
            $successCount = 0;
            $failedCount = 0;
            $errors = [];
            
            foreach ($customerIds as $customerId) {
                try {
                    // Validate customer exists
                    $customer = HomeConnectionCustomer::where('id', $customerId)
                        ->where('company_id', $companyId)
                        ->first();
                    
                    if (!$customer) {
                        $errors[] = "Customer ID {$customerId} not found";
                        $failedCount++;
                        continue;
                    }
                    
                    // Check if customer already has an active assignment
                    $existingAssignment = SiteAssignment::where('customer_id', $customerId)
                        ->where('company_id', $companyId)
                        ->whereIn('status', ['pending', 'in_progress'])
                        ->first();
                    
                    if ($existingAssignment) {
                        Log::info("Customer {$customer->customer_name} already has an active assignment, skipping");
                        $errors[] = "{$customer->customer_name} already has an active assignment";
                        $failedCount++;
                        continue;
                    }
                    
                    // Create assignment
                    $assignment = new SiteAssignment();
                    $assignment->company_id = $companyId;
                    $assignment->customer_id = $customerId;
                    $assignment->team_id = $request->team_id;
                    $assignment->assigned_by = $userId;
                    $assignment->assignment_title = $request->assignment_title ?? 'Bulk Scheduled Assignment';
                    $assignment->description = $request->description;
                    $assignment->status = 'pending';
                    $assignment->priority = $request->priority;
                    $assignment->assigned_date = $request->assigned_date;
                    $assignment->has_issue = false;
                    
                    $assignment->save();
                    Log::info("Assignment created for customer: {$customer->customer_name} (ID: {$customerId})");
                    
                    // Update customer status to "Schedule"
                    $customer->status = 'Schedule';
                    $customer->save();
                    Log::info("Customer status updated to Schedule for: {$customer->customer_name}");
                    
                    $successCount++;
                    
                } catch (\Exception $e) {
                    Log::error("Failed to create assignment for customer ID {$customerId}: " . $e->getMessage());
                    $errors[] = "Failed for customer ID {$customerId}: " . $e->getMessage();
                    $failedCount++;
                }
            }
            
            DB::commit();
            
            // Build success message
            $message = "Bulk scheduling completed. ";
            $message .= "{$successCount} appointment(s) scheduled successfully.";
            
            if ($failedCount > 0) {
                $message .= " {$failedCount} failed.";
                if (!empty($errors)) {
                    $message .= " Errors: " . implode(', ', array_slice($errors, 0, 3));
                    if (count($errors) > 3) {
                        $message .= "... and " . (count($errors) - 3) . " more.";
                    }
                }
            }
            
            Log::info('Bulk assignment completed', [
                'success_count' => $successCount,
                'failed_count' => $failedCount,
                'total' => count($customerIds)
            ]);
            
            if ($successCount > 0) {
                return redirect()->back()->with('success', $message);
            } else {
                return redirect()->back()->with('error', 'Failed to create any assignments. ' . implode(', ', $errors));
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== Bulk Assignment Creation Error ===');
            Log::error('Message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Bulk scheduling failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified assignment details.
     */
    public function show($id): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            $assignment = SiteAssignment::where('company_id', $companyId)
                ->with(['customer', 'team'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $assignment->id,
                    'customer_name' => $assignment->customer->customer_name ?? 'N/A',
                    'team_name' => $assignment->team->team_name ?? 'N/A',
                    'assignment_title' => $assignment->assignment_title,
                    'priority' => $assignment->priority,
                    'status' => $assignment->status,
                    'assigned_date' => $assignment->assigned_date,
                    'start_date' => $assignment->start_date,
                    'end_date' => $assignment->due_date,
                    'progress' => $assignment->progress ?? 0,
                    'notes' => $assignment->description
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading assignment details', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error loading assignment details. Please try again.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Company session expired. Please login again.');
            }
            
            $assignment = SiteAssignment::with(['customer', 'team'])
                ->where('company_id', $companyId)
                ->findOrFail($id);

            $sites = HomeConnectionCustomer::where('company_id', $companyId)->get();
            $teams = TeamParing::where('company_id', $companyId)->get();

            return view('company.ProjectManagement.components.home-connection-tabs.site-assignment', [
                'assignment' => $assignment,
                'sites' => $sites,
                'teams' => $teams,
                'showEditModal' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading assignment for edit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error loading assignment for editing. Please try again.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Company session expired. Please login again.');
            }
            
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|exists:home_connection_customers,id',
                'team_id' => 'required|exists:team_paring,id',
                'assignment_title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'priority' => 'required|in:low,medium,high,critical,urgent',
                'status' => 'required|in:pending,in_progress,completed',
                'assigned_date' => 'nullable|date',
                'start_date' => 'nullable|date',
                'due_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $assignment = SiteAssignment::where('company_id', $companyId)
                ->findOrFail($id);

            DB::beginTransaction();

            $assignment->update($request->only([
                'customer_id',
                'team_id',
                'assignment_title',
                'description',
                'priority',
                'status',
                'assigned_date',
                'start_date',
                'due_date'
            ]));

            // Update completed_date if status is completed
            if ($request->status === 'completed' && !$assignment->completed_date) {
                $assignment->update(['completed_date' => now()]);
            }

            DB::commit();

            // Check if this is an AJAX request (from edit modal)
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Assignment updated successfully'
                ]);
            }

            return redirect()
                ->route('site-assignments.index')
                ->with('success', 'Assignment updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e)
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating site assignment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating assignment'
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Error updating site assignment. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse|JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');

            if (!$companyId) {
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Company session expired. Please login again.'
                    ], 401);
                }
                return redirect()
                    ->back()
                    ->with('error', 'Company session expired. Please login again.');
            }

            $assignment = SiteAssignment::where('company_id', $companyId)
                ->findOrFail($id);

            DB::beginTransaction();

            // Get customer ID before deleting assignment
            $customerId = $assignment->customer_id;

            // Soft delete the assignment
            $assignment->delete();

            // Update customer status back to "Pending" when assignment is deleted
            if ($customerId) {
                $customer = HomeConnectionCustomer::find($customerId);
                if ($customer) {
                    $customer->status = 'Pending';
                    $customer->save();
                    Log::info('Customer status updated to Pending for customer ID: ' . $customerId);
                }
            }

            DB::commit();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Site assignment deleted successfully and customer status updated to Pending'
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'Site assignment deleted successfully and customer status updated to Pending');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting site assignment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to delete schedule'
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Error deleting site assignment. Please try again.');
        }
    }
    
    /**
     * Report an issue for a site assignment
     */
    public function reportIssue(Request $request, $assignmentId): RedirectResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return redirect()
                    ->back()
                    ->with('error', 'Company session expired. Please login again.');
            }

            $assignment = SiteAssignment::where('company_id', $companyId)
                ->findOrFail($assignmentId);

            $validator = Validator::make($request->all(), [
                'issue_description' => 'required|string|max:1000',
                'priority' => 'nullable|in:low,medium,high,critical',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please check the form for errors.');
            }

            DB::beginTransaction();

            // Update assignment with issue
            $assignment->update([
                'has_issue' => true,
                'issue_description' => $request->issue_description,
                'issue_status' => SiteAssignment::ISSUE_STATUS_REPORTED,
                'issue_reported_at' => now(),
                'priority' => $request->priority ?? $assignment->priority,
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Issue reported successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error reporting issue', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error reporting issue. Please try again.');
        }
    }
    
    /**
     * Resolve an issue
     */
    public function resolveIssue(Request $request, $assignmentId, $issueId): RedirectResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return redirect()
                    ->back()
                    ->with('error', 'Company session expired. Please login again.');
            }

            $assignment = SiteAssignment::where('company_id', $companyId)
                ->findOrFail($assignmentId);

            $validator = Validator::make($request->all(), [
                'resolution_notes' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please check the form for errors.');
            }

            DB::beginTransaction();

            // Get the authenticated user ID
            $userId = $this->getAuthenticatedUserId();

            // Update assignment with resolution
            $assignment->update([
                'issue_status' => SiteAssignment::ISSUE_STATUS_RESOLVED,
                'resolution_notes' => $request->resolution_notes,
                'issue_resolved_at' => now(),
                'resolved_by' => $userId,
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Issue resolved successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error resolving issue', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error resolving issue. Please try again.');
        }
    }

    /**
     * Get the authenticated user ID from the appropriate guard.
     */
    private function getAuthenticatedUserId(): ?int
    {
        // Check company_sub_user guard first
        if (Auth::guard('company_sub_user')->check()) {
            return Auth::guard('company_sub_user')->id();
        }
        
        // Check sub_user guard
        if (Auth::guard('sub_user')->check()) {
            return Auth::guard('sub_user')->id();
        }
        
        // Check default web guard
        if (Auth::check()) {
            return Auth::id();
        }
        
        return null;
    }

    /**
     * Export site assignments for roster management to CSV
     */
    public function export(Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect()->back()->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->back()->with('error', 'Company session expired');
            }

            // Build query for assignments that are not completed/cancelled (same as index)
            $query = SiteAssignment::where('company_id', $companyId)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->with(['customer', 'team.teamMembers', 'assignedBy', 'resolvedBy']);

            // Apply team filter if specified
            if ($request->has('team_id') && $request->team_id !== 'all') {
                $query->where('team_id', $request->team_id);
            }

            $assignments = $query->orderBy('created_at', 'desc')->get();

            $filename = 'roster_assignments_export_' . date('Y-m-d_H-i-s') . '.csv';
            $tempFile = tempnam(sys_get_temp_dir(), $filename);
            $handle = fopen($tempFile, 'w');

            // Write header row
            $headers = [
                'ID',
                'Customer Name',
                'Location',
                'Team Name',
                'Team Code',
                'Assignment Title',
                'Priority',
                'Status',
                'Assigned Date',
                'Assigned By',
                'Progress',
                'Description',
                'Created At'
            ];
            fputcsv($handle, $headers);

            // Write assignment data
            foreach ($assignments as $assignment) {
                $row = [
                    $assignment->id,
                    $assignment->customer->customer_name ?? 'N/A',
                    $assignment->customer->location ?? 'N/A',
                    $assignment->team->team_name ?? 'N/A',
                    $assignment->team->team_code ?? 'N/A',
                    $assignment->assignment_title ?? 'N/A',
                    ucfirst($assignment->priority ?? 'low'),
                    ucfirst(str_replace('_', ' ', $assignment->status ?? 'pending')),
                    $assignment->assigned_date ? $assignment->assigned_date->format('Y-m-d') : 'N/A',
                    $assignment->assignedBy ? ($assignment->assignedBy->full_name ?? ($assignment->assignedBy->first_name . ' ' . $assignment->assignedBy->last_name)) : 'N/A',
                    ($assignment->progress ?? 0) . '%',
                    $assignment->description ?? '',
                    $assignment->created_at->format('Y-m-d H:i:s')
                ];
                fputcsv($handle, $row);
            }

            fclose($handle);

            return response()->download($tempFile, $filename, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error exporting roster assignments: ' . $e->getMessage());
            return back()->with('error', 'Unable to export roster assignments');
        }
    }
}
