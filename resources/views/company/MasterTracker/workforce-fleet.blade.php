@extends('layouts.vertical', ['page_title' => 'Workforce and Fleet', 'mode' => session('theme_mode', 'light')])

@section('css')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<!-- DataTables CDN -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<!-- Select2 for multi-select -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .workforce-container {
        background: #f5f7ff;
        min-height: 100%;
        padding: 1.5rem;
        border-radius: 12px;
    }

    /* Tabs Styling */
    .workforce-tabs {
        background: #fff;
        border-radius: 10px 10px 0 0;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border-bottom: 1px solid #e3e6f0;
    }

    .workforce-tabs .nav-link {
        color: #5a6c7d;
        font-weight: 600;
        padding: 20px 25px;
        border: none;
        border-radius: 0;
        transition: all 0.3s ease;
        position: relative;
        margin: 0;
    }

    .workforce-tabs .nav-link:hover {
        color: #3b7ddd;
        background-color: rgba(59, 125, 221, 0.1);
    }

    .workforce-tabs .nav-link.active {
        color: #3b7ddd;
        background-color: #fff;
        border-bottom: 3px solid #3b7ddd;
    }

    .workforce-tabs .nav-link i {
        margin-right: 8px;
        font-size: 16px;
    }

    /* Tab Content */
    .tab-content {
        background: #fff;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        padding: 25px;
        min-height: 600px;
    }

    /* Dashboard Cards */
    .dashboard-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-left: 4px solid #3b7ddd;
        position: relative;
        overflow: hidden;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .card-content {
        position: relative;
        z-index: 2;
    }

    .card-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #fff;
        margin-bottom: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .dashboard-card:hover .card-icon {
        transform: scale(1.1);
    }

    .card-title {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 8px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-value {
        font-size: 28px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .card-change {
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        color: #6c757d;
    }

    /* Card specific colors */
    .card-members { border-left-color: #28a745; }
    .card-drivers { border-left-color: #ffc107; }
    .card-vehicles { border-left-color: #17a2b8; }
    .card-total { border-left-color: #007bff; }

    /* Action buttons */
    .action-btn {
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 0 2px;
    }
    
    .action-btn:hover {
        opacity: 0.9 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }
    
    .action-btn i {
        font-size: 14px;
    }

    /* Status badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active { background-color: #d4edda; color: #155724; }
    .status-inactive { background-color: #f8d7da; color: #721c24; }
    .status-on-leave { background-color: #fff3cd; color: #856404; }
    .status-available { background-color: #d1ecf1; color: #0c5460; }
    .status-in-use { background-color: #cce5ff; color: #0056b3; }
    .status-maintenance { background-color: #f0f0f0; color: #6c757d; }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
    }

    .modal-xl {
        max-width: 1200px;
    }

    .required-field::after {
        content: " *";
        color: #e74c3c;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        height: 58px;
        padding: 0.375rem 0.75rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .workforce-tabs .nav-link {
            padding: 15px 20px;
            font-size: 14px;
        }

        .dashboard-card {
            margin-bottom: 15px;
            padding: 20px;
        }
        
        .card-value {
            font-size: 24px;
        }
    }
</style>
@endsection

@section('content')
<div class="workforce-container">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Tracker</a></li>
                        <li class="breadcrumb-item active">Workforce and Fleet</li>
                    </ol>
                </div>
                <h4 class="page-title">Workforce and Fleet Management</h4>
                <p class="text-muted mb-0">Manage team members, drivers, and vehicles</p>
            </div>
        </div>
    </div>

    

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-members">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6 class="card-title">Team Members</h6>
                    <h3 class="card-value" id="totalMembers">{{ $team_members_count ?? 0 }}</h3>
                    <div class="card-change">
                        <i class="fas fa-user-check me-1"></i> <span id="activeMembers">{{ $stats->active ?? 0 }}</span> Active
                        @if(($stats->inactive ?? 0) > 0)
                            <br><small class="text-muted"><i class="fas fa-user-times me-1"></i> {{ $stats->inactive ?? 0 }} Inactive</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-drivers">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h6 class="card-title">Drivers</h6>
                    <h3 class="card-value" id="totalDrivers">{{ $drivers_count ?? 0 }}</h3>
                    <div class="card-change">
                        <i class="fas fa-steering-wheel me-1"></i> <span id="availableDrivers">{{ $driver_stats->available ?? 0 }}</span> Available
                        @if(($driver_stats->assigned ?? 0) > 0)
                            <br><small class="text-muted"><i class="fas fa-user-tie me-1"></i> {{ $driver_stats->assigned ?? 0 }} Assigned</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-vehicles">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                        <i class="fas fa-car"></i>
                    </div>
                    <h6 class="card-title">Vehicles</h6>
                    <h3 class="card-value" id="totalVehicles">{{ $vehicles_count ?? 0 }}</h3>
                    <div class="card-change">
                        <i class="fas fa-check-circle me-1"></i> <span id="operationalVehicles">{{ ($vehicle_stats->available ?? 0) + ($vehicle_stats->in_use ?? 0) }}</span> Operational
                        @if(($vehicle_stats->maintenance ?? 0) > 0)
                            <br><small class="text-muted"><i class="fas fa-wrench me-1"></i> {{ $vehicle_stats->maintenance ?? 0 }} In Maintenance</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-total">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h6 class="card-title">Total Resources</h6>
                    <h3 class="card-value" id="totalResources">{{ ($team_members_count ?? 0) + ($drivers_count ?? 0) + ($vehicles_count ?? 0) }}</h3>
                    <div class="card-change">
                        <i class="fas fa-database me-1"></i> All categories
                        <br><small class="text-muted">
                            <i class="fas fa-users me-1"></i> {{ $team_members_count ?? 0 }} Members
                            <i class="fas fa-id-card me-1 ms-2"></i> {{ $drivers_count ?? 0 }} Drivers  
                            <i class="fas fa-car me-1 ms-2"></i> {{ $vehicles_count ?? 0 }} Vehicles
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 me-3">Quick Actions:</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshAllStats()">
                                    <i class="fas fa-sync-alt me-1"></i> Refresh Stats
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#quickAddModal">
                                    <i class="fas fa-plus me-1"></i> Quick Add
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="switchToActiveTab('members')">
                                    <i class="fas fa-users me-1"></i> View Members
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="switchToActiveTab('drivers')">
                                    <i class="fas fa-id-card me-1"></i> View Drivers
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="switchToActiveTab('vehicles')">
                                    <i class="fas fa-car me-1"></i> View Vehicles
                                </button>
                                <button type="button" class="btn btn-outline-dark btn-sm" onclick="exportAllData()">
                                    <i class="fas fa-download me-1"></i> Export All
                                </button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <small><i class="fas fa-info-circle me-1"></i> Last updated: <span id="lastUpdated">Loading...</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs workforce-tabs" id="workforceTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="members-tab" data-bs-toggle="tab" data-bs-target="#members" type="button" role="tab" aria-controls="members" aria-selected="true">
                <i class="fas fa-users"></i>Team Members
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="drivers-tab" data-bs-toggle="tab" data-bs-target="#drivers" type="button" role="tab" aria-controls="drivers" aria-selected="false">
                <i class="fas fa-id-card"></i>Drivers
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="vehicles-tab" data-bs-toggle="tab" data-bs-target="#vehicles" type="button" role="tab" aria-controls="vehicles" aria-selected="false">
                <i class="fas fa-car"></i>Vehicles
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="workforceTabsContent">
        @include('company.MasterTracker.components.team-members')
        @include('company.MasterTracker.components.drivers')
        @include('company.MasterTracker.components.vehicles')
    </div>
</div>

<!-- Team Member Functions (Available Immediately) -->
<script>
// Quick Action Functions - Define globally first
window.switchToActiveTab = function(tabName) {
    console.log('Switching to tab:', tabName);
    const tabElement = document.getElementById(tabName + '-tab');
    if (tabElement) {
        tabElement.click();
    }
};

window.refreshAllStats = function() {
    console.log('Refreshing all stats...');
    // This will be defined later in the document ready function
    if (typeof loadDashboardStats === 'function') {
        loadDashboardStats();
    }
    // Update the last updated time immediately
    window.updateLastUpdatedTime();
    
    // Reload all tables
    if (typeof membersTable !== 'undefined') {
        membersTable.ajax.reload();
    }
    if (typeof driversTable !== 'undefined') {
        driversTable.ajax.reload();
    }
    if (typeof vehiclesTable !== 'undefined') {
        vehiclesTable.ajax.reload();
    }
    
    // Show success message
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Stats Refreshed!',
            text: 'All statistics and tables have been refreshed.',
            showConfirmButton: false,
            timer: 1500
        });
    }
};

window.exportAllData = function() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Export All Data',
            text: 'This will export data from all three tables. Do you want to continue?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, export all!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Export all three datasets
                window.open('{{ route("team-members.export") }}', '_blank');
                setTimeout(() => {
                    window.open('/company/MasterTracker/workforce-fleet/drivers/export', '_blank');
                }, 1000);
                setTimeout(() => {
                    window.open('/company/MasterTracker/workforce-fleet/vehicles/export', '_blank');
                }, 2000);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Export Started!',
                    text: 'All data exports have been initiated.',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    }
};

// Helper function to update last updated time - Available globally
window.updateLastUpdatedTime = function() {
    let now = new Date();
    let timeString = now.toLocaleTimeString('en-US', { 
        hour12: true, 
        hour: '2-digit', 
        minute: '2-digit' 
    });
    const lastUpdatedElement = document.getElementById('lastUpdated');
    if (lastUpdatedElement) {
        lastUpdatedElement.textContent = timeString;
    }
};

// Update the time immediately when the script loads
window.updateLastUpdatedTime();

// Quick Add function - Available globally
window.quickAdd = function(type) {
    console.log('quickAdd called with type:', type);
    
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not available');
        return;
    }
    
    // Hide the quick add modal
    const quickAddModal = document.getElementById('quickAddModal');
    if (quickAddModal) {
        try {
            const modal = new bootstrap.Modal(quickAddModal);
            modal.hide();
        } catch (e) {
            // Fallback to jQuery
            $('#quickAddModal').modal('hide');
        }
    }
    
    // Show the appropriate modal
    let targetModalId = '';
    if (type === 'member') {
        targetModalId = 'addMemberModal';
    } else if (type === 'driver') {
        targetModalId = 'addDriverModal';
    } else if (type === 'vehicle') {
        targetModalId = 'addVehicleModal';
    }
    
    if (targetModalId) {
        const targetModal = document.getElementById(targetModalId);
        if (targetModal) {
            try {
                const modal = new bootstrap.Modal(targetModal);
                modal.show();
            } catch (e) {
                // Fallback to jQuery
                $('#' + targetModalId).modal('show');
            }
        } else {
            console.error('Target modal not found:', targetModalId);
        }
    }
};

// Test that the function is available
console.log('quickAdd function defined:', typeof window.quickAdd);

// Team Member Functions (following Categories section pattern)
function viewMember(id) {
    console.log('Viewing member:', id);
    
    $.ajax({
        url: `{{ url('company/MasterTracker/team-members') }}/${id}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                const member = response.data;
                
                // Populate the view modal
                $('#viewMemberFullName').text(member.full_name);
                $('#viewMemberEmployeeId').text(member.employee_id);
                $('#viewMemberPosition').text(member.position);
                $('#viewMemberDepartment').text(member.department ? member.department.name : 'N/A');
                $('#viewMemberPhone').text(member.phone);
                $('#viewMemberEmail').text(member.email);
                $('#viewMemberHireDate').text(member.hire_date || 'N/A');
                $('#viewMemberNotes').text(member.notes || 'No notes available');
                $('#viewMemberCreated').text(member.created_at || 'N/A');
                $('#viewMemberUpdated').text(member.updated_at || 'N/A');
                
                // Format status with badge
                const statusBadge = member.status === 'active' ? 'bg-success' :
                                   member.status === 'inactive' ? 'bg-danger' : 'bg-warning';
                $('#viewMemberStatus').html(`<span class="badge ${statusBadge}">${member.status.replace('-', ' ').toUpperCase()}</span>`);
                
                // Store member ID for edit button
                $('#editFromViewBtn').attr('data-member-id', id);
                
                // Show the modal
                $('#viewMemberModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to load member details'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load member details'
            });
        }
    });
}

function editMember(id) {
    console.log('Editing member:', id);
    
    $.ajax({
        url: `{{ url('company/MasterTracker/team-members') }}/${id}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                const member = response.data;
                
                // Populate edit form
                $('#editMemberId').val(member.id);
                $('#editMemberFullName').val(member.full_name);
                $('#editMemberEmployeeId').val(member.employee_id);
                $('#editMemberPosition').val(member.position);
                $('#editMemberDepartment').val(member.department_id || '');
                $('#editMemberPhone').val(member.phone);
                $('#editMemberEmail').val(member.email);
                
                // Format hire date for HTML date input (YYYY-MM-DD)
                if (member.hire_date) {
                    const hireDate = new Date(member.hire_date);
                    const formattedDate = hireDate.toISOString().split('T')[0];
                    $('#editMemberHireDate').val(formattedDate);
                } else {
                    $('#editMemberHireDate').val('');
                }
                
                $('#editMemberStatus').val(member.status);
                $('#editMemberNotes').val(member.notes || '');
                
                // Show edit modal
                $('#editMemberModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to load member details'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load member details'
            });
        }
    });
}

function deleteMember(id) {
    console.log('Deleting member:', id);
    
    // Get member name from the table row for confirmation
    const memberName = $(`button[onclick="deleteMember(${id})"]`).closest('tr').find('td:nth-child(2) h6').text();
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete "${memberName}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `{{ url('company/MasterTracker/team-members') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        // Refresh page to show updated data
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to delete member'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to delete member'
                    });
                }
            });
        }
    });
}

function editMemberFromView() {
    const memberId = $('#editFromViewBtn').attr('data-member-id');
    $('#viewMemberModal').modal('hide');
    setTimeout(() => {
        editMember(memberId);
    }, 300);
}

// Test function
window.testFunction = function() {
    console.log('Test function works!');
    alert('Test function works!');
};

console.log('=== TEAM MEMBER FUNCTIONS LOADED ===');
console.log('Functions available:', {
    viewMember: typeof viewMember,
    editMember: typeof editMember,
    deleteMember: typeof deleteMember,
    editMemberFromView: typeof editMemberFromView
});

// Driver Functions (following Team Member pattern)
function viewDriver(id) {
    console.log('Viewing driver:', id);
    
    $.ajax({
        url: `{{ url('company/MasterTracker/drivers') }}/${id}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                const driver = response.data;
                
                // Populate the view modal
                $('#viewDriverFullName').text(driver.full_name);
                $('#viewDriverLicenseNumber').text(driver.license_number);
                $('#viewDriverLicenseType').text(driver.license_type_formatted);
                $('#viewDriverPhone').text(driver.phone);
                $('#viewDriverExperience').text(driver.experience_years || 'N/A');
                $('#viewDriverLicenseExpiry').text(driver.license_expiry ? new Date(driver.license_expiry).toLocaleDateString() : 'N/A');
                $('#viewDriverEmergencyContact').text(driver.emergency_contact || 'N/A');
                $('#viewDriverNotes').text(driver.notes || 'No notes available');
                $('#viewDriverCreated').text(driver.created_at || 'N/A');
                $('#viewDriverUpdated').text(driver.updated_at || 'N/A');
                
                // Format status with badge
                const statusBadge = driver.status === 'available' ? 'bg-success' :
                                   driver.status === 'assigned' ? 'bg-primary' :
                                   driver.status === 'on-leave' ? 'bg-warning' : 'bg-danger';
                $('#viewDriverStatus').html(`<span class="badge ${statusBadge}">${driver.status_formatted}</span>`);
                
                // Store driver ID for edit button
                $('#editFromViewDriverBtn').attr('data-driver-id', id);
                
                // Show the modal
                $('#viewDriverModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to load driver details'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load driver details'
            });
        }
    });
}

function editDriver(id) {
    console.log('Editing driver:', id);
    
    $.ajax({
        url: `{{ url('company/MasterTracker/drivers') }}/${id}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                const driver = response.data;
                
                // Populate edit form
                $('#editDriverId').val(driver.id);
                $('#editDriverFullName').val(driver.full_name);
                $('#editDriverLicenseNumber').val(driver.license_number);
                $('#editDriverLicenseType').val(driver.license_type);
                $('#editDriverPhone').val(driver.phone);
                $('#editDriverExperience').val(driver.experience_years || '');
                
                // Format license expiry date for HTML date input (YYYY-MM-DD)
                if (driver.license_expiry) {
                    const licenseExpiry = new Date(driver.license_expiry);
                    const formattedDate = licenseExpiry.toISOString().split('T')[0];
                    $('#editDriverLicenseExpiry').val(formattedDate);
                } else {
                    $('#editDriverLicenseExpiry').val('');
                }
                
                $('#editDriverEmergencyContact').val(driver.emergency_contact || '');
                $('#editDriverStatus').val(driver.status);
                $('#editDriverNotes').val(driver.notes || '');
                
                // Show edit modal
                $('#editDriverModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to load driver details'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load driver details'
            });
        }
    });
}

function deleteDriver(id) {
    console.log('Deleting driver:', id);
    
    // Get driver name from the table row for confirmation
    const driverName = $(`button[onclick="deleteDriver(${id})"]`).closest('tr').find('td:nth-child(2) h6').text();
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete "${driverName}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `{{ url('company/MasterTracker/drivers') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        // Refresh page to show updated data
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to delete driver'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to delete driver'
                    });
                }
            });
        }
    });
}

function editDriverFromView() {
    const driverId = $('#editFromViewDriverBtn').attr('data-driver-id');
    $('#viewDriverModal').modal('hide');
    setTimeout(() => {
        editDriver(driverId);
    }, 300);
}

console.log('=== DRIVER FUNCTIONS LOADED ===');
console.log('Driver functions available:', {
    viewDriver: typeof viewDriver,
    editDriver: typeof editDriver,
    deleteDriver: typeof deleteDriver,
    editDriverFromView: typeof editDriverFromView
});

// Vehicle Functions (following Driver pattern)
function viewVehicle(id) {
    console.log('Viewing vehicle:', id);

    $.ajax({
        url: `{{ url('company/MasterTracker/vehicles') }}/${id}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                const vehicle = response.data;

                // Populate the view modal
                $('#viewVehicleRegistration').text(vehicle.registration_number);
                $('#viewVehicleMakeModel').text(vehicle.make_model);
                $('#viewVehicleType').text(vehicle.type_formatted);
                $('#viewVehicleYear').text(vehicle.year);
                $('#viewVehicleColor').text(vehicle.color || 'N/A');
                $('#viewVehicleFuelType').text(vehicle.fuel_type || 'N/A');
                $('#viewVehicleInsuranceExpiry').text(vehicle.insurance_expiry ? new Date(vehicle.insurance_expiry).toLocaleDateString() : 'N/A');
                $('#viewVehicleMileage').text(vehicle.mileage || 'N/A');
                $('#viewVehicleAssignedDriver').text(vehicle.assigned_driver ? vehicle.assigned_driver.full_name : 'Unassigned');
                $('#viewVehicleNotes').text(vehicle.notes || 'No notes available');
                $('#viewVehicleCreated').text(vehicle.created_at || 'N/A');
                $('#viewVehicleUpdated').text(vehicle.updated_at || 'N/A');

                // Format status with badge
                const statusBadge = vehicle.status === 'available' ? 'bg-success' :
                                   vehicle.status === 'in-use' ? 'bg-primary' :
                                   vehicle.status === 'maintenance' ? 'bg-warning' : 'bg-danger';
                $('#viewVehicleStatus').text(vehicle.status_formatted);

                // Store vehicle ID for edit button
                $('#editFromViewVehicleBtn').attr('data-vehicle-id', id);

                // Show the modal
                $('#viewVehicleModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to load vehicle details'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load vehicle details'
            });
        }
    });
}

function editVehicle(id) {
    console.log('Editing vehicle:', id);

    $.ajax({
        url: `{{ url('company/MasterTracker/vehicles') }}/${id}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                const vehicle = response.data;

                // Populate edit form
                $('#editVehicleId').val(vehicle.id);
                $('#editVehicleRegistration').val(vehicle.registration_number);
                $('#editVehicleMake').val(vehicle.make);
                $('#editVehicleModel').val(vehicle.model);
                $('#editVehicleType').val(vehicle.type);
                $('#editVehicleYear').val(vehicle.year);
                $('#editVehicleColor').val(vehicle.color || '');
                $('#editVehicleFuelType').val(vehicle.fuel_type || '');

                // Format insurance expiry date for HTML date input (YYYY-MM-DD)
                if (vehicle.insurance_expiry) {
                    const insuranceExpiry = new Date(vehicle.insurance_expiry);
                    const formattedDate = insuranceExpiry.toISOString().split('T')[0];
                    $('#editVehicleInsuranceExpiry').val(formattedDate);
                } else {
                    $('#editVehicleInsuranceExpiry').val('');
                }

                $('#editVehicleMileage').val(vehicle.mileage || '');
                $('#editVehicleStatus').val(vehicle.status);
                $('#editVehicleAssignedDriver').val(vehicle.assigned_driver_id || '');
                $('#editVehicleNotes').val(vehicle.notes || '');

                // Show edit modal
                $('#editVehicleModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to load vehicle details'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load vehicle details'
            });
        }
    });
}

function deleteVehicle(id) {
    console.log('Deleting vehicle:', id);

    // Get vehicle registration from the table row for confirmation
    const vehicleRegistration = $(`button[onclick="deleteVehicle(${id})"]`).closest('tr').find('td:nth-child(2) h6').text();

    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete vehicle "${vehicleRegistration}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `{{ url('company/MasterTracker/vehicles') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Refresh page to show updated data
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to delete vehicle'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to delete vehicle'
                    });
                }
            });
        }
    });
}

function editVehicleFromView() {
    const vehicleId = $('#editFromViewVehicleBtn').attr('data-vehicle-id');
    $('#viewVehicleModal').modal('hide');
    setTimeout(() => {
        editVehicle(vehicleId);
    }, 300);
}

console.log('=== VEHICLE FUNCTIONS LOADED ===');
console.log('Vehicle functions available:', {
    viewVehicle: typeof viewVehicle,
    editVehicle: typeof editVehicle,
    deleteVehicle: typeof deleteVehicle,
    editVehicleFromView: typeof editVehicleFromView
});

// Update Member Form Function (moved to inline onclick)
</script>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">Add Team Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addMemberForm" onsubmit="return false;">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="full_name" id="memberFullName" placeholder=" " required>
                                <label for="memberFullName" class="required-field">Full Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="employee_id" id="memberEmployeeId" placeholder=" " required>
                                <label for="memberEmployeeId" class="required-field">Employee ID</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="position" id="memberPosition" placeholder=" " required>
                                <label for="memberPosition" class="required-field">Position</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                            <select class="form-select" name="department_id" id="memberDepartment" required>
                                    <option value="">Select Department</option>
                                @foreach($department_categories as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                                </select>
                                <label for="memberDepartment" class="required-field">Department</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="phone" id="memberPhone" placeholder=" " required>
                                <label for="memberPhone" class="required-field">Phone Number</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="email" id="memberEmail" placeholder=" " required>
                                <label for="memberEmail" class="required-field">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="hire_date" id="memberHireDate" placeholder=" ">
                                <label for="memberHireDate">Hire Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="memberStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="on-leave">On Leave</option>
                                </select>
                                <label for="memberStatus" class="required-field">Status</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="memberNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="memberNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveMemberBtn" onclick="
                        console.log('Saving team member...');
                        try {
                            let form = document.getElementById('addMemberForm');
                            if (!form) { 
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Form not found'
                                }); 
                                return; 
                            }
                            
                            if (form.checkValidity() === false) {
                                form.reportValidity();
                                return;
                            }
                            
                            // Show loading
                            Swal.fire({
                                title: 'Saving...',
                                text: 'Please wait while we save the team member.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            let formData = new FormData(form);
                            fetch('{{ route("team-members.store") }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Hide modal first, then show success
                                    $('#addMemberModal').modal('hide');
                                    
                                    // Remove any leftover backdrops
                                    setTimeout(() => {
                                        $('.modal-backdrop').remove();
                                        $('body').removeClass('modal-open');
                                        $('body').css('padding-right', '');
                                        
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: data.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }, 100);
                                    
                                    form.reset();
                                    // Refresh the page to show new data
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                    if (typeof loadDashboardStats === 'function') loadDashboardStats();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: data.message || 'An error occurred while saving.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Network Error!',
                                    text: 'Failed to save team member. Please try again.',
                                    confirmButtonText: 'OK'
                                });
                            });
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Unexpected Error!',
                                text: error.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    ">Save Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Driver Modal -->
<div class="modal fade" id="addDriverModal" tabindex="-1" aria-labelledby="addDriverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDriverModalLabel">Add Driver</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addDriverForm" onsubmit="return false;">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="full_name" id="driverFullName" placeholder=" " required>
                                <label for="driverFullName" class="required-field">Full Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="license_number" id="driverLicenseNumber" placeholder=" " required>
                                <label for="driverLicenseNumber" class="required-field">License Number</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="license_type" id="driverLicenseType" required>
                                    <option value="">Select License Type</option>
                                    <option value="class-a">Class A</option>
                                    <option value="class-b">Class B</option>
                                    <option value="class-c">Class C</option>
                                    <option value="motorcycle">Motorcycle</option>
                                </select>
                                <label for="driverLicenseType" class="required-field">License Type</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="phone" id="driverPhone" placeholder=" " required>
                                <label for="driverPhone" class="required-field">Phone Number</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="experience_years" id="driverExperience" placeholder=" " min="0">
                                <label for="driverExperience">Experience (Years)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="license_expiry" id="driverLicenseExpiry" placeholder=" " required>
                                <label for="driverLicenseExpiry" class="required-field">License Expiry Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="emergency_contact" id="driverEmergencyContact" placeholder=" ">
                                <label for="driverEmergencyContact">Emergency Contact</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="driverStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="available">Available</option>
                                    <option value="assigned">Assigned</option>
                                    <option value="on-leave">On Leave</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="driverStatus" class="required-field">Status</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="driverNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="driverNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveDriverBtn" onclick="
                        console.log('Saving driver...');
                        try {
                            let form = document.getElementById('addDriverForm');
                            if (!form) { 
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Form not found'
                                }); 
                                return; 
                            }
                            
                            if (form.checkValidity() === false) {
                                form.reportValidity();
                                return;
                            }
                            
                            // Show loading
                            Swal.fire({
                                title: 'Saving...',
                                text: 'Please wait while we save the driver.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            let formData = new FormData(form);
                            
                            // Debug: Log form data
                            console.log('Form data being sent:');
                            for (let [key, value] of formData.entries()) {
                                console.log(key, value);
                            }
                            
                            fetch('{{ route("drivers.store") }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Hide modal first, then show success
                                    $('#addDriverModal').modal('hide');
                                    
                                    // Remove any leftover backdrops
                                    setTimeout(() => {
                                        $('.modal-backdrop').remove();
                                        $('body').removeClass('modal-open');
                                        $('body').css('padding-right', '');
                                        
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: data.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }, 100);
                                    
                                    form.reset();
                                    // Refresh the page to show new data
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                    if (typeof loadDashboardStats === 'function') loadDashboardStats();
                                } else {
                                    let errorMessage = data.message || 'An error occurred while saving.';
                                    
                                    // If there are validation errors, show them
                                    if (data.errors) {
                                        let errorList = '';
                                        for (let field in data.errors) {
                                            errorList += data.errors[field].join('<br>') + '<br>';
                                        }
                                        errorMessage = errorList;
                                    }
                                    
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        html: errorMessage,
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Network Error!',
                                    text: 'Failed to save driver. Please try again.',
                                    confirmButtonText: 'OK'
                                });
                            });
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Unexpected Error!',
                                text: error.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    ">Save Driver</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Driver Modal -->
<div class="modal fade" id="viewDriverModal" tabindex="-1" aria-labelledby="viewDriverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDriverModalLabel">View Driver Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Full Name:</label>
                        <p id="viewDriverFullName" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">License Number:</label>
                        <p id="viewDriverLicenseNumber" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">License Type:</label>
                        <p id="viewDriverLicenseType" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Phone:</label>
                        <p id="viewDriverPhone" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Experience (Years):</label>
                        <p id="viewDriverExperience" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">License Expiry:</label>
                        <p id="viewDriverLicenseExpiry" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Emergency Contact:</label>
                        <p id="viewDriverEmergencyContact" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status:</label>
                        <p id="viewDriverStatus" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Notes:</label>
                        <p id="viewDriverNotes" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Created:</label>
                        <p id="viewDriverCreated" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Last Updated:</label>
                        <p id="viewDriverUpdated" class="form-control-plaintext"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editDriverFromView()" id="editFromViewDriverBtn">
                    <i class="fas fa-edit me-1"></i> Edit Driver
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Driver Modal -->
<div class="modal fade" id="editDriverModal" tabindex="-1" aria-labelledby="editDriverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDriverModalLabel">Edit Driver</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDriverForm" onsubmit="return false;">
                @csrf
                @method('PUT')
                <input type="hidden" name="driver_id" id="editDriverId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="full_name" id="editDriverFullName" placeholder=" " required>
                                <label for="editDriverFullName" class="required-field">Full Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="license_number" id="editDriverLicenseNumber" placeholder=" " required>
                                <label for="editDriverLicenseNumber" class="required-field">License Number</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="license_type" id="editDriverLicenseType" required>
                                    <option value="">Select License Type</option>
                                    <option value="class-a">Class A</option>
                                    <option value="class-b">Class B</option>
                                    <option value="class-c">Class C</option>
                                    <option value="motorcycle">Motorcycle</option>
                                </select>
                                <label for="editDriverLicenseType" class="required-field">License Type</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="phone" id="editDriverPhone" placeholder=" " required>
                                <label for="editDriverPhone" class="required-field">Phone Number</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="experience_years" id="editDriverExperience" placeholder=" " min="0">
                                <label for="editDriverExperience">Experience (Years)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="license_expiry" id="editDriverLicenseExpiry" placeholder=" " required>
                                <label for="editDriverLicenseExpiry" class="required-field">License Expiry Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="emergency_contact" id="editDriverEmergencyContact" placeholder=" ">
                                <label for="editDriverEmergencyContact">Emergency Contact</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="editDriverStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="available">Available</option>
                                    <option value="assigned">Assigned</option>
                                    <option value="on-leave">On Leave</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="editDriverStatus" class="required-field">Status</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="editDriverNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="editDriverNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="updateDriverBtn" onclick="
                        console.log('Updating driver...');
                        try {
                            let form = document.getElementById('editDriverForm');
                            if (!form) { 
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Form not found'
                                }); 
                                return; 
                            }
                            
                            if (form.checkValidity() === false) {
                                form.reportValidity();
                                return;
                            }
                            
                            let driverId = document.getElementById('editDriverId').value;
                            if (!driverId) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Driver ID not found'
                                });
                                return;
                            }
                            
                            // Show loading
                            Swal.fire({
                                title: 'Updating...',
                                text: 'Please wait while we update the driver.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            let formData = new FormData(form);
                            fetch('{{ route("drivers.update", "") }}/' + driverId, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Hide modal first, then show success
                                    $('#editDriverModal').modal('hide');
                                    
                                    // Remove any leftover backdrops
                                    setTimeout(() => {
                                        $('.modal-backdrop').remove();
                                        $('body').removeClass('modal-open');
                                        $('body').css('padding-right', '');
                                        
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Updated!',
                                            text: data.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }, 100);
                                    
                                    // Refresh the page to show updated data
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: data.message || 'An error occurred while updating.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Network Error!',
                                    text: 'Failed to update driver. Please try again.',
                                    confirmButtonText: 'OK'
                                });
                            });
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Unexpected Error!',
                                text: error.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    ">Update Driver</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVehicleModalLabel">Add Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addVehicleForm" onsubmit="return false;">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="registration_number" id="vehicleRegistration" placeholder=" " required>
                                <label for="vehicleRegistration" class="required-field">Registration Number</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="make" id="vehicleMake" placeholder=" " required>
                                <label for="vehicleMake" class="required-field">Make</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="model" id="vehicleModel" placeholder=" " required>
                                <label for="vehicleModel" class="required-field">Model</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="type" id="vehicleType" required>
                                    <option value="">Select Type</option>
                                    <option value="sedan">Sedan</option>
                                    <option value="suv">SUV</option>
                                    <option value="truck">Truck</option>
                                    <option value="van">Van</option>
                                    <option value="motorcycle">Motorcycle</option>
                                </select>
                                <label for="vehicleType" class="required-field">Vehicle Type</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="year" id="vehicleYear" placeholder=" " min="1990" max="2025" required>
                                <label for="vehicleYear" class="required-field">Year</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="color" id="vehicleColor" placeholder=" ">
                                <label for="vehicleColor">Color</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="fuel_type" id="vehicleFuelType" placeholder=" ">
                                <label for="vehicleFuelType">Fuel Type</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="insurance_expiry" id="vehicleInsuranceExpiry" placeholder=" " required>
                                <label for="vehicleInsuranceExpiry" class="required-field">Insurance Expiry</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="mileage" id="vehicleMileage" placeholder=" " min="0">
                                <label for="vehicleMileage">Current Mileage</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="vehicleStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="available">Available</option>
                                    <option value="in-use">In Use</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="vehicleStatus" class="required-field">Status</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="assigned_driver_id" id="vehicleAssignedDriver">
                                    <option value="">Select Driver</option>
                                    @if(isset($drivers) && $drivers->count() > 0)
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="vehicleAssignedDriver">Assigned Driver</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="vehicleNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="vehicleNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveVehicleBtn" onclick="
                        console.log('Saving vehicle...');
                        try {
                            let form = document.getElementById('addVehicleForm');
                            if (!form) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Form not found'
                                });
                                return;
                            }

                            if (form.checkValidity() === false) {
                                form.reportValidity();
                                return;
                            }
                            
                            // Show loading
                            Swal.fire({
                                title: 'Saving...',
                                text: 'Please wait while we save the vehicle.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            let formData = new FormData(form);
                            
                            // Debug: Log form data
                            console.log('Form data being sent:');
                            for (let [key, value] of formData.entries()) {
                                console.log(key, value);
                            }
                            
                            fetch('{{ route("vehicles.store") }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Hide modal first, then show success
                                    $('#addVehicleModal').modal('hide');
                                    
                                    // Remove any leftover backdrops
                                    setTimeout(() => {
                                        $('.modal-backdrop').remove();
                                        $('body').removeClass('modal-open');
                                        $('body').css('padding-right', '');
                                        
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: data.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }, 100);
                                    
                                    form.reset();
                                    // Refresh the page to show new data
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                    if (typeof loadDashboardStats === 'function') loadDashboardStats();
                                } else {
                                    let errorMessage = data.message || 'An error occurred while saving.';
                                    
                                    // If there are validation errors, show them
                                    if (data.errors) {
                                        let errorList = '';
                                        for (let field in data.errors) {
                                            errorList += data.errors[field].join('<br>') + '<br>';
                                        }
                                        errorMessage = errorList;
                                    }
                                    
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        html: errorMessage,
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Network Error!',
                                    text: 'Failed to save vehicle. Please try again.',
                                    confirmButtonText: 'OK'
                                });
                            });
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Unexpected Error!',
                                text: error.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    ">Save Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Vehicle Modal -->
<div class="modal fade" id="viewVehicleModal" tabindex="-1" aria-labelledby="viewVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewVehicleModalLabel">Vehicle Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Registration Number:</label>
                        <p id="viewVehicleRegistration" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Make & Model:</label>
                        <p id="viewVehicleMakeModel" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Vehicle Type:</label>
                        <p id="viewVehicleType" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Year:</label>
                        <p id="viewVehicleYear" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Color:</label>
                        <p id="viewVehicleColor" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fuel Type:</label>
                        <p id="viewVehicleFuelType" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Insurance Expiry:</label>
                        <p id="viewVehicleInsuranceExpiry" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Current Mileage:</label>
                        <p id="viewVehicleMileage" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status:</label>
                        <p id="viewVehicleStatus" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Assigned Driver:</label>
                        <p id="viewVehicleAssignedDriver" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Notes:</label>
                        <p id="viewVehicleNotes" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Created:</label>
                        <p id="viewVehicleCreated" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Last Updated:</label>
                        <p id="viewVehicleUpdated" class="form-control-plaintext"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="editFromViewVehicleBtn" onclick="editVehicleFromView()">
                    <i class="fas fa-edit me-1"></i> Edit Vehicle
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Vehicle Modal -->
<div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVehicleModalLabel">Edit Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editVehicleForm" onsubmit="return false;">
                @csrf
                <input type="hidden" id="editVehicleId" name="vehicle_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="registration_number" id="editVehicleRegistration" placeholder=" " required>
                                <label for="editVehicleRegistration" class="required-field">Registration Number</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="make" id="editVehicleMake" placeholder=" " required>
                                <label for="editVehicleMake" class="required-field">Make</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="model" id="editVehicleModel" placeholder=" " required>
                                <label for="editVehicleModel" class="required-field">Model</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="type" id="editVehicleType" required>
                                    <option value="">Select Type</option>
                                    <option value="sedan">Sedan</option>
                                    <option value="suv">SUV</option>
                                    <option value="truck">Truck</option>
                                    <option value="van">Van</option>
                                    <option value="motorcycle">Motorcycle</option>
                                </select>
                                <label for="editVehicleType" class="required-field">Vehicle Type</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="year" id="editVehicleYear" placeholder=" " min="1990" max="2025" required>
                                <label for="editVehicleYear" class="required-field">Year</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="color" id="editVehicleColor" placeholder=" ">
                                <label for="editVehicleColor">Color</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="fuel_type" id="editVehicleFuelType" placeholder=" ">
                                <label for="editVehicleFuelType">Fuel Type</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="insurance_expiry" id="editVehicleInsuranceExpiry" placeholder=" " required>
                                <label for="editVehicleInsuranceExpiry" class="required-field">Insurance Expiry</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="mileage" id="editVehicleMileage" placeholder=" " min="0">
                                <label for="editVehicleMileage">Current Mileage</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="editVehicleStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="available">Available</option>
                                    <option value="in-use">In Use</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="editVehicleStatus" class="required-field">Status</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="assigned_driver_id" id="editVehicleAssignedDriver">
                                    <option value="">Select Driver</option>
                                    @if(isset($drivers) && $drivers->count() > 0)
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="editVehicleAssignedDriver">Assigned Driver</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="editVehicleNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="editVehicleNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="updateVehicleBtn" onclick="
                        console.log('Updating vehicle...');
                        try {
                            let form = document.getElementById('editVehicleForm');
                            if (!form) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Form not found'
                                });
                                return;
                            }

                            if (form.checkValidity() === false) {
                                form.reportValidity();
                                return;
                            }
                            
                            let vehicleId = document.getElementById('editVehicleId').value;
                            if (!vehicleId) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Vehicle ID not found'
                                });
                                return;
                            }
                            
                            // Show loading
                            Swal.fire({
                                title: 'Updating...',
                                text: 'Please wait while we update the vehicle.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            let formData = new FormData(form);
                            formData.append('_method', 'PUT');
                            
                            fetch('{{ route("vehicles.update", "") }}/' + vehicleId, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Hide modal first, then show success
                                    $('#editVehicleModal').modal('hide');
                                    
                                    // Remove any leftover backdrops
                                    setTimeout(() => {
                                        $('.modal-backdrop').remove();
                                        $('body').removeClass('modal-open');
                                        $('body').css('padding-right', '');
                                        
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: data.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }, 100);
                                    
                                    // Refresh the page to show updated data
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                    if (typeof loadDashboardStats === 'function') loadDashboardStats();
                                } else {
                                    let errorMessage = data.message || 'An error occurred while updating.';
                                    
                                    // If there are validation errors, show them
                                    if (data.errors) {
                                        let errorList = '';
                                        for (let field in data.errors) {
                                            errorList += data.errors[field].join('<br>') + '<br>';
                                        }
                                        errorMessage = errorList;
                                    }
                                    
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        html: errorMessage,
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Network Error!',
                                    text: 'Failed to update vehicle. Please try again.',
                                    confirmButtonText: 'OK'
                                });
                            });
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Unexpected Error!',
                                text: error.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    ">Update Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Member Modal -->
<div class="modal fade" id="viewMemberModal" tabindex="-1" aria-labelledby="viewMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewMemberModalLabel">View Team Member Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Full Name:</label>
                        <p id="viewMemberFullName" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Employee ID:</label>
                        <p id="viewMemberEmployeeId" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Position:</label>
                        <p id="viewMemberPosition" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Department:</label>
                        <p id="viewMemberDepartment" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Phone:</label>
                        <p id="viewMemberPhone" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email:</label>
                        <p id="viewMemberEmail" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hire Date:</label>
                        <p id="viewMemberHireDate" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status:</label>
                        <p id="viewMemberStatus" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Notes:</label>
                        <p id="viewMemberNotes" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Created:</label>
                        <p id="viewMemberCreated" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Last Updated:</label>
                        <p id="viewMemberUpdated" class="form-control-plaintext"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editMemberFromView()" id="editFromViewBtn">
                    <i class="fas fa-edit me-1"></i> Edit Member
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modals (similar structure to Add modals but with edit prefix) -->
<!-- Edit Member Modal -->
<div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMemberModalLabel">Edit Team Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMemberForm" onsubmit="return false;">
                @csrf
                @method('PUT')
                <input type="hidden" name="member_id" id="editMemberId">
                <div class="modal-body">
                    <!-- Same fields as add member modal with edit prefix -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="full_name" id="editMemberFullName" placeholder=" " required>
                                <label for="editMemberFullName" class="required-field">Full Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="employee_id" id="editMemberEmployeeId" placeholder=" " required>
                                <label for="editMemberEmployeeId" class="required-field">Employee ID</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="position" id="editMemberPosition" placeholder=" " required>
                                <label for="editMemberPosition" class="required-field">Position</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="department_id" id="editMemberDepartment" required>
                                    <option value="">Select Department</option>
                                    @foreach($department_categories as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <label for="editMemberDepartment" class="required-field">Department</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="phone" id="editMemberPhone" placeholder=" " required>
                                <label for="editMemberPhone" class="required-field">Phone Number</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="email" id="editMemberEmail" placeholder=" " required>
                                <label for="editMemberEmail" class="required-field">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="hire_date" id="editMemberHireDate" placeholder=" ">
                                <label for="editMemberHireDate">Hire Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="editMemberStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="on-leave">On Leave</option>
                                </select>
                                <label for="editMemberStatus" class="required-field">Status</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="editMemberNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="editMemberNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="updateMemberBtn" onclick="
                        console.log('Updating team member...');
                        try {
                            let form = document.getElementById('editMemberForm');
                            if (!form) { 
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Form not found'
                                }); 
                                return; 
                            }
                            
                            if (form.checkValidity() === false) {
                                form.reportValidity();
                                return;
                            }
                            
                            let memberId = document.getElementById('editMemberId').value;
                            if (!memberId) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Member ID not found'
                                });
                                return;
                            }
                            
                            // Show loading
                            Swal.fire({
                                title: 'Updating...',
                                text: 'Please wait while we update the team member.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            let formData = new FormData(form);
                            fetch('{{ route("team-members.update", "") }}/' + memberId, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Hide modal first, then show success
                                    $('#editMemberModal').modal('hide');
                                    
                                    // Remove any leftover backdrops
                                    setTimeout(() => {
                                        $('.modal-backdrop').remove();
                                        $('body').removeClass('modal-open');
                                        $('body').css('padding-right', '');
                                        
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Updated!',
                                            text: data.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }, 100);
                                    
                                    // Refresh the page to show updated data
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: data.message || 'An error occurred while updating.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Network Error!',
                                    text: 'Failed to update team member. Please try again.',
                                    confirmButtonText: 'OK'
                                });
                            });
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Unexpected Error!',
                                text: error.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    ">Update Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Add Modal -->
<div class="modal fade" id="quickAddModal" tabindex="-1" aria-labelledby="quickAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickAddModalLabel">Quick Add</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>What would you like to add?</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="quickAdd('member')">
                        <i class="fas fa-users me-2"></i> Team Member
                    </button>
                    <button type="button" class="btn btn-outline-warning" onclick="quickAdd('driver')">
                        <i class="fas fa-id-card me-2"></i> Driver
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="quickAdd('vehicle')">
                        <i class="fas fa-car me-2"></i> Vehicle
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this <span id="deleteItemType"></span>?</p>
                <p><strong><span id="deleteItemName"></span></strong></p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CDN -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
// Test if script is loading
console.log('Script loading...');

// Global function to save member form
window.saveMemberForm = function() {
    console.log('saveMemberForm called');
    alert('Function is working!');
    
    try {
        // Validate form
        let form = document.getElementById('addMemberForm');
        console.log('Form found:', form);
        
        if (!form) {
            alert('Form not found!');
            return;
        }
        
        if (form.checkValidity() === false) {
            console.log('Form validation failed');
            form.reportValidity();
            return;
        }
        
        console.log('Form validation passed, submitting...');
        alert('Form is valid, submitting...');
        
        // Check if submitForm function exists
        if (typeof submitForm !== 'function') {
            alert('submitForm function not found!');
            return;
        }
        
        // Submit via AJAX
        submitForm(form, '{{ route("team-members.store") }}', 'Member added successfully!', window.membersTable);
    } catch (error) {
        console.error('Error in save member handler:', error);
        alert('An error occurred: ' + error.message);
    }
};

// Test function availability
console.log('saveMemberForm defined:', typeof window.saveMemberForm);

// Team Member Functions moved to immediate script section above

// Debug: Test if script is loading
console.log('=== WORKFORCE FLEET SCRIPT LOADING ===');
console.log('Page loaded at:', new Date());

// Test basic functionality
window.testFunction = function() {
    console.log('Test function works!');
    alert('Test function works!');
};

// Debug: Test if functions are properly defined (moved to immediate script section)

$(document).ready(function() {
    // Load initial stats
    loadDashboardStats();
    
    // Update last updated time
    window.updateLastUpdatedTime();
    
    // Initialize Tables
    initializeMembersTable();
    let driversTable = initializeDriversTable();
    let vehiclesTable = initializeVehiclesTable();
    
    // Tab change handlers
    $('#workforceTabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        let target = $(e.target).attr('data-bs-target');
        if (target === '#members') {
            membersTable.columns.adjust().responsive.recalc();
        } else if (target === '#drivers') {
            driversTable.columns.adjust().responsive.recalc();
        } else if (target === '#vehicles') {
            vehiclesTable.columns.adjust().responsive.recalc();
        }
    });

    // Handle save member button click (backup)
    $(document).on('click', '#saveMemberBtn', function(e) {
        e.preventDefault();
        saveMemberForm();
    });

    // Handle update member button click (using inline onclick now)

    $('#addDriverForm').on('submit', function(e) {
        e.preventDefault();
        submitForm(this, '/company/MasterTracker/workforce-fleet/drivers', 'Driver added successfully!', driversTable);
    });

    $('#addVehicleForm').on('submit', function(e) {
        e.preventDefault();
        submitForm(this, '/company/MasterTracker/workforce-fleet/vehicles', 'Vehicle added successfully!', vehiclesTable);
    });

    // Handle update member button click
    $('#updateMemberBtn').on('click', function(e) {
        e.preventDefault();
        console.log('Update member button clicked');
        
        // Validate form
        let form = $('#editMemberForm')[0];
        if (form.checkValidity() === false) {
            form.reportValidity();
            return;
        }
        
        // Get member ID and submit via AJAX
        let memberId = $('#editMemberId').val();
        const formData = new FormData(form);
        
        // Show loading
        Swal.fire({
            title: 'Updating...',
            text: 'Please wait while we update the team member.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch(`{{ route('team-members.update', '') }}/${memberId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal first, then show success
                $('#editMemberModal').modal('hide');
                
                // Remove any leftover backdrops
                setTimeout(() => {
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    $('body').css('padding-right', '');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }, 100);
                
                form.reset();
                // Refresh the page to show updated data
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'An error occurred while updating.',
                    showConfirmButton: true
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while updating the team member.',
                showConfirmButton: true
            });
        });
    });

    // Search handlers for drivers and vehicles (members handled in setupMemberFilters)
    $('#searchDrivers').on('keyup', function() {
        driversTable.search(this.value).draw();
    });

    $('#searchVehicles').on('keyup', function() {
        vehiclesTable.search(this.value).draw();
    });

    // Delete confirmation
    $(document).on('click', '.delete-btn', function() {
        let type = $(this).data('type');
        let id = $(this).data('id');
        let name = $(this).data('name');
        
        $('#deleteItemType').text(type);
        $('#deleteItemName').text(name);
        $('#confirmDeleteBtn').data('type', type).data('id', id);
        $('#deleteConfirmModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', function() {
        let type = $(this).data('type');
        let id = $(this).data('id');
        let endpoint = type === 'member' ? `{{ url('company/MasterTracker/team-members') }}/${id}` : `/company/MasterTracker/workforce-fleet/${type}s/${id}`;
        
        $.ajax({
            url: endpoint,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#deleteConfirmModal').modal('hide');
                    
                    // Reload appropriate table
                    if (type === 'member') {
                        location.reload();
                    } else if (type === 'driver') {
                        driversTable.ajax.reload();
                    } else if (type === 'vehicle') {
                        vehiclesTable.ajax.reload();
                    }
                    
                    loadDashboardStats();
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to delete item.'
                });
            }
        });
    });

    // Edit handlers
    $(document).on('click', '.edit-btn', function() {
        let type = $(this).data('type');
        let id = $(this).data('id');
        
        if (type === 'member') {
            loadMemberForEdit(id);
        } else if (type === 'driver') {
            loadDriverForEdit(id);
        } else if (type === 'vehicle') {
            loadVehicleForEdit(id);
        }
    });

    // Initialize tables functions
    function initializeMembersTable() {
        console.log('Initializing members table with local data...');
        console.log('Table element found:', $('#members-datatable').length);
        
        // Set up local search and filter functionality
        setupMemberFilters();
        
        // Set up action button handlers
        setupMemberActionHandlers();
        
        return true; // Return true to indicate initialization success
    }
    
    // Set up search and filter functionality for team members
    function setupMemberFilters() {
        // Search functionality
        $('#searchMembers').on('input', function() {
            const searchValue = $(this).val().toLowerCase();
            filterMembersTable();
        });
        
        // Status filter
        $('#filterMemberStatus').on('change', function() {
            filterMembersTable();
        });
        
        // Department filter
        $('#filterMemberDepartment').on('change', function() {
            filterMembersTable();
        });
    }
    
    // Set up action button handlers (using onclick attributes now)
    function setupMemberActionHandlers() {
        console.log('Action handlers setup completed - using onclick attributes');
    }
    
    // Filter the members table
    function filterMembersTable() {
        const searchValue = $('#searchMembers').val().toLowerCase();
        const statusFilter = $('#filterMemberStatus').val();
        const departmentFilter = $('#filterMemberDepartment').val();
        
        $('#members-datatable tbody tr').each(function() {
            const row = $(this);
            let showRow = true;
            
            // Skip empty state rows
            if (row.find('td').length === 1) {
                return;
            }
            
            // Search filter
            if (searchValue) {
                const text = row.text().toLowerCase();
                if (!text.includes(searchValue)) {
                    showRow = false;
                }
            }
            
            // Status filter
            if (statusFilter && showRow) {
                const statusBadge = row.find('.badge').text().toLowerCase().replace(' ', '-');
                if (statusBadge !== statusFilter) {
                    showRow = false;
                }
            }
            
            // Department filter (simplified for now)
            if (departmentFilter && showRow) {
                const departmentText = row.find('td:nth-child(5)').text();
                // This is a simplified check - you might want to enhance this
                if (!departmentText.includes(departmentFilter)) {
                    showRow = false;
                }
            }
            
            row.toggle(showRow);
        });
    }
    
    // Apply member filters (called by filter button)
    function applyMemberFilters() {
        filterMembersTable();
    }
    
    // Clear member filters
    function clearMemberFilters() {
        $('#searchMembers').val('');
        $('#filterMemberStatus').val('');
        $('#filterMemberDepartment').val('');
        $('#members-datatable tbody tr').show();
    }
    
    // View member function
    window.viewMember = function(id) {
        console.log('Viewing member with ID:', id);
        
        fetch(`{{ route('team-members.show', '') }}/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const member = data.data;
                
                // Populate view modal
                document.getElementById('viewMemberFullName').textContent = member.full_name;
                document.getElementById('viewMemberEmployeeId').textContent = member.employee_id;
                document.getElementById('viewMemberPosition').textContent = member.position;
                document.getElementById('viewMemberDepartment').textContent = member.department || 'N/A';
                document.getElementById('viewMemberPhone').textContent = member.phone;
                document.getElementById('viewMemberEmail').textContent = member.email;
                document.getElementById('viewMemberHireDate').textContent = member.hire_date || 'N/A';
                
                // Format status with badge
                const statusElement = document.getElementById('viewMemberStatus');
                const statusClass = member.status === 'active' ? 'bg-success' : 
                                   member.status === 'inactive' ? 'bg-danger' : 'bg-warning';
                statusElement.innerHTML = `<span class="badge ${statusClass}">${member.status.replace('-', ' ').toUpperCase()}</span>`;
                
                document.getElementById('viewMemberNotes').textContent = member.notes || 'No notes available';
                document.getElementById('viewMemberCreated').textContent = member.created_at || 'N/A';
                document.getElementById('viewMemberUpdated').textContent = member.updated_at || 'N/A';
                
                // Store member ID for edit button
                document.getElementById('editFromViewBtn').setAttribute('data-member-id', id);
                
                // Show modal
                $('#viewMemberModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Failed to load member details'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load member details'
            });
        });
    }
    
    // Edit member from view modal
    window.editMemberFromView = function() {
        const memberId = document.getElementById('editFromViewBtn').getAttribute('data-member-id');
        $('#viewMemberModal').modal('hide');
        setTimeout(() => {
            editMember(memberId);
        }, 300);
    }
    
    // Edit member function
    window.editMember = function(id) {
        console.log('Editing member with ID:', id);
        
        fetch(`{{ route('team-members.show', '') }}/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const member = data.data;
                
                // Populate edit form
                document.getElementById('editMemberId').value = member.id;
                document.getElementById('editMemberFullName').value = member.full_name;
                document.getElementById('editMemberEmployeeId').value = member.employee_id;
                document.getElementById('editMemberPosition').value = member.position;
                document.getElementById('editMemberDepartment').value = member.department_id || '';
                document.getElementById('editMemberPhone').value = member.phone;
                document.getElementById('editMemberEmail').value = member.email;
                document.getElementById('editMemberHireDate').value = member.hire_date || '';
                document.getElementById('editMemberStatus').value = member.status;
                document.getElementById('editMemberNotes').value = member.notes || '';
                
                // Show edit modal
                $('#editMemberModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Failed to load member details'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load member details'
            });
        });
    }
    
    // Delete member function
    window.deleteMember = function(id, name) {
        console.log('Deleting member with ID:', id, 'Name:', name);
        
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to delete "${name}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the team member.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch(`{{ route('team-members.destroy', '') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        // Refresh page to show updated data
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to delete team member'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to delete team member'
                    });
                });
            }
        });
    }

    function initializeDriversTable() {
        return $('#drivers-datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/company/MasterTracker/workforce-fleet/drivers/data',
                data: function(d) {
                    d.status = $('#filterDriverStatus').val();
                    d.license_type = $('#filterLicenseType').val();
                    d.search = $('#searchDrivers').val();
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'full_name', name: 'full_name' },
                { data: 'license_number', name: 'license_number' },
                { data: 'license_type', name: 'license_type' },
                { data: 'phone', name: 'phone' },
                { data: 'experience_years', name: 'experience_years' },
                { data: 'license_expiry', name: 'license_expiry' },
                { 
                    data: 'status', 
                    name: 'status',
                    render: function(data, type, row) {
                        let badgeClass = 'status-' + data;
                        return `<span class="status-badge ${badgeClass}">${data}</span>`;
                    }
                },
                { 
                    data: 'actions', 
                    name: 'actions', 
                    orderable: false, 
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex gap-1 justify-content-center">
                                <button type="button" class="btn btn-sm btn-warning action-btn edit-btn" 
                                        data-type="driver" data-id="${row.id}" title="Edit Driver">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger action-btn delete-btn" 
                                        data-type="driver" data-id="${row.id}" data-name="${row.full_name}" title="Delete Driver">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 25
        });
    }

    function initializeVehiclesTable() {
        return $('#vehicles-datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/company/MasterTracker/workforce-fleet/vehicles/data',
                data: function(d) {
                    d.status = $('#filterVehicleStatus').val();
                    d.type = $('#filterVehicleType').val();
                    d.search = $('#searchVehicles').val();
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'registration_number', name: 'registration_number' },
                { 
                    data: 'make_model', 
                    name: 'make_model',
                    render: function(data, type, row) {
                        return `${row.make} ${row.model}`;
                    }
                },
                { data: 'type', name: 'type' },
                { data: 'year', name: 'year' },
                { data: 'assigned_driver', name: 'assigned_driver' },
                { data: 'insurance_expiry', name: 'insurance_expiry' },
                { 
                    data: 'status', 
                    name: 'status',
                    render: function(data, type, row) {
                        let badgeClass = 'status-' + data;
                        return `<span class="status-badge ${badgeClass}">${data}</span>`;
                    }
                },
                { 
                    data: 'actions', 
                    name: 'actions', 
                    orderable: false, 
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex gap-1 justify-content-center">
                                <button type="button" class="btn btn-sm btn-warning action-btn edit-btn" 
                                        data-type="vehicle" data-id="${row.id}" title="Edit Vehicle">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger action-btn delete-btn" 
                                        data-type="vehicle" data-id="${row.id}" data-name="${row.registration_number}" title="Delete Vehicle">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 25
        });
    }

    // Helper functions
    function submitForm(form, url, successMessage, table) {
        console.log('submitForm called with:', { url, successMessage, table });
        let formData = new FormData(form);
        
        // Debug: Log form data
        console.log('Form data entries:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('AJAX success response:', response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: successMessage,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $(form).closest('.modal').modal('hide');
                    form.reset();
                    table.ajax.reload();
                    loadDashboardStats();
                } else {
                    console.log('Response success is false:', response);
                }
            },
            error: function(xhr) {
                console.log('AJAX error:', xhr);
                let errors = xhr.responseJSON?.errors || {};
                let errorMessage = 'Please check the form for errors.';
                
                if (Object.keys(errors).length > 0) {
                    errorMessage = Object.values(errors).flat().join('<br>');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage
                });
            }
        });
    }

    function loadMemberForEdit(id) {
        $.ajax({
            url: `{{ url('company/MasterTracker/team-members') }}/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let member = response.data;
                    $('#editMemberId').val(member.id);
                    $('#editMemberFullName').val(member.full_name);
                    $('#editMemberEmployeeId').val(member.employee_id);
                    $('#editMemberPosition').val(member.position);
                    $('#editMemberDepartment').val(member.department_id);
                    $('#editMemberPhone').val(member.phone);
                    $('#editMemberEmail').val(member.email);
                    $('#editMemberHireDate').val(member.hire_date);
                    $('#editMemberStatus').val(member.status);
                    $('#editMemberNotes').val(member.notes);
                    $('#editMemberModal').modal('show');
                }
            }
        });
    }

    function loadDriverForEdit(id) {
        // Similar implementation for drivers
    }

    function loadVehicleForEdit(id) {
        // Similar implementation for vehicles
    }

    function loadDashboardStats() {
        // Stats are now loaded server-side, so we just refresh the page to get updated data
        // This function is kept for compatibility but stats are displayed from server data
        console.log('Stats loaded from server-side data');
    }

    // Helper function to update last updated time (now defined globally above)

    // Global functions
    window.refreshAllStats = function() {
        console.log('Refreshing all stats...');
        loadDashboardStats();
        window.updateLastUpdatedTime();
        
        // Reload all tables
        if (typeof membersTable !== 'undefined') {
            membersTable.ajax.reload();
        }
        if (typeof driversTable !== 'undefined') {
            driversTable.ajax.reload();
        }
        if (typeof vehiclesTable !== 'undefined') {
            vehiclesTable.ajax.reload();
        }
        
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Stats Refreshed!',
            text: 'All statistics and tables have been refreshed.',
            showConfirmButton: false,
            timer: 1500
        });
    };


    window.exportData = function(type) {
        if (type === 'members') {
            window.open('{{ route("team-members.export") }}', '_blank');
        } else {
        window.open(`/company/MasterTracker/workforce-fleet/${type}/export`, '_blank');
        }
    };

    window.applyMemberFilters = function() {
        membersTable.ajax.reload();
    };

    window.clearMemberFilters = function() {
        $('#filterMemberStatus, #filterMemberDepartment').val('');
        membersTable.ajax.reload();
    };

    window.applyDriverFilters = function() {
        driversTable.ajax.reload();
    };

    window.clearDriverFilters = function() {
        $('#filterDriverStatus, #filterLicenseType').val('');
        driversTable.ajax.reload();
    };

    window.applyVehicleFilters = function() {
        vehiclesTable.ajax.reload();
    };

    window.clearVehicleFilters = function() {
        $('#filterVehicleStatus, #filterVehicleType').val('');
        vehiclesTable.ajax.reload();
    };


    // Auto-refresh stats every 5 minutes (like GESL tracker)
    setInterval(function() {
        loadDashboardStats();
        window.updateLastUpdatedTime();
    }, 300000); // 5 minutes

    // Update stats when tables are reloaded
    $('#members-datatable, #drivers-datatable, #vehicles-datatable').on('draw.dt', function() {
        setTimeout(() => {
            loadDashboardStats();
            window.updateLastUpdatedTime();
        }, 500);
    });

});
</script>
@endpush
