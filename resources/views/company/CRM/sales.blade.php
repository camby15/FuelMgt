@php
    use App\Helpers\CurrencyHelper;
@endphp

@section('css')

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
            transform: translateY(-50%) translateX(0.5rem) scale(0.85);
            color: white;
            border-radius: 5px;
            z-index: 5;
        }
        .form-floating input.form-control:focus ~ label::before,
        .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
        .form-floating select.form-select:focus ~ label::before,
        .form-floating select.form-select:not([value=""]) ~ label::before,
        .form-floating textarea.form-control:focus ~ label::before,
        .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
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
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .action-btn i {
            font-size: 0.875rem;
        }

    </style>
@endsection
<div class="row">

    <!-- Period Filter -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="dashboard-filter card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 form-floating">

                        <select id="period-filter" class="form-select">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="last7">Last 7 Days</option>
                            <option value="last28" selected>Last 28 Days</option>
                            <option value="thismonth">This Month</option>
                            <option value="lastmonth">Last Month</option>
                            <option value="last3months">Last 3 Months</option>
                            <option value="custom">Custom Range</option>
                            <option value="">All Time</option>
                        </select>
                        <label for="period-filter" class="form-label">Time Period:</label>
                    </div>
                    <div class="col-md-6" id="custom-range-container" style="display: none;">
                        <div class="row">
                            <div class="col-md-5 form-floating">

                                <input type="date" id="custom-start-date" class="form-control">
                                <label for="custom-start-date" class="form-label">Start Date:</label>
                            </div>
                            <div class="col-md-5 form-floating">

                                <input type="date" id="custom-end-date" class="form-control">
                                <label for="custom-end-date" class="form-label">End Date:</label>
                            </div>
                            <div class="col-md-2 d-flex align-items-end form-floating">
                                <button id="apply-custom-range" class="btn btn-primary">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Cards -->
<div class="row">
    <!-- Total Sales Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card sales">
            <i class="stat-icon fas fa-chart-line"></i>
            <div class="stat-title">Total Sales</div>
            <div class="stat-value-sale" id="total-sales-value">{{ \App\Helpers\CurrencyHelper::format(0) }}</div>
            <div class="stat-change">
                <i class="fas" id="total-sales-trend-icon"></i>
                <span id="total-sales-change">0% vs last period</span>
            </div>
        </div>
    </div>

    <!-- Conversion Rate Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card conversion">
            <i class="stat-icon fas fa-percent"></i>
            <div class="stat-title">Conversion Rate</div>
            <div class="stat-value-sale" id="conversion-rate-value">0%</div>
            <div class="stat-change">
                <i class="fas" id="conversion-rate-trend-icon"></i>
                <span id="conversion-rate-change">0% vs last period</span>
            </div>
        </div>
    </div>

    <!-- Average Deal Size Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card deal">
            <i class="stat-icon fas fa-dollar-sign"></i>
            <div class="stat-title">Average Deal Size</div>
            <div class="stat-value-sale" id="avg-deal-value">{{ \App\Helpers\CurrencyHelper::format(0) }}</div>
            <div class="stat-change">
                <i class="fas" id="avg-deal-trend-icon"></i>
                <span id="avg-deal-change">0% vs last period</span>
            </div>
        </div>
    </div>

    <!-- Pipeline Value Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card pipeline">
            <i class="stat-icon fas fa-filter"></i>
            <div class="stat-title">Pipeline Value</div>
            <div class="stat-value-sale" id="pipeline-value">{{ \App\Helpers\CurrencyHelper::format(0) }}</div>
            <div class="stat-change">
                <i class="fas" id="pipeline-trend-icon"></i>
                <span id="pipeline-change">0% vs last period</span>
            </div>
        </div>
    </div>
</div>

