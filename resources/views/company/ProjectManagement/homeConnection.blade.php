@extends('layouts.vertical', ['page_title' => 'Home Connection Management'])

@section('css')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
   integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
   crossorigin=""/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<!-- DataTables and Select2 CSS are loaded via main Vite bundle -->
<!-- Leaflet CSS is loaded via CDN above -->
<style>
    :root {
        --gesl-primary: #0056b3;
        --gesl-secondary: #ffc107;
    }
    
    /* Action Button Styles */
    .action-btn {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
        transition: all 0.2s;
    }
    
    .action-btn:hover {
        transform: scale(1.2);
    }
    
    .action-btn i {
        font-size: 14px;
    }
    
    .connection-container { 
        background-color: #f8f9fa;
        min-height: calc(100vh - 70px);
        padding: 20px;
    }
    
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .nav-tabs {
        border-bottom: 1px solid #e9ecef;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 1rem 1.5rem;
    }
    
    .nav-tabs .nav-link.active {
        color: var(--gesl-primary);
        border-bottom: 3px solid var(--gesl-primary);
    }
    
    .status-badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 0.25rem;
    }
    
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-in-progress { background-color: #cce5ff; color: #004085; }
    .status-completed { background-color: #d4edda; color: #155724; }

    #map {
        height: 500px;
        border-radius: 0.5rem;
        border: 1px solid #e9ecef;
    }
    
    .connection-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }
    
    .connection-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    
    .page-heading {
        color: rgb(5, 67, 160); /* Deep blue color */
        font-weight: 700; /* Bolder text */
        font-size: 1.75rem; /* Larger font size */
        letter-spacing: -0.5px; /* Slightly tighter letter spacing */
    }
