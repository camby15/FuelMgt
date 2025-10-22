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
            $requisitions = Requisition::with(['requestor.personalInfo', 'departmentCategory', 'projectManager', 'teamLeader'])
                ->where('company_id', $companyId)
                ->where('status', Requisition::STATUS_PENDING)
                ->orderBy('created_at', 'desc')
                ->paginate(15);

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
                ->where('status', Requisition::STATUS_PENDING)
                ->count(),
            'high_priority' => Requisition::where('company_id', $companyId)
                ->where('status', Requisition::STATUS_PENDING)
                ->whereIn('priority', [Requisition::PRIORITY_HIGH, Requisition::PRIORITY_URGENT])
                ->count(),
            'approved' => Requisition::where('company_id', $companyId)
                ->where('status', Requisition::STATUS_MANAGEMENT_APPROVED)
                ->count(),
            'rejected' => Requisition::where('company_id', $companyId)
                ->where('status', Requisition::STATUS_REJECTED)
                ->count(),
        ];
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
                    
                    // Include the team lead in the list
                    if ($teamPairing->teamLead) {
                        $teamMembers->prepend($teamPairing->teamLead);
                        Log::info('Step 4: Added team lead to members', [
                            'team_lead_id' => $teamPairing->teamLead->id,
                            'team_lead_name' => $teamPairing->teamLead->full_name
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
        
        // Add team members to requisition object - ensure it's always an array
        $requisition->team_members = $teamMembers->toArray();
        
        return $requisition;
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

            $query = Requisition::with(['requestor.personalInfo', 'departmentCategory', 'projectManager', 'teamLeader'])
                ->where('company_id', $companyId)
                ->where('status', Requisition::STATUS_PENDING);

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
        try {
            $companyId = Session::get('selected_company_id');
            
            $requisition = Requisition::with(['requestor.personalInfo', 'departmentCategory', 'projectManager', 'teamLeader'])
                ->where('company_id', $companyId)
                ->where('status', Requisition::STATUS_PENDING)
                ->findOrFail($id);

            // Load team members using the same method as the main requisitions list
            $this->loadTeamMembersForRequisition($requisition, $companyId);

            Log::info('Team leader debug', [
                'requisition_id' => $id,
                'team_leader_id' => $requisition->team_leader_id,
                'team_leader' => $requisition->teamLeader,
                'team_members_count' => $requisition->team_members ? count($requisition->team_members) : 0
            ]);
            
            // Get team members - debug the process step by step
            $teamMembers = collect();
            
            Log::info('=== TEAM MEMBERS DEBUG START ===', [
                'requisition_id' => $id,
                'team_leader_id' => $requisition->team_leader_id,
                'company_id' => $companyId
            ]);
            
            if ($requisition->team_leader_id) {
                // Step 1: Check if team leader exists
                $teamLeader = \App\Models\TeamMember::find($requisition->team_leader_id);
                Log::info('Step 1: Team leader lookup', [
                    'team_leader_id' => $requisition->team_leader_id,
                    'team_leader_found' => $teamLeader ? true : false,
                    'team_leader_name' => $teamLeader ? $teamLeader->full_name : 'Not found'
                ]);
                
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
                    
                    // Include the team lead in the list
                    if ($teamPairing->teamLead) {
                        $teamMembers->prepend($teamPairing->teamLead);
                        Log::info('Step 4: Added team lead to members', [
                            'team_lead_id' => $teamPairing->teamLead->id,
                            'team_lead_name' => $teamPairing->teamLead->full_name
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
            
            Log::info('=== TEAM MEMBERS DEBUG END ===', [
                'final_team_members_count' => $teamMembers->count(),
                'final_team_members' => $teamMembers->map(function($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->full_name,
                        'position' => $member->position
                    ];
                })->toArray()
            ]);
            
            // Get team member statistics
            $teamMemberStats = collect();
            foreach ($teamMembers as $member) {
                // Try to find employee by email or name to get requisitions
                $employee = \App\Models\Employee::where('company_id', $companyId)
                    ->where('email', $member->email)
                    ->first();
                
                if ($employee) {
                    // Get requisitions for this employee
                    $memberRequisitions = Requisition::where('company_id', $companyId)
                        ->where('requester_id', $employee->id)
                        ->get();
                    
                    $stats = [
                        'total' => $memberRequisitions->count(),
                        'pending' => $memberRequisitions->where('status', 'pending')->count(),
                        'approved' => $memberRequisitions->where('status', 'approved')->count(),
                        'rejected' => $memberRequisitions->where('status', 'rejected')->count(),
                        'last_activity' => $memberRequisitions->max('created_at') ? $memberRequisitions->max('created_at')->format('Y-m-d') : 'Never'
                    ];
                } else {
                    // If no employee found, show zero stats
                    $stats = [
                        'total' => 0,
                        'pending' => 0,
                        'approved' => 0,
                        'rejected' => 0,
                        'last_activity' => 'Never'
                    ];
                }
                
                $teamMemberStats->push([
                    'member' => $member,
                    'stats' => $stats
                ]);
            }
            
            $requisition->team_members = $teamMembers;
            $requisition->team_member_stats = $teamMemberStats;

            // Debug logging
            Log::info('Requisition details for management approval', [
                'requisition_id' => $id,
                'project_manager_id' => $requisition->project_manager_id,
                'project_manager' => $requisition->projectManager,
                'project_manager_name' => $requisition->projectManager ? $requisition->projectManager->fullname : 'NULL',
                'team_leader_id' => $requisition->team_leader_id,
                'team_leader' => $requisition->teamLeader,
                'team_leader_name' => $requisition->teamLeader ? $requisition->teamLeader->full_name : 'NULL',
                'team_members_count' => $teamMembers->count(),
                'team_members' => $teamMembers->toArray(),
                'team_member_stats' => $teamMemberStats->toArray()
            ]);

            // Test the data structure before sending
            $testData = [
                'id' => $requisition->id,
                'title' => $requisition->title,
                'requisition_number' => $requisition->requisition_number,
                'priority' => $requisition->priority,
                'department' => $requisition->department,
                'description' => $requisition->description,
                'notes' => $requisition->notes,
                'items' => $requisition->items,
                'total_amount' => $requisition->total_amount,
                'requisition_date' => $requisition->requisition_date,
                'required_date' => $requisition->required_date,
                'project_manager_id' => $requisition->project_manager_id,
                'team_leader_id' => $requisition->team_leader_id,
                'requestor' => [
                    'id' => $requisition->requestor ? $requisition->requestor->id : null,
                    'name' => $requisition->requestor ? $requisition->requestor->name : null,
                    'fullname' => $requisition->requestor ? $requisition->requestor->fullname : null,
                    'email' => $requisition->requestor ? $requisition->requestor->email : null,
                    'personalInfo' => $requisition->requestor && $requisition->requestor->personalInfo ? [
                        'first_name' => $requisition->requestor->personalInfo->first_name,
                        'last_name' => $requisition->requestor->personalInfo->last_name,
                    ] : null
                ],
                'projectManager' => $requisition->projectManager ? [
                    'id' => $requisition->projectManager->id,
                    'fullname' => $requisition->projectManager->fullname,
                    'name' => $requisition->projectManager->name,
                    'email' => $requisition->projectManager->email,
                ] : null,
                'teamLeader' => $requisition->teamLeader ? [
                    'id' => $requisition->teamLeader->id,
                    'full_name' => $requisition->teamLeader->full_name,
                    'name' => $requisition->teamLeader->name,
                    'email' => $requisition->teamLeader->email,
                ] : null,
                'departmentCategory' => $requisition->departmentCategory ? [
                    'id' => $requisition->departmentCategory->id,
                    'name' => $requisition->departmentCategory->name,
                ] : null,
            ];

            Log::info('Test data structure for edit modal', $testData);
            
            // Additional debugging for project manager
            Log::info('Project Manager Debug Info', [
                'project_manager_id' => $requisition->project_manager_id,
                'projectManager_relationship' => $requisition->projectManager,
                'projectManager_fullname' => $requisition->projectManager ? $requisition->projectManager->fullname : 'NULL',
                'testData_projectManager' => $testData['projectManager']
            ]);

            return response()->json([
                'success' => true,
                'data' => $testData
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching requisition details', [
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
                ->where('status', Requisition::STATUS_PENDING)
                ->findOrFail($id);

            // Update requisition status to management approved
            $requisition->update([
                'status' => Requisition::STATUS_MANAGEMENT_APPROVED,
                'management_status' => 'approved',
                'management_approved_by' => Auth::id(),
                'management_approved_at' => now(),
                'management_notes' => $request->notes,
            ]);

            // Handle item-level allocations if provided
            if ($request->has('item_allocations') && is_array($request->item_allocations) && !empty($request->item_allocations)) {
                $itemAllocations = $request->item_allocations;
                
                Log::info('Processing item allocations', [
                    'requisition_id' => $id,
                    'item_allocations' => $itemAllocations
                ]);
                
                // Validate each item's total allocation doesn't exceed available quantity
                $items = $requisition->items ?? [];
                foreach ($itemAllocations as $itemIndex => $allocations) {
                    if (is_array($allocations)) {
                        $item = $items[$itemIndex] ?? null;
                        if ($item) {
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
                    'team_allocations' => $itemAllocations
                ]);
                
                Log::info('Item allocations set for requisition', [
                    'requisition_id' => $id,
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
     * Update requisition (Management edit)
     */
    public function update(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $request->validate([
                'title' => 'required|string|max:255',
                'priority' => 'required|string|in:low,medium,high,urgent',
                'department' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'notes' => 'nullable|string',
                'project_manager_id' => 'nullable|exists:company_sub_users,id',
                'team_leader_id' => 'nullable|exists:team_members,id',
                'items' => 'required|array|min:1',
                'items.*.item_name' => 'required|string|max:255',
                'items.*.description' => 'nullable|string',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'total_amount' => 'required|numeric|min:0'
            ]);

            $requisition = Requisition::where('company_id', $companyId)
                ->where('status', Requisition::STATUS_PENDING)
                ->findOrFail($id);

            // Update requisition
            $requisition->update([
                'title' => $request->title,
                'priority' => $request->priority,
                'department' => $request->department,
                'description' => $request->description,
                'notes' => $request->notes,
                'project_manager_id' => $request->project_manager_id,
                'team_leader_id' => $request->team_leader_id,
                'items' => $request->items,
                'total_amount' => $request->total_amount,
            ]);

            Log::info('Requisition updated by management', [
                'requisition_id' => $id,
                'updated_by' => Auth::id(),
                'company_id' => $companyId,
                'title' => $request->title,
                'priority' => $request->priority,
                'project_manager_id' => $request->project_manager_id,
                'team_leader_id' => $request->team_leader_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Requisition updated successfully',
                'data' => $requisition->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating requisition', [
                'error' => $e->getMessage(),
                'requisition_id' => $id,
                'company_id' => Session::get('selected_company_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update requisition'
            ], 500);
        }
    }

    /**
     * Reject requisition (Management rejection)
     */
    public function reject(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $request->validate([
                'reason' => 'required|string|max:100',
                'comments' => 'nullable|string|max:1000'
            ]);

            $requisition = Requisition::where('company_id', $companyId)
                ->where('status', Requisition::STATUS_PENDING)
                ->findOrFail($id);

            // Update requisition status to rejected
            $requisition->update([
                'status' => Requisition::STATUS_REJECTED,
                'management_status' => 'rejected',
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->reason . ($request->comments ? ' - ' . $request->comments : ''),
                'management_notes' => $request->comments,
            ]);

            Log::info('Requisition rejected by management', [
                'requisition_id' => $id,
                'rejected_by' => Auth::id(),
                'company_id' => $companyId,
                'reason' => $request->reason,
                'comments' => $request->comments
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Requisition rejected successfully',
                'data' => $requisition->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error rejecting requisition', [
                'error' => $e->getMessage(),
                'requisition_id' => $id,
                'company_id' => Session::get('selected_company_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject requisition'
            ], 500);
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

            foreach ($requisitionIds as $id) {
                $requisition = Requisition::where('company_id', $companyId)
                    ->where('status', Requisition::STATUS_PENDING)
                    ->find($id);

                if ($requisition) {
                    $requisition->update([
                        'status' => Requisition::STATUS_MANAGEMENT_APPROVED,
                        'management_approved_by' => Auth::id(),
                        'management_approved_at' => now(),
                    ]);
                    $approvedCount++;
                }
            }

            Log::info('Bulk approval by management', [
                'approved_count' => $approvedCount,
                'total_requested' => count($requisitionIds),
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