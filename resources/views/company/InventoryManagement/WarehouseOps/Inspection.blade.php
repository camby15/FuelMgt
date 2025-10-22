@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Base button styles */
    .btn {
        font-weight: 500;
        text-transform: none;
        letter-spacing: 0.3px;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn i {
        font-size: 0.9em;
    }
    
    /* Primary button */
    .btn-primary {
        background-color: #4361ee;
        border-color: #4361ee;
    }
    
    .btn-primary:hover, .btn-primary:focus {
        background-color: #3a56d4;
        border-color: #3a56d4;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(67, 97, 238, 0.15);
    }
    
    /* Success button */
    .btn-success {
        background-color: #10b981;
        border-color: #10b981;
    }
    
    .btn-success:hover, .btn-success:focus {
        background-color: #0d9f6e;
        border-color: #0d9f6e;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.15);
    }
    
    /* Secondary button */
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-secondary:hover, .btn-secondary:focus {
        background-color: #5c636a;
        border-color: #5c636a;
        transform: translateY(-1px);
    }
    
    /* Action buttons */
    .action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
        border: 1px solid transparent;
    }
    
    .action-btn i {
        font-size: 0.85rem;
    }
    
    .action-btn:hover, .action-btn:focus {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    
    /* Button variants */
    .btn-view {
        color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.1);
        border-color: transparent;
    }
    
    .btn-view:hover, .btn-view:focus {
        color: #fff;
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .btn-print {
        color: #6b7280;
        background-color: rgba(107, 114, 128, 0.1);
        border-color: transparent;
    }
    
    .btn-print:hover, .btn-print:focus {
        color: #fff;
        background-color: #6b7280;
        border-color: #6b7280;
    }
    
    .btn-status {
        color: #10b981;
        background-color: rgba(16, 185, 129, 0.1);
        border-color: transparent;
    }
    
    .btn-status:hover, .btn-status:focus {
        color: #fff;
        background-color: #10b981;
        border-color: #10b981;
    }
    
    /* Dropdown menu */
    .dropdown-menu {
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        padding: 0.5rem 0;
    }
    
    .dropdown-item {
        padding: 0.5rem 1.25rem;
        font-size: 0.9rem;
        color: #4b5563;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .dropdown-item:hover, .dropdown-item:focus {
        background-color: #f9fafb;
        color: #1f2937;
    }
    
    .dropdown-item i {
        width: 18px;
        text-align: center;
    }
    
    /* Make sure tooltips work with the buttons */
    [data-bs-toggle="tooltip"] {
        position: relative;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .action-btn {
            width: 30px;
            height: 30px;
        }
        
        .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
        }
        
        .dropdown-menu {
            min-width: 180px;
            font-size: 0.875rem;
        }
        
        .dropdown-item {
            padding: 0.4rem 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .action-btn {
            width: 28px;
            height: 28px;
        }
        
        .btn {
            padding: 0.35rem 0.7rem;
            font-size: 0.85rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
<div class="warehouse-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Quality Control Inspections</h5>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newInspectionModal">
            <i class="fas fa-plus me-2"></i>New Inspection
        </button>
    </div>
    
    <div class="table-responsive">
        
        <table class="table table-hover" id="inspectionTable">
            <thead class="table-light">
                <tr>
                    <th>Inspection ID</th>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Batch #</th>
                    <th>Inspector</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
                        <tbody>
                <!-- Data will be loaded dynamically -->
            </tbody>
        </table>

        <!-- Empty state for inspection table -->
        <div id="inspectionEmptyState" class="text-center py-5" style="display: none;">
            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
            <h6 class="text-muted">No Quality Inspections Found</h6>
            <p class="text-muted small">No quality inspections have been performed yet. Click "New Inspection" to get started.</p>
        </div>

   

<div class="row mt-3">
    <div class="col-md-12">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="ins_paginationControls">
                <!-- Pagination controls will be inserted here by JavaScript --> 
            </ul>
        </nav>
    </div>
</div>

    </div>
</div>
     
<!-- New Inspection Modal -->
<div class="modal fade" id="newInspectionModal" tabindex="-1" aria-labelledby="newInspectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newInspectionModalLabel">New Quality Inspection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="inspectionForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ins_supplier" class="form-label">Supplier</label>
                            <select class="form-select" id="ins_supplier" required>
                                <option value="">Select Supplier</option>
                              
                            </select>
                        </div>
                    <div class="col-md-6">
                        <label for="ins_poNumber" class="form-label">PO Number</label>
                        <select class="form-control" id="ins_poNumber" required>
                            <option value="">Select PO Number</option>
                            <!-- Options will be loaded dynamically via JavaScript -->
                        </select>
                    </div>
                    </div>

                                         <div class="row mb-3">
                         <div class="col-md-4">
                             <label for="ins_item" class="form-label">Item</label>
                             <select class="form-select" id="ins_item" required>
                                 <option value="">Select Item</option>
                                 <!-- Options will be loaded dynamically via JavaScript -->
                             </select>
                         </div>
                        <div class="col-md-4">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="ins_category" required>
                            <option value="">Select Category</option>

                            <!-- General Categories -->
                            <option value="Electronics">Electronics</option>
                            <option value="Office Supplies">Office Supplies</option>
                            <option value="IT Equipment">IT Equipment</option>
                            <option value="Tools & Hardware">Tools & Hardware</option>
                            <option value="Cleaning Supplies">Cleaning Supplies</option>
                            <option value="Packaging Materials">Packaging Materials</option>
                            <option value="Stationery">Stationery</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Safety Equipment">Safety Equipment</option>
                            <option value="Lighting">Lighting</option>
                            <option value="Building Materials">Building Materials</option>
                            <option value="Medical Supplies">Medical Supplies</option>
                            <option value="Food & Beverages">Food & Beverages</option>
                            <option value="Maintenance & Repairs">Maintenance & Repairs</option>
                            <option value="Batteries & Power">Batteries & Power</option>
                            <option value="Networking Devices">Networking Devices</option>
                            <option value="Accessories">Accessories</option>
                            <option value="Miscellaneous">Miscellaneous</option>
                        </select>
                    </div>

                        <div class="col-md-4">
                            <label for="batchNumber" class="form-label">Batch/Lot Number</label>
                            <input type="text" class="form-control" id="ins_batchNumber" required disabled>
                        </div>
                    </div>

                                         <div class="row mb-3">
                         <div class="col-md-3">
                             <label for="quantity" class="form-label">Quantity</label>
                             <input type="number" class="form-control" id="ins_quantity" required readonly>
                         </div>
                         <div class="col-md-3">
                             <label for="unitPrice" class="form-label">Unit Price (GH₵)</label>
                             <div class="input-group">
                                 <span class="input-group-text">GH₵</span>
                                 <input type="number" class="form-control" id="ins_unitPrice" step="0.01" required readonly>
                             </div>
                         </div>
                         <div class="col-md-3">
                             <label for="totalPrice" class="form-label">Total Price (GH₵)</label>
                             <div class="input-group">
                                 <span class="input-group-text">GH₵</span>
                                 <input type="text" class="form-control" id="ins_totalPrice" readonly>
                             </div>
                         </div>
                        <div class="col-md-3">
                            <label for="inspectionDate" class="form-label">Inspection Date</label>
                            <input type="date" class="form-control" id="ins_inspectionDate" required>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Quality Checklist</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check1">
                                <label class="form-check-label" for="check1">Packaging is intact and undamaged</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check2">
                                <label class="form-check-label" for="check2">Labels match the product</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check3">
                                <label class="form-check-label" for="check3">Quantity matches packing slip</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check4">
                                <label class="form-check-label" for="check4">No visible damage to items</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check5">
                                <label class="form-check-label" for="check5">Meets specifications</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="inspectionNotes" class="form-label">Inspection Notes</label>
                        <textarea class="form-control" id="inspectionNotes" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="photoUpload" class="form-label">Upload Photos</label>
                        <input class="form-control" type="file" id="ins_photoUpload" multiple accept="image/*">
                        <div class="form-text">Upload photos of any issues or for documentation</div>

                        <div id="ins_imagePreviewContainer" class="mb-3"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Inspection Result</label>
                        <div class="d-flex gap-3">
                                                         <div class="form-check">
                                 <input class="form-check-input" type="radio" name="inspectionResult" id="resultApproved" value="approved" checked>
                                 <label class="form-check-label text-success" for="resultApproved">
                                     <i class="fas fa-check-circle me-1"></i> Approved
                                 </label>
                             </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="inspectionResult" id="resultProcessing" value="processing">
                                <label class="form-check-label text-warning" for="resultProcessing">
                                    <i class="fas fa-cog me-1"></i> Processing
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="inspectionResult" id="resultReject" value="reject">
                                <label class="form-check-label text-danger" for="resultReject">
                                    <i class="fas fa-times-circle me-1"></i> Reject
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveInspection">Save Inspection</button>
            </div>
        </div>
    </div>
</div>

<!-- View Inspection Modal -->
<div class="modal fade" id="viewInspectionModal" tabindex="-1" aria-labelledby="viewInspectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewInspectionModalLabel">Inspection Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="inspectionDetails">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
                <button type="button" class="btn btn-primary btn-print-modal">
                    <i class="fas fa-print me-1"></i> Print Report
                </button>
            </div>
        </div>
    </div>
</div>





<!-- Edit Inspection Modal -->
<div class="modal fade" id="editInspectionModal" tabindex="-1" aria-labelledby="editInspectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInspectionModalLabel">Edit Quality Inspection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editInspectionForm">
                    <input type="hidden" id="edit_inspection_id">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_supplier" class="form-label">Supplier</label>
                            <select class="form-select" id="edit_supplier" required disabled>
                              
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_poNumber" class="form-label">PO Number</label>
                            <select class="form-control" id="edit_poNumber" required disabled>
                                
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="edit_item" class="form-label">Item</label>
                            <input type="text" class="form-control" id="edit_item" required readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="edit_category" required readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_batchNumber" class="form-label">Batch/Lot Number</label>
                            <input type="text" class="form-control" id="edit_batchNumber" required readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="edit_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit_quantity" required>
                        </div>
                        <div class="col-md-3">
                            <label for="edit_unitPrice" class="form-label">Unit Price (GH₵)</label>
                            <div class="input-group">
                                <span class="input-group-text">GH₵</span>
                                <input type="number" class="form-control" id="edit_unitPrice" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="edit_totalPrice" class="form-label">Total Price (GH₵)</label>
                            <div class="input-group">
                                <span class="input-group-text">GH₵</span>
                                <input type="text" class="form-control" id="edit_totalPrice" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="edit_inspectionDate" class="form-label">Inspection Date</label>
                            <input type="date" class="form-control" id="edit_inspectionDate" required>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Quality Checklist</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="edit_check1">
                                <label class="form-check-label" for="edit_check1">Packaging is intact and undamaged</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="edit_check2">
                                <label class="form-check-label" for="edit_check2">Labels match the product</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="edit_check3">
                                <label class="form-check-label" for="edit_check3">Quantity matches packing slip</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="edit_check4">
                                <label class="form-check-label" for="edit_check4">No visible damage to items</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_check5">
                                <label class="form-check-label" for="edit_check5">Meets specifications</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_inspectionNotes" class="form-label">Inspection Notes</label>
                        <textarea class="form-control" id="edit_inspectionNotes" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_photoUpload" class="form-label">Add More Photos</label>
                        <input class="form-control" type="file" id="edit_photoUpload" multiple accept="image/*">
                        <div id="edit_imagePreviewContainer" class="mb-3"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Inspection Result</label>
                        <div class="d-flex gap-3">
                                                         <div class="form-check">
                                 <input class="form-check-input" type="radio" name="edit_inspectionResult" id="edit_resultApproved" value="approved">
                                 <label class="form-check-label text-success" for="edit_resultApproved">
                                     <i class="fas fa-check-circle me-1"></i> Approved
                                 </label>
                             </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="edit_inspectionResult" id="edit_resultProcessing" value="processing">
                                <label class="form-check-label text-warning" for="edit_resultProcessing">
                                    <i class="fas fa-cog me-1"></i> Processing
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="edit_inspectionResult" id="edit_resultReject" value="reject">
                                <label class="form-check-label text-danger" for="edit_resultReject">
                                    <i class="fas fa-times-circle me-1"></i> Reject
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateInspection">Update Inspection</button>
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
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
    $(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();

     // Initialize Select2 for category after loading options
     function initializeCategorySelect2() {
         $('#ins_category').select2({
            placeholder: "Select a category",
            allowClear: true,
            dropdownParent: $('#newInspectionModal')
        });
     }
    
    // Load suppliers when modal opens
    $('#newInspectionModal').on('show.bs.modal', function() {
        loadSuppliers();
        loadCategories();
        generateBatchNumber();
    });

    // Calculate total price
    $('#ins_quantity, #ins_unitPrice').on('input', function() {
        const qty = parseInt($('#ins_quantity').val()) || 0;
        const price = parseFloat($('#ins_unitPrice').val()) || 0;
        $('#totalPrice').val((qty * price).toFixed(2));
    });

    // Load suppliers with pending POs
    loadSuppliers();
    
    // Test categories loading immediately
    console.log("Testing categories load on page ready...");
    
    // Simple test - load static categories immediately
    console.log("Loading static categories for testing...");
    loadStaticCategories();
    
    // Also try dynamic loading
    loadCategories();
    function loadSuppliers() {
        $.ajax({
            url: '/company/warehouse/quality-inspections/suppliers-with-pending-pos',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log("Response data ssss:", response);
                $('#ins_supplier').empty().append('<option value="">Select Supplier</option>');
                response.forEach(supplier => {
                    $('#ins_supplier').append(`<option value="${supplier.id}">${supplier.company_name}</option>`);
                });
            }
        });
    }

    // Load categories dynamically
    function loadCategories() {
        console.log("Loading categories...");
        console.log("CSRF Token:", $('meta[name="csrf-token"]').attr('content'));
        console.log("URL:", '/company/warehouse/quality-inspections/categories');
        console.log("Category element exists:", $('#ins_category').length > 0);
        console.log("Category element:", $('#ins_category')[0]);
        
        $.ajax({
            url: '/company/warehouse/quality-inspections/categories',
            method: 'POST',
            data: {},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            success: function(response) {
                console.log("Categories loaded:", response);
                if (response.success && response.data) {
                    console.log("Found " + response.data.length + " categories");
                    $('#ins_category').empty().append('<option value="">Select Category</option>');
                    response.data.forEach(category => {
                        console.log("Adding category:", category.name);
                        $('#ins_category').append(`<option value="${category.name}">${category.name}</option>`);
                    });
                    // Destroy existing Select2 and reinitialize with a small delay
                    if ($('#ins_category').hasClass('select2-hidden-accessible')) {
                        $('#ins_category').select2('destroy');
                    }
                    setTimeout(function() {
                        initializeCategorySelect2();
                        console.log("Categories loaded successfully");
                    }, 100);
                } else {
                    console.warn("Failed to load dynamic categories, using static ones");
                    loadStaticCategories();
                }
            },
            error: function(xhr) {
                console.error("Error loading categories:", xhr);
                console.error("Status:", xhr.status);
                console.error("Response:", xhr.responseText);
                // Load static categories as fallback
                loadStaticCategories();
            }
        });
    }

    // Load static categories as fallback
    function loadStaticCategories() {
        console.log("Loading static categories as fallback...");
        $('#ins_category').empty().append('<option value="">Select Category</option>');
        
        const staticCategories = [
            'Electronics', 'Office Supplies', 'IT Equipment', 'Tools & Hardware',
            'Cleaning Supplies', 'Packaging Materials', 'Stationery', 'Furniture',
            'Safety Equipment', 'Lighting', 'Building Materials', 'Medical Supplies',
            'Food & Beverages', 'Maintenance & Repairs', 'Batteries & Power',
            'Networking Devices', 'Accessories', 'Miscellaneous'
        ];
        
        staticCategories.forEach(category => {
            $('#ins_category').append(`<option value="${category}">${category}</option>`);
        });
        
        // Destroy existing Select2 and reinitialize with a small delay
        if ($('#ins_category').hasClass('select2-hidden-accessible')) {
            $('#ins_category').select2('destroy');
        }
        setTimeout(function() {
            initializeCategorySelect2();
            console.log("Static categories loaded");
        }, 100);
    }


    // Generate batch number
    function generateBatchNumber() {
        $.ajax({
            url: '/company/warehouse/quality-inspections/generate-batch-number',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#ins_batchNumber').val(response.batch_number);
                }
            },
            error: function(xhr) {
                console.error("Error generating batch number:", xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to generate batch number'
                });
            }
        });
    }

    // When supplier is selected, load their POs
    $('#ins_supplier').on('change', function() {
        const supplierId = $(this).val();
        if (!supplierId) return;
        
        $.ajax({
            url: `/company/warehouse/quality-inspections/${supplierId}/pending-pos`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log("Response data: vvv", response);
                $('#ins_poNumber').empty().append('<option value="">Select Purchase Order Number</option>');
                response.forEach(po => {
                    // $('#ins_poNumber').append(`<option value="${po.id}" data-items='${JSON.stringify(po.items)}'>${po.po_number} - <span >${po.inspection_progress} inspected</span></option>`);
                    $('#ins_poNumber').append(`
                    <option 
                        value="${po.id}" 
                        data-items='${JSON.stringify(po.items)}'
                        style="
                        padding: 8px 12px;
                        font-size: 14px;
                        border-bottom: 1px solid #eee;
                        " >
                        <span style="font-weight: bold;">${po.po_number}</span> - 
                        <span style="color: ${po.inspection_progress.split('/')[0] == po.inspection_progress.split('/')[1] ? '#28a745' : '#ffc107'}; 
                            font-weight: 500;">
                        ${po.inspection_progress} inspected
                        </span>
                    </option>
                    `);
                });
            },
            error: function(err){
                console.log("Error loading POs: ", err);
            }
        });
    });

    // When PO is selected, load uninspected items
    $('#ins_poNumber').on('change', function() {
        const poId = $(this).val();
        if (!poId) return;
        
        $.ajax({
            url: `/company/warehouse/quality-inspections/${poId}/uninspected-items`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log("Response data: nnnn", response);
                $('#ins_item').empty().append('<option value="">Select Item</option>');
                response.forEach(item => {
                    $('#ins_item').append(`<option value="${item.name}" data-quantity="${item.quantity}" data-price="${item.unit_price}">${item.name}</option>`);
                });
            }
        });
    });

    // When item is selected, populate details
    $('#ins_item').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        if (!selectedOption.val()) return;
        
        // $('#ins_category').val(selectedOption.data('category'));
        $("#ins_quantity").val(selectedOption.data('quantity')); 
        $('#ins_unitPrice').val(selectedOption.data('price'));
        $("#ins_totalPrice").val((parseFloat(selectedOption.data('price')) * parseInt($('#ins_quantity').val() || 0)).toFixed(2));
        $('#ins_quantity').trigger('input'); // Recalculate total
    });


    $('#ins_photoUpload').on('change', function() {
    const files = this.files;
    const previewContainer = $('#ins_imagePreviewContainer');
    previewContainer.empty();
    
    if (files.length > 0) {
        previewContainer.append('<h6 class="mt-2">Selected Images:</h6><div class="d-flex flex-wrap gap-2"></div>');
        const previewDiv = previewContainer.find('div');
        
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.append(`
                    <div class="position-relative" style="width: 100px;">
                        <img src="${e.target.result}" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="$(this).parent().remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `);
            }
            reader.readAsDataURL(files[i]);
        }
    }
});


   $('#ins_quantity, #ins_unitPrice').on('input', function() {
        calculateTotalPrice();
    });

    // Set current date as default inspection date
    const today = new Date().toISOString().split('T')[0];
   
    $('#ins_inspectionDate').val(today).attr('max', today);

    // Calculation function
    function calculateTotalPrice() {
        const quantity = parseFloat($('#ins_quantity').val()) || 0;
        const unitPrice = parseFloat($('#ins_unitPrice').val()) || 0;
        const totalPrice = quantity * unitPrice;
        
        // Format with 2 decimal places and display
        $('#ins_totalPrice').val(totalPrice.toFixed(2));
    }

    $('#ins_quantity, #ins_unitPrice').on('input', function() {
    if ($(this).val() < 0) {
        $(this).val(0);
    }
    calculateTotalPrice();
    });

    calculateTotalPrice();




 $('#saveInspection').on('click', function() {
    // Create FormData object to handle file uploads
    const formData = new FormData();
    
    // Add all form fields
    formData.append('supplier_id', $('#ins_supplier').val());
    formData.append('purchase_order_id', $('#ins_poNumber').val());
    formData.append('item_name', $('#ins_item').val());
    formData.append('item_category', $('#ins_category').val());
    formData.append('unit_price', $('#ins_unitPrice').val());
    formData.append('quantity', $('#ins_quantity').val());
    formData.append('batch_number', $('#ins_batchNumber').val());
    formData.append('inspection_date', $('#ins_inspectionDate').val());
    formData.append('checklist_results', JSON.stringify(getChecklistResults()));
    formData.append('status', $('input[name="inspectionResult"]:checked').val());
    formData.append('notes', $('#inspectionNotes').val());
    formData.append('inspection_result', $('input[name="inspectionResult"]:checked').val());
    

  
    // Add all selected files
    const files = $('#ins_photoUpload')[0].files;
    for (let i = 0; i < files.length; i++) {
        formData.append('photos[]', files[i]);
    }

      // Debugging - log form data contents
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    // Show loading indicator
    const saveBtn = $(this);
    saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
     
    
    $.ajax({
        url: '/company/warehouse/quality-inspections',
        method: 'POST',
        data: formData,
        processData: false,  // Important for file upload
        contentType: false,   // Important for file upload
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
                 success: function(response) {
             if (response.success) {
                 $('#newInspectionModal').modal('hide');
                 
                 // Clean up modal backdrop after a short delay
                 setTimeout(function() {
                     cleanupModalBackdrops();
                 }, 300);
                 
                 Swal.fire({
                     icon: 'success',
                     title: 'Success',
                     text: response.message || 'Inspection saved successfully'
                 }).then(function() {
                     // Additional cleanup after SweetAlert is closed
                     forceCleanupAllModals();
                     
                     // Ensure page is scrollable
                     $('body').css({
                         'overflow': 'auto',
                         'position': 'static'
                     });
                     $('html').css({
                         'overflow': 'auto',
                         'position': 'static'
                     });
                 });
                 
                 // Refresh the inspection list instead of reloading the page
                 loadInspections(1);
                 // Clear the form
                 clearInspectionForm();
                 
                 // Final backdrop cleanup
                 setTimeout(function() {
                     forceCleanupAllModals();
                 }, 500);
             }
         },
        error: function(xhr) {
            console.log("Error response data: ", xhr);
            let errorMessage = xhr.responseJSON?.message || 'Failed to save inspection';
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
        },
        complete: function() {
            saveBtn.prop('disabled', false).html('Save Inspection');
        }
    });
});

