<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DepartmentCategory;
use App\Models\Requisition;
use App\Models\CentralStore;
use App\Models\Employee;
use App\Models\HrEmploymentPersonalInfo;
use App\Models\ProjectManagement\Project;
use App\Models\TeamParing;
use App\Models\TeamMember;
use App\Models\CompanySubUser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RequisitionController extends Controller
{
    /**
     * Generate unique reference code
     */
    private function generateUniqueReferenceCode()
    {
        $referenceCode = 'REF-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $attempts = 0;
        
        while (Requisition::where('reference_code', $referenceCode)->exists() && $attempts < 10) {
            $referenceCode = 'REF-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            $attempts++;
        }
        
        return $referenceCode;
    }

    private function generateRequisitionNumber()
    {
        $date = date('Ymd');
        $sequence = Requisition::whereDate('created_at', today())->count() + 1;
        return 'PR-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get warehouse departments for requisition form
     */
    public function getWarehouseDepartments()
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // If no company_id in session, try to use company_id = 1 as fallback
            if (!$companyId) {
                $companyId = 1; // Fallback for testing
                \Log::warning('No company_id in session, using fallback company_id = 1');
            }

            \Log::info('Fetching warehouse departments for company_id: ' . $companyId);

            // Fetch departments where name contains 'warehouse' (case insensitive)
            $warehouseDepartments = DepartmentCategory::where('company_id', $companyId)
                ->where('status', 'active')
                ->where(function($query) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%warehouse%'])
                          ->orWhereRaw('LOWER(name) LIKE ?', ['%store%'])
                          ->orWhereRaw('LOWER(name) LIKE ?', ['%inventory%']);
                })
                ->get();


            // Process departments to include only sub-departments
            $processedDepartments = [];
            foreach ($warehouseDepartments as $department) {
                // Get sub-departments from the sub_departments field (JSON array)
                $subDepartments = is_array($department->sub_departments) 
                    ? $department->sub_departments 
                    : json_decode($department->sub_departments ?? '[]', true);
                
                if ($subDepartments && is_array($subDepartments)) {
                    foreach ($subDepartments as $index => $subDeptName) {
                        // Sub-department names are stored as simple strings
                        
                        $processedDepartments[] = [
                            'id' => $department->id . '_sub_' . $index,
                            'name' => $subDeptName,
                            'code' => $department->code . '_' . ($index + 1),
                            'type' => 'sub',
                            'parent_id' => $department->id,
                            'parent_name' => $department->name
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'departments' => $processedDepartments,
                'debug_info' => [
                    'company_id' => $companyId,
                    'main_departments_count' => $warehouseDepartments->count(),
                    'total_options_count' => count($processedDepartments)
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching warehouse departments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching departments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all departments for requisition form (fallback)
     */
    public function getAllDepartments()
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // If no company_id in session, try to use company_id = 1 as fallback
            if (!$companyId) {
                $companyId = 1; // Fallback for testing
                \Log::warning('No company_id in session for getAllDepartments, using fallback company_id = 1');
            }

            \Log::info('Fetching all departments for company_id: ' . $companyId);

            // Fetch all active departments
            $departments = DepartmentCategory::where('company_id', $companyId)
                ->where('status', 'active')
                ->select('id', 'name', 'code')
                ->orderBy('name', 'asc')
                ->get();

            \Log::info('Found ' . $departments->count() . ' total departments');

            return response()->json([
                'success' => true,
                'departments' => $departments,
                'debug_info' => [
                    'company_id' => $companyId,
                    'count' => $departments->count()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching all departments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching departments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug method to test department fetching
     */
    public function debugDepartments()
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // Get all departments for debugging
            $allDepartments = DepartmentCategory::where('company_id', $companyId)->get();
            
            // Get warehouse departments
            $warehouseDepartments = DepartmentCategory::where('company_id', $companyId)
                ->where('status', 'active')
                ->where(function($query) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%warehouse%'])
                          ->orWhereRaw('LOWER(name) LIKE ?', ['%store%'])
                          ->orWhereRaw('LOWER(name) LIKE ?', ['%inventory%']);
                })
                ->get();

            return response()->json([
                'success' => true,
                'debug_info' => [
                    'company_id' => $companyId,
                    'total_departments' => $allDepartments->count(),
                    'warehouse_departments' => $warehouseDepartments->count(),
                    'all_departments' => $allDepartments->map(function($dept) {
                        return [
                            'id' => $dept->id,
                            'name' => $dept->name,
                            'status' => $dept->status
                        ];
                    }),
                    'warehouse_departments_list' => $warehouseDepartments->map(function($dept) {
                        return [
                            'id' => $dept->id,
                            'name' => $dept->name,
                            'status' => $dept->status
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Debug error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Store a new requisition
     */
    public function store(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            
            // Debug logging
            Log::info('Requisition store request', [
                'request_data' => $request->all(),
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);
            
            // Debug: Log all incoming data
            Log::info('Requisition form data received:', $request->all());
            
            // Check if requester_id is provided, if not we need to handle this case
            $requesterId = $request->requester_id;
            
            if (!$requesterId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requester is required. Please select a requester from the dropdown.'
                ], 422);
            }
            
            // Validate the request
            $request->validate([
                'title' => 'required|string|max:255',
                'requester_id' => 'required|exists:company_sub_users,id',
                'project_manager_id' => 'nullable|exists:company_sub_users,id',
                'team_leader_id' => 'nullable|exists:team_members,id', // Changed to check team_members table
                'department_id' => 'required|string',
                'priority' => 'required|in:low,medium,high,urgent',
                'requisition_date' => 'required|date',
                'required_date' => 'required|date|after_or_equal:requisition_date',
                'notes' => 'nullable|string',
                'items' => 'required|string', // JSON string
                'attachments.*' => 'nullable|file|max:10240', // 10MB max
            ]);

            // Parse items JSON
            $items = json_decode($request->items, true);
            if (!$items || !is_array($items)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid items data'
                ], 400);
            }

            // Handle department_id and department name
            $departmentIdOriginal = $request->department_id;
            $departmentId = $departmentIdOriginal;
            $departmentName = '';
            
            
            if (strpos($departmentIdOriginal, '_sub_') !== false) {
                // Extract parent department ID from sub-department format (e.g., "2_sub_0" -> "2")
                $departmentId = explode('_sub_', $departmentIdOriginal)[0];
                
                // Find the sub-department name from the warehouse departments
                try {
                    $warehouseDepartments = DepartmentCategory::where('company_id', $companyId)
                        ->where('status', 'active')
                        ->where(function($query) {
                            $query->whereRaw('LOWER(name) LIKE ?', ['%warehouse%'])
                                  ->orWhereRaw('LOWER(name) LIKE ?', ['%store%'])
                                  ->orWhereRaw('LOWER(name) LIKE ?', ['%inventory%']);
                        })
                        ->get();
                    
                    foreach ($warehouseDepartments as $dept) {
                        // Check sub_departments field (your data structure)
                        $subDepartments = is_array($dept->sub_departments) 
                            ? $dept->sub_departments 
                            : json_decode($dept->sub_departments ?? '[]', true);
                            
                        if ($subDepartments && is_array($subDepartments)) {
                            foreach ($subDepartments as $index => $subDeptName) {
                                $subId = $dept->id . '_sub_' . $index;
                                if ($subId === $departmentIdOriginal) {
                                    $departmentName = $subDeptName;
                                    break 2;
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to get sub-department name', ['error' => $e->getMessage()]);
                }
            } else {
                // For regular departments (not sub-departments), get the actual department name
                try {
                    $department = DepartmentCategory::find($departmentId);
                    if ($department) {
                        $departmentName = $department->name;
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to get department name', ['error' => $e->getMessage()]);
                    $departmentName = 'Unknown Department';
                }
            }

            // If department name is still empty, use a fallback
            if (empty($departmentName)) {
                // Try to get any department name as fallback
                try {
                    $department = DepartmentCategory::find($departmentId);
                    if ($department) {
                        $departmentName = $department->name;
                    } else {
                        $departmentName = 'Department ID: ' . $departmentId;
                    }
                } catch (\Exception $e) {
                    $departmentName = 'Department ID: ' . $departmentId;
                }
            }

            // Handle department enum constraint - map to allowed values
            $allowedDepartments = ['GPON', 'Home Connection'];
            if (!in_array($departmentName, $allowedDepartments)) {
                // Map to closest match or use default
                if (stripos($departmentName, 'gpon') !== false || stripos($departmentName, 'network') !== false) {
                    $departmentName = 'GPON';
                } elseif (stripos($departmentName, 'home') !== false || stripos($departmentName, 'connection') !== false) {
                    $departmentName = 'Home Connection';
                } else {
                    $departmentName = 'GPON'; // Default fallback
                }
            }


            // Calculate total amount
            $totalAmount = 0;
            foreach ($items as $item) {
                $totalAmount += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            }

            // Handle file uploads
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('requisitions/attachments', $filename, 'public');
                    $attachments[] = $path;
                }
            }

            // Create requisition with proper fields
            $requisition = Requisition::create([
                'company_id' => $companyId,
                'title' => $request->title,
                'requester_id' => $requesterId,
                'project_manager_id' => $request->project_manager_id,
                'team_leader_id' => $request->team_leader_id,
                'department' => $departmentName,
                'priority' => $request->priority,
                'status' => $request->is_draft ? 'draft' : 'created',
                'notes' => $request->notes,
                'items' => $items,
                'attachments' => $attachments,
                'requisition_number' => $this->generateRequisitionNumber(),
                'reference_code' => $this->generateUniqueReferenceCode(),
            ]);


            // Note: Inventory quantities are now deducted only when requisition is approved
            // This allows for easy editing of pending requisitions without inventory complications

            Log::info('Requisition created successfully', [
                'requisition_id' => $requisition->id,
                'requisition_number' => $requisition->requisition_number,
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => $request->is_draft ? 'Draft saved successfully!' : 'Requisition submitted successfully!',
                'requisition' => $requisition,
                'requisition_number' => $requisition->requisition_number
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error creating requisition', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating requisition', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create requisition: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get requisitions for the current company
     */
    public function index(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            
            // Get pagination parameters
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            
            $query = Requisition::with(['requestor.personalInfo', 'departmentCategory', 'approver'])
                ->where('company_id', $companyId);
            
            // Add search functionality
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('requisition_number', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%")
                      ->orWhere('priority', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%");
                });
            }
            
            $requisitions = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $requisitions->items(),
                'pagination' => [
                    'current_page' => $requisitions->currentPage(),
                    'last_page' => $requisitions->lastPage(),
                    'per_page' => $requisitions->perPage(),
                    'total' => $requisitions->total(),
                    'from' => $requisitions->firstItem(),
                    'to' => $requisitions->lastItem(),
                    'has_more_pages' => $requisitions->hasMorePages(),
                    'prev_page' => $requisitions->currentPage() > 1 ? $requisitions->currentPage() - 1 : null,
                    'next_page' => $requisitions->hasMorePages() ? $requisitions->currentPage() + 1 : null,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching requisitions', [
                'error' => $e->getMessage(),
                'company_id' => Session::get('selected_company_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch requisitions'
            ], 500);
        }
    }

    /**
     * Show a specific requisition
     */
    public function show($id)
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            
            $requisition = Requisition::with(['requestor.personalInfo', 'departmentCategory', 'approver', 'teamLeader'])
                ->where('company_id', $companyId)
                ->findOrFail($id);

            // Add current available quantities and status for each item
            if ($requisition->items && is_array($requisition->items)) {
                $items = $requisition->items;
                foreach ($items as &$item) {
                    // Get current available quantity from CentralStore
                    $centralStoreItem = CentralStore::find($item['item_id']);
                    if ($centralStoreItem) {
                        $requestedQuantity = (float) ($item['quantity'] ?? 0);
                        $availableQuantity = (float) $centralStoreItem->quantity;
                        
                        // Just show the current available quantity in inventory
                        $item['current_available_quantity'] = $availableQuantity;
                        
                        // For partially approved requisitions, add item status information
                        if ($requisition->status === 'partially_approved') {
                            if ($requestedQuantity <= $availableQuantity) {
                                // Item was fully approved
                                $item['status'] = 'approved';
                                $item['quantity_approved'] = $requestedQuantity;
                                $item['quantity_pending'] = 0;
                            } else {
                                // Item was partially approved (some available, some need re-order)
                                $item['status'] = 'partial';
                                $item['quantity_approved'] = $availableQuantity;
                                $item['quantity_pending'] = $requestedQuantity - $availableQuantity;
                            }
                        } else {
                            // For other statuses, set default values
                            $item['status'] = 'pending';
                            $item['quantity_approved'] = 0;
                            $item['quantity_pending'] = $requestedQuantity;
                        }
                        
                        Log::info('Setting item details', [
                            'item_id' => $item['item_id'],
                            'item_name' => $item['item_name'],
                            'current_available' => $availableQuantity,
                            'requested_quantity' => $requestedQuantity,
                            'status' => $item['status'] ?? 'pending',
                            'quantity_approved' => $item['quantity_approved'] ?? 0,
                            'quantity_pending' => $item['quantity_pending'] ?? 0
                        ]);
                    } else {
                        $item['current_available_quantity'] = 0;
                        $item['status'] = 'pending';
                        $item['quantity_approved'] = 0;
                        $item['quantity_pending'] = $item['quantity'] ?? 0;
                        Log::warning('CentralStore item not found', ['item_id' => $item['item_id']]);
                    }
                }
                $requisition->items = $items;
            }

            return response()->json([
                'success' => true,
                'data' => $requisition
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching requisition', [
                'error' => $e->getMessage(),
                'requisition_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Requisition not found'
            ], 404);
        }
    }

    /**
     * Get employees for the requested by dropdown
     */
    public function getUsers()
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            
            Log::info('Fetching employees for company', ['company_id' => $companyId]);
            
            // First, let's check if we have any employees at all
            $totalEmployees = Employee::where('company_id', $companyId)->count();
            Log::info('Total employees found', ['count' => $totalEmployees]);
            
            // Get employees from the same company with their personal info
            $employees = Employee::where('company_id', $companyId)
                ->where('status', 'active')
                ->with('personalInfo')
                ->get();
                
            Log::info('Active employees found', ['count' => $employees->count()]);
            
            // If no active employees, try without status filter
            if ($employees->isEmpty()) {
                Log::info('No active employees, trying without status filter');
                $employees = Employee::where('company_id', $companyId)
                    ->with('personalInfo')
                    ->get();
                Log::info('All employees found', ['count' => $employees->count()]);
            }
            
            $employeeList = $employees->map(function ($employee) {
                $name = $employee->personalInfo ? 
                    trim(($employee->personalInfo->first_name ?? '') . ' ' . ($employee->personalInfo->last_name ?? '')) : 
                    'Employee #' . $employee->staff_id;
                
                // If name is empty, use staff_id
                if (empty($name) || $name === 'Employee #') {
                    $name = 'Employee #' . ($employee->staff_id ?? $employee->id);
                }
                
                return [
                    'id' => $employee->id,
                    'name' => $name,
                    'staff_id' => $employee->staff_id,
                    'email' => $employee->email
                ];
            });

            Log::info('Final employee list', ['employees' => $employeeList->toArray()]);

            return response()->json([
                'success' => true,
                'users' => $employeeList,
                'current_user_id' => Auth::id(),
                'debug' => [
                    'company_id' => $companyId,
                    'total_employees' => $totalEmployees,
                    'filtered_count' => $employeeList->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching employees', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employees: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get statistics for the dashboard
     */
    public function getStatistics()
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            
            // Pending Approvals (requisitions with status pending)
            $pendingApprovals = Requisition::where('company_id', $companyId)
                ->where('status', 'pending')
                ->count();
            
            // Open POs (you might need to adjust this based on your PO model)
            // For now, let's count approved requisitions as "open POs"
            $openPOs = Requisition::where('company_id', $companyId)
                ->where('status', 'approved')
                ->count();
            
            // Monthly Spend (total value of approved/completed requisitions this month)
            // Note: Only counts approved/completed requisitions, not pending ones
            $monthlySpendRequisitions = Requisition::where('company_id', $companyId)
                ->whereIn('status', ['approved', 'completed'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->get();
                
            Log::info('Monthly spend calculation', [
                'company_id' => $companyId,
                'month' => now()->month,
                'year' => now()->year,
                'approved_completed_count' => $monthlySpendRequisitions->count(),
                'total_this_month' => Requisition::where('company_id', $companyId)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count()
            ]);
                
            $monthlySpend = $monthlySpendRequisitions->sum(function($requisition) {
                $total = 0;
                if ($requisition->items && is_array($requisition->items)) {
                    foreach ($requisition->items as $item) {
                        $quantity = $item['quantity'] ?? 0;
                        $unitPrice = $item['unit_price'] ?? 0;
                        $total += $quantity * $unitPrice;
                    }
                }
                return $total;
            });
            
            // Active Suppliers from wh__suppliers table
            $activeSuppliers = 0;
            try {
                // Check if wh__suppliers table exists (the actual suppliers table)
                if (\Schema::hasTable('wh__suppliers')) {
                    $activeSuppliers = \DB::table('wh__suppliers')
                        ->where('company_id', $companyId)
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                        ->count();
                } else {
                    // Fallback: if no suppliers table, show 0
                    $activeSuppliers = 0;
                }
            } catch (\Exception $e) {
                Log::error('Error counting active suppliers', ['error' => $e->getMessage()]);
                $activeSuppliers = 0; // Show 0 instead of arbitrary number
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'pending_approvals' => $pendingApprovals,
                    'open_pos' => $openPOs,
                    'monthly_spend' => round($monthlySpend, 2),
                    'active_suppliers' => $activeSuppliers,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching statistics', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }

    /**
     * Update a requisition
     */
    public function update(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            
            // Find the requisition
            $requisition = Requisition::where('id', $id)
                ->where('company_id', $companyId)
                ->first();

            if (!$requisition) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requisition not found'
                ], 404);
            }

            // Check if requisition can be updated (only pending/draft requisitions)
            if (!in_array($requisition->status, ['pending', 'draft'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update ' . $requisition->status . ' requisitions'
                ], 422);
            }

            // Validate the request
            $request->validate([
                'title' => 'required|string|max:255',
                'department_id' => 'required|string',
                'priority' => 'required|in:low,medium,high,urgent',
                'requester_id' => 'required|exists:company_sub_users,id',
                'project_manager_id' => 'nullable|exists:company_sub_users,id',
                'team_leader_id' => 'nullable|exists:company_sub_users,id',
                'requisition_date' => 'required|date',
                'required_date' => 'required|date|after_or_equal:requisition_date',
                'notes' => 'nullable|string',
                'items' => 'required|string', // JSON string
            ]);

            // Parse items JSON
            $items = json_decode($request->items, true);
            if (!$items || !is_array($items)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid items data'
                ], 400);
            }

            // Handle department_id and department name (same logic as store)
            $departmentIdOriginal = $request->department_id;
            $departmentId = $departmentIdOriginal;
            $departmentName = '';
            
            if (strpos($departmentIdOriginal, '_sub_') !== false) {
                $departmentId = explode('_sub_', $departmentIdOriginal)[0];
                
                try {
                    $warehouseDepartments = DepartmentCategory::where('company_id', $companyId)
                        ->where('status', 'active')
                        ->where(function($query) {
                            $query->whereRaw('LOWER(name) LIKE ?', ['%warehouse%'])
                                  ->orWhereRaw('LOWER(name) LIKE ?', ['%store%'])
                                  ->orWhereRaw('LOWER(name) LIKE ?', ['%inventory%']);
                        })
                        ->get();
                    
                    foreach ($warehouseDepartments as $dept) {
                        $subDepartments = is_array($dept->sub_departments) ? $dept->sub_departments : json_decode($dept->sub_departments ?? '[]', true);
                        if ($subDepartments) {
                            foreach ($subDepartments as $index => $subDeptName) {
                                $subId = $dept->id . '_sub_' . $index;
                                if ($subId === $departmentIdOriginal) {
                                    $departmentName = $subDeptName;
                                    break 2;
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to get sub-department name during update', ['error' => $e->getMessage()]);
                }
            }

            // Restore inventory for old items first
            if ($requisition->items && is_array($requisition->items)) {
                foreach ($requisition->items as $oldItem) {
                    $itemId = $oldItem['item_id'] ?? null;
                    $quantity = $oldItem['quantity'] ?? 0;
                    
                    if ($itemId && $quantity > 0) {
                        $centralStoreItem = CentralStore::find($itemId);
                        if ($centralStoreItem) {
                            $newQuantity = $centralStoreItem->quantity;
                            // $centralStoreItem->update(['quantity' => $newQuantity]);
                        }
                    }
                }
            }

            // Handle attachments
            $attachments = $requisition->attachments ?? [];
            
            // Handle existing attachments (from frontend)
            if ($request->has('existing_attachments')) {
                $existingAttachments = json_decode($request->existing_attachments, true);
                $attachments = is_array($existingAttachments) ? $existingAttachments : [];
            }
            
            // Handle new file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('requisitions/attachments', $filename, 'public');
                    $attachments[] = $path;
                }
            }

            // Handle department enum constraint - map to allowed values
            $allowedDepartments = ['GPON', 'Home Connection'];
            if ($departmentName && !in_array($departmentName, $allowedDepartments)) {
                // Map to closest match or use default
                if (stripos($departmentName, 'gpon') !== false || stripos($departmentName, 'network') !== false) {
                    $departmentName = 'GPON';
                } elseif (stripos($departmentName, 'home') !== false || stripos($departmentName, 'connection') !== false) {
                    $departmentName = 'Home Connection';
                } else {
                    $departmentName = 'GPON'; // Default fallback
                }
            }

            // Update the requisition (explicitly preserve reference_code and requisition_number)
            $requisition->update([
                'title' => $request->title,
                'department_id' => $departmentId,
                'department' => $departmentName ?: $requisition->department,
                'priority' => $request->priority,
                'requester_id' => $request->requester_id,
                'project_manager_id' => $request->project_manager_id,
                'team_leader_id' => $request->team_leader_id,
                'requisition_date' => $request->requisition_date,
                'required_date' => $request->required_date,
                'notes' => $request->notes,
                'items' => $items,
                'attachments' => $attachments,
                // Explicitly preserve these fields during update
                'reference_code' => $requisition->reference_code,
                'requisition_number' => $requisition->requisition_number,
            ]);

            // Note: Inventory quantities are now deducted only when requisition is approved
            // This allows for easy editing of pending requisitions without inventory complications

            Log::info('Requisition updated successfully', [
                'requisition_id' => $id,
                'requisition_number' => $requisition->requisition_number,
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Requisition updated successfully!',
                'requisition' => $requisition->fresh(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating requisition', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'requisition_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update requisition: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a requisition and deduct inventory
     */
    public function approve(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            
            $requisition = Requisition::where('id', $id)
                ->where('company_id', $companyId)
                ->first();

            if (!$requisition) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requisition not found'
                ], 404);
            }

            // Check if requisition can be approved
            if ($requisition->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending requisitions can be approved'
                ], 422);
            }

            DB::beginTransaction();
            
            // Check stock levels and categorize items
            $items = $requisition->items;
            $itemsNeedingReorder = [];
            $itemsWithStock = [];
            $itemsToDeduct = [];
            
            if ($items && is_array($items)) {
                foreach ($items as $item) {
                    $centralStoreItem = \App\Models\CentralStore::find($item['item_id']);
                    if ($centralStoreItem) {
                        $requestedQuantity = (float) ($item['quantity'] ?? 0);
                        $availableQuantity = (float) $centralStoreItem->quantity;
                        
                        if ($requestedQuantity > $availableQuantity) {
                            // Item needs re-order
                            $shortfallQuantity = $requestedQuantity - $availableQuantity;
                            
                            $itemsNeedingReorder[] = [
                                'item_id' => $item['item_id'],
                                'item_name' => $centralStoreItem->item_name,
                                'batch_number' => $centralStoreItem->batch_number,
                                'requested_quantity' => $requestedQuantity,
                                'available_quantity' => $availableQuantity,
                                'shortfall_quantity' => $shortfallQuantity,
                                'unit_price' => $centralStoreItem->unit_price,
                                'central_store_item' => $centralStoreItem
                            ];
                            
                            // If there's some available quantity, deduct it now
                            if ($availableQuantity > 0) {
                                $itemsToDeduct[] = [
                                    'item_id' => $item['item_id'],
                                    'quantity_to_deduct' => $availableQuantity,
                                    'central_store_item' => $centralStoreItem
                                ];
                                
                                $itemsWithStock[] = [
                                    'item_id' => $item['item_id'],
                                    'item_name' => $centralStoreItem->item_name,
                                    'requested_quantity' => $requestedQuantity,
                                    'available_quantity' => $availableQuantity,
                                    'shortfall_quantity' => $shortfallQuantity
                                ];
                            }
                        } else {
                            // Item has sufficient stock - approve immediately
                            $itemsToDeduct[] = [
                                'item_id' => $item['item_id'],
                                'quantity_to_deduct' => $requestedQuantity,
                                'central_store_item' => $centralStoreItem
                            ];
                            
                            $itemsWithStock[] = [
                                'item_id' => $item['item_id'],
                                'item_name' => $centralStoreItem->item_name,
                                'requested_quantity' => $requestedQuantity,
                                'available_quantity' => $availableQuantity,
                                'shortfall_quantity' => 0
                            ];
                        }
                    }
                }
            }
            
            $approvedItemsCount = count($itemsWithStock);
            $reorderItemsCount = count($itemsNeedingReorder);
            
            if ($reorderItemsCount > 0) {
                // Some or all items need re-order
                $createdPO = $this->autoCreateReorderPO($itemsNeedingReorder, $companyId);
                
                if ($createdPO) {
                    if ($approvedItemsCount > 0 && $reorderItemsCount > 0) {
                        // Partial approval
                        $requisition->update([
                            'status' => 'partially_approved',
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);
                        
                        // Deduct inventory for approved items
                        foreach ($itemsToDeduct as $deductionItem) {
                            $centralStoreItem = $deductionItem['central_store_item'];
                            $quantityToDeduct = $deductionItem['quantity_to_deduct'];
                            $newQuantity = $centralStoreItem->quantity - $quantityToDeduct;
                            $centralStoreItem->update(['quantity' => max(0, $newQuantity)]);
                        }
                        
                        DB::commit();
                        return response()->json([
                            'success' => true,
                            'message' => "Requisition partially approved! {$approvedItemsCount} item(s) approved. {$reorderItemsCount} item(s) need re-order - PO {$createdPO->po_number} created.",
                            'approval_type' => 'partial'
                        ]);
                    } else {
                        // All items need re-order
                        DB::commit();
                        return response()->json([
                            'success' => true,
                            'message' => 'All items need re-order. PO created. Requisition remains pending.',
                            'approval_type' => 'full_reorder'
                        ]);
                    }
                } else {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Could not create re-order PO. Please check supplier setup.'
                    ]);
                }
            } else {
                // Full approval: all items have sufficient stock
                $requisition->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

                // Deduct inventory for all items
                foreach ($itemsToDeduct as $deductionItem) {
                    $centralStoreItem = $deductionItem['central_store_item'];
                    $quantityToDeduct = $deductionItem['quantity_to_deduct'];
                    $newQuantity = $centralStoreItem->quantity - $quantityToDeduct;
                    $centralStoreItem->update(['quantity' => max(0, $newQuantity)]);
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Requisition fully approved! All items have sufficient stock.',
                    'approval_type' => 'full'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error approving requisition', [
                'error' => $e->getMessage(),
                'requisition_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve requisition: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a requisition
     */
    public function destroy($id)
    {
        try {
            Log::info('Attempting to delete requisition', ['id' => $id]);
            
            $companyId = Session::get('selected_company_id', 1);
            Log::info('Company ID for deletion', ['company_id' => $companyId]);
            
            // Validate the ID parameter
            if (!$id || !is_numeric($id)) {
                Log::error('Invalid requisition ID provided', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid requisition ID'
                ], 400);
            }
            
            $requisition = Requisition::where('id', $id)
                ->where('company_id', $companyId)
                ->first();
                
            Log::info('Requisition found', ['requisition' => $requisition ? $requisition->toArray() : null]);

            if (!$requisition) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requisition not found'
                ], 404);
            }

            // Check if requisition can be deleted (only drafts and pending can be deleted)
            if (in_array($requisition->status, ['approved', 'completed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete approved or completed requisitions'
                ], 422);
            }

            // Restore inventory quantities for each item in the requisition
            $items = $requisition->items;
            
            // Handle cases where items might be stored as JSON string
            if (is_string($items)) {
                $items = json_decode($items, true);
            }
            
            Log::info('Processing items for inventory restoration', [
                'items_exists' => !empty($items),
                'items_type' => gettype($items),
                'items_count' => is_array($items) ? count($items) : 'not array',
                'raw_items' => $requisition->items
            ]);
            
            if ($items && is_array($items)) {
                foreach ($items as $index => $item) {
                    Log::info("Processing item {$index}", ['item' => $item]);
                    
                    $itemId = $item['item_id'] ?? null;
                    $quantity = $item['quantity'] ?? 0;
                    
                    if ($itemId && $quantity > 0) {
                        Log::info('Restoring inventory for item', [
                            'item_id' => $itemId,
                            'quantity' => $quantity
                        ]);
                        
                        // Find the inventory item and restore quantity
                        $inventoryItem = \DB::table('central_store')
                            ->where('id', $itemId)
                            ->where('company_id', $companyId)
                            ->first();
                            
                        if ($inventoryItem) {
                            \DB::table('central_store')
                                ->where('id', $itemId)
                                ->increment('quantity', $quantity);
                                
                            Log::info('Inventory quantity restored', [
                                'item_id' => $itemId,
                                'item_name' => $inventoryItem->item_name,
                                'restored_quantity' => $quantity,
                                'requisition_id' => $id
                            ]);
                        } else {
                            Log::warning('Inventory item not found for restoration', [
                                'item_id' => $itemId,
                                'company_id' => $companyId
                            ]);
                        }
                    }
                }
            }

            Log::info('About to delete requisition', ['id' => $id]);
            $requisition->delete();
            Log::info('Requisition deleted successfully');

            Log::info('Requisition deleted', [
                'requisition_id' => $id,
                'requisition_number' => $requisition->requisition_number,
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Requisition deleted successfully and inventory quantities restored'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting requisition', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'requisition_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete requisition: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get project managers for requisition form
     * Returns users assigned to Project Manager profile and their assigned sub-users
     */
    public function getProjectManagers()
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            
            Log::info('Fetching project managers for company_id: ' . $companyId);
            
            // First, find the Project Manager profile
            $projectManagerProfile = \App\Models\UserProfile::where('company_id', $companyId)
                ->where(function($query) {
                    $query->where('profile_name', 'Project Manager')
                          ->orWhere('profile_name', 'project manager')
                          ->orWhere('profile_name', 'PROJECT MANAGER')
                          ->orWhereRaw('LOWER(profile_name) LIKE ?', ['%project manager%']);
                })
                ->where('status', 'active')
                ->first();
            
            if (!$projectManagerProfile) {
                Log::info('No Project Manager profile found');
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'debug' => [
                        'company_id' => $companyId,
                        'message' => 'No Project Manager profile found'
                    ]
                ]);
            }
            
            Log::info('Found Project Manager profile: ' . $projectManagerProfile->profile_name);
            
            // Get users assigned to the Project Manager profile
            $projectManagers = CompanySubUser::where('company_id', $companyId)
                ->where('profile_id', $projectManagerProfile->id)
                ->where(function($query) {
                    $query->where('status', true)
                          ->orWhere('status', 'active')
                          ->orWhere('status', 1);
                })
                ->get();
            
            Log::info('Found ' . $projectManagers->count() . ' users with Project Manager profile');
            
            $mappedManagers = collect();
            
            // Add ONLY project managers themselves (not sub-users)
            foreach ($projectManagers as $manager) {
                $mappedManagers->push([
                    'id' => $manager->id,
                    'name' => $manager->fullname,
                    'email' => $manager->email,
                    'position' => $manager->role,
                    'phone' => $manager->phone_number,
                    'type' => 'project_manager',
                    'profile_name' => $projectManagerProfile->profile_name
                ]);
                
                Log::info('Added project manager: ' . $manager->fullname);
            }

            return response()->json([
                'success' => true,
                'data' => $mappedManagers,
                'debug' => [
                    'company_id' => $companyId,
                    'project_manager_profile' => $projectManagerProfile->profile_name,
                    'project_managers_count' => $projectManagers->count(),
                    'total_options' => $mappedManagers->count(),
                    'users' => $mappedManagers->toArray()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching project managers', [
                'error' => $e->getMessage(),
                'company_id' => Session::get('selected_company_id'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch project managers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get team leaders for requisition form
     */
    public function getTeamLeaders()
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            
            // Debug: Log the team leaders being fetched
            Log::info('=== FETCHING TEAM LEADERS ===');
            Log::info('Company ID:', ['company_id' => $companyId]);
            
            // Get team leaders from team_paring table
            $teamParingRecords = TeamParing::where('company_id', $companyId)
                ->whereNotNull('team_lead')
                ->get();
                
            Log::info('Team paring records found:', $teamParingRecords->toArray());
            
            // If no records found, return empty array
            if ($teamParingRecords->isEmpty()) {
                Log::info('No team paring records found for company:', ['company_id' => $companyId]);
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }
            
            $teamLeaders = $teamParingRecords->map(function ($team) {
                try {
                    $teamLeadId = $team->team_lead;
                    
                    Log::info("Processing team: {$team->team_name}, team_lead: {$teamLeadId}");
                    
                    // Try to get team lead user directly from TeamMember table
                    $teamLeadUser = \App\Models\TeamMember::find($teamLeadId);
                    
                    if (!$teamLeadUser) {
                        Log::warning("Team member not found for ID: {$teamLeadId}");
                        return [
                            'id' => $teamLeadId,
                            'name' => 'Team Leader Not Found',
                            'email' => '',
                            'position' => 'Team Leader',
                            'team_name' => $team->team_name
                        ];
                    }
                    
                    Log::info("Team lead user data:", $teamLeadUser->toArray());
                    
                    return [
                        'id' => $teamLeadId,
                        'name' => $teamLeadUser->full_name ?? 'Unknown',
                        'email' => $teamLeadUser->email ?? '',
                        'position' => $teamLeadUser->position ?? 'Team Leader',
                        'team_name' => $team->team_name
                    ];
                } catch (\Exception $e) {
                    Log::error("Error processing team {$team->team_name}:", [
                        'error' => $e->getMessage(),
                        'team_data' => $team->toArray()
                    ]);
                    
                    return [
                        'id' => $team->team_lead,
                        'name' => 'Error loading team leader',
                        'email' => '',
                        'position' => 'Team Leader',
                        'team_name' => $team->team_name
                    ];
                }
            });

            Log::info('Final team leaders data:', $teamLeaders->toArray());

            return response()->json([
                'success' => true,
                'data' => $teamLeaders
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching team leaders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'company_id' => Session::get('selected_company_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch team leaders'
            ], 500);
        }
    }

    /**
     * Reject a requisition
     */
    public function reject(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            $rejectionReason = $request->input('rejection_reason', 'No reason provided');
            
            $requisition = Requisition::where('company_id', $companyId)
                ->where('status', 'pending')
                ->findOrFail($id);

            $requisition->update([
                'status' => 'rejected',
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $rejectionReason,
            ]);

            Log::info('Requisition rejected', [
                'requisition_id' => $id,
                'rejection_reason' => $rejectionReason,
                'rejected_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Requisition has been rejected successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error rejecting requisition', [
                'error' => $e->getMessage(),
                'requisition_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject requisition: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Auto-create re-order PO
     */
    private function autoCreateReorderPO($itemsNeedingReorder, $companyId)
    {
        try {
            // Get the first item to find original PO details
            $firstItem = $itemsNeedingReorder[0];
            $batchNumber = $firstItem['batch_number'];
            
            // Find the QualityInspection record to get the original PO details
            $inspection = \App\Models\QualityInspection::where('batch_number', $batchNumber)
                ->where('company_id', $companyId)
                ->first();
            
            $originalPO = null;
            $supplierId = null;
            
            if ($inspection && $inspection->purchase_order_id) {
                $originalPO = \App\Models\Wh_PurchaseOrder::find($inspection->purchase_order_id);
                if ($originalPO) {
                    $supplierId = $originalPO->supplier_id;
                }
            }
            
            // If not found in QualityInspection, try CentralStore
            if (!$originalPO) {
                $centralStoreItem = \App\Models\CentralStore::where('batch_number', $batchNumber)
                    ->where('company_id', $companyId)
                    ->first();
                
                if ($centralStoreItem && $centralStoreItem->purchase_order_id) {
                    $originalPO = \App\Models\Wh_PurchaseOrder::find($centralStoreItem->purchase_order_id);
                    if ($originalPO) {
                        $supplierId = $originalPO->supplier_id;
                    }
                }
            }
            
            // If still no original PO found, we can't proceed
            if (!$originalPO) {
                return null;
            }
            
            // Generate new PO number
            $datePrefix = date('Ymd');
            $maxNumber = \App\Models\Wh_PurchaseOrder::withTrashed()
                ->where('company_id', $companyId)
                ->where('po_number', 'like', 'PO-'.$datePrefix.'-%')
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(po_number, '-', -1) AS UNSIGNED)) as max_num")
                ->value('max_num');
            $nextNumber = ($maxNumber ? $maxNumber + 1 : 1);
            $poNumber = 'PO-' . $datePrefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            
            // Prepare re-order items
            $reorderItems = collect($itemsNeedingReorder)->map(function($item) {
                return [
                    'name' => $item['item_name'],
                    'quantity' => $item['shortfall_quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['shortfall_quantity'] * $item['unit_price'],
                    'category' => $item['central_store_item']->item_category ?? 'general',
                    'is_reorder' => true,
                    'batch_number' => $item['batch_number'],
                    'reorder_reason' => 'Auto-reorder: Insufficient stock for requisition'
                ];
            });
            
            $subtotal = $reorderItems->sum('total_price');
            
            // Create re-order PO using original PO details
            $reorderPO = \App\Models\Wh_PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $supplierId,
                'order_date' => now(),
                'delivery_date' => now()->addDays(14),
                'status' => 'created',
                'payment_terms' => $originalPO->payment_terms ?? 'Net 30',
                'notes' => 'Auto-generated re-order PO due to insufficient stock (Based on: ' . $originalPO->po_number . ')',
                'requested_by' => Auth::user()->fullname ?? Auth::user()->name ?? 'System',
                'items' => $reorderItems->toArray(),
                'total_value' => $subtotal,
                'total_items' => $reorderItems->count(),
                'created_by' => Auth::id(),
                'company_id' => $companyId,
                'user_id' => Auth::id(),
                'tax_configuration_id' => $originalPO->tax_configuration_id,
                'tax_type' => $originalPO->tax_type,
                'tax_rate' => $originalPO->tax_rate,
                'subtotal' => $subtotal,
                'tax_amount' => $originalPO->tax_configuration_id ? ($subtotal * ($originalPO->tax_rate / 100)) : 0,
                'total_amount' => $originalPO->tax_configuration_id ? ($subtotal + ($subtotal * ($originalPO->tax_rate / 100))) : $subtotal,
                'is_tax_exempt' => $originalPO->is_tax_exempt ?? false,
                'tax_exemption_reason' => $originalPO->tax_exemption_reason,
            ]);
            
            Log::info('Auto-created re-order PO', [
                'po_number' => $poNumber,
                'items_count' => $reorderItems->count(),
                'total_amount' => $reorderPO->total_amount,
                'supplier_id' => $supplierId,
                'original_po_id' => $originalPO->id
            ]);
            
            return $reorderPO;
            
        } catch (\Exception $e) {
            Log::error('Error creating re-order PO', [
                'error' => $e->getMessage(),
                'items_count' => count($itemsNeedingReorder)
            ]);
            return null;
        }
    }
}
