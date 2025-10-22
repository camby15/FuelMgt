@extends('layouts.vertical', ['page_title' => 'Category Settings', 'mode' => session('theme_mode', 'light')])

@section('css')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<!-- Select2 for multi-select -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Color Picker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>

<style>
    .settings-container {
        background: #f5f7ff;
        min-height: 100%;
        padding: 1.5rem;
        border-radius: 12px;
    }

    .settings-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid #e3e6f0;
    }

    .settings-card-header {
        border-bottom: 2px solid #f1f3f4;
        padding-bottom: 16px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: between;
    }

    .settings-card-title {
        font-size: 18px;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .settings-card-title i {
        font-size: 20px;
        color: #3b7ddd;
    }

    .setting-group {
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid #f1f3f4;
    }

    .setting-group:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .setting-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }

    .setting-description {
        color: #6b7280;
        font-size: 13px;
        margin-bottom: 12px;
        line-height: 1.5;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #3b7ddd;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        cursor: pointer;
        display: inline-block;
        margin-left: 12px;
        vertical-align: middle;
        transition: all 0.3s ease;
    }

    .color-preview:hover {
        border-color: #3b7ddd;
        transform: scale(1.05);
    }

    .setting-row {
        display: flex;
        align-items: center;
        justify-content: between;
        gap: 16px;
        margin-bottom: 16px;
    }

    .setting-content {
        flex: 1;
    }

    .setting-control {
        flex-shrink: 0;
    }

    .form-range {
        width: 200px;
    }

    .range-value {
        font-weight: 600;
        color: #3b7ddd;
        margin-left: 12px;
        min-width: 40px;
        text-align: center;
    }

    .permission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-top: 16px;
    }

    .permission-item {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: between;
        transition: all 0.3s ease;
    }

    .permission-item:hover {
        background: #e9ecef;
        border-color: #3b7ddd;
    }

    .permission-item.active {
        background: rgba(59, 125, 221, 0.1);
        border-color: #3b7ddd;
    }

    .notification-type {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .save-btn-container {
        position: sticky;
        bottom: 0;
        background: #fff;
        padding: 20px;
        border-top: 1px solid #e3e6f0;
        border-radius: 0 0 12px 12px;
        margin: -24px -24px 0 -24px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .backup-item {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: between;
    }

    .backup-info {
        flex: 1;
    }

    .backup-date {
        font-weight: 600;
        color: #374151;
    }

    .backup-size {
        font-size: 13px;
        color: #6b7280;
    }

    .backup-actions {
        display: flex;
        gap: 8px;
    }

    .import-zone {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        background: #f9fafb;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .import-zone:hover {
        border-color: #3b7ddd;
        background: #f0f4ff;
    }

    .import-zone.dragover {
        border-color: #3b7ddd;
        background: #e0e9ff;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="settings-container">

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Category Settings</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Administration</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/Categories/category-management') }}">Category Management</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value" id="totalSettings">24</div>
            <div class="stat-label">Total Settings</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stat-value" id="activeRules">8</div>
            <div class="stat-label">Active Rules</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="stat-value" id="lastBackup">2</div>
            <div class="stat-label">Days Since Backup</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="stat-value" id="systemHealth">98%</div>
            <div class="stat-label">System Health</div>
        </div>
    </div>

    <form id="categorySettingsForm">
        <!-- General Settings -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <i class="fas fa-cog"></i>
                    General Settings
                </h3>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Enable Category Hierarchy</label>
                        <div class="setting-description">Allow categories to have parent-child relationships and subcategories</div>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input type="checkbox" id="enableHierarchy" name="enable_hierarchy" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Auto-Generate Category Codes</label>
                        <div class="setting-description">Automatically generate unique category codes when creating new categories</div>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input type="checkbox" id="autoGenerateCodes" name="auto_generate_codes" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Category Code Prefix</label>
                        <div class="setting-description">Prefix to use when auto-generating category codes</div>
                    </div>
                    <div class="setting-control">
                        <input type="text" class="form-control" id="codePrefix" name="code_prefix" value="CAT" style="width: 120px;">
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Maximum Hierarchy Depth</label>
                        <div class="setting-description">Maximum number of levels allowed in category hierarchy</div>
                    </div>
                    <div class="setting-control">
                        <input type="range" class="form-range" id="maxDepth" name="max_depth" min="1" max="10" value="5">
                        <span class="range-value" id="maxDepthValue">5</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Settings -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <i class="fas fa-eye"></i>
                    Display Settings
                </h3>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Items Per Page</label>
                        <div class="setting-description">Number of items to display per page in tables</div>
                    </div>
                    <div class="setting-control">
                        <select class="form-select" id="itemsPerPage" name="items_per_page" style="width: 120px;">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Show Category Colors</label>
                        <div class="setting-description">Display color indicators for categories in lists and forms</div>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input type="checkbox" id="showColors" name="show_colors" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Default Department Color</label>
                        <div class="setting-description">Default color for new departments</div>
                    </div>
                    <div class="setting-control">
                        <div class="color-preview" id="deptColorPreview" style="background-color: #28a745;" onclick="openColorPicker('dept')"></div>
                        <input type="hidden" id="deptColor" name="dept_color" value="#28a745">
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Default Category Color</label>
                        <div class="setting-description">Default color for new categories</div>
                    </div>
                    <div class="setting-control">
                        <div class="color-preview" id="catColorPreview" style="background-color: #ffc107;" onclick="openColorPicker('cat')"></div>
                        <input type="hidden" id="catColor" name="cat_color" value="#ffc107">
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Show Statistics Dashboard</label>
                        <div class="setting-description">Display statistics cards on the category management page</div>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input type="checkbox" id="showStats" name="show_stats" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Validation Rules -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <i class="fas fa-shield-alt"></i>
                    Validation Rules
                </h3>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Require Category Descriptions</label>
                        <div class="setting-description">Make description field mandatory when creating categories</div>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input type="checkbox" id="requireDescriptions" name="require_descriptions">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Unique Category Names</label>
                        <div class="setting-description">Ensure category names are unique across the system</div>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input type="checkbox" id="uniqueNames" name="unique_names" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Minimum Name Length</label>
                        <div class="setting-description">Minimum number of characters required for category names</div>
                    </div>
                    <div class="setting-control">
                        <input type="range" class="form-range" id="minNameLength" name="min_name_length" min="2" max="20" value="3">
                        <span class="range-value" id="minNameLengthValue">3</span>
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Maximum Name Length</label>
                        <div class="setting-description">Maximum number of characters allowed for category names</div>
                    </div>
                    <div class="setting-control">
                        <input type="range" class="form-range" id="maxNameLength" name="max_name_length" min="10" max="200" value="100">
                        <span class="range-value" id="maxNameLengthValue">100</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <i class="fas fa-users-cog"></i>
                    User Permissions
                </h3>
            </div>

            <div class="setting-group">
                <label class="setting-label">Category Management Permissions</label>
                <div class="setting-description">Configure which user roles can perform specific actions</div>
                
                <div class="permission-grid">
                    <div class="permission-item active">
                        <div>
                            <strong>Create Categories</strong>
                            <br><small>Add new categories to the system</small>
                        </div>
                        <div>
                            <input type="checkbox" class="form-check-input" name="permissions[]" value="create_categories" checked>
                        </div>
                    </div>
                    
                    <div class="permission-item active">
                        <div>
                            <strong>Edit Categories</strong>
                            <br><small>Modify existing category information</small>
                        </div>
                        <div>
                            <input type="checkbox" class="form-check-input" name="permissions[]" value="edit_categories" checked>
                        </div>
                    </div>
                    
                    <div class="permission-item">
                        <div>
                            <strong>Delete Categories</strong>
                            <br><small>Remove categories from the system</small>
                        </div>
                        <div>
                            <input type="checkbox" class="form-check-input" name="permissions[]" value="delete_categories">
                        </div>
                    </div>
                    
                    <div class="permission-item active">
                        <div>
                            <strong>View Categories</strong>
                            <br><small>Access category information and lists</small>
                        </div>
                        <div>
                            <input type="checkbox" class="form-check-input" name="permissions[]" value="view_categories" checked>
                        </div>
                    </div>
                    
                    <div class="permission-item">
                        <div>
                            <strong>Bulk Operations</strong>
                            <br><small>Perform bulk imports and exports</small>
                        </div>
                        <div>
                            <input type="checkbox" class="form-check-input" name="permissions[]" value="bulk_operations">
                        </div>
                    </div>
                    
                    <div class="permission-item">
                        <div>
                            <strong>Manage Settings</strong>
                            <br><small>Access and modify system settings</small>
                        </div>
                        <div>
                            <input type="checkbox" class="form-check-input" name="permissions[]" value="manage_settings">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <i class="fas fa-bell"></i>
                    Notification Settings
                </h3>
            </div>

            <div class="setting-group">
                <label class="setting-label">Email Notifications</label>
                <div class="setting-description">Configure when to send email notifications for category events</div>
                
                <div class="notification-type">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>New Category Created</strong>
                            <br><small>Notify administrators when new categories are added</small>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="notify_new_category" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                
                <div class="notification-type">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Category Modified</strong>
                            <br><small>Notify when existing categories are updated</small>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="notify_category_modified">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                
                <div class="notification-type">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Category Deleted</strong>
                            <br><small>Notify when categories are removed from the system</small>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="notify_category_deleted" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Notification Recipients</label>
                        <div class="setting-description">Select who should receive category notifications</div>
                    </div>
                    <div class="setting-control">
                        <select class="form-select" id="notificationRecipients" name="notification_recipients[]" multiple style="width: 300px;">
                            <option value="admin" selected>System Administrators</option>
                            <option value="hr" selected>HR Department</option>
                            <option value="managers">Department Managers</option>
                            <option value="users">All Users</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup & Import -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <i class="fas fa-database"></i>
                    Backup & Import
                </h3>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Auto Backup</label>
                        <div class="setting-description">Automatically backup category data at regular intervals</div>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input type="checkbox" id="autoBackup" name="auto_backup" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <div class="setting-row">
                    <div class="setting-content">
                        <label class="setting-label">Backup Frequency</label>
                        <div class="setting-description">How often to create automatic backups</div>
                    </div>
                    <div class="setting-control">
                        <select class="form-select" id="backupFrequency" name="backup_frequency" style="width: 150px;">
                            <option value="daily" selected>Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <label class="setting-label">Manual Backup</label>
                <div class="setting-description">Create a backup of all category data immediately</div>
                <button type="button" class="btn btn-outline-primary" onclick="createBackup()">
                    <i class="fas fa-download me-1"></i> Create Backup Now
                </button>
            </div>

            <div class="setting-group">
                <label class="setting-label">Recent Backups</label>
                <div class="setting-description">View and manage recent backup files</div>
                
                <div class="backup-item">
                    <div class="backup-info">
                        <div class="backup-date">2024-01-15 14:30:00</div>
                        <div class="backup-size">2.4 MB • 1,245 records</div>
                    </div>
                    <div class="backup-actions">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="downloadBackup('backup1')">
                            <i class="fas fa-download"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="restoreBackup('backup1')">
                            <i class="fas fa-upload"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBackup('backup1')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="backup-item">
                    <div class="backup-info">
                        <div class="backup-date">2024-01-14 14:30:00</div>
                        <div class="backup-size">2.3 MB • 1,238 records</div>
                    </div>
                    <div class="backup-actions">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="downloadBackup('backup2')">
                            <i class="fas fa-download"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="restoreBackup('backup2')">
                            <i class="fas fa-upload"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBackup('backup2')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="setting-group">
                <label class="setting-label">Import Categories</label>
                <div class="setting-description">Import category data from CSV or Excel files</div>
                
                <div class="import-zone" onclick="document.getElementById('importFile').click()">
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                    <h5>Drop files here or click to upload</h5>
                    <p class="text-muted">Supports CSV, Excel (.xlsx, .xls) files</p>
                    <input type="file" id="importFile" name="import_file" accept=".csv,.xlsx,.xls" style="display: none;" onchange="handleFileSelect(this)">
                </div>
            </div>
        </div>

        <!-- Save Settings -->
        <div class="save-btn-container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Settings are automatically saved when changed
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="resetToDefaults()">
                        <i class="fas fa-undo me-1"></i> Reset to Defaults
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save All Settings
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('#notificationRecipients').select2({
        placeholder: 'Select recipients...',
        allowClear: true
    });

    // Initialize range sliders
    initializeRangeSliders();
    
    // Setup form submission
    setupFormSubmission();
    
    // Setup drag and drop
    setupDragAndDrop();
    
    // Auto-save functionality
    setupAutoSave();
});

// Range slider functionality
function initializeRangeSliders() {
    const ranges = [
        { input: 'maxDepth', output: 'maxDepthValue' },
        { input: 'minNameLength', output: 'minNameLengthValue' },
        { input: 'maxNameLength', output: 'maxNameLengthValue' }
    ];

    ranges.forEach(range => {
        const input = document.getElementById(range.input);
        const output = document.getElementById(range.output);
        
        input.addEventListener('input', function() {
            output.textContent = this.value;
        });
    });
}

// Color picker functionality
let colorPickers = {};

function openColorPicker(type) {
    const elementId = type + 'ColorPreview';
    const inputId = type + 'Color';
    
    if (colorPickers[type]) {
        colorPickers[type].show();
        return;
    }

    colorPickers[type] = Pickr.create({
        el: '#' + elementId,
        theme: 'classic',
        default: document.getElementById(inputId).value,
        components: {
            preview: true,
            opacity: true,
            hue: true,
            interaction: {
                hex: true,
                rgba: true,
                hsla: true,
                hsva: true,
                cmyk: true,
                input: true,
                clear: true,
                save: true
            }
        }
    });

    colorPickers[type].on('save', (color, instance) => {
        const hexColor = color.toHEXA().toString();
        document.getElementById(inputId).value = hexColor;
        document.getElementById(elementId).style.backgroundColor = hexColor;
        instance.hide();
        
        // Trigger auto-save
        autoSaveSettings();
    });
}

// Form submission
function setupFormSubmission() {
    $('#categorySettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Show loading
        Swal.fire({
            title: 'Saving Settings...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Simulate AJAX call - replace with actual endpoint
        setTimeout(() => {
            console.log('Saving settings:', Object.fromEntries(formData));
            
            Swal.fire({
                icon: 'success',
                title: 'Settings Saved!',
                text: 'Your category settings have been updated successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1500);
    });
}

// Auto-save functionality
function setupAutoSave() {
    // Auto-save on toggle changes
    $('input[type="checkbox"]').on('change', function() {
        autoSaveSettings();
        updatePermissionItems();
    });
    
    // Auto-save on select changes
    $('select').on('change', function() {
        autoSaveSettings();
    });
    
    // Auto-save on text input changes (with debounce)
    let autoSaveTimeout;
    $('input[type="text"], textarea').on('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(autoSaveSettings, 1000);
    });
}

function autoSaveSettings() {
    // Show subtle save indicator
    const saveIndicator = $('<span class="text-success ms-2"><i class="fas fa-check"></i> Saved</span>');
    $('.save-btn-container').find('.text-success').remove();
    $('.save-btn-container .text-muted').after(saveIndicator);
    
    setTimeout(() => {
        saveIndicator.fadeOut();
    }, 2000);
    
    // Here you would make an AJAX call to save settings
    console.log('Auto-saving settings...');
}

// Update permission item styling
function updatePermissionItems() {
    $('.permission-item').each(function() {
        const checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.is(':checked')) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    });
}

// Drag and drop functionality
function setupDragAndDrop() {
    const importZone = document.querySelector('.import-zone');
    
    importZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    importZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    importZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelect({files: files});
        }
    });
}

// File handling
function handleFileSelect(input) {
    const files = input.files || input;
    const file = files[0];
    
    if (file) {
        const validTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        
        if (validTypes.includes(file.type) || file.name.match(/\.(csv|xlsx|xls)$/i)) {
            Swal.fire({
                title: 'Import File',
                text: `Ready to import "${file.name}" (${(file.size/1024/1024).toFixed(2)} MB)`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Import',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    importFile(file);
                }
            });
        } else {
            Swal.fire('Error', 'Please select a valid CSV or Excel file.', 'error');
        }
    }
}