<div class="my-3"></div>

    <!-- Sales Pipeline -->
    <div class="col-xl-8">
        <div class="dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Sales Pipeline</h4>

                </div>
                <div class="row mb-3">
                  <div class="col-md-8">
                    <div class="col-md-12">
                        <div>
                            <button id="addNewDeal" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDealModal">Add New Deal</button>
                            <button class="btn btn-primary" id="exportSalesData" data-bs-toggle="modal" data-bs-target="#exportModal">Export File</button>

                         </div>
                        {{-- <div class="row align-items-end">
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="startDate">
                                <label for="startDate" class="form-label">Start Date:</label>
                            </div>

                            <div class="col-md-3">

                                <input type="date" class="form-control" id="endDate">
                                <label for="endDate" class="form-label">End Date:</label>
                            </div>

                            <div class="col-md-3 text-end">
                                <button class="btn btn-primary w-100" onclick="salesFilter()">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>

                            <div class="col-md-3">

                            </div>
                        </div> --}}
                    </div>
                  </div>

                    <div class="col-md-4 form-floating">
                        <input type="text" class="form-control " id="searchDeals" placeholder="Search Deals...">
                        <label class="form-label" for="searchDeals">Search Deal</label>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover deal-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Deal</th>
                                <th>Stage</th>
                                <th>Value ({{ CurrencyHelper::symbol() }})</th>
                                <th>Probability</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination" id="dealpagination">

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales by Category -->
    <div class="col-xl-4">
        <div class="dashboard-card">
            <div class="card-body">
                <h4 class="card-title mb-3">Sales by Category</h4>
                <div class="chart-container" style="height: 300px;">
                    <div id="salesByCategory"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Deal Modal -->
<div class="modal fade" id="addDealModal" tabindex="-1" aria-labelledby="addDealModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDealModalLabel">Add New Deal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addDealForm" method="POST" action="{{ route('company.sales.store') }}">
                    @csrf
                    <div class="row mb-3">

                        <div class="col-md-6  form-floating ">
                            <input type="text" name="deal_name" class="form-control " id="deal_name" placeholder=" " required>
                            <label class="form-label" for="deal_name">Deal Name</label>

                            <div class="invalid-feedback">

                            </div>

                        </div>

                        <div class="col-md-6 form-floating">
                            <input type="text"  name="deal_company" class="form-control " id="deal_company" placeholder=" " required>
                            <label class="form-label" for="deal_company">Company</label>

                        </div>
                    </div>
                    <div class="row mb-3">

                        <div class="col-md-6 form-floating">
                            <select class="form-select" name="category_id" id="category_id" required>
                                <option value="">Loading categories...</option>
                            </select>
                            <label class="form-label" for="category_id">Category</label>
                        </div>

                        <div class="col-md-6  form-floating">

                                <input type="number" class="form-control  " name="deal_value" id="deal_value" placeholder="" required>
                                <label class="form-label" for="deal_value">Deal Value ($)</label>

                        </div>

                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-floating ">

                            <select class="form-select   " name="stage" id="stage" required>
                                <option value="">Select Stage</option>
                                <option value="qualification">Qualification</option>
                                <option value="proposal">Proposal</option>
                                <option value="negotiation">Negotiation</option>
                                <option value="closed_won">Closed Won</option>
                                <option value="closed_lost">Closed Lost</option>
                            </select>
                            <label class="form-label" for="stage">Stage</label>

                        </div>

                        <div class="col-md-6 form-floating ">
                            <input type="text" class="form-control " name="sales_commission" id="sales_commission" value="5" placeholder="" readonly required>
                            <label class="form-label" for="sales_commission">Sale Commmission (Read Only)</label>

                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 form-floating ">
                            <input type="date" class="form-control   " name="expected_close_date" id="expected_close_date" placeholder="" required>
                            <label class="form-label" for="expected_close_date">Expected Close Date</label>

                        </div>
                        <div class="col-md-6 form-floating">
                            <input type="number" class="form-control  " min="0" max="100" name="probability" id="probability" placeholder="" required>
                            <label class="form-label" for="probability">Probability (%)</label>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 form-floating ">

                            <select class="form-select" name="sales_manager_id" id="sales_manager_id" required>
                                <option value="">Select Sales Manager</option>

                            </select>
                            <label class="form-label" for="sales_manager_id">Sales Manager</label>

                        </div>

                    </div>
                    <div class="mb-3 form-floating">
                        <textarea class="form-control " name="description" id="description" placeholder=" " rows="3"></textarea>
                        <label class="form-label" for="description">Description</label>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitNewDeal">Add Deal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editDealModal" tabindex="-1" aria-labelledby="editDealModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDealModalLabel">Edit Deal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDealForm" method="POST" action="">
                    @csrf
                    @method('PUT') <!-- For updating data -->
                    <input type="hidden" name="deal_id" id="edit_deal_id"> <!-- Hidden field to store deal ID -->

                    <div class="row mb-3">
                        <div class="col-md-6 form-floating">
                            <input type="text" name="deal_name" id="edit_deal_name" class="form-control" placeholder=" " required>
                            <label class="form-label" for="edit_deal_name">Deal Name</label>
                        </div>
                        <div class="col-md-6 form-floating">
                            <select class="form-select" name="company_id" id="edit_company_id" required>
                                <option value="1">ShrinQ Ltd</option>
                                <option value="2">WiTech Group</option>
                            </select>
                            <label class="form-label" for="edit_company_id">Company</label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 form-floating">
                            <input type="number" class="form-control" name="deal_value" id="edit_deal_value" placeholder="" required>
                            <label class="form-label" for="edit_deal_value">Deal Value ($)</label>
                        </div>
                        <div class="col-md-6 form-floating">
                            <select class="form-select" name="stage" id="edit_stage" required>
                                <option value="qualification">Qualification</option>
                                <option value="proposal">Proposal</option>
                                <option value="negotiation">Negotiation</option>
                                <option value="closed_won">Closed Won</option>
                                <option value="closed_lost">Closed Lost</option>
                            </select>
                            <label class="form-label" for="edit_stage">Stage</label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 form-floating">
                            <input type="date" class="form-control" name="expected_close_date" id="edit_expected_close_date" required>
                            <label class="form-label" for="edit_expected_close_date">Expected Close Date</label>
                        </div>
                        <div class="col-md-6 form-floating">
                            <input type="number" class="form-control" min="0" max="100" name="probability" id="edit_probability" required>
                            <label class="form-label" for="edit_probability">Probability (%)</label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 form-floating ">

                            <select class="form-select" name="sales_manager_id" id="edit_sales_manager_id" required>
                                <option value="">Select Sales Manager</option>

                            </select>
                            <label class="form-label" for="edit_sales_manager_id">Sales Manager</label>

                        </div>

                    </div>

                    <div class="mb-3 form-floating">
                        <textarea class="form-control" name="description" id="edit_description" placeholder=" " rows="3"></textarea>
                        <label class="form-label" for="edit_description">Description</label>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitEditDeal">Update Deal</button>
            </div>
        </div>
    </div>