// // Helper function to get checklist results
// function getChecklistResults() {
//     return {
//         packaging_ok: $('#check1').is(':checked'),
//         labels_match: $('#check2').is(':checked'),
//         quantity_matches: $('#check3').is(':checked'),
//         no_damage: $('#check4').is(':checked'),
//         meets_specs: $('#check5').is(':checked')
//     };
// }


    // Helper function to get checklist results
    function getChecklistResults() {
        const results = {};
        for (let i = 1; i <= 5; i++) {
            results[`check${i}`] = $(`#check${i}`).is(':checked');
        }
        return results;
    }

    // View inspection details
    $('.view-inspection').on('click', function() {
        const inspectionId = $(this).data('id');
        $.ajax({
            url: `/company/warehouse/quality-inspections/${inspectionId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    displayInspectionDetails(response.data);
                    $('#viewInspectionModal').modal('show');
                }
            }
        });
    });

    // Display inspection details in modal
    function displayInspectionDetails(inspection) {
        const checklist = inspection.checklist_results || {};
        
        let html = `
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Supplier Information</h6>
                    <p><strong>Name:</strong> ${inspection.supplier.company_name}</p>
                    <p><strong>PO Number:</strong> ${inspection.purchase_order.po_number}</p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>Inspection ID:</strong> ${inspection.inspection_id}</p>
                    <p><strong>Batch Number:</strong> ${inspection.batch_number}</p>
                    <p><strong>Date:</strong> ${new Date(inspection.inspection_date).toLocaleDateString()}</p>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Item Details</h6>
                    <p><strong>Item:</strong> ${inspection.item_name}</p>
                    <p><strong>Category:</strong> ${inspection.item_category}</p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>Quantity:</strong> ${inspection.quantity}</p>
                    <p><strong>Unit Price:</strong> GH₵${parseFloat(inspection.unit_price).toFixed(2)}</p>
                    <p><strong>Total Price:</strong> GH₵${parseFloat(inspection.total_price).toFixed(2)}</p>
                </div>
            </div>
            
            <div class="mb-4">
                <h6>Checklist Results</h6>
                <ul class="list-group">
                    ${generateChecklistHtml(checklist)}
                </ul>
            </div>
            
            <div class="mb-4">
                <h6>Inspection Result</h6>
                <span class="badge bg-${getStatusBadgeClass(inspection.status)}">
                    ${inspection.status.toUpperCase()}
                </span>
                ${inspection.inspection_result ? `<p class="mt-2">${inspection.inspection_result}</p>` : ''}
            </div>
            
            <div class="mb-4">
                <h6>Notes</h6>
                <p>${inspection.notes || 'No notes available'}</p>
            </div>
        `;
        
        $('#inspectionDetails').html(html);
    }

    function generateChecklistHtml(checklist) {
        const items = [
            { id: 'check1', label: 'Packaging is intact and undamaged' },
            { id: 'check2', label: 'Labels match the product' },
            { id: 'check3', label: 'Quantity matches packing slip' },
            { id: 'check4', label: 'No visible damage to items' },
            { id: 'check5', label: 'Meets specifications' }
        ];
        
        return items.map(item => `
            <li class="list-group-item ${checklist[item.id] ? 'list-group-item-success' : 'list-group-item-danger'}">
                ${item.label}
            </li>
        `).join('');
    }

         function getStatusBadgeClass(status) {
         switch(status) {
             case 'approved': return 'success';
             case 'processing': return 'warning';
             case 'reject': return 'danger';
             default: return 'secondary';
         }
     }

     // Function to clear the inspection form
     function clearInspectionForm() {
         $('#ins_supplier').val('');
         $('#ins_poNumber').val('').html('<option value="">Select PO Number</option>');
         $('#ins_item').val('').html('<option value="">Select Item</option>');
         $('#ins_category').val('');
         $('#ins_batchNumber').val('');
         $('#ins_quantity').val('');
         $('#ins_unitPrice').val('');
         $('#ins_totalPrice').val('');
         $('#ins_inspectionDate').val('');
         $('#inspectionNotes').val('');
         $('#ins_photoUpload').val('');
         $('#ins_imagePreviewContainer').empty();
         
         // Reset checkboxes
         for (let i = 1; i <= 5; i++) {
             $(`#check${i}`).prop('checked', false);
         }
         
         // Reset radio buttons to default
         $('#resultApproved').prop('checked', true);
     }

     // Clear form when modal is closed
     $('#newInspectionModal').on('hidden.bs.modal', function() {
         clearInspectionForm();
         // Clean up any remaining backdrop
         cleanupModalBackdrops();
     });

    // Function to clean up all modal backdrops
    function cleanupModalBackdrops() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css({
            'padding-right': '',
            'overflow': '',
            'position': ''
        });
        $('html').removeClass('modal-open').css({
            'overflow': '',
            'position': ''
        });
    }

    // More aggressive backdrop cleanup
    function forceCleanupAllModals() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css({
            'padding-right': '',
            'overflow': '',
            'position': '',
            'top': '',
            'left': '',
            'right': '',
            'bottom': ''
        });
        $('.modal').removeClass('show').attr('style', '');
        $('html').removeClass('modal-open').css({
            'overflow': '',
            'position': '',
            'top': '',
            'left': '',
            'right': '',
            'bottom': ''
        });
        
        // Force reflow to ensure styles are applied
        $('body')[0].offsetHeight;
    }

     // Clean up backdrops when clicking on them
     $(document).on('click', '.modal-backdrop', function() {
         forceCleanupAllModals();
     });






        // Clean up any existing backdrops on page load
    forceCleanupAllModals();
    
    // Additional cleanup to ensure page is scrollable
    $(window).on('load', function() {
        forceCleanupAllModals();
        $('body').css({
            'overflow': 'auto',
            'position': 'static'
        });
        $('html').css({
            'overflow': 'auto',
            'position': 'static'
        });
    });
     
     // Load inspections when page loads
