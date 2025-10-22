@extends('layouts.vertical', ['page_title' => 'Requisition Management'])

@section('css')
@vite([
    'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
    'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'
])
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />

<!-- Load all required libraries early to prevent undefined errors -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .requisition-container {
        background-color: #f8f9fc;
        min-height: calc(100vh - 70px);
        padding: 20px;
    }  

    .requisition-tabs {
        background: #f8f9fc;
        border-radius: 12px 12px 0 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 0;
        padding: 5px 15px 0;
        border: none;
        overflow-x: auto;
        white-space: nowrap;
        display: flex;
        flex-wrap: nowrap;
    }

    .requisition-tabs .nav-link {
        color: #5a5c69;
        font-weight: 600;
        padding: 15px 25px;
        border: none;
        position: relative;
        transition: all 0.3s ease;
        border-radius: 8px 8px 0 0;
        margin-right: 4px;
        background: #e9ecef;
        display: inline-flex;
        align-items: center;
    }

    .requisition-tabs .nav-link.active {
        color: #fff;
        background: linear-gradient(45deg, #3b7ddd, #2c6ecb);
        box-shadow: 0 4px 10px rgba(59, 125, 221, 0.2);
        transform: translateY(-2px);
    }

    .requisition-tabs .nav-link:hover:not(.active) {
        color: #3b7ddd;
        background: #e3e8f7;
        transform: translateY(-2px);
    }

    .requisition-tabs .nav-link i {
        margin-right: 8px;
        font-size: 1.1em;
    }

    .requisition-tabs .nav-link.active i {
        color: rgba(255, 255, 255, 0.9);
    }

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
        background: linear-gradient(45deg, #3b7ddd, #6c8ebf);
    }
</style>
@endsection

@section('content')
<div class="requisition-container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Requisition Management</h1>
                
            </div>
        </div>
    </div>

    <!-- Dashboard Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Pending Approvals</div>
                        <div class="h4 mb-0" id="pendingApprovalsCount">0</div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Open POs</div>
                        <div class="h4 mb-0" id="openPOsCount">0</div>
                    </div>
                    <div class="card-icon" style="background: linear-gradient(45deg, #1cc88a, #17a673);">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Monthly Spend</div>
                        <div class="h4 mb-0" id="monthlySpend">$0</div>
                    </div>
                    <div class="card-icon" style="background: linear-gradient(45deg, #f6c23e, #f8b02d);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Active Suppliers</div>
                        <div class="h4 mb-0" id="activeSuppliersCount">0</div>
                    </div>
                    <div class="card-icon" style="background: linear-gradient(45deg, #e74a3b, #d62c1a);">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Tabs Navigation -->
    <div class="row">
        <div class="col-12">
            <ul class="nav requisition-tabs" id="requisitionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="requisitions-tab" data-bs-toggle="tab" 
                            data-bs-target="#requisitions" type="button" role="tab" 
                            aria-controls="requisitions" aria-selected="true">
                        <i class="fas fa-clipboard-list me-2"></i>Purchase Requisitions 
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content bg-white p-4 rounded-bottom shadow-sm border-0" id="procurementTabsContent" style="min-height: 400px;">
                <style>
                    .tab-pane {
                        padding: 20px;
                        border-radius: 0 0 8px 8px;
                        background: #fff;
                    }
                    .tab-pane h5 {
                        color: #4e73df;
                        font-weight: 600;
                        margin-bottom: 20px;
                        padding-bottom: 10px;
                        border-bottom: 2px solid #f8f9fc;
                    }
                </style>
                <!-- Purchase Requisitions Tab -->
                <div class="tab-pane fade show active" id="requisitions" role="tabpanel" aria-labelledby="requisitions-tab">
                    @include('company.InventoryManagement.ProcRequi.purchase-requi')
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- jQuery and Select2 already loaded in head -->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- <script src="{{ asset('js/pages/requisition.js') }}"></script> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
        
        // Load statistics on page load
        loadStatistics();
        
        // Tab switching logic - reload statistics when switching to overview
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            const targetTab = $(e.target).attr('href');
            if (targetTab === '#overview-tab' || targetTab === '#overview') {
                // Reload statistics when switching to overview tab
                setTimeout(loadStatistics, 100);
            }
        });
    });
    
    // Load statistics for the dashboard
    function loadStatistics() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log('Loading statistics...');
        
        // Show loading state
        $('#pendingApprovalsCount').html('<span class="spinner-border spinner-border-sm" role="status"></span>');
        $('#openPOsCount').html('<span class="spinner-border spinner-border-sm" role="status"></span>');
        $('#monthlySpend').html('<span class="spinner-border spinner-border-sm" role="status"></span>');
        $('#activeSuppliersCount').html('<span class="spinner-border spinner-border-sm" role="status"></span>');
        
        $.ajax({
            url: '/company/warehouse/requisitions/statistics',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log('Statistics loaded:', response);
                
                if (response.success) {
                    // Update pending approvals
                    $('#pendingApprovalsCount').html(response.data.pending_approvals || 0);
                    
                    // Update open POs
                    $('#openPOsCount').html(response.data.open_pos || 0);
                    
                    // Update monthly spend with currency formatting
                    const monthlySpend = response.data.monthly_spend || 0;
                    $('#monthlySpend').html('$' + new Intl.NumberFormat('en-US').format(monthlySpend));
                    
                    // Update active suppliers
                    $('#activeSuppliersCount').html(response.data.active_suppliers || 0);
                } else {
                    // Show error state
                    $('#pendingApprovalsCount').html('Error');
                    $('#openPOsCount').html('Error');
                    $('#monthlySpend').html('Error');
                    $('#activeSuppliersCount').html('Error');
                    console.error('Statistics API returned failure:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading statistics:', error);
                console.error('Response:', xhr.responseText);
                console.error('Status:', status);
                
                // Show error state with fallback values
                $('#pendingApprovalsCount').html('0');
                $('#openPOsCount').html('0');
                $('#monthlySpend').html('$0');
                $('#activeSuppliersCount').html('0');
            }
        });
    }
</script>
@endpush

@endsection
