@php
/**
 * Team Registration Tab Component
 * 
 * Manages team member registration and details
 */
@endphp

<div class="row">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Register New Team</h5>
            </div>
            <div class="card-body">
                <form id="teamRegistrationForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="teamName" class="form-label">Team Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="teamName" name="team_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="teamLeader" class="form-label">Team Leader <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="teamLeader" name="team_leader" required>
                                <option value="">Select Team Leader</option>
                                <!-- Team leaders will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="teamMembers" class="form-label">Team Members <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="teamMembers" name="team_members[]" multiple required>
                                <!-- Team members will be loaded dynamically -->
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple members</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assigned Region</label>
                            <select class="form-select" required>
                                <option value="">Select Region</option>
                                <option>Greater Accra</option>
                                <option>Ashanti</option>
                                <option>Western</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assigned a Project</label>
                            <select class="form-select">
                                <option value="">Choose a Project</option>
                                <option>HC </option>
                                <option>HR </option>
                                <option>HR & HC </option>
                            </select>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Register Team
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Registered Teams</h5>
                <div class="input-group" style="width: 200px;">
                    <input type="text" class="form-control form-control-sm" placeholder="Search teams...">
                    <button class="btn btn-sm btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Team Name</th>
                                <th>Leader</th>
                                <th>Members</th>
                                <th>Location</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Team Alpha</td>
                                <td>Henry Martey</td>
                                <td>3</td>
                                <td>Ashanti Region</td>
                                <td>HR</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary view-team" data-team-id="1" data-bs-toggle="modal" data-bs-target="#viewTeamModal">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary edit-team" data-team-id="1" data-bs-toggle="modal" data-bs-target="#editTeamModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-team" data-bs-toggle="modal" data-bs-target="#deleteTeamModal" data-team-id="1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- More team rows would go here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fetch employees from HR database
function fetchEmployees() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/company/hr/employees/all',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.data) {
                    resolve(response.data);
                } else {
                    reject(new Error('No employee data found'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching employees:', error);
                reject(error);
            }
        });
    });
}

