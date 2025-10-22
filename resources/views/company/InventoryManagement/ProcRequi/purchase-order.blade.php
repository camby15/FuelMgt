
{{-- @push('css')
    @vite([
        'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
        'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'
    ]) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-hover: #3a56d4;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 0.375rem;
            --box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        .po-container {
            background-color: #f5f7fb;
            min-height: 100%;
            padding: 1.5rem;
            border-radius: 0.5rem;
        }

        /* Tax Configuration Styling */
        #taxConfiguration {
            min-width: 100%;
            font-size: 0.95rem;
        }
        
        #taxConfigLoading {
            z-index: 10;
        }
        
        .tax-config-section {
            transition: all 0.3s ease;
        }

        .po-tabs {
            background: #fff;
            border-radius: 0.5rem 0.5rem 0 0;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
            margin-bottom: 0;
            padding: 0.5rem 1rem 0;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-bottom: none;
            overflow-x: auto;
            white-space: nowrap;
            display: flex;
            flex-wrap: nowrap;
        }

        .po-tabs .nav-link {
            color: #6c757d;
            font-weight: 500;
            padding: 0.85rem 1.5rem;
            border: none;
            position: relative;
            transition: var(--transition);
            border-radius: 0.5rem 0.5rem 0 0;
            margin-right: 0.25rem;
            background: transparent;
            display: inline-flex;
            align-items: center;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }

        .po-tabs .nav-link.active {
            color: var(--primary-color);
            background: #fff;
            box-shadow: 0 -0.25rem 0.5rem rgba(0, 0, 0, 0.03);
            transform: translateY(1px);
            border-left: 1px solid rgba(0, 0, 0, 0.05);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            border-top: 2px solid var(--primary-color);
        }

        .po-tabs .nav-link i {
            margin-right: 0.5rem;
            font-size: 1rem;
            width: 1.25rem;
            text-align: center;
        }

        .po-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 0 0 0.5rem 0.5rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 1.5rem;
            background: #fff;
            transition: var(--transition);
        }

        .po-card .card-header {
            background: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-icon {
            width: 2rem;
            height: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 50%;
        }

        .btn-sm i {
            font-size: 0.875rem;
        }

        .po-status {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        .status-draft { background-color: #f0f4f8; color: #4a5568; border-left: 3px solid #a0aec0; }
        .status-created { background-color: #e6fffa; color: #234e52; border-left: 3px solid #38b2ac; }
        .status-pending { background-color: #fffaf0; color: #c05621; border-left: 3px solid #ed8936; }
        .status-approved { background-color: #f0fff4; color: #2f855a; border-left: 3px solid #48bb78; }
        .status-completed { background-color: #ebf8ff; color: #2b6cb0; border-left: 3px solid #4299e1; }
        .status-processing { background-color: #fffaf0; color: #c05621; border-left: 3px solid #ed8936; }
        .status-received { background-color: #f0fff4; color: #2f855a; border-left: 3px solid #48bb78; }
        .status-rejected { background-color: #fff5f5; color: #c53030; border-left: 3px solid #f56565; }

        .po-item {
            transition: var(--transition);
            border-left: 3px solid var(--primary-color);
            background: #fff;
            margin-bottom: 0.5rem;
            border-radius: 0.25rem;
            overflow: hidden;
        }

        .po-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), #3a56d4);
            color: white;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
            transition: var(--transition);
        }

        .btn-close:hover {
            opacity: 1;
        }

        .modal-footer {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Form Styles */
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #4a5568;
            font-size: 0.875rem;
        }

        .form-control, .form-select, .form-control:focus, .form-select:focus {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 0.5rem 0.875rem;
            font-size: 0.875rem;
            transition: var(--transition);
            box-shadow: none;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
        }

        /* Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8fafc;
            color: #4a5568;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f1f5f9;
            color: #4a5568;
        }

        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Badge Styles */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            border-radius: 0.25rem;
        }

        /* Button Styles */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn i {
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover, .btn-primary:focus {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        /* Re-order Fields Styling */
        .reorder-fields {
            background-color: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #e9ecef;
        }
        
        .reorder-fields input,
        .reorder-fields textarea {
            font-size: 0.85rem;
        }
        
        .reorder-fields textarea {
            resize: vertical;
            min-height: 50px;
        }
        
        .reorder-checkbox {
            cursor: pointer;
        }
        
        /* Batch number validation styles */
        .batch-number-input.loading {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Cpath fill='%234361ee' d='M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z' opacity='.25'/%3E%3Cpath fill='%234361ee' d='M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z'%3E%3CanimateTransform attributeName='transform' type='rotate' dur='0.75s' values='0 12 12;360 12 12' repeatCount='indefinite'/%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 16px;
            padding-right: 30px;
        }
        
        .batch-validation-message {
            margin-top: 4px;
            font-size: 0.75rem;
        }
        
        .batch-number-input.is-valid {
            border-color: #28a745;
            background-color: #f0fff4;
        }
        
        .batch-number-input.is-invalid {
            border-color: #dc3545;
            background-color: #fff5f5;
        }
        
        /* Locked item name styling */
        input[name="item_name[]"][readonly] {
            background-color: #e9ecef !important;
            cursor: not-allowed;
            opacity: 0.8;
        }
        
        /* Item name validation */
        .item-name-validation-message {
            margin-top: 4px;
            font-size: 0.75rem;
        }
        
        .item-name-input.is-invalid {
            border-color: #dc3545;
            background-color: #fff5f5;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .po-container {
                padding: 1rem;
            }
            
            .po-tabs .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.8rem;
            }
            
            .btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }
</style>
{{-- @endpush --}}
<div class="po-container fade-in">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h4 mb-1 text-gray-800 fw-bold">Purchase Order Management</h1>
                    <p class="text-muted mb-0">Create, track, and manage all your purchase orders in one place</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importPOModal">
                        <i class="fas fa-upload me-2"></i>Import
                    </button>
                    <button class="btn btn-primary createPOModal" data-bs-toggle="modal" data-bs-target="#createPOModal">
                        <i class="fas fa-plus me-2"></i>Create PO
                    </button>
                </div>
            </div>
        </div>
    </div>



<!-- Tabs Navigation -->
<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs po-tabs" id="poTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-po-tab" data-bs-toggle="tab" 
                        data-bs-target="#all-po" type="button" role="tab" aria-controls="all-po" 
                        aria-selected="true">
                    <i class="fas fa-list"></i> All POs
                    <span class="badge bg-primary rounded-pill ms-1" id="all-po-count">0</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="draft-tab" data-bs-toggle="tab" 
                        data-bs-target="#draft" type="button" role="tab" 
                        aria-controls="draft" aria-selected="false">
                    <i class="fas fa-file-alt"></i> Draft
                    <span class="badge bg-secondary rounded-pill ms-1" id="draft-count">0</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="created-tab" data-bs-toggle="tab" 
                        data-bs-target="#created" type="button" role="tab" 
                        aria-controls="created" aria-selected="false">
                    <i class="fas fa-plus-circle"></i> Created
                    <span class="badge bg-info rounded-pill ms-1" id="created-count">0</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="external-approval-tab" data-bs-toggle="tab" 
                        data-bs-target="#external-approval" type="button" role="tab" 
                        aria-controls="external-approval" aria-selected="false">
                    <i class="fas fa-user-check"></i> External Approval
                    <span class="badge bg-primary rounded-pill ms-1" id="external-approval-count">0</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pending-approval-tab" data-bs-toggle="tab" 
                        data-bs-target="#pending-approval" type="button" role="tab" 
                        aria-controls="pending-approval" aria-selected="false">
                    <i class="fas fa-clock"></i> Pending
                    <span class="badge bg-warning text-dark rounded-pill ms-1" id="pending-count">0</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="received-tab" data-bs-toggle="tab" 
                        data-bs-target="#received" type="button" role="tab" 
                        aria-controls="received" aria-selected="false">
                    <i class="fas fa-box-open"></i> Received
                    <span class="badge bg-success rounded-pill ms-1" id="received-count">0</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="processing-tab" data-bs-toggle="tab" 
                        data-bs-target="#processing" type="button" role="tab" 
                        aria-controls="processing" aria-selected="false">
                    <i class="fas fa-cogs"></i> Processing
                    <span class="badge bg-warning text-dark rounded-pill ms-1" id="processing-count">0</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="approved-tab" data-bs-toggle="tab" 
                        data-bs-target="#approved" type="button" role="tab" 
                        aria-controls="approved" aria-selected="false">
                    <i class="fas fa-check-circle"></i> Approved
                    <span class="badge bg-success rounded-pill ms-1" id="approved-count">0</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" 
                        data-bs-target="#completed" type="button" role="tab" 
                        aria-controls="completed" aria-selected="false">
                    <i class="fas fa-check-double"></i> Completed
                    <span class="badge bg-info rounded-pill ms-1" id="completed-count">0</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" 
                        data-bs-target="#rejected" type="button" role="tab" 
                        aria-controls="rejected" aria-selected="false">
                    <i class="fas fa-times-circle"></i> Rejected
                    <span class="badge bg-danger rounded-pill ms-1" id="rejected-count">0</span>
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- Tab Content -->
<div class="tab-content bg-white rounded-bottom p-3 border-top-0" id="poTabsContent">
    <!-- All POs Tab -->
    <div class="tab-pane fade show active" id="all-po" role="tabpanel" aria-labelledby="all-po-tab">
        <!-- Search and filter controls -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2 align-items-center">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Search POs..." id="searchPO">
                </div>
                <!-- Filter dropdown remains the same -->
            </div>
            <!-- Action buttons remain the same -->
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="allPoTable">
                <thead>
                    <tr>
                        <!-- Table headers remain the same -->
                    </tr>
                </thead>
                <tbody id="poTableBody">
                    <!-- Data will be loaded here via JavaScript -->
                </tbody>
            </table>
        </div>
         
    </div>

    <!-- Other tabs (Draft, Created, Pending, etc.) will use the same structure but with different IDs -->
    <div class="tab-pane fade" id="draft" role="tabpanel" aria-labelledby="draft-tab">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <!-- Same headers as all-po tab -->
                </tr>
            </thead>
            <tbody id="draftTableBody">
                <!-- Draft POs will be loaded here -->
            </tbody>
        </table>
    </div>
    
</div>

<div class="tab-pane fade" id="created" role="tabpanel" aria-labelledby="created-tab">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <!-- Same headers as all-po tab -->
                </tr>
            </thead>
            <tbody id="createdTableBody">
                <!-- Created POs will be loaded here -->
            </tbody>
        </table>
    </div>
    
</div>

<div class="tab-pane fade" id="external-approval" role="tabpanel" aria-labelledby="external-approval-tab">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <!-- Same headers as all-po tab -->
                </tr>
            </thead>
            <tbody id="externalApprovalTableBody">
                <!-- External Approval POs will be loaded here -->
            </tbody>
        </table>
    </div>
    
</div>

<div class="tab-pane fade" id="pending-approval" role="tabpanel" aria-labelledby="pending-approval-tab">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <!-- Same headers as all-po tab -->
                </tr>
            </thead>
            <tbody id="pendingApprovalTableBody">
                <!-- Pending approval POs will be loaded here -->
            </tbody>
        </table>
    </div>

   
</div>

<div class="tab-pane fade" id="received" role="tabpanel" aria-labelledby="received-tab">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <!-- Same headers as all-po tab -->
                </tr>
            </thead>
            <tbody id="receivedTableBody">
                <!-- Received POs will be loaded here -->
            </tbody>
        </table>
    </div>
</div>

<div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="processing-tab">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <!-- Same headers as all-po tab -->
                </tr>
            </thead>
            <tbody id="processingTableBody">
                <!-- Processing POs will be loaded here -->
            </tbody>
        </table>
    </div>
</div>

<div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <!-- Same headers as all-po tab -->
                </tr>
            </thead>
            <tbody id="approvedTableBody">
                <!-- Approved POs will be loaded here -->
            </tbody>
        </table>
    </div>
   
</div>

<div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <!-- Same headers as all-po tab -->
                </tr>
            </thead>
            <tbody id="completedTableBody">
                <!-- Completed POs will be loaded here -->
            </tbody>
        </table>
    </div>
    
</div>

<div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <!-- Same headers as all-po tab -->
                </tr>
            </thead>
            <tbody id="rejectedTableBody">
                <!-- Rejected POs will be loaded here -->
            </tbody>
        </table>
    </div>
</div>

 <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted" id="paginationInfo">
                Showing <span class="fw-semibold">0</span> to <span class="fw-semibold">0</span> of <span class="fw-semibold">0</span> entries
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0" id="poPagination">
                    <!-- Pagination will be loaded here via JavaScript -->
                </ul>
            </nav>
        </div>
    
   
</div>
</div>



<!-- Create PO Modal -->
<div class="modal fade" id="createPOModal" tabindex="-1" aria-labelledby="createPOModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPOModalLabel">Create New Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createPOForm">
                    <div class="row mb-3">
                           <div class="col-md-6">
                            <label class="form-label">PO Number</label>
                            <input type="text" class="form-control" name="po_number" id="po_number" placeholder="Enter PO Number" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="vendor" class="form-label">Suppliers</label>
                            <select class="form-select" id="supplierSelect" name="supplier_id" required>
                             
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="poDate" class="form-label">PO Date</label>
                            <input type="date" class="form-control" id="poDate" name="po_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="requestedBy" class="form-label">Requested By</label>
                                                            <input type="text" class="form-control" id="requestedBy" name="requested_by" value="{{ Auth::user()->fullname ?? '' }}" readonly>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="expectedDelivery" class="form-label">Expected Delivery Date</label>
                            <input type="date" class="form-control" id="expectedDelivery" name="expected_delivery" required>
                        </div>
                        <div class="col-md-6">
                            <label for="paymentTerms" class="form-label">Payment Terms</label>
                            <select class="form-select" id="paymentTerms">
                                <option value="cod">Cash on Delivery</option>
                                <option value="7days">7 Days</option>
                                <option value="14days">14 Days</option>
                                <option value="30days">30 Days</option>
                                <option value="50_50">50% Advance, 50% on Delivery</option>
                                <option value="30_70">30% Advance, 70% on Delivery</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="po_notes" rows="2"></textarea>
                    </div>
                    
                    <!-- Tax Configuration Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-calculator me-2"></i>Tax Configuration
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="taxExempt" class="form-label">Tax Exemption</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="taxExempt" name="is_tax_exempt" value="1">
                                        <input type="hidden" name="is_tax_exempt" value="0">
                                        <label class="form-check-label" for="taxExempt">
                                            Mark as Tax Exempt
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="taxMethod" class="form-label">Tax Method</label>
                                    <select class="form-select" id="taxMethod" name="tax_method">
                                        <option value="select" selected>Select from Configurations</option>
                                        <option value="manual">Enter Manual Rate</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Tax Configuration Selection -->
                            <div class="row mb-3" id="taxConfigurationSection">
                                <div class="col-md-8">
                                    <label for="taxConfiguration" class="form-label">Tax Configuration</label>
                                    <div class="position-relative">
                                        <select class="form-select" id="taxConfiguration" name="tax_configuration_id" style="min-width: 100%;">
                                            <option value="">Loading configurations...</option>
                                        </select>
                                        <div id="taxConfigLoading" class="position-absolute top-50 end-0 translate-middle-y me-3">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="taxType" class="form-label">Tax Type</label>
                                    <select class="form-select" id="taxType" name="tax_type">
                                        <option value="standard">Standard</option>
                                        <option value="flat_rate">Flat Rate</option>
                                        <option value="custom">Custom Rate</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Manual Tax Rate Input -->
                            <div class="row mb-3" id="manualTaxSection" style="display: none;">
                                <div class="col-md-6">
                                    <label for="customTaxRateInput" class="form-label">Tax Rate (%)</label>
                                    <input type="number" class="form-control" id="customTaxRateInput" name="tax_rate" min="0" max="100" step="0.01" placeholder="Enter tax rate" value="21.90">
                                </div>
                                <div class="col-md-6">
                                    <label for="manualTaxType" class="form-label">Tax Type</label>
                                    <select class="form-select" id="manualTaxType" name="tax_type">
                                        <option value="standard">Standard</option>
                                        <option value="flat_rate">Flat Rate</option>
                                        <option value="custom">Custom Rate</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-3" id="taxExemptionReason" style="display: none;">
                                <div class="col-12">
                                    <label for="exemptionReason" class="form-label">Exemption Reason</label>
                                    <textarea class="form-control" id="exemptionReason" name="tax_exemption_reason" rows="2" placeholder="Enter reason for tax exemption"></textarea>
                                </div>
                            </div>
                            
                            <div class="alert alert-info" id="taxInfo" style="display: none;">
                                <i class="fas fa-info-circle me-2"></i>
                                <span id="taxInfoText"></span>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="mt-4 mb-3">Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="poItemsTable">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>Re-order</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="poItemsBody">
                                <!-- Items will be added here dynamically -->
                                <tr class="item-row">
                                    <td><input type="text" class="form-control item-name-input" name="item_name[]" onblur="validateItemName(this)"></td>
                                    <td><input type="text" class="form-control" name="items[0][description]"></td>
                                    <td><input type="number" class="form-control quantity" name="item_qty[]" min="1" value="1" required></td>
                                    <td><input type="number" step="0.01" class="form-control price" name="item_price[]" min="0" value="0" required></td>
                                    <td><input type="text" class="form-control total" name="item_total[]" value="0.00" readonly></td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input reorder-checkbox" onchange="toggleReorderFields(this)">
                                        </div>
                                        <div class="reorder-fields" style="display: none; margin-top: 10px;">
                                            <input type="text" class="form-control form-control-sm mb-2 batch-number-input" placeholder="Batch Number" name="batch_number[]" onblur="validateBatchNumber(this)">
                                            <textarea class="form-control form-control-sm" placeholder="Reason for re-order" rows="2" name="reorder_reason[]"></textarea>
                                        </div>
                                    </td>
                                    <td><button type="button" class="btn btn-sm btn-danger "></button></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn" onclick="addItemRow()">
                                            <i class="fas fa-plus me-1"></i> Add Item 
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                    <td colspan="2" id="po_subtotal">GHS 0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Tax:</td>
                                    <td colspan="2" id="po_tax">GHS 0.00</td>
                                </tr>
                                <tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total:</td>
                                    <td colspan="2" id="po_total" class="fw-bold">GHS 0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                     <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveAsDraftBtn" data-action="draft">
                    <i class="fas fa-save me-1"></i> Save as Draft
                </button>
                <button type="submit" class="btn btn-success" id="submitForApprovalBtn" data-action="created">
                    <i class="fas fa-paper-plane me-1"></i> Submit for Approval
                </button>
            </div>
                </form>
            </div>
           
        </div>
    </div>
</div>




<!-- View PO Modal -->
<div class="modal fade" id="viewPOModal" tabindex="-1" aria-labelledby="viewPOModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewPOModalLabel">Purchase Order Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="printPOBtn">
                    <i class="fas fa-print"></i> Print
                </button>
                <button type="button" class="btn btn-info" id="emailPOBtn">
                    <i class="fas fa-envelope"></i> Email
                </button>
            </div>
        </div>
    </div>
</div>




<!-- Edit PO Modal -->
<div class="modal fade" id="editPOModal" tabindex="-1" aria-labelledby="editPOModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editPOModalLabel">Edit Purchase Order</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="editPOModalBody">
                <!-- Loading state will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="editPOSaveBtn">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<!-- Add these in your <head> section -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>


function toggleReorderFields(checkbox) {
    const row = $(checkbox).closest('tr');
    const reorderFields = row.find('.reorder-fields');
    const itemNameInput = row.find('input[name="item_name[]"]');
    
    if (checkbox.checked) {
        reorderFields.slideDown(200);
    } else {
        reorderFields.slideUp(200);
        // Clear the fields when unchecked
        reorderFields.find('input, textarea').val('');
        // Remove validation classes
        reorderFields.find('input').removeClass('is-valid is-invalid');
        row.find('.batch-validation-message').remove();
        
        // Unlock item name field
        itemNameInput.prop('readonly', false).removeClass('bg-light');
    }
}

// Check for duplicate batch numbers in the table
function checkDuplicateBatchNumber(currentInput, batchNumber) {
    let isDuplicate = false;
    let duplicateRow = null;
    
    $('.batch-number-input').each(function() {
        if (this !== currentInput && $(this).val().trim().toUpperCase() === batchNumber.toUpperCase()) {
            isDuplicate = true;
            duplicateRow = $(this).closest('tr').index() + 1;
            return false; // break loop
        }
    });
    
    return { isDuplicate, duplicateRow };
}

// Check for duplicate item names in the table
function checkDuplicateItemName(currentInput, itemName) {
    let isDuplicate = false;
    let duplicateRow = null;
    
    $('input[name="item_name[]"]').each(function() {
        if (this !== currentInput && $(this).val().trim().toLowerCase() === itemName.toLowerCase()) {
            isDuplicate = true;
            duplicateRow = $(this).closest('tr').index() + 1;
            return false; // break loop
        }
    });
    
    return { isDuplicate, duplicateRow };
}

// Validate item name for duplicates
function validateItemName(itemNameInput) {
    if (!itemNameInput || !$(itemNameInput).length) {
        console.log('Invalid item name input element');
        return;
    }
    
    const itemName = $(itemNameInput).val() ? $(itemNameInput).val().trim() : '';
    const row = $(itemNameInput).closest('tr');
    
    // Clear previous validation messages
    $(itemNameInput).removeClass('is-invalid');
    row.find('.item-name-validation-message').remove();
    
    if (!itemName) {
        return;
    }
    
    // Skip validation if field is readonly (locked by batch number)
    if ($(itemNameInput).prop('readonly')) {
        return;
    }
    
    // Check for duplicate item name
    const duplicateCheck = checkDuplicateItemName(itemNameInput, itemName);
    if (duplicateCheck.isDuplicate) {
        $(itemNameInput).addClass('is-invalid');
        $(itemNameInput).after(`
            <div class="item-name-validation-message text-danger small mt-1">
                <i class="fas fa-exclamation-triangle"></i> 
                This item is already added in row ${duplicateCheck.duplicateRow}
            </div>
        `);
        
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Item',
            text: `"${itemName}" is already added in row ${duplicateCheck.duplicateRow}. Each item can only be added once per PO.`,
            confirmButtonText: 'OK'
        }).then(() => {
            $(itemNameInput).val(''); // Clear the duplicate
            $(itemNameInput).focus();
        });
        return;
    }
}

// Validate batch number
function validateBatchNumber(batchInput) {
    if (!batchInput || !$(batchInput).length) {
        console.log('Invalid batch input element');
        return;
    }
    
    const row = $(batchInput).closest('tr');
    const batchNumber = $(batchInput).val() ? $(batchInput).val().trim() : '';
    const itemNameInput = row.find('input[name="item_name[]"]').length ? 
        row.find('input[name="item_name[]"]') : row.find('.item-name, .item-name-input');
    const itemName = itemNameInput.val() ? itemNameInput.val().trim() : '';
    
    // Clear previous validation
    $(batchInput).removeClass('is-valid is-invalid');
    row.find('.batch-validation-message').remove();
    
    if (!batchNumber) {
        // If batch number is cleared, unlock the item name field
        itemNameInput.prop('readonly', false).removeClass('bg-light');
        return;
    }
    
    // Check for duplicate batch number in the table
    const duplicateCheck = checkDuplicateBatchNumber(batchInput, batchNumber);
    if (duplicateCheck.isDuplicate) {
        $(batchInput).addClass('is-invalid');
        $(batchInput).after(`
            <div class="batch-validation-message text-danger small mt-1">
                <i class="fas fa-exclamation-triangle"></i> 
                This batch number is already used in row ${duplicateCheck.duplicateRow}
            </div>
        `);
        $(batchInput).val(''); // Clear the duplicate
        
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Batch Number',
            text: `Batch number "${batchNumber}" is already used in row ${duplicateCheck.duplicateRow}. Each batch number can only be used once per PO.`,
            confirmButtonText: 'OK'
        });
        return;
    }
    
    // Show loading
    $(batchInput).addClass('loading');
    
    $.ajax({
        url: '/company/warehouse/purchasing_order/validate-batch-number',
        method: 'POST',
        data: {
            batch_number: batchNumber,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $(batchInput).removeClass('loading');
            
            if (response.success && response.exists) {
                // Check if item name was already entered
                if (itemName && response.item_name.toLowerCase().trim() !== itemName.toLowerCase().trim()) {
                    // Item name doesn't match - ask user which one is correct
                    Swal.fire({
                        title: 'Item Name Mismatch',
                        html: `
                            <div class="text-start">
                                <p class="mb-3">The batch number <strong>${batchNumber}</strong> belongs to:</p>
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-box"></i> <strong>${response.item_name}</strong>
                                </div>
                                <p class="mb-2">But you entered:</p>
                                <div class="alert alert-warning mb-3">
                                    <i class="fas fa-edit"></i> <strong>${itemName}</strong>
                                </div>
                                <p class="text-muted small">Which item name would you like to use?</p>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        showDenyButton: true,
                        confirmButtonText: `Use "${response.item_name}"`,
                        denyButtonText: `Keep "${itemName}"`,
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#28a745',
                        denyButtonColor: '#dc3545'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Use the batch's original name
                            itemNameInput.val(response.item_name);
                            itemNameInput.prop('readonly', true).addClass('bg-light');
                            $(batchInput).addClass('is-valid');
                            $(batchInput).after(`
                                <div class="batch-validation-message text-success small mt-1">
                                    <i class="fas fa-check-circle"></i> 
                                    Valid batch number for "${response.item_name}"
                                </div>
                            `);
                        } else if (result.isDenied) {
                            // User wants to keep their name - clear the batch number
                            $(batchInput).val('');
                            $(batchInput).addClass('is-invalid');
                            Swal.fire({
                                icon: 'error',
                                title: 'Batch Number Removed',
                                text: 'The batch number has been cleared because it does not match the item name you entered.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        } else {
                            // Cancelled - clear batch number
                            $(batchInput).val('');
                        }
                    });
                } else {
                    // Item name matches or was empty
                    $(batchInput).addClass('is-valid');
                    $(batchInput).after(`
                        <div class="batch-validation-message text-success small mt-1">
                            <i class="fas fa-check-circle"></i> 
                            Valid batch number for "${response.item_name}"
                        </div>
                    `);
                    
                    // Auto-fill and lock item name
                    itemNameInput.val(response.item_name);
                    itemNameInput.prop('readonly', true).addClass('bg-light');
                    
                    // Store original item name as data attribute
                    itemNameInput.data('original-name', response.item_name);
                }
            } else {
                $(batchInput).addClass('is-invalid');
                $(batchInput).after(`
                    <div class="batch-validation-message text-danger small mt-1">
                        <i class="fas fa-times-circle"></i> 
                        ${response.message || 'Batch number not found'}
                    </div>
                `);
            }
        },
        error: function(xhr) {
            $(batchInput).removeClass('loading');
            $(batchInput).addClass('is-invalid');
            $(batchInput).after(`
                <div class="batch-validation-message text-danger small mt-1">
                    <i class="fas fa-times-circle"></i> 
                    Error validating batch number
                </div>
            `);
        }
    });
}

function addItemRow() {
    console.log("Adding new item row");
    
    const newRow = `
    <tr class="item-row">
        <td>
            <input type="text" class="form-control item-name-input" name="item_name[]" placeholder="Enter item name" onblur="validateItemName(this)" required>
        </td>
        <td>
            <input type="text" class="form-control" name="items[0][description]" placeholder="Item description">
        </td>
        <td>
            <input type="number" class="form-control quantity" name="item_qty[]" min="1" value="1" oninput="calculateTotal(this)" required>
        </td>
        <td>
            <input type="number" step="0.01" class="form-control price" name="item_price[]" min="0" value="0" oninput="calculateTotal(this)" required>
        </td>
        <td>
            <input type="text" class="form-control total" readonly name="item_total[]" value="0.00">
        </td>
        <td>
            <div class="form-check">
                <input type="checkbox" class="form-check-input reorder-checkbox" onchange="toggleReorderFields(this)">
            </div>
            <div class="reorder-fields" style="display: none; margin-top: 10px;">
                <input type="text" class="form-control form-control-sm mb-2 batch-number-input" placeholder="Batch Number" name="batch_number[]" onblur="validateBatchNumber(this)">
                <textarea class="form-control form-control-sm" placeholder="Reason for re-order" rows="2" name="reorder_reason[]"></textarea>
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>`;
    $('#poItemsTable tbody').append(newRow);
     calculateTotals();
    
}

function calculateTotal(input) {
    // Find the parent row
    const row = $(input).closest('tr');
    
    // Get the values using the new input name format
    const quantity = parseFloat(row.find('input[name^="items["][name$="][quantity]"]').val()) || 0;
    const unitPrice = parseFloat(row.find('input[name^="items["][name$="][unit_price]"]').val()) || 0;
    const total = quantity * unitPrice;
    
    // Update the total field
    row.find('input[name^="items["][name$="][total]"]').val(total.toFixed(2));
    
    // Update the grand totals
    updateGrandTotals();
}
 calculateTotals();
function calculateTotals() {

    // console.log("Calculating totals...");
    let subtotal = 0;
    
    // Loop through all item rows
    $('#poItemsTable tbody tr').each(function() {
        // Get quantity and price values
        const quantity = parseFloat($(this).find('input[name="item_qty[]"]').val()) || 0;
        const price = parseFloat($(this).find('input[name="item_price[]"]').val()) || 0;
        
        // Calculate row total
        const rowTotal = quantity * price;
        subtotal += rowTotal;
        
        // Update the row's total field
        $(this).find('input[name="item_total[]"]').val(rowTotal.toFixed(2));
    });
    
    // Calculate tax based on configuration
    calculateTaxAmounts(subtotal);
}

function calculateTaxAmounts(subtotal) {
    console.log('calculateTaxAmounts called with subtotal:', subtotal);
    const isTaxExempt = $('#taxExempt').is(':checked');
    const taxMethod = $('#taxMethod').val();
    console.log('Tax settings:', { isTaxExempt, taxMethod });
    
    let taxAmount = 0;
    let totalAmount = subtotal;
    
    if (isTaxExempt) {
        // No tax calculation needed
        taxAmount = 0;
        totalAmount = subtotal;
    } else if (taxMethod === 'manual') {
        // Use manual tax rate
        const customTaxRate = parseFloat($('#customTaxRateInput').val()) || 0;
        console.log('Manual tax rate:', customTaxRate);
        if (customTaxRate > 0) {
            taxAmount = subtotal * (customTaxRate / 100);
            totalAmount = subtotal + taxAmount;
            console.log('Manual calculation result:', { taxAmount, totalAmount });
        }
    } else if (taxMethod === 'select') {
        // Use tax configuration
        const taxConfigurationId = $('#taxConfiguration').val();
        console.log('Using tax configuration:', taxConfigurationId);
        if (taxConfigurationId) {
            $.ajax({
                url: '/company/warehouse/purchasing_order/calculate-tax',
                method: 'POST',
                data: {
                    subtotal: subtotal,
                    tax_configuration_id: taxConfigurationId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Tax calculation response:', response);
                    if (response.success) {
                        const data = response.data;
                        updateTaxDisplay(subtotal, data.tax_amount, data.total_amount);
                    }
                },
                error: function(xhr) {
                    console.error('Tax calculation error:', xhr);
                    // Fallback to manual calculation
                    const customTaxRate = 21.90; // Default rate
                    taxAmount = subtotal * (customTaxRate / 100);
                    totalAmount = subtotal + taxAmount;
                    updateTaxDisplay(subtotal, taxAmount, totalAmount);
                }
            });
            return; // Exit early as AJAX will handle the display
        } else {
            console.log('No tax configuration selected, using default calculation');
            // No configuration selected, use default calculation
            const defaultRate = 21.90;
            taxAmount = subtotal * (defaultRate / 100);
            totalAmount = subtotal + taxAmount;
        }
    }
    
    updateTaxDisplay(subtotal, taxAmount, totalAmount);
}

function calculateItemByItemTax(subtotal) {
    let taxableAmount = 0;
    let taxAmount = 0;
    
    // Calculate tax item by item
    $('#poItemsTable tbody tr').each(function() {
        const itemTotal = parseFloat($(this).find('input[name="item_total[]"]').val()) || 0;
        const category = $(this).find('select[name="item_category[]"]').val();
        
        // Check if item is exempt based on category
        const isExempt = isItemExempt(category);
        
        if (!isExempt && itemTotal > 0) {
            taxableAmount += itemTotal;
        }
    });
    
    // Apply tax only to taxable amount
    if (taxableAmount > 0) {
        taxAmount = taxableAmount * 0.219; // 21.9% tax
    }
    
    const totalAmount = subtotal + taxAmount;
    updateTaxDisplay(subtotal, taxAmount, totalAmount);
}

function isItemExempt(category) {
    // Items that are exempt from tax
    const exemptCategories = ['food', 'medicine', 'education', 'exempt'];
    return exemptCategories.includes(category);
}

function showItemTaxBreakdown(itemExemptions) {
    // Create a breakdown display
    let breakdownHtml = '<div class="mt-2"><small class="text-muted">Tax Breakdown:</small><ul class="list-unstyled small">';
    
    $('#poItemsTable tbody tr').each(function(index) {
        const itemName = $(this).find('input[name="item_name[]"]').val();
        const itemTotal = parseFloat($(this).find('input[name="item_total[]"]').val()) || 0;
        const category = $(this).find('select[name="item_category[]"]').val();
        
        if (itemName && itemTotal > 0) {
            const isExempt = isItemExempt(category);
            const status = isExempt ? '<span class="text-success">EXEMPT</span>' : '<span class="text-primary">TAXABLE</span>';
            breakdownHtml += `<li>${itemName} (${category}): ${status}</li>`;
        }
    });
    
    breakdownHtml += '</ul></div>';
    
    // Show or update breakdown
    let breakdownDiv = $('#taxBreakdown');
    if (breakdownDiv.length === 0) {
        $('#taxInfo').after('<div id="taxBreakdown"></div>');
        breakdownDiv = $('#taxBreakdown');
    }
    breakdownDiv.html(breakdownHtml);
}

// Removed getItemsData function - no longer needed

function updateTaxDisplay(subtotal, taxAmount, totalAmount) {
    console.log('updateTaxDisplay called with:', { subtotal, taxAmount, totalAmount });
    
    // Update the summary fields
    $('#po_subtotal').text('GHS ' + subtotal.toFixed(2));
    $('#po_tax').text('GHS ' + taxAmount.toFixed(2));
    $('#po_total').text('GHS ' + totalAmount.toFixed(2));
    
    console.log('Tax display updated');
}

$(document).on('input', 'input[name="item_qty[]"], input[name="item_price[]"]', function() {
    calculateTotals();
});

// Update tax status when category changes
$(document).on('change', 'select[name="item_category[]"]', function() {
    const row = $(this).closest('tr');
    const category = $(this).val();
    const isExempt = isItemExempt(category);
    
    // Add visual indicator
    if (isExempt) {
        row.addClass('table-success').removeClass('table-warning');
        row.find('td').css('background-color', '#d1e7dd');
    } else {
        row.removeClass('table-success table-warning');
        row.find('td').css('background-color', '');
    }
    
    calculateTotals();
});

// Tax configuration event handlers
$(document).on('change', '#taxMethod', function() {
    const method = $(this).val();
    console.log('Tax method changed to:', method);
    if (method === 'select') {
        $('#taxConfigurationSection').show();
        $('#manualTaxSection').hide();
        $('#taxInfo').hide();
        // Show loading state
        $('#taxConfiguration').html('<option value="">Loading configurations...</option>').prop('disabled', true);
        loadTaxConfigurations();
    } else if (method === 'manual') {
        $('#taxConfigurationSection').hide();
        $('#manualTaxSection').show();
        $('#taxInfo').hide();
    }
    // Force tax calculation after method change
    setTimeout(() => {
        calculateTotals();
    }, 100);
});

$(document).on('change', '#taxConfiguration', function() {
    const selectedId = $(this).val();
    console.log('Tax configuration changed to:', selectedId);
    if (selectedId) {
        $('#taxInfo').show();
        loadTaxConfigurationInfo(selectedId);
    } else {
        $('#taxInfo').hide();
    }
    // Force tax calculation after configuration change
    setTimeout(() => {
        calculateTotals();
    }, 100);
});

$(document).on('change', '#taxExempt', function() {
    const isChecked = $(this).is(':checked');
    console.log('Tax exempt changed to:', isChecked);
    if (isChecked) {
        $('#taxExemptionReason').show();
    } else {
        $('#taxExemptionReason').hide();
    }
    // Force tax calculation after exempt change
    setTimeout(() => {
        calculateTotals();
    }, 100);
});

$(document).on('input', '#customTaxRateInput', function() {
    console.log('Manual tax rate changed to:', $(this).val());
    calculateTotals();
});

// Load tax configurations
function loadTaxConfigurations() {
    // Show loading indicator immediately
    $('#taxConfigLoading').show();
    $('#taxConfiguration').prop('disabled', true);
    
    $.ajax({
        url: '/company/warehouse/purchasing_order/tax-configurations',
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                const select = $('#taxConfiguration');
                select.empty().append('<option value="">Select Tax Configuration</option>');
                
                response.data.forEach(function(config) {
                    select.append(`<option value="${config.id}" data-rate="${config.rate}" data-type="${config.type}">${config.name} (${config.rate}%)</option>`);
                });
            }
        },
        error: function() {
            console.error('Failed to load tax configurations');
            // Show error message
            const select = $('#taxConfiguration');
            select.empty().append('<option value="">Error loading configurations</option>');
        },
        complete: function() {
            // Hide loading indicator
            $('#taxConfigLoading').hide();
            $('#taxConfiguration').prop('disabled', false);
        }
    });
}

function loadTaxConfigurationInfo(configId) {
    const option = $(`#taxConfiguration option[value="${configId}"]`);
    const rate = option.data('rate');
    const type = option.data('type');
    
    let infoText = `Tax Rate: ${rate}%`;
    if (type === 'standard') {
        infoText += ' (Ghana Standard VAT)';
    } else if (type === 'flat_rate') {
        infoText += ' (Ghana Flat Rate)';
    } else if (type === 'exempt') {
        infoText = 'Tax Exempt';
    }
    
    $('#taxInfoText').text(infoText);
}

function updateGrandTotals() {
    let totalItems = 0;
    let totalValue = 0;
    
    $('#itemsTable tbody tr').each(function() {
        const quantity = parseFloat($(this).find('input[name^="items["][name$="][quantity]"]').val()) || 0;
        const total = parseFloat($(this).find('input[name^="items["][name$="][total]"]').val()) || 0;
        
        totalItems += quantity;
        totalValue += total;
    });
    
    // Update your summary fields if you have them
    $('#totalItems').text(totalItems);
    $('#totalValue').text(totalValue.toFixed(2));
}


function removeRow(button) {
    $(button).closest('tr').remove();
     calculateTotals();
}

function calculateTotal(input) {
    const row = input.closest('tr');
    const qty = parseFloat(row.querySelector('[name="item_qty[]"]').value) || 0;
    const price = parseFloat(row.querySelector('[name="item_price[]"]').value) || 0;
    const total = qty * price;
    row.querySelector('[name="item_total[]"]').value = total.toFixed(2);
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // You can add additional event handlers here for the modals
    document.querySelectorAll('.view-order, .edit-order, .delete-order').forEach(button => {
        button.addEventListener('click', function() {
            const poId = this.getAttribute('data-id');
            // You can load specific PO data here based on poId
            console.log(`Action for PO ID: ${poId}`);
        });
    });
});




// Global flag to prevent multiple alerts
if (!window.isAlertShowing) {
    window.isAlertShowing = false;
}

function showSafeSweetAlert(options) {
    if (!window.isAlertShowing) {
        window.isAlertShowing = true;
        return Swal.fire(options).finally(() => {
            window.isAlertShowing = false;
        });
    } else {
        // Return a resolved promise if alert is already showing
        return Promise.resolve();
    }
}

// Currency formatter
window.CurrencyHelper = {
    format: function(amount) {
        if (!amount) return 'GH₵0.00';
        const formatted = parseFloat(amount).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        return 'GH₵' + formatted;
    }
};

// Comprehensive modal cleanup functions
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

// Document ready
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Load initial data
    fetchPurchaseOrders();
    loadSuppliers();
    
    // Modal cleanup event handlers
    $('#createPOModal').on('hidden.bs.modal', function() {
        console.log('Create PO modal hidden, cleaning up...');
        cleanupModalBackdrops();
    });
    
    // Global modal cleanup function
    function cleanupModalBackdrops() {
        setTimeout(() => {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css('padding-right', '');
        }, 100);
    }
    
    // Force cleanup on window load to fix any existing modal issues
    $(window).on('load', function() {
        console.log('Window loaded, cleaning up any existing modal issues...');
        forceCleanupAllModals();
    });
    
    // Add global function to fix scrolling issues
    window.fixPageScrolling = function() {
        console.log('Fixing page scrolling...');
        forceCleanupAllModals();
        
        // Additional cleanup for scroll issues
        $('body').css({
            'overflow': 'auto',
            'position': 'static',
            'top': 'auto',
            'left': 'auto',
            'right': 'auto',
            'bottom': 'auto'
        });
        $('html').css({
            'overflow': 'auto',
            'position': 'static',
            'top': 'auto',
            'left': 'auto',
            'right': 'auto',
            'bottom': 'auto'
        });
        
        console.log('Page scrolling fixed');
    };
    
    // Add keyboard shortcut to fix scrolling (Ctrl + Shift + S)
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'S') {
            e.preventDefault();
            console.log('Keyboard shortcut triggered: Fixing page scrolling...');
            window.fixPageScrolling();
        }
    });
    
    // Print functionality for PO view modal
    $('#printPOBtn').on('click', function() {
        console.log('Print button clicked');
        
        // Get the current PO data from the modal
        const poData = window.currentViewPOData;
        if (!poData) {
            console.error('No PO data available for printing');
            alert('No PO data available for printing');
            return;
        }
        
        // Create print window
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        
        // Generate print content
        const printContent = generatePrintContent(poData);
        
        // Write content to print window
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Trigger print after content loads
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    });
    
    // Function to generate print content
    function generatePrintContent(po) {
        const currentDate = new Date().toLocaleDateString();
        const companyName = '{{ Auth::user()->company->company_name ?? "Company Name" }}';
        const companyLogo = '{{ asset("images/gesl_logo.png") }}';
        
        // Calculate totals
        let subtotal = 0;
        let itemsHtml = '';
        
        if (Array.isArray(po.items) && po.items.length > 0) {
            po.items.forEach(item => {
                const itemTotal = parseFloat(item.total_price) || 0;
                subtotal += itemTotal;
                
                itemsHtml += `
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">${item.name || 'N/A'}</td>
                        <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${item.quantity || 0}</td>
                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">GHS ${(parseFloat(item.unit_price) || 0).toFixed(2)}</td>
                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">GHS ${itemTotal.toFixed(2)}</td>
                    </tr>
                `;
            });
        } else {
            itemsHtml = '<tr><td colspan="4" style="padding: 20px; text-align: center; border: 1px solid #ddd;">No items found</td></tr>';
        }
        
        const taxAmount = parseFloat(po.tax_amount) || 0;
        const totalAmount = parseFloat(po.total_amount) || subtotal;
        const taxRate = parseFloat(po.tax_rate) || 0;
        const isTaxExempt = po.is_tax_exempt || false;
        
        return generatePrintHTML(po, subtotal, itemsHtml, taxAmount, totalAmount, taxRate, isTaxExempt, companyLogo);
    }
    
    // Function to generate the complete print HTML
    function generatePrintHTML(po, subtotal, itemsHtml, taxAmount, totalAmount, taxRate, isTaxExempt, companyLogo) {
        return `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Purchase Order - ${po.po_number}</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.4; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
                .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #007bff; }
                .company-info { flex: 1; }
                .company-logo { width: 120px; height: 120px; margin-right: 20px; display: inline-block; }
                .company-logo img { max-width: 100%; max-height: 100%; object-fit: contain; border: 1px solid #ddd; }
                .po-info { text-align: right; flex: 1; }
                .po-number { font-size: 24px; font-weight: bold; color: #007bff; margin-bottom: 10px; }
                .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                .items-table th { background-color: #007bff; color: white; padding: 12px 8px; text-align: left; font-weight: bold; }
                .items-table td { padding: 8px; border: 1px solid #ddd; }
                .totals-section { margin-top: 20px; text-align: right; }
                .totals-table { width: 350px; margin-left: auto; border-collapse: collapse; }
                .totals-table td { padding: 8px 12px; border: 1px solid #ddd; }
                .totals-table .label { background-color: #f8f9fa; font-weight: bold; text-align: right; }
                .totals-table .total-row { background-color: #e9ecef; font-weight: bold; font-size: 16px; }
                .signature-section { margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
                .signature-box { text-align: center; padding: 20px; border: 1px solid #dee2e6; min-height: 80px; }
                .status-badge { padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
                .status-created { background-color: #d1ecf1; color: #0c5460; }
                .status-pending { background-color: #fff3cd; color: #856404; }
                .status-approved { background-color: #d4edda; color: #155724; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="company-info">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div class="company-logo">
                            <img src="${companyLogo}" alt="Company Logo" />
                        </div>
                    </div>
                </div>
                <div class="po-info">
                    <div class="po-number">PURCHASE ORDER</div>
                    <div style="font-size: 18px; font-weight: bold;">${po.po_number}</div>
                    <div style="color: #6c757d; margin-top: 5px;">
                        Date: ${new Date(po.order_date).toLocaleDateString()}<br>
                        Status: <span class="status-badge status-${po.status}">${po.status.toUpperCase()}</span>
                    </div>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <h3>Vendor: ${po.supplier ? po.supplier.company_name : 'N/A'}</h3>
                <p>Contact: ${po.supplier ? po.supplier.primary_contact : 'N/A'}</p>
                <p>Email: ${po.supplier ? po.supplier.email : 'N/A'}</p>
            </div>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Item Description</th>
                        <th style="width: 15%; text-align: center;">Quantity</th>
                        <th style="width: 20%; text-align: right;">Unit Price</th>
                        <th style="width: 25%; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHtml}
                </tbody>
            </table>
            
            <div class="totals-section">
                <table class="totals-table">
                    <tr>
                        <td class="label">Subtotal:</td>
                        <td>GHS ${subtotal.toFixed(2)}</td>
                    </tr>
                    ${isTaxExempt ? `
                    <tr>
                        <td class="label">Tax Status:</td>
                        <td style="color: #dc3545; font-weight: bold;">TAX EXEMPT</td>
                    </tr>
                    ` : ''}
                    ${!isTaxExempt && taxAmount > 0 ? `
                    <tr>
                        <td class="label">Tax (${taxRate}%):</td>
                        <td>GHS ${taxAmount.toFixed(2)}</td>
                    </tr>
                    ` : ''}
                    <tr class="total-row">
                        <td class="label">Total Amount:</td>
                        <td>GHS ${totalAmount.toFixed(2)}</td>
                    </tr>
                </table>
            </div>
            
            <div class="signature-section">
                <div class="signature-box">
                    <div style="font-weight: bold; margin-bottom: 20px;">Authorized Signature</div>
                    <div style="border-bottom: 1px solid #333; margin-bottom: 5px;"></div>
                    <div style="font-size: 12px; color: #6c757d;">Date: _______________</div>
                </div>
                <div class="signature-box">
                    <div style="font-weight: bold; margin-bottom: 20px;">Vendor Signature</div>
                    <div style="border-bottom: 1px solid #333; margin-bottom: 5px;"></div>
                    <div style="font-size: 12px; color: #6c757d;">Date: _______________</div>
                </div>
            </div>
        </body>
        </html>
        `;
    }
});


let clickedAction = null;

$('#createPOForm button[type="submit"]').on('click', function () {
    clickedAction = $(this).data('action');
});

$('#createPOForm').on('submit', function(e) {
    e.preventDefault();
    
    // Check for invalid batch numbers
    const invalidBatches = $('.batch-number-input.is-invalid:visible');
    if (invalidBatches.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Batch Numbers',
            html: 'Please fix the invalid batch numbers before submitting the PO.<br>Invalid batch numbers are highlighted in red.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    // Check for invalid item names
    const invalidItems = $('.item-name-input.is-invalid:visible');
    if (invalidItems.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Items',
            html: 'Please fix the duplicate items before submitting the PO.<br>Duplicate items are highlighted in red.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    // Check for duplicate batch numbers one final time
    const batchNumbers = [];
    let duplicateBatchFound = false;
    $('.batch-number-input:visible').each(function() {
        const batch = $(this).val().trim().toUpperCase();
        if (batch && batchNumbers.includes(batch)) {
            duplicateBatchFound = true;
            $(this).addClass('is-invalid');
        } else if (batch) {
            batchNumbers.push(batch);
        }
    });
    
    if (duplicateBatchFound) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Batch Numbers',
            text: 'Each batch number can only be used once per PO. Please remove duplicates.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    // Check for duplicate item names one final time
    const itemNames = [];
    let duplicateItemFound = false;
    $('.item-name-input:visible').each(function() {
        const itemName = $(this).val().trim().toLowerCase();
        if (itemName && !$(this).prop('readonly')) {
            if (itemNames.includes(itemName)) {
                duplicateItemFound = true;
                $(this).addClass('is-invalid');
            } else {
                itemNames.push(itemName);
            }
        }
    });
    
    if (duplicateItemFound) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Items',
            text: 'Each item can only be added once per PO. Please remove duplicates.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    // Check for reorder items without batch numbers
    let missingBatchNumbers = false;
    $('.reorder-checkbox:checked').each(function() {
        const row = $(this).closest('tr');
        const batchNumber = row.find('.batch-number-input').val().trim();
        if (!batchNumber) {
            missingBatchNumbers = true;
            row.find('.batch-number-input').addClass('is-invalid');
        }
    });
    
    if (missingBatchNumbers) {
        Swal.fire({
            icon: 'error',
            title: 'Missing Batch Numbers',
            text: 'Please enter batch numbers for all re-order items.',
            confirmButtonText: 'OK'
        });
        return false;
    }
 
        

       let formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        po_number: $('#po_number').val(),
        supplier_id: $('#supplierSelect').val(),
        order_date: $('#poDate').val(),
        expected_delivery: $('#expectedDelivery').val(),
        payment_terms: $('#paymentTerms').val(),
        notes: $('#po_notes').val(),
        status: clickedAction,
        // Tax configuration fields
        tax_method: $('#taxMethod').val(),
        tax_configuration_id: $('#taxConfiguration').val(),
        tax_type: $('#taxMethod').val() === 'manual' ? $('#manualTaxType').val() : $('#taxType').val(),
        tax_rate: $('#customTaxRateInput').val(),
        is_tax_exempt: $('#taxExempt').is(':checked') ? true : false,
        tax_exemption_reason: $('#exemptionReason').val(),
        items: [],
        total_items: 0,
        total_value: 0
    };
    

    

    // Process items correctly
    $('[name="item_name[]"]').each(function(index) {
        const row = $(this).closest('tr');
        const isReorder = row.find('.reorder-checkbox').is(':checked');
        
        const item = {
            name: $(this).val(),
            quantity: $('[name="item_qty[]"]').eq(index).val(),
            unit_price: $('[name="item_price[]"]').eq(index).val(),
            total_price: $('[name="item_total[]"]').eq(index).val(),
            category: 'general',
            is_reorder: isReorder ? 1 : 0,
            batch_number: isReorder ? ($('[name="batch_number[]"]').eq(index).val() || null) : null,
            reorder_reason: isReorder ? ($('[name="reorder_reason[]"]').eq(index).val() || null) : null
        };
        
        // Only add if name exists (skip empty rows)
        if (item.name) {
            formData.items.push(item);
            formData.total_items += parseInt(item.quantity) || 0;
            formData.total_value += parseFloat(item.total_price) || 0;
        }
    });
    
    console.log("Final form data:", formData);
    console.log("Items with re-order data:", formData.items);
    

    
    $.ajax({
        url: '/company/warehouse/purchasing_order/',
        method: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $('#createPOForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        },
        success: function(response) {
            console.log('PO created successfully, showing alert...');
            
            // Show the success alert
            const alertPromise = showSafeSweetAlert({
                icon: 'success',
                title: 'Success!',
                text: response.message || 'Purchase order created successfully.',
                confirmButtonText: 'OK'
            });
            
                            // Function to close modal
                function closeModal() {
                    console.log('Attempting to close modal...');
                    
                    try {
                        const modalElement = document.getElementById('createPOModal');
                        console.log('Modal element found:', modalElement);
                        
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            console.log('Bootstrap modal instance:', modal);
                            
                            if (modal) {
                                console.log('Closing modal using Bootstrap 5 instance');
                                modal.hide();
                            } else {
                                console.log('No Bootstrap 5 instance found, trying jQuery');
                                $('#createPOModal').modal('hide');
                            }
                        } else {
                            console.log('Modal element not found');
                        }
                    } catch (error) {
                        console.error('Error closing modal:', error);
                        // Fallback
                        $('#createPOModal').modal('hide');
                    }
                    
                    // Use comprehensive cleanup after modal is closed
                    setTimeout(() => {
                        cleanupModalBackdrops();
                    }, 300);
                }
            
            // Try to close modal after alert is confirmed
            alertPromise.then((result) => {
                console.log('SweetAlert confirmed, closing modal...');
                closeModal();
            }).catch((error) => {
                console.error('SweetAlert error:', error);
                closeModal();
            });
            
            // Fallback: close modal after 3 seconds regardless
            setTimeout(() => {
                console.log('Fallback: closing modal after timeout');
                closeModal();
            }, 3000);
            
            // Reset form and refresh data
            $('#createPOForm')[0].reset();
            
            // Refresh the current active tab
            const activeTab = $('.po-tabs .nav-link.active').attr('id');
            if (activeTab) {
                loadTabContent(activeTab);
            } else {
                // Fallback to all-po tab
                loadTabContent('all-po');
            }
            
            // Also refresh badge counts by making a quick API call
            $.ajax({
                url: '/company/warehouse/purchasing_order/all',
                method: 'POST',
                data: { page: 1, per_page: 1 },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(badgeResponse) {
                    updateBadgeCounts(badgeResponse, true);
                }
            });
        },
        error: function(xhr) {
            console.log('PO Creation Error:', xhr);
            console.log('Response JSON:', xhr.responseJSON);
            console.log('Response Text:', xhr.responseText);
            
            let errorMessage = 'Failed to create purchase order. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                console.log('Validation errors:', xhr.responseJSON.errors);
                errorMessage = '<strong>Validation Errors:</strong><br>' + Object.values(xhr.responseJSON.errors).join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                html: errorMessage,
                confirmButtonText: 'OK'
            });
        },
        complete: function() {
            $('#createPOForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save PO');
        }
    });
});






















// Initialize the page
$(document).ready(function() {
    console.log("Document ready, initializing PO system...");
    
    // Initialize Bootstrap tabs
    const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabEls.forEach(tabEl => {
        tabEl.addEventListener('shown.bs.tab', function(e) {
            const targetTab = e.target.getAttribute('aria-controls');
            console.log("Tab changed to:", targetTab);
            loadTabContent(targetTab);
        });
    });
    
    // Handle tab changes to reset pagination
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const targetTab = $(e.target).attr('data-bs-target');
        console.log('Tab changed to:', targetTab);
        console.log('Tab button clicked:', $(e.target).attr('id'));
        
        // Reset pagination to page 1 when switching tabs
        const activeTab = targetTab.replace('#', '');
        const searchQuery = $('#searchPO').val() || '';
        const startDate = $('#startDateFilter').val() || '';
        const endDate = $('#endDateFilter').val() || '';
        let status = '';
        let targetTableBody = '#poTableBody';
        
        switch(activeTab) {
            case 'all-po':
                status = '';
                targetTableBody = '#poTableBody';
                break;
            case 'draft': 
                status = 'draft';
                targetTableBody = '#draftTableBody';
                break;
            case 'created': 
                status = 'created';
                targetTableBody = '#createdTableBody';
                break;
            case 'external-approval': 
                status = 'external_approval';
                targetTableBody = '#externalApprovalTableBody';
                break;
            case 'pending-approval': 
                status = 'pending';
                targetTableBody = '#pendingApprovalTableBody';
                break;
            case 'received': 
                status = 'received';
                targetTableBody = '#receivedTableBody';
                break;
            case 'processing': 
                status = 'processing';
                targetTableBody = '#processingTableBody';
                break;
            case 'approved': 
                status = 'approved';
                targetTableBody = '#approvedTableBody';
                break;
            case 'completed': 
                status = 'completed';
                targetTableBody = '#completedTableBody';
                break;
            case 'rejected': 
                status = 'rejected';
                targetTableBody = '#rejectedTableBody';
                break;
            default:
                status = '';
                targetTableBody = '#poTableBody';
        }
        
        console.log('Tab switch debug:', {
            activeTab: activeTab,
            status: status,
            targetTableBody: targetTableBody
        });
        
        // Fetch data for the new tab starting from page 1
        fetchPurchaseOrders(1, startDate, endDate, searchQuery, status, targetTableBody);
    });
    
    // Load initial data for the active tab
    loadTabContent('all-po');
    
    // Initialize search with debounce
    $('#searchPO').on('keyup', debounce(function() {
        const searchQuery = $(this).val();
        const activeTab = $('.tab-pane.active').attr('id') || 'all-po';
        loadTabContent(activeTab, searchQuery);
    }, 300));
});

// Enhanced loadTabContent function
function loadTabContent(tabId, searchQuery = '') {
    console.log(`Loading tab ${tabId} with search: ${searchQuery}`);
    
    let status = '';
    let targetTableBody = '#poTableBody'; // Default to all-po table
    
    switch(tabId) {
        case 'all-po': 
            status = ''; // Show all statuses
            break;
        case 'draft': 
            status = 'draft';
            targetTableBody = '#draftTableBody';
            break;
        case 'created': 
            status = 'created';
            targetTableBody = '#createdTableBody';
            break;
        case 'external-approval': 
            status = 'external_approval';
            targetTableBody = '#externalApprovalTableBody';
            break;
        case 'pending-approval': 
            status = 'pending';
            targetTableBody = '#pendingApprovalTableBody';
            break;
        case 'approved': 
            status = 'approved';
            targetTableBody = '#approvedTableBody';
            break;
        case 'completed': 
            status = 'completed';
            targetTableBody = '#completedTableBody';
            break;
        case 'processing': 
            status = 'processing';
            targetTableBody = '#processingTableBody';
            break;
        case 'received': 
            status = 'received';
            targetTableBody = '#receivedTableBody';
            break;
        case 'rejected': 
            status = 'rejected';
            targetTableBody = '#rejectedTableBody';
            break;
    }
    
    fetchPurchaseOrders(1, '', '', searchQuery, status, targetTableBody);
}

// Enhanced fetchPurchaseOrders function
let isLoading = false;
function fetchPurchaseOrders(page = 1, startDate = '', endDate = '', searchQuery = '', status = '', targetTableBody = '#poTableBody') {
    // Prevent duplicate calls
    if (isLoading) {
        console.log("Already loading, skipping duplicate call");
        return;
    }
    
    console.log("Fetching purchase orders with params:", {page, startDate, endDate, searchQuery, status});
    console.log("Status debug:", {
        status: status,
        statusType: typeof status,
        statusLength: status ? status.length : 0,
        statusEmpty: !status || status === '',
        targetTableBody: targetTableBody
    });
    
    isLoading = true;
    
    // Show loading state
    $(targetTableBody).html('<tr><td colspan="8" class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</td></tr>');
    
    $.ajax({
        url: '/company/warehouse/purchasing_order/all',
        method: 'POST',
        data: {
            page: page,
            start_date: startDate,
            end_date: endDate,
            search: searchQuery,
            status: status
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            isLoading = false; // Reset loading state
            
            console.log("API response received:", response);
            console.log("Response type:", typeof response);
            console.log("Response keys:", Object.keys(response || {}));
            console.log("Has meta:", !!(response && response.meta));
            if (response && response.meta) {
                console.log("Meta keys:", Object.keys(response.meta));
                console.log("Meta values:", response.meta);
            }
            
            if (!response) {
                showError(targetTableBody, "Empty response from server");
                return;
            }
            renderPurchaseOrders(response, targetTableBody);
            // Only update badge counts for All PO tab (no status filter)
            updateBadgeCounts(response, targetTableBody === '#poTableBody');
            renderPagination(response, targetTableBody);
        },
        error: function(xhr) {
            isLoading = false; // Reset loading state
            console.error("AJAX error:", xhr.responseJSON);
            showError(targetTableBody, xhr.responseJSON?.message || "Error loading data");
        }
    });
}

// Enhanced renderPurchaseOrders function
function renderPurchaseOrders(response, targetTableBody) {
    console.log(`Rendering to ${targetTableBody}`, response);
    console.log("Data check:", {
        hasData: !!(response && response.data),
        dataLength: response && response.data ? response.data.length : 0,
        dataType: response && response.data ? typeof response.data : 'undefined'
    });
    console.log("Target table body element:", $(targetTableBody));
    console.log("Target table body exists:", $(targetTableBody).length > 0);
    
    // Debug tab and visibility state
    console.log("Tab and visibility debug:", {
        activeTab: $('.po-tabs .nav-link.active').attr('id'),
        activeTabPane: $('.tab-pane.active').attr('id'),
        allPoTabPane: $('#all-po').hasClass('show active'),
        allPoTableBody: $('#poTableBody').is(':visible'),
        allPoTable: $('#all-po table').is(':visible'),
        allPoTabContent: $('#all-po').is(':visible'),
        targetTableBody: targetTableBody,
        targetElementVisible: $(targetTableBody).is(':visible'),
        targetElementParentVisible: $(targetTableBody).parent().is(':visible')
    });
    
    let tableBody = '';

      const data = response.data || response.meta?.data || [];
    
    if (!response?.data?.length) {
        tableBody = `<tr>
            <td colspan="8" class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                </div>
                <h5 class="fw-semibold mb-2">No purchase orders found</h5>
                <p class="text-muted">There are no purchase orders to display</p>
            </td>
        </tr>`;
    } else {
        response.data.forEach(po => {
             try {
                // Debug: Check items structure for imported POs
                if (po.po_number && po.po_number.includes('PO-20250924')) {
                    console.log(`Debug PO ${po.po_number}:`, {
                        hasItems: !!po.items,
                        itemsType: typeof po.items,
                        isArray: Array.isArray(po.items),
                        itemsLength: po.items ? po.items.length : 'N/A',
                        totalAmount: po.total_amount,
                        items: po.items
                    });
                }
                // Calculate total amount
                let totalAmount = 0;
                if (po.items && Array.isArray(po.items) && po.items.length > 0) {
                    totalAmount = po.items.reduce((sum, item) => {
                        return sum + (parseFloat(item.total_price) || 0);
                    }, 0);
                } else if (po.total_amount) {
                    // For imported POs, use the stored total_amount
                    totalAmount = parseFloat(po.total_amount) || 0;
                }
                
                // Format dates
                const formatDate = (dateString) => {
                    if (!dateString) return 'N/A';
                    try {
                        const date = new Date(dateString);
                        return isNaN(date) ? 'N/A' : date.toLocaleDateString();
                    } catch (e) {
                        return 'N/A';
                    }
                };
                
                const orderDate = formatDate(po.order_date);
                const deliveryDate = formatDate(po.delivery_date);
                
                // Status badge
                let statusBadge = '';
                const status = po.status ? po.status.toLowerCase() : '';
                switch(status) {
                    case 'approved':
                        statusBadge = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Approved</span>';
                        break;
                    case 'created':
                        statusBadge = '<span class="badge bg-info"><i class="fas fa-plus-circle me-1"></i> Created</span>';
                        break;
                    case 'pending':
                        statusBadge = '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>';
                        break;
                    case 'draft':
                        statusBadge = '<span class="badge bg-secondary"><i class="fas fa-file-alt me-1"></i> Draft</span>';
                        break;
                    case 'completed':
                        statusBadge = '<span class="badge bg-primary"><i class="fas fa-check-double me-1"></i> Completed</span>';
                        break;
                    case 'processing':
                        statusBadge = '<span class="badge bg-warning text-dark"><i class="fas fa-cogs me-1"></i> Processing</span>';
                        break;
                    case 'received':
                        statusBadge = '<span class="badge bg-success"><i class="fas fa-box-open me-1"></i> Received</span>';
                        break;
                    case 'rejected':
                        statusBadge = '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Rejected</span>';
                        break;
                    default:
                        statusBadge = `<span class="badge bg-secondary">${po.status || 'Unknown'}</span>`;
                }
                
                // Add reorder indicator if it's a reorder
                if (po.is_reorder) {
                    statusBadge += ' <span class="badge bg-warning text-dark ms-1"><i class="fas fa-redo me-1"></i> Reorder</span>';
                }
                
                tableBody += `<tr class="po-item" data-po-id="${po.id}">
                   
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-2 me-2">
                                <i class="fas fa-file-invoice text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">${po.po_number || 'N/A'}</div>
                                <small class="text-muted">#${po.id || ''}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(po.supplier?.company_name || 'V')}&background=4e73df&color=fff&size=32" 
                                 class="rounded-circle me-2" width="24" height="24" alt="Vendor">
                            <span>${po.supplier?.company_name || 'Vendor not specified'}</span>
                        </div>
                    </td>
                    <td>${orderDate}</td>
                    <td>${deliveryDate}</td>
                    <td class="fw-semibold">GHS ${totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td>${statusBadge}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary view-po" data-po-id="${po.id}" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm ${po.status && !['created', 'draft'].includes(po.status.toLowerCase()) ? 'btn-outline-secondary disabled' : 'btn-outline-secondary'} edit-po" 
                                    data-po-id="${po.id}" 
                                    data-po-status="${po.status || 'created'}" 
                                    title="${po.status && !['created', 'draft'].includes(po.status.toLowerCase()) ? 'Cannot edit - Status: ' + po.status : 'Edit'}"
                                    ${po.status && !['created', 'draft'].includes(po.status.toLowerCase()) ? 'disabled' : ''}>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm ${po.status && !['created', 'draft'].includes(po.status.toLowerCase()) ? 'btn-outline-secondary disabled' : 'btn-outline-danger'} delete-po" 
                                    data-po-id="${po.id}" 
                                    data-po-status="${po.status || 'created'}" 
                                    title="${po.status && !['created', 'draft'].includes(po.status.toLowerCase()) ? 'Cannot delete - Status: ' + po.status : 'Delete'}"
                                    ${po.status && !['created', 'draft'].includes(po.status.toLowerCase()) ? 'disabled' : ''}>
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            } catch (error) {
                console.error("Error rendering PO:", po, error);
                // Add a fallback row for failed POs
                tableBody += `
                    <tr class="po-item" data-po-id="${po.id}">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-2">
                                    <i class="fas fa-file-invoice text-warning"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">${po.po_number || 'Unknown'}</div>
                                    <small class="text-muted">#${po.id}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="text-muted">Error loading supplier</span>
                            </div>
                        </td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td class="fw-semibold text-muted">Error</td>
                        <td><span class="badge bg-warning"><i class="fas fa-exclamation-triangle me-1"></i> Error</span></td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary disabled" disabled>
                                <i class="fas fa-exclamation-triangle"></i>
                            </button>
                        </td>
                    </tr>`;
            }
        });
        
    }
    
    // Update the table body
    $(targetTableBody).html(tableBody);
    console.log("Table body content set:", $(targetTableBody).html()); // Debug log
    console.log("Table body element after update:", $(targetTableBody));
    console.log("Table body children count:", $(targetTableBody).children().length);
    
    // Additional debugging for visibility issues
    console.log("Table body parent visibility:", $(targetTableBody).parent().is(':visible'));
    console.log("Table body parent display:", $(targetTableBody).parent().css('display'));
    console.log("Table body visibility:", $(targetTableBody).is(':visible'));
    console.log("Table body display:", $(targetTableBody).css('display'));
    console.log("Active tab pane:", $('.tab-pane.active').attr('id'));
    console.log("Table body parent classes:", $(targetTableBody).parent().attr('class'));
    
    // Force visibility check with delay
    setTimeout(() => {
        console.log("Delayed visibility check:", {
            tableBodyVisible: $(targetTableBody).is(':visible'),
            tableBodyChildren: $(targetTableBody).children().length,
            tableBodyParentVisible: $(targetTableBody).parent().is(':visible'),
            activeTabPane: $('.tab-pane.active').attr('id'),
            allPoTabActive: $('#all-po').hasClass('show active'),
            allPoTabVisible: $('#all-po').is(':visible')
        });
        
        // If table body is not visible, try to force it visible
        if (!$(targetTableBody).is(':visible') && targetTableBody === '#poTableBody') {
            console.log("Table body not visible, attempting to fix...");
            $(targetTableBody).css('display', 'table-row-group');
            $(targetTableBody).parent().css('display', 'table');
            $('#all-po').addClass('show active');
            console.log("Forced visibility - checking again:", {
                tableBodyVisible: $(targetTableBody).is(':visible'),
                tableBodyChildren: $(targetTableBody).children().length
            });
        }
    }, 100);
    
    // Force visibility check
    setTimeout(() => {
        console.log("Delayed check - Table body visible:", $(targetTableBody).is(':visible'));
        console.log("Delayed check - Table body children:", $(targetTableBody).children().length);
        console.log("Delayed check - First row visible:", $(targetTableBody).children().first().is(':visible'));
        console.log("Delayed check - Table body content still there:", $(targetTableBody).html().length > 0);
        
        // Check if content was modified
        const currentContent = $(targetTableBody).html();
        if (currentContent !== tableBody) {
            console.warn("Table content was modified after insertion!");
            console.log("Original content length:", tableBody.length);
            console.log("Current content length:", currentContent.length);
        }
    }, 100);
    
    // Initialize event listeners for this tab's buttons
    initializeEventListeners(targetTableBody);
}


function updateBadgeCounts(response, isFullCounts = false) {
    // Only update badge counts if this is a full counts response (from All PO tab or initial load)
    if (!isFullCounts) {
        console.log("Skipping badge count update - not a full counts response");
        return;
    }
    
    // Check if we have the meta object with status counts
    if (response.meta && typeof response.meta.draft_count !== 'undefined') {
        console.log("Updating badge counts with custom meta data:", response.meta);
        
        $('#all-po-count').text(response.meta.total || 0);
        $('#draft-count').text(response.meta.draft_count || 0);
        $('#created-count').text(response.meta.created_count || 0);
        $('#external-approval-count').text(response.meta.external_approval_count || 0);
        $('#pending-count').text(response.meta.pending_count || 0);
        $('#approved-count').text(response.meta.approved_count || 0);
        $('#completed-count').text(response.meta.completed_count || 0);
        $('#rejected-count').text(response.meta.rejected_count || 0);
    }
    // Fallback for standard Laravel pagination response
    else if (response.total !== undefined) {
        console.log("Updating badge counts with standard pagination data");
        
        // For standard pagination, we only have the total count
        $('#all-po-count').text(response.total || 0);
        
        // Set all status-specific counts to 0 since we don't have this data
        $('#draft-count').text(0);
        $('#created-count').text(0);
        $('#external-approval-count').text(0);
        $('#pending-count').text(0);
        $('#approved-count').text(0);
        $('#completed-count').text(0);
        $('#rejected-count').text(0);
    }
    else {
        console.warn("Unknown response format for badge counts:", response);
        
        // Fallback to all zeros if we can't understand the response
        $('#all-po-count').text(0);
        $('#draft-count').text(0);
        $('#created-count').text(0);
        $('#external-approval-count').text(0);
        $('#pending-count').text(0);
        $('#approved-count').text(0);
        $('#completed-count').text(0);
        $('#rejected-count').text(0);
    }
}



function renderPagination(response, targetTableBody = '#poTableBody') {
    console.log("renderPagination called with response:", response);
    console.log("Response structure:", {
        hasResponse: !!response,
        hasMeta: !!(response && response.meta),
        metaKeys: response && response.meta ? Object.keys(response.meta) : [],
        responseKeys: response ? Object.keys(response) : []
    });
    
    if (!response || !response.meta) {
        console.error("Invalid response format for pagination", response);
        
        // If we have data but no meta, try to calculate pagination from data
        if (response && response.data && response.data.length > 0) {
            const dataCount = response.data.length;
            $('#poPagination').html('');
            $('#paginationInfo').html(`Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">${dataCount}</span> of <span class="fw-semibold">${dataCount}</span> entries`);
            return;
        }
        
        $('#poPagination').html('');
        $('#paginationInfo').html('Showing <span class="fw-semibold">0</span> entries');
        return;
    }
   

    const meta = response.meta;
    const currentPage = meta.current_page || 1;
    const lastPage = meta.last_page || 1;
    const totalItems = meta.total || 0;
    const perPage = meta.per_page || 10;
    const from = ((currentPage - 1) * perPage) + 1;
    const to = Math.min(from + perPage - 1, totalItems);
    
    console.log("Pagination calculation:", {
        currentPage: currentPage,
        lastPage: lastPage,
        totalItems: totalItems,
        perPage: perPage,
        from: from,
        to: to,
        meta: meta
    });

    // Update pagination info
    const paginationInfoHtml = `
        Showing <span class="fw-semibold">${from}</span> to 
        <span class="fw-semibold">${to}</span> of 
        <span class="fw-semibold">${totalItems}</span> entries
    `;
    
    console.log("Updating pagination info:", {
        from: from,
        to: to,
        totalItems: totalItems,
        currentPage: currentPage,
        perPage: perPage,
        targetTableBody: targetTableBody
    });
    
    $('#paginationInfo').html(paginationInfoHtml);
    
    // Don't show pagination if only one page
    if (lastPage <= 1) {
        $('#poPagination').html('');
        return;
    }

    let paginationHtml = '';
    const maxVisiblePages = 5; // Show up to 5 page numbers
    
    // Previous button
    const prevUrl = response.links?.prev || '';
    paginationHtml += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${currentPage - 1}" ${!prevUrl ? 'disabled' : ''}>Previous</a>
    </li>`;

    // Calculate range of pages to show
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages/2));
    let endPage = Math.min(lastPage, startPage + maxVisiblePages - 1);
    
    // Adjust if we're at the end
    if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    // First page and ellipsis if needed
    if (startPage > 1) {
        paginationHtml += `<li class="page-item">
            <a class="page-link" href="#" data-page="1">1</a>
        </li>`;
        if (startPage > 2) {
            paginationHtml += `<li class="page-item disabled">
                <span class="page-link">...</span>
            </li>`;
        }
    }

    // Page numbers in range
    for (let i = startPage; i <= endPage; i++) {
        paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#" data-page="${i}">${i}</a>
        </li>`;
    }

    // Last page and ellipsis if needed
    if (endPage < lastPage) {
        if (endPage < lastPage - 1) {
            paginationHtml += `<li class="page-item disabled">
                <span class="page-link">...</span>
            </li>`;
        }
        paginationHtml += `<li class="page-item">
            <a class="page-link" href="#" data-page="${lastPage}">${lastPage}</a>
        </li>`;
    }

    // Next button
    const nextUrl = response.links?.next || '';
    paginationHtml += `<li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${currentPage + 1}" ${!nextUrl ? 'disabled' : ''}>Next</a>
    </li>`;

    console.log("pagination iiii", paginationHtml);
    
    $('#poPagination').html(paginationHtml);
}
    
  $(document).on('click', '#poPagination .page-link', function(e) {
        e.preventDefault();
        if ($(this).parent().hasClass('disabled')) return;
        
        const page = $(this).data('page');
        if (!page) return;
        
        const activeTab = $('#poTabsContent .tab-pane.active').attr('id') || 'all-po';
        const searchQuery = $('#searchPO').val() || '';
        const startDate = $('#startDateFilter').val() || '';
        const endDate = $('#endDateFilter').val() || '';
        let status = '';
        let targetTableBody = '#poTableBody';
        
        // Debug tab detection
        console.log("Tab detection debug:", {
            activeTab: activeTab,
            poTabPanes: $('#poTabsContent .tab-pane').map((i, el) => ({id: el.id, classes: el.className, visible: $(el).is(':visible')})).get(),
            activePOTabPane: $('#poTabsContent .tab-pane.active').attr('id'),
            activePOTabPaneClasses: $('#poTabsContent .tab-pane.active').attr('class'),
            allTabPanes: $('.tab-pane').map((i, el) => ({id: el.id, classes: el.className, visible: $(el).is(':visible')})).get(),
            activeTabPane: $('.tab-pane.active').attr('id'),
            activeTabPaneClasses: $('.tab-pane.active').attr('class')
        });
        
        switch(activeTab) {
            case 'all-po':
                status = '';
                targetTableBody = '#poTableBody';
                break;
            case 'draft': 
                status = 'draft';
                targetTableBody = '#draftTableBody';
                break;
            case 'created': 
                status = 'pending';
                targetTableBody = '#createdTableBody';
                break;
        case 'pending-approval': 
            status = 'pending';
            targetTableBody = '#pendingApprovalTableBody';
            break;
        case 'received': 
            status = 'received';
            targetTableBody = '#receivedTableBody';
            break;
        case 'processing': 
            status = 'processing';
            targetTableBody = '#processingTableBody';
            break;
        case 'approved': 
            status = 'approved';
            targetTableBody = '#approvedTableBody';
            break;
        case 'completed': 
            status = 'completed';
            targetTableBody = '#completedTableBody';
            break;
        case 'rejected': 
            status = 'rejected';
            targetTableBody = '#rejectedTableBody';
            break;
        }
        
        console.log('Pagination clicked:', { page, activeTab, searchQuery, startDate, endDate, status, targetTableBody });
        fetchPurchaseOrders(page, startDate, endDate, searchQuery, status, targetTableBody);
    });

// // Update updatePaginationInfo to handle both response formats
// function updatePaginationInfo(response) {
//     if (!response) return;
    
//     // Determine pagination info based on response format
//     const from = response.meta?.from || response.from || 0;
//     const to = response.meta?.to || response.to || 0;
//     const total = response.meta?.total || response.total || 0;
    
//     $('#paginationInfo').html(`
//         Showing <span class="fw-semibold">${from}</span> to <span class="fw-semibold">${to}</span> of <span class="fw-semibold">${total}</span> entries 
//     `);
// }


function changePage(event, page) {
    event.preventDefault();
    const searchQuery = $('#searchPO').val() || '';
    const startDate = $('#startDateFilter').val() || '';
    const endDate = $('#endDateFilter').val() || '';
    const activeTab = $('.tab-pane.active').attr('id') || 'all-po';
    let status = '';
    let targetTableBody = '#poTableBody';
    
    // Determine status and target table body based on active tab
    switch(activeTab) {
        case 'all-po': 
            status = '';
            break;
        case 'draft': 
            status = 'draft';
            targetTableBody = '#draftTableBody';
            break;
        case 'created': 
            status = 'created';
            targetTableBody = '#createdTableBody';
            break;
        case 'pending-approval': 
            status = 'pending';
            targetTableBody = '#pendingApprovalTableBody';
            break;
        case 'approved': 
            status = 'approved';
            targetTableBody = '#approvedTableBody';
            break;
        case 'completed': 
            status = 'completed';
            targetTableBody = '#completedTableBody';
            break;
        case 'processing': 
            status = 'processing';
            targetTableBody = '#processingTableBody';
            break;
        case 'received': 
            status = 'received';
            targetTableBody = '#receivedTableBody';
            break;
        case 'rejected': 
            status = 'rejected';
            targetTableBody = '#rejectedTableBody';
            break;
    }
    
    console.log('changePage called:', { page, activeTab, searchQuery, startDate, endDate, status, targetTableBody });
    fetchPurchaseOrders(page, startDate, endDate, searchQuery, status, targetTableBody);
}
//  Search function
function searchPurchaseOrders() {
    const searchQuery = $('#searchPO').val() || '';
    const startDate = $('#startDateFilter').val() || '';
    const endDate = $('#endDateFilter').val() || '';
    fetchPurchaseOrders(1, startDate, endDate, searchQuery);
}






// Helper functions
function showError(target, message) {
    $(target).html(`<tr><td colspan="8" class="text-center py-3 text-danger">
        <i class="fas fa-exclamation-circle me-2"></i>${message}
    </td></tr>`);
}

function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

function initializeEventListeners(tableBodySelector) {
    // Initialize view/edit/delete buttons for this table
    $(`${tableBodySelector} .view-po`).click(function() {
        const poId = $(this).data('po-id');
        viewPurchaseOrder(poId);
    });
    
    // Similar for edit and delete buttons
}







// Toggle select all checkboxes
function toggleSelectAll() {
    const isChecked = $('#selectAll').prop('checked');
    $('.row-checkbox').prop('checked', isChecked);
}

// Delete selected POs
function deleteSelectedPOs() {
    const selectedIds = [];
    $('.row-checkbox:checked').each(function() {
        selectedIds.push($(this).data('po-id'));
    });
    
    if (selectedIds.length === 0) {
        alert('Please select at least one purchase order to delete.');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${selectedIds.length} selected purchase order(s)?`)) {
        // AJAX call to delete multiple POs
        $.ajax({
            url: '/company/warehouse/purchasing_order/delete-multiple',
            method: 'POST',
            data: {
                ids: selectedIds
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Selected purchase orders deleted successfully.');
                fetchPurchaseOrders();
            },
            error: function(xhr) {
                alert('Error deleting purchase orders: ' + xhr.responseJSON.message);
            }
        });
    }
}
// Initialize on page load
$(document).ready(function() {
    fetchPurchaseOrders();
    
    // Set up enter key in search input
    $('#searchPO').keypress(function(e) {
        if (e.which === 13) {
            searchPurchaseOrders();
        }
    });
});







// Load suppliers for dropdown
function loadSuppliers() {
    $.ajax({
        url: '/company/warehouse/purchasing_order/suppliers/all',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // console.log("Suppliers response:", response.data);
            let options = '<option value="">-- Select Supplier --</option>';
            response.data.forEach(supplier => {
                options += `<option value="${supplier.id}">${supplier.company_name}</option>`;
            });
            
            $('#supplierSelect, #editSupplier').html(options);
        },
        error: function(xhr) {
            // console.error("Error loading suppliers:", xhr.responseJSON);
        }
    });
}

// View PO details
$(document).on('click', '.view-order', function(e) {
    e.preventDefault();
    const poId = $(this).data('id');
    
    $.ajax({
        url: `/company/warehouse/purchasing_order/${poId}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $('#viewOrderModal').modal('show');
            // Just show a spinner OVER the existing content
            $('#viewOrderModal .modal-body').prepend(
                '<div id="loadingOverlay" class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(255,255,255,0.7); z-index: 1000; top: 0; left: 0;">' +
                '<i class="fas fa-spinner fa-spin fa-3x"></i>' +
                '</div>'
            );
        },
     success: function(response) {
    
      $('#loadingOverlay').remove();
            if (!response) {
                console.error("Empty response received");
                const modal = bootstrap.Modal.getInstance(document.getElementById('viewOrderModal'));
                if (modal) {
                    modal.hide();
                } else {
                $('#viewOrderModal').modal('hide');
                }
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Received empty response from server',
                    confirmButtonText: 'OK'
                });
                return;
            }

            console.log("PO details:", response);
            const po = response.data || response; // Handle both wrapped and raw responses
            
            // Verify required fields exist
            if (!po || !po.supplier || !po.items) {
                console.error("Invalid PO data structure:", po);
                const modal = bootstrap.Modal.getInstance(document.getElementById('viewOrderModal'));
                if (modal) {
                    modal.hide();
                } else {
                $('#viewOrderModal').modal('hide');
                }
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Invalid purchase order data structure',
                    confirmButtonText: 'OK'
                });
                return;
            }

            let statusBadge = '';
            switch((po.status || '').toLowerCase()) {
                case 'created': statusBadge = 'bg-info'; break;
                case 'approved': statusBadge = 'bg-primary'; break;
                case 'delivered': statusBadge = 'bg-success'; break;
                case 'cancelled': statusBadge = 'bg-danger'; break;
                default: statusBadge = 'bg-secondary';
            }
            
            // Update modal fields
            $('#viewPoNumber').text(po.po_number || 'N/A');
            $('#viewSupplier').text(po.supplier.name || 'N/A');
            $('#viewOrderDate').text(po.order_date || 'N/A');
            $('#viewExpectedDelivery').text(po.delivery_date || 'N/A');
            $('#viewStatus').text(po.status || 'N/A').removeClass().addClass('badge ' + statusBadge);
            $('#viewTotalItems').text(po.total_items || '0');
            $('#viewTotalValue').text(window.CurrencyHelper.format(po.total_value || 0));
            
            // Build items list
            let itemsHtml = '<table class="table table-sm"><thead><tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead><tbody>';
            
            (po.items || []).forEach(item => {
                itemsHtml += `<tr>
                    <td>${item.name || 'N/A'}</td>
                    <td>${item.quantity || '0'}</td>
                    <td>${window.CurrencyHelper.format(item.unit_price || 0)}</td>
                    <td>${window.CurrencyHelper.format(item.total_price || 0)}</td>
                </tr>`;
            });
            
            itemsHtml += '</tbody></table>';

            // console.log("Items HTML:", itemsHtml);
            $('#viewItems').html(itemsHtml);
            },
        error: function(xhr) {
            console.error("Error fetching PO details:", xhr.responseJSON);
            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load purchase order details.',
                confirmButtonText: 'OK'
            });
            const modal = bootstrap.Modal.getInstance(document.getElementById('viewOrderModal'));
            if (modal) {
                modal.hide();
            } else {
            $('#viewOrderModal').modal('hide');
            }
        }
    });
});

