@extends('layouts.vertical', ['page_title' => 'Loyalty Program'])

@section('css')
@vite([
'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'
])
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    /* Floating Label Styles */
    .form-floating {
        position: relative;
        margin-bottom: 1rem;
    }
    .form-floating input.form-control,
    .form-floating select.form-select,
    .form-floating textarea.form-control {
            height: 50px;
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            background-color: transparent;
            font-size: 1rem;
            padding: 1rem 0.75rem;
            transition: all 0.8s;
        }
        .form-floating textarea.form-control {
            min-height: 100px;
            height: auto;
            padding-top: 1.625rem;
        }
        .form-floating label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            color: #2f2f2f;
            transition: all 0.8s;
            pointer-events: none;
            z-index: 1;
        }
    .form-floating input.form-control:focus,
    .form-floating input.form-control:not(:placeholder-shown),
    .form-floating select.form-select:focus,
    .form-floating select.form-select:not([value=""]),
    .form-floating textarea.form-control:focus,
    .form-floating textarea.form-control:not(:placeholder-shown) {
        border-color: #033c42;
        box-shadow: none;
    }
    .form-floating input.form-control:focus ~ label,
    .form-floating input.form-control:not(:placeholder-shown) ~ label,
    .form-floating select.form-select:focus ~ label,
    .form-floating select.form-select:not([value=""]) ~ label,
    .form-floating textarea.form-control:focus ~ label,
    .form-floating textarea.form-control:not(:placeholder-shown) ~ label {
        height: auto;
        padding: 0 0.5rem;
        transform: translateY(-60%) translateX(0.5rem) scale(0.85);
        color: white;
        border-radius: 5px;
        z-index: 5;
    }
    .form-floating input.form-control:focus~label::before,
    .form-floating input.form-control:not(:placeholder-shown)~label::before,
    .form-floating select.form-select:focus~label::before,
    .form-floating select.form-select:not([value=""])~label::before,
    .form-floating textarea.form-control:focus~label::before,
    .form-floating textarea.form-control:not(:placeholder-shown)~label::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: #033c42;
            border-radius: 5px;
            z-index: -1;
        }
    .form-floating input.form-control:focus::placeholder {
        color: transparent;
    }

    /* Dark mode styles */
    [data-bs-theme="dark"] .form-floating input.form-control,
    [data-bs-theme="dark"] .form-floating select.form-select,
    [data-bs-theme="dark"] .form-floating textarea.form-control {
        border-color: #6c757d;
        color: #e9ecef;
    }
    
    [data-bs-theme="dark"] .form-floating label {
        color: #adb5bd;
    }

    [data-bs-theme="dark"] .form-floating input.form-control:focus,
    [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown),
    [data-bs-theme="dark"] .form-floating select.form-select:focus,
    [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]),
    [data-bs-theme="dark"] .form-floating textarea.form-control:focus,
    [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) {
        border-color: #0dcaf0;
    }

    [data-bs-theme="dark"] .form-floating input.form-control:focus ~ label::before,
    [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
    [data-bs-theme="dark"] .form-floating select.form-select:focus ~ label::before,
    [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]) ~ label::before,
    [data-bs-theme="dark"] .form-floating textarea.form-control:focus ~ label::before,
    [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
        background: #0dcaf0;
    }

    [data-bs-theme="dark"] select.form-select option {
        background-color: #212529;
        color: #e9ecef;
    }

    /* Action Button Styles */
    .action-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 10px;
        transition: transform 0.3s;
    }
    .action-btn i {
        font-size: 24px; /* Adjust icon size */
        color: #555; /* Default color */
    }
    .action-btn:hover i {
        transform: scale(1.2); /* Slightly enlarge the icon on hover */
    }
    .edit-btn i {
        color: #007bff; /* Blue for edit */
    }
    .view-btn i {
        color: #28a745; /* Green for view */
    }
    .delete-btn i {
        color: #dc3545; /* Red for delete */
    }
    .assign-btn i {
        color: #ff9900; /* Orange for assign */
    }
    /* Additional Loyalty-specific Styles */
    .tier-badge {
        padding: 0.35rem 0.65rem;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 500;
    }
    .program-card {
        border: 2px solid #033c42;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .program-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }
    .stat-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px); /* Move the card up */
        box-shadow: 0 8px 16px rgba(0,0,0,0.2); /* Increase shadow for depth */
    }
    .stat-card h5 {
        margin-top: 12%;
    }
    .stat-card.active-members {
        background-color: #007bff; /* Sky Blue */
        
        color: white;
    }
    .stat-card.active-members {
        border-left: 4px solid #a687eb;
    }

    .stat-card.points-redeemed {
        background-color: #28a745; /* Lime Green */
        color: white;
    }
    .stat-card.points-redeemed {
        border-left: 4px solid #7aed7a;
    }

    .stat-card.active-programs {
        background-color: #db9f28; /* Gold */
        color: white;
    }
    .stat-card.active-programs {
        border-left: 4px solid #f4c93b;
    }

    .stat-card.clv-increase {
        background-color: #2b5f9c;
        color: white;
    }
    .stat-card.clv-increase {
        border-left: 4px solid #3d80e4;
    }
    
    .card-header i {
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Shadow effect */
    }
    .form-check-input:checked {
        background-color: #28a745 !important; /* Green for active */
        border-color: #28a745 !important; /* Green border */
    }
    .form-check-input:not(:checked) {
        background-color: #e23535 !important; /* Red for inactive */
        border-color: #e71c31 !important; /* Red border */
    }
    /* Dark Mode Styles */
    body {
        background-color: #343a40;
        color: #ffffff;
    }
    .card {
        background-color: #495057;
        border-color: #6c757d;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    .btn-custom {
        background-color: #007bff;
        color: white;
        border-radius: 5px;
        padding: 10px 20px;
        transition: background-color 0.3s;
    }
    .btn-custom:hover {
        background-color: #0056b3;
    }
    .tier-card {
        border: 1px solid #6c757d;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    .btn-icon {
        background-color: transparent;
        border: none;
        color: #007bff;
        font-size: 1.5rem;
        margin-right: 10px;
        transition: color 0.3s;
    }

    .btn-icon:hover {
        color: #0056b3;
    }
    .btn-add-new {
        background-color: #007bff; /* Blue color */
        color: white;
        border: 2px solid #0056b3; /* Darker blue border */
        border-radius: 5px;
        padding: 10px 15px;
        font-size: 1rem;
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn-add-new:hover {
        background-color: #0056b3; /* Darker blue on hover */
        transform: scale(1.05);
    }

    .btn-add-new i {
        margin-right: 5px; /* Space between icon and text */
    }
    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
    }
    .btn-info {
        background-color: #28a745;
        color: white;
    }
    .btn-info:hover {
        background-color: #218838;
    }
    .btn-success {
        background-color: #28a745;
        color: white;
    }
    .btn-success:hover {
        background-color: #218838;
    }
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
    .btn-warning {
        background-color: #ff9900;
        color: white;
    }
    .btn-warning:hover {
        background-color: #cc7a00;
    }
    .btn-light {
        background-color: #f8f9fa;
        color: #333;
    }
    .btn-light:hover {
        background-color: #e2e6ea;
    }
    .btn-dark {
        background-color: #343a40;
        color: white;
    }
    .btn-dark:hover {
        background-color: #23272b;
    }
    .btn-link {
        background-color: transparent;
        color: #007bff;
    }
    .btn-link:hover {
        color: #0056b3;
    }
    .btn-sm {
        padding: 5px 10px;
        font-size: 0.875rem;
    }
    .btn-lg {
        padding: 15px 30px;
        font-size: 1.25rem;
    }
    .btn-block {
        width: 100%;
        padding: 10px 0;
        font-size: 1rem;
    }
    .btn-block + .btn-block {
        margin-top: 10px;
    }
    .reward-option {
    padding: 8px;
    line-height: 1.4;
}
.reward-option strong {
    display: block;
    margin-bottom: 2px;
}
.reward-details {
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 4px;
    border-left: 3px solid #0d6efd;
}
.reward-details p {
    margin-bottom: 5px;
}
.select2-container--default .select2-results__option--highlighted .reward-option {
    color: white;
}
.select2-container--default .select2-results__option--highlighted .text-primary {
    color: #a7d1ff !important;
}
/* For the link container */
#invitationLink {
    display: block;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    word-break: break-all;
}

/* Optional: Add tooltip for full link */
#invitationLink:hover {
    white-space: normal;
    overflow: visible;
}
/* Add to your existing CSS */
.form-switch .form-check-input {
    width: 2.5em;
    height: 1.5em;
    cursor: pointer;
    margin-left: 0;
}

.form-switch .form-check-input:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.form-switch .form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

/* Highlight current program row */
.table tbody tr.current-program {
    background-color: rgba(40, 167, 69, 0.1);
}
.current-program {
  background-color: rgba(0, 123, 255, 0.1);
}



