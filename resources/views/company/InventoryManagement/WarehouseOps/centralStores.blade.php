@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --warning-color: #f8961e;
        --danger-color: #f72585;
        --light-bg: #f8f9ff;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --card-hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .inventory-container {
        background: #f5f7ff;
        min-height: 100%;
        padding: 1.5rem;
        border-radius: 12px;
    }

    /* Text Utilities */
    .text-white-60 {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    .text-white-80 {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    /* Stats Cards */
    .stat-card {
        position: relative;
        border-radius: 16px;
        border: none;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        z-index: 1;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        color: white;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.05) 100%);
        z-index: -1;
        border-radius: 16px;
    }
    
    .stat-card-bg {
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        opacity: 0.1;
        z-index: -1;
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .stat-card:hover .stat-card-bg {
        transform: scale(1.8) rotate(15deg);
        opacity: 0.1;
    }
    
    .stat-card-content {
        position: relative;
        z-index: 2;
        padding: 1.5rem;
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .stat-card:hover .stat-icon {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .stat-card h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
        line-height: 1.2;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Individual card theming */
    .stat-card:nth-child(1) .stat-icon {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
    }
    
    .stat-card:nth-child(1) h3 {
        background: linear-gradient(45deg, #1e3a8a, #3b82f6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .stat-card:nth-child(2) .stat-icon {
        background: linear-gradient(135deg, #06d6a0, #04a777);
        color: white;
    }
    
    .stat-card:nth-child(2) h3 {
        background: linear-gradient(45deg, #065f46, #10b981);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .stat-card:nth-child(3) .stat-icon {
        background: linear-gradient(135deg, #ff9e00, #ff7b00);
        color: white;
    }
    
    .stat-card:nth-child(3) h3 {
        background: linear-gradient(45deg, #92400e, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .stat-card:nth-child(4) .stat-icon {
        background: linear-gradient(135deg, #7209b7, #560bad);
        color: white;
    }
    
    .stat-card:nth-child(4) h3 {
        background: linear-gradient(45deg, #6b21a8, #a855f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .stat-card p {
        font-size: 0.9375rem;
        color: #4b5563;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .stat-trend {
        font-size: 0.8125rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 0.5rem;
    }
    
    .badge {
        padding: 0.4em 0.8em;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.03em;
        border-radius: 8px;
        text-transform: none;
    }
    
    .bg-soft-primary {
        background-color: rgba(67, 97, 238, 0.1) !important;
        color: #4361ee !important;
    }
    
    .bg-soft-success {
        background-color: rgba(6, 214, 160, 0.1) !important;
        color: #06d6a0 !important;
    }
    
    .bg-soft-warning {
        background-color: rgba(255, 158, 0, 0.1) !important;
        color: #ff9e00 !important;
    }
    
    .bg-soft-danger {
        background-color: rgba(239, 68, 68, 0.1) !important;
        color: #ef4444 !important;
    }
    
    .bg-soft-info {
        background-color: rgba(99, 102, 241, 0.1) !important;
        color: #6366f1 !important;
    }
    
    /* Hover effects */
    .stat-card:hover {
        transform: translateY(-8px) !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02) !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .stat-card h3 {
            font-size: 1.5rem;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }
    }

    .inventory-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
    }

    .inventory-table:hover {
        box-shadow: var(--card-hover-shadow);
    }

    .table thead th {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.875rem 1.25rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        color: #64748b;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    /* Hover effect for table headers */
    .table thead th:hover {
        background: #f1f5f9;
    }
    
    /* Sort indicator */
    .table thead th.sorting:after,
    .table thead th.sorting_asc:after,
    .table thead th.sorting_desc:after {
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-left: 8px;
        opacity: 0.5;
    }
    
    .table thead th.sorting:after {
        content: '\f0dc';
    }
    
    .table thead th.sorting_asc:after {
        content: '\f0de';
        opacity: 1;
        color: var(--primary-color);
    }
    
    .table thead th.sorting_desc:after {
        content: '\f0dd';
        opacity: 1;
        color: var(--primary-color);
    }

    .table tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-color: #f1f5f9;
        color: #334155;
        font-size: 0.9rem;
        transition: all 0.15s ease;
    }

    .table tbody tr {
        transition: all 0.2s ease;
        position: relative;
    }

    /* Zebra striping for better readability */
    .table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .table tbody tr:hover {
        background-color: #f1f5ff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }
    
    /* Highlight selected row */
    .table tbody tr.table-active {
        background-color: #eef2ff;
        box-shadow: 0 0 0 1px #c7d2fe;
    }
    
    /* Subtle border between rows */
    .table tbody tr:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }
    
    /* Status badges */
    .badge {
        padding: 0.4em 0.8em;
        font-size: 0.75rem;
        font-weight: 500;
        letter-spacing: 0.02em;
        border-radius: 6px;
    }
    
    /* Custom scrollbar for table */
    .dataTables_scrollBody::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }
    
    .dataTables_scrollBody::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .badge {
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 50px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Enhanced form controls */
    .dataTables_filter input,
    .dataTables_length select {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    
    .dataTables_filter input:focus,
    .dataTables_length select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        outline: none;
    }
    
    .dataTables_filter {
        margin-bottom: 1rem;
    }
    
    .dataTables_filter label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .dataTables_filter input {
        min-width: 250px;
    }
    
    /* Pagination styles */
    .dataTables_paginate {
        display: flex;
        gap: 0.5rem;
        margin-top: 1.5rem;
    }
    
    .paginate_button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        height: 2rem;
        padding: 0 0.5rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        background: #fff;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .paginate_button:hover:not(.disabled) {
        background: #f8fafc;
        color: var(--primary-color);
        border-color: #c7d2fe;
    }
    
    .paginate_button.current {
        background: var(--primary-color);
        color: white !important;
        border-color: var(--primary-color);
    }
    
    .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* Info text */
    .dataTables_info {
        color: #64748b;
        font-size: 0.875rem;
        padding-top: 1.5rem !important;
    }
    
    /* Buttons */
    .btn-primary {
        background: var(--primary-color);
        border: none;
        font-weight: 500;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background: var(--secondary-color);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
    }

    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #4a5568;
        font-weight: 500;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #f8f9ff;
        border-color: #cbd5e0;
        color: #2d3748;
    }

    .form-control, .form-select, .select2-container--default .select2-selection--single {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus, .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        box-shadow: var(--card-hover-shadow);
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #edf2f7;
        padding: 1.25rem 1.5rem;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #718096;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s ease;
    }

    .nav-tabs .nav-link:hover {
        color: var(--primary-color);
        background: rgba(67, 97, 238, 0.05);
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        background: white;
        border-bottom: 3px solid var(--primary-color);
        font-weight: 600;
    }

    .item-image {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #edf2f7;
        transition: all 0.3s ease;
    }

    .item-image:hover {
        transform: scale(1.1);
    }

    .action-btns .btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 8px;
        margin-right: 0.5rem;
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .table-responsive {
            border-radius: 8px;
            border: 1px solid #edf2f7;
        }
        
        .action-btns .btn {
            margin-bottom: 0.5rem;
        }
    }
    
    /* Central Store specific styles */
    .empty-state-icon {
        opacity: 0.6;
        transition: all 0.3s ease;
    }
    
    .empty-state-icon:hover {
        opacity: 0.8;
        transform: scale(1.05);
    }
    
    .item-row {
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 10px;
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }
    
    .item-row:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    .item-row.selected {
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Fix horizontal scrolling issues with Select2 */
    .select2-container {
        width: 100% !important;
        max-width: 100%;
    }
    
    .select2-dropdown {
        max-width: 100%;
        overflow-x: hidden;
    }
    
    .select2-dropdown-modal {
        max-width: 100% !important;
        overflow-x: hidden !important;
    }
    
    .modal-body {
        overflow-x: hidden;
    }
    
    .select2-selection {
        max-width: 100%;
        overflow: hidden;
    }
</style>
@endpush

<div class="inventory-container">
    <!-- Central Store Management -->

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex mb-2">
                    <div class="input-group me-2" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Search central store items..." id="searchInput">
                        <button class="btn btn-outline-secondary" type="button" onclick="searchItems()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="dropdown me-2">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                            <li><a class="dropdown-item" href="#" data-status="">All Items</a></li>
                            <li><a class="dropdown-item" href="#" data-status="pending">Pending Items</a></li>
                            <li><a class="dropdown-item" href="#" data-status="completed">Completed Items</a></li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex">
                    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#newItemModal">
                        <i class="fas fa-plus me-1"></i> Add New Item 
                    </button>
                    <button class="btn btn-outline-secondary me-2" id="refreshBtn">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Central Store Table -->
    <div class="card shadow-sm border-0 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Central Store Items</h5>
                    <p class="text-muted mb-0">Items that have passed inspection and are ready for central storage</p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Empty State (shown when no items) -->
            <div id="emptyState" class="text-center py-5" style="display: none;">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-warehouse" style="font-size: 6rem; color: #cbd5e1;"></i>
                </div>
                <h4 class="text-muted mb-3">Central Store is Empty</h4>
                <p class="text-muted mb-4">No items have been added to the central store yet.</p>
                                 <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newItemModal">
                     <i class="fas fa-plus me-2"></i>Add First Item
                 </button>
            </div>
            
            <!-- Items Table -->
            <div id="centralStoreTable" class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Item Name</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>PO Number</th>
                            <th class="text-end">Quantity</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total Value</th>
                            <th>Batch Number</th>
                            <th>Status</th>
                            <th>Transfer Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="centralStoreTableBody">
                        <!-- Items will be loaded dynamically -->
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4 pb-4">
                    <nav aria-label="Central Store pagination">
                        <ul class="pagination" id="paginationControls">
                            <!-- Pagination will be generated dynamically -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
<!-- @include('company.InventoryManagement.WarehouseOps.Modals.inventory') -->

<!-- Central Store New Item Modal -->
<div class="modal fade" id="newItemModal" tabindex="-1" aria-labelledby="newItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="newItemModalLabel">Add New Item to Central Store</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="centralStoreItemForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Basic Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="new_supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="new_supplier" name="supplier_id" required>
                                            <option value="">Select Supplier</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_po" class="form-label">Purchase Order <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="new_po" name="purchase_order_id" required>
                                            <option value="">Select PO Number</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_item_name" class="form-label">Item Name <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="new_item_name" name="item_name" required>
                                            <option value="">Select Item Name</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_brand" class="form-label">Brand</label>
                                        <input type="text" class="form-control" id="new_brand" name="brand" 
                                               placeholder="Enter brand name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_unit" class="form-label">Unit of Measure <span class="text-danger">*</span></label>
                                        <select class="form-select" id="new_unit" name="unit" required>
                                            <option value="">Select Unit</option>
                                            <option value="pcs">Pieces (pcs)</option>
                                            <option value="kg">Kilograms (kg)</option>
                                            <option value="g">Grams (g)</option>
                                            <option value="l">Liters (l)</option>
                                            <option value="m">Meters (m)</option>
                                            <option value="box">Box</option>
                                            <option value="set">Set</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_description" class="form-label">Description</label>
                                        <textarea class="form-control" id="new_description" name="description" 
                                                  rows="3" placeholder="Enter item description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- Inventory Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Inventory Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="new_quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="new_quantity"
                                                   name="quantity" value="0" min="0" required
                                                   title="Quantity is set from inspection and cannot be changed">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="new_unit_price" class="form-label">Unit Price <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">GH₵</span>
                                                <input type="number" class="form-control" id="new_unit_price" 
                                                       name="unit_price" step="0.01" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_batch_number" class="form-label">Batch Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="new_batch_number" name="batch_number" readonly required>
                                        <small class="form-text text-muted">Batch number is automatically set from inspection and cannot be changed.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_location" class="form-label">Location <span class="text-danger">*</span></label>
                                        <select class="form-select" id="new_location" name="location" required>
                                            <option value="">Select Location</option>
                                            <option value="Central Store">Central Store</option>
                                            <option value="Warehouse A">Warehouse A</option>
                                            <option value="Warehouse B">Warehouse B</option>
                                            <option value="Storage Room 1">Storage Room 1</option>
                                            <option value="Storage Room 2">Storage Room 2</option>
                                            <option value="Cold Storage">Cold Storage</option>
                                            <option value="Hazardous Storage">Hazardous Storage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_sku" class="form-label">SKU</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="new_sku" name="sku" 
                                                   placeholder="Enter or generate SKU">
                                            <button class="btn btn-outline-secondary" type="button" id="generateSKU">
                                                <i class="fas fa-sync-alt"></i> Generate
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_barcode" class="form-label">Barcode</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="new_barcode" name="barcode" 
                                                   placeholder="Scan or enter barcode">
                                            <button class="btn btn-outline-secondary" type="button" id="generateBarcode">
                                                <i class="fas fa-barcode"></i> Generate
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_notes" class="form-label">Notes</label>
                                        <textarea class="form-control" id="new_notes" name="notes" rows="3" placeholder="Additional notes about this item"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Images Section -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Product Images</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="new_itemImages" class="form-label">Upload Images</label>
                                        <input class="form-control" type="file" id="new_itemImages" name="images[]" multiple 
                                               accept="image/*" onchange="previewImages(this)">
                                        <div class="form-text">Max 5 images, 2MB each, JPG/PNG only</div>
                                    </div>
                                    <div class="row g-2" id="new_imagePreviewContainer">
                                        <div class="col-12 text-center py-4 border rounded bg-light" id="new_noImagesPlaceholder">
                                            <i class="fas fa-images fa-3x text-muted mb-2"></i>
                                            <p class="mb-0 text-muted">No images uploaded yet</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="saveAsDraftBtn">
                        <i class="far fa-save me-1"></i> Save as Draft
                    </button>
                    <button type="button" class="btn btn-primary" id="addNewItemBtn">
                        <i class="fas fa-save me-1"></i> Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Item Modal -->
<div class="modal fade" id="viewItemModal" tabindex="-1" aria-labelledby="viewItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="viewItemModalLabel">View Item Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Basic Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Item Name:</strong></td>
                                <td id="view_item_name">-</td>
                            </tr>
                            <tr>
                                <td><strong>Category:</strong></td>
                                <td id="view_item_category">-</td>
                            </tr>
                            <tr>
                                <td><strong>Brand:</strong></td>
                                <td id="view_brand">-</td>
                            </tr>
                            <tr>
                                <td><strong>Unit:</strong></td>
                                <td id="view_unit">-</td>
                            </tr>
                            <tr>
                                <td><strong>Description:</strong></td>
                                <td id="view_description">-</td>
                            </tr>
                            <tr>
                                <td><strong>Supplier:</strong></td>
                                <td id="view_supplier">-</td>
                            </tr>
                            <tr>
                                <td><strong>Purchase Order:</strong></td>
                                <td id="view_po">-</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Inventory Details</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Quantity:</strong></td>
                                <td id="view_quantity">-</td>
                            </tr>
                            <tr>
                                <td><strong>Unit Price:</strong></td>
                                <td id="view_unit_price">-</td>
                            </tr>
                            <tr>
                                <td><strong>Total Value:</strong></td>
                                <td id="view_total_value">-</td>
                            </tr>
                            <tr>
                                <td><strong>Batch Number:</strong></td>
                                <td id="view_batch_number">-</td>
                            </tr>
                            <tr>
                                <td><strong>Location:</strong></td>
                                <td id="view_location">-</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td id="view_status">-</td>
                            </tr>
                            <tr>
                                <td><strong>Notes:</strong></td>
                                <td id="view_notes">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">Identification</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>SKU:</strong></td>
                                <td id="view_sku">-</td>
                            </tr>
                            <tr>
                                <td><strong>Barcode:</strong></td>
                                <td id="view_barcode">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">Product Images</h6>
                        <div id="view_images">
                            <p class="text-muted">No images available</p>
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

<!-- Full Size Image Modal -->
<div class="modal fade" id="fullSizeImageModal" tabindex="-1" aria-labelledby="fullSizeImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="fullSizeImageModalLabel">Product Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="fullSizeImage" src="" alt="Full Size Product Image" class="img-fluid" style="max-height: 70vh;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editItemForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit_item_id" name="item_id">
                <input type="hidden" id="edit_images_to_delete" name="images_to_delete" value="">
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Basic Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="edit_supplier_store" class="form-label">Supplier</label>
                                        <input type="text" class="form-control" id="edit_supplier_display_store" readonly>
                                        <input type="hidden" id="edit_supplier_store" name="supplier_id">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_po_store" class="form-label">Purchase Order</label>
                                        <input type="text" class="form-control" id="edit_po_display_store" readonly>
                                        <input type="hidden" id="edit_po_store" name="purchase_order_id">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_item_name_store" class="form-label">Item Name</label>
                                        <input type="text" class="form-control" id="edit_item_name_store" name="item_name" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_brand_store" class="form-label">Brand</label>
                                        <input type="text" class="form-control" id="edit_brand_store" name="brand" 
                                               placeholder="Enter brand name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_unit_store" class="form-label">Unit of Measure <span class="text-danger">*</span></label>
                                        <select class="form-select" id="edit_unit_store" name="unit" required>
                                            <option value="">Select Unit</option>
                                            <option value="pcs">Pieces (pcs)</option>
                                            <option value="kg">Kilograms (kg)</option>
                                            <option value="g">Grams (g)</option>
                                            <option value="l">Liters (l)</option>
                                            <option value="m">Meters (m)</option>
                                            <option value="box">Box</option>
                                            <option value="set">Set</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_description_store" class="form-label">Description</label>
                                        <textarea class="form-control" id="edit_description_store" name="description" 
                                                  rows="3" placeholder="Enter item description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- Inventory Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Inventory Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="edit_quantity_store" class="form-label">Quantity <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="edit_quantity_store" 
                                                   name="quantity" min="0" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="edit_unit_price_store" class="form-label">Unit Price <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">GH₵</span>
                                                <input type="number" class="form-control" id="edit_unit_price_store" 
                                                       name="unit_price" step="0.01" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_batch_number_store" class="form-label">Batch Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_batch_number_store" name="batch_number" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_location_store" class="form-label">Location <span class="text-danger">*</span></label>
                                        <select class="form-select" id="edit_location_store" name="location" required>
                                            <option value="">Select Location</option>
                                            <option value="Central Store">Central Store</option>
                                            <option value="Warehouse A">Warehouse A</option>
                                            <option value="Warehouse B">Warehouse B</option>
                                            <option value="Storage Room 1">Storage Room 1</option>
                                            <option value="Storage Room 2">Storage Room 2</option>
                                            <option value="Cold Storage">Cold Storage</option>
                                            <option value="Hazardous Storage">Hazardous Storage</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_sku_store" class="form-label">SKU</label>
                                        <input type="text" class="form-control" id="edit_sku_store" name="sku" 
                                               placeholder="SKU" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_barcode_store" class="form-label">Barcode</label>
                                        <input type="text" class="form-control" id="edit_barcode_store" name="barcode" 
                                               placeholder="Barcode" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_notes_store" class="form-label">Notes</label>
                                        <textarea class="form-control" id="edit_notes_store" name="notes" rows="3" placeholder="Additional notes about this item"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Images Section -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Product Images</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="edit_itemImages" class="form-label">Upload Images</label>
                                        <input class="form-control" type="file" id="edit_itemImages" name="images[]" multiple 
                                               accept="image/*" onchange="previewEditImages(this)">
                                        <div class="form-text">Max 5 images, 2MB each, JPG/PNG only</div>
                                    </div>
                                    <div class="row g-2" id="edit_imagePreviewContainer">
                                        <div class="col-12 text-center py-4 border rounded bg-light" id="edit_noImagesPlaceholder">
                                            <i class="fas fa-images fa-3x text-muted mb-2"></i>
                                            <p class="mb-0 text-muted">No images uploaded yet</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Item
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

<!-- Central Store JavaScript Functionality -->
<script>
// Check if jQuery is loaded
if (typeof jQuery === 'undefined') {
    console.error('jQuery is not loaded');
} else {
    console.log('jQuery version:', jQuery.fn.jquery);
}

$(document).ready(function() {
    console.log('Document ready - Central Store initializing');
    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
    console.log('emptyState element exists:', $('#emptyState').length > 0);
    console.log('centralStoreTable element exists:', $('#centralStoreTable').length > 0);
    
    // Check CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (!csrfToken) {
        console.error('CSRF Token not found');
    } else {
        console.log('CSRF Token found: ' + csrfToken.substring(0, 20) + '...');
    }
    
    // Load initial data
   
    // Initialize Select2 for dropdowns
    $('.select2').select2();
    
    // Show empty state initially if no items
    setTimeout(function() {
        console.log('Initial timeout check for empty state');
        const rowCount = $('#centralStoreTableBody tr').length;
        console.log('Initial table row count:', rowCount);
        if (rowCount === 0) {
            console.log('No rows found, showing empty state');
            showEmptyState();
        } else {
            console.log('Rows found, not showing empty state');
        }
    }, 1000);
    
    // Search functionality
    $(document).on('keyup', '#searchInput', function(e) {
        if (e.key === 'Enter') {
            searchItems();
        }
    });
    
    // Refresh button
    $(document).on('click', '#refreshBtn', function() {
        loadPageData();
        loadCentralStoreItems();
    });
    
    // Filter functionality
    $(document).on('click', '[data-status]', function(e) {
        e.preventDefault();
        const status = $(this).data('status');
        $('#filterDropdown').text($(this).text());
        loadCentralStoreItems(1, status);
    });
   
    // Load suppliers for new item modal
    $('#newItemModal').on('show.bs.modal', function() {

        console.log('New item modal opening, loading suppliers...');
        loadSuppliersForNewItem();
    });
    
    // Reset form when modal is hidden
    $('#newItemModal').on('hidden.bs.modal', function() {
        resetNewItemForm();
    });
    
    // Generate SKU functionality
    $(document).on('click', '#generateSKU', function() {
        const btn = $(this);
        const originalText = btn.html();
        
        // Show loading state
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...');
        
        const selectedItemName = $('#new_item_name option:selected').text();
        let itemName = 'ITEM';
        
        if (selectedItemName && selectedItemName !== 'Select Item Name') {
            itemName = selectedItemName.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase();
        }
        
        // Generate unique SKU with timestamp and random number
        const timestamp = Date.now().toString().slice(-6);
        const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        const sku = `${itemName}-${timestamp}${random}`;
        
        // Check if SKU is unique
        checkSKUUniqueness(sku, function(isUnique) {
            if (isUnique) {
                $('#new_sku').val(sku);
                btn.prop('disabled', false).html(originalText);
            } else {
                // If not unique, generate a new one
                generateUniqueSKU(btn, originalText, itemName, category);
            }
        });
    });
    
    // Generate barcode functionality
    $(document).on('click', '#generateBarcode', function() {
        const btn = $(this);
        const originalText = btn.html();
        
        // Show loading state
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...');
        
        // Generate a unique barcode
        generateUniqueBarcode(btn, originalText);
    });
    
    // When supplier is selected in new item modal, load their POs
    $(document).on('change', '#new_supplier', function() {
        console.log('Supplier changed to:', $(this).val());
        const supplierId = $(this).val();
        if (!supplierId) {
            $('#new_po').empty().append('<option value="">Select PO Number</option>');
            $('#new_item_name').empty().append('<option value="">Select Item Name</option>');
            return;
        }
        loadPOsForNewItem(supplierId);
    });
    
    // When PO is selected in new item modal, load available items
    $(document).on('change', '#new_po', function() {
        console.log('PO changed to:', $(this).val());
        const poId = $(this).val();
        if (!poId) {
            $('#new_item_name').empty().append('<option value="">Select Item Name</option>');
            return;
        }
        loadItemsForNewItem(poId);
    });
    

    
    
    // Generate SKU for edit modal
    $(document).on('click', '#editGenerateSkuBtn', function() {
        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        generateUniqueSku(btn, originalText);
    });
    
    // Generate barcode for edit modal
    $(document).on('click', '#editGenerateBarcodeBtn', function() {
        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        generateUniqueBarcode(btn, originalText);
    });
    
    
    // When item is selected, populate the form fields including batch number from inspection
    $(document).on('change', '#new_item_name', function() {
        const selectedItem = $(this).find('option:selected').data('item');
        console.log('Item selected:', selectedItem);

        if (selectedItem) {
            // Disable quantity field and set value from inspection
            $('#new_quantity').val(selectedItem.quantity).prop('disabled', true);
            // Keep unit price editable but set from inspection
            $('#new_unit_price').val(selectedItem.unit_price).prop('disabled', false);
            // Use the batch number from the inspection instead of auto-generated
            $('#new_batch_number').val(selectedItem.batch_number);
            console.log('Quantity (disabled), unit price, and batch number updated from inspection:', selectedItem.batch_number);
        } else {
            console.log('No item data found');
            // Re-enable fields if no item selected
            $('#new_quantity').prop('disabled', false);
            $('#new_unit_price').prop('disabled', false);
        }
    });
    
    
    // Add new item button click
    $(document).on('click', '#addNewItemBtn', function() {
        addNewItem();
    });
    
    // Save as draft button click
    $(document).on('click', '#saveAsDraftBtn', function() {
        addNewItem(true); // true = save as draft
    });
});

// Initialize Central Store page
$(document).ready(function() {
    // Load central store items on page load
    loadCentralStoreItems();
});

// Load page data (statistics, metrics, etc.)
function loadPageData() {
    // Placeholder function for loading page-level data
    // Can be expanded to load statistics, dashboard metrics, etc.
    console.log('Page data loaded');
}

// Load central store items
function loadCentralStoreItems(page = 1, status = '') {
    const searchTerm = $('#searchInput').val() || '';
    
    $.ajax({
        url: '/company/warehouse/central-store/items',
        method: 'POST',
        data: { 
            page: page,
            search: searchTerm,
            status: status
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                displayItems(response.items);
                updatePagination(response.pagination);
                
                if (response.items.length === 0) {
                    showEmptyState();
                } else {
                    hideEmptyState();
                }
            }
        },
        error: function(xhr) {
            console.error('Error loading items:', xhr);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load central store items'
            });
        },
        complete: function() {
            // If no items were displayed, show empty state
            const rowCount = $('#centralStoreTableBody tr').length;
            if (rowCount === 0) {
                showEmptyState();
            }
        }
    });
}

// Display items in table
function displayItems(items) {
    const tbody = $('#centralStoreTableBody');
    if (tbody.length === 0) {
        console.error('Table body not found');
        return;
    }
    
    // Store items globally for modal access
    window.currentItems = items;
    
    tbody.empty();
    
    items.forEach(item => {
        const row = `
            <tr data-item-id="${item.id}">
                <td class="ps-4">${item.item_name}</td>
                <td>${item.item_category || 'N/A'}</td>
                <td>${item.supplier.company_name}</td>
                <td>${item.purchase_order.po_number}</td>
                <td class="text-end">${item.quantity}</td>
                <td class="text-end">GH₵${parseFloat(item.unit_price).toFixed(2)}</td>
                <td class="text-end">GH₵${parseFloat(item.total_price).toFixed(2)}</td>
                <td>${item.batch_number}</td>
                <td>
                    <span class="badge bg-${getStatusBadgeClass(item.status)}">
                        ${item.status.toUpperCase()}
                    </span>
                </td>
                <td>${new Date(item.transfer_date).toLocaleDateString()}</td>
                <td class="pe-4">
                    <div class="action-btns d-flex justify-content-end">
                        ${item.status === 'pending' ? `
                            <button class="btn btn-sm btn-light text-success me-1 complete-item" 
                                    data-id="${item.id}" 
                                    title="Mark as Completed">
                                <i class="fas fa-check"></i>
                            </button>
                        ` : ''}
                        <button class="btn btn-sm btn-light text-primary me-1 view-item-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#viewItemModal"
                                data-item-id="${item.id}"
                                title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-light text-warning me-1 edit-item-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editItemModal"
                                data-item-id="${item.id}"
                                title="Edit Item">
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
function getStatusBadgeClass(status) {
    switch(status) {
        case 'pending': return 'warning';
        case 'completed': return 'success';
        default: return 'secondary';
    }
}

// Show/hide empty state
function showEmptyState() {
    if ($('#emptyState').length) {
        $('#emptyState').show();
        $('#centralStoreTable').hide();
    } else {
        console.error('emptyState element NOT found');
    }
}

function hideEmptyState() {
    if ($('#emptyState').length) {
        $('#emptyState').hide();
        $('#centralStoreTable').show();
    } else {
        console.error('emptyState element NOT found');
    }
}

// Update pagination controls
function updatePagination(pagination) {
    const container = $('#paginationControls');
    if (container.length === 0) return;
    
    container.empty();
    
    if (pagination.last_page <= 1) return;
    
    // Previous button
    const prevDisabled = pagination.current_page === 1 ? 'disabled' : '';
    container.append(`
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
        container.append(`
            <li class="page-item ${active}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `);
    }
    
    // Next button
    const nextDisabled = pagination.current_page === pagination.last_page ? 'disabled' : '';
    container.append(`
        <li class="page-item ${nextDisabled}">
            <a class="page-link" href="#" data-page="${pagination.current_page + 1}">
                Next &raquo;
            </a>
        </li>
    `);
}

// Handle pagination clicks
$(document).on('click', '.page-link', function(e) {
    e.preventDefault();
    const page = $(this).data('page');
    if (page) {
        loadCentralStoreItems(page);
    }
});

// Search items
function searchItems() {
    loadCentralStoreItems(1);
}

// Mark item as completed
$(document).on('click', '.complete-item', function() {
    const itemId = $(this).data('id');
    const btn = $(this);
    
    Swal.fire({
        title: 'Mark as Completed?',
        text: 'Are you sure you want to mark this item as completed?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Complete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            
            $.ajax({
                url: `/company/warehouse/central-store/${itemId}/complete`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Completed!',
                            text: response.message
                        });
                        
                        // Refresh data
                        loadCentralStoreItems();
                        loadPageData();
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to mark item as completed'
                    });
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-check"></i>');
                }
            });
        }
    });
});

