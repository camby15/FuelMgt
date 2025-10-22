@extends('layouts.vertical', ['page_title' => 'Mailing Lists', 'mode' => session('theme_mode', 'light')])

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
    .mailing-lists-container {
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
    .card-total-lists { border-left-color: #007bff; }
    .card-total-subscribers { border-left-color: #28a745; }
    .card-active-subscribers { border-left-color: #17a2b8; }
    .card-unsubscribed { border-left-color: #ffc107; }

    /* Tabs styling */
    .nav-tabs .nav-link {
        color: #495057;
        border-color: transparent;
        font-weight: 500;
        padding: 12px 20px;
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

    /* Mailing List Cards */
    .list-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 20px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .list-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
        border-color: #007bff;
    }

    .list-header {
        padding: 15px 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-name {
        font-weight: 600;
        color: #2d3748;
        margin: 0;
        font-size: 16px;
    }

    .list-status {
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

    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .list-body {
        padding: 20px;
    }

    .list-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .list-info {
        font-size: 12px;
        color: #6c757d;
    }

    .list-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 15px;
    }

    .stat-item {
        text-align: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 2px;
    }

    .stat-label {
        font-size: 11px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .list-description {
        color: #6c757d;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .list-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
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

    .btn-view {
        background: #e9ecef;
        color: #495057;
    }

    .btn-view:hover {
        background: #dee2e6;
    }

    .btn-edit {
        background: #007bff;
        color: white;
    }

    .btn-edit:hover {
        background: #0056b3;
    }

    .btn-export {
        background: #28a745;
        color: white;
    }

    .btn-export:hover {
        background: #1e7e34;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    /* Subscriber Table */
    .subscriber-table {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
    }

    .subscriber-table table {
        margin-bottom: 0;
        font-size: 12px;
    }

    .subscriber-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        border: 1px solid #dee2e6;
        padding: 12px 8px;
        font-size: 11px;
        vertical-align: middle;
    }

    .subscriber-table td {
        border: 1px solid #dee2e6;
        padding: 10px 8px;
        font-size: 11px;
        vertical-align: middle;
    }

    /* Status indicators */
    .status-subscribed {
        background: #d4edda;
        color: #155724;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 500;
    }

    .status-unsubscribed {
        background: #f8d7da;
        color: #721c24;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 500;
    }

    .status-bounced {
        background: #fff3cd;
        color: #856404;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 500;
    }

    /* Filter Panel */
    .filter-panel {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    /* Import/Export panels */
    .import-panel {
        background: #e7f3ff;
        border: 1px solid #b3d7ff;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .export-panel {
        background: #e8f5e8;
        border: 1px solid #b3e5b3;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
    }

    /* Charts */
    .chart-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
        height: 300px;
    }

    .chart-title {
        font-size: 16px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 15px;
        text-align: center;
    }

    /* Segment builder */
    .segment-builder {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    .condition-row {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .condition-select {
        min-width: 120px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .list-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .list-actions {
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .list-stats {
            grid-template-columns: 1fr;
        }

        .card-value {
            font-size: 24px;
        }

        .condition-row {
            flex-direction: column;
            align-items: stretch;
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

    /* Drag and drop */
    .drop-zone {
        border: 2px dashed #dee2e6;
        border-radius: 6px;
        padding: 40px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .drop-zone:hover,
    .drop-zone.dragover {
        border-color: #007bff;
        background: #e7f3ff;
    }

    .drop-zone-icon {
        font-size: 48px;
        color: #6c757d;
        margin-bottom: 15px;
    }

    /* Progress indicators */
    .progress-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .progress-item:last-child {
        border-bottom: none;
    }

    .progress-bar-mini {
        width: 100px;
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: #28a745;
        border-radius: 3px;
        transition: width 0.3s ease;
    }
</style>
@endsection

@section('content')
<div class="mailing-lists-container">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Administration</a></li>
                        <li class="breadcrumb-item active">Mailing Lists</li>
                    </ol>
                </div>
                <h4 class="page-title">Mailing List Management</h4>
                <p class="text-muted mb-0">Manage email lists, subscribers, and segmentation for targeted communications</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-total-lists">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                        <i class="fas fa-list"></i>
                    </div>
                    <h6 class="card-title">Total Lists</h6>
                    <h3 class="card-value" id="totalLists">12</h3>
                    <div class="card-change">
                        <i class="fas fa-chart-line me-1"></i> Active mailing lists
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-total-subscribers">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6 class="card-title">Total Subscribers</h6>
                    <h3 class="card-value" id="totalSubscribers">5,247</h3>
                    <div class="card-change">
                        <i class="fas fa-arrow-up me-1 text-success"></i> All subscribers
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-active-subscribers">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h6 class="card-title">Active Subscribers</h6>
                    <h3 class="card-value" id="activeSubscribers">4,892</h3>
                    <div class="card-change">
                        <i class="fas fa-check-circle me-1"></i> Currently subscribed
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-unsubscribed">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <h6 class="card-title">Unsubscribed</h6>
                    <h3 class="card-value" id="unsubscribedCount">355</h3>
                    <div class="card-change">
                        <i class="fas fa-minus-circle me-1"></i> This month: 23
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createListModal">
                        <i class="fas fa-plus me-2"></i>Create List
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importSubscribersModal">
                        <i class="fas fa-upload me-2"></i>Import Subscribers
                    </button>
                    <button type="button" class="btn btn-info" onclick="exportAllLists()">
                        <i class="fas fa-download me-2"></i>Export All
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#segmentBuilderModal">
                        <i class="fas fa-filter me-2"></i>Create Segment
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>Tools
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="cleanupLists()">
                                <i class="fas fa-broom me-2"></i>Cleanup Lists
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="mergeSubscribers()">
                                <i class="fas fa-compress-alt me-2"></i>Merge Duplicates
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="validateEmails()">
                                <i class="fas fa-check-double me-2"></i>Validate Emails
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="viewAnalytics()">
                                <i class="fas fa-chart-bar me-2"></i>Analytics
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

    <!-- View Tabs -->
    <ul class="nav nav-tabs mb-4" id="mailingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="lists-tab" data-bs-toggle="tab" data-bs-target="#lists-view" type="button" role="tab">
                <i class="fas fa-list me-1"></i>Mailing Lists
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="subscribers-tab" data-bs-toggle="tab" data-bs-target="#subscribers-view" type="button" role="tab">
                <i class="fas fa-users me-1"></i>All Subscribers
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="segments-tab" data-bs-toggle="tab" data-bs-target="#segments-view" type="button" role="tab">
                <i class="fas fa-filter me-1"></i>Segments
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics-view" type="button" role="tab">
                <i class="fas fa-chart-line me-1"></i>Analytics
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="mailingTabsContent">
        <!-- Mailing Lists View -->
        <div class="tab-pane fade show active" id="lists-view" role="tabpanel">
            <!-- Filter Panel -->
            <div class="filter-panel">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="listStatusFilter" class="form-label">Status</label>
                        <select class="form-select form-select-sm" id="listStatusFilter">
                            <option value="all">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="listCategoryFilter" class="form-label">Category</label>
                        <select class="form-select form-select-sm" id="listCategoryFilter">
                            <option value="all">All Categories</option>
                            <option value="newsletter">Newsletter</option>
                            <option value="marketing">Marketing</option>
                            <option value="notifications">Notifications</option>
                            <option value="employees">Employees</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="searchLists" class="form-label">Search</label>
                        <input type="text" class="form-control form-control-sm" id="searchLists" placeholder="Search lists...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary btn-sm" onclick="applyListFilters()">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetListFilters()">
                                <i class="fas fa-times me-1"></i>Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mailing Lists Grid -->
            <div class="row" id="mailingListsGrid">
                <!-- Lists will be loaded here -->
            </div>
        </div>

        <!-- All Subscribers View -->
        <div class="tab-pane fade" id="subscribers-view" role="tabpanel">
            <!-- Subscriber Filter Panel -->
            <div class="filter-panel">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label for="subscriberStatusFilter" class="form-label">Status</label>
                        <select class="form-select form-select-sm" id="subscriberStatusFilter">
                            <option value="all">All Status</option>
                            <option value="subscribed">Subscribed</option>
                            <option value="unsubscribed">Unsubscribed</option>
                            <option value="bounced">Bounced</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="subscriberListFilter" class="form-label">List</label>
                        <select class="form-select form-select-sm" id="subscriberListFilter">
                            <option value="all">All Lists</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="dateRangeFilter" class="form-label">Date Range</label>
                        <input type="text" class="form-control form-control-sm" id="dateRangeFilter" placeholder="Select date range">
                    </div>
                    <div class="col-md-3">
                        <label for="searchSubscribers" class="form-label">Search</label>
                        <input type="text" class="form-control form-control-sm" id="searchSubscribers" placeholder="Search subscribers...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-primary btn-sm" onclick="applySubscriberFilters()">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetSubscriberFilters()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscribers Table -->
            <div class="subscriber-table">
                <div class="table-responsive">
                    <table class="table table-sm mb-0" id="subscribersTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllSubscribers"></th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Lists</th>
                                <th>Status</th>
                                <th>Subscribed Date</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="subscribersTableBody">
                            <!-- Data will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Segments View -->
        <div class="tab-pane fade" id="segments-view" role="tabpanel">
            <div class="segment-builder">
                <h5><i class="fas fa-filter me-2"></i>Smart Segments</h5>
                <p class="text-muted">Create dynamic subscriber segments based on criteria</p>
                
                <div class="row" id="segmentsGrid">
                    <!-- Segments will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Analytics View -->
        <div class="tab-pane fade" id="analytics-view" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">Subscriber Growth</div>
                        <canvas id="subscriberGrowthChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">List Performance</div>
                        <canvas id="listPerformanceChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">Subscription Sources</div>
                        <canvas id="subscriptionSourcesChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">Email Engagement</div>
                        <canvas id="emailEngagementChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create List Modal -->
<div class="modal fade" id="createListModal" tabindex="-1" aria-labelledby="createListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createListModalLabel">Create Mailing List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createListForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="list_name" id="listName" required placeholder=" ">
                                <label for="listName" class="required-field">List Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="category" id="listCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="newsletter">Newsletter</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="notifications">Notifications</option>
                                    <option value="employees">Employees</option>
                                    <option value="customers">Customers</option>
                                </select>
                                <label for="listCategory" class="required-field">Category</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="description" id="listDescription" placeholder=" " style="height: 80px"></textarea>
                                <label for="listDescription">Description</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="listStatus">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="listStatus">Status</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check" style="margin-top: 20px;">
                                <input class="form-check-input" type="checkbox" id="doubleOptIn" name="double_opt_in" checked>
                                <label class="form-check-label" for="doubleOptIn">
                                    Require double opt-in confirmation
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Default Fields</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="fieldFirstName" name="fields[]" value="first_name" checked>
                                        <label class="form-check-label" for="fieldFirstName">First Name</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="fieldLastName" name="fields[]" value="last_name" checked>
                                        <label class="form-check-label" for="fieldLastName">Last Name</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="fieldPhone" name="fields[]" value="phone">
                                        <label class="form-check-label" for="fieldPhone">Phone</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="fieldCompany" name="fields[]" value="company">
                                        <label class="form-check-label" for="fieldCompany">Company</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="fieldDepartment" name="fields[]" value="department">
                                        <label class="form-check-label" for="fieldDepartment">Department</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="fieldLocation" name="fields[]" value="location">
                                        <label class="form-check-label" for="fieldLocation">Location</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create List</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Subscribers Modal -->
<div class="modal fade" id="importSubscribersModal" tabindex="-1" aria-labelledby="importSubscribersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importSubscribersModalLabel">Import Subscribers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importSubscribersForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="import-panel">
                        <h6><i class="fas fa-info-circle me-2"></i>Import Instructions</h6>
                        <ul class="mb-0 ps-3">
                            <li>Supported formats: CSV (.csv), Excel (.xlsx)</li>
                            <li>Required column: Email</li>
                            <li>Optional columns: First Name, Last Name, Phone, Company</li>
                            <li>Maximum file size: 10MB</li>
                        </ul>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="target_list" id="targetList" required>
                                    <option value="">Select Target List</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                                <label for="targetList" class="required-field">Target List</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="import_mode" id="importMode">
                                    <option value="add">Add new subscribers</option>
                                    <option value="update">Update existing</option>
                                    <option value="replace">Replace all</option>
                                </select>
                                <label for="importMode">Import Mode</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label for="subscriberFile" class="form-label required-field">Subscriber File</label>
                            <div class="drop-zone" onclick="document.getElementById('subscriberFile').click()">
                                <div class="drop-zone-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h6>Drop your file here or click to browse</h6>
                                <p class="text-muted mb-0">CSV or Excel files up to 10MB</p>
                                <input type="file" id="subscriberFile" name="subscriber_file" accept=".csv,.xlsx" style="display: none;" required>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sendWelcomeEmail" name="send_welcome_email" checked>
                                <label class="form-check-label" for="sendWelcomeEmail">
                                    Send welcome email to new subscribers
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="skipDuplicates" name="skip_duplicates" checked>
                                <label class="form-check-label" for="skipDuplicates">
                                    Skip duplicate email addresses
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Import Subscribers</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Segment Builder Modal -->
<div class="modal fade" id="segmentBuilderModal" tabindex="-1" aria-labelledby="segmentBuilderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="segmentBuilderModalLabel">Create Smart Segment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="segmentBuilderForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="segment_name" id="segmentName" required placeholder=" ">
                                <label for="segmentName" class="required-field">Segment Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="base_list" id="baseList" required>
                                    <option value="">Select Base List</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                                <label for="baseList" class="required-field">Base List</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Segment Conditions</label>
                            <div id="conditionsContainer">
                                <div class="condition-row">
                                    <select class="form-select condition-select" name="conditions[0][field]">
                                        <option value="email">Email</option>
                                        <option value="first_name">First Name</option>
                                        <option value="last_name">Last Name</option>
                                        <option value="company">Company</option>
                                        <option value="department">Department</option>
                                        <option value="location">Location</option>
                                        <option value="subscription_date">Subscription Date</option>
                                        <option value="last_activity">Last Activity</option>
                                    </select>
                                    <select class="form-select condition-select" name="conditions[0][operator]">
                                        <option value="contains">Contains</option>
                                        <option value="equals">Equals</option>
                                        <option value="starts_with">Starts with</option>
                                        <option value="ends_with">Ends with</option>
                                        <option value="not_equals">Not equals</option>
                                        <option value="is_empty">Is empty</option>
                                        <option value="is_not_empty">Is not empty</option>
                                    </select>
                                    <input type="text" class="form-control" name="conditions[0][value]" placeholder="Value">
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="addCondition()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="description" id="segmentDescription" placeholder=" " style="height: 60px"></textarea>
                                <label for="segmentDescription">Description</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="previewSegment()">Preview</button>
                    <button type="submit" class="btn btn-primary">Create Segment</button>
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
let subscriberGrowthChart;
let listPerformanceChart;
let subscriptionSourcesChart;
let emailEngagementChart;

$(document).ready(function() {
    // Initialize data tables
    initializeSubscribersTable();
    
    // Load initial data
    loadMailingLists();
    loadSubscribers();
    loadSegments();
    initializeCharts();
    updateLastUpdatedTime();
    
    // Initialize date picker
    flatpickr("#dateRangeFilter", {
        mode: "range",
        dateFormat: "Y-m-d",
    });

    // Form submissions
    $('#createListForm').on('submit', function(e) {
        e.preventDefault();
        submitList(this);
    });

    $('#importSubscribersForm').on('submit', function(e) {
        e.preventDefault();
        importSubscribers(this);
    });

    $('#segmentBuilderForm').on('submit', function(e) {
        e.preventDefault();
        createSegment(this);
    });

    // Search functionality
    $('#searchLists').on('keyup', function() {
        searchLists($(this).val());
    });

    $('#searchSubscribers').on('keyup', function() {
        searchSubscribers($(this).val());
    });

    // File upload handling
    $('#subscriberFile').on('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'Drop your file here or click to browse';
        $('.drop-zone h6').text(fileName);
    });
});

// Data loading functions
function loadMailingLists() {
    const filters = getListFilters();
    
    $.ajax({
        url: '/company/Administration/mailing-lists/data',
        method: 'GET',
        data: filters,
        success: function(response) {
            if (response.success) {
                populateListsGrid(response.data);
                updateStats(response.stats);
                populateListSelects(response.data);
            }
        },
        error: function() {
            loadSampleLists();
        }
    });
}

function populateListsGrid(lists) {
    let gridHtml = '';
    
    lists.forEach((list, index) => {
        const statusClass = `status-${list.status}`;
        const statusText = list.status.charAt(0).toUpperCase() + list.status.slice(1);
        
        gridHtml += `
            <div class="col-md-6 col-xl-4">
                <div class="list-card" data-list-id="${list.id}">
                    <div class="list-header">
                        <h6 class="list-name">${list.name}</h6>
                        <span class="list-status ${statusClass}">${statusText}</span>
                    </div>
                    <div class="list-body">
                        <div class="list-meta">
                            <div class="list-info">
                                <div>Category: ${list.category}</div>
                                <div>Created: ${formatDate(list.created_at)}</div>
                            </div>
                        </div>
                        <div class="list-stats">
                            <div class="stat-item">
                                <div class="stat-value">${list.subscriber_count || 0}</div>
                                <div class="stat-label">Subscribers</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">${list.active_count || 0}</div>
                                <div class="stat-label">Active</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">${list.unsubscribed_count || 0}</div>
                                <div class="stat-label">Unsubscribed</div>
                            </div>
                        </div>
                        <div class="list-description">
                            ${list.description || 'No description available'}
                        </div>
                        <div class="list-actions">
                            <button class="action-btn btn-view" onclick="viewList(${list.id})">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="action-btn btn-edit" onclick="editList(${list.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="action-btn btn-export" onclick="exportList(${list.id})">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteList(${list.id})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#mailingListsGrid').html(gridHtml);
}

function populateListSelects(lists) {
    let optionsHtml = '<option value="">Select List</option>';
    
    lists.forEach(list => {
        optionsHtml += `<option value="${list.id}">${list.name} (${list.subscriber_count || 0} subscribers)</option>`;
    });
    
    $('#targetList, #baseList, #subscriberListFilter').html(optionsHtml);
}

function initializeSubscribersTable() {
    $('#subscribersTable').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        pageLength: 25,
        order: [[5, 'desc']], // Order by subscribed date
        columnDefs: [
            { orderable: false, targets: [0, 7] } // Disable ordering for checkbox and actions
        ]
    });
}

function loadSubscribers() {
    const filters = getSubscriberFilters();
    
    $.ajax({
        url: '/company/Administration/mailing-lists/subscribers',
        method: 'GET',
        data: filters,
        success: function(response) {
            if (response.success) {
                populateSubscribersTable(response.data);
            }
        },
        error: function() {
            loadSampleSubscribers();
        }
    });
}

function populateSubscribersTable(subscribers) {
    let tableHtml = '';
    
    subscribers.forEach((subscriber, index) => {
        const statusClass = `status-${subscriber.status}`;
        
        tableHtml += `
            <tr>
                <td><input type="checkbox" class="subscriber-checkbox" value="${subscriber.id}"></td>
                <td>${subscriber.email}</td>
                <td>${subscriber.first_name || ''} ${subscriber.last_name || ''}</td>
                <td>${subscriber.lists ? subscriber.lists.join(', ') : '-'}</td>
                <td><span class="${statusClass}">${subscriber.status}</span></td>
                <td>${formatDate(subscriber.subscribed_at)}</td>
                <td>${formatDate(subscriber.last_activity) || 'Never'}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary btn-sm" onclick="viewSubscriber(${subscriber.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="editSubscriber(${subscriber.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="removeSubscriber(${subscriber.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    $('#subscribersTableBody').html(tableHtml);
}

function updateStats(stats) {
    $('#totalLists').text(stats?.total_lists || '12');
    $('#totalSubscribers').text(formatNumber(stats?.total_subscribers || 5247));
    $('#activeSubscribers').text(formatNumber(stats?.active_subscribers || 4892));
    $('#unsubscribedCount').text(formatNumber(stats?.unsubscribed || 355));
}

// Chart functions
function initializeCharts() {
    const ctx1 = document.getElementById('subscriberGrowthChart').getContext('2d');
    const ctx2 = document.getElementById('listPerformanceChart').getContext('2d');
    const ctx3 = document.getElementById('subscriptionSourcesChart').getContext('2d');
    const ctx4 = document.getElementById('emailEngagementChart').getContext('2d');

    subscriberGrowthChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'New Subscribers',
                data: [120, 190, 300, 500, 200, 300, 450],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }, {
                label: 'Unsubscribed',
                data: [20, 30, 25, 45, 35, 40, 30],
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
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

    listPerformanceChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Newsletter', 'Marketing', 'Notifications', 'Employees'],
            datasets: [{
                label: 'Active Subscribers',
                data: [1200, 800, 650, 400],
                backgroundColor: ['#007bff', '#28a745', '#17a2b8', '#ffc107'],
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
            }
        }
    });

    subscriptionSourcesChart = new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: ['Website', 'Social Media', 'Email Campaigns', 'Referrals', 'Direct'],
            datasets: [{
                data: [40, 25, 15, 12, 8],
                backgroundColor: ['#007bff', '#28a745', '#17a2b8', '#ffc107', '#6f42c1'],
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

    emailEngagementChart = new Chart(ctx4, {
        type: 'radar',
        data: {
            labels: ['Open Rate', 'Click Rate', 'Conversion', 'Forward Rate', 'Unsubscribe'],
            datasets: [{
                label: 'Current Performance',
                data: [85, 65, 45, 25, 5],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                pointBackgroundColor: '#007bff',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#007bff'
            }, {
                label: 'Industry Average',
                data: [70, 50, 35, 20, 8],
                borderColor: '#6c757d',
                backgroundColor: 'rgba(108, 117, 125, 0.2)',
                pointBackgroundColor: '#6c757d',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#6c757d'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

// Form submission functions
function submitList(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/Administration/mailing-lists',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Mailing list created successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#createListModal').modal('hide');
                form.reset();
                loadMailingLists();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function importSubscribers(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/Administration/mailing-lists/import',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Import Successful!',
                    text: `Imported ${response.data.imported} subscriber(s) successfully.`,
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#importSubscribersModal').modal('hide');
                form.reset();
                loadMailingLists();
                loadSubscribers();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function createSegment(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/Administration/mailing-lists/segments',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Segment Created!',
                    text: 'Smart segment created successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#segmentBuilderModal').modal('hide');
                form.reset();
                loadSegments();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

// Utility functions
function getListFilters() {
    return {
        status: $('#listStatusFilter').val(),
        category: $('#listCategoryFilter').val(),
        search: $('#searchLists').val()
    };
}

function getSubscriberFilters() {
    return {
        status: $('#subscriberStatusFilter').val(),
        list: $('#subscriberListFilter').val(),
        date_range: $('#dateRangeFilter').val(),
        search: $('#searchSubscribers').val()
    };
}

function applyListFilters() {
    loadMailingLists();
}

function resetListFilters() {
    $('#listStatusFilter').val('all');
    $('#listCategoryFilter').val('all');
    $('#searchLists').val('');
    loadMailingLists();
}

function applySubscriberFilters() {
    loadSubscribers();
}

function resetSubscriberFilters() {
    $('#subscriberStatusFilter').val('all');
    $('#subscriberListFilter').val('all');
    $('#dateRangeFilter').val('');
    $('#searchSubscribers').val('');
    loadSubscribers();
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function formatNumber(value) {
    return parseInt(value).toLocaleString();
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

// List management functions
function viewList(listId) {
    window.location.href = `/company/Administration/mailing-lists/${listId}`;
}

function editList(listId) {
    // Load list data and populate edit form
    Swal.fire('Info', 'Edit functionality will be implemented.', 'info');
}

function exportList(listId) {
    window.open(`/company/Administration/mailing-lists/${listId}/export`, '_blank');
}

function deleteList(listId) {
    Swal.fire({
        title: 'Delete Mailing List?',
        text: "This will also remove all subscribers from this list.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/company/Administration/mailing-lists/${listId}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Deleted!', 'Mailing list has been deleted.', 'success');
                        loadMailingLists();
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to delete mailing list.', 'error');
                }
            });
        }
    });
}

// Segment builder functions
function addCondition() {
    const container = $('#conditionsContainer');
    const conditionCount = container.children().length;
    
    const newCondition = `
        <div class="condition-row">
            <select class="form-select condition-select" name="conditions[${conditionCount}][field]">
                <option value="email">Email</option>
                <option value="first_name">First Name</option>
                <option value="last_name">Last Name</option>
                <option value="company">Company</option>
                <option value="department">Department</option>
                <option value="location">Location</option>
                <option value="subscription_date">Subscription Date</option>
                <option value="last_activity">Last Activity</option>
            </select>
            <select class="form-select condition-select" name="conditions[${conditionCount}][operator]">
                <option value="contains">Contains</option>
                <option value="equals">Equals</option>
                <option value="starts_with">Starts with</option>
                <option value="ends_with">Ends with</option>
                <option value="not_equals">Not equals</option>
                <option value="is_empty">Is empty</option>
                <option value="is_not_empty">Is not empty</option>
            </select>
            <input type="text" class="form-control" name="conditions[${conditionCount}][value]" placeholder="Value">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCondition(this)">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    
    container.append(newCondition);
}

function removeCondition(button) {
    $(button).closest('.condition-row').remove();
}

function previewSegment() {
    const formData = new FormData($('#segmentBuilderForm')[0]);
    
    $.ajax({
        url: '/company/Administration/mailing-lists/segments/preview',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Segment Preview',
                    html: `
                        <div style="text-align: left;">
                            <h6>Estimated Results: ${response.data.count} subscribers</h6>
                            <hr>
                            <div style="max-height: 300px; overflow-y: auto;">
                                ${response.data.preview.map(sub => `<div>${sub.email} - ${sub.first_name} ${sub.last_name}</div>`).join('')}
                            </div>
                        </div>
                    `,
                    width: 600,
                    showCloseButton: true
                });
            }
        },
        error: function() {
            Swal.fire('Error', 'Failed to preview segment.', 'error');
        }
    });
}

// Global functions
window.exportAllLists = function() {
    window.open('/company/Administration/mailing-lists/export-all', '_blank');
};

window.cleanupLists = function() {
    Swal.fire({
        title: 'Cleanup Mailing Lists',
        text: "This will remove invalid email addresses and duplicates.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Start Cleanup'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Processing!', 'Cleanup process started. You will be notified when complete.', 'success');
        }
    });
};

window.mergeSubscribers = function() {
    Swal.fire({
        title: 'Merge Duplicate Subscribers',
        text: "This will merge subscribers with duplicate email addresses.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Start Merge'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Processing!', 'Merge process started.', 'success');
        }
    });
};

window.validateEmails = function() {
    Swal.fire({
        title: 'Validate Email Addresses',
        text: "This will check all email addresses for validity.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Start Validation'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Processing!', 'Email validation started.', 'success');
        }
    });
};

window.viewAnalytics = function() {
    $('#analytics-tab').click();
};

function searchLists(query) {
    loadMailingLists();
}

function searchSubscribers(query) {
    loadSubscribers();
}

function loadSegments() {
    // Load segments implementation
}

// Load sample data
function loadSampleLists() {
    const sampleLists = [
        {
            id: 1,
            name: 'Newsletter Subscribers',
            category: 'newsletter',
            status: 'active',
            description: 'Monthly newsletter subscribers',
            subscriber_count: 1247,
            active_count: 1180,
            unsubscribed_count: 67,
            created_at: '2024-01-15'
        },
        {
            id: 2,
            name: 'Marketing Campaigns',
            category: 'marketing',
            status: 'active',
            description: 'Marketing and promotional emails',
            subscriber_count: 856,
            active_count: 798,
            unsubscribed_count: 58,
            created_at: '2024-01-10'
        },
        {
            id: 3,
            name: 'Employee Updates',
            category: 'employees',
            status: 'active',
            description: 'Internal employee communications',
            subscriber_count: 342,
            active_count: 340,
            unsubscribed_count: 2,
            created_at: '2024-01-20'
        }
    ];
    
    populateListsGrid(sampleLists);
    populateListSelects(sampleLists);
    updateStats({
        total_lists: 12,
        total_subscribers: 5247,
        active_subscribers: 4892,
        unsubscribed: 355
    });
}

function loadSampleSubscribers() {
    const sampleSubscribers = [
        {
            id: 1,
            email: 'john.doe@example.com',
            first_name: 'John',
            last_name: 'Doe',
            lists: ['Newsletter', 'Marketing'],
            status: 'subscribed',
            subscribed_at: '2024-01-15',
            last_activity: '2024-02-10'
        },
        {
            id: 2,
            email: 'jane.smith@example.com',
            first_name: 'Jane',
            last_name: 'Smith',
            lists: ['Newsletter'],
            status: 'subscribed',
            subscribed_at: '2024-01-20',
            last_activity: '2024-02-08'
        }
    ];
    
    populateSubscribersTable(sampleSubscribers);
}

// Initialize sample data on load
$(document).ready(function() {
    loadSampleLists();
    loadSampleSubscribers();
});
</script>
@endpush