.switch {
  position: relative;
  display: inline-block;
  width: 47px;
  height: 22px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 2px;
  bottom: 2px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
@endsection

@section('content')
<!-- Container Fluid -->
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <!-- Page Title Right -->
                <div class="page-title-right">
                    <div class="d-flex">
                        <!-- Button Container -->
                        <div class="button-container">
                            <!-- New Program Button -->
                            <button type="button" class="btn btn-primary me-2 createProgramForm">
                                <i class="fas fa-plus me-1"></i> New Program
                            </button>
                           
                       
                            <!-- Generate Report Button -->
                            <button type="button" class="btn btn-info btn-custom" onclick="generateReport()">
                                <i class="fas fa-chart-bar me-1"></i> Generate Report
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Page Title -->
                <h1 class="page-title">Loyalty Program</h1>
            </div>
        </div>
    </div>

    <!-- Program Overview Cards -->
    <div class="row mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card active-members">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Active Members</h5>
                    <i class="fas fa-users fa-3x"></i>
                </div>
                <div class="card-body">
                    <!-- Added ID here -->
                    <p>Number of active members: <span id="activeMembersCount">0</span></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card points-redeemed">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Points Redeemed</h5>
                    <i class="fas fa-gift fa-3x"></i>
                </div>
                <div class="card-body">
                    <!-- Added ID here -->
                    <p>Points redeemed: <span id="pointsRedeemedCount">0</span></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card active-programs">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Active Programs</h5>
                    <i class="fas fa-star fa-3x"></i>
                </div>
                <div class="card-body">
                    <!-- Added ID here -->
                    <p>Active programs: <span id="activeProgramsCount">0</span></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card clv-increase">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>CLV Increase</h5>
                    <i class="fas fa-chart-line fa-3x"></i>
                </div>
                <div class="card-body">
                    <!-- Added ID here -->
                    <p>CLV increase: <span id="clvIncreasePercent">0%</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Management -->
    <div class="card program-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-hover" id="loyaltyProgramsTable">
                    <thead>
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Program Name</th>
                            <th scope="col">Type</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Active Members</th>
                            <th scope="col">Points Issued</th>
                            <th scope="col">Redemption Rate</th>
                            <th scope="col">Status</th>
                            <th scope="col">Current</th> <!-- New column -->
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Data -->
                      
                    </tbody>
                </table>
                <nav aria-label="Page navigation example">
                    <ul class="pagination" id="loyaltyprogrampagination">
                
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Customer Segmentation Section -->

<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">Customer Segmentation</h5>
        <div class="row">
            <div class="col-md-4">
                <div class="card reward-card">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-crown text-warning reward-card-icon me-3"></i>
                        <div>
                            <h6>Platinum Members</h6>
                            <!-- Added ID here -->
                            <p class="mb-1"><span id="platinumMembersCount">0</span> customers</p>
                            <small class="text-muted">Top 5% spenders</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card reward-card">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-star text-success reward-card-icon me-3"></i>
                        <div>
                            <h6>Active Redeemers</h6>
                            <!-- Added ID here -->
                            <p class="mb-1"><span id="activeRedeemersCount">0</span> customers</p>
                            <small class="text-muted">Redeemed 3+ times</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card reward-card">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-danger reward-card-icon me-3"></i>
                        <div>
                            <h6>At-Risk Members</h6>
                            <!-- Added ID here -->
                            <p class="mb-1"><span id="atRiskMembersCount">0</span> customers</p>
                            <small class="text-muted">No activity in 90 days</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>









    <!-- Tier Management Section -->

<div class="card program-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Customer Tiers</h5>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addTierModal">
            <i class="fas fa-plus me-1"></i> Add New Tier
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tiersTable">
                <thead class="table-light">
                    <tr>
                        <th>#ID</th>
                        <th>Tier Name</th>
                        <th>Benefits</th>
                        <th>Points Required</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Will be populated dynamically -->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="dataTables_info" id="tiersInfo">
                    Showing 0 to 0 of 0 entries
                </div>
            </div>
            <div class="col-md-6">
                <div class="dataTables_paginate paging_simple_numbers">
                    <ul class="pagination justify-content-end" id="tiersPagination">
                        <li class="page-item disabled" id="tiersPrev">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item" id="tiersNext">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>






  <!-- Points Redemption Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Points Redemption</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#redemptionModal">
                    <i class="fas fa-plus me-1"></i> New Redemption
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="redemptionsTable">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Customer</th>
                                <th>Reward</th>
                                <th>Points Used</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Will be populated by AJAX -->
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="dataTables_info" id="redemptionsInfo"></div>
                        <div class="dataTables_paginate">
                            <ul class="pagination" id="redemptionsPagination"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Redemption Modal -->
<div class="modal fade" id="redemptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">New Redemption</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="redemptionForm">
                    <input type="hidden" id="redemptionId">
                    <div class="mb-3">
                        <label for="customerSelect" class="form-label">Customer</label>
                        <select class="form-select" id="customerSelect" required>
                            <option value="">Select Customer</option>
                            <!-- AJAX will populate this -->
                        </select>
                        <div id="customerPoints" class="mt-1 small text-muted"></div>
                    </div>
                    <div class="mb-3">
                        <label for="rewardSelect" class="form-label">Reward</label>
                        <select class="form-select" id="rewardSelect" required disabled>
                            <option value="">Select Customer first</option>
                        </select>
                        <div id="rewardDetails" class="mt-2 small text-muted"></div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveRedemption">Save</button>
            </div>
        </div>
    </div>
</div>
    
    <!-- Personalized Offers Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Personalized Offers</h5>
                </div>
                <div class="card-body">
                    <p>Special offer for you: <strong>{{ isset($personalizedOffer) ? $personalizedOffer : 'No offers available' }}</strong></p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#personalizedOffersModal">View Offer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Program Section -->
    <!-- Referral Program Modal -->
<div class="modal fade" id="referralProgramModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="referralModalTitle">New Referral</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="referralForm">
                    <input type="hidden" id="referralId">
                    <div class="form-floating mb-3">
                        <select class="form-control" id="customerReferralSelect" required>
                            <option value="">Select Customer</option>
                            <!-- Populated via AJAX -->
                        </select>
                        <label for="customerReferralSelect">Referrer *</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="friendEmail" placeholder="Friend's Email" required>
                        <label for="friendEmail">Friend's Email *</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="pointsAwarded" placeholder="Points" value="500" min="0" required>
                        <label for="pointsAwarded">Points Awarded *</label>
                    </div>
                 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveReferralBtn">Save Referral</button>
            </div>
        </div>
    </div>
</div>


    <!-- View Referral Modal -->
<div class="modal fade" id="viewReferralModal" tabindex="-1" aria-labelledby="viewReferralModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewReferralModalLabel">Referral Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Referrer Information</h6>
                        <div class="mb-3">
                            <label class="form-label">Name:</label>
                            <p class="form-control-static" id="referrerName"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <p class="form-control-static" id="referrerEmail"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone:</label>
                            <p class="form-control-static" id="referrerPhone"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Invited User Information</h6>
                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <p class="form-control-static" id="invitedEmail"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status:</label>
                            <p class="form-control-static">
                                <span class="badge" id="referralStatus"></span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Invitation Link:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="invitationLinkInput" readonly>
                                <button class="btn btn-outline-secondary" type="button" id="copyInviteLink">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <small class="text-muted">Click the copy button to share</small>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Points Awarded:</label>
                            <p class="form-control-static" id="pointsAwarded"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Date Invited:</label>
                            <p class="form-control-static" id="dateInvited"></p>
                        </div>
                    </div>
                </div>
                <div class="row" id="refereeInfoSection" style="display: none;">
                    <div class="col-12">
                        <hr>
                        <h6>Referred User Details</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name:</label>
                                    <p class="form-control-static" id="refereeName"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Signup Date:</label>
                                    <p class="form-control-static" id="refereeSignupDate"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="copyInviteLink">
                    <i class="fas fa-copy me-1"></i> Copy Link
                </button>
            </div>
        </div>
    </div>
</div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Referral Program</h5>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#referralProgramModal">
                        <i class="fas fa-plus me-1"></i> Refer Now
                    </button>
                </div>
                <div class="card-body">
                    <!-- Referral Content -->
                    <div class="table-responsive">
                        <table class="table table-striped" id="referralsTable">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Referrer</th>
                                    <th>Invited Email</th>
                                    <th>Referred Customer</th>
                                    <th>Points Awarded</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="dataTables_info" id="referralsInfo">
                                Showing 0 to 0 of 0 entries
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dataTables_paginate paging_simple_numbers">
                                <ul class="pagination justify-content-end" id="referralsPagination">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   

    <!-- Notifications Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <strong>Notice:</strong> Your points will expire in <strong>30 days</strong>! Check your rewards.
            </div>
        </div>
    </div>

    <!-- Feedback Mechanism Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Feedback</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="feedback" class="form-label">Your Feedback</label>
                            <textarea class="form-control" id="feedback" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#feedbackModal">Submit Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




 <!-- Add Tier Modal -->
<div class="modal fade" id="addTierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Tier</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTierForm">
                    <div class="form-floating mb-3">
                        <select class="form-control" id="programSelect" required>
                            <option value="">Select a Program</option>
                            <!-- Options populated dynamically -->
                        </select>
                        <label for="programSelect">Program *</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="tierName" required minlength="2" maxlength="255">
                        <label for="tierName">Tier Name *</label>
                        <div class="invalid-feedback">Please provide a tier name (2-255 characters)</div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="tierBenefits" maxlength="500"></textarea>
                        <label for="tierBenefits">Benefits</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="tierCriteria" required min="0">
                        <label for="tierCriteria">Points Required *</label>
                        <div class="invalid-feedback">Please enter a positive number</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary addTierbtn">Add Tier</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Tier Modal -->
<div class="modal fade" id="editTierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Tier</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTierForm">
                    <input type="hidden" id="editTierId">
                    <div class="form-floating mb-3">
                        <select class="form-control" id="editProgramSelect" required>
                            {{-- <option value="">Select a Program</option> --}}
                            <!-- Options will be populated dynamically -->
                        </select>
                        <label for="editProgramSelect">Program</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="editTierName" required>
                        <label for="editTierName">Tier Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="editTierBenefits">
                        <label for="editTierBenefits">Benefits</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="editTierCriteria" min="0" required>
                        <label for="editTierCriteria">Points Required</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="updateTierBtn">Update Tier</button>
            </div>
        </div>
    </div>
</div>
 







  

    







    <!-- Add New Program Modal -->
    <div class="modal fade" id="addProgramModal" tabindex="-1" aria-labelledby="addProgramModalLabel" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProgramModalLabel">Add New Loyalty Program</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createProgramForm">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Program Name" required>
                            <label for="name">Program Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-control" id="program_type" name="program_type" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="points">Points</option>
                                <option value="tier">Tier</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                            <label for="program_type">Program Type</label>
                        </div>

                       
                        
                    <!-- Customer Selection with Checkboxes -->
                    <div class="mb-3">
                        <label class="form-label">Customer Categories</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllCustomers">
                            <label class="form-check-label" for="selectAllCustomers">
                                Select All
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input customer-checkbox" type="checkbox" name="customer_category[]" value="standard" id="standardCustomer">
                            <label class="form-check-label" for="standardCustomer">
                                Standard
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input customer-checkbox" type="checkbox" name="customer_category[]" value="VIP" id="vipCustomer">
                            <label class="form-check-label" for="vipCustomer">
                                VIP
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input customer-checkbox" type="checkbox" name="customer_category[]" value="HVC" id="hvcCustomer">
                            <label class="form-check-label" for="hvcCustomer">
                                HVC (High Value Customer)
                            </label>
                        </div>
                    </div>








                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="description" name="description" placeholder="Program Description"></textarea>
                            <label for="description">Description</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                            <label for="start_date">Start Date</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="end_date" name="end_date">
                            <label for="end_date">End Date</label>
                        </div>
                         

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" step="1" min="1" class="form-control" id="points" name="points" placeholder="Points" required>
                                <label for="points">Points</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" step="0.01" min="0.01" class="form-control" id="currency_value" name="currency_value" placeholder="Currency Value" required>
                                <label for="currency_value">Currency Value (GHS)</label>
                            </div>
                        </div>
                        <small class="text-muted">Example: 500 points per 1 GHS</small>
                    </div>



                     
                   

                        
                        <button type="submit" class="btn btn-primary" id="createProgrambtn">Create Program</button>
                    </form>
                    
                    <div id="feedbackMessage" class="mt-3" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Loyalty Program Modal -->
    <div class="modal fade" id="editLoyaltyProgramModal" tabindex="-1" aria-labelledby="editLoyaltyProgramModalLabel" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLoyaltyProgramModalLabel">Edit Loyalty Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editLoyaltyProgramForm">
                        <!-- Hidden ID -->
                        <input type="hidden" id="editProgramId" name="id">
                    
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="programName" name="name" placeholder="Program Name" required>
                            <label for="programName">Program Name</label>
                        </div>
                    
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="programType" name="program_type" placeholder="Program Type" required>
                            <label for="programType">Program Type</label>
                        </div>

                              <div class="mb-3">
                        <label class="form-label">Customer Categories</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllCustomers">
                            <label class="form-check-label" for="selectAllCustomers">
                                Select All
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input customer-checkbox" type="checkbox" name="edit_customers_category[]" value="standard" id="standardCustomer">
                            <label class="form-check-label" for="standardCustomer">
                                Standard
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input customer-checkbox" type="checkbox" name="edit_customers_category[]" value="VIP" id="vipCustomer">
                            <label class="form-check-label" for="vipCustomer">
                                VIP
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input customer-checkbox" type="checkbox" name="edit_customers_category[]" value="HVC" id="hvcCustomer">
                            <label class="form-check-label" for="hvcCustomer">
                                HVC (High Value Customer)
                            </label>
                        </div>
                    </div>

                    
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="programDescription" name="description" placeholder="Description" style="height: 100px"></textarea>
                            <label for="programDescription">Description</label>
                        </div>
                    
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="startDate" name="start_date" required>
                            <label for="startDate">Start Date</label>
                        </div>
                    
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="endDate" name="end_date">
                            <label for="endDate">End Date</label>
                        </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" step="1" min="1" class="form-control" id="edit_points" name="edit_points" placeholder="Points" required>
                                <label for="points">Points</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" step="0.01" min="0.01" class="form-control" id="edit_currency_value" name="edit_currency_value" placeholder="Currency Value" required>
                                <label for="currency_value">Currency Value (GHS)</label>
                            </div>
                        </div>
                        <small class="text-muted">Example: 500 points per 1 GHS</small>
                    </div>
                    
                        <div class="form-floating mb-3">
                            <select class="form-select" id="programStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label for="programStatus">Status</label>
                        </div>
                    </form>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary " id="editLoyaltyProgrambtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Loyalty Program Modal -->
    <div class="modal fade" id="viewLoyaltyProgramModal" tabindex="-1" aria-labelledby="viewLoyaltyProgramModalLabel" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewLoyaltyProgramModalLabel">View Loyalty Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="loyaltyProgramDetails">
                        <!-- Loyalty program details will be dynamically loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

   


    <!-- Tier Management Modal -->
    {{-- <div class="modal fade" id="tierManagementModal" tabindex="-1" aria-labelledby="tierManagementModalLabel" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tierManagementModalLabel">Manage Customer Tiers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="tierName" class="form-label">Tier Name</label>
                            <input type="text" class="form-control" id="tierName" placeholder="Enter tier name">
                        </div>
                        <div class="mb-3">
                            <label for="tierBenefits" class="form-label">Benefits</label>
                            <textarea class="form-control" id="tierBenefits" rows="3" placeholder="Enter benefits"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Personalized Offers Modal -->
    {{-- <div class="modal fade" id="personalizedOffersModal" tabindex="-1" aria-labelledby="personalizedOffersModalLabel" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="personalizedOffersModalLabel">Personalized Offers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Your special offer: <strong>{{ isset($personalizedOffer) ? $personalizedOffer : 'No offers available' }}</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Redeem Offer</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Referral Program Modal -->
    <div class="modal fade" id="referralProgramModal" tabindex="-1" aria-labelledby="referralProgramModalLabel" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="referralProgramModalLabel">Referral Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Refer a friend and earn <strong>{{ isset($referralPoints) ? $referralPoints : '500' }}</strong> points!</p>
                    <button class="btn btn-success">Copy Referral Link</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    {{-- <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="feedbackText" class="form-label">Your Feedback</label>
                            <textarea class="form-control" id="feedbackText" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Submit Feedback</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
   
   <script>
      
      document.getElementById('selectAllCustomers').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.customer-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
        // Trigger change event to ensure form detects the change
        $(checkbox).trigger('change');
    });
});

// Individual checkbox handling
document.querySelectorAll('.customer-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        if (!this.checked) {
            document.getElementById('selectAllCustomers').checked = false;
        } else {
            const allChecked = Array.from(document.querySelectorAll('.customer-checkbox'))
                                .every(cb => cb.checked);
            document.getElementById('selectAllCustomers').checked = allChecked;
        }
    });
});

