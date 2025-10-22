@extends('layouts.vertical', ['page_title' => 'SBC List & Scoring', 'mode' => session('theme_mode', 'light')])

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

<style>
    .sbc-container {
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
    .card-total { border-left-color: #007bff; }
    .card-active { border-left-color: #28a745; }
    .card-quality { border-left-color: #ffc107; }
    .card-teams { border-left-color: #17a2b8; }

    /* SBC Table Styles */
    .sbc-table {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .sbc-table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        text-align: center;
    }

    .sbc-table table {
        margin-bottom: 0;
        font-size: 11px;
    }

    .sbc-table th {
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

    .sbc-table td {
        text-align: center;
        border: 1px solid #dee2e6;
        padding: 6px 4px;
        font-size: 10px;
        vertical-align: middle;
    }

    /* SBC Name column styling */
    .sbc-name-cell {
        text-align: left !important;
        font-weight: 600;
        background: #f8f9fa;
        color: #495057;
    }

    /* Status indicators */
    .status-active {
        background-color: #d4edda !important;
        color: #155724;
        font-weight: 600;
    }

    .status-inactive {
        background-color: #f8d7da !important;
        color: #721c24;
        font-weight: 600;
    }

    /* Quality score colors */
    .quality-excellent {
        background-color: #d4edda !important;
        color: #155724;
        font-weight: 600;
    }

    .quality-good {
        background-color: #d1ecf1 !important;
        color: #0c5460;
        font-weight: 600;
    }

    .quality-average {
        background-color: #fff3cd !important;
        color: #856404;
        font-weight: 600;
    }

    .quality-poor {
        background-color: #f8d7da !important;
        color: #721c24;
        font-weight: 600;
    }

    /* Team tracker table */
    .team-tracker-table {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .team-tracker-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        text-align: center;
    }

    .team-name-cell {
        text-align: left !important;
        font-weight: 600;
        background: #f8f9fa;
        color: #495057;
    }

    /* Team status colors */
    .team-completed { background-color: #d4edda !important; color: #155724; }
    .team-ongoing { background-color: #d1ecf1 !important; color: #0c5460; }
    .team-rescheduled { background-color: #fff3cd !important; color: #856404; }
    .team-refunded { background-color: #f8d7da !important; color: #721c24; }
    .team-cancelled { background-color: #e2e3e5 !important; color: #383d41; }
    .team-out-of-scope { background-color: #ffeeba !important; color: #856404; }
    .team-on-hold { background-color: #bee5eb !important; color: #0c5460; }
    .team-action-required { background-color: #f5c6cb !important; color: #721c24; }

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

    /* Progress bars for metrics */
    .metric-bar {
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 4px;
    }

    .metric-fill {
        height: 100%;
        transition: width 0.3s ease;
    }

    .metric-excellent { background: #28a745; }
    .metric-good { background: #17a2b8; }
    .metric-average { background: #ffc107; }
    .metric-poor { background: #dc3545; }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .sbc-table th,
        .sbc-table td {
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

    /* Badge styles */
    .ranking-badge {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }

    .rank-1 { background-color: #ffd700; color: #000; }
    .rank-2 { background-color: #c0c0c0; color: #000; }
    .rank-3 { background-color: #cd7f32; color: #fff; }
    .rank-other { background-color: #6c757d; color: #fff; }

    /* Tabs for different views */
    .nav-tabs .nav-link {
        color: #495057;
        border-color: transparent;
    }

    .nav-tabs .nav-link.active {
        color: #007bff;
        border-color: #007bff #007bff #fff;
        background-color: #fff;
    }

    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
    }

    /* Total row styling */
    .total-row {
        background-color: #e9ecef !important;
        font-weight: 700;
        border-top: 2px solid #6c757d;
    }

    .total-row td {
        font-weight: 700;
        background-color: #e9ecef !important;
    }
</style>
@endsection

@section('content')
<div class="sbc-container">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Tracker</a></li>
                        <li class="breadcrumb-item active">SBC List & Scoring</li>
                    </ol>
                </div>
                <h4 class="page-title">SBC List & Scoring Management</h4>
                <p class="text-muted mb-0">Manage SBC performance tracking and team assignments</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-total">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                        <i class="fas fa-list-alt"></i>
                    </div>
                    <h6 class="card-title">Total SBCs</h6>
                    <h3 class="card-value" id="totalSbcs">11</h3>
                    <div class="card-change">
                        <i class="fas fa-building me-1"></i> Active SBC centers
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-active">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h6 class="card-title">Active SBCs</h6>
                    <h3 class="card-value" id="activeSbcs">9</h3>
                    <div class="card-change">
                        <i class="fas fa-chart-line me-1"></i> Currently operational
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-quality">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-star"></i>
                    </div>
                    <h6 class="card-title">Avg Quality Score</h6>
                    <h3 class="card-value" id="avgQuality">2.6</h3>
                    <div class="card-change">
                        <i class="fas fa-award me-1"></i> Overall performance
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-teams">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6 class="card-title">Active Teams</h6>
                    <h3 class="card-value" id="activeTeams">13</h3>
                    <div class="card-change">
                        <i class="fas fa-user-friends me-1"></i> Working teams
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSbcModal">
                        <i class="fas fa-plus me-2"></i>Add SBC
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                        <i class="fas fa-upload me-2"></i>Bulk Upload
                    </button>
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#teamAssignModal">
                        <i class="fas fa-user-plus me-2"></i>Assign Team
                    </button>
                    <button type="button" class="btn btn-warning" onclick="generateReport()">
                        <i class="fas fa-chart-bar me-2"></i>Generate Report
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
                            <li><a class="dropdown-item" href="#" onclick="calculateScores()">
                                <i class="fas fa-calculator me-2"></i>Recalculate Scores
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
                <label for="statusFilter" class="form-label">Status</label>
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="qualityFilter" class="form-label">Quality Score</label>
                <select class="form-select form-select-sm" id="qualityFilter">
                    <option value="all">All Scores</option>
                    <option value="excellent">Excellent (4.0+)</option>
                    <option value="good">Good (3.0-3.9)</option>
                    <option value="average">Average (2.0-2.9)</option>
                    <option value="poor">Poor (<2.0)</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="teamFilter" class="form-label">Team Count</label>
                <select class="form-select form-select-sm" id="teamFilter">
                    <option value="all">All Teams</option>
                    <option value="1">1 Team</option>
                    <option value="2">2 Teams</option>
                    <option value="3+">3+ Teams</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sbcSearch" class="form-label">Search SBC</label>
                <input type="text" class="form-control form-control-sm" id="sbcSearch" placeholder="Search by SBC name...">
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

    <!-- View Tabs -->
    <ul class="nav nav-tabs mb-4" id="viewTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="sbc-list-tab" data-bs-toggle="tab" data-bs-target="#sbc-list-view" type="button" role="tab">
                <i class="fas fa-list me-1"></i>SBC List & Scoring
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="team-tracker-tab" data-bs-toggle="tab" data-bs-target="#team-tracker-view" type="button" role="tab">
                <i class="fas fa-users me-1"></i>Team Tracker
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="viewTabsContent">
        <!-- SBC List & Scoring View -->
        <div class="tab-pane fade show active" id="sbc-list-view" role="tabpanel">
            <div class="sbc-table">
                <div class="sbc-table-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>SBC List & Scoring
                        <span class="float-end">
                            <small>Total: <span id="sbcListTotal">11</span> SBCs</small>
                        </span>
                    </h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm mb-0" id="sbcTable">
                        <thead>
                            <tr>
                                <th style="width: 100px;">SBC Name</th>
                                <th style="width: 80px;">Status</th>
                                <th style="width: 80px;">Current Team Count</th>
                                <th style="width: 80px;">Previous Team Count</th>
                                <th style="width: 100px;">Completed Sites</th>
                                <th style="width: 80px;">Start Date</th>
                                <th style="width: 80px;">Duration</th>
                                <th style="width: 100px;">Average per Month</th>
                                <th style="width: 100px;">Average per Team</th>
                                <th style="width: 100px;">Overall Quality Score</th>
                                <th style="width: 80px;">Rating</th>
                                <th style="width: 80px;">Rank</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sbcTableBody">
                            <!-- Data will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Team Tracker View -->
        <div class="tab-pane fade" id="team-tracker-view" role="tabpanel">
            <div class="team-tracker-table">
                <div class="team-tracker-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Tracker Team Total List
                        <span class="float-end">
                            <small>Total Teams: <span id="teamListTotal">13</span></small>
                        </span>
                    </h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm mb-0" id="teamTrackerTable">
                        <thead>
                            <tr>
                                <th style="width: 150px;">Team Name</th>
                                <th style="width: 150px;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="teamTrackerTableBody">
                            <!-- Data will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row" id="chartsSection">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">SBC Performance Distribution</div>
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">Team Status Overview</div>
                <canvas id="teamStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Add SBC Modal -->
<div class="modal fade" id="addSbcModal" tabindex="-1" aria-labelledby="addSbcModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSbcModalLabel">Add New SBC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSbcForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="sbc_name" id="sbcName" required placeholder=" ">
                                <label for="sbcName" class="required-field">SBC Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="sbcStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="sbcStatus" class="required-field">Status</label>
                            </div>
                        </div>
                        
                        <!-- Team Information -->
                        <div class="col-12">
                            <h6 class="mb-3 text-primary"><i class="fas fa-users me-2"></i>Team Information</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="current_team_count" id="currentTeamCount" min="0" placeholder=" ">
                                <label for="currentTeamCount">Current Team Count</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="previous_team_count" id="previousTeamCount" min="0" placeholder=" ">
                                <label for="previousTeamCount">Previous Team Count</label>
                            </div>
                        </div>
                        
                        <!-- Performance Metrics -->
                        <div class="col-12">
                            <h6 class="mb-3 text-success"><i class="fas fa-chart-line me-2"></i>Performance Metrics</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="completed_sites" id="completedSites" min="0" placeholder=" ">
                                <label for="completedSites">Completed Sites</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="start_date" id="startDate" placeholder=" ">
                                <label for="startDate">Start Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="duration" id="duration" step="0.1" min="0" placeholder=" ">
                                <label for="duration">Duration (months)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="overall_quality_score" id="overallQualityScore" step="0.1" min="0" max="5" placeholder=" ">
                                <label for="overallQualityScore">Overall Quality Score</label>
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="location" id="sbcLocation" placeholder=" ">
                                <label for="sbcLocation">Location</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="manager" id="sbcManager" placeholder=" ">
                                <label for="sbcManager">Manager</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="sbcNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="sbcNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add SBC</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Team Assignment Modal -->
<div class="modal fade" id="teamAssignModal" tabindex="-1" aria-labelledby="teamAssignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teamAssignModalLabel">Assign Team to SBC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="teamAssignForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="assignSbc" class="form-label required-field">SBC</label>
                            <select class="form-select" name="sbc_id" id="assignSbc" required style="width: 100%;">
                                <option value="">Select SBC</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_name" id="teamName" required placeholder=" ">
                                <label for="teamName" class="required-field">Team Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="team_status" id="teamStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="completed">Completed</option>
                                    <option value="ongoing">Ongoing</option>
                                    <option value="rescheduled">Rescheduled</option>
                                    <option value="refunded">Refunded</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="out-of-scope">Out of Scope</option>
                                    <option value="on-hold">On Hold</option>
                                    <option value="action-required">Action Required</option>
                                </select>
                                <label for="teamStatus" class="required-field">Status</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="assignment_date" id="assignmentDate" placeholder=" ">
                                <label for="assignmentDate">Assignment Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_lead" id="teamLead" placeholder=" ">
                                <label for="teamLead">Team Lead</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="team_size" id="teamSize" min="1" placeholder=" ">
                                <label for="teamSize">Team Size</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="assignment_notes" id="assignmentNotes" placeholder=" " style="height: 80px"></textarea>
                                <label for="assignmentNotes">Assignment Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Assign Team</button>
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
                <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload SBC Data</h5>
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
                                    <li>Download the template file and fill in your SBC data</li>
                                    <li>Required columns: SBC Name, Status, Current Team Count</li>
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
                                        <a href="/company/MasterTracker/sbc-list-scoring/template/excel" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-file-excel me-1"></i>Excel Template
                                        </a>
                                        <a href="/company/MasterTracker/sbc-list-scoring/template/csv" class="btn btn-outline-info btn-sm">
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
                                    Update existing SBCs if name matches
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
let performanceChart;
let teamStatusChart;

$(document).ready(function() {
    // Load initial data
    loadSbcData();
    loadTeamTrackerData();
    updateLastUpdatedTime();
    initializeCharts();
    loadSbcOptions();
    
    // Initialize Select2
    $('#assignSbc').select2({
        placeholder: 'Select SBC',
        allowClear: true,
        dropdownParent: $('#teamAssignModal')
    });

    // Form submissions
    $('#addSbcForm').on('submit', function(e) {
        e.preventDefault();
        submitNewSbc(this);
    });

    $('#teamAssignForm').on('submit', function(e) {
        e.preventDefault();
        submitTeamAssignment(this);
    });

    $('#bulkUploadForm').on('submit', function(e) {
        e.preventDefault();
        submitBulkUpload(this);
    });

    // Filter change handlers
    $('#statusFilter, #qualityFilter, #teamFilter').on('change', function() {
        applyFilters();
    });

    $('#sbcSearch').on('keyup', debounce(function() {
        applyFilters();
    }, 500));
});

// Data loading functions
function loadSbcData() {
    const filters = getActiveFilters();
    
    $.ajax({
        url: '/company/MasterTracker/sbc-list-scoring/data',
        method: 'GET',
        data: filters,
        success: function(response) {
            if (response.success) {
                populateSbcTable(response.data);
                updateCharts(response.data);
            }
        },
        error: function() {
            // Load sample data for demonstration
            loadSampleSbcData();
        }
    });
}

function loadTeamTrackerData() {
    $.ajax({
        url: '/company/MasterTracker/sbc-list-scoring/team-tracker',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                populateTeamTrackerTable(response.data);
            }
        },
        error: function() {
            // Load sample team data
            loadSampleTeamData();
        }
    });
}

function populateSbcTable(data) {
    let tableBody = '';
    
    data.forEach((sbc, index) => {
        const statusClass = sbc.status === 'Active' ? 'status-active' : 'status-inactive';
        const qualityClass = getQualityClass(sbc.overall_quality_score);
        const rankClass = getRankClass(sbc.rank);
        
        tableBody += `
            <tr>
                <td class="sbc-name-cell">${sbc.sbc_name}</td>
                <td class="${statusClass}">${sbc.status}</td>
                <td>${sbc.current_team_count}</td>
                <td>${sbc.previous_team_count}</td>
                <td>${sbc.completed_sites || '#REF!'}</td>
                <td>${formatDate(sbc.start_date)}</td>
                <td>${sbc.duration}</td>
                <td>${sbc.average_per_month || '#REF!'}</td>
                <td>${sbc.average_per_team || '#REF!'}</td>
                <td class="${qualityClass}">${sbc.overall_quality_score}</td>
                <td>${sbc.rating || '#REF!'}</td>
                <td>${getRankBadge(sbc.rank)}</td>
                <td>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-warning action-btn" 
                                onclick="editSbc(${sbc.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger action-btn" 
                                onclick="deleteSbc(${sbc.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-info action-btn" 
                                onclick="viewDetails(${sbc.id})" title="Details">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    // Add total row
    tableBody += `
        <tr class="total-row">
            <td class="sbc-name-cell">Total</td>
            <td></td>
            <td><strong>14</strong></td>
            <td><strong>0</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    `;
    
    $('#sbcTableBody').html(tableBody);
}

function populateTeamTrackerTable(data) {
    let tableBody = '';
    
    data.forEach((team, index) => {
        const statusClass = getTeamStatusClass(team.status);
        tableBody += `
            <tr>
                <td class="team-name-cell">${team.team_name}</td>
                <td class="${statusClass}">${team.status}</td>
            </tr>
        `;
    });
    
    $('#teamTrackerTableBody').html(tableBody);
}

// Chart functions
function initializeCharts() {
    const ctx1 = document.getElementById('performanceChart').getContext('2d');
    const ctx2 = document.getElementById('teamStatusChart').getContext('2d');

    performanceChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Quality Score',
                data: [],
                backgroundColor: '#007bff',
                borderColor: '#007bff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    teamStatusChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Ongoing', 'Rescheduled', 'On Hold', 'Others'],
            datasets: [{
                data: [0, 0, 0, 0, 0],
                backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#6c757d', '#dc3545'],
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
    // Update performance chart
    const sbcNames = data.map(sbc => sbc.sbc_name);
    const qualityScores = data.map(sbc => parseFloat(sbc.overall_quality_score) || 0);

    performanceChart.data.labels = sbcNames;
    performanceChart.data.datasets[0].data = qualityScores;
    performanceChart.update();

    // Update team status chart with sample data
    const statusData = [1, 1, 1, 1, 9]; // Based on sample team data
    teamStatusChart.data.datasets[0].data = statusData;
    teamStatusChart.update();
}

// Form submission functions
function submitNewSbc(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/sbc-list-scoring/sbcs',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'SBC added successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#addSbcModal').modal('hide');
                form.reset();
                loadSbcData();
                loadSbcOptions();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function submitTeamAssignment(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/sbc-list-scoring/teams/assign',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Team assigned successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#teamAssignModal').modal('hide');
                form.reset();
                loadTeamTrackerData();
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
        url: '/company/MasterTracker/sbc-list-scoring/sbcs/bulk-upload',
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
                let message = `Successfully processed ${response.data.processed} SBC records`;
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
                loadSbcData();
                loadSbcOptions();
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

// Utility functions
function getQualityClass(score) {
    const quality = parseFloat(score) || 0;
    if (quality >= 4.0) return 'quality-excellent';
    if (quality >= 3.0) return 'quality-good';
    if (quality >= 2.0) return 'quality-average';
    return 'quality-poor';
}

function getRankClass(rank) {
    if (rank === 1) return 'rank-1';
    if (rank === 2) return 'rank-2';
    if (rank === 3) return 'rank-3';
    return 'rank-other';
}

function getRankBadge(rank) {
    if (!rank || rank === '#REF!') return '<span class="ranking-badge rank-other">#REF!</span>';
    const rankClass = getRankClass(rank);
    return `<span class="ranking-badge ${rankClass}">${rank}</span>`;
}

function getTeamStatusClass(status) {
    switch(status.toLowerCase()) {
        case 'completed': return 'team-completed';
        case 'ongoing': return 'team-ongoing';
        case 'rescheduled': return 'team-rescheduled';
        case 'refunded': return 'team-refunded';
        case 'cancelled': return 'team-cancelled';
        case 'out of scope': return 'team-out-of-scope';
        case 'on hold': return 'team-on-hold';
        case 'action required': return 'team-action-required';
        default: return '';
    }
}

function formatDate(dateString) {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-US');
}

function getActiveFilters() {
    return {
        status: $('#statusFilter').val(),
        quality: $('#qualityFilter').val(),
        team_count: $('#teamFilter').val(),
        search: $('#sbcSearch').val()
    };
}

function applyFilters() {
    loadSbcData();
}

function resetFilters() {
    $('#statusFilter').val('all');
    $('#qualityFilter').val('all');
    $('#teamFilter').val('all');
    $('#sbcSearch').val('');
    applyFilters();
}

function loadSbcOptions() {
    $.ajax({
        url: '/company/MasterTracker/sbc-list-scoring/sbcs/options',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">Select SBC</option>';
                response.data.forEach(sbc => {
                    options += `<option value="${sbc.id}">${sbc.sbc_name}</option>`;
                });
                $('#assignSbc').html(options);
            }
        }
    });
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
    loadSbcData();
    loadTeamTrackerData();
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
    loadSbcData();
    updateLastUpdatedTime();
    Swal.fire({
        icon: 'success',
        title: 'Stats Refreshed!',
        text: 'Statistics updated successfully.',
        showConfirmButton: false,
        timer: 1500
    });
};

window.exportData = function(format) {
    const filters = getActiveFilters();
    const params = new URLSearchParams(filters).toString();
    window.open(`/company/MasterTracker/sbc-list-scoring/export/${format}?${params}`, '_blank');
};

window.generateReport = function() {
    Swal.fire({
        title: 'Generate Report',
        html: `
            <div class="mb-3">
                <label class="form-label">Report Type:</label>
                <select class="form-select" id="reportType">
                    <option value="performance">Performance Report</option>
                    <option value="team-summary">Team Summary</option>
                    <option value="ranking">Ranking Report</option>
                </select>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Generate',
        preConfirm: () => {
            const type = document.getElementById('reportType').value;
            return { type };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Generate report logic here
            Swal.fire('Generated!', 'Report has been generated and downloaded.', 'success');
        }
    });
};

window.calculateScores = function() {
    Swal.fire({
        title: 'Recalculate Scores?',
        text: "This will recalculate all SBC quality scores and rankings.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, recalculate!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Recalculate logic here
            Swal.fire('Recalculated!', 'All scores have been updated.', 'success');
            loadSbcData();
        }
    });
};

window.editSbc = function(id) {
    // Edit SBC logic
    console.log('Edit SBC:', id);
};

window.deleteSbc = function(id) {
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
            // Delete logic here
            Swal.fire('Deleted!', 'SBC has been deleted.', 'success');
        }
    });
};

window.viewDetails = function(id) {
    // View details logic
    console.log('View details:', id);
};

// Load sample data for demonstration
function loadSampleSbcData() {
    const sampleData = [
        { id: 1, sbc_name: 'SBC 1', status: 'Active', current_team_count: 2, previous_team_count: 2, completed_sites: '#REF!', start_date: '2025-04-24', duration: 4.4, average_per_month: '#REF!', average_per_team: '#REF!', overall_quality_score: 4.0, rating: '#REF!', rank: '#REF!' },
        { id: 2, sbc_name: 'SBC 2', status: 'Active', current_team_count: 2, previous_team_count: 2, completed_sites: '#REF!', start_date: '2025-05-21', duration: 3.5, average_per_month: '#REF!', average_per_team: '#REF!', overall_quality_score: 3.5, rating: '#REF!', rank: '#REF!' },
        { id: 3, sbc_name: 'SBC 3', status: 'Active', current_team_count: 1, previous_team_count: 1, completed_sites: '#REF!', start_date: '2025-05-16', duration: 3.7, average_per_month: '#REF!', average_per_team: '#REF!', overall_quality_score: 2.0, rating: '#REF!', rank: '#REF!' },
        { id: 4, sbc_name: 'SBC 4', status: 'Active', current_team_count: 2, previous_team_count: 2, completed_sites: '#REF!', start_date: '2025-05-29', duration: 3.3, average_per_month: '#REF!', average_per_team: '#REF!', overall_quality_score: 3.0, rating: '#REF!', rank: '#REF!' },
        { id: 5, sbc_name: 'SBC 5', status: 'Active', current_team_count: 3, previous_team_count: 4, completed_sites: '#REF!', start_date: '2025-05-07', duration: 4.0, average_per_month: '#REF!', average_per_team: '#REF!', overall_quality_score: 1.0, rating: '#REF!', rank: '#REF!' },
        { id: 6, sbc_name: 'SBC 6', status: 'Active', current_team_count: 1, previous_team_count: 1, completed_sites: '#REF!', start_date: '2025-07-10', duration: 1.9, average_per_month: '#REF!', average_per_team: '#REF!', overall_quality_score: 1.8, rating: '#REF!', rank: '#REF!' },
        { id: 7, sbc_name: 'SBC 7', status: 'Active', current_team_count: 1, previous_team_count: 2, completed_sites: '#REF!', start_date: '2025-04-28', duration: 4.3, average_per_month: '#REF!', average_per_team: '#REF!', overall_quality_score: 2.5, rating: '#REF!', rank: '#REF!' },
        { id: 8, sbc_name: 'SBC 8', status: 'Active', current_team_count: 1, previous_team_count: 1, completed_sites: '#REF!', start_date: '2025-04-29', duration: 4.3, average_per_month: '#REF!', average_per_team: '#REF!', overall_quality_score: 3.0, rating: '#REF!', rank: '#REF!' },
        { id: 9, sbc_name: 'SBC 9', status: 'Active', current_team_count: 1, previous_team_count: 1, completed_sites: '#REF!', start_date: '2025-07-10', duration: 1.9, average_per_month: '#REF!', average_per_team: '#REF!', overall_quality_score: 1.8, rating: '#REF!', rank: '#REF!' },
        { id: 10, sbc_name: 'SBC 10', status: 'Inactive', current_team_count: 1, previous_team_count: 1, completed_sites: '#REF!', start_date: null, duration: 1530.1, average_per_month: 0, average_per_team: 0, overall_quality_score: 0.0, rating: 0, rank: '#REF!' },
        { id: 11, sbc_name: 'SBC 11', status: 'Inactive', current_team_count: 3, previous_team_count: 3, completed_sites: '#REF!', start_date: null, duration: 1530.1, average_per_month: 0, average_per_team: 0, overall_quality_score: 0.0, rating: 0, rank: '#REF!' }
    ];
    
    populateSbcTable(sampleData);
    updateCharts(sampleData);
}

function loadSampleTeamData() {
    const sampleTeamData = [
        { team_name: 'Team 1', status: 'Completed' },
        { team_name: 'Team 2', status: 'Ongoing' },
        { team_name: 'Team 3', status: 'Rescheduled' },
        { team_name: 'Team 4', status: 'Refunded' },
        { team_name: 'Team 5', status: 'Cancelled' },
        { team_name: 'Team 6', status: 'Out of Scope' },
        { team_name: 'Team 7', status: 'On Hold' },
        { team_name: 'Team 8', status: 'Action Required' },
        { team_name: 'Team 9', status: '' },
        { team_name: 'Team 10', status: '' },
        { team_name: 'Team 11', status: '' },
        { team_name: 'Team 12', status: '' },
        { team_name: 'Team 13', status: '' }
    ];
    
    populateTeamTrackerTable(sampleTeamData);
}

// Initialize sample data on load
$(document).ready(function() {
    loadSampleSbcData();
    loadSampleTeamData();
});
</script>
@endpush
