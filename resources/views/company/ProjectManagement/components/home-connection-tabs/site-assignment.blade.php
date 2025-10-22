@php
/**
 * Site Assignment & Issues Tab Component
 */
@endphp

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Site Assignment & Issues</h5>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshAssignmentsBtn">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignSiteModal">
                    <i class="fas fa-plus me-1"></i> New Assignment
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-4" id="siteTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                    Active Assignments
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="issues-tab" data-bs-toggle="tab" data-bs-target="#issues" type="button" role="tab">
                    Reported Issues
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                    Assignment History
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="siteTabsContent">
            <!-- Active Assignments Tab -->
            <div class="tab-pane fade show active" id="active" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Site Name</th>
                                <th>Assigned Team</th>
                                <th>Assignment Date</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($assignments && $assignments->count() > 0)
                                @foreach($assignments as $index => $assignment)
                                    <tr>
                                        <td>{{ ($assignments->currentPage() - 1) * $assignments->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $assignment->customer->customer_name ?? 'N/A' }}</strong>
                                                @if($assignment->customer && $assignment->customer->location)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($assignment->customer->location, 30) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $assignment->team->team_name ?? 'N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $assignment->team->team_code ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $assignment->assigned_date ? $assignment->assigned_date->format('Y-m-d') : 'N/A' }}</td>
                                        <td>
                                            @php
                                                $priorityClass = match($assignment->priority) {
                                                    'low' => 'bg-secondary',
                                                    'medium' => 'bg-primary',
                                                    'high' => 'bg-warning',
                                                    'critical' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $priorityClass }}">{{ ucfirst($assignment->priority) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($assignment->status) {
                                                    'pending' => 'bg-warning',
                                                    'in_progress' => 'bg-info',
                                                    'completed' => 'bg-success',
                                                    'cancelled' => 'bg-secondary',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $assignment->status)) }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-info view-assignment" 
                                                        data-id="{{ $assignment->id }}"
                                                        data-customer-name="{{ $assignment->customer->customer_name ?? 'N/A' }}"
                                                        data-team-name="{{ $assignment->team->team_name ?? 'N/A' }}"
                                                        data-assignment-title="{{ $assignment->assignment_title ?? 'N/A' }}"
                                                        data-priority="{{ $assignment->priority }}"
                                                        data-status="{{ $assignment->status }}"
                                                        data-assigned-date="{{ $assignment->assigned_date ? $assignment->assigned_date->format('M d, Y') : 'N/A' }}"
                                                        data-start-date="{{ $assignment->start_date ? $assignment->start_date->format('M d, Y') : 'N/A' }}"
                                                        data-due-date="{{ $assignment->due_date ? $assignment->due_date->format('M d, Y') : 'N/A' }}"
                                                        data-progress="{{ $assignment->progress ?? 0 }}"
                                                        data-notes="{{ $assignment->description ?? 'No notes available.' }}"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-warning edit-assignment" 
                                                        title="Edit"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editAssignmentModal"
                                                        data-assignment-id="{{ $assignment->id }}" 
                                                        data-assignment-customer-id="{{ $assignment->customer_id }}"
                                                        data-assignment-team-id="{{ $assignment->team_id }}"
                                                        data-assignment-title="{{ $assignment->assignment_title ?? '' }}"
                                                        data-assignment-description="{{ $assignment->description ?? '' }}"
                                                        data-assignment-priority="{{ $assignment->priority ?? 'medium' }}"
                                                        data-assignment-status="{{ $assignment->status ?? 'pending' }}"
                                                        data-assignment-assigned-date="{{ $assignment->assigned_date ? $assignment->assigned_date->format('Y-m-d\TH:i') : '' }}"
                                                        data-assignment-update-url="{{ route('site-assignments.update', $assignment->id) }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('site-assignments.destroy', '') }}/{{ $assignment->id }}" method="POST" class="d-inline delete-assignment-form" 
                                                      data-assignment-name="{{ $assignment->customer->customer_name ?? 'Assignment' }}">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="confirmDelete(event, '{{ $assignment->customer->customer_name ?? 'this assignment' }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-tasks fa-3x mb-3"></i>
                                            <h5>No active assignments found</h5>
                                            <p>Start by creating your first site assignment.</p>
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignSiteModal">
                                                <i class="fas fa-plus me-1"></i> Create First Assignment
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination for Active Assignments -->
                @if(isset($assignments) && $assignments->hasPages())
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
            
            <!-- Reported Issues Tab -->
            <div class="tab-pane fade" id="issues" role="tabpanel">
                <div class="list-group">
                    @if($issues && $issues->count() > 0)
                        @foreach($issues as $issue)
                            <div class="list-group-item border-0 p-3 mb-2 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">{{ $issue->customer->customer_name ?? 'N/A' }} - {{ $issue->assignment_title ?? 'Issue' }}</h6>
                                    @php
                                        $priorityClass = match($issue->priority) {
                                            'low' => 'bg-secondary',
                                            'medium' => 'bg-primary',
                                            'high' => 'bg-warning',
                                            'critical' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $priorityClass }}">{{ ucfirst($issue->priority) }}</span>
                                </div>
                                <p class="mb-2 text-muted">{{ $issue->issue_description }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Reported: {{ $issue->issue_reported_at ? $issue->issue_reported_at->diffForHumans() : 'N/A' }}</small>
                                    <div>
                                        <form action="{{ route('site-assignments.resolve-issue', [$issue->id, $issue->id]) }}" method="POST" class="d-inline resolve-issue-form">
                                            @csrf
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1 resolve-issue-btn" 
                                                    data-id="{{ $issue->id }}">
                                                <i class="fas fa-tools me-1"></i> Resolve
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5>No reported issues</h5>
                            <p class="text-muted">All assignments are running smoothly.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Assignment History Tab -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Site</th>
                                <th>Team</th>
                                <th>Assigned Date</th>
                                <th>Completed Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($history && $history->count() > 0)
                                @foreach($history as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->customer->customer_name ?? 'N/A' }}</td>
                                        <td>{{ $item->team->team_name ?? 'N/A' }}</td>
                                        <td>{{ $item->assigned_date ? $item->assigned_date->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ $item->completed_date ? $item->completed_date->format('Y-m-d') : 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-success me-2">Completed</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-history fa-3x mb-3"></i>
                                            <h5>No completed assignments yet</h5>
                                            <p>Completed assignments will appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Assignment Modal -->
<div class="modal fade" id="assignSiteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">New Site Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('site-assignments.store') }}" method="POST" id="newAssignmentForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="siteSelect" class="form-label">Select Site (Customer)</label>
                        <select class="form-select" id="siteSelect" name="site_id" required>
                            <option value="">Select a customer site</option>
                            @if(isset($sites) && $sites->count() > 0)
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}">
                                        {{ $site->name ?? 'N/A' }}@if(!empty($site->address)) - {{ $site->address }}@endif
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>No customers available</option>
                            @endif
                        </select>
                        @if(isset($sites))
                            <small class="text-muted">{{ $sites->count() }} customer(s) available</small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="teamSelect" class="form-label">Assign Team</label>
                        <select class="form-select" id="teamSelect" name="team_id" required>
                            <option value="">Select a team</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="assignmentNotes" class="form-label">Assignment Notes</label>
                        <textarea class="form-control" id="assignmentNotes" name="description" rows="3" placeholder="Add any special instructions or notes"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="prioritySelect" class="form-label">Priority</label>
                        <select class="form-select" id="prioritySelect" name="priority" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Assign Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Assignment Modal -->