// Clear new item modal when closed
$(document).on('hidden.bs.modal', '#newItemModal', function() {
    resetNewItemForm();
});

// Load suppliers for new item modal
function loadSuppliersForNewItem() {
    console.log('Loading suppliers for new item modal...');
    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
    console.log('URL:', '/company/warehouse/central-store/suppliers-with-approved-pos');
    console.log('Supplier select element exists:', $('#new_supplier').length > 0);
    
    // Show loading alert
    Swal.fire({
        title: 'Loading Suppliers...',
        text: 'Please wait while we load the suppliers',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: '/company/warehouse/central-store/suppliers-with-approved-pos',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Suppliers loaded successfully:', response);
            
            // Close loading alert
            Swal.close();
            
            const select = $('#new_supplier');
            if (select.length) {
                select.empty().append('<option value="">Select Supplier</option>');
                if (response && response.length > 0) {
                    response.forEach(supplier => {
                        select.append(`<option value="${supplier.id}">${supplier.company_name}</option>`);
                    });
                } else {
                    select.append('<option value="" disabled>No suppliers with approved POs found</option>');
                }
                
                // Refresh Select2 after adding options
                if (select.hasClass('select2-hidden-accessible')) {
                    select.select2('destroy');
                }
                
                // Add a small delay to ensure DOM is ready
                setTimeout(function() {
                    select.select2({
                        placeholder: "Select Supplier",
                        allowClear: true,
                        dropdownParent: $('#newItemModal'),
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-modal'
                    });
                    console.log('Suppliers loaded and Select2 refreshed');
                }, 100);
            }
        },
        error: function(xhr) {
            console.error('Error loading suppliers:', xhr);
            console.error('Response:', xhr.responseText);
            
            // Close loading alert
            Swal.close();
            
            // Load static suppliers as fallback
            loadStaticSuppliers();
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load suppliers, using fallback'
            });
        }
    });
}

