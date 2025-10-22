@extends('layouts.vertical', ['page_title' => 'Email Templates', 'mode' => session('theme_mode', 'light')])

@section('css')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<!-- DataTables CDN -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<!-- Select2 for multi-select -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Summernote WYSIWYG Editor -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<!-- Prism.js for code highlighting -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>

<style>
    .email-templates-container {
        background: #f5f7ff;
        min-height: 100%;
        padding: 1.5rem;
        border-radius: 12px;
    }

    /* Dashboard Cards */
    .dashboard-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-left: 4px solid #3b7ddd;
        position: relative;
        overflow: hidden;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .card-content {
        position: relative;
        z-index: 2;
    }

    .card-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #fff;
        margin-bottom: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .dashboard-card:hover .card-icon {
        transform: scale(1.1);
    }

    .card-title {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 8px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-value {
        font-size: 28px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .card-change {
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        color: #6c757d;
    }

    /* Card specific colors */
    .card-total-templates { border-left-color: #007bff; }
    .card-active-templates { border-left-color: #28a745; }
    .card-draft-templates { border-left-color: #ffc107; }
    .card-emails-sent { border-left-color: #17a2b8; }

    /* Template Cards */
    .template-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 20px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .template-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
        border-color: #007bff;
    }

    .template-header {
        padding: 15px 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .template-name {
        font-weight: 600;
        color: #2d3748;
        margin: 0;
        font-size: 16px;
    }

    .template-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-draft {
        background: #fff3cd;
        color: #856404;
    }

    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .template-body {
        padding: 20px;
    }

    .template-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .template-info {
        font-size: 12px;
        color: #6c757d;
    }

    .template-type {
        background: #e9ecef;
        color: #495057;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .template-description {
        color: #6c757d;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .template-preview {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        max-height: 150px;
        overflow: hidden;
        position: relative;
    }

    .template-preview::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 30px;
        background: linear-gradient(transparent, #f8f9fa);
    }

    .template-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-preview {
        background: #e9ecef;
        color: #495057;
    }

    .btn-preview:hover {
        background: #dee2e6;
    }

    .btn-edit {
        background: #007bff;
        color: white;
    }

    .btn-edit:hover {
        background: #0056b3;
    }

    .btn-duplicate {
        background: #28a745;
        color: white;
    }

    .btn-duplicate:hover {
        background: #1e7e34;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    /* Filter Panel */
    .filter-panel {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    /* Editor Styles */
    .email-editor {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    .editor-toolbar {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }

    .variable-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 10px;
    }

    .variable-tag {
        background: #007bff;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .variable-tag:hover {
        background: #0056b3;
    }

    /* Template Variables */
    .variables-panel {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .variables-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .variables-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 10px;
    }

    .variable-item {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .variable-item:hover {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }

    /* Preview Panel */
    .preview-panel {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 20px;
        margin-top: 20px;
        max-height: 400px;
        overflow-y: auto;
    }

    .preview-header {
        background: #f8f9fa;
        padding: 10px 15px;
        margin: -20px -20px 20px -20px;
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
        color: #495057;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .template-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .template-actions {
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .card-value {
            font-size: 24px;
        }

        .variables-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Form styling */
    .form-floating > label {
        padding: 1rem 0.75rem;
    }

    .required-field::after {
        content: " *";
        color: #e74c3c;
    }

    /* Code highlighting */
    pre[class*="language-"] {
        margin: 0;
        padding: 1rem;
        border-radius: 6px;
        font-size: 12px;
    }

    /* Test Email Panel */
    .test-email-panel {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .test-email-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .test-email-icon {
        color: #856404;
        font-size: 18px;
    }

    .test-email-title {
        font-weight: 600;
        color: #856404;
        margin: 0;
    }

    /* Template Categories */
    .category-filter {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .category-btn {
        padding: 8px 16px;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #495057;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .category-btn.active,
    .category-btn:hover {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }
</style>
@endsection

@section('content')
<div class="email-templates-container">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Administration</a></li>
                        <li class="breadcrumb-item active">Email Templates</li>
                    </ol>
                </div>
                <h4 class="page-title">Email Template Configuration</h4>
                <p class="text-muted mb-0">Create, manage, and customize email templates for automated communications</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-total-templates">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h6 class="card-title">Total Templates</h6>
                    <h3 class="card-value" id="totalTemplates">24</h3>
                    <div class="card-change">
                        <i class="fas fa-chart-line me-1"></i> All email templates
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-active-templates">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h6 class="card-title">Active Templates</h6>
                    <h3 class="card-value" id="activeTemplates">18</h3>
                    <div class="card-change">
                        <i class="fas fa-arrow-up me-1 text-success"></i> Currently in use
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-draft-templates">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h6 class="card-title">Draft Templates</h6>
                    <h3 class="card-value" id="draftTemplates">6</h3>
                    <div class="card-change">
                        <i class="fas fa-clock me-1"></i> Pending completion
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-emails-sent">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h6 class="card-title">Emails Sent</h6>
                    <h3 class="card-value" id="emailsSent">1,247</h3>
                    <div class="card-change">
                        <i class="fas fa-calendar me-1"></i> This month
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                        <i class="fas fa-plus me-2"></i>Create Template
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importTemplateModal">
                        <i class="fas fa-upload me-2"></i>Import Template
                    </button>
                    <button type="button" class="btn btn-info" onclick="exportAllTemplates()">
                        <i class="fas fa-download me-2"></i>Export All
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#testEmailModal">
                        <i class="fas fa-flask me-2"></i>Test Email
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>Settings
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="configureDefaults()">
                                <i class="fas fa-sliders-h me-2"></i>Default Settings
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="manageVariables()">
                                <i class="fas fa-tags me-2"></i>Manage Variables
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="emailSettings()">
                                <i class="fas fa-envelope-open me-2"></i>Email Settings
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="viewEmailLogs()">
                                <i class="fas fa-history me-2"></i>Email Logs
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="text-muted">
                    <small><i class="fas fa-info-circle me-1"></i>Last updated: <span id="lastUpdated">Just now</span></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="filter-panel">
        <div class="row g-3">
            <div class="col-md-2">
                <label for="categoryFilter" class="form-label">Category</label>
                <select class="form-select form-select-sm" id="categoryFilter">
                    <option value="all">All Categories</option>
                    <option value="notification">Notifications</option>
                    <option value="marketing">Marketing</option>
                    <option value="transactional">Transactional</option>
                    <option value="system">System</option>
                    <option value="welcome">Welcome</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="statusFilter" class="form-label">Status</label>
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="draft">Draft</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="languageFilter" class="form-label">Language</label>
                <select class="form-select form-select-sm" id="languageFilter">
                    <option value="all">All Languages</option>
                    <option value="en">English</option>
                    <option value="fr">French</option>
                    <option value="es">Spanish</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="searchTemplate" class="form-label">Search</label>
                <input type="text" class="form-control form-control-sm" id="searchTemplate" placeholder="Search templates...">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="applyFilters()">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                        <i class="fas fa-times me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="refreshTemplates()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Filter Buttons -->
    <div class="category-filter">
        <button class="category-btn active" data-category="all">All Templates</button>
        <button class="category-btn" data-category="notification">Notifications</button>
        <button class="category-btn" data-category="marketing">Marketing</button>
        <button class="category-btn" data-category="transactional">Transactional</button>
        <button class="category-btn" data-category="system">System</button>
        <button class="category-btn" data-category="welcome">Welcome</button>
    </div>

    <!-- Templates Grid -->
    <div class="row" id="templatesGrid">
        <!-- Templates will be loaded here -->
    </div>
</div>

<!-- Create Template Modal -->
<div class="modal fade" id="createTemplateModal" tabindex="-1" aria-labelledby="createTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTemplateModalLabel">Create Email Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createTemplateForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="template_name" id="templateName" required placeholder=" ">
                                <label for="templateName" class="required-field">Template Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="category" id="templateCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="notification">Notification</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="transactional">Transactional</option>
                                    <option value="system">System</option>
                                    <option value="welcome">Welcome</option>
                                </select>
                                <label for="templateCategory" class="required-field">Category</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="subject" id="templateSubject" required placeholder=" ">
                                <label for="templateSubject" class="required-field">Email Subject</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="language" id="templateLanguage" required>
                                    <option value="en">English</option>
                                    <option value="fr">French</option>
                                    <option value="es">Spanish</option>
                                </select>
                                <label for="templateLanguage" class="required-field">Language</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="description" id="templateDescription" placeholder=" " style="height: 60px"></textarea>
                                <label for="templateDescription">Description</label>
                            </div>
                        </div>
                        
                        <!-- Variables Panel -->
                        <div class="col-12">
                            <div class="variables-panel">
                                <div class="variables-title">Available Variables (Click to insert)</div>
                                <div class="variables-grid">
                                    <div class="variable-item" data-variable="@{{first_name}}">First Name</div>
                                    <div class="variable-item" data-variable="@{{last_name}}">Last Name</div>
                                    <div class="variable-item" data-variable="@{{email}}">Email</div>
                                    <div class="variable-item" data-variable="@{{company_name}}">Company</div>
                                    <div class="variable-item" data-variable="@{{current_date}}">Current Date</div>
                                    <div class="variable-item" data-variable="@{{user_role}}">User Role</div>
                                    <div class="variable-item" data-variable="@{{department}}">Department</div>
                                    <div class="variable-item" data-variable="@{{phone}}">Phone</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Email Content Editor -->
                        <div class="col-12">
                            <label for="templateContent" class="form-label required-field">Email Content</label>
                            <textarea id="templateContent" name="content" class="form-control"></textarea>
                        </div>
                        
                        <!-- Template Settings -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="templateStatus">
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="templateStatus">Status</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check" style="margin-top: 20px;">
                                <input class="form-check-input" type="checkbox" id="isDefault" name="is_default">
                                <label class="form-check-label" for="isDefault">
                                    Set as default template for this category
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="previewTemplate()">Preview</button>
                    <button type="submit" class="btn btn-primary">Create Template</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1" aria-labelledby="testEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testEmailModalLabel">Send Test Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="testEmailForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="test-email-panel">
                        <div class="test-email-header">
                            <i class="fas fa-flask test-email-icon"></i>
                            <h6 class="test-email-title">Test Email Configuration</h6>
                        </div>
                        <p class="mb-0 text-muted">Send a test email to verify template rendering and delivery.</p>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="template_id" id="testTemplateId" required>
                                    <option value="">Select Template</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                                <label for="testTemplateId" class="required-field">Template</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="test_email" id="testEmail" required placeholder=" ">
                                <label for="testEmail" class="required-field">Test Email Address</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Test Data (for variable replacement)</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm" name="test_first_name" placeholder="First Name" value="John">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm" name="test_last_name" placeholder="Last Name" value="Doe">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm" name="test_company" placeholder="Company Name" value="GESL">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm" name="test_role" placeholder="User Role" value="Manager">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="includeAnalytics" name="include_analytics" checked>
                                <label class="form-check-label" for="includeAnalytics">
                                    Include email tracking for analytics
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Send Test Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Template Modal -->
<div class="modal fade" id="importTemplateModal" tabindex="-1" aria-labelledby="importTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importTemplateModalLabel">Import Email Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importTemplateForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Import Instructions</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Supported formats: HTML (.html), JSON (.json), ZIP (.zip)</li>
                                    <li>ZIP files can contain multiple templates</li>
                                    <li>JSON format should include template metadata</li>
                                    <li>HTML files will be imported as single templates</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label for="importFile" class="form-label required-field">Template File</label>
                            <div class="input-group">
                                <input type="file" class="form-control" name="template_file" id="importFile" 
                                       accept=".html,.json,.zip" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearImportFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <small>Accepted formats: .html, .json, .zip (Max: 10MB)</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="default_category" id="importCategory">
                                    <option value="">Use File Metadata</option>
                                    <option value="notification">Notification</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="transactional">Transactional</option>
                                    <option value="system">System</option>
                                </select>
                                <label for="importCategory">Default Category</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="default_status" id="importStatus">
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="importStatus">Default Status</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="overwriteExisting" name="overwrite_existing">
                                <label class="form-check-label" for="overwriteExisting">
                                    Overwrite existing templates with same name
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Import Template</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CDN -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Summernote editor
    $('#templateContent').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview']]
        ],
        placeholder: 'Enter your email template content here...',
        callbacks: {
            onInit: function() {
                // Set default content
                $('#templateContent').summernote('code', getDefaultTemplate());
            }
        }
    });

    // Load templates
    loadEmailTemplates();
    updateLastUpdatedTime();

    // Form submissions
    $('#createTemplateForm').on('submit', function(e) {
        e.preventDefault();
        submitTemplate(this);
    });

    $('#testEmailForm').on('submit', function(e) {
        e.preventDefault();
        sendTestEmail(this);
    });

    $('#importTemplateForm').on('submit', function(e) {
        e.preventDefault();
        importTemplate(this);
    });

    // Variable insertion
    $('.variable-item').on('click', function() {
        const variable = $(this).data('variable');
        $('#templateContent').summernote('insertText', variable);
    });

    // Category filter buttons
    $('.category-btn').on('click', function() {
        $('.category-btn').removeClass('active');
        $(this).addClass('active');
        const category = $(this).data('category');
        filterTemplatesByCategory(category);
    });

    // Search functionality
    $('#searchTemplate').on('keyup', function() {
        searchTemplates($(this).val());
    });
});

// Data loading functions
function loadEmailTemplates() {
    const filters = getActiveFilters();
    
    $.ajax({
        url: '/company/Administration/email-templates/data',
        method: 'GET',
        data: filters,
        success: function(response) {
            if (response.success) {
                populateTemplatesGrid(response.data);
                updateStats(response.stats);
                populateTestTemplateSelect(response.data);
            }
        },
        error: function() {
            loadSampleTemplates();
        }
    });
}

function populateTemplatesGrid(templates) {
    let gridHtml = '';
    
    templates.forEach((template, index) => {
        const statusClass = `status-${template.status}`;
        const statusText = template.status.charAt(0).toUpperCase() + template.status.slice(1);
        
        gridHtml += `
            <div class="col-md-6 col-xl-4">
                <div class="template-card" data-template-id="${template.id}">
                    <div class="template-header">
                        <h6 class="template-name">${template.name}</h6>
                        <span class="template-status ${statusClass}">${statusText}</span>
                    </div>
                    <div class="template-body">
                        <div class="template-meta">
                            <div class="template-info">
                                <div>Category: <span class="template-type">${template.category}</span></div>
                                <div>Language: ${template.language.toUpperCase()}</div>
                                <div>Created: ${formatDate(template.created_at)}</div>
                            </div>
                        </div>
                        <div class="template-description">
                            ${template.description || 'No description available'}
                        </div>
                        <div class="template-preview">
                            <strong>Subject:</strong> ${template.subject}<br>
                            <div style="margin-top: 8px;">
                                ${stripHtml(template.content).substring(0, 100)}...
                            </div>
                        </div>
                        <div class="template-actions">
                            <button class="action-btn btn-preview" onclick="previewTemplate(${template.id})">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                            <button class="action-btn btn-edit" onclick="editTemplate(${template.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="action-btn btn-duplicate" onclick="duplicateTemplate(${template.id})">
                                <i class="fas fa-copy"></i> Duplicate
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteTemplate(${template.id})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#templatesGrid').html(gridHtml);
}

function populateTestTemplateSelect(templates) {
    let optionsHtml = '<option value="">Select Template</option>';
    
    templates.forEach(template => {
        if (template.status === 'active') {
            optionsHtml += `<option value="${template.id}">${template.name} (${template.category})</option>`;
        }
    });
    
    $('#testTemplateId').html(optionsHtml);
}

function updateStats(stats) {
    $('#totalTemplates').text(stats?.total || '24');
    $('#activeTemplates').text(stats?.active || '18');
    $('#draftTemplates').text(stats?.draft || '6');
    $('#emailsSent').text(stats?.sent || '1,247');
}

// Form submission functions
function submitTemplate(form) {
    let formData = new FormData(form);
    formData.set('content', $('#templateContent').summernote('code'));
    
    $.ajax({
        url: '/company/Administration/email-templates',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Email template created successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#createTemplateModal').modal('hide');
                form.reset();
                $('#templateContent').summernote('reset');
                loadEmailTemplates();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function sendTestEmail(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/Administration/email-templates/test',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Test Email Sent!',
                    text: 'Test email has been sent successfully.',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#testEmailModal').modal('hide');
                form.reset();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function importTemplate(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/Administration/email-templates/import',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Import Successful!',
                    text: `Imported ${response.data.imported} template(s) successfully.`,
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#importTemplateModal').modal('hide');
                form.reset();
                loadEmailTemplates();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

// Template management functions
function previewTemplate(templateId) {
    if (!templateId) {
        // Preview current template being created
        const content = $('#templateContent').summernote('code');
        const subject = $('#templateSubject').val() || 'Template Subject';
        
        showPreviewModal(subject, content);
    } else {
        // Preview existing template
        $.ajax({
            url: `/company/Administration/email-templates/${templateId}/preview`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    showPreviewModal(response.data.subject, response.data.content);
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to load template preview.', 'error');
            }
        });
    }
}

function showPreviewModal(subject, content) {
    Swal.fire({
        title: 'Email Template Preview',
        html: `
            <div style="text-align: left;">
                <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                    <strong>Subject:</strong> ${subject}
                </div>
                <div style="border: 1px solid #dee2e6; padding: 15px; border-radius: 4px; max-height: 400px; overflow-y: auto;">
                    ${content}
                </div>
            </div>
        `,
        width: 800,
        showCloseButton: true,
        showConfirmButton: false
    });
}

function editTemplate(templateId) {
    $.ajax({
        url: `/company/Administration/email-templates/${templateId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const template = response.data;
                
                // Populate form with template data
                $('#templateName').val(template.name);
                $('#templateCategory').val(template.category);
                $('#templateSubject').val(template.subject);
                $('#templateLanguage').val(template.language);
                $('#templateDescription').val(template.description);
                $('#templateStatus').val(template.status);
                $('#isDefault').prop('checked', template.is_default);
                $('#templateContent').summernote('code', template.content);
                
                // Change modal title and form action for editing
                $('#createTemplateModalLabel').text('Edit Email Template');
                $('#createTemplateForm').attr('data-template-id', templateId);
                
                $('#createTemplateModal').modal('show');
            }
        },
        error: function() {
            Swal.fire('Error', 'Failed to load template data.', 'error');
        }
    });
}

function duplicateTemplate(templateId) {
    Swal.fire({
        title: 'Duplicate Template',
        input: 'text',
        inputLabel: 'New Template Name',
        inputPlaceholder: 'Enter name for duplicated template...',
        showCancelButton: true,
        confirmButtonText: 'Duplicate'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            $.ajax({
                url: `/company/Administration/email-templates/${templateId}/duplicate`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: result.value
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Duplicated!', 'Template has been duplicated successfully.', 'success');
                        loadEmailTemplates();
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to duplicate template.', 'error');
                }
            });
        }
    });
}

function deleteTemplate(templateId) {
    Swal.fire({
        title: 'Delete Template?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/company/Administration/email-templates/${templateId}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Deleted!', 'Template has been deleted.', 'success');
                        loadEmailTemplates();
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to delete template.', 'error');
                }
            });
        }
    });
}

