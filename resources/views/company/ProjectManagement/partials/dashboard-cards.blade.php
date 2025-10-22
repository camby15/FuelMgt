
<div class="container-fluid">
    <div class="row">

        @php
            $cards = [
                [
                    'title' => 'Open Tickets',
                    'count' => 25,
                    'icon' => 'ticket-alt',
                    'bg' => 'primary',
                    'modal' => 'tickets',
                    'stats' => [
                        'line1' => 'Resolved: 10',
                        'line2' => 'Pending: 15',
                        'badge' => [
                            'text' => '5 New',
                            'icon' => 'plus',
                            'class' => 'success'
                        ]
                    ],
                    'details' => [
                        'Ticket #101 - Pending',
                        'Ticket #102 - Resolved',
                        'Ticket #103 - Pending',
                        'Ticket #104 - In Progress',
                    ]
                ],
                [
                    'title' => 'Pending Approvals',
                    'count' => 8,
                    'icon' => 'clipboard-check',
                    'bg' => 'warning',
                    'modal' => 'approvals',
                    'stats' => [
                        'line1' => 'Today: 3',
                        'line2' => 'This Week: 5',
                        'badge' => [
                            'text' => 'Urgent',
                            'icon' => 'exclamation',
                            'class' => 'danger'
                        ]
                    ],
                    'details' => [
                        'Approval #201 - Pending',
                        'Approval #202 - Pending',
                        'Approval #203 - Urgent',
                    ]
                ],
                [
                    'title' => 'Ongoing Maintenance',
                    'count' => 4,
                    'icon' => 'tools',
                    'bg' => 'success',
                    'modal' => 'maintenance',
                    'stats' => [
                        'line1' => 'Electrical: 2',
                        'line2' => 'Mechanical: 2',
                        'badge' => [
                            'text' => 'In Progress',
                            'icon' => 'spinner',
                            'class' => 'info',
                            'prefix' => 'fas fa-spin'
                        ]
                    ],
                    'details' => [
                        'Maintenance #301 - Electrical',
                        'Maintenance #302 - Mechanical',
                        'Maintenance #303 - Electrical',
                        'Maintenance #304 - Mechanical',
                    ]
                ],
                [
                    'title' => 'Upcoming Inspections',
                    'count' => 12,
                    'icon' => 'search',
                    'bg' => 'danger',
                    'modal' => 'inspections',
                    'stats' => [
                        'line1' => 'Next: Tomorrow, 10:00 AM',
                        'line2' => '3 Facilities',
                        'badge' => [
                            'text' => 'Reminder',
                            'icon' => 'bell',
                            'class' => 'info',
                            'prefix' => 'far'
                        ]
                    ],
                    'details' => [
                        'Inspection #401 - Scheduled',
                        'Inspection #402 - Scheduled',
                        'Inspection #403 - Scheduled',
                    ]
                ]
            ];
        @endphp

        <!-- Render Cards -->
        @foreach($cards as $card)
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-{{ $card['bg'] }} shadow"
                 onclick="showModal('{{ $card['modal'] }}')"
                 style="cursor:pointer;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-{{ $card['icon'] }} fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="card-title">{{ $card['title'] }}</h5>
                            <h3>{{ $card['count'] }}</h3>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small>{{ $card['stats']['line1'] }}</small><br>
                        <small>{{ $card['stats']['line2'] }}</small>
                        @if(isset($card['stats']['badge']))
                            <span class="badge bg-{{ $card['stats']['badge']['class'] }}">
                                <i class="{{ $card['stats']['badge']['prefix'] ?? 'fas' }} fa-{{ $card['stats']['badge']['icon'] }}"></i>
                                {{ $card['stats']['badge']['text'] }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Monthly Overview</h5>
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Task Distribution</h5>
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0 fw-semibold">Recent Activity</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item active" href="#">All Activities</a></li>
                            <li><a class="dropdown-item" href="#">Tickets</a></li>
                            <li><a class="dropdown-item" href="#">Maintenance</a></li>
                            <li><a class="dropdown-item" href="#">Inspections</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <!-- Activity Item 1 -->
                        <div class="list-group-item border-0 border-bottom p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 text-truncate">New Ticket Created</h6>
                                        <small class="text-muted">2 min ago</small>
                                    </div>
                                    <p class="mb-1 text-muted">User <strong>John Doe</strong> created a new support ticket</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-soft-primary text-primary me-2">#TKT-1001</span>
                                        <small class="text-muted">AC not working in Conference Room</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activity Item 2 -->
                        <div class="list-group-item border-0 border-bottom p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm bg-soft-warning text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 text-truncate">Maintenance Update</h6>
                                        <small class="text-muted">1 hour ago</small>
                                    </div>
                                    <p class="mb-1 text-muted">Maintenance task <strong>#MT-102</strong> status updated</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-soft-warning text-warning me-2">In Progress</span>
                                        <small class="text-muted">Plumbing issue in 2nd floor restroom</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activity Item 3 -->
                        <div class="list-group-item border-0 p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm bg-soft-success text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 text-truncate">Inspection Scheduled</h6>
                                        <small class="text-muted">3 hours ago</small>
                                    </div>
                                    <p class="mb-1 text-muted">Quarterly safety inspection scheduled</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-soft-info text-info me-2">Scheduled</span>
                                        <small class="text-muted">Facility A - Oct 15, 2023, 10:00 AM</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3 text-center">
                    <a href="#" class="text-primary fw-medium">View All Activity <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1rem;
    }
    .list-group-item {
        transition: all 0.2s;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    </style>
</div>

<!-- Dynamic Modals -->
@foreach($cards as $card)
<div class="modal fade" id="{{ $card['modal'] }}Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-{{ $card['bg'] }} text-white">
                <h5 class="modal-title">{{ $card['title'] }} Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @foreach($card['details'] as $detail)
                        <li class="list-group-item">{{ $detail }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function showModal(modalId) {
    var myModal = new bootstrap.Modal(document.getElementById(modalId + 'Modal'));
    myModal.show();
}

// Charts
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('lineChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [{
                label: 'Tickets',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.2)',
                fill: true
            }]
        }
    });

    new Chart(document.getElementById('doughnutChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Tickets','Approvals','Maintenance','Inspections'],
            datasets: [{
                data: [25,8,4,12],
                backgroundColor: ['#007bff','#ffc107','#28a745','#dc3545']
            }]
        }
    });
});
</script>

