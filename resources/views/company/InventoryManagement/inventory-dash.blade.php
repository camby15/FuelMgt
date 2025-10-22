@extends('layouts.vertical', ['page_title' => 'Comprehensive Warehouse Analytics'])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        --info-gradient: linear-gradient(135deg, #0093E9 0%, #80D0C7 100%);
        --warning-gradient: linear-gradient(135deg, #FFB75E 0%, #ED8F03 100%);
        --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    }
    
    body {
        background: #f5f7fa;
    }
    
    .dashboard-header {
        background: var(--primary-gradient);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .stat-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .stat-card .card-body {
        padding: 1.5rem;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .chart-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    }
    
    .chart-card .card-header {
        background: white;
        border-bottom: 2px solid #f0f0f0;
        padding: 1.5rem;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        padding-left: 1rem;
        border-left: 4px solid #667eea;
    }
    
    .progress-thin {
        height: 8px;
        border-radius: 10px;
    }
    
    .activity-timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
    }
    
    .activity-item {
        position: relative;
        padding: 15px 0;
    }
    
    .activity-dot {
        position: absolute;
        left: -26px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fc;
        cursor: pointer;
    }
    
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4" style="padding-bottom: 120px; margin-bottom: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Analytic Dashboard</h1>
        <div class="d-flex align-items-center gap-3">
            <!-- Date Range Filter -->
            <div class="d-flex align-items-center gap-2">
                <label for="startDate" class="form-label mb-0 fw-bold">From:</label>
                <input type="date" class="form-control form-control-sm" id="startDate" style="width: 140px;">
                <label for="endDate" class="form-label mb-0 fw-bold">To:</label>
                <input type="date" class="form-control form-control-sm" id="endDate" style="width: 140px;">
                <button class="btn btn-outline-primary btn-sm" id="applyFiltersBtn">
                    <i class="fas fa-filter me-1"></i>Apply Filters
                </button>
                <button class="btn btn-outline-secondary btn-sm" id="resetFiltersBtn">
                    <i class="fas fa-undo me-1"></i>Reset
                </button>
            </div>
            <button class="btn btn-primary" id="generateReportBtn">
                <i class="fas fa-download me-2"></i>Generate Report
            </button>
        </div>
    </div>

    <!-- Key Performance Indicators - Row 1 -->
    <h5 class="section-title"><i class="fas fa-tachometer-alt me-2"></i>Key Performance Indicators</h5>
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Total Inventory Items</p>
                            <h2 class="stat-number text-primary" id="totalInventoryItems">0</h2>
                            <small class="text-success"><i class="fas fa-arrow-up"></i> <span id="inventoryGrowth">0%</span> this month</small>
                        </div>
                        <div class="stat-icon" style="background: var(--primary-gradient);">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Total Inventory Value</p>
                            <h2 class="stat-number text-success" id="totalInventoryValue">GHS 0</h2>
                            <small class="text-muted"><span id="inventoryValueChange">0%</span> from last month</small>
                        </div>
                        <div class="stat-icon" style="background: var(--success-gradient);">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Active Purchase Orders</p>
                            <h2 class="stat-number text-info" id="activePurchaseOrders">0</h2>
                            <small class="text-muted"><span id="pendingPOs">0</span> pending</small>
                        </div>
                        <div class="stat-icon" style="background: var(--info-gradient);">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Low Stock Alerts</p>
                            <h2 class="stat-number text-warning" id="lowStockAlerts">0</h2>
                            <small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Need attention</small>
                        </div>
                        <div class="stat-icon" style="background: var(--warning-gradient);">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Procurement & Requisition Stats - Row 2 -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Pending Requisitions</p>
                            <h2 class="stat-number" style="color: #f6c23e;" id="pendingRequisitions">0</h2>
                            <small><span id="newRequisitionsToday">0</span> new today</small>
                        </div>
                        <div class="icon-circle bg-warning text-white">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Approved Requisitions</p>
                            <h2 class="stat-number text-success" id="approvedRequisitions">0</h2>
                            <small class="text-muted">This month</small>
                        </div>
                        <div class="icon-circle bg-success text-white">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Total Suppliers</p>
                            <h2 class="stat-number text-info" id="totalSuppliers">0</h2>
                            <small><span id="activeSuppliers">0</span> active</small>
                        </div>
                        <div class="icon-circle bg-info text-white">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Re-order POs</p>
                            <h2 class="stat-number" style="color: #e74a3b;" id="reorderPOs">0</h2>
                            <small class="text-muted"><span id="autoReorderPOs">0</span> auto-generated</small>
                        </div>
                        <div class="icon-circle bg-danger text-white">
                            <i class="fas fa-redo"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Warehouse Operations Stats - Row 3 -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Items Received</p>
                            <h2 class="stat-number text-primary" id="itemsReceived">0</h2>
                            <small class="text-muted">This month</small>
                        </div>
                        <div class="icon-circle bg-primary text-white">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Items Issued</p>
                            <h2 class="stat-number text-success" id="itemsIssued">0</h2>
                            <small class="text-muted">This month</small>
                        </div>
                        <div class="icon-circle bg-success text-white">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Quality Inspections</p>
                            <h2 class="stat-number text-info" id="qualityInspections">0</h2>
                            <small class="text-success"><span id="inspectionsPassed">0</span> passed</small>
                        </div>
                        <div class="icon-circle bg-info text-white">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-label mb-2">Total Batches</p>
                            <h2 class="stat-number" style="color: #f6c23e;" id="totalBatches">0</h2>
                            <small class="text-muted"><span id="activeBatches">0</span> active</small>
                        </div>
                        <div class="icon-circle bg-warning text-white">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <h5 class="section-title"><i class="fas fa-chart-area me-2"></i>Analytics & Trends</h5>
    <div class="row g-3 mb-3">
        <!-- Inventory Trend Chart -->
        <div class="col-xl-8">
            <div class="card chart-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i>Inventory Value Trend</h6>
                        <select class="form-select form-select-sm" style="width: 150px;" id="inventoryPeriod">
                            <option value="7">Last 7 Days</option>
                            <option value="30" selected>Last 30 Days</option>
                            <option value="90">Last 90 Days</option>
                            <option value="365">Last Year</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="inventoryTrendChart" height="220"></canvas>
                </div>
            </div>
        </div>       
        <!-- Category Distribution -->
        <div class="col-xl-4">
            <div class="card chart-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-success"></i>Stock by Category</h6>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- More Charts Row -->
    <div class="row g-3 mb-4">
        <!-- PO Status Chart -->
        <div class="col-xl-4">
            <div class="card chart-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice me-2 text-info"></i>Purchase Orders by Status</h6>
                </div>
                <div class="card-body">
                    <canvas id="poStatusChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Stock Movement Chart -->
        <div class="col-xl-8">
            <div class="card chart-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-exchange-alt me-2 text-warning"></i>Stock Movement (In vs Out)</h6>
                </div>
                <div class="card-body">
                    <canvas id="stockMovementChart" height="230"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Performance -->
    <h5 class="section-title"><i class="fas fa-users me-2"></i>Team Performance & Activity</h5>
    <div class="row g-3 mb-4">
        <!-- Top Performers -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-trophy me-2 text-warning"></i>Top Performing Team Members</h6>
                </div>
                <div class="card-body">
                    <div id="topPerformers">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                            <p class="mt-2 text-muted">Loading team performance...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-clock me-2 text-info"></i>Recent Activities</h6>
                </div>
                <div class="card-body">
                    <div class="activity-timeline" id="recentActivities">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                            <p class="mt-2 text-muted">Loading activities...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Tables Section -->
    <h5 class="section-title"><i class="fas fa-table me-2"></i>Detailed Reports</h5>
    
    <!-- Tabs for Different Reports -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#inventoryReport">
                        <i class="fas fa-warehouse me-2"></i>Inventory Report
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#procurementReport">
                        <i class="fas fa-shopping-cart me-2"></i>Procurement Report
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#requisitionReport">
                        <i class="fas fa-clipboard-list me-2"></i>Requisition Report
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#batchReport">
                        <i class="fas fa-layer-group me-2"></i>Batch Tracking
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#supplierReport">
                        <i class="fas fa-building me-2"></i>Supplier Performance
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#financialReport">
                        <i class="fas fa-chart-line me-2"></i>Financial Analysis
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Inventory Report Tab -->
                <div class="tab-pane fade show active" id="inventoryReport">
                    <div class="table-responsive">
                        <table class="table table-hover" id="inventoryReportTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Batch Number</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Value</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="inventoryReportBody">
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="spinner-border text-primary"></div>
                                        <p class="mt-2 text-muted">Loading inventory data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Procurement Report Tab -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Procurement Report</h6>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade" id="procurementReport">
                    <div class="table-responsive">
                        <table class="table table-hover" id="procurementReportTable">
                            <thead class="table-light">
                                <tr>
                                    <th>PO Number</th>
                                    <th>Supplier</th>
                                    <th>Order Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Re-order</th>
                                    <th>Created By</th>
                                </tr>
                            </thead>
                            <tbody id="procurementReportBody">
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="spinner-border text-primary"></div>
                                        <p class="mt-2 text-muted">Loading procurement data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requisition Report Tab -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Requisition Report</h6>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade" id="requisitionReport">
                    <div class="table-responsive">
                        <table class="table table-hover" id="requisitionReportTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Requisition #</th>
                                    <th>Requested By</th>
                                    <th>Department</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody id="requisitionReportBody">
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="spinner-border text-primary"></div>
                                        <p class="mt-2 text-muted">Loading requisition data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Tracking Tab -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Batch Tracking</h6>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade" id="batchReport">
                    <div class="table-responsive">
                        <table class="table table-hover" id="batchReportTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Batch Number</th>
                                    <th>Item Name</th>
                                    <th>Original Quantity</th>
                                    <th>Current Quantity</th>
                                    <th>Re-orders</th>
                                    <th>Total Added</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody id="batchReportBody">
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="spinner-border text-primary"></div>
                                        <p class="mt-2 text-muted">Loading batch data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplier Performance Tab -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Supplier Performance</h6>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade" id="supplierReport">
                    <div class="table-responsive">
                        <table class="table table-hover" id="supplierReportTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Total POs</th>
                                    <th>Total Spend</th>
                                    <th>On-Time Delivery</th>
                                    <th>Quality Score</th>
                                    <th>Last Order</th>
                                </tr>
                            </thead>
                            <tbody id="supplierReportBody">
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="spinner-border text-primary"></div>
                                        <p class="mt-2 text-muted">Loading supplier data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Analysis Tab -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Financial Analysis</h6>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade" id="financialReport">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                <div class="card-body">
                                    <h6 class="text-muted">Total Procurement Spend</h6>
                                    <h3 class="text-primary" id="totalProcurementSpend">GHS 0</h3>
                                </div>
                        </div>
                    </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="text-muted">Average PO Value</h6>
                                    <h3 class="text-success" id="avgPOValue">GHS 0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="text-muted">Total Tax Paid</h6>
                                    <h3 class="text-info" id="totalTaxPaid">GHS 0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="text-muted">Pending Payments</h6>
                                    <h3 class="text-warning" id="pendingPayments">GHS 0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fw-bold">Monthly Procurement Spending</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="financialChart" height="80"></canvas>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Low Stock & Critical Items -->
    <h5 class="section-title"><i class="fas fa-exclamation-triangle me-2"></i>Critical Alerts & Monitoring</h5>
    <div class="row g-3 mb-4">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Low Stock Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Current</th>
                                    <th>Min Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="lowStockTable">
                                <tr>
                                    <td colspan="4" class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <h6 class="mb-0"><i class="fas fa-hourglass-half me-2"></i>Pending Approvals</h6>
                </div>
                <div class="card-body">
                    <div class="list-group" id="pendingApprovalsList">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

    <!-- Re-order Analysis -->
    <h5 class="section-title"><i class="fas fa-redo me-2"></i>Re-order Analysis & Tracking</h5>
    <div class="row g-3 mb-4">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-sync-alt me-2 text-primary"></i>Re-order Purchase Orders</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>PO Number</th>
                                    <th>Supplier</th>
                                    <th>Items</th>
                                    <th>Batch Numbers</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="reorderPOTable">
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="spinner-border text-primary"></div>
                                        <p class="mt-2 text-muted">Loading re-order data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Warehouse Operations Summary -->
    <h5 class="section-title"><i class="fas fa-tasks me-2"></i>Warehouse Operations Summary</h5>
    <div class="row g-3 mb-4">
        <!-- Receiving Summary -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header" style="background: var(--primary-gradient); color: white;">
                    <h6 class="mb-0"><i class="fas fa-truck-loading me-2"></i>Receiving Operations</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Today</span>
                            <strong id="receivedToday">0 items</strong>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-primary" id="receivedTodayProgress" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>This Week</span>
                            <strong id="receivedWeek">0 items</strong>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-primary" id="receivedWeekProgress" style="width: 0%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>This Month</span>
                            <strong id="receivedMonth">0 items</strong>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-primary" id="receivedMonthProgress" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Issuing Summary -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header" style="background: var(--success-gradient); color: white;">
                    <h6 class="mb-0"><i class="fas fa-truck me-2"></i>Issuing Operations</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Today</span>
                            <strong id="issuedToday">0 items</strong>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-success" id="issuedTodayProgress" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>This Week</span>
                            <strong id="issuedWeek">0 items</strong>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-success" id="issuedWeekProgress" style="width: 0%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>This Month</span>
                            <strong id="issuedMonth">0 items</strong>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-success" id="issuedMonthProgress" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inspection Summary -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header" style="background: var(--info-gradient); color: white;">
                    <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Quality Inspections</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Passed</span>
                            <strong id="inspectionsPassed">0</strong>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-success" id="inspectionsPassedProgress" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Failed</span>
                            <strong id="inspectionsFailed">0</strong>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-danger" id="inspectionsFailedProgress" style="width: 0%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Pending</span>
                            <strong id="inspectionsPending">0</strong>
                        </div>
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-warning" id="inspectionsPendingProgress" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-download me-2"></i>Export & Download Options</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <button class="btn btn-danger w-100" onclick="generateReport('pdf')">
                                <i class="fas fa-file-pdf me-2"></i>Download PDF Report
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success w-100" onclick="generateReport('excel')">
                                <i class="fas fa-file-excel me-2"></i>Download Excel Report
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-info w-100" onclick="generateReport('csv')">
                                <i class="fas fa-file-csv me-2"></i>Download CSV Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    console.log('Comprehensive Warehouse Analytics loading...');

    // Set default date range to current month
    setDefaultDateRange();

    // Load all data
    loadComprehensiveAnalytics();

    // Initialize all charts
    setTimeout(() => {
        initializeAllCharts();
    }, 1000);

    // Bind filter events
    $('#applyFiltersBtn').on('click', function() {
        applyDateFilters();
    });

    $('#resetFiltersBtn').on('click', function() {
        resetDateFilters();
    });

    // Allow Enter key to apply filters
    $('#startDate, #endDate').on('keypress', function(e) {
        if (e.which === 13) {
            applyDateFilters();
        }
    });
});

