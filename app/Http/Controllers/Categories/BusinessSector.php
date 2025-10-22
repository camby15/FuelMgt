<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\BusinessSector as BusinessSectorModel;
use App\Models\Category;
use App\Models\DepartmentCategory;
use App\Models\CompanySubUser;
use App\Http\Requests\Categories\StoreBusinessSectorRequest;
use App\Http\Requests\Categories\UpdateBusinessSectorRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\View\View;
use Illuminate\Support\Facades\{Auth, Session, DB, Log, Storage, Validator};
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BusinessSectorExport;
use App\Imports\BusinessSectorImport;

class BusinessSector extends Controller
{
    /**
     * Display a listing of business sectors.
     */
    public function index(): View|RedirectResponse
    {
        try {
            // Check authentication with multiple guards
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isDefaultAuth = Auth::check();
            
            if (!$isCompanySubUser && !$isDefaultAuth) {
                return redirect()->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            // Debug session information
            Log::info('BusinessSector@index - Session Debug', [
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
            
            // Get business sectors with relationships
            if ($companyId) {
                // Use company scope if company ID is available
                $businessSectors = BusinessSectorModel::where('company_id', $companyId)
                    ->with(['creator:id,fullname', 'updater:id,fullname'])
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('name', 'asc')
                    ->get();
                $stats = BusinessSectorModel::where('company_id', $companyId)->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
                    SUM(CASE WHEN head_name IS NOT NULL AND head_name != "" THEN 1 ELSE 0 END) as with_heads,
                    SUM(CASE WHEN sub_sectors IS NOT NULL AND sub_sectors != "[]" AND sub_sectors != "" THEN 1 ELSE 0 END) as with_sub_sectors
                ')->first()->toArray();
            } else {
                // Fallback: Get all business sectors if no company scope (for development/testing)
                Log::warning('No company ID available, loading all business sectors');
                $businessSectors = BusinessSectorModel::with(['creator:id,fullname', 'updater:id,fullname'])
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('name', 'asc')
                    ->get();
                $stats = [
                    'total' => BusinessSectorModel::count(),
                    'active' => BusinessSectorModel::where('status', 'active')->count(),
                    'inactive' => BusinessSectorModel::where('status', 'inactive')->count(),
                    'with_heads' => BusinessSectorModel::whereNotNull('head_name')->where('head_name', '!=', '')->count(),
                    'with_sub_sectors' => BusinessSectorModel::whereNotNull('sub_sectors')->where('sub_sectors', '!=', '[]')->count(),
                ];
            }

            Log::info('BusinessSector@index loaded', [
                'business_sectors_count' => $businessSectors->count(),
                'company_id' => $companyId,
                'stats' => $stats,
                'sample_business_sectors' => $businessSectors->take(2)->pluck('name', 'id')->toArray(),
                'all_business_sectors_sample' => $businessSectors->map(function($sector) {
                    return [
                        'id' => $sector->id,
                        'name' => $sector->name,
                        'company_id' => $sector->company_id ?? 'null',
                        'status' => $sector->status
                    ];
                })->toArray()
            ]);

            // Debug: Add some temporary debugging
            if ($businessSectors->count() === 0) {
                Log::warning('No business sectors found, checking database directly', [
                    'total_business_sectors_in_db' => BusinessSectorModel::count(),
                    'business_sectors_with_company_id' => $companyId ? BusinessSectorModel::where('company_id', $companyId)->count() : 'N/A',
                    'sample_from_db' => BusinessSectorModel::take(3)->get(['id', 'name', 'company_id', 'status'])->toArray()
                ]);
            }

            // Also load categories and departments for unified view
            $categories = Category::where('company_id', $companyId)
                ->with(['creator:id,fullname', 'updater:id,fullname'])
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
            
            $categoryStats = Category::where('company_id', $companyId)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN head_name IS NOT NULL AND head_name != "" THEN 1 ELSE 0 END) as with_heads,
                SUM(CASE WHEN sub_categories IS NOT NULL AND sub_categories != "[]" AND sub_categories != "" THEN 1 ELSE 0 END) as with_sub_categories
            ')->first()->toArray();

            $departments = DepartmentCategory::where('company_id', $companyId)
                ->with(['creator:id,fullname', 'updater:id,fullname'])
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
            
            $departmentStats = DepartmentCategory::where('company_id', $companyId)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN head_name IS NOT NULL AND head_name != "" THEN 1 ELSE 0 END) as with_heads,
                SUM(CASE WHEN sub_departments IS NOT NULL AND sub_departments != "[]" AND sub_departments != "" THEN 1 ELSE 0 END) as with_sub_departments
            ')->first()->toArray();

            return view('company.Categories.category-management', [
                'categories' => $categories,
                'stats' => $categoryStats,
                'departments' => $departments,
                'departmentStats' => $departmentStats,
                'businessSectors' => $businessSectors,
                'businessSectorStats' => $businessSectorStats
            ]);

        } catch (\Exception $e) {
            Log::error('Error in BusinessSector@index: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'An error occurred while loading business sectors.');
        }
    }

    /**
     * Get business sectors data for DataTables.
     */
    public function getBusinessSectorsData(Request $request): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $query = BusinessSectorModel::with(['creator:id,fullname', 'updater:id,fullname']);
            
            if ($companyId) {
                $query->where('company_id', $companyId);
            }
            
            // Apply search filter
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%")
                      ->orWhere('description', 'like', "%{$searchValue}%")
                      ->orWhere('head_name', 'like', "%{$searchValue}%");
                });
            }
            
            // Apply status filter
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }
            
            $businessSectors = $query->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
            
            $data = $businessSectors->map(function($sector) {
                return [
                    'id' => $sector->id,
                    'name' => $sector->name,
                    'description' => $sector->description ?? '-',
                    'head_name' => $sector->head_name ?? '-',
                    'status' => $sector->status,
                    'sub_sectors' => $sector->sub_sectors ? json_decode($sector->sub_sectors, true) : [],
                    'sort_order' => $sector->sort_order,
                    'created_at' => $sector->created_at->format('M d, Y'),
                    'updated_at' => $sector->updated_at->format('M d, Y'),
                    'creator' => $sector->creator ? $sector->creator->fullname : 'System',
                    'updater' => $sector->updater ? $sector->updater->fullname : 'System',
                ];
            });
            
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $businessSectors->count(),
                'recordsFiltered' => $businessSectors->count(),
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getBusinessSectorsData: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load business sectors data'], 500);
        }
    }

    /**
     * Store a newly created business sector.
     */
    public function store(StoreBusinessSectorRequest $request): JsonResponse
    {
        try {
            Log::info('BusinessSector@store - Request received', [
                'request_data' => $request->all(),
                'auth_check' => Auth::check(),
                'company_sub_user_check' => Auth::guard('company_sub_user')->check(),
                'sub_user_check' => Auth::guard('sub_user')->check()
            ]);
            
            // Check authentication (following CompanySubUserController pattern)
            if (!Auth::check()) {
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
            
            Log::info('BusinessSector@store - Validation passed', [
                'validated_data' => $validatedData,
                'company_id' => $companyId
            ]);
            
            // Set company_id (following CompanySubUserController pattern)
            $validatedData['company_id'] = $companyId;
            
            // Set created_by and updated_by
            $userId = null;
            if (auth('sub_user')->check()) {
                $userId = auth('sub_user')->id();
            } elseif (auth('company_sub_user')->check()) {
                $userId = auth('company_sub_user')->id();
            } elseif (Auth::check()) {
                $userId = Auth::id();
            }
            
            $validatedData['created_by'] = $userId;
            $validatedData['updated_by'] = $userId;
            
            Log::info('BusinessSector@store - User IDs set', [
                'user_id' => $userId,
                'created_by' => $validatedData['created_by'],
                'updated_by' => $validatedData['updated_by']
            ]);
            
            // Handle sub-sectors
            if (isset($validatedData['sub_sectors']) && is_array($validatedData['sub_sectors'])) {
                $validatedData['sub_sectors'] = json_encode(array_filter($validatedData['sub_sectors']));
            }
            
            // Set sort order if not provided
            if (!isset($validatedData['sort_order'])) {
                $maxSortOrder = BusinessSectorModel::where('company_id', $companyId)->max('sort_order') ?? 0;
                $validatedData['sort_order'] = $maxSortOrder + 1;
            }

            $businessSector = BusinessSectorModel::create($validatedData);

            DB::commit();

            Log::info('Business sector created successfully', [
                'business_sector_id' => $businessSector->id,
                'name' => $businessSector->name,
                'created_by' => auth('company_sub_user')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Business sector created successfully!',
                'data' => [
                    'id' => $businessSector->id,
                    'name' => $businessSector->name,
                    'description' => $businessSector->description
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating business sector: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create business sector. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified business sector.
     */
    public function show($id): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $businessSector = BusinessSectorModel::where('id', $id);
            
            if ($companyId) {
                $businessSector->where('company_id', $companyId);
            }
            
            $businessSector = $businessSector->with(['creator:id,fullname', 'updater:id,fullname'])->first();
            
            if (!$businessSector) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business sector not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $businessSector
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching business sector: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch business sector.'
            ], 500);
        }
    }

    /**
     * Update the specified business sector.
     */
    public function update(UpdateBusinessSectorRequest $request, $id): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session not found. Please login again.'
                ], 401);
            }

            $businessSector = BusinessSectorModel::where('id', $id)
                ->where('company_id', $companyId)
                ->first();
            
            if (!$businessSector) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business sector not found.'
                ], 404);
            }

            $data = $request->validated();
            $data['updated_by'] = Auth::id();
            
            // Handle sub-sectors
            if (isset($data['sub_sectors']) && is_array($data['sub_sectors'])) {
                $data['sub_sectors'] = json_encode(array_filter($data['sub_sectors']));
            }

            $businessSector->update($data);

            Log::info('Business sector updated successfully', [
                'business_sector_id' => $businessSector->id,
                'name' => $businessSector->name,
                'company_id' => $companyId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Business sector updated successfully.',
                'data' => $businessSector->fresh(['creator:id,fullname', 'updater:id,fullname'])
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating business sector: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update business sector. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified business sector.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session not found. Please login again.'
                ], 401);
            }

            $businessSector = BusinessSectorModel::where('id', $id)
                ->where('company_id', $companyId)
                ->first();
            
            if (!$businessSector) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business sector not found.'
                ], 404);
            }

            $businessSectorName = $businessSector->name;
            $businessSector->delete();

            Log::info('Business sector deleted successfully', [
                'business_sector_id' => $id,
                'name' => $businessSectorName,
                'company_id' => $companyId
            ]);

            return response()->json([
                'success' => true,
                'message' => "Business sector '{$businessSectorName}' deleted successfully."
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting business sector: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete business sector. Please try again.'
            ], 500);
        }
    }

    /**
     * Get business sector statistics.
     */
    public function getStats(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session not found.'
                ], 401);
            }

            $stats = BusinessSectorModel::where('company_id', $companyId)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN head_name IS NOT NULL AND head_name != "" THEN 1 ELSE 0 END) as with_heads,
                SUM(CASE WHEN sub_sectors IS NOT NULL AND sub_sectors != "[]" AND sub_sectors != "" THEN 1 ELSE 0 END) as with_sub_sectors
            ')->first();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching business sector stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics.'
            ], 500);
        }
    }

    /**
     * Import business sectors from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:csv|max:10240' // 10MB max - CSV only until ZipArchive is installed
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file. Please upload a valid CSV file (max 10MB). Excel support requires PHP ZipArchive extension.',
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

            $import = new BusinessSectorImport($companyId);
            Excel::import($import, $request->file('file'));

            $results = [
                'imported' => $import->getImportedCount(),
                'failed' => count($import->getErrors()),
                'errors' => $import->getErrors()
            ];

            DB::commit();

            Log::info('Business sector import completed', [
                'company_id' => $companyId,
                'imported' => $results['imported'],
                'failed' => $results['failed'],
                'imported_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Import completed! {$results['imported']} business sectors imported successfully.",
                'data' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing business sectors: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to import business sectors. Please check your file format and try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export business sectors to Excel.
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                abort(401, 'Company session not found.');
            }

            $export = new BusinessSectorExport($companyId);
            return Excel::download($export, 'business_sectors_' . date('Y-m-d_H-i-s') . '.xlsx');

        } catch (\Exception $e) {
            Log::error('Error exporting business sectors: ' . $e->getMessage());
            abort(500, 'Failed to export business sectors.');
        }
    }

    /**
     * Download import template.
     */
    public function downloadTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        try {
            $export = new BusinessSectorExport();
            return Excel::download($export, 'business_sectors_import_template.xlsx');

        } catch (\Exception $e) {
            Log::error('Error downloading template: ' . $e->getMessage());
            abort(500, 'Failed to download template.');
        }
    }

    /**
     * Add sub-sector to business sector.
     */
    public function addSubSector(Request $request, $id): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session not found. Please login again.'
                ], 401);
            }

            $request->validate([
                'sub_sector' => 'required|string|max:255'
            ]);

            $businessSector = BusinessSectorModel::where('id', $id)
                ->where('company_id', $companyId)
                ->first();
            
            if (!$businessSector) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business sector not found.'
                ], 404);
            }

            $subSectors = $businessSector->sub_sectors ? json_decode($businessSector->sub_sectors, true) : [];
            $subSectors[] = $request->sub_sector;
            
            $businessSector->update([
                'sub_sectors' => json_encode($subSectors),
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sub-sector added successfully.',
                'data' => $businessSector->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding sub-sector: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add sub-sector.'
            ], 500);
        }
    }

    /**
     * Remove sub-sector from business sector.
     */
    public function removeSubSector(Request $request, $id): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session not found. Please login again.'
                ], 401);
            }

            $request->validate([
                'sub_sector' => 'required|string'
            ]);

            $businessSector = BusinessSectorModel::where('id', $id)
                ->where('company_id', $companyId)
                ->first();
            
            if (!$businessSector) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business sector not found.'
                ], 404);
            }

            $subSectors = $businessSector->sub_sectors ? json_decode($businessSector->sub_sectors, true) : [];
            $subSectors = array_values(array_filter($subSectors, function($sector) use ($request) {
                return $sector !== $request->sub_sector;
            }));
            
            $businessSector->update([
                'sub_sectors' => json_encode($subSectors),
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sub-sector removed successfully.',
                'data' => $businessSector->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error removing sub-sector: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove sub-sector.'
            ], 500);
        }
    }

    /**
     * Update sort order of business sectors.
     */
    public function updateSortOrder(Request $request): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session not found. Please login again.'
                ], 401);
            }

            $request->validate([
                'sectors' => 'required|array',
                'sectors.*.id' => 'required|integer|exists:business_sectors,id',
                'sectors.*.sort_order' => 'required|integer|min:0'
            ]);

            DB::transaction(function() use ($request, $companyId) {
                foreach ($request->sectors as $sector) {
                    BusinessSectorModel::where('id', $sector['id'])
                        ->where('company_id', $companyId)
                        ->update([
                            'sort_order' => $sector['sort_order'],
                            'updated_by' => Auth::id()
                        ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Sort order updated successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating sort order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sort order.'
            ], 500);
        }
    }

}
