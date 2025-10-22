/**
 * Leave Management JavaScript
 * Handles all AJAX requests for leave management functionality
 */

class LeaveManagement {
    constructor() {
        this.baseUrl = '/company/hr/leaves';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.currentFilters = {};
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadLeaveStats();
        this.loadLeaveTable();
    }

    bindEvents() {
        // Request Time Off form submission
        const timeOffForm = document.getElementById('timeOffRequestForm');
        if (timeOffForm) {
            timeOffForm.addEventListener('submit', (e) => this.handleTimeOffRequest(e));
        }

        // Approve leave button
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="approve-leave"]')) {
                const leaveId = e.target.closest('[data-action="approve-leave"]').dataset.leaveId;
                this.approveLeave(leaveId);
            }
        });

        // Reject leave button
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="reject-leave"]')) {
                const leaveId = e.target.closest('[data-action="reject-leave"]').dataset.leaveId;
                this.rejectLeave(leaveId);
            }
        });

        // Cancel leave button
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="cancel-leave"]')) {
                const leaveId = e.target.closest('[data-action="cancel-leave"]').dataset.leaveId;
                this.cancelLeave(leaveId);
            }
        });

        // Edit leave button
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="edit-leave"]')) {
                const leaveId = e.target.closest('[data-action="edit-leave"]').dataset.leaveId;
                this.editLeave(leaveId);
            }
        });

        // Delete leave button
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="delete-leave"]')) {
                const leaveId = e.target.closest('[data-action="delete-leave"]').dataset.leaveId;
                this.deleteLeave(leaveId);
            }
        });

        // Filter buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-filter]')) {
                const filter = e.target.closest('[data-filter]').dataset.filter;
                this.filterLeaves(filter);
            }
        });

        // Search input
        const searchInput = document.querySelector('#leaveSearch');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.searchLeaves(e.target.value);
                }, 500);
            });
        }
    }

    /**
     * Handle Time Off Request form submission
     */
    handleTimeOffRequest(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = {
            employee_id: formData.get('employee_id'),
            leave_type: formData.get('leave_type'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            reason: formData.get('reason')
        };

        this.showLoading('Submitting leave request...');

        fetch(`${this.baseUrl}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            this.hideLoading();
            if (data.success) {
                this.showToast('Leave request submitted successfully!', 'success');
                this.closeModal('requestTimeOffModal');
                this.loadLeaveTable();
                this.loadLeaveStats();
                e.target.reset();
            } else {
                this.showToast(data.message || 'Failed to submit leave request', 'error');
            }
        })
        .catch(error => {
            this.hideLoading();
            this.showToast('An error occurred while submitting the request', 'error');
            console.error('Error:', error);
        });
    }

    /**
     * Approve a leave request
     */
    approveLeave(leaveId) {
        if (!confirm('Are you sure you want to approve this leave request?')) {
            return;
        }

        this.showLoading('Approving leave request...');

        fetch(`${this.baseUrl}/${leaveId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            this.hideLoading();
            if (data.success) {
                this.showToast('Leave request approved successfully!', 'success');
                this.loadLeaveTable();
                this.loadLeaveStats();
                this.refreshCalendar();
            } else {
                this.showToast(data.message || 'Failed to approve leave request', 'error');
            }
        })
        .catch(error => {
            this.hideLoading();
            this.showToast('An error occurred while approving the request', 'error');
            console.error('Error:', error);
        });
    }

    /**
     * Reject a leave request
     */
    rejectLeave(leaveId) {
        const rejectionReason = prompt('Please provide a reason for rejection:');
        if (!rejectionReason) {
            return;
        }

        this.showLoading('Rejecting leave request...');

        fetch(`${this.baseUrl}/${leaveId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify({
                rejection_reason: rejectionReason
            })
        })
        .then(response => response.json())
        .then(data => {
            this.hideLoading();
            if (data.success) {
                this.showToast('Leave request rejected successfully!', 'success');
                this.loadLeaveTable();
                this.loadLeaveStats();
                this.refreshCalendar();
            } else {
                this.showToast(data.message || 'Failed to reject leave request', 'error');
            }
        })
        .catch(error => {
            this.hideLoading();
            this.showToast('An error occurred while rejecting the request', 'error');
            console.error('Error:', error);
        });
    }

    /**
     * Cancel a leave request
     */
    cancelLeave(leaveId) {
        if (!confirm('Are you sure you want to cancel this leave request?')) {
            return;
        }

        this.showLoading('Cancelling leave request...');

        fetch(`${this.baseUrl}/${leaveId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            this.hideLoading();
            if (data.success) {
                this.showToast('Leave request cancelled successfully!', 'success');
                this.loadLeaveTable();
                this.loadLeaveStats();
                this.refreshCalendar();
            } else {
                this.showToast(data.message || 'Failed to cancel leave request', 'error');
            }
        })
        .catch(error => {
            this.hideLoading();
            this.showToast('An error occurred while cancelling the request', 'error');
            console.error('Error:', error);
        });
    }

    /**
     * Edit a leave request
     */
    editLeave(leaveId) {
        // Load leave data and populate edit form
        fetch(`${this.baseUrl}/${leaveId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.populateEditForm(data.data);
                this.showModal('editLeaveModal');
            } else {
                this.showToast(data.message || 'Failed to load leave data', 'error');
            }
        })
        .catch(error => {
            this.showToast('An error occurred while loading leave data', 'error');
            console.error('Error:', error);
        });
    }

    /**
     * Delete a leave request
     */
    deleteLeave(leaveId) {
        if (!confirm('Are you sure you want to delete this leave request? This action cannot be undone.')) {
            return;
        }

        this.showLoading('Deleting leave request...');

        fetch(`${this.baseUrl}/${leaveId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            this.hideLoading();
            if (data.success) {
                this.showToast('Leave request deleted successfully!', 'success');
                this.loadLeaveTable();
                this.loadLeaveStats();
                this.refreshCalendar();
            } else {
                this.showToast(data.message || 'Failed to delete leave request', 'error');
            }
        })
        .catch(error => {
            this.hideLoading();
            this.showToast('An error occurred while deleting the request', 'error');
            console.error('Error:', error);
        });
    }

    /**
     * Load leave statistics
     */
    loadLeaveStats() {
        fetch(`${this.baseUrl}/stats`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateStatsCards(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading stats:', error);
        });
    }

    /**
     * Load leave table data
     */
    loadLeaveTable(filters = {}, page = 1) {
        const params = new URLSearchParams();
        Object.keys(filters).forEach(key => {
            if (filters[key]) {
                params.append(key, filters[key]);
            }
        });
        params.append('page', page);

        fetch(`${this.baseUrl}?${params.toString()}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateLeaveTable(data.data);
                this.updatePagination(data.pagination);
            }
        })
        .catch(error => {
            console.error('Error loading leave table:', error);
        });
    }

    /**
     * Update pagination controls
     */
    updatePagination(pagination) {
        const paginationContainer = document.getElementById('leavePagination');
        if (!paginationContainer || !pagination) return;

        const { current_page, last_page, per_page, total } = pagination;
        
        let paginationHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing ${((current_page - 1) * per_page) + 1} to ${Math.min(current_page * per_page, total)} of ${total} entries
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
        `;

        // Previous button
        if (current_page > 1) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${current_page - 1}">Previous</a>
                </li>
            `;
        }

        // Page numbers
        const startPage = Math.max(1, current_page - 2);
        const endPage = Math.min(last_page, current_page + 2);

        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <li class="page-item ${i === current_page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }

        // Next button
        if (current_page < last_page) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${current_page + 1}">Next</a>
                </li>
            `;
        }

        paginationHTML += `
                    </ul>
                </nav>
            </div>
        `;

        paginationContainer.innerHTML = paginationHTML;

        // Bind pagination click events
        paginationContainer.addEventListener('click', (e) => {
            e.preventDefault();
            if (e.target.classList.contains('page-link')) {
                const page = parseInt(e.target.dataset.page);
                this.loadLeaveTable(this.currentFilters, page);
            }
        });
    }

    /**
     * Filter leaves
     */
    filterLeaves(filter) {
        this.currentFilters = {};
        if (filter !== 'all') {
            this.currentFilters.status = filter;
        }
        this.loadLeaveTable(this.currentFilters);
    }

    /**
     * Search leaves
     */
    searchLeaves(searchTerm) {
        this.currentFilters = { ...this.currentFilters };
        if (searchTerm) {
            this.currentFilters.search = searchTerm;
        } else {
            delete this.currentFilters.search;
        }
        this.loadLeaveTable(this.currentFilters);
    }

    /**
     * Update stats cards
     */
    updateStatsCards(stats) {
        // Update pending count
        const pendingElement = document.querySelector('#pendingCount');
        if (pendingElement) {
            pendingElement.textContent = stats.pending || 0;
        }

        // Update approved count
        const approvedElement = document.querySelector('#approvedCount');
        if (approvedElement) {
            approvedElement.textContent = stats.approved || 0;
        }

        // Update leave balance
        const balanceElement = document.querySelector('#leaveBalance');
        if (balanceElement) {
            balanceElement.textContent = stats.leave_balance || 0;
        }

        // Update team on leave
        const teamOnLeaveElement = document.querySelector('#teamOnLeave');
        if (teamOnLeaveElement) {
            teamOnLeaveElement.textContent = stats.team_on_leave || 0;
        }
    }

    /**
     * Update leave table
     */
    updateLeaveTable(leaves) {
        const tbody = document.querySelector('#leaveTable tbody');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (leaves.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">No leave requests found</td></tr>';
            return;
        }

        leaves.forEach(leave => {
            const row = this.createLeaveRow(leave);
            tbody.appendChild(row);
        });
    }

    /**
     * Create leave table row
     */
    createLeaveRow(leave) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(leave.employee.personal_info.first_name + ' ' + leave.employee.personal_info.last_name)}&background=4e73df&color=fff" 
                         class="rounded-circle me-2" width="32" height="32" alt="${leave.employee.personal_info.first_name}">
                    <div>
                        <h6 class="mb-0">${leave.employee.personal_info.first_name} ${leave.employee.personal_info.last_name}</h6>
                        <small class="text-muted">${leave.employee.staff_id || 'N/A'}</small>
                    </div>
                </div>
            </td>
            <td>${leave.leave_type_label}</td>
            <td>${this.formatDate(leave.start_date)}</td>
            <td>${this.formatDate(leave.end_date)}</td>
            <td>${leave.total_days}</td>
            <td>
                <span class="badge ${leave.status_badge_class}">${leave.status_label}</span>
            </td>
            <td>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" data-action="view-leave" data-leave-id="${leave.id}" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    ${this.getActionButtons(leave)}
                </div>
            </td>
        `;
        return row;
    }

    /**
     * Get action buttons based on leave status
     */
    getActionButtons(leave) {
        let buttons = '';

        if (leave.status === 'pending') {
            buttons += `
                <button class="btn btn-sm btn-outline-success" data-action="approve-leave" data-leave-id="${leave.id}" title="Approve">
                    <i class="fas fa-check"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" data-action="reject-leave" data-leave-id="${leave.id}" title="Reject">
                    <i class="fas fa-times"></i>
                </button>
            `;
        }

        if (['pending', 'cancelled'].includes(leave.status)) {
            buttons += `
                <button class="btn btn-sm btn-outline-secondary" data-action="edit-leave" data-leave-id="${leave.id}" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" data-action="delete-leave" data-leave-id="${leave.id}" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }

        if (leave.status === 'approved') {
            buttons += `
                <button class="btn btn-sm btn-outline-warning" data-action="cancel-leave" data-leave-id="${leave.id}" title="Cancel">
                    <i class="fas fa-ban"></i>
                </button>
            `;
        }

        return buttons;
    }

    /**
     * Refresh calendar
     */
    refreshCalendar() {
        if (window.calendar) {
            window.calendar.refetchEvents();
        }
    }

    /**
     * Show loading state
     */
    showLoading(message = 'Loading...') {
        // Create or show loading overlay
        let loadingOverlay = document.querySelector('#loadingOverlay');
        if (!loadingOverlay) {
            loadingOverlay = document.createElement('div');
            loadingOverlay.id = 'loadingOverlay';
            loadingOverlay.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center';
            loadingOverlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
            loadingOverlay.style.zIndex = '9999';
            document.body.appendChild(loadingOverlay);
        }

        loadingOverlay.innerHTML = `
            <div class="text-center text-white">
                <div class="spinner-border mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>${message}</div>
            </div>
        `;
        loadingOverlay.style.display = 'flex';
    }

    /**
     * Hide loading state
     */
    hideLoading() {
        const loadingOverlay = document.querySelector('#loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'success') {
        const toastContainer = document.querySelector('.toast-container') || this.createToastContainer();
        
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastContainer.appendChild(toastEl);
        
        const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
        toast.show();
        
        // Remove toast after it's hidden
        toastEl.addEventListener('hidden.bs.toast', function() {
            toastEl.remove();
        });
    }

    /**
     * Create toast container
     */
    createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '11';
        document.body.appendChild(container);
        return container;
    }

    /**
     * Close modal
     */
    closeModal(modalId) {
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        if (modal) {
            modal.hide();
        }
    }

    /**
     * Show modal
     */
    showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    /**
     * Format date
     */
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    /**
     * Populate edit form
     */
    populateEditForm(leave) {
        // This would populate the edit form with leave data
        // Implementation depends on your edit form structure
        console.log('Populating edit form with:', leave);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new LeaveManagement();
});