function loadComprehensiveAnalytics(startDate = null, endDate = null) {
    console.log('Loading comprehensive analytics...', { startDate, endDate });

    // Show loading status
    Swal.fire({
        title: 'Loading Comprehensive Analytics...',
        text: 'Please wait while we load all warehouse data',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Prepare data with optional date filters
    const requestData = {};
    if (startDate && endDate) {
        requestData.start_date = startDate;
        requestData.end_date = endDate;
    }

    // Load inventory analytics data
    $.ajax({
        url: '/company/warehouse/reports/inventory-analytics',
        method: 'POST',
        data: requestData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                updateDashboardCards(response.data);
                updateRecentActivities(response.data.recent_activities);
                updateLowStockTable(response.data.low_stock_items);

                // Load detailed reports for each tab
                loadInventoryReport();
                loadProcurementReport();
                loadRequisitionReport();
                loadBatchReport();
                loadSupplierReport();
                loadFinancialReport();
                loadReorderPOTable();

                Swal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Analytics Loaded!',
                    text: 'Comprehensive warehouse analytics are now available',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Failed to load analytics data'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading analytics:', {
                xhr: xhr,
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status,
                readyState: xhr.readyState
            });

            // Try to parse error response
            let errorMessage = 'Failed to load analytics data. Please try again.';
            let errorDetails = '';

            try {
                if (xhr.responseText) {
                    const response = JSON.parse(xhr.responseText);
                    console.error('Parsed error response:', response);
                    errorMessage = response.message || errorMessage;
                    errorDetails = response.error || '';
                }
            } catch (e) {
                console.error('Could not parse error response:', e);
                errorDetails = xhr.responseText || '';
            }

            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error Loading Analytics',
                html: `<strong>${errorMessage}</strong><br><small style="color: #666;">Status: ${xhr.status} ${status}</small><br><small style="color: #999;">${errorDetails}</small>`,
                footer: 'Check browser console for detailed error information'
            });
        }
    });
}

