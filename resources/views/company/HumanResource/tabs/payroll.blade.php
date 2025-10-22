<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="header-title">Payroll Management</h4>
        <p class="text-muted">Manage employee salaries, deductions, and payment processing</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#runPayrollModal">
            <i class="fas fa-calculator me-1"></i> Run Payroll
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
            <i class="fas fa-plus me-1"></i> Add Payment
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Monthly Payroll Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-primary text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-cedi-sign fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">This Month's Payroll</h6>
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="mb-0 fw-bold" id="monthlyPayrollAmount">₵0</h2>
                        <span class="badge bg-white-20 rounded-pill px-2 py-1" id="monthlyPayrollChange">
                            <i class="fas fa-arrow-up me-1"></i> 0%
                        </span>
                    </div>
                    <div class="mt-auto pt-2">
                        <span class="small opacity-75">vs last month</span>
                    </div>
                </div>
            </div>
            <a href="#payroll" class="stretched-link"></a>
        </div>
    </div>
    <!-- Employees Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-success text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Employees</h6>
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="mb-0 fw-bold" id="totalEmployees">0</h2>
                        <span class="badge bg-white-20 rounded-pill px-2 py-1" id="newEmployeesThisMonth">
                            <i class="fas fa-user-plus me-1"></i> 0
                        </span>
                    </div>
                    <div class="mt-auto pt-2">
                        <span class="small opacity-75">this month</span>
                    </div>
                </div>
            </div>
            <a href="#employees" class="stretched-link"></a>
        </div>
    </div>
    <!-- Average Salary Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-info text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Avg. Salary</h6>
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="mb-0 fw-bold" id="avgSalary">₵0</h2>
                        <span class="badge bg-white-20 rounded-pill px-2 py-1" id="avgSalaryGrowth">
                            <i class="fas fa-arrow-up me-1"></i> 0%
                        </span>
                    </div>
                    <div class="mt-auto pt-2">
                        <span class="small opacity-75">annual growth</span>
                    </div>
                </div>
            </div>
            <a href="#salaries" class="stretched-link"></a>
        </div>
    </div>
    
    <!-- Taxes Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stats-card bg-gradient-warning text-white h-100 border-0 shadow-sm hover-scale">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-white-20 rounded-circle p-3">
                        <i class="fas fa-receipt fa-lg"></i>
                    </div>
                </div>
                <div class="d-flex flex-column h-100">
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold small">Taxes & SSNIT</h6>
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="mb-0 fw-bold" id="taxesAmount">₵0</h2>
                        <span class="badge bg-white-20 rounded-pill px-2 py-1" id="taxesPeriod">
                            <i class="far fa-calendar-alt me-1"></i> Monthly
                        </span>
                    </div>
                    <div class="mt-auto pt-2">
                        <span class="small opacity-75">This month</span>
                    </div>
                </div>
            </div>
            <a href="#taxes" class="stretched-link"></a>
        </div>
    </div>
</div>

<!-- Payroll Period Selector -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h5 class="card-title mb-3 mb-md-0">Payroll Records</h5>
            <div class="d-flex gap-2">
                <div class="input-group" style="width: 200px;">
                    <select class="form-select" id="payrollPeriod">
                        <option>This Month</option>
                        <option>Last Month</option>
                        <option>Last 3 Months</option>
                        <option>This Year</option>
                        <option>Custom Range</option>
                    </select>
                </div>
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exportPayrollModal">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payroll Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="payrollTable">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Basic Salary</th>
                        <th>Allowances</th>
                        <th>Deductions</th>
                        <th>Net Pay</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="payrollTableBody">
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=John+Doe&background=4e73df&color=fff" 
                                     class="rounded-circle me-2" width="32" height="32" alt="John Doe">
                                <div>
                                    <h6 class="mb-0">John Doe</h6>
                                    <small class="text-muted">EMP-001</small>
                                </div>
                            </div>
                        </td>
                        <td>₵4,200.00</td>
                        <td>₵580.00</td>
                        <td>₵1,020.00</td>
                        <td>
                            <strong>₵3,760.00</strong>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success">Paid</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" title="View Details" data-bs-toggle="modal" data-bs-target="#viewDetailsModal">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editPayroll(1)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" title="Print Payslip" data-bs-toggle="modal" data-bs-target="#printPreviewModal">
                                    <i class="fas fa-print"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" title="Send for Approval" data-bs-toggle="modal" data-bs-target="#sendForApprovalModal">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- More rows would be dynamically generated -->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <div class="text-muted">
                Showing <span class="fw-semibold" id="paginationFrom">0</span> to <span class="fw-semibold" id="paginationTo">0</span> of <span class="fw-semibold" id="paginationTotal">0</span> payroll records
            </div>
            <!-- <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0 small">Per page:</label>
                    <select class="form-select form-select-sm" id="perPageSelect" style="width: auto;" onchange="changePerPage()">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
            </div> -->
            <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0" id="paginationNav">
                        <!-- Pagination will be generated dynamically -->
                </ul>
            </nav>
            </div>
        </div>
    </div>
</div>

<!-- Run Payroll Modal -->
<div class="modal fade" id="runPayrollModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calculator me-2"></i>Run Payroll
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="runPayrollForm">
                    <!-- Pay Period and Date -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pay Period <span class="text-danger">*</span></label>
                            <select class="form-select" id="payPeriod" name="pay_period" required>
                                <option value="">Select Pay Period</option>
                                <option value="monthly">Monthly</option>
                                <option value="bi-weekly">Bi-Weekly</option>
                                <option value="weekly">Weekly</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="runPayrollStartDate" name="start_date" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="runPayrollEndDate" name="end_date" required>
                        </div>
                    </div>

                    <!-- Employee Selection Type -->
                    <div class="mb-4">
                        <label class="form-label">Employee Selection <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="employeeSelection" id="allEmployees" value="all" checked>
                                    <label class="form-check-label" for="allEmployees">
                                        <i class="fas fa-users me-1"></i>All Employees
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="employeeSelection" id="byDepartment" value="department">
                                    <label class="form-check-label" for="byDepartment">
                                        <i class="fas fa-building me-1"></i>By Department
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="employeeSelection" id="individual" value="individual">
                                    <label class="form-check-label" for="individual">
                                        <i class="fas fa-user me-1"></i>Individual Selection
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Department Selection (hidden by default) -->
                    <div class="mb-3" id="departmentSelection" style="display: none;">
                        <label class="form-label">Select Department</label>
                        <select class="form-select" id="departmentSelect" name="department">
                            <option value="">Select Department</option>
                        </select>
                    </div>

                    <!-- Employee List (hidden by default) -->
                    <div class="mb-3" id="employeeList" style="display: none;">
                        <label class="form-label">Select Employees</label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="selectAllEmployees">
                                <label class="form-check-label fw-semibold" for="selectAllEmployees">
                                    Select All
                                </label>
                            </div>
                            <hr class="my-2">
                            <div id="employeeCheckboxes">
                                <!-- Employee checkboxes will be populated here -->
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="includeBonuses" name="include_bonuses">
                                <label class="form-check-label" for="includeBonuses">Include Bonuses</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="includeDeductions" name="include_deductions" checked>
                                <label class="form-check-label" for="includeDeductions">Include Deductions</label>
                            </div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="alert alert-info" id="payrollSummary">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="summaryText">Select employees to see payroll summary</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="runPayrollBtn">
                    <i class="fas fa-calculator me-1"></i>Run Payroll
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPaymentForm">
                    <div class="mb-3">
                        <label class="form-label">Employee</label>
                        <select class="form-select" id="employeeSelect" name="employee_id" required>
                            <option value="">Select Employee</option>
                        </select>
                        <div class="invalid-feedback">Please select an employee.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pay Period</label>
                        <select class="form-select" id="payPeriod" name="pay_period" required>
                            <option value="">Select Pay Period</option>
                            <option value="monthly">Monthly</option>
                            <option value="bi-weekly">Bi-Weekly</option>
                            <option value="weekly">Weekly</option>
                        </select>
                        <div class="invalid-feedback">Please select a pay period.</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="addPaymentStartDate" name="start_date" required>
                            <div class="invalid-feedback">Please select a start date.</div>
                            </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" id="addPaymentEndDate" name="end_date">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Date</label>
                            <input type="date" class="form-control" id="paymentDate" name="payment_date" style="border: 1px solid #ced4da !important; box-shadow: none !important;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Basic Salary</label>
                            <div class="input-group">
                                <span class="input-group-text">₵</span>
                                <input type="number" class="form-control" id="basicSalary" name="basic_salary" step="0.01" min="0" required>
                            </div>
                            <div class="invalid-feedback">Please enter a valid basic salary.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Housing Allowance</label>
                            <div class="input-group">
                                <span class="input-group-text">₵</span>
                                <input type="number" class="form-control" id="housingAllowance" name="housing_allowance" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transport Allowance</label>
                            <div class="input-group">
                                <span class="input-group-text">₵</span>
                                <input type="number" class="form-control" id="transportAllowance" name="transport_allowance" step="0.01" min="0" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Overtime</label>
                            <div class="input-group">
                                <span class="input-group-text">₵</span>
                                <input type="number" class="form-control" id="overtime" name="overtime" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bonus</label>
                            <div class="input-group">
                                <span class="input-group-text">₵</span>
                                <input type="number" class="form-control" id="bonus" name="bonus" step="0.01" min="0" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Other Allowances</label>
                            <div class="input-group">
                                <span class="input-group-text">₵</span>
                                <input type="number" class="form-control" id="otherAllowances" name="other_allowances" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Other Deductions</label>
                            <div class="input-group">
                                <span class="input-group-text">₵</span>
                                <input type="number" class="form-control" id="otherDeductions" name="other_deductions" step="0.01" min="0" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Enter any additional notes"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitPaymentBtn">
                    <span class="spinner-border spinner-border-sm d-none" id="submitSpinner"></span>
                    <span id="submitText">Add Payment</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
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
    
    .bg-white-20 {
        background-color: rgba(255, 255, 255, 0.2) !important;
        backdrop-filter: blur(5px);
    }
    
    /* Smooth transitions */
    .card, .badge, .icon-shape {
        transition: all 0.3s ease-in-out;
    }
    
    /* Stretched link styles */
    .stretched-link::after {
        z-index: 1;
    }
