@extends('layouts.vertical', ['page_title' => 'Email Analytics', 'mode' => session('theme_mode', 'light')])

@section('css')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Chart.js for analytics charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

<!-- DataTables CDN -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<!-- Date Range Picker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<style>
    .email-analytics-container {
        background: #f5f7ff;
        min-height: 100vh;
        padding: 20px 0;
    }

    .analytics-card {
        background: #ffffff;
        border: 1px solid #e3ebf6;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-left: 4px solid #3b7ddd;
    }

    .analytics-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .metric-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        border: 1px solid #e3ebf6;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
    }

    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #007bff, #28a745);
    }

    .metric-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 15px 0 5px;
        color: #2c3e50;
    }

    .metric-label {
        color: #64748b;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .metric-change {
        font-size: 0.875rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 15px;
        display: inline-block;
    }

    .metric-change.positive {
        background: #d4edda;
        color: #155724;
    }

    .metric-change.negative {
        background: #f8d7da;
        color: #721c24;
    }

    .metric-change.neutral {
        background: #e2e3e5;
        color: #495057;
    }

    .chart-container {
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 20px;
        border: 1px solid #e3ebf6;
    }

    .chart-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .filter-section {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .performance-table {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .performance-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .performance-excellent {
        background: #d4edda;
        color: #155724;
    }

    .performance-good {
        background: #d1ecf1;
        color: #0c5460;
    }

    .performance-average {
        background: #fff3cd;
        color: #856404;
    }

    .performance-poor {
        background: #f8d7da;
        color: #721c24;
    }

    .progress-bar-custom {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #007bff, #28a745);
        transition: width 0.6s ease;
    }

    .heatmap-container {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .heatmap-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        margin-top: 10px;
    }

    .heatmap-cell {
        aspect-ratio: 1;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .heatmap-cell:hover {
        transform: scale(1.1);
        z-index: 10;
        position: relative;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .metric-number {
            font-size: 2rem;
        }
        
        .chart-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="email-analytics-container">
    <!-- Page Title -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Administration</a></li>
                            <li class="breadcrumb-item active">Email Analytics</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Email Analytics Dashboard</h4>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="filter-section">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <input type="text" class="form-control" id="dateRange" placeholder="Select date range">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Campaign Type</label>
                            <select class="form-select" id="campaignTypeFilter">
                                <option value="">All Types</option>
                                <option value="newsletter">Newsletter</option>
                                <option value="promotional">Promotional</option>
                                <option value="transactional">Transactional</option>
                                <option value="automated">Automated</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="sent">Sent</option>
                                <option value="delivered">Delivered</option>
                                <option value="opened">Opened</option>
                                <option value="clicked">Clicked</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Compare With</label>
                            <select class="form-select" id="compareFilter">
                                <option value="">No Comparison</option>
                                <option value="previous_period">Previous Period</option>
                                <option value="last_month">Last Month</option>
                                <option value="last_quarter">Last Quarter</option>
                                <option value="last_year">Last Year</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" onclick="applyFilters()">
                                    <i class="fas fa-filter me-1"></i>Apply
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                                    <i class="fas fa-sync me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics Row -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="metric-card">
                    <div class="metric-label">Total Emails Sent</div>
                    <div class="metric-number" id="totalSent">245,678</div>
                    <div class="metric-change positive">+12.5% from last month</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="metric-card">
                    <div class="metric-label">Delivery Rate</div>
                    <div class="metric-number" id="deliveryRate">97.8%</div>
                    <div class="metric-change positive">+2.1% from last month</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="metric-card">
                    <div class="metric-label">Open Rate</div>
                    <div class="metric-number" id="openRate">24.6%</div>
                    <div class="metric-change negative">-1.2% from last month</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="metric-card">
                    <div class="metric-label">Click-Through Rate</div>
                    <div class="metric-number" id="clickRate">3.8%</div>
                    <div class="metric-change positive">+0.5% from last month</div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="chart-title">Email Performance Trends</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="trendPeriod" id="trend7days" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="trend7days">7 Days</label>
                            
                            <input type="radio" class="btn-check" name="trendPeriod" id="trend30days" autocomplete="off">
                            <label class="btn btn-outline-primary" for="trend30days">30 Days</label>
                            
                            <input type="radio" class="btn-check" name="trendPeriod" id="trend90days" autocomplete="off">
                            <label class="btn btn-outline-primary" for="trend90days">90 Days</label>
                        </div>
                    </div>
                    <canvas id="performanceChart" height="300"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="chart-title">Campaign Types Distribution</h5>
                    </div>
                    <canvas id="campaignTypeChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Engagement Heatmap and Device Analytics -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="heatmap-container">
                    <h5 class="chart-title mb-3">Email Activity Heatmap</h5>
                    <p class="text-muted mb-3">Best times to send emails based on engagement data</p>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Hours (24h format)</small>
                        <div class="d-flex align-items-center gap-2">
                            <small class="text-muted">Low</small>
                            <div class="d-flex gap-1">
                                <div style="width: 10px; height: 10px; background: #f8f9fa; border-radius: 2px;"></div>
                                <div style="width: 10px; height: 10px; background: #e3f2fd; border-radius: 2px;"></div>
                                <div style="width: 10px; height: 10px; background: #2196f3; border-radius: 2px;"></div>
                                <div style="width: 10px; height: 10px; background: #1976d2; border-radius: 2px;"></div>
                                <div style="width: 10px; height: 10px; background: #0d47a1; border-radius: 2px;"></div>
                            </div>
                            <small class="text-muted">High</small>
                        </div>
                    </div>
                    <div id="heatmapGrid" class="heatmap-grid"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="chart-title">Device & Client Analytics</h5>
                    </div>
                    <canvas id="deviceChart" height="250"></canvas>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <strong>Mobile</strong><br>
                                <span class="text-muted">68.2%</span>
                            </div>
                            <div class="col-4">
                                <strong>Desktop</strong><br>
                                <span class="text-muted">24.1%</span>
                            </div>
                            <div class="col-4">
                                <strong>Tablet</strong><br>
                                <span class="text-muted">7.7%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Campaigns Table -->
        <div class="row">
            <div class="col-12">
                <div class="performance-table">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="chart-title">Top Performing Campaigns</h5>
                        <button class="btn btn-outline-primary btn-sm" onclick="exportCampaignData()">
                            <i class="fas fa-download me-1"></i>Export Data
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="campaignsTable">
                            <thead>
                                <tr>
                                    <th>Campaign Name</th>
                                    <th>Type</th>
                                    <th>Sent Date</th>
                                    <th>Recipients</th>
                                    <th>Delivered</th>
                                    <th>Open Rate</th>
                                    <th>Click Rate</th>
                                    <th>Performance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table data will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
let performanceChart, campaignTypeChart, deviceChart;

$(document).ready(function() {
    // Initialize date range picker
    initializeDateRangePicker();
    
    // Initialize charts
    initializeCharts();
    
    // Generate heatmap
    generateHeatmap();
    
    // Initialize campaigns table
    initializeCampaignsTable();
    
    // Bind filter events
    bindFilterEvents();
});

function initializeDateRangePicker() {
    $('#dateRange').daterangepicker({
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });
}

function initializeCharts() {
    // Performance Trends Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    performanceChart = new Chart(performanceCtx, {
        type: 'line',
        data: {
            labels: generateDateLabels(7),
            datasets: [
                {
                    label: 'Open Rate (%)',
                    data: [24.2, 26.1, 23.8, 27.3, 25.9, 28.4, 24.6],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Click Rate (%)',
                    data: [3.2, 3.6, 3.1, 4.1, 3.8, 4.2, 3.8],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Bounce Rate (%)',
                    data: [2.1, 2.3, 1.9, 2.5, 2.2, 1.8, 2.2],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Campaign Types Pie Chart
    const campaignTypeCtx = document.getElementById('campaignTypeChart').getContext('2d');
    campaignTypeChart = new Chart(campaignTypeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Newsletter', 'Promotional', 'Transactional', 'Automated'],
            datasets: [{
                data: [35, 28, 22, 15],
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#17a2b8'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Device Analytics Chart
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    deviceChart = new Chart(deviceCtx, {
        type: 'bar',
        data: {
            labels: ['Mobile', 'Desktop', 'Tablet'],
            datasets: [{
                label: 'Usage %',
                data: [68.2, 24.1, 7.7],
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107'
                ],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

function generateDateLabels(days) {
    const labels = [];
    for (let i = days - 1; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
    }
    return labels;
}

function generateHeatmap() {
    const container = document.getElementById('heatmapGrid');
    const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    const hours = Array.from({length: 24}, (_, i) => i);
    
    // Clear existing content
    container.innerHTML = '';
    
    // Add day labels
    days.forEach(day => {
        const dayLabel = document.createElement('div');
        dayLabel.textContent = day;
        dayLabel.style.cssText = 'display: flex; align-items: center; justify-content: center; font-weight: 600; color: #64748b; font-size: 12px;';
        container.appendChild(dayLabel);
    });
    
    // Generate heatmap data (simulated)
    days.forEach((day, dayIndex) => {
        hours.forEach(hour => {
            const cell = document.createElement('div');
            cell.className = 'heatmap-cell';
            
            // Simulate engagement data (higher during business hours)
            let intensity = Math.random();
            if (hour >= 9 && hour <= 17 && dayIndex < 5) {
                intensity = Math.random() * 0.5 + 0.5; // Higher intensity
            } else if (hour >= 19 && hour <= 21) {
                intensity = Math.random() * 0.4 + 0.3; // Medium intensity
            }
            
            const color = getHeatmapColor(intensity);
            cell.style.backgroundColor = color;
            cell.title = `${day} ${hour}:00 - Engagement: ${(intensity * 100).toFixed(1)}%`;
            
            container.appendChild(cell);
        });
    });
}

function getHeatmapColor(intensity) {
    const colors = [
        '#f8f9fa',
        '#e3f2fd',
        '#bbdefb',
        '#2196f3',
        '#1976d2',
        '#0d47a1'
    ];
    
    const index = Math.min(Math.floor(intensity * colors.length), colors.length - 1);
    return colors[index];
}

function initializeCampaignsTable() {
    const sampleData = [
        {
            name: 'Summer Sale Campaign',
            type: 'Promotional',
            date: '2024-01-20',
            recipients: 15420,
            delivered: 15201,
            openRate: 32.4,
            clickRate: 5.8,
            performance: 'excellent'
        },
        {
            name: 'Weekly Newsletter #34',
            type: 'Newsletter',
            date: '2024-01-18',
            recipients: 8935,
            delivered: 8876,
            openRate: 28.7,
            clickRate: 4.2,
            performance: 'good'
        },
        {
            name: 'Welcome Series - Part 1',
            type: 'Automated',
            date: '2024-01-17',
            recipients: 2341,
            delivered: 2329,
            openRate: 45.6,
            clickRate: 12.3,
            performance: 'excellent'
        },
        {
            name: 'Order Confirmation',
            type: 'Transactional',
            date: '2024-01-16',
            recipients: 1876,
            delivered: 1876,
            openRate: 78.2,
            clickRate: 23.4,
            performance: 'excellent'
        },
        {
            name: 'Product Update Announcement',
            type: 'Promotional',
            date: '2024-01-15',
            recipients: 12580,
            delivered: 12234,
            openRate: 19.8,
            clickRate: 2.1,
            performance: 'average'
        }
    ];

    const tableBody = document.querySelector('#campaignsTable tbody');
    tableBody.innerHTML = '';
    
    sampleData.forEach((campaign, index) => {
        const deliveryRate = ((campaign.delivered / campaign.recipients) * 100).toFixed(1);
        const performanceBadge = getPerformanceBadge(campaign.performance);
        
        const row = `
            <tr>
                <td><strong>${campaign.name}</strong></td>
                <td><span class="badge bg-primary">${campaign.type}</span></td>
                <td>${campaign.date}</td>
                <td>${campaign.recipients.toLocaleString()}</td>
                <td>${campaign.delivered.toLocaleString()} (${deliveryRate}%)</td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="me-2">${campaign.openRate}%</span>
                        <div class="progress-bar-custom flex-grow-1">
                            <div class="progress-fill" style="width: ${campaign.openRate}%"></div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="me-2">${campaign.clickRate}%</span>
                        <div class="progress-bar-custom flex-grow-1">
                            <div class="progress-fill" style="width: ${campaign.clickRate * 5}%"></div>
                        </div>
                    </div>
                </td>
                <td>${performanceBadge}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="viewCampaignDetails(${index})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-info" onclick="downloadReport(${index})">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });

    // Initialize DataTable
    $('#campaignsTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[2, 'desc']], // Sort by date
        columnDefs: [
            { orderable: false, targets: [7, 8] } // Disable sorting for performance and action columns
        ]
    });
}

function getPerformanceBadge(performance) {
    const badges = {
        excellent: '<span class="performance-badge performance-excellent">Excellent</span>',
        good: '<span class="performance-badge performance-good">Good</span>',
        average: '<span class="performance-badge performance-average">Average</span>',
        poor: '<span class="performance-badge performance-poor">Poor</span>'
    };
    return badges[performance] || badges.average;
}

function bindFilterEvents() {
    // Period selection for performance chart
    $('input[name="trendPeriod"]').on('change', function() {
        const period = this.id.replace('trend', '').replace('days', '');
        updatePerformanceChart(parseInt(period));
    });
}

function updatePerformanceChart(days) {
    performanceChart.data.labels = generateDateLabels(days);
    
    // Generate new sample data based on the period
    const openRates = Array.from({length: days}, () => Math.random() * 10 + 20);
    const clickRates = Array.from({length: days}, () => Math.random() * 2 + 2);
    const bounceRates = Array.from({length: days}, () => Math.random() * 1 + 1.5);
    
    performanceChart.data.datasets[0].data = openRates;
    performanceChart.data.datasets[1].data = clickRates;
    performanceChart.data.datasets[2].data = bounceRates;
    
    performanceChart.update();
}

// Action Functions
function applyFilters() {
    Swal.fire({
        title: 'Filters Applied',
        text: 'Analytics data has been updated based on your filter selection.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
    
    // Here you would typically make an AJAX call to fetch filtered data
    // For demo purposes, we'll just show the alert
}

function resetFilters() {
    $('#campaignTypeFilter').val('');
    $('#statusFilter').val('');
    $('#compareFilter').val('');
    $('#dateRange').data('daterangepicker').setStartDate(moment().subtract(29, 'days'));
    $('#dateRange').data('daterangepicker').setEndDate(moment());
    
    Swal.fire({
        title: 'Filters Reset',
        text: 'All filters have been cleared.',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

function viewCampaignDetails(index) {
    Swal.fire({
        title: 'Campaign Details',
        html: `
            <div class="text-start">
                <h6>Detailed Analytics</h6>
                <p><strong>Campaign ID:</strong> CAMP-${1000 + index}</p>
                <p><strong>Conversion Rate:</strong> ${(Math.random() * 5 + 2).toFixed(2)}%</p>
                <p><strong>Revenue Generated:</strong> $${(Math.random() * 50000 + 10000).toFixed(2)}</p>
                <p><strong>Unsubscribe Rate:</strong> ${(Math.random() * 2).toFixed(2)}%</p>
                <p><strong>Spam Complaints:</strong> ${Math.floor(Math.random() * 10)}</p>
            </div>
        `,
        confirmButtonText: 'Close',
        width: '500px'
    });
}

function downloadReport(index) {
    Swal.fire({
        title: 'Download Report',
        text: 'Campaign report is being prepared for download.',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    });
}

function exportCampaignData() {
    Swal.fire({
        title: 'Export Data',
        text: 'Analytics data export is being prepared.',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    });
}
</script>
@endpush