// Load static suppliers as fallback
function loadStaticSuppliers() {
    console.log('Loading static suppliers as fallback...');
    const select = $('#new_supplier');
    if (select.length) {
        select.empty().append('<option value="">Select Supplier</option>');
        select.append('<option value="1">Turner and Tanner Co</option>');
        select.append('<option value="2">Gray and Burch Inc</option>');
        
        // Refresh Select2
        if (select.hasClass('select2-hidden-accessible')) {
            select.select2('destroy');
        }
        
        setTimeout(function() {
            select.select2({
                placeholder: "Select Supplier",
                allowClear: true,
                dropdownParent: $('#newItemModal'),
                width: '100%',
                dropdownCssClass: 'select2-dropdown-modal'
            });
            console.log('Static suppliers loaded');
        }, 100);
    }
}

// Load POs for new item modal
function loadPOsForNewItem(supplierId) {
    console.log('=== LOADING POs FOR NEW ITEM ===');
    console.log('Supplier ID:', supplierId);
    console.log('URL:', `/company/warehouse/central-store/${supplierId}/approved-pos`);
    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
    
    // Show loading state
    const select = $('#new_po');
    if (select.length) {
        select.empty().append('<option value="">Loading POs...</option>');
    }
    
    $.ajax({
        url: `/company/warehouse/central-store/${supplierId}/approved-pos`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('POs loaded successfully:', response);
            console.log('Response type:', typeof response);
            console.log('Response length:', response ? response.length : 'null');
            
            const select = $('#new_po');
            if (select.length) {
                select.empty().append('<option value="">Select PO Number</option>');
                if (response && response.length > 0) {
                    console.log('Adding POs to dropdown...');
                    response.forEach(po => {
                        console.log('Adding PO:', po);
                        select.append(`
                            <option value="${po.id}">
                                ${po.po_number} - ${po.remaining_items} items remaining
                            </option>
                        `);
                    });
                    console.log('POs added to dropdown successfully');
                } else {
                    console.log('No POs found, adding disabled option');
                    select.append('<option value="" disabled>No approved POs found for this supplier</option>');
                }
                
                // Refresh Select2 after adding options
                if (select.hasClass('select2-hidden-accessible')) {
                    select.select2('destroy');
                }
                
                // Add a small delay to ensure DOM is ready
                setTimeout(function() {
                    select.select2({
                        placeholder: "Select PO Number",
                        allowClear: true,
                        dropdownParent: $('#newItemModal'),
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-modal'
                    });
                    console.log('POs loaded and Select2 refreshed');
                }, 100);
            } else {
                console.error('PO select element not found!');
            }
        },
        error: function(xhr) {
            console.error('=== ERROR LOADING POs ===');
            console.error('Status:', xhr.status);
            console.error('Status Text:', xhr.statusText);
            console.error('Response:', xhr.responseText);
            
            const select = $('#new_po');
            if (select.length) {
                select.empty().append('<option value="" disabled>Error loading POs</option>');
                
                // Refresh Select2 after adding error option
                if (select.hasClass('select2-hidden-accessible')) {
                    select.select2('destroy');
                }
                
                setTimeout(function() {
                    select.select2({
                        placeholder: "Select PO Number",
                        allowClear: true,
                        dropdownParent: $('#newItemModal'),
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-modal'
                    });
                }, 100);
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load purchase orders for this supplier: ' + xhr.statusText
            });
        }
    });
}

