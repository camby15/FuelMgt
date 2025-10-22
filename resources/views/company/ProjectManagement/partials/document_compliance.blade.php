<!-- Document & Compliance Management -->
<div class="tab-pane fade" id="documentCompliance" role="tabpanel" aria-labelledby="document-compliance-tab">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Document & Compliance Management</h4>
            <p class="text-muted mb-0">Manage all documents and compliance requirements</p>
        </div>
        <div class="d-flex">
            <div class="input-group input-group-sm" style="width: 280px;">
                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Search documents...">
            </div>
        </div>
    </div>
    
    <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#documentRepo" role="tab">
                <i class="fas fa-folder me-1"></i> Document Repository
                <span class="badge bg-primary rounded-pill ms-1">5</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#incidentReports" role="tab">
                <i class="fas fa-file-alt me-1"></i> Incident Reports
                <span class="badge bg-danger rounded-pill ms-1">2</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#auditLogs" role="tab">
                <i class="fas fa-clipboard-list me-1"></i> Audit Logs
            </a>
        </li>
    </ul>

    <div class="tab-content p-3 border border-top-0 rounded-bottom">
        <!-- Document Repository -->
        <div class="tab-pane fade show active" id="documentRepo" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-0">Document Repository</h5>
                    <p class="text-muted mb-0 small">Manage all project documents and compliance files</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 280px;">
                            <h6 class="dropdown-header">Filter Options</h6>
                            <div class="mb-2">
                                <label class="form-label small text-muted mb-1">Document Type</label>
                                <select class="form-select form-select-sm">
                                    <option>All Types</option>
                                    <option>PDF</option>
                                    <option>Word</option>
                                    <option>Excel</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small text-muted mb-1">Status</label>
                                <select class="form-select form-select-sm">
                                    <option>All Status</option>
                                    <option>Draft</option>
                                    <option>Pending Review</option>
                                    <option>Approved</option>
                                    <option>Archived</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-sm btn-outline-secondary">Reset</button>
                                <button class="btn btn-sm btn-primary">Apply Filters</button>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-sort-amount-down me-1"></i> Sort
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#"><i class="far fa-sort-amount-up me-2"></i>Name (A-Z)</a>
                            <a class="dropdown-item" href="#"><i class="far fa-sort-amount-down me-2"></i>Name (Z-A)</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"><i class="far fa-calendar-plus me-2"></i>Date Added (Newest)</a>
                            <a class="dropdown-item" href="#"><i class="far fa-calendar-minus me-2"></i>Date Added (Oldest)</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"><i class="far fa-file-pdf me-2"></i>File Type</a>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i class="fas fa-upload me-1"></i> Upload
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40%;">Document</th>
                            <th>Category</th>
                            <th>Version</th>
                            <th>Modified</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-soft-danger text-danger rounded">
                                                <i class="far fa-file-pdf"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Employee Handbook 2023</h6>
                                        <p class="text-muted mb-0 small">2.4 MB • PDF</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-soft-primary text-primary">Policies</span></td>
                            <td><span class="badge bg-light text-dark">v2.1</span></td>
                            <td>
                                <div>Aug 15, 2023</div>
                                <small class="text-muted">by John Doe</small>
                            </td>
                            <td><span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Approved</span></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Document actions">
                                    <!-- Preview Button -->
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#previewDocumentModal" title="Preview">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    
                                    <!-- Download Button -->
                                    <a href="#" class="btn btn-outline-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    
                                    <!-- Share Button -->
                                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#shareDocumentModal" title="Share">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editDocumentModal" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteDocumentModal" title="Delete">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-soft-primary text-primary rounded">
                                                <i class="far fa-file-word"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Project Proposal Template</h6>
                                        <p class="text-muted mb-0 small">1.2 MB • DOCX</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-soft-info text-info">Templates</span></td>
                            <td><span class="badge bg-light text-dark">v1.0</span></td>
                            <td>
                                <div>Aug 10, 2023</div>
                                <small class="text-muted">by Sarah Smith</small>
                            </td>
                            <td><span class="badge bg-warning"><i class="fas fa-clock me-1"></i> Pending Review</span></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Document actions">
                                    <!-- Preview Button -->
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#previewDocumentModal" title="Preview">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    
                                    <!-- Download Button -->
                                    <a href="#" class="btn btn-outline-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    
                                    <!-- Share Button -->
                                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#shareDocumentModal" title="Share">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editDocumentModal" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteDocumentModal" title="Delete">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">2</span> of <span class="fw-semibold">24</span> entries
                    </div>
                    <nav aria-label="Page navigation">
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

        <!-- New Incident Modal -->
        <div class="modal fade" id="newIncidentModal" tabindex="-1" aria-labelledby="newIncidentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light p-3">
                        <h5 class="modal-title" id="newIncidentModalLabel">
                            <i class="fas fa-plus-circle text-primary me-2"></i> Report New Incident
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="incidentForm">
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> Please provide all required details about the incident.
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Incident Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Severity <span class="text-danger">*</span></label>
                                        <select class="form-select" required>
                                            <option value="">Select severity</option>
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="4" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Incident
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Incident Reports -->
        <div class="tab-pane fade" id="incidentReports" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-1">Incident Reports</h5>
                    <p class="text-muted mb-0">Track and manage all security and operational incidents</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newIncidentModal">
                        <i class="fas fa-plus me-1"></i> Report Incident
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <select class="form-select form-select-sm">
                        <option>All Status</option>
                        <option>Open</option>
                        <option>Investigating</option>
                        <option>Resolved</option>
                        <option>Closed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm">
                        <option>All Severity</option>
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>
                        <option>Critical</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" placeholder="Search incidents...">
                    </div>
                </div>
            </div>
            <!-- Incidents Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Reported On</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Incident 1 -->
                        <tr style="cursor: pointer;" onclick="viewIncident('INC-2023-001')">
                            <td class="fw-semibold">#INC-2023-001</td>
                            <td>Unauthorized Server Access</td>
                            <td>Aug 10, 2023</td>
                            <td><span class="badge bg-danger">Critical</span></td>
                            <td><span class="badge bg-warning">Investigating</span></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View Details" onclick="event.stopPropagation(); viewIncident('INC-2023-001')">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" title="Update Status" onclick="event.stopPropagation(); updateIncidentStatus('INC-2023-001')">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" title="Delete" onclick="event.stopPropagation(); deleteIncident('INC-2023-001')">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Incident 2 -->
                        <tr style="cursor: pointer;" onclick="viewIncident('INC-2023-002')">
                            <td class="fw-semibold">#INC-2023-002</td>
                            <td>Data Backup Failure</td>
                            <td>Aug 15, 2023</td>
                            <td><span class="badge bg-warning">High</span></td>
                            <td><span class="badge bg-info">In Progress</span></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View Details" onclick="event.stopPropagation(); viewIncident('INC-2023-002')">
                                        <i class="far fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" title="Update Status" onclick="event.stopPropagation(); updateIncidentStatus('INC-2023-002')">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" title="Delete" onclick="event.stopPropagation(); deleteIncident('INC-2023-002')">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">2</span> of <span class="fw-semibold">2</span> entries
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Audit Logs -->
        <div class="tab-pane fade" id="auditLogs" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Audit Logs</h5>
                <div>
                    <button class="btn btn-outline-secondary btn-sm me-2">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2023-08-20 14:32</td>
                            <td>Mike Roach</td>
                            <td><span class="badge bg-success">Updated</span></td>
                            <td>Document: Employee Handbook v2.1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Incident Modal -->