// Edit PO


$(document).on('click', '.edit-order', function(e) {
    e.preventDefault();
    const poId = $(this).data('id');
    
    $.ajax({
        url: `/company/warehouse/purchasing_order/${poId}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $('#editOrderModal').modal('show');
            $('#editOrderModal .modal-body').prepend(
                '<div id="editloadingOverlay" class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(255,255,255,0.7); z-index: 1000; top: 0; left: 0;">' +
                '<i class="fas fa-spinner fa-spin fa-3x"></i>' +
                '</div>'
            );
        },
        success: function(response) {
            $('#editloadingOverlay').remove();
            const po = response.data || response; 
            
            // Set basic info
            $('#editPoNumber').text(po.po_number);
            $('#editPoNumberInput').val(po.po_number);
            
            // Set supplier - first clear existing options
            $('#editSupplier').empty();
            $('#editSupplier').append('<option value="">-- Select Supplier --</option>');
            
            // Add current supplier as selected option
            if (po.supplier) {
                $('#editSupplier').append(
                    `<option value="${po.supplier.id}" selected>${po.supplier.name}</option>`
                );
            }
            
            // You might want to add other suppliers here if needed
            // Example: $('#editSupplier').append('<option value="1">Other Supplier</option>');
            
            $('#editOrderDate').val(po.order_date);
            $('#editExpectedDelivery').val(po.delivery_date); // Fixed typo from "delivery_date"
            $('#editStatus').val(po.status.toLowerCase()); // Ensure case matches
            
            // Clear existing rows
            $('#editItemsTable tbody').empty();
            
            
            // Add items to table
           if (po.items && Array.isArray(po.items) && po.items.length > 0) {
            console.log("Items found:", po.items); // Debug log
            console.log("Items debug:", {
                hasItems: !!po.items,
                itemsType: typeof po.items,
                isArray: Array.isArray(po.items),
                itemsLength: po.items ? po.items.length : 'N/A',
                items: po.items
            });
            
            // Clear the table first
            $('#editItemsTable tbody').empty();
            
            // Ensure items is an array before processing
            const itemsArray = Array.isArray(po.items) ? po.items : [];
            itemsArray.forEach((item, index) => {
                console.log(`Processing item ${index}:`, item); // Debug log
                
                // Convert values to proper types
                const quantity = item.quantity ? Number(item.quantity) : '';
                const unitPrice = item.unit_price ? Number(item.unit_price).toFixed(2) : '';
                const totalPrice = item.total_price ? Number(item.total_price).toFixed(2) : '';
                
                console.log(`Formatted values - Qty: ${quantity}, Price: ${unitPrice}, Total: ${totalPrice}`); // Debug log
                
                // Create new row
                const row = `
                    <tr>
                        <td><input type="text" class="form-control" name="edit_item_name[]" value="${item.name || ''}"></td>
                        <td><input type="number" class="form-control" name="edit_item_qty[]" value="${quantity}" oninput="calculateEditTotal(this)"></td>
                        <td><input type="number" step="0.01" class="form-control" name="edit_item_price[]" value="${unitPrice}" oninput="calculateEditTotal(this)"></td>
                        <td><input type="text" class="form-control" readonly name="edit_item_total[]" value="${totalPrice}"></td>
                        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeEditRow(this)">x</button></td>
                    </tr>
                `;
                
                $('#editItemsTable tbody').append(row);
                
                // Verify DOM after insertion
                const addedRow = $('#editItemsTable tbody tr').last();
                console.log("Added row inputs:", {
                    name: addedRow.find('input[name="edit_item_name[]"]').val(),
                    qty: addedRow.find('input[name="edit_item_qty[]"]').val(),
                    price: addedRow.find('input[name="edit_item_price[]"]').val(),
                    total: addedRow.find('input[name="edit_item_total[]"]').val()
                });
            });
        } else {
            console.log("No items found, adding empty row"); // Debug log
            console.log("Items debug for empty case:", {
                hasItems: !!po.items,
                itemsType: typeof po.items,
                isArray: Array.isArray(po.items),
                items: po.items
            });
            
            $('#editItemsTable tbody').empty();
            
            // For imported POs, show a message instead of empty row
            if (po.items && typeof po.items === 'string') {
                $('#editItemsTable tbody').html(`
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            Items imported from Excel file. Use the import feature to modify items.
                        </td>
                    </tr>
                `);
            } else {
                addEditItemRow();
            }
        }
            // Set totals
            updateEditTotals();
        },
        error: function(xhr) {
            console.error("Error fetching PO details:", xhr.responseJSON);
            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load purchase order details for editing.',
                confirmButtonText: 'OK'
            });
            const modal = bootstrap.Modal.getInstance(document.getElementById('editOrderModal'));
            if (modal) {
                modal.hide();
            } else {
            $('#editOrderModal').modal('hide');
            }
        }
    });
});


// Add row to edit items table
function addEditItemRow(name = '', quantity = '', price = '', total = '') {
    const row = `
        <tr>
            <td><input type="text" class="form-control" name="edit_item_name[]" value="${name}"></td>
            <td><input type="number" class="form-control" name="edit_item_qty[]" value="${quantity}" oninput="calculateEditTotal(this)"></td>
            <td><input type="number" step="0.01" class="form-control" name="edit_item_price[]" value="${price}" oninput="calculateEditTotal(this)"></td>
            <td><input type="text" class="form-control" readonly name="edit_item_total[]" value="${total}"></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeEditRow(this)">x</button></td>
        </tr>
    `;
    $('#editItemsTable tbody').append(row);
}

// Remove row from edit items table
function removeEditRow(button) {
    $(button).closest('tr').remove();
    updateEditTotals();
}

// Calculate total for a row in edit table
function calculateEditTotal(input) {
    const row = $(input).closest('tr');
    const quantity = parseFloat(row.find('input[name="edit_item_qty[]"]').val()) || 0;
    const price = parseFloat(row.find('input[name="edit_item_price[]"]').val()) || 0;
    const total = quantity * price;
    row.find('input[name="edit_item_total[]"]').val(total.toFixed(2));
    updateEditTotals();
}


// Update the summary totals in edit modal
function updateEditTotals() {
    let totalItems = 0;
    let totalValue = 0;
    
    $('#editItemsTable tbody tr').each(function() {
        const quantity = parseFloat($(this).find('input[name="edit_item_qty[]"]').val()) || 0;
        const total = parseFloat($(this).find('input[name="edit_item_total[]"]').val()) || 0;
        
        totalItems += quantity;
        totalValue += total;
    });
    
    $('#editTotalItems').val(totalItems);
    $('#editTotalValue').val(totalValue.toFixed(2));
}




// Add this inside your $(document).ready() function or script section
$('.createPOModal').on('click', function () {
    // Clear the form when modal opens

    console.log("Create PO button clicked");
    $("#createPOModal").show();
    $('#createPOForm')[0].reset();

    console.log("ggg")
    
    // Generate and set PO number
    generatePONumber();
    
    // Auto-load tax configurations since "Select from Configurations" is selected by default
    loadTaxConfigurations();
});

// Function to generate and set PO number
function generatePONumber() {
    $.ajax({
        url: '/company/warehouse/purchasing_order/generatePONumber', // Your route for generating PO numbers
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $('#poNumberInput').val('Generating...');
        },
        success: function(response) {
            console.log("PO number generated:", response);
            if (response.success) {
                $('#po_number').val(response.po_number);
            }
        },
        error: function(xhr) {
            console.log("Error generating PO number:", xhr)
            console.error("Error generating PO number:", xhr.responseJSON);
            $('#poNumberInput').val('PO-ERROR');
            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to generate PO number. Please try again.',
                confirmButtonText: 'OK'
            });
        }
    });
}


// // Save edited PO
// $('#editPOForm').on('submit', function(e) {
//     e.preventDefault();
//     const poId = $('#editPoNumber').text(); // Extract ID from PO number
    
//     let formData = {
//         _token: $('meta[name="csrf-token"]').attr('content'),
//         _method: 'PUT',
//         po_number: $('#editPoNumberInput').val(),
//         supplier_id: $('#editSupplier').val(),
//         order_date: $('#editOrderDate').val(),
//         expected_delivery_date: $('#editExpectedDelivery').val(),
//         status: $('#editStatus').val(),
//         items: [],
//         total_items: 0,
//         total_value: 0
//     };
//     console.log("poId:", poId);


//     console.log("Form data before collecting items:", formData); 
    
//     // Collect items
//     $('#editItemsTable tbody tr').each(function() {
//         const item = {
//             name: $(this).find('[name="edit_item_name[]"]').val(),
//             quantity: $(this).find('[name="edit_item_qty[]"]').val(),
//             unit_price: $(this).find('[name="edit_item_price[]"]').val(),
//             total_price: $(this).find('[name="edit_item_total[]"]').val()
//         };
//         formData.items.push(item);
//         formData.total_items += parseInt(item.quantity) || 0;
//         formData.total_value += parseFloat(item.total_price) || 0;
//     });
    
//     $.ajax({
//         url: `/company/warehouse/purchasing_order/${poId}`,
//         method: 'POST',
//         data: formData,
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         beforeSend: function() {
//             $('#editPOForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
//         },
//         success: function(response) {
//             showSafeSweetAlert({
//                 icon: 'success',
//                 title: 'Success!',
//                 text: response.message || 'Purchase order updated successfully.',
//                 confirmButtonText: 'OK'
//             }).then(() => {
//                 $('#editOrderModal').modal('hide');
//                 fetchPurchaseOrders();
//             });
//         },
//         error: function(xhr) {
//             console.log("Error updating PO:", xhr);
//             let errorMessage = 'Failed to update purchase order. Please try again.';
//             if (xhr.responseJSON && xhr.responseJSON.errors) {
//                 errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
//             }
            
//             showSafeSweetAlert({
//                 icon: 'error',
//                 title: 'Error!',
//                 html: errorMessage,
//                 confirmButtonText: 'OK'
//             });
//         },
//         complete: function() {

//             $('#editPOForm button[type="submit"]').prop('disabled', false).html('Save Changes');
//         }
//     });
// });

// Delete PO
$(document).on('click', '.delete-order', function(e) {
    e.preventDefault();
    const poId = $(this).data('id');
    
    showSafeSweetAlert({
        icon: 'warning',
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            deletePurchaseOrder(poId);
        }
    });
});

function deletePurchaseOrder(poId) {
    $.ajax({
        url: `/company/warehouse/purchasing_order/${poId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $('.delete-order[data-id="' + poId + '"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        },
        success: function(response) {
            showSafeSweetAlert({
                icon: 'success',
                title: 'Deleted!',
                text: response.message || 'Purchase order has been deleted.',
                confirmButtonText: 'OK'
            }).then(() => {
                fetchPurchaseOrders();
            });
        },
        error: function(xhr) {
            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON.message || 'Failed to delete purchase order.',
                confirmButtonText: 'OK'
            });
        },
        complete: function() {
            $('.delete-order[data-id="' + poId + '"]').prop('disabled', false).html('<i class="fas fa-trash-alt d-none d-sm-inline"></i><span class="d-inline d-sm-none">Delete</span>');
        }
    });
}










$(document).ready(function() {
    // Initialize modal functionality
    initEditPOModal();
});

function initEditPOModal() {
    // Handle edit button click
    $(document).on('click', '.edit-po', function() {
        const poId = $(this).data('po-id');
        const status = $(this).data('po-status') || $(this).closest('tr').find('[data-status]').data('status');
        
        // Check if PO can be edited (only 'created' and 'draft' status allow editing)
        const editableStatuses = ['created', 'draft'];
        if (status && !editableStatuses.includes(status.toLowerCase())) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Edit Purchase Order',
                text: `This purchase order has status "${status}" and cannot be edited. Only "Created" and "Draft" purchase orders can be modified.`,
                confirmButtonText: 'OK'
            });
            return;
        }
        
        loadPOData(poId);
    });
    
    // Set up delegated event handlers
    setupEditModalHandlers();
}

