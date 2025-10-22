<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add New Inventory Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addItemForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Item Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="">Select Category</option>
                                    <option>Cleaning Supplies</option>
                                    <option>Office Supplies</option>
                                    <option>Maintenance Tools</option>
                                    <option>Safety Equipment</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Unit</label>
                                <select class="form-select">
                                    <option>Each</option>
                                    <option>Box</option>
                                    <option>Pack</option>
                                    <option>Liter</option>
                                    <option>Kilogram</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <select class="form-select">
                                    <option>Main Storage</option>
                                    <option>Floor 1 Closet</option>
                                    <option>Floor 2 Closet</option>
                                    <option>Janitor's Room</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Threshold (Low Stock Alert)</label>
                                <input type="number" class="form-control" placeholder="Minimum quantity before alert">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Item Image</label>
                                <input type="file" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Item Modal (Example for first item) -->
<div class="modal fade" id="viewItemModal0" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Item Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img src="https://via.placeholder.com/200" class="img-fluid rounded" alt="Item Image">
                </div>
                <table class="table table-sm">
                    <tr>
                        <th width="40%">Item ID:</th>
                        <td>INV-1001</td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td>Disinfectant Wipes</td>
                    </tr>
                    <tr>
                        <th>Category:</th>
                        <td>Cleaning Supplies</td>
                    </tr>
                    <tr>
                        <th>Quantity:</th>
                        <td>15 <span class="badge bg-warning">Low Stock</span></td>
                    </tr>
                    <tr>
                        <th>Location:</th>
                        <td>Main Storage</td>
                    </tr>
                    <tr>
                        <th>Threshold:</th>
                        <td>20</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td>2 hours ago</td>
                    </tr>
                </table>
                <div class="mt-3">
                    <h6>Description:</h6>
                    <p class="mb-0">Disposable wipes for surface disinfection. Kills 99.9% of bacteria and viruses.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal (Example for first item) -->
<div class="modal fade" id="deleteItemModal0" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
                <p class="mb-0"><strong>INV-1001:</strong> Disinfectant Wipes</p>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone. All inventory history for this item will be permanently deleted.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Delete Item</button>
            </div>
        </div>
    </div>
</div>

<!-- Include this script at the bottom of the file -->
@push('scripts')
<script>
    // Initialize any inventory-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Form validation for add item form
        document.getElementById('addItemForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            // Add form submission logic here
            console.log('Form submitted');
            // You can add AJAX submission here
        });
    });
</script>
@endpush
