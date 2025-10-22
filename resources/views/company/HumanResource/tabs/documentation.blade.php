<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="header-title">Document Management</h4>
        <p class="text-muted">Store, organize, and manage all company documents in one place</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createFolderModal">
            <i class="fas fa-folder-plus me-1"></i> New Folder
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
            <i class="fas fa-upload me-1"></i> Upload Document
        </button>
    </div>
</div>


<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Total Documents Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-primary text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-file-alt fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Total Documents</h6>
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="mb-0 fw-bold" id="totalDocuments">0</h2>
                        <span class="badge bg-white-20 rounded-pill px-2 py-1" id="monthlyChange">
                            <i class="fas fa-arrow-up me-1"></i> <span id="monthlyChangeText">0%</span>
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
            <a href="#documents" class="stretched-link"></a>
        </div>
    </div>

    <!-- Pending Approvals Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-warning text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Pending Approvals</h6>
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="mb-0 fw-bold" id="pendingApprovals">0</h2>
                        <span class="badge bg-white-20 rounded-pill px-2 py-1 text-warning">
                            <i class="fas fa-exclamation-circle me-1"></i> <span id="overdueCount">0</span> Overdue
                        </span>
                    </div>
                    <div class="mt-auto pt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small opacity-75">Needs your attention</span>
                            <i class="fas fa-chevron-right small opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#pending-approvals" class="stretched-link"></a>
        </div>
    </div>

    <!-- Shared Documents Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-info text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-share-alt fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Recent Uploads</h6>
                    <div class="d-flex align-items-end mb-2">
                        <h2 class="mb-0 fw-bold" id="recentUploads">0</h2>
                        <span class="ms-2 small opacity-75">This month</span>
                    </div>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small"><i class="fas fa-users me-1"></i> <span id="activeUsers">0</span> active users</span>
                            <i class="fas fa-chevron-right small opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#shared" class="stretched-link"></a>
        </div>
    </div>

    <!-- Recent Activity Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-success text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-history fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Storage Used</h6>
                    <div class="d-flex align-items-end justify-content-between mb-2">
                        <h2 class="mb-0 fw-bold" id="storageUsed">0 MB</h2>
                        <span class="badge bg-white-20 rounded-pill px-2 py-1">
                            <i class="fas fa-hdd me-1"></i> Total
                        </span>
                    </div>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small">Latest updates</span>
                            <i class="fas fa-chevron-right small opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#activity" class="stretched-link"></a>
        </div>
    </div>
</div>

<!-- Latest Updates Section -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2 text-primary"></i>Latest Updates
                </h5>
            </div>
            <div class="card-body">
                <div id="latestUpdatesList" class="list-group list-group-flush">
                    <!-- Latest updates will be loaded here -->
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Loading latest updates...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Stats Cards Styling */
    .stats-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .stats-card:hover::before {
        opacity: 1;
    }
    
    .hover-scale {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: translateY(-5px) scale(1.02);
    }
    
    .icon-shape {
        transition: all 0.3s ease;
    }
    
    .stats-card:hover .icon-shape {
        transform: scale(1.1);
        background-color: rgba(255,255,255,0.3) !important;
    }
    
    /* Gradient Backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3b7ddd 0%, #2f6bc6 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #1cbb8c 0%, #189f74 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #3f9ee5 0%, #2f8ed4 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f7b84b 0%, #f59f00 100%);
    }
    
    /* Ensure text is readable on gradient backgrounds */
    .text-white-50 {
        opacity: 0.9;
    }
    
    .bg-white-20 {
        background-color: rgba(255, 255, 255, 0.2) !important;
        backdrop-filter: blur(5px);
    }
    
    /* Smooth transitions */
    .card, .badge, .icon-shape {
        transition: all 0.3s ease-in-out;
    }
</style>

<style>
    .shadow-hover {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .shadow-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
    }
    .icon-shape {
        transition: all 0.3s ease;
    }
    .card:hover .icon-shape {
        transform: scale(1.1);
    }
    .badge {
        font-size: 0.7rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        padding: 0.35rem 0.65rem;
    }
    .display-6 {
        font-size: 1.75rem;
    }
</style>

<!-- Document Browser -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <div class="d-flex gap-2">
                <div class="input-group" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" placeholder="Search documents...">
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary" id="listViewBtn" title="List view">
                        <i class="fas fa-list"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary active" id="gridViewBtn" title="Grid view">
                        <i class="fas fa-th-large"></i>
                    </button>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-sort-amount-down me-1"></i> Sort
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Name (A-Z)</a></li>
                        <li><a class="dropdown-item" href="#">Name (Z-A)</a></li>
                        <li><a class="dropdown-item" href="#">Newest</a></li>
                        <li><a class="dropdown-item" href="#">Oldest</a></li>
                        <li><a class="dropdown-item" href="#">Size</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div class="list-view">
            <div class="table-responsive">
                <table class="table table-centered table-hover table-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Last Modified</th>
                            <th>Size</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="documentsTableBody">
                        <!-- Documents will be loaded dynamically -->
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-spinner fa-spin fa-4x text-primary mb-3"></i>
                                <h5 class="text-muted">Loading documents...</h5>
                                <p class="text-muted">Please wait while we fetch your documents</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Grid View (Hidden by default) -->
        <div class="grid-view" style="display: none;" id="documentsGridView">
            <div class="row" id="documentsGridBody">
                <!-- Documents will be loaded dynamically -->
                <div class="col-12 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading documents...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Folders Management -->
<div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0" id="folderSearch" placeholder="Search folders...">
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary active" data-view="list">
                                <i class="fas fa-list"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-view="grid">
                                <i class="fas fa-th"></i>
                            </button>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                            <i class="fas fa-folder-plus me-1"></i> New Folder
                        </button>
                    </div>
                </div>
                
                <!-- Folders Table View -->
                <div class="table-view" id="foldersTableView">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Documents</th>
                                    <th>Access Level</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="foldersTableBody">
                                <!-- Folders will be loaded dynamically -->
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-spinner fa-spin fa-4x text-primary mb-3"></i>
                                        <h5 class="text-muted">Loading folders...</h5>
                                        <p class="text-muted">Please wait while we fetch your folders</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Folders Grid View -->
                <div class="grid-view" style="display: none;" id="foldersGridView">
                    <div class="row" id="foldersGridBody">
                        <!-- Folders will be loaded dynamically -->
                        <div class="col-12 text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading folders...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Folder Modal -->
