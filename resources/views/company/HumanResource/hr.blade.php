@extends('layouts.vertical', ['page_title' => 'HR Management'])

@section('css')
    @vite([
        'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
        'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
        'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
        'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'
    ])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Import Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap');
        
        /* Base Typography */
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.6;
            color: #2d3748;
        }
        
        /* Headings */
        h1, h2, h3, h4, h5, h6,
        .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 600;
            line-height: 1.3;
            color: #1a202c;
        }
        
        /* Global Typography */
        body, 
        .modal-content,
        .modal-header,
        .modal-body,
        .modal-footer,
        .card,
        .card-header,
        .card-body,
        .card-footer,
        .form-control,
        .form-select,
        .form-label,
        .form-check-label,
        .btn,
        .dropdown-menu,
        .dropdown-item,
        .table,
        .table th,
        .table td,
        .dataTables_wrapper,
        .dataTables_info,
        .dataTables_length,
        .dataTables_filter,
        .dataTables_paginate,
        .pagination,
        .page-link,
        .badge,
        .alert,
        .tooltip,
        .popover,
        .nav,
        .nav-tabs,
        .nav-link,
        .tab-content,
        .tab-pane,
        .input-group-text,
        .form-text,
        .invalid-feedback,
        .valid-feedback,
        .was-validated .form-control:invalid,
        .was-validated .form-control:valid,
        .form-control:focus,
        .form-select:focus,
        .btn:focus,
        .page-item,
        .page-link,
        .dropdown-header,
        .dropdown-divider,
        .list-group,
        .list-group-item,
        .breadcrumb,
        .breadcrumb-item,
        .toast,
        .toast-header,
        .toast-body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        /* Ensure all form elements use the same font */
        input,
        button,
        select,
        optgroup,
        textarea {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        /* Fix for select2 if used */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-search--dropdown .select2-search__field,
        .select2-container--default .select2-results__option,
        .select2-dropdown {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        }
        
        /* Fix for datepickers if used */
        .flatpickr-input,
        .flatpickr-day,
        .flatpickr-weekday,
        .flatpickr-month,
        .flatpickr-current-month,
        .flatpickr-time input,
        .flatpickr-time-separator,
        .flatpickr-am-pm {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        }

        /* Navigation Tabs */
        .nav-tabs .nav-link {
            color: #4a5568;
            font-family: 'Figtree', sans-serif;
            font-weight: 500;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            padding: 0.75rem 1.5rem;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.25s ease-in-out;
            text-transform: capitalize;
        }
        
        /* Employee Database */
        #employee-database-tab.active,
        #employee-database-tab:hover:not(.active) {
            color: #2c7be5;
            border-color: #2c7be5;
            font-weight: 600;
        }
        
        /* Recruitment */
        #recruitment-tab.active,
        #recruitment-tab:hover:not(.active) {
            color: #00d97e;
            border-color: #00d97e;
            font-weight: 600;
        }
        
        /* Attendance */
        #attendance-tab.active,
        #attendance-tab:hover:not(.active) {
            color: #f6c343;
            border-color: #f6c343;
            font-weight: 600;
        }
        
        /* Leave */
        #leave-tab.active,
        #leave-tab:hover:not(.active) {
            color: #e63757;
            border-color: #e63757;
            font-weight: 600;
        }
        
        /* Payroll */
        #payroll-tab.active,
        #payroll-tab:hover:not(.active) {
            color: #6f42c1;
            border-color: #6f42c1;
            font-weight: 600;
        }
        
        /* Performance */
        #performance-tab.active,
        #performance-tab:hover:not(.active) {
            color: #2b908f;
            border-color: #2b908f;
        }
        
        /* Training */
        #training-tab.active,
        #training-tab:hover:not(.active) {
            color: #f39c12;
            border-color: #f39c12;
        }
        
        /* Documentation */
        #documentation-tab.active,
        #documentation-tab:hover:not(.active) {
            color: #e74c3c;
            border-color: #e74c3c;
        }
        
        .nav-tabs .nav-link.active {
            font-weight: 600;
            background-color: transparent;
        }
        
        .tab-content {
            padding: 1.5rem 0;
        }
        
        /* Fix for tab content layout issues */
        .tab-pane {
            min-height: 0;
            overflow: visible;
        }
        
        .tab-pane:not(.active) {
            display: none !important;
        }
        
        .tab-pane.active {
            display: block !important;
        }
        
        /* Ensure performance tab content doesn't get affected by other tabs */
        #performance {
            position: relative;
            z-index: 1;
        }
        
        /* Fix for performance tab cards */
        #performance .card {
            position: relative;
            z-index: auto;
        }
        
        /* Dark theme overrides */
        [data-bs-theme="dark"] .nav-tabs .nav-link {
            color: #adb5bd;
        }
        [data-bs-theme="dark"] #employee-database-tab.active,
        [data-bs-theme="dark"] #employee-database-tab:hover:not(.active) {
            color: #6ea8fe;
            border-color: #6ea8fe;
        }
        [data-bs-theme="dark"] #recruitment-tab.active,
        [data-bs-theme="dark"] #recruitment-tab:hover:not(.active) {
            color: #20c997;
            border-color: #20c997;
        }
        [data-bs-theme="dark"] #attendance-tab.active,
        [data-bs-theme="dark"] #attendance-tab:hover:not(.active) {
            color: #ffc107;
            border-color: #ffc107;
        }
        [data-bs-theme="dark"] #leave-tab.active,
        [data-bs-theme="dark"] #leave-tab:hover:not(.active) {
            color: #fd7e14;
            border-color: #fd7e14;
        }
        [data-bs-theme="dark"] #payroll-tab.active,
        [data-bs-theme="dark"] #payroll-tab:hover:not(.active) {
            color: #9d7bd8;
            border-color: #9d7bd8;
        }
        [data-bs-theme="dark"] #performance-tab.active,
        [data-bs-theme="dark"] #performance-tab:hover:not(.active) {
            color: #20c9bf;
            border-color: #20c9bf;
        }
        [data-bs-theme="dark"] #training-tab.active,
        [data-bs-theme="dark"] #training-tab:hover:not(.active) {
            color: #ff9800;
            border-color: #ff9800;
        }
        [data-bs-theme="dark"] #documentation-tab.active,
        [data-bs-theme="dark"] #documentation-tab:hover:not(.active) {
            color: #e74c3c;
            border-color: #e74c3c;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box mb-4">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="">Home</a></li>
                            <li class="breadcrumb-item active">HR Management</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Human Resources</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" id="hrTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="employee-database-tab" data-bs-toggle="tab" 
                                    data-bs-target="#employee-database" type="button" role="tab" 
                                    aria-controls="employee-database" aria-selected="true">
                                    <i class="fas fa-users me-1"></i> Employee Database
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="recruitment-tab" data-bs-toggle="tab" 
                                    data-bs-target="#recruitment" type="button" role="tab" 
                                    aria-controls="recruitment" aria-selected="false">
                                    <i class="fas fa-user-plus me-1"></i> Recruitment & Onboarding
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" 
                                    data-bs-target="#attendance" type="button" role="tab" 
                                    aria-controls="attendance" aria-selected="false">
                                    <i class="fas fa-calendar-check me-1"></i> Attendance
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="leave-tab" data-bs-toggle="tab" 
                                    data-bs-target="#leave" type="button" role="tab" 
                                    aria-controls="leave" aria-selected="false">
                                    <i class="fas fa-calendar-minus me-1"></i> Leave
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="payroll-tab" data-bs-toggle="tab" 
                                    data-bs-target="#payroll" type="button" role="tab" 
                                    aria-controls="payroll" aria-selected="false">
                                    <i class="fas fa-money-bill-wave me-1"></i> Payroll
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="training-tab" data-bs-toggle="tab" 
                                    data-bs-target="#training" type="button" role="tab" 
                                    aria-controls="training" aria-selected="false">
                                    <i class="fas fa-graduation-cap me-1"></i> Training
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="performance-tab" data-bs-toggle="tab" 
                                    data-bs-target="#performance" type="button" role="tab" 
                                    aria-controls="performance" aria-selected="false">
                                    <i class="fas fa-chart-line me-1"></i> Performance
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="documentation-tab" data-bs-toggle="tab" 
                                    data-bs-target="#documentation" type="button" role="tab" 
                                    aria-controls="documentation" aria-selected="false">
                                    <i class="fas fa-file-alt me-1"></i> Documentation
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content pt-0 mt-0" id="hrTabsContent">
                            <div class="tab-pane fade show active" id="employee-database" role="tabpanel" 
                                aria-labelledby="employee-database-tab">
                                @include('company.HumanResource.tabs.employee-database')
                            </div>
                            <div class="tab-pane fade" id="recruitment" role="tabpanel" 
                                aria-labelledby="recruitment-tab">
                                @include('company.HumanResource.tabs.recruitment')
                            </div>
                            <div class="tab-pane fade" id="attendance" role="tabpanel" 
                                aria-labelledby="attendance-tab">
                                @include('company.HumanResource.tabs.attendance')
                            </div>
                            <div class="tab-pane fade" id="leave" role="tabpanel" 
                                aria-labelledby="leave-tab">
                                @include('company.HumanResource.tabs.leave')
                            </div>
                            <div class="tab-pane fade" id="payroll" role="tabpanel" 
                                aria-labelledby="payroll-tab">
                                @include('company.HumanResource.tabs.payroll')
                            </div>
                            <div class="tab-pane fade" id="training" role="tabpanel" 
                                aria-labelledby="training-tab">
                                @include('company.HumanResource.tabs.training')
                            </div>
                            <div class="tab-pane fade" id="performance" role="tabpanel" 
                                aria-labelledby="performance-tab">
                                @include('company.HumanResource.tabs.performance')
                            </div>
                            <div class="tab-pane fade" id="documentation" role="tabpanel" 
                                aria-labelledby="documentation-tab">
                                @include('company.HumanResource.tabs.documentation')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>
    <!-- Dropzone JS -->
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Moment.js and DateRangePicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1.0/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1.0/daterangepicker.min.css">
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // JavaScript is working - no test alerts needed
        console.log('🔥 MAIN HR PAGE: JavaScript is working!');
        
        // Initialize any global components when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize Select2 for multi-select elements
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('select[multiple]').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: $(this).data('placeholder') || 'Select options',
                    allowClear: true,
                    closeOnSelect: false
                });
            }
            
            // Fix tab content layout issues
            const tabButtons = document.querySelectorAll('#hrTabs button[data-bs-toggle="tab"]');
            tabButtons.forEach(button => {
                button.addEventListener('shown.bs.tab', function (event) {
                    // Force reflow to fix layout issues
                    const targetTab = document.querySelector(event.target.getAttribute('data-bs-target'));
                    if (targetTab) {
                        targetTab.style.display = 'block';
                        targetTab.offsetHeight; // Trigger reflow
                    }
                });
            });
        });
        
        // Function to show alert messages
        function showAlert(message, type = 'info', duration = 5000) {
            // Remove any existing alerts
            const existingAlert = document.querySelector('.alert-dismissible');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-3`;
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insert after the page header
            const pageHeader = document.querySelector('.page-header');
            if (pageHeader) {
                pageHeader.after(alertDiv);
            } else {
                // Fallback to prepending to the body
                document.body.prepend(alertDiv);
            }
            
            // Auto-dismiss after specified duration
            if (duration > 0) {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alertDiv);
                    bsAlert.close();
                }, duration);
            }
            
            return alertDiv;
        }
    </script>
    
    <!-- Load tab-specific scripts -->
    @stack('scripts')
@endsection