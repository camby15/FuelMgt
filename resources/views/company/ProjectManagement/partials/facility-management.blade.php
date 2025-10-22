<!-- New Maintenance Request Modal -->
<div class="modal fade" id="newMaintenanceModal" tabindex="-1" aria-labelledby="newMaintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="newMaintenanceModalLabel">New Maintenance Request</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="maintenanceRequestForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="requestType" class="form-label">Request Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="requestType" required>
                                <option value="" selected disabled>Select type</option>
                                <option value="maintenance">Preventive Maintenance</option>
                                <option value="repair">Repair</option>
                                <option value="inspection">Inspection</option>
                                <option value="cleaning">Cleaning</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" id="priority" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <select class="form-select" id="location" required>
                                <option value="" selected disabled>Select location</option>
                                <option value="building-a">Building A</option>
                                <option value="building-b">Building B</option>
                                <option value="building-c">Building C</option>
                                <option value="building-d">Building D</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="floor" class="form-label">Floor/Room</label>
                            <input type="text" class="form-control" id="floor" placeholder="e.g., 3rd Floor, Room 302">
                        </div>
                        <div class="col-md-6">
                            <label for="dueDate" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="dueDate">
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" rows="3" required placeholder="Please describe the issue or request in detail"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input class="form-control" type="file" id="attachments" multiple>
                            <div class="form-text">You can upload images or documents (max 5MB each)</div>
                        </div>
                        <div class="col-md-6">
                            <label for="assignedTo" class="form-label">Assign To</label>
                            <select class="form-select" id="assignedTo">
                                <option value="">Unassigned</option>
                                <option value="john">John Doe (Maintenance)</option>
                                <option value="jane">Jane Smith (Electrician)</option>
                                <option value="mike">Mike Johnson (Plumber)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="estimatedTime" class="form-label">Estimated Time (hours)</label>
                            <input type="number" class="form-control" id="estimatedTime" min="0.5" step="0.5" placeholder="e.g., 2.5">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 50%;
        transition: all 0.2s;
    }
    .btn-icon:hover {
        transform: translateY(-2px);
    }
    .dropdown-menu {
        min-width: 200px;
    }
    .dropdown-item {
        padding: 0.5rem 1rem;
    }
    .dropdown-item i {
        width: 20px;
        text-align: center;
        margin-right: 8px;
    }
    .modal-header {
        padding: 1rem 1.5rem;
    }
    .modal-title {
        font-weight: 500;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-footer {
        padding: 1rem 1.5rem;
        background-color: #f9f9f9;
        border-top: 1px solid #eee;
    }
    .avatar-xs {
        width: 32px;
        height: 32px;
        font-size: 0.75rem;
    }
</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Facility Management</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMaintenanceModal">
            <i class="fas fa-plus me-1"></i> New Request
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#REQ-1001</td>
                        <td><span class="badge bg-info">Maintenance</span></td>
                        <td>Building A - Floor 3</td>
                        <td><span class="badge bg-warning">In Progress</span></td>
                        <td>Henry Martey</td>
                        <td>Jun 15, 2023</td>
                        <td class="text-nowrap">
                            <button class="btn btn-icon btn-sm btn-outline-primary me-1" title="View Details" data-bs-toggle="modal" data-bs-target="#viewRequestModal1">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-success me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#editRequestModal1">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-danger me-1" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteRequestModal1">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-secondary" title="More Actions" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>Export PDF</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-flag me-2"></i>Report Issue</a></li>
                            </ul>
                        </td>
                        
                        <!-- View Request Modal -->
                        <div class="modal fade" id="viewRequestModal1" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Request Details #REQ-1001</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Request Information</h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Type:</span>
                                                <span class="fw-medium">Maintenance</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Priority:</span>
                                                <span class="badge bg-warning">Medium</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Status:</span>
                                                <span class="badge bg-info">In Progress</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Created:</span>
                                                <span>Jun 1, 2023</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Due Date:</span>
                                                <span>Jun 15, 2023</span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Location</h6>
                                            <p>Building A - Floor 3, Room 302</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Description</h6>
                                            <p>AC unit in the conference room is not cooling properly. Needs maintenance check.</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Assigned To</h6>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">HM</span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">Henry Martey</h6>
                                                    <small class="text-muted">Maintenance Team</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Print Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Request Modal -->
                        <div class="modal fade" id="editRequestModal1" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Edit Request #REQ-1001</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select class="form-select">
                                                    <option>Pending</option>
                                                    <option selected>In Progress</option>
                                                    <option>On Hold</option>
                                                    <option>Completed</option>
                                                    <option>Cancelled</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Priority</label>
                                                <select class="form-select">
                                                    <option>Low</option>
                                                    <option selected>Medium</option>
                                                    <option>High</option>
                                                    <option>Critical</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Assigned To</label>
                                                <select class="form-select">
                                                    <option>Unassigned</option>
                                                    <option selected>Henry Martey</option>
                                                    <option>Patrick Asare</option>
                                                    <option>Dzifa Mike</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Notes</label>
                                                <textarea class="form-control" rows="3" placeholder="Add update notes..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteRequestModal1" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center py-4">
                                        <div class="text-center mb-4">
                                            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                                        </div>
                                        <h5>Are you sure?</h5>
                                        <p class="text-muted">You are about to delete request #REQ-1001. This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer justify-content-center border-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger">Delete Request</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                    <tr>
                        <td>#REQ-1002</td>
                        <td><span class="badge bg-danger">Repair</span></td>
                        <td>Building B - Lobby</td>
                        <td><span class="badge bg-secondary">Pending</span></td>
                        <td>Patrick Asare</td>
                        <td>Jun 10, 2023</td>
                        <td class="text-nowrap">
                            <button class="btn btn-icon btn-sm btn-outline-primary me-1" title="View Details" data-bs-toggle="modal" data-bs-target="#viewRequestModal1">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-success me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#editRequestModal1">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-danger me-1" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteRequestModal1">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-secondary" title="More Actions" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>Export PDF</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-flag me-2"></i>Report Issue</a></li>
                            </ul>
                        </td>
                        
                        <!-- View Request Modal -->
                        <div class="modal fade" id="viewRequestModal1" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Request Details #REQ-1001</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Request Information</h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Type:</span>
                                                <span class="fw-medium">Maintenance</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Priority:</span>
                                                <span class="badge bg-warning">Medium</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Status:</span>
                                                <span class="badge bg-info">In Progress</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Created:</span>
                                                <span>Jun 1, 2023</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Due Date:</span>
                                                <span>Jun 15, 2023</span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Location</h6>
                                            <p>Building A - Floor 3, Room 302</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Description</h6>
                                            <p>AC unit in the conference room is not cooling properly. Needs maintenance check.</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Assigned To</h6>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">JD</span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">Henry Martey</h6>
                                                    <small class="text-muted">Maintenance Team</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Print Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Request Modal -->
                        <div class="modal fade" id="editRequestModal1" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Edit Request #REQ-1001</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select class="form-select">
                                                    <option>Pending</option>
                                                    <option selected>In Progress</option>
                                                    <option>On Hold</option>
                                                    <option>Completed</option>
                                                    <option>Cancelled</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Priority</label>
                                                <select class="form-select">
                                                    <option>Low</option>
                                                    <option selected>Medium</option>
                                                    <option>High</option>
                                                    <option>Critical</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Assigned To</label>
                                                <select class="form-select">
                                                    <option>Unassigned</option>
                                                    <option selected>Henry Martey</option>
                                                    <option>Patrick Asare</option>
                                                    <option>Dzifa Mike</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Notes</label>
                                                <textarea class="form-control" rows="3" placeholder="Add update notes..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteRequestModal1" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center py-4">
                                        <div class="text-center mb-4">
                                            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                                        </div>
                                        <h5>Are you sure?</h5>
                                        <p class="text-muted">You are about to delete request #REQ-1001. This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer justify-content-center border-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger">Delete Request</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