<div class="modal fade" id="viewIncidentModal" tabindex="-1" aria-labelledby="viewIncidentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="viewIncidentModalLabel">
                    <i class="fas fa-file-alt text-primary me-2"></i> Incident Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h4 id="incidentTitle">Unauthorized Server Access</h4>
                        <p id="incidentDescription" class="text-muted">
                            Detected unauthorized access to the production server from an external IP address.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge bg-danger me-2" id="incidentSeverity">Critical</span>
                        <span class="badge bg-warning" id="incidentStatus">Investigating</span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-3">Incident Details</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="far fa-calendar-alt me-2 text-muted"></i>
                                        <strong>Reported:</strong> <span id="incidentReportedDate">Aug 10, 2023 14:30</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="far fa-user me-2 text-muted"></i>
                                        <strong>Reporter:</strong> <span id="incidentReporter">John Doe</span>
                                    </li>
                                    <li>
                                        <i class="fas fa-id-badge me-2 text-muted"></i>
                                        <strong>ID:</strong> <span id="incidentId">INC-2023-001</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
                <button type="button" class="btn btn-warning" onclick="updateIncidentStatus($('#incidentId').text())">
                    <i class="fas fa-sync-alt me-1"></i> Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="updateStatusModalLabel">
                    <i class="fas fa-sync-alt text-warning me-2"></i> Update Incident Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateStatusForm">
                <div class="modal-body">
                    <input type="hidden" id="statusIncidentId" name="incident_id">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="incidentStatusSelect" name="status" required>
                            <option value="">Select status</option>
                            <option value="reported">Reported</option>
                            <option value="investigating">Investigating</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Update Notes</label>
                        <textarea class="form-control" id="statusNotes" name="notes" rows="3" placeholder="Add update notes..."></textarea>
                        <div class="form-text">Provide details about the status update</div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="notifyTeam" name="notify_team" checked>
                        <label class="form-check-label" for="notifyTeam">
                            Notify team members about this update
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Incident Modal -->
<div class="modal fade" id="deleteIncidentModal" tabindex="-1" aria-labelledby="deleteIncidentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title text-danger" id="deleteIncidentModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Delete Incident
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this incident? This action cannot be undone.</p>
                <p class="mb-0"><strong>Incident ID:</strong> <span id="deleteIncidentId"></span></p>
                <p class="mb-3"><strong>Title:</strong> <span id="deleteIncidentTitle"></span></p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirmDeleteCheckbox">
                    <label class="form-check-label" for="confirmDeleteCheckbox">
                        I understand that this action cannot be undone
                    </label>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteIncident" disabled>
                    <i class="far fa-trash-alt me-1"></i> Delete Permanently
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="uploadDocumentModalLabel">
                    <i class="fas fa-cloud-upload-alt text-primary me-2"></i> Upload New Document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="documentUploadForm">
                <div class="modal-body p-4">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i> 
                        All fields marked with <span class="text-danger">*</span> are required.
                    </div>
                    
                    <!-- File Upload -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Document <span class="text-danger">*</span></label>
                        <div class="border-2 border-dashed rounded p-5 text-center bg-light" id="dropzone">
                            <div class="dz-message" data-dz-message>
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h5>Drop files here or click to upload</h5>
                                <p class="text-muted mb-0">Maximum file size: 10MB. Supported formats: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</p>
                            </div>
                        </div>
                        <div id="filePreview" class="mt-3 d-none">
                            <div class="d-flex align-items-center bg-light p-3 rounded">
                                <i class="far fa-file-pdf fa-2x text-danger me-3"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="file-name fw-medium">document.pdf</span>
                                        <span class="file-size text-muted small">2.4 MB</span>
                                    </div>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-link text-danger ms-3" id="removeFile">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <!-- Document Details -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Document Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter document title" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="">Select category</option>
                                    <option>Policies</option>
                                    <option>Standard Operating Procedures</option>
                                    <option>Templates</option>
                                    <option>Reports</option>
                                    <option>Contracts</option>
                                    <option>Certifications</option>
                                </select>
                            </div>
                            
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Version <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="1.0" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select class="form-select">
                                            <option>Draft</option>
                                            <option>Pending Review</option>
                                            <option>Approved</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Details -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea class="form-control" rows="3" placeholder="Enter document description"></textarea>
                            </div>
                            
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Effective Date</label>
                                        <input type="date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Review Cycle</label>
                                        <select class="form-select">
                                            <option value="">No Review</option>
                                            <option value="1">1 Month</option>
                                            <option value="3">3 Months</option>
                                            <option value="6" selected>6 Months</option>
                                            <option value="12">1 Year</option>
                                            <option value="24">2 Years</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-0">
                                <label class="form-label fw-semibold">Tags</label>
                                <select class="form-control" multiple data-trigger name="tags[]" id="documentTags">
                                    <option value="hr">HR</option>
                                    <option value="finance">Finance</option>
                                    <option value="it">IT</option>
                                    <option value="operations">Operations</option>
                                    <option value="legal">Legal</option>
                                    <option value="compliance">Compliance</option>
                                </select>
                                <small class="text-muted">Press Enter or comma to add tags</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Access Control -->
                    <div class="card border-0 bg-light mt-3">
                        <div class="card-body p-3">
                            <h6 class="card-title mb-3">
                                <i class="fas fa-lock me-2"></i> Access Control
                            </h6>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="restrictAccess">
                                <label class="form-check-label" for="restrictAccess">Restrict access to specific teams</label>
                            </div>
                            <div id="accessControlFields" class="d-none">
                                <select class="form-select" multiple>
                                    <option>Management</option>
                                    <option>HR</option>
                                    <option>Finance</option>
                                    <option>IT</option>
                                    <option>Operations</option>
                                </select>
                                <small class="text-muted">Leave empty to allow access to all users</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i> Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Document Modal -->
