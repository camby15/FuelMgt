<div class="row">
    <div class="col-md-6">
        <div class="card crm-card">
            <div class="card-body">
                <h4 class="header-title mb-3">Lead Sources</h4>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="leadSourcesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card crm-card">
            <div class="card-body">
                <h4 class="header-title mb-3">Sales Pipeline</h4>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="salesPipelineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-4">
        <div class="card crm-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="header-title mb-0">Performance Metrics</h4>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary active">Monthly</button>
                        <button class="btn btn-outline-primary">Quarterly</button>
                        <button class="btn btn-outline-primary">Yearly</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-hover">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>Current</th>
                                <th>Previous</th>
                                <th>Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Revenue</td>
                                <td>$157,482</td>
                                <td>$145,321</td>
                                <td><span class="text-success">+8.3%</span></td>
                            </tr>
                            <tr>
                                <td>New Leads</td>
                                <td>245</td>
                                <td>220</td>
                                <td><span class="text-success">+11.4%</span></td>
                            </tr>
                            <tr>
                                <td>Conversion Rate</td>
                                <td>28.5%</td>
                                <td>27.1%</td>
                                <td><span class="text-success">+5.2%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Report Modal -->
<div class="modal fade" id="customReportModal" tabindex="-1" aria-labelledby="customReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customReportModalLabel">Create Custom Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="customReportForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Report Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Report Type</label>
                            <select class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="sales">Sales Report</option>
                                <option value="leads">Lead Analysis</option>
                                <option value="performance">Performance Metrics</option>
                                <option value="custom">Custom Metrics</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date Range</label>
                            <select class="form-select" required>
                                <option value="last7">Last 7 Days</option>
                                <option value="last30">Last 30 Days</option>
                                <option value="last90">Last 90 Days</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Chart Type</label>
                            <select class="form-select" required>
                                <option value="line">Line Chart</option>
                                <option value="bar">Bar Chart</option>
                                <option value="pie">Pie Chart</option>
                                <option value="table">Table Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metrics</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="revenue">
                                    <label class="form-check-label">Revenue</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="leads">
                                    <label class="form-check-label">Lead Count</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="conversion">
                                    <label class="form-check-label">Conversion Rate</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Filters</label>
                        <div id="filterBuilder" class="border rounded p-3">
                            <div class="filter-row mb-2">
                                <select class="form-select form-select-sm d-inline-block w-auto me-2">
                                    <option value="source">Lead Source</option>
                                    <option value="status">Status</option>
                                    <option value="region">Region</option>
                                </select>
                                <select class="form-select form-select-sm d-inline-block w-auto me-2">
                                    <option value="equals">Equals</option>
                                    <option value="contains">Contains</option>
                                    <option value="greater">Greater Than</option>
                                    <option value="less">Less Than</option>
                                </select>
                                <input type="text" class="form-control form-control-sm d-inline-block w-auto">
                                <button type="button" class="btn btn-sm btn-light ms-2">Add Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Generate Report</button>
            </div>
        </div>
    </div>
</div>

<!-- Export Report Modal -->
<div class="modal fade" id="exportReportModal" tabindex="-1" aria-labelledby="exportReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportReportModalLabel">Export Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="mb-3">
                        <label class="form-label">Export Format</label>
                        <select class="form-select" required>
                            <option value="pdf">PDF Document</option>
                            <option value="excel">Excel Spreadsheet</option>
                            <option value="csv">CSV File</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Include</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Charts and Graphs</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Data Tables</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Summary Statistics</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Report Range</label>
                        <input type="text" class="form-control" id="reportDateRange">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Export</button>
            </div>
        </div>
    </div>
</div>

<!-- Add custom styles -->
<style>
    /* Chart Styles */
    .chart-container {
        position: relative;
        margin: auto;
        height: 300px;
        background-color: #ffffff;
        border-radius: 0.5rem;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Metric Card Styles */
    .metric-card {
        background: #ffffff;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1f2937;
    }

    .metric-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Trend Indicator Styles */
    .trend-indicator {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .trend-up {
        background-color: #d1fae5;
        color: #059669;
    }

    .trend-down {
        background-color: #fee2e2;
        color: #dc2626;
    }

    /* Filter Builder Styles */
    .filter-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.5rem;
        background-color: #f9fafb;
        border-radius: 0.375rem;
    }

    #filterBuilder {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
    }

    /* Form Control Styles */
    .form-control,
    .form-select {
        border-color: #e5e7eb;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    .modal-footer {
        background-color: #f8fafc;
        border-top: 1px solid #e5e7eb;
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    /* Button Styles */
    .btn-group-vertical {
        background-color: #ffffff;
        padding: 0.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .btn-group-vertical .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 0.5rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    .btn-group-vertical .btn:last-child {
        margin-bottom: 0;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8fafc;
        border-bottom: 2px solid #e5e7eb;
        color: #4b5563;
        font-weight: 600;
    }

    .table tbody td {
        vertical-align: middle;
        color: #1f2937;
    }

    /* Date Range Picker Styles */
    .daterangepicker {
        z-index: 1100 !important;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .daterangepicker .calendar-table {
        border-radius: 0.5rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-row .form-select,
        .filter-row .form-control {
            width: 100% !important;
        }

        .btn-group-vertical {
            padding: 0.25rem;
        }

        .metric-value {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Add necessary JavaScript -->
@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Charts
        const leadSourcesChart = new Chart(document.getElementById('leadSourcesChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Website', 'Referral', 'Social Media', 'Direct', 'Other'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        '#4f46e5',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#6b7280'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        const salesPipelineChart = new Chart(document.getElementById('salesPipelineChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                datasets: [{
                    label: 'Revenue',
                    data: [65000, 59000, 80000, 81000],
                    backgroundColor: '#4f46e5'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Initialize DateRangePicker
        $('#reportDateRange').daterangepicker({
            opens: 'left',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        // Refresh Reports Handler
        $('#refreshReports').on('click', function() {
            const btn = $(this);
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Refreshing...');

            // Simulate refresh
            setTimeout(() => {
                btn.prop('disabled', false);
                btn.html('<i class="fas fa-sync-alt me-1"></i> Refresh');
            }, 1500);
        });

        // Custom Report Form Handler
        $('#customReportForm').on('submit', function(e) {
            e.preventDefault();
            // Handle report generation
        });

        // Export Form Handler
        $('#exportForm').on('submit', function(e) {
            e.preventDefault();
            // Handle export
        });

        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush