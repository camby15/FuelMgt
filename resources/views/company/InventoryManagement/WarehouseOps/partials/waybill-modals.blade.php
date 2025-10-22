<!-- New Waybill Modal -->
<div class="modal fade" id="newWaybillModal" tabindex="-1" aria-labelledby="newWaybillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="newWaybillModalLabel">Create New Waybill</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="waybillForm">
                    @csrf
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Waybill Number</label>
                                <input type="text" class="form-control" name="waybill_number" value="" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Outbound Order</label>
                                <select class="form-select" name="outbound_order_id" id="outboundOrderSelect" required>
                                    <option value="">Select Outbound Order</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Carrier</label>
                                <select class="form-select" name="carrier_id" id="carrierSelect" required>
                                    <option value="">Select Carrier</option>
                                    <option value="1">DHL Express</option>
                                    <option value="2">FedEx</option>
                                    <option value="3">UPS</option>
                                    <option value="4">Ghana Post</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estimated Delivery Date</label>
                                <input type="date" class="form-control" name="estimated_delivery_date" min="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tracking Number</label>
                                <input type="text" class="form-control" name="tracking_number" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="in_transit">In Transit</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="delayed">Delayed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveWaybill">
                    <i class="fas fa-save me-2"></i>Save Waybill
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Waybill Modal -->
<div class="modal fade" id="viewWaybillModal" tabindex="-1" aria-labelledby="viewWaybillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewWaybillModalLabel">Waybill Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Shipment Details</h6>
                        <p class="mb-1"><strong>Waybill #:</strong> <span class="waybill-number">-</span></p>
                        <p class="mb-1"><strong>Status:</strong> <span class="badge waybill-status">-</span></p>
                        <p class="mb-1"><strong>Customer:</strong> <span class="waybill-customer">-</span></p>
                        <p class="mb-1"><strong>Created:</strong> <span class="waybill-created">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Shipping Information</h6>
                        <p class="mb-1"><strong>Origin:</strong> <span class="waybill-origin">-</span></p>
                        <p class="mb-1"><strong>Destination:</strong> <span class="waybill-destination">-</span></p>
                        <p class="mb-1"><strong>Items:</strong> <span class="waybill-items">-</span></p>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Shipment Timeline</h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-point timeline-point-primary">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <div class="timeline-event">
                                    <div class="timeline-header">
                                        <h6>Waybill Created</h6>
                                        <small class="waybill-created">-</small>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-point timeline-point-primary">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                                <div class="timeline-event">
                                    <div class="timeline-header">
                                        <h6>In Transit</h6>
                                        <small>Estimated delivery: <span class="waybill-estimated-delivery">-</span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-point timeline-point-primary">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="timeline-event">
                                    <div class="timeline-header">
                                        <h6>Delivered</h6>
                                        <small class="waybill-delivered">Not yet delivered</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Shipment Items</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Weight</th>
                                    <th class="text-end">Dimensions</th>
                                </tr>
                            </thead>
                            <tbody id="viewItemsList">
                                <!-- Items will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Shipping From</h6>
                            </div>
                            <div class="card-body p-3">
                                <p class="mb-1"><strong>Acme Corporation</strong></p>
                                <p class="mb-1">123 Warehouse St</p>
                                <p class="mb-1">San Francisco, CA 94107</p>
                                <p class="mb-0">United States</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Shipping To</h6>
                            </div>
                            <div class="card-body p-3">
                                <p class="mb-1"><strong>John Doe</strong></p>
                                <p class="mb-1">456 Customer Ave</p>
                                <p class="mb-1">New York, NY 10001</p>
                                <p class="mb-0">United States</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <h6>Notes</h6>
                    <p class="text-muted" id="viewNotes">Fragile items - Handle with care</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary print-waybill" data-bs-toggle="modal" data-bs-target="#printWaybillModal">
                    <i class="fas fa-print me-2"></i>Print
                </button>
                <button type="button" class="btn btn-info text-white email-waybill" data-bs-toggle="modal" data-bs-target="#emailWaybillModal">
                    <i class="fas fa-envelope me-2"></i>Email
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Waybill Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    <input type="hidden" id="waybill_id" name="waybill_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="in_transit">In Transit</option>
                            <option value="delivered">Delivered</option>
                            <option value="delayed">Delayed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="delivered_fields" style="display: none;">
                        <label class="form-label">Delivered To</label>
                        <input type="text" class="form-control" id="delivered_to" name="delivered_to" placeholder="Enter recipient name">
                    </div>
                    
                    <div class="mb-3" id="delivery_notes_field" style="display: none;">
                        <label class="form-label">Delivery Notes</label>
                        <textarea class="form-control" id="delivery_notes" name="delivery_notes" rows="3" placeholder="Enter delivery notes"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateWaybillStatus()">
                    <i class="fas fa-save me-2"></i>Update Status
                </button>
            </div>
        </div>
    </div>
</div>

@include('company.InventoryManagement.WarehouseOps.partials.print-waybill-modal')
@include('company.InventoryManagement.WarehouseOps.partials.email-waybill-modal')
@include('company.InventoryManagement.WarehouseOps.partials.delete-waybill-modal')