</style>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================
        // TAB PERSISTENCE - Remember Active Tab
        // ============================================
        
        // Restore last active tab on page load
        const lastActiveTab = localStorage.getItem('homeConnectionActiveTab');
        if (lastActiveTab) {
            // Remove active class from default tab
            const defaultTab = document.querySelector('#customers-tab');
            const defaultPane = document.querySelector('#customers');
            if (defaultTab && defaultPane) {
                defaultTab.classList.remove('active');
                defaultTab.setAttribute('aria-selected', 'false');
                defaultPane.classList.remove('show', 'active');
            }
            
            // Activate the saved tab
            const savedTab = document.querySelector(`#${lastActiveTab}`);
            const savedPane = document.querySelector(savedTab?.getAttribute('data-bs-target'));
            if (savedTab && savedPane) {
                savedTab.classList.add('active');
                savedTab.setAttribute('aria-selected', 'true');
                savedPane.classList.add('show', 'active');
            }
        }
        
        // Save active tab when clicked
        document.querySelectorAll('#myTab button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                localStorage.setItem('homeConnectionActiveTab', event.target.id);
            });
        });
        
        // ============================================
        // CHART INITIALIZATION
        // ============================================
        
        // Initialize Chart
        const chartCanvas = document.getElementById('teamOutputChart');
        if (!chartCanvas) {
            console.warn('Team output chart canvas not found');
            return;
        }
        
        const ctx = chartCanvas.getContext('2d');
        const teamOutputChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
                datasets: [{
                    label: 'Tasks Completed',
                    data: [12, 19, 3, 5, 2],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 2
                        }
                    }
                }
            }
        });

        // Handle port selection
        document.querySelectorAll('.port-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.port-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                // Here you would typically load the data for the selected port
                const portNumber = this.dataset.port;
                console.log(`Port ${portNumber} selected`);
                // Load port data via AJAX would go here
            });
        });

        // Handle image upload buttons
        const handleImageUpload = (fileInputId, imageId, placeholderId) => {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/*';
            fileInput.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const img = document.getElementById(imageId);
                        const placeholder = document.getElementById(placeholderId);
                        
                        img.src = event.target.result;
                        img.classList.remove('d-none');
                        placeholder.classList.add('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            };
            fileInput.click();
        };

        // Set up event listeners for all upload buttons
        document.querySelectorAll('[data-upload-target]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const target = btn.dataset.uploadTarget;
                const [imageId, placeholderId] = target.split(',');
                handleImageUpload('', imageId, placeholderId);
            });
        });

        // Handle time period buttons in Master Tracker
        document.querySelectorAll('[data-period]').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all period buttons
                document.querySelectorAll('[data-period]').forEach(b => 
                    b.classList.remove('active')
                );
                // Add active class to clicked button
                this.classList.add('active');
                
                const period = this.dataset.period;
                console.log(`Period changed to: ${period}`);
                // Here you would typically update the chart data based on the selected period
            });
        });
    });
</script>
@endsection

@section('content')
<div class="connection-container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 page-heading">Home Connection Management</h4>
                    <p class="text-muted mb-0">Manage customer connections and field operations</p>
                </div>
                <div>
                    <!-- Buttons moved to Connection tab -->
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-6">
            <div class="card stat-card border-0 shadow-sm h-100 overflow-hidden bg-gradient-1">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-white">
                            <h6 class="text-uppercase mb-1 fw-semibold small opacity-75">Total Connections</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalConnections ?? 0) }}</h2>
                            <div class="mt-2 d-flex align-items-center">
                                <span class="badge bg-white bg-opacity-25 px-2 py-1 small fw-normal">
                                    @if(isset($growthPercentage) && $growthPercentage != 0)
                                        <i class="fas fa-arrow-{{ $growthPercentage > 0 ? 'up' : 'down' }} me-1"></i> 
                                        {{ abs($growthPercentage) }}% from last month
                                    @else
                                        <i class="fas fa-minus me-1"></i> No change
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-home"></i>
                        </div>
                    </div>
                    <div class="stat-wave">
                        <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                            <path d="M0,100 C150,180 350,0 500,100 L500,150 L0,150 Z" style="opacity: 0.2;"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card border-0 shadow-sm h-100 overflow-hidden bg-gradient-2">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-white">
                            <h6 class="text-uppercase mb-1 fw-semibold small opacity-75">Active Teams</h6>
                            <h2 class="mb-0 fw-bold">{{ $activeTeams ?? 0 }}</h2>
                            <div class="mt-2 d-flex align-items-center">
                                <span class="badge bg-white bg-opacity-25 px-2 py-1 small fw-normal">
                                    <i class="fas fa-users me-1"></i> {{ $teamsOnField ?? 0 }} teams on field
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-wave">
                        <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                            <path d="M0,100 C150,180 350,0 500,100 L500,150 L0,150 Z" style="opacity: 0.2;"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card border-0 shadow-sm h-100 overflow-hidden bg-gradient-3">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-white">
                            <h6 class="text-uppercase mb-1 fw-semibold small opacity-75">Pending Requests</h6>
                            <h2 class="mb-0 fw-bold">{{ $pendingRequests ?? 0 }}</h2>
                            <div class="mt-2 d-flex align-items-center">
                                <span class="badge bg-white bg-opacity-25 px-2 py-1 small fw-normal">
                                    <i class="fas fa-clock me-1"></i> {{ $newPendingToday ?? 0 }} new today
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="stat-wave">
                        <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                            <path d="M0,100 C150,180 350,0 500,100 L500,150 L0,150 Z" style="opacity: 0.2;"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card border-0 shadow-sm h-100 overflow-hidden bg-gradient-4">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-white">
                            <h6 class="text-uppercase mb-1 fw-semibold small opacity-75">Satisfaction</h6>
                            <h2 class="mb-0 fw-bold">{{ $satisfactionRate ?? 0 }}%</h2>
                            <div class="mt-2 d-flex align-items-center">
                                <span class="badge bg-white bg-opacity-25 px-2 py-1 small fw-normal">
                                    <i class="fas fa-star me-1"></i> 
                                    @if(isset($satisfactionRate))
                                        @if($satisfactionRate >= 90)
                                            Excellent
                                        @elseif($satisfactionRate >= 75)
                                            Good
                                        @elseif($satisfactionRate >= 60)
                                            Average
                                        @else
                                            Needs Improvement
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="stat-wave">
                        <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                            <path d="M0,100 C150,180 350,0 500,100 L500,150 L0,150 Z" style="opacity: 0.2;"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="customers-tab" data-bs-toggle="tab" 
                                data-bs-target="#customers" type="button" role="tab" 
                                aria-controls="customers" aria-selected="true">
                                <i class="fas fa-users me-1"></i> Customers
                            </button>
                        </li>
                        <!-- 3. Map View Tab -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="map-tab" data-bs-toggle="tab" 
                                data-bs-target="#map-view" type="button" role="tab" 
                                aria-controls="map-view" aria-selected="false">
                                <i class="fas fa-map-marked-alt me-1"></i> Map View
                            </button>
                        </li>
                        <!-- 4. Team Registration Tab -->
                       <!-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="team-registration-tab" data-bs-toggle="tab" 
                                data-bs-target="#team-registration" type="button" role="tab" 
                                aria-controls="team-registration" aria-selected="false">
                                <i class="fas fa-user-plus me-1"></i> Team Registration
                            </button>
                        </li> -->
                        <!-- 5. Site Assignment and Issues Tab -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="site-issues-tab" data-bs-toggle="tab" 
                                data-bs-target="#site-issues" type="button" role="tab" 
                                aria-controls="site-issues" aria-selected="false">
                                <i class="fas fa-exclamation-triangle me-1"></i> Site Assignment & Issues
                            </button>
                        </li>
                        <!-- 6. Roster Management Tab -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="roster-management-tab" data-bs-toggle="tab" 
                                data-bs-target="#roster-management" type="button" role="tab" 
                                aria-controls="roster-management" aria-selected="false">
                                <i class="fas fa-calendar-alt me-1"></i> Roster Management
                            </button>
                        </li>
                
                        <!--
                        11. FAT/Sub-box Port Tab -
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="fat-port-tab" data-bs-toggle="tab" 
                                data-bs-target="#fat-port" type="button" role="tab" 
                                aria-controls="fat-port" aria-selected="false">
                                <i class="fas fa-network-wired me-1"></i> FAT/Sub-box Port
                            </button>
                        </li>
-->
                        
                        <!-- 12. Financial Tracking Tab -->
                     <!---   <li class="nav-item" role="presentation">
                            <button class="nav-link" id="financial-tracking-tab" data-bs-toggle="tab" 
                                data-bs-target="#financial-tracking" type="button" role="tab" 
                                aria-controls="financial-tracking" aria-selected="false">
                                <i class="fas fa-chart-line me-1"></i> Financial Tracking
                            </button>
                        </li>  --->
                    </ul>

                    <div class="tab-content mt-3" id="myTabContent">
                        <!-- 1. Customers Tab -->
                        <div class="tab-pane fade show active" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                            <!-- Debug: Customers variable: {{ isset($customers) ? count($customers) : 'Not set' }} -->
                            <!-- Debug: Active tab: {{ $activeTab ?? 'Not set' }} -->
                            <!-- Debug: All variables: {{ json_encode(get_defined_vars()) }} -->
                            @include('company.ProjectManagement.components.home-connection-tabs.customers', ['customers' => $customers ?? collect()])
                        </div>
                        <!-- 3. Map View Tab -->
                        <div class="tab-pane fade" id="map-view" role="tabpanel" aria-labelledby="map-view-tab">
                            @include('company.ProjectManagement.components.home-connection-tabs.map-view')
                        </div>
                        <!-- 4. Team Registration Tab -->
                        <div class="tab-pane fade" id="team-registration" role="tabpanel" aria-labelledby="team-registration-tab">
                            @include('company.ProjectManagement.components.home-connection-tabs.team-registration')
                        </div>
                        <!-- 5. Site Assignment and Issues Tab -->
                        <div class="tab-pane fade" id="site-issues" role="tabpanel" aria-labelledby="site-issues-tab">
                            @include('company.ProjectManagement.components.home-connection-tabs.site-assignment')
                        </div>
                        <!-- 6. Roster Management Tab -->
                        <div class="tab-pane fade" id="roster-management" role="tabpanel" aria-labelledby="roster-management-tab">
                            @include('company.ProjectManagement.components.home-connection-tabs.roster-management')
                        </div>
                        <!-- 11. FAT/Sub-box Port Tab -->
                        <div class="tab-pane fade" id="fat-port" role="tabpanel" aria-labelledby="fat-port-tab">
                            @include('company.ProjectManagement.components.home-connection-tabs.fat-port')
                        </div>
                        <!-- 12. Financial Tracking Tab -->
                        <div class="tab-pane fade" id="financial-tracking" role="tabpanel" aria-labelledby="financial-tracking-tab">
                            @include('company.ProjectManagement.components.home-connection-tabs.financial-tracking')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==============================================================
    MODAL DIALOGS
    ============================================================== -->
{{-- All modals are included here for better organization --}}
<!-- New Connection Modal -->
<div class="modal fade" id="newConnectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="fas fa-plus-circle me-2"></i>
                    Add New Connection
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="newConnectionForm" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <!-- Customer Information Section -->
                        <div class="col-12 mb-4">
                            <h6 class="text-uppercase text-muted mb-3">Customer Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">Full Name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-primary"></i></span>
                                            <input type="text" class="form-control" name="customer_name" placeholder="Enter full name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">Phone (MSISDN) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone-alt text-primary"></i></span>
                                            <input type="tel" class="form-control" name="msisdn" placeholder="e.g. 0244123456" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-primary"></i></span>
                                            <input type="email" class="form-control" name="email" placeholder="Enter email address">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Details Section -->
                        <div class="col-12 mb-4">
                            <h6 class="text-uppercase text-muted mb-3">Location Details</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">Location <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-marker-alt text-primary"></i></span>
                                            <input type="text" class="form-control" name="location" placeholder="e.g. East Legon" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">Area <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-marked-alt text-primary"></i></span>
                                            <input type="text" class="form-control" name="area" placeholder="e.g. Ashiyie" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">GPS Coordinates <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-pin text-primary"></i></span>
                                            <input type="text" class="form-control" name="gps_coordinates" placeholder="e.g. 5.6037, -0.1870" required>
                                            <button class="btn btn-outline-secondary" type="button" id="getLocationBtn">
                                                <i class="fas fa-location-arrow"></i> Get Location
                                            </button>
                                        </div>
                                        <small class="form-text text-muted">Format: Latitude, Longitude (e.g., 5.6037, -0.1870)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Network Information Section -->
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted mb-3">Network Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">FAT/Subbox <span class="text-danger">*</span></label>
                                        <select class="form-select" name="fat_subbox_name" required>
                                            <option value="" selected disabled>Select FAT/Subbox</option>
                                            <option value="FAT-001">FAT-001</option>
                                            <option value="FAT-002">FAT-002</option>
                                            <option value="Subbox-001">Subbox-001</option>
                                            <option value="Subbox-002">Subbox-002</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">FAT/Subbox Coordinates</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-marker-alt text-primary"></i></span>
                                            <input type="text" class="form-control" name="fat_subbox_coordinates" placeholder="e.g. 5.6037, -0.1870">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">Port <span class="text-danger">*</span></label>
                                        <select class="form-select" name="subbox_port" required>
                                            <option value="" selected disabled>Select Port</option>
                                            <option value="Port 1">Port 1</option>
                                            <option value="Port 2">Port 2</option>
                                            <option value="Port 3">Port 3</option>
                                            <option value="Port 4">Port 4</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">Connection Type <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-network-wired text-primary"></i></span>
                                            <select class="form-select" name="connection_type" required>
                                                <option value="" selected disabled>Select Type</option>
                                                <option value="Residential">Residential</option>
                                                <option value="Business">Business</option>
                                                <option value="Enterprise">Enterprise</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Information Section -->
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted mb-3">Additional Information</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">Full Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 align-items-start pt-2"><i class="fas fa-home text-primary mt-1"></i></span>
                                            <textarea class="form-control" name="full_address" rows="2" placeholder="Enter complete address"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-medium text-muted mb-1">Additional Notes</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 align-items-start pt-2"><i class="fas fa-sticky-note text-primary mt-1"></i></span>
                                            <textarea class="form-control" name="notes" rows="2" placeholder="Any additional information or special instructions"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-medium text-muted mb-1">Internet Plan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-tachometer-alt text-primary"></i></span>
                                    <select class="form-select" name="internet_plan" required>
                                        <option value="" selected disabled>Select Internet Plan</option>
                                        <option value="Basic - 10Mbps">Basic - 10Mbps</option>
                                        <option value="Standard - 25Mbps">Standard - 25Mbps</option>
                                        <option value="Premium - 50Mbps">Premium - 50Mbps</option>
                                        <option value="Business - 100Mbps">Business - 100Mbps</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-medium text-muted mb-1">Assigned Team <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-users text-primary"></i></span>
                                    <select class="form-select" name="assigned_team" required>
                                        <option value="" selected disabled>Select Team</option>
                                        <option value="Team A">Installation Team A</option>
                                        <option value="Team B">Installation Team B</option>
                                        <option value="Team C">Support Team</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Connection
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="fas fa-file-upload me-2"></i>
                    Bulk Upload Connections
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Download the template file and fill in the required information. Then upload the completed file below.
                </div>
                
                <div class="text-center mb-4">
                    <a href="#" class="btn btn-outline-primary" id="downloadTemplate">
                        <i class="fas fa-file-excel me-2"></i> Download Template
                    </a>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-medium">Upload Excel File <span class="text-danger">*</span></label>
                    <div class="dropzone border rounded p-5 text-center" id="excelDropzone">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                        <h5>Drag & drop your Excel file here</h5>
                        <p class="text-muted">or</p>
                        <button type="button" class="btn btn-primary">Browse Files</button>
                        <input type="file" id="excelFile" accept=".xlsx, .xls" class="d-none">
                    </div>
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>
                        Supported formats: .xlsx, .xls (Max 5MB)
                    </div>
                </div>
                
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Required Fields:</strong> MSISDN, Customer Name, Location, Area, GPS Coordinates, FAT/Subbox Name, FAT/Subbox Coordinates, Subbox Port
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-upload me-1"></i> Upload File
                </button>
            </div>
        </div>
    </div>
</div>


<!-- View Customer Modal -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="fas fa-user-circle me-2"></i>
                    Customer Profile
                </h5>
                <div class="d-flex align-items-center">
                    <span class="badge bg-white text-primary me-3">#CUS-001</span>
                    <button type="button" class="btn-close btn-close-white m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            
            <!-- Body -->
            <div class="modal-body p-0">
                <!-- Profile Header -->
                <div class="bg-light p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-xxl position-relative">
                                <span class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle display-5">
                                    JD
                                </span>
                                <span class="position-absolute bottom-0 end-0 p-1 bg-success rounded-circle border border-3 border-white"></span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <h3 class="mb-1">John Doe</h3>
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-circle me-1 small"></i> Active
                                </span>
                                <span class="ms-2 text-muted">Member since June 2025</span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="tel:0244123456" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-phone-alt me-1"></i> Call
                                </a>
                                <a href="mailto:john.doe@example.com" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-envelope me-1"></i> Email
                                </a>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-sms me-1"></i> SMS
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs nav-tabs-custom px-4 pt-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#overview" role="tab">
                            <i class="fas fa-home me-1"></i> Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#connection" role="tab">
                            <i class="fas fa-plug me-1"></i> Connection
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content p-4">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Personal Information</h6>
                                <dl class="row mb-0">
                                    <dt class="col-sm-4 text-muted">Full Name:</dt>
                                    <dd class="col-sm-8">John Doe</dd>
                                    
                                    <dt class="col-sm-4 text-muted">Email:</dt>
                                    <dd class="col-sm-8">john.doe@example.com</dd>
                                    
                                    <dt class="col-sm-4 text-muted">Phone:</dt>
                                    <dd class="col-sm-8">+233 24 412 3456</dd>
                                    
                                    <dt class="col-sm-4 text-muted">Address:</dt>
                                    <dd class="col-sm-8">
                                        123 Main Street<br>
                                        East Legon, Accra<br>
                                        Ghana
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Account Information</h6>
                                <dl class="row mb-0">
                                    <dt class="col-sm-4 text-muted">Customer ID:</dt>
                                    <dd class="col-sm-8">#CUS-001</dd>
                                    
                                    <dt class="col-sm-4 text-muted">Join Date:</dt>
                                    <dd class="col-sm-8">June 15, 2025</dd>
                                    
                                    <dt class="col-sm-4 text-muted">Status:</dt>
                                    <dd class="col-sm-8"><span class="badge bg-success">Active</span></dd>
                                    
                                    <dt class="col-sm-4 text-muted">Last Login:</dt>
                                    <dd class="col-sm-8">Today, 14:30</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Connection Tab -->
                    <div class="tab-pane fade" id="connection" role="tabpanel">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Connection Active</strong> - All systems operational
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                <i class="fas fa-tachometer-alt"></i>
                                            </div>
                                        </div>
                                        <h5>50 Mbps</h5>
                                        <p class="text-muted mb-0">Download Speed</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-soft-info text-info rounded-circle">
                                                <i class="fas fa-upload"></i>
                                            </div>
                                        </div>
                                        <h5>25 Mbps</h5>
                                        <p class="text-muted mb-0">Upload Speed</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-soft-warning text-warning rounded-circle">
                                                <i class="fas fa-network-wired"></i>
                                            </div>
                                        </div>
                                        <h5>192.168.1.100</h5>
                                        <p class="text-muted mb-0">IP Address</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-soft-success text-success rounded-circle">
                                                <i class="fas fa-wifi"></i>
                                            </div>
                                        </div>
                                        <h5>Home Unlimited</h5>
                                        <p class="text-muted mb-0">Package</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit Profile
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Stats Cards Styling */
    .stat-card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.8;
        color: rgba(255,255,255,0.9);
    }
    
    .stat-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40%;
        overflow: hidden;
    }
    
    .stat-wave svg {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 100%;
    }
    
    .bg-gradient-1 {
        background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
    }
    
    .bg-gradient-2 {
        background: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
    }
    
    .bg-gradient-3 {
        background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);
    }
    
    .bg-gradient-4 {
        background: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
    }
    
    .stat-card h2 {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    
    .stat-card h6 {
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }
    
    .stat-card .badge {
        font-size: 0.65rem;
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    @media (max-width: 768px) {
        .stat-card h2 {
            font-size: 1.5rem;
        }
        .stat-icon {
            font-size: 2rem;
        }
    }
    
    .nav-tabs-custom .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        border-bottom: 2px solid transparent;
    }
    
    .nav-tabs-custom .nav-link.active {
        color: #0d6efd;
        background: transparent;
        border-color: #0d6efd;
    }
    
    .nav-tabs-custom .nav-link i {
        margin-right: 0.5rem;
    }
    
    .avatar-xxl {
        width: 100px;
        height: 100px;
        font-size: 2.5rem;
    }
    
    .avatar-lg {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-soft-primary {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    
    .bg-soft-success {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    
    .bg-soft-info {
        background-color: rgba(13, 202, 240, 0.1) !important;
    }
    
    .bg-soft-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
</style>


<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Customer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCustomerForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter full name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" placeholder="Enter email address" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" placeholder="Enter phone number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" value="0244123456" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="john.doe@example.com">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" rows="2">123 East Legon Street, Accra, Ghana</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Connection Type</label>
                            <select class="form-select">
                                <option>Residential</option>
                                <option>Business</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option>Active</option>
                                <option>Pending</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Connection Status Modal -->
<div class="modal fade" id="connectionStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="fas fa-plug me-2"></i>
                    Connection Status
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Status Overview -->
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block mb-3">
                        <div class="status-indicator bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    <h5 class="mb-1">Connection is Active</h5>
                    <p class="text-muted mb-0">Last updated: August 12, 2025 20:30</p>
                </div>

                <!-- Status Form -->
                <form>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Update Status</label>
                        <div class="d-grid gap-2">
                            <div class="btn-group-vertical" role="group">
                                <input type="radio" class="btn-check" name="status" id="statusActive" autocomplete="off" checked>
                                <label class="btn btn-outline-success text-start mb-2" for="statusActive">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <div>
                                            <div class="fw-bold">Active</div>
                                            <small class="text-muted">Connection is working normally</small>
                                        </div>
                                    </div>
                                </label>

                                <input type="radio" class="btn-check" name="status" id="statusDisconnected" autocomplete="off">
                                <label class="btn btn-outline-danger text-start mb-2" for="statusDisconnected">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-plug-circle-xmark me-2"></i>
                                        <div>
                                            <div class="fw-bold">Disconnected</div>
                                            <small class="text-muted">Connection is down</small>
                                        </div>
                                    </div>
                                </label>

                                <input type="radio" class="btn-check" name="status" id="statusMaintenance" autocomplete="off">
                                <label class="btn btn-outline-warning text-start mb-2" for="statusMaintenance">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tools me-2"></i>
                                        <div>
                                            <div class="fw-bold">Maintenance</div>
                                            <small class="text-muted">Scheduled maintenance</small>
                                        </div>
                                    </div>
                                </label>

                                <input type="radio" class="btn-check" name="status" id="statusSuspended" autocomplete="off">
                                <label class="btn btn-outline-secondary text-start" for="statusSuspended">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-pause-circle me-2"></i>
                                        <div>
                                            <div class="fw-bold">Suspended</div>
                                            <small class="text-muted">Account suspended</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea class="form-control" rows="3" placeholder="Add details about the status change..."></textarea>
                        <div class="form-text">This note will be visible in the connection history.</div>
                    </div>

                    <div class="alert alert-light bg-light border">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-primary mt-1"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="alert-heading">Status Information</h6>
                                <p class="small mb-0">Updating the status will notify the customer and log this change in the connection history. Please provide clear details about any issues or changes.</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-sync-alt me-1"></i> Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .status-indicator {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        margin: 0 auto;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    
    .btn-group-vertical > .btn {
        text-align: left;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem !important;
        margin-bottom: 0.5rem;
        transition: all 0.2s;
    }
    
    .btn-group-vertical > .btn:last-child {
        margin-bottom: 0;
    }
    
    .btn-group-vertical > .btn i {
        font-size: 1.25rem;
        width: 24px;
        text-align: center;
    }
    
    .btn-check:checked + .btn {
        border-width: 2px;
        border-color: var(--bs-primary);
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .btn-outline-success:not(:disabled):not(.disabled).active, 
    .btn-outline-success:not(:disabled):not(.disabled):active {
        color: #198754;
        background-color: rgba(25, 135, 84, 0.1);
    }
    
    .btn-outline-danger:not(:disabled):not(.disabled).active, 
    .btn-outline-danger:not(:disabled):not(.disabled):active {
        color: #dc3545;
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .btn-outline-warning:not(:disabled):not(.disabled).active, 
    .btn-outline-warning:not(:disabled):not(.disabled):active {
        color: #fd7e14;
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .btn-outline-secondary:not(:disabled):not(.disabled).active, 
    .btn-outline-secondary:not(:disabled):not(.disabled):active {
        color: #6c757d;
        background-color: rgba(108, 117, 125, 0.1);
    }
</style>


<!-- View History Modal -->
<div class="modal fade" id="viewHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="fas fa-history me-2"></i>
                    Connection History
                    <span class="badge bg-white text-primary ms-2">3 Events</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="timeline p-4">
                    <!-- Timeline Item 1 -->
                    <div class="timeline-item position-relative mb-4">
                        <div class="timeline-badge bg-success d-flex align-items-center justify-content-center">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-content p-3 bg-soft-success rounded">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold text-success">
                                    <i class="fas fa-plug me-2"></i>Connection Activated
                                </h6>
                                <span class="badge bg-success-soft">Completed</span>
                            </div>
                            <p class="text-muted small mb-2">
                                <i class="far fa-clock me-1"></i> August 10, 2025 14:30
                            </p>
                            <p class="mb-0">Connection successfully activated after installation. All systems are functioning normally.</p>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark border me-1">
                                    <i class="fas fa-user me-1"></i> Tech: John D.
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-hashtag me-1"></i> #TKT-00452
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Item 2 -->
                    <div class="timeline-item position-relative mb-4">
                        <div class="timeline-badge bg-info d-flex align-items-center justify-content-center">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="timeline-content p-3 bg-soft-info rounded">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold text-info">
                                    <i class="far fa-calendar-check me-2"></i>Installation Scheduled
                                </h6>
                                <span class="badge bg-info-soft">Scheduled</span>
                            </div>
                            <p class="text-muted small mb-2">
                                <i class="far fa-clock me-1"></i> August 5, 2025 10:15
                            </p>
                            <p class="mb-0">Installation scheduled for August 10, 2025 between 10:00 AM - 2:00 PM</p>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark border me-1">
                                    <i class="fas fa-phone me-1"></i> Confirmed
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Item 3 -->
                    <div class="timeline-item position-relative">
                        <div class="timeline-badge bg-primary d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="timeline-content p-3 bg-soft-primary rounded">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="fas fa-plus-circle me-2"></i>New Connection Request
                                </h6>
                                <span class="badge bg-primary-soft">Received</span>
                            </div>
                            <p class="text-muted small mb-2">
                                <i class="far fa-clock me-1"></i> August 1, 2025 09:30
                            </p>
                            <p class="mb-0">New connection request received through online portal. Initial verification completed.</p>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark border me-1">
                                    <i class="fas fa-user-tie me-1"></i> Agent: Sarah M.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <div class="d-flex justify-content-between w-100">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i> Last updated: 5 minutes ago
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Close
                        </button>
                        <button type="button" class="btn btn-primary">
                            <i class="fas fa-download me-1"></i> Export History
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this to your existing CSS -->
<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 1.5rem;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-badge {
        position: absolute;
        left: -2.5rem;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        z-index: 1;
        border: 3px solid white;
        box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
    }
    
    .timeline-content {
        position: relative;
        margin-left: 1rem;
    }
    
    .bg-soft-success {
        background-color: rgba(25, 135, 84, 0.1) !important;
        border-left: 3px solid #198754;
    }
    
    .bg-soft-info {
        background-color: rgba(13, 202, 240, 0.1) !important;
        border-left: 3px solid #0dcaf0;
    }
    
    .bg-soft-primary {
        background-color: rgba(13, 110, 253, 0.1) !important;
        border-left: 3px solid #0d6efd;
    }
    
    .bg-success-soft {
        background-color: rgba(25, 135, 84, 0.2) !important;
        color: #198754 !important;
    }
    
    .bg-info-soft {
        background-color: rgba(13, 202, 240, 0.2) !important;
        color: #0dcaf0 !important;
    }
    
    .bg-primary-soft {
        background-color: rgba(13, 110, 253, 0.2) !important;
        color: #0d6efd !important;
    }
</style>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5>Are you sure?</h5>
                    <p class="text-muted">You are about to delete customer <strong>John Doe (CUS-001)</strong>. This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Yes, Delete Customer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle file selection
        $('#excelFile').on('change', function() {
            const file = this.files[0];
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                const fileExt = fileName.split('.').pop().toLowerCase();
                
                if (fileExt !== 'xlsx' && fileExt !== 'xls') {
                    alert('Only Excel files are allowed.');
                    $(this).val('');
                    return;
                }
                
                if (fileSize > 5) {
                    alert('File size should not exceed 5MB');
                    $(this).val('');
                    return;
                }
                
                // Update UI to show selected file
                $('#excelDropzone').html(`
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-3x mb-2"></i>
                        <h5>${fileName}</h5>
                        <p class="text-muted mb-0">${fileSize} MB</p>
                    </div>
                `);
                
                $('#uploadExcelBtn').prop('disabled', false);
            }
        });
        
        // Handle dropzone click
        $('.dropzone').on('click', function() {
            $('#excelFile').click();
        });
        
        // Handle drag and drop
        $('.dropzone').on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('border-primary bg-light');
        });
        
        $('.dropzone').on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('border-primary bg-light');
        });
        
        $('.dropzone').on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('border-primary bg-light');
            
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                $('#excelFile')[0].files = files;
                $('#excelFile').trigger('change');
            }
        });
        
        // Handle template download
        $('#downloadTemplate').on('click', function(e) {
            e.preventDefault();
            // This would trigger a server-side route to download the template
            window.location.href = '';
        });
        
        // Handle upload
        $('#uploadExcelBtn').on('click', function() {
            const fileInput = $('#excelFile')[0];
            if (fileInput.files.length === 0) return;
            
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            
            // Show loading state
            const $btn = $(this);
            const originalText = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Uploading...');
            
            // This would be an AJAX call to your server
            /*
            $.ajax({
                url: '',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Handle success
                    alert('File uploaded successfully!');
                    $('#bulkUploadModal').modal('hide');
                    // Refresh the table or update UI as needed
                    // customersTable.ajax.reload();
                },
                error: function(xhr) {
                    // Handle error
                    alert('Error uploading file: ' + (xhr.responseJSON?.message || 'Unknown error'));
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalText);
                }
            });
            */
            
            // For demo purposes
            setTimeout(() => {
                alert('File uploaded successfully! (Demo)');
                $btn.prop('disabled', false).html(originalText);
                $('#bulkUploadModal').modal('hide');
                // Reset the form
                $('#excelFile').val('');
                $('#excelDropzone').html(`
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                    <h5>Drag & drop your Excel file here</h5>
                    <p class="text-muted">or</p>
                    <button type="button" class="btn btn-primary">Browse Files</button>
                `);
            }, 1500);
        });
    });
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
   integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
   crossorigin=""></script>