</div>

<!-- View Deal Modal -->
<div class="modal fade" id="viewDealModal" tabindex="-1" aria-labelledby="viewDealModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDealModalLabel">Deal Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="deal-title mb-3">Enterprise Software Solution</h4>
                        <div class="deal-info mb-4">
                            <p><strong>Company:</strong> <span id="viewdealCompany">ShrinQ Ltd</span></p>
                            <p><strong>Value:</strong> <span id="viewdealValue">$45,000</span></p>
                            <p><strong>Stage:</strong> <span id="viewdealStage" class="badge bg-info">Negotiation</span></p>
                            <p><strong>Probability:</strong> <span id="viewdealProbability">75%</span></p>
                            <p><strong>Expected Close Date:</strong> <span id="viewdealCloseDate">2025-03-15</span></p>
                        </div>
                        <div class="deal-description mb-4">
                            <h5>Description</h5>
                            <p id="viewdealDescription">Enterprise-wide software implementation project including licenses, implementation, and training.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="deal-status-card">
                            <h6>Deal Progress</h6>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-success" style=`width: ${dealProgress}%`></div>
                            </div>
                            <small class="text-muted" id="dealProgress"></small>
                        </div>
                    </div>
                </div>
                <div class="deal-timeline mt-4">
                    <h5>Deal Timeline</h5>
                    <div class="timeline-container position-relative">
                        <!-- Vertical line -->
                        {{-- <div class="timeline-line position-absolute start-50 translate-middle-x h-100 bg-secondary" style="width: 2px"></div> --}}
                        <div class="timeline-dot position-absolute start-50 translate-middle bg-primary rounded-circle" style="width: 12px; height: 12px; top: 25px;"></div>
                        <!-- Timeline items will be inserted here by JavaScript -->
                        <div class="timeline-items pt-3"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Edit Deal</button>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Export Sales Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Select the format to export the sales data:</p>
                <select class="form-select" id="dealexportFormat">
                    <option value="csv">CSV</option>
                    <option value="xlsx">Excel</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="dealconfirmExport">Export</button>
            </div>
        </div>
    </div>
