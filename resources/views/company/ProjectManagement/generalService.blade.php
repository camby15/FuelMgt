@extends('layouts.vertical', ['page_title' => 'General Services'])

@section('css')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
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
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">General Services Dashboard</h4>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Print</button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                        <i class="far fa-calendar-alt me-1"></i>
                        This week
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs warehouse-tabs" id="generalServiceTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="true">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="facility-tab" data-bs-toggle="tab" data-bs-target="#facility" type="button" role="tab" aria-controls="facility" aria-selected="false">
                <i class="fas fa-building me-2"></i>Facility Management
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="housekeeping-tab" data-bs-toggle="tab" data-bs-target="#housekeeping" type="button" role="tab" aria-controls="housekeeping" aria-selected="false">
                <i class="fas fa-broom me-2"></i>Housekeeping
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ticketing-tab" data-bs-toggle="tab" data-bs-target="#ticketing" type="button" role="tab" aria-controls="ticketing" aria-selected="false">
                <i class="fas fa-ticket-alt me-2"></i>Ticketing & Service Requests
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab" aria-controls="inventory" aria-selected="false">
                <i class="fas fa-boxes me-2"></i>Inventory
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="transport-tab" data-bs-toggle="tab" data-bs-target="#transport" type="button" role="tab" aria-controls="transport" aria-selected="false">
                <i class="fas fa-truck me-2"></i>Transport
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                <i class="fas fa-shield-alt me-2"></i>Security
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="document-compliance-tab" data-bs-toggle="tab" data-bs-target="#documentCompliance" type="button" role="tab" aria-controls="documentCompliance" aria-selected="false">
                <i class="fas fa-file-contract me-2"></i>Document & Compliance
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="generalServiceTabsContent">
        <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
            @include('company.ProjectManagement.partials.dashboard-cards')
        </div>
        
        <!-- Facility Management -->
        <div class="tab-pane fade" id="facility" role="tabpanel" aria-labelledby="facility-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Facility Management</h5>
            </div>
            @include('company.ProjectManagement.partials.facility-management')
        </div>
        
        <!-- Housekeeping -->
        <div class="tab-pane fade" id="housekeeping" role="tabpanel" aria-labelledby="housekeeping-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Housekeeping Tasks</h5>
                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#housekeepingModal">
                    <i class="fas fa-plus me-1"></i> New Task
                </button>
            </div>
            @include('company.ProjectManagement.partials.housekeeping')
        </div>
        
        <!-- Ticketing & Service Requests -->
        <div class="tab-pane fade" id="ticketing" role="tabpanel" aria-labelledby="ticketing-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Ticketing & Service Requests</h5>
            </div>
            @include('company.ProjectManagement.partials.ticketing_system')
        </div>
        
        <!-- Inventory -->
        @include('company.ProjectManagement.partials.inventory')
        
        <!-- Transport -->
        @include('company.ProjectManagement.partials.transport')
        
        <!-- Security -->
        @include('company.ProjectManagement.partials.security')
        
        <!-- Document & Compliance -->
        @include('company.ProjectManagement.partials.document_compliance')
    </div>
    
    <!-- Include All Modals -->
    @include('company.ProjectManagement.modals.modals')
</div>

<!-- Include Modals -->
@include('company.ProjectManagement.modals.new-maintenance')
@include('company.ProjectManagement.modals.modals')
@include('company.ProjectManagement.modals.inventory-modals')

@push('styles')
<style>
    .sidebar {
        min-height: 100vh;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .sidebar .nav-link {
        color: #dee2e6;
        padding: 0.75rem 1rem;
        margin: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        transition: all 0.3s;
    }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: rgba(255,255,255,0.1);
        color: #fff;
    }
    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
    }
    .user-profile {
        padding: 1.5rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Custom Housekeeping Tasks JS -->
<script src="{{ asset('assets/js/housekeeping-tasks.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Initialize Select2
        $('.select2-multiple').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select options',
            allowClear: true
        });

        // Handle form submission for maintenance form
        document.getElementById('maintenanceForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            // Add form submission logic here
            alert('Maintenance request submitted successfully!');
            var modal = bootstrap.Modal.getInstance(document.getElementById('newMaintenanceModal'));
            modal.hide();
        });
    });
</script>
@endpush
@endsection