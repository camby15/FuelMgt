<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-danger mb-3" style="font-size: 48px;"></i>
                    <h5>Are you sure you want to delete this customer?</h5>
                    <p class="mb-0">Customer: <strong id="customerName"></strong></p>
                    <p>ID: <strong id="customerId"></strong></p>
                    <p class="text-danger">This action cannot be undone!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash-alt me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize delete confirmation modal
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteConfirmationModal');
        
        // When modal is shown, update the customer details
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const customerId = button.getAttribute('data-customer-id');
            const customerName = button.getAttribute('data-customer-name');
            
            // Update the modal's content
            document.getElementById('customerId').textContent = customerId;
            document.getElementById('customerName').textContent = customerName;
            
            // Set up the delete button click handler
            document.getElementById('confirmDeleteBtn').onclick = function() {
                // Here you would typically make an AJAX call to delete the customer
                console.log('Deleting customer:', customerId);
                
                // Example AJAX call (uncomment and implement your endpoint)
                /*
                fetch(`/api/customers/${customerId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Handle success (e.g., remove the row from the table)
                    button.closest('tr').remove();
                    // Show success message
                    alert('Customer deleted successfully');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting customer');
                });
                */
                
                // For demo purposes, just close the modal and remove the row
                const modal = bootstrap.Modal.getInstance(deleteModal);
                modal.hide();
                button.closest('tr').remove();
                
                // Show a success message (you can replace this with a toast notification)
                alert(`Customer ${customerName} (${customerId}) has been deleted.`);
            };
        });
        
        // Clean up event listeners when modal is hidden
        deleteModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('confirmDeleteBtn').onclick = null;
        });
    });
</script>
@endpush
