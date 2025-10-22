@extends('layouts.vertical', ['page_title' => 'Requisition Approval Management'])

@section('css')
@vite([
    'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
    'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'
])
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />
<!-- Load jQuery in head section -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<!-- Load SweetAlert2 in head section -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.swal-wide {
    width: 90% !important;
    max-width: 1200px !important;
}
.swal-wide .swal2-html-container {
    max-height: 70vh;
    overflow-y: auto;
}

/* Custom allocation popup styles */
.allocation-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    display: flex;
    justify-content: center;
    align-items: center;
}

.allocation-popup {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    max-width: 800px;
    width: 90%;
    max-height: 80vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.allocation-popup-header {
    padding: 20px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f8f9fa;
}

.allocation-popup-header .btn-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6c757d;
}

.allocation-popup-header .btn-close:hover {
    color: #000;
}

.allocation-popup-body {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
}

.allocation-popup-footer {
    padding: 20px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    background-color: #f8f9fa;
}
</style>
@endsection

@section('content')
<div class="row mt-4">
    <!-- Pending Requisitions Card -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded bg-soft-warning">
                            <span class="avatar-title rounded">
                                <i class="ri-time-line font-20 text-warning"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-1">Pending</p>
                        <h4 class="mb-0 pending-approvals-count">0</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approved Card -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded bg-soft-success">
                            <span class="avatar-title rounded">
                                <i class="ri-check-line font-20 text-success"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-1">Approved</p>
                        <h4 class="mb-0 approved-count">0</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejected Card -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded bg-soft-danger">
                            <span class="avatar-title rounded">
                                <i class="ri-close-line font-20 text-danger"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-1">Rejected</p>
                        <h4 class="mb-0 rejected-count">0</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Projects Card -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded bg-soft-info">
                            <span class="avatar-title rounded">
                                <i class="ri-task-line font-20 text-info"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-1">Active Projects</p>
                        <h4 class="mb-0 high-priority-count">0</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Requisition Approval Queue Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ri-file-list-line me-2"></i>
                            Requisition Approval Queue
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshRequisitions()">
                                <i class="ri-refresh-line me-1"></i>Refresh
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="bulkApproveRequisitions()">
                                <i class="ri-checkbox-multiple-line me-1"></i>Bulk Approve
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="exportRequisitions()">
                                <i class="ri-download-line me-1"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="requisitionApprovalTable">
                <thead class="table-light">
                    <tr>
                        <th width="50">
                            <input type="checkbox" class="form-check-input" id="selectAllRequisitions">
                        </th>
                        <th>Req Number</th>
                        <th>Project</th>
                        <th>Requested By</th>
                        <th>Department</th>
                        <th>Priority</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th width="200">Actions</th>
                    </tr>
                </thead>
                            <tbody id="requisitionApprovalTableBody">
                                <!-- Data will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <small id="paginationInfo">Loading...</small>
                        </div>
                        <nav aria-label="Requisition Approval pagination">
                            <ul class="pagination pagination-sm mb-0" id="paginationNav">
                                <!-- Pagination will be generated dynamically -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Load statistics
        loadRequisitionStatistics();
        
        // Load requisitions
        loadRequisitions();
        
        // Initialize checkbox functionality
        $('#selectAllRequisitions').on('change', function() {
            $('.requisition-checkbox').prop('checked', this.checked);
        });
        
        $('.requisition-checkbox').on('change', function() {
            if (!this.checked) {
                $('#selectAllRequisitions').prop('checked', false);
            }
        });
    });

    function loadRequisitionStatistics() {
        // Statistics will be loaded with the requisitions data
        // This function is kept for compatibility but statistics are loaded in loadRequisitions()
    }

    let currentPage = 1;
    let totalPages = 1;

    function loadRequisitions(page = 1) {
        currentPage = page;
        
        $.ajax({
            url: '{{ route("management.requisitions.all") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                page: page,
                per_page: 10
            },
            success: function(response) {
                if (response.success && response.data) {
                    // Store current requisitions globally for debug access
                    window.currentRequisitions = response.data;
                    
                    populateRequisitionTable(response.data);
                    
                    // Update pagination
                    if (response.pagination) {
                        updatePagination(response.pagination);
                    }
                } else {
                    console.error('No data received:', response);
                }
                
                // Update statistics if available
                if (response.stats) {
                    $('.pending-approvals-count').text(response.stats.pending_approvals || 0);
                    $('.high-priority-count').text(response.stats.high_priority || 0);
                    $('.approved-count').text(response.stats.approved || 0);
                    $('.rejected-count').text(response.stats.rejected || 0);
                }
            },
            error: function(xhr) {
                console.error('Error loading requisitions:', xhr);
            }
        });
    }

    function updatePagination(pagination) {
        const paginationNav = $('#paginationNav');
        const paginationInfo = $('#paginationInfo');
        
        // Update pagination info
        paginationInfo.text(`Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total} entries`);
        
        // Clear existing pagination
        paginationNav.empty();
        
        // Previous button
        const prevDisabled = pagination.current_page <= 1 ? 'disabled' : '';
        paginationNav.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link" href="#" onclick="loadRequisitions(${pagination.current_page - 1}); return false;">Previous</a>
            </li>
        `);
        
        // Page numbers
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === pagination.current_page ? 'active' : '';
            paginationNav.append(`
                <li class="page-item ${activeClass}">
                    <a class="page-link" href="#" onclick="loadRequisitions(${i}); return false;">${i}</a>
                </li>
            `);
        }
        
        // Next button
        const nextDisabled = pagination.current_page >= pagination.last_page ? 'disabled' : '';
        paginationNav.append(`
            <li class="page-item ${nextDisabled}">
                <a class="page-link" href="#" onclick="loadRequisitions(${pagination.current_page + 1}); return false;">Next</a>
            </li>
        `);
    }

    function populateRequisitionTable(requisitions) {
        const tbody = $('#requisitionApprovalTableBody');
        tbody.empty();
        
            if (requisitions.length === 0) {
                tbody.append(`
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <i class="ri-inbox-line" style="font-size: 3rem; color: #6c757d;"></i>
                                <div class="mt-3" style="font-size: 1.1rem; font-weight: 500;">
                                    No requisitions pending approval
                                </div>
                                <div class="mt-2" style="font-size: 0.9rem; color: #868e96;">
                                    All requisitions with "New" status will appear here
                                </div>
                            </div>
                        </td>
                    </tr>
                `);
                return;
            }
        
                requisitions.forEach(function(requisition) {
                    // Get requestor name properly
                    let requestorName = 'Unknown';
                    if (requisition.requestor) {
                        // API returns personal_info (with underscore), not personalInfo (camelCase)
                        if (requisition.requestor.personal_info) {
                            requestorName = `${requisition.requestor.personal_info.first_name || ''} ${requisition.requestor.personal_info.last_name || ''}`.trim();
                            if (!requestorName) {
                                requestorName = requisition.requestor.staff_id || 'Unknown';
                            }
                        } else {
                            requestorName = requisition.requestor.staff_id || 'Unknown';
                        }
                    }
                    

                    const row = `
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input requisition-checkbox" value="${requisition.id}">
                            </td>
                            <td><strong>${requisition.requisition_number}</strong></td>
                            <td>${requisition.title}</td>
                            <td>${requestorName}</td>
                            <td>${requisition.department || 'Not Assigned'}</td>
                            <td><span class="badge bg-${getPriorityBadgeClass(requisition.priority)}">${requisition.priority}</span></td>
                            <td>${formatDate(requisition.created_at)}</td>
                            <td>
                                <span class="badge bg-${getStatusBadgeClass(requisition.status)}">${requisition.status}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewRequisitionDetails(${requisition.id})" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="editRequisition(${requisition.id})" title="Edit Requisition">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="showApprovalModal(${requisition.id})" title="Approve">
                                        <i class="ri-check-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="showRejectionModal(${requisition.id})" title="Reject">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
    }

    function getPriorityBadgeClass(priority) {
        const classes = {
            'low': 'success',
            'medium': 'info', 
            'high': 'warning',
            'urgent': 'danger'
        };
        return classes[priority] || 'secondary';
    }

    function getStatusBadgeClass(status) {
        const classes = {
            'draft': 'secondary',
            'created': 'primary',
            'pending': 'warning',
            'approved': 'success',
            'rejected': 'danger',
            'issued': 'info'
        };
        return classes[status] || 'secondary';
    }

    function showTeamMembersDebug(requisitionId, requisitionNumber) {
        // Find the requisition data from the current loaded data
        const requisitions = window.currentRequisitions || [];
        const requisition = requisitions.find(req => req.id === requisitionId);
        
        if (!requisition) {
            Swal.fire('Error', 'Requisition data not found', 'error');
            return;
        }

        let teamMembersHtml = '';
        
        if (requisition.team_members && requisition.team_members.length > 0) {
            teamMembersHtml = `
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Email</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${requisition.team_members.map(member => `
                                <tr>
                                    <td>${member.id}</td>
                                    <td><strong>${member.full_name || 'Unknown'}</strong></td>
                                    <td>${member.position || 'No Position'}</td>
                                    <td>${member.email || 'No Email'}</td>
                                    <td>${member.phone || 'No Phone'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        } else {
            teamMembersHtml = '<p class="text-muted">No team members found for this requisition</p>';
        }

        Swal.fire({
            title: `Team Members - ${requisitionNumber}`,
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <h6>Team Leader Information:</h6>
                        <p><strong>ID:</strong> ${requisition.team_leader_id || 'Not Assigned'}</p>
                        <p><strong>Name:</strong> ${requisition.teamLeader ? requisition.teamLeader.full_name : 'Not Found'}</p>
                        <p><strong>Email:</strong> ${requisition.teamLeader ? requisition.teamLeader.email : 'Not Found'}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Team Members (${requisition.team_members ? requisition.team_members.length : 0} found):</h6>
                        ${teamMembersHtml}
                    </div>
                    
                    <div class="alert alert-info">
                        <h6>Debug Information:</h6>
                        <small>
                            <strong>Team Leader ID:</strong> ${requisition.team_leader_id || 'null'}<br>
                            <strong>Team Members Count:</strong> ${requisition.team_members ? requisition.team_members.length : 0}<br>
                            <strong>Team Members Array:</strong> ${JSON.stringify(requisition.team_members, null, 2)}
                        </small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6>Database Check:</h6>
                        <button class="btn btn-sm btn-primary" onclick="checkDatabaseTeamMembers(${requisition.team_leader_id})">
                            <i class="fas fa-database me-1"></i>Check Database for Team Members
                        </button>
                        <button class="btn btn-sm btn-info ms-2" onclick="debugTeamLeaderIssue(${requisition.id})">
                            <i class="fas fa-bug me-1"></i>Debug Team Leader Issue
                        </button>
                    </div>
                </div>
            `,
            width: '80%',
            showCloseButton: true,
            confirmButtonText: 'Close',
            customClass: {
                popup: 'swal-wide'
            }
        });
    }

    function checkDatabaseTeamMembers(teamLeaderId) {
        if (!teamLeaderId) {
            Swal.fire('Error', 'No team leader ID provided', 'error');
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Checking Database...',
            text: 'Fetching team members from database',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Make AJAX call to check database
        $.ajax({
            url: '{{ url("company/management/requisitions/check-team-members") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                team_leader_id: teamLeaderId
            },
            success: function(response) {
                Swal.close();
                
                let dbResultsHtml = '';
                
                if (response.success) {
                    if (response.team_members && response.team_members.length > 0) {
                        dbResultsHtml = `
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Email</th>
                                            <th>Company ID</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${response.team_members.map(member => `
                                            <tr>
                                                <td>${member.id}</td>
                                                <td><strong>${member.full_name || 'Unknown'}</strong></td>
                                                <td>${member.position || 'No Position'}</td>
                                                <td>${member.email || 'No Email'}</td>
                                                <td>${member.company_id || 'N/A'}</td>
                                                <td><span class="badge bg-${member.status === 'active' ? 'success' : 'warning'}">${member.status || 'unknown'}</span></td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        `;
                    } else {
                        dbResultsHtml = '<p class="text-muted">No team members found in database for this team leader</p>';
                    }
                } else {
                    dbResultsHtml = '<p class="text-danger">Error fetching team members from database</p>';
                }

                Swal.fire({
                    title: 'Database Team Members',
                    html: `
                        <div class="text-start">
                            <div class="mb-3">
                                <h6>Team Leader ID: ${teamLeaderId}</h6>
                                <p><strong>Results from Database:</strong></p>
                                ${dbResultsHtml}
                            </div>
                            
                            <div class="alert alert-info">
                                <h6>Database Query Results:</h6>
                                <small>${JSON.stringify(response, null, 2)}</small>
                            </div>
                        </div>
                    `,
                    width: '90%',
                    showCloseButton: true,
                    confirmButtonText: 'Close',
                    customClass: {
                        popup: 'swal-wide'
                    }
                });
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire('Error', 'Failed to check database: ' + xhr.responseText, 'error');
            }
        });
    }

    function debugTeamLeaderIssue(requisitionId) {
        // Show loading
        Swal.fire({
            title: 'Debugging Team Leader Issue...',
            text: 'Investigating why team leader exists but no team members found',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Make AJAX call to debug the issue
        $.ajax({
            url: '{{ url("company/management/requisitions") }}/' + requisitionId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.close();
                
                if (response.success) {
                    const req = response.data;
                    
                    let debugHtml = `
                        <div class="text-start">
                            <h6>🔍 Team Leader Debug Analysis</h6>
                            
                            <div class="alert alert-info">
                                <h6>Requisition Data:</h6>
                                <p><strong>Requisition ID:</strong> ${req.id}</p>
                                <p><strong>Team Leader ID:</strong> ${req.team_leader_id || 'Not Set'}</p>
                                <p><strong>Company ID:</strong> ${req.company_id || 'Not Set'}</p>
                            </div>
                            
                            <div class="alert alert-warning">
                                <h6>Team Leader Object Analysis:</h6>
                                <pre>${JSON.stringify(req.teamLeader, null, 2)}</pre>
                            </div>
                            
                            <div class="alert alert-danger">
                                <h6>Team Members Analysis:</h6>
                                <p><strong>Team Members Array:</strong> ${req.team_members ? 'EXISTS' : 'UNDEFINED'}</p>
                                <p><strong>Team Members Count:</strong> ${req.team_members ? req.team_members.length : 'UNDEFINED'}</p>
                                <pre>${JSON.stringify(req.team_members, null, 2)}</pre>
                            </div>
                            
                            <div class="alert alert-success">
                                <h6>Expected Behavior:</h6>
                                <ul>
                                    <li>If team_leader_id = ${req.team_leader_id}, then teamLeader object should have data</li>
                                    <li>If teamLeader exists, team_members should be an array (even if empty)</li>
                                    <li>Team leader should be included in team_members array</li>
                                    <li>If no team pairing exists, should fallback to all company team members</li>
                                </ul>
                            </div>
                            
                            <div class="alert alert-secondary">
                                <h6>Raw Response Data:</h6>
                                <pre>${JSON.stringify(response, null, 2)}</pre>
                            </div>
                        </div>
                    `;

                    Swal.fire({
                        title: 'Team Leader Debug Results',
                        html: debugHtml,
                        width: '90%',
                        showCloseButton: true,
                        confirmButtonText: 'Close',
                        customClass: {
                            popup: 'swal-wide'
                        }
                    });
                } else {
                    Swal.fire('Error', 'Failed to load requisition data: ' + response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire('Error', 'Failed to debug team leader issue: ' + xhr.responseText, 'error');
            }
        });
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            console.error('Invalid date:', dateString);
            return 'Invalid Date';
        }
        return date.toLocaleDateString();
    }

    function formatAmount(amount) {
        return parseFloat(amount).toFixed(2);
    }

    function showPendingRequisitions() {
        // Scroll to the approval table
        document.getElementById('requisitionApprovalTable').scrollIntoView({ behavior: 'smooth' });
    }

    function showProjectRequisitions() {
        Swal.fire({
            title: 'Project Requisitions',
            html: `
                <div class="text-start">
                    <h6>Active Projects with Pending Requisitions</h6>
                    <ul class="list-unstyled">
                        <li><strong>Website Redesign:</strong> 3 pending requisitions</li>
                        <li><strong>Mobile App Development:</strong> 2 pending requisitions</li>
                        <li><strong>Marketing Campaign:</strong> 1 pending requisition</li>
                        <li><strong>Database Migration:</strong> 2 pending requisitions</li>
                    </ul>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }

    function showAnalytics() {
        Swal.fire({
            title: 'Approval Analytics',
            html: `
                <div class="text-start">
                    <h6>Project Management Approval Performance</h6>
                    <ul class="list-unstyled">
                        <li><strong>Average Processing Time:</strong> 1.8 days</li>
                        <li><strong>Approval Rate:</strong> 95.2%</li>
                        <li><strong>Rejection Rate:</strong> 4.8%</li>
                        <li><strong>Pending Items:</strong> 47</li>
                        <li><strong>Active Projects:</strong> 12</li>
                    </ul>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }

    function refreshRequisitions() {
        Swal.fire({
            title: 'Refreshing...',
            text: 'Loading latest requisition data...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        loadRequisitions();
        
        setTimeout(() => {
            Swal.close();
            Swal.fire('Success!', 'Requisition data refreshed successfully.', 'success');
        }, 1000);
    }

    function bulkApproveRequisitions() {
        const selectedRequisitions = $('.requisition-checkbox:checked').length;
        
        if (selectedRequisitions === 0) {
            Swal.fire({
                title: 'No Selection',
                text: 'Please select at least one requisition to approve.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        Swal.fire({
            title: 'Bulk Approval',
            text: `Are you sure you want to approve ${selectedRequisitions} requisition(s)?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve All',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const requisitionIds = $('.requisition-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                
                $.ajax({
                    url: '{{ route("management.requisitions.bulk-approve") }}',
                    method: 'POST',
                    data: {
                        requisition_ids: requisitionIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success');
                            loadRequisitions();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to approve requisitions', 'error');
                    }
                });
            }
        });
    }

    function exportRequisitions() {
        Swal.fire({
            title: 'Export Requisitions',
            text: 'This will export all requisition data to CSV format.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Export',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Exporting...',
                    text: 'Please wait while we prepare your export.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Create CSV content
                $.ajax({
                    url: '{{ route("management.requisitions.all") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        export: true,
                        per_page: 1000 // Get all data for export
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            // Create CSV content
                            let csvContent = "Requisition Number,Title,Requested By,Department,Team Leader,Team Members Count,Team Members,Amount,Priority,Status,Created Date\n";
                            
                            response.data.forEach(function(requisition) {
                                const requestorName = requisition.requestor ? requisition.requestor.name : 'Unknown';
                                const department = requisition.department || 'Not Assigned';
                                const teamLeader = requisition.teamLeader ? requisition.teamLeader.full_name : 'Not Assigned';
                                const teamMembersCount = requisition.team_members ? requisition.team_members.length : 0;
                                const teamMembers = requisition.team_members ? requisition.team_members.map(member => member.full_name).join('; ') : 'No Members';
                                const amount = formatAmount(requisition.total_amount || 0);
                                const createdDate = formatDate(requisition.created_at);
                                
                                csvContent += `"${requisition.requisition_number}","${requisition.title}","${requestorName}","${department}","${teamLeader}","${teamMembersCount}","${teamMembers}","GHS ${amount}","${requisition.priority}","${requisition.status}","${createdDate}"\n`;
                            });
                            
                            // Download CSV
                            const blob = new Blob([csvContent], { type: 'text/csv' });
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `requisitions_export_${new Date().toISOString().split('T')[0]}.csv`;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            window.URL.revokeObjectURL(url);
                            
                Swal.fire('Success!', 'Requisition data exported successfully.', 'success');
                        } else {
                            Swal.fire('Error!', 'Failed to export requisitions', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to export requisitions', 'error');
                    }
                });
            }
        });
    }

    function viewReqDetails(reqId) {
        Swal.fire({
            title: 'Requisition Details',
            html: `
                <div class="text-start">
                    <h6>REQ-2024-00${reqId}</h6>
                    <p><strong>Project:</strong> Sample Project</p>
                    <p><strong>Requested By:</strong> John Smith</p>
                    <p><strong>Department:</strong> IT</p>
                    <p><strong>Amount:</strong> GHS 2,500.00</p>
                    <p><strong>Status:</strong> Pending Approval</p>
                    <p><strong>Created:</strong> 2024-01-15</p>
                    <p><strong>Description:</strong> Software licenses and development tools</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }

    function approveRequisition(id) {
        Swal.fire({
            title: 'Approve Requisition',
            text: 'Are you sure you want to approve this requisition?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('company/management/requisitions') }}/${id}/approve`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            let successMessage = response.message;
                            
                            
                            Swal.fire('Success!', successMessage, 'success');
                            loadRequisitions();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to approve requisition', 'error');
                    }
                });
            }
        });
    }

    function rejectRequisition(id) {
        Swal.fire({
            title: 'Reject Requisition',
            input: 'textarea',
            inputLabel: 'Reason for rejection:',
            inputPlaceholder: 'Please provide a reason for rejection...',
            showCancelButton: true,
            confirmButtonText: 'Reject',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('company/management/requisitions') }}/${id}/reject`,
                    method: 'POST',
                    data: {
                        reason: result.value,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success');
                            loadRequisitions();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to reject requisition', 'error');
                    }
                });
            }
        });
    }

        function viewRequisitionDetails(id) {
            console.log('=== VIEW REQUISITION DETAILS DEBUG START ===');
            console.log('Function called with ID:', id);
            console.log('ID type:', typeof id);
            
            const ajaxUrl = `{{ url('company/management/requisitions') }}/${id}`;
            const ajaxData = {
                _token: '{{ csrf_token() }}'
            };
            
            console.log('AJAX Request Details:');
            console.log('URL:', ajaxUrl);
            console.log('Method: POST');
            console.log('Data being sent:', ajaxData);
            console.log('CSRF Token:', '{{ csrf_token() }}');
            
            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: ajaxData,
                beforeSend: function(xhr) {
                    console.log('=== VIEW DETAILS AJAX BEFORE SEND ===');
                    console.log('XHR object:', xhr);
                },
                success: function(response) {
                    console.log('=== VIEW DETAILS AJAX SUCCESS ===');
                    console.log('Response received:', response);
                    if (response.success) {
                        const req = response.data;
                        
                        // COMPREHENSIVE DEBUG: Log the entire response
                        console.log('=== VIEW MODAL DEBUG ===');
                        console.log('Full API Response:', response);
                        console.log('Requisition Data:', req);
                        console.log('Requestor Object:', req.requestor);
                        console.log('Requestor PersonalInfo:', req.requestor ? req.requestor.personal_info : 'No requestor');
                        console.log('Requestor Email:', req.requestor ? req.requestor.email : 'No requestor');
                        console.log('Requestor Staff ID:', req.requestor ? req.requestor.staff_id : 'No requestor');
                        console.log('Requestor User:', req.requestor ? req.requestor.user : 'No requestor user');
                        console.log('Project Manager Object:', req.projectManager);
                        console.log('Team Leader Object:', req.teamLeader);
                        console.log('Team Leader ID:', req.team_leader_id);
                        console.log('Project Manager ID:', req.project_manager_id);
                        console.log('========================');
                    
                    // Build items table
                    let itemsHtml = '';
                    if (req.items && req.items.length > 0) {
                        itemsHtml = `
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;
                        req.items.forEach(function(item) {
                            const total = (item.quantity || 0) * (item.unit_price || 0);
                            itemsHtml += `
                                <tr>
                                    <td>${item.item_name || 'N/A'}</td>
                                    <td>${item.description || 'N/A'}</td>
                                    <td>${item.quantity || 0}</td>
                                    <td>GHS ${formatAmount(item.unit_price || 0)}</td>
                                    <td>GHS ${formatAmount(total)}</td>
                                </tr>
                            `;
                        });
                        itemsHtml += `
                                    </tbody>
                                </table>
                            </div>
                        `;
                    } else {
                        itemsHtml = '<p class="text-muted">No items found</p>';
                    }
                    
                    // Build team information
                    // Use the same helper functions that work in edit modal
                    let requestorName = getRequestorName(req);
                    let requestorEmail = req.requestor ? req.requestor.email || 'N/A' : 'N/A';
                    
                    let projectManagerName = getProjectManagerName(req);
                    let projectManagerEmail = req.projectManager ? req.projectManager.email || 'N/A' : 'N/A';
                    
                    // Use the same helper function that works in edit modal
                    let teamLeaderName = getTeamLeaderName(req);
                    let teamLeaderEmail = req.teamLeader ? req.teamLeader.email || 'N/A' : 'N/A';
                    
                    // Debug if relationship failed
                    if (req.team_leader_id && !req.teamLeader) {
                        console.log('View Modal - Team Leader Debug:', {
                            team_leader_id: req.team_leader_id,
                            teamLeader_object: req.teamLeader,
                            requisition_id: req.id,
                            raw_data: req
                        });
                    }
                    
                    let teamHtml = `
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Requested By</h6>
                                <p><strong>Name:</strong> ${requestorName}</p>
                                <p><strong>Email:</strong> ${requestorEmail}</p>
                            </div>
                            <div class="col-md-4">
                                <h6>Project Manager</h6>
                                <p><strong>Name:</strong> ${projectManagerName}</p>
                                <p><strong>Email:</strong> ${projectManagerEmail}</p>
                            </div>
                            <div class="col-md-4">
                                <h6>Team Leader</h6>
                                <p><strong>Name:</strong> ${teamLeaderName}</p>
                                <p><strong>Email:</strong> ${teamLeaderEmail}</p>
                            </div>
                        </div>
                    `;
                    
                    // Debug team members
                    console.log('Team members debug:', {
                        team_members: req.team_members,
                        team_leader_id: req.team_leader_id,
                        team_leader: req.teamLeader,
                        team_members_length: req.team_members ? req.team_members.length : 'undefined',
                        team_member_stats: req.team_member_stats,
                        created_at: req.created_at
                    });
                    
                    // Build team members display
                    let teamMembersHtml = '';
                    if (req.team_members && req.team_members.length > 0) {
                        teamMembersHtml = `
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-users me-2"></i>Team Members
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Team Member</th>
                                                    <th>Position</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                        `;
                        req.team_members.forEach(function(member) {
                            teamMembersHtml += `
                                <tr>
                                    <td><strong>${member.full_name || 'N/A'}</strong></td>
                                    <td>${member.position || 'N/A'}</td>
                                    <td>${member.email || 'N/A'}</td>
                                    <td>${member.phone || 'N/A'}</td>
                                </tr>
                            `;
                        });
                        teamMembersHtml += `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        teamMembersHtml = '<p class="text-muted">No team members assigned</p>';
                    }
                    
                    // Build team history with team member statistics
                    let historyHtml = '';
                    if (req.team_members && req.team_members.length > 0) {
                        historyHtml = `
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Team Member</th>
                                            <th>Position</th>
                                            <th>Total Requisitions</th>
                                            <th>Pending</th>
                                            <th>Approved</th>
                                            <th>Rejected</th>
                                            <th>Last Activity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;
                        
                        // Show team member statistics
                        if (req.team_member_stats && req.team_member_stats.length > 0) {
                            req.team_member_stats.forEach(function(memberData) {
                                const member = memberData.member;
                                const stats = memberData.stats;
                                
                                historyHtml += `
                                    <tr>
                                        <td><strong>${member.full_name || 'N/A'}</strong></td>
                                        <td>${member.position || 'N/A'}</td>
                                        <td><span class="badge bg-info">${stats.total}</span></td>
                                        <td><span class="badge bg-warning">${stats.pending}</span></td>
                                        <td><span class="badge bg-success">${stats.approved}</span></td>
                                        <td><span class="badge bg-danger">${stats.rejected}</span></td>
                                        <td>${stats.last_activity}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            // Fallback to basic team member info
                            req.team_members.forEach(function(member) {
                                historyHtml += `
                                    <tr>
                                        <td><strong>${member.full_name || 'N/A'}</strong></td>
                                        <td>${member.position || 'N/A'}</td>
                                        <td><span class="badge bg-secondary">No Data</span></td>
                                        <td><span class="badge bg-secondary">No Data</span></td>
                                        <td><span class="badge bg-secondary">No Data</span></td>
                                        <td><span class="badge bg-secondary">No Data</span></td>
                                        <td>No Data</td>
                                    </tr>
                                `;
                            });
                        }
                        
                        historyHtml += `
                                    </tbody>
                                </table>
                            </div>
                        `;
                    } else {
                        historyHtml = `
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>No Team Members Assigned</h6>
                                <p class="mb-0">This requisition has no team members assigned. Team history will be available once team members are assigned.</p>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Requisition Activity</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                    <th>User</th>
                                                    <th>Comments</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>${formatDate(req.created_at)}</td>
                                                    <td><span class="badge bg-primary">New</span></td>
                                                    <td><strong>${requestorName}</strong> <small class="text-muted">(Requestor)</small></td>
                                                    <td>Requisition created and submitted for approval</td>
                                                </tr>
                                                ${req.team_leader_id ? `
                                                <tr>
                                                    <td>${formatDate(req.created_at)}</td>
                                                    <td><span class="badge bg-info">Team Leader Assigned</span></td>
                                                    <td><strong>${req.teamLeader ? req.teamLeader.full_name : 'Unknown'}</strong> <small class="text-muted">(Team Leader)</small></td>
                                                    <td>Team leader assigned but no team members configured</td>
                                                </tr>
                                                ` : ''}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    
                    Swal.fire({
                        title: 'Requisition Details',
                        html: `
                            <div class="text-start">
                                <!-- Basic Information -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6>Requisition Information</h6>
                                        <p><strong>Number:</strong> ${req.requisition_number}</p>
                                        <p><strong>Title:</strong> ${req.title}</p>
                                        <p><strong>Department:</strong> ${req.department || 'Not Assigned'}</p>
                                        <p><strong>Priority:</strong> <span class="badge bg-${getPriorityBadgeClass(req.priority)}">${req.priority}</span></p>
                                        <p><strong>Status:</strong> <span class="badge bg-warning">${req.status}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Dates & Notes</h6>
                                        <p><strong>Created:</strong> ${formatDate(req.created_at)}</p>
                                        <p><strong>Required Date:</strong> ${req.required_date ? formatDate(req.required_date) : 'Not specified'}</p>
                                        <p><strong>Notes:</strong> ${req.notes || 'None'}</p>
                                    </div>
                                </div>
                                
                                <!-- Team Information -->
                                <div class="mb-4">
                                    <h6>Team Information</h6>
                                    ${teamHtml}
                                </div>
                                
                                <!-- Items -->
                                <div class="mb-4">
                                    <h6>Requisition Items</h6>
                                    ${itemsHtml}
                                    
                                </div>
                                
                                <!-- Team Members -->
                                <div class="mb-4">
                                    ${teamMembersHtml}
                                </div>
                                
                                <!-- Team History / Activity -->
                                <div class="mb-4">
                                    <h6>${req.team_members && req.team_members.length > 0 ? 'Team Member Statistics' : 'Requisition Activity'}</h6>
                                    ${historyHtml}
                                </div>
                            </div>
                        `,
                        width: '80%',
                        showCloseButton: true,
                        showCancelButton: false,
                        confirmButtonText: 'Close',
                        customClass: {
                            popup: 'swal-wide'
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('=== VIEW DETAILS AJAX ERROR ===');
                console.log('XHR object:', xhr);
                console.log('Status:', status);
                console.log('Error:', error);
                console.log('Response Status:', xhr.status);
                console.log('Response Status Text:', xhr.statusText);
                console.log('Response Text:', xhr.responseText);
                
                try {
                    const responseJson = JSON.parse(xhr.responseText);
                    console.log('Parsed Response JSON:', responseJson);
                } catch (e) {
                    console.log('Could not parse response as JSON:', e);
                }
                
                let errorMessage = 'Failed to load requisition details';
                if (xhr.status === 404) {
                    errorMessage = 'Requisition not found (404 error)';
                    console.log('404 Error - Requisition not found');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                console.log('Final error message:', errorMessage);
                Swal.fire('Error!', errorMessage, 'error');
            },
            complete: function(xhr, status) {
                console.log('=== VIEW DETAILS AJAX COMPLETE ===');
                console.log('Final status:', status);
                console.log('Final XHR status:', xhr.status);
                console.log('=== VIEW REQUISITION DETAILS DEBUG END ===');
            }
            });
        }

        // New approval modal with team member distribution
        function showApprovalModal(id) {
            console.log('=== SHOW APPROVAL MODAL DEBUG START ===');
            console.log('Function called with ID:', id);
            console.log('ID type:', typeof id);
            
            // Clear previous allocations when opening new approval modal
            window.itemAllocations = {};
            
            const ajaxUrl = `{{ url('company/management/requisitions') }}/${id}`;
            const ajaxData = {
                _token: '{{ csrf_token() }}'
            };
            
            console.log('APPROVAL MODAL AJAX Request Details:');
            console.log('URL:', ajaxUrl);
            console.log('Method: POST');
            console.log('Data being sent:', ajaxData);
            
            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: ajaxData,
                beforeSend: function(xhr) {
                    console.log('=== APPROVAL MODAL AJAX BEFORE SEND ===');
                    console.log('XHR object:', xhr);
                },
                success: function(response) {
                    console.log('=== APPROVAL MODAL AJAX SUCCESS ===');
                    console.log('Response received:', response);
                    
                    if (response.success) {
                        console.log('Building approval modal with data:', response.data);
                        const req = response.data;
                        
                        // Build items table with individual item allocation
                        let itemsHtml = '';
                        if (req.items && req.items.length > 0) {
                            itemsHtml = `
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Item</th>
                                                <th>Description</th>
                                                <th>Total Qty</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                                <th>Allocate to Team Members</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                            `;
                            req.items.forEach(function(item, itemIndex) {
                                const total = (item.quantity || 0) * (item.unit_price || 0);
                                
                                // Build allocation button for this item
                                let itemAllocationHtml = '';
                                console.log('Building allocation for item:', item.item_name, 'team_members:', req.team_members);
                                if (req.team_members && req.team_members.length > 0) {
                                    itemAllocationHtml = `
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary allocate-item-btn" 
                                                data-item-index="${itemIndex}"
                                                data-item-name="${item.item_name || 'N/A'}"
                                                data-item-qty="${item.quantity || 0}"
                                                data-item-description="${item.description || 'N/A'}"
                                                title="Allocate to team members">
                                            <i class="fas fa-users me-1"></i>
                                            Allocate (${item.quantity || 0} available)
                                        </button>
                                        <div class="mt-1">
                                            <small class="text-muted" id="allocation_summary_${itemIndex}">
                                                <span class="badge bg-warning">0/${item.quantity || 0} allocated</span>
                                            </small>
                                        </div>`;
                                } else {
                                    itemAllocationHtml = '<small class="text-muted">No team members available</small>';
                                }
                                
                                itemsHtml += `
                                    <tr>
                                        <td><strong>${item.item_name || 'N/A'}</strong></td>
                                        <td>${item.description || 'N/A'}</td>
                                        <td>${item.quantity || 0} ${item.unit || ''}</td>
                                        <td>GHS ${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                                        <td><strong>GHS ${total.toFixed(2)}</strong></td>
                                        <td>${itemAllocationHtml}</td>
                                    </tr>
                                `;
                            });
                            itemsHtml += `
                                        </tbody>
                                    </table>
                                </div>
                            `;
                        } else {
                            itemsHtml = '<p class="text-muted">No items found for this requisition.</p>';
                        }
                        
                        // Show team members info
                        let teamMembersHtml = '';
                        if (req.team_members && req.team_members.length > 0) {
                            teamMembersHtml = `
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-users me-2"></i>Team Members
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Team Member</th>
                                                        <th>Position</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                            `;
                            req.team_members.forEach(function(member) {
                                teamMembersHtml += `
                                    <tr>
                                        <td><strong>${member.full_name || 'N/A'}</strong></td>
                                        <td>${member.position || 'N/A'}</td>
                                        <td>${member.email || 'N/A'}</td>
                                        <td>${member.phone || 'N/A'}</td>
                                    </tr>
                                `;
                            });
                            teamMembersHtml += `
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="alert alert-info mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Note:</strong> Allocate specific quantities of each item to team members in the items table above.
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            teamMembersHtml = `
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <strong>No Team Members:</strong> This requisition doesn't have any team members assigned.
                                </div>
                            `;
                        }
                        
                        // Display the approval modal
                        Swal.fire({
                            title: `Approve Requisition: ${req.requisition_number}`,
                            html: `
                                <div class="text-start">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Title:</strong> ${req.title}<br>
                                            <strong>Requested by:</strong> ${req.requestor?.personal_info?.first_name || 'N/A'} ${req.requestor?.personal_info?.last_name || ''}<br>
                                            <strong>Department:</strong> ${req.department || 'N/A'}<br>
                                            <strong>Priority:</strong> <span class="badge bg-${getPriorityBadgeClass(req.priority)}">${req.priority}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Total Amount:</strong> GHS ${parseFloat(req.total_amount || 0).toFixed(2)}<br>
                                            <strong>Date Created:</strong> ${new Date(req.created_at).toLocaleDateString()}<br>
                                            <strong>Project Manager:</strong> ${req.projectManager?.name || 'N/A'}<br>
                                            <strong>Team Leader:</strong> ${req.teamLeader?.full_name || 'N/A'}
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="alert alert-info mb-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Allocation Required:</strong> You must allocate ALL item quantities to team members before approval is allowed.
                                        </div>
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-boxes me-2"></i>Items to Approve
                                        </h6>
                                        ${itemsHtml}
                                    </div>
                                    
                                    ${teamMembersHtml}
                                    
                                    <div class="mb-3">
                                        <label for="approvalNotes" class="form-label">Approval Notes (Optional)</label>
                                        <textarea class="form-control" id="approvalNotes" rows="3" placeholder="Add any notes for this approval..."></textarea>
                                    </div>
                                </div>
                            `,
                            width: '80%',
                            showCancelButton: true,
                            confirmButtonText: 'Approve Requisition',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#28a745',
                            preConfirm: () => {
                                console.log('=== APPROVAL VALIDATION START ===');
                                const notes = document.getElementById('approvalNotes').value;
                                const allocations = window.itemAllocations || {};
                                
                                console.log('Current allocations:', allocations);
                                console.log('Items to validate:', req.items);
                                
                                // Validate that all items are fully allocated
                                let validationErrors = [];
                                
                                if (req.items && req.items.length > 0) {
                                    req.items.forEach((item, itemIndex) => {
                                        const itemQty = parseInt(item.quantity) || 0;
                                        const itemAllocations = allocations[itemIndex] || [];
                                        
                                        // Calculate total allocated quantity for this item
                                        const totalAllocated = itemAllocations.reduce((sum, allocation) => {
                                            return sum + (parseInt(allocation.quantity) || 0);
                                        }, 0);
                                        
                                        console.log(`Item ${itemIndex} (${item.item_name}):`, {
                                            required: itemQty,
                                            allocated: totalAllocated,
                                            allocations: itemAllocations
                                        });
                                        
                                        if (totalAllocated < itemQty) {
                                            validationErrors.push({
                                                item: item.item_name || `Item ${itemIndex + 1}`,
                                                required: itemQty,
                                                allocated: totalAllocated,
                                                missing: itemQty - totalAllocated
                                            });
                                        } else if (totalAllocated > itemQty) {
                                            validationErrors.push({
                                                item: item.item_name || `Item ${itemIndex + 1}`,
                                                required: itemQty,
                                                allocated: totalAllocated,
                                                excess: totalAllocated - itemQty
                                            });
                                        }
                                    });
                                }
                                
                                console.log('Validation errors:', validationErrors);
                                
                                // If there are validation errors, show them and prevent approval
                                if (validationErrors.length > 0) {
                                    let errorMessage = '<div class="text-start"><h6 class="text-danger mb-3">❌ Allocation Issues Found:</h6><ul class="text-danger">';
                                    
                                    validationErrors.forEach(error => {
                                        if (error.missing) {
                                            errorMessage += `<li><strong>${error.item}:</strong> Missing ${error.missing} units (allocated ${error.allocated}/${error.required})</li>`;
                                        } else if (error.excess) {
                                            errorMessage += `<li><strong>${error.item}:</strong> Over-allocated by ${error.excess} units (allocated ${error.allocated}/${error.required})</li>`;
                                        }
                                    });
                                    
                                    errorMessage += '</ul><div class="alert alert-warning mt-3"><i class="fas fa-exclamation-triangle me-2"></i><strong>Please allocate ALL quantities to team members before approving.</strong></div></div>';
                                    
                                    Swal.showValidationMessage(errorMessage);
                                    console.log('Approval blocked due to allocation issues');
                                    return false;
                                }
                                
                                console.log('Validation passed - all items fully allocated');
                                return {
                                    notes: notes,
                                    allocations: allocations
                                };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                console.log('User confirmed approval with:', result.value);
                                approveRequisition(id, result.value.notes, result.value.allocations);
                            }
                        });
                        
                        // Bind allocation button click events after modal is shown
                        setTimeout(() => {
                            $(document).on('click', '.allocate-item-btn', function() {
                                const itemIndex = $(this).data('item-index');
                                const itemName = $(this).data('item-name');
                                const itemQty = $(this).data('item-qty');
                                const itemDescription = $(this).data('item-description');
                                
                                console.log('Allocation button clicked:', {itemIndex, itemName, itemQty, itemDescription});
                                showItemAllocationModal(itemIndex, itemName, itemQty, itemDescription, req.team_members);
                            });
                        }, 500);
                        
                    } else {
                        console.log('Response success was false:', response.message);
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('=== APPROVAL MODAL AJAX ERROR ===');
                    console.log('XHR object:', xhr);
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response Status:', xhr.status);
                    console.log('Response Status Text:', xhr.statusText);
                    console.log('Response Text:', xhr.responseText);
                    
                    try {
                        const responseJson = JSON.parse(xhr.responseText);
                        console.log('Parsed Response JSON:', responseJson);
                    } catch (e) {
                        console.log('Could not parse response as JSON:', e);
                    }
                    
                    let errorMessage = 'Failed to load requisition for approval';
                    if (xhr.status === 404) {
                        errorMessage = 'Requisition not found (404 error)';
                        console.log('404 Error - Requisition not found for approval');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    console.log('Final approval error message:', errorMessage);
                    Swal.fire('Error!', errorMessage, 'error');
                },
                complete: function(xhr, status) {
                    console.log('=== APPROVAL MODAL AJAX COMPLETE ===');
                    console.log('Final status:', status);
                    console.log('Final XHR status:', xhr.status);
                    console.log('=== SHOW APPROVAL MODAL DEBUG END ===');
                }
            });
        }
        
        // EDIT REQUISITION FUNCTION WITH DEBUG LOGGING
        function editRequisition(id) {
            console.log('=== EDIT REQUISITION DEBUG START ===');
            console.log('Function called with ID:', id);
            console.log('ID type:', typeof id);
            
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching requisition details...',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const ajaxUrl = `{{ url('company/management/requisitions') }}/${id}`;
            
            console.log('EDIT REQUISITION AJAX Request Details:');
            console.log('URL:', ajaxUrl);
            console.log('Method: GET');
            
            // Fetch requisition data and show edit modal
            $.ajax({
                url: ajaxUrl,
                method: 'GET',
                beforeSend: function(xhr) {
                    console.log('=== EDIT REQUISITION AJAX BEFORE SEND ===');
                    console.log('XHR object:', xhr);
                },
                success: function(response) {
                    console.log('=== EDIT REQUISITION AJAX SUCCESS ===');
                    console.log('Response received:', response);
                    
                    if (response.success) {
                        console.log('Edit response success, closing loading modal');
                        Swal.close();
                        console.log('Calling showEditModal with data:', response.data);
                        showEditModal(response.data);
                    } else {
                        console.log('Edit response failed:', response.message);
                        Swal.fire('Error!', 'Failed to load requisition details', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('=== EDIT REQUISITION AJAX ERROR ===');
                    console.log('XHR object:', xhr);
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response Status:', xhr.status);
                    console.log('Response Status Text:', xhr.statusText);
                    console.log('Response Text:', xhr.responseText);
                    
                    try {
                        const responseJson = JSON.parse(xhr.responseText);
                        console.log('Parsed Response JSON:', responseJson);
                    } catch (e) {
                        console.log('Could not parse response as JSON:', e);
                    }
                    
                    let errorMessage = 'Failed to load requisition details';
                    if (xhr.status === 404) {
                        errorMessage = 'Requisition not found (404 error)';
                        console.log('404 Error - Requisition not found for editing');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    console.log('Final edit error message:', errorMessage);
                    Swal.fire('Error!', errorMessage, 'error');
                },
                complete: function(xhr, status) {
                    console.log('=== EDIT REQUISITION AJAX COMPLETE ===');
                    console.log('Final status:', status);
                    console.log('Final XHR status:', xhr.status);
                    console.log('=== EDIT REQUISITION DEBUG END ===');
                }
            });
        }
        
        // Continue with the original showApprovalModal implementation
        function continueShowApprovalModal(id) {
            $.ajax({
                url: `{{ url('company/management/requisitions') }}/${id}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        const req = response.data;
                        
                        // Debug logging
                        console.log('View Details - Requisition data:', req);
                        console.log('View Details - Project Manager:', req.projectManager);
                        console.log('View Details - Team Members:', req.team_members);
                        
                        // Build items table with individual item allocation
                        let itemsHtml = '';
                        if (req.items && req.items.length > 0) {
                            itemsHtml = `
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Item</th>
                                                <th>Description</th>
                                                <th>Total Qty</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                                <th>Allocate to Team Members</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                            `;
                            req.items.forEach(function(item, itemIndex) {
                                const total = (item.quantity || 0) * (item.unit_price || 0);
                                
                                // Build allocation button for this item
                                let itemAllocationHtml = '';
                                console.log('Building allocation for item:', item.item_name, 'team_members:', req.team_members);
                                if (req.team_members && req.team_members.length > 0) {
                                    itemAllocationHtml = `
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary allocate-item-btn" 
                                                data-item-index="${itemIndex}"
                                                data-item-name="${item.item_name || 'N/A'}"
                                                data-item-qty="${item.quantity || 0}"
                                                data-item-description="${item.description || 'N/A'}"
                                                title="Allocate to team members">
                                            <i class="fas fa-users me-1"></i>
                                            Allocate (${item.quantity || 0} available)
                                        </button>
                                        <div class="mt-1">
                                            <small class="text-muted" id="allocation_summary_${itemIndex}">
                                                No allocations yet
                                            </small>
                                        </div>
                                    `;
                                } else {
                                    itemAllocationHtml = '<p class="text-muted">No team members available</p>';
                                }
                                
                                itemsHtml += `
                                    <tr>
                                        <td><strong>${item.item_name || 'N/A'}</strong></td>
                                        <td>${item.description || 'N/A'}</td>
                                        <td><strong>${item.quantity || 0}</strong></td>
                                        <td>GHS ${formatAmount(item.unit_price || 0)}</td>
                                        <td>GHS ${formatAmount(total)}</td>
                                        <td>${itemAllocationHtml}</td>
                                    </tr>
                                `;
                            });
                            itemsHtml += `
                                        </tbody>
                                    </table>
                                </div>
                            `;
                        } else {
                            itemsHtml = '<p class="text-muted">No items found</p>';
                        }
                        
                        // Debug team members for approval modal
                        console.log('Approval modal - Team members debug:', {
                            team_members: req.team_members,
                            team_leader_id: req.team_leader_id,
                            team_leader: req.teamLeader,
                            team_members_length: req.team_members ? req.team_members.length : 'undefined'
                        });
                        
                        // Show team members info (no allocation inputs needed since we do item-level allocation)
                        let teamMembersHtml = '';
                        if (req.team_members && req.team_members.length > 0) {
                            teamMembersHtml = `
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-users me-2"></i>Team Members
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Team Member</th>
                                                        <th>Position</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                            `;
                            req.team_members.forEach(function(member) {
                                teamMembersHtml += `
                                    <tr>
                                        <td><strong>${member.full_name || 'N/A'}</strong></td>
                                        <td>${member.position || 'N/A'}</td>
                                        <td>${member.email || 'N/A'}</td>
                                        <td>${member.phone || 'N/A'}</td>
                                    </tr>
                                `;
                            });
                            teamMembersHtml += `
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="alert alert-info mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Note:</strong> Allocate specific quantities of each item to team members in the items table above.
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            teamMembersHtml = '<p class="text-muted">No team members assigned</p>';
                        }
                        
                        Swal.fire({
                            title: 'Approve Requisition',
                            html: `
                                <div class="text-start">
                                    <!-- Requisition Info -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <h6>Requisition Information</h6>
                                            <p><strong>Number:</strong> ${req.requisition_number}</p>
                                            <p><strong>Title:</strong> ${req.title}</p>
                                            <p><strong>Priority:</strong> <span class="badge bg-${getPriorityBadgeClass(req.priority)}">${req.priority}</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Team Information</h6>
                                            <p><strong>Team Leader:</strong> ${req.teamLeader ? req.teamLeader.full_name : 'Not Assigned'}</p>
                                            <p><strong>Project Manager:</strong> ${req.projectManager ? req.projectManager.fullname : 'Not Assigned'}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Items -->
                                    <div class="mb-4">
                                        <h6>Requisition Items</h6>
                                        ${itemsHtml}
                                    </div>
                                    
                                    <!-- Team Members -->
                                    ${teamMembersHtml}
                                    
                                    <!-- Approval Notes -->
                                    <div class="mb-3">
                                        <label for="approvalNotes" class="form-label">Approval Notes (Optional)</label>
                                        <textarea class="form-control" id="approvalNotes" rows="3" placeholder="Add any notes about this approval..."></textarea>
                                    </div>
                                </div>
                            `,
                            width: '90%',
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonText: 'Approve Requisition',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#28a745',
                            customClass: {
                                popup: 'swal-wide'
                            },
                            preConfirm: () => {
                                const notes = document.getElementById('approvalNotes').value;
                                
                                // Get allocations from global variable
                                const itemAllocations = window.itemAllocations || {};
                                
                                return { 
                                    notes: notes,
                                    item_allocations: itemAllocations
                                };
                            }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (result.value) {
                        // Save the changes
                        approveRequisition(id, result.value.notes, result.value.item_allocations);
                    }
                }
            });
                        
                        // Reset allocation summaries and add click handlers for allocation buttons
                        resetAllocationSummaries();
                        setTimeout(() => {
                            const allocationButtons = document.querySelectorAll('.allocate-item-btn');
                            console.log('Found allocation buttons:', allocationButtons.length);
                            allocationButtons.forEach(button => {
                                button.addEventListener('click', function() {
                                    console.log('Allocate button clicked');
                                    const itemIndex = this.getAttribute('data-item-index');
                                    const itemName = this.getAttribute('data-item-name');
                                    const itemQty = parseInt(this.getAttribute('data-item-qty'));
                                    const itemDescription = this.getAttribute('data-item-description');
                                    
                                    console.log('Opening allocation modal for item:', itemName, 'qty:', itemQty);
                                    showItemAllocationModal(itemIndex, itemName, itemQty, itemDescription, req.team_members);
                                });
                            });
                        }, 100);
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to load requisition details', 'error');
                }
            });
        }

        // New rejection modal with form
        function showRejectionModal(id) {
            console.log('=== SHOW REJECTION MODAL DEBUG ===');
            console.log('Requisition ID:', id);
            console.log('Showing rejection modal for requisition:', id);
            
            Swal.fire({
                title: 'Reject Requisition',
                html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label for="rejectionReason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                            <select class="form-select" id="rejectionReason" required>
                                <option value="">Select a reason...</option>
                                <option value="insufficient_budget">Insufficient Budget</option>
                                <option value="incorrect_items">Incorrect Items Requested</option>
                                <option value="missing_justification">Missing Justification</option>
                                <option value="duplicate_request">Duplicate Request</option>
                                <option value="incomplete_information">Incomplete Information</option>
                                <option value="not_approved_by_supervisor">Not Approved by Supervisor</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rejectionComments" class="form-label">Additional Comments</label>
                            <textarea class="form-control" id="rejectionComments" rows="4" placeholder="Provide detailed explanation for the rejection..."></textarea>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: 'Reject Requisition',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545',
                preConfirm: () => {
                    console.log('=== REJECTION MODAL PRECONFIRM ===');
                    const reason = document.getElementById('rejectionReason').value;
                    const comments = document.getElementById('rejectionComments').value;
                    
                    console.log('Selected reason:', reason);
                    console.log('Entered comments:', comments);
                    
                    if (!reason) {
                        console.log('Validation failed: No reason selected');
                        Swal.showValidationMessage('Please select a reason for rejection');
                        return false;
                    }
                    
                    const result = { reason: reason, comments: comments };
                    console.log('PreConfirm returning:', result);
                    return result;
                }
            }).then((result) => {
                console.log('=== REJECTION MODAL THEN ===');
                console.log('Result:', result);
                console.log('Is confirmed:', result.isConfirmed);
                
                if (result.isConfirmed) {
                    console.log('User confirmed rejection, calling rejectRequisition with:');
                    console.log('ID:', id);
                    console.log('Reason:', result.value.reason);
                    console.log('Comments:', result.value.comments);
                    
                    rejectRequisition(id, result.value.reason, result.value.comments);
                } else {
                    console.log('User cancelled rejection');
                }
            });
        }

        // Updated approve function
        function approveRequisition(id, notes = '', itemAllocations = {}) {
            $.ajax({
                url: `{{ url('company/management/requisitions') }}/${id}/approve`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    notes: notes,
                    item_allocations: itemAllocations
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success!', response.message, 'success');
                        loadRequisitions();
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to approve requisition', 'error');
                }
            });
        }

        // Item allocation popup (not modal)
        function showItemAllocationModal(itemIndex, itemName, itemQty, itemDescription, teamMembers) {
            console.log('showItemAllocationModal called with:', {
                itemIndex, itemName, itemQty, itemDescription, teamMembers
            });
            
            // Remove existing popup if any
            const existingPopup = document.getElementById('allocationPopup');
            if (existingPopup) {
                existingPopup.remove();
            }
            
            // Build popup content
            let popupContent = '';
            if (teamMembers && teamMembers.length > 0) {
                popupContent = `
                    <div class="allocation-popup-header">
                        <h5 class="mb-0">Allocate "${itemName}" to Team Members</h5>
                        <button type="button" class="btn-close" id="closeAllocationPopup">&times;</button>
                    </div>
                    <div class="allocation-popup-body">
                        <div class="mb-3">
                            <p class="text-muted mb-1">Available Quantity: <strong>${itemQty}</strong></p>
                            <p class="text-muted mb-3">${itemDescription}</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Team Member</th>
                                        <th>Position</th>
                                        <th>Allocate Quantity</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
                
                teamMembers.forEach(function(member, memberIndex) {
                    popupContent += `
                        <tr>
                            <td><strong>${member.full_name || 'N/A'}</strong></td>
                            <td>${member.position || 'N/A'}</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="number" 
                                           class="form-control item-allocation-input" 
                                           data-member-id="${member.id}"
                                           data-member-name="${member.full_name}"
                                           min="0" 
                                           max="${itemQty}"
                                           placeholder="0">
                                    <span class="input-group-text">/ ${itemQty}</span>
                                </div>
                            </td>
                            <td>
                                <input type="text" 
                                       class="form-control form-control-sm allocation-notes-input" 
                                       data-member-id="${member.id}"
                                       placeholder="Optional notes">
                            </td>
                        </tr>
                    `;
                });
                
                popupContent += `
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Remaining:</strong> <span id="popup_remaining_${itemIndex}">${itemQty}</span> units
                        </div>
                    </div>
                    <div class="allocation-popup-footer">
                        <button type="button" class="btn btn-secondary" id="cancelAllocation">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveAllocation">Save Allocation</button>
                    </div>
                `;
            } else {
                popupContent = `
                    <div class="allocation-popup-header">
                        <h5 class="mb-0">Allocate "${itemName}"</h5>
                        <button type="button" class="btn-close" id="closeAllocationPopup">&times;</button>
                    </div>
                    <div class="allocation-popup-body">
                        <p class="text-muted">No team members available</p>
                    </div>
                    <div class="allocation-popup-footer">
                        <button type="button" class="btn btn-secondary" id="cancelAllocation">Close</button>
                    </div>
                `;
            }
            
            // Create popup HTML
            const popupHtml = `
                <div id="allocationPopup" class="allocation-popup-overlay">
                    <div class="allocation-popup">
                        ${popupContent}
                    </div>
                </div>
            `;
            
            // Add popup to body
            document.body.insertAdjacentHTML('beforeend', popupHtml);
            
            // Add event listeners
            document.getElementById('closeAllocationPopup').addEventListener('click', closeAllocationPopup);
            document.getElementById('cancelAllocation').addEventListener('click', closeAllocationPopup);
            document.getElementById('saveAllocation').addEventListener('click', function() {
                saveItemAllocationPopup(itemIndex, itemQty);
            });
            
            // Add real-time validation
            setTimeout(() => {
                const allocationInputs = document.querySelectorAll('.item-allocation-input');
                allocationInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        let totalAllocated = 0;
                        allocationInputs.forEach(inp => {
                            totalAllocated += parseInt(inp.value) || 0;
                        });
                        
                        const remaining = itemQty - totalAllocated;
                        const remainingSpan = document.getElementById(`popup_remaining_${itemIndex}`);
                        if (remainingSpan) {
                            remainingSpan.textContent = remaining;
                            remainingSpan.className = remaining < 0 ? 'text-danger' : 'text-success';
                        }
                        
                        // Update input max values
                        allocationInputs.forEach(inp => {
                            const currentValue = parseInt(inp.value) || 0;
                            const otherAllocated = totalAllocated - currentValue;
                            const maxAllowed = itemQty - otherAllocated;
                            inp.setAttribute('max', Math.max(0, maxAllowed));
                        });
                    });
                });
            }, 100);
        }
        
        // Close allocation popup
        function closeAllocationPopup() {
            const popup = document.getElementById('allocationPopup');
            if (popup) {
                popup.remove();
            }
        }
        
        // Save allocation from popup
        function saveItemAllocationPopup(itemIndex, itemQty) {
            console.log('saveItemAllocationPopup called for item:', itemIndex, 'qty:', itemQty);
            
            const allocations = [];
            const allocationInputs = document.querySelectorAll('.item-allocation-input');
            const notesInputs = document.querySelectorAll('.allocation-notes-input');
            
            let totalAllocated = 0;
            
            allocationInputs.forEach((input, index) => {
                const quantity = parseInt(input.value) || 0;
                const memberId = input.getAttribute('data-member-id');
                const memberName = input.getAttribute('data-member-name');
                const notes = notesInputs[index] ? notesInputs[index].value : '';
                
                if (quantity > 0) {
                    allocations.push({
                        member_id: memberId,
                        member_name: memberName,
                        quantity: quantity,
                        notes: notes
                    });
                }
                
                totalAllocated += quantity;
            });
            
            // Validate total allocation
            if (totalAllocated > itemQty) {
                alert(`Total allocation (${totalAllocated}) cannot exceed available quantity (${itemQty})`);
                return;
            }
            
            // Store allocation in global variable
            if (!window.itemAllocations) {
                window.itemAllocations = {};
            }
            window.itemAllocations[itemIndex] = allocations;
            
            console.log('Stored allocations:', window.itemAllocations);
            
            // Update the allocation summary
            updateAllocationSummary(itemIndex, totalAllocated, itemQty - totalAllocated);
            
            // Close popup
            closeAllocationPopup();
            
            // Show simple success message without SweetAlert2
            const successMsg = document.createElement('div');
            successMsg.className = 'alert alert-success alert-dismissible fade show position-fixed';
            successMsg.style.cssText = 'top: 20px; right: 20px; z-index: 10001; min-width: 300px;';
            successMsg.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                Successfully allocated ${totalAllocated} units to team members
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(successMsg);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (successMsg.parentNode) {
                    successMsg.remove();
                }
            }, 3000);
        }
        
        
        // Update allocation summary
        function updateAllocationSummary(itemIndex, totalAllocated, remaining) {
            const summaryElement = document.getElementById(`allocation_summary_${itemIndex}`);
            if (summaryElement) {
                const totalRequired = totalAllocated + remaining;
                
                if (remaining === 0) {
                    // Fully allocated - show success
                    summaryElement.innerHTML = `
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>
                            ${totalAllocated}/${totalRequired} allocated ✓
                        </span>
                    `;
                } else if (totalAllocated > 0) {
                    // Partially allocated - show warning
                    summaryElement.innerHTML = `
                        <span class="badge bg-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            ${totalAllocated}/${totalRequired} allocated (${remaining} remaining)
                        </span>
                    `;
                } else {
                    // Not allocated - show danger
                    summaryElement.innerHTML = `
                        <span class="badge bg-danger">
                            <i class="fas fa-times-circle me-1"></i>
                            0/${totalRequired} allocated (⚠️ Required)
                        </span>
                    `;
                }
            }
            
            // Update the allocate button to show it's been allocated
            const allocateBtn = document.querySelector(`[data-item-index="${itemIndex}"]`);
            if (allocateBtn && totalAllocated > 0) {
                allocateBtn.classList.remove('btn-outline-primary');
                allocateBtn.classList.add('btn-success');
                allocateBtn.innerHTML = `
                    <i class="fas fa-check-circle me-1"></i>
                    Allocated (${totalAllocated}/${allocateBtn.getAttribute('data-item-qty')})
                `;
            }
        }
        
        // Reset all allocation summaries
        function resetAllocationSummaries() {
            const summaryElements = document.querySelectorAll('[id^="allocation_summary_"]');
            summaryElements.forEach(element => {
                element.innerHTML = 'No allocations yet';
            });
        }

        // Edit requisition function - fetch and show edit modal
        function editRequisition(id) {
            // Show loading
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching requisition details for editing.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Fetch requisition data and show edit modal
            $.ajax({
                url: `{{ url('company/management/requisitions') }}/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        Swal.close();
                        showEditModal(response.data);
                    } else {
                        Swal.fire('Error!', 'Failed to load requisition details', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to load requisition details', 'error');
                }
            });
        }
        
        // Helper function to get requestor name - use EXACT same logic as main table
        function getRequestorName(requisition) {
            console.log('Getting requestor name for:', requisition);
            if (requisition.requestor) {
                console.log('Requestor data:', requisition.requestor);
                console.log('Requestor personal_info:', requisition.requestor.personal_info);
                
                // Use EXACT same logic as main table (lines 364-376)
                if (requisition.requestor.personal_info) {
                    const name = `${requisition.requestor.personal_info.first_name || ''} ${requisition.requestor.personal_info.last_name || ''}`.trim();
                    console.log('Personal info name:', name);
                    if (name) {
                        return name;
                    } else {
                        console.log('Personal info empty, using staff_id:', requisition.requestor.staff_id);
                        return requisition.requestor.staff_id || 'Unknown';
                    }
                } else {
                    console.log('No personal info, using staff_id:', requisition.requestor.staff_id);
                    return requisition.requestor.staff_id || 'Unknown';
                }
            }
            console.log('No requestor data found');
            return 'Unknown';
        }
        
        // Helper function to get project manager name
        function getProjectManagerName(requisition) {
            console.log('Getting project manager name for:', requisition);
            if (requisition.projectManager) {
                console.log('Project manager data:', requisition.projectManager);
                const name = requisition.projectManager.fullname || requisition.projectManager.name || 'Unknown';
                console.log('Project manager name:', name);
                return name;
            }
            console.log('No project manager data found');
            return 'Not Assigned';
        }
        
        // Helper function to get team leader name
        function getTeamLeaderName(requisition) {
            console.log('Getting team leader name for:', requisition);
            if (requisition.teamLeader) {
                console.log('Team leader data:', requisition.teamLeader);
                const name = requisition.teamLeader.full_name || requisition.teamLeader.name || 'Unknown';
                console.log('Team leader name:', name);
                return name;
            }
            console.log('No team leader data found');
            return 'Not Assigned';
        }
        
        // Show edit modal with warehouse requisition layout
        function showEditModal(requisition) {
            console.log('Edit modal - Full requisition data:', requisition);
            console.log('Edit modal - Requestor:', requisition.requestor);
            console.log('Edit modal - Project Manager:', requisition.projectManager);
            console.log('Edit modal - Items:', requisition.items);
            
            // Store requisition data globally for dropdown functions
            window.currentRequisitionData = requisition;
            
            // Format dates for HTML date inputs (YYYY-MM-DD format)
            // If dates are null, set default values
            const today = new Date().toISOString().split('T')[0];
            const nextWeek = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            
            const requisitionDate = requisition.requisition_date ? 
                new Date(requisition.requisition_date).toISOString().split('T')[0] : 
                today; // Default to today if null
                
            const requiredDate = requisition.required_date ? 
                new Date(requisition.required_date).toISOString().split('T')[0] : 
                nextWeek; // Default to next week if null
            
            console.log('=== DATE DEBUGGING ===');
            console.log('Raw requisition data:', requisition);
            console.log('Raw requisition.requisition_date:', requisition.requisition_date);
            console.log('Raw requisition.required_date:', requisition.required_date);
            console.log('Type of requisition_date:', typeof requisition.requisition_date);
            console.log('Type of required_date:', typeof requisition.required_date);
            console.log('Formatted dates:', {
                requisitionDate: requisitionDate,
                requiredDate: requiredDate,
                originalRequisitionDate: requisition.requisition_date,
                originalRequiredDate: requisition.required_date
            });
            console.log('Date formatting check:');
            if (requisition.requisition_date) {
                console.log('requisition_date exists, trying to parse...');
                try {
                    const testDate = new Date(requisition.requisition_date);
                    console.log('Parsed requisition_date:', testDate);
                    console.log('ISO string:', testDate.toISOString());
                    console.log('Final formatted:', testDate.toISOString().split('T')[0]);
                } catch (e) {
                    console.error('Error parsing requisition_date:', e);
                }
            } else {
                console.log('requisition_date is null/undefined - using default (today):', today);
            }
            
            if (requisition.required_date) {
                console.log('required_date exists, trying to parse...');
                try {
                    const testDate = new Date(requisition.required_date);
                    console.log('Parsed required_date:', testDate);
                    console.log('ISO string:', testDate.toISOString());
                    console.log('Final formatted:', testDate.toISOString().split('T')[0]);
                } catch (e) {
                    console.error('Error parsing required_date:', e);
                }
            } else {
                console.log('required_date is null/undefined - using default (next week):', nextWeek);
            }
            
            let editForm = `
                <div class="edit-requisition-container" style="max-width: 100%; margin: 0; padding: 0;">
                    <!-- Header -->
                    <div class="mb-4">
                        <h4 class="mb-1">Fill in the details below to create a new material requisition</h4>
                    </div>
                    
                    <!-- Requisition Details Section -->
                    <div class="form-section" style="background: #fff; border-radius: 0.5rem; padding: 1.75rem; margin-bottom: 1.75rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); border: 1px solid #e3e6f0;">
                        <h5 class="section-title" style="position: relative; font-weight: 600; color: #5a5c69; margin: 0 0 1.5rem; padding-bottom: 0.75rem;">
                            <i class="fas fa-file-alt me-2"></i>Requisition Details
                            <div style="position: absolute; left: 0; bottom: 0; width: 50px; height: 3px; background: #3b7ddd; border-radius: 3px;"></div>
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_title" class="form-label" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">
                                    <i class="fas fa-heading me-1 text-muted"></i>Requisition Title <span style="color: #e74a3b;">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                    <input type="text" class="form-control" id="edit_title" value="${requisition.title}" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_requisition_date" class="form-label" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">
                                    <i class="fas fa-calendar me-1 text-muted"></i>Requisition Date
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <input type="date" class="form-control" id="edit_requisition_date" value="${requisitionDate}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_requested_by" class="form-label" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">
                                    <i class="fas fa-user me-1 text-muted"></i>Requested By
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="edit_requested_by" value="${getRequestorName(requisition)}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_required_date" class="form-label" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">
                                    <i class="fas fa-calendar-alt me-1 text-muted"></i>Required Date
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="date" class="form-control" id="edit_required_date" value="${requiredDate}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_department" class="form-label" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">
                                    <i class="fas fa-building me-1 text-muted"></i>Department
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    <input type="text" class="form-control" id="edit_department" value="${requisition.department || ''}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_project_manager" class="form-label" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">
                                    <i class="fas fa-user-tie me-1 text-muted"></i>Project Manager
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                    <select class="form-select" id="edit_project_manager">
                                        <option value="">Select Project Manager</option>
                                        <!-- Project managers will be loaded dynamically -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_team_leader" class="form-label" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">
                                    <i class="fas fa-user-friends me-1 text-muted"></i>Team Leader
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-friends"></i></span>
                                    <select class="form-select" id="edit_team_leader">
                                        <option value="">Select Team Leader</option>
                                        <!-- Team leaders will be loaded dynamically -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_priority" class="form-label" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">
                                    <i class="fas fa-exclamation-triangle me-1 text-muted"></i>Priority <span style="color: #e74a3b;">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-exclamation-triangle"></i></span>
                                    <select class="form-select" id="edit_priority" required>
                                        <option value="low" ${requisition.priority === 'low' ? 'selected' : ''}>Low</option>
                                        <option value="medium" ${requisition.priority === 'medium' ? 'selected' : ''}>Medium</option>
                                        <option value="high" ${requisition.priority === 'high' ? 'selected' : ''}>High</option>
                                        <option value="urgent" ${requisition.priority === 'urgent' ? 'selected' : ''}>Urgent</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_reference_number" class="form-label" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">
                                    <i class="fas fa-hashtag me-1 text-muted"></i>Reference Number
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                    <input type="text" class="form-control" id="edit_reference_number" value="${requisition.requisition_number}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
            `;
            
            // Add items section
            editForm += `
                <!-- Items Section -->
                <div class="form-section" style="background: #fff; border-radius: 0.5rem; padding: 1.75rem; margin-bottom: 1.75rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); border: 1px solid #e3e6f0;">
                    <h5 class="section-title" style="position: relative; font-weight: 600; color: #5a5c69; margin: 0 0 1.5rem; padding-bottom: 0.75rem;">
                        <i class="fas fa-boxes me-2"></i>Items
                        <div style="position: absolute; left: 0; bottom: 0; width: 50px; height: 3px; background: #3b7ddd; border-radius: 3px;"></div>
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30%;">Item</th>
                                    <th style="width: 15%;">Quantity</th>
                                    <th style="width: 15%;">Unit</th>
                                    <th style="width: 15%;">Unit Price</th>
                                    <th style="width: 15%;">Total</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="editItemsTableBody">
            `;
            
            // Add existing items
            if (requisition.items && requisition.items.length > 0) {
                requisition.items.forEach((item, index) => {
                    const total = (item.quantity || 0) * (item.unit_price || 0);
                    editForm += `
                        <tr>
                            <td>
                                <select class="form-select item-select" id="edit_item_select_${index}" required>
                                    <option value="">Select Item</option>
                                    <!-- Items will be loaded dynamically -->
                                </select>
                                <small class="text-muted">Available: Loading...</small>
                            </td>
                            <td>
                                <input type="number" class="form-control" value="${item.quantity || 0}" id="edit_item_quantity_${index}" min="1" max="1000" required>
                            </td>
                            <td>
                                <span class="form-control-plaintext" id="edit_item_unit_${index}">${item.unit || '-'}</span>
                            </td>
                            <td>
                                <input type="number" class="form-control" value="${item.unit_price || 0}" id="edit_item_price_${index}" min="0" step="0.01" required>
                            </td>
                            <td>
                                <span class="item-total fw-bold" id="edit_item_total_${index}" style="color: #1cc88a;">GHS ${formatAmount(total)}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeEditItem(${index})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
            
            editForm += `
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary" id="addEditItemBtn">
                                <i class="fas fa-plus me-1"></i> Add Item
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3">
                                <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2 mt-2">
                                    <span>Total Items:</span>
                                    <span id="edit_total_amount" style="color: #1cc88a;">GHS ${formatAmount(requisition.total_amount || 0)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            editForm += `
                </div>
            `;
            
            Swal.fire({
                title: 'Edit Requisition',
                html: editForm,
                width: '90%',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: 'Save Changes',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#28a745',
                customClass: {
                    popup: 'swal-wide'
                },
                preConfirm: async () => {
                    const data = saveRequisitionChanges(requisition.id);
                    if (data) {
                        try {
                            // Save the data to server
                            const result = await saveRequisitionUpdate(requisition.id, data);
                            if (result.success) {
                                // Show success message
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Requisition updated successfully',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                // Refresh the requisition list
                                loadRequisitions();
                                return true;
                            } else {
                                // Show error message
                                Swal.fire({
                                    title: 'Error!',
                                    text: result.message || 'Failed to update requisition',
                                    icon: 'error'
                                });
                                return false;
                            }
                        } catch (error) {
                            // Show error message
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to update requisition: ' + error.message,
                                icon: 'error'
                            });
                            return false;
                        }
                    }
                    return false;
                },
                onClose: () => {
                    // Clear loaded items cache to prevent infinite loops
                    clearLoadedItems();
                }
            });
            
            // Initialize edit modal functionality
            setTimeout(() => {
                // Clear any existing cache first to ensure fresh loading
                clearLoadedItems();
                
                // Load project managers and team leaders
                loadEditProjectManagers();
                loadEditTeamLeaders();
                
                // Set current values after a delay to ensure dropdowns are loaded
                setTimeout(() => {
                    console.log('Setting project manager ID:', requisition.project_manager_id);
                    console.log('Setting team leader ID:', requisition.team_leader_id);
                    
                    if (requisition.project_manager_id) {
                        const projectManagerSelect = document.getElementById('edit_project_manager');
                        if (projectManagerSelect) {
                            projectManagerSelect.value = requisition.project_manager_id;
                            console.log('Set project manager value to:', requisition.project_manager_id);
                            // Trigger change event to ensure the selection is properly set
                            $(projectManagerSelect).trigger('change');
                        }
                    }
                    if (requisition.team_leader_id) {
                        const teamLeaderSelect = document.getElementById('edit_team_leader');
                        if (teamLeaderSelect) {
                            teamLeaderSelect.value = requisition.team_leader_id;
                            console.log('Set team leader value to:', requisition.team_leader_id);
                            // Trigger change event to ensure the selection is properly set
                            $(teamLeaderSelect).trigger('change');
                        }
                    }
                    
                    // Set date values
                    console.log('=== SETTING DATE VALUES ===');
                    const requisitionDateInput = document.getElementById('edit_requisition_date');
                    const requiredDateInput = document.getElementById('edit_required_date');
                    
                    console.log('requisitionDateInput element:', requisitionDateInput);
                    console.log('requiredDateInput element:', requiredDateInput);
                    console.log('requisitionDate value to set:', requisitionDate);
                    console.log('requiredDate value to set:', requiredDate);
                    
                    if (requisitionDateInput) {
                        if (requisitionDate) {
                        requisitionDateInput.value = requisitionDate;
                        console.log('Set requisition date to:', requisitionDate);
                            console.log('Actual input value after setting:', requisitionDateInput.value);
                        } else {
                            console.log('requisitionDate is empty, not setting');
                        }
                    } else {
                        console.error('requisitionDateInput element not found!');
                    }
                    
                    if (requiredDateInput) {
                        if (requiredDate) {
                        requiredDateInput.value = requiredDate;
                        console.log('Set required date to:', requiredDate);
                            console.log('Actual input value after setting:', requiredDateInput.value);
                        } else {
                            console.log('requiredDate is empty, not setting');
                        }
                    } else {
                        console.error('requiredDateInput element not found!');
                    }
                }, 2000);
                
                // Fallback: Set dates immediately as well
                setTimeout(() => {
                    const requisitionDateInput = document.getElementById('edit_requisition_date');
                    const requiredDateInput = document.getElementById('edit_required_date');
                    
                    if (requisitionDateInput && requisitionDate && !requisitionDateInput.value) {
                        requisitionDateInput.value = requisitionDate;
                        console.log('Fallback: Set requisition date to:', requisitionDate);
                    }
                    
                    if (requiredDateInput && requiredDate && !requiredDateInput.value) {
                        requiredDateInput.value = requiredDate;
                        console.log('Fallback: Set required date to:', requiredDate);
                    }
                }, 100);
                
                // Load available items for all existing item selects
                if (requisition.items && requisition.items.length > 0) {
                    requisition.items.forEach((item, index) => {
                        loadAvailableItemsForEdit(`#edit_item_select_${index}`);
                        
                        // Set the selected item after items are loaded
                        setTimeout(() => {
                            const select = document.getElementById(`edit_item_select_${index}`);
                            const quantityInput = document.getElementById(`edit_item_quantity_${index}`);
                            const priceInput = document.getElementById(`edit_item_price_${index}`);
                            
                            console.log(`Setting values for item ${index}:`, item);
                            
                            if (select && item.item_id) {
                                console.log('Setting selected item for index', index, 'item_id:', item.item_id);
                                select.value = item.item_id;
                                console.log('Selected item value set to:', item.item_id);
                                
                                // Update the availability text immediately
                                const availabilityText = select.parentNode.querySelector('small.text-muted');
                                if (availabilityText && item.available_qty) {
                                    availabilityText.textContent = `Available: ${item.available_qty} ${item.unit || 'pcs'}`;
                                    availabilityText.style.color = '#28a745';
                                }
                                
                                // Trigger the change event to update price and unit
                                setTimeout(() => {
                                    $(select).trigger('change');
                                }, 100);
                            }
                            
                            // Set quantity and price directly as fallback
                            if (quantityInput && item.quantity) {
                                quantityInput.value = item.quantity;
                                console.log('Set quantity to:', item.quantity);
                            }
                            
                            if (priceInput && item.unit_price) {
                                priceInput.value = item.unit_price;
                                console.log('Set unit price to:', item.unit_price);
                            }
                            
                            // Also set unit if available
                            const unitDisplay = document.getElementById(`edit_item_unit_${index}`);
                            if (unitDisplay && item.unit) {
                                unitDisplay.textContent = item.unit;
                                console.log('Set unit to:', item.unit);
                            }
                        }, 1500);
                        
                        // Update total amount after loading items
                        setTimeout(() => {
                            updateEditTotalAmount();
                        }, 2000);
                        
                        // Add event listeners for calculations
                        const quantityInput = document.getElementById(`edit_item_quantity_${index}`);
                        const priceInput = document.getElementById(`edit_item_price_${index}`);
                        const totalSpan = document.getElementById(`edit_item_total_${index}`);
                        
                        if (quantityInput && priceInput && totalSpan) {
                            const calculateTotal = () => {
                                const qty = parseFloat(quantityInput.value) || 0;
                                const price = parseFloat(priceInput.value) || 0;
                                const total = qty * price;
                                totalSpan.textContent = `GHS ${formatAmount(total)}`;
                                updateEditTotalAmount();
                            };
                            
                            quantityInput.addEventListener('input', calculateTotal);
                            priceInput.addEventListener('input', calculateTotal);
                        }
                    });
                }
                
                // Add event listener for add item button
                document.getElementById('addEditItemBtn').addEventListener('click', addNewEditItem);
                
                // Reload items for all existing rows after modal is fully initialized
                setTimeout(() => {
                    console.log('🔄 Reloading items for all existing rows after modal initialization...');
                    const allItemSelects = document.querySelectorAll('[id^="edit_item_select_"]');
                    console.log('Found', allItemSelects.length, 'item selects to reload');
                    
                    allItemSelects.forEach((select, index) => {
                        console.log(`🔄 Reloading items for select ${index}:`, select.id);
                        // Force reload items for this selector
                        loadAvailableItemsForEdit(select.id, true);
                    });
                }, 1500);
            }, 100);
        }
        
        // Track loaded items to prevent infinite loops
        const loadedItems = new Set();
        
        // Function to clear loaded items when modal is closed
        function clearLoadedItems() {
            loadedItems.clear();
            console.log('Cleared loaded items cache');
        }
        
        // Check for duplicate items and merge quantities
        function checkForDuplicateItems() {
            console.log('Checking for duplicate items...');
            const tbody = document.getElementById('editItemsTableBody');
            const rows = tbody.querySelectorAll('tr');
            const itemCounts = {};
            
            console.log(`Found ${rows.length} rows to check`);
            
            // Count items by ID
            rows.forEach((row, index) => {
                const select = row.querySelector('select[id^="edit_item_select_"]');
                const quantityInput = row.querySelector('input[id^="edit_item_quantity_"]');
                
                if (select && select.value && quantityInput) {
                    const itemId = select.value;
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const itemName = select.options[select.selectedIndex].textContent;
                    
                    console.log(`Row ${index}: Item ID ${itemId} (${itemName}), Quantity: ${quantity}`);
                    
                    if (itemCounts[itemId]) {
                        // Duplicate found - merge quantities
                        itemCounts[itemId].quantity += quantity;
                        itemCounts[itemId].rows.push({row, index});
                        console.log(`Duplicate found for item ${itemId}, total quantity now: ${itemCounts[itemId].quantity}`);
                    } else {
                        itemCounts[itemId] = {
                            quantity: quantity,
                            rows: [{row, index}],
                            name: itemName
                        };
                    }
                }
            });
            
            // Handle duplicates
            Object.keys(itemCounts).forEach(itemId => {
                const itemData = itemCounts[itemId];
                if (itemData.rows.length > 1) {
                    console.log(`Merging ${itemData.rows.length} duplicate rows for item ${itemId} (${itemData.name})`);
                    console.log(`Total quantity: ${itemData.quantity}`);
                    
                    // Show user-friendly message
                    const itemName = itemData.name.split(' - ')[0]; // Get just the item name
                    console.log(`Duplicate item detected: ${itemName}. Merging quantities...`);
                    
                    // Keep the first row, remove others
                    const firstRow = itemData.rows[0];
                    const firstQuantityInput = firstRow.row.querySelector('input[id^="edit_item_quantity_"]');
                    const firstPriceInput = firstRow.row.querySelector('input[id^="edit_item_price_"]');
                    const firstTotalSpan = firstRow.row.querySelector('.item-total');
                    
                    // Update quantity in first row
                    if (firstQuantityInput) {
                        firstQuantityInput.value = itemData.quantity;
                        console.log(`Updated first row quantity to: ${itemData.quantity}`);
                        
                        // Recalculate total for first row
                        if (firstPriceInput && firstTotalSpan) {
                            const price = parseFloat(firstPriceInput.value) || 0;
                            const total = itemData.quantity * price;
                            firstTotalSpan.textContent = `GHS ${total.toFixed(2)}`;
                            console.log(`Recalculated total: ${total}`);
                        }
                        
                        // Trigger calculation
                        firstQuantityInput.dispatchEvent(new Event('input'));
                    }
                    
                    // Remove duplicate rows (in reverse order to maintain indices)
                    for (let i = itemData.rows.length - 1; i > 0; i--) {
                        const duplicateRow = itemData.rows[i];
                        console.log(`Removing duplicate row ${duplicateRow.index}`);
                        duplicateRow.row.remove();
                    }
                    
                    // Update total amount
                    updateEditTotalAmount();
                    
                    // Show success message
                    console.log(`✅ Merged duplicate items: ${itemName} - Total quantity: ${itemData.quantity}`);
                }
            });
            
            // After handling duplicates, ensure all remaining rows have items loaded
            setTimeout(() => {
                const remainingRows = tbody.querySelectorAll('tr');
                remainingRows.forEach((row, index) => {
                    const select = row.querySelector('select[id^="edit_item_select_"]');
                    if (select && (!select.value || select.innerHTML.includes('Loading items...'))) {
                        console.log(`Reloading items for row ${index}`);
                        const selector = `#${select.id}`;
                        // Force reload items for this selector
                        loadAvailableItemsForEdit(selector, true);
                    }
                });
            }, 100);
        }
        
        // Load available items for edit modal
        function loadAvailableItemsForEdit(selector, forceReload = false) {
            console.log('Loading available items for selector:', selector, 'forceReload:', forceReload);
            
            // Prevent multiple loads for the same selector unless force reload is requested
            if (loadedItems.has(selector) && !forceReload) {
                console.log('Items already loaded for selector:', selector);
                return;
            }
            
            // If force reload, remove from cache first
            if (forceReload && loadedItems.has(selector)) {
                loadedItems.delete(selector);
                console.log('Force reload requested, cleared cache for selector:', selector);
            }
            
            // Show loading state
            const select = document.querySelector(selector);
            if (select) {
                select.innerHTML = '<option value="">Loading items...</option>';
            }
            
            $.ajax({
                url: '/company/warehouse/central-store/available-items-for-requisition',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Items response:', response);
                    if (response.success && response.data) {
                        console.log('Found items:', response.data.length);
                        const select = document.querySelector(selector);
                        if (select) {
                            // Clear existing options except the first one
                            select.innerHTML = '<option value="">Select Item</option>';
                            
                            response.data.forEach(item => {
                                console.log('Adding item:', item);
                                const option = document.createElement('option');
                                option.value = item.id;
                                option.textContent = `${item.name} - ${item.category} (Available: ${item.quantity_available} ${item.unit})`;
                                option.setAttribute('data-price', item.unit_price || 0);
                                option.setAttribute('data-quantity', item.quantity_available || 0);
                                option.setAttribute('data-unit', item.unit || 'pcs');
                                select.appendChild(option);
                            });
                            
                            // Remove any existing event listeners to prevent duplicates
                            const newSelect = select.cloneNode(true);
                            select.parentNode.replaceChild(newSelect, select);
                            
                            // Add change event listener to update price and availability
                            newSelect.addEventListener('change', function() {
                                const selectedOption = this.options[this.selectedIndex];
                                console.log('Item select changed, selected option:', selectedOption);
                                
                                if (selectedOption && selectedOption.value) {
                                    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                                    const quantity = selectedOption.getAttribute('data-quantity') || 0;
                                    const unit = selectedOption.getAttribute('data-unit') || 'pcs';
                                    
                                    console.log('Item selected - Price:', price, 'Quantity:', quantity, 'Unit:', unit);
                                    
                                    // Update the price input
                                    const row = this.closest('tr');
                                    console.log('Row found:', row);
                                    
                                    // Debug: Show all elements in the row
                                    const allInputs = row.querySelectorAll('input');
                                    const allSelects = row.querySelectorAll('select');
                                    console.log('All inputs in row:', allInputs);
                                    console.log('All selects in row:', allSelects);
                                    
                                    // Find price input by ID pattern
                                    const selectId = this.id;
                                    const index = selectId.split('_').pop();
                                    console.log('Select ID:', selectId, 'Index:', index);
                                    
                                    const priceInput = document.getElementById(`edit_item_price_${index}`);
                                    console.log('Price input found:', priceInput);
                                    
                                    if (priceInput) {
                                        priceInput.value = price;
                                        console.log('Updated price input to:', price);
                                    } else {
                                        console.error('Price input not found for index:', index);
                                    }
                                    
                                    // Update the unit display
                                    const unitDisplay = document.getElementById(`edit_item_unit_${index}`);
                                    console.log('Unit display found:', unitDisplay);
                                    
                                    if (unitDisplay) {
                                        unitDisplay.textContent = unit;
                                        console.log('Updated unit display to:', unit);
                                        console.log('✅ Unit field auto-populated with:', unit);
                                    } else {
                                        console.error('Unit display not found for index:', index);
                                    }
                                    
                                    // Update availability text
                                    const availabilityText = row.querySelector('small.text-muted');
                                    if (availabilityText) {
                                        availabilityText.textContent = `Available: ${quantity} ${unit}`;
                                        availabilityText.style.color = '#28a745';
                                        console.log('Updated availability text');
                                    }
                                    
                                    // Set max quantity for the quantity input
                                    const quantityInput = document.getElementById(`edit_item_quantity_${index}`);
                                    console.log('Quantity input found:', quantityInput);
                                    
                                    if (quantityInput) {
                                        quantityInput.max = quantity;
                                        quantityInput.setAttribute('data-max-quantity', quantity);
                                        quantityInput.setAttribute('placeholder', `Max: ${quantity}`);
                                        quantityInput.style.borderColor = '#28a745';
                                        console.log('Set max quantity to:', quantity);
                                        
                                        // Remove existing event listeners to prevent duplicates
                                        const newQuantityInput = quantityInput.cloneNode(true);
                                        quantityInput.parentNode.replaceChild(newQuantityInput, quantityInput);
                                        
                                        // Add input event listener for quantity validation
                                        newQuantityInput.addEventListener('input', function() {
                                            const enteredQty = parseFloat(this.value) || 0;
                                            const maxQty = parseFloat(this.getAttribute('data-max-quantity')) || 0;
                                            
                                            if (enteredQty > maxQty) {
                                                this.value = maxQty;
                                                this.style.borderColor = '#dc3545';
                                                // Remove red border after 2 seconds
                                                setTimeout(() => {
                                                    this.style.borderColor = '#28a745';
                                                }, 2000);
                                            } else {
                                                this.style.borderColor = '#28a745';
                                            }
                                            
                                    // Trigger calculation without recursion
                                    const priceInput = row.querySelector('input[type="number"]:last-of-type');
                                    const totalSpan = row.querySelector('.item-total');
                                    if (priceInput && totalSpan) {
                                        const price = parseFloat(priceInput.value) || 0;
                                        const total = enteredQty * price;
                                        totalSpan.textContent = `GHS ${total.toFixed(2)}`;
                                    }
                                    
                                    // Check for duplicates after a short delay to allow item loading to complete
                                    setTimeout(() => {
                                        checkForDuplicateItems();
                                    }, 200);
                                        });
                                    }
                                    
                                    // Trigger calculation
                                    if (priceInput) {
                                        priceInput.dispatchEvent(new Event('input'));
                                    }
                                } else {
                                    console.log('No item selected or invalid selection');
                                    // Reset fields if no item selected
                                    const row = this.closest('tr');
                                    const priceInput = row.querySelector('input[type="number"]:last-of-type');
                                    const quantityInput = row.querySelector('input[type="number"]:first-of-type');
                                    const availabilityText = row.querySelector('small.text-muted');
                                    
                                    if (priceInput) priceInput.value = '';
                                    if (quantityInput) {
                                        quantityInput.value = '';
                                        quantityInput.removeAttribute('data-max-quantity');
                                        quantityInput.style.borderColor = '';
                                    }
                                    if (availabilityText) {
                                        availabilityText.textContent = 'Available: Loading...';
                                        availabilityText.style.color = '';
                                    }
                                }
                            });
                            
                            // Mark this selector as loaded after successful loading
                            loadedItems.add(selector);
                        }
                    } else {
                        console.error('No items data in response:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load available items:', error);
                    console.error('Response:', xhr.responseText);
                }
            });
        }
        
        // Load project managers for edit modal
        function loadEditProjectManagers() {
            console.log('Loading project managers for edit modal...');
            $.ajax({
                url: '{{ url("company/warehouse/requisitions/project-managers") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Project managers response:', response);
                    if (response.success) {
                        const select = document.getElementById('edit_project_manager');
                        if (select) {
                            select.innerHTML = '<option value="">Select Project Manager</option>';
                            console.log('Found', response.data.length, 'project managers');
                            response.data.forEach(manager => {
                                console.log('Adding project manager:', manager);
                                console.log('Manager name field:', manager.name);
                                console.log('Manager fullname field:', manager.fullname);
                                // Use 'name' field since that's what the API returns
                                const displayName = manager.name || manager.fullname || 'Unknown';
                                console.log('Final display name:', displayName);
                                select.innerHTML += `<option value="${manager.id}">${displayName}</option>`;
                            });
                            
                            // Set the selected value after populating the dropdown
                            const currentProjectManagerId = window.currentRequisitionData?.project_manager_id;
                            if (currentProjectManagerId) {
                                select.value = currentProjectManagerId;
                                console.log('Set project manager value to:', currentProjectManagerId);
                                // Trigger change event
                                $(select).trigger('change');
                            }
                        } else {
                            console.error('Project manager select element not found');
                        }
                    } else {
                        console.error('Failed to load project managers:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading project managers:', error);
                    console.error('Response:', xhr.responseText);
                }
            });
        }
        
        // Load team leaders for edit modal
        function loadEditTeamLeaders() {
            console.log('Loading team leaders for edit modal...');
            $.ajax({
                url: '{{ url("company/warehouse/requisitions/team-leaders") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Team leaders response:', response);
                    if (response.success) {
                        const select = document.getElementById('edit_team_leader');
                        if (select) {
                            select.innerHTML = '<option value="">Select Team Leader</option>';
                            console.log('Found', response.data.length, 'team leaders');
                            response.data.forEach(leader => {
                                console.log('Adding team leader:', leader);
                                // Use 'name' field since that's what the API returns
                                const displayName = leader.name || leader.full_name || 'Unknown';
                                select.innerHTML += `<option value="${leader.id}">${displayName}</option>`;
                            });
                            
                            // Set the selected value after populating the dropdown
                            const currentTeamLeaderId = window.currentRequisitionData?.team_leader_id;
                            if (currentTeamLeaderId) {
                                select.value = currentTeamLeaderId;
                                console.log('Set team leader value to:', currentTeamLeaderId);
                                // Trigger change event
                                $(select).trigger('change');
                            }
                        } else {
                            console.error('Team leader select element not found');
                        }
                    } else {
                        console.error('Failed to load team leaders:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading team leaders:', error);
                    console.error('Response:', xhr.responseText);
                }
            });
        }
        
        // Add new item row in edit modal
        function addNewEditItem() {
            const tbody = document.getElementById('editItemsTableBody');
            const index = tbody.children.length;
            
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <select class="form-select item-select" id="edit_item_select_${index}" required>
                        <option value="">Select Item</option>
                        <!-- Items will be loaded dynamically -->
                    </select>
                    <small class="text-muted">Available: Loading...</small>
                </td>
                <td>
                    <input type="number" class="form-control" id="edit_item_quantity_${index}" min="1" max="1000" required>
                </td>
                <td>
                    <span class="form-control-plaintext" id="edit_item_unit_${index}">-</span>
                </td>
                <td>
                    <input type="number" class="form-control" id="edit_item_price_${index}" min="0" step="0.01" required>
                </td>
                <td>
                    <span class="item-total fw-bold" id="edit_item_total_${index}" style="color: #1cc88a;">GHS 0.00</span>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeEditItem(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            
            // Add change event listener to check for duplicate items
            const itemSelect = document.getElementById(`edit_item_select_${index}`);
            itemSelect.addEventListener('change', function() {
                // Check for duplicates after a short delay to ensure the item is fully loaded
                setTimeout(() => {
                    checkForDuplicateItems();
                }, 500);
            });
            
            // Load available items for the new select
            loadAvailableItemsForEdit(`#edit_item_select_${index}`);
            
            // Check for duplicates after adding new item
            setTimeout(() => {
                checkForDuplicateItems();
            }, 1000);
            
            // Ensure items are loaded for this new row
            setTimeout(() => {
                const select = document.getElementById(`edit_item_select_${index}`);
                if (select && select.innerHTML.includes('Loading items...')) {
                    console.log(`Ensuring items are loaded for new row ${index}`);
                    const selector = `#edit_item_select_${index}`;
                    loadedItems.delete(selector);
                    loadAvailableItemsForEdit(selector);
                }
            }, 1500);
            
            // Add event listeners for calculations with delay to prevent infinite loops
            setTimeout(() => {
                const quantityInput = document.getElementById(`edit_item_quantity_${index}`);
                const priceInput = document.getElementById(`edit_item_price_${index}`);
                const totalSpan = document.getElementById(`edit_item_total_${index}`);
                
                if (quantityInput && priceInput && totalSpan) {
                    const calculateTotal = () => {
                        const qty = parseFloat(quantityInput.value) || 0;
                        const price = parseFloat(priceInput.value) || 0;
                        const total = qty * price;
                        console.log(`Calculating total for item ${index}: qty=${qty}, price=${price}, total=${total}`);
                        totalSpan.textContent = `GHS ${total.toFixed(2)}`;
                        updateEditTotalAmount();
                    };
                    
                    // Remove existing event listeners to prevent duplicates
                    const newQuantityInput = quantityInput.cloneNode(true);
                    const newPriceInput = priceInput.cloneNode(true);
                    quantityInput.parentNode.replaceChild(newQuantityInput, quantityInput);
                    priceInput.parentNode.replaceChild(newPriceInput, priceInput);
                    
                    // Add quantity validation
                    newQuantityInput.addEventListener('input', function() {
                        const enteredQty = parseFloat(this.value) || 0;
                        const maxQty = parseFloat(this.getAttribute('data-max-quantity')) || 0;
                        
                        if (maxQty > 0 && enteredQty > maxQty) {
                            this.value = maxQty;
                            this.style.borderColor = '#dc3545';
                            // Remove red border after 2 seconds
                            setTimeout(() => {
                                this.style.borderColor = '#28a745';
                            }, 2000);
                        }
                        
                        // Calculate total without recursion
                        const price = parseFloat(newPriceInput.value) || 0;
                        const total = enteredQty * price;
                        console.log(`Item ${index} calculation: qty=${enteredQty}, price=${price}, total=${total}`);
                        totalSpan.textContent = `GHS ${total.toFixed(2)}`;
                        updateEditTotalAmount();
                    });
                    
                    // Add price input event listener
                    newPriceInput.addEventListener('input', function() {
                        const enteredQty = parseFloat(newQuantityInput.value) || 0;
                        const price = parseFloat(this.value) || 0;
                        const total = enteredQty * price;
                        console.log(`Price change for item ${index}: qty=${enteredQty}, price=${price}, total=${total}`);
                        totalSpan.textContent = `GHS ${total.toFixed(2)}`;
                        updateEditTotalAmount();
                    });
                }
            }, 100);
        }
        
        // Remove item from edit modal
        function removeEditItem(index) {
            const tbody = document.getElementById('editItemsTableBody');
            const row = document.querySelector(`[id="edit_item_select_${index}"]`).closest('tr');
            if (row) {
                row.remove();
                updateEditTotalAmount();
            }
        }
        
        // Update total amount in edit modal
        function updateEditTotalAmount() {
            let totalAmount = 0;
            const itemInputs = document.querySelectorAll('[id^="edit_item_quantity_"]');
            console.log('Updating total amount, found', itemInputs.length, 'quantity inputs');
            
            itemInputs.forEach((input, index) => {
                const quantity = parseFloat(input.value) || 0;
                const priceInput = document.getElementById(input.id.replace('quantity', 'price'));
                const price = parseFloat(priceInput ? priceInput.value : 0) || 0;
                const itemTotal = quantity * price;
                totalAmount += itemTotal;
                console.log(`Item ${index}: qty=${quantity}, price=${price}, total=${itemTotal}`);
            });
            
            console.log('Total amount calculated:', totalAmount);
            const totalSpan = document.getElementById('edit_total_amount');
            if (totalSpan) {
                totalSpan.textContent = `GHS ${totalAmount.toFixed(2)}`;
                console.log('Updated total span to:', totalSpan.textContent);
            } else {
                console.error('Total span not found');
            }
        }
        
        // Save requisition changes
        function saveRequisitionChanges(requisitionId) {
            const title = document.getElementById('edit_title').value;
            const requisitionDate = document.getElementById('edit_requisition_date').value;
            const requiredDate = document.getElementById('edit_required_date').value;
            const department = document.getElementById('edit_department').value;
            const projectManager = document.getElementById('edit_project_manager').value;
            const teamLeader = document.getElementById('edit_team_leader').value;
            const priority = document.getElementById('edit_priority').value;
            
            // Collect updated items
            const items = [];
            let totalAmount = 0;
            
            // Get all item inputs
            const itemSelects = document.querySelectorAll('[id^="edit_item_select_"]');
            console.log('Found item selects:', itemSelects.length);
            console.log('Item selects:', itemSelects);
            
            itemSelects.forEach((select, selectIndex) => {
                console.log(`Processing select ${selectIndex}:`, select.id);
                const index = select.id.replace('edit_item_select_', '');
                const selectedOption = select.options[select.selectedIndex];
                const quantity = parseFloat(document.getElementById(`edit_item_quantity_${index}`).value) || 0;
                const unit = document.getElementById(`edit_item_unit_${index}`).textContent;
                const unitPrice = parseFloat(document.getElementById(`edit_item_price_${index}`).value) || 0;
                const total = quantity * unitPrice;
                
                console.log(`Item ${selectIndex} - selectedOption:`, selectedOption);
                console.log(`Item ${selectIndex} - quantity:`, quantity);
                console.log(`Item ${selectIndex} - unitPrice:`, unitPrice);
                
                if (selectedOption && selectedOption.value && quantity > 0) {
                    const itemName = selectedOption.textContent.split(' - ')[0]; // Get just the item name
                    const maxQuantity = parseInt(selectedOption.getAttribute('data-quantity')) || 1000;
                    console.log(`Item ${selectIndex} - itemName:`, itemName);
                    console.log(`Item ${selectIndex} - maxQuantity:`, maxQuantity);
                    
                    // Validate quantity doesn't exceed available stock
                    if (quantity > maxQuantity) {
                        Swal.showValidationMessage(`Quantity cannot exceed available stock (${maxQuantity})`);
                        return false;
                    }
                    
                    const itemData = {
                        item_name: itemName,
                        item_id: selectedOption.value,
                        quantity: quantity,
                        unit: unit,
                        unit_price: unitPrice,
                        total: total
                    };
                    
                    console.log(`Adding item ${selectIndex}:`, itemData);
                    items.push(itemData);
                    totalAmount += total;
                }
            });
            
            // Validate required fields
            if (!title.trim()) {
                Swal.showValidationMessage('Title is required');
                return false;
            }
            
            if (items.length === 0) {
                Swal.showValidationMessage('At least one item is required');
                return false;
            }
            
            const result = {
                title: title,
                requisition_date: requisitionDate,
                required_date: requiredDate,
                department: department,
                project_manager_id: projectManager,
                team_leader_id: teamLeader,
                priority: priority,
                items: items,
                total_amount: totalAmount
            };
            
            console.log('Collected data for save:', result);
            console.log('Items collected:', items);
            console.log('Total amount:', totalAmount);
            
            return result;
        }
        
        // Save requisition update
        function saveRequisitionUpdate(requisitionId, data) {
            console.log('Saving requisition update:', {
                requisitionId: requisitionId,
                data: data,
                itemsCount: data.items ? data.items.length : 0
            });
            
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `{{ url('company/management/requisitions') }}/${requisitionId}/update`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        title: data.title,
                        requisition_date: data.requisition_date,
                        required_date: data.required_date,
                        department: data.department,
                        project_manager_id: data.project_manager_id,
                        team_leader_id: data.team_leader_id,
                        priority: data.priority,
                        items: data.items,
                        total_amount: data.total_amount
                    },
                    success: function(response) {
                        console.log('Save response:', response);
                        if (response.success) {
                            resolve(response);
                        } else {
                            reject(new Error(response.message || 'Failed to update requisition'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Save error:', error);
                        console.error('Response:', xhr.responseText);
                        console.error('Status:', xhr.status);
                        
                        let errorMessage = 'Failed to update requisition: ' + error;
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            console.error('Could not parse error response:', e);
                        }
                        
                        reject(new Error(errorMessage));
                    }
                });
            });
        }

        // Updated reject function
        function rejectRequisition(id, reason, comments) {
            console.log('=== REJECT REQUISITION FUNCTION START ===');
            console.log('Function called with parameters:');
            console.log('ID:', id);
            console.log('Reason:', reason);
            console.log('Comments:', comments);
            
            const ajaxData = {
                    _token: '{{ csrf_token() }}',
                    reason: reason,
                    comments: comments
            };
            
            const ajaxUrl = `{{ url('company/management/requisitions') }}/${id}/reject`;
            
            console.log('AJAX Request Details:');
            console.log('URL:', ajaxUrl);
            console.log('Method: POST');
            console.log('Data being sent:', ajaxData);
            console.log('CSRF Token:', '{{ csrf_token() }}');
            
            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: ajaxData,
                beforeSend: function(xhr) {
                    console.log('=== AJAX BEFORE SEND ===');
                    console.log('XHR object:', xhr);
                    console.log('Request headers will be sent');
                },
                success: function(response) {
                    console.log('=== AJAX SUCCESS ===');
                    console.log('Response received:', response);
                    console.log('Response type:', typeof response);
                    console.log('Response success:', response.success);
                    console.log('Response message:', response.message);
                    
                    if (response.success) {
                        console.log('Success response - showing success message');
                        Swal.fire('Success!', 'Requisition rejected successfully', 'success');
                        console.log('Calling loadRequisitions to refresh the list');
                        loadRequisitions(); // Refresh the list
                    } else {
                        console.log('Response indicates failure:', response.message);
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('=== AJAX ERROR ===');
                    console.log('XHR object:', xhr);
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response Status:', xhr.status);
                    console.log('Response Status Text:', xhr.statusText);
                    console.log('Response Text:', xhr.responseText);
                    
                    try {
                        const responseJson = JSON.parse(xhr.responseText);
                        console.log('Parsed Response JSON:', responseJson);
                        console.log('Error message from server:', responseJson.message);
                    } catch (e) {
                        console.log('Could not parse response as JSON:', e);
                    }
                    
                    let errorMessage = 'Failed to reject requisition';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                        console.log('Using server error message:', errorMessage);
                    } else if (xhr.status === 500) {
                        errorMessage = 'Internal Server Error (500) - Check server logs';
                        console.log('500 Internal Server Error detected');
                    } else if (xhr.status === 422) {
                        errorMessage = 'Validation Error (422) - Check request data';
                        console.log('422 Validation Error detected');
                    }
                    
                    console.log('Final error message to show:', errorMessage);
                    Swal.fire('Error!', errorMessage, 'error');
                },
                complete: function(xhr, status) {
                    console.log('=== AJAX COMPLETE ===');
                    console.log('Final status:', status);
                    console.log('Final XHR status:', xhr.status);
                    console.log('Request completed');
                }
            });
            
            console.log('=== REJECT REQUISITION FUNCTION END ===');
    }

    // editRequisition function - fetch requisition data and show edit modal
    function editRequisition(id) {
        $.ajax({
            url: `{{ url('company/management/requisitions') }}/${id}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    const requisition = response.data;
                    
                    console.log('Edit modal - Full requisition data:', requisition);
                    console.log('Edit modal - Requestor:', requisition.requestor);
                    console.log('Edit modal - Project Manager:', requisition.projectManager);
                    console.log('Edit modal - Items:', requisition.items);
                    
                    showEditModal(requisition);
                } else {
                    Swal.fire('Error!', 'Failed to load requisition details', 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Failed to load requisition details', 'error');
            }
        });
    }
</script>
@endsection
