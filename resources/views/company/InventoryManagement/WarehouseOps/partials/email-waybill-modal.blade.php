<!-- Email Waybill Modal -->
<div class="modal fade" id="emailWaybillModal" tabindex="-1" aria-labelledby="emailWaybillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="emailWaybillModalLabel">Email Waybill</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="emailWaybillForm">
                    <div class="mb-3">
                        <label class="form-label">To</label>
                        <input type="email" class="form-control" value="customer@example.com">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" value="Your Waybill #WB-2023-1001">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="5">Dear Valued Customer,
                            Please find attached the waybill for your recent order #ORD-2023-1001.
                            Tracking Number: 1234567890
                            Carrier: DHL Express
                            If you have any questions, please don't hesitate to contact us.
                            Best regards, ShrinQ Ghana Limited
                        </textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Attachments</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="attachWaybill" checked>
                            <label class="form-check-label" for="attachWaybill">Waybill (PDF)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="attachInvoice" checked>
                            <label class="form-check-label" for="attachInvoice">Invoice (PDF)</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info text-white">
                    <i class="fas fa-paper-plane me-2"></i>Send Email
                </button>
            </div>
        </div>
    </div>
</div>
