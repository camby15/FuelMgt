<?php

namespace App\Http\Controllers\MasterTracker;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\View\View;
use Illuminate\Support\Facades\{Auth, Session, DB, Log, Validator};
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VehiclesExport;
use App\Imports\VehiclesImport;

class VehicleManagementController extends Controller
{
    /**
     * Display a listing of vehicles.
     */
    public function index(): View|RedirectResponse
    {
        try {
            // Check authentication with multiple guards
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isDefaultAuth = Auth::check();
            
            if (!$isCompanySubUser && !$isDefaultAuth) {
                Log::warning('VehicleManagement@index - User not authenticated', [
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
            Log::info('VehicleManagement@index - Session Debug', [
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
            
            // Get vehicles with relationships
            if ($companyId) {
                // Use company scope if company ID is available
                $vehicles = Vehicle::where('company_id', $companyId)
                    ->with(['creator:id,fullname', 'updater:id,fullname', 'assignedDriver:id,full_name'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                $stats = Vehicle::where('company_id', $companyId)->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) as available,
                    SUM(CASE WHEN status = "in-use" THEN 1 ELSE 0 END) as in_use,
                    SUM(CASE WHEN status = "maintenance" THEN 1 ELSE 0 END) as maintenance,
                    SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive
                ')->first();
                
                Log::info('Vehicles loaded successfully', [
                    'company_id' => $companyId,
                    'vehicles_count' => $vehicles->count(),
                    'stats' => $stats
                ]);
            } else {
                // Fallback: Get all vehicles if no company scope (for development/testing)
                Log::warning('No company ID available, loading all vehicles');
                $vehicles = Vehicle::with(['creator:id,fullname', 'updater:id,fullname', 'assignedDriver:id,full_name'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                $stats = Vehicle::selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) as available,
                    SUM(CASE WHEN status = "in-use" THEN 1 ELSE 0 END) as in_use,
                    SUM(CASE WHEN status = "maintenance" THEN 1 ELSE 0 END) as maintenance,
                    SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive
                ')->first();
            }

            return view('company.MasterTracker.workforce-fleet', [
                'vehicles' => $vehicles,
                'vehicles_count' => $vehicles->count(),
                'vehicle_stats' => $stats,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in VehicleManagement@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip()
            ]);
            
            return redirect()->back()->with('error', 'An error occurred while loading vehicles.');
        }
    }

    /**
     * Store a newly created vehicle.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Debug: Log the incoming request data
            Log::info('Vehicle store request received', [
                'request_data' => $request->all(),
                'user_id' => $this->getAuthenticatedUserId(),
                'company_id' => Session::get('selected_company_id')
            ]);
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'registration_number' => 'required|string|max:255|unique:vehicles,registration_number',
                'make' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'type' => 'required|in:sedan,suv,truck,van,motorcycle',
                'year' => 'required|integer|min:1990|max:2025',
                'color' => 'nullable|string|max:100',
                'fuel_type' => 'nullable|string|max:100',
                'insurance_expiry' => 'required|date',
                'mileage' => 'nullable|integer|min:0',
                'status' => 'required|in:available,in-use,maintenance,inactive',
                'notes' => 'nullable|string',
                'assigned_driver_id' => 'nullable|exists:drivers,id',
            ]);

            if ($validator->fails()) {
                Log::error('Vehicle validation failed', [
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

            $vehicle = Vehicle::create([
                'company_id' => Session::get('selected_company_id'),
                'registration_number' => $request->registration_number,
                'make' => $request->make,
                'model' => $request->model,
                'type' => $request->type,
                'year' => $request->year,
                'color' => $request->color,
                'fuel_type' => $request->fuel_type,
                'insurance_expiry' => $request->insurance_expiry,
                'mileage' => $request->mileage,
                'status' => $request->status,
                'notes' => $request->notes,
                'assigned_driver_id' => $request->assigned_driver_id,
                'created_by' => $userId,
            ]);

            DB::commit();

            Log::info('Vehicle created successfully', [
                'vehicle_id' => $vehicle->id,
                'registration_number' => $vehicle->registration_number,
                'make_model' => $vehicle->make_model,
                'created_by' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle created successfully!',
                'data' => $vehicle
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating vehicle', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create vehicle. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified vehicle.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $vehicle = Vehicle::with(['creator:id,fullname', 'updater:id,fullname', 'assignedDriver:id,full_name'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $vehicle
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching vehicle', [
                'vehicle_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found.'
            ], 404);
        }
    }

    /**
     * Update the specified vehicle.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'registration_number' => 'required|string|max:255|unique:vehicles,registration_number,' . $id,
                'make' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'type' => 'required|in:sedan,suv,truck,van,motorcycle',
                'year' => 'required|integer|min:1990|max:2025',
                'color' => 'nullable|string|max:100',
                'fuel_type' => 'nullable|string|max:100',
                'insurance_expiry' => 'required|date',
                'mileage' => 'nullable|integer|min:0',
                'status' => 'required|in:available,in-use,maintenance,inactive',
                'notes' => 'nullable|string',
                'assigned_driver_id' => 'nullable|exists:drivers,id',
            ]);

            if ($validator->fails()) {
                Log::error('Vehicle validation failed', [
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

            $vehicle = Vehicle::findOrFail($id);

            $vehicle->update([
                'registration_number' => $request->registration_number,
                'make' => $request->make,
                'model' => $request->model,
                'type' => $request->type,
                'year' => $request->year,
                'color' => $request->color,
                'fuel_type' => $request->fuel_type,
                'insurance_expiry' => $request->insurance_expiry,
                'mileage' => $request->mileage,
                'status' => $request->status,
                'notes' => $request->notes,
                'assigned_driver_id' => $request->assigned_driver_id,
                'updated_by' => $userId,
            ]);

            DB::commit();

            Log::info('Vehicle updated successfully', [
                'vehicle_id' => $vehicle->id,
                'registration_number' => $vehicle->registration_number,
                'make_model' => $vehicle->make_model,
                'updated_by' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle updated successfully!',
                'data' => $vehicle
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating vehicle', [
                'vehicle_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update vehicle. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified vehicle.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $vehicle = Vehicle::findOrFail($id);
            $vehicle->delete();

            DB::commit();

            Log::info('Vehicle deleted successfully', [
                'vehicle_id' => $id,
                'registration_number' => $vehicle->registration_number,
                'make_model' => $vehicle->make_model,
                'deleted_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting vehicle', [
                'vehicle_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vehicle. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vehicles data for DataTables.
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // Debug: Log request data
            Log::info('Vehicle DataTable request', [
                'company_id' => $companyId,
                'request_data' => $request->all()
            ]);
            
            $query = Vehicle::where('company_id', $companyId)
                ->with(['creator:id,fullname', 'updater:id,fullname', 'assignedDriver:id,full_name']);

            // Search functionality
            if ($request->has('search') && isset($request->search['value']) && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('registration_number', 'like', "%{$searchValue}%")
                      ->orWhere('make', 'like', "%{$searchValue}%")
                      ->orWhere('model', 'like', "%{$searchValue}%")
                      ->orWhere('color', 'like', "%{$searchValue}%")
                      ->orWhereHas('assignedDriver', function($subQ) use ($searchValue) {
                          $subQ->where('full_name', 'like', "%{$searchValue}%");
                      });
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Filter by type
            if ($request->has('type') && $request->type) {
                $query->where('type', $request->type);
            }

            $vehicles = $query->orderBy('id', 'desc')->get();
            
            // Log query results
            Log::info('Vehicle DataTable results', [
                'returned_count' => $vehicles->count()
            ]);

            $data = $vehicles->map(function ($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'registration_number' => $vehicle->registration_number,
                    'make_model' => $vehicle->make_model,
                    'type' => $vehicle->type_formatted,
                    'year' => $vehicle->year,
                    'assigned_driver' => $vehicle->assignedDriver ? $vehicle->assignedDriver->full_name : 'Unassigned',
                    'insurance_expiry' => $vehicle->insurance_expiry ? $vehicle->insurance_expiry->format('Y-m-d') : '',
                    'status' => $vehicle->status_formatted,
                    'notes' => $vehicle->notes,
                    'created_by' => $vehicle->creator ? $vehicle->creator->fullname : '',
                    'updated_by' => $vehicle->updater ? $vehicle->updater->fullname : '',
                    'created_at' => $vehicle->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $vehicle->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $vehicles->count(),
                'recordsFiltered' => $vehicles->count(),
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching vehicles data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Failed to load vehicles data'], 500);
        }
    }

    /**
     * Get vehicles statistics.
     */
    public function getStats(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $stats = Vehicle::where('company_id', $companyId)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN status = "in-use" THEN 1 ELSE 0 END) as in_use,
                SUM(CASE WHEN status = "maintenance" THEN 1 ELSE 0 END) as maintenance,
                SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive
            ')->first();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching vehicles stats', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }

    /**
     * Import vehicles from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:10240' // 10MB max
            ]);

            $file = $request->file('file');
            $import = new VehiclesImport();
            
            Excel::import($import, $file);
            
            $results = [
                'imported' => $import->getRowCount(),
                'failures' => $import->failures()->count()
            ];

            Log::info('Vehicles imported successfully', [
                'imported_count' => $results['imported'],
                'failures_count' => $results['failures'],
                'imported_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Import completed! {$results['imported']} vehicles imported successfully.",
                'data' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Error importing vehicles: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to import vehicles. Please check your file format and try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export vehicles to Excel.
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        try {
            $format = $request->get('format', 'xlsx');
            $export = new VehiclesExport();
            
            return Excel::download($export, 'vehicles_' . date('Y-m-d_H-i-s') . '.xlsx');

        } catch (\Exception $e) {
            Log::error('Error exporting vehicles: ' . $e->getMessage());
            abort(500, 'Failed to export vehicles.');
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
                'Registration Number',
                'Make', 
                'Model',
                'Type',
                'Year',
                'Color',
                'Fuel Type',
                'Insurance Expiry',
                'Mileage',
                'Status',
                'Notes'
            ];

            $export = new VehiclesExport();
            
            return Excel::download($export, 'vehicles_import_template.xlsx');

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