// Utility functions
function getActiveFilters() {
    return {
        category: $('#categoryFilter').val(),
        status: $('#statusFilter').val(),
        language: $('#languageFilter').val(),
        search: $('#searchTemplate').val()
    };
}

function applyFilters() {
    loadEmailTemplates();
}

function resetFilters() {
    $('#categoryFilter').val('all');
    $('#statusFilter').val('all');
    $('#languageFilter').val('all');
    $('#searchTemplate').val('');
    $('.category-btn').removeClass('active');
    $('.category-btn[data-category="all"]').addClass('active');
    loadEmailTemplates();
}

function filterTemplatesByCategory(category) {
    $('#categoryFilter').val(category);
    loadEmailTemplates();
}

function searchTemplates(query) {
    loadEmailTemplates();
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function stripHtml(html) {
    const div = document.createElement('div');
    div.innerHTML = html;
    return div.textContent || div.innerText || '';
}

function updateLastUpdatedTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour12: true, 
        hour: '2-digit', 
        minute: '2-digit' 
    });
    $('#lastUpdated').text(timeString);
}

function handleFormError(xhr) {
    const errors = xhr.responseJSON?.errors || {};
    let errorMessage = 'Please check the form for errors.';
    
    if (Object.keys(errors).length > 0) {
        errorMessage = Object.values(errors).flat().join('<br>');
    }
    
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        html: errorMessage
    });
}