// Load items for new item modal
function loadItemsForNewItem(poId) {
    console.log('Loading items for PO ID:', poId);
    
    $.ajax({
        url: `/company/warehouse/central-store/${poId}/approved-items`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Items loaded successfully:', response);
            const select = $('#new_item_name');
            if (select.length === 0) return;
            
            select.empty().append('<option value="">Select Item Name</option>');
            
            if (response && response.length > 0) {
                console.log('Items received from API:', response);
                response.forEach(item => {
                    console.log('Processing item:', item);
                    console.log('Item batch number:', item.batch_number);
                    select.append(`
                        <option value="${item.name}" data-item='${JSON.stringify(item)}'>
                            ${item.name}
                        </option>
                    `);
                });
                
            } else {
                select.append('<option value="" disabled>No items available from this PO</option>');
            }
            
            // Refresh Select2 after adding options
            if (select.hasClass('select2-hidden-accessible')) {
                select.select2('destroy');
            }
            
            // Add a small delay to ensure DOM is ready
            setTimeout(function() {
                select.select2({
                    placeholder: "Select Item Name",
                    allowClear: true,
                    dropdownParent: $('#newItemModal'),
                    width: '100%',
                    dropdownCssClass: 'select2-dropdown-modal'
                });
                console.log('Items loaded and Select2 refreshed');
            }, 100);
        },
        error: function(xhr) {
            console.error('Error loading items:', xhr);
            console.error('Response:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load items for this PO'
            });
        }
    });
}

