<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\DepartmentCategory;
use App\Models\CompanySubUser;
use App\Http\Requests\Categories\StoreDepartmentCategoryRequest;
use App\Http\Requests\Categories\UpdateDepartmentCategoryRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\View\View;
use Illuminate\Support\Facades\{Auth, Session, DB, Log, Storage, Validator};
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DepartmentCategoriesExport;
use App\Imports\DepartmentCategoriesImport;

class DepartmentCategories extends Controller
{
    /**
     * Display a listing of department categories.
     */
    public function index(): View|RedirectResponse
    {
        try {
            // Check authentication with multiple guards
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isSubUser = Auth::guard('sub_user')->check();
            $isDefaultAuth = Auth::check();
            
            if (!$isCompanySubUser && !$isSubUser && !$isDefaultAuth) {
                return redirect()->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            // Get departments with relationships
            if ($companyId) {
                // Use company scope if company ID is available
                $departments = DepartmentCategory::forCurrentCompany()
                    ->with(['creator:id,fullname', 'updater:id,fullname'])
                    ->orderBySortOrder()
                    ->get();
                $stats = DepartmentCategory::getStats();
            } else {
                // Fallback: Get all departments if no company scope (for development/testing)
                Log::warning('No company ID in session, loading all departments');
                $departments = DepartmentCategory::with(['creator:id,fullname', 'updater:id,fullname'])
                    ->orderBySortOrder()
                    ->get();
                $stats = [
                    'total' => DepartmentCategory::count(),
                    'active' => DepartmentCategory::where('status', 'active')->count(),
                    'inactive' => DepartmentCategory::where('status', 'inactive')->count(),
                    'with_heads' => DepartmentCategory::whereNotNull('head_name')->where('head_name', '!=', '')->count(),
                    'with_sub_departments' => DepartmentCategory::whereNotNull('sub_departments')->where('sub_departments', '!=', '[]')->count(),
                ];
            }

            Log::info('DepartmentCategories@index loaded', [
                'departments_count' => $departments->count(),
                'has_company_id' => !empty($companyId)
            ]);

            return view('company.Categories.category-management', compact(
                'departments',
                'stats'
            ));

        } catch (\Exception $e) {
            Log::error('Error in DepartmentCategories@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading departments.');
        }
    }

    /**
     * Get departments data for DataTables.
     */
    public function getDepartmentsData(Request $request): JsonResponse
    {
        try {
            // Debug session and company ID
            $companyId = Session::get('selected_company_id');
            Log::info('getDepartmentsData - Company ID from session: ' . $companyId);
            
            // Temporarily bypass company scope for debugging
            $query = DepartmentCategory::query()
                ->with(['creator:id,fullname']);

            // Search functionality
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchTerm = $request->search['value'];
                $query->search($searchTerm);
            }

            // Status filter
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            // Total records (temporarily bypass company scope for debugging)
            $totalRecords = DepartmentCategory::count();
            $filteredRecords = $query->count();

            // Ordering
            if ($request->has('order')) {
                $columnIndex = $request->order[0]['column'];
                $columnName = $request->columns[$columnIndex]['data'];
                $direction = $request->order[0]['dir'];
                
                if (in_array($columnName, ['name', 'code', 'status', 'created_at'])) {
                    $query->orderBy($columnName, $direction);
                }
            } else {
                $query->orderBySortOrder();
            }

            // Pagination
            if ($request->has('length') && $request->length != -1) {
                $query->offset($request->start)->limit($request->length);
            }

            $departments = $query->get();
            
            Log::info('getDepartmentsData - Found departments count: ' . $departments->count());
            Log::info('getDepartmentsData - Departments: ', $departments->toArray());

            $data = $departments->map(function ($department) {
                return [
                    'id' => $department->id,
                    'name' => $department->name,
                    'code' => $department->code,
                    'description' => $department->description ? substr($department->description, 0, 100) . (strlen($department->description) > 100 ? '...' : '') : '-',
                    'head_of_department' => $department->head_name ?: '-',
                    'sub_departments' => $department->hasSubDepartments() ? 
                        '<span class="badge bg-info">' . $department->sub_departments_count . ' sub departments</span>' : 
                        '<span class="text-muted">None</span>',
                    'status' => $department->status,
                    'created_at' => $department->created_at->format('Y-m-d H:i:s'),
                    'actions' => $this->generateActionButtons($department)
                ];
            });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getDepartmentsData: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load departments data'], 500);
        }
    }