<div class="modal fade" id="viewAssignmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Assignment Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Assignment Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Customer:</th>
                                <td id="assignmentViewCustomerName"></td>
                            </tr>
                            <tr>
                                <th>Team:</th>
                                <td id="assignmentViewTeamName"></td>
                            </tr>
                            <tr>
                                <th>Title:</th>
                                <td id="assignmentViewTitle"></td>
                            </tr>
                            <tr>
                                <th>Priority:</th>
                                <td><span id="assignmentViewPriority" class="badge"></span></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><span id="assignmentViewStatus" class="badge"></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Timeline</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Assigned Date:</th>
                                <td id="assignmentViewAssignedDate"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Notes</h6>
                        <div class="card">
                            <div class="card-body">
                                <p id="assignmentViewNotes" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Resolve Issue Modal -->
<div class="modal fade" id="resolveIssueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Resolve Issue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="resolveIssueForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="resolutionType" class="form-label">Resolution Type</label>
                        <select class="form-select" id="resolutionType" name="resolution_type" required>
                            <option value="">Select Resolution Type</option>
                            <option value="fixed">Fixed</option>
                            <option value="workaround">Workaround</option>
                            <option value="cannot_reproduce">Cannot Reproduce</option>
                            <option value="duplicate">Duplicate</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="resolutionDetails" class="form-label">Resolution Details</label>
                        <textarea class="form-control" id="resolutionDetails" name="resolution_details" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-1"></i> Mark as Resolved
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Comment Modal -->
<div class="modal fade" id="addCommentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Comment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCommentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="commentText" class="form-label">Your Comment</label>
                        <textarea class="form-control" id="commentText" name="comment" rows="4" required></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="internalNote" name="internal_note">
                        <label class="form-check-label" for="internalNote">
                            Mark as internal note (visible to staff only)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-comment me-1"></i> Post Comment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View History Modal -->