<div class="modal fade" id="editFolderModal" tabindex="-1" aria-labelledby="editFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFolderModalLabel">Edit Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editFolderForm">
                <input type="hidden" id="editFolderId" name="folderId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editFolderName" class="form-label">Folder Name</label>
                        <input type="text" class="form-control" id="editFolderName" name="folderName" placeholder="Enter folder name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editFolderDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editFolderDescription" name="folderDescription" rows="2" placeholder="Enter folder description (optional)"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editParentFolder" class="form-label">Location</label>
                        <select class="form-select" id="editParentFolder" name="parentFolder">
                            <option value="">Root Directory</option>
                            <option value="documents">Documents</option>
                            <option value="shared">Shared</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editFolderAccess" class="form-label">Access Level</label>
                        <select class="form-select" id="editFolderAccess" name="folderAccess" required>
                            <option value="private">Private</option>
                            <option value="department">Department</option>
                            <option value="role">Role-based</option>
                            <option value="public">Public</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Folder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Document View Modal -->
<div class="modal fade" id="documentViewModal" tabindex="-1" aria-labelledby="documentViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="documentViewModalLabel">
                    <i class="fas fa-eye me-2"></i>Document Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="documentViewContent">
                <!-- Document details will be loaded here -->
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Loading document details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editDocumentBtn">
                    <i class="fas fa-edit me-1"></i>Edit Document
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Document Edit Modal -->
<div class="modal fade" id="documentEditModal" tabindex="-1" aria-labelledby="documentEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="documentEditModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDocumentForm">
                <div class="modal-body">
                    <input type="hidden" id="editDocumentId" name="id">
                    
                    <div class="mb-3">
                        <label for="editDocumentTitle" class="form-label">Document Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editDocumentTitle" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editDocumentDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDocumentDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDocumentCategory" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="editDocumentCategory" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="policy">Policy</option>
                                    <option value="procedure">Procedure</option>
                                    <option value="form">Form</option>
                                    <option value="template">Template</option>
                                    <option value="contract">Contract</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDocumentFolder" class="form-label">Folder</label>
                                <select class="form-select" id="editDocumentFolder" name="folder_id">
                                    <option value="">Root Directory</option>
                                    <!-- Folders will be loaded dynamically -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDocumentAccess" class="form-label">Access Level</label>
                                <select class="form-select" id="editDocumentAccess" name="access_level">
                                    <option value="private">Private (Only Me)</option>
                                    <option value="department">Department Access</option>
                                    <option value="role">Role-based Access</option>
                                    <option value="public">Public (All Employees)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDocumentStatus" class="form-label">Status</label>
                                <select class="form-select" id="editDocumentStatus" name="status">
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="archived">Archived</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editDocumentTags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="editDocumentTags" name="tags" placeholder="Enter tags separated by commas">
                        <div class="form-text">Separate multiple tags with commas</div>
                    </div>
                    
                    <!-- Current File Info -->
                    <div class="mb-3">
                        <label class="form-label">Current File</label>
                        <div class="border rounded p-3 bg-light">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-alt fa-2x text-primary me-3"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold" id="editCurrentFileName">No file selected</div>
                                    <small class="text-muted" id="editCurrentFileSize">-</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="downloadCurrentFile">
                                    <i class="fas fa-download me-1"></i>Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title"><i class="fas fa-upload me-2"></i>Upload Documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadDocumentForm">
                    <!-- File Upload Area -->
                    <div class="text-center border-2 border-dashed rounded p-5 mb-4" id="dropZone" style="border: 2px dashed #dee2e6; border-radius: 6px;">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                        <h5>Drag & drop files here</h5>
                        <p class="text-muted mb-0">or</p>
                        <label class="btn btn-outline-primary mt-3" for="documentFiles">
                            <i class="fas fa-folder-open me-2"></i>Browse Files
                            <input type="file" id="documentFiles" name="file" class="d-none" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.txt">
                        </label>
                        <p class="small text-muted mt-2 mb-0">Supports: PDF, DOCX, XLSX, PPTX, JPG, PNG (Max: 10MB)</p>
                    </div>

                    <!-- File Preview Area -->
                    <div id="filePreview" class="mb-3" style="display: none;">
                        <h6>Selected File:</h6>
                        <div id="fileList" class="list-group"></div>
                    </div>

                    <!-- Progress Bar -->
                    <div id="uploadProgress" class="mb-3" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small">Uploading...</span>
                            <span class="small" id="progressText">0%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 id="progressBar" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="documentName" class="form-label">Document Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="documentName" name="title" placeholder="Enter document name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="documentDescription" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="documentDescription" name="description" rows="3" placeholder="Enter document description"></textarea>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="documentCategory" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="documentCategory" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="policy">Policy</option>
                                    <option value="procedure">Procedure</option>
                                    <option value="form">Form</option>
                                    <option value="template">Template</option>
                                    <option value="contract">Contract</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="documentFolder" class="form-label">Folder</label>
                                <select class="form-select" id="documentFolder" name="folder_id">
                                    <option value="">Root Directory</option>
                                    <!-- Folders will be loaded dynamically -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="documentAccess" class="form-label">Access Level</label>
                                <select class="form-select" id="documentAccess" name="access_level">
                                    <option value="private">Private (Only Me)</option>
                                    <option value="department">Department Access</option>
                                    <option value="role">Role-based Access</option>
                                    <option value="public">Public (All Employees)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="documentTags" class="form-label">Tags (Optional)</label>
                                <input type="text" class="form-control" id="documentTags" name="tags" placeholder="Enter tags separated by commas">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check form-switch mt-3 mb-3">
                        <input class="form-check-input" type="checkbox" id="notifyTeam" name="notify_team" checked>
                        <label class="form-check-label" for="notifyTeam">Notify team members about this upload</label>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i> Files will be stored securely with encryption
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-upload me-1"></i> Upload Files
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFolderModalLabel">Create New Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createFolderForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="folderName" class="form-label">Folder Name</label>
                        <input type="text" class="form-control" id="folderName" name="folderName" placeholder="Enter folder name" required>
                    </div>
                    <div class="mb-3">
                        <label for="folderDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="folderDescription" name="folderDescription" rows="2" placeholder="Enter folder description (optional)"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="parentFolder" class="form-label">Location</label>
                        <select class="form-select" id="parentFolder" name="parentFolder">
                            <option value="">Root Directory</option>
                            <option value="documents">Documents</option>
                            <option value="shared">Shared</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="folderAccess" class="form-label">Access Level</label>
                        <select class="form-select" id="folderAccess" name="folderAccess" required>
                            <option value="private">Private</option>
                            <option value="department">Department</option>
                            <option value="role">Role-based</option>
                            <option value="public">Public</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="addToFavorites" name="addToFavorites">
                        <label class="form-check-label" for="addToFavorites">
                            Add to Favorites
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Folder</button>
                </div>
            </form>
        </div>
    </div>