function clearImportFile() {
    $('#importFile').val('');
}

function getDefaultTemplate() {
    return `
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff;">
            <div style="background-color: #007bff; padding: 20px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0;">@{{company_name}}</h1>
            </div>
            <div style="padding: 30px 20px;">
                <h2 style="color: #333333;">Hello @{{first_name}},</h2>
                <p style="color: #666666; line-height: 1.6;">
                    This is your email template content. You can customize this message and use variables like:
                </p>
                <ul style="color: #666666; line-height: 1.6;">
                    <li>@{{first_name}} - Recipient's first name</li>
                    <li>@{{last_name}} - Recipient's last name</li>
                    <li>@{{email}} - Recipient's email address</li>
                    <li>@{{company_name}} - Your company name</li>
                    <li>@{{current_date}} - Current date</li>
                </ul>
                <p style="color: #666666; line-height: 1.6;">
                    Best regards,<br>
                    The @{{company_name}} Team
                </p>
            </div>
            <div style="background-color: #f8f9fa; padding: 20px; text-align: center; color: #999999; font-size: 12px;">
                © @{{current_date}} @{{company_name}}. All rights reserved.
            </div>
        </div>
    `;
}

// Global functions
window.refreshTemplates = function() {
    loadEmailTemplates();
    updateLastUpdatedTime();
};

