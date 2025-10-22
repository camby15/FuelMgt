// HR Management JavaScript
class HRManagement {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // View Employee
        document.addEventListener('click', (e) => {
            const viewBtn = e.target.closest('.view-employee-btn');
            if (viewBtn) {
                e.preventDefault();
                this.handleViewEmployee(viewBtn);
            }
        });

        // Edit Employee
        document.addEventListener('click', (e) => {
            const editBtn = e.target.closest('.edit-employee-btn');
            if (editBtn) {
                e.preventDefault();
                this.handleEditEmployee(editBtn);
            }
        });

        // Delete Employee
        document.addEventListener('click', (e) => {
            const deleteBtn = e.target.closest('.delete-employee-btn');
            if (deleteBtn) {
                e.preventDefault();
                this.handleDeleteEmployee(deleteBtn);
            }
        });
    }


    handleViewEmployee(button) {
        const employeeId = button.dataset.employeeId;
        console.log('Viewing employee:', employeeId);
        
        // Here you would typically make an AJAX call to fetch employee data
        // For now, we'll just show the modal
        const modal = new bootstrap.Modal(document.getElementById('viewEmployeeModal'));
        modal.show();
        
        // Update modal with employee data (example)
        // this.fetchEmployeeData(employeeId).then(data => {
        //     this.updateViewModal(data);
        //     modal.show();
        // });
    }


    handleEditEmployee(button) {
        const employeeId = button.dataset.employeeId;
        console.log('Editing employee:', employeeId);
        
        // Here you would typically make an AJAX call to fetch employee data
        // For now, we'll just show the modal
        const modal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
        modal.show();
    }


    handleDeleteEmployee(button) {
        const employeeId = button.dataset.employeeId;
        console.log('Deleting employee:', employeeId);
        
        // Show confirmation dialog
        if (confirm('Are you sure you want to delete this employee?')) {
            // Here you would typically make an AJAX call to delete the employee
            console.log('Employee deleted:', employeeId);
            
            // Show success message
            this.showToast('Success', 'Employee deleted successfully', 'success');
            
            // Refresh the table or remove the row
            button.closest('tr').remove();
        }
    }


    showToast(title, message, type = 'info') {
        // You can implement a toast notification here
        // Example with Bootstrap 5 Toast
        const toastContainer = document.getElementById('toastContainer') || this.createToastContainer();
        
        const toastElement = document.createElement('div');
        toastElement.className = `toast align-items-center text-white bg-${type} border-0`;
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'assertive');
        toastElement.setAttribute('aria-atomic', 'true');
        
        toastElement.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong><br>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastContainer.appendChild(toastElement);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
        
        // Remove toast after it's hidden
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }
    
    createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.style.position = 'fixed';
        container.style.top = '20px';
        container.style.right = '20px';
        container.style.zIndex = '1100';
        document.body.appendChild(container);
        return container;
    }
}

// Initialize HR Management when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize HR Management
    const hrManagement = new HRManagement();
    
    // Make it globally available if needed
    window.HRManagement = hrManagement;
    
    // Initialize DataTable if it exists
    if (typeof $.fn.DataTable === 'function' && document.getElementById('employeesTable')) {
        $('#employeesTable').DataTable({
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search employees...",
                paginate: {
                    previous: "<i class='fas fa-chevron-left'></i>",
                    next: "<i class='fas fa-chevron-right'></i>"
                }
            },
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });
    }
});
