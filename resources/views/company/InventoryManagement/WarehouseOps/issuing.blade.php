@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .issuing-container {
        background: #f5f7ff;
        min-height: 100%;
        padding: 1.5rem;
        border-radius: 12px;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .card-header {
        background: #fff;
        border-bottom: 1px solid #eef2f7;
        padding: 1.25rem 1.5rem;
        border-radius: 12px 12px 0 0 !important;
    }

    .card-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0;
    }

    .btn-primary {
        background-color: #4361ee;
        border-color: #4361ee;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #3a56d4;
        border-color: #3a56d4;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #718096;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem 1.5rem;
        border-color: #e2e8f0;
    }

    .select2-container .select2-selection--single {
        height: 40px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        height: 40px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #a4c2f4;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }

    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
        border-radius: 6px;
    }

    .badge-soft-primary {
        background-color: rgba(67, 97, 238, 0.1);
        color: #4361ee;
    }

    .badge-soft-success {
        background-color: rgba(72, 187, 120, 0.1);
        color: #48bb78;
    }

    .badge-soft-warning {
        background-color: rgba(237, 137, 54, 0.1);
        color: #ed8936;
    }
</style>
@endpush

<div class="issuing-container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Issuing & Waybill Management</h4>
                <div>
                    <button class="btn btn-primary" onclick="openOutboundOrderModal()">
                        <i class="fas fa-plus me-2"></i>Create Outbound Order
                    </button>
                    <button class="btn btn-success ms-2" onclick="openWaybillModalDirect()">
                        <i class="fas fa-truck me-2"></i>Create Waybill
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="issuingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="outbound-tab" data-bs-toggle="tab" data-bs-target="#outbound-orders" type="button" role="tab">
                <i class="fas fa-box-open me-2"></i>Outbound Orders
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="waybills-tab" data-bs-toggle="tab" data-bs-target="#waybills" type="button" role="tab">
                <i class="fas fa-truck me-2"></i>Waybills
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="issuingTabsContent">
        <!-- Outbound Orders Tab -->
        <div class="tab-pane fade show active" id="outbound-orders" role="tabpanel">
            <!-- Outbound Orders Table -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Outbound Orders</h5>
                        <div class="d-flex">
                            <div class="input-group ms-2" style="max-width: 250px;">
                                <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="outboundSearch" placeholder="Search orders...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Department</th>
                                    <th>Requested Date</th>
                                    <th>Requested By</th>
                                    <th>Items</th>
                                    <th>Items Value</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="outboundOrdersTableBody">
                                <!-- Approved requisitions will be loaded here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted" id="paginationInfo">
                            Loading...
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0" id="paginationContainer">
                                <!-- Pagination will be generated dynamically -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Waybills Tab -->
        <div class="tab-pane fade" id="waybills" role="tabpanel">
            <!-- Search and Filter -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Search</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" id="waybillSearchInput" placeholder="Search waybills...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="waybillStatusSelect">
                                        <option value="">All Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="in_transit">In Transit</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="delayed">Delayed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date Range</label>
                                    <input type="text" class="form-control" id="waybillDateRangeInput" placeholder="Select date range">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary w-100" id="applyWaybillFiltersBtn">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Waybills Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="waybillsTable">
                            <thead>
                                <tr>
                                    <th>Waybill #</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Origin</th>
                                    <th>Destination</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Waybills will be loaded here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Waybills Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted" id="waybillsPaginationInfo">
                            Loading...
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0" id="waybillsPaginationContainer">
                                <!-- Pagination will be generated dynamically -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Outbound Order Modal -->
<div class="modal fade" id="createOutboundOrderModal" tabindex="-1" aria-labelledby="createOutboundOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createOutboundOrderModalLabel">Create Outbound Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="outboundOrderForm">
                    <!-- Step 1: Order Details -->
                    <div class="step" id="step1">
                        <h6 class="mb-3 text-muted">Order Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Customer/Department</label>
                                <select class="form-select select2" id="customerSelect">
                                    <option value="">Loading departments...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Reference Type</label>
                                <select class="form-select" id="referenceType">
                                    <option value="">Select Reference</option>
                                    <option value="sales_order">Sales Order</option>
                                    <option value="job">Job</option>
                                    <option value="requisition">Requisition</option>
                                    <option value="none">None</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Reference #</label>
                                <input type="text" class="form-control" id="referenceNumber" placeholder="Enter reference number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Requested By</label>
                                <input type="text" class="form-control" id="requestedBy" placeholder="Will be auto-filled from reference">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" id="orderNotes" rows="2" placeholder="Add any special instructions"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Review Items from Reference -->
                    <div class="step d-none" id="step2">
                        <h6 class="mb-3 text-muted">Items from Reference</h6>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Items are automatically loaded from the approved reference. You can adjust issue quantities if needed.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Description</th>
                                        <th>Available</th>
                                        <th>Requested Qty</th>
                                        <th>Issue Qty</th>
                                        <th>UoM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-search me-2"></i>
                                            Enter a reference number to load items
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Step 3: Review -->
                    <div class="step d-none" id="step3">
                        <h6 class="mb-3 text-muted">Review Order</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6>Order Details</h6>
                                        <p class="mb-1"><strong>Customer/Department:</strong> <span id="reviewCustomer">-</span></p>
                                        <p class="mb-1"><strong>Reference:</strong> <span id="reviewReference">-</span></p>
                                        <p class="mb-0"><strong>Notes:</strong> <span id="reviewNotes">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Items</h6>
                                        <div id="reviewItems">
                                            <p class="text-muted mb-0">No items added</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="confirmOrder">
                            <label class="form-check-label" for="confirmOrder">
                                I confirm that all information is correct
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">Previous</button>
                <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                <button type="button" class="btn btn-success" id="submitBtn" style="display: none;">Submit Order</button>
            </div>
        </div>
    </div>
</div>

<!-- View Order Modal -->
<div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOrderModalLabel">Outbound Order #ORD-2023-001</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <p class="mb-1"><strong>Customer/Department:</strong> Sales Department</p>
                        <p class="mb-1"><strong>Reference:</strong> SO-2023-0456</p>
                        <p class="mb-1"><strong>Status:</strong> <span class="badge bg-warning">Pending</span></p>
                        <p class="mb-1"><strong>Requested By:</strong> John Smith</p>
                        <p class="mb-0"><strong>Requested Date:</strong> 2023-11-15</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Picking Information</h6>
                        <p class="mb-1"><strong>Assigned To:</strong> Warehouse Team</p>
                        <p class="mb-1"><strong>Priority:</strong> <span class="badge bg-danger">High</span></p>
                        <p class="mb-1"><strong>Required By:</strong> 2023-11-17</p>
                        <p class="mb-0"><strong>Notes:</strong> Urgent order for client meeting</p>
                    </div>
                </div>
                
                <h6>Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Requested</th>
                                <th>Requested by</th>
                                <th>Picked</th>
                                <th>Status</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>LT-1001</td>
                                <td>Laptop Pro 15"</td>
                                <td>2</td>
                                <td>Martin Kudjoe</td>
                                <td>0</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>A-12-5</td>
                            </tr>
                            <tr>
                                <td>MC-2001</td>
                                <td>Wireless Mouse</td>
                                <td>5</td>
                                <td>John Smith</td>
                                <td>5</td>
                                <td><span class="badge bg-success">Picked</span></td>
                                <td>B-5-12</td>
                            </tr>
                            <tr>
                                <td>KB-3001</td>
                                <td>Mechanical Keyboard</td>
                                <td>3</td>
                                <td>Martin Kudjoe</td>
                                <td>1</td>
                                <td><span class="badge bg-info">Partially Picked</span></td>
                                <td>B-5-15</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <h6>Picking Progress</h6>
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50% Complete</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-success me-2"><i class="fas fa-check-circle me-1"></i> 1/3 Items Picked</span>
                            <span class="badge bg-warning"><i class="fas fa-clock me-1"></i> 2 Items Remaining</span>
                        </div>
                        <button class="btn btn-sm btn-primary">
                            <i class="fas fa-clipboard-check me-1"></i> Complete Picking
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline-primary">
                    <i class="fas fa-print me-1"></i> Print Picking Slip
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-check-circle me-1"></i> Mark as Complete
                </button>
            </div>
        </div>
    </div>
</div>



<!-- View Waybill Modal -->
<div class="modal fade" id="viewWaybillModal" tabindex="-1" aria-labelledby="viewWaybillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewWaybillModalLabel">Waybill Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Basic Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Waybill #:</strong></td>
                                <td class="waybill-number">-</td>
                            </tr>
                            <tr>
                                <td><strong>Customer:</strong></td>
                                <td class="waybill-customer">-</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td><span class="badge waybill-status">-</span></td>
                            </tr>
                            <tr>
                                <td><strong>Origin:</strong></td>
                                <td class="waybill-origin">-</td>
                            </tr>
                            <tr>
                                <td><strong>Destination:</strong></td>
                                <td class="waybill-destination">-</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Delivery Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td class="waybill-created">-</td>
                            </tr>
                            <tr>
                                <td><strong>Estimated Delivery:</strong></td>
                                <td class="waybill-estimated-delivery">-</td>
                            </tr>
                            <tr>
                                <td><strong>Tracking #:</strong></td>
                                <td class="waybill-tracking">-</td>
                            </tr>
                            <tr>
                                <td><strong>Total Items:</strong></td>
                                <td class="waybill-items">-</td>
                            </tr>
                            <tr>
                                <td><strong>Total Weight:</strong></td>
                                <td class="waybill-weight">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6 class="text-primary mb-3">Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Description</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Unit</th>
                                    <th class="text-end">Brand</th>
                                </tr>
                            </thead>
                            <tbody id="viewItemsList">
                                <tr>
                                    <td colspan="5" class="text-center">No items found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Warehouse Processes Section -->
                <div class="mt-4">
                    <h6 class="text-primary mb-3">Warehouse Processes</h6>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Waybill Created</h6>
                                <p class="timeline-text text-muted">Waybill has been created and is ready for processing</p>
                                <small class="text-muted waybill-created-process">-</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Items Picked</h6>
                                <p class="timeline-text text-muted">Items have been picked from inventory</p>
                                <small class="text-muted waybill-picked-process">Pending</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Items Packed</h6>
                                <p class="timeline-text text-muted">Items have been packed and prepared for shipping</p>
                                <small class="text-muted waybill-packed-process">Pending</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Dispatched</h6>
                                <p class="timeline-text text-muted">Package has been dispatched to carrier</p>
                                <small class="text-muted waybill-dispatched-process">Pending</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-dark"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Delivered</h6>
                                <p class="timeline-text text-muted">Package has been delivered to destination</p>
                                <small class="text-muted waybill-delivered-process">Pending</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3" id="waybillNotes" style="display: none;">
                    <h6 class="text-primary">Notes</h6>
                    <p class="waybill-notes">-</p>
                </div>
                
                <style>
                .timeline {
                    position: relative;
                    padding-left: 30px;
                }
                
                .timeline::before {
                    content: '';
                    position: absolute;
                    left: 15px;
                    top: 0;
                    bottom: 0;
                    width: 2px;
                    background: #e9ecef;
                }
                
                .timeline-item {
                    position: relative;
                    margin-bottom: 20px;
                }
                
                .timeline-marker {
                    position: absolute;
                    left: -22px;
                    top: 5px;
                    width: 12px;
                    height: 12px;
                    border-radius: 50%;
                    border: 2px solid #fff;
                    box-shadow: 0 0 0 2px #e9ecef;
                }
                
                .timeline-content {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 8px;
                    border-left: 3px solid #007bff;
                }
                
                .timeline-title {
                    margin: 0 0 5px 0;
                    font-size: 14px;
                    font-weight: 600;
                    color: #495057;
                }
                
                .timeline-text {
                    margin: 0 0 5px 0;
                    font-size: 13px;
                }
                
                .timeline-item.completed .timeline-marker {
                    background: #28a745 !important;
                    box-shadow: 0 0 0 2px #28a745;
                }
                
                .timeline-item.completed .timeline-content {
                    background: #d4edda;
                    border-left-color: #28a745;
                }
                </style>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary print-waybill" id="printBtn" onclick="handlePrintClick()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Waybill Modal -->
<div class="modal fade" id="editWaybillModal" tabindex="-1" aria-labelledby="editWaybillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editWaybillModalLabel">Edit Waybill</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editWaybillForm">
                    @csrf
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Waybill Number</label>
                                <input type="text" class="form-control" id="editWaybillNumber" name="waybill_number" value="" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Outbound Order</label>
                                <select class="form-select" name="outbound_order_id" id="editOutboundOrderSelect" required disabled>
                                    <option value="">Select Outbound Order</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Carrier</label>
                                <select class="form-select" name="carrier_id" id="editCarrierSelect" required>
                                    <option value="">Select Carrier</option>
                                    <option value="1">DHL Express</option>
                                    <option value="2">FedEx</option>
                                    <option value="3">UPS</option>
                                    <option value="4">Ghana Post</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estimated Delivery Date</label>
                                <input type="date" class="form-control" name="estimated_delivery_date" id="editEstimatedDelivery" min="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tracking Number</label>
                                <input type="text" class="form-control" name="tracking_number" id="editTrackingNumber" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" id="editStatus" required>
                                    <option value="pending">Pending</option>
                                    <option value="in_transit">In Transit</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="delayed">Delayed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateWaybillBtn">
                    <i class="fas fa-save me-2"></i>Update Waybill
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Print Waybill Modal -->
<div class="modal fade" id="printWaybillModal" tabindex="-1" aria-labelledby="printWaybillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printWaybillModalLabel">Print Waybill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="print-content">
                    <div class="text-center mb-4">
                        <h4>WAYBILL</h4>
                        <p class="mb-0">Waybill #<span class="print-waybill-number">-</span></p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>From:</h6>
                            <p class="print-origin">-</p>
                        </div>
                        <div class="col-md-6">
                            <h6>To:</h6>
                            <p class="print-destination">-</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Customer:</h6>
                            <p class="print-customer-name">-</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Created:</h6>
                            <p class="print-created">-</p>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SKU</th>
                                    <th>Description</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Unit</th>
                                    <th class="text-end">Brand</th>
                                </tr>
                            </thead>
                            <tbody id="printItemsList">
                                <tr>
                                    <td colspan="6" class="text-center">No items found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printWaybillBtn">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Email Waybill Modal -->