// Add new item to central store
function addNewItem(isDraft = false) {
    const submitBtn = $('#addNewItemBtn');
    
    // Prevent duplicate submissions
    if (submitBtn.prop('disabled')) {
        console.log('Form submission already in progress, ignoring duplicate call');
        return;
    }
    
    const selectedItem = $('#new_item_name').find('option:selected').data('item');
    const itemName = $('#new_item_name').val();
    const location = $('#new_location').val();
    
    if (!itemName || itemName === 'Select Item Name') {
        Swal.fire({
            icon: 'warning',
            title: 'No Item Selected',
            text: 'Please select an item from the dropdown.'
        });
        reEnableAddItemButton();
        return;
    }
    
    if (!location || location === 'Select Location' || location === 'add_new') {
        Swal.fire({
            icon: 'warning',
            title: 'No Location Selected',
            text: 'Please select a location.'
        });
        reEnableAddItemButton();
        return;
    }
    
    // Disable button to prevent duplicate submissions
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
    
    // Safety timeout to re-enable button if something goes wrong
    const safetyTimeout = setTimeout(function() {
        console.log('Safety timeout triggered - re-enabling button');
        reEnableAddItemButton();
    }, 15000); // 15 second timeout
    
    const formData = {
        item_name: itemName,
        supplier_id: $('#new_supplier').val(),
        purchase_order_id: $('#new_po').val(),
        brand: $('#new_brand').val() || '',
        unit: $('#new_unit').val(),
        description: $('#new_description').val() || '',
        quantity: $('#new_quantity').val(),
        unit_price: $('#new_unit_price').val(),
        batch_number: $('#new_batch_number').val(),
        location: location,
        sku: $('#new_sku').val() || '',
        barcode: $('#new_barcode').val() || '',
        notes: $('#new_notes').val() || '',
        is_draft: isDraft
    };
    
    // Debug: Log form data
    console.log('=== COMPREHENSIVE FORM DATA DEBUG ===');
    console.log('Form data being sent:', formData);
    console.log('Individual field values:');
    console.log('- item_name:', itemName, '(type:', typeof itemName, ')');
    console.log('- supplier_id:', $('#new_supplier').val(), '(type:', typeof $('#new_supplier').val(), ')');
    console.log('- purchase_order_id:', $('#new_po').val(), '(type:', typeof $('#new_po').val(), ')');
    console.log('- brand:', $('#new_brand').val(), '(type:', typeof $('#new_brand').val(), ')');
    console.log('- unit:', $('#new_unit').val(), '(type:', typeof $('#new_unit').val(), ')');
    console.log('- description:', $('#new_description').val(), '(type:', typeof $('#new_description').val(), ')');
    console.log('- quantity:', $('#new_quantity').val(), '(type:', typeof $('#new_quantity').val(), ')');
    console.log('- unit_price:', $('#new_unit_price').val(), '(type:', typeof $('#new_unit_price').val(), ')');
    console.log('- batch_number:', $('#new_batch_number').val(), '(type:', typeof $('#new_batch_number').val(), ')');
    console.log('- location:', location, '(type:', typeof location, ')');
    console.log('- sku:', $('#new_sku').val(), '(type:', typeof $('#new_sku').val(), ')');
    console.log('- barcode:', $('#new_barcode').val(), '(type:', typeof $('#new_barcode').val(), ')');
    console.log('- notes:', $('#new_notes').val(), '(type:', typeof $('#new_notes').val(), ')');
    console.log('- is_draft:', isDraft, '(type:', typeof isDraft, ')');
    
    // Check for empty or null values
    console.log('=== CHECKING FOR EMPTY/NULL VALUES ===');
    Object.keys(formData).forEach(key => {
        const value = formData[key];
        if (value === null || value === undefined || value === '' || value === '0') {
            console.warn('⚠️ EMPTY/NULL VALUE FOUND:', key, '=', value);
        } else {
            console.log('✅ OK:', key, '=', value);
        }
    });
    console.log('=== END DEBUG ===');
    console.log('- unit:', $('#new_unit').val());
    console.log('- quantity:', $('#new_quantity').val());
    console.log('- unit_price:', $('#new_unit_price').val());
    console.log('- batch_number:', $('#new_batch_number').val());
    console.log('- location:', location);
    
    // Validate required fields (removed item_category since it's no longer in the form)
    if (!formData.supplier_id || !formData.purchase_order_id || !formData.item_name || 
        !formData.unit || !formData.quantity || 
        !formData.unit_price || !formData.batch_number || !formData.location) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Information',
            text: 'Please fill in all required fields.'
        });
        reEnableAddItemButton();
        return;
    }
    
    // Show loading state
    const btn = $('#addNewItemBtn');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');
    
    // No file uploads for now - using JSON data
    console.log('Files being uploaded: 0 (using JSON data)');
    
    // Debug: Log form data object
    console.log('=== FORM DATA OBJECT DEBUG ===');
    console.log('formData:', formData);
    console.log('=== END FORM DATA OBJECT DEBUG ===');
    
    console.log('=== AJAX REQUEST DEBUG ===');
    console.log('About to send AJAX request to:', '/company/warehouse/central-store/add-item');
    console.log('Data being sent:', formData);
    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
    console.log('=== END AJAX REQUEST DEBUG ===');
    
    $.ajax({
        url: '/company/warehouse/central-store/add-item',
        method: 'POST',
        data: JSON.stringify(formData),
        processData: false,
        contentType: 'application/json',
        timeout: 30000, // 30 second timeout
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Clear safety timeout
            clearTimeout(safetyTimeout);
            
            // Re-enable button immediately on success
            submitBtn.prop('disabled', false).html('<i class="fas fa-plus me-2"></i>Add Item');
            
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: isDraft ? 'Item Saved as Draft!' : 'Item Added!',
                    text: response.message
                });
                
                // Close modal and refresh data
                const modal = bootstrap.Modal.getInstance(document.getElementById('newItemModal'));
                if (modal) {
                    modal.hide();
                } else {
                    $('#newItemModal').modal('hide');
                }
                loadCentralStoreItems();
                loadPageData();
                
                // Reset form
                resetNewItemForm();
            }
        },
        error: function(xhr, status, error) {
            // Clear safety timeout
            clearTimeout(safetyTimeout);
            
            // Re-enable button on error
            submitBtn.prop('disabled', false).html('<i class="fas fa-plus me-2"></i>Add Item');
            
            console.log('=== AJAX ERROR RESPONSE ===');
            console.log('Status:', xhr.status);
            console.log('Status Text:', status);
            console.log('Error:', error);
            console.log('Response:', xhr.responseJSON);
            
            let errorMessage = 'Failed to add item';
            let errorDetails = '';
            
            // Handle timeout
            if (status === 'timeout') {
                errorMessage = 'Request timed out. Please try again.';
            } else if (xhr.responseJSON) {
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
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage + errorDetails,
                width: '600px'
            });
        },
        complete: function() {
            submitBtn.prop('disabled', false).html('<i class="fas fa-plus me-2"></i>Add Item');
        }
    });
}

