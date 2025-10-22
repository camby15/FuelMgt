<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">

<style>
    :root {
        --primary: #4361ee;
        --primary-light: #4cc9f0;
        --secondary: #7209b7;
        --success: #06d6a0;
        --info: #4cc9f0;
        --warning: #ffbe0b;
        --danger: #ef476f;
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #6c757d;
        --gray-light: #f8f9fa;
        --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        --border-radius: 0.5rem;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
        color: #333;
    }
    
    .supplier-container {
        background-color: #f5f7fb;
        min-height: calc(100vh - 70px);
        padding: 2rem;
        transition: var(--transition);
    }
    
    .supplier-card {
        background: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        margin-bottom: 1.5rem;
        border: none;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    
    .supplier-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, var(--primary), var(--primary-light));
        z-index: 2;
    }
    
    .supplier-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow);
    }
    
    .supplier-card .card-header {
        background: #fff;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.25rem 1.5rem;
        position: relative;
    }
    
    .supplier-card .card-body {
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .supplier-card .card-header {
        background: #fff;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.25rem 1.5rem;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .supplier-card .card-header h5 {
        font-weight: 600;
        color: #2d3748;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .supplier-card .card-header h5 i {
        color: var(--primary);
        font-size: 1.1em;
    }
    
    .supplier-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #fff;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        box-shadow: 0 4px 6px rgba(67, 97, 238, 0.15);
        transition: var(--transition);
    }
    
    .supplier-avatar i {
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }
    
    .supplier-avatar:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(67, 97, 238, 0.2);
    }
    
    .status-badge {
        padding: 0.4rem 0.9rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid transparent;
    }
    
    .status-badge i {
        transition: transform 0.2s ease;
    }
    
    .status-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Hover effect for table rows */
    tr {
        position: relative;
    }
    
    tr::after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, rgba(67, 97, 238, 0.05) 0%, rgba(255,255,255,0) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    
    tr:hover::after {
        opacity: 1;
    }
    
    /* Form Wizard Styles */
    .steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .steps::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e9ecef;
        z-index: 1;
    }
    
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        background: white;
        padding: 0 1rem;
    }
    
    .step-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 0.5rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .step.active .step-icon {
        background-color: #4361ee;
        color: white;
        border-color: #4361ee;
    }
    
    .step.completed .step-icon {
        background-color: #06d6a0;
        color: white;
        border-color: #06d6a0;
    }
    
    .step-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-align: center;
    }
    
    .step.active .step-label {
        color: #4361ee;
        font-weight: 500;
    }
    
    .step-content {
        transition: all 0.3s ease;
    }
    
    /* Form Controls */
    .form-control-lg, .form-select-lg {
        padding: 0.75rem 1rem;
        font-size: 0.9375rem;
        border-radius: 0.5rem;
    }
    
    .input-group-lg > .form-control,
    .input-group-lg > .form-select,
    .input-group-lg > .input-group-text {
        padding: 0.75rem 1rem;
        font-size: 0.9375rem;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    
    /* Modal Header */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
    }
    
    /* Form Sections */
    .form-section {
        margin-bottom: 2rem;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .step-label {
            display: none !important;
        }
        
        .steps {
            padding: 0 1rem;
        }
        
        .step {
            padding: 0 0.5rem;
        }
    }
    
    /* Dropdown fixes */
    .dropdown-menu.show {
        display: block !important;
        z-index: 1050;
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        margin-top: 0.125rem !important;
        background-color: #fff !important;
        border: 1px solid rgba(0,0,0,.15) !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175) !important;
    }
    
    .dropdown-menu {
        z-index: 1050;
        min-width: 220px;
    }
    
    .dropdown {
        position: relative;
    }
    
    .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.375rem 1rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        text-decoration: none;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
        cursor: pointer;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #1e2125;
    }
    
    .dropdown-header {
        display: block;
        padding: 0.5rem 1rem;
        margin-bottom: 0;
        font-size: 0.875rem;
        color: #6c757d;
        white-space: nowrap;
    }
    
    .dropdown-divider {
        height: 0;
        margin: 0.5rem 0;
        border: 0;
        border-top: 1px solid rgba(0,0,0,.15);
    }
</style>

<div class="supplier-container">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-5">
        <div class="mb-3 mb-lg-0">
            <div class="d-flex align-items-center mb-2">
                <h1 class="h4 mb-0">
                    <i class="fas fa-truck me-3" style="color:rgb(182, 8, 8);"></i>
                    <span>Supplier Directory</span>
                </h1>

            </div>
            <p class="text-muted mb-0">Efficiently manage your supplier relationships and procurement processes</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 1rem;">
            <div class="search-box" style="flex: 1; max-width: 300px;">
                <input type="text" class="form-control" placeholder="Search suppliers...">
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal" style="white-space: nowrap;">
                <i class="fas fa-plus me-2"></i>Add Supplier
            </button>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="card supplier-card animate-fade-in">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h5 class="card-title mb-0">
                    <i class="fas fa-list-ul me-2"></i>All Suppliers
                </h5>
            </div>
            <div class="d-flex gap-2 mt-3 mt-md-0">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center" type="button" 
                            id="filterDropdownBtn">
                        <i class="fas fa-filter me-1"></i> 
                        <span>Filter</span>
                        <span class="badge bg-primary rounded-pill ms-2" id="filterCount">0</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 220px;" id="filterDropdownMenu">
                        <li class="dropdown-header fw-semibold text-uppercase small mb-2 text-muted">Status</li>
                        <li id="statusFilters">
                            <!-- Status filters will be loaded dynamically -->
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="dropdown-header fw-semibold text-uppercase small mb-2 text-muted">Locations</li>
                        <li id="locationFilters">
                            <!-- Location filters will be loaded dynamically -->
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-primary" href="#" data-filter="reset">
                                <i class="fas fa-sync-alt me-2"></i>Reset Filters
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="btn-group" role="group">
                    <button class="btn btn-outline-secondary btn-sm d-flex align-items-center" 
                            id="exportBtn"
                            data-bs-toggle="tooltip" 
                            title="Export Data">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" 
                            id="refreshBtn"
                            data-bs-toggle="tooltip" 
                            title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <style>
                    /* Enhanced table styling */
                    .table {
                        --bs-table-hover-bg: rgba(67, 97, 238, 0.03);
                        margin-bottom: 0;
                    }
                    
                    .table > :not(caption) > * > * {
                        padding: 1rem 1.25rem;
                        border-bottom-width: 1px;
                        box-shadow: none;
                    }
                    
                    .table > thead > tr > th {
                        border-bottom: 1px solid rgba(0,0,0,0.05);
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        font-size: 0.7rem;
                        color: #6c757d;
                        white-space: nowrap;
                        padding-top: 0.75rem;
                        padding-bottom: 0.75rem;
                        background-color: #f8fafc;
                    }
                    
                    .table > tbody > tr {
                        transition: all 0.2s ease;
                        position: relative;
                    }
                    
                    .table > tbody > tr:not(:last-child) {
                        border-bottom: 1px solid rgba(0,0,0,0.03);
                    }
                    
                    .table > tbody > tr:hover {
                        background-color: #f8fafc !important;
                        transform: translateX(4px);
                        box-shadow: -4px 0 0 0 var(--primary);
                    }
                    
                    .table > tbody > tr td {
                        vertical-align: middle;
                        transition: all 0.2s ease;
                        position: relative;
                        z-index: 1;
                    }
                    
                    /* Checkbox styling */
                    .form-check-input {
                        width: 1.1em;
                        height: 1.1em;
                        margin-top: 0.15em;
                        border: 1px solid #dee2e6;
                        transition: all 0.2s ease;
                    }
                    
                    .form-check-input:checked {
                        background-color: var(--primary);
                        border-color: var(--primary);
                    }
                    
                    /* Action buttons */
                    .btn-icon {
                        width: 28px;
                        height: 28px;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        padding: 0;
                        transition: all 0.2s ease;
                    }
                    
                    .btn-icon:hover {
                        transform: translateY(-2px);
                    }
                    
                    /* Status badges */
                    .badge {
                        font-weight: 500;
                        letter-spacing: 0.3px;
                    }
                    
                    /* Rating stars */
                    .rating {
                        color:rgb(255, 193, 7);
                        letter-spacing: 2px;
                    }
                    
                    /* Hover effect for table rows */
                    tr {
                        position: relative;
                    }
                    
                    tr::after {
                        content: '';
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(90deg, rgba(67, 97, 238, 0.05) 0%, rgba(255,255,255,0) 100%);
                        opacity: 0;
                        transition: opacity 0.3s ease;
                        pointer-events: none;
                    }
                    
                    tr:hover::after {
                        opacity: 1;
                    }
                    
                    /* Ratings Modal Styles */
                    .ratings-modal .swal2-popup {
                        border-radius: 15px;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                    }
                    
                    .ratings-container {
                        scrollbar-width: thin;
                        scrollbar-color: #cbd5e0 #f7fafc;
                    }
                    
                    .ratings-container::-webkit-scrollbar {
                        width: 6px;
                    }
                    
                    .ratings-container::-webkit-scrollbar-track {
                        background: #f7fafc;
                        border-radius: 3px;
                    }
                    
                    .ratings-container::-webkit-scrollbar-thumb {
                        background: #cbd5e0;
                        border-radius: 3px;
                    }
                    
                    .rating-item {
                        transition: all 0.3s ease;
                        border: 1px solid #e2e8f0 !important;
                    }
                    
                    .rating-item:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                        border-color: #cbd5e0 !important;
                    }
                    
                    .rating-stars {
                        color: #fbbf24;
                    }
                    
                    .rating-stars i {
                        margin-right: 2px;
                    }
                    
                    .rating-comment {
                        background: #f8fafc;
                        padding: 10px;
                        border-radius: 8px;
                        border-left: 3px solid #3b82f6;
                    }
                    
                    .rating-meta {
                        border-top: 1px solid #e2e8f0;
                        padding-top: 8px;
                        margin-top: 8px;
                    }
                </style>
                
                <!-- Refresh Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Suppliers</h5>
                    <div>
                        <button class="btn btn-outline-primary me-2" id="refreshSuppliersBtn">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                
                
                <table class="table align-middle mb-0" id="suppliersTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 40px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllSuppliers">
                                    <label class="form-check-label" for="selectAllSuppliers"></label>
                                </div>
                            </th>
                            <th class="text-uppercase small fw-bold text-muted">Supplier</th>
                            <th class="text-uppercase small fw-bold text-muted">Contact</th>
                            <th class="text-uppercase small fw-bold text-muted">Location</th>
                            <th class="text-uppercase small fw-bold text-muted">Status</th>
                            <th class="text-uppercase small fw-bold text-muted">Last Order</th>
                            <th class="text-uppercase small fw-bold text-muted">Rating</th>
                            <th class="text-end pe-4 text-uppercase small fw-bold text-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic content will be loaded here -->
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Loading suppliers...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="d-flex align-items-center">
                    <div class="text-muted small me-3" id="supplierPaginationInfo">
                    Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">2</span> of <span class="fw-semibold">2</span> entries
                    </div>
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="supplierPagination">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>




        </div>
    </div>
</div>

