@extends('layouts.vertical', ['page_title' => 'GESL Operations Dashboard', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
    @vite(['node_modules/daterangepicker/daterangepicker.css', 'node_modules/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .gesl-dashboard {
            background: #f8f9fa;
        }
        
        .metric-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }
        
        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }
        
        .metric-card.installations::before { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .metric-card.teams::before { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .metric-card.inventory::before { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .metric-card.customers::before { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .metric-card.projects::before { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        
        .metric-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            margin-bottom: 12px;
        }
        
        .metric-card.installations .metric-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .metric-card.teams .metric-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .metric-card.inventory .metric-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .metric-card.customers .metric-icon { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .metric-card.projects .metric-icon { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        
        .metric-value {
            font-size: 32px;
            font-weight: 700;
            color: #2d3748;
            margin: 8px 0;
        }
        
        .metric-label {
            font-size: 13px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .metric-change {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }
        
        .metric-change.positive {
            background: #d4edda;
            color: #155724;
        }
        
        .metric-change.negative {
            background: #f8d7da;
            color: #721c24;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #667eea;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-active { background: #d4edda; color: #155724; }
        .status-deployed { background: #cce5ff; color: #004085; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        
        .quick-action-btn {
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .chart-container {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .team-cluster-item {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 3px solid #667eea;
            transition: all 0.3s ease;
        }
        
        .team-cluster-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateX(5px);
        }
        
        /* Ensure proper spacing for footer visibility */
        .gesl-dashboard {
            padding-bottom: 80px; /* Extra space for footer */
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid gesl-dashboard">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <div class="input-group">
                                <input type="text" class="form-control shadow border-0" id="dash-daterange" />
                                <span class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-todo-fill fs-13"></i>
                                </span>
                            </div>
                            <a href="javascript: void(0);" class="btn btn-primary ms-2" onclick="refreshDashboard()">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </form>
                    </div>
                    <h4 class="page-title" style="font-weight: 700; color: #0d6efd; font-size: 28px;">
                        <i class="ri-dashboard-3-line me-2"></i>GESL Operations Dashboard
                    </h4>
                    <p class="text-muted mb-0">Real-time fiber optic deployment & operations overview</p>
                </div>
            </div>
        </div>

        <!-- Key Metrics Row -->
        <div class="row">
            <!-- Active Installations -->
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card installations">
                    <div class="card-body">
                        <div class="metric-icon">
                            <i class="ri-home-wifi-line"></i>
                        </div>
                        <div class="metric-label">Active Installations</div>
                        <div class="metric-value" id="active-installations">0</div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="metric-change positive">
                                <i class="ri-arrow-up-line"></i> +12.5%
                            </span>
                            <small class="text-muted">This month</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deployed Teams -->
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card teams">
                    <div class="card-body">
                        <div class="metric-icon">
                            <i class="ri-team-line"></i>
                        </div>
                        <div class="metric-label">Deployed Teams</div>
                        <div class="metric-value" id="deployed-teams">0</div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="metric-change positive">
                                <i class="ri-arrow-up-line"></i> +8.3%
                            </span>
                            <small class="text-muted">Active now</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ONT Inventory -->
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card inventory">
                    <div class="card-body">
                        <div class="metric-icon">
                            <i class="ri-router-line"></i>
                        </div>
                        <div class="metric-label">ONT Inventory</div>
                        <div class="metric-value" id="ont-inventory">0</div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="metric-change negative">
                                <i class="ri-arrow-down-line"></i> -5.2%
                            </span>
                            <small class="text-muted">Stock level</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Home Connections -->
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card customers">
                    <div class="card-body">
                        <div class="metric-icon">
                            <i class="ri-user-add-line"></i>
                        </div>
                        <div class="metric-label">Home Connections</div>
                        <div class="metric-value" id="home-connections">0</div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="metric-change positive">
                                <i class="ri-arrow-up-line"></i> +18.7%
                            </span>
                            <small class="text-muted">Total customers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Metrics Row -->
        <div class="row">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card metric-card projects">
                    <div class="card-body text-center">
                        <div class="metric-icon mx-auto">
                            <i class="ri-folder-line"></i>
                        </div>
                        <div class="metric-label">Active Projects</div>
                        <div class="metric-value" id="active-projects">0</div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card metric-card">
                    <div class="card-body text-center">
                        <div class="metric-icon mx-auto" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="ri-truck-line"></i>
                        </div>
                        <div class="metric-label">Active Vehicles</div>
                        <div class="metric-value" id="active-vehicles">0</div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card metric-card">
                    <div class="card-body text-center">
                        <div class="metric-icon mx-auto" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="ri-tools-line"></i>
                        </div>
                        <div class="metric-label">Pending Tasks</div>
                        <div class="metric-value" id="pending-tasks">0</div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card metric-card">
                    <div class="card-body text-center">
                        <div class="metric-icon mx-auto" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <i class="ri-checkbox-circle-line"></i>
                        </div>
                        <div class="metric-label">Completed Today</div>
                        <div class="metric-value" id="completed-today">0</div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card metric-card">
                    <div class="card-body text-center">
                        <div class="metric-icon mx-auto" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="ri-shopping-cart-line"></i>
                        </div>
                        <div class="metric-label">Purchase Orders</div>
                        <div class="metric-value" id="purchase-orders">0</div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card metric-card">
                    <div class="card-body text-center">
                        <div class="metric-icon mx-auto" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="ri-user-line"></i>
                        </div>
                        <div class="metric-label">Field Staff</div>
                        <div class="metric-value" id="field-staff">0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Data Row -->
        <div class="row">
            <!-- Installation Trends Chart -->
            <div class="col-xl-8">
                <div class="chart-container">
                    <h5 class="section-title">
                        <i class="ri-line-chart-line"></i>
                        Installation Trends (Last 30 Days)
                    </h5>
                    <canvas id="installationTrendsChart" height="80"></canvas>
                </div>
            </div>

            <!-- Team Cluster Rotation -->
            <div class="col-xl-4">
                <div class="chart-container">
                    <h5 class="section-title">
                        <i class="ri-map-pin-line"></i>
                        Team Cluster Rotation
                    </h5>
                    <div id="team-clusters" style="max-height: 300px; overflow-y: auto;">
                        <!-- Dynamic content will be loaded here -->
                        <div class="text-center text-muted py-4">
                            <i class="ri-loader-4-line ri-spin fs-3"></i>
                            <p class="mt-2">Loading cluster data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operational Status Row -->
        <div class="row mt-3">
            <!-- Recent Installations -->
            <div class="col-xl-6">
                <div class="chart-container">
                    <h5 class="section-title">
                        <i class="ri-history-line"></i>
                        Recent Installations
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Location</th>
                                    <th>Team</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="recent-installations">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="ri-loader-4-line ri-spin"></i> Loading data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Inventory Status -->
            <div class="col-xl-6">
                <div class="chart-container">
                    <h5 class="section-title">
                        <i class="ri-stack-line"></i>
                        Critical Inventory Levels
                    </h5>
                    <canvas id="inventoryChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="chart-container">
                    <h5 class="section-title">
                        <i class="ri-flashlight-line"></i>
                        Quick Actions
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ url('company/ProjectManagement/homeConnection') }}" class="btn btn-primary quick-action-btn w-100">
                                <i class="ri-add-circle-line me-2"></i>New Installation
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ url('company/MasterTracker/team-pairing') }}" class="btn btn-info quick-action-btn w-100">
                                <i class="ri-team-line me-2"></i>Manage Teams
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ url('company/MasterTracker/team-roaster') }}" class="btn btn-warning quick-action-btn w-100">
                                <i class="ri-calendar-line me-2"></i>Team Roster
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ url('company/InventoryManagement/WarehouseOperations') }}" class="btn btn-success quick-action-btn w-100">
                                <i class="ri-box-3-line me-2"></i>Inventory
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End container-fluid -->

@endsection

@section('script')
    <script>
        // Dashboard Data Management
        const dashboardData = {
            activeInstallations: 0,
            deployedTeams: 0,
            ontInventory: 0,
            homeConnections: 0,
            activeProjects: 0,
            activeVehicles: 0,
            pendingTasks: 0,
            completedToday: 0,
            purchaseOrders: 0,
            fieldStaff: 0
        };

        // Refresh Dashboard Function
        function refreshDashboard() {
            Swal.fire({
                title: 'Refreshing Dashboard',
                text: 'Loading latest data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate API call - Replace with actual API endpoints
            setTimeout(() => {
                loadDashboardMetrics();
                loadTeamClusters();
                loadRecentInstallations();
                Swal.close();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Dashboard Updated',
                    text: 'All data has been refreshed',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 1500);
        }

        // Load Dashboard Metrics
        function loadDashboardMetrics() {
            // Simulate loading metrics - Replace with actual API calls
            const metrics = {
                activeInstallations: Math.floor(Math.random() * 500) + 100,
                deployedTeams: Math.floor(Math.random() * 50) + 10,
                ontInventory: Math.floor(Math.random() * 5000) + 1000,
                homeConnections: Math.floor(Math.random() * 10000) + 5000,
                activeProjects: Math.floor(Math.random() * 30) + 5,
                activeVehicles: Math.floor(Math.random() * 40) + 15,
                pendingTasks: Math.floor(Math.random() * 200) + 50,
                completedToday: Math.floor(Math.random() * 100) + 20,
                purchaseOrders: Math.floor(Math.random() * 50) + 10,
                fieldStaff: Math.floor(Math.random() * 150) + 50
            };

            // Animate counter updates
            animateValue('active-installations', 0, metrics.activeInstallations, 1000);
            animateValue('deployed-teams', 0, metrics.deployedTeams, 1000);
            animateValue('ont-inventory', 0, metrics.ontInventory, 1000);
            animateValue('home-connections', 0, metrics.homeConnections, 1000);
            animateValue('active-projects', 0, metrics.activeProjects, 1000);
            animateValue('active-vehicles', 0, metrics.activeVehicles, 1000);
            animateValue('pending-tasks', 0, metrics.pendingTasks, 1000);
            animateValue('completed-today', 0, metrics.completedToday, 1000);
            animateValue('purchase-orders', 0, metrics.purchaseOrders, 1000);
            animateValue('field-staff', 0, metrics.fieldStaff, 1000);
        }

        // Animate Counter
        function animateValue(id, start, end, duration) {
            const element = document.getElementById(id);
            if (!element) return;
            
            const range = end - start;
            const increment = range / (duration / 16);
            let current = start;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= end) {
                    element.textContent = Math.floor(end).toLocaleString();
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        }

        // Load Team Clusters
        function loadTeamClusters() {
            const clusters = [
                { team: 'Team Alpha', cluster: 'Cluster A - Accra Central', status: 'active', rotation: 'Week 1 of 4' },
                { team: 'Team Beta', cluster: 'Cluster B - Tema', status: 'deployed', rotation: 'Week 2 of 4' },
                { team: 'Team Gamma', cluster: 'Cluster C - Kumasi', status: 'active', rotation: 'Week 3 of 4' },
                { team: 'Team Delta', cluster: 'Cluster D - Takoradi', status: 'deployed', rotation: 'Week 1 of 4' },
                { team: 'Team Epsilon', cluster: 'Cluster E - Cape Coast', status: 'active', rotation: 'Week 4 of 4' }
            ];

            const container = document.getElementById('team-clusters');
            container.innerHTML = clusters.map(cluster => `
                <div class="team-cluster-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 fw-bold">${cluster.team}</h6>
                            <small class="text-muted"><i class="ri-map-pin-line me-1"></i>${cluster.cluster}</small>
                        </div>
                        <div class="text-end">
                            <span class="status-badge status-${cluster.status}">${cluster.status}</span>
                            <br><small class="text-muted">${cluster.rotation}</small>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Load Recent Installations
        function loadRecentInstallations() {
            const installations = [
                { customer: 'Henry Martey', location: 'Accra', team: 'Team Alpha', status: 'completed', date: '2025-10-01' },
                { customer: 'Appiah Smith', location: 'Tema', team: 'Team Beta', status: 'pending', date: '2025-10-01' },
                { customer: 'Otoo Johnson', location: 'Kumasi', team: 'Team Gamma', status: 'active', date: '2025-09-30' },
                { customer: 'Jones Williams', location: 'Takoradi', team: 'Team Delta', status: 'completed', date: '2025-09-30' },
                { customer: 'Nii Brown', location: 'Cape Coast', team: 'Team Epsilon', status: 'deployed', date: '2025-09-29' }
            ];

            const tbody = document.getElementById('recent-installations');
            tbody.innerHTML = installations.map(inst => `
                <tr>
                    <td>${inst.customer}</td>
                    <td>${inst.location}</td>
                    <td>${inst.team}</td>
                    <td><span class="status-badge status-${inst.status}">${inst.status}</span></td>
                    <td>${inst.date}</td>
                </tr>
            `).join('');
        }

        // Initialize Installation Trends Chart
        function initInstallationTrendsChart() {
            const ctx = document.getElementById('installationTrendsChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Day 1', 'Day 5', 'Day 10', 'Day 15', 'Day 20', 'Day 25', 'Day 30'],
                    datasets: [{
                        label: 'Installations',
                        data: [12, 19, 15, 25, 22, 30, 28],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Pending',
                        data: [8, 12, 10, 15, 13, 18, 16],
                        borderColor: '#f093fb',
                        backgroundColor: 'rgba(240, 147, 251, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Initialize Inventory Chart
        function initInventoryChart() {
            const ctx = document.getElementById('inventoryChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['ONT Devices', 'Fiber Cable', 'Splitters', 'Poles', 'FDP'],
                    datasets: [{
                        label: 'Stock Level',
                        data: [1250, 3500, 850, 420, 680],
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.8)',
                            'rgba(240, 147, 251, 0.8)',
                            'rgba(79, 172, 254, 0.8)',
                            'rgba(67, 233, 123, 0.8)',
                            'rgba(250, 112, 154, 0.8)'
                        ],
                        borderColor: [
                            '#667eea',
                            '#f093fb',
                            '#4facfe',
                            '#43e97b',
                            '#fa709a'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Initialize Dashboard on Page Load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardMetrics();
            loadTeamClusters();
            loadRecentInstallations();
            initInstallationTrendsChart();
            initInventoryChart();

            // Auto-refresh every 5 minutes
            setInterval(() => {
                loadDashboardMetrics();
                loadTeamClusters();
                loadRecentInstallations();
            }, 300000);
        });

        // Check for success message
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ok'
            });
        @endif
    </script>
@endsection