</style>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payroll Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Employee Information</h6>
                        <div class="mb-3">
                            <p class="mb-1"><strong>Name:</strong> John Doe</p>
                            <p class="mb-1"><strong>Employee ID:</strong> EMP-001</p>
                            <p class="mb-1"><strong>Department:</strong> Engineering</p>
                            <p class="mb-1"><strong>Position:</strong> Senior Developer</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Payment Details</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td>Basic Salary:</td>
                                        <td class="text-end">₵4,200.00</td>
                                    </tr>
                                    <tr>
                                        <td>Overtime:</td>
                                        <td class="text-end">₵250.00</td>
                                    </tr>
                                    <tr>
                                        <td>Bonus:</td>
                                        <td class="text-end">₵330.00</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td><strong>Gross Pay:</strong></td>
                                        <td class="text-end"><strong>₵4,780.00</strong></td>
                                    </tr>
                                    <tr>
                                        <td>PAYE (25%):</td>
                                        <td class="text-end">₵1,195.00</td>
                                    </tr>
                                    <tr>
                                        <td>SSNIT (13.5%):</td>
                                        <td class="text-end">₵645.30</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td><strong>Total Deductions:</strong></td>
                                        <td class="text-end"><strong>-₵1,840.30</strong></td>
                                    </tr>
                                    <tr class="table-active">
                                        <td><strong>Net Pay:</strong></td>
                                        <td class="text-end"><strong>₵2,939.70</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Print Payslip</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Payroll Modal -->
<div class="modal fade" id="editPayrollModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Payroll</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Basic Salary (GHS)</label>
                        <div class="input-group">
                            <span class="input-group-text">₵</span>
                            <input type="number" class="form-control" value="4200.00" step="0.01">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Overtime (GHS)</label>
                        <div class="input-group">
                            <span class="input-group-text">₵</span>
                            <input type="number" class="form-control" value="250.00" step="0.01">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bonus (GHS)</label>
                        <div class="input-group">
                            <span class="input-group-text">₵</span>
                            <input type="number" class="form-control" value="330.00" step="0.01">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PAYE Rate (%)</label>
                        <input type="number" class="form-control" value="25" min="0" max="30">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SSNIT Rate (%)</label>
                        <input type="number" class="form-control" value="13.5" min="0" max="20" step="0.1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Print Preview Modal -->
<div class="modal fade" id="printPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payslip Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Payslip content would be dynamically generated here -->
                <div class="p-4">
                    <div class="text-center mb-4">
                        <h4>COMPANY NAME</h4>
                        <p class="mb-0">123 Business Street, City, Country</p>
                        <h5 class="mt-4">PAYSLIP</h5>
                        <p class="mb-0">For the month of September 2025</p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Employee:</strong> John Doe</p>
                            <p class="mb-1"><strong>Employee ID:</strong> EMP-001</p>
                            <p class="mb-1"><strong>Department:</strong> Engineering</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>Pay Date:</strong> September 30, 2025</p>
                            <p class="mb-1"><strong>Pay Period:</strong> Sep 1 - Sep 30, 2025</p>
                            <p class="mb-1"><strong>Payment Method:</strong> Bank Transfer</p>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Basic Salary</td>
                                    <td class="text-end">₵4,200.00</td>
                                </tr>
                                <tr>
                                    <td>Overtime</td>
                                    <td class="text-end">₵250.00</td>
                                </tr>
                                <tr>
                                    <td>Bonus</td>
                                    <td class="text-end">₵330.00</td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>Gross Pay</strong></td>
                                    <td class="text-end"><strong>₵4,780.00</strong></td>
                                </tr>
                                <tr>
                                    <td>PAYE (25%)</td>
                                    <td class="text-end">-₵1,195.00</td>
                                </tr>
                                <tr>
                                    <td>SSNIT (13.5%)</td>
                                    <td class="text-end">-₵645.30</td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>Net Pay</strong></td>
                                    <td class="text-end"><strong>₵2,939.70</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="mb-1">Thank you for your hard work!</p>
                        <p class="text-muted small">This is a system generated payslip. No signature required.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printPayslipContent()">
                    <i class="fas fa-print me-1"></i> Print Payslip
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>Are you sure you want to delete this payroll record?</h5>
                    <p class="text-muted">This action cannot be undone. All data related to this record will be permanently removed.</p>
                </div>
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Note: This will not affect the employee's record, only this specific payroll entry.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Delete Record</button>
            </div>
        </div>
    </div>
</div>

