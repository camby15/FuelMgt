@extends('layouts.vertical', ['page_title' => 'Category Management', 'mode' => session('theme_mode', 'light')])

@php
use Illuminate\Support\Str;
@endphp

@section('css')
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
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

<style>
    .category-container {
        background: #f5f7ff;
        min-height: 100%;
        padding: 1.5rem;
        border-radius: 12px;
    }

    /* Tabs Styling */
    .category-tabs {
        background: #fff;
        border-radius: 10px 10px 0 0;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border-bottom: 1px solid #e3e6f0;
    }

    .category-tabs .nav-link {
        color: #5a6c7d;
        font-weight: 600;
        padding: 20px 25px;
        border: none;
        border-radius: 0;
        transition: all 0.3s ease;
        position: relative;
        margin: 0;
    }

    .category-tabs .nav-link:hover {
        color: #3b7ddd;
        background-color: rgba(59, 125, 221, 0.1);
    }

    .category-tabs .nav-link.active {
        color: #3b7ddd;
        background-color: #fff;
        border-bottom: 3px solid #3b7ddd;
    }

    .category-tabs .nav-link i {
        margin-right: 8px;
        font-size: 16px;
    }

    /* Tab Content */
    .tab-content {
        background: #fff;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        padding: 25px;
        min-height: 600px;
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
    .card-departments { border-left-color: #28a745; }
    .card-categories { border-left-color: #ffc107; }
    .card-sectors { border-left-color: #17a2b8; }
    .card-types { border-left-color: #dc3545; }

    /* Action buttons */
    .action-btn {
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 0 2px;
    }
    
    .action-btn:hover {
        opacity: 0.9 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }
    
    .action-btn i {
        font-size: 14px;
    }

    /* Status badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active { background-color: #d4edda; color: #155724; }
    .status-inactive { background-color: #f8d7da; color: #721c24; }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
    }

    .modal-xl {
        max-width: 1200px;
    }

    .required-field::after {
        content: " *";
        color: #e74c3c;
        font-weight: bold;
    }

    .subdepartment-item {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 8px 12px;
        margin: 4px 0;
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .subdepartment-item .remove-btn {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 3px;
        transition: all 0.2s ease;
    }

    .subdepartment-item .remove-btn:hover {
        background-color: #dc3545;
        color: white;
    }

    /* View modal specific styles */
    .subdepartment-item-view {
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }

    .subdepartment-item-view:hover {
        background-color: #f8f9fa !important;
        transform: translateX(5px);
    }

    #viewDepartmentModal .modal-header {
        border-bottom: 3px solid #0d6efd;
    }

    #viewDepartmentModal .card-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
        border-bottom: 1px solid #dee2e6;
    }

    #viewDepartmentModal .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    #viewDepartmentModal .fw-bold {
        color: #212529;
    }

    #viewDepartmentModal .text-muted.small {
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Search and filter styles */
    .search-input-container {
        position: relative;
    }

    .search-clear-btn {
        position: absolute;
        right: 35px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: none;
        color: #6c757d;
        cursor: pointer;
        z-index: 10;
        padding: 4px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .search-clear-btn:hover {
        color: #dc3545;
        background-color: #f8f9fa;
    }

    .search-clear-btn:active {
        transform: translateY(-50%) scale(0.95);
    }

    /* Filter button enhancements */
    .filter-btn {
        transition: all 0.2s ease;
        border-width: 2px;
    }

    .filter-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .clear-btn:hover {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
    }

    /* Export dropdown styles */
    .btn-group .dropdown-menu {
        border: 1px solid #dee2e6;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 0.375rem;
    }

    .btn-group .dropdown-item {
        transition: all 0.2s ease;
        padding: 0.5rem 1rem;
    }

    .btn-group .dropdown-item:hover {
        background-color: #f8f9fa;
        transform: translateX(2px);
    }

    .btn-group .dropdown-item i {
        width: 16px;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="category-container">

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Category Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Administration</a></li>
                        <li class="breadcrumb-item active">Category Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-categories">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h6 class="card-title">Categories</h6>
                    <h3 class="card-value" id="totalCategories">{{ $stats['total'] ?? 0 }}</h3>
                    <div class="card-change">
                        <i class="fas fa-tag me-1"></i> <span id="activeCategories">{{ $stats['active'] ?? 0 }}</span> Active
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-departments">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <i class="fas fa-building"></i>
                    </div>
                    <h6 class="card-title">Departments</h6>
                    <h3 class="card-value" id="totalDepartments">{{ $departmentStats['total'] ?? 0 }}</h3>
                    <div class="card-change">
                        <i class="fas fa-check-circle me-1"></i> <span id="activeDepartments">{{ $departmentStats['active'] ?? 0 }}</span> Active
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-sectors">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h6 class="card-title">Business Sectors</h6>
                    <h3 class="card-value" id="totalBusinessSectors">{{ $businessSectorStats['total'] ?? 0 }}</h3>
                    <div class="card-change">
                        <i class="fas fa-check-circle me-1"></i> <span id="activeBusinessSectors">{{ $businessSectorStats['active'] ?? 0 }}</span> Active
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-total">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                        <i class="fas fa-database"></i>
                    </div>
                    <h6 class="card-title">Total Records</h6>
                    <h3 class="card-value" id="totalRecords">{{ ($stats['total'] ?? 0) + ($departmentStats['total'] ?? 0) + ($businessSectorStats['total'] ?? 0) }}</h3>
                    <div class="card-change">
                        <i class="fas fa-chart-line me-1"></i> <span id="totalActiveRecords">{{ ($stats['active'] ?? 0) + ($departmentStats['active'] ?? 0) + ($businessSectorStats['active'] ?? 0) }}</span> Active
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 me-3">Quick Actions:</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshAllStats()">
                                    <i class="fas fa-sync-alt me-1"></i> Refresh Stats
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="switchToActiveTab('categories')">
                                    <i class="fas fa-tags me-1"></i> View Categories
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="switchToActiveTab('departments')">
                                    <i class="fas fa-building me-1"></i> View Departments
                                </button>
                             
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="switchToActiveTab('sectors')">
                                    <i class="fas fa-industry me-1"></i> View Sectors
                                </button>
                                <button type="button" class="btn btn-outline-dark btn-sm" onclick="exportAllData()">
                                    <i class="fas fa-download me-1"></i> Export All
                                </button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <small><i class="fas fa-info-circle me-1"></i> Last updated: <span id="lastUpdated">Loading...</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs category-tabs" id="categoryTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ (!isset($activeTab) || $activeTab == 'categories') ? 'active' : '' }}" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="{{ (!isset($activeTab) || $activeTab == 'categories') ? 'true' : 'false' }}">
                <i class="fas fa-tags"></i>Categories
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ isset($activeTab) && $activeTab == 'departments' ? 'active' : '' }}" id="departments-tab" data-bs-toggle="tab" data-bs-target="#departments" type="button" role="tab" aria-controls="departments" aria-selected="{{ isset($activeTab) && $activeTab == 'departments' ? 'true' : 'false' }}">
                <i class="fas fa-building"></i>Departments
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ isset($activeTab) && $activeTab == 'sectors' ? 'active' : '' }}" id="sectors-tab" data-bs-toggle="tab" data-bs-target="#sectors" type="button" role="tab" aria-controls="sectors" aria-selected="{{ isset($activeTab) && $activeTab == 'sectors' ? 'true' : 'false' }}">
                <i class="fas fa-industry"></i>Business Sectors
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="categoryTabsContent">
        
        <!-- Departments Tab -->
        <div class="tab-pane fade {{ isset($activeTab) && $activeTab == 'departments' ? 'show active' : '' }}" id="departments" role="tabpanel" aria-labelledby="departments-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Department Management</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                        <i class="fas fa-plus me-1"></i> Add Department
                    </button>
                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#bulkUploadDepartmentsModal" onclick="console.log('Bulk upload button clicked')">
                        <i class="fas fa-upload me-1"></i> Bulk Upload
                    </button>
                    <!-- Export Buttons -->
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success btn-sm" onclick="exportDepartments('csv')">
                            <i class="fas fa-file-csv me-1"></i> CSV
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="exportDepartments('excel')">
                            <i class="fas fa-file-excel me-1"></i> Excel
                        </button>
                    </div>
                    
                    <!-- Debug Button -->
                    <button type="button" class="btn btn-warning btn-sm" onclick="testBulkUploadModal()">
                        <i class="fas fa-bug me-1"></i> Test Modal
                    </button>
                    
                </div>
            </div>

            <!-- Search and Filter for Departments -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="input-group search-input-container">
                        <input type="text" id="searchDepartments" class="form-control" placeholder="Search departments..." style="height: 38px;">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="filterDepartmentStatus">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-info w-100" onclick="showAdvancedSearch()" title="Advanced search options">
                        <i class="fas fa-search-plus me-1"></i> Advanced
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-primary w-100 filter-btn" onclick="applyDepartmentFilters()">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100 clear-btn" onclick="clearDepartmentFilters()">
                        <i class="fas fa-times me-1"></i> Clear
                    </button>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-dark w-100" onclick="showSearchHelp()" title="Search tips and shortcuts">
                        <i class="fas fa-question"></i>
                    </button>
                </div>
            </div>

            <!-- Departments Table -->
            <div class="table-responsive">
                <table class="table table-centered table-hover dt-responsive nowrap w-100" id="departments-datatable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Department Name</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Head of Department</th>
                            <th>Sub Departments</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments ?? [] as $department)
                            <tr>
                                <td>{{ $department->id }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->code }}</td>
                                <td>{{ $department->description ? Str::limit($department->description, 100) : '-' }}</td>
                                <td>{{ $department->head_name ?: '-' }}</td>
                                <td>
                                    @if($department->hasSubDepartments())
                                        <span class="badge bg-info">{{ $department->sub_departments_count }} sub departments</span>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>
                                    @if($department->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary action-btn" onclick="viewDepartment({{ $department->id }})" title="View" data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning action-btn" onclick="editDepartment({{ $department->id }})" title="Edit" data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger action-btn" onclick="deleteDepartment({{ $department->id }})" title="Delete" data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No departments found</h5>
                                        <p class="text-muted mb-3">Get started by adding your first department</p>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                                            <i class="fas fa-plus me-1"></i> Add Department
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Categories Tab -->
        <div class="tab-pane fade {{ (!isset($activeTab) || $activeTab == 'categories') ? 'show active' : '' }}" id="categories" role="tabpanel" aria-labelledby="categories-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Category Management</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fas fa-plus me-1"></i> Add Category
                    </button>
                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#bulkUploadCategoriesModal">
                        <i class="fas fa-upload me-1"></i> Bulk Upload
                    </button>
                    <!-- Export Buttons -->
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success btn-sm" onclick="exportCategories('csv')">
                            <i class="fas fa-file-csv me-1"></i> CSV
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="exportCategories('excel')">
                            <i class="fas fa-file-excel me-1"></i> Excel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search and Filter for Categories -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="input-group search-input-container">
                        <input type="text" id="searchCategories" class="form-control" placeholder="Search categories..." style="height: 38px;">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="filterCategoryStatus">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-info w-100" onclick="showAdvancedSearchCategories()" title="Advanced search options">
                        <i class="fas fa-search-plus me-1"></i> Advanced
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-primary w-100 filter-btn" onclick="applyCategoryFilters()">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100 clear-btn" onclick="clearCategoryFilters()">
                        <i class="fas fa-times me-1"></i> Clear
                    </button>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-dark w-100" onclick="showSearchHelp()" title="Search tips and shortcuts">
                        <i class="fas fa-question"></i>
                    </button>
                </div>
            </div>

            <!-- Categories Table -->
            <div class="table-responsive">
                <table class="table table-centered table-hover dt-responsive nowrap w-100" id="categories-datatable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Head of Category</th>
                            <th>Sub Categories</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($categories))
                            @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->code }}</td>
                                <td>{{ $category->description ? Str::limit($category->description, 100) : '-' }}</td>
                                <td>{{ $category->head_name ?: '-' }}</td>
                                <td>
                                        @if(method_exists($category, 'hasSubCategories') && $category->hasSubCategories())
                                        <span class="badge bg-info">{{ $category->sub_categories_count }} sub categories</span>
                                        @elseif(is_array($category->sub_categories) && count($category->sub_categories) > 0)
                                            <span class="badge bg-info">{{ count($category->sub_categories) }} sub categories</span>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary action-btn" onclick="viewCategory({{ $category->id }})" title="View" data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning action-btn" onclick="editCategory({{ $category->id }})" title="Edit" data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger action-btn" onclick="deleteCategory({{ $category->id }})" title="Delete" data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No categories found</h5>
                                        <p class="text-muted mb-3">Get started by adding your first category</p>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                            <i class="fas fa-plus me-1"></i> Add Category
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        @else
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h5 class="text-muted">Categories data not available</h5>
                                        <p class="text-muted mb-3">There seems to be an issue loading categories data</p>
                                        <button class="btn btn-primary btn-sm" onclick="window.location.reload()">
                                            <i class="fas fa-refresh me-1"></i> Reload Page
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Departments Tab -->
        <div class="tab-pane fade" id="departments" role="tabpanel" aria-labelledby="departments-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Department Management</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                        <i class="fas fa-plus me-1"></i> Add Department
                    </button>
                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#bulkUploadDepartmentsModal">
                        <i class="fas fa-upload me-1"></i> Bulk Upload
                    </button>
                    <!-- Export Buttons -->
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success btn-sm" onclick="exportDepartments('csv')">
                            <i class="fas fa-file-csv me-1"></i> CSV
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="exportDepartments('excel')">
                            <i class="fas fa-file-excel me-1"></i> Excel
                    </button>
                    </div>
                </div>
            </div>

            <!-- Search and Filter for Departments -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="input-group search-input-container">
                        <input type="text" id="searchDepartments" class="form-control" placeholder="Search departments..." style="height: 38px;">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="filterDepartmentStatus">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-info w-100" onclick="showAdvancedSearch()" title="Advanced search options">
                        <i class="fas fa-search-plus me-1"></i> Advanced
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-primary w-100 filter-btn" onclick="applyDepartmentFilters()">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100 clear-btn" onclick="clearDepartmentFilters()">
                        <i class="fas fa-times me-1"></i> Clear
                    </button>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-dark w-100" onclick="showSearchHelp()" title="Search tips and shortcuts">
                        <i class="fas fa-question"></i>
                    </button>
                </div>
            </div>

            <!-- Departments Table -->
            <div class="table-responsive">
                <table class="table table-centered table-hover dt-responsive nowrap w-100" id="departments-datatable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Department Name</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Head of Department</th>
                            <th>Sub Departments</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($departments))
                            @forelse($departments as $department)
                                <tr>
                                    <td>{{ $department->id }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>{{ $department->code }}</td>
                                    <td>{{ $department->description ? Str::limit($department->description, 100) : '-' }}</td>
                                    <td>{{ $department->head_name ?: '-' }}</td>
                                    <td>
                                        @if(method_exists($department, 'hasSubDepartments') && $department->hasSubDepartments())
                                            <span class="badge bg-info">{{ $department->sub_departments_count }} sub departments</span>
                                        @elseif(is_array($department->sub_departments) && count($department->sub_departments) > 0)
                                            <span class="badge bg-info">{{ count($department->sub_departments) }} sub departments</span>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($department->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary action-btn" onclick="viewDepartment({{ $department->id }})" title="View" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning action-btn" onclick="editDepartment({{ $department->id }})" title="Edit" data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger action-btn" onclick="deleteDepartment({{ $department->id }})" title="Delete" data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No departments found</h5>
                                            <p class="text-muted mb-3">Get started by adding your first department</p>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                                                <i class="fas fa-plus me-1"></i> Add Department
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h5 class="text-muted">Departments data not available</h5>
                                        <p class="text-muted mb-3">There seems to be an issue loading departments data</p>
                                        <button class="btn btn-primary btn-sm" onclick="window.location.reload()">
                                            <i class="fas fa-refresh me-1"></i> Reload Page
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Business Sectors Tab -->
        <div class="tab-pane fade {{ isset($activeTab) && $activeTab == 'sectors' ? 'show active' : '' }}" id="sectors" role="tabpanel" aria-labelledby="sectors-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Business Sector Management</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSectorModal">
                        <i class="fas fa-plus me-1"></i> Add Sector
                    </button>
                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#bulkUploadSectorsModal">
                        <i class="fas fa-upload me-1"></i> Bulk Upload
                    </button>
                    <!-- Export Buttons -->
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success btn-sm" onclick="exportSectors('csv')">
                            <i class="fas fa-file-csv me-1"></i> CSV
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="exportSectors('excel')">
                            <i class="fas fa-file-excel me-1"></i> Excel
                    </button>
                    </div>
                </div>
            </div>

            <!-- Search and Filter for Business Sectors -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="input-group search-input-container">
                        <input type="text" id="searchSectors" class="form-control" placeholder="Search business sectors..." style="height: 38px;">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="filterSectorStatus">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-info w-100" onclick="showAdvancedSearchSectors()" title="Advanced search options">
                        <i class="fas fa-search-plus me-1"></i> Advanced
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-primary w-100 filter-btn" onclick="applySectorFilters()">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100 clear-btn" onclick="clearSectorFilters()">
                        <i class="fas fa-times me-1"></i> Clear
                    </button>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-dark w-100" onclick="showSearchHelp()" title="Search tips and shortcuts">
                        <i class="fas fa-question"></i>
                    </button>
                </div>
            </div>

            <!-- Sectors Table -->
            <div class="table-responsive">
                <table class="table table-centered table-hover dt-responsive nowrap w-100" id="sectors-datatable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Sector Name</th>
                            <th>Description</th>
                            <th>Head Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($businessSectors))
                            @forelse($businessSectors as $sector)
                                <tr>
                                    <td>{{ $sector->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $sector->name }}</h6>
                                                @if($sector->sub_sectors_count > 0)
                                                    <small class="text-muted">{{ $sector->sub_sectors_count }} sub-sector(s)</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $sector->description ?? '-' }}</td>
                                    <td>{{ $sector->head_name ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $sector->status_badge_class }}">
                                            {{ $sector->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info action-btn" onclick="viewSector({{ $sector->id }})" title="View" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary action-btn" onclick="editSector({{ $sector->id }})" title="Edit" data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger action-btn" onclick="deleteSector({{ $sector->id }})" title="Delete" data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-industry fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No business sectors found</h5>
                                            <p class="text-muted mb-3">Get started by adding your first business sector</p>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSectorModal">
                                                <i class="fas fa-plus me-1"></i> Add Business Sector
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-industry fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Business sectors data not available</h5>
                                        <p class="text-muted">Please refresh the page or contact support if the issue persists.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</div>

<!-- MODALS START HERE -->

<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDepartmentModalLabel">Add New Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addDepartmentForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="departmentName" name="name" placeholder="Department Name" required>
                                <label for="departmentName" class="required-field">Department Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="departmentCode" name="code" placeholder="Department Code" required>
                                <label for="departmentCode" class="required-field">Department Code</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="departmentDescription" name="description" placeholder="Description" style="height: 100px;"></textarea>
                        <label for="departmentDescription">Description</label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="departmentHead" name="head_name" placeholder="Department Head Name">
                                <label for="departmentHead">Head of Department</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="departmentStatus" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="departmentStatus" class="required-field">Status</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sub Departments</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="subDepartmentInput" placeholder="Enter sub department name">
                            <button type="button" class="btn btn-outline-primary" onclick="addSubDepartment()">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <div id="subDepartmentsList" class="mt-2">
                            <!-- Sub departments will be displayed here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Department Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDepartmentForm">
                <input type="hidden" id="editDepartmentId" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="editDepartmentName" name="name" placeholder="Department Name" required>
                                <label for="editDepartmentName" class="required-field">Department Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="editDepartmentCode" name="code" placeholder="Department Code" required>
                                <label for="editDepartmentCode" class="required-field">Department Code</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="editDepartmentDescription" name="description" placeholder="Description" style="height: 100px;"></textarea>
                        <label for="editDepartmentDescription">Description</label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="editDepartmentHead" name="head_name" placeholder="Department Head Name">
                                <label for="editDepartmentHead">Head of Department</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="editDepartmentStatus" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="editDepartmentStatus" class="required-field">Status</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sub Departments</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="editSubDepartmentInput" placeholder="Enter sub department name">
                            <button type="button" class="btn btn-outline-primary" onclick="addSubDepartmentEdit()">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <div id="editSubDepartmentsList" class="mt-2">
                            <!-- Sub departments will be displayed here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Department Modal -->
<div class="modal fade" id="viewDepartmentModal" tabindex="-1" aria-labelledby="viewDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewDepartmentModalLabel">
                    <i class="fas fa-eye me-2"></i>Department Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Department Name</label>
                                    <div class="fw-bold" id="viewDepartmentName">-</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Department Code</label>
                                    <div class="fw-bold" id="viewDepartmentCode">-</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Status</label>
                                    <div id="viewDepartmentStatus">-</div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label text-muted small">Head of Department</label>
                                    <div class="fw-bold" id="viewDepartmentHead">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Additional Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Department ID</label>
                                    <div class="fw-bold" id="viewDepartmentId">-</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Sort Order</label>
                                    <div class="fw-bold" id="viewDepartmentSortOrder">-</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Created Date</label>
                                    <div class="fw-bold" id="viewDepartmentCreated">-</div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label text-muted small">Last Updated</label>
                                    <div class="fw-bold" id="viewDepartmentUpdated">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-file-text me-2"></i>Description</h6>
                    </div>
                    <div class="card-body">
                        <div id="viewDepartmentDescription" class="text-muted">No description provided</div>
                    </div>
                </div>

                <!-- Sub Departments -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-sitemap me-2"></i>Sub Departments</h6>
                    </div>
                    <div class="card-body">
                        <div id="viewSubDepartmentsList">
                            <div class="text-muted">No sub departments</div>
                        </div>
                    </div>
                </div>

                <!-- Created/Updated By -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="text-muted small">Created By</div>
                            <div class="fw-bold" id="viewDepartmentCreatedBy">-</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="text-muted small">Updated By</div>
                            <div class="fw-bold" id="viewDepartmentUpdatedBy">-</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" onclick="editDepartmentFromView()">
                    <i class="fas fa-edit me-1"></i>Edit Department
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Upload Departments Modal -->
<div class="modal fade" id="bulkUploadDepartmentsModal" tabindex="-1" aria-labelledby="bulkUploadDepartmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUploadDepartmentsModalLabel">
                    <i class="fas fa-upload me-2"></i>Bulk Upload Departments
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('department-categories.import') }}" method="POST" enctype="multipart/form-data" id="bulkUploadDepartmentsForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        Download the template first and fill it with your department data. Make sure to follow the format exactly.
                        <div class="mt-2">
                            <a href="{{ route('department-categories.template.download') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i>Download Template
                            </a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="department_bulk_upload_file" class="form-label">
                            <i class="fas fa-file-csv me-1"></i>Choose CSV/Excel File
                        </label>
                        <input type="file" class="form-control" id="department_bulk_upload_file" name="file" 
                               accept=".csv,.xlsx,.xls" required>
                        <div class="form-text">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Accepted formats: .csv, .xlsx, .xls (max 10MB)
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-lightbulb me-1"></i>Template Instructions:</h6>
                        <ul class="mb-0 small">
                            <li><strong>Department Name:</strong> Required (max 255 characters)</li>
                            <li><strong>Department Code:</strong> Optional (will auto-generate if empty)</li>
                            <li><strong>Description:</strong> Optional description</li>
                            <li><strong>Head of Department Email:</strong> Email (looks up user) OR direct name (e.g., "John Smith")</li>
                            <li><strong>Status:</strong> active or inactive (defaults to active)</li>
                            <li><strong>Color:</strong> Hex color code (e.g., #3b7ddd)</li>
                            <li><strong>Sub Departments:</strong> Comma-separated list</li>
                            <li><strong>Sort Order:</strong> Number for ordering (optional)</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Upload Departments
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCategoryForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="categoryName" name="name" required>
                                    <option value="">Select Category</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Master Tracker">Master Tracker</option>
                                    <option value="Human Resource">Human Resource</option>
                                    <option value="CRM">CRM</option>
                                    <option value="Warehouse">Warehouse</option>
                                    <option value="Requisition">Requisition</option>
                                    <option value="Procurement">Procurement</option>
                                    <option value="Report">Report</option>
                                    <option value="Project Management">Project Management</option>
                                    <option value="GPON">GPON</option>
                                    <option value="Home Connection">Home Connection</option>
                                    <option value="Field Update">Field Update</option>
                                    <option value="Quality Audit">Quality Audit</option>
                                    <option value="General Service">General Service</option>
                                </select>
                                <label for="categoryName" class="required-field">Category Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="categoryCode" name="code" placeholder="Category Code" required>
                                <label for="categoryCode" class="required-field">Category Code</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="categoryDescription" name="description" placeholder="Description" style="height: 100px;"></textarea>
                        <label for="categoryDescription">Description</label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="categoryHead" name="head_name" placeholder="Head of Category">
                                <label for="categoryHead">Head of Category</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="categoryStatus" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="categoryStatus" class="required-field">Status</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="color" class="form-control form-control-color" id="categoryColor" name="color" value="#3b7ddd">
                                <label for="categoryColor">Color</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="categorySortOrder" name="sort_order" placeholder="Sort Order" min="0" max="9999">
                                <label for="categorySortOrder">Sort Order</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sub Categories</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="subCategoryInput" placeholder="Enter sub category name">
                            <button type="button" class="btn btn-outline-primary" onclick="addSubCategory()">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <div id="subCategoriesList" class="mt-2">
                            <!-- Sub categories will be displayed here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm">
                <input type="hidden" id="editCategoryId" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="editCategoryName" name="name" required>
                                    <option value="">Select Category</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Master Tracker">Master Tracker</option>
                                    <option value="Human Resource">Human Resource</option>
                                    <option value="CRM">CRM</option>
                                    <option value="Warehouse">Warehouse</option>
                                    <option value="Requisition">Requisition</option>
                                    <option value="Procurement">Procurement</option>
                                    <option value="Report">Report</option>
                                    <option value="Project Management">Project Management</option>
                                    <option value="GPON">GPON</option>
                                    <option value="Home Connection">Home Connection</option>
                                    <option value="Field Update">Field Update</option>
                                    <option value="Quality Audit">Quality Audit</option>
                                    <option value="General Service">General Service</option>
                                </select>
                                <label for="editCategoryName" class="required-field">Category Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="editCategoryCode" name="code" placeholder="Category Code" required>
                                <label for="editCategoryCode" class="required-field">Category Code</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="editCategoryDescription" name="description" placeholder="Description" style="height: 100px;"></textarea>
                        <label for="editCategoryDescription">Description</label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="editCategoryHead" name="head_name" placeholder="Head of Category">
                                <label for="editCategoryHead">Head of Category</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="editCategoryStatus" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="editCategoryStatus" class="required-field">Status</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="color" class="form-control form-control-color" id="editCategoryColor" name="color" value="#3b7ddd">
                                <label for="editCategoryColor">Color</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="editCategorySortOrder" name="sort_order" placeholder="Sort Order" min="0" max="9999">
                                <label for="editCategorySortOrder">Sort Order</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sub Categories Section -->
                    <div class="mb-3">
                        <label class="form-label">Sub Categories</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="editSubCategoryInput" placeholder="Enter sub category name">
                            <button type="button" class="btn btn-outline-primary" onclick="addSubCategoryEdit()">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <div id="editSubCategoriesList" class="border rounded p-2" style="min-height: 50px; max-height: 150px; overflow-y: auto;">
                            <!-- Sub categories will be listed here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Category Modal -->
<div class="modal fade" id="viewCategoryModal" tabindex="-1" aria-labelledby="viewCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewCategoryModalLabel">
                    <i class="fas fa-tags me-2"></i>Category Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Category Name</label>
                            <p class="form-control-plaintext" id="viewCategoryName">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Category Code</label>
                            <p class="form-control-plaintext" id="viewCategoryCode">-</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Description</label>
                    <p class="form-control-plaintext" id="viewCategoryDescription">-</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Head of Category</label>
                            <p class="form-control-plaintext" id="viewCategoryHead">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Status</label>
                            <p class="form-control-plaintext" id="viewCategoryStatus">-</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Color</label>
                            <p class="form-control-plaintext" id="viewCategoryColor">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Sub Categories</label>
                            <p class="form-control-plaintext" id="viewCategorySubCategories">-</p>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Created By</label>
                            <p class="form-control-plaintext" id="viewCategoryCreatedBy">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Updated By</label>
                            <p class="form-control-plaintext" id="viewCategoryUpdatedBy">-</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Created At</label>
                            <p class="form-control-plaintext" id="viewCategoryCreatedAt">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Updated At</label>
                            <p class="form-control-plaintext" id="viewCategoryUpdatedAt">-</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Upload Categories Modal -->
<div class="modal fade" id="bulkUploadCategoriesModal" tabindex="-1" aria-labelledby="bulkUploadCategoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUploadCategoriesModalLabel">Bulk Upload Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkUploadCategoriesForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Instructions:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Download the template file to see the required format</li>
                            <li>Fill in your category data following the template</li>
                            <li>Upload the completed file (Excel or CSV format)</li>
                            <li>Maximum file size: 10MB</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <label for="categoriesFile" class="form-label">Select File</label>
                        <input type="file" class="form-control" id="categoriesFile" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Supported formats: Excel (.xlsx, .xls) and CSV (.csv)</div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('categories.template.download') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-1"></i>Download Template
                        </a>
                        <button type="button" class="btn btn-outline-info" onclick="showUploadHelp()">
                            <i class="fas fa-question-circle me-1"></i>Upload Help
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Upload Categories
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Business Sector Modal -->
<div class="modal fade" id="addSectorModal" tabindex="-1" aria-labelledby="addSectorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSectorModalLabel">Add New Business Sector</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSectorForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="sectorName" name="name" placeholder="Sector Name" required>
                                <label for="sectorName" class="required-field">Sector Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="sectorHeadName" name="head_name" placeholder="Head Name">
                                <label for="sectorHeadName">Head Name</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="sectorDescription" name="description" placeholder="Description" style="height: 100px;"></textarea>
                        <label for="sectorDescription">Description</label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="sectorStatus" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="sectorStatus" class="required-field">Status</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sub-sectors Section -->
                    <div class="mb-3">
                        <label class="form-label">Sub-sectors</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="subSectorInput" placeholder="Enter sub-sector name">
                            <button type="button" class="btn btn-outline-primary" onclick="addSubSector()">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <div id="subSectorsList" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Sector
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Business Sector Modal -->
<div class="modal fade" id="editSectorModal" tabindex="-1" aria-labelledby="editSectorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSectorModalLabel">Edit Business Sector</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSectorForm">
                <input type="hidden" id="editSectorId" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="editSectorName" name="name" placeholder="Sector Name" required>
                                <label for="editSectorName" class="required-field">Sector Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="editSectorHeadName" name="head_name" placeholder="Head Name">
                                <label for="editSectorHeadName">Head Name</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="editSectorDescription" name="description" placeholder="Description" style="height: 100px;"></textarea>
                        <label for="editSectorDescription">Description</label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="editSectorStatus" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="editSectorStatus" class="required-field">Status</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sub-sectors Section -->
                    <div class="mb-3">
                        <label class="form-label">Sub-sectors</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="editSubSectorInput" placeholder="Enter sub-sector name">
                            <button type="button" class="btn btn-outline-primary" onclick="addSubSectorEdit()">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <div id="editSubSectorsList" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Sector
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Business Sector Modal -->
<div class="modal fade" id="viewSectorModal" tabindex="-1" aria-labelledby="viewSectorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewSectorModalLabel">
                    <i class="fas fa-building me-2"></i>Business Sector Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                        <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Sector Name</label>
                            <p class="form-control-plaintext" id="viewSectorName">-</p>
                            </div>
                        </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Head Name</label>
                            <p class="form-control-plaintext" id="viewSectorHeadName">-</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Description</label>
                    <p class="form-control-plaintext" id="viewSectorDescription">-</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Status</label>
                            <p class="form-control-plaintext" id="viewSectorStatus">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Created Date</label>
                            <p class="form-control-plaintext" id="viewSectorCreatedAt">-</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Sub-sectors</label>
                    <div id="viewSectorSubSectors" class="form-control-plaintext">-</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Upload Business Sectors Modal -->
<div class="modal fade" id="bulkUploadSectorsModal" tabindex="-1" aria-labelledby="bulkUploadSectorsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUploadSectorsModalLabel">
                    <i class="fas fa-upload me-2"></i>Bulk Upload Business Sectors
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkUploadSectorsForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Instructions:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Download the template file to see the required format</li>
                            <li>Fill in your business sector data following the template</li>
                            <li>Upload the completed CSV file (.csv format)</li>
                            <li>Maximum file size: 10MB</li>
                            <li><strong>Note:</strong> Excel support requires PHP ZipArchive extension</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sectorsFile" class="form-label">Select File</label>
                        <input type="file" class="form-control" id="sectorsFile" name="file" accept=".csv" required>
                        <div class="form-text">Supported format: CSV (.csv) - Excel support requires PHP ZipArchive extension</div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('business-sectors.template.download') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-1"></i>Download Template
                        </a>
                        <button type="button" class="btn btn-outline-info" onclick="showUploadHelp()">
                            <i class="fas fa-question-circle me-1"></i>Upload Help
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Upload Sectors
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('script')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" onload="console.log('DataTables core loaded')" onerror="console.error('Failed to load DataTables core')"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js" onload="console.log('DataTables Bootstrap loaded')" onerror="console.error('Failed to load DataTables Bootstrap')"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js" onload="console.log('DataTables Responsive loaded')" onerror="console.error('Failed to load DataTables Responsive')"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js" onload="console.log('DataTables Responsive Bootstrap loaded')" onerror="console.error('Failed to load DataTables Responsive Bootstrap')"></script>

<script>
// Debug: Test if JavaScript is loading
console.log('=== DEPARTMENTS SCRIPT LOADED ===');

// Debug: Check jQuery and DataTables availability
console.log('jQuery version:', typeof $ !== 'undefined' ? $.fn.jquery : 'jQuery not loaded');
console.log('DataTables available:', typeof $.fn.DataTable !== 'undefined');

// Test function to verify JavaScript execution

// Export function for departments (moved up for better availability)
function exportDepartments(format) {
    console.log('Exporting departments as', format);
    
    // Show loading
    Swal.fire({
        title: 'Preparing Export...',
        text: `Generating ${format.toUpperCase()} file with department data`,
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Get current filters to include only visible data
    const searchValue = $('#searchDepartments').val();
    const statusFilter = $('#filterDepartmentStatus').val();
    
    // Prepare export parameters
    const params = new URLSearchParams({
        format: format,
        ...(searchValue && { search: searchValue }),
        ...(statusFilter && { status: statusFilter })
    });
    
    // Create export URL using window.location.origin for better compatibility
    const exportUrl = `${window.location.origin}/company/Categories/departments/export?${params.toString()}`;
    console.log('Export URL:', exportUrl);
    
    // Use AJAX to handle the export with better error handling
    $.ajax({
        url: exportUrl,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json, text/plain, */*'
        },
        xhrFields: {
            responseType: 'blob' // Important for file downloads
        },
        success: function(data, status, xhr) {
            console.log('Export successful, processing download...');
            
            // Get the filename from the response header
            const contentDisposition = xhr.getResponseHeader('Content-Disposition');
            let filename = `departments_${new Date().getTime()}.${format === 'excel' ? 'xlsx' : format}`;
            
            if (contentDisposition) {
                const filenameMatch = contentDisposition.match(/filename="?([^"]+)"?/);
                if (filenameMatch) {
                    filename = filenameMatch[1];
                }
            }
            
            // Create blob and download
            const blob = new Blob([data], { 
                type: xhr.getResponseHeader('Content-Type') || 'application/octet-stream' 
            });
            
            // Create download link
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            link.style.display = 'none';
            
            // Trigger download
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Clean up blob URL
            window.URL.revokeObjectURL(url);
            
            // Show success message
            const hasFilters = searchValue || statusFilter;
            let message = `Your ${format.toUpperCase()} file "${filename}" has been downloaded successfully.`;
            
            if (hasFilters) {
                const filterInfo = [];
                if (searchValue) filterInfo.push(`search: "${searchValue}"`);
                if (statusFilter) filterInfo.push(`status: ${statusFilter}`);
                message = `Filtered data (${filterInfo.join(', ')}) exported as ${format.toUpperCase()}: "${filename}".`;
            }
            
            Swal.fire({
                icon: 'success',
                title: 'Export Complete!',
                text: message,
                timer: 4000,
                showConfirmButton: true
            });
        },
        error: function(xhr, status, error) {
            console.error('Export failed:', xhr, status, error);
            
            let errorMessage = 'Failed to export departments. Please try again.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 401) {
                errorMessage = 'You are not authorized to export data. Please log in again.';
            } else if (xhr.status === 403) {
                errorMessage = 'You do not have permission to export this data.';
            } else if (xhr.status === 500) {
                errorMessage = 'Server error occurred during export. Please try again later.';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Export Failed!',
                text: errorMessage,
                showConfirmButton: true
            });
        }
    });
}

// Test export functionality

// Tab state management function
function initializeTabState() {
    // Prevent duplicate initialization
    if (window.tabStateInitialized) {
        console.log('Tab state already initialized, skipping...');
        return;
    }
    window.tabStateInitialized = true;
    
    console.log('Initializing tab state management...');
    
    // Get saved tab from sessionStorage or default to categories
    var activeTabId = sessionStorage.getItem('categoryActiveTab') || '#categories';
    console.log('Saved tab from sessionStorage:', activeTabId);
    
    // Override server-side active state if we have a saved tab
    if (activeTabId && activeTabId !== '#categories') {
        console.log('Restoring saved tab:', activeTabId);
        $('.tab-pane').removeClass('show active');
        $(activeTabId).addClass('show active');
        $('.nav-link').removeClass('active');
        $('.nav-tabs button[data-bs-target="' + activeTabId + '"]').addClass('active');
        
        // Update aria-selected attributes
        $('.nav-tabs button').attr('aria-selected', 'false');
        $('.nav-tabs button[data-bs-target="' + activeTabId + '"]').attr('aria-selected', 'true');
    }
    
    // Update sessionStorage when tab changes
    $('.nav-tabs button').on('shown.bs.tab', function(e) {
        var targetTab = $(e.target).attr('data-bs-target');
        console.log('Tab changed to:', targetTab);
        sessionStorage.setItem('categoryActiveTab', targetTab);
    });
    
    // Handle form submissions to maintain tab state
    $('form').on('submit', function() {
        var currentTab = $('.nav-tabs button.active').attr('data-bs-target') || '#categories';
        console.log('Form submitted, saving tab state:', currentTab);
        sessionStorage.setItem('categoryActiveTab', currentTab);
    });
    
    // Handle AJAX calls to maintain tab state
    $(document).ajaxComplete(function() {
        var currentTab = $('.nav-tabs button.active').attr('data-bs-target') || '#categories';
        sessionStorage.setItem('categoryActiveTab', currentTab);
    });
    
    // Override AJAX calls to include tab state
    var originalAjax = $.ajax;
    $.ajax = function(options) {
        // Add tab parameter to data if it's a POST/PUT/DELETE request
        if (options.type && ['POST', 'PUT', 'DELETE'].includes(options.type.toUpperCase())) {
            var currentTab = $('.nav-tabs button.active').attr('data-bs-target') || '#categories';
            if (options.data) {
                if (typeof options.data === 'string') {
                    options.data += '&tab=' + encodeURIComponent(currentTab);
                } else if (typeof options.data === 'object') {
                    options.data.tab = currentTab;
                }
            } else {
                options.data = { tab: currentTab };
            }
        }
        return originalAjax.call(this, options);
    };
    
    // Clean up on page unload
    $(window).on('beforeunload', function() {
        var currentTab = $('.nav-tabs button.active').attr('data-bs-target') || '#categories';
        sessionStorage.setItem('categoryActiveTab', currentTab);
    });
    
    // Handle browser back/forward navigation
    $(window).on('popstate', function() {
        setTimeout(function() {
            var activeTabId = sessionStorage.getItem('categoryActiveTab') || '#categories';
            if (activeTabId && activeTabId !== '#categories') {
                $('.tab-pane').removeClass('show active');
                $(activeTabId).addClass('show active');
                $('.nav-link').removeClass('active');
                $('.nav-tabs button[data-bs-target="' + activeTabId + '"]').addClass('active');
            }
        }, 100);
    });
}

$(document).ready(function() {
    console.log('=== CATEGORIES PAGE INITIALIZATION ===');
    console.log('Document ready, initializing...');
    console.log('Current URL:', window.location.href);
    console.log('Current route:', window.location.pathname);
    
    // Initialize tab state management
    initializeTabState();
    
    // Show which controller/route is being used
    if (window.location.pathname.includes('/Categories/categories')) {
        console.log('✓ CORRECT ROUTE: Using CategoriesManagement controller');
    } else if (window.location.pathname.includes('/Categories/departments')) {
        console.log('✗ WRONG ROUTE: Using DepartmentCategories controller - switch to "Manage Categories" menu');
    } else if (window.location.pathname.includes('/categories')) {
        console.log('✗ WRONG ROUTE: Using CompanyUserCategories controller - switch to "Manage Categories" menu');
    } else {
        console.log('? UNKNOWN ROUTE: Not sure which controller is being used');
    }
    
    // Debug: Check PHP data passed to view
    @if(isset($categories))
        console.log('Categories variable exists in PHP:', true);
        console.log('Categories count from PHP:', {{ $categories->count() ?? 0 }});
        @if($categories->count() > 0)
            console.log('Sample categories from PHP:', @json($categories->take(3)->pluck('name', 'id')));
        @endif
    @else
        console.log('Categories variable does NOT exist in PHP');
    @endif
    
    @if(isset($departments))
        console.log('Departments variable exists in PHP:', true);
        console.log('Departments count from PHP:', {{ $departments->count() ?? 0 }});
        @if($departments->count() > 0)
            console.log('Sample departments from PHP:', @json($departments->take(3)->pluck('name', 'id')));
        @endif
    @else
        console.log('Departments variable does NOT exist in PHP');
    @endif
    
    @if(isset($stats))
        console.log('Categories stats from PHP:', @json($stats));
    @else
        console.log('Categories stats variable does NOT exist in PHP');
    @endif
    
    @if(isset($departmentStats))
        console.log('Departments stats from PHP:', @json($departmentStats));
    @else
        console.log('Departments stats variable does NOT exist in PHP');
    @endif
    
    // Debug: Check if categories data is available in HTML
    const tableBody = $('#categories-datatable tbody');
    const rowCount = tableBody.find('tr').length;
    console.log('Categories table rows found in HTML:', rowCount);
    
    if (rowCount > 0) {
        tableBody.find('tr').each(function(index) {
            const row = $(this);
            const categoryId = row.find('td:eq(0)').text().trim();
            const categoryName = row.find('td:eq(1)').text().trim();
            const categoryCode = row.find('td:eq(2)').text().trim();
            console.log(`Row ${index + 1}: ID=${categoryId}, Name="${categoryName}", Code="${categoryCode}"`);
        });
    } else {
        console.warn('No category rows found in table');
    }
    
    // Initialize DataTables - simplified approach
    function initializeDataTablesNow() {
        if (typeof $.fn.DataTable !== 'undefined' || typeof $.fn.dataTable !== 'undefined') {
            console.log('DataTables loaded, initializing...');
            console.log('DataTable plugin available:', typeof $.fn.DataTable !== 'undefined');
            console.log('dataTable plugin available:', typeof $.fn.dataTable !== 'undefined');
            initializeDataTables();
        } else {
            console.log('DataTables not available, waiting...');
            console.log('jQuery available:', typeof $ !== 'undefined');
            console.log('jQuery fn available:', typeof $.fn !== 'undefined');
            setTimeout(initializeDataTablesNow, 200);
        }
    }
    
    // Start initialization after a short delay to ensure scripts are loaded
    setTimeout(initializeDataTablesNow, 500);
    
    // Categories DataTable will be initialized with the main DataTables initialization
    
    // Load initial stats
    refreshAllStats();
    
    // Update last updated time
    updateLastUpdated();
    
    // Set up form submissions
    setupFormSubmissions();
    
    // Set up bulk upload form
    setupBulkUploadForm();
    
    // Set up search and filter event handlers
    setupSearchAndFilters();
    
    console.log('All initialization completed');
    console.log('=== END CATEGORIES PAGE INITIALIZATION ===');
});

// Global variables for sub departments
let subDepartments = [];
let editSubDepartments = [];

// Test function for debugging sub departments
function testSubDepartments() {
    console.log('=== Sub Department Test Function ===');
    console.log('Current editSubDepartments:', editSubDepartments);
    
    const input = document.getElementById('editSubDepartmentInput');
    console.log('Input element exists:', !!input);
    if (input) {
        console.log('Input value:', input.value);
    }
    
    const container = document.getElementById('editSubDepartmentsList');
    console.log('Container element exists:', !!container);
    
    // Test adding a sub department
    console.log('Testing addSubDepartmentEdit...');
    if (input) {
        input.value = 'Test Sub Dept';
        addSubDepartmentEdit();
    }
}

// Debug function to check sub-department state
function debugSubDepartmentState() {
    console.log('=== SUB DEPARTMENT STATE DEBUG ===');
    console.log('editSubDepartments:', editSubDepartments);
    console.log('Type:', typeof editSubDepartments);
    console.log('Is Array:', Array.isArray(editSubDepartments));
    console.log('Length:', editSubDepartments ? editSubDepartments.length : 'N/A');
    
    if (editSubDepartments && editSubDepartments.length > 0) {
        editSubDepartments.forEach((dept, index) => {
            console.log(`  [${index}]: "${dept}"`);
        });
    }
    
    const container = document.getElementById('editSubDepartmentsList');
    if (container) {
        console.log('Visual elements count:', container.children.length);
    }
    
    return editSubDepartments;
}

// Function to generate a unique department code
function generateDepartmentCode() {
    const now = new Date();
    const timestamp = now.getFullYear().toString().substr(-2) + 
                     (now.getMonth() + 1).toString().padStart(2, '0') + 
                     now.getDate().toString().padStart(2, '0') + 
                     now.getHours().toString().padStart(2, '0') + 
                     now.getMinutes().toString().padStart(2, '0');
    return 'DEPT-' + timestamp;
}

// Function to suggest a unique code based on department name
function generateCodeFromName(name) {
    if (!name) return generateDepartmentCode();
    
    const cleanName = name.replace(/[^a-zA-Z\s]/g, '').trim();
    const words = cleanName.split(/\s+/);
    
    let code = '';
    if (words.length >= 2) {
        code = words[0].substring(0, 2).toUpperCase() + words[1].substring(0, 2).toUpperCase();
    } else if (words.length === 1) {
        code = words[0].substring(0, 4).toUpperCase();
    } else {
        return generateDepartmentCode();
    }
    
    // Add number suffix
    const timestamp = new Date().getTime().toString().slice(-3);
    return code + '-' + timestamp;
}

// Helper function to format dates
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        return dateString;
    }
}

// Helper function to populate sub departments in view modal
function populateViewSubDepartments(subDepartments) {
    const container = document.getElementById('viewSubDepartmentsList');
    
    if (!container) {
        console.error('viewSubDepartmentsList container not found!');
        return;
    }
    
    container.innerHTML = '';
    
    if (!subDepartments || subDepartments.length === 0) {
        container.innerHTML = '<div class="text-muted">No sub departments</div>';
        return;
    }
    
    subDepartments.forEach((dept, index) => {
        const div = document.createElement('div');
        div.className = 'subdepartment-item-view d-flex align-items-center mb-2 p-2 bg-light rounded';
        div.innerHTML = `
            <i class="fas fa-building me-2 text-primary"></i>
            <span class="fw-medium">${dept}</span>
        `;
        container.appendChild(div);
    });
}

// Function to edit department from view modal
function editDepartmentFromView() {
    const departmentId = $('#viewDepartmentModal').data('department-id');
    if (departmentId) {
        $('#viewDepartmentModal').modal('hide');
        setTimeout(() => {
            editDepartment(departmentId);
        }, 300); // Small delay to ensure view modal is closed
    } else {
        console.error('No department ID found in view modal');
    }
}

// Set up search and filter event handlers
function setupSearchAndFilters() {
    console.log('Setting up search and filter handlers...');
    
    // Real-time search functionality
    $('#searchDepartments').on('input', function() {
        const searchValue = $(this).val();
        console.log('Search input changed:', searchValue);
        
        // Debounce the search to avoid too many calls
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => {
            if (departmentsTable) {
                departmentsTable.search(searchValue).draw();
            } else {
                // Fallback: client-side search
                const statusFilter = $('#filterDepartmentStatus').val();
                filterDepartmentsClientSide(searchValue.toLowerCase().trim(), statusFilter, false);
            }
        }, 300); // 300ms delay
    });
    
    // Enter key support for search
    $('#searchDepartments').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            applyDepartmentFilters();
        }
    });
    
    // Status filter change handler
    $('#filterDepartmentStatus').on('change', function() {
        console.log('Status filter changed:', $(this).val());
        // Auto-apply filters when status changes (without showing alert for just status change)
        const searchValue = $('#searchDepartments').val().toLowerCase().trim();
        const statusFilter = $(this).val();
        
        if (departmentsTable) {
            // Apply search filter
            departmentsTable.search(searchValue);
            
            // Apply status filter using column search
            if (statusFilter) {
                departmentsTable.column(6).search('^' + statusFilter + '$', true, false);
            } else {
                departmentsTable.column(6).search('');
            }
            
            // Redraw table
            departmentsTable.draw();
        } else {
            // Fallback: client-side filtering for non-DataTable
            filterDepartmentsClientSide(searchValue, statusFilter, false);
        }
    });
    
    // Add clear search button
    if ($('#searchDepartments').siblings('.search-clear-btn').length === 0) {
        $('#searchDepartments').parent().append('<button type="button" class="search-clear-btn" onclick="clearSearchInput()" title="Clear search"><i class="fas fa-times"></i></button>');
    }
    
    // Set up categories search handlers (same as departments)
    console.log('Setting up categories search handlers...');
    
    // Search functionality for categories
    $('#searchCategories').off('input').on('input', function() {
        const searchValue = $(this).val();
        console.log('Categories search input changed:', searchValue);
        
        // Debounce the search to avoid too many calls
        clearTimeout(window.categoriesSearchTimeout);
        window.categoriesSearchTimeout = setTimeout(() => {
            if (categoriesDataTable) {
                categoriesDataTable.search(searchValue).draw();
            } else {
                // Fallback: client-side search
                const statusFilter = $('#filterCategoryStatus').val();
                filterCategoriesClientSide(searchValue.toLowerCase().trim(), statusFilter, false);
            }
        }, 300); // 300ms delay
    });

    // Enter key support for categories search
    $('#searchCategories').off('keypress').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            applyCategoryFilters();
        }
    });

    // Status filter functionality for categories
    $('#filterCategoryStatus').off('change').on('change', function() {
        console.log('Categories status filter changed:', $(this).val());
        const searchValue = $('#searchCategories').val().toLowerCase().trim();
        const statusFilter = $(this).val();
        
        if (categoriesDataTable) {
            // Apply search filter
            categoriesDataTable.search(searchValue);
            // Apply status filter to the status column (column 6)
            categoriesDataTable.column(6).search(statusFilter);
            // Redraw the table
            categoriesDataTable.draw();
            console.log('Categories filters applied - Search:', searchValue, 'Status:', statusFilter);
        } else {
            // Fallback: client-side filtering
            filterCategoriesClientSide(searchValue, statusFilter, false);
        }
    });

    // Add clear search button for categories
    if ($('#searchCategories').siblings('.search-clear-btn').length === 0) {
        $('#searchCategories').parent().append('<button type="button" class="search-clear-btn" onclick="clearCategoriesSearchInput()" title="Clear search"><i class="fas fa-times"></i></button>');
    }
    
    // Set up business sectors search handlers
    console.log('Setting up business sectors search handlers...');
    
    // Search functionality for business sectors
    $('#searchSectors').off('input').on('input', function() {
        const searchValue = $(this).val();
        console.log('Business sectors search input changed:', searchValue);
        
        // Debounce the search to avoid too many calls
        clearTimeout(window.sectorsSearchTimeout);
        window.sectorsSearchTimeout = setTimeout(() => {
            if (sectorsDataTable) {
                sectorsDataTable.search(searchValue).draw();
            } else {
                // Fallback: client-side search
                const statusFilter = $('#filterSectorStatus').val();
                filterSectorsClientSide(searchValue.toLowerCase().trim(), statusFilter, false);
            }
        }, 300); // 300ms delay
    });

    // Enter key support for business sectors search
    $('#searchSectors').off('keypress').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            applySectorFilters();
        }
    });

    // Status filter functionality for business sectors
    $('#filterSectorStatus').off('change').on('change', function() {
        console.log('Business sectors status filter changed:', $(this).val());
        const searchValue = $('#searchSectors').val().toLowerCase().trim();
        const statusFilter = $(this).val();
        
        if (sectorsDataTable) {
            // Apply search filter
            sectorsDataTable.search(searchValue);
            // Apply status filter to the status column (column 6)
            sectorsDataTable.column(6).search(statusFilter);
            // Redraw the table
            sectorsDataTable.draw();
            console.log('Business sectors filters applied - Search:', searchValue, 'Status:', statusFilter);
        } else {
            // Fallback: client-side filtering
            filterSectorsClientSide(searchValue, statusFilter, false);
        }
    });

    // Add clear search button for business sectors
    if ($('#searchSectors').siblings('.search-clear-btn').length === 0) {
        $('#searchSectors').parent().append('<button type="button" class="search-clear-btn" onclick="clearSectorsSearchInput()" title="Clear search"><i class="fas fa-times"></i></button>');
    }
    
    console.log('Search and filter handlers set up successfully');
}

// Set up bulk upload form handling
function setupBulkUploadForm() {
    console.log('Setting up bulk upload form...');
    
    $('#bulkUploadDepartmentsForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const fileInput = $('#department_bulk_upload_file')[0];
        
        // Validate file selection
        if (!fileInput.files || fileInput.files.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'No File Selected',
                text: 'Please select a CSV or Excel file to upload.',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        const file = fileInput.files[0];
        const allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        // Validate file type
        if (!allowedTypes.includes(file.type) && !file.name.match(/\.(csv|xlsx|xls)$/i)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please upload a valid CSV or Excel file (.csv, .xlsx, .xls).',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        // Validate file size
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'File size must be less than 10MB.',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        // Show loading
        Swal.fire({
            title: 'Uploading Departments...',
            text: 'Please wait while we process your file.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit the form via AJAX
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Bulk upload response:', response);
                
                $('#bulkUploadDepartmentsModal').modal('hide');
                
                if (response.success) {
                    let message = `Successfully imported ${response.imported || 0} departments.`;
                    
                    if (response.failed && response.failed > 0) {
                        message += ` ${response.failed} departments failed to import.`;
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Upload Successful!',
                        text: message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Refresh the departments table
                        if (typeof refreshDepartmentsData === 'function') {
                            refreshDepartmentsData();
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Upload Completed with Issues',
                        text: response.message || 'Some departments may not have been imported.',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Bulk upload error:', xhr, status, error);
                
                let errorMessage = 'An error occurred during upload.';
                
                if (xhr.responseJSON) {
                    errorMessage = xhr.responseJSON.message || errorMessage;
                    
                    // Show detailed errors if available
                    if (xhr.responseJSON.errors && xhr.responseJSON.errors.length > 0) {
                        errorMessage += '\n\nErrors:\n' + xhr.responseJSON.errors.slice(0, 5).join('\n');
                        if (xhr.responseJSON.errors.length > 5) {
                            errorMessage += `\n... and ${xhr.responseJSON.errors.length - 5} more errors.`;
                        }
                    }
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: errorMessage,
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    
    // Reset form when modal is closed
    $('#bulkUploadDepartmentsModal').on('hidden.bs.modal', function() {
        $('#bulkUploadDepartmentsForm')[0].reset();
    });
    
    console.log('Bulk upload form handlers set up successfully');
}

// Test function to manually open bulk upload modal
function testBulkUploadModal() {
    console.log('Testing bulk upload modal...');
    
    // Check if modal exists
    const modal = document.getElementById('bulkUploadDepartmentsModal');
    if (!modal) {
        alert('Modal element not found!');
        return;
    }
    
    console.log('Modal element found:', modal);
    
    // Check if Bootstrap is available
    if (typeof bootstrap === 'undefined') {
        alert('Bootstrap not loaded!');
        return;
    }
    
    console.log('Bootstrap is available');
    
    // Try to open modal manually
    try {
        // Method 1: Bootstrap 5 way
        if (typeof bootstrap !== 'undefined') {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            console.log('Modal opened with Bootstrap 5');
            return;
        }
        
        // Method 2: jQuery way (fallback)
        if (typeof $ !== 'undefined') {
            $('#bulkUploadDepartmentsModal').modal('show');
            console.log('Modal opened with jQuery');
            return;
        }
        
        alert('Neither Bootstrap nor jQuery is available');
    } catch (error) {
        console.error('Error opening modal:', error);
        alert('Error opening modal: ' + error.message);
    }
}


// Function to clear search input
function clearSearchInput() {
    $('#searchDepartments').val('').trigger('input');
    $('#searchDepartments').focus(); // Return focus to search input
}

// Advanced search functionality
function showAdvancedSearch() {
    Swal.fire({
        title: 'Advanced Search',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Search in:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInName" checked>
                        <label class="form-check-label" for="searchInName">Department Name</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInCode" checked>
                        <label class="form-check-label" for="searchInCode">Department Code</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInDescription">
                        <label class="form-check-label" for="searchInDescription">Description</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInHead">
                        <label class="form-check-label" for="searchInHead">Head of Department</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Search Term:</label>
                    <input type="text" id="advancedSearchTerm" class="form-control" placeholder="Enter search term...">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Search',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const searchTerm = document.getElementById('advancedSearchTerm').value;
            const searchInName = document.getElementById('searchInName').checked;
            const searchInCode = document.getElementById('searchInCode').checked;
            const searchInDescription = document.getElementById('searchInDescription').checked;
            const searchInHead = document.getElementById('searchInHead').checked;
            
            if (!searchTerm.trim()) {
                Swal.showValidationMessage('Please enter a search term');
                return false;
            }
            
            if (!searchInName && !searchInCode && !searchInDescription && !searchInHead) {
                Swal.showValidationMessage('Please select at least one field to search in');
                return false;
            }
            
            return {
                searchTerm: searchTerm.trim(),
                searchInName,
                searchInCode,
                searchInDescription,
                searchInHead
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            performAdvancedSearch(result.value);
        }
    });
}

// Advanced search functionality for categories
function showAdvancedSearchCategories() {
    Swal.fire({
        title: 'Advanced Search - Categories',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Search in:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInCategoryName" checked>
                        <label class="form-check-label" for="searchInCategoryName">Category Name</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInCategoryCode" checked>
                        <label class="form-check-label" for="searchInCategoryCode">Category Code</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInCategoryDescription">
                        <label class="form-check-label" for="searchInCategoryDescription">Description</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInCategoryHead">
                        <label class="form-check-label" for="searchInCategoryHead">Head of Category</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="advancedSearchTermCategories" class="form-label">Search Term:</label>
                    <input type="text" class="form-control" id="advancedSearchTermCategories" placeholder="Enter search term...">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Search',
        cancelButtonText: 'Cancel',
        width: '500px',
        preConfirm: () => {
            const searchTerm = document.getElementById('advancedSearchTermCategories').value;
            const searchInName = document.getElementById('searchInCategoryName').checked;
            const searchInCode = document.getElementById('searchInCategoryCode').checked;
            const searchInDescription = document.getElementById('searchInCategoryDescription').checked;
            const searchInHead = document.getElementById('searchInCategoryHead').checked;
            
            if (!searchTerm.trim()) {
                Swal.showValidationMessage('Please enter a search term');
                return false;
            }
            
            if (!searchInName && !searchInCode && !searchInDescription && !searchInHead) {
                Swal.showValidationMessage('Please select at least one field to search in');
                return false;
            }
            
            return {
                searchTerm: searchTerm.trim(),
                searchInName,
                searchInCode,
                searchInDescription,
                searchInHead
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            performAdvancedSearchCategories(result.value);
        }
    });
}

// Advanced search functionality for business sectors
function showAdvancedSearchSectors() {
    Swal.fire({
        title: 'Advanced Search - Business Sectors',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Search in:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInSectorName" checked>
                        <label class="form-check-label" for="searchInSectorName">Sector Name</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInSectorCode" checked>
                        <label class="form-check-label" for="searchInSectorCode">Sector Code</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInSectorDescription">
                        <label class="form-check-label" for="searchInSectorDescription">Description</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="searchInSectorHead">
                        <label class="form-check-label" for="searchInSectorHead">Head of Sector</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="advancedSearchTermSectors" class="form-label">Search Term:</label>
                    <input type="text" class="form-control" id="advancedSearchTermSectors" placeholder="Enter search term...">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Search',
        cancelButtonText: 'Cancel',
        width: '500px',
        preConfirm: () => {
            const searchTerm = document.getElementById('advancedSearchTermSectors').value;
            const searchInName = document.getElementById('searchInSectorName').checked;
            const searchInCode = document.getElementById('searchInSectorCode').checked;
            const searchInDescription = document.getElementById('searchInSectorDescription').checked;
            const searchInHead = document.getElementById('searchInSectorHead').checked;
            
            if (!searchTerm.trim()) {
                Swal.showValidationMessage('Please enter a search term');
                return false;
            }
            
            if (!searchInName && !searchInCode && !searchInDescription && !searchInHead) {
                Swal.showValidationMessage('Please select at least one field to search in');
                return false;
            }
            
            return {
                searchTerm: searchTerm.trim(),
                searchInName,
                searchInCode,
                searchInDescription,
                searchInHead
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            performAdvancedSearchSectors(result.value);
        }
    });
}

// Perform advanced search
function performAdvancedSearch(criteria) {
    console.log('Performing advanced search:', criteria);
    
    let visibleCount = 0;
    
    $('#departments-datatable tbody tr').each(function() {
        const row = $(this);
        const departmentName = row.find('td:eq(1)').text().toLowerCase();
        const departmentCode = row.find('td:eq(2)').text().toLowerCase();
        const departmentDescription = row.find('td:eq(3)').text().toLowerCase();
        const departmentHead = row.find('td:eq(4)').text().toLowerCase();
        const departmentSubDepts = row.find('td:eq(5)').text().toLowerCase();
        const departmentStatus = row.find('td:eq(6)').text().toLowerCase();
        
        const searchTerm = criteria.searchTerm.toLowerCase();
        let matchFound = false;
        
        if (criteria.searchInName && departmentName.includes(searchTerm)) matchFound = true;
        if (criteria.searchInCode && departmentCode.includes(searchTerm)) matchFound = true;
        if (criteria.searchInDescription && departmentDescription.includes(searchTerm)) matchFound = true;
        if (criteria.searchInHead && departmentHead.includes(searchTerm)) matchFound = true;
        
        if (matchFound) {
            row.show();
            visibleCount++;
        } else {
            row.hide();
        }
    });
    
    // Update search input with the search term
    $('#searchDepartments').val(criteria.searchTerm);
    
    Swal.fire({
        icon: 'success',
        title: 'Advanced Search Complete!',
        text: `Found ${visibleCount} departments matching your criteria.`,
        timer: 2000,
        showConfirmButton: false
    });
}

// Perform advanced search for categories
function performAdvancedSearchCategories(criteria) {
    console.log('Performing advanced search for categories:', criteria);
    
    let visibleCount = 0;
    
    $('#categories-datatable tbody tr').each(function() {
        const row = $(this);
        const categoryName = row.find('td:eq(1)').text().toLowerCase();
        const categoryCode = row.find('td:eq(2)').text().toLowerCase();
        const categoryDescription = row.find('td:eq(3)').text().toLowerCase();
        const categoryHead = row.find('td:eq(4)').text().toLowerCase();
        const categorySubCats = row.find('td:eq(5)').text().toLowerCase();
        const categoryStatus = row.find('td:eq(6)').text().toLowerCase();
        
        const searchTerm = criteria.searchTerm.toLowerCase();
        let matchFound = false;
        
        if (criteria.searchInName && categoryName.includes(searchTerm)) matchFound = true;
        if (criteria.searchInCode && categoryCode.includes(searchTerm)) matchFound = true;
        if (criteria.searchInDescription && categoryDescription.includes(searchTerm)) matchFound = true;
        if (criteria.searchInHead && categoryHead.includes(searchTerm)) matchFound = true;
        
        if (matchFound) {
            row.show();
            visibleCount++;
        } else {
            row.hide();
        }
    });
    
    // Update search input with the search term
    $('#searchCategories').val(criteria.searchTerm);
    
    Swal.fire({
        icon: 'success',
        title: 'Advanced Search Complete!',
        text: `Found ${visibleCount} categories matching your criteria.`,
        timer: 2000,
        showConfirmButton: false
    });
}

// Perform advanced search for business sectors
function performAdvancedSearchSectors(criteria) {
    console.log('Performing advanced search for business sectors:', criteria);
    
    let visibleCount = 0;
    
    $('#sectors-datatable tbody tr').each(function() {
        const row = $(this);
        const sectorName = row.find('td:eq(1)').text().toLowerCase();
        const sectorCode = row.find('td:eq(2)').text().toLowerCase();
        const sectorDescription = row.find('td:eq(3)').text().toLowerCase();
        const sectorHead = row.find('td:eq(4)').text().toLowerCase();
        const sectorSubSectors = row.find('td:eq(5)').text().toLowerCase();
        const sectorStatus = row.find('td:eq(6)').text().toLowerCase();
        
        const searchTerm = criteria.searchTerm.toLowerCase();
        let matchFound = false;
        
        if (criteria.searchInName && sectorName.includes(searchTerm)) matchFound = true;
        if (criteria.searchInCode && sectorCode.includes(searchTerm)) matchFound = true;
        if (criteria.searchInDescription && sectorDescription.includes(searchTerm)) matchFound = true;
        if (criteria.searchInHead && sectorHead.includes(searchTerm)) matchFound = true;
        
        if (matchFound) {
            row.show();
            visibleCount++;
        } else {
            row.hide();
        }
    });
    
    // Update search input with the search term
    $('#searchSectors').val(criteria.searchTerm);
    
    Swal.fire({
        icon: 'success',
        title: 'Advanced Search Complete!',
        text: `Found ${visibleCount} business sectors matching your criteria.`,
        timer: 2000,
        showConfirmButton: false
    });
}

// Show search help and tips
function showSearchHelp() {
    Swal.fire({
        title: 'Search Tips & Shortcuts',
        html: `
            <div class="text-start">
                <h6><i class="fas fa-keyboard me-2"></i>Keyboard Shortcuts:</h6>
                <ul class="list-unstyled mb-3">
                    <li><kbd>Ctrl</kbd> + <kbd>F</kbd> - Focus search box</li>
                    <li><kbd>Enter</kbd> - Apply current filters</li>
                    <li><kbd>Esc</kbd> - Clear search box</li>
                </ul>
                
                <h6><i class="fas fa-search me-2"></i>Search Features:</h6>
                <ul class="list-unstyled mb-3">
                    <li><strong>Real-time Search:</strong> Results update as you type</li>
                    <li><strong>Multi-field Search:</strong> Searches name, code, and description</li>
                    <li><strong>Case Insensitive:</strong> No need to worry about capitalization</li>
                </ul>
                
                <h6><i class="fas fa-filter me-2"></i>Filter Options:</h6>
                <ul class="list-unstyled mb-3">
                    <li><strong>Status Filter:</strong> Filter by Active/Inactive status</li>
                    <li><strong>Advanced Search:</strong> Search specific fields only</li>
                    <li><strong>Combine Filters:</strong> Use search + status filter together</li>
                </ul>
                
                <h6><i class="fas fa-lightbulb me-2"></i>Tips:</h6>
                <ul class="list-unstyled">
                    <li>• Use the Clear button to reset all filters</li>
                    <li>• Click Advanced for field-specific searches</li>
                    <li>• Status filter auto-applies when changed</li>
                </ul>
            </div>
        `,
        icon: 'info',
        showCloseButton: true,
        showConfirmButton: false,
        width: '600px'
    });
}

// Keyboard shortcuts
$(document).on('keydown', function(e) {
    // Ctrl+F to focus search (context-aware)
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        // Focus the search input for the currently active tab
        if ($('#categories-tab').hasClass('active')) {
            $('#searchCategories').focus();
        } else if ($('#departments-tab').hasClass('active')) {
        $('#searchDepartments').focus();
        } else if ($('#sectors-tab').hasClass('active')) {
            $('#searchSectors').focus();
        }
    }
    
    // Esc to clear search when search input is focused
    if (e.key === 'Escape') {
        if ($('#searchCategories').is(':focus')) {
            clearCategoriesSearchInput();
        } else if ($('#searchDepartments').is(':focus')) {
        clearSearchInput();
        } else if ($('#searchSectors').is(':focus')) {
            clearSectorsSearchInput();
        }
    }
});

// Initialize DataTables
function initializeDataTables() {
    try {
        // Check if DataTables is available
        if (typeof $.fn.DataTable === 'undefined' && typeof $.fn.dataTable === 'undefined') {
            console.error('DataTables is not loaded!');
            console.log('DataTable plugin available:', typeof $.fn.DataTable !== 'undefined');
            console.log('dataTable plugin available:', typeof $.fn.dataTable !== 'undefined');
            return;
        }
        
        // Use the available DataTable plugin
        const DataTablePlugin = $.fn.DataTable || $.fn.dataTable;
        console.log('Using DataTable plugin:', DataTablePlugin === $.fn.DataTable ? 'DataTable' : 'dataTable');

        // Departments DataTable with client-side data (no AJAX)
        if (!DataTablePlugin.isDataTable('#departments-datatable')) {
            console.log('Initializing departments DataTable...');
            departmentsTable = $('#departments-datatable').DataTable({
                responsive: true,
                processing: false,
                serverSide: false,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[0, 'desc']],
                language: {
                    emptyTable: "No departments available. Click 'Add Department' to get started.",
                    zeroRecords: "No departments found matching your search criteria.",
                    search: "Search departments:",
                    lengthMenu: "Show _MENU_ departments per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ departments",
                    infoEmpty: "Showing 0 to 0 of 0 departments",
                    infoFiltered: "(filtered from _MAX_ total departments)"
                },
                columnDefs: [
                    { 
                        targets: [5, 7], // Sub departments and Actions columns
                        orderable: false 
                    },
                    {
                        targets: [6], // Status column
                        type: 'string'
                    }
                ]
            });
            
            console.log('Departments DataTable initialized successfully');
        }

        // Initialize Categories DataTable (client-side like departments)
        if (!DataTablePlugin.isDataTable('#categories-datatable')) {
            console.log('Initializing categories DataTable...');
            console.log('Categories table element found:', $('#categories-datatable').length > 0);
            console.log('Categories table element:', $('#categories-datatable')[0]);
            try {
                categoriesDataTable = $('#categories-datatable').DataTable({
                    responsive: true,
                    processing: false,
                    serverSide: false,
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    order: [[0, 'desc']],
                    language: {
                        emptyTable: "No categories available. Click 'Add Category' to get started.",
                        zeroRecords: "No categories found matching your search criteria.",
                        search: "Search categories:",
                        lengthMenu: "Show _MENU_ categories per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ categories",
                        infoEmpty: "Showing 0 to 0 of 0 categories",
                        infoFiltered: "(filtered from _MAX_ total categories)"
                    },
                    columnDefs: [
                        { 
                            targets: [5, 7], // Sub categories and Actions columns
                            orderable: false 
                        },
                        {
                            targets: [6], // Status column
                            type: 'string'
                        }
                    ]
                });
                
                console.log('Categories DataTable initialized successfully');
                
            } catch (error) {
                console.error('Error initializing categories DataTable:', error);
            }
        }
        
    } catch (error) {
        console.error('Error initializing DataTables:', error);
    }
}

// Fallback functions removed - using traditional Laravel approach

// Sub department functions
function addSubDepartment() {
    const input = document.getElementById('subDepartmentInput');
    const value = input.value.trim();
    
    if (value && !subDepartments.includes(value)) {
        subDepartments.push(value);
        updateSubDepartmentsList();
        input.value = '';
    }
}

function addSubDepartmentEdit() {
    console.log('addSubDepartmentEdit function called');
    
    const input = document.getElementById('editSubDepartmentInput');
    console.log('Input element:', input);
    
    if (!input) {
        console.error('editSubDepartmentInput element not found!');
        return;
    }
    
    const value = input.value.trim();
    console.log('Input value:', value);
    console.log('Current editSubDepartments:', editSubDepartments);
    
    if (!value) {
        console.log('Empty value, not adding');
        alert('Please enter a sub department name');
        return;
    }
    
    if (editSubDepartments.includes(value)) {
        console.log('Duplicate value, not adding');
        alert('This sub department already exists');
        return;
    }
    
    // Initialize editSubDepartments if it's undefined
    if (!editSubDepartments) {
        console.log('Initializing editSubDepartments array');
        editSubDepartments = [];
    }
    
    editSubDepartments.push(value);
    console.log('Sub department added. New list:', editSubDepartments);
    
    updateEditSubDepartmentsList();
    input.value = '';
    
    // Visual feedback
    console.log('Sub department "' + value + '" added successfully');
}

function removeSubDepartment(index) {
    subDepartments.splice(index, 1);
    updateSubDepartmentsList();
}

function removeEditSubDepartment(index) {
    editSubDepartments.splice(index, 1);
    updateEditSubDepartmentsList();
}

function updateSubDepartmentsList() {
    const container = document.getElementById('subDepartmentsList');
    container.innerHTML = '';
    
    subDepartments.forEach((dept, index) => {
        const div = document.createElement('div');
        div.className = 'subdepartment-item';
        div.innerHTML = `
            <span>${dept}</span>
            <button type="button" class="remove-btn" onclick="removeSubDepartment(${index})">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    });
}

function updateEditSubDepartmentsList() {
    console.log('updateEditSubDepartmentsList called with:', editSubDepartments);
    
    const container = document.getElementById('editSubDepartmentsList');
    console.log('Container element:', container);
    
    if (!container) {
        console.error('editSubDepartmentsList container not found!');
        return;
    }
    
    container.innerHTML = '';
    
    if (!editSubDepartments || editSubDepartments.length === 0) {
        console.log('No sub departments to display');
        return;
    }
    
    editSubDepartments.forEach((dept, index) => {
        console.log('Creating element for sub department:', dept, 'at index:', index);
        const div = document.createElement('div');
        div.className = 'subdepartment-item';
        div.innerHTML = `
            <span>${dept}</span>
            <button type="button" class="remove-btn" onclick="removeEditSubDepartment(${index})">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    });
    
    console.log('Sub departments list updated with', editSubDepartments.length, 'items');
}

// Form submission handlers
function setupFormSubmissions() {
    // Department form
    $('#addDepartmentForm').on('submit', function(e) {
        e.preventDefault();
        console.log('Department form submitted!');
        
        const formData = new FormData(this);
        
        // Add sub departments as array elements
        console.log('=== ADD FORM SUBMISSION DEBUG ===');
        console.log('subDepartments array:', subDepartments);
        console.log('subDepartments type:', typeof subDepartments);
        console.log('subDepartments isArray:', Array.isArray(subDepartments));
        console.log('subDepartments length:', subDepartments ? subDepartments.length : 'undefined');
        
        // Fix array handling - ensure subDepartments is properly initialized
        if (!Array.isArray(subDepartments)) {
            console.warn('subDepartments is not an array, initializing...');
            subDepartments = [];
        }
        
        if (subDepartments && subDepartments.length > 0) {
            subDepartments.forEach((subDept, index) => {
                console.log(`Adding sub_departments[${index}]:`, subDept);
                formData.append(`sub_departments[${index}]`, subDept);
            });
        } else {
            console.log('No sub-departments to add');
        }
        
        console.log('FormData entries:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // Show loading
        Swal.fire({
            title: 'Saving Department...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        // AJAX call to save department
        console.log('Making AJAX call to save department...');
        console.log('URL:', '{{ route("department-categories.store") }}');
        console.log('Form data:', Object.fromEntries(formData));
        
        $.ajax({
            url: '{{ route("department-categories.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Reset form and close modal
                    $('#addDepartmentForm')[0].reset();
                    subDepartments = [];
                    updateSubDepartmentsList();
                    $('#addDepartmentModal').modal('hide');
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Refresh the department list/stats
                    refreshAllStats();
                    
                    // Since we're using server-side rendering, reload the page to show new data
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to save department.'
                    });
                }
            },
            error: function(xhr) {
                console.log('AJAX Error:', xhr);
                console.log('Status:', xhr.status);
                console.log('Response:', xhr.responseText);
                
                let errorMessage = 'An error occurred while saving the department.';
                let errorTitle = 'Error!';
                
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.error && xhr.responseJSON.error.includes('Duplicate entry')) {
                        // Handle duplicate code error specifically
                        if (xhr.responseJSON.error.includes('department_categories_code_unique')) {
                            errorTitle = 'Duplicate Department Code!';
                            errorMessage = 'A department with this code already exists. Please use a different code.';
                        } else {
                            errorTitle = 'Duplicate Entry!';
                            errorMessage = 'This department information already exists. Please check your data.';
                        }
                    } else if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        errorMessage = errors.join('<br>');
                    }
                } else if (xhr.status === 422) {
                    errorTitle = 'Validation Error!';
                    errorMessage = 'Please check your input and try again.';
                } else if (xhr.status === 500) {
                    errorTitle = 'Server Error!';
                    errorMessage = 'A server error occurred. Please try again or contact support.';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: errorTitle,
                    html: errorMessage
                });
            }
        });
    });

    // Edit Department form
    $('#editDepartmentForm').on('submit', function(e) {
        e.preventDefault();
        console.log('Edit Department form submitted!');
        
        const departmentId = $('#editDepartmentId').val();
        
        // Collect form data manually to ensure proper submission
        const data = {
            name: $('#editDepartmentName').val(),
            code: $('#editDepartmentCode').val(),
            description: $('#editDepartmentDescription').val(),
            head_name: $('#editDepartmentHead').val(),
            status: $('#editDepartmentStatus').val(),
            sub_departments: editSubDepartments || []
        };
        
        console.log('=== FORM SUBMISSION DEBUG ===');
        console.log('editSubDepartments array:', editSubDepartments);
        console.log('editSubDepartments length:', editSubDepartments ? editSubDepartments.length : 'undefined');
        console.log('sub_departments in data:', data.sub_departments);
        console.log('Full data object:', data);
        
        // Show loading
        Swal.fire({
            title: 'Updating Department...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        // AJAX call to update department
        console.log('Making AJAX call to update department...');
        console.log('Department ID:', departmentId);
        console.log('Data to send:', data);
        
        $.ajax({
            url: '{{ route("department-categories.update", ":id") }}'.replace(':id', departmentId),
            method: 'PUT',
            data: JSON.stringify(data),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Update response:', response);
                
                if (response.success) {
                    // Reset form and close modal
                    $('#editDepartmentForm')[0].reset();
                    editSubDepartments = [];
                    updateEditSubDepartmentsList();
                    $('#editDepartmentModal').modal('hide');
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: response.message || 'Department updated successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Refresh the department list/stats
                    refreshAllStats();
                    
                    // Since we're using server-side rendering, reload the page to show updated data
                    setTimeout(function() {
                        console.log('Reloading page to show updated data...');
                        window.location.reload();
                    }, 1000);
                } else {
                    console.error('Update failed:', response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to update department.'
                    });
                }
            },
            error: function(xhr) {
                console.log('AJAX Error:', xhr);
                console.log('Status:', xhr.status);
                console.log('Response:', xhr.responseText);
                
                let errorMessage = 'An error occurred while updating the department.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join('<br>');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed!',
                    html: errorMessage
                });
            }
        });
    });

    // Category form - handled by the main form handler below

    // Edit Sector form
    $('#editSectorForm').on('submit', function(e) {
        e.preventDefault();
        
        console.log('Edit business sector form submitted');
        console.log('Form element:', this);
        console.log('Form found:', $('#editSectorForm').length);
        
        // Debug: Check form field values before creating FormData
        console.log('Form field values:');
        console.log('Name:', $('#editSectorName').val());
        console.log('Head Name:', $('#editSectorHeadName').val());
        console.log('Description:', $('#editSectorDescription').val());
        console.log('Status:', $('#editSectorStatus').val());
        console.log('Sector ID:', $('#editSectorId').val());
        
        // Debug: Check if form fields exist and have values
        console.log('Form field validation:');
        console.log('Name field exists:', $('#editSectorName').length > 0);
        console.log('Name field has value:', $('#editSectorName').val() !== '');
        console.log('Status field exists:', $('#editSectorStatus').length > 0);
        console.log('Status field has value:', $('#editSectorStatus').val() !== '');
        
        // Try a different approach - manually create FormData
        const formData = new FormData();
        const sectorId = $('#editSectorId').val();
        
        // Manually add form fields
        formData.append('_method', 'PUT');
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('id', sectorId);
        formData.append('name', $('#editSectorName').val());
        formData.append('head_name', $('#editSectorHeadName').val());
        formData.append('description', $('#editSectorDescription').val());
        formData.append('status', $('#editSectorStatus').val());
        
        // Debug: Check FormData contents
        console.log('Manually created FormData contents:');
        for (let [key, value] of formData.entries()) {
            console.log(`FormData: ${key} = ${value}`);
        }
        
        // Add sub-sectors as individual array elements
        editSubSectors.forEach((subSector, index) => {
            formData.append(`sub_sectors[${index}]`, subSector);
        });
        
        console.log('Edit form data:', Object.fromEntries(formData));
        console.log('Edit sub-sectors:', editSubSectors);
        console.log('Route URL:', `{{ url('company/Categories/business-sectors') }}/${sectorId}`);
        
        // Debug: Log all form data
        for (let [key, value] of formData.entries()) {
            console.log(`Form field: ${key} = ${value}`);
        }
        
        $.ajax({
            url: `{{ url('company/Categories/business-sectors') }}/${sectorId}`,
            method: 'POST', // Use POST with _method=PUT
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Edit business sector AJAX success:', response);
                if (response.success) {
        // Reset form and close modal
                    $('#editSectorForm')[0].reset();
                    editSubSectors = [];
                    updateEditSubSectorsList();
                    
                    // Properly close modal and remove backdrop
                    $('#editSectorModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Stay on current tab (sectors) and refresh table
                        if (typeof sectorsDataTable !== 'undefined' && sectorsDataTable) {
                            sectorsDataTable.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                        refreshAllStats();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to update business sector.'
                    });
                }
            },
            error: function(xhr) {
                console.error('Edit business sector creation error:', xhr);
                console.error('Error response:', xhr.responseJSON);
                console.error('Status:', xhr.status);
                console.error('Response text:', xhr.responseText);
                
                let errorMessage = 'An error occurred while updating the business sector.';
                
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        console.log('Validation errors:', errors);
                        const firstError = Object.values(errors)[0];
                        errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                    }
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    footer: xhr.status === 422 ? 'Validation Error - Check console for details' : ''
                });
            }
        });
    });

    // Sector form
    $('#addSectorForm').on('submit', function(e) {
        e.preventDefault();
        
        console.log('Business sector form submitted');
        
        const formData = new FormData(this);
        
        // Add sub-sectors as individual array elements
        subSectors.forEach((subSector, index) => {
            formData.append(`sub_sectors[${index}]`, subSector);
        });
        
        console.log('Form data:', Object.fromEntries(formData));
        console.log('Sub-sectors:', subSectors);
        console.log('Route URL:', '{{ route("business-sectors.store") }}');
        console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
        
        $.ajax({
            url: '{{ route("business-sectors.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Business sector AJAX success:', response);
                if (response.success) {
        // Reset form and close modal
                    $('#addSectorForm')[0].reset();
                    subSectors = [];
                    updateSubSectorsList();
                    
                    // Properly close modal and remove backdrop
        $('#addSectorModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Refresh DataTable after SweetAlert closes
                        if (typeof sectorsDataTable !== 'undefined' && sectorsDataTable) {
                            sectorsDataTable.ajax.reload(null, false);
                        } else {
                            // If DataTable not initialized, reload the page
                            location.reload();
                        }
                        
                        // Refresh stats
                        refreshAllStats();
                    });
                    
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to create business sector.'
                    });
                }
            },
            error: function(xhr) {
                console.error('Business sector creation error:', xhr);
                console.error('Error response:', xhr.responseJSON);
                let errorMessage = 'An error occurred while creating the business sector.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('<br>');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage
                });
            }
        });
    });

}

// Tab switching function
function switchToActiveTab(tabName) {
    const tabElement = document.getElementById(tabName + '-tab');
    if (tabElement) {
        tabElement.click();
    }
}

// Refresh stats
function refreshAllStats() {
    // Get real-time stats from the server for departments
    $.ajax({
        url: '{{ route("department-categories.stats") }}',
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success && response.data) {
                const stats = response.data;
                
                // Update departments stats with null checks
                const totalDeptElement = document.getElementById('totalDepartments');
                const activeDeptElement = document.getElementById('activeDepartments');
                
                if (totalDeptElement) {
                    totalDeptElement.textContent = stats.total || 0;
                }
                if (activeDeptElement) {
                    activeDeptElement.textContent = stats.active || 0;
                }
                
                // Update last updated time
                updateLastUpdated();
            }
        },
        error: function(xhr) {
            console.error('Error refreshing department stats:', xhr);
        }
    });
    
    // Get real-time stats from the server for business sectors
    $.ajax({
        url: '{{ route("business-sectors.stats") }}',
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success && response.data) {
                const stats = response.data;
                
                // Update business sectors stats with null checks
                const totalSectorsElement = document.getElementById('totalBusinessSectors');
                const activeSectorsElement = document.getElementById('activeBusinessSectors');
                
                if (totalSectorsElement) {
                    totalSectorsElement.textContent = stats.total || 0;
                }
                if (activeSectorsElement) {
                    activeSectorsElement.textContent = stats.active || 0;
                }
            }
        },
        error: function(xhr) {
            console.error('Error refreshing business sectors stats:', xhr);
        }
    });
    
    // Update Total Records card
    updateTotalRecordsCard();
}

// Update Total Records card
function updateTotalRecordsCard() {
    // Get current values from all cards
    const totalCategories = parseInt(document.getElementById('totalCategories')?.textContent || '0');
    const totalDepartments = parseInt(document.getElementById('totalDepartments')?.textContent || '0');
    const totalBusinessSectors = parseInt(document.getElementById('totalBusinessSectors')?.textContent || '0');
    
    const activeCategories = parseInt(document.getElementById('activeCategories')?.textContent || '0');
    const activeDepartments = parseInt(document.getElementById('activeDepartments')?.textContent || '0');
    const activeBusinessSectors = parseInt(document.getElementById('activeBusinessSectors')?.textContent || '0');
    
    // Calculate totals
    const totalRecords = totalCategories + totalDepartments + totalBusinessSectors;
    const totalActiveRecords = activeCategories + activeDepartments + activeBusinessSectors;
    
    // Update Total Records card
    const totalRecordsElement = document.getElementById('totalRecords');
    const totalActiveRecordsElement = document.getElementById('totalActiveRecords');
    
    if (totalRecordsElement) {
        totalRecordsElement.textContent = totalRecords;
    }
    if (totalActiveRecordsElement) {
        totalActiveRecordsElement.textContent = totalActiveRecords;
    }
}

// Update last updated time
function updateLastUpdated() {
    const now = new Date();
    const lastUpdatedElement = document.getElementById('lastUpdated');
    if (lastUpdatedElement) {
        lastUpdatedElement.textContent = now.toLocaleString();
    }
}

// Global variable to store the DataTable instance
let departmentsTable = null;

// Filter functions for departments
function applyDepartmentFilters() {
    console.log('Applying department filters');
    
    const searchValue = $('#searchDepartments').val().toLowerCase().trim();
    const statusFilter = $('#filterDepartmentStatus').val();
    
    console.log('Search value:', searchValue);
    console.log('Status filter:', statusFilter);
    
    // Only show feedback if there's actually a filter to apply
    const hasFilters = searchValue || statusFilter;
    
    if (departmentsTable) {
        // Apply search filter
        departmentsTable.search(searchValue);
        
        // Apply status filter using column search
        if (statusFilter) {
            // Status is in column 6 (0-indexed: ID, Name, Code, Description, Head, Sub-Departments, Status)
            departmentsTable.column(6).search('^' + statusFilter + '$', true, false);
        } else {
            // Clear status filter
            departmentsTable.column(6).search('');
        }
        
        // Redraw table
        departmentsTable.draw();
        
        // Show feedback only if filters were applied
        if (hasFilters) {
            const filterText = [];
            if (searchValue) filterText.push(`search "${searchValue}"`);
            if (statusFilter) filterText.push(`status "${statusFilter}"`);
            
            Swal.fire({
                icon: 'success',
                title: 'Filters Applied!',
                text: `Filtered departments by: ${filterText.join(' and ')}`,
                timer: 1500,
                showConfirmButton: false
            });
        }
    } else {
        // Fallback: client-side filtering for non-DataTable
        filterDepartmentsClientSide(searchValue, statusFilter, hasFilters);
    }
}

function clearDepartmentFilters() {
    console.log('Clearing department filters');
    
    // Clear form inputs
    $('#searchDepartments').val('');
    $('#filterDepartmentStatus').val('');
    
    if (departmentsTable) {
        // Clear all DataTable filters
        departmentsTable.search('');
        departmentsTable.columns().search('');
        departmentsTable.draw();
    } else {
        // Fallback: show all rows
        $('#departments-datatable tbody tr').show();
    }
    
    // Show feedback
    Swal.fire({
        icon: 'info',
        title: 'Filters Cleared!',
        text: 'All departments are now visible.',
        timer: 1500,
        showConfirmButton: false
    });
}

// Client-side filtering fallback (when DataTables is not available)
function filterDepartmentsClientSide(searchValue, statusFilter, showAlert = false) {
    console.log('Using client-side filtering fallback');
    
    let visibleCount = 0;
    
    $('#departments-datatable tbody tr').each(function() {
        const row = $(this);
        const departmentName = row.find('td:eq(1)').text().toLowerCase(); // Department name column
        const departmentCode = row.find('td:eq(2)').text().toLowerCase(); // Department code column
        const departmentDescription = row.find('td:eq(3)').text().toLowerCase(); // Description column
        const departmentHead = row.find('td:eq(4)').text().toLowerCase(); // Head of department column
        const departmentSubDepts = row.find('td:eq(5)').text().toLowerCase(); // Sub departments column
        const departmentStatus = row.find('td:eq(6)').text().toLowerCase(); // Status column
        
        let showRow = true;
        
        // Apply search filter (search in name, code, description, and head)
        if (searchValue) {
            const matchesSearch = departmentName.includes(searchValue) || 
                                departmentCode.includes(searchValue) || 
                                departmentDescription.includes(searchValue) ||
                                departmentHead.includes(searchValue) ||
                                departmentSubDepts.includes(searchValue);
            if (!matchesSearch) {
                showRow = false;
            }
        }
        
        // Apply status filter
        if (statusFilter && showRow) {
            if (!departmentStatus.includes(statusFilter.toLowerCase())) {
                showRow = false;
            }
        }
        
        if (showRow) {
            row.show();
            visibleCount++;
        } else {
            row.hide();
        }
    });
    
    console.log(`Filtered results: ${visibleCount} departments visible`);
    
    // Show feedback only if explicitly requested (like from manual filter button click)
    if (showAlert && (searchValue || statusFilter)) {
        const filterText = [];
        if (searchValue) filterText.push(`search "${searchValue}"`);
        if (statusFilter) filterText.push(`status "${statusFilter}"`);
        
        Swal.fire({
            icon: 'success',
            title: 'Filters Applied!',
            text: `Found ${visibleCount} departments matching your criteria.`,
            timer: 1500,
            showConfirmButton: false
        });
    }
}

// Client-side filtering fallback for categories (when DataTables is not available)
function filterCategoriesClientSide(searchValue, statusFilter, showAlert = false) {
    console.log('Using client-side filtering fallback for categories');
    
    let visibleCount = 0;
    
    $('#categories-datatable tbody tr').each(function() {
        const row = $(this);
        const categoryName = row.find('td:eq(1)').text().toLowerCase(); // Category name column
        const categoryCode = row.find('td:eq(2)').text().toLowerCase(); // Category code column
        const categoryDescription = row.find('td:eq(3)').text().toLowerCase(); // Description column
        const categoryHead = row.find('td:eq(4)').text().toLowerCase(); // Head of category column
        const categorySubCats = row.find('td:eq(5)').text().toLowerCase(); // Sub categories column
        const categoryStatus = row.find('td:eq(6)').text().toLowerCase(); // Status column
        
        let showRow = true;
        
        // Apply search filter (search in name, code, description, and head)
        if (searchValue) {
            const matchesSearch = categoryName.includes(searchValue) || 
                                categoryCode.includes(searchValue) || 
                                categoryDescription.includes(searchValue) ||
                                categoryHead.includes(searchValue) ||
                                categorySubCats.includes(searchValue);
            if (!matchesSearch) {
                showRow = false;
            }
        }
        
        // Apply status filter
        if (statusFilter && showRow) {
            if (!categoryStatus.includes(statusFilter.toLowerCase())) {
                showRow = false;
            }
        }
        
        if (showRow) {
            row.show();
            visibleCount++;
        } else {
            row.hide();
        }
    });
    
    console.log(`Filtered results: ${visibleCount} categories visible`);
    
    // Show feedback only if explicitly requested (like from manual filter button click)
    if (showAlert && (searchValue || statusFilter)) {
        const filterText = [];
        if (searchValue) filterText.push(`search "${searchValue}"`);
        if (statusFilter) filterText.push(`status "${statusFilter}"`);
        
        Swal.fire({
            icon: 'success',
            title: 'Filters Applied!',
            text: `Found ${visibleCount} categories matching your criteria.`,
            timer: 1500,
            showConfirmButton: false
        });
    }
}

// Client-side filtering fallback for business sectors (when DataTables is not available)
function filterSectorsClientSide(searchValue, statusFilter, showAlert = false) {
    console.log('Using client-side filtering fallback for business sectors');
    
    let visibleCount = 0;
    
    $('#sectors-datatable tbody tr').each(function() {
        const row = $(this);
        const sectorName = row.find('td:eq(1)').text().toLowerCase(); // Sector name column
        const sectorCode = row.find('td:eq(2)').text().toLowerCase(); // Sector code column
        const sectorDescription = row.find('td:eq(3)').text().toLowerCase(); // Description column
        const sectorHead = row.find('td:eq(4)').text().toLowerCase(); // Head of sector column
        const sectorSubSectors = row.find('td:eq(5)').text().toLowerCase(); // Sub sectors column
        const sectorStatus = row.find('td:eq(6)').text().toLowerCase(); // Status column
        
        let showRow = true;
        
        // Apply search filter (search in name, code, description, and head)
        if (searchValue) {
            const matchesSearch = sectorName.includes(searchValue) || 
                                sectorCode.includes(searchValue) || 
                                sectorDescription.includes(searchValue) ||
                                sectorHead.includes(searchValue) ||
                                sectorSubSectors.includes(searchValue);
            if (!matchesSearch) {
                showRow = false;
            }
        }
        
        // Apply status filter
        if (statusFilter && showRow) {
            if (!sectorStatus.includes(statusFilter.toLowerCase())) {
                showRow = false;
            }
        }
        
        if (showRow) {
            row.show();
            visibleCount++;
        } else {
            row.hide();
        }
    });
    
    console.log(`Filtered results: ${visibleCount} business sectors visible`);
    
    // Show feedback only if explicitly requested (like from manual filter button click)
    if (showAlert && (searchValue || statusFilter)) {
        const filterText = [];
        if (searchValue) filterText.push(`search "${searchValue}"`);
        if (statusFilter) filterText.push(`status "${statusFilter}"`);
        
        Swal.fire({
            icon: 'success',
            title: 'Filters Applied!',
            text: `Found ${visibleCount} business sectors matching your criteria.`,
            timer: 1500,
            showConfirmButton: false
        });
    }
}

function applySectorFilters() {
    console.log('Applying sector filters...');
    const searchTerm = $('#searchSectors').val();
    const statusFilter = $('#filterSectorStatus').val();
    
    console.log('Search term:', searchTerm);
    console.log('Status filter:', statusFilter);
    
    if (sectorsDataTable) {
        // Apply search
        if (searchTerm) {
            sectorsDataTable.search(searchTerm).draw();
        }
        
        // Apply status filter
        if (statusFilter) {
            sectorsDataTable.column(6).search(statusFilter).draw();
        }
        
        // If no filters, clear all
        if (!searchTerm && !statusFilter) {
            sectorsDataTable.search('').columns().search('').draw();
        }
    } else {
        // Fallback: client-side filtering
        filterSectorsClientSide(searchTerm.toLowerCase().trim(), statusFilter, true);
    }
}

function clearSectorFilters() {
    console.log('Clearing sector filters...');
    $('#searchSectors').val('');
    $('#filterSectorStatus').val('');
    
    if (sectorsDataTable) {
        sectorsDataTable.search('').columns().search('').draw();
        console.log('Sector filters cleared');
    } else {
        // Fallback: show all rows
        $('#sectors-datatable tbody tr').show();
        console.log('Sector filters cleared (client-side)');
    }
}

function applyTypeFilters() {
    console.log('Applying type filters');
}

function clearTypeFilters() {
    console.log('Clearing type filters');
}


// Legacy export function (for other tabs)
function exportData(type) {
    console.log('Exporting', type, 'data');
    Swal.fire('Export', `Exporting ${type} data...`, 'info');
}

function exportAllData() {
    console.log('Exporting all data');
    Swal.fire('Export', 'Exporting all category management data...', 'info');
}

// CRUD Operations

// Helper function to remove table row
function removeTableRow(departmentId) {
    try {
        // Check if DataTable is initialized
        const DataTablePlugin = $.fn.DataTable || $.fn.dataTable;
        if (DataTablePlugin.isDataTable('#departments-datatable')) {
            var table = $('#departments-datatable').DataTable();
            
            // Find and remove the row by ID
            table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                var data = this.data();
                if (data && data[0] == departmentId) { // Assuming ID is in first column
                    this.remove();
                    return false; // Break the loop
                }
            });
            
            // Redraw the table
            table.draw();
        } else {
            // Fallback: remove row from DOM directly
            $(`button[onclick="deleteDepartment(${departmentId})"]`).closest('tr').fadeOut(300, function() {
                $(this).remove();
            });
        }
    } catch (error) {
        console.error('Error removing table row:', error);
        // Fallback to page reload if there's an issue
        setTimeout(function() {
            window.location.reload();
        }, 1000);
    }
}

// Department operations
function viewDepartment(id) {
    console.log('Viewing department:', id);
    
    // Show loading state
    Swal.fire({
        title: 'Loading Department Details...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Fetch department data
    $.ajax({
        url: '{{ route("department-categories.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success && response.data) {
                const dept = response.data;
                console.log('Department data loaded for view:', dept);
                
                // Populate view modal fields
                $('#viewDepartmentId').text(dept.id);
                $('#viewDepartmentName').text(dept.name);
                $('#viewDepartmentCode').text(dept.code);
                $('#viewDepartmentHead').text(dept.head_name || 'Not assigned');
                $('#viewDepartmentSortOrder').text(dept.sort_order || '0');
                
                // Handle status with proper styling
                const statusHtml = dept.status === 'active' 
                    ? '<span class="badge bg-success">Active</span>' 
                    : '<span class="badge bg-danger">Inactive</span>';
                $('#viewDepartmentStatus').html(statusHtml);
                
                // Handle description
                $('#viewDepartmentDescription').text(dept.description || 'No description provided');
                
                // Handle dates
                $('#viewDepartmentCreated').text(formatDate(dept.created_at));
                $('#viewDepartmentUpdated').text(formatDate(dept.updated_at));
                
                // Handle created/updated by
                $('#viewDepartmentCreatedBy').text(dept.created_by ? (dept.created_by.fullname || dept.created_by.name || 'Unknown User') : 'System');
                $('#viewDepartmentUpdatedBy').text(dept.updated_by ? (dept.updated_by.fullname || dept.updated_by.name || 'Unknown User') : 'System');
                
                // Handle sub departments
                populateViewSubDepartments(dept.sub_departments || []);
                
                // Store department ID for potential edit action
                $('#viewDepartmentModal').data('department-id', dept.id);
                
                // Close loading and show modal
                Swal.close();
                $('#viewDepartmentModal').modal('show');
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to load department details.'
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading department for view:', xhr);
            
            let errorMessage = 'Failed to load department details.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 404) {
                errorMessage = 'Department not found.';
            } else if (xhr.status === 403) {
                errorMessage = 'You do not have permission to view this department.';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Load Failed!',
                text: errorMessage
            });
        }
    });
}

function editDepartment(id) {
    console.log('Editing department:', id);
    
    // Clear the form first
    $('#editDepartmentForm')[0].reset();
    editSubDepartments = [];
    console.log('Cleared editSubDepartments:', editSubDepartments);
    updateEditSubDepartmentsList();
    
    // Show loading state
    Swal.fire({
        title: 'Loading Department Data...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Fetch department data
    $.ajax({
        url: '{{ route("department-categories.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success && response.data) {
                const dept = response.data;
                
                // Populate form fields
                $('#editDepartmentId').val(dept.id);
                $('#editDepartmentName').val(dept.name);
                $('#editDepartmentCode').val(dept.code);
                $('#editDepartmentDescription').val(dept.description || '');
                $('#editDepartmentHead').val(dept.head_name || '');
                $('#editDepartmentStatus').val(dept.status);
                
                // Populate sub departments - ensure we create a proper copy
                if (dept.sub_departments && Array.isArray(dept.sub_departments)) {
                    editSubDepartments = [...dept.sub_departments]; // Create a copy of the array
                } else {
                    editSubDepartments = [];
                }
                console.log('Loaded sub departments:', editSubDepartments);
                console.log('Original from server:', dept.sub_departments);
                updateEditSubDepartmentsList();
                
                // Close loading and show modal
                Swal.close();
                $('#editDepartmentModal').modal('show');
                
                console.log('Department data loaded successfully');
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to load department data.'
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading department:', xhr);
            
            let errorMessage = 'Failed to load department data.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 404) {
                errorMessage = 'Department not found.';
            } else if (xhr.status === 403) {
                errorMessage = 'You do not have permission to edit this department.';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Load Failed!',
                text: errorMessage
            });
        }
    });
}

function deleteDepartment(id) {
    // Get department name from the table row for better UX
    let departmentName = 'this department';
    try {
        const row = $(`button[onclick="deleteDepartment(${id})"]`).closest('tr');
        const nameCell = row.find('td:nth-child(2)');
        if (nameCell.length) {
            departmentName = `"${nameCell.text().trim()}"`;
        }
    } catch (e) {
        console.log('Could not extract department name, using default');
    }

    Swal.fire({
        title: 'Are you sure?',
        html: `You are about to delete ${departmentName}.<br><br><strong>This action cannot be undone!</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Disable delete button to prevent double-clicks
            const deleteButton = $(`button[onclick="deleteDepartment(${id})"]`);
            deleteButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            // Show loading state
            Swal.fire({
                title: 'Deleting Department...',
                text: 'Please wait while we delete the department.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make AJAX call to delete department
            $.ajax({
                url: '{{ route("department-categories.destroy", ":id") }}'.replace(':id', id),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Refresh DataTable after SweetAlert closes
                            if (typeof categoriesDataTable !== 'undefined' && categoriesDataTable) {
                                categoriesDataTable.ajax.reload(null, false);
                            } else {
                                // If DataTable not initialized, reload the page
                                location.reload();
                            }
                        
                        // Refresh stats
                        refreshAllStats();
                        });
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to delete department.'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Delete Error:', xhr);
                    
                    // Re-enable delete button on error
                    const deleteButton = $(`button[onclick="deleteDepartment(${id})"]`);
                    deleteButton.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                    
                    let errorMessage = 'An error occurred while deleting the department.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 404) {
                        errorMessage = 'Department not found or may have already been deleted.';
                    } else if (xhr.status === 403) {
                        errorMessage = 'You do not have permission to delete this department.';
                    } else if (xhr.status === 401) {
                        errorMessage = 'You are not authorized to perform this action. Please log in again.';
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete Failed!',
                        text: errorMessage
                    });
                }
            });
        }
    });
}

// Category operations
function viewCategory(id) {
    console.log('Viewing category:', id);
}

function editCategory(id) {
    console.log('Editing category:', id);
}

function deleteCategory(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Deleting category:', id);
            Swal.fire('Deleted!', 'Category has been deleted.', 'success');
        }
    });
}

// Sector operations
function viewSector(id) {
    console.log('Viewing sector:', id);
    
    $.ajax({
        url: `{{ url('company/Categories/business-sectors') }}/${id}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                const sector = response.data;
                
                // Populate the view modal
                $('#viewSectorName').text(sector.name);
                $('#viewSectorHeadName').text(sector.head_name || '-');
                $('#viewSectorDescription').text(sector.description || '-');
                
                // Format status with badge
                const statusBadge = sector.status === 'active' 
                    ? '<span class="badge bg-success">Active</span>' 
                    : '<span class="badge bg-danger">Inactive</span>';
                $('#viewSectorStatus').html(statusBadge);
                
                // Format created date
                const createdDate = new Date(sector.created_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                $('#viewSectorCreatedAt').text(createdDate);
                
                // Handle sub-sectors - ensure it's always an array
                let subSectors = [];
                if (sector.sub_sectors) {
                    if (Array.isArray(sector.sub_sectors)) {
                        subSectors = sector.sub_sectors;
                    } else if (typeof sector.sub_sectors === 'string') {
                        try {
                            subSectors = JSON.parse(sector.sub_sectors);
                            if (!Array.isArray(subSectors)) {
                                subSectors = [];
                            }
                        } catch (e) {
                            subSectors = [];
                        }
                    }
                }
                
                // Display sub-sectors
                if (subSectors && subSectors.length > 0) {
                    let subSectorsHtml = '<ul class="list-unstyled mb-0">';
                    subSectors.forEach(subSector => {
                        subSectorsHtml += `<li><i class="fas fa-arrow-right me-2 text-muted"></i>${subSector}</li>`;
                    });
                    subSectorsHtml += '</ul>';
                    $('#viewSectorSubSectors').html(subSectorsHtml);
                } else {
                    $('#viewSectorSubSectors').html('<span class="text-muted">No sub-sectors</span>');
                }
                
                // Show the modal
                $('#viewSectorModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to load business sector details.'
                });
            }
        },
        error: function(xhr) {
            console.error('View Sector Error:', xhr);
            let errorMessage = 'An error occurred while loading business sector details.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 404) {
                errorMessage = 'Business sector not found.';
            } else if (xhr.status === 403) {
                errorMessage = 'You do not have permission to view this business sector.';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Load Failed!',
                text: errorMessage
            });
        }
    });
}

function editSector(id) {
    console.log('Editing sector:', id);
    
    // First, fetch the sector data
    $.ajax({
        url: `{{ url('company/Categories/business-sectors') }}/${id}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                const sector = response.data;
                
                // Populate the edit form
                console.log('Populating edit form with sector data:', sector);
                
                // Add a small delay to ensure modal is fully loaded
                setTimeout(() => {
                    // Debug: Check if form fields exist before setting values
                    console.log('Form field elements found before setting:');
                    console.log('ID element:', $('#editSectorId').length);
                    console.log('Name element:', $('#editSectorName').length);
                    console.log('Head Name element:', $('#editSectorHeadName').length);
                    console.log('Description element:', $('#editSectorDescription').length);
                    console.log('Status element:', $('#editSectorStatus').length);
                    
                    // Only set values if elements exist
                    if ($('#editSectorId').length > 0) {
                        $('#editSectorId').val(sector.id);
                        console.log('Set ID to:', sector.id);
                    }
                    if ($('#editSectorName').length > 0) {
                        $('#editSectorName').val(sector.name);
                        console.log('Set Name to:', sector.name);
                    }
                    if ($('#editSectorHeadName').length > 0) {
                        $('#editSectorHeadName').val(sector.head_name || '');
                        console.log('Set Head Name to:', sector.head_name || '');
                    }
                    if ($('#editSectorDescription').length > 0) {
                        $('#editSectorDescription').val(sector.description || '');
                        console.log('Set Description to:', sector.description || '');
                    }
                    if ($('#editSectorStatus').length > 0) {
                        $('#editSectorStatus').val(sector.status);
                        console.log('Set Status to:', sector.status);
                    }
                
                    // Debug: Verify form field values after setting
                    console.log('Form field values after setting:');
                    console.log('ID:', $('#editSectorId').val());
                    console.log('Name:', $('#editSectorName').val());
                    console.log('Head Name:', $('#editSectorHeadName').val());
                    console.log('Description:', $('#editSectorDescription').val());
                    console.log('Status:', $('#editSectorStatus').val());
                }, 100);
                
                // Handle sub-sectors - ensure it's always an array
                if (sector.sub_sectors && Array.isArray(sector.sub_sectors)) {
                    editSubSectors = sector.sub_sectors;
                } else if (sector.sub_sectors && typeof sector.sub_sectors === 'string') {
                    try {
                        editSubSectors = JSON.parse(sector.sub_sectors);
                        if (!Array.isArray(editSubSectors)) {
                            editSubSectors = [];
                        }
                    } catch (e) {
                        editSubSectors = [];
                    }
                } else {
                    editSubSectors = [];
                }
                updateEditSubSectorsList();
                
                // Show the edit modal
                $('#editSectorModal').modal('show');
            }
        },
        error: function(xhr) {
            console.error('Error fetching sector:', xhr);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to fetch business sector details.'
            });
        }
    });
}

function deleteSector(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Deleting sector:', id);
            
            $.ajax({
                url: `{{ url('company/Categories/business-sectors') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        // Stay on current tab (sectors)
                        if (typeof sectorsDataTable !== 'undefined' && sectorsDataTable) {
                            sectorsDataTable.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                        refreshAllStats();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to delete business sector.'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error deleting sector:', xhr);
                    let errorMessage = 'An error occurred while deleting the business sector.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                }
            });
        }
    });
}

// Type operations
function viewType(id) {
    console.log('Viewing type:', id);
}

function editType(id) {
    console.log('Editing type:', id);
}

function deleteType(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Deleting type:', id);
            Swal.fire('Deleted!', 'Business type has been deleted.', 'success');
        }
    });
}

// Allow Enter key to add sub departments
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('subDepartmentInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addSubDepartment();
        }
    });
    
    document.getElementById('editSubDepartmentInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addSubDepartmentEdit();
        }
    });
});

// Category Management Functions
let categoriesDataTable;
let subCategories = [];
let editSubCategories = [];

// DataTable instances
let sectorsDataTable = null;

// Business Sector Management Functions
let subSectors = [];
let editSubSectors = [];

// Categories DataTable is now initialized in the main initializeDataTables() function

// Category CRUD Functions
function viewCategory(id) {
    $.ajax({
        url: '{{ route("categories.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                const category = response.data;
                
                // Populate view modal
                $('#viewCategoryName').text(category.name);
                $('#viewCategoryCode').text(category.code);
                $('#viewCategoryDescription').text(category.description || 'No description provided');
                $('#viewCategoryHead').text(category.head_name || 'Not assigned');
                $('#viewCategoryStatus').text(category.status.charAt(0).toUpperCase() + category.status.slice(1));
                $('#viewCategoryColor').html(`<span class="badge" style="background-color: ${category.color}">${category.color}</span>`);
                $('#viewCategorySubCategories').text(category.sub_categories.length > 0 ? category.sub_categories.join(', ') : 'None');
                $('#viewCategoryCreatedBy').text(category.created_by ? category.created_by.fullname : 'System');
                $('#viewCategoryUpdatedBy').text(category.updated_by ? category.updated_by.fullname : 'System');
                $('#viewCategoryCreatedAt').text(category.created_at);
                $('#viewCategoryUpdatedAt').text(category.updated_at);
                
                // Show modal
                $('#viewCategoryModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to load category details.'
                });
            }
        },
        error: function(xhr) {
            console.error('View Category Error:', xhr);
            let errorMessage = 'An error occurred while loading category details.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 404) {
                errorMessage = 'Category not found.';
            } else if (xhr.status === 403) {
                errorMessage = 'You do not have permission to view this category.';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Load Failed!',
                text: errorMessage
            });
        }
    });
}

function editCategory(id) {
    $.ajax({
        url: '{{ route("categories.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                const category = response.data;
                
                // Populate edit form
                $('#editCategoryId').val(category.id);
                $('#editCategoryName').val(category.name);
                $('#editCategoryCode').val(category.code);
                $('#editCategoryDescription').val(category.description);
                $('#editCategoryHead').val(category.head_name);
                $('#editCategoryStatus').val(category.status);
                $('#editCategoryColor').val(category.color);
                $('#editCategorySortOrder').val(category.sort_order);
                
                // Handle sub categories
                editSubCategories = category.sub_categories || [];
                updateEditSubCategoriesList();
                
                // Show modal
                $('#editCategoryModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to load category details.'
                });
            }
        },
        error: function(xhr) {
            console.error('Edit Category Error:', xhr);
            let errorMessage = 'An error occurred while loading category details.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 404) {
                errorMessage = 'Category not found.';
            } else if (xhr.status === 403) {
                errorMessage = 'You do not have permission to edit this category.';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Load Failed!',
                text: errorMessage
            });
        }
    });
}

function deleteCategory(id) {
    // Get category name from the table row for better UX
    let categoryName = 'this category';
    try {
        const row = $(`button[onclick="deleteCategory(${id})"]`).closest('tr');
        const nameCell = row.find('td:nth-child(2)');
        if (nameCell.length) {
            categoryName = `"${nameCell.text().trim()}"`;
        }
    } catch (e) {
        console.log('Could not extract category name, using default');
    }

    Swal.fire({
        title: 'Are you sure?',
        html: `You are about to delete ${categoryName}.<br><br><strong>This action cannot be undone!</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Disable delete button to prevent double-clicks
            const deleteButton = $(`button[onclick="deleteCategory(${id})"]`);
            deleteButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            // Show loading state
            Swal.fire({
                title: 'Deleting Category...',
                text: 'Please wait while we delete the category.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make AJAX call to delete category
            $.ajax({
                url: '{{ route("categories.destroy", ":id") }}'.replace(':id', id),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Refresh DataTable after SweetAlert closes
                            if (typeof categoriesDataTable !== 'undefined' && categoriesDataTable) {
                                categoriesDataTable.ajax.reload(null, false);
                            } else {
                                // If DataTable not initialized, reload the page
                                location.reload();
                            }
                        
                        // Refresh stats
                        refreshAllStats();
                        });
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to delete category.'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Delete Error:', xhr);
                    
                    // Re-enable delete button on error
                    const deleteButton = $(`button[onclick="deleteCategory(${id})"]`);
                    deleteButton.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                    
                    let errorMessage = 'An error occurred while deleting the category.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 404) {
                        errorMessage = 'Category not found or may have already been deleted.';
                    } else if (xhr.status === 403) {
                        errorMessage = 'You do not have permission to delete this category.';
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                }
            });
        }
    });
}

// Category Form Handlers
$('#addCategoryForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Add sub-categories as individual array elements
    subCategories.forEach((subCategory, index) => {
        formData.append(`sub_categories[${index}]`, subCategory);
    });
    
    // Debug: Log the form data
    console.log('Sub-categories being sent:', subCategories);
    console.log('Form data entries:', Array.from(formData.entries()));
    
    // Show loading state
    Swal.fire({
        title: 'Creating Category...',
        text: 'Please wait while we create the category.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: '{{ route("categories.store") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            if (response.success) {
                // Reset form and close modal
                $('#addCategoryForm')[0].reset();
                subCategories = [];
                updateSubCategoriesList();
                
                // Properly close modal and remove backdrop
                $('#addCategoryModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Refresh DataTable after SweetAlert closes
                    if (typeof categoriesDataTable !== 'undefined' && categoriesDataTable) {
                        categoriesDataTable.ajax.reload(null, false);
                    } else {
                        // If DataTable not initialized, reload the page
                        location.reload();
                }
                
                // Refresh stats
                refreshAllStats();
                });
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to create category.'
                });
            }
        },
        error: function(xhr) {
            console.error('Create Category Error:', xhr);
            
            let errorMessage = 'An error occurred while creating the category.';
            
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    const errorMessages = Object.values(errors).flat();
                    errorMessage = errorMessages.join('<br>');
                }
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage
            });
        }
    });
});

$('#editCategoryForm').on('submit', function(e) {
    e.preventDefault();
    
    const categoryId = $('#editCategoryId').val();
    const formData = new FormData(this);
    
    // Add sub-categories as individual array elements
    editSubCategories.forEach((subCategory, index) => {
        formData.append(`sub_categories[${index}]`, subCategory);
    });
    
    // Debug: Log the form data
    console.log('Edit sub-categories being sent:', editSubCategories);
    console.log('Edit form data entries:', Array.from(formData.entries()));
    
    // Show loading state
    Swal.fire({
        title: 'Updating Category...',
        text: 'Please wait while we update the category.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: '{{ route("categories.update", ":id") }}'.replace(':id', categoryId),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PUT'
        },
        success: function(response) {
            if (response.success) {
                // Properly close modal and remove backdrop
                $('#editCategoryModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Refresh DataTable after SweetAlert closes
                    if (typeof categoriesDataTable !== 'undefined' && categoriesDataTable) {
                        categoriesDataTable.ajax.reload(null, false);
                    } else {
                        // If DataTable not initialized, reload the page
                        location.reload();
                }
                
                // Refresh stats
                refreshAllStats();
                });
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to update category.'
                });
            }
        },
        error: function(xhr) {
            console.error('Update Category Error:', xhr);
            
            let errorMessage = 'An error occurred while updating the category.';
            
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    const errorMessages = Object.values(errors).flat();
                    errorMessage = errorMessages.join('<br>');
                }
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage
            });
        }
    });
});

// Sub Categories Management
function addSubCategory() {
    const input = document.getElementById('subCategoryInput');
    const subCategoryName = input.value.trim();
    
    if (subCategoryName && !subCategories.includes(subCategoryName)) {
        subCategories.push(subCategoryName);
        updateSubCategoriesList();
        input.value = '';
    }
}

function removeSubCategory(index) {
    subCategories.splice(index, 1);
    updateSubCategoriesList();
}

function updateSubCategoriesList() {
    const list = document.getElementById('subCategoriesList');
    list.innerHTML = '';
    
    subCategories.forEach((subCategory, index) => {
        const item = document.createElement('div');
        item.className = 'd-flex justify-content-between align-items-center mb-2 p-2 border rounded';
        item.innerHTML = `
            <span>${subCategory}</span>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSubCategory(${index})">
                <i class="fas fa-times"></i>
            </button>
        `;
        list.appendChild(item);
    });
}

function addSubCategoryEdit() {
    const input = document.getElementById('editSubCategoryInput');
    const subCategoryName = input.value.trim();
    
    if (subCategoryName && !editSubCategories.includes(subCategoryName)) {
        editSubCategories.push(subCategoryName);
        updateEditSubCategoriesList();
        input.value = '';
    }
}

function removeSubCategoryEdit(index) {
    editSubCategories.splice(index, 1);
    updateEditSubCategoriesList();
}

function updateEditSubCategoriesList() {
    const list = document.getElementById('editSubCategoriesList');
    list.innerHTML = '';
    
    editSubCategories.forEach((subCategory, index) => {
        const item = document.createElement('div');
        item.className = 'd-flex justify-content-between align-items-center mb-2 p-2 border rounded';
        item.innerHTML = `
            <span>${subCategory}</span>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSubCategoryEdit(${index})">
                <i class="fas fa-times"></i>
            </button>
        `;
        list.appendChild(item);
    });
}

// Sub Sectors Management
function addSubSector() {
    const input = document.getElementById('subSectorInput');
    const subSectorName = input.value.trim();
    
    if (subSectorName && !subSectors.includes(subSectorName)) {
        subSectors.push(subSectorName);
        updateSubSectorsList();
        input.value = '';
    }
}

function removeSubSector(index) {
    subSectors.splice(index, 1);
    updateSubSectorsList();
}

function updateSubSectorsList() {
    const list = document.getElementById('subSectorsList');
    if (list) {
        list.innerHTML = '';
        
        subSectors.forEach((subSector, index) => {
            const item = document.createElement('div');
            item.className = 'd-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded';
            item.innerHTML = `
                <span>${subSector}</span>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSubSector(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            list.appendChild(item);
        });
    }
}

function addSubSectorEdit() {
    const input = document.getElementById('editSubSectorInput');
    const subSectorName = input.value.trim();
    
    if (subSectorName && !editSubSectors.includes(subSectorName)) {
        editSubSectors.push(subSectorName);
        updateEditSubSectorsList();
        input.value = '';
    }
}

function removeSubSectorEdit(index) {
    editSubSectors.splice(index, 1);
    updateEditSubSectorsList();
}

function updateEditSubSectorsList() {
    const list = document.getElementById('editSubSectorsList');
    if (list) {
        list.innerHTML = '';
        
        // Ensure editSubSectors is an array
        if (!Array.isArray(editSubSectors)) {
            editSubSectors = [];
        }
        
        editSubSectors.forEach((subSector, index) => {
            const item = document.createElement('div');
            item.className = 'd-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded';
            item.innerHTML = `
                <span>${subSector}</span>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSubSectorEdit(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            list.appendChild(item);
        });
    }
}

// Category Filter Functions
function applyCategoryFilters() {
    console.log('Applying category filters...');
        const searchTerm = $('#searchCategories').val();
        const statusFilter = $('#filterCategoryStatus').val();
        
    console.log('Search term:', searchTerm);
    console.log('Status filter:', statusFilter);
    
    if (categoriesDataTable) {
        // Apply search
        if (searchTerm) {
            categoriesDataTable.search(searchTerm).draw();
        }
        
        // Apply status filter
        if (statusFilter) {
            categoriesDataTable.column(6).search(statusFilter).draw();
        }
        
        // If no filters, clear all
        if (!searchTerm && !statusFilter) {
            categoriesDataTable.search('').columns().search('').draw();
        }
    } else {
        // Fallback: client-side filtering
        filterCategoriesClientSide(searchTerm.toLowerCase().trim(), statusFilter, true);
    }
}

function clearCategoryFilters() {
    console.log('Clearing category filters...');
    $('#searchCategories').val('');
    $('#filterCategoryStatus').val('');
    
    if (categoriesDataTable) {
        categoriesDataTable.search('').columns().search('').draw();
        console.log('Category filters cleared');
    } else {
        // Fallback: show all rows
        $('#categories-datatable tbody tr').show();
        console.log('Category filters cleared (client-side)');
    }
}

// Category Export Functions
function exportCategories(format) {
    const searchTerm = $('#searchCategories').val();
    const statusFilter = $('#filterCategoryStatus').val();
    
    let url = '{{ route("categories.export") }}?format=' + format;
    
    if (searchTerm) {
        url += '&search=' + encodeURIComponent(searchTerm);
    }
    
    if (statusFilter) {
        url += '&status=' + encodeURIComponent(statusFilter);
    }
    
    // Show loading state
    Swal.fire({
        title: 'Preparing Export...',
        text: 'Please wait while we prepare your export file.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Create a temporary link to trigger download
    const link = document.createElement('a');
    link.href = url;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Hide loading state after a short delay
    setTimeout(() => {
        Swal.close();
    }, 2000);
}

// Initialize categories when tab is shown (backup initialization)
$('#categories-tab').on('shown.bs.tab', function() {
    console.log('Categories tab clicked');
    if (categoriesDataTable) {
        console.log('Categories DataTable already initialized');
        // Refresh the table if needed
        categoriesDataTable.draw();
    } else {
        console.log('Categories DataTable not initialized yet');
    }
});

// Business Sectors Functions
function exportSectors(format) {
    const searchTerm = $('#searchSectors').val();
    const statusFilter = $('#filterSectorStatus').val();
    
    let url = '{{ route("business-sectors.export") }}?format=' + format;
    
    if (searchTerm) {
        url += '&search=' + encodeURIComponent(searchTerm);
    }
    
    if (statusFilter) {
        url += '&status=' + encodeURIComponent(statusFilter);
    }
    
    // Show loading state
    Swal.fire({
        title: 'Preparing Export...',
        text: 'Please wait while we prepare your export file.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Create a temporary link to trigger download
    const link = document.createElement('a');
    link.href = url;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Hide loading state after a short delay
    setTimeout(() => {
        Swal.close();
    }, 2000);
}

function applySectorFilters() {
    const searchTerm = $('#searchSectors').val();
    const statusFilter = $('#filterSectorStatus').val();
    
    // Apply search
    if (searchTerm) {
        sectorsDataTable.search(searchTerm).draw();
    }
    
    // Apply status filter
    if (statusFilter) {
        sectorsDataTable.column(4).search(statusFilter).draw();
    }
    
    // If no filters, show all
    if (!searchTerm && !statusFilter) {
        sectorsDataTable.search('').columns().search('').draw();
    }
}

function clearSectorFilters() {
    $('#searchSectors').val('');
    $('#filterSectorStatus').val('');
    
    if (sectorsDataTable) {
        sectorsDataTable.search('').columns().search('').draw();
    }
}

// Bulk Upload Sectors Form Handler
$('#bulkUploadSectorsForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    Swal.fire({
        title: 'Uploading Sectors...',
        text: 'Please wait while we process your file.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: '{{ route("business-sectors.import") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            Swal.close();
            
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Upload Successful!',
                    text: response.message || 'Business sectors uploaded successfully.',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    // Close modal
                    $('#bulkUploadSectorsModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    
                    // Refresh table and stats
                    if (sectorsDataTable) {
                        sectorsDataTable.ajax.reload(null, false);
                    }
                    refreshAllStats();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed!',
                    text: response.message || 'Failed to upload business sectors.'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            
            let errorMessage = 'An error occurred while uploading the file.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                const firstError = Object.values(errors)[0];
                errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Upload Error!',
                text: errorMessage
            });
        }
    });
});

// Categories search handlers are now set up in setupSearchAndFilters() function

// Function to clear categories search input
function clearCategoriesSearchInput() {
    $('#searchCategories').val('').trigger('input');
    $('#searchCategories').focus(); // Return focus to search input
}

// Function to clear business sectors search input
function clearSectorsSearchInput() {
    $('#searchSectors').val('').trigger('input');
    $('#searchSectors').focus(); // Return focus to search input
}

// Business sectors search handlers are now set up in setupSearchAndFilters() function

// Initialize sectors when tab is shown
$('#sectors-tab').on('shown.bs.tab', function() {
    console.log('Sectors tab clicked');
    if (sectorsDataTable) {
        console.log('Sectors DataTable already initialized');
        sectorsDataTable.draw();
    } else {
        console.log('Sectors DataTable not initialized yet');
    }
});

// Event listeners for sub category inputs
document.getElementById('subCategoryInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addSubCategory();
    }
});

document.getElementById('editSubCategoryInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addSubCategoryEdit();
    }
});



</script>
@endsection