// For the edit modal
document.getElementById('selectAllCustomers').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('#editLoyaltyProgramModal .customer-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
        $(checkbox).trigger('change');
    });
});

// Individual checkbox handling for edit modal
document.querySelectorAll('#editLoyaltyProgramModal .customer-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        if (!this.checked) {
            document.getElementById('selectAllCustomers').checked = false;
        } else {
            const allChecked = Array.from(document.querySelectorAll('#editLoyaltyProgramModal .customer-checkbox'))
                                .every(cb => cb.checked);
            document.getElementById('selectAllCustomers').checked = allChecked;
        }
    });
});
 

        // Function to show the generic modal with dynamic title
        function showGenericModal(title, formId) {
            $('#genericModalLabel').text(title);
            $('#genericForm').attr('id', formId);
            $('#genericModal').modal('show');
        }

        // Function to generate report
        const generateReport = () => {
            console.log('Generate Report function called');
            $.ajax({
                type: 'GET',
                url: '/generate-report',
                success: function(response) {
                    Swal.fire('Success!', 'Report generated', 'success');
                }
            });
        };


        // Function to view loyalty program
        function viewLoyaltyProgram(id) {
            // Fetch details for the loyalty program
            $.ajax({
                url: '/loyalty-programs/' + id,
                method: 'GET',
                success: function(data) {
                    // Display the data in the view modal
                    $('#viewLoyaltyProgramModal').find('.modal-body').html(data.details);
                    // Show the modal
                    $('#viewLoyaltyProgramModal').modal('show');
                },
                error: function(error) {
                    console.log('Error fetching loyalty program details:', error);
                }
            });
        }
         

        // loyalty.js - Complete AJAX handler with all helper functions

$(document).ready(function() {

    
$('#editLoyaltyProgramModal #selectAllCustomers').change(function() {
        $('#editLoyaltyProgramModal .customer-checkbox').prop('checked', this.checked);
    });

    // Individual checkbox handling
    $('#editLoyaltyProgramModal').on('change', '.customer-checkbox', function() {
        const allChecked = $('#editLoyaltyProgramModal .customer-checkbox').length === 
                          $('#editLoyaltyProgramModal .customer-checkbox:checked').length;
        $('#editLoyaltyProgramModal #selectAllCustomers').prop('checked', allChecked);
    });
   // Simple cookie helper functions
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

function setCookie(name, value, days) {
  const date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
  document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;
}


let currentProgramId =  null;
let savedProgramId = getCookie('currentLoyaltyProgram');



    // At the start of your script (after getting the cookie)
if (savedProgramId) {
    currentProgramId = savedProgramId;
} else if ($('#loyaltyProgramsTable tbody tr').length > 0) {
    // Find the first active program
    const firstActive = $('#loyaltyProgramsTable tbody tr').find('.status-toggle:checked').first();
    if (firstActive.length) {
        currentProgramId = firstActive.data('id');
        setCookie('currentLoyaltyProgram', currentProgramId, 7);
    }
}


$(document).on('change', '.current-program-selector', function() {
  currentProgramId = $(this).val();
  setCookie('currentLoyaltyProgram', currentProgramId, 7);
  
  // Optional: Highlight the current program row
  $('#loyaltyProgramsTable tr').removeClass('current-program');
  $(this).closest('tr').addClass('current-program');

        fetchTiers(currentProgramId);
        fetchReferrals(currentProgramId);
        fetchStats();
        fetchSegmentation();
        fetchLoyaltyPrograms();
        loadRedemptions();
});

    console.log('Current Program ID:', savedProgramId );

 
// Add this event handler after your document.ready function
$(document).on('change', '.program-selector', function() {
    const newProgramId = $(this).val();
    
    if (newProgramId && newProgramId !== currentProgramId) {
        // Update current program
        currentProgramId = newProgramId;
        
        // Save to cookie
        setCookie('currentLoyaltyProgram', currentProgramId, 7); // Save for 7 days
        
        // Update UI - uncheck all other radios
        $('.program-selector').not(this).prop('checked', false);
        
        // Refresh all program-dependent data
        fetchTiers(currentProgramId);
        fetchReferrals(currentProgramId);
        fetchStats();
        fetchSegmentation();
        
        
        showSuccess('Program Changed', 'You have switched to a different loyalty program');
    }
});

$(document).on('change', '.status-toggle', function() {
    const programId = $(this).data('id');
    const isActive = $(this).is(':checked');
    
    showLoading('Updating program status...');
    
    $.ajax({
        url: `/company/loyalty-programs/${programId}/status`,
        method: 'PUT',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        data: {
            is_active: isActive
        },
        success: function(response) {
            if (response.success) {
                showSuccess('Success', `Program ${isActive ? 'activated' : 'deactivated'} successfully`);
                fetchLoyaltyPrograms();
                
                // If deactivating the current program, find a new active one
                if (!isActive && programId == currentProgramId) {
                    const firstActive = $('#loyaltyProgramsTable tbody tr').find('.status-toggle:checked').first();
                    if (firstActive.length) {
                        firstActive.prop('checked', true).trigger('change');
                    } else {
                        currentProgramId = null;
                        setCookie('currentLoyaltyProgram', '', -1); // Remove cookie
                    }
                }
            }
        },
        error: handleAjaxError
    });
});
   
    const today = new Date().toISOString().split('T')[0];
    $('input[type="date"]').attr('min', today);
    
    // Ensure end date is never before start date
    $('#start_date, #startDate').on('change', function() {
        const endDateId = $(this).attr('id') === 'start_date' ? '#end_date' : '#endDate';
        $(endDateId).attr('min', $(this).val());
        
        if ($(endDateId).val() && $(endDateId).val() < $(this).val()) {
            $(endDateId).val($(this).val());
        }
    });

    // Get CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('#csrfForm input[name="_token"]').val();

    $(".createProgramForm").click(function() {
        $('#addProgramModal').modal('show');
    });
    
  
  

    // ====================
    // MAIN PROGRAM HANDLING
    // ====================
    
    // Fetch all programs
    window.fetchLoyaltyPrograms = function(page = 1, search = '', status = '') {
     
        // showLoading('Loading programs...');
        
        $.ajax({
            url: `/company/loyalty-programs/all?page=${page}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { search, status },
            success: renderProgramsTable,
            error: handleAjaxError
        });
    };
    

    // Create program
    // $('#createProgramForm').submit(function(e) {
    //     e.preventDefault();
    //     submitForm({
    //         form: this,
    //         url: '/company/loyalty-programs',
    //         method: 'POST',
    //         headers: { 'X-CSRF-TOKEN': csrfToken },
    //         success: (response) => {
    //             console.log('Program created successfully', response);
    //             $('#addProgramModal').modal('hide');
    //             fetchLoyaltyPrograms();
    //             fetchStats();
    //             fetchSegmentation();
    //         },
           
    //     });
    // });
     

//     $('#createProgramForm').submit(function(e) {
//     e.preventDefault();
    
//     // Debug: Check what checkboxes are selected
//      const selectedCategories = [];
//     $('.customer-checkbox:checked').each(function() {
//         const val = $(this).val();
//         if (!selectedCategories.includes(val)) {
//             selectedCategories.push(val);
//         }
//     });
//     console.log('Unique selected categories:', selectedCategories);
    
//     // Create new form data
//     const formData = new FormData(this);
    
//     // Remove all existing customer_category entries
//     formData.delete('customer_category[]');
    
//     // Add unique categories only
//     selectedCategories.forEach(category => {
//        formData.append('customer_category[]', category);
//     });

//       const formDataEntries = {};
//     for (let [key, value] of formData.entries()) {
//         if (!formDataEntries[key]) formDataEntries[key] = [];
//         formDataEntries[key].push(value);
//     }
//     console.log('Final FormData being submitted:', formDataEntries);
    
//     // return;
//     submitForm({
//         form: this,
//         url: '/company/loyalty-programs',
//         method: 'POST',
//         headers: { 'X-CSRF-TOKEN': csrfToken },
//         success: (response) => {
//             console.log('Program created successfully', response);
//             $('#addProgramModal').modal('hide');
//             // fetchLoyaltyPrograms();
//             // fetchStats();
//             // fetchSegmentation();
//         },
//         error: (xhr) => {
//             console.error('Error:', xhr.responseText);
//         }
//     });
//    });
   




$('#createProgramForm').submit(function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Debug log
    const payload = {};
    for (let [key, val] of formData.entries()) {
        if (!payload[key]) payload[key] = [];
        payload[key].push(val);
    }
    console.log('Submitting:', payload);

    $.ajax({
        url: '/company/loyalty-programs',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
             console.log('Program created successfully', response);
            $('#addProgramModal').modal('hide');
            fetchLoyaltyPrograms();
            fetchStats();
            fetchSegmentation();

            

            
        },
        error: function (xhr) {
            console.error('Error:', xhr.responseText);
            const errorMessage = xhr.responseJSON?.message || 'An error occurred while creating the program';
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                confirmButtonText: 'OK'
            });
            
        }
    });
});




    // View program
    $(document).on('click', '.view-program', function() {
    const id = $(this).data('id');
    showLoading('Loading program...');
    
    $.ajax({
        url: `/company/loyalty-programs/${id}`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: (response) => {
            if (response.success) {
                Swal.close();
                populateViewModal(response.data);
                $('#viewLoyaltyProgramModal').modal('show');
            }
        },
        error: handleAjaxError
    });
});

    // Edit program
    $(document).on('click', '.edit-program', function() {
        const id = $(this).data('id');
        showLoading('Loading program...');
        
        $.ajax({
            url: `/company/loyalty-programs/${id}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: (response) => {
               
                if (response.success) {
                    Swal.close();
                    populateEditForm(response.data);
                    $('#editLoyaltyProgramModal').modal('show');
                }
            },
            error: handleAjaxError
        });
    });

    
//     $('#editLoyaltyProgrambtn').click(function (e) {
//     e.preventDefault();

//     const id = $('#editProgramId').val();
//     const form = $('#editLoyaltyProgramForm');
//     const formData = form.serialize(); // If your backend expects standard form-encoded data

//     $.ajax({
//         url: `/company/loyalty-programs/${id}`,
//         method: 'PUT',
//         data: formData,
//         headers: {
//             'X-CSRF-TOKEN': csrfToken // Make sure csrfToken is defined
//         },
//         success: function (response) {
//             console.log(response);
//             $('#editLoyaltyProgramModal').modal('hide');
//             fetchLoyaltyPrograms();
//         },
//         error: function (xhr) {
//             console.error(xhr.responseJSON || xhr.responseText);
//         }
//     });
// });


// $('#editLoyaltyProgrambtn').click(function (e) {
//     e.preventDefault();

//     const id = $('#editProgramId').val();
//     const form = $('#editLoyaltyProgramForm');
    
//     // Create FormData object
//     const formData = new FormData();
    
//     // Add all regular form fields
//     form.find('input[type!="checkbox"], select, textarea').each(function() {
//         if (this.name) {
//             formData.append(this.name, $(this).val());
//         }
//     });
    
//     // Special handling for checkboxes
//     const customerCategories = [];
//     form.find('input[name="edit_customers_category[]"]:checked').each(function() {
//         customerCategories.push(this.value);
//     });
    
//     // Add customer categories (even if empty array)
//     customerCategories.forEach(category => {
//         formData.append('customer_category[]', category);
//     });
    
//     // If no categories selected, add empty value to pass validation
//     if (customerCategories.length === 0) {
//         formData.append('customer_category[]', '');
//     }

//     // Add method override for Laravel
//     formData.append('_method', 'PUT');

//     // Debug: Log form data to console
//     for (let [key, value] of formData.entries()) {
//         console.log(key + ': ' + value);
//     }

//     showLoading('Updating program...');
    
//     $.ajax({
//         url: `/company/loyalty-programs/${id}`,
//         method: 'PUT',
//         data: formData,
//         processData: false,
//         contentType: false,
//         headers: { 'X-CSRF-TOKEN': csrfToken },
//         success: function (response) {
//             console.log('Program updated successfully', response);
//             Swal.close();
//             if (response.success) {
//                 Swal.fire({
//                     icon: 'success',
//                     title: 'Success',
//                     text: 'Program updated successfully',
//                     timer: 2000,
//                     showConfirmButton: false
//                 });
//                 $('#editLoyaltyProgramModal').modal('hide');
//                 fetchLoyaltyPrograms();
//                 fetchStats();
//                 fetchSegmentation();
//             } else {
//                 showError('Failed to update program', response.errors);
//             }
//         },
//         error: function (xhr) {
//             console.error("errr",xhr);
//             Swal.close();
//             const response = xhr.responseJSON || {};
//             showError(response.message || 'An error occurred', response.errors);
//         }
//     });
// });


$('#editLoyaltyProgrambtn').click(function (e) {
    e.preventDefault();

    const id = $('#editProgramId').val();
    const form = $('#editLoyaltyProgramForm')[0]; // Get DOM element
    
    // Create FormData from the form
    const formData = new FormData(form);
    
    // Manually handle checkboxes
    formData.delete('edit_customers_category[]'); // Remove any existing
    $('input[name="edit_customers_category[]"]:checked').each(function() {
        formData.append('customer_category[]', this.value); // Use correct name
    });

    // Add method override
    formData.append('_method', 'PUT');

    // Debug: Show what's being sent
    for (let [key, value] of formData.entries()) {
        console.log(key + ':', value);
    }

    showLoading('Updating program...');
    
    $.ajax({
        url: `/company/loyalty-programs/${id}`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function (response) {
            Swal.close();
            if (response.success) {
                $('#editLoyaltyProgramModal').modal('hide');
                  fetchLoyaltyPrograms();
                fetchStats();
                fetchSegmentation();
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Program updated successfully',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                showError(response.error || 'Failed to update program', response.errors);
            }
        },
        error: function (xhr) {
            Swal.close();
            const response = xhr.responseJSON || {};
            showError(response.message || 'An error occurred', response.errors);
        }
    });
});
function showError(message, errors) {
    let errorHtml = `<div class="text-start">${message}`;
    
    if (errors) {
        errorHtml += '<ul class="mt-2 mb-0">';
        for (const [field, fieldErrors] of Object.entries(errors)) {
            errorHtml += `<li>${fieldErrors.join(', ')}</li>`;
        }
        errorHtml += '</ul>';
    }
    errorHtml += '</div>';
    
    Swal.fire({
        icon: 'error',
        title: 'Error',
        html: errorHtml,
        confirmButtonText: 'OK'
    });
}
   
   
   
   
   
   
    $(document).on('click', '.delete-program', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        confirmDelete({
            title: `Delete ${name}?`,
            text: 'This will permanently delete the program and all associated data',
            url: `/company/loyalty-programs/${id}`,
            method: 'DELETE',
            callback: fetchLoyaltyPrograms
        });
    });

      // Initialize
      fetchLoyaltyPrograms();
    fetchStats();
    fetchSegmentation();

    // =============
    // TIERS HANDLING
    // =============
 
    // Tier Management Functions
