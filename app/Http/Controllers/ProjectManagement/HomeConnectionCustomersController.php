<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\HomeConnectionCustomer;
use App\Models\ProjectManagement\SiteAssignment;
use App\Models\ProjectManagement\HomeConnectionTeamRoster;
use App\Models\Customer;
use App\Models\TeamParing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\HomeConnectionAssignmentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class HomeConnectionCustomersController extends Controller
{
    /**
     * Display the home connection dashboard with customers data
     */
    public function homeConnection(Request $request)
    {
        try {
            Log::info('HomeConnectionCustomersController@homeConnection called');
            
            // Temporarily bypass authentication for testing
            $companyId = Session::get('selected_company_id') ?? 1;
            Log::info('Company ID: ' . $companyId);

            // Get the business unit filter from request
            $businessUnit = $request->get('business_unit');
            
            // Prepare assignment filters
            $assignmentFilters = $this->extractAssignmentFilters($request);
            
            Log::info('Assignment filters extracted', [
                'filters' => $assignmentFilters,
                'request_params' => $request->all()
            ]);
            
            // Build the customers query
            $query = HomeConnectionCustomer::where('company_id', $companyId);
            
            // Apply business unit filter if provided
            if ($businessUnit && in_array($businessUnit, ['GESL', 'LINFRA'])) {
                $query->where('business_unit', $businessUnit);
            }
            
            // Apply customer filters based on assignment filters
            if (!empty($assignmentFilters['location'])) {
                $query->where('location', $assignmentFilters['location']);
            }
            
            if (!empty($assignmentFilters['connection_type'])) {
                $query->where('connection_type', $assignmentFilters['connection_type']);
            }
            
            // If team filter is applied, filter customers who have assignments with that team
            if (!empty($assignmentFilters['team_id'])) {
                $query->whereHas('assignments', function($assignmentQuery) use ($assignmentFilters) {
                    $assignmentQuery->where('team_id', $assignmentFilters['team_id']);
                });
            }
            
            // If date filters are applied, filter customers who have assignments in that date range
            if (!empty($assignmentFilters['date_from']) || !empty($assignmentFilters['date_to'])) {
                $query->whereHas('assignments', function($assignmentQuery) use ($assignmentFilters) {
                    if (!empty($assignmentFilters['date_from'])) {
                        $assignmentQuery->whereRaw('COALESCE(assigned_date, created_at) >= ?', [
                            \Carbon\Carbon::parse($assignmentFilters['date_from'])->startOfDay()->format('Y-m-d H:i:s')
                        ]);
                    }
                    if (!empty($assignmentFilters['date_to'])) {
                        $assignmentQuery->whereRaw('COALESCE(assigned_date, created_at) <= ?', [
                            \Carbon\Carbon::parse($assignmentFilters['date_to'])->endOfDay()->format('Y-m-d H:i:s')
                        ]);
                    }
                });
            }
            
            // If issue filter is applied, filter customers based on their assignment issues
            if (!empty($assignmentFilters['issue'])) {
                $query->whereHas('assignments', function($assignmentQuery) use ($assignmentFilters) {
                    switch ($assignmentFilters['issue']) {
                        case 'with_issue':
                            $assignmentQuery->where('has_issue', true)
                                ->where(function ($issueQuery) {
                                    $issueQuery->whereNull('issue_status')
                                        ->orWhere('issue_status', '!=', SiteAssignment::ISSUE_STATUS_RESOLVED);
                                });
                            break;
                        case 'without_issue':
                            $assignmentQuery->where(function ($issueQuery) {
                                $issueQuery->whereNull('has_issue')
                                    ->orWhere('has_issue', false)
                                    ->orWhere('issue_status', SiteAssignment::ISSUE_STATUS_RESOLVED);
                            });
                            break;
                        case 'resolved_issue':
                            $assignmentQuery->where('issue_status', SiteAssignment::ISSUE_STATUS_RESOLVED);
                            break;
                    }
                });
            }

            // Fetch customers for the current company with pagination
            $customers = $query->orderBy('created_at', 'desc')
                ->paginate(5); // 5 customers per page

            Log::info('Fetched customers after filtering', [
                'count' => $customers->count(),
                'total' => $customers->total(),
                'applied_filters' => $assignmentFilters
            ]);

            $assignmentQuery = $this->buildFilteredAssignmentQuery($companyId, $assignmentFilters);

            // Get site assignment data with filters applied
            $assignments = (clone $assignmentQuery)
                ->orderBy('created_at', 'desc')
                ->get();

            // Get sites (customers) for dropdown - using home_connection_customers
            $sites = HomeConnectionCustomer::where('company_id', $companyId)
                ->select('id', 'customer_name as name', 'location as address', 'contact_number as phone')
                ->orderBy('customer_name')
                ->get();

            // Get teams for dropdown with members
            $teams = TeamParing::where('company_id', $companyId)
                ->with(['teamMembers:id,full_name,position,phone,email'])
                ->orderBy('team_name')
                ->get();

            // Get assignments with issues
            $issues = (clone $assignmentQuery)
                ->where('has_issue', true)
                ->where('issue_status', '!=', SiteAssignment::ISSUE_STATUS_RESOLVED)
                ->orderBy('issue_reported_at', 'desc')
                ->get();

            // Get assignment history
            $history = (clone $assignmentQuery)
                ->where('status', SiteAssignment::STATUS_COMPLETED)
                ->orderBy('completed_date', 'desc')
                ->limit(20)
                ->get();

            // Get rosters for roster management tab
            $rosters = HomeConnectionTeamRoster::where('company_id', $companyId)
                ->with(['team.teamMembers', 'siteAssignment.customer', 'creator', 'updater'])
                ->orderBy('schedule_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Get site assignments for roster dropdown (active assignments only)
            $siteAssignments = SiteAssignment::where('company_id', $companyId)
                ->whereIn('status', [SiteAssignment::STATUS_ASSIGNED, SiteAssignment::STATUS_IN_PROGRESS])
                ->with(['customer', 'team'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('company.ProjectManagement.homeConnection', [
                'customers' => $customers,
                'businessUnit' => $businessUnit,
                'activeTab' => 'customers', // Default to customers tab
                'assignments' => $assignments,
                'sites' => $sites,
                'teams' => $teams,
                'issues' => $issues,
                'history' => $history,
                'rosters' => $rosters,
                'siteAssignments' => $siteAssignments,
                'companyId' => $companyId,
                'assignmentFilters' => $assignmentFilters,
                'assignmentFilterOptions' => $assignmentFilterOptions,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching home connection page: ' . $e->getMessage());
            return view('company.ProjectManagement.homeConnection', [
                'customers' => collect(),
                'businessUnit' => null,
                'activeTab' => 'customers',
                'assignments' => collect(),
                'sites' => collect(),
                'teams' => collect(),
                'issues' => collect(),
                'history' => collect(),
                'rosters' => collect(),
                'siteAssignments' => collect(),
                'companyId' => $companyId ?? 1,
                'assignmentFilters' => $this->extractAssignmentFilters($request),
                'assignmentFilterOptions' => [
                    'locations' => [],
                    'connection_types' => ['Traditional', 'Quick ODN'],
                    'issue_options' => [
                        '' => 'All Issues',
                        'with_issue' => 'With Issues',
                        'without_issue' => 'Without Issues',
                        'resolved_issue' => 'Resolved Issues',
                    ],
                ],
            ]);
        }
    }

    /**
     * Display a paginated list of all home connection customers
     */
    public function index(Request $request)
    {
        try {
            // Debug logging
            Log::info('HomeConnectionCustomersController@index called', [
                'auth_check' => Auth::check(),
                'user_id' => Auth::id(),
                'company_id' => Session::get('selected_company_id'),
                'business_unit' => $request->get('business_unit'),
                'session_data' => Session::all()
            ]);

            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                Log::info('User not authenticated, redirecting to login');
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                Log::info('No company ID in session, redirecting to login');
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            // Get the business unit filter from request
            $businessUnit = $request->get('business_unit');
            
            // Build the query
            $query = HomeConnectionCustomer::where('company_id', $companyId);
            
            // Apply business unit filter if provided
            if ($businessUnit && in_array($businessUnit, ['GESL', 'LINFRA'])) {
                $query->where('business_unit', $businessUnit);
            }
            
            // Fetch customers with pagination
            $customers = $query->orderBy('created_at', 'desc')
                ->paginate(5); // 5 customers per page

            // If this is an AJAX request, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'customers' => $customers->items(),
                    'pagination' => [
                        'current_page' => $customers->currentPage(),
                        'last_page' => $customers->lastPage(),
                        'per_page' => $customers->perPage(),
                        'total' => $customers->total(),
                        'from' => $customers->firstItem(),
                        'to' => $customers->lastItem(),
                    ],
                    'business_unit' => $businessUnit
                ]);
            }

            // Get site assignment data
            $assignmentFilters = $this->extractAssignmentFilters($request);
            $assignmentQuery = $this->buildFilteredAssignmentQuery($companyId, $assignmentFilters);

            $assignments = (clone $assignmentQuery)
                ->orderBy('created_at', 'desc')
                ->get();

            // Get sites (customers) for dropdown - using home_connection_customers
            $sites = HomeConnectionCustomer::where('company_id', $companyId)
                ->select('id', 'customer_name as name', 'location as address', 'contact_number as phone')
                ->orderBy('customer_name')
                ->get();

            // Get teams for dropdown with members
            $teams = TeamParing::where('company_id', $companyId)
                ->with(['teamMembers:id,full_name,position,phone,email'])
                ->orderBy('team_name')
                ->get();

            // Get assignments with issues
            $issues = (clone $assignmentQuery)
                ->where('has_issue', true)
                ->where('issue_status', '!=', SiteAssignment::ISSUE_STATUS_RESOLVED)
                ->orderBy('issue_reported_at', 'desc')
                ->get();

            // Get assignment history
            $history = (clone $assignmentQuery)
                ->where('status', SiteAssignment::STATUS_COMPLETED)
                ->orderBy('completed_date', 'desc')
                ->limit(20)
                ->get();

            $assignmentFilterOptions = $this->getAssignmentFilterOptions($companyId);

            return view('company.ProjectManagement.homeConnection', [
                'customers' => $customers,
                'businessUnit' => $businessUnit,
                'assignments' => $assignments,
                'sites' => $sites,
                'teams' => $teams,
                'issues' => $issues,
                'history' => $history,
                'companyId' => $companyId,
                'assignmentFilters' => $assignmentFilters,
                'assignmentFilterOptions' => $assignmentFilterOptions,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching customers: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to fetch customers'
                ], 500);
            }
            
            return back()->with('error', 'Unable to fetch customers');
        }
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('company.ProjectManagement.components.home-connection-tabs.create-customer');
    }

    /**
     * Store a newly created customer in the database
     */
    public function store(Request $request)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            $authUserId = $this->getAuthenticatedUserId();

            // Add company_id and user_id to request
            $request->merge([
                'company_id' => $companyId,
                'created_by' => $authUserId,
                'updated_by' => $authUserId
            ]);

            // Format MSISDN to include +233 prefix
            if ($request->has('msisdn')) {
                $msisdn = $request->input('msisdn');
                if (strpos($msisdn, '+233') !== 0) {
                    $request->merge(['msisdn' => '+233' . $msisdn]);
                }
            }

            // Format contact number to include +233 prefix
            if ($request->has('contact_number')) {
                $contactNumber = $request->input('contact_number');
                if (strpos($contactNumber, '+233') !== 0) {
                    $request->merge(['contact_number' => '+233' . $contactNumber]);
                }
            }

            // Use the validation rules defined in the HomeConnectionCustomer model
            $validator = Validator::make($request->all(),
                HomeConnectionCustomer::validationRules(null, $companyId)
            );

            // Add custom validation for unique MSISDN within the same company
            $validator->after(function ($validator) use ($request, $companyId) {
                $existingCustomer = HomeConnectionCustomer::where('msisdn', $request->input('msisdn'))
                    ->where('company_id', $companyId)
                    ->whereNull('deleted_at')
                    ->first();
                if ($existingCustomer) {
                    $validator->errors()->add('msisdn', 'This MSISDN is already registered with another customer.');
                }

                if ($request->filled('email')) {
                    $existingEmailCustomer = HomeConnectionCustomer::where('email', $request->input('email'))
                        ->where('company_id', $companyId)
                        ->whereNull('deleted_at')
                        ->first();
                    if ($existingEmailCustomer) {
                        $validator->errors()->add('email', 'This email is already registered with another customer.');
                    }
                }
            });

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the errors below.');
            }

            $validatedData = $validator->validated();

            // Ensure status defaults to Pending (overrides any user input)
            $validatedData['status'] = 'Pending';

            // Remove any previously soft-deleted records that conflict with unique constraints
            $conflictingSoftDeleted = HomeConnectionCustomer::onlyTrashed()
                ->where('company_id', $companyId)
                ->where(function ($query) use ($validatedData) {
                    $query->where('msisdn', $validatedData['msisdn']);
                    if (!empty($validatedData['email'])) {
                        $query->orWhere('email', $validatedData['email']);
                    }
                })
                ->get();

            if ($conflictingSoftDeleted->isNotEmpty()) {
                foreach ($conflictingSoftDeleted as $conflict) {
                    Log::warning('Force deleting soft-deleted customer due to unique field conflict', [
                        'customer_id' => $conflict->id,
                        'msisdn' => $conflict->msisdn,
                        'email' => $conflict->email,
                    ]);
                    $conflict->forceDelete();
                }
            }

            // Create a new customer using validated data
            $customer = HomeConnectionCustomer::create($validatedData);

            // Check if auto-schedule is enabled
            if ($request->has('auto_schedule_enabled') && $request->input('auto_schedule_enabled') === 'true') {
                try {
                    // Pick a random active team
                    $randomTeam = TeamParing::where('company_id', $companyId)
                        ->whereRaw('LOWER(team_status) = ?', ['active'])
                        ->inRandomOrder()
                        ->first();
                    
                    if ($randomTeam) {
                        
                        // Calculate next day at 9:00 AM
                        $nextDay9AM = \Carbon\Carbon::tomorrow()->setTime(9, 0, 0);
                        
                        // Create site assignment
                        $assignment = new \App\Models\ProjectManagement\SiteAssignment();
                        $assignment->company_id = $companyId;
                        $assignment->customer_id = $customer->id;
                        $assignment->team_id = $randomTeam->id;
                        $assignment->assigned_by = $authUserId;
                        $assignment->assignment_title = 'Auto-Scheduled Assignment';
                        $assignment->description = 'Automatically created assignment for new customer: ' . $customer->customer_name;
                        $assignment->status = SiteAssignment::STATUS_PENDING;
                        $assignment->priority = SiteAssignment::PRIORITY_HIGH; // Default to high priority
                        $assignment->assigned_date = $nextDay9AM;
                        $assignment->has_issue = false;
                        $assignment->save();
                        
                        // Update customer status to Schedule
                        $customer->status = 'Schedule';
                        $customer->save();
                        
                        Log::info('Auto-scheduled assignment created for customer', [
                            'customer_id' => $customer->id,
                            'team_id' => $randomTeam->id,
                            'team_name' => $randomTeam->team_name,
                            'assigned_date' => $nextDay9AM->toDateTimeString(),
                        ]);
                        
                        return redirect()->route('project-management.home-connection')
                            ->with('success', 'Customer created and automatically scheduled to team "' . $randomTeam->team_name . '" for tomorrow at 9:00 AM!');
                    } else {
                        Log::warning('No active teams available for auto-scheduling');
                        return redirect()->route('project-management.home-connection')
                            ->with('success', 'Customer created successfully. No active teams available for auto-scheduling.');
                    }
                } catch (\Exception $e) {
                    Log::error('Error auto-scheduling customer: ' . $e->getMessage());
                    // Customer was created, just assignment failed
                    return redirect()->route('project-management.home-connection')
                        ->with('success', 'Customer created successfully but auto-scheduling failed. You can manually schedule them.');
                }
            }

            return redirect()->route('project-management.home-connection')
                ->with('success', 'Customer created successfully');
        } catch (QueryException $e) {
            Log::error('Database error creating customer: ' . $e->getMessage());

            if ($e->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->with('error', 'A customer with the same MSISDN or email already exists. Please use a different value or update the existing record.');
            }

            return back()->with('error', 'Unable to create customer');
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            return back()->with('error', 'Unable to create customer');
        }
    }

    /**
     * Display detailed information about a specific customer
     */
    public function show($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $customer = HomeConnectionCustomer::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            return view('company.ProjectManagement.components.home-connection-tabs.show-customer', compact('customer'));
        } catch (\Exception $e) {
            Log::error('Error fetching customer details: ' . $e->getMessage());
            return back()->with('error', 'Customer not found');
        }
    }

    /**
     * Show the form for editing an existing customer
     */
    public function edit($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $customer = HomeConnectionCustomer::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            return view('company.ProjectManagement.components.home-connection-tabs.edit-customer', compact('customer'));
        } catch (\Exception $e) {
            Log::error('Error preparing customer edit: ' . $e->getMessage());
            return back()->with('error', 'Customer not found');
        }
    }

    /**
     * Update customer status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            $customer = HomeConnectionCustomer::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            $customer->update([
                'status' => $request->input('status', 'Pending'),
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('project-management.customers.index')
                ->with('success', 'Customer status updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating customer status: ' . $e->getMessage());
            return back()->with('error', 'Unable to update customer status');
        }
    }

    /**
     * Update an existing customer's information
     */
    public function update(Request $request, $id)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            $customer = HomeConnectionCustomer::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            // Debug: Log the incoming request data
            Log::info('Edit customer request data', [
                'customer_id' => $id,
                'all_data' => $request->all(),
                'customer_name' => $request->input('customer_name'),
                'msisdn' => $request->input('msisdn'),
                'email' => $request->input('email'),
                'contact_number' => $request->input('contact_number'),
                'connection_type' => $request->input('connection_type'),
                'location' => $request->input('location'),
                'gps_address' => $request->input('gps_address'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'status' => $request->input('status')
            ]);

            // Check if this is a status update
            // Removed problematic status update check that's causing method failures

            $validatedData = $request->validate([
                'msisdn' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('home_connection_customers', 'msisdn')
                        ->ignore($customer->id)
                        ->where(function ($q) use ($companyId) {
                            $q->where('company_id', $companyId)
                              ->whereNull('deleted_at');
                        })
                ],
                'customer_name' => 'required|string|max:255',
                'email' => [
                    'nullable',
                    'email',
                    Rule::unique('home_connection_customers', 'email')
                        ->ignore($customer->id)
                        ->where(function ($q) use ($companyId) {
                            $q->where('company_id', $companyId)
                              ->whereNull('deleted_at');
                        })
                ],
                'contact_number' => 'required|string|max:20',
                'connection_type' => 'required|in:Traditional,Quick ODN',
                'location' => 'required|string|max:255',
                'gps_address' => 'nullable|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'status' => 'required|in:Active,Inactive,Pending,Schedule'
            ], [
                'latitude.between' => 'Latitude must be between -90 and 90 degrees.',
                'longitude.between' => 'Longitude must be between -180 and 180 degrees.',
                'latitude.numeric' => 'Latitude must be a valid number.',
                'longitude.numeric' => 'Longitude must be a valid number.'
            ]);

            // Only set latitude and longitude if they have valid values
            if (empty($validatedData['latitude']) && empty($validatedData['longitude'])) {
                $validatedData['latitude'] = null;
                $validatedData['longitude'] = null;
            }

            // Format MSISDN to include +233 prefix
            if (isset($validatedData['msisdn']) && strpos($validatedData['msisdn'], '+233') !== 0) {
                $validatedData['msisdn'] = '+233' . $validatedData['msisdn'];
            }

            // Format contact number to include +233 prefix
            if (isset($validatedData['contact_number']) && strpos($validatedData['contact_number'], '+233') !== 0) {
                $validatedData['contact_number'] = '+233' . $validatedData['contact_number'];
            }

            // GPS coordinates are already handled as separate latitude/longitude fields

            $validatedData['updated_by'] = Auth::id();

            $customer->update($validatedData);

            // Debug: Log what was actually saved
            Log::info('Customer updated successfully', [
                'customer_id' => $id,
                'updated_data' => $validatedData,
                'final_customer_data' => $customer->fresh()->toArray()
            ]);

            // Check if this is an AJAX request (from edit modal)
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer updated successfully'
                ]);
            }

            return redirect()->route('project-management.home-connection')
                ->with('success', 'Customer updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors for AJAX requests
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Error updating customer: ' . $e->getMessage());

            // Check if this is an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to update customer'
                ], 500);
            }

            return back()->with('error', 'Unable to update customer');
        }
    }

    /**
     * Remove a specific customer from the database
     */
    public function destroy($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $customer = HomeConnectionCustomer::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            // Delete related site assignments permanently
            $assignmentConditions = SiteAssignment::withTrashed()
                ->where('company_id', $companyId)
                ->where('customer_id', $customer->id);

            $deletedAssignments = (clone $assignmentConditions)->count();
            if ($deletedAssignments > 0) {
                $assignmentConditions->forceDelete();

                Log::info('Deleted site assignments for customer', [
                    'customer_id' => $customer->id,
                    'deleted_assignments' => $deletedAssignments,
                ]);
            }

            $customer->forceDelete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer and related assignments deleted successfully'
                ]);
            }

            return redirect()->route('project-management.home-connection')
                ->with('success', 'Customer and related assignments deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting customer: ' . $e->getMessage());
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to delete customer'
                ], 500);
            }
            return back()->with('error', 'Unable to delete customer');
        }
    }

    /**
     * Download CSV template for bulk customer upload
     */
    public function downloadTemplate()
    {
        try {
            $filename = 'Customer Bulk Upload Template.csv';
            $tempFile = tempnam(sys_get_temp_dir(), $filename);
            $handle = fopen($tempFile, 'w');

            // Write the header row
            $headers = ['MSISDN', 'Customer Name', 'Email', 'Contact Number', 'Connection Type', 'Location', 'GPS Address', 'Latitude', 'Longitude', 'Status', 'Business Unit'];
            fputcsv($handle, $headers);

            // Write an example row
            $exampleRow = [
                '541234567',  // MSISDN without +233 prefix (will be added automatically)
                'John Doe',
                'john.doe@example.com',
                '541234567',  // Contact number without +233 prefix (will be added automatically)
                'Traditional',
                'Adenta - Accra',
                'GA-767',
                '5.6037',
                '-0.1870',
                'Pending',
                'GESL'
            ];
            fputcsv($handle, $exampleRow);

            fclose($handle);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error creating template: ' . $e->getMessage());
            return back()->with('error', 'Unable to download template');
        }
    }

    /**
     * Export customers to CSV
     */
    public function export(Request $request)
    {
        try {
            // Debug: Log authentication status
            Log::info('Export method called', [
                'auth_check' => Auth::check(),
                'user_id' => Auth::id(),
                'session_company_id' => Session::get('selected_company_id'),
                'request_url' => $request->fullUrl(),
                'request_params' => $request->all(),
                'request_method' => $request->method()
            ]);

            if (!Auth::check()) {
                Log::warning('Export failed: User not authenticated');
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => 'Please login to continue'], 401);
                }
                return redirect()->back()->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                Log::warning('Export failed: No company session');
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => 'Company session expired'], 401);
                }
                return redirect()->back()->with('error', 'Company session expired');
            }

            $query = HomeConnectionCustomer::where('company_id', $companyId);
            
            // Filter by business unit if provided
            if ($request->has('business_unit') && in_array($request->input('business_unit'), ['GESL', 'LINFRA'])) {
                $query->where('business_unit', $request->input('business_unit'));
            }
            
            $customers = $query->orderBy('created_at', 'desc')->get();

            // Debug: Log export details
            Log::info('Export request details', [
                'company_id' => $companyId,
                'business_unit_filter' => $request->input('business_unit'),
                'total_customers_found' => $customers->count()
            ]);

            $filename = 'customers_export_' . date('Y-m-d_H-i-s') . '.csv';
            $tempFile = tempnam(sys_get_temp_dir(), $filename);
            $handle = fopen($tempFile, 'w');

            // Write the header row
            $headers = ['MSISDN', 'Customer Name', 'Email', 'Contact Number', 'Connection Type', 'Location', 'GPS Address', 'Latitude', 'Longitude', 'Status', 'Business Unit', 'Created At'];
            fputcsv($handle, $headers);

            // Write customer data
            foreach ($customers as $customer) {
                $row = [
                    $customer->msisdn,
                    $customer->customer_name,
                    $customer->email,
                    $customer->contact_number,
                    $customer->connection_type,
                    $customer->location,
                    $customer->gps_address,
                    $customer->latitude,
                    $customer->longitude,
                    $customer->status,
                    $customer->business_unit,
                    $customer->created_at->format('Y-m-d H:i:s')
                ];
                fputcsv($handle, $row);
            }

            fclose($handle);

            // Return file download response
            return response()->download($tempFile, $filename, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error exporting customers: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Unable to export customers'], 500);
            }
            return back()->with('error', 'Unable to export customers');
        }
    }

    public function exportAssignments(Request $request)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->back()->with('error', 'Company session expired');
            }

            $filters = $this->extractAssignmentFilters($request);
            $assignmentQuery = $this->buildFilteredAssignmentQuery($companyId, $filters);

            $assignments = (clone $assignmentQuery)
                ->orderBy('created_at', 'desc')
                ->get();

            $format = strtolower($request->input('export_format', 'excel'));
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');

            if ($format === 'pdf') {
                $pdf = Pdf::loadView('company.ProjectManagement.exports.home_connection_assignments', [
                    'assignments' => $assignments,
                    'filters' => $filters,
                    'generatedAt' => Carbon::now(),
                ])->setPaper('A4', 'landscape');

                return $pdf->download("home_connection_assignments_{$timestamp}.pdf");
            }

            $export = new HomeConnectionAssignmentsExport($assignments);
            return Excel::download($export, "home_connection_assignments_{$timestamp}.xlsx");
        } catch (\Exception $e) {
            Log::error('Error exporting assignments: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Unable to export assignments at this time.');
        }
    }

    /**
     * Bulk upload customers from a CSV file
     */
    public function bulkUpload(Request $request)
    {
        try {
            Log::info('Bulk upload method called', [
                'auth_check' => Auth::check(),
                'user_id' => Auth::id(),
                'session_company_id' => Session::get('selected_company_id'),
                'request_data' => $request->all()
            ]);

            if (!Auth::check()) {
                Log::warning('Bulk upload failed: User not authenticated');
                return redirect()->back()->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                Log::warning('Bulk upload failed: No company session');
                return redirect()->back()->with('error', 'Company session expired');
            }

            $authUserId = $this->getAuthenticatedUserId() ?? Auth::id();

            $request->validate([
                'bulk_upload_file' => 'required|file|mimes:csv,txt|max:2048',
                'business_unit' => 'required|in:GESL,LINFRA'
            ]);

            $file = $request->file('bulk_upload_file');
            $handle = fopen($file->getPathname(), 'r');
            $headers = fgetcsv($handle);
            
            $requiredHeaders = ['MSISDN', 'Customer Name', 'Contact Number', 'Connection Type', 'Location', 'Status', 'Business Unit'];
            foreach ($requiredHeaders as $requiredHeader) {
                if (!in_array($requiredHeader, $headers)) {
                    return redirect()->back()->with('error', "Missing required column: $requiredHeader");
                }
            }

            $successCount = 0;
            $failedCustomers = [];
            $assignmentSuccessCount = 0;
            $assignmentFailures = [];

            $selectedBusinessUnit = $request->input('business_unit');
            $autoScheduleEnabled = $request->input('auto_schedule_enabled') === 'true';
            $activeTeams = collect();
            $noActiveTeamsForAutoSchedule = false;

            if ($autoScheduleEnabled) {
                $activeTeams = TeamParing::where('company_id', $companyId)
                    ->whereRaw('LOWER(team_status) = ?', ['active'])
                    ->get();

                if ($activeTeams->isEmpty()) {
                    $noActiveTeamsForAutoSchedule = true;
                    Log::warning('Bulk upload auto-schedule skipped: No active teams found', [
                        'company_id' => $companyId
                    ]);
                }
            }
            
            Log::info('Starting bulk upload processing', [
                'selected_business_unit' => $selectedBusinessUnit,
                'company_id' => $companyId,
                'user_id' => $authUserId,
                'auto_schedule_enabled' => $autoScheduleEnabled
            ]);
            
            while (($row = fgetcsv($handle)) !== false) {
                $customerData = array_combine($headers, $row);
                $customerData['company_id'] = $companyId;
                $customerData['created_by'] = $authUserId;
                $customerData['updated_by'] = $authUserId;
                $customerData['Business Unit'] = $selectedBusinessUnit; // Override with selected business unit

                $validator = Validator::make($customerData, [
                    'MSISDN' => [
                        'required',
                        'string',
                        'max:20',
                        Rule::unique('home_connection_customers', 'msisdn')->where('company_id', $companyId)
                    ],
                    'Customer Name' => 'required|string|max:255',
                    'Email' => 'nullable|email|max:255',
                    'Contact Number' => 'required|string|max:20',
                    'Connection Type' => 'required|in:Traditional,Quick ODN',
                    'Location' => 'required|string|max:255',
                    'GPS Address' => 'nullable|string',
                    'Latitude' => 'nullable|numeric|between:-90,90',
                    'Longitude' => 'nullable|numeric|between:-180,180',
                    'Status' => 'required|in:Active,Inactive,Pending,Schedule',
                    'Business Unit' => 'required|in:GESL,LINFRA'
                ]);

                if ($validator->fails()) {
                    $failedCustomers[] = [
                        'data' => $customerData,
                        'errors' => $validator->errors()->all()
                    ];
                    continue;
                }

                // Format MSISDN to include +233 prefix if not present
                $msisdn = $customerData['MSISDN'];
                if (strpos($msisdn, '+233') !== 0) {
                    $msisdn = '+233' . $msisdn;
                }
                
                // Format contact number to include +233 prefix if not present
                $contactNumber = $customerData['Contact Number'];
                if (strpos($contactNumber, '+233') !== 0) {
                    $contactNumber = '+233' . $contactNumber;
                }
                
                // Combine latitude and longitude into gps_coordinates
                $gpsCoordinates = null;
                if (!empty($customerData['Latitude']) && !empty($customerData['Longitude'])) {
                    // Keep latitude and longitude as separate fields since gps_coordinates doesn't exist in DB
                    // $gpsCoordinates = $customerData['Latitude'] . ',' . $customerData['Longitude'];
                }

                try {
                    $customer = HomeConnectionCustomer::create([
                        'msisdn' => $msisdn,
                        'customer_name' => $customerData['Customer Name'],
                        'email' => $customerData['Email'] ?? null,
                        'contact_number' => $contactNumber,
                        'connection_type' => $customerData['Connection Type'],
                        'location' => $customerData['Location'],
                        'gps_address' => $customerData['GPS Address'] ?? null,
                        'latitude' => $customerData['Latitude'] ?? null,
                        'longitude' => $customerData['Longitude'] ?? null,
                        'status' => $autoScheduleEnabled ? 'Pending' : $customerData['Status'],
                        'business_unit' => $customerData['Business Unit'],
                        'company_id' => $companyId,
                        'created_by' => $authUserId,
                        'updated_by' => $authUserId
                    ]);

                    $successCount++;

                    if ($autoScheduleEnabled && !$noActiveTeamsForAutoSchedule && $activeTeams->isNotEmpty()) {
                        try {
                            $randomTeam = $activeTeams->random();
                            $nextDay9AM = \Carbon\Carbon::tomorrow()->setTime(9, 0, 0);

                            $assignment = new SiteAssignment();
                            $assignment->company_id = $companyId;
                            $assignment->customer_id = $customer->id;
                            $assignment->team_id = $randomTeam->id;
                            $assignment->assigned_by = $authUserId;
                            $assignment->assignment_title = 'Auto-Scheduled Assignment';
                            $assignment->description = 'Automatically created assignment for bulk uploaded customer: ' . $customer->customer_name;
                            $assignment->status = SiteAssignment::STATUS_PENDING;
                            $assignment->priority = SiteAssignment::PRIORITY_HIGH;
                            $assignment->assigned_date = $nextDay9AM;
                            $assignment->has_issue = false;
                            $assignment->save();

                            $customer->status = 'Schedule';
                            $customer->save();

                            $assignmentSuccessCount++;

                            Log::info('Bulk auto-scheduled assignment created', [
                                'customer_id' => $customer->id,
                                'team_id' => $randomTeam->id,
                                'team_name' => $randomTeam->team_name,
                                'assigned_date' => $nextDay9AM->toDateTimeString()
                            ]);
                        } catch (\Exception $assignmentException) {
                            $assignmentFailures[] = [
                                'customer' => $customer->customer_name,
                                'msisdn' => $customer->msisdn,
                                'error' => $assignmentException->getMessage()
                            ];

                            Log::error('Error auto-scheduling customer during bulk upload', [
                                'customer_id' => $customer->id,
                                'error' => $assignmentException->getMessage()
                            ]);
                        }
                    }
                } catch (\Exception $creationException) {
                    $failedCustomers[] = [
                        'data' => $customerData,
                        'errors' => [$creationException->getMessage()]
                    ];

                    Log::error('Error creating customer during bulk upload', [
                        'msisdn' => $msisdn,
                        'error' => $creationException->getMessage()
                    ]);
                }
            }

            fclose($handle);

            $message = "Successfully imported $successCount customers.";
            if (!empty($failedCustomers)) {
                $message .= " " . count($failedCustomers) . " customers failed to import.";
                Log::warning('Bulk customer import partial failure', ['failed_imports' => $failedCustomers]);
            }

            if ($autoScheduleEnabled) {
                if ($assignmentSuccessCount > 0) {
                    $message .= " Auto-scheduled {$assignmentSuccessCount} customers.";
                }

                if ($noActiveTeamsForAutoSchedule) {
                    $message .= " Auto-scheduling skipped because no active teams were available.";
                } elseif (!empty($assignmentFailures)) {
                    $message .= ' ' . count($assignmentFailures) . ' customers could not be auto-scheduled.';
                    Log::warning('Bulk auto-schedule partial failure', ['assignment_failures' => $assignmentFailures]);
                }
            }

            Log::info('Bulk upload completed', [
                'success_count' => $successCount,
                'failed_count' => count($failedCustomers),
                'auto_schedule_enabled' => $autoScheduleEnabled,
                'auto_schedule_success_count' => $assignmentSuccessCount,
                'auto_schedule_failure_count' => count($assignmentFailures),
                'message' => $message
            ]);

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Bulk upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Bulk upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Schedule appointment for a customer
     */
    public function scheduleAppointment(Request $request, $id)
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            $customer = HomeConnectionCustomer::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            $validatedData = $request->validate([
                'appointment_date' => 'required|date|after:now',
                'assigned_engineer' => 'required|string|max:255',
                'purpose' => 'required|string|max:500',
                'notes' => 'nullable|string|max:1000'
            ]);

            // Update customer status to Schedule
            $customer->update([
                'status' => 'Schedule',
                'updated_by' => Auth::id()
            ]);

            // Here you would typically create an appointment record
            // For now, we'll just log the appointment data
            Log::info('Appointment scheduled', [
                'customer_id' => $customer->id,
                'customer_name' => $customer->customer_name,
                'appointment_data' => $validatedData
            ]);

            return redirect()->route('project-management.customers.index')
                ->with('success', 'Appointment scheduled successfully');
        } catch (\Exception $e) {
            Log::error('Error scheduling appointment: ' . $e->getMessage());
            return back()->with('error', 'Unable to schedule appointment');
        }
    }

    /**
     * Resolve the authenticated user ID across supported guards.
     */
    /**
     * Send customer appointment details via SMS.
     */
    public function sendSms(HomeConnectionCustomer $customer): JsonResponse
    {
        try {
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId || $companyId !== $customer->company_id) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Get customer's phone number
            $rawPhoneNumber = $customer->contact_number ?? $customer->msisdn;
            $phoneNumber = $rawPhoneNumber ? preg_replace('/\s+/', '', trim($rawPhoneNumber)) : null;
            
            if (empty($phoneNumber)) {
                return response()->json([
                    'error' => 'Customer has no phone number configured'
                ], 400);
            }

            // Ensure Ghana country code prefix just like we do for company sub users
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '+233' . substr($phoneNumber, 1);
            } elseif (!str_starts_with($phoneNumber, '+') && !str_starts_with($phoneNumber, '233')) {
                $phoneNumber = '+233' . ltrim($phoneNumber, '+');
            } elseif (str_starts_with($phoneNumber, '233')) {
                $phoneNumber = '+' . $phoneNumber;
            }

            // Get latest assignment for appointment details
            $latestAssignment = SiteAssignment::where('customer_id', $customer->id)
                ->where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->first();

            // Prepare message
            $message = "Your GESL appointment details:\n";
            $message .= "Customer: {$customer->customer_name}\n";
            $message .= "Location: {$customer->location}\n";
            $message .= "Phone: {$phoneNumber}\n";
            
            if ($latestAssignment) {
                if ($latestAssignment->assigned_date) {
                    $appointmentDate = \Carbon\Carbon::parse($latestAssignment->assigned_date);
                    $message .= "Appointment Date: " . $appointmentDate->format('M d, Y') . "\n";
                    $message .= "Appointment Time: " . $appointmentDate->format('h:i A') . "\n";
                }
                
                if ($latestAssignment->team && $latestAssignment->team->team_name) {
                    $message .= "Assigned Team: {$latestAssignment->team->team_name}\n";
                }
                
                $message .= "Status: " . ucfirst($latestAssignment->status) . "\n";
            } else {
                $message .= "Status: Pending\n";
            }
            
            $message .= "\nThank you for choosing our services!";

            $payload = [
                'action' => 'send-sms',
                'api_key' => 'SWpvdEx1bGtNSXl2Tk9JT0ZxdG0=',
                'to' => $phoneNumber,
                'from' => 'SHRINQ',
                'sms' => $message,
            ];

            // Short-circuit in local/testing so devs aren't blocked by SMS gateway outages
            if (app()->environment(['local', 'testing'])) {
                Log::info('SMS send for GESL (local/testing)', [
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->customer_name,
                    'phone' => $phoneNumber,
                    'payload' => $payload,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'SMS send  for ' . $customer->customer_name
                ]);
            }

            // Send SMS using the same API as CompanySubUserController with retry/backoff
            $response = Http::retry(3, 500)
                ->timeout(10)
                ->get('https://sms.shrinqghana.com/sms/api', $payload);

            if (!$response->successful()) {
                Log::error('SMS gateway responded with error', [
                    'customer_id' => $customer->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'error' => 'SMS service unavailable, please try again later.'
                ], 502);
            }

            Log::info('SMS sent to customer', [
                'customer_id' => $customer->id,
                'customer_name' => $customer->customer_name,
                'phone' => $phoneNumber,
                'response' => $response->json()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SMS sent successfully to ' . $customer->customer_name
            ]);

        } catch (ConnectionException $e) {
            Log::error('SMS gateway connection exception', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Unable to reach SMS service, please try again later.'
            ], 503);
        } catch (\Exception $e) {
            Log::error('Error sending SMS to customer', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to send SMS'
            ], 500);
        }
    }

    private function extractAssignmentFilters(Request $request): array
    {
        return [
            'date_from' => $request->input('filter_date_from'),
            'date_to' => $request->input('filter_date_to'),
            'team_id' => $request->input('filter_team'),
            'location' => $request->input('filter_location'),
            'connection_type' => $request->input('filter_connection_type'),
            'issue' => $request->input('filter_issue'),
        ];
    }

    private function buildFilteredAssignmentQuery(int $companyId, array $filters): Builder
    {
        $query = SiteAssignment::where('company_id', $companyId)
            ->with(['customer', 'team.teamMembers', 'assignedBy', 'resolvedBy']);

        return $this->applyAssignmentFilters($query, $filters);
    }

    private function applyAssignmentFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['team_id']) && $filters['team_id'] !== 'all') {
            $query->where('team_id', $filters['team_id']);
        }

        if (!empty($filters['location'])) {
            $query->whereHas('customer', function ($customerQuery) use ($filters) {
                $customerQuery->where('location', $filters['location']);
            });
        }

        if (!empty($filters['connection_type'])) {
            $query->whereHas('customer', function ($customerQuery) use ($filters) {
                $customerQuery->where('connection_type', $filters['connection_type']);
            });
        }

        if (!empty($filters['issue'])) {
            switch ($filters['issue']) {
                case 'with_issue':
                    $query->where('has_issue', true)
                        ->where(function ($issueQuery) {
                            $issueQuery->whereNull('issue_status')
                                ->orWhere('issue_status', '!=', SiteAssignment::ISSUE_STATUS_RESOLVED);
                        });
                    break;
                case 'without_issue':
                    $query->where(function ($issueQuery) {
                        $issueQuery->whereNull('has_issue')
                            ->orWhere('has_issue', false)
                            ->orWhere('issue_status', SiteAssignment::ISSUE_STATUS_RESOLVED);
                    });
                    break;
                case 'resolved_issue':
                    $query->where('issue_status', SiteAssignment::ISSUE_STATUS_RESOLVED);
                    break;
            }
        }

        if (!empty($filters['date_from'])) {
            try {
                $from = Carbon::parse($filters['date_from'])->startOfDay();
                $query->whereRaw('COALESCE(assigned_date, created_at) >= ?', [$from->format('Y-m-d H:i:s')]);
            } catch (\Exception $e) {
                Log::warning('Invalid assignment filter date_from', [
                    'value' => $filters['date_from'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if (!empty($filters['date_to'])) {
            try {
                $to = Carbon::parse($filters['date_to'])->endOfDay();
                $query->whereRaw('COALESCE(assigned_date, created_at) <= ?', [$to->format('Y-m-d H:i:s')]);
            } catch (\Exception $e) {
                Log::warning('Invalid assignment filter date_to', [
                    'value' => $filters['date_to'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $query;
    }

    private function getAssignmentFilterOptions(int $companyId): array
    {
        $locations = HomeConnectionCustomer::where('company_id', $companyId)
            ->whereNotNull('location')
            ->orderBy('location')
            ->distinct()
            ->pluck('location')
            ->filter()
            ->values()
            ->all();

        $connectionTypes = HomeConnectionCustomer::where('company_id', $companyId)
            ->whereNotNull('connection_type')
            ->distinct()
            ->pluck('connection_type')
            ->filter()
            ->values()
            ->all();

        if (empty($connectionTypes)) {
            $connectionTypes = ['Traditional', 'Quick ODN'];
        }

        return [
            'locations' => $locations,
            'connection_types' => $connectionTypes,
            'issue_options' => [
                '' => 'All Issues',
                'with_issue' => 'With Issues',
                'without_issue' => 'Without Issues',
                'resolved_issue' => 'Resolved Issues',
            ],
        ];
    }

    private function getAuthenticatedUserId(): ?int
    {
        if (Auth::guard('company_sub_user')->check()) {
            return Auth::guard('company_sub_user')->id();
        }

        if (Auth::guard('sub_user')->check()) {
            return Auth::guard('sub_user')->id();
        }

        if (Auth::check()) {
            return Auth::id();
        }

        return null;
    }
}