</div>

<!-- Add custom styles -->
<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        padding-left: 40px;
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-date {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
    }

    .deal-status-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .stat-card {
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }
    /* Dashboard Cards */
.stat-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    height: 100%;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 24px;
    margin-bottom: 10px;
}

.sales .stat-icon { color: #6777ef; }
.conversion .stat-icon { color: #17a2b8; }
.deal .stat-icon { color: #ffc107; }
.pipeline .stat-icon { color: #28a745; }

.stat-title {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 5px;
    font-weight: 500;
}

.stat-value-sale {
    font-size: 24px;
    font-weight: 600;
    color: white;
    margin-bottom: 5px;
}

.stat-change {
    font-size: 12px;
    color: #6c757d;
}

.text-success { color: #28a745; }
.text-danger { color: #dc3545; }
.text-muted { color: #6c757d; }

/* Filter Section */
.dashboard-filter {
    margin-bottom: 20px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.dashboard-filter .card-body {
    padding: 15px;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- @section('script')
    @parent --}}

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
//    console.log("hi");

    function showSafeSweetAlert(options) {
            if (!window.isAlertShowing) {
                window.isAlertShowing = true;
                return Swal.fire(options).finally(() => {
                    window.isAlertShowing = false;
                });
            }
        }

        function selectSalesManager(valueToSelect) {
    // Check if option with the specified value exists
    if ($('#edit_sales_manager_id option[value="' + valueToSelect + '"]').length > 0) {
        $('#edit_sales_manager_id').val(valueToSelect);
    } else {
        // Option not available yet, try again after delay
        setTimeout(function() {
            selectSalesManager(valueToSelect);
        }, 100);
    }
}

        load_sub_users_contact();
 function load_sub_users_contact() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `/company/crm/activities/sub_users_contact`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (!response.data || !Array.isArray(response.data)) {
                    console.error("Invalid data format received from API");
                    reject("Invalid data format");
                    return;
                }
                resolve(response.data);

                let options = '<option value="">Select Contact</option>';

                response.data.forEach(contact => {
                    options += `<option value="${contact.id}">${contact.fullname}</option>`;
                });

                $("#sales_manager_id").html(options);
                $("#edit_sales_manager_id").html(options);
                console.log("Sub Users Contact Data:", response.data);
            },
            error: function(err) {
                console.error("AJAX Error:", err);
                reject(err);
            }
        });
    });
}

        // console.log("hi");
$(document).off('click', '#submitNewDeal').on('click', '#submitNewDeal', function(e) {
    e.preventDefault();

    let submitBtn = $(this);
    submitBtn.prop("disabled", true).text("Saving...");

    let urlc = $("#addDealForm").attr("action");  // Get form action URL
    let formData = new FormData($("#addDealForm")[0]);  // Collect form data

    $.ajax({
        url: urlc,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(response) {
            console.log('Server response:', response);
            submitBtn.prop("disabled", false).text("Add Deal");

            if (response.success) {
                showSafeSweetAlert({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Deal added successfully',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 1. Hide the modal using Bootstrap's method
                    //    $('#addDealModal').modal('hide');

                        $('#addDealForm')[0].reset();
                        fetchSalesData();
                        fetchSalesByCategory();

                       fetchDashboardStats('last28');
                    }
                });
            } else {
                console.error('Error response:', response);
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message[0] || 'Failed to add Deal. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', { status, error, response: xhr.responseJSON });
            submitBtn.prop("disabled", false).text("Add Deal");

            let errorMessage = 'Failed to add Deal. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
            }

            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                html: errorMessage,
                confirmButtonText: 'OK'
            });
        }
    });
});