let currentTierPage = 1;
// let currentProgramId = null;

// Fetch tiers with pagination
function fetchTiers(programId, page = 1) {
    currentProgramId = programId;
    currentTierPage = page;

      if (!currentProgramId) {
            
        // showError('Error', 'Please select a loyalty program first');
        return;
    }
    
    showLoading('Loading tiers...');
    
    $.ajax({
        url: `/company/loyalty-programs/${programId}/tiers/all?page=${page}`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(response) {
            if (response.success) {
                renderTiersTable(response.data);
                updateTiersPagination(response.pagination);
                Swal.close();
            }
        },
        error: handleAjaxError
    });
}

// Render tiers table
function renderTiersTable(tiers) {
    const tbody = $('#tiersTable tbody');
    tbody.empty();
    
    if (tiers.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="4" class="text-center py-4">No tiers found</td>
            </tr>
        `);
        return;
    }
    
    let id = 1;
    tiers.forEach(tier => {
        tbody.append(`
            <tr>
                <td>${id}</td>
                <td>${tier.name}</td>
                <td>${tier.benefits || '-'}</td>
                <td>${tier.points_required}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-primary edit-tier" 
                                data-id="${tier.id}" 
                                data-program-id="${tier.program_id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-tier" 
                                data-id="${tier.id}" 
                                data-program-id="${tier.loyalty_program_id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `);
        id++;
    });
}

// Update pagination
function updateTiersPagination(pagination) {
    const paginationContainer = $('#tiersPagination');
    paginationContainer.empty();
    
    // Previous button
    const prevItem = `<li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="changeTierPage(event, ${pagination.current_page - 1})">Previous</a>
    </li>`;
    paginationContainer.append(prevItem);
    
    // Page numbers
    const startPage = Math.max(1, pagination.current_page - 2);
    const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        const pageItem = `<li class="page-item ${i === pagination.current_page ? 'active' : ''}">
            <a class="page-link" href="#" onclick="changeTierPage(event, ${i})">${i}</a>
        </li>`;
        paginationContainer.append(pageItem);
    }
    
    // Next button
    const nextItem = `<li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="changeTierPage(event, ${pagination.current_page + 1})">Next</a>
    </li>`;
    paginationContainer.append(nextItem);
    
    // Update info text
    $('#tiersInfo').text(
        `Showing ${pagination.from} to ${pagination.to} of ${pagination.total} entries`
    );
}
// Handle tier pagination
window.changeTierPage = function(event, page) {
    event.preventDefault();
    fetchTiers(currentProgramId, page);
};
// Add tier
$('.addTierbtn').click(function(e) {
    e.preventDefault();

    const programId = $('#programSelect').val();
    if (!programId) {
        showError('Error', 'Please select a program');
        return;
    }

    // Collect form data properly
    const formData = {
        name: $('#tierName').val().trim(),
        benefits: $('#tierBenefits').val().trim(),
        points_required: parseInt($('#tierCriteria').val()) || 0
    };

    // Validate required fields before sending
    if (!formData.name) {
        showError('Validation Error', 'Tier name is required');
        $('#tierName').focus();
        return;
    }

    if (isNaN(formData.points_required) || formData.points_required < 0) {
        showError('Validation Error', 'Points required must be a positive number');
        $('#tierCriteria').focus();
        return;
    }

    showLoading('Adding tier...');

    $.ajax({
        url: `/company/loyalty-programs/${programId}/tiers`,
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        data: JSON.stringify(formData),
        success: function(response) {
            if (response.success) {
                showSuccess('Success', 'Tier added successfully');
                $('#addTierModal').modal('hide');
                fetchTiers(programId, 1); // Refresh to first page
                // Reset form
                $('#tierName').val('');
                $('#tierBenefits').val('');
                $('#tierCriteria').val('');
            
    
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                // Handle validation errors
                const errors = xhr.responseJSON.errors;
                let errorMessages = [];
                for (const field in errors) {
                    errorMessages.push(...errors[field]);
                }
                showError('Validation Error', errorMessages.join('<br>'));
            } else {
                handleAjaxError(xhr);
            }
        }
    });
});

// Edit tier
$(document).on('click', '.edit-tier', function() {
    const tierId = $(this).data('id');
    const programId = $(this).data('program-id');
    
    showLoading('Loading tier...');
    
    $.ajax({
        url: `/company/loyalty-programs/${programId}/tiers/${tierId}`,
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(response) {
            if (response.success) {
                
                
               console.log(response.data.points_required);
              
                populateTierEditForm(response.data);
                $('#editTierModal').modal('show');
                 // Wait for modal to be fully visible
                 $('#editTierModal').on('shown.bs.modal', function () {
                    let selectval = response.data.loyalty_program_id;
                    $('#editProgramSelect').val(selectval).change();
                    $("#editTierCriteria").val(response.data.points_required);
                });

                swal.close();
               
            }
        },
        error: handleAjaxError
    });
});

