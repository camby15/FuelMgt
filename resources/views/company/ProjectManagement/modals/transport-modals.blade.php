<!-- Add Transport Request Modal -->
<div class="modal fade" id="addTransportRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">New Transport Request</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTransportRequestForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Request Type</label>
                                <select class="form-select" id="transportType">
                                    <option value="passenger">Passenger Transport</option>
                                    <option value="cargo">Cargo Transport</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Priority</label>
                                <select class="form-select" required>
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Passenger Fields -->
                    <div id="passengerFields">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Passenger Name</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Number of Passengers</label>
                                    <input type="number" class="form-control" value="1" min="1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cargo Fields (Hidden by default) -->
                    <div id="cargoFields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Item Description</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="number" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vehicle Type</label>
                                <select class="form-select" required>
                                    <option value="">Select Vehicle</option>
                                    <option>Car (1-4 passengers)</option>
                                    <option>Van (5-8 passengers)</option>
                                    <option>Minibus (9-15 passengers)</option>
                                    <option>Bus (16+ passengers)</option>
                                    <option>Truck (Cargo)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pickup Date & Time</label>
                                <input type="datetime-local" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pickup Location</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Destination</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Additional Notes</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Transport Request Modal -->
@php
    $transportRequests = [
        [
            'id' => 'TR-1001',
            'type' => 'Passenger',
            'passenger_name' => 'John Doe',
            'vehicle_type' => 'Car',
            'pickup' => 'Office Main',
            'destination' => 'Airport',
            'schedule' => now()->addDays(1)->format('Y-m-d H:i:s'),
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
            'schedule' => now()->addHours(2)->format('Y-m-d H:i:s'),
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
            'schedule' => now()->subDays(1)->format('Y-m-d H:i:s'),
            'status' => 'completed',
            'priority' => 'low',
            'notes' => 'Team of 15 people',
            'driver' => 'Robert Brown'
        ]
    ];
@endphp

@foreach($transportRequests as $index => $request)
<!-- View Transport Modal -->
<!-- View Transport Modal -->
<div class="modal fade" id="viewTransportModal{{ $index }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Transport Request #{{ $request['id'] }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6>Request Details</h6>
                    <p><strong>Type:</strong> {{ ucfirst($request['type']) }}</p>
                    <p><strong>{{ $request['type'] === 'Passenger' ? 'Passenger' : 'Item' }}:</strong> 
                        {{ $request['type'] === 'Passenger' ? $request['passenger_name'] : $request['item_description'] }}
                        @if($request['priority'] === 'high')
                            <span class="badge bg-danger ms-2">High Priority</span>
                        @endif
                    </p>
                    <p><strong>Vehicle:</strong> {{ $request['vehicle_type'] }}</p>
                    <p><strong>Status:</strong> 
                        @php
                            $statusClass = [
                                'scheduled' => 'bg-info',
                                'in-progress' => 'bg-warning',
                                'completed' => 'bg-success',
                                'cancelled' => 'bg-danger'
                            ][$request['status']] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($request['status']) }}</span>
                    </p>
                </div>
                <div class="mb-3">
                    <h6>Trip Details</h6>
                    <p><strong>Pickup:</strong> {{ $request['pickup'] }}</p>
                    <p><strong>Destination:</strong> {{ $request['destination'] }}</p>
                    <p><strong>Scheduled:</strong> {{ \Carbon\Carbon::parse($request['schedule'])->format('M d, Y H:i') }}</p>
                    @if(!empty($request['driver']))
                        <p><strong>Driver:</strong> {{ $request['driver'] }}</p>
                    @endif
                </div>
                @if(!empty($request['notes']))
                    <div class="alert alert-light">
                        <h6>Notes:</h6>
                        <p class="mb-0">{{ $request['notes'] }}</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Print Details</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Transport Modal -->
<div class="modal fade" id="editTransportModal{{ $index }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Edit Transport Request #{{ $request['id'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option value="scheduled" {{ $request['status'] === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="in-progress" {{ $request['status'] === 'in-progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $request['status'] === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $request['status'] === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Driver</label>
                        <input type="text" class="form-control" value="{{ $request['driver'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vehicle</label>
                        <input type="text" class="form-control" value="{{ $request['vehicle_type'] }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3">{{ $request['notes'] ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Transport Modal -->
<div class="modal fade" id="cancelTransportModal{{ $index }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Cancel Transport Request</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this transport request?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This action cannot be undone. A notification will be sent to all relevant parties.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for cancellation</label>
                        <select class="form-select">
                            <option>Select a reason...</option>
                            <option>No longer needed</option>
                            <option>Duplicate request</option>
                            <option>Schedule conflict</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional notes</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
                    <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    // Toggle between passenger and cargo fields
    document.addEventListener('DOMContentLoaded', function() {
        const transportType = document.getElementById('transportType');
        if (transportType) {
            transportType.addEventListener('change', function() {
                const type = this.value;
                const passengerFields = document.getElementById('passengerFields');
                const cargoFields = document.getElementById('cargoFields');
                
                if (passengerFields) {
                    passengerFields.style.display = type === 'passenger' ? 'block' : 'none';
                }
                if (cargoFields) {
                    cargoFields.style.display = type === 'cargo' ? 'block' : 'none';
                }
            });
        }
    });
</script>
@endpush