// Function to handle fetchSalesData action
function fetchSalesData(page = 1,startDate = '', endDate = '',) {

    let searchQuery = $('#searchDeals').val();

    $.ajax({
        url: `/company/sales/all?page=${page}`, 
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate,
            search: searchQuery
        },
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(response) {
            let tableBody = '';

            if (response.data.length === 0) {
                tableBody = `<tr>
                    <td colspan="7" class="text-center">
                        <div class="text-muted">
                            <i class="fas fa-tasks fa-3x mb-3"></i>
                            <p>No sales found. Start by creating a new sales.</p>
                        </div>
                    </td>
                </tr>`;
                $('.deal-table tbody').html(tableBody);
                $('#dealpagination').html("");
                return;
            } 
            let i = 1;
            if(response.current_page== 1){
                i = 1;
            }else if(response.current_page > 1){
                i = (response.current_page - 1) * response.per_page + 1;
            }

            console.log("Response:", response);
            response.data.forEach(deal => {
    let status = "";
    if(deal.stage == "closed_won") {
        status = `<span class="badge bg-success-subtle text-success">Closed Won</span>`;
    } else if(deal.stage == "closed_lost") {
        status = `<span class="badge bg-danger-subtle text-danger">Closed Lost</span>`;
    } else {
        status = `<span class="badge bg-warning-subtle text-warning">In Progress</span>`;
    }

    tableBody += `<tr>
        <td>${i}</td>
        <td>${deal.deal_name}</td>
        <td>${deal.stage}</td>
        <td>${window.CurrencyHelper.format(deal.deal_value)}</td>
        <td>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar ${deal.probability <= 33 ? 'bg-danger' : deal.probability <= 66 ? 'bg-warning' : 'bg-success'}" 
                     role="progressbar" 
                     style="width:${deal.probability}%"></div>
            </div>
            <small class="text-muted">${deal.probability}% Goal</small>
        </td>
        <td>${status}</td>
        <td>
            <i class="fas fa-edit me-2 text-primary cursor-pointer editDeal" 
               title="Edit" 
               id="${deal.id}"></i>  
            <i class="fas fa-trash-alt me-2 text-danger cursor-pointer deleteDeal" 
               title="Delete" 
               id="${deal.id}"></i>  
            <i class="fas fa-eye text-success cursor-pointer viewDeal" 
               title="View" 
               id="${deal.id}"></i>  
        </td>
    </tr>`;
    i++;
});

            $('.deal-table tbody').html(tableBody);

            // Generate Pagination
            let paginationHtml = `<li class="page-item ${response.prev_page_url ? '' : 'disabled'}">
                <a class="page-link" href="#" onclick="return changePage(event, ${response.current_page - 1})">Previous</a>
            </li>`;

            for (let i = 1; i <= response.last_page; i++) {
                paginationHtml += `<li class="page-item ${i === response.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="return changePage(event, ${i})">${i}</a>
                </li>`;
            }

            paginationHtml += `<li class="page-item ${response.next_page_url ? '' : 'disabled'}">
                <a class="page-link" href="#" onclick="return changePage(event, ${response.current_page + 1})">Next</a>
            </li>`;

            $('#dealpagination').html(paginationHtml);
        },
        error: function(err){
            console.log("Error:", err);
        }
    });
}

// Prevent Page Refresh on Pagination Click
function changePage(event, page) {
    event.preventDefault();
    fetchSalesData(page);
    return false;
}

// Initial Call
fetchSalesData();

    $('#searchDeals').on('keyup', function() {
        fetchSalesData();
    });

    function salesFilter(){
        let startDate = $('#startDate').val();
        let endDate = $('#endDate').val();
        fetchSalesData(null,startDate,endDate);
    }

    function calculateTimeAgo(dateString) {
    try {
        const date = new Date(dateString);
        if (isNaN(date)) return "Just now";

        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        // Future date check
        if (seconds < 0) return "Soon";

        // Time periods
        const intervals = {
            year: 31536000,
            month: 2592000,
            week: 604800,
            day: 86400,
            hour: 3600,
            minute: 60
        };

        for (const [unit, secondsInUnit] of Object.entries(intervals)) {
            const interval = Math.floor(seconds / secondsInUnit);
            if (interval >= 1) {
                return `${interval} ${unit}${interval === 1 ? '' : 's'} ago`;
            }
        }

        return "Just now";
    } catch (e) {
        console.error("Date error:", e);
        return "Recently";
    }
}

