document.addEventListener('DOMContentLoaded', function() {
    // Toggle recurring task options
    const recurringTaskCheckbox = document.getElementById('recurringTask');
    const recurringOptions = document.getElementById('recurringOptions');
    
    if (recurringTaskCheckbox && recurringOptions) {
        recurringTaskCheckbox.addEventListener('change', function() {
            recurringOptions.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Set minimum date to today for task date
    const today = new Date().toISOString().split('T')[0];
    const taskDateInput = document.getElementById('taskDate');
    if (taskDateInput) {
        taskDateInput.min = today;
    }

    // Form validation
    const housekeepingForm = document.getElementById('housekeepingForm');
    if (housekeepingForm) {
        housekeepingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            let isValid = true;
            const requiredFields = housekeepingForm.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Time validation
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            
            if (startTime && endTime && startTime >= endTime) {
                isValid = false;
                alert('End time must be after start time');
            }

            if (isValid) {
                // Show loading state
                const submitBtn = housekeepingForm.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Creating...';
                
                // Simulate form submission
                setTimeout(() => {
                    // Here you would typically make an AJAX call to submit the form
                    console.log('Form submitted successfully');
                    
                    // Show success message
                    alert('Task created successfully!');
                    
                    // Reset form and close modal
                    housekeepingForm.reset();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('housekeepingModal'));
                    if (modal) modal.hide();
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    
                    // You might want to refresh the task list here
                    // refreshTaskList();
                }, 1500);
            }
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Function to format date for display
function formatDate(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Function to refresh task list (to be implemented)
function refreshTaskList() {
    // Implementation to refresh the task list after form submission
    console.log('Refreshing task list...');
}
