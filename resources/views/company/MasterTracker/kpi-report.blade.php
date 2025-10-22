@extends('layouts.vertical', ['page_title' => 'KPI Report', 'mode' => session('theme_mode', 'light')])

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

<!-- Chart.js for visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Date picker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    .kpi-container {
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
    .card-gesl { border-left-color: #007bff; }
    .card-linfra { border-left-color: #28a745; }
    .card-overall { border-left-color: #ffc107; }
    .card-targets { border-left-color: #17a2b8; }

    /* KPI Table Styles */
    .kpi-table {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .kpi-table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        text-align: center;
    }

    .kpi-table table {
        margin-bottom: 0;
    }

    .kpi-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        text-align: center;
        border: 1px solid #dee2e6;
        padding: 12px 8px;
        font-size: 12px;
        vertical-align: middle;
    }

    .kpi-table td {
        text-align: center;
        border: 1px solid #dee2e6;
        padding: 10px 8px;
        font-size: 12px;
        vertical-align: middle;
    }

    .kpi-task-cell {
        text-align: left !important;
        font-weight: 500;
        background: #f8f9fa;
        max-width: 200px;
    }

    .kpi-description-cell {
        text-align: left !important;
        font-size: 11px;
        color: #6c757d;
        max-width: 250px;
    }

    .performance-good {
        background-color: #d4edda !important;
        color: #155724;
        font-weight: 600;
    }

    .performance-warning {
        background-color: #fff3cd !important;
        color: #856404;
        font-weight: 600;
    }

    .performance-poor {
        background-color: #f8d7da !important;
        color: #721c24;
        font-weight: 600;
    }

    .company-header-gesl {
        background: #007bff !important;
        color: white;
    }

    .company-header-linfra {
        background: #28a745 !important;
        color: white;
    }

    /* Filter Panel */
    .filter-panel {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    .filter-section {
        margin-bottom: 15px;
    }

    .filter-section:last-child {
        margin-bottom: 0;
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

    /* Export buttons */
    .export-dropdown .dropdown-item {
        font-size: 14px;
        padding: 8px 16px;
    }

    .export-dropdown .dropdown-item i {
        width: 20px;
        margin-right: 8px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .kpi-table th,
        .kpi-table td {
            font-size: 10px;
            padding: 6px 4px;
        }

        .kpi-task-cell,
        .kpi-description-cell {
            font-size: 10px;
        }

        .card-value {
            font-size: 24px;
        }

        .chart-container {
            height: 300px;
        }
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

    /* Remark column styling */
    .remark-cell {
        text-align: left !important;
        font-size: 11px;
        color: #6c757d;
        max-width: 300px;
        line-height: 1.3;
    }

    .remark-highlight {
        background-color: #fff3cd;
        padding: 2px 4px;
        border-radius: 3px;
        color: #856404;
    }
</style>
@endsection

@section('content')
<div class="kpi-container">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Tracker</a></li>
                        <li class="breadcrumb-item active">KPI Report</li>
                    </ol>
                </div>
                <h4 class="page-title">KPI Performance Report</h4>
                <p class="text-muted mb-0">Monitor Key Performance Indicators across companies</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-gesl">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                        <i class="fas fa-building"></i>
                    </div>
                    <h6 class="card-title">GESL Performance</h6>
                    <h3 class="card-value" id="geslPerformance">0%</h3>
                    <div class="card-change">
                        <i class="fas fa-chart-line me-1"></i> Average completion rate
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-linfra">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h6 class="card-title">LINFRA Performance</h6>
                    <h3 class="card-value" id="linfraPerformance">0%</h3>
                    <div class="card-change">
                        <i class="fas fa-chart-line me-1"></i> Average completion rate
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-overall">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h6 class="card-title">Overall Performance</h6>
                    <h3 class="card-value" id="overallPerformance">0%</h3>
                    <div class="card-change">
                        <i class="fas fa-chart-pie me-1"></i> Combined average
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-targets">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                        <i class="fas fa-target"></i>
                    </div>
                    <h6 class="card-title">Targets Met</h6>
                    <h3 class="card-value" id="targetsMet">0</h3>
                    <div class="card-change">
                        <i class="fas fa-bullseye me-1"></i> Out of total KPIs
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKpiModal">
                        <i class="fas fa-plus me-2"></i>Add KPI
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                        <i class="fas fa-upload me-2"></i>Bulk Upload
                    </button>
                    <div class="btn-group export-dropdown">
                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="exportData('excel')">
                                <i class="fas fa-file-excel text-success"></i>Excel Report
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportData('pdf')">
                                <i class="fas fa-file-pdf text-danger"></i>PDF Report
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportData('csv')">
                                <i class="fas fa-file-csv text-info"></i>CSV Data
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="printReport()">
                                <i class="fas fa-print text-dark"></i>Print Report
                            </a></li>
                        </ul>
                    </div>
                    <button type="button" class="btn btn-warning" onclick="refreshData()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>More Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">
                                <i class="fas fa-cog me-2"></i>Report Settings
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="toggleCharts()">
                                <i class="fas fa-chart-bar me-2"></i>Toggle Charts
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
                <label for="periodFilter" class="form-label">Period</label>
                <select class="form-select form-select-sm" id="periodFilter">
                    <option value="current">Current Period</option>
                    <option value="weekly">This Week</option>
                    <option value="monthly">This Month</option>
                    <option value="quarterly">This Quarter</option>
                    <option value="yearly">This Year</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="companyFilter" class="form-label">Company</label>
                <select class="form-select form-select-sm" id="companyFilter">
                    <option value="all">All Companies</option>
                    <option value="gesl">GESL</option>
                    <option value="linfra">LINFRA GH LTD</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="categoryFilter" class="form-label">Category</label>
                <select class="form-select form-select-sm" id="categoryFilter">
                    <option value="all">All Categories</option>
                    <option value="connection">Connection</option>
                    <option value="installation">Installation</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="customer-service">Customer Service</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="statusFilter" class="form-label">Performance</label>
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="all">All Performance</option>
                    <option value="good">Above 90%</option>
                    <option value="warning">70% - 90%</option>
                    <option value="poor">Below 70%</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="dateFrom" class="form-label">From Date</label>
                <input type="date" class="form-control form-control-sm" id="dateFrom">
            </div>
            <div class="col-md-2">
                <label for="dateTo" class="form-label">To Date</label>
                <input type="date" class="form-control form-control-sm" id="dateTo">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="applyFilters()">
                        <i class="fas fa-filter me-1"></i>Apply Filters
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                        <i class="fas fa-times me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="saveFilterPreset()">
                        <i class="fas fa-save me-1"></i>Save Preset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Performance Table -->
    <div class="kpi-table">
        <div class="kpi-table-header">
            <h5 class="mb-0">
                <i class="fas fa-chart-line me-2"></i>KPI Performance Report
                <span class="float-end" id="reportPeriod">Current Period</span>
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-sm mb-0" id="kpiTable">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 40px;">SN</th>
                        <th rowspan="2" style="width: 200px;">Task</th>
                        <th rowspan="2" style="width: 250px;">KPI Description</th>
                        <th colspan="3" class="company-header-gesl">GESL</th>
                        <th colspan="3" class="company-header-linfra">LINFRA GH LTD</th>
                        <th rowspan="2" style="width: 300px;">Remark</th>
                    </tr>
                    <tr>
                        <th style="width: 80px;">Target</th>
                        <th style="width: 80px;">Performance</th>
                        <th style="width: 60px;">%</th>
                        <th style="width: 80px;">Target</th>
                        <th style="width: 80px;">Performance</th>
                        <th style="width: 60px;">%</th>
                    </tr>
                </thead>
                <tbody id="kpiTableBody">
                    <!-- Data will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row" id="chartsSection">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">Performance Comparison by Company</div>
                <canvas id="companyComparisonChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">KPI Performance Trends</div>
                <canvas id="performanceTrendChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Add KPI Modal -->
<div class="modal fade" id="addKpiModal" tabindex="-1" aria-labelledby="addKpiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKpiModalLabel">Add New KPI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addKpiForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="task_name" id="taskName" required placeholder=" ">
                                <label for="taskName" class="required-field">Task Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="category" id="kpiCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="connection">Connection Performance</option>
                                    <option value="installation">Installation</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="customer-service">Customer Service</option>
                                    <option value="quality">Quality Assurance</option>
                                </select>
                                <label for="kpiCategory" class="required-field">Category</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="kpi_description" id="kpiDescription" required placeholder=" " style="height: 80px"></textarea>
                                <label for="kpiDescription" class="required-field">KPI Description</label>
                            </div>
                        </div>
                        
                        <!-- GESL Targets -->
                        <div class="col-12">
                            <h6 class="mb-3 text-primary"><i class="fas fa-building me-2"></i>GESL Targets</h6>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="gesl_target" id="geslTarget" placeholder=" ">
                                <label for="geslTarget">GESL Target</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="gesl_performance" id="geslPerformanceInput" placeholder=" ">
                                <label for="geslPerformanceInput">GESL Performance</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="gesl_percentage" id="geslPercentage" step="0.1" placeholder=" " readonly>
                                <label for="geslPercentage">GESL Percentage (%)</label>
                            </div>
                        </div>
                        
                        <!-- LINFRA Targets -->
                        <div class="col-12">
                            <h6 class="mb-3 text-success"><i class="fas fa-industry me-2"></i>LINFRA GH LTD Targets</h6>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="linfra_target" id="linfraTarget" placeholder=" ">
                                <label for="linfraTarget">LINFRA Target</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="linfra_performance" id="linfraPerformanceInput" placeholder=" ">
                                <label for="linfraPerformanceInput">LINFRA Performance</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="linfra_percentage" id="linfraPercentage" step="0.1" placeholder=" " readonly>
                                <label for="linfraPercentage">LINFRA Percentage (%)</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="report_date" id="reportDate" required>
                                <label for="reportDate" class="required-field">Report Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="kpiStatus">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending Review</option>
                                </select>
                                <label for="kpiStatus">Status</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="remark" id="kpiRemark" placeholder=" " style="height: 100px"></textarea>
                                <label for="kpiRemark">Remark</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add KPI</button>
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
                <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload KPI Data</h5>
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
                                    <li>Download the template file and fill in your KPI data</li>
                                    <li>Supported formats: Excel (.xlsx), CSV (.csv)</li>
                                    <li>Maximum file size: 10MB</li>
                                    <li>Existing KPIs with same task name will be updated</li>
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
                                        <a href="/company/MasterTracker/kpi-report/template/excel" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-file-excel me-1"></i>Excel Template
                                        </a>
                                        <a href="/company/MasterTracker/kpi-report/template/csv" class="btn btn-outline-info btn-sm">
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
                                <input type="date" class="form-control" name="default_report_date" id="defaultReportDate">
                                <label for="defaultReportDate">Default Report Date</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="default_status" id="defaultStatus">
                                    <option value="">Use File Data</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending Review</option>
                                </select>
                                <label for="defaultStatus">Default Status</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="updateExisting" name="update_existing" checked>
                                <label class="form-check-label" for="updateExisting">
                                    Update existing KPIs if task name matches
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
                        <i class="fas fa-upload me-1"></i>Upload KPI Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Report Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="settingsModalLabel">Report Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="showCharts" checked>
                            <label class="form-check-label" for="showCharts">
                                Show Performance Charts
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="autoRefresh" checked>
                            <label class="form-check-label" for="autoRefresh">
                                Auto-refresh data (5 minutes)
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="colorCoding" checked>
                            <label class="form-check-label" for="colorCoding">
                                Performance color coding
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="refreshInterval" class="form-label">Refresh Interval (minutes)</label>
                        <select class="form-select" id="refreshInterval">
                            <option value="1">1 minute</option>
                            <option value="5" selected>5 minutes</option>
                            <option value="10">10 minutes</option>
                            <option value="30">30 minutes</option>
                            <option value="0">Manual only</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveSettings()">Save Settings</button>
            </div>
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
let companyComparisonChart;
let performanceTrendChart;
let autoRefreshInterval;

$(document).ready(function() {
    // Load initial data
    loadKpiData();
    loadDashboardStats();
    updateLastUpdatedTime();
    initializeCharts();
    
    // Set default dates
    const today = new Date();
    const lastWeek = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
    $('#dateFrom').val(lastWeek.toISOString().split('T')[0]);
    $('#dateTo').val(today.toISOString().split('T')[0]);
    $('#reportDate').val(today.toISOString().split('T')[0]);
    $('#defaultReportDate').val(today.toISOString().split('T')[0]);

    // Form submissions
    $('#addKpiForm').on('submit', function(e) {
        e.preventDefault();
        submitNewKpi(this);
    });

    $('#bulkUploadForm').on('submit', function(e) {
        e.preventDefault();
        submitBulkUpload(this);
    });

    // Auto-calculate percentages
    $('#geslTarget, #geslPerformanceInput').on('input', function() {
        calculatePercentage('gesl');
    });

    $('#linfraTarget, #linfraPerformanceInput').on('input', function() {
        calculatePercentage('linfra');
    });

    // Filter change handlers
    $('#periodFilter, #companyFilter, #categoryFilter, #statusFilter').on('change', function() {
        if (this.value !== 'custom' || this.id !== 'periodFilter') {
            applyFilters();
        }
    });

    // Custom date range handler
    $('#periodFilter').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#dateFrom, #dateTo').prop('disabled', false).closest('.col-md-2').show();
        } else {
            $('#dateFrom, #dateTo').prop('disabled', true);
            setPresetDateRange($(this).val());
        }
    });

    // Auto-refresh setup
    startAutoRefresh();
});

// Data loading functions
function loadKpiData() {
    const filters = getActiveFilters();
    
    $.ajax({
        url: '/company/MasterTracker/kpi-report/data',
        method: 'GET',
        data: filters,
        success: function(response) {
            if (response.success) {
                populateKpiTable(response.data);
                updateCharts(response.data);
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load KPI data.'
            });
        }
    });
}

function populateKpiTable(data) {
    let tableBody = '';
    
    data.forEach((kpi, index) => {
        const geslPerformanceClass = getPerformanceClass(kpi.gesl_percentage);
        const linfraPerformanceClass = getPerformanceClass(kpi.linfra_percentage);
        
        tableBody += `
            <tr>
                <td>${index + 1}</td>
                <td class="kpi-task-cell">${kpi.task_name}</td>
                <td class="kpi-description-cell">${kpi.kpi_description}</td>
                <td>${formatNumber(kpi.gesl_target)}</td>
                <td>${formatNumber(kpi.gesl_performance)}</td>
                <td class="${geslPerformanceClass}">${kpi.gesl_percentage}%</td>
                <td>${formatNumber(kpi.linfra_target)}</td>
                <td>${formatNumber(kpi.linfra_performance)}</td>
                <td class="${linfraPerformanceClass}">${kpi.linfra_percentage}%</td>
                <td class="remark-cell">${kpi.remark || ''}</td>
            </tr>
        `;
    });
    
    $('#kpiTableBody').html(tableBody);
}

function loadDashboardStats() {
    const filters = getActiveFilters();
    
    $.ajax({
        url: '/company/MasterTracker/kpi-report/stats',
        method: 'GET',
        data: filters,
        success: function(response) {
            if (response.success) {
                const stats = response.data;
                $('#geslPerformance').text(stats.gesl_avg + '%');
                $('#linfraPerformance').text(stats.linfra_avg + '%');
                $('#overallPerformance').text(stats.overall_avg + '%');
                $('#targetsMet').text(stats.targets_met + '/' + stats.total_kpis);
            }
        }
    });
}

// Chart functions
function initializeCharts() {
    const ctx1 = document.getElementById('companyComparisonChart').getContext('2d');
    const ctx2 = document.getElementById('performanceTrendChart').getContext('2d');

    companyComparisonChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'GESL',
                data: [],
                backgroundColor: '#007bff',
                borderColor: '#007bff',
                borderWidth: 1
            }, {
                label: 'LINFRA GH LTD',
                data: [],
                backgroundColor: '#28a745',
                borderColor: '#28a745',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    performanceTrendChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'GESL Average',
                data: [],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }, {
                label: 'LINFRA Average',
                data: [],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
}

function updateCharts(data) {
    // Update comparison chart
    const taskNames = data.map(kpi => kpi.task_name.substring(0, 15) + '...');
    const geslData = data.map(kpi => parseFloat(kpi.gesl_percentage));
    const linfraData = data.map(kpi => parseFloat(kpi.linfra_percentage));

    companyComparisonChart.data.labels = taskNames;
    companyComparisonChart.data.datasets[0].data = geslData;
    companyComparisonChart.data.datasets[1].data = linfraData;
    companyComparisonChart.update();

    // Update trend chart with sample trend data
    const trendLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
    const geslTrend = [85, 87, 89, 91];
    const linfraTrend = [82, 85, 88, 90];

    performanceTrendChart.data.labels = trendLabels;
    performanceTrendChart.data.datasets[0].data = geslTrend;
    performanceTrendChart.data.datasets[1].data = linfraTrend;
    performanceTrendChart.update();
}

// Form functions
function submitNewKpi(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/kpi-report/kpis',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'KPI added successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#addKpiModal').modal('hide');
                form.reset();
                loadKpiData();
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
        url: '/company/MasterTracker/kpi-report/kpis/bulk-upload',
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
                let message = `Successfully processed ${response.data.processed} KPI records`;
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
                loadKpiData();
                loadDashboardStats();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        },
        complete: function() {
            $('#uploadProgress').hide();
            $('.progress-bar').css('width', '0%');
            $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload me-1"></i>Upload KPI Data');
        }
    });
}