function escapeHtml(text) {
    return text
        .toString()
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Function to handle View action
$(document).on("click", ".viewDeal", function(e) {
    e.preventDefault();

    let dealId = $(this).attr("id"); 

    $.ajax({
        url: `/company/sales/${dealId}`, 
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(response) {
            // Populate modal with deal data
            $("#viewdealCompany").text(response.sale.deal_company); 
            $("#viewdealValue").text(window.CurrencyHelper.format(response.sale.deal_value));
            $("#viewdealStage").text(response.sale.stage).removeClass().addClass("badge bg-info"); 
            $("#viewdealProbability").text(response.sale.probability ? response.sale.probability + "%" : "N/A"); 
            $("#viewdealCloseDate").text(response.sale.expected_close_date); 
            $("#viewdealDescription").text(response.sale.description); 

            $(".deal-status-card").html(`
    <h6>Deal Progress</h6>
    <div class="progress mb-2" style="height: 10px;">
        <div class="progress-bar ${response.sale.probability <= 33 ? 'bg-danger' : response.sale.probability <= 66 ? 'bg-warning' : 'bg-success'}" role="progressbar" style="width: ${response.sale.probability}%"></div>
    </div>
    <small class="text-muted" id="dealProgress">${response.sale.probability}% to goal</small>
   `);

            // Clear previous timeline
            $(".timeline").empty();

            // Loop through timeline data and append to the timeline container

response.timeline.forEach(function(item, index) {
    const createdAt = item.created_at ? item.created_at.trim() : '';
    if (!createdAt) return;

    const timeAgo = calculateTimeAgo(createdAt);
    const action = item.timeline_action || 'Activity';
    const details = item.timeline_vales || 'No details';

    // Alternate between left and right
    const isEven = index % 2 === 0;
    const bubbleClass = isEven ? 'bg-primary text-white' : 'bg-light';
    const offsetClass = isEven ? 'me-auto' : 'ms-auto';

    $(".timeline-items").append(`
      <div class="position-relative mb-4 ps-4">
        <!-- Time badge -->
        <div class="position-absolute top-0 start-0 translate-middle-y bg-white rounded-pill border px-2" style="left: 16px">
          <small class="text-muted">${escapeHtml(timeAgo)}</small>
        </div>

        <!-- Chat bubble -->
        <div class="timeline-bubble ${offsetClass} ${bubbleClass} rounded-3 p-3 shadow-sm mt-3" style="max-width: 80%">
          <h6 class="mb-1">${escapeHtml(action)}</h6>
          <p class="mb-0 small">${escapeHtml(details)}</p>
        </div>
      </div>
    `);
});

            // Show the modal
            $("#viewDealModal").modal("show");
        },
        error: function(xhr) {
            console.error("Error fetching deal:", xhr.responseJSON);
            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                text:"Failed to load deal details.",
                confirmButtonText: 'OK'
            });
        }
    });
});

$("#deal_value").keyup(function(e){

    let deal_val = $(this).val();

    let commision = deal_val * 0.10; 

    let sale_commission = $("#sales_commission").val(commision);
})

$(document).on("click", ".editDeal", function(e) {
    e.preventDefault();
    // $("#editDealModal").modal("show");

    let dealId = $(this).attr("id"); 
    $.ajax({
        url: `/company/sales/${dealId}`, 
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(response) {
            // console.log(response);

            $("#edit_deal_id").val(response.sale.id);
            $("#edit_deal_name").val(response.sale.deal_name);
            $("#edit_company_id").val(response.sale.company_id);
            $("#edit_deal_value").val(response.sale.deal_value);
            $("#edit_stage").val(response.sale.stage);
            $("#edit_expected_close_date").val(response.sale.expected_close_date);
            $("#edit_probability").val(response.sale.probability);
            $("#edit_description").val(response.sale.description);
            selectSalesManager(response.sale.sales_manager_id); 

            $("#editDealModal").modal("show");
        },
        error: function(xhr) {
            console.error("Error fetching deal:", xhr.responseJSON);

            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                text:"Failed to load deal details.",
                confirmButtonText: 'OK'
            });
        }
    });

});

