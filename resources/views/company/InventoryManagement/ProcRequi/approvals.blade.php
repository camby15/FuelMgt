<!-- PO Approval Dashboard -->



<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Purchase Order Approval Dashboard</h4>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" id="bulkApproveBtn">
                    <i class="fas fa-check-double me-2"></i>Bulk Approve
                </button>
                <button class="btn btn-outline-secondary" id="exportApprovalsBtn">
                    <i class="fas fa-download me-2"></i>Export
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Approval Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Pending Approval</h6>
                        <h3 class="mb-0">0</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Approved Today</h6>
                        <h3 class="mb-0">0</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Rejected Today</h6>
                        <h3 class="mb-0">0</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Avg. Response Time</h6>
                        <h3 class="mb-0" id="avgResponseTime">--</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-stopwatch fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-md-3">
        <select class="form-select" id="approvalTypeFilter">
            <option value="">All Types</option>
            <option value="purchase_order">Purchase Orders</option>
            <option value="requisition">Purchase Requisitions</option>
            <option value="expense">Expense Claims</option>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-select" id="priorityFilter">
            <option value="">All Priorities</option>
            <option value="high">High Priority</option>
            <option value="normal">Normal Priority</option>
            <option value="low">Low Priority</option>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-select" id="supplierFilter">
            <option value="">All Suppliers</option>
            <!-- Supplier options will be loaded dynamically -->
        </select>
    </div>
    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Search approvals..." id="approvalSearch">
        </div>
    </div>
</div>

<!-- Approval Queue -->
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">PO Approval Queue</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="approvalTable">
                <thead class="table-light">
                    <tr>
                        <th width="50">
                            <input type="checkbox" class="form-check-input" id="selectAllApprovals">
                        </th>
                        <th>Type</th>
                        <th>Reference</th>
                        <th>Requested By</th>
                        <th>Supplier</th>
                        <th>Amount</th>
                        <th>Priority</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th width="250">Actions</th>
                    </tr>
                </thead>
                <tbody id="approvalTableBody">
                    <!-- Approval items will be loaded here -->
                </tbody>
            </table>
        </div>
        
                 <!-- Pagination -->
         <div class="card-footer bg-light">
             <div class="d-flex justify-content-between align-items-center">
                 <div class="text-muted">
                     <small id="poApprovalPaginationInfo">Showing 0 to 0 of 0 entries</small>
                 </div>
                 <nav aria-label="PO Approval pagination">
                     <ul class="pagination pagination-sm mb-0 po-approval-pagination" id="poApprovalPagination">
                         <!-- Pagination links will be generated here -->
                     </ul>
                 </nav>
             </div>
         </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalTitle">Approve Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="approvalForm">
                    <input type="hidden" id="approvalId" name="approval_id">
                    <input type="hidden" id="approvalAction" name="action">
                    
                    <div class="mb-3">
                        <label for="approvalComments" class="form-label">Comments (Optional)</label>
                        <textarea class="form-control" id="approvalComments" name="comments" rows="3" 
                                placeholder="Add any comments or notes about this approval..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="approvalPriority" class="form-label">Set Priority</label>
                        <select class="form-select" id="approvalPriority" name="priority">
                            <option value="normal">Normal Priority</option>
                            <option value="high">High Priority</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="approvalAssignTo" class="form-label">Assign To (Optional)</label>
                        <select class="form-select" id="approvalAssignTo" name="assign_to">
                            <option value="">No Assignment</option>
                            <option value="procurement">Procurement Team</option>
                            <option value="finance">Finance Team</option>
                            <option value="operations">Operations Team</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmApprovalBtn">
                    <i class="fas fa-check me-2"></i>Confirm Approval
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Approval Details Modal -->
<div class="modal fade" id="approvalDetailsModal" tabindex="-1" aria-labelledby="approvalDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalDetailsModalLabel">PO Approval Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <h6>Request Information</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Reference:</strong></td>
                                    <td id="detailReference">PO-2023-001</td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td id="detailType">Purchase Order</td>
                                </tr>
                                <tr>
                                    <td><strong>Requested By:</strong></td>
                                    <td id="detailRequester">John Doe</td>
                                </tr>
                                <tr>
                                    <td><strong>Supplier:</strong></td>
                                    <td id="detailDepartment">IT Department</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td id="detailAmount">$12,500.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Priority:</strong></td>
                                    <td id="detailPriority">High</td>
                                </tr>
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td id="detailDate">Nov 15, 2023</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Approval History</h6>
                        <div class="timeline">
                            <!-- Dynamic timeline content will be loaded here -->
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <h6>Request <Title></Title></h6>
                        <p id="detailDescription"></p>
                    </div>
                </div>

                <!-- Attachments Section (for requisitions) -->
                <div class="row mt-4" id="attachmentsSection" style="display: none;">
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-paperclip me-2"></i>Attachments
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="attachmentsList">
                                    <p class="text-muted mb-0">No attachments found</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Order Items Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-secondary">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-list me-2"></i>Purchase Order Items
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0" id="poItemsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Item</th>
                                                <th>Description</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-end">Unit Price</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="poItemsTableBody">
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3">
                                                    <i class="fas fa-spinner fa-spin me-2"></i>Loading items...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                                 <!-- Invoice Status Section -->
                 <div class="row mt-4" id="invoiceStatusSection">
                     <div class="col-12">
                         <div class="card border-primary">
                             <div class="card-header bg-primary text-white">
                                 <h6 class="mb-0">
                                     <i class="fas fa-file-invoice me-2"></i>Invoice Status
                                 </h6>
                             </div>
                             <div class="card-body">
                                 <div class="uploaded-files">
                                     <h6 class="mb-3">Invoice Files</h6>
                                     <div id="uploadedFilesList">
                                         <div class="alert alert-info">
                                             <i class="fas fa-info-circle me-2"></i>
                                             Checking invoice status...
                                         </div>
                                     </div>
                                 </div>
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