<div class="modal fade" id="previewDocumentModal" tabindex="-1" aria-labelledby="previewDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="previewDocumentModalLabel">
                    <i class="far fa-file-pdf text-danger me-2"></i> Document Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe src="https://mozilla.github.io/pdf.js/web/viewer.html?file=/sample.pdf" class="w-100" style="border: none;"></iframe>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> Download
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Document Modal -->
<div class="modal fade" id="editDocumentModal" tabindex="-1" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="editDocumentModalLabel">
                    <i class="far fa-edit text-warning me-2"></i> Edit Document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Document Name</label>
                        <input type="text" class="form-control" value="Employee Handbook 2023">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3">Company employee handbook for the year 2023</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select">
                            <option>Policies</option>
                            <option>Guides</option>
                            <option>Manuals</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteDocumentModal" tabindex="-1" aria-labelledby="deleteDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title text-danger" id="deleteDocumentModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>Employee Handbook 2023</strong>? This action cannot be undone.</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirmDelete">
                    <label class="form-check-label" for="confirmDelete">
                        I understand that this will permanently delete the document
                    </label>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="far fa-trash-alt me-1"></i> Delete Permanently
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Share Document Modal -->
<div class="modal fade" id="shareDocumentModal" tabindex="-1" aria-labelledby="shareDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="shareDocumentModalLabel">
                    <i class="fas fa-share-alt text-primary me-2"></i> Share Document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Share with</label>
                    <select class="form-control" multiple data-trigger>
                        <option>John Doe (john@example.com)</option>
                        <option>Jane Smith (jane@example.com)</option>
                        <option>Mike Johnson (mike@example.com)</option>
                    </select>
                    <small class="text-muted">Start typing to search for users</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Permission</label>
                    <select class="form-select">
                        <option>Can view</option>
                        <option>Can comment</option>
                        <option>Can edit</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Message (optional)</label>
                    <textarea class="form-control" rows="2" placeholder="Add a message"></textarea>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="notifyPeople">
                    <label class="form-check-label" for="notifyPeople">
                        Notify people
                    </label>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i> Share
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Incident Actions Handlers
function viewIncident(incidentId) {
    console.log('Viewing incident:', incidentId);
    // In a real app, you would fetch incident details via AJAX
    const incident = {
        id: incidentId,
        title: 'Unauthorized Server Access',
        description: 'Detected unauthorized access to the production server from an external IP address. The attacker attempted to brute force the admin login.',
        severity: 'Critical',
        status: 'Investigating',
        reportedDate: 'Aug 10, 2023 14:30',
        reporter: 'John Doe (Admin)',
        category: 'Security Breach'
    };

    // Update modal content
    $('#incidentTitle').text(incident.title);
    $('#incidentDescription').text(incident.description);
    $('#incidentSeverity').text(incident.severity).removeClass().addClass('badge bg-danger');
    $('#incidentStatus').text(incident.status).removeClass().addClass('badge bg-warning');
    $('#incidentReportedDate').text(incident.reportedDate);
    $('#incidentReporter').text(incident.reporter);
    $('#incidentId').text(incident.id);

    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('viewIncidentModal'));
    modal.show();
}