function initializeAllCharts() {
    // Initialize Inventory Trend Chart with dynamic data
    initInventoryTrendChart();
    // Initialize Category Distribution Chart
    initCategoryChart();
    // Initialize PO Status Chart
    initPOStatusChart();
    // Initialize Stock Movement Chart
    initStockMovementChart();
    // Initialize Financial Chart
    initFinancialChart();
}

function initInventoryTrendChart() {
    const ctx = document.getElementById('inventoryTrendChart');
    if (!ctx) return;

    // Get current date range from filters
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    // Load dynamic chart data
    $.ajax({
        url: '{{ route("warehouse.reports.chart_data") }}',
        method: 'POST',
        data: {
            period: $('#inventoryPeriod').val() || 'month',
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success && response.data) {
                const chartData = response.data;

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Inventory Value (GHS)',
                            data: chartData.inventory_values || [120000, 150000, 180000, 200000, 220000, 250000, 280000, 300000, 320000, 350000, 380000, 400000],
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0,0,0,0.1)'
                                }
                            }
                        }
                    }
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading chart data:', xhr);
            // Fallback to static data
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Inventory Value (GHS)',
                        data: [120000, 150000, 180000, 200000, 220000, 250000, 280000, 300000, 320000, 350000, 380000, 400000],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        }
                    }
                }
            });
        }
    });
}

