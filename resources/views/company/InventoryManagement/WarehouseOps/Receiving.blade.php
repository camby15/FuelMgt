<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
/* Fix Select2 dropdown visibility in modals */
.select2-container--open {
    z-index: 9999 !important;
}

/* Pagination styling */
.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    color: #007bff;
    border: 1px solid #dee2e6;
    padding: 0.375rem 0.75rem;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

.pagination .page-link:hover {
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.select2-dropdown {
    z-index: 9999 !important;
}

.select2-results__option {
    padding: 8px 12px;
    cursor: pointer;
}

.select2-results__option--highlighted {
    background-color: #007bff !important;
    color: white !important;
}

.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
    padding-right: 20px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}

/* Action buttons styling and centering */
.btn-action {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    margin: 0 0.125rem;
    display: inline-block;
    vertical-align: middle;
    cursor: pointer;
}

.btn-action i {
    font-size: 0.75rem;
}

/* Action button container alignment */
.table td:last-child {
    text-align: center;
    vertical-align: middle;
}

.table th:last-child {
    text-align: center;
    vertical-align: middle;
}

/* Action button group styling */
.d-flex.flex-nowrap.gap-1 {
    justify-content: center;
    align-items: center;
    flex-wrap: nowrap;
    gap: 0.25rem;
}

.btn-group.btn-group-sm {
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Ensure action buttons are properly centered */
.table td:last-child .d-flex,
.table td:last-child .btn-group {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    width: 100%;
}

/* Additional centering for action columns */
.table th:last-child,
.table td:last-child {
    text-align: center !important;
    vertical-align: middle !important;
    padding-left: 0.5rem !important;
    padding-right: 0.5rem !important;
}

/* Ensure button groups are centered */
.btn-group {
    display: inline-flex !important;
    justify-content: center !important;
    align-items: center !important;
}

/* Fix for flex containers in action cells */
.table td:last-child > div {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    width: 100%;
}

/* Button hover effects */
.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Ensure buttons are clickable */
.btn-action:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}
</style>

<div class="warehouse-container">
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-3" id="receivingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="po-receiving-tab" data-bs-toggle="tab" data-bs-target="#po-receiving" type="button" role="tab" aria-controls="po-receiving" aria-selected="true">
                <i class="fas fa-clipboard-list me-2"></i>Purchase Orders
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="goods-receipt-tab" data-bs-toggle="tab" data-bs-target="#goods-receipt" type="button" role="tab" aria-controls="goods-receipt" aria-selected="false">
                <i class="fas fa-truck-loading me-2"></i>Goods Receipts
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns" type="button" role="tab" aria-controls="returns" aria-selected="false">
                <i class="fas fa-undo me-2"></i>Supplier Returns
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="receivingTabsContent">
        <!-- Purchase Order Receiving -->
        <div class="tab-pane fade show active" id="po-receiving" role="tabpanel" aria-labelledby="po-receiving-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Purchase Order Receiving</h5>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newPOReceivingModal">
                    <i class="fas fa-plus me-2"></i>New PO Receiving
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="poReceivingTable" style="display: none;">
                    <thead class="table-light">
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Order Date</th>
                            <th>Expected Date</th>
                            <th>Status</th>
                            <th>Total Items</th>
                            <th>Total Value</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
                <!-- Empty state for PO table -->
                <div id="poReceivingEmptyState" class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No Purchase Orders Found</h6>
                    <p class="text-muted small">No purchase orders are available for receiving at this time.</p>
                </div>
                
                <!-- Pagination for PO table -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <nav aria-label="PO Receiving pagination">
                            <ul class="pagination justify-content-center" id="poReceivingPaginationControls">
                                <!-- Pagination controls will be inserted here by JavaScript -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Goods Receipt Tracking -->
        <div class="tab-pane fade" id="goods-receipt" role="tabpanel" aria-labelledby="goods-receipt-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Goods Receipt Tracking</h5>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="exportAllGRNsToCSV()">
                            <i class="fas fa-file-csv me-2"></i>Export All to CSV
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportAllGRNsToExcel()">
                            <i class="fas fa-file-excel me-2"></i>Export All to Excel
                        </a></li>
                    </ul>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            <div class="table-responsive">
                <table class="table table-hover" id="goodsReceiptTable" style="display: none;">
                    <thead class="table-light">
                        <tr>
                            <th>GRN #</th>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Received Date</th>
                            <th>Received By</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
                <!-- Empty state for Goods Receipt table -->
                <div id="goodsReceiptEmptyState" class="text-center py-5">
                    <i class="fas fa-truck-loading fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No Goods Receipts Found</h6>
                    <p class="text-muted small">No goods receipts have been processed yet.</p>
                </div>
                
                <!-- Pagination for Goods Receipt table -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <nav aria-label="Goods Receipt pagination">
                            <ul class="pagination justify-content-center" id="goodsReceiptPaginationControls">
                                <!-- Pagination controls will be inserted here by JavaScript -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supplier Returns -->
        <div class="tab-pane fade" id="returns" role="tabpanel" aria-labelledby="returns-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Supplier Returns Management</h5>
                <div class="btn-group">
                    <button class="btn btn-outline-secondary" id="testLoadReturns">
                        <i class="fas fa-sync me-2"></i>Refresh Returns
                    </button>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#newReturnModal">
                        <i class="fas fa-undo me-2"></i>New Return
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="returnsTable" style="display: none;">
                    <thead class="table-light">
                        <tr>
                            <th>Return #</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Total Value</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
                <!-- Empty state for Returns table -->
                <div id="returnsEmptyState" class="text-center py-5">
                    <i class="fas fa-undo fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No Supplier Returns Found</h6>
                    <p class="text-muted small">No supplier returns have been processed yet.</p>
                </div>
                
                <!-- Pagination for Returns table -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <nav aria-label="Returns pagination">
                            <ul class="pagination justify-content-center" id="returnsPaginationControls">
                                <!-- Pagination controls will be inserted here by JavaScript -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
<!-- Enhanced PO Receiving Modal -->
<div class="modal fade" id="newPOReceivingModal" tabindex="-1" aria-labelledby="newPOReceivingModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="newPOReceivingModalLabel">Purchase Order Receiving</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="poReceivingForm">
                <div class="modal-body">
                    <!-- Step 1: PO Selection -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="rec_poNumber" class="form-label">Select Pending PO</label>
                            <select class="form-select select2" id="rec_poNumber" required>
                                <option value="">Search Pending PO...</option>
                            </select>
                            <small class="text-muted">Only pending POs are available for receiving</small>
                        </div>
                        <div class="col-md-4">
                            <label for="supplier" class="form-label">Supplier</label>
                            <input type="text" class="form-control" id="supplier" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="receivingDate" class="form-label">Receiving Date</label>
                            <input type="date" class="form-control" id="receivingDate" required>
                        </div>
                    </div>

                    <!-- PO Items Table -->
                    <div class="card mb-4" id="poItemsCard" style="display: none;">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Items to Receive</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-success" id="processReceivingBtn">
                                    <i class="fas fa-check me-1"></i>Process Receiving
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                <table class="table table-sm table-hover mb-0" id="poItemsTable">
                                    <thead class="table-light sticky-top" style="position: sticky; top: 0; z-index: 10; background: white;">
                                        <tr>
                                            <th>Item</th>
                                            <th>Ordered</th>
                                            <th>Previously Received</th>
                                            <th>To Receive</th>
                                            <th>Unit</th>
                                            <th>Unit Cost</th>
                                            <th>Total</th>
                                            <th>Location</th>
                                            <th>Quality Check</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Items will be populated dynamically -->
                                    </tbody>
                                </table>
                                <!-- Empty state -->
                                <div id="poItemsEmptyState" class="text-center py-5" style="display: none;">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">No Items Available</h6>
                                    <p class="text-muted small">No items found for this purchase order.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Receiving Form -->
                    <div id="receivingFormSection" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deliveryNote" class="form-label">Delivery Note #</label>
                                    <input type="text" class="form-control" id="deliveryNote">
                                </div>
                                <div class="mb-3">
                                    <label for="vehicleNumber" class="form-label">Vehicle #</label>
                                    <input type="text" class="form-control" id="vehicleNumber">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="receivedBy" class="form-label">Received By</label>
                                    <input type="text" class="form-control" id="receivedBy" value="{{ auth()->user()->fullname ?? auth()->user()->name ?? 'Current User' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex flex-wrap gap-2 justify-content-between justify-content-sm-end bg-light" style="position: sticky; bottom: 0; z-index: 10;">
                    <button type="button" class="btn btn-secondary m-1 w-10 w-sm-auto" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success m-1 w-10 w-sm-auto" id="submitReceivingBtn" style="display: none;">
                        <i class="fas fa-check-circle me-1"></i>Complete Receiving
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View GRN Modal -->
<div class="modal fade" id="viewGRNModal" tabindex="-1" aria-labelledby="viewGRNModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewGRNModalLabel">
                    <i class="fas fa-eye me-2"></i>View GRN Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printCurrentGRN()">
                    <i class="fas fa-print me-2"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View PO Modal -->
<div class="modal fade" id="viewPOModal" tabindex="-1" aria-labelledby="viewPOModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPOModalLabel">
                    <i class="fas fa-clipboard-list me-2"></i>View Purchase Order Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- New Return Modal (for standalone returns) -->
