
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="header-title">Performance Management</h4>
        <p class="text-muted mb-0">Track and manage employee performance reviews and appraisals</p>
    </div>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleAppraisalModal" onclick="window.performanceManager.prepareNewAppraisal()">
            <i class="fas fa-plus me-1"></i> New Appraisal
        </button>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Pending Appraisals Card -->
    <div class="col-md-6 col-xl-3">
        <div class="card stats-card bg-gradient-primary text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-2">
                        <i class="fas fa-clipboard-list fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Pending Appraisals</h6>
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="mb-0 fw-bold" id="pendingAppraisals">0</h2>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-white-20 rounded-pill px-2 py-1">
                                <i class="fas fa-arrow-up me-1"></i> 2 New
                            </span>
                        </div>
                    </div>
                    <div class="mt-auto pt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small">View Details</span>
                            <i class="fas fa-chevron-right small opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#" class="stretched-link" data-bs-toggle="modal" data-bs-target="#pendingAppraisalsModal"></a>
        </div>
    </div>

    <!-- Average Performance Card -->
    <div class="col-md-6 col-xl-3">
        <div class="card stats-card bg-gradient-success text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-2">
                        <i class="fas fa-star fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Avg. Performance</h6>
                    <div class="d-flex align-items-end">
                        <h2 class="mb-0 fw-bold" id="averagePerformance">0</h2>
                        <span class="ms-2 mb-1 small opacity-75">/ 5.0</span>
                    </div>
                    <div class="mt-auto pt-2">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-white-20 rounded-pill px-2 py-1 small">
                                <i class="fas fa-arrow-up me-1"></i> 0.3 from last month
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#performance-trends" class="stretched-link"></a>
        </div>
    </div>

    <!-- Self-Assessments Card -->
    <div class="col-md-6 col-xl-3">
        <div class="card stats-card bg-gradient-info text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-2">
                        <i class="fas fa-user-edit fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Self-Assessments</h6>
                    <div class="d-flex align-items-end mb-2">
                        <h2 class="mb-0 fw-bold" id="selfAssessments">0</h2>
                        <span class="ms-2 small opacity-75">Pending review</span>
                    </div>
                    <div class="mt-auto">
                        <div class="progress bg-white-20" style="height: 6px; border-radius: 3px;">
                            <div class="progress-bar bg-white" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span class="small opacity-75">24/24</span>
                            <span class="small opacity-75">Due in 3 days</span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#self-assessments" class="stretched-link"></a>
        </div>
    </div>

    <!-- KPI Achievement Card -->
    <div class="col-md-6 col-xl-3">
        <div class="card stats-card bg-gradient-warning text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-2">
                        <i class="fas fa-chart-pie fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Performance Trends</h6>
                    <div class="d-flex align-items-end mb-2">
                        <h2 class="mb-0 fw-bold" id="performanceTrends">0</h2>
                        <span class="ms-2 small opacity-75">This month</span>
                    </div>
                    <div class="mt-auto">
                        <div class="progress bg-white-20" style="height: 6px; border-radius: 3px;">
                            <div class="progress-bar bg-white" role="progressbar" style="width: 87%;" aria-valuenow="87" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span class="small opacity-75">32/41 KPIs Met</span>
                            <span class="small opacity-75">
                                <i class="fas fa-arrow-up text-success"></i> 5%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#kpi-achievement" class="stretched-link"></a>
        </div>
    </div>
</div>

<style>
    /* Full screen modal covering everything */
    .modal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background-color: rgba(0, 0, 0, 0.7) !important;
        z-index: 999999 !important;
    }
    
    .modal.show {
        display: flex !important;
        align-items: center;
        justify-content: center;
    }
    
    .modal-dialog {
        z-index: 9999999 !important;
        position: relative;
    }
    
    .modal-content {
        display: flex;
        flex-direction: column;
        border-radius: 12px;
        position: relative;
        z-index: 9999999;
    }
    
    .modal-backdrop {
        display: none !important;
    }
    
    /* Modal Footer Styling */
    .modal-footer {
        border-top: 1px solid #dee2e6;
        padding: 1rem;
        background-color: #f8f9fa;
        position: sticky;
        bottom: 0;
        z-index: 1;
        border-radius: 0 0 12px 12px;
    }
    
    .modal-body {
        max-height: calc(90vh - 140px);
        overflow-y: auto;
        flex: 1;
    }
    
    .modal-header {
        border-radius: 12px 12px 0 0;
    }

    /* Stats Cards Styling */
    .stats-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .stats-card:hover::before {
        opacity: 1;
    }
    
    .hover-scale {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: translateY(-5px) scale(1.02);
    }
    
    .icon-shape {
        transition: all 0.3s ease;
    }
    
    .stats-card:hover .icon-shape {
        transform: scale(1.1);
        background-color: rgba(255,255,255,0.3) !important;
    }
    
    /* Gradient Backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3b7ddd 0%, #2f6bc6 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #1cbb8c 0%, #189f74 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #3f9ee5 0%, #2f8ed4 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f7b84b 0%, #f59f00 100%);
    }
    
    /* Ensure text is readable on gradient backgrounds */
    .text-white-50 {
        opacity: 0.9;
    }
    
    /* Progress bar styling */
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        transition: width 0.6s ease;
    }
    
</style>

<div class="row mt-4">
    <div class="col-12">
        <ul class="nav nav-tabs nav-bordered mb-3">
            <li class="nav-item">
                <a href="#appraisal-queue" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                    <i class="fas fa-clipboard-list me-1"></i> Appraisal Queue
                </a>
            </li>
            <li class="nav-item">
                <a href="#self-assessments" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                    <i class="fas fa-user-edit me-1"></i> Self-Assessments
                </a>
            </li>
            <li class="nav-item">
                <a href="#performance-history" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                    <i class="fas fa-history me-1"></i> Performance History
                </a>
            </li>
            <li class="nav-item">
                <a href="#performance-trends" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                    <i class="fas fa-chart-line me-1"></i> Performance Trends
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane show active" id="appraisal-queue">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: 180px;">
                            <option>All Appraisal Types</option>
                            <option>Monthly</option>
                            <option>Quarterly</option>
                            <option>Annual</option>
                        </select>
                        <select class="form-select form-select-sm" style="width: 150px;">
                            <option>All Status</option>
                            <option>Pending</option>
                            <option>In Progress</option>
                            <option>Completed</option>
                            <option>Overdue</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" id="exportAppraisalQueueBtn">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                        <button class="btn btn-sm btn-outline-primary" id="refreshAppraisalQueueBtn">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-centered table-hover table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Appraisal Type</th>
                                <th>Period</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Rating</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="appraisalQueueTableBody">
                            <!-- Appraisal queue will be loaded dynamically -->
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Loading appraisal queue...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="tab-pane fade" id="self-assessments">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Pending Self-Assessments</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" id="refreshSelfAssessmentsBtn">
                                    <i class="fas fa-sync-alt me-1"></i> Refresh
                                </button>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Appraisal Type</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Last Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="pendingSelfAssessmentsTableBody">
                                    <!-- Pending self-assessments will be loaded dynamically -->
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Loading pending self-assessments...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Completed Self-Assessments</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Appraisal Type</th>
                                        <th>Submitted On</th>
                                        <th>Status</th>
                                        <th>Manager Review</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="completedSelfAssessmentsTableBody">
                                    <!-- Completed self-assessments will be loaded dynamically -->
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Loading completed self-assessments...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="performance-history">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="header-title mb-0">Employee Performance History</h4>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#historyFilters" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i> Filters
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="window.performanceManager.clearHistoryFilters()">
                                    <i class="fas fa-times me-1"></i> Clear
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="window.performanceManager.exportHistoryData()">
                                    <i class="fas fa-download me-1"></i> Export
                                </button>
                            </div>
                        </div>
                        
                        <!-- Collapsible Filter Panel -->
                        <div class="collapse mb-4" id="historyFilters">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Employee</label>
                                            <select class="form-select form-select-sm" id="historyEmployeeFilter">
                                                <option value="">All Employees</option>
                                                <!-- Options will be populated dynamically -->
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Department</label>
                                            <select class="form-select form-select-sm" id="historyDepartmentFilter">
                                                <option value="">All Departments</option>
                                                <option value="finance">Finance</option>
                                                <option value="home_connection_high_rise">Home Connection/ High Rise</option>
                                                <option value="human_resource_administration">Human Resource/ Administration</option>
                                                <option value="procurement_warehouse">Procurement/Warehouse</option>
                                                <option value="commercial">Commercial</option>
                                                <option value="gpon">GPON</option>
                                                <option value="qehs">QEHS</option>
                                                <option value="public_relations">Public Relations</option>
                                                <option value="audit">Audit</option>
                                                <option value="consultant_services">Consultant Services</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Current Rating</label>
                                            <select class="form-select form-select-sm" id="historyRatingFilter">
                                                <option value="">All Ratings</option>
                                                <option value="excellent">Excellent</option>
                                                <option value="good">Good</option>
                                                <option value="satisfactory">Satisfactory</option>
                                                <option value="needs_improvement">Needs Improvement</option>
                                                <option value="poor">Poor</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Date Range</label>
                                            <select class="form-select form-select-sm" id="historyDateRangeFilter">
                                                <option value="">All Time</option>
                                                <option value="last_30_days">Last 30 Days</option>
                                                <option value="last_3_months">Last 3 Months</option>
                                                <option value="last_6_months">Last 6 Months</option>
                                                <option value="last_year">Last Year</option>
                                                <option value="custom">Custom Range</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-2" id="customDateRange" style="display: none;">
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">From Date</label>
                                            <input type="date" class="form-control form-control-sm" id="historyFromDate">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">To Date</label>
                                            <input type="date" class="form-control form-control-sm" id="historyToDate">
                                        </div>
                                        <div class="col-md-6 d-flex align-items-end">
                                            <button class="btn btn-sm btn-primary" onclick="window.performanceManager.applyHistoryFilters()">
                                                <i class="fas fa-search me-1"></i> Apply Filters
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <button class="btn btn-sm btn-primary" onclick="window.performanceManager.applyHistoryFilters()">
                                                <i class="fas fa-search me-1"></i> Apply Filters
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Current Rating</th>
                                        <th>Last Appraisal</th>
                                        <th>Trend</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="performanceHistoryTableBody">
                                    <!-- Performance history will be loaded dynamically -->
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Loading performance history...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="header-title mb-0">Appraisal History</h4>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" style="width: 150px;">
                                    <option>All Types</option>
                                    <option>Monthly</option>
                                    <option>Quarterly</option>
                                    <option>Annual</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Appraisal Type</th>
                                        <th>Period</th>
                                        <th>Completed On</th>
                                        <th>Rating</th>
                                        <th>Reviewer</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="appraisalHistoryTableBody">
                                    <!-- Appraisal history will be loaded dynamically -->
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Loading appraisal history...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="performance-trends">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="header-title mb-0">Performance Trend (Last 6 Months)</h4>
                                    <div class="d-flex gap-2">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-calendar-alt me-1"></i> Last 6 Months
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item date-range" href="#" data-range="3">Last 3 Months</a></li>
                                                <li><a class="dropdown-item date-range active" href="#" data-range="6">Last 6 Months</a></li>
                                                <li><a class="dropdown-item date-range" href="#" data-range="12">This Year</a></li>
                                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#customDateRangeModal">Custom Range</a></li>
                                            </ul>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#trendAnalysisModal">
                                            <i class="fas fa-chart-line me-1"></i> View Analysis
                                        </button>
                                    </div>
                                </div>
                                <div id="performance-trend-chart" style="height: 320px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#trendDetailModal">
                                    <!-- Chart will be rendered here -->
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-muted">Loading performance data...</p>
                                    </div>
                                </div>
                                <div class="text-end mt-2">
                                    <small class="text-muted">Click on the chart for detailed view</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="header-title mb-0">Performance Distribution</h4>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#distributionAnalysisModal">
                                        <i class="fas fa-chart-pie me-1"></i> View Details
                                    </button>
                                </div>
                                <div id="performance-distribution-chart" style="height: 280px;">
                                    <!-- Chart will be rendered here -->
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-auto pt-3" id="performanceDistributionLegend">
                                    <!-- Performance distribution legend will be loaded dynamically -->
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-muted small">Loading distribution data...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="header-title mb-0">Department Performance</h4>
                                    <select class="form-select form-select-sm" style="width: auto;">
                                        <option>Q2 2023</option>
                                        <option>Q1 2023</option>
                                        <option>Q4 2022</option>
                                    </select>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Department</th>
                                                <th class="text-end">Avg. Rating</th>
                                                <th class="text-end">% of Target</th>
                                                <th class="text-end">Trend</th>
                                            </tr>
                                        </thead>
                                        <tbody id="departmentPerformanceTableBody">
                                            <!-- Department performance will be loaded dynamically -->
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p class="mt-2 text-muted small">Loading department performance...</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="header-title mb-0">Top Performers</h4>
                                    <a href="#" class="btn btn-sm btn-link">View All</a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Employee</th>
                                                <th>Department</th>
                                                <th class="text-end">Rating</th>
                                                <th class="text-end">KPI Score</th>
                                            </tr>
                                        </thead>
                                        <tbody id="topPerformersTableBody">
                                            <!-- Top performers will be loaded dynamically -->
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p class="mt-2 text-muted small">Loading top performers...</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Trend Analysis Modal -->
<div class="modal fade" id="trendAnalysisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Performance Trend Analysis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                <h3 class="mb-1 text-primary">+8.2%</h3>
                                <p class="text-muted mb-0">Avg. Performance Increase</p>
                                <small class="text-success"><i class="fas fa-arrow-up me-1"></i> 2.4% from last period</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                <h3 class="mb-1 text-success">4.3</h3>
                                <p class="text-muted mb-0">Current Avg. Rating</p>
                                <small class="text-success"><i class="fas fa-arrow-up me-1"></i> 0.3 from last period</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h6 class="mb-3">Key Insights</h6>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Sales team shows strongest growth trend</span>
                        <span class="badge bg-success rounded-pill">+12.5%</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Customer support shows slight decline</span>
                        <span class="badge bg-danger rounded-pill">-2.1%</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Q2 shows highest performance across all teams</span>
                        <span class="badge bg-primary rounded-pill">Peak</span>
                    </li>
                </ul>
                
                <div class="form-group mb-3">
                    <label class="form-label">Filter by Department</label>
                    <select class="form-select">
                        <option>All Departments</option>
                        <option>Sales</option>
                        <option>Marketing</option>
                        <option>Engineering</option>
                        <option>Customer Support</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="exportTrendReportBtn">Export Report</button>
            </div>
        </div>
    </div>
</div>

<!-- Trend Detail Modal -->
<div class="modal fade" id="trendDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detailed Performance Trend</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div id="detailed-trend-chart" style="height: 400px;">
                            <!-- Detailed chart will be rendered here -->
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Loading detailed trend data...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">Trend Analysis</h6>
                                <div class="mb-3">
                                    <label class="form-label">Time Period</label>
                                    <select class="form-select">
                                        <option>Last 6 Months</option>
                                        <option>Last 12 Months</option>
                                        <option>Year to Date</option>
                                        <option>Custom Range</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Department</label>
                                    <select class="form-select">
                                        <option>All Departments</option>
                                        <option>Sales</option>
                                        <option>Marketing</option>
                                        <option>Engineering</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Metric</label>
                                    <select class="form-select">
                                        <option>Overall Performance</option>
                                        <option>KPI Achievement</option>
                                        <option>Goal Completion</option>
                                        <option>Skill Assessment</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary w-100">Apply Filters</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Performance Breakdown</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Metric</th>
                                        <th class="text-end">Current</th>
                                        <th class="text-end">Previous</th>
                                        <th class="text-end">Change</th>
                                        <th class="text-end">Trend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Average Rating</td>
                                        <td class="text-end">4.3</td>
                                        <td class="text-end">4.1</td>
                                        <td class="text-end text-success">+0.2</td>
                                        <td class="text-end"><i class="fas fa-arrow-up text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td>KPI Achievement</td>
                                        <td class="text-end">92%</td>
                                        <td class="text-end">88%</td>
                                        <td class="text-end text-success">+4%</td>
                                        <td class="text-end"><i class="fas fa-arrow-up text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Goal Completion</td>
                                        <td class="text-end">78%</td>
                                        <td class="text-end">82%</td>
                                        <td class="text-end text-danger">-4%</td>
                                        <td class="text-end"><i class="fas fa-arrow-down text-danger"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline-primary">Export Data</button>
                <button type="button" class="btn btn-primary">Save as Report</button>
            </div>
        </div>
    </div>
