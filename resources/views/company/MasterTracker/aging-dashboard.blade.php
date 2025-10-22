@extends('layouts.vertical', ['page_title' => 'Aging Dashboard', 'mode' => session('theme_mode', 'light')])

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
    .aging-container {
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
    .card-total { border-left-color: #ffc107; }
    .card-pending { border-left-color: #17a2b8; }

    /* Summary Table Styles */
    .summary-table {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .summary-table-header {
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        text-align: center;
    }

    .gesl-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }

    .linfra-header {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    }

    .summary-table table {
        margin-bottom: 0;
        font-size: 11px;
    }

    .summary-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        text-align: center;
        border: 1px solid #dee2e6;
        padding: 8px 6px;
        font-size: 10px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .summary-table td {
        text-align: center;
        border: 1px solid #dee2e6;
        padding: 6px 4px;
        font-size: 10px;
        vertical-align: middle;
    }

    /* Month column styling */
    .month-cell {
        text-align: left !important;
        font-weight: 600;
        background: #f8f9fa;
        color: #495057;
        width: 80px;
    }

    /* Status column colors */
    .col-on-hold { background-color: #fff3cd; color: #856404; }
    .col-no-access { background-color: #f8d7da; color: #721c24; }
    .col-fat-full { background-color: #d4edda; color: #155724; }
    .col-action-required { background-color: #f5c6cb; color: #721c24; }
    .col-pole-planting { background-color: #d1ecf1; color: #0c5460; }
    .col-rescheduled { background-color: #e2e3e5; color: #383d41; }

    /* Grand Total row styling */
    .grand-total-row {
        background-color: #343a40 !important;
        color: white !important;
        font-weight: 700;
    }

    .grand-total-row td {
        background-color: #343a40 !important;
        color: white !important;
        font-weight: 700;
        border-color: #495057 !important;
    }

    /* Notes Section */
    .notes-section {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    .notes-header {
        background: linear-gradient(135deg, #6f42c1 0%, #5a3a9a 100%);
        color: white;
        padding: 15px 20px;
        margin: -20px -20px 20px -20px;
        border-radius: 10px 10px 0 0;
        font-weight: 600;
        text-align: center;
    }

    .notes-item {
        background: #f8f9fa;
        border-left: 4px solid #ffc107;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 0 8px 8px 0;
    }

    .notes-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
    }

    .notes-content {
        color: #6c757d;
        font-size: 14px;
        line-height: 1.5;
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
        .summary-table th,
        .summary-table td {
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

    /* Monthly comparison */
    .monthly-comparison {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    .comparison-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 15px 20px;
        margin: -20px -20px 20px -20px;
        border-radius: 10px 10px 0 0;
        font-weight: 600;
        text-align: center;
    }

    /* Trend indicators */
    .trend-up {
        color: #28a745;
        font-weight: 600;
    }

    .trend-down {
        color: #dc3545;
        font-weight: 600;
    }

    .trend-stable {
        color: #6c757d;
        font-weight: 600;
    }

    /* Alert indicators */
    .alert-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .alert-critical { background-color: #dc3545; }
    .alert-warning { background-color: #ffc107; }
    .alert-info { background-color: #17a2b8; }
    .alert-success { background-color: #28a745; }

    /* Year selector */
    .year-selector {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .year-nav-btn {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .year-nav-btn:hover {
        background: #e9ecef;
        border-color: #adb5bd;
    }

    .current-year {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }
</style>
@endsection

@section('content')
<div class="aging-container">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Tracker</a></li>
                        <li class="breadcrumb-item active">Aging Dashboard</li>
                    </ol>
                </div>
                <h4 class="page-title">Aging Dashboard</h4>
                <p class="text-muted mb-0">Monitor aging tickets and pending actions across companies</p>
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
                    <h6 class="card-title">GESL Total</h6>
                    <h3 class="card-value" id="geslTotal">0</h3>
                    <div class="card-change">
                        <i class="fas fa-chart-line me-1"></i> Total aging tickets
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
                    <h6 class="card-title">LINFRA Total</h6>
                    <h3 class="card-value" id="linfraTotal">0</h3>
                    <div class="card-change">
                        <i class="fas fa-chart-bar me-1"></i> Total aging tickets
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-total">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h6 class="card-title">Combined Total</h6>
                    <h3 class="card-value" id="combinedTotal">0</h3>
                    <div class="card-change">
                        <i class="fas fa-plus me-1"></i> GESL + LINFRA
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-pending">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h6 class="card-title">Action Required</h6>
                    <h3 class="card-value" id="actionRequired">0</h3>
                    <div class="card-change">
                        <i class="fas fa-exclamation-triangle me-1"></i> Immediate attention
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAgingModal">
                        <i class="fas fa-plus me-2"></i>Add Entry
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkUpdateModal">
                        <i class="fas fa-upload me-2"></i>Bulk Update
                    </button>
                    <button type="button" class="btn btn-info" onclick="generateAgingReport()">
                        <i class="fas fa-chart-line me-2"></i>Generate Report
                    </button>
                    <button type="button" class="btn btn-warning" onclick="updateNotesAuto()">
                        <i class="fas fa-sync-alt me-2"></i>Update Notes
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
                                <i class="fas fa-file-pdf me-2"></i>PDF Summary
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
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">
                                <i class="fas fa-cog me-2"></i>Dashboard Settings
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="clearFilters()">
                                <i class="fas fa-filter me-2"></i>Clear Filters
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

    <!-- Year Selector -->
    <div class="year-selector">
        <div class="d-flex align-items-center">
            <button type="button" class="btn year-nav-btn" onclick="previousYear()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <span class="current-year mx-3" id="currentYear">2024</span>
            <button type="button" class="btn year-nav-btn" onclick="nextYear()">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="d-flex align-items-center gap-2">
            <label for="yearSelect" class="form-label mb-0">Jump to:</label>
            <select class="form-select form-select-sm" id="yearSelect" onchange="jumpToYear(this.value)" style="width: auto;">
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
            </select>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="goToCurrentYear()">
                <i class="fas fa-calendar-day me-1"></i>Current Year
            </button>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="filter-panel">
        <div class="row g-3">
            <div class="col-md-2">
                <label for="monthFilter" class="form-label">Month</label>
                <select class="form-select form-select-sm" id="monthFilter">
                    <option value="all">All Months</option>
                    <option value="Nov">November</option>
                    <option value="Dec">December</option>
                    <option value="Jan">January</option>
                    <option value="Feb">February</option>
                    <option value="Mar">March</option>
                    <option value="Apr">April</option>
                    <option value="May">May</option>
                    <option value="Jun">June</option>
                    <option value="Jul">July</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="companyFilter" class="form-label">Company</label>
                <select class="form-select form-select-sm" id="companyFilter">
                    <option value="all">All Companies</option>
                    <option value="gesl">GESL</option>
                    <option value="linfra">LINFRA</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="statusFilter" class="form-label">Status</label>
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="all">All Status</option>
                    <option value="on-hold">On Hold</option>
                    <option value="no-access">No Access</option>
                    <option value="fat-full">Fat Full</option>
                    <option value="action-required">Action Required</option>
                    <option value="pole-planting">Pole Planting</option>
                    <option value="rescheduled">Rescheduled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="dateRange" class="form-label">Date Range</label>
                <input type="text" class="form-control form-control-sm" id="dateRange" placeholder="Select date range">
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
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="refreshStats()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- GESL Summary -->
    <div class="summary-table">
        <div class="summary-table-header gesl-header">
            <h5 class="mb-0">
                <i class="fas fa-building me-2"></i>GESL SUMMARY
                <span class="float-end">
                    <small>Year: <span id="geslYear">2024</span></small>
                </span>
            </h5>
        </div>
        
        <div class="table-responsive">
            <table class="table table-sm mb-0" id="geslTable">
                <thead>
                    <tr>
                        <th class="month-cell">Month</th>
                        <th class="col-on-hold">On hold</th>
                        <th class="col-no-access">No Access</th>
                        <th class="col-fat-full">FAT Full</th>
                        <th class="col-action-required">Action Required</th>
                        <th class="col-pole-planting">Pole Planting</th>
                        <th class="col-rescheduled">Rescheduled</th>
                    </tr>
                </thead>
                <tbody id="geslTableBody">
                    <!-- Data will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- LINFRA Summary -->
    <div class="summary-table">
        <div class="summary-table-header linfra-header">
            <h5 class="mb-0">
                <i class="fas fa-industry me-2"></i>LINFRA SUMMARY
                <span class="float-end">
                    <small>Year: <span id="linfraYear">2024</span></small>
                </span>
            </h5>
        </div>
        
        <div class="table-responsive">
            <table class="table table-sm mb-0" id="linfraTable">
                <thead>
                    <tr>
                        <th class="month-cell">Month</th>
                        <th class="col-on-hold">On hold</th>
                        <th class="col-no-access">No access</th>
                        <th class="col-fat-full">fat full</th>
                        <th class="col-action-required">Action Required</th>
                        <th class="col-pole-planting">Pole Planting</th>
                        <th class="col-rescheduled">Rescheduled</th>
                    </tr>
                </thead>
                <tbody id="linfraTableBody">
                    <!-- Data will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Notes Section -->
    <div class="notes-section">
        <div class="notes-header">
            <h5 class="mb-0">
                <i class="fas fa-sticky-note me-2"></i>NOTES
                <span class="float-end">
                    <small>Action Items & Updates</small>
                </span>
            </h5>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="notes-item">
                    <div class="notes-title">
                        <i class="alert-indicator alert-critical"></i>Action Required
                    </div>
                    <div class="notes-content">
                        No/Low power in subbox - Immediate electrical infrastructure assessment needed for affected sites.
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="notes-item">
                    <div class="notes-title">
                        <i class="alert-indicator alert-warning"></i>No/low power in Fat
                    </div>
                    <div class="notes-content">
                        Fiber Access Terminal power issues - Coordinate with power utility company for restoration.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                <i class="fas fa-plus me-1"></i>Add Note
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="refreshNotes()">
                <i class="fas fa-sync-alt me-1"></i>Refresh Notes
            </button>
        </div>
    </div>

    <!-- Monthly Comparison -->
    <div class="monthly-comparison">
        <div class="comparison-header">
            <h5 class="mb-0">
                <i class="fas fa-chart-line me-2"></i>Monthly Comparison & Trends
            </h5>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <h6 class="mb-3">GESL vs LINFRA Comparison</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>GESL</th>
                                <th>LINFRA</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>On Hold</td>
                                <td id="compGeslOnHold">0</td>
                                <td id="compLinfraOnHold">0</td>
                                <td><span class="trend-stable"><i class="fas fa-minus"></i></span></td>
                            </tr>
                            <tr>
                                <td>Action Required</td>
                                <td id="compGeslAction">0</td>
                                <td id="compLinfraAction">0</td>
                                <td><span class="trend-up"><i class="fas fa-arrow-up"></i></span></td>
                            </tr>
                            <tr>
                                <td>Total Issues</td>
                                <td id="compGeslTotal">0</td>
                                <td id="compLinfraTotal">0</td>
                                <td><span class="trend-down"><i class="fas fa-arrow-down"></i></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3">Alert Summary</h6>
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>High Priority Items</h6>
                    <ul class="mb-0">
                        <li>Power infrastructure issues require immediate attention</li>
                        <li>Multiple sites pending pole planting approval</li>
                        <li>FAT connectivity restoration in progress</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row" id="chartsSection">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">Monthly Aging Trends</div>
                <canvas id="agingTrendChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">Status Distribution</div>
                <canvas id="statusDistributionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Add Aging Entry Modal -->
<div class="modal fade" id="addAgingModal" tabindex="-1" aria-labelledby="addAgingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAgingModalLabel">Add Aging Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addAgingForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="company" id="agingCompany" required>
                                    <option value="">Select Company</option>
                                    <option value="gesl">GESL</option>
                                    <option value="linfra">LINFRA</option>
                                </select>
                                <label for="agingCompany" class="required-field">Company</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="month" id="agingMonth" required>
                                    <option value="">Select Month</option>
                                    <option value="Nov">November</option>
                                    <option value="Dec">December</option>
                                    <option value="Jan">January</option>
                                    <option value="Feb">February</option>
                                    <option value="Mar">March</option>
                                    <option value="Apr">April</option>
                                    <option value="May">May</option>
                                    <option value="Jun">June</option>
                                    <option value="Jul">July</option>
                                </select>
                                <label for="agingMonth" class="required-field">Month</label>
                            </div>
                        </div>
                        
                        <!-- Status Counts -->
                        <div class="col-12">
                            <h6 class="mb-3 text-primary"><i class="fas fa-chart-bar me-2"></i>Status Counts</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="on_hold" id="onHoldCount" min="0" placeholder=" ">
                                <label for="onHoldCount">On Hold</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="no_access" id="noAccessCount" min="0" placeholder=" ">
                                <label for="noAccessCount">No Access</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="fat_full" id="fatFullCount" min="0" placeholder=" ">
                                <label for="fatFullCount">FAT Full</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="action_required" id="actionRequiredCount" min="0" placeholder=" ">
                                <label for="actionRequiredCount">Action Required</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="pole_planting" id="polePlantingCount" min="0" placeholder=" ">
                                <label for="polePlantingCount">Pole Planting</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="rescheduled" id="rescheduledCount" min="0" placeholder=" ">
                                <label for="rescheduledCount">Rescheduled</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="record_date" id="recordDate" required>
                                <label for="recordDate" class="required-field">Record Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="recorded_by" id="recordedBy" placeholder=" ">
                                <label for="recordedBy">Recorded By</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="agingNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="agingNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel">Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addNoteForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="note_title" id="noteTitle" required placeholder=" ">
                                <label for="noteTitle" class="required-field">Note Title</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <select class="form-select" name="priority" id="notePriority" required>
                                    <option value="">Select Priority</option>
                                    <option value="critical">Critical</option>
                                    <option value="warning">Warning</option>
                                    <option value="info">Info</option>
                                    <option value="success">Success</option>
                                </select>
                                <label for="notePriority" class="required-field">Priority</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="note_content" id="noteContent" required placeholder=" " style="height: 120px"></textarea>
                                <label for="noteContent" class="required-field">Note Content</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Note</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1" aria-labelledby="bulkUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUpdateModalLabel">Bulk Update Aging Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkUpdateForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Upload Instructions</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Download the template file and fill in your aging data</li>
                                    <li>Required columns: Company, Month, Status counts</li>
                                    <li>Supported formats: Excel (.xlsx), CSV (.csv)</li>
                                    <li>Maximum file size: 10MB</li>
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
                                        <a href="/company/MasterTracker/aging-dashboard/template/excel" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-file-excel me-1"></i>Excel Template
                                        </a>
                                        <a href="/company/MasterTracker/aging-dashboard/template/csv" class="btn btn-outline-info btn-sm">
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
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="updateExisting" name="update_existing" checked>
                                <label class="form-check-label" for="updateExisting">
                                    Update existing records if month and company match
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
                        <i class="fas fa-upload me-1"></i>Upload Data
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

<script>
let agingTrendChart;
let statusDistributionChart;
let currentYear = 2024;

$(document).ready(function() {
    // Load initial data
    loadAgingData();
    updateLastUpdatedTime();
    initializeCharts();
    
    // Initialize date picker
    flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
    });
    
    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    $('#recordDate').val(today);

    // Form submissions
    $('#addAgingForm').on('submit', function(e) {
        e.preventDefault();
        submitAgingEntry(this);
    });

    $('#addNoteForm').on('submit', function(e) {
        e.preventDefault();
        submitNote(this);
    });

    $('#bulkUpdateForm').on('submit', function(e) {
        e.preventDefault();
        submitBulkUpdate(this);
    });

    // Filter change handlers
    $('#monthFilter, #companyFilter, #statusFilter').on('change', function() {
        applyFilters();
    });
});

// Data loading functions
function loadAgingData() {
    const filters = getActiveFilters();
    
    $.ajax({
        url: '/company/MasterTracker/aging-dashboard/data',
        method: 'GET',
        data: { ...filters, year: currentYear },
        success: function(response) {
            if (response.success) {
                populateAgingTables(response.data);
                updateDashboardStats(response.data);
                updateCharts(response.data);
            }
        },
        error: function() {
            // Load sample data for demonstration
            loadSampleData();
        }
    });
}

function populateAgingTables(data) {
    populateGeslTable(data.gesl || []);
    populateLinfraTable(data.linfra || []);
}

function populateGeslTable(data) {
    let tableBody = '';
    let grandTotal = {
        on_hold: 0,
        no_access: 0,
        fat_full: 0,
        action_required: 0,
        pole_planting: 0,
        rescheduled: 0
    };
    
    data.forEach((month, index) => {
        tableBody += `
            <tr>
                <td class="month-cell">${month.month}</td>
                <td class="col-on-hold">${month.on_hold || '#REF!'}</td>
                <td class="col-no-access">${month.no_access || '#REF!'}</td>
                <td class="col-fat-full">${month.fat_full || '#REF!'}</td>
                <td class="col-action-required">${month.action_required || '#REF!'}</td>
                <td class="col-pole-planting">${month.pole_planting || '#REF!'}</td>
                <td class="col-rescheduled">${month.rescheduled || '#REF!'}</td>
            </tr>
        `;
        
        // Add to grand total
        Object.keys(grandTotal).forEach(key => {
            if (month[key] && month[key] !== '#REF!') {
                grandTotal[key] += parseInt(month[key]);
            }
        });
    });
    
    // Add grand total row
    tableBody += `
        <tr class="grand-total-row">
            <td class="month-cell">Grand Total</td>
            <td>${grandTotal.on_hold || '#REF!'}</td>
            <td>${grandTotal.no_access || '#REF!'}</td>
            <td>${grandTotal.fat_full || '#REF!'}</td>
            <td>${grandTotal.action_required || '#REF!'}</td>
            <td>${grandTotal.pole_planting || '#REF!'}</td>
            <td>${grandTotal.rescheduled || '#REF!'}</td>
        </tr>
    `;
    
    $('#geslTableBody').html(tableBody);
}

function populateLinfraTable(data) {
    let tableBody = '';
    let grandTotal = {
        on_hold: 0,
        no_access: 0,
        fat_full: 0,
        action_required: 0,
        pole_planting: 0,
        rescheduled: 0
    };
    
    data.forEach((month, index) => {
        tableBody += `
            <tr>
                <td class="month-cell">${month.month}</td>
                <td class="col-on-hold">${month.on_hold || '#REF!'}</td>
                <td class="col-no-access">${month.no_access || '#REF!'}</td>
                <td class="col-fat-full">${month.fat_full || '#REF!'}</td>
                <td class="col-action-required">${month.action_required || '#REF!'}</td>
                <td class="col-pole-planting">${month.pole_planting || '#REF!'}</td>
                <td class="col-rescheduled">${month.rescheduled || '#REF!'}</td>
            </tr>
        `;
        
        // Add to grand total
        Object.keys(grandTotal).forEach(key => {
            if (month[key] && month[key] !== '#REF!') {
                grandTotal[key] += parseInt(month[key]);
            }
        });
    });
    
    // Add grand total row
    tableBody += `
        <tr class="grand-total-row">
            <td class="month-cell">Grand Total</td>
            <td>${grandTotal.on_hold || '#REF!'}</td>
            <td>${grandTotal.no_access || '#REF!'}</td>
            <td>${grandTotal.fat_full || '#REF!'}</td>
            <td>${grandTotal.action_required || '#REF!'}</td>
            <td>${grandTotal.pole_planting || '#REF!'}</td>
            <td>${grandTotal.rescheduled || '#REF!'}</td>
        </tr>
    `;
    
    $('#linfraTableBody').html(tableBody);
}

function updateDashboardStats(data) {
    // Calculate totals from sample data
    const geslTotal = 0; // Calculate from actual data
    const linfraTotal = 0; // Calculate from actual data
    const combinedTotal = geslTotal + linfraTotal;
    const actionRequired = 0; // Calculate action required items
    
    $('#geslTotal').text(formatNumber(geslTotal));
    $('#linfraTotal').text(formatNumber(linfraTotal));
    $('#combinedTotal').text(formatNumber(combinedTotal));
    $('#actionRequired').text(formatNumber(actionRequired));
    
    // Update comparison section
    $('#compGeslOnHold').text(formatNumber(geslTotal));
    $('#compLinfraOnHold').text(formatNumber(linfraTotal));
    $('#compGeslAction').text(formatNumber(actionRequired));
    $('#compLinfraAction').text(formatNumber(actionRequired));
    $('#compGeslTotal').text(formatNumber(geslTotal));
    $('#compLinfraTotal').text(formatNumber(linfraTotal));
}

// Chart functions
function initializeCharts() {
    const ctx1 = document.getElementById('agingTrendChart').getContext('2d');
    const ctx2 = document.getElementById('statusDistributionChart').getContext('2d');

    agingTrendChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'GESL',
                data: [],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }, {
                label: 'LINFRA',
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
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    statusDistributionChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['On Hold', 'No Access', 'FAT Full', 'Action Required', 'Pole Planting', 'Rescheduled'],
            datasets: [{
                data: [0, 0, 0, 0, 0, 0],
                backgroundColor: ['#ffc107', '#dc3545', '#28a745', '#fd7e14', '#17a2b8', '#6c757d'],
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
}

function updateCharts(data) {
    // Update trend chart with sample data
    const geslTrend = [0, 0, 0, 0, 0, 0, 0, 0, 0];
    const linfraTrend = [0, 0, 0, 0, 0, 0, 0, 0, 0];

    agingTrendChart.data.datasets[0].data = geslTrend;
    agingTrendChart.data.datasets[1].data = linfraTrend;
    agingTrendChart.update();

    // Update distribution chart with sample data
    const statusData = [0, 0, 0, 0, 0, 0];
    statusDistributionChart.data.datasets[0].data = statusData;
    statusDistributionChart.update();
}

// Form submission functions
function submitAgingEntry(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/aging-dashboard/entries',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Aging entry added successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#addAgingModal').modal('hide');
                form.reset();
                loadAgingData();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function submitNote(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/aging-dashboard/notes',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Note added successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#addNoteModal').modal('hide');
                form.reset();
                refreshNotes();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function submitBulkUpdate(form) {
    let formData = new FormData(form);
    
    // Show progress
    $('#uploadProgress').show();
    $('#uploadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');
    
    $.ajax({
        url: '/company/MasterTracker/aging-dashboard/bulk-update',
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
                let message = `Successfully processed ${response.data.processed} aging records`;
                if (response.data.errors && response.data.errors.length > 0) {
                    message += ` (${response.data.errors.length} errors skipped)`;
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'Upload Complete!',
                    html: `<p>${message}</p>`,
                    showConfirmButton: true
                });
                
                $('#bulkUpdateModal').modal('hide');
                form.reset();
                clearFileInput();
                loadAgingData();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        },
        complete: function() {
            $('#uploadProgress').hide();
            $('.progress-bar').css('width', '0%');
            $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload me-1"></i>Upload Data');
        }
    });
}

// Year navigation functions
function previousYear() {
    currentYear--;
    updateYearDisplay();
    loadAgingData();
}

function nextYear() {
    currentYear++;
    updateYearDisplay();
    loadAgingData();
}

function jumpToYear(year) {
    currentYear = parseInt(year);
    updateYearDisplay();
    loadAgingData();
}

function goToCurrentYear() {
    currentYear = new Date().getFullYear();
    updateYearDisplay();
    loadAgingData();
}

function updateYearDisplay() {
    $('#currentYear').text(currentYear);
    $('#yearSelect').val(currentYear);
    $('#geslYear, #linfraYear').text(currentYear);
}

// Utility functions
function formatNumber(value) {
    if (!value || value === '#REF!') return '0';
    return parseInt(value).toLocaleString();
}

function getActiveFilters() {
    return {
        month: $('#monthFilter').val(),
        company: $('#companyFilter').val(),
        status: $('#statusFilter').val(),
        date_range: $('#dateRange').val()
    };
}

function applyFilters() {
    loadAgingData();
}

function resetFilters() {
    $('#monthFilter').val('all');
    $('#companyFilter').val('all');
    $('#statusFilter').val('all');
    $('#dateRange').val('');
    applyFilters();
}

function clearFilters() {
    resetFilters();
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

function clearFileInput() {
    $('#bulkFile').val('');
}

// Global functions
window.refreshData = function() {
    loadAgingData();
    updateLastUpdatedTime();
    Swal.fire({
        icon: 'success',
        title: 'Refreshed!',
        text: 'Data updated successfully.',
        showConfirmButton: false,
        timer: 1500
    });
};

window.refreshStats = function() {
    loadAgingData();
    updateLastUpdatedTime();
    Swal.fire({
        icon: 'success',
        title: 'Stats Refreshed!',
        text: 'Statistics updated successfully.',
        showConfirmButton: false,
        timer: 1500
    });
};

window.refreshNotes = function() {
    // Refresh notes logic here
    updateLastUpdatedTime();
    Swal.fire({
        icon: 'success',
        title: 'Notes Refreshed!',
        text: 'Notes updated successfully.',
        showConfirmButton: false,
        timer: 1500
    });
};

window.updateNotesAuto = function() {
    Swal.fire({
        title: 'Auto-Update Notes',
        text: "This will automatically update notes based on current aging data.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Auto-update notes logic here
            Swal.fire('Updated!', 'Notes have been automatically updated.', 'success');
            refreshNotes();
        }
    });
};

window.generateAgingReport = function() {
    Swal.fire({
        title: 'Generate Aging Report',
        html: `
            <div class="mb-3">
                <label class="form-label">Report Type:</label>
                <select class="form-select" id="reportType">
                    <option value="summary">Monthly Summary</option>
                    <option value="detailed">Detailed Analysis</option>
                    <option value="trend">Trend Report</option>
                    <option value="comparison">Company Comparison</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Format:</label>
                <select class="form-select" id="reportFormat">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                </select>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Generate',
        preConfirm: () => {
            const type = document.getElementById('reportType').value;
            const format = document.getElementById('reportFormat').value;
            return { type, format };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Generate report logic here
            Swal.fire('Generated!', 'Report has been generated and downloaded.', 'success');
        }
    });
};

window.exportData = function(format) {
    const filters = getActiveFilters();
    const params = new URLSearchParams({ ...filters, year: currentYear }).toString();
    window.open(`/company/MasterTracker/aging-dashboard/export/${format}?${params}`, '_blank');
};

// Load sample data for demonstration
function loadSampleData() {
    const sampleData = {
        gesl: [
            { month: 'Nov', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Dec', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Jan', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Feb', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Mar', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Apr', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'May', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Jun', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Jul', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' }
        ],
        linfra: [
            { month: 'Nov', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Dec', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Jan', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Feb', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Mar', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Apr', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'May', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' },
            { month: 'Jun', on_hold: '#REF!', no_access: '#REF!', fat_full: '#REF!', action_required: '#REF!', pole_planting: '#REF!', rescheduled: '#REF!' }
        ]
    };
    
    populateAgingTables(sampleData);
    updateDashboardStats(sampleData);
    updateCharts(sampleData);
}

// Initialize sample data on load
$(document).ready(function() {
    loadSampleData();
});
</script>
@endpush