<!-- View Supplier Modal -->
<div class="modal fade" id="viewSupplierModal" tabindex="-1" aria-labelledby="viewSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewSupplierModalLabel">Supplier Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Header with Supplier Info -->
                <div class="bg-light p-4">
                    <div class="d-flex flex-column flex-md-row align-items-center">
                        <div class="me-md-4 mb-3 mb-md-0">
                            <div class="avatar-xl bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 text-center text-md-start">
                            <h3 class="mb-1" id="supplierName">Ghana Logistics Ltd</h3>
                            <div class="d-flex flex-wrap justify-content-center justify-content-md-start align-items-center gap-2 mb-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary" id="supplierType">Logistics</span>
                                <span class="badge bg-success bg-opacity-10 text-success" id="supplierStatus">
                                    <i class="fas fa-check-circle me-1"></i> Active
                                </span>
                                <div class="d-flex align-items-center text-warning" id="supplierRating">
                                    <i class="fas fa-star"></i>
                                    <span class="ms-1 text-dark">4.7</span>
                                </div>
                            </div>
                            <p class="text-muted mb-0" id="supplierLocation">Accra, Ghana</p>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="border-bottom">
                    <ul class="nav nav-tabs nav-tabs-custom" id="supplierTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                <i class="fas fa-info-circle me-2"></i>Overview
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                                <i class="fas fa-address-book me-2"></i>Contact
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                                <i class="fas fa-history me-2"></i>Activity
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content p-4">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                        <h5 class="mb-4">Company Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Business Type</label>
                                    <p class="mb-0" id="overviewType">Logistics</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Established</label>
                                    <p class="mb-0" id="overviewEstablished">2015</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Tax ID</label>
                                    <p class="mb-0" id="overviewTaxId">C12345678</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-0">
                                    <label class="form-label text-muted small mb-1">Notes</label>
                                    <p class="mb-0" id="overviewNotes">Reliable logistics partner with nationwide coverage.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Tab -->
                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Primary Contact</label>
                                    <p class="mb-0" id="contactName">Kwame Mensah</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Email</label>
                                    <p class="mb-0">
                                        <a href="mailto:kwame@ghanalogistics.gh" id="contactEmail">kwame@ghanalogistics.gh</a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Phone</label>
                                    <p class="mb-0" id="contactPhone">+233 24 123 4567</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Website</label>
                                    <p class="mb-0">
                                        <a href="https://www.ghanalogistics.gh" target="_blank" id="contactWebsite">www.ghanalogistics.gh</a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-0">
                                    <label class="form-label text-muted small mb-1">Address</label>
                                    <p class="mb-0" id="contactAddress">123 Business Ave, North Industrial Area<br>Accra, Greater Accra GA123<br>Ghana</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Tab -->
                    <div class="tab-pane fade" id="activity" role="tabpanel">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-badge bg-success"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">Order #ORD-2023-1001</h6>
                                        <small class="text-muted">2 days ago</small>
                                    </div>
                                    <p class="mb-1 small">Delivery completed successfully</p>
                                    <span class="badge bg-success bg-opacity-10 text-success">Completed</span>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-badge bg-primary"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">Order #ORD-2023-0998</h6>
                                        <small class="text-muted">1 week ago</small>
                                    </div>
                                    <p class="mb-1 small">In transit - Expected delivery tomorrow</p>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">In Transit</span>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-badge bg-warning"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">Contract Renewal</h6>
                                        <small class="text-muted">2 weeks ago</small>
                                    </div>
                                    <p class="mb-1 small">Annual service contract renewed for 2023-2024</p>
                                    <span class="badge bg-warning bg-opacity-10 text-warning">Updated</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit Supplier
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #3b7ddd;
        --primary-light: #6c8ebf;
        --primary-dark: #2c6ecb;
        --secondary: #6c757d;
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --light: #f8f9fc;
        --dark: #3a3b45;
        --gray-100: #f8f9fc;
        --gray-200: #eaecf4;
        --gray-300: #dddfeb;
        --gray-400: #d1d3e2;
        --gray-500: #b7b9cc;
        --gray-600: #858796;
        --gray-700: #6e707e;
        --gray-800: #5a5c69;
        --gray-900: #3a3b45;
        --border: #e3e6f0;
        --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        --transition: all 0.2s ease-in-out;
    }

    /* Card Styles */
    .supplier-card {
        border: 1px solid rgba(0, 0, 0, 0.04);
        border-radius: 12px;
        margin-bottom: 1.75rem;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        background: #fff;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .supplier-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }

    .supplier-header {
        padding: 1.75rem 1.5rem 1.25rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        position: relative;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        overflow: hidden;
    }

    .supplier-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 60%);
        pointer-events: none;
    }

    .supplier-rating {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .supplier-avatar {
        display: flex;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        font-weight: 600;
        margin-bottom: 1rem;
        position: relative;
        border: 2px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .supplier-card:hover .supplier-avatar {
        transform: scale(1.05);
    }

    .supplier-avatar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            135deg,
            rgba(255, 255, 255, 0.4) 0%,
            rgba(255, 255, 255, 0.1) 100%
        );
        transform: rotate(45deg);
        z-index: 1;
    }

    .supplier-stats {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 1.5rem;
        background: var(--gray-100);
        border-bottom: 1px solid var(--gray-200);
    }

    .stat-item {
        text-align: center;
        flex: 1;
    }

    .stat-value {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.25rem;
        font-size: 1rem;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.7rem;
        color: var(--gray-700);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
    }
    
    .contract-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
    
    .supplier-body {
        padding: 1.5rem;
        flex-grow: 1;
    }
    
    .icon-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: var(--gray-100);
        color: var(--primary);
    }

    .supplier-body::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
    }

    .supplier-info {
        margin-bottom: 1rem;
    }

    .info-label {
        font-size: 0.75rem;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 0.9rem;
        color: var(--gray-800);
        font-weight: 500;
        margin-bottom: 0.75rem;
    }

    .performance-metric {
        margin-bottom: 1.25rem;
        position: relative;
        padding-left: 1rem;
    }

    .performance-metric:not(:last-child)::after {
        content: '';
        position: absolute;
        bottom: -0.75rem;
        left: 1rem;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0,0,0,0.05), transparent);
    }

    .performance-metric .progress {
        height: 8px;
        border-radius: 10px;
        background-color: var(--gray-100);
        overflow: visible;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .progress-bar {
        border-radius: 10px;
        position: relative;
        overflow: visible;
        transition: width 0.6s ease;
        background-color: var(--primary);
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        top: -2px;
        right: 0;
        width: 1px;
        height: 12px;
        background: white;
        border-radius: 2px;
        box-shadow: 0 0 5px rgba(255, 255, 255, 0.8);
    }

    .metric-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .metric-name {
        font-size: 0.8rem;
        color: var(--gray-700);
    }

    .metric-value {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--primary);
    }

    .contract-item {
        background: var(--gray-100);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid var(--gray-200);
        position: relative;
        transition: all 0.3s ease;
    }

    .contract-item:hover {
        transform: translateX(5px);
        box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.05);
    }

    .contract-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, var(--primary), var(--primary-light));
        border-radius: 4px 0 0 4px;
    }
    
    .contract-item:hover {
        background: #f0f4ff;
        transform: translateX(4px);
    }
    
    .contract-title i {
        font-size: 1.1em;
        color: var(--primary-color);
    }
    
    .contract-dates {
        font-size: 0.8rem;
        color: var(--secondary-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        z-index: 1;
    }
    
    .contract-dates i {
        font-size: 0.9em;
        color: var(--secondary-color);
        opacity: 0.7;
    }
    
    .supplier-actions {
        padding: 1.25rem 1.5rem;
        background: #f8fafd;
        border-top: 1px solid rgba(0, 0, 0, 0.04);
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: auto;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        position: relative;
        z-index: 1;
    }
    
    .supplier-actions::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0,0,0,0.05), transparent);
    }
    
    .supplier-actions::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0,0,0,0.05), transparent);
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.05);
        color: #5a5c69;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }
    
    .action-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(0, 0, 0, 0.1);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%, -50%);
        transform-origin: 50% 50%;
        border-color: var(--primary);
    }
    
    .action-btn i {
        font-size: 0.9em;
        margin-right: 0.4rem;
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .action-btn:active {
        transform: translateY(0);
    }
    
    .action-btn.view {
        color: var(--primary);
    }
    
    .action-btn.view:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .action-btn.edit:hover {
        background: var(--warning);
        color: var(--dark);
        border-color: var(--warning);
    }
    
    .action-btn.delete:hover {
        background: var(--danger);
        color: white;
        border-color: var(--danger);
    }
    
    .action-btn.view:hover {
        background: rgba(78, 115, 223, 0.15);
        color: var(--primary-dark);
    }
    
    .action-btn.edit {
        background: rgba(108, 117, 125, 0.1);
        color: var(--secondary-color);
    }
    
    .action-btn.edit:hover {
        background: rgba(108, 117, 125, 0.15);
        color: #495057;
    }
    
    .action-btn.po {
        background: rgba(28, 200, 138, 0.1);
        color: var(--success-color);
    }
    
    .action-btn.po:hover {
        background: rgba(28, 200, 138, 0.15);
        color: #169b6b;
    }
    
    .action-btn.contact {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }
    
    .action-btn.contact:hover {
        background: rgba(13, 110, 253, 0.15);
        color: #0a58ca;
    }
    
    /* Search and filter styles */
    .search-box {
        position: relative;
        transition: var(--transition);
    }
    
    .search-box:focus-within {
        transform: translateY(-1px);
    }
    
    .search-box .form-control {
        padding: 0.5rem 1rem 0.5rem 2.75rem;
        border: 1px solid var(--border);
        border-radius: 0.375rem;
        font-size: 0.9rem;
        transition: var(--transition);
        height: 2.5rem;
    }
    
    .search-box .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }
    
    .search-icon {
        position: absolute;
        left: 1.1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #a1a5b7;
        z-index: 5;
        transition: all 0.2s ease;
    }
    
    .search-box:focus-within .search-icon {
        color: var(--primary-color);
    }
    
    .form-select {
        height: 2.5rem;
        padding: 0.5rem 2.25rem 0.5rem 1rem;
        font-size: 0.9rem;
        border: 1px solid var(--border);
        border-radius: 0.375rem;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236c757d' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        transition: var(--transition);
        cursor: pointer;
    }
    
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #e2e5ec;
        height: calc(2.5rem + 2px);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-weight: 500;
        padding: 0.5rem 1rem;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #d7dae0;
        color: #5a5f6e;
    }
    
    .btn-outline-secondary i {
        margin-right: 0.4rem;
    }
    
    /* Custom badges */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    /* Ripple effect */

    
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
</style>
<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="addSupplierForm" class="needs-validation" novalidate>
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Add New Supplier
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Progress Bar -->
                <div class="progress rounded-0" style="height: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <div class="modal-body p-4">
        <!-- Step 1: Company Information -->
        <div class="step-content" id="step-1">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-soft-primary rounded-circle p-2 me-3">
                    <i class="fas fa-building text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0">Company Information</h6>
                    <p class="text-muted small mb-0">Basic details about the supplier's company</p>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="companyName" class="form-label">Company/Enterprise Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-building text-muted"></i></span>
                        <input type="text" class="form-control" id="companyName" name="company_name" placeholder="e.g., ABC Suppliers Limited" required>
                    </div>
                    <div class="invalid-feedback">Please enter company/enterprise name</div>
                </div>
                
                <div class="col-md-6">
                    <label for="companyType" class="form-label">Business Type <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-tag text-muted"></i></span>
                        <select class="form-select" id="companyType" name="business_type" required>
                            <option value="">Select Business Type</option>
                            <option value="sole_proprietor">Sole Proprietorship</option>
                            <option value="partnership">Partnership</option>
                            <option value="ltd">Private Limited Company (Ltd)</option>
                            <option value="plc">Public Limited Company (PLC)</option>
                            <option value="ngo">NGO/Foundation</option>
                            <option value="cooperative">Cooperative Society</option>
                            <option value="government">Government Entity</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="invalid-feedback">Please select a business type</div>
                </div>
                
                <div class="col-md-6">
                    <label for="tin" class="form-label">Tax Identification Number (TIN) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-id-card text-muted"></i></span>
                        <input type="text" class="form-control" id="tin" name="tin" placeholder="C0000000000" pattern="[A-Z]\d{10}" required>
                    </div>
                    <small class="form-text text-muted">Format: C followed by 10 digits</small>
                    <div class="invalid-feedback">Please enter a valid TIN (e.g., C0001234567)</div>
                </div>
                
                <div class="col-md-6">
                    <label for="vatNumber" class="form-label">VAT Registration Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-file-invoice-dollar text-muted"></i></span>
                        <input type="text" class="form-control" id="vatNumber" name="vat_number" placeholder="e.g., 123456789101">
                    </div>
                    <small class="form-text text-muted">12-digit VAT number (if registered)</small>
                </div>
                
                <div class="col-md-6">
                    <label for="ssnitNumber" class="form-label">SSNIT Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-user-shield text-muted"></i></span>
                        <input type="text" class="form-control" id="ssnitNumber" name="ssnit_number" placeholder="e.g., C0000000000">
                    </div>
                    <small class="form-text text-muted">For employee contributions</small>
                </div>
                
                <div class="col-md-6">
                    <label for="yearEstablished" class="form-label">Year Established</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-muted"></i></span>
                        <input type="number" class="form-control" id="yearEstablished" name="year_established" min="1957" max="2099" step="1" value="2023">
                    </div>
                    <small class="form-text text-muted">Year of business registration in Ghana</small>
                </div>
                
                <div class="col-md-6">
                    <label for="registrationNumber" class="form-label">Registrar General's Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-file-contract text-muted"></i></span>
                        <input type="text" class="form-control" id="registrationNumber" name="registration_number" placeholder="e.g., BN123456789">
                    </div>
                    <small class="form-text text-muted">From Registrar General's Department</small>
                </div>
                
                <div class="col-12">
                    <label for="companyDescription" class="form-label">Nature of Business</label>
                    <textarea class="form-control" id="companyDescription" name="company_description" rows="2" placeholder="Detailed description of business activities (e.g., Import/Export, Manufacturing, Retail, Services)"></textarea>
                    <small class="form-text text-muted">Please describe your main business activities in Ghana</small>
                </div>
                
                <div class="col-md-6">
                    <label for="businessSector" class="form-label">Business Sector <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-industry text-muted"></i></span>
                        <select class="form-select" id="businessSector" name="business_sector" required>
                            <option value="">Select Business Sector</option>
                            <option value="agriculture">Agriculture & Agro-processing</option>
                            <option value="mining">Mining & Quarrying</option>
                            <option value="manufacturing">Manufacturing</option>
                            <option value="utilities">Utilities (Water, Electricity, Gas)</option>
                            <option value="construction">Construction</option>
                            <option value="trade">Trade (Wholesale & Retail)</option>
                            <option value="hospitality">Hospitality & Tourism</option>
                            <option value="transport">Transport & Storage</option>
                            <option value="finance">Financial Services</option>
                            <option value="real_estate">Real Estate</option>
                            <option value="professional">Professional Services</option>
                            <option value="education">Education</option>
                            <option value="health">Health & Social Work</option>
                            <option value="other">Other Services</option>
                        </select>
                    </div>
                    <div class="invalid-feedback">Please select a business sector</div>
                </div>
                
                <div class="col-md-6">
                    <label for="companySize" class="form-label">Company Size <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-users text-muted"></i></span>
                        <select class="form-select" id="companySize" name="company_size" required>
                            <option value="">Select Company Size</option>
                            <option value="micro">Micro Enterprise (1-5 employees)</option>
                            <option value="small">Small Enterprise (6-29 employees)</option>
                            <option value="medium">Medium Enterprise (30-99 employees)</option>
                            <option value="large">Large Enterprise (100+ employees)</option>
                        </select>
                    </div>
                    <div class="invalid-feedback">Please select company size</div>
                </div>
            </div>
        </div>
            
        <!-- Step 2: Contact Information -->
        <div class="step-content d-none" id="step-2">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-soft-primary rounded-circle p-2 me-3">
                    <i class="fas fa-address-book text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0">Contact Information</h6>
                    <p class="text-muted small mb-0">Primary contact details for this supplier</p>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="primaryContact" class="form-label">Primary Contact Person <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                        <input type="text" class="form-control" id="primaryContact" name="primary_contact" placeholder="Full name (as on ID)" required>
                    </div>
                    <div class="invalid-feedback">Please enter the full name of primary contact person</div>
                </div>
                
                <div class="col-md-6">
                    <label for="contactPosition" class="form-label">Position in Company <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-id-badge text-muted"></i></span>
                        <select class="form-select" id="contactPosition" name="contact_position" required>
                            <option value="">Select Position</option>
                            <option value="ceo">CEO/Managing Director</option>
                            <option value="director">Director</option>
                            <option value="manager">Manager</option>
                            <option value="officer">Administrative Officer</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="invalid-feedback">Please select a position</div>
                </div>
                
                <div class="col-md-6">
                    <label for="jobTitle" class="form-label">Job Title</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-briefcase text-muted"></i></span>
                        <input type="text" class="form-control" id="jobTitle" name="job_title" placeholder="e.g., Sales Manager">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label for="email" class="form-label">Business Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="contact@company.com.gh" required>
                    </div>
                    <div class="invalid-feedback">Please enter a valid business email address</div>
                </div>
                
                <div class="col-md-6">
                    <label for="phone" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">+233</span>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="24 123 4567" pattern="[0-9]{9}" required>
                    </div>
                    <small class="form-text text-muted">Format: 24/20/26/54/55/59 followed by 7 digits</small>
                    <div class="invalid-feedback">Please enter a valid Ghanaian mobile number (e.g., 24 123 4567)</div>
                </div>
                
                <div class="col-md-6">
                    <label for="whatsappNumber" class="form-label">WhatsApp Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fab fa-whatsapp text-muted"></i></span>
                        <input type="tel" class="form-control" id="whatsappNumber" name="whatsapp_number" placeholder="24 123 4567" pattern="[0-9]{9}">
                    </div>
                    <small class="form-text text-muted">If different from mobile number</small>
                </div>
                
                <div class="col-md-6">
                    <label for="landline" class="form-label">Landline (Office)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-phone-alt text-muted"></i></span>
                        <input type="tel" class="form-control" id="landline" name="landline" placeholder="0302 123456">
                    </div>
                    <small class="form-text text-muted">Include area code (e.g., 0302 for Accra)</small>
                </div>
                
                <div class="col-md-6">
                    <label for="website" class="form-label">Company Website</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-globe text-muted"></i></span>
                        <input type="url" class="form-control" id="website" name="website" placeholder="https://">
                    </div>
                    <small class="form-text text-muted">e.g., https://www.yourcompany.com.gh</small>
                </div>
                
                <div class="col-md-6">
                    <label for="socialMedia" class="form-label">Social Media</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-hashtag text-muted"></i></span>
                        <input type="text" class="form-control" id="socialMedia" name="social_media" placeholder="Facebook/Twitter/LinkedIn">
                    </div>
                    <small class="form-text text-muted">Social media handles (e.g., @yourcompany_gh)</small>
                </div>
                
                <!-- Ghana-Specific Business Information -->
                <div class="col-12 mt-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-soft-primary rounded-circle p-2 me-3">
                            <i class="fas fa-building-flag text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Ghana Business Details</h6>
                            <p class="text-muted small mb-0">Additional information for Ghanaian businesses</p>
                        </div>
                    </div>
                    <hr class="mt-2">
                </div>
                
                <div class="col-md-6">
                    <label for="gipcRegistration" class="form-label">GIPC Registration Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-file-signature text-muted"></i></span>
                        <input type="text" class="form-control" id="gipcRegistration" name="gipc_registration" placeholder="e.g., GIPC/12345/2023">
                    </div>
                    <small class="form-text text-muted">If registered with Ghana Investment Promotion Centre</small>
                </div>
                
                <div class="col-md-6">
                    <label for="fdiaStatus" class="form-label">FDIA Status</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-passport text-muted"></i></span>
                        <select class="form-select" id="fdiaStatus" name="fdia_status">
                            <option value="">Select Status</option>
                            <option value="none">Not Applicable</option>
                            <option value="partial">Partially Foreign Owned</option>
                            <option value="wholly">Wholly Foreign Owned</option>
                            <option value="joint">Joint Venture</option>
                        </select>
                    </div>
                    <small class="form-text text-muted">Foreign Direct Investment Act status</small>
                </div>
                
                <div class="col-md-6">
                    <label for="ghanapostAddress" class="form-label">Ghana Post Digital Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-map-marked text-muted"></i></span>
                        <input type="text" class="form-control" id="ghanapostAddress" name="ghanapost_address" placeholder="e.g., GA-123-4567">
                    </div>
                    <small class="form-text text-muted">Official Ghana Post GPS address</small>
                </div>
                
                <div class="col-md-6">
                    <label for="localCouncil" class="form-label">Metro/Municipal/District Assembly</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-landmark text-muted"></i></span>
                        <input type="text" class="form-control" id="localCouncil" name="local_council" placeholder="e.g., Accra Metropolitan Assembly">
                    </div>
                    <small class="form-text text-muted">Local government authority</small>
                </div>
                
                <div class="col-12 mt-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-soft-primary rounded-circle p-2 me-3">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Address Information</h6>
                            <p class="text-muted small mb-0">Physical location of the company</p>
                        </div>
                    </div>
                    <hr class="mt-2">
                </div>
                
                <div class="col-12">
                    <label for="streetAddress" class="form-label">Street Address/Landmark <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-map-marked-alt text-muted"></i></span>
                        <input type="text" class="form-control" id="streetAddress" name="street_address" placeholder="e.g., 123 Osu Ako-Adjei Road" required>
                    </div>
                    <small class="form-text text-muted">Include street name and number, or well-known landmark</small>
                    <div class="invalid-feedback">Please enter the street address or landmark</div>
                </div>
                
                <div class="col-md-6">
                    <label for="area" class="form-label">Area/Suburb <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-muted"></i></span>
                        <input type="text" class="form-control" id="area" name="area" placeholder="e.g., Osu" required>
                    </div>
                    <div class="invalid-feedback">Please enter the area or suburb</div>
                </div>
                
                <div class="col-md-6">
                    <label for="city" class="form-label">City/Town <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-city text-muted"></i></span>
                        <select class="form-select" id="city" name="city" required>
                            <option value="">Select City/Town</option>

                            <!-- Ahafo Region -->
                            <optgroup label="Ahafo Region">
                              <option value="goaso">Goaso</option>
                              <option value="bechem">Bechem</option>
                              <option value="kenyasi">Kenyasi</option>
                            </optgroup>

                            <!-- Ashanti Region -->
                            <optgroup label="Ashanti Region">
                              <option value="kumasi">Kumasi</option>
                              <option value="obuasi">Obuasi</option>
                              <option value="konongo">Konongo</option>
                              <option value="ejura">Ejura</option>
                              <option value="mampong">Mampong</option>
                            </optgroup>

                            <!-- Bono Region -->
                            <optgroup label="Bono Region">
                              <option value="sunyani">Sunyani</option>
                              <option value="dormaa-ahu">Dormaa Ahenkro</option>
                              <option value="berekum">Berekum</option>
                            </optgroup>

                            <!-- Bono East Region -->
                            <optgroup label="Bono East Region">
                              <option value="techiman">Techiman</option>
                              <option value="kintampo">Kintampo</option>
                              <option value="nkoranza">Nkoranza</option>
                            </optgroup>

                            <!-- Central Region -->
                            <optgroup label="Central Region">
                              <option value="cape-coast">Cape Coast</option>
                              <option value="elmina">Elmina</option>
                              <option value="winneba">Winneba</option>
                              <option value="agona-swedru">Agona Swedru</option>
                            </optgroup>

                            <!-- Eastern Region -->
                            <optgroup label="Eastern Region">
                              <option value="koforidua">Koforidua</option>
                              <option value="nkawkaw">Nkawkaw</option>
                              <option value="akim-oda">Akim Oda</option>
                              <option value="nsawam">Nsawam</option>
                            </optgroup>

                            <!-- Greater Accra Region -->
                            <optgroup label="Greater Accra Region">
                              <option value="accra">Accra</option>
                              <option value="tema">Tema</option>
                              <option value="madina">Madina</option>
                              <option value="ashaiman">Ashaiman</option>
                              <option value="teshie">Teshie</option>
                              <option value="lapaz">La Paz</option>
                            </optgroup>

                            <!-- North East Region -->
                            <optgroup label="North East Region">
                              <option value="nalerigu">Nalerigu</option>
                              <option value="walewale">Walewale</option>
                              <option value="chereponi">Chereponi</option>
                            </optgroup>

                            <!-- Northern Region -->
                            <optgroup label="Northern Region">
                              <option value="tamale">Tamale</option>
                              <option value="yendi">Yendi</option>
                              <option value="saboba">Saboba</option>
                            </optgroup>

                            <!-- Oti Region -->
                            <optgroup label="Oti Region">
                              <option value="dambai">Dambai</option>
                              <option value="krachi-east">Krachi East</option>
                              <option value="nkwanta">Nkwanta</option>
                            </optgroup>

                            <!-- Savannah Region -->
                            <optgroup label="Savannah Region">
                              <option value="damongo">Damongo</option>
                              <option value="buipe">Buipe</option>
                              <option value="salaga">Salaga</option>
                            </optgroup>

                            <!-- Upper East Region -->
                            <optgroup label="Upper East Region">
                              <option value="bolgatanga">Bolgatanga</option>
                              <option value="navrongo">Navrongo</option>
                              <option value="bawku">Bawku</option>
                            </optgroup>

                            <!-- Upper West Region -->
                            <optgroup label="Upper West Region">
                              <option value="wa">Wa</option>
                              <option value="jirapa">Jirapa</option>
                              <option value="lawra">Lawra</option>
                            </optgroup>

                            <!-- Volta Region -->
                            <optgroup label="Volta Region">
                              <option value="ho">Ho</option>
                              <option value="hohoe">Hohoe</option>
                              <option value="kpando">Kpando</option>
                              <option value="sogakope">Sogakope</option>
                            </optgroup>

                            <!-- Western Region -->
                            <optgroup label="Western Region">
                              <option value="takoradi">Takoradi</option>
                              <option value="sekondi">Sekondi</option>
                              <option value="tarkwa">Tarkwa</option>
                              <option value="prestea">Prestea</option>
                            </optgroup>

                            <!-- Western North Region -->
                            <optgroup label="Western North Region">
                              <option value="sefwi-wiawso">Sefwi Wiawso</option>
                              <option value="bibiani">Bibiani</option>
                              <option value="juaboso">Juaboso</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="invalid-feedback">Please select a city or town</div>
                </div>
                
                <div class="col-md-6">
                    <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-map text-muted"></i></span>
                        <select class="form-select" id="region" name="region" required>
                            <option value="">Select Region</option>
                            <option value="ahafo">Ahafo Region</option>
                            <option value="ashanti">Ashanti Region</option>
                            <option value="bono">Bono Region</option>
                            <option value="bono_east">Bono East Region</option>
                            <option value="central">Central Region</option>
                            <option value="eastern">Eastern Region</option>
                            <option value="greater_accra">Greater Accra Region</option>
                            <option value="north_east">North East Region</option>
                            <option value="northern">Northern Region</option>
                            <option value="oti">Oti Region</option>
                            <option value="savannah">Savannah Region</option>
                            <option value="upper_east">Upper East Region</option>
                            <option value="upper_west">Upper West Region</option>
                            <option value="volta">Volta Region</option>
                            <option value="western">Western Region</option>
                            <option value="western_north">Western North Region</option>
                        </select>
                    </div>
                    <div class="invalid-feedback">Please select a region</div>
                </div>
                
                <div class="col-md-6">
                    <label for="gpsAddress" class="form-label">GPS Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-map-pin text-muted"></i></span>
                        <input type="text" class="form-control" id="gpsAddress" name="gps_address" placeholder="e.g., GA-123-4567">
                    </div>
                    <small class="form-text text-muted">Optional: Ghana Post GPS address</small>
                </div>
                
                <div class="col-md-6">
                    <label for="postalCode" class="form-label">Postal Code</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-mail-bulk text-muted"></i></span>
                        <input type="text" class="form-control" id="postalCode" name="postal_code" placeholder="e.g., GA123">
                    </div>
                    <small class="form-text text-muted">Ghana Post GPS code (if available)</small>
                </div>
            </div>
        </div>
        
        <!-- Step 3: Additional Information -->
        <div class="step-content d-none" id="step-3">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-soft-primary rounded-circle p-2 me-3">
                    <i class="fas fa-info-circle text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0">Additional Information</h6>
                    <p class="text-muted small mb-0">Payment terms and other details</p>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="paymentTerms" class="form-label">Payment Terms</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-file-invoice-dollar text-muted"></i></span>
                        <select class="form-select" id="paymentTerms" name="payment_terms" required>
                            <option value="">Select Payment Terms</option>
                            <option value="net15">Net 15 Days</option>
                            <option value="net30" selected>Net 30 Days</option>
                            <option value="net60">Net 60 Days</option>
                            <option value="cod">Cash on Delivery</option>
                        </select>
                    </div>
                    <div class="invalid-feedback">Please select payment terms</div>
                </div>
                
                <div class="col-md-6">
                    <label for="currency" class="form-label">Preferred Currency</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-money-bill-wave text-muted"></i></span>
                        <select class="form-select" id="currency" name="currency" required>
                            <option value="GHS" selected>Ghana Cedi (GHS)</option>
                            <option value="USD">US Dollar (USD)</option>
                            <option value="EUR">Euro (EUR)</option>
                            <option value="GBP">British Pound (GBP)</option>
                        </select>
                    </div>
                    <div class="invalid-feedback">Please select a currency</div>
                </div>
                
                <div class="col-12">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light align-items-start pt-2"><i class="fas fa-sticky-note text-muted"></i></span>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional information about this supplier"></textarea>
                    </div>
                </div>
            </div>
        </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <div class="w-100 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary prev-step" data-prev="1" disabled>
                            <i class="fas fa-arrow-left me-1"></i> Previous
                        </button>
                        
                        <button type="button" class="btn btn-primary next-step" data-next="2">
                            Next <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                        
                        <button type="submit" class="btn btn-success d-none" id="submitFormBtn">
                            <i class="fas fa-save me-1"></i> Save Supplier
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Supplier
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Modal content will be loaded dynamically via AJAX -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Initialize suppliers page
function initializeSuppliersPage() {
    // Prevent multiple initializations
    if (window.suppliersPageInitialized) {
        console.log('Suppliers page already initialized, skipping...');
        return;
    }
    
    console.log('=== INITIALIZING SUPPLIERS PAGE ===');
    window.suppliersPageInitialized = true;
    
    let currentStep = 1;
    const totalSteps = 3;
    let currentFilters = {};

    // Initialize form
    function initializeForm() {
        $('.step-content').addClass('d-none');
        $('#step-1').removeClass('d-none');
        updateNavigation();
    }

    function updateNavigation() {
        const progressPercentage = (currentStep / totalSteps) * 100;
        $('.progress-bar').css('width', progressPercentage + '%');
        $('.prev-step').prop('disabled', currentStep === 1);
        
        if (currentStep < totalSteps) {
            $('.next-step').removeClass('d-none');
            $('#submitFormBtn').addClass('d-none');
        } else {
            $('.next-step').addClass('d-none');
            $('#submitFormBtn').removeClass('d-none');
        }
    }

    function validateStep(step) {
        let isValid = true;
        $(`#step-${step} .is-invalid`).removeClass('is-invalid');
        
        if (step === 1) {
            if (!$('#companyName').val()) {
                $('#companyName').addClass('is-invalid');
                isValid = false;
            }
            if (!$('#companyType').val()) {
                $('#companyType').addClass('is-invalid');
                isValid = false;
            }
        }
        else if (step === 2) {
            if (!$('#primaryContact').val()) {
                $('#primaryContact').addClass('is-invalid');
                isValid = false;
            }
            if (!$('#email').val()) {
                $('#email').addClass('is-invalid');
                isValid = false;
            }
        }
        
        return isValid;
    }

    $(document).on('click', '.next-step', function() {
        if (validateStep(currentStep)) {
            $(`#step-${currentStep}`).addClass('d-none');
            currentStep++;
            $(`#step-${currentStep}`).removeClass('d-none');
            updateNavigation();
        }
    });

    $(document).on('click', '.prev-step', function() {
        $(`#step-${currentStep}`).addClass('d-none');
        currentStep--;
        $(`#step-${currentStep}`).removeClass('d-none');
        updateNavigation();
    });

    $('#addSupplierModal').on('show.bs.modal', function() {
        resetAddSupplierModal(); // Ensure modal is reset when opened
        initializeForm();
    });
    
    // Add modal cleanup event listeners
    $('#addSupplierModal').on('hidden.bs.modal', function() {
        cleanupModalBackdrops();
        // Reset the form when modal is closed
        resetAddSupplierModal();
    });
    
    $('#editSupplierModal').on('hidden.bs.modal', function() {
        cleanupModalBackdrops();
    });
    
    $('#viewSupplierModal').on('hidden.bs.modal', function() {
        cleanupModalBackdrops();
    });

    // Initialize components
    initializeSelect2();
    initializeTooltips();
    
    // Load initial data
    fetchSuppliers();
    
    // Make functions globally accessible for debugging and external calls
    window.fetchSuppliers = fetchSuppliers;
    window.renderSuppliers = renderSuppliers;
    window.initializeSuppliersPage = initializeSuppliersPage;
    window.loadFilterData = loadFilterData;
    window.changeSupplierPage = changeSupplierPage;
    
    // Force cleanup on window load to fix any existing modal issues
    $(window).on('load', function() {
        setTimeout(function() {
            forceCleanupAllModals();
        }, 100);
    });
    
    // Form submission handlers
    $('#addSupplierForm').on('submit', handleAddSupplier);
    // $('#editSupplierForm').on('submit', handleUpdateSupplier);
    $(document).on('submit', '#editSupplierForm', handleUpdateSupplier);
    
    // Event handlers
    $(document).on('click', '.view-supplier', handleViewSupplier);
    $(document).on('click', '.edit-supplier', handleEditSupplier);
    $(document).on('click', '.delete-supplier', handleDeleteSupplier);
    $(document).on('click', '.rate-supplier', handleRateSupplier);
    $(document).on('click', '.view-ratings', handleViewRatings);
    
    
    // Refresh button handler
    $(document).on('click', '#refreshSuppliersBtn', function() {
        console.log('Refresh button clicked');
        fetchSuppliers(1, '', true); // Reset to page 1 and force refresh when refreshing
    });
    
    // Table refresh button handler (for the button in the table when no data)
    $(document).on('click', '#tableRefreshBtn', function() {
        console.log('Table refresh button clicked');
        fetchSuppliers(1, '', true); // Reset to page 1 and force refresh when refreshing
    });
    
    
    // Pagination click handler
    $(document).on('click', '#supplierPagination .page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        console.log('Pagination clicked, page data:', page);
        console.log('Element clicked:', $(this));
        console.log('Parent li classes:', $(this).parent().attr('class'));
        
        if (page && page > 0) {
            console.log('Pagination clicked, going to page:', page);
            fetchSuppliers(page, '', true); // Force refresh when changing pages
        } else {
            console.log('Invalid page number or disabled link');
        }
    });
    
    // Search functionality
    $('.search-box input').on('input', debounce(function() {
        const searchTerm = $(this).val().toLowerCase();
        filterSuppliers(searchTerm);
    }, 300));
    
    // Initialize Select2 dropdowns
    function initializeSelect2() {
        $('.select2').select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true,
            dropdownParent: $('#addSupplierModal')
        });
    }
    
    // Initialize tooltips
    function initializeTooltips() {
        $('[data-bs-toggle="tooltip"]').tooltip({
            trigger: 'hover',
            placement: 'top'
        });
    }
    
    // Reset add supplier modal to initial state
    function resetAddSupplierModal() {
        // Reset form
        $('#addSupplierForm')[0].reset();
        
        // Reset multi-step form to step 1
        currentStep = 1;
        $('.step-content').addClass('d-none');
        $('#step-1').removeClass('d-none');
        updateNavigation();
        
        // Clear validation states
        $('#addSupplierForm').removeClass('was-validated');
        $('#addSupplierForm .form-control').removeClass('is-valid is-invalid');
        $('#addSupplierForm .invalid-feedback').remove();
        
        // Reset form button state
        $('#addSupplierForm button[type="submit"]').prop('disabled', false).html(`
            <i class="fas fa-save me-2"></i>Save Supplier
        `);
    }
    
    // Clean up modal backdrops and reset page styles
    function cleanupModalBackdrops() {
        // Remove all modal backdrops
        $('.modal-backdrop').remove();
        
        // Reset body styles
        $('body').removeClass('modal-open').css({
            'overflow': '',
            'padding-right': '',
            'position': '',
            'top': '',
            'left': '',
            'right': '',
            'bottom': ''
        });
        
        // Reset html styles
        $('html').css({
            'overflow': '',
            'position': '',
            'top': '',
            'left': '',
            'right': '',
            'bottom': ''
        });
        
        console.log('Modal cleanup completed');
    }
    
    // Force cleanup all modals
    function forceCleanupAllModals() {
        // Remove all modal-related classes and elements
        $('.modal-backdrop').remove();
        $('.modal').removeClass('show').css('display', 'none');
        $('body').removeClass('modal-open').css({
            'overflow': '',
            'padding-right': '',
            'position': '',
            'top': '',
            'left': '',
            'right': '',
            'bottom': ''
        });
        $('html').css({
            'overflow': '',
            'position': '',
            'top': '',
            'left': '',
            'right': '',
            'bottom': ''
        });
        
        console.log('Force cleanup all modals completed');
    }
    
    // Fetch suppliers with pagination and search
    function fetchSuppliers(page = 1, searchQuery = '', forceRefresh = false) {
        const search = searchQuery || $('.search-box input').val();
        
        console.log('=== FETCH SUPPLIERS CALLED ===');
        console.log('Fetching suppliers with:', {
            page: page,
            search: search,
            per_page: 10,
            filters: currentFilters,
            url: '/company/warehouse/suppliers/all',
            forceRefresh: forceRefresh
        });
        console.log('Table element exists:', $('#suppliersTable').length);
        console.log('Table body exists:', $('#suppliersTable tbody').length);
        
        // Check if table already has data and is not in loading state (only if not forcing refresh)
        if (!forceRefresh) {
            const currentTableContent = $('#suppliersTable tbody').html();
            const hasData = currentTableContent && !currentTableContent.includes('Loading suppliers...') && !currentTableContent.includes('spinner-border') && !currentTableContent.includes('No data received');
            
            if (hasData && page === 1) {
                console.log('Table already has data, skipping fetch...');
                return;
            }
        }
        
        // Show loading state
        $('#suppliersTable tbody').html(`
            <tr>
                <td colspan="10" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading suppliers...</p>
                </td>
            </tr>
        `);
        
        // Debug: Log the exact data being sent
        const requestData = {
            page: page,
            search: search,
            per_page: 10,
            filters: currentFilters
        };
        console.log('Request data being sent:', requestData);
        
        $.ajax({
            url: '/company/warehouse/suppliers/all',
            method: 'POST',
            data: {
                page: page,
                search: search,
                per_page: 10,
                filters: currentFilters
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
            },
            beforeSend: function() {
                console.log('Starting AJAX request...');
                $('#suppliersTable tbody').html(`
                    <tr>
                        <td colspan="10" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </td>
                    </tr>
                `);
            },
            success: function(response) {
                console.log('=== AJAX SUCCESS RESPONSE ===');
                console.log('AJAX response received:', response);
                console.log('Response structure:', {
                    success: response.success,
                    hasData: !!response.data,
                    dataType: typeof response.data,
                    isDataArray: Array.isArray(response.data)
                });
                
                if (response.success && response.data) {
                    console.log('Suppliers data received:', response.data);
                    console.log('Pagination data:', {
                        current_page: response.data.current_page,
                        last_page: response.data.last_page,
                        total: response.data.total,
                        per_page: response.data.per_page
                    });
                    console.log('Rendering suppliers...');
                    renderSuppliers(response.data);
                    renderPagination(response.data);
                    console.log('Suppliers rendered successfully');
                } else {
                    console.error('No data in response:', response);
                    $('#suppliersTable tbody').html(`
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                No data received from server
                            </td>
                        </tr>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('Supplier fetch error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText,
                    statusCode: xhr.status,
                    statusText: xhr.statusText
                });
                
                let errorMessage = 'Failed to load suppliers. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    errorMessage = 'Suppliers endpoint not found. Please check the route.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later.';
                } else if (xhr.status === 0) {
                    errorMessage = 'Network error. Please check your connection.';
                }
                
                $('#suppliersTable tbody').html(`
                    <tr>
                        <td colspan="10" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${errorMessage}
                        </td>
                    </tr>
                `);
                
                showAlert('error', 'Error', errorMessage);
            }
        });
    }
    
    // Filter suppliers based on search term (client-side)
    function filterSuppliers(searchTerm) {
        $('table tbody tr').each(function() {
            const row = $(this);
            const rowText = row.text().toLowerCase();
            
            if (rowText.includes(searchTerm)) {
                row.show();
            } else {
                row.hide();
            }
        });
    }
    
    // Render suppliers table
    function renderSuppliers(suppliers) {
        console.log('=== RENDER SUPPLIERS CALLED ===');
        console.log('Suppliers to render:', suppliers);
        console.log('Suppliers type:', typeof suppliers);
        console.log('Is array:', Array.isArray(suppliers));
        
        // Handle different data structures
        let suppliersArray = [];
        if (Array.isArray(suppliers)) {
            suppliersArray = suppliers;
        } else if (suppliers && suppliers.data && Array.isArray(suppliers.data)) {
            suppliersArray = suppliers.data;
        } else if (suppliers && typeof suppliers === 'object') {
            // If it's an object with pagination data, extract the data array
            suppliersArray = suppliers.data || [];
        }
        
        console.log('Suppliers array:', suppliersArray);
        console.log('Suppliers count:', suppliersArray.length);
        
        let tableBody = '';
        
        if (!suppliersArray || suppliersArray.length === 0) {
        tableBody = `
            <tr>
                <td colspan="10" class="text-center py-4">
                    <i class="fas fa-box-open fa-2x mb-2 text-muted"></i>
                    <p class="text-muted">No suppliers found</p>
                    <button class="btn btn-sm btn-primary" id="tableRefreshBtn">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </button>
                </td>
            </tr>
        `;
    } else {
        suppliersArray.forEach((supplier, index) => {
            // Default to 'active' if status doesn't exist
            const status = supplier.status || 'active';
            const statusClass = status === 'active' ? 'bg-success' : 'bg-secondary';
            const statusText = status.charAt(0).toUpperCase() + status.slice(1);
            
            // Calculate average rating (default to 0 if not available)
            const avgRating = parseFloat(supplier.ratings_avg_rating) || 0;
            const ratingStars = getRatingStars(avgRating);
            
            tableBody += `
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="${supplier.id}">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="supplier-avatar me-3">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">${supplier.company_name}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-tag me-1"></i>
                                    ${supplier.business_type}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-medium">${supplier.primary_contact}</span>
                            <small class="text-muted">
                                <i class="fas fa-phone-alt me-1"></i>
                                ${supplier.phone}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-envelope me-1"></i>
                                ${supplier.email}
                            </small>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                            <span>${supplier.city}, ${supplier.region}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge ${statusClass}">
                            ${statusText}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            ${(() => {
                                // Create a local copy of the supplier data to avoid any reference issues
                                const supplierData = { ...supplier };
                                let orders = null;
                                
                                // Check for purchase orders in both formats
                                if (supplierData.purchase_orders && supplierData.purchase_orders.length > 0) {
                                    orders = [...supplierData.purchase_orders]; // Create a copy
                                } else if (supplierData.purchaseOrders && supplierData.purchaseOrders.length > 0) {
                                    orders = [...supplierData.purchaseOrders]; // Create a copy
                                }
                                
                                if (orders && orders.length > 0) {
                                    // Sort orders by date to get the most recent one
                                    const sortedOrders = orders.sort((a, b) => 
                                        new Date(b.created_at) - new Date(a.created_at)
                                    );
                                    const latestOrder = sortedOrders[0];
                                    
                                    return `<span class="fw-medium">${formatDate(latestOrder.created_at)}</span>
                                            <small class="text-muted">${formatCurrency(latestOrder.total_value)}</small>`;
                                } else {
                                    return `<span class="text-muted">No orders yet</span>`;
                                }
                            })()}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center text-warning">
                            ${ratingStars}
                            <span class="ms-2 fw-medium">${(avgRating || 0).toFixed(1)}</span>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-icon btn-sm btn-outline-warning rate-supplier" 
                                    data-id="${supplier.id}"
                                    data-name="${supplier.company_name}"
                                    data-bs-toggle="tooltip" 
                                    title="Rate Supplier">
                                <i class="fas fa-star"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-info view-ratings" 
                                    data-id="${supplier.id}"
                                    data-name="${supplier.company_name}"
                                    data-bs-toggle="tooltip" 
                                    title="View Ratings">
                                <i class="fas fa-chart-bar"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-primary view-supplier" 
                                    data-id="${supplier.id}"
                                    data-bs-toggle="tooltip" 
                                    title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-success edit-supplier" 
                                    data-id="${supplier.id}"
                                    data-bs-toggle="tooltip" 
                                    title="Edit Supplier">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-danger delete-supplier" 
                                    data-id="${supplier.id}"
                                    data-name="${supplier.company_name}"
                                    data-bs-toggle="tooltip" 
                                    title="Delete Supplier">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    console.log('Setting table body HTML...');
    $('#suppliersTable tbody').html(tableBody);
    console.log('Table body HTML set successfully');
    console.log('Table body content length:', $('#suppliersTable tbody').html().length);
    initializeTooltips(); // Reinitialize tooltips for new elements
    console.log('=== RENDER SUPPLIERS COMPLETED ===');
}

// Helper function to generate rating stars
function getRatingStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    let stars = '';
    
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }
    
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }
    
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
    }
    
    return stars;
}
    
    // Helper function to format date
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        
        const date = new Date(dateString);
        const now = new Date();
        
        // Reset time to start of day for accurate comparison
        const dateOnly = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        const nowOnly = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        
        const diffTime = nowOnly - dateOnly;
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) {
            return 'Today';
        } else if (diffDays === 1) {
            return 'Yesterday';
        } else if (diffDays < 7) {
            return `${diffDays} days ago`;
        } else if (diffDays < 30) {
            const weeks = Math.floor(diffDays / 7);
            return `${weeks} week${weeks > 1 ? 's' : ''} ago`;
        } else {
            return date.toLocaleDateString();
        }
    }
    
    // Helper function to format currency
    function formatCurrency(amount) {
        if (!amount) return 'GHS 0.00';
        
        return new Intl.NumberFormat('en-GH', {
            style: 'currency',
            currency: 'GHS',
            minimumFractionDigits: 2
        }).format(amount);
    }
    // Render pagination
    function renderPagination(response) {
        let paginationHtml = '';
        const currentPage = response.current_page || 1;
        const lastPage = response.last_page || 1;
        const total = response.total || 0;
        const perPage = response.per_page || 10;
        
        // Calculate start and end entries
        const start = ((currentPage - 1) * perPage) + 1;
        const end = Math.min(currentPage * perPage, total);
        
        // Update pagination info
        $('#supplierPaginationInfo').html(`
            Showing <span class="fw-semibold">${start}</span> to <span class="fw-semibold">${end}</span> of <span class="fw-semibold">${total}</span> entries
        `);
        
        // Previous button
        if (currentPage === 1) {
        paginationHtml += `
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                </li>
            `;
        } else {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        `;
        }
        
        // Page numbers
        for (let i = 1; i <= lastPage; i++) {
            if (i === currentPage) {
            paginationHtml += `
                    <li class="page-item active">
                        <span class="page-link">${i}</span>
                    </li>
                `;
            } else {
                paginationHtml += `
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
            }
        }
        
        // Next button
        if (currentPage === lastPage) {
        paginationHtml += `
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            `;
        } else {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `;
        }
        
        $('#supplierPagination').html(paginationHtml);
    }
    
    // Change page
    function changeSupplierPage(event, page) {
        event.preventDefault();
        fetchSuppliers(page);
    }
    
    // View supplier details
    function handleViewSupplier() {
        const supplierId = $(this).data('id');
        
        $.ajax({
            url: `/company/warehouse/suppliers/${supplierId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#viewSupplierModal').modal('show');
                // Show spinner in a loading overlay instead of replacing the entire modal body
                $('#viewSupplierModal .modal-body').append(`
                    <div id="loadingOverlay" class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0; background: rgba(255,255,255,0.9); z-index: 1050;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
            },
            success: function(response) {
                // Remove the loading overlay
                $('#loadingOverlay').remove();
                
                if (response.success && response.data) {
                    populateViewModal(response.data);
                } else if (response.data) {
                    // Handle case where response is directly the supplier data
                    populateViewModal(response.data);
                } else {
                    showAlert('error', 'Error', 'Invalid response from server.');
                    $('#viewSupplierModal').modal('hide');
                }
            },
            error: function(xhr) {
                // Remove the loading overlay
                $('#loadingOverlay').remove();
                
                let errorMessage = 'Failed to load supplier details.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('error', 'Error', errorMessage);
                $('#viewSupplierModal').modal('hide');
            }
        });
    }
    
    // Populate view modal with supplier data
    function populateViewModal(supplier) {
        try {
            // Safely handle the status with a default value
            const status = supplier.status || 'active';
            const statusText = status.charAt(0).toUpperCase() + status.slice(1);
            
            // Basic Info
            $('#supplierName').text(supplier.company_name || 'N/A');
            $('#supplierType').text(supplier.business_type || 'N/A');
            $('#supplierStatus').html(`<i class="fas fa-check-circle me-1"></i> ${statusText}`);
            $('#supplierLocation').text(`${supplier.city || 'N/A'}, ${supplier.region || 'N/A'}`);
            
            // Overview tab
            $('#overviewType').text(supplier.business_type || 'N/A');
            $('#overviewEstablished').text(supplier.year_established || 'N/A');
            $('#overviewTaxId').text(supplier.tin || 'N/A');
            $('#overviewNotes').text(supplier.notes || 'No additional notes');
            
            // Contact tab
            $('#contactName').text(supplier.primary_contact || 'N/A');
            $('#contactEmail').text(supplier.email || 'N/A').attr('href', `mailto:${supplier.email || ''}`);
            $('#contactPhone').text(supplier.phone || 'N/A');
            
            // Handle website - make sure it has http:// if it exists
            const website = supplier.website || 'N/A';
            const websiteHref = website === 'N/A' ? '#' : 
                               (website.startsWith('http') ? website : `http://${website}`);
            $('#contactWebsite').text(website).attr('href', websiteHref);
            
            // Build address safely
            const addressParts = [
                supplier.street_address,
                supplier.area,
                `${supplier.city}, ${supplier.region}`,
                supplier.country || ''
            ].filter(part => part); // Remove empty parts
            
            $('#contactAddress').html(addressParts.length > 0 ? addressParts.join('<br>') : 'No address provided');
        } catch (error) {
            showAlert('error', 'Error', 'Failed to populate supplier details.');
        }
    }





    // Edit supplier
    function handleEditSupplier() {
        const supplierId = $(this).data('id');
        
        $.ajax({
            url: `/company/warehouse/suppliers/${supplierId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#editSupplierModal').modal('show');
                $('#editSupplierModal .modal-body').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
            },
            success: function(response) {
                if (response.success && response.data) {
                    populateEditForm(response.data);
                } else if (response.data) {
                    // Handle case where response is directly the supplier data
                    populateEditForm(response.data);
                } else {
                    showAlert('error', 'Error', 'Invalid response from server.');
                    $('#editSupplierModal').modal('hide');
                }
            },
            error: function(xhr) {
                showAlert('error', 'Error', 'Failed to load supplier for editing.');
                $('#editSupplierModal').modal('hide');
            }
        });
    }
    
    // Populate edit form with supplier data
    function populateEditForm(supplier) {
        // Safely handle undefined values
        const safeSupplier = {
            id: supplier.id || '',
            company_name: supplier.company_name || '',
            business_type: supplier.business_type || '',
            tin: supplier.tin || '',
            vat_number: supplier.vat_number || '',
            business_sector: supplier.business_sector || '',
            company_size: supplier.company_size || '',
            primary_contact: supplier.primary_contact || '',
            contact_position: supplier.contact_position || '',
            email: supplier.email || '',
            phone: supplier.phone || '',
            street_address: supplier.street_address || '',
            area: supplier.area || '',
            city: supplier.city || '',
            region: supplier.region || '',
            payment_terms: supplier.payment_terms || '',
            currency: supplier.currency || 'GHS',
            status: supplier.status || 'active'
        };
        
        const form = `
            <form id="editSupplierForm">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" value="${safeSupplier.id}">
                
                <!-- Company Information -->
                <div class="mb-4">
                    <h5 class="mb-3"><i class="fas fa-building me-2"></i>Company Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editCompanyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="editCompanyName" name="company_name" value="${safeSupplier.company_name}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editBusinessType" class="form-label">Business Type</label>
                            <select class="form-select" id="editBusinessType" name="business_type" required>
                                <option value="sole_proprietor" ${safeSupplier.business_type === 'sole_proprietor' ? 'selected' : ''}>Sole Proprietorship</option>
                                <option value="partnership" ${safeSupplier.business_type === 'partnership' ? 'selected' : ''}>Partnership</option>
                                <option value="ltd" ${safeSupplier.business_type === 'ltd' ? 'selected' : ''}>Private Limited Company</option>
                                <option value="plc" ${safeSupplier.business_type === 'plc' ? 'selected' : ''}>Public Limited Company</option>
                                <option value="other" ${safeSupplier.business_type === 'other' ? 'selected' : ''}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editTin" class="form-label">Tax ID (TIN)</label>
                            <input type="text" class="form-control" id="editTin" name="tin" value="${safeSupplier.tin}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editVatNumber" class="form-label">VAT Number</label>
                            <input type="text" class="form-control" id="editVatNumber" name="vat_number" value="${safeSupplier.vat_number}">
                        </div>
                        <div class="col-md-6">
                            <label for="editBusinessSector" class="form-label">Business Sector</label>
                            <select class="form-select" id="editBusinessSector" name="business_sector" required>
                                <option value="agriculture" ${safeSupplier.business_sector === 'agriculture' ? 'selected' : ''}>Agriculture</option>
                                <option value="manufacturing" ${safeSupplier.business_sector === 'manufacturing' ? 'selected' : ''}>Manufacturing</option>
                                <option value="retail" ${safeSupplier.business_sector === 'retail' ? 'selected' : ''}>Retail</option>
                                <option value="services" ${safeSupplier.business_sector === 'services' ? 'selected' : ''}>Services</option>
                                <option value="other" ${safeSupplier.business_sector === 'other' ? 'selected' : ''}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editCompanySize" class="form-label">Company Size</label>
                            <select class="form-select" id="editCompanySize" name="company_size" required>
                                <option value="micro" ${safeSupplier.company_size === 'micro' ? 'selected' : ''}>Micro (1-5 employees)</option>
                                <option value="small" ${safeSupplier.company_size === 'small' ? 'selected' : ''}>Small (6-29 employees)</option>
                                <option value="medium" ${safeSupplier.company_size === 'medium' ? 'selected' : ''}>Medium (30-99 employees)</option>
                                <option value="large" ${safeSupplier.company_size === 'large' ? 'selected' : ''}>Large (100+ employees)</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="mb-4">
                    <h5 class="mb-3"><i class="fas fa-address-book me-2"></i>Contact Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editPrimaryContact" class="form-label">Primary Contact</label>
                            <input type="text" class="form-control" id="editPrimaryContact" name="primary_contact" value="${safeSupplier.primary_contact}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editContactPosition" class="form-label">Position</label>
                            <input type="text" class="form-control" id="editContactPosition" name="contact_position" value="${safeSupplier.contact_position}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" value="${safeSupplier.email}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editPhone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="editPhone" name="phone" value="${safeSupplier.phone}" required>
                        </div>
                    </div>
                </div>
                
                <!-- Address Information -->
                <div class="mb-4">
                    <h5 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>Address Information</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="editStreetAddress" class="form-label">Street Address</label>
                            <input type="text" class="form-control" id="editStreetAddress" name="street_address" value="${safeSupplier.street_address}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editArea" class="form-label">Area/Suburb</label>
                            <input type="text" class="form-control" id="editArea" name="area" value="${safeSupplier.area}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editCity" class="form-label">City/Town</label>
                            <input type="text" class="form-control" id="editCity" name="city" value="${safeSupplier.city}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editRegion" class="form-label">Region</label>
                            <select class="form-select" id="editRegion" name="region" required>
                                <option value="ahafo" ${safeSupplier.region === 'ahafo' ? 'selected' : ''}>Ahafo Region</option>
                                <option value="ashanti" ${safeSupplier.region === 'ashanti' ? 'selected' : ''}>Ashanti Region</option>
                                <option value="bono" ${safeSupplier.region === 'bono' ? 'selected' : ''}>Bono Region</option>
                                <option value="bono_east" ${safeSupplier.region === 'bono_east' ? 'selected' : ''}>Bono East Region</option>
                                <option value="central" ${safeSupplier.region === 'central' ? 'selected' : ''}>Central Region</option>
                                <option value="eastern" ${safeSupplier.region === 'eastern' ? 'selected' : ''}>Eastern Region</option>
                                <option value="greater_accra" ${safeSupplier.region === 'greater_accra' ? 'selected' : ''}>Greater Accra Region</option>
                                <option value="north_east" ${safeSupplier.region === 'north_east' ? 'selected' : ''}>North East Region</option>
                                <option value="northern" ${safeSupplier.region === 'northern' ? 'selected' : ''}>Northern Region</option>
                                <option value="oti" ${safeSupplier.region === 'oti' ? 'selected' : ''}>Oti Region</option>
                                <option value="savannah" ${safeSupplier.region === 'savannah' ? 'selected' : ''}>Savannah Region</option>
                                <option value="upper_east" ${safeSupplier.region === 'upper_east' ? 'selected' : ''}>Upper East Region</option>
                                <option value="upper_west" ${safeSupplier.region === 'upper_west' ? 'selected' : ''}>Upper West Region</option>
                                <option value="volta" ${safeSupplier.region === 'volta' ? 'selected' : ''}>Volta Region</option>
                                <option value="western" ${safeSupplier.region === 'western' ? 'selected' : ''}>Western Region</option>
                                <option value="western_north" ${safeSupplier.region === 'western_north' ? 'selected' : ''}>Western North Region</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="mb-4">
                    <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Additional Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editPaymentTerms" class="form-label">Payment Terms</label>
                            <select class="form-select" id="editPaymentTerms" name="payment_terms" required>
                                <option value="net15" ${safeSupplier.payment_terms === 'net15' ? 'selected' : ''}>Net 15 Days</option>
                                <option value="net30" ${safeSupplier.payment_terms === 'net30' ? 'selected' : ''}>Net 30 Days</option>
                                <option value="net60" ${safeSupplier.payment_terms === 'net60' ? 'selected' : ''}>Net 60 Days</option>
                                <option value="cod" ${safeSupplier.payment_terms === 'cod' ? 'selected' : ''}>Cash on Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editCurrency" class="form-label">Currency</label>
                            <select class="form-select" id="editCurrency" name="currency" required>
                                <option value="GHS" ${safeSupplier.currency === 'GHS' ? 'selected' : ''}>Ghana Cedi (GHS)</option>
                                <option value="USD" ${safeSupplier.currency === 'USD' ? 'selected' : ''}>US Dollar (USD)</option>
                                <option value="EUR" ${safeSupplier.currency === 'EUR' ? 'selected' : ''}>Euro (EUR)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="active" ${safeSupplier.status === 'active' ? 'selected' : ''}>Active</option>
                                <option value="inactive" ${safeSupplier.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                                <option value="pending" ${safeSupplier.status === 'pending' ? 'selected' : ''}>Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        `;
        
        $('#editSupplierModal .modal-body').html(form);
        initializeSelect2(); // Reinitialize Select2 for the edit form
    }
    
    // Handle add supplier form submission
    function handleAddSupplier(e) {
        e.preventDefault();
        console.log('=== HANDLE ADD SUPPLIER CALLED ===');
        
        const form = $('#addSupplierForm')[0];
    const formData = new FormData(form);
        
        // Debug: Log form data
        console.log('=== FORM DATA DEBUG ===');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        console.log('=== MAKING AJAX REQUEST ===');
        console.log('URL:', '/company/warehouse/suppliers');
        console.log('Method:', 'POST');
        console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
        
        $.ajax({
            url: '/company/warehouse/suppliers',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#addSupplierForm button[type="submit"]').prop('disabled', true).html(`
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...
                `);
            },
            success: function(response) {
                console.log('=== SUPPLIER ADDED SUCCESSFULLY ===');
                console.log('Response:', response);
                console.log('Response type:', typeof response);
                console.log('Response keys:', Object.keys(response || {}));
                
                showAlert('success', 'Success', 'Supplier added successfully.');
                
                // Close modal using Bootstrap 5 syntax
                const modal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                if (modal) {
                    modal.hide();
                } else {
                $('#addSupplierModal').modal('hide');
                }
                
                // Reset form and refresh data
                $('#addSupplierForm')[0].reset();
                
                // Reset multi-step form to step 1
                currentStep = 1;
                $('.step-content').addClass('d-none');
                $('#step-1').removeClass('d-none');
                updateNavigation();
                
                // Clear validation states
                $('#addSupplierForm').removeClass('was-validated');
                $('#addSupplierForm .form-control').removeClass('is-valid is-invalid');
                $('#addSupplierForm .invalid-feedback').remove();
                
                // Reset form button state
                $('#addSupplierForm button[type="submit"]').prop('disabled', false).html(`
                    <i class="fas fa-save me-2"></i>Save Supplier
                `);
                
                // Refresh suppliers table with a small delay to ensure modal is closed
                console.log('Refreshing suppliers table...');
                setTimeout(function() {
                    // Clear the table first to ensure clean state
                    $('#suppliersTable tbody').html('');
                    
                    if (typeof window.fetchSuppliers === 'function') {
                        console.log('Calling window.fetchSuppliers with forceRefresh...');
                        window.fetchSuppliers(1, '', true); // Reset to page 1 and force refresh to show the new supplier
                    } else if (typeof fetchSuppliers === 'function') {
                        console.log('Calling fetchSuppliers with forceRefresh...');
                        fetchSuppliers(1, '', true); // Reset to page 1 and force refresh to show the new supplier
                    } else {
                        console.error('fetchSuppliers function not found!');
                    }
                }, 300); // Reduced delay for faster refresh
                
                // Also refresh procurement statistics if the function exists
                if (typeof loadProcurementStatistics === 'function') {
                    console.log('Refreshing procurement statistics...');
                    loadProcurementStatistics();
                } else {
                    console.log('loadProcurementStatistics function not found');
                }
                
                // Clean up backdrop and reset page styles
                setTimeout(function() {
                    cleanupModalBackdrops();
                }, 300);
            },
            error: function(xhr) {
                console.log('=== SUPPLIER ADD ERROR ===');
                console.log('XHR:', xhr);
                console.log('Status:', xhr.status);
                console.log('Response:', xhr.responseText);
                console.log('Response JSON:', xhr.responseJSON);
                console.log('Error details:', xhr);
                
                let errorMessage = 'Failed to add supplier. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    errorMessage = 'Suppliers endpoint not found. Please check the route.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later.';
                }
                
                console.log('Error message:', errorMessage);
                showAlert('error', 'Error', errorMessage);
            },
            complete: function() {
                $('#addSupplierForm button[type="submit"]').prop('disabled', false).html('Save Supplier');
            }
        });
    }
    
    // Handle update supplier form submission
    function handleUpdateSupplier(e) {
        e.preventDefault();

        // console.log("fff")
        
        const supplierId = $('#editSupplierForm input[name="id"]').val();
        const formData = new FormData(this);
        
        $.ajax({
            url: `/company/warehouse/suppliers/${supplierId}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#editSupplierForm button[type="submit"]').prop('disabled', true).html(`
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...
                `);
            },
            success: function(response) {
                showAlert('success', 'Success', 'Supplier updated successfully.');
                
                // Close modal using Bootstrap 5 syntax
                const modal = bootstrap.Modal.getInstance(document.getElementById('editSupplierModal'));
                if (modal) {
                    modal.hide();
                } else {
                $('#editSupplierModal').modal('hide');
                }
                
                fetchSuppliers(1, '', true); // Force refresh to show updated data
                
                // Also refresh procurement statistics if the function exists
                if (typeof loadProcurementStatistics === 'function') {
                    console.log('Refreshing procurement statistics...');
                    loadProcurementStatistics();
                } else {
                    console.log('loadProcurementStatistics function not found');
                }
                
                // Clean up backdrop and reset page styles
                setTimeout(function() {
                    cleanupModalBackdrops();
                }, 300);
            },
            error: function(xhr) {
                console.log(xhr);
                let errorMessage = 'Failed to update supplier. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                }
                showAlert('error', 'Error', errorMessage);
            },
            complete: function() {
                $('#editSupplierForm button[type="submit"]').prop('disabled', false).html('Save Changes');
            }
        });
    }
    
    // Handle delete supplier
    function handleDeleteSupplier() {
        const supplierId = $(this).data('id');
        const supplierName = $(this).data('name');
        
        showAlert(
            'warning',
            'Confirm Delete',
            `Are you sure you want to delete <strong>${supplierName}</strong>? This action cannot be undone.`,
            true,
            'Yes, delete it!',
            'No, cancel'
        ).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/company/warehouse/suppliers/${supplierId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $(this).prop('disabled', true).html(`
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        `);
                    },
                    success: function(response) {
                        showAlert('success', 'Deleted', 'Supplier deleted successfully.');
                        fetchSuppliers(1, '', true); // Force refresh to show updated data
                        
                        // Also refresh procurement statistics if the function exists
                        if (typeof loadProcurementStatistics === 'function') {
                            console.log('Refreshing procurement statistics...');
                            loadProcurementStatistics();
                        } else {
                            console.log('loadProcurementStatistics function not found');
                        }
                        
                        // Clean up any modal backdrops
                        setTimeout(function() {
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open').css('overflow', '').css('padding-right', '');
                        }, 300);
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        showAlert('error', 'Error', 'Failed to delete supplier. Please try again.');
                    },
                    complete: function() {
                        $(this).prop('disabled', false).html('<i class="fas fa-trash-alt"></i>');
                    }
                });
            }
        });
    }
    
    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
    
    // Show alert using SweetAlert2
    function showAlert(icon, title, text, showCancel = false, confirmText = 'OK', cancelText = 'Cancel') {
        return Swal.fire({
            icon: icon,
            title: title,
            html: text,
            showCancelButton: showCancel,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-secondary'
            },
            buttonsStyling: false
        });
    }
    
    // Handle supplier rating
    function handleRateSupplier() {
        const supplierId = $(this).data('id');
        const supplierName = $(this).data('name');
        
        Swal.fire({
            title: `Rate ${supplierName}`,
            html: `
                <div class="text-center mb-3">
                    <div class="rating-stars mb-3">
                        <i class="fas fa-star text-muted" data-rating="1" style="font-size: 2rem; cursor: pointer; margin: 0 5px;"></i>
                        <i class="fas fa-star text-muted" data-rating="2" style="font-size: 2rem; cursor: pointer; margin: 0 5px;"></i>
                        <i class="fas fa-star text-muted" data-rating="3" style="font-size: 2rem; cursor: pointer; margin: 0 5px;"></i>
                        <i class="fas fa-star text-muted" data-rating="4" style="font-size: 2rem; cursor: pointer; margin: 0 5px;"></i>
                        <i class="fas fa-star text-muted" data-rating="5" style="font-size: 2rem; cursor: pointer; margin: 0 5px;"></i>
                    </div>
                    <div class="rating-text text-muted">Click on a star to rate</div>
                </div>
                <textarea id="ratingComments" class="form-control" placeholder="Add your comments (optional)" rows="3"></textarea>
            `,
            showCancelButton: true,
            confirmButtonText: 'Submit Rating',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-secondary'
            },
            buttonsStyling: false,
            didOpen: function() {
                let selectedRating = 0;
                
                // Handle star clicks
                $('.rating-stars .fa-star').on('click', function() {
                    const rating = parseInt($(this).data('rating'));
                    selectedRating = rating;
                    
                    // Update stars
                    $('.rating-stars .fa-star').each(function(index) {
                        const starRating = index + 1;
                        if (starRating <= rating) {
                            $(this).removeClass('text-muted').addClass('text-warning');
                        } else {
                            $(this).removeClass('text-warning').addClass('text-muted');
                        }
                    });
                    
                    // Update text
                    const ratingTexts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
                    $('.rating-text').text(ratingTexts[rating] || 'Click on a star to rate');
                });
                
                // Handle form submission
                $('.swal2-confirm').on('click', function(e) {
                    e.preventDefault();
                    
                    if (selectedRating === 0) {
                        Swal.showValidationMessage('Please select a rating');
                        return false;
                    }
                    
                    const comments = $('#ratingComments').val();
                    
                    // Submit rating
                    $.ajax({
                        url: '/company/warehouse/suppliers/rate',
                        method: 'POST',
                        data: {
                            supplier_id: supplierId,
                            rating: selectedRating,
                            comments: comments,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rating Submitted',
                                text: 'Thank you for your rating!',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                            fetchSuppliers(1, '', true); // Force refresh the list
                        },
                        error: function(xhr) {
                            let errorMessage = 'Failed to submit rating. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                });
            }
        });
    }
    
    // Handle view supplier ratings
    function handleViewRatings() {
        const supplierId = $(this).data('id');
        const supplierName = $(this).data('name');
        
        $.ajax({
            url: `/company/warehouse/suppliers/${supplierId}/ratings`,
            method: 'GET',
            success: function(response) {
                let ratingsHtml = '';
                
                if (response.ratings && response.ratings.length > 0) {
                    response.ratings.forEach(function(rating) {
                        const stars = getRatingStars(rating.rating);
                        const date = new Date(rating.created_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                        
                        ratingsHtml += `
                            <div class="rating-item border rounded p-3 mb-3 bg-light">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="rating-stars me-2">
                                            ${stars}
                                        </div>
                                        <span class="fw-bold text-primary">${rating.rating}/5</span>
                                    </div>
                                    <small class="text-muted">${date}</small>
                                </div>
                                
                                ${rating.comments ? `
                                    <div class="rating-comment mb-2">
                                        <p class="mb-0 text-dark">${rating.comments}</p>
                                    </div>
                                ` : ''}
                                
                                <div class="rating-meta">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        ${rating.user ? rating.user.fullname : 'Anonymous User'}
                                    </small>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    ratingsHtml = `
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Ratings Yet</h5>
                            <p class="text-muted">This supplier hasn't received any ratings yet.</p>
                        </div>
                    `;
                }
                
                Swal.fire({
                    title: `<i class="fas fa-star text-warning me-2"></i>${supplierName} - Ratings`,
                    html: `
                        <div class="ratings-container" style="max-height: 400px; overflow-y: auto;">
                            ${ratingsHtml}
                        </div>
                    `,
                    width: '700px',
                    confirmButtonText: 'Close',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        popup: 'ratings-modal'
                    },
                    buttonsStyling: false,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            },
            error: function(xhr) {
                showAlert('error', 'Error', 'Failed to load ratings. Please try again.');
            }
        });
    }
    
    // Filter functionality
    function initializeFilters() {
        console.log('Initializing filters...');
        console.log('Status filter items found:', $('.dropdown-item[data-filter="status"]').length);
        console.log('Location filter items found:', $('.dropdown-item[data-filter="location"]').length);
        console.log('Reset filter items found:', $('.dropdown-item[data-filter="reset"]').length);
        
        // Status filter
        $('.dropdown-item[data-filter="status"]').on('click', function(e) {
            e.preventDefault();
            const status = $(this).data('value');
            const filterText = $(this).find('span').first().text().trim();
            
            // Update filter button text
            $('#filterDropdownBtn').html(`
                <i class="fas fa-filter me-1"></i> 
                <span>Status: ${filterText}</span>
                <span class="badge bg-primary rounded-pill ms-2" id="filterCount">1</span>
            `);
            
            // Apply filter
            applyFilter('status', status);
        });
        
        // Location filter
        $('.dropdown-item[data-filter="location"]').on('click', function(e) {
            e.preventDefault();
            const location = $(this).data('value');
            const filterText = $(this).text().trim();
            
            // Update filter button text
            $('#filterDropdownBtn').html(`
                <i class="fas fa-filter me-1"></i> 
                <span>Location: ${filterText}</span>
                <span class="badge bg-primary rounded-pill ms-2" id="filterCount">1</span>
            `);
            
            // Apply filter
            applyFilter('location', location);
        });
        
        // Reset filters
        $('.dropdown-item[data-filter="reset"]').on('click', function(e) {
            e.preventDefault();
            
            // Reset filter button text
            $('#filterDropdownBtn').html(`
                <i class="fas fa-filter me-1"></i> 
                <span>Filter</span>
                <span class="badge bg-primary rounded-pill ms-2" id="filterCount">0</span>
            `);
            
            // Clear filters
            clearFilters();
        });
    }
    
    // Apply filter
    function applyFilter(type, value) {
        // Clear other filter types when applying a new filter
        if (type === 'status') {
            delete currentFilters['location'];
        } else if (type === 'location') {
            delete currentFilters['status'];
        }
        
        currentFilters[type] = value;
        console.log('Applying filter:', { type: type, value: value, currentFilters: currentFilters });
        fetchSuppliers(1, $('.search-box input').val());
    }
    
    // Clear filters
    function clearFilters() {
        currentFilters = {};
        console.log('Filters cleared, currentFilters:', currentFilters);
        fetchSuppliers(1, $('.search-box input').val());
    }
    
    // Export functionality
    function exportSuppliers() {
        const search = $('.search-box input').val();
        const filters = currentFilters;
        
        // Show loading
        Swal.fire({
            title: 'Exporting...',
            text: 'Please wait while we prepare your export.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: '/company/warehouse/suppliers/export',
            method: 'POST',
            data: {
                search: search,
                filters: filters,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                Swal.close();
                
                // Create download link
                const blob = new Blob([response], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `suppliers_${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                showAlert('success', 'Export Complete', 'Your supplier data has been exported successfully.');
            },
            error: function(xhr) {
                Swal.close();
                showAlert('error', 'Export Failed', 'Failed to export supplier data. Please try again.');
            }
        });
    }
    
    // Refresh functionality
    function refreshSuppliers() {
        // Show loading spinner
        const refreshBtn = $('#refreshBtn');
        const originalIcon = refreshBtn.html();
        refreshBtn.html('<i class="fas fa-spinner fa-spin"></i>');
        refreshBtn.prop('disabled', true);
        
        // Fetch fresh data
        fetchSuppliers(1, $('.search-box input').val());
        
        // Restore button after a delay
        setTimeout(() => {
            refreshBtn.html(originalIcon);
            refreshBtn.prop('disabled', false);
        }, 1000);
    }
    
    // Load filter data from backend
    function loadFilterData() {
        console.log('=== LOADING FILTER DATA ===');
        console.log('URL:', '/company/warehouse/suppliers/filter-data');
        
        $.ajax({
            url: '/company/warehouse/suppliers/filter-data',
            method: 'GET',
            success: function(response) {
                console.log('Filter data response:', response);
                if (response.success && response.data) {
                    console.log('Populating filter dropdown with data:', response.data);
                    populateFilterDropdown(response.data);
                } else {
                    console.log('No filter data received, using static filters');
                    // Use static filter data if backend doesn't provide it
                    populateFilterDropdown({
                        status_counts: {
                            'active': 5,
                            'inactive': 2,
                            'pending': 1
                        },
                        locations: ['Accra', 'Kumasi', 'Tamale', 'Takoradi', 'Cape Coast']
                    });
                }
            },
            error: function(xhr) {
                console.error('Failed to load filter data:', xhr);
                console.log('Using static filter data as fallback');
                // Use static filter data as fallback
                populateFilterDropdown({
                    status_counts: {
                        'active': 5,
                        'inactive': 2,
                        'pending': 1
                    },
                    locations: ['Accra', 'Kumasi', 'Tamale', 'Takoradi', 'Cape Coast']
                });
            }
        });
    }
    
    // Make loadFilterData globally accessible
    window.loadFilterData = loadFilterData;
    
    // Populate filter dropdown with dynamic data
    function populateFilterDropdown(filterData) {
        console.log('=== POPULATING FILTER DROPDOWN ===');
        console.log('Filter data received:', filterData);
        
        const { status_counts, locations } = filterData;
        console.log('Status counts:', status_counts);
        console.log('Locations:', locations);
        
        // Populate status filters
        let statusHtml = '';
        Object.keys(status_counts).forEach(status => {
            const count = status_counts[status];
            const statusText = status.charAt(0).toUpperCase() + status.slice(1);
            const badgeClass = status === 'active' ? 'bg-primary-soft text-primary' : 'bg-secondary-soft text-secondary';
            
            statusHtml += `
                <li>
                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="#" data-filter="status" data-value="${status}">
                        <span>${statusText}</span>
                        <span class="badge ${badgeClass} rounded-pill">${count}</span>
                    </a>
                </li>
            `;
        });
        $('#statusFilters').html(statusHtml);
        console.log('Status filters HTML:', statusHtml);
        
        // Populate location filters
        let locationHtml = '';
        locations.forEach(location => {
            locationHtml += `
                <li><a class="dropdown-item" href="#" data-filter="location" data-value="${location}">${location}</a></li>
            `;
        });
        $('#locationFilters').html(locationHtml);
        console.log('Location filters HTML:', locationHtml);
        console.log('Filter dropdown populated successfully');
        
        // Re-initialize filter event listeners
        initializeFilters();
    }
    
    // Load initial data
    console.log('Loading initial suppliers data...');
    fetchSuppliers();
    
    // Close the initializeSuppliersPage function
    console.log('=== SUPPLIERS PAGE INITIALIZATION COMPLETED ===');
    }
    
    // Initialize event listeners for filter, export, and refresh buttons
    $(document).ready(function() {
        // Export button
        $('#exportBtn').on('click', function() {
            exportSuppliers();
        });
        
        // Refresh button
        $('#refreshBtn').on('click', function() {
            refreshSuppliers();
        });
        
        // Load filter data and initialize filters
        loadFilterData();
        
        // Debug dropdown functionality
        console.log('Dropdown button found:', $('#filterDropdownBtn').length);
        console.log('Dropdown menu found:', $('.dropdown-menu').length);
        
        // Custom dropdown functionality
        $('#filterDropdownBtn').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Filter button clicked - toggling dropdown');
            
            const dropdownMenu = $(this).siblings('.dropdown-menu');
            dropdownMenu.toggleClass('show');
            
            // Position the dropdown properly
            if (dropdownMenu.hasClass('show')) {
                dropdownMenu.css({
                    'display': 'block',
                    'position': 'absolute',
                    'top': '100%',
                    'left': '0',
                    'z-index': '1050'
                });
            } else {
                dropdownMenu.css('display', 'none');
            }
        });
        
        // Close dropdown when clicking outside
        $(document).off('click.filterDropdown').on('click.filterDropdown', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').removeClass('show').css('display', 'none');
            }
        });
        
        // Close dropdown when clicking on dropdown items
        $('.dropdown-item').off('click.closeDropdown').on('click.closeDropdown', function(e) {
            setTimeout(() => {
                $('.dropdown-menu').removeClass('show').css('display', 'none');
            }, 100);
    });
    
        // Initialize the suppliers page (only if not already initialized)
        console.log('Calling initializeSuppliersPage...');
        if (!window.suppliersPageInitialized) {
            initializeSuppliersPage();
        } else {
            console.log('Suppliers page already initialized, just calling fetchSuppliers...');
            if (typeof window.fetchSuppliers === 'function') {
                window.fetchSuppliers();
            }
        }
});
</script>

