@php
/**
 * Roster Management Tab Component
 * 
 * Handles team scheduling and roster management
 */
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Team Roster</h5>
        <div class="d-flex">
            {{-- <button class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#autoScheduleModal">
                <i class="fas fa-robot me-1"></i> Auto Schedule
            </button> --}}
            {{-- <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#newScheduleModal">
                <i class="fas fa-plus me-1"></i> New Schedule
            </button> --}}
            {{-- <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-calendar-week me-1"></i> View
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item view-option active" href="#" data-view="daily"><i class="fas fa-calendar-day me-2"></i>Daily</a></li>
                    <li><a class="dropdown-item view-option" href="#" data-view="weekly"><i class="fas fa-calendar-week me-2"></i>Weekly</a></li>
                    <li><a class="dropdown-item view-option" href="#" data-view="monthly"><i class="fas fa-calendar-alt me-2"></i>Monthly</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" id="teamManagementBtn"><i class="fas a-users-cog me-2"></i>Team Management</a></li>
                </ul>
            </div> --}}
            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exportRosterModal">
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4" id="scheduleControls">
            <div class="col-md-6">
                <label class="form-label">Select Team</label>
                <select class="form-select" id="teamFilter">
                    <option value="all">All Teams</option>
                    @php
                        $teams = $teams ?? [];
                    @endphp
                    @foreach($teams as $team)
                        @php
                            $teamMembersCollection = ($team->teamMembers ?? collect());
                            $teamMembersPayload = $teamMembersCollection->map(function ($member) {
                                return [
                                    'id' => $member->id,
                                    'name' => $member->full_name ?? trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')),
                                    'role' => $member->position ?? $member->role ?? null,
                                    'contact' => $member->phone_number ?? $member->contact_number ?? null,
                                ];
                            })->values();
                            $teamLead = optional($team->teamLead);
                            $teamLeadName = $teamLead->full_name ?? trim(($teamLead->first_name ?? '') . ' ' . ($teamLead->last_name ?? ''));
                            $teamFormationDate = $team->formation_date ? $team->formation_date->format('M d, Y') : '';
                            $teamStatusFormatted = $team->team_status_formatted ?? ucfirst($team->team_status ?? '');
                            $teamLocationFormatted = $team->location_formatted ?? ucfirst(str_replace('-', ' ', $team->team_location ?? ''));
                            $teamSummary = $team->team_summary ?? '';
                        @endphp
                        <option value="{{ $team->id }}"
                            data-team-name="{{ $team->team_name }}"
                            data-team-code="{{ $team->team_code }}"
                            data-team-status="{{ $team->team_status }}"
                            data-team-status-formatted="{{ $teamStatusFormatted }}"
                            data-team-location="{{ $team->team_location }}"
                            data-team-location-formatted="{{ $teamLocationFormatted }}"
                            data-team-lead="{{ $teamLeadName }}"
                            data-team-contact="{{ $team->contact_number }}"
                            data-team-formation-date="{{ $teamFormationDate }}"
                            data-team-notes="{{ $team->notes ?? '' }}"
                            data-team-summary="{{ $teamSummary }}"
                            data-team-members-count="{{ $teamMembersCollection->count() }}"
                            data-team-members='@json($teamMembersPayload)'
                            {{ (isset($teamId) && $teamId == $team->id) ? 'selected' : '' }}>
                            {{ $team->team_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button class="btn btn-primary w-100" id="applyFilters">
                    <i class="fas fa-filter me-1"></i> Apply Filter
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle" id="rosterTable">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">#</th>
                        <th>Team</th>
                        <th>Project</th>
                        <th>Date</th>
                        <th>Members</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @php
                        $assignmentCollection = ($assignments ?? collect());
                        $activeAssignments = $assignmentCollection->filter(function ($assignment) {
                            return !in_array($assignment->status, ['completed', 'cancelled']);
                        });
                        $statusColors = [
                            'pending' => 'warning',
                            'in_progress' => 'info',
                            'completed' => 'success',
                            'cancelled' => 'secondary',
                        ];
                        $statusIcons = [
                            'pending' => 'exclamation-circle',
                            'in_progress' => 'spinner',
                            'completed' => 'check-circle',
                            'cancelled' => 'ban',
                        ];
                    @endphp
                    @forelse($activeAssignments as $index => $assignment)
                    <tr data-team-id="{{ $assignment->team_id }}" data-assigned-date="{{ $assignment->assigned_date ? $assignment->assigned_date->format('Y-m-d') : '' }}">
                        <td class="text-center fw-bold">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2">
                                    <span class="avatar avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center">
                                        <i class="fas fa-users"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $assignment->team->team_name ?? 'N/A' }}</h6>
                                    <small class="text-muted">Code: {{ $assignment->team->team_code ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary me-2">
                                    <i class="fas fa-project-diagram"></i>
                                </span>
                                <div>
                                    <h6 class="mb-0">{{ $assignment->site_name ?? $assignment->customer->customer_name ?? 'N/A' }}</h6>
                                    <small class="text-muted">{{ $assignment->customer->location ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-medium">
                                    {{ $assignment->assigned_date ? $assignment->assigned_date->format('D, d M Y') : 'N/A' }}
                                </span>
                                <small class="text-muted">Assigned</small>
                            </div>
                        </td>
                        <td>
                            <div class="avatar-group">
                                @php
                                    $members = optional($assignment->team)->teamMembers ?? collect();
                                    $members = collect($members);
                                    $displayCount = min(3, $members->count());
                                    $memberDetails = $members->map(function ($member) {
                                        return [
                                            'name' => $member->full_name ?? 'N/A',
                                            'role' => $member->position ?? $member->role ?? null,
                                            'contact' => $member->phone_number ?? $member->contact_number ?? null,
                                        ];
                                    })->values();
                                @endphp
                                @foreach($members->take($displayCount) as $member)
                                    <span class="avatar avatar-xs rounded-circle bg-primary text-white" data-bs-toggle="tooltip" title="{{ $member->full_name ?? 'N/A' }}">
                                        {{ strtoupper(substr($member->full_name ?? 'N', 0, 1)) }}{{ strtoupper(substr(str_word_count($member->full_name ?? '') > 1 ? collect(explode(' ', $member->full_name ?? ''))->last() : '', 0, 1)) }}
                                    </span>
                                @endforeach
                                @if($members->count() > 3)
                                    <span class="avatar avatar-xs rounded-circle bg-secondary text-white" data-bs-toggle="tooltip" title="+{{ $members->count() - 3 }} more">+{{ $members->count() - 3 }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                <span>{{ $assignment->customer->location ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusKey = strtolower($assignment->status ?? '');
                                $statusColor = $statusColors[$statusKey] ?? 'secondary';
                                $statusIcon = $statusIcons[$statusKey] ?? 'circle';
                            @endphp
                            <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }}">
                                <i class="fas fa-{{ $statusIcon }} me-1"></i> {{ ucfirst(str_replace('_', ' ', $assignment->status ?? 'N/A')) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary view-roster"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewRosterModal"
                                    data-team-name="{{ $assignment->team->team_name ?? 'N/A' }}"
                                    data-project-name="{{ $assignment->site_name ?? $assignment->customer->customer_name ?? 'N/A' }}"
                                    data-priority="{{ strtolower($assignment->priority ?? 'medium') }}"
                                    data-status="{{ strtolower($assignment->status ?? 'pending') }}"
                                    data-location="{{ $assignment->customer->location ?? 'N/A' }}"
                                    data-assigned-date="{{ $assignment->assigned_date ? $assignment->assigned_date->format('D, d M Y') : 'N/A' }}"
                                    data-notes="{{ $assignment->description ? e($assignment->description) : 'No notes available.' }}"
                                    data-team-members='@json($memberDetails)'
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning edit-roster"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editRosterModal"
                                    data-roster-id="{{ $assignment->id }}"
                                    data-customer-id="{{ $assignment->customer_id }}"
                                    data-roster-team-id="{{ $assignment->team_id }}"
                                    data-roster-date="{{ $assignment->assigned_date ? $assignment->assigned_date->format('Y-m-d') : '' }}"
                                    data-roster-status="{{ $assignment->status ?? 'pending' }}"
                                    data-roster-priority="{{ $assignment->priority ?? 'medium' }}"
                                    data-roster-shift-type="{{ $assignment->shift_type ?? 'full_day' }}"
                                    data-roster-notes="{{ $assignment->description ?? '' }}"
                                    data-roster-update-url="{{ route('company.home-connection.site-assignments.update', $assignment->id) }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('company.home-connection.site-assignments.destroy', $assignment->id) }}" method="POST" class="d-inline delete-roster-form"
                                    data-roster-name="{{ $assignment->customer->customer_name ?? 'Assignment' }}"
                                    data-roster-id="{{ $assignment->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                        data-roster-name="{{ $assignment->customer->customer_name ?? 'this assignment' }}"
                                        data-roster-id="{{ $assignment->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                <p class="mb-0">No active site assignments available.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(isset($assignments) && method_exists($assignments, 'hasPages') && $assignments->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }} assignments
                </div>
                <div>
                    {{ $assignments->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="editRosterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Schedule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRosterForm" method="POST" action="#">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="editCustomer">Customer <span class="text-danger">*</span></label>
                            <select class="form-select" id="editCustomer" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}">{{ $site->customer_name ?? $site->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="editRosterTeam">Team <span class="text-danger">*</span></label>
                            <select class="form-select" id="editRosterTeam" name="team_id" required>
                                <option value="">Select Team</option>
                                @foreach($teams as $teamOption)
                                    <option value="{{ $teamOption->id }}">{{ $teamOption->team_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="editRosterDate">Assigned Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="editRosterDate" name="assigned_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="editRosterStatus">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="editRosterStatus" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="editRosterPriority">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" id="editRosterPriority" name="priority" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="editRosterShift">Shift Type</label>
                            <select class="form-select" id="editRosterShift" name="shift_type">
                                <option value="full_day">Full Day</option>
                                <option value="morning">Morning</option>
                                <option value="afternoon">Afternoon</option>
                                <option value="night">Night</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="editRosterNotes">Notes</label>
                            <textarea class="form-control" id="editRosterNotes" name="description" rows="3" placeholder="Add any special instructions or notes"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Roster Modal -->
<div class="modal fade" id="viewRosterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Assignment Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Team:</strong> <span id="viewRosterTeam"></span></p>
                        <p><strong>Project:</strong> <span id="viewRosterProject"></span></p>
                        <p><strong>Date:</strong> <span id="viewRosterDate"></span></p>
                        <p><strong>Priority:</strong> <span id="viewRosterPriority"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Location:</strong> <span id="viewRosterLocation"></span></p>
                        <p><strong>Status:</strong> <span id="viewRosterStatus"></span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Team Members</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody id="viewRosterMembers">
                                    <tr>
                                        <td class="text-center text-muted">No team members assigned</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Notes</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0" id="viewRosterNotes">No notes available.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Schedule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editScheduleForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="schedule_id" id="editScheduleId">
                <div class="modal-body">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editTeam" class="form-label">Team</label>
                                    <select class="form-select" id="editTeam" name="team">
                                        <option value="team1">Team Alpha</option>
                                        <option value="team2">Team Beta</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editProject" class="form-label">Project</label>
                                    <select class="form-select" id="editProject" name="project">
                                        <option value="PRJ-AC-2023-001">Accra Central Grid (PRJ-AC-2023-001)</option>
                                        <option value="PRJ-KS-2023-015">Kumasi Network Upgrade (PRJ-KS-2023-015)</option>
                                        <option value="PRJ-TM-2023-042">Tamale Solar Farm (PRJ-TM-2023-042)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Shift Date</label>
                            <input type="date" class="form-control" name="shift_date" id="editShiftDate" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" id="editLocation" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="editStatus" required>
                                <option value="scheduled">Scheduled</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Team Members</label>
                        <select class="form-select" multiple name="team_members[]" id="editTeamMembers" required>
                            <option value="1">John Doe (Team Lead)</option>
                            <option value="2">Jane Smith (Technician)</option>
                            <option value="3">Mike Johnson (Technician)</option>
                            <option value="4">Sarah Williams (Safety Officer)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" id="editNotes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Schedule Confirmation Modal -->
<div class="modal fade" id="deleteScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteScheduleForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="schedule_id" id="deleteScheduleId">
                <div class="modal-body">
                    <p>Are you sure you want to delete this schedule? This action cannot be undone.</p>
                    <div class="alert alert-warning mb-0">
                        <strong>Warning:</strong> This will permanently remove the schedule and all associated data.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Roster Modal -->
<div class="modal fade" id="exportRosterModal" tabindex="-1" aria-labelledby="exportRosterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportRosterModalLabel">
                    <i class="fas fa-download me-2"></i>Export Team Rosters
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('home-connection.export-rosters') }}" method="POST" id="exportRosterForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exportTeamFilter" class="form-label">Filter by Team (Optional)</label>
                        <select class="form-select" id="exportTeamFilter" name="team_id">
                            <option value="all">All Teams</option>
                            @if(isset($teams) && $teams->count() > 0)
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}"
                                        {{ (isset($teamId) && $teamId == $team->id) ? 'selected' : '' }}>
                                        {{ $team->team_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="form-text">Leave as "All Teams" to export all assignments, or select a specific team to export only that team's assignments.</div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Export Details:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Exports team roster schedules with assignment details</li>
                            <li>Includes customer information, team details, and schedule data</li>
                            <li>File will be downloaded as CSV format</li>
                            <li>Filtered results will respect your current team selection</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="exportRosterBtn">
                        <i class="fas fa-download me-1"></i>Export CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .avatar-sm {
        width: 1.5rem;
        height: 1.5rem;
        font-size: 0.625rem;
    }

    .avatar-xs {
        width: 1.25rem;
        height: 1.25rem;
        font-size: 0.5rem;
        margin-left: -0.5rem;
        border: 2px solid #fff;
    }

    .avatar-xs:first-child {
        margin-left: 0;
    }

    .avatar-group {
        display: flex;
        flex-wrap: wrap;
        padding-left: 0.5rem;
    }

    #rosterTable th {
        white-space: nowrap;
        vertical-align: middle;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.6875rem;
        letter-spacing: 0.5px;
    }

    #rosterTable td {
        vertical-align: middle;
    }

    .table-hover > tbody > tr:hover {
        --bs-table-accent-bg: rgba(var(--bs-primary-rgb), 0.03);
    }
</style>
@endpush

@push('javascript')
<script>
console.log('Roster management script loaded');

function initializeRosterManagementWithjQuery() {
    console.log('initializeRosterManagementWithjQuery called');
    const $ = window.jQuery;
    if (!$) {
        console.log('jQuery not found');
        return;
    }

    $(document).ready(function () {
        console.log('Document ready in roster management');
        const teamFilterSelect = $('#teamFilter');
        const weekStartInput = $('#weekStartDate');
        const teamDetailsCardEl = $('#teamDetailsCard');
        const teamDetailsNameEl = $('#teamDetailName');
        const teamDetailsCodeEl = $('#teamDetailCode');
        const teamDetailsStatusEl = $('#teamDetailStatus');
        const teamDetailsSummaryEl = $('#teamDetailSummary');
        const teamDetailsFormationEl = $('#teamDetailFormation');
        const teamDetailsLocationEl = $('#teamDetailLocation');
        const teamDetailsLeadEl = $('#teamDetailLead');
        const teamDetailsContactEl = $('#teamDetailContact');
        const teamDetailsNotesEl = $('#teamDetailNotes');
        const teamDetailsMembersList = $('#teamDetailMembers');
        const teamDetailsMembersEmpty = $('#teamDetailMembersEmpty');

        const teamStatusClassMap = {
            'active': 'bg-success bg-opacity-10 text-success',
            'deployed': 'bg-primary bg-opacity-10 text-primary',
            'inactive': 'bg-secondary bg-opacity-10 text-secondary',
            'maintenance': 'bg-warning bg-opacity-10 text-dark'
        };

        let rosterTable = null;
        let teamFilterValue = teamFilterSelect.val() || 'all';
        let weekFilterStart = null;
        let weekFilterEnd = null;

        const parseFilterDate = function (value) {
            if (!value) {
                return null;
            }

            const parts = value.split('-');
            if (parts.length !== 3) {
                return null;
            }

            const year = parseInt(parts[0], 10);
            const month = parseInt(parts[1], 10) - 1;
            const day = parseInt(parts[2], 10);

            const parsedDate = new Date(year, month, day);
            return isNaN(parsedDate.getTime()) ? null : parsedDate;
        };

        const evaluateFilter = function (rowTeamId, rowDateStr) {
            if (teamFilterValue && teamFilterValue !== 'all') {
                if ((rowTeamId || '').toString() !== teamFilterValue.toString()) {
                    return false;
                }
            }

            if (weekFilterStart && weekFilterEnd) {
                const rowDate = parseFilterDate(rowDateStr);
                if (!rowDate) {
                    return false;
                }

                const startTime = weekFilterStart.getTime();
                const endTime = weekFilterEnd.getTime();
                const rowTime = rowDate.getTime();

                if (rowTime < startTime || rowTime > endTime) {
                    return false;
                }
            }

            return true;
        };

        const updateTeamDetailsCard = function () {
            const selectedOption = teamFilterSelect.find('option:selected');

            if (!selectedOption.length || !selectedOption.val() || selectedOption.val() === 'all') {
                teamDetailsCardEl.addClass('d-none');
                return;
            }

            const getAttr = (attr) => selectedOption.attr(attr) || '';

            const teamData = {
                name: getAttr('data-team-name') || selectedOption.text().trim(),
                code: getAttr('data-team-code') || '',
                status: getAttr('data-team-status') || '',
                statusFormatted: getAttr('data-team-status-formatted') || '',
                summary: getAttr('data-team-summary') || '',
                location: getAttr('data-team-location') || '',
                locationFormatted: getAttr('data-team-location-formatted') || '',
                lead: getAttr('data-team-lead') || '',
                contact: getAttr('data-team-contact') || '',
                formation: getAttr('data-team-formation-date') || '',
                notes: getAttr('data-team-notes') || '',
                membersCount: parseInt(getAttr('data-team-members-count'), 10) || 0,
                membersPayload: getAttr('data-team-members') || ''
            };

            let members = [];
            if (teamData.membersPayload) {
                try {
                    members = JSON.parse(teamData.membersPayload);
                } catch (error) {
                    console.warn('Unable to parse team members payload', error);
                }
            }

            teamDetailsNameEl.text(teamData.name || 'Unnamed Team');

            if (teamData.code) {
                teamDetailsCodeEl.text(teamData.code).removeClass('d-none');
            } else {
                teamDetailsCodeEl.addClass('d-none');
            }

            const statusKey = (teamData.status || '').toLowerCase();
            const statusFormatted = teamData.statusFormatted || teamData.status || 'N/A';
            const statusClass = teamStatusClassMap[statusKey] || 'bg-secondary bg-opacity-10 text-secondary';
            teamDetailsStatusEl.attr('class', `badge rounded-pill px-3 py-2 ${statusClass}`);
            teamDetailsStatusEl.text(statusFormatted);

            if (teamData.summary) {
                teamDetailsSummaryEl.text(teamData.summary).removeClass('d-none');
            } else if (teamData.membersCount) {
                teamDetailsSummaryEl.text(`${teamData.membersCount} member${teamData.membersCount === 1 ? '' : 's'}`).removeClass('d-none');
            } else {
                teamDetailsSummaryEl.addClass('d-none');
            }

            teamDetailsFormationEl.text(teamData.formation || 'N/A');
            teamDetailsLocationEl.text(teamData.locationFormatted || teamData.location || 'N/A');
            teamDetailsLeadEl.text(teamData.lead || 'N/A');
            teamDetailsContactEl.text(teamData.contact || 'N/A');
            teamDetailsNotesEl.text(teamData.notes || 'No notes recorded for this team.');

            teamDetailsMembersList.empty();

            if (Array.isArray(members) && members.length) {
                members.forEach(function (member) {
                    const memberName = member.name || 'Unnamed Member';
                    const memberRole = member.role ? `<span class="text-muted small d-block">${member.role}</span>` : '';
                    const memberContact = member.contact ? `<span class="text-muted small">${member.contact}</span>` : '';

                    const item = $(`
                        <li class="list-group-item px-0">
                            <div class="d-flex justify-content-between align">`}}}

(function waitForjQueryAndInit(attempts) {
    if (typeof window.jQuery === 'undefined') {
        if (attempts > 0) {
            setTimeout(function () {
                waitForjQueryAndInit(attempts - 1);
            }, 50);
        } else {
            console.error('Roster management script: jQuery is not available.');
        }
        return;
    }

    initializeRosterManagementWithjQuery();
})(40);

(function () {
    console.log('View roster modal IIFE starting');
    const modalElement = document.getElementById('viewRosterModal');
    console.log('Modal element found:', !!modalElement);
    if (!modalElement) {
        console.warn('Roster modal initializer: no modal element found');
        return;
    }

    const bootstrapModal = (typeof bootstrap !== 'undefined' && bootstrap.Modal)
        ? bootstrap.Modal.getOrCreateInstance(modalElement)
        : null;

    const fallbackModal = (typeof window.jQuery !== 'undefined' && typeof window.jQuery.fn.modal === 'function')
        ? window.jQuery
        : null;

    function setText(id, value) {
        const el = document.getElementById(id);
        if (el) {
            el.textContent = value || 'N/A';
        }
    }

    function formatPriorityText(priority) {
        if (!priority) {
            return 'N/A';
        }
        return priority.charAt(0).toUpperCase() + priority.slice(1);
    }

    function formatStatusText(status) {
        if (!status) {
            return 'N/A';
        }
        return status.replace(/_/g, ' ').replace(/\b\w/g, function (char) { return char.toUpperCase(); });
    }

    function getPriorityBadgeClass(priority) {
        switch (priority) {
            case 'low':
                return 'bg-secondary';
            case 'medium':
                return 'bg-primary';
            case 'high':
                return 'bg-warning';
            case 'critical':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    function getStatusBadgeClass(status) {
        switch (status) {
            case 'pending':
                return 'bg-warning';
            case 'in_progress':
                return 'bg-info';
            case 'completed':
                return 'bg-success';
            case 'cancelled':
                return 'bg-secondary';
            default:
                return 'bg-secondary';
        }
    }

    function renderMembers(payload) {
        const target = document.getElementById('viewRosterMembers');
        if (!target) {
            return;
        }

        let members = [];
        if (payload) {
            try {
                members = JSON.parse(payload) || [];
            } catch (error) {
                console.warn('Unable to parse team members payload', error);
            }
        }

        if (Array.isArray(members) && members.length) {
            target.innerHTML = members.map(function (member) {
                const name = member && member.name ? member.name : 'N/A';
                return `<tr><td>${name}</td></tr>`;
            }).join('');
        } else {
            target.innerHTML = '<tr><td class="text-center text-muted">No team members assigned</td></tr>';
        }
    }

    function populateRosterViewModalForDisplay(trigger) {
        console.log('populateRosterViewModalForDisplay called with trigger:', trigger);
        if (!trigger) {
            console.log('No trigger provided to populateRosterViewModalForDisplay');
            return;
        }

        const dataset = trigger.dataset || {};
        console.log('Populating modal with dataset:', dataset);

        setText('viewRosterTeam', dataset.teamName || 'N/A');
        setText('viewRosterProject', dataset.projectName || 'N/A');
        setText('viewRosterDate', dataset.assignedDate || 'N/A');
        setText('viewRosterLocation', dataset.location || 'N/A');

        const notesEl = document.getElementById('viewRosterNotes');
        if (notesEl) {
            notesEl.textContent = dataset.notes || 'No notes available.';
        }

        const priorityBadge = document.getElementById('viewRosterPriority');
        if (priorityBadge) {
            const priority = dataset.priority || 'medium';
            priorityBadge.innerHTML = `<span class="badge ${getPriorityBadgeClass(priority)}">${formatPriorityText(priority)}</span>`;
        }

        const statusBadge = document.getElementById('viewRosterStatus');
        if (statusBadge) {
            const status = dataset.status || 'pending';
            statusBadge.innerHTML = `<span class="badge ${getStatusBadgeClass(status)}">${formatStatusText(status)}</span>`;
        }

        renderMembers(dataset.teamMembers);

        console.log('Modal populated successfully with data:', {
            team: dataset.teamName,
            project: dataset.projectName,
            assignedDate: dataset.assignedDate,
            location: dataset.location,
            priority: dataset.priority,
            status: dataset.status,
            notes: dataset.notes,
            membersPayload: dataset.teamMembers
        });
    }

    window.populateRosterViewModal = populateRosterViewModal;
    window.initializeRosterViewModal = populateRosterViewModal;
    console.log('Roster modal helper registered:', typeof window.populateRosterViewModal);

    document.body.addEventListener('click', function (event) {
        console.log('Body click event fired');
        const trigger = event.target.closest('.view-roster');
        if (!trigger) {
            console.log('No view-roster trigger found');
            return;
        }

        console.log('View roster button clicked, populating modal after delay');

        // Use setTimeout to let Bootstrap open the modal first
        setTimeout(function() {
            populateRosterViewModalForDisplay(trigger);
        }, 100);
    }, true);

    console.log('Roster modal initializer ready:', {
        modalFound: !!modalElement,
        hasBootstrap: !!bootstrapModal,
        hasjQueryFallback: !!fallbackModal
    });
})();
</script>

<!-- Test inline script -->
<script>
console.log('Inline test script running');

function populateRosterViewModalForDisplay(trigger) {
    console.log('populateRosterViewModalForDisplay called with trigger:', trigger);
    if (!trigger) {
        console.log('No trigger provided to populateRosterViewModalForDisplay');
        return;
    }

    const dataset = trigger.dataset || {};
    console.log('Populating modal with dataset:', dataset);

    // Set text content for simple fields
    const setText = (id, value) => {
        const el = document.getElementById(id);
        if (el) {
            el.textContent = value || 'N/A';
        } else {
            console.log('Element not found:', id);
        }
    };

    setText('viewRosterTeam', dataset.teamName || 'N/A');
    setText('viewRosterProject', dataset.projectName || 'N/A');
    setText('viewRosterDate', dataset.assignedDate || 'N/A');
    setText('viewRosterLocation', dataset.location || 'N/A');

    const notesEl = document.getElementById('viewRosterNotes');
    if (notesEl) {
        notesEl.textContent = dataset.notes || 'No notes available.';
    }

    // Handle priority badge
    const priorityBadge = document.getElementById('viewRosterPriority');
    if (priorityBadge) {
        const priority = dataset.priority || 'medium';
        let badgeClass = 'bg-secondary';
        switch (priority) {
            case 'low': badgeClass = 'bg-secondary'; break;
            case 'medium': badgeClass = 'bg-primary'; break;
            case 'high': badgeClass = 'bg-warning'; break;
            case 'critical': badgeClass = 'bg-danger'; break;
        }
        priorityBadge.innerHTML = `<span class="badge ${badgeClass}">${priority.charAt(0).toUpperCase() + priority.slice(1)}</span>`;
    }

    // Handle status badge
    const statusBadge = document.getElementById('viewRosterStatus');
    if (statusBadge) {
        const status = dataset.status || 'pending';
        let badgeClass = 'bg-secondary';
        switch (status) {
            case 'pending': badgeClass = 'bg-warning'; break;
            case 'in_progress': badgeClass = 'bg-info'; break;
            case 'completed': badgeClass = 'bg-success'; break;
            case 'cancelled': badgeClass = 'bg-secondary'; break;
        }
        const formattedStatus = status.replace(/_/g, ' ').replace(/\b\w/g, function(char) { return char.toUpperCase(); });
        statusBadge.innerHTML = `<span class="badge ${badgeClass}">${formattedStatus}</span>`;
    }

    // Handle team members
    const membersTarget = document.getElementById('viewRosterMembers');
    if (membersTarget) {
        let members = [];
        if (dataset.teamMembers) {
            try {
                members = JSON.parse(dataset.teamMembers) || [];
            } catch (error) {
                console.warn('Unable to parse team members payload', error);
            }
        }

        if (Array.isArray(members) && members.length) {
            membersTarget.innerHTML = members.map(function (member) {
                const name = member && member.name ? member.name : 'N/A';
                return `<tr><td>${name}</td></tr>`;
            }).join('');
        } else {
            membersTarget.innerHTML = '<tr><td class="text-center text-muted">No team members assigned</td></tr>';
        }
    }

    console.log('Modal populated successfully with data:', {
        team: dataset.teamName,
        project: dataset.projectName,
        assignedDate: dataset.assignedDate,
        location: dataset.location,
        priority: dataset.priority,
        status: dataset.status,
        notes: dataset.notes,
        membersPayload: dataset.teamMembers
    });
}

document.addEventListener('click', function(e) {
    if (e.target.closest('.view-roster')) {
        console.log('View roster button clicked (inline script)');
        const trigger = e.target.closest('.view-roster');
        console.log('Trigger dataset:', trigger.dataset);

        // Try to populate immediately
        populateRosterViewModalForDisplay(trigger);
    }

    // Handle edit roster button click
    if (e.target.closest('.edit-roster')) {
        console.log('Edit roster button clicked');
        const trigger = e.target.closest('.edit-roster');
        console.log('Edit trigger dataset:', trigger.dataset);

        populateEditRosterModal(trigger);
    }
});

function populateEditRosterModal(trigger) {
    console.log('Populating edit modal with trigger:', trigger);
    if (!trigger) {
        console.log('No trigger provided for edit modal');
        return;
    }

    const dataset = trigger.dataset || {};
    console.log('Edit modal dataset:', dataset);

    // Set the form action URL
    const form = document.getElementById('editRosterForm');
    if (form && dataset.rosterUpdateUrl) {
        form.action = dataset.rosterUpdateUrl;
        console.log('Set form action to:', dataset.rosterUpdateUrl);
    }

    // Populate form fields
    setFieldValue('editCustomer', dataset.customerId || '');
    setFieldValue('editRosterTeam', dataset.rosterTeamId || '');
    setFieldValue('editRosterDate', dataset.rosterDate || '');
    setFieldValue('editRosterStatus', dataset.rosterStatus || '');
    setFieldValue('editRosterPriority', dataset.rosterPriority || 'medium');
    setFieldValue('editRosterShift', dataset.rosterShiftType || 'full_day');
    setFieldValue('editRosterNotes', dataset.rosterNotes || '');

    console.log('Edit modal populated successfully');
}

function setFieldValue(fieldId, value) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.value = value;
        console.log(`Set ${fieldId} to:`, value);
    } else {
        console.log(`Field not found: ${fieldId}`);
    }
}

// Handle edit form submission
const editForm = document.getElementById('editRosterForm');
if (editForm) {
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Edit form submitted');

        const formData = new FormData(this);
        console.log('Form data being sent:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

        console.log('Submitting to URL:', this.action);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.json();
        })
        .then(data => {
            console.log('Edit response:', data);
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editRosterModal'));
                if (modal) {
                    modal.hide();
                }

                // Show success message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Roster assignment updated successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                // Reload the page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to update roster assignment');
            }
        })
        .catch(error => {
            console.error('Edit error:', error);

            // Show error message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Failed to update roster assignment. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
}

// Handle delete form submission
document.addEventListener('submit', function(e) {
    if (e.target.classList.contains('delete-roster-form')) {
        e.preventDefault();
        console.log('Delete form submitted');

        const form = e.target;
        const rosterName = form.dataset.rosterName || 'this assignment';
        const rosterId = form.dataset.rosterId;

        // Show confirmation dialog
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete the assignment for "${rosterName}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitDeleteForm(form);
                }
            });
        } else {
            // Fallback if Swal is not available
            if (confirm(`Are you sure you want to delete the assignment for "${rosterName}"?`)) {
                submitDeleteForm(form);
            }
        }
    }
});

function submitDeleteForm(form) {
    console.log('Submitting delete form');

    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': formData.get('_token'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Delete response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Delete response:', data);
        if (data.success) {
            // Show success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'Assignment has been deleted successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            // Reload the page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Failed to delete assignment');
        }
    })
    .catch(error => {
        console.error('Delete error:', error);

        // Show error message
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Failed to delete assignment. Please try again.',
                confirmButtonText: 'OK'
            });
        } else {
            alert('Error: ' + (error.message || 'Failed to delete assignment'));
        }
    });
}

// Handle filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const applyFiltersBtn = document.getElementById('applyFilters');
    const teamFilter = document.getElementById('teamFilter');
    const rosterTable = document.getElementById('rosterTable');
    const tableRows = rosterTable ? rosterTable.querySelectorAll('tbody tr') : [];

    // Apply filters on button click - redirect with team parameter
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const selectedTeamId = teamFilter ? teamFilter.value : 'all';
            console.log('Applying team filter (server-side):', selectedTeamId);

            // Build URL with team parameter
            const currentUrl = new URL(window.location.href);
            if (selectedTeamId && selectedTeamId !== 'all') {
                currentUrl.searchParams.set('team_id', selectedTeamId);
            } else {
                currentUrl.searchParams.delete('team_id');
            }

            // Redirect to the filtered URL
            window.location.href = currentUrl.toString();
        });
    }

    // Auto-apply filter when "All Teams" is selected
    if (teamFilter) {
        teamFilter.addEventListener('change', function() {
            if (this.value === 'all') {
                console.log('All Teams selected, redirecting to show all assignments');

                // Build URL without team parameter
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('team_id');

                // Redirect to the URL without team filter
                window.location.href = currentUrl.toString();
            }
        });
    }

    // Handle export functionality
    const exportRosterBtn = document.getElementById('exportRosterBtn');
    const exportRosterForm = document.getElementById('exportRosterForm');

    if (exportRosterBtn && exportRosterForm) {
        exportRosterForm.addEventListener('submit', function(e) {
            // Show loading state
            exportRosterBtn.disabled = true;
            exportRosterBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Exporting...';

            // Close modal after a short delay (file download will start)
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('exportRosterModal'));
                if (modal) {
                    modal.hide();
                }

                // Reset button state and reload page
                exportRosterBtn.disabled = false;
                exportRosterBtn.innerHTML = '<i class="fas fa-download me-1"></i>Export CSV';

                // Show success message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Export Started',
                        text: 'Your CSV file is being prepared for download.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }

                // Reload the page after a short delay to refresh the data
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }, 1000);
        });
    }
});
</script>

@endpush
