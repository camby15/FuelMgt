@extends('layouts.vertical', ['page_title' => 'Procurement Management'])

@section('css')
@vite([
    'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
    'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'
])
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />
<style>
    .procurement-container {
        background-color: #f8f9fc;
        min-height: calc(100vh - 70px);
        padding: 20px;
    }

    .procurement-tabs {
        background: #f8f9fc;
        border-radius: 12px 12px 0 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 0;
        padding: 5px 15px 0;
        border: none;
        overflow-x: auto;
        white-space: nowrap;
        display: flex;
        flex-wrap: nowrap;
    }

    .procurement-tabs .nav-link {
        color: #5a5c69;
        font-weight: 600;
        padding: 15px 25px;
        border: none;
        position: relative;
        transition: all 0.3s ease;
        border-radius: 8px 8px 0 0;
        margin-right: 4px;
        background: #e9ecef;
        display: inline-flex;
        align-items: center;
    }

    .procurement-tabs .nav-link.active {
        color: #fff;
        background: linear-gradient(45deg, #3b7ddd, #2c6ecb);
        box-shadow: 0 4px 10px rgba(59, 125, 221, 0.2);
        transform: translateY(-2px);
    }

    .procurement-tabs .nav-link:hover:not(.active) {
        color: #3b7ddd;
        background: #e3e8f7;
        transform: translateY(-2px);
    }

    .procurement-tabs .nav-link i {
        margin-right: 8px;
        font-size: 1.1em;
    }

    .procurement-tabs .nav-link .badge {
        margin-left: 8px;
        font-size: 0.7em;
        padding: 3px 6px;
    }

    .dashboard-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #fff;
        margin-bottom: 15px;
    }

    .requisition-card {
        border-left: 4px solid #3b7ddd;
        margin-bottom: 15px;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .requisition-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .requisition-card .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .requisition-card .card-body {
        padding: 20px;
    }

    .requisition-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 15px;
    }

    .meta-item {
        flex: 1;
        min-width: 150px;
    }

    .meta-label {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .meta-value {
        font-weight: 600;
        color: #2d3748;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }

    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .status-completed {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
        display: inline-block;
    }
    
    @media print {
        body * {
            visibility: hidden;
        }
        #requisitionDetailsModal, 
        #requisitionDetailsModal * {
            visibility: visible;
        }
        #requisitionDetailsModal {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .modal-footer, 
        .modal-header .btn-close {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="procurement-container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Procurement Dashboard</h1>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-export me-2"></i>Export</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Pending Approvals</div>
                        <h3 class="card-value" id="pendingApprovals">0</h3>
                        <div class="text-success small" id="pendingApprovalsChange">
                            <i class="fas fa-arrow-up me-1"></i> <span id="pendingApprovalsNew">0</span> New
                        </div>
                    </div>
                    <div class="card-icon" style="background: linear-gradient(45deg, #ffc107, #ff9800);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Approved This Month</div>
                        <h3 class="card-value" id="approvedThisMonth">0</h3>
                        <div class="text-success small" id="approvedThisMonthChange">
                            <i class="fas fa-arrow-up me-1"></i> <span id="approvedThisMonthPercent">0</span>%
                        </div>
                    </div>
                    <div class="card-icon" style="background: linear-gradient(45deg, #28a745, #20c997);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Total Spend</div>
                        <h3 class="card-value" id="totalSpend">$0</h3>
                        <div class="text-danger small" id="totalSpendChange">
                            <i class="fas fa-arrow-down me-1"></i> <span id="totalSpendPercent">0</span>% from last month
                        </div>
                    </div>
                    <div class="card-icon" style="background: linear-gradient(45deg, #17a2b8, #0dcaf0);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Vendors</div>
                        <h3 class="card-value" id="vendors">0</h3>
                        <div class="text-success small" id="vendorsChange">
                            <i class="fas fa-arrow-up me-1"></i> <span id="vendorsNew">0</span> New
                        </div>
                    </div>
                    <div class="card-icon" style="background: linear-gradient(45deg, #6f42c1, #9b4dca);">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <ul class="nav procurement-tabs" id="procurementTabs" role="tablist">
                        <!-- Commented out the original first tab
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" 
                                data-bs-target="#pending" type="button" role="tab" aria-controls="pending" 
                                aria-selected="true">
                                <i class="fas fa-clock"></i> Pending Approval
                                <span class="badge bg-danger rounded-pill">2</span>
                            </button>
                        </li>
                        -->

                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="approvals-tab" data-bs-toggle="tab" 
                                data-bs-target="#approvals" type="button" role="tab" 
                                aria-controls="approvals" aria-selected="true">
                                <i class="fas fa-gavel me-2"></i>Pending - Approval
                                <!-- <span class="badge bg-warning rounded-pill"></span> -->
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="all-tab" data-bs-toggle="tab" 
                                data-bs-target="#all" type="button" role="tab" 
                                aria-controls="all" aria-selected="false">
                                <i class="fas fa-list"></i> All Requisitions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="suppliers-tab" data-bs-toggle="tab" 
                                data-bs-target="#suppliers" type="button" role="tab" 
                                aria-controls="suppliers" aria-selected="false">
                                <i class="fas fa-building me-2"></i>Suppliers
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="purchase-orders-tab" data-bs-toggle="tab" 
                                data-bs-target="#purchase-orders" type="button" role="tab" 
                                aria-controls="purchase-orders" aria-selected="false">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Purchase Orders
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-3" id="procurementTabsContent">
                          <!-- Approvals Tab -->
                        <div class="tab-pane fade show active" id="approvals" role="tabpanel" aria-labelledby="approvals-tab">
                            @include('company.InventoryManagement.ProcRequi.approvals')
                        </div>
                        <!-- All Tab -->
                        <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                            <!-- Filters -->
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="allStatusFilter" class="form-label">Status</label>
                                        <select class="form-select" id="allStatusFilter">
                                            <option value="">All Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="allSearchFilter" class="form-label">Search</label>
                                        <input type="text" class="form-control" id="allSearchFilter" placeholder="Search requisitions...">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="allDateFromFilter" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="allDateFromFilter">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="allDateToFilter" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="allDateToFilter">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-primary d-block" id="loadAllRequisitionsBtn">
                                            <i class="fas fa-sync me-1"></i>Load Data
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="allRequisitionsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Reference</th>
                                            <th>Title</th>
                                            <th>Requested By</th>
                                            <th>Department</th>
                                            <th>Amount</th>
                                            <th>Priority</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="allRequisitionsTableBody">
                                        <!-- Data will be loaded here -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Loading and Empty States -->
                            <div id="allRequisitionsLoading" class="text-center py-4" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading requisitions...</p>
                            </div>

                            <div id="allRequisitionsEmpty" class="text-center py-5" style="display: none;">
                                <i class="fas fa-list-ul text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5>No Requisitions Found</h5>
                                <p class="text-muted">No requisitions match your current filters.</p>
                            </div>

                            <!-- Pagination -->
                            <div id="allRequisitionsPagination" class="d-flex justify-content-between align-items-center mt-4" style="display: none;">
                                <div class="pagination-info">
                                    <span id="paginationInfo" class="text-muted"></span>
                                </div>
                                <nav aria-label="Requisitions pagination">
                                    <ul class="pagination pagination-sm mb-0" id="paginationControls">
                                        <!-- Pagination controls will be generated here -->
                                    </ul>
                                </nav>
                                <div class="pagination-size">
                                    <select class="form-select form-select-sm" id="perPageSelect" style="width: auto;">
                                        <option value="5">5 per page</option>
                                        <option value="10" selected>10 per page</option>
                                        <option value="25">25 per page</option>
                                        <option value="50">50 per page</option>
                                        <option value="100">100 per page</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Suppliers Tab -->
                        <div class="tab-pane fade" id="suppliers" role="tabpanel" aria-labelledby="suppliers-tab">
                            @include('company.InventoryManagement.ProcRequi.suppliers')
                        </div>

                        <!-- Purchase Orders Tab -->
                        <div class="tab-pane fade" id="purchase-orders" role="tabpanel" aria-labelledby="purchase-orders-tab">
                            @include('company.InventoryManagement.ProcRequi.purchase-order')
                        </div>

                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Requisition Details Modal -->
<div class="modal fade" id="requisitionDetailsModal" tabindex="-1" aria-labelledby="requisitionDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="requisitionDetailsModalLabel">Requisition #<span id="requisitionId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h4 id="requisitionTitle" class="mb-3"></h4>
                        <div class="d-flex align-items-center mb-3">
                            <span id="requisitionStatus" class="status-badge me-3"></span>
                            <span class="text-muted">Requested on <span id="requisitionDate"></span></span>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="fw-bold fs-4" id="requisitionAmount"></div>
                        <div class="text-muted">Total Amount</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Request Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="text-muted small">Requested By</div>
                                    <div id="requisitionRequester" class="fw-medium"></div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-muted small">Department</div>
                                    <div id="requisitionDepartment" class="fw-medium"></div>
                                </div>
                                <div>
                                    <div class="text-muted small">Priority</div>
                                    <div id="requisitionPriority" class="fw-medium"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Title</h6>
                            </div>
                            <div class="card-body">
                                <p id="requisitionDescription" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Items</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Description</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="requisitionItems">
                                    <!-- Items will be populated by JavaScript -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Subtotal</td>
                                        <td class="text-end fw-bold" id="subtotalAmount"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end">Tax (0%)</td>
                                        <td class="text-end">$0.00</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td colspan="4" class="text-end fw-bold">Total</td>
                                        <td class="text-end fw-bold" id="totalAmount"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printRequisition">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    console.log('Document ready - JavaScript is loading');
    
    // Pagination state
    let currentPage = 1;
    let perPage = 10;
    let totalPages = 1;
    let totalRecords = 0;
    
    // Test if button exists
    console.log('Load Data button exists:', $('#loadAllRequisitionsBtn').length);
    
    // Manual load button click handler
    $('#loadAllRequisitionsBtn').on('click', function(e) {
        e.preventDefault();
        console.log('Load Data button clicked!');
        loadAllRequisitionsInternal();
    });
    
    // Also try with direct click handler
    $(document).on('click', '#loadAllRequisitionsBtn', function(e) {
        e.preventDefault();
        console.log('Load Data button clicked via document delegation');
        loadAllRequisitionsInternal();
    });

    // Filter event handlers for All Requisitions tab
    $('#allStatusFilter, #allSearchFilter, #allDateFromFilter, #allDateToFilter').on('change input', function() {
        currentPage = 1; // Reset to first page when filtering
        loadAllRequisitionsInternal();
    });

    // Per page change handler
    $('#perPageSelect').on('change', function() {
        perPage = parseInt($(this).val());
        currentPage = 1; // Reset to first page when changing page size
        loadAllRequisitionsInternal();
        });

        // Handle tab changes
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        console.log('Tab changed to:', e.target.id);
        // Load data when switching to All Requisitions tab
        if (e.target.id === 'all-tab') {
            console.log('All Requisitions tab activated, loading data...');
            loadAllRequisitionsInternal();
        }
        // Load data when switching to Suppliers tab
        if (e.target.id === 'suppliers-tab') {
            console.log('Suppliers tab activated, initializing suppliers page...');
            // Check if initializeSuppliersPage function exists and call it
            if (typeof window.initializeSuppliersPage === 'function') {
                console.log('Calling initializeSuppliersPage from procurement page...');
                window.initializeSuppliersPage();
            } else {
                console.log('initializeSuppliersPage function not found, waiting for suppliers page to load...');
                // Wait a bit for the suppliers page to load
                setTimeout(function() {
                    if (typeof window.initializeSuppliersPage === 'function') {
                        console.log('Calling initializeSuppliersPage after delay...');
                        window.initializeSuppliersPage();
                    } else {
                        console.log('initializeSuppliersPage still not found after delay');
                        // Try to call fetchSuppliers directly as fallback
                        if (typeof window.fetchSuppliers === 'function') {
                            console.log('Calling fetchSuppliers as fallback...');
                            window.fetchSuppliers();
                        }
                    }
                }, 1000);
            }
        }
        // Load data when switching to Approvals tab
        if (e.target.id === 'approvals-tab') {
            console.log('=== APPROVALS TAB ACTIVATED ===');
            console.log('Target ID:', e.target.id);
            console.log('Target element:', e.target);
            console.log('loadApprovalData function exists:', typeof window.loadApprovalData);
            console.log('initializeApprovalTab function exists:', typeof window.initializeApprovalTab);
            
            // Check if loadApprovalData function exists and call it
            if (typeof window.loadApprovalData === 'function') {
                console.log('Calling loadApprovalData from procurement page...');
                try {
                    window.loadApprovalData(1, '', true); // Force refresh
                    console.log('loadApprovalData called successfully');
                } catch (error) {
                    console.error('Error calling loadApprovalData:', error);
                }
            } else {
                console.log('loadApprovalData function not found, waiting for approvals page to load...');
                // Wait a bit for the approvals page to load
                setTimeout(function() {
                    console.log('Checking for loadApprovalData after delay...');
                    console.log('loadApprovalData function exists after delay:', typeof window.loadApprovalData);
                    
                    if (typeof window.loadApprovalData === 'function') {
                        console.log('Calling loadApprovalData after delay...');
                        try {
                            window.loadApprovalData(1, '', true); // Force refresh
                            console.log('loadApprovalData called successfully after delay');
                        } catch (error) {
                            console.error('Error calling loadApprovalData after delay:', error);
                        }
                    } else {
                        console.log('loadApprovalData still not found after delay');
                        // Try to call initializeApprovalTab as fallback
                        if (typeof window.initializeApprovalTab === 'function') {
                            console.log('Calling initializeApprovalTab as fallback...');
                            try {
                                window.initializeApprovalTab();
                                console.log('initializeApprovalTab called successfully');
                            } catch (error) {
                                console.error('Error calling initializeApprovalTab:', error);
                            }
                        } else {
                            console.log('initializeApprovalTab also not found');
                        }
                    }
                }, 1000);
            }
        }
    });

    // Load all requisitions when page loads (if on all tab)
    if ($('#all-tab').hasClass('active')) {
        console.log('All Requisitions tab is active on page load, loading data...');
        loadAllRequisitionsInternal();
    }

    // Make loadAllRequisitions globally accessible for testing
    window.loadAllRequisitions = function() {
        console.log('loadAllRequisitions called globally');
        loadAllRequisitionsInternal();
    };
    
    // Load all requisitions function
    function loadAllRequisitionsInternal() {
        console.log('loadAllRequisitions function called');
        
        const status = $('#allStatusFilter').val();
        const search = $('#allSearchFilter').val();
        const dateFrom = $('#allDateFromFilter').val();
        const dateTo = $('#allDateToFilter').val();

        console.log('Filter values:', { status, search, dateFrom, dateTo });

        // Show loading state
        $('#allRequisitionsLoading').show();
        $('#allRequisitionsTable').hide();
        $('#allRequisitionsEmpty').hide();

        console.log('Making AJAX request to:', '{{ route("po_approval.all_requisitions") }}');

        $.ajax({
            url: '{{ route("po_approval.all_requisitions") }}',
            method: 'POST',
            data: {
                status: status,
                search: search,
                date_from: dateFrom,
                date_to: dateTo,
                page: currentPage,
                per_page: perPage,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('AJAX success response:', response);
                $('#allRequisitionsLoading').hide();
                
                // Update pagination state
                currentPage = response.current_page || 1;
                totalPages = response.last_page || 1;
                totalRecords = response.total || 0;
                perPage = response.per_page || 10;
                
                if (response.data && response.data.length > 0) {
                    console.log('Rendering table with', response.data.length, 'requisitions');
                    renderAllRequisitionsTable(response.data);
                    $('#allRequisitionsTable').show();
                    renderPagination(response);
                    $('#allRequisitionsPagination').show();
                    console.log('Table should be visible now');
                } else {
                    console.log('No data found, showing empty state');
                    $('#allRequisitionsEmpty').show();
                    $('#allRequisitionsPagination').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', { xhr, status, error });
                $('#allRequisitionsLoading').hide();
                $('#allRequisitionsEmpty').show();
                console.error('Error loading requisitions:', error);
            }
        });
    }

    // Render all requisitions table
    function renderAllRequisitionsTable(requisitions) {
        console.log('renderAllRequisitionsTable called with', requisitions.length, 'requisitions');
        
        const tbody = $('#allRequisitionsTableBody');
        console.log('Table body element:', tbody.length);
        tbody.empty();

        requisitions.forEach(function(requisition, index) {
            console.log('Processing requisition', index + 1, ':', requisition.requisition_number);
            
            const statusBadge = getStatusBadge(requisition.status);
            const priorityBadge = getPriorityBadge(requisition.priority || 'normal');
            const formattedDate = new Date(requisition.created_at).toLocaleDateString();
            const formattedAmount = formatCurrency(requisition.total_amount || 0);

            const row = `
                <tr>
                    <td>
                        <div class="fw-medium">${requisition.requisition_number}</div>
                        <small class="text-muted">${requisition.reference_code || ''}</small>
                    </td>
                    <td>
                        <div class="fw-medium">${requisition.title || 'No Title'}</div>
                        <small class="text-muted">${requisition.description ? requisition.description.substring(0, 50) + '...' : ''}</small>
                    </td>
                    <td>${requisition.requestor_name || 'Unknown'}</td>
                    <td>${requisition.department || 'N/A'}</td>
                    <td class="text-end fw-medium">${formattedAmount}</td>
                    <td>${priorityBadge}</td>
                    <td>${formattedDate}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary btn-view-requisition" 
                                data-id="${requisition.id}"
                                data-title="${requisition.title || 'No Title'}"
                                data-status="${requisition.status}"
                                data-requester="${requisition.requestor_name || 'Unknown'}"
                                data-department="${requisition.department || 'N/A'}"
                                data-date="${formattedDate}"
                                data-amount="${formattedAmount}"
                                data-priority="${requisition.priority || 'normal'}"
                                data-description="${requisition.description || ''}"
                                data-items='${JSON.stringify(requisition.items || [])}'>
                            <i class="fas fa-eye me-1"></i>View
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
        
        console.log('Table rendering complete. Rows added:', tbody.find('tr').length);
    }

    // Get status badge HTML
    function getStatusBadge(status) {
        const statusMap = {
            'pending': '<span class="badge bg-warning text-dark">Pending</span>',
            'approved': '<span class="badge bg-success">Approved</span>',
            'rejected': '<span class="badge bg-danger">Rejected</span>',
            'completed': '<span class="badge bg-info">Completed</span>'
        };
        return statusMap[status] || '<span class="badge bg-secondary">Unknown</span>';
    }

    // Get priority badge HTML
    function getPriorityBadge(priority) {
        const priorityMap = {
            'high': '<span class="badge bg-danger">High</span>',
            'normal': '<span class="badge bg-primary">Normal</span>',
            'low': '<span class="badge bg-secondary">Low</span>'
        };
        return priorityMap[priority] || '<span class="badge bg-secondary">Normal</span>';
    }

    // Format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2
        }).format(amount);
    }

    // Handle view requisition button click
    $(document).on('click', '.btn-view-requisition', function() {
            const $btn = $(this);
            const id = $btn.data('id');
            const title = $btn.data('title');
            const status = $btn.data('status');
            const requester = $btn.data('requester');
            const department = $btn.data('department');
            const date = $btn.data('date');
            const amount = $btn.data('amount');
            const priority = $btn.data('priority');
        const description = $btn.data('title');
        const items = $btn.data('items') || [];

            // Set modal content
            $('#requisitionId').text(id);
            $('#requisitionTitle').text(title);
            $('#requisitionStatus').text(status).removeClass().addClass('status-badge ' + getStatusClass(status));
            $('#requisitionRequester').text(requester);
            $('#requisitionDepartment').text(department);
            $('#requisitionDate').text(date);
            $('#requisitionAmount').text(amount);
            $('#requisitionPriority').html(`<span class="badge ${getPriorityClass(priority)}">${priority}</span>`);
            $('#requisitionDescription').text(description);
            
            // Populate items table
            const $itemsContainer = $('#requisitionItems').empty();
        let subtotal = 0;
        
            items.forEach(item => {
            const itemTotal = (item.quantity || 0) * (item.unit_price || 0);
            subtotal += itemTotal;
            
                $itemsContainer.append(`
                    <tr>
                    <td>${item.item_name || 'Unknown Item'}</td>
                    <td>${item.description || ''}</td>
                    <td class="text-end">${item.quantity || 0}</td>
                    <td class="text-end">${formatCurrency(item.unit_price || 0)}</td>
                    <td class="text-end fw-medium">${formatCurrency(itemTotal)}</td>
                    </tr>
                `);
            });

            // Update totals
            $('#subtotalAmount').text(formatCurrency(subtotal));
            $('#totalAmount').text(amount);

            // Show the modal
            $('#requisitionDetailsModal').modal('show');
        });

    // Helper function to get status class for modal
        function getStatusClass(status) {
            const statusMap = {
                'Pending Approval': 'status-pending',
                'Approved': 'status-approved',
                'Rejected': 'status-rejected',
                'Completed': 'status-completed'
            };
            return statusMap[status] || 'status-pending';
        }

    // Helper function to get priority class for modal
        function getPriorityClass(priority) {
            const priorityMap = {
                'High': 'bg-warning text-dark',
                'Normal': 'bg-info',
                'Low': 'bg-secondary'
            };
            return priorityMap[priority] || 'bg-secondary';
        }

    // Print button handler
    $('#printRequisition').on('click', function() {
        window.print();
    });

    // Render pagination controls
    function renderPagination(response) {
        const paginationControls = $('#paginationControls');
        const paginationInfo = $('#paginationInfo');
        
        // Update pagination info
        const from = response.from || 0;
        const to = response.to || 0;
        const total = response.total || 0;
        paginationInfo.text(`Showing ${from} to ${to} of ${total} entries`);
        
        // Clear existing pagination
        paginationControls.empty();
        
        // Don't show pagination if only one page
        if (totalPages <= 1) {
            return;
        }
        
        // Previous button
        const prevDisabled = currentPage <= 1 ? 'disabled' : '';
        paginationControls.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link" href="#" data-page="${currentPage - 1}" ${prevDisabled ? 'tabindex="-1"' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        `);
        
        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);
        
        // First page if not in range
        if (startPage > 1) {
            paginationControls.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="1">1</a>
                </li>
            `);
            if (startPage > 2) {
                paginationControls.append(`
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                `);
            }
        }
        
        // Page numbers in range
        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === currentPage ? 'active' : '';
            paginationControls.append(`
                <li class="page-item ${activeClass}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }
        
        // Last page if not in range
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationControls.append(`
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                `);
            }
            paginationControls.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
                </li>
            `);
        }
        
        // Next button
        const nextDisabled = currentPage >= totalPages ? 'disabled' : '';
        paginationControls.append(`
            <li class="page-item ${nextDisabled}">
                <a class="page-link" href="#" data-page="${currentPage + 1}" ${nextDisabled ? 'tabindex="-1"' : ''}>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `);
    }

    // Handle pagination clicks
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        if (page && page !== currentPage && page >= 1 && page <= totalPages) {
            currentPage = page;
            loadAllRequisitionsInternal();
        }
    });

    // Load procurement statistics
    loadProcurementStatistics();
});

// Load procurement statistics
function loadProcurementStatistics() {
    console.log('Loading procurement statistics...');
    console.log('Route URL:', '{{ route("po_approval.statistics") }}');
    
    $.ajax({
        url: '{{ route("po_approval.statistics") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Statistics API response:', response);
            if (response.success) {
                updateStatisticsDisplay(response.data);
            } else {
                console.error('Error loading statistics:', response.error);
                showFallbackStatistics();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error loading statistics:', error);
            console.error('XHR response:', xhr.responseText);
            showFallbackStatistics();
        }
    });
}

// Update statistics display
function updateStatisticsDisplay(data) {
    console.log('Updating statistics display with data:', data);
    
    // Pending Approvals
    $('#pendingApprovals').text(data.pending_approvals);
    $('#pendingApprovalsNew').text(data.pending_approvals_new);
    
    // Approved This Month
    $('#approvedThisMonth').text(data.approved_this_month);
    $('#approvedThisMonthPercent').text(data.approved_change_percent);
    
    // Update change indicator color and icon
    if (data.approved_change_percent >= 0) {
        $('#approvedThisMonthChange').removeClass('text-danger').addClass('text-success');
        $('#approvedThisMonthChange i').removeClass('fa-arrow-down').addClass('fa-arrow-up');
    } else {
        $('#approvedThisMonthChange').removeClass('text-success').addClass('text-danger');
        $('#approvedThisMonthChange i').removeClass('fa-arrow-up').addClass('fa-arrow-down');
    }
    
    // Total Spend
    $('#totalSpend').text('$' + formatNumber(data.total_spend));
    $('#totalSpendPercent').text(Math.abs(data.spend_change_percent));
    
    // Update spend change indicator
    if (data.spend_change_percent >= 0) {
        $('#totalSpendChange').removeClass('text-danger').addClass('text-success');
        $('#totalSpendChange i').removeClass('fa-arrow-down').addClass('fa-arrow-up');
    } else {
        $('#totalSpendChange').removeClass('text-success').addClass('text-danger');
        $('#totalSpendChange i').removeClass('fa-arrow-up').addClass('fa-arrow-down');
    }
    
    // Vendors
    $('#vendors').text(data.vendors);
    $('#vendorsNew').text(data.vendors_new);
}

// Show fallback statistics when API fails
function showFallbackStatistics() {
    $('#pendingApprovals').text('0');
    $('#pendingApprovalsNew').text('0');
    $('#approvedThisMonth').text('0');
    $('#approvedThisMonthPercent').text('0');
    $('#totalSpend').text('$0');
    $('#totalSpendPercent').text('0');
    $('#vendors').text('0');
    $('#vendorsNew').text('0');
}

// Format number with commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
</script>
@endsection

@push('styles')
<style>
    /* Add any additional styles for the procurement page here */
    .tab-pane {
        padding: 0;
        border-radius: 0 0 8px 8px;
        background: #fff;
    }
    
    .tab-content {
        padding: 20px;
    }
    .tab-pane h5 {
        color: #4e73df;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f8f9fc;
    }
</style>
@endpush


