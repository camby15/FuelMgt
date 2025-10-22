<div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Service Request Management</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTicketModal">
                    <i class="fas fa-plus me-1"></i> New Service Request
                </button>
            </div>
        </div>
    </div>

    <!-- Create Ticket Modal -->
<div class="modal fade" id="createTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Create New Service Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ticketForm">
                <div class="modal-body">
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>SLA Tracking:</strong> Response time is calculated based on ticket priority.
                                <span id="slaInfo">Medium priority tickets have a 24-hour response time.</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="requestCategory" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="requestCategory" required>
                                <option value="">Select Category</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="it">IT Support</option>
                                <option value="transport">Transport</option>
                                <option value="facilities">Facilities</option>
                                <option value="cleaning">Cleaning</option>
                                <option value="security">Security</option>
                                <option value="event">Event Support</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" id="priority" required>
                                <option value="low" data-sla="72">Low (72h)</option>
                                <option value="medium" selected data-sla="24">Medium (24h)</option>
                                <option value="high" data-sla="8">High (8h)</option>
                                <option value="critical" data-sla="4">Critical (4h)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="subject" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" rows="4" placeholder="Please describe the issue in detail..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="assignedTo" class="form-label">Assign To</label>
                            <select class="form-select" id="assignedTo" required>
                                <option value="">Select Assignee</option>
                                <optgroup label="Technicians">
                                    <option value="1">Henry Martey (Technician)</option>
                                    <option value="2">Asare Adjei (Technician)</option>
                                </optgroup>
                                <optgroup label="Supervisors">
                                    <option value="3">Jones Clara (Supervisor)</option>
                                </optgroup>
                                <optgroup label="Managers">
                                    <option value="4">Mike Roach (Manager)</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="dueDate" class="form-label">Due Date</label>
                            <input type="datetime-local" class="form-control" id="dueDate" readonly>
                            <small class="text-muted">Auto-calculated based on SLA</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Ticket Modal -->