function loadInspections(page = 1) {
    $.ajax({
        url: '/company/warehouse/quality-inspections/all',
        method: 'POST',
        data: { page: page }, // Send current page number
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log("Response data: ", response);
            if (response.success) {
                // Clear and rebuild table
                $('#inspectionTable tbody').empty();
                
                                if (response.inspections && response.inspections.length > 0) {
                    // Show table, hide empty state
                    $('#inspectionTable').show();
                    $('#inspectionEmptyState').hide();
                    
                    response.inspections.forEach(inspection => {
                        const row = `
                            <tr>
                                <td>${inspection.inspection_id || 'N/A'}</td>
                                <td>${inspection.item_name}</td>
                                <td>${inspection.item_category}</td>
                                <td>${inspection.supplier.company_name}</td>
                                <td>${inspection.quantity}</td>
                                <td>GH₵${parseFloat(inspection.unit_price).toFixed(2)}</td>
                                <td>GH₵${parseFloat(inspection.total_price).toFixed(2)}</td>
                                <td>${inspection.batch_number}</td>
                                <td>${inspection.inspector_name}</td>
                                <td><span class="badge bg-${getStatusBadgeClass(inspection.status)}">${inspection.status.toUpperCase()}</span></td>
                                <td>${new Date(inspection.inspection_date).toLocaleDateString()}</td>
                                <td>
                                    <div class="d-flex gap-2"> 
                                        <!-- View Button -->
                                        <button class="btn btn-sm btn-outline-primary view-inspection" data-id="${inspection.id}" data-bs-toggle="tooltip" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <!-- edit Button -->
                                        <button class="btn btn-sm btn-outline-success edit-inspection" data-id="${inspection.id}" data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Delete Button -->
                                        <button class="btn btn-sm btn-outline-danger delete-inspection" data-id="${inspection.id}" data-bs-toggle="tooltip" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        $('#inspectionTable tbody').append(row);
                    });
                    
                    // Update pagination controls
                    updatePaginationControls(response.pagination);
                    
                    // Reinitialize tooltips for new elements
                    $('[data-bs-toggle="tooltip"]').tooltip();
                } else {
                    // Hide table, show empty state
                    $('#inspectionTable').hide();
                    $('#inspectionEmptyState').show();
                    $('#ins_paginationControls').empty();
                }
            }
        },
        error: function(xhr) {
            console.error("Error loading inspections:", xhr);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load inspections'
            });
        }
    });
}

function updatePaginationControls(pagination) {
    console.log("Pagination data: ", pagination);
    const paginationContainer = $('#ins_paginationControls');
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

// Handle pagination clicks
$(document).on('click', '.page-link', function(e) {
    e.preventDefault();
    const page = $(this).data('page');
    if (page) {
        loadInspections(page);
    }
});

 loadInspections(1);

// Show empty state initially if no inspections
setTimeout(() => {
    if ($('#inspectionTable tbody tr').length === 0) {
        $('#inspectionTable').hide();
        $('#inspectionEmptyState').show();
    }
}, 500);

// Edit inspection
$(document).on('click', '.edit-inspection', function() {
    const inspectionId = $(this).data('id');
    
    $.ajax({
        url: `/company/warehouse/quality-inspections/${inspectionId}/edit`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Populate the edit form (you'll need to create this modal)
                populateEditForm(response.data);
                $('#editInspectionModal').modal('show');
            }
        }
    });
});


// Update inspection
$('#updateInspection').on('click', function() {
    const formData = new FormData();
    const inspectionId = $('#edit_inspection_id').val();
    
    // Add all required form fields
    formData.append('_method', 'PUT');
    formData.append('item_name', $('#edit_item').val());
    formData.append('item_category', $('#edit_category').val());
    formData.append('unit_price', $('#edit_unitPrice').val());
    formData.append('quantity', $('#edit_quantity').val());
    formData.append('inspection_date', $('#edit_inspectionDate').val());
    formData.append('status', $('input[name="edit_inspectionResult"]:checked').val());
    formData.append('notes', $('#edit_inspectionNotes').val());
    
    // Add checklist results
    const checklistResults = {
        check1: $('#edit_check1').is(':checked'),
        check2: $('#edit_check2').is(':checked'),
        check3: $('#edit_check3').is(':checked'),
        check4: $('#edit_check4').is(':checked'),
        check5: $('#edit_check5').is(':checked')
    };
    formData.append('checklist_results', JSON.stringify(checklistResults));
    
    // Add new photos
    const files = $('#edit_photoUpload')[0].files;
    for (let i = 0; i < files.length; i++) {
        formData.append('photos[]', files[i]);
    }

    const saveBtn = $(this);
    saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
    
    $.ajax({
        url: `/company/warehouse/quality-inspections/${inspectionId}`,
        method: 'POST', // Laravel needs POST for FormData with PUT method
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#editInspectionModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message || 'Inspection updated successfully'
                });
                loadInspections();
            }
        },
        error: function(xhr) {
            let errorMessage = 'Failed to update inspection';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMessage
            });
        },
        complete: function() {
            saveBtn.prop('disabled', false).html('Update Inspection');
        }
    });
});

$(document).on('click', '.delete-inspection', function() {
    const inspectionId = $(this).data('id');
    
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/company/warehouse/quality-inspections/${inspectionId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message || 'Inspection deleted successfully'
                        });
                        loadInspections(); // Refresh the list
                    }
                },
                error: function(xhr) {
                    console.error("Error deleting inspection:", xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete inspection'
                    });
                }
            });
        }
    });
}); 


$(document).on('click', '.view-inspection', function() {
    const inspectionId = $(this).data('id');
    
    $.ajax({
        url: `/company/warehouse/quality-inspections/${inspectionId}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                displayInspectionDetails(response.data);
                $('#viewInspectionModal').modal('show');
            }
        },
        error: function(xhr) {
            console.error("Error loading inspection details:", xhr);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load inspection details'
            });
        }
    });
});