<div class="modal fade" id="newReturnModal" tabindex="-1" aria-labelledby="newReturnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <style>
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
        
        .is-invalid + .select2-container .select2-selection {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
        
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        </style>
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="newReturnModalLabel">New Supplier Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="returnForm">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="returnSupplier" class="form-label">Supplier</label>
                            <select class="form-select select2" id="returnSupplier" required>
                                <option value="">Select Supplier</option>
                                <!-- Suppliers will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="returnDate" class="form-label">Return Date</label>
                            <input type="date" class="form-control" id="returnDate" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="returnReference" class="form-label">Reference (Receive Number)</label>
                            <select class="form-select select2" id="returnReference" required>
                                <option value="">Select Reference</option>
                                <!-- References will be loaded dynamically -->
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="returnReason" class="form-label">Reason for Return</label>
                        <select class="form-select" id="returnReason" required>
                            <option value="">Select Reason</option>
                            <option value="damaged">Damaged Goods</option>
                            <option value="wrong_item">Wrong Item Received</option>
                            <option value="excess">Excess Quantity</option>
                            <option value="quality">Quality Issues</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="returnDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="returnDescription" rows="2" required></textarea>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Items to Return</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="addReturnItem">
                                    <i class="fas fa-plus me-1"></i>Add Item
                                </button>
                            </div>
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-sm table-hover mb-0" id="returnItemsTable">
                                    <thead class="table-light sticky-top" style="position: sticky; top: 0; z-index: 10; background: white;">
                                        <tr>
                                            <th>Item</th>
                                            <th>Received Qty</th>
                                            <th>Return Qty</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                            <th>Reason</th>
                                            <th>Action</th>
                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Items will be added dynamically -->
                    </tbody>
                </table>
            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex flex-wrap gap-2 justify-content-between justify-content-sm-end">
                    <button type="button" class="btn btn-secondary m-1 w-10 w-sm-auto" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger m-1 w-10 w-sm-auto">
                        <i class="fas fa-undo me-1"></i>Submit Return
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Global functions that can be called from onclick attributes
    function exportAllGRNsToCSV() {
        console.log('Exporting all GRNs to CSV...');
        
        // Show loading
        Swal.fire({
            title: 'Preparing Export...',
            text: 'Please wait while we gather all GRN data',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Get all GRNs data using the existing goods-receipts endpoint
        $.ajax({
            url: '/company/warehouse/receivings/goods-receipts',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                page: 1,
                per_page: 1000, // Get all records
                search: '',
                date_filter: ''
            }),
            contentType: 'application/json',
            success: function(response) {
                Swal.close();
                console.log('Export response:', response);
                if (response.success && response.receipts && response.receipts.length > 0) {
                    exportGRNsToCSV(response.receipts);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Data',
                        text: 'No GRNs found to export'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                console.error('Error fetching GRNs for export:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Export Failed',
                    text: 'Failed to fetch GRN data for export'
                });
            }
        });
    }

    function exportAllGRNsToExcel() {
        console.log('Exporting all GRNs to Excel...');
        
        // Show loading
        Swal.fire({
            title: 'Preparing Export...',
            text: 'Please wait while we gather all GRN data',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Get all GRNs data using the existing goods-receipts endpoint
        $.ajax({
            url: '/company/warehouse/receivings/goods-receipts',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                page: 1,
                per_page: 1000, // Get all records
                search: '',
                date_filter: ''
            }),
            contentType: 'application/json',
            success: function(response) {
                Swal.close();
                console.log('Export response:', response);
                if (response.success && response.receipts && response.receipts.length > 0) {
                    exportGRNsToExcel(response.receipts);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Data',
                        text: 'No GRNs found to export'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                console.error('Error fetching GRNs for export:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Export Failed',
                    text: 'Failed to fetch GRN data for export'
                });
            }
        });
    }

    function exportGRNsToCSV(grnsData) {
        // Create CSV content
        let csvContent = "data:text/csv;charset=utf-8,";
        
        // Add header
        csvContent += "Goods Receipt Notes (GRNs) - Export\n";
        csvContent += `Generated on: ${new Date().toLocaleDateString()}\n\n`;
        
        // Add summary
        csvContent += `Total GRNs: ${grnsData.length}\n\n`;
        
        // Add main data table header
        csvContent += "GRN Number,PO Number,Supplier,Received Date,Received By,Status,Total Value,Received Items,Total Items\n";
        
        // Add data rows
        grnsData.forEach(grn => {
            csvContent += `${grn.grn_number},${grn.po_number},${grn.supplier},${grn.received_date},${grn.received_by},${grn.status},GH₵ ${parseFloat(grn.total_value || 0).toFixed(2)},${grn.received_items},${grn.total_items}\n`;
        });
        
        // Create download link
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `All_GRNs_${new Date().toISOString().split('T')[0]}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Export Successful',
            text: `${grnsData.length} GRNs have been exported to CSV`,
            timer: 2000,
            showConfirmButton: false
        });
    }

    function exportGRNsToExcel(grnsData) {
        // For Excel export, we'll create a more detailed format
        let csvContent = "data:text/csv;charset=utf-8,";
        
        // Add header
        csvContent += "Goods Receipt Notes (GRNs) - Detailed Export\n";
        csvContent += `Generated on: ${new Date().toLocaleDateString()}\n\n`;
        
        // Add summary
        csvContent += `Total GRNs: ${grnsData.length}\n\n`;
        
        // Add detailed data
        grnsData.forEach((grn, index) => {
            csvContent += `GRN ${index + 1}: ${grn.grn_number}\n`;
            csvContent += `PO Number,${grn.po_number}\n`;
            csvContent += `Supplier,${grn.supplier}\n`;
            csvContent += `Received Date,${grn.received_date}\n`;
            csvContent += `Received By,${grn.received_by}\n`;
            csvContent += `Status,${grn.status}\n`;
            csvContent += `Total Value,GH₵ ${parseFloat(grn.total_value || 0).toFixed(2)}\n`;
            csvContent += `Received Items,${grn.received_items}\n`;
            csvContent += `Total Items,${grn.total_items}\n\n`;
            
            csvContent += "\n" + "=".repeat(50) + "\n\n";
        });
        
        // Create download link
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `All_GRNs_Detailed_${new Date().toISOString().split('T')[0]}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Export Successful',
            text: `${grnsData.length} GRNs have been exported to Excel format`,
            timer: 2000,
            showConfirmButton: false
        });
    }

    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Load pending POs for the modal dropdown
        loadPendingPOsForModal();
        
        // Reload pending POs when modal is opened
        $('#newPOReceivingModal').on('show.bs.modal', function() {
            loadPendingPOsForModal();
            
            // Set current date as default receiving date
            const today = new Date().toISOString().split('T')[0];
            $('#receivingDate').val(today);
        });
        
        // Handle PO pre-selection after modal is fully shown and Select2 is ready
        $('#newPOReceivingModal').on('shown.bs.modal', function() {
            console.log('Modal is fully shown, checking for pre-selection...');
            
            // Pre-select PO if one was selected from the receive button
            if (window.selectedPOId) {
                console.log('Pre-selecting PO ID:', window.selectedPOId);
                
                // Wait for Select2 to be ready, then select the PO
                setTimeout(() => {
                    console.log('Attempting to pre-select PO:', window.selectedPOId);
                    
                    // Destroy existing Select2 if it exists
                    if ($('#rec_poNumber').hasClass('select2-hidden-accessible')) {
                        $('#rec_poNumber').select2('destroy');
                    }
                    
                    // Set the value
                    $('#rec_poNumber').val(window.selectedPOId);
                    
                    // Reinitialize Select2 with the value
                    $('#rec_poNumber').select2({
                        theme: 'bootstrap4',
                        placeholder: 'Search Pending PO...',
                        allowClear: true,
                        width: '100%'
                    });
                    
                    // Trigger change to load PO details
                    $('#rec_poNumber').trigger('change');
                    
                    console.log('PO pre-selected and Select2 reinitialized:', window.selectedPOId);
                    
                    // Clear the stored PO ID
                    window.selectedPOId = null;
                }, 800);
            }
        });

        // Clean up backdrop when modal is hidden
        $('#newPOReceivingModal').on('hidden.bs.modal', function() {
            setTimeout(() => {
                cleanupModalBackdrops();
            }, 150);
        });
        
        // Additional modal event handlers for better cleanup
        $('#newPOReceivingModal').on('hide.bs.modal', function() {
            console.log('Modal is being hidden');
        });
        
        $('#newPOReceivingModal').on('show.bs.modal', function() {
            console.log('Modal is being shown');
            // Clean up any existing modal state before showing
            cleanupModalBackdrops();
        });
        
        // Ensure backdrop is created after modal is shown
        $('#newPOReceivingModal').on('shown.bs.modal', function() {
            console.log('Modal is fully shown');
            // Ensure backdrop exists
            if (!$('.modal-backdrop').length) {
                $('<div class="modal-backdrop fade show"></div>').appendTo('body');
            }
        });
        
        // Force cleanup on window load to fix any existing modal issues
        $(window).on('load', function() {
            console.log('Window loaded, cleaning up any existing modal issues...');
            forceCleanupAllModals();
        });
        
        // Add CSS to ensure backdrop is visible
        $('<style>')
            .prop('type', 'text/css')
            .html(`
                .modal-backdrop {
                    background-color: rgba(0, 0, 0, 0.5) !important;
                    opacity: 0.5 !important;
                    z-index: 1040 !important;
                }
                .modal {
                    z-index: 1050 !important;
                }
            `)
            .appendTo('head');
        
        // Handle New PO Receiving button click
        $(document).on('click', 'button[data-bs-target="#newPOReceivingModal"]', function(e) {
            e.preventDefault();
            console.log('New PO Receiving button clicked');
            
            // Clean up any existing modal state
            forceCleanupAllModals();
            
            // Small delay to ensure cleanup is complete
            setTimeout(() => {
                $('#newPOReceivingModal').modal({
                    backdrop: true,
                    keyboard: true,
                    show: true
                });
            }, 100);
        });
        
        // Add comprehensive modal cleanup functions
        function cleanupModalBackdrops() {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css({
                'overflow': '', 'padding-right': '', 'position': '',
                'top': '', 'left': '', 'right': '', 'bottom': ''
            });
            $('html').css({
                'overflow': '', 'position': '', 'top': '',
                'left': '', 'right': '', 'bottom': ''
            });
            console.log('Modal cleanup completed');
        }

        function forceCleanupAllModals() {
            $('.modal-backdrop').remove();
            $('.modal').removeClass('show').css('display', 'none');
            $('body').removeClass('modal-open').css({
                'overflow': '', 'padding-right': '', 'position': '',
                'top': '', 'left': '', 'right': '', 'bottom': ''
            });
            $('html').css({
                'overflow': '', 'position': '', 'top': '',
                'left': '', 'right': '', 'bottom': ''
            });
            console.log('Force cleanup all modals completed');
        }
        
            // Handle PO selection in modal
    $(document).on('change', '#rec_poNumber', function() {
        const poId = $(this).val();
        console.log('PO selected:', poId);

        if (poId) {
            loadPODetailsForReceiving(poId);
        } else {
            // Clear form when no PO is selected
            $('#supplier').val('');
            $('#poItemsCard').hide();
            $('#receivingFormSection').hide();
            $('#submitReceivingBtn').hide();
        }
    });

    // Handle form submission
    $(document).on('submit', '#poReceivingForm', function(e) {
        e.preventDefault(); // Prevent page refresh
        console.log('Form submitted');
        
        // Validate form
        if (!validateReceivingForm()) {
            return false;
        }
        
        // Submit form via AJAX
        submitReceivingForm();
    });

    // Global variables for tracking current pages
    let currentPOPage = 1;
    let currentGoodsReceiptPage = 1;
    let currentReturnsPage = 1;

    // Load data for all tables
    loadPurchaseOrders();
    loadGoodsReceipts();
    loadSupplierReturns();

    // Tab change event to refresh data (but maintain current page)
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr('data-bs-target');
        switch(target) {
            case '#po-receiving':
                loadPurchaseOrders(currentPOPage);
                break;
            case '#goods-receipt':
                loadGoodsReceipts(currentGoodsReceiptPage);
                break;
            case '#returns':
                loadSupplierReturns(currentReturnsPage);
                break;
        }
    });

    // Function to load PO details for receiving
    function loadPODetailsForReceiving(poId) {
        console.log('Loading PO details for receiving:', poId);
        
        // Show loading state
        $('#supplier').val('Loading...');
        $('#poItemsCard').hide();
        
        $.ajax({
            url: `/company/warehouse/receivings/po-items/${poId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: JSON.stringify({}),
            success: function(response) {
                console.log('PO details response:', response);
                
                if (response.success && response.po) {
                    // Populate supplier field
                    $('#supplier').val(response.po.supplier || 'Unknown Supplier');
                    
                    // Load PO items
                    if (response.po.items && response.po.items.length > 0) {
                        loadPOItemsForReceiving(response.po.items);
                        $('#poItemsCard').show();
                        $('#receivingFormSection').show();
                        $('#submitReceivingBtn').show();
                    } else {
                        $('#poItemsCard').hide();
                        $('#receivingFormSection').hide();
                        $('#submitReceivingBtn').hide();
                        console.log('No items found for this PO');
                    }
                } else {
                    $('#supplier').val('Error loading PO details');
                    $('#poItemsCard').hide();
                    $('#receivingFormSection').hide();
                    $('#submitReceivingBtn').hide();
                    console.error('Failed to load PO details:', response.message);
                }
            },
            error: function(xhr) {
                console.error('Error loading PO details:', xhr);
                $('#supplier').val('Error loading PO details');
                $('#poItemsCard').hide();
                $('#receivingFormSection').hide();
                $('#submitReceivingBtn').hide();
            }
        });
    }

        // Function to validate receiving form
    function validateReceivingForm() {
        const poId = $('#rec_poNumber').val();
        const receivingDate = $('#receivingDate').val();
        
        if (!poId) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select a Purchase Order'
            });
            return false;
        }
        
        if (!receivingDate) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select a receiving date'
            });
            return false;
        }
        
        // Check if any items have quantities to receive
        let hasItemsToReceive = false;
        $('#poItemsTable tbody tr').each(function() {
            const toReceiveInput = $(this).find('input[type="number"]');
            const toReceiveQty = parseInt(toReceiveInput.val()) || 0;
            if (toReceiveQty > 0) {
                hasItemsToReceive = true;
                return false; // break the loop
            }
        });
        
        if (!hasItemsToReceive) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please specify quantities to receive for at least one item'
            });
            return false;
        }
        
        return true;
    }

    // Function to submit receiving form
    function submitReceivingForm() {
        const poId = $('#rec_poNumber').val();
        const receivingDate = $('#receivingDate').val();
        const deliveryNote = $('#deliveryNote').val();
        const vehicleNumber = $('#vehicleNumber').val();
        const receivedBy = $('#receivedBy').val();
        const notes = $('#notes').val();
        
        // Collect items data
        const items = [];
        $('#poItemsTable tbody tr').each(function() {
            const toReceiveInput = $(this).find('input[type="number"]');
            const locationSelect = $(this).find('select');
            const qualityCheck = $(this).find('input[type="checkbox"]');
            
            const toReceiveQty = parseInt(toReceiveInput.val()) || 0;
            const itemId = toReceiveInput.data('item-id');
            const location = locationSelect.val();
            
            if (toReceiveQty > 0) {
                items.push({
                    item_id: itemId,
                    received_qty: toReceiveQty,
                    rejected_qty: 0, // Default to 0 for now
                    location: location || 'Main Warehouse',
                    quality_check: qualityCheck.is(':checked') ? 'pass' : 'fail'
                });
            }
        });
        
        if (items.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'No Items to Receive',
                text: 'Please specify quantities to receive for at least one item'
            });
            return;
        }
        
        // Show loading
        Swal.fire({
            title: 'Processing Receiving...',
            text: 'Please wait while we process your receiving',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit via AJAX
        $.ajax({
            url: '/company/warehouse/receivings',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: JSON.stringify({
                purchase_order_id: poId,
                receiving_date: receivingDate,
                delivery_note: deliveryNote,
                vehicle_number: vehicleNumber,
                received_by: receivedBy,
                notes: notes,
                items: items
            }),
            success: function(response) {
                Swal.close();
                
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Receiving Completed!',
                        text: response.message || 'Goods have been successfully received',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        // Close modal, clear form, and refresh data
                        $('#newPOReceivingModal').modal('hide');
                        
                        // Clean up modal backdrop
                        setTimeout(() => {
                            cleanupModalBackdrop();
                        }, 150);
                        
                        clearReceivingForm();
                        refreshReceivingData();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Receiving Failed',
                        text: response.message || 'Failed to complete receiving'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                console.error('Error submitting receiving:', xhr);
                
                let errorMessage = 'Failed to complete receiving';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Receiving Failed',
                    text: errorMessage
                });
            }
        });
    }

    // Function to load PO items for receiving
    function loadPOItemsForReceiving(items) {
        console.log('Loading PO items for receiving:', items);
        
        const tbody = $('#poItemsTable tbody');
        tbody.empty();

        if (items && items.length > 0) {
            items.forEach(function(item, index) {
                const previouslyReceived = item.previously_received || 0;
                const toReceive = Math.max(0, (item.quantity || 0) - previouslyReceived);
                const itemTotal = (item.quantity || 0) * (item.unit_price || 0);
                
                const row = `
                    <tr>
                        <td>
                            <div>
                                <strong>${item.name || 'Unknown Item'}</strong>
                                ${item.description ? `<br><small class="text-muted">${item.description}</small>` : ''}
                            </div>
                        </td>
                        <td class="text-center">${item.quantity || 0}</td>
                        <td class="text-center">${previouslyReceived}</td>
                        <td class="text-center">
                            <input type="number" 
                                   class="form-control form-control-sm text-center" 
                                   value="${toReceive}" 
                                   min="0" 
                                   max="${toReceive}"
                                   data-item-id="${item.id || index}"
                                   data-original-quantity="${item.quantity || 0}"
                                   data-previously-received="${previouslyReceived}"
                                   style="width: 80px;">
                        </td>
                        <td class="text-center">${item.unit || 'pcs'}</td>
                        <td class="text-end">GH₵ ${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                        <td class="text-end">GH₵ ${itemTotal.toFixed(2)}</td>
                        <td class="text-center">
                            <select class="form-select form-select-sm" style="width: 120px;">
                                <option value="">Select Location</option>
                                <option value="main_warehouse">Main Warehouse</option>
                                <option value="secondary_warehouse">Secondary Warehouse</option>
                                <option value="cold_storage">Cold Storage</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="qualityCheck${index}">
                                <label class="form-check-label" for="qualityCheck${index}">
                                    Pass
                                </label>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
            
            $('#poItemsEmptyState').hide();
        } else {
            $('#poItemsEmptyState').show();
        }
    }

    // Function to load pending POs for modal dropdown
    function loadPendingPOsForModal() {
        console.log('Loading pending POs for modal dropdown...');
        
        $.ajax({
            url: '/company/warehouse/receivings/pending-pos',
            method: 'POST',
            data: JSON.stringify({
                page: 1,
                per_page: 100  // Get more POs for dropdown
            }),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Pending POs response:', response);
                
                if (response.success && response.pendingPOs && response.pendingPOs.length > 0) {
                    // Clear existing options
                    $('#rec_poNumber').empty();
                    $('#rec_poNumber').append('<option value="">Search Pending PO...</option>');
                    
                    // Add pending POs to dropdown
                    response.pendingPOs.forEach(function(po) {
                        $('#rec_poNumber').append(`
                            <option value="${po.id}">${po.po_number} - ${po.supplier}</option>
                        `);
                    });
                    
                // Only initialize Select2 if not already initialized and no pre-selection is pending
                if (!window.selectedPOId) {
                    // Destroy existing Select2 if it exists
                    if ($('#rec_poNumber').hasClass('select2-hidden-accessible')) {
                        $('#rec_poNumber').select2('destroy');
                    }
                    
                    // Reinitialize Select2
                    $('#rec_poNumber').select2({
                        theme: 'bootstrap4',
                        placeholder: 'Search Pending PO...',
                        allowClear: true,
                        width: '100%'
                    });
                }
                    
                    console.log('Loaded ' + response.pendingPOs.length + ' pending POs for dropdown');
                } else {
                    console.log('No pending POs found for dropdown');
                    $('#rec_poNumber').empty();
                    $('#rec_poNumber').append('<option value="">No pending POs available</option>');
                }
            },
            error: function(xhr) {
                console.error('Error loading pending POs for modal:', xhr);
                $('#rec_poNumber').empty();
                $('#rec_poNumber').append('<option value="">Error loading POs</option>');
                
                // Initialize Select2 even on error
                if ($('#rec_poNumber').hasClass('select2-hidden-accessible')) {
                    $('#rec_poNumber').select2('destroy');
                }
                
                $('#rec_poNumber').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Error loading POs',
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    }

    // Function to load Purchase Orders
    function loadPurchaseOrders(page = 1) {
        console.log('Loading purchase orders for page:', page);
        console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
        
        const requestData = {
            page: page,
            per_page: 10
        };
        console.log('Request data being sent:', requestData);
        
        $.ajax({
            url: '/company/warehouse/receivings/purchase-orders',
            method: 'POST',
            data: JSON.stringify(requestData),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Purchase orders response:', response);
                console.log('Response success:', response.success);
                console.log('Response purchaseOrders:', response.purchaseOrders);
                console.log('Response purchaseOrders length:', response.purchaseOrders ? response.purchaseOrders.length : 'undefined');
                
                const tbody = $('#poReceivingTable tbody');
                console.log('Table body element:', tbody.length > 0 ? 'Found' : 'Not found');
                tbody.empty();
                
                if (response.success && response.purchaseOrders && response.purchaseOrders.length > 0) {
                    console.log('Found ' + response.purchaseOrders.length + ' purchase orders');
                    response.purchaseOrders.forEach((po, index) => {
                        console.log('Processing PO ' + index + ':', po);
                        console.log('PO Status:', po.status, 'Type:', typeof po.status);
                        const statusBadge = getStatusBadge(po.status);
                        const actions = `
                            <div class="d-flex flex-nowrap gap-1">
                                <button class="btn btn-outline-primary btn-action btn-sm view-po" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="View PO" 
                                        data-id="${po.id}">
                                    <i class="fas fa-eye d-none d-sm-inline"></i>
                                    <span class="d-inline d-sm-none">View</span>
                                </button>
                                ${po.status === 'pending' ? `
                                <button class="btn btn-outline-success btn-action btn-sm receive-po" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="Receive Items" 
                                        data-id="${po.id}">
                                    <i class="fas fa-check-circle d-none d-sm-inline"></i>
                                    <span class="d-inline d-sm-none">Receive</span>
                                </button>
                                ` : ''}
                            </div>
                        `;
                        
                        const row = `
                            <tr>
                                <td>${po.po_number || 'N/A'}</td>
                                <td>${po.supplier || 'N/A'}</td>
                                <td>${po.order_date || 'N/A'}</td>
                                <td>${po.expected_date || 'N/A'}</td>
                                <td>${statusBadge}</td>
                                <td>${po.total_items || 0}</td>
                                <td>GH₵ ${parseFloat(po.total_value || 0).toFixed(2)}</td>
                                <td>${actions}</td>
                            </tr>
                        `;
                        console.log('Adding row:', row);
                        tbody.append(row);
                    });
                    
                    console.log('Showing table, hiding empty state');
                    // Show table, hide empty state
                    $('#poReceivingTable').show();
                    $('#poReceivingEmptyState').hide();
                    
                    // Update pagination controls
                    if (response.pagination) {
                        updatePaginationControls(response.pagination, 'poReceivingPaginationControls');
                    }
                } else {
                    console.log('No purchase orders found - showing empty state');
                    console.log('Response details:', response);
                    console.log('Response success:', response.success);
                    console.log('Response purchaseOrders exists:', !!response.purchaseOrders);
                    console.log('Response purchaseOrders length:', response.purchaseOrders ? response.purchaseOrders.length : 'undefined');
                    // Hide table, show empty state
                    $('#poReceivingTable').hide();
                    $('#poReceivingEmptyState').show();
                    $('#poReceivingPaginationControls').empty();
                }
                
                // Re-initialize tooltips for new content
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading purchase orders:', xhr);
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                console.error('Response JSON:', xhr.responseJSON);
                
                // Show error message to user
                Swal.fire({
                    icon: 'error',
                    title: 'Error Loading Purchase Orders',
                    text: 'Failed to load purchase orders. Please check the console for details.',
                    confirmButtonText: 'OK'
                });
                
                // Hide table, show empty state on error
                $('#poReceivingTable').hide();
                $('#poReceivingEmptyState').show();
                $('#poReceivingPaginationControls').empty();
            }
        });
    }

    // Function to load Goods Receipts
    function loadGoodsReceipts(page = 1) {
        console.log('Loading goods receipts for page:', page);
        
        // Get CSRF token
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log('CSRF Token:', csrfToken);
        
        $.ajax({
            url: '/company/warehouse/receivings/goods-receipts',
            method: 'POST',
            data: JSON.stringify({
                page: page,
                per_page: 10
            }),
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Goods receipts response:', response);
                console.log('Response success:', response.success);
                console.log('Response receipts:', response.receipts);
                console.log('Response receipts length:', response.receipts ? response.receipts.length : 'undefined');
                
                const tbody = $('#goodsReceiptTable tbody');
                console.log('Table body element:', tbody.length > 0 ? 'Found' : 'Not found');
                tbody.empty();
                
                if (response.success && response.receipts && response.receipts.length > 0) {
                    console.log('Found ' + response.receipts.length + ' goods receipts');
                    response.receipts.forEach((grn, index) => {
                        console.log('Processing GRN ' + index + ':', grn);
                        const statusBadge = getStatusBadge(grn.status);
                        const actions = `
                            <div class="d-flex flex-nowrap gap-1">
                                <button class="btn btn-outline-primary btn-action btn-sm view-grn" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="View GRN" 
                                        data-id="${grn.id}">
                                    <i class="fas fa-eye d-none d-sm-inline"></i>
                                    <span class="d-inline d-sm-none">View</span>
                                </button>
                                <button class="btn btn-outline-secondary btn-action btn-sm print-grn" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="Print GRN" 
                                        data-id="${grn.id}">
                                    <i class="fas fa-print d-none d-sm-inline"></i>
                                    <span class="d-inline d-sm-none">Print</span>
                                </button>
                                <button class="btn btn-outline-success btn-action btn-sm export-grn" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="Export GRN" 
                                        data-id="${grn.id}">
                                    <i class="fas fa-download d-none d-sm-inline"></i>
                                    <span class="d-inline d-sm-none">Export</span>
                                </button>
                            </div>
                        `;
                        
                        const row = `
                            <tr>
                                <td>${grn.grn_number || 'N/A'}</td>
                                <td>${grn.po_number || 'N/A'}</td>
                                <td>${grn.supplier || 'N/A'}</td>
                                <td>${grn.received_date || 'N/A'}</td>
                                <td>${grn.received_by || 'N/A'}</td>
                                <td>${grn.received_items || 0}/${grn.total_items || 0}</td>
                                <td>${statusBadge}</td>
                                <td>${actions}</td>
                            </tr>
                        `;
                        console.log('Adding GRN row:', row);
                        tbody.append(row);
                    });
                    
                    console.log('Showing goods receipt table, hiding empty state');
                    // Show table, hide empty state
                    $('#goodsReceiptTable').show();
                    $('#goodsReceiptEmptyState').hide();
                    
                    // Update pagination controls
                    if (response.pagination) {
                        updatePaginationControls(response.pagination, 'goodsReceiptPaginationControls');
                    }
                } else {
                    console.log('No goods receipts found - showing empty state');
                    console.log('Response details:', response);
                    console.log('Response success:', response.success);
                    console.log('Response receipts exists:', !!response.receipts);
                    console.log('Response receipts length:', response.receipts ? response.receipts.length : 'undefined');
                    // Hide table, show empty state
                    $('#goodsReceiptTable').hide();
                    $('#goodsReceiptEmptyState').show();
                    $('#goodsReceiptPaginationControls').empty();
                }
                
                // Re-initialize tooltips for new content
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading goods receipts:', xhr);
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                console.error('Response JSON:', xhr.responseJSON);
                
                // Show error message to user
                Swal.fire({
                    icon: 'error',
                    title: 'Error Loading Goods Receipts',
                    text: 'Failed to load goods receipts. Please check the console for details.',
                    confirmButtonText: 'OK'
                });
                
                // Hide table, show empty state on error
                $('#goodsReceiptTable').hide();
                $('#goodsReceiptEmptyState').show();
                $('#goodsReceiptPaginationControls').empty();
            }
        });
    }

    // Function to load Supplier Returns
    function loadSupplierReturns(page = 1) {
        console.log('Loading supplier returns for page:', page);
        
        // Get CSRF token
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log('CSRF Token:', csrfToken);
        
        $.ajax({
            url: '/company/warehouse/receivings/supplier-returns',
            method: 'POST',
            data: JSON.stringify({
                page: page,
                per_page: 10
            }),
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Supplier returns response:', response);
                console.log('Response success:', response.success);
                console.log('Response returns:', response.returns);
                console.log('Response returns length:', response.returns ? response.returns.length : 'undefined');
                
                const tbody = $('#returnsTable tbody');
                console.log('Table body element:', tbody.length > 0 ? 'Found' : 'Not found');
                tbody.empty();
                
                if (response.success && response.returns && response.returns.length > 0) {
                    console.log('Found ' + response.returns.length + ' supplier returns');
                    response.returns.forEach((returnItem, index) => {
                        console.log('Processing return ' + index + ':', returnItem);
                        const statusBadge = getStatusBadge(returnItem.status);
                        const actions = `
                            <div class="btn-group btn-group-sm" role="group" aria-label="Return actions">
                                <button class="btn btn-outline-primary btn-action view-return" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="View Return" 
                                        data-id="${returnItem.id}">
                                    <i class="fas fa-eye"></i>
                                    <span class="d-inline d-sm-none ms-1">View</span>
                                </button>
                                ${returnItem.status === 'pending' ? `
                                <button class="btn btn-outline-success btn-action process-return" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="Process Return" 
                                        data-id="${returnItem.id}">
                                    <i class="fas fa-check"></i>
                                    <span class="d-inline d-sm-none ms-1">Process</span>
                                </button>
                                ` : ''}
                            </div>
                        `;
                        
                        const row = `
                            <tr>
                                <td>${returnItem.return_number || 'N/A'}</td>
                                <td>${returnItem.supplier || 'N/A'}</td>
                                <td>${returnItem.return_date || 'N/A'}</td>
                                <td>${returnItem.po_number || 'N/A'}</td>
                                <td>${returnItem.items_count || 0}</td>
                                <td>${statusBadge}</td>
                                <td>GH₵ ${parseFloat(returnItem.total_value || 0).toFixed(2)}</td>
                                <td>${actions}</td>
                            </tr>
                        `;
                        console.log('Adding return row:', row);
                        tbody.append(row);
                    });
                    
                    console.log('Showing returns table, hiding empty state');
                    // Show table, hide empty state
                    $('#returnsTable').show();
                    $('#returnsEmptyState').hide();
                    
                    // Update pagination controls
                    if (response.pagination) {
                        updatePaginationControls(response.pagination, 'returnsPaginationControls');
                    }
                } else {
                    console.log('No supplier returns found - showing empty state');
                    console.log('Response details:', response);
                    console.log('Response success:', response.success);
                    console.log('Response returns exists:', !!response.returns);
                    console.log('Response returns length:', response.returns ? response.returns.length : 'undefined');
                    // Hide table, show empty state
                    $('#returnsTable').hide();
                    $('#returnsEmptyState').show();
                    $('#returnsPaginationControls').empty();
                }
                
                // Re-initialize tooltips for new content
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading supplier returns:', xhr);
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                console.error('Response JSON:', xhr.responseJSON);
                
                // Show error message to user
                Swal.fire({
                    icon: 'error',
                    title: 'Error Loading Supplier Returns',
                    text: 'Failed to load supplier returns. Please check the console for details.',
                    confirmButtonText: 'OK'
                });
                
                // Hide table, show empty state on error
                $('#returnsTable').hide();
                $('#returnsEmptyState').show();
                $('#returnsPaginationControls').empty();
            }
        });
    }

    // Helper function to get status badge
    function getStatusBadge(status) {
        const statusMap = {
            'pending': '<span class="badge bg-warning">Pending</span>',
            'completed': '<span class="badge bg-success">Completed</span>',
            'processing': '<span class="badge bg-info">Processing</span>',
            'cancelled': '<span class="badge bg-danger">Cancelled</span>',
            'returned': '<span class="badge bg-secondary">Returned</span>',
            'approved': '<span class="badge bg-primary">Approved</span>',
            'partially_received': '<span class="badge bg-info">Partially Received</span>',
            'fully_received': '<span class="badge bg-success">Fully Received</span>',
            'received': '<span class="badge bg-success">Received</span>',
            'in_progress': '<span class="badge bg-info">In Progress</span>',
            'on_hold': '<span class="badge bg-warning">On Hold</span>',
            'rejected': '<span class="badge bg-danger">Rejected</span>',
            'draft': '<span class="badge bg-secondary">Draft</span>',
            'submitted': '<span class="badge bg-primary">Submitted</span>',
            'under_review': '<span class="badge bg-info">Under Review</span>'
        };
        return statusMap[status] || '<span class="badge bg-secondary">Unknown</span>';
    }

    // Function to update pagination controls
    function updatePaginationControls(pagination, containerId) {
        console.log("Pagination data: ", pagination);
        console.log("Container ID: ", containerId);
        const paginationContainer = $('#' + containerId);
        console.log("Pagination container found: ", paginationContainer.length > 0);
        paginationContainer.empty();
        
        // Don't show pagination if only one page
        if (pagination.last_page <= 1) return;
        
        // Previous button
        const prevDisabled = pagination.current_page === 1 ? 'disabled' : '';
        paginationContainer.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link" href="#" data-page="${pagination.current_page - 1}">
                    &laquo; Previous
                </a>
            </li>
        `);
        
        // Page numbers
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.last_page, startPage + 4);
        
        for (let i = startPage; i <= endPage; i++) {
            const active = i === pagination.current_page ? 'active' : '';
            paginationContainer.append(`
                <li class="page-item ${active}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }
        
        // Next button
        const nextDisabled = pagination.current_page === pagination.last_page ? 'disabled' : '';
        paginationContainer.append(`
            <li class="page-item ${nextDisabled}">
                <a class="page-link" href="#" data-page="${pagination.current_page + 1}">
                    Next &raquo;
                </a>
            </li>
        `);
    }

    // Handle pagination clicks (single handler for all tables)
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        console.log('Pagination clicked - Page:', page);
        console.log('Element clicked:', $(this));
        
        if (page) {
            // Determine which table to update based on the container
            const container = $(this).closest('ul');
            const containerId = container.attr('id');
            console.log('Container ID:', containerId);
            
            if (containerId === 'poReceivingPaginationControls') {
                console.log('Loading PO page:', page);
                currentPOPage = page;
                loadPurchaseOrders(page);
            } else if (containerId === 'goodsReceiptPaginationControls') {
                console.log('Loading Goods Receipt page:', page);
                currentGoodsReceiptPage = page;
                loadGoodsReceipts(page);
            } else if (containerId === 'returnsPaginationControls') {
                console.log('Loading Returns page:', page);
                currentReturnsPage = page;
                loadSupplierReturns(page);
            }
        }
    });

    // Event handlers for action buttons
    $(document).on('click', '.view-po', function() {
        const poId = $(this).data('id');
        console.log('View PO clicked for ID:', poId);
        
        // Show loading
        Swal.fire({
            title: 'Loading PO Details...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: `/company/warehouse/receivings/po-details/${poId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    const po = response.po;
                    const modalBody = $('#viewPOModal .modal-body');
                    
                    modalBody.html(`
                        <div class="container-fluid px-4">
                            <!-- Purchase Order Information Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header bg-light border-bottom">
                                            <h6 class="mb-0 text-dark">
                                                <i class="fas fa-info-circle me-2 text-muted"></i>Purchase Order Information
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">PO Number</small>
                                                        <strong class="text-dark">${po.po_number}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">Supplier</small>
                                                        <strong class="text-dark">${po.supplier}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">Order Date</small>
                                                        <strong class="text-dark">${po.order_date}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">Expected Date</small>
                                                        <strong class="text-dark">${po.expected_date}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">Status</small>
                                                        <div>${getStatusBadge(po.status)}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">Total Value</small>
                                                        <strong class="text-dark fs-5">GH₵ ${parseFloat(po.total_value).toFixed(2)}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Items Section -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header bg-light border-bottom">
                                            <h6 class="mb-0 text-dark">
                                                <i class="fas fa-boxes me-2 text-muted"></i>Items Ordered (${po.items.length} items)
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <thead>
                                                        <tr class="border-bottom">
                                                            <th class="px-4 py-3 text-muted fw-normal">Item Name</th>
                                                            <th class="px-4 py-3 text-center text-muted fw-normal">Quantity</th>
                                                            <th class="px-4 py-3 text-center text-muted fw-normal">Unit</th>
                                                            <th class="px-4 py-3 text-end text-muted fw-normal">Unit Price</th>
                                                            <th class="px-4 py-3 text-end text-muted fw-normal">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        ${po.items.map((item, index) => `
                                                            <tr class="border-bottom">
                                                                <td class="px-4 py-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3 border" style="width: 32px; height: 32px; font-size: 11px; font-weight: 500;">
                                                                            ${index + 1}
                                                                        </div>
                                                                        <div>
                                                                            <span class="text-dark">${item.name}</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-3 text-center">
                                                                    <span class="text-dark fw-medium">${item.quantity}</span>
                                                                </td>
                                                                <td class="px-4 py-3 text-center">
                                                                    <small class="text-muted">${item.unit || 'pcs'}</small>
                                                                </td>
                                                                <td class="px-4 py-3 text-end">
                                                                    <span class="text-dark">GH₵ ${parseFloat(item.unit_price || 0).toFixed(2)}</span>
                                                                </td>
                                                                <td class="px-4 py-3 text-end">
                                                                    <span class="text-dark fw-medium">GH₵ ${(parseFloat(item.quantity) * parseFloat(item.unit_price || 0)).toFixed(2)}</span>
                                                                </td>
                                                            </tr>
                                                        `).join('')}
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="border-top">
                                                            <td colspan="4" class="px-4 py-3 text-end">
                                                                <strong class="text-dark">Total Value:</strong>
                                                            </td>
                                                            <td class="px-4 py-3 text-end">
                                                                <strong class="text-dark fs-5">GH₵ ${parseFloat(po.total_value).toFixed(2)}</strong>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    
                    $('#viewPOModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to load PO details'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                console.log('View PO AJAX error:', xhr);
                console.log('Error response:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load PO details: ' + (xhr.responseJSON?.message || xhr.statusText)
                });
            }
        });
    });

    $(document).on('click', 'button.receive-po', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const poId = $(this).data('id');
        console.log('=== RECEIVE BUTTON CLICKED ===');
        console.log('Receive PO clicked for ID:', poId);
        console.log('Button element:', this);
        console.log('Modal element exists:', $('#newPOReceivingModal').length);
        console.log('Modal element:', $('#newPOReceivingModal')[0]);
        
        if (!poId) {
            console.error('No PO ID found on receive button');
            alert('No PO ID found on receive button');
            return;
        }
        
        // Store the PO ID to be selected when modal opens
        window.selectedPOId = poId;
        console.log('Stored PO ID:', window.selectedPOId);
        
        // Test if modal can be opened
        console.log('Testing modal opening...');
        try {
            $('#newPOReceivingModal').modal('show');
            console.log('Modal show command executed');
        } catch (error) {
            console.error('Error opening modal:', error);
            alert('Error opening modal: ' + error.message);
        }
    });

    // Button event handlers for Goods Receipts table
    $(document).on('click', '.view-grn', function() {
        const grnId = $(this).data('id');
        console.log('View GRN clicked for ID:', grnId);
        
        $.ajax({
            url: `/company/warehouse/receivings/grn-details/${grnId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('View GRN AJAX response:', response);
                if (response.success) {
                    const grn = response.data;
                    console.log('GRN data:', grn);
                    
                    // Store the GRN data globally for print function
                    window.currentGRNData = grn;
                    
                    const modalBody = $('#viewGRNModal .modal-body');
                    
                    modalBody.html(`
                        <div class="container-fluid px-4">
                            <!-- GRN Information Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header bg-light border-bottom">
                                            <h6 class="mb-0 text-dark">
                                                <i class="fas fa-truck-loading me-2 text-muted"></i>Goods Receipt Information
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">GRN Number</small>
                                                        <strong class="text-dark">${grn.grn_number}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">PO Number</small>
                                                        <strong class="text-dark">${grn.po_number}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">Supplier</small>
                                                        <strong class="text-dark">${grn.supplier}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">Received Date</small>
                                                        <strong class="text-dark">${grn.received_date}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">Received By</small>
                                                        <strong class="text-dark">${grn.received_by}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">Status</small>
                                                        <div>${getStatusBadge(grn.status)}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">Total Value</small>
                                                        <strong class="text-dark fs-5">GH₵ ${parseFloat(grn.total_value).toFixed(2)}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted mb-1">Items Count</small>
                                                        <strong class="text-dark">${grn.items ? grn.items.length : 0} items</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Items Section -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header bg-light border-bottom">
                                            <h6 class="mb-0 text-dark">
                                                <i class="fas fa-boxes me-2 text-muted"></i>Items Received (${grn.items ? grn.items.length : 0} items)
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <thead>
                                                        <tr class="border-bottom">
                                                            <th class="px-4 py-3 text-muted fw-normal">Item Name</th>
                                                            <th class="px-4 py-3 text-center text-muted fw-normal">Original Received</th>
                                                            <th class="px-4 py-3 text-center text-muted fw-normal">Returned</th>
                                                            <th class="px-4 py-3 text-center text-muted fw-normal">Effective Qty</th>
                                                            <th class="px-4 py-3 text-center text-muted fw-normal">Rejected Qty</th>
                                                            <th class="px-4 py-3 text-center text-muted fw-normal">Unit</th>
                                                            <th class="px-4 py-3 text-end text-muted fw-normal">Unit Price</th>
                                                            <th class="px-4 py-3 text-end text-muted fw-normal">Total</th>
                                                            <th class="px-4 py-3 text-center text-muted fw-normal">Location</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        ${grn.items ? grn.items.map((item, index) => `
                                                            <tr class="border-bottom">
                                                                <td class="px-4 py-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3 border" style="width: 32px; height: 32px; font-size: 11px; font-weight: 500;">
                                                                            ${index + 1}
                                                                        </div>
                                                                        <div>
                                                                            <span class="text-dark">${item.name}</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-3 text-center">
                                                                    <span class="text-dark fw-medium">${item.received_qty}</span>
                                                                </td>
                                                                <td class="px-4 py-3 text-center">
                                                                    <span class="text-danger fw-medium">${item.returned_qty || 0}</span>
                                                                </td>
                                                                <td class="px-4 py-3 text-center">
                                                                    <span class="text-success fw-medium">${item.effective_qty || item.received_qty}</span>
                                                                </td>
                                                                <td class="px-4 py-3 text-center">
                                                                    <span class="text-dark fw-medium">${item.rejected_qty}</span>
                                                                </td>
                                                                <td class="px-4 py-3 text-center">
                                                                    <small class="text-muted">pcs</small>
                                                                </td>
                                                                <td class="px-4 py-3 text-end">
                                                                    <span class="text-dark">GH₵ ${parseFloat(item.unit_price || 0).toFixed(2)}</span>
                                                                </td>
                                                                <td class="px-4 py-3 text-end">
                                                                    <span class="text-dark fw-medium">GH₵ ${parseFloat(item.total || 0).toFixed(2)}</span>
                                                                </td>
                                                                <td class="px-4 py-3 text-center">
                                                                    <small class="text-muted">${item.location}</small>
                                                                </td>
                                                            </tr>
                                                        `).join('') : '<tr><td colspan="9" class="text-center text-muted py-4">No items found</td></tr>'}
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="border-top">
                                                            <td colspan="7" class="px-4 py-3 text-end">
                                                                <strong class="text-dark">Total Value:</strong>
                                                            </td>
                                                            <td class="px-4 py-3 text-end">
                                                                <strong class="text-dark fs-5">GH₵ ${parseFloat(grn.total_value).toFixed(2)}</strong>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes Section -->
                            ${grn.notes ? `
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header bg-light border-bottom">
                                            <h6 class="mb-0 text-dark">
                                                <i class="fas fa-sticky-note me-2 text-muted"></i>Notes
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-0">${grn.notes}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    `);
                    
                    $('#viewGRNModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to load GRN details'
                    });
                }
            },
            error: function(xhr) {
                console.log('View GRN AJAX error:', xhr);
                console.log('Error response:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load GRN details: ' + (xhr.responseJSON?.message || xhr.statusText)
                });
            }
        });
    });

    $(document).on('click', '.print-grn', function() {
        const grnId = $(this).data('id');
        console.log('Print GRN clicked for ID:', grnId);
        
        $.ajax({
            url: `/company/warehouse/receivings/grn-details/${grnId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    printGRNOnSamePage(response.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to load GRN details for printing'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load GRN details for printing'
                });
            }
        });
    });

    $(document).on('click', '.export-grn', function() {
        const grnId = $(this).data('id');
        console.log('Export GRN clicked for ID:', grnId);
        
        $.ajax({
            url: `/company/warehouse/receivings/grn-details/${grnId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    exportGRNToCSV(response.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to load GRN details for export'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load GRN details for export'
                });
            }
        });
    });

    // Button event handlers for Supplier Returns table
    $(document).on('click', '.view-return', function() {
        const returnId = $(this).data('id');
        console.log('View Return clicked for ID:', returnId);
        
        // Show loading
        Swal.fire({
            title: 'Loading Return Details...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Fetch return details
        $.ajax({
            url: `/company/warehouse/receivings/return-details/${returnId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    showViewReturnModal(response.return);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to load return details'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                console.error('Error loading return details:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load return details. Please try again.'
                });
            }
        });
    });

    $(document).on('click', '.process-return', function() {
        const returnId = $(this).data('id');
        const button = $(this);
        console.log('Process Return clicked for ID:', returnId);
        
        // Confirm processing
        Swal.fire({
            title: 'Process Supplier Return?',
            text: 'This will update the receipt quantities, reduce inventory, and send a notification email to the supplier. This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Process Return',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Disable button to prevent double-click
                button.prop('disabled', true);
                button.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
                
                // Show processing message
                Swal.fire({
                    title: 'Processing Return...',
                    text: 'Please wait while we process the return and send notifications.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Process the return
                $.ajax({
                    url: `/company/warehouse/receivings/process-return/${returnId}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Return Processed Successfully!',
                                html: `
                                    <p>The supplier return has been processed successfully.</p>
                                    <p><strong>Email sent to:</strong> ${response.supplier_email}</p>
                                    <p><strong>Items updated:</strong> ${response.items_updated} items</p>
                                    <p><strong>Inventory reduced:</strong> ${response.inventory_reduced} items</p>
                                `,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Reload the returns table
                                loadSupplierReturns();
                            });
                        } else {
                            // Re-enable button
                            button.prop('disabled', false);
                            button.html('<i class="fas fa-check"></i> Process');
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Processing Failed',
                                text: response.message || 'Failed to process return'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        // Re-enable button
                        button.prop('disabled', false);
                        button.html('<i class="fas fa-check"></i> Process');
                        
                        console.error('Error processing return:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Processing Failed',
                            text: 'Failed to process return. Please try again.'
                        });
                    }
                });
            }
        });
    });

    // Helper functions for GRN operations
    function printGRNOnSamePage(grnData) {
        const printWindow = window.open('', '_blank');
        
        // Show loading message
        Swal.fire({
            title: 'Preparing Print...',
            text: 'Please wait while we load company information',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // First, get company information
        $.ajax({
            url: '/company/warehouse/receivings/company-info',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(companyResponse) {
                Swal.close();
                
                const companyInfo = companyResponse.success ? companyResponse.company : {
                    name: 'Company Name Not Available',
                    address: 'Address Not Available',
                    phone: 'Phone Not Available'
                };
                
                // Continue with the rest of the print function
                continuePrintFunction(printWindow, grnData, companyInfo);
            },
            error: function(xhr) {
                Swal.close();
                console.log('Error loading company info:', xhr);
                
                const companyInfo = {
                    name: 'Company Name Not Available',
                    address: 'Address Not Available',
                    phone: 'Phone Not Available'
                };
                
                // Continue with the rest of the print function
                continuePrintFunction(printWindow, grnData, companyInfo);
            }
        });
    }

    function continuePrintFunction(printWindow, grnData, companyInfo) {
        // Wait for the window to open before writing content
        setTimeout(() => {
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>GRN - ${grnData.grn_number}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                        .company-info { margin-bottom: 20px; }
                        .grn-info { margin-bottom: 20px; }
                        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        .items-table th { background-color: #f2f2f2; }
                        .total-section { text-align: right; margin-top: 20px; }
                        .footer { margin-top: 40px; text-align: center; }
                        @media print { body { margin: 0; } }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>GOODS RECEIPT NOTE (GRN)</h1>
                        <h2>${grnData.grn_number}</h2>
                    </div>
                    
                    <div class="company-info">
                        <h3>Company Information</h3>
                        <p><strong>Company:</strong> ${companyInfo.name}</p>
                        <p><strong>Address:</strong> ${companyInfo.address}</p>
                        <p><strong>Phone:</strong> ${companyInfo.phone}</p>
                    </div>
                    
                    <div class="grn-info">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <p><strong>PO Number:</strong> ${grnData.po_number}</p>
                                <p><strong>Supplier:</strong> ${grnData.supplier}</p>
                                <p><strong>Received Date:</strong> ${grnData.received_date}</p>
                            </div>
                            <div>
                                <p><strong>Received By:</strong> ${grnData.received_by}</p>
                                <p><strong>Status:</strong> ${grnData.status}</p>
                            </div>
                        </div>
                    </div>
                    
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Original Received</th>
                                <th>Returned</th>
                                <th>Effective Qty</th>
                                <th>Rejected Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${grnData.items ? grnData.items.map(item => `
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${item.received_qty}</td>
                                    <td style="color: #dc3545; font-weight: bold;">${item.returned_qty || 0}</td>
                                    <td style="color: #28a745; font-weight: bold;">${item.effective_qty || item.received_qty}</td>
                                    <td>${item.rejected_qty}</td>
                                    <td>GH₵ ${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                                    <td>GH₵ ${parseFloat(item.total || 0).toFixed(2)}</td>
                                    <td>${item.location}</td>
                                </tr>
                            `).join('') : ''}
                        </tbody>
                    </table>
                    
                    <div class="total-section">
                        <h3>Total Value: GH₵ ${parseFloat(grnData.total_value).toFixed(2)}</h3>
                    </div>
                    
                    <div class="footer">
                        <p><strong>Notes:</strong> ${grnData.notes || 'No additional notes'}</p>
                        <p>Generated on: ${new Date().toLocaleDateString()}</p>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            
            // Wait a bit more before printing
            setTimeout(() => {
                printWindow.print();
            }, 500);
        }, 100);
    }

    function exportGRNToCSV(grnData) {
        // Create CSV content
        let csvContent = "data:text/csv;charset=utf-8,";
        
        // Add header
        csvContent += "Goods Receipt Note (GRN)\n";
        csvContent += `${grnData.grn_number}\n\n`;
        
        // Add GRN information
        csvContent += "GRN Information\n";
        csvContent += `PO Number,${grnData.po_number}\n`;
        csvContent += `Supplier,${grnData.supplier}\n`;
        csvContent += `Received Date,${grnData.received_date}\n`;
        csvContent += `Received By,${grnData.received_by}\n`;
        csvContent += `Status,${grnData.status}\n`;
        csvContent += `Total Value,GH₵ ${parseFloat(grnData.total_value).toFixed(2)}\n\n`;
        
        // Add items header
        csvContent += "Items Received\n";
        csvContent += "Item Name,Original Received,Returned,Effective Qty,Rejected Qty,Unit Price,Total,Location\n";
        
        // Add items
        if (grnData.items && grnData.items.length > 0) {
            grnData.items.forEach(item => {
                csvContent += `${item.name},${item.received_qty},${item.returned_qty || 0},${item.effective_qty || item.received_qty},${item.rejected_qty},GH₵ ${parseFloat(item.unit_price || 0).toFixed(2)},GH₵ ${parseFloat(item.total || 0).toFixed(2)},${item.location}\n`;
            });
        }
        
        // Add notes if available
        if (grnData.notes) {
            csvContent += `\nNotes,${grnData.notes}\n`;
        }
        
        // Create download link
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `GRN_${grnData.grn_number}_${new Date().toISOString().split('T')[0]}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Export Successful',
            text: `GRN ${grnData.grn_number} has been exported to CSV`,
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Function to show View Return Modal
    function showViewReturnModal(returnData) {
        console.log('Showing view return modal with data:', returnData);
        
        // Create modal content dynamically
        let modalContent = `
            <div class="modal fade" id="viewReturnModal" tabindex="-1" aria-labelledby="viewReturnModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title" id="viewReturnModalLabel">View Supplier Return</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid px-4">
                                <!-- Return Information -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 text-dark">Return Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p class="mb-1 text-muted small">Return Number</p>
                                                <p class="mb-3 fw-bold">${returnData.return_number || 'N/A'}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1 text-muted small">Supplier</p>
                                                <p class="mb-3 fw-bold">${returnData.supplier || 'N/A'}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1 text-muted small">Return Date</p>
                                                <p class="mb-3 fw-bold">${returnData.return_date || 'N/A'}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-1 text-muted small">Status</p>
                                                <p class="mb-3">${getStatusBadge(returnData.status)}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Reference</p>
                                                <p class="mb-3 fw-bold">${returnData.po_number || 'N/A'}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Return Reason</p>
                                                <p class="mb-3 fw-bold">${returnData.return_reason || 'N/A'}</p>
                                            </div>
                                        </div>
                                        ${returnData.description ? `
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="mb-1 text-muted small">Description</p>
                                                <p class="mb-3">${returnData.description}</p>
                                            </div>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>

                                <!-- Returned Items -->
                                <div class="card">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 text-dark">Returned Items</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="border-bottom">#</th>
                                                        <th class="border-bottom">Item Name</th>
                                                        <th class="border-bottom text-center">Return Quantity</th>
                                                        <th class="border-bottom text-end">Unit Price</th>
                                                        <th class="border-bottom text-end">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${generateReturnItemsRows(returnData.return_items)}
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <td colspan="4" class="text-end fw-bold">Total Value:</td>
                                                        <td class="text-end fw-bold">GH₵ ${calculateReturnTotal(returnData.return_items).toFixed(2)}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
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
        `;
        
        // Remove existing modal if any
        $('#viewReturnModal').remove();
        
        // Add modal to body
        $('body').append(modalContent);
        
        // Show the modal
        $('#viewReturnModal').modal('show');
        
        // Remove modal from DOM when hidden
        $('#viewReturnModal').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    }

    // Helper function to generate return items rows
    function generateReturnItemsRows(returnItems) {
        if (!returnItems || returnItems.length === 0) {
            return '<tr><td colspan="5" class="text-center text-muted">No items found</td></tr>';
        }
        
        let rows = '';
        returnItems.forEach((item, index) => {
            const itemTotal = parseFloat(item.quantity || 0) * parseFloat(item.unit_price || 0);
            rows += `
                <tr>
                    <td class="border-bottom">${index + 1}</td>
                    <td class="border-bottom">${item.item_name || 'Unknown Item'}</td>
                    <td class="border-bottom text-center">${item.quantity || 0}</td>
                    <td class="border-bottom text-end">GH₵ ${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                    <td class="border-bottom text-end">GH₵ ${itemTotal.toFixed(2)}</td>
                </tr>
            `;
        });
        return rows;
    }

    // Helper function to calculate return total
    function calculateReturnTotal(returnItems) {
        if (!returnItems || returnItems.length === 0) {
            return 0;
        }
        
        let total = 0;
        returnItems.forEach(item => {
            total += parseFloat(item.quantity || 0) * parseFloat(item.unit_price || 0);
        });
        return total;
    }

    // Function to clear receiving form
    function clearReceivingForm() {
        console.log('Clearing receiving form...');
        
        // Clear form fields
        $('#rec_poNumber').val('').trigger('change');
        $('#supplier').val('');
        $('#receivingDate').val('');
        $('#deliveryNote').val('');
        $('#vehicleNumber').val('');
        $('#notes').val('');
        
        // Clear items table
        $('#poItemsTable tbody').empty();
        
        // Hide sections
        $('#poItemsCard').hide();
        $('#receivingFormSection').hide();
        $('#submitReceivingBtn').hide();
        
        // Reset Select2
        $('#rec_poNumber').select2('destroy');
        $('#rec_poNumber').select2({
            theme: 'bootstrap4',
            placeholder: 'Search Pending PO...',
            allowClear: true
        });
    }

    // Function to clean up modal backdrop
    function cleanupModalBackdrop() {
        // Remove all modal backdrops
        $('.modal-backdrop').remove();
        
        // Remove modal-open class from body
        $('body').removeClass('modal-open');
        
        // Reset body padding
        $('body').css('padding-right', '');
        
        // Remove any remaining modal-related classes
        $('body').removeClass('modal-open');
        
        // Force reflow to ensure cleanup
        $('body')[0].offsetHeight;
        
        console.log('Modal backdrop cleaned up');
    }

    // Global function to force cleanup all modal artifacts
    function forceCleanupAllModals() {
        // Close all open modals
        $('.modal').modal('hide');
        
        // Remove all backdrops
        $('.modal-backdrop').remove();
        
        // Clean body
        $('body').removeClass('modal-open').css('padding-right', '');
        
        // Force reflow
        $('body')[0].offsetHeight;
        
        console.log('All modals force cleaned up');
    }

    // Function to refresh receiving data
    function refreshReceivingData() {
        console.log('Refreshing receiving data...');
        
        // Refresh all tables
        loadPurchaseOrders(currentPOPage);
        loadGoodsReceipts(currentGoodsReceiptPage);
        loadSupplierReturns(currentReturnsPage);
        
        // Also refresh pending POs for modal
        loadPendingPOsForModal();
        
        console.log('All data refresh functions called');
    }

    // Function to print current GRN (called from modal print button)
    function printCurrentGRN() {
        // Get the current GRN data from the modal
        const modalBody = $('#viewGRNModal .modal-body');
        const grnNumber = modalBody.find('.card-header h6').text().match(/GRN\s+([A-Z0-9-]+)/)?.[1];
        
        if (!grnNumber) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not find GRN number. Please try viewing the GRN again.'
            });
            return;
        }
        
        // Find the GRN ID from the current GRN data
        const currentGRNData = window.currentGRNData;
        if (!currentGRNData) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'GRN data not found. Please try viewing the GRN again.'
            });
            return;
        }
        
        // Call the print function with the current GRN data
        printGRNOnSamePage(currentGRNData);
    }

    // Add event listeners for the export buttons above the table
    $(document).on('click', 'a[onclick="exportAllGRNsToCSV()"]', function(e) {
        e.preventDefault();
        exportAllGRNsToCSV();
    });

    $(document).on('click', 'a[onclick="exportAllGRNsToExcel()"]', function(e) {
        e.preventDefault();
        exportAllGRNsToExcel();
    });

    // Keyboard shortcut to force cleanup modal backdrops (Ctrl+Shift+M)
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'M') {
            e.preventDefault();
            forceCleanupAllModals();
            console.log('Modal cleanup triggered via keyboard shortcut');
        }
    });

    // ===== SUPPLIER RETURN FUNCTIONALITY =====
    
    // Load suppliers for return modal
    function loadSuppliersForReturn() {
        console.log('Loading suppliers for return modal...');
        
        $.ajax({
            url: '/company/warehouse/receivings/all-suppliers',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: JSON.stringify({}),
            success: function(response) {
                console.log('Suppliers response:', response);
                if (response.success && response.suppliers && response.suppliers.length > 0) {
                    $('#returnSupplier').empty();
                    $('#returnSupplier').append('<option value="">Select Supplier</option>');
                    
                    response.suppliers.forEach(function(supplier) {
                        $('#returnSupplier').append(`
                            <option value="${supplier.id}">${supplier.company_name}</option>
                        `);
                    });
                    
                    $('#returnSupplier').select2({
                        theme: 'bootstrap4',
                        placeholder: 'Select Supplier',
                        allowClear: true
                    });
                    
                    console.log('Loaded ' + response.suppliers.length + ' suppliers');
                } else {
                    console.log('No suppliers found or invalid response');
                    $('#returnSupplier').empty();
                    $('#returnSupplier').append('<option value="">No suppliers available</option>');
                }
            },
            error: function(xhr) {
                console.error('Error loading suppliers:', xhr);
                console.error('Response text:', xhr.responseText);
                console.error('Status:', xhr.status);
                
                $('#returnSupplier').empty();
                $('#returnSupplier').append('<option value="">Error loading suppliers</option>');
                
                // Show error to user
                Swal.fire({
                    icon: 'error',
                    title: 'Error Loading Suppliers',
                    text: 'Failed to load suppliers. Please try again.',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    }

    // Load references (POs/Receivings) for selected supplier
    function loadReferencesForSupplier(supplierId) {
        console.log('Loading references for supplier:', supplierId);
        
        if (!supplierId) {
            $('#returnReference').empty();
            $('#returnReference').append('<option value="">Select Reference</option>');
            return;
        }
        
        $.ajax({
            url: '/company/warehouse/receivings/supplier-references',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: JSON.stringify({
                supplier_id: supplierId
            }),
            success: function(response) {
                console.log('References response:', response);
                if (response.success && response.references && response.references.length > 0) {
                    $('#returnReference').empty();
                    $('#returnReference').append('<option value="">Select Reference</option>');
                    
                    response.references.forEach(function(ref) {
                        $('#returnReference').append(`
                            <option value="${ref.id}" data-type="${ref.type}">${ref.reference_number} - ${ref.date}</option>
                        `);
                    });
                    
                    $('#returnReference').select2({
                        theme: 'bootstrap4',
                        placeholder: 'Select Reference',
                        allowClear: true
                    });
                } else {
                    $('#returnReference').empty();
                    $('#returnReference').append('<option value="">No references found</option>');
                }
            },
            error: function(xhr) {
                console.error('Error loading references:', xhr);
                $('#returnReference').empty();
                $('#returnReference').append('<option value="">Error loading references</option>');
            }
        });
    }

    // Load items for selected reference
    function loadItemsForReference(referenceId, referenceType) {
        console.log('Loading items for reference:', referenceId, 'Type:', referenceType);
        
        if (!referenceId) {
            $('#returnItemsTable tbody').empty();
            return;
        }
        
        // Since we only show receiving references now, always use receiving-items endpoint
        const endpoint = `/company/warehouse/receivings/receiving-items/${referenceId}`;
        
        $.ajax({
            url: endpoint,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: JSON.stringify({}),
            success: function(response) {
                console.log('Items response:', response);
                if (response.success && response.items && response.items.length > 0) {
                    // Store items globally for return form
                    window.availableReturnItems = response.items;
                    
                    // Debug: Log the first item to see its structure
                    console.log('First item structure:', response.items[0]);
                    
                    // Show add item button
                    $('#addReturnItem').show();
                } else {
                    window.availableReturnItems = [];
                    $('#addReturnItem').hide();
                    console.log('No items found for this reference');
                }
            },
            error: function(xhr) {
                console.error('Error loading items:', xhr);
                window.availableReturnItems = [];
                $('#addReturnItem').hide();
            }
        });
    }

    // Add return item row
    function addReturnItemRow() {
        console.log('Adding return item row...');
        console.log('Available items:', window.availableReturnItems);
        
        if (!window.availableReturnItems || window.availableReturnItems.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Items Available',
                text: 'No items found for the selected reference'
            });
            return;
        }
        
        const itemId = `return_item_${Date.now()}`;
        const row = `
            <tr id="${itemId}">
                <td>
                    <select class="form-select form-select-sm return-item-select" required>
                        <option value="">Select Item</option>
                        ${window.availableReturnItems.map(item => {
                            // Handle different possible field names for item name
                            const itemName = item.name || item.item_name || item.description || 'Unknown Item';
                            const receivedQty = item.received_qty || item.quantity || 0;
                            const unitPrice = item.unit_price || 0;
                            
                            console.log('Processing item for dropdown:', {
                                item: item,
                                itemName: itemName,
                                receivedQty: receivedQty,
                                unitPrice: unitPrice
                            });
                            
                            return `
                                <option value="${item.id}" 
                                        data-received="${receivedQty}" 
                                        data-price="${unitPrice}">
                                    ${itemName} (Received: ${receivedQty})
                                </option>
                            `;
                        }).join('')}
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm received-qty" readonly>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm return-qty" min="0" required>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm unit-price" readonly>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-total" readonly>
                </td>
                <td>
                    <select class="form-select form-select-sm item-reason" required>
                        <option value="">Reason</option>
                        <option value="damaged">Damaged</option>
                        <option value="wrong_item">Wrong Item</option>
                        <option value="excess">Excess</option>
                        <option value="quality">Quality Issue</option>
                        <option value="other">Other</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-item" onclick="removeReturnItem('${itemId}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        
        $('#returnItemsTable tbody').append(row);
        
        // Initialize Select2 for the new row
        $(`#${itemId} .return-item-select`).select2({
            theme: 'bootstrap4',
            placeholder: 'Select Item'
        });
    }

    // Remove return item row
    function removeReturnItem(itemId) {
        $(`#${itemId}`).remove();
    }

    // Handle return item selection
    $(document).on('change', '.return-item-select', function() {
        const row = $(this).closest('tr');
        const selectedOption = $(this).find('option:selected');
        const receivedQty = selectedOption.data('received') || 0;
        const unitPrice = selectedOption.data('price') || 0;
        
        row.find('.received-qty').val(receivedQty);
        row.find('.unit-price').val(unitPrice);
        row.find('.return-qty').attr('max', receivedQty);
    });

    // Handle return quantity change
    $(document).on('input', '.return-qty', function() {
        const row = $(this).closest('tr');
        const returnQty = parseFloat($(this).val()) || 0;
        const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
        const total = returnQty * unitPrice;
        
        row.find('.item-total').val(total.toFixed(2));
    });

    // Handle supplier selection in return modal
    $(document).on('change', '#returnSupplier', function() {
        const supplierId = $(this).val();
        loadReferencesForSupplier(supplierId);
    });

    // Handle reference selection in return modal
    $(document).on('change', '#returnReference', function() {
        const referenceId = $(this).val();
        const selectedOption = $(this).find('option:selected');
        const referenceType = selectedOption.data('type');
        
        loadItemsForReference(referenceId, referenceType);
        $('#returnItemsTable tbody').empty();
    });

    // Handle add return item button
    $(document).on('click', '#addReturnItem', function() {
        addReturnItemRow();
    });

    // Handle return form submission
    $(document).on('submit', '#returnForm', function(e) {
        e.preventDefault();
        console.log('Return form submitted');
        
        // Validate form
        if (!validateReturnForm()) {
            return false;
        }
        
        // Submit form via AJAX
        submitReturnForm();
    });

    // Validate return form
    function validateReturnForm() {
        const supplier = $('#returnSupplier').val();
        const returnDate = $('#returnDate').val();
        const reference = $('#returnReference').val();
        const reason = $('#returnReason').val();
        const description = $('#returnDescription').val();
        
        if (!supplier) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select a supplier'
            });
            return false;
        }
        
        if (!returnDate) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select a return date'
            });
            return false;
        }
        
        if (!reference) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select a reference'
            });
            return false;
        }
        
        if (!reason) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select a reason for return'
            });
            return false;
        }
        
        if (!description.trim()) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please provide a description'
            });
            return false;
        }
        
        // Check if any items are added
        const itemRows = $('#returnItemsTable tbody tr');
        if (itemRows.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please add at least one item to return'
            });
            return false;
        }
        
        // Validate each item
        let hasValidItems = false;
        itemRows.each(function() {
            const itemSelect = $(this).find('.return-item-select');
            const returnQty = $(this).find('.return-qty');
            const itemReason = $(this).find('.item-reason');
            
            if (itemSelect.val() && returnQty.val() > 0 && itemReason.val()) {
                hasValidItems = true;
                return false; // break the loop
            }
        });
        
        if (!hasValidItems) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please ensure all items have valid quantities and reasons'
            });
            return false;
        }
        
        return true;
    }

    // Submit return form
    function submitReturnForm() {
        const supplierId = $('#returnSupplier').val();
        const returnDate = $('#returnDate').val();
        const referenceId = $('#returnReference').val();
        const reason = $('#returnReason').val();
        const description = $('#returnDescription').val();
        
        // Collect items data
        const items = [];
        $('#returnItemsTable tbody tr').each(function() {
            const itemSelect = $(this).find('.return-item-select');
            const returnQty = $(this).find('.return-qty');
            const itemReason = $(this).find('.item-reason');
            const unitPrice = $(this).find('.unit-price').val() || 0;
            
            if (itemSelect.val() && returnQty.val() > 0) {
                // Get the selected item data from availableReturnItems
                const selectedItem = window.availableReturnItems.find(item => item.id === itemSelect.val());
                
                items.push({
                    item_id: itemSelect.val(),
                    item_name: selectedItem ? selectedItem.name : 'Unknown Item',
                    quantity: parseFloat(returnQty.val()),
                    unit_price: parseFloat(unitPrice),
                    reason: itemReason.val()
                });
            }
        });
        
        // Show loading
        Swal.fire({
            title: 'Processing Return...',
            text: 'Please wait while we process your return',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit via AJAX
        $.ajax({
            url: '/company/warehouse/receivings/submit-return',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: JSON.stringify({
                supplier_id: supplierId,
                return_date: returnDate,
                reference_id: referenceId,
                reference_type: 'GRN', // Since we're only showing receiving references
                reason: reason,
                description: description,
                items: items
            }),
            success: function(response) {
                Swal.close();
                console.log('Return submission response:', response);
                
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Return Submitted!',
                        text: response.message || 'Supplier return has been successfully submitted',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        console.log('Success dialog closed, closing modal...');
                        // Close modal and refresh data
                        $('#newReturnModal').modal('hide');
                        setTimeout(() => {
                            cleanupModalBackdrop();
                        }, 150);
                        clearReturnForm();
                        refreshReceivingData();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Return Failed',
                        text: response.message || 'Failed to submit return'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                console.error('Error submitting return:', xhr);
                
                let errorMessage = 'Failed to submit return';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Return Failed',
                    text: errorMessage
                });
            }
        });
    }

    // Clear return form
    function clearReturnForm() {
        console.log('Clearing return form...');
        
        // Clear form fields
        $('#returnSupplier').val('').trigger('change');
        $('#returnReference').val('').trigger('change');
        $('#returnDate').val('');
        $('#returnReason').val('');
        $('#returnDescription').val('');
        
        // Clear items table
        $('#returnItemsTable tbody').empty();
        
        // Reset Select2
        $('#returnSupplier').select2('destroy');
        $('#returnReference').select2('destroy');
        $('#returnSupplier').select2({
            theme: 'bootstrap4',
            placeholder: 'Select Supplier',
            allowClear: true
        });
        $('#returnReference').select2({
            theme: 'bootstrap4',
            placeholder: 'Select Reference',
            allowClear: true
        });
        
        // Clear global items
        window.availableReturnItems = [];
    }

    // Initialize return modal
    $('#newReturnModal').on('show.bs.modal', function() {
        loadSuppliersForReturn();
        
        // Set current date as default return date
        const today = new Date().toISOString().split('T')[0];
        $('#returnDate').val(today);
    });

    // Test button to manually load returns (for debugging)
    $(document).on('click', '#testLoadReturns', function() {
        console.log('Manual test: Loading supplier returns...');
        loadSupplierReturns();
    });

    // Clean up return modal backdrop
    $('#newReturnModal').on('hidden.bs.modal', function() {
        setTimeout(() => {
            cleanupModalBackdrop();
        }, 150);
    });
});
</script>