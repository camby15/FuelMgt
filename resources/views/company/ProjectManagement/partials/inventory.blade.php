@push('styles')
<style>
    .inventory-card { transition: all 0.3s ease; height: 100%; }
    .inventory-card:hover { transform: translateY(-5px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1) !important; }
    .stock-low { border-left: 4px solid #f06548; }
    .stock-medium { border-left: 4px solid #f7b84b; }
    .stock-high { border-left: 4px solid #0ab39c; }
    .stock-out { border-left: 4px solid #f06548; opacity: 0.7; }
</style>
@endpush

<div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5>Inventory Management</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
            <i class="fas fa-plus me-1"></i> Add New Item
        </button>
    </div>
    <!-- Inventory Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-centered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $inventoryItems = [
                                ['id' => 'INV-1001', 'name' => 'Disinfectant Wipes', 'category' => 'Cleaning', 'location' => 'Main Storage', 'qty' => 15, 'status' => 'low'],
                                ['id' => 'INV-1002', 'name' => 'Trash Bags', 'category' => 'Cleaning', 'location' => 'Floor 1', 'qty' => 45, 'status' => 'high'],
                                ['id' => 'INV-1003', 'name' => 'Paper Towels', 'category' => 'Office', 'location' => 'Main Storage', 'qty' => 5, 'status' => 'low'],
                            ];
                        @endphp

                        @php
                            $inventoryItems = [
                                ['id' => 'INV-1001', 'name' => 'Disinfectant Wipes', 'category' => 'Cleaning', 'location' => 'Main Storage', 'qty' => 15, 'threshold' => 20, 'status' => 'low', 'unit' => 'pcs', 'description' => 'Disposable wipes for surface disinfection'],
                                ['id' => 'INV-1002', 'name' => 'Trash Bags', 'category' => 'Cleaning', 'location' => 'Floor 1', 'qty' => 45, 'threshold' => 30, 'status' => 'high', 'unit' => 'rolls', 'description' => 'Large trash bags 50L'],
                                ['id' => 'INV-1003', 'name' => 'Paper Towels', 'category' => 'Office', 'location' => 'Main Storage', 'qty' => 5, 'threshold' => 10, 'status' => 'low', 'unit' => 'packs', 'description' => '2-ply paper towels'],
                            ];
                        @endphp

                        @foreach($inventoryItems as $index => $item)
                            <tr class="align-middle">
                                <td>{{ $item['id'] }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['category'] }}</td>
                                <td>{{ $item['location'] }}</td>
                                <td>{{ $item['qty'] }} {{ $item['unit'] }}</td>
                                <td>
                                    @if($item['status'] === 'high')
                                        <span class="badge bg-success">In Stock</span>
                                    @elseif($item['status'] === 'low')
                                        <span class="badge bg-warning">Low Stock</span>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#viewItemModal{{ $index }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $index }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteItemModal{{ $index }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Edit Item Modal -->
                            <div class="modal fade" id="editItemModal{{ $index }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title">Edit Item</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="editItemForm{{ $index }}">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Item Name</label>
                                                    <input type="text" class="form-control" value="{{ $item['name'] }}" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Quantity</label>
                                                            <input type="number" class="form-control" value="{{ $item['qty'] }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Unit</label>
                                                            <select class="form-select">
                                                                <option {{ $item['unit'] === 'pcs' ? 'selected' : '' }}>pcs</option>
                                                                <option {{ $item['unit'] === 'rolls' ? 'selected' : '' }}>rolls</option>
                                                                <option {{ $item['unit'] === 'packs' ? 'selected' : '' }}>packs</option>
                                                                <option {{ $item['unit'] === 'liters' ? 'selected' : '' }}>liters</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Category</label>
                                                    <select class="form-select">
                                                        <option {{ $item['category'] === 'Cleaning' ? 'selected' : '' }}>Cleaning</option>
                                                        <option {{ $item['category'] === 'Office' ? 'selected' : '' }}>Office</option>
                                                        <option {{ $item['category'] === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                        <option {{ $item['category'] === 'Safety' ? 'selected' : '' }}>Safety</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Location</label>
                                                    <select class="form-select">
                                                        <option {{ $item['location'] === 'Main Storage' ? 'selected' : '' }}>Main Storage</option>
                                                        <option {{ $item['location'] === 'Floor 1' ? 'selected' : '' }}>Floor 1</option>
                                                        <option {{ $item['location'] === 'Floor 2' ? 'selected' : '' }}>Floor 2</option>
                                                        <option {{ $item['location'] === 'Janitor Room' ? 'selected' : '' }}>Janitor Room</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Threshold (Low Stock Alert)</label>
                                                    <input type="number" class="form-control" value="{{ $item['threshold'] }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" rows="3">{{ $item['description'] }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteItemModal{{ $index }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Confirm Deletion</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this item?</p>
                                            <p class="mb-0"><strong>{{ $item['id'] }}:</strong> {{ $item['name'] }}</p>
                                            <div class="alert alert-warning mt-3">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                This action cannot be undone.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-danger">Delete Item</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('company.ProjectManagement.modals.inventory-modals')
