<!-- Drivers Tab Content -->
<div class="tab-pane fade" id="drivers" role="tabpanel" aria-labelledby="drivers-tab">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Drivers Management</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDriverModal">
                <i class="fas fa-plus me-1"></i> Add Driver
            </button>
            <button class="btn btn-secondary btn-sm" onclick="alert('Bulk upload feature coming soon!')">
                <i class="fas fa-upload me-1"></i> Bulk Upload
            </button>
            <button class="btn btn-success btn-sm" onclick="exportData('drivers')">
                <i class="fas fa-file-export me-1"></i> Export
            </button>
        </div>
    </div>

    <!-- Search and Filter for Drivers -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" id="searchDrivers" class="form-control" placeholder="Search drivers..." style="height: 38px;">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="filterDriverStatus">
                <option value="">All Status</option>
                <option value="available">Available</option>
                <option value="assigned">Assigned</option>
                <option value="on-leave">On Leave</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="filterLicenseType">
                <option value="">All License Types</option>
                <option value="class-a">Class A</option>
                <option value="class-b">Class B</option>
                <option value="class-c">Class C</option>
                <option value="motorcycle">Motorcycle</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-primary w-100" onclick="applyDriverFilters()">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-secondary w-100" onclick="clearDriverFilters()">
                <i class="fas fa-times me-1"></i> Clear
            </button>
        </div>
    </div>

    <!-- Drivers Table -->
    <div class="table-responsive">
        <table class="table table-centered table-hover dt-responsive nowrap w-100" id="drivers-datatable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>License Number</th>
                    <th>License Type</th>
                    <th>Phone</th>
                    <th>Experience</th>
                    <th>License Expiry</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($drivers) && $drivers->count() > 0)
                    @foreach($drivers as $driver)
                        <tr>
                            <td>{{ $driver->id }}</td>
                            <td>
                                <h6 class="mb-0">{{ $driver->full_name }}</h6>
                            </td>
                            <td>{{ $driver->license_number }}</td>
                            <td>
                                <span class="badge bg-info">{{ $driver->license_type_formatted }}</span>
                            </td>
                            <td>{{ $driver->phone }}</td>
                            <td>{{ $driver->experience_years ?? 'N/A' }} years</td>
                            <td>
                                @if($driver->license_expiry)
                                    {{ $driver->license_expiry->format('Y-m-d') }}
                                    @if($driver->isLicenseExpired())
                                        <span class="badge bg-danger ms-1">Expired</span>
                                    @elseif($driver->isLicenseExpiringSoon())
                                        <span class="badge bg-warning ms-1">Expiring Soon</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match($driver->status) {
                                        'available' => 'bg-success',
                                        'assigned' => 'bg-primary',
                                        'on-leave' => 'bg-warning',
                                        'inactive' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $driver->status_formatted }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <button type="button" class="btn btn-sm btn-info action-btn" onclick="viewDriver({{ $driver->id }})" title="View Driver">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning action-btn" onclick="editDriver({{ $driver->id }})" title="Edit Driver">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger action-btn" onclick="deleteDriver({{ $driver->id }})" title="Delete Driver">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-id-card fa-3x mb-3"></i>
                                <h5>No drivers found</h5>
                                <p>Start by adding your first driver using the "Add Driver" button above.</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