</div>


  
<!-- Success Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-check-circle text-success me-2"></i>
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            Folder created successfully!
        </div>
    </div>
</div>

<style>
    /* Modal styles */
    .modal-header {
        border-bottom: 1px solid #e9ecef;
    }
    .modal-footer {
        border-top: 1px solid #e9ecef;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .toast {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    /* Table action buttons */
    .table td {
        vertical-align: middle;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.25rem;
    }
    .btn i {
        font-size: 0.875em;
    }
</style>

<!-- View Document Modal -->
<div class="modal fade" id="viewDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fas fa-file-pdf text-danger" style="font-size: 4rem;"></i>
                    <h5 class="mt-3" id="viewDocumentName"></h5>
                </div>
                <div class="border rounded p-3 bg-light">
                    <p class="mb-2">Document preview would be displayed here</p>
                    <p class="text-muted small mb-0">PDF Viewer Integration</p>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <div>
                    <button type="button" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> Download
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Download Options Modal -->
<div class="modal fade" id="downloadOptionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Download Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Original Format (PDF)</h6>
                            <small>2.4 MB</small>
                        </div>
                        <small class="text-muted">Best for printing</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Microsoft Word</h6>
                            <small>1.8 MB</small>
                        </div>
                        <small class="text-muted">Editable format</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Plain Text</h6>
                            <small>0.5 MB</small>
                        </div>
                        <small class="text-muted">Text only</small>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Share Document Modal -->
<div class="modal fade" id="shareDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Share Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Share Link</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="https://example.com/share/abc123" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="copyLinkBtn">
                            <i class="far fa-copy"></i>
                        </button>
                    </div>
                    <div class="form-text">Anyone with the link can view this document</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Share via Email</label>
                    <div class="input-group mb-2">
                        <input type="email" class="form-control" placeholder="Enter email addresses">
                        <button class="btn btn-primary" type="button">Send</button>
                    </div>
                    <div class="form-text">Separate multiple emails with commas</div>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="allowEditing" checked>
                    <label class="form-check-label" for="allowEditing">Allow editing</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Copy Link</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center px-5">
                <div class="mb-4">
                    <div class="icon-shape bg-soft-danger text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <h4>Delete Document?</h4>
                    <p class="text-muted">Are you sure you want to delete <span class="fw-bold" id="documentToDelete"></span>? <br>This action cannot be undone.</p>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="far fa-trash-alt me-1"></i> Delete
                    </button>
                </div>
            </div>
            <div class="modal-footer border-0"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('📁 Documentation: DOM Content Loaded - Scripts initialized!');


        
        // Documentation Management Class
        class DocumentationManagement {
            constructor() {
                this.baseUrl = '/company/hr/documentation';
                this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                this.init();
            }

            init() {
                this.loadDocumentationStats();
                this.loadDocuments();
                this.loadFolders();
                this.bindEvents();
            }

            bindEvents() {
                // Upload Document form submission
                const uploadDocumentForm = document.getElementById('uploadDocumentForm');
                if (uploadDocumentForm) {
                    uploadDocumentForm.addEventListener('submit', (e) => this.handleUploadDocument(e));
                }

                // Create Folder form submission
                const createFolderForm = document.getElementById('createFolderForm');
                if (createFolderForm) {
                    createFolderForm.addEventListener('submit', (e) => this.handleCreateFolder(e));
                }

                // Edit Folder form submission
                const editFolderForm = document.getElementById('editFolderForm');
                if (editFolderForm) {
                    editFolderForm.addEventListener('submit', (e) => this.handleEditFolder(e));
                }

                // Edit Document form submission
                const editDocumentForm = document.getElementById('editDocumentForm');
                if (editDocumentForm) {
                    editDocumentForm.addEventListener('submit', (e) => this.handleEditDocumentForm(e));
                }

                // Edit Document button in view modal
                const editDocumentBtn = document.getElementById('editDocumentBtn');
                if (editDocumentBtn) {
                    editDocumentBtn.addEventListener('click', () => {
                        const documentId = editDocumentBtn.dataset.documentId;
                        if (documentId) {
                            this.editDocument(documentId);
                        }
                    });
                }

                // Download current file button
                const downloadCurrentFile = document.getElementById('downloadCurrentFile');
                if (downloadCurrentFile) {
                    downloadCurrentFile.addEventListener('click', () => {
                        const documentId = downloadCurrentFile.dataset.documentId;
                        if (documentId) {
                            this.downloadDocument(documentId);
                        }
                    });
                }

                // Folder search functionality
                const folderSearch = document.getElementById('folderSearch');
                if (folderSearch) {
                    let searchTimeout;
                    folderSearch.addEventListener('input', (e) => {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            this.searchFolders(e.target.value);
                        }, 500);
                    });
                }

                // Filter buttons
                document.addEventListener('click', (e) => {
                    if (e.target.closest('[data-filter]')) {
                        const filter = e.target.closest('[data-filter]').dataset.filter;
                        this.filterDocuments(filter);
                    }
                });

                // File selection handler
                const fileInput = document.getElementById('documentFiles');
                if (fileInput) {
                    fileInput.addEventListener('change', (e) => this.handleFileSelection(e));
                }

                // Drag and drop functionality
                const dropZone = document.getElementById('dropZone');
                if (dropZone) {
                    dropZone.addEventListener('dragover', (e) => this.handleDragOver(e));
                    dropZone.addEventListener('drop', (e) => this.handleDrop(e));
                    dropZone.addEventListener('dragleave', (e) => this.handleDragLeave(e));
                }

                // Document action buttons
                document.addEventListener('click', (e) => {
                    if (e.target.closest('[data-action]')) {
                        const action = e.target.closest('[data-action]').dataset.action;
                        const documentId = e.target.closest('[data-action]').dataset.documentId;
                        this.handleDocumentAction(action, documentId);
                    }
                });

                // Search functionality
                const searchInput = document.getElementById('documentSearch');
                if (searchInput) {
                    let searchTimeout;
                    searchInput.addEventListener('input', (e) => {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            this.searchDocuments(e.target.value);
                        }, 500);
                    });
                }
            }

            loadDocumentationStats() {
                console.log('📊 Loading documentation statistics...');
                
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
                    } else {
                        console.error('Failed to load documentation stats:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading documentation stats:', error);
                });
            }

            loadDocuments(filters = {}) {
                console.log('📋 Loading documents...');
                
                const params = new URLSearchParams();
                Object.keys(filters).forEach(key => {
                    if (filters[key]) {
                        params.append(key, filters[key]);
                    }
                });

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
                        this.updateDocumentsTable(data.data);
                    } else {
                        console.error('Failed to load documents:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading documents:', error);
                });
            }

            updateStatsCards(stats) {
                console.log('Updating stats cards with:', stats);
                
                // Update total documents
                const totalDocuments = document.getElementById('totalDocuments');
                if (totalDocuments) {
                    totalDocuments.textContent = stats.total_documents || 0;
                }

                // Update monthly change percentage
                const monthlyChangeText = document.getElementById('monthlyChangeText');
                if (monthlyChangeText) {
                    const change = stats.this_month_change || 0;
                    monthlyChangeText.textContent = change + '%';
                    
                    // Update arrow direction and color based on change
                    const monthlyChangeBadge = document.getElementById('monthlyChange');
                    if (monthlyChangeBadge) {
                        const arrow = monthlyChangeBadge.querySelector('i');
                        if (change >= 0) {
                            arrow.className = 'fas fa-arrow-up me-1';
                            monthlyChangeBadge.className = 'badge bg-white-20 rounded-pill px-2 py-1 text-success';
                        } else {
                            arrow.className = 'fas fa-arrow-down me-1';
                            monthlyChangeBadge.className = 'badge bg-white-20 rounded-pill px-2 py-1 text-danger';
                        }
                    }
                }

                // Update pending approvals
                const pendingApprovals = document.getElementById('pendingApprovals');
                if (pendingApprovals) {
                    pendingApprovals.textContent = stats.pending_approvals || 0;
                }

                // Update overdue count
                const overdueCount = document.getElementById('overdueCount');
                if (overdueCount) {
                    overdueCount.textContent = stats.overdue_documents || 0;
                }

                // Update recent uploads
                const recentUploads = document.getElementById('recentUploads');
                if (recentUploads) {
                    recentUploads.textContent = stats.recent_uploads || 0;
                }

                // Update storage used
                const storageUsed = document.getElementById('storageUsed');
                if (storageUsed) {
                    storageUsed.textContent = (stats.storage_used || 0).toFixed(1) + ' MB';
                }

                // Update active users
                const activeUsers = document.getElementById('activeUsers');
                if (activeUsers) {
                    activeUsers.textContent = stats.active_users || 0;
                }

                // Update latest updates
                this.updateLatestUpdates(stats.latest_updates || []);
            }

            updateLatestUpdates(updates) {
                const updatesList = document.getElementById('latestUpdatesList');
                if (!updatesList) return;

                if (updates.length === 0) {
                    updatesList.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No recent updates</p>
                        </div>
                    `;
                    return;
                }

                updatesList.innerHTML = updates.map(update => `
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-file-alt text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">${update.title}</h6>
                                        <p class="mb-1 text-muted small">
                                            <span class="badge bg-${this.getCategoryBadgeClass(update.category)} me-2">
                                                ${this.getCategoryLabel(update.category)}
                                            </span>
                                            by ${update.uploader_name}
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-${this.getStatusBadgeClass(update.status)} mb-1">
                                            ${this.getStatusLabel(update.status)}
                                        </span>
                                        <p class="mb-0 small text-muted">${this.formatDate(update.updated_at)}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            updateDocumentsTable(documents) {
                const tbody = document.getElementById('documentsTableBody');
                if (!tbody) return;

                tbody.innerHTML = '';

                if (documents.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No documents found</h5>
                                <p class="text-muted">Upload your first document to get started!</p>
                            </td>
                        </tr>
                    `;
                    return;
                }

                documents.forEach(doc => {
                    const row = this.createDocumentRow(doc);
                    tbody.appendChild(row);
                });
            }

            createDocumentRow(doc) {
                const row = document.createElement('tr');
                const uploaderName = doc.uploader ? 
                    `${doc.uploader.first_name || ''} ${doc.uploader.last_name || ''}`.trim() : 
                    'Unknown User';
                
                row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="${this.getFileIconClass(doc.file_type)} me-2"></i>
                            <div>
                                <h6 class="mb-0">${doc.title}</h6>
                                <small class="text-muted">${doc.file_name}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge ${this.getCategoryBadgeClass(doc.category)}">${this.getCategoryLabel(doc.category)}</span></td>
                    <td><span class="badge ${this.getAccessLevelBadgeClass(doc.access_level)}">${this.getAccessLevelLabel(doc.access_level)}</span></td>
                    <td>${this.formatFileSize(doc.file_size)}</td>
                    <td>${uploaderName}</td>
                    <td>${this.formatDate(doc.created_at)}</td>
                    <td><span class="badge ${this.getStatusBadgeClass(doc.status)}">${this.getStatusLabel(doc.status)}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-action="view-document" data-document-id="${doc.id}" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" data-action="edit-document" data-document-id="${doc.id}" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" data-action="download-document" data-document-id="${doc.id}" title="Download">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-action="delete-document" data-document-id="${doc.id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                return row;
            }

            getCategoryLabel(category) {
                const categories = {
                    'policy': 'Policy',
                    'procedure': 'Procedure',
                    'form': 'Form',
                    'template': 'Template',
                    'contract': 'Contract',
                    'other': 'Other'
                };
                return categories[category] || category;
            }

            getCategoryBadgeClass(category) {
                const classes = {
                    'policy': 'bg-primary bg-opacity-10 text-primary',
                    'procedure': 'bg-info bg-opacity-10 text-info',
                    'form': 'bg-success bg-opacity-10 text-success',
                    'template': 'bg-warning bg-opacity-10 text-warning',
                    'contract': 'bg-danger bg-opacity-10 text-danger',
                    'other': 'bg-secondary bg-opacity-10 text-secondary'
                };
                return classes[category] || 'bg-secondary bg-opacity-10 text-secondary';
            }

            getAccessLevelLabel(accessLevel) {
                const levels = {
                    'public': 'Public',
                    'department': 'Department',
                    'role': 'Role-based',
                    'private': 'Private'
                };
                return levels[accessLevel] || accessLevel;
            }

            getAccessLevelBadgeClass(accessLevel) {
                const classes = {
                    'public': 'bg-success bg-opacity-10 text-success',
                    'department': 'bg-info bg-opacity-10 text-info',
                    'role': 'bg-warning bg-opacity-10 text-warning',
                    'private': 'bg-danger bg-opacity-10 text-danger'
                };
                return classes[accessLevel] || 'bg-secondary bg-opacity-10 text-secondary';
            }

            getStatusLabel(status) {
                const statuses = {
                    'pending': 'Pending',
                    'approved': 'Approved',
                    'rejected': 'Rejected'
                };
                return statuses[status] || status;
            }

            getStatusBadgeClass(status) {
                const classes = {
                    'pending': 'bg-warning bg-opacity-10 text-warning',
                    'approved': 'bg-success bg-opacity-10 text-success',
                    'rejected': 'bg-danger bg-opacity-10 text-danger'
                };
                return classes[status] || 'bg-secondary bg-opacity-10 text-secondary';
            }

            getFileIconClass(fileType) {
                const icons = {
                    'pdf': 'fas fa-file-pdf text-danger',
                    'doc': 'fas fa-file-word text-primary',
                    'docx': 'fas fa-file-word text-primary',
                    'xls': 'fas fa-file-excel text-success',
                    'xlsx': 'fas fa-file-excel text-success',
                    'ppt': 'fas fa-file-powerpoint text-warning',
                    'pptx': 'fas fa-file-powerpoint text-warning',
                    'txt': 'fas fa-file-alt text-secondary',
                    'jpg': 'fas fa-file-image text-info',
                    'jpeg': 'fas fa-file-image text-info',
                    'png': 'fas fa-file-image text-info',
                    'gif': 'fas fa-file-image text-info'
                };
                return icons[fileType] || 'fas fa-file text-secondary';
            }

            formatFileSize(bytes) {
                if (!bytes) return '0 B';
                const units = ['B', 'KB', 'MB', 'GB'];
                let size = bytes;
                let unitIndex = 0;
                
                while (size >= 1024 && unitIndex < units.length - 1) {
                    size /= 1024;
                    unitIndex++;
                }
                
                return Math.round(size * 100) / 100 + ' ' + units[unitIndex];
            }

            formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            filterDocuments(filter) {
                const filters = {};
                if (filter !== 'all') {
                    filters.category = filter;
                }
                this.loadDocuments(filters);
            }

            // Folder Management Methods
            loadFolders() {
                console.log('📁 Loading folders...');
                console.log('Base URL:', this.baseUrl);
                console.log('CSRF Token:', this.csrfToken);
                
                // First test the simple debug route
                fetch('/debug-folders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                })
                .then(response => {
                    console.log('Debug route response:', response.status);
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Debug route failed');
                })
                .then(data => {
                    console.log('Debug route data:', data);
                    // Test the controller directly
                    return fetch('/test-documentation-controller', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    });
                })
                .then(response => {
                    console.log('Controller test response:', response.status);
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Controller test failed');
                })
                .then(data => {
                    console.log('Controller test data:', data);
                    // Now try the actual folders route
                    return fetch(`${this.baseUrl}/folders`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    });
                })
                .then(response => {
                    console.log('Folders API Response Status:', response.status);
                    console.log('Folders API Response Headers:', response.headers.get('content-type'));
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Non-JSON response received:', text);
                            throw new Error('Server returned non-JSON response: ' + text.substring(0, 200));
                        });
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('Folders API Response Data:', data);
                    if (data.success) {
                        console.log('Folders loaded successfully:', data.data);
                        this.updateFoldersTable(data.data);
                        this.updateFolderSelect(data.data);
                    } else {
                        console.error('Failed to load folders:', data.message);
                        this.showToast('Failed to load folders: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading folders:', error);
                    this.showToast('Error loading folders: ' + error.message, 'error');
                });
            }

            updateFoldersTable(folders) {
                console.log('Updating folders table with:', folders);
                const tbody = document.getElementById('foldersTableBody');
                if (!tbody) {
                    console.error('Folders table body not found!');
                    return;
                }

                tbody.innerHTML = '';

                if (folders.length === 0) {
                    console.log('No folders found, showing empty state');
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No folders found</h5>
                                <p class="text-muted">Create your first folder to get started!</p>
                            </td>
                        </tr>
                    `;
                    return;
                }

                folders.forEach(folder => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-folder-id', folder.id);
                    row.setAttribute('data-parent-id', folder.parent_id || '');
                    row.setAttribute('data-access-level', folder.access_level);
                    row.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-folder text-primary me-2"></i>
                                <strong>${folder.name}</strong>
                            </div>
                        </td>
                        <td>${folder.description || 'No description'}</td>
                        <td>
                            <span class="badge bg-info">${folder.documents_count || 0} documents</span>
                        </td>
                        <td>
                            <span class="badge ${this.getAccessLevelClass(folder.access_level)}">
                                ${this.getAccessLevelText(folder.access_level)}
                            </span>
                        </td>
                        <td>${folder.creator ? folder.creator.name : 'Unknown'}</td>
                        <td>${this.formatDate(folder.created_at)}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="documentationManager.editFolder(${folder.id})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="documentationManager.deleteFolder(${folder.id})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }

            updateFolderSelect(folders) {
                console.log('Updating folder select with:', folders);
                const select = document.getElementById('documentFolder');
                if (!select) {
                    console.error('Document folder select not found!');
                    return;
                }

                // Clear existing options except the first one
                select.innerHTML = '<option value="">Select Folder</option>';
                
                folders.forEach(folder => {
                    const option = document.createElement('option');
                    option.value = folder.id;
                    option.textContent = folder.name;
                    select.appendChild(option);
                });
                
                console.log('Folder select updated with', folders.length, 'folders');
            }

            getAccessLevelClass(level) {
                const classes = {
                    'public': 'bg-success',
                    'department': 'bg-info',
                    'role': 'bg-warning',
                    'private': 'bg-secondary'
                };
                return classes[level] || 'bg-secondary';
            }

            getAccessLevelText(level) {
                const texts = {
                    'public': 'Public',
                    'department': 'Department',
                    'role': 'Role-based',
                    'private': 'Private'
                };
                return texts[level] || 'Unknown';
            }

            editFolder(folderId) {
                console.log('Edit folder:', folderId);
                
                // Get folder data from the table row instead of this.folders array
                const row = document.querySelector(`tr[data-folder-id="${folderId}"]`);
                if (!row) {
                    this.showToast('Folder not found!', 'error');
                    return;
                }

                // Extract folder data from the table row
                const folderData = {
                    id: folderId,
                    name: row.cells[0].textContent.trim(),
                    description: row.cells[1].textContent.trim() || '',
                    parent_id: row.dataset.parentId || '',
                    access_level: row.dataset.accessLevel || 'private'
                };

                console.log('Folder data extracted:', folderData);

                // Populate the edit form
                document.getElementById('editFolderId').value = folderData.id;
                document.getElementById('editFolderName').value = folderData.name;
                document.getElementById('editFolderDescription').value = folderData.description;
                document.getElementById('editParentFolder').value = folderData.parent_id;
                document.getElementById('editFolderAccess').value = folderData.access_level;

                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('editFolderModal'));
                modal.show();
            }

            deleteFolder(folderId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this! This will permanently delete the folder and all its contents.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the folder.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch(`${this.baseUrl}/folders/${folderId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Folder has been deleted successfully.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                this.loadFolders();
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to delete folder: ' + data.message,
                                    icon: 'error'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting folder:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error deleting folder: ' + error.message,
                                icon: 'error'
                            });
                        });
                    }
                });
            }

            handleEditFolder(e) {
                e.preventDefault();
                console.log('📁 Updating folder...');
                
                const formData = new FormData(e.target);
                const submitBtn = e.target.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Updating...';
                
                const folderData = {
                    name: formData.get('folderName'),
                    description: formData.get('folderDescription'),
                    access_level: formData.get('folderAccess'),
                    parent_id: formData.get('parentFolder') || null
                };

                const folderId = formData.get('folderId');

                fetch(`${this.baseUrl}/folders/${folderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify(folderData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.showToast('Folder updated successfully!', 'success');
                        this.loadFolders();
                        
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editFolderModal'));
                        modal.hide();
                    } else {
                        this.showToast('Failed to update folder: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Error updating folder:', error);
                    this.showToast('Error updating folder: ' + error.message, 'error');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
            }

            searchDocuments(searchTerm) {
                const filters = {};
                if (searchTerm) {
                    filters.search = searchTerm;
                }
                this.loadDocuments(filters);
            }

            searchFolders(searchTerm) {
                console.log('🔍 Searching folders:', searchTerm);
                // For now, just reload all folders
                // TODO: Implement server-side folder search
                this.loadFolders();
            }

            handleUploadDocument(e) {
                e.preventDefault();
                console.log('📁 Uploading document...');
                
                const formData = new FormData(e.target);
                const submitBtn = e.target.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                // Validate required fields
                const documentName = formData.get('title');
                if (!documentName || documentName.trim() === '') {
                    this.showToast('Document name is required!', 'error');
                    return;
                }
                
                // Validate category
                const category = formData.get('category');
                if (!category) {
                    this.showToast('Please select a category!', 'error');
                    return;
                }
                
                // Check if file is selected
                const file = formData.get('file');
                if (!file || !file.name) {
                    this.showToast('Please select a file!', 'error');
                    return;
                }
                
                // Show progress bar
                document.getElementById('uploadProgress').style.display = 'block';
                const progressBar = document.getElementById('progressBar');
                const progressText = document.getElementById('progressText');
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Uploading...';
                
                // Create XMLHttpRequest for progress tracking
                const xhr = new XMLHttpRequest();
                
                // Track upload progress
                xhr.upload.addEventListener('progress', (event) => {
                    if (event.lengthComputable) {
                        const percentComplete = Math.round((event.loaded / event.total) * 100);
                        progressBar.style.width = percentComplete + '%';
                        progressText.textContent = percentComplete + '%';
                    }
                });
                
                xhr.addEventListener('load', () => {
                    if (xhr.status === 200) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        this.showToast('Document uploaded successfully!', 'success');
                        e.target.reset();
                                document.getElementById('filePreview').style.display = 'none';
                                document.getElementById('fileList').innerHTML = '';
                        const modal = bootstrap.Modal.getInstance(document.getElementById('uploadDocumentModal'));
                        if (modal) modal.hide();
                        this.loadDocuments();
                    } else {
                        this.showToast(data.message || 'Failed to upload document', 'error');
                                console.error('Upload error:', data);
                            }
                        } catch (error) {
                            console.error('Error parsing response:', error);
                            this.showToast('Invalid response from server', 'error');
                        }
                    } else {
                        this.showToast('Upload failed with status: ' + xhr.status, 'error');
                    }
                });
                
                xhr.addEventListener('error', () => {
                    this.showToast('Network error occurred during upload', 'error');
                });
                
                xhr.open('POST', `${this.baseUrl}/store`);
                xhr.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);
                xhr.send(formData);
                
                // Reset button state after request completes
                xhr.addEventListener('loadend', () => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    document.getElementById('uploadProgress').style.display = 'none';
                    progressBar.style.width = '0%';
                    progressText.textContent = '0%';
                });
            }

            // File handling functions
            handleFileSelection(e) {
                const file = e.target.files[0];
                if (file) {
                    this.displayFilePreview(file);
                }
            }

            handleDragOver(e) {
                e.preventDefault();
                e.currentTarget.style.borderColor = '#007bff';
                e.currentTarget.style.backgroundColor = '#f8f9fa';
            }

            handleDragLeave(e) {
                e.preventDefault();
                e.currentTarget.style.borderColor = '#dee2e6';
                e.currentTarget.style.backgroundColor = '';
            }

            handleDrop(e) {
                e.preventDefault();
                e.currentTarget.style.borderColor = '#dee2e6';
                e.currentTarget.style.backgroundColor = '';
                
                const files = Array.from(e.dataTransfer.files);
                if (files.length > 0) {
                    const fileInput = document.getElementById('documentFiles');
                    const file = files[0]; // Only take the first file
                    
                    // Update file input with dropped file
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    fileInput.files = dt.files;
                    
                    this.displayFilePreview(file);
                }
            }

            displayFilePreview(file) {
                const filePreview = document.getElementById('filePreview');
                const fileList = document.getElementById('fileList');
                
                if (!file) {
                    filePreview.style.display = 'none';
                    return;
                }
                
                filePreview.style.display = 'block';
                fileList.innerHTML = '';
                
                const fileItem = document.createElement('div');
                fileItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                fileItem.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file me-2 text-muted"></i>
                        <div>
                            <div class="fw-bold">${file.name}</div>
                            <small class="text-muted">${this.formatFileSize(file.size)}</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="documentationManager.removeFile()">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                fileList.appendChild(fileItem);
            }

            removeFile() {
                const fileInput = document.getElementById('documentFiles');
                fileInput.value = ''; // Clear the file input
                this.displayFilePreview(null);
            }

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Document action handlers
            handleDocumentAction(action, documentId) {
                console.log(`Document action: ${action} for document ${documentId}`);
                
                switch (action) {
                    case 'view-document':
                        this.viewDocument(documentId);
                        break;
                    case 'edit-document':
                        this.editDocument(documentId);
                        break;
                    case 'download-document':
                        this.downloadDocument(documentId);
                        break;
                    case 'delete-document':
                        this.deleteDocument(documentId);
                        break;
                    default:
                        console.warn(`Unknown document action: ${action}`);
                }
            }

            async viewDocument(documentId) {
                console.log('Viewing document:', documentId);
                
                try {
                    // Show loading state
                    Swal.fire({
                        title: 'Loading document...',
                        text: 'Please wait while we fetch the document details.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const response = await fetch(`${this.baseUrl}/${documentId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();
                    Swal.close();

                    if (data.success) {
                        this.showDocumentViewModal(data.data);
                    } else {
                        this.showToast(data.message || 'Failed to load document', 'error');
                    }
                } catch (error) {
                    Swal.close();
                    console.error('Error loading document:', error);
                    this.showToast('An error occurred while loading the document', 'error');
                }
            }

            async editDocument(documentId) {
                console.log('Editing document:', documentId);
                
                try {
                    // Show loading state
                    Swal.fire({
                        title: 'Loading document...',
                        text: 'Please wait while we fetch the document details.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const response = await fetch(`${this.baseUrl}/${documentId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();
                    Swal.close();

                    if (data.success) {
                        this.showDocumentEditModal(data.data);
                    } else {
                        this.showToast(data.message || 'Failed to load document', 'error');
                    }
                } catch (error) {
                    Swal.close();
                    console.error('Error loading document:', error);
                    this.showToast('An error occurred while loading the document', 'error');
                }
            }

            downloadDocument(documentId) {
                console.log('Downloading document:', documentId);
                
                // Create a temporary link to download the document
                const downloadUrl = `${this.baseUrl}/${documentId}/download`;
                
                // Create a temporary anchor element and trigger download
                const link = document.createElement('a');
                link.href = downloadUrl;
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                this.showToast('Document download started!', 'success');
            }

            deleteDocument(documentId) {
                console.log('Deleting document:', documentId);
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This document will be permanently deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.performDeleteDocument(documentId);
                    }
                });
            }

            async performDeleteDocument(documentId) {
                try {
                    const response = await fetch(`${this.baseUrl}/${documentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.showToast('Document deleted successfully!', 'success');
                        this.loadDocuments(); // Refresh the documents table
                        this.loadDocumentationStats(); // Refresh stats
                    } else {
                        this.showToast(data.message || 'Failed to delete document', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting document:', error);
                    this.showToast('An error occurred while deleting the document', 'error');
                }
            }

            showDocumentViewModal(doc) {
                console.log('Showing document view modal:', doc);
                
                const content = document.getElementById('documentViewContent');
                const editBtn = document.getElementById('editDocumentBtn');
                
                // Set document ID for edit button
                editBtn.dataset.documentId = doc.id;
                
                // Create the view content
                content.innerHTML = `
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-file-alt fa-3x text-primary me-3"></i>
                                    <div>
                                        <h4 class="mb-1">${doc.title}</h4>
                                        <p class="text-muted mb-0">${doc.file_name || 'Unknown file'}</p>
                                    </div>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="border rounded p-3">
                                            <h6 class="text-muted mb-2">Category</h6>
                                            <span class="badge bg-${this.getCategoryBadgeClass(doc.category)} fs-6">
                                                ${this.getCategoryLabel(doc.category)}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-3">
                                            <h6 class="text-muted mb-2">Status</h6>
                                            <span class="badge bg-${this.getStatusBadgeClass(doc.status)} fs-6">
                                                ${this.getStatusLabel(doc.status)}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-3">
                                            <h6 class="text-muted mb-2">Access Level</h6>
                                            <span class="badge bg-${this.getAccessLevelBadgeClass(doc.access_level)} fs-6">
                                                ${this.getAccessLevelLabel(doc.access_level)}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-3">
                                            <h6 class="text-muted mb-2">File Size</h6>
                                            <span class="fw-bold">${this.formatFileSize(doc.file_size || 0)}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                ${doc.description ? `
                                <div class="mt-4">
                                    <h6 class="text-muted mb-2">Description</h6>
                                    <p class="border rounded p-3 bg-light">${doc.description}</p>
                                </div>
                                ` : ''}
                                
                                ${doc.tags ? `
                                <div class="mt-4">
                                    <h6 class="text-muted mb-2">Tags</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        ${doc.tags.split(',').map(tag => `
                                            <span class="badge bg-secondary">${tag.trim()}</span>
                                        `).join('')}
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">Document Information</h6>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Uploaded by</small>
                                        <div class="fw-bold">${doc.uploader ? 
                                            (doc.uploader.first_name + ' ' + doc.uploader.last_name) : 
                                            'Unknown User'}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Upload Date</small>
                                        <div class="fw-bold">${this.formatDate(doc.created_at)}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Last Modified</small>
                                        <div class="fw-bold">${this.formatDate(doc.updated_at)}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Folder</small>
                                        <div class="fw-bold">${doc.folder ? doc.folder.name : 'Root Directory'}</div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <button class="btn btn-primary w-100 mb-2" onclick="documentationManager.downloadDocument(${doc.id})">
                                            <i class="fas fa-download me-1"></i>Download Document
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('documentViewModal'));
                modal.show();
            }

            showDocumentEditModal(doc) {
                console.log('Showing document edit modal:', doc);
                
                // Populate form fields
                document.getElementById('editDocumentId').value = doc.id;
                document.getElementById('editDocumentTitle').value = doc.title || '';
                document.getElementById('editDocumentDescription').value = doc.description || '';
                document.getElementById('editDocumentCategory').value = doc.category || '';
                document.getElementById('editDocumentAccess').value = doc.access_level || '';
                document.getElementById('editDocumentStatus').value = doc.status || '';
                document.getElementById('editDocumentTags').value = doc.tags || '';
                document.getElementById('editDocumentFolder').value = doc.folder_id || '';
                
                // Update current file info
                document.getElementById('editCurrentFileName').textContent = doc.file_name || 'No file selected';
                document.getElementById('editCurrentFileSize').textContent = this.formatFileSize(doc.file_size || 0);
                
                // Set download button data
                const downloadBtn = document.getElementById('downloadCurrentFile');
                downloadBtn.dataset.documentId = doc.id;
                
                // Load folders into the folder select
                this.loadFoldersForEdit();
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('documentEditModal'));
                modal.show();
            }

            async loadFoldersForEdit() {
                try {
                    const response = await fetch(`${this.baseUrl}/folders`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        const folderSelect = document.getElementById('editDocumentFolder');
                        folderSelect.innerHTML = '<option value="">Root Directory</option>';
                        
                        data.data.forEach(folder => {
                            const option = document.createElement('option');
                            option.value = folder.id;
                            option.textContent = folder.name;
                            folderSelect.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Error loading folders for edit:', error);
                }
            }

            async handleEditDocumentForm(e) {
                e.preventDefault();
                console.log('Handling edit document form...');
                
                const formData = new FormData(e.target);
                const documentId = formData.get('id');
                const submitBtn = e.target.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';
                
                try {
                    const response = await fetch(`${this.baseUrl}/${documentId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            title: formData.get('title'),
                            description: formData.get('description'),
                            category: formData.get('category'),
                            folder_id: formData.get('folder_id'),
                            access_level: formData.get('access_level'),
                            status: formData.get('status'),
                            tags: formData.get('tags')
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.showToast('Document updated successfully!', 'success');
                        
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('documentEditModal'));
                        modal.hide();
                        
                        // Refresh data
                        this.loadDocuments();
                        this.loadDocumentationStats();
                    } else {
                        this.showToast(data.message || 'Failed to update document', 'error');
                    }
                } catch (error) {
                    console.error('Error updating document:', error);
                    this.showToast('An error occurred while updating the document', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            }

            handleCreateFolder(e) {
                e.preventDefault();
                console.log('📁 Creating folder...');
                
                const formData = new FormData(e.target);
                const submitBtn = e.target.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Creating...';
                
                const folderData = {
                    name: formData.get('folderName'),
                    description: formData.get('folderDescription'),
                    access_level: formData.get('folderAccess'),
                    parent_id: formData.get('parentFolder') || null
                };

                fetch(`${this.baseUrl}/folders/create`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify(folderData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                    this.showToast('Folder created successfully!', 'success');
                    e.target.reset();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createFolderModal'));
                    if (modal) modal.hide();
                        this.loadFolders();
                    } else {
                        this.showToast(data.message || 'Failed to create folder', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error creating folder:', error);
                    this.showToast('An error occurred while creating the folder', 'error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
            }

            formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            showToast(message, type = 'success') {
                const toastEl = document.createElement('div');
                toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
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
                
                const toastContainer = document.querySelector('.toast-container') || this.createToastContainer();
                toastContainer.appendChild(toastEl);
                
                const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
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
        }

        // Initialize Documentation Management
        window.documentationManager = new DocumentationManagement();
        // Toggle between grid and list view
        const gridViewBtn = document.getElementById('gridViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        
        if (gridViewBtn && listViewBtn) {
            gridViewBtn.addEventListener('click', function() {
                document.querySelector('.list-view').style.display = 'none';
                document.querySelector('.grid-view').style.display = 'block';
                listViewBtn.classList.remove('active');
                gridViewBtn.classList.add('active');
            });
            
            listViewBtn.addEventListener('click', function() {
                document.querySelector('.grid-view').style.display = 'none';
                document.querySelector('.list-view').style.display = 'block';
                gridViewBtn.classList.remove('active');
                listViewBtn.classList.add('active');
            });
        }

        // Handle folder creation form submission
        const createFolderForm = document.getElementById('createFolderForm');
        if (createFolderForm) {
            createFolderForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const folderName = document.getElementById('folderName').value;
                const parentFolder = document.getElementById('parentFolder').value;
                const addToFavorites = document.getElementById('addToFavorites').checked;
                
                // Here you would typically make an API call to create the folder
                console.log('Creating folder:', { folderName, parentFolder, addToFavorites });
                
                // Show success message
                const toast = new bootstrap.Toast(document.getElementById('toast'));
                document.getElementById('toastMessage').textContent = `Folder "${folderName}" created successfully!`;
                toast.show();
                
                // Reset form and close modal
                createFolderForm.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('createFolderModal'));
                modal.hide();
            });
        }
    });
</script>

<style>
    /* Modal custom styles */
    .modal-content {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }
    .modal-header {
        border-bottom: 1px solid #f1f1f1;
        padding: 1.25rem 1.5rem;
    }
    .modal-footer {
        border-top: 1px solid #f1f1f1;
        padding: 1rem 1.5rem;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .list-group-item {
        padding: 0.75rem 1.25rem;
        border-left: none;
        border-right: none;
    }
    .list-group-item:first-child {
        border-top: none;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
</style>