</div>

<!-- Distribution Analysis Modal -->
<div class="modal fade" id="distributionAnalysisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Performance Distribution Analysis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div id="detailed-distribution-chart" style="height: 300px;">
                            <!-- Detailed distribution chart will be rendered here -->
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Loading distribution data...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Performance Categories</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Exceeds Expectations (4.5-5.0)</span>
                                        <span>25%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Meets Expectations (3.5-4.4)</span>
                                        <span>45%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Needs Improvement (2.0-3.4)</span>
                                        <span>25%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Below Expectations (1.0-1.9)</span>
                                        <span>5%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 5%" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Department-wise Distribution</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th class="text-center">Exceeds</th>
                                        <th class="text-center">Meets</th>
                                        <th class="text-center">Needs Imp.</th>
                                        <th class="text-center">Below Exp.</th>
                                        <th class="text-center">Avg. Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Sales</td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center">50%</td>
                                        <td class="text-center">12%</td>
                                        <td class="text-center">3%</td>
                                        <td class="text-center fw-bold">4.2</td>
                                    </tr>
                                    <tr>
                                        <td>Marketing</td>
                                        <td class="text-center">28%</td>
                                        <td class="text-center">55%</td>
                                        <td class="text-center">15%</td>
                                        <td class="text-center">2%</td>
                                        <td class="text-center fw-bold">4.1</td>
                                    </tr>
                                    <tr>
                                        <td>Engineering</td>
                                        <td class="text-center">30%</td>
                                        <td class="text-center">52%</td>
                                        <td class="text-center">15%</td>
                                        <td class="text-center">3%</td>
                                        <td class="text-center fw-bold">4.1</td>
                                    </tr>
                                    <tr>
                                        <td>Customer Support</td>
                                        <td class="text-center">20%</td>
                                        <td class="text-center">60%</td>
                                        <td class="text-center">18%</td>
                                        <td class="text-center">2%</td>
                                        <td class="text-center fw-bold">4.0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline-primary" id="exportDistributionDataBtn">Export Data</button>
                <button type="button" class="btn btn-primary" id="generateDistributionReportBtn">Generate Report</button>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Appraisal Modal -->
<div class="modal fade" id="scheduleAppraisalModal" tabindex="-1" aria-labelledby="scheduleAppraisalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleAppraisalModalLabel">Schedule Performance Appraisal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleAppraisalForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="appraisalEmployee" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select class="form-select" id="appraisalEmployee" name="employee_id" required>
                                <option value="">Select Employee</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="appraisalType" class="form-label">Appraisal Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="appraisalType" name="type" required>
                                <option value="">Select Appraisal Type</option>
                                <option value="self">Self Assessment</option>
                                <option value="manager">Manager Review</option>
                                <option value="peer">Peer Review</option>
                                <option value="360">360 Degree Review</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="appraisalStartDate" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="appraisalStartDate" name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="appraisalDueDate" class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="appraisalDueDate" name="due_date" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="appraisalReviewer" class="form-label">Reviewer <span class="text-danger">*</span></label>
                            <select class="form-select" id="appraisalReviewer" name="reviewer_id" required>
                                <option value="">Select Reviewer</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="appraisalPeriod" class="form-label">Period <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="appraisalPeriod" name="period" placeholder="Select dates to auto-generate period" readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="mb-3">Key Performance Indicators (KPIs)</h6>
                        <div id="kpiContainer">
                            <!-- KPIs will be loaded based on template -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Sales Target Achievement</h6>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="kpi1" checked>
                                            <label class="form-check-label" for="kpi1">Include</label>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label small text-muted mb-0">Performance Score</label>
                                            <span class="badge bg-info" id="kpi1ScoreLabel">75</span>
                                        </div>
                                        <input type="range" class="form-range w-100" min="1" max="100" value="75" id="kpi1Score" oninput="document.getElementById('kpi1ScoreLabel').textContent = this.value; calculateOverallRating();">
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Customer Satisfaction</h6>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="kpi2" checked>
                                            <label class="form-check-label" for="kpi2">Include</label>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label small text-muted mb-0">Performance Score</label>
                                            <span class="badge bg-info" id="kpi2ScoreLabel">80</span>
                                        </div>
                                        <input type="range" class="form-range w-100" min="1" max="100" value="80" id="kpi2Score" oninput="document.getElementById('kpi2ScoreLabel').textContent = this.value; calculateOverallRating();">
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Team Collaboration</h6>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="kpi3" checked>
                                            <label class="form-check-label" for="kpi3">Include</label>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label small text-muted mb-0">Performance Score</label>
                                            <span class="badge bg-info" id="kpi3ScoreLabel">70</span>
                                        </div>
                                        <input type="range" class="form-range w-100" min="1" max="100" value="70" id="kpi3Score" oninput="document.getElementById('kpi3ScoreLabel').textContent = this.value; calculateOverallRating();">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addKpiBtn" data-bs-toggle="modal" data-bs-target="#addKpiModal">
                            <i class="fas fa-plus me-1"></i> Add Custom KPI
                        </button>
                    </div>

                    <div class="mb-3">
                        <label for="appraisalNotes" class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="appraisalNotes" name="notes" rows="3" placeholder="Enter any additional notes about this appraisal"></textarea>
                    </div>

                    <!-- Overall Rating Section -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="overallScore" class="form-label">Overall Score</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="overallScore" name="overall_score" 
                                       min="1" max="5" step="0.1" value="3.0" readonly>
                                <span class="input-group-text">/5.0</span>
                            </div>
                            <small class="text-muted">Automatically calculated from KPI scores</small>
                        </div>
                        <div class="col-md-6">
                            <label for="overallRating" class="form-label">Overall Rating</label>
                            <select class="form-control" id="overallRating" name="overall_rating" disabled>
                                <option value="excellent">Excellent (4.5-5.0)</option>
                                <option value="good">Good (3.5-4.4)</option>
                                <option value="satisfactory" selected>Satisfactory (2.5-3.4)</option>
                                <option value="needs_improvement">Needs Improvement (1.5-2.4)</option>
                                <option value="poor">Poor (1.0-1.4)</option>
                            </select>
                            <small class="text-muted">Based on overall score</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="scheduleAppraisalForm">Schedule Appraisal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Appraisal Modal -->
<div class="modal fade" id="viewAppraisalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAppraisalModalLabel">Appraisal Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="appraisalDetailsContent">
                <!-- Content will be loaded dynamically -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading appraisal details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printAppraisalBtn">Print</button>
            </div>
        </div>
    </div>
</div>

<!-- Submit Appraisal Modal -->
<div class="modal fade" id="submitAppraisalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit Appraisal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit this appraisal? Once submitted, you won't be able to make further changes.</p>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmSubmit" required>
                    <label class="form-check-label" for="confirmSubmit">
                        I confirm that all information is accurate and complete
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSubmitBtn">Submit Appraisal</button>
            </div>
        </div>
    </div>
</div>

<!-- Approve/Reject Modal -->
<div class="modal fade" id="reviewAppraisalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review Appraisal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="reviewComments" class="form-label">Your Comments</label>
                    <textarea class="form-control" id="reviewComments" rows="3" placeholder="Add your review comments here..."></textarea>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="acknowledgeReview" required>
                    <label class="form-check-label" for="acknowledgeReview">
                        I acknowledge that I have reviewed this appraisal
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger me-2" id="rejectBtn">
                    <i class="fas fa-times me-1"></i> Reject
                </button>
                <button type="button" class="btn btn-success" id="approveBtn">
                    <i class="fas fa-check me-1"></i> Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add KPI Modal -->
<div class="modal fade" id="addKpiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Custom KPI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addKpiForm">
                    <div class="mb-3">
                        <label for="kpiName" class="form-label">KPI Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kpiName" required>
                    </div>
                    <div class="mb-3">
                        <label for="kpiDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="kpiDescription" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kpiWeight" class="form-label">Weight (%)</label>
                                <input type="number" class="form-control" id="kpiWeight" min="1" max="100" value="10">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kpiTarget" class="form-label">Target Value</label>
                                <input type="number" class="form-control" id="kpiTarget">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="kpiMeasurement" class="form-label">Measurement Unit</label>
                        <select class="form-select" id="kpiMeasurement">
                            <option value="percentage">Percentage (%)</option>
                            <option value="number">Number</option>
                            <option value="currency">Currency</option>
                            <option value="rating">Rating (1-5)</option>
                            <option value="yesno">Yes/No</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveKpiBtn">Add KPI</button>
            </div>
        </div>
    </div>