// Update tier
$('#updateTierBtn').click(function(e) {
    e.preventDefault();
    
    const tierId = $('#editTierId').val();
    const programId = $('#editProgramSelect').val();
    
    const formData = {
        name: $('#editTierName').val(),
        benefits: $('#editTierBenefits').val(),
        points_required: $('#editTierCriteria').val()
    };

    showLoading('Updating tier...');

    $.ajax({
        url: `/company/loyalty-programs/${programId}/tiers/${tierId}`,
        method: 'PUT',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        data: formData,
        success: function(response) {
            if (response.success) {
                showSuccess('Success', 'Tier updated successfully');
                $('#editTierModal').modal('hide');
                fetchTiers(programId, currentTierPage);
            }
        },
        error: handleAjaxError
    });
});

// Populate edit form
function populateTierEditForm(tier) {

   
    $('#editTierName').val(tier.name);
    $('#editTierBenefits').val(tier.benefits);
    $('#editTierCriteria').val(tier.points_required);
}


// Delete tier
$(document).on('click', '.delete-tier', function() {
    const tierId = $(this).data('id');
    const programId = $(this).data('program-id');
    const tierName = $(this).closest('tr').find('td:first').text().trim();
    
    // Verify all required data exists
    if (!programId || !tierId) {
        console.error('Missing programId or tierId');
        return;
    }

    console.log('Deleting tier:', tierId, programId);

    confirmDelete({
        title: `Delete ${tierName}?`,
        text: 'This will permanently delete the tier',
        url: `/company/loyalty-programs/${programId}/tiers/${tierId}`,
        headers: { 
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        method: 'DELETE',
        callback: () => {
            // Refresh only if we have a valid programId
            if (programId) {
                console.log(programId, currentTierPage);
                fetchTiers(programId, currentTierPage);
            }
        }
    });
});




// Initialize tier management when a program is selected
$(document).on('change', '#programSelect, #editProgramSelect', function() {
    const programId = $(this).val();
    if (programId) {
        fetchTiers(programId);
    }
});

// Populate program dropdowns when modals are shown
$('#addTierModal, #editTierModal').on('show.bs.modal', function() {
    $.ajax({
        url: '/company/loyalty-programs/all',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(response) {
            if (response.success) {
                const selectId = $(this).attr('id') === 'addTierModal' ? '#programSelect' : '#editProgramSelect';
                const select = $(selectId);
                select.empty().append('<option value="">Select a Program</option>');
                
                response.data.data.forEach(program => {
                    select.append(`<option value="${program.id}">${program.name}</option>`);
                });
            }
        },
        error: handleAjaxError
    });
});
    




 

    // ====================
    // POINTS REDEMPTION
    // ====================
    
    // Global variables
let currentPage = 1;
const perPage = 10;

// Initialize redemptions table
function loadRedemptions(page = 1) {
    if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
    currentPage = page;
    showLoading('Loading redemptions...');
    
    $.ajax({
        url: `/company/loyalty-programs/${currentProgramId}/redemptions/all`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        data: {
            page: page,
            per_page: perPage
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                renderRedemptionsTable(response);
                renderPagination(response.meta);
            }
        },
        error: handleAjaxError
    });
}

// Render redemptions table
function renderRedemptionsTable(response) {
    const $tbody = $('#redemptionsTable tbody');
    $tbody.empty();
    
    if (response.data.length === 0) {
        $tbody.append('<tr><td colspan="6" class="text-center">No redemptions found</td></tr>');
        return;
    }
    
    let id = 1;
    response.data.forEach(redemption => {
        $tbody.append(`
            <tr>
                <td>${id}</td>
                <td>${redemption.customer?.name || 'N/A'}</td>
                <td>${redemption.reward?.name || 'N/A'}</td>
                <td>${redemption.points_used}</td>
                <td>${new Date(redemption.created_at).toLocaleDateString()}</td>
                <td><span class="badge bg-${getStatusBadgeClass(redemption.status)}">${redemption.status}</span></td>
                <td>
                    <button class="btn btn-sm btn-primary edit-program edit-redemption" data-id="${redemption.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-redemption" data-id="${redemption.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
        id++;
    });
    
    // Update info text
    const meta = response.meta;
    $('#redemptionsInfo').text(`Showing ${meta.from} to ${meta.to} of ${meta.total} entries`);
}

// Render pagination
function renderPagination(meta) {
    const $pagination = $('#redemptionsPagination');
    $pagination.empty();
    
    if (meta.total <= meta.per_page) return;
    
    // Previous button
    $pagination.append(`
        <li class="page-item ${meta.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${meta.current_page - 1}">Previous</a>
        </li>
    `);
    
    // Page numbers
    for (let i = 1; i <= meta.last_page; i++) {
        $pagination.append(`
            <li class="page-item ${meta.current_page === i ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `);
    }
    
    // Next button
    $pagination.append(`
        <li class="page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${meta.current_page + 1}">Next</a>
        </li>
    `);
}

loadRedemptions()




// Helper function for status badge class
function getStatusBadgeClass(status) {
    switch(status) {
        case 'completed': return 'success';
        case 'pending': return 'warning';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}

// Load customers and rewards for modal
function loadModalData() {

    // Load customers
    $.ajax({
        url: `/company/customers`,
        method: 'GET',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(response) {
            const $select = $('#customerSelect');
            const $selectreferrer = $('#customerSelectreferrer');
            $select.empty().append('<option value="">Select Customer</option>');
            response.data.forEach(customer => {
                $select.append(`<option value="${customer.id}">${customer.name}</option>`);
                $selectreferrer.append(`<option value="${customer.id}">${customer.name}</option>`);
            });
        }
    });
    
    // Load rewards
    $.ajax({
        url: `/company/loyalty-programs/${currentProgramId}/rewards`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(response) {
            const $select = $('#rewardSelect');
            $select.empty().append('<option value="">Select Reward</option>');
            response.data.forEach(reward => {
                $select.append(`<option value="${reward.id}" data-points="${reward.points_required}">${reward.name} (${reward.points_required} points)</option>`);
            });
        }
    });
}

// Handle reward selection change
$('#rewardSelect').change(function() {
    const selectedOption = $(this).find('option:selected');
    const pointsRequired = selectedOption.data('points');
    
    if (pointsRequired) {
        $('#rewardDetails').html(`This reward requires <strong>${pointsRequired}</strong> points.`);
    } else {
        $('#rewardDetails').html('');
    }
});

// Save redemption (create or update)
$('#saveRedemption').click(function() {
    const redemptionId = $('#redemptionId').val();
    const method = redemptionId ? 'PUT' : 'POST';
    const url = redemptionId 
        ? `/company/loyalty-programs/${currentProgramId}/redemptions/${redemptionId}`
        : `/company/loyalty-programs/${currentProgramId}/redemptions`;
    
    const data = {
        customer_id: $('#customerSelect').val(),
        reward_id: $('#rewardSelect').val(),
        notes: $('#notes').val()
    };
    
    showLoading('Saving redemption...');
    
    $.ajax({
        url: url,
        method: method,
        headers: { 'X-CSRF-TOKEN': csrfToken },
        data: data,
        success: function(response) {
            if (response.success) {
                $('#redemptionModal').modal('hide');
                showSuccess('Success', redemptionId ? 'Redemption updated successfully!' : 'Redemption created successfully!');
                loadRedemptions(currentPage);
            }
        },
        error: handleAjaxError
    });
});

// Edit redemption
$(document).on('click', '.edit-redemption', function() {
    const redemptionId = $(this).data('id');

    
    showLoading('Loading redemption details...');
    
    $.ajax({
        url: `/company/loyalty-programs/${currentProgramId}/redemptions/${redemptionId}`,
        method: 'GET',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(response) {
            if (response.success) {
                $('#modalTitle').text('Edit Redemption');
                $('#redemptionId').val(response.data.id);
                $('#customerSelect').val(response.data.customer_id);
                $('#rewardSelect').val(response.data.reward_id);
                $('#notes').val(response.data.notes);
                
                // Trigger change to show points
                $('#rewardSelect').trigger('change');
                
                $('#redemptionModal').modal('show');
            }
        },
        error: handleAjaxError
    });
});




$(document).on('click', '.delete-redemption', function() {
    const redemptionId = $(this).data('id');
    
    Swal.fire({
        title: 'Delete Redemption',
        text: 'Are you sure you want to delete this redemption?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading('Deleting redemption...');
            
            $.ajax({
                url: `/company/loyalty-programs/${currentProgramId}/redemptions/${redemptionId}`,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(response) {
                    console.log(response, `/company/loyalty-programs/${currentProgramId}/redemptions/${redemptionId}`);

                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            'Redemption deleted successfully.',
                            'success'
                        ).then(() => {
                            loadRedemptions(currentPage);
                        });
                    }
                },
                error: handleAjaxError
            });
        }
    });
});

// Pagination click handler
$(document).on('click', '#redemptionsPagination .page-link', function(e) {
    e.preventDefault();
    const page = $(this).data('page');
    if (page) {
        loadRedemptions(page);
    }
});

// Initialize modal when shown
$('#redemptionModal').on('show.bs.modal', function() {
    loadModalData();
    $('#modalTitle').text('New Redemption');
    $('#redemptionId').val('');
    $('#redemptionForm')[0].reset();
    $('#rewardDetails').html('');
});

// Initialize redemptions table when page loads
$(document).ready(function() {
    if (currentProgramId) {
        loadRedemptions();
    }
});




















    // Load rewards when modal opens
    $('#pointsRedemptionModal').on('show.bs.modal', function() {
        if (!currentProgramId) {
            showError('Error', 'No program selected');
            return false;
        }

        $.ajax({
            url: `/company/loyalty-programs/${currentProgramId}/rewards`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function(response) {
                if (response.success) {
                    const $select = $('#rewardSelect');
                    $select.empty().append('<option value="">Select a Reward</option>');
                    response.data.forEach(reward => {
                        $select.append(`<option value="${reward.id}" data-points="${reward.points_required}">${reward.name} (${reward.points_required} points)</option>`);
                    });
                }
            },
            error: handleAjaxError
        });
    });

    // ====================
    // PERSONALIZED OFFERS
    // ====================
    
    $('#personalizedOffersModal').on('show.bs.modal', function() {
        if (!currentProgramId) {
            showError('Error', 'No program selected');
            return false;
        }

        showLoading('Loading offers...');
        
        $.ajax({
            url: `/company/loyalty-programs/${currentProgramId}/rewards`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function(response) {
                if (response.success) {
                    const $offersList = $('#offersList');
                    $offersList.empty();
                    
                    if (response.data.length === 0) {
                        $offersList.append('<p class="text-muted">No offers available at this time</p>');
                    } else {
                        response.data.forEach(offer => {
                            $offersList.append(`
                                <div class="offer-item mb-3 p-3 border rounded">
                                    <h5>${offer.name}</h5>
                                    <p>${offer.description || 'No description available'}</p>
                                    <p><strong>${offer.points_required} points required</strong></p>
                                    <button class="btn btn-sm btn-primary claim-offer" 
                                            data-offer-id="${offer.id}">
                                        Claim Offer
                                    </button>
                                </div>
                            `);
                        });
                    }
                    Swal.close();
                }
            },
            error: handleAjaxError
        });
    });

    // Claim offer
    $(document).on('click', '.claim-offer', function() {
        const offerId = $(this).data('offer-id');
        
        showLoading('Processing your offer...');
        
        $.ajax({
            url: `/company/loyalty-programs/${currentProgramId}/rewards/${offerId}/claim`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function(response) {
                if (response.success) {
                    showSuccess('Success', 'Offer claimed successfully!');
                    $('#personalizedOffersModal').modal('hide');
                    // Refresh points if balance changed
                    if (response.new_balance !== undefined) {
                        $('.available-points').text(response.new_balance);
                    }
                }
            },
            error: handleAjaxError
        });
    });

    // ====================
    // REFERRAL PROGRAM
    // ====================

    // Global variables