<!-- Send for Approval Modal -->
<div class="modal fade" id="sendForApprovalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-paper-plane text-warning me-2"></i> Send for Approval
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-light text-warning rounded-circle fs-3">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Approval Workflow</h5>
                            <p class="text-muted mb-0">This payroll will be sent for manager approval</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Approver</label>
                        <select class="form-select" required>
                            <option value="">Select Approver</option>
                            <option value="1">John Doe (HR Manager)</option>
                            <option value="2">Sarah Smith (Finance Manager)</option>
                            <option value="3">Michael Brown (Department Head)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Additional Notes</label>
                        <textarea class="form-control" rows="3" placeholder="Add any notes for the approver"></textarea>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <span class="small">
                                An email notification will be sent to the selected approver.
                                You can track the approval status in the payroll records.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmSendForApproval">
                    <i class="fas fa-paper-plane me-1"></i> Send for Approval
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="exportToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span>Export completed successfully!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Export Payroll Modal -->
<div class="modal fade" id="exportPayrollModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Payroll Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exportPayrollForm">
                    <div class="mb-3">
                        <label class="form-label">Export Format</label>
                        <select class="form-select" required>
                            <option value="">Select format</option>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            <input type="text" class="form-control" id="exportDateRange" value="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Include</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="includeSalaryDetails" checked>
                            <label class="form-check-label" for="includeSalaryDetails">
                                Salary Details
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="includeDeductions" checked>
                            <label class="form-check-label" for="includeDeductions">
                                Deductions
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="includeBankDetails" checked>
                            <label class="form-check-label" for="includeBankDetails">
                                Bank Details
                            </label>
                        </div>
                    </div>
                </form>
                <div class="alert alert-info small mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    The export will include data based on your current filters and search criteria.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmExport">
                    <i class="fas fa-download me-1"></i> Export Data
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Additional CDN Libraries -->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any payroll-specific JavaScript here
        console.log('Payroll module initialized');
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize date range picker
        $('#exportDateRange').daterangepicker({
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Apply',
                cancelLabel: 'Clear',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            },
            opens: 'right',
            autoUpdateInput: true,
            showDropdowns: true,
            linkedCalendars: false,
            ranges: {
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
               'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().endOf('month')],
               'This Year': [moment().startOf('year'), moment().endOf('year')]
            }
        });

        // Handle export button click
        document.getElementById('confirmExport').addEventListener('click', function() {
            const format = document.querySelector('#exportPayrollForm select').value;
            const dateRange = document.getElementById('exportDateRange').value;
            const includeSalaryDetails = document.getElementById('includeSalaryDetails').checked;
            const includeDeductions = document.getElementById('includeDeductions').checked;
            const includeBankDetails = document.getElementById('includeBankDetails').checked;

            if (!format) {
                alert('Please select an export format');
                return;
            }

            // Show loading state
            const exportBtn = this;
            const originalText = exportBtn.innerHTML;
            exportBtn.disabled = true;
            exportBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Exporting...';

            // Simulate API call (replace with actual export logic)
            setTimeout(() => {
                // Reset button state
                exportBtn.disabled = false;
                exportBtn.innerHTML = originalText;
                
                // Close modal
                const exportModal = bootstrap.Modal.getInstance(document.getElementById('exportPayrollModal'));
                exportModal.hide();
                
                // Show success message
                const toast = new bootstrap.Toast(document.getElementById('exportToast'));
                document.querySelector('.toast-body').textContent = `Payroll data exported successfully as ${format.toUpperCase()}!`;
                toast.show();
                
                // In a real application, you would trigger a file download here
                console.log('Exporting with:', {
                    format,
                    dateRange,
                    includeSalaryDetails,
                    includeDeductions,
                    includeBankDetails
                });
            }, 1500);
        });

        // Handle Send for Approval
        document.getElementById('confirmSendForApproval')?.addEventListener('click', function() {
            const approverSelect = document.querySelector('#sendForApprovalModal select');
            const notes = document.querySelector('#sendForApprovalModal textarea').value;
            
            if (!approverSelect?.value) {
                alert('Please select an approver');
                return;
            }

            // Show loading state
            const sendBtn = this;
            const originalText = sendBtn.innerHTML;
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Sending...';

            // Simulate API call (replace with actual API call)
            setTimeout(() => {
                // Reset button state
                sendBtn.disabled = false;
                sendBtn.innerHTML = originalText;
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('sendForApprovalModal'));
                modal.hide();
                
                // Show success message
                const toast = new bootstrap.Toast(document.getElementById('exportToast'));
                document.querySelector('.toast-body').innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    <span>Payroll sent for approval successfully!</span>
                `;
                document.getElementById('exportToast').classList.remove('bg-success', 'bg-danger');
                document.getElementById('exportToast').classList.add('bg-success');
                toast.show();
                
                // In a real application, you would make an API call here
                console.log('Sending for approval:', {
                    approverId: approverSelect.value,
                    approverName: approverSelect.options[approverSelect.selectedIndex].text,
                    notes: notes
                });
            }, 1500);
        });
    });

    // ===== PAYROLL ADD FUNCTIONALITY =====
    
    // Load employees when modal opens
    document.getElementById('addPaymentModal').addEventListener('show.bs.modal', function() {
        loadEmployees();
        // Add a small delay to ensure DOM elements are ready
        setTimeout(() => {
        setDefaultDates();
        }, 100);
    });

    // Clean up backdrop when modal is hidden
    document.getElementById('addPaymentModal').addEventListener('hidden.bs.modal', function() {
        // Remove any remaining backdrop
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('padding-right', '');
    });

    // Load employees from backend
    function loadEmployees() {
        console.log('📊 Loading employees for Add Payment modal...');
        const employeeSelect = document.getElementById('employeeSelect');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        if (!employeeSelect) {
            console.error('❌ Employee select element not found');
            return Promise.resolve();
        }
        
        // Show loading state
        employeeSelect.innerHTML = '<option value="">Loading employees...</option>';
        employeeSelect.disabled = true;
        
        return fetch('/company/hr/payroll/employees', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            console.log('📊 Employee loading response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('📊 Employee loading response data:', data);
            if (data && data.success && Array.isArray(data.data)) {
                employeeSelect.innerHTML = '<option value="">Select Employee</option>';
                
                data.data.forEach(employee => {
                    const option = document.createElement('option');
                    option.value = employee.value;
                    option.textContent = `${employee.label} (${employee.staff_id}) - ${employee.department}`;
                    employeeSelect.appendChild(option);
                });
                
                employeeSelect.disabled = false;
                console.log('✅ Employees loaded successfully:', data.data.length);
            } else {
                console.error('❌ Invalid employee response data:', data);
                throw new Error('Invalid response data');
            }
        })
        .catch(error => {
            console.error('❌ Error loading employees:', error);
            employeeSelect.innerHTML = '<option value="">Error loading employees - Click to retry</option>';
            employeeSelect.disabled = false;
            
            // Add retry functionality
            employeeSelect.addEventListener('click', function() {
                if (this.innerHTML.includes('Error loading employees')) {
                    console.log('🔄 Retrying employee loading...');
                    loadEmployees();
                }
            });
            
            throw error;
        });
    }

    // Set default dates
    function setDefaultDates() {
        console.log('📅 Setting default dates for Add Payment modal...');
        const today = new Date();
        const startDate = document.getElementById('addPaymentStartDate');
        const endDate = document.getElementById('addPaymentEndDate');
        const paymentDate = document.getElementById('paymentDate');
        
        if (!startDate || !endDate || !paymentDate) {
            console.error('❌ Date input elements not found');
            return;
        }
        
        // Set date restrictions - allow 3 months before and after, but default to current month
        const threeMonthsBefore = new Date(today.getFullYear(), today.getMonth() - 3, 1);
        const threeMonthsAfter = new Date(today.getFullYear(), today.getMonth() + 3, 0);
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        // Set min/max attributes to allow 3 months before and after
        startDate.min = threeMonthsBefore.toISOString().split('T')[0];
        startDate.max = threeMonthsAfter.toISOString().split('T')[0];
        endDate.min = threeMonthsBefore.toISOString().split('T')[0];
        endDate.max = threeMonthsAfter.toISOString().split('T')[0];
        
        // Set default values: start of current month and end of current month
        startDate.value = firstDayOfMonth.toISOString().split('T')[0];
        endDate.value = lastDayOfMonth.toISOString().split('T')[0];
        
        // Set payment date to today
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const todayFormatted = `${year}-${month}-${day}`;
        
        paymentDate.value = todayFormatted;
        
        console.log('📅 Date formatting details:', {
            originalDate: today,
            formatted: todayFormatted,
            inputValue: paymentDate.value
        });
        
        // Verify the payment date was set correctly
        if (!paymentDate.value) {
            console.error('❌ Failed to set payment date value');
        } else {
            console.log('✅ Payment date set successfully:', {
                originalDate: today,
                formattedDate: todayFormatted,
                actualValue: paymentDate.value,
                inputType: paymentDate.type,
                validity: paymentDate.validity
            });
        }
        
        console.log('📅 Default dates set:', {
            startDate: startDate.value,
            endDate: endDate.value,
            paymentDate: paymentDate.value
        });
        
        // Remove any validation styling from payment date
        paymentDate.classList.remove('is-invalid', 'is-valid');
        paymentDate.style.border = '1px solid #ced4da';
        paymentDate.style.boxShadow = 'none';
        
        // Add date change event listeners
        setupDateChangeListeners();
    }
    
    // Setup date change event listeners
    function setupDateChangeListeners() {
        const startDate = document.getElementById('addPaymentStartDate');
        const endDate = document.getElementById('addPaymentEndDate');
        const paymentDate = document.getElementById('paymentDate');
        const payPeriod = document.getElementById('payPeriod');
        
        // When start date changes, update end date based on pay period
        if (startDate) {
            startDate.addEventListener('change', function() {
                console.log('📅 Start date changed to:', this.value);
                updateEndDateBasedOnPeriod();
            });
        }
        
        // When pay period changes, update end date
        if (payPeriod) {
            payPeriod.addEventListener('change', function() {
                console.log('📅 Pay period changed to:', this.value);
                updateEndDateBasedOnPeriod();
            });
        }
        
        // When end date changes, validate it's after start date
        if (endDate) {
            endDate.addEventListener('change', function() {
                console.log('📅 End date changed to:', this.value);
                validateDateRange();
            });
        }
        
        // When payment date changes, validate it's after end date
        if (paymentDate) {
            paymentDate.addEventListener('change', function() {
                console.log('📅 Payment date changed to:', this.value);
                validatePaymentDate();
            });
        }
    }
    
    // Update end date based on pay period
    function updateEndDateBasedOnPeriod() {
        const startDate = document.getElementById('addPaymentStartDate');
        const endDate = document.getElementById('addPaymentEndDate');
        const payPeriod = document.getElementById('payPeriod');
        
        if (!startDate || !endDate || !payPeriod || !startDate.value) return;
        
        const start = new Date(startDate.value);
        const period = payPeriod.value;
        
        // Set minimum end date to be the start date
        endDate.min = startDate.value;
        
        let end;
        switch (period) {
            case 'weekly':
                end = new Date(start);
                end.setDate(start.getDate() + 6); // 7 days including start date
                break;
            case 'bi-weekly':
                end = new Date(start);
                end.setDate(start.getDate() + 13); // 14 days including start date
                break;
            case 'monthly':
                end = new Date(start);
                end.setMonth(start.getMonth() + 1);
                end.setDate(0); // Last day of the month
                break;
            default:
                // Default to monthly
                end = new Date(start);
                end.setMonth(start.getMonth() + 1);
                end.setDate(0);
        }
        
        // Only update end date if it's currently empty or before start date
        if (!endDate.value || new Date(endDate.value) < start) {
            endDate.value = end.toISOString().split('T')[0];
        }
        
        console.log('📅 Updated end date to:', endDate.value);
    }
    
    // Validate date range
    function validateDateRange() {
        const startDate = document.getElementById('addPaymentStartDate');
        const endDate = document.getElementById('addPaymentEndDate');
        
        if (!startDate || !endDate || !startDate.value || !endDate.value) return;
        
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);
        
        if (end < start) {
            console.warn('⚠️ End date is before start date');
            endDate.classList.add('is-invalid');
            endDate.setCustomValidity('End date must be after start date');
        } else {
            endDate.classList.remove('is-invalid');
            endDate.setCustomValidity('');
        }
    }
    
    // Validate payment date
    function validatePaymentDate() {
        const endDate = document.getElementById('addPaymentEndDate');
        const paymentDate = document.getElementById('paymentDate');
        
        if (!endDate || !paymentDate || !endDate.value || !paymentDate.value) return;
        
        const end = new Date(endDate.value);
        const payment = new Date(paymentDate.value);
        
        if (payment < end) {
            console.warn('⚠️ Payment date is before end date');
            paymentDate.classList.add('is-invalid');
            paymentDate.setCustomValidity('Payment date should be after end date');
        } else {
            paymentDate.classList.remove('is-invalid');
            paymentDate.setCustomValidity('');
        }
    }

    // Format date for HTML date input (YYYY-MM-DD)
    function formatDateForInput(dateString) {
        if (!dateString) return '';
        
        try {
            // If it's already in YYYY-MM-DD format, return as is
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
                return dateString;
            }
            
            // Parse the date and format it
            const date = new Date(dateString);
            
            // Check if date is valid
            if (isNaN(date.getTime())) {
                console.warn('Invalid date:', dateString);
                return '';
            }
            
            // Format as YYYY-MM-DD
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            
            return `${year}-${month}-${day}`;
        } catch (error) {
            console.error('Error formatting date:', dateString, error);
            return '';
        }
    }

    // Set date restrictions for edit modal
    function setEditModalDateRestrictions() {
        console.log('📅 Setting date restrictions for Edit Payment modal...');
        const today = new Date();
        const startDate = document.getElementById('editPaymentStartDate');
        const endDate = document.getElementById('editPaymentEndDate');
        const paymentDate = document.getElementById('editPaymentDate');
        
        if (!startDate || !endDate || !paymentDate) {
            console.error('❌ Edit modal date input elements not found');
            return;
        }
        
        // Set date restrictions - allow 3 months before and after
        const threeMonthsBefore = new Date(today.getFullYear(), today.getMonth() - 3, 1);
        const threeMonthsAfter = new Date(today.getFullYear(), today.getMonth() + 3, 0);
        
        // Set min/max attributes to allow 3 months before and after
        startDate.min = threeMonthsBefore.toISOString().split('T')[0];
        startDate.max = threeMonthsAfter.toISOString().split('T')[0];
        endDate.min = threeMonthsBefore.toISOString().split('T')[0];
        endDate.max = threeMonthsAfter.toISOString().split('T')[0];
        paymentDate.min = threeMonthsBefore.toISOString().split('T')[0];
        paymentDate.max = threeMonthsAfter.toISOString().split('T')[0];
        
        console.log('📅 Edit modal date restrictions set:', {
            startDateMin: startDate.min,
            startDateMax: startDate.max,
            endDateMin: endDate.min,
            endDateMax: endDate.max,
            paymentDateMin: paymentDate.min,
            paymentDateMax: paymentDate.max
        });
    }

    // Setup pay period change handler for edit modal
    function setupEditModalPayPeriodHandler() {
        console.log('📅 Setting up pay period handler for Edit Payment modal...');
        const payPeriod = document.getElementById('editPayPeriod');
        const startDate = document.getElementById('editPaymentStartDate');
        const endDate = document.getElementById('editPaymentEndDate');
        
        if (!payPeriod || !startDate || !endDate) {
            console.error('❌ Edit modal pay period or date elements not found');
            return;
        }
        
        // When pay period changes, update end date
        payPeriod.addEventListener('change', function() {
            console.log('📅 Edit modal pay period changed to:', this.value);
            updateEditModalEndDateBasedOnPeriod();
        });
        
        // When start date changes, update end date based on pay period
        startDate.addEventListener('change', function() {
            console.log('📅 Edit modal start date changed to:', this.value);
            updateEditModalEndDateBasedOnPeriod();
        });
    }

    // Update end date based on pay period for edit modal
    function updateEditModalEndDateBasedOnPeriod() {
        const startDate = document.getElementById('editPaymentStartDate');
        const endDate = document.getElementById('editPaymentEndDate');
        const payPeriod = document.getElementById('editPayPeriod');
        
        if (!startDate || !endDate || !payPeriod || !startDate.value || !payPeriod.value) {
            return;
        }
        
        const start = new Date(startDate.value);
        let end = new Date(start);
        
        switch (payPeriod.value) {
            case 'weekly':
                end.setDate(start.getDate() + 6); // 7 days total (start + 6)
                break;
            case 'bi-weekly':
                end.setDate(start.getDate() + 13); // 14 days total (start + 13)
                break;
            case 'monthly':
                end.setMonth(start.getMonth() + 1);
                end.setDate(0); // Last day of the month
                break;
            default:
                return;
        }
        
        endDate.value = end.toISOString().split('T')[0];
        console.log('📅 Edit modal end date updated to:', endDate.value);
    }

    // Handle form submission
    document.getElementById('submitPaymentBtn').addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('🚀 Form submission started');
        
        // No automatic date setting - let user enter whatever they want
        
        const form = document.getElementById('addPaymentForm');
        const submitBtn = document.getElementById('submitPaymentBtn');
        const submitSpinner = document.getElementById('submitSpinner');
        const submitText = document.getElementById('submitText');
        
        // Remove any validation styling from payment date
        const paymentDateField = document.getElementById('paymentDate');
        if (paymentDateField) {
            paymentDateField.classList.remove('is-invalid', 'is-valid');
            paymentDateField.style.border = '1px solid #ced4da';
        }
        
        // No validation - just submit the form
        console.log('🚀 Submitting form without validation');
        
        // Show loading state
        submitBtn.disabled = true;
        submitSpinner.classList.remove('d-none');
        submitText.textContent = 'Adding...';
        
        console.log('📝 Collecting form data...');
        
        // Collect form data
        const formData = new FormData(form);
        const payrollData = Object.fromEntries(formData.entries());
        
        // Convert numeric fields
        const numericFields = ['basic_salary', 'housing_allowance', 'transport_allowance', 'overtime', 'bonus', 'other_allowances', 'other_deductions'];
        numericFields.forEach(field => {
            payrollData[field] = parseFloat(payrollData[field]) || 0;
        });
        
        console.log('📝 Submitting payroll data:', payrollData);
        console.log('📝 Raw form data entries:', Array.from(formData.entries()));
        
        // Submit to backend
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        console.log('🌐 Making API request...');
        
        // Add timeout to prevent freezing
        const controller = new AbortController();
        const timeoutId = setTimeout(() => {
            controller.abort();
            console.log('⏰ Request timeout');
            showNotification('error', 'Request timed out. Please try again.');
            submitBtn.disabled = false;
            submitSpinner.classList.add('d-none');
            submitText.textContent = 'Add Payment';
        }, 30000); // 30 second timeout
        
        fetch('/company/hr/payroll', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payrollData),
            signal: controller.signal
        })
        .then(response => {
            console.log('📡 API response received:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response is not JSON. Server may be returning an error page.');
            }
            
            return response.json();
        })
        .then(data => {
            console.log('✅ API response data:', data);
            if (data && data.success) {
                console.log('✅ Payroll added successfully');
                
                // Show success message
                showNotification('success', 'Payroll added successfully!');
                
                // Reset form
                form.reset();
                form.classList.remove('was-validated');
                
                // Close modal and remove backdrop
                const modalElement = document.getElementById('addPaymentModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                } else {
                    // Fallback: manually hide modal
                    $(modalElement).modal('hide');
                }
                
                // Remove any remaining backdrop and fix scrolling
                setTimeout(() => {
                    // Remove all modal backdrops
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(backdrop => backdrop.remove());
                    
                    // Reset body classes and styles
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                    
                    // Don't force overflow auto - let it be natural
                    // document.body.style.overflow = 'auto';
                    // document.documentElement.style.overflow = 'auto';
                    // document.documentElement.style.height = 'auto';
                    
                    console.log('✅ Modal cleanup completed - page should be scrollable');
                }, 300);
                
                // Refresh payroll table and stats
                loadPayrollTable();
                loadPayrollStats();
            } else {
                // Handle validation errors
                if (data && data.errors) {
                    let errorMessage = 'Validation failed:\n';
                    Object.keys(data.errors).forEach(field => {
                        errorMessage += `• ${field}: ${data.errors[field].join(', ')}\n`;
                    });
                    showNotification('error', errorMessage);
                } else {
                    throw new Error(data?.message || 'Unknown error');
                }
            }
        })
        .catch(error => {
            console.error('❌ Error adding payroll:', error);
            
            let errorMessage = 'Failed to add payroll: ';
            if (error.message.includes('not JSON')) {
                errorMessage += 'Server error - please check the console for details.';
            } else if (error.message.includes('timeout')) {
                errorMessage += 'Request timed out. Please try again.';
            } else {
                errorMessage += error.message;
            }
            
            showNotification('error', errorMessage);
        })
        .finally(() => {
            console.log('🔄 Resetting button state');
            // Clear timeout
            clearTimeout(timeoutId);
            // Reset button state
            submitBtn.disabled = false;
            submitSpinner.classList.add('d-none');
            submitText.textContent = 'Add Payment';
        });
    });

    // SweetAlert2 Notification (same style as other tabs)
    function showNotification(type, message) {
        Swal.fire({
            icon: type,
            title: type.charAt(0).toUpperCase() + type.slice(1),
            text: message,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    // ===== STATS AND TABLE FUNCTIONALITY =====
    
    // Load payroll stats
    function loadPayrollStats() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        fetch('/company/hr/payroll/stats', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                updateStatsDisplay(data.data);
            } else {
                console.error('Failed to load stats:', data?.message);
                setDefaultStats();
            }
        })
        .catch(error => {
            console.error('Error loading stats:', error);
            setDefaultStats();
        });
    }

    // Update stats display
    function updateStatsDisplay(stats) {
        // Monthly Payroll
        const monthlyPayroll = document.getElementById('monthlyPayrollAmount');
        const monthlyChange = document.getElementById('monthlyPayrollChange');
        if (monthlyPayroll) {
            monthlyPayroll.textContent = `₵${(stats.total_payroll || 0).toLocaleString()}`;
        }
        if (monthlyChange) {
            const change = stats.payroll_change || 0;
            monthlyChange.innerHTML = `<i class="fas fa-arrow-${change >= 0 ? 'up' : 'down'} me-1"></i> ${Math.abs(change).toFixed(1)}%`;
        }

        // Total Employees
        const totalEmployees = document.getElementById('totalEmployees');
        const newEmployees = document.getElementById('newEmployeesThisMonth');
        if (totalEmployees) {
            totalEmployees.textContent = stats.total_employees || 0;
        }
        if (newEmployees) {
            newEmployees.innerHTML = `<i class="fas fa-user-plus me-1"></i> ${stats.new_employees_this_month || 0}`;
        }

        // Average Salary
        const avgSalary = document.getElementById('avgSalary');
        const salaryGrowth = document.getElementById('avgSalaryGrowth');
        if (avgSalary) {
            avgSalary.textContent = `₵${(stats.avg_salary || 0).toLocaleString()}`;
        }
        if (salaryGrowth) {
            const growth = stats.salary_growth || 0;
            salaryGrowth.innerHTML = `<i class="fas fa-arrow-${growth >= 0 ? 'up' : 'down'} me-1"></i> ${Math.abs(growth).toFixed(1)}%`;
        }

        // Taxes & SSNIT
        const taxesAmount = document.getElementById('taxesAmount');
        if (taxesAmount) {
            taxesAmount.textContent = `₵${(stats.total_taxes || 0).toLocaleString()}`;
        }
    }

    // Set default stats when loading fails
    function setDefaultStats() {
        const elements = [
            'monthlyPayrollAmount', 'totalEmployees', 'avgSalary', 'taxesAmount'
        ];
        elements.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                if (id === 'totalEmployees') {
                    element.textContent = '0';
                } else {
                    element.textContent = '₵0';
                }
            }
        });
    }

    // Payroll pagination object to avoid variable conflicts
    const payrollTablePagination = {
        currentPage: 1,
        perPage: 10,
        totalRecords: 0,
        lastPage: 1
    };

    // Load payroll table data
    function loadPayrollTable(page = 1, perPageValue = null) {
        // If perPageValue is not provided, get it from the dropdown
        console.log("cccccccccccccccccccccc",perPageValue);
        if (perPageValue === null) {
            const perPageSelect = document.getElementById('perPageSelect');
            perPageValue = perPageSelect ? parseInt(perPageSelect.value) : 5;
            console.log('🔄 Using dropdown value for per-page:', perPageValue, 'Dropdown value:', perPageSelect ? perPageSelect.value : 'not found');
        }
        
        console.log('📊 Loading payroll table - Page:', page, 'Per Page:', perPageValue);
        payrollTablePagination.currentPage = page;
        payrollTablePagination.perPage = perPageValue;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        // Show loading state
        const tableBody = document.getElementById('payrollTableBody');
        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2">Loading payroll data...</div>
                    </td>
                </tr>
            `;
        }
        
        const requestData = {
            page: page,
            per_page: perPageValue
        };
        
        console.log('📤 Sending request to backend:', requestData);
        
        fetch('/company/hr/payroll/all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('📥 Backend response:', data);
            console.log('📊 Items returned:', data.data ? data.data.length : 'No data');
            console.log('📄 Pagination info:', data.pagination);
            
            if (data && data.success && Array.isArray(data.data)) {
                renderPayrollTable(data.data);
                if (data.pagination) {
                    updatePagination(data.pagination);
                }
            } else {
                throw new Error('Invalid response data');
            }
        })
        .catch(error => {
            console.error('Error loading payroll table:', error);
            if (tableBody) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted mb-2">Error Loading Data</h5>
                                <p class="text-muted mb-0">Failed to load payroll records.</p>
                                <button class="btn btn-outline-primary btn-sm mt-2" onclick="loadPayrollTable()">
                                    <i class="fas fa-refresh me-1"></i> Try Again
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }
        });
    }

    // Render payroll table
    function renderPayrollTable(payrolls) {
        console.log('🔄 renderPayrollTable called with:', payrolls);
        console.log('📊 Number of payroll items to render:', payrolls ? payrolls.length : 'No data');
        
        const tableBody = document.getElementById('payrollTableBody');
        console.log('🔄 Table body element:', tableBody);
        
        if (!tableBody) {
            console.error('❌ Table body element not found!');
            return;
        }

        if (payrolls.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted mb-2">No Payroll Records</h5>
                            <p class="text-muted mb-0">No payroll records found for the selected period.</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        console.log('🔄 About to render table with', payrolls.length, 'items');
        
        tableBody.innerHTML = payrolls.map(payroll => {
            const allowances = parseFloat(payroll.housing_allowance || 0) + parseFloat(payroll.transport_allowance || 0) + parseFloat(payroll.overtime || 0) + parseFloat(payroll.bonus || 0) + parseFloat(payroll.other_allowances || 0);
            const deductions = parseFloat(payroll.ssnit || 0) + parseFloat(payroll.paye || 0) + parseFloat(payroll.tier2_pension || 0) + parseFloat(payroll.other_deductions || 0);
            
            const statusClass = {
                'paid': 'success',
                'pending': 'warning',
                'draft': 'secondary',
                'approved': 'info',
                'rejected': 'danger'
            }[payroll.status] || 'secondary';

            return `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(payroll.employee?.first_name || '')}+${encodeURIComponent(payroll.employee?.last_name || '')}&background=4e73df&color=fff" 
                                 class="rounded-circle me-2" width="32" height="32" alt="${payroll.employee?.first_name || ''} ${payroll.employee?.last_name || ''}">
                            <div>
                                <h6 class="mb-0">${payroll.employee?.first_name || ''} ${payroll.employee?.last_name || ''}</h6>
                                <small class="text-muted">${payroll.employee?.staff_id || 'N/A'}</small>
                            </div>
                        </div>
                    </td>
                    <td>₵${(payroll.basic_salary || 0).toLocaleString()}</td>
                    <td>₵${allowances.toLocaleString()}</td>
                    <td>₵${deductions.toLocaleString()}</td>
                    <td><strong>₵${(payroll.net_pay || 0).toLocaleString()}</strong></td>
                    <td>
                        <span class="badge bg-${statusClass}">${(payroll.status || 'draft').charAt(0).toUpperCase() + (payroll.status || 'draft').slice(1)}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-outline-primary" onclick="viewPayroll(${payroll.id})" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="editPayroll(${payroll.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" onclick="printPayslip(${payroll.id})" title="Print Payslip">
                                <i class="fas fa-print"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deletePayroll(${payroll.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
        
        console.log('✅ Table rendered successfully with', payrolls.length, 'items');
        console.log('🔄 Table body innerHTML length:', tableBody.innerHTML.length);
    }

    // Action functions
    function viewPayroll(id) {
        console.log('View payroll:', id);
        
        // Show loading state
        const modalBody = document.querySelector('#viewPayrollModal .modal-body');
        if (modalBody) {
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2">Loading payroll details...</div>
                </div>
            `;
        }
        
        // Show modal
        const modalElement = document.getElementById('viewPayrollModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            console.error('View payroll modal not found');
            return;
        }
        
        // Fetch payroll details
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        fetch(`/company/hr/payroll/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                renderPayrollDetails(data.data);
            } else {
                throw new Error(data?.message || 'Failed to load payroll details');
            }
        })
        .catch(error => {
            console.error('Error loading payroll details:', error);
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h5 class="text-danger">Error Loading Details</h5>
                        <p class="text-muted">Failed to load payroll details: ${error.message}</p>
                        <button class="btn btn-outline-primary btn-sm" onclick="viewPayroll(${id})">
                            <i class="fas fa-refresh me-1"></i> Try Again
                        </button>
                    </div>
                `;
            }
        });
    }
    
    // Render payroll details in modal
    function renderPayrollDetails(payroll) {
        const modalBody = document.querySelector('#viewPayrollModal .modal-body');
        if (!modalBody) return;
        
        const allowances = parseFloat(payroll.housing_allowance || 0) + parseFloat(payroll.transport_allowance || 0) + parseFloat(payroll.overtime || 0) + parseFloat(payroll.bonus || 0) + parseFloat(payroll.other_allowances || 0);
        const deductions = parseFloat(payroll.ssnit || 0) + parseFloat(payroll.paye || 0) + parseFloat(payroll.tier2_pension || 0) + parseFloat(payroll.other_deductions || 0);
        
        const statusClass = {
            'paid': 'success',
            'pending': 'warning',
            'draft': 'secondary',
            'approved': 'info',
            'rejected': 'danger'
        }[payroll.status] || 'secondary';
        
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Employee Information</h6>
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(payroll.employee?.first_name || '')}+${encodeURIComponent(payroll.employee?.last_name || '')}&background=4e73df&color=fff" 
                             class="rounded-circle me-3" width="60" height="60" alt="Employee">
                        <div>
                            <h5 class="mb-1">${payroll.employee?.first_name || ''} ${payroll.employee?.last_name || ''}</h5>
                            <p class="text-muted mb-0">${payroll.employee?.staff_id || 'N/A'}</p>
                            <span class="badge bg-${statusClass}">${(payroll.status || 'draft').charAt(0).toUpperCase() + (payroll.status || 'draft').slice(1)}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Pay Period</h6>
                    <p class="mb-1"><strong>Period:</strong> ${(payroll.pay_period || '').charAt(0).toUpperCase() + (payroll.pay_period || '').slice(1)}</p>
                    <p class="mb-1"><strong>Start Date:</strong> ${new Date(payroll.start_date).toLocaleDateString()}</p>
                    <p class="mb-1"><strong>End Date:</strong> ${payroll.end_date ? new Date(payroll.end_date).toLocaleDateString() : 'N/A'}</p>
                    <p class="mb-0"><strong>Payment Date:</strong> ${new Date(payroll.payment_date).toLocaleDateString()}</p>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Earnings</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td>Basic Salary:</td>
                                    <td class="text-end">₵${(payroll.basic_salary || 0).toLocaleString()}</td>
                                </tr>
                                <tr>
                                    <td>Housing Allowance:</td>
                                    <td class="text-end">₵${(payroll.housing_allowance || 0).toLocaleString()}</td>
                                </tr>
                                <tr>
                                    <td>Transport Allowance:</td>
                                    <td class="text-end">₵${(payroll.transport_allowance || 0).toLocaleString()}</td>
                                </tr>
                                <tr>
                                    <td>Overtime:</td>
                                    <td class="text-end">₵${(payroll.overtime || 0).toLocaleString()}</td>
                                </tr>
                                <tr>
                                    <td>Bonus:</td>
                                    <td class="text-end">₵${(payroll.bonus || 0).toLocaleString()}</td>
                                </tr>
                                <tr>
                                    <td>Other Allowances:</td>
                                    <td class="text-end">₵${(payroll.other_allowances || 0).toLocaleString()}</td>
                                </tr>
                                <tr class="table-light">
                                    <td><strong>Total Allowances:</strong></td>
                                    <td class="text-end"><strong>₵${allowances.toLocaleString()}</strong></td>
                                </tr>
                                <tr class="table-primary">
                                    <td><strong>Gross Pay:</strong></td>
                                    <td class="text-end"><strong>₵${(payroll.gross_pay || 0).toLocaleString()}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Deductions</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td>SSNIT (5.5%):</td>
                                    <td class="text-end">₵${(payroll.ssnit || 0).toLocaleString()}</td>
                                </tr>
                                <tr>
                                    <td>PAYE:</td>
                                    <td class="text-end">₵${(payroll.paye || 0).toLocaleString()}</td>
                                </tr>
                                <tr>
                                    <td>Tier 2 Pension (5%):</td>
                                    <td class="text-end">₵${(payroll.tier2_pension || 0).toLocaleString()}</td>
                                </tr>
                                <tr>
                                    <td>Other Deductions:</td>
                                    <td class="text-end">₵${(payroll.other_deductions || 0).toLocaleString()}</td>
                                </tr>
                                <tr class="table-light">
                                    <td><strong>Total Deductions:</strong></td>
                                    <td class="text-end"><strong>₵${deductions.toLocaleString()}</strong></td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>Net Pay:</strong></td>
                                    <td class="text-end"><strong>₵${(payroll.net_pay || 0).toLocaleString()}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            ${payroll.notes ? `
                <hr class="my-4">
                <h6 class="text-muted mb-3">Notes</h6>
                <p class="text-muted">${payroll.notes}</p>
            ` : ''}
        `;
    }

    function editPayroll(id) {
        console.log('Edit payroll:', id);
        
        // Show loading state
        const modalBody = document.querySelector('#addPaymentModal .modal-body');
        if (modalBody) {
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2">Loading payroll for editing...</div>
                </div>
            `;
        }
        
        // Show modal
        const modalElement = document.getElementById('addPaymentModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            console.error('Add payment modal not found');
            return;
        }
        
        // Fetch payroll details
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        fetch(`/company/hr/payroll/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('📊 Payroll data received for edit:', data);
            console.log('📊 Full data object:', JSON.stringify(data, null, 2));
            if (data && data.success) {
                console.log('📊 Payroll data.data:', data.data);
                console.log('📊 Payroll pay_period specifically:', data.data?.pay_period);
                console.log('📊 Payroll pay_period type:', typeof data.data?.pay_period);
                populateEditForm(data.data);
            } else {
                throw new Error(data?.message || 'Failed to load payroll details');
            }
        })
        .catch(error => {
            console.error('Error loading payroll for edit:', error);
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h5 class="text-danger">Error Loading Payroll</h5>
                        <p class="text-muted">Failed to load payroll details: ${error.message}</p>
                        <button class="btn btn-outline-primary btn-sm" onclick="editPayroll(${id})">
                            <i class="fas fa-refresh me-1"></i> Try Again
                        </button>
                    </div>
                `;
            }
        });
    }
    
    // Populate edit form with payroll data
    function populateEditForm(payroll) {
        console.log('📝 Populating edit form with payroll data:', payroll);
        console.log('📝 Full payroll object:', JSON.stringify(payroll, null, 2));
        console.log('📅 Date fields in payroll data:', {
            start_date: payroll.start_date,
            end_date: payroll.end_date,
            payment_date: payroll.payment_date
        });
        console.log('📅 Pay period field details:', {
            pay_period: payroll.pay_period,
            pay_period_type: typeof payroll.pay_period,
            pay_period_length: payroll.pay_period?.length,
            pay_period_trimmed: payroll.pay_period?.trim()
        });
        
        // Restore the original form HTML
        const modalBody = document.querySelector('#addPaymentModal .modal-body');
        modalBody.innerHTML = `
            <form id="addPaymentForm">
                <div class="mb-3">
                    <label class="form-label">Employee</label>
                    <select class="form-select" id="employeeSelect" name="employee_id" required>
                        <option value="">Select Employee</option>
                    </select>
                    <div class="invalid-feedback">Please select an employee.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pay Period</label>
                    <select class="form-select" id="editPayPeriod" name="pay_period" required>
                        <option value="">Select Pay Period</option>
                        <option value="monthly">Monthly</option>
                        <option value="bi-weekly">Bi-Weekly</option>
                        <option value="weekly">Weekly</option>
                    </select>
                    <div class="invalid-feedback">Please select a pay period.</div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="editPaymentStartDate" name="start_date" required>
                        <div class="invalid-feedback">Please select a start date.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" id="editPaymentEndDate" name="end_date">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" class="form-control" id="editPaymentDate" name="payment_date">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Basic Salary</label>
                        <div class="input-group">
                            <span class="input-group-text">₵</span>
                            <input type="number" class="form-control" id="basicSalary" name="basic_salary" step="0.01" min="0" required>
                        </div>
                        <div class="invalid-feedback">Please enter a valid basic salary.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Housing Allowance</label>
                        <div class="input-group">
                            <span class="input-group-text">₵</span>
                            <input type="number" class="form-control" id="housingAllowance" name="housing_allowance" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Transport Allowance</label>
                        <div class="input-group">
                            <span class="input-group-text">₵</span>
                            <input type="number" class="form-control" id="transportAllowance" name="transport_allowance" step="0.01" min="0" value="0">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Overtime</label>
                        <div class="input-group">
                            <span class="input-group-text">₵</span>
                            <input type="number" class="form-control" id="overtime" name="overtime" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bonus</label>
                        <div class="input-group">
                            <span class="input-group-text">₵</span>
                            <input type="number" class="form-control" id="bonus" name="bonus" step="0.01" min="0" value="0">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Other Allowances</label>
                        <div class="input-group">
                            <span class="input-group-text">₵</span>
                            <input type="number" class="form-control" id="otherAllowances" name="other_allowances" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Other Deductions</label>
                        <div class="input-group">
                            <span class="input-group-text">₵</span>
                            <input type="number" class="form-control" id="otherDeductions" name="other_deductions" step="0.01" min="0" value="0">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Enter any additional notes"></textarea>
                </div>
            </form>
        `;
        
        // Load employees and populate form
        loadEmployees().then(() => {
            // Add a small delay to ensure DOM is fully updated
            setTimeout(() => {
                // Populate form fields
                const employeeSelect = document.getElementById('employeeSelect');
                if (employeeSelect) employeeSelect.value = payroll.employee_id;
                
                const payPeriod = document.getElementById('editPayPeriod');
                if (payPeriod) {
                    console.log('📅 Pay period element found:', payPeriod);
                    console.log('📅 Available options before setting:', Array.from(payPeriod.options).map(opt => ({ value: opt.value, text: opt.text, selected: opt.selected })));
                    console.log('📅 Setting pay period to:', payroll.pay_period);
                    console.log('📅 Pay period value type:', typeof payroll.pay_period);
                    console.log('📅 Pay period value length:', payroll.pay_period?.length);
                    
                    // Try multiple approaches to set the value
                    console.log('📅 Attempting to set value directly...');
                    payPeriod.value = payroll.pay_period;
                    
                    // If direct setting didn't work, try finding the option and setting selectedIndex
                    if (payPeriod.value !== payroll.pay_period) {
                        console.log('📅 Direct value setting failed, trying selectedIndex approach...');
                        const targetOption = Array.from(payPeriod.options).find(opt => opt.value === payroll.pay_period);
                        if (targetOption) {
                            console.log('📅 Found matching option:', targetOption);
                            payPeriod.selectedIndex = targetOption.index;
                        } else {
                            console.error('❌ No matching option found for value:', payroll.pay_period);
                            console.log('📅 Available values:', Array.from(payPeriod.options).map(opt => opt.value));
                        }
                    }
                    
                    console.log('📅 Pay period value after setting:', payPeriod.value);
                    console.log('📅 Pay period selectedIndex:', payPeriod.selectedIndex);
                    console.log('📅 Pay period options after setting:', Array.from(payPeriod.options).map(opt => ({ value: opt.value, text: opt.text, selected: opt.selected })));
                    
                    // Force trigger change event to ensure any listeners are notified
                    payPeriod.dispatchEvent(new Event('change', { bubbles: true }));
                    console.log('📅 Change event dispatched');
                } else {
                    console.error('❌ Pay period element not found');
                }
                
                const startDate = document.getElementById('editPaymentStartDate');
                if (startDate) {
                    // Format date for HTML date input (YYYY-MM-DD)
                    const formattedStartDate = formatDateForInput(payroll.start_date);
                    startDate.value = formattedStartDate;
                    console.log('📅 Setting start date:', formattedStartDate, 'from:', payroll.start_date);
                }
                
                const endDate = document.getElementById('editPaymentEndDate');
                if (endDate) {
                    // Format date for HTML date input (YYYY-MM-DD)
                    const formattedEndDate = payroll.end_date ? formatDateForInput(payroll.end_date) : '';
                    endDate.value = formattedEndDate;
                    console.log('📅 Setting end date:', formattedEndDate, 'from:', payroll.end_date);
                }
                
                const paymentDate = document.getElementById('editPaymentDate');
                if (paymentDate) {
                    // Format date for HTML date input (YYYY-MM-DD)
                    const formattedPaymentDate = formatDateForInput(payroll.payment_date);
                    paymentDate.value = formattedPaymentDate;
                    console.log('📅 Setting payment date:', formattedPaymentDate, 'from:', payroll.payment_date);
                }
                
                const basicSalary = document.getElementById('basicSalary');
                if (basicSalary) basicSalary.value = payroll.basic_salary;
                
                const housingAllowance = document.getElementById('housingAllowance');
                if (housingAllowance) housingAllowance.value = payroll.housing_allowance || 0;
                
                const transportAllowance = document.getElementById('transportAllowance');
                if (transportAllowance) transportAllowance.value = payroll.transport_allowance || 0;
                
                const overtime = document.getElementById('overtime');
                if (overtime) overtime.value = payroll.overtime || 0;
                
                const bonus = document.getElementById('bonus');
                if (bonus) bonus.value = payroll.bonus || 0;
                
                const otherAllowances = document.getElementById('otherAllowances');
                if (otherAllowances) otherAllowances.value = payroll.other_allowances || 0;
                
                const otherDeductions = document.getElementById('otherDeductions');
                if (otherDeductions) otherDeductions.value = payroll.other_deductions || 0;
                
                const notes = document.getElementById('notes');
                if (notes) notes.value = payroll.notes || '';
                
                // Set up date restrictions for edit modal
                setEditModalDateRestrictions();
                
                // Set up pay period change handler for edit modal
                setupEditModalPayPeriodHandler();
                
                // Update modal title and button
                const modalTitle = document.querySelector('#addPaymentModal .modal-title');
                if (modalTitle) modalTitle.textContent = 'Edit Payroll';
                
                const submitText = document.getElementById('submitText');
                if (submitText) submitText.textContent = 'Update Payroll';
                
                // Store payroll ID for update
                const form = document.getElementById('addPaymentForm');
                if (form) form.setAttribute('data-payroll-id', payroll.id);
            }, 100); // 100ms delay to ensure DOM is fully updated
        }).catch(error => {
            console.error('Error in populateEditForm:', error);
        });
    }

    // Load logo as base64 for print compatibility
    function loadLogoAsBase64() {
        return new Promise((resolve) => {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = this.width;
                canvas.height = this.height;
                ctx.drawImage(this, 0, 0);
                const dataURL = canvas.toDataURL('image/png');
                resolve(dataURL);
            };
            img.onerror = function() {
                // Fallback to text if image fails
                resolve(null);
            };
            img.src = `${window.location.origin}/images/gesl_logo.png`;
        });
    }

    // Custom print function for payslip
    function printPayslipContent() {
        const payslipContent = document.getElementById('payslipContent');
        if (!payslipContent) {
            console.error('Payslip content not found');
            return;
        }

        // Create a new window for printing
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Payslip</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        background: white;
                        color: black;
                        line-height: 1.4;
                    }
                    .payslip-container {
                        width: 100%;
                        max-width: 800px;
                        margin: 0 auto;
                        padding: 20px;
                        background: white;
                    }
                    .payslip-header {
                        text-align: center;
                        border-bottom: 2px solid #007bff;
                        padding-bottom: 15px;
                        margin-bottom: 20px;
                    }
                    .payslip-header img {
                        max-height: 60px;
                        margin-bottom: 10px;
                    }
                    .payslip-header h3 {
                        margin: 10px 0 5px 0;
                        color: #333;
                    }
                    .payslip-header p {
                        margin: 2px 0;
                        color: #666;
                    }
                    .payslip-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 20px 0;
                        font-size: 14px;
                    }
                    .payslip-table th,
                    .payslip-table td {
                        border: 1px solid #333;
                        padding: 8px 12px;
                        text-align: left;
                    }
                    .payslip-table th {
                        background-color: #f8f9fa;
                        font-weight: 600;
                    }
                    .payslip-table .text-end {
                        text-align: right;
                    }
                    .total-row {
                        background-color: #e3f2fd !important;
                        font-weight: bold;
                    }
                    .net-pay-row {
                        background-color: #c8e6c9 !important;
                        font-weight: bold;
                        font-size: 16px;
                    }
                    .row {
                        display: flex;
                        margin-bottom: 15px;
                    }
                    .col-md-6 {
                        flex: 1;
                        padding: 0 10px;
                    }
                    .text-center {
                        text-align: center;
                    }
                    .text-muted {
                        color: #666;
                    }
                    .mb-1 { margin-bottom: 5px; }
                    .mb-2 { margin-bottom: 10px; }
                    .mb-3 { margin-bottom: 15px; }
                    .mb-4 { margin-bottom: 20px; }
                    .mt-4 { margin-top: 20px; }
                    .small { font-size: 12px; }
                    .badge {
                        display: inline-block;
                        padding: 4px 8px;
                        font-size: 12px;
                        font-weight: bold;
                        border-radius: 4px;
                    }
                    .bg-success {
                        background-color: #28a745;
                        color: white;
                    }
                </style>
            </head>
            <body>
                ${payslipContent.outerHTML}
            </body>
            </html>
        `);
        
        printWindow.document.close();
        
        // Wait for content to load, then print
        printWindow.onload = function() {
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        };
    }

    async function printPayslip(id) {
        console.log('Print payslip:', id);
        
        // Show loading state
        const modalBody = document.querySelector('#viewPayslipModal .modal-body');
        if (modalBody) {
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2">Loading payslip...</div>
                </div>
            `;
        }
        
        // Show modal
        const modalElement = document.getElementById('viewPayslipModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            console.error('View payslip modal not found');
            return;
        }
        
        // Fetch payroll details
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        fetch(`/company/hr/payroll/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(async data => {
            if (data && data.success) {
                await renderPayslip(data.data);
            } else {
                throw new Error(data?.message || 'Failed to load payslip');
            }
        })
        .catch(error => {
            console.error('Error loading payslip:', error);
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h5 class="text-danger">Error Loading Payslip</h5>
                        <p class="text-muted">Failed to load payslip: ${error.message}</p>
                        <button class="btn btn-outline-primary btn-sm" onclick="printPayslip(${id})">
                            <i class="fas fa-refresh me-1"></i> Try Again
                        </button>
                    </div>
                `;
            }
        });
    }
    
    // Render payslip for printing
    async function renderPayslip(payroll) {
        const modalBody = document.querySelector('#viewPayslipModal .modal-body');
        if (!modalBody) return;
        
        // Load logo as base64
        const logoBase64 = await loadLogoAsBase64();
        
        const allowances = parseFloat(payroll.housing_allowance || 0) + parseFloat(payroll.transport_allowance || 0) + parseFloat(payroll.overtime || 0) + parseFloat(payroll.bonus || 0) + parseFloat(payroll.other_allowances || 0);
        const deductions = parseFloat(payroll.ssnit || 0) + parseFloat(payroll.paye || 0) + parseFloat(payroll.tier2_pension || 0) + parseFloat(payroll.other_deductions || 0);
        
        modalBody.innerHTML = `
            <style>
                .payslip-container {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .payslip-header {
                    border-bottom: 2px solid #007bff;
                    padding-bottom: 15px;
                    margin-bottom: 20px;
                }
                .payslip-table {
                    font-size: 14px;
                }
                .payslip-table th {
                    background-color: #f8f9fa;
                    font-weight: 600;
                    border: 1px solid #dee2e6;
                }
                .payslip-table td {
                    border: 1px solid #dee2e6;
                    padding: 8px 12px;
                }
                .total-row {
                    background-color: #e3f2fd !important;
                    font-weight: bold;
                }
                .net-pay-row {
                    background-color: #c8e6c9 !important;
                    font-weight: bold;
                    font-size: 16px;
                }
                @media print {
                    * {
                        -webkit-print-color-adjust: exact !important;
                        color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                    body * {
                        visibility: hidden;
                    }
                    .payslip-container, .payslip-container * {
                        visibility: visible;
                    }
                    .payslip-container {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100% !important;
                        height: 100% !important;
                        margin: 0 !important;
                        padding: 20px !important;
                        box-shadow: none !important;
                        background: white !important;
                        border: none !important;
                        border-radius: 0 !important;
                    }
                    .no-print {
                        display: none !important;
                    }
                    .modal-backdrop {
                        display: none !important;
                    }
                    .modal {
                        background: transparent !important;
                    }
                    .modal-dialog {
                        margin: 0 !important;
                        max-width: none !important;
                    }
                    .modal-content {
                        border: none !important;
                        box-shadow: none !important;
                        background: transparent !important;
                    }
                }
            </style>
            <div class="payslip-container" id="payslipContent">
                <div class="payslip-header text-center">
                    <div class="mb-3">
                        ${logoBase64 ? 
                            `<img src="${logoBase64}" alt="Company Logo" style="max-height: 60px; margin-bottom: 10px;">` :
                            `<div style="font-size: 24px; font-weight: bold; color: #007bff; margin-bottom: 10px;">GESL</div>`
                        }
                    </div>
                    <h3 class="mb-1">PAYSLIP</h3>
                    <p class="text-muted mb-0">${(payroll.pay_period || '').charAt(0).toUpperCase() + (payroll.pay_period || '').slice(1)} Pay Period</p>
                    <p class="text-muted">${new Date(payroll.start_date).toLocaleDateString()} - ${payroll.end_date ? new Date(payroll.end_date).toLocaleDateString() : 'N/A'}</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Employee Information</h6>
                        <p class="mb-1"><strong>Name:</strong> ${payroll.employee?.first_name || ''} ${payroll.employee?.last_name || ''}</p>
                        <p class="mb-1"><strong>Staff ID:</strong> ${payroll.employee?.staff_id || 'N/A'}</p>
                        <p class="mb-0"><strong>Payment Date:</strong> ${new Date(payroll.payment_date).toLocaleDateString()}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h6 class="text-muted mb-2">Payroll Details</h6>
                        <p class="mb-1"><strong>Period:</strong> ${(payroll.pay_period || '').charAt(0).toUpperCase() + (payroll.pay_period || '').slice(1)}</p>
                        <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">${(payroll.status || 'draft').charAt(0).toUpperCase() + (payroll.status || 'draft').slice(1)}</span></p>
                        <p class="mb-0"><strong>Generated:</strong> ${new Date().toLocaleDateString()}</p>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered payslip-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Amount (₵)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>EARNINGS</strong></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Basic Salary</td>
                                <td class="text-end">₵${parseFloat(payroll.basic_salary || 0).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <td>Housing Allowance</td>
                                <td class="text-end">₵${parseFloat(payroll.housing_allowance || 0).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <td>Transport Allowance</td>
                                <td class="text-end">₵${parseFloat(payroll.transport_allowance || 0).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <td>Overtime</td>
                                <td class="text-end">₵${parseFloat(payroll.overtime || 0).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <td>Bonus</td>
                                <td class="text-end">₵${parseFloat(payroll.bonus || 0).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <td>Other Allowances</td>
                                <td class="text-end">₵${parseFloat(payroll.other_allowances || 0).toLocaleString()}</td>
                            </tr>
                            <tr class="total-row">
                                <td><strong>Total Earnings</strong></td>
                                <td class="text-end"><strong>₵${parseFloat(payroll.gross_pay || 0).toLocaleString()}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>DEDUCTIONS</strong></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>SSNIT (5.5%)</td>
                                <td class="text-end">₵${parseFloat(payroll.ssnit || 0).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <td>PAYE</td>
                                <td class="text-end">₵${parseFloat(payroll.paye || 0).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <td>Tier 2 Pension (5%)</td>
                                <td class="text-end">₵${parseFloat(payroll.tier2_pension || 0).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <td>Other Deductions</td>
                                <td class="text-end">₵${parseFloat(payroll.other_deductions || 0).toLocaleString()}</td>
                            </tr>
                            <tr class="total-row">
                                <td><strong>Total Deductions</strong></td>
                                <td class="text-end"><strong>₵${deductions.toLocaleString()}</strong></td>
                            </tr>
                            <tr class="net-pay-row">
                                <td><strong>NET PAY</strong></td>
                                <td class="text-end"><strong>₵${parseFloat(payroll.net_pay || 0).toLocaleString()}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 text-center">
                    <p class="mb-1">Thank you for your hard work!</p>
                    <p class="text-muted small">This is a system generated payslip. No signature required.</p>
                </div>
            </div>
        `;
    }

    function deletePayroll(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deletePayrollRecord(id);
            }
        });
    }
    
    // Delete payroll record
    function deletePayrollRecord(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        fetch(`/company/hr/payroll/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                showNotification('success', 'Payroll record deleted successfully!');
                // Refresh table and stats
                loadPayrollTable();
                loadPayrollStats();
            } else {
                throw new Error(data?.message || 'Failed to delete payroll record');
            }
        })
        .catch(error => {
            console.error('Error deleting payroll:', error);
            showNotification('error', 'Failed to delete payroll record: ' + error.message);
        });
    }

    // Update pagination display
    function updatePagination(paginationData) {
        payrollTablePagination.totalRecords = paginationData.total;
        payrollTablePagination.lastPage = paginationData.last_page;
        
        // Update per-page dropdown to reflect current value
        const perPageSelect = document.getElementById('perPageSelect');
        if (perPageSelect && paginationData.per_page) {
            perPageSelect.value = paginationData.per_page;
            console.log('🔄 Updated per-page dropdown to:', paginationData.per_page);
        }
        
        // Update pagination info
        const fromEl = document.getElementById('paginationFrom');
        const toEl = document.getElementById('paginationTo');
        const totalEl = document.getElementById('paginationTotal');
        
        if (fromEl) fromEl.textContent = paginationData.from || 0;
        if (toEl) toEl.textContent = paginationData.to || 0;
        if (totalEl) totalEl.textContent = paginationData.total || 0;
        
        // Generate pagination buttons
        const paginationNav = document.getElementById('paginationNav');
        if (!paginationNav) return;
        
        let paginationHTML = '';
        
        // Previous button
        paginationHTML += `
            <li class="page-item ${paginationData.current_page <= 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${paginationData.current_page - 1})" ${paginationData.current_page <= 1 ? 'tabindex="-1" aria-disabled="true"' : ''}>Previous</a>
            </li>
        `;
        
        // Page numbers
        const startPage = Math.max(1, paginationData.current_page - 2);
        const endPage = Math.min(paginationData.last_page, paginationData.current_page + 2);
        
        if (startPage > 1) {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(1)">1</a></li>`;
            if (startPage > 2) {
                paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <li class="page-item ${i === paginationData.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="goToPage(${i})">${i}</a>
                </li>
            `;
        }
        
        if (endPage < paginationData.last_page) {
            if (endPage < paginationData.last_page - 1) {
                paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${paginationData.last_page})">${paginationData.last_page}</a></li>`;
        }
        
        // Next button
        paginationHTML += `
            <li class="page-item ${paginationData.current_page >= paginationData.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${paginationData.current_page + 1})" ${paginationData.current_page >= paginationData.last_page ? 'tabindex="-1" aria-disabled="true"' : ''}>Next</a>
            </li>
        `;
        
        paginationNav.innerHTML = paginationHTML;
    }
    
    // Go to specific page
    function goToPage(page) {
        if (page < 1 || page > payrollTablePagination.lastPage || page === payrollTablePagination.currentPage) return;
        loadPayrollTable(page, payrollTablePagination.perPage);
    }
    
    // Change per page
    function changePerPage() {
        console.log('🔄 changePerPage function called!');
        
        const perPageSelect = document.getElementById('perPageSelect');
        console.log('🔄 Per page select element:', perPageSelect);
        
        if (!perPageSelect) {
            console.error('❌ Per page select element not found!');
            return;
        }
        
        const newPerPage = parseInt(perPageSelect.value);
        console.log('🔄 Changing per page to:', newPerPage);
        console.log('🔄 Current pagination state:', payrollTablePagination);
        
        // Update pagination state
        payrollTablePagination.perPage = newPerPage;
        payrollTablePagination.currentPage = 1;
        
        console.log('🔄 New pagination state:', payrollTablePagination);
        console.log('🔄 Calling loadPayrollTable with page=1, perPage=' + newPerPage);
        
        // Load table with new per-page value
        loadPayrollTable(1, newPerPage);
    }
    
    // Test function to manually trigger per-page change
    function testPerPageChange() {
        console.log('🧪 Manual test: Changing per page to 5');
        const select = document.getElementById('perPageSelect');
        if (select) {
            select.value = '5';
            console.log('🧪 Set dropdown value to 5, now calling changePerPage()');
            changePerPage();
        }
    }
    
    // Test function to manually change dropdown to 25
    function testPerPage25() {
        console.log('🧪 Manual test: Changing per page to 25');
        const select = document.getElementById('perPageSelect');
        if (select) {
            select.value = '25';
            console.log('🧪 Set dropdown value to 25, now calling changePerPage()');
            changePerPage();
        }
    }
    
    // Test function to check current dropdown value
    function checkDropdownValue() {
        const select = document.getElementById('perPageSelect');
        if (select) {
            console.log('🧪 Current dropdown value:', select.value);
            console.log('🧪 All dropdown options:', Array.from(select.options).map(opt => ({value: opt.value, selected: opt.selected})));
        } else {
            console.log('🧪 Dropdown not found');
        }
    }
    
    // Test function to manually load table with specific per-page
    function testLoadTable() {
        console.log('🧪 Manual test: Loading table with 5 items per page');
        loadPayrollTable(1, 5);
    }
    
    // Debug function to test backend per-page parameter
    function debugBackendPerPage() {
        console.log('🧪 Testing backend per-page parameter...');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        fetch('/company/hr/payroll/debug-per-page', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                page: 1,
                per_page: 5
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('🧪 Backend debug response:', data);
        })
        .catch(error => {
            console.error('🧪 Backend debug error:', error);
        });
    }
    
    // Fix scrolling issues
    function fixScrolling() {
        console.log('🔧 Fixing scrolling issues...');
        
        // Remove all modal backdrops
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // Reset body classes and styles
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Don't force overflow - let it be natural
        // document.body.style.overflow = 'auto';
        // document.documentElement.style.overflow = 'auto';
        // document.documentElement.style.height = 'auto';
        
        console.log('✅ Scrolling fixed!');
    }
    
    // Run Payroll Modal Functions
    let allEmployeesData = [];
    let departmentsData = [];
    let selectedEmployees = [];

    // Load departments for Run Payroll modal
    function loadDepartments() {
        console.log('📊 Loading departments for Run Payroll...');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        fetch('/company/hr/payroll/departments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.success && Array.isArray(data.data)) {
                departmentsData = data.data;
                populateDepartmentSelect();
                console.log('✅ Departments loaded:', data.data.length);
            } else {
                console.error('❌ Invalid departments response:', data);
            }
        })
        .catch(error => {
            console.error('❌ Error loading departments:', error);
        });
    }


    // Load all employees for Run Payroll modal
    function loadAllEmployeesForPayroll() {
        console.log('📊 Loading all employees for Run Payroll...');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        fetch('/company/hr/payroll/employees', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.success && Array.isArray(data.data)) {
                allEmployeesData = data.data;
                console.log('✅ All employees loaded for payroll:', data.data.length);
                updatePayrollSummary();
            } else {
                console.error('❌ Invalid employees response:', data);
            }
        })
        .catch(error => {
            console.error('❌ Error loading employees for payroll:', error);
        });
    }

    // Populate department select
    function populateDepartmentSelect() {
        const departmentSelect = document.getElementById('departmentSelect');
        if (!departmentSelect) return;
        
        departmentSelect.innerHTML = '<option value="">Select Department</option>';
        departmentsData.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.value;
            option.textContent = dept.label;
            departmentSelect.appendChild(option);
        });
    }


    // Handle employee selection type change
    function handleEmployeeSelectionChange() {
        const allEmployees = document.getElementById('allEmployees');
        const byDepartment = document.getElementById('byDepartment');
        const individual = document.getElementById('individual');
        
        const departmentSelection = document.getElementById('departmentSelection');
        const employeeList = document.getElementById('employeeList');
        
        // Hide all sections first
        departmentSelection.style.display = 'none';
        employeeList.style.display = 'none';
        
        if (allEmployees.checked) {
            console.log('📊 All employees selected');
            selectedEmployees = [...allEmployeesData];
            updatePayrollSummary();
        } else if (byDepartment.checked) {
            console.log('📊 By department selected');
            departmentSelection.style.display = 'block';
        } else if (individual.checked) {
            console.log('📊 Individual selection');
            employeeList.style.display = 'block';
            populateEmployeeCheckboxes();
        }
    }

    // Handle department selection change
    function handleDepartmentChange() {
        const departmentSelect = document.getElementById('departmentSelect');
        
        if (!departmentSelect.value) {
            selectedEmployees = [];
            updatePayrollSummary();
            return;
        }
        
        // Filter employees by department
        let filteredEmployees = allEmployeesData.filter(emp => emp.department === departmentSelect.value);
        
        selectedEmployees = filteredEmployees;
        updatePayrollSummary();
        console.log('📊 Filtered employees by department:', filteredEmployees.length);
    }


    // Populate employee checkboxes
    function populateEmployeeCheckboxes() {
        const employeeCheckboxes = document.getElementById('employeeCheckboxes');
        if (!employeeCheckboxes) {
            console.error('❌ Employee checkboxes container not found');
            return;
        }
        
        console.log('📊 Populating employee checkboxes with data:', allEmployeesData);
        
        if (!allEmployeesData || allEmployeesData.length === 0) {
            console.error('❌ No employee data available');
            employeeCheckboxes.innerHTML = '<div class="text-muted">No employees available</div>';
            return;
        }
        
        employeeCheckboxes.innerHTML = '';
        allEmployeesData.forEach((employee, index) => {
            console.log(`📊 Employee ${index + 1}:`, employee);
            const div = document.createElement('div');
            div.className = 'form-check mb-2';
            div.innerHTML = `
                <input class="form-check-input employee-checkbox" type="checkbox" value="${employee.value}" id="emp_${employee.value}">
                <label class="form-check-label" for="emp_${employee.value}">
                    ${employee.label} (${employee.staff_id}) - ${employee.department}
                </label>
            `;
            employeeCheckboxes.appendChild(div);
        });
        
        // Add event listeners to checkboxes
        const checkboxes = employeeCheckboxes.querySelectorAll('.employee-checkbox');
        console.log('📊 Created', checkboxes.length, 'employee checkboxes');
        
        checkboxes.forEach((checkbox, index) => {
            console.log(`📊 Adding event listener to checkbox ${index + 1}:`, checkbox.value);
            checkbox.addEventListener('change', function() {
                console.log('📊 Checkbox changed:', this.value, 'checked:', this.checked);
                handleIndividualEmployeeChange();
            });
        });
        
        // Reset selected employees when populating
        selectedEmployees = [];
        updatePayrollSummary();
    }

    // Handle individual employee selection change
    function handleIndividualEmployeeChange() {
        const checkboxes = document.querySelectorAll('.employee-checkbox');
        selectedEmployees = [];
        
        console.log('📊 Handling individual employee change, found', checkboxes.length, 'checkboxes');
        console.log('📊 Available employee data:', allEmployeesData);
        
        checkboxes.forEach((checkbox, index) => {
            console.log(`📊 Checkbox ${index + 1}:`, checkbox.value, 'checked:', checkbox.checked);
            if (checkbox.checked) {
                const employee = allEmployeesData.find(emp => emp.value == checkbox.value);
                console.log('📊 Found employee for value', checkbox.value, ':', employee);
                if (employee) {
                    selectedEmployees.push(employee);
                    console.log('📊 Added employee to selection:', employee);
                } else {
                    console.error('❌ Employee not found for value:', checkbox.value);
                }
            }
        });
        
        updatePayrollSummary();
        console.log('📊 Final selected employees:', selectedEmployees.length, selectedEmployees);
    }

    // Handle select all employees checkbox
    function handleSelectAllEmployees() {
        const selectAllCheckbox = document.getElementById('selectAllEmployees');
        const checkboxes = document.querySelectorAll('.employee-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        
        handleIndividualEmployeeChange();
    }

    // Update payroll summary
    function updatePayrollSummary() {
        const summaryText = document.getElementById('summaryText');
        if (!summaryText) return;
        
        const employeeCount = selectedEmployees.length;
        const estimatedTotal = employeeCount * 2500; // Placeholder calculation
        
        if (employeeCount === 0) {
            summaryText.textContent = 'Select employees to see payroll summary';
        } else {
            summaryText.innerHTML = `This will process payroll for <strong>${employeeCount} employees</strong> with an estimated total of <strong>₵${estimatedTotal.toLocaleString()}</strong>`;
        }
    }

    // Run payroll
    function runPayroll() {
        console.log('🚀 Running payroll...');
        
        const form = document.getElementById('runPayrollForm');
        const formData = new FormData(form);
        
        const payrollData = {
            pay_period: formData.get('pay_period'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            employee_selection: formData.get('employeeSelection'),
            department: formData.get('department'),
            include_bonuses: formData.get('include_bonuses') === 'on',
            include_deductions: formData.get('include_deductions') === 'on',
            selected_employees: selectedEmployees.map(emp => emp.value)
        };
        
        console.log('📊 Selected employees before sending:', selectedEmployees);
        console.log('📊 Selected employee IDs:', payrollData.selected_employees);
        
        // Validate that employees are selected
        if (payrollData.employee_selection === 'individual' && payrollData.selected_employees.length === 0) {
            showNotification('error', 'Please select at least one employee for individual selection');
            return;
        }
        
        console.log('📊 Payroll data:', payrollData);
        
        // Show loading state
        const runBtn = document.getElementById('runPayrollBtn');
        const originalText = runBtn.innerHTML;
        runBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
        runBtn.disabled = true;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        fetch('/company/hr/payroll/run', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payrollData)
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.success) {
                showNotification('success', 'Payroll run created successfully!');
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('runPayrollModal'));
                if (modal) modal.hide();
                // Refresh table
                loadPayrollTable();
            } else {
                showNotification('error', data.message || 'Failed to create payroll run');
            }
        })
        .catch(error => {
            console.error('❌ Error running payroll:', error);
            showNotification('error', 'Failed to run payroll: ' + error.message);
        })
        .finally(() => {
            // Reset button
            runBtn.innerHTML = originalText;
            runBtn.disabled = false;
        });
    }

    // Set default dates for Run Payroll modal
    function setDefaultPayrollDates() {
        const startDate = document.getElementById('runPayrollStartDate');
        const endDate = document.getElementById('runPayrollEndDate');
        
        if (startDate && endDate) {
            const today = new Date();
            const threeMonthsBefore = new Date(today.getFullYear(), today.getMonth() - 3, 1);
            const threeMonthsAfter = new Date(today.getFullYear(), today.getMonth() + 3, 0);
            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            
            // Set date restrictions - allow 3 months before and after
            startDate.min = threeMonthsBefore.toISOString().split('T')[0];
            startDate.max = threeMonthsAfter.toISOString().split('T')[0];
            endDate.min = threeMonthsBefore.toISOString().split('T')[0];
            endDate.max = threeMonthsAfter.toISOString().split('T')[0];
            
            // Set default values to current month
            startDate.value = firstDayOfMonth.toISOString().split('T')[0];
            endDate.value = lastDayOfMonth.toISOString().split('T')[0];
        }
    }

    // Make test functions globally available
    window.testPerPageChange = testPerPageChange;
    window.testPerPage25 = testPerPage25;
    window.testLoadTable = testLoadTable;
    window.debugBackendPerPage = debugBackendPerPage;
    window.fixScrolling = fixScrolling;

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadPayrollStats();
        loadPayrollTable();
        
        // Add modal event listeners to fix scrolling issues
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function() {
                console.log('🔧 Modal closed, cleaning up...');
                fixScrolling();
            });
        });
        
        // Setup Run Payroll Modal
        setupRunPayrollModal();
    });
    
    // Setup Run Payroll Modal event listeners
    function setupRunPayrollModal() {
        // Employee selection radio buttons
        document.querySelectorAll('input[name="employeeSelection"]').forEach(radio => {
            radio.addEventListener('change', handleEmployeeSelectionChange);
        });
        
        // Department select
        const departmentSelect = document.getElementById('departmentSelect');
        if (departmentSelect) {
            departmentSelect.addEventListener('change', handleDepartmentChange);
        }
        
        // Select all employees checkbox
        const selectAllCheckbox = document.getElementById('selectAllEmployees');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', handleSelectAllEmployees);
        }
        
        // Run Payroll button
        const runPayrollBtn = document.getElementById('runPayrollBtn');
        if (runPayrollBtn) {
            runPayrollBtn.addEventListener('click', runPayroll);
        }
        
        // Modal show event - load data when modal opens
        const runPayrollModal = document.getElementById('runPayrollModal');
        if (runPayrollModal) {
            runPayrollModal.addEventListener('show.bs.modal', function() {
                console.log('📊 Run Payroll modal opening...');
                setDefaultPayrollDates();
                loadAllEmployeesForPayroll();
                loadDepartments();
            });
        }
    }
</script>

<!-- View Payroll Modal -->
<div class="modal fade" id="viewPayrollModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>View Payroll Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printPayslipContent()">
                    <i class="fas fa-print me-1"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Payslip Modal -->
<div class="modal fade" id="viewPayslipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-invoice me-2"></i>Payslip
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printPayslipContent()">
                    <i class="fas fa-print me-1"></i>Print Payslip
                </button>
            </div>
        </div>
    </div>
</div>