// Utility functions
function calculatePercentage(company) {
    const target = parseFloat($(`#${company}Target`).val()) || 0;
    const performance = parseFloat($(`#${company}PerformanceInput`).val()) || 0;
    
    if (target > 0) {
        const percentage = (performance / target * 100).toFixed(1);
        $(`#${company}Percentage`).val(percentage);
    } else {
        $(`#${company}Percentage`).val('');
    }
}

function getPerformanceClass(percentage) {
    const perf = parseFloat(percentage);
    if (perf >= 90) return 'performance-good';
    if (perf >= 70) return 'performance-warning';
    return 'performance-poor';
}

function formatNumber(value) {
    if (!value) return '0';
    return parseFloat(value).toLocaleString();
}

function getActiveFilters() {
    return {
        period: $('#periodFilter').val(),
        company: $('#companyFilter').val(),
        category: $('#categoryFilter').val(),
        status: $('#statusFilter').val(),
        date_from: $('#dateFrom').val(),
        date_to: $('#dateTo').val()
    };
}

function applyFilters() {
    loadKpiData();
    loadDashboardStats();
    updateLastUpdatedTime();
}

function resetFilters() {
    $('#periodFilter').val('current');
    $('#companyFilter').val('all');
    $('#categoryFilter').val('all');
    $('#statusFilter').val('all');
    $('#dateFrom, #dateTo').val('');
    applyFilters();
}