<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Field Updates Tab Functionality
        // Toggle binding status text
        $('#bindingStatus').on('change', function() {
            const statusText = $('#bindingStatusText');
            if ($(this).is(':checked')) {
                statusText.text('Bound').addClass('text-success fw-bold');
            } else {
                statusText.text('Not Bound').removeClass('text-success fw-bold');
            }
        });

        // Toggle subbox upload section
        $('#subboxScanned').on('change', function() {
            $('#subboxUploadSection').toggleClass('d-none', !$(this).is(':checked'));
        });

        // Handle save for offline functionality
        $('#saveOffline').on('click', function() {
            // Check if browser supports service workers and cache API
            if ('serviceWorker' in navigator && 'caches' in window) {
                // In a real app, you would save form data to IndexedDB here
                alert('Form data will be saved for offline submission. You can submit when you have an internet connection.');
            } else {
                alert('Offline saving is not supported in your browser. Please check your internet connection.');
            }
        });

        // Handle file upload preview (simplified example)
        $('input[type="file"]').on('change', function(e) {
            if (this.files.length > 0) {
                console.log(`${this.files.length} file(s) selected for upload`);
                // In a real app, you would handle file previews here
            }
        });

        // Handle form submissions
        $('#fieldUpdateForm').on('submit', function(e) {
            e.preventDefault();
            // Add form submission logic here
            alert('Field update saved successfully!');
        });

        $('#ontInstallationForm').on('submit', function(e) {
            e.preventDefault();
            // Add form submission logic here
            alert('ONT installation details saved successfully!');
        });
        
        // Simple table initialization - no DataTable for now
        console.log('Customers table loaded');

        // Initialize map when the map tab is shown
        var map;
        var mapInitialized = false;
        
        function initMap() {
            // Remove the loading spinner
            $('#map').html('');
            
            // Initialize the map
            map = L.map('map', {
                center: [5.6037, -0.1870],
                zoom: 13,
                zoomControl: true,
                preferCanvas: true
            });
            
            // Add OpenStreetMap tiles with error handling
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
                errorTileUrl: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'
            }).addTo(map);
            
            // Add a marker
            L.marker([5.6037, -0.1870])
                .addTo(map)
                .bindPopup('Sample Location<br>Accra, Ghana')
                .openPopup();
            
            // Force a resize to ensure the map renders correctly
            setTimeout(function() {
                map.invalidateSize();
            }, 100);
                
            mapInitialized = true;
        }
        
        // Save active tab to localStorage when tab is clicked
        document.querySelectorAll('#myTab button[data-bs-toggle="tab"]').forEach(button => {
            button.addEventListener('shown.bs.tab', function (event) {
                const activeTabId = event.target.id;
                localStorage.setItem('homeConnectionActiveTab', activeTabId);
            });
        });
        
        // Restore active tab on page load
        const savedTab = localStorage.getItem('homeConnectionActiveTab');
        if (savedTab) {
            const tabButton = document.getElementById(savedTab);
            if (tabButton) {
                const tab = new bootstrap.Tab(tabButton);
                tab.show();
            }
        }
        
        // Handle tab switching
        $('#map-tab').on('shown.bs.tab', function (e) {
            if (!mapInitialized) {
                initMap();
            } else if (map) {
                // If map is already initialized, just invalidate size
                setTimeout(function() {
                    map.invalidateSize();
                }, 100);
            }
        });
        
        // Also try to initialize map on page load if we're already on the map tab
        if (window.location.hash === '#map-view' || 
            (document.querySelector('.nav-link.active') && 
             document.querySelector('.nav-link.active').getAttribute('aria-controls') === 'map-view')) {
            // Small delay to ensure the tab is fully shown
            setTimeout(initMap, 300);
        }

        // Manual Override Tab Functionality
        $('#applyOverride').on('click', function() {
            const team = $('#overrideTeam').val();
            const startDate = $('#overrideStartDate').val();
            const endDate = $('#overrideEndDate').val();
            const reason = $('#overrideReason').val();
            const scheduleType = $('input[name="scheduleType"]:checked').attr('id');
            
            // Basic validation
            if (!team) {
                alert('Please select a team');
                return;
            }
            
            if (!startDate || !endDate) {
                alert('Please select a date range');
                return;
            }
            
            if (!reason) {
                alert('Please provide a reason for this override');
                return;
            }
            
            // Format dates for display
            const startDateFormatted = new Date(startDate).toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric',
                year: 'numeric' 
            });
            
            const endDateFormatted = new Date(endDate).toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric',
                year: 'numeric' 
            });
            
            // Get schedule type label
            let typeLabel = '';
            let typeClass = '';
            
            switch(scheduleType) {
                case 'workDay':
                    typeLabel = 'Work Day';
                    typeClass = 'success';
                    break;
                case 'dayOff':
                    typeLabel = 'Day Off';
                    typeClass = 'secondary';
                    break;
                case 'sickLeave':
                    typeLabel = 'Sick Leave';
                    typeClass = 'warning';
                    break;
                case 'training':
                    typeLabel = 'Training';
                    typeClass = 'info';
                    break;
            }
            
            // Set values in confirmation modal
            $('#overrideTeamName').text($('#overrideTeam option:selected').text());
            $('#overrideDateRange').text(startDateFormatted + (startDate !== endDate ? ' - ' + endDateFormatted : ''));
            $('#overrideType').html(`<span class="badge bg-${typeClass}">${typeLabel}</span>`);
            $('#overrideReasonText').text(reason);
            
            // Show confirmation modal
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmOverrideModal'));
            confirmModal.show();
        });
        
        // Handle confirm override
        $('#confirmOverride').on('click', function() {
            // Here you would typically make an AJAX call to save the override
            // For now, we'll just show a success message
            
            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmOverrideModal'));
            modal.hide();
            
            // Show success message
            alert('Schedule override has been applied successfully');
            
            // Reset the form
            $('#overrideReason').val('');
            $('#workDay').prop('checked', true);
            
            // In a real app, you would update the upcoming overrides table here
            // and add the new entry to the change log
        });
        
        // Set default end date to start date
        $('#overrideStartDate').on('change', function() {
            if (!$('#overrideEndDate').val()) {
                $('#overrideEndDate').val($(this).val());
            }
        });
    });
