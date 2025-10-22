@push('styles')
<style>
    .transport-card { transition: all 0.3s ease; }
    .transport-card:hover { transform: translateY(-5px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1) !important; }
    .status-scheduled { border-left: 4px solid #3577f1; }
    .status-in-progress { border-left: 4px solid #f7b84b; }
    .status-completed { border-left: 4px solid #0ab39c; }
    .status-cancelled { border-left: 4px solid #f06548; }
</style>
@endpush

<div class="tab-pane fade" id="transport" role="tabpanel" aria-labelledby="transport-tab">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5>Transport Management</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransportRequestModal">
            <i class="fas fa-plus me-1"></i> New Transport Request
        </button>
    </div>

    <!-- Transport Requests Table -->
    <div class="card">
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Status</option>
                        <option>Scheduled</option>
                        <option>In Progress</option>
                        <option>Completed</option>
                        <option>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Vehicle Types</option>
                        <option>Car</option>
                        <option>Van</option>
                        <option>Truck</option>
                        <option>Bus</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search requests...">
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
                            <th>Request #</th>
                            <th>Passenger/Item</th>
                            <th>Vehicle Type</th>
                            <th>Pickup</th>
                            <th>Destination</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $transportRequests = [
                                [
                                    'id' => 'TR-1001',
                                    'type' => 'Passenger',
                                    'passenger_name' => 'John Doe',
                                    'vehicle_type' => 'Car',
                                    'pickup' => 'Office Main',
                                    'destination' => 'Airport',
                                    'schedule' => '2025-08-20 14:00',
                                    'status' => 'scheduled',
                                    'priority' => 'high',
                                    'notes' => 'Flight at 16:30, need to arrive by 14:30',
                                    'driver' => 'Michael Johnson'
                                ],
                                [
                                    'id' => 'TR-1002',
                                    'type' => 'Cargo',
                                    'item_description' => 'Office Supplies',
                                    'vehicle_type' => 'Van',
                                    'pickup' => 'Warehouse',
                                    'destination' => 'Office Main',
                                    'schedule' => '2025-08-20 10:30',
                                    'status' => 'in-progress',
                                    'priority' => 'medium',
                                    'notes' => 'Fragile items, handle with care',
                                    'driver' => 'Sarah Williams'
                                ],
                                [
                                    'id' => 'TR-1003',
                                    'type' => 'Passenger',
                                    'passenger_name' => 'Team Meeting Group',
                                    'vehicle_type' => 'Bus',
                                    'pickup' => 'Office Main',
                                    'destination' => 'Conference Center',
                                    'schedule' => '2025-08-19 08:00',
                                    'status' => 'completed',
                                    'priority' => 'low',
                                    'notes' => 'Team of 15 people',
                                    'driver' => 'Robert Brown'
                                ]
                            ];
                        @endphp

                        @foreach($transportRequests as $index => $request)
                            <tr class="align-middle status-{{ $request['status'] }}">
                                <td>{{ $request['id'] }}</td>
                                <td>
                                    @if($request['type'] === 'Passenger')
                                        <i class="fas fa-user me-1 text-primary"></i> {{ $request['passenger_name'] }}
                                    @else
                                        <i class="fas fa-box me-1 text-warning"></i> {{ $request['item_description'] ?? 'Cargo' }}
                                    @endif
                                    @if(isset($request['priority']) && $request['priority'] === 'high')
                                        <span class="badge bg-danger ms-2">High Priority</span>
                                    @endif
                                </td>
                                <td>{{ $request['vehicle_type'] }}</td>
                                <td>{{ $request['pickup'] }}</td>
                                <td>{{ $request['destination'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($request['schedule'])->format('M d, Y H:i') }}</td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'scheduled' => 'bg-info',
                                            'in-progress' => 'bg-warning',
                                            'completed' => 'bg-success',
                                            'cancelled' => 'bg-danger'
                                        ];
                                        $statusText = ucfirst(str_replace('-', ' ', $request['status']));
                                    @endphp
                                    <span class="badge {{ $statusClasses[$request['status']] ?? 'bg-secondary' }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#viewTransportModal{{ $index }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editTransportModal{{ $index }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelTransportModal{{ $index }}">
                                        <i class="fas fa-times"></i>
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
                    <div class="dataTables_info">Showing 1 to 3 of 3 entries</div>
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

<!-- Include Transport Modals -->
@include('company.ProjectManagement.modals.transport-modals')