function importFile(file) {
    // Simulate file import
    Swal.fire({
        title: 'Importing...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Import Complete!',
            text: 'Successfully imported 150 categories.',
            timer: 3000,
            showConfirmButton: false
        });
    }, 2000);
}

// Backup functions
function createBackup() {
    Swal.fire({
        title: 'Creating Backup...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Backup Created!',
            text: 'Category data backup has been created successfully.',
            timer: 2000,
            showConfirmButton: false
        });
    }, 1500);
}

function downloadBackup(backupId) {
    console.log('Downloading backup:', backupId);
    Swal.fire('Download', 'Backup download started...', 'info');
}

function restoreBackup(backupId) {
    Swal.fire({
        title: 'Restore Backup',
        text: 'This will replace all current category data. Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, restore it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Restoring backup:', backupId);
            Swal.fire('Restored!', 'Backup has been restored successfully.', 'success');
        }
    });
}

function deleteBackup(backupId) {
    Swal.fire({
        title: 'Delete Backup',
        text: 'Are you sure you want to delete this backup?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Deleting backup:', backupId);
            Swal.fire('Deleted!', 'Backup has been deleted.', 'success');
        }
    });
}

// Reset to defaults
function resetToDefaults() {
    Swal.fire({
        title: 'Reset to Defaults',
        text: 'This will reset all settings to their default values. Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, reset!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Reset form to default values
            document.getElementById('categorySettingsForm').reset();
            
            // Reset specific elements
            document.getElementById('enableHierarchy').checked = true;
            document.getElementById('autoGenerateCodes').checked = true;
            document.getElementById('maxDepth').value = 5;
            document.getElementById('maxDepthValue').textContent = '5';
            
            Swal.fire('Reset Complete!', 'All settings have been reset to defaults.', 'success');
        }
    });
}

// Initialize permission items styling
updatePermissionItems();
</script>
@endsection