<!-- Invoice Upload Modal -->
<div class="modal fade" id="invoiceUploadModal" tabindex="-1" aria-labelledby="invoiceUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceUploadModalLabel">
                    <i class="fas fa-file-invoice me-2"></i>Upload Invoice
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="upload-area-large" id="modalInvoiceUploadArea">
                            <div class="upload-content text-center">
                                <i class="fas fa-cloud-upload-alt fa-4x text-muted mb-4"></i>
                                <h5>Upload Invoice Files</h5>
                                <p class="text-muted mb-4">Drag and drop your invoice files here or click to browse</p>
                                <input type="file" id="modalInvoiceFileInput" class="d-none" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" multiple>
                                <button type="button" class="btn btn-primary btn-lg" onclick="document.getElementById('modalInvoiceFileInput').click()">
                                    <i class="fas fa-upload me-2"></i>Choose Files
                                </button>
                                <p class="text-muted mt-3">Supported formats: PDF, JPG, PNG, DOC, DOCX (Max 10MB each)</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="uploaded-files">
                            <h6 class="mb-3">Uploaded Files</h6>
                            <div id="modalUploadedFilesList">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No files uploaded yet.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="modalInvoiceNotes" class="form-label">Invoice Notes (Optional)</label>
                            <textarea class="form-control" id="modalInvoiceNotes" rows="3" 
                                    placeholder="Add any notes about the invoice upload..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveInvoiceUploadBtn">
                    <i class="fas fa-save me-2"></i>Save Upload
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Fix for tab content positioning - target the correct elements */

    
    /* Timeline Styles for Approval History */
    .timeline {
        position: relative;
        padding-left: 20px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
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
        left: -20px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }

    .timeline-content {
        padding-left: 10px;
    }

    .timeline-title {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 2px;
    }

    .timeline-subtitle {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .timeline-text {
        font-size: 0.85rem;
        color: #495057;
    }

    /* Invoice Upload Styles */
    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-area:hover {
        border-color: #007bff;
        background-color: #e3f2fd;
    }

    .upload-area.dragover {
        border-color: #007bff;
        background-color: #e3f2fd;
        transform: scale(1.02);
    }

    .upload-area-large {
        border: 3px dashed #dee2e6;
        border-radius: 12px;
        padding: 3rem;
        text-align: center;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-area-large:hover {
        border-color: #007bff;
        background-color: #e3f2fd;
    }

    .upload-area-large.dragover {
        border-color: #007bff;
        background-color: #e3f2fd;
        transform: scale(1.02);
    }

    .uploaded-file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        margin-bottom: 0.5rem;
        background-color: #fff;
        transition: all 0.2s ease;
    }

    .uploaded-file-item:hover {
        border-color: #007bff;
        box-shadow: 0 2px 4px rgba(0,123,255,0.1);
    }

    .uploaded-file-item.existing-file {
        background-color: #e8f5e8;
        border-color: #28a745;
        border-left: 4px solid #28a745;
    }

    .file-status {
        margin-left: 10px;
    }

    .file-info {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .file-icon {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }

    .file-icon.pdf { background-color: #dc3545; color: white; }
    .file-icon.image { background-color: #28a745; color: white; }
    .file-icon.document { background-color: #007bff; color: white; }

    .file-details h6 {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .file-details small {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .file-actions {
        display: flex;
        gap: 0.5rem;
    }

    .upload-progress {
        width: 100%;
        height: 4px;
        background-color: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .upload-progress-bar {
        height: 100%;
        background-color: #007bff;
        transition: width 0.3s ease;
    }
    
         /* Empty state styling */
     .text-muted .fa-3x {
         opacity: 0.6;
     }
     
     .text-muted h5 {
         font-weight: 500;
         margin-bottom: 0.5rem;
     }
     
     .text-muted p {
         font-size: 0.9rem;
         opacity: 0.8;
     }
     
           /* PO Approval Pagination styling */
      .po-approval-pagination .page-link {
          padding: 0.25rem 0.5rem;
          font-size: 0.875rem;
          line-height: 1.5;
      }
      
      .po-approval-pagination .page-item.active .page-link {
          background-color: #007bff;
          border-color: #007bff;
      }
      
      .po-approval-pagination .page-item.disabled .page-link {
          color: #6c757d;
          pointer-events: none;
          background-color: #fff;
          border-color: #dee2e6;
      }
</style>
<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<!-- Add these in your <head> section -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Initialize approval functionality
        initializeApprovalTab();
        
        // Also try to initialize when the page is fully loaded
        $(window).on('load', function() {
            initializeApprovalTab();
        });
        
        // Add a more robust initialization with multiple attempts
        let initAttempts = 0;
        const maxAttempts = 5;
        
        function attemptInitialization() {
            if (initAttempts >= maxAttempts) return;
            
            if ($('#approvalTableBody').length > 0) {
                console.log('Attempt initialization: loading approval data...');
                loadApprovalData(1, '', true); // Force refresh
                initializeApprovalHandlers();
            } else {
                initAttempts++;
                setTimeout(attemptInitialization, 500);
            }
        }
        
        // Start the initialization attempts
        setTimeout(attemptInitialization, 500);
    });

    function initializeApprovalTab() {
        // Load approval data when approvals tab is shown
        $('button[data-bs-target="#approvals"]').off('shown.bs.tab').on('shown.bs.tab', function() {
            console.log('Approvals tab shown event triggered');
            setTimeout(function() {
                console.log('Refreshing approval data on tab show...');
                loadApprovalData(1, '', true); // Force refresh
                initializeApprovalHandlers();
            }, 100);
        });

        // Also add a click handler to the tab button
        $('#approvals-tab').off('click').on('click', function() {
            console.log('Approvals tab click event triggered');
            setTimeout(function() {
                console.log('Refreshing approval data on tab click...');
                loadApprovalData(1, '', true); // Force refresh
                initializeApprovalHandlers();
            }, 300);
        });

        // Load approval data immediately if approvals tab is active
        if (($('#approvals').hasClass('active') || $('#approvals').hasClass('show'))) {
            console.log('Approvals tab is active on initialization, loading data...');
            setTimeout(function() {
                loadApprovalData(1, '', true); // Force refresh
                initializeApprovalHandlers();
            }, 100);
        }
        
        // Also check if the tab button is active
        if ($('#approvals-tab').hasClass('active')) {
            console.log('Approvals tab button is active on initialization, loading data...');
            setTimeout(function() {
                loadApprovalData(1, '', true); // Force refresh
                initializeApprovalHandlers();
            }, 100);
        }
        
        // Add a manual trigger for when the tab content is loaded
        if ($('#approvalTableBody').length > 0) {
            console.log('Approval table body found, loading data...');
            setTimeout(function() {
                loadApprovalData(1, '', true); // Force refresh
                initializeApprovalHandlers();
            }, 200);
        }
        
        // Add a periodic check to ensure data is loaded
        setTimeout(function() {
            if ($('#approvalTableBody').length > 0) {
                console.log('Periodic check: loading approval data...');
                loadApprovalData(1, '', true); // Force refresh
                initializeApprovalHandlers();
            }
        }, 1000);
    }

    // Load approval data
    function loadApprovalData(page = 1, searchQuery = '', forceRefresh = false) {
        console.log('=== LOAD APPROVAL DATA CALLED ===');
        console.log('Parameters:', { page, searchQuery, forceRefresh });
        console.log('isLoadingApprovalData:', window.isLoadingApprovalData);
        
        // Prevent multiple simultaneous calls (unless forcing refresh)
        if (window.isLoadingApprovalData && !forceRefresh) {
            console.log('Already loading approval data, skipping...');
            return;
        }
        window.isLoadingApprovalData = true;
        console.log('Starting to load approval data...');
        
        // Get filter values
        const searchTerm = searchQuery || $('#approvalSearch').val();
        const dateFrom = $('#dateFromFilter').val();
        const dateTo = $('#dateToFilter').val();
        const amountMin = $('#amountMinFilter').val();
        const amountMax = $('#amountMaxFilter').val();
        const approvalType = $('#approvalTypeFilter').val();

        // Show loading state
        $('#approvalTableBody').html(`
            <tr>
                <td colspan="10" class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
                        <h5>Loading Approvals...</h5>
                        <p class="mb-0">Please wait while we fetch the latest data.</p>
                    </div>
                </td>
            </tr>
        `);

        $.ajax({
            url: '{{ route("po_approval.pending") }}',
            method: 'POST',
            data: {
                search: searchTerm,
                date_from: dateFrom,
                date_to: dateTo,
                amount_min: amountMin,
                amount_max: amountMax,
                approval_type: approvalType,
                page: page,
                _token: '{{ csrf_token() }}'
            },
                         success: function(response) {
                 if (response.success) {

                     renderApprovalTable(response.data);
                      console.log("vv", response.meta);
                      renderPOApprovalovalPagination(response.meta);
                     updateStats(response.stats);
                     // Load suppliers for filter dropdown
                     loadSupplierOptions(response.data);
                 } else {
                    $('#approvalTableBody').html(`
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-danger">
                                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                    <h5>Failed to Load Data</h5>
                                    <p class="mb-0">${response.message || 'Unknown error occurred'}</p>
                                </div>
                            </td>
                        </tr>
                    `);
                }
                window.isLoadingApprovalData = false;
            },
            error: function(xhr, status, error) {
                $('#approvalTableBody').html(`
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <div class="text-danger">
                                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                <h5>Error Loading Data</h5>
                                <p class="mb-0">${error || 'Network error occurred'}</p>
                            </div>
                        </td>
                    </tr>
                `);
                window.isLoadingApprovalData = false;
            }
        });
    }

    // Helper function to calculate total from items
    function calculateItemsTotal(items) {
        if (!items) return 0;
        
        // Handle items as JSON field (string) or array
        let itemsArray = items;
        if (typeof items === 'string') {
            try {
                itemsArray = JSON.parse(items);
            } catch (e) {
                console.error('Failed to parse items JSON:', e);
                return 0;
            }
        }
        
        if (!Array.isArray(itemsArray)) return 0;
        
        let total = 0;
        itemsArray.forEach(item => {
            const quantity = parseFloat(item.quantity || 0);
            const price = parseFloat(item.price || item.unit_price || 0);
            total += quantity * price;
        });
        
        return total;
    }

    // Render approval table
    function renderApprovalTable(data) {
        const tbody = $('#approvalTableBody');
        tbody.empty();

        if (!data || data.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <h5>No Pending Approvals</h5>
                            <p class="mb-0">There are no purchase orders waiting for approval.</p>
                        </div>
                    </td>
                </tr>
            `);
            // Don't return here - let the function continue to render pagination
        }

        data.forEach((item, index) => {
            // Determine if this is a PO or Requisition
            const isRequisition = item.type === 'requisition' || item.requisition_number;
            const isPO = item.type === 'purchase_order' || item.po_number;
            
            // Set up dynamic data based on type
            const badge = isRequisition ? '<span class="badge bg-success">REQ</span>' : '<span class="badge bg-primary">PO</span>';
            const number = isRequisition ? (item.requisition_number || `REQ-${item.id}`) : (item.po_number || `PO-${item.id}`);
            const description = isRequisition ? 
                (item.title || 'No title') : 
                (item.title || 'No title');
            const requester = item.requested_by || item.requestor_name || (item.requestor ? 
                (item.requestor.personalInfo ? 
                    ((item.requestor.personalInfo.first_name || '') + ' ' + (item.requestor.personalInfo.last_name || '')).trim() || ('Employee #' + item.requestor.staff_id) :
                    ('Employee #' + item.requestor.staff_id)
                ) : 
                (item.created_by ? item.created_by.fullname : 'Unknown')
            );
            const supplier = isRequisition ? (item.department ? (typeof item.department === 'object' ? item.department.name : item.department) : 'Internal') : (item.supplier ? item.supplier.company_name : 'Unknown');
            // Calculate value based on type
            let value = 0;
            if (isRequisition) {
                // For requisitions, calculate from items if total_amount is 0
                value = item.total_amount || 0;
                if (value === 0 && item.items) {
                    value = calculateItemsTotal(item.items);
                }
            } else {
                // For POs, use total_value
                value = item.total_value || 0;
            }
            const priority = item.priority ? item.priority.toUpperCase() : 'NORMAL';
            const priorityBadge = priority === 'HIGH' ? 'bg-danger' : (priority === 'MEDIUM' ? 'bg-warning text-dark' : 'bg-secondary');
            const status = item.status || 'Pending';
            const statusBadge = status.toLowerCase() === 'pending' ? 'bg-warning text-dark' : 'bg-info';
            
            const row = `
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input approval-checkbox" value="${item.id}" data-type="${isRequisition ? 'requisition' : 'purchase_order'}">
                    </td>
                    <td>
                        ${badge}
                    </td>
                    <td>
                        <strong>${number}</strong>
                        <br><small class="text-muted">${description}</small>
                    </td>
                    <td>${requester}</td>
                    <td>${supplier}</td>
                    <td class="fw-bold">${formatCurrency(value)}</td>
                    <td>
                        <span class="badge ${priorityBadge}">${priority}</span>
                    </td>
                    <td>${formatDate(item.created_at)}</td>
                    <td>
                        <span class="badge ${statusBadge}">${status}</span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary btn-view-approval" data-id="${item.id}" data-type="${isRequisition ? 'requisition' : 'purchase_order'}">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${isPO ? `<button type="button" class="btn btn-outline-info btn-upload-invoice" data-id="${item.id}" data-type="purchase_order" title="Upload Invoice">
                                <i class="fas fa-file-invoice"></i>
                            </button>` : ''}
                            <button type="button" class="btn btn-outline-success btn-approve" data-id="${item.id}" data-type="${isRequisition ? 'requisition' : 'purchase_order'}">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-reject" data-id="${item.id}" data-type="${isRequisition ? 'requisition' : 'purchase_order'}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        ${isPO ? `<div class="mt-1">
                            <span class="badge bg-secondary invoice-status" data-po-id="${item.id}">
                                <i class="fas fa-spinner fa-spin"></i> Checking...
                            </span>
                        </div>` : ''}
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        // Batch check invoice status for POs only (not requisitions)
        const poIds = data.filter(item => {
            const isPO = item.type === 'purchase_order' || item.po_number;
            return isPO;
        }).map(item => item.id);
        
        if (poIds.length > 0) {
            batchCheckInvoiceStatus(poIds);
        }
    }

              // Render pagination
     function renderPOApprovalovalPagination(meta) {
         console.log('renderPagination called with meta:', meta);
         const paginationContainer = $('#poApprovalPagination');
         const paginationInfo = $('#poApprovalPaginationInfo');
         
         console.log('Pagination container found:', paginationContainer.length);
         console.log('Pagination info found:', paginationInfo.length);
         
         // Convert to numbers to ensure they're valid, with fallbacks
         const currentPage = parseInt(meta.current_page) || 1;
         const perPage = parseInt(meta.per_page) || 10;
         const total = parseInt(meta.total) || 0;
         const lastPage = parseInt(meta.last_page) || 1;
         
         // Update pagination info
         const start = total > 0 ? ((currentPage - 1) * perPage) + 1 : 0;
         const end = Math.min(currentPage * perPage, total);
         const paginationText = `Showing ${start} to ${end} of ${total} entries`;
         console.log('Setting pagination text:', paginationText);
         paginationInfo.text(paginationText);
         
         // Clear existing pagination
         paginationContainer.empty();
         
         // If only one page or no data, don't show pagination
         if (lastPage <= 1) {
             return;
         }
         
         // Previous button
         const prevDisabled = currentPage === 1 ? 'disabled' : '';
         paginationContainer.append(`
             <li class="page-item ${prevDisabled}">
                 <a class="page-link" href="#" data-page="${currentPage - 1}" ${prevDisabled}>
                     <i class="fas fa-chevron-left"></i>
                 </a>
             </li>
         `);
         
         // Page numbers
         const startPage = Math.max(1, currentPage - 2);
         const endPage = Math.min(lastPage, currentPage + 2);
         
         // First page if not in range
         if (startPage > 1) {
             paginationContainer.append(`
                 <li class="page-item">
                     <a class="page-link" href="#" data-page="1">1</a>
                 </li>
             `);
             if (startPage > 2) {
                 paginationContainer.append(`
                     <li class="page-item disabled">
                         <span class="page-link">...</span>
                     </li>
                 `);
             }
         }
         
         // Page numbers in range
         for (let i = startPage; i <= endPage; i++) {
             const active = i === currentPage ? 'active' : '';
             paginationContainer.append(`
                 <li class="page-item ${active}">
                     <a class="page-link" href="#" data-page="${i}">${i}</a>
                 </li>
             `);
         }
         
         // Last page if not in range
         if (endPage < lastPage) {
             if (endPage < lastPage - 1) {
                 paginationContainer.append(`
                     <li class="page-item disabled">
                         <span class="page-link">...</span>
                     </li>
                 `);
             }
             paginationContainer.append(`
                 <li class="page-item">
                     <a class="page-link" href="#" data-page="${lastPage}">${lastPage}</a>
                 </li>
             `);
         }
         
         // Next button
         const nextDisabled = currentPage === lastPage ? 'disabled' : '';
         paginationContainer.append(`
             <li class="page-item ${nextDisabled}">
                 <a class="page-link" href="#" data-page="${currentPage + 1}" ${nextDisabled}>
                     <i class="fas fa-chevron-right"></i>
                 </a>
             </li>
         `);
     }

    // Initialize approval event handlers
    function initializeApprovalHandlers() {
        // Remove existing handlers to prevent duplicates
        $('#selectAllApprovals').off('change');
        $('#bulkApproveBtn').off('click');
        $('#confirmApprovalBtn').off('click');
        $('#approvalSearch').off('keyup');
        $('#approvalTypeFilter, #priorityFilter, #supplierFilter').off('change');
        
        // Clear file state when approval details modal is closed
        $('#approvalDetailsModal').off('hidden.bs.modal').on('hidden.bs.modal', function() {
            const currentPoId = $(this).data('currentPoId');
            if (currentPoId && window.uploadedInvoiceFiles && window.uploadedInvoiceFiles[currentPoId]) {
                delete window.uploadedInvoiceFiles[currentPoId];
            }
            // Clear the file input
            $('#invoiceFileInput').val('');
        });
        
        // Select all checkboxes
        $('#selectAllApprovals').on('change', function() {
            $('.approval-checkbox').prop('checked', this.checked);
        });

        // Bulk approve
        $('#bulkApproveBtn').on('click', function() {
            const selectedItems = $('.approval-checkbox:checked').map(function() {
                return {
                    id: $(this).val(),
                    type: $(this).data('type') || 'purchase_order'
                };
            }).get();

            if (selectedItems.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Items Selected',
                    text: 'Please select items to approve'
                });
                return;
            }

            // Separate PO IDs and Requisition IDs
            const poIds = selectedItems.filter(item => item.type === 'purchase_order').map(item => item.id);
            const requisitionIds = selectedItems.filter(item => item.type === 'requisition').map(item => item.id);

            // Check if all selected POs have invoices uploaded
            checkBulkApprovalInvoices(poIds, function(canApprove, missingInvoices) {
                if (!canApprove) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invoice Required',
                        html: `The following purchase orders require invoice uploads before approval:<br><br>
                               <strong>${missingInvoices.join('<br>')}</strong><br><br>
                               Please upload invoices for these POs before proceeding.`
                    });
                    return;
                }

                // Build confirmation message
                let confirmMessage = 'Are you sure you want to approve ';
                const items = [];
                if (poIds.length > 0) {
                    items.push(`${poIds.length} purchase order(s)`);
                }
                if (requisitionIds.length > 0) {
                    items.push(`${requisitionIds.length} requisition(s)`);
                }
                confirmMessage += items.join(' and ') + '?';

                Swal.fire({
                    title: 'Confirm Bulk Approval',
                    text: confirmMessage,
                    icon: 'question',
                    input: 'textarea',
                    inputLabel: 'Comments (Optional)',
                    inputPlaceholder: 'Enter any comments for this bulk approval...',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Approve All',
                    cancelButtonText: 'Cancel',
                    inputValidator: (value) => {
                        if (value && value.length > 1000) {
                            return 'Comments must be less than 1000 characters';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkApprove(poIds, requisitionIds, result.value);
                    }
                });
            });
        });

        // Individual approve
        $(document).on('click', '.btn-approve', function() {
            const id = $(this).data('id');
            const type = $(this).data('type') || 'purchase_order';
            
            if (type === 'requisition') {
                // For requisitions, show simple confirmation dialog
                showRequisitionApprovalConfirmation(id);
            } else {
                // For POs, show the full approval modal with invoice requirements
                $('#approvalDetailsModal').data('approvalType', type);
                showApprovalModal(id, 'approve');
            }
        });

        // Individual reject
        $(document).on('click', '.btn-reject', function() {
            const id = $(this).data('id');
            const type = $(this).data('type') || 'purchase_order';
            
            if (type === 'requisition') {
                // For requisitions, show simple rejection dialog
                showRequisitionRejectionConfirmation(id);
            } else {
                // For POs, show the full approval modal
                $('#approvalDetailsModal').data('approvalType', type);
                showApprovalModal(id, 'reject');
            }
        });

        // Export approvals
        $('#exportApprovalsBtn').on('click', function() {
            // Show loading state
            const originalText = $(this).text();
            $(this).prop('disabled', true).text('Exporting...');
            
            // Get current filter values
            const supplier = $('#supplierFilter').val();
            const priority = $('#priorityFilter').val();
            const dateFrom = $('#dateFromFilter').val();
            const dateTo = $('#dateToFilter').val();
            
            // Build query string
            const params = new URLSearchParams();
            if (supplier) params.append('supplier', supplier);
            if (priority) params.append('priority', priority);
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);
            
            // Create download link
            const url = '{{ route("po_approval.export") }}?' + params.toString();
            
            // Create a temporary link to trigger download
            const link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Reset button state after a short delay
            setTimeout(() => {
                $(this).prop('disabled', false).text(originalText);
            }, 2000);
        });

        // Confirm approval button
        $('#confirmApprovalBtn').on('click', function() {
            const id = $('#approvalId').val();
            const action = $('#approvalAction').val();
            const comments = $('#approvalComments').val();
            const priority = $('#approvalPriority').val();
            const assignTo = $('#approvalAssignTo').val();

            console.log('Confirm Approval clicked for PO ID:', id);
            console.log('Action:', action);
            console.log('Current uploadedInvoiceFiles:', window.uploadedInvoiceFiles);

                            if (action === 'approve') {
                // Get the type from the button that triggered this modal
                const type = $('#approvalDetailsModal').data('approvalType') || 'purchase_order';
                
                if (type === 'purchase_order') {
                    // Check if invoice is uploaded before allowing approval for POs
                    const hasLocalFiles = window.uploadedInvoiceFiles && 
                                        window.uploadedInvoiceFiles[id] && 
                                        window.uploadedInvoiceFiles[id].length > 0;
                    
                    if (!hasLocalFiles) {
                        // Check server for uploaded invoices
                        checkPOInvoiceStatus(id, function(hasServerInvoices) {
                            if (hasServerInvoices) {
                                approveItem(id, comments, priority, assignTo, type);
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Invoice Required',
                                    text: 'Please upload at least one invoice before approving this purchase order.'
                                });
                            }
                        });
                        return;
                    }
                }
                
                approveItem(id, comments, priority, assignTo, type);
            } else {
                const type = $('#approvalDetailsModal').data('approvalType') || 'purchase_order';
                rejectItem(id, comments, type);
            }
        });

        // View approval details
        $(document).on('click', '.btn-view-approval', function() {
            const id = $(this).data('id');
            const type = $(this).data('type') || 'purchase_order';
            $('#approvalDetailsModal').data('approvalType', type);
            showApprovalDetailsModal(id, type);
        });

        // Upload invoice
        $(document).on('click', '.btn-upload-invoice', function() {
            const id = $(this).data('id');
            showInvoiceUploadModal(id);
        });

        // Search and filters
        $('#approvalSearch').on('keyup', function() {
            filterApprovals();
        });

                $('#approvalTypeFilter, #priorityFilter, #supplierFilter').on('change', function() {
            filterApprovals();
        });

                 // Pagination event handlers
         $(document).on('click', '.po-approval-pagination .page-link', function(e) {
             e.preventDefault();
             const page = $(this).data('page');
             if (page && !$(this).parent().hasClass('disabled')) {
                 loadApprovalData(page);
             }
         });

        // Initialize invoice upload functionality
        initializeInvoiceUpload();
    }

    // Update statistics
    function updateStats(stats) {
        if (stats) {
            $('.card.bg-warning h3').text(stats.pending_count || 0);
            $('.card.bg-success h3').text(stats.approved_today || 0);
            $('.card.bg-danger h3').text(stats.rejected_today || 0);
            $('#avgResponseTime').text(stats.avg_response_time || 'N/A');
        }
    }

    // Show approval modal
    function showApprovalModal(id, action) {
        $('#approvalAction').val(action);
        $('#approvalId').val(id);
        $('#approvalModalTitle').text(action === 'approve' ? 'Approve Request' : 'Reject Request');
        
        // Initialize button state for approval
        if (action === 'approve') {
            // Check if this PO has uploaded invoices
            const hasUploadedInvoices = window.uploadedInvoiceFiles && 
                                      window.uploadedInvoiceFiles[id] && 
                                      window.uploadedInvoiceFiles[id].length > 0;
            
            if (hasUploadedInvoices) {
                $('#confirmApprovalBtn').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('#confirmApprovalBtn').html('<i class="fas fa-check me-2"></i>Confirm Approval');
            } else {
                // Check server for uploaded invoices
                checkPOInvoiceStatus(id, function(hasInvoices) {
                    if (hasInvoices) {
                        $('#confirmApprovalBtn').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                        $('#confirmApprovalBtn').html('<i class="fas fa-check me-2"></i>Confirm Approval');
                    } else {
                        $('#confirmApprovalBtn').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                        $('#confirmApprovalBtn').html('<i class="fas fa-exclamation-triangle me-2"></i>Invoice Required');
                    }
                });
            }
        }
        
        $('#approvalModal').modal('show');
    }

    // Approve purchase order
    function approvePO(id, comments, priority, assignTo) {
        $.ajax({
            url: `{{ url('company/warehouse/po-approval/approve') }}/${id}`,
            method: 'POST',
            data: {
                comments: comments,
                priority: priority,
                assign_to: assignTo,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#approvalModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    });
                    
                    // Clear uploaded files for this PO since it's been processed
                    if (window.uploadedInvoiceFiles && window.uploadedInvoiceFiles[id]) {
                        delete window.uploadedInvoiceFiles[id];
                    }
                    
                    loadApprovalData(1);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to approve purchase order'
                });
            }
        });
    }

    // Reject purchase order
    function rejectPO(id, comments) {
        $.ajax({
            url: `{{ url('company/warehouse/po-approval/reject') }}/${id}`,
            method: 'POST',
            data: {
                comments: comments,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#approvalModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    });
                    
                    // Clear uploaded files for this PO since it's been processed
                    if (window.uploadedInvoiceFiles && window.uploadedInvoiceFiles[id]) {
                        delete window.uploadedInvoiceFiles[id];
                    }
                    
                    loadApprovalData(1);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to reject purchase order'
                });
            }
        });
    }

    // Unified approve function for both POs and Requisitions
    function approveItem(id, comments, priority, assignTo, type) {
        $.ajax({
            url: `{{ url('company/warehouse/po-approval/approve') }}/${id}`,
            method: 'POST',
            data: {
                comments: comments,
                priority: priority,
                assign_to: assignTo,
                type: type,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('=== AJAX SUCCESS RESPONSE ===');
                console.log('Full response:', response);
                console.log('Response success:', response.success);
                console.log('Response message:', response.message);
                console.log('Created PO:', response.created_po);
                console.log('Items needing reorder:', response.items_needing_reorder);
                console.log('Debug info:', response.debug_info);
                console.log('Debug step:', response.debug_info ? response.debug_info.debug_step : 'not provided');
                console.log('Debug message:', response.debug_info ? response.debug_info.debug_message : 'not provided');
                console.log('Full debug object:', JSON.stringify(response.debug_info, null, 2));
                
                if (response.success) {
                    $('#approvalModal').modal('hide');
                    
                    // Enhanced success message for re-order cases
                    let successMessage = response.message;
                    if (response.created_po) {
                        successMessage += '\n\nCreated Re-order PO: ' + response.created_po;
                        console.log('✅ Re-order PO created successfully:', response.created_po);
                    } else if (response.items_needing_reorder && response.items_needing_reorder.length > 0) {
                        console.log('⚠️ Items need re-order but no PO was created');
                        console.log('Items needing reorder:', response.items_needing_reorder);
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: successMessage
                    });
                    
                    // Clear uploaded files for POs since it's been processed
                    if (type === 'purchase_order' && window.uploadedInvoiceFiles && window.uploadedInvoiceFiles[id]) {
                        delete window.uploadedInvoiceFiles[id];
                    }
                    
                    loadApprovalData(1);
                } else {
                    // Handle inventory validation errors for requisitions
                    if (response.validation_errors && response.validation_errors.length > 0) {
                        let errorMessage = response.message + '\n\n';
                        errorMessage += 'Inventory Issues:\n';
                        response.validation_errors.forEach(function(error) {
                            errorMessage += '• ' + error + '\n';
                        });
                        
                        Swal.fire({
                            icon: 'warning',
                            title: 'Cannot Approve Requisition',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                }
            },
            error: function(xhr) {
                let errorMessage = `Failed to approve ${type === 'requisition' ? 'requisition' : 'purchase order'}`;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
    }

    // Unified reject function for both POs and Requisitions
    function rejectItem(id, comments, type) {
        $.ajax({
            url: `{{ url('company/warehouse/po-approval/reject') }}/${id}`,
            method: 'POST',
            data: {
                comments: comments,
                type: type,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#approvalModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    });
                    
                    // Clear uploaded files for POs since it's been processed
                    if (type === 'purchase_order' && window.uploadedInvoiceFiles && window.uploadedInvoiceFiles[id]) {
                        delete window.uploadedInvoiceFiles[id];
                    }
                    
                    loadApprovalData(1);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = `Failed to reject ${type === 'requisition' ? 'requisition' : 'purchase order'}`;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
    }

    // Show approval details modal
    function showApprovalDetailsModal(id, type = 'purchase_order') {
        // Load approval details and show modal
        $.ajax({
            url: `{{ url('company/warehouse/po-approval/details') }}/${id}`,
            method: 'POST',
            data: {
                type: type,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success && response.data) {
                    populateApprovalDetails(response.data, response.type || type);
                    $('#approvalDetailsModal').data('currentPoId', id); // Store the ID
                    $('#approvalDetailsModal').data('currentType', response.type || type); // Store the type
                    
                    // Show modal first, then check invoice status (only for POs)
                    $('#approvalDetailsModal').modal('show');
                     
                    // Check invoice status after modal is shown (only for purchase orders)
                    if ((response.type || type) === 'purchase_order') {
                        setTimeout(() => {
                            checkAndDisplayInvoiceStatus(id);
                        }, 200);
                    }
                } else {
                   Swal.fire({
                       icon: 'error',
                       title: 'Error!',
                       text: 'Failed to load approval details'
                   });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load approval details'
                });
            }
        });
    }

    // Populate approval details modal
    function populateApprovalDetails(data, type = 'purchase_order') {
        const isRequisition = type === 'requisition';
        
        // Update modal title based on type
        const modalTitle = isRequisition ? 'Requisition Approval Details' : 'PO Approval Details';
        $('#approvalDetailsModal .modal-title').text(modalTitle);
        
        // Set basic details based on type
        if (isRequisition) {
            $('#detailReference').text(data.requisition_number);
            $('#detailType').text('Purchase Requisition');
            $('#detailRequester').text(data.requestor_name || (data.requestor ? 
                (data.requestor.personalInfo ? 
                    ((data.requestor.personalInfo.first_name || '') + ' ' + (data.requestor.personalInfo.last_name || '')).trim() || ('Employee #' + data.requestor.staff_id) :
                    ('Employee #' + data.requestor.staff_id)
                ) : 'Unknown'
            ));
            $('#detailDepartment').text(data.department ? (typeof data.department === 'object' ? data.department.name : data.department) : 'Internal');
            
            // Show attachments section for requisitions
            $('#attachmentsSection').show();
            populateAttachments(data.attachments || []);
            
            // Calculate total amount from items if not already calculated
            let totalAmount = data.total_amount || 0;
            if ((!totalAmount || totalAmount === 0) && data.items) {
                totalAmount = calculateItemsTotal(data.items);
            }
            $('#detailAmount').text(formatCurrency(totalAmount));
            $('#detailPriority').text((data.priority || 'normal').toUpperCase());
        } else {
            $('#detailReference').text(data.po_number);
            $('#detailType').text('Purchase Order');
            $('#detailRequester').text(data.requested_by || (data.created_by ? data.created_by.fullname : 'Unknown'));
            $('#detailDepartment').text(data.supplier ? data.supplier.company_name : 'Unknown');
            // Use the proper tax-calculated total amount instead of total_value
            $('#detailAmount').text(formatCurrency(data.total_amount || data.total_value || 0));
            $('#detailPriority').text('Normal');
            
            // Hide attachments section for POs
            $('#attachmentsSection').hide();
        }
        
        $('#detailDate').text(formatDate(data.created_at));
        // Set description based on type
        const description = isRequisition ? 
            (data.title || 'No description provided') : 
            (data.title || 'No description provided');
        $('#detailDescription').text(description);
        
        // Populate dynamic approval history
        populateApprovalHistory(data, type);
        
        // Populate items based on type
        if (isRequisition) {
            populateRequisitionItems(data.items);
        } else {
            populatePOItems(data.items, data); // Pass the full PO data for tax information
        }
        
        // Show/hide invoice sections based on type
        if (isRequisition) {
            $('#invoiceStatusSection').hide();
        } else {
            $('#invoiceStatusSection').show();
            checkAndDisplayInvoiceStatus(data.id);
        }
    }
    
    // Populate dynamic approval history
    function populateApprovalHistory(data, type = 'purchase_order') {
        const timelineContainer = $('.timeline');
        timelineContainer.empty();
        
        const isRequisition = type === 'requisition';
        const itemName = isRequisition ? 'Requisition' : 'Purchase Order';
        const createdBy = isRequisition 
            ? (data.requestor_name || (data.requestor ? 
                (data.requestor.personalInfo ? 
                    ((data.requestor.personalInfo.first_name || '') + ' ' + (data.requestor.personalInfo.last_name || '')).trim() || ('Employee #' + data.requestor.staff_id) :
                    ('Employee #' + data.requestor.staff_id)
                ) : 'Unknown'
            ))
            : (data.created_by ? data.created_by.fullname : 'Unknown');
        
        // Add creation event
        timelineContainer.append(`
            <div class="timeline-item">
                <div class="timeline-marker bg-info"></div>
                <div class="timeline-content">
                    <div class="timeline-title">${itemName} Created</div>
                    <div class="timeline-subtitle">${formatDateTime(data.created_at)}</div>
                    <div class="timeline-text">${itemName} created by ${createdBy}</div>
                </div>
            </div>
        `);
        
        // Add approval events if any
        if (data.approved_by || data.approver) {
            const approver = isRequisition 
                ? (data.approver ? data.approver.fullname : 'Unknown')
                : (data.approved_by ? data.approved_by.fullname : 'Unknown');
                
            timelineContainer.append(`
                <div class="timeline-item">
                    <div class="timeline-marker bg-success"></div>
                    <div class="timeline-content">
                        <div class="timeline-title">Approved</div>
                        <div class="timeline-subtitle">${formatDateTime(data.approved_at)}</div>
                        <div class="timeline-text">Approved by ${approver}</div>
                    </div>
                </div>
            `);
        }
        
        // Add rejection events if any
        if (data.rejected_by || data.rejection_reason) {
            timelineContainer.append(`
                <div class="timeline-item">
                    <div class="timeline-marker bg-danger"></div>
                    <div class="timeline-content">
                        <div class="timeline-title">Rejected</div>
                        <div class="timeline-subtitle">${formatDateTime(data.rejected_at)}</div>
                        <div class="timeline-text">Rejected${data.rejection_reason ? ': ' + data.rejection_reason : ''}</div>
                    </div>
                </div>
            `);
        }
        
        // Add logs if available
        if (data.logs && data.logs.length > 0) {
            data.logs.forEach(log => {
                const logIcon = getLogIcon(log.action);
                const logTitle = getLogTitle(log.action);
                
                timelineContainer.append(`
                    <div class="timeline-item">
                        <div class="timeline-marker ${logIcon.class}"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">${logTitle}</div>
                            <div class="timeline-subtitle">${formatDateTime(log.performed_at)}</div>
                            <div class="timeline-text">${log.description}</div>
                        </div>
                    </div>
                `);
            });
        }
        
        // If no logs, show current status
        if (!data.logs || data.logs.length === 0) {
            const currentStatus = data.status || (isRequisition ? 'pending' : 'created');
            const statusInfo = getStatusInfo(currentStatus, isRequisition);
            
            timelineContainer.append(`
                <div class="timeline-item">
                    <div class="timeline-marker ${statusInfo.icon}"></div>
                    <div class="timeline-content">
                        <div class="timeline-title">${statusInfo.title}</div>
                        <div class="timeline-subtitle">Current Status</div>
                        <div class="timeline-text">${statusInfo.description}</div>
                    </div>
                </div>
            `);
        }
    }
    
    // Check and display invoice upload status in approval details
    function checkAndDisplayInvoiceStatus(poId) {
        checkPOInvoiceStatus(poId, function(hasInvoices, invoiceData) {
            const invoiceSection = $('#uploadedFilesList');
            
            if (hasInvoices && invoiceData && invoiceData.length > 0) {
                // Show uploaded invoices
                let invoiceHtml = '<h6 class="mb-3">Uploaded Invoices</h6>';
                invoiceData.forEach((invoice, index) => {
                    const fileIcon = getFileIcon(invoice.file_type);
                    const fileSize = formatFileSize(invoice.file_size);
                    const uploadDate = new Date(invoice.created_at).toLocaleDateString();
                    
                    invoiceHtml += `
                        <div class="uploaded-file-item existing-file mb-2">
                            <div class="file-info">
                                <div class="file-icon ${fileIcon.class}">
                                    <i class="${fileIcon.icon}"></i>
                                </div>
                                <div class="file-details">
                                    <h6 class="mb-1">${invoice.original_name}</h6>
                                    <small class="text-muted">${fileSize} • Uploaded: ${uploadDate}</small>
                                    ${invoice.uploaded_by ? `<br><small class="text-muted">By: ${invoice.uploaded_by.fullname || 'Unknown'}</small>` : ''}
                                    ${invoice.notes ? `<br><small class="text-muted"><strong>Notes:</strong> ${invoice.notes}</small>` : ''}
                                </div>
                            </div>
                            <div class="file-actions">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="downloadInvoice(${invoice.purchase_order_id}, ${invoice.id})">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteExistingInvoice(${invoice.purchase_order_id}, ${invoice.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="file-status">
                                <span class="badge bg-success">Uploaded</span>
                            </div>
                        </div>
                    `;
                });
                
                invoiceSection.html(invoiceHtml);
            } else {
                // Show no invoices message
                invoiceSection.html(`
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>No invoices uploaded yet.</strong><br>
                        <small class="text-muted">Upload invoice files to proceed with approval.</small>
                    </div>
                `);
            }
        });
    }
    
    // Helper functions for timeline
    function getLogIcon(action) {
        const icons = {
            'create_po': { class: 'bg-info' },
            'update_po': { class: 'bg-warning' },
            'approve_po': { class: 'bg-success' },
            'reject_po': { class: 'bg-danger' },
            'upload_invoice': { class: 'bg-primary' },
            'delete_invoice': { class: 'bg-secondary' }
        };
        return icons[action] || { class: 'bg-secondary' };
    }
    
    function getLogTitle(action) {
        const titles = {
            'create_po': 'Purchase Order Created',
            'update_po': 'Purchase Order Updated',
            'approve_po': 'Purchase Order Approved',
            'reject_po': 'Purchase Order Rejected',
            'upload_invoice': 'Invoice Uploaded',
            'delete_invoice': 'Invoice Deleted'
        };
        return titles[action] || 'Action Performed';
    }
    
    function getStatusInfo(status, isRequisition = false) {
        const itemType = isRequisition ? 'Requisition' : 'Purchase order';
        
        const statuses = {
            'draft': {
                title: 'Draft',
                description: `${itemType} is in draft status`,
                icon: 'bg-secondary'
            },
            'created': {
                title: 'Created',
                description: `${itemType} created and waiting for approval`,
                icon: 'bg-info'
            },
            'pending': {
                title: 'Pending',
                description: isRequisition 
                    ? 'Requisition submitted and awaiting approval'
                    : 'Purchase order approved and pending processing',
                icon: 'bg-warning'
            },
            'approved': {
                title: 'Approved',
                description: `${itemType} has been approved`,
                icon: 'bg-success'
            },
            'rejected': {
                title: 'Rejected',
                description: `${itemType} has been rejected`,
                icon: 'bg-danger'
            },
            'completed': {
                title: 'Completed',
                description: `${itemType} has been completed`,
                icon: 'bg-info'
            }
        };
        return statuses[status] || {
            title: 'Unknown Status',
            description: 'Status information not available',
            icon: 'bg-secondary'
        };
    }
    
    function formatDateTime(dateString) {
        return new Date(dateString).toLocaleString();
    }
    
         // Populate purchase order items table
     function populatePOItems(items, poData = null) {
         const tbody = $('#poItemsTableBody');
         const thead = $('#poItemsTable thead tr');
         tbody.empty();
         
         // Handle items as JSON field (string) or array
         let itemsArray = items;
         if (typeof items === 'string') {
             try {
                 itemsArray = JSON.parse(items);
             } catch (e) {
                 console.error('Failed to parse items JSON:', e);
                 itemsArray = [];
             }
         }
         
         if (!itemsArray || itemsArray.length === 0) {
             tbody.html(`
                 <tr>
                     <td colspan="5" class="text-center text-muted py-3">
                         <i class="fas fa-inbox me-2"></i>No items found for this purchase order.
                     </td>
                 </tr>
             `);
             return;
         }
         
         // Check if any item has re-order data
         console.log('Approval Tab - PO Items:', itemsArray);
         const hasReorders = itemsArray.some(item => {
             console.log('Checking item:', item.name, 'is_reorder:', item.is_reorder);
             return item.is_reorder === true || item.is_reorder === 1 || item.is_reorder === '1';
         });
         console.log('Approval Tab - Has reorders:', hasReorders);
         
         // Update table header to include re-order column if needed
         thead.html(`
             <th>Item</th>
             <th>Description</th>
             <th class="text-center">Quantity</th>
             <th class="text-end">Unit Price</th>
             <th class="text-end">Total</th>
             ${hasReorders ? '<th>Re-order Info</th>' : ''}
         `);
         
         let totalAmount = 0;
         
         itemsArray.forEach((item, index) => {
             const itemTotal = (item.quantity || 0) * (item.unit_price || 0);
             totalAmount += itemTotal;
             const isReorderItem = item.is_reorder === true || item.is_reorder === 1 || item.is_reorder === '1';
             
             let reorderInfo = '';
             if (hasReorders) {
                 if (isReorderItem) {
                     reorderInfo = `
                         <td>
                             <div class="badge bg-warning text-dark mb-2 d-block">
                                 <i class="fas fa-redo me-1"></i> Re-order
                             </div>
                             ${item.batch_number ? `<small class="text-muted d-block"><strong>Batch:</strong> ${item.batch_number}</small>` : ''}
                             ${item.reorder_reason ? `<small class="text-muted d-block"><strong>Reason:</strong> ${item.reorder_reason}</small>` : ''}
                         </td>`;
                 } else {
                     reorderInfo = '<td><span class="badge bg-success">New Item</span></td>';
                 }
             }
             
             const row = `
                 <tr>
                     <td>
                         <strong>${item.item_name || item.name || 'N/A'}</strong>
                         ${item.item_code ? `<br><small class="text-muted">Code: ${item.item_code}</small>` : ''}
                     </td>
                     <td>${item.title || item.description || 'No description'}</td>
                     <td class="text-center">
                         <span class="badge bg-primary">${item.quantity || 0}</span>
                     </td>
                     <td class="text-end">${formatCurrency(item.unit_price || 0)}</td>
                     <td class="text-end fw-bold">${formatCurrency(itemTotal)}</td>
                     ${reorderInfo}
                 </tr>
             `;
             tbody.append(row);
         });
         
        // Calculate colspan based on whether we have re-order column
        const colspanValue = hasReorders ? 5 : 4;
         
        // Add tax breakdown rows
        tbody.append(`
            <tr class="table-active">
                <td colspan="${colspanValue}" class="text-end fw-bold">Subtotal:</td>
                <td class="text-end fw-bold">${formatCurrency(totalAmount)}</td>
            </tr>
        `);
        
        // Add tax information if available from PO data
        if (poData) {
            const taxAmount = parseFloat(poData.tax_amount) || 0;
            const taxRate = parseFloat(poData.tax_rate) || 0;
            const isTaxExempt = poData.is_tax_exempt || false;
            
            if (isTaxExempt) {
                tbody.append(`
                    <tr class="table-active">
                        <td colspan="${colspanValue}" class="text-end fw-bold text-danger">Tax Status:</td>
                        <td class="text-end fw-bold text-danger">TAX EXEMPT</td>
                    </tr>
                `);
            } else if (taxAmount > 0) {
                tbody.append(`
                    <tr class="table-active">
                        <td colspan="${colspanValue}" class="text-end fw-bold">Tax (${taxRate}%):</td>
                        <td class="text-end fw-bold">${formatCurrency(taxAmount)}</td>
                    </tr>
                `);
            }
            
            
            // Add final total
            const finalTotal = parseFloat(poData.total_amount) || totalAmount;
            tbody.append(`
                <tr class="table-active">
                    <td colspan="${colspanValue}" class="text-end fw-bold">Total Amount:</td>
                    <td class="text-end fw-bold">${formatCurrency(finalTotal)}</td>
                </tr>
            `);
        } else {
            // Fallback to simple total if no PO data
            tbody.append(`
                <tr class="table-active">
                    <td colspan="${colspanValue}" class="text-end fw-bold">Total Amount:</td>
                    <td class="text-end fw-bold text-primary">${formatCurrency(totalAmount)}</td>
                </tr>
            `);
        }
     }
     
     // Populate requisition items
     function populateRequisitionItems(items) {
         const tbody = $('#poItemsTableBody'); // Reuse the same table
         tbody.empty();
         
         // Handle items as JSON field (string) or array
         let itemsArray = items;
         if (typeof items === 'string') {
             try {
                 itemsArray = JSON.parse(items);
             } catch (e) {
                 console.error('Failed to parse items JSON:', e);
                 itemsArray = [];
             }
         }
         
         if (!itemsArray || itemsArray.length === 0) {
             tbody.html(`
                 <tr>
                     <td colspan="5" class="text-center text-muted py-3">
                         <i class="fas fa-inbox me-2"></i>No items found for this requisition.
                     </td>
                 </tr>
             `);
             return;
         }
         
         let totalAmount = 0;
         
         itemsArray.forEach((item, index) => {
             const itemTotal = (item.quantity || 0) * (item.price || item.unit_price || 0);
             totalAmount += itemTotal;
             
             const row = `
                 <tr>
                     <td>
                         <strong>${item.item_name || item.name || 'N/A'}</strong>
                         ${item.item_code ? `<br><small class="text-muted">Code: ${item.item_code}</small>` : ''}
                     </td>
                     <td>${item.title || 'No title'}</td>
                     <td class="text-center">
                         <span class="badge bg-success">${item.quantity || 0}</span>
                     </td>
                     <td class="text-end">${formatCurrency(item.price || item.unit_price || 0)}</td>
                     <td class="text-end fw-bold">${formatCurrency(itemTotal)}</td>
                 </tr>
             `;
             tbody.append(row);
         });
         
         // Add total row
         tbody.append(`
             <tr class="table-active">
                 <td colspan="4" class="text-end fw-bold">Total Amount:</td>
                 <td class="text-end fw-bold text-success">${formatCurrency(totalAmount)}</td>
             </tr>
         `);
     }

    // Helper functions for formatting and utilities
    function formatCurrency(amount) {
        if (amount === null || amount === undefined) return 'GH₵0.00';
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'GHS'
        }).format(amount);
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function getFileIcon(fileType) {
        if (fileType && fileType.includes('pdf')) {
            return { class: 'pdf', icon: 'fas fa-file-pdf' };
        } else if (fileType && (fileType.includes('image') || fileType.includes('jpg') || fileType.includes('jpeg') || fileType.includes('png'))) {
            return { class: 'image', icon: 'fas fa-file-image' };
        } else if (fileType && (fileType.includes('word') || fileType.includes('doc'))) {
            return { class: 'document', icon: 'fas fa-file-word' };
        } else {
            return { class: 'document', icon: 'fas fa-file' };
        }
    }

    // Invoice upload functions
    function initializeInvoiceUpload() {
        // Remove existing handlers to prevent duplicates
        $('#modalInvoiceFileInput').off('change');
        $('#saveInvoiceUploadBtn').off('click');
        $('#modalInvoiceUploadArea').off('click');
        
        // File input change handler (remove any existing handlers first)
        $('#modalInvoiceFileInput').off('change').on('change', function(e) {
            console.log('File input change handler triggered in setupInvoiceUploadHandlers');
            const files = e.target.files;
            if (files && files.length > 0) {
                console.log('Files selected:', files.length);
                handleFileSelection(files, 'modalInvoiceFileInput');
            }
            // Clear the input to prevent duplicate processing
            $(this).val('');
        });

        // Save upload button
        $('#saveInvoiceUploadBtn').on('click', function() {
            const approvalId = $('#invoiceUploadModal').data('approvalId');
            saveInvoiceUpload(approvalId);
        });

        // Upload area click handler
        $('#modalInvoiceUploadArea').on('click', function() {
            $('#modalInvoiceFileInput').click();
        });

        // Setup drag and drop
        setupDragAndDrop();
    }

    function setupDragAndDrop() {
        const uploadArea = $('#modalInvoiceUploadArea');
        
        uploadArea.off('dragover dragenter drop dragleave');
        
        uploadArea.on('dragover dragenter', function(e) {
            e.preventDefault();
            $(this).addClass('dragover');
        });

        uploadArea.on('dragleave drop', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
        });

        uploadArea.on('drop', function(e) {
            e.preventDefault();
            const files = e.originalEvent.dataTransfer.files;
            handleFileSelection(files, 'modalInvoiceFileInput');
        });
    }

    // Add a flag to prevent multiple processing
    window.fileProcessingInProgress = false;
    
    // Add a watcher to detect if files are being cleared
    let lastFileCount = 0;
    setInterval(() => {
        if (window.uploadedInvoiceFiles) {
            Object.keys(window.uploadedInvoiceFiles).forEach(approvalId => {
                const currentCount = window.uploadedInvoiceFiles[approvalId].length;
                if (currentCount !== lastFileCount) {
                    console.log(`🔍 File count changed for approval ${approvalId}: ${lastFileCount} → ${currentCount}`);
                    lastFileCount = currentCount;
                }
            });
        }
    }, 500);

    function handleFileSelection(files, inputId) {
        console.log('=== handleFileSelection START ===');
        console.log('handleFileSelection called with:', files.length, 'files');
        
        // Prevent multiple processing
        if (window.fileProcessingInProgress) {
            console.log('File processing already in progress, skipping...');
            return;
        }
        
        if (!files || files.length === 0) {
            console.log('No files provided, returning');
            return;
        }
        
        // Set processing flag
        window.fileProcessingInProgress = true;
        
        // Store files for the current PO
        const approvalId = $('#invoiceUploadModal').data('approvalId');
        console.log('Approval ID:', approvalId);
        
        if (!window.uploadedInvoiceFiles) {
            window.uploadedInvoiceFiles = {};
            console.log('Created new uploadedInvoiceFiles object');
        }
        
        if (!window.uploadedInvoiceFiles[approvalId]) {
            window.uploadedInvoiceFiles[approvalId] = [];
            console.log('Created new array for approval:', approvalId);
        }
        
        // Store the current count before adding
        const beforeCount = window.uploadedInvoiceFiles[approvalId].length;
        console.log('Files before adding:', beforeCount);
        
        // Add new files
        Array.from(files).forEach((file, index) => {
            console.log(`Adding file ${index + 1}:`, file.name, 'Size:', file.size);
            window.uploadedInvoiceFiles[approvalId].push(file);
        });
        
        const afterCount = window.uploadedInvoiceFiles[approvalId].length;
        console.log('Files after adding:', afterCount);
        console.log('Total files for approval', approvalId, ':', afterCount);
        
        // Use setTimeout to ensure UI update happens after any other event handlers
        setTimeout(() => {
            console.log('=== Delayed displayUploadedFiles call ===');
            console.log('Files at display time:', window.uploadedInvoiceFiles[approvalId].length);
            displayUploadedFiles(inputId);
            updateApprovalButtonState(approvalId);
            
            // Clear processing flag
            window.fileProcessingInProgress = false;
            console.log('=== handleFileSelection END ===');
        }, 100);
    }

    function displayUploadedFiles(inputId) {
        const approvalId = $('#invoiceUploadModal').data('approvalId');
        const container = $('#modalUploadedFilesList');
        
        console.log('=== displayUploadedFiles START ===');
        console.log('displayUploadedFiles called for approval:', approvalId);
        console.log('Container found:', container.length);
        console.log('Files available:', window.uploadedInvoiceFiles ? window.uploadedInvoiceFiles[approvalId] : 'No files object');
        
        // Ensure container exists
        if (container.length === 0) {
            console.error('Container #modalUploadedFilesList not found!');
            return;
        }
        
        if (!window.uploadedInvoiceFiles || !window.uploadedInvoiceFiles[approvalId] || window.uploadedInvoiceFiles[approvalId].length === 0) {
            console.log('No files to display, showing info message');
            container.html(`
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No files uploaded yet.
                </div>
            `);
            console.log('=== displayUploadedFiles END (no files) ===');
            return;
        }
        
        console.log('Displaying', window.uploadedInvoiceFiles[approvalId].length, 'files');

        let html = '';
        window.uploadedInvoiceFiles[approvalId].forEach((file, index) => {
            const fileIcon = getFileIcon(file.type);
            const fileSize = formatFileSize(file.size);
            
            html += `
                <div class="uploaded-file-item">
                    <div class="file-info">
                        <div class="file-icon ${fileIcon.class}">
                            <i class="${fileIcon.icon}"></i>
                        </div>
                        <div class="file-details">
                            <h6 class="mb-1">${file.name}</h6>
                            <small class="text-muted">${fileSize}</small>
                        </div>
                    </div>
                    <div class="file-actions">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        console.log('Setting HTML content:', html);
        container.html(html);
        console.log('HTML content set, container now has:', container.html());
        console.log('=== displayUploadedFiles END (files displayed) ===');
        
        // Add a check after 1 second to see if files are still there
        setTimeout(() => {
            console.log('=== 1 second later check ===');
            console.log('Files still there:', window.uploadedInvoiceFiles[approvalId].length);
            console.log('Container content:', container.html());
            if (window.uploadedInvoiceFiles[approvalId].length === 0) {
                console.log('❌ FILES HAVE BEEN CLEARED! Something cleared the files!');
            } else {
                console.log('✅ Files are still there');
            }
        }, 1000);
    }

    function removeFile(index) {
        const approvalId = $('#invoiceUploadModal').data('approvalId');
        if (window.uploadedInvoiceFiles && window.uploadedInvoiceFiles[approvalId]) {
            console.log('Removing file at index:', index);
            window.uploadedInvoiceFiles[approvalId].splice(index, 1);
            console.log('Files remaining:', window.uploadedInvoiceFiles[approvalId].length);
            
            // Use setTimeout to ensure UI update happens properly
            setTimeout(() => {
                displayUploadedFiles('modalInvoiceFileInput');
                updateApprovalButtonState(approvalId);
            }, 50);
        }
    }

    function getFileIcon(fileType) {
        if (fileType.includes('pdf')) {
            return { icon: 'fas fa-file-pdf', class: 'text-danger' };
        } else if (fileType.includes('image')) {
            return { icon: 'fas fa-file-image', class: 'text-success' };
        } else if (fileType.includes('word') || fileType.includes('document')) {
            return { icon: 'fas fa-file-word', class: 'text-primary' };
        } else {
            return { icon: 'fas fa-file', class: 'text-secondary' };
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function updateApprovalButtonState(approvalId) {
        const hasFiles = window.uploadedInvoiceFiles && 
                        window.uploadedInvoiceFiles[approvalId] && 
                        window.uploadedInvoiceFiles[approvalId].length > 0;
        
        if (hasFiles) {
            $('#confirmApprovalBtn').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
            $('#confirmApprovalBtn').html('<i class="fas fa-check me-2"></i>Confirm Approval');
        } else {
            // Check server for uploaded invoices
            checkPOInvoiceStatus(approvalId, function(hasServerInvoices) {
                if (hasServerInvoices) {
                    $('#confirmApprovalBtn').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                    $('#confirmApprovalBtn').html('<i class="fas fa-check me-2"></i>Confirm Approval');
                } else {
                    $('#confirmApprovalBtn').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                    $('#confirmApprovalBtn').html('<i class="fas fa-exclamation-triangle me-2"></i>Invoice Required');
                }
            });
        }
    }

    function showInvoiceUploadModal(id) {
        $('#invoiceUploadModal').data('approvalId', id);
        
        // Check for existing invoices
        checkPOInvoiceStatus(id, function(hasInvoices, invoiceData) {
            if (hasInvoices && invoiceData && invoiceData.length > 0) {
                $('#invoiceUploadModalLabel').html('<i class="fas fa-file-invoice me-2"></i>Edit Invoice Upload');
                $('#saveInvoiceUploadBtn').html('<i class="fas fa-save me-2"></i>Update Upload');
                displayExistingInvoices(invoiceData);
            } else {
                $('#invoiceUploadModalLabel').html('<i class="fas fa-file-invoice me-2"></i>Upload Invoice');
                $('#saveInvoiceUploadBtn').html('<i class="fas fa-save me-2"></i>Save Upload');
                $('#modalUploadedFilesList').html(`
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        No files uploaded yet.
                    </div>
                `);
            }
        });
        
        // Clear any previous files
        if (window.uploadedInvoiceFiles && window.uploadedInvoiceFiles[id]) {
            delete window.uploadedInvoiceFiles[id];
        }
        
        $('#modalInvoiceFileInput').val('');
        $('#modalInvoiceNotes').val('');
        $('#invoiceUploadModal').modal('show');
    }

    function displayExistingInvoices(invoiceData) {
        const container = $('#modalUploadedFilesList');
        let html = '<h6 class="mb-3">Existing Invoices</h6>';
        
        invoiceData.forEach((invoice, index) => {
            const fileIcon = getFileIcon(invoice.file_type);
            const fileSize = formatFileSize(invoice.file_size);
            const uploadDate = new Date(invoice.created_at).toLocaleDateString();
            
            html += `
                <div class="uploaded-file-item existing-file">
                    <div class="file-info">
                        <div class="file-icon ${fileIcon.class}">
                            <i class="${fileIcon.icon}"></i>
                        </div>
                        <div class="file-details">
                            <h6 class="mb-1">${invoice.original_name}</h6>
                            <small class="text-muted">${fileSize} • Uploaded: ${uploadDate}</small>
                            ${invoice.uploaded_by ? `<br><small class="text-muted">By: ${invoice.uploaded_by.fullname || 'Unknown'}</small>` : ''}
                            ${invoice.notes ? `<br><small class="text-muted"><strong>Notes:</strong> ${invoice.notes}</small>` : ''}
                        </div>
                    </div>
                    <div class="file-actions">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="downloadInvoice(${invoice.purchase_order_id}, ${invoice.id})">
                            <i class="fas fa-download"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteExistingInvoice(${invoice.purchase_order_id}, ${invoice.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="file-status">
                        <span class="badge bg-success">Uploaded</span>
                    </div>
                </div>
            `;
        });
        
        container.html(html);
    }

    function saveInvoiceUpload(approvalId) {
        const files = window.uploadedInvoiceFiles && window.uploadedInvoiceFiles[approvalId] ? window.uploadedInvoiceFiles[approvalId] : [];
        const notes = $('#modalInvoiceNotes').val();
        
        // Debug logging
        console.log('Invoice upload debug:', {
            approvalId: approvalId,
            files: files,
            filesLength: files.length,
            notes: notes,
            uploadedFiles: window.uploadedInvoiceFiles
        });
        
        if (files.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Files Selected',
                text: 'Please select at least one file to upload.'
            });
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('notes', notes);
        
        files.forEach((file, index) => {
            formData.append(`files[]`, file);
        });

        // Show loading state
        $('#saveInvoiceUploadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Uploading...');

        // Debug: Log form data
        console.log('FormData contents:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        $.ajax({
            url: `{{ url('company/warehouse/po-approval/upload-invoice') }}/${approvalId}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#invoiceUploadModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    });
                    
                    // Mark as uploaded for this session
                    window.uploadedInvoiceFiles[approvalId] = [{ uploaded: true }];
                    
                    // Refresh the table to update invoice status
                    loadApprovalData(1);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: response.message || 'Failed to upload invoice files'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Invoice upload error:', xhr);
                console.error('Response JSON:', xhr.responseJSON);
                console.error('Response text:', xhr.responseText);
                console.error('Status:', xhr.status);
                
                let errorMessage = 'Failed to upload invoice files';
                
                if (xhr.status === 404) {
                    errorMessage = 'Upload endpoint not found';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred';
                } else if (xhr.status === 422) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        console.error('Validation errors:', xhr.responseJSON.errors);
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        errorMessage = 'Validation error: ' + errors.join(', ');
                    } else {
                        errorMessage = 'Validation error: Please check your input';
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: errorMessage
                });
            },
            complete: function() {
                $('#saveInvoiceUploadBtn').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save Upload');
            }
        });
    }

    function deleteExistingInvoice(poId, invoiceId) {
        Swal.fire({
            title: 'Delete Invoice?',
            text: 'Are you sure you want to delete this invoice?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('company/warehouse/po-approval/delete-invoice') }}/${poId}/${invoiceId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message
                            });
                            
                            // Refresh the modal content
                            showInvoiceUploadModal(poId);
                            
                            // Update the main table
                            updateInvoiceStatusBadge(poId);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete invoice'
                        });
                    }
                });
            }
        });
    }

    function downloadInvoice(poId, invoiceId) {
        window.open(`{{ url('company/warehouse/po-approval/download-invoice') }}/${poId}/${invoiceId}`, '_blank');
    }

         function checkPOInvoiceStatus(poId, callback) {
         $.ajax({
             url: `{{ url('company/warehouse/po-approval/details') }}/${poId}`,
             method: 'POST',
             data: {
                 _token: '{{ csrf_token() }}'
             },
             success: function(response) {
                 if (response.success && response.data && response.data.invoices && response.data.invoices.length > 0) {
                     callback(true, response.data.invoices);
                 } else {
                     callback(false, null);
                 }
             },
             error: function(xhr, status, error) {
                 callback(false, null);
             }
         });
     }

     // Batch check invoice status for multiple POs
     function batchCheckInvoiceStatus(poIds) {
         if (!poIds || poIds.length === 0) return;
         
         $.ajax({
             url: '{{ route("po_approval.batch_invoice_status") }}',
             method: 'POST',
             data: {
                 po_ids: poIds,
                 _token: '{{ csrf_token() }}'
             },
             success: function(response) {
                 if (response.success && response.data) {
                     // Update all invoice status badges at once
                     Object.keys(response.data).forEach(poId => {
                         const hasInvoices = response.data[poId];
                         updateInvoiceStatusBadgeFast(poId, hasInvoices);
                     });
                 }
             },
             error: function(xhr, status, error) {
                 // If batch check fails, fall back to individual checks but with shorter timeout
                 poIds.forEach(poId => {
                     updateInvoiceStatusBadgeFast(poId, false);
                 });
             }
         });
     }

     // Fast invoice status badge update (no AJAX call)
     function updateInvoiceStatusBadgeFast(poId, hasInvoices) {
         const badge = $(`.invoice-status[data-po-id="${poId}"]`);
         
         if (!badge.length) {
             return;
         }
         
         // First check if there are local files for this PO
         const hasLocalFiles = window.uploadedInvoiceFiles && 
                              window.uploadedInvoiceFiles[poId] && 
                              window.uploadedInvoiceFiles[poId].length > 0;
         
         if (hasLocalFiles) {
             badge.removeClass('bg-secondary bg-danger').addClass('bg-success');
             badge.html('<i class="fas fa-check me-1"></i>Invoice Uploaded');
             return;
         }
         
         // Use the batch check result
         if (hasInvoices) {
             badge.removeClass('bg-secondary bg-danger').addClass('bg-success');
             badge.html('<i class="fas fa-check me-1"></i>Invoice Uploaded');
         } else {
             badge.removeClass('bg-secondary bg-success').addClass('bg-danger');
             badge.html('<i class="fas fa-exclamation-triangle me-1"></i>Invoice Required');
         }
     }

     // Individual invoice status badge update (with timeout)
     function updateInvoiceStatusBadge(poId) {
         const badge = $(`.invoice-status[data-po-id="${poId}"]`);
         
         if (!badge.length) {
             return;
         }
         
         // First check if there are local files for this PO
         const hasLocalFiles = window.uploadedInvoiceFiles && 
                              window.uploadedInvoiceFiles[poId] && 
                              window.uploadedInvoiceFiles[poId].length > 0;
         
         if (hasLocalFiles) {
             badge.removeClass('bg-secondary bg-danger').addClass('bg-success');
             badge.html('<i class="fas fa-check me-1"></i>Invoice Uploaded');
             return;
         }
         
         // For individual checks, use a shorter timeout
         const timeoutId = setTimeout(() => {
             badge.removeClass('bg-secondary bg-danger').addClass('bg-danger');
             badge.html('<i class="fas fa-exclamation-triangle me-1"></i>Invoice Required');
         }, 5000); // 5 second timeout instead of waiting indefinitely
         
         // Then check server for uploaded invoices
         checkPOInvoiceStatus(poId, function(hasInvoices) {
             clearTimeout(timeoutId);
             if (hasInvoices) {
                 badge.removeClass('bg-secondary bg-danger').addClass('bg-success');
                 badge.html('<i class="fas fa-check me-1"></i>Invoice Uploaded');
             } else {
                 badge.removeClass('bg-secondary bg-success').addClass('bg-danger');
                 badge.html('<i class="fas fa-exclamation-triangle me-1"></i>Invoice Required');
             }
         });
     }

    function checkBulkApprovalInvoices(selectedIds, callback) {
        let checkedCount = 0;
        let missingInvoices = [];
        
        selectedIds.forEach(poId => {
            checkPOInvoiceStatus(poId, function(hasInvoices) {
                checkedCount++;
                
                if (!hasInvoices) {
                    // Get PO details for display
                    $.ajax({
                        url: `{{ url('company/warehouse/po-approval/details') }}/${poId}`,
                        method: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success && response.data) {
                                missingInvoices.push(response.data.po_number || `PO-${poId}`);
                            } else {
                                missingInvoices.push(`PO-${poId}`);
                            }
                        },
                        error: function() {
                            missingInvoices.push(`PO-${poId}`);
                        }
                    });
                }
                
                if (checkedCount === selectedIds.length) {
                    callback(missingInvoices.length === 0, missingInvoices);
                }
            });
        });
    }

    function bulkApprove(poIds, requisitionIds, comments = '') {
        console.log('Bulk approve called with:', { poIds, requisitionIds, comments });
        
        // Build processing message
        let processingMessage = 'Processing...';
        const items = [];
        if (poIds.length > 0) {
            items.push(`${poIds.length} purchase order(s)`);
        }
        if (requisitionIds.length > 0) {
            items.push(`${requisitionIds.length} requisition(s)`);
        }
        processingMessage = `Approving ${items.join(' and ')}...`;
        
        Swal.fire({
            title: 'Processing...',
            text: processingMessage,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '{{ route("po_approval.bulk_approve") }}',
            method: 'POST',
            data: {
                po_ids: poIds,
                requisition_ids: requisitionIds,
                comments: comments,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Bulk approve success response:', response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    });
                    loadApprovalData(1);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Unknown error occurred'
                    });
                }
            },
            error: function(xhr) {
                console.log('Bulk approve error response:', xhr);
                let errorMessage = 'Failed to approve purchase orders';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 0) {
                    errorMessage = 'Network error: Unable to connect to server';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error: Please try again later';
                } else if (xhr.status === 422) {
                    errorMessage = 'Validation error: Please check your input';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
    }

    function loadSupplierOptions(data) {
        const supplierFilter = $('#supplierFilter');
        const existingOptions = supplierFilter.find('option').length;
        
        if (existingOptions > 1) return; // Already populated
        
        const suppliers = new Set();
        data.forEach(item => {
            if (item.supplier && item.supplier.company_name) {
                suppliers.add(item.supplier.company_name);
            }
        });
        
        suppliers.forEach(supplier => {
            supplierFilter.append(`<option value="${supplier}">${supplier}</option>`);
        });
    }

    function filterApprovals() {
        // Reset to first page when filtering
        loadApprovalData(1);
    }

    // Function to populate attachments for requisitions
    function populateAttachments(attachments) {
        const attachmentsList = $('#attachmentsList');
        
        if (!attachments || attachments.length === 0) {
            attachmentsList.html('<p class="text-muted mb-0">No attachments found</p>');
            return;
        }
        
        let attachmentsHtml = '';
        attachments.forEach(function(attachment, index) {
            const fileName = attachment.split('/').pop(); // Get filename from path
            const fileExtension = fileName.split('.').pop().toLowerCase();
            let iconClass = 'fa-file';
            
            // Set appropriate icon based on file extension
            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'].includes(fileExtension)) {
                iconClass = 'fa-file-image text-success';
            } else if (fileExtension === 'pdf') {
                iconClass = 'fa-file-pdf text-danger';
            } else if (['doc', 'docx'].includes(fileExtension)) {
                iconClass = 'fa-file-word text-primary';
            } else if (['xls', 'xlsx'].includes(fileExtension)) {
                iconClass = 'fa-file-excel text-success';
            } else if (['zip', 'rar', '7z'].includes(fileExtension)) {
                iconClass = 'fa-file-archive text-warning';
            }
            
            attachmentsHtml += `
                <div class="d-flex align-items-center mb-2 p-2 border rounded">
                    <i class="fas ${iconClass} me-3"></i>
                    <div class="flex-grow-1">
                        <div class="fw-medium">${fileName}</div>
                        <small class="text-muted">Attachment ${index + 1}</small>
                    </div>
                    <a href="/storage/${attachment}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download me-1"></i>Download
                    </a>
                </div>
            `;
        });
        
        attachmentsList.html(attachmentsHtml);
    }

    // Function to show simple confirmation for requisition approval
    function showRequisitionApprovalConfirmation(id) {
        Swal.fire({
            title: 'Approve Requisition?',
            text: 'This will approve the requisition and deduct inventory. Are you sure?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                // Directly approve the requisition without complex modal
                approveItem(id, '', 'normal', '', 'requisition');
            }
        });
    }

    // Function to show simple confirmation for requisition rejection
    function showRequisitionRejectionConfirmation(id) {
        Swal.fire({
            title: 'Reject Requisition?',
            text: 'This will reject the requisition. Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Reject',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                // Directly reject the requisition
                rejectItem(id, 'Rejected by approver', 'requisition');
            }
        });
    }

    // Make functions globally accessible for external calls
    window.loadApprovalData = loadApprovalData;
    window.initializeApprovalTab = initializeApprovalTab;
    window.initializeApprovalHandlers = initializeApprovalHandlers;

    // Global watcher for debugging
    $(document).ready(function() {
        console.log('Document ready - invoice upload system initialized');
        
        // Watch for modal events
        $('#invoiceUploadModal').on('show.bs.modal', function() {
            console.log('🔍 Invoice upload modal opened');
        });
        
        $('#invoiceUploadModal').on('hide.bs.modal', function() {
            console.log('🔍 Invoice upload modal closed');
        });
        
        // Add a global watcher to detect if files are being cleared
        setInterval(function() {
            if (window.uploadedInvoiceFiles) {
                Object.keys(window.uploadedInvoiceFiles).forEach(approvalId => {
                    const files = window.uploadedInvoiceFiles[approvalId];
                    if (files && files.length > 0) {
                        console.log(`Watcher: Approval ${approvalId} has ${files.length} files`);
                    }
                });
            }
        }, 2000);
    });

</script>
