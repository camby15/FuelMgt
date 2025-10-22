// Waybill Modals Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle view waybill button clicks
    document.querySelectorAll('.view-waybill').forEach(button => {
        button.addEventListener('click', function() {
            // In a real app, you would fetch the waybill data here
            console.log('Viewing waybill:', this.dataset.id);
        });
    });

    // Handle print waybill button clicks
    document.querySelectorAll('.print-waybill').forEach(button => {
        button.addEventListener('click', function(e) {
            // Don't trigger if this is a button inside another modal
            if (this.closest('.modal')) {
                e.stopPropagation();
                return;
            }
            
            const waybillId = this.dataset.id;
            console.log('Printing waybill:', waybillId);
            // In a real app, you would open the print dialog or redirect to print page
            // window.open(`/waybills/${waybillId}/print`, '_blank');
        });
    });

    // Handle email waybill button clicks
    document.querySelectorAll('.email-waybill').forEach(button => {
        button.addEventListener('click', function(e) {
            // Don't trigger if this is a button inside another modal
            if (this.closest('.modal')) {
                e.stopPropagation();
                return;
            }
            
            const waybillId = this.dataset.id;
            console.log('Emailing waybill:', waybillId);
            // The modal will be shown by Bootstrap's data-bs-toggle
        });
    });

    // Handle delete waybill button clicks
    document.querySelectorAll('.delete-waybill').forEach(button => {
        button.addEventListener('click', function(e) {
            const waybillId = this.dataset.id;
            console.log('Deleting waybill:', waybillId);
            
            // In a real app, you would show a confirmation dialog first
            // and then make an API call to delete the waybill
            // fetch(`/api/waybills/${waybillId}`, { method: 'DELETE' })
            //     .then(response => response.json())
            //     .then(data => {
            //         // Refresh the waybills table or remove the row
            //         if (typeof waybillsTable !== 'undefined') {
            //             waybillsTable.ajax.reload();
            //         }
            //     });
        });
    });

    // Handle form submission for creating/editing waybills
    const waybillForm = document.getElementById('waybillForm');
    if (waybillForm) {
        waybillForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In a real app, you would collect the form data and send it to the server
            const formData = new FormData(waybillForm);
            console.log('Form data:', Object.fromEntries(formData));
            
            // Example fetch request:
            // fetch('/api/waybills', {
            //     method: 'POST',
            //     body: formData
            // })
            // .then(response => response.json())
            // .then(data => {
            //     // Close the modal and refresh the table
            //     const modal = bootstrap.Modal.getInstance(document.getElementById('newWaybillModal'));
            //     modal.hide();
            //     if (typeof waybillsTable !== 'undefined') {
            //         waybillsTable.ajax.reload();
            //     }
            // });
        });
    }

    // Handle print button in the print modal
    const printWaybillBtn = document.getElementById('printWaybillBtn');
    if (printWaybillBtn) {
        printWaybillBtn.addEventListener('click', function() {
            console.log('Printing waybill...');
            // In a real app, you would trigger the print dialog
            // window.print();
        });
    }

    // Handle send button in the email modal
    const sendEmailBtn = document.querySelector('#emailWaybillModal .btn-primary');
    if (sendEmailBtn) {
        sendEmailBtn.addEventListener('click', function() {
            console.log('Sending email...');
            // In a real app, you would collect the form data and send it to the server
            // const emailForm = document.getElementById('emailWaybillForm');
            // const formData = new FormData(emailForm);
            // fetch('/api/waybills/email', {
            //     method: 'POST',
            //     body: formData
            // })
            // .then(response => response.json())
            // .then(data => {
            //     // Close the modal
            //     const modal = bootstrap.Modal.getInstance(document.getElementById('emailWaybillModal'));
            //     modal.hide();
            // });
        });
    }
});