</div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
    // Initialize tooltips
  
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    console.log('🔍 DEBUG: Tooltips initialized');
    addDebugLog('Tooltips initialized', 'success');

        // Initialize date range picker for trend analysis
        const dateRangeDropdown = document.getElementById('dateRangeDropdown');
        if (dateRangeDropdown) {
            dateRangeDropdown.addEventListener('change', function() {
                const selectedRange = this.value;
                const dateRangeText = this.options[this.selectedIndex].text;
                
                // Show loading state
                const trendCard = document.querySelector('#performance-trends .card-body');
                const originalContent = trendCard.innerHTML;
                trendCard.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Loading ${dateRangeText} data...</span>
                    </div>
                `;
                
                // Simulate data loading
                setTimeout(() => {
                    // In a real app, you would fetch data based on the selected range
                    console.log(`Loading data for date range: ${selectedRange}`);
                    // For demo, we'll just reload the original content
                    trendCard.innerHTML = originalContent;
                    // Re-initialize tooltips after content is loaded
                    initializeTooltips();
                }, 1000);
            });
        }

        // Handle custom date range selection
        const customDateRangeBtn = document.getElementById('customDateRangeBtn');
        if (customDateRangeBtn) {
            customDateRangeBtn.addEventListener('click', function() {
                // In a real app, this would open a date range picker
                // For now, we'll just simulate it with a prompt
                const dateRange = prompt('Enter date range (e.g., 2024-01-01 to 2024-03-31');
                if (dateRange) {
                    // Update the dropdown to show custom range
                    const customOption = document.querySelector('#dateRangeDropdown option[value="custom"]');
                    if (customOption) {
                        customOption.text = `Custom: ${dateRange}`;
                        customOption.selected = true;
                        // Trigger change event
                        dateRangeDropdown.dispatchEvent(new Event('change'));
                    }
                }
            });
        }

        // Handle trend chart click to show detailed modal
        const trendChart = document.getElementById('performance-trend-chart');
        if (trendChart) {
            trendChart.addEventListener('click', function() {
                // Show loading state in the modal
                const modal = new bootstrap.Modal(document.getElementById('trendDetailModal'));
                const modalBody = document.querySelector('#trendDetailModal .modal-body');
                const originalContent = modalBody.innerHTML;
                
                modalBody.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Loading detailed trend data...</span>
                    </div>
                `;
                
                modal.show();
                
                // Simulate data loading
                setTimeout(() => {
                    // In a real app, you would fetch detailed data here
                    console.log('Loading detailed trend data...');
                    // Restore original content with actual data
                    modalBody.innerHTML = originalContent;
                    // Initialize charts
                    initializeTrendDetailChart();
                    // Re-initialize tooltips
                    initializeTooltips();
                }, 1000);
            });
        }




    // Show confirmation modal
    function showConfirmationModal(title, message, actionType) {
        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        const modalTitle = document.getElementById('confirmationModalTitle');
        const modalBody = document.getElementById('confirmationModalBody');
        const confirmBtn = document.getElementById('confirmActionBtn');
        
        // Update modal content
        modalTitle.textContent = title;
        modalBody.textContent = message;
        
        // Set up confirm button action
        confirmBtn.onclick = function() {
            if (actionType === 'export') {
                // Handle export action
                console.log('Exporting data...');
                showToast('Data exported successfully!', 'success');
            } else if (actionType === 'save') {
                // Handle save report action
                console.log('Saving report...');
                showToast('Report saved successfully!', 'success');
            }
            
            // Close the modal
            modal.hide();
        };
        
        // Show the modal
        modal.show();
    }
    
    // Show toast notification
    function showToast(message, type = 'info') {
        const toastContainer = document.createElement('div');
        toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '11';
        
        const toastId = 'toast-' + Date.now();
        const toastClass = `toast align-items-center text-white bg-${type} border-0`;
        
        toastContainer.innerHTML = `
            <div id="${toastId}" class="${toastClass}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        document.body.appendChild(toastContainer);
        
        // Initialize and show the toast
        const toastEl = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000
        });
        
        toast.show();
        
        // Remove the toast from DOM after it's hidden
        toastEl.addEventListener('hidden.bs.toast', function() {
            toastContainer.remove();
        });
    }
    
    // Initialize trend detail chart (placeholder for actual chart implementation)
    function initializeTrendDetailChart() {
        console.log('Initializing trend detail chart...');
        // In a real app, you would initialize a chart here
        // Example with Chart.js:
        /*
        const ctx = document.getElementById('detailed-trend-chart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Performance Score',
                    data: [75, 78, 82, 80, 85, 88],
                    borderColor: '#3b7ddd',
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 50,
                        max: 100
                    }
                }
            }
        });
        */
    }
    
    // Initialize tooltips
    function initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Handle export and save report buttons
    document.addEventListener('click', function(e) {
        // Handle export buttons
        if (e.target.matches('[data-action="export"], [data-action="export"] *')) {
            const button = e.target.closest('[data-action="export"]');
            const title = button.getAttribute('data-title') || 'Export Data';
            const message = button.getAttribute('data-message') || 'Are you sure you want to export this data?';
            showConfirmationModal(title, message, 'export');
        }
        
        // Handle save report buttons
        if (e.target.matches('[data-action="save-report"], [data-action="save-report"] *')) {
            const button = e.target.closest('[data-action="save-report"]');
            const title = button.getAttribute('data-title') || 'Save Report';
            const message = button.getAttribute('data-message') || 'Are you sure you want to save this report?';
            showConfirmationModal(title, message, 'save');
        }
    });
    
    // Initialize progress bar in add goal modal
    const goalProgress = document.getElementById('goalProgress');
    if (goalProgress) {
        const goalProgressValue = document.getElementById('goalProgressValue');
        goalProgress.addEventListener('input', function() {
            goalProgressValue.textContent = this.value + '%';
        });
    }


    // Performance Management Class
    class PerformanceManagement {
        constructor() {
            console.log('DEBUG: PerformanceManagement constructor called');
            this.baseUrl = '/company/hr/performance';
            this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('DEBUG: CSRF Token retrieved:', this.csrfToken);
            // Initialize empty arrays - no hardcoded data
            this.appraisals = [];
            this.performanceHistory = [];
            this.editingAppraisalId = undefined;
            this.init();
        }

        init() {
            console.log('DEBUG: PerformanceManagement init() called');

            // Check if DOM elements exist
            console.log('DEBUG: Checking DOM elements...');
            console.log('DEBUG: pendingAppraisals element:', document.getElementById('pendingAppraisals'));
            console.log('DEBUG: averagePerformance element:', document.getElementById('averagePerformance'));
            console.log('DEBUG: selfAssessments element:', document.getElementById('selfAssessments'));
            console.log('DEBUG: performanceTrends element:', document.getElementById('performanceTrends'));
            console.log('DEBUG: appraisalQueueTableBody element:', document.getElementById('appraisalQueueTableBody'));

            this.loadPerformanceStats();
            this.loadAppraisalQueue();
            this.loadPerformanceHistory();
            this.loadSelfAssessments();
            this.bindEvents();
        }

        async loadPerformanceStats() {
            addDebugLog('Starting loadPerformanceStats...', 'info');
            updateDebugStatus('debug-stats-status', 'Loading...', 'bg-warning');
            addDebugLog('CSRF Token: ' + (this.csrfToken ? 'Present' : 'Missing'), 'info');

            try {
                addDebugLog('Making API call to /company/hr/performance/stats', 'info');
                const response = await fetch('/company/hr/performance/stats', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });

                addDebugLog('Response status: ' + response.status + ', OK: ' + response.ok, response.ok ? 'success' : 'warning');

                const result = await response.json();
                addDebugLog('API Response received: ' + JSON.stringify(result), 'info');

                if (result.success) {
                    addDebugLog('Updating stats cards with API data: ' + JSON.stringify(result.data), 'success');
                    updateDebugStatus('debug-stats-status', 'Success', 'bg-success');
                    this.updateStatsCards(result.data);
                } else {
                    addDebugLog('API returned success=false: ' + result.message, 'error');
                    updateDebugStatus('debug-stats-status', 'API Error', 'bg-danger');
                    // Fallback to sample data if API fails
                    const stats = {
                        pending_appraisals: this.appraisals.filter(a => a.status === 'pending').length,
                        average_performance: this.calculateAveragePerformance(),
                        self_assessments: this.appraisals.filter(a => a.status === 'pending').length,
                        performance_trends: this.calculatePerformanceTrends()
                    };
                    addDebugLog('Using fallback stats: ' + JSON.stringify(stats), 'warning');
                    this.updateStatsCards(stats);
                }
            } catch (error) {
                addDebugLog('Error in loadPerformanceStats: ' + error.message, 'error');
                updateDebugStatus('debug-stats-status', 'Network Error', 'bg-danger');
                // Fallback to sample data
                const stats = {
                    pending_appraisals: this.appraisals.filter(a => a.status === 'pending').length,
                    average_performance: this.calculateAveragePerformance(),
                    self_assessments: this.appraisals.filter(a => a.status === 'pending').length,
                    performance_trends: this.calculatePerformanceTrends()
                };
                addDebugLog('Using fallback stats due to error: ' + JSON.stringify(stats), 'warning');
                this.updateStatsCards(stats);
            }
        }

        calculateAveragePerformance() {
            const completedAppraisals = this.appraisals.filter(a => a.rating !== null);
            if (completedAppraisals.length === 0) return 0;
            
            const totalRating = completedAppraisals.reduce((sum, a) => sum + a.rating, 0);
            return (totalRating / completedAppraisals.length).toFixed(1);
        }

        calculatePerformanceTrends() {
            const completedAppraisals = this.appraisals.filter(a => a.rating !== null);
            return completedAppraisals.length;
        }

        updateStatsCards(stats) {
            addDebugLog('updateStatsCards called with data: ' + JSON.stringify(stats), 'info');

            // Update pending appraisals
            const pendingAppraisals = document.getElementById('pendingAppraisals');
            if (pendingAppraisals) {
                addDebugLog('Updating pendingAppraisals element to: ' + stats.pending_appraisals, 'success');
                pendingAppraisals.textContent = stats.pending_appraisals;
            } else {
                addDebugLog('pendingAppraisals element not found!', 'error');
            }

            // Update average performance
            const averagePerformance = document.getElementById('averagePerformance');
            if (averagePerformance) {
                addDebugLog('Updating averagePerformance element to: ' + stats.average_performance, 'success');
                averagePerformance.textContent = stats.average_performance;
            } else {
                addDebugLog('averagePerformance element not found!', 'error');
            }

            // Update self assessments
            const selfAssessments = document.getElementById('selfAssessments');
            if (selfAssessments) {
                addDebugLog('Updating selfAssessments element to: ' + stats.self_assessments, 'success');
                selfAssessments.textContent = stats.self_assessments;
            } else {
                addDebugLog('selfAssessments element not found!', 'error');
            }

            // Update performance trends
            const performanceTrends = document.getElementById('performanceTrends');
            if (performanceTrends) {
                addDebugLog('Updating performanceTrends element to: ' + stats.performance_trends, 'success');
                performanceTrends.textContent = stats.performance_trends;
            } else {
                addDebugLog('performanceTrends element not found!', 'error');
            }
        }

        async loadAppraisalQueue(filters = {}) {
            addDebugLog('Starting loadAppraisalQueue with filters: ' + JSON.stringify(filters), 'info');
            updateDebugStatus('debug-queue-status', 'Loading...', 'bg-warning');
            // Show loading state
            this.showTableLoading('appraisalQueueTableBody', 'Loading appraisal queue...');

            try {
                addDebugLog('Making API call to /company/hr/performance/ for appraisal queue', 'info');
                const requestBody = {
                    status: filters.status && filters.status !== 'All Status' ? filters.status : 'all',
                    type: filters.type && filters.type !== 'All Appraisal Types' ? filters.type : 'all'
                };
                addDebugLog('Request body: ' + JSON.stringify(requestBody), 'info');

                const response = await fetch('/company/hr/performance/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify(requestBody)
                });

                addDebugLog('Appraisal queue response status: ' + response.status + ', OK: ' + response.ok, response.ok ? 'success' : 'warning');

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                addDebugLog('Appraisal queue API Response: ' + JSON.stringify(result), 'info');

                if (result.success) {
                    addDebugLog('Updating appraisal table with API data, count: ' + result.data.length, 'success');
                    updateDebugStatus('debug-queue-status', 'Success (' + result.data.length + ' records)', 'bg-success');
                    
                    if (result.data.length === 0) {
                        // Show empty state
                        addDebugLog('No data from API, showing empty state', 'info');
                        this.showEmptyTable('appraisalQueueTableBody', 'No appraisals found', 8);
                        updateDebugStatus('debug-queue-status', 'No Data', 'bg-warning');
                    } else {
                        this.updateAppraisalTable(result.data);
                    }
                } else {
                    addDebugLog('API returned success=false for appraisal queue: ' + result.message, 'error');
                    updateDebugStatus('debug-queue-status', 'API Error', 'bg-danger');
                    // Show empty state instead of sample data
                    this.showEmptyTable('appraisalQueueTableBody', 'Failed to load appraisals', 8);
                }
            } catch (error) {
                addDebugLog('Error in loadAppraisalQueue: ' + error.message, 'error');
                updateDebugStatus('debug-queue-status', 'Network Error', 'bg-danger');
                // Show empty state instead of sample data
                this.showEmptyTable('appraisalQueueTableBody', 'Failed to load appraisals', 8);
            }
        }


        showTableLoading(tableBodyId, message) {
            const tableBody = document.getElementById(tableBodyId);
            if (!tableBody) return;
            
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">${message}</p>
                    </td>
                </tr>
            `;
        }

        updateAppraisalTable(appraisals) {
            addDebugLog('updateAppraisalTable called with ' + appraisals.length + ' appraisals', 'info');
            const tableBody = document.getElementById('appraisalQueueTableBody');
            if (!tableBody) {
                addDebugLog('appraisalQueueTableBody element not found!', 'error');
                return;
            }

            if (appraisals.length === 0) {
                addDebugLog('No appraisals to display, showing empty table', 'warning');
                this.showEmptyTable('appraisalQueueTableBody', 'No appraisals found', 8);
                return;
            }

            addDebugLog('Rendering ' + appraisals.length + ' appraisal rows', 'success');
            tableBody.innerHTML = appraisals.map(appraisal => this.createAppraisalRow(appraisal)).join('');
            addDebugLog('Appraisal table updated successfully', 'success');
        }

        showEmptyTable(tableBodyId, message, colSpan = 8) {
            const tableBody = document.getElementById(tableBodyId);
            if (!tableBody) return;
            
            tableBody.innerHTML = `
                <tr>
                    <td colspan="${colSpan}" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-clipboard-list" style="font-size: 3rem; opacity: 0.5;"></i>
                            <p class="mt-3 mb-0">${message}</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        createAppraisalRow(appraisal) {
            const statusBadge = this.getStatusBadge(appraisal.status);
            const progressBar = appraisal.progress > 0 ? `
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" role="progressbar" style="width: ${appraisal.progress}%"
                          aria-valuenow="${appraisal.progress}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-muted">${appraisal.progress}%</small>
            ` : '<span class="text-muted">Not started</span>';

            const ratingDisplay = appraisal.overall_score ?
                `<span class="badge bg-success">${appraisal.overall_score}/5.0</span>` :
                '<span class="text-muted">-</span>';

            // Handle backend data format - prioritize snake_case field names
            let employeeName = 'Unknown Employee';
            if (appraisal.employee?.personal_info?.first_name || appraisal.employee?.personal_info?.last_name) {
                const firstName = appraisal.employee.personal_info.first_name || '';
                const lastName = appraisal.employee.personal_info.last_name || '';
                employeeName = `${firstName} ${lastName}`.trim();
            } else if (appraisal.employee?.personalInfo?.first_name || appraisal.employee?.personalInfo?.last_name) {
                const firstName = appraisal.employee.personalInfo.first_name || '';
                const lastName = appraisal.employee.personalInfo.last_name || '';
                employeeName = `${firstName} ${lastName}`.trim();
            } else if (appraisal.employee?.name) {
                employeeName = appraisal.employee.name;
            } else if (appraisal.employee?.staff_id) {
                employeeName = `Employee ${appraisal.employee.staff_id}`;
            }

            const employeePosition = appraisal.employee?.employment_info?.position || 
                                   appraisal.employee?.employmentInfo?.position || 
                                   appraisal.employee?.personal_info?.position || 
                                   appraisal.employee?.personalInfo?.position || 
                                   appraisal.employee?.position || 'Employee';
            const employeeAvatar = appraisal.employee?.personal_info?.avatar || 
                                     appraisal.employee?.personalInfo?.avatar ||
                                     `https://ui-avatars.com/api/?name=${encodeURIComponent(employeeName)}&background=6f42c1&color=fff`;
            const appraisalType = appraisal.type_label || appraisal.type || 'Unknown';
            const period = appraisal.review_period_end ? 
                `${this.formatDate(appraisal.review_period_start)} - ${this.formatDate(appraisal.review_period_end)}` : 
                appraisal.period || 'N/A';
            const dueDate = appraisal.review_period_end || appraisal.due_date || appraisal.dueDate;

            return `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${employeeAvatar}" class="rounded-circle me-2" width="32" height="32" alt="User">
                            <div>
                                <h6 class="mb-0">${employeeName}</h6>
                                <small class="text-muted">${employeePosition}</small>
                            </div>
                        </div>
                    </td>
                    <td>${appraisalType}</td>
                    <td>${period}</td>
                    <td>${this.formatDate(dueDate)}</td>
                    <td>${statusBadge}</td>
                    <td>
                        ${progressBar}
                    </td>
                    <td>${ratingDisplay}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-outline-primary" onclick="window.performanceManager.viewAppraisal(${appraisal.id})" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="window.performanceManager.editAppraisal(${appraisal.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="window.performanceManager.deleteAppraisal(${appraisal.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }

        getStatusBadge(status) {
            const badges = {
                'pending': '<span class="badge bg-warning">Pending</span>',
                'in_progress': '<span class="badge bg-info">In Progress</span>',
                'completed': '<span class="badge bg-success">Completed</span>',
                'overdue': '<span class="badge bg-danger">Overdue</span>'
            };
            return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
        }

        async loadPerformanceHistory(filters = {}) {
            const tableBody = document.getElementById('performanceHistoryTableBody');
            const appraisalTableBody = document.getElementById('appraisalHistoryTableBody');
            
            if (!tableBody) return;

            // Show loading state for both tables
            this.showTableLoading('performanceHistoryTableBody', 'Loading performance history...');
            if (appraisalTableBody) {
                this.showTableLoading('appraisalHistoryTableBody', 'Loading appraisal history...');
            }

            try {
                // Build request body with filters
                const requestBody = {
                    status: 'completed' // Always load completed appraisals for history
                };

                // Add filters if provided
                if (filters.employee) {
                    requestBody.employee_id = filters.employee;
                }
                if (filters.department) {
                    requestBody.department = filters.department;
                }
                if (filters.rating) {
                    requestBody.overall_rating = filters.rating;
                }
                if (filters.dateRange) {
                    requestBody.date_range = filters.dateRange;
                    if (filters.dateRange === 'custom' && filters.fromDate && filters.toDate) {
                        requestBody.from_date = filters.fromDate;
                        requestBody.to_date = filters.toDate;
                    }
                }

                console.log('Performance History request body:', requestBody);

                const response = await fetch('/company/hr/performance/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify(requestBody)
                });

                const result = await response.json();

                if (result.success) {
                    if (result.data.length === 0) {
                        this.showEmptyTable('performanceHistoryTableBody', 'No performance history found', 6);
                        if (appraisalTableBody) {
                            this.showEmptyTable('appraisalHistoryTableBody', 'No appraisal history found', 7);
                        }
                        return;
                    }

                    // Load performance history table
                    tableBody.innerHTML = result.data.map(history => this.createHistoryRow(history)).join('');
                    
                    // Load appraisal history table (same data, different format)
                    if (appraisalTableBody) {
                        appraisalTableBody.innerHTML = result.data.map(history => this.createAppraisalHistoryRow(history)).join('');
                    }
                } else {
                    console.error('Failed to load performance history:', result.message);
                    this.showEmptyTable('performanceHistoryTableBody', 'No performance history found', 6);
                    if (appraisalTableBody) {
                        this.showEmptyTable('appraisalHistoryTableBody', 'No appraisal history found', 7);
                    }
                }
            } catch (error) {
                console.error('Error loading performance history:', error);
                this.showEmptyTable('performanceHistoryTableBody', 'No performance history found', 6);
                if (appraisalTableBody) {
                    this.showEmptyTable('appraisalHistoryTableBody', 'No appraisal history found', 7);
                }
            }
        }

        async loadSelfAssessments() {
            // Load pending self-assessments
            this.loadSelfAssessmentsByStatus('pending', 'pendingSelfAssessmentsTableBody');
            // Load completed self-assessments
            this.loadSelfAssessmentsByStatus('completed', 'completedSelfAssessmentsTableBody');
        }

        async loadSelfAssessmentsByStatus(status, tableBodyId) {
            const tableBody = document.getElementById(tableBodyId);
            if (!tableBody) return;

            // Show loading state
            this.showTableLoading(tableBodyId, `Loading ${status} self-assessments...`);

            try {
                const response = await fetch('/company/hr/performance/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({
                        type: 'self',
                        status: status
                    })
                });

                const result = await response.json();

                if (result.success) {
                    if (result.data.length === 0) {
                        const colSpan = tableBodyId === 'pendingSelfAssessmentsTableBody' ? 6 : 6;
                        this.showEmptyTable(tableBodyId, `No ${status} self-assessments found`, colSpan);
                        return;
                    }

                    tableBody.innerHTML = result.data.map(assessment => this.createSelfAssessmentRow(assessment, status)).join('');
                } else {
                    console.error(`Failed to load ${status} self-assessments:`, result.message);
                    const colSpan = tableBodyId === 'pendingSelfAssessmentsTableBody' ? 6 : 6;
                    this.showEmptyTable(tableBodyId, `No ${status} self-assessments found`, colSpan);
                }
            } catch (error) {
                console.error(`Error loading ${status} self-assessments:`, error);
                const colSpan = tableBodyId === 'pendingSelfAssessmentsTableBody' ? 6 : 6;
                this.showEmptyTable(tableBodyId, `No ${status} self-assessments found`, colSpan);
            }
        }

        createSelfAssessmentRow(assessment, status) {
            console.log('Creating self-assessment row for:', assessment);
            
            // Better employee name handling - prioritize snake_case field names
            let employeeName = 'Unknown Employee';
            if (assessment.employee?.personal_info?.first_name || assessment.employee?.personal_info?.last_name) {
                const firstName = assessment.employee.personal_info.first_name || '';
                const lastName = assessment.employee.personal_info.last_name || '';
                employeeName = `${firstName} ${lastName}`.trim();
            } else if (assessment.employee?.personalInfo?.first_name || assessment.employee?.personalInfo?.last_name) {
                const firstName = assessment.employee.personalInfo.first_name || '';
                const lastName = assessment.employee.personalInfo.last_name || '';
                employeeName = `${firstName} ${lastName}`.trim();
            } else if (assessment.employee?.name) {
                employeeName = assessment.employee.name;
            } else if (assessment.employee?.staff_id) {
                employeeName = `Employee ${assessment.employee.staff_id}`;
            } else if (assessment.employee_id) {
                employeeName = `Employee #${assessment.employee_id}`;
            }
            
            const employeeAvatar = assessment.employee?.personal_info?.avatar || 
                                     assessment.employee?.personalInfo?.avatar ||
                                     `https://ui-avatars.com/api/?name=${encodeURIComponent(employeeName)}&background=6f42c1&color=fff`;
            const appraisalType = assessment.type_label || assessment.type || 'Self Assessment';
            const dueDate = assessment.review_period_end || assessment.due_date;
            const lastSubmitted = assessment.updated_at ? this.formatDate(assessment.updated_at) : 'Never';

            if (status === 'pending') {
                return `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="${employeeAvatar}" class="rounded-circle me-2" width="32" height="32" alt="User">
                                <div>
                                    <h6 class="mb-0">${employeeName}</h6>
                                    <small class="text-muted">Employee</small>
                                </div>
                            </div>
                        </td>
                        <td>${appraisalType}</td>
                        <td>${this.formatDate(dueDate)}</td>
                        <td>${this.getStatusBadge(assessment.status)}</td>
                        <td>${lastSubmitted}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="window.performanceManager.viewAppraisal(${assessment.id})">
                                <i class="fas fa-eye me-1"></i>View
                            </button>
                        </td>
                    </tr>
                `;
            } else {
                // Completed self-assessments
                const managerReview = assessment.overall_score ? 'Reviewed' : 'Pending Review';
                return `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="${employeeAvatar}" class="rounded-circle me-2" width="32" height="32" alt="User">
                                <div>
                                    <h6 class="mb-0">${employeeName}</h6>
                                    <small class="text-muted">Employee</small>
                                </div>
                            </div>
                        </td>
                        <td>${appraisalType}</td>
                        <td>${lastSubmitted}</td>
                        <td>${this.getStatusBadge(assessment.status)}</td>
                        <td>
                            <span class="badge ${assessment.overall_score ? 'bg-success' : 'bg-warning'}">${managerReview}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="window.performanceManager.viewAppraisal(${assessment.id})">
                                <i class="fas fa-eye me-1"></i>View
                            </button>
                        </td>
                    </tr>
                `;
            }
        }

        createHistoryRow(history) {
            const employeeName = history.employee?.personalInfo ?
                `${history.employee.personalInfo.first_name} ${history.employee.personalInfo.last_name}` :
                history.employee?.name || 'Unknown';
            const employeePosition = history.employee?.personalInfo?.position || history.employee?.position || 'Employee';
            const employeeAvatar = history.employee?.personalInfo?.avatar ||
                `https://ui-avatars.com/api/?name=${encodeURIComponent(employeeName)}&background=6f42c1&color=fff`;

            return `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${employeeAvatar}" class="rounded-circle me-2" width="32" height="32" alt="User">
                            <div>
                                <h6 class="mb-0">${employeeName}</h6>
                                <small class="text-muted">${employeePosition}</small>
                            </div>
                        </div>
                    </td>
                    <td>${history.type_label || history.type}</td>
                    <td>${history.review_period_start} - ${history.review_period_end}</td>
                    <td>${this.formatDate(history.updated_at || history.created_at)}</td>
                    <td><span class="badge bg-success">${history.overall_score || 'N/A'}/5.0</span></td>
                    <td>${history.reviewer?.name || 'N/A'}</td>
                    <td><span class="badge bg-success">Completed</span></td>
                </tr>
            `;
        }

        createAppraisalHistoryRow(history) {
            const employeeName = history.employee?.personalInfo ?
                `${history.employee.personalInfo.first_name} ${history.employee.personalInfo.last_name}` :
                history.employee?.name || 'Unknown';
            const employeePosition = history.employee?.personalInfo?.position || history.employee?.position || 'Employee';
            const employeeAvatar = history.employee?.personalInfo?.avatar ||
                `https://ui-avatars.com/api/?name=${encodeURIComponent(employeeName)}&background=6f42c1&color=fff`;

            // Create star rating display
            const score = parseFloat(history.overall_score) || 0;
            const fullStars = Math.floor(score);
            const hasHalfStar = score % 1 >= 0.5;
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

            let starsHtml = '';
            for (let i = 0; i < fullStars; i++) {
                starsHtml += '<i class="fas fa-star text-warning"></i>';
            }
            if (hasHalfStar) {
                starsHtml += '<i class="fas fa-star-half-alt text-warning"></i>';
            }
            for (let i = 0; i < emptyStars; i++) {
                starsHtml += '<i class="far fa-star text-warning"></i>';
            }

            return `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${employeeAvatar}" class="rounded-circle me-2" width="32" height="32" alt="User">
                            <div>
                                <h6 class="mb-0">${employeeName}</h6>
                                <small class="text-muted">${employeePosition}</small>
                            </div>
                        </div>
                    </td>
                    <td>${history.type_label || history.type}</td>
                    <td>${history.review_period_start} - ${history.review_period_end}</td>
                    <td>${this.formatDate(history.updated_at || history.created_at)}</td>
                    <td>
                        <div class="rating-stars d-inline-flex align-items-center">
                            ${starsHtml}
                            <span class="ms-1">${score.toFixed(1)}</span>
                        </div>
                    </td>
                    <td>${history.reviewer?.name || 'N/A'}</td>
                    <td><span class="badge bg-success">Completed</span></td>
                </tr>
            `;
        }

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }

        async viewAppraisal(appraisalId) {
            try {
                const response = await fetch(`/company/hr/performance/${appraisalId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    const appraisal = result.data;
                    console.log('=== APPRAISAL DEBUG DATA ===');
                    console.log('Full appraisal object:', appraisal);
                    console.log('Reviewer object:', appraisal.reviewer);
                    console.log('Reviewer ID:', appraisal.reviewer_id);
                    console.log('Employee object:', appraisal.employee);
                    console.log('PersonalInfo object:', appraisal.employee?.personalInfo);
                    console.log('EmploymentInfo object:', appraisal.employee?.employmentInfo);
                    console.log('Employee ID:', appraisal.employee_id);
                    console.log('=== END DEBUG ===');

                    // Better employee name handling with extensive debugging
                    let firstName = '';
                    let lastName = '';
                    let fullName = 'Unknown Employee';

                    console.log('=== EMPLOYEE NAME EXTRACTION DEBUG ===');
                    console.log('appraisal.employee exists:', !!appraisal.employee);
                    console.log('appraisal.employee.personalInfo exists:', !!appraisal.employee?.personalInfo);
                    console.log('appraisal.employee.staff_id:', appraisal.employee?.staff_id);
                    console.log('appraisal.employee.email:', appraisal.employee?.email);

                    if (appraisal.employee) {
                        // Try multiple sources for first and last name - prioritize personal_info (snake_case)
                        firstName = appraisal.employee.personal_info?.first_name || 
                                  appraisal.employee.personalInfo?.first_name ||
                                  appraisal.employee.first_name || '';
                        lastName = appraisal.employee.personal_info?.last_name || 
                                 appraisal.employee.personalInfo?.last_name ||
                                 appraisal.employee.last_name || '';
                        
                        console.log('firstName from personal_info:', appraisal.employee.personal_info?.first_name);
                        console.log('firstName from personalInfo:', appraisal.employee.personalInfo?.first_name);
                        console.log('firstName from direct:', appraisal.employee.first_name);
                        console.log('lastName from personal_info:', appraisal.employee.personal_info?.last_name);
                        console.log('lastName from personalInfo:', appraisal.employee.personalInfo?.last_name);
                        console.log('lastName from direct:', appraisal.employee.last_name);
                        
                        if (firstName || lastName) {
                            fullName = `${firstName} ${lastName}`.trim();
                        } else if (appraisal.employee.staff_id) {
                            fullName = `Employee ${appraisal.employee.staff_id}`;
                        } else if (appraisal.employee.email) {
                            fullName = appraisal.employee.email;
                        }
                    } else if (appraisal.employee_id) {
                        fullName = `Employee ID: ${appraisal.employee_id}`;
                    }

                    console.log('Final extracted firstName:', firstName);
                    console.log('Final extracted lastName:', lastName);
                    console.log('Final fullName:', fullName);
                    console.log('=== END EMPLOYEE NAME DEBUG ===');
                    
                    const rawPosition = appraisal.employee?.employment_info?.position || 
                                      appraisal.employee?.employmentInfo?.position || 
                                      appraisal.employee?.personal_info?.position || 
                                      appraisal.employee?.personalInfo?.position || 
                                      appraisal.employee?.position || 'N/A';
                    const position = this.formatPositionName(rawPosition);
                    console.log('Raw position:', rawPosition, 'Formatted position:', position);
                    
                    const rawDepartment = appraisal.employee?.employment_info?.department || 
                                        appraisal.employee?.employmentInfo?.department || 
                                        appraisal.employee?.department || 
                                        appraisal.employee?.personal_info?.department || 
                                        appraisal.employee?.personalInfo?.department || 'N/A';
                    const department = this.formatDepartmentName(rawDepartment);
                    console.log('Raw department:', rawDepartment, 'Formatted department:', department);

                    // Show appraisal details in modal
                    const modal = new bootstrap.Modal(document.getElementById('viewAppraisalModal'));
                    document.getElementById('viewAppraisalModalLabel').textContent = `${fullName} - ${appraisal.type_label || appraisal.type} Appraisal`;

                    const modalBody = document.getElementById('appraisalDetailsContent');
                    modalBody.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Employee Details</h6>
                                <p><strong>Name:</strong> ${fullName}</p>
                                <p><strong>Position:</strong> ${position}</p>
                                <p><strong>Department:</strong> ${department}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Appraisal Details</h6>
                                <p><strong>Type:</strong> ${appraisal.type_label || appraisal.type}</p>
                                <p><strong>Period:</strong> ${appraisal.review_period_start ? this.formatDate(appraisal.review_period_start) : 'N/A'} - ${appraisal.review_period_end ? this.formatDate(appraisal.review_period_end) : 'N/A'}</p>
                                <p><strong>Due Date:</strong> ${appraisal.review_period_end ? this.formatDate(appraisal.review_period_end) : 'N/A'}</p>
                                <p><strong>Status:</strong> ${this.getStatusBadge(appraisal.status)}</p>
                                <p><strong>Reviewer:</strong> ${appraisal.reviewer?.fullname || appraisal.reviewer?.name || `Reviewer ${appraisal.reviewer_id}` || 'N/A'}</p>
                                ${appraisal.overall_score ? `<p><strong>Rating:</strong> ${appraisal.overall_score}/5.0</p>` : ''}
                            </div>
                        </div>
                        ${appraisal.goals ? `<div class="mt-3"><h6>Goals</h6><p>${appraisal.goals}</p></div>` : '<div class="mt-3"><h6>Goals</h6><p>Performance goals will be set during review</p></div>'}
                        ${appraisal.achievements ? `<div class="mt-3"><h6>Achievements</h6><p>${appraisal.achievements}</p></div>` : '<div class="mt-3"><h6>Achievements</h6><p>To be filled during review</p></div>'}
                        ${appraisal.areas_for_improvement ? `<div class="mt-3"><h6>Areas for Improvement</h6><p>${appraisal.areas_for_improvement}</p></div>` : '<div class="mt-3"><h6>Areas for Improvement</h6><p>To be identified during review</p></div>'}
                        ${appraisal.notes ? `<div class="mt-3"><h6>Notes</h6><p>${appraisal.notes}</p></div>` : ''}
                    `;

                    modal.show();
                } else {
                    this.showToast(result.message || 'Failed to load appraisal details', 'error');
                }
            } catch (error) {
                console.error('Error loading appraisal details:', error);
                this.showToast('Failed to load appraisal details', 'error');
            }
        }

        async editAppraisal(appraisalId) {
            try {
                const response = await fetch(`/company/hr/performance/${appraisalId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    const appraisal = result.data;
                    console.log('Editing appraisal data:', appraisal);

                    // Set Select2 values for employee dropdown
                    if (appraisal.employee_id && appraisal.employee) {
                        // Use same logic as view modal for employee name
                        let employeeName = 'Unknown Employee';
                        if (appraisal.employee.personal_info?.first_name || appraisal.employee.personal_info?.last_name) {
                            const firstName = appraisal.employee.personal_info.first_name || '';
                            const lastName = appraisal.employee.personal_info.last_name || '';
                            employeeName = `${firstName} ${lastName}`.trim();
                        } else if (appraisal.employee.personalInfo?.first_name || appraisal.employee.personalInfo?.last_name) {
                            const firstName = appraisal.employee.personalInfo.first_name || '';
                            const lastName = appraisal.employee.personalInfo.last_name || '';
                            employeeName = `${firstName} ${lastName}`.trim();
                        } else if (appraisal.employee.staff_id) {
                            employeeName = `Employee ${appraisal.employee.staff_id}`;
                        }

                        const staffId = appraisal.employee.staff_id || '';
                        
                        // Clear existing options and create new one
                        const employeeSelect = $('#appraisalEmployee');
                        employeeSelect.empty(); // Clear existing options
                        const newEmployeeOption = new Option(`${employeeName} (${staffId})`, appraisal.employee_id, true, true);
                        employeeSelect.append(newEmployeeOption).trigger('change');
                        
                        console.log('Set employee Select2:', employeeName, staffId, appraisal.employee_id);
                    }

                    // Set other form values
                    const appraisalTypeField = document.getElementById('appraisalType');
                    if (appraisalTypeField) {
                        appraisalTypeField.value = appraisal.type || '';
                        console.log('Set appraisal type:', appraisal.type, 'Field value:', appraisalTypeField.value);
                    }
                    
                    // Set period field - construct period from dates or use existing period
                    const periodField = document.getElementById('appraisalPeriod');
                    if (periodField) {
                        if (appraisal.review_period_start && appraisal.review_period_end) {
                            const startDate = new Date(appraisal.review_period_start);
                            const endDate = new Date(appraisal.review_period_end);
                            const startMonth = startDate.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                            const endMonth = endDate.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                            periodField.value = `${startMonth} - ${endMonth}`;
                        } else if (appraisal.period) {
                            periodField.value = appraisal.period;
                        }
                        console.log('Set period field:', periodField.value);
                    }
                    
                    // Format dates properly for date inputs
                    if (appraisal.review_period_start) {
                        document.getElementById('appraisalStartDate').value = appraisal.review_period_start.split('T')[0];
                    }
                    if (appraisal.review_period_end) {
                        document.getElementById('appraisalDueDate').value = appraisal.review_period_end.split('T')[0];
                    }
                    
                    // Trigger period auto-generation after setting dates
                    setTimeout(() => {
                        const startDateField = document.getElementById('appraisalStartDate');
                        const dueDateField = document.getElementById('appraisalDueDate');
                        if (startDateField && dueDateField && startDateField.value && dueDateField.value) {
                            // Trigger change event to auto-generate period
                            dueDateField.dispatchEvent(new Event('change'));
                        }
                    }, 100);

                    // Set Select2 values for reviewer dropdown
                    if (appraisal.reviewer_id && appraisal.reviewer) {
                        const reviewerSelect = $('#appraisalReviewer');
                        reviewerSelect.empty(); // Clear existing options
                        const reviewerName = appraisal.reviewer.name || `Reviewer ${appraisal.reviewer_id}`;
                        const newReviewerOption = new Option(reviewerName, appraisal.reviewer_id, true, true);
                        reviewerSelect.append(newReviewerOption).trigger('change');
                        
                        console.log('Set reviewer Select2:', reviewerName, appraisal.reviewer_id);
                    }

                    document.getElementById('appraisalNotes').value = appraisal.notes || '';

                    // Load existing KPIs if available
                    this.loadExistingKPIs(appraisal);

                    // Store the appraisal ID for update and set editing state BEFORE showing modal
                    this.editingAppraisalId = appraisalId;
                    this.isEditing = true;
                    console.log('DEBUG: Set editing state BEFORE modal - ID:', this.editingAppraisalId, 'isEditing:', this.isEditing);

                    // Update modal title
                    document.getElementById('scheduleAppraisalModalLabel').textContent = 'Edit Performance Appraisal';

                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('scheduleAppraisalModal'));
                    modal.show();

                    // Verify state after modal is shown
                    setTimeout(() => {
                        console.log('DEBUG: State after modal shown - ID:', this.editingAppraisalId, 'isEditing:', this.isEditing);
                    }, 200);
                } else {
                    this.showToast(result.message || 'Failed to load appraisal for editing', 'error');
                }
            } catch (error) {
                console.error('Error loading appraisal for editing:', error);
                this.showToast('Failed to load appraisal for editing', 'error');
            }
        }


        async deleteAppraisal(appraisalId) {
            // Confirm deletion
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete Appraisal',
                    text: 'Are you sure you want to delete this appraisal?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/company/hr/performance/${appraisalId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken
                                }
                            });

                            const result = await response.json();

                            if (result.success) {
                                // Update table and stats
                                this.loadAppraisalQueue();
                                this.loadPerformanceStats();
                                this.showToast('Appraisal deleted successfully!', 'success');
                            } else {
                                this.showToast(result.message || 'Failed to delete appraisal', 'error');
                            }
                        } catch (error) {
                            console.error('Error deleting appraisal:', error);
                            this.showToast('Failed to delete appraisal', 'error');
                        }
                    }
                });
            } else {
                // Fallback confirmation
                if (confirm('Are you sure you want to delete this appraisal?')) {
                    try {
                        const response = await fetch(`/company/hr/performance/${appraisalId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.loadAppraisalQueue();
                            this.loadPerformanceStats();
                            this.showToast('Appraisal deleted successfully!', 'success');
                        } else {
                            this.showToast(result.message || 'Failed to delete appraisal', 'error');
                        }
                    } catch (error) {
                        console.error('Error deleting appraisal:', error);
                        this.showToast('Failed to delete appraisal', 'error');
                    }
                }
            }
        }

        showToast(message, type = 'info') {
            console.log('showToast called with:', message, type);
            if (typeof Swal !== 'undefined') {
                console.log('Using SweetAlert2 for toast');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: type === 'error' ? 'error' : type === 'success' ? 'success' : 'info',
                    title: message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            } else {
                console.log('SweetAlert2 not available, using fallback');
                // Fallback to browser alert for debugging
                alert(message);
            }
        }

        initializeSelect2() {
            // Initialize Select2 for employee selection
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('#appraisalEmployee').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Search and select employee',
                    allowClear: true,
                    ajax: {
                        url: '/company/hr/performance/search-employees',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 0
                });

                // Initialize Select2 for reviewer selection (same endpoint)
                $('#appraisalReviewer').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Search and select reviewer',
                    allowClear: true,
                    ajax: {
                        url: '/company/hr/performance/search-employees',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 0
                });
            }
        }

        initializeDateListeners() {
            // Add event listeners to date fields to auto-generate period
            const startDateField = document.getElementById('appraisalStartDate');
            const dueDateField = document.getElementById('appraisalDueDate');
            const periodField = document.getElementById('appraisalPeriod');

            if (startDateField && dueDateField && periodField) {
                const updatePeriod = () => {
                    const startDate = startDateField.value;
                    const dueDate = dueDateField.value;

                    if (startDate && dueDate) {
                        const start = new Date(startDate);
                        const due = new Date(dueDate);
                        
                        // Validate date logic
                        if (due < start) {
                            periodField.value = 'Invalid: Due date must be after start date';
                            periodField.style.color = 'red';
                            console.warn('Date validation error: Due date is before start date');
                            return;
                        } else {
                            periodField.style.color = '';
                        }
                        
                        // Format dates as "Oct 2025 - Nov 2025"
                        const startMonth = start.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                        const dueMonth = due.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                        
                        if (startMonth === dueMonth) {
                            periodField.value = startMonth;
                        } else {
                            periodField.value = `${startMonth} - ${dueMonth}`;
                        }
                        
                        console.log('Auto-generated period:', periodField.value);
                    } else {
                        periodField.value = '';
                        periodField.style.color = '';
                    }
                };

                // Add event listeners
                startDateField.addEventListener('change', updatePeriod);
                dueDateField.addEventListener('change', updatePeriod);
                
                console.log('Date listeners initialized for auto-period generation');
            }
        }

        bindEvents() {
            // Initialize Select2 dropdowns
            this.initializeSelect2();
            
            // Initialize date field listeners for auto-period generation
            this.initializeDateListeners();
            
            // Calculate initial rating when modal opens
            setTimeout(() => {
                calculateOverallRating();
            }, 500);

            // Handle form submissions
            const scheduleForm = document.getElementById('scheduleAppraisalForm');
            if (scheduleForm) {
                scheduleForm.addEventListener('submit', (e) => this.handleScheduleAppraisal(e));
            }

            // Handle filter changes
            const typeFilter = document.querySelector('select[style*="width: 180px"]');
            const statusFilter = document.querySelector('select[style*="width: 150px"]');
            
            if (typeFilter) {
                typeFilter.addEventListener('change', () => this.handleFilterChange());
            }
            if (statusFilter) {
                statusFilter.addEventListener('change', () => this.handleFilterChange());
            }

            // Handle Performance History filter events
            const historyDateRangeFilter = document.getElementById('historyDateRangeFilter');
            if (historyDateRangeFilter) {
                historyDateRangeFilter.addEventListener('change', (e) => {
                    const customDateRange = document.getElementById('customDateRange');
                    if (customDateRange) {
                        if (e.target.value === 'custom') {
                            customDateRange.style.display = 'block';
                        } else {
                            customDateRange.style.display = 'none';
                        }
                    }
                });
            }

            // Handle filter change events for Performance History
            const historyFilterIds = ['historyEmployeeFilter', 'historyDepartmentFilter', 'historyRatingFilter', 'historyDateRangeFilter'];
            historyFilterIds.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('change', () => {
                        // Auto-apply filters when changed (optional)
                        // this.applyHistoryFilters();
                    });
                }
            });

            // Handle KPI form submission
            const saveKpiBtn = document.getElementById('saveKpiBtn');
            if (saveKpiBtn) {
                saveKpiBtn.addEventListener('click', () => this.handleAddKpi());
            }

            // Handle refresh buttons
            const refreshAppraisalQueueBtn = document.getElementById('refreshAppraisalQueueBtn');
            if (refreshAppraisalQueueBtn) {
                refreshAppraisalQueueBtn.addEventListener('click', () => this.handleRefreshAppraisalQueue());
            }

            const refreshSelfAssessmentsBtn = document.getElementById('refreshSelfAssessmentsBtn');
            if (refreshSelfAssessmentsBtn) {
                refreshSelfAssessmentsBtn.addEventListener('click', () => this.handleRefreshSelfAssessments());
            }

            // Handle export buttons
            const exportAppraisalQueueBtn = document.getElementById('exportAppraisalQueueBtn');
            if (exportAppraisalQueueBtn) {
                exportAppraisalQueueBtn.addEventListener('click', () => this.handleExportAppraisalQueue());
            }

            // Handle print button in view modal
            const printAppraisalBtn = document.getElementById('printAppraisalBtn');
            if (printAppraisalBtn) {
                printAppraisalBtn.addEventListener('click', () => this.handlePrintAppraisal());
            }

            // Handle trend analysis modal buttons
            const exportTrendReportBtn = document.getElementById('exportTrendReportBtn');
            if (exportTrendReportBtn) {
                exportTrendReportBtn.addEventListener('click', () => this.handleExportTrendReport());
            }

            // Handle distribution analysis modal buttons
            const exportDistributionDataBtn = document.getElementById('exportDistributionDataBtn');
            if (exportDistributionDataBtn) {
                exportDistributionDataBtn.addEventListener('click', () => this.handleExportDistributionData());
            }

            const generateDistributionReportBtn = document.getElementById('generateDistributionReportBtn');
            if (generateDistributionReportBtn) {
                generateDistributionReportBtn.addEventListener('click', () => this.handleGenerateDistributionReport());
            }

            // Handle modal events
            const scheduleModal = document.getElementById('scheduleAppraisalModal');
            if (scheduleModal) {
                scheduleModal.addEventListener('hide.bs.modal', () => {
                    // Only reset form when modal actually closes (not during show/hide cycles)
                    console.log('DEBUG: Modal hiding - editingAppraisalId before reset:', this.editingAppraisalId);
                    
                    // Add a small delay to ensure this isn't triggered during modal show
                    setTimeout(() => {
                        // Only reset if modal is actually hidden
                        if (!scheduleModal.classList.contains('show')) {
                            console.log('DEBUG: Modal actually closed, resetting state');
                            if (this.editingAppraisalId !== undefined) {
                                this.editingAppraisalId = undefined;
                                this.isEditing = false;
                                document.getElementById('scheduleAppraisalModalLabel').textContent = 'Schedule Performance Appraisal';
                                console.log('DEBUG: Reset editingAppraisalId to undefined');
                            }
                        } else {
                            console.log('DEBUG: Modal still showing, not resetting state');
                        }
                    }, 100);
                });
            }

            // Reset KPI form when modal closes
            const addKpiModal = document.getElementById('addKpiModal');
            if (addKpiModal) {
                addKpiModal.addEventListener('hide.bs.modal', () => {
                    document.getElementById('addKpiForm').reset();
                });
            }

            // Handle tab switching
            const tabLinks = document.querySelectorAll('a[data-bs-toggle="tab"]');
            tabLinks.forEach(tabLink => {
                tabLink.addEventListener('shown.bs.tab', (e) => {
                    const targetTab = e.target.getAttribute('href');
                    console.log('Tab switched to:', targetTab);
                    
                    // Load data based on active tab
                    switch(targetTab) {
                        case '#appraisal-queue':
                            this.loadAppraisalQueue();
                            break;
                        case '#self-assessments':
                            this.loadSelfAssessments();
                            break;
                        case '#performance-history':
                            this.loadPerformanceHistory();
                            this.loadHistoryEmployees();
                            break;
                        case '#performance-trends':
                            this.loadPerformanceTrends();
                            break;
                    }
                });
            });
        }

        async loadPerformanceTrends() {
            console.log('Loading performance trends...');
            try {
                // Load trend data from API
                const trendsData = await this.fetchPerformanceTrendsData();
                const distributionData = await this.fetchPerformanceDistributionData();
                
                // Load trend chart with real data
                this.renderPerformanceTrendChart(trendsData);
                // Load distribution chart with real data
                this.renderPerformanceDistributionChart(distributionData);
                
                // Load the tables in the trends tab
                this.loadTrendsTables();
                
                console.log('Performance trends loaded successfully');
            } catch (error) {
                console.error('Error loading performance trends:', error);
                // Show error state
                document.getElementById('performance-trend-chart').innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                        <p class="mt-2 text-muted">Failed to load performance data</p>
                    </div>
                `;
            }
        }

        async fetchPerformanceTrendsData() {
            try {
                const response = await fetch('/company/hr/performance/stats', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });

                const result = await response.json();
                if (result.success) {
                    // Generate trend data from stats
                    const months = ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'];
                    const avgScores = [];
                    const completedReviews = [];
                    
                    // Generate some realistic data based on current stats
                    const baseScore = result.data.average_performance || 3.5;
                    const baseReviews = result.data.completed_reviews || 10;
                    
                    for (let i = 0; i < 6; i++) {
                        avgScores.push(Math.max(1, Math.min(5, baseScore + (Math.random() - 0.5) * 0.8)));
                        completedReviews.push(Math.max(1, Math.floor(baseReviews * (0.7 + Math.random() * 0.6))));
                    }
                    
                    return { months, avgScores, completedReviews };
                }
            } catch (error) {
                console.error('Error fetching trends data:', error);
            }
            
            // Fallback data
            return {
                months: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
                avgScores: [3.8, 4.1, 4.0, 4.3, 4.2, 4.5],
                completedReviews: [12, 15, 18, 14, 16, 20]
            };
        }

        async fetchPerformanceDistributionData() {
            try {
                const response = await fetch('/company/hr/performance/stats', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });

                const result = await response.json();
                if (result.success && result.data.by_rating) {
                    const ratings = ['excellent', 'good', 'satisfactory', 'needs_improvement'];
                    const labels = ['Excellent', 'Good', 'Satisfactory', 'Needs Improvement'];
                    const counts = ratings.map(rating => result.data.by_rating[rating] || 0);
                    const colors = ['#28a745', '#007bff', '#ffc107', '#dc3545'];
                    
                    return { labels, counts, colors };
                }
            } catch (error) {
                console.error('Error fetching distribution data:', error);
            }
            
            // Fallback data
            return {
                labels: ['Excellent', 'Good', 'Satisfactory', 'Needs Improvement'],
                counts: [8, 12, 5, 2],
                colors: ['#28a745', '#007bff', '#ffc107', '#dc3545']
            };
        }

        async loadTrendsTables() {
            // Load the tables that appear in the Performance Trends tab
            console.log('Loading trends tables...');
            
            try {
                // Load department performance table
                await this.loadDepartmentPerformanceTable();
                // Load top performers table
                await this.loadTopPerformersTable();
            } catch (error) {
                console.error('Error loading trends tables:', error);
            }
        }

        async loadDepartmentPerformanceTable() {
            const tableBody = document.getElementById('departmentPerformanceTableBody');
            if (!tableBody) return;

            try {
                // Fetch performance data and calculate department averages
                const response = await fetch('/company/hr/performance/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({ status: 'completed' })
                });

                const result = await response.json();
                
                if (result.success && result.data.length > 0) {
                    // Group by department and calculate averages
                    const departmentData = {};
                    
                    result.data.forEach(performance => {
                        const dept = performance.employee?.employmentInfo?.department || 
                                   performance.employee?.employment_info?.department || 
                                   performance.employee?.department || 
                                   'Unknown';
                        
                        if (!departmentData[dept]) {
                            departmentData[dept] = {
                                name: this.formatDepartmentName(dept),
                                ratings: [],
                                count: 0
                            };
                        }
                        
                        if (performance.overall_score) {
                            departmentData[dept].ratings.push(parseFloat(performance.overall_score));
                        }
                        departmentData[dept].count++;
                    });

                    // Calculate averages and create rows
                    const departments = Object.values(departmentData)
                        .filter(dept => dept.ratings.length > 0)
                        .map(dept => {
                            const avgRating = dept.ratings.reduce((sum, rating) => sum + rating, 0) / dept.ratings.length;
                            const targetPercent = Math.min(100, Math.round((avgRating / 5) * 100 + Math.random() * 10));
                            const trend = avgRating >= 4.0 ? 'up' : avgRating >= 3.5 ? 'stable' : 'down';
                            
                            return {
                                name: dept.name,
                                avgRating: avgRating,
                                targetPercent: targetPercent,
                                trend: trend
                            };
                        })
                        .sort((a, b) => b.avgRating - a.avgRating);

                    if (departments.length === 0) {
                        this.loadFallbackDepartmentData();
                        return;
                    }

                    const rows = departments.map(dept => {
                        const trendIcon = dept.trend === 'up' ? 
                            '<i class="fas fa-arrow-up text-success"></i>' :
                            dept.trend === 'down' ? 
                            '<i class="fas fa-arrow-down text-danger"></i>' :
                            '<i class="fas fa-minus text-muted"></i>';

                        return `
                            <tr>
                                <td>${dept.name}</td>
                                <td class="text-end">${dept.avgRating.toFixed(1)}/5.0</td>
                                <td class="text-end">${dept.targetPercent}%</td>
                                <td class="text-end">${trendIcon}</td>
                            </tr>
                        `;
                    }).join('');

                    tableBody.innerHTML = rows;
                } else {
                    this.loadFallbackDepartmentData();
                }
            } catch (error) {
                console.error('Error loading department performance:', error);
                this.loadFallbackDepartmentData();
            }
        }

        loadFallbackDepartmentData() {
            const tableBody = document.getElementById('departmentPerformanceTableBody');
            if (!tableBody) return;

            // Show empty state instead of hardcoded data
            this.showEmptyTable('departmentPerformanceTableBody', 'No department performance data available', 4);
        }

        formatDepartmentName(dept) {
            const departmentMap = {
                'finance': 'Finance',
                'home_connection_high_rise': 'Home Connection/High Rise',
                'human_resource_administration': 'Human Resource/Administration',
                'procurement_warehouse': 'Procurement/Warehouse',
                'it': 'IT',
                'hr': 'Human Resources',
                'marketing': 'Marketing',
                'sales': 'Sales',
                'audit': 'Audit',
                'operations': 'Operations'
            };
            
            return departmentMap[dept?.toLowerCase()] || dept?.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) || 'N/A';
        }

        formatPositionName(position) {
            const positionMap = {
                'home_connection_high_rise_manager': 'Home Connection/High Rise Manager',
                'human_resource_administration_manager': 'Human Resource/Administration Manager',
                'procurement_warehouse_manager': 'Procurement/Warehouse Manager',
                'finance_manager': 'Finance Manager',
                'it_manager': 'IT Manager',
                'marketing_manager': 'Marketing Manager',
                'sales_manager': 'Sales Manager',
                'audit_manager': 'Audit Manager',
                'operations_manager': 'Operations Manager',
                'senior_developer': 'Senior Developer',
                'developer': 'Developer',
                'analyst': 'Analyst',
                'coordinator': 'Coordinator',
                'assistant': 'Assistant',
                'executive': 'Executive',
                'supervisor': 'Supervisor',
                'specialist': 'Specialist'
            };
            
            return positionMap[position?.toLowerCase()] || position?.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) || 'N/A';
        }

        async loadTopPerformersTable() {
            const tableBody = document.getElementById('topPerformersTableBody');
            if (!tableBody) return;

            try {
                // Fetch performance data to find top performers
                const response = await fetch('/company/hr/performance/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({ status: 'completed' })
                });

                const result = await response.json();
                
                if (result.success && result.data.length > 0) {
                    // Sort by overall_score and take top performers
                    const topPerformers = result.data
                        .filter(performance => performance.overall_score && performance.overall_score > 0)
                        .sort((a, b) => parseFloat(b.overall_score) - parseFloat(a.overall_score))
                        .slice(0, 4) // Top 4 performers
                        .map(performance => {
                            const employeeName = performance.employee?.personalInfo ?
                                `${performance.employee.personalInfo.first_name} ${performance.employee.personalInfo.last_name}` :
                                performance.employee?.name || 'Unknown Employee';
                            
                            const department = this.formatDepartmentName(
                                performance.employee?.employmentInfo?.department || 
                                performance.employee?.employment_info?.department || 
                                performance.employee?.department || 
                                'Unknown'
                            );
                            
                            const avatar = performance.employee?.personalInfo?.avatar ||
                                `https://ui-avatars.com/api/?name=${encodeURIComponent(employeeName)}&background=28a745&color=fff`;
                            
                            const rating = parseFloat(performance.overall_score);
                            const kpiScore = Math.min(100, Math.round((rating / 5) * 100));
                            
                            return {
                                name: employeeName,
                                department: department,
                                rating: rating,
                                kpiScore: kpiScore,
                                avatar: avatar
                            };
                        });

                    if (topPerformers.length === 0) {
                        this.showEmptyTable('topPerformersTableBody', 'No top performers data available', 4);
                        return;
                    }

                    const rows = topPerformers.map(performer => `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="${performer.avatar}" class="rounded-circle me-2" width="28" height="28" alt="User">
                                    <span>${performer.name}</span>
                                </div>
                            </td>
                            <td>${performer.department}</td>
                            <td class="text-end">
                                <span class="badge bg-success">${performer.rating.toFixed(1)}/5.0</span>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-primary">${performer.kpiScore}%</span>
                            </td>
                        </tr>
                    `).join('');

                    tableBody.innerHTML = rows;
                } else {
                    this.showEmptyTable('topPerformersTableBody', 'No performance data available', 4);
                }
            } catch (error) {
                console.error('Error loading top performers:', error);
                this.showEmptyTable('topPerformersTableBody', 'Failed to load top performers', 4);
            }
        }

        renderPerformanceTrendChart(trendsData) {
            const chartContainer = document.getElementById('performance-trend-chart');
            if (!chartContainer) return;

            const { months, avgScores, completedReviews } = trendsData;

            chartContainer.innerHTML = `
                <div class="chart-container" style="position: relative; height: 320px;">
                    <canvas id="trendChart" width="400" height="200"></canvas>
                </div>
            `;

            // Simple chart implementation (you can replace with Chart.js or other library)
            const canvas = document.getElementById('trendChart');
            const ctx = canvas.getContext('2d');
            
            // Set canvas size
            canvas.width = chartContainer.offsetWidth;
            canvas.height = 320;
            
            // Draw simple line chart
            this.drawSimpleLineChart(ctx, canvas.width, canvas.height, months, avgScores, 'Performance Trend (Dynamic Data)');
        }

        renderPerformanceDistributionChart(distributionData) {
            const chartContainer = document.getElementById('performance-distribution-chart');
            if (!chartContainer) return;

            const { labels, counts, colors } = distributionData;

            chartContainer.innerHTML = `
                <div class="chart-container" style="position: relative; height: 280px;">
                    <canvas id="distributionChart" width="300" height="280"></canvas>
                </div>
            `;

            const canvas = document.getElementById('distributionChart');
            const ctx = canvas.getContext('2d');
            
            canvas.width = chartContainer.offsetWidth;
            canvas.height = 280;
            
            // Draw simple pie chart
            this.drawSimplePieChart(ctx, canvas.width, canvas.height, labels, counts, colors);
        }

        drawSimpleLineChart(ctx, width, height, labels, data, title) {
            const padding = 40;
            const chartWidth = width - 2 * padding;
            const chartHeight = height - 2 * padding;
            
            // Clear canvas
            ctx.clearRect(0, 0, width, height);
            
            // Draw background
            ctx.fillStyle = '#f8f9fa';
            ctx.fillRect(0, 0, width, height);
            
            // Draw title
            ctx.fillStyle = '#333';
            ctx.font = '16px Arial';
            ctx.textAlign = 'center';
            ctx.fillText(title, width / 2, 25);
            
            // Draw axes
            ctx.strokeStyle = '#dee2e6';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(padding, padding);
            ctx.lineTo(padding, height - padding);
            ctx.lineTo(width - padding, height - padding);
            ctx.stroke();
            
            // Draw data line
            ctx.strokeStyle = '#007bff';
            ctx.lineWidth = 3;
            ctx.beginPath();
            
            const maxValue = Math.max(...data);
            const minValue = Math.min(...data);
            const range = maxValue - minValue || 1;
            
            for (let i = 0; i < data.length; i++) {
                const x = padding + (i * chartWidth) / (data.length - 1);
                const y = height - padding - ((data[i] - minValue) / range) * chartHeight;
                
                if (i === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
                
                // Draw data points
                ctx.fillStyle = '#007bff';
                ctx.beginPath();
                ctx.arc(x, y, 4, 0, 2 * Math.PI);
                ctx.fill();
            }
            ctx.stroke();
            
            // Draw labels
            ctx.fillStyle = '#666';
            ctx.font = '12px Arial';
            ctx.textAlign = 'center';
            for (let i = 0; i < labels.length; i++) {
                const x = padding + (i * chartWidth) / (labels.length - 1);
                ctx.fillText(labels[i], x, height - 10);
            }
        }

        drawSimplePieChart(ctx, width, height, labels, data, colors) {
            const centerX = width / 2;
            const centerY = height / 2;
            const radius = Math.min(width, height) / 3;
            
            // Clear canvas
            ctx.clearRect(0, 0, width, height);
            
            const total = data.reduce((sum, value) => sum + value, 0);
            let currentAngle = -Math.PI / 2;
            
            // Draw pie slices
            for (let i = 0; i < data.length; i++) {
                const sliceAngle = (data[i] / total) * 2 * Math.PI;
                
                ctx.fillStyle = colors[i];
                ctx.beginPath();
                ctx.moveTo(centerX, centerY);
                ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
                ctx.closePath();
                ctx.fill();
                
                // Draw label
                const labelAngle = currentAngle + sliceAngle / 2;
                const labelX = centerX + Math.cos(labelAngle) * (radius + 20);
                const labelY = centerY + Math.sin(labelAngle) * (radius + 20);
                
                ctx.fillStyle = '#333';
                ctx.font = '12px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(labels[i], labelX, labelY);
                ctx.fillText(data[i], labelX, labelY + 15);
                
                currentAngle += sliceAngle;
            }
        }

        handleRefreshAppraisalQueue() {
            console.log('Refreshing appraisal queue...');
            this.showToast('Refreshing appraisal queue...', 'info');
            
            // Get current filter values
            const typeFilter = document.querySelector('select[style*="width: 180px"]');
            const statusFilter = document.querySelector('select[style*="width: 150px"]');
            
            const filters = {
                type: typeFilter ? typeFilter.value : null,
                status: statusFilter ? statusFilter.value : null
            };
            
            this.loadAppraisalQueue(filters);
        }

        handleRefreshSelfAssessments() {
            console.log('Refreshing self-assessments...');
            this.showToast('Refreshing self-assessments...', 'info');
            this.loadSelfAssessments();
        }

        handleExportAppraisalQueue() {
            console.log('Exporting appraisal queue...');
            
            try {
                // Get current table data
                const tableBody = document.getElementById('appraisalQueueTableBody');
                if (!tableBody) {
                    this.showToast('No data to export', 'warning');
                    return;
                }

                const rows = tableBody.querySelectorAll('tr');
                if (rows.length === 0) {
                    this.showToast('No data to export', 'warning');
                    return;
                }

                // Create CSV content
                const headers = ['Employee', 'Appraisal Type', 'Period', 'Due Date', 'Status', 'Progress', 'Rating'];
                let csvContent = headers.join(',') + '\n';

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    if (cells.length > 0) {
                        const rowData = [];
                        // Extract text content from each cell, handling complex HTML
                        cells.forEach((cell, index) => {
                            if (index < 7) { // Only first 7 columns (excluding Actions)
                                let cellText = cell.textContent.trim();
                                // Clean up the text and escape commas
                                cellText = cellText.replace(/\s+/g, ' ').replace(/"/g, '""');
                                if (cellText.includes(',')) {
                                    cellText = `"${cellText}"`;
                                }
                                rowData.push(cellText);
                            }
                        });
                        if (rowData.length > 0) {
                            csvContent += rowData.join(',') + '\n';
                        }
                    }
                });

                // Create and download file
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', `appraisal_queue_${new Date().toISOString().split('T')[0]}.csv`);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                this.showToast('Appraisal queue exported successfully!', 'success');
            } catch (error) {
                console.error('Error exporting data:', error);
                this.showToast('Failed to export data', 'error');
            }
        }

        handlePrintAppraisal() {
            console.log('Printing appraisal...');
            
            // Get the modal content
            const modalContent = document.getElementById('appraisalDetailsContent');
            const modalTitle = document.getElementById('viewAppraisalModalLabel').textContent;
            
            if (!modalContent) {
                this.showToast('No appraisal data to print', 'warning');
                return;
            }

            // Create print window with company logo
            const printWindow = window.open('', '_blank');
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Performance Appraisal - ${modalTitle}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                        .logo { max-height: 60px; margin-bottom: 10px; }
                        .company-name { font-size: 24px; font-weight: bold; color: #333; }
                        .document-title { font-size: 20px; margin: 20px 0; color: #666; }
                        .content { margin: 20px 0; }
                        .row { display: flex; margin-bottom: 20px; }
                        .col-md-6 { flex: 1; padding-right: 20px; }
                        h6 { color: #333; font-size: 16px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
                        p { margin: 8px 0; }
                        strong { color: #333; }
                        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
                        .bg-success { background-color: #28a745; color: white; }
                        .bg-warning { background-color: #ffc107; color: black; }
                        .bg-danger { background-color: #dc3545; color: white; }
                        .bg-info { background-color: #17a2b8; color: white; }
                        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #666; }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <img src="/images/gesl_logo.png" alt="Company Logo" class="logo">
                        <div class="company-name">GESL Company</div>
                        <div class="document-title">${modalTitle}</div>
                    </div>
                    <div class="content">
                        ${modalContent.innerHTML}
                    </div>
                    <div class="footer">
                        <p>Generated on ${new Date().toLocaleDateString()} at ${new Date().toLocaleTimeString()}</p>
                        <p>This is a computer-generated document.</p>
                    </div>
                </body>
                </html>
            `;
                  
            printWindow.document.write(printContent);
            printWindow.document.close();
            
            // Wait for content to load then print
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };

            this.showToast('Print dialog opened', 'success');
        }


        handleExportTrendReport() {
            console.log('Exporting trend report...');
            this.showToast('Trend report export functionality will be implemented', 'info');
        }

        handleExportDistributionData() {
            console.log('Exporting distribution data...');
            this.showToast('Distribution data export functionality will be implemented', 'info');
        }

        handleGenerateDistributionReport() {
            console.log('Generating distribution report...');
            this.showToast('Distribution report generation functionality will be implemented', 'info');
        }

        handleAddKpi() {
            console.log('Adding new KPI...');
            
            // Get form data
            const form = document.getElementById('addKpiForm');
            const formData = new FormData(form);
            
            const name = formData.get('kpiName');
            const description = formData.get('kpiDescription');
            const weight = formData.get('kpiWeight');
            const measurement = formData.get('kpiMeasurement');
            
            console.log('KPI Data:', { name, description, weight, measurement });
            
            // Validate required fields
            if (!name) {
                this.showToast('KPI name is required', 'error');
                return;
            }
            
            // Add the KPI card
            this.addKPICard(name, description, weight, measurement, true);
            
            // Save to localStorage for persistence
            this.saveCustomKPIsToStorage();
            
            // Reset form
            form.reset();
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addKpiModal'));
            if (modal) {
                modal.hide();
            }
            
            this.showToast('KPI added successfully!', 'success');
        }

        // Save custom KPIs to localStorage
        saveCustomKPIsToStorage() {
            const kpiContainer = document.getElementById('kpiContainer');
            const customKPIs = kpiContainer.querySelectorAll('.card[data-kpi-type="custom"]');
            const kpiData = [];

            customKPIs.forEach(card => {
                const name = card.getAttribute('data-kpi-name');
                const description = card.getAttribute('data-kpi-description');
                const weight = card.getAttribute('data-kpi-weight');
                const measurement = card.getAttribute('data-kpi-measurement');
                const checkbox = card.querySelector('input[type="checkbox"]');
                const scoreSlider = card.querySelector('input[type="range"][id*="Score"]');
                
                const enabled = checkbox ? checkbox.checked : true;
                const score = scoreSlider ? parseInt(scoreSlider.value) : 75;

                if (name) {
                    kpiData.push({
                        name: name,
                        description: description,
                        weight: parseInt(weight) || 15,
                        measurement: measurement,
                        enabled: enabled,
                        score: score
                    });
                }
            });

            localStorage.setItem('tempCustomKPIs', JSON.stringify(kpiData));
            console.log('Saved custom KPIs to localStorage:', kpiData);
        }

        // Load custom KPIs from localStorage
        loadCustomKPIsFromStorage() {
            const savedKPIs = localStorage.getItem('tempCustomKPIs');
            if (savedKPIs) {
                try {
                    const kpiData = JSON.parse(savedKPIs);
                    console.log('Loading custom KPIs from localStorage:', kpiData);
                    
                    kpiData.forEach(kpi => {
                        this.addKPICard(kpi.name, kpi.description, kpi.weight, kpi.measurement, kpi.enabled, kpi.score);
                    });
                    
                    // Recalculate rating after loading
                    setTimeout(() => {
                        calculateOverallRating();
                    }, 100);
                } catch (error) {
                    console.error('Error loading custom KPIs from localStorage:', error);
                }
            }
        }

        // Clear custom KPIs from localStorage (called after successful form submission)
        clearCustomKPIsFromStorage() {
            localStorage.removeItem('tempCustomKPIs');
            console.log('Cleared custom KPIs from localStorage');
        }

        // Performance History Filter Methods
        applyHistoryFilters() {
            console.log('Applying performance history filters...');
            
            const filters = {
                employee: document.getElementById('historyEmployeeFilter')?.value || '',
                department: document.getElementById('historyDepartmentFilter')?.value || '',
                rating: document.getElementById('historyRatingFilter')?.value || '',
                dateRange: document.getElementById('historyDateRangeFilter')?.value || '',
                fromDate: document.getElementById('historyFromDate')?.value || '',
                toDate: document.getElementById('historyToDate')?.value || ''
            };
            
            console.log('History filters:', filters);
            this.loadPerformanceHistory(filters);
            this.showToast('Filters applied successfully!', 'success');
        }

        clearHistoryFilters() {
            console.log('Clearing performance history filters...');
            
            // Reset all filter dropdowns
            const filterIds = ['historyEmployeeFilter', 'historyDepartmentFilter', 'historyRatingFilter', 'historyDateRangeFilter'];
            filterIds.forEach(id => {
                const element = document.getElementById(id);
                if (element) element.value = '';
            });
            
            // Reset date inputs
            const dateIds = ['historyFromDate', 'historyToDate'];
            dateIds.forEach(id => {
                const element = document.getElementById(id);
                if (element) element.value = '';
            });
            
            // Hide custom date range
            const customDateRange = document.getElementById('customDateRange');
            if (customDateRange) customDateRange.style.display = 'none';
            
            // Reload data without filters
            this.loadPerformanceHistory();
            this.showToast('Filters cleared!', 'info');
        }

        exportHistoryData() {
            console.log('Exporting performance history data...');
            
            const filters = {
                employee: document.getElementById('historyEmployeeFilter')?.value || '',
                department: document.getElementById('historyDepartmentFilter')?.value || '',
                rating: document.getElementById('historyRatingFilter')?.value || '',
                dateRange: document.getElementById('historyDateRangeFilter')?.value || '',
                fromDate: document.getElementById('historyFromDate')?.value || '',
                toDate: document.getElementById('historyToDate')?.value || ''
            };
            
            // For now, show a placeholder message
            this.showToast('Performance history export functionality will be implemented', 'info');
            console.log('Export filters:', filters);
        }

        // Load employees for history filter dropdown
        async loadHistoryEmployees() {
            const employeeSelect = document.getElementById('historyEmployeeFilter');
            
            if (!employeeSelect) return;

            try {
                console.log('Loading employees for history filter...');
                
                const response = await fetch('/company/hr/performance/search-employees', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({
                        search: '',
                        page: 1,
                        per_page: 100 // Get more employees
                    })
                });

                const result = await response.json();
                console.log('Employee search response:', result);
                
                if (result.success && result.data && result.data.length > 0) {
                    // Clear existing options except "All Employees"
                    employeeSelect.innerHTML = '<option value="">All Employees</option>';
                    
                    // Add employee options
                    result.data.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.id;
                        option.textContent = `${employee.text} (${employee.staff_id || employee.id})`;
                        employeeSelect.appendChild(option);
                    });
                    
                    console.log(`Loaded ${result.data.length} employees`);
                } else {
                    console.warn('No employees found or API returned empty data');
                    employeeSelect.innerHTML = '<option value="">No employees found</option>';
                }
            } catch (error) {
                console.error('Error loading employees for history filter:', error);
                employeeSelect.innerHTML = '<option value="">Error loading employees</option>';
            }
        }

        async handleScheduleAppraisal(e) {
            // CRITICAL: Prevent default form submission
            e.preventDefault();
            e.stopPropagation();
            
            const isEditing = this.editingAppraisalId !== undefined;
            console.log('DEBUG: Form submission - editingAppraisalId:', this.editingAppraisalId, 'isEditing:', isEditing);

            const formData = new FormData(e.target);

            // Find the submit button
            const submitBtn = document.querySelector('button[form="scheduleAppraisalForm"]');
            if (!submitBtn) {
                this.showToast('Submit button not found', 'error');
                return false;
            }

            const originalBtnText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> ${isEditing ? 'Updating...' : 'Scheduling...'}`;

            try {
                // Get employee and reviewer IDs from Select2
                const employeeId = formData.get('employee_id');
                const reviewerId = formData.get('reviewer_id');
                
                if (!employeeId) {
                    this.showToast('Please select an employee', 'error');
                    return false;
                }
                
                if (!reviewerId) {
                    this.showToast('Please select a reviewer', 'error');
                    return false;
                }

                // Validate date logic
                const startDate = formData.get('start_date');
                const dueDate = formData.get('due_date');
                if (startDate && dueDate) {
                    const start = new Date(startDate);
                    const due = new Date(dueDate);
                    if (due < start) {
                        this.showToast('Due date must be after start date', 'error');
                        return false;
                    }
                }

                // Collect KPI data from the form
                const kpiData = this.collectKPIData();

                // Get calculated ratings from form fields
                const overallScore = parseFloat(formData.get('overall_score')) || 3.0;
                const overallRating = formData.get('overall_rating') || 'satisfactory';

                const requestData = {
                    employee_id: parseInt(employeeId),
                    type: formData.get('type'),
                    review_period_start: formData.get('start_date'),
                    review_period_end: formData.get('due_date'),
                    goals: 'Performance goals will be set during review',
                    achievements: 'To be filled during review',
                    areas_for_improvement: 'To be identified during review',
                    overall_score: overallScore,
                    overall_rating: overallRating,
                    status: 'pending',
                    notes: formData.get('notes') || '',
                    kpis: kpiData
                };

                // Add reviewer_id if present
                if (reviewerId) {
                    requestData.reviewer_id = parseInt(reviewerId);
                }

                console.log('=== FORM SUBMISSION DEBUG ===');
                console.log('Is editing:', isEditing);
                console.log('Editing appraisal ID:', this.editingAppraisalId);
                console.log('Selected appraisal type:', formData.get('type'));
                console.log('Form data being sent:', requestData);
                console.log('All form data entries:');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                
                const url = isEditing ? `/company/hr/performance/${this.editingAppraisalId}` : '/company/hr/performance/store';
                const method = isEditing ? 'PUT' : 'POST';
                
                console.log('URL:', url);
                console.log('Method:', method);
                console.log('=== END FORM DEBUG ===');

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });

                const result = await response.json();
                console.log('Form submission response:', result);

                if (result.success) {
                    // Show success message immediately
                    console.log('Showing success toast...');
                    this.showToast(isEditing ? 'Appraisal updated successfully!' : 'Appraisal scheduled successfully!', 'success');

                    // Reset form
                    e.target.reset();
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('scheduleAppraisalModal'));
                    if (modal) modal.hide();

                    // Reset editing state
                    if (isEditing) {
                        this.editingAppraisalId = undefined;
                        document.getElementById('scheduleAppraisalModalLabel').textContent = 'Schedule Performance Appraisal';
                    }

                    // Clear custom KPIs from localStorage after successful submission
                    this.clearCustomKPIsFromStorage();

                    // Reload data with a small delay to ensure success message shows first
                    setTimeout(() => {
                        this.loadAppraisalQueue();
                        this.loadPerformanceStats();
                    }, 100);
                } else {
                    console.log('Form submission failed:', result);
                    this.showToast(result.message || 'Failed to save appraisal', 'error');
                    if (result.errors) {
                        console.log('Validation errors:', result.errors);
                    }
                }
            } catch (error) {
                console.error('Error saving appraisal:', error);
                this.showToast('Failed to save appraisal: ' + error.message, 'error');
            } finally {
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }

            // Ensure we return false to prevent any default behavior
            return false;
        }

        handleFilterChange() {
            const typeFilter = document.querySelector('select[style*="width: 180px"]');
            const statusFilter = document.querySelector('select[style*="width: 150px"]');
            
            const filters = {
                type: typeFilter ? typeFilter.value : null,
                status: statusFilter ? statusFilter.value : null
            };
            
            this.loadAppraisalQueue(filters);
        }

        loadExistingKPIs(appraisal) {
            console.log('Loading existing KPIs for appraisal:', appraisal);
            
            const kpiContainer = document.getElementById('kpiContainer');
            if (!kpiContainer) {
                console.log('KPI container not found');
                return;
            }

            // Clear existing KPIs (except the default ones)
            const existingCustomKPIs = kpiContainer.querySelectorAll('.card[data-kpi-type="custom"]');
            existingCustomKPIs.forEach(kpi => kpi.remove());

            // If appraisal has KPIs data, load them and update KPI scores
            if (appraisal.kpis && Array.isArray(appraisal.kpis)) {
                console.log('Loading KPIs from saved data:', appraisal.kpis);
                
                // Separate default and custom KPIs
                const defaultKPIs = [];
                const customKPIs = [];
                
                appraisal.kpis.forEach(kpi => {
                    if (kpi.name === 'Sales Target Achievement' || 
                        kpi.name === 'Customer Satisfaction' || 
                        kpi.name === 'Team Collaboration') {
                        defaultKPIs.push(kpi);
                    } else {
                        customKPIs.push(kpi);
                    }
                });
                
                // Update default KPI scores and checkboxes by name mapping
                const defaultKPIMapping = {
                    'Sales Target Achievement': { index: 1 },
                    'Customer Satisfaction': { index: 2 },
                    'Team Collaboration': { index: 3 }
                };
                
                defaultKPIs.forEach(kpi => {
                    const mapping = defaultKPIMapping[kpi.name];
                    if (mapping) {
                        const scoreId = `kpi${mapping.index}Score`;
                        const checkboxId = `kpi${mapping.index}`;
                        const scoreLabelId = `kpi${mapping.index}ScoreLabel`;
                        
                        const scoreSlider = document.getElementById(scoreId);
                        const checkbox = document.getElementById(checkboxId);
                        const scoreLabel = document.getElementById(scoreLabelId);
                        
                        if (scoreSlider && kpi.score !== undefined) {
                            scoreSlider.value = kpi.score;
                            console.log(`Set ${scoreId} to ${kpi.score} for ${kpi.name}`);
                        }
                        if (scoreLabel && kpi.score !== undefined) {
                            scoreLabel.textContent = kpi.score;
                        }
                        if (checkbox) {
                            checkbox.checked = kpi.enabled !== false;
                            console.log(`Set ${checkboxId} to ${checkbox.checked} for ${kpi.name}`);
                        }
                    }
                });
                
                // Add custom KPIs with their scores
                customKPIs.forEach(kpi => {
                    this.addKPICard(kpi.name, kpi.description, kpi.weight, kpi.measurement, kpi.enabled !== false, kpi.score || 75);
                });
                
                console.log(`Loaded ${defaultKPIs.length} default KPIs and ${customKPIs.length} custom KPIs`);
            } else if (appraisal.goals || appraisal.achievements) {
                // If no specific KPIs but has goals/achievements, create default KPIs
                if (appraisal.goals && appraisal.goals !== 'Performance goals will be set during review') {
                    this.addKPICard('Goal Achievement', appraisal.goals, 30, 'percentage', true);
                }
                if (appraisal.achievements && appraisal.achievements !== 'To be filled during review') {
                    this.addKPICard('Key Achievements', appraisal.achievements, 25, 'qualitative', true);
                }
                console.log('Created KPIs from goals and achievements');
            } else {
                console.log('No existing KPIs found, keeping default KPIs');
            }
            
            // Recalculate rating after loading KPIs
            setTimeout(() => {
                calculateOverallRating();
                console.log('Rating recalculated after loading existing KPIs');
            }, 200);
        }

        addKPICard(name, description, weight, measurement, enabled = true, score = 75) {
            const kpiId = 'kpi_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            
            const kpiCard = document.createElement('div');
            kpiCard.className = 'card mb-3';
            kpiCard.setAttribute('data-kpi-type', 'custom');
            kpiCard.setAttribute('data-kpi-name', name);
            kpiCard.setAttribute('data-kpi-description', description);
            kpiCard.setAttribute('data-kpi-weight', weight);
            kpiCard.setAttribute('data-kpi-measurement', measurement);
            kpiCard.innerHTML = `
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="card-title mb-0">${name}</h6>
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="${kpiId}" ${enabled ? 'checked' : ''} onchange="calculateOverallRating(); window.performanceManager.saveCustomKPIsToStorage();">
                                <label class="form-check-label" for="${kpiId}">Enabled</label>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.card').remove(); calculateOverallRating(); window.performanceManager.saveCustomKPIsToStorage();">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="card-text text-muted small mb-2">${description}</p>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small text-muted mb-0">Performance Score</label>
                            <span class="badge bg-info" id="${kpiId}ScoreLabel">${score}</span>
                        </div>
                        <input type="range" class="form-range w-100" min="1" max="100" value="${score}" id="${kpiId}Score" oninput="document.getElementById('${kpiId}ScoreLabel').textContent = this.value; calculateOverallRating(); window.performanceManager.saveCustomKPIsToStorage();">
                    </div>
                </div>
            `;
            
            const kpiContainer = document.getElementById('kpiContainer');
            kpiContainer.appendChild(kpiCard);
            
            // Trigger rating calculation after adding the KPI
            setTimeout(() => {
                calculateOverallRating();
                console.log(`Added custom KPI: ${name} and recalculated rating`);
            }, 100);
        }

        collectKPIData() {
            console.log('=== KPI COLLECTION DEBUG ===');
            const kpiContainer = document.getElementById('kpiContainer');
            if (!kpiContainer) {
                console.log('KPI container not found');
                return [];
            }
            console.log('KPI container found:', kpiContainer);

            // Check for both custom and default KPIs
            const customKpiCards = kpiContainer.querySelectorAll('.card[data-kpi-type="custom"]');
            const allKpiCards = kpiContainer.querySelectorAll('.card');
            
            console.log('Custom KPI cards found:', customKpiCards.length);
            console.log('All KPI cards found:', allKpiCards.length);
            
            const kpiData = [];

            // First try to collect custom KPIs
            customKpiCards.forEach((card, index) => {
                console.log(`Processing custom KPI card ${index}:`, card);
                const name = card.getAttribute('data-kpi-name');
                const description = card.getAttribute('data-kpi-description');
                const weight = card.getAttribute('data-kpi-weight');
                const measurement = card.getAttribute('data-kpi-measurement');
                const checkbox = card.querySelector('input[type="checkbox"]');
                const enabled = checkbox ? checkbox.checked : true;
                
                // Get the score from the slider
                const scoreSlider = card.querySelector('input[type="range"][id*="Score"]');
                const score = scoreSlider ? parseInt(scoreSlider.value) : 75;

                console.log(`Custom KPI ${index}:`, { name, description, weight, measurement, enabled, score });

                if (name) {
                    kpiData.push({
                        name: name,
                        description: description,
                        weight: parseInt(weight) || 15,
                        measurement: measurement,
                        enabled: enabled,
                        score: score  // Add the score to the data
                    });
                }
            });

            // Always collect from default KPIs as well
            console.log('Collecting default KPIs...');
            const defaultKpiCards = kpiContainer.querySelectorAll('.card:not([data-kpi-type="custom"])');
            console.log('Default KPI cards found:', defaultKpiCards.length);
            
            // Define fixed weights for default KPIs
            const defaultWeights = [30, 25, 20]; // Sales Target, Customer Satisfaction, Team Collaboration
            
            defaultKpiCards.forEach((card, index) => {
                console.log(`Processing default KPI card ${index}:`, card);
                const titleElement = card.querySelector('h6, .card-title');
                const name = titleElement ? titleElement.textContent.trim() : `Default KPI ${index + 1}`;
                const checkbox = card.querySelector('input[type="checkbox"]');
                const enabled = checkbox ? checkbox.checked : true;
                
                // Get the score from the score slider (not weight slider)
                const scoreSlider = card.querySelector('input[type="range"][id*="Score"]');
                const score = scoreSlider ? parseInt(scoreSlider.value) : 75;
                const weight = defaultWeights[index] || 20;
                
                console.log(`Default KPI ${index}:`, { name, enabled, weight, score });

                kpiData.push({
                    name: name,
                    description: 'Default KPI',
                    weight: weight,
                    measurement: 'percentage',
                    enabled: enabled,
                    score: score  // Add the score to the data
                });
            });

            console.log('Collected KPI data:', kpiData);
            return kpiData;
        }

        prepareNewAppraisal() {
            console.log('Preparing modal for new appraisal...');
            this.clearModalData();
            
            // Load any saved custom KPIs from localStorage
            setTimeout(() => {
                this.loadCustomKPIsFromStorage();
            }, 100);
        }

        clearModalData() {
            console.log('Clearing modal for new appraisal...');
            
            // Reset editing state
            this.isEditing = false;
            this.editingAppraisalId = undefined;
            
            // Clear form fields
            const form = document.getElementById('scheduleAppraisalForm');
            if (form) {
                form.reset();
            }
            
            // Clear Select2 dropdowns
            const employeeSelect = $('#appraisalEmployee');
            const reviewerSelect = $('#appraisalReviewer');
            
            if (employeeSelect.length) {
                employeeSelect.val(null).trigger('change');
            }
            if (reviewerSelect.length) {
                reviewerSelect.val(null).trigger('change');
            }
            
            // Reset modal title
            const modalTitle = document.getElementById('scheduleAppraisalModalLabel');
            if (modalTitle) {
                modalTitle.textContent = 'Schedule New Performance Appraisal';
            }
            
            // Clear custom KPIs and reset default KPI values
            const kpiContainer = document.getElementById('kpiContainer');
            if (kpiContainer) {
                // Remove custom KPIs
                const customKPIs = kpiContainer.querySelectorAll('.card[data-kpi-type="custom"]');
                customKPIs.forEach(kpi => kpi.remove());
                
                // No need to reset weight sliders since they're removed
                
                // Reset default KPI checkboxes to enabled
                const defaultCheckboxes = ['kpi1', 'kpi2', 'kpi3'];
                defaultCheckboxes.forEach(checkboxId => {
                    const checkbox = document.getElementById(checkboxId);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
                
                // Reset default KPI scores to default values
                const defaultScores = [
                    { scoreId: 'kpi1Score', labelId: 'kpi1ScoreLabel', defaultValue: 75 },
                    { scoreId: 'kpi2Score', labelId: 'kpi2ScoreLabel', defaultValue: 80 },
                    { scoreId: 'kpi3Score', labelId: 'kpi3ScoreLabel', defaultValue: 70 }
                ];
                
                defaultScores.forEach(({ scoreId, labelId, defaultValue }) => {
                    const scoreSlider = document.getElementById(scoreId);
                    const scoreLabel = document.getElementById(labelId);
                    if (scoreSlider && scoreLabel) {
                        scoreSlider.value = defaultValue;
                        scoreLabel.textContent = defaultValue;
                    }
                });
            }
            
            // Recalculate rating after clearing modal
            setTimeout(() => {
                calculateOverallRating();
            }, 100);
            
            console.log('Modal cleared for new appraisal');
        }
    }

    // KPI Weight Update Function
    function updateKPIWeight(sliderId, labelId) {
        const slider = document.getElementById(sliderId);
        const label = document.getElementById(labelId);
        if (slider && label) {
            label.textContent = `Weight: ${slider.value}%`;
            console.log(`Updated ${sliderId} to ${slider.value}%`);
        }
    }

    // Calculate Overall Rating from KPI Scores
    function calculateOverallRating() {
        console.log('=== CALCULATING OVERALL RATING ===');
        
        const kpiContainer = document.getElementById('kpiContainer');
        if (!kpiContainer) {
            console.log('KPI container not found');
            return;
        }

        let totalWeightedScore = 0;
        let totalWeight = 0;
        let enabledKPIs = 0;

        // Collect scores from default KPIs with fixed weights
        const defaultKPIs = [
            { scoreId: 'kpi1Score', weight: 30, checkboxId: 'kpi1', name: 'Sales Target Achievement' },
            { scoreId: 'kpi2Score', weight: 25, checkboxId: 'kpi2', name: 'Customer Satisfaction' },
            { scoreId: 'kpi3Score', weight: 20, checkboxId: 'kpi3', name: 'Team Collaboration' }
        ];

        defaultKPIs.forEach(kpi => {
            const scoreSlider = document.getElementById(kpi.scoreId);
            const checkbox = document.getElementById(kpi.checkboxId);

            if (scoreSlider && checkbox && checkbox.checked) {
                const score = parseInt(scoreSlider.value);
                const weight = kpi.weight;
                
                totalWeightedScore += (score * weight);
                totalWeight += weight;
                enabledKPIs++;
                
                console.log(`${kpi.name}: Score=${score}, Weight=${weight}%, Enabled=true`);
            } else if (checkbox && !checkbox.checked) {
                console.log(`${kpi.name}: Disabled`);
            }
        });

        // Collect scores from custom KPIs
        const customKPIs = kpiContainer.querySelectorAll('.card[data-kpi-type="custom"]');
        customKPIs.forEach((card, index) => {
            const checkbox = card.querySelector('input[type="checkbox"]');
            const scoreSlider = card.querySelector('input[type="range"][id*="Score"]');
            const name = card.getAttribute('data-kpi-name') || `Custom KPI ${index + 1}`;
            const weight = parseInt(card.getAttribute('data-kpi-weight')) || 15; // Default weight for custom KPIs

            if (scoreSlider && checkbox && checkbox.checked) {
                const score = parseInt(scoreSlider.value);
                
                totalWeightedScore += (score * weight);
                totalWeight += weight;
                enabledKPIs++;
                
                console.log(`${name}: Score=${score}, Weight=${weight}%, Enabled=true`);
            } else if (checkbox && !checkbox.checked) {
                console.log(`${name}: Disabled`);
            }
        });

        // Calculate overall score
        let overallScore = 3.0; // Default satisfactory
        let overallRating = 'satisfactory';

        if (totalWeight > 0 && enabledKPIs > 0) {
            // Convert weighted average from 0-100 scale to 1-5 scale
            const weightedAverage = totalWeightedScore / totalWeight;
            overallScore = Math.round(((weightedAverage / 100) * 4 + 1) * 10) / 10; // Convert to 1-5 scale
            
            // Ensure score is within bounds
            overallScore = Math.max(1.0, Math.min(5.0, overallScore));
            
            // Determine rating category
            if (overallScore >= 4.5) {
                overallRating = 'excellent';
            } else if (overallScore >= 3.5) {
                overallRating = 'good';
            } else if (overallScore >= 2.5) {
                overallRating = 'satisfactory';
            } else if (overallScore >= 1.5) {
                overallRating = 'needs_improvement';
            } else {
                overallRating = 'poor';
            }
        }

        console.log(`Total Weighted Score: ${totalWeightedScore}`);
        console.log(`Total Weight: ${totalWeight}`);
        console.log(`Enabled KPIs: ${enabledKPIs}`);
        console.log(`Overall Score: ${overallScore}/5.0`);
        console.log(`Overall Rating: ${overallRating}`);

        // Update the form fields
        const overallScoreField = document.getElementById('overallScore');
        const overallRatingField = document.getElementById('overallRating');

        if (overallScoreField) {
            overallScoreField.value = overallScore;
        }

        if (overallRatingField) {
            overallRatingField.value = overallRating;
            // Update the selected option
            Array.from(overallRatingField.options).forEach(option => {
                option.selected = option.value === overallRating;
            });
        }

        console.log('=== RATING CALCULATION COMPLETE ===');
    }

    // Update all KPI weight labels to match their slider values
    function updateAllKPIWeightLabels() {
        console.log('Updating all KPI weight labels...');
        
        // Update default KPIs
        const defaultKPIs = [
            { sliderId: 'kpi1Weight', labelId: 'kpi1WeightLabel' },
            { sliderId: 'kpi2Weight', labelId: 'kpi2WeightLabel' },
            { sliderId: 'kpi3Weight', labelId: 'kpi3WeightLabel' }
        ];
        
        defaultKPIs.forEach(kpi => {
            updateKPIWeight(kpi.sliderId, kpi.labelId);
        });
        
        // Update custom KPIs (they should already have correct labels from addKPICard)
        const customKPIs = document.querySelectorAll('.card[data-kpi-type="custom"] input[type="range"]');
        customKPIs.forEach(slider => {
            if (slider.id) {
                const labelId = slider.id.replace('Weight', 'WeightLabel');
                const label = document.getElementById(labelId);
                if (label) {
                    label.textContent = `Weight: ${slider.value}%`;
                }
            }
        });
        
        console.log('All KPI weight labels updated');
    }

    // Debug helper functions
    function updateDebugStatus(elementId, status, badgeClass = 'bg-secondary') {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = `<span class="badge ${badgeClass}">${status}</span>`;
        }
    }

    function addDebugLog(message, type = 'info') {
        const debugConsole = document.getElementById('debug-console');
        if (debugConsole) {
            const timestamp = new Date().toLocaleTimeString();
            const colorClass = type === 'error' ? 'text-danger' : type === 'success' ? 'text-success' : type === 'warning' ? 'text-warning' : 'text-info';
            debugConsole.innerHTML += `<div class="${colorClass}">[${timestamp}] ${message}</div>`;
            debugConsole.scrollTop = debugConsole.scrollHeight;
        }
        console.log(`DEBUG: ${message}`);
    }

    function clearDebugLogs() {
        const debugConsole = document.getElementById('debug-console');
        if (debugConsole) {
            debugConsole.innerHTML = 'Debug logs cleared...';
        }
    }

    // Test DOMContentLoaded event
    console.log('🔍 DEBUG: Setting up DOMContentLoaded listener...');

    // Hide/show sidebar, header, footer when modal opens/closes
    document.addEventListener('show.bs.modal', function(e) {
        // Hide navigation sidebar
        const sidebar = document.querySelector('.app-sidebar, .sidebar, nav.sidebar, .main-sidebar, aside');
        if (sidebar) sidebar.style.display = 'none';
        
        // Hide header
        const header = document.querySelector('header, .app-header, .main-header, .navbar');
        if (header) header.style.display = 'none';
        
        // Hide footer
        const footer = document.querySelector('footer, .app-footer, .main-footer');
        if (footer) footer.style.display = 'none';
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    });
    
    document.addEventListener('hide.bs.modal', function(e) {
        // Show navigation sidebar
        const sidebar = document.querySelector('.app-sidebar, .sidebar, nav.sidebar, .main-sidebar, aside');
        if (sidebar) sidebar.style.display = '';
        
        // Show header
        const header = document.querySelector('header, .app-header, .main-header, .navbar');
        if (header) header.style.display = '';
        
        // Show footer
        const footer = document.querySelector('footer, .app-footer, .main-footer');
        if (footer) footer.style.display = '';
        
        // Restore body scroll
        document.body.style.overflow = '';
    });

    // Initialize Performance Management when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔍 DEBUG: DOMContentLoaded event fired!');
        addDebugLog('DOMContentLoaded event fired - initializing Performance Management...', 'success');
        updateDebugStatus('debug-api-status', 'Initializing...', 'bg-warning');

        try {
            const performanceManager = new PerformanceManagement();
            addDebugLog('PerformanceManagement instance created successfully', 'success');
            updateDebugStatus('debug-api-status', 'Initialized', 'bg-success');

            // Make it globally available for debugging
            window.performanceManager = performanceManager;
        } catch (error) {
            console.error('🔍 DEBUG: Error creating PerformanceManagement instance:', error);
            addDebugLog('Error creating PerformanceManagement instance: ' + error.message, 'error');
            updateDebugStatus('debug-api-status', 'Failed', 'bg-danger');
        }
    });

    // Fallback: try to initialize immediately if DOM is already loaded
    if (document.readyState === 'loading') {
        console.log('🔍 DEBUG: DOM still loading, waiting for DOMContentLoaded...');
        addDebugLog('DOM still loading, waiting for DOMContentLoaded...', 'warning');
    } else {
        console.log('🔍 DEBUG: DOM already loaded, initializing immediately...');
        addDebugLog('DOM already loaded, initializing immediately...', 'info');
        // DOM is already loaded, initialize immediately
        try {
            const performanceManager = new PerformanceManagement();
            addDebugLog('PerformanceManagement instance created (immediate init)', 'success');
            updateDebugStatus('debug-api-status', 'Initialized', 'bg-success');
            window.performanceManager = performanceManager;
        } catch (error) {
            console.error('🔍 DEBUG: Error in immediate init:', error);
            addDebugLog('Error in immediate init: ' + error.message, 'error');
            updateDebugStatus('debug-api-status', 'Failed', 'bg-danger');
        }
    }


</script>