function setPresetDateRange(period) {
    const today = new Date();
    let fromDate, toDate;
    
    switch(period) {
        case 'weekly':
            fromDate = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
            toDate = today;
            break;
        case 'monthly':
            fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
            toDate = today;
            break;
        case 'quarterly':
            fromDate = new Date(today.getFullYear(), Math.floor(today.getMonth() / 3) * 3, 1);
            toDate = today;
            break;
        case 'yearly':
            fromDate = new Date(today.getFullYear(), 0, 1);
            toDate = today;
            break;
        default:
            fromDate = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
            toDate = today;
    }
    
    $('#dateFrom').val(fromDate.toISOString().split('T')[0]);
    $('#dateTo').val(toDate.toISOString().split('T')[0]);
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

function startAutoRefresh() {
    const interval = parseInt($('#refreshInterval').val()) || 5;
    if (interval > 0) {
        autoRefreshInterval = setInterval(function() {
            loadKpiData();
            loadDashboardStats();
            updateLastUpdatedTime();
        }, interval * 60000);
    }
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

// Global functions
window.refreshData = function() {
    loadKpiData();
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
    window.open(`/company/MasterTracker/kpi-report/export/${format}?${params}`, '_blank');
};

window.printReport = function() {
    window.print();
};

window.toggleCharts = function() {
    $('#chartsSection').toggle();
    $('#showCharts').prop('checked', $('#chartsSection').is(':visible'));
};

window.saveFilterPreset = function() {
    Swal.fire({
        title: 'Save Filter Preset',
        input: 'text',
        inputLabel: 'Preset Name',
        inputPlaceholder: 'Enter preset name...',
        showCancelButton: true,
        confirmButtonText: 'Save'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Save preset logic here
            Swal.fire('Saved!', 'Filter preset saved successfully.', 'success');
        }
    });
};

window.saveSettings = function() {
    const settings = {
        showCharts: $('#showCharts').prop('checked'),
        autoRefresh: $('#autoRefresh').prop('checked'),
        colorCoding: $('#colorCoding').prop('checked'),
        refreshInterval: $('#refreshInterval').val()
    };
    
    // Clear existing interval
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
    
    // Restart auto-refresh with new settings
    if (settings.autoRefresh) {
        startAutoRefresh();
    }
    
    // Toggle charts visibility
    if (!settings.showCharts) {
        $('#chartsSection').hide();
    } else {
        $('#chartsSection').show();
    }
    
    $('#settingsModal').modal('hide');
    Swal.fire({
        icon: 'success',
        title: 'Settings Saved!',
        text: 'Your preferences have been updated.',
        showConfirmButton: false,
        timer: 1500
    });
};

// Load sample data for demonstration
$(document).ready(function() {
    // Sample KPI data
    const sampleData = [
        {
            task_name: "Connection Performance Rate (YTD)",
            kpi_description: "Total No. of Connections Completed (YTD)/ Total No. of Connections Received (YTD)",
            gesl_target: 3214,
            gesl_performance: 3053,
            gesl_percentage: "95.0",
            linfra_target: 3279,
            linfra_performance: 3242,
            linfra_percentage: "98.9",
            remark: "Target of 95% in YTD performance. Decrease in performance from 96% due to removal of sites received in 2024 but completed in 2025"
        },
        {
            task_name: "Backlog Connection",
            kpi_description: "Sites received from previous weeks",
            gesl_target: 100,
            gesl_performance: 60,
            gesl_percentage: "60.0",
            linfra_target: 0,
            linfra_performance: 0,
            linfra_percentage: "0.0",
            remark: "Sites to be closed. New sites received on Saturday and Sunday"
        },
        {
            task_name: "New Additional (New Request this week)",
            kpi_description: "New sites received in the week (Sun - Sat)",
            gesl_target: 58,
            gesl_performance: 17,
            gesl_percentage: "29.3",
            linfra_target: 0,
            linfra_performance: 0,
            linfra_percentage: "0.0",
            remark: "Pending sites rescheduled for later days"
        }
    ];
    
    populateKpiTable(sampleData);
    updateCharts(sampleData);
    
    // Update stats
    $('#geslPerformance').text('84.3%');
    $('#linfraPerformance').text('89.6%');
    $('#overallPerformance').text('86.9%');
    $('#targetsMet').text('12/16');
});
</script>
@endpush