<div class="modal fade" id="viewTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Ticket #<span id="viewTicketId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Subject</h6>
                        <p id="viewSubject" class="fw-bold"></p>
                        
                        <h6 class="text-muted mt-3">Description</h6>
                        <p id="viewDescription"></p>
                        
                        <h6 class="text-muted mt-3">Resolution Notes</h6>
                        <div id="resolutionNotes" class="p-3 bg-light rounded">
                            <p class="text-muted fst-italic mb-0">No resolution notes available.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-4">Ticket Details</h6>
                                <div class="mb-3">
                                    <span class="text-muted d-block">Status</span>
                                    <span id="viewStatus" class="badge bg-primary">Open</span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted d-block">Priority</span>
                                    <span id="viewPriority" class="badge bg-warning">Medium</span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted d-block">Category</span>
                                    <span id="viewCategory">-</span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted d-block">Assigned To</span>
                                    <span id="viewAssignedTo">Unassigned</span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted d-block">Created</span>
                                    <span id="viewCreated">-</span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted d-block">Due Date</span>
                                    <span id="viewDueDate">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Activity Timeline -->
                <h6 class="border-bottom pb-2 mb-3">Activity Timeline</h6>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-badge bg-success"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Ticket Created</span>
                                <small class="text-muted">Just now</small>
                            </div>
                            <p class="small mb-0">Ticket was created by you</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="closeTicketBtn">
                    <i class="fas fa-lock me-1"></i> Close Ticket
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Tickets List -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ticket #</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample row - will be populated dynamically -->
                        <tr>
                            <td>#TKT-001</td>
                            <td>AC Not Working</td>
                            <td><span class="badge bg-info">Maintenance</span></td>
                            <td><span class="badge bg-warning">High</span></td>
                            <td>
                                <span class="badge bg-primary">In Progress</span>
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" title="12 hours remaining"></div>
                                </div>
                                <small class="text-muted">12h remaining</small>
                            </td>
                            <td>Henry Martey</td>
                            <td>
                                Aug 20, 2023
                                <div class="text-muted small">09:30 AM</div>
                            </td>
                            <td>
                                <div>Aug 22, 2023</div>
                                <div class="text-muted small">09:30 AM</div>
                                <span class="badge bg-success">On Track</span>
                            </td>
                            <td class="text-nowrap">
                                <button class="btn btn-sm btn-outline-primary view-ticket" title="View Details" data-bs-toggle="modal" data-bs-target="#viewTicketModal">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger close-ticket" title="Close Ticket">
                                    <i class="fas fa-lock"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">Showing 1 to 10 of 24 entries</div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SLA Calculation
            const prioritySelect = document.getElementById('priority');
            const dueDateInput = document.getElementById('dueDate');
            const slaInfo = document.getElementById('slaInfo');
            
            // Update SLA info and due date when priority changes
            if (prioritySelect) {
                prioritySelect.addEventListener('change', updateSLA);
                updateSLA(); // Initial call
            }
            
            // Form submission
            const ticketForm = document.getElementById('ticketForm');
            if (ticketForm) {
                ticketForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // In a real app, this would submit to the server
                    showToast('Ticket created successfully!', 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createTicketModal'));
                    modal.hide();
                    // Reset form
                    this.reset();
                    updateSLA();
                });
            }
            
            // Close ticket buttons
            document.querySelectorAll('.close-ticket').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to close this ticket?')) {
                        const row = this.closest('tr');
                        const statusBadge = row.querySelector('.badge.bg-primary');
                        if (statusBadge) {
                            statusBadge.textContent = 'Closed';
                            statusBadge.className = 'badge bg-secondary';
                            this.disabled = true;
                            showToast('Ticket closed successfully!', 'success');
                        }
                    }
                });
            });
            
            // View ticket buttons
            document.querySelectorAll('.view-ticket').forEach(button => {
                button.addEventListener('click', function() {
                    // In a real app, this would fetch ticket data
                    const row = this.closest('tr');
                    const ticketId = row.querySelector('td:first-child').textContent;
                    const subject = row.querySelector('td:nth-child(2)').textContent;
                    const category = row.querySelector('td:nth-child(3) .badge').textContent;
                    const priority = row.querySelector('td:nth-child(4) .badge').textContent;
                    const assignedTo = row.querySelector('td:nth-child(6)').textContent;
                    
                    // Update modal with ticket data
                    document.getElementById('viewTicketId').textContent = ticketId;
                    document.getElementById('viewSubject').textContent = subject;
                    document.getElementById('viewCategory').textContent = category;
                    document.getElementById('viewPriority').textContent = priority;
                    document.getElementById('viewAssignedTo').textContent = assignedTo;
                    
                    // Set up close ticket button in modal
                    const closeBtn = document.getElementById('closeTicketBtn');
                    closeBtn.onclick = function() {
                        if (confirm('Are you sure you want to close this ticket?')) {
                            const statusBadge = document.querySelector(`tr:has(td:contains('${ticketId}')) .badge.bg-primary`);
                            if (statusBadge) {
                                statusBadge.textContent = 'Closed';
                                statusBadge.className = 'badge bg-secondary';
                                const closeBtn = row.querySelector('.close-ticket');
                                if (closeBtn) closeBtn.disabled = true;
                                
                                const modal = bootstrap.Modal.getInstance(document.getElementById('viewTicketModal'));
                                modal.hide();
                                showToast('Ticket closed successfully!', 'success');
                            }
                        }
                    };
                });
            });
            
            function updateSLA() {
                if (!prioritySelect || !slaInfo) return;
                
                const selectedOption = prioritySelect.options[prioritySelect.selectedIndex];
                const hours = selectedOption.dataset.sla || '24';
                const now = new Date();
                const dueDate = new Date(now.getTime() + (hours * 60 * 60 * 1000));
                
                // Update SLA info
                slaInfo.textContent = `${selectedOption.text} tickets have a ${hours}-hour response time.`;
                
                // Update due date field
                if (dueDateInput) {
                    dueDateInput.value = dueDate.toISOString().slice(0, 16);
                }
            }
            
            function showToast(message, type = 'success') {
                const toastEl = document.getElementById('toast');
                if (!toastEl) return;
                
                const toastHeader = toastEl.querySelector('.toast-header');
                const toastBody = toastEl.querySelector('.toast-body');
                const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
                
                // Update toast content
                toastHeader.className = `toast-header bg-${type} text-white`;
                toastHeader.querySelector('strong').textContent = type.charAt(0).toUpperCase() + type.slice(1);
                toastBody.innerHTML = `<i class="fas fa-${icon} me-2"></i>${message}`;
                
                // Show toast
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
            
            // Auto-escalation simulation (would be server-side in production)
            function checkSLAEscalations() {
                // This would query the database for tickets approaching their SLA deadline
                console.log('Checking for tickets needing escalation...');
                // In a real app, this would be an API call to check and escalate tickets
            }
            
            // Check for escalations every 15 minutes
            setInterval(checkSLAEscalations, 15 * 60 * 1000);
        });
    </script>
    @endpush
    
    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                Operation completed successfully.
            </div>
        </div>
    </div>
