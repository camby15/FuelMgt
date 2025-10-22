<?php

namespace App\Http\Controllers\MasterTracker;

use App\Http\Controllers\Controller;
use App\Models\TeamParing;
use App\Models\TeamMember;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Http\Requests\TeamParingRequest;
use Illuminate\Support\Facades\{Log, Auth, Session, DB};
use Illuminate\Http\{JsonResponse, Request, Response, RedirectResponse};
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class TeamParingController extends Controller
{
    /**
     * Display a listing of the team pairings.
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
                $companyProfile = null; // Sub-users don't have company profiles
            } elseif ($isSubUser) {
                $companyUser = Auth::guard('sub_user')->user();
                $companyProfile = null; // Sub-users don't have company profiles
            } else {
                $companyUser = null;
                $companyProfile = null;
            }

            // Get teams for the company
            $teams = TeamParing::forCompany($companyId)
                ->with(['teamMembers', 'vehicles', 'drivers'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Get assigned IDs to exclude from dropdowns
            $assignedMemberIds = collect();
            $assignedVehicleIds = collect();
            $assignedDriverIds = collect();
            
            foreach ($teams as $team) {
                $assignedMemberIds = $assignedMemberIds->merge($team->teamMembers->pluck('id'));
                $assignedVehicleIds = $assignedVehicleIds->merge($team->vehicles->pluck('id'));
                $assignedDriverIds = $assignedDriverIds->merge($team->drivers->pluck('id'));
            }

            // Get unassigned resources for dropdowns
            $unassignedTeamMembers = TeamMember::forCompany($companyId)
                ->whereNotIn('id', $assignedMemberIds->unique())
                ->select('id', 'full_name', 'position', 'status')
                ->orderBy('full_name')
                ->get();

            $unassignedVehicles = Vehicle::forCompany($companyId)
                ->whereNotIn('id', $assignedVehicleIds->unique())
                ->select('id', 'registration_number', 'make', 'model', 'status')
                ->orderBy('registration_number')
                ->get();

            $unassignedDrivers = Driver::forCompany($companyId)
                ->whereNotIn('id', $assignedDriverIds->unique())
                ->select('id', 'full_name', 'license_number', 'status')
                ->orderBy('full_name')
                ->get();

            // Get session data for modals
            $viewTeamData = session('view_team_data');
            $editTeamData = session('edit_team_data');
            
            // Clear session data after retrieving
            session()->forget(['view_team_data', 'edit_team_data']);

            return view('company.MasterTracker.team-pairing', [
                'company' => $companyProfile,
                'teams' => $teams,
                'unassignedTeamMembers' => $unassignedTeamMembers,
                'unassignedVehicles' => $unassignedVehicles,
                'unassignedDrivers' => $unassignedDrivers,
                'companyId' => $companyId,
                'viewTeamData' => $viewTeamData,
                'editTeamData' => $editTeamData
            ]);

        } catch (\Exception $e) {
            Log::error('Error in TeamParingController index method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error loading team pairing page. Please try again.');
        }
    }

    /**
     * Get team pairing data for DataTables
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            $query = TeamParing::with(['teamMembers', 'vehicles', 'drivers', 'teamLead', 'primaryVehicle', 'primaryDriver'])
                ->forCompany($companyId);

            // Apply filters
            if ($request->filled('status')) {
                $query->where('team_status', $request->status);
            }
            
            if ($request->filled('location')) {
                $query->where('team_location', $request->location);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('team_name', 'like', "%{$search}%")
                      ->orWhere('team_code', 'like', "%{$search}%")
                      ->orWhere('team_location', 'like', "%{$search}%");
                });
            }

            $teams = $query->orderBy('created_at', 'desc')->paginate(25);

            $data = $teams->map(function ($team) {
                return [
                    'id' => $team->id,
                    'team_name' => $team->team_name,
                    'team_code' => $team->team_code,
                    'team_location' => $team->location_formatted,
                    'team_status' => $team->team_status,
                    'team_members' => $team->teamMembers->map(function ($member) {
                        return [
                            'id' => $member->id,
                            'name' => $member->full_name,
                            'position' => $member->position
                        ];
                    }),
                    'vehicles' => $team->vehicles->map(function ($vehicle) {
                        return [
                            'id' => $vehicle->id,
                            'registration' => $vehicle->registration_number,
                            'model' => $vehicle->make . ' ' . $vehicle->model
                        ];
                    }),
                    'drivers' => $team->drivers->map(function ($driver) {
                        return [
                            'id' => $driver->id,
                            'name' => $driver->full_name,
                            'license' => $driver->license_number
                        ];
                    }),
                    'team_allocation' => $team->team_allocation,
                    'team_lead' => $team->teamLead ? $team->teamLead->id : null,
                    'primary_vehicle' => $team->primaryVehicle ? $team->primaryVehicle->id : null,
                    'primary_driver' => $team->primaryDriver ? $team->primaryDriver->id : null,
                    'formation_date' => $team->formation_date?->format('Y-m-d'),
                    'contact_number' => $team->contact_number,
                    'notes' => $team->notes,
                    'team_summary' => $team->team_summary,
                    'assignment_completeness' => $team->assignment_completeness,
                    'created_at' => $team->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $team->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'pagination' => [
                    'current_page' => $teams->currentPage(),
                    'last_page' => $teams->lastPage(),
                    'per_page' => $teams->perPage(),
                    'total' => $teams->total(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getData method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error loading team data. Please try again.'
            ], 500);
        }
    }

    /**
     * Store a newly created team pairing.
     */
    public function store(TeamParingRequest $request): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            DB::beginTransaction();

            // Get the authenticated user ID from the appropriate guard
            $userId = $this->getAuthenticatedUserId();

            // Create the team pairing
            $teamParing = TeamParing::create([
                'company_id' => $companyId,
                'team_name' => $request->team_name,
                'team_code' => strtoupper($request->team_code),
                'team_location' => $request->team_location,
                'team_status' => $request->team_status,
                'team_allocation' => $request->team_allocation,
                'team_lead' => $request->team_lead,
                'primary_vehicle' => $request->primary_vehicle,
                'primary_driver' => $request->primary_driver,
                'formation_date' => $request->formation_date,
                'contact_number' => $request->contact_number,
                'notes' => $request->notes,
                'created_by' => $userId,
            ]);

            // Attach team members
            if ($request->team_members) {
                $teamParing->teamMembers()->attach($request->team_members);
            }

            // Attach vehicles
            if ($request->assigned_vehicles) {
                $teamParing->vehicles()->attach($request->assigned_vehicles);
            }

            // Attach drivers
            if ($request->assigned_drivers) {
                $teamParing->drivers()->attach($request->assigned_drivers);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Team created successfully',
                'data' => $teamParing->load(['teamMembers', 'vehicles', 'drivers'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating team pairing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error creating team. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified team pairing.
     */
    public function show(TeamParing $teamParing): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId || $teamParing->company_id !== $companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 401);
            }

            $teamParing->load(['teamMembers', 'vehicles', 'drivers', 'teamLead', 'primaryVehicle', 'primaryDriver']);

            return response()->json([
                'status' => 'success',
                'data' => $teamParing
            ]);

        } catch (\Exception $e) {
            Log::error('Error showing team pairing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error loading team details. Please try again.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified team pairing.
     */
    public function edit(TeamParing $teamParing): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId || $teamParing->company_id !== $companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 401);
            }

            $teamParing->load(['teamMembers', 'vehicles', 'drivers']);

            return response()->json([
                'status' => 'success',
                'data' => $teamParing
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading team for edit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error loading team for editing. Please try again.'
            ], 500);
        }
    }

    /**
     * Update the specified team pairing.
     */
    public function update(TeamParingRequest $request, TeamParing $teamParing): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId || $teamParing->company_id !== $companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 401);
            }

            DB::beginTransaction();

            // Get the authenticated user ID from the appropriate guard
            $userId = $this->getAuthenticatedUserId();

            // Update the team pairing
            $teamParing->update([
                'team_name' => $request->team_name,
                'team_code' => strtoupper($request->team_code),
                'team_location' => $request->team_location,
                'team_status' => $request->team_status,
                'team_allocation' => $request->team_allocation,
                'team_lead' => $request->team_lead,
                'primary_vehicle' => $request->primary_vehicle,
                'primary_driver' => $request->primary_driver,
                'formation_date' => $request->formation_date,
                'contact_number' => $request->contact_number,
                'notes' => $request->notes,
                'updated_by' => $userId,
            ]);

            // Sync team members
            if ($request->team_members) {
                $teamParing->teamMembers()->sync($request->team_members);
            } else {
                $teamParing->teamMembers()->detach();
            }

            // Sync vehicles
            if ($request->assigned_vehicles) {
                $teamParing->vehicles()->sync($request->assigned_vehicles);
            } else {
                $teamParing->vehicles()->detach();
            }

            // Sync drivers
            if ($request->assigned_drivers) {
                $teamParing->drivers()->sync($request->assigned_drivers);
            } else {
                $teamParing->drivers()->detach();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Team updated successfully',
                'data' => $teamParing->load(['teamMembers', 'vehicles', 'drivers'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating team pairing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error updating team. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified team pairing from storage.
     */
    public function destroy(TeamParing $teamParing): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId || $teamParing->company_id !== $companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 401);
            }

            DB::beginTransaction();

            // Detach all relationships
            $teamParing->teamMembers()->detach();
            $teamParing->vehicles()->detach();
            $teamParing->drivers()->detach();

            // Soft delete the team pairing
            $teamParing->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Team deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting team pairing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting team. Please try again.'
            ], 500);
        }
    }

    /**
     * Get team members for dropdown
     */
    public function getTeamMembers(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            Log::info('Getting team members', ['company_id' => $companyId]);
            
            if (!$companyId) {
                // For testing purposes, let's get all team members if no company session
                $members = TeamMember::select('id', 'full_name', 'position', 'employee_id')
                    ->orderBy('full_name')
                    ->get();
                
                Log::info('No company session, returning all team members', ['count' => $members->count()]);
            } else {
                $members = TeamMember::forCompany($companyId)
                    ->select('id', 'full_name', 'position', 'employee_id', 'status')
                    ->orderBy('full_name')
                    ->get();
                    
                Log::info('Team members for company', ['company_id' => $companyId, 'count' => $members->count()]);
            }

            return response()->json([
                'status' => 'success',
                'data' => $members
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading team members', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error loading team members. Please try again.'
            ], 500);
        }
    }

    /**
     * Get vehicles for dropdown
     */
    public function getVehicles(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            Log::info('Getting vehicles', ['company_id' => $companyId]);
            
            if (!$companyId) {
                // For testing purposes, let's get all vehicles if no company session
                $vehicles = Vehicle::select('id', 'registration_number', 'make', 'model', 'type')
                    ->orderBy('registration_number')
                    ->get();
                
                Log::info('No company session, returning all vehicles', ['count' => $vehicles->count()]);
            } else {
                $vehicles = Vehicle::forCompany($companyId)
                    ->select('id', 'registration_number', 'make', 'model', 'type', 'status')
                    ->orderBy('registration_number')
                    ->get();
                    
                Log::info('Vehicles for company', ['company_id' => $companyId, 'count' => $vehicles->count()]);
            }

            return response()->json([
                'status' => 'success',
                'data' => $vehicles
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading vehicles', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error loading vehicles. Please try again.'
            ], 500);
        }
    }

    /**
     * Get drivers for dropdown
     */
    public function getDrivers(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            Log::info('Getting drivers', ['company_id' => $companyId]);
            
            if (!$companyId) {
                // For testing purposes, let's get all drivers if no company session
                $drivers = Driver::select('id', 'full_name', 'license_number', 'license_type')
                    ->orderBy('full_name')
                    ->get();
                
                Log::info('No company session, returning all drivers', ['count' => $drivers->count()]);
            } else {
                $drivers = Driver::forCompany($companyId)
                    ->select('id', 'full_name', 'license_number', 'license_type', 'status')
                    ->orderBy('full_name')
                    ->get();
                    
                Log::info('Drivers for company', ['company_id' => $companyId, 'count' => $drivers->count()]);
            }

            return response()->json([
                'status' => 'success',
                'data' => $drivers
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading drivers', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error loading drivers. Please try again.'
            ], 500);
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getStats(): JsonResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            // Get team counts by status
            $activeTeams = TeamParing::forCompany($companyId)->active()->count();
            $inactiveTeams = TeamParing::forCompany($companyId)->inactive()->count();
            $deployedTeams = TeamParing::forCompany($companyId)->deployed()->count();
            $unavailableTeams = TeamParing::forCompany($companyId)->inMaintenance()->count();

            $stats = [
                'total_teams' => $activeTeams,
                'total_members' => $inactiveTeams,
                'total_vehicles' => $deployedTeams,
                'total_drivers' => $unavailableTeams,
                'active_teams' => $activeTeams,
                'deployed_teams' => $deployedTeams,
                'teams_in_maintenance' => $unavailableTeams,
            ];

            return response()->json([
                'status' => 'success',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading dashboard stats', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error loading statistics. Please try again.'
            ], 500);
        }
    }

    /**
     * Bulk allocation of resources to teams
     */
    public function bulkAllocation(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'teams' => 'required|array|min:1',
                'teams.*' => 'exists:team_paring,id',
                'members' => 'nullable|array',
                'members.*' => 'exists:team_members,id',
                'vehicles' => 'nullable|array',
                'vehicles.*' => 'exists:vehicles,id',
                'drivers' => 'nullable|array',
                'drivers.*' => 'exists:drivers,id',
                'notes' => 'nullable|string|max:500',
            ]);

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            DB::beginTransaction();

            $teams = TeamParing::forCompany($companyId)
                ->whereIn('id', $request->teams)
                ->get();

            $allocatedCount = 0;

            foreach ($teams as $team) {
                $updated = false;

                if ($request->members) {
                    $team->teamMembers()->syncWithoutDetaching($request->members);
                    $updated = true;
                }

                if ($request->vehicles) {
                    $team->vehicles()->syncWithoutDetaching($request->vehicles);
                    $updated = true;
                }

                if ($request->drivers) {
                    $team->drivers()->syncWithoutDetaching($request->drivers);
                    $updated = true;
                }

                if ($updated) {
                    $team->update(['updated_by' => Auth::id()]);
                    $allocatedCount++;
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Bulk allocation completed successfully. {$allocatedCount} teams updated.",
                'data' => ['allocated_count' => $allocatedCount]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk allocation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error applying bulk allocation. Please try again.'
            ], 500);
        }
    }

    /**
     * Export team data
     */
    public function export(Request $request): Response
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            $format = $request->get('export_format', 'csv');
            $includeMembers = $request->boolean('include_members', true);
            $includeVehicles = $request->boolean('include_vehicles', true);
            $includeDrivers = $request->boolean('include_drivers', true);

            $teams = TeamParing::forCompany($companyId)
                ->with(['teamMembers', 'vehicles', 'drivers', 'teamLead', 'primaryVehicle', 'primaryDriver'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Generate export data based on format
            if ($format === 'csv') {
                return $this->exportToCsv($teams, $includeMembers, $includeVehicles, $includeDrivers);
            } elseif ($format === 'xlsx') {
                return $this->exportToExcel($teams, $includeMembers, $includeVehicles, $includeDrivers);
            } else {
                return $this->exportToPdf($teams, $includeMembers, $includeVehicles, $includeDrivers);
            }

        } catch (\Exception $e) {
            Log::error('Error exporting team data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error exporting data. Please try again.'
            ], 500);
        }
    }

    /**
     * Export to CSV format
     */
    private function exportToCsv($teams, $includeMembers, $includeVehicles, $includeDrivers): Response
    {
        $filename = 'team_pairing_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($teams, $includeMembers, $includeVehicles, $includeDrivers) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            $csvHeaders = [
                'Team Name',
                'Team Code',
                'Location',
                'Status',
                'Formation Date',
                'Contact Number',
                'Team Allocation',
                'Notes'
            ];

            if ($includeMembers) {
                $csvHeaders[] = 'Team Members';
                $csvHeaders[] = 'Team Lead';
            }

            if ($includeVehicles) {
                $csvHeaders[] = 'Assigned Vehicles';
                $csvHeaders[] = 'Primary Vehicle';
            }

            if ($includeDrivers) {
                $csvHeaders[] = 'Assigned Drivers';
                $csvHeaders[] = 'Primary Driver';
            }

            fputcsv($file, $csvHeaders);

            // CSV Data
            foreach ($teams as $team) {
                $row = [
                    $team->team_name,
                    $team->team_code,
                    $team->location_formatted,
                    $team->status_formatted,
                    $team->formation_date?->format('Y-m-d'),
                    $team->contact_number,
                    $team->team_allocation,
                    $team->notes
                ];

                if ($includeMembers) {
                    $row[] = $team->teamMembers->pluck('full_name')->join(', ');
                    $row[] = $team->teamLead?->full_name;
                }

                if ($includeVehicles) {
                    $row[] = $team->vehicles->pluck('registration_number')->join(', ');
                    $row[] = $team->primaryVehicle?->registration_number;
                }

                if ($includeDrivers) {
                    $row[] = $team->drivers->pluck('full_name')->join(', ');
                    $row[] = $team->primaryDriver?->full_name;
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to Excel format (placeholder - would need Laravel Excel package)
     */
    private function exportToExcel($teams, $includeMembers, $includeVehicles, $includeDrivers): Response
    {
        // This would require Laravel Excel package implementation
        return response()->json([
            'status' => 'error',
            'message' => 'Excel export not implemented. Please use CSV format.'
        ], 501);
    }

    /**
     * Export to PDF format (placeholder - would need PDF package)
     */
    private function exportToPdf($teams, $includeMembers, $includeVehicles, $includeDrivers): Response
    {
        // This would require PDF package implementation
        return response()->json([
            'status' => 'error',
            'message' => 'PDF export not implemented. Please use CSV format.'
        ], 501);
    }

    /**
     * Generate team report
     */
    public function report(): StreamedResponse
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company session expired. Please login again.'
                ], 401);
            }

            $teams = TeamParing::forCompany($companyId)
                ->with(['teamMembers', 'vehicles', 'drivers', 'teamLead', 'primaryVehicle', 'primaryDriver'])
                ->orderBy('created_at', 'desc')
                ->get();

            $filename = 'team_report_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($teams) {
                $file = fopen('php://output', 'w');
                
                // Report Headers
                fputcsv($file, [
                    'Team Name',
                    'Team Code',
                    'Location',
                    'Status',
                    'Members Count',
                    'Vehicles Count',
                    'Drivers Count',
                    'Assignment Completeness (%)',
                    'Team Lead',
                    'Primary Vehicle',
                    'Primary Driver',
                    'Formation Date',
                    'Contact Number',
                    'Created At'
                ]);

                // Report Data
                foreach ($teams as $team) {
                    fputcsv($file, [
                        $team->team_name,
                        $team->team_code,
                        $team->location_formatted,
                        $team->status_formatted,
                        $team->teamMembers->count(),
                        $team->vehicles->count(),
                        $team->drivers->count(),
                        $team->assignment_completeness,
                        $team->teamLead?->full_name,
                        $team->primaryVehicle?->registration_number,
                        $team->primaryDriver?->full_name,
                        $team->formation_date?->format('Y-m-d'),
                        $team->contact_number,
                        $team->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error generating team report', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error generating report. Please try again.'
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