    /**
     * Store a newly created department category.
     */
    public function store(StoreDepartmentCategoryRequest $request): JsonResponse
    {
        try {
            // Check authentication with multiple guards (following CategoriesManagement pattern)
            if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to continue.'
                ], 401);
            }

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired.'
                ], 401);
            }

            DB::beginTransaction();

            $validatedData = $request->validated();
            
            // Set company_id (following CompanySubUserController pattern)
            $validatedData['company_id'] = $companyId;
            
            // Set created_by only for sub_user or company_sub_user to avoid foreign key constraint
            if (auth('sub_user')->check()) {
                $validatedData['created_by'] = auth('sub_user')->id();
            } elseif (auth('company_sub_user')->check()) {
                $validatedData['created_by'] = auth('company_sub_user')->id();
            }
            // Leave null for regular users to avoid foreign key constraint issues
            
            $department = DepartmentCategory::create($validatedData);

            DB::commit();

            // Get created_by info for logging
            $createdBy = null;
            if (auth('sub_user')->check()) {
                $createdBy = auth('sub_user')->id();
            } elseif (auth('company_sub_user')->check()) {
                $createdBy = auth('company_sub_user')->id();
            } elseif (auth()->check()) {
                $createdBy = auth()->id();
            }

            Log::info('Department created successfully', [
                'department_id' => $department->id,
                'name' => $department->name,
                'created_by' => $createdBy
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully!',
                'data' => [
                    'id' => $department->id,
                    'name' => $department->name,
                    'code' => $department->code
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating department: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create department. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified department category.
     */
    public function show(int $id): JsonResponse
    {
        try {
            // Check authentication
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isSubUser = Auth::guard('sub_user')->check();
            $isDefaultAuth = Auth::check();
            
            if (!$isCompanySubUser && !$isSubUser && !$isDefaultAuth) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 401);
            }

            $companyId = Session::get('selected_company_id');
            
            // Find department with or without company scope
            if ($companyId) {
                $department = DepartmentCategory::forCurrentCompany()
                    ->with(['creator:id,fullname', 'updater:id,fullname', 'creatorUser:id,fullname', 'updaterUser:id,fullname'])
                    ->findOrFail($id);
            } else {
                // Fallback for no company scope (development/testing)
                $department = DepartmentCategory::with(['creator:id,fullname', 'updater:id,fullname', 'creatorUser:id,fullname', 'updaterUser:id,fullname'])
                    ->findOrFail($id);
            }

            // Get current user info for debugging and fallback
            $currentUser = null;
            $currentUserName = 'System';
            $authGuardUsed = 'none';
            
            if (auth('sub_user')->check()) {
                $currentUser = auth('sub_user')->user();
                $currentUserName = $currentUser->fullname ?? $currentUser->name ?? 'Sub User';
                $authGuardUsed = 'sub_user';
            } elseif (auth('company_sub_user')->check()) {
                $currentUser = auth('company_sub_user')->user();
                $currentUserName = $currentUser->fullname ?? $currentUser->name ?? 'Company Sub User';
                $authGuardUsed = 'company_sub_user';
            } elseif (auth()->check()) {
                $currentUser = auth()->user();
                $currentUserName = $currentUser->name ?? $currentUser->fullname ?? 'User';
                $authGuardUsed = 'web';
            }

            // Log authentication debug info
            Log::info('Department view - Authentication Debug', [
                'auth_guard_used' => $authGuardUsed,
                'current_user_id' => $currentUser ? $currentUser->id : null,
                'current_user_name' => $currentUserName,
                'department_created_by' => $department->created_by,
                'department_updated_by' => $department->updated_by,
                'has_creator_relation' => $department->creator ? true : false,
                'has_updater_relation' => $department->updater ? true : false
            ]);

            // Prepare created_by and updated_by info
            $createdBy = null;
            $updatedBy = null;

            if ($department->creator) {
                $createdBy = [
                    'id' => $department->creator->id,
                    'fullname' => $department->creator->fullname,
                    'name' => $department->creator->fullname
                ];
            } elseif ($department->creatorUser) {
                $createdBy = [
                    'id' => $department->creatorUser->id,
                    'fullname' => $department->creatorUser->fullname,
                    'name' => $department->creatorUser->fullname
                ];
            } else {
                // If no creator info exists, show current user name (but don't save to avoid foreign key issues)
                $createdBy = [
                    'id' => null, // Don't store ID to avoid foreign key constraint issues
                    'fullname' => $currentUserName,
                    'name' => $currentUserName
                ];
            }

            if ($department->updater) {
                $updatedBy = [
                    'id' => $department->updater->id,
                    'fullname' => $department->updater->fullname,
                    'name' => $department->updater->fullname
                ];
            } elseif ($department->updaterUser) {
                $updatedBy = [
                    'id' => $department->updaterUser->id,
                    'fullname' => $department->updaterUser->fullname,
                    'name' => $department->updaterUser->fullname
                ];
            } else {
                // If no updater info exists, show current user name (but don't save to avoid foreign key issues)
                $updatedBy = [
                    'id' => null, // Don't store ID to avoid foreign key constraint issues
                    'fullname' => $currentUserName,
                    'name' => $currentUserName
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $department->id,
                    'name' => $department->name,
                    'code' => $department->code,
                    'description' => $department->description,
                    'head_name' => $department->head_name,
                    'status' => $department->status,
                    'color' => $department->color,
                    'sub_departments' => $department->sub_departments ?? [],
                    'sort_order' => $department->sort_order,
                    'created_by' => $createdBy,
                    'updated_by' => $updatedBy,
                    'created_at' => $department->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $department->updated_at->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Department not found for viewing', [
                'department_id' => $id,
                'company_id' => Session::get('selected_company_id')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Department not found or you do not have permission to view it.'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Error showing department: ' . $e->getMessage(), [
                'department_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load department data.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update the specified department category.
     */
    public function update(UpdateDepartmentCategoryRequest $request, int $id): JsonResponse
    {
        try {
            // Check authentication
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isSubUser = Auth::guard('sub_user')->check();
            $isDefaultAuth = Auth::check();
            
            if (!$isCompanySubUser && !$isSubUser && !$isDefaultAuth) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 401);
            }

            DB::beginTransaction();

            $companyId = Session::get('selected_company_id');
            
            // Find department with or without company scope
            if ($companyId) {
                $department = DepartmentCategory::forCurrentCompany()->findOrFail($id);
            } else {
                // Fallback for no company scope (development/testing)
                $department = DepartmentCategory::findOrFail($id);
            }
            
            $validatedData = $request->validated();
            
            // Log the incoming data for debugging
            Log::info('Department update request data', [
                'department_id' => $id,
                'request_data' => $request->all(),
                'validated_data' => $validatedData
            ]);
            
            // Set updated_by only for sub_user or company_sub_user to avoid foreign key constraint
            if (auth('sub_user')->check()) {
                $validatedData['updated_by'] = auth('sub_user')->id();
            } elseif ($isCompanySubUser) {
                $validatedData['updated_by'] = auth('company_sub_user')->id();
            }
            // Leave unchanged for regular users to avoid foreign key constraint issues
            
            $department->update($validatedData);

            DB::commit();

            Log::info('Department updated successfully', [
                'department_id' => $department->id,
                'name' => $department->name,
                'updated_by' => $isCompanySubUser ? auth('company_sub_user')->id() : Auth::id(),
                'has_company_scope' => !empty($companyId)
            ]);

            return response()->json([
                'success' => true,
                'message' => "Department '{$department->name}' updated successfully!",
                'data' => [
                    'id' => $department->id,
                    'name' => $department->name,
                    'code' => $department->code
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::warning('Department not found for update', [
                'department_id' => $id,
                'company_id' => Session::get('selected_company_id')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Department not found or you do not have permission to update it.'
            ], 404);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating department: ' . $e->getMessage(), [
                'department_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update department. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified department category.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            // Check authentication
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isSubUser = Auth::guard('sub_user')->check();
            $isDefaultAuth = Auth::check();
            
            if (!$isCompanySubUser && !$isSubUser && !$isDefaultAuth) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 401);
            }

            DB::beginTransaction();

            $companyId = Session::get('selected_company_id');
            
            // Find department with or without company scope
            if ($companyId) {
                $department = DepartmentCategory::forCurrentCompany()->findOrFail($id);
            } else {
                // Fallback for no company scope (development/testing)
                $department = DepartmentCategory::findOrFail($id);
            }
            
            // Store department name for logging
            $departmentName = $department->name;
            $departmentCompanyId = $department->company_id;
            
            // Soft delete the department
            $department->delete();

            DB::commit();

            Log::info('Department deleted successfully', [
                'department_id' => $id,
                'name' => $departmentName,
                'company_id' => $departmentCompanyId,
                'deleted_by' => $isCompanySubUser ? auth('company_sub_user')->id() : Auth::id(),
                'has_company_scope' => !empty($companyId)
            ]);

            return response()->json([
                'success' => true,
                'message' => "Department '{$departmentName}' has been deleted successfully!"
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::warning('Department not found for deletion', [
                'department_id' => $id,
                'company_id' => Session::get('selected_company_id')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Department not found or you do not have permission to delete it.'
            ], 404);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting department: ' . $e->getMessage(), [
                'department_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete department. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get department statistics.
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = DepartmentCategory::getStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting department stats: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics.'
            ], 500);
        }
    }

    /**
     * Test export functionality.
     */
    public function exportTest(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id', 1); // Fallback for testing
            
            // Get a simple count
            $count = DepartmentCategory::where('company_id', $companyId)->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Export test successful',
                'company_id' => $companyId,
                'department_count' => $count,
                'auth_guards' => [
                    'company_sub_user' => Auth::guard('company_sub_user')->check(),
                    'sub_user' => Auth::guard('sub_user')->check(),
                    'default' => Auth::check()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export departments to Excel.
     */
    public function export(Request $request)
    {
        try {
            Log::info('Export request initiated', ['request_params' => $request->all()]);
            
            // Check authentication
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isSubUser = Auth::guard('sub_user')->check();
            $isDefaultAuth = Auth::check();
            
            Log::info('Authentication check', [
                'company_sub_user' => $isCompanySubUser,
                'sub_user' => $isSubUser,
                'default_auth' => $isDefaultAuth
            ]);
            
            if (!$isCompanySubUser && !$isSubUser && !$isDefaultAuth) {
                Log::warning('Unauthorized export attempt');
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 401);
            }

            $companyId = Session::get('selected_company_id');
            Log::info('Company ID from session', ['company_id' => $companyId]);
            
            if (!$companyId) {
                Log::warning('No company ID in session');
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired.'
                ], 401);
            }

            // Get format parameter (default to excel)
            $format = strtolower($request->get('format', 'excel'));
            $validFormats = ['csv', 'excel', 'xlsx'];
            
            Log::info('Export format requested', ['format' => $format]);
            
            if (!in_array($format, $validFormats)) {
                Log::warning('Invalid format requested', ['format' => $format]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid export format. Supported formats: ' . implode(', ', $validFormats)
                ], 400);
            }

            // Get query parameters for filtering
            $filters = [
                'status' => $request->get('status'),
                'search' => $request->get('search'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to')
            ];

            Log::info('Filters applied', ['filters' => $filters]);

            // Determine file extension and MIME type
            $extension = $format === 'excel' ? 'xlsx' : $format;
            $timestamp = Carbon::now()->format('Y_m_d_H_i_s');
            $filename = "departments_{$timestamp}.{$extension}";

            Log::info('File details', ['filename' => $filename, 'extension' => $extension]);

            // Create export instance
            Log::info('Creating export instance');
            $export = new DepartmentCategoriesExport($companyId, $filters);
            Log::info('Export instance created successfully');

            // Test the collection first
            $collection = $export->collection();
            Log::info('Export collection test', ['count' => $collection->count()]);

            // Log export activity
            Log::info('Department export initiated', [
                'format' => $format,
                'company_id' => $companyId,
                'filters' => $filters,
                'user_id' => $isCompanySubUser ? auth('company_sub_user')->id() : ($isSubUser ? auth('sub_user')->id() : auth()->id()),
                'filename' => $filename
            ]);

            // Return appropriate format with proper headers
            Log::info('Starting download process');
            switch ($format) {
                case 'csv':
                    Log::info('Downloading as CSV');
                    return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::CSV, [
                        'Content-Type' => 'text/csv',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0'
                    ]);
                    
                case 'excel':
                case 'xlsx':
                default:
                    Log::info('Downloading as Excel');
                    return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX, [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0'
                    ]);
            }

        } catch (\Exception $e) {
            Log::error('Error exporting departments: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to export departments. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Download template for bulk import.
     */
    public function downloadTemplate()
    {
        try {
            $headers = [
                'Department Name',
                'Department Code',
                'Description',
                'Head of Department Email',
                'Status',
                'Color',
                'Sub Departments (comma separated)',
                'Sort Order'
            ];

            $sampleData = [
                [
                    'Information Technology',
                    'IT',
                    'Responsible for all IT operations and support',
                    'it.head@company.com',
                    'active',
                    '#3b7ddd',
                    'Software Development,Network Administration,Help Desk',
                    '1'
                ],
                [
                    'Human Resources',
                    'HR',
                    'Manages employee relations and company policies',
                    'John Smith',
                    'active',
                    '#28a745',
                    'Recruitment,Training,Payroll',
                    '2'
                ],
                [
                    'Finance',
                    'FIN',
                    'Handles financial operations and accounting',
                    'finance.head@company.com',
                    'active',
                    '#ffc107',
                    'Accounting,Budgeting,Tax',
                    '3'
                ]
            ];

            $csvContent = [];
            $csvContent[] = implode(',', $headers);
            
            foreach ($sampleData as $row) {
                $csvContent[] = implode(',', array_map(function($field) {
                    return '"' . str_replace('"', '""', $field) . '"';
                }, $row));
            }

            $filename = 'department_import_template_' . Carbon::now()->format('Y_m_d') . '.csv';
            
            return response(implode("\n", $csvContent))
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            Log::error('Error downloading template: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to download template. Please try again.'
            ], 500);
        }
    }

    /**
     * Import departments from uploaded file.
     */
    public function import(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:xlsx,xls,csv|max:10240' // 10MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file. Please upload a valid Excel or CSV file (max 10MB).',
                    'errors' => $validator->errors()
                ], 422);
            }

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired.'
                ], 401);
            }

            DB::beginTransaction();

            $import = new DepartmentCategoriesImport($companyId);
            Excel::import($import, $request->file('file'));

            $results = $import->getResults();

            DB::commit();

            Log::info('Department import completed', [
                'company_id' => $companyId,
                'imported' => $results['imported'],
                'failed' => $results['failed'],
                'imported_by' => auth('company_sub_user')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Import completed! {$results['imported']} departments imported successfully.",
                'data' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing departments: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to import departments. Please check your file format and try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a sub department to an existing department.
     */
    public function addSubDepartment(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'sub_department_name' => 'required|string|min:2|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid sub department name.',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $department = DepartmentCategory::forCurrentCompany()->findOrFail($id);
            
            $subDepartmentName = trim($request->sub_department_name);
            
            // Check if sub department already exists
            $subDepartments = $department->sub_departments ?? [];
            if (in_array($subDepartmentName, $subDepartments)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub department already exists.'
                ], 409);
            }

            $department->addSubDepartment($subDepartmentName);
            $department->save();

            DB::commit();

            Log::info('Sub department added', [
                'department_id' => $id,
                'sub_department' => $subDepartmentName,
                'added_by' => auth('company_sub_user')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sub department added successfully!',
                'data' => [
                    'sub_departments' => $department->sub_departments
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding sub department: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add sub department. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a sub department from an existing department.
     */
    public function removeSubDepartment(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'sub_department_name' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid sub department name.',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $department = DepartmentCategory::forCurrentCompany()->findOrFail($id);
            
            $subDepartmentName = trim($request->sub_department_name);
            $department->removeSubDepartment($subDepartmentName);
            $department->save();

            DB::commit();

            Log::info('Sub department removed', [
                'department_id' => $id,
                'sub_department' => $subDepartmentName,
                'removed_by' => auth('company_sub_user')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sub department removed successfully!',
                'data' => [
                    'sub_departments' => $department->sub_departments
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing sub department: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove sub department. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update sort order of departments.
     */
    public function updateSortOrder(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'departments' => 'required|array',
                'departments.*.id' => 'required|integer|exists:department_categories,id',
                'departments.*.sort_order' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid sort order data.',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $companyId = Session::get('selected_company_id');

            foreach ($request->departments as $departmentData) {
                DepartmentCategory::where('id', $departmentData['id'])
                    ->where('company_id', $companyId)
                    ->update(['sort_order' => $departmentData['sort_order']]);
            }

            DB::commit();

            Log::info('Department sort order updated', [
                'company_id' => $companyId,
                'updated_by' => auth('company_sub_user')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sort order updated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating sort order: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sort order. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate action buttons for DataTable.
     */
    private function generateActionButtons(DepartmentCategory $department): string
    {
        $buttons = '<div class="btn-group" role="group">';
        
        $buttons .= '<button type="button" class="btn btn-sm btn-outline-primary action-btn" onclick="viewDepartment(' . $department->id . ')" title="View" data-bs-toggle="tooltip">';
        $buttons .= '<i class="fas fa-eye"></i>';
        $buttons .= '</button>';
        
        $buttons .= '<button type="button" class="btn btn-sm btn-outline-warning action-btn" onclick="editDepartment(' . $department->id . ')" title="Edit" data-bs-toggle="tooltip">';
        $buttons .= '<i class="fas fa-edit"></i>';
        $buttons .= '</button>';
        
        $buttons .= '<button type="button" class="btn btn-sm btn-outline-danger action-btn" onclick="deleteDepartment(' . $department->id . ')" title="Delete" data-bs-toggle="tooltip">';
        $buttons .= '<i class="fas fa-trash"></i>';
        $buttons .= '</button>';
        
        $buttons .= '</div>';
        
        return $buttons;
    }
}
