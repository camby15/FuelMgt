<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Teams Management</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeamModal">
            <i class="ri-add-line me-1"></i> Add New Team
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-nowrap mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Team Name</th>
                        <th>Team Lead</th>
                        <th>Members</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Sample data - replace with actual data from your controller
                        $teams = [
                            [
                                'name' => 'HR Team A',
                                'lead' => 'Patrick Boamah',
                                'members' => 4,
                                'location' => 'Accra',
                                'status' => 'active',
                                'member_names' => ['Mirian Dzifa', 'Nana Ama', 'Camby Omori']
                            ],
                            [
                                'name' => 'HC Team B',
                                'lead' => 'Derek Asare',
                                'members' => 3,
                                'location' => 'Kumasi',
                                'status' => 'active',
                                'member_names' => ['Mirian Dzifa', 'Nana Ama']
                            ],
                            [
                                'name' => 'Support Team',
                                'lead' => 'Kofi Smith',
                                'members' => 4,
                                'location' => 'Tema',
                                'status' => 'active',
                                'member_names' => ['Camby Omori', 'Nana Ama']
                            ]
                        ];
                    @endphp

                    @php
                        $colors = ['primary', 'success', 'info', 'warning', 'danger'];
                        $gradients = [
                            'linear-gradient(45deg, #3b82f6, #8b5cf6)',
                            'linear-gradient(45deg, #10b981, #06b6d4)',
                            'linear-gradient(45deg, #6366f1, #8b5cf6)',
                            'linear-gradient(45deg, #f59e0b, #f97316)',
                            'linear-gradient(45deg, #ef4444, #f97316)'
                        ];
                    @endphp
                    @foreach($teams as $index => $team)
                    @php
                        $colorIndex = $index % 5;
                        $gradient = $gradients[$colorIndex];
                        $color = $colors[$colorIndex];
                    @endphp
                    <tr class="align-middle hover-row">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold">{{ $team['name'] }}</h6>
                                    <small class="text-muted">ID: {{ 1000 + $index }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle d-flex align-items-center justify-content-center" style="background: {{ $gradient }}; width: 32px; height: 32px;">
                                            <span class="text-white fs-12">{{ strtoupper(substr($team['lead'], 0, 1)) }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-medium">{{ $team['lead'] }}</h6>
                                    <small class="text-muted">Team Lead</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="avatar-group">
                                @php
                                    $memberGradients = [
                                        'linear-gradient(45deg, #3b82f6, #8b5cf6)',
                                        'linear-gradient(45deg, #10b981, #06b6d4)',
                                        'linear-gradient(45deg, #6366f1, #8b5cf6)',
                                        'linear-gradient(45deg, #f59e0b, #f97316)',
                                        'linear-gradient(45deg, #ef4444, #f97316)'
                                    ];
                                @endphp
                                @php
                                    $allMembers = array_merge([$team['lead']], $team['member_names'] ?? []);
                                @endphp
                                @foreach(array_slice($allMembers, 0, 5) as $index => $member)
                                @php
                                    $initials = implode('', array_map(function($n) { return $n[0]; }, explode(' ', $member)));
                                @endphp
                                <div class="avatar-group-item" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $member }}">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle d-flex align-items-center justify-content-center" style="background: {{ $memberGradients[$index % 5] }}; width: 32px; height: 32px;">
                                            <span class="text-white fs-12">{{ $initials }}</span>
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                                @if($team['members'] > 5)
                                <div class="avatar-group-item" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $team['members'] - 5 }} more members">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle d-flex align-items-center justify-content-center" style="background: #e2e8f0; width: 32px; height: 32px;">
                                            <span class="text-muted fs-12">+{{ $team['members'] - 5 }}</span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="mt-1">
                                <small class="text-muted">{{ $team['members'] }} {{ $team['members'] === 1 ? 'member' : 'members' }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-2">
                                    <i class="ri-map-pin-line text-muted"></i>
                                </div>
                                <span class="fw-medium">{{ $team['location'] }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="position-relative me-2">
                                    <span class="status-indicator status-{{ $team['status'] }}"></span>
                                </span>
                                <span class="status-text text-{{ $team['status'] == 'active' ? 'success' : 'danger' }} fw-medium">
                                    {{ ucfirst($team['status']) }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-sm btn-outline-primary view-team" 
                                        title="View" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewTeamModal"
                                        data-id="{{ $index + 1 }}"
                                        data-name="{{ $team['name'] }}"
                                        data-lead="{{ $team['lead'] }}"
                                        data-members="{{ $team['members'] }}"
                                        data-location="{{ $team['location'] }}"
                                        data-status="{{ $team['status'] }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary edit-team" 
                                        title="Edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editTeamModal"
                                        data-id="{{ $index + 1 }}"
                                        data-name="{{ $team['name'] }}"
                                        data-lead="{{ $team['lead'] }}"
                                        data-members="{{ $team['members'] }}"
                                        data-location="{{ $team['location'] }}"
                                        data-status="{{ $team['status'] }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-team" 
                                        title="Delete" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteTeamModal"
                                        data-id="{{ $index + 1 }}"
                                        data-name="{{ $team['name'] }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Team Modal -->
<div class="modal fade" id="addTeamModal" tabindex="-1" aria-labelledby="addTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeamModalLabel">Add New Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="teamName" class="form-label">Team Name</label>
                        <input type="text" class="form-control" id="teamName" required>
                    </div>
                    <div class="mb-3">
                        <label for="teamLead" class="form-label">Team Lead</label>
                        <select class="form-select" id="teamLead" required>
                            <option value="">Select Team Lead</option>
                            <option value="1">John Doe</option>
                            <option value="2">Jane Smith</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Team Members</label>
                        <select class="form-select" multiple>
                            <option>Select Members</option>
                            <option value="1">Alex Johnson</option>
                            <option value="2">Sarah Wilson</option>
                            <option value="3">Michael Brown</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="activeStatus" value="active" checked>
                            <label class="form-check-label" for="activeStatus">
                                Active
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="inactiveStatus" value="inactive">
                            <label class="form-check-label" for="inactiveStatus">
                                Inactive
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Team</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Team Modal -->
<div class="modal fade" id="viewTeamModal" tabindex="-1" aria-labelledby="viewTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTeamModalLabel">Team Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <div class="avatar-xxl mb-3">
                            <div class="avatar-title rounded-circle d-flex align-items-center justify-content-center" style="background: linear-gradient(45deg, #3b82f6, #8b5cf6); width: 100px; height: 100px;">
                                <span class="text-white fs-1" id="viewTeamInitials">T</span>
                            </div>
                        </div>
                        <h5 id="viewTeamName">Team Name</h5>
                        <span class="badge bg-success" id="viewTeamStatus">Active</span>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" style="width: 30%;">Team Lead:</th>
                                        <td class="text-end text-muted" id="viewTeamLead">John Doe</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Location:</th>
                                        <td class="text-end text-muted" id="viewTeamLocation">Accra, Ghana</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Members:</th>
                                        <td class="text-end text-muted" id="viewTeamMemberCount">5 members</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Created:</th>
                                        <td class="text-end text-muted">Jan 15, 2023</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Team Members</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3" id="viewTeamMembers">
                            <!-- Team members will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Edit Team</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Team Modal -->
<div class="modal fade" id="editTeamModal" tabindex="-1" aria-labelledby="editTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTeamModalLabel">Edit Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTeamForm">
                <input type="hidden" id="editTeamId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editTeamName" class="form-label">Team Name</label>
                            <input type="text" class="form-control" id="editTeamName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editTeamLead" class="form-label">Team Lead</label>
                            <select class="form-select" id="editTeamLead" required>
                                <option value="">Select Team Lead</option>
                                <option value="1">Patrick Boamah</option>
                                <option value="2">Derek Asare</option>
                                <option value="3">Kofi Smith</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editTeamMembers" class="form-label">Team Members</label>
                        <select class="form-select" id="editTeamMembers" multiple>
                            <option value="1">Mirian Dzifa</option>
                            <option value="2">Nana Ama</option>
                            <option value="3">Camby Omori</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editTeamLocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="editTeamLocation" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="editTeamStatus" id="editActiveStatus" value="active" checked>
                                    <label class="form-check-label" for="editActiveStatus">Active</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="editTeamStatus" id="editInactiveStatus" value="inactive">
                                    <label class="form-check-label" for="editInactiveStatus">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Team Confirmation Modal -->
<div class="modal fade" id="deleteTeamModal" tabindex="-1" aria-labelledby="deleteTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteTeamModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteTeamName">[Team Name]</strong>? This action cannot be undone.</p>
                <p class="text-muted mb-0">All team data and associated information will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteTeam">Delete Team</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Team Modal
    const viewTeamModal = document.getElementById('viewTeamModal');
    if (viewTeamModal) {
        viewTeamModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const teamName = button.getAttribute('data-name');
            const teamLead = button.getAttribute('data-lead');
            const members = button.getAttribute('data-members').split(',');
            const location = button.getAttribute('data-location');
            const status = button.getAttribute('data-status');
            
            // Update modal content
            document.getElementById('viewTeamName').textContent = teamName;
            document.getElementById('viewTeamInitials').textContent = teamName.charAt(0).toUpperCase();
            document.getElementById('viewTeamLead').textContent = teamLead;
            document.getElementById('viewTeamLocation').textContent = location;
            document.getElementById('viewTeamStatus').textContent = status.charAt(0).toUpperCase() + status.slice(1);
            document.getElementById('viewTeamStatus').className = `badge bg-${status === 'active' ? 'success' : 'danger'}`;
            
            // Update member count and list
            const memberCount = members.length;
            document.getElementById('viewTeamMemberCount').textContent = `${memberCount} ${memberCount === 1 ? 'member' : 'members'}`;
            
            const membersContainer = document.getElementById('viewTeamMembers');
            membersContainer.innerHTML = '';
            
            members.forEach(member => {
                if (member.trim()) {
                    const initials = member.trim().split(' ').map(n => n[0]).join('').toUpperCase();
                    membersContainer.innerHTML += `
                        <div class="text-center">
                            <div class="avatar-md mb-2">
                                <div class="avatar-title rounded-circle bg-soft-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <span class="text-primary fw-medium">${initials}</span>
                                </div>
                            </div>
                            <h6 class="mb-0">${member.trim()}</h6>
                            <small class="text-muted">${member.trim() === teamLead ? 'Team Lead' : 'Member'}</small>
                        </div>
                    `;
                }
            });
        });
    }
    
    // Edit Team Modal
    const editTeamModal = document.getElementById('editTeamModal');
    if (editTeamModal) {
        editTeamModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const teamId = button.getAttribute('data-id');
            const teamName = button.getAttribute('data-name');
            const teamLead = button.getAttribute('data-lead');
            const members = button.getAttribute('data-members').split(',');
            const location = button.getAttribute('data-location');
            const status = button.getAttribute('data-status');
            
            // Set form values
            document.getElementById('editTeamId').value = teamId;
            document.getElementById('editTeamName').value = teamName;
            document.getElementById('editTeamLocation').value = location;
            
            // Set team lead
            const teamLeadSelect = document.getElementById('editTeamLead');
            for (let i = 0; i < teamLeadSelect.options.length; i++) {
                if (teamLeadSelect.options[i].text === teamLead) {
                    teamLeadSelect.selectedIndex = i;
                    break;
                }
            }
            
            // Set status
            document.getElementById(`edit${status.charAt(0).toUpperCase() + status.slice(1)}Status`).checked = true;
        });
    }
    
    // Delete Team Modal
    const deleteTeamModal = document.getElementById('deleteTeamModal');
    if (deleteTeamModal) {
        deleteTeamModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const teamId = button.getAttribute('data-id');
            const teamName = button.getAttribute('data-name');
            
            document.getElementById('deleteTeamName').textContent = teamName;
            document.getElementById('confirmDeleteTeam').setAttribute('data-id', teamId);
        });
    }
    
    // Handle form submissions
    const editTeamForm = document.getElementById('editTeamForm');
    if (editTeamForm) {
        editTeamForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Add your form submission logic here
            const modal = bootstrap.Modal.getInstance(editTeamModal);
            modal.hide();
            // Show success message
            alert('Team updated successfully!');
        });
    }
    
    // Handle delete confirmation
    const confirmDeleteBtn = document.getElementById('confirmDeleteTeam');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            const teamId = this.getAttribute('data-id');
            // Add your delete logic here
            const modal = bootstrap.Modal.getInstance(deleteTeamModal);
            modal.hide();
            // Show success message
            alert('Team deleted successfully!');
        });
    }
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

