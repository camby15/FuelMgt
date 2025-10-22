 
 
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="header-title">Training & Development</h4>
        <p class="text-muted">Manage employee training programs and development resources</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addResourceModal">
            <i class="fas fa-plus me-1"></i> Add Resource
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTrainingModal">
            <i class="fas fa-plus me-1"></i> Create Training
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Total Programs Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-primary text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-graduation-cap fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Total Programs</h6>
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="mb-0 fw-bold" id="totalPrograms">0</h2>
                        <span class="badge bg-white-20 rounded-pill px-2 py-1">
                            <i class="fas fa-arrow-up me-1"></i> 12.5%
                        </span>
                    </div>
                    <div class="mt-auto pt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small opacity-75">This month</span>
                            <i class="fas fa-chevron-right small opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Programs Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-success text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-play-circle fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Active Programs</h6>
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="mb-0 fw-bold" id="activePrograms">0</h2>
                        <span class="badge bg-white-20 rounded-pill px-2 py-1">
                            <i class="fas fa-arrow-up me-1"></i> 8.2%
                        </span>
                    </div>
                    <div class="mt-auto pt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small opacity-75">Currently running</span>
                            <i class="fas fa-chevron-right small opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Programs Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-info text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Completed</h6>
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="mb-0 fw-bold" id="completedPrograms">0</h2>
                        <span class="badge bg-white-20 rounded-pill px-2 py-1">
                            <i class="fas fa-arrow-up me-1"></i> 15.3%
                        </span>
                    </div>
                    <div class="mt-auto pt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small opacity-75">This quarter</span>
                            <i class="fas fa-chevron-right small opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Participants Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-warning text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Participants</h6>
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="mb-0 fw-bold" id="totalParticipants">0</h2>
                        <span class="badge bg-white-20 rounded-pill px-2 py-1">
                            <i class="fas fa-arrow-up me-1"></i> 22.1%
                        </span>
                    </div>
                    <div class="mt-auto pt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small opacity-75">Total enrolled</span>
                            <i class="fas fa-chevron-right small opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Training Programs Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Training Programs</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-centered mb-0">
                <thead>
                    <tr>
                        <th>Training Title</th>
                        <th>Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Participants</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="trainingTableBody">
                    <!-- Training programs will be loaded dynamically -->
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading training programs...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Training Resources Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Training Resources</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-centered mb-0">
                <thead>
                    <tr>
                        <th>Resource Name</th>
                        <th>Type</th>
                        <th>Uploaded On</th>
                        <th>Access Level</th>
                        <th>Size</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="resourcesTableBody">
                    <!-- Resources will be loaded dynamically -->
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading training resources...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Training Modal -->
<div class="modal fade" id="createTrainingModal" tabindex="-1" aria-labelledby="createTrainingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTrainingModalLabel">Create New Training Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createTrainingForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="trainingTitle" name="title" placeholder="Training Title" required>
                                <label for="trainingTitle">Training Title</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="trainingType" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="workshop">Workshop</option>
                                    <option value="online">Online Course</option>
                                    <option value="seminar">Seminar</option>
                                    <option value="course">Course</option>
                                    <option value="certification">Certification</option>
                                </select>
                                <label for="trainingType">Training Type</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="startDate" name="start_date" min="<?php echo date('Y-m-d'); ?>" required>
                                <label for="startDate">Start Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="endDate" name="end_date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                <label for="endDate">End Date</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="trainingDescription" name="description" style="height: 100px;" placeholder="Description" required></textarea>
                        <label for="trainingDescription">Description</label>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="trainingInstructor" name="instructor" placeholder="Instructor/Provider" required>
                                <label for="trainingInstructor">Instructor/Provider</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="participantCount" name="participant_count" placeholder="Expected Participants" min="1" required>
                                <label for="participantCount">Expected Participants</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Training Audience</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="audienceType" id="allEmployees" value="all" checked>
                            <label class="form-check-label" for="allEmployees">
                                All Employees
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="audienceType" id="specificDepartment" value="department">
                            <label class="form-check-label" for="specificDepartment">
                                Specific Department(s)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="audienceType" id="specificEmployees" value="employees">
                            <label class="form-check-label" for="specificEmployees">
                                Specific Employee(s)
                            </label>
                        </div>
                        
                        <div id="audienceSelection" class="mt-2" style="display: none;">
                            <select class="form-select" id="audienceSelect" name="audience" multiple>
                                <!-- Options will be populated by JavaScript -->
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="trainingLocation" name="location" placeholder="e.g., Conference Room A, Zoom, LMS, etc.">
                                <label for="trainingLocation">Location/Platform</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="trainingStatus" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="planned">Planned</option>
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <label for="trainingStatus">Status</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="trainingMaterials" class="form-label">Training Materials</label>
                        <div class="dropzone" id="trainingMaterials"></div>
                        <small class="text-muted">Upload relevant documents, presentations, or links</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="createTrainingForm" class="btn btn-primary">Create Training</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Resource Modal -->