<div class="modal fade" id="emailWaybillModal" tabindex="-1" aria-labelledby="emailWaybillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailWaybillModalLabel">Email Waybill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="emailWaybillForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="emailRecipient" class="form-label">Recipient Email</label>
                        <input type="email" class="form-control" id="emailRecipient" name="to" required>
                    </div>
                    <div class="mb-3">
                        <label for="emailSubject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="emailSubject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="emailBody" class="form-label">Message</label>
                        <textarea class="form-control" id="emailBody" name="message" rows="8" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="sendWaybillEmail">
                        <span class="spinner-border spinner-border-sm d-none me-1" role="status" aria-hidden="true"></span>
                        <i class="fas fa-envelope me-1"></i> Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Waybill Modal -->
<div class="modal fade" id="deleteWaybillModal" tabindex="-1" aria-labelledby="deleteWaybillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteWaybillModalLabel">Delete Waybill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Are you sure you want to delete this waybill? This action cannot be undone.
                </div>
                <p><strong>Waybill #:</strong> <span class="waybill-number">-</span></p>
                <p><strong>Customer:</strong> <span class="customer-name">-</span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteWaybill">
                    <span class="spinner-border spinner-border-sm d-none me-1" role="status" aria-hidden="true"></span>
                    <i class="fas fa-trash me-1"></i> Delete Waybill
                </button>
            </div>
        </div>
    </div>
</div>