</script>
@endpush


<!-- Generate Report Modal -->
<div class="modal fade" id="generateReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-invoice me-2"></i> Generate Financial Report
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="financialReportForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Report Type <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="" selected disabled>Select report type</option>
                                <option value="monthly">Monthly Financial Report</option>
                                <option value="quarterly">Quarterly Financial Report</option>
                                <option value="team_performance">Team Performance Report</option>
                                <option value="cost_analysis">Cost Analysis Report</option>
                                <option value="revenue_breakdown">Revenue Breakdown</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date Range <span class="text-danger">*</span></label>
                            <select class="form-select" id="dateRangeSelect" required>
                                <option value="" selected disabled>Select date range</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="this_quarter">This Quarter</option>
                                <option value="last_quarter">Last Quarter</option>
                                <option value="this_year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-6 custom-date-range d-none">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate">
                        </div>
                        <div class="col-md-6 custom-date-range d-none">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Teams</label>
                            <select class="form-select" multiple>
                                <option value="all" selected>All Teams</option>
                                <option value="alpha">Alpha Team</option>
                                <option value="beta">Beta Team</option>
                                <option value="gamma">Gamma Team</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Report Format</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reportFormat" id="formatPdf" value="pdf" checked>
                                <label class="form-check-label" for="formatPdf">
                                    <i class="far fa-file-pdf text-danger me-1"></i> PDF Document
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reportFormat" id="formatExcel" value="excel">
                                <label class="form-check-label" for="formatExcel">
                                    <i class="far fa-file-excel text-success me-1"></i> Excel Spreadsheet
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reportFormat" id="formatCsv" value="csv">
                                <label class="form-check-label" for="formatCsv">
                                    <i class="fas fa-file-csv text-info me-1"></i> CSV File
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="includeCharts" checked>
                                <label class="form-check-label" for="includeCharts">
                                    Include charts and visualizations
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="emailReport" checked>
                                <label class="form-check-label" for="emailReport">
                                    Email report to my email address
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-export me-1"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Export Data Modal -->
<div class="modal fade" id="exportDataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-export me-2"></i> Export Financial Data
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="exportDataForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Data to Export <span class="text-danger">*</span></label>
                        <select class="form-select" required>
                            <option value="" selected disabled>Select data to export</option>
                            <option value="revenue">Revenue Data</option>
                            <option value="costs">Cost Data</option>
                            <option value="team_performance">Team Performance</option>
                            <option value="all">Complete Financial Data</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <select class="form-select">
                            <option value="all" selected>All Time</option>
                            <option value="this_month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="this_quarter">This Quarter</option>
                            <option value="last_quarter">Last Quarter</option>
                            <option value="this_year">This Year</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Export Format <span class="text-danger">*</span></label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="exportFormat" id="exportExcel" value="excel" checked>
                            <label class="form-check-label" for="exportExcel">
                                <i class="far fa-file-excel text-success me-1"></i> Excel (.xlsx)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="exportFormat" id="exportCsv" value="csv">
                            <label class="form-check-label" for="exportCsv">
                                <i class="fas fa-file-csv text-info me-1"></i> CSV (.csv)
                            </label>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="includeHeaders" checked>
                        <label class="form-check-label" for="includeHeaders">
                            Include column headers
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download me-1"></i> Export Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Cost Adjustment Modal -->
<div class="modal fade" id="costAdjustmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-adjust me-2"></i> Adjust Cost Entry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="costAdjustmentForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Adjusting costs will update the financial records. Please provide a reason for this adjustment.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Cost Type <span class="text-danger">*</span></label>
                        <select class="form-select" required>
                            <option value="" selected disabled>Select cost type</option>
                            <option value="labor">Labor</option>
                            <option value="materials">Materials</option>
                            <option value="equipment">Equipment</option>
                            <option value="transportation">Transportation</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Current Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">GHS</span>
                                <input type="text" class="form-control" value="1,250.00" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">GHS</span>
                                <input type="number" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 mt-3">
                        <label class="form-label">Adjustment Reason <span class="text-danger">*</span></label>
                        <select class="form-select mb-2" required>
                            <option value="" selected disabled>Select a reason</option>
                            <option value="price_correction">Price Correction</option>
                            <option value="quantity_change">Quantity Change</option>
                            <option value="discount_applied">Discount Applied</option>
                            <option value="tax_adjustment">Tax Adjustment</option>
                            <option value="other">Other</option>
                        </select>
                        <textarea class="form-control mt-2" rows="3" placeholder="Please provide details about this adjustment..." required></textarea>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="notifyTeam" checked>
                        <label class="form-check-label" for="notifyTeam">
                            Notify team members about this adjustment
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Save Adjustment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Financial Notes Modal -->
<div class="modal fade" id="financialNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-sticky-note me-2"></i> Financial Notes
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="d-flex flex-column h-100">
                    <!-- Notes List -->
                    <div class="border-bottom p-3" style="max-height: 300px; overflow-y: auto;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Recent Notes</h6>
                            <button class="btn btn-sm btn-outline-primary" id="addNewNoteBtn">
                                <i class="fas fa-plus me-1"></i> New Note
                            </button>
                        </div>
                        
                        <div class="list-group list-group-flush" id="notesList">
                            <!-- Note Item 1 -->
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold">Q3 Budget Review</h6>
                                    <small class="text-muted">2 days ago</small>
                                </div>
                                <p class="mb-1">Budget for Q3 needs to be reviewed due to unexpected material cost increases. Need to adjust by ~15%.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">By: Admin User</small>
                                    <div>
                                        <button class="btn btn-sm btn-link text-primary p-0 me-2">
                                            <i class="far fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-link text-danger p-0">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Note Item 2 -->
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold">Team Performance</h6>
                                    <small class="text-muted">1 week ago</small>
                                </div>
                                <p class="mb-1">Alpha team has exceeded their cost allocation for the month. Need to review their expenses.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">By: Finance Team</small>
                                    <div>
                                        <button class="btn btn-sm btn-link text-primary p-0 me-2">
                                            <i class="far fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-link text-danger p-0">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add/Edit Note Form -->
                    <div class="p-3 d-none" id="noteFormContainer">
                        <h6 class="mb-3" id="noteFormTitle">Add New Note</h6>
                        <form id="financialNoteForm">
                            <input type="hidden" id="noteId">
                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="noteTitle" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Note <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="noteContent" rows="4" required></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary me-2" id="cancelNoteBtn">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Note
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle bulk upload form submission for connections
    $('#bulkUploadConnectionsForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const progressBar = $('#uploadConnectionsProgress');
        const uploadStatus = $('#uploadConnectionsStatus');
        const uploadButton = $(this).find('button[type="submit"]');
        const cancelButton = $('.cancel-connections-upload');
        
        // Reset status and show progress
        progressBar.removeClass('d-none').find('.progress-bar').width('0%').text('0%');
        uploadStatus.removeClass('d-none').html('<i class="fas fa-spinner fa-spin me-2"></i> Uploading file...');
        uploadButton.prop('disabled', true);
        cancelButton.prop('disabled', false);
        
        // AJAX request for file upload
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressBar.find('.progress-bar')
                            .width(percent + '%')
                            .text(percent + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    uploadStatus.html(`
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            ${response.message || 'File uploaded successfully!'}
                            <div class="mt-2">
                                <strong>Processed:</strong> ${response.processed || 0} | 
                                <strong>Created:</strong> ${response.created || 0} | 
                                <strong>Updated:</strong> ${response.updated || 0} | 
                                <strong>Failed:</strong> ${response.failed || 0}
                            </div>
                            ${response.errors && response.errors.length ? 
                                `<div class="mt-2">
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#errorDetails">
                                        Show error details
                                    </button>
                                    <div class="collapse mt-2" id="errorDetails">
                                        <div class="card card-body small">
                                            <pre class="mb-0">${JSON.stringify(response.errors, null, 2)}</pre>
                                        </div>
                                    </div>
                                </div>` : ''
                            }
                        </div>
                    `);
                    
                    // Show the upload summary
                    $('#uploadSummary').removeClass('d-none');
                    
                    // Update summary values
                    if (response.summary) {
                        $('#totalProcessed').text(response.summary.processed || 0);
                        $('#totalCreated').text(response.summary.created || 0);
                        $('#totalUpdated').text(response.summary.updated || 0);
                        $('#totalFailed').text(response.summary.failed || 0);
                    }
                    
                    // Reload the connections table if it exists
                    if (typeof connectionsTable !== 'undefined') {
                        connectionsTable.ajax.reload(null, false);
                    } else {
                        // Fallback to page reload
                        setTimeout(() => location.reload(), 2000);
                    }
                } else {
                    // Show error message
                    uploadStatus.html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            ${response.message || 'An error occurred while uploading the file.'}
                            ${response.errors ? 
                                `<div class="mt-2 small">${Array.isArray(response.errors) ? 
                                    response.errors.join('<br>') : 
                                    JSON.stringify(response.errors, null, 2)}
                                </div>` : ''
                            }
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while uploading the file.';
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    } else if (response.errors) {
                        errorMessage = Object.values(response.errors).flat().join(' ');
                    }
                } catch (e) {
                    console.error('Error parsing error response:', e);
                }
                
                uploadStatus.html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        ${errorMessage}
                    </div>
                `);
            },
            complete: function() {
                uploadButton.prop('disabled', false);
                cancelButton.prop('disabled', true);
            }
        });
    });
    
    // Handle cancel upload
    $('.cancel-connections-upload').on('click', function() {
        // This would need proper abort handling in a real application
        // For now, we'll just reset the form and hide the progress
        $('#bulkUploadConnectionsForm')[0].reset();
        $('#uploadConnectionsProgress').addClass('d-none');
        $('#uploadConnectionsStatus').addClass('d-none').html('');
        $(this).prop('disabled', true);
    });
    
    // Handle file input change for connections
    $('#connectionsExcelFile').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        const fileSize = this.files[0] ? (this.files[0].size / 1024 / 1024).toFixed(2) + ' MB' : '';
        const fileLabel = $(this).siblings('.custom-file-label');
        
        if (fileName) {
            fileLabel.html(`
                <i class="fas fa-file-excel text-success me-2"></i>
                ${fileName}
                <small class="text-muted d-block">${fileSize}</small>
            `);
        } else {
            fileLabel.html('Choose file...');
        }
    });
    
    // Download template for connections
    $('.download-connections-template').on('click', function(e) {
        e.preventDefault();
        window.location.href = '';
    });
    
    // Reset form when modal is closed
    $('#bulkUploadConnectionsModal').on('hidden.bs.modal', function () {
        $('#bulkUploadConnectionsForm')[0].reset();
        $('#uploadConnectionsProgress').addClass('d-none');
        $('#uploadConnectionsStatus').addClass('d-none').html('');
        $('#bulkUploadConnectionsForm .custom-file-label').html('Choose file...');
        $('.cancel-connections-upload').prop('disabled', true);
        $('#uploadSummary').addClass('d-none');
    });

    // Toggle custom date range fields
    $('#dateRangeSelect').on('change', function() {
            if ($(this).val() === 'custom') {
                $('.custom-date-range').removeClass('d-none');
            } else {
                $('.custom-date-range').addClass('d-none');
            }
        });

        // Initialize Select2 for multi-select dropdowns
    $('select[multiple]').select2({
            placeholder: 'Select options',
            width: '100%',
            closeOnSelect: false
        });

        // Toggle note form visibility
        $('#addNewNoteBtn').on('click', function() {
            $('#noteFormTitle').text('Add New Note');
            $('#financialNoteForm')[0].reset();
            $('#noteId').val('');
            $('#notesList').addClass('d-none');
            $('#noteFormContainer').removeClass('d-none');
        });

        $('#cancelNoteBtn').on('click', function() {
            $('#notesList').removeClass('d-none');
            $('#noteFormContainer').addClass('d-none');
        });

        // Handle note form submission
        $('#financialNoteForm').on('submit', function(e) {
            e.preventDefault();
            // Add your form submission logic here
            const title = $('#noteTitle').val();
            const content = $('#noteContent').val();
            
            // Example: Add the new note to the list
            const noteId = $('#noteId').val() || Date.now();
            const noteHtml = `
                <div class="list-group-item border-0 px-0 py-3" id="note-${noteId}">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1 fw-bold">${title}</h6>
                        <small class="text-muted">Just now</small>
                    </div>
                    <p class="mb-1">${content}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">By: Current User</small>
                        <div>
                            <button class="btn btn-sm btn-link text-primary p-0 me-2 edit-note" data-id="${noteId}">
                                <i class="far fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-link text-danger p-0 delete-note" data-id="${noteId}">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            if ($('#noteId').val()) {
                $(`#note-${noteId}`).replaceWith(noteHtml);
            } else {
                $('#notesList').prepend(noteHtml);
            }
            
            // Reset form and show list
            $('#financialNoteForm')[0].reset();
            $('#noteId').val('');
            $('#notesList').removeClass('d-none');
            $('#noteFormContainer').addClass('d-none');
            
            // Show success message
            alert('Note saved successfully!');
        });

        // Handle edit note
        $(document).on('click', '.edit-note', function() {
            const noteId = $(this).data('id');
            const note = $(`#note-${noteId}`);
            const title = note.find('h6').text();
            const content = note.find('p').text();
            
            $('#noteFormTitle').text('Edit Note');
            $('#noteId').val(noteId);
            $('#noteTitle').val(title);
            $('#noteContent').val(content);
            
            $('#notesList').addClass('d-none');
            $('#noteFormContainer').removeClass('d-none');
        });

        // Handle delete note
        $(document).on('click', '.delete-note', function() {
            if (confirm('Are you sure you want to delete this note?')) {
                const noteId = $(this).data('id');
                $(`#note-${noteId}`).remove();
                // Add your delete logic here
                alert('Note deleted successfully!');
            }
        });

        // Form submission handlers
        $('#financialReportForm, #exportDataForm, #costAdjustmentForm').on('submit', function(e) {
            e.preventDefault();
            const formId = $(this).attr('id');
            const modalId = `#${formId.replace('Form', 'Modal')}`;
            
            // Simulate form submission
            console.log(`Form submitted: ${formId}`, $(this).serialize());
            
            // Show success message and close modal
            alert('Operation completed successfully!');
            $(modalId).modal('hide');
        });
    });
</script>
@endpush

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Show success/error messages using SweetAlert2
    @if(session('success'))
        console.log('Success message detected in main layout:', '{{ session('success') }}');
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonText: 'Ok'
        });
    @endif

    @if(session('error'))
        console.log('Error message detected in main layout:', '{{ session('error') }}');
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonText: 'Ok'
        });
    @endif
</script>

@endsection