// Reset new item form
function resetNewItemForm() {
    $('#new_supplier').val('').trigger('change');
    $('#new_po').val('').trigger('change');
    $('#new_item_name').empty().append('<option value="">Select Item Name</option>');
    $('#new_item_category').empty().append('<option value="">Select Category</option>');
    $('#new_location').val('');
    $('#new_brand').val('');
    $('#new_unit').val('');
    $('#new_description').val('');
    $('#new_quantity').val('0').prop('disabled', false);
    $('#new_unit_price').val('').prop('disabled', false);
    $('#new_sku').val('');
    $('#new_barcode').val('');
    $('#new_notes').val('');
    
    // Reset category input
    $('#newCategoryInput').addClass('d-none');
    $('#newCategory').val('');
    
    // Reset image preview
    $('#new_itemImages').val('');
    $('#new_imagePreviewContainer').html(`
        <div class="col-12 text-center py-4 border rounded bg-light" id="new_noImagesPlaceholder">
            <i class="fas fa-images fa-3x text-muted mb-2"></i>
            <p class="mb-0 text-muted">No images uploaded yet</p>
        </div>
    `);
}

// Test function to verify JavaScript is working
function testAlert() {
    console.log('Test alert function called');
}

// Image preview function
function previewImages(input) {
    const container = $('#new_imagePreviewContainer');
    const placeholder = $('#new_noImagesPlaceholder');
    
    if (input.files && input.files.length > 0) {
        placeholder.remove();
        container.empty();
        
        Array.from(input.files).forEach((file, index) => {
            if (index < 5) { // Max 5 images
                const reader = new FileReader();
                reader.onload = function(e) {
                    container.append(`
                        <div class="col-6 col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="position-relative">
                                    <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: contain; background-color: #f8f9fa;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                            onclick="removeImage(${index})" style="margin: 2px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="card-body p-2">
                                    <small class="text-muted">Image ${index + 1}</small>
                                </div>
                            </div>
                        </div>
                    `);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        container.html(`
            <div class="col-12 text-center py-4 border rounded bg-light" id="new_noImagesPlaceholder">
                <i class="fas fa-images fa-3x text-muted mb-2"></i>
                <p class="mb-0 text-muted">No images uploaded yet</p>
            </div>
        `);
    }
}

// Remove image function
function removeImage(index) {
    const input = document.getElementById('new_itemImages');
    const dt = new DataTransfer();
    
    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    previewImages(input);
}

// Check if SKU is unique
function checkSKUUniqueness(sku, callback) {
    $.ajax({
        url: '/company/warehouse/central-store/check-sku-uniqueness',
        method: 'POST',
        data: { sku: sku },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            callback(response.is_unique);
        },
        error: function() {
            // If check fails, assume it's unique to avoid blocking
            callback(true);
        }
    });
}

// Helper function to re-enable the add item button
function reEnableAddItemButton() {
    const submitBtn = $('#addNewItemBtn');
    submitBtn.prop('disabled', false).html('<i class="fas fa-plus me-2"></i>Add Item');
}

// Load page data function
function loadPageData() {
    console.log('Loading page data...');
    // This function can be expanded as needed
}

// Generate unique SKU recursively
function generateUniqueSKU(btn, originalText, itemName, category, attempts = 0) {
    if (attempts >= 5) {
        // If we've tried 5 times, just use the current one
        const timestamp = Date.now().toString().slice(-6);
        const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        const sku = `${itemName}-${category}-${timestamp}${random}`;
        $('#new_sku').val(sku);
        btn.prop('disabled', false).html(originalText);
        return;
    }
    
    const timestamp = Date.now().toString().slice(-6);
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    const sku = `${itemName}-${category}-${timestamp}${random}`;
    
    checkSKUUniqueness(sku, function(isUnique) {
        if (isUnique) {
            $('#new_sku').val(sku);
            btn.prop('disabled', false).html(originalText);
        } else {
            // Try again
            setTimeout(() => {
                generateUniqueSKU(btn, originalText, itemName, category, attempts + 1);
            }, 100);
        }
    });
}

// Check if barcode is unique
function checkBarcodeUniqueness(barcode, callback) {
    $.ajax({
        url: '/company/warehouse/central-store/check-barcode-uniqueness',
        method: 'POST',
        data: { barcode: barcode },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            callback(response.is_unique);
        },
        error: function() {
            // If check fails, assume it's unique to avoid blocking
            callback(true);
        }
    });
}

// Generate unique barcode recursively
function generateUniqueBarcode(btn, originalText, attempts = 0) {
    if (attempts >= 5) {
        // If we've tried 5 times, just use the current one
        const timestamp = Date.now().toString().slice(-8);
        const random = Math.floor(Math.random() * 100000).toString().padStart(5, '0');
        const barcode = `BC${timestamp}${random}`;
        $('#new_barcode').val(barcode);
        btn.prop('disabled', false).html(originalText);
        return;
    }
    
    const timestamp = Date.now().toString().slice(-8);
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    const barcode = `BC${timestamp}${random}`;
    
    checkBarcodeUniqueness(barcode, function(isUnique) {
        if (isUnique) {
            $('#new_barcode').val(barcode);
            btn.prop('disabled', false).html(originalText);
        } else {
            // Try again
            setTimeout(() => {
                generateUniqueBarcode(btn, originalText, attempts + 1);
            }, 100);
        }
    });
}

// View Item Modal Functionality
$(document).on('click', '.view-item-btn', function() {
    const itemId = $(this).data('item-id');
    console.log('View modal clicked for item ID:', itemId);
    
    if (!itemId) {
        console.error('No item ID found');
        return;
    }
    
    // Check if currentItems exists
    if (!window.currentItems || !Array.isArray(window.currentItems)) {
        console.error('currentItems not found or not an array');
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Item data not available. Please refresh the page and try again.'
        });
        return;
    }
    
    // Find the item data from the current items array
    const item = window.currentItems.find(item => item.id == itemId);
    console.log('Found item:', item);
    
    if (!item) {
        console.error('Item not found:', itemId);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Item data not found. Please refresh the page and try again.'
        });
        return;
    }
    
    // Populate the view modal
    $('#view_item_name').text(item.item_name || '-');
    $('#view_item_category').text(item.item_category || '-');
    $('#view_brand').text(item.brand || '-');
    $('#view_unit').text(item.unit || 'pcs');
    $('#view_description').text(item.description || '-');
    $('#view_supplier').text(item.supplier?.company_name || '-');
    $('#view_po').text(item.purchase_order?.po_number || '-');
    $('#view_quantity').text(item.quantity || '0');
    $('#view_unit_price').text(item.unit_price ? `GH₵${parseFloat(item.unit_price).toFixed(2)}` : '-');
    $('#view_total_value').text(item.total_price ? `GH₵${parseFloat(item.total_price).toFixed(2)}` : '-');
    $('#view_batch_number').text(item.batch_number || '-');
    $('#view_location').text(item.location || '-');
    $('#view_status').html(`<span class="badge bg-${getStatusBadgeClass(item.status)}">${item.status}</span>`);
    $('#view_notes').text(item.notes || '-');
    $('#view_sku').text(item.sku || '-');
    $('#view_barcode').text(item.barcode || '-');
    
    // Display images in view modal if available
    if (item.images && item.images.length > 0) {
        displayViewImages(item.images);
    } else {
        $('#view_images').html('<p class="text-muted">No images available</p>');
    }
});

