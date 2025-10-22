@extends('layouts.vertical', ['page_title' => 'Warehouse Operations'])

@section('css')

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />
<style>
    /* Main Container */
    .warehouse-container {
        background-color: #f8f9fc;
        min-height: calc(100vh - 70px);
        padding: 20px;
    }

    /* Tab Navigation */
    .warehouse-tabs {
        background: #fff;
        border-radius: 10px 10px 0 0;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 0;
        padding: 0 20px;
        border-bottom: 1px solid #e3e6f0;
    }

    .warehouse-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
        padding: 15px 25px;
        border: none;
        position: relative;
        transition: all 0.3s ease;
    }

    .warehouse-tabs .nav-link.active {
        color: #3b7ddd;
        background: transparent;
    }

    .warehouse-tabs .nav-link.active:after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 3px;
        background: #3b7ddd;
        border-radius: 3px 3px 0 0;
    }

    .warehouse-tabs .nav-link:hover:not(.active) {
        color: #3b7ddd;
    }

    /* Dashboard Cards */
    .dashboard-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-left: 4px solid #3b7ddd;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #fff;
        margin-bottom: 15px;
    }

    .card-title {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .card-value {
        font-size: 24px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .card-change {
        font-size: 12px;
        font-weight: 500;
    }

    .positive {
        color: #28a745;
    }

    .negative {
        color: #dc3545;
    }

    /* Loading spinner styles */
    .card-value .fa-spinner {
        font-size: 1.5rem;
        animation: spin 1s linear infinite;
    }
    
    .card-change .fa-spinner {
        font-size: 0.875rem;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Tab Content */
    .tab-content {
        background: #fff;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        padding: 25px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .warehouse-tabs .nav-link {
            padding: 12px 15px;
            font-size: 14px;
        }

        .dashboard-card {
            margin-bottom: 15px;
        }
    }
</style>
@endsection

@section('content')
<div class="warehouse-container">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Warehouse Operations Dashboard</h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col">
            <div class="dashboard-card">
                <div class="card-icon" style="background-color: #4e73df;">
                    <i class="fas fa-warehouse"></i>
                </div>
                <h6 class="card-title">Total Items</h6>
                <h3 class="card-value" id="totalItems">
                    <i class="fas fa-spinner fa-spin text-primary"></i>
                </h3>
                <div class="card-change" id="totalItemsChange">
                    <span class="text-muted">
                        <i class="fas fa-spinner fa-spin me-1"></i>Loading...
                    </span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="dashboard-card">
                <div class="card-icon" style="background-color: #1cc88a;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h6 class="card-title">In Stock</h6>
                <h3 class="card-value" id="inStockItems">
                    <i class="fas fa-spinner fa-spin text-success"></i>
                </h3>
                <div class="card-change" id="inStockChange">
                    <span class="text-muted">
                        <i class="fas fa-spinner fa-spin me-1"></i>Loading...
                    </span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="dashboard-card">
                <div class="card-icon" style="background-color: #f6c23e;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h6 class="card-title">Low Stock</h6>
                <h3 class="card-value" id="lowStockItems">
                    <i class="fas fa-spinner fa-spin text-warning"></i>
                </h3>
                <div class="card-change" id="lowStockChange">
                    <span class="text-muted">
                        <i class="fas fa-spinner fa-spin me-1"></i>Loading...
                    </span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="dashboard-card">
                <div class="card-icon" style="background-color: #e74a3b;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h6 class="card-title">Out of Stock</h6>
                <h3 class="card-value" id="outOfStockItems">
                    <i class="fas fa-spinner fa-spin text-danger"></i>
                </h3>
                <div class="card-change" id="outOfStockChange">
                    <span class="text-muted">
                        <i class="fas fa-spinner fa-spin me-1"></i>Loading...
                    </span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="dashboard-card">
                <div class="card-icon" style="background-color: #6f42c1;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h6 class="card-title">Total Value</h6>
                <h3 class="card-value" id="totalValue">
                    <i class="fas fa-spinner fa-spin text-info"></i>
                </h3>
                <div class="card-change" id="totalValueChange">
                    <span class="text-muted">
                        <i class="fas fa-spinner fa-spin me-1"></i>Loading...
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs warehouse-tabs" id="warehouseTabs" role="tablist">
         <li class="nav-item" role="presentation">
            <button class="nav-link active" id="receiving-tab" data-bs-toggle="tab" data-bs-target="#receiving" type="button" role="tab" aria-controls="receiving" aria-selected="true">
                <i class="fas fa-truck-loading me-2"></i>Receiving
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link " id="inspection-tab" data-bs-toggle="tab" data-bs-target="#inspection" type="button" role="tab" aria-controls="inspection" aria-selected="false">
                <i class="fas fa-clipboard-check me-2"></i>Inspection & QC
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="central-stores-tab" data-bs-toggle="tab" data-bs-target="#central-stores" type="button" role="tab" aria-controls="central-stores" aria-selected="false">
                <i class="fas fa-warehouse me-2"></i>Stores
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="issuing-tab" data-bs-toggle="tab" data-bs-target="#issuing" type="button" role="tab" aria-controls="issuing" aria-selected="false">
                <i class="fas fa-truck me-2"></i>Issuing & Waybill
            </button>
        </li>

    </ul>

    <!-- Tab Content #H2RMG0211SPIDER-->
    <div class="tab-content" id="warehouseTabsContent">

        <div class="tab-pane fade show active" id="receiving" role="tabpanel" aria-labelledby="receiving-tab">
            @include('company.InventoryManagement.WarehouseOps.Receiving')
        </div>

       <div class="tab-pane fade " id="inspection" role="tabpanel" aria-labelledby="inspection-tab">
            @include('company.InventoryManagement.WarehouseOps.Inspection')
        </div>
        <!-- Other Tabs Content -->

        <div class="tab-pane fade" id="central-stores" role="tabpanel" aria-labelledby="central-stores-tab">
            @include('company.InventoryManagement.WarehouseOps.centralStores')
        </div>

        <div class="tab-pane fade" id="issuing" role="tabpanel" aria-labelledby="issuing-tab">
            @include('company.InventoryManagement.WarehouseOps.issuing')
        </div>

    </div>
</div>
@endsection

@push('javascript')
<!-- Required JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.form-select').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Load warehouse statistics immediately
    console.log('Page ready, loading warehouse statistics...');
    console.log('Route URL:', '{{ route("warehouse.dashboard.statistics") }}');
    
    // Load statistics from API
    loadWarehouseStatistics();
});

