@push('styles')
<style>
    .severity-low { border-left: 4px solid #0ab39c; }
    .severity-medium { border-left: 4px solid #f7b84b; }
    .severity-high { border-left: 4px solid #f06548; }
    .severity-critical { border-left: 4px solid #8b0000; }
    .status-open { background-color: rgba(13, 110, 253, 0.1); }
    .status-investigating { background-color: rgba(255, 193, 7, 0.1); }
    .status-resolved { background-color: rgba(25, 135, 84, 0.1); }
</style>
@endpush

<div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5>Security Incidents</h5>
        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#reportIncidentModal">
            <i class="fas fa-plus me-1"></i> Report Incident
        </button>
    </div>

    <!-- Enhanced Incident Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-start border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-1">Open Incidents</h6>
                            <h2 class="mb-0">5</h2>
                            <div class="mt-2">
                                <span class="badge bg-soft-warning text-warning">
                                    <i class="fas fa-arrow-up me-1"></i> 12% from last month
                                </span>
                            </div>
                        </div>
                        <div class="avatar-lg rounded-circle bg-soft-warning text-center d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 1.75rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Investigation</small>
                            <small class="fw-bold">3</small>
                        </div>
                        <div class="progress mt-1" style="height: 4px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card h-100 border-start border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-1">High Severity</h6>
                            <h2 class="mb-0">2</h2>
                            <div class="mt-2">
                                <span class="badge bg-soft-danger text-danger">
                                    <i class="fas fa-exclamation-circle me-1"></i> Immediate attention
                                </span>
                            </div>
                        </div>
                        <div class="avatar-lg rounded-circle bg-soft-danger text-center d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-shield-alt text-danger" style="font-size: 1.75rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Critical: 1</small>
                            <small class="text-muted">High: 1</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card h-100 border-start border-3 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-1">This Month</h6>
                            <h2 class="mb-0">12</h2>
                            <div class="mt-2">
                                <span class="badge bg-soft-success text-success">
                                    <i class="fas fa-arrow-down me-1"></i> 8% from last month
                                </span>
                            </div>
                        </div>
                        <div class="avatar-lg rounded-circle bg-soft-info text-center d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-calendar-alt text-info" style="font-size: 1.75rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Avg. resolution: 2.3 days</small>
                            <i class="fas fa-info-circle text-muted" data-bs-toggle="tooltip" title="Average time to resolve incidents this month"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card h-100 border-start border-3 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-1">Resolved</h6>
                            <h2 class="mb-0">24</h2>
                            <div class="mt-2">
                                <span class="badge bg-soft-success text-success">
                                    <i class="fas fa-check-circle me-1"></i> 92% success rate
                                </span>
                            </div>
                        </div>
                        <div class="avatar-lg rounded-circle bg-soft-success text-center d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-check-circle text-success" style="font-size: 1.75rem;"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">This year: 145</small>
                            <i class="fas fa-chart-line text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incident List -->
    <div class="card">
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Status</option>
                        <option>Open</option>
                        <option>Investigating</option>
                        <option>Resolved</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Severity</option>
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>
                        <option>Critical</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search incidents...">
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-centered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Incident #</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Reported</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $incidents = [
                                [
                                    'id' => 'SEC-2023-001',
                                    'type' => 'Unauthorized Access',
                                    'description' => 'Attempted access to restricted server room',
                                    'location' => 'Server Room B',
                                    'reported_at' => now()->subHours(2),
                                    'severity' => 'high',
                                    'status' => 'investigating',
                                    'reported_by' => 'John Smith'
                                ],
                                [
                                    'id' => 'SEC-2023-002',
                                    'type' => 'Suspicious Package',
                                    'description' => 'Unattended bag found in lobby',
                                    'location' => 'Main Lobby',
                                    'reported_at' => now()->subDays(1),
                                    'severity' => 'medium',
                                    'status' => 'resolved',
                                    'reported_by' => 'Reception Desk'
                                ],
                                [
                                    'id' => 'SEC-2023-003',
                                    'type' => 'Data Breach Attempt',
                                    'description' => 'Multiple failed login attempts detected',
                                    'location' => 'Remote Access',
                                    'reported_at' => now()->subDays(3),
                                    'severity' => 'critical',
                                    'status' => 'investigating',
                                    'reported_by' => 'IT Security'
                                ],
                                [
                                    'id' => 'SEC-2023-004',
                                    'type' => 'Lost Access Card',
                                    'description' => 'Employee reported lost access card',
                                    'location' => 'Parking Lot',
                                    'reported_at' => now()->subDays(5),
                                    'severity' => 'low',
                                    'status' => 'open',
                                    'reported_by' => 'Sarah Johnson'
                                ]
                            ];
                        @endphp

                        @foreach($incidents as $index => $incident)
                            <tr class="align-middle severity-{{ $incident['severity'] }} status-{{ $incident['status'] }}">
                                <td>{{ $incident['id'] }}</td>
                                <td>{{ $incident['type'] }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $incident['description'] }}</div>
                                    <small class="text-muted">Reported by {{ $incident['reported_by'] }}</small>
                                </td>
                                <td>{{ $incident['location'] }}</td>
                                <td>
                                    {{ $incident['reported_at']->diffForHumans() }}
                                    <div class="text-muted small">{{ $incident['reported_at']->format('M d, Y H:i') }}</div>
                                </td>
                                <td>
                                    @php
                                        $severityClasses = [
                                            'low' => 'bg-success',
                                            'medium' => 'bg-warning',
                                            'high' => 'bg-danger',
                                            'critical' => 'bg-dark'
                                        ];
                                        $severityText = ucfirst($incident['severity']);
                                    @endphp
                                    <span class="badge {{ $severityClasses[$incident['severity']] ?? 'bg-secondary' }}">
                                        {{ $severityText }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'open' => 'bg-primary',
                                            'investigating' => 'bg-warning',
                                            'resolved' => 'bg-success'
                                        ];
                                        $statusText = ucfirst($incident['status']);
                                    @endphp
                                    <span class="badge {{ $statusClasses[$incident['status']] ?? 'bg-secondary' }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#viewIncidentModal{{ $index }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#updateIncidentModal{{ $index }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="row mt-4">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info">Showing 1 to 4 of 4 entries</div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination justify-content-end">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
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

<!-- Include Security Modals -->
@include('company.ProjectManagement.modals.security-modals')
