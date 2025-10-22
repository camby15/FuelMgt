@extends('layouts.vertical', ['page_title' => 'Material Balance', 'mode' => session('theme_mode', 'light')])

@section('css')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<!-- DataTables CDN -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<!-- Select2 for multi-select -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Chart.js for visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .material-container {
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
    .card-materials { border-left-color: #007bff; }
    .card-issued { border-left-color: #28a745; }
    .card-used { border-left-color: #ffc107; }
    .card-balance { border-left-color: #17a2b8; }

    /* Material Balance Table Styles */
    .material-table {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .material-table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        text-align: center;
    }

    .material-table table {
        margin-bottom: 0;
        font-size: 11px;
    }

    .material-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        text-align: center;
        border: 1px solid #dee2e6;
        padding: 8px 4px;
        font-size: 10px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .material-table td {
        text-align: center;
        border: 1px solid #dee2e6;
        padding: 6px 4px;
        font-size: 10px;
        vertical-align: middle;
    }

    /* Fixed columns styling */
    .material-fixed {
        background: #f8f9fa !important;
        font-weight: 600;
        position: sticky;
        left: 0;
        z-index: 10;
    }

    .material-name-cell {
        text-align: left !important;
        font-weight: 600;
        max-width: 120px;
        background: #f8f9fa !important;
    }

    .description-cell {
        text-align: left !important;
        max-width: 150px;
        font-size: 9px;
        color: #6c757d;
        background: #f8f9fa !important;
    }

    /* Team header colors */
    .team-header-linfra {
        background: #007bff !important;
        color: white;
    }

    .team-header-gesl {
        background: #28a745 !important;
        color: white;
    }

    .team-header-1 { background: #6f42c1 !important; color: white; }
    .team-header-2 { background: #e83e8c !important; color: white; }
    .team-header-3 { background: #fd7e14 !important; color: white; }
    .team-header-4 { background: #20c997 !important; color: white; }
    .team-header-5 { background: #6610f2 !important; color: white; }
    .team-header-6 { background: #d63384 !important; color: white; }
    .team-header-7 { background: #fd7e14 !important; color: white; }
    .team-header-8 { background: #198754 !important; color: white; }
    .team-header-9 { background: #0dcaf0 !important; color: white; }
    .team-header-10 { background: #6c757d !important; color: white; }

    /* Stock level indicators */
    .stock-good {
        background-color: #d4edda !important;
        color: #155724;
        font-weight: 600;
    }

    .stock-warning {
        background-color: #fff3cd !important;
        color: #856404;
        font-weight: 600;
    }

    .stock-critical {
        background-color: #f8d7da !important;
        color: #721c24;
        font-weight: 600;
    }

    .stock-empty {
        background-color: #f8d7da !important;
        color: #721c24;
        font-weight: 600;
    }

    /* Filter Panel */
    .filter-panel {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    /* Chart containers */
    .chart-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
        height: 400px;
    }

    .chart-title {
        font-size: 16px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 15px;
        text-align: center;
    }

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

    .form-floating > label {
        padding: 1rem 0.75rem;
    }

    .required-field::after {
        content: " *";
        color: #e74c3c;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .material-table th,
        .material-table td {
            font-size: 9px;
            padding: 4px 2px;
        }

        .card-value {
            font-size: 24px;
        }

        .chart-container {
            height: 300px;
        }
    }

    /* Material category colors */
    .category-ont { border-left: 3px solid #007bff; }
    .category-cable { border-left: 3px solid #28a745; }
    .category-connector { border-left: 3px solid #ffc107; }
    .category-tools { border-left: 3px solid #dc3545; }
    .category-other { border-left: 3px solid #6c757d; }

    /* Table scroll container */
    .table-scroll-container {
        overflow-x: auto;
        position: relative;
    }

    .material-balance-table {
        min-width: 1500px;
        white-space: nowrap;
    }

    /* Fixed left columns */
    .fixed-left {
        position: sticky;
        left: 0;
        background: #f8f9fa;
        z-index: 5;
        border-right: 2px solid #dee2e6;
    }

    .fixed-left-2 {
        position: sticky;
        left: 120px;
        background: #f8f9fa;
        z-index: 5;
        border-right: 2px solid #dee2e6;
    }
</style>
@endsection

@section('content')
<div class="material-container">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Tracker</a></li>
                        <li class="breadcrumb-item active">Material Balance</li>
                    </ol>
                </div>
                <h4 class="page-title">Material Balance Management</h4>
                <p class="text-muted mb-0">Track material inventory across teams and departments</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-materials">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h6 class="card-title">Total Materials</h6>
                    <h3 class="card-value" id="totalMaterials">0</h3>
                    <div class="card-change">
                        <i class="fas fa-layer-group me-1"></i> Active materials
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-issued">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <h6 class="card-title">Total Issued</h6>
                    <h3 class="card-value" id="totalIssued">0</h3>
                    <div class="card-change">
                        <i class="fas fa-shipping-fast me-1"></i> Items issued
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-used">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <h6 class="card-title">Total Used</h6>
                    <h3 class="card-value" id="totalUsed">0</h3>
                    <div class="card-change">
                        <i class="fas fa-tools me-1"></i> Items consumed
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-balance">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h6 class="card-title">Total Balance</h6>
                    <h3 class="card-value" id="totalBalance">0</h3>
                    <div class="card-change">
                        <i class="fas fa-warehouse me-1"></i> Available stock
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                        <i class="fas fa-plus me-2"></i>Add Material
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                        <i class="fas fa-upload me-2"></i>Bulk Upload
                    </button>
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#issueMaterialModal">
                        <i class="fas fa-share me-2"></i>Issue Material
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#useMaterialModal">
                        <i class="fas fa-minus me-2"></i>Use Material
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="exportData('excel')">
                                <i class="fas fa-file-excel me-2"></i>Excel Report
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportData('pdf')">
                                <i class="fas fa-file-pdf me-2"></i>PDF Report
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportData('csv')">
                                <i class="fas fa-file-csv me-2"></i>CSV Data
                            </a></li>
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>More Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="refreshData()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh Data
                            </a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#stockReportModal">
                                <i class="fas fa-chart-bar me-2"></i>Stock Report
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="resetFilters()">
                                <i class="fas fa-filter me-2"></i>Reset Filters
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="text-muted">
                    <small><i class="fas fa-info-circle me-1"></i>Last updated: <span id="lastUpdated">Loading...</span></small>
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
                    <option value="ont-patch-cord">ONT & Patch Cord</option>
                    <option value="cable">Cables</option>
                    <option value="connector">Connectors</option>
                    <option value="tools">Tools</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="teamFilter" class="form-label">Team</label>
                <select class="form-select form-select-sm" id="teamFilter">
                    <option value="all">All Teams</option>
                    <option value="linfra">Linfra</option>
                    <option value="gesl">GESL</option>
                    <option value="team1">Team 1</option>
                    <option value="team2">Team 2</option>
                    <option value="team3">Team 3</option>
                    <option value="team4">Team 4</option>
                    <option value="team5">Team 5</option>
                    <option value="team6">Team 6</option>
                    <option value="team7">Team 7</option>
                    <option value="team8">Team 8</option>
                    <option value="team9">Team 9</option>
                    <option value="team10">Team 10</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="stockFilter" class="form-label">Stock Level</label>
                <select class="form-select form-select-sm" id="stockFilter">
                    <option value="all">All Levels</option>
                    <option value="good">Good (>50)</option>
                    <option value="warning">Warning (11-50)</option>
                    <option value="critical">Critical (1-10)</option>
                    <option value="empty">Empty (0)</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="materialSearch" class="form-label">Search Material</label>
                <input type="text" class="form-control form-control-sm" id="materialSearch" placeholder="Search by name or description...">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="applyFilters()">
                        <i class="fas fa-filter me-1"></i>Apply
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                        <i class="fas fa-times me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="toggleTableView()">
                        <i class="fas fa-expand me-1"></i>Expand
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Material Balance Table -->
    <div class="material-table">
        <div class="material-table-header">
            <h5 class="mb-0">
                <i class="fas fa-chart-bar me-2"></i>Material Balance Report
                <span class="float-end">
                    <small>Updated: <span id="tableLastUpdated">Just now</span></small>
                </span>
            </h5>
        </div>
        
        <div class="table-scroll-container">
            <table class="table table-sm mb-0 material-balance-table" id="materialTable">
                <thead>
                    <tr>
                        <th rowspan="2" class="fixed-left" style="width: 120px;">Material</th>
                        <th rowspan="2" class="fixed-left-2" style="width: 150px;">Description</th>
                        <th colspan="3" class="team-header-linfra">Linfra</th>
                        <th colspan="3" class="team-header-gesl">GESL</th>
                        <th colspan="3" class="team-header-1">Team 1</th>
                        <th colspan="3" class="team-header-2">Team 2</th>
                        <th colspan="3" class="team-header-3">Team 3</th>
                        <th colspan="3" class="team-header-4">Team 4</th>
                        <th colspan="3" class="team-header-5">Team 5</th>
                        <th colspan="3" class="team-header-6">Team 6</th>
                        <th colspan="3" class="team-header-7">Team 7</th>
                        <th colspan="3" class="team-header-8">Team 8</th>
                        <th colspan="3" class="team-header-9">Team 9</th>
                        <th colspan="3" class="team-header-10">Team 10</th>
                    </tr>
                    <tr>
                        <!-- Linfra -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                        <!-- GESL -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                        <!-- Team 1 -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                        <!-- Team 2 -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                        <!-- Team 3 -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                        <!-- Team 4 -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                        <!-- Team 5 -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                        <!-- Team 6 -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                        <!-- Team 7 -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                        <!-- Team 8 -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                        <!-- Team 9 -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                        <!-- Team 10 -->
                        <th style="width: 60px;">Issued</th>
                        <th style="width: 60px;">Used</th>
                        <th style="width: 60px;">Balance</th>
                    </tr>
                </thead>
                <tbody id="materialTableBody">
                    <!-- Data will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row" id="chartsSection">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">Material Distribution by Team</div>
                <canvas id="materialDistributionChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">Stock Level Analysis</div>
                <canvas id="stockLevelChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Add Material Modal -->
<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMaterialModalLabel">Add New Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addMaterialForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="material_name" id="materialName" required placeholder=" ">
                                <label for="materialName" class="required-field">Material Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="category" id="materialCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="ont-patch-cord">ONT & Patch Cord</option>
                                    <option value="cable">Cables</option>
                                    <option value="connector">Connectors</option>
                                    <option value="tools">Tools</option>
                                    <option value="other">Other</option>
                                </select>
                                <label for="materialCategory" class="required-field">Category</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="description" id="materialDescription" placeholder=" " style="height: 80px"></textarea>
                                <label for="materialDescription">Description</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="unit" id="materialUnit" placeholder=" ">
                                <label for="materialUnit">Unit (e.g., pcs, meters, kg)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="minimum_stock" id="minimumStock" min="0" placeholder=" ">
                                <label for="minimumStock">Minimum Stock Level</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="unit_cost" id="unitCost" step="0.01" min="0" placeholder=" ">
                                <label for="unitCost">Unit Cost</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="materialStatus">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="discontinued">Discontinued</option>
                                </select>
                                <label for="materialStatus">Status</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Material</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Issue Material Modal -->
<div class="modal fade" id="issueMaterialModal" tabindex="-1" aria-labelledby="issueMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issueMaterialModalLabel">Issue Material to Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="issueMaterialForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="issueMaterial" class="form-label required-field">Material</label>
                            <select class="form-select" name="material_id" id="issueMaterial" required style="width: 100%;">
                                <option value="">Select Material</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="team" id="issueTeam" required>
                                    <option value="">Select Team</option>
                                    <option value="linfra">Linfra</option>
                                    <option value="gesl">GESL</option>
                                    <option value="team1">Team 1</option>
                                    <option value="team2">Team 2</option>
                                    <option value="team3">Team 3</option>
                                    <option value="team4">Team 4</option>
                                    <option value="team5">Team 5</option>
                                    <option value="team6">Team 6</option>
                                    <option value="team7">Team 7</option>
                                    <option value="team8">Team 8</option>
                                    <option value="team9">Team 9</option>
                                    <option value="team10">Team 10</option>
                                </select>
                                <label for="issueTeam" class="required-field">Team</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="quantity" id="issueQuantity" required min="1" placeholder=" ">
                                <label for="issueQuantity" class="required-field">Quantity</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="issue_date" id="issueDate" required>
                                <label for="issueDate" class="required-field">Issue Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="issued_by" id="issuedBy" placeholder=" ">
                                <label for="issuedBy">Issued By</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="received_by" id="receivedBy" placeholder=" ">
                                <label for="receivedBy">Received By</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="issueNotes" placeholder=" " style="height: 80px"></textarea>
                                <label for="issueNotes">Notes</label>
                            </div>
                        </div>
                        <div class="col-12" id="currentStockInfo" style="display: none;">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Current Stock Information</h6>
                                <p class="mb-0">Available Stock: <span id="availableStock">0</span> units</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Issue Material</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Use Material Modal -->
<div class="modal fade" id="useMaterialModal" tabindex="-1" aria-labelledby="useMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="useMaterialModalLabel">Record Material Usage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="useMaterialForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="useMaterial" class="form-label required-field">Material</label>
                            <select class="form-select" name="material_id" id="useMaterial" required style="width: 100%;">
                                <option value="">Select Material</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="team" id="useTeam" required>
                                    <option value="">Select Team</option>
                                    <option value="linfra">Linfra</option>
                                    <option value="gesl">GESL</option>
                                    <option value="team1">Team 1</option>
                                    <option value="team2">Team 2</option>
                                    <option value="team3">Team 3</option>
                                    <option value="team4">Team 4</option>
                                    <option value="team5">Team 5</option>
                                    <option value="team6">Team 6</option>
                                    <option value="team7">Team 7</option>
                                    <option value="team8">Team 8</option>
                                    <option value="team9">Team 9</option>
                                    <option value="team10">Team 10</option>
                                </select>
                                <label for="useTeam" class="required-field">Team</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="quantity" id="useQuantity" required min="1" placeholder=" ">
                                <label for="useQuantity" class="required-field">Quantity Used</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="use_date" id="useDate" required>
                                <label for="useDate" class="required-field">Usage Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="project_site" id="projectSite" placeholder=" ">
                                <label for="projectSite">Project/Site</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="used_by" id="usedBy" placeholder=" ">
                                <label for="usedBy">Used By</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="usage_notes" id="usageNotes" placeholder=" " style="height: 80px"></textarea>
                                <label for="usageNotes">Usage Notes</label>
                            </div>
                        </div>
                        <div class="col-12" id="teamBalanceInfo" style="display: none;">
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Team Balance Information</h6>
                                <p class="mb-0">Team Balance: <span id="teamBalance">0</span> units</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Record Usage</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload Material Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkUploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Upload Instructions</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Download the template file and fill in your material data</li>
                                    <li>Supported formats: Excel (.xlsx), CSV (.csv)</li>
                                    <li>Maximum file size: 10MB</li>
                                    <li>Existing materials will be updated if names match</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="card border-dashed">
                                <div class="card-body text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-download fa-2x text-primary mb-2"></i>
                                        <h6>Download Template</h6>
                                        <p class="text-muted mb-0">Get the template file with proper column headers</p>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="/company/MasterTracker/material-balance/template/excel" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-file-excel me-1"></i>Excel Template
                                        </a>
                                        <a href="/company/MasterTracker/material-balance/template/csv" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-file-csv me-1"></i>CSV Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label for="bulkFile" class="form-label required-field">Upload File</label>
                            <div class="input-group">
                                <input type="file" class="form-control" name="bulk_file" id="bulkFile" 
                                       accept=".xlsx,.xls,.csv" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearFileInput()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <small>Accepted formats: .xlsx, .xls, .csv (Max: 10MB)</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="default_category" id="defaultCategory">
                                    <option value="">Use File Data</option>
                                    <option value="ont-patch-cord">ONT & Patch Cord</option>
                                    <option value="cable">Cables</option>
                                    <option value="connector">Connectors</option>
                                    <option value="tools">Tools</option>
                                    <option value="other">Other</option>
                                </select>
                                <label for="defaultCategory">Default Category</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="default_status" id="defaultStatus">
                                    <option value="">Use File Data</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="discontinued">Discontinued</option>
                                </select>
                                <label for="defaultStatus">Default Status</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="updateExisting" name="update_existing" checked>
                                <label class="form-check-label" for="updateExisting">
                                    Update existing materials if name matches
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="skipErrors" name="skip_errors" checked>
                                <label class="form-check-label" for="skipErrors">
                                    Skip rows with errors and continue processing
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12" id="uploadProgress" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="text-muted mt-1">Processing file...</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="uploadBtn">
                        <i class="fas fa-upload me-1"></i>Upload Materials
                    </button>
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
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

<script>
let materialDistributionChart;
let stockLevelChart;
let isTableExpanded = false;

$(document).ready(function() {
    // Load initial data
    loadMaterialData();
    loadDashboardStats();
    updateLastUpdatedTime();
    initializeCharts();
    loadMaterialOptions();
    
    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    $('#issueDate, #useDate').val(today);

    // Initialize Select2
    $('#issueMaterial, #useMaterial').select2({
        placeholder: 'Select Material',
        allowClear: true,
        dropdownParent: $('#issueMaterialModal, #useMaterialModal')
    });

    // Form submissions
    $('#addMaterialForm').on('submit', function(e) {
        e.preventDefault();
        submitNewMaterial(this);
    });

    $('#issueMaterialForm').on('submit', function(e) {
        e.preventDefault();
        submitMaterialIssue(this);
    });

    $('#useMaterialForm').on('submit', function(e) {
        e.preventDefault();
        submitMaterialUsage(this);
    });

    $('#bulkUploadForm').on('submit', function(e) {
        e.preventDefault();
        submitBulkUpload(this);
    });

    // Material selection handlers
    $('#issueMaterial').on('change', function() {
        if ($(this).val()) {
            loadCurrentStock($(this).val());
        } else {
            $('#currentStockInfo').hide();
        }
    });

    $('#useMaterial, #useTeam').on('change', function() {
        if ($('#useMaterial').val() && $('#useTeam').val()) {
            loadTeamBalance($('#useMaterial').val(), $('#useTeam').val());
        } else {
            $('#teamBalanceInfo').hide();
        }
    });

    // Filter handlers
    $('#categoryFilter, #teamFilter, #stockFilter').on('change', function() {
        applyFilters();
    });

    $('#materialSearch').on('keyup', debounce(function() {
        applyFilters();
    }, 500));
});

// Data loading functions
function loadMaterialData() {
    const filters = getActiveFilters();
    
    $.ajax({
        url: '/company/MasterTracker/material-balance/data',
        method: 'GET',
        data: filters,
        success: function(response) {
            if (response.success) {
                populateMaterialTable(response.data);
                updateCharts(response.data);
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load material data.'
            });
        }
    });
}

function populateMaterialTable(data) {
    let tableBody = '';
    
    data.forEach((material, index) => {
        tableBody += `
            <tr class="category-${material.category}">
                <td class="material-name-cell fixed-left">${material.material_name}</td>
                <td class="description-cell fixed-left-2">${material.description || ''}</td>
                <!-- Linfra -->
                <td>${formatNumber(material.linfra_issued)}</td>
                <td>${formatNumber(material.linfra_used)}</td>
                <td class="${getStockClass(material.linfra_balance)}">${formatNumber(material.linfra_balance)}</td>
                <!-- GESL -->
                <td>${formatNumber(material.gesl_issued)}</td>
                <td>${formatNumber(material.gesl_used)}</td>
                <td class="${getStockClass(material.gesl_balance)}">${formatNumber(material.gesl_balance)}</td>
                <!-- Team 1 -->
                <td>${formatNumber(material.team1_issued)}</td>
                <td>${formatNumber(material.team1_used)}</td>
                <td class="${getStockClass(material.team1_balance)}">${formatNumber(material.team1_balance)}</td>
                <!-- Team 2 -->
                <td>${formatNumber(material.team2_issued)}</td>
                <td>${formatNumber(material.team2_used)}</td>
                <td class="${getStockClass(material.team2_balance)}">${formatNumber(material.team2_balance)}</td>
                <!-- Team 3 -->
                <td>${formatNumber(material.team3_issued)}</td>
                <td>${formatNumber(material.team3_used)}</td>
                <td class="${getStockClass(material.team3_balance)}">${formatNumber(material.team3_balance)}</td>
                <!-- Team 4 -->
                <td>${formatNumber(material.team4_issued)}</td>
                <td>${formatNumber(material.team4_used)}</td>
                <td class="${getStockClass(material.team4_balance)}">${formatNumber(material.team4_balance)}</td>
                <!-- Team 5 -->
                <td>${formatNumber(material.team5_issued)}</td>
                <td>${formatNumber(material.team5_used)}</td>
                <td class="${getStockClass(material.team5_balance)}">${formatNumber(material.team5_balance)}</td>
                <!-- Team 6 -->
                <td>${formatNumber(material.team6_issued)}</td>
                <td>${formatNumber(material.team6_used)}</td>
                <td class="${getStockClass(material.team6_balance)}">${formatNumber(material.team6_balance)}</td>
                <!-- Team 7 -->
                <td>${formatNumber(material.team7_issued)}</td>
                <td>${formatNumber(material.team7_used)}</td>
                <td class="${getStockClass(material.team7_balance)}">${formatNumber(material.team7_balance)}</td>
                <!-- Team 8 -->
                <td>${formatNumber(material.team8_issued)}</td>
                <td>${formatNumber(material.team8_used)}</td>
                <td class="${getStockClass(material.team8_balance)}">${formatNumber(material.team8_balance)}</td>
                <!-- Team 9 -->
                <td>${formatNumber(material.team9_issued)}</td>
                <td>${formatNumber(material.team9_used)}</td>
                <td class="${getStockClass(material.team9_balance)}">${formatNumber(material.team9_balance)}</td>
                <!-- Team 10 -->
                <td>${formatNumber(material.team10_issued)}</td>
                <td>${formatNumber(material.team10_used)}</td>
                <td class="${getStockClass(material.team10_balance)}">${formatNumber(material.team10_balance)}</td>
            </tr>
        `;
    });
    
    $('#materialTableBody').html(tableBody);
    updateLastUpdatedTime();
}

function loadDashboardStats() {
    const filters = getActiveFilters();
    
    $.ajax({
        url: '/company/MasterTracker/material-balance/stats',
        method: 'GET',
        data: filters,
        success: function(response) {
            if (response.success) {
                const stats = response.data;
                $('#totalMaterials').text(formatNumber(stats.total_materials));
                $('#totalIssued').text(formatNumber(stats.total_issued));
                $('#totalUsed').text(formatNumber(stats.total_used));
                $('#totalBalance').text(formatNumber(stats.total_balance));
            }
        }
    });
}

function loadMaterialOptions() {
    $.ajax({
        url: '/company/MasterTracker/material-balance/materials',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">Select Material</option>';
                response.data.forEach(material => {
                    options += `<option value="${material.id}">${material.material_name} - ${material.description || ''}</option>`;
                });
                $('#issueMaterial, #useMaterial').html(options);
            }
        }
    });
}

function loadCurrentStock(materialId) {
    $.ajax({
        url: `/company/MasterTracker/material-balance/materials/${materialId}/stock`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#availableStock').text(formatNumber(response.data.available_stock));
                $('#currentStockInfo').show();
            }
        }
    });
}

function loadTeamBalance(materialId, team) {
    $.ajax({
        url: `/company/MasterTracker/material-balance/materials/${materialId}/team-balance`,
        method: 'GET',
        data: { team: team },
        success: function(response) {
            if (response.success) {
                $('#teamBalance').text(formatNumber(response.data.team_balance));
                $('#teamBalanceInfo').show();
            }
        }
    });
}

// Chart functions
function initializeCharts() {
    const ctx1 = document.getElementById('materialDistributionChart').getContext('2d');
    const ctx2 = document.getElementById('stockLevelChart').getContext('2d');

    materialDistributionChart = new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Linfra', 'GESL', 'Teams 1-5', 'Teams 6-10'],
            datasets: [{
                data: [0, 0, 0, 0],
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#17a2b8'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    stockLevelChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Good Stock', 'Warning', 'Critical', 'Empty'],
            datasets: [{
                label: 'Materials Count',
                data: [0, 0, 0, 0],
                backgroundColor: ['#28a745', '#ffc107', '#fd7e14', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function updateCharts(data) {
    // Update distribution chart with sample data
    const distributionData = [150, 120, 200, 180];
    materialDistributionChart.data.datasets[0].data = distributionData;
    materialDistributionChart.update();

    // Update stock level chart with sample data
    const stockData = [45, 25, 15, 8];
    stockLevelChart.data.datasets[0].data = stockData;
    stockLevelChart.update();
}

// Form submission functions
function submitNewMaterial(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/material-balance/materials',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Material added successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#addMaterialModal').modal('hide');
                form.reset();
                loadMaterialData();
                loadDashboardStats();
                loadMaterialOptions();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function submitMaterialIssue(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/material-balance/materials/issue',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Material issued successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#issueMaterialModal').modal('hide');
                form.reset();
                $('#currentStockInfo').hide();
                loadMaterialData();
                loadDashboardStats();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function submitMaterialUsage(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/material-balance/materials/use',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Material usage recorded successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#useMaterialModal').modal('hide');
                form.reset();
                $('#teamBalanceInfo').hide();
                loadMaterialData();
                loadDashboardStats();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function submitBulkUpload(form) {
    let formData = new FormData(form);
    
    // Show progress
    $('#uploadProgress').show();
    $('#uploadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');
    
    $.ajax({
        url: '/company/MasterTracker/material-balance/materials/bulk-upload',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total * 100;
                    $('.progress-bar').css('width', percentComplete + '%');
                }
            }, false);
            return xhr;
        },
        success: function(response) {
            if (response.success) {
                let message = `Successfully processed ${response.data.processed} material records`;
                if (response.data.errors && response.data.errors.length > 0) {
                    message += ` (${response.data.errors.length} errors skipped)`;
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'Upload Complete!',
                    html: `<p>${message}</p>`,
                    showConfirmButton: true
                });
                
                $('#bulkUploadModal').modal('hide');
                form.reset();
                clearFileInput();
                loadMaterialData();
                loadDashboardStats();
                loadMaterialOptions();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        },
        complete: function() {
            $('#uploadProgress').hide();
            $('.progress-bar').css('width', '0%');
            $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload me-1"></i>Upload Materials');
        }
    });
}

// Utility functions
function getStockClass(balance) {
    const stock = parseInt(balance) || 0;
    if (stock === 0) return 'stock-empty';
    if (stock <= 10) return 'stock-critical';
    if (stock <= 50) return 'stock-warning';
    return 'stock-good';
}

function formatNumber(value) {
    if (!value || value === '#REF!') return '0';
    return parseInt(value).toLocaleString();
}

function getActiveFilters() {
    return {
        category: $('#categoryFilter').val(),
        team: $('#teamFilter').val(),
        stock_level: $('#stockFilter').val(),
        search: $('#materialSearch').val()
    };
}

function applyFilters() {
    loadMaterialData();
    loadDashboardStats();
}

function resetFilters() {
    $('#categoryFilter').val('all');
    $('#teamFilter').val('all');
    $('#stockFilter').val('all');
    $('#materialSearch').val('');
    applyFilters();
}

function toggleTableView() {
    isTableExpanded = !isTableExpanded;
    if (isTableExpanded) {
        $('.material-table').addClass('table-expanded');
        $(document.body).css('overflow-x', 'auto');
    } else {
        $('.material-table').removeClass('table-expanded');
        $(document.body).css('overflow-x', 'hidden');
    }
}

function updateLastUpdatedTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour12: true, 
        hour: '2-digit', 
        minute: '2-digit' 
    });
    $('#lastUpdated, #tableLastUpdated').text(timeString);
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

function clearFileInput() {
    $('#bulkFile').val('');
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Global functions
window.refreshData = function() {
    loadMaterialData();
    loadDashboardStats();
    updateLastUpdatedTime();
    Swal.fire({
        icon: 'success',
        title: 'Refreshed!',
        text: 'Data updated successfully.',
        showConfirmButton: false,
        timer: 1500
    });
};

window.exportData = function(format) {
    const filters = getActiveFilters();
    const params = new URLSearchParams(filters).toString();
    window.open(`/company/MasterTracker/material-balance/export/${format}?${params}`, '_blank');
};

// Load sample data for demonstration
$(document).ready(function() {
    // Sample material data
    const sampleData = [
        {
            material_name: "ONT & Patch Cord",
            description: "Qty Received",
            category: "ont-patch-cord",
            linfra_issued: 0, linfra_used: 0, linfra_balance: 0,
            gesl_issued: 4130, gesl_used: 0, gesl_balance: 4130,
            team1_issued: 534, team1_used: 0, team1_balance: 534,
            team2_issued: 90, team2_used: 0, team2_balance: 90,
            team3_issued: 278, team3_used: 0, team3_balance: 278,
            team4_issued: 618, team4_used: 0, team4_balance: 618,
            team5_issued: 406, team5_used: 0, team5_balance: 406,
            team6_issued: 172, team6_used: 0, team6_balance: 172,
            team7_issued: 491, team7_used: 0, team7_balance: 491,
            team8_issued: 227, team8_used: 0, team8_balance: 227,
            team9_issued: 0, team9_used: 0, team9_balance: 0,
            team10_issued: 382, team10_used: 0, team10_balance: 382
        },
        {
            material_name: "ATB",
            description: "",
            category: "connector",
            linfra_issued: 0, linfra_used: 0, linfra_balance: 0,
            gesl_issued: 0, gesl_used: 0, gesl_balance: 0,
            team1_issued: 534, team1_used: 534, team1_balance: 0,
            team2_issued: 0, team2_used: 0, team2_balance: 0,
            team3_issued: 278, team3_used: 278, team3_balance: 0,
            team4_issued: 618, team4_used: 618, team4_balance: 0,
            team5_issued: 406, team5_used: 406, team5_balance: 0,
            team6_issued: 172, team6_used: 172, team6_balance: 0,
            team7_issued: 491, team7_used: 491, team7_balance: 0,
            team8_issued: 227, team8_used: 227, team8_balance: 0,
            team9_issued: 0, team9_used: 0, team9_balance: 0,
            team10_issued: 382, team10_used: 382, team10_balance: 0
        }
    ];
    
    populateMaterialTable(sampleData);
    updateCharts(sampleData);
    
    // Update stats
    $('#totalMaterials').text('87');
    $('#totalIssued').text('15,423');
    $('#totalUsed').text('12,891');
    $('#totalBalance').text('2,532');
});
</script>
@endpush