window.exportAllTemplates = function() {
    window.open('/company/Administration/email-templates/export', '_blank');
};

window.configureDefaults = function() {
    Swal.fire({
        title: 'Default Email Settings',
        html: `
            <div style="text-align: left;">
                <div class="mb-3">
                    <label class="form-label">Default From Name:</label>
                    <input type="text" class="form-control" id="defaultFromName" value="GESL System">
                </div>
                <div class="mb-3">
                    <label class="form-label">Default From Email:</label>
                    <input type="email" class="form-control" id="defaultFromEmail" value="noreply@gesl.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Default Language:</label>
                    <select class="form-select" id="defaultLanguage">
                        <option value="en">English</option>
                        <option value="fr">French</option>
                        <option value="es">Spanish</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Save Settings'
    });
};

window.manageVariables = function() {
    Swal.fire({
        title: 'Manage Template Variables',
        html: `
            <div style="text-align: left;">
                <p>Available template variables:</p>
                <ul>
                    <li><code>@{{first_name}}</code> - User's first name</li>
                    <li><code>@{{last_name}}</code> - User's last name</li>
                    <li><code>@{{email}}</code> - User's email address</li>
                    <li><code>@{{company_name}}</code> - Company name</li>
                    <li><code>@{{current_date}}</code> - Current date</li>
                    <li><code>@{{user_role}}</code> - User's role</li>
                    <li><code>@{{department}}</code> - User's department</li>
                    <li><code>@{{phone}}</code> - User's phone number</li>
                </ul>
                <p class="text-muted">Contact administrator to add custom variables.</p>
            </div>
        `,
        confirmButtonText: 'OK'
    });
};

window.emailSettings = function() {
    Swal.fire({
        title: 'Email Server Settings',
        text: 'Email server configuration should be done in the system settings.',
        icon: 'info'
    });
};

window.viewEmailLogs = function() {
    window.open('/company/Administration/email-logs', '_blank');
};

// Load sample templates for demonstration
function loadSampleTemplates() {
    const sampleTemplates = [
        {
            id: 1,
            name: 'Welcome New User',
            category: 'welcome',
            subject: 'Welcome to @{{company_name}}!',
            language: 'en',
            status: 'active',
            description: 'Welcome email for new user registrations',
            content: '<h1>Welcome @{{first_name}}!</h1><p>Thank you for joining @{{company_name}}.</p>',
            created_at: '2024-01-15'
        },
        {
            id: 2,
            name: 'Password Reset',
            category: 'system',
            subject: 'Reset Your Password',
            language: 'en',
            status: 'active',
            description: 'Password reset instructions',
            content: '<h1>Password Reset</h1><p>Click the link below to reset your password.</p>',
            created_at: '2024-01-10'
        },
        {
            id: 3,
            name: 'Monthly Newsletter',
            category: 'marketing',
            subject: '@{{company_name}} Monthly Update',
            language: 'en',
            status: 'draft',
            description: 'Monthly company newsletter template',
            content: '<h1>Monthly Update</h1><p>Here are this month\'s highlights...</p>',
            created_at: '2024-01-20'
        }
    ];
    
    populateTemplatesGrid(sampleTemplates);
    populateTestTemplateSelect(sampleTemplates);
    updateStats({
        total: 24,
        active: 18,
        draft: 6,
        sent: 1247
    });
}

// Initialize sample data on load
$(document).ready(function() {
    loadSampleTemplates();
});
</script>
@endpush