$(document).on("click", "#submitEditDeal", function (e) {
    e.preventDefault();

    let dealId = $("#edit_deal_id").val();

    let formData = {
        _token: $('input[name="_token"]').val(),
        _method: "PUT", // For updating data
        deal_name: $("#edit_deal_name").val(),
        company_id: $("#edit_company_id").val(),
        deal_value: $("#edit_deal_value").val(),
        stage: $("#edit_stage").val(),
        expected_close_date: $("#edit_expected_close_date").val(),
        probability: $("#edit_probability").val(),
        description: $("#edit_description").val(),
        sales_manager_id: $("#edit_sales_manager_id").val(), 
    };

    $.ajax({
        url: `/company/sales/${dealId}`,
        method: "POST", // Laravel handles PUT via _method
        data: formData,
        success: function (response) {
            console.log(response);

            showSafeSweetAlert({
                icon: "success",
                title: "Success!",
                text: "Deal updated successfully.",
                confirmButtonText: "OK",
            });

            fetchSalesData();
            fetchSalesByCategory();

            fetchDashboardStats('last28');

            // Close the modal
            // $("#editDealModal").modal("hide");

        },
        error: function (xhr) {
            console.error("Error updating deal:", xhr.responseJSON);

            showSafeSweetAlert({
                icon: "error",
                title: "Error!",
                text: "Failed to update deal details.",
                confirmButtonText: "OK",
            });
        },
    });
});

$('#dealconfirmExport').click(function(e) {
    e.preventDefault();

    let exportFormat = $("#dealexportFormat").val();
    let startDate = $('#startDate').val();
    let endDate = $('#endDate').val();
    let searchQuery = $('#searchDeals').val();

    // console.log("Export format:", exportFormat);

    $.ajax({
        url: "/company/sales/dealexport",
        method: 'POST',
        data: {
            export_format: exportFormat,
            start_date: startDate || null,  // Ensure empty values are handled
            end_date: endDate || null,
            search: searchQuery || null,
            _method: "POST",
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        xhrFields: {
            responseType: 'blob'  // Important for handling file downloads
        },
        success: function(response, status, xhr) {
            let filename = xhr.getResponseHeader('Content-Disposition')
                .split('filename=')[1]
                .replace(/"/g, '');

            let blob = new Blob([response], { type: 'text/csv' });
            let link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            Swal.fire({
                icon: 'success',
                title: 'Export Successful!',
                text: 'Your sales data has been exported successfully.',
                confirmButtonText: 'OK'
            });
        },
        error: function(xhr) {
            console.error("Error exporting sales:", xhr.responseJSON);

            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                text: "Failed to export sales data.",
                confirmButtonText: 'OK'
            });
        }
    });

    console.log("Export request sent");
});

// Function to handle deletion action
$(document).on("click", ".deleteDeal", function(e) {

    e.preventDefault();
    let itemId = $(this).attr('id');  // Get the ID of the item to delete

    showSafeSweetAlert({
        icon: 'warning',
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteItem(itemId);  // Call the delete function
        }
    });
});

function deleteItem(itemId) {
    $.ajax({
        url: `/company/sales/${itemId}`,  
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()  // CSRF protection
        },
        success: function(response) {
            console.log(response);
            showSafeSweetAlert({
                icon: 'success',
                title: 'Deleted!',
                text: response.message || 'Deal has been deleted successfully.',
                confirmButtonText: 'OK'
            }).then(() => {
                fetchSalesData();
            });
        },
        error: function(xhr) {
            let errorMessage = 'Failed to delete the deal. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                text: errorMessage,
                confirmButtonText: 'OK'
            });
        }
    });
}

load_categoripp();
function load_categoripp(){
    $.ajax({
        url: '/company/sales/sale_categories',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {

            // console.log(response); 
            let select = $('#category_id');
            select.empty();
            select.append('<option value="">Select Category</option>');

            response.data.forEach(function(category) {
                select.append(`<option value="${category.id}">${category.name}</option>`);
            });
        },
        error: function(err) {
            console.log(err);
            $('#category_id').html('<option value="">Error loading categories</option>');
        }
    });
}

load_categories();
function load_categories(){
    $.ajax({
        url: '/company/sales/sale_categories',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {

            // console.log(response); 
            let select = $('#category_id');
            select.empty();
            select.append('<option value="">Select Category</option>');

            response.data.forEach(function(category) {
                select.append(`<option value="${category.id}">${category.name}</option>`);
            });
        },
        error: function(err) {
            console.log(err);
            $('#category_id').html('<option value="">Error loading categories</option>');
        }
    });
}

    // Function to fetch sales data by category
    function fetchSalesByCategory() {
    $.ajax({
        url: '/company/sales/salescategories',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $('#salesByCategory').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
        },
        success: function(response) {
            // console.log(response);
            if (response.success && response.data) {
                displaySimpleCategories(response.data);
            } else {
                $('#salesByCategory').html('<div class="alert alert-info">No category data available</div>');
            }
        },
        error: function(xhr) {
            $('#salesByCategory').html('<div class="alert alert-danger">Failed to load data</div>');
        }
    });
}

