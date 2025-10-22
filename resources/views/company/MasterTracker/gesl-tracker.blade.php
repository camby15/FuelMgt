@extends('layouts.vertical', ['page_title' => 'GESL Tracker', 'mode' => session('theme_mode', 'light')])

@section('css')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<!-- DataTables -->
<link href="{{ asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />

<style>
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

    .Linfra-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-completed { background-color: #d4edda; color: #155724; }
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-in-progress { background-color: #cce5ff; color: #0056b3; }
    .status-unsuccessful { background-color: #f8d7da; color: #721c24; }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
    }

    .modal-lg {
        max-width: 1000px;
    }

    .required-field::after {
        content: " *";
        color: #e74c3c;
    }

    .coordinate-input {
        font-family: 'Courier New', monospace;
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

    .dashboard-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
        border-radius: 50%;
        transform: translate(30px, -30px);
        z-index: 1;
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
    }

    .card-change i {
        margin-right: 4px;
    }

    .positive {
        color: #28a745;
    }

    .negative {
        color: #dc3545;
    }

    .neutral {
        color: #6c757d;
    }

    /* Card specific colors */
    .card-total { border-left-color: #007bff; }
    .card-completed { border-left-color: #28a745; }
    .card-pending { border-left-color: #ffc107; }
    .card-in-progress { border-left-color: #17a2b8; }
    .card-unsuccessful { border-left-color: #dc3545; }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-card {
            margin-bottom: 15px;
            padding: 20px;
        }
        
        .card-value {
            font-size: 24px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Tracker</a></li>
                        <li class="breadcrumb-item active">GESL Tracker</li>
                    </ol>
                </div>
                <h4 class="page-title">GESL Tracker Management</h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="dashboard-card card-total">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                        <i class="fas fa-list-alt"></i>
                    </div>
                    <h6 class="card-title">Total Records</h6>
                    <h3 class="card-value" id="totalRecords">0</h3>
                    <div class="card-change neutral">
                        <i class="fas fa-database"></i> All time
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="dashboard-card card-completed">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h6 class="card-title">Completed</h6>
                    <h3 class="card-value" id="completedRecords">0</h3>
                    <div class="card-change positive">
                        <i class="fas fa-arrow-up"></i> <span id="completedPercentage">0%</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="dashboard-card card-in-progress">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <h6 class="card-title">In Progress</h6>
                    <h3 class="card-value" id="inProgressRecords">0</h3>
                    <div class="card-change neutral">
                        <i class="fas fa-clock"></i> <span id="inProgressPercentage">0%</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="dashboard-card card-pending">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <h6 class="card-title">Pending</h6>
                    <h3 class="card-value" id="pendingRecords">0</h3>
                    <div class="card-change neutral">
                        <i class="fas fa-pause"></i> <span id="pendingPercentage">0%</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="dashboard-card card-unsuccessful">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h6 class="card-title">Unsuccessful</h6>
                    <h3 class="card-value" id="unsuccessfulRecords">0</h3>
                    <div class="card-change negative">
                        <i class="fas fa-exclamation-triangle"></i> <span id="unsuccessfulPercentage">0%</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="dashboard-card" style="border-left-color: #6f42c1;">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #6f42c1, #5a359a);">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <h6 class="card-title">This Month</h6>
                    <h3 class="card-value" id="thisMonthRecords">0</h3>
                    <div class="card-change positive">
                        <i class="fas fa-chart-line"></i> <span id="monthlyGrowth">+0%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 me-3">Quick Actions:</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshStats()">
                                    <i class="fas fa-sync-alt me-1"></i> Refresh Stats
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#addLinfraTrackerModal">
                                    <i class="fas fa-plus me-1"></i> Quick Add
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="applyStatusFilter('pending')">
                                    <i class="fas fa-filter me-1"></i> View Pending
                                </button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <small><i class="fas fa-info-circle me-1"></i> Last updated: <span id="lastUpdated">Loading...</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="Gesl-card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                        <h4 class="header-title mb-0">GESL Tracker Records</h4>
                        <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGeslTrackerModal">
                                <i class="fas fa-plus me-1"></i> Add New Record
                            </button>
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                                <i class="fa-solid fa-upload me-1"></i> Bulk Upload
                            </button>
                            <button class="btn btn-outline-primary" id="downloadTemplate">
                                <i class="fa-solid fa-download me-1"></i> Download Template
                            </button>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="fa-solid fa-file-export me-1"></i> Export Data
                            </button>
                            <div class="form-floating" style="width: 250px;" height="10px">
                                <input type="text" id="searchGeslTracker" class="form-control" placeholder=" " aria-label="Search">
                                <label for="searchLinfraTracker">Search Records...</label>
                            </div>
                        </div>
                    </div>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

              

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-centered table-hover dt-responsive nowrap w-100" id="Gesl-tracker-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>MSISDN</th>
                                    <th>Customer</th>
                                    <th>House No.</th>
                                    <th>Location</th>
                                    <th>Phone</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Team</th>
                                    <th>Status</th>
                                    <th>Received Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated via JavaScript/AJAX -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center" id="Gesl-pagination">
                            <!-- Pagination will be generated dynamically -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit GESL Tracker Modal -->
<div class="modal fade" id="addGeslTrackerModal" tabindex="-1" aria-labelledby="addGeslTrackerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGeslTrackerModalLabel">Add New GESL Tracker Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addGeslTrackerForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h6 class="text-muted fw-bold mb-3">Basic Information</h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="msisdn" id="msisdn" placeholder=" " required>
                                <label for="msisdn" class="required-field">MSISDN</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="customer_name" id="customerName" placeholder=" " required>
                                <label for="customerName" class="required-field">Customer Name</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="house_number" id="houseNumber" placeholder=" ">
                                <label for="houseNumber">House Number</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="location" id="location" placeholder=" " required>
                                <label for="location" class="required-field">Location</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="phone_number" id="phoneNumber" placeholder=" " required>
                                <label for="phoneNumber" class="required-field">Phone Number</label>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control coordinate-input" name="coordinates" id="coordinates" placeholder=" ">
                                <label for="coordinates">Coordinates (Lat, Lng)</label>
                                <div class="form-text">Example: 5.5502, -0.2174</div>
                            </div>
                        </div>

                        <!-- Service Details -->
                        <div class="col-12 mt-4">
                            <h6 class="text-muted fw-bold mb-3">Service Details</h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" name="category" id="category" required>
                                    <option value="">Select Category</option>
                                    <option value="residential">Residential</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="enterprise">Enterprise</option>
                                </select>
                                <label for="category" class="required-field">Category</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" name="type" id="type" required>
                                    <option value="">Select Type</option>
                                    <option value="new-connection">New Connection</option>
                                    <option value="upgrade">Upgrade</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="relocation">Relocation</option>
                                </select>
                                <label for="type" class="required-field">Type</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="subbox_endbox_name" id="subboxEndboxName" placeholder=" ">
                                <label for="subboxEndboxName">Subbox/Endbox Name</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="port_number" id="portNumber" placeholder=" ">
                                <label for="portNumber">Port Number</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="serial_number" id="serialNumber" placeholder=" ">
                                <label for="serialNumber">Serial Number</label>
                            </div>
                        </div>

                        <!-- Team Information -->
                        <div class="col-12 mt-4">
                            <h6 class="text-muted fw-bold mb-3">Team Information</h6>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select class="form-select" name="team" id="team" required>
                                    <option value="">Select Team</option>
                                    <option value="team-alpha">Team Alpha</option>
                                    <option value="team-beta">Team Beta</option>
                                    <option value="team-gamma">Team Gamma</option>
                                    <option value="team-delta">Team Delta</option>
                                </select>
                                <label for="team" class="required-field">Team</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_number" id="teamNumber" placeholder=" ">
                                <label for="teamNumber">Team Number</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_lead" id="teamLead" placeholder=" ">
                                <label for="teamLead">Team Lead</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="site_engineer" id="siteEngineer" placeholder=" ">
                                <label for="siteEngineer">Site Engineer</label>
                            </div>
                        </div>

                        <!-- Status and Dates -->
                        <div class="col-12 mt-4">
                            <h6 class="text-muted fw-bold mb-3">Status & Timeline</h6>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="received_date" id="receivedDate" placeholder=" " required>
                                <label for="receivedDate" class="required-field">Received Date</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="completion_date" id="completionDate" placeholder=" ">
                                <label for="completionDate">Completion Date</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="stats" required>
                                    <option value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="unsuccessful">Unsuccessful</option>
                                </select>
                                <label for="status" class="required-field">Status</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select class="form-select" name="scanning_status" id="scanningStatus">
                                    <option value="">Select Scanning Status</option>
                                    <option value="scanned">Scanned</option>
                                    <option value="not-scanned">Not Scanned</option>
                                    <option value="pending-scan">Pending Scan</option>
                                </select>
                                <label for="scanningStatus">Scanning Status</label>
                            </div>
                        </div>

                        <!-- Unsuccessful Details -->
                        <div class="col-12 mt-3" id="unsuccessfulDetails" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="unsuccessful_connection" id="unsuccessfulConnection" placeholder=" ">
                                        <label for="unsuccessfulConnection">Unsuccessful Connection Reason</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" name="unsuccessful_date" id="unsuccessfulDate" placeholder=" ">
                                        <label for="unsuccessfulDate">Unsuccessful Date</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="comments" id="comments" placeholder=" " style="height: 100px"></textarea>
                                <label for="comments">Comments</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit GESL Tracker Modal -->
<div class="modal fade" id="editGeslTrackerModal" tabindex="-1" aria-labelledby="editGeslTrackerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGeslTrackerModalLabel">Edit GESL Tracker Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editGeslTrackerForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="record_id" id="editRecordId">
                <div class="modal-body">
                    <!-- Same form structure as add modal but with edit prefix on IDs -->
                    <div class="row g-3">
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h6 class="text-muted fw-bold mb-3">Basic Information</h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="msisdn" id="editMsisdn" placeholder=" " required>
                                <label for="editMsisdn" class="required-field">MSISDN</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="customer_name" id="editCustomerName" placeholder=" " required>
                                <label for="editCustomerName" class="required-field">Customer Name</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="house_number" id="editHouseNumber" placeholder=" ">
                                <label for="editHouseNumber">House Number</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="location" id="editLocation" placeholder=" " required>
                                <label for="editLocation" class="required-field">Location</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="phone_number" id="editPhoneNumber" placeholder=" " required>
                                <label for="editPhoneNumber" class="required-field">Phone Number</label>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control coordinate-input" name="coordinates" id="editCoordinates" placeholder=" ">
                                <label for="editCoordinates">Coordinates (Lat, Lng)</label>
                                <div class="form-text">Example: 5.5502, -0.2174</div>
                            </div>
                        </div>

                        <!-- Service Details -->
                        <div class="col-12 mt-4">
                            <h6 class="text-muted fw-bold mb-3">Service Details</h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" name="category" id="editCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="residential">Residential</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="enterprise">Enterprise</option>
                                </select>
                                <label for="editCategory" class="required-field">Category</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" name="type" id="editType" required>
                                    <option value="">Select Type</option>
                                    <option value="new-connection">New Connection</option>
                                    <option value="upgrade">Upgrade</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="relocation">Relocation</option>
                                </select>
                                <label for="editType" class="required-field">Type</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="subbox_endbox_name" id="editSubboxEndboxName" placeholder=" ">
                                <label for="editSubboxEndboxName">Subbox/Endbox Name</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="port_number" id="editPortNumber" placeholder=" ">
                                <label for="editPortNumber">Port Number</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="serial_number" id="editSerialNumber" placeholder=" ">
                                <label for="editSerialNumber">Serial Number</label>
                            </div>
                        </div>

                        <!-- Team Information -->
                        <div class="col-12 mt-4">
                            <h6 class="text-muted fw-bold mb-3">Team Information</h6>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select class="form-select" name="team" id="editTeam" required>
                                    <option value="">Select Team</option>
                                    <option value="team-alpha">Team Alpha</option>
                                    <option value="team-beta">Team Beta</option>
                                    <option value="team-gamma">Team Gamma</option>
                                    <option value="team-delta">Team Delta</option>
                                </select>
                                <label for="editTeam" class="required-field">Team</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_number" id="editTeamNumber" placeholder=" ">
                                <label for="editTeamNumber">Team Number</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_lead" id="editTeamLead" placeholder=" ">
                                <label for="editTeamLead">Team Lead</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="site_engineer" id="editSiteEngineer" placeholder=" ">
                                <label for="editSiteEngineer">Site Engineer</label>
                            </div>
                        </div>

                        <!-- Status and Dates -->
                        <div class="col-12 mt-4">
                            <h6 class="text-muted fw-bold mb-3">Status & Timeline</h6>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="received_date" id="editReceivedDate" placeholder=" " required>
                                <label for="editReceivedDate" class="required-field">Received Date</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="completion_date" id="editCompletionDate" placeholder=" ">
                                <label for="editCompletionDate">Completion Date</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="editStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="unsuccessful">Unsuccessful</option>
                                </select>
                                <label for="editStatus" class="required-field">Status</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select class="form-select" name="scanning_status" id="editScanningStatus">
                                    <option value="">Select Scanning Status</option>
                                    <option value="scanned">Scanned</option>
                                    <option value="not-scanned">Not Scanned</option>
                                    <option value="pending-scan">Pending Scan</option>
                                </select>
                                <label for="editScanningStatus">Scanning Status</label>
                            </div>
                        </div>

                        <!-- Unsuccessful Details -->
                        <div class="col-12 mt-3" id="editUnsuccessfulDetails" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="unsuccessful_connection" id="editUnsuccessfulConnection" placeholder=" ">
                                        <label for="editUnsuccessfulConnection">Unsuccessful Connection Reason</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" name="unsuccessful_date" id="editUnsuccessfulDate" placeholder=" ">
                                        <label for="editUnsuccessfulDate">Unsuccessful Date</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="comments" id="editComments" placeholder=" " style="height: 100px"></textarea>
                                <label for="editComments">Comments</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View GESL Tracker Modal -->
<div class="modal fade" id="viewGeslTrackerModal" tabindex="-1" aria-labelledby="viewGeslTrackerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewGeslTrackerModalLabel">GESL Tracker Record Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Record details will be populated here -->
                    <div class="col-12" id="recordDetails">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editFromView">Edit Record</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteGeslTrackerModal" tabindex="-1" aria-labelledby="deleteLinfraTrackerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteGeslTrackerModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this GESL Tracker record?</p>
                <p><strong>Customer:</strong> <span id="deleteCustomerName"></span></p>
                <p><strong>MSISDN:</strong> <span id="deleteMsisdn"></span></p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete Record</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload GESL Tracker Records</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkUploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bulkFile" class="form-label">Choose CSV/Excel File</label>
                        <input type="file" class="form-control" id="bulkFile" name="bulk_file" accept=".csv,.xlsx,.xls" required>
                        <div class="form-text">
                            Please ensure your file follows the template format. 
                            <a href="#" id="downloadTemplateLink">Download template</a>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <strong>File Requirements:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Maximum file size: 10MB</li>
                            <li>Supported formats: CSV, Excel (.xlsx, .xls)</li>
                            <li>First row should contain column headers</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Records</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Export GESL Tracker Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="exportForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Export Format</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="export_format" id="exportCsv" value="csv" checked>
                                <label class="form-check-label" for="exportCsv">CSV</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="export_format" id="exportExcel" value="xlsx">
                                <label class="form-check-label" for="exportExcel">Excel (.xlsx)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="export_format" id="exportPdf" value="pdf">
                                <label class="form-check-label" for="exportPdf">PDF</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Range (Optional)</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="date" class="form-control" name="export_start_date" id="exportStartDate">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control" name="export_end_date" id="exportEndDate">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="exportFiltered" name="export_filtered">
                            <label class="form-check-label" for="exportFiltered">
                                Export only filtered results
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Export Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- DataTables -->
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Load initial stats
    loadDashboardStats();
    
    // Update last updated time
    updateLastUpdatedTime();
    
    // Initialize DataTable
    let table = $('#Gesl-tracker-datatable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/company/MasterTracker/Gesl-tracker/data',
            data: function(d) {
                d.status = $('#filterStatus').val();
                d.category = $('#filterCategory').val();
                d.type = $('#filterType').val();
                d.team = $('#filterTeam').val();
                d.search = $('#searchGeslTracker').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'msisdn', name: 'msisdn' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'house_number', name: 'house_number' },
            { data: 'location', name: 'location' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'category', name: 'category' },
            { data: 'type', name: 'type' },
            { data: 'team', name: 'team' },
            { 
                data: 'status', 
                name: 'status',
                render: function(data, type, row) {
                    let badgeClass = 'status-pending';
                    switch(data) {
                        case 'completed': badgeClass = 'status-completed'; break;
                        case 'in-progress': badgeClass = 'status-in-progress'; break;
                        case 'unsuccessful': badgeClass = 'status-unsuccessful'; break;
                    }
                    return `<span class="status-badge ${badgeClass}">${data}</span>`;
                }
            },
            { data: 'received_date', name: 'received_date' },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-sm btn-info action-btn view-record" 
                                    data-id="${row.id}" title="View Record">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning action-btn edit-record" 
                                    data-id="${row.id}" title="Edit Record">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger action-btn delete-record" 
                                    data-id="${row.id}" data-customer="${row.customer_name}" 
                                    data-msisdn="${row.msisdn}" title="Delete Record">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    });

    // Search functionality
    $('#searchGeslTracker').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Filter functionality
    $('#applyFilters').on('click', function() {
        table.ajax.reload();
    });

    $('#clearFilters').on('click', function() {
        $('#filterStatus, #filterCategory, #filterType, #filterTeam').val('');
        table.ajax.reload();
    });

    // Status change handler for add form
    $('#status').on('change', function() {
        if ($(this).val() === 'unsuccessful') {
            $('#unsuccessfulDetails').show();
        } else {
            $('#unsuccessfulDetails').hide();
        }
    });

    // Status change handler for edit form
    $('#editStatus').on('change', function() {
        if ($(this).val() === 'unsuccessful') {
            $('#editUnsuccessfulDetails').show();
        } else {
            $('#editUnsuccessfulDetails').hide();
        }
    });

    // Add Record Form Submit
    $('#addGeslTrackerForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: '/company/MasterTracker/Gesl-tracker',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#addGeslTrackerModal').modal('hide');
                    $('#addGeslTrackerForm')[0].reset();
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors || {};
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
        });
    });

    // View Record
    $(document).on('click', '.view-record', function() {
        let recordId = $(this).data('id');
        
        $.ajax({
            url: `/company/MasterTracker/Gesl-tracker/${recordId}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let record = response.data;
                    let detailsHtml = generateRecordDetailsHtml(record);
                    $('#recordDetails').html(detailsHtml);
                    $('#editFromView').data('id', recordId);
                    $('#viewGeslTrackerModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load record details.'
                });
            }
        });
    });

    // Edit Record
    $(document).on('click', '.edit-record', function() {
        let recordId = $(this).data('id');
        loadRecordForEdit(recordId);
    });

    // Edit from view modal
    $('#editFromView').on('click', function() {
        let recordId = $(this).data('id');
        $('#viewGeslTrackerModal').modal('hide');
        loadRecordForEdit(recordId);
    });

    // Delete Record
    $(document).on('click', '.delete-record', function() {
        let recordId = $(this).data('id');
        let customerName = $(this).data('customer');
        let msisdn = $(this).data('msisdn');
        
        $('#deleteCustomerName').text(customerName);
        $('#deleteMsisdn').text(msisdn);
        $('#confirmDelete').data('id', recordId);
        $('#deleteGeslTrackerModal').modal('show');
    });

    // Confirm Delete
    $('#confirmDelete').on('click', function() {
        let recordId = $(this).data('id');
        
        $.ajax({
            url: `/company/MasterTracker/Gesl-tracker/${recordId}`,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#deleteGeslTrackerModal').modal('hide');
                    table.ajax.reload();
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to delete record.'
                });
            }
        });
    });

    // Update Record Form Submit
    $('#editGeslTrackerForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let recordId = $('#editRecordId').val();
        
        $.ajax({
            url: `/company/MasterTracker/Gesl-tracker/${recordId}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#editGeslTrackerModal').modal('hide');
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors || {};
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
        });
    });

    // Bulk Upload
    $('#bulkUploadForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: '/company/MasterTracker/Gesl-tracker/bulk-upload',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $('#bulkUploadModal').modal('hide');
                    $('#bulkUploadForm')[0].reset();
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Failed to upload file.'
                });
            }
        });
    });

    // Export functionality
    $('#exportForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        // Convert FormData to query string for GET request
        let params = new URLSearchParams();
        for (let [key, value] of formData.entries()) {
            params.append(key, value);
        }
        
        // Create download link
        let downloadUrl = `/company/MasterTracker/Gesl-tracker/export?${params.toString()}`;
        window.open(downloadUrl, '_blank');
        
        $('#exportModal').modal('hide');
    });

    // Download template
    $('#downloadTemplate, #downloadTemplateLink').on('click', function(e) {
        e.preventDefault();
        window.open('/company/MasterTracker/Gesl-tracker/template', '_blank');
    });

    // Helper function to load record for editing
    function loadRecordForEdit(recordId) {
        $.ajax({
            url: `/company/MasterTracker/Gesl-tracker/${recordId}/edit`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let record = response.data;
                    populateEditForm(record);
                    $('#editGeslTrackerModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load record for editing.'
                });
            }
        });
    }

    // Helper function to populate edit form
    function populateEditForm(record) {
        $('#editRecordId').val(record.id);
        $('#editMsisdn').val(record.msisdn);
        $('#editCustomerName').val(record.customer_name);
        $('#editHouseNumber').val(record.house_number);
        $('#editLocation').val(record.location);
        $('#editPhoneNumber').val(record.phone_number);
        $('#editCoordinates').val(record.coordinates);
        $('#editCategory').val(record.category);
        $('#editType').val(record.type);
        $('#editSubboxEndboxName').val(record.subbox_endbox_name);
        $('#editPortNumber').val(record.port_number);
        $('#editSerialNumber').val(record.serial_number);
        $('#editTeam').val(record.team);
        $('#editTeamNumber').val(record.team_number);
        $('#editTeamLead').val(record.team_lead);
        $('#editSiteEngineer').val(record.site_engineer);
        $('#editReceivedDate').val(record.received_date);
        $('#editCompletionDate').val(record.completion_date);
        $('#editStatus').val(record.status);
        $('#editScanningStatus').val(record.scanning_status);
        $('#editUnsuccessfulConnection').val(record.unsuccessful_connection);
        $('#editUnsuccessfulDate').val(record.unsuccessful_date);
        $('#editComments').val(record.comments);
        
        // Show/hide unsuccessful details based on status
        if (record.status === 'unsuccessful') {
            $('#editUnsuccessfulDetails').show();
        } else {
            $('#editUnsuccessfulDetails').hide();
        }
    }

    // Helper function to generate record details HTML
    function generateRecordDetailsHtml(record) {
        return `
            <div class="row g-4">
                <div class="col-12">
                    <h6 class="text-primary fw-bold mb-3">Basic Information</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <strong>MSISDN:</strong><br>
                            <span class="text-muted">${record.msisdn || 'N/A'}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Customer:</strong><br>
                            <span class="text-muted">${record.customer_name || 'N/A'}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>House Number:</strong><br>
                            <span class="text-muted">${record.house_number || 'N/A'}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Location:</strong><br>
                            <span class="text-muted">${record.location || 'N/A'}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Phone Number:</strong><br>
                            <span class="text-muted">${record.phone_number || 'N/A'}</span>
                        </div>
                        <div class="col-md-12">
                            <strong>Coordinates:</strong><br>
                            <span class="text-muted coordinate-input">${record.coordinates || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <h6 class="text-primary fw-bold mb-3">Service Details</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <strong>Category:</strong><br>
                            <span class="text-muted text-capitalize">${record.category || 'N/A'}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Type:</strong><br>
                            <span class="text-muted text-capitalize">${record.type || 'N/A'}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Subbox/Endbox:</strong><br>
                            <span class="text-muted">${record.subbox_endbox_name || 'N/A'}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Port Number:</strong><br>
                            <span class="text-muted">${record.port_number || 'N/A'}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Serial Number:</strong><br>
                            <span class="text-muted">${record.serial_number || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <h6 class="text-primary fw-bold mb-3">Team Information</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <strong>Team:</strong><br>
                            <span class="text-muted text-capitalize">${record.team || 'N/A'}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Team Number:</strong><br>
                            <span class="text-muted">${record.team_number || 'N/A'}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Team Lead:</strong><br>
                            <span class="text-muted">${record.team_lead || 'N/A'}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Site Engineer:</strong><br>
                            <span class="text-muted">${record.site_engineer || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <h6 class="text-primary fw-bold mb-3">Status & Timeline</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <strong>Received Date:</strong><br>
                            <span class="text-muted">${record.received_date || 'N/A'}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Completion Date:</strong><br>
                            <span class="text-muted">${record.completion_date || 'N/A'}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Status:</strong><br>
                            <span class="status-badge status-${record.status}">${record.status || 'N/A'}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Scanning Status:</strong><br>
                            <span class="text-muted text-capitalize">${record.scanning_status || 'N/A'}</span>
                        </div>
                        ${record.status === 'unsuccessful' ? `
                        <div class="col-md-6">
                            <strong>Unsuccessful Reason:</strong><br>
                            <span class="text-muted">${record.unsuccessful_connection || 'N/A'}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Unsuccessful Date:</strong><br>
                            <span class="text-muted">${record.unsuccessful_date || 'N/A'}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
                
                ${record.comments ? `
                <div class="col-12">
                    <h6 class="text-primary fw-bold mb-3">Comments</h6>
                    <div class="bg-light p-3 rounded">
                        <span class="text-muted">${record.comments}</span>
                    </div>
                </div>
                ` : ''}
            </div>
        `;
    }

    // Reset forms when modals are hidden
    $('#addGeslTrackerModal, #editGeslTrackerModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $('#unsuccessfulDetails, #editUnsuccessfulDetails').hide();
    });

    // Dashboard Stats Functions
    function loadDashboardStats() {
        $.ajax({
            url: '/company/MasterTracker/Gesl-tracker/stats',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    updateStatsCards(response.data);
                }
            },
            error: function() {
                // Set default values if API fails
                updateStatsCards({
                    total: 0,
                    completed: 0,
                    pending: 0,
                    in_progress: 0,
                    unsuccessful: 0,
                    this_month: 0,
                    monthly_growth: 0
                });
            }
        });
    }

    function updateStatsCards(stats) {
        // Update values
        $('#totalRecords').text(formatNumber(stats.total || 0));
        $('#completedRecords').text(formatNumber(stats.completed || 0));
        $('#pendingRecords').text(formatNumber(stats.pending || 0));
        $('#inProgressRecords').text(formatNumber(stats.in_progress || 0));
        $('#unsuccessfulRecords').text(formatNumber(stats.unsuccessful || 0));
        $('#thisMonthRecords').text(formatNumber(stats.this_month || 0));

        // Calculate and update percentages
        let total = stats.total || 1; // Avoid division by zero
        let completedPerc = ((stats.completed || 0) / total * 100).toFixed(1);
        let pendingPerc = ((stats.pending || 0) / total * 100).toFixed(1);
        let inProgressPerc = ((stats.in_progress || 0) / total * 100).toFixed(1);
        let unsuccessfulPerc = ((stats.unsuccessful || 0) / total * 100).toFixed(1);
        
        $('#completedPercentage').text(completedPerc + '%');
        $('#pendingPercentage').text(pendingPerc + '%');
        $('#inProgressPercentage').text(inProgressPerc + '%');
        $('#unsuccessfulPercentage').text(unsuccessfulPerc + '%');
        
        // Monthly growth
        let growth = stats.monthly_growth || 0;
        let growthText = growth > 0 ? '+' + growth + '%' : growth + '%';
        $('#monthlyGrowth').text(growthText);
        
        // Update growth indicator classes
        let $monthlyGrowthElement = $('#monthlyGrowth').parent();
        $monthlyGrowthElement.removeClass('positive negative neutral');
        if (growth > 0) {
            $monthlyGrowthElement.addClass('positive');
        } else if (growth < 0) {
            $monthlyGrowthElement.addClass('negative');
        } else {
            $monthlyGrowthElement.addClass('neutral');
        }

        // Add animation effect
        $('.card-value').addClass('animate__animated animate__pulse');
        setTimeout(() => {
            $('.card-value').removeClass('animate__animated animate__pulse');
        }, 1000);
    }

    function formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + 'M';
        } else if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K';
        }
        return num.toString();
    }

    function updateLastUpdatedTime() {
        let now = new Date();
        let timeString = now.toLocaleTimeString('en-US', { 
            hour12: true, 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        $('#lastUpdated').text(timeString);
    }

    // Global function for refreshing stats
    window.refreshStats = function() {
        // Show loading state
        $('.card-value').html('<i class="fas fa-spinner fa-spin"></i>');
        
        // Reload stats
        setTimeout(() => {
            loadDashboardStats();
            updateLastUpdatedTime();
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Refreshed!',
                text: 'Dashboard statistics updated successfully.',
                showConfirmButton: false,
                timer: 1500
            });
        }, 500);
    };

    // Global function for applying status filter from quick actions
    window.applyStatusFilter = function(status) {
        $('#filterStatus').val(status);
        table.ajax.reload();
        
        // Highlight the applied filter
        $('#filterStatus').addClass('border-primary');
        setTimeout(() => {
            $('#filterStatus').removeClass('border-primary');
        }, 2000);
    };

    // Auto-refresh stats every 5 minutes
    setInterval(function() {
        loadDashboardStats();
        updateLastUpdatedTime();
    }, 300000); // 5 minutes

    // Refresh stats when table is reloaded
    table.on('draw.dt', function() {
        loadDashboardStats();
    });
});
</script>

<!-- Add animate.css for card animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

@endsection