function initCategoryChart() {
    const ctx = document.getElementById('categoryChart');
    if (!ctx) return;

    // Get current date range from filters
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    // Load dynamic category data
    $.ajax({
        url: '{{ route("warehouse.reports.inventory_analytics") }}',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success && response.data) {
                // Calculate category distribution from inventory data
                const categoryData = {};
                // This would need to be implemented in the controller to return category breakdown
                // For now, use static data as fallback
                const labels = ['Electronics', 'Office Supplies', 'Raw Materials', 'Tools', 'Safety Equipment'];
                const data = [35, 25, 20, 15, 5];

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                '#667eea',
                                '#0ba360',
                                '#0093E9',
                                '#FFB75E',
                                '#eb3349'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading category data:', xhr);
            // Fallback to static data
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Electronics', 'Office Supplies', 'Raw Materials', 'Tools', 'Safety Equipment'],
                    datasets: [{
                        data: [35, 25, 20, 15, 5],
                        backgroundColor: [
                            '#667eea',
                            '#0ba360',
                            '#0093E9',
                            '#FFB75E',
                            '#eb3349'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
}

function initPOStatusChart() {
    const ctx = document.getElementById('poStatusChart');
    if (!ctx) return;

    // Get current date range from filters
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    // Load dynamic PO status data
    $.ajax({
        url: '{{ route("warehouse.reports.procurement_details") }}',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success && response.data) {
                // Calculate status distribution
                const statusCounts = {};
                response.data.forEach(po => {
                    const status = po.status || 'Unknown';
                    statusCounts[status] = (statusCounts[status] || 0) + 1;
                });

                const labels = Object.keys(statusCounts);
                const data = Object.values(statusCounts);

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading PO status data:', xhr);
            // Fallback to static data
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Created', 'Approved', 'Pending', 'Completed', 'Cancelled'],
                    datasets: [{
                        data: [15, 25, 10, 8, 2],
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
}

function initStockMovementChart() {
    const ctx = document.getElementById('stockMovementChart');
    if (!ctx) return;

    // Get current date range from filters
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    // Load dynamic stock movement data
    $.ajax({
        url: '{{ route("warehouse.reports.inventory_analytics") }}',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success && response.data) {
                // Use warehouse operations data for stock movement
                const ops = response.data.warehouse_operations || {};
                const receiving = ops.receiving || {};
                const issuing = ops.issuing || {};

                // Create monthly data for the filtered period
                const labels = ['Current Period'];
                const itemsIn = [receiving.month || 0];
                const itemsOut = [issuing.month || 0];

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Items Received',
                            data: itemsIn,
                            backgroundColor: 'rgba(0, 186, 96, 0.8)'
                        }, {
                            label: 'Items Issued',
                            data: itemsOut,
                            backgroundColor: 'rgba(255, 99, 132, 0.8)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading stock movement data:', xhr);
            // Fallback to static data
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Items Received',
                        data: [120, 150, 180, 200, 220, 250],
                        backgroundColor: 'rgba(0, 186, 96, 0.8)'
                    }, {
                        label: 'Items Issued',
                        data: [100, 130, 160, 180, 200, 230],
                        backgroundColor: 'rgba(255, 99, 132, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
}

function initFinancialChart() {
    const ctx = document.getElementById('financialChart');
    if (!ctx) return;

    // Get current date range from filters
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    // Load dynamic financial data
    $.ajax({
        url: '{{ route("warehouse.reports.financial_details") }}',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success && response.data) {
                const data = response.data;
                // Create chart with current period data
                const labels = ['Current Period'];
                const spendData = [parseFloat((data.total_spend || '0').replace(/,/g, ''))];

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Procurement Spend (GHS)',
                            data: spendData,
                            backgroundColor: 'rgba(102, 126, 234, 0.8)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading financial data:', xhr);
            // Fallback to static data
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Procurement Spend (GHS)',
                        data: [50000, 75000, 60000, 80000, 90000, 100000],
                        backgroundColor: 'rgba(102, 126, 234, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
}
    
function generateReport(format) {
    Swal.fire({
        title: 'Generating Report...',
        text: `Creating ${format.toUpperCase()} report`,
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Get current filter dates
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    // Call the backend report generation with filters
    $.ajax({
        url: '/company/warehouse/reports/generate-report',
        method: 'POST',
        data: {
            type: 'inventory',
            format: format,
            start_date: startDate,
            end_date: endDate
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Report Generated!',
                    text: `Your ${format.toUpperCase()} report is ready`,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Failed to generate report'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to generate report. Please try again.'
            });
        }
    });
}

// Helper functions to update dashboard elements

function updateDashboardCards(data) {
    $('#totalInventoryItems').text(data.total_inventory || 0);
    $('#totalInventoryValue').text('GHS ' + (data.total_inventory_value || 0).toLocaleString());
    $('#activePurchaseOrders').text(data.active_pos || 0);
    $('#lowStockAlerts').text(data.low_stock_count || 0);
    $('#pendingRequisitions').text(data.pending_requisitions || 0);
    $('#approvedRequisitions').text(data.approved_requisitions || 0);
    $('#totalSuppliers').text(data.total_suppliers || 0);
    $('#reorderPOs').text(data.reorder_pos || 0);
    $('#itemsReceived').text(data.items_received || 0);
    $('#itemsIssued').text(data.items_issued || 0);
    $('#qualityInspections').text(data.quality_inspections || 0);
    $('#totalBatches').text(data.total_batches || 0);

    // Update growth percentages
    $('#inventoryGrowth').text((data.inventory_change_percent || 0) + '%');
    $('#inventoryValueChange').text((data.inventory_change_percent || 0) + '%');

    // Update warehouse operations summary
    if (data.warehouse_operations) {
        // Receiving Operations
        $('#receivedToday').text((data.warehouse_operations.receiving?.today || 0) + ' items');
        $('#receivedWeek').text((data.warehouse_operations.receiving?.week || 0) + ' items');
        $('#receivedMonth').text((data.warehouse_operations.receiving?.month || 0) + ' items');

        // Update progress bars for receiving
        updateProgressBar('#receivedTodayProgress', data.warehouse_operations.receiving?.today || 0, 100);
        updateProgressBar('#receivedWeekProgress', data.warehouse_operations.receiving?.week || 0, 500);
        updateProgressBar('#receivedMonthProgress', data.warehouse_operations.receiving?.month || 0, 2000);

        // Issuing Operations
        $('#issuedToday').text((data.warehouse_operations.issuing?.today || 0) + ' items');
        $('#issuedWeek').text((data.warehouse_operations.issuing?.week || 0) + ' items');
        $('#issuedMonth').text((data.warehouse_operations.issuing?.month || 0) + ' items');

        // Update progress bars for issuing
        updateProgressBar('#issuedTodayProgress', data.warehouse_operations.issuing?.today || 0, 100);
        updateProgressBar('#issuedWeekProgress', data.warehouse_operations.issuing?.week || 0, 500);
        updateProgressBar('#issuedMonthProgress', data.warehouse_operations.issuing?.month || 0, 2000);

        // Quality Inspections
        $('#inspectionsPassed').text(data.warehouse_operations.inspections?.passed || 0);
        $('#inspectionsFailed').text(data.warehouse_operations.inspections?.failed || 0);
        $('#inspectionsPending').text(data.warehouse_operations.inspections?.pending || 0);

        // Update progress bars for inspections
        const totalInspections = (data.warehouse_operations.inspections?.passed || 0) +
                                (data.warehouse_operations.inspections?.failed || 0) +
                                (data.warehouse_operations.inspections?.pending || 0);
        updateProgressBar('#inspectionsPassedProgress', data.warehouse_operations.inspections?.passed || 0, totalInspections);
        updateProgressBar('#inspectionsFailedProgress', data.warehouse_operations.inspections?.failed || 0, totalInspections);
        updateProgressBar('#inspectionsPendingProgress', data.warehouse_operations.inspections?.pending || 0, totalInspections);
    }

    // Update team performance
    updateTeamPerformance(data.team_performance);

    // Load pending approvals
    loadPendingApprovals();
}

function updateRecentActivities(activities) {
    const container = $('#recentActivities');
    container.empty();

    if (activities && activities.length > 0) {
        activities.forEach(activity => {
            const activityHtml = `
                <div class="activity-item">
                    <div class="activity-dot bg-${getActivityColor(activity.status)}"></div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1 fw-bold">${activity.title}</p>
                            <small class="text-muted">${formatDate(activity.time)}</small>
                        </div>
                        <span class="badge badge-custom bg-${getActivityColor(activity.status)}">${activity.status}</span>
                    </div>
                </div>
            `;
            container.append(activityHtml);
        });
    } else {
        container.html('<p class="text-muted text-center py-4">No recent activities</p>');
    }
}

function updateLowStockTable(items) {
    const tbody = $('#lowStockTable');
    tbody.empty();

    if (items && items.length > 0) {
        items.forEach(item => {
            const row = `
                <tr>
                    <td>${item.item_name}</td>
                    <td>${item.current_stock}</td>
                    <td>${item.reorder_level}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="reorderItem(${item.id})">
                            <i class="fas fa-plus"></i> Reorder
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    } else {
        tbody.html('<tr><td colspan="4" class="text-center py-3">No low stock items</td></tr>');
    }
}

function updateTeamPerformance(teamData) {
    const container = $('#topPerformers');
    container.empty();

    if (teamData && teamData.top_performing_teams && teamData.top_performing_teams.length > 0) {
        teamData.top_performing_teams.forEach((team, index) => {
            const medal = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : '🏅';
            const teamHtml = `
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="badge bg-primary fs-6">${medal}</span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">${team.team_name}</h6>
                            <small class="text-muted">Team Performance</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <h6 class="mb-0 text-success">${team.completed_assignments}</h6>
                        <small class="text-muted">Assignments</small>
                    </div>
                </div>
            `;
            container.append(teamHtml);
        });

        // Add summary stats
        const summaryHtml = `
            <hr>
            <div class="row text-center">
                <div class="col-4">
                    <h5 class="text-primary">${teamData.total_teams || 0}</h5>
                    <small class="text-muted">Total Teams</small>
                </div>
                <div class="col-4">
                    <h5 class="text-success">${teamData.active_teams || 0}</h5>
                    <small class="text-muted">Active Teams</small>
                </div>
                <div class="col-4">
                    <h5 class="text-info">${teamData.assignments_this_month || 0}</h5>
                    <small class="text-muted">This Month</small>
                </div>
            </div>
        `;
        container.append(summaryHtml);
    } else {
        container.html('<p class="text-muted text-center py-4">No team performance data available</p>');
    }
}

function getActivityColor(status) {
    switch(status) {
        case 'completed': return 'success';
        case 'warning': return 'warning';
        case 'in_transit': return 'info';
        default: return 'secondary';
    }
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
}

// Functions to load detailed reports
function loadInventoryReport() {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    $.ajax({
        url: '/company/warehouse/reports/inventory-details',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success) {
                populateInventoryTable(response.data);
            }
        }
    });
}

function loadProcurementReport() {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    $.ajax({
        url: '/company/warehouse/reports/procurement-details',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success) {
                populateProcurementTable(response.data);
            }
        }
    });
}

function loadRequisitionReport() {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    $.ajax({
        url: '/company/warehouse/reports/requisition-details',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success) {
                populateRequisitionTable(response.data);
            }
        }
    });
}

function loadBatchReport() {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    $.ajax({
        url: '/company/warehouse/reports/batch-details',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success) {
                populateBatchTable(response.data);
            }
        }
    });
}

function loadSupplierReport() {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    $.ajax({
        url: '/company/warehouse/reports/supplier-details',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success) {
                populateSupplierTable(response.data);
            }
        }
    });
}

function loadFinancialReport() {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    $.ajax({
        url: '/company/warehouse/reports/financial-details',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success) {
                updateFinancialCards(response.data);
            }
        }
    });
}

function loadReorderPOTable() {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    $.ajax({
        url: '/company/warehouse/reports/reorder-pos',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success) {
                populateReorderPOTable(response.data);
            }
        },
        error: function(xhr) {
            console.error('Error loading reorder POs:', xhr);
        }
    });
}

// Table population functions
function populateInventoryTable(data) {
    const tbody = $('#inventoryReportBody');
    tbody.empty();

    if (data && data.length > 0) {
        data.forEach(item => {
            const row = `
                <tr>
                    <td>${item.item_name}</td>
                    <td>${item.category}</td>
                    <td>${item.batch_number}</td>
                    <td>${item.quantity}</td>
                    <td>GHS ${item.unit_price}</td>
                    <td>GHS ${item.total_value}</td>
                    <td>${item.location || 'N/A'}</td>
                    <td><span class="badge bg-${item.status === 'completed' ? 'success' : 'warning'}">${item.status}</span></td>
                </tr>
            `;
            tbody.append(row);
        });
    } else {
        tbody.html('<tr><td colspan="8" class="text-center py-4">No inventory data available</td></tr>');
    }
}

function populateProcurementTable(data) {
    const tbody = $('#procurementReportBody');
    tbody.empty();

    if (data && data.length > 0) {
        data.forEach(po => {
            const row = `
                <tr>
                    <td>${po.po_number}</td>
                    <td>${po.supplier_name}</td>
                    <td>${po.order_date}</td>
                    <td>GHS ${po.total_amount}</td>
                    <td><span class="badge bg-${getStatusColor(po.status)}">${po.status}</span></td>
                    <td>${po.is_reorder ? 'Yes' : 'No'}</td>
                    <td>${po.created_by}</td>
                </tr>
            `;
            tbody.append(row);
        });
    } else {
        tbody.html('<tr><td colspan="7" class="text-center py-4">No procurement data available</td></tr>');
    }
}

function populateRequisitionTable(data) {
    const tbody = $('#requisitionReportBody');
    tbody.empty();

    if (data && data.length > 0) {
        data.forEach(req => {
            const row = `
                <tr>
                    <td>${req.requisition_number}</td>
                    <td>${req.requested_by}</td>
                    <td>${req.department}</td>
                    <td>${req.date}</td>
                    <td>GHS ${req.total_amount}</td>
                    <td><span class="badge bg-${getStatusColor(req.status)}">${req.status}</span></td>
                    <td><span class="badge bg-${getPriorityColor(req.priority)}">${req.priority}</span></td>
                </tr>
            `;
            tbody.append(row);
        });
    } else {
        tbody.html('<tr><td colspan="7" class="text-center py-4">No requisition data available</td></tr>');
    }
}

function populateBatchTable(data) {
    const tbody = $('#batchReportBody');
    tbody.empty();

    if (data && data.length > 0) {
        data.forEach(batch => {
            const row = `
                <tr>
                    <td>${batch.batch_number}</td>
                    <td>${batch.item_name}</td>
                    <td>${batch.original_quantity}</td>
                    <td>${batch.current_quantity}</td>
                    <td>${batch.reorders}</td>
                    <td>${batch.total_added}</td>
                    <td>${batch.created_date}</td>
                </tr>
            `;
            tbody.append(row);
        });
    } else {
        tbody.html('<tr><td colspan="7" class="text-center py-4">No batch data available</td></tr>');
    }
}

function populateSupplierTable(data) {
    const tbody = $('#supplierReportBody');
    tbody.empty();

    if (data && data.length > 0) {
        data.forEach(supplier => {
            const row = `
                <tr>
                    <td>${supplier.name}</td>
                    <td>${supplier.total_pos}</td>
                    <td>GHS ${supplier.total_spend}</td>
                    <td>${supplier.on_time_delivery}%</td>
                    <td>${supplier.quality_score}/5</td>
                    <td>${supplier.last_order}</td>
                </tr>
            `;
            tbody.append(row);
        });
    } else {
        tbody.html('<tr><td colspan="6" class="text-center py-4">No supplier data available</td></tr>');
    }
}

function populateReorderPOTable(data) {
    const tbody = $('#reorderPOTable');
    tbody.empty();

    if (data && data.length > 0) {
        data.forEach(po => {
            const row = `
                <tr>
                    <td>${po.po_number}</td>
                    <td>${po.supplier}</td>
                    <td>${po.items}</td>
                    <td>${po.batch_numbers}</td>
                    <td>${po.quantity}</td>
                    <td>GHS ${po.amount}</td>
                    <td><span class="badge bg-info">${po.type}</span></td>
                    <td><span class="badge bg-${getStatusColor(po.status)}">${po.status}</span></td>
                    <td>${po.date}</td>
                </tr>
            `;
            tbody.append(row);
        });
    } else {
        tbody.html('<tr><td colspan="9" class="text-center py-4">No reorder purchase orders available</td></tr>');
    }
}

function updateFinancialCards(data) {
    $('#totalProcurementSpend').text('GHS ' + (data.total_spend || 0).toLocaleString());
    $('#avgPOValue').text('GHS ' + (data.avg_po_value || 0).toLocaleString());
    $('#totalTaxPaid').text('GHS ' + (data.total_tax || 0).toLocaleString());
    $('#pendingPayments').text('GHS ' + (data.pending_payments || 0).toLocaleString());
}

function getStatusColor(status) {
    switch(status) {
        case 'completed': case 'approved': return 'success';
        case 'pending': return 'warning';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
}

function getPriorityColor(priority) {
    switch(priority) {
        case 'urgent': return 'danger';
        case 'high': return 'warning';
        case 'medium': return 'info';
        case 'low': return 'success';
        default: return 'secondary';
    }
}

function loadPendingApprovals() {
    $.ajax({
        url: '/company/warehouse/reports/pending-approvals',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success) {
                populatePendingApprovals(response.data);
            }
        },
        error: function(xhr) {
            console.error('Error loading pending approvals:', xhr);
        }
    });
}

function populatePendingApprovals(data) {
    const container = $('#pendingApprovalsList');
    container.empty();

    if (data && data.length > 0) {
        data.forEach(item => {
            const itemHtml = `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">${item.title || item.requisition_number || item.po_number}</h6>
                        <small class="text-muted">${item.description || item.supplier_name || 'Pending approval'}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-warning">Pending</span>
                        <small class="d-block text-muted">${formatDate(item.created_at)}</small>
                    </div>
                </div>
            `;
            container.append(itemHtml);
        });
    } else {
        container.html('<div class="text-center py-4"><i class="fas fa-check-circle text-success fa-2x mb-2"></i><p class="text-muted mb-0">No pending approvals</p></div>');
    }
}

function updateProgressBar(selector, value, maxValue) {
    const percentage = maxValue > 0 ? Math.min((value / maxValue) * 100, 100) : 0;
    $(selector).css('width', percentage + '%');
}

function reorderItem(itemId) {
    // Implement reorder functionality
    Swal.fire({
        title: 'Reorder Item',
        text: 'This will create a purchase order for this item. Continue?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, reorder'
    }).then((result) => {
        if (result.isConfirmed) {
            // Call reorder API
            $.ajax({
                url: `/company/warehouse/reports/central-store/reorder/${itemId}`,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success', 'Reorder purchase order created successfully', 'success');
                        loadComprehensiveAnalytics(); // Refresh data
                    } else {
                        Swal.fire('Error', response.message || 'Failed to create reorder', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Failed to create reorder request', 'error');
                }
            });
        }
    });
}

// Date filter functions
function setDefaultDateRange() {
    const now = new Date();
    const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
    const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);

    // Format dates as YYYY-MM-DD
    const startDateStr = startOfMonth.toISOString().split('T')[0];
    const endDateStr = endOfMonth.toISOString().split('T')[0];

    // Set the date inputs
    $('#startDate').val(startDateStr);
    $('#endDate').val(endDateStr);

    console.log('Default date range set to current month:', startDateStr, 'to', endDateStr);
}

function applyDateFilters() {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    if (!startDate || !endDate) {
        Swal.fire('Error', 'Please select both start and end dates', 'error');
        return;
    }

    if (new Date(startDate) > new Date(endDate)) {
        Swal.fire('Error', 'Start date cannot be after end date', 'error');
        return;
    }

    // Reload data with filters
    loadComprehensiveAnalytics(startDate, endDate);
}

function resetDateFilters() {
    setDefaultDateRange();
    loadComprehensiveAnalytics();
}
</script>
@endsection