function displaySimpleCategories(categories) {
    // Filter out categories with 0% sales
    const activeCategories = categories.filter(cat => cat.percentage > 0);

    if (activeCategories.length === 0) {
        $('#salesByCategory').html('<div class="alert alert-warning">No active sales categories</div>');
        return;
    }

    let html = '<div class="row">';

    activeCategories.forEach(category => {
        html += `

        <ul class="list-group">

  <li class="list-group-item d-flex justify-content-between align-items-start">
    <div class="ms-2 me-auto">
      <div class="fw-bold"> ${category.category_name} </div>

    </div>
    <span class="badge bg-primary rounded-pill">${category.percentage_display}</span>
  </li>

</ul>
       `;
    });

    html += '</div>';
    $('#salesByCategory').html(html);
}

fetchSalesByCategory();

fetchDashboardStats('last28');

// Period filter change
$('#period-filter').change(function() {
    if ($(this).val() === 'custom') {
        $('#custom-range-container').show();
    } else {
        $('#custom-range-container').hide();
        fetchDashboardStats($(this).val());
    }
});

// Apply custom range
$('#apply-custom-range').click(function() {
    const startDate = $('#custom-start-date').val();
    const endDate = $('#custom-end-date').val();

    if (startDate && endDate) {
        fetchDashboardStats('custom', startDate, endDate);
    } else {
        alert('Please select both start and end dates');
    }
});

function fetchDashboardStats(period, startDate = null, endDate = null) {
    $.ajax({
        url: '/company/sales/statistics',
        type: 'POST',
        data: {
            period: period,
            custom_start: startDate,
            custom_end: endDate
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            // Show loading indicators
            $('.stat-value-sale').html('<i class="fas fa-spinner fa-spin"></i>');
        },
        success: function(response) {
            if (response.success) {
                updateDashboardCards(response.data);
            } else {
                console.error('Error:', response.error);
                alert('Failed to load dashboard data');
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr.responseText);
            // alert('Error loading dashboard data');
        }
    });
}

function updateDashboardCards(data) {
    // Update Total Sales card
    updateCard(
        '#total-sales-value', 
        '#total-sales-trend-icon', 
        '#total-sales-change',
        data.totalSales.formattedValue,
        data.totalSales.change
    );

    // Update Conversion Rate card
    updateCard(
        '#conversion-rate-value', 
        '#conversion-rate-trend-icon', 
        '#conversion-rate-change',
        data.conversionRate.formattedValue,
        data.conversionRate.change
    );

    // Update Avg Deal Size card
    updateCard(
        '#avg-deal-value', 
        '#avg-deal-trend-icon', 
        '#avg-deal-change',
        data.avgDealSize.formattedValue,
        data.avgDealSize.change
    );

    // Update Pipeline Value card
    updateCard(
        '#pipeline-value', 
        '#pipeline-trend-icon', 
        '#pipeline-change',
        data.pipelineValue.formattedValue,
        data.pipelineValue.change
    );
}

function updateCard(valueElement, iconElement, changeElement, value, changeData) {
    $(valueElement).text(value);

    const icon = $(iconElement);
    icon.removeClass('fa-arrow-up fa-arrow-down fa-equals');

    if (changeData.trend === 'up') {
        icon.addClass('fa-arrow-up text-success');
        $(changeElement).text('+' + changeData.percentage + '% vs last period');
    } else if (changeData.trend === 'down') {
        icon.addClass('fa-arrow-down text-danger');
        $(changeElement).text('-' + changeData.percentage + '% vs last period');
    } else {
        icon.addClass('fa-equals text-muted');
        $(changeElement).text('No change');
    }
}

@section('script')
@parent
<script>
    // Make CurrencyHelper available globally with proper symbol
    window.CurrencyHelper = {
        format: function(amount) {
            if (!amount) return '{{ CurrencyHelper::symbol() }}0.00';
            const formatted = parseFloat(amount).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            return '{{ CurrencyHelper::symbol() }}' + formatted;
        }
    };

@endsection
</script>
{{-- @endsection --}}