// Populate the edit form with inspection data
function populateEditForm(inspection) {
    // Basic information

    console.log("Inspection data: ", inspection);
    $('#edit_inspection_id').val(inspection.id);
    $('#edit_supplier').val(inspection.supplier.id).trigger('change');
    $('#edit_poNumber').val(inspection.purchase_order_id).trigger('change');
    $('#edit_item').val(inspection.item_name);
    $('#edit_category').val(inspection.item_category);
    $('#edit_batchNumber').val(inspection.batch_number);
    $('#edit_quantity').val(inspection.quantity);
    $('#edit_unitPrice').val(inspection.unit_price);
    $('#edit_totalPrice').val(inspection.total_price);
    $('#edit_inspectionDate').val(inspection.inspection_date.split('T')[0]);
    $('#edit_inspectionNotes').val(inspection.notes);

        $('#edit_supplier').empty().append(
        `<option value="${inspection.supplier.id}" selected>${inspection.supplier.company_name}</option>`
    );

    
    
    // PO Number information (read-only display)
    $('#edit_poNumber').empty().append(
        `<option value="${inspection.purchase_order_id}" selected>${inspection.purchase_order.po_number}</option>`
    );
    
    // Checklist
    const checklist = inspection.checklist_results || {};
    for (let i = 1; i <= 5; i++) {
        $(`#edit_check${i}`).prop('checked', checklist[`check${i}`] || false);
    }
    
    // Inspection result
    $(`#edit_result${inspection.status.charAt(0).toUpperCase() + inspection.status.slice(1)}`).prop('checked', true);
    
    // Photos preview
    const previewContainer = $('#edit_imagePreviewContainer');
    previewContainer.empty();
    
    if (inspection.photos && inspection.photos.length > 0) {
        previewContainer.append('<h6 class="mt-2">Current Images:</h6><div class="d-flex flex-wrap gap-2" id="currentPhotosContainer"></div>');
        const currentPhotosDiv = previewContainer.find('#currentPhotosContainer');
        
        inspection.photos.forEach(photo => {
            currentPhotosDiv.append(`
                <div class="position-relative" style="width: 100px;">
                    <img src="/storage/${photo.path}" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="removePhoto(${inspection.id}, '${photo.id || photo.path}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `);
        });
    }
    
    // Handle new photo upload preview
    $('#edit_photoUpload').on('change', function() {
        const files = this.files;
        const previewContainer = $('#edit_imagePreviewContainer');
        
        if (!previewContainer.find('#newPhotosContainer').length) {
            previewContainer.append('<h6 class="mt-3">New Images:</h6><div class="d-flex flex-wrap gap-2" id="newPhotosContainer"></div>');
        }
        
        const newPhotosDiv = previewContainer.find('#newPhotosContainer');
        
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                newPhotosDiv.append(`
                    <div class="position-relative" style="width: 100px;">
                        <img src="${e.target.result}" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="$(this).parent().remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `);
            }
            reader.readAsDataURL(files[i]);
        }
    });
    
    // Calculate total price when quantity or price changes
    $('#edit_quantity, #edit_unitPrice').on('input', function() {
        const quantity = parseFloat($('#edit_quantity').val()) || 0;
        const unitPrice = parseFloat($('#edit_unitPrice').val()) || 0;
        $('#edit_totalPrice').val((quantity * unitPrice).toFixed(2));
    });
}

