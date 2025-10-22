@extends('layouts.vertical-admin', ['page_title' => 'Super Admin Dashboard'])

@section('css')
    @vite(['node_modules/daterangepicker/daterangepicker.css', 'node_modules/admin-resources/jquery-jvectormap/jquery-jvectormap-1.2.2.css'])
    <style>
        /* Floating Label Styles (copied from company/user-management/users.blade.php) */
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
        .form-floating select.form-select {
            display: block;
            width: 100%;
            height: 50px;
            padding: 1rem 0.75rem;
        }
        /* Custom Dashboard Styles */
        .profile-card-bg {
            background: linear-gradient(135deg, #760808 0%, #26d0ce 100%);
            color: #fff;
        }
        .timeline .badge {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .timeline li {
            position: relative;
            padding-left: 2.5rem;
        }
        .timeline li:before {
            content: '';
            position: absolute;
            left: 1.1rem;
            top: 0.5rem;
            width: 2px;
            height: 100%;
            background: #e9ecef;
            z-index: 0;
        }
        .timeline li:last-child:before {
            display: none;
        }
        .card.bg-gradient-primary {
            background: linear-gradient(135deg, #020e56 0%, #26d0ce 100%) !important;
        }
        .card.bg-gradient-info {
            background: linear-gradient(135deg, #04443f 0%, #38ef7d 100%) !important;
        }
        .card.bg-gradient-warning {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%) !important;
            color: #222 !important;
        }
        .card.bg-gradient-danger {
            background: linear-gradient(135deg, #aa0502 0%, #e35d5b 100%) !important;
        }
        .card.border-0.shadow-sm {
            border-radius: 1rem;
        }
        .btn.w-100 {
            margin-bottom: 0.5rem;
        }
        .alert.alert-danger {
            border-radius: 0.8rem;
        }
        @media (max-width: 991.98px) {
            .timeline li { padding-left: 1.5rem; }
        }
        .avatar-upload {
            position: relative;
            display: inline-block;
        }
        .avatar-upload input[type="file"] {
            display: none;
        }
        .avatar-upload-label {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #222c;
            color: #fff;
            border-radius: 50%;
            padding: 0.3rem;
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            transition: background 0.2s;
        }
        .avatar-upload-label:hover {
            background: #1a2980;
        }
        /* Responsive, Space-Optimized Dashboard Cards */
        .card-body {
            min-height: 160px;
        }
        @media (min-width: 1200px) {
            .row-cols-xl-4 > .col {flex: 1 0 0%; max-width: 25%;}
        }
        /* Enhanced Styling for the Welcome card */
        .dashboard-header {
            background: linear-gradient(90deg, #140157 0%, #3535fa9c 90%);
            color: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(25, 118, 210, 0.08);
            padding: 2rem 2.5rem 1.5rem 2.5rem;
            margin-bottom: 2rem;
        }
        .dashboard-header h2 {
            color: #fff;
            font-weight: 400;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-size: 1.8rem;
            letter-spacing: 0.5px;
        }
        .dashboard-header p {
            color: #e3f2fd;
        }
        /* Avatar card */
        .avatar-card {
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 2px 24px rgba(25, 118, 210, 0.07);
            border: none;
            padding-top: 2rem;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }
        .avatar-card img {
            border: 4px solid #f5faff;
            box-shadow: 0 4px 16px rgba(67, 233, 123, 0.12);
        }
        .avatar-card .btn-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: #222;
            border: none;
            box-shadow: 0 2px 8px rgba(250, 112, 154, 0.10);
            transition: background 0.2s, color 0.2s;
        }
        .avatar-card .btn-danger:hover {
            background: linear-gradient(135deg, #fee140 0%, #fa709a 100%);
            color: #fff;
        }
        /* Card enhancements */
        .card {
            border-radius: 1.25rem !important;
            box-shadow: 0 2px 16px rgba(25, 118, 210, 0.06);
            border: none;
        }
        .card-body {
            background: transparent;
        }
        .card .fw-bold, .card .fw-semibold {
            letter-spacing: 0.3px;
        }
        /* KPI icons */
        .card .fs-1 {
            filter: drop-shadow(0 2px 8px rgba(25,118,210,0.10));
        }
        /* Quick Actions for superAdmin buttons and actions for managing users and content*/
        .d-grid .btn {
            border-radius: 2rem;
            font-weight: 500;
            letter-spacing: 0.2px;
        }
        .d-grid .btn-outline-primary {
            border: 2px solid #045dbb;
            color: #021b35;
            background: #f5faff;
        }
        .d-grid .btn-outline-primary:hover {
            background: #1976d2;
            color: #fff;
        }
        .d-grid .btn-outline-success {
            border: 2px solid #43e97b;
            color: #024218;
            background: #f5faff;
        }
        .d-grid .btn-outline-warning {
            border: 2px solid #6f0101;
            color: #6f0101;
            background: #f5faff;   
        }
        .d-grid .btn-outline-warning:hover {
            background: #6f0101;
            color: white
        }
        .d-grid .btn-outline-success:hover {
            background: #43e97b;
            color: #fff;
        }
        .d-grid .btn-outline-dark {
            border: 2px solid #222;
            color: #222;
            background: #f5faff;
        }
        .d-grid .btn-outline-dark:hover {
            background: #222;
            color: #fff;
        }
        /* Chart card */
        #userGrowthChart {
            background: linear-gradient(90deg, #f5faff 0%, #e3f2fd 100%);
            border-radius: 1rem;
            padding: 1rem;
        }
        /* Responsive tweaks */
        @media (max-width: 767px) {
            .dashboard-header {
                padding: 1.2rem 1rem 1rem 1rem;
            }
            .avatar-card {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
        }

    </style>
@endsection

@section('content')
@if(session('login_success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'You have successfully logged in',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif
<div class="container-fluid">
    <div class="dashboard-header">
        <h2 class="fw-bold mb-1">Welcome, Admin!</h2>
        <p class="mb-0">Here’s an overview of your platform at a glance.</p>
    </div>
    <div class="row mt-3">
        <!-- Super Admin Avatar Card -->
        <div class="row mb-4">
            <div class="col-md-4 col-lg-3 mb-3">
                <div class="card avatar-card text-center h-100 py-4">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/images/users/avatar-1.jpg" class="rounded-circle mb-3 shadow" width="80" height="80" alt="Admin">
                            <h5 class="mb-1">Denaked Spider</h5>
                            <span class="badge bg-primary mb-2">Super Admin</span>
                            <span class="text-muted small mb-2"><i class="bi bi-clock-history me-1"></i>Last login: 2025-04-23 01:30</span>
                            <span class="text-muted small mb-2"><i class="bi bi-envelope me-1"></i>admin@shrinq.com</span>
                            <button class="btn btn-outline-secondary btn-sm mb-2 rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editProfileModal"><i class="bi bi-pencil me-1"></i>Edit Profile</button>
                            <span class="badge bg-success mb-2">Online</span>
                            <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                                <button id="logoutBtn" class="btn btn-danger d-flex justify-content-center align-items-center p-0" style="width:48px;height:48px;border-radius:50%;font-size:1.4rem;"><i class="bi bi-box-arrow-right"></i></button>
                                <button id="lockScreenBtn" class="btn btn-warning d-flex justify-content-center align-items-center p-0" style="width:48px;height:48px;border-radius:50%;font-size:1.4rem;"><i class="bi bi-lock"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-9">
                <!-- Superadmin Dashboard -->
                <div class="row g-3 mb-4 row-cols-1 row-cols-md-2 row-cols-xl-4">
                    <!-- Active Agents -->
                    <div class="col d-flex align-items-stretch">
                        <div class="card shadow-sm border-0 flex-fill h-100 py-4">
                            <div class="card-body text-center">
                                <div class="mb-3"><i class="bi bi-headset fs-1 text-primary"></i></div>
                                <h3 class="fw-bold mb-1">45</h3>
                                <div class="text-muted fs-5">Active Agents</div>
                                <div class="small text-success mt-2">
                                    <i class="bi bi-arrow-up"></i> +12% from last month
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Open Tickets -->
                    <div class="col d-flex align-items-stretch">
                        <div class="card shadow-sm border-0 flex-fill h-100 py-4">
                            <div class="card-body text-center">
                                <div class="mb-3"><i class="bi bi-ticket-detailed fs-1 text-success"></i></div>
                                <h3 class="fw-bold mb-1">128</h3>
                                <div class="text-muted fs-5">Open Tickets</div>
                                <div class="small text-danger mt-2">
                                    23 urgent tickets
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Response Time -->
                    <div class="col d-flex align-items-stretch">
                        <div class="card shadow-sm border-0 flex-fill h-100 py-4">
                            <div class="card-body text-center">
                                <div class="mb-3"><i class="bi bi-clock-history fs-1 text-warning"></i></div>
                                <h3 class="fw-bold mb-1">15m</h3>
                                <div class="text-muted fs-5">Avg Response Time</div>
                                <div class="small text-success mt-2">
                                    <i class="bi bi-arrow-up"></i> 28% faster
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Quick Actions -->
                    <div class="col d-flex align-items-stretch">
                        <div class="card shadow-sm border-0 flex-fill h-100 py-4">
                            <div class="card-body text-center">
                                <div class="mb-3"><i class="bi bi-lightning-charge fs-1 text-info"></i></div>
                                <div class="d-grid gap-2">
                                    <a href="/superAdmin/users" class="btn btn-outline-primary btn-sm">Manage Users</a>
                                    <a href="/superAdmin/settings" class="btn btn-outline-success btn-sm">Settings</a>
                                    <a href="/superAdmin/roles" class="btn btn-outline-warning btn-sm">Manage Roles</a>
                                    <a href="/superAdmin/audit" class="btn btn-outline-dark btn-sm">Audit Logs</a>
                                    <a href="/superAdmin/agents" class="btn btn-outline-danger btn-sm">Manage Agents</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Overview Section -->
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-semibold mb-0">Support Overview</h5>
                            <div class="d-flex gap-2">
                                <a href="/superAdmin/agents" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-people me-1"></i> Manage Agents
                                </a>
                                <button class="btn btn-sm btn-outline-success" id="refreshStats">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh Stats
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions and Search -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Quick Search</h6>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Search tickets...">
                                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                                </div>

                                <h6 class="fw-semibold mb-3">Common Actions</h6>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                        <i class="bi bi-ticket me-2"></i> View All Tickets
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle me-2"></i> Urgent Tickets
                                    </button>
                                    <button class="btn btn-outline-success btn-sm d-flex align-items-center">
                                        <i class="bi bi-graph-up me-2"></i> Performance Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agent Performance -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Top Agents</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Agent</th>
                                                <th class="text-center">Tickets</th>
                                                <th class="text-center">Rating</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="align-middle">Clara J.</td>
                                                <td class="text-center">45 <small class="text-success">↑</small></td>
                                                <td class="text-center">4.9 ⭐</td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle">Mike Roach.</td>
                                                <td class="text-center">38 <small class="text-success">↑</small></td>
                                                <td class="text-center">4.8 ⭐</td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle">Franci P.</td>
                                                <td class="text-center">36 <small class="text-warning">→</small></td>
                                                <td class="text-center">4.7 ⭐</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Health & Activity Timeline -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-semibold mb-0">System Health</h5>
                                    <span class="badge bg-success">Online</span>
                                </div>
                                <div class="small mb-2">Uptime: <strong>99.98%</strong> &bull; Last Backup: <span class="text-success">Today</span></div>
                                <h6 class="fw-semibold mb-2 mt-3">Recent Activity</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 py-2">
                                        <i class="bi bi-ticket text-danger me-2"></i>
                                        New urgent ticket: Login issue (#1234)
                                        <small class="text-muted d-block">2 minutes ago</small>
                                    </li>
                                    <li class="list-group-item px-0 py-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Ticket #1230 resolved by Mike
                                        <small class="text-muted d-block">15 minutes ago</small>
                                    </li>
                                    <li class="list-group-item px-0 py-2">
                                        <i class="bi bi-headset text-primary me-2"></i>
                                        New agent Clara joined the team
                                        <small class="text-muted d-block">1 hour ago</small>
                                    </li>
                                    <li class="list-group-item px-0 py-2">
                                        <i class="bi bi-star-fill text-warning me-2"></i>
                                        5-star rating received for ticket #1228
                                        <small class="text-muted d-block">2 hours ago</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Usage Chart (condensed) with Filter -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-semibold mb-0">User Growth</h5>
                                    <select id="userGrowthFilter" class="form-select form-select-sm w-auto">
                                        <option value="7">Last 7 days</option>
                                        <option value="30">Last 30 days</option>
                                        <option value="90">Last 90 days</option>
                                    </select>
                                </div>
                                <canvas id="userGrowthChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to logout?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmLogoutBtn">Logout</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editProfileForm">
        <div class="modal-body">
          <div class="mb-3 text-center">
            <img src="/images/users/avatar-1.jpg" class="rounded-circle mb-2" width="64" height="64" alt="Avatar">
            <input type="file" class="form-control form-control-sm mt-2" id="editAvatar" accept="image/*">
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="editName" value="Denaked" placeholder="Name">
            <label for="editName">Name</label>
          </div>
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="editEmail" value="admin@yourdomain.com" placeholder="Email">
            <label for="editEmail">Email</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>


    // User Growth Chart (simple demo with filter)
    const userGrowthData = {
        '7': {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'New Users',
                data: [12, 19, 3, 5, 2, 3, 7],
                borderColor: '#4CAF50',
                tension: 0.1
            }]
        },
        '30': {
            labels: Array.from({length: 30}, (_, i) => `Day ${i + 1}`),
            datasets: [{
                label: 'New Users',
                data: Array.from({length: 30}, () => Math.floor(Math.random() * 20)),
                borderColor: '#4CAF50',
                tension: 0.1
            }]
        },
        '90': {
            labels: Array.from({length: 12}, (_, i) => `Week ${i + 1}`),
            datasets: [{
                label: 'New Users',
                data: Array.from({length: 12}, () => Math.floor(Math.random() * 100)),
                borderColor: '#4CAF50',
                tension: 0.1
            }]
        }
    };

    const ctx = document.getElementById('userGrowthChart').getContext('2d');
    let userGrowthChart = new Chart(ctx, {
        type: 'line',
        data: userGrowthData['7'],
        options: {
            responsive: true,
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

    document.getElementById('userGrowthFilter')?.addEventListener('change', function() {
        userGrowthChart.data = userGrowthData[this.value];
        userGrowthChart.update();
    });

    // Quick Ticket Search
    document.querySelector('.input-group input')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchTicket(this.value);
        }
    });

    document.querySelector('.input-group button')?.addEventListener('click', function() {
        const searchInput = this.previousElementSibling;
        searchTicket(searchInput.value);
    });

    function searchTicket(query) {
        if (!query.trim()) return;

        Swal.fire({
            title: 'Searching...',
            text: `Looking for ticket matching "${query}"`,
            timer: 1000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    }

    // Logout confirmation
    document.getElementById('confirmLogoutBtn')?.addEventListener('click', function() {
        Swal.fire({
            icon: 'success',
            title: 'Logged out',
            text: 'You have been logged out successfully!',
            timer: 1800,
            showConfirmButton: false
        });
        const modal = bootstrap.Modal.getInstance(document.getElementById('logoutModal'));
        modal.hide();
        setTimeout(() => { window.location.href = '/login'; }, 1800);
    });
    // Responsive Logout (SweetAlert)
    document.getElementById('logoutBtn')?.addEventListener('click', function() {
        Swal.fire({
            icon: 'question',
            title: 'Logout',
            text: 'Are you sure you want to logout?',
            showCancelButton: true,
            confirmButtonText: 'Logout',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Logged out',
                    text: 'You have been logged out successfully!',
                    timer: 1800,
                    showConfirmButton: false
                });
                setTimeout(() => { window.location.href = '/auth/login'; }, 1800);
            }
        });
    });
    // Responsive Lock Screen (SweetAlert)
    document.getElementById('lockScreenBtn')?.addEventListener('click', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Lock Screen',
            text: 'Do you want to lock the screen?',
            showCancelButton: true,
            confirmButtonText: 'Lock',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Screen Locked',
                    text: 'Your screen is now locked.',
                    timer: 1800,
                    showConfirmButton: false
                });
                setTimeout(() => { window.location.href = '/lock-screen'; }, 1800);
            }
        });
    });
    // Edit Profile Modal JS (demo: just closes modal and shows SweetAlert)
    document.getElementById('editProfileForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
        modal.hide();
        Swal.fire({
            icon: 'success',
            title: 'Profile Updated',
            text: 'Your changes have been saved.',
            timer: 1600,
            showConfirmButton: false
        });
    });
</script>
@endsection