// Initialize Select2 and load employees
$(document).ready(function() {
    // Initialize modals
    const viewTeamModal = new bootstrap.Modal(document.getElementById('viewTeamModal'));
    const editTeamModal = new bootstrap.Modal(document.getElementById('editTeamModal'));
    const deleteTeamModal = new bootstrap.Modal(document.getElementById('deleteTeamModal'));
    
    // Current team being viewed/edited
    let currentTeamId = null;
    // Initialize Select2 for better dropdowns
    $('.select2, .select2-edit').select2({
        placeholder: 'Select an option',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#teamRegistrationForm')
    });

    // Load employees into dropdowns
    const loadingHtml = '<option value="" disabled>Loading employees...</option>';
    $('#teamLeader').html(loadingHtml);
    $('#teamMembers').html(loadingHtml);

    fetchEmployees()
        .then(employees => {
            // Populate team leader dropdown (single select)
            let leaderOptions = '<option value="">Select Team Leader</option>';
            let memberOptions = '';
            
            employees.forEach(employee => {
                const fullName = `${employee.personal_info?.first_name || ''} ${employee.personal_info?.last_name || ''}`.trim();
                const employeeId = employee.id;
                
                if (fullName) {
                    leaderOptions += `<option value="${employeeId}">${fullName} (${employee.staff_id || 'N/A'})</option>`;
                    memberOptions += `<option value="${employeeId}">${fullName} (${employee.staff_id || 'N/A'})</option>`;
                }
            });
            
            $('#teamLeader').html(leaderOptions);
            $('#teamMembers').html(memberOptions);
        })
        .catch(error => {
            console.error('Error:', error);
            $('#teamLeader').html('<option value="" disabled>Error loading employees</option>');
            $('#teamMembers').html('<option value="" disabled>Error loading employees</option>');
            
            // Show error message
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load employee data. Please try again later.',
                showConfirmButton: true
            });
        });
    
    // Form submission handler
    $('#teamRegistrationForm').on('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        
        // Validate form
        const teamName = formData.get('team_name');
        const teamLeader = formData.get('team_leader');
        const teamMembers = formData.getAll('team_members[]');
        const region = formData.get('region');
        const project = formData.get('project');
        
        if (!teamName || !teamLeader || teamMembers.length === 0 || !region) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all required fields',
                showConfirmButton: true
            });
            return;
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
        
        // Prepare data for submission
        const data = {
            team_name: teamName,
            team_leader: teamLeader,
            team_members: teamMembers,
            region: region,
            project: project,
            _token: '{{ csrf_token() }}'
        };
        
        // Simulate API call (replace with actual API endpoint when available)
        console.log('Submitting team data:', data);
        
        // TODO: Replace with actual API call
        // Example:
        // $.post('/api/teams', data)
        //     .done(function(response) {
        //         // Handle success
        //         showSuccess('Team registered successfully');
        //         resetForm();
        //         // Refresh teams list
        //         loadTeams();
        //     })
        //     .fail(function(error) {
        //         // Handle error
        //         showError('Failed to register team. Please try again.');
        //     })
        //     .always(function() {
        //         // Re-enable button
        //         submitBtn.prop('disabled', false).html(originalBtnText);
        //     });
        
        // For now, just simulate success after a delay
        setTimeout(() => {
            // Reset button state
            submitBtn.prop('disabled', false).html(originalBtnText);
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Team registered successfully!',
                showConfirmButton: false,
                timer: 2000
            });
            
            // Reset form
            this.reset();
            $('.select2').val(null).trigger('change');
            
            // TODO: Refresh the teams table when implemented
            // loadTeams();
            
        }, 1500);
    });
    
    // View Team Button Click
    $(document).on('click', '.view-team', function() {
        const teamId = $(this).data('team-id');
        currentTeamId = teamId;
        
        // TODO: Replace with actual API call to get team details
        console.log('Viewing team:', teamId);
        
        // Sample data - replace with actual API call
        const teamData = {
            id: teamId,
            name: 'Team Alpha',
            leader: {
                id: 1,
                name: 'Henry Martey',
                staff_id: 'EMP001'
            },
            members: [
                { id: 2, name: 'John Doe', staff_id: 'EMP002', is_leader: false },
                { id: 3, name: 'Jane Smith', staff_id: 'EMP003', is_leader: false },
                { id: 4, name: 'Mike Johnson', staff_id: 'EMP004', is_leader: false }
            ],
            region: 'Ashanti',
            project: 'HR',
            status: 'active'
        };
        
        // Populate view modal
        $('#viewTeamName').text(teamData.name);
        $('#viewTeamRegion').text(teamData.region);
        $('#viewTeamProject').text(teamData.project);
        
        // Set status badge
        const statusBadge = teamData.status === 'active' 
            ? '<span class="badge bg-success">Active</span>' 
            : '<span class="badge bg-secondary">Inactive</span>';
        $('#viewTeamStatus').replaceWith(statusBadge);
        
        // Populate team members table
        const membersTable = $('#viewTeamMembers tbody');
        membersTable.empty();
        
        // Add team leader first
        membersTable.append(`
            <tr class="table-primary">
                <td>${teamData.leader.name} (Leader)</td>
                <td>Team Leader</td>
                <td>${teamData.leader.staff_id}</td>
            </tr>
        `);
        
        // Add other team members
        teamData.members.forEach(member => {
            membersTable.append(`
                <tr>
                    <td>${member.name}</td>
                    <td>Team Member</td>
                    <td>${member.staff_id}</td>
                </tr>
            `);
        });
    });
    
    // Edit Team Button Click (from view modal)
    $(document).on('click', '.edit-team-from-view', function() {
        viewTeamModal.hide();
        editTeamModal.show();
        
        // Populate edit form with current team data
        // This would be replaced with actual API call
        const teamData = {
            id: currentTeamId,
            name: 'Team Alpha',
            leader_id: 1,
            members: [2, 3, 4],
            region: 'Ashanti',
            project: 'HR'
        };
        
        $('#editTeamId').val(teamData.id);
        $('#editTeamName').val(teamData.name);
        $('#editTeamRegion').val(teamData.region);
        $('#editTeamProject').val(teamData.project);
        
        // Set team leader
        if (teamData.leader_id) {
            $('#editTeamLeader').val(teamData.leader_id).trigger('change');
        }
        
        // Set team members
        if (teamData.members && teamData.members.length > 0) {
            $('#editTeamMembers').val(teamData.members).trigger('change');
        }
    });
    
    // Edit Team Form Submission
    $('#editTeamForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        console.log('Updating team:', formData);
        
        // TODO: Replace with actual API call
        // $.post('/api/teams/update', formData)
        //     .done(function(response) {
        //         showSuccess('Team updated successfully');
        //         editTeamModal.hide();
        //         // Refresh teams list
        //         loadTeams();
        //     })
        //     .fail(function(error) {
        //         showError('Failed to update team. Please try again.');
        //     });
        
        // For now, just show success message
        setTimeout(() => {
            showSuccess('Team updated successfully');
            editTeamModal.hide();
        }, 1000);
    });
    
    // Delete Team Button Click
    $(document).on('click', '.delete-team', function() {
        const button = $(this);
        const teamId = button.data('team-id');
        const teamName = button.closest('tr').find('td:first').text();
        
        currentTeamId = teamId;
        $('#deleteTeamName').text(teamName);
        
        // Store the button reference for the confirm click handler
        const deleteTeamModalElement = document.getElementById('deleteTeamModal');
        const deleteModal = bootstrap.Modal.getOrCreateInstance(deleteTeamModalElement);
        
        // Reset any previous click handlers
        $('#confirmDeleteTeam').off('click');
        
        // Set up new click handler
        $('#confirmDeleteTeam').on('click', function() {
            // TODO: Replace with actual API call
            console.log('Deleting team:', currentTeamId);
            
            // Show loading state
            const deleteBtn = $(this);
            const originalText = deleteBtn.html();
            deleteBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...');
            
            // Simulate API call
            setTimeout(() => {
                // Show success message
                showSuccess('Team deleted successfully');
                
                // Close the modal
                deleteModal.hide();
                
                // Remove the row from the table
                button.closest('tr').fadeOut(400, function() {
                    $(this).remove();
                });
                
                // Reset the button
                deleteBtn.prop('disabled', false).html(originalText);
            }, 1000);
        });
    });
    
});

