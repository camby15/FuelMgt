<?php

namespace App\Http\Controllers\MasterTracker;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\CompanySubUser;
use App\Models\DepartmentCategory;
use App\Http\Requests\TeamMember\StoreTeamMemberRequest;
use App\Http\Requests\TeamMember\UpdateTeamMemberRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\View\View;
use Illuminate\Support\Facades\{Auth, Session, DB, Log, Storage, Validator};
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeamMembersExport;
use App\Imports\TeamMembersImport;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of team members.
     */
    public function index(): View|RedirectResponse
    {
        try {
            // Check authentication with multiple guards
            $isCompanySubUser = Auth::guard('company_sub_user')->check();
            $isDefaultAuth = Auth::check();
            $isSubUser = Auth::guard('sub_user')->check();
            
            if (!$isCompanySubUser && !$isDefaultAuth && !$isSubUser) {
                Log::warning('TeamMember@index - User not authenticated', [
                    'isCompanySubUser' => $isCompanySubUser,
                    'isDefaultAuth' => $isDefaultAuth,
                    'isSubUser' => $isSubUser,
                    'session_id' => session()->getId(),
                    'user_agent' => request()->userAgent(),
                    'ip' => request()->ip()
                ]);
                return redirect()->route('auth.login')
                    ->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            
            // Debug session information
            Log::info('TeamMember@index - Session Debug', [
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
            
            // Get team members with relationships
            if ($companyId) {
                // Use company scope if company ID is available
                $teamMembers = TeamMember::where('company_id', $companyId)
                    ->with(['creator:id,fullname', 'updater:id,fullname', 'department:id,name'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                $stats = TeamMember::where('company_id', $companyId)->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
                    SUM(CASE WHEN status = "on-leave" THEN 1 ELSE 0 END) as on_leave
                ')->first();
                
                Log::info('Team members loaded successfully', [
                    'company_id' => $companyId,
                    'team_members_count' => $teamMembers->count(),
                    'stats' => $stats
                ]);
            } else {
                // Fallback: Get all team members if no company scope (for development/testing)
                Log::warning('No company ID available, loading all team members');
                $teamMembers = TeamMember::with(['creator:id,fullname', 'updater:id,fullname'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                $stats = TeamMember::selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
                    SUM(CASE WHEN status = "on-leave" THEN 1 ELSE 0 END) as on_leave
                ')->first();
                
                // Get drivers data for fallback
                $drivers = \App\Models\Driver::with(['creator:id,fullname', 'updater:id,fullname'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                $driverStats = \App\Models\Driver::selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) as available,
                    SUM(CASE WHEN status = "assigned" THEN 1 ELSE 0 END) as assigned,
                    SUM(CASE WHEN status = "on-leave" THEN 1 ELSE 0 END) as on_leave,
                    SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive
                ')->first();
            }

            // Get department categories for dropdown
            $departmentCategories = DepartmentCategory::where('company_id', $companyId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            // Get drivers data for the workforce-fleet view
            $drivers = \App\Models\Driver::where('company_id', $companyId)
                ->with(['creator:id,fullname', 'updater:id,fullname'])
                ->orderBy('created_at', 'desc')
                ->get();
                
            $driverStats = \App\Models\Driver::where('company_id', $companyId)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN status = "assigned" THEN 1 ELSE 0 END) as assigned,
                SUM(CASE WHEN status = "on-leave" THEN 1 ELSE 0 END) as on_leave,
                SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive
            ')->first();

            // Get vehicles data for the workforce-fleet view
            $vehicles = \App\Models\Vehicle::where('company_id', $companyId)
                ->with(['creator:id,fullname', 'updater:id,fullname', 'assignedDriver:id,full_name'])
                ->orderBy('created_at', 'desc')
                ->get();
                
            $vehicleStats = \App\Models\Vehicle::where('company_id', $companyId)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN status = "in-use" THEN 1 ELSE 0 END) as in_use,
                SUM(CASE WHEN status = "maintenance" THEN 1 ELSE 0 END) as maintenance,
                SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive
            ')->first();


            return view('company.MasterTracker.workforce-fleet', [
                'team_members' => $teamMembers,
                'team_members_count' => $teamMembers->count(),
                'stats' => $stats,
                'department_categories' => $departmentCategories,
                'drivers' => $drivers,
                'drivers_count' => $drivers->count(),
                'driver_stats' => $driverStats,
                'vehicles' => $vehicles,
                'vehicles_count' => $vehicles->count(),
                'vehicle_stats' => $vehicleStats,
                'sample_team_members' => $teamMembers->take(2)->pluck('full_name', 'id')->toArray(),
                'all_team_members_sample' => $teamMembers->map(function($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->full_name,
                        'position' => $member->position,
                        'department' => $member->department,
                        'status' => $member->status,
                    ];
                })->toArray(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error in TeamMember@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip()
            ]);
            
            return redirect()->back()->with('error', 'An error occurred while loading team members.');
        }
    }

    /**
     * Store a newly created team member.
     */
    public function store(StoreTeamMemberRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Get the authenticated user ID from the appropriate guard
            $userId = $this->getAuthenticatedUserId();

            $teamMember = TeamMember::create([
                'company_id' => Session::get('selected_company_id'),
                'full_name' => $request->full_name,
                'employee_id' => $request->employee_id,
                'position' => $request->position,
                'department_id' => $request->department_id,
                'phone' => $request->phone,
                'email' => $request->email,
                'hire_date' => $request->hire_date,
                'status' => $request->status,
                'notes' => $request->notes,
                'created_by' => $userId,
            ]);

            DB::commit();

            Log::info('Team member created successfully', [
                'team_member_id' => $teamMember->id,
                'employee_id' => $teamMember->employee_id,
                'full_name' => $teamMember->full_name,
                'created_by' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Team member created successfully!',
                'data' => $teamMember
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating team member', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => $this->getAuthenticatedUserId()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create team member. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified team member.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $teamMember = TeamMember::with(['creator:id,fullname', 'updater:id,fullname', 'department:id,name'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $teamMember
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching team member', [
                'team_member_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Team member not found.'
            ], 404);
        }
    }

    /**
     * Update the specified team member.
     */
    public function update(UpdateTeamMemberRequest $request, string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Get the authenticated user ID from the appropriate guard
            $userId = $this->getAuthenticatedUserId();

            $teamMember = TeamMember::findOrFail($id);

            $teamMember->update([
                'full_name' => $request->full_name,
                'employee_id' => $request->employee_id,
                'position' => $request->position,
                'department_id' => $request->department_id,
                'phone' => $request->phone,
                'email' => $request->email,
                'hire_date' => $request->hire_date,
                'status' => $request->status,
                'notes' => $request->notes,
                'updated_by' => $userId,
            ]);

            DB::commit();

            Log::info('Team member updated successfully', [
                'team_member_id' => $teamMember->id,
                'employee_id' => $teamMember->employee_id,
                'full_name' => $teamMember->full_name,
                'updated_by' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Team member updated successfully!',
                'data' => $teamMember
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating team member', [
                'team_member_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => $this->getAuthenticatedUserId()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update team member. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified team member.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $teamMember = TeamMember::findOrFail($id);
            $teamMember->delete();

            DB::commit();

            Log::info('Team member deleted successfully', [
                'team_member_id' => $id,
                'employee_id' => $teamMember->employee_id,
                'full_name' => $teamMember->full_name,
                'deleted_by' => $this->getAuthenticatedUserId()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Team member deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting team member', [
                'team_member_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $this->getAuthenticatedUserId()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete team member. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get team members data for DataTables.
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // Debug: Log request data
            Log::info('DataTable request', [
                'company_id' => $companyId,
                'request_data' => $request->all()
            ]);
            
            $query = TeamMember::where('company_id', $companyId)
                ->with(['creator:id,fullname', 'updater:id,fullname', 'department:id,name']);

            // Search functionality
            if ($request->has('search') && isset($request->search['value']) && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('full_name', 'like', "%{$searchValue}%")
                      ->orWhere('employee_id', 'like', "%{$searchValue}%")
                      ->orWhere('position', 'like', "%{$searchValue}%")
                      ->orWhereHas('department', function($subQ) use ($searchValue) {
                          $subQ->where('name', 'like', "%{$searchValue}%");
                      })
                      ->orWhere('email', 'like', "%{$searchValue}%")
                      ->orWhere('phone', 'like', "%{$searchValue}%");
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Filter by department
            if ($request->has('department') && $request->department) {
                $query->where('department_id', $request->department);
            }

            $teamMembers = $query->orderBy('id', 'desc')->get();
            
            // Log query results
            Log::info('DataTable results', [
                'returned_count' => $teamMembers->count()
            ]);

            $data = $teamMembers->map(function ($member) {
                return [
                    'id' => $member->id,
                    'full_name' => $member->full_name,
                    'employee_id' => $member->employee_id,
                    'position' => $member->position,
                    'department' => $member->department ? $member->department->name : 'N/A',
                    'department_id' => $member->department_id,
                    'phone' => $member->phone,
                    'email' => $member->email,
                    'hire_date' => $member->hire_date ? $member->hire_date->format('Y-m-d') : '',
                    'status' => ucfirst(str_replace('-', ' ', $member->status)),
                    'notes' => $member->notes,
                    'created_by' => $member->creator ? $member->creator->fullname : '',
                    'updated_by' => $member->updater ? $member->updater->fullname : '',
                    'created_at' => $member->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $member->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $teamMembers->count(),
                'recordsFiltered' => $teamMembers->count(),
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching team members data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Failed to load team members data'], 500);
        }
    }

    /**
     * Get team members statistics.
     */
    public function getStats(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $stats = TeamMember::where('company_id', $companyId)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN status = "on-leave" THEN 1 ELSE 0 END) as on_leave
            ')->first();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching team members stats', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }

    /**
     * Import team members from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:10240' // 10MB max
            ]);

            $file = $request->file('file');
            $import = new TeamMembersImport();
            
            Excel::import($import, $file);
            
            $results = [
                'imported' => $import->getRowCount(),
                'failures' => $import->failures()->count()
            ];

            Log::info('Team members imported successfully', [
                'imported_count' => $results['imported'],
                'failures_count' => $results['failures'],
                'imported_by' => $this->getAuthenticatedUserId()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Import completed! {$results['imported']} team members imported successfully.",
                'data' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Error importing team members: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to import team members. Please check your file format and try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export team members to Excel.
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        try {
            $format = $request->get('format', 'xlsx');
            $export = new TeamMembersExport();
            
            return Excel::download($export, 'team_members_' . date('Y-m-d_H-i-s') . '.xlsx');

        } catch (\Exception $e) {
            Log::error('Error exporting team members: ' . $e->getMessage());
            abort(500, 'Failed to export team members.');
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
                'Employee ID', 
                'Position',
                'Department',
                'Phone',
                'Email',
                'Hire Date',
                'Status',
                'Notes'
            ];

            $export = new TeamMembersExport();
            
            return Excel::download($export, 'team_members_import_template.xlsx');

        } catch (\Exception $e) {
            Log::error('Error downloading template: ' . $e->getMessage());
            abort(500, 'Failed to download template.');
        }
    }

    /**
     * Update sort order of team members.
     */
    public function updateSortOrder(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'members' => 'required|array',
                'members.*.id' => 'required|integer|exists:team_members,id',
                'members.*.sort_order' => 'required|integer'
            ]);

            DB::beginTransaction();

            $userId = $this->getAuthenticatedUserId();
            
            foreach ($request->members as $member) {
                TeamMember::where('id', $member['id'])
                    ->update(['updated_by' => $userId]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sort order updated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating sort order', [
                'error' => $e->getMessage(),
                'user_id' => $this->getAuthenticatedUserId()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update sort order.'
            ], 500);
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