function updateIncidentStatus(incidentId) {
    console.log('Updating status for incident:', incidentId);
    // Store the incident ID in the modal
    $('#statusIncidentId').val(incidentId);
    
    // In a real app, you would fetch current status and set it
    $('#incidentStatusSelect').val('investigating');
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

function deleteIncident(incidentId) {
    console.log('Deleting incident:', incidentId);
    // Set the incident details in the delete confirmation modal
    $('#deleteIncidentId').text(incidentId);
    $('#deleteIncidentTitle').text('Unauthorized Server Access'); // In real app, fetch the title
    
    // Store the incident ID in the modal for form submission
    $('#deleteIncidentModal').data('incident-id', incidentId);
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('deleteIncidentModal'));
    modal.show();
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Form submission handler for update status
    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault();
        
        // In a real app, you would make an AJAX call to update the status
        const formData = $(this).serialize();
        console.log('Updating incident status:', formData);
        
        // Show success message
        showToast('Status updated successfully', 'success');
        
        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('updateStatusModal'));
        modal.hide();
    });

    // Delete incident handler
    $('#confirmDeleteIncident').on('click', function() {
        const incidentId = $('#deleteIncidentModal').data('incident-id');
        
        // In a real app, you would make an AJAX call to delete the incident
        console.log('Deleting incident:', incidentId);
        
        // Show success message
        showToast('Incident deleted successfully', 'success');
        
        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteIncidentModal'));
        modal.hide();
        
        // Remove the incident row from the table
        $(`tr[onclick*="${incidentId}"]`).fadeOut(300, function() {
            $(this).remove();
        });
    });

    // Enable/disable delete button based on confirmation checkbox
    $('#confirmDeleteCheckbox').on('change', function() {
        $('#confirmDeleteIncident').prop('disabled', !this.checked);
    });

    // Reset delete confirmation when modal is hidden
    $('#deleteIncidentModal').on('hidden.bs.modal', function () {
        $('#confirmDeleteCheckbox').prop('checked', false);
        $('#confirmDeleteIncident').prop('disabled', true);
    });
});