<div class="modal fade" id="viewHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Assignment History</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Details</th>
                                <th>Changed By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2023-08-14 15:30</td>
                                <td>Completed</td>
                                <td>Assignment marked as completed by Team Beta</td>
                                <td>John Smith</td>
                            </tr>
                            <tr>
                                <td>2023-08-10 09:15</td>
                                <td>Updated</td>
                                <td>Added 2 more team members to the assignment</td>
                                <td>Sarah Johnson</td>
                            </tr>
                            <tr>
                                <td>2023-08-01 10:00</td>
                                <td>Assigned</td>
                                <td>Initial assignment to Team Beta</td>
                                <td>System</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> Export to CSV
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Assignment Modal -->
<div class="modal fade" id="editAssignmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAssignmentForm" method="POST" action="">
                <input type="hidden" name="_method" value="PUT">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editAssignmentId" name="id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editSiteSelect" class="form-label">Site <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="editSiteSelect" name="customer_id" required>
                                <option value="">Select Site</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editTeamSelect" class="form-label">Team <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="editTeamSelect" name="team_id" required>
                                <option value="">Select Team</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="editAssignmentNotes" class="form-label">Notes</label>
                            <textarea class="form-control" id="editAssignmentNotes" name="description" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="editPrioritySelect" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" id="editPrioritySelect" name="priority" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editStatusSelect" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="editStatusSelect" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editAssignedDate" class="form-label">Assignment Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="editAssignedDate" name="assigned_date" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <span class="d-none spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Update Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .loading {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }
    .loading:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 40px;
        height: 40px;
        margin: -20px 0 0 -20px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 9999;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@push('javascript')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const refreshButton = document.getElementById('refreshAssignmentsBtn');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            const originalHtml = refreshButton.innerHTML;
            refreshButton.disabled = true;
            refreshButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Refreshing';
            window.location.reload();
            setTimeout(() => {
                refreshButton.disabled = false;
                refreshButton.innerHTML = originalHtml;
            }, 2000);
        });
    }

    // Resolve Issue Button
    $(document).on('click', '.resolve-issue-btn', function() {
        const issueId = $(this).data('id');
        
        Swal.fire({
            title: 'Resolve Issue',
            html: `
                <textarea id="resolution_notes" class="swal2-textarea" placeholder="Enter resolution notes..." rows="4" style="width: 100%;"></textarea>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Mark as Resolved',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const notes = document.getElementById('resolution_notes').value;
                if (!notes) {
                    Swal.showValidationMessage('Please enter resolution notes');
                    return false;
                }
                return { notes: notes };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit form
                const form = $('<form>', {
                    'method': 'POST',
                    'action': `/company/site-assignments/${issueId}/resolve-issue/${issueId}`
                });
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'resolution_notes',
                    'value': result.value.notes
                }));
                
                $('body').append(form);
                form.submit();
            }
        });
    });

        // Handle edit assignment button click
        $(document).on('click', '.edit-assignment', function(e) {
            e.preventDefault();

            const button = $(this);

            try {
                const assignmentId = button.data('assignment-id');
                if (!assignmentId) {
                    console.error('Missing assignment ID');
                    toastr.error('Unable to load assignment. Please refresh and try again.');
                    return;
                }

                const form = $('#editAssignmentForm');
                const buttonUpdateUrl = button.data('assignment-update-url');
                const updateUrlTemplate = '{{ route('site-assignments.update', ['assignment' => '__ASSIGNMENT_ID__']) }}';
                const formAction = buttonUpdateUrl ? buttonUpdateUrl : updateUrlTemplate.replace('__ASSIGNMENT_ID__', assignmentId);
                form.attr('action', formAction);

                const assignmentData = {
                    id: assignmentId,
                    customerId: button.data('assignment-customer-id'),
                    teamId: button.data('assignment-team-id'),
                    description: button.data('assignment-description') || '',
                    title: button.data('assignment-title') || '',
                    priority: button.data('assignment-priority') || 'medium',
                    status: button.data('assignment-status') || 'pending',
                    assignedDate: button.data('assignment-assigned-date') || ''
                };

                $('#editAssignmentId').val(assignmentData.id);
                $('#editSiteSelect').val(String(assignmentData.customerId || ''));
                $('#editTeamSelect').val(String(assignmentData.teamId || ''));
                $('#editSiteSelect').trigger('change.select2');
                $('#editTeamSelect').trigger('change.select2');
                $('#editAssignmentNotes').val(assignmentData.description || assignmentData.title);
                $('#editPrioritySelect').val(assignmentData.priority);
                $('#editStatusSelect').val(assignmentData.status);
                $('#editAssignedDate').val(assignmentData.assignedDate);

                const modalInstance = new bootstrap.Modal(document.getElementById('editAssignmentModal'));
                modalInstance.show();
            } catch (error) {
                console.error('Error in edit assignment:', error);
                toastr.error('An error occurred while loading the assignment for editing.');
            }
        });
        
        // Handle form submission
        $('#editAssignmentForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            const originalBtnText = submitBtn.html();
            
            // Show loading state
            submitBtn.prop('disabled', true);
            submitBtn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Updating...');
            
            // Submit the form via AJAX
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Assignment updated successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editAssignmentModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Reload the page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    // Show error message
                    let errorMessage = 'An error occurred while updating the assignment.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Handle validation errors
                        const errors = [];
                        for (const field in xhr.responseJSON.errors) {
                            errors.push(xhr.responseJSON.errors[field][0]);
                        }
                        errorMessage = errors.join('<br>');
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    // Reset button state
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalBtnText);
                }
            });
        });
        
        const viewAssignmentModalEl = document.getElementById('viewAssignmentModal');
        const viewAssignmentModal = viewAssignmentModalEl ? new bootstrap.Modal(viewAssignmentModalEl) : null;

        document.addEventListener('click', function(event) {
            const button = event.target.closest('.view-assignment');
            if (!button) {
                return;
            }

            event.preventDefault();

            const assignmentData = {
                customerName: button.dataset.customerName || 'N/A',
                teamName: button.dataset.teamName || 'N/A',
                assignmentTitle: button.dataset.assignmentTitle || 'N/A',
                priority: button.dataset.priority || 'medium',
                status: button.dataset.status || 'pending',
                assignedDate: button.dataset.assignedDate || 'N/A',
                notes: button.dataset.notes || 'No notes available.'
            };

            setTextContent('assignmentViewCustomerName', assignmentData.customerName);
            setTextContent('assignmentViewTeamName', assignmentData.teamName);
            setTextContent('assignmentViewTitle', assignmentData.assignmentTitle);
            setTextContent('assignmentViewAssignedDate', assignmentData.assignedDate);

            const priorityBadge = document.getElementById('assignmentViewPriority');
            if (priorityBadge) {
                priorityBadge.className = 'badge ' + getPriorityBadgeClass(assignmentData.priority);
                priorityBadge.textContent = formatPriorityText(assignmentData.priority);
            }

            const statusBadge = document.getElementById('assignmentViewStatus');
            if (statusBadge) {
                statusBadge.className = 'badge ' + getStatusBadgeClass(assignmentData.status);
                statusBadge.textContent = formatStatusText(assignmentData.status);
            }


            const notesElement = document.getElementById('assignmentViewNotes');
            if (notesElement) {
                const notesText = assignmentData.notes || '';
                notesElement.innerHTML = notesText.replace(/\n/g, '<br>');
            }

            if (viewAssignmentModal) {
                viewAssignmentModal.show();
            }
        });

        function setTextContent(elementId, value) {
            const el = document.getElementById(elementId);
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
            return status.replace('_', ' ').replace(/\b\w/g, char => char.toUpperCase());
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

        // Function to confirm delete
        window.confirmDelete = function(event, assignmentName) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete ${assignmentName}? This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    });
    </script>
@endpush
