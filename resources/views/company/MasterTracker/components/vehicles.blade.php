<!-- Vehicles Tab Content -->
<div class="tab-pane fade" id="vehicles" role="tabpanel" aria-labelledby="vehicles-tab">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Vehicles Management</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                <i class="fas fa-plus me-1"></i> Add Vehicle
            </button>
            <button class="btn btn-secondary btn-sm" onclick="alert('Bulk upload feature coming soon!')">
                <i class="fas fa-upload me-1"></i> Bulk Upload
            </button>
            <button class="btn btn-success btn-sm" onclick="exportData('vehicles')">
                <i class="fas fa-file-export me-1"></i> Export
            </button>
            <a href="{{ route('vehicles.template.download') }}" class="btn btn-info btn-sm">
                <i class="fas fa-download me-1"></i> Template
            </a>
        </div>
    </div>

    <!-- Search and Filter for Vehicles -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" id="searchVehicles" class="form-control" placeholder="Search vehicles..." style="height: 38px;">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="filterVehicleStatus">
                <option value="">All Status</option>
                <option value="available">Available</option>
                <option value="in-use">In Use</option>
                <option value="maintenance">Maintenance</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="filterVehicleType">
                <option value="">All Types</option>
                <option value="sedan">Sedan</option>
                <option value="suv">SUV</option>
                <option value="truck">Truck</option>
                <option value="van">Van</option>
                <option value="motorcycle">Motorcycle</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-primary w-100" onclick="applyVehicleFilters()">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-secondary w-100" onclick="clearVehicleFilters()">
                <i class="fas fa-times me-1"></i> Clear
            </button>
        </div>
    </div>

    <!-- Vehicles Table -->
    <div class="table-responsive">
        <table class="table table-centered table-hover dt-responsive nowrap w-100" id="vehicles-datatable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Registration</th>
                    <th>Make & Model</th>
                    <th>Type</th>
                    <th>Year</th>
                    <th>Assigned Driver</th>
                    <th>Insurance Expiry</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($vehicles) && $vehicles->count() > 0)
                    @foreach($vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $vehicle->registration_number }}</h6>
                                        @if($vehicle->make_model)
                                            <small class="text-muted">{{ $vehicle->make_model }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $vehicle->make_model }}</td>
                            <td>{{ $vehicle->year }}</td>
                            <td>
                                <span class="badge bg-info">{{ $vehicle->type_formatted }}</span>
                            </td>
                            <td>
                                @if($vehicle->assignedDriver)
                                    <span class="badge bg-primary">{{ $vehicle->assignedDriver->full_name }}</span>
                                @else
                                    <span class="badge bg-secondary">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                @if($vehicle->insurance_expiry)
                                    @if($vehicle->isInsuranceExpired())
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif($vehicle->isInsuranceExpiringSoon())
                                        <span class="badge bg-warning">Expiring Soon</span>
                                    @else
                                        <span class="badge bg-success">{{ $vehicle->insurance_expiry->format('M d, Y') }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($vehicle->status === 'available')
                                    <span class="badge bg-success">{{ $vehicle->status_formatted }}</span>
                                @elseif($vehicle->status === 'in-use')
                                    <span class="badge bg-primary">{{ $vehicle->status_formatted }}</span>
                                @elseif($vehicle->status === 'maintenance')
                                    <span class="badge bg-warning">{{ $vehicle->status_formatted }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $vehicle->status_formatted }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-info action-btn" onclick="viewVehicle({{ $vehicle->id }})" title="View" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary action-btn" onclick="editVehicle({{ $vehicle->id }})" title="Edit" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger action-btn" onclick="deleteVehicle({{ $vehicle->id }})" title="Delete" data-bs-toggle="tooltip">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No vehicles found</h5>
                                <p class="text-muted mb-3">Get started by adding your first vehicle to the fleet</p>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                                    <i class="fas fa-plus me-1"></i> Add Vehicle
                                </button>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
