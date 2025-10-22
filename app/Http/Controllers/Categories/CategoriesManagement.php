<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CompanySubUser;
use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\View\View;
use Illuminate\Support\Facades\{Auth, Session, DB, Log, Storage, Validator};
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;

class CategoriesManagement extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            // Get active tab from request or default to 'categories'
            $activeTab = $request->get('tab', 'categories');
            
            // Check authentication with multiple guards
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isSubUser = Auth::guard('sub_user')->check();
            $isDefaultAuth = Auth::check();
            
            if (!$isCompanySubUser && !$isSubUser && !$isDefaultAuth) {
                Log::warning('CategoriesManagement@index - User not authenticated', [
                    'isCompanySubUser' => $isCompanySubUser,
                    'isSubUser' => $isSubUser,
                    'isDefaultAuth' => $isDefaultAuth,
                    'session_id' => session()->getId(),
                    'user_agent' => request()->userAgent(),
                    'ip' => request()->ip()
                ]);
                return redirect()->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            // Debug session information
            Log::info('CategoriesManagement@index - Session Debug', [
                'selected_company_id' => $companyId,
                'session_all' => Session::all(),
                'auth_check' => Auth::check(),
                'company_sub_user_check' => Auth::guard('company_sub_user')->check(),
                'sub_user_check' => Auth::guard('sub_user')->check()
            ]);
            
            // If no company ID in session, try to set it from authenticated user
            if (!$companyId) {
                if ($isCompanySubUser) {
                    $subUser = Auth::guard('company_sub_user')->user();
                    $companyId = $subUser->company_id;
                    Session::put('selected_company_id', $companyId);
                    Log::info('Set company ID from company_sub_user', ['company_id' => $companyId]);
                } elseif (Auth::guard('sub_user')->check()) {
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
                    } else {
                        // For testing: If no company profile, set a default company ID
                        $companyId = 1; // Default to company ID 1 for testing
                        Session::put('selected_company_id', $companyId);
                        Log::info('Set default company ID for testing', ['company_id' => $companyId]);
                    }
                }
            }
            
            // Final fallback for development/testing
            if (!$companyId && (config('app.env') === 'local' || config('app.debug'))) {
                $companyId = 1; // Default to company ID 1 for development
                Session::put('selected_company_id', $companyId);
                Log::info('Set fallback company ID for development', ['company_id' => $companyId]);
            }
            
            // Get categories with relationships
            if ($companyId) {
                // Use company scope if company ID is available
                $categories = Category::where('company_id', $companyId)
                    ->with(['creator:id,fullname', 'updater:id,fullname'])
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('name', 'asc')
                    ->get();
                $stats = Category::where('company_id', $companyId)->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
                    SUM(CASE WHEN head_name IS NOT NULL AND head_name != "" THEN 1 ELSE 0 END) as with_heads,
                    SUM(CASE WHEN sub_categories IS NOT NULL AND sub_categories != "[]" AND sub_categories != "" THEN 1 ELSE 0 END) as with_sub_categories
                ')->first()->toArray();
            } else {
                // Fallback: Get all categories if no company scope (for development/testing)
                Log::warning('No company ID available, loading all categories');
                $categories = Category::with(['creator:id,fullname', 'updater:id,fullname'])
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('name', 'asc')
                    ->get();
                $stats = [
                    'total' => Category::count(),
                    'active' => Category::where('status', 'active')->count(),
                    'inactive' => Category::where('status', 'inactive')->count(),
                    'with_heads' => Category::whereNotNull('head_name')->where('head_name', '!=', '')->count(),
                    'with_sub_categories' => Category::whereNotNull('sub_categories')->where('sub_categories', '!=', '[]')->count(),
                ];
            }

            Log::info('CategoriesManagement@index loaded', [
                'categories_count' => $categories->count(),
                'company_id' => $companyId,
                'stats' => $stats,
                'sample_categories' => $categories->take(2)->pluck('name', 'id')->toArray(),
                'all_categories_sample' => $categories->map(function($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'company_id' => $cat->company_id ?? 'null',
                        'status' => $cat->status
                    ];
                })->toArray()
            ]);

            // Debug: Add some temporary debugging
            if ($categories->count() === 0) {
                Log::warning('No categories found, checking database directly', [
                    'total_categories_in_db' => Category::count(),
                    'categories_with_company_id' => $companyId ? Category::where('company_id', $companyId)->count() : 'N/A',
                    'sample_from_db' => Category::take(3)->get(['id', 'name', 'company_id', 'status'])->toArray()
                ]);
            }

            // Also load departments for unified view
            $departments = \App\Models\DepartmentCategory::where('company_id', $companyId)
                ->with(['creator:id,fullname', 'updater:id,fullname'])
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
            
            $departmentStats = \App\Models\DepartmentCategory::where('company_id', $companyId)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN head_name IS NOT NULL AND head_name != "" THEN 1 ELSE 0 END) as with_heads,
                SUM(CASE WHEN sub_departments IS NOT NULL AND sub_departments != "[]" AND sub_departments != "" THEN 1 ELSE 0 END) as with_sub_departments
            ')->first();
            
            $departmentStats = $departmentStats ? $departmentStats->toArray() : [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'with_heads' => 0,
                'with_sub_departments' => 0
            ];

            // Also load business sectors for unified view
            $businessSectors = \App\Models\BusinessSector::where('company_id', $companyId)
                ->with(['creator:id,fullname', 'updater:id,fullname'])
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
            
            $businessSectorStats = \App\Models\BusinessSector::where('company_id', $companyId)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN head_name IS NOT NULL AND head_name != "" THEN 1 ELSE 0 END) as with_heads,
                SUM(CASE WHEN sub_sectors IS NOT NULL AND sub_sectors != "[]" AND sub_sectors != "" THEN 1 ELSE 0 END) as with_sub_sectors
            ')->first();
            
            $businessSectorStats = $businessSectorStats ? $businessSectorStats->toArray() : [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'with_heads' => 0,
                'with_sub_sectors' => 0
            ];

            Log::info('CategoriesManagement@index - Returning view with data', [
                'categories_count' => $categories->count(),
                'departments_count' => $departments->count(),
                'business_sectors_count' => $businessSectors->count(),
                'company_id' => $companyId,
                'stats' => $stats,
                'departmentStats' => $departmentStats,
                'businessSectorStats' => $businessSectorStats,
                'calculated_total' => ($stats['total'] ?? 0) + ($departmentStats['total'] ?? 0) + ($businessSectorStats['total'] ?? 0)
            ]);

            return view('company.Categories.category-management', compact(
                'categories',
                'stats',
                'departments',
                'departmentStats',
                'businessSectors',
                'businessSectorStats',
                'activeTab'
            ));

        } catch (\Exception $e) {
            Log::error('Error in CategoriesManagement@index: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'An error occurred while loading categories.');
        }
    }

    /**
     * Get categories data for DataTables.
     */
    public function getCategoriesData(Request $request): JsonResponse
    {
        try {
            // Check authentication for all guard types
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isSubUser = Auth::guard('sub_user')->check();
            $isDefaultAuth = Auth::check();
            
            if (!$isCompanySubUser && !$isSubUser && !$isDefaultAuth) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to continue.'
                ], 401);
            }

            // Debug session and company ID
            $companyId = Session::get('selected_company_id');
            Log::info('getCategoriesData - Company ID from session: ' . $companyId);
            
            // Apply company scope
            $query = Category::forCurrentCompany()
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

            // Total records with company scope
            $totalRecords = Category::forCurrentCompany()->count();
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

            $categories = $query->get();
            
            Log::info('getCategoriesData - Found categories count: ' . $categories->count());
            Log::info('getCategoriesData - Categories: ', $categories->toArray());

            $data = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'code' => $category->code,
                    'description' => $category->description ? substr($category->description, 0, 100) . (strlen($category->description) > 100 ? '...' : '') : '-',
                    'head_of_category' => $category->head_name ?: '-',
                    'sub_categories' => $category->hasSubCategories() ? 
                        '<span class="badge bg-info">' . $category->sub_categories_count . ' sub categories</span>' : 
                        '<span class="text-muted">None</span>',
                    'status' => $category->status === 'active' ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>',
                    'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                    'actions' => $this->generateActionButtons($category)
                ];
            });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getCategoriesData: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load categories data'], 500);
        }
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            // Check authentication for all guard types
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isSubUser = Auth::guard('sub_user')->check();
            $isDefaultAuth = Auth::check();
            
            if (!$isCompanySubUser && !$isSubUser && !$isDefaultAuth) {
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
            
            $category = Category::create($validatedData);

            DB::commit();

            // Get the correct user ID for logging
            $createdBy = null;
            if (Auth::guard('sub_user')->check()) {
                $createdBy = Auth::guard('sub_user')->id();
            } elseif (Auth::guard('company_sub_user')->check()) {
                $createdBy = Auth::guard('company_sub_user')->id();
            } elseif (Auth::check()) {
                $createdBy = Auth::id();
            }

            Log::info('Category created successfully', [
                'category_id' => $category->id,
                'name' => $category->name,
                'created_by' => $createdBy
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully!',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'code' => $category->code
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating category: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified category.
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
            
            // Find category with or without company scope
            if ($companyId) {
                $category = Category::forCurrentCompany()
                    ->with(['creator:id,fullname', 'updater:id,fullname', 'creatorUser:id,fullname', 'updaterUser:id,fullname'])
                    ->findOrFail($id);
            } else {
                // Fallback for no company scope (development/testing)
                $category = Category::with(['creator:id,fullname', 'updater:id,fullname', 'creatorUser:id,fullname', 'updaterUser:id,fullname'])
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
            Log::info('Category view - Authentication Debug', [
                'auth_guard_used' => $authGuardUsed,
                'current_user_id' => $currentUser ? $currentUser->id : null,
                'current_user_name' => $currentUserName,
                'category_created_by' => $category->created_by,
                'category_updated_by' => $category->updated_by,
                'has_creator_relation' => $category->creator ? true : false,
                'has_updater_relation' => $category->updater ? true : false
            ]);

            // Prepare created_by and updated_by info
            $createdBy = null;
            $updatedBy = null;

            if ($category->creator) {
                $createdBy = [
                    'id' => $category->creator->id,
                    'fullname' => $category->creator->fullname,
                    'name' => $category->creator->fullname
                ];
            } elseif ($category->creatorUser) {
                $createdBy = [
                    'id' => $category->creatorUser->id,
                    'fullname' => $category->creatorUser->fullname,
                    'name' => $category->creatorUser->fullname
                ];
            } else {
                // If no creator info exists, show current user name (but don't save to avoid foreign key issues)
                $createdBy = [
                    'id' => null, // Don't store ID to avoid foreign key constraint issues
                    'fullname' => $currentUserName,
                    'name' => $currentUserName
                ];
            }

            if ($category->updater) {
                $updatedBy = [
                    'id' => $category->updater->id,
                    'fullname' => $category->updater->fullname,
                    'name' => $category->updater->fullname
                ];
            } elseif ($category->updaterUser) {
                $updatedBy = [
                    'id' => $category->updaterUser->id,
                    'fullname' => $category->updaterUser->fullname,
                    'name' => $category->updaterUser->fullname
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
                    'id' => $category->id,
                    'name' => $category->name,
                    'code' => $category->code,
                    'description' => $category->description,
                    'head_name' => $category->head_name,
                    'status' => $category->status,
                    'color' => $category->color,
                    'sub_categories' => $category->sub_categories ?? [],
                    'sort_order' => $category->sort_order,
                    'created_by' => $createdBy,
                    'updated_by' => $updatedBy,
                    'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $category->updated_at->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Category not found for viewing', [
                'category_id' => $id,
                'company_id' => Session::get('selected_company_id')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Category not found or you do not have permission to view it.'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Error showing category: ' . $e->getMessage(), [
                'category_id' => $id,
                'company_id' => Session::get('selected_company_id'),
                'auth_checks' => [
                    'default_auth' => Auth::check(),
                    'company_sub_user' => Auth::guard('company_sub_user')->check(),
                    'sub_user' => Auth::guard('sub_user')->check()
                ],
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load category data.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
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
            
            // Find category with or without company scope
            if ($companyId) {
                $category = Category::forCurrentCompany()->findOrFail($id);
            } else {
                // Fallback for no company scope (development/testing)
                $category = Category::findOrFail($id);
            }
            
            $validatedData = $request->validated();
            
            // Log the incoming data for debugging
            Log::info('Category update request data', [
                'category_id' => $id,
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
            
            $category->update($validatedData);

            DB::commit();

            Log::info('Category updated successfully', [
                'category_id' => $category->id,
                'name' => $category->name,
                'updated_by' => $isCompanySubUser ? auth('company_sub_user')->id() : Auth::id(),
                'has_company_scope' => !empty($companyId)
            ]);

            return response()->json([
                'success' => true,
                'message' => "Category '{$category->name}' updated successfully!",
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'code' => $category->code
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::warning('Category not found for update', [
                'category_id' => $id,
                'company_id' => Session::get('selected_company_id')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Category not found or you do not have permission to update it.'
            ], 404);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating category: ' . $e->getMessage(), [
                'category_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified category.
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
            
            // Find category with or without company scope
            if ($companyId) {
                $category = Category::forCurrentCompany()->findOrFail($id);
            } else {
                // Fallback for no company scope (development/testing)
                $category = Category::findOrFail($id);
            }
            
            // Store category name for logging
            $categoryName = $category->name;
            $categoryCompanyId = $category->company_id;
            
            // Soft delete the category
            $category->delete();

            DB::commit();

            Log::info('Category deleted successfully', [
                'category_id' => $id,
                'name' => $categoryName,
                'company_id' => $categoryCompanyId,
                'deleted_by' => $isCompanySubUser ? auth('company_sub_user')->id() : Auth::id(),
                'has_company_scope' => !empty($companyId)
            ]);

            return response()->json([
                'success' => true,
                'message' => "Category '{$categoryName}' has been deleted successfully!"
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::warning('Category not found for deletion', [
                'category_id' => $id,
                'company_id' => Session::get('selected_company_id')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Category not found or you do not have permission to delete it.'
            ], 404);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting category: ' . $e->getMessage(), [
                'category_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get category statistics.
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = Category::getStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting category stats: ' . $e->getMessage());
            
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
            $count = Category::where('company_id', $companyId)->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Export test successful',
                'company_id' => $companyId,
                'category_count' => $count,
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
     * Export categories to Excel.
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
            $filename = "categories_{$timestamp}.{$extension}";

            Log::info('File details', ['filename' => $filename, 'extension' => $extension]);

            // Create export instance
            Log::info('Creating export instance');
            $export = new CategoriesExport($companyId, $filters);
            Log::info('Export instance created successfully');

            // Test the collection first
            $collection = $export->collection();
            Log::info('Export collection test', ['count' => $collection->count()]);

            // Log export activity
            Log::info('Category export initiated', [
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
            Log::error('Error exporting categories: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to export categories. Please try again.',
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
                'Category Name',
                'Category Code',
                'Description',
                'Head of Category Email',
                'Status',
                'Color',
                'Sub Categories (comma separated)',
                'Sort Order'
            ];

            $sampleData = [
                [
                    'Electronics',
                    'ELEC',
                    'Electronic devices and components',
                    'electronics.head@company.com',
                    'active',
                    '#3b7ddd',
                    'Smartphones,Laptops,Accessories',
                    '1'
                ],
                [
                    'Clothing',
                    'CLOTH',
                    'Fashion and apparel items',
                    'John Smith',
                    'active',
                    '#28a745',
                    'Men\'s Wear,Women\'s Wear,Children\'s Wear',
                    '2'
                ],
                [
                    'Books',
                    'BOOKS',
                    'Educational and entertainment books',
                    'books.head@company.com',
                    'active',
                    '#ffc107',
                    'Fiction,Non-Fiction,Educational',
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

            $filename = 'category_import_template_' . Carbon::now()->format('Y_m_d') . '.csv';
            
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
     * Import categories from uploaded file.
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

            $import = new CategoriesImport($companyId);
            Excel::import($import, $request->file('file'));

            $results = $import->getResults();

            DB::commit();

            Log::info('Category import completed', [
                'company_id' => $companyId,
                'imported' => $results['imported'],
                'failed' => $results['failed'],
                'imported_by' => auth('company_sub_user')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Import completed! {$results['imported']} categories imported successfully.",
                'data' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing categories: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to import categories. Please check your file format and try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a sub category to an existing category.
     */
    public function addSubCategory(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'sub_category_name' => 'required|string|min:2|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid sub category name.',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $category = Category::forCurrentCompany()->findOrFail($id);
            
            $subCategoryName = trim($request->sub_category_name);
            
            // Check if sub category already exists
            $subCategories = $category->sub_categories ?? [];
            if (in_array($subCategoryName, $subCategories)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub category already exists.'
                ], 409);
            }

            $category->addSubCategory($subCategoryName);
            $category->save();

            DB::commit();

            Log::info('Sub category added', [
                'category_id' => $id,
                'sub_category' => $subCategoryName,
                'added_by' => auth('company_sub_user')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sub category added successfully!',
                'data' => [
                    'sub_categories' => $category->sub_categories
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding sub category: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add sub category. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a sub category from an existing category.
     */
    public function removeSubCategory(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'sub_category_name' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid sub category name.',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $category = Category::forCurrentCompany()->findOrFail($id);
            
            $subCategoryName = trim($request->sub_category_name);
            $category->removeSubCategory($subCategoryName);
            $category->save();

            DB::commit();

            Log::info('Sub category removed', [
                'category_id' => $id,
                'sub_category' => $subCategoryName,
                'removed_by' => auth('company_sub_user')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sub category removed successfully!',
                'data' => [
                    'sub_categories' => $category->sub_categories
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing sub category: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove sub category. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update sort order of categories.
     */
    public function updateSortOrder(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'categories' => 'required|array',
                'categories.*.id' => 'required|integer|exists:categories,id',
                'categories.*.sort_order' => 'required|integer|min:0'
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

            foreach ($request->categories as $categoryData) {
                Category::where('id', $categoryData['id'])
                    ->where('company_id', $companyId)
                    ->update(['sort_order' => $categoryData['sort_order']]);
            }

            DB::commit();

            Log::info('Category sort order updated', [
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
    private function generateActionButtons(Category $category): string
    {
        $buttons = '<div class="btn-group" role="group">';
        
        $buttons .= '<button type="button" class="btn btn-sm btn-outline-primary action-btn" onclick="viewCategory(' . $category->id . ')" title="View" data-bs-toggle="tooltip">';
        $buttons .= '<i class="fas fa-eye"></i>';
        $buttons .= '</button>';
        
        $buttons .= '<button type="button" class="btn btn-sm btn-outline-warning action-btn" onclick="editCategory(' . $category->id . ')" title="Edit" data-bs-toggle="tooltip">';
        $buttons .= '<i class="fas fa-edit"></i>';
        $buttons .= '</button>';
        
        $buttons .= '<button type="button" class="btn btn-sm btn-outline-danger action-btn" onclick="deleteCategory(' . $category->id . ')" title="Delete" data-bs-toggle="tooltip">';
        $buttons .= '<i class="fas fa-trash"></i>';
        $buttons .= '</button>';
        
        $buttons .= '</div>';
        
        return $buttons;
    }
}