// Function to remove a photo (called from the photo remove button)
function removePhoto(inspectionId, photoId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This photo will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/company/warehouse/quality-inspections/${inspectionId}/photos`,
                method: 'DELETE',
                data: {
                    photo_id: photoId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log("Response data: ", response);
                    if (response.success) {
                        // Remove the photo from the UI
                        $(`button[onclick*="${photoId}"]`).parent().remove();
                        Swal.fire(
                            'Deleted!',
                            'The photo has been deleted.',
                            'success'
                        );
                    }
                },
                error: function(xhr) {
                    console.error("Error deleting photo:", xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete photo'
                    });
                }
            });
        }
    });
}

// Update inspection when save button is clicked
$('#updateInspection').on('click', function() {
    const inspectionId = $('#edit_inspection_id').val();
    const formData = new FormData();
    
    // Add all form fields
    formData.append('_method', 'PUT'); // Laravel's way to handle PUT with POST
    formData.append('item_name', $('#edit_item').val());
    formData.append('item_category', $('#edit_category').val())
    formData.append('quantity', $('#edit_quantity').val());
    formData.append('unit_price', $('#edit_unitPrice').val());
    formData.append('inspection_date', $('#edit_inspectionDate').val());
    formData.append('checklist_results', JSON.stringify(getEditChecklistResults()));
    formData.append('status', $('input[name="edit_inspectionResult"]:checked').val());
    formData.append('notes', $('#edit_inspectionNotes').val());
    
    // Add new photos
    const files = $('#edit_photoUpload')[0].files;
    for (let i = 0; i < files.length; i++) {
        formData.append('photos[]', files[i]);
    }

    // Show loading indicator
    const saveBtn = $(this);
    saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
    
    $.ajax({
        url: `/company/warehouse/quality-inspections/${inspectionId}`,
        method: 'POST',
        data: formData,
        processData: false,  // Important for file upload
        contentType: false,   // Important for file upload
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#editInspectionModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message || 'Inspection updated successfully'
                });
                loadInspections(1); // Refresh the list
            }
        },
        error: function(xhr) {
            console.error("Error updating inspection:", xhr);
            let errorMessage = xhr.responseJSON?.message || 'Failed to update inspection';
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
        },
        complete: function() {
            saveBtn.prop('disabled', false).html('Update Inspection');
        }
    });
});

// Helper function to get checklist results from edit form
function getEditChecklistResults() {
    return {
        check1: $('#edit_check1').is(':checked'),
        check2: $('#edit_check2').is(':checked'),
        check3: $('#edit_check3').is(':checked'),
        check4: $('#edit_check4').is(':checked'),
        check5: $('#edit_check5').is(':checked')
    };
}

});
</script>