// Edit Item Modal Functionality
$(document).on('click', '.edit-item-btn', function() {
    const itemId = $(this).data('item-id');
    console.log('Edit modal clicked for item ID:', itemId);
    
    if (!itemId) {
        console.error('No item ID found');
        return;
    }
    
    // Check if currentItems exists
    if (!window.currentItems || !Array.isArray(window.currentItems)) {
        console.error('currentItems not found or not an array');
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Item data not available. Please refresh the page and try again.'
        });
        return;
    }
    
    // Find the item data from the current items array
    const item = window.currentItems.find(item => item.id == itemId);
    console.log('Found item for edit:', item);
    
    if (!item) {
        console.error('Item not found:', itemId);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Item data not found. Please refresh the page and try again.'
        });
        return;
    }
    
    // Populate the edit modal
    console.log('Populating edit modal with item:', item);
    console.log('Available fields in response:', Object.keys(item));
    console.log('Brand field exists:', 'brand' in item);
    console.log('Unit field exists:', 'unit' in item);
    console.log('Description field exists:', 'description' in item);
    
    $('#edit_item_id').val(item.id);
    $('#edit_item_name_store').val(item.item_name);
    $('#edit_brand_store').val(item.brand || '');
    $('#edit_unit_store').val(item.unit || 'pcs');
    $('#edit_description_store').val(item.description || '');
    $('#edit_quantity_store').val(item.quantity);
    $('#edit_unit_price_store').val(item.unit_price);
    $('#edit_batch_number_store').val(item.batch_number);
    $('#edit_location_store').val(item.location || 'Central Store');
    $('#edit_notes_store').val(item.notes || '');
    $('#edit_sku_store').val(item.sku);
    $('#edit_barcode_store').val(item.barcode);
    
    console.log('Quantity set to:', item.quantity);
    
    // Debug: Check if quantity field exists and set value
    const quantityField = $('#edit_quantity_store');
    if (quantityField.length) {
        // Try multiple approaches to set the value
        quantityField.val(item.quantity);
        quantityField.attr('value', item.quantity);
        quantityField.prop('value', item.quantity);
        
        console.log('Quantity field found, setting to:', item.quantity);
        console.log('Field value after setting:', quantityField.val());
        
        // Force trigger change event
        quantityField.trigger('change');
        quantityField.trigger('input');
        
        // Check again after a short delay
        setTimeout(() => {
            console.log('Quantity field value after delay:', $('#edit_quantity_store').val());
        }, 100);
    } else {
        console.error('Quantity field not found');
    }
    
    // Populate read-only fields with current values
    $('#edit_supplier_display_store').val(item.supplier?.company_name || '');
    $('#edit_supplier_store').val(item.supplier?.id || '');
    $('#edit_po_display_store').val(item.purchase_order?.po_number || '');
    $('#edit_po_store').val(item.purchase_order?.id || '');
    $('#edit_item_name_store').val(item.item_name);
    
    // Load and display existing images if any
    if (item.images && item.images.length > 0) {
        displayExistingImages(item.images);
    } else {
        // Show placeholder if no images
        $('#edit_noImagesPlaceholder').show();
        $('#edit_imagePreviewContainer').empty();
    }
    
    // Load categories only (supplier and PO are read-only)
    loadCategoriesForEdit();
    
    // Final check and set for quantity field
    setTimeout(() => {
        const finalQuantityField = $('#edit_quantity_store');
        if (finalQuantityField.length) {
            finalQuantityField.val(item.quantity);
            finalQuantityField.attr('value', item.quantity);
            console.log('Final quantity check - setting to:', item.quantity);
            console.log('Final quantity field value:', finalQuantityField.val());
        }
    }, 500);
});


    
    
    // Preview images for edit modal
    function previewEditImages(input) {
        const container = $('#edit_imagePreviewContainer');
        const placeholder = $('#edit_noImagesPlaceholder');
        
        if (input.files && input.files.length > 0) {
            placeholder.hide();
            container.empty();
            
            Array.from(input.files).forEach((file, index) => {
                if (index >= 5) return; // Max 5 images
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = $('<div class="col-4"></div>');
                    const card = $(`
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                            <div class="card-body p-2">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEditImage(${index})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `);
                    col.append(card);
                    container.append(col);
                };
                reader.readAsDataURL(file);
            });
        } else {
            placeholder.show();
        }
    }
    
    // Remove image from edit modal
    function removeEditImage(index) {
        const input = document.getElementById('edit_itemImages');
        const dt = new DataTransfer();
        
        Array.from(input.files).forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        input.files = dt.files;
        previewEditImages(input);
    }
    
    // Display existing images in edit modal
    function displayExistingImages(images) {
        const container = $('#edit_imagePreviewContainer');
        const placeholder = $('#edit_noImagesPlaceholder');
        
        if (images && images.length > 0) {
            placeholder.hide();
            container.empty();
            
            images.forEach((image, index) => {
                // Handle both string paths and object formats
                let imageSrc = '';
                if (typeof image === 'string') {
                    imageSrc = `/storage/${image}`;
                } else if (image.url) {
                    imageSrc = image.url;
                } else if (image.path) {
                    imageSrc = `/storage/${image.path}`;
                }
                
                const col = $('<div class="col-6 col-md-4 mb-3 existing-image"></div>');
                const card = $(`
                    <div class="card h-100">
                        <img src="${imageSrc}" class="card-img-top" style="height: 150px; object-fit: contain; background-color: #f8f9fa;" alt="Product Image">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Existing Image ${index + 1}</small>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeExistingImage(${index}, '${image}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
                col.append(card);
                container.append(col);
            });
        } else {
            placeholder.show();
        }
    }
    
    // Remove existing image from edit modal
    function removeExistingImage(index, imagePath) {
        // Add to a hidden input to track images to delete
        let imagesToDelete = $('#edit_images_to_delete').val();
        if (!imagesToDelete) {
            imagesToDelete = [];
        } else {
            imagesToDelete = JSON.parse(imagesToDelete);
        }
        
        imagesToDelete.push(imagePath);
        $('#edit_images_to_delete').val(JSON.stringify(imagesToDelete));
        
        // Remove from display
        $(`#edit_imagePreviewContainer .existing-image:eq(${index})`).remove();
        
        // Show placeholder if no images left
        if ($('#edit_imagePreviewContainer .existing-image').length === 0 && $('#edit_imagePreviewContainer .new-image-preview').length === 0) {
            $('#edit_noImagesPlaceholder').show();
        }
    }
    
    // Display images in view modal
    function displayViewImages(images) {
        const container = $('#view_images');
        
        console.log('displayViewImages called with:', images);
        console.log('Images type:', typeof images);
        console.log('Images length:', images ? images.length : 'null');
        
        if (images && images.length > 0) {
            let html = '<div class="row g-2">';
            images.forEach((image, index) => {
                console.log(`Image ${index}:`, image);
                console.log(`Image ${index} type:`, typeof image);
                
                // Handle both string paths and object formats
                let imageSrc = '';
                if (typeof image === 'string') {
                    imageSrc = `/storage/${image}`;
                } else if (image.url) {
                    imageSrc = image.url;
                } else if (image.path) {
                    imageSrc = `/storage/${image.path}`;
                }
                
                console.log(`Image ${index} src:`, imageSrc);
                
                html += `
                    <div class="col-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <img src="${imageSrc}" class="card-img-top" style="height: 200px; object-fit: contain; background-color: #f8f9fa; cursor: pointer;" alt="Product Image" onerror="console.log('Image failed to load:', '${imageSrc}')" onclick="showFullImage('${imageSrc}', 'Product Image ${index + 1}')">
                            <div class="card-body p-2">
                                <small class="text-muted">Product Image ${index + 1}</small>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            container.html(html);
        } else {
            container.html('<p class="text-muted">No images available</p>');
        }
    }
    
    // Show full size image modal
    function showFullImage(imageSrc, imageTitle) {
        $('#fullSizeImage').attr('src', imageSrc);
        $('#fullSizeImageModalLabel').text(imageTitle);
        $('#fullSizeImageModal').modal('show');
    }
    
    // Preview images for edit modal
    function previewEditImages(input) {
        const container = $('#edit_imagePreviewContainer');
        const placeholder = $('#edit_noImagesPlaceholder');
        
        if (input.files && input.files.length > 0) {
            placeholder.hide();
            
            // Clear existing previews for new files
            container.find('.new-image-preview').remove();
            
            Array.from(input.files).forEach((file, index) => {
                if (index < 5) { // Max 5 images
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        container.append(`
                            <div class="col-6 col-md-4 mb-3 new-image-preview">
                                <div class="card h-100">
                                    <div class="position-relative">
                                        <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: contain; background-color: #f8f9fa;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                                onclick="removeEditImage(${index})" style="margin: 2px;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="card-body p-2">
                                        <small class="text-muted">New Image ${index + 1}</small>
                                    </div>
                                </div>
                            </div>
                        `);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            // If no new files selected, show placeholder if no existing images
            if (container.find('.col-6').length === 0) {
                placeholder.show();
            }
        }
    }
    
    // Remove new image from edit modal
    function removeEditImage(index) {
        const input = document.getElementById('edit_itemImages');
        const dt = new DataTransfer();
        
        Array.from(input.files).forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        input.files = dt.files;
        previewEditImages(input);
    }



// Handle Edit Form Submission
$('#editItemForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const itemId = $('#edit_item_id').val();
    
    console.log('Submitting form with itemId:', itemId);
    console.log('Form data:', formData);
    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
    
    $.ajax({
        url: `/company/warehouse/central-store/items/${itemId}/update`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Item updated successfully'
                });
                
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editItemModal'));
                if (modal) {
                    modal.hide();
                } else {
                    $('#editItemModal').modal('hide');
                }
                
                // Reload the items
                loadCentralStoreItems(1);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to update item'
                });
            }
        },
        error: function(xhr) {
            console.error('Error updating item:', xhr);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to update item. Please try again.'
            });
        }
    });
});

// Initialize modals properly
$(document).ready(function() {
    // Initialize Bootstrap modals
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modalElement => {
        new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
    });
    
    // Clean up modal backdrops when modals are hidden
    $('.modal').on('hidden.bs.modal', function() {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });
    
    // Test route access
    $.get('/company/warehouse/central-store/test-update-route', function(response) {
        console.log('Test route response:', response);
    }).fail(function(xhr, status, error) {
        console.error('Test route failed:', xhr.status, error);
    });


    loadPageData();
    loadCentralStoreItems();
    
});

</script>