// Helper function to show toast messages
function showToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    const $toast = $(toastHtml);
    $('#toastContainer').append($toast);
    const toast = new bootstrap.Toast($toast[0]);
    toast.show();
    
    // Remove toast after it's hidden
    $toast.on('hidden.bs.toast', function () {
        $(this).remove();
    });
}

// Incident Actions
function viewIncident(id) {
    console.log('Viewing incident:', id);
    const modal = new bootstrap.Modal(document.getElementById('viewIncidentModal'));
    modal.show();
}

function updateIncidentStatus(id) {
    console.log('Updating status for incident:', id);
    $('#statusIncidentId').val(id);
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

function deleteIncident(id) {
    console.log('Deleting incident:', id);
    $('#deleteIncidentId').text(id);
    $('#deleteIncidentModal').data('incident-id', id);
    const modal = new bootstrap.Modal(document.getElementById('deleteIncidentModal'));
    modal.show();
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    $('#confirmDeleteCheckbox').on('change', function() {
        $('#confirmDeleteIncident').prop('disabled', !this.checked);
    });

    // Delete incident
    $('#confirmDeleteIncident').on('click', function() {
        const id = $('#deleteIncidentModal').data('incident-id');
        console.log('Deleting incident:', id);
        showToast('Incident deleted successfully', 'success');
        $(`tr[onclick*="${id}"]`).fadeOut(300).remove();
        $('#deleteIncidentModal').modal('hide');
    });

    // Update status form
    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault();
        showToast('Status updated successfully', 'success');
        $('#updateStatusModal').modal('hide');
    });
});