function loadPOData(poId) {
    // Show loading state
    $('#editPOModalBody').html(`
        <div class="text-center py-5">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
            <p class="mt-3">Loading purchase order details...</p>
        </div>
    `);
    
    $('#editPOModal').modal('show');
    
    // Fetch PO details
    $.ajax({
        url: `/company/warehouse/purchasing_order/${poId}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Store PO data globally for tax calculations
            window.currentPOData = response;
            console.log('PO data loaded for editing:', response);
            renderPOForm(response);
        },
        error: function(xhr) {
            console.error("Error loading PO:", xhr);
            $('#editPOModalBody').html(`
                <div class="alert alert-danger">
                    Failed to load purchase order details. Please try again.
                </div>
            `);
        }
    });
}

function renderPOForm(po) {
    // Debug: Log PO data to see items structure
    console.log('Edit PO - Full PO data:', po);
    console.log('Edit PO - Items:', po.items);
    if (po.items && po.items.length > 0) {
        console.log('Edit PO - First item:', po.items[0]);
        console.log('Edit PO - First item is_reorder:', po.items[0].is_reorder);
    }
    
    // Safely handle dates
    const orderDate = po.order_date ? po.order_date.split(' ')[0] : '';
    const deliveryDate = po.delivery_date ? po.delivery_date.split(' ')[0] : '';
    
    // Generate form HTML
    const formHtml = `
        <form id="editPOForm">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">PO Number</label>
                    <input type="text" class="form-control" id="edit_po_number" value="${escapeHtml(po.po_number || '')}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supplier</label>
                    <select class="form-select" id="edit_supplier_id" required>
                        <option value="">Select Supplier</option>
                        <option value="${po.supplier_id}" selected>${escapeHtml(po.supplier?.company_name || 'Supplier')}</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">PO Date</label>
                    <input type="date" class="form-control" id="edit_po_date" value="${orderDate}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Expected Delivery</label>
                    <input type="date" class="form-control" id="edit_expected_delivery" value="${deliveryDate}" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Payment Terms</label>
                    <select class="form-select" id="edit_payment_terms">
                        <option value="cod" ${po.payment_terms === 'cod' ? 'selected' : ''}>Cash on Delivery</option>
                        <option value="7days" ${po.payment_terms === '7days' ? 'selected' : ''}>7 Days</option>
                        <option value="14days" ${po.payment_terms === '14days' ? 'selected' : ''}>14 Days</option>
                        <option value="30days" ${po.payment_terms === '30days' ? 'selected' : ''}>30 Days</option>
                        <option value="50_50" ${po.payment_terms === '50_50' ? 'selected' : ''}>50% Advance, 50% on Delivery</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="edit_status" name="status">
                        <option value="created" ${po.status === 'created' ? 'selected' : ''}>Created</option>
                        <option value="draft" ${po.status === 'draft' ? 'selected' : ''}>Draft</option>
                    </select>
                    <small class="form-text text-muted">Only Created and Draft statuses can be edited</small>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" id="edit_notes" rows="2">${escapeHtml(po.notes || '')}</textarea>
            </div>
            
            <!-- Tax Configuration Section -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Tax Configuration</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tax Method</label>
                            <select class="form-select" id="edit_taxMethod" name="tax_method">
                                <option value="config" ${po.tax_configuration_id ? 'selected' : ''}>Select from Configurations</option>
                                <option value="manual" ${!po.tax_configuration_id ? 'selected' : ''}>Enter Manual Rate</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="edit_taxConfigContainer" style="${!po.tax_configuration_id ? 'display: none;' : ''}">
                            <label class="form-label">Tax Configuration</label>
                            <div class="d-flex">
                                <select class="form-select" id="edit_taxConfiguration" name="tax_configuration_id" style="width: 80%;">
                                    <option value="">Loading configurations...</option>
                                </select>
                                <div class="ms-2" id="edit_taxConfigLoading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="edit_manualTaxContainer" style="${po.tax_configuration_id ? 'display: none;' : ''}">
                            <label class="form-label">Custom Tax Rate (%)</label>
                            <input type="number" class="form-control" id="edit_customTaxRate" name="tax_rate" 
                                   step="0.01" min="0" max="100" value="${po.tax_rate || ''}" 
                                   placeholder="Enter tax rate">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_taxExempt" name="is_tax_exempt" value="1" ${po.is_tax_exempt ? 'checked' : ''}>
                                <input type="hidden" name="is_tax_exempt" value="0">
                                <label class="form-check-label" for="edit_taxExempt">
                                    Mark as Tax Exempt
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3" id="edit_taxExemptionReasonContainer" style="${!po.is_tax_exempt ? 'display: none;' : ''}">
                        <div class="col-md-12">
                            <label class="form-label">Tax Exemption Reason</label>
                            <textarea class="form-control" id="edit_taxExemptionReason" name="tax_exemption_reason" 
                                      rows="2" placeholder="Enter reason for tax exemption">${escapeHtml(po.tax_exemption_reason || '')}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <h6 class="mt-4 mb-3">Items</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="editItemsTable">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit Price (GHS)</th>
                            <th>Total (GHS)</th>
                            <th>Re-order</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${(Array.isArray(po.items) ? po.items : []).map(item => {
                            const isReorder = item.is_reorder === true || item.is_reorder === 1 || item.is_reorder === '1';
                            const itemName = escapeHtml(item.name || '');
                            const batchNumber = item.batch_number ? escapeHtml(item.batch_number) : '';
                            const reorderReason = item.reorder_reason ? escapeHtml(item.reorder_reason) : '';
                            const total = ((item.quantity || 0) * (item.unit_price || 0)).toFixed(2);
                            
                            return `
                            <tr class="edit-item-row">
                                <td><input type="text" class="form-control item-name-input item-name" value="${itemName}" ${isReorder ? 'readonly class="bg-light"' : ''} required></td>
                                <td><input type="number" class="form-control item-qty" min="1" value="${item.quantity || ''}" required></td>
                                <td><input type="number" class="form-control item-price" step="0.01" min="0" value="${item.unit_price || ''}" required></td>
                                <td><input type="text" class="form-control item-total" value="${total}" readonly></td>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input reorder-checkbox" ${isReorder ? 'checked' : ''} onchange="toggleReorderFields(this)">
                                    </div>
                                    <div class="reorder-fields" style="${isReorder ? 'margin-top: 10px;' : 'display: none; margin-top: 10px;'}">
                                        <input type="text" class="form-control form-control-sm mb-2 batch-number-input" placeholder="Batch Number" value="${batchNumber}" onblur="validateBatchNumber(this)">
                                        <textarea class="form-control form-control-sm" placeholder="Reason for re-order" rows="2">${reorderReason}</textarea>
                                    </div>
                                </td>
                                <td><button type="button" class="btn btn-sm btn-danger btn-remove-item"><i class="fas fa-trash"></i></button></td>
                            </tr>`;
                        }).join('')}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-end">
                                <button type="button" class="btn btn-sm btn-primary" id="addEditItemBtn">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                            <td colspan="2" id="edit_subtotal">GHS 0.00</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Tax:</td>
                            <td colspan="2" id="edit_tax">GHS 0.00</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td colspan="2" id="edit_total" class="fw-bold">GHS 0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    `;
    
    // Insert the form HTML
    $('#editPOModalBody').html(formHtml);
    
    // Load tax configurations if needed
    if (po.tax_configuration_id) {
        loadEditTaxConfigurations();
    }
    
    // Calculate initial totals
    calculateEditTotals();
    
    // Set up save button
    $('#editPOSaveBtn').off('click').on('click', function() {
        updatePurchaseOrder(po.id);
    });
}

function setupEditModalHandlers() {
    // Add new item row
    $(document).on('click', '#addEditItemBtn', function() {
        addEditItemRow();
    });
    
    // Remove item row
    $(document).on('click', '.btn-remove-item', function() {
        $(this).closest('tr').remove();
        calculateEditTotals();
    });
    
    // Calculate row total when quantity or price changes
    $(document).on('input', '.item-qty, .item-price', function() {
        const row = $(this).closest('tr');
        calculateEditRowTotal(row);
    });
    
    // Validate batch number in edit modal
    $(document).on('blur', '#editItemsTable .batch-number-input', function() {
        validateBatchNumber(this);
    });
    
    // Validate item name in edit modal
    $(document).on('blur', '#editItemsTable .item-name-input, #editItemsTable .item-name', function() {
        if (!$(this).prop('readonly')) {
            validateItemName(this);
        }
    });
    
    // Tax configuration change handlers
    $(document).on('change', '#edit_taxMethod', function() {
        const method = $(this).val();
        console.log('Edit tax method changed to:', method);
        if (method === 'config') {
            $('#edit_taxConfigContainer').show();
            $('#edit_manualTaxContainer').hide();
            loadEditTaxConfigurations();
        } else {
            $('#edit_taxConfigContainer').hide();
            $('#edit_manualTaxContainer').show();
        }
        // Force tax calculation after method change
        setTimeout(() => {
            calculateEditTotals();
        }, 100);
    });
    
    $(document).on('change', '#edit_taxConfiguration, #edit_customTaxRate', function() {
        console.log('Edit tax configuration/rate changed');
        calculateEditTotals();
    });
    
    $(document).on('change', '#edit_taxExempt', function() {
        const isExempt = $(this).is(':checked');
        console.log('Edit tax exempt changed to:', isExempt);
        if (isExempt) {
            $('#edit_taxExemptionReasonContainer').show();
        } else {
            $('#edit_taxExemptionReasonContainer').hide();
        }
        calculateEditTotals();
    });
}

function addEditItemRow(name = '', quantity = 1, price = 0) {
    const total = (quantity * price).toFixed(2);
    const row = `
        <tr class="edit-item-row">
            <td><input type="text" class="form-control item-name-input item-name" value="${escapeHtml(name)}" onblur="validateItemName(this)" required></td>
            <td><input type="number" class="form-control item-qty" min="1" value="${quantity}" required></td>
            <td><input type="number" class="form-control item-price" step="0.01" min="0" value="${price}" required></td>
            <td><input type="text" class="form-control item-total" value="${total}" readonly></td>
            <td>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input reorder-checkbox" onchange="toggleReorderFields(this)">
                </div>
                <div class="reorder-fields" style="display: none; margin-top: 10px;">
                    <input type="text" class="form-control form-control-sm mb-2 batch-number-input" placeholder="Batch Number" onblur="validateBatchNumber(this)">
                    <textarea class="form-control form-control-sm" placeholder="Reason for re-order" rows="2"></textarea>
                </div>
            </td>
            <td><button type="button" class="btn btn-sm btn-danger btn-remove-item"><i class="fas fa-trash"></i></button></td>
        </tr>
    `;
    $('#editItemsTable tbody').append(row);
}

function calculateEditRowTotal(row) {
    const qty = parseFloat($(row).find('.item-qty').val()) || 0;
    const price = parseFloat($(row).find('.item-price').val()) || 0;
    const total = (qty * price).toFixed(2);
    $(row).find('.item-total').val(total);
    calculateEditTotals();
}

function calculateEditTotals() {
    console.log('calculateEditTotals called');
    let subtotal = 0;
    
    $('.edit-item-row').each(function() {
        subtotal += parseFloat($(this).find('.item-total').val()) || 0;
    });
    
    console.log('Subtotal calculated:', subtotal);
    
    // Calculate tax based on current settings
    const isTaxExempt = $('#edit_taxExempt').is(':checked');
    console.log('Is tax exempt:', isTaxExempt);
    let taxAmount = 0;
    let taxRate = 0;
    
    if (!isTaxExempt) {
        const taxMethod = $('#edit_taxMethod').val();
        console.log('Tax method:', taxMethod);
        
        if (taxMethod === 'manual') {
            // Use manual tax rate
            taxRate = parseFloat($('#edit_customTaxRate').val()) || 0;
            console.log('Manual tax rate:', taxRate);
            taxAmount = subtotal * (taxRate / 100);
        } else {
            // Use configuration - recalculate based on current subtotal
            const selectedConfigId = $('#edit_taxConfiguration').val();
            console.log('Selected config ID:', selectedConfigId);
            console.log('Current PO data:', window.currentPOData);
            
            if (selectedConfigId && window.currentPOData?.tax_configuration) {
                const config = window.currentPOData.tax_configuration;
                taxRate = parseFloat(config.rate) || 0;
                taxAmount = subtotal * (taxRate / 100);
                console.log('Using config tax calculation:', { taxRate, taxAmount });
                
            } else if (selectedConfigId) {
                // Configuration selected but not loaded, use default calculation
                console.log('Configuration selected but not loaded, using default calculation');
                taxRate = 21.90; // Default rate
                taxAmount = subtotal * (taxRate / 100);
            } else {
                // Fallback to stored values
                taxAmount = parseFloat(window.currentPOData?.tax_amount) || 0;
                taxRate = parseFloat(window.currentPOData?.tax_rate) || 0;
                console.log('Using stored values:', { taxAmount, taxRate });
            }
        }
    }
    
    const totalAmount = subtotal + taxAmount;

    console.log('Final calculations:', { subtotal, taxAmount, totalAmount, taxRate });
    
    // Update display
    $('#edit_subtotal').text(`GHS ${subtotal.toFixed(2)}`);
    console.log('Updated subtotal display');
    
    if (isTaxExempt) {
        $('#edit_tax').text('GHS 0.00 (Tax Exempt)');
        console.log('Tax exempt');
    } else {
        $('#edit_tax').text(`GHS ${taxAmount.toFixed(2)} (${taxRate}%)`);
        console.log('Updated tax display:', `GHS ${taxAmount.toFixed(2)} (${taxRate}%)`);
        
        console.log('Updated tax display');
    }
    
    $('#edit_total').text(`GHS ${totalAmount.toFixed(2)}`);
    console.log('Updated total display:', `GHS ${totalAmount.toFixed(2)}`);
}

// Load tax configurations for edit modal
function loadEditTaxConfigurations() {
    console.log('Loading tax configurations...');
    $('#edit_taxConfigLoading').show();
    $('#edit_taxConfiguration').html('<option value="">Loading configurations...</option>');
    
    $.ajax({
        url: '/company/warehouse/purchasing_order/tax-configurations',
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Tax configurations loaded:', response);
            $('#edit_taxConfiguration').html('<option value="">Select Tax Configuration</option>');
            
            if (response.data && response.data.length > 0) {
                response.data.forEach(function(config) {
                    const selected = window.currentPOData?.tax_configuration_id == config.id ? 'selected' : '';
                    $('#edit_taxConfiguration').append(
                        `<option value="${config.id}" ${selected}>${escapeHtml(config.name)} (${config.rate}%)</option>`
                    );
                });
            } else {
                $('#edit_taxConfiguration').append('<option value="">No configurations available</option>');
            }
            
            $('#edit_taxConfigLoading').hide();
        },
        error: function(xhr) {
            console.error('Error loading tax configurations:', xhr);
            $('#edit_taxConfiguration').html('<option value="">Error loading configurations</option>');
            $('#edit_taxConfigLoading').hide();
        }
    });
}

// function updatePurchaseOrder(poId) {
//     // Collect form data
//     const formData = {
//         po_number: $('#edit_po_number').val(),
//         supplier_id: $('#edit_supplier_id').val(),
//         order_date: $('#edit_po_date').val(),
//         expected_delivery_date: $('#edit_expected_delivery').val(),
//         payment_terms: $('#edit_payment_terms').val(),
//         notes: $('#edit_notes').val(),
//         items: []
//     };
    
//     // Collect items data
//     $('.edit-item-row').each(function() {
//         formData.items.push({
//             name: $(this).find('.item-name').val(),
//             quantity: $(this).find('.item-qty').val(),
//             unit_price: $(this).find('.item-price').val(),
//             total_price: $(this).find('.item-total').val()
//         });
//     });
    
//     // Submit the update
//     $.ajax({
//         url: `/company/warehouse/purchasing_order/${poId}/update`,
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         data: formData,
//         success: function(response) {
//             // Handle success
//             $('#editPOModal').modal('hide');
//             showToast('success', 'Purchase order updated successfully');
//             // Refresh the PO list or perform other actions
//         },
//         error: function(xhr) {
//             console.log("Error updating PO:", xhr);
//             showToast('error', 'Failed to update purchase order');
//         }
//     });
// }

// Helper function to escape HTML
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Helper function to show toast messages
function showToast(type, message) {
    // Implement your toast notification system here
    console.log(`${type}: ${message}`);
}






















// // Update purchase order
//  function updatePurchaseOrder(poId) {
//     // Collect form data
//     const formData = {
//         _token: $('meta[name="csrf-token"]').attr('content'),
//         _method: 'PUT',
//         po_number: $('#edit_po_number').val(),
//         supplier_id: $('#edit_supplier_id').val(),
//         order_date: $('#edit_po_date').val(),
//         expected_delivery_date: $('#edit_expected_delivery').val(),
//         payment_terms: $('#edit_payment_terms').val(),
//         notes: $('#edit_notes').val(),
//         items: [],
       
//     };
    
//     // Collect items
//     $('#editItemsTable tbody tr').each(function() {
//         formData.items.push({
//             name: $(this).find('.item-name').val(),
//             quantity: $(this).find('.item-qty').val(),
//             unit_price: $(this).find('.item-price').val(),
//             total_price: $(this).find('.item-total').val()
//         });
//     });
    
//     // Submit the form
//     $.ajax({
//         url: `/company/warehouse/purchasing_order/${poId}`,
//         method: 'POST', // Using POST with _method: 'PUT'
//         data: formData,
//         beforeSend: function() {
//             $('#editPOForm button[type="submit"]').prop('disabled', true)
//                 .html('<i class="fas fa-spinner fa-spin"></i> Saving...');
//         },
//         success: function(response) {
//             Swal.fire({
//                 icon: 'success',
//                 title: 'Success!',
//                 text: response.message || 'Purchase order updated successfully',
//                 confirmButtonText: 'OK'
//             }).then(() => {
//                 $('#editPOModal').modal('hide');
//                 fetchPurchaseOrders(); // Refresh the PO list
//             });
//         },
//         error: function(xhr) {
//             console.log("Error updating PO:", xhr);
//             let errorMessage = 'Failed to update purchase order';
//             if (xhr.responseJSON && xhr.responseJSON.message) {
//                 errorMessage = xhr.responseJSON.message;
//             }
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Error',
//                 text: errorMessage,
//                 confirmButtonText: 'OK'
//             });
//         },
//         complete: function() {
//             $('#editPOForm button[type="submit"]').prop('disabled', false)
//                 .html('<i class="fas fa-save"></i> Save Changes');
//         }
//     });
// }


function updatePurchaseOrder(poId) {
    // Check for invalid batch numbers
    const invalidBatches = $('#editItemsTable .batch-number-input.is-invalid:visible');
    if (invalidBatches.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Batch Numbers',
            html: 'Please fix the invalid batch numbers before updating the PO.<br>Invalid batch numbers are highlighted in red.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    // Check for invalid item names
    const invalidItems = $('#editItemsTable .item-name-input.is-invalid:visible');
    if (invalidItems.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Items',
            html: 'Please fix the duplicate items before updating the PO.<br>Duplicate items are highlighted in red.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    // Check for duplicate batch numbers
    const batchNumbers = [];
    let duplicateBatchFound = false;
    $('#editItemsTable .batch-number-input:visible').each(function() {
        const batch = $(this).val().trim().toUpperCase();
        if (batch && batchNumbers.includes(batch)) {
            duplicateBatchFound = true;
            $(this).addClass('is-invalid');
        } else if (batch) {
            batchNumbers.push(batch);
        }
    });
    
    if (duplicateBatchFound) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Batch Numbers',
            text: 'Each batch number can only be used once per PO. Please remove duplicates.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    // Check for duplicate item names
    const itemNames = [];
    let duplicateItemFound = false;
    $('#editItemsTable .item-name-input:visible, #editItemsTable .item-name:visible').each(function() {
        const itemName = $(this).val().trim().toLowerCase();
        if (itemName && !$(this).prop('readonly')) {
            if (itemNames.includes(itemName)) {
                duplicateItemFound = true;
                $(this).addClass('is-invalid');
            } else {
                itemNames.push(itemName);
            }
        }
    });
    
    if (duplicateItemFound) {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Items',
            text: 'Each item can only be added once per PO. Please remove duplicates.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    // Check for reorder items without batch numbers
    let missingBatchNumbers = false;
    $('#editItemsTable .reorder-checkbox:checked').each(function() {
        const row = $(this).closest('tr');
        const batchNumber = row.find('.batch-number-input').val().trim();
        if (!batchNumber) {
            missingBatchNumbers = true;
            row.find('.batch-number-input').addClass('is-invalid');
        }
    });
    
    if (missingBatchNumbers) {
        Swal.fire({
            icon: 'error',
            title: 'Missing Batch Numbers',
            text: 'Please enter batch numbers for all re-order items.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    // Collect form data with all required fields
    const formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        _method: 'PUT',
        po_number: $('#edit_po_number').val(),
        supplier_id: $('#edit_supplier_id').val(),
        order_date: $('#edit_po_date').val(),
        expected_delivery: $('#edit_expected_delivery').val(), // Match backend field name
        payment_terms: $('#edit_payment_terms').val(),
        notes: $('#edit_notes').val(),
        status: $('#edit_status').val(),
        
        // Tax configuration fields
        tax_method: $('#edit_taxMethod').val(),
        tax_configuration_id: $('#edit_taxConfiguration').val() || null,
        tax_type: $('#edit_taxMethod').val() === 'manual' ? 'custom' : 'standard',
        tax_rate: $('#edit_taxMethod').val() === 'manual' ? $('#edit_customTaxRate').val() : null,
        is_tax_exempt: $('#edit_taxExempt').is(':checked') ? '1' : '0',
        tax_exemption_reason: $('#edit_taxExemptionReason').val() || null,
        
        items: []
    };
    
    // Collect items data with re-order information
    $('#editItemsTable tbody tr').each(function() {
        const row = $(this);
        const isReorder = row.find('.reorder-checkbox').is(':checked');
        const batchNumberInput = row.find('.batch-number-input');
        const reorderReasonTextarea = row.find('.reorder-fields textarea');
        
        formData.items.push({
            name: row.find('.item-name').val(),
            quantity: parseFloat(row.find('.item-qty').val()) || 0,
            unit_price: parseFloat(row.find('.item-price').val()) || 0,
            category: 'general',
            is_reorder: isReorder ? 1 : 0,
            batch_number: isReorder ? (batchNumberInput.val() || null) : null,
            reorder_reason: isReorder ? (reorderReasonTextarea.val() || null) : null
        });
    });
    
    // Show loading state
    $('#editPOSaveBtn').prop('disabled', true)
        .html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    
    // Debug: Log the form data being sent
    console.log('Edit PO - Form data being sent:', formData);
    console.log('Edit PO - Items:', formData.items);
    
    // Submit to the correct endpoint
    $.ajax({
        url: `/company/warehouse/purchasing_order/${poId}`, // Match backend route
        method: 'POST', // Using POST with _method: 'PUT'
        data: formData,
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message || 'Purchase order updated successfully',
                confirmButtonText: 'OK'
            }).then(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('editPOModal'));
                if (modal) {
                    modal.hide();
                } else {
                $('#editPOModal').modal('hide');
                }
                fetchPurchaseOrders(); // Refresh the PO list
            });
        },
        error: function(xhr) {
            console.error('Edit PO Error:', xhr);
            console.error('Response JSON:', xhr.responseJSON);
            console.error('Response Text:', xhr.responseText);
            
            let errorMessage = 'Failed to update purchase order';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                console.error('Validation errors:', xhr.responseJSON.errors);
                errorMessage = '<strong>Validation Errors:</strong><br>' + Object.values(xhr.responseJSON.errors).join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMessage,
                confirmButtonText: 'OK'
            });
        },
        complete: function() {
            $('#editPOSaveBtn').prop('disabled', false)
                .html('<i class="fas fa-save"></i> Save Changes');
        }
    });
}

// View Purchase Order Details
function viewPurchaseOrder(poId) {
    // Show loading state
    $('#viewPOModal').modal('show');
    $('#viewPOModal .modal-body').html(`
        <div class="text-center py-5">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
            <p class="mt-3">Loading purchase order details...</p>
        </div>
    `);

    // Fetch PO details
    $.ajax({
        url: `/company/warehouse/purchasing_order/${poId}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            const po = response;
            
            // Store PO data for printing
            window.currentViewPOData = po;

            console.log("PO details:", response);
            
            // Format dates
            const formatDate = (dateString) => {
                if (!dateString) return 'N/A';
                try {
                    const date = new Date(dateString);
                    return isNaN(date) ? 'N/A' : date.toLocaleDateString();
                } catch (e) {
                    return 'N/A';
                }
            };

            // Status badge
            let statusBadge = '';
            const status = po.status ? po.status.toLowerCase() : '';
            switch(status) {
                case 'approved':
                    statusBadge = '<span class="badge bg-success">Approved</span>';
                    break;
                case 'created':
                    statusBadge = '<span class="badge bg-info">Created</span>';
                    break;
                case 'pending':
                    statusBadge = '<span class="badge bg-warning text-dark">Pending</span>';
                    break;
                case 'draft':
                    statusBadge = '<span class="badge bg-secondary">Draft</span>';
                    break;
                case 'completed':
                    statusBadge = '<span class="badge bg-primary">Completed</span>';
                    break;
                case 'rejected':
                    statusBadge = '<span class="badge bg-danger">Rejected</span>';
                    break;
                default:
                    statusBadge = `<span class="badge bg-secondary">${po.status || 'Unknown'}</span>`;
            }

            // Build items table
            let itemsHtml = '';
            let subtotal = 0;
            
            if (po.items && Array.isArray(po.items) && po.items.length > 0) {
                // Debug: Log items to see structure
                console.log('PO Items for view:', po.items);
                console.log('First item structure:', po.items[0]);
                
                // Check if any item has re-order data
                const hasReorders = po.items.some(item => {
                    console.log('Checking item for reorder:', item.name, 'is_reorder:', item.is_reorder);
                    return item.is_reorder === true || item.is_reorder === 1 || item.is_reorder === '1';
                });
                
                console.log('Has reorders:', hasReorders);
                
                itemsHtml = `
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    ${hasReorders ? '<th>Re-order Info</th>' : ''}
                                </tr>
                            </thead>
                            <tbody>`;
                
                // Ensure items is an array before processing
                const itemsArray = Array.isArray(po.items) ? po.items : [];
                itemsArray.forEach(item => {
                    const itemTotal = parseFloat(item.total_price) || (parseFloat(item.quantity) * parseFloat(item.unit_price)) || 0;
                    subtotal += itemTotal;
                    
                    let reorderInfo = '';
                    if (hasReorders) {
                        const isReorderItem = item.is_reorder === true || item.is_reorder === 1 || item.is_reorder === '1';
                        if (isReorderItem) {
                            reorderInfo = `
                                <td>
                                    <div class="badge bg-warning text-dark mb-1">
                                        <i class="fas fa-redo"></i> Re-order
                                    </div>
                                    ${item.batch_number ? `<br><small class="text-muted"><strong>Batch:</strong> ${item.batch_number}</small>` : ''}
                                    ${item.reorder_reason ? `<br><small class="text-muted"><strong>Reason:</strong> ${item.reorder_reason}</small>` : ''}
                                </td>`;
                        } else {
                            reorderInfo = '<td><span class="badge bg-success">New Item</span></td>';
                        }
                    }
                    
                    itemsHtml += `
                        <tr>
                            <td>${item.name || 'N/A'}</td>
                            <td>${item.description || 'N/A'}</td>
                            <td>${item.quantity || '0'}</td>
                            <td>GHS ${(parseFloat(item.unit_price) || 0).toFixed(2)}</td>
                            <td>GHS ${itemTotal.toFixed(2)}</td>
                            ${reorderInfo}
                        </tr>`;
                });
                
                // Use stored tax values or calculate from stored data
                
                const taxAmount = parseFloat(po.tax_amount) || 0;
                const totalAmount = parseFloat(po.total_amount) || (subtotal + taxAmount);
                const taxRate = parseFloat(po.tax_rate) || 0;
                const isTaxExempt = po.is_tax_exempt || false;
                
                const colspanValue = hasReorders ? 5 : 4;
                
                itemsHtml += `
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="${colspanValue}" class="text-end fw-bold">Subtotal:</td>
                                    <td class="fw-bold">GHS ${subtotal.toFixed(2)}</td>
                                </tr>`;
                
                if (isTaxExempt) {
                    itemsHtml += `
                                <tr>
                                    <td colspan="${colspanValue}" class="text-end fw-bold text-success">Tax Exempt:</td>
                                    <td class="fw-bold text-success">GHS 0.00</td>
                                </tr>`;
                } else {
                    itemsHtml += `
                                <tr>
                                    <td colspan="${colspanValue}" class="text-end fw-bold">Tax (${taxRate}%):</td>
                                    <td class="fw-bold">GHS ${taxAmount.toFixed(2)}</td>
                                </tr>`;
                    
                }
                
                itemsHtml += `
                                <tr>
                                    <td colspan="${colspanValue}" class="text-end fw-bold">Total:</td>
                                    <td class="fw-bold">GHS ${totalAmount.toFixed(2)}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>`;
            } else {
                console.log("No items found for view modal");
                console.log("Items debug for view empty case:", {
                    hasItems: !!po.items,
                    itemsType: typeof po.items,
                    isArray: Array.isArray(po.items),
                    items: po.items
                });
                
                // For imported POs, show a different message
                if (po.items && typeof po.items === 'string') {
                    itemsHtml = '<div class="alert alert-info mt-4"><i class="fas fa-info-circle me-2"></i>Items were imported from Excel file</div>';
                } else {
                    itemsHtml = '<div class="alert alert-info mt-4">No items found for this purchase order</div>';
                }
            }

            // Build approval history
            let approvalHtml = '';
            if (po.approvals && po.approvals.length > 0) {
                approvalHtml = `
                    <div class="mt-4">
                        <h6>Approval History</h6>
                        <div class="timeline">`;
                
                po.approvals.forEach(approval => {
                    const approvalDate = formatDate(approval.created_at);
                    approvalHtml += `
                        <div class="timeline-item">
                            <div class="timeline-marker ${approval.status === 'approved' ? 'bg-success' : 'bg-danger'}"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">${approval.status.charAt(0).toUpperCase() + approval.status.slice(1)}</span>
                                    <small class="text-muted">${approvalDate}</small>
                                </div>
                                <p class="mb-0">${approval.user?.name || 'System'}</p>
                                ${approval.notes ? `<p class="mb-0"><small>Note: ${approval.notes}</small></p>` : ''}
                            </div>
                        </div>`;
                });
                
                approvalHtml += `
                        </div>
                    </div>`;
            }

            // Set modal content
            $('#viewPOModal .modal-body').html(`
                <div class="row">
                    <div class="col-md-6">
                        <h6>Vendor Information</h6>
                        <p class="mb-1"><strong>${po.supplier?.company_name || 'N/A'}</strong></p>
                        <p class="mb-1">${po.supplier?.address || 'N/A'}</p>
                        <p class="mb-1">${po.supplier?.email || 'N/A'}</p>
                        <p class="mb-0">${po.supplier?.phone || 'N/A'}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p class="mb-1"><strong>PO Number:</strong> ${po.po_number || 'N/A'}</p>
                        <p class="mb-1"><strong>PO Date:</strong> ${formatDate(po.order_date)}</p>
                        <p class="mb-1"><strong>Expected Delivery:</strong> ${formatDate(po.delivery_date)}</p>
                        <p class="mb-1"><strong>Status:</strong> ${statusBadge}</p>
                        ${po.is_reorder ? `
                            <div class="mt-2 p-2 bg-warning bg-opacity-10 border border-warning rounded">
                                <p class="mb-1"><strong><i class="fas fa-redo me-1"></i>Reorder Information:</strong></p>
                                <p class="mb-1"><strong>Batch Number:</strong> ${po.batch_number || 'N/A'}</p>
                                <p class="mb-0"><strong>Reason:</strong> ${po.reorder_reason || 'N/A'}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                ${itemsHtml}
                
                <div class="mt-4">
                    <h6>Notes</h6>
                    <p>${po.notes || 'No notes available'}</p>
                </div>
                
                ${approvalHtml}
            `);
        },
        error: function(xhr) {
            console.log("Error loading PO:", xhr);
            $('#viewPOModal .modal-body').html(`
                <div class="alert alert-danger">
                    Failed to load purchase order details. Please try again.
                </div>
            `);
        }
    });
}

// Delete purchase order
$(document).on('click', '.delete-po', function() {
    const poId = $(this).data('po-id');
    const status = $(this).data('po-status');
    
    // Check if PO can be deleted (only 'created' and 'draft' status allow deletion)
    const deletableStatuses = ['created', 'draft'];
    if (status && !deletableStatuses.includes(status.toLowerCase())) {
        Swal.fire({
            icon: 'warning',
            title: 'Cannot Delete Purchase Order',
            text: `This purchase order has status "${status}" and cannot be deleted. Only "Created" and "Draft" purchase orders can be deleted.`,
            confirmButtonText: 'OK'
        });
        return;
    }
    
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/company/warehouse/purchasing_order/${poId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $(`.delete-po[data-po-id="${poId}"]`).prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        response.message || 'Purchase order has been deleted.',
                        'success'
                    ).then(() => {
                        fetchPurchaseOrders(); // Refresh the PO list
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        xhr.responseJSON.message || 'Failed to delete purchase order',
                        'error'
                    );
                },
                complete: function() {
                    $(`.delete-po[data-po-id="${poId}"]`).prop('disabled', false)
                        .html('<i class="fas fa-trash-alt"></i>');
                }
            });
        }
    });
});

// Add missing loadFilterData function
function loadFilterData() {
    console.log('loadFilterData called - no action needed for PO page');
    // This function is called but not needed for PO page
    // Just prevent the error
}

// Make it globally accessible
window.loadFilterData = loadFilterData;

</script>

<!-- Include Import Modal -->
@include('company.InventoryManagement.ProcRequi.po-import-modal')