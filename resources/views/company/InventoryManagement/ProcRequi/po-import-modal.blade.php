<!-- PO Import Modal -->
<div class="modal fade" id="importPOModal" tabindex="-1" aria-labelledby="importPOModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importPOModalLabel">
                    <i class="fas fa-file-import me-2"></i>Import Purchase Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Instructions -->
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Import Instructions</h6>
                    <ol class="mb-0">
                        <li>Download the Excel template below</li>
                        <li>Fill in the item details (description, quantity, unit price)</li>
                        <li>Complete the PO details in this form</li>
                        <li>Upload the filled template</li>
                        <li>Review and submit</li>
                    </ol>
                </div>

                <form id="poImportForm" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Step 1: Download Template -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-download me-2"></i>Step 1: Download Template</h6>
                        </div>
                        <div class="card-body text-center">
                            <p class="text-muted mb-3">Download the Excel template and fill in your item details</p>
                            <button type="button" class="btn btn-success" id="downloadTemplateBtn">
                                <i class="fas fa-file-excel me-2"></i>Download Excel Template (.xlsx)
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: PO Details -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Step 2: PO Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Auto-generated PO Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="importPONumber" class="form-label">PO Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="importPONumber" name="po_number" readonly>
                                    <div class="form-text">Auto-generated PO number</div>
                                </div>

                                <!-- Supplier Selection -->
                                <div class="col-md-6 mb-3">
                                    <label for="importSupplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                                    <select class="form-select" id="importSupplier" name="supplier_id" required>
                                        <option value="">Select Supplier</option>
                                    </select>
                                </div>

                                <!-- Order Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="importOrderDate" class="form-label">Order Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="importOrderDate" name="order_date" required>
                                </div>

                                <!-- Expected Delivery Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="importDeliveryDate" class="form-label">Expected Delivery Date</label>
                                    <input type="date" class="form-control" id="importDeliveryDate" name="delivery_date">
                                </div>

                                <!-- Notes -->
                                <div class="col-12 mb-3">
                                    <label for="importNotes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="importNotes" name="notes" rows="3" placeholder="Additional notes for this purchase order..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Tax Configuration -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Step 3: Tax Configuration</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Tax Method -->
                                <div class="col-md-6 mb-3">
                                    <label for="importTaxMethod" class="form-label">Tax Method <span class="text-danger">*</span></label>
                                    <select class="form-select" id="importTaxMethod" name="tax_method" required>
                                        <option value="select_config">Select from Configurations</option>
                                        <option value="manual_rate">Enter Manual Rate</option>
                                    </select>
                                </div>

                                <!-- Tax Configuration Dropdown -->
                                <div class="col-md-6 mb-3" id="importTaxConfigDiv">
                                    <label for="importTaxConfiguration" class="form-label">Tax Configuration</label>
                                    <div class="input-group">
                                        <select class="form-select" id="importTaxConfiguration" name="tax_configuration_id">
                                            <option value="">Select Configuration</option>
                                        </select>
                                        <span class="input-group-text">
                                            <i class="fas fa-spinner fa-spin d-none" id="importTaxConfigSpinner"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Manual Tax Rate -->
                                <div class="col-md-6 mb-3 d-none" id="importManualTaxRateDiv">
                                    <label for="importManualTaxRate" class="form-label">Tax Rate (%)</label>
                                    <input type="number" class="form-control" id="importManualTaxRate" name="tax_rate" 
                                           min="0" max="100" step="0.01" placeholder="Enter tax rate">
                                </div>

                                <!-- Tax Exemption -->
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="importTaxExempt" name="is_tax_exempt" value="1">
                                        <input type="hidden" name="is_tax_exempt" value="0">
                                        <label class="form-check-label" for="importTaxExempt">
                                            Mark as Tax Exempt
                                        </label>
                                    </div>
                                </div>

                                <!-- Tax Exemption Reason -->
                                <div class="col-12 mb-3 d-none" id="importTaxExemptionReasonDiv">
                                    <label for="importTaxExemptionReason" class="form-label">Tax Exemption Reason</label>
                                    <textarea class="form-control" id="importTaxExemptionReason" name="tax_exemption_reason" 
                                              rows="2" placeholder="Reason for tax exemption..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Upload File -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-upload me-2"></i>Step 4: Upload Filled Template</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="importExcelFile" class="form-label">Excel File <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="importExcelFile" name="excel_file" 
                                       accept=".xlsx,.xls,.csv" required>
                                <div class="form-text">Upload the filled Excel template (.xlsx, .xls, or .csv)</div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="progress mb-3 d-none" id="importProgressBar">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>

                            <!-- Results -->
                            <div id="importResults" class="d-none"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="submitImportBtn">
                    <i class="fas fa-upload me-2"></i>Import Purchase Order
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize import modal
    $('#importPOModal').on('show.bs.modal', function() {
        initializeImportModal();
    });

    // Generate PO number when modal opens
    function initializeImportModal() {
        // Set today's date as default
        $('#importOrderDate').val(new Date().toISOString().split('T')[0]);
        $('#importDeliveryDate').val(new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]);
        
        // Generate PO number
        generateImportPONumber();
        
        // Load suppliers
        loadImportSuppliers();
        
        // Load tax configurations
        loadImportTaxConfigurations();
        
        // Reset form
        $('#poImportForm')[0].reset();
        $('#importProgressBar').addClass('d-none');
        $('#importResults').addClass('d-none').empty();
    }

    // Generate PO number
    function generateImportPONumber() {
        $.ajax({
            url: '/company/warehouse/purchasing_order/generatePONumber',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#importPONumber').val(response.po_number);
                }
            },
            error: function(xhr) {
                console.error('Error generating PO number:', xhr.responseJSON);
                $('#importPONumber').val('PO-' + new Date().toISOString().slice(0,10).replace(/-/g,'') + '-XXXX');
            }
        });
    }

    // Load suppliers
    function loadImportSuppliers() {
        $.ajax({
            url: '/company/warehouse/purchasing_order/suppliers/all',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const select = $('#importSupplier');
                select.empty().append('<option value="">Select Supplier</option>');
                
                if (response.data && response.data.length > 0) {
                    response.data.forEach(supplier => {
                        select.append(`<option value="${supplier.id}">${supplier.company_name}</option>`);
                    });
                }
            },
            error: function(xhr) {
                console.error('Error loading suppliers:', xhr.responseJSON);
            }
        });
    }

    // Load tax configurations
    function loadImportTaxConfigurations() {
        $('#importTaxConfigSpinner').removeClass('d-none');
        
        $.ajax({
            url: '/company/warehouse/purchasing_order/tax-configurations',
            method: 'GET',
            success: function(response) {
                const select = $('#importTaxConfiguration');
                select.empty().append('<option value="">Select Configuration</option>');
                
                if (response.success && response.data) {
                    response.data.forEach(config => {
                        select.append(`<option value="${config.id}" data-rate="${config.rate}">
                            ${config.name} (${config.rate}%)
                        </option>`);
                    });
                }
            },
            error: function(xhr) {
                console.error('Error loading tax configurations:', xhr.responseJSON);
            },
            complete: function() {
                $('#importTaxConfigSpinner').addClass('d-none');
            }
        });
    }

    // Handle tax method change
    $('#importTaxMethod').on('change', function() {
        const method = $(this).val();
        if (method === 'select_config') {
            $('#importTaxConfigDiv').removeClass('d-none');
            $('#importManualTaxRateDiv').addClass('d-none');
            $('#importTaxConfiguration').prop('required', true);
            $('#importManualTaxRate').prop('required', false);
        } else {
            $('#importTaxConfigDiv').addClass('d-none');
            $('#importManualTaxRateDiv').removeClass('d-none');
            $('#importTaxConfiguration').prop('required', false);
            $('#importManualTaxRate').prop('required', true);
        }
    });

    // Handle tax exemption change
    $('#importTaxExempt').on('change', function() {
        if ($(this).is(':checked')) {
            $('#importTaxExemptionReasonDiv').removeClass('d-none');
        } else {
            $('#importTaxExemptionReasonDiv').addClass('d-none');
        }
    });

    // Download template
    $('#downloadTemplateBtn').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Downloading...');
        
        // Create a temporary form for download
        const form = $('<form>', {
            method: 'GET',
            action: '/company/warehouse/purchasing_order/download-template',
            target: '_blank'
        });
        
        $('body').append(form);
        form.submit();
        form.remove();
        
        // Reset button after a delay
        setTimeout(() => {
            btn.prop('disabled', false).html(originalText);
        }, 2000);
    });

    // Submit import
    $('#submitImportBtn').on('click', function() {
        const form = $('#poImportForm')[0];
        const formData = new FormData(form);
        
        // Validate form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        const btn = $(this);
        const originalText = btn.html();
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Importing...');
        
        // Show progress bar
        $('#importProgressBar').removeClass('d-none');
        
        $.ajax({
            url: '/company/warehouse/purchasing_order/import',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        const percentComplete = evt.loaded / evt.total * 100;
                        $('#importProgressBar .progress-bar').css('width', percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    $('#importResults').removeClass('d-none').html(`
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-2"></i>Import Successful!</h6>
                            <p class="mb-1"><strong>PO Number:</strong> ${response.po_number}</p>
                            <p class="mb-1"><strong>Items Imported:</strong> ${response.items_imported}</p>
                            <p class="mb-0"><strong>Total Amount:</strong> GHS ${parseFloat(response.total_amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                        </div>
                    `);
                    
                    // Close modal after delay
                    setTimeout(() => {
                        $('#importPOModal').modal('hide');
                        
                        // Clean up backdrop and modal with comprehensive cleanup
                        setTimeout(() => {
                            // Use comprehensive modal cleanup
                            if (typeof forceCleanupAllModals === 'function') {
                                forceCleanupAllModals();
                            } else {
                                // Fallback cleanup
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
                            }
                            
                            // Fix any scrolling issues
                            if (typeof window.fixPageScrolling === 'function') {
                                window.fixPageScrolling();
                            }
                            
                            // Ensure All PO tab is active and visible
                            setTimeout(() => {
                                console.log('Ensuring All PO tab is active...');
                                $('#all-po-tab').tab('show');
                                $('#all-po').addClass('show active');
                                $('#all-po').removeClass('fade');
                                
                                // Refresh the PO list
                                if (typeof loadTabContent === 'function') {
                                    console.log('Refreshing all-po tab after import...');
                                    loadTabContent('all-po');
                                }
                            }, 100);
                        }, 300);
                    }, 3000);
                } else {
                    $('#importResults').removeClass('d-none').html(`
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-circle me-2"></i>Import Failed</h6>
                            <p class="mb-0">${response.message}</p>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred during import';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                $('#importResults').removeClass('d-none').html(`
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-circle me-2"></i>Import Failed</h6>
                        <p class="mb-0">${errorMessage}</p>
                    </div>
                `);
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
                $('#importProgressBar').addClass('d-none');
            }
        });
    });

    // Reset modal when closed
    $('#importPOModal').on('hidden.bs.modal', function() {
        $('#poImportForm')[0].reset();
        $('#importProgressBar').addClass('d-none');
        $('#importResults').addClass('d-none').empty();
        $('#importTaxExemptionReasonDiv').addClass('d-none');
        
        // Clean up any lingering backdrop with comprehensive cleanup
        setTimeout(() => {
            // Use comprehensive modal cleanup
            if (typeof forceCleanupAllModals === 'function') {
                forceCleanupAllModals();
            } else {
                // Fallback cleanup
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
            }
        }, 100);
    });
});
</script>
