@extends('layouts.vertical', ['page_title' => 'Team Pairing', 'mode' => session('theme_mode', 'light')])

@section('css')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">

<!-- DataTables CDN -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<!-- Select2 removed - using regular HTML select elements -->

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

    .team-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }

    .team-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    .team-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 20px;
    }

    .team-stats {
        display: flex;
        justify-content: space-around;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 0 0 12px 12px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
    }

    .stat-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .member-badge {
        display: inline-block;
        background: #e3f2fd;
        color: #1976d2;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        margin: 2px;
        border: 1px solid #bbdefb;
    }

    .vehicle-badge {
        display: inline-block;
        background: #e8f5e8;
        color: #2e7d32;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        margin: 2px;
        border: 1px solid #c8e6c9;
    }

    .driver-badge {
        display: inline-block;
        background: #fff3e0;
        color: #f57c00;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        margin: 2px;
        border: 1px solid #ffcc02;
    }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }

    .avatar-sm {
        width: 40px;
        height: 40px;
    }

    .member-badge, .vehicle-badge, .driver-badge {
        display: inline-block;
        margin: 1px;
        font-size: 11px;
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
    }

    .modal-xl {
        max-width: 1200px;
    }

    .required-field::after {
        content: " *";
        color: #e74c3c;
    }

    .allocation-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin: 15px 0;
        border-left: 4px solid #007bff;
    }

    /* Regular select element styles */
    .form-select {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
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
    .card-teams { border-left-color: #007bff; }
    .card-members { border-left-color: #28a745; }
    .card-vehicles { border-left-color: #17a2b8; }
    .card-drivers { border-left-color: #ffc107; }

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

    /* Team Details Modal Styles */
    .team-details-container {
        padding: 0;
    }
    
    .team-header-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    
    .team-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .team-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        border-left: 4px solid #007bff;
    }
    
    .section-header {
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .section-title {
        color: #495057;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    .section-content {
        margin-top: 1rem;
    }
    
    .member-card, .vehicle-card, .driver-card {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .member-card:hover, .vehicle-card:hover, .driver-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .team-lead-card {
        border-left: 4px solid #ffc107;
        background: linear-gradient(135deg, #fff9e6 0%, #ffffff 100%);
    }
    
    .primary-vehicle-card {
        border-left: 4px solid #28a745;
        background: linear-gradient(135deg, #e8f5e8 0%, #ffffff 100%);
    }
    
    .primary-driver-card {
        border-left: 4px solid #ffc107;
        background: linear-gradient(135deg, #fff9e6 0%, #ffffff 100%);
    }
    
    .member-avatar, .vehicle-icon, .driver-icon {
        width: 50px;
        height: 50px;
        background: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e9ecef;
    }
    
    .member-info, .vehicle-info, .driver-info {
        flex: 1;
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }
    
    .empty-state i {
        opacity: 0.5;
    }
    
    .info-item {
        margin-bottom: 1rem;
    }
    
    .info-item strong {
        color: #495057;
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .info-item p {
        color: #6c757d;
        margin-left: 1.5rem;
    }
    
    /* Status Badge Colors */
    .badge.bg-success {
        background-color: #28a745 !important;
    }
    
    .badge.bg-secondary {
        background-color: #6c757d !important;
    }
    
    .badge.bg-info {
        background-color: #17a2b8 !important;
    }
    
    .badge.bg-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .team-header-section .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        .team-icon {
            margin: 0 auto 1rem auto;
        }
        
        .member-card, .vehicle-card, .driver-card {
            margin-bottom: 1rem;
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
                        <li class="breadcrumb-item active">Team Pairing</li>
                    </ol>
                </div>
                <h4 class="page-title">Team Pairing Management</h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-teams">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6 class="card-title">Active Teams</h6>
                    <h3 class="card-value" id="totalTeams">0</h3>
                    <div class="card-change">
                        <i class="fas fa-layer-group me-1"></i> Currently deployed
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-members">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h6 class="card-title">Inactive Teams</h6>
                    <h3 class="card-value" id="totalMembers">0</h3>
                    <div class="card-change">
                        <i class="fas fa-pause-circle me-1"></i> Not operational
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-vehicles">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                        <i class="fas fa-car"></i>
                    </div>
                    <h6 class="card-title">Deployed Teams</h6>
                    <h3 class="card-value" id="totalVehicles">0</h3>
                    <div class="card-change">
                        <i class="fas fa-rocket me-1"></i> On assignment
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card card-drivers">
                <div class="card-content">
                    <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h6 class="card-title">Unavailable Teams</h6>
                    <h3 class="card-value" id="totalDrivers">0</h3>
                    <div class="card-change">
                        <i class="fas fa-wrench me-1"></i> Under maintenance
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
                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                                    <i class="fas fa-plus me-1"></i> New Team
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="viewAllocationMatrix()">
                                    <i class="fas fa-table me-1"></i> Allocation Matrix
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
            <div class="card team-card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                        <h4 class="header-title mb-0">Team Management</h4>
                        <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                                <i class="fas fa-plus me-1"></i> Add New Team
                            </button>
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#bulkAllocationModal">
                                <i class="fa-solid fa-users-gear me-1"></i> Bulk Allocation
                            </button>
                            <button class="btn btn-outline-primary" id="downloadTeamReport">
                                <i class="fa-solid fa-download me-1"></i> Team Report
                            </button>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="fa-solid fa-file-export me-1"></i> Export Data
                            </button>
                            <div class="input-group" style="width: 250px;">
                                <input type="text" id="searchTeams" class="form-control" placeholder="Search Teams..." aria-label="Search" style="height: 38px;">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
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
                        <table class="table table-centered table-hover dt-responsive nowrap w-100" id="teams-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Team Name</th>
                                    <th>Team Members</th>
                                    <th>Team Allocation</th>
                                    <th>Vehicles</th>
                                    <th>Drivers</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($teams && $teams->count() > 0)
                                    @foreach($teams as $index => $team)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="fas fa-users text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $team->team_name }}</h6>
                                                        <small class="text-muted">{{ $team->team_code }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($team->teamMembers && $team->teamMembers->count() > 0)
                                                    @foreach($team->teamMembers->take(3) as $member)
                                                        <span class="member-badge">{{ $member->full_name }}</span>
                                                    @endforeach
                                                    @if($team->teamMembers->count() > 3)
                                                        <span class="member-badge">+{{ $team->teamMembers->count() - 3 }} more</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No members assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($team->team_allocation)
                                                    {{ Str::limit($team->team_allocation, 50) }}
                                                @else
                                                    <span class="text-muted">Not specified</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($team->vehicles && $team->vehicles->count() > 0)
                                                    @foreach($team->vehicles->take(2) as $vehicle)
                                                        <span class="vehicle-badge">{{ $vehicle->registration_number }}</span>
                                                    @endforeach
                                                    @if($team->vehicles->count() > 2)
                                                        <span class="vehicle-badge">+{{ $team->vehicles->count() - 2 }} more</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No vehicles</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($team->drivers && $team->drivers->count() > 0)
                                                    @foreach($team->drivers->take(2) as $driver)
                                                        <span class="driver-badge">{{ $driver->full_name }}</span>
                                                    @endforeach
                                                    @if($team->drivers->count() > 2)
                                                        <span class="driver-badge">+{{ $team->drivers->count() - 2 }} more</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No drivers</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $badgeClass = match($team->team_status) {
                                                        'active' => 'bg-success',
                                                        'deployed' => 'bg-primary',
                                                        'inactive' => 'bg-warning',
                                                        'maintenance' => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ ucfirst($team->team_status) }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <button type="button" class="btn btn-sm btn-info action-btn view-team" 
                                                            data-id="{{ $team->id }}" title="View Team">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-warning action-btn edit-team" 
                                                            data-id="{{ $team->id }}" title="Edit Team">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                               
                                                    <button type="button" class="btn btn-sm btn-danger action-btn delete-team" 
                                                            data-id="{{ $team->id }}" data-name="{{ $team->team_name }}" 
                                                            data-code="{{ $team->team_code }}" title="Delete Team">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-3x mb-3"></i>
                                                <h5>No teams found</h5>
                                                <p>Start by creating your first team pairing.</p>
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                                                    <i class="fas fa-plus me-1"></i> Create First Team
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Team Modal -->
<div class="modal fade" id="addTeamModal" tabindex="-1" aria-labelledby="addTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeamModalLabel">Add New Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTeamForm" action="/company/MasterTracker/team-pairing" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Basic Team Information -->
                        <div class="col-12">
                            <h6 class="text-muted fw-bold mb-3">Basic Team Information</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_name" id="teamName" placeholder=" " required>
                                <label for="teamName" class="required-field">Team Name</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_code" id="teamCode" placeholder=" " required>
                                <label for="teamCode" class="required-field">Team Code</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="team_location" id="teamLocation" required>
                                    <option value="">Select Location</option>

                                    <!-- Ahafo Region -->
                                    <optgroup label="Ahafo Region">
                                      <option value="goaso">Goaso</option>
                                      <option value="bechem">Bechem</option>
                                      <option value="kenyasi">Kenyasi</option>
                                    </optgroup>

                                    <!-- Ashanti Region -->
                                    <optgroup label="Ashanti Region">
                                      <option value="kumasi">Kumasi</option>
                                      <option value="obuasi">Obuasi</option>
                                      <option value="konongo">Konongo</option>
                                      <option value="ejura">Ejura</option>
                                      <option value="mampong">Mampong</option>
                                    </optgroup>

                                    <!-- Bono Region -->
                                    <optgroup label="Bono Region">
                                      <option value="sunyani">Sunyani</option>
                                      <option value="dormaa-ahu">Dormaa Ahenkro</option>
                                      <option value="berekum">Berekum</option>
                                    </optgroup>

                                    <!-- Bono East Region -->
                                    <optgroup label="Bono East Region">
                                      <option value="techiman">Techiman</option>
                                      <option value="kintampo">Kintampo</option>
                                      <option value="nkoranza">Nkoranza</option>
                                    </optgroup>

                                    <!-- Central Region -->
                                    <optgroup label="Central Region">
                                      <option value="cape-coast">Cape Coast</option>
                                      <option value="elmina">Elmina</option>
                                      <option value="winneba">Winneba</option>
                                      <option value="agona-swedru">Agona Swedru</option>
                                    </optgroup>

                                    <!-- Eastern Region -->
                                    <optgroup label="Eastern Region">
                                      <option value="koforidua">Koforidua</option>
                                      <option value="nkawkaw">Nkawkaw</option>
                                      <option value="akim-oda">Akim Oda</option>
                                      <option value="nsawam">Nsawam</option>
                                    </optgroup>

                                    <!-- Greater Accra Region -->
                                    <optgroup label="Greater Accra Region">
                                      <option value="accra">Accra</option>
                                      <option value="tema">Tema</option>
                                      <option value="madina">Madina</option>
                                      <option value="ashaiman">Ashaiman</option>
                                      <option value="teshie">Teshie</option>
                                      <option value="lapaz">La Paz</option>
                                    </optgroup>

                                    <!-- North East Region -->
                                    <optgroup label="North East Region">
                                      <option value="nalerigu">Nalerigu</option>
                                      <option value="walewale">Walewale</option>
                                      <option value="chereponi">Chereponi</option>
                                    </optgroup>

                                    <!-- Northern Region -->
                                    <optgroup label="Northern Region">
                                      <option value="tamale">Tamale</option>
                                      <option value="yendi">Yendi</option>
                                      <option value="saboba">Saboba</option>
                                    </optgroup>

                                    <!-- Oti Region -->
                                    <optgroup label="Oti Region">
                                      <option value="dambai">Dambai</option>
                                      <option value="krachi-east">Krachi East</option>
                                      <option value="nkwanta">Nkwanta</option>
                                    </optgroup>

                                    <!-- Savannah Region -->
                                    <optgroup label="Savannah Region">
                                      <option value="damongo">Damongo</option>
                                      <option value="buipe">Buipe</option>
                                      <option value="salaga">Salaga</option>
                                    </optgroup>

                                    <!-- Upper East Region -->
                                    <optgroup label="Upper East Region">
                                      <option value="bolgatanga">Bolgatanga</option>
                                      <option value="navrongo">Navrongo</option>
                                      <option value="bawku">Bawku</option>
                                    </optgroup>

                                    <!-- Upper West Region -->
                                    <optgroup label="Upper West Region">
                                      <option value="wa">Wa</option>
                                      <option value="jirapa">Jirapa</option>
                                      <option value="lawra">Lawra</option>
                                    </optgroup>

                                    <!-- Volta Region -->
                                    <optgroup label="Volta Region">
                                      <option value="ho">Ho</option>
                                      <option value="hohoe">Hohoe</option>
                                      <option value="kpando">Kpando</option>
                                      <option value="sogakope">Sogakope</option>
                                    </optgroup>

                                    <!-- Western Region -->
                                    <optgroup label="Western Region">
                                      <option value="takoradi">Takoradi</option>
                                      <option value="sekondi">Sekondi</option>
                                      <option value="tarkwa">Tarkwa</option>
                                      <option value="prestea">Prestea</option>
                                    </optgroup>

                                    <!-- Western North Region -->
                                    <optgroup label="Western North Region">
                                      <option value="sefwi-wiawso">Sefwi Wiawso</option>
                                      <option value="bibiani">Bibiani</option>
                                      <option value="juaboso">Juaboso</option>
                                    </optgroup>
                                </select>
                                <label for="teamLocation" class="required-field">Team Location</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="team_status" id="teamStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="deployed">Deployed</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                                <label for="teamStatus" class="required-field">Team Status</label>
                            </div>
                        </div>

                        <!-- Team Member Allocation -->
                        <div class="col-12 mt-4">
                            <div class="allocation-section">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-users me-2"></i>Team Member Allocation
                                </h6>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="teamMembers" class="form-label">Select Team Members</label>
                                        <select class="form-select" name="team_members[]" id="teamMembers" multiple="multiple" style="width: 100%;">
                                            @if($unassignedTeamMembers && $unassignedTeamMembers->count() > 0)
                                                @foreach($unassignedTeamMembers as $member)
                                                    <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->position }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available team members</option>
                                            @endif
                                        </select>
                                        <div class="form-text">Select multiple team members using Ctrl+Click</div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="teamLead" class="form-label">Team Lead</label>
                                        <select class="form-select" name="team_lead" id="teamLead">
                                            <option value="">Select Team Lead</option>
                                            @if($unassignedTeamMembers && $unassignedTeamMembers->count() > 0)
                                                @foreach($unassignedTeamMembers as $member)
                                                    <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->position }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available team members</option>
                                            @endif
                                        </select>
                                        <div class="form-text">Team lead must be selected from team members</div>
                                    </div>
                                </div>
                                
                                <div class="row g-3 mt-2">
                                    <div class="col-md-12">
                                        <label class="form-label">Team Allocation Details</label>
                                        <textarea class="form-control" name="team_allocation" id="teamAllocation" rows="3" placeholder="Describe team responsibilities, work areas, or project assignments..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Assignment -->
                        <div class="col-12 mt-4">
                            <div class="allocation-section">
                                <h6 class="text-success fw-bold mb-3">
                                    <i class="fas fa-car me-2"></i>Vehicle Assignment
                                </h6>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="assignedVehicles" class="form-label">Assigned Vehicles</label>
                                        <select class="form-select" name="assigned_vehicles[]" id="assignedVehicles" multiple="multiple" style="width: 100%;">
                                            @if($unassignedVehicles && $unassignedVehicles->count() > 0)
                                                @foreach($unassignedVehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}">{{ $vehicle->registration_number }} - {{ $vehicle->make }} {{ $vehicle->model }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available vehicles</option>
                                            @endif
                                        </select>
                                        <div class="form-text">Select vehicles assigned to this team</div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="primaryVehicle" class="form-label">Primary Vehicle</label>
                                        <select class="form-select" name="primary_vehicle" id="primaryVehicle">
                                            <option value="">Select Primary Vehicle</option>
                                            @if($unassignedVehicles && $unassignedVehicles->count() > 0)
                                                @foreach($unassignedVehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}">{{ $vehicle->registration_number }} - {{ $vehicle->make }} {{ $vehicle->model }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available vehicles</option>
                                            @endif
                                        </select>
                                        <div class="form-text">Main vehicle for team operations</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Driver Assignment -->
                        <div class="col-12 mt-4">
                            <div class="allocation-section">
                                <h6 class="text-warning fw-bold mb-3">
                                    <i class="fas fa-id-card me-2"></i>Driver Assignment
                                </h6>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="assignedDrivers" class="form-label">Assigned Drivers</label>
                                        <select class="form-select" name="assigned_drivers[]" id="assignedDrivers" multiple="multiple" style="width: 100%;">
                                            @if($unassignedDrivers && $unassignedDrivers->count() > 0)
                                                @foreach($unassignedDrivers as $driver)
                                                    <option value="{{ $driver->id }}">{{ $driver->full_name }} - License: {{ $driver->license_number }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available drivers</option>
                                            @endif
                                        </select>
                                        <div class="form-text">Select drivers assigned to this team</div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="primaryDriver" class="form-label">Primary Driver</label>
                                        <select class="form-select" name="primary_driver" id="primaryDriver">
                                            <option value="">Select Primary Driver</option>
                                            @if($unassignedDrivers && $unassignedDrivers->count() > 0)
                                                @foreach($unassignedDrivers as $driver)
                                                    <option value="{{ $driver->id }}">{{ $driver->full_name }} - License: {{ $driver->license_number }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available drivers</option>
                                            @endif
                                        </select>
                                        <div class="form-text">Main driver for team operations</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-12 mt-4">
                            <h6 class="text-muted fw-bold mb-3">Additional Information</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="formation_date" id="formationDate" placeholder=" ">
                                <label for="formationDate">Formation Date</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="contact_number" id="contactNumber" placeholder=" ">
                                <label for="contactNumber">Team Contact Number</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="teamNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="teamNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Team</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Team Modal -->
<div class="modal fade" id="editTeamModal" tabindex="-1" aria-labelledby="editTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTeamModalLabel">Edit Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTeamForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="team_id" id="editTeamId">
                <div class="modal-body">
                    <!-- Same form structure as add modal but with edit prefix on IDs -->
                    <div class="row g-3">
                        <!-- Basic Team Information -->
                        <div class="col-12">
                            <h6 class="text-muted fw-bold mb-3">Basic Team Information</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_name" id="editTeamName" placeholder=" " required>
                                <label for="editTeamName" class="required-field">Team Name</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_code" id="editTeamCode" placeholder=" " required>
                                <label for="editTeamCode" class="required-field">Team Code</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="team_location" id="editTeamLocation" required>
                                    <option value="">Select Location</option>

                                    <!-- Ahafo Region -->
                                    <optgroup label="Ahafo Region">
                                      <option value="goaso">Goaso</option>
                                      <option value="bechem">Bechem</option>
                                      <option value="kenyasi">Kenyasi</option>
                                    </optgroup>

                                    <!-- Ashanti Region -->
                                    <optgroup label="Ashanti Region">
                                      <option value="kumasi">Kumasi</option>
                                      <option value="obuasi">Obuasi</option>
                                      <option value="konongo">Konongo</option>
                                      <option value="ejura">Ejura</option>
                                      <option value="mampong">Mampong</option>
                                    </optgroup>

                                    <!-- Bono Region -->
                                    <optgroup label="Bono Region">
                                      <option value="sunyani">Sunyani</option>
                                      <option value="dormaa-ahu">Dormaa Ahenkro</option>
                                      <option value="berekum">Berekum</option>
                                    </optgroup>

                                    <!-- Bono East Region -->
                                    <optgroup label="Bono East Region">
                                      <option value="techiman">Techiman</option>
                                      <option value="kintampo">Kintampo</option>
                                      <option value="nkoranza">Nkoranza</option>
                                    </optgroup>

                                    <!-- Central Region -->
                                    <optgroup label="Central Region">
                                      <option value="cape-coast">Cape Coast</option>
                                      <option value="elmina">Elmina</option>
                                      <option value="winneba">Winneba</option>
                                      <option value="agona-swedru">Agona Swedru</option>
                                    </optgroup>

                                    <!-- Eastern Region -->
                                    <optgroup label="Eastern Region">
                                      <option value="koforidua">Koforidua</option>
                                      <option value="nkawkaw">Nkawkaw</option>
                                      <option value="akim-oda">Akim Oda</option>
                                      <option value="nsawam">Nsawam</option>
                                    </optgroup>

                                    <!-- Greater Accra Region -->
                                    <optgroup label="Greater Accra Region">
                                      <option value="accra">Accra</option>
                                      <option value="tema">Tema</option>
                                      <option value="madina">Madina</option>
                                      <option value="ashaiman">Ashaiman</option>
                                      <option value="teshie">Teshie</option>
                                      <option value="lapaz">La Paz</option>
                                    </optgroup>

                                    <!-- North East Region -->
                                    <optgroup label="North East Region">
                                      <option value="nalerigu">Nalerigu</option>
                                      <option value="walewale">Walewale</option>
                                      <option value="chereponi">Chereponi</option>
                                    </optgroup>

                                    <!-- Northern Region -->
                                    <optgroup label="Northern Region">
                                      <option value="tamale">Tamale</option>
                                      <option value="yendi">Yendi</option>
                                      <option value="saboba">Saboba</option>
                                    </optgroup>

                                    <!-- Oti Region -->
                                    <optgroup label="Oti Region">
                                      <option value="dambai">Dambai</option>
                                      <option value="krachi-east">Krachi East</option>
                                      <option value="nkwanta">Nkwanta</option>
                                    </optgroup>

                                    <!-- Savannah Region -->
                                    <optgroup label="Savannah Region">
                                      <option value="damongo">Damongo</option>
                                      <option value="buipe">Buipe</option>
                                      <option value="salaga">Salaga</option>
                                    </optgroup>

                                    <!-- Upper East Region -->
                                    <optgroup label="Upper East Region">
                                      <option value="bolgatanga">Bolgatanga</option>
                                      <option value="navrongo">Navrongo</option>
                                      <option value="bawku">Bawku</option>
                                    </optgroup>

                                    <!-- Upper West Region -->
                                    <optgroup label="Upper West Region">
                                      <option value="wa">Wa</option>
                                      <option value="jirapa">Jirapa</option>
                                      <option value="lawra">Lawra</option>
                                    </optgroup>

                                    <!-- Volta Region -->
                                    <optgroup label="Volta Region">
                                      <option value="ho">Ho</option>
                                      <option value="hohoe">Hohoe</option>
                                      <option value="kpando">Kpando</option>
                                      <option value="sogakope">Sogakope</option>
                                    </optgroup>

                                    <!-- Western Region -->
                                    <optgroup label="Western Region">
                                      <option value="takoradi">Takoradi</option>
                                      <option value="sekondi">Sekondi</option>
                                      <option value="tarkwa">Tarkwa</option>
                                      <option value="prestea">Prestea</option>
                                    </optgroup>

                                    <!-- Western North Region -->
                                    <optgroup label="Western North Region">
                                      <option value="sefwi-wiawso">Sefwi Wiawso</option>
                                      <option value="bibiani">Bibiani</option>
                                      <option value="juaboso">Juaboso</option>
                                    </optgroup>
                                </select>
                                <label for="editTeamLocation" class="required-field">Team Location</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="team_status" id="editTeamStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="deployed">Deployed</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                                <label for="editTeamStatus" class="required-field">Team Status</label>
                            </div>
                        </div>

                        <!-- Team Member Allocation -->
                        <div class="col-12 mt-4">
                            <div class="allocation-section">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-users me-2"></i>Team Member Allocation
                                </h6>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="editTeamMembers" class="form-label">Select Team Members</label>
                                        <select class="form-select" name="team_members[]" id="editTeamMembers" multiple="multiple" style="width: 100%;">
                                            @if($unassignedTeamMembers && $unassignedTeamMembers->count() > 0)
                                                @foreach($unassignedTeamMembers as $member)
                                                    <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->position }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available team members</option>
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="editTeamLead" class="form-label">Team Lead</label>
                                        <select class="form-select" name="team_lead" id="editTeamLead">
                                            <option value="">Select Team Lead</option>
                                            @if($unassignedTeamMembers && $unassignedTeamMembers->count() > 0)
                                                @foreach($unassignedTeamMembers as $member)
                                                    <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->position }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available team members</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row g-3 mt-2">
                                    <div class="col-md-12">
                                        <label class="form-label">Team Allocation Details</label>
                                        <textarea class="form-control" name="team_allocation" id="editTeamAllocation" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Assignment -->
                        <div class="col-12 mt-4">
                            <div class="allocation-section">
                                <h6 class="text-success fw-bold mb-3">
                                    <i class="fas fa-car me-2"></i>Vehicle Assignment
                                </h6>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="editAssignedVehicles" class="form-label">Assigned Vehicles</label>
                                        <select class="form-select" name="assigned_vehicles[]" id="editAssignedVehicles" multiple="multiple" style="width: 100%;">
                                            @if($unassignedVehicles && $unassignedVehicles->count() > 0)
                                                @foreach($unassignedVehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}">{{ $vehicle->registration_number }} - {{ $vehicle->make }} {{ $vehicle->model }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available vehicles</option>
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="editPrimaryVehicle" class="form-label">Primary Vehicle</label>
                                        <select class="form-select" name="primary_vehicle" id="editPrimaryVehicle">
                                            <option value="">Select Primary Vehicle</option>
                                            @if($unassignedVehicles && $unassignedVehicles->count() > 0)
                                                @foreach($unassignedVehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}">{{ $vehicle->registration_number }} - {{ $vehicle->make }} {{ $vehicle->model }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available vehicles</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Driver Assignment -->
                        <div class="col-12 mt-4">
                            <div class="allocation-section">
                                <h6 class="text-warning fw-bold mb-3">
                                    <i class="fas fa-id-card me-2"></i>Driver Assignment
                                </h6>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="editAssignedDrivers" class="form-label">Assigned Drivers</label>
                                        <select class="form-select" name="assigned_drivers[]" id="editAssignedDrivers" multiple="multiple" style="width: 100%;">
                                            @if($unassignedDrivers && $unassignedDrivers->count() > 0)
                                                @foreach($unassignedDrivers as $driver)
                                                    <option value="{{ $driver->id }}">{{ $driver->full_name }} - License: {{ $driver->license_number }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available drivers</option>
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="editPrimaryDriver" class="form-label">Primary Driver</label>
                                        <select class="form-select" name="primary_driver" id="editPrimaryDriver">
                                            <option value="">Select Primary Driver</option>
                                            @if($unassignedDrivers && $unassignedDrivers->count() > 0)
                                                @foreach($unassignedDrivers as $driver)
                                                    <option value="{{ $driver->id }}">{{ $driver->full_name }} - License: {{ $driver->license_number }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No available drivers</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-12 mt-4">
                            <h6 class="text-muted fw-bold mb-3">Additional Information</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="formation_date" id="editFormationDate" placeholder=" ">
                                <label for="editFormationDate">Formation Date</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="contact_number" id="editContactNumber" placeholder=" ">
                                <label for="editContactNumber">Team Contact Number</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="editTeamNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="editTeamNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Team</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Team Modal -->
<div class="modal fade" id="viewTeamModal" tabindex="-1" aria-labelledby="viewTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTeamModalLabel">Team Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Team details will be populated here -->
                    <div class="col-12" id="teamDetails">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editFromView">Edit Team</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTeamModal" tabindex="-1" aria-labelledby="deleteTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTeamModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this team?</p>
                <p><strong>Team Name:</strong> <span id="deleteTeamName"></span></p>
                <p><strong>Team Code:</strong> <span id="deleteTeamCode"></span></p>
                <p class="text-danger"><small>This action cannot be undone. All team allocations will be removed.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete Team</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Allocation Modal -->
<div class="modal fade" id="bulkAllocationModal" tabindex="-1" aria-labelledby="bulkAllocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkAllocationModalLabel">Bulk Team Allocation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkAllocationForm" method="POST" action="/company/MasterTracker/team-pairing/bulk-allocation">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Use this feature to quickly allocate multiple resources to selected teams.
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="bulkTeams" class="form-label">Select Teams</label>
                            <select class="form-select" name="teams[]" id="bulkTeams" multiple="multiple" style="width: 100%;">
                                @if($teams && $teams->count() > 0)
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->team_name }} ({{ $team->team_code }})</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No teams available</option>
                                @endif
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="bulkMembers" class="form-label">Assign Members</label>
                            <select class="form-select" name="members[]" id="bulkMembers" multiple="multiple" style="width: 100%;">
                                @if($unassignedTeamMembers && $unassignedTeamMembers->count() > 0)
                                    @foreach($unassignedTeamMembers as $member)
                                        <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->position }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No available team members</option>
                                @endif
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="bulkVehicles" class="form-label">Assign Vehicles</label>
                            <select class="form-select" name="vehicles[]" id="bulkVehicles" multiple="multiple" style="width: 100%;">
                                @if($unassignedVehicles && $unassignedVehicles->count() > 0)
                                    @foreach($unassignedVehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->registration_number }} - {{ $vehicle->make }} {{ $vehicle->model }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No available vehicles</option>
                                @endif
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="bulkDrivers" class="form-label">Assign Drivers</label>
                            <select class="form-select" name="drivers[]" id="bulkDrivers" multiple="multiple" style="width: 100%;">
                                @if($unassignedDrivers && $unassignedDrivers->count() > 0)
                                    @foreach($unassignedDrivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->full_name }} - License: {{ $driver->license_number }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No available drivers</option>
                                @endif
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label for="bulkNotes" class="form-label">Allocation Notes</label>
                            <textarea class="form-control" name="notes" id="bulkNotes" rows="3" placeholder="Add notes about this bulk allocation..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply Allocation</button>
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
                <h5 class="modal-title" id="exportModalLabel">Export Team Data</h5>
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
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeMembers" name="include_members" checked>
                            <label class="form-check-label" for="includeMembers">
                                Include Team Members Details
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeVehicles" name="include_vehicles" checked>
                            <label class="form-check-label" for="includeVehicles">
                                Include Vehicle Information
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeDrivers" name="include_drivers" checked>
                            <label class="form-check-label" for="includeDrivers">
                                Include Driver Information
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

@push('javascript')
<!-- DataTables CDN (load first) -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Load initial stats
    loadDashboardStats();
    updateLastUpdatedTime();
    
    // Initialize Select2 for multi-select dropdowns
    initializeSelect2();
    
    // Load dropdown options
    loadDropdownOptions();
    
    // Initialize DataTable with client-side processing (only if DataTables is loaded)
    if (typeof $.fn.DataTable !== 'undefined') {
        let table = $('#teams-datatable').DataTable({
            responsive: true,
            processing: false,
            serverSide: false,
            order: [[0, 'asc']],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            columnDefs: [
                { orderable: false, targets: [7] } // Actions column
            ],
            language: {
                emptyTable: "No teams found",
                zeroRecords: "No matching teams found"
            }
        });
    } else {
        console.log('DataTables not loaded, skipping initialization');
    }

    // Search functionality (only if DataTables is loaded)
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#searchTeams').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Filter functionality (client-side)
        $('#applyFilters').on('click', function() {
            table.draw();
        });

        $('#clearFilters').on('click', function() {
            $('#filterStatus, #filterLocation, #filterTeamSize').val('');
            table.draw();
        });
    }

    // Initialize regular dropdowns (no Select2)
    function initializeSelect2() {
        // No Select2 initialization - using regular HTML select elements
        console.log('Using regular HTML select elements');
    }

    // Dropdown options are now populated directly from Laravel data
    function loadDropdownOptions() {
        // No need to load via AJAX since data is already in the blade template
        console.log('Dropdown options loaded from Laravel data');
    }

    // Update team lead options based on selected members
    $('#teamMembers').on('change', function() {
        updateTeamLeadOptions($(this).val(), '#teamLead');
    });

    $('#editTeamMembers').on('change', function() {
        updateTeamLeadOptions($(this).val(), '#editTeamLead');
    });

    function updateTeamLeadOptions(selectedMembers, targetSelect) {
        let options = '<option value="">Select Team Lead</option>';
        if (selectedMembers && selectedMembers.length > 0) {
            selectedMembers.forEach(memberId => {
                // Get member text from the source select
                let sourceSelect = targetSelect === '#editTeamLead' ? '#editTeamMembers' : '#teamMembers';
                let memberText = $(`${sourceSelect} option[value="${memberId}"]`).text();
                if (memberText) {
                    options += `<option value="${memberId}">${memberText}</option>`;
                }
            });
        } else {
            // If no members selected, show all available members
            let sourceSelect = targetSelect === '#editTeamLead' ? '#editTeamMembers' : '#teamMembers';
            $(`${sourceSelect} option:not(:first)`).each(function() {
                if ($(this).val() !== '') {
                    options += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                }
            });
        }
        $(targetSelect).html(options);
    }

    // Update primary vehicle options based on assigned vehicles
    $('#assignedVehicles').on('change', function() {
        updatePrimaryOptions($(this).val(), '#primaryVehicle', '#assignedVehicles');
    });

    $('#editAssignedVehicles').on('change', function() {
        updatePrimaryOptions($(this).val(), '#editPrimaryVehicle', '#editAssignedVehicles');
    });

    // Update primary driver options based on assigned drivers
    $('#assignedDrivers').on('change', function() {
        updatePrimaryOptions($(this).val(), '#primaryDriver', '#assignedDrivers');
    });

    $('#editAssignedDrivers').on('change', function() {
        updatePrimaryOptions($(this).val(), '#editPrimaryDriver', '#editAssignedDrivers');
    });

    function updatePrimaryOptions(selectedItems, targetSelect, sourceSelect) {
        let options = '<option value="">Select Primary</option>';
        if (selectedItems && selectedItems.length > 0) {
            selectedItems.forEach(itemId => {
                let itemText = $(`${sourceSelect} option[value="${itemId}"]`).text();
                if (itemText) {
                    options += `<option value="${itemId}">${itemText}</option>`;
                }
            });
        } else {
            // If no items selected, show all available items
            $(`${sourceSelect} option:not(:first)`).each(function() {
                if ($(this).val() !== '') {
                    options += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                }
            });
        }
        $(targetSelect).html(options);
    }

    // Handle add team form submission (exactly like categories)
    const addTeamForm = document.getElementById('addTeamForm');
    console.log('Add team form found:', addTeamForm);
    
    if (addTeamForm) {
        console.log('Adding event listener to add team form');
        addTeamForm.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            e.preventDefault();
            console.log('Default prevented');

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: new FormData(this)
            })
            .then(response => {
                console.log('Fetch response:', response);
                return response.json();
            })
            .then(response => {
                console.log('Parsed response:', response);
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTeamModal'));
                modal.hide();

                if (response.status === 'success') {
                    console.log('Showing success SweetAlert');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        console.log('SweetAlert result:', result);
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    throw new Error(response.message || 'Something went wrong');
                }
            })
            .catch(error => {
                console.log('Error caught:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to create team. Please try again.',
                    showConfirmButton: true,
                    confirmButtonColor: '#3085d6'
                });
            });
        });
    } else {
        console.error('Add team form not found!');
    }

    // View Team
    $(document).on('click', '.view-team', function() {
        let teamId = $(this).data('id');
        
        $.ajax({
            url: `/company/MasterTracker/team-pairing/${teamId}`,
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let team = response.data;
                    let detailsHtml = generateTeamDetailsHtml(team);
                    $('#teamDetails').html(detailsHtml);
                    $('#editFromView').data('id', teamId);
                    $('#viewTeamModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load team details.'
                });
            }
        });
    });

    // Edit Team
    $(document).on('click', '.edit-team', function() {
        let teamId = $(this).data('id');
        loadTeamForEdit(teamId);
    });

    // Edit from view modal
    $('#editFromView').on('click', function() {
        let teamId = $(this).data('id');
        $('#viewTeamModal').modal('hide');
        loadTeamForEdit(teamId);
    });

    // Delete Team
    $(document).on('click', '.delete-team', function() {
        let teamId = $(this).data('id');
        let teamName = $(this).data('name');
        let teamCode = $(this).data('code');
        
        // Use SweetAlert for confirmation instead of modal
        Swal.fire({
            title: 'Are you sure?',
            html: `
                <div class="text-start">
                    <p>You are about to delete the following team:</p>
                    <div class="bg-light p-3 rounded mt-2">
                        <strong>Team Name:</strong> ${teamName}<br>
                        <strong>Team Code:</strong> ${teamCode}
                    </div>
                    <p class="text-danger mt-2"><small>This action cannot be undone. All team allocations will be removed.</small></p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with deletion
                deleteTeam(teamId);
            }
        });
    });

    // Delete Team Function
    function deleteTeam(teamId) {
        // Show loading message
        Swal.fire({
            title: 'Deleting Team...',
            text: 'Please wait while we delete the team.',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: `/company/MasterTracker/team-pairing/${teamId}`,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to delete team.'
                });
            }
        });
    }

    // Confirm Delete (legacy - keeping for modal compatibility)
    $('#confirmDelete').on('click', function() {
        let teamId = $(this).data('id');
        deleteTeam(teamId);
        $('#deleteTeamModal').modal('hide');
    });

    // Update Team Form Submit
    $('#editTeamForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let teamId = $('#editTeamId').val();
        
        $.ajax({
            url: `/company/MasterTracker/team-pairing/${teamId}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
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

    // Allocate Members
    $(document).on('click', '.allocate-members', function() {
        let teamId = $(this).data('id');
        // Open allocation modal for specific team
        loadTeamForAllocation(teamId);
    });

    // Bulk Allocation Form Submit
    $('#bulkAllocationForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: '/company/MasterTracker/team-pairing/bulk-allocation',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Failed to apply bulk allocation.'
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
        
        // Show loading message
        Swal.fire({
            title: 'Preparing Export...',
            text: 'Please wait while we prepare your data for download.',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Create download link
        let downloadUrl = `/company/MasterTracker/team-pairing/export?${params.toString()}`;
        
        // Create a temporary link to trigger download
        let link = document.createElement('a');
        link.href = downloadUrl;
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show success message
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Export Started!',
                text: 'Your data export has been initiated. Check your downloads folder.',
                showConfirmButton: true,
                confirmButtonColor: '#3085d6'
            });
        }, 1000);
        
        $('#exportModal').modal('hide');
    });

    // Download team report
    $('#downloadTeamReport').on('click', function(e) {
        e.preventDefault();
        
        // Show loading message
        Swal.fire({
            title: 'Generating Report...',
            text: 'Please wait while we generate your team report.',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Open report in new tab
        let reportWindow = window.open('/company/MasterTracker/team-pairing/report', '_blank');
        
        // Show success message
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Report Generated!',
                text: 'Your team report has been generated and opened in a new tab.',
                showConfirmButton: true,
                confirmButtonColor: '#3085d6'
            });
        }, 1500);
    });

    // Helper functions
    function loadTeamForEdit(teamId) {
        $.ajax({
            url: `/company/MasterTracker/team-pairing/${teamId}/edit`,
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let team = response.data;
                    populateEditForm(team);
                    $('#editTeamModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load team for editing.'
                });
            }
        });
    }

    function populateEditForm(team) {
        console.log('Full team data for edit:', team);
        console.log('Formation date value:', team.formation_date);
        console.log('Formation date type:', typeof team.formation_date);
        
        $('#editTeamId').val(team.id);
        $('#editTeamName').val(team.team_name);
        $('#editTeamCode').val(team.team_code);
        $('#editTeamLocation').val(team.team_location);
        $('#editTeamStatus').val(team.team_status);
        $('#editTeamAllocation').val(team.team_allocation);
        
        // Format formation date for HTML date input (YYYY-MM-DD)
        if (team.formation_date) {
            const formationDate = new Date(team.formation_date);
            const formattedDate = formationDate.toISOString().split('T')[0];
            console.log('Setting formation date to:', formattedDate);
            $('#editFormationDate').val(formattedDate);
        } else {
            console.log('No formation date found, clearing field');
            $('#editFormationDate').val('');
        }
        $('#editContactNumber').val(team.contact_number);
        $('#editTeamNotes').val(team.notes);
        
        // Add assigned members to edit dropdown if not already present
        if (team.team_members && team.team_members.length > 0) {
            team.team_members.forEach(member => {
                if ($(`#editTeamMembers option[value="${member.id}"]`).length === 0) {
                    $('#editTeamMembers').append(`<option value="${member.id}">${member.full_name} - ${member.position}</option>`);
                }
            });
            $('#editTeamMembers').val(team.team_members.map(m => m.id)).trigger('change');
        }
        
        // Add assigned vehicles to edit dropdown if not already present
        if (team.vehicles && team.vehicles.length > 0) {
            team.vehicles.forEach(vehicle => {
                if ($(`#editAssignedVehicles option[value="${vehicle.id}"]`).length === 0) {
                    $('#editAssignedVehicles').append(`<option value="${vehicle.id}">${vehicle.registration_number} - ${vehicle.make} ${vehicle.model}</option>`);
                }
            });
            $('#editAssignedVehicles').val(team.vehicles.map(v => v.id)).trigger('change');
        }
        
        // Add assigned drivers to edit dropdown if not already present
        if (team.drivers && team.drivers.length > 0) {
            team.drivers.forEach(driver => {
                if ($(`#editAssignedDrivers option[value="${driver.id}"]`).length === 0) {
                    $('#editAssignedDrivers').append(`<option value="${driver.id}">${driver.full_name} - License: ${driver.license_number}</option>`);
                }
            });
            $('#editAssignedDrivers').val(team.drivers.map(d => d.id)).trigger('change');
        }
        
        // Set primary selections
        if (team.team_lead) {
            $('#editTeamLead').val(team.team_lead);
        }
        if (team.primary_vehicle) {
            $('#editPrimaryVehicle').val(team.primary_vehicle);
        }
        if (team.primary_driver) {
            $('#editPrimaryDriver').val(team.primary_driver);
        }
    }

    function generateTeamDetailsHtml(team) {
        return `
            <div class="team-details-container">
                <!-- Team Header Section -->
                <div class="team-header-section mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="team-icon me-3">
                            <i class="fas fa-users text-primary fs-2"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-white fw-bold">${team.team_name}</h4>
                            <p class="mb-0 text-white-50">Team Code: <span class="badge bg-light text-dark">${team.team_code}</span></p>
                        </div>
                        <div class="ms-auto">
                            <span class="badge ${team.team_status === 'active' ? 'bg-success' : team.team_status === 'inactive' ? 'bg-secondary' : team.team_status === 'deployed' ? 'bg-info' : 'bg-warning'} fs-6">
                                ${team.team_status.charAt(0).toUpperCase() + team.team_status.slice(1)}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong><i class="fas fa-map-marker-alt text-success me-2"></i>Location:</strong> ${team.team_location.charAt(0).toUpperCase() + team.team_location.slice(1)}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong><i class="fas fa-calendar-alt text-info me-2"></i>Formation Date:</strong> ${team.formation_date ? new Date(team.formation_date).toLocaleDateString() : 'Not Set'}</p>
                        </div>
                    </div>
                </div>

                <!-- Team Members Section -->
                <div class="team-section mb-4">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="fas fa-users text-primary me-2"></i>
                            Team Members
                            <span class="badge bg-primary ms-2">${team.team_members ? team.team_members.length : 0}</span>
                        </h5>
                    </div>
                    <div class="section-content">
                        ${team.team_members && team.team_members.length > 0 ? 
                            `<div class="row">
                                ${team.team_members.map(member => `
                                    <div class="col-md-6 mb-3">
                                        <div class="member-card ${member.id == team.team_lead ? 'team-lead-card' : ''}">
                                            <div class="d-flex align-items-center">
                                                <div class="member-avatar me-3">
                                                    <i class="fas fa-user-circle text-primary fs-4"></i>
                                                </div>
                                                <div class="member-info">
                                                    <h6 class="mb-1 fw-bold">${member.full_name}</h6>
                                                    <p class="mb-1 text-muted small">${member.position || 'Position not set'}</p>
                                                    <p class="mb-0 text-muted small">ID: ${member.employee_id}</p>
                                                    ${member.id == team.team_lead ? '<span class="badge bg-warning text-dark small">Team Lead</span>' : ''}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>` : 
                            '<div class="empty-state"><i class="fas fa-user-slash text-muted fs-1 mb-3"></i><p class="text-muted">No team members assigned</p></div>'}
                    </div>
                </div>

                <!-- Vehicles Section -->
                <div class="team-section mb-4">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="fas fa-truck text-success me-2"></i>
                            Assigned Vehicles
                            <span class="badge bg-success ms-2">${team.vehicles ? team.vehicles.length : 0}</span>
                        </h5>
                    </div>
                    <div class="section-content">
                        ${team.vehicles && team.vehicles.length > 0 ? 
                            `<div class="row">
                                ${team.vehicles.map(vehicle => `
                                    <div class="col-md-6 mb-3">
                                        <div class="vehicle-card ${vehicle.id == team.primary_vehicle ? 'primary-vehicle-card' : ''}">
                                            <div class="d-flex align-items-center">
                                                <div class="vehicle-icon me-3">
                                                    <i class="fas fa-truck text-success fs-4"></i>
                                                </div>
                                                <div class="vehicle-info">
                                                    <h6 class="mb-1 fw-bold">${vehicle.registration_number}</h6>
                                                    <p class="mb-1 text-muted small">${vehicle.make} ${vehicle.model}</p>
                                                    <p class="mb-0 text-muted small">${vehicle.year} • ${vehicle.type}</p>
                                                    ${vehicle.id == team.primary_vehicle ? '<span class="badge bg-success text-white small">Primary Vehicle</span>' : ''}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>` : 
                            '<div class="empty-state"><i class="fas fa-truck text-muted fs-1 mb-3"></i><p class="text-muted">No vehicles assigned</p></div>'}
                    </div>
                </div>

                <!-- Drivers Section -->
                <div class="team-section mb-4">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="fas fa-id-card text-warning me-2"></i>
                            Assigned Drivers
                            <span class="badge bg-warning ms-2">${team.drivers ? team.drivers.length : 0}</span>
                        </h5>
                    </div>
                    <div class="section-content">
                        ${team.drivers && team.drivers.length > 0 ? 
                            `<div class="row">
                                ${team.drivers.map(driver => `
                                    <div class="col-md-6 mb-3">
                                        <div class="driver-card ${driver.id == team.primary_driver ? 'primary-driver-card' : ''}">
                                            <div class="d-flex align-items-center">
                                                <div class="driver-icon me-3">
                                                    <i class="fas fa-id-card text-warning fs-4"></i>
                                                </div>
                                                <div class="driver-info">
                                                    <h6 class="mb-1 fw-bold">${driver.full_name}</h6>
                                                    <p class="mb-1 text-muted small">License: ${driver.license_number}</p>
                                                    <p class="mb-0 text-muted small">${driver.experience_years} years experience</p>
                                                    ${driver.id == team.primary_driver ? '<span class="badge bg-warning text-dark small">Primary Driver</span>' : ''}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>` : 
                            '<div class="empty-state"><i class="fas fa-id-card text-muted fs-1 mb-3"></i><p class="text-muted">No drivers assigned</p></div>'}
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="team-section">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Additional Information
                        </h5>
                    </div>
                    <div class="section-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <strong><i class="fas fa-phone text-success me-2"></i>Contact Number:</strong>
                                    <p class="mb-0">${team.contact_number || 'Not provided'}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <strong><i class="fas fa-tasks text-primary me-2"></i>Allocation:</strong>
                                    <p class="mb-0">${team.team_allocation || 'Not specified'}</p>
                                </div>
                            </div>
                        </div>
                        ${team.notes ? `
                            <div class="info-item mt-3">
                                <strong><i class="fas fa-sticky-note text-warning me-2"></i>Notes:</strong>
                                <p class="mb-0">${team.notes}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    // Dashboard Stats Functions
    function loadDashboardStats() {
        $.ajax({
            url: '/company/MasterTracker/team-pairing/stats',
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    updateStatsCards(response.data);
                }
            },
            error: function() {
                // Set default values if API fails
                updateStatsCards({
                    total_teams: 0,
                    total_members: 0,
                    total_vehicles: 0,
                    total_drivers: 0
                });
            }
        });
    }

    function updateStatsCards(stats) {
        $('#totalTeams').text(stats.total_teams || 0);
        $('#totalMembers').text(stats.total_members || 0);
        $('#totalVehicles').text(stats.total_vehicles || 0);
        $('#totalDrivers').text(stats.total_drivers || 0);
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
        loadDashboardStats();
        updateLastUpdatedTime();
        
        Swal.fire({
            icon: 'success',
            title: 'Refreshed!',
            text: 'Dashboard statistics updated successfully.',
            showConfirmButton: false,
            timer: 1500
        });
    };

    // Global function for viewing allocation matrix
    window.viewAllocationMatrix = function() {
        // Show loading message
        Swal.fire({
            title: 'Loading Allocation Matrix...',
            text: 'Please wait while we prepare the allocation matrix.',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Open allocation matrix in new tab
        let matrixWindow = window.open('/company/MasterTracker/team-pairing/allocation-matrix', '_blank');
        
        // Show success message
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Allocation Matrix Loaded!',
                text: 'The allocation matrix has been opened in a new tab.',
                showConfirmButton: true,
                confirmButtonColor: '#3085d6'
            });
        }, 1000);
    };

    // Reset forms when modals are hidden
    $('#addTeamModal, #editTeamModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
    });

    // Auto-refresh stats every 5 minutes
    setInterval(function() {
        loadDashboardStats();
        updateLastUpdatedTime();
    }, 300000); // 5 minutes
});
</script>
@endpush
