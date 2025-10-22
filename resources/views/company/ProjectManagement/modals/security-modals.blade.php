<!-- Report Incident Modal -->
<div class="modal fade" id="reportIncidentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Report Security Incident</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reportIncidentForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Incident Type <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="">Select type</option>
                                    <option>Unauthorized Access</option>
                                    <option>Suspicious Activity</option>
                                    <option>Data Breach</option>
                                    <option>Lost/Stolen Item</option>
                                    <option>Policy Violation</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Severity <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="low">Low - Minor issue</option>
                                    <option value="medium" selected>Medium - Standard issue</option>
                                    <option value="high">High - Major issue</option>
                                    <option value="critical">Critical - Emergency</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date & Time of Incident</label>
                                <input type="datetime-local" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Attach Files</label>
                                <input type="file" class="form-control" multiple>
                                <small class="text-muted">Upload photos, documents, or other evidence</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        For emergencies requiring immediate assistance, please contact security at extension 911.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Incident Modal -->
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
            'reported_by' => 'John Smith',
            'assigned_to' => 'Security Team',
            'details' => 'Security camera detected unauthorized personnel attempting to access the server room without proper credentials. Incident was caught by security personnel.'
        ]
    ];
@endphp

@foreach($incidents as $index => $incident)
<div class="modal fade" id="viewIncidentModal{{ $index }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Incident #{{ $incident['id'] }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Incident Details</h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Type:</dt>
                            <dd class="col-sm-8">{{ $incident['type'] }}</dd>
                            
                            <dt class="col-sm-4">Status:</dt>
                            <dd class="col-sm-8">
                                @php
                                    $statusClass = [
                                        'open' => 'bg-primary',
                                        'investigating' => 'bg-warning',
                                        'resolved' => 'bg-success'
                                    ][$incident['status']] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($incident['status']) }}
                                </span>
                            </dd>
                            
                            <dt class="col-sm-4">Severity:</dt>
                            <dd class="col-sm-8">
                                @php
                                    $severityClass = [
                                        'low' => 'bg-success',
                                        'medium' => 'bg-warning',
                                        'high' => 'bg-danger',
                                        'critical' => 'bg-dark'
                                    ][$incident['severity']] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $severityClass }}">
                                    {{ ucfirst($incident['severity']) }}
                                </span>
                            </dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h6>Location & Reporting</h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Location:</dt>
                            <dd class="col-sm-8">{{ $incident['location'] }}</dd>
                            
                            <dt class="col-sm-4">Reported:</dt>
                            <dd class="col-sm-8">
                                {{ $incident['reported_at']->format('M d, Y H:i') }}
                                <div class="text-muted small">{{ $incident['reported_at']->diffForHumans() }}</div>
                            </dd>
                            
                            <dt class="col-sm-4">Reported By:</dt>
                            <dd class="col-sm-8">{{ $incident['reported_by'] }}</dd>
                            
                            <dt class="col-sm-4">Assigned To:</dt>
                            <dd class="col-sm-8">{{ $incident['assigned_to'] }}</dd>
                        </dl>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Incident Description</h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            <p class="mb-0">{{ $incident['details'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Investigation Updates</h6>
                    <div class="timeline-2">
                        <div class="timeline-item">
                            <div class="timeline-badge bg-primary"><i class="fas fa-flag"></i></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Incident Reported</h6>
                                <p class="text-muted small mb-0">{{ $incident['reported_at']->format('M d, Y H:i') }}</p>
                                <p class="mb-0">Incident was reported by {{ $incident['reported_by'] }}</p>
                            </div>
                        </div>
                        @if($incident['status'] === 'investigating' || $incident['status'] === 'resolved')
                        <div class="timeline-item">
                            <div class="timeline-badge bg-warning"><i class="fas fa-search"></i></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Investigation Started</h6>
                                <p class="text-muted small mb-0">{{ $incident['reported_at']->addMinutes(15)->format('M d, Y H:i') }}</p>
                                <p class="mb-0">Assigned to {{ $incident['assigned_to'] }} for investigation</p>
                            </div>
                        </div>
                        @endif
                        @if($incident['status'] === 'resolved')
                        <div class="timeline-item">
                            <div class="timeline-badge bg-success"><i class="fas fa-check"></i></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Incident Resolved</h6>
                                <p class="text-muted small mb-0">{{ $incident['reported_at']->addHours(2)->format('M d, Y H:i') }}</p>
                                <p class="mb-0">Incident has been resolved and case closed</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Print Report</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateIncidentModal{{ $index }}">
                    <i class="fas fa-edit me-1"></i> Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Incident Status Modal -->
<div class="modal fade" id="updateIncidentModal{{ $index }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Update Incident Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option value="open" {{ $incident['status'] === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="investigating" {{ $incident['status'] === 'investigating' ? 'selected' : '' }}>Investigating</option>
                            <option value="resolved" {{ $incident['status'] === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assigned To</label>
                        <select class="form-select">
                            <option>Security Team</option>
                            <option>IT Security</option>
                            <option>Management</option>
                            <option>External Agency</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Update Notes</label>
                        <textarea class="form-control" rows="3" placeholder="Add update details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
