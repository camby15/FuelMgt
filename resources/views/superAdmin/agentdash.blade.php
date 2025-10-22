@extends('layouts.vertical-admin', ['page_title' => 'Agent Dashboard'])

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
        [data-bs-theme="dark"] .form-floating input.form-control:focus ~ label,
        [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown) ~ label,
        [data-bs-theme="dark"] .form-floating select.form-select:focus ~ label,
        [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]) ~ label,
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus ~ label,
        [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) ~ label {
            color: #fff;
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

        /* Status badges */
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            border-radius: 0.25rem;
            text-transform: uppercase;
        }
        .status-badge.open {
            background-color: #0dcaf0;
            color: #000;
        }
        .status-badge.in-progress {
            background-color: #fd7e14;
            color: #fff;
        }
        .status-badge.completed {
            background-color: #198754;
            color: #fff;
        }
        .status-badge.closed {
            background-color: #6c757d;
            color: #fff;
        }
        .status-badge.urgent {
            background-color: #dc3545;
            color: #fff;
        }

        /* Availability toggle */
        .availability-toggle {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }
        .availability-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .availability-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .availability-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .availability-slider {
            background-color: #198754;
        }
        input:focus + .availability-slider {
            box-shadow: 0 0 1px #198754;
        }
        input:checked + .availability-slider:before {
            transform: translateX(30px);
        }

        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        .action-btn i {
            font-size: 0.875rem;
        }

        /* Dashboard cards */
        .dashboard-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .dashboard-card .card-body {
            padding: 1.5rem;
        }
        .dashboard-card .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .dashboard-card .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .dashboard-card .card-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        /* Timeline styles */
        .timeline {
            position: relative;
            padding-left: 1.5rem;
        }
        .timeline:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 2px;
            background: #e9ecef;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }
        .timeline-item:last-child {
            padding-bottom: 0;
        }
        .timeline-item:before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 0.25rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #0dcaf0;
            border: 2px solid #fff;
        }
        [data-bs-theme="dark"] .timeline:before {
            background: #495057;
        }
        [data-bs-theme="dark"] .timeline-item:before {
            border-color: #212529;
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
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <label class="form-check-label me-2">Availability Status:</label>
                                <label class="availability-toggle">
                                    <input type="checkbox" id="availabilityToggle" checked>
                                    <span class="availability-slider"></span>
                                </label>
                                <span id="availabilityStatus" class="ms-2 text-success">Available</span>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reportCompletionModal">
                                <i class="fas fa-check-circle me-1"></i> Report Task Completion
                            </button>
                        </div>
                    </div>
                    <h4 class="page-title">Agent Dashboard</h4>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-primary bg-opacity-25 text-primary">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div class="ms-auto text-end">
                                <h5 class="card-title mb-0">Total Assigned</h5>
                                <h2 class="card-value mb-0">24</h2>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-center">
                                <span class="flex-grow-1">Your total assigned tickets</span>
                                <span class="badge bg-primary-subtle text-primary">+5 today</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-warning bg-opacity-25 text-warning">
                                <i class="fas fa-spinner"></i>
                            </div>
                            <div class="ms-auto text-end">
                                <h5 class="card-title mb-0">In Progress</h5>
                                <h2 class="card-value mb-0">8</h2>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-center">
                                <span class="flex-grow-1">Tickets you're working on</span>
                                <span class="badge bg-warning-subtle text-warning">2 urgent</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-success bg-opacity-25 text Succes
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="ms-auto text-end">
                                <h5 class="card-title mb-0">Completed</h5>
                                <h2 class="card-value mb-0">16</h2>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-center">
                                <span class="flex-grow-1">Successfully completed</span>
                                <span class="badge bg-success-subtle text-success">+3 today</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-info bg-opacity-25 text-info">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="ms-auto text-end">
                                <h5 class="card-title mb-0">Pending Review</h5>
                                <h2 class="card-value mb-0">5</h2>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-center">
                                <span class="flex-grow-1">Awaiting approval</span>
                                <span class="badge bg-info-subtle text-info">1 day avg</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">My Assigned Tickets</h4>
                        <div class="d-flex">
                            <div class="form-floating me-2" style="width: 200px;">
                                <select class="form-select" id="ticketStatusFilter">
                                    <option value="all">All Statuses</option>
                                    <option value="open">Open</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="closed">Closed</option>
                                </select>
                                <label for="ticketStatusFilter">Filter by Status</label>
                            </div>
                            <div class="form-floating" style="width: 200px;">
                                <select class="form-select" id="ticketPriorityFilter">
                                    <option value="all">All Priorities</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                                <label for="ticketPriorityFilter">Filter by Priority</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="tickets-table">
                                <thead>
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>Subject</th>
                                        <th>Client</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Assigned Date</th>
                                        <th>Due Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach([
                                        ['id' => 'TKT-001', 'subject' => 'Network configuration issue', 'client' => 'ShrinQ Inc', 'priority' => 'Urgent', 'status' => 'In Progress', 'assigned' => '2025-05-16', 'due' => '2025-05-19'],
                                        ['id' => 'TKT-002', 'subject' => 'Email server migration', 'client' => 'Witech group', 'priority' => 'High', 'status' => 'Open', 'assigned' => '2025-05-17', 'due' => '2025-05-20'],
                                        ['id' => 'TKT-003', 'subject' => 'Software installation', 'client' => 'Alkes comm', 'priority' => 'Medium', 'status' => 'In Progress', 'assigned' => '2025-05-15', 'due' => '2025-05-18'],
                                        ['id' => 'TKT-004', 'subject' => 'Printer configuration', 'client' => 'Panga ltd', 'priority' => 'Low', 'status' => 'Completed', 'assigned' => '2025-05-14', 'due' => '2025-05-17'],
                                        ['id' => 'TKT-005', 'subject' => 'VPN access setup', 'client' => 'MTN Group', 'priority' => 'Medium', 'status' => 'Completed', 'assigned' => '2025-05-13', 'due' => '2025-05-16']
                                    ] as $ticket)
                                        <tr>
                                            <td><a href="#" class="text-body fw-bold">#{{ $ticket['id'] }}</a></td>
                                            <td>{{ $ticket['subject'] }}</td>
                                            <td>{{ $ticket['client'] }}</td>
                                            <td><span class="badge bg-{{ $ticket['priority'] === 'Urgent' ? 'danger' : ($ticket['priority'] === 'High' ? 'warning' : ($ticket['priority'] === 'Medium' ? 'info' : 'secondary')) }}">{{ $ticket['priority'] }}</span></td>
                                            <td><span class="status-badge {{ strtolower(str_replace(' ', '-', $ticket['status'])) }}">{{ $ticket['status'] }}</span></td>
                                            <td>{{ $ticket['assigned'] }}</td>
                                            <td>{{ $ticket['due'] }}</td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewTicketModal" data-ticket-id="{{ $ticket['id'] }}">
                                                            <i class="fas fa-eye me-1"></i> View Details
                                                        </a>
                                                        @if($ticket['status'] !== 'Completed' && $ticket['status'] !== 'Closed')
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#updateStatusModal" data-ticket-id="{{ $ticket['id'] }}">
                                                                <i class="fas fa-edit me-1"></i> Update Status
                                                            </a>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reportCompletionModal" data-ticket-id="{{ $ticket['id'] }}">
                                                                <i class="fas fa-check-circle me-1"></i> Mark Complete
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Timeline -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Recent Activity</h4>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach([
                                ['title' => 'Ticket #TKT-005 marked as completed', 'time' => 'Today at 10:45 AM', 'description' => 'Successfully configured VPN access for MTN Group'],
                                ['title' => 'Started working on Ticket #TKT-001', 'time' => 'Today at 9:30 AM', 'description' => 'Network configuration issue for ShrinQ Inc'],
                                ['title' => 'Ticket #TKT-004 marked as completed', 'time' => 'Yesterday at 3:15 PM', 'description' => 'Printer configuration for Panga ltd completed successfully'],
                                ['title' => 'New ticket #TKT-003 assigned', 'time' => 'Yesterday at 11:20 AM', 'description' => 'Software installation for Alkes comm'],
                            ] as $activity)
                                <div class="timeline-item">
                                    <h5 class="fw-bold">{{ $activity['title'] }}</h5>
                                    <p class="text-muted mb-0">{{ $activity['time'] }}</p>
                                    <p>{{ $activity['description'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Upcoming Deadlines</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover">
                                <thead>
                                    <tr>
                                        <th>Ticket</th>
                                        <th>Client</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach([
                                        ['id' => 'TKT-001', 'client' => 'ShrinQ Inc', 'due' => '2025-05-19', 'status' => 'In Progress'],
                                        ['id' => 'TKT-002', 'client' => 'Witech group', 'due' => '2025-05-20', 'status' => 'Open'],
                                        ['id' => 'TKT-003', 'client' => 'Alkes comm', 'due' => '2025-05-18', 'status' => 'In Progress']
                                    ] as $deadline)
                                        <tr>
                                            <td><a href="#" class="text-body fw-bold">#{{ $deadline['id'] }}</a></td>
                                            <td>{{ $deadline['client'] }}</td>
                                            <td class="{{ $deadline['due'] <= date('Y-m-d') ? 'text-danger' : '' }} fw-bold">{{ $deadline['due'] }}</td>
                                            <td><span class="status-badge {{ strtolower(str_replace(' ', '-', $deadline['status'])) }}">{{ $deadline['status'] }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Ticket Modal -->
        <div class="modal fade" id="viewTicketModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ticket Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ticket ID:</label>
                                    <p id="view-ticket-id">#TKT-001</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status:</label>
                                    <p><span class="status-badge in-progress" id="view-ticket-status">In Progress</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Client:</label>
                                    <p id="view-ticket-client">ShrinQ Inc</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Priority:</label>
                                    <p><span class="badge bg-danger" id="view-ticket-priority">Urgent</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Assigned Date:</label>
                                    <p id="view-ticket-assigned">2025-05-16</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Due Date:</label>
                                    <p id="view-ticket-due">2025-05-19</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subject:</label>
                            <p id="view-ticket-subject">Network configuration issue</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description:</label>
                            <div class="p-3 bg-light rounded" id="view-ticket-description">
                                <p>The client is experiencing network connectivity issues in their main office. Several workstations are unable to connect to the company server. Initial diagnosis suggests it might be related to recent router configuration changes.</p>
                                <p>Tasks required:</p>
                                <ul>
                                    <li>Check router configuration</li>
                                    <li>Verify network switch settings</li>
                                    <li>Test connectivity from multiple workstations</li>
                                    <li>Update network documentation after resolution</li>
                                </ul>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Attachments:</label>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-pdf me-1"></i> Network_Diagram.pdf</a>
                                <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-image me-1"></i> Error_Screenshot.png</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">Update Status</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Status Modal -->
        <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Ticket Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateStatusForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Ticket ID</label>
                                <input type="text" class="form-control" id="update-ticket-id" readonly>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" id="update-status" required>
                                    <option value="">Select Status</option>
                                    <option value="open">Open</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="closed">Closed</option>
                                </select>
                                <label for="update-status">Status</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="update-notes" style="height: 100px" placeholder=" " required></textarea>
                                <label for="update-notes">Status Update Notes</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Report Task Completion Modal -->
        <div class="modal fade" id="reportCompletionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Report Task Completion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="reportCompletionForm">
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="completion-ticket-id" required>
                                    <option value="">Select Ticket</option>
                                    <option value="TKT-001">TKT-001 - Network configuration issue</option>
                                    <option value="TKT-002">TKT-002 - Email server migration</option>
                                    <option value="TKT-003">TKT-003 - Software installation</option>
                                </select>
                                <label for="completion-ticket-id">Ticket</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="completion-summary" style="height: 100px" placeholder=" " required></textarea>
                                <label for="completion-summary">Completion Summary</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="completion-time-spent" min="1" placeholder=" " required>
                                <label for="completion-time-spent">Time Spent (hours)</label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Attachments (if any)</label>
                                <input type="file" class="form-control" id="completion-attachments" multiple>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#tickets-table').DataTable({
                responsive: true,
                lengthChange: false,
                pageLength: 10,
                buttons: ['copy', 'excel', 'pdf', 'colvis'],
                columnDefs: [
                    { targets: 4, data: null, render: function(data, type, row) {
                        return `<span class="status-badge ${data.toLowerCase().replace(' ', '-')}">${data}</span>`;
                    }},
                    { targets: 3, data: null, render: function(data, type, row) {
                        const badgeClass = {
                            'Urgent': 'danger',
                            'High': 'warning',
                            'Medium': 'info',
                            'Low': 'secondary'
                        }[data] || 'secondary';
                        return `<span class="badge bg-${badgeClass}">${data}</span>`;
                    }}
                ]
            });

            // Availability toggle
            $('#availabilityToggle').change(function() {
                const isAvailable = $(this).is(':checked');
                $('#availabilityStatus')
                    .text(isAvailable ? 'Available' : 'Unavailable')
                    .removeClass(isAvailable ? 'text-danger' : 'text-success')
                    .addClass(isAvailable ? 'text-success' : 'text-danger');
                updateAvailabilityStatus(isAvailable);
            });

            // Ticket filters
            $('#ticketStatusFilter, #ticketPriorityFilter').change(function() {
                const status = $('#ticketStatusFilter').val();
                const priority = $('#ticketPriorityFilter').val();

                table.columns(4).search(status === 'all' ? '' : status).draw();
                table.columns(3).search(priority === 'all' ? '' : priority).draw();
            });

            // View ticket modal
            $('#viewTicketModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const ticketId = button.data('ticket-id');
                $('#view-ticket-id').text('#' + ticketId);
                $('#update-ticket-id').val(ticketId);
                // In a real app, fetch ticket details via AJAX
            });

            // Update status form submission
            $('#updateStatusForm').submit(function(e) {
                e.preventDefault();
                const ticketId = $('#update-ticket-id').val();
                const status = $('#update-status').val();
                const notes = $('#update-notes').val();

                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated!',
                    text: `Ticket #${ticketId} status has been updated to ${status}.`,
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#updateStatusModal').modal('hide');
                });
            });

            // Report completion form submission
            $('#reportCompletionForm').submit(function(e) {
                e.preventDefault();
                const ticketId = $('#completion-ticket-id').val();
                const summary = $('#completion-summary').val();
                const timeSpent = $('#completion-time-spent').val();

                Swal.fire({
                    icon: 'success',
                    title: 'Task Completed!',
                    text: `Completion report for Ticket #${ticketId} has been submitted successfully.`,
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#reportCompletionForm')[0].reset();
                    $('#reportCompletionModal').modal('hide');
                });
            });

            // Function to update availability status (stub for real app)
            function updateAvailabilityStatus(isAvailable) {
                console.log('Agent availability status updated:', isAvailable ? 'Available' : 'Unavailable');
                // Implement AJAX call to server here
            }
        });

        // Show success/error messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ok'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Ok'
            });
        @endif
    </script>
@endsection