// Helper function for toast messages
function showToast(message, type = 'info') {
    const toast = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>`;
    $('#toastContainer').append(toast);
    const bsToast = new bootstrap.Toast($('.toast').last()[0]);
    bsToast.show();
    $('.toast').on('hidden.bs.toast', function() {
        $(this).remove();
    });
}

// File upload preview
const dropzone = document.getElementById('dropzone');
const fileInput = document.createElement('input');
fileInput.type = 'file';
fileInput.className = 'd-none';
document.body.appendChild(fileInput);

// Handle file selection
dropzone.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const filePreview = document.getElementById('filePreview');
        const fileName = document.querySelector('.file-name');
        const fileSize = document.querySelector('.file-size');
        
        // Update preview
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        filePreview.classList.remove('d-none');
        
        // Simulate upload progress
        const progressBar = document.querySelector('.progress-bar');
        let progress = 0;
        const interval = setInterval(() => {
            progress += 5;
            progressBar.style.width = `${Math.min(progress, 100)}%`;
            
            if (progress >= 100) {
                clearInterval(interval);
                progressBar.classList.remove('bg-success');
                progressBar.classList.add('bg-primary');
            }
        }, 50);
    }
});

// Handle remove file
document.getElementById('removeFile')?.addEventListener('click', () => {
    const filePreview = document.getElementById('filePreview');
    filePreview.classList.add('d-none');
    fileInput.value = '';
    document.querySelector('.progress-bar').style.width = '0%';
    document.querySelector('.progress-bar').classList.remove('bg-primary');
    document.querySelector('.progress-bar').classList.add('bg-success');
});

// Toggle access control fields
document.getElementById('restrictAccess')?.addEventListener('change', function() {
    const accessControlFields = document.getElementById('accessControlFields');
    if (this.checked) {
        accessControlFields.classList.remove('d-none');
    } else {
        accessControlFields.classList.add('d-none');
    }
});

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Handle delete confirmation
const confirmDelete = document.getElementById('confirmDelete');
const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

if (confirmDelete && confirmDeleteBtn) {
    confirmDelete.addEventListener('change', function() {
        confirmDeleteBtn.disabled = !this.checked;
    });
}

// Make action buttons responsive on small screens
document.addEventListener('DOMContentLoaded', function() {
    const actionGroups = document.querySelectorAll('.btn-group[aria-label="Document actions"]');
    
    function updateButtonVisibility() {
        const isMobile = window.innerWidth < 768; // Bootstrap's md breakpoint
        
        actionGroups.forEach(group => {
            const buttons = group.querySelectorAll('.btn');
            buttons.forEach((btn, index) => {
                if (isMobile && index >= 3) { // Show only first 3 buttons on mobile
                    btn.classList.add('d-none');
                } else {
                    btn.classList.remove('d-none');
                }
            });
            
            // Add dropdown if on mobile and not already added
            if (isMobile && !group.querySelector('.dropdown-toggle')) {
                const dropdownHtml = `
                    <div class="dropdown d-inline-block ms-1">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            ${Array.from(group.querySelectorAll('.btn.d-none')).map(btn => 
                                `<li><a class="dropdown-item" href="#" data-action="${btn.getAttribute('data-bs-target')}">
                                    ${btn.innerHTML.trim()} ${btn.title ? btn.title : ''}
                                </a></li>`
                            ).join('')}
                        </ul>
                    </div>`;
                
                group.insertAdjacentHTML('beforeend', dropdownHtml);
                
                // Add click handler for dropdown items
                group.querySelector('.dropdown-menu').addEventListener('click', function(e) {
                    const target = e.target.closest('[data-action]');
                    if (target) {
                        e.preventDefault();
                        const modalId = target.getAttribute('data-action');
                        const modal = bootstrap.Modal.getOrCreateInstance(document.querySelector(modalId));
                        modal.show();
                    }
                });
            } else if (!isMobile) {
                // Remove dropdown if it exists and we're not on mobile
                const dropdown = group.querySelector('.dropdown');
                if (dropdown) {
                    dropdown.remove();
                }
            }
        });
    }
    
    // Initial check
    updateButtonVisibility();
    
    // Update on window resize
    window.addEventListener('resize', updateButtonVisibility);
});

<!-- View Incident Modal -->
<div class="modal fade" id="viewIncidentModal" tabindex="-1" aria-labelledby="viewIncidentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="viewIncidentModalLabel">
                    <i class="fas fa-file-alt text-primary me-2"></i> Incident Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h4 id="incidentTitle">Loading...</h4>
                        <p id="incidentDescription" class="text-muted"></p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span id="incidentSeverity" class="badge me-2"></span>
                        <span id="incidentStatus" class="badge"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-3">Incident Details</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="far fa-calendar-alt text-primary me-2"></i>
                                        <strong>Reported:</strong> 
                                        <span id="incidentReportedDate"></span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-user text-primary me-2"></i>
                                        <strong>Reported By:</strong> 
                                        <span id="incidentReporter"></span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-link text-primary me-2"></i>
                                        <strong>Reference ID:</strong> 
                                        <span id="incidentId"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-3">Activity Log</h6>
                                <div class="timeline" id="incidentTimeline">
                                    <!-- Timeline items will be added here by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
                <button type="button" class="btn btn-primary" onclick="updateIncidentStatus($('#incidentId').text())">
                    <i class="fas fa-sync-alt me-1"></i> Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Incident Modal -->
<div class="modal fade" id="viewIncidentModal" tabindex="-1" aria-labelledby="viewIncidentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="viewIncidentModalLabel">
                    <i class="fas fa-file-alt text-primary me-2"></i> Incident Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h4 id="incidentTitle">Unauthorized Server Access</h4>
                        <p id="incidentDescription" class="text-muted">
                            Detected unauthorized access to the production server from an unknown IP address.
                            The incident is currently under investigation by the security team.
                        </p>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body p-3">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <th>Status:</th>
                                        <td><span class="badge bg-warning">Investigating</span></td>
                                    </tr>
                                    <tr>
                                        <th>Severity:</th>
                                        <td><span class="badge bg-danger">Critical</span></td>
                                    </tr>
                                    <tr>
                                        <th>Reported:</th>
                                        <td>Aug 10, 2023</td>
                                    </tr>
                                    <tr>
                                        <th>Reported By:</th>
                                        <td>John Doe</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated:</th>
                                        <td>2 hours ago</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-comment-dots text-muted me-2"></i> Activity Log
                    </h6>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-icon bg-primary">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">Incident Created</h6>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                                <p class="mb-1 small">Incident reported by John Doe</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon bg-info">
                                <i class="fas fa-comment"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">Status Updated</h6>
                                    <small class="text-muted">1 hour ago</small>
                                </div>
                                <p class="mb-1 small">Status changed to <span class="badge bg-warning">Investigating</span></p>
                                <p class="mb-0 small text-muted">Assigned to Security Team</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="attachments">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-paperclip text-muted me-2"></i> Attachments
                    </h6>
                    <div class="d-flex gap-2">
                        <div class="border rounded p-2 text-center" style="width: 100px;">
                            <i class="far fa-file-pdf text-danger fa-2x mb-2"></i>
                            <div class="text-truncate small">server_logs.pdf</div>
                            <small class="text-muted">1.2 MB</small>
                        </div>
                        <div class="border rounded p-2 text-center" style="width: 100px;">
                            <i class="far fa-file-image text-success fa-2x mb-2"></i>
                            <div class="text-truncate small">screenshot.png</div>
                            <small class="text-muted">450 KB</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
                <button type="button" class="btn btn-primary" onclick="$('#viewIncidentModal').modal('hide'); updateIncidentStatus('INC-2023-001')">
                    <i class="fas fa-sync-alt me-1"></i> Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline:before {
    content: '';
    position: absolute;
    left: 9px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-icon {
    position: absolute;
    left: -30px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
}
.timeline-content {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 10px 15px;
    position: relative;
    margin-left: 10px;
}
</style>

<script>
// Incident Management Functions
function viewIncident(incidentId) {
    // In a real app, you would fetch incident details via AJAX
    $('#viewIncidentModal').modal('show');
}

function updateIncidentStatus(incidentId) {
    // Store the incident ID in the modal for form submission
    $('#updateStatusModal').data('incident-id', incidentId);
    $('#updateStatusModal').modal('show');
}

function deleteIncident(incidentId) {
    // Store the incident ID in the modal for form submission
    $('#deleteIncidentModal').data('incident-id', incidentId);
    $('#deleteIncidentModal').modal('show');
}

// Handle new incident form submission
$('#incidentForm').on('submit', function(e) {
    e.preventDefault();
    
    // Basic form validation
    const form = this;
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }
    
    // Here you would typically make an AJAX call to save the incident
    const formData = new FormData(form);
    console.log('Submitting incident form with data:', Object.fromEntries(formData));
    
    // Show success message
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed bottom-0 end-0 m-3';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i> Incident reported successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Close the modal and reset form
    $('#newIncidentModal').modal('hide');
    form.reset();
    form.classList.remove('was-validated');
    
    // Remove toast after hiding
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
});

// Handle update status form submission
$('#updateStatusForm').on('submit', function(e) {
    e.preventDefault();
    
    if (!this.checkValidity()) {
        e.stopPropagation();
        this.classList.add('was-validated');
        return;
    }
    
    const incidentId = $('#updateStatusModal').data('incident-id');
    const status = $(this).find('select').val();
    const notes = $(this).find('textarea').val();
    
    // Here you would typically make an AJAX call to update the status
    console.log('Updating status for incident:', incidentId, 'to', status, 'with notes:', notes);
    
    // Show success message
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed bottom-0 end-0 m-3';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i> Incident status updated successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Close the modal and reset form
    $('#updateStatusModal').modal('hide');
    this.reset();
    this.classList.remove('was-validated');
    
    // Remove toast after hiding
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
    
    // In a real app, you would refresh the incident list or update the UI
});

// Handle delete confirmation
$('#confirmDeleteIncident').on('click', function() {
    const incidentId = $('#deleteIncidentModal').data('incident-id');
    
    // Here you would typically make an AJAX call to delete the incident
    console.log('Deleting incident:', incidentId);
    
    // Show success message
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-danger border-0 position-fixed bottom-0 end-0 m-3';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-trash-alt me-2"></i> Incident deleted successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Close the modal
    $('#deleteIncidentModal').modal('hide');
    
    // Remove the incident row from the table
    $(`tr[onclick*="${incidentId}"]`).fadeOut(300, function() { 
        $(this).remove(); 
    });
    
    // Remove toast after hiding
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
});
</script>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="updateStatusModalLabel">
                    <i class="fas fa-sync-alt text-warning me-2"></i> Update Incident Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateStatusForm" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" required>
                            <option value="">Select status</option>
                            <option value="open">Open</option>
                            <option value="investigating" selected>Investigating</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a status
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Update Notes <span class="text-danger">*</span></label>
                        <textarea class="form-control" rows="3" required placeholder="Add update notes..."></textarea>
                        <div class="invalid-feedback">
                            Please provide update notes
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notifyTeam" checked>
                        <label class="form-check-label" for="notifyTeam">Notify team members</label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteIncidentModal" tabindex="-1" aria-labelledby="deleteIncidentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title text-danger" id="deleteIncidentModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this incident? This action cannot be undone.</p>
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Warning:</strong> This will permanently remove the incident record and all associated data.
                </div>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="confirmDeleteCheckbox">
                    <label class="form-check-label" for="confirmDeleteCheckbox">
                        I understand that this action cannot be undone
                    </label>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteIncident" disabled>
                    <i class="far fa-trash-alt me-1"></i> Delete Permanently
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Incident Actions Handlers
function viewIncident(incidentId) {
    // In a real app, you would fetch incident details via AJAX
    const incident = {
        id: incidentId,
        title: 'Unauthorized Server Access',
        description: 'Detected unauthorized access to the production server from an external IP address. The attacker attempted to brute force the admin login.',
        severity: 'Critical',
        status: 'Investigating',
        reportedDate: 'Aug 10, 2023 14:30',
        reporter: 'John Doe (Admin)',
        category: 'Security Breach'
    };

    // Update modal content
    $('#incidentTitle').text(incident.title);
    $('#incidentDescription').text(incident.description);
    $('#incidentSeverity').text(incident.severity).removeClass().addClass('badge bg-danger');
    $('#incidentStatus').text(incident.status).removeClass().addClass('badge bg-warning');
    $('#incidentReportedDate').text(incident.reportedDate);
    $('#incidentReporter').text(incident.reporter);
    $('#incidentId').text(incident.id);

    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('viewIncidentModal'));
    modal.show();
}

function updateIncidentStatus(incidentId) {
    // Store the incident ID in the modal
    $('#statusIncidentId').val(incidentId);
    
    // In a real app, you would fetch current status and set it
    $('#incidentStatusSelect').val('investigating');
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

function deleteIncident(incidentId) {
    // Set the incident details in the delete confirmation modal
    $('#deleteIncidentId').text(incidentId);
    $('#deleteIncidentTitle').text('Unauthorized Server Access'); // In real app, fetch the title
    
    // Store the incident ID in the modal for form submission
    $('#deleteIncidentModal').data('incident-id', incidentId);
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('deleteIncidentModal'));
    modal.show();
}

// Form submission handlers
$('#updateStatusForm').on('submit', function(e) {
    e.preventDefault();
    
    // In a real app, you would make an AJAX call to update the status
    const formData = $(this).serialize();
    console.log('Updating incident status:', formData);
    
    // Show success message
    showToast('Status updated successfully', 'success');
    
    // Close the modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('updateStatusModal'));
    modal.hide();
});

$('#confirmDeleteIncident').on('click', function() {
    const incidentId = $('#deleteIncidentModal').data('incident-id');
    
    // In a real app, you would make an AJAX call to delete the incident
    console.log('Deleting incident:', incidentId);
    
    // Show success message
    showToast('Incident deleted successfully', 'success');
    
    // Close the modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteIncidentModal'));
    modal.hide();
    
    // Remove the incident row from the table
    $(`tr[onclick*="${incidentId}"]`).fadeOut(300, function() {
        $(this).remove();
    });
});

// Enable/disable delete button based on confirmation checkbox
$('#confirmDeleteCheckbox').on('change', function() {
    $('#confirmDeleteIncident').prop('disabled', !this.checked);
});

// Reset delete confirmation when modal is hidden
$('#deleteIncidentModal').on('hidden.bs.modal', function () {
    $('#confirmDeleteCheckbox').prop('checked', false);
    $('#confirmDeleteIncident').prop('disabled', true);
});

// Helper function to show toast messages
function showToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    const $toast = $(toastHtml);
    $('#toastContainer').append($toast);
    const toast = new bootstrap.Toast($toast[0]);
    toast.show();
    
    // Remove toast after it's hidden
    $toast.on('hidden.bs.toast', function () {
        $(this).remove();
    });
}
</script>

<!-- Toast Container -->
<div id="toastContainer" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <!-- Toasts will be inserted here by JavaScript -->
</div>

<style>
/* Toast animation */
.toast {
    opacity: 1;
    transition: opacity 0.3s ease-in-out;
}

.toast.hide {
    opacity: 0;
}
</style>

@endpush
