@push('css')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Make table rows clickable */
    .clickable-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .clickable-row:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    
    /* Prevent action buttons from triggering row click */
    .action-buttons {
        white-space: nowrap;
        text-align: right;
        padding-right: 10px !important;
    }
    
    /* Ensure buttons are properly spaced */
    .action-buttons .btn {
        margin: 0 2px;
    }
    
    /* Hide Unit Price and Total columns in all tables */
    table th:nth-child(4),
    table td:nth-child(4),
    table th:nth-child(5),
    table td:nth-child(5) {
        display: none !important;
    }
    
    /* Hide Unit Price and Total columns in requisition tables */
    .requisition-table th:nth-child(4),
    .requisition-table td:nth-child(4),
    .requisition-table th:nth-child(5),
    .requisition-table td:nth-child(5) {
        display: none !important;
    }
    
    /* Hide Unit Price and Total columns in modal tables */
    .table th:nth-child(4),
    .table td:nth-child(4),
    .table th:nth-child(5),
    .table td:nth-child(5) {
        display: none !important;
    }
    
    /* Partially approved status styling */
    .badge.partially-approved {
        background: linear-gradient(45deg, #ffc107, #fd7e14) !important;
        color: white !important;
        font-weight: 600;
        animation: pulse 2s infinite;
    }
    
    .badge.partially-approved i {
        margin-right: 5px;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    
    /* Row highlighting for partially approved requisitions */
    tr.partially-approved-row {
        background-color: rgba(255, 193, 7, 0.1) !important;
        border-left: 4px solid #ffc107;
    }
    
    tr.partially-approved-row:hover {
        background-color: rgba(255, 193, 7, 0.2) !important;
    }
    
    /* Item status styling in modal */
    .table-success {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
    
    .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    
    .table-danger {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    
    /* Item status badges in modal */
    .badge.bg-success {
        font-size: 0.75em;
        padding: 0.25em 0.5em;
    }
    
    .badge.bg-warning {
        font-size: 0.75em;
        padding: 0.25em 0.5em;
    }
    
    .badge.bg-danger {
        font-size: 0.75em;
        padding: 0.25em 0.5em;
    }
    
    /* Hide Unit Price and Total columns in table-bordered tables */
    .table-bordered th:nth-child(4),
    .table-bordered td:nth-child(4),
    .table-bordered th:nth-child(5),
    .table-bordered td:nth-child(5) {
        display: none !important;
    }
    
    /* Hide any column that contains unit price or total data */
    th:contains("Unit Price"),
    td:contains("GH₵"),
    th:contains("Total"),
    td:contains("Total") {
        display: none !important;
    }
    
    /* Force hide any 4th and 5th columns in all tables */
    * table th:nth-child(4),
    * table td:nth-child(4),
    * table th:nth-child(5),
    * table td:nth-child(5) {
        display: none !important;
    }
    
    /* Hide Unit Price and Total form fields in requisition form */
    .col-md-2:has(.form-label:contains("Unit Price")),
    .col-md-2:has(.form-label:contains("Total")) {
        display: none !important;
    }
    
    /* Alternative selector for Unit Price and Total fields */
    .form-label:contains("Unit Price"),
    .form-label:contains("Total"),
    .price-display,
    input[name="item_total[]"] {
        display: none !important;
    }
    
    /* Hide the parent divs containing Unit Price and Total labels */
    div:has(> .form-label:contains("Unit Price")),
    div:has(> .form-label:contains("Total")) {
        display: none !important;
    }
    
    /* Force hide all Unit Price and Total form fields */
    .form-label:contains("Unit Price"),
    .form-label:contains("Total"),
    .price-display,
    input[name="item_unit_price[]"],
    input[name="item_total[]"] {
        display: none !important;
    }
    
    /* Hide parent divs of Unit Price and Total fields */
    .col-md-2:has(.form-label:contains("Unit Price")),
    .col-md-2:has(.form-label:contains("Total")) {
        display: none !important;
    }
    
    /* Ensure form fields use full width */
    .item-row .form-control,
    .item-row .form-select {
        width: 100% !important;
    }
    
    /* Make sure the row uses full width */
    .item-row .row {
        width: 100%;
    }
</style>
<style>
    :root {
        --primary-color: #3b7ddd;
        --secondary-color: #6c757d;
        --success-color: #1cc88a;
        --danger-color: #e74a3b;
    }
    
    body {
        background-color: #f8f9fc;
    }
    .requisition-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem;
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: var(--shadow);
    }
    
    .section-title {
        position: relative;
        font-weight: 600;
        color: var(--dark-color);
        margin: 2rem 0 1.5rem;
        padding-bottom: 0.75rem;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: var(--primary-color);
        border-radius: 3px;
    }
    
    .form-section {
        background: #fff;
        border-radius: var(--border-radius);
        padding: 1.75rem;
        margin-bottom: 1.75rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }
    
    .form-section:hover {
        box-shadow: var(--shadow);
        border-color: rgba(var(--primary-color), 0.2);
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .required-field::after {
        content: " *";
        color: var(--danger-color);
    }
    
    /* Item Rows */
    .item-row {
        position: relative;
        background: #f8fafc;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px dashed var(--border-color);
        transition: var(--transition);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }
    
    .item-row:hover {
        background: #f1f8ff;
        border-color: rgba(var(--primary-color), 0.3);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .remove-item {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 50%;
        color: var(--danger-color);
        opacity: 0.7;
        transition: var(--transition);
    }
    
    .remove-item:hover {
        opacity: 1;
        background: var(--danger-color);
        color: #fff;
        transform: rotate(90deg);
    }
    
    /* File Upload */
    .file-upload-container {
        border: 2px dashed var(--border-color);
        border-radius: var(--border-radius);
        padding: 2.5rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        background: #f8fafc;
    }
    
    .file-upload-container:hover {
        border-color: var(--primary-color);
        background: rgba(var(--primary-color), 0.03);
    }
    
    .file-upload-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        transition: var(--transition);
    }
    
    .file-upload-container:hover .file-upload-icon {
        transform: translateY(-3px);
    }
    
    .file-preview {
        margin-top: 1.5rem;
    }
    
    .file-preview-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        margin-bottom: 0.75rem;
        transition: var(--transition);
    }
    
    .file-preview-item:hover {
        border-color: var(--primary-color);
        background: rgba(var(--primary-color), 0.03);
    }
    
    .file-preview-item i {
        margin-right: 0.75rem;
        color: var(--secondary-color);
        font-size: 1.25rem;
    }
    
    .file-preview-item .file-name {
        flex-grow: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--dark-color);
        font-size: 0.9rem;
    }
    
    .file-preview-item .file-size {
        color: var(--secondary-color);
        font-size: 0.8rem;
        margin-right: 1rem;
    }
    
    .file-preview-item .file-remove {
        color: var(--danger-color);
        cursor: pointer;
        margin-left: 0.5rem;
        opacity: 0.7;
        transition: var(--transition);
    }
    
    .file-preview-item .file-remove:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    
    /* Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: var(--transition);
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }
    
    .btn-responsive {
        min-width: 130px;
        transition: var(--transition);
        font-weight: 500;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
    }
    
    /* Empty state styling */
    .empty-state {
        padding: 2rem;
    }
    
    .empty-state i {
        opacity: 0.5;
    }
    
    .empty-state h5 {
        font-weight: 500;
    }
    
    .empty-state p {
        font-size: 0.95rem;
    }
    
    .btn-outline-secondary {
        color: var(--secondary-color);
        border-color: var(--border-color);
    }
    
    .btn-outline-secondary:hover {
        background: #f8f9fa;
        color: var(--dark-color);
        border-color: var(--border-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    /* Animations */
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(15px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .requisition-container {
            padding: 1.25rem;
        }
        
        .form-section {
            padding: 1.5rem 1.25rem;
        }
        
        .section-title {
            font-size: 1.35rem;
        }
    }
    
    @media (max-width: 767.98px) {
        .btn-responsive {
            width: 100%;
            margin-bottom: 0.75rem;
        }
        
        .btn-group .btn-responsive {
            margin-bottom: 0;
        }
        
        .item-row {
            padding: 1.25rem 1rem;
        }
        
        .file-upload-container {
            padding: 1.75rem 1rem;
        }
    }
    
    @media (max-width: 575.98px) {
        .requisition-container {
            padding: 1rem;
            border-radius: 0;
        }
        
        .form-section {
            padding: 1.25rem 1rem;
            margin-left: -1rem;
            margin-right: -1rem;
            border-left: 0;
            border-right: 0;
            border-radius: 0;
        }
        
        .section-title {
            margin-left: -1rem;
            margin-right: -1rem;
            padding-left: 1rem;
            font-size: 1.25rem;
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
</style>
@endpush

<div class="requisition-container animate-fade-in">
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="requisitionTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="create-tab" data-bs-toggle="tab" data-bs-target="#create-requisition" type="button" role="tab">
                <i class="fas fa-plus-circle me-2"></i>Create Requisition
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#requisition-list" type="button" role="tab">
                <i class="fas fa-list me-2"></i>Requisition List
            </button>
        </li>
    </ul>
    
    <!-- Tab Content -->
    <div class="tab-content" id="requisitionTabsContent">
        <!-- Create Requisition Tab -->
        <div class="tab-pane fade show active" id="create-requisition" role="tabpanel" aria-labelledby="create-tab">
            <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Error!</strong> Please check the form below for errors.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h4 class="mb-1">New Material Requisition</h4>
            <p class="text-muted mb-0">Fill in the details below to create a new material requisition</p>
        </div>
    </div>
    
    <form id="purchaseRequisitionForm" method="POST" action="/company/warehouse/requisitions" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        
        <!-- Requisition Details -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="fas fa-file-alt me-2"></i>Requisition Details
            </h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label required-field">
                        <i class="fas fa-heading me-1 text-muted"></i>Requisition Title
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-heading"></i></span>
                        <input type="text" class="form-control" id="title" name="title" required 
                               placeholder="Enter a descriptive title for this requisition">
                        <div class="invalid-feedback">
                            Please provide a title for this requisition.
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="requisitionDate" class="form-label required-field">
                        <i class="far fa-calendar-alt me-1 text-muted"></i>Requisition Date
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                        <input type="date" class="form-control" id="requisitionDate" name="requisition_date" required>
                    </div>
                </div>


                


                <div class="col-md-3 mb-3">
                    <label for="requestedBy" class="form-label required-field">
                        <i class="fas fa-user-tie me-1 text-muted"></i>Requested By 
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        <select class="form-select" id="requestedBy" name="requester_id" required>
                            <option value="">Select Requester</option>
                            <option value="1">Henry Martey (Procurement Manager)</option>
                            <option value="2">Appiah Kumi (Department Head)</option>
                            <option value="3">Kwame Osei (Operations Manager)</option>
                            <option value="4">Miriam Jones (Finance Manager)</option>
                            <option value="5">David Wilson (Project Lead)</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select the person requesting this requisition
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="requiredDate" class="form-label required-field">
                        <i class="far fa-calendar-check me-1 text-muted"></i>Required Date
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                        <input type="date" class="form-control" id="requiredDate" name="required_date" required>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="department" class="form-label required-field">
                        <i class="fas fa-building me-1 text-muted"></i>Department
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                        <select class="form-select" id="department" name="department_id" required>
                            <option value="" selected disabled>Loading departments...</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a department.
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="projectManager" class="form-label required-field">
                        <i class="fas fa-user-tie me-1 text-muted"></i>Project Manager
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        <select class="form-select" id="projectManager" name="project_manager_id" required>
                            <option value="" selected disabled>Loading Project Managers...</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a project manager.
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="teamLeader" class="form-label required-field">
                        <i class="fas fa-users me-1 text-muted"></i>Team Leader
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                        <select class="form-select" id="teamLeader" name="team_leader_id" required>
                            <option value="" selected disabled>Loading Team Leaders...</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a team leader.
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="priority" class="form-label required-field">
                        <i class="fas fa-flag me-1 text-muted"></i>Priority
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-flag"></i></span>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="referenceNumber" class="form-label">
                        <i class="fas fa-hashtag me-1 text-muted"></i>Reference Number
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                        <input type="text" class="form-control bg-light" id="referenceNumber" 
                               name="reference_number" value="PR-{{ date('Ymd') }}-{{ strtoupper(Str::random(6)) }}" 
                               readonly>
                    </div>
                </div>
            </div>
        
        <!-- Items Section -->
        <div class="form-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Items</h5>
                <button type="button" class="btn btn-sm btn-primary" id="addNewItemBtn">
                    <i class="fas fa-plus me-1"></i> Add Item
                </button>
            </div>
            
            <div id="itemsContainer">
                <!-- Item rows will be added here dynamically by JavaScript -->
            </div>
            
            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <div class="bg-light p-3 rounded-3">
                        <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2 mt-2">
                            <span>Total Items:</span>
                            <span id="totalItems">0</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Note:</strong> All items will be reviewed by the procurement team. Please ensure all details are accurate.
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attachments Section -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="fas fa-paperclip me-2"></i>Attachments
            </h5>
            
            <div class="file-upload-container" id="fileUploadContainer">
                <input type="file" id="fileUpload" name="attachments[]" multiple style="display: none;">
                <div class="text-center py-4">
                    <div class="file-upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <h5>Drag & drop files here or click to browse</h5>
                    <p class="text-muted mb-2">Supported formats: PDF, JPG, PNG, DOC, DOCX, XLS, XLSX</p>
                    <p class="text-muted">Maximum file size: 10MB per file</p>
                    <button type="button" class="btn btn-outline-primary mt-2" id="browseFilesBtn">
                        <i class="fas fa-folder-open me-2"></i>Browse Files
                    </button>
                </div>
            </div>
            
            <div class="file-preview mt-4" id="filePreview">
                <!-- File previews will be added here -->
                <div class="text-center text-muted py-3" id="noFilesMessage">
                    <i class="far fa-folder-open fa-2x mb-2"></i>
                    <p class="mb-0">No files attached yet</p>
                </div>
            </div>
            
            <div class="alert alert-light mt-3" role="alert">
                <div class="d-flex">
                    <i class="fas fa-info-circle text-primary mt-1 me-2"></i>
                    <div>
                        <strong>Tip:</strong> You can attach quotes, specifications, or any other relevant documents to support your requisition. 
                        <span class="d-block mt-1">Supported formats: .pdf, .jpg, .jpeg, .png, .doc, .docx, .xls, .xlsx (Max 10MB each)</span>
                    </div>
                </div>
            </div>
        </div>
        
        </div>
        
        <!-- Form Actions -->
        <div class="form-section bg-light mt-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                <div class="mb-3 mb-sm-0 text-center text-sm-start">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required>
                        <label class="form-check-label" for="termsCheck">
                            I confirm that all information provided is accurate and complete
                        </label>
                        <div class="invalid-feedback">
                            You must confirm before submitting.
                        </div>
                    </div>
                    <div class="form-text mt-1">
                        <i class="fas fa-shield-alt me-1 text-success"></i>Your information is secure and will be encrypted
                    </div>
                </div>
                
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-sm-auto mt-3 mt-sm-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" id="cancelBtn">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-outline-primary px-4 py-2" id="saveDraftBtn">
                        <i class="far fa-save me-2"></i> Save as Draft
                    </button>
                    <button type="submit" class="btn btn-primary px-4 py-2" id="submitBtn">
                        <i class="fas fa-paper-plane me-2"></i> Submit for Approval
                    </button>
                </div>
            </div>
            
            <div class="alert alert-light border mt-3 mb-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    <div>
                        <strong>Next Steps:</strong> After submission, your request will be reviewed by the approver. 
                        You'll receive email notifications about the status of your request.
                    </div>
                </div>
            </div>
        </div>
        </form>
        </div>
        
        <!-- Requisition List Tab -->
        <div class="tab-pane fade" id="requisition-list" role="tabpanel" aria-labelledby="list-tab">
            
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Material Requisitions</h5>
                    <div class="d-flex">
                        <div class="input-group input-group-sm" style="max-width: 300px;">
                            <input type="text" id="searchRequisitions" class="form-control" placeholder="Search requisitions...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <button class="btn btn-sm btn-primary ms-2" id="refreshRequisitions">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Controls -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="requisitionSearch" placeholder="Search requisitions...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="perPageSelect">
                                <option value="5">5 per page</option>
                                <option value="10" selected>10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-primary" id="refreshRequisitions">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="requisitionsTable">
                            <thead>
                                <tr>
                                    <th>Requisition #</th>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th>Requestor</th>
                                    <th>Date</th>
                                    <th>Total Items</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded dynamically via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination and Info -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div id="paginationInfo" class="text-muted"></div>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-end" id="paginationControls">
                                    <!-- Pagination buttons will be generated here -->
                        </ul>
                    </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Item Selection Modal -->
<div class="modal fade" id="itemSelectionModal" tabindex="-1" aria-labelledby="itemSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemSelectionModalLabel">Select Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="itemSearch" placeholder="Search items...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="40">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllItems">
                                    </div>
                                </th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>In Stock</th>
                            </tr>
                        </thead>
                        <tbody id="itemsList">
                            <!-- Items will be loaded here dynamically -->
                            <tr>
                                <td><input type="checkbox" class="form-check-input item-checkbox" data-id="1" data-name="Laptop" data-price="2500.00"></td>
                                <td>Dell XPS 15</td>
                                <td>Electronics</td>
                                <td>12</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="form-check-input item-checkbox" data-id="2" data-name="Monitor" data-price="1200.00"></td>
                                <td>27" 4K Monitor</td>
                                <td>Electronics</td>
                                <td>8</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="addSelectedItems">
                    <i class="fas fa-plus me-2"></i>Add Selected Items
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-paper-plane fa-3x text-primary"></i>
                    </div>
                    <h5>Submit Purchase Requisition?</h5>
                    <p class="text-muted">Are you sure you want to submit this purchase requisition for approval? This action cannot be undone.</p>
                </div>
                <div class="bg-light p-3 rounded">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Items:</span>
                        <span id="modalItemCount">0</span>
                    </div>
                    <div id="modalItemDetails">
                        <!-- Item details will be populated here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSubmit">
                    <i class="fas fa-check me-2"></i>Yes, Submit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery loaded in main template -->
<!-- jQuery and SweetAlert2 loaded in main template -->
<!-- Select2 will be loaded in main template -->


<script>
    // Verify jQuery is loaded
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded!');
        alert('jQuery is not loaded! Action buttons will not work.');
    } else {
        // jQuery loaded successfully
    }
    
    // Also check if $ is available
    if (typeof $ === 'undefined') {
        console.error('$ is not defined!');
    } else {
        // $ is available
    }

    // Main document ready function
    $(document).ready(function() {
        // Initialize UI components first
        initializeUI();
        
        // Load departments and users for the form
        loadDepartments();
        loadUsers();
        
        // Load project managers and team leaders for the form
        loadProjectManagers();
        loadTeamLeaders();
        
        // Load statistics - use backup loader since main function may not exist
        setTimeout(() => {
            refreshStatistics();
        }, 1000);
        
        // Show empty state immediately, then load requisitions
        populateRequisitionsTable([]);
        loadRequisitions();
        
        // Add search functionality
        let searchTimeout;
        $('#requisitionSearch').on('input', function() {
            clearTimeout(searchTimeout);
            const searchValue = $(this).val();
            searchTimeout = setTimeout(function() {
                loadRequisitions(1, searchValue, perPage);
            }, 500); // Debounce search
        });
        
        // Add per page change functionality
        $('#perPageSelect').on('change', function() {
            const newPerPage = $(this).val();
            loadRequisitions(1, searchTerm, newPerPage);
        });
        
        // Add refresh functionality
        $('#refreshRequisitions').on('click', function() {
            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            
            loadRequisitions(currentPage, searchTerm, perPage);
            
            // Reset button after a delay
            setTimeout(() => {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Refresh');
            }, 1000);
        });
        
        // Also try loading departments after a delay
        setTimeout(function() {
            const departmentSelect = $('#department');
            if (departmentSelect.find('option').length <= 1) {
                console.log('Department select still empty after document ready, trying again...');
                loadDepartments();
            }
        }, 2000);
        
        // Search functionality for Bootstrap table
        $('#searchRequisitions').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#requisitionsTable tbody tr').each(function() {
                const rowText = $(this).text().toLowerCase();
                if (rowText.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Refresh button
        $('#refreshRequisitions').on('click', function() {
            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            
            // Reload requisitions
            loadRequisitions();
            
            // Reset button after a delay
            setTimeout(() => {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
            }, 1000);
        });
        
        // Handle modal events
        $('#viewRequisitionModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const requisitionId = button.data('id');
            const modal = $(this);
            
            // Show loading state
            modal.find('.modal-body').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            
            // Simulate API call to get requisition details
            setTimeout(() => {
                const requisitionData = {
                    id: requisitionId,
                    requisition_number: `PR-2023-${String(requisitionId).padStart(3, '0')}`,
                    date: '2023-11-15',
                    requested_by: 'John Doe',
                    department: 'IT Department',
                    status: 'Pending',
                    items: [
                        { name: 'Laptop', quantity: 2, unit: 'PCS', total: '2' },
                        { name: 'Mouse', quantity: 2, unit: 'PCS', total: '2' }
                    ],
                    total_items: '4',
                    notes: 'Urgent requirement for new employees.'
                };
                
                // Render requisition details
                renderRequisitionDetails(requisitionData);
            }, 800);
        });
        
        // Handle edit modal
        $('#editRequisitionModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const requisitionId = button.data('id');
            const modal = $(this);
            
            // Show loading state
            modal.find('.modal-body').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading edit form...</span>
                    </div>
                </div>
            `);
            
            // Simulate loading edit form
            setTimeout(() => {
                modal.find('.modal-body').html(`
                    <form id="editRequisitionForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Requisition Number</label>
                                <input type="text" class="form-control" value="PR-2023-${String(requisitionId).padStart(3, '0')}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" value="2023-11-15">
                            </div>
                        </div>
                        <!-- Add more form fields as needed -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" value="Laptop"></td>
                                        <td><input type="number" class="form-control" value="2" min="1"></td>
                                        <td><input type="text" class="form-control" value="PCS"></td>
                                        <td><button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" value="Mouse"></td>
                                        <td><input type="number" class="form-control" value="2" min="1"></td>
                                        <td><input type="text" class="form-control" value="PCS"></td>
                                        <td><button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-sm btn-success">
                                <i class="fas fa-plus me-1"></i> Add Item
                            </button>
                        </div>
                    </form>
                `);
            }, 800);
        });
        
        // Handle delete confirmation
        $('#deleteRequisitionModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const requisitionId = button.data('id');
            $('#requisitionToDelete').val(requisitionId);
        });
        
        // Confirm delete button
        $('#confirmDeleteBtn').on('click', function() {
            const requisitionId = $('#requisitionToDelete').val();
            const btn = $(this);
            const originalText = btn.html();
            
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...');
            
            // Simulate API call
            setTimeout(() => {
                $('#deleteRequisitionModal').modal('hide');
                showAlert('Success', 'Requisition has been deleted successfully', 'success');
                // Refresh the table or remove the row
                $(`button[data-id="${requisitionId}"]`).closest('tr').fadeOut(400, function() {
                    $(this).remove();
                });
                btn.prop('disabled', false).html(originalText);
            }, 1000);
        });
        
        // View requisition details
        $('.view-requisition').on('click', function() {
            const requisitionId = $(this).data('id');
            const modal = $('#viewRequisitionModal');
            
            // Show loading state
            modal.find('.modal-body').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            
            // Show modal
            modal.modal('show');
            
            // Make real API call to get requisition details
            $.ajax({
                url: `/company/warehouse/requisitions/show/${requisitionId}`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Modal API response:', response);
                    if (response.success && response.data) {
                        const requisition = response.data;
                        console.log('Requisition data:', requisition);
                        console.log('Requestor data:', requisition.requestor);
                        console.log('PersonalInfo data:', requisition.requestor?.personalInfo);
                        
                        // Get requestor name
                        let requestorName = 'N/A';
                        if (requisition.requestor && requisition.requestor.personal_info) {
                            const firstName = requisition.requestor.personal_info.first_name || '';
                            const lastName = requisition.requestor.personal_info.last_name || '';
                            requestorName = (firstName + ' ' + lastName).trim() || ('Employee #' + requisition.requestor.staff_id);
                            console.log('Using personal_info - firstName:', firstName, 'lastName:', lastName, 'result:', requestorName);
                        } else if (requisition.requestor && requisition.requestor.personalInfo) {
                            const firstName = requisition.requestor.personalInfo.first_name || '';
                            const lastName = requisition.requestor.personalInfo.last_name || '';
                            requestorName = (firstName + ' ' + lastName).trim() || ('Employee #' + requisition.requestor.staff_id);
                            console.log('Using personalInfo - firstName:', firstName, 'lastName:', lastName, 'result:', requestorName);
                        } else if (requisition.requestor && requisition.requestor.staff_id) {
                            requestorName = 'Employee #' + requisition.requestor.staff_id;
                            console.log('Using staff_id fallback:', requestorName);
                        } else {
                            console.log('No requestor data found');
                        }
                        
                        // Get department name
                        let departmentName = requisition.department || 'N/A';
                        
                        // Format items for display
                        const items = requisition.items || [];
                        const itemsHtml = items.map(item => `
                            <tr>
                                <td>${item.item_name || 'N/A'}</td>
                                <td class="text-end">${item.quantity || 0}</td>
                                <td>${item.unit || 'N/A'}</td>
                                <td class="text-end" style="display: none !important;">${(item.quantity * item.unit_price || 0).toFixed(2)}</td>
                            </tr>
                        `).join('');
                        
                        // Calculate total items
                        const totalItems = items.reduce((sum, item) => sum + (parseFloat(item.quantity) || 0), 0);
                        
                        // Format date
                        let formattedDate = 'N/A';
                        if (requisition.requisition_date) {
                            const date = new Date(requisition.requisition_date);
                            formattedDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: '2-digit'
                            });
                        }
                        
                        const requisitionData = {
                            id: requisition.id,
                            requisition_number: requisition.requisition_number || 'N/A',
                            date: formattedDate,
                            requested_by: requestorName,
                            department: departmentName,
                            status: requisition.status || 'Unknown',
                            items: items,
                            total_items: totalItems,
                            notes: requisition.notes || 'No notes'
                        };
                        
                        // Render requisition details
                        renderRequisitionDetails(requisitionData);
                    } else {
                        modal.find('.modal-body').html(`
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5>Error Loading Requisition</h5>
                                <p class="text-muted">Could not load requisition details.</p>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading requisition:', error);
                    modal.find('.modal-body').html(`
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                            <h5>Error Loading Requisition</h5>
                            <p class="text-muted">Could not load requisition details.</p>
                        </div>
                    `);
                }
            });
        });
        
        // Initialize action button handlers
        attachActionButtonHandlers();
        
    }); // End of document ready function
    
    // Function to attach action button handlers
    function attachActionButtonHandlers() {
        // Remove existing handlers to prevent duplicates
        $(document).off('click', '.view-requisition');
        $(document).off('click', '.edit-requisition');
        $(document).off('click', '.delete-requisition');
        
        // Try table-specific event delegation
        $('#requisitionsTable').on('click', '.view-requisition', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const requisitionId = $(this).data('id');
            
            if (!requisitionId) {
                return;
            }
            
            viewRequisition(requisitionId);
        });
        
        $('#requisitionsTable').on('click', '.edit-requisition', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const requisitionId = $(this).data('id');
            
            if ($(this).prop('disabled')) {
                return;
            }
            
            if (!requisitionId) {
                return;
            }
            
            editRequisition(requisitionId);
        });
        
        $('#requisitionsTable').on('click', '.delete-requisition', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const requisitionId = $(this).data('id');
            
            if ($(this).prop('disabled')) {
                return;
            }
            
            if (!requisitionId) {
                return;
            }
            
            deleteRequisition(requisitionId);
        });
        
        // ALSO keep the document-level handlers as backup
        // Removed duplicate global handlers to prevent conflicts and page refreshes
        
        // Action button handlers attached successfully
        
        // Test button removed - action buttons are now working
        
        // Test if buttons exist after attaching handlers
        setTimeout(() => {
            console.log('=== BUTTON INSPECTION ===');
            const viewButtons = $('.view-requisition');
            const editButtons = $('.edit-requisition');
            const deleteButtons = $('.delete-requisition');
            const allActionButtons = $('.view-requisition, .edit-requisition, .delete-requisition');
            
            console.log('Found buttons after attach:', {
                view: viewButtons.length,
                edit: editButtons.length,
                delete: deleteButtons.length,
                total: allActionButtons.length
            });
            
            // Inspect table structure
            const table = $('#requisitionsTable');
            const rows = table.find('tbody tr');
            console.log('Table found:', table.length > 0);
            console.log('Rows in table:', rows.length);
            
            if (rows.length > 0) {
                const firstRow = rows.first();
                const buttonsInFirstRow = firstRow.find('button');
                console.log('Buttons in first row:', buttonsInFirstRow.length);
                
                buttonsInFirstRow.each(function(index) {
                    console.log(`First row button ${index}:`, {
                        classes: $(this).attr('class'),
                        dataId: $(this).data('id'),
                        html: $(this)[0].outerHTML.substring(0, 100)
                    });
                });
            }
            
            // Removed auto-trigger debugging code
        }, 3000);
        
        // Clean up: Removed multiple duplicate event handlers that were causing conflicts
    }
    
    // Action button functions
    function viewRequisition(requisitionId) {
        console.log('View function called with ID:', requisitionId);
        
        if (!requisitionId) {
            console.error('No requisition ID provided to view function');
            return;
        }
        
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log('Making AJAX request to view requisition...');
        
        $.ajax({
            url: '/company/warehouse/requisitions/show/' + requisitionId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log('View response:', response);
                
                if (response.success && response.data) {
                    showRequisitionModal(response.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load requisition details'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading requisition:', error);
                console.error('Response:', xhr.responseText);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load requisition details'
                });
            }
        });
    }
    
    function editRequisition(requisitionId) {
        if (!requisitionId) {
            return;
        }
        
        
        // First, fetch the requisition data
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
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
        
        $.ajax({
            url: '/company/warehouse/requisitions/show/' + requisitionId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success && response.data) {
                    // Close loading and switch to Create Requisition tab with prefilled data
                    Swal.close();
                    editRequisitionInForm(response.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load requisition details for editing'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading requisition for edit:', error);
                console.error('Response:', xhr.responseText);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load requisition details for editing'
                });
            }
        });
    }
    
    // Function to populate form with requisition data for editing
    function editRequisitionInForm(requisition) {
        
        // First, close any existing SweetAlert
        Swal.close();
        
        // Reset the form to ensure clean state
        const form = document.getElementById('requisitionForm');
        if (form) {
            form.reset();
        } else {
            $('#requisitionForm input, #requisitionForm select, #requisitionForm textarea').val('');
        }
        
        // Clear any existing items and add one empty row
        clearAllItems();
        addItemRow(); // Add at least one empty item row
        
        
        // Force switch to Create Requisition tab - use jQuery method instead of Bootstrap
        $('#list-tab').removeClass('active').attr('aria-selected', 'false');
        $('#create-tab').addClass('active').attr('aria-selected', 'true');
        $('#requisition-list').removeClass('show active');
        $('#create-requisition').addClass('show active');
        
        // Add tab protection after a small delay to prevent immediate conflicts
        setTimeout(() => {
            $('.nav-link').off('click.edit-mode');
            $('#list-tab').on('click.edit-mode', function(e) {
                // Check if we're actually in edit mode
                if (window.editingRequisitionId && $('#create-tab').hasClass('active')) {
                    e.preventDefault();
                    e.stopPropagation();
                    Swal.fire({
                        title: 'Exit Edit Mode?',
                        text: 'You are currently editing a requisition. Do you want to exit edit mode?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Exit Edit',
                        cancelButtonText: 'Stay in Edit'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            cancelEdit();
                        }
                    });
                    return false;
                } else {
                    // Not in edit mode, allow normal tab switching and clean up
                    exitEditMode();
                }
            });
        }, 500);
        
        
        // Add a small delay to ensure tab switch completes
        setTimeout(() => {
        
        $('#title').val(requisition.title || '');
        
        // Set reference code if there's a hidden field for it
        if ($('#referenceCode').length) {
            $('#referenceCode').val(requisition.reference_code || '');
        }
        
        // Set form values with additional delay for select elements to load their options
        setTimeout(() => {
            // Debug: Log the requisition data to see what we're working with
            console.log('Requisition data for edit:', {
                department_id: requisition.department_id,
                department: requisition.department,
                departmentCategory: requisition.departmentCategory,
                sub_departments: requisition.departmentCategory ? requisition.departmentCategory.sub_departments : 'No departmentCategory'
            });
            
            // Use the department name to find the correct option in the dropdown
            if (requisition.department && typeof requisition.department === 'string') {
                console.log('Looking for department by name:', requisition.department);
                
                // Function to find and set department
                function findAndSetDepartment() {
                    let foundOption = null;
                    console.log('Current department options:', $('#department option').map(function() { 
                        return { value: $(this).val(), text: $(this).text().trim() }; 
                    }).get());
                    
                    $('#department option').each(function() {
                        if ($(this).text().trim() === requisition.department.trim()) {
                            foundOption = $(this).val();
                            console.log('Found department option by name:', foundOption);
                            return false; // break the loop
                        }
                    });
                    
                    if (foundOption) {
                        $('#department').val(foundOption).trigger('change');
                        console.log('Set department dropdown to:', foundOption);
                        return true;
                    }
                    return false;
                }
                
                // Try to find the department immediately
                if (!findAndSetDepartment()) {
                    // If not found, wait a bit more and try again
                    setTimeout(() => {
                        if (!findAndSetDepartment()) {
                            // If still not found, fetch departments and try again
                            console.log('Department not found in current options, fetching departments...');
                            $.ajax({
                                url: '/warehouse/requisitions/departments',
                                method: 'GET',
                                success: function(response) {
                                    if (response.success && response.departments) {
                                        console.log('Fetched departments:', response.departments);
                                        // Look for the department name in the API response
                                        for (let dept of response.departments) {
                                            if (dept.name === requisition.department) {
                                                console.log('Found department in API response:', dept);
                                                $('#department').val(dept.id).trigger('change');
                                                break;
                                            }
                                        }
                                    }
                                },
                                error: function() {
                                    console.log('Failed to fetch departments for fallback');
                                }
                            });
                        }
                    }, 1000); // Wait 1 second before trying again
                }
            } else {
                // Fallback to department_id if no department name
                $('#department').val(requisition.department_id).trigger('change');
            }
            $('#projectManager').val(requisition.project_manager_id || '').trigger('change');
            $('#teamLeader').val(requisition.team_leader_id || '').trigger('change');
            $('#requestedBy').val(requisition.requester_id || '').trigger('change');
            $('#priority').val(requisition.priority || 'medium').trigger('change');
        }, 500); // Increased delay for select options to load
        // Format dates for HTML date inputs (YYYY-MM-DD)
        let formattedRequiredDate = '';
        let formattedRequisitionDate = '';
        
        if (requisition.required_date) {
            const reqDate = new Date(requisition.required_date);
            formattedRequiredDate = reqDate.toISOString().split('T')[0];
        }
        
        if (requisition.requisition_date) {
            const requisitionDate = new Date(requisition.requisition_date);
            formattedRequisitionDate = requisitionDate.toISOString().split('T')[0];
        }
        
        $('#requiredDate').val(formattedRequiredDate);
        $('#requisitionDate').val(formattedRequisitionDate);
        $('#notes').val(requisition.notes || '');
        
        // Ensure the form is visible and scroll to it
        $('#create-requisition').show();
        $('#requisitionForm').show();
        
        // Scroll to the form
        $('html, body').animate({
            scrollTop: $('#create-requisition').offset().top - 100
        }, 300);
        
        
        // Set editing mode
        window.editingRequisitionId = requisition.id;
        
        // Update form title and buttons
        $('.card-title').first().text('Edit Requisition - ' + (requisition.requisition_number || ''));
        $('#submitBtn').html('<i class="fas fa-save"></i> Update Requisition');
        $('#saveDraftBtn').hide(); // Hide save draft when editing
        
        
        // Add cancel edit button and enhance item management
        if (!$('#cancelEditBtn').length) {
            $('#saveDraftBtn').after('<button type="button" id="cancelEditBtn" class="btn btn-secondary me-2"><i class="fas fa-times"></i> Cancel Edit</button>');
            
            $('#cancelEditBtn').on('click', function() {
                Swal.fire({
                    title: 'Cancel Edit?',
                    text: 'Any unsaved changes will be lost.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Cancel',
                    cancelButtonText: 'No, Stay',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        cancelEdit();
                    }
                });
            });
        }
        
        // Show item management buttons when editing
        $('.add-item-btn, .remove-item-btn').show();
        
        // Enable all form fields for editing (they should all be editable when status is pending)
        $('#requisitionForm input, #requisitionForm select, #requisitionForm textarea').prop('disabled', false);
        
        // Enhance item management for edit mode
        enhanceEditItemManagement();
        
        // Populate items (already cleared at the beginning)
        
        if (requisition.items && Array.isArray(requisition.items)) {
            console.log('Found items to populate:', requisition.items);
            
            // First, load available items into the dropdown
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log('Loading available items...');
            
            $.ajax({
                url: '/company/warehouse/central-store/available-items-for-requisition',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log('Items loaded successfully:', response);
                    if (response.success && response.data) {
                        console.log('Found', response.data.length, 'items to load');
                        
                        // Load items into all item select dropdowns
                        $('.item-select').each(function() {
                            const selectElement = $(this);
                            console.log('Loading items into select:', selectElement);
                            // Clear existing options except the first one
                            selectElement.find('option:not(:first)').remove();
                            
                            // Add new options
                            response.data.forEach(function(item) {
                                
                                // Clean and escape the item data to prevent JSON parsing errors
                                const cleanItem = {
                                    id: item.id,
                                    name: item.name || '',
                                    unit: item.unit || '',
                                    unit_price: item.unit_price || '',
                                    quantity: item.quantity_available || 0,
                                    quantity_available: item.quantity_available || 0,
                                    current_available_quantity: item.quantity_available || 0,
                                    category_name: item.category_name || item.category || 'Unknown',
                                    description: (item.description || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/\n/g, ' ').replace(/\r/g, ' ')
                                };
                                
                                const option = $('<option>', {
                                    value: item.id,
                                    text: item.name + ' - ' + (item.category_name || item.category || 'Unknown') + ' (Available: ' + (item.quantity_available || 0) + ' ' + (item.unit || '') + ')',
                                    'data-item-data': JSON.stringify(cleanItem)
                                });
                                selectElement.append(option);
                            });
                        });
                        
                        console.log('Items loaded into all dropdowns, now populating rows...');
                        
                        // Now populate the rows with the requisition items
            requisition.items.forEach(function(item, index) {
                if (index === 0) {
                    // Use first existing row
                                const firstRow = $('#itemsContainer .item-row').first();
                                firstRow.addClass('existing-item');
                                populateItemRow(firstRow, item);
                } else {
                    // Add new rows for additional items
                    addItemRow();
                                const lastRow = $('#itemsContainer .item-row').last();
                                lastRow.addClass('existing-item');
                                populateItemRow(lastRow, item);
                            }
                        });
                    } else {
                        console.error('Failed to load items - no data');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading items:', error);
                }
            });
        }
        
        // Handle existing attachments
        if (requisition.attachments && requisition.attachments.length > 0) {
            const filePreview = $('#filePreview');
            const noFilesMessage = $('#noFilesMessage');
            
            // Clear existing previews
            filePreview.empty();
            
            // Add existing attachments to preview
            requisition.attachments.forEach(function(attachment, index) {
                const fileName = attachment.split('/').pop();
                const fileUrl = '/storage/' + attachment;
                
                const previewItem = $(`
                    <div class="file-preview-item" data-index="${index}" data-existing="true" data-path="${attachment}">
                        <div class="file-info">
                            <i class="fas fa-file me-2"></i>
                            <span class="file-name">${fileName}</span>
                            <small class="text-muted d-block">Existing attachment</small>
                        </div>
                        <div class="file-actions">
                            <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-info me-1" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger file-remove" title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `);
                
                filePreview.append(previewItem);
            });
            
            // Hide no files message if we have attachments
            if (requisition.attachments.length > 0) {
                noFilesMessage.hide();
            }
        }
        
            
            // Form should now be populated and ready for editing
        }, 100); // Reduced delay since we're forcing tab switch
    }
    
    // Function to populate an item row with data
    function populateItemRow(row, itemData) {
        console.log('populateItemRow called with:', itemData);
        console.log('itemData.current_available_quantity:', itemData.current_available_quantity);
        console.log('window.editingRequisitionId:', window.editingRequisitionId);
        
        // Set the item selection without triggering change event for existing items
        row.find('.item-select').val(itemData.item_id || '');
        
        // Only trigger change for new items, not when editing existing requisitions
        if (!window.editingRequisitionId) {
            row.find('.item-select').trigger('change');
        }
        
        // Set hidden fields
        row.find('input[name="item_id[]"]').val(itemData.item_id || '');
        row.find('input[name="item_name[]"]').val(itemData.item_name || '');
        
        // Set unit price
        row.find('.price-display').val(itemData.unit_price || '');
        row.find('input[name="item_unit_price[]"]').val(itemData.unit_price || '');
        
        // Set unit
        row.find('.unit-display').val(itemData.unit || '');
        row.find('input[name="item_unit[]"]').val(itemData.unit || '');
        
        // Get current available quantity from inventory
        let availableQty = '0';
        
        if (window.editingRequisitionId) {
            // For existing items, ALWAYS use the current available quantity from backend
            if (itemData.current_available_quantity !== undefined) {
                availableQty = itemData.current_available_quantity.toString();
                console.log('Using current available quantity for existing item from backend:', availableQty);
            } else {
                console.error('No current_available_quantity found in itemData:', itemData);
                availableQty = '0';
            }
        } else {
            // For new items, get current available quantity from dropdown
            const selectedOption = row.find('.item-select option:selected');
            if (selectedOption.length > 0) {
                try {
                    const optionData = JSON.parse(selectedOption.attr('data-item-data') || '{}');
                    availableQty = optionData.quantity_available || optionData.current_available_quantity || optionData.quantity || '0';
                    console.log('Available quantity from option data for new item:', availableQty);
                } catch (e) {
                    console.error('Error parsing option data:', e);
                    availableQty = '0';
                }
            }
        }
        
        row.find('input[name="item_available_qty[]"]').val(availableQty);
        row.find('.available-qty-text').text('Available: ' + availableQty);
        
        // Set quantity AFTER getting available quantity to avoid conflicts
        // Use the original requested quantity from the requisition
        const requestedQuantity = itemData.quantity || 0;
        row.find('.quantity').val(requestedQuantity);
        row.find('input[name="item_quantity[]"]').val(requestedQuantity);
        
        // Set max attribute to current available quantity
        row.find('input[name="item_quantity[]"]').attr('max', availableQty);
        
        // Set total
        const total = requestedQuantity * (itemData.unit_price || 0);
        row.find('input[name="item_total[]"]').val(total.toFixed(2));
        
        console.log('Item data populated:', {
            id: itemData.item_id,
            name: itemData.item_name,
            quantity: itemData.quantity,
            unit_price: itemData.unit_price,
            unit: itemData.unit,
            available: availableQty
        });
    }
    
    // Function to load available items for all dropdowns
    function loadAvailableItems() {
        console.log('loadAvailableItems function called');
        return new Promise((resolve, reject) => {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log('Making AJAX request to load items...');
            
            $.ajax({
                url: '/company/warehouse/central-store/available-items-for-requisition',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log('Items loaded successfully:', response);
                    if (response.success && response.data) {
                        console.log('Found', response.data.length, 'items to load');
                        // Load items into all item select dropdowns
                        $('.item-select').each(function() {
                            const selectElement = $(this);
                            console.log('Loading items into select:', selectElement);
                            // Clear existing options except the first one
                            selectElement.find('option:not(:first)').remove();
                            
                            // Add new options
                            response.data.forEach(function(item) {
                                
                                // Clean and escape the item data to prevent JSON parsing errors
                                const cleanItem = {
                                    id: item.id,
                                    name: item.name || '',
                                    unit: item.unit || '',
                                    unit_price: item.unit_price || '',
                                    quantity: item.quantity_available || 0,
                                    quantity_available: item.quantity_available || 0,
                                    current_available_quantity: item.quantity_available || 0,
                                    category_name: item.category_name || item.category || 'Unknown',
                                    description: (item.description || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/\n/g, ' ').replace(/\r/g, ' ')
                                };
                                
                                const option = $('<option>', {
                                    value: item.id,
                                    text: item.name + ' - ' + (item.category_name || item.category || 'Unknown') + ' (Available: ' + (item.quantity_available || 0) + ' ' + (item.unit || '') + ')',
                                    'data-item-data': JSON.stringify(cleanItem)
                                });
                                selectElement.append(option);
                            });
                        });
                        
                        console.log('Items loaded into all dropdowns');
                        resolve();
                    } else {
                        console.error('Failed to load items - no data');
                        reject('Failed to load items');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading items:', error);
                    reject('Error loading items: ' + error);
                }
            });
        });
    }
    
    // Function to reset edit form without leaving edit mode
    function resetEditForm() {
        // Keep the editing state but reset UI to allow further edits
        $('.card-title').first().text('Edit Requisition - Updated Successfully');
        $('#submitBtn').html('<i class="fas fa-save"></i> Update Requisition');
        $('#saveDraftBtn').hide();
        
        // Re-enable form elements
        $('#requisitionForm input, #requisitionForm select, #requisitionForm textarea').prop('disabled', false);
        
        // Re-enable item management buttons
        $('.remove-item-btn, .add-item-btn').prop('disabled', false).show();
        
        // Reset submit button state
        $('#submitBtn').prop('disabled', false);
        
        // Refresh item management UI
        enhanceEditItemManagement();
    }
    
    // Function to completely exit edit mode and clean up
    function exitEditMode() {
        // Clear edit state completely
        window.editingRequisitionId = null;
        
        // Remove all edit mode protections
        $('.nav-link').off('click.edit-mode');
        
        // Reset UI to normal state
        $('.card-title').first().text('Create New Requisition');
        $('#submitBtn').html('<i class="fas fa-paper-plane"></i> Submit Requisition');
        $('#saveDraftBtn').show();
        $('#cancelEditBtn').remove();
        
        // Clear form safely
        const form = document.getElementById('requisitionForm');
        if (form) {
            form.reset();
        } else {
            $('#requisitionForm input, #requisitionForm select, #requisitionForm textarea').val('');
        }
        
        clearAllItems();
        initializeForm();
        enhanceEditItemManagement();
    }
    
    // Function to cancel edit mode
    function cancelEdit() {
        exitEditMode();
        
        // Switch back to list tab
        $('#list-tab').removeClass('').addClass('active').attr('aria-selected', 'true');
        $('#create-tab').removeClass('active').attr('aria-selected', 'false');
        $('#create-requisition').removeClass('show active');
        $('#requisition-list').addClass('show active');
    }
    
    function deleteRequisition(requisitionId) {
        
        if (!requisitionId) {
            return;
        }
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! The inventory quantities will be restored.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                console.log('User confirmed deletion, proceeding...');
                
                // Show loading state
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the requisition and restore inventory.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: '/company/warehouse/requisitions/' + requisitionId,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        console.log('Delete response:', response);
                        
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message || 'Requisition has been deleted and inventory restored.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Reload the requisitions list
                                loadRequisitions(currentPage, searchTerm, perPage);
                                
                                // Refresh statistics
                                refreshStatistics();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Failed to delete requisition.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete error:', error);
                        console.error('Response:', xhr.responseText);
                        
                        let errorMessage = 'An error occurred while deleting the requisition.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    }
    
    function showRequisitionModal(requisition) {
        console.log('Showing modal for requisition:', requisition);
        
        // Format the dates
        let formattedRequisitionDate = 'N/A';
        let formattedRequiredDate = 'N/A';
        let formattedCreatedDate = 'N/A';
        
        if (requisition.requisition_date) {
            const date = new Date(requisition.requisition_date);
            formattedRequisitionDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: '2-digit'
            });
        }
        
        if (requisition.required_date) {
            const date = new Date(requisition.required_date);
            formattedRequiredDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: '2-digit'
            });
        }
        
        if (requisition.created_at) {
            const date = new Date(requisition.created_at);
            formattedCreatedDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        // Build items table
        let itemsHtml = '';
        if (requisition.items && requisition.items.length > 0) {
            requisition.items.forEach(item => {
                itemsHtml += `
                    <tr>
                        <td>${item.item_name || 'N/A'}</td>
                        <td class="text-center">${item.quantity || 0}</td>
                        <td class="text-center">${item.unit || 'N/A'}</td>
                        <td class="text-end" style="display: none !important;">GH₵${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                        <td class="text-end" style="display: none !important;">GH₵${(parseFloat(item.quantity || 0) * parseFloat(item.unit_price || 0)).toFixed(2)}</td>
                    </tr>
                `;
            });
        } else {
            itemsHtml = '<tr><td colspan="3" class="text-center text-muted">No items found</td></tr>';
        }
        
        const modalHtml = `
            <div class="modal fade" id="viewRequisitionModal" tabindex="-1" aria-labelledby="viewRequisitionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewRequisitionModalLabel">Requisition Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Requisition Number:</strong><br>
                                    <span class="text-primary">${requisition.requisition_number || 'N/A'}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Status:</strong><br>
                                    ${getStatusBadge(requisition.status)}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Requestor:</strong><br>
                                    ${requisition.requestor && requisition.requestor.personal_info ? 
                                        ((requisition.requestor.personal_info.first_name || '') + ' ' + (requisition.requestor.personal_info.last_name || '')).trim() || ('Employee #' + requisition.requestor.staff_id) :
                                        (requisition.requestor && requisition.requestor.personalInfo ? 
                                            ((requisition.requestor.personalInfo.first_name || '') + ' ' + (requisition.requestor.personalInfo.last_name || '')).trim() || ('Employee #' + requisition.requestor.staff_id) :
                                            (requisition.requestor && requisition.requestor.staff_id ? 'Employee #' + requisition.requestor.staff_id : 'N/A')
                                        )
                                    }
                                </div>
                                <div class="col-md-6">
                                    <strong>Department:</strong><br>
                                    ${requisition.department || 'N/A'}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Priority:</strong><br>
                                    ${getPriorityBadge(requisition.priority)}
                                </div>
                                <div class="col-md-6">
                                    <strong>Requisition Date:</strong><br>
                                    ${formattedRequisitionDate}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Required Date:</strong><br>
                                    ${formattedRequiredDate}
                                </div>
                                <div class="col-md-6">
                                    <strong>Date Created:</strong><br>
                                    ${formattedCreatedDate}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Title:</strong><br>
                                    <span class="text-primary">${requisition.title || 'No Title'}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Notes:</strong><br>
                                    <p class="text-muted mb-0">${requisition.notes || 'No notes provided'}</p>
                                </div>
                            </div>
                            ${requisition.attachments && requisition.attachments.length > 0 ? `
                            <div class="row mb-3">
                                <div class="col-12">
                                    <strong>Attachments:</strong>
                                    <div class="mt-2">
                                        ${requisition.attachments.map(attachment => {
                                            const fileName = attachment.split('/').pop();
                                            const fileUrl = '/storage/' + attachment;
                                            return `
                                                <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                                                    <div>
                                                        <i class="fas fa-file me-2"></i>
                                                        <span>${fileName}</span>
                                                    </div>
                                                    <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-download me-1"></i>Download
                                                    </a>
                                                </div>
                                            `;
                                        }).join('')}
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                            <div class="row">
                                <div class="col-12">
                                    <strong>Items:</strong>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Item Name</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-center">Unit</th>
                                                    <th class="text-end" style="display: none !important;">Unit Price</th>
                                                    <th class="text-end" style="display: none !important;">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${itemsHtml}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            ${requisition.status === 'pending' || requisition.status === 'partially_approved' ? `<button type="button" class="btn btn-primary" onclick="editRequisition(${requisition.id})">Edit</button>` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        $('#viewRequisitionModal').remove();
        
        // Add modal to body and show
        $('body').append(modalHtml);
        $('#viewRequisitionModal').modal('show');
    }
        
        // Function to render requisition details
        function renderRequisitionDetails(data) {
            const statusClass = {
                'Pending': 'bg-warning',
                'Partially Approved': 'bg-warning',
                'Approved': 'bg-success',
                'Rejected': 'bg-danger',
                'Completed': 'bg-info'
            }[data.status] || 'bg-secondary';
            
            // For partially approved requisitions, show item status
            const itemsHtml = data.items.map(item => {
                let itemStatus = '';
                let rowClass = '';
                
                if (data.status === 'Partially Approved' || data.status === 'partially_approved') {
                    // Check if this item was approved or is pending re-order
                    if (item.status === 'approved') {
                        itemStatus = '<span class="badge bg-success"><i class="fas fa-check"></i> Approved</span>';
                        rowClass = 'table-success';
                    } else if (item.status === 'partial') {
                        itemStatus = '<span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Partially Approved</span>';
                        rowClass = 'table-warning';
                    } else {
                        itemStatus = '<span class="badge bg-danger"><i class="fas fa-clock"></i> Pending Re-order</span>';
                        rowClass = 'table-danger';
                    }
                }
                
                return `
                    <tr class="${rowClass}">
                        <td>
                            ${item.name}
                            ${itemStatus}
                        </td>
                        <td class="text-end">
                            <strong>${item.quantity}</strong>
                            ${data.status === 'Partially Approved' || data.status === 'partially_approved' ? 
                                (item.quantity_approved > 0 ? `<br><small class="text-success"><i class="fas fa-check"></i> ${item.quantity_approved} approved</small>` : '') +
                                (item.quantity_pending > 0 ? `<br><small class="text-warning"><i class="fas fa-clock"></i> ${item.quantity_pending} pending re-order</small>` : '') : ''}
                        </td>
                        <td>${item.unit}</td>
                    </tr>
                `;
            }).join('');
            
            const html = `
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">${data.requisition_number}</h4>
                        <span class="badge ${statusClass}">${data.status}</span>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Date:</strong> ${data.date}</p>
                            <p class="mb-1"><strong>Requested By:</strong> ${data.requested_by}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Department:</strong> ${data.department}</p>
                        </div>
                    </div>
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-end">Qty</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th colspan="3" class="text-end">Total Items:</th>
                                    <th class="text-end">${data.total_items}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    ${data.notes ? `
                        <div class="card bg-light">
                            <div class="card-header">
                                <strong>Notes:</strong>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">${data.notes}</p>
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
            
            $('#viewRequisitionModal .modal-body').html(html);
        }
    
    // Form validation
    (function() {
        'use strict';
        
        // Fetch all forms with validation
        var forms = document.querySelectorAll('.needs-validation');
        
        // Add validation to each form
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    

    // Initialize UI components
    function initializeUI() {
        console.log('initializeUI function called');
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('.modal').length ? $('.modal') : document.body
        });
        
        // Initialize date pickers (commented out - library not loaded)
        // $('.datepicker').datepicker({
        //     format: 'yyyy-mm-dd',
        //     autoclose: true
        // });
        
        // Initialize all components
        // initializePurchaseOrders(); // Function not defined yet
        // initializeEventHandlers(); // Function not defined yet
        
        // Set default dates
        const today = new Date().toISOString().split('T')[0];
        $('#requisitionDate').val(today);
        
        const nextWeek = new Date();
        nextWeek.setDate(nextWeek.getDate() + 7);
        $('#requiredDate').val(nextWeek.toISOString().split('T')[0]);
        
        // Ensure required by date is not before requisition date
        $('#requisitionDate, #requiredDate').on('change', function() {
            const reqDate = new Date($('#requisitionDate').val());
            const reqByDate = new Date($('#requiredDate').val());
            
            if (reqByDate < reqDate) {
                // If required by date is before requisition date, set it to requisition date
                $('#requiredDate').val($('#requisitionDate').val());
                
                // Show error message
                const errorDiv = $('<div class="invalid-feedback d-block">Required by date cannot be before requisition date</div>');
                $('#requiredDate').closest('.input-group').after(errorDiv);
                
                // Remove error after 3 seconds
                setTimeout(() => {
                    errorDiv.fadeOut(300, function() { $(this).remove(); });
                }, 3000);
            }
        });
        
        // Open item selection modal or file upload
        $('#addItemBtn').on('click', function() {
            console.log('Add Item button clicked');
            $('#itemSelectionModal').modal('show');
        });
        
        $('#browseFilesBtn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const fileInput = document.getElementById('fileUpload');
            if (fileInput) {
                fileInput.click();
            }
        });
        
        // Handle file upload container click
        $('#fileUploadContainer').on('click', function(e) {
            if (!$(e.target).closest('.file-remove').length) {
                $('#fileUpload').click();
            }
        });
        
        // Initialize with one empty item row
        if ($('.item-row').length === 0) {
            console.log('Creating initial item row...');
            addItemRow();
            console.log('Initial item row created, checking if items are loaded...');
            
            // Add a small delay to ensure DOM is ready, then load items for initial row
            setTimeout(function() {
                const initialSelect = $('.item-row:first .item-select');
                console.log('Looking for initial select element...');
                console.log('Found select elements:', $('.item-select').length);
                console.log('First select element:', initialSelect);
                if (initialSelect.length > 0) {
                    console.log('Loading items for initial select element...');
                    loadAvailableItems(initialSelect);
                } else {
                    console.log('Initial select element not found!');
                }
            }, 1000);
        }
        
        console.log('initializeUI function completed successfully');
        
        
        // Handle file upload change
        $('#fileUpload').on('change', function() {
            handleFileUpload(this.files);
        });
        
        // Global file remove handler (using event delegation)
        $(document).on('click', '.file-remove', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const filePreviewItem = $(this).closest('.file-preview-item');
            const fileName = filePreviewItem.data('file-name');
            const filePath = filePreviewItem.data('path');
            const isExisting = filePreviewItem.data('existing');
            
            
            if (isExisting) {
                // For existing files, just remove from preview (backend will handle via existing_attachments)
                filePreviewItem.fadeOut(300, function() {
                    $(this).remove();
                    
                    // Show no files message if no files left
                    if ($('.file-preview-item').length === 0) {
                        $('#noFilesMessage').fadeIn(200);
                    }
                });
            } else {
                // For new files, remove from the file input
                const dt = new DataTransfer();
                const input = document.getElementById('fileUpload');
                
                // Remove file from the input
                for (let i = 0; i < input.files.length; i++) {
                    if (input.files[i].name !== fileName) {
                        dt.items.add(input.files[i]);
                    }
                }
                
                input.files = dt.files;
                filePreviewItem.fadeOut(300, function() {
                    $(this).remove();
                    
                    // Show no files message if no files left
                    if ($('.file-preview-item').length === 0) {
                        $('#noFilesMessage').fadeIn(200);
                    }
                });
            }
        });
        
        // Handle drag and drop for file upload
        const dropArea = document.getElementById('fileUploadContainer');
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        // Remove highlight when item is dragged away
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);
        
        // Drag and drop helper functions
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlight() {
            dropArea.classList.add('border-primary', 'bg-light');
        }
        
        function unhighlight() {
            dropArea.classList.remove('border-primary', 'bg-light');
        }
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFileUpload(files);
        }
        
        // Helper function to check if an item already exists in other rows
        function checkForDuplicateItem(itemId, currentRow) {
            let existingRow = null;
            
            $('#itemsContainer .item-row').each(function() {
                const row = $(this);
                // Skip the current row we're checking
                if (row.is(currentRow)) {
                    return true; // Continue to next iteration
                }
                
                // Check if this row has the same item ID
                const rowItemId = row.find('input[name="item_id[]"]').val();
                if (rowItemId && rowItemId == itemId) {
                    existingRow = row;
                    return false; // Break out of the loop
                }
            });
            
            return existingRow;
        }
        
        // Helper function to consolidate quantities when duplicate item is selected
        function consolidateItemQuantities(existingRow, duplicateRow, itemData) {
            try {
                // Get current quantity from existing row
                const existingQuantity = parseFloat(existingRow.find('input[name="item_quantity[]"]').val()) || 0;
                
                // Get quantity from the duplicate row (if any was entered)
                let duplicateQuantity = parseFloat(duplicateRow.find('input[name="item_quantity[]"]').val()) || 0;
                
                // Only add quantity if there was actually a quantity entered in the duplicate row
                if (duplicateQuantity > 0) {
                    // Calculate new total quantity
                    const newTotalQuantity = existingQuantity + duplicateQuantity;
                    
                    console.log(`Consolidating quantities: ${existingQuantity} + ${duplicateQuantity} = ${newTotalQuantity}`);
                    
                    // Get available quantity for validation
                    const availableQty = parseFloat(existingRow.find('input[name="item_available_qty[]"]').val()) || 
                                       parseFloat(duplicateRow.find('input[name="item_available_qty[]"]').val()) || 0;
                    
                    // Final validation - make sure consolidated quantity doesn't exceed available
                    let finalQuantity = newTotalQuantity;
                    if (availableQty > 0 && newTotalQuantity > availableQty) {
                        finalQuantity = availableQty;
                        console.log(`Quantity adjusted from ${newTotalQuantity} to ${finalQuantity} due to stock limit`);
                    }
                    
                    // Update the existing row with the new quantity
                    existingRow.find('input[name="item_quantity[]"]').val(finalQuantity);
                    
                    // Recalculate the total for the existing row
                    const unitPrice = parseFloat(existingRow.find('input[name="item_unit_price[]"]').val()) || 
                                     parseFloat(duplicateRow.find('input[name="item_unit_price[]"]').val()) || 0;
                    const newTotal = finalQuantity * unitPrice;
                    existingRow.find('input[name="item_total[]"]').val(newTotal.toFixed(2));
                    
                    // Update the total display if it exists
                    existingRow.find('.total-display').val(newTotal.toFixed(2));
                    
                    // Show consolidation message
                    toastr.info(`Quantity added! Total quantity for "${itemData.name}" is now ${finalQuantity}`, 'Items Merged');
                } else {
                    // No quantity to add, just removing duplicate
                    console.log(`Just removing duplicate item "${itemData.name}", no quantity to add`);
                }
                
                // Reset the duplicate row to default state
                resetRowToDefault(duplicateRow);
                
                // Recalculate the grand total
                calculateGrandTotal();
                
                // Focus on the existing row to highlight where the item is
                existingRow.find('input[name="item_quantity[]"]').focus();
                
            } catch (error) {
                console.error('Error consolidating quantities:', error);
                toastr.error('Error consolidating item quantities', 'Error');
            }
        }
        
        // Helper function to get all currently selected item IDs
        function getSelectedItemIds() {
            const selectedIds = [];
            console.log('=== GETTING SELECTED ITEM IDS ===');
            $('#itemsContainer .item-row').each(function(index) {
                const itemId = $(this).find('input[name="item_id[]"]').val();
                const itemName = $(this).find('input[name="item_name[]"]').val();
                console.log(`Row ${index}: itemId="${itemId}", itemName="${itemName}"`);
                if (itemId && itemId.trim() !== '') {
                    selectedIds.push(itemId);
                    console.log(`Added to selected list: ID=${itemId}, Name=${itemName}`);
                }
            });
            console.log('Final selected IDs array:', selectedIds);
            console.log('=== END GETTING SELECTED ITEM IDS ===');
            return selectedIds;
        }
        
        // Helper function to update all dropdowns to hide already selected items
        function updateAllDropdowns() {
            const selectedIds = getSelectedItemIds();
            console.log('=== UPDATING ALL DROPDOWNS ===');
            console.log('Selected IDs to disable:', selectedIds);
            
            const dropdowns = $('#itemsContainer .item-select');
            console.log('Found dropdowns:', dropdowns.length);
            
            dropdowns.each(function(index) {
                const currentSelect = $(this);
                const currentSelectedId = currentSelect.val();
                console.log(`Processing dropdown ${index}, current value: "${currentSelectedId}"`);
                
                // Count options before
                const totalOptions = currentSelect.find('option').length;
                console.log(`Dropdown ${index} has ${totalOptions} options`);
                
                // Enable all options first
                currentSelect.find('option').prop('disabled', false);
                console.log(`Dropdown ${index}: Enabled all options`);
                
                // Disable options that are selected in other rows
                let disabledCount = 0;
                selectedIds.forEach(function(selectedId) {
                    if (selectedId !== currentSelectedId && selectedId !== '') {
                        const optionToDisable = currentSelect.find(`option[value="${selectedId}"]`);
                        if (optionToDisable.length > 0) {
                            optionToDisable.prop('disabled', true);
                            disabledCount++;
                            console.log(`Dropdown ${index}: Disabled option with value "${selectedId}"`);
                        } else {
                            console.log(`Dropdown ${index}: Option with value "${selectedId}" not found`);
                        }
                    }
                });
                
                console.log(`Dropdown ${index}: Disabled ${disabledCount} options`);
                
                // Refresh Select2 if it exists
                if (currentSelect.hasClass('select2-hidden-accessible')) {
                    console.log(`Dropdown ${index}: Refreshing Select2`);
                    currentSelect.select2('destroy');
                    currentSelect.select2({
                        placeholder: 'Select an item...',
                        allowClear: true,
                        width: '100%'
                    });
                } else {
                    console.log(`Dropdown ${index}: No Select2 detected`);
                }
            });
            
            console.log('=== DROPDOWN UPDATE COMPLETE ===');
        }
        
        // Helper function to restore all options to dropdowns (used when items are deselected)
        function restoreAllOptions() {
            console.log('Restoring all options to dropdowns');
            
            // Counter to track how many dropdowns are being reloaded
            let dropdownsToReload = $('#itemsContainer .item-select').length;
            let dropdownsReloaded = 0;
            
            if (dropdownsToReload === 0) return;
            
            // Reload items for all dropdowns
            $('#itemsContainer .item-select').each(function() {
                const selectElement = $(this);
                const currentValue = selectElement.val();
                
                // Store current value in data attribute temporarily
                selectElement.data('temp-value', currentValue);
                
                // Reload the dropdown with all items
                loadAvailableItems(selectElement);
            });
            
            // After a delay, restore selections and filter
            setTimeout(() => {
                $('#itemsContainer .item-select').each(function() {
                    const selectElement = $(this);
                    const tempValue = selectElement.data('temp-value');
                    
                    // Restore the selection if it was set
                    if (tempValue) {
                        selectElement.val(tempValue);
                    }
                    
                    // Clear temp data
                    selectElement.removeData('temp-value');
                });
                
                // Now filter out selected items
                setTimeout(() => updateAllDropdowns(), 200);
            }, 1000);
        }
        
        // Helper function to reset a row to its default state
        function resetRowToDefault(row) {
            // Reset select dropdown
            row.find('.item-select').val('').trigger('change.select2');
            
            // Clear all input fields
            row.find('input[name="item_id[]"]').val('');
            row.find('input[name="item_name[]"]').val('');
            row.find('input[name="item_unit[]"]').val('');
            row.find('input[name="item_unit_price[]"]').val('');
            row.find('input[name="item_quantity[]"]').val('');
            row.find('input[name="item_total[]"]').val('');
            row.find('input[name="item_available_qty[]"]').val('');
            
            // Clear display fields
            row.find('.unit-display').val('');
            row.find('.price-display').val('');
            row.find('.total-display').val('');
            row.find('.available-qty-text').text('Available: 0');
            
            // Remove max attribute from quantity input
            row.find('input[name="item_quantity[]"]').removeAttr('max');
            
            // Update all dropdowns to reflect the change
            setTimeout(function() {
                updateAllDropdowns();
            }, 100);
        }
        
        // Handle item selection
        $(document).on('change', '.item-select', function() {
            const selectedOption = $(this).find('option:selected');
            let itemData = selectedOption.data('item-data');
            const currentRow = $(this).closest('.item-row');
            const selectedItemId = selectedOption.val();
            
            console.log('Item selected:', itemData);
            
            // IMMEDIATE CHECK: Prevent duplicate selection
            if (selectedItemId && selectedItemId !== '') {
                const existingRows = $('#itemsContainer .item-row').not(currentRow);
                let isDuplicate = false;
                
                existingRows.each(function() {
                    const existingItemId = $(this).find('input[name="item_id[]"]').val();
                    if (existingItemId === selectedItemId) {
                        isDuplicate = true;
                        const existingItemName = $(this).find('input[name="item_name[]"]').val();
                        console.log('DUPLICATE DETECTED! Item already selected:', existingItemName);
                        
                        // Show alert and reset selection
                        Swal.fire({
                            title: 'Item Already Selected!',
                            text: `"${existingItemName}" is already selected in another row. Please choose a different item.`,
                            icon: 'warning',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#ffc107'
                        });
                        
                        // Reset the dropdown (using the main function scope variable)
                        const currentSelect = $(currentRow).find('.item-select');
                        currentSelect.val('').trigger('change');
                        return false; // Break out of loop
                    }
                });
                
                if (isDuplicate) {
                    return; // Exit the function early
                }
            }
            
            // Robust parsing of item data with proper error handling
            if (!itemData) {
                try {
                    const rawData = selectedOption.attr('data-item-data');
                    if (rawData && rawData.trim()) {
                        itemData = JSON.parse(rawData);
                        console.log('Successfully parsed itemData:', itemData);
                    } else {
                        console.warn('No raw data attribute found');
                        return; // Exit early if no data
                    }
                } catch (e) {
                    console.error('Error parsing item data:', e);
                    console.error('Raw data was:', selectedOption.attr('data-item-data'));
                    // Try to use individual data attributes as fallback
                    itemData = {
                        id: selectedOption.val(),
                        name: selectedOption.text().split(' - ')[0] || '',
                        unit: selectedOption.attr('data-unit') || '',
                        unit_price: selectedOption.attr('data-unit-price') || '',
                        quantity_available: selectedOption.attr('data-available-qty') || 0,
                        description: selectedOption.attr('data-description') || ''
                    };
                    console.log('Using fallback itemData:', itemData);
                }
            }
            
            if (itemData) {
                // First, populate the essential item data in the current row
                // This ensures the data is available for consolidation if needed
                currentRow.find('input[name="item_id[]"]').val(itemData.id);
                currentRow.find('input[name="item_name[]"]').val(itemData.name);
                currentRow.find('input[name="item_unit[]"]').val(itemData.unit);
                currentRow.find('input[name="item_unit_price[]"]').val(itemData.unit_price);
                
                // Use the correct field name for available quantity
                const availableQty = itemData.current_available_quantity || itemData.quantity_available || itemData.quantity || 0;
                currentRow.find('input[name="item_available_qty[]"]').val(availableQty);
                
                // Update the display fields
                currentRow.find('.unit-display').val(itemData.unit);
                currentRow.find('.price-display').val(itemData.unit_price);
                
                // Update the available quantity text and set max attribute
                currentRow.find('.available-qty-text').text(`Available: ${availableQty}`);
                currentRow.find('input[name="item_quantity[]"]').attr('max', availableQty);
                
                // Calculate total for this row
                const quantity = parseFloat(currentRow.find('input[name="item_quantity[]"]').val()) || 0;
                const unitPrice = parseFloat(itemData.unit_price) || 0;
                const total = quantity * unitPrice;
                currentRow.find('input[name="item_total[]"]').val(total.toFixed(2));
                
                console.log('Item data populated:', {
                    id: itemData.id,
                    name: itemData.name,
                    unit: itemData.unit,
                    unit_price: itemData.unit_price,
                    available: itemData.quantity_available
                });
                
                // Update all dropdowns to hide this selected item from other rows
                console.log('Item selected, updating all dropdowns');
                updateAllDropdowns();
                
                // Also call with a delay to ensure it works
                setTimeout(function() {
                    console.log('Calling updateAllDropdowns again after delay');
                    updateAllDropdowns();
                }, 500);
            } else {
                // Clear fields if no item selected
                currentRow.find('input[name="item_id[]"]').val('');
                currentRow.find('input[name="item_name[]"]').val('');
                currentRow.find('input[name="item_unit[]"]').val('');
                currentRow.find('input[name="item_unit_price[]"]').val('');
                currentRow.find('input[name="item_available_qty[]"]').val('');
                currentRow.find('.unit-display').val('');
                currentRow.find('.price-display').val('');
                currentRow.find('.available-qty-text').text('Available: 0');
                currentRow.find('input[name="item_total[]"]').val('0.00');
                
                // Update all dropdowns to show previously hidden items
                setTimeout(function() {
                    updateAllDropdowns();
                }, 100);
            }
            
            // Recalculate totals
            calculateTotals();
        });
        
        // Prevent entering quantity beyond max value
        $(document).on('keydown', '.quantity', function(e) {
            const currentValue = parseInt($(this).val()) || 0;
            const maxValue = parseInt($(this).attr('max')) || 0;
            const key = e.which || e.keyCode;
            
            // Allow: backspace, delete, tab, escape, enter
            if ([8, 9, 27, 13, 46].indexOf(key) !== -1 ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (key === 65 && e.ctrlKey === true) ||
                (key === 67 && e.ctrlKey === true) ||
                (key === 86 && e.ctrlKey === true) ||
                (key === 88 && e.ctrlKey === true) ||
                // Allow: home, end, left, right, down, up
                (key >= 35 && key <= 40)) {
                return;
            }
            
            // Prevent if would exceed max
            if (maxValue > 0) {
                const newValue = parseInt($(this).val() + String.fromCharCode(key)) || 0;
                if (newValue > maxValue) {
                    e.preventDefault();
                }
            }
        });
        
        // Calculate totals when quantity changes
        $(document).on('input', '.quantity', function() {
            const row = $(this).closest('.item-row');
            const requestedQty = parseFloat($(this).val()) || 0;
            const availableQty = parseFloat(row.find('input[name="item_available_qty[]"]').val()) || 0;
            const itemName = row.find('input[name="item_name[]"]').val();
            const itemId = row.find('input[name="item_id[]"]').val();
            
            // No need to check for duplicates here since items are filtered from dropdowns
            
            // Validate quantity against available stock for single items
            if (requestedQty > availableQty && availableQty > 0) {
                Swal.fire({
                    title: 'Quantity Limit Exceeded!',
                    text: `You cannot request ${requestedQty} items. Only ${availableQty} available for "${itemName}". Setting to maximum available.`,
                    icon: 'warning',
                    timer: 3000,
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ffc107'
                });
                $(this).val(availableQty); // Set to maximum available
                $(this).attr('max', availableQty); // Update max attribute
                
                // Trigger input event to recalculate totals
                $(this).trigger('input');
                return;
            }
            
            const quantity = parseFloat($(this).val()) || 0;
            const unitPrice = parseFloat(row.find('input[name="item_unit_price[]"]').val()) || 0;
            const total = quantity * unitPrice;
            row.find('input[name="item_total[]"]').val(total.toFixed(2));
            calculateTotals();
        });
        
        // Handle item removal
        $(document).on('click', '.remove-item', function() {
            if ($('.item-row').length > 1) {
                const itemRow = $(this).closest('.item-row');
                const itemName = itemRow.find('.item-select option:selected').text() || 'this item';
                
                // Show confirmation for any item removal
                Swal.fire({
                    title: 'Remove Item?',
                    text: `Are you sure you want to remove "${itemName}" from this requisition?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Remove',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        removeItemRow(itemRow);
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Remove',
                    text: 'At least one item is required in the requisition.',
                    confirmButtonText: 'OK'
                });
            }
        });
        
        // Add button to add new empty item row
        console.log('Attaching click handler to #addNewItemBtn');
        $('#addNewItemBtn').on('click', function() {
            console.log('Add Item button clicked!');
            console.log('addItemRow function exists:', typeof addItemRow);
            if (typeof addItemRow === 'function') {
            addItemRow();
            // Scroll to the new item
            $('html, body').animate({
                scrollTop: $('.item-row:last').offset().top - 20
            }, 500);
            } else {
                console.error('addItemRow function is not defined!');
            }
        });
        console.log('Click handler attached successfully');
        
        // Form submission handler
        $('#purchaseRequisitionForm').on('submit', function(e) {
            console.log('Form submit triggered');
            e.preventDefault();
            
            // Validate form
            const form = this;
            if (!form.checkValidity()) {
                console.log('Form validation failed');
                console.log('Form validity:', form.checkValidity());
                
            // Check each required field
            const requiredFields = form.querySelectorAll('[required]');
            console.log('Total required fields:', requiredFields.length);
            requiredFields.forEach(field => {
                if (!field.validity.valid) {
                    console.log('Invalid field:', field.name, 'Value:', field.value, 'Validity:', field.validity);
                } else {
                    console.log('Valid field:', field.name, 'Value:', field.value);
                }
            });
                
                e.stopPropagation();
                form.classList.add('was-validated');
                
                // Scroll to first invalid field
                const firstInvalid = $('.is-invalid').first();
                if (firstInvalid.length) {
                    $('html, body').animate({
                        scrollTop: firstInvalid.offset().top - 100
                    }, 500);
                }
                
                return false;
            }
            
            // Check if at least one item is added
            if ($('.item-row').length === 0) {
                showAlert('Error', 'Please add at least one item to the requisition', 'danger');
                return false;
            }
            
            // Check if requestor is selected
            if (!$('#requestedBy').val()) {
                showAlert('Error', 'Please select who is requesting this', 'danger');
                $('#requestedBy').focus();
                return false;
            }
            
            // Check if department is selected
            if (!$('#department').val()) {
                showAlert('Error', 'Please select a department', 'danger');
                $('#department').focus();
                return false;
            }
            
            // Check if project manager is selected
            if (!$('#projectManager').val()) {
                showAlert('Error', 'Please select a project manager', 'danger');
                $('#projectManager').focus();
                return false;
            }
            
            // Check if team leader is selected
            if (!$('#teamLeader').val()) {
                showAlert('Error', 'Please select a team leader', 'danger');
                $('#teamLeader').focus();
                return false;
            }
            
            // Check if at least one item has been selected
            let hasSelectedItem = false;
            $('.item-row').each(function() {
                if ($(this).find('.item-select').val()) {
                    hasSelectedItem = true;
                    return false; // break out of loop
                }
            });
            
            if (!hasSelectedItem) {
                showAlert('Error', 'Please select at least one item for the requisition', 'danger');
                return false;
            }
            
            // Check for duplicate items and validate total quantities
            const itemQuantities = {};
            let duplicateFound = false;
            let quantityExceeded = false;
            let errorMessage = '';
            
            $('.item-row').each(function() {
                const itemId = $(this).find('.item-select').val();
                const itemName = $(this).find('.item-select option:selected').text();
                const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                const available = parseFloat($(this).find('input[name="item_available_qty[]"]').val()) || 0;
                
                if (itemId) {
                    if (itemQuantities[itemId]) {
                        // Duplicate item found
                        duplicateFound = true;
                        itemQuantities[itemId].totalQuantity += quantity;
                        itemQuantities[itemId].rows.push($(this));
                    } else {
                        itemQuantities[itemId] = {
                            name: itemName.split(' - ')[0], // Get just the item name
                            totalQuantity: quantity,
                            available: available,
                            rows: [$(this)]
                        };
                    }
                }
            });
            
            // Check for quantity issues
            for (const itemId in itemQuantities) {
                const item = itemQuantities[itemId];
                if (item.totalQuantity > item.available) {
                    quantityExceeded = true;
                    errorMessage += `${item.name}: Requesting ${item.totalQuantity} but only ${item.available} available.\n`;
                }
            }
            
            if (duplicateFound || quantityExceeded) {
                let alertMessage = '';
                if (duplicateFound) {
                    alertMessage += 'Duplicate items found. Please consolidate quantities into a single row.\n\n';
                }
                if (quantityExceeded) {
                    alertMessage += 'Quantity limits exceeded:\n' + errorMessage;
                }
                
                Swal.fire({
                    title: 'Validation Error',
                    text: alertMessage,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            
            // Check if confirmation checkbox is checked
            if (!$('#termsCheck').is(':checked')) {
                showAlert('Error', 'Please confirm that all information is correct before submitting', 'danger');
                return false;
            }
            
            // Show confirmation modal
            const itemCount = $('.item-row').length;
            
            $('#modalItemCount').text(itemCount);
            
            // Populate item details in modal
            let itemDetailsHtml = '';
            $('.item-row').each(function(index) {
                const itemName = $(this).find('input[name="item_name[]"]').val();
                const quantity = $(this).find('input[name="item_quantity[]"]').val();
                const unit = $(this).find('input[name="item_unit[]"]').val();
                const unitPrice = $(this).find('input[name="item_unit_price[]"]').val();
                const total = $(this).find('input[name="item_total[]"]').val();
                
                if (itemName) {
                    itemDetailsHtml += `
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>${itemName}</strong>
                                    <br>
                                    <small class="text-muted">Quantity: ${quantity} ${unit}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">$${total}</div>
                                    <small class="text-muted">@ $${unitPrice}/${unit}</small>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
            
            if (itemDetailsHtml) {
                $('#modalItemDetails').html(itemDetailsHtml);
            } else {
                $('#modalItemDetails').html('<p class="text-muted text-center">No items selected</p>');
            }
            
            // Store the form submission function
            const submitForm = function() {
                const fileInput = document.getElementById('fileUpload');
                
                // Prepare form data
                const formData = new FormData(form);
                
                // Add files to form data
                for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append('attachments[]', fileInput.files[i]);
                }
                
                // Add existing attachments when editing
                if (window.editingRequisitionId) {
                    const existingAttachments = [];
                    $('.file-preview-item[data-existing="true"]').each(function() {
                        existingAttachments.push($(this).data('path'));
                    });
                    formData.append('existing_attachments', JSON.stringify(existingAttachments));
                }
                
                // Collect items data
                const items = [];
                $('.item-row').each(function() {
                    const item = {
                        item_id: $(this).find('[name="item_id[]"]').val(),
                        item_name: $(this).find('[name="item_name[]"]').val(),
                        quantity: $(this).find('[name="item_quantity[]"]').val(),
                        unit_price: $(this).find('[name="item_unit_price[]"]').val(),
                        unit: $(this).find('[name="item_unit[]"]').val(),
                        available_qty: $(this).find('[name="item_available_qty[]"]').val(),
                        total: $(this).find('[name="item_total[]"]').val()
                    };
                    items.push(item);
                });
                
                // Add items to form data
                formData.append('items', JSON.stringify(items));
                
                // Debug: Log the items being sent
                console.log('Items being sent to server:', items);
                console.log('Items JSON:', JSON.stringify(items));
                
                // Show loading state
                const submitButton = $('#submitBtn');
                const originalText = submitButton.html();
                submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                
                // Determine if we're editing or creating
                const isEditing = window.editingRequisitionId;
                let url = form.action || '/company/warehouse/requisitions';
                let method = 'POST';
                
                if (isEditing) {
                    url = '/company/warehouse/requisitions/' + window.editingRequisitionId;
                    formData.append('_method', 'PUT'); // Laravel method spoofing for PUT request
                }
                
                // Debug: Log form data before submission
                console.log('=== FORM SUBMISSION DEBUG ===');
                console.log('Form action:', form.action);
                console.log('Form method:', form.method);
                console.log('Is editing:', isEditing);
                console.log('URL:', url);
                console.log('Method:', method);
                
                // Debug: Log all form field values
                const formFields = {};
                for (let [key, value] of formData.entries()) {
                    formFields[key] = value;
                }
                console.log('Form data being sent:', formFields);
                console.log('Requester ID value:', $('#requestedBy').val());
                console.log('Team Leader ID value:', $('#teamLeader').val());
                console.log('=== END FORM SUBMISSION DEBUG ===');
                
                // Submit form via AJAX
                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Handle success response
                    if (response.success) {
                        // Close the confirmation modal
                        $('#confirmationModal').modal('hide');
                        
                        // Show SweetAlert success message
                        const isEditing = window.editingRequisitionId;
                        Swal.fire({
                            title: 'Success!',
                            text: response.message || (isEditing ? 'Requisition updated successfully!' : 'Purchase requisition submitted successfully!'),
                            icon: 'success',
                            timer: 3000,
                            showConfirmButton: true,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#28a745'
                        });
                        
                        if (isEditing) {
                            // Stay on the edit form after successful update
                            resetEditForm();
                            
                            // Reload the requisitions list in background
                            loadRequisitions();
                            
                            // Refresh statistics
                            refreshStatistics();
                            
                            // Just stay on the edit form - no automatic dialog or switching
                        } else {
                            // Reset the form for new requisition
                            form.reset();
                            $('#itemsContainer').empty();
                            addItemRow();
                            $('#filePreview').empty();
                            $('#noFilesMessage').show();
                            form.classList.remove('was-validated');
                            
                            // Reload the requisitions list
                            loadRequisitions();
                            
                            // Refresh statistics
                            refreshStatistics();
                            
                            // Scroll to top
                            window.scrollTo(0, 0);
                        }
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message || 'Failed to submit requisition',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#dc3545'
                        });
                        }
                    },
                    error: function(xhr) {
                        // Handle errors
                        console.log('=== FORM SUBMISSION ERROR ===');
                        console.log('Error response:', xhr);
                        console.log('Status:', xhr.status);
                        console.log('Response text:', xhr.responseText);
                        console.log('Response JSON:', xhr.responseJSON);
                        
                        let errorMessage = 'An error occurred while submitting the form.';
                        let errorDetails = '';
                        
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            
                            // Handle validation errors
                            if (xhr.responseJSON.errors) {
                                errorDetails = '\n\nValidation Errors:\n';
                                Object.keys(xhr.responseJSON.errors).forEach(field => {
                                    errorDetails += `• ${field}: ${xhr.responseJSON.errors[field].join(', ')}\n`;
                                });
                            }
                        }
                        
                        console.log('Final error message:', errorMessage + errorDetails);
                        showAlert('Error', errorMessage + errorDetails, 'danger');
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).html(originalText);
                    }
                });
            };
            
            // Store the submit function in the confirm button
            $('#confirmSubmit').off('click').on('click', submitForm);
            
            // Show the confirmation modal
            $('#confirmationModal').modal('show');
            
            return false;
        });
        
        // Handle save draft button
        $('#saveDraftBtn').on('click', function() {
            console.log('Save Draft button clicked');
            const form = document.getElementById('purchaseRequisitionForm');
            const formData = new FormData(form);
            formData.append('is_draft', '1');
            
            // Add files to form data
            const fileInput = document.getElementById('fileUpload');
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append('attachments[]', fileInput.files[i]);
            }
            
            // Show loading state
            const saveButton = $(this);
            const originalText = saveButton.html();
            saveButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
            
            // Submit draft via AJAX
            $.ajax({
                url: form.action || '/company/warehouse/requisitions',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        showAlert('Success', 'Draft saved successfully!', 'success');
                        
                        // If we have a draft ID, update the form action
                        if (response.draft_id) {
                            form.action = form.action.replace(/\/draft$/, '') + '/' + response.draft_id;
                        }
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred while saving the draft.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = '';
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            errorMessage += errors[field][0] + '\n';
                        }
                    }
                    showAlert('Error', errorMessage, 'danger');
                },
                complete: function() {
                    saveButton.prop('disabled', false).html(originalText);
                }
            });
        });
        
        // Handle cancel button
        $('#cancelBtn').on('click', function() {
            if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = '';
            }
        });
    }
        
        // Function to add new item row
        function addItemRow(itemData = {}) {
            console.log('addItemRow function called');
            const itemCount = $('.item-row').length + 1;
            console.log('Current item count:', itemCount);
            
            const newItem = `
                <div class="item-row border rounded p-3 mb-3 position-relative" data-item="${itemCount}">
                    <div class="position-absolute top-0 end-0 mt-2 me-2">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-action remove-item" data-bs-toggle="tooltip" title="Remove item">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">Item</label>
                            <select class="form-select item-select" name="item_select[]" required>
                                <option value="">Select an item...</option>
                            </select>
                            <input type="hidden" name="item_id[]" value="">
                            <input type="hidden" name="item_name[]" value="">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label required-field">Quantity</label>
                            <input type="number" class="form-control quantity" name="item_quantity[]" min="1" max="0" value="1" required>
                            <small class="form-text text-muted available-qty-text">Available: 0</small>
                            <input type="hidden" name="item_available_qty[]" value="">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Unit</label>
                            <input type="text" class="form-control unit-display" readonly>
                            <input type="hidden" name="item_unit[]" value="">
                        </div>
                        <div class="col-md-2 mb-3" style="display: none !important;">
                            <label class="form-label">Unit Price</label>
                            <input type="number" class="form-control price-display" readonly>
                            <input type="hidden" name="item_unit_price[]" value="">
                        </div>
                        <div class="col-md-2 mb-3" style="display: none !important;">
                            <label class="form-label">Total</label>
                            <input type="number" class="form-control" name="item_total[]" readonly value="0.00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="item-details">
                                <!-- Item details will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#itemsContainer').append(newItem);
            
            // Load available items for the new select element
            const newSelect = $('#itemsContainer .item-row:last .item-select');
            loadAvailableItems(newSelect);
            
            // Update all dropdowns to hide already selected items
            setTimeout(function() {
                if (typeof updateAllDropdowns === 'function') {
                    updateAllDropdowns();
                } else {
                    console.log('updateAllDropdowns function not available yet, skipping...');
                }
            }, 500); // Give time for items to load
            
            // Initialize tooltips for the new row
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
        
        // Function to clear all items
        function clearAllItems() {
            $('#itemsContainer').empty();
        }
        
        // Function to initialize form
        function initializeForm() {
            // Add at least one item row
            addItemRow();
        }
        
        // Enhanced add item button handler for edit mode
        function enhanceEditItemManagement() {
            // Make the add item button more prominent during edit
            const addBtn = $('#addNewItemBtn');
            if (window.editingRequisitionId) {
                addBtn.removeClass('btn-sm').addClass('btn-md')
                    .html('<i class="fas fa-plus me-1"></i> Add Another Item')
                    .removeClass('btn-primary').addClass('btn-success');
            } else {
                addBtn.addClass('btn-sm').removeClass('btn-md')
                    .html('<i class="fas fa-plus me-1"></i> Add Item')
                    .removeClass('btn-success').addClass('btn-primary');
            }
        }
        
        // Function to remove an item row
        function removeItemRow(itemRow) {
            itemRow.fadeOut(300, function() {
                $(this).remove();
                calculateTotals();
                // Renumber remaining items for form submission
                $('.item-row').each(function(index) {
                    $(this).find('input, select, textarea').each(function() {
                        const name = $(this).attr('name');
                        if (name) {
                            const newName = name.replace(/\[\d+\]/, '[' + index + ']');
                            $(this).attr('name', newName);
                        }
                    });
                    $(this).attr('data-item', index + 1);
                });
            });
        }
        
        // Handle file upload preview
        function handleFileUpload(files) {
            const filePreview = $('#filePreview');
            const noFilesMessage = $('#noFilesMessage');
            const fileInput = document.getElementById('fileUpload');
            
            // Set the files to the input element using DataTransfer
            if (files instanceof FileList) {
                // Files are already from input, no need to reassign
            } else {
                // Files are from drag & drop, need to transfer to input
                const dt = new DataTransfer();
                for (let i = 0; i < files.length; i++) {
                    dt.items.add(files[i]);
                }
                fileInput.files = dt.files;
            }
            
            if (files.length > 0) {
                noFilesMessage.hide();
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    
                    // Check file size (max 10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        showAlert('File too large', 'The file ' + file.name + ' exceeds the 10MB limit.', 'danger');
                        continue;
                    }
                    
                    const fileType = file.type.split('/')[0];
                    let iconClass = 'fa-file';
                    
                    // Set appropriate icon based on file type
                    if (fileType === 'image') {
                        iconClass = 'fa-file-image';
                    } else if (fileType === 'application') {
                        if (file.type.includes('pdf')) {
                            iconClass = 'fa-file-pdf';
                        } else if (file.type.includes('word') || file.type.includes('document')) {
                            iconClass = 'fa-file-word';
                        } else if (file.type.includes('excel') || file.type.includes('spreadsheet')) {
                            iconClass = 'fa-file-excel';
                        }
                    }
                    
                    const fileSize = formatFileSize(file.size);
                    
                    const filePreviewItem = `
                        <div class="file-preview-item" data-file-name="${file.name}">
                            <i class="fas ${iconClass} text-primary"></i>
                            <div class="file-name" title="${file.name}">${truncateFileName(file.name, 30)}</div>
                            <div class="file-size">${fileSize}</div>
                            <i class="fas fa-times-circle file-remove" title="Remove file"></i>
                        </div>
                    `;
                    
                    // Check if file already exists
                    if (!filePreview.find(`[data-file-name="${file.name}"]`).length) {
                        filePreview.append(filePreviewItem);
                    }
                }
                
                // Trigger global file remove handler setup (will be handled by document level handler)
                setupFileRemoveHandlers();
                
            }
        }
        
        // Setup file remove handlers (placeholder function - actual handling is done by global event delegation)
        function setupFileRemoveHandlers() {
            // This function ensures all file remove buttons work via the global event delegation above
            // No additional setup needed since we're using $(document).on('click', '.file-remove', ...)
        }
        
        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // Truncate long file names
        function truncateFileName(name, maxLength) {
            if (name.length > maxLength) {
                return name.substring(0, maxLength / 2) + '...' + name.substring(name.length - (maxLength / 2 - 3));
            }
            return name;
        }
        
        // Show alert message
        function showAlert(title, message, type = 'info') {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <strong>${title}</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            $('.container').prepend(alertHtml);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                $('.alert').fadeOut(300, function() { $(this).remove(); });
            }, 5000);
        }
        
        // Calculate and update totals
        function calculateTotals() {
            // Calculate total items
            let totalItems = 0;
            $('.item-row').each(function() {
                const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                totalItems += quantity;
            });
            
            // Update total items display
            $('#totalItems').text(totalItems);
        }
    
    // Load departments for requisition form
    function loadDepartments() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log('Loading departments...');
    console.log('CSRF Token:', csrfToken);
    
        // First, let's test the debug endpoint
    $.ajax({
            url: '/company/warehouse/requisitions/debug-departments',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        },
        success: function(response) {
                console.log('Debug response:', response);
                
                // Now try the actual warehouse departments endpoint
                $.ajax({
                    url: '/company/warehouse/requisitions/warehouse-departments',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    success: function(response) {
                        console.log('Departments response:', response);
                        const departmentSelect = $('#department');
                        departmentSelect.empty();
                        departmentSelect.append('<option value="" selected disabled>Select Department</option>');
                        
                        if (response.success && response.departments && response.departments.length > 0) {
                            response.departments.forEach(function(department) {
                                // Since we only show sub-departments now, just use the name directly
                                departmentSelect.append(`<option value="${department.id}">${department.name}</option>`);
                            });
                            console.log('Successfully loaded', response.departments.length, 'sub-departments');
                            console.log('Debug info:', response.debug_info);
            } else {
                            console.log('No warehouse sub-departments found, trying fallback...');
                            loadAllDepartments();
            }
        },
        error: function(xhr, status, error) {
                        console.error('Error loading departments:', error);
                        console.error('Response:', xhr.responseText);
            console.error('Status:', status);
                        loadAllDepartments();
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error with debug endpoint:', error);
                console.error('Debug Response:', xhr.responseText);
                
                // Try the warehouse departments endpoint anyway
                $.ajax({
                    url: '/company/warehouse/requisitions/warehouse-departments',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    success: function(response) {
                        console.log('Departments response (after debug error):', response);
                        const departmentSelect = $('#department');
                        departmentSelect.empty();
                        departmentSelect.append('<option value="" selected disabled>Select Department</option>');
                        
                        if (response.success && response.departments && response.departments.length > 0) {
                            response.departments.forEach(function(department) {
                                // Since we only show sub-departments now, just use the name directly
                                departmentSelect.append(`<option value="${department.id}">${department.name}</option>`);
                            });
                            console.log('Successfully loaded sub-departments (after debug error):', response.departments.length);
    } else {
                            loadAllDepartments();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading departments (after debug error):', error);
                        loadAllDepartments();
                    }
                });
            }
        });
    }
    
    // Fallback function to load all departments
    function loadAllDepartments() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log('Loading all departments as fallback...');
        
        $.ajax({
            url: '/company/warehouse/requisitions/all-departments',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            success: function(response) {
                console.log('All departments response:', response);
                const departmentSelect = $('#department');
                departmentSelect.empty();
                departmentSelect.append('<option value="" selected disabled>Select Department</option>');
                
                if (response.success && response.departments && response.departments.length > 0) {
                    response.departments.forEach(function(department) {
                        departmentSelect.append(`<option value="${department.id}">${department.name}</option>`);
                    });
        } else {
                    departmentSelect.append('<option value="" disabled>No departments available</option>');
                    showAlert('Warning', 'No departments found. Please contact administrator.', 'warning');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading all departments:', error);
                const departmentSelect = $('#department');
                departmentSelect.empty();
                departmentSelect.append('<option value="" disabled>Error loading departments</option>');
                showAlert('Error', 'Failed to load departments. Please refresh the page.', 'error');
            }
        });
    }
    
    // Load users for the requested by dropdown
    function loadUsers() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: '/company/warehouse/requisitions/users',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                const requestedBySelect = $('#requestedBy');
                requestedBySelect.empty();
                requestedBySelect.append('<option value="" selected disabled>Select Requestor</option>');
                
                if (response.success && response.users && response.users.length > 0) {
                    response.users.forEach(function(user) {
                        const isCurrentUser = response.current_user_id && user.id == response.current_user_id;
                        requestedBySelect.append(`<option value="${user.id}" ${isCurrentUser ? 'selected' : ''}>${user.name}</option>`);
                    });
                } else {
                    requestedBySelect.append('<option value="" disabled>No employees available</option>');
                }
            },
            error: function(xhr, status, error) {
                const requestedBySelect = $('#requestedBy');
                requestedBySelect.empty();
                requestedBySelect.append('<option value="" disabled>Error loading employees</option>');
            }
        });
    }

    // Load project managers for the form
    function loadProjectManagers() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        console.log('Loading project managers...');
        console.log('CSRF Token:', csrfToken);
        
        $.ajax({
            url: '/company/warehouse/requisitions/project-managers',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log('Project managers response:', response);
                
                const projectManagerSelect = $('#projectManager');
                projectManagerSelect.empty();
                projectManagerSelect.append('<option value="" selected disabled>Select Project Manager</option>');
                
                if (response.success && response.data && response.data.length > 0) {
                    console.log('Found ' + response.data.length + ' project managers');
                    
                    // Add only project managers (backend now only returns project managers)
                    response.data.forEach(function(manager) {
                        console.log('Adding project manager:', manager);
                        // Clean up the position name - remove "limited_user" and other unwanted text
                        let cleanPosition = manager.position || '';
                        if (cleanPosition.toLowerCase().includes('limited_user')) {
                            cleanPosition = cleanPosition.replace(/limited_user/gi, '').replace(/\s*-\s*$/, '').trim();
                        }
                        // If position is empty or just dashes, show only the name
                        if (!cleanPosition || cleanPosition === '-' || cleanPosition === '') {
                            projectManagerSelect.append(`<option value="${manager.id}">${manager.name}</option>`);
                        } else {
                            projectManagerSelect.append(`<option value="${manager.id}">${manager.name} - ${cleanPosition}</option>`);
                        }
                    });
                } else {
                    console.log('No project managers found');
                    projectManagerSelect.append('<option value="" disabled>No project managers available - Please create a "Project Manager" profile and assign users to it</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading project managers:', error);
                console.error('XHR Response:', xhr.responseText);
                
                const projectManagerSelect = $('#projectManager');
                projectManagerSelect.empty();
                projectManagerSelect.append('<option value="" disabled>Error loading project managers</option>');
            }
        });
    }

    // Load team leaders for the form
    function loadTeamLeaders() {
        console.log('=== LOADING TEAM LEADERS ===');
        console.log('Starting team leaders load...');
        
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log('CSRF Token:', csrfToken);
        console.log('Making AJAX request to team-leaders endpoint...');
        
        $.ajax({
            url: '/company/warehouse/requisitions/team-leaders',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            beforeSend: function() {
                console.log('AJAX request started for team leaders...');
            },
            success: function(response) {
                console.log('=== TEAM LEADERS API SUCCESS ===');
                console.log('Full response:', response);
                console.log('Response success:', response.success);
                console.log('Response data:', response.data);
                console.log('Data length:', response.data ? response.data.length : 'No data');
                
                const teamLeaderSelect = $('#teamLeader');
                console.log('Team leader select element found:', teamLeaderSelect.length > 0);
                
                teamLeaderSelect.empty();
                teamLeaderSelect.append('<option value="" selected disabled>Select Team Leader</option>');
                
                if (response.success && response.data && response.data.length > 0) {
                    console.log(`Processing ${response.data.length} team leaders...`);
                    
                    response.data.forEach(function(leader, index) {
                        console.log(`=== TEAM LEADER ${index + 1} ===`);
                        console.log('Leader object:', leader);
                        console.log('ID:', leader.id);
                        console.log('Name:', leader.name);
                        console.log('Email:', leader.email);
                        console.log('Position:', leader.position);
                        console.log('Team Name:', leader.team_name);
                        
                        const displayText = leader.team_name ? 
                            `${leader.name} - ${leader.position} (${leader.team_name})` : 
                            `${leader.name} - ${leader.position}`;
                        
                        console.log('Display text:', displayText);
                        teamLeaderSelect.append(`<option value="${leader.id}">${displayText}</option>`);
                    });
                    
                    console.log('All team leaders processed successfully');
                } else {
                    console.log('No team leaders found or error in response');
                    teamLeaderSelect.append('<option value="" disabled>No team leaders available</option>');
                }
                
                console.log('=== TEAM LEADERS LOADING COMPLETED ===');
            },
            error: function(xhr, status, error) {
                console.log('=== TEAM LEADERS API ERROR ===');
                console.log('Error status:', xhr.status);
                console.log('Error status text:', xhr.statusText);
                console.log('Error:', error);
                console.log('Response text:', xhr.responseText);
                console.log('Response JSON:', xhr.responseJSON);
                
                const teamLeaderSelect = $('#teamLeader');
                teamLeaderSelect.empty();
                teamLeaderSelect.append('<option value="" disabled>Error loading team leaders</option>');
                
                console.log('=== END TEAM LEADERS ERROR ===');
            },
            complete: function() {
                console.log('Team leaders AJAX request completed');
            }
        });
    }
    
    // Load statistics - use backup loader since main function may not exist
    function refreshStatistics() {
        // Check if the main loadStatistics function exists
        if (typeof loadStatistics === 'function') {
            loadStatistics();
        } else {
            // Use our own backup loader
            loadStatisticsBackup();
        }
    }
    
    // Backup statistics loader if main one doesn't exist
    function loadStatisticsBackup() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        // Show loading state first
        if ($('#pendingApprovalsCount').length) $('#pendingApprovalsCount').html('<span class="spinner-border spinner-border-sm"></span>');
        if ($('#openPOsCount').length) $('#openPOsCount').html('<span class="spinner-border spinner-border-sm"></span>');
        if ($('#monthlySpend').length) $('#monthlySpend').html('<span class="spinner-border spinner-border-sm"></span>');
        if ($('#activeSuppliersCount').length) $('#activeSuppliersCount').html('<span class="spinner-border spinner-border-sm"></span>');
        
    $.ajax({
            url: '/company/warehouse/requisitions/statistics',
        method: 'POST',
        headers: {
                'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
                if (response.success && response.data) {
                    // Update the cards with received data
                    if ($('#pendingApprovalsCount').length) {
                        $('#pendingApprovalsCount').html(response.data.pending_approvals || response.data.pending_requisitions || '0');
                    }
                    if ($('#openPOsCount').length) {
                        $('#openPOsCount').html(response.data.open_pos || response.data.total_requisitions || '0');
                    }
                    if ($('#monthlySpend').length) {
                        const spend = response.data.monthly_spend || response.data.total_value || 0;
                        $('#monthlySpend').html('$' + new Intl.NumberFormat('en-US').format(spend));
                    }
                    if ($('#activeSuppliersCount').length) {
                        $('#activeSuppliersCount').html(response.data.active_suppliers || response.data.approved_today || '0');
                    }
    } else {
                    // Show error in cards
                    if ($('#pendingApprovalsCount').length) $('#pendingApprovalsCount').html('Error');
                    if ($('#openPOsCount').length) $('#openPOsCount').html('Error');
                    if ($('#monthlySpend').length) $('#monthlySpend').html('Error');
                    if ($('#activeSuppliersCount').length) $('#activeSuppliersCount').html('Error');
                }
            },
            error: function(xhr, status, error) {
                // Show error in cards
                if ($('#pendingApprovalsCount').length) $('#pendingApprovalsCount').html('Error');
                if ($('#openPOsCount').length) $('#openPOsCount').html('Error');
                if ($('#monthlySpend').length) $('#monthlySpend').html('Error');
                if ($('#activeSuppliersCount').length) $('#activeSuppliersCount').html('Error');
            }
        });
    }
    
    // Update statistics cards with data (this function is kept for compatibility but may not be used)
    function updateStatisticsCards(stats) {
        // Update pending approvals (from API data)
        if ($('#pendingApprovalsCount').length > 0) {
            $('#pendingApprovalsCount').html(stats.pending_approvals || stats.pending_requisitions || '0');
        }
        
        // Update open POs (from API data)
        if ($('#openPOsCount').length > 0) {
            $('#openPOsCount').html(stats.open_pos || stats.total_requisitions || '0');
        }
        
        // Update monthly spend with currency formatting
        if ($('#monthlySpend').length > 0) {
            const monthlySpend = stats.monthly_spend || stats.total_value || 0;
            $('#monthlySpend').html('$' + new Intl.NumberFormat('en-US').format(monthlySpend));
        }
        
        // Update active suppliers
        if ($('#activeSuppliersCount').length > 0) {
            $('#activeSuppliersCount').html(stats.active_suppliers || stats.approved_today || '0');
        }
    }
    
    // Global pagination state
    let currentPage = 1;
    let perPage = 10;
    let searchTerm = '';

    // Load requisitions for the table
    function loadRequisitions(page = 1, search = '', pageSize = null) {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log('Loading requisitions...');
        
        // Update global state
        currentPage = page;
        if (search !== null) searchTerm = search;
        if (pageSize !== null) perPage = pageSize;
        
    $.ajax({
            url: '/company/warehouse/requisitions/all',
        method: 'POST',
            data: {
                page: currentPage,
                per_page: perPage,
                search: searchTerm
            },
        headers: {
                'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
                console.log('Requisitions loaded:', response);
                if (response.success) {
                    populateRequisitionsTable(response.data || []);
                    updatePagination(response.pagination);
                } else {
                    // Show empty state if no data
                    populateRequisitionsTable([]);
                    updatePagination(null);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading requisitions:', error);
                console.error('Response:', xhr.responseText);
                // Show empty state on error
                populateRequisitionsTable([]);
                updatePagination(null);
        }
    });
}

    // Populate requisitions table
    function populateRequisitionsTable(requisitions) {
        console.log('Populating table with:', requisitions);
        const tbody = $('#requisitionsTable tbody');
        tbody.empty();
        
        if (!requisitions || requisitions.length === 0) {
            console.log('Showing empty state');
            tbody.append(`
                <tr>
                    <td colspan="9" class="text-center text-muted py-5">
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list fa-4x mb-3 text-muted"></i>
                            <h5 class="text-muted mb-2">No Requisitions Found</h5>
                            <p class="text-muted mb-3">You haven't created any requisitions yet.</p>
                            <button type="button" class="btn btn-primary" onclick="$('#requisition-form-tab').tab('show')">
                                <i class="fas fa-plus me-2"></i>Create Your First Requisition
                            </button>
                        </div>
                    </td>
                </tr>
            `);
                return;
            }

        requisitions.forEach(function(requisition) {
            console.log('Processing requisition:', requisition);
            console.log('Requisition fields:', Object.keys(requisition));
            
            const statusBadge = getStatusBadge(requisition.status);
            const priorityBadge = getPriorityBadge(requisition.priority);
            const totalItems = requisition.items ? requisition.items.length : 0;
            
            // Format the requisition date
            let formattedDate = 'N/A';
            if (requisition.requisition_date) {
                const date = new Date(requisition.requisition_date);
                formattedDate = date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit'
                });
            } else if (requisition.created_at) {
                // Fallback to created_at if requisition_date is not available
                const date = new Date(requisition.created_at);
                formattedDate = date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit'
                });
            }
            
            // Get department name
            let departmentName = requisition.department || 'N/A';
            
            // Get requestor name from employee relationship
            let requestorName = 'N/A';
            if (requisition.requestor && requisition.requestor.personal_info) {
                const firstName = requisition.requestor.personal_info.first_name || '';
                const lastName = requisition.requestor.personal_info.last_name || '';
                requestorName = (firstName + ' ' + lastName).trim() || ('Employee #' + requisition.requestor.staff_id);
            } else if (requisition.requestor && requisition.requestor.personalInfo) {
                const firstName = requisition.requestor.personalInfo.first_name || '';
                const lastName = requisition.requestor.personalInfo.last_name || '';
                requestorName = (firstName + ' ' + lastName).trim() || ('Employee #' + requisition.requestor.staff_id);
            } else if (requisition.requestor && requisition.requestor.staff_id) {
                requestorName = 'Employee #' + requisition.requestor.staff_id;
            }
            
            console.log('Creating row for requisition:', requisition.id, 'Status:', requisition.status);
            
                const rowClass = requisition.status === 'partially_approved' ? 'clickable-row partially-approved-row' : 'clickable-row';
                const row = `
                <tr class="${rowClass}" data-id="${requisition.id}">
                    <td><strong>${requisition.requisition_number || 'N/A'}</strong></td>
                    <td><strong>${requisition.title || 'No Title'}</strong></td>
                    <td>${departmentName}</td>
                    <td>${requestorName}</td>
                    <td>${formattedDate}</td>
                    <td><span class="badge bg-light text-dark">${totalItems}</span></td>
                    <td>${priorityBadge}</td>
                    <td>${statusBadge}</td>
                    <td class="action-buttons">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-info view-requisition" data-id="${requisition.id}" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${requisition.status === 'pending' || requisition.status === 'partially_approved' ? `
                                <button type="button" class="btn btn-sm btn-primary edit-requisition" data-id="${requisition.id}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-requisition" data-id="${requisition.id}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : `
                                <button type="button" class="btn btn-sm btn-secondary" disabled title="Cannot edit ${requisition.status} requisition">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" disabled title="Cannot delete ${requisition.status} requisition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `}
                        </div>
                    </td>
                    </tr>
                `;
                
            tbody.append(row);
            
            // Add direct click handlers to ensure buttons work
            const addedRow = tbody.find('tr:last');
            const addedButtons = addedRow.find('button');
            addedButtons.each(function() {
                // Add direct click handler as backup
                $(this).off('click.direct').on('click.direct', function(e) {
                    console.log('Direct handler triggered for:', $(this).attr('class').split(' ').pop());
                });
    });
});

        // Action buttons are handled by event delegation, no need to re-attach
        console.log('Table populated with', requisitions.length, 'requisitions');
    }
    
    // Update pagination controls
    function updatePagination(pagination) {
        const paginationInfo = $('#paginationInfo');
        const paginationControls = $('#paginationControls');
        
        if (!pagination || !pagination.total) {
            paginationInfo.html('');
            paginationControls.html('');
            return;
        }
        
        // Update info text
        const from = pagination.from || 0;
        const to = pagination.to || 0;
        const total = pagination.total || 0;
        paginationInfo.html(`Showing ${from} to ${to} of ${total} entries`);
        
        // Update pagination controls
        let paginationHtml = '';
        
        // Previous button
        if (pagination.prev_page) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadRequisitions(${pagination.prev_page}, searchTerm, perPage); return false;">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            `;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>';
        }
        
        // Page numbers
        const currentPage = pagination.current_page;
        const lastPage = pagination.last_page;
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(lastPage, currentPage + 2);
        
        // First page
        if (startPage > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="loadRequisitions(1, searchTerm, perPage); return false;">1</a></li>`;
            if (startPage > 2) {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // Page numbers around current page
        for (let i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                paginationHtml += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="loadRequisitions(${i}, searchTerm, perPage); return false;">${i}</a></li>`;
            }
        }
        
        // Last page
        if (endPage < lastPage) {
            if (endPage < lastPage - 1) {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="loadRequisitions(${lastPage}, searchTerm, perPage); return false;">${lastPage}</a></li>`;
        }
        
        // Next button
        if (pagination.next_page) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadRequisitions(${pagination.next_page}, searchTerm, perPage); return false;">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>';
        }
        
        paginationControls.html(paginationHtml);
    }
    
    // Get status badge HTML
    function getStatusBadge(status) {
        const badges = {
            'draft': '<span class="badge bg-secondary">Draft</span>',
            'pending': '<span class="badge bg-warning">Pending</span>',
            'partially_approved': '<span class="badge partially-approved" title="Some items approved, some pending re-order"><i class="fas fa-clock"></i> Partially Approved</span>',
            'approved': '<span class="badge bg-success">Approved</span>',
            'rejected': '<span class="badge bg-danger">Rejected</span>',
            'completed': '<span class="badge bg-info">Completed</span>'
        };
        return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
    }

    // Approve requisition
    function approveItem(id) {
        $.ajax({
            url: `/company/warehouse/requisitions/${id}/approve`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showAlert('Success', response.message, 'success');
                    loadRequisitions(); // Refresh the table
                } else {
                    showAlert('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('Error', response?.message || 'Failed to approve requisition', 'error');
            }
        });
    }

    // Reject requisition
    function rejectItem(id) {
        Swal.fire({
            title: 'Reject Requisition',
            text: 'Please provide a reason for rejection:',
            input: 'textarea',
            inputPlaceholder: 'Enter rejection reason...',
            showCancelButton: true,
            confirmButtonText: 'Reject',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                const rejectionReason = result.value || 'No reason provided';
                
                $.ajax({
                    url: `/company/warehouse/requisitions/${id}/reject`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        rejection_reason: rejectionReason
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Success', response.message, 'success');
                            loadRequisitions(); // Refresh the table
                        } else {
                            showAlert('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        showAlert('Error', response?.message || 'Failed to reject requisition', 'error');
                    }
                });
            }
        });
    }
    
    // Get priority badge HTML
    function getPriorityBadge(priority) {
        const badges = {
            'low': '<span class="badge bg-success">Low</span>',
            'medium': '<span class="badge bg-info">Medium</span>',
            'high': '<span class="badge bg-warning">High</span>',
            'urgent': '<span class="badge bg-danger">Urgent</span>'
        };
        return badges[priority] || '<span class="badge bg-secondary">Unknown</span>';
    }
    
    // Load available items from central store
    function loadAvailableItems(selectElement) {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log('=== LOADING AVAILABLE ITEMS ===');
        console.log('Loading available items...');
        console.log('CSRF Token:', csrfToken);
        console.log('Select element:', selectElement);
    
    $.ajax({
            url: '/company/warehouse/central-store/available-items-for-requisition',
        method: 'POST',
        headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                search: '',
                category: ''
            }),
        success: function(response) {
                console.log('=== API SUCCESS RESPONSE ===');
                console.log('Success response:', response);
                console.log('Response data:', response.data);
                console.log('Data length:', response.data ? response.data.length : 0);
                console.log('=== END API RESPONSE ===');
                
                $(selectElement).empty();
                $(selectElement).append('<option value="">Select an item...</option>');
                
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(item) {
                        
                        // Clean and escape the item data to prevent JSON parsing errors
                        const cleanItem = {
                            id: item.id,
                            name: item.name || '',
                            unit: item.unit || '',
                            unit_price: item.unit_price || '',
                            quantity_available: item.quantity_available || 0,
                            category: item.category || '',
                            description: (item.description || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/\n/g, ' ').replace(/\r/g, ' ')
                        };
                        
                        const option = $('<option>', {
                            value: item.id,
                            text: `${item.name} - ${item.category} (Available: ${item.quantity_available} ${item.unit})`,
                            'data-unit': item.unit,
                            'data-unit-price': item.unit_price,
                            'data-available-qty': item.quantity_available,
                            'data-description': cleanItem.description,
                            'data-item-data': JSON.stringify(cleanItem)
                        });
                        $(selectElement).append(option);
                    });
                } else {
                    $(selectElement).append('<option value="" disabled>No items available</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading items:', error);
                console.error('Response:', xhr.responseText);
                console.error('Status:', status);
                $(selectElement).empty();
                $(selectElement).append('<option value="" disabled>Error loading items</option>');
            }
        });
        }
</script>

<!-- View Requisition Modal -->

<div class="modal fade" id="viewRequisitionModal" tabindex="-1" aria-labelledby="viewRequisitionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="viewRequisitionModalLabel">
                    <i class="fas fa-file-invoice me-2"></i>Requisition Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
                <button type="button" class="btn btn-primary" id="printRequisition">
                    <i class="fas fa-print me-1"></i>Print
                </button>
                <button type="button" class="btn btn-success" id="approveRequisition">
                    <i class="fas fa-check me-1"></i>Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Requisition Modal -->
<div class="modal fade" id="editRequisitionModal" tabindex="-1" aria-labelledby="editRequisitionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editRequisitionModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Requisition
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteRequisitionModal" tabindex="-1" aria-labelledby="deleteRequisitionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteRequisitionModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                    <h5>Are you sure you want to delete this requisition?</h5>
                    <p class="text-muted">This action cannot be undone. All items in this requisition will be permanently removed.</p>
                    <input type="hidden" id="requisitionToDelete" value="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Required Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<!-- Add these in your <head> section -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Also load departments when window is fully loaded (final backup)
    $(window).on('load', function() {
        console.log('Window loaded, ensuring departments are loaded...');
        setTimeout(function() {
            const departmentSelect = $('#department');
            if (departmentSelect.find('option').length <= 1) {
                console.log('Department select still empty on window load, loading departments...');
                loadDepartments();
            }
        }, 1000);
    });
</script>