// Helper function to show success messages
function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        showConfirmButton: false,
        timer: 2000
    });
}

// Helper function to show error messages
function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        showConfirmButton: true
    });
}

// Helper function to reset the form
function resetForm() {
    $('#teamRegistrationForm')[0].reset();
    $('.select2').val(null).trigger('change');
}

// TODO: Implement this function to load teams into the table
function loadTeams() {
    // Implementation to load teams from the server
    // and update the teams table
}
</script>
@endpush

<!-- View Team Modal -->
<div class="modal fade" id="viewTeamModal" tabindex="-1" aria-labelledby="viewTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewTeamModalLabel">Team Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Team Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 40%;">Team Name:</th>
                                <td id="viewTeamName">-</td>
                            </tr>
                            <tr>
                                <th>Region:</th>
                                <td id="viewTeamRegion">-</td>
                            </tr>
                            <tr>
                                <th>Project:</th>
                                <td id="viewTeamProject">-</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><span class="badge bg-success" id="viewTeamStatus">Active</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Team Members</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="viewTeamMembers">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Staff ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Team members will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary edit-team-from-view">
                    <i class="fas fa-edit me-1"></i> Edit Team
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Team Modal -->
<div class="modal fade" id="editTeamModal" tabindex="-1" aria-labelledby="editTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editTeamModalLabel">Edit Team</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTeamForm">
                <div class="modal-body">
                    <input type="hidden" id="editTeamId" name="team_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editTeamName" class="form-label">Team Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editTeamName" name="team_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="editTeamLeader" class="form-label">Team Leader <span class="text-danger">*</span></label>
                                <select class="form-select select2-edit" id="editTeamLeader" name="team_leader" required>
                                    <option value="">Select Team Leader</option>
                                    <!-- Team leaders will be loaded dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editTeamRegion" class="form-label">Region <span class="text-danger">*</span></label>
                                <select class="form-select" id="editTeamRegion" name="region" required>
                                    <option value="">Select Region</option>
                                    <option>Greater Accra</option>
                                    <option>Ashanti</option>
                                    <option>Western</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editTeamProject" class="form-label">Project</label>
                                <select class="form-select" id="editTeamProject" name="project">
                                    <option value="">Choose a Project</option>
                                    <option>HC</option>
                                    <option>HR</option>
                                    <option>HR & HC</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="editTeamMembers" class="form-label">Team Members <span class="text-danger">*</span></label>
                            <select class="form-select select2-edit" id="editTeamMembers" name="team_members[]" multiple required>
                                <!-- Team members will be loaded dynamically -->
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple members</small>
                        </div>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTeamModal" tabindex="-1" aria-labelledby="deleteTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteTeamModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this team? This action cannot be undone.</p>
                <p class="mb-0"><strong>Team:</strong> <span id="deleteTeamName"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteTeam">
                    <i class="fas fa-trash-alt me-1"></i> Delete Team
                </button>
            </div>
        </div>
    </div>
</div>