@include('company.InventoryManagement.WarehouseOps.partials.waybill-modals')


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/waybill-modals.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: 'Select an option',
            allowClear: true,
            width: '100%'
        });

        // Load departments for waybill dropdown
        loadDepartmentsForWaybill();

        // Load approved requisitions for outbound orders
        loadApprovedRequisitions();
        
        // Check remaining quantities for partial issue buttons
        checkRemainingQuantities();
        
        // Load waybills
        loadWaybills();

        // Auto-fill requested by when reference number is entered
        setupReferenceLookup();
        
        // Waybill form submission
        $('#waybillForm').on('submit', function(e) {
            e.preventDefault();
            submitWaybill();
        });
        
        // Waybill event handlers
        setupWaybillEventHandlers();
        
        
        // Global function for onclick attribute
        window.handlePrintClick = function() {
            // Get waybill data from the view modal
            const waybillData = $('#viewWaybillModal').data('waybill');
            if (!waybillData) {
                return;
            }
            
            // Create print content directly
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Waybill #${waybillData.waybill_number}</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            margin: 20px; 
                            font-size: 14px;
                            line-height: 1.4;
                        }
                        .header { 
                            text-align: center; 
                            margin-bottom: 30px; 
                            border-bottom: 3px solid #0d6efd;
                            padding-bottom: 20px;
                        }
                        .header h1 { 
                            color: #0d6efd; 
                            margin-bottom: 10px; 
                            font-size: 28px;
                            font-weight: bold;
                        }
                        .info-section { 
                            margin-bottom: 25px; 
                        }
                        .info-section h3 { 
                            color: #495057; 
                            border-bottom: 2px solid #0d6efd; 
                            padding-bottom: 8px; 
                            font-size: 18px;
                            margin-bottom: 15px;
                        }
                        .info-table { 
                            width: 100%; 
                            border-collapse: collapse; 
                            margin-bottom: 15px; 
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        }
                        .info-table td { 
                            padding: 12px; 
                            border: 1px solid #dee2e6; 
                            vertical-align: top;
                        }
                        .info-table td:first-child { 
                            background-color: #f8f9fa; 
                            font-weight: bold; 
                            width: 30%; 
                            color: #495057;
                        }
                        .items-table { 
                            width: 100%; 
                            border-collapse: collapse; 
                            margin-top: 20px; 
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        }
                        .items-table th, .items-table td { 
                            padding: 12px; 
                            border: 1px solid #dee2e6; 
                            text-align: left; 
                        }
                        .items-table th { 
                            background-color: #0d6efd; 
                            color: white;
                            font-weight: bold; 
                            text-align: center;
                        }
                        .items-table .text-end { text-align: right; }
                        .text-center { text-align: center; }
                        .badge { 
                            padding: 6px 12px; 
                            border-radius: 6px; 
                            font-size: 12px; 
                            font-weight: bold; 
                            text-transform: uppercase;
                        }
                        .badge-success { background-color: #d4edda; color: #155724; }
                        .badge-warning { background-color: #fff3cd; color: #856404; }
                        .badge-danger { background-color: #f8d7da; color: #721c24; }
                        .badge-info { background-color: #d1ecf1; color: #0c5460; }
                        .badge-secondary { background-color: #e2e3e5; color: #383d41; }
                        @media print {
                            body { margin: 0; padding: 20px; }
                            .no-print { display: none; }
                            .header { page-break-inside: avoid; }
                            .info-section { page-break-inside: avoid; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <img src="${window.location.origin}/images/gesl_logo.png" alt="Company Logo" style="max-height: 60px; margin-bottom: 10px;">
                        <h1>WAYBILL</h1>
                        <p><strong>Waybill #${waybillData.waybill_number}</strong></p>
                    </div>
                    
                    <div class="info-section">
                        <h3>Basic Information</h3>
                        <table class="info-table">
                            <tr><td>Waybill Number:</td><td>${waybillData.waybill_number}</td></tr>
                            <tr><td>Tracking Number:</td><td>${waybillData.tracking_number || 'N/A'}</td></tr>
                            <tr><td>Status:</td><td><span class="badge badge-${getStatusClass(waybillData.status)}">${waybillData.status.replace('_', ' ').toUpperCase()}</span></td></tr>
                            <tr><td>Customer:</td><td>${waybillData.customer_name || waybillData.destination_name || 'N/A'}</td></tr>
                            <tr><td>Origin:</td><td>${waybillData.origin || waybillData.requisition?.department || 'N/A'}</td></tr>
                            <tr><td>Destination:</td><td>${waybillData.destination || waybillData.destination_name || 'N/A'}</td></tr>
                            <tr><td>Created:</td><td>${new Date(waybillData.created_at).toLocaleString()}</td></tr>
                            <tr><td>Expected Delivery:</td><td>${waybillData.expected_delivery_date ? new Date(waybillData.expected_delivery_date).toLocaleDateString() : 'N/A'}</td></tr>
                        </table>
                    </div>
                    
                    <div class="info-section">
                        <h3>Items</h3>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SKU</th>
                                    <th>Description</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Unit</th>
                                    <th class="text-end">Brand</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${waybillData.items && waybillData.items.length > 0 ? 
                                    waybillData.items.map((item, index) => `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${item.sku || 'N/A'}</td>
                                            <td>${item.description || 'N/A'}</td>
                                            <td class="text-end">${item.quantity || '0'}</td>
                                            <td class="text-end">${item.unit || 'EA'}</td>
                                            <td class="text-end">${item.brand || 'N/A'}</td>
                                        </tr>
                                    `).join('') : 
                                    '<tr><td colspan="6" class="text-center">No items found</td></tr>'
                                }
                            </tbody>
                        </table>
                    </div>
                    
                    ${waybillData.notes ? `
                    <div class="info-section">
                        <h3>Notes</h3>
                        <p>${waybillData.notes}</p>
                    </div>
                    ` : ''}
                </body>
                </html>
            `;
            
            // Open print window - bigger and centered
            const printWindow = window.open('', '_blank', 'width=1200,height=800,left=' + (screen.width/2 - 600) + ',top=' + (screen.height/2 - 400));
            printWindow.document.write(printContent);
            printWindow.document.close();
            
            // Wait for content to load then print
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        };
        
        // Helper function for status badge class
        function getStatusClass(status) {
            switch(status) {
                case 'pending': return 'warning';
                case 'in_transit': return 'info';
                case 'delivered': return 'success';
                case 'delayed': return 'danger';
                case 'cancelled': return 'secondary';
                default: return 'secondary';
            }
        }

        // Multi-step form handling
        let currentStep = 1;
        const totalSteps = 3;

        function showStep(step) {
            $('.step').addClass('d-none');
            $(`#step${step}`).removeClass('d-none');
            
            // Update buttons
            $('#prevBtn').toggle(step > 1);
            $('#nextBtn').toggle(step < totalSteps);
            $('#submitBtn').toggle(step === totalSteps);
        }

        $('#nextBtn').click(function() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
                
                // Update review section
                if (currentStep === totalSteps) {
                    updateReviewSection();
                }
            }
        });

        $('#prevBtn').click(function() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Add item row
        $('#addItemRow').click(function() {
            const newRow = `
                <tr>
                    <td>
                        <select class="form-select form-select-sm item-select">
                            <option value="">Select Item</option>
                            <option value="item1">Laptop</option>
                            <option value="item2">Monitor</option>
                            <option value="item3">Keyboard</option>
                        </select>
                    </td>
                    <td><small class="text-muted item-description">-</small></td>
                    <td><span class="badge bg-light text-dark item-available">0</span></td>
                    <td style="width: 100px;">
                        <input type="number" class="form-control form-control-sm quantity" min="1" value="1">
                    </td>
                    <td style="width: 100px;">
                        <select class="form-select form-select-sm uom">
                            <option>EA</option>
                            <option>PKG</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#itemsTable tbody').append(newRow);
        });

        // Remove item row
        $(document).on('click', '.remove-item', function() {
            if ($('#itemsTable tbody tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                // Reset the first row instead of removing it
                const row = $(this).closest('tr');
                row.find('select').val('').trigger('change');
                row.find('.item-description').text('-');
                row.find('.item-available').text('0');
                row.find('.quantity').val('1');
            }
        });

        // Update item details when selected
        $(document).on('change', '.item-select', function() {
            const row = $(this).closest('tr');
            const selectedItem = $(this).val();
            
            // Simulate fetching item details
            if (selectedItem) {
                const items = {
                    'item1': { description: 'Laptop Pro 15"', available: 10 },
                    'item2': { description: '27" 4K Monitor', available: 5 },
                    'item3': { description: 'Mechanical Keyboard', available: 15 }
                };
                
                const item = items[selectedItem] || { description: '-', available: 0 };
                row.find('.item-description').text(item.description);
                row.find('.item-available').text(item.available).removeClass('bg-light text-dark').addClass(item.available > 0 ? 'bg-success text-white' : 'bg-danger text-white');
            } else {
                row.find('.item-description').text('-');
                row.find('.item-available').text('0').removeClass().addClass('badge bg-light text-dark');
            }
        });

        // Validate quantity
        $(document).on('change', '.quantity', function() {
            const row = $(this).closest('tr');
            const available = parseInt(row.find('.item-available').text()) || 0;
            const quantity = parseInt($(this).val()) || 0;
            
            if (quantity > available) {
                $(this).addClass('is-invalid');
                row.find('.item-available').addClass('bg-danger text-white');
            } else {
                $(this).removeClass('is-invalid');
                row.find('.item-available').toggleClass('bg-danger', available === 0).toggleClass('bg-success', available > 0);
            }
        });


        // Form submission
        $('#submitBtn').click(function() {
            if ($('#confirmOrder').is(':checked')) {
                // Show loading state
                Swal.fire({
                    title: 'Processing...',
                    text: 'Creating outbound order',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Get form data
                const referenceNumber = $('#referenceNumber').val();
                const referenceType = $('#referenceType').val();
                
                if (!referenceNumber || !referenceType) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Information',
                        text: 'Please enter a reference number and select reference type',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                
                // First, get the requisition ID from the reference number
                $.ajax({
                    url: '{{ route("warehouse.outbound.reference-details") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        reference_number: referenceNumber,
                        reference_type: referenceType
                    },
                    success: function(response) {
                        if (response.success && response.data && response.data.requisition_id) {
                            // Collect the actual quantities entered by the user
                            const modifiedItems = [];
                            console.log('🔍 Collecting quantities from form...');
                            console.log('🔍 Found issue-qty inputs:', $('.issue-qty').length);
                            
                            $('.issue-qty').each(function(index) {
                                const issueQty = parseFloat($(this).val()) || 0;
                                const itemName = $(this).closest('tr').find('td:first strong').text().trim();
                                const originalQty = parseFloat($(this).data('original')) || 0;
                                const unit = $(this).closest('tr').find('.item-unit').val() || 'EA';
                                
                                console.log(`🔍 Item ${index + 1}: ${itemName}, Issue Qty: ${issueQty}, Original: ${originalQty}`);
                                
                                // Validate quantity
                                if (issueQty < 0) {
                                    $(this).addClass('is-invalid');
                                    $(this).val(0);
                                    return;
                                } else {
                                    $(this).removeClass('is-invalid');
                                }
                                
                                // Find the original item data to get unit price
                                let unitPrice = 0;
                                if (response.data.items && Array.isArray(response.data.items)) {
                                    const originalItem = response.data.items.find(item => 
                                        (item.item_name || item.name) === itemName
                                    );
                                    if (originalItem) {
                                        unitPrice = parseFloat(originalItem.unit_price || originalItem.price || 0);
                                        console.log(`🔍 Found original item for ${itemName}, unit price: ${unitPrice}`);
                                    }
                                }
                                
                                if (issueQty > 0) {
                                    modifiedItems.push({
                                        item_name: itemName,
                                        quantity: issueQty,
                                        unit: unit,
                                        unit_price: unitPrice
                                    });
                                    console.log(`✅ Added to modified items: ${itemName} x ${issueQty}`);
                                } else {
                                    console.log(`❌ Skipped ${itemName} - quantity is 0`);
                                }
                            });
                            
                            // Debug: Log the modified items being sent
                            console.log('📦 Modified items being sent:', modifiedItems);
                            console.log('📦 Original response data:', response.data);
                            console.log('📦 Issue quantities found:', $('.issue-qty').length);
                            
                            // Check if there are any items to issue
                            if (modifiedItems.length === 0) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'No Items to Issue',
                                    text: 'Please enter quantities greater than 0 for at least one item.',
                                    confirmButtonText: 'OK'
                                });
                                return;
                            }
                            
                            // Now call the issue requisition API with modified quantities
                            $.ajax({
                                url: '{{ url("company/warehouse/outbound/issue-requisition") }}/' + response.data.requisition_id,
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    modified_items: modifiedItems
                                },
                                success: function(issueResponse) {
                                    if (issueResponse.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Order Created!',
                                            text: 'Outbound order has been created successfully',
                                            confirmButtonText: 'OK',
                                            confirmButtonColor: '#28a745'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $('#createOutboundOrderModal').modal('hide');
                                                // Reset form
                                                currentStep = 1;
                                                showStep(1);
                                                $('#outboundOrderForm')[0].reset();
                                                // Reset items table to reference-only format
                                                $('#itemsTable tbody').html(`
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted py-4">
                                                            <i class="fas fa-search me-2"></i>
                                                            Enter a reference number to load items
                                                        </td>
                                                    </tr>
                                                `);
                                                // Reload the outbound orders table
                                                loadApprovedRequisitions();
                                                // Scroll to top of page
                                                $('html, body').animate({ scrollTop: 0 }, 500);
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: issueResponse.message || 'Failed to create outbound order',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    console.error('Error creating outbound order:', xhr);
                                    let errorMessage = 'Failed to create outbound order. Please try again.';
                                    
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                        
                                        // Handle validation errors
                                        if (xhr.responseJSON.validation_errors && Array.isArray(xhr.responseJSON.validation_errors)) {
                                            errorMessage += '\n\nIssues:\n' + xhr.responseJSON.validation_errors.join('\n');
                                        }
                                    }
                                    
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: errorMessage,
                                        confirmButtonText: 'OK'
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Reference Not Found',
                                text: 'Could not find the specified reference number',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error looking up reference:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to look up reference. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Confirmation Required',
                    text: 'Please confirm that all information is correct before submitting',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ffc107'
                });
            }
        });

        // Reset modal when closed
        $('#createOutboundOrderModal').on('hidden.bs.modal', function () {
            currentStep = 1;
            showStep(1);
            $('#outboundOrderForm')[0].reset();
        });
    });

    // Load departments for waybill dropdown
    function loadDepartmentsForWaybill() {
        console.log('Loading departments for waybill...');
        $.ajax({
            url: '{{ route("warehouse.outbound.departments") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Departments loaded:', response);
                if (response.success && response.data) {
                    populateDepartmentDropdown(response.data);
                } else {
                    showDepartmentError();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading departments:', xhr);
                showDepartmentError();
            }
        });
    }

    // Populate department dropdown with data
    function populateDepartmentDropdown(departments) {
        const select = $('#customerSelect');
        select.empty();
        select.append('<option value="">Select Customer/Department</option>');
        
        // Group departments and sub-departments
        const departmentGroups = {};
        
        departments.forEach(dept => {
            if (dept.type === 'department') {
                departmentGroups[dept.id] = {
                    department: dept,
                    subDepartments: []
                };
            } else if (dept.type === 'sub_department') {
                const parentId = 'dept_' + dept.parent_id;
                if (departmentGroups[parentId]) {
                    departmentGroups[parentId].subDepartments.push(dept);
                }
            }
        });
        
        // Add options to dropdown
        Object.values(departmentGroups).forEach(group => {
            // Add main department
            select.append(`<option value="${group.department.id}" data-type="department">
                ${group.department.name} ${group.department.head_name ? '(' + group.department.head_name + ')' : ''}
            </option>`);
            
            // Add sub-departments
            group.subDepartments.forEach(subDept => {
                select.append(`<option value="${subDept.id}" data-type="sub_department" data-parent="${group.department.name}">
                    └─ ${subDept.name} (${group.department.name})
                </option>`);
            });
        });
        
        // Reinitialize Select2
        select.select2({
            placeholder: 'Select Customer/Department',
            allowClear: true,
            width: '100%'
        });
        
        console.log('Department dropdown populated successfully');
    }

    // Show error message for department loading
    function showDepartmentError() {
        const select = $('#customerSelect');
        select.empty();
        select.append('<option value="">Error loading departments</option>');
        select.select2({
            placeholder: 'Error loading departments',
            allowClear: true,
            width: '100%'
        });
    }

    // Setup reference lookup functionality
    function setupReferenceLookup() {
        console.log('🔧 Setting up reference lookup functionality...');
        let lookupTimeout;
        
        // Listen for changes in reference number input
        $('#referenceNumber').on('input', function() {
            const referenceNumber = $(this).val().trim();
            const referenceType = $('#referenceType').val();
            
            console.log('📝 Reference number input changed:', referenceNumber);
            console.log('📝 Reference type:', referenceType);
            
            // Clear previous timeout
            if (lookupTimeout) {
                clearTimeout(lookupTimeout);
            }
            
            // Only lookup if both reference number and type are provided
            if (referenceNumber && referenceType && referenceType !== 'none') {
                console.log('⏰ Setting timeout for lookup...');
                // Debounce the lookup - wait 500ms after user stops typing
                lookupTimeout = setTimeout(() => {
                    console.log('⏰ Timeout triggered, calling lookupReferenceDetails...');
                    lookupReferenceDetails(referenceNumber, referenceType);
                }, 500);
            } else {
                console.log('🧹 Clearing requested by field...');
                // Clear the requested by field if no reference
                $('#requestedBy').val('');
            }
        });
        
        // Listen for changes in reference type
        $('#referenceType').on('change', function() {
            const referenceNumber = $('#referenceNumber').val().trim();
            const referenceType = $(this).val();
            
            console.log('🔄 Reference type changed:', referenceType);
            console.log('🔄 Current reference number:', referenceNumber);
            
            if (referenceNumber && referenceType && referenceType !== 'none') {
                console.log('🔄 Calling lookupReferenceDetails immediately...');
                lookupReferenceDetails(referenceNumber, referenceType);
            } else {
                console.log('🧹 Clearing requested by field...');
                $('#requestedBy').val('');
            }
        });
        
        console.log('✅ Reference lookup setup complete');
    }

        // Update review section
        function updateReviewSection() {
            // Update customer info
        const customerValue = $('#customerSelect').val();
        const customerText = $('#customerSelect option:selected').text();
        // Clean up the text to remove any extra formatting
        const cleanCustomerText = customerText.replace(/\s*\([^)]*\)\s*$/, '').trim();
        $('#reviewCustomer').text(cleanCustomerText || '-');
            $('#reviewReference').text($('#referenceType').val() ? `${$('#referenceType').val()}: ${$('#referenceNumber').val()}` : 'None');
            $('#reviewNotes').text($('#orderNotes').val() || 'None');
            
        // Update items - check for both old and new format
            let itemsHtml = '';
            $('#itemsTable tbody tr').each(function() {
            const $row = $(this);
            
            // Check if this is a reference-based row (new format)
            const itemName = $row.find('td:first strong').text();
            const issueQty = $row.find('.issue-qty').val();
            const unit = $row.find('.item-unit').val();
            
            if (itemName && itemName.trim() !== '' && issueQty) {
                itemsHtml += `<div class="d-flex justify-content-between">
                    <span>${itemName}</span>
                    <span>${issueQty} ${unit}</span>
                </div>`;
            } else {
                // Fallback to old format for manual items
                const item = $row.find('.item-select option:selected').text();
                const qty = $row.find('.quantity').val();
                const uom = $row.find('.uom').val();
                
                if (item && item !== 'Select Item' && qty) {
                    itemsHtml += `<div class="d-flex justify-content-between">
                        <span>${item}</span>
                        <span>${qty} ${uom}</span>
                    </div>`;
                }
                }
            });
            
            $('#reviewItems').html(itemsHtml || '<p class="text-muted mb-0">No items added</p>');
        }

    // Lookup reference details and auto-fill requested by
    function lookupReferenceDetails(referenceNumber, referenceType) {
        console.log('🔍 Looking up reference:', referenceNumber, referenceType);
        console.log('🔍 API URL:', '{{ route("warehouse.outbound.reference-details") }}');
        
        // Show loading state
        $('#requestedBy').val('Looking up...');
        
        $.ajax({
            url: '{{ route("warehouse.outbound.reference-details") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                reference_number: referenceNumber,
                reference_type: referenceType
            },
            beforeSend: function() {
                console.log('📤 Sending AJAX request...');
            },
            success: function(response) {
                console.log('✅ Reference lookup response:', response);
                console.log('✅ Response success:', response.success);
                console.log('✅ Response data:', response.data);
                
                if (response.success && response.data) {
                    console.log('✅ Auto-filling requested_by with:', response.data.requested_by);
                    // Auto-fill the requested by field
                    $('#requestedBy').val(response.data.requested_by);
                    console.log('✅ Field value after setting:', $('#requestedBy').val());
                    
                    // Optionally auto-fill other fields
                    if (response.data.department) {
                        console.log('✅ Trying to auto-fill department:', response.data.department);
                        // Try to match department in the dropdown
                        const departmentSelect = $('#customerSelect');
                        const departmentOption = departmentSelect.find(`option:contains("${response.data.department}")`).first();
                        if (departmentOption.length) {
                            departmentSelect.val(departmentOption.val()).trigger('change');
                            console.log('✅ Department auto-filled');
                        } else {
                            console.log('⚠️ Department not found in dropdown options');
                        }
                    }
                    
                    // Auto-fill items table if items exist
                    if (response.data.items && Array.isArray(response.data.items) && response.data.items.length > 0) {
                        console.log('✅ Auto-filling items table with:', response.data.items.length, 'items');
                        populateItemsTableFromReference(response.data.items);
                        // Update review section to show the items
                        setTimeout(() => {
                            updateReviewSection();
                        }, 100);
                    } else {
                        console.log('⚠️ No items found in reference');
                        // Show message that reference has no items
                $('#itemsTable tbody').html(`
                            <tr>
                                <td colspan="6" class="text-center text-warning py-4">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No items found in this reference
                                </td>
                            </tr>
                        `);
                        // Update review section to show no items
                        updateReviewSection();
                    }
                    
                    // Show success message with remaining quantity info
                    const remainingItems = response.data.total_remaining_items || 0;
                    const hasRemaining = response.data.has_remaining_quantity || false;
                    let message = `Reference found: ${response.data.requested_by} (${remainingItems} items remaining)`;
                    
                    if (!hasRemaining) {
                        message += ' - No quantity left for partial issue';
                    }
                    
                    showReferenceLookupMessage(message, hasRemaining ? 'success' : 'warning');
                } else {
                    console.log('❌ Reference not found or invalid response');
                    $('#requestedBy').val('');
                    showReferenceLookupMessage(response.message || 'Reference not found', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error looking up reference:', xhr);
                console.error('❌ Status:', status);
                console.error('❌ Error:', error);
                console.error('❌ Response text:', xhr.responseText);
                $('#requestedBy').val('');
                showReferenceLookupMessage('Error looking up reference: ' + error, 'error');
            }
        });
    }

    // Show reference lookup message
    function showReferenceLookupMessage(message, type) {
        // Remove any existing messages
        $('.reference-lookup-message').remove();
        
        // Create message element
        const messageClass = type === 'success' ? 'text-success' : 'text-danger';
        const messageElement = $(`<small class="reference-lookup-message ${messageClass} d-block mt-1">${message}</small>`);
        
        // Insert after the requested by field
        $('#requestedBy').after(messageElement);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            messageElement.fadeOut(() => messageElement.remove());
        }, 3000);
    }

    // Update warehouse processes timeline
    function updateWarehouseProcesses($modal, waybillData) {
        const status = waybillData.status;
        const createdDate = new Date(waybillData.created_at).toLocaleString();
        
        // Set created date
        $modal.find('.waybill-created-process').text(createdDate);
        
        // Reset all timeline items
        $modal.find('.timeline-item').removeClass('completed');
        
        // Mark completed processes based on status
        if (status === 'pending') {
            // Only waybill created is completed
            $modal.find('.timeline-item').first().addClass('completed');
        } else if (status === 'in_transit') {
            // Waybill created and items picked are completed
            $modal.find('.timeline-item').eq(0).addClass('completed');
            $modal.find('.timeline-item').eq(1).addClass('completed');
            $modal.find('.waybill-picked-process').text(createdDate);
        } else if (status === 'delivered') {
            // All processes are completed
            $modal.find('.timeline-item').addClass('completed');
            $modal.find('.waybill-picked-process').text(createdDate);
            $modal.find('.waybill-packed-process').text(createdDate);
            $modal.find('.waybill-dispatched-process').text(createdDate);
            $modal.find('.waybill-delivered-process').text(waybillData.actual_delivery_date ? new Date(waybillData.actual_delivery_date).toLocaleString() : createdDate);
        } else if (status === 'delayed') {
            // Waybill created, picked, packed, and dispatched (but delivery delayed)
            $modal.find('.timeline-item').eq(0).addClass('completed');
            $modal.find('.timeline-item').eq(1).addClass('completed');
            $modal.find('.timeline-item').eq(2).addClass('completed');
            $modal.find('.timeline-item').eq(3).addClass('completed');
            $modal.find('.waybill-picked-process').text(createdDate);
            $modal.find('.waybill-packed-process').text(createdDate);
            $modal.find('.waybill-dispatched-process').text(createdDate);
            $modal.find('.waybill-delivered-process').text('Delayed');
        }
    }

    // Populate items table with reference data
    function populateItemsTableFromReference(items) {
        console.log('📦 Populating items table with:', items);
        
        // Clear existing items
        $('#itemsTable tbody').empty();
        
        // Add each item as a new row (only items with remaining quantity)
        items.forEach((item, index) => {
            const itemName = item.item_name || item.name || 'Unknown Item';
            const itemId = item.item_id || item.id || '';
            const originalQty = item.original_quantity || item.quantity || 1;
            const alreadyIssued = item.already_issued || 0;
            const remainingQty = item.remaining_quantity || 0;
            const unit = item.unit || item.uom || 'EA';
            const unitPrice = item.unit_price || item.price || 0;
            
            // Only show items that have remaining quantity
            if (remainingQty > 0) {
                const rowHtml = `
                    <tr>
                        <td>
                            <strong>${itemName}</strong>
                            <input type="hidden" class="item-id" value="${itemId}">
                        </td>
                        <td>
                            <small class="text-muted">Original: ${originalQty}</small><br>
                            <small class="text-info">Issued: ${alreadyIssued}</small><br>
                            <strong class="text-success">Remaining: ${remainingQty}</strong>
                        </td>
                        <td>
                            <span class="text-muted">${originalQty}</span>
                            <input type="hidden" class="requested-qty" value="${originalQty}">
                            </td>
                        <td style="width: 120px;">
                            <input type="number" class="form-control form-control-sm issue-qty" 
                                   min="0" max="${remainingQty}" value="${remainingQty}" 
                                   data-original="${originalQty}" 
                                   data-already-issued="${alreadyIssued}"
                                   data-remaining="${remainingQty}"
                                   oninput="validateIssueQuantity(this)">
                            <div class="invalid-feedback">Please enter a valid quantity (0-${remainingQty})</div>
                        </td>
                        <td>
                            <span class="text-muted">${unit}</span>
                            <input type="hidden" class="item-unit" value="${unit}">
                            </td>
                        </tr>
                `;
                
                $('#itemsTable tbody').append(rowHtml);
            }
        });
        
        // Add validation for issue quantities
        $('.issue-qty').on('input', function() {
            const original = parseInt($(this).data('original'));
            const alreadyIssued = parseInt($(this).data('already-issued'));
            const remaining = parseInt($(this).data('remaining'));
            const issueQty = parseInt($(this).val()) || 0;
            
            // Remove previous feedback
            $(this).removeClass('is-invalid is-warning');
            $(this).next('.invalid-feedback, .warning-feedback').remove();
            
            if (issueQty > remaining) {
                $(this).addClass('is-invalid');
                $(this).after(`<div class="invalid-feedback">Cannot issue more than remaining quantity (${remaining})</div>`);
            } else if (issueQty > original) {
                $(this).addClass('is-warning');
                $(this).after(`<div class="warning-feedback text-warning small">Issuing more than originally requested (${original})</div>`);
            } else if (issueQty + alreadyIssued > original) {
                $(this).addClass('is-warning');
                $(this).after(`<div class="warning-feedback text-warning small">Total issued will exceed original request</div>`);
            }
            
            // Update review section when quantities change
            updateReviewSection();
        });
        
        console.log('✅ Items table populated with', items.length, 'items from reference');
    }

    // View requisition details
    function viewRequisitionDetails(requisitionNumber) {
        console.log('Viewing requisition details for:', requisitionNumber);
        
        // Fetch requisition details
        $.ajax({
            url: '{{ route("warehouse.outbound.reference-details") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                reference_number: requisitionNumber,
                reference_type: 'requisition'
            },
            success: function(response) {
                if (response.success && response.data) {
                    const data = response.data;
                    const itemsHtml = data.items && data.items.length > 0 ? 
                        data.items.map(item => `
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span>${item.item_name || 'Unknown Item'}</span>
                                <span>${item.quantity} ${item.unit || 'EA'}</span>
                            </div>
                        `).join('') : '<p class="text-muted">No items</p>';
                    
                    Swal.fire({
                        title: 'Requisition Details',
                        html: `
                            <div class="text-start">
                                <p><strong>Reference:</strong> ${data.reference_number}</p>
                                <p><strong>Title:</strong> ${data.title || 'No title'}</p>
                                <p><strong>Requested By:</strong> ${data.requested_by}</p>
                                <p><strong>Department:</strong> ${data.department}</p>
                                <p><strong>Status:</strong> <span class="badge bg-success">${data.status}</span></p>
                                <p><strong>Priority:</strong> <span class="badge bg-warning">${data.priority}</span></p>
                                <p><strong>Requested Date:</strong> ${data.requested_date || 'Not set'}</p>
                                <hr>
                                <h6>Items:</h6>
                                <div class="max-height-200 overflow-auto">
                                    ${itemsHtml}
                                </div>
                            </div>
                        `,
                        width: '600px',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#6c757d'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Could not load requisition details',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                console.error('Error loading requisition details:', xhr);
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to load requisition details',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    // Create outbound order from requisition
    function createOutboundOrder(requisitionNumber) {
        console.log('Creating outbound order for:', requisitionNumber);
        
        // Open the modal
        $('#createOutboundOrderModal').modal('show');
        
        // Set the reference type and number
        $('#referenceType').val('requisition').trigger('change');
        $('#referenceNumber').val(requisitionNumber);
        
        // Trigger the lookup
        setTimeout(() => {
            lookupReferenceDetails(requisitionNumber, 'requisition');
        }, 500);
    }

    // Open outbound order modal for manual entry
    function openOutboundOrderModal() {
        // Open the modal
        $('#createOutboundOrderModal').modal('show');
        
        // Set the reference type to requisition
        $('#referenceType').val('requisition').trigger('change');
        
        // Clear all fields for manual entry
        $('#referenceNumber').val('');
        $('#requestedBy').val('');
        $('#department').val('');
        $('#itemsTableBody').empty();
    }

    // Create waybill from requisition
    function createWaybill(requisitionNumber) {
        console.log('🚀 createWaybill called with:', requisitionNumber);
        
        // Always create a new waybill - no existing check
        openWaybillModal(requisitionNumber);
    }

    // Open waybill modal directly (for top button)
    function openWaybillModalDirect() {
        console.log('🚀 openWaybillModalDirect called (top button)');
        
        // Generate waybill number (WB-YYYYMMDD-XXXXXX)
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const random = Math.random().toString(36).substring(2, 8).toUpperCase();
        const waybillNumber = `WB-${year}${month}${day}-${random}`;
        
        // Generate tracking number (TRK-XXXXXXXX)
        const trackingNumber = `TRK-${Math.random().toString(36).substring(2, 10).toUpperCase()}`;
        
        console.log('✅ Generated waybill number:', waybillNumber);
        console.log('✅ Generated tracking number:', trackingNumber);
        
        // Show the modal first
        const modal = $('#newWaybillModal');
        console.log('🔍 Modal found:', modal.length);
        
        if (modal.length === 0) {
            alert('Modal not found!');
            return;
        }
        
        // Show modal immediately
        const bsModal = new bootstrap.Modal(modal[0]);
        bsModal.show();
        
        // Set values when modal is shown
        modal.on('shown.bs.modal', function() {
            console.log('🎭 Modal is now shown, setting values...');
            
            // Set waybill number
            const waybillInput = modal.find('input[name="waybill_number"]');
            console.log('🔍 Waybill input found:', waybillInput.length);
            if (waybillInput.length > 0) {
                waybillInput.val(waybillNumber);
                console.log('✅ Waybill number set:', waybillInput.val());
            }
            
            // Set tracking number
            const trackingInput = modal.find('input[name="tracking_number"]');
            console.log('🔍 Tracking input found:', trackingInput.length);
            if (trackingInput.length > 0) {
                trackingInput.val(trackingNumber);
                console.log('✅ Tracking number set:', trackingInput.val());
            }
            
            // Set delivery date
            const dateInput = modal.find('input[name="estimated_delivery_date"]');
            console.log('🔍 Date input found:', dateInput.length);
            if (dateInput.length > 0) {
                dateInput.val(now.toISOString().split('T')[0]);
                console.log('✅ Date set:', dateInput.val());
            }
            
            // Load outbound orders
            console.log('🔄 Loading outbound orders...');
            loadIssuedRequisitionsForWaybill(modal, null);
            
            // Reset other fields
            modal.find('select[name="carrier_id"]').val('');
            modal.find('select[name="status"]').val('pending');
        });
    }

    // Create additional outbound order for partial issues
    function createAdditionalOutboundOrder(requisitionNumber) {
        console.log('Creating additional outbound order for:', requisitionNumber);
        
        // Open the create outbound order modal
        $('#createOutboundOrderModal').modal('show');
        
        // Set the reference number and type
        $('#referenceNumber').val(requisitionNumber);
        $('#referenceType').val('requisition');
        
        // Trigger the reference lookup
        lookupReferenceDetails();
    }
    
    // Check if waybill already exists for the requisition
    
    
    // Open waybill modal for new waybill creation
    function openWaybillModal(requisitionNumber) {
        console.log('🚀 openWaybillModal called with:', requisitionNumber);
        
        // Generate waybill number (WB-YYYYMMDD-XXXXXX)
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const random = Math.random().toString(36).substring(2, 8).toUpperCase();
        const waybillNumber = `WB-${year}${month}${day}-${random}`;
        
        // Generate tracking number (TRK-XXXXXXXX)
        const trackingNumber = `TRK-${Math.random().toString(36).substring(2, 10).toUpperCase()}`;
        
        console.log('✅ Generated waybill number:', waybillNumber);
        console.log('✅ Generated tracking number:', trackingNumber);
        
        // Show the modal first
        const modal = $('#newWaybillModal');
        console.log('🔍 Modal found:', modal.length);
        
        if (modal.length === 0) {
            alert('Modal not found!');
            return;
        }
        
        // Show modal immediately
        const bsModal = new bootstrap.Modal(modal[0]);
        bsModal.show();
        
        // Set values when modal is shown
        modal.on('shown.bs.modal', function() {
            console.log('🎭 Modal is now shown, setting values...');
            
            // Set waybill number
            const waybillInput = modal.find('input[name="waybill_number"]');
            console.log('🔍 Waybill input found:', waybillInput.length);
            if (waybillInput.length > 0) {
                waybillInput.val(waybillNumber);
                console.log('✅ Waybill number set:', waybillInput.val());
            }
            
            // Set tracking number
            const trackingInput = modal.find('input[name="tracking_number"]');
            console.log('🔍 Tracking input found:', trackingInput.length);
            if (trackingInput.length > 0) {
                trackingInput.val(trackingNumber);
                console.log('✅ Tracking number set:', trackingInput.val());
            }
            
            // Set delivery date
            const dateInput = modal.find('input[name="estimated_delivery_date"]');
            console.log('🔍 Date input found:', dateInput.length);
            if (dateInput.length > 0) {
                dateInput.val(now.toISOString().split('T')[0]);
                console.log('✅ Date set:', dateInput.val());
            }
            
            // Load outbound orders
            console.log('🔄 Loading outbound orders...');
            loadIssuedRequisitionsForWaybill(modal, requisitionNumber);
            
            // Reset other fields
            modal.find('select[name="carrier_id"]').val('');
            modal.find('select[name="status"]').val('pending');
        });
    }

    // Load issued requisitions for waybill creation
    function loadIssuedRequisitionsForWaybill(modal, preselectedRequisition = null) {
        console.log('🔄 loadIssuedRequisitionsForWaybill called');
        const outboundOrderSelect = modal.find('select[name="outbound_order_id"]');
        console.log('🔍 Outbound order select found:', outboundOrderSelect.length);
        
        if (outboundOrderSelect.length === 0) {
            console.error('❌ Outbound order select not found!');
            return;
        }
        
        outboundOrderSelect.html('<option value="">Loading...</option>');
        
        console.log('🌐 Making AJAX call to load requisitions...');
        $.ajax({
            url: '{{ route("warehouse.outbound.issued-requisitions") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('✅ AJAX response:', response);
                console.log('✅ Response success:', response.success);
                console.log('✅ Response data:', response.data);
                console.log('✅ Data length:', response.data ? response.data.length : 'No data');
                
                if (response.success && response.data && response.data.length > 0) {
                    let options = '<option value="">Select Outbound Order</option>';
                    
                    response.data.forEach(function(requisition) {
                        const isSelected = preselectedRequisition && requisition.requisition_number === preselectedRequisition ? 'selected' : '';
                        options += `<option value="${requisition.requisition_number}" ${isSelected}>
                            ${requisition.requisition_number} - ${requisition.title || 'No title'} (${requisition.requested_by})
                        </option>`;
                    });
                    
                    console.log('✅ Setting dropdown options:', options);
                    outboundOrderSelect.html(options);
                } else {
                    console.log('❌ No data in response or empty data');
                    if (response.data && response.data.length === 0) {
                        outboundOrderSelect.html('<option value="">No issued requisitions available</option>');
                    } else {
                        outboundOrderSelect.html('<option value="">Error loading requisitions</option>');
                    }
                }
            },
            error: function(xhr) {
                console.error('❌ AJAX error:', xhr);
                outboundOrderSelect.html('<option value="">Error loading requisitions</option>');
            }
        });
    }


    // Load waybills
    function loadWaybills(page = 1) {
        $.ajax({
            url: '{{ route("warehouse.waybills.list") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                page: page,
                per_page: 15
            },
            success: function(response) {
                if (response.success) {
                    renderWaybillsTable(response.data);
                    updateWaybillsPagination(response.pagination);
                } else {
                    showEmptyWaybillsTable();
                }
            },
            error: function(xhr) {
                showEmptyWaybillsTable();
            }
        });
    }

    // Render waybills table
    function renderWaybillsTable(waybills) {
        const tbody = $('#waybillsTable tbody');
        tbody.empty();

        if (waybills.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-truck fa-2x mb-2"></i>
                            <p>No waybills found</p>
                        </div>
                    </td>
                </tr>
            `);
            return;
        }

        waybills.forEach(function(waybill) {
            const statusBadge = getStatusBadge(waybill.status);
            const row = `
                <tr>
                    <td>
                        <a href="#" class="text-primary fw-medium view-waybill" data-waybill-id="${waybill.id}">
                            ${waybill.waybill_number}
                        </a>
                    </td>
                    <td>${waybill.customer_name}</td>
                    <td>${waybill.items_count}</td>
                    <td>${waybill.origin}</td>
                    <td>${waybill.destination}</td>
                    <td>${waybill.expected_delivery_date}</td>
                    <td>GHS ${parseFloat(waybill.total_value).toFixed(2)}</td>
                    <td>
                        <span class="badge ${statusBadge}">${waybill.status.replace('_', ' ').toUpperCase()}</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-info view-waybill" data-waybill-id="${waybill.id}" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning edit-waybill" data-waybill-id="${waybill.id}" title="Edit Waybill">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Get status badge class
    function getStatusBadge(status) {
        const badges = {
            'pending': 'badge-warning',
            'in_transit': 'badge-info',
            'out_for_delivery': 'badge-primary',
            'delivered': 'badge-success',
            'returned': 'badge-secondary',
            'cancelled': 'badge-danger'
        };
        return badges[status] || 'badge-secondary';
    }

    // Show empty waybills table
    function showEmptyWaybillsTable() {
        const tbody = $('#waybillsTable tbody');
        tbody.html(`
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-truck fa-2x mb-2"></i>
                        <p>No waybills found</p>
                    </div>
                        </td>
                    </tr>
                `);
    }

    // Update waybills pagination
    function updateWaybillsPagination(pagination) {
        const paginationInfo = $('#waybillsPaginationInfo');
        const paginationContainer = $('#waybillsPaginationContainer');
        
        if (pagination && pagination.total) {
            const total = pagination.total;
            const currentPage = pagination.current_page || 1;
            const lastPage = pagination.last_page || 1;
            const perPage = pagination.per_page || 15;
            const from = pagination.from || 0;
            const to = pagination.to || 0;
            
            paginationInfo.html(`Showing ${from} to ${to} of ${total} entries`);
            
            let paginationHtml = '';
            
            // Previous button
            paginationHtml += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="loadWaybills(${currentPage - 1})" ${currentPage === 1 ? 'tabindex="-1" aria-disabled="true"' : ''}>Previous</a>
                </li>
            `;
            
            // Page numbers
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(lastPage, currentPage + 2);
            
            if (startPage > 1) {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="loadWaybills(1)">1</a></li>`;
                if (startPage > 2) {
                    paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadWaybills(${i})">${i}</a>
                    </li>
                `;
            }
            
            if (endPage < lastPage) {
                if (endPage < lastPage - 1) {
                    paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="loadWaybills(${lastPage})">${lastPage}</a></li>`;
            }
            
            // Next button
            paginationHtml += `
                <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="loadWaybills(${currentPage + 1})" ${currentPage === lastPage ? 'tabindex="-1" aria-disabled="true"' : ''}>Next</a>
                </li>
            `;
            
            paginationContainer.html(paginationHtml);
        } else {
            paginationInfo.html('No entries found');
            paginationContainer.html('');
        }
    }

    // Check remaining quantities for partial issue buttons
    function checkRemainingQuantities() {
        console.log('🔍 Checking remaining quantities for partial issue buttons...');
        
        $('.partial-issue-btn').each(function() {
            const requisitionId = $(this).data('requisition-id');
            const requisitionNumber = $(this).data('requisition-number');
            console.log('🔍 Checking requisition:', requisitionNumber, 'ID:', requisitionId);
            
            if (requisitionId && requisitionNumber) {
                checkRequisitionRemainingQuantity(requisitionId, requisitionNumber, $(this));
            } else {
                console.error('❌ Missing requisition ID or number:', {requisitionId, requisitionNumber});
                $(this).hide();
            }
        });
    }

    // Validate issue quantity input
    function validateIssueQuantity(input) {
        const value = parseFloat(input.value) || 0;
        const max = parseFloat(input.getAttribute('max')) || 0;
        const min = parseFloat(input.getAttribute('min')) || 0;
        
        if (value < min || value > max) {
            input.classList.add('is-invalid');
            input.setCustomValidity(`Please enter a value between ${min} and ${max}`);
        } else {
            input.classList.remove('is-invalid');
            input.setCustomValidity('');
        }
    }

    // Check remaining quantity for a specific requisition
    function checkRequisitionRemainingQuantity(requisitionId, requisitionNumber, buttonElement) {
        console.log('🔍 Checking remaining quantity for:', requisitionNumber);
        
        // Validate that we have the required data
        if (!requisitionNumber || requisitionNumber.trim() === '') {
            console.error('❌ No requisition number found for button');
            buttonElement.hide();
            return;
        }
        
        $.ajax({
            url: '{{ route("warehouse.outbound.reference-details") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                reference_number: requisitionNumber.trim(),
                reference_type: 'requisition'
            },
            success: function(response) {
                console.log('🔍 Response for', requisitionNumber, ':', response);
                
                if (response.success && response.data.found) {
                    const hasRemaining = response.data.has_remaining_quantity;
                    const remainingItems = response.data.total_remaining_items || 0;
                    
                    console.log('🔍 Has remaining:', hasRemaining, 'Remaining items:', remainingItems);
                    
                    if (hasRemaining && remainingItems > 0) {
                        buttonElement.show();
                        buttonElement.attr('title', `Partial Issue (${remainingItems} items remaining)`);
                        console.log('✅ Showing plus button for', requisitionNumber, '-', remainingItems, 'items remaining');
                    } else {
                        buttonElement.hide();
                        console.log('❌ Hiding plus button for', requisitionNumber, '- no remaining quantity');
                    }
                } else {
                    buttonElement.hide();
                    console.log('❌ Hiding plus button for', requisitionNumber, '- reference not found');
                }
            },
            error: function(xhr) {
                console.error('❌ Error checking remaining quantity for', requisitionNumber, ':', xhr);
                console.error('❌ Response text:', xhr.responseText);
                console.error('❌ Status:', xhr.status);
                buttonElement.hide();
            }
        });
    }

    // Submit waybill form
    function submitWaybill() {
        console.log('submitWaybill function called');
        const submitBtn = $('#saveWaybill');
        const originalText = submitBtn.html();
        
        // Show loading state
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creating...');
        submitBtn.prop('disabled', true);
        
        const formData = new FormData($('#waybillForm')[0]);
        console.log('Form data:', formData);
        console.log('Waybill number:', formData.get('waybill_number'));
        console.log('Tracking number:', formData.get('tracking_number'));
        console.log('Outbound order ID:', formData.get('outbound_order_id'));
        
        $.ajax({
            url: '{{ route("warehouse.waybills.create") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Waybill created successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Close modal and reset form
                    $('#newWaybillModal').modal('hide');
                    $('#waybillForm')[0].reset();
                    
                    // Reload waybills
                    loadWaybills();
            } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to create waybill'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to create waybill';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            },
            complete: function() {
                // Reset button state
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    }

    // Setup waybill event handlers
    function setupWaybillEventHandlers() {
        // Submit waybill form
        $(document).on('click', '#saveWaybill', function(e) {
            e.preventDefault();
            console.log('Waybill submit button clicked');
            submitWaybill();
        });
        
        // View waybill
        $(document).on('click', '.view-waybill', function(e) {
            e.preventDefault();
            const waybillId = $(this).data('waybill-id');
            viewWaybillDetails(waybillId);
        });
        
        // Update status
        $(document).on('click', '.update-status', function(e) {
            e.preventDefault();
            const waybillId = $(this).data('waybill-id');
            openUpdateStatusModal(waybillId);
        });
        
        
        // Status change handler for delivery fields
        $('#status').on('change', function() {
            const status = $(this).val();
            if (status === 'delivered') {
                $('#delivered_fields, #delivery_notes_field').show();
            } else {
                $('#delivered_fields, #delivery_notes_field').hide();
            }
        });
        
        // Update status form submission
        $('#updateStatusForm').on('submit', function(e) {
            e.preventDefault();
            updateWaybillStatus();
        });
        
        // Print button handler for view modal
        $(document).on('click', '#printBtn', function(e) {
            e.preventDefault();
            
            // Get waybill data from the view modal
            const waybillData = $('#viewWaybillModal').data('waybill');
            if (!waybillData) {
                return;
            }
            
            // Populate print modal with waybill data
            const $printModal = $('#printWaybillModal');
            $printModal.find('.modal-title').text(`Print Waybill #${waybillData.waybill_number}`);
            $printModal.find('.print-waybill-number').text(waybillData.waybill_number);
            $printModal.find('.print-customer-name').text(waybillData.customer_name);
            $printModal.find('.print-origin').text(waybillData.origin);
            $printModal.find('.print-destination').text(waybillData.destination);
            $printModal.find('.print-created').text(new Date(waybillData.created_at).toLocaleString());
            
            // Populate items for printing
            const $itemsList = $printModal.find('#printItemsList');
            $itemsList.empty();
            
            if (waybillData.items && waybillData.items.length > 0) {
                waybillData.items.forEach((item, index) => {
                    $itemsList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.sku || 'N/A'}</td>
                            <td>${item.description || 'N/A'}</td>
                            <td class="text-end">${item.quantity || '0'}</td>
                            <td class="text-end">${item.unit || 'EA'}</td>
                            <td class="text-end">${item.brand || 'N/A'}</td>
                        </tr>
                    `);
                });
            } else {
                $itemsList.append('<tr><td colspan="6" class="text-center">No items found</td></tr>');
            }
            
            // Show print modal
            const printModal = new bootstrap.Modal(document.getElementById('printWaybillModal'));
            printModal.show();
        });
        
    }

    // View waybill details
    function viewWaybillDetails(waybillId) {
        $.ajax({
            url: `{{ url('company/warehouse/waybills') }}/${waybillId}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    displayWaybillDetails(response.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to load waybill details'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load waybill details'
                });
            }
        });
    }

    // Display waybill details in modal
    function displayWaybillDetails(waybill) {
        const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary">Basic Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Waybill Number:</strong></td><td>${waybill.waybill_number}</td></tr>
                        <tr><td><strong>Tracking Number:</strong></td><td>${waybill.tracking_number}</td></tr>
                        <tr><td><strong>Status:</strong></td><td><span class="badge ${getStatusBadge(waybill.status)}">${waybill.status.replace('_', ' ').toUpperCase()}</span></td></tr>
                        <tr><td><strong>Shipment Type:</strong></td><td>${waybill.shipment_type}</td></tr>
                        <tr><td><strong>Created:</strong></td><td>${new Date(waybill.created_at).toLocaleDateString()}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary">Delivery Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Destination:</strong></td><td>${waybill.destination_name}</td></tr>
                        <tr><td><strong>Address:</strong></td><td>${waybill.destination_address}</td></tr>
                        <tr><td><strong>Contact:</strong></td><td>${waybill.destination_contact || 'N/A'}</td></tr>
                        <tr><td><strong>Phone:</strong></td><td>${waybill.destination_phone || 'N/A'}</td></tr>
                        <tr><td><strong>Expected Delivery:</strong></td><td>${waybill.expected_delivery_date ? new Date(waybill.expected_delivery_date).toLocaleDateString() : 'N/A'}</td></tr>
                    </table>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <h6 class="text-primary">Transport Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Transport Mode:</strong></td><td>${waybill.transport_mode}</td></tr>
                        <tr><td><strong>Vehicle Number:</strong></td><td>${waybill.vehicle_number || 'N/A'}</td></tr>
                        <tr><td><strong>Driver:</strong></td><td>${waybill.driver_name || 'N/A'}</td></tr>
                        <tr><td><strong>Driver Phone:</strong></td><td>${waybill.driver_phone || 'N/A'}</td></tr>
                        <tr><td><strong>Carrier:</strong></td><td>${waybill.carrier_company || 'N/A'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary">Additional Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Total Value:</strong></td><td>GHS ${parseFloat(waybill.total_value || 0).toFixed(2)}</td></tr>
                        <tr><td><strong>Total Weight:</strong></td><td>${waybill.total_weight && waybill.total_weight > 0 ? waybill.total_weight + ' kg' : 'Not specified'}</td></tr>
                        <tr><td><strong>Total Packages:</strong></td><td>${waybill.total_packages || 0}</td></tr>
                        <tr><td><strong>Requires Signature:</strong></td><td>${waybill.requires_signature ? 'Yes' : 'No'}</td></tr>
                        <tr><td><strong>Fragile:</strong></td><td>${waybill.fragile ? 'Yes' : 'No'}</td></tr>
                        <tr><td><strong>Urgent:</strong></td><td>${waybill.urgent ? 'Yes' : 'No'}</td></tr>
                    </table>
                </div>
            </div>
            ${waybill.notes ? `<div class="mt-3"><h6 class="text-primary">Notes</h6><p>${waybill.notes}</p></div>` : ''}
        `;
        
        $('#waybillDetailsContent').html(content);
        
        // Store waybill data on the modal for print functionality
        $('#viewWaybillModal').data('waybill', waybill);
        
        
        $('#viewWaybillModal').modal('show');
    }

    // Open update status modal
    function openUpdateStatusModal(waybillId) {
        // Get the waybill status from the button
        const statusButton = $(`[data-waybill-id="${waybillId}"]`);
        const currentStatus = statusButton.data('waybill-status');
        
        console.log('Opening update modal for waybill:', waybillId, 'Current status:', currentStatus);
        
        // If already delivered, show warning and don't open modal
        if (currentStatus === 'delivered') {
            Swal.fire({
                title: 'Cannot Edit',
                text: 'This waybill has already been delivered and cannot be modified.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        $('#waybill_id').val(waybillId);
        $('#status').val(currentStatus); // Set current status as default
        $('#updateStatusModal').modal('show');
    }

    // Update waybill status
    function updateWaybillStatus() {
        const waybillId = $('#waybill_id').val();
        const status = $('#status').val();
        const deliveredTo = $('#delivered_to').val();
        const deliveryNotes = $('#delivery_notes').val();
        
        $.ajax({
            url: `{{ url('company/warehouse/waybills') }}/${waybillId}/status`,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                status: status,
                delivered_to: deliveredTo,
                delivery_notes: deliveryNotes
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Waybill status updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    $('#updateStatusModal').modal('hide');
                    loadWaybills();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to update status'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update waybill status'
                });
            }
        });
    }


    // Create partial outbound order for issued requisition
    function createPartialOutboundOrder(requisitionNumber) {
        console.log('Creating partial outbound order for:', requisitionNumber);
        
        // Open the outbound order modal for partial issue
        const modal = $('#createOutboundOrderModal');
        
        // Set reference type and number
        modal.find('#referenceType').val('requisition');
        modal.find('#referenceNumber').val(requisitionNumber);
        
        // Trigger the lookup to load the requisition details
        setTimeout(() => {
            lookupReferenceDetails(requisitionNumber, 'requisition');
        }, 500);
        
        // Show the modal
        const bsModal = new bootstrap.Modal(modal[0]);
        bsModal.show();
    }

    // Pagination variables
    let currentPage = 1;
    let totalPages = 1;
    let totalItems = 0;
    let itemsPerPage = 10;

    // Load approved requisitions for outbound orders
    function loadApprovedRequisitions(page = 1) {
        currentPage = page;
        
        $.ajax({
            url: '{{ route("warehouse.outbound.approved-requisitions") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                page: page,
                per_page: itemsPerPage
            },
            success: function(response) {
                if (response.success) {
                    renderOutboundOrdersTable(response.data);
                    updatePagination(response.pagination);
                } else {
                    showEmptyOutboundTable();
                    updatePagination({});
                }
            },
            error: function(xhr) {
                console.error('Error loading approved requisitions:', xhr);
                showEmptyOutboundTable();
                updatePagination({});
            }
        });
    }

    // Update pagination controls
    function updatePagination(pagination) {
        const paginationInfo = $('#paginationInfo');
        const paginationContainer = $('#paginationContainer');
        
        if (pagination && pagination.total) {
            totalItems = pagination.total;
            totalPages = pagination.last_page || 1;
            currentPage = pagination.current_page || 1;
            
            // Update pagination info
            const start = ((currentPage - 1) * itemsPerPage) + 1;
            const end = Math.min(currentPage * itemsPerPage, totalItems);
            paginationInfo.html(`Showing <span class="fw-semibold">${start}</span> to <span class="fw-semibold">${end}</span> of <span class="fw-semibold">${totalItems}</span> entries`);
            
            // Generate pagination buttons
            let paginationHtml = '';
            
            // Previous button
            paginationHtml += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="loadApprovedRequisitions(${currentPage - 1})" ${currentPage === 1 ? 'tabindex="-1" aria-disabled="true"' : ''}>Previous</a>
                </li>
            `;
            
            // Page numbers
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);
            
            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadApprovedRequisitions(${i})">${i}</a>
                    </li>
                `;
            }
            
            // Next button
            paginationHtml += `
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="loadApprovedRequisitions(${currentPage + 1})" ${currentPage === totalPages ? 'tabindex="-1" aria-disabled="true"' : ''}>Next</a>
                </li>
            `;
            
            paginationContainer.html(paginationHtml);
        } else {
            paginationInfo.html('No entries found');
            paginationContainer.html('');
        }
    }

    // Render outbound orders table with approved requisitions
    function renderOutboundOrdersTable(requisitions) {
        const tbody = $('#outboundOrdersTableBody');
        tbody.empty();

        if (!requisitions || requisitions.length === 0) {
            showEmptyOutboundTable();
            return;
        }


        requisitions.forEach(requisition => {
            const itemCount = requisition.items ? (Array.isArray(requisition.items) ? requisition.items.length : JSON.parse(requisition.items || '[]').length) : 0;
            
            // Calculate total value from items array
            let totalValue = 0;
            if (requisition.items && Array.isArray(requisition.items)) {
                requisition.items.forEach(item => {
                    const quantity = parseFloat(item.quantity || 0);
                    const unitPrice = parseFloat(item.unit_price || 0);
                    totalValue += quantity * unitPrice;
                });
            }
            
            // Get department for table display
            const department = requisition.department || 'Unknown';
            
            // Get requester name from the prepared data
            const requester = requisition.requestor ? requisition.requestor.name : 'Unknown';
            
            const status = requisition.issued_at ? 'Issued' : 'Ready for Issue';
            const statusClass = requisition.issued_at ? 'bg-soft-success text-success' : 'bg-soft-info text-info';

            const row = `
                <tr>
                    <td>${requisition.requisition_number}</td>
                    <td>${department}</td>
                    <td>${requisition.requested_date || 'N/A'}</td>
                    <td>${requester}</td>
                    <td>${itemCount} Items</td>
                    <td>GHS ${totalValue.toLocaleString()}</td>
                    <td><span class="badge ${statusClass}">${status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="viewRequisitionDetails('${requisition.requisition_number}')" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                    ${!requisition.issued_at ? `
                        <button class="btn btn-sm btn-outline-success me-1" onclick="createOutboundOrder('${requisition.requisition_number}')" title="Create Outbound Order">
                            <i class="fas fa-truck"></i>
                        </button>
                    ` : `
                        <button class="btn btn-sm btn-outline-info me-1" onclick="createWaybill('${requisition.requisition_number}')" title="Create Waybill">
                            <i class="fas fa-file-alt"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning me-1 partial-issue-btn" 
                                onclick="createPartialOutboundOrder('${requisition.requisition_number}')" 
                                title="Partial Issue" 
                                data-requisition-id="${requisition.id}"
                                data-requisition-number="${requisition.requisition_number}"
                                style="display: none;">
                            <i class="fas fa-plus"></i>
                        </button>
                    `}
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
        
        // Check remaining quantities for partial issue buttons
        setTimeout(() => {
            checkRemainingQuantities();
        }, 500);
    }

    // Show empty table message
    function showEmptyOutboundTable() {
        const tbody = $('#outboundOrdersTableBody');
        tbody.html(`
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <h5>No Approved Requisitions</h5>
                        <p>There are no approved requisitions ready for issuing.</p>
                    </div>
                </td>
            </tr>
        `);
    }

    // Format date function
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }


    // Issue requisition
    function issueRequisition(requisitionId) {
        if (confirm('Are you sure you want to issue this requisition?')) {
            $.ajax({
                url: '{{ url("company/warehouse/outbound/issue") }}/' + requisitionId,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Requisition issued successfully!');
                        loadApprovedRequisitions(); // Reload the table
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error issuing requisition');
                }
            });
        }
    }

    // Handle view waybill
    $(document).on('click', '.view-waybill', function(e) {
        e.preventDefault();
        console.log('View waybill clicked!'); // Debug log
        
        const waybillId = $(this).data('waybill-id');
        console.log('Button data-waybill-id:', waybillId); // Debug log
        
        if (!waybillId) {
            console.error('No waybill ID found on button');
            alert('Error: No waybill ID found');
            return;
        }
        
        // Load waybill details from server
        $.ajax({
            url: '{{ route("warehouse.waybills.details", ":id") }}'.replace(':id', waybillId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const waybillData = response.data;
                    console.log('Loaded waybill data:', waybillData); // Debug log
            
                    // Populate the view modal with waybill data
                    const $modal = $('#viewWaybillModal');
                    console.log('Modal found:', $modal.length); // Debug log
                    console.log('Waybill data structure:', waybillData); // Debug log
                    
                    $modal.find('.modal-title').text(`Waybill #${waybillData.waybill_number}`);
                    
                    // Set waybill number in the basic info section
                    $modal.find('.waybill-number').text(waybillData.waybill_number);
                    
                    // Get customer name from related data
                    let customerName = 'Unknown';
                    console.log('Waybill data structure for customer:', waybillData); // Debug log
                    
                    if (waybillData.requisition && waybillData.requisition.requestor && waybillData.requisition.requestor.personal_info) {
                        const personalInfo = waybillData.requisition.requestor.personal_info;
                        const firstName = personalInfo.first_name || '';
                        const lastName = personalInfo.last_name || '';
                        customerName = (firstName + ' ' + lastName).trim() || 'Unknown';
                        console.log('Customer name from personal_info:', customerName); // Debug log
                    } else if (waybillData.destination_name) {
                        // Fallback to destination name if personal_info is not available
                        customerName = waybillData.destination_name;
                        console.log('Customer name from destination_name:', customerName); // Debug log
                    }
                    
                    $modal.find('.waybill-customer').text(customerName);
                    console.log('Customer element found:', $modal.find('.waybill-customer').length); // Debug log
                    
                    $modal.find('.waybill-status')
                        .removeClass('bg-success bg-warning bg-danger bg-primary bg-secondary')
                        .addClass(waybillData.status === 'delivered' ? 'bg-success' : 
                                 waybillData.status === 'in_transit' ? 'bg-primary' : 
                                 waybillData.status === 'delayed' ? 'bg-danger' : 
                                 waybillData.status === 'pending' ? 'bg-warning' : 'bg-secondary')
                        .text(waybillData.status.charAt(0).toUpperCase() + waybillData.status.slice(1).replace('_', ' '));
                    
                    // Use correct field names from the waybill data
                    // Get origin from department field in requisition
                    let originName = 'Unknown';
                    if (waybillData.requisition && waybillData.requisition.department) {
                        originName = waybillData.requisition.department;
                    } else if (waybillData.origin_name) {
                        originName = waybillData.origin_name;
                    }
                    $modal.find('.waybill-origin').text(originName);
                    
                    // Destination is the requestor name (already working correctly)
                    $modal.find('.waybill-destination').text(waybillData.destination_name || 'Unknown');
                    $modal.find('.waybill-created').text(new Date(waybillData.created_at).toLocaleString());
                    $modal.find('.waybill-estimated-delivery').text(waybillData.expected_delivery_date ? new Date(waybillData.expected_delivery_date).toLocaleDateString() : 'Not set');
                    $modal.find('.waybill-tracking').text(waybillData.tracking_number || 'Not provided');
                    
                    // Count items from the items array
                    let itemsCount = 0;
                    if (waybillData.items && Array.isArray(waybillData.items)) {
                        itemsCount = waybillData.items.length;
                    }
                    $modal.find('.waybill-items').text(itemsCount.toString());
                    
                    // Display weight only if it's greater than 0
                    if (waybillData.total_weight && waybillData.total_weight > 0) {
                        $modal.find('.waybill-weight').text(`${waybillData.total_weight} kg`);
                    } else {
                        $modal.find('.waybill-weight').text('Not specified');
                    }
                    
                    // Populate warehouse processes timeline
                    updateWarehouseProcesses($modal, waybillData);
            
                    // Populate items table
                    const $itemsList = $modal.find('#viewItemsList');
                    $itemsList.empty();
                    
                    console.log('Items data:', waybillData.items); // Debug log
                    console.log('Items count:', waybillData.items ? waybillData.items.length : 'No items');
                    
                    if (waybillData.items && Array.isArray(waybillData.items) && waybillData.items.length > 0) {
                        waybillData.items.forEach((item, index) => {
                            console.log(`Item ${index + 1} raw data:`, item); // Debug log
                            console.log(`Item ${index + 1} item_id:`, item.item_id); // Debug log
                            console.log(`Item ${index + 1} sku:`, item.sku); // Debug log
                            console.log(`Item ${index + 1} description:`, item.description); // Debug log
                            
                            // Use the enhanced item structure from the backend
                            const sku = item.sku || 'N/A';
                            const description = item.description || 'N/A';
                            const quantity = item.quantity || '0';
                            const unit = item.unit || 'EA';
                            const brand = item.brand || 'N/A';
                            
                            console.log(`Item ${index + 1} processed:`, { sku, description, quantity, unit, brand }); // Debug log
                            
                            $itemsList.append(`
                                <tr>
                                    <td>${sku}</td>
                                    <td>${description}</td>
                                    <td class="text-end">${quantity}</td>
                                    <td class="text-end">${unit}</td>
                                    <td class="text-end">${brand}</td>
                                </tr>
                            `);
                        });
                    } else {
                        $itemsList.append('<tr><td colspan="5" class="text-center">No items found</td></tr>');
                    }
            
            // Show notes if available
            if (waybillData.notes) {
                $modal.find('.waybill-notes').text(waybillData.notes);
                $modal.find('#waybillNotes').show();
            } else {
                $modal.find('#waybillNotes').hide();
            }
            
            // Store the waybill data in the modal for other actions
            $modal.data('waybill', waybillData);
            
            // Show the modal
                    const viewModal = new bootstrap.Modal($modal[0]);
                    viewModal.show();
                } else {
                    showToast('Error', 'Failed to load waybill details', 'danger');
                }
            },
            error: function(xhr) {
                console.error('Error loading waybill details:', xhr);
                showToast('Error', 'Failed to load waybill details', 'danger');
            }
        });
    });

    // Handle edit waybill
    $(document).on('click', '.edit-waybill', function(e) {
        e.preventDefault();
        
        const waybillId = $(this).data('waybill-id');
        console.log('Edit waybill ID:', waybillId);
        
        if (!waybillId) {
            console.error('No waybill ID found on edit button');
            showToast('Error', 'No waybill ID found', 'error');
            return;
        }
        
        // Load waybill details from server
        $.ajax({
            url: '{{ route("warehouse.waybills.details", ":id") }}'.replace(':id', waybillId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const waybillData = response.data;
                    console.log('Loaded waybill data for editing:', waybillData);
            
            // Populate the edit modal with waybill data
            const $modal = $('#editWaybillModal');
            $modal.find('.modal-title').text(`Edit Waybill #${waybillData.waybill_number}`);
            
            // Fill form fields exactly like create waybill modal
            $modal.find('#editWaybillNumber').val(waybillData.waybill_number);
            $modal.find('#editCarrierSelect').val(waybillData.carrier_id || '');
            $modal.find('#editEstimatedDelivery').val(waybillData.expected_delivery_date ? formatDateForInput(waybillData.expected_delivery_date) : '');
            $modal.find('#editTrackingNumber').val(waybillData.tracking_number || '');
            $modal.find('#editStatus').val(waybillData.status);
            
            // Load outbound orders for the select dropdown and preselect the current requisition
            loadIssuedRequisitionsForWaybill($modal, waybillData.requisition ? waybillData.requisition.requisition_number : null);
            
            // Store the waybill data in the modal for form submission
            $modal.data('waybill', waybillData);
            
                    // Show the modal
                    const editModal = new bootstrap.Modal($modal[0]);
                    editModal.show();
                } else {
                    showToast('Error', 'Failed to load waybill for editing', 'danger');
                }
            },
            error: function(xhr) {
                console.error('Error loading waybill details for editing:', xhr);
                showToast('Error', 'Failed to load waybill for editing', 'danger');
            }
        });
    });

    // Handle edit waybill form submission
    $(document).on('submit', '#editWaybillForm', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $modal = $('#editWaybillModal');
        const $submitBtn = $form.find('button[type="submit"]');
        const $spinner = $submitBtn.find('.spinner-border');
        const waybillData = $modal.data('waybill');
        
        if (!waybillData) {
            showToast('Error', 'Waybill data not found', 'danger');
            return;
        }
        
        // Show loading state
        $submitBtn.prop('disabled', true);
        $spinner.removeClass('d-none');
        
        // Collect form data
        const formData = {
            waybill_number: $form.find('#editWaybillNumber').val(),
            customer_name: $form.find('#editCustomerName').val(),
            origin: $form.find('#editOrigin').val(),
            destination: $form.find('#editDestination').val(),
            status: $form.find('#editStatus').val(),
            tracking_number: $form.find('#editTrackingNumber').val(),
            estimated_delivery: $form.find('#editEstimatedDelivery').val(),
            total_weight: $form.find('#editTotalWeight').val(),
            notes: $form.find('#editNotes').val(),
            requires_signature: $form.find('#editRequiresSignature').is(':checked'),
            fragile: $form.find('#editFragile').is(':checked'),
            urgent: $form.find('#editUrgent').is(':checked')
        };
        
        // Simulate API call (replace with actual API call)
        setTimeout(() => {
            try {
                console.log('Updating waybill:', waybillData.id, formData);
                
                // For now, just show success message
                showToast('Success', `Waybill #${formData.waybill_number} has been updated successfully`, 'success');
                
                // Close the modal
                const editModal = bootstrap.Modal.getInstance($modal[0]);
                editModal.hide();
                
                // Refresh the waybills table
                if (typeof loadWaybills === 'function') {
                    loadWaybills();
                }
                
            } catch (error) {
                console.error('Error updating waybill:', error);
                showToast('Error', 'Failed to update waybill', 'danger');
            } finally {
                // Reset button state
                $submitBtn.prop('disabled', false);
                $spinner.addClass('d-none');
            }
        }, 1500);
    });

    // Format date for date inputs
    function formatDateForInput(dateString) {
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    }

    // Show toast notification function
    function showToast(title, message, type = 'info') {
        // Check if SweetAlert2 is available
        if (typeof Swal !== 'undefined') {
            // Convert Bootstrap types to SweetAlert2 types
            const iconMap = {
                'success': 'success',
                'error': 'error',
                'danger': 'error',
                'warning': 'warning',
                'info': 'info'
            };
            
            Swal.fire({
                title: title,
                text: message,
                icon: iconMap[type] || 'info',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        } else {
            // Fallback to alert if SweetAlert2 is not available
            alert(`${title}: ${message}`);
        }
    }

    // Handle print waybill
    $(document).on('click', '.print-waybill', function(e) {
        e.preventDefault();
        try {
            const waybillDataString = $(this).data('waybill');
            if (!waybillDataString) {
                console.error('No waybill data found on print button');
                showToast('Error', 'No waybill data found', 'error');
                return;
            }
            const waybillData = JSON.parse(waybillDataString.replace(/\\'/g, "'"));
            console.log('Print waybill clicked!', waybillData);
            
            const $modal = $('#printWaybillModal');
            $modal.find('.modal-title').text(`Print Waybill #${waybillData.waybill_number}`);
            $modal.find('.print-waybill-number').text(waybillData.waybill_number);
            $modal.find('.print-customer-name').text(waybillData.customer_name);
            $modal.find('.print-origin').text(waybillData.origin);
            $modal.find('.print-destination').text(waybillData.destination);
            $modal.find('.print-created').text(new Date(waybillData.created_at).toLocaleString());
            
            // Populate items for printing
            const $itemsList = $modal.find('#printItemsList');
            $itemsList.empty();
            
            if (waybillData.items && waybillData.items.length > 0) {
                waybillData.items.forEach((item, index) => {
                    $itemsList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.sku || 'N/A'}</td>
                            <td>${item.description || 'N/A'}</td>
                            <td class="text-end">${item.quantity || '0'}</td>
                            <td class="text-end">${item.unit || 'EA'}</td>
                            <td class="text-end">${item.brand || 'N/A'}</td>
                        </tr>
                    `);
                });
            } else {
                $itemsList.append('<tr><td colspan="6" class="text-center">No items found</td></tr>');
            }
            
            // Store the waybill data in the modal for the print action
            $modal.data('waybill', waybillData);
            
            // Show the modal
            const printModal = new bootstrap.Modal($modal[0]);
            printModal.show();
            
        } catch (error) {
            console.error('Error parsing waybill data for print:', error);
            showToast('Error', 'Failed to load waybill for printing', 'danger');
        }
    });

    // Handle email waybill
    $(document).on('click', '.email-waybill', function(e) {
        e.preventDefault();
        try {
            const waybillDataString = $(this).data('waybill');
            if (!waybillDataString) {
                console.error('No waybill data found on email button');
                showToast('Error', 'No waybill data found', 'error');
                return;
            }
            const waybillData = JSON.parse(waybillDataString.replace(/\\'/g, "'"));
            console.log('Email waybill clicked!', waybillData);
            
            const $modal = $('#emailWaybillModal');
            $modal.find('.modal-title').text(`Email Waybill #${waybillData.waybill_number}`);
            $modal.find('#emailRecipient').val(waybillData.customer_name + ' <customer@example.com>');
            $modal.find('#emailSubject').val(`Waybill #${waybillData.waybill_number} - Your Shipment Details`);
            
            // Pre-fill email body with waybill details
            let emailBody = `Dear ${waybillData.customer_name},\n\n`;
            emailBody += `Please find attached the details for Waybill #${waybillData.waybill_number}.\n\n`;
            emailBody += `Origin: ${waybillData.origin}\n`;
            emailBody += `Destination: ${waybillData.destination}\n`;
            emailBody += `Status: ${waybillData.status.charAt(0).toUpperCase() + waybillData.status.slice(1).replace('_', ' ')}\n\n`;
            
            if (waybillData.tracking_number) {
                emailBody += `Tracking Number: ${waybillData.tracking_number}\n`;
            }
            if (waybillData.estimated_delivery) {
                emailBody += `Estimated Delivery: ${new Date(waybillData.estimated_delivery).toLocaleDateString()}\n`;
            }
            
            emailBody += '\nItems:\n';
            
            if (waybillData.items && waybillData.items.length > 0) {
                waybillData.items.forEach((item, index) => {
                    emailBody += `${index + 1}. ${item.quantity || '0'} x ${item.description || 'Item'} (${item.sku || 'N/A'})\n`;
                });
            }
            
            emailBody += '\nThank you for your business!\n\nBest regards,\nYour Company Name';
            
            $modal.find('#emailBody').val(emailBody);
            
            // Store the waybill data in the modal for the email action
            $modal.data('waybill', waybillData);
            
            // Show the modal
            const emailModal = new bootstrap.Modal($modal[0]);
            emailModal.show();
            
        } catch (error) {
            console.error('Error parsing waybill data for email:', error);
            showToast('Error', 'Failed to prepare waybill for email', 'danger');
        }
    });

    // Handle edit waybill form submission
    $('#editWaybillForm').on('submit', function(e) {
        e.preventDefault();
        updateWaybill();
    });
    
    // Update waybill button click handler
    $('#updateWaybillBtn').on('click', function(e) {
        e.preventDefault();
        console.log('Update button clicked!'); // Debug log
        updateWaybill();
    });

    // Update waybill function
    function updateWaybill() {
        console.log('updateWaybill function called'); // Debug log
        
        const $form = $('#editWaybillForm');
        const $modal = $('#editWaybillModal');
        const $submitBtn = $('#updateWaybillBtn');
        const waybillData = $modal.data('waybill');
        
        console.log('Form found:', $form.length); // Debug log
        console.log('Modal found:', $modal.length); // Debug log
        console.log('Button found:', $submitBtn.length); // Debug log
        console.log('Waybill data:', waybillData); // Debug log
        
        if (!waybillData) {
            console.log('No waybill data found!'); // Debug log
            showToast('Error', 'Waybill data not found', 'danger');
            return;
        }
        
        // Show loading state
        $submitBtn.prop('disabled', true);
        $submitBtn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating...');
        
        // Collect form data - only editable fields
        const formData = {
            _token: '{{ csrf_token() }}',
            waybill_number: $form.find('#editWaybillNumber').val(),
            outbound_order_id: $form.find('#editOutboundOrderSelect').val(),
            carrier_id: $form.find('#editCarrierSelect').val(),
            status: $form.find('#editStatus').val(),
            tracking_number: $form.find('#editTrackingNumber').val(),
            estimated_delivery_date: $form.find('#editEstimatedDelivery').val()
        };
        
        // Make API call to update the waybill
        console.log('Making AJAX call to:', `{{ url('company/warehouse/waybills') }}/${waybillData.id}`); // Debug log
        console.log('Form data:', formData); // Debug log
        
        $.ajax({
            url: `{{ url('company/warehouse/waybills') }}/${waybillData.id}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                console.log('Waybill update response:', response);
                
                if (response.success) {
                    showToast('Success', `Waybill #${formData.waybill_number} has been updated successfully`, 'success');
                    
                    // Close the modal
                    const editModal = bootstrap.Modal.getInstance($modal[0]);
                    editModal.hide();
                    
                    // Refresh the waybills table
                    loadWaybills();
                } else {
                    showToast('Error', response.message || 'Failed to update waybill', 'danger');
                }
            },
            error: function(xhr) {
                console.error('Error updating waybill:', xhr);
                let errorMessage = 'Failed to update waybill';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join(', ');
                }
                
                showToast('Error', errorMessage, 'danger');
            },
            complete: function() {
                // Reset button state
                $submitBtn.prop('disabled', false);
                $submitBtn.html('<i class="fas fa-save me-2"></i>Update Waybill');
            }
        });
    }

</script>

{{-- Add waybill scripts --}}
<script>
$(document).ready(function() {
    // Sample waybill data with more details for modals
    var waybillsData = [
        {
            id: 1,
            waybill_number: 'WB-2023-1001',
            customer_name: 'Acme Corporation',
            items_count: 3,
            origin: 'Main Warehouse',
            destination: '123 Business Ave, New York',
            status: 'in_transit',
            created_at: '2023-08-15T09:30:00',
            estimated_delivery: '2023-08-20',
            tracking_number: 'DHL123456789',
            carrier: 'DHL Express',
            total_weight: 1.8,
            notes: 'Handle with care - fragile items included',
            requires_signature: true,
            fragile: true,
            urgent: false,
            items: [
                { sku: 'SKU-1001', description: 'Wireless Headphones', quantity: 2, weight: '0.5 kg', dimensions: '15x10x5 cm' },
                { sku: 'SKU-2005', description: 'Bluetooth Speaker', quantity: 1, weight: '1.2 kg', dimensions: '20x15x10 cm' },
                { sku: 'SKU-3002', description: 'USB-C Cable', quantity: 2, weight: '0.1 kg', dimensions: '100x2x1 cm' }
            ]
        },
        {
            id: 2,
            waybill_number: 'WB-2023-1002',
            customer_name: 'Globex Inc',
            items_count: 3,
            origin: 'Main Warehouse',
            destination: '456 Industry St, Chicago',
            status: 'delivered',
            created_at: '2023-08-10T14:15:00',
            estimated_delivery: '2023-08-14',
            delivered_at: '2023-08-14T10:30:00',
            tracking_number: 'FEDX987654321',
            carrier: 'FedEx',
            total_weight: 3.5,
            notes: 'Delivered successfully',
            requires_signature: true,
            fragile: false,
            urgent: false,
            items: [
                { sku: 'SKU-4001', description: 'Monitor Stand', quantity: 1, weight: '2.5 kg', dimensions: '60x25x5 cm' },
                { sku: 'SKU-5003', description: 'Ergonomic Keyboard', quantity: 1, weight: '0.8 kg', dimensions: '45x15x3 cm' },
                { sku: 'SKU-6002', description: 'Wireless Mouse', quantity: 1, weight: '0.2 kg', dimensions: '10x6x3 cm' }
            ]
        },
        {
            id: 3,
            waybill_number: 'WB-2023-1003',
            customer_name: 'Soylent Corp',
            items_count: 12,
            origin: 'North Warehouse',
            destination: '789 Commerce Blvd, Los Angeles',
            status: 'pending',
            created_at: '2023-08-18T11:20:00',
            estimated_delivery: '2023-08-25',
            tracking_number: 'UPS456789123',
            carrier: 'UPS',
            total_weight: 12.6,
            notes: 'Bulk order - standard shipping',
            requires_signature: false,
            fragile: false,
            urgent: false,
            items: [
                { sku: 'SKU-7001', description: 'Desk Lamp', quantity: 5, weight: '1.5 kg', dimensions: '30x20x20 cm' },
                { sku: 'SKU-8002', description: 'Notebook Set', quantity: 5, weight: '0.8 kg', dimensions: '25x15x5 cm' },
                { sku: 'SKU-9003', description: 'Pen Set', quantity: 2, weight: '0.3 kg', dimensions: '20x5x2 cm' }
            ]
        },
        {
            id: 4,
            waybill_number: 'WB-2023-1004',
            customer_name: 'Initech LLC',
            items_count: 7,
            origin: 'South Warehouse',
            destination: '321 Office Park, Austin',
            status: 'delayed',
            created_at: '2023-08-17T16:45:00',
            estimated_delivery: '2023-08-19',
            tracking_number: 'USPS123789456',
            carrier: 'USPS',
            delay_reason: 'Weather conditions',
            total_weight: 10.5,
            notes: 'Delayed due to weather - will update delivery date',
            requires_signature: true,
            fragile: true,
            urgent: false,
            items: [
                { sku: 'SKU-1005', description: 'Laptop Stand', quantity: 3, weight: '1.2 kg', dimensions: '35x25x5 cm' },
                { sku: 'SKU-2008', description: 'Monitor Arm', quantity: 2, weight: '3.5 kg', dimensions: '50x30x10 cm' },
                { sku: 'SKU-3004', description: 'Webcam', quantity: 2, weight: '0.3 kg', dimensions: '10x5x5 cm' }
            ]
        },
        {
            id: 5,
            waybill_number: 'WB-2023-1005',
            customer_name: 'Umbrella Corp',
            items_count: 2,
            origin: 'Main Warehouse',
            destination: '654 Research Dr, Raccoon City',
            status: 'in_transit',
            created_at: '2023-08-16T10:10:00',
            estimated_delivery: '2023-08-19',
            tracking_number: 'DHL987654321',
            carrier: 'DHL Express',
            total_weight: 9.6,
            notes: 'High-value electronics - signature required',
            requires_signature: true,
            fragile: true,
            urgent: true,
            items: [
                { sku: 'SKU-4005', description: 'Gaming Monitor', quantity: 1, weight: '8.5 kg', dimensions: '120x70x15 cm' },
                { sku: 'SKU-5007', description: 'Mechanical Keyboard', quantity: 1, weight: '1.1 kg', dimensions: '44x14x4 cm' }
            ]
        }
    ];

    // The waybills will be loaded from the database via loadWaybills() function
    // which is called automatically when the page loads
    console.log('Waybills will be loaded from database via loadWaybills() function');

    // Apply filters
    function applyWaybillFilters() {
        alert('applyWaybillFilters function called!');
        
        const statusFilter = $('#waybillStatusSelect').val();
        const dateRange = $('#waybillDateRangeInput').val();
        const searchTerm = $('#waybillSearchInput').val();
        
        alert('Filter values - Search: "' + searchTerm + '", Status: "' + statusFilter + '", Date: "' + dateRange + '"');
        
        // Parse date range
        let startDate = null;
        let endDate = null;
        if (dateRange) {
            const dates = dateRange.split(' - ');
            if (dates.length === 2) {
                startDate = dates[0];
                endDate = dates[1];
            }
        }
        
        alert('About to call loadWaybillsWithFilters...');
        // Load waybills with filters
        loadWaybillsWithFilters(1, searchTerm, statusFilter, startDate, endDate);
    }
    
    // Load waybills with search and filter parameters
    function loadWaybillsWithFilters(page = 1, search = '', status = '', startDate = null, endDate = null) {
        alert('loadWaybillsWithFilters called with - Search: "' + search + '", Status: "' + status + '", StartDate: "' + startDate + '", EndDate: "' + endDate + '"');
        
        // Show loading state
        $('#waybillsPaginationInfo').html('Loading...');
        
        $.ajax({
            url: '{{ route("warehouse.waybills.list") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                page: page,
                per_page: 15,
                search: search,
                status: status,
                date_from: startDate,
                date_to: endDate
            },
            success: function(response) {
                if (response.success && response.data) {
                    renderWaybillsTable(response.data);
                    updateWaybillsPagination(response.pagination);
                } else {
                    showEmptyWaybillsTable();
                }
            },
            error: function(xhr) {
                showEmptyWaybillsTable();
            }
        });
    }
    
    // Format date for display
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }
    
    // Format date for date inputs
    function formatDateForInput(dateString) {
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    }
    
    // Handle print waybill
    $(document).on('click', '.print-waybill', function(e) {
        e.preventDefault();
        const waybillData = $(this).data('waybill') ? 
            JSON.parse($(this).data('waybill').replace(/\\'/g, "'")) : 
            $('#viewWaybillModal').data('waybill');
        
        if (waybillData) {
            // Populate print modal
            const modal = $('#printWaybillModal');
            modal.find('.waybill-number').text(waybillData.waybill_number);
            modal.find('.print-customer').text(waybillData.customer_name);
            modal.find('.print-origin').text(waybillData.origin);
            modal.find('.print-destination').text(waybillData.destination);
            modal.find('.print-created').text(formatDate(waybillData.created_at));
            
            // Populate items for printing
            const itemsList = modal.find('#printItemsList');
            itemsList.empty();
            
            waybillData.items.forEach(item => {
                itemsList.append(`
                    <tr>
                        <td>${item.sku}</td>
                        <td>${item.description}</td>
                        <td class="text-end">${item.quantity}</td>
                        <td class="text-end">${item.unit || 'EA'}</td>
                        <td class="text-end">${item.brand || 'N/A'}</td>
                    </tr>
                `);
            });
            
            // Store waybill data in modal for print action
            modal.data('waybill', waybillData);
            
            // Show the modal
            const bsModal = new bootstrap.Modal(modal[0]);
            bsModal.show();
        }
    });
    
    // Handle print button click
    $('#printWaybillBtn').on('click', function() {
        // Add print-specific styles
        const printStyles = `
            <style>
                @media print {
                    body * { visibility: hidden; }
                    .print-content, .print-content * { visibility: visible; }
                    .print-content { position: absolute; left: 0; top: 0; width: 100%; }
                    .modal-header, .modal-footer { display: none !important; }
                    .print-content { margin: 0; padding: 20px; }
                    .table { border-collapse: collapse; width: 100%; }
                    .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    .table th { background-color: #f2f2f2; font-weight: bold; }
                    .text-end { text-align: right; }
                    .text-center { text-align: center; }
                }
            </style>
        `;
        
        // Add styles to head if not already present
        if (!document.getElementById('print-styles')) {
            const styleElement = document.createElement('div');
            styleElement.id = 'print-styles';
            styleElement.innerHTML = printStyles;
            document.head.appendChild(styleElement);
        }
        
        // Print the waybill
        window.print();
    });
    
    // Handle email waybill
    $(document).on('click', '.email-waybill', function(e) {
        e.preventDefault();
        const waybillData = $(this).data('waybill') ? 
            JSON.parse($(this).data('waybill').replace(/\\'/g, "'")) : 
            $('#viewWaybillModal').data('waybill');
        
        if (waybillData) {
            // Populate email modal
            const modal = $('#emailWaybillModal');
            modal.find('.waybill-number').text(waybillData.waybill_number);
            
            // Set default email values
            const emailInput = modal.find('input[name="to"]');
            const subjectInput = modal.find('input[name="subject"]');
            const messageTextarea = modal.find('textarea[name="message"]');
            
            // Set default recipient if available
            if (waybillData.customer_email) {
                emailInput.val(waybillData.customer_email);
            } else {
                emailInput.val('');
            }
            
            // Set default subject and message
            subjectInput.val(`Waybill #${waybillData.waybill_number} - ${waybillData.customer_name}`);
            
            const defaultMessage = `Dear ${waybillData.customer_name},\n\nPlease find attached the waybill #${waybillData.waybill_number} for your records.\n\n`;
            messageTextarea.val(defaultMessage);
            
            // Store waybill data in modal for send action
            modal.data('waybill', waybillData);
            
            // Show the modal
            const bsModal = new bootstrap.Modal(modal[0]);
            bsModal.show();
            
            // Focus on the email input
            setTimeout(() => {
                emailInput.focus();
            }, 500);
        }
    });
    
    // Handle send email form submission
    $('#sendWaybillEmailForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const modal = $('#emailWaybillModal');
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        
        // Disable button and show loading state
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Sending...');
        
        // Simulate API call (replace with actual API call)
        setTimeout(() => {
            // Show success message
            showToast('Success', 'Waybill has been sent successfully!', 'success');
            
            // Hide the modal
            const bsModal = bootstrap.Modal.getInstance(modal[0]);
            bsModal.hide();
            
            // Reset form and button state
            form[0].reset();
            submitBtn.prop('disabled', false).html(originalBtnText);
        }, 1500);
    });
    
    
    
    // Initialize tooltips
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip({
            trigger: 'hover',
            placement: 'top'
        });
    });
    
    // Handle tab change to initialize DataTable when waybills tab is shown
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        if (e.target.getAttribute('href') === '#waybills') {
            // Load waybills when tab is shown
            loadWaybills();
            
            // Setup filter event handlers
            setupWaybillFilters();
            
            // Reinitialize tooltips when waybills tab is shown
            $('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip({
                trigger: 'hover',
                placement: 'top'
            });
        }
    });
    
    // Initialize date range picker for waybill filters
    if ($('#waybillDateRangeInput').length) {
        $('#waybillDateRangeInput').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD',
                applyLabel: 'Apply',
                cancelLabel: 'Clear',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            },
            opens: 'left',
            autoApply: true
        });
        
        // Handle date range selection
        $('#waybillDateRangeInput').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            applyWaybillFilters();
        });
        
        // Handle clear date range
        $('#waybillDateRangeInput').on('cancel.daterangepicker', function() {
            $(this).val('');
            applyWaybillFilters();
        });
    }
    
    // Show toast notification
    function showToast(title, message, type = 'info') {
        const toast = `
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong><br>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        const toastContainer = $('.toast-container');
        if (toastContainer.length === 0) {
            $('body').append('<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
        }
        
        const $toast = $(toast);
        $('.toast-container').append($toast);
        
        const bsToast = new bootstrap.Toast($toast[0]);
        bsToast.show();
        
        // Remove toast after it's hidden
        $toast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            bsToast.hide();
        }, 5000);
    }
    

    // Setup waybill filter event handlers
    function setupWaybillFilters() {
        alert('Setting up waybill filters...');
        
        // Apply filters when status changes
        $('#waybillStatusSelect').off('change').on('change', function() {
            const status = $(this).val();
            alert('Status changed to: ' + status);
            applyWaybillFilters();
        });
        
        // Debounced search - apply filters after user stops typing
        let searchTimeout;
        $('#waybillSearchInput').off('keyup').on('keyup', function() {
            const searchTerm = $(this).val();
            alert('Search term: ' + searchTerm);
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                alert('Applying search filter...');
                applyWaybillFilters();
            }, 500); // Wait 500ms after user stops typing
        });
        
        // Apply filters when filter button is clicked
        $('#applyWaybillFiltersBtn').off('click').on('click', function() {
            alert('Apply filters button clicked!');
            applyWaybillFilters();
        });
        
        alert('Waybill filters setup complete!');
    }
    
    // Initialize waybill filters when document is ready
    $(document).ready(function() {
        setupWaybillFilters();
        setupOutboundSearch();
        
        // Test if search and filter elements exist
        alert('Checking elements - Search: ' + $('#waybillSearchInput').length + ', Status: ' + $('#waybillStatusSelect').length + ', Date: ' + $('#waybillDateRangeInput').length + ', Button: ' + $('#applyWaybillFiltersBtn').length);
    });
    
    // Setup outbound orders search
    function setupOutboundSearch() {
        // Debounced search for outbound orders
        let outboundSearchTimeout;
        $('#outboundSearch').off('keyup').on('keyup', function() {
            const searchTerm = $(this).val();
            console.log('🔍 Outbound search:', searchTerm);
            
            clearTimeout(outboundSearchTimeout);
            outboundSearchTimeout = setTimeout(function() {
                searchOutboundOrders(searchTerm);
            }, 500);
        });
    }
    
    // Search outbound orders
    function searchOutboundOrders(searchTerm) {
        console.log('📡 Searching outbound orders:', searchTerm);
        
        // Filter the current data or reload with search
        if (searchTerm.trim() === '') {
            loadApprovedRequisitions(1);
        } else {
            // For now, filter client-side. In production, you'd want server-side search
            filterOutboundOrdersClientSide(searchTerm);
        }
    }
    
    // Filter outbound orders client-side
    function filterOutboundOrdersClientSide(searchTerm) {
        const tbody = $('#outboundOrdersTableBody');
        const rows = tbody.find('tr');
        
        rows.each(function() {
            const row = $(this);
            const text = row.text().toLowerCase();
            const search = searchTerm.toLowerCase();
            
            if (text.includes(search)) {
                row.show();
            } else {
                row.hide();
            }
        });
    }

    // Handle print waybill
    $(document).on('click', '.print-waybill', function(e) {
        e.preventDefault();
        try {
            const waybillData = JSON.parse($(this).data('waybill').replace(/\\'/g, "'"));
            const $modal = $('#printWaybillModal');
            
            // Populate the print modal with waybill data
            $modal.find('.modal-title').text(`Print Waybill #${waybillData.waybill_number}`);
            $modal.find('.print-waybill-number').text(waybillData.waybill_number);
            $modal.find('.print-customer-name').text(waybillData.customer_name);
            $modal.find('.print-origin').text(waybillData.origin);
            $modal.find('.print-destination').text(waybillData.destination);
            $modal.find('.print-created').text(new Date(waybillData.created_at).toLocaleString());
            
            // Populate items for printing
            const $itemsList = $modal.find('#printItemsList');
            $itemsList.empty();
            
            if (waybillData.items && waybillData.items.length > 0) {
                waybillData.items.forEach((item, index) => {
                    $itemsList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.sku || 'N/A'}</td>
                            <td>${item.description || 'N/A'}</td>
                            <td class="text-end">${item.quantity || '0'}</td>
                            <td class="text-end">${item.unit || 'EA'}</td>
                            <td class="text-end">${item.brand || 'N/A'}</td>
                        </tr>
                    `);
                });
            }
            
            // Store the waybill data in the modal for the print action
            $modal.data('waybill', waybillData);
            
            // Show the modal
            const printModal = new bootstrap.Modal($modal[0]);
            printModal.show();
            
        } catch (error) {
            console.error('Error parsing waybill data:', error);
            showToast('Error', 'Failed to load waybill for printing', 'danger');
        }
        $('#printWaybillModal').data('waybill-id', waybillData.id);
    });

    // Handle email waybill
    $(document).on('click', '.email-waybill', function(e) {
        e.preventDefault();
        try {
            const waybillData = JSON.parse($(this).data('waybill').replace(/\\'/g, "'"));
            const $modal = $('#emailWaybillModal');
            
            // Populate the email modal with waybill data
            $modal.find('.modal-title').text(`Email Waybill #${waybillData.waybill_number}`);
            $modal.find('#emailRecipient').val(waybillData.customer_name + ' <customer@example.com>');
            $modal.find('#emailSubject').val(`Waybill #${waybillData.waybill_number} - Your Shipment Details`);
            
            // Pre-fill email body with waybill details
            let emailBody = `Dear ${waybillData.customer_name},\n\n`;
            emailBody += `Please find attached the details for Waybill #${waybillData.waybill_number}.\n\n`;
            emailBody += `Origin: ${waybillData.origin}\n`;
            emailBody += `Destination: ${waybillData.destination}\n`;
            emailBody += `Status: ${waybillData.status.charAt(0).toUpperCase() + waybillData.status.slice(1).replace('_', ' ')}\n\n`;
            
            if (waybillData.tracking_number) {
                emailBody += `Tracking Number: ${waybillData.tracking_number}\n`;
            }
            if (waybillData.estimated_delivery) {
                emailBody += `Estimated Delivery: ${new Date(waybillData.estimated_delivery).toLocaleDateString()}\n`;
            }
            
            emailBody += '\nItems:\n';
            
            if (waybillData.items && waybillData.items.length > 0) {
                waybillData.items.forEach((item, index) => {
                    emailBody += `${index + 1}. ${item.quantity || '0'} x ${item.description || 'Item'} (${item.sku || 'N/A'})\n`;
                });
            }
            
            emailBody += '\nThank you for your business!\n\nBest regards,\nYour Company Name';
            
            $modal.find('#emailBody').val(emailBody);
            
            // Store the waybill data in the modal for the email action
            $modal.data('waybill', waybillData);
            
            // Show the modal
            const emailModal = new bootstrap.Modal($modal[0]);
            emailModal.show();
            
        } catch (error) {
            console.error('Error parsing waybill data:', error);
            showToast('Error', 'Failed to prepare waybill for email', 'danger');
        }
    });
    
    // Handle send email button
    $('#sendWaybillEmail').on('click', function() {
        const $form = $('#emailWaybillForm');
        const $button = $(this);
        const $spinner = $button.find('.spinner-border');
        
        // Show loading state
        $button.prop('disabled', true);
        $spinner.removeClass('d-none');
        
        // Simulate API call
        setTimeout(function() {
            // In a real app, you would make an API call to send the email
            console.log('Sending email:', {
                to: $form.find('#emailRecipient').val(),
                subject: $form.find('#emailSubject').val(),
                body: $form.find('#emailBody').val(),
                waybillId: $('#emailWaybillModal').data('waybill').id
            });
            
            // Hide loading state
            $button.prop('disabled', false);
            $spinner.addClass('d-none');
            
            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('emailWaybillModal'));
            modal.hide();
            
            // Show success message
            showToast('Success', 'Waybill has been sent successfully', 'success');
            
        }, 1500);
    });
    
    // Handle delete waybill
    $(document).on('click', '.delete-waybill', function(e) {
        e.preventDefault();
        try {
            const waybillData = JSON.parse($(this).data('waybill').replace(/\\'/g, "'"));
            const $modal = $('#deleteWaybillModal');
            
            // Populate the delete modal with waybill data
            $modal.find('.waybill-number').text(waybillData.waybill_number);
            $modal.find('.customer-name').text(waybillData.customer_name);
            
            // Store the waybill data in the modal for the delete action
            $modal.data('waybill', waybillData);
            
            // Show the modal
            const deleteModal = new bootstrap.Modal($modal[0]);
            deleteModal.show();
            
        } catch (error) {
            console.error('Error parsing waybill data:', error);
            showToast('Error', 'Failed to prepare waybill for deletion', 'danger');
        }
    });
    
    // Handle confirm delete button
    $('#confirmDeleteWaybill').on('click', function() {
        const $modal = $('#deleteWaybillModal');
        const waybillData = $modal.data('waybill');
        const $button = $(this);
        const $spinner = $button.find('.spinner-border');
        
        if (!waybillData) {
            showToast('Error', 'Waybill data not found', 'danger');
            return;
        }
        
        // Show loading state
        $button.prop('disabled', true);
        $spinner.removeClass('d-none');
        
        // Simulate API call
        setTimeout(() => {
            try {
                // In a real app, you would make an API call to delete the waybill
                console.log('Deleting waybill:', waybillData.id);
                
                // Remove the row from the table
                let rowToDelete = null;
                waybillsTable.rows().every(function() {
                    const data = this.data();
                    if (data.id === waybillData.id) {
                        rowToDelete = this;
                        return false; // Exit the loop
                    }
                });
                
                if (rowToDelete) {
                    rowToDelete.remove().draw();
                }
                
                // Close the modal
                const bsModal = bootstrap.Modal.getInstance($modal[0]);
                bsModal.hide();
                
                // Show success message
                showToast('Success', `Waybill #${waybillData.waybill_number} has been deleted successfully`, 'success');
                
            } catch (error) {
                console.error('Error deleting waybill:', error);
                showToast('Error', 'Failed to delete waybill', 'danger');
            } finally {
                // Reset button state
                $button.prop('disabled', false);
                $spinner.addClass('d-none');
            }
        }, 1000);
    });
});
</script>
