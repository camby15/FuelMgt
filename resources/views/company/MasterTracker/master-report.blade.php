@extends('layouts.vertical', ['page_title' => 'Master Report', 'mode' => session('theme_mode', 'light')])

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
    .master-report-container {
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
    .card-total-activities { border-left-color: #007bff; }
    .card-active-modules { border-left-color: #28a745; }
    .card-pending-actions { border-left-color: #ffc107; }
    .card-critical-issues { border-left-color: #dc3545; }

    /* Module Summary Cards */
    .module-summary {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 15px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .module-summary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
    }

    .module-header {
        padding: 15px 20px;
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .module-content {
        padding: 15px 20px;
    }

    .module-gesl { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); }
    .module-team { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
    .module-material { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); }
    .module-restock { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); }
    .module-sbc { background: linear-gradient(135deg, #6f42c1 0%, #5a3a9a 100%); }
    .module-aging { background: linear-gradient(135deg, #fd7e14 0%, #e0660e 100%); }
    .module-kpi { background: linear-gradient(135deg, #e83e8c 0%, #d91a72 100%); }
    .module-workforce { background: linear-gradient(135deg, #20c997 0%, #19a674 100%); }

    .module-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .module-stat {
        text-align: center;
        flex: 1;
    }

    .module-stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 2px;
    }

    .module-stat-label {
        font-size: 11px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Activity Feed */
    .activity-feed {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 20px;
        max-height: 600px;
        overflow-y: auto;
    }

    .activity-header {
        background: linear-gradient(135deg, #343a40 0%, #495057 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .activity-item {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: flex-start;
        transition: background-color 0.3s ease;
    }

    .activity-item:hover {
        background-color: #f8f9fa;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
        font-size: 16px;
        color: white;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .activity-description {
        color: #6c757d;
        font-size: 13px;
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .activity-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #adb5bd;
    }

    .activity-time {
        display: flex;
        align-items: center;
    }

    .activity-module {
        background: #e9ecef;
        color: #495057;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
    }

    /* Activity type colors */
    .activity-create { background: #28a745; }
    .activity-update { background: #17a2b8; }
    .activity-delete { background: #dc3545; }
    .activity-assign { background: #6f42c1; }
    .activity-complete { background: #20c997; }
    .activity-alert { background: #fd7e14; }

    /* Filter Panel */
    .filter-panel {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    /* Charts */
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

    /* Report Table */
    .report-table {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .report-table-header {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
    }

    .report-table table {
        margin-bottom: 0;
        font-size: 12px;
    }

    .report-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        text-align: center;
        border: 1px solid #dee2e6;
        padding: 10px 8px;
        font-size: 11px;
        vertical-align: middle;
    }

    .report-table td {
        text-align: center;
        border: 1px solid #dee2e6;
        padding: 8px 6px;
        font-size: 11px;
        vertical-align: middle;
    }

    /* Status indicators */
    .status-good { background-color: #d4edda; color: #155724; font-weight: 600; }
    .status-warning { background-color: #fff3cd; color: #856404; font-weight: 600; }
    .status-danger { background-color: #f8d7da; color: #721c24; font-weight: 600; }
    .status-info { background-color: #d1ecf1; color: #0c5460; font-weight: 600; }

    /* Real-time indicators */
    .realtime-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #28a745;
        border-radius: 50%;
        margin-right: 8px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }

    /* Export buttons */
    .export-btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .module-stats {
            flex-direction: column;
            gap: 10px;
        }

        .activity-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .activity-icon {
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 24px;
        }

        .chart-container {
            height: 300px;
        }
    }

    /* Tabs styling */
    .nav-tabs .nav-link {
        color: #495057;
        border-color: transparent;
        font-weight: 500;
    }

    .nav-tabs .nav-link.active {
        color: #007bff;
        border-color: #007bff #007bff #fff;
        background-color: #fff;
        font-weight: 600;
    }

    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
    }

    /* Performance indicators */
    .performance-indicator {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 8px;
    }

    .performance-label {
        font-size: 12px;
        font-weight: 500;
        color: #495057;
    }

    .performance-value {
        font-size: 14px;
        font-weight: 600;
    }

    .performance-good { color: #28a745; }
    .performance-warning { color: #ffc107; }
    .performance-danger { color: #dc3545; }
</style>
@endsection

@section('content')
<div class="master-report-container">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Tracker</a></li>
                        <li class="breadcrumb-item active">Master Report</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <span class="realtime-indicator"></span>Master Tracker Report
                </h4>
                <p class="text-muted mb-0">Comprehensive overview of all Master Tracker activities and metrics</p>
            </div>
        </div>
    </div>

    <!-- Overall Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-total-activities">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h6 class="card-title">Total Activities</h6>
                    <h3 class="card-value" id="totalActivities">1,247</h3>
                    <div class="card-change">
                        <i class="fas fa-arrow-up me-1 text-success"></i> All modules combined
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-active-modules">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <i class="fas fa-puzzle-piece"></i>
                    </div>
                    <h6 class="card-title">Active Modules</h6>
                    <h3 class="card-value" id="activeModules">8</h3>
                    <div class="card-change">
                        <i class="fas fa-check-circle me-1 text-success"></i> Operational modules
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-pending-actions">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h6 class="card-title">Pending Actions</h6>
                    <h3 class="card-value" id="pendingActions">23</h3>
                    <div class="card-change">
                        <i class="fas fa-exclamation-triangle me-1 text-warning"></i> Require attention
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-critical-issues">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #dc3545, #b02a37);">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h6 class="card-title">Critical Issues</h6>
                    <h3 class="card-value" id="criticalIssues">5</h3>
                    <div class="card-change">
                        <i class="fas fa-arrow-down me-1 text-danger"></i> Immediate action
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
                    <button type="button" class="btn btn-primary" onclick="generateMasterReport()">
                        <i class="fas fa-file-alt me-2"></i>Generate Report
                    </button>
                    <button type="button" class="btn btn-success" onclick="exportAllData()">
                        <i class="fas fa-download me-2"></i>Export All Data
                    </button>
                    <button type="button" class="btn btn-info" onclick="refreshAllModules()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh All
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#alertsModal">
                        <i class="fas fa-bell me-2"></i>View Alerts
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-chart-bar me-1"></i>Analytics
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="showPerformanceAnalytics()">
                                <i class="fas fa-chart-line me-2"></i>Performance Analytics
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="showTrendAnalysis()">
                                <i class="fas fa-chart-area me-2"></i>Trend Analysis
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="showModuleComparison()">
                                <i class="fas fa-balance-scale me-2"></i>Module Comparison
                            </a></li>
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>Settings
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="configureAlerts()">
                                <i class="fas fa-bell-slash me-2"></i>Alert Settings
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="configureRefresh()">
                                <i class="fas fa-clock me-2"></i>Auto-refresh Settings
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="resetDashboard()">
                                <i class="fas fa-undo me-2"></i>Reset Dashboard
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
                <label for="moduleFilter" class="form-label">Module</label>
                <select class="form-select form-select-sm" id="moduleFilter">
                    <option value="all">All Modules</option>
                    <option value="gesl-tracker">GESL Tracker</option>
                    <option value="team-pairing">Team Pairing</option>
                    <option value="workforce-fleet">Workforce & Fleet</option>
                    <option value="team-roaster">Team Roaster</option>
                    <option value="kpi-report">KPI Report</option>
                    <option value="material-balance">Material Balance</option>
                    <option value="ont-restock">ONT Restock</option>
                    <option value="sbc-scoring">SBC Scoring</option>
                    <option value="aging-dashboard">Aging Dashboard</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="activityFilter" class="form-label">Activity Type</label>
                <select class="form-select form-select-sm" id="activityFilter">
                    <option value="all">All Activities</option>
                    <option value="create">Created</option>
                    <option value="update">Updated</option>
                    <option value="delete">Deleted</option>
                    <option value="assign">Assigned</option>
                    <option value="complete">Completed</option>
                    <option value="alert">Alerts</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="priorityFilter" class="form-label">Priority</label>
                <select class="form-select form-select-sm" id="priorityFilter">
                    <option value="all">All Priorities</option>
                    <option value="critical">Critical</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
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
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                        <i class="fas fa-times me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="saveFilterPreset()">
                        <i class="fas fa-save me-1"></i>Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Tabs -->
    <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview-view" type="button" role="tab">
                <i class="fas fa-tachometer-alt me-1"></i>Overview
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="modules-tab" data-bs-toggle="tab" data-bs-target="#modules-view" type="button" role="tab">
                <i class="fas fa-th-large me-1"></i>Module Summary
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity-view" type="button" role="tab">
                <i class="fas fa-list me-1"></i>Activity Feed
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics-view" type="button" role="tab">
                <i class="fas fa-chart-line me-1"></i>Analytics
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="reportTabsContent">
        <!-- Overview View -->
        <div class="tab-pane fade show active" id="overview-view" role="tabpanel">
            <!-- Performance Overview -->
            <div class="report-table">
                <div class="report-table-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Performance Overview
                        <span class="float-end">
                            <small>Real-time Metrics</small>
                        </span>
                    </h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>Total Records</th>
                                <th>Today's Activity</th>
                                <th>Completion Rate</th>
                                <th>Pending Items</th>
                                <th>Status</th>
                                <th>Last Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-building text-primary me-2"></i>GESL Tracker</td>
                                <td>1,247</td>
                                <td>23</td>
                                <td><span class="status-good">94.5%</span></td>
                                <td>12</td>
                                <td><span class="status-good">Active</span></td>
                                <td>2 mins ago</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-users text-success me-2"></i>Team Pairing</td>
                                <td>89</td>
                                <td>5</td>
                                <td><span class="status-warning">87.2%</span></td>
                                <td>7</td>
                                <td><span class="status-good">Active</span></td>
                                <td>5 mins ago</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-boxes text-warning me-2"></i>Material Balance</td>
                                <td>2,532</td>
                                <td>18</td>
                                <td><span class="status-good">91.8%</span></td>
                                <td>8</td>
                                <td><span class="status-good">Active</span></td>
                                <td>1 min ago</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-redo text-info me-2"></i>ONT Restock</td>
                                <td>156</td>
                                <td>7</td>
                                <td><span class="status-good">96.3%</span></td>
                                <td>3</td>
                                <td><span class="status-good">Active</span></td>
                                <td>3 mins ago</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-star text-purple me-2"></i>SBC Scoring</td>
                                <td>67</td>
                                <td>2</td>
                                <td><span class="status-warning">83.4%</span></td>
                                <td>4</td>
                                <td><span class="status-warning">Warning</span></td>
                                <td>8 mins ago</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-clock text-danger me-2"></i>Aging Dashboard</td>
                                <td>342</td>
                                <td>11</td>
                                <td><span class="status-danger">76.1%</span></td>
                                <td>15</td>
                                <td><span class="status-danger">Critical</span></td>
                                <td>1 min ago</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Performance Indicators -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-gauge me-2"></i>System Performance</h6>
                        </div>
                        <div class="card-body">
                            <div class="performance-indicator">
                                <span class="performance-label">Overall Health</span>
                                <span class="performance-value performance-good">89.4%</span>
                            </div>
                            <div class="performance-indicator">
                                <span class="performance-label">Data Processing</span>
                                <span class="performance-value performance-good">94.2%</span>
                            </div>
                            <div class="performance-indicator">
                                <span class="performance-label">User Activity</span>
                                <span class="performance-value performance-warning">76.8%</span>
                            </div>
                            <div class="performance-indicator">
                                <span class="performance-label">Critical Issues</span>
                                <span class="performance-value performance-danger">5 Active</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-bell me-2"></i>Recent Alerts</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Aging Dashboard:</strong> 15 items pending action for over 30 days.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Material Balance:</strong> Low stock alert for 3 materials.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module Summary View -->
        <div class="tab-pane fade" id="modules-view" role="tabpanel">
            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="module-summary">
                        <div class="module-header module-gesl">
                            <div>
                                <i class="fas fa-building me-2"></i>GESL Tracker
                            </div>
                            <div class="badge bg-light text-dark">1,247 Records</div>
                        </div>
                        <div class="module-content">
                            <div class="module-stats">
                                <div class="module-stat">
                                    <div class="module-stat-value">94.5%</div>
                                    <div class="module-stat-label">Completion</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">23</div>
                                    <div class="module-stat-label">Today</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">12</div>
                                    <div class="module-stat-label">Pending</div>
                                </div>
                            </div>
                            <small class="text-muted">Last activity: 2 minutes ago</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="module-summary">
                        <div class="module-header module-team">
                            <div>
                                <i class="fas fa-users me-2"></i>Team Pairing
                            </div>
                            <div class="badge bg-light text-dark">89 Teams</div>
                        </div>
                        <div class="module-content">
                            <div class="module-stats">
                                <div class="module-stat">
                                    <div class="module-stat-value">87.2%</div>
                                    <div class="module-stat-label">Efficiency</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">5</div>
                                    <div class="module-stat-label">Active</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">7</div>
                                    <div class="module-stat-label">Unassigned</div>
                                </div>
                            </div>
                            <small class="text-muted">Last activity: 5 minutes ago</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="module-summary">
                        <div class="module-header module-workforce">
                            <div>
                                <i class="fas fa-id-badge me-2"></i>Workforce & Fleet
                            </div>
                            <div class="badge bg-light text-dark">156 Members</div>
                        </div>
                        <div class="module-content">
                            <div class="module-stats">
                                <div class="module-stat">
                                    <div class="module-stat-value">92.1%</div>
                                    <div class="module-stat-label">Available</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">43</div>
                                    <div class="module-stat-label">Vehicles</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">12</div>
                                    <div class="module-stat-label">Drivers</div>
                                </div>
                            </div>
                            <small class="text-muted">Last activity: 4 minutes ago</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="module-summary">
                        <div class="module-header module-material">
                            <div>
                                <i class="fas fa-boxes me-2"></i>Material Balance
                            </div>
                            <div class="badge bg-light text-dark">2,532 Items</div>
                        </div>
                        <div class="module-content">
                            <div class="module-stats">
                                <div class="module-stat">
                                    <div class="module-stat-value">91.8%</div>
                                    <div class="module-stat-label">In Stock</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">18</div>
                                    <div class="module-stat-label">Issued</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">8</div>
                                    <div class="module-stat-label">Low Stock</div>
                                </div>
                            </div>
                            <small class="text-muted">Last activity: 1 minute ago</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="module-summary">
                        <div class="module-header module-restock">
                            <div>
                                <i class="fas fa-redo me-2"></i>ONT Restock
                            </div>
                            <div class="badge bg-light text-dark">156 ONTs</div>
                        </div>
                        <div class="module-content">
                            <div class="module-stats">
                                <div class="module-stat">
                                    <div class="module-stat-value">96.3%</div>
                                    <div class="module-stat-label">Delivered</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">7</div>
                                    <div class="module-stat-label">Scheduled</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">3</div>
                                    <div class="module-stat-label">Pending</div>
                                </div>
                            </div>
                            <small class="text-muted">Last activity: 3 minutes ago</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="module-summary">
                        <div class="module-header module-sbc">
                            <div>
                                <i class="fas fa-star me-2"></i>SBC Scoring
                            </div>
                            <div class="badge bg-light text-dark">67 SBCs</div>
                        </div>
                        <div class="module-content">
                            <div class="module-stats">
                                <div class="module-stat">
                                    <div class="module-stat-value">83.4%</div>
                                    <div class="module-stat-label">Score</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">2</div>
                                    <div class="module-stat-label">Updated</div>
                                </div>
                                <div class="module-stat">
                                    <div class="module-stat-value">4</div>
                                    <div class="module-stat-label">Review</div>
                                </div>
                            </div>
                            <small class="text-muted">Last activity: 8 minutes ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Feed View -->
        <div class="tab-pane fade" id="activity-view" role="tabpanel">
            <div class="activity-feed">
                <div class="activity-header">
                    <h5 class="mb-0">
                        <i class="fas fa-stream me-2"></i>Real-time Activity Feed
                        <span class="float-end">
                            <small>Live Updates</small>
                        </span>
                    </h5>
                </div>
                
                <div id="activityFeedContent">
                    <!-- Activities will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Analytics View -->
        <div class="tab-pane fade" id="analytics-view" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">Module Activity Trends</div>
                        <canvas id="activityTrendChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">Performance Distribution</div>
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">Module Comparison</div>
                        <canvas id="moduleComparisonChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">Issues by Category</div>
                        <canvas id="issuesCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alerts Modal -->
<div class="modal fade" id="alertsModal" tabindex="-1" aria-labelledby="alertsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertsModalLabel">System Alerts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <div class="list-group-item list-group-item-warning">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><i class="fas fa-exclamation-triangle me-2"></i>Aging Dashboard Alert</h6>
                            <small>5 mins ago</small>
                        </div>
                        <p class="mb-1">15 items have been pending action for over 30 days.</p>
                        <small>Module: Aging Dashboard</small>
                    </div>
                    <div class="list-group-item list-group-item-info">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><i class="fas fa-info-circle me-2"></i>Material Stock Alert</h6>
                            <small>12 mins ago</small>
                        </div>
                        <p class="mb-1">3 materials are running low on stock.</p>
                        <small>Module: Material Balance</small>
                    </div>
                    <div class="list-group-item list-group-item-success">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><i class="fas fa-check-circle me-2"></i>ONT Restock Completed</h6>
                            <small>25 mins ago</small>
                        </div>
                        <p class="mb-1">Scheduled restock of 150 ONTs completed successfully.</p>
                        <small>Module: ONT Restock Tracker</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">View All Alerts</button>
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
let activityTrendChart;
let performanceChart;
let moduleComparisonChart;
let issuesCategoryChart;

$(document).ready(function() {
    // Initialize dashboard
    loadMasterReportData();
    loadActivityFeed();
    initializeCharts();
    updateLastUpdatedTime();
    
    // Initialize date picker
    flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
    });

    // Filter change handlers
    $('#moduleFilter, #activityFilter, #priorityFilter').on('change', function() {
        applyFilters();
    });

    // Auto-refresh every 30 seconds
    setInterval(function() {
        loadMasterReportData();
        loadActivityFeed();
        updateLastUpdatedTime();
    }, 30000);
});

// Data loading functions
function loadMasterReportData() {
    const filters = getActiveFilters();
    
    $.ajax({
        url: '/company/MasterTracker/master-report/data',
        method: 'GET',
        data: filters,
        success: function(response) {
            if (response.success) {
                updateDashboardStats(response.data);
                updateCharts(response.data);
            }
        },
        error: function() {
            console.log('Using sample data for Master Report');
        }
    });
}

function loadActivityFeed() {
    const filters = getActiveFilters();
    
    $.ajax({
        url: '/company/MasterTracker/master-report/activities',
        method: 'GET',
        data: { ...filters, limit: 50 },
        success: function(response) {
            if (response.success) {
                populateActivityFeed(response.data);
            }
        },
        error: function() {
            loadSampleActivityFeed();
        }
    });
}

function populateActivityFeed(activities) {
    let feedHtml = '';
    
    activities.forEach((activity, index) => {
        const iconClass = getActivityIcon(activity.type);
        const timeAgo = getTimeAgo(activity.created_at);
        
        feedHtml += `
            <div class="activity-item">
                <div class="activity-icon activity-${activity.type}">
                    <i class="${iconClass}"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">${activity.title}</div>
                    <div class="activity-description">${activity.description}</div>
                    <div class="activity-meta">
                        <div class="activity-time">
                            <i class="fas fa-clock me-1"></i>${timeAgo}
                        </div>
                        <div class="activity-module">${activity.module}</div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#activityFeedContent').html(feedHtml);
}

function updateDashboardStats(data) {
    // Update main stats cards
    $('#totalActivities').text(data.total_activities || '1,247');
    $('#activeModules').text(data.active_modules || '8');
    $('#pendingActions').text(data.pending_actions || '23');
    $('#criticalIssues').text(data.critical_issues || '5');
}

// Chart functions
function initializeCharts() {
    const ctx1 = document.getElementById('activityTrendChart').getContext('2d');
    const ctx2 = document.getElementById('performanceChart').getContext('2d');
    const ctx3 = document.getElementById('moduleComparisonChart').getContext('2d');
    const ctx4 = document.getElementById('issuesCategoryChart').getContext('2d');

    activityTrendChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Total Activities',
                data: [65, 78, 66, 89, 95, 120, 98],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
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
            }
        }
    });

    performanceChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Excellent', 'Good', 'Average', 'Poor'],
            datasets: [{
                data: [45, 30, 20, 5],
                backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#dc3545'],
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

    moduleComparisonChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ['GESL', 'Team', 'Material', 'Restock', 'SBC', 'Aging'],
            datasets: [{
                label: 'Performance Score',
                data: [94.5, 87.2, 91.8, 96.3, 83.4, 76.1],
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#17a2b8', '#6f42c1', '#fd7e14'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    issuesCategoryChart = new Chart(ctx4, {
        type: 'polarArea',
        data: {
            labels: ['Critical', 'High', 'Medium', 'Low'],
            datasets: [{
                data: [5, 12, 23, 45],
                backgroundColor: ['#dc3545', '#fd7e14', '#ffc107', '#28a745'],
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
    // Update charts with real data when available
    // For now, using sample data
}

// Utility functions
function getActivityIcon(type) {
    switch(type) {
        case 'create': return 'fas fa-plus';
        case 'update': return 'fas fa-edit';
        case 'delete': return 'fas fa-trash';
        case 'assign': return 'fas fa-user-plus';
        case 'complete': return 'fas fa-check';
        case 'alert': return 'fas fa-exclamation-triangle';
        default: return 'fas fa-info';
    }
}

function getTimeAgo(timestamp) {
    // Simple time ago calculation
    const now = new Date();
    const time = new Date(timestamp);
    const diff = Math.floor((now - time) / 1000);
    
    if (diff < 60) return 'Just now';
    if (diff < 3600) return Math.floor(diff / 60) + ' mins ago';
    if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
    return Math.floor(diff / 86400) + ' days ago';
}

function getActiveFilters() {
    return {
        module: $('#moduleFilter').val(),
        activity: $('#activityFilter').val(),
        priority: $('#priorityFilter').val(),
        date_range: $('#dateRange').val()
    };
}

function applyFilters() {
    loadMasterReportData();
    loadActivityFeed();
}

function resetFilters() {
    $('#moduleFilter').val('all');
    $('#activityFilter').val('all');
    $('#priorityFilter').val('all');
    $('#dateRange').val('');
    applyFilters();
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

// Global functions
window.generateMasterReport = function() {
    Swal.fire({
        title: 'Generate Master Report',
        html: `
            <div class="mb-3">
                <label class="form-label">Report Type:</label>
                <select class="form-select" id="reportType">
                    <option value="comprehensive">Comprehensive Report</option>
                    <option value="summary">Executive Summary</option>
                    <option value="performance">Performance Analysis</option>
                    <option value="activity">Activity Report</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Time Period:</label>
                <select class="form-select" id="timePeriod">
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="quarter">This Quarter</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Format:</label>
                <select class="form-select" id="reportFormat">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                    <option value="powerpoint">PowerPoint</option>
                </select>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Generate',
        preConfirm: () => {
            const type = document.getElementById('reportType').value;
            const period = document.getElementById('timePeriod').value;
            const format = document.getElementById('reportFormat').value;
            return { type, period, format };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Generated!', 'Master report has been generated and will be downloaded shortly.', 'success');
        }
    });
};

window.exportAllData = function() {
    Swal.fire({
        title: 'Export All Data',
        text: "This will export data from all Master Tracker modules. This may take a while.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, export all!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Exporting!', 'All data is being exported. You will receive a download link shortly.', 'success');
        }
    });
};

window.refreshAllModules = function() {
    Swal.fire({
        title: 'Refreshing All Modules...',
        text: 'Please wait while we update data from all modules.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading()
        }
    });
    
    setTimeout(() => {
        loadMasterReportData();
        loadActivityFeed();
        updateLastUpdatedTime();
        Swal.fire('Refreshed!', 'All module data has been updated.', 'success');
    }, 2000);
};

window.showPerformanceAnalytics = function() {
    $('#analytics-tab').click();
};

window.showTrendAnalysis = function() {
    $('#analytics-tab').click();
};

window.showModuleComparison = function() {
    $('#analytics-tab').click();
};

window.configureAlerts = function() {
    Swal.fire({
        title: 'Alert Configuration',
        html: `
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="emailAlerts" checked>
                    <label class="form-check-label" for="emailAlerts">
                        Email Alerts
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="smsAlerts">
                    <label class="form-check-label" for="smsAlerts">
                        SMS Alerts
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="criticalOnly">
                    <label class="form-check-label" for="criticalOnly">
                        Critical Issues Only
                    </label>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Save Settings'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Saved!', 'Alert settings have been updated.', 'success');
        }
    });
};

window.configureRefresh = function() {
    Swal.fire({
        title: 'Auto-refresh Settings',
        html: `
            <div class="mb-3">
                <label class="form-label">Refresh Interval:</label>
                <select class="form-select" id="refreshInterval">
                    <option value="30">30 seconds</option>
                    <option value="60">1 minute</option>
                    <option value="300">5 minutes</option>
                    <option value="0">Manual only</option>
                </select>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Save Settings'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Saved!', 'Auto-refresh settings have been updated.', 'success');
        }
    });
};

window.resetDashboard = function() {
    Swal.fire({
        title: 'Reset Dashboard?',
        text: "This will reset all filters and settings to default values.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, reset!'
    }).then((result) => {
        if (result.isConfirmed) {
            resetFilters();
            Swal.fire('Reset!', 'Dashboard has been reset to default settings.', 'success');
        }
    });
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
            Swal.fire('Saved!', 'Filter preset saved successfully.', 'success');
        }
    });
};

// Load sample activity feed
function loadSampleActivityFeed() {
    const sampleActivities = [
        {
            type: 'create',
            title: 'New GESL Tracker Record',
            description: 'Customer site installation completed for MSISDN: +233XXXXXXXX',
            module: 'GESL Tracker',
            created_at: new Date(Date.now() - 120000) // 2 minutes ago
        },
        {
            type: 'update',
            title: 'Material Stock Updated',
            description: 'ONT & Patch Cord stock levels updated for Team 3',
            module: 'Material Balance',
            created_at: new Date(Date.now() - 300000) // 5 minutes ago
        },
        {
            type: 'alert',
            title: 'Critical Aging Alert',
            description: '15 items have been pending for over 30 days',
            module: 'Aging Dashboard',
            created_at: new Date(Date.now() - 480000) // 8 minutes ago
        },
        {
            type: 'complete',
            title: 'ONT Restock Completed',
            description: 'Scheduled restock of 150 ONTs delivered successfully',
            module: 'ONT Restock',
            created_at: new Date(Date.now() - 600000) // 10 minutes ago
        },
        {
            type: 'assign',
            title: 'Team Assignment Updated',
            description: 'Team 5 assigned to SBC 7 for quality improvement',
            module: 'Team Pairing',
            created_at: new Date(Date.now() - 900000) // 15 minutes ago
        }
    ];
    
    populateActivityFeed(sampleActivities);
}

// Initialize sample data
$(document).ready(function() {
    loadSampleActivityFeed();
});
</script>
@endpush