let currentReferralPage = 1;
let currentEditingReferralId = null;

fetchReferrals(currentProgramId, currentReferralPage);
// Fetch referrals function
function fetchReferrals(programId, page = 1, searchParams = {}) {
    if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
    $('#referrals-loading').show();
    
    let params = {
        page: page,
        ...searchParams
    };

    $.ajax({
        url: `/company/loyalty-programs/${programId}/referrals/all`,
        type: 'POST',
        data: params,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Referral response:', response);
            if (response.success) {
                updateReferralsTable(response.data);
                updatePagination(response.meta, response.links, programId);
                updateTableInfo(response.meta);
            } else {
                showError(response.error || 'Failed to fetch referrals');
            }
        },
        error: function(xhr) {
            // showError(xhr.responseJSON?.error || 'Server error');
        },
        complete: function() {
            $('#referrals-loading').hide();
        }
    });
}

// Update the referrals table
function updateReferralsTable(referrals) {
    const tbody = $('#referralsTable tbody');
    tbody.empty();
    
    let id = 1;
    referrals.forEach(referral => {
        const row = `
            <tr>
                <td>${id}</td>
                <td>${referral.referrer?.name || 'N/A'}</td>
                <td>${referral.email}</td>
                <td>${referral.referee?.name || 'Pending'}</td>
                <td>${referral.points_awarded}</td>
                <td>${new Date(referral.created_at).toLocaleDateString()}</td>
                <td>
                    <span class="badge ${getStatusBadgeClass(referral.status)}">
                        ${referral.status.charAt(0).toUpperCase() + referral.status.slice(1)}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary view-referral" data-id="${referral.id}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-referral" data-id="${referral.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        id++;
        tbody.append(row);
    });
}

// Update pagination
function updatePagination(meta, links, programId) {
    const pagination = $('#referralsPagination');
    pagination.empty();

    // Previous button
    pagination.append(`
        <li class="page-item ${!links.prev ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="fetchReferrals(${programId}, ${meta.current_page - 1})">Previous</a>
        </li>
    `);

    // Page numbers
    for (let i = 1; i <= meta.last_page; i++) {
        pagination.append(`
            <li class="page-item ${i === meta.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="fetchReferrals(${programId}, ${i})">${i}</a>
            </li>
        `);
    }

    // Next button
    pagination.append(`
        <li class="page-item ${!links.next ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="fetchReferrals(${programId}, ${meta.current_page + 1})">Next</a>
        </li>
    `);
}

// Update table info (showing X to Y of Z entries)
function updateTableInfo(meta) {
    $('#referralsInfo').text(
        `Showing ${meta.from} to ${meta.to} of ${meta.total} entries`
    );
}




// Initialize modal
$('#referralProgramModal').on('show.bs.modal', function(e) {
    const isEdit = $(e.relatedTarget).hasClass('edit-referral');
    
    if (isEdit) {
        currentEditingReferralId = $(e.relatedTarget).data('id');
        loadReferralData(currentEditingReferralId);
        $('#referralModalTitle').text('Edit Referral');
    } else {
        currentEditingReferralId = null;
        $('#referralForm')[0].reset();
        $('#referralModalTitle').text('New Referral');
    }
    
    // Always load customers
    fetchAndPopulateCustomers(currentProgramId);
});


// Load single referral data
function loadReferralData(referralId) {
    if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
    showLoading('Loading referral...');
    
    $.ajax({
        url: `/company/loyalty-programs/${currentProgramId}/referrals/${referralId}`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(response) {
            if (response.success) {
                window.currentReferrerId = response.data.referrer_id;
                $('#friendEmail').val(response.data.email);
                $('#pointsAwarded').val(response.data.points_awarded);
                $('#customMessage').val(response.data.custom_message || '');
                Swal.close();
            }
        },
        error: handleAjaxError
    });
}

// Save/Update referral
$('#saveReferralBtn').click(function() {
    const formData = {
        customer_id: $('#customerReferralSelect').val(),
        email: $('#friendEmail').val(),
        points_awarded: $('#pointsAwarded').val(),
       
    };

    // Validation
    if (!formData.customer_id || !formData.email) {
        showError('Error', 'Please fill all required fields');
        return;
    }

    const url = currentEditingReferralId 
        ? `/company/loyalty-programs/${currentProgramId}/referrals/${currentEditingReferralId}`
        : `/company/loyalty-programs/${currentProgramId}/referrals`;

    const method = currentEditingReferralId ? 'PUT' : 'POST';

    showLoading(currentEditingReferralId ? 'Updating...' : 'Creating...');
    
    $.ajax({
        url: url,
        method: method,
        headers: { 'X-CSRF-TOKEN': csrfToken },
        data: formData,
        success: function(response) {
            console.log('Referral response:', response);
            if (response.success) {
                showSuccess('Success', response.message);
                $('#referralProgramModal').modal('hide');
               
                fetchReferrals(currentProgramId);
                
            }
        },
        error: handleAjaxError
    });
});





// Add this to your existing JavaScript code

// View referral details
$('#referralsTable').on('click', '.view-referral', function() {
    const referralId = $(this).data('id');
    fetchReferralDetails(referralId);
});

// Fetch referral details from server
function fetchReferralDetails(referralId) {
    if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
    $.ajax({
        url: `/company/loyalty-programs/${currentProgramId}/referrals/${referralId}`,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                populateReferralModal(response.data);
                $('#viewReferralModal').modal('show');
            } else {
                showError(response.error || 'Failed to fetch referral details');
            }
        },
        error: function(xhr) {
            showError(xhr.responseJSON?.error || 'Server error');
        }
    });
}

// Populate modal with referral data
function populateReferralModal(referral) {
    // Basic info
    $('#referrerName').text(referral.referrer?.name || 'N/A');
    $('#referrerEmail').text(referral.referrer?.email || 'N/A');
    $('#referrerPhone').text(referral.referrer?.phone || 'N/A');
    $('#invitedEmail').text(referral.email);
    $('#pointsAwarded').text(referral.points_awarded);
    $('#dateInvited').text(new Date(referral.created_at).toLocaleString());
    
    // Status
    const statusBadge = $('#referralStatus');
    statusBadge.text(referral.status.charAt(0).toUpperCase() + referral.status.slice(1));
    statusBadge.removeClass().addClass('badge ' + getStatusBadgeClass(referral.status));
    
    // Invitation link
    const inviteLink = `${window.location.origin}/referral/process/${referral.token}`;
    // $('#invitationLink').attr('href', inviteLink).text(inviteLink);

    // In populateReferralModal function:
$('#invitationLinkInput').val(inviteLink);

// Update copy function
$('#copyInviteLink').off('click').on('click', function() {
    const input = document.getElementById('invitationLinkInput');
    input.select();
    document.execCommand('copy');
    
    // Show feedback
    $(this).html('<i class="fas fa-check"></i> Copied!');
    setTimeout(() => {
        $(this).html('<i class="fas fa-copy"></i>');
    }, 2000);
});
    
    // Referee info (if exists)
    if (referral.referee) {
        $('#refereeInfoSection').show();
        $('#refereeName').text(referral.referee.name);
        $('#refereeSignupDate').text(new Date(referral.completed_at).toLocaleString());
    } else {
        $('#refereeInfoSection').hide();
    }
    


    $('#copyInviteLink').off('click').on('click', function() {
    navigator.clipboard.writeText(inviteLink).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'Invitation link copied to clipboard!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    }, function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to copy link',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    });
});
}






// Delete referral
$(document).on('click', '.delete-referral', function() {
    const referralId = $(this).data('id');
    const referralEmail = $(this).closest('tr').find('td:nth-child(2)').text();
    
    confirmDelete({
        title: `Delete Referral?`,
        text: `This will permanently delete the referral to ${referralEmail}`,
        url: `/company/loyalty-programs/${currentProgramId}/referrals/${referralId}`,
        method: 'DELETE',
        callback: function() {
           
           fetchReferrals(currentProgramId, currentReferralPage); // Refresh after delete
        }
    });
});

    


    // ====================
    // FEEDBACK
    // ====================
    
    $('#feedbackForm').submit(function(e) {
        e.preventDefault();
        const feedback = $('#feedback').val().trim();
        
        if (!feedback) {
            showError('Error', 'Please enter your feedback');
            return;
        }

        showLoading('Submitting feedback...');
        
        $.ajax({
            url: '/feedback',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: {
                message: feedback,
                program_id: currentProgramId
            },
            success: function(response) {
                if (response.success) {
                    $('#feedbackModal').modal('hide');
                    showSuccess('Thank You!', 'Your feedback has been submitted.');
                    $('#feedback').val('');
                }
            },
            error: handleAjaxError
        });
    });







    
    // ====================
    // GENERATE REPORT
    // ====================
    
    function generateReport() {
        if (!currentProgramId) {
            showError('Error', 'Please select a program first');
            return;
        }

        showLoading('Generating report...');
        
        $.ajax({
            url: '/company/loyalty-programs/report',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: {
                program_id: currentProgramId,
                type: $('#reportType').val() || 'summary'
            },
            success: function(response) {
                if (response.success) {
                    displayReport(response.data);
                    $('#reportModal').modal('show');
                }
            },
            error: handleAjaxError
        });
    }

    function displayReport(data) {
        const $reportBody = $('#reportBody');
        $reportBody.empty();
        
        // Simple summary report display
        $reportBody.append(`
            <div class="report-summary">
                <h5>Program Report Summary</h5>
                <p>Total Members: ${data.total_members || 0}</p>
                <p>Active Members: ${data.active_members || 0}</p>
                <p>Points Issued: ${data.points_issued || 0}</p>
                <p>Points Redeemed: ${data.points_redeemed || 0}</p>
                <p>Redemption Rate: ${data.redemption_rate || '0%'}</p>
            </div>
        `);
    }
 // Initialize with first program if available
 if ($('#loyaltyProgramsTable tbody tr').length > 0) {
        currentProgramId = $('#loyaltyProgramsTable tbody tr:first').data('id');
    }




















    // ===================
    // COMPLETE HELPER FUNCTIONS
    // ===================
    
    function renderProgramsTable(data) {
        
        Swal.close();
        const tbody = $('#loyaltyProgramsTable tbody');
        tbody.empty();

        const programSelect = $('#programSelect');
        const editProgramSelect = $('#editProgramSelect');
        programSelect.empty().append('<option value="">Select a Program</option>');
        // editProgramSelect.empty().append('<option value="">Select a Program</option>');

        data.data.data.forEach(program => {
            const option = `<option value="${program.id}">${program.name}</option>`;
            programSelect.append(option);
            editProgramSelect.append(option);
        });

        if (data.data.length === 0) {
            tbody.append('<tr><td colspan="10" class="text-center py-4">No programs found</td></tr>');
            return;
        }


                    console.log('Programs data:', data);

                    const paginationData = data.data;  

                    console.log('Pagination data:', paginationData.data);

                let paginationHtml = `<li class="page-item ${paginationData.prev_page_url ? '' : 'disabled'}">
                    <a class="page-link" href="#" onclick="return changePage(event, ${paginationData.current_page - 1})">Previous</a>
                </li>`;

                for (let i = 1; i <= paginationData.last_page; i++) {
                    paginationHtml += `<li class="page-item ${i === paginationData.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="return changePage(event, ${i})">${i}</a>
                    </li>`;
                }

                paginationHtml += `<li class="page-item ${paginationData.next_page_url ? '' : 'disabled'}">
                    <a class="page-link" href="#" onclick="return changePage(event, ${paginationData.current_page + 1})">Next</a>
                </li>`;

                $('#loyaltyprogrampagination').html(paginationHtml);

              

                let id = 1;
                data.data.data.forEach(program => {
        // Check if this is the currently selected program
       
        const isCurrent = program.id == currentProgramId;
        if (!currentProgramId && program.length > 0) {
                currentProgramId = program[0].id;
                setCookie('currentLoyaltyProgram', currentProgramId, 7);
            }
                    
        tbody.append(`
            <tr ${isCurrent ? 'class="current-program"' : ''}>
                <td>${id}</td>
                <td>${program.name}</td>
                <td>${program.program_type}</td>
                <td>${formatDate(program.start_date)}</td>
                <td>${program.end_date ? formatDate(program.end_date) : 'N/A'}</td>
                <td>${program.members_count || 0}</td>
                <td>${program.points_issued || 0}</td>
                <td>${program.redemption_rate || '0%'}</td>
                <td>
                  <span class="badge ${program.status === 'active' ? 'bg-success' : 'bg-danger'}">${program.status.charAt(0).toUpperCase() + program.status.slice(1)}</span>
                </td>
                 <td>
                  

                    <label class="switch">
                    <input type="checkbox"
                            name="currentProgram"
                            value="${program.id}"
                            ${isCurrent ? 'checked' : ''}
                            class="current-program-selector">
                    <span class="slider round"></span>
                    </label>
                    </td>
                
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-primary edit-program" data-id="${program.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-info view-program" data-id="${program.id}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-program" data-id="${program.id}" data-name="${program.name}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `);

        id++;
    });
             


    }


    window.changePage = function(event, page)  {
    event.preventDefault();

    if (page < 1) return false;  // prevent invalid pages

    // Make AJAX call to get page data (adjust URL as needed)
    $.ajax({
        url: `/company/loyalty-programs/all?page=${page}`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(response) {
            renderProgramsTable(response);
            renderPagination(response);
        },
        error: function() {
            alert('Failed to load page data');
        }
    });

    return false;
   }
  



 

   
    function populateViewModal(program) {
        if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }

        console.log('Populating view modal with program:', program);
    // Create the modal content HTML
    const modalContent = `
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Program Name</h6>
                <p class="text-muted">${program.name}</p>
            </div>
            <div class="col-md-6">
                <h6>Program Type</h6>
                <p class="text-muted">${program.program_type}</p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <h6>Description</h6>
                <p class="text-muted">${program.description || 'No description available'}</p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Start Date</h6>
                <p class="text-muted">${formatDate(program.start_date)}</p>
            </div>
            <div class="col-md-6">
                <h6>End Date</h6>
                <p class="text-muted">${program.end_date ? formatDate(program.end_date) : 'Not specified'}</p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Currency Ratio</h6>
                <p class="text-muted">${program.currency_ratio}</p>
            </div>
            <div class="col-md-6">
                <h6>Status</h6>
                <p class="text-muted">
                    <span class="badge ${program.is_active ? 'bg-success' : 'bg-danger'}">
                        ${program.status}
                    </span>
                </p>
            </div>
        </div>
        
        <hr>
        
        <h5 class="mb-3">Program Tiers</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="programTiersTable">
                <thead class="table-light">
                    <tr>
                        <th>Tier Name</th>
                        <th>Points Required</th>
                        <th>Benefits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${program.tiers && program.tiers.length > 0 ? 
                        program.tiers.map(tier => `
                            <tr>
                                <td>${tier.name}</td>
                                <td>${tier.points_required}</td>
                                <td>${tier.benefits || '-'}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-tier" 
                                            data-id="${tier.id}" 
                                            data-program-id="${program.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-tier" 
                                            data-id="${tier.id}" 
                                            data-program-id="${program.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('') : `
                        <tr>
                            <td colspan="4" class="text-center text-muted">No tiers available</td>
                        </tr>
                    `}
                </tbody>
            </table>
        </div>
        
        <h5 class="mb-3 mt-4">Program Rewards</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="programRewardsTable">
                <thead class="table-light">
                    <tr>
                        <th>Reward Name</th>
                        <th>Points Required</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${program.rewards && program.rewards.length > 0 ? 
                        program.rewards.map(reward => `
                            <tr>
                                <td>${reward.name}</td>
                                <td>${reward.points_required}</td>
                                <td>${reward.quantity || 'Unlimited'}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-reward" 
                                            data-id="${reward.id}" 
                                            data-program-id="${program.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-reward" 
                                            data-id="${reward.id}" 
                                            data-program-id="${program.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('') : `
                        <tr>
                            <td colspan="4" class="text-center text-muted">No rewards available</td>
                        </tr>
                    `}
                </tbody>
            </table>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6>Program Statistics</h6>
                        <p>Total Members: ${program.stats?.total_members || 0}</p>
                        <p>Active Members: ${program.stats?.active_members || 0}</p>
                        <p>Points Issued: ${program.stats?.points_issued || 0}</p>
                        <p>Points Redeemed: ${program.stats?.points_redeemed || 0}</p>
                        <p>Redemption Rate: ${program.stats?.redemption_rate || '0%'}</p>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Set the modal content
    $('#loyaltyProgramDetails').html(modalContent);
    
    // Initialize tooltips for the new elements
    $('[data-bs-toggle="tooltip"]').tooltip();
}
   
   
   
   // function populateEditForm(program) {
    //    $('#editProgramId').val(program.id);
   //     $('#programName').val(program.name);
    //    $('#programType').val(program.program_type);
   //     $('#programDescription').val(program.description || '');
   //     $('#startDate').val(program.start_date.split('T')[0]);
    //    $('#endDate').val(program.end_date ? program.end_date.split('T')[0] : '');
    //    $('#pointsRatio').val(program.currency_ratio || '');
    //    $('#programStatus').val(program.status || 'inactive');
   // }

   function populateEditForm(program) {
    $('#editProgramId').val(program.id);
    $('#programName').val(program.name);
    $('#programType').val(program.program_type);
    $('#programDescription').val(program.description || '');
    $('#startDate').val(program.start_date.split('T')[0]);
    $('#endDate').val(program.end_date ? program.end_date.split('T')[0] : '');
    $('#edit_points').val(program.points || '');
    $('#edit_currency_value').val(program.currency_value || '');
    $('#programStatus').val(program.status || 'inactive');
    
    // Handle customer categories checkboxes
    const customerCategories = program.customer_type || [];
    
    // Uncheck all first
    $('.customer-checkbox').prop('checked', false);
    
    // Check the appropriate boxes
    customerCategories.forEach(category => {
        $(`input[name="edit_customers_category[]"][value="${category}"]`).prop('checked', true);
    });
    
    // Update "Select All" checkbox state
    updateSelectAllCheckbox();
}

function updateSelectAllCheckbox() {
    const allChecked = $('.customer-checkbox:checked').length === $('.customer-checkbox').length;
    $('#selectAllCustomers').prop('checked', allChecked);
}

    function populateTierEditForm(tier) {
        $('#editTierId').val(tier.id);
        $('#editTierName').val(tier.name);
        $('#editTierBenefits').val(tier.benefits);
        $('#editTierPointsRequired').val(tier.points_required);
    }

    function fetchProgramTiers(programId) {
        if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
        showLoading('Loading tiers...');
        
        $.ajax({
            url: `/company/loyalty-programs/${programId}/tiers`,
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: (response) => {
                if (response.success) {
                    // Update tiers display in view modal
                    const tbody = $('#programTiersTable tbody');
                    tbody.empty();
                    
                    response.data.forEach(tier => {
                        tbody.append(`
                            <tr>
                                <td>${tier.name}</td>
                                <td>${tier.points_required}</td>
                                <td>${tier.benefits || '-'}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-tier" 
                                            data-id="${tier.id}" 
                                            data-program-id="${programId}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-tier" 
                                            data-id="${tier.id}" 
                                            data-program-id="${programId}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                }
            },
            error: handleAjaxError
        });
    }

    function fetchProgramRewards(programId) {
        if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
        showLoading('Loading rewards...');
        
        $.ajax({
            url: `/company/loyalty-programs/${programId}/rewards`,
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: (response) => {
                if (response.success) {
                    // Update rewards display
                    const tbody = $('#programRewardsTable tbody');
                    tbody.empty();
                    
                    response.data.forEach(reward => {
                        tbody.append(`
                            <tr>
                                <td>${reward.name}</td>
                                <td>${reward.points_required}</td>
                                <td>${reward.quantity || 'Unlimited'}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-reward" 
                                            data-id="${reward.id}" 
                                            data-program-id="${programId}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-reward" 
                                            data-id="${reward.id}" 
                                            data-program-id="${programId}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                }
            },
            error: handleAjaxError
        });
    }

    function fetchProgramRedemptions(programId) {
        if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
        showLoading('Loading redemptions...');
        
        $.ajax({
            url: `/company/loyalty-programs/${programId}/redemptions`,
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: (response) => {
                if (response.success) {
                    // Update redemptions display
                    const tbody = $('#programRedemptionsTable tbody');
                    tbody.empty();
                    
                    response.data.forEach(redemption => {
                        tbody.append(`
                            <tr>
                                <td>${redemption.id}</td>
                                <td>${redemption.customer_name}</td>
                                <td>${redemption.reward_name}</td>
                                <td>${redemption.points_used}</td>
                                <td>${formatDate(redemption.created_at)}</td>
                                <td>
                                    <span class="badge bg-${getStatusBadgeClass(redemption.status)}">
                                        ${redemption.status}
                                    </span>
                                </td>
                            </tr>
                        `);
                    });
                }
            },
            error: handleAjaxError
        });
    }

    function fetchProgramReferrals(programId) {
        if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
        showLoading('Loading referrals...');
        
        $.ajax({
            url: `/company/loyalty-programs/${programId}/referrals`,
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: (response) => {
                if (response.success) {
                    // Update referrals display
                    const tbody = $('#programReferralsTable tbody');
                    tbody.empty();
                    
                    let id = 1;
                    response.data.forEach(referral => {
                        tbody.append(`
                            <tr>
                                <td>${id}</td>
                                <td>${referral.referrer_name}</td>
                                <td>${referral.email}</td>
                                <td>${referral.points_awarded || 'Pending'}</td>
                                <td>
                                    <span class="badge bg-${getStatusBadgeClass(referral.status)}">
                                        ${referral.status}
                                    </span>
                                </td>
                                <td>${referral.completed_at ? formatDate(referral.completed_at) : 'N/A'}</td>
                            </tr>
                        `);

                        id++;
                    });


                }
            },
            error: handleAjaxError
        });
    }


    function fetchStats() {
        if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
    showLoading('Loading stats...');
    
    $.ajax({
        url: '/company/loyalty-programs/stats',
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: (response) => {
            console.log('Stats response:', response);
            if (response.success) {
                // Format numbers nicely
                $('#activeMembersCount').text(
                    response.data.active_members?.toLocaleString() ?? '0'
                );
                
                $('#pointsRedeemedCount').text(
                    response.data.points_redeemed >= 1000000 
                        ? (response.data.points_redeemed/1000000).toFixed(1) + 'M'
                        : response.data.points_redeemed?.toLocaleString() ?? '0'
                );
                
                $('#activeProgramsCount').text(
                    response.data.active_programs ?? '0'
                );
                
                $('#clvIncreasePercent').text(
                    (response.data.clv_increase ?? 0) + '%'
                );
                
                if (response.debug) {
                    console.debug('Debug info:', response.debug);
                }
            } else {
                showError(response.error || 'Failed to load stats');
                console.error('Error details:', response.details);
            }
        },
        error: (xhr) => {
            let error = xhr.responseJSON?.error || xhr.statusText;
            showError('Stats error: ' + error);
            console.error('Full error:', xhr.responseJSON);
            
            // Set default values on error
            $('#activeMembersCount').text('0');
            $('#pointsRedeemedCount').text('0');
            $('#activeProgramsCount').text('0');
            $('#clvIncreasePercent').text('0%');
        }
    });
}


function fetchSegmentation() {
    if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
    showLoading('Loading segmentation...');
    
    $.ajax({
        url: '/company/loyalty-programs/segmentation',
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: (response) => {
            console.log('Segmentation response:', response);
            
            if (response.success) {
                // Format numbers with thousands separators
                $('#platinumMembersCount').text(
                    response.data.platinum?.toLocaleString() ?? '0'
                );
                $('#activeRedeemersCount').text(
                    response.data.active_redeemers?.toLocaleString() ?? '0'
                );
                $('#atRiskMembersCount').text(
                    response.data.at_risk?.toLocaleString() ?? '0'
                );
            } else {
                showError(response.error || 'Failed to load segmentation data');
                console.error('Error details:', response.details);
            }
        },
        error: (xhr) => {
            let error = xhr.responseJSON?.error || xhr.statusText;
            showError('Segmentation error: ' + error);
            console.error('Full error:', xhr.responseJSON);
            
            // Reset to zero on error
            $('#platinumMembersCount').text('0');
            $('#activeRedeemersCount').text('0');
            $('#atRiskMembersCount').text('0');
        }
    });
}



    function displayReport(data, type) {
        const reportBody = $('#reportBody');
        reportBody.empty();
        
        if (type === 'summary') {
            reportBody.append(`
                <div class="report-summary">
                    <h5>Program Summary</h5>
                    <p>Total Members: ${data.total_members}</p>
                    <p>Active Members: ${data.active_members}</p>
                    <p>Points Issued: ${data.points_issued}</p>
                    <p>Points Redeemed: ${data.points_redeemed}</p>
                    <p>Redemption Rate: ${data.redemption_rate}</p>
                </div>
            `);
        } else if (type === 'detailed') {
            // Detailed report implementation
            let table = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Members</th>
                            <th>Points Issued</th>
                            <th>Points Redeemed</th>
                            <th>Redemption Rate</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            data.forEach(program => {
                table += `
                    <tr>
                        <td>${program.name}</td>
                        <td>${program.members_count}</td>
                        <td>${program.points_issued}</td>
                        <td>${program.points_redeemed}</td>
                        <td>${program.redemption_rate}</td>
                    </tr>
                `;
            });
            
            table += `</tbody></table>`;
            reportBody.append(table);
        }
    }

    function submitForm({ form, url, method, success }) {
        const formData = $(form).serializeArray().reduce((obj, item) => {
            obj[item.name] = item.value;
            return obj;
        }, {});

        showLoading('Processing...');
        
        $.ajax({
            url: url,
            method: method,
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: formData,
            success: (response) => {
                if (response.success) {
                    showSuccess('Success', response.message || 'Operation completed successfully');
                    if (typeof success === 'function') success(response);
                } else {
                    showValidationErrors(response.errors);
                }
            },
            error: function(xhr) {
                handleAjaxError(xhr);
                if (xhr.status === 422) {
                    showValidationErrors(xhr.responseJSON.errors);
                }
            }
        });
    }

    function confirmDelete({ title, text, url, method, callback }) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Deleting...');
                
                $.ajax({
                    url: url,
                    method: method,
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    success: (response) => {
                        if (response.success) {
                            showSuccess('Deleted', response.message || 'Item deleted successfully');
                            if (typeof callback === 'function') callback();
                        }
                    },
                    error: handleAjaxError
                });
            }
        });
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    function getStatusBadgeClass(status) {
        switch (status.toLowerCase()) {
            case 'active':
            case 'completed':
                return 'success';
            case 'pending':
                return 'warning';
            case 'inactive':
            case 'cancelled':
            case 'expired':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    function showLoading(message) {
        Swal.fire({
            title: 'Please wait',
            html: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    function showSuccess(title, message) {
        Swal.fire({
            title: title,
            text: message,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }

    function showError(title, message) {
        Swal.fire({
            title: title,
            text: message,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }

    function showValidationErrors(errors) {
        let errorMessages = '';
        for (const field in errors) {
            errorMessages += `<strong>${field}:</strong> ${errors[field].join('<br>')}<br>`;
        }
        
        Swal.fire({
            title: 'Validation Error',
            html: errorMessages,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }

    function handleAjaxError(xhr) {
        let errorMessage = 'An error occurred';
          
        console.log('AJAX error:', xhr);
        if (xhr.responseJSON) {
            if (xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }
        } else if (xhr.statusText) {
            errorMessage = xhr.statusText;
        }
        
        // showError('Error', errorMessage);
    }

    // Close loading when modal hides
    $('.modal').on('hidden.bs.modal', function() {
        Swal.close();
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();



    // Function to fetch and populate customers
function fetchAndPopulateCustomers(programId) {
    showLoading('Loading customers...');

    if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
    
    $.ajax({
        url: `/company/loyalty-programs/${currentProgramId}/redemptions/customers`,
        method: 'post',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function(response) {
            if (response.success) {
                swal.close();
                const customerSelect = $('#customerSelect');
                const customerreferralSelect = $('#customerReferralSelect');

                customerSelect.empty().append('<option value="">Select Customer</option>');
                customerreferralSelect.empty().append('<option value="">Select Customer</option>');
                
                response.data.forEach(customer => {
                    customerSelect.append(
                        `<option value="${customer.id}" data-points="${customer.points_balance}">
                            ${customer.name} (${customer.email}) - ${customer.points_balance} points
                        </option>`
                    );
                    customerreferralSelect.append(
                        `<option value="${customer.id}" data-points="${customer.points_balance}">
                            ${customer.name} (${customer.email}) 
                        </option>`
                    );
                });
                
                // Initialize select2 if you're using it
                if ($.fn.select2) {
                    customerSelect.select2({
                        placeholder: "Select Customer",
                        width: '100%'
                    });
                }
            }
        },
        error: handleAjaxError,
        complete: function() {
            hideLoading();
        }
    });
}

// Call this when opening the modal
$('#redemptionModal').on('show.bs.modal', function() {
    fetchAndPopulateCustomers(currentProgramId);
});

// Handle customer selection change
$('#customerSelect').change(function() {
    const selectedOption = $(this).find('option:selected');
    const pointsBalance = selectedOption.data('points') || 0;
    
    // You can use this to filter rewards by available points
    console.log(`Customer has ${pointsBalance} points`);
    // Additional logic to filter rewards can go here
});

function fetchAndPopulateRewards(programId, customerPoints) {

    if (!currentProgramId) {
            
            // showError('Error', 'Please select a loyalty program first');
            return;
        }
    // Show loading state
    $('#rewardSelect').html('<option value="">Loading rewards...</option>');
    $('#rewardSelect').prop('disabled', true);
    
    $.ajax({
        url: `/company/loyalty-programs/${programId}/rewards/getrewards`,
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function(response) {
            const rewardSelect = $('#rewardSelect');
            rewardSelect.empty();

            console.log('Rewards response:', response);
            
            if (response.success && response.data && response.data.length) {
                // Filter by customer points and format options
                const eligibleRewards = response.data.filter(reward => 
                    reward.points_required <= customerPoints
                );
                
                if (eligibleRewards.length > 0) {
                    rewardSelect.append('<option value="">Select Reward</option>');
                    
                    eligibleRewards.forEach(reward => {
                        const availability = reward.quantity === null ? 
                            'Unlimited' : `${reward.quantity} available`;
                        
                        rewardSelect.append(
                            `<option value="${reward.id}" 
                                data-points="${reward.points_required}"
                                data-quantity="${reward.quantity || ''}"
                                data-description="${reward.description || ''}">
                                ${reward.name} - ${reward.points_required} pts (${availability})
                            </option>`
                        );
                    });
                } else {
                    rewardSelect.append('<option value="" disabled>No rewards for your points</option>');
                    console.log('Customer points:', customerPoints);
                    console.log('All rewards:', response.data);
                }
            } else {
                rewardSelect.append('<option value="" disabled>No rewards available</option>');
            }
            
            rewardSelect.prop('disabled', false);
        },
        error: function(xhr) {
            $('#rewardSelect').html('<option value="">Error loading rewards</option>');
            console.error('Error:', {
                status: xhr.status,
                response: xhr.responseJSON
            });
        }
    });
}

function hideLoading() {
    // Implement your loading hide logic here
    // Example using SweetAlert:
    swal.close();
}



// Format select2 reward option display
function formatRewardOption(reward) {
    if (!reward.id) return reward.text;
    const $option = $(
        `<div class="reward-option">
            <strong>${reward.text.split(' - ')[0]}</strong>
            <div class="text-primary">${$(reward.element).data('points')} pts</div>
            ${$(reward.element).data('quantity') ? 
              `<div class="text-muted small">${$(reward.element).data('quantity')} available</div>` : ''}
            ${$(reward.element).data('description') ? 
              `<div class="text-muted small">${$(reward.element).data('description')}</div>` : ''}
        </div>`
    );
    return $option;
}

// Format select2 selected reward
function formatRewardSelection(reward) {
    if (!reward.id) return reward.text;
    return `${reward.text.split(' - ')[0]}`; // Shows just the reward name
}

// Handle reward selection changes
$('#rewardSelect').change(function() {
    const selectedOption = $(this).find('option:selected');
    const rewardDetails = $('#rewardDetails');
    
    if (selectedOption.val()) {
        const pointsRequired = selectedOption.data('points');
        const quantity = selectedOption.data('quantity');
        const description = selectedOption.data('description');
        
        let detailsHtml = `<div class="reward-details">
            <p><strong>Points Required:</strong> ${pointsRequired}</p>`;
        
        if (quantity !== undefined && quantity !== '') {
            detailsHtml += `<p><strong>Available:</strong> ${quantity}</p>`;
        }
        
        if (description) {
            detailsHtml += `<p><strong>Description:</strong> ${description}</p>`;
        }
        
        detailsHtml += `</div>`;
        rewardDetails.html(detailsHtml);
    } else {
        rewardDetails.empty();
    }
});

$('#customerSelect').change(function() {
    const customerId = $(this).val();
    const pointsBalance = $(this).find('option:selected').data('points') || 0;
    
    if (customerId) {
        // Enable reward dropdown and fetch rewards
        $('#rewardSelect').prop('disabled', false);
        fetchAndPopulateRewards(currentProgramId, pointsBalance);
        
        // Clear previous selection
        $('#rewardSelect').val('').trigger('change');
        $('#rewardDetails').empty();
    } else {
        // Reset reward section
        $('#rewardSelect').prop('disabled', true)
                         .html('<option value="">Select Customer first</option>');
        $('#rewardDetails').empty();
    }
});

});
    </script>
@endsection
@push('scripts')
    <script src="path/to/your/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush