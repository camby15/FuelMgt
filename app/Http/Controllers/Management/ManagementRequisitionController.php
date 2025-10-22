<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ManagementRequisitionController extends Controller
{
    /**
     * Display requisitions pending management approval
     */
    public function index()
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return redirect()->back()->with('error', 'Company session expired. Please login again.');
            }

            // Get requisitions pending management approval
            $requisitions = Requisition::with(['requestor.personalInfo', 'departmentCategory'])
                ->where('company_id', $companyId)
                ->where('status', 'created')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            // Manually load project manager and team leader for each requisition
            foreach ($requisitions as $requisition) {
                $this->loadProjectManagerForRequisition($requisition);
                $this->loadTeamLeaderForRequisition($requisition, $companyId);
            }

            // Get statistics
            $stats = $this->getStatistics($companyId);

            // Return JSON response for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'stats' => $stats,
                    'data' => $requisitions
                ]);
            }
            
            return view('company.Management.requisition-approval', compact('requisitions', 'stats'));

        } catch (\Exception $e) {
            Log::error('Error fetching management requisitions', [
                'error' => $e->getMessage(),
                'company_id' => Session::get('selected_company_id')
            ]);

            return redirect()->back()->with('error', 'Failed to load requisitions');
        }
    }

    /**
     * Get statistics for the dashboard
     */
    private function getStatistics($companyId)
    {
        return [
            'pending_approvals' => Requisition::where('company_id', $companyId)
                ->where('status', 'created')
                ->count(),
            'high_priority' => Requisition::where('company_id', $companyId)
                ->where('status', 'created')
                ->whereIn('priority', [Requisition::PRIORITY_HIGH, Requisition::PRIORITY_URGENT])
                ->count(),
            'approved' => Requisition::where('company_id', $companyId)
                ->where('status', 'approved')
                ->count(),
            'rejected' => Requisition::where('company_id', $companyId)
                ->where('status', Requisition::STATUS_REJECTED)
                ->count(),
        ];
    }

    /**
     * Load team leader for a requisition with company_id constraint
     */
    private function loadTeamLeaderForRequisition($requisition, $companyId)
    {
        Log::info('loadTeamLeaderForRequisition called', [
            'requisition_id' => $requisition->id,
            'team_leader_id' => $requisition->team_leader_id,
            'company_id' => $companyId
        ]);
        
        if ($requisition->team_leader_id) {
            // First try without company_id constraint to find the team leader
            $teamLeader = \App\Models\TeamMember::find($requisition->team_leader_id);
            
            Log::info('TeamMember query result', [
                'team_leader_id' => $requisition->team_leader_id,
                'found_team_member' => $teamLeader ? true : false,
                'team_member_data' => $teamLeader
            ]);
            
            if ($teamLeader) {
                // Manually set the relationship
                $requisition->setRelation('teamLeader', $teamLeader);
                
                // Also set it as an attribute to ensure it's included in JSON
                $requisition->teamLeader = $teamLeader;
                
                Log::info('Relationship set, verifying', [
                    'requisition_id' => $requisition->id,
                    'team_leader_after_set' => $requisition->teamLeader,
                    'relationship_loaded' => $requisition->relationLoaded('teamLeader')
                ]);
                
                if ($teamLeader->company_id == $companyId) {
                    Log::info('Team leader loaded successfully with matching company_id', [
                        'requisition_id' => $requisition->id,
                        'team_leader_id' => $requisition->team_leader_id,
                        'team_leader_name' => $teamLeader->full_name,
                        'company_id' => $companyId
                    ]);
                } else {
                    Log::warning('Team leader loaded with different company_id', [
                        'requisition_id' => $requisition->id,
                        'team_leader_id' => $requisition->team_leader_id,
                        'team_leader_name' => $teamLeader->full_name,
                        'team_leader_company_id' => $teamLeader->company_id,
                        'session_company_id' => $companyId
                    ]);
                }
            } else {
                Log::error('Team leader not found in database', [
                    'requisition_id' => $requisition->id,
                    'team_leader_id' => $requisition->team_leader_id,
                    'company_id' => $companyId
                ]);
            }
        } else {
            Log::info('No team_leader_id found', [
                'requisition_id' => $requisition->id,
                'team_leader_id' => $requisition->team_leader_id
            ]);
        }
    }

    /**
     * Load project manager for a requisition
     */
    private function loadProjectManagerForRequisition($requisition)
    {
        if ($requisition->project_manager_id) {
            // Try to find project manager
            $projectManager = \App\Models\CompanySubUser::find($requisition->project_manager_id);
            
            if ($projectManager) {
                $requisition->setRelation('projectManager', $projectManager);
                // Also set it as an attribute to ensure it's included in JSON
                $requisition->projectManager = $projectManager;
                
                Log::info('Project manager loaded successfully', [
                    'requisition_id' => $requisition->id,
                    'project_manager_id' => $requisition->project_manager_id,
                    'project_manager_name' => $projectManager->name ?? $projectManager->first_name ?? 'No name'
                ]);
            } else {
                Log::warning('Project manager not found', [
                    'requisition_id' => $requisition->id,
                    'project_manager_id' => $requisition->project_manager_id
                ]);
            }
        }
    }

    /**
     * Load team members for a requisition
     */
    private function loadTeamMembersForRequisition($requisition, $companyId)
    {
        Log::info('=== LOADING TEAM MEMBERS FOR REQUISITION ===', [
            'requisition_id' => $requisition->id,
            'team_leader_id' => $requisition->team_leader_id,
            'company_id' => $companyId
        ]);

        $teamMembers = collect();
        
        if ($requisition->team_leader_id) {
            // Step 1: Check if team leader exists
            $teamLeader = \App\Models\TeamMember::find($requisition->team_leader_id);
            
            Log::info('Step 1: Team leader lookup', [
                'team_leader_id' => $requisition->team_leader_id,
                'team_leader_found' => $teamLeader ? true : false,
                'team_leader_name' => $teamLeader ? $teamLeader->full_name : 'Not found'
            ]);
            
            if ($teamLeader) {
                // Step 2: Look for team pairing with this team leader
                $teamPairing = \App\Models\TeamParing::where('team_lead', $requisition->team_leader_id)
                    ->where('company_id', $companyId)
                    ->with('teamMembers', 'teamLead')
                    ->first();
                
                Log::info('Step 2: Team pairing lookup', [
                    'team_pairing_found' => $teamPairing ? true : false,
                    'team_pairing_id' => $teamPairing ? $teamPairing->id : null,
                    'team_pairing_name' => $teamPairing ? $teamPairing->team_name : 'Not found'
                ]);
                
                if ($teamPairing) {
                    // Step 3: Get team members from team pairing
                    $teamMembers = $teamPairing->teamMembers;
                    
                    Log::info('Step 3: Team pairing members', [
                        'team_members_count' => $teamMembers->count(),
                        'team_members' => $teamMembers->map(function($member) {
                            return [
                                'id' => $member->id,
                                'name' => $member->full_name,
                                'position' => $member->position
                            ];
                        })->toArray()
                    ]);
                    
                    // Include the team lead in the list only if not already present
                    if ($teamPairing->teamLead && !$teamMembers->contains('id', $teamPairing->teamLead->id)) {
                        $teamMembers->prepend($teamPairing->teamLead);
                        Log::info('Step 4: Added team lead to members', [
                            'team_lead_id' => $teamPairing->teamLead->id,
                            'team_lead_name' => $teamPairing->teamLead->full_name
                        ]);
                    } else {
                        Log::info('Step 4: Team lead already in members list', [
                            'team_lead_id' => $teamPairing->teamLead ? $teamPairing->teamLead->id : null,
                            'team_lead_name' => $teamPairing->teamLead ? $teamPairing->teamLead->full_name : 'No team lead'
                        ]);
                    }
                } else {
                    // Step 3: No team pairing found - get all company team members
                    $teamMembers = \App\Models\TeamMember::where('company_id', $companyId)->get();
                    
                    Log::info('Step 3: No team pairing - using all company members', [
                        'company_team_members_count' => $teamMembers->count()
                    ]);
                    
                    // Ensure team leader is included
                    if ($teamLeader && !$teamMembers->contains('id', $teamLeader->id)) {
                        $teamMembers->prepend($teamLeader);
                        Log::info('Step 4: Added team leader to company members', [
                            'team_leader_id' => $teamLeader->id,
                            'team_leader_name' => $teamLeader->full_name
                        ]);
                    }
                }
            } else {
                Log::warning('Team leader not found in database', [
                    'team_leader_id' => $requisition->team_leader_id
                ]);
            }
        } else {
            // No team leader assigned - get all team members from company
            $teamMembers = \App\Models\TeamMember::where('company_id', $companyId)->get();
            Log::info('No team leader assigned - using all company members', [
                'company_team_members_count' => $teamMembers->count()
            ]);
        }
        
        // Final safety check
        if ($teamMembers->isEmpty() && $requisition->team_leader_id) {
            $teamLeader = \App\Models\TeamMember::find($requisition->team_leader_id);
            if ($teamLeader) {
                $teamMembers = collect([$teamLeader]);
                Log::info('Final safety check: team leader only', [
                    'team_leader_id' => $teamLeader->id,
                    'team_leader_name' => $teamLeader->full_name
                ]);
            }
        }
        
        Log::info('=== FINAL TEAM MEMBERS RESULT ===', [
            'final_team_members_count' => $teamMembers->count(),
            'final_team_members' => $teamMembers->map(function($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->full_name,
                    'position' => $member->position
                ];
            })->toArray()
        ]);
        
        // Calculate team member statistics
        $teamMemberStats = $this->calculateTeamMemberStatistics($teamMembers);
        
        // Add team members to requisition object - ensure it's always an array
        $requisition->team_members = $teamMembers->toArray();
        
        // Add team member statistics
        $requisition->team_member_stats = $teamMemberStats;
        
        return $requisition;
    }

    /**
     * Calculate team member statistics
     */
    private function calculateTeamMemberStatistics($teamMembers)
    {
        $stats = [];
        
        foreach ($teamMembers as $member) {
            // TeamMember has employee_id, but Requisition.requester_id points to Employee
            // So we need to use the employee_id to find requisitions
            $employeeId = $member->employee_id;
            
            if (!$employeeId) {
                // If no employee_id, skip statistics for this member
                $stats[] = [
                    'member' => $member,
                    'stats' => [
                        'total' => 0,
                        'pending' => 0,
                        'approved' => 0,
                        'rejected' => 0,
                        'last_activity' => 'N/A'
                    ]
                ];
                continue;
            }
            
            // Get requisition counts for this team member's employee record
            $totalRequisitions = \App\Models\Requisition::where('requester_id', $employeeId)->count();
            $pendingRequisitions = \App\Models\Requisition::where('requester_id', $employeeId)
                ->where('status', 'created')->count();
            $approvedRequisitions = \App\Models\Requisition::where('requester_id', $employeeId)
                ->where('status', 'approved')->count();
            $rejectedRequisitions = \App\Models\Requisition::where('requester_id', $employeeId)
                ->where('status', \App\Models\Requisition::STATUS_REJECTED)->count();
            
            // Get last activity (most recent requisition date)
            $lastRequisition = \App\Models\Requisition::where('requester_id', $employeeId)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $lastActivity = $lastRequisition ? $lastRequisition->created_at->format('M d, Y') : 'Never';
            
            $stats[] = [
                'member' => $member,
                'stats' => [
                    'total' => $totalRequisitions,
                    'pending' => $pendingRequisitions,
                    'approved' => $approvedRequisitions,
                    'rejected' => $rejectedRequisitions,
                    'last_activity' => $lastActivity
                ]
            ];
        }
        
        return $stats;
    }

    /**
     * Check team members in database for debugging
     */
    public function checkTeamMembers(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $teamLeaderId = $request->team_leader_id;
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired'
                ], 401);
            }

            Log::info('Checking team members in database', [
                'team_leader_id' => $teamLeaderId,
                'company_id' => $companyId
            ]);

            // Get team leader information
            $teamLeader = \App\Models\TeamMember::find($teamLeaderId);
            
            // Get team pairing information
            $teamPairing = \App\Models\TeamParing::where('team_lead', $teamLeaderId)
                ->where('company_id', $companyId)
                ->with('teamMembers')
                ->first();

            // Get all team members for the company
            $allCompanyTeamMembers = \App\Models\TeamMember::where('company_id', $companyId)->get();

            // Get team members from team pairing if exists
            $teamPairingMembers = collect();
            if ($teamPairing) {
                $teamPairingMembers = $teamPairing->teamMembers;
            }

            return response()->json([
                'success' => true,
                'team_leader' => $teamLeader,
                'team_pairing' => $teamPairing,
                'team_pairing_members' => $teamPairingMembers,
                'all_company_members' => $allCompanyTeamMembers,
                'team_members' => $teamPairingMembers->count() > 0 ? $teamPairingMembers : $allCompanyTeamMembers,
                'debug_info' => [
                    'team_leader_exists' => $teamLeader ? true : false,
                    'team_pairing_exists' => $teamPairing ? true : false,
                    'team_pairing_id' => $teamPairing ? $teamPairing->id : null,
                    'team_pairing_name' => $teamPairing ? $teamPairing->team_name : null,
                    'total_company_members' => $allCompanyTeamMembers->count(),
                    'team_pairing_members_count' => $teamPairingMembers->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking team members in database', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error checking team members: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get requisitions for AJAX requests
     */
    public function getRequisitions(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            Log::info('Getting requisitions for company: ' . $companyId);
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired'
                ], 401);
            }

            $query = Requisition::with(['requestor.personalInfo', 'departmentCategory'])
                ->where('company_id', $companyId)
                ->where('status', 'created');
            

            // Apply filters
            if ($request->has('priority') && $request->priority) {
                $query->where('priority', $request->priority);
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('requisition_number', 'like', "%{$search}%")
                      ->orWhere('title', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%");
                });
            }

            // Check if this is an export request
            if ($request->has('export') && $request->export) {
                $requisitions = $query->orderBy('created_at', 'desc')->get();
                $stats = $this->getStatistics($companyId);
                
                // Load team members for export data
                foreach ($requisitions as $requisition) {
                    $this->loadTeamMembersForRequisition($requisition, $companyId);
                }
                
                return response()->json([
                    'success' => true,
                    'data' => $requisitions,
                    'stats' => $stats
                ]);
            }
            
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            
            $requisitions = $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
            $stats = $this->getStatistics($companyId);
            

            // Load team members for each requisition
            $requisitionsWithTeamMembers = $requisitions->items();
            foreach ($requisitionsWithTeamMembers as $requisition) {
                // Manually load relationships
                $this->loadTeamLeaderForRequisition($requisition, $companyId);
                $this->loadProjectManagerForRequisition($requisition);
                
                // Debug team leader relationship
                Log::info('Team Leader Relationship Debug', [
                    'requisition_id' => $requisition->id,
                    'team_leader_id' => $requisition->team_leader_id,
                    'team_leader_loaded' => $requisition->teamLeader ? true : false,
                    'team_leader_name' => $requisition->teamLeader ? $requisition->teamLeader->full_name : 'Not loaded',
                    'company_id' => $companyId
                ]);
                
                $this->loadTeamMembersForRequisition($requisition, $companyId);
            }

            Log::info('Found ' . $requisitions->count() . ' requisitions on page ' . $page);

            return response()->json([
                'success' => true,
                'data' => $requisitionsWithTeamMembers,
                'pagination' => [
                    'current_page' => $requisitions->currentPage(),
                    'last_page' => $requisitions->lastPage(),
                    'per_page' => $requisitions->perPage(),
                    'total' => $requisitions->total(),
                    'from' => $requisitions->firstItem(),
                    'to' => $requisitions->lastItem()
                ],
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching management requisitions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'company_id' => Session::get('selected_company_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch requisitions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show requisition details
     */
    public function show($id)
    {
        Log::info('=== MANAGEMENT SHOW METHOD START ===', [
            'requisition_id' => $id,
            'request_method' => request()->method(),
            'request_url' => request()->fullUrl(),
            'user_id' => Auth::id(),
            'company_id_from_session' => Session::get('selected_company_id')
        ]);
        
        try {
            $companyId = Session::get('selected_company_id');
            
            Log::info('Management show method called', [
                'requisition_id' => $id,
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);
            
            // First check if requisition exists at all
            $exists = Requisition::where('id', $id)->exists();
            Log::info('Requisition existence check', [
                'requisition_id' => $id,
                'exists' => $exists
            ]);
            
            if (!$exists) {
                Log::error('Requisition not found in database', ['requisition_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Requisition not found in database'
                ], 404);
            }
            
            // Check if requisition exists for this company
            $companyExists = Requisition::where('id', $id)->where('company_id', $companyId)->exists();
            Log::info('Company requisition check', [
                'requisition_id' => $id,
                'company_id' => $companyId,
                'exists_for_company' => $companyExists
            ]);
            
            if (!$companyExists) {
                Log::error('Requisition not found for company', [
                    'requisition_id' => $id,
                    'company_id' => $companyId
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Requisition not found for this company'
                ], 404);
            }
            
            $requisition = Requisition::with(['requestor.personalInfo', 'requestor.user', 'departmentCategory'])
                ->where('company_id', $companyId)
                ->findOrFail($id);

            // Debug the IDs before loading relationships
            Log::info('Requisition IDs before loading relationships', [
                'requisition_id' => $id,
                'project_manager_id' => $requisition->project_manager_id,
                'team_leader_id' => $requisition->team_leader_id,
                'requester_id' => $requisition->requester_id
            ]);

            // Manually load relationships
            $this->loadTeamLeaderForRequisition($requisition, $companyId);
            $this->loadProjectManagerForRequisition($requisition);
            $this->loadTeamMembersForRequisition($requisition, $companyId);
            
            // Manually load requestor personal info if not loaded
            if ($requisition->requestor) {
                // Always reload personalInfo to ensure we have the latest data
                $requisition->requestor->load('personalInfo');
                
                // Direct database query to check if personal info exists
                $directPersonalInfo = \App\Models\HrEmploymentPersonalInfo::where('employee_id', $requisition->requestor->id)->first();
                
                Log::info('Requestor personalInfo debug', [
                    'requisition_id' => $id,
                    'requestor_id' => $requisition->requestor->id,
                    'requestor_staff_id' => $requisition->requestor->staff_id,
                    'personal_info_loaded' => $requisition->requestor->relationLoaded('personalInfo'),
                    'personal_info' => $requisition->requestor->personalInfo,
                    'personal_info_first_name' => $requisition->requestor->personalInfo ? $requisition->requestor->personalInfo->first_name : 'No personal info',
                    'personal_info_last_name' => $requisition->requestor->personalInfo ? $requisition->requestor->personalInfo->last_name : 'No personal info',
                    'direct_personal_info' => $directPersonalInfo,
                    'direct_first_name' => $directPersonalInfo ? $directPersonalInfo->first_name : 'No direct personal info',
                    'direct_last_name' => $directPersonalInfo ? $directPersonalInfo->last_name : 'No direct personal info'
                ]);
            }

            Log::info('Team leader debug', [
                'requisition_id' => $id,
                'team_leader_id' => $requisition->team_leader_id,
                'team_leader' => $requisition->teamLeader,
                'team_members_count' => $requisition->team_members ? count($requisition->team_members) : 0,
                'company_id' => $companyId
            ]);
            
            // Additional debug - check if relationships exist
            Log::info('Relationship Debug', [
                'requisition_id' => $id,
                'has_team_leader_relation' => $requisition->relationLoaded('teamLeader'),
                'team_leader_object' => $requisition->teamLeader,
                'has_project_manager_relation' => $requisition->relationLoaded('projectManager'),
                'project_manager_object' => $requisition->projectManager,
                'has_requestor_relation' => $requisition->relationLoaded('requestor'),
                'requestor_object' => $requisition->requestor,
                'requestor_personal_info' => $requisition->requestor ? $requisition->requestor->personalInfo : 'No requestor',
                'requestor_user' => $requisition->requestor ? $requisition->requestor->user : 'No user',
                'requestor_staff_id' => $requisition->requestor ? $requisition->requestor->staff_id : 'No staff_id'
            ]);
            
            // Prepare the response data with explicit relationship inclusion
            $responseData = $requisition->toArray();
            
            // Explicitly add project manager data
            if ($requisition->projectManager) {
                $responseData['projectManager'] = $requisition->projectManager->toArray();
                $responseData['project_manager'] = $requisition->projectManager->toArray(); // Alternative key
            } else {
                $responseData['projectManager'] = null;
                $responseData['project_manager'] = null;
            }
            
            // Explicitly add team leader data  
            if (isset($requisition->teamLeader)) {
                $responseData['teamLeader'] = $requisition->teamLeader->toArray();
                $responseData['team_leader'] = $requisition->teamLeader->toArray(); // Alternative key
            } else {
                $responseData['teamLeader'] = null;
                $responseData['team_leader'] = null;
            }
            
            // Add team members if they exist
            if (isset($requisition->team_members)) {
                $responseData['team_members'] = $requisition->team_members;
            }
            
            if (isset($requisition->team_member_stats)) {
                $responseData['team_member_stats'] = $requisition->team_member_stats;
            }
            
            Log::info('Final response data for modal', [
                'requisition_id' => $id,
                'has_project_manager' => isset($responseData['projectManager']),
                'project_manager_name' => isset($responseData['projectManager']) ? 
                    ($responseData['projectManager']['name'] ?? $responseData['projectManager']['first_name'] ?? 'No name') : 'NULL',
                'has_team_leader' => isset($responseData['teamLeader']),
                'team_leader_name' => isset($responseData['teamLeader']) ? 
                    ($responseData['teamLeader']['full_name'] ?? $responseData['teamLeader']['name'] ?? 'No name') : 'NULL'
            ]);
            
            Log::info('=== MANAGEMENT SHOW METHOD SUCCESS ===', [
                'requisition_id' => $id,
                'response_data_keys' => array_keys($responseData),
                'success' => true
            ]);

            return response()->json([
                'success' => true,
                'data' => $responseData
            ]);

        } catch (\Exception $e) {
            Log::error('=== MANAGEMENT SHOW METHOD ERROR ===', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'requisition_id' => $id,
                'company_id' => Session::get('selected_company_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Requisition not found: ' . $e->getMessage()
            ], 404);
        } finally {
            Log::info('=== MANAGEMENT SHOW METHOD END ===', ['requisition_id' => $id]);
        }
    }

    /**
     * Approve requisition (Management approval)
     */
    public function approve(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $request->validate([
                'notes' => 'nullable|string|max:1000',
                'item_allocations' => 'nullable|array'
            ]);

            $requisition = Requisition::where('company_id', $companyId)
                ->where('status', 'created')
                ->findOrFail($id);

            // Update requisition status to pending
            $requisition->update([
                'status' => 'pending',
                'management_status' => 'approved',
                'management_approved_by' => Auth::id(),
                'management_approved_at' => now(),
                'management_notes' => $request->notes
            ]);

            // Handle item allocations if provided
            if ($request->has('item_allocations') && is_array($request->item_allocations)) {
                $itemAllocations = [];
                
                foreach ($request->item_allocations as $itemIndex => $allocations) {
                    if (is_array($allocations)) {
                        foreach ($allocations as $allocation) {
                            if (isset($allocation['quantity']) && $allocation['quantity'] > 0) {
                                $itemAllocations[] = [
                                    'item_index' => $itemIndex,
                                    'location' => $allocation['location'] ?? 'Main Store',
                                    'quantity' => $allocation['quantity']
                                ];
                            }
                        }
                        
                        // Validate total allocation doesn't exceed available quantity
                        if (isset($requisition->items[$itemIndex])) {
                            $item = $requisition->items[$itemIndex];
                            $totalAllocated = array_sum(array_column($allocations, 'quantity'));
                            $availableQty = $item['quantity'] ?? 0;
                            
                            if ($totalAllocated > $availableQty) {
                                return response()->json([
                                    'success' => false,
                                    'message' => "Item " . ($itemIndex + 1) . ": Total allocation ({$totalAllocated}) cannot exceed available quantity ({$availableQty})"
                                ], 400);
                            }
                        }
                    }
                }
                
                // Store item allocations in the requisition
                $requisition->update([
                    'item_allocations' => $itemAllocations
                ]);
            }

            Log::info('Requisition approved by management', [
                'requisition_id' => $id,
                'approved_by' => Auth::id(),
                'company_id' => $companyId,
                'notes' => $request->notes,
                'has_allocations' => $request->has('allocations')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Requisition approved successfully',
                'data' => $requisition->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error approving requisition', [
                'error' => $e->getMessage(),
                'requisition_id' => $id,
                'company_id' => Session::get('selected_company_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve requisition'
            ], 500);
        }
    }

    /**
     * Update requisition
     */
    public function update(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // Log the incoming request for debugging
            Log::info('Update requisition request', [
                'requisition_id' => $id,
                'request_data' => $request->all(),
                'items_count' => $request->has('items') ? count($request->items) : 0,
                'items_data' => $request->items,
                'title' => $request->title,
                'total_amount' => $request->total_amount
            ]);

            // Validate basic fields first
            $request->validate([
                'title' => 'required|string|max:255',
                'priority' => 'required|in:low,medium,high,urgent',
                'project_manager_id' => 'required|exists:users,id',
                'team_leader_id' => 'nullable|exists:team_members,id',
                'items' => 'required|array',
                'total_amount' => 'required|numeric|min:0'
            ]);

            // Validate items with more detailed error handling
            if ($request->has('items')) {
                foreach ($request->items as $index => $item) {
                    if (!isset($item['item_id']) || empty($item['item_id'])) {
                        throw new \Exception("Item {$index} is missing item_id");
                    }
                    if (!isset($item['quantity']) || $item['quantity'] <= 0) {
                        throw new \Exception("Item {$index} has invalid quantity");
                    }
                    if (!isset($item['unit_price']) || $item['unit_price'] < 0) {
                        throw new \Exception("Item {$index} has invalid unit_price");
                    }
                    
                    // Check if item exists in central_stores
                    $centralStoreItem = \App\Models\CentralStore::find($item['item_id']);
                    if (!$centralStoreItem) {
                        throw new \Exception("Item {$index} (ID: {$item['item_id']}) not found in central store");
                    }
                }
            }

            $requisition = Requisition::where('company_id', $companyId)
                ->where('status', 'created')
                ->findOrFail($id);

            $requisition->update([
                'title' => $request->title,
                'priority' => $request->priority,
                'project_manager_id' => $request->project_manager_id,
                'team_leader_id' => $request->team_leader_id,
                'items' => $request->items,
                'total_amount' => $request->total_amount
            ]);

            Log::info('Requisition updated by management', [
                'requisition_id' => $id,
                'updated_by' => Auth::id(),
                'company_id' => $companyId,
                'changes' => $request->only(['title', 'priority', 'project_manager_id', 'team_leader_id', 'items', 'total_amount']),
                'items_count' => count($request->items),
                'total_amount' => $request->total_amount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Requisition updated successfully',
                'data' => $requisition->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating requisition', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'requisition_id' => $id,
                'company_id' => Session::get('selected_company_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update requisition: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject requisition
     */
    public function reject(Request $request, $id)
    {
        Log::info('=== REJECT REQUISITION DEBUG START ===', [
            'requisition_id' => $id,
            'request_data' => $request->all(),
            'auth_user_id' => Auth::id(),
            'company_id' => Session::get('selected_company_id')
        ]);

        try {
            $companyId = Session::get('selected_company_id');
            
            Log::info('Company ID retrieved', ['company_id' => $companyId]);
            
            if (!$companyId) {
                Log::error('No company ID in session');
                return response()->json([
                    'success' => false,
                    'message' => 'Company session expired'
                ], 401);
            }

            Log::info('Starting validation', [
                'reason' => $request->reason,
                'comments' => $request->comments
            ]);
            
            $request->validate([
                'reason' => 'required|string|max:500',
                'comments' => 'nullable|string|max:1000'
            ]);

            Log::info('Validation passed, searching for requisition', [
                'id' => $id,
                'company_id' => $companyId
            ]);

            $requisition = Requisition::where('company_id', $companyId)
                ->where('status', 'created')
                ->findOrFail($id);

            Log::info('Requisition found', [
                'requisition_id' => $requisition->id,
                'current_status' => $requisition->status,
                'current_management_status' => $requisition->management_status,
                'title' => $requisition->title
            ]);

            Log::info('About to update requisition with data', [
                'status' => Requisition::STATUS_REJECTED,
                'management_status' => 'rejected',
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->reason,
                'management_notes' => $request->comments
            ]);

            $requisition->update([
                'status' => Requisition::STATUS_REJECTED,
                'management_status' => 'rejected',
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->reason,
                'management_notes' => $request->comments
            ]);

            Log::info('Requisition update successful', [
                'updated_status' => $requisition->fresh()->status,
                'updated_management_status' => $requisition->fresh()->management_status
            ]);

            Log::info('Requisition rejected by management', [
                'requisition_id' => $id,
                'rejected_by' => Auth::id(),
                'company_id' => $companyId,
                'reason' => $request->reason,
                'comments' => $request->comments
            ]);

            $responseData = $requisition->fresh();
            Log::info('Response data prepared', ['response_data' => $responseData]);

            return response()->json([
                'success' => true,
                'message' => 'Requisition rejected successfully',
                'data' => $responseData
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in reject method', [
                'validation_errors' => $e->errors(),
                'requisition_id' => $id,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Requisition not found', [
                'requisition_id' => $id,
                'company_id' => Session::get('selected_company_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Requisition not found or not eligible for rejection'
            ], 404);

        } catch (\Exception $e) {
            Log::error('General error rejecting requisition', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'requisition_id' => $id,
                'company_id' => Session::get('selected_company_id'),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject requisition: ' . $e->getMessage()
            ], 500);
        } finally {
            Log::info('=== REJECT REQUISITION DEBUG END ===');
        }
    }

    /**
     * Bulk approve requisitions
     */
    public function bulkApprove(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $request->validate([
                'requisition_ids' => 'required|array',
                'requisition_ids.*' => 'exists:requisitions,id'
            ]);

            $requisitionIds = $request->requisition_ids;
            $approvedCount = 0;

            foreach ($requisitionIds as $requisitionId) {
                $requisition = Requisition::where('company_id', $companyId)
                    ->where('status', 'created')
                    ->find($requisitionId);

                if ($requisition) {
                    $requisition->update([
                        'status' => 'pending',
                        'management_status' => 'approved',
                        'approved_by' => Auth::id(),
                        'approved_at' => now()
                    ]);
                    $approvedCount++;
                }
            }

            Log::info('Bulk approval by management', [
                'requisition_ids' => $requisitionIds,
                'approved_count' => $approvedCount,
                'approved_by' => Auth::id(),
                'company_id' => $companyId
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully approved {$approvedCount} requisition(s)",
                'approved_count' => $approvedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error in bulk approval', [
                'error' => $e->getMessage(),
                'company_id' => Session::get('selected_company_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve requisitions'
            ], 500);
        }
    }
}