// Load fallback statistics only when API fails
function loadFallbackStatistics() {
    console.log('📊 Loading fallback statistics...');
    
    const fallbackData = {
        total_items: 0,
        in_stock_items: 0,
        low_stock_items: 0,
        out_of_stock_items: 0,
        total_value: 0,
        total_items_change: 0,
        in_stock_change: 0,
        low_stock_change: 0,
        out_of_stock_change: 0,
        total_value_change: 0
    };
    
    updateStatisticsDisplay(fallbackData);
}


// Load warehouse statistics from API
function loadWarehouseStatistics() {
    console.log('Loading warehouse statistics...');
    
    $.ajax({
        url: '{{ route("warehouse.dashboard.statistics") }}',
        type: 'POST',
        timeout: 10000, // 10 second timeout
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('✅ Statistics loaded successfully:', response);
            if (response.success) {
                updateStatisticsDisplay(response.data);
            } else {
                console.error('❌ Statistics failed:', response);
                loadFallbackStatistics();
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ AJAX Error:', {
                status: xhr.status,
                statusText: xhr.statusText,
                error: error
            });
            loadFallbackStatistics();
        }
    });
}


// Update statistics display with real data
function updateStatisticsDisplay(data) {
    // Total Items
    $('#totalItems').html(formatNumber(data.total_items || 0));
    $('#totalItemsChange').html(formatChangePercentage(data.total_items_change || 0, 'items this month'));

    // In Stock Items
    $('#inStockItems').html(formatNumber(data.in_stock_items || 0));
    $('#inStockChange').html(formatChangePercentage(data.in_stock_change || 0, 'from last month'));

    // Low Stock Items
    $('#lowStockItems').html(formatNumber(data.low_stock_items || 0));
    $('#lowStockChange').html(formatChangePercentage(data.low_stock_change || 0, 'from last month'));

    // Out of Stock Items
    $('#outOfStockItems').html(formatNumber(data.out_of_stock_items || 0));
    $('#outOfStockChange').html(formatChangePercentage(data.out_of_stock_change || 0, 'from last month'));

    // Total Value
    $('#totalValue').html('GHS ' + formatMoney(data.total_value || 0));
    $('#totalValueChange').html(formatChangePercentage(data.total_value_change || 0, 'from last month'));
}


// Format number with commas
function formatNumber(num) {
    return Number(num).toLocaleString();
}

// Format money with two decimal places
function formatMoney(amount) {
    return parseFloat(amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// Format change percentage with appropriate icon and color
function formatChangePercentage(change, suffix) {
    // Handle null, undefined, or NaN values
    if (change === null || change === undefined || isNaN(change)) {
        return '<span class="text-muted"><i class="fas fa-minus me-1"></i>No data ' + suffix + '</span>';
    }
    
    if (change === 0) {
        return '<span class="text-muted"><i class="fas fa-minus me-1"></i>No change ' + suffix + '</span>';
    }
    
    const isPositive = change > 0;
    const icon = isPositive ? 'fas fa-arrow-up' : 'fas fa-arrow-down';
    const colorClass = isPositive ? 'positive' : 'negative';
    const changeText = Math.abs(change).toFixed(1) + '%';
    
    return `<span class="${colorClass}"><i class="${icon} me-1"></i>${changeText} ${suffix}</span>`;
}

// Refresh statistics (can be called from other parts of the app)
function refreshWarehouseStatistics() {
    loadWarehouseStatistics();
}
</script>
@endpush
