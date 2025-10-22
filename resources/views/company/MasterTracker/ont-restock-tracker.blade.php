@extends('layouts.vertical', ['page_title' => 'ONT Restock Tracker', 'mode' => session('theme_mode', 'light')])

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
    .restock-container {
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
    .card-linfra { border-left-color: #28a745; }
    .card-pktech { border-left-color: #ffc107; }
    .card-pending { border-left-color: #17a2b8; }

    /* Restock Table Styles */
    .restock-table {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .restock-table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        text-align: center;
    }

    .restock-table table {
        margin-bottom: 0;
        font-size: 12px;
    }

    .restock-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        text-align: center;
        border: 1px solid #dee2e6;
        padding: 10px 8px;
        font-size: 11px;
        vertical-align: middle;
    }

    .restock-table td {
        text-align: center;
        border: 1px solid #dee2e6;
        padding: 8px 6px;
        font-size: 11px;
        vertical-align: middle;
    }

    /* Company header colors */
    .header-linfra {
        background: #28a745 !important;
        color: white;
    }

    .header-pktech {
        background: #ffc107 !important;
        color: #212529;
    }

    .header-total {
        background: #007bff !important;
        color: white;
    }

    .header-date {
        background: #6c757d !important;
        color: white;
    }

    /* Time tracking table */
    .time-tracking-table {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .time-tracking-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        text-align: center;
    }

    .weekend-row {
        background-color: #e3f2fd !important;
        font-weight: 600;
    }

    .date-cell {
        text-align: left !important;
        font-weight: 600;
        background: #f8f9fa;
    }

    /* Status indicators */
    .status-completed {
        background-color: #d4edda !important;
        color: #155724;
        font-weight: 600;
    }

    .status-pending {
        background-color: #fff3cd !important;
        color: #856404;
        font-weight: 600;
    }

    .status-overdue {
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

    /* Calendar integration */
    .calendar-widget {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    .calendar-day {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 2px;
    }

    .calendar-day:hover {
        background: #e9ecef;
    }

    .calendar-day.has-restock {
        background: #007bff;
        color: white;
    }

    .calendar-day.weekend {
        background: #f8f9fa;
        color: #6c757d;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .restock-table th,
        .restock-table td {
            font-size: 10px;
            padding: 6px 4px;
        }

        .card-value {
            font-size: 24px;
        }

        .chart-container {
            height: 300px;
        }
    }

    /* Quick add form */
    .quick-add-panel {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
    }

    .quantity-input {
        max-width: 120px;
        text-align: center;
    }

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
</style>
@endsection

@section('content')
<div class="restock-container">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Tracker</a></li>
                        <li class="breadcrumb-item active">ONT Restock Tracker</li>
                    </ol>
                </div>
                <h4 class="page-title">ONT Restock Tracker</h4>
                <p class="text-muted mb-0">Track ONT restock schedules and inventory distribution</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-total">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h6 class="card-title">Total ONTs</h6>
                    <h3 class="card-value" id="totalOnts">8740</h3>
                    <div class="card-change">
                        <i class="fas fa-layer-group me-1"></i> Total inventory
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-linfra">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <i class="fas fa-building"></i>
                    </div>
                    <h6 class="card-title">Linfra Total</h6>
                    <h3 class="card-value" id="linfraTotal">4610</h3>
                    <div class="card-change">
                        <i class="fas fa-chart-line me-1"></i> Linfra allocation
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-pktech">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h6 class="card-title">PK Tech Total</h6>
                    <h3 class="card-value" id="pktechTotal">4130</h3>
                    <div class="card-change">
                        <i class="fas fa-chart-bar me-1"></i> PK Tech allocation
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
                    <h6 class="card-title">Pending Restocks</h6>
                    <h3 class="card-value" id="pendingRestocks">12</h3>
                    <div class="card-change">
                        <i class="fas fa-calendar-alt me-1"></i> Scheduled restocks
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRestockModal">
                        <i class="fas fa-plus me-2"></i>Add Restock
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                        <i class="fas fa-upload me-2"></i>Bulk Upload
                    </button>
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#quickAddModal">
                        <i class="fas fa-lightning-bolt me-2"></i>Quick Add
                    </button>
                    <button type="button" class="btn btn-warning" onclick="generateSchedule()">
                        <i class="fas fa-calendar-plus me-2"></i>Generate Schedule
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
                                <i class="fas fa-file-pdf me-2"></i>PDF Schedule
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
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#calendarModal">
                                <i class="fas fa-calendar me-2"></i>Calendar View
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
                <label for="monthFilter" class="form-label">Month</label>
                <select class="form-select form-select-sm" id="monthFilter">
                    <option value="all">All Months</option>
                    <option value="current">Current Month</option>
                    <option value="next">Next Month</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="companyFilter" class="form-label">Company</label>
                <select class="form-select form-select-sm" id="companyFilter">
                    <option value="all">All Companies</option>
                    <option value="linfra">Linfra</option>
                    <option value="pktech">PK Tech</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="statusFilter" class="form-label">Status</label>
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="all">All Status</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="yearFilter" class="form-label">Year</label>
                <select class="form-select form-select-sm" id="yearFilter">
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2026">2026</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="applyFilters()">
                        <i class="fas fa-filter me-1"></i>Apply
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                        <i class="fas fa-times me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="toggleView()">
                        <i class="fas fa-eye me-1"></i>Toggle View
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Tabs -->
    <ul class="nav nav-tabs mb-4" id="viewTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule-view" type="button" role="tab">
                <i class="fas fa-calendar-alt me-1"></i>Restock Schedule
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="timetrack-tab" data-bs-toggle="tab" data-bs-target="#timetrack-view" type="button" role="tab">
                <i class="fas fa-clock me-1"></i>Time Tracking
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="viewTabsContent">
        <!-- Restock Schedule View -->
        <div class="tab-pane fade show active" id="schedule-view" role="tabpanel">
            <div class="restock-table">
                <div class="restock-table-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>ONT Restock Schedule
                        <span class="float-end">
                            <small>Total: <span id="scheduleTotal">8740</span> ONTs</small>
                        </span>
                    </h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm mb-0" id="restockTable">
                        <thead>
                            <tr>
                                <th class="header-date">Issuance Date</th>
                                <th class="header-linfra">Linfra</th>
                                <th class="header-pktech">PK Tech</th>
                                <th class="header-total">Total</th>
                                <th style="background: #6c757d; color: white;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="restockTableBody">
                            <!-- Data will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Time Tracking View -->
        <div class="tab-pane fade" id="timetrack-view" role="tabpanel">
            <div class="time-tracking-table">
                <div class="time-tracking-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Time-based Tracking
                        <span class="float-end">
                            <small>Weekend Schedule</small>
                        </span>
                    </h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm mb-0" id="timeTrackingTable">
                        <thead>
                            <tr>
                                <th style="background: #6c757d; color: white; width: 120px;">Day</th>
                                <th style="background: #6c757d; color: white; width: 80px;">Time</th>
                                <th class="header-gesl">GESL</th>
                                <th class="header-linfra">Linfra</th>
                            </tr>
                        </thead>
                        <tbody id="timeTrackingTableBody">
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
                <div class="chart-title">Monthly Restock Distribution</div>
                <canvas id="restockDistributionChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">Company Allocation Trends</div>
                <canvas id="allocationTrendChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Add Restock Modal -->
<div class="modal fade" id="addRestockModal" tabindex="-1" aria-labelledby="addRestockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRestockModalLabel">Add Restock Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addRestockForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="issuance_date" id="issuanceDate" required>
                                <label for="issuanceDate" class="required-field">Issuance Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="priority" id="restockPriority">
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                                <label for="restockPriority">Priority</label>
                            </div>
                        </div>
                        
                        <!-- Company Allocations -->
                        <div class="col-12">
                            <h6 class="mb-3 text-primary"><i class="fas fa-building me-2"></i>Company Allocations</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="linfra_quantity" id="linfraQuantity" min="0" placeholder=" ">
                                <label for="linfraQuantity">Linfra Quantity</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="pktech_quantity" id="pktechQuantity" min="0" placeholder=" ">
                                <label for="pktechQuantity">PK Tech Quantity</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="total_quantity" id="totalQuantity" readonly placeholder=" ">
                                <label for="totalQuantity">Total Quantity (Auto-calculated)</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="supplier" id="supplier" placeholder=" ">
                                <label for="supplier">Supplier</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="restockStatus">
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="in-transit">In Transit</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="completed">Completed</option>
                                </select>
                                <label for="restockStatus">Status</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="restockNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="restockNotes">Notes</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sendNotification" name="send_notification">
                                <label class="form-check-label" for="sendNotification">
                                    Send notification to relevant teams
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Restock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Add Modal -->
<div class="modal fade" id="quickAddModal" tabindex="-1" aria-labelledby="quickAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickAddModalLabel">Quick Add Restock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickAddForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="quick_date" id="quickDate" required>
                                <label for="quickDate" class="required-field">Date</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <input type="number" class="form-control quantity-input" name="quick_linfra" id="quickLinfra" min="0" placeholder=" ">
                                <label for="quickLinfra">Linfra</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <input type="number" class="form-control quantity-input" name="quick_pktech" id="quickPktech" min="0" placeholder=" ">
                                <label for="quickPktech">PK Tech</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Quick Add</button>
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
                <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload Restock Data</h5>
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
                                    <li>Download the template file and fill in your restock data</li>
                                    <li>Required columns: Issuance Date, Linfra, PK Tech</li>
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
                                        <a href="/company/MasterTracker/ont-restock-tracker/template/excel" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-file-excel me-1"></i>Excel Template
                                        </a>
                                        <a href="/company/MasterTracker/ont-restock-tracker/template/csv" class="btn btn-outline-info btn-sm">
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
                                    Update existing records if date matches
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
let restockDistributionChart;
let allocationTrendChart;

$(document).ready(function() {
    // Load initial data
    loadRestockData();
    loadTimeTrackingData();
    updateLastUpdatedTime();
    initializeCharts();
    
    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    $('#issuanceDate, #quickDate').val(today);

    // Form submissions
    $('#addRestockForm').on('submit', function(e) {
        e.preventDefault();
        submitRestock(this);
    });

    $('#quickAddForm').on('submit', function(e) {
        e.preventDefault();
        submitQuickAdd(this);
    });

    $('#bulkUploadForm').on('submit', function(e) {
        e.preventDefault();
        submitBulkUpload(this);
    });

    // Auto-calculate total quantity
    $('#linfraQuantity, #pktechQuantity').on('input', function() {
        calculateTotal();
    });

    $('#quickLinfra, #quickPktech').on('input', function() {
        calculateQuickTotal();
    });

    // Filter change handlers
    $('#monthFilter, #companyFilter, #statusFilter, #yearFilter').on('change', function() {
        applyFilters();
    });
});

// Data loading functions
function loadRestockData() {
    const filters = getActiveFilters();
    
    $.ajax({
        url: '/company/MasterTracker/ont-restock-tracker/data',
        method: 'GET',
        data: filters,
        success: function(response) {
            if (response.success) {
                populateRestockTable(response.data);
                updateCharts(response.data);
            }
        },
        error: function() {
            // Load sample data for demonstration
            loadSampleData();
        }
    });
}

function loadTimeTrackingData() {
    $.ajax({
        url: '/company/MasterTracker/ont-restock-tracker/time-tracking',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                populateTimeTrackingTable(response.data);
            }
        },
        error: function() {
            // Load sample time tracking data
            loadSampleTimeData();
        }
    });
}

function populateRestockTable(data) {
    let tableBody = '';
    
    data.forEach((restock, index) => {
        const statusClass = getStatusClass(restock.status);
        tableBody += `
            <tr class="${statusClass}">
                <td class="date-cell">${formatDate(restock.issuance_date)}</td>
                <td>${formatNumber(restock.linfra_quantity)}</td>
                <td>${formatNumber(restock.pktech_quantity)}</td>
                <td><strong>${formatNumber(restock.total_quantity)}</strong></td>
                <td>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-warning action-btn" 
                                onclick="editRestock(${restock.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger action-btn" 
                                onclick="deleteRestock(${restock.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-info action-btn" 
                                onclick="viewDetails(${restock.id})" title="Details">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    $('#restockTableBody').html(tableBody);
}

function populateTimeTrackingTable(data) {
    let tableBody = '';
    
    data.forEach((track, index) => {
        const rowClass = track.day.includes('Saturday') || track.day.includes('Sunday') ? 'weekend-row' : '';
        tableBody += `
            <tr class="${rowClass}">
                <td class="date-cell">${track.day}</td>
                <td>${track.time}</td>
                <td>${track.gesl || '-'}</td>
                <td>${track.linfra || '-'}</td>
            </tr>
        `;
    });
    
    $('#timeTrackingTableBody').html(tableBody);
}

// Chart functions
function initializeCharts() {
    const ctx1 = document.getElementById('restockDistributionChart').getContext('2d');
    const ctx2 = document.getElementById('allocationTrendChart').getContext('2d');

    restockDistributionChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Linfra',
                data: [],
                backgroundColor: '#28a745',
                borderColor: '#28a745',
                borderWidth: 1
            }, {
                label: 'PK Tech',
                data: [],
                backgroundColor: '#ffc107',
                borderColor: '#ffc107',
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
                    position: 'top',
                }
            }
        }
    });

    allocationTrendChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Linfra Trend',
                data: [],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }, {
                label: 'PK Tech Trend',
                data: [],
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
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
}

function updateCharts(data) {
    // Update distribution chart
    const months = data.map(item => formatDate(item.issuance_date).substring(0, 7));
    const linfraData = data.map(item => item.linfra_quantity);
    const pktechData = data.map(item => item.pktech_quantity);

    restockDistributionChart.data.labels = months;
    restockDistributionChart.data.datasets[0].data = linfraData;
    restockDistributionChart.data.datasets[1].data = pktechData;
    restockDistributionChart.update();

    // Update trend chart
    allocationTrendChart.data.labels = months;
    allocationTrendChart.data.datasets[0].data = linfraData;
    allocationTrendChart.data.datasets[1].data = pktechData;
    allocationTrendChart.update();
}

// Form submission functions
function submitRestock(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/ont-restock-tracker/restocks',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Restock schedule added successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#addRestockModal').modal('hide');
                form.reset();
                loadRestockData();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function submitQuickAdd(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/ont-restock-tracker/restocks/quick',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Quick restock added successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#quickAddModal').modal('hide');
                form.reset();
                loadRestockData();
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
        url: '/company/MasterTracker/ont-restock-tracker/restocks/bulk-upload',
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
                let message = `Successfully processed ${response.data.processed} restock records`;
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
                loadRestockData();
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
function calculateTotal() {
    const linfra = parseInt($('#linfraQuantity').val()) || 0;
    const pktech = parseInt($('#pktechQuantity').val()) || 0;
    $('#totalQuantity').val(linfra + pktech);
}

function calculateQuickTotal() {
    const linfra = parseInt($('#quickLinfra').val()) || 0;
    const pktech = parseInt($('#quickPktech').val()) || 0;
    // You could add a total field here if needed
}

function getStatusClass(status) {
    switch(status) {
        case 'completed': return 'status-completed';
        case 'pending': return 'status-pending';
        case 'overdue': return 'status-overdue';
        default: return '';
    }
}

function formatNumber(value) {
    if (!value) return '0';
    return parseInt(value).toLocaleString();
}

function formatDate(dateString) {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-US');
}

function getActiveFilters() {
    return {
        month: $('#monthFilter').val(),
        company: $('#companyFilter').val(),
        status: $('#statusFilter').val(),
        year: $('#yearFilter').val()
    };
}

function applyFilters() {
    loadRestockData();
}

function resetFilters() {
    $('#monthFilter').val('all');
    $('#companyFilter').val('all');
    $('#statusFilter').val('all');
    $('#yearFilter').val('2025');
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
    loadRestockData();
    loadTimeTrackingData();
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
    window.open(`/company/MasterTracker/ont-restock-tracker/export/${format}?${params}`, '_blank');
};

window.generateSchedule = function() {
    Swal.fire({
        title: 'Generate Restock Schedule',
        html: `
            <div class="mb-3">
                <label class="form-label">Schedule Type:</label>
                <select class="form-select" id="scheduleType">
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Start Date:</label>
                <input type="date" class="form-control" id="scheduleStartDate">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Generate',
        preConfirm: () => {
            const type = document.getElementById('scheduleType').value;
            const startDate = document.getElementById('scheduleStartDate').value;
            if (!startDate) {
                Swal.showValidationMessage('Please select a start date');
                return false;
            }
            return { type, startDate };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Generate schedule logic here
            Swal.fire('Generated!', 'Restock schedule has been generated.', 'success');
        }
    });
};

window.editRestock = function(id) {
    // Edit restock logic
    console.log('Edit restock:', id);
};

window.deleteRestock = function(id) {
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
            Swal.fire('Deleted!', 'Restock has been deleted.', 'success');
        }
    });
};