<div class="modal fade" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResourceModalLabel">Add Training Resource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addResourceForm">
                    @csrf
                    <div class="mb-3">
                        <label for="resourceName" class="form-label">Resource Name</label>
                        <input type="text" class="form-control" id="resourceName" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Resource Type</label>
                        <select class="form-select" id="resourceType" name="type" required>
                            <option value="">Select Type</option>
                            <option value="document">Document</option>
                            <option value="video">Video</option>
                            <option value="presentation">Presentation</option>
                            <option value="link">Web Link</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload Method</label>
                        <ul class="nav nav-tabs mb-3" id="resourceTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-pane" type="button" role="tab">
                                    <i class="fas fa-upload me-1"></i> Upload File
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="link-tab" data-bs-toggle="tab" data-bs-target="#link-pane" type="button" role="tab">
                                    <i class="fas fa-link me-1"></i> Web Link
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="resourceTabContent">
                            <div class="tab-pane fade show active" id="upload-pane" role="tabpanel" aria-labelledby="upload-tab">
                                <div class="dropzone" id="resourceFile"></div>
                                <small class="text-muted">Accepted formats: PDF, DOCX, XLSX, PPT, MP4, etc. (Max 50MB)</small>
                            </div>
                            <div class="tab-pane fade" id="link-pane" role="tabpanel" aria-labelledby="link-tab">
                                <input type="url" class="form-control mt-2" id="resourceLink" name="url" placeholder="https://example.com/resource">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resourceDescription" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="resourceDescription" name="description" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Access Control</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="resourceAccess" id="accessPublic" value="public" checked>
                            <label class="form-check-label" for="accessPublic">
                                All Employees
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="resourceAccess" id="accessDepartment" value="department">
                            <label class="form-check-label" for="accessDepartment">
                                Specific Department(s)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="resourceAccess" id="accessRole" value="role">
                            <label class="form-check-label" for="accessRole">
                                Specific Role(s)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="resourceAccess" id="accessPrivate" value="private">
                            <label class="form-check-label" for="accessPrivate">
                                Specific Employee(s)
                            </label>
                        </div>
                        
                        <div id="accessSelectionContainer" class="mt-2 d-none">
                            <label id="accessTypeLabel" class="form-label">Select Access</label>
                            <select class="form-select" id="resourceAccessValue" name="access_values" multiple>
                                <!-- Options will be populated by JavaScript -->
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="setExpiration">
                            <label class="form-check-label" for="setExpiration">
                                Set expiration date
                            </label>
                        </div>
                        <div id="expirationDateContainer" class="mt-2 d-none">
                            <label for="expirationDate" class="form-label">Expiration Date</label>
                            <input type="date" class="form-control" id="expirationDate" name="expiration_date">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addResourceForm" class="btn btn-primary">Add Resource</button>
            </div>
        </div>
    </div>
</div>

<!-- View Resource Modal -->
<div class="modal fade" id="viewResourceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewResourceTitle">Resource Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewResourceBody">
                <!-- Content will be populated dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="downloadResourceBtn">
                    <i class="fas fa-download me-1"></i> Download
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Share Resource Modal -->
<div class="modal fade" id="shareResourceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareResourceTitle">Share Resource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Share Link</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="shareLinkInput" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="copyLinkBtn">
                            <i class="far fa-copy"></i>
                        </button>
                    </div>
                    <small class="text-muted">Anyone with this link can access the resource.</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Share via Email</label>
                    <div class="input-group mb-2">
                        <input type="email" class="form-control" placeholder="Enter email addresses" id="shareEmailInput">
                        <button class="btn btn-outline-secondary" type="button" id="sendEmailBtn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <small class="text-muted">Separate multiple emails with commas</small>
                </div>
                
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="allowDownload" checked>
                    <label class="form-check-label" for="allowDownload">Allow download</label>
                </div>
                
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="setExpiration">
                    <label class="form-check-label" for="setExpiration">Set expiration date</label>
                </div>
                
                <div class="mb-3" id="expirationDateContainer" style="display: none;">
                    <input type="date" class="form-control" id="expirationDate">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveShareSettingsBtn">Save Changes</button>
            </div>
        </div>
    </div>
                </div>
                

<!-- Edit Resource Modal -->
<div class="modal fade" id="editResourceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editResourceTitle">Edit Resource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editResourceForm">
                    <div class="mb-3">
                        <label for="editResourceName" class="form-label">Resource Name</label>
                        <input type="text" class="form-control" id="editResourceName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editResourceDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editResourceDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Access Control</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="editResourceAccess" id="editAccessPublic" value="public">
                            <label class="form-check-label" for="editAccessPublic">
                                Public (All employees)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="editResourceAccess" id="editAccessDepartment" value="department">
                            <label class="form-check-label" for="editAccessDepartment">
                                Specific Department
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="editResourceAccess" id="editAccessRole" value="role">
                            <label class="form-check-label" for="editAccessRole">
                                Specific Role
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="editResourceAccess" id="editAccessPrivate" value="private">
                            <label class="form-check-label" for="editAccessPrivate">
                                Private (Specific Employees)
                            </label>
                        </div>
                    </div>
                    <div class="mb-3" id="editAccessValueContainer" style="display: none;">
                        <label for="editResourceAccessValue" class="form-label" id="editAccessTypeLabel">
                            Select
                        </label>
                        <select class="form-select select2" id="editResourceAccessValue" multiple>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editResourceExpiration" class="form-label">Expiration Date (Optional)</label>
                        <input type="date" class="form-control" id="editResourceExpiration">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger me-auto" data-bs-dismiss="modal" id="deleteResourceBtn">
                    <i class="fas fa-trash-alt me-1"></i> Delete
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveResourceBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>


