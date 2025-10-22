@extends('layouts.vertical', ['page_title' => 'Purchase Order Management Approval'])

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
@endsection

@section('content')
<div class="row mt-4">
    <!-- Pending Approvals Card -->
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Pending Approvals</p>
                        <h4 class="mb-0 pending-approvals-count">0</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- High Priority Card -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded bg-soft-danger">
                            <span class="avatar-title rounded">
                                <i class="ri-alarm-warning-line font-20 text-danger"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-1">High Priority</p>
                        <h4 class="mb-0 high-priority-count">0</h4>
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
                        <div class="avatar-sm rounded bg-soft-info">
                            <span class="avatar-title rounded">
                                <i class="ri-close-line font-20 text-info"></i>
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
</div>

    <!-- PO Approval Queue Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ri-file-list-line me-2"></i>
                            Purchase Order Management Approval Queue
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshApprovals()">
                                <i class="ri-refresh-line me-1"></i>Refresh
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="bulkApprove()">
                                <i class="ri-checkbox-multiple-line me-1"></i>Bulk Approve
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="exportApprovals()">
                                <i class="ri-download-line me-1"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
            <table class="table table-hover mb-0" id="poApprovalTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                            <input type="checkbox" class="form-check-input" id="selectAllPOs">
                                    </th>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Created By</th>
                        <th>Total Amount</th>
                        <th>Invoices</th>
                                    <th>Priority</th>
                                    <th>Date Created</th>
                                    <th>Status</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                <tbody id="poApprovalTableBody">
                    <!-- Loading state -->
                    <tr id="loadingState" style="display: none;">
                        <td colspan="10" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                        </div>
                                <h5 class="text-muted mb-2">Loading Purchase Orders...</h5>
                                <p class="text-muted mb-0">Please wait while we fetch the data</p>
                                        </div>
                                    </td>
                                </tr>
                    <!-- Empty state with large icon -->
                    <tr id="emptyState">
                        <td colspan="10" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <div class="mb-4">
                                    <i class="ri-shopping-cart-line text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted mb-2">No Purchase Orders pending approval</h5>
                                <p class="text-muted mb-0">Purchase orders requiring external approval will appear here</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light" id="paginationFooter" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted" id="paginationInfo">
                            <small>Loading...</small>
                        </div>
                        <nav aria-label="PO Approval pagination" id="paginationNav">
                            <!-- Dynamic pagination will be inserted here -->
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
        loadPOStatistics();
        
        // Load purchase orders
        loadPurchaseOrders();
        
        // Initialize checkbox functionality
        $('#selectAllPOs').on('change', function() {
            $('.po-checkbox').prop('checked', this.checked);
        });
        
        $('.po-checkbox').on('change', function() {
            if (!this.checked) {
                $('#selectAllPOs').prop('checked', false);
            }
        });
    });

    function loadPOStatistics() {
        // Load PO statistics from the controller
        $.ajax({
            url: '{{ route("management.po_approval.data") }}',
            method: 'POST',
            data: { 
                ajax: true,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.stats) {
                    $('.pending-approvals-count').text(response.stats.external_approval || 0);
                    $('.high-priority-count').text(response.stats.high_priority || 0);
                    $('.approved-count').text(response.stats.approved || 0);
                    $('.rejected-count').text(response.stats.rejected || 0);
                }
            }
        });
    }

    function loadPurchaseOrders(page = 1) {
        console.log('Loading purchase orders from management page...', 'Page:', page);
        console.log('Route URL:', '{{ route("management.po_approval.data") }}');
        
        // Show loading spinner
        $('#loadingState').show();
        $('#emptyState').hide();
        $('#paginationFooter').hide();
        $('#poApprovalTableBody tr:not(#loadingState):not(#emptyState)').remove();
        
        $.ajax({
            url: '{{ route("management.po_approval.data") }}',
            method: 'POST',
            data: { 
                ajax: true,
                page: page,
                per_page: 10,
                _token: '{{ csrf_token() }}'
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Management page response:', response);
                // Hide loading spinner
                $('#loadingState').hide();
                
                if (response && response.success && response.data) {
                    console.log('POs found:', response.data.data ? response.data.data.length : response.data.length);
                    populatePOTable(response.data);
                } else {
                    console.log('No data in response or response format issue');
                    console.log('Response structure:', response);
                    // Show empty state if no data
                    $('#emptyState').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error Details:');
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response Status:', xhr.status);
                console.error('Response Text:', xhr.responseText);
                console.error('Response Headers:', xhr.getAllResponseHeaders());
                
                // Hide loading spinner on error
                $('#loadingState').hide();
                $('#emptyState').show();
                
                // Check if it's an authentication issue
                if (xhr.status === 401) {
                    console.error('Authentication issue - user not logged in');
                } else if (xhr.status === 404) {
                    console.error('Route not found');
                } else if (xhr.status === 500) {
                    console.error('Server error');
                }
            }
        });
    }

    function populatePOTable(response) {
        const tbody = $('#poApprovalTableBody');
        
        // Hide loading and empty states
        $('#loadingState').hide();
        $('#emptyState').hide();
        
        // Clear existing data rows (but keep loading and empty states)
        tbody.find('tr:not(#loadingState):not(#emptyState)').remove();
        
        // Handle the response structure from POApprovalController
        const purchaseOrders = response.data || [];
        const pagination = response.meta || null;
        
        if (!purchaseOrders || purchaseOrders.length === 0) {
            $('#emptyState').show();
            $('#paginationFooter').hide();
            return;
        }
        
        purchaseOrders.forEach(function(po) {
            // Count invoices
            const invoiceCount = po.invoices ? po.invoices.length : 0;
            const invoiceDisplay = invoiceCount > 0 ? 
                `<span class="badge bg-success">${invoiceCount} file${invoiceCount > 1 ? 's' : ''}</span>` : 
                `<span class="badge bg-secondary">No files</span>`;
            
            const row = `
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input po-checkbox" value="${po.id}">
                    </td>
                    <td><strong>${po.po_number}</strong></td>
                    <td>${po.supplier ? po.supplier.company_name : 'Unknown'}</td>
                    <td>${getUserName(po.createdBy || po.user)}</td>
                    <td><strong>GHS ${parseFloat(po.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong></td>
                    <td>${invoiceDisplay}</td>
                    <td><span class="badge bg-secondary">Standard</span></td>
                    <td>${formatDate(po.created_at)}</td>
                    <td><span class="badge bg-info">External Approval</span></td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-primary" onclick="viewPODetails(${po.id})" title="View Details">
                                <i class="ri-eye-line"></i>
                            </button>
                            ${po.status === 'external_approval' ? 
                                `<button class="btn btn-sm btn-outline-warning" onclick="revertPO(${po.id})" title="Revert to Created">
                                    <i class="ri-arrow-go-back-line"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="approvePO(${po.id})" title="Approve">
                                    <i class="ri-check-line"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="rejectPO(${po.id})" title="Reject">
                                    <i class="ri-close-line"></i>
                                </button>` :
                                `<button class="btn btn-sm btn-outline-success" onclick="approvePO(${po.id})" title="Approve">
                                    <i class="ri-check-line"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="rejectPO(${po.id})" title="Reject">
                                    <i class="ri-close-line"></i>
                                </button>`
                            }
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
        
        // Add pagination if available
        if (pagination) {
            renderPagination(pagination);
        } else {
            $('#paginationFooter').hide();
        }
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

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString();
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function getUserName(user) {
        if (!user) return 'Unknown';
        
        // User model uses 'fullname' field
        if (user.fullname && user.fullname.trim() !== '') return user.fullname.trim();
        
        // Fallback to other possible name fields
        if (user.name && user.name.trim() !== '') return user.name.trim();
        if (user.full_name && user.full_name.trim() !== '') return user.full_name.trim();
        
        // Try combining first and last name
        if (user.first_name && user.last_name) {
            const firstName = user.first_name.trim();
            const lastName = user.last_name.trim();
            if (firstName !== '' && lastName !== '') {
                return firstName + ' ' + lastName;
            }
        }
        
        // Try individual names
        if (user.first_name && user.first_name.trim() !== '') return user.first_name.trim();
        if (user.last_name && user.last_name.trim() !== '') return user.last_name.trim();
        
        // Try email as fallback
        if (user.personal_email && user.personal_email.trim() !== '') return user.personal_email.trim();
        if (user.email && user.email.trim() !== '') return user.email.trim();
        
        return 'Unknown';
    }

    function showPendingApprovals() {
        // Scroll to the approval table
        document.getElementById('requisitionApprovalTable').scrollIntoView({ behavior: 'smooth' });
    }

    function showUrgentApprovals() {
        // Filter table to show only urgent items
        $('#poApprovalTableBody tr').each(function() {
            const priority = $(this).find('td:nth-child(6) .badge').text();
            if (priority === 'Urgent') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        Swal.fire({
            title: 'Urgent Approvals',
            text: 'Showing only urgent purchase orders requiring immediate attention.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    }

    function showAnalytics() {
        Swal.fire({
            title: 'Approval Analytics',
            html: `
                <div class="text-start">
                    <h6>Approval Performance</h6>
                    <ul class="list-unstyled">
                        <li><strong>Average Processing Time:</strong> 2.3 days</li>
                        <li><strong>Approval Rate:</strong> 98.5%</li>
                        <li><strong>Rejection Rate:</strong> 1.5%</li>
                        <li><strong>Pending Items:</strong> 23</li>
                    </ul>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }

    function refreshApprovals() {
        // Show loading state
        Swal.fire({
            title: 'Refreshing...',
            text: 'Loading latest approval data',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Reload data
        loadPOStatistics();
        loadPurchaseOrders();
        
        setTimeout(() => {
            Swal.close();
            Swal.fire('Success!', 'Approval data refreshed successfully.', 'success');
        }, 1000);
    }

    function bulkApprove() {
        const selectedPOs = $('.po-checkbox:checked');
        
        if (selectedPOs.length === 0) {
            Swal.fire({
                title: 'No Selection',
                text: 'Please select at least one purchase order to approve.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        const poIds = selectedPOs.map(function() {
            return $(this).val();
        }).get();
        
        Swal.fire({
            title: 'Bulk Approval',
            text: `Are you sure you want to approve ${selectedPOs.length} purchase order(s)?`,
            input: 'textarea',
            inputLabel: 'Approval Notes (Optional)',
            inputPlaceholder: 'Add any notes for the approval...',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve All',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("management.po_approval.bulk_approve") }}',
                    method: 'POST',
                    data: {
                        po_ids: poIds,
                        notes: result.value || '',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success');
                            loadPOStatistics();
                            loadPurchaseOrders();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Bulk approve error:', xhr);
                        Swal.fire('Error!', 'An error occurred while processing the request.', 'error');
                    }
                });
            }
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
                        rejection_reason: result.value,
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
        $.ajax({
            url: `{{ url('company/management/requisitions') }}/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const req = response.data;
                    Swal.fire({
                        title: 'Requisition Details',
                        html: `
                            <div class="text-start">
                                <h6>${req.requisition_number}</h6>
                                <p><strong>Title:</strong> ${req.title}</p>
                                <p><strong>Requested By:</strong> ${req.requestor_name || 'Unknown'}</p>
                                <p><strong>Project Manager:</strong> ${req.project_manager_name || 'Not Assigned'}</p>
                                <p><strong>Priority:</strong> ${req.priority}</p>
                                <p><strong>Status:</strong> ${req.status}</p>
                                <p><strong>Created:</strong> ${formatDate(req.created_at)}</p>
                                <p><strong>Notes:</strong> ${req.notes || 'None'}</p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function() {
                Swal.fire('Error!', 'Failed to load requisition details', 'error');
            }
        });
    }

    function exportApprovals() {
        // Show loading state
        Swal.fire({
            title: 'Exporting...',
            text: 'Preparing approval data for export',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Make AJAX request to export data
        $.ajax({
            url: '{{ route("management.po_approval.data") }}',
            method: 'POST',
            data: { 
                ajax: true,
                export: true,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response && response.success && response.data) {
                    // Create CSV content
                    let csvContent = 'PO Number,Supplier,Created By,Total Amount,Status,Date Created,Invoice Count\n';
                    
                    const purchaseOrders = response.data.data || response.data;
                    purchaseOrders.forEach(function(po) {
                        const invoiceCount = po.invoices ? po.invoices.length : 0;
                        csvContent += `"${po.po_number}","${po.supplier ? po.supplier.company_name : 'Unknown'}","${po.user ? po.user.name : 'Unknown'}","${po.total_amount || 0}","External Approval","${formatDate(po.created_at)}","${invoiceCount}"\n`;
                    });
                    
                    // Download CSV
                    const blob = new Blob([csvContent], { type: 'text/csv' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `po_approvals_${new Date().toISOString().split('T')[0]}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                    
                    Swal.close();
                Swal.fire('Success!', 'Approval data exported successfully.', 'success');
                } else {
                    Swal.close();
                    Swal.fire('Error!', 'No data available for export.', 'error');
                }
            },
            error: function(xhr) {
                Swal.close();
                console.error('Export error:', xhr);
                Swal.fire('Error!', 'Failed to export approval data.', 'error');
            }
        });
    }

    function viewPODetails(poId) {
        Swal.fire({
            title: 'Purchase Order Details',
            html: `
                <div class="text-start">
                    <h6>PO-2024-00${poId}</h6>
                    <p><strong>Vendor:</strong> Sample Vendor</p>
                    <p><strong>Amount:</strong> GHS 15,500.00</p>
                    <p><strong>Status:</strong> Pending Approval</p>
                    <p><strong>Created:</strong> 2024-01-15</p>
                    <p><strong>Description:</strong> Office supplies and equipment</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }

    function approvePO(poId) {
        Swal.fire({
            title: 'Approve Purchase Order',
            text: `Are you sure you want to approve PO-2024-00${poId}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Success!', 'Purchase order approved successfully.', 'success');
                // Here you would typically make an API call to approve the PO
            }
        });
    }

    function rejectPO(poId) {
        Swal.fire({
            title: 'Reject Purchase Order',
            input: 'textarea',
            inputLabel: 'Reason for rejection:',
            inputPlaceholder: 'Please provide a reason for rejection...',
            showCancelButton: true,
            confirmButtonText: 'Reject',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Success!', 'Purchase order rejected successfully.', 'success');
                // Here you would typically make an API call to reject the PO
            }
        });
    }

    // Pagination function
    function renderPagination(pagination) {
        if (!pagination) {
            $('#paginationFooter').hide();
            return;
        }
        
        // Ensure pagination data is valid with fallbacks
        const currentPage = parseInt(pagination.current_page) || 1;
        const perPage = parseInt(pagination.per_page) || 10;
        const total = parseInt(pagination.total) || 0;
        const lastPage = parseInt(pagination.last_page) || 1;
        
        // Only show pagination if there are multiple pages
        if (lastPage <= 1) {
            $('#paginationFooter').show();
            $('#paginationInfo').html(`<small>Showing ${total} of ${total} entries</small>`);
            $('#paginationNav').html('');
            return;
        }
        
        // Show pagination footer
        $('#paginationFooter').show();
        
        // Update pagination info with safe calculations
        const start = total > 0 ? ((currentPage - 1) * perPage) + 1 : 0;
        const end = Math.min(currentPage * perPage, total);
        $('#paginationInfo').html(`<small>Showing ${start} to ${end} of ${total} entries</small>`);
        
        // Create pagination HTML
        let paginationHtml = '<ul class="pagination pagination-sm mb-0">';
        
        // Previous button
        if (currentPage > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="loadPurchaseOrders(${currentPage - 1}); return false;">Previous</a></li>`;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }
        
        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(lastPage, currentPage + 2);
        
        if (startPage > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="loadPurchaseOrders(1); return false;">1</a></li>`;
            if (startPage > 2) {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === currentPage ? 'active' : '';
            paginationHtml += `<li class="page-item ${activeClass}"><a class="page-link" href="#" onclick="loadPurchaseOrders(${i}); return false;">${i}</a></li>`;
        }
        
        if (endPage < lastPage) {
            if (endPage < lastPage - 1) {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="loadPurchaseOrders(${lastPage}); return false;">${lastPage}</a></li>`;
        }
        
        // Next button
        if (currentPage < lastPage) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="loadPurchaseOrders(${currentPage + 1}); return false;">Next</a></li>`;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }
        
        paginationHtml += '</ul>';
        
        $('#paginationNav').html(paginationHtml);
    }

    // PO-specific action functions
    function viewPODetails(poId) {
        // Load PO details with invoices
        $.ajax({
            url: '{{ route("management.po_approval.data") }}',
            method: 'POST',
            data: { 
                ajax: true,
                po_id: poId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response && response.success && response.data) {
                    const po = response.data.data ? response.data.data[0] : response.data[0];
                    if (po) {
                        showPODetailsModal(po);
                    }
                }
            },
            error: function(xhr) {
                console.error('Error loading PO details:', xhr);
                Swal.fire('Error', 'Failed to load PO details', 'error');
            }
        });
    }

    function showPODetailsModal(po) {
        // Store PO data for printing
        currentPODetails = po;
        
        // Parse items if it's a JSON string
        let items = [];
        if (po.items) {
            if (typeof po.items === 'string') {
                try {
                    items = JSON.parse(po.items);
                } catch (e) {
                    items = [];
                }
            } else if (Array.isArray(po.items)) {
                items = po.items;
            }
        }

        // Create items table
        let itemsHtml = '';
        if (items && items.length > 0) {
            // Check if any item has re-order data
            console.log('Approval Page - Items:', items);
            const hasReorders = items.some(item => {
                console.log('Checking item:', item.name, 'is_reorder:', item.is_reorder);
                return item.is_reorder === true || item.is_reorder === 1 || item.is_reorder === '1';
            });
            console.log('Has reorders:', hasReorders);
            
            itemsHtml = `
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-header bg-primary text-white border-0">
                        <h6 class="mb-0"><i class="ri-shopping-cart-line me-2"></i>Purchase Order Items</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0 po-items-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 py-3 px-4"><i class="ri-product-hunt-line me-1 text-primary"></i>Item Name</th>
                                        <th class="border-0 py-3 px-4 text-center"><i class="ri-number-1 me-1 text-primary"></i>Quantity</th>
                                        <th class="border-0 py-3 px-4 text-end"><i class="ri-money-dollar-circle-line me-1 text-primary"></i>Unit Price</th>
                                        <th class="border-0 py-3 px-4 text-end"><i class="ri-calculator-line me-1 text-primary"></i>Total</th>
                                        ${hasReorders ? '<th class="border-0 py-3 px-4"><i class="ri-repeat-line me-1 text-primary"></i>Re-order Info</th>' : ''}
                                    </tr>
                                </thead>
                                <tbody>
            `;
            items.forEach(function(item, index) {
                const total = parseFloat(item.quantity || 0) * parseFloat(item.unit_price || 0);
                const isReorderItem = item.is_reorder === true || item.is_reorder === 1 || item.is_reorder === '1';
                
                let reorderInfo = '';
                if (hasReorders) {
                    if (isReorderItem) {
                        reorderInfo = `
                            <td class="py-3 px-4">
                                <div class="badge bg-warning text-dark mb-2 d-block">
                                    <i class="ri-repeat-line me-1"></i> Re-order
                                </div>
                                ${item.batch_number ? `<div class="text-muted small mb-1"><strong>Batch:</strong> ${item.batch_number}</div>` : ''}
                                ${item.reorder_reason ? `<div class="text-muted small"><strong>Reason:</strong> ${item.reorder_reason}</div>` : ''}
                            </td>`;
                    } else {
                        reorderInfo = '<td class="py-3 px-4"><span class="badge bg-success">New Item</span></td>';
                    }
                }
                
                itemsHtml += `
                    <tr class="po-item-row">
                        <td class="py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="ri-shopping-bag-line text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">${item.name || 'N/A'}</h6>
                                    <small class="text-muted">Item #${index + 1}</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">
                                ${item.quantity || '0'}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-end">
                            <span class="fw-semibold text-dark">
                                GHS ${parseFloat(item.unit_price || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-end">
                            <span class="fw-bold text-success fs-6">
                                GHS ${total.toLocaleString('en-US', {minimumFractionDigits: 2})}
                            </span>
                        </td>
                        ${reorderInfo}
                    </tr>
                `;
            });
            itemsHtml += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
        } else {
            itemsHtml = `
                <div class="card mb-3">
                    <div class="card-body text-center py-4">
                        <i class="ri-shopping-cart-line text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2 mb-0">No items found</p>
                    </div>
                </div>
            `;
        }

        // Create totals section
        const subtotal = parseFloat(po.subtotal || 0);
        const taxAmount = parseFloat(po.tax_amount || 0);
        const totalAmount = parseFloat(po.total_amount || 0);
        
        let totalsHtml = `
            <div class="card mb-3">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 text-dark"><i class="ri-calculator-line me-2"></i>Financial Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="fw-medium"><i class="ri-subtract-line me-1"></i>Subtotal:</td>
                                            <td class="text-end fw-bold">GHS ${subtotal.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium"><i class="ri-percent-line me-1"></i>Tax (${po.tax_rate || 0}%):</td>
                                            <td class="text-end">GHS ${taxAmount.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium"><i class="ri-add-line me-1"></i>Total:</td>
                                            <td class="text-end">GHS ${totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td class="fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Total Amount:</td>
                                            <td class="text-end fw-bold text-primary fs-5">GHS ${totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-info text-white border-0">
                                    <h6 class="mb-0"><i class="ri-settings-3-line me-2"></i>Tax Configuration</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="ri-settings-3-line text-info"></i>
                                        </div>
                                        <div>
                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-semibold">
                                                ${po.tax_configuration ? po.tax_configuration.name : 'Manual'}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-medium text-muted">Type:</span>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded">
                                                ${po.tax_type || 'N/A'}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-medium text-muted">Tax Exempt:</span>
                                            <span class="badge ${po.is_tax_exempt ? 'bg-warning text-dark' : 'bg-success text-white'} px-2 py-1 rounded">
                                                ${po.is_tax_exempt ? 'Yes' : 'No'}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    ${po.tax_exemption_reason ? `
                                        <div class="alert alert-warning border-0 bg-warning bg-opacity-10">
                                            <div class="d-flex align-items-start">
                                                <i class="ri-information-line text-warning me-2 mt-1"></i>
                                                <div>
                                                    <small class="fw-semibold text-warning">Exemption Reason:</small><br>
                                                    <small class="text-muted">${po.tax_exemption_reason}</small>
                                                </div>
                                            </div>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Create invoice display
        let invoiceHtml = '';
        if (po.invoices && po.invoices.length > 0) {
            invoiceHtml = `
                <div class="card">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 text-dark"><i class="ri-file-pdf-line me-2"></i>Uploaded Invoices (${po.invoices.length})</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
            `;
            po.invoices.forEach(function(invoice, index) {
                const fileIcon = invoice.file_type && invoice.file_type.includes('pdf') ? 'ri-file-pdf-line text-danger' : 'ri-file-line text-primary';
                invoiceHtml += `
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="${fileIcon}" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-truncate" title="${invoice.original_name}">${invoice.original_name}</h6>
                                        <small class="text-muted">
                                            <i class="ri-file-size-line me-1"></i>${formatFileSize(invoice.file_size)}
                                        </small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="/storage/${invoice.file_path}" target="_blank" class="btn btn-sm btn-outline-primary" title="Download">
                                            <i class="ri-download-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            invoiceHtml += `
                        </div>
                    </div>
                </div>
            `;
        } else {
            invoiceHtml = `
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ri-file-pdf-line text-muted" style="font-size: 3rem;"></i>
                        <h6 class="text-muted mt-3 mb-1">No Invoices Uploaded</h6>
                        <p class="text-muted mb-0">No invoice files have been uploaded for this purchase order.</p>
                    </div>
                </div>
            `;
        }

        Swal.fire({
            title: `
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        <i class="ri-file-list-line text-primary me-2"></i>
                        <span>Purchase Order Details</span>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="printPODetails()" title="Print PDF">
                        <i class="ri-printer-line me-1"></i>Print PDF
                    </button>
                </div>
            `,
            html: `
                <div class="text-start">
                    <!-- Header Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="mb-0 text-dark"><i class="ri-information-line me-2"></i>PO Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark me-2">PO Number</span>
                                        <strong>${po.po_number}</strong>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark me-2">Supplier</span>
                                        ${po.supplier ? po.supplier.company_name : 'Unknown'}
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark me-2">Created By</span>
                                        ${getUserName(po.user)}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark me-2">Order Date</span>
                                        ${formatDate(po.order_date)}
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark me-2">Delivery Date</span>
                                        ${formatDate(po.delivery_date)}
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-primary">External Approval</span>
                                    </div>
                                </div>
                            </div>
                            ${po.payment_terms ? `
                                <div class="mt-2">
                                    <span class="badge bg-light text-dark me-2">Payment Terms</span>
                                    ${po.payment_terms}
                                </div>
                            ` : ''}
                            ${po.notes ? `
                                <div class="mt-2">
                                    <span class="badge bg-light text-dark me-2">Notes</span>
                                    ${po.notes}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    ${itemsHtml}
                    ${totalsHtml}
                    ${invoiceHtml}
                </div>
            `,
            width: '1200px',
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: 'swal-wide'
            }
        });
    }

    function approvePO(poId) {
        Swal.fire({
            title: 'Approve Purchase Order',
            text: 'Are you sure you want to approve this purchase order?',
            input: 'textarea',
            inputLabel: 'Approval Notes (Optional)',
            inputPlaceholder: 'Add any notes for the approval...',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("management.po_approval.approve", ":id") }}'.replace(':id', poId),
                    method: 'POST',
                    data: {
                        notes: result.value || '',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success');
                            loadPOStatistics();
                            loadPurchaseOrders();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Failed to approve purchase order', 'error');
                    }
                });
            }
        });
    }

    function rejectPO(poId) {
        Swal.fire({
            title: 'Reject Purchase Order',
            text: 'Are you sure you want to reject this purchase order?',
            input: 'textarea',
            inputLabel: 'Rejection Reason (Required)',
            inputPlaceholder: 'Please provide a reason for rejection...',
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to provide a reason for rejection!'
                }
            },
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Reject',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("po_approval.reject", ":id") }}'.replace(':id', poId),
                    method: 'POST',
                    data: {
                        reason: result.value,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success');
                            loadPOStatistics();
                            loadPurchaseOrders();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Failed to reject purchase order', 'error');
                    }
                });
            }
        });
    }

    function revertPO(poId) {
        Swal.fire({
            title: 'Revert Purchase Order',
            text: 'Are you sure you want to revert this purchase order from external approval back to procurement for further processing?',
            input: 'textarea',
            inputLabel: 'Revert Reason (Optional)',
            inputPlaceholder: 'Please provide a reason for reverting...',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Revert',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#f39c12'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("po_approval.revert", ":id") }}'.replace(':id', poId),
                    method: 'POST',
                    data: {
                        comments: result.value || '',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success');
                            loadPOStatistics();
                            loadPurchaseOrders();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Failed to revert purchase order', 'error');
                    }
                });
            }
        });
    }

    // Store current PO data for printing
    let currentPODetails = null;

    function printPODetails() {
        if (!currentPODetails) {
            Swal.fire('Error!', 'No purchase order data available for printing', 'error');
            return;
        }

        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        
        // Generate the print content
        const printContent = generatePrintContent(currentPODetails);
        
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Wait for content to load then print
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    }

    function generatePrintContent(po) {
        // Parse items if it's a JSON string
        let items = [];
        if (po.items) {
            if (typeof po.items === 'string') {
                try {
                    items = JSON.parse(po.items);
                } catch (e) {
                    items = [];
                }
            } else if (Array.isArray(po.items)) {
                items = po.items;
            }
        }

        // Calculate totals
        const subtotal = parseFloat(po.subtotal || 0);
        const taxAmount = parseFloat(po.tax_amount || 0);
        const totalAmount = parseFloat(po.total_amount || 0);

        // Generate items table
        let itemsTable = '';
        if (items && items.length > 0) {
            itemsTable = `
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            items.forEach(function(item) {
                const total = parseFloat(item.quantity || 0) * parseFloat(item.unit_price || 0);
                itemsTable += `
                    <tr>
                        <td>${item.name || 'N/A'}</td>
                        <td>${item.quantity || '0'}</td>
                        <td>GHS ${parseFloat(item.unit_price || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                        <td>GHS ${total.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                    </tr>
                `;
            });
            itemsTable += `
                    </tbody>
                </table>
            `;
        }

        return `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Purchase Order - ${po.po_number || 'N/A'}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 15px;
                        color: #333;
                        line-height: 1.4;
                        font-size: 11px;
                    }
                    .header {
                        text-align: center;
                        border-bottom: 2px solid #007bff;
                        padding-bottom: 15px;
                        margin-bottom: 20px;
                    }
                    .logo-section {
                        margin-bottom: 10px;
                    }
                    .company-logo {
                        max-height: 50px;
                        max-width: 180px;
                        object-fit: contain;
                    }
                    .header h1 {
                        color: #007bff;
                        margin: 0;
                        font-size: 24px;
                        font-weight: bold;
                    }
                    .header h2 {
                        margin: 3px 0;
                        color: #666;
                        font-size: 16px;
                        font-weight: normal;
                    }
                    .header p {
                        margin: 3px 0 0 0;
                        font-size: 10px;
                        color: #888;
                    }
                    .info-section {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 30px;
                        gap: 15px;
                    }
                    .info-box {
                        flex: 1;
                        padding: 12px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        background: #f9f9f9;
                        min-width: 0;
                    }
                    .info-box h3 {
                        margin: 0 0 8px 0;
                        color: #007bff;
                        font-size: 14px;
                        border-bottom: 1px solid #ddd;
                        padding-bottom: 3px;
                        white-space: nowrap;
                    }
                    .info-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 3px;
                        font-size: 12px;
                        line-height: 1.3;
                    }
                    .info-row strong {
                        color: #555;
                        min-width: 80px;
                        flex-shrink: 0;
                    }
                    .info-row span {
                        text-align: right;
                        word-break: break-word;
                        flex: 1;
                    }
                    .items-section {
                        margin: 20px 0;
                    }
                    .items-section h3 {
                        margin: 0 0 10px 0;
                        color: #007bff;
                        font-size: 14px;
                    }
                    .items-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 8px;
                        font-size: 11px;
                    }
                    .items-table th,
                    .items-table td {
                        border: 1px solid #ddd;
                        padding: 6px 8px;
                        text-align: left;
                    }
                    .items-table th {
                        background-color: #007bff;
                        color: white;
                        font-weight: bold;
                        font-size: 11px;
                    }
                    .items-table tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                    .items-table tr:nth-child(odd) {
                        background-color: white;
                    }
                    .totals-section {
                        margin-top: 20px;
                        display: flex;
                        justify-content: flex-end;
                    }
                    .totals-table {
                        width: 280px;
                        border-collapse: collapse;
                        font-size: 11px;
                    }
                    .totals-table td {
                        border: 1px solid #ddd;
                        padding: 5px 10px;
                    }
                    .totals-table .total-row {
                        background-color: #007bff;
                        color: white;
                        font-weight: bold;
                        font-size: 12px;
                    }
                    .tax-section {
                        margin-top: 20px;
                        padding: 10px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        background: #f9f9f9;
                    }
                    .tax-section h3 {
                        margin: 0 0 8px 0;
                        color: #007bff;
                        font-size: 14px;
                    }
                    .tax-section .info-row {
                        font-size: 11px;
                        margin-bottom: 2px;
                    }
                    .footer {
                        margin-top: 50px;
                        text-align: center;
                        color: #666;
                        font-size: 12px;
                        border-top: 1px solid #ddd;
                        padding-top: 20px;
                    }
                    @media print {
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <div class="logo-section">
                        <img src="${window.location.origin}/images/gesl_logo.png" alt="Company Logo" class="company-logo" />
                    </div>
                    <h1>PURCHASE ORDER</h1>
                    <h2>${po.po_number || 'N/A'}</h2>
                    <p>Generated on ${new Date().toLocaleDateString()} at ${new Date().toLocaleTimeString()}</p>
                </div>

                <div class="info-section">
                    <div class="info-box">
                        <h3>Purchase Order Details</h3>
                        <div class="info-row">
                            <strong>PO Number:</strong>
                            <span>${po.po_number || 'N/A'}</span>
                        </div>
                        <div class="info-row">
                            <strong>Status:</strong>
                            <span>${po.status || 'N/A'}</span>
                        </div>
                        <div class="info-row">
                            <strong>Priority:</strong>
                            <span>${po.priority || 'N/A'}</span>
                        </div>
                        <div class="info-row">
                            <strong>Date Created:</strong>
                            <span>${po.created_at ? new Date(po.created_at).toLocaleDateString() : 'N/A'}</span>
                        </div>
                    </div>

                    <div class="info-box">
                        <h3>Supplier Information</h3>
                        <div class="info-row">
                            <strong>Supplier:</strong>
                            <span>${po.supplier ? (po.supplier.company_name || po.supplier.name || 'N/A') : 'N/A'}</span>
                        </div>
                        <div class="info-row">
                            <strong>Contact:</strong>
                            <span>${po.supplier ? (po.supplier.email || po.supplier.phone || 'N/A') : 'N/A'}</span>
                        </div>
                    </div>

                    <div class="info-box">
                        <h3>Created By</h3>
                        <div class="info-row">
                            <strong>User:</strong>
                            <span>${getUserName(po.createdBy || po.user)}</span>
                        </div>
                    </div>
                </div>

                <div class="items-section">
                    <h3>Purchase Order Items</h3>
                    ${itemsTable}
                </div>

                <div class="totals-section">
                    <table class="totals-table">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td>GHS ${subtotal.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                        </tr>
                        <tr>
                            <td><strong>Tax (${po.tax_rate || 0}%):</strong></td>
                            <td>GHS ${taxAmount.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                        </tr>
                        <tr>
                        </tr>
                        <tr class="total-row">
                            <td><strong>TOTAL AMOUNT:</strong></td>
                            <td>GHS ${totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                        </tr>
                    </table>
                </div>

                <div class="tax-section">
                    <h3>Tax Configuration</h3>
                    <div class="info-row">
                        <strong>Configuration:</strong>
                        <span>${po.tax_configuration ? po.tax_configuration.name : 'Manual'}</span>
                    </div>
                    <div class="info-row">
                        <strong>Type:</strong>
                        <span>${po.tax_type || 'N/A'}</span>
                    </div>
                    <div class="info-row">
                        <strong>Tax Exempt:</strong>
                        <span>${po.is_tax_exempt ? 'Yes' : 'No'}</span>
                    </div>
                    ${po.tax_exemption_reason ? `
                        <div class="info-row">
                            <strong>Exemption Reason:</strong>
                            <span>${po.tax_exemption_reason}</span>
                        </div>
                    ` : ''}
                </div>

                <div class="footer">
                    <p>This document was generated automatically from the ERP system.</p>
                    <p>For any queries, please contact the procurement department.</p>
                </div>
            </body>
            </html>
        `;
    }
</script>

<style>
/* Custom styles for the PO details modal */
.swal-wide {
    max-width: 1200px !important;
}

.swal2-popup .swal2-title {
    margin-bottom: 1rem !important;
}

.swal2-popup .swal2-html-container {
    margin: 0 !important;
    padding: 0 !important;
}

/* Card hover effects */
.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s ease;
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

/* Table styling */
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

/* Invoice card styling */
.card.border {
    border-color: #dee2e6 !important;
    transition: all 0.3s ease;
}

.card.border:hover {
    border-color: #007bff !important;
    transform: translateY(-2px);
}

/* Financial summary styling */
.table-primary td {
    background-color: #e3f2fd !important;
    border-color: #bbdefb !important;
}

/* Icon styling */
.ri-file-pdf-line {
    color: #dc3545 !important;
}

.ri-file-line {
    color: #007bff !important;
}

/* PO Items Table Styling */
.po-items-table {
    border-collapse: separate;
    border-spacing: 0;
}

.po-items-table .po-item-row {
    transition: none !important;
    border-bottom: 1px solid #f8f9fa;
}

.po-items-table .po-item-row:hover {
    background-color: transparent !important;
    transform: none !important;
}

.po-items-table .po-item-row:last-child {
    border-bottom: none;
}

.po-items-table .po-item-row:nth-child(even) {
    background-color: #f8f9fa;
}

.po-items-table .po-item-row:nth-child(even):hover {
    background-color: #f8f9fa !important;
}

.po-items-table .po-item-row:nth-child(odd) {
    background-color: #ffffff;
}

.po-items-table .po-item-row:nth-child(odd):hover {
    background-color: #ffffff !important;
}

/* Remove all hover effects from table rows */
.po-items-table tr:hover {
    background-color: inherit !important;
}

/* Custom badge styling */
.badge.bg-primary.bg-opacity-10 {
    background-color: rgba(13, 110, 253, 0.1) !important;
    color: #0d6efd !important;
    border: 1px solid rgba(13, 110, 253, 0.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .swal-wide {
        max-width: 95% !important;
    }
    
    .card-body {
        padding: 1rem !important;
    }
    
    .po-items-table .po-item-row td {
        padding: 0.75rem 1rem !important;
    }
    
    .po-items-table .po-item-row .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .po-items-table .po-item-row .d-flex .bg-primary {
        margin-bottom: 0.5rem;
        margin-right: 0 !important;
    }
}
</style>
@endsection

