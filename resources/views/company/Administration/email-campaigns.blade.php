@extends('layouts.vertical', ['page_title' => 'Email Campaigns', 'mode' => session('theme_mode', 'light')])

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

<!-- Chart.js for analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .email-campaigns-container {
        background: #f5f7ff;
        min-height: 100vh;
        padding: 20px 0;
    }

    .dashboard-card {
        background: #ffffff;
        border: 1px solid #e3ebf6;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-left: 4px solid #3b7ddd;
        cursor: pointer;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .card-icon {
        width: 60px;
        height: 60px;
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

    .card-content h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: #2c3e50;
    }

    .card-content p {
        margin: 8px 0 0;
        color: #64748b;
        font-weight: 500;
    }

    .campaign-card {
        background: #fff;
        border: 1px solid #e3ebf6;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .campaign-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
        border-color: #007bff;
    }

    .campaign-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .campaign-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-draft {
        background: #fff3cd;
        color: #856404;
    }

    .status-scheduled {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-sent {
        background: #d4edda;
        color: #155724;
    }

    .status-paused {
        background: #f8d7da;
        color: #721c24;
    }

    .campaign-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
        margin: 15px 0;
    }

    .stat-item {
        text-align: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #64748b;
        margin: 4px 0 0;
    }

    .search-filter-section {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    .btn-custom {
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .campaign-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .campaign-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="email-campaigns-container">
    <!-- Page Title -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Administration</a></li>
                            <li class="breadcrumb-item active">Email Campaigns</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Email Campaigns Management</h4>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="dashboard-card card-total-campaigns">
                    <div class="card-content">
                        <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3 id="totalCampaigns">0</h3>
                        <p>Total Campaigns</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card card-active-campaigns">
                    <div class="card-content">
                        <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                            <i class="fas fa-play"></i>
                        </div>
                        <h3 id="activeCampaigns">0</h3>
                        <p>Active Campaigns</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card card-scheduled-campaigns">
                    <div class="card-content">
                        <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 id="scheduledCampaigns">0</h3>
                        <p>Scheduled Campaigns</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card card-emails-sent">
                    <div class="card-content">
                        <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <h3 id="emailsSent">0</h3>
                        <p>Emails Sent</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-custom" onclick="createCampaign()">
                            <i class="fas fa-plus me-2"></i>Create Campaign
                        </button>
                        <button type="button" class="btn btn-success btn-custom" onclick="importCampaigns()">
                            <i class="fas fa-upload me-2"></i>Import Campaigns
                        </button>
                        <button type="button" class="btn btn-info btn-custom" onclick="exportCampaigns()">
                            <i class="fas fa-download me-2"></i>Export Data
                        </button>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-2"></i>Settings
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="campaignSettings()">
                                <i class="fas fa-sliders-h me-2"></i>Campaign Settings
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="automationRules()">
                                <i class="fas fa-robot me-2"></i>Automation Rules
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="deliverySettings()">
                                <i class="fas fa-envelope-open me-2"></i>Delivery Settings
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="viewReports()">
                                <i class="fas fa-chart-bar me-2"></i>Campaign Reports
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="row">
            <div class="col-12">
                <div class="search-filter-section">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search Campaigns</label>
                            <input type="text" class="form-control" id="searchCampaigns" placeholder="Search by name or description...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="filterStatus">
                                <option value="">All Status</option>
                                <option value="draft">Draft</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="sent">Sent</option>
                                <option value="paused">Paused</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date Range</label>
                            <select class="form-select" id="filterDate">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Campaign Type</label>
                            <select class="form-select" id="filterType">
                                <option value="">All Types</option>
                                <option value="newsletter">Newsletter</option>
                                <option value="promotional">Promotional</option>
                                <option value="transactional">Transactional</option>
                                <option value="automated">Automated</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary btn-sm" onclick="applyFilters()">
                                    <i class="fas fa-filter me-1"></i>Filter
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                                    <i class="fas fa-times me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaigns Grid -->
        <div class="row" id="campaignsContainer">
            <!-- Campaign cards will be populated here -->
        </div>

    </div>
</div>

<!-- Create Campaign Modal -->
<div class="modal fade" id="createCampaignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Email Campaign</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createCampaignForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label required-field">Campaign Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required-field">Campaign Type</label>
                            <select class="form-select" name="type" required>
                                <option value="">Select Type</option>
                                <option value="newsletter">Newsletter</option>
                                <option value="promotional">Promotional</option>
                                <option value="transactional">Transactional</option>
                                <option value="automated">Automated</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Brief description of the campaign..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-field">Subject Line</label>
                            <input type="text" class="form-control" name="subject" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sender Name</label>
                            <input type="text" class="form-control" name="sender_name" placeholder="Your Organization">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Schedule Type</label>
                            <select class="form-select" name="schedule_type" onchange="toggleScheduleOptions(this.value)">
                                <option value="now">Send Now</option>
                                <option value="scheduled">Schedule for Later</option>
                                <option value="recurring">Recurring Campaign</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="scheduleDateTime" style="display: none;">
                            <label class="form-label">Schedule Date & Time</label>
                            <input type="datetime-local" class="form-control" name="scheduled_at">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="previewCampaign()">Preview</button>
                    <button type="submit" class="btn btn-primary">Create Campaign</button>
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
$(document).ready(function() {
    // Load campaigns
    loadEmailCampaigns();
    updateStats();

    // Form submission
    $('#createCampaignForm').on('submit', function(e) {
        e.preventDefault();
        submitCampaign(this);
    });

    // Search functionality
    $('#searchCampaigns').on('keyup', function() {
        filterCampaigns();
    });

    // Filter change handlers
    $('#filterStatus, #filterDate, #filterType').on('change', function() {
        filterCampaigns();
    });
});

// Load sample campaigns for demonstration
function loadEmailCampaigns() {
    const sampleCampaigns = [
        {
            id: 1,
            name: 'Welcome Series',
            type: 'automated',
            status: 'active',
            subject: 'Welcome to Our Platform!',
            description: 'Automated welcome series for new users',
            sent_count: 1245,
            open_rate: 68.5,
            click_rate: 24.3,
            created_at: '2024-01-15',
            scheduled_at: null
        },
        {
            id: 2,
            name: 'Monthly Newsletter',
            type: 'newsletter',
            status: 'scheduled',
            subject: 'Your Monthly Update is Here',
            description: 'Monthly company newsletter with updates and news',
            sent_count: 0,
            open_rate: 0,
            click_rate: 0,
            created_at: '2024-01-20',
            scheduled_at: '2024-02-01 09:00:00'
        },
        {
            id: 3,
            name: 'Product Launch',
            type: 'promotional',
            status: 'sent',
            subject: 'Introducing Our Latest Product',
            description: 'Product launch announcement campaign',
            sent_count: 3421,
            open_rate: 72.1,
            click_rate: 31.8,
            created_at: '2024-01-10',
            scheduled_at: null
        },
        {
            id: 4,
            name: 'Abandoned Cart Reminder',
            type: 'automated',
            status: 'paused',
            subject: 'You left something in your cart',
            description: 'Automated reminder for abandoned shopping carts',
            sent_count: 567,
            open_rate: 45.2,
            click_rate: 18.7,
            created_at: '2024-01-08',
            scheduled_at: null
        }
    ];
    
    populateCampaignsGrid(sampleCampaigns);
    updateStats({
        total: 4,
        active: 1,
        scheduled: 1,
        sent: 2
    });
}

function populateCampaignsGrid(campaigns) {
    const container = $('#campaignsContainer');
    container.empty();
    
    campaigns.forEach(campaign => {
        const campaignCard = createCampaignCard(campaign);
        container.append(campaignCard);
    });
}

function createCampaignCard(campaign) {
    const statusClass = `status-${campaign.status}`;
    const statusText = campaign.status.charAt(0).toUpperCase() + campaign.status.slice(1);
    
    return `
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="campaign-card">
                <div class="campaign-header">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <h5 class="mb-1">${campaign.name}</h5>
                            <small class="text-muted">${campaign.type.charAt(0).toUpperCase() + campaign.type.slice(1)}</small>
                        </div>
                        <span class="campaign-status ${statusClass}">${statusText}</span>
                    </div>
                </div>
                
                <div class="campaign-content">
                    <p class="mb-2"><strong>Subject:</strong> ${campaign.subject}</p>
                    <p class="text-muted mb-3">${campaign.description}</p>
                    
                    <div class="campaign-stats">
                        <div class="stat-item">
                            <div class="stat-number">${campaign.sent_count.toLocaleString()}</div>
                            <div class="stat-label">Sent</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">${campaign.open_rate}%</div>
                            <div class="stat-label">Open Rate</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">${campaign.click_rate}%</div>
                            <div class="stat-label">Click Rate</div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">Created: ${campaign.created_at}</small>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="viewCampaign(${campaign.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-secondary" onclick="editCampaign(${campaign.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-info" onclick="duplicateCampaign(${campaign.id})">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteCampaign(${campaign.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function updateStats(stats) {
    if (stats) {
        $('#totalCampaigns').text(stats.total || 0);
        $('#activeCampaigns').text(stats.active || 0);
        $('#scheduledCampaigns').text(stats.scheduled || 0);
        $('#emailsSent').text((stats.sent * 1000 || 5234).toLocaleString());
    }
}

// Campaign Management Functions
function createCampaign() {
    $('#createCampaignModal').modal('show');
}

function submitCampaign(form) {
    // Simulate form submission
    Swal.fire({
        title: 'Success!',
        text: 'Campaign created successfully!',
        icon: 'success',
        confirmButtonText: 'OK'
    }).then(() => {
        $('#createCampaignModal').modal('hide');
        form.reset();
        loadEmailCampaigns(); // Refresh the list
    });
}

function toggleScheduleOptions(value) {
    const scheduleDiv = $('#scheduleDateTime');
    if (value === 'scheduled' || value === 'recurring') {
        scheduleDiv.show();
    } else {
        scheduleDiv.hide();
    }
}

function viewCampaign(id) {
    Swal.fire({
        title: 'Campaign Details',
        html: `
            <div class="text-start">
                <p><strong>Campaign ID:</strong> ${id}</p>
                <p><strong>Status:</strong> Active</p>
                <p><strong>Performance:</strong> Above average</p>
                <p><strong>Next Action:</strong> Review analytics</p>
            </div>
        `,
        confirmButtonText: 'Close'
    });
}

function editCampaign(id) {
    Swal.fire({
        title: 'Edit Campaign',
        text: 'Campaign editing functionality would be implemented here.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function duplicateCampaign(id) {
    Swal.fire({
        title: 'Duplicate Campaign',
        text: 'Campaign has been duplicated successfully!',
        icon: 'success',
        confirmButtonText: 'OK'
    }).then(() => {
        loadEmailCampaigns();
    });
}

function deleteCampaign(id) {
    Swal.fire({
        title: 'Delete Campaign',
        text: 'Are you sure you want to delete this campaign?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Deleted!', 'Campaign has been deleted.', 'success');
            loadEmailCampaigns();
        }
    });
}

function previewCampaign() {
    Swal.fire({
        title: 'Campaign Preview',
        html: 'Campaign preview functionality would be implemented here.',
        confirmButtonText: 'Close'
    });
}

function importCampaigns() {
    Swal.fire({
        title: 'Import Campaigns',
        text: 'Campaign import functionality would be implemented here.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function exportCampaigns() {
    Swal.fire({
        title: 'Export Data',
        text: 'Campaign data export functionality would be implemented here.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function campaignSettings() {
    Swal.fire({
        title: 'Campaign Settings',
        text: 'Campaign settings configuration would be implemented here.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function automationRules() {
    Swal.fire({
        title: 'Automation Rules',
        text: 'Email automation rules configuration would be implemented here.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function deliverySettings() {
    Swal.fire({
        title: 'Delivery Settings',
        text: 'Email delivery settings configuration would be implemented here.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function viewReports() {
    Swal.fire({
        title: 'Campaign Reports',
        text: 'Detailed campaign reports would be implemented here.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function filterCampaigns() {
    // Filter implementation would go here
    console.log('Filtering campaigns...');
}

function applyFilters() {
    filterCampaigns();
}

function resetFilters() {
    $('#searchCampaigns').val('');
    $('#filterStatus').val('');
    $('#filterDate').val('');
    $('#filterType').val('');
    loadEmailCampaigns();
}
</script>
@endpush
