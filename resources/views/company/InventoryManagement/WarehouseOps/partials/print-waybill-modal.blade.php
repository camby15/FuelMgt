<!-- Print Waybill Modal -->
<div class="modal fade" id="printWaybillModal" tabindex="-1" aria-labelledby="printWaybillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="printWaybillModalLabel">Print Waybill</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-print fa-4x text-muted mb-3"></i>
                    <h5>Print Waybill</h5>
                    <p class="text-muted">Select print options below</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Printer</label>
                    <select class="form-select">
                        <option>Select Printer</option>
                        <option>HP OfficeJet Pro 8020</option>
                        <option>Save as PDF</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Copies</label>
                    <input type="number" class="form-control" value="1" min="1">
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="printLabel" checked>
                    <label class="form-check-label" for="printLabel">Include Shipping Label</label>
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="printPackingSlip" checked>
                    <label class="form-check-label" for="printPackingSlip">Include Packing Slip</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>