@push('styles')
<style>
    /* Modern Table Styles */
    .table {
        --bs-table-hover-bg: rgba(241, 245, 249, 0.5);
    }
    
    .hover-row {
        transition: all 0.2s ease-in-out;
    }
    
    .hover-row:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    
    /* Avatar Group Styles */
    .avatar-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }
    
    .avatar-group-item {
        transition: all 0.2s ease-in-out;
    }
    
    .avatar-group-item:hover {
        transform: translateY(-2px) scale(1.1);
        z-index: 2;
    }
    
    /* Status Indicators */
    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        position: relative;
    }
    
    .status-indicator.status-active {
        background-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        animation: pulse 2s infinite;
    }
    
    .status-indicator.status-inactive {
        background-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
    }
    
    .status-text {
        font-size: 0.8125rem;
        letter-spacing: 0.3px;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        line-height: 1;
    }
    
    .status-active {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .status-inactive {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
    
    .status-active .status-dot {
        background-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
    
    .status-inactive .status-dot {
        background-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
    }
    
    /* Button Styles */
    .btn-group-sm > .btn, .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    
    .btn-outline-primary {
        color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .btn-outline-primary:hover {
        color: #fff;
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .btn-outline-danger {
        color: #ef4444;
        border-color: #ef4444;
    }
    
    .btn-outline-danger:hover {
        color: #fff;
        background-color: #ef4444;
        border-color: #ef4444;
    }
    
    .btn-group .btn + .btn,
    .btn-group .btn + .btn-group,
    .btn-group .btn-group + .btn,
    .btn-group .btn-group + .btn-group {
        margin-left: -1px;
    }
    
    /* Table Cell Padding */
    .table > :not(caption) > * > * {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
    }
    
    /* Avatar Title */
    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2
        if (typeof $().select2 === 'function') {
            $('select[multiple]').select2({
                placeholder: "Select members",
                allowClear: true,
                width: '100%'
            });
        }

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add hover effect to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.style.transition = 'all 0.2s ease';
            row.addEventListener('mouseenter', () => {
                row.style.backgroundColor = 'rgba(0, 0, 0, 0.02)';
                row.style.transform = 'translateX(4px)';
            });
            row.addEventListener('mouseleave', () => {
                row.style.backgroundColor = '';
                row.style.transform = '';
            });
        });
    });
</script>
@endpush