@push('styles')
    <!-- Dropzone CSS -->
    <link href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('script')
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Initialize components when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎓 Training: DOM Content Loaded - Scripts initialized!');
            
            // Training Management Class
            class TrainingManagement {
                constructor() {
                    this.baseUrl = '/company/hr/training';
                    this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    this.trainings = [];
                    this.resources = [];
                    this.init();
                }

                init() {
                    this.loadTrainingStats();
                    this.loadTrainingPrograms();
                    this.loadTrainingResources();
                    this.bindEvents();
                }

                bindEvents() {
                    // Create Training form submission
                    const createTrainingForm = document.getElementById('createTrainingForm');
                    if (createTrainingForm) {
                        createTrainingForm.addEventListener('submit', (e) => this.handleCreateTraining(e));
                    }

                    // Add Resource form submission
                    const addResourceForm = document.getElementById('addResourceForm');
                    if (addResourceForm) {
                        addResourceForm.addEventListener('submit', (e) => this.handleAddResource(e));
                    }

                    // Filter buttons
                    document.addEventListener('click', (e) => {
                        if (e.target.closest('[data-filter]')) {
                            const filter = e.target.closest('[data-filter]').dataset.filter;
                            this.filterTrainings(filter);
                        }
                    });

                    // Access control radio buttons
                    document.addEventListener('change', (e) => {
                        if (e.target.name === 'resourceAccess') {
                            this.updateAccessSelection(e.target.value);
                        }
                    });

                    // Expiration date checkbox
                    document.addEventListener('change', (e) => {
                        if (e.target.id === 'setExpiration') {
                            const container = document.getElementById('expirationDateContainer');
                            if (e.target.checked) {
                                container.classList.remove('d-none');
                            } else {
                                container.classList.add('d-none');
                            }
                        }
                    });

                    // Search functionality
                    const searchInput = document.getElementById('trainingSearch');
                    if (searchInput) {
                        let searchTimeout;
                        searchInput.addEventListener('input', (e) => {
                            clearTimeout(searchTimeout);
                            searchTimeout = setTimeout(() => {
                                this.searchTrainings(e.target.value);
                            }, 500);
                        });
                    }

                    // Training and Resource action buttons
                    document.addEventListener('click', (e) => {
                        // Training actions
                        if (e.target.closest('[data-action="view-training"]')) {
                            const trainingId = parseInt(e.target.closest('[data-action="view-training"]').dataset.trainingId);
                            this.viewTraining(trainingId);
                        } else if (e.target.closest('[data-action="edit-training"]')) {
                            const trainingId = parseInt(e.target.closest('[data-action="edit-training"]').dataset.trainingId);
                            this.editTraining(trainingId);
                        } else if (e.target.closest('[data-action="delete-training"]')) {
                            const trainingId = parseInt(e.target.closest('[data-action="delete-training"]').dataset.trainingId);
                            this.deleteTraining(trainingId);
                        }
                        // Resource actions
                        else if (e.target.closest('[data-action="view-resource"]')) {
                            const resourceId = parseInt(e.target.closest('[data-action="view-resource"]').dataset.resourceId);
                            this.viewResource(resourceId);
                        } else if (e.target.closest('[data-action="edit-resource"]')) {
                            const resourceId = parseInt(e.target.closest('[data-action="edit-resource"]').dataset.resourceId);
                            this.editResource(resourceId);
                        } else if (e.target.closest('[data-action="delete-resource"]')) {
                            const resourceId = parseInt(e.target.closest('[data-action="delete-resource"]').dataset.resourceId);
                            this.deleteResource(resourceId);
                        }
                    });
                }

                loadTrainingStats() {
                    console.log('📊 Loading training statistics...');
                    
                    // Calculate stats from local data
                    const stats = {
                        total_programs: this.trainings.length,
                        active_programs: this.trainings.filter(t => t.status === 'active').length,
                        completed_programs: this.trainings.filter(t => t.status === 'completed').length,
                        total_participants: this.trainings.reduce((sum, t) => sum + t.participant_count, 0)
                    };
                    
                    this.updateStatsCards(stats);
                }

                loadTrainingPrograms(filters = {}) {
                    console.log('📋 Loading training programs...');
                    
                    // Show loading state
                    this.showTableLoading('trainingTableBody', 'Loading training programs...');
                    
                    // Simulate API call delay
                    setTimeout(() => {
                        let filteredTrainings = [...this.trainings];
                        
                        // Apply filters
                        if (filters.status) {
                            filteredTrainings = filteredTrainings.filter(t => t.status === filters.status);
                        }
                        if (filters.search) {
                            const searchTerm = filters.search.toLowerCase();
                            filteredTrainings = filteredTrainings.filter(t => 
                                t.title.toLowerCase().includes(searchTerm) ||
                                t.description.toLowerCase().includes(searchTerm)
                            );
                        }
                        
                        this.updateTrainingTable(filteredTrainings);
                    }, 500);
                }

                loadTrainingResources() {
                    console.log('📚 Loading training resources...');
                    
                    // Show loading state
                    this.showTableLoading('resourcesTableBody', 'Loading training resources...', 6);
                    
                    // Simulate API call delay
                    setTimeout(() => {
                        this.updateResourcesTable(this.resources);
                    }, 500);
                }

                updateStatsCards(stats) {
                    // Update total programs
                    const totalPrograms = document.getElementById('totalPrograms');
                    if (totalPrograms) {
                        totalPrograms.textContent = stats.total_programs || 0;
                    }

                    // Update active programs
                    const activePrograms = document.getElementById('activePrograms');
                    if (activePrograms) {
                        activePrograms.textContent = stats.active_programs || 0;
                    }

                    // Update completed programs
                    const completedPrograms = document.getElementById('completedPrograms');
                    if (completedPrograms) {
                        completedPrograms.textContent = stats.completed_programs || 0;
                    }

                    // Update total participants
                    const totalParticipants = document.getElementById('totalParticipants');
                    if (totalParticipants) {
                        totalParticipants.textContent = stats.total_participants || 0;
                    }
                }

                updateTrainingTable(trainings) {
                    const tbody = document.getElementById('trainingTableBody');
                    if (!tbody) return;

                    tbody.innerHTML = '';

                    if (trainings.length === 0) {
                        this.showEmptyTable('trainingTableBody', 'No training programs found', 'fas fa-graduation-cap');
                        return;
                    }

                    trainings.forEach(training => {
                        const row = this.createTrainingRow(training);
                        tbody.appendChild(row);
                    });
                }

                updateResourcesTable(resources) {
                    const tbody = document.getElementById('resourcesTableBody');
                    if (!tbody) return;

                    tbody.innerHTML = '';

                    if (resources.length === 0) {
                        this.showEmptyTable('resourcesTableBody', 'No training resources available', 'fas fa-file-alt', 6);
                        return;
                    }

                    resources.forEach(resource => {
                        const row = this.createResourceRow(resource);
                        tbody.appendChild(row);
                    });
                }

                showTableLoading(tableBodyId, message, colSpan = 7) {
                    const tbody = document.getElementById(tableBodyId);
                    if (!tbody) return;
                    
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="${colSpan}" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted mb-0">${message}</p>
                            </td>
                        </tr>
                    `;
                }

                showTableError(tableBodyId, message, colSpan = 7) {
                    const tbody = document.getElementById(tableBodyId);
                    if (!tbody) return;
                    
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="${colSpan}" class="text-center py-4">
                                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                                <p class="mt-2 text-muted mb-0">${message}</p>
                                <button class="btn btn-sm btn-outline-primary mt-2" onclick="trainingManagement.loadTrainingPrograms()">
                                    <i class="fas fa-redo me-1"></i> Retry
                                </button>
                            </td>
                        </tr>
                    `;
                }

                showEmptyTable(tableBodyId, message, iconClass = 'fas fa-inbox', colSpan = 7) {
                    const tbody = document.getElementById(tableBodyId);
                    if (!tbody) return;
                    
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="${colSpan}" class="text-center py-5">
                                <i class="${iconClass} text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                                <p class="mt-3 text-muted mb-0">${message}</p>
                            </td>
                        </tr>
                    `;
                }

                createTrainingRow(training) {
                    const row = document.createElement('tr');
                    
                    row.innerHTML = `
                        <td>${training.title}</td>
                        <td><span class="badge ${this.getTypeBadgeClass(training.type)}">${this.getTypeLabel(training.type)}</span></td>
                        <td>${this.formatDate(training.start_date)}</td>
                        <td>${this.formatDate(training.end_date)}</td>
                        <td>${training.participant_count}</td>
                        <td><span class="badge ${this.getStatusBadgeClass(training.status)}">${this.getStatusLabel(training.status)}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-action="view-training" data-training-id="${training.id}" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" data-action="edit-training" data-training-id="${training.id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" data-action="delete-training" data-training-id="${training.id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    return row;
                }

                createResourceRow(resource) {
                    const row = document.createElement('tr');
                    
                    row.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas ${this.getResourceIcon(resource.type)} me-2 text-muted"></i>
                                <div>
                                    <h6 class="mb-0">${resource.name}</h6>
                                    ${resource.description ? `<small class="text-muted">${resource.description}</small>` : ''}
                                </div>
                            </div>
                        </td>
                        <td><span class="badge ${this.getResourceTypeBadgeClass(resource.type)}">${this.getResourceTypeLabel(resource.type)}</span></td>
                        <td>${this.formatDate(resource.uploaded_on)}</td>
                        <td><span class="badge ${this.getAccessBadgeClass(resource.access_type)}">${this.getAccessLabel(resource.access_type)}</span></td>
                        <td>${resource.size || 'N/A'}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-action="view-resource" data-resource-id="${resource.id}" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" data-action="edit-resource" data-resource-id="${resource.id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" data-action="delete-resource" data-resource-id="${resource.id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    return row;
                }

                getTypeLabel(type) {
                    const types = {
                        'workshop': 'Workshop',
                        'seminar': 'Seminar',
                        'course': 'Course',
                        'certification': 'Certification',
                        'online': 'Online'
                    };
                    return types[type] || type;
                }

                getTypeBadgeClass(type) {
                    const classes = {
                        'workshop': 'bg-primary bg-opacity-10 text-primary',
                        'seminar': 'bg-info bg-opacity-10 text-info',
                        'course': 'bg-success bg-opacity-10 text-success',
                        'certification': 'bg-warning bg-opacity-10 text-warning',
                        'online': 'bg-purple bg-opacity-10 text-purple'
                    };
                    return classes[type] || 'bg-secondary bg-opacity-10 text-secondary';
                }

                getStatusLabel(status) {
                    const statuses = {
                        'planned': 'Planned',
                        'active': 'Active',
                        'completed': 'Completed',
                        'cancelled': 'Cancelled'
                    };
                    return statuses[status] || status;
                }

                getStatusBadgeClass(status) {
                    const classes = {
                        'planned': 'bg-warning bg-opacity-10 text-warning',
                        'active': 'bg-success bg-opacity-10 text-success',
                        'completed': 'bg-primary bg-opacity-10 text-primary',
                        'cancelled': 'bg-danger bg-opacity-10 text-danger'
                    };
                    return classes[status] || 'bg-secondary bg-opacity-10 text-secondary';
                }

                getResourceIcon(type) {
                    const icons = {
                        'document': 'fa-file-pdf',
                        'video': 'fa-video',
                        'presentation': 'fa-presentation',
                        'link': 'fa-link',
                        'other': 'fa-file'
                    };
                    return icons[type] || 'fa-file';
                }

                getResourceTypeLabel(type) {
                    const types = {
                        'document': 'Document',
                        'video': 'Video',
                        'presentation': 'Presentation',
                        'link': 'Web Link',
                        'other': 'Other'
                    };
                    return types[type] || type;
                }

                getResourceTypeBadgeClass(type) {
                    const classes = {
                        'document': 'bg-primary bg-opacity-10 text-primary',
                        'video': 'bg-danger bg-opacity-10 text-danger',
                        'presentation': 'bg-info bg-opacity-10 text-info',
                        'link': 'bg-success bg-opacity-10 text-success',
                        'other': 'bg-secondary bg-opacity-10 text-secondary'
                    };
                    return classes[type] || 'bg-secondary bg-opacity-10 text-secondary';
                }

                getAccessLabel(accessType) {
                    const access = {
                        'public': 'Public',
                        'department': 'Department',
                        'role': 'Role',
                        'private': 'Private'
                    };
                    return access[accessType] || accessType;
                }

                getAccessBadgeClass(accessType) {
                    const classes = {
                        'public': 'bg-success bg-opacity-10 text-success',
                        'department': 'bg-info bg-opacity-10 text-info',
                        'role': 'bg-warning bg-opacity-10 text-warning',
                        'private': 'bg-danger bg-opacity-10 text-danger'
                    };
                    return classes[accessType] || 'bg-secondary bg-opacity-10 text-secondary';
                }

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }

                filterTrainings(filter) {
                    const filters = {};
                    if (filter !== 'all') {
                        filters.status = filter;
                    }
                    this.loadTrainingPrograms(filters);
                }

                searchTrainings(searchTerm) {
                    const filters = {};
                    if (searchTerm) {
                        filters.search = searchTerm;
                    }
                    this.loadTrainingPrograms(filters);
                }

                handleCreateTraining(e) {
                    e.preventDefault();
                    const isEditing = this.editingTrainingId !== undefined;
                    console.log(isEditing ? '✏️ Editing training program...' : '🎓 Creating training program...');
                    
                    // Client-side validation
                    const startDate = document.getElementById('startDate').value;
                    const endDate = document.getElementById('endDate').value;
                    const today = new Date().toISOString().split('T')[0];
                    
                    if (startDate < today) {
                        this.showToast('Start date cannot be in the past. Please select a future date.', 'error');
                        return;
                    }
                    
                    if (endDate <= startDate) {
                        this.showToast('End date must be after the start date.', 'error');
                        return;
                    }
                    
                    const formData = new FormData(e.target);
                    
                    // Debug: Log all form data
                    console.log('📋 Form Data being sent:');
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }
                    
                    // Add audience data based on selection
                    const audienceType = document.querySelector('input[name="audienceType"]:checked');
                    if (audienceType && audienceType.value !== 'all') {
                        const audienceSelect = document.getElementById('audienceSelect');
                        if (audienceSelect) {
                            const selectedOptions = Array.from(audienceSelect.selectedOptions).map(option => option.value);
                            formData.append('audience_type', audienceType.value);
                            formData.append('audience_values', JSON.stringify(selectedOptions));
                        }
                    } else {
                        formData.append('audience_type', 'all');
                    }
                    
                    // Find the submit button by form attribute since it's outside the form
                    const submitBtn = document.querySelector('button[form="createTrainingForm"]');
                    
                    if (!submitBtn) {
                        console.error('Submit button not found');
                        this.showToast('Submit button not found', 'error');
                        return;
                    }
                    
                    const originalBtnText = submitBtn.innerHTML;
                    
                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> ${isEditing ? 'Updating...' : 'Creating...'}`;
                    
                    // Simulate API call
                    setTimeout(() => {
                        const trainingData = {
                            id: isEditing ? this.editingTrainingId : Date.now(),
                            title: formData.get('title'),
                            type: formData.get('type'),
                            start_date: formData.get('start_date'),
                            end_date: formData.get('end_date'),
                            participant_count: parseInt(formData.get('participant_count')),
                            instructor: formData.get('instructor'),
                            location: formData.get('location'),
                            status: formData.get('status'),
                            description: formData.get('description')
                        };
                        
                        if (isEditing) {
                            // Update existing training
                            const index = this.trainings.findIndex(t => t.id === this.editingTrainingId);
                            if (index !== -1) {
                                this.trainings[index] = trainingData;
                            }
                            this.editingTrainingId = undefined;
                            this.showToast('Training program updated successfully!', 'success');
                        } else {
                            // Add new training
                            this.trainings.push(trainingData);
                            this.showToast('Training program created successfully!', 'success');
                        }
                        
                        e.target.reset();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createTrainingModal'));
                        if (modal) modal.hide();
                        
                        // Reset modal title
                        document.getElementById('createTrainingModalLabel').textContent = 'Create New Training Program';
                        
                        this.loadTrainingPrograms();
                        this.loadTrainingStats(); // Update stats
                        
                        // Reset button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }, 1000);
                }

                handleAddResource(e) {
                    e.preventDefault();
                    const isEditing = this.editingResourceId !== undefined;
                    console.log(isEditing ? '✏️ Editing training resource...' : '📚 Adding training resource...');
                    
                    const formData = new FormData(e.target);
                    
                    // Add access type
                    const accessType = document.querySelector('input[name="resourceAccess"]:checked');
                    if (accessType) {
                        formData.append('access_type', accessType.value);
                    }
                    
                    // Add file or URL based on active tab
                    const activeTab = document.querySelector('#resourceTabs .nav-link.active');
                    if (activeTab && activeTab.id === 'link-tab') {
                        const url = document.getElementById('resourceLink').value;
                        if (url) {
                            formData.append('url', url);
                        }
                    }
                    
                    // Add expiration date if set
                    const setExpiration = document.getElementById('setExpiration').checked;
                    if (setExpiration) {
                        const expirationDate = document.getElementById('expirationDate').value;
                        if (expirationDate) {
                            formData.append('expiration_date', expirationDate);
                        }
                    }
                    
                    // Find the submit button
                    const submitBtn = document.querySelector('button[form="addResourceForm"]');
                    if (!submitBtn) {
                        this.showToast('Submit button not found', 'error');
                        return;
                    }
                    
                    const originalBtnText = submitBtn.innerHTML;
                    
                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> ${isEditing ? 'Updating...' : 'Adding...'}`;
                    
                    // Add/Update resource (simulating API call)
                    setTimeout(() => {
                        const resourceData = {
                            id: isEditing ? this.editingResourceId : Date.now(),
                            name: formData.get('name'),
                            type: formData.get('type'),
                            description: formData.get('description') || '',
                            access_type: formData.get('access_type') || 'public',
                            url: formData.get('url') || '',
                            uploaded_on: isEditing ? this.resources.find(r => r.id === this.editingResourceId)?.uploaded_on || new Date().toISOString().split('T')[0] : new Date().toISOString().split('T')[0],
                            size: formData.get('url') ? 'Link' : 'N/A',
                            expiration_date: formData.get('expiration_date') || null
                        };
                        
                        if (isEditing) {
                            // Update existing resource
                            const index = this.resources.findIndex(r => r.id === this.editingResourceId);
                            if (index !== -1) {
                                this.resources[index] = resourceData;
                            }
                            this.editingResourceId = undefined;
                            this.showToast('Training resource updated successfully!', 'success');
                        } else {
                            // Add new resource
                            this.resources.push(resourceData);
                            this.showToast('Training resource added successfully!', 'success');
                        }
                        
                        e.target.reset();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addResourceModal'));
                        if (modal) modal.hide();
                        
                        // Reset modal title
                        document.getElementById('addResourceModalLabel').textContent = 'Add Training Resource';
                        
                        this.loadTrainingResources();
                        
                        // Reset button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }, 1000);
                }

                updateAccessSelection(accessType) {
                    const container = document.getElementById('accessSelectionContainer');
                    const label = document.getElementById('accessTypeLabel');
                    const select = document.getElementById('resourceAccessValue');
                    
                    if (accessType === 'public') {
                        container.classList.add('d-none');
                    } else {
                        container.classList.remove('d-none');
                        
                        // Clear existing options
                        select.innerHTML = '';
                        
                        // Update label and populate options
                        if (accessType === 'department') {
                            label.textContent = 'Select Departments';
                            this.populateAccessOptions(select, 'departments');
                        } else if (accessType === 'role') {
                            label.textContent = 'Select Roles';
                            this.populateAccessOptions(select, 'roles');
                        } else if (accessType === 'private') {
                            label.textContent = 'Select Employees';
                            this.populateAccessOptions(select, 'employees');
                        }
                    }
                }

                populateAccessOptions(select, type) {
                    let items = [];
                    
                    switch(type) {
                        case 'departments':
                            items = [
                                { id: 1, name: 'Sales' },
                                { id: 2, name: 'Marketing' },
                                { id: 3, name: 'Human Resources' },
                                { id: 4, name: 'IT' },
                                { id: 5, name: 'Finance' },
                                { id: 6, name: 'Operations' }
                            ];
                            break;
                            
                        case 'roles':
                            items = [
                                { id: 1, name: 'Manager' },
                                { id: 2, name: 'Team Lead' },
                                { id: 3, name: 'Developer' },
                                { id: 4, name: 'Designer' },
                                { id: 5, name: 'Analyst' },
                                { id: 6, name: 'Executive' }
                            ];
                            break;
                            
                        case 'employees':
                            items = [
                                { id: 1, name: 'John Doe', email: 'john@example.com' },
                                { id: 2, name: 'Jane Smith', email: 'jane@example.com' },
                                { id: 3, name: 'Mike Johnson', email: 'mike@example.com' },
                                { id: 4, name: 'Sarah Williams', email: 'sarah@example.com' },
                                { id: 5, name: 'David Brown', email: 'david@example.com' }
                            ];
                            break;
                    }
                    
                    items.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        select.appendChild(option);
                    });
                }

                showToast(message, type = 'success') {
                    // Try SweetAlert2 first if available
                    if (typeof Swal !== 'undefined') {
                        const icon = type === 'success' ? 'success' : 'error';
                        const title = type === 'success' ? 'Success!' : 'Error!';
                        
                        Swal.fire({
                            icon: icon,
                            title: title,
                            text: message,
                            timer: type === 'success' ? 3000 : 0,
                            showConfirmButton: type === 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    
                    // Fallback to Bootstrap toast
                    const toastEl = document.createElement('div');
                    toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
                    toastEl.setAttribute('role', 'alert');
                    toastEl.setAttribute('aria-live', 'assertive');
                    toastEl.setAttribute('aria-atomic', 'true');
                    
                    // Truncate message if too long
                    const displayMessage = message.length > 100 ? message.substring(0, 100) + '...' : message;
                    
                    toastEl.innerHTML = `
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                                ${displayMessage}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    `;
                    
                    const toastContainer = document.querySelector('.toast-container') || this.createToastContainer();
                    toastContainer.appendChild(toastEl);
                    
                    const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: type === 'error' ? 0 : 5000 });
                    toast.show();
                    
                    toastEl.addEventListener('hidden.bs.toast', function() {
                        toastEl.remove();
                    });
                }

                createToastContainer() {
                    const container = document.createElement('div');
                    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                    container.style.zIndex = '11';
                    document.body.appendChild(container);
                    return container;
                }

                viewResource(resourceId) {
                    const resource = this.resources.find(r => r.id === resourceId);
                    if (!resource) {
                        this.showToast('Resource not found', 'error');
                        return;
                    }

                    // Show resource details in modal
                    const modal = new bootstrap.Modal(document.getElementById('viewResourceModal'));
                    document.getElementById('viewResourceTitle').textContent = resource.name;
                    
                    const modalBody = document.getElementById('viewResourceBody');
                    modalBody.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Resource Details</h6>
                                <p><strong>Name:</strong> ${resource.name}</p>
                                <p><strong>Type:</strong> ${this.getResourceTypeLabel(resource.type)}</p>
                                <p><strong>Description:</strong> ${resource.description || 'No description'}</p>
                                <p><strong>Access Level:</strong> ${this.getAccessLabel(resource.access_type)}</p>
                                <p><strong>Uploaded:</strong> ${this.formatDate(resource.uploaded_on)}</p>
                                ${resource.expiration_date ? `<p><strong>Expires:</strong> ${this.formatDate(resource.expiration_date)}</p>` : ''}
                            </div>
                            <div class="col-md-6">
                                <h6>Actions</h6>
                                ${resource.url ? `<a href="${resource.url}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-external-link-alt me-1"></i> Open Link</a>` : ''}
                                <button class="btn btn-outline-primary mb-2"><i class="fas fa-download me-1"></i> Download</button>
                                <button class="btn btn-outline-secondary mb-2"><i class="fas fa-share me-1"></i> Share</button>
                            </div>
                        </div>
                    `;
                    
                    modal.show();
                }

                editResource(resourceId) {
                    const resource = this.resources.find(r => r.id === resourceId);
                    if (!resource) {
                        this.showToast('Resource not found', 'error');
                        return;
                    }

                    // Populate the add resource form with existing data
                    document.getElementById('resourceName').value = resource.name;
                    document.getElementById('resourceType').value = resource.type;
                    document.getElementById('resourceDescription').value = resource.description || '';
                    
                    // Set access type
                    const accessRadio = document.querySelector(`input[name="resourceAccess"][value="${resource.access_type}"]`);
                    if (accessRadio) {
                        accessRadio.checked = true;
                        this.updateAccessSelection(resource.access_type);
                    }
                    
                    // Set URL if it's a link resource
                    if (resource.url) {
                        document.getElementById('link-tab').click();
                        document.getElementById('resourceLink').value = resource.url;
                    } else {
                        document.getElementById('upload-tab').click();
                    }
                    
                    // Set expiration date if exists
                    if (resource.expiration_date) {
                        document.getElementById('setExpiration').checked = true;
                        document.getElementById('expirationDate').value = resource.expiration_date;
                        document.getElementById('expirationDateContainer').classList.remove('d-none');
                    }

                    // Update modal title
                    document.getElementById('addResourceModalLabel').textContent = 'Edit Training Resource';
                    
                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('addResourceModal'));
                    modal.show();

                    // Store the resource ID for update
                    this.editingResourceId = resourceId;
                }

                deleteResource(resourceId) {
                    const resource = this.resources.find(r => r.id === resourceId);
                    if (!resource) {
                        this.showToast('Resource not found', 'error');
                        return;
                    }

                    // Confirm deletion
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Delete Resource',
                            text: `Are you sure you want to delete "${resource.name}"?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Remove from array
                                this.resources = this.resources.filter(r => r.id !== resourceId);
                                
                                // Update table
                                this.loadTrainingResources();
                                
                                this.showToast('Resource deleted successfully!', 'success');
                            }
                        });
                    } else {
                        // Fallback confirmation
                        if (confirm(`Are you sure you want to delete "${resource.name}"?`)) {
                            this.resources = this.resources.filter(r => r.id !== resourceId);
                            this.loadTrainingResources();
                            this.showToast('Resource deleted successfully!', 'success');
                        }
                    }
                }

                viewTraining(trainingId) {
                    const training = this.trainings.find(t => t.id === trainingId);
                    if (!training) {
                        this.showToast('Training not found', 'error');
                        return;
                    }

                    // Show training details in modal
                    const modal = new bootstrap.Modal(document.getElementById('viewResourceModal'));
                    document.getElementById('viewResourceTitle').textContent = training.title;
                    
                    const modalBody = document.getElementById('viewResourceBody');
                    modalBody.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Training Details</h6>
                                <p><strong>Title:</strong> ${training.title}</p>
                                <p><strong>Type:</strong> ${this.getTypeLabel(training.type)}</p>
                                <p><strong>Status:</strong> ${this.getStatusLabel(training.status)}</p>
                                <p><strong>Instructor:</strong> ${training.instructor}</p>
                                <p><strong>Location:</strong> ${training.location || 'Not specified'}</p>
                                <p><strong>Participants:</strong> ${training.participant_count}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Schedule</h6>
                                <p><strong>Start Date:</strong> ${this.formatDate(training.start_date)}</p>
                                <p><strong>End Date:</strong> ${this.formatDate(training.end_date)}</p>
                                <h6 class="mt-3">Description</h6>
                                <p>${training.description}</p>
                            </div>
                        </div>
                    `;
                    
                    modal.show();
                }

                editTraining(trainingId) {
                    const training = this.trainings.find(t => t.id === trainingId);
                    if (!training) {
                        this.showToast('Training not found', 'error');
                        return;
                    }

                    // Populate the create training form with existing data
                    document.getElementById('trainingTitle').value = training.title;
                    document.getElementById('trainingType').value = training.type;
                    document.getElementById('startDate').value = training.start_date;
                    document.getElementById('endDate').value = training.end_date;
                    document.getElementById('trainingDescription').value = training.description;
                    document.getElementById('trainingInstructor').value = training.instructor;
                    document.getElementById('participantCount').value = training.participant_count;
                    document.getElementById('trainingLocation').value = training.location || '';
                    document.getElementById('trainingStatus').value = training.status;

                    // Update modal title
                    document.getElementById('createTrainingModalLabel').textContent = 'Edit Training Program';
                    
                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('createTrainingModal'));
                    modal.show();

                    // Store the training ID for update
                    this.editingTrainingId = trainingId;
                }

                deleteTraining(trainingId) {
                    const training = this.trainings.find(t => t.id === trainingId);
                    if (!training) {
                        this.showToast('Training not found', 'error');
                        return;
                    }

                    // Confirm deletion
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Delete Training',
                            text: `Are you sure you want to delete "${training.title}"?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Remove from array
                                this.trainings = this.trainings.filter(t => t.id !== trainingId);
                                
                                // Update table and stats
                                this.loadTrainingPrograms();
                                this.loadTrainingStats();
                                
                                this.showToast('Training deleted successfully!', 'success');
                            }
                        });
                    } else {
                        // Fallback confirmation
                        if (confirm(`Are you sure you want to delete "${training.title}"?`)) {
                            this.trainings = this.trainings.filter(t => t.id !== trainingId);
                            this.loadTrainingPrograms();
                            this.loadTrainingStats();
                            this.showToast('Training deleted successfully!', 'success');
                        }
                    }
                }
            }

            // Initialize Training Management
            new TrainingManagement();
        });
    </script>
@endsection