window.viewDetails = function(id) {
    // View details logic
    console.log('View details:', id);
};

window.toggleView = function() {
    const activeTab = $('.nav-link.active').attr('id');
    if (activeTab === 'schedule-tab') {
        $('#timetrack-tab').click();
    } else {
        $('#schedule-tab').click();
    }
};

// Load sample data for demonstration
function loadSampleData() {
    const sampleData = [
        {
            id: 1,
            issuance_date: '2024-12-05',
            linfra_quantity: 350,
            pktech_quantity: 300,
            total_quantity: 650,
            status: 'completed'
        },
        {
            id: 2,
            issuance_date: '2025-01-21',
            linfra_quantity: 300,
            pktech_quantity: 300,
            total_quantity: 600,
            status: 'pending'
        },
        {
            id: 3,
            issuance_date: '2025-02-06',
            linfra_quantity: 400,
            pktech_quantity: 0,
            total_quantity: 400,
            status: 'pending'
        },
        {
            id: 4,
            issuance_date: '2025-02-11',
            linfra_quantity: 0,
            pktech_quantity: 250,
            total_quantity: 250,
            status: 'pending'
        }
    ];
    
    populateRestockTable(sampleData);
    updateCharts(sampleData);
}

function loadSampleTimeData() {
    const sampleTimeData = [
        { day: 'Saturday', time: '1pm', gesl: '20', linfra: '-' },
        { day: 'Sunday', time: '2pm', gesl: '23', linfra: '-' },
        { day: '', time: '11am', gesl: '-', linfra: '-' },
        { day: 'Saturday', time: '4pm', gesl: '-', linfra: '73' }
    ];
    
    populateTimeTrackingTable(sampleTimeData);
}

// Initialize sample data on load
$(document).ready(function() {
    loadSampleData();
    loadSampleTimeData();
});
</script>
@endpush
