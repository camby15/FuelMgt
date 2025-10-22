<?php

namespace App\Http\Controllers\MasterTracker;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\View\View;
use Illuminate\Support\Facades\{Auth, Session, DB, Log, Validator};
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DriversExport;
use App\Imports\DriversImport;

class DriverMemberController extends Controller
{
    /**
     * Display a listing of drivers.
     */
    public function index(): View|RedirectResponse
    {
        try {
            // Check authentication with multiple guards
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isDefaultAuth = Auth::check();
            
            if (!$isCompanySubUser && !$isDefaultAuth) {
                Log::warning('DriverMember@index - User not authenticated', [
                    'isCompanySubUser' => $isCompanySubUser,
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
            Log::info('DriverMember@index - Session Debug', [
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
            
            // Get drivers with relationships
            if ($companyId) {
                // Use company scope if company ID is available
                $drivers = Driver::where('company_id', $companyId)
                    ->with(['creator:id,fullname', 'updater:id,fullname'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                $stats = Driver::where('company_id', $companyId)->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) as available,
                    SUM(CASE WHEN status = "assigned" THEN 1 ELSE 0 END) as assigned,
                    SUM(CASE WHEN status = "on-leave" THEN 1 ELSE 0 END) as on_leave,
                    SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive
                ')->first();
                
                Log::info('Drivers loaded successfully', [
                    'company_id' => $companyId,
                    'drivers_count' => $drivers->count(),
                    'stats' => $stats
                ]);
            } else {
                // Fallback: Get all drivers if no company scope (for development/testing)
                Log::warning('No company ID available, loading all drivers');
                $drivers = Driver::with(['creator:id,fullname', 'updater:id,fullname'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                $stats = Driver::selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) as available,
                    SUM(CASE WHEN status = "assigned" THEN 1 ELSE 0 END) as assigned,
                    SUM(CASE WHEN status = "on-leave" THEN 1 ELSE 0 END) as on_leave,
                    SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive
                ')->first();
            }

            return view('company.MasterTracker.workforce-fleet', [
                'drivers' => $drivers,
                'drivers_count' => $drivers->count(),
                'driver_stats' => $stats,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in DriverMember@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip()
            ]);
            
            return redirect()->back()->with('error', 'An error occurred while loading drivers.');
        }
    }

    /**
     * Store a newly created driver.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Debug: Log the incoming request data
            Log::info('Driver store request received', [
                'request_data' => $request->all(),
                'user_id' => $this->getAuthenticatedUserId(),
                'company_id' => Session::get('selected_company_id')
            ]);
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'license_number' => 'required|string|max:255|unique:drivers,license_number',
                'license_type' => 'required|in:class-a,class-b,class-c,motorcycle',
                'phone' => 'required|string|max:20',
                'experience_years' => 'nullable|integer|min:0|max:50',
                'license_expiry' => 'required|date',
                'emergency_contact' => 'nullable|string|max:255',
                'status' => 'required|in:available,assigned,on-leave,inactive',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                Log::error('Driver validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'request_data' => $request->all()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Get the authenticated user ID from the appropriate guard
            $userId = $this->getAuthenticatedUserId();

            $driver = Driver::create([
                'company_id' => Session::get('selected_company_id'),
                'full_name' => $request->full_name,
                'license_number' => $request->license_number,
                'license_type' => $request->license_type,
                'phone' => $request->phone,
                'experience_years' => $request->experience_years,
                'license_expiry' => $request->license_expiry,
                'emergency_contact' => $request->emergency_contact,
                'status' => $request->status,
                'notes' => $request->notes,
                'created_by' => $userId,
            ]);

            DB::commit();

            Log::info('Driver created successfully', [
                'driver_id' => $driver->id,
                'license_number' => $driver->license_number,
                'full_name' => $driver->full_name,
                'created_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Driver created successfully!',
                'data' => $driver
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating driver', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create driver. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified driver.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $driver = Driver::with(['creator:id,fullname', 'updater:id,fullname'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $driver
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching driver', [
                'driver_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Driver not found.'
            ], 404);
        }
    }

    /**
     * Update the specified driver.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'license_number' => 'required|string|max:255|unique:drivers,license_number,' . $id,
                'license_type' => 'required|in:class-a,class-b,class-c,motorcycle',
                'phone' => 'required|string|max:20',
                'experience_years' => 'nullable|integer|min:0|max:50',
                'license_expiry' => 'required|date',
                'emergency_contact' => 'nullable|string|max:255',
                'status' => 'required|in:available,assigned,on-leave,inactive',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Get the authenticated user ID from the appropriate guard
            $userId = $this->getAuthenticatedUserId();

            $driver = Driver::findOrFail($id);

            $driver->update([
                'full_name' => $request->full_name,
                'license_number' => $request->license_number,
                'license_type' => $request->license_type,
                'phone' => $request->phone,
                'experience_years' => $request->experience_years,
                'license_expiry' => $request->license_expiry,
                'emergency_contact' => $request->emergency_contact,
                'status' => $request->status,
                'notes' => $request->notes,
                'updated_by' => $userId,
            ]);

            DB::commit();

            Log::info('Driver updated successfully', [
                'driver_id' => $driver->id,
                'license_number' => $driver->license_number,
                'full_name' => $driver->full_name,
                'updated_by' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Driver updated successfully!',
                'data' => $driver
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating driver', [
                'driver_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update driver. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified driver.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $driver = Driver::findOrFail($id);
            $driver->delete();

            DB::commit();

            Log::info('Driver deleted successfully', [
                'driver_id' => $id,
                'license_number' => $driver->license_number,
                'full_name' => $driver->full_name,
                'deleted_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Driver deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting driver', [
                'driver_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete driver. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get drivers data for DataTables.
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // Debug: Log request data
            Log::info('Driver DataTable request', [
                'company_id' => $companyId,
                'request_data' => $request->all()
            ]);
            
            $query = Driver::where('company_id', $companyId)
                ->with(['creator:id,fullname', 'updater:id,fullname']);

            // Search functionality
            if ($request->has('search') && isset($request->search['value']) && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('full_name', 'like', "%{$searchValue}%")
                      ->orWhere('license_number', 'like', "%{$searchValue}%")
                      ->orWhere('phone', 'like', "%{$searchValue}%")
                      ->orWhere('emergency_contact', 'like', "%{$searchValue}%");
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Filter by license type
            if ($request->has('license_type') && $request->license_type) {
                $query->where('license_type', $request->license_type);
            }

            $drivers = $query->orderBy('id', 'desc')->get();
            
            // Log query results
            Log::info('Driver DataTable results', [
                'returned_count' => $drivers->count()
            ]);

            $data = $drivers->map(function ($driver) {
                return [
                    'id' => $driver->id,
                    'full_name' => $driver->full_name,
                    'license_number' => $driver->license_number,
                    'license_type' => $driver->license_type_formatted,
                    'phone' => $driver->phone,
                    'experience_years' => $driver->experience_years,
                    'license_expiry' => $driver->license_expiry ? $driver->license_expiry->format('Y-m-d') : '',
                    'emergency_contact' => $driver->emergency_contact,
                    'status' => $driver->status_formatted,
                    'notes' => $driver->notes,
                    'created_by' => $driver->creator ? $driver->creator->fullname : '',
                    'updated_by' => $driver->updater ? $driver->updater->fullname : '',
                    'created_at' => $driver->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $driver->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $drivers->count(),
                'recordsFiltered' => $drivers->count(),
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching drivers data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Failed to load drivers data'], 500);
        }
    }

    /**
     * Get drivers statistics.
     */
    public function getStats(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $stats = Driver::where('company_id', $companyId)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN status = "assigned" THEN 1 ELSE 0 END) as assigned,
                SUM(CASE WHEN status = "on-leave" THEN 1 ELSE 0 END) as on_leave,
                SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive
            ')->first();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching drivers stats', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }

    /**
     * Import drivers from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:10240' // 10MB max
            ]);

            $file = $request->file('file');
            $import = new DriversImport();
            
            Excel::import($import, $file);
            
            $results = [
                'imported' => $import->getRowCount(),
                'failures' => $import->failures()->count()
            ];

            Log::info('Drivers imported successfully', [
                'imported_count' => $results['imported'],
                'failures_count' => $results['failures'],
                'imported_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Import completed! {$results['imported']} drivers imported successfully.",
                'data' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Error importing drivers: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to import drivers. Please check your file format and try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export drivers to Excel.
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        try {
            $format = $request->get('format', 'xlsx');
            $export = new DriversExport();
            
            return Excel::download($export, 'drivers_' . date('Y-m-d_H-i-s') . '.xlsx');

        } catch (\Exception $e) {
            Log::error('Error exporting drivers: ' . $e->getMessage());
            abort(500, 'Failed to export drivers.');
        }
    }

    /**
     * Download import template.
     */
    public function downloadTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        try {
            // Create a sample template with headers
            $headers = [
                'Full Name',
                'License Number', 
                'License Type',
                'Phone',
                'Experience Years',
                'License Expiry',
                'Emergency Contact',
                'Status',
                'Notes'
            ];

            $export = new DriversExport();
            
            return Excel::download($export, 'drivers_import_template.xlsx');

        } catch (\Exception $e) {
            Log::error('Error downloading template: ' . $e->getMessage());
            abort(500, 'Failed to download template.');
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
}
