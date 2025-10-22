@php
    $canManageCustomers = \Illuminate\Support\Facades\Auth::guard('company_sub_user')->check()
        || \Illuminate\Support\Facades\Auth::guard('sub_user')->check()
        || \Illuminate\Support\Facades\Auth::check();
@endphp
<div class="card" id="customers-root" data-can-manage="{{ $canManageCustomers ? '1' : '0' }}" data-flash-success="{{ addslashes(session('success') ?? '') }}" data-flash-error="{{ addslashes(session('error') ?? '') }}">
    <div class="card-body">
        <!-- Success/Error Messages are now handled by SweetAlert2 -->

        @php
            $canManageCustomers = \Illuminate\Support\Facades\Auth::guard('company_sub_user')->check()
                || \Illuminate\Support\Facades\Auth::guard('sub_user')->check()
                || \Illuminate\Support\Facades\Auth::check();
        @endphp

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Company Selection and Action Buttons HSW021125PGL00 -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <label class="me-2 mb-0">Select Company:</label>
                    <select id="companySelect" class="form-select" style="width: 200px;">
                        <option value="">All Companies</option>
                        <option value="GESL" {{ (isset($businessUnit) && $businessUnit == 'GESL') ? 'selected' : '' }}>GESL</option>
                        <option value="LINFRA" {{ (isset($businessUnit) && $businessUnit == 'LINFRA') ? 'selected' : '' }}>LINFRA</option>
                    </select>
                </div>
                @if($canManageCustomers)
                    <div class="d-flex align-items-center">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="autoScheduleToggle" style="cursor: pointer;">
                            <label class="form-check-label mb-0" for="autoScheduleToggle" style="cursor: pointer;">
                                <i class="ri-calendar-check-line me-1"></i> Auto Schedule
                            </label>
                        </div>
                    </div>
                @endif
            </div>
            <div>
                @if($canManageCustomers)
                    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                        <i class="ri-user-add-line me-1"></i> Add Customer
                    </button>
                    <button class="btn btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                        <i class="ri-upload-2-line me-1"></i> Bulk Upload
                    </button>
                @endif
                <button class="btn btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#exportCustomersModal">
                    <i class="ri-download-2-line me-1"></i> Export
                </button>
                <button class="btn btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#customerFilters" aria-expanded="false">
                    <i class="ri-filter-3-line me-1"></i> Filters
                </button>
            </div>
        </div>

        <!-- Bulk Actions Bar (Hidden by default, shown when customers are selected) -->
        @if($canManageCustomers)
            <div id="bulkActionsBar" class="alert alert-info d-none mb-3" role="alert">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="ri-checkbox-multiple-line me-2"></i>
                        <strong><span id="selectedCount">0</span> customer(s) selected</strong>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-primary me-2" id="bulkScheduleBtn">
                            <i class="ri-calendar-event-line me-1"></i> Bulk Schedule Appointment
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="clearSelectionBtn">
                            <i class="ri-close-line me-1"></i> Clear Selection
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="collapse mb-4" id="customerFilters">
            <div class="card card-body bg-light">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small">Date From</label>
                        <input type="date" class="form-control form-control-sm filter-input" id="filter_date_from" 
                               value="{{ $assignmentFilters['date_from'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Date To</label>
                        <input type="date" class="form-control form-control-sm filter-input" id="filter_date_to" 
                               value="{{ $assignmentFilters['date_to'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Team</label>
                        <select class="form-select form-select-sm filter-input" id="filter_team">
                            <option value="">All Teams</option>
                            @if(isset($teams) && count($teams) > 0)
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ (isset($assignmentFilters['team_id']) && $assignmentFilters['team_id'] == $team->id) ? 'selected' : '' }}>
                                        {{ $team->team_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Location</label>
                        <select class="form-select form-select-sm filter-input" id="filter_location">
                            <option value="">All Locations</option>
                            
                            <!-- Greater Accra Region -->
                            <optgroup label="Greater Accra Region">
                                <option value="Accra" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Accra') ? 'selected' : '' }}>Accra</option>
                                <option value="Tema" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Tema') ? 'selected' : '' }}>Tema</option>
                                <option value="Madina" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Madina') ? 'selected' : '' }}>Madina</option>
                                <option value="Adenta" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Adenta') ? 'selected' : '' }}>Adenta</option>
                                <option value="Kasoa" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Kasoa') ? 'selected' : '' }}>Kasoa</option>
                                <option value="Dodowa" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Dodowa') ? 'selected' : '' }}>Dodowa</option>
                            </optgroup>

                            <!-- Ashanti Region -->
                            <optgroup label="Ashanti Region">
                                <option value="Kumasi" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Kumasi') ? 'selected' : '' }}>Kumasi</option>
                                <option value="Obuasi" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Obuasi') ? 'selected' : '' }}>Obuasi</option>
                                <option value="Ejisu" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Ejisu') ? 'selected' : '' }}>Ejisu</option>
                                <option value="Konongo" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Konongo') ? 'selected' : '' }}>Konongo</option>
                                <option value="Mampong" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Mampong') ? 'selected' : '' }}>Mampong</option>
                            </optgroup>

                            <!-- Western Region -->
                            <optgroup label="Western Region">
                                <option value="Takoradi" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Takoradi') ? 'selected' : '' }}>Takoradi</option>
                                <option value="Sekondi" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Sekondi') ? 'selected' : '' }}>Sekondi</option>
                                <option value="Tarkwa" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Tarkwa') ? 'selected' : '' }}>Tarkwa</option>
                                <option value="Prestea" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Prestea') ? 'selected' : '' }}>Prestea</option>
                            </optgroup>

                            <!-- Northern Region -->
                            <optgroup label="Northern Region">
                                <option value="Tamale" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Tamale') ? 'selected' : '' }}>Tamale</option>
                                <option value="Yendi" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Yendi') ? 'selected' : '' }}>Yendi</option>
                                <option value="Savelugu" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Savelugu') ? 'selected' : '' }}>Savelugu</option>
                            </optgroup>

                            <!-- Central Region -->
                            <optgroup label="Central Region">
                                <option value="Cape Coast" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Cape Coast') ? 'selected' : '' }}>Cape Coast</option>
                                <option value="Elmina" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Elmina') ? 'selected' : '' }}>Elmina</option>
                                <option value="Winneba" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Winneba') ? 'selected' : '' }}>Winneba</option>
                                <option value="Agona Swedru" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Agona Swedru') ? 'selected' : '' }}>Agona Swedru</option>
                            </optgroup>

                            <!-- Eastern Region -->
                            <optgroup label="Eastern Region">
                                <option value="Koforidua" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Koforidua') ? 'selected' : '' }}>Koforidua</option>
                                <option value="Nkawkaw" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Nkawkaw') ? 'selected' : '' }}>Nkawkaw</option>
                                <option value="Akim Oda" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Akim Oda') ? 'selected' : '' }}>Akim Oda</option>
                                <option value="Nsawam" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Nsawam') ? 'selected' : '' }}>Nsawam</option>
                            </optgroup>

                            <!-- Volta Region -->
                            <optgroup label="Volta Region">
                                <option value="Ho" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Ho') ? 'selected' : '' }}>Ho</option>
                                <option value="Keta" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Keta') ? 'selected' : '' }}>Keta</option>
                                <option value="Kpando" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Kpando') ? 'selected' : '' }}>Kpando</option>
                            </optgroup>

                            <!-- Upper East Region -->
                            <optgroup label="Upper East Region">
                                <option value="Bolgatanga" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Bolgatanga') ? 'selected' : '' }}>Bolgatanga</option>
                                <option value="Navrongo" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Navrongo') ? 'selected' : '' }}>Navrongo</option>
                            </optgroup>

                            <!-- Upper West Region -->
                            <optgroup label="Upper West Region">
                                <option value="Wa" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Wa') ? 'selected' : '' }}>Wa</option>
                                <option value="Lawra" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Lawra') ? 'selected' : '' }}>Lawra</option>
                            </optgroup>

                            <!-- Bono Region -->
                            <optgroup label="Bono Region">
                                <option value="Sunyani" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Sunyani') ? 'selected' : '' }}>Sunyani</option>
                                <option value="Berekum" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Berekum') ? 'selected' : '' }}>Berekum</option>
                            </optgroup>

                            <!-- Bono East Region -->
                            <optgroup label="Bono East Region">
                                <option value="Techiman" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Techiman') ? 'selected' : '' }}>Techiman</option>
                                <option value="Kintampo" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Kintampo') ? 'selected' : '' }}>Kintampo</option>
                            </optgroup>

                            <!-- Ahafo Region -->
                            <optgroup label="Ahafo Region">
                                <option value="Goaso" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Goaso') ? 'selected' : '' }}>Goaso</option>
                            </optgroup>

                            <!-- Western North Region -->
                            <optgroup label="Western North Region">
                                <option value="Sefwi Wiawso" {{ (isset($assignmentFilters['location']) && $assignmentFilters['location'] == 'Sefwi Wiawso') ? 'selected' : '' }}>Sefwi Wiawso</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Connection Type</label>
                        <select class="form-select form-select-sm filter-input" id="filter_connection_type">
                            <option value="">All Types</option>
                            <option value="Traditional" {{ (isset($assignmentFilters['connection_type']) && $assignmentFilters['connection_type'] == 'Traditional') ? 'selected' : '' }}>
                                Traditional
                            </option>
                            <option value="Quick ODN" {{ (isset($assignmentFilters['connection_type']) && $assignmentFilters['connection_type'] == 'Quick ODN') ? 'selected' : '' }}>
                                Quick ODN
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Issue Status</label>
                        <select class="form-select form-select-sm filter-input" id="filter_issue">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ (isset($assignmentFilters['issue']) && $assignmentFilters['issue'] == 'pending') ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="in_progress" {{ (isset($assignmentFilters['issue']) && $assignmentFilters['issue'] == 'in_progress') ? 'selected' : '' }}>
                                In Progress
                            </option>
                            <option value="completed" {{ (isset($assignmentFilters['issue']) && $assignmentFilters['issue'] == 'completed') ? 'selected' : '' }}>
                                Completed
                            </option>
                            <option value="cancelled" {{ (isset($assignmentFilters['issue']) && $assignmentFilters['issue'] == 'cancelled') ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearFiltersBtn">
                            <i class="ri-refresh-line me-1"></i> Clear Filters
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm ms-2" id="showAllBtn">
                            <i class="ri-eye-line me-1"></i> Show All
                        </button>
                        <div class="ms-3">
                            <small class="text-muted">
                                <i class="ri-information-line me-1"></i>
                                Filters apply automatically as you type/select
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Table -->
        <div class="table-responsive">
            <table id="customersTable" class="table table-hover table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        @if($canManageCustomers)
                            <th style="width: 50px;">
                                <input type="checkbox" class="form-check-input" id="selectAllCustomers" title="Select All">
                            </th>
                        @endif
                        <th style="width: 110px;">MSISDN</th>
                        <th style="width: 150px;">Customer Name</th>
                        <th style="width: 140px;">Contacts</th>
                        <th style="width: 130px;">Connection Type</th>
                        <th style="width: 120px;">Location</th>
                        <th style="width: 130px;">GPS Address</th>
                        <th style="width: 140px;">GPS Coordinates</th>
                        <th style="width: 100px;">Status</th>
                        <th class="text-end" style="width: 200px; min-width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="customerTableBody">
                    @if(isset($customers) && count($customers) > 0)
                        @foreach($customers as $customer)
                            <tr>
                                @if($canManageCustomers)
                                    <td>
                                        <input type="checkbox" class="form-check-input customer-checkbox" 
                                               data-customer-id="{{ $customer->id }}"
                                               data-customer-name="{{ $customer->customer_name }}">
                                    </td>
                                @endif
                                <td>{{ $customer->msisdn }}</td>
                                <td>{{ $customer->customer_name }}</td>
                                <td>
                                    @if($customer->email)
                                        {{ $customer->email }}<br>
                                    @endif
                                    {{ $customer->contact_number }}
                                </td>
                                <td>{{ $customer->connection_type }}</td>
                                <td>{{ $customer->location }}</td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px;" title="{{ $customer->gps_address ?? 'N/A' }}">{{ $customer->gps_address ?? 'N/A' }}</td>
                                <td>{{ $customer->gps_coordinates_formatted ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $customer->status_badge_class }}">
                                        {{ $customer->status }}
                                    </span>
                                </td>
                                <td class="text-end" style="white-space: nowrap;">
                                    <button class="btn btn-sm btn-outline-primary me-1 view-customer-btn" 
                                            title="View"
                                            data-customer-id="{{ $customer->id }}"
                                            data-customer-name="{{ $customer->customer_name }}"
                                            data-customer-msisdn="{{ $customer->msisdn }}"
                                            data-customer-email="{{ $customer->email }}"
                                            data-customer-phone="{{ $customer->contact_number }}"
                                            data-customer-connection-type="{{ $customer->connection_type }}"
                                            data-customer-location="{{ $customer->location }}"
                                            data-customer-gps-address="{{ $customer->gps_address }}"
                                            data-customer-latitude="{{ $customer->latitude }}"
                                            data-customer-longitude="{{ $customer->longitude }}"
                                            data-customer-status="{{ $customer->status }}">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success me-1 contact-appointment-btn" 
                                            title="Contact & Appointment Info"
                                            data-customer-id="{{ $customer->id }}"
                                            data-customer-name="{{ $customer->customer_name }}"
                                            data-customer-msisdn="{{ $customer->msisdn }}"
                                            data-customer-phone="{{ $customer->contact_number }}"
                                            data-customer-email="{{ $customer->email }}"
                                            data-customer-location="{{ $customer->location }}">
                                        <i class="ri-phone-line"></i>
                                    </button>
                                    @if($canManageCustomers)
                                        <button class="btn btn-sm btn-outline-secondary me-1 edit-customer-btn" 
                                                title="Edit"
                                                data-customer-id="{{ $customer->id }}"
                                                data-customer-name="{{ $customer->customer_name }}"
                                                data-customer-msisdn="{{ $customer->msisdn }}"
                                                data-customer-email="{{ $customer->email }}"
                                                data-customer-phone="{{ $customer->contact_number }}"
                                                data-customer-connection-type="{{ $customer->connection_type }}"
                                                data-customer-location="{{ $customer->location }}"
                                                data-customer-gps-address="{{ $customer->gps_address }}"
                                                data-customer-latitude="{{ $customer->latitude }}"
                                                data-customer-longitude="{{ $customer->longitude }}"
                                                data-customer-status="{{ $customer->status }}">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info me-1 schedule-appointment-btn" 
                                                title="Schedule Appointment"
                                                data-customer-id="{{ $customer->id }}">
                                            <i class="ri-calendar-event-line"></i>
                                        </button>
                                        <form action="{{ route('project-management.customers.destroy', $customer->id) }}" 
                                              method="POST" 
                                              class="d-inline delete-customer-form"
                                              data-customer-name="{{ $customer->customer_name }}"
                                              data-customer-id="{{ $customer->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
                                                    data-customer-name="{{ $customer->customer_name }}"
                                                    data-customer-id="{{ $customer->id }}">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ $canManageCustomers ? '10' : '9' }}" class="text-center">No customers found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(isset($customers) && $customers->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers
                </div>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
        @endif

    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCustomerForm" method="POST" action="{{ route('project-management.customers.store') }}">
                @csrf
                <input type="hidden" id="autoScheduleEnabledField" name="auto_schedule_enabled" value="">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Business Unit <span class="text-danger">*</span></label>
                            <select class="form-select @error('business_unit') is-invalid @enderror" name="business_unit" required>
                                <option value="">Select Business Unit</option>
                                <option value="GESL" {{ old('business_unit') == 'GESL' ? 'selected' : '' }}>GESL</option>
                                <option value="LINFRA" {{ old('business_unit') == 'LINFRA' ? 'selected' : '' }}>LINFRA</option>
                            </select>
                            @error('business_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">MSISDN <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+233</span>
                                <input type="text" class="form-control @error('msisdn') is-invalid @enderror" name="msisdn" placeholder="541234567" pattern="[0-9]{9}" title="Please enter a valid 9-digit number" value="{{ old('msisdn') }}" required>
                            </div>
                            @error('msisdn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror" name="customer_name" value="{{ old('customer_name') }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+233</span>
                                <input type="text" class="form-control @error('contact_number') is-invalid @enderror" name="contact_number" placeholder="541234567" pattern="[0-9]{9}" title="Please enter a valid 9-digit number" value="{{ old('contact_number') }}" required>
                            </div>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Connection Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('connection_type') is-invalid @enderror" name="connection_type" required>
                                <option value="">Select Type</option>
                                <option value="Traditional" {{ old('connection_type') == 'Traditional' ? 'selected' : '' }}>Traditional</option>
                                <option value="Quick ODN" {{ old('connection_type') == 'Quick ODN' ? 'selected' : '' }}>Quick ODN</option>
                            </select>
                            @error('connection_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" name="location" placeholder="Area - City (e.g., Adenta - Accra)" value="{{ old('location') }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">GPS Address</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control @error('gps_address') is-invalid @enderror" name="gps_address" id="gpsAddress" value="{{ old('gps_address') }}">
                                <button class="btn btn-outline-secondary" type="button" id="getLocationBtn" title="Find coordinates from the typed address/location">
                                    <i class="ri-map-pin-line"></i> Find From Address
                                </button>
                            </div>
                            @error('gps_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Type a GPS Address (e.g., house address or GhanaPost GPS/GPR) or a place in Location, then click “Find From Address”. You can also enter coordinates manually below.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">GPS Coordinates</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Lat</span>
                                <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" placeholder="5.6037" value="{{ old('latitude') }}">
                                <span class="input-group-text">Long</span>
                                <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" placeholder="-0.1870" value="{{ old('longitude') }}">
                            </div>
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Example: 5.6037, -0.1870 (Accra)</small>
                        </div>
                        <!-- Hidden field: Status defaults to Pending -->
                        <input type="hidden" name="status" value="Pending">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Upload Customers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkUploadForm" method="POST" action="{{ route('project-management.customers.bulk-upload') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="bulkAutoScheduleEnabledField" name="auto_schedule_enabled" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Company</label>
                        <select class="form-select" name="business_unit" required>
                            <option value="GESL">GESL</option>
                            <option value="LINFRA">LINFRA</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload CSV File</label>
                        <input type="file" class="form-control" name="bulk_upload_file" accept=".csv" required>
                        <div class="form-text">Download the template file for the correct format</div>
                    </div>
                    <div class="alert alert-info">
                        <i class="ri-information-line me-1"></i> Ensure your CSV file follows the required format.
                        <a href="{{ route('project-management.customers.download-template') }}" class="alert-link">Download Template</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload & Process</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Customer Modal -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Customer Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Customer Name:</th>
                                <td id="viewCustomerName"></td>
                            </tr>
                            <tr>
                                <th>MSISDN:</th>
                                <td id="viewMsisdn"></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td id="viewEmail"></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td id="viewPhone"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Location & Status</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Connection Type:</th>
                                <td id="viewConnectionType"></td>
                            </tr>
                            <tr>
                                <th>Location:</th>
                                <td id="viewLocation"></td>
                            </tr>
                            <tr>
                                <th>GPS Address:</th>
                                <td id="viewGpsAddress"></td>
                            </tr>
                            <tr>
                                <th>GPS Coordinates:</th>
                                <td id="viewGpsCoordinates"></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td id="viewStatus"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCustomerForm" method="POST" action="{{ route('project-management.customers.update', ':id') }}" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editCustomerId" name="customer_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">MSISDN <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+233</span>
                                <input type="text" class="form-control" id="editMsisdn" name="msisdn" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editCustomerName" name="customer_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+233</span>
                                <input type="text" class="form-control" id="editPhone" name="contact_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Connection Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="editConnectionType" name="connection_type" required>
                                <option value="Traditional">Traditional</option>
                                <option value="Quick ODN">Quick ODN</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editLocation" name="location" placeholder="Area - City (e.g., Adenta - Accra)" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">GPS Address</label>
                            <input type="text" class="form-control" id="editGpsAddress" name="gps_address">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">GPS Coordinates</label>
                            <div class="input-group">
                                <span class="input-group-text">Lat</span>
                                <input type="text" class="form-control" id="editLatitude" name="latitude" placeholder="Optional">
                                <span class="input-group-text">Long</span>
                                <input type="text" class="form-control" id="editLongitude" name="longitude" placeholder="Optional">
                            </div>
                            <small class="text-muted">Leave blank if GPS coordinates are not available</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Pending">Pending</option>
                                <option value="Schedule">Scheduled for appointment</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Appointment Modal -->
<div class="modal fade" id="scheduleAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Schedule Appointment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('site-assignments.store') }}" method="POST" id="scheduleAppointmentForm">
                @csrf
                <input type="hidden" id="appointmentCustomerId" name="customer_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="appointmentCustomerName" class="form-label">Customer</label>
                        <input type="text" class="form-control" id="appointmentCustomerName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="appointmentDate" class="form-label">Appointment Date</label>
                        <input type="datetime-local" class="form-control" id="appointmentDate" name="assigned_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="assignedEngineer" class="form-label">Assign Team</label>
                        <select class="form-select" id="assignedEngineer" name="team_id" required>
                            <option value="">Select a team</option>
                            @if(isset($teams) && $teams->count() > 0)
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>No teams available</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="appointmentPurpose" class="form-label">Assignment Notes</label>
                        <textarea class="form-control" id="appointmentPurpose" name="description" rows="3" placeholder="Add any special instructions or notes"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="prioritySelect" class="form-label">Priority</label>
                        <select class="form-select" id="prioritySelect" name="priority" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calendar-check me-1"></i> Schedule Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Customers Modal -->
<div class="modal fade" id="exportCustomersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="ri-download-2-line me-2"></i>Export Data
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-3" id="exportTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="export-customers-tab" data-bs-toggle="tab" data-bs-target="#export-customers" type="button" role="tab">
                            <i class="ri-user-line me-1"></i>Export Customers
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="export-assignments-tab" data-bs-toggle="tab" data-bs-target="#export-assignments" type="button" role="tab">
                            <i class="ri-task-line me-1"></i>Export Assignments
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="exportTabsContent">
                    <!-- Export Customers Tab -->
                    <div class="tab-pane fade show active" id="export-customers" role="tabpanel">
                        <form id="exportCustomersForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="exportBusinessUnit" class="form-label">Select Company</label>
                                <select class="form-select" id="exportBusinessUnit" name="business_unit">
                                    <option value="">All Companies</option>
                                    <option value="GESL">GESL</option>
                                    <option value="LINFRA">LINFRA</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exportFormat" class="form-label">Select Export Format</label>
                                <select class="form-select" id="exportFormat" name="format">
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-success" id="confirmExportCustomers">
                                <i class="ri-download-line me-1"></i>Export Customers
                            </button>
                        </form>
                    </div>

                    <!-- Export Assignments Tab -->
                    <div class="tab-pane fade" id="export-assignments" role="tabpanel">
                        <form id="exportAssignmentsForm" method="POST" action="{{ route('project-management.customers.export-assignments') }}">
                            @csrf
                            <input type="hidden" name="filter_date_from" value="{{ $assignmentFilters['date_from'] ?? '' }}">
                            <input type="hidden" name="filter_date_to" value="{{ $assignmentFilters['date_to'] ?? '' }}">
                            <input type="hidden" name="filter_team" value="{{ $assignmentFilters['team_id'] ?? '' }}">
                            <input type="hidden" name="filter_location" value="{{ $assignmentFilters['location'] ?? '' }}">
                            <input type="hidden" name="filter_connection_type" value="{{ $assignmentFilters['connection_type'] ?? '' }}">
                            <input type="hidden" name="filter_issue" value="{{ $assignmentFilters['issue'] ?? '' }}">
                            
                            <div class="alert alert-info">
                                <i class="ri-information-line me-1"></i>
                                This will export all assignments with the currently applied filters.
                            </div>

                            @if(isset($assignmentFilters) && array_filter($assignmentFilters))
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Active Filters:</label>
                                    <ul class="small mb-0">
                                        @if(!empty($assignmentFilters['date_from']))
                                            <li>Date From: {{ $assignmentFilters['date_from'] }}</li>
                                        @endif
                                        @if(!empty($assignmentFilters['date_to']))
                                            <li>Date To: {{ $assignmentFilters['date_to'] }}</li>
                                        @endif
                                        @if(!empty($assignmentFilters['team_id']))
                                            <li>Team: {{ $teams->where('id', $assignmentFilters['team_id'])->first()->team_name ?? 'N/A' }}</li>
                                        @endif
                                        @if(!empty($assignmentFilters['location']))
                                            <li>Location: {{ $assignmentFilters['location'] }}</li>
                                        @endif
                                        @if(!empty($assignmentFilters['connection_type']))
                                            <li>Connection Type: {{ $assignmentFilters['connection_type'] }}</li>
                                        @endif
                                        @if(!empty($assignmentFilters['issue']))
                                            <li>Issue: {{ $assignmentFilterOptions['issue_options'][$assignmentFilters['issue']] ?? $assignmentFilters['issue'] }}</li>
                                        @endif
                                    </ul>
                                </div>
                            @else
                                <p class="text-muted small">No filters applied. All assignments will be exported.</p>
                            @endif

                            <div class="mb-3">
                                <label for="exportAssignmentFormat" class="form-label">Select Export Format</label>
                                <select class="form-select" id="exportAssignmentFormat" name="export_format" required>
                                    <option value="excel">Excel (XLSX)</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-success">
                                <i class="ri-download-line me-1"></i>Export Assignments
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact & Appointment Info Modal -->
<div class="modal fade" id="contactAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="ri-phone-line me-2"></i>Contact & Appointment Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Customer Information -->
                    <div class="col-md-6">
                        <h6><i class="ri-user-line me-2"></i>Customer Details</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Name:</th>
                                <td id="contactCustomerName">-</td>
                            </tr>
                            <tr>
                                <th>Location:</th>
                                <td id="contactCustomerLocation">-</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td id="contactCustomerEmail">-</td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Contact Numbers -->
                    <div class="col-md-6">
                        <h6><i class="ri-phone-line me-2"></i>Contact Numbers</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">MSISDN:</th>
                                <td>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span id="contactCustomerMsisdn">-</span>
                                        <button type="button" class="btn btn-sm btn-success" id="dialMsisdnBtn" title="Call MSISDN">
                                            <i class="ri-phone-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Contact Number:</th>
                                <td>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span id="contactCustomerPhone">-</span>
                                        <button type="button" class="btn btn-sm btn-success" id="dialPhoneBtn" title="Call Contact Number">
                                            <i class="ri-phone-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Appointment Information -->
                <div class="row mt-3">
                    <div class="col-12">
                        <h6><i class="ri-calendar-line me-2"></i>Appointment Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Status:</th>
                                        <td id="appointmentStatus">No appointment scheduled</td>
                                    </tr>
                                    <tr>
                                        <th>Assigned Team:</th>
                                        <td id="assignedTeam">-</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Appointment Date:</th>
                                        <td id="appointmentDateDisplay">-</td>
                                    </tr>
                                    <tr>
                                        <th>Appointment Time:</th>
                                        <td id="appointmentTimeDisplay">-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="mt-3 pt-3 border-top">
                            <h6 class="mb-3">Quick Actions</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-outline-primary" id="scheduleAppointmentBtn">
                                    <i class="ri-calendar-event-line me-1"></i> Schedule Appointment
                                </button>
                                <button type="button" class="btn btn-outline-info" id="sendSmsBtn">
                                    <i class="ri-message-line me-1"></i> Send SMS
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="viewLocationBtn">
                                    <i class="ri-map-pin-line me-1"></i> View Location
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Schedule Appointment Modal -->
<div class="modal fade" id="bulkScheduleAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ri-calendar-event-line me-2"></i>Bulk Schedule Appointment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('site-assignments.bulk-store') }}" method="POST" id="bulkScheduleAppointmentForm">
                @csrf
                <input type="hidden" id="bulkCustomerIds" name="customer_ids">
                <div class="modal-body">
                    <!-- Selected Customers Summary -->
                    <div class="alert alert-info mb-3">
                        <h6 class="alert-heading">
                            <i class="ri-information-line me-2"></i>Selected Customers
                        </h6>
                        <div id="selectedCustomersList" class="mt-2">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="bulkAppointmentDate" class="form-label">Appointment Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="bulkAppointmentDate" name="assigned_date" required>
                            <small class="text-muted">All selected customers will have the same appointment date/time</small>
                        </div>
                        <div class="col-md-6">
                            <label for="bulkAssignedTeam" class="form-label">Assign Team <span class="text-danger">*</span></label>
                            <select class="form-select" id="bulkAssignedTeam" name="team_id" required>
                                <option value="">Select a team</option>
                                @if(isset($teams) && $teams->count() > 0)
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No teams available</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="bulkPrioritySelect" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" id="bulkPrioritySelect" name="priority" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="bulkAppointmentNotes" class="form-label">Assignment Notes</label>
                            <textarea class="form-control" id="bulkAppointmentNotes" name="description" rows="3" placeholder="Add any special instructions or notes (same for all customers)"></textarea>
                            <small class="text-muted">These notes will be applied to all appointments</small>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="mt-3">
                        <h6>Options</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sendNotifications" name="send_notifications" checked>
                            <label class="form-check-label" for="sendNotifications">
                                Send appointment notifications to customers
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-calendar-check-line me-1"></i> Schedule <span id="bulkScheduleCount"></span> Appointments
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // Permissions and flashes from DOM dataset (avoid Blade in JS)
    (function() {
        const decodeHtmlEntities = (input = '') => {
            if (!input) return '';
            const textarea = document.createElement('textarea');
            textarea.innerHTML = input;
            return textarea.value;
        };

        const rootEl = document.getElementById('customers-root');
        window.canManageCustomers = rootEl && rootEl.dataset && rootEl.dataset.canManage === '1';
        const rawSuccess = rootEl && rootEl.dataset ? rootEl.dataset.flashSuccess : '';
        const rawError = rootEl && rootEl.dataset ? rootEl.dataset.flashError : '';
        window.flashSuccess = decodeHtmlEntities(rawSuccess);
        window.flashError = decodeHtmlEntities(rawError);
        
        // Pass assignments data to JavaScript (handle both paginated and collection data)
        @if(isset($assignments))
            @if(method_exists($assignments, 'items'))
                // Paginated data
                window.assignmentsData = @json($assignments->items());
            @else
                // Collection data
                window.assignmentsData = @json($assignments);
            @endif
        @else
            window.assignmentsData = [];
        @endif
    })();

    // State management
    let currentPage = 1;
    const itemsPerPage = 10;

    // Setup toast container
    const setupToastContainer = () => {
        if (!document.getElementById('toastContainer')) {
            const toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '11';
            document.body.appendChild(toastContainer);
        }
    };

    // Show toast notification
    function showToast(title, message, type = 'info') {
        setupToastContainer();
        const toastContainer = document.getElementById('toastContainer');
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        
        toast.id = toastId;
        toast.className = `toast align-items-center text-white bg-${type} border-0 show mb-2`;
        toast.role = 'alert';
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong><br>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastContainer.insertBefore(toast, toastContainer.firstChild);
        
        // Auto-remove toast after 5 seconds
        setTimeout(() => {
            const toastElement = document.getElementById(toastId);
            if (toastElement) {
                toastElement.remove();
            }
        }, 5000);
    }

    // Show/hide loading state
    function showLoading(show) {
        const loadingElement = document.getElementById('loadingIndicator');
        if (!loadingElement) return;
        
        loadingElement.classList.toggle('d-none', !show);
    }

    // Update customer table with new data (DISABLED - Using Laravel pagination)
    function updateCustomerTable(customers, pagination) {
        console.log('updateCustomerTable is disabled - using Laravel pagination instead');
        return;
        const tbody = document.getElementById('customerTableBody');
        if (!tbody) return;

        // Clear existing rows
        tbody.innerHTML = '';

        if (customers && customers.length > 0) {
            customers.forEach(customer => {
                const row = document.createElement('tr');
                
                // Format GPS coordinates
                let gpsCoords = 'N/A';
                if (customer.gps_coordinates_formatted) {
                    gpsCoords = customer.gps_coordinates_formatted;
                } else if (customer.gps_coordinates) {
                    gpsCoords = customer.gps_coordinates.split(',').map(coord => parseFloat(coord).toFixed(6)).join(', ');
                } else if (customer.latitude && customer.longitude) {
                    gpsCoords = `${parseFloat(customer.latitude).toFixed(6)}, ${parseFloat(customer.longitude).toFixed(6)}`;
                }
                
                // Get status badge class
                const statusBadgeClass = getStatusBadgeClass(customer.status);
                
                // Build actions based on permissions
                let actionsHtml = `
                    <button class="btn btn-sm btn-outline-primary me-1 view-customer-btn" 
                            title="View"
                            data-customer-id="${customer.id}"
                            data-customer-name="${customer.customer_name}"
                            data-customer-msisdn="${customer.msisdn}"
                            data-customer-email="${customer.email}"
                            data-customer-phone="${customer.contact_number}"
                            data-customer-connection-type="${customer.connection_type}"
                            data-customer-location="${customer.location}"
                            data-customer-gps-address="${customer.gps_address}"
                            data-customer-latitude="${customer.latitude}"
                            data-customer-longitude="${customer.longitude}"
                            data-customer-status="${customer.status}">
                        <i class="ri-eye-line"></i>
                    </button>`;

                if (window.canManageCustomers) {
                    actionsHtml += `
                        <button class="btn btn-sm btn-outline-secondary me-1 edit-customer-btn" 
                                title="Edit"
                                data-customer-id="${customer.id}"
                                data-customer-name="${customer.customer_name}"
                                data-customer-msisdn="${customer.msisdn}"
                                data-customer-email="${customer.email}"
                                data-customer-phone="${customer.contact_number}"
                                data-customer-connection-type="${customer.connection_type}"
                                data-customer-location="${customer.location}"
                                data-customer-gps-address="${customer.gps_address}"
                                data-customer-latitude="${customer.latitude}"
                                data-customer-longitude="${customer.longitude}"
                                data-customer-status="${customer.status}">
                            <i class="ri-edit-line"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info me-1 schedule-appointment-btn" 
                                title="Schedule Appointment"
                                data-customer-id="${customer.id}">
                            <i class="ri-calendar-event-line"></i>
                        </button>
                        <form action="{{ route('project-management.customers.destroy', '') }}/${customer.id}" 
                              method="POST" 
                              class="d-inline delete-customer-form"
                              data-customer-name="${customer.customer_name}"
                              data-customer-id="${customer.id}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" 
                                    class="btn btn-sm btn-outline-danger"
                                    title="Delete"
                                    data-customer-name="${customer.customer_name}"
                                    data-customer-id="${customer.id}">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>`;
                }

                row.innerHTML = `
                    <td>${customer.msisdn || 'N/A'}</td>
                    <td>${customer.customer_name || 'N/A'}</td>
                    <td>
                        ${customer.email ? customer.email + '<br>' : ''}
                        ${customer.contact_number || 'N/A'}
                    </td>
                    <td>${customer.connection_type || 'N/A'}</td>
                    <td>${customer.location || 'N/A'}</td>
                    <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px;" title="${customer.gps_address || 'N/A'}">${customer.gps_address || 'N/A'}</td>
                    <td>${gpsCoords}</td>
                    <td>
                        <span class="badge ${statusBadgeClass}">
                            ${customer.status || 'N/A'}
                        </span>
                    </td>
                    <td class="text-end" style="white-space: nowrap;">${actionsHtml}</td>
                `;
                tbody.appendChild(row);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center">No customers found</td></tr>';
        }

        // Update pagination info if provided
        if (pagination) {
            updatePaginationInfo(pagination);
        }
    }

    // Get status badge class
    function getStatusBadgeClass(status) {
        switch(status) {
            case 'Active': return 'bg-success';
            case 'Pending': return 'bg-warning';
            case 'Inactive': return 'bg-secondary';
            case 'Schedule': return 'bg-info';
            default: return 'bg-secondary';
        }
    }

    // Update pagination info (DISABLED - Using Laravel pagination)
    function updatePaginationInfo(pagination) {
        console.log('updatePaginationInfo is disabled - using Laravel pagination instead');
        return;
    }


    // SMS function for customers - sends appointment details
    function sendSms(customerId) {
        fetch(`${window.location.origin}/company/home-connections/customers/${customerId}/send-sms`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.payload) {
                console.log('Customer SMS preview:', data.payload);
            }
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'SMS Sent!',
                    text: data.message || 'SMS sent successfully with appointment details'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error || 'Failed to send SMS'
                });
            }
        })
        .catch(error => {
            console.error('SMS Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to send SMS. Please try again.'
            });
        });
    }

    // Load appointment data for a customer
    function loadAppointmentData(customerId) {
        console.log('Loading appointment data for customer:', customerId);

        // Check assignments data first (primary source)
        if (!window.assignmentsData || !Array.isArray(window.assignmentsData) || window.assignmentsData.length === 0) {
            // Show default no appointment data
            document.getElementById('appointmentStatus').innerHTML = '<span class="badge bg-warning">No Assignment</span>';
            document.getElementById('assignedTeam').textContent = 'Not assigned';
            document.getElementById('appointmentDateDisplay').textContent = 'Not scheduled';
            document.getElementById('appointmentTimeDisplay').textContent = 'Not scheduled';
            return;
        }

        // Find assignments for this customer
        console.log('All assignments data:', window.assignmentsData);
        console.log('Looking for customer ID:', customerId);
        
        const customerAssignments = window.assignmentsData.filter(assignment => 
            assignment.customer_id == customerId
        );

        console.log('Found customer assignments:', customerAssignments);

        if (customerAssignments.length === 0) {
            // No assignments found
            document.getElementById('appointmentStatus').innerHTML = '<span class="badge bg-warning">No Assignment</span>';
            document.getElementById('assignedTeam').textContent = 'Not assigned';
            document.getElementById('appointmentDateDisplay').textContent = 'Not scheduled';
            document.getElementById('appointmentTimeDisplay').textContent = 'Not scheduled';
            return;
        }

        // Get the most recent assignment
        const latestAssignment = customerAssignments[0];
        
        // Set appointment status with appropriate badge for assignments
        let statusBadge = '';
        switch(latestAssignment.status) {
            case 'pending':
                statusBadge = '<span class="badge bg-warning">Pending</span>';
                break;
            case 'in_progress':
                statusBadge = '<span class="badge bg-info">In Progress</span>';
                break;
            case 'completed':
                statusBadge = '<span class="badge bg-success">Completed</span>';
                break;
            case 'cancelled':
                statusBadge = '<span class="badge bg-secondary">Cancelled</span>';
                break;
            default:
                statusBadge = '<span class="badge bg-warning">Pending</span>';
        }
        document.getElementById('appointmentStatus').innerHTML = statusBadge;

        // Set assigned team
        if (latestAssignment.team && latestAssignment.team.team_name) {
            document.getElementById('assignedTeam').textContent = `${latestAssignment.team.team_name} (${latestAssignment.team.team_code || 'N/A'})`;
        } else {
            document.getElementById('assignedTeam').textContent = 'Not assigned';
        }
        
        // Get appointment date from assigned_date column (primary source)
        const appointmentDateTime = latestAssignment.assigned_date;
        console.log('Found assigned_date from database:', appointmentDateTime);
        console.log('Type of assigned_date:', typeof appointmentDateTime);
        console.log('Raw assignment object:', JSON.stringify(latestAssignment, null, 2));

        // Set appointment date and time
        console.log('Appointment date/time source:', appointmentDateTime);
        
        if (appointmentDateTime) {
            // Handle datetime-local format (YYYY-MM-DDTHH:mm)
            let assignedDate;
            
            if (typeof appointmentDateTime === 'string' && appointmentDateTime.includes('T')) {
                // This is datetime-local format from Schedule Appointment modal
                assignedDate = new Date(appointmentDateTime);
                console.log('Parsed datetime-local format:', assignedDate);
            } else {
                // This is from assignments database
                assignedDate = new Date(appointmentDateTime);
                console.log('Parsed database datetime:', assignedDate);
                
                // If invalid, try different approaches
                if (isNaN(assignedDate.getTime())) {
                    console.log('First attempt failed, trying alternative parsing...');
                    
                    // Handle Laravel datetime format: "2024-01-15 09:00:00"
                    if (typeof appointmentDateTime === 'string') {
                        // Replace space with 'T' for ISO format
                        const isoFormat = appointmentDateTime.replace(' ', 'T');
                        assignedDate = new Date(isoFormat);
                        console.log('ISO format attempt:', assignedDate);
                    }
                    
                    // If still invalid, try just the date part
                    if (isNaN(assignedDate.getTime())) {
                        const datePart = appointmentDateTime.split(' ')[0]; // Get just the date part
                        assignedDate = new Date(datePart);
                        console.log('Date part only attempt:', assignedDate);
                    }
                }
            }
            
            console.log('Final parsed appointment date:', assignedDate);
            
            // Check if date is valid
            if (!isNaN(assignedDate.getTime())) {
                const dateOptions = { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                };
                const timeOptions = { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: true 
                };
                
                // Try multiple date formatting approaches
                const formattedDate = assignedDate.toLocaleDateString('en-US', dateOptions);
                const formattedTime = assignedDate.toLocaleTimeString('en-US', timeOptions);
                
                // Fallback date format if locale formatting fails
                const fallbackDate = assignedDate.toDateString();
                const fallbackTime = assignedDate.toTimeString().split(' ')[0];
                
                console.log('Fallback date:', fallbackDate);
                console.log('Fallback time:', fallbackTime);
                
                console.log('Formatted date:', formattedDate);
                console.log('Formatted time:', formattedTime);
                
                // Ensure we're setting the text content properly
                const dateElement = document.getElementById('appointmentDateDisplay');
                const timeElement = document.getElementById('appointmentTimeDisplay');
                
                if (dateElement) {
                    // Use formatted date if available, otherwise use fallback
                    const finalDate = formattedDate || fallbackDate;
                    dateElement.textContent = finalDate;
                    dateElement.innerHTML = finalDate;
                    console.log('Date element updated:', dateElement.textContent);
                } else {
                    console.error('Date element not found!');
                }
                
                if (timeElement) {
                    // Use formatted time if available, otherwise use fallback
                    const finalTime = formattedTime || fallbackTime;
                    timeElement.textContent = finalTime;
                    console.log('Time element updated:', timeElement.textContent);
                } else {
                    console.error('Time element not found!');
                }
            } else {
                console.log('Invalid date format');
                document.getElementById('appointmentDateDisplay').textContent = 'Invalid date';
                document.getElementById('appointmentTimeDisplay').textContent = 'Invalid time';
            }
        } else {
            console.log('No appointment date/time found');
            document.getElementById('appointmentDateDisplay').textContent = 'Not scheduled';
            document.getElementById('appointmentTimeDisplay').textContent = 'Not scheduled';
        }

        // Update assignment details if available
        if (latestAssignment.assignment_title) {
            // Could add more details here if needed
            console.log('Assignment Title:', latestAssignment.assignment_title);
        }
        
        // Force update the date elements to ensure they're not stuck on "-"
        setTimeout(() => {
            const dateElement = document.getElementById('appointmentDateDisplay');
            const timeElement = document.getElementById('appointmentTimeDisplay');
            
            if (dateElement && dateElement.textContent === '-') {
                console.log('Date element still shows "-", forcing update...');
                // Try to get the date from assigned_date column in database
                let appointmentDate = null;
                if (latestAssignment && latestAssignment.assigned_date) {
                    appointmentDate = latestAssignment.assigned_date;
                }
                
                if (appointmentDate) {
                    // Use same improved parsing logic
                    let assignedDate = new Date(appointmentDate);
                    if (isNaN(assignedDate.getTime()) && typeof appointmentDate === 'string') {
                        const isoFormat = appointmentDate.replace(' ', 'T');
                        assignedDate = new Date(isoFormat);
                    }
                    if (isNaN(assignedDate.getTime())) {
                        const datePart = appointmentDate.split(' ')[0];
                        assignedDate = new Date(datePart);
                    }
                    
                    if (!isNaN(assignedDate.getTime())) {
                        const fallbackDate = assignedDate.toDateString();
                    dateElement.textContent = fallbackDate;
                    dateElement.innerHTML = fallbackDate;
                        console.log('Forced date update to:', fallbackDate);
                    } else {
                        console.log('All parsing attempts failed for date:', appointmentDate);
                    }
                } else {
                    dateElement.textContent = 'Not scheduled';
                    console.log('Forced date to: Not scheduled');
                }
            }
            
            if (timeElement && timeElement.textContent === '-') {
                console.log('Time element still shows "-", forcing update...');
                let appointmentDate = null;
                if (latestAssignment && latestAssignment.assigned_date) {
                    appointmentDate = latestAssignment.assigned_date;
                }
                
                if (appointmentDate) {
                    // Use same improved parsing logic
                    let assignedDate = new Date(appointmentDate);
                    if (isNaN(assignedDate.getTime()) && typeof appointmentDate === 'string') {
                        const isoFormat = appointmentDate.replace(' ', 'T');
                        assignedDate = new Date(isoFormat);
                    }
                    if (isNaN(assignedDate.getTime())) {
                        const datePart = appointmentDate.split(' ')[0];
                        assignedDate = new Date(datePart);
                    }
                    
                    if (!isNaN(assignedDate.getTime())) {
                        const fallbackTime = assignedDate.toTimeString().split(' ')[0];
                    timeElement.textContent = fallbackTime;
                        console.log('Forced time update to:', fallbackTime);
                    } else {
                        console.log('All parsing attempts failed for time:', appointmentDate);
                    }
                } else {
                    timeElement.textContent = 'Not scheduled';
                    console.log('Forced time to: Not scheduled');
                }
            }
        }, 100); // Small delay to ensure DOM is updated
    }

    // Initialize the table when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Setup toast container
        setupToastContainer();

        // Bulk Actions: Track selected customers
        const selectedCustomers = new Set();
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCountSpan = document.getElementById('selectedCount');
        const selectAllCheckbox = document.getElementById('selectAllCustomers');
        const customerCheckboxes = document.querySelectorAll('.customer-checkbox');

        // Update bulk actions bar visibility
        function updateBulkActionsBar() {
            const count = selectedCustomers.size;
            if (count > 0 && bulkActionsBar) {
                bulkActionsBar.classList.remove('d-none');
                if (selectedCountSpan) selectedCountSpan.textContent = count;
            } else if (bulkActionsBar) {
                bulkActionsBar.classList.add('d-none');
            }
        }

        // Select All functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                customerCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    const customerId = checkbox.getAttribute('data-customer-id');
                    const customerName = checkbox.getAttribute('data-customer-name');
                    
                    if (isChecked) {
                        selectedCustomers.add(JSON.stringify({ id: customerId, name: customerName }));
                    } else {
                        selectedCustomers.delete(JSON.stringify({ id: customerId, name: customerName }));
                    }
                });
                updateBulkActionsBar();
            });
        }

        // Individual checkbox selection
        customerCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const customerId = this.getAttribute('data-customer-id');
                const customerName = this.getAttribute('data-customer-name');
                const customerData = JSON.stringify({ id: customerId, name: customerName });
                
                if (this.checked) {
                    selectedCustomers.add(customerData);
                } else {
                    selectedCustomers.delete(customerData);
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;
                }
                
                updateBulkActionsBar();
            });
        });

        // Clear Selection Button
        const clearSelectionBtn = document.getElementById('clearSelectionBtn');
        if (clearSelectionBtn) {
            clearSelectionBtn.addEventListener('click', function() {
                selectedCustomers.clear();
                customerCheckboxes.forEach(checkbox => checkbox.checked = false);
                if (selectAllCheckbox) selectAllCheckbox.checked = false;
                updateBulkActionsBar();
            });
        }

        // Bulk Schedule Button
        const bulkScheduleBtn = document.getElementById('bulkScheduleBtn');
        if (bulkScheduleBtn) {
            bulkScheduleBtn.addEventListener('click', function() {
                if (selectedCustomers.size === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Customers Selected',
                        text: 'Please select at least one customer to schedule appointments.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Populate the modal with selected customers
                const selectedCustomersList = document.getElementById('selectedCustomersList');
                const bulkCustomerIds = document.getElementById('bulkCustomerIds');
                const bulkScheduleCount = document.getElementById('bulkScheduleCount');
                
                // Parse selected customers
                const customers = Array.from(selectedCustomers).map(item => JSON.parse(item));
                
                // Create customer IDs array
                const customerIds = customers.map(c => c.id);
                
                // Set hidden field value
                if (bulkCustomerIds) {
                    bulkCustomerIds.value = JSON.stringify(customerIds);
                }
                
                // Update count
                if (bulkScheduleCount) {
                    bulkScheduleCount.textContent = customers.length;
                }
                
                // Create customer list HTML
                let listHTML = '<div class="row">';
                customers.forEach((customer, index) => {
                    listHTML += `
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="ri-user-line me-2 text-primary"></i>
                                <span class="small">${index + 1}. ${customer.name}</span>
                            </div>
                        </div>
                    `;
                });
                listHTML += '</div>';
                
                if (selectedCustomersList) {
                    selectedCustomersList.innerHTML = listHTML;
                }
                
                // Set default appointment date to next hour
                const now = new Date();
                now.setHours(now.getHours() + 1, 0, 0, 0);
                const bulkAppointmentDate = document.getElementById('bulkAppointmentDate');
                if (bulkAppointmentDate) {
                    bulkAppointmentDate.value = now.toISOString().slice(0, 16);
                }
                
                // Show the modal
                const bulkScheduleModal = new bootstrap.Modal(document.getElementById('bulkScheduleAppointmentModal'));
                bulkScheduleModal.show();
            });
        }

        // Handle bulk schedule form submission
        const bulkScheduleForm = document.getElementById('bulkScheduleAppointmentForm');
        if (bulkScheduleForm) {
            bulkScheduleForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Scheduling...';
                
                // Show confirmation
                const customers = Array.from(selectedCustomers).map(item => JSON.parse(item));
                
                Swal.fire({
                    title: 'Confirm Bulk Schedule',
                    html: `You are about to schedule appointments for <strong>${customers.length}</strong> customer(s).<br><br>Do you want to proceed?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#405189',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Schedule',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form
                        this.submit();
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            });
        }
        
        // Add loading indicator if not exists
        if (!document.getElementById('loadingIndicator')) {
            const loadingIndicator = document.createElement('div');
            loadingIndicator.id = 'loadingIndicator';
            loadingIndicator.className = 'position-fixed top-0 start-0 w-100 text-center p-2 bg-primary text-white d-none';
            loadingIndicator.style.zIndex = '9999';
            loadingIndicator.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></span> Loading...';
            document.body.appendChild(loadingIndicator);
        }
        
        // Initialize elements
        const companySelect = document.getElementById('companySelect');
        
        // Initialize export modal with current company selection
        const currentCompany = companySelect.value;
        const exportBusinessUnit = document.getElementById('exportBusinessUnit');
        if (exportBusinessUnit) {
            exportBusinessUnit.value = currentCompany;
        }
        
        // Get modal elements
        const viewCustomerModalEl = document.getElementById('viewCustomerModal');
        const editCustomerModalEl = document.getElementById('editCustomerModal');
        const scheduleAppointmentModalEl = document.getElementById('scheduleAppointmentModal');
        
        // Initialize modals if elements exist
        let viewCustomerModal, editCustomerModal, scheduleAppointmentModal;
        
        if (viewCustomerModalEl) {
            viewCustomerModal = new bootstrap.Modal(viewCustomerModalEl);
        }
        if (editCustomerModalEl) {
            editCustomerModal = new bootstrap.Modal(editCustomerModalEl);
        }
        if (scheduleAppointmentModalEl) {
            scheduleAppointmentModal = new bootstrap.Modal(scheduleAppointmentModalEl);
        }
        
        // Auto-expand filters if any filter is active
        const customerFilters = document.getElementById('customerFilters');
        @if(isset($assignmentFilters) && array_filter($assignmentFilters))
            if (customerFilters && bootstrap.Collapse) {
                const bsCollapse = new bootstrap.Collapse(customerFilters, {
                    show: true
                });
            }
        @endif

        // Auto Schedule Toggle
        const autoScheduleToggle = document.getElementById('autoScheduleToggle');
        const autoScheduleHiddenField = document.getElementById('autoScheduleEnabledField');
        const bulkAutoScheduleHiddenField = document.getElementById('bulkAutoScheduleEnabledField');

        const syncAutoScheduleHiddenFields = (enabled) => {
            if (autoScheduleHiddenField) {
                autoScheduleHiddenField.value = enabled ? 'true' : 'false';
            }
            if (bulkAutoScheduleHiddenField) {
                bulkAutoScheduleHiddenField.value = enabled ? 'true' : 'false';
            }
        };
        
        // Load auto schedule state from localStorage
        if (autoScheduleToggle) {
            const isEnabled = localStorage.getItem('autoScheduleEnabled') === 'true';
            autoScheduleToggle.checked = isEnabled;
            syncAutoScheduleHiddenFields(isEnabled);
            
            // Handle toggle change
            autoScheduleToggle.addEventListener('change', function() {
                const enabled = this.checked;
                localStorage.setItem('autoScheduleEnabled', enabled ? 'true' : 'false');
                syncAutoScheduleHiddenFields(enabled);
                showToast(
                    enabled ? 'Success' : 'Info',
                    enabled
                        ? 'Auto Schedule enabled. New customers will be automatically assigned to a random team with High priority for next day 9:00 AM.'
                        : 'Auto Schedule disabled.',
                    enabled ? 'success' : 'info'
                );
            });
        } else {
            // Ensure hidden fields reflect local storage even if toggle is not rendered
            const isEnabled = localStorage.getItem('autoScheduleEnabled') === 'true';
            syncAutoScheduleHiddenFields(isEnabled);
        }

        // Real-time Customer Filtering
        const filterInputs = document.querySelectorAll('.filter-input');
        const customerTableBody = document.getElementById('customerTableBody');
        
        // Get all customer rows (excluding any pagination or other rows)
        const allCustomerRows = Array.from(customerTableBody.querySelectorAll('tr')).filter(row => {
            // Only include rows that have customer data (not pagination or empty rows)
            const cells = row.querySelectorAll('td');
            return cells.length >= 8 && !row.classList.contains('no-results-row'); // Customer rows should have at least 8 cells and not be the "no results" row
        });
        
        console.log('Found customer rows:', allCustomerRows.length);
        
        // Store original customer data for filtering
        const customerData = allCustomerRows.map((row, index) => {
            const cells = row.querySelectorAll('td');
            // Check if first cell has a checkbox (indicates canManageCustomers is true)
            const hasCheckbox = cells[0]?.querySelector('input[type="checkbox"]') !== null;
            const offset = hasCheckbox ? 1 : 0;

            const data = {
                element: row,
                index: index,
                msisdn: cells[offset]?.textContent?.trim() || '',
                name: cells[offset + 1]?.textContent?.trim() || '',
                contacts: cells[offset + 2]?.textContent?.trim() || '',
                connectionType: cells[offset + 3]?.textContent?.trim() || '',
                location: cells[offset + 4]?.textContent?.trim() || '',
                gpsAddress: cells[offset + 5]?.textContent?.trim() || '',
                gpsCoordinates: cells[offset + 6]?.textContent?.trim() || '',
                status: cells[offset + 7]?.textContent?.trim() || ''
            };
            console.log(`Customer ${index}:`, data);
            return data;
        });

        function filterCustomers() {
            const filters = {
                company: companySelect?.value || '',
                dateFrom: document.getElementById('filter_date_from')?.value || '',
                dateTo: document.getElementById('filter_date_to')?.value || '',
                team: document.getElementById('filter_team')?.value || '',
                location: document.getElementById('filter_location')?.value || '',
                connectionType: document.getElementById('filter_connection_type')?.value || '',
                issue: document.getElementById('filter_issue')?.value || ''
            };

            console.log('Applying filters:', filters);

            // Hide all rows first
            allCustomerRows.forEach(row => {
                row.style.display = 'none';
                row.style.visibility = 'hidden';
            });

            // Filter and show matching rows
            const visibleRows = customerData.filter(customer => {
                let matches = true;

                // Location filter - handle "Area - City" format
                if (filters.location) {
                    // Check if the selected location is contained in the customer's location
                    // This handles both exact matches and "Area - City" format
                    const customerLocation = customer.location.toLowerCase();
                    const filterLocation = filters.location.toLowerCase();
                    if (!customerLocation.includes(filterLocation)) {
                        matches = false;
                    }
                }

                // Connection type filter
                if (filters.connectionType && customer.connectionType !== filters.connectionType) {
                    matches = false;
                }

                // Date from/to, team, and issue filters - check against assignments data
                if (filters.dateFrom || filters.dateTo || filters.team || filters.issue) {
                    // Find assignments for this customer
                    const customerAssignments = window.assignmentsData.filter(assignment =>
                        assignment.customer_id == customer.element.querySelector('[data-customer-id]')?.getAttribute('data-customer-id')
                    );

                    if (customerAssignments.length === 0) {
                        // No assignments found - only show if no assignment-related filters are applied
                        if (filters.dateFrom || filters.dateTo || filters.team || filters.issue) {
                            matches = false;
                        }
                    } else {
                        // Check each assignment against the filters
                        let assignmentMatches = false;

                        for (const assignment of customerAssignments) {
                            let assignmentMatch = true;

                            // Date from filter
                            if (filters.dateFrom) {
                                const assignmentDate = new Date(assignment.assigned_date);
                                const filterDateFrom = new Date(filters.dateFrom);
                                if (assignmentDate < filterDateFrom) {
                                    assignmentMatch = false;
                                }
                            }

                            // Date to filter
                            if (filters.dateTo) {
                                const assignmentDate = new Date(assignment.assigned_date);
                                const filterDateTo = new Date(filters.dateTo);
                                // Set time to end of day for date-to filter
                                filterDateTo.setHours(23, 59, 59, 999);
                                if (assignmentDate > filterDateTo) {
                                    assignmentMatch = false;
                                }
                            }

                            // Team filter
                            if (filters.team) {
                                if (!assignment.team || assignment.team.id != filters.team) {
                                    assignmentMatch = false;
                                }
                            }

                            // Issue status filter
                            if (filters.issue) {
                                if (assignment.status !== filters.issue) {
                                    assignmentMatch = false;
                                }
                            }

                            if (assignmentMatch) {
                                assignmentMatches = true;
                                break; // At least one assignment matches
                            }
                        }

                        if (!assignmentMatches) {
                            matches = false;
                        }
                    }
                }

                console.log(`Customer ${customer.name}: location="${customer.location}", connectionType="${customer.connectionType}", matches=${matches}`);
                return matches;
            });

            console.log('Visible rows after filtering:', visibleRows.length);

            // Show matching rows
            visibleRows.forEach(customer => {
                customer.element.style.display = '';
                customer.element.style.visibility = 'visible';
                console.log('Showing customer:', customer.name);
            });

            // Remove any existing "no results" row
            const existingNoResults = customerTableBody.querySelector('tr.no-results-row');
            if (existingNoResults) {
                existingNoResults.remove();
            }

            // Show message if no results
            if (visibleRows.length === 0) {
                const noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = `
                    <td colspan="9" class="text-center py-4">
                        <div class="text-muted">
                            <i class="ri-search-line me-2"></i>
                            No customers match the current filters
                        </div>
                    </td>
                `;
                customerTableBody.appendChild(noResultsRow);
            }

            console.log(`Filtered customers: ${visibleRows.length} of ${customerData.length} visible`);
            
            // Update filter indicator
            updateFilterIndicator(filters);
        }

        function updateFilterIndicator(filters) {
            const activeFilters = Object.values(filters).filter(value => value !== '').length;
            const filterButton = document.querySelector('[data-bs-target="#customerFilters"]');
            
            if (activeFilters > 0) {
                filterButton.innerHTML = `
                    <i class="ri-filter-3-line me-1"></i> Filters 
                    <span class="badge bg-primary ms-1">${activeFilters}</span>
                `;
            } else {
                filterButton.innerHTML = `
                    <i class="ri-filter-3-line me-1"></i> Filters
                `;
            }
        }

        // Add event listeners to all filter inputs with debouncing
        let filterTimeout;
        filterInputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(filterCustomers, 300); // 300ms delay
            });
            input.addEventListener('change', filterCustomers);
        });

        // Clear filters functionality
        document.getElementById('clearFiltersBtn')?.addEventListener('click', function() {
            filterInputs.forEach(input => {
                if (input.type === 'date') {
                    input.value = '';
                } else if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                }
            });
            filterCustomers();
        });

        // Show all customers functionality (for debugging)
        document.getElementById('showAllBtn')?.addEventListener('click', function() {
            console.log('Showing all customers...');
            allCustomerRows.forEach(row => {
                row.style.display = '';
                row.style.visibility = 'visible';
            });
            
            // Remove any "no results" row
            const existingNoResults = customerTableBody.querySelector('tr.no-results-row');
            if (existingNoResults) {
                existingNoResults.remove();
            }
            
            console.log('All customers should now be visible');
        });

        // Initial filter application
        filterCustomers();
        
        // Handle company selection change
        companySelect.addEventListener('change', function() {
            const selectedCompany = this.value;
            console.log('Company filter changed to:', selectedCompany);
            
            // Update export modal with selected company
            const exportBusinessUnit = document.getElementById('exportBusinessUnit');
            if (exportBusinessUnit) {
                exportBusinessUnit.value = selectedCompany;
            }
            
            // Apply company filter to customer table
            filterCustomers();
            
            // Build URL with business_unit parameter
            const currentUrl = new URL(window.location);
            const params = new URLSearchParams(currentUrl.search);
            
            if (selectedCompany) {
                params.set('business_unit', selectedCompany);
            } else {
                params.delete('business_unit');
            }
            
            // Reload page with new parameters (preserves pagination)
            window.location.href = `${currentUrl.pathname}?${params.toString()}`;
        });
        
        // Handle view customer
        document.addEventListener('click', function(e) {
            // View button
            if (e.target.closest('.view-customer-btn') || e.target.closest('[title="View"]')) {
                const button = e.target.closest('.view-customer-btn') || e.target.closest('[title="View"]');
                
                // Get customer data from data attributes
                const customerData = {
                    id: button.getAttribute('data-customer-id'),
                    name: button.getAttribute('data-customer-name'),
                    msisdn: button.getAttribute('data-customer-msisdn'),
                    email: button.getAttribute('data-customer-email'),
                    phone: button.getAttribute('data-customer-phone'),
                    connectionType: button.getAttribute('data-customer-connection-type'),
                    location: button.getAttribute('data-customer-location'),
                    gpsAddress: button.getAttribute('data-customer-gps-address'),
                    latitude: button.getAttribute('data-customer-latitude'),
                    longitude: button.getAttribute('data-customer-longitude'),
                    status: button.getAttribute('data-customer-status')
                };
                
                // Populate view modal
                document.getElementById('viewCustomerName').textContent = customerData.name;
                document.getElementById('viewMsisdn').textContent = customerData.msisdn;
                document.getElementById('viewEmail').textContent = customerData.email || 'N/A';
                document.getElementById('viewPhone').textContent = customerData.phone || 'N/A';
                document.getElementById('viewConnectionType').textContent = customerData.connectionType || 'N/A';
                document.getElementById('viewLocation').textContent = customerData.location || 'N/A';
                document.getElementById('viewGpsAddress').textContent = customerData.gpsAddress || 'N/A';
                
                // Format GPS coordinates for display
                let gpsDisplay = 'N/A';
                if (customerData.latitude && customerData.longitude) {
                    gpsDisplay = `${customerData.latitude}, ${customerData.longitude}`;
                } else if (customerData.latitude || customerData.longitude) {
                    gpsDisplay = `${customerData.latitude || 'N/A'}, ${customerData.longitude || 'N/A'}`;
                }
                document.getElementById('viewGpsCoordinates').textContent = gpsDisplay;
                document.getElementById('viewStatus').innerHTML = `<span class="badge bg-${customerData.status === 'Active' ? 'success' : customerData.status === 'Pending' ? 'warning' : 'secondary'}">${customerData.status}</span>`;
                
                if (viewCustomerModal) {
                    viewCustomerModal.show();
                }
            }
            
            // Contact & Appointment button
            else if (e.target.closest('.contact-appointment-btn') || e.target.closest('[title="Contact & Appointment Info"]')) {
                const button = e.target.closest('.contact-appointment-btn') || e.target.closest('[title="Contact & Appointment Info"]');
                
                // Get customer data from data attributes
                const customerData = {
                    id: button.getAttribute('data-customer-id'),
                    name: button.getAttribute('data-customer-name'),
                    msisdn: button.getAttribute('data-customer-msisdn'),
                    phone: button.getAttribute('data-customer-phone'),
                    email: button.getAttribute('data-customer-email'),
                    location: button.getAttribute('data-customer-location')
                };
                
                // Populate contact/appointment modal
                document.getElementById('contactCustomerName').textContent = customerData.name || '-';
                document.getElementById('contactCustomerLocation').textContent = customerData.location || '-';
                document.getElementById('contactCustomerEmail').textContent = customerData.email || '-';
                document.getElementById('contactCustomerMsisdn').textContent = customerData.msisdn || '-';
                document.getElementById('contactCustomerPhone').textContent = customerData.phone || '-';
                
                // Set up dial buttons
                const dialMsisdnBtn = document.getElementById('dialMsisdnBtn');
                const dialPhoneBtn = document.getElementById('dialPhoneBtn');
                
                if (customerData.msisdn && customerData.msisdn !== '-') {
                    dialMsisdnBtn.style.display = 'inline-block';
                    dialMsisdnBtn.onclick = () => {
                        window.open(`tel:${customerData.msisdn}`, '_self');
                    };
                } else {
                    dialMsisdnBtn.style.display = 'none';
                }
                
                if (customerData.phone && customerData.phone !== '-') {
                    dialPhoneBtn.style.display = 'inline-block';
                    dialPhoneBtn.onclick = () => {
                        window.open(`tel:${customerData.phone}`, '_self');
                    };
                } else {
                    dialPhoneBtn.style.display = 'none';
                }
                
                // Set up quick action buttons
                document.getElementById('sendSmsBtn').onclick = () => {
                    // Use the SMS function from users blade
                    sendSms(customerData.id);
                };
                
                document.getElementById('viewLocationBtn').onclick = () => {
                    if (customerData.location && customerData.location !== '-') {
                        const encodedLocation = encodeURIComponent(customerData.location);
                        window.open(`https://www.google.com/maps/search/?api=1&query=${encodedLocation}`, '_blank');
                    } else {
                        alert('No location available');
                    }
                };
                
                document.getElementById('scheduleAppointmentBtn').onclick = () => {
                    // Close contact modal and open schedule modal
                    const contactModal = bootstrap.Modal.getInstance(document.getElementById('contactAppointmentModal'));
                    contactModal.hide();
                    
                    // Find and click the schedule appointment button for this customer
                    const scheduleBtn = document.querySelector(`[data-customer-id="${customerData.id}"].schedule-appointment-btn`);
                    if (scheduleBtn) {
                        scheduleBtn.click();
                    } else {
                        // Fallback: manually open the schedule modal
                        const scheduleModal = new bootstrap.Modal(document.getElementById('scheduleAppointmentModal'));
                        
                        // Set customer data
                        document.getElementById('appointmentCustomerId').value = customerData.id;
                        document.getElementById('appointmentCustomerName').value = customerData.name;
                        
                        // Set default appointment time to next hour
                        const now = new Date();
                        now.setHours(now.getHours() + 1, 0, 0, 0);
                        document.getElementById('appointmentDate').value = now.toISOString().slice(0, 16);
                        
                        scheduleModal.show();
                    }
                };
                
                // Load appointment data from site assignments
                loadAppointmentData(customerData.id);
                
                // Show the modal
                const contactAppointmentModal = new bootstrap.Modal(document.getElementById('contactAppointmentModal'));
                contactAppointmentModal.show();
            }
            
            // Edit button
            else if (e.target.closest('.edit-customer-btn') || e.target.closest('[title="Edit"]')) {
                const button = e.target.closest('.edit-customer-btn') || e.target.closest('[title="Edit"]');
                
                // Get customer data from data attributes
                const customerData = {
                    id: button.getAttribute('data-customer-id'),
                    name: button.getAttribute('data-customer-name'),
                    msisdn: button.getAttribute('data-customer-msisdn').replace('+233', ''),
                    email: button.getAttribute('data-customer-email'),
                    phone: button.getAttribute('data-customer-phone').replace('+233', ''),
                    connectionType: button.getAttribute('data-customer-connection-type'),
                    location: button.getAttribute('data-customer-location'),
                    gpsAddress: button.getAttribute('data-customer-gps-address'),
                    latitude: button.getAttribute('data-customer-latitude'),
                    longitude: button.getAttribute('data-customer-longitude'),
                    status: button.getAttribute('data-customer-status')
                };
                
                // Update form action URL
                const form = document.getElementById('editCustomerForm');
                form.action = form.action.replace(':id', customerData.id);
                
                // Debug: Log the form action
                console.log('Form action updated to:', form.action);
                console.log('GPS Coordinates being parsed:', customerData.latitude, customerData.longitude);
                console.log('Extracted latitude:', customerData.latitude, 'longitude:', customerData.longitude);
                
                // Populate edit form
                document.getElementById('editCustomerId').value = customerData.id;
                document.getElementById('editMsisdn').value = customerData.msisdn;
                document.getElementById('editCustomerName').value = customerData.name;
                document.getElementById('editEmail').value = customerData.email;
                document.getElementById('editPhone').value = customerData.phone;
                document.getElementById('editConnectionType').value = customerData.connectionType;
                document.getElementById('editLocation').value = customerData.location;
                document.getElementById('editGpsAddress').value = customerData.gpsAddress;
                document.getElementById('editLatitude').value = customerData.latitude || '';
                document.getElementById('editLongitude').value = customerData.longitude || '';
                document.getElementById('editStatus').value = customerData.status;
                
                // Disable HTML5 validation for optional GPS fields
                const gpsAddressField = document.getElementById('editGpsAddress');
                const latitudeField = document.getElementById('editLatitude');
                const longitudeField = document.getElementById('editLongitude');
                
                if (gpsAddressField) gpsAddressField.removeAttribute('required');
                if (latitudeField) latitudeField.removeAttribute('required');
                if (longitudeField) longitudeField.removeAttribute('required');
                
                if (editCustomerModal) {
                    editCustomerModal.show();
                }
            }
            
            // Schedule appointment button
            else if (e.target.closest('.schedule-appointment-btn') || e.target.closest('[title="Schedule Appointment"]')) {
                const button = e.target.closest('.schedule-appointment-btn') || e.target.closest('[title="Schedule Appointment"]');
                const customerId = button.getAttribute('data-customer-id');
                const row = button.closest('tr');
                const customerName = row.cells[1].textContent;
                
                // Set customer info in the form
                document.getElementById('appointmentCustomerId').value = customerId;
                document.getElementById('appointmentCustomerName').value = customerName;
                
                // Set default appointment time to next hour
                const now = new Date();
                now.setHours(now.getHours() + 1, 0, 0, 0);
                document.getElementById('appointmentDate').value = now.toISOString().slice(0, 16);
                
                // Clear other fields
                document.getElementById('appointmentPurpose').value = '';
                document.getElementById('assignedEngineer').value = '';
                document.getElementById('prioritySelect').value = 'medium';
                
                if (scheduleAppointmentModal) {
                    scheduleAppointmentModal.show();
                }
            }
            
        });
        
        // Handle edit customer form submission with loading state and AJAX
        document.getElementById('editCustomerForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Prevent HTML5 validation for optional GPS fields
            const latitudeField = document.getElementById('editLatitude');
            const longitudeField = document.getElementById('editLongitude');
            const gpsAddressField = document.getElementById('editGpsAddress');

            // Temporarily remove validation for GPS fields to allow empty submission
            if (latitudeField && !latitudeField.value.trim()) {
                latitudeField.setAttribute('data-original-required', latitudeField.hasAttribute('required'));
                latitudeField.removeAttribute('required');
            }
            if (longitudeField && !longitudeField.value.trim()) {
                longitudeField.setAttribute('data-original-required', longitudeField.hasAttribute('required'));
                longitudeField.removeAttribute('required');
            }
            if (gpsAddressField && !gpsAddressField.value.trim()) {
                gpsAddressField.setAttribute('data-original-required', gpsAddressField.hasAttribute('required'));
                gpsAddressField.removeAttribute('required');
            }

            // Add loading state to submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

            // Prepare form data
            const formData = new FormData(this);
            console.log('Submitting customer update:', {
                action: this.action,
                formData: Object.fromEntries(formData.entries())
            });

            // Submit via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                console.log('Server response:', {
                    status: response.status,
                    statusText: response.statusText,
                    headers: Object.fromEntries(response.headers.entries())
                });

                if (!response.ok) {
                    throw new Error('Update failed');
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);

                if (data.success || data.message) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: data.message || 'Customer updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Close modal
                    const editModal = bootstrap.Modal.getInstance(document.getElementById('editCustomerModal'));
                    if (editModal) {
                        editModal.hide();
                    }

                    // Refresh the page to show updated data
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    throw new Error(data.message || 'Update failed');
                }
            })
            .catch(error => {
                console.error('Update error:', error);

                // Handle validation errors
                if (error.message === 'Validation failed' || error.message === 'Update failed') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: error.message || 'Failed to update customer. Please check your input.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: error.message || 'Failed to update customer. Please try again.',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .finally(() => {
                // Restore validation attributes
                if (latitudeField && latitudeField.hasAttribute('data-original-required')) {
                    latitudeField.setAttribute('required', '');
                    latitudeField.removeAttribute('data-original-required');
                }
                if (longitudeField && longitudeField.hasAttribute('data-original-required')) {
                    longitudeField.setAttribute('required', '');
                    longitudeField.removeAttribute('data-original-required');
                }
                if (gpsAddressField && gpsAddressField.hasAttribute('data-original-required')) {
                    gpsAddressField.setAttribute('required', '');
                    gpsAddressField.removeAttribute('data-original-required');
                }

                // Restore button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // Reset form and button state when modal is hidden
        document.getElementById('editCustomerModal').addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('editCustomerForm');
            form.reset();
            
            // Clear validation states for GPS fields
            const latitudeField = document.getElementById('editLatitude');
            const longitudeField = document.getElementById('editLongitude');
            if (latitudeField) {
                latitudeField.classList.remove('is-invalid');
                latitudeField.removeAttribute('title');
            }
            if (longitudeField) {
                longitudeField.classList.remove('is-invalid');
                longitudeField.removeAttribute('title');
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Changes';
        });
        
        // Handle schedule appointment form submission
        // Schedule Appointment form now uses standard form submission to controller
        // No AJAX needed as it redirects to the same page with success message
        

        // Export functionality
        // Bulk upload form submission with loading state
        document.getElementById('bulkUploadForm').addEventListener('submit', function(e) {
            const hiddenField = document.getElementById('bulkAutoScheduleEnabledField');
            if (hiddenField) {
                hiddenField.value = localStorage.getItem('autoScheduleEnabled') === 'true' ? 'true' : 'false';
            }
            const fileInput = this.querySelector('input[type="file"]');
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please select a file to upload',
                    confirmButtonText: 'Ok'
                });
                return;
            }
            
            // Add loading state to submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
        });

        // Reset form and button state when modal is hidden
        document.getElementById('bulkUploadModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('bulkUploadForm').reset();
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Upload & Process';
        });

        // Handle export customers
        document.getElementById('confirmExportCustomers').addEventListener('click', function(e) {
            e.preventDefault();
            
            const businessUnit = document.getElementById('exportBusinessUnit').value;
            const exportType = businessUnit ? `${businessUnit} customers` : 'all customers';
            
            console.log('Export customers clicked:', exportType);
            
            // SweetAlert2 confirmation before export
            Swal.fire({
                title: 'Export Customers',
                text: `Do you want to export ${exportType} as CSV?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, export',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading dialog
                    Swal.fire({
                        title: 'Exporting...',
                        html: 'Your download will start shortly.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Prepare and submit the form via AJAX to get the CSV
                    const form = document.getElementById('exportCustomersForm');
                    const formData = new FormData(form);

                    fetch('{{ route("project-management.customers.export") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Export failed');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        Swal.close();
                        
                        // Create download link
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `customers_export_${new Date().toISOString().slice(0, 10)}.csv`;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);

                        Swal.fire({
                            icon: 'success',
                            title: 'Exported!',
                            text: 'Customers exported successfully.',
                            confirmButtonText: 'OK'
                        });
                        
                        // Close the modal
                        const exportModal = bootstrap.Modal.getInstance(document.getElementById('exportCustomersModal'));
                        if (exportModal) {
                            exportModal.hide();
                        }
                    })
                    .catch(error => {
                        console.error('Export error:', error);
                        Swal.close();
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Export Failed!',
                            text: 'Failed to export customers. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });
    });

    // Location Services
    document.addEventListener('DOMContentLoaded', function() {
        const getLocationBtn = document.getElementById('getLocationBtn');
        
        if (getLocationBtn) {
            getLocationBtn.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Locating...';
                
                const addressEl = document.getElementById('gpsAddress');
                const locationEl = document.querySelector('input[name="location"]');
                const addressText = (addressEl ? addressEl.value : '').trim();
                const fallbackText = (locationEl ? locationEl.value : '').trim();
                let query = addressText || fallbackText;

                if (!query) {
                    showToast('Error', 'Please enter a GPS Address or Location to look up', 'error');
                    this.disabled = false;
                    this.innerHTML = originalText;
                    return;
                }

                // If user typed coordinates like "5.6037, -0.1870" use them directly
                const coordMatch = query.match(/\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)\s*/);
                if (coordMatch) {
                    const lat = parseFloat(coordMatch[1]);
                    const lon = parseFloat(coordMatch[2]);
                    document.getElementById('latitude').value = isNaN(lat) ? '' : lat.toFixed(6);
                    document.getElementById('longitude').value = isNaN(lon) ? '' : lon.toFixed(6);
                    if (addressEl) addressEl.value = `${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                    showToast('Success', 'Coordinates parsed from input', 'success');
                    this.disabled = false;
                    this.innerHTML = originalText;
                    return;
                }

                // Parse Google Maps URL if pasted
                try {
                    const maybeUrl = new URL(query);
                    if (maybeUrl.hostname.includes('google')) {
                        // Patterns: @lat,lng, or q=lat,lng
                        const atMatch = maybeUrl.pathname.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                        const qParam = maybeUrl.searchParams.get('q');
                        const qMatch = qParam && qParam.match(/(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/);
                        const chosen = atMatch || qMatch;
                        if (chosen) {
                            const lat = parseFloat(chosen[1]);
                            const lon = parseFloat(chosen[2]);
                            if (!isNaN(lat) && !isNaN(lon)) {
                                document.getElementById('latitude').value = lat.toFixed(6);
                                document.getElementById('longitude').value = lon.toFixed(6);
                                if (addressEl) addressEl.value = `${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                                showToast('Success', 'Coordinates parsed from Google Maps link', 'success');
                                this.disabled = false;
                                this.innerHTML = originalText;
                                return;
                            }
                        }
                    }
                } catch (_) {}

                // Improve query with country hint (helps OSM for Ghana addresses/GPR)
                const lower = query.toLowerCase();
                if (!lower.includes('ghana') && !lower.includes('accra') && !lower.includes('kumasi')) {
                    query = `${query}, Ghana`;
                }

                // Provider 1: Nominatim with Ghana country bias
                const providers = [
                    // Nominatim with Ghana bias and bounding box
                    () => fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&addressdetails=1&countrycodes=gh&viewbox=-3.3,11.2,1.2,4.6&bounded=1&q=${encodeURIComponent(query)}`, { headers: { 'Accept': 'application/json' } }),
                    // Photon (Komoot) OSM geocoder
                    () => fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=1`, { headers: { 'Accept': 'application/json' } }),
                    // geocode.maps.co as last resort
                    () => fetch(`https://geocode.maps.co/search?q=${encodeURIComponent(query)}`, { headers: { 'Accept': 'application/json' } })
                ];

                (async () => {
                    let result = null;
                    let lastError = null;
                    for (const provider of providers) {
                        try {
                            const res = await provider();
                            if (!res.ok) { lastError = new Error('Geocoding failed'); continue; }
                            const json = await res.json();
                            // Nominatim returns array; Photon returns {features:[{geometry:{coordinates:[lng,lat]}, properties:{name}}]}
                            let arr = [];
                            if (Array.isArray(json)) {
                                arr = json;
                            } else if (Array.isArray(json?.data)) {
                                arr = json.data;
                            } else if (Array.isArray(json?.features) && json.features.length > 0) {
                                const f = json.features[0];
                                const coords = (f.geometry && Array.isArray(f.geometry.coordinates)) ? { lon: f.geometry.coordinates[0], lat: f.geometry.coordinates[1], display_name: f.properties?.name } : null;
                                if (coords) arr = [coords];
                            }
                            if (arr.length > 0) { result = arr[0]; break; }
                        } catch (e) {
                            lastError = e;
                        }
                    }

                    if (!result) {
                        throw (lastError || new Error('No results found for the provided address'));
                    }

                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);
                    document.getElementById('latitude').value = isNaN(lat) ? '' : lat.toFixed(6);
                    document.getElementById('longitude').value = isNaN(lon) ? '' : lon.toFixed(6);
                    const display = result.display_name || addressText || fallbackText;
                    if (addressEl && display) addressEl.value = display;
                    showToast('Success', 'Location found from the entered address', 'success');
                })()
                .catch(err => {
                    console.error('Geocoding error:', err);
                    showToast('Error', 'No results found for the provided address. Try adding area, city, and country (e.g., "Adenta - Accra, Ghana")', 'error');
                })
                .finally(() => {
                    this.disabled = false;
                    this.innerHTML = originalText;
                });
            });
        }
    });

    // Unified delete confirmation (static and dynamic rows)
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.classList && form.classList.contains('delete-customer-form')) {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            const customerName = (btn && btn.dataset.customerName) ? btn.dataset.customerName : 'this customer';
            const customerId = (btn && btn.dataset.customerId) ? btn.dataset.customerId : null;

            if (typeof Swal === 'undefined') {
                if (confirm(`Are you sure you want to delete ${customerName}?`)) {
                    form.submit();
                }
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete ${customerName}? This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit via AJAX for smoother UX
                    const submitBtn = btn;
                    const originalHtml = submitBtn ? submitBtn.innerHTML : '';
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
                    }

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': (form.querySelector('input[name="_token"]') || {}).value,
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams(new FormData(form)).toString()
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message || 'Customer deleted successfully',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            const row = form.closest('tr');
                            if (row) row.remove();
                            try {
                                window.dispatchEvent(new CustomEvent('customerDeleted', { detail: { id: customerId } }));
                            } catch (e) { console.warn('customerDeleted event dispatch failed', e); }
                        } else {
                            throw new Error((data && data.message) || 'Failed to delete customer');
                        }
                    })
                    .catch(err => {
                        console.error('Delete error:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: err.message || 'Failed to delete customer. Please try again.'
                        });
                    })
                    .finally(() => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalHtml || '<i class="ri-delete-bin-line"></i>';
                        }
                    });
                }
            });
        }
    });
</script>

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
    }
    .table {
        --bs-table-striped-bg: rgba(0, 0, 0, 0.02);
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        color: #6c757d;
        border-top: none;
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
    }
    
    /* Bulk Actions Bar */
    #bulkActionsBar {
        position: sticky;
        top: 0;
        z-index: 10;
        animation: slideDown 0.3s ease-in-out;
        border-left: 4px solid #405189;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Checkbox styling */
    .customer-checkbox, #selectAllCustomers {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .customer-checkbox:checked, #selectAllCustomers:checked {
        background-color: #405189;
        border-color: #405189;
    }
    
    /* Selected row highlight */
    tr:has(.customer-checkbox:checked) {
        background-color: rgba(64, 81, 137, 0.05);
    }
    .btn {
        font-weight: 500;
        white-space: nowrap;
    }
    .form-select, .form-control {
        border-radius: 0.375rem;
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .btn-outline-primary {
        color: #405189;
        border-color: #405189;
    }
    .btn-outline-primary:hover {
        background-color: #405189;
        color: #fff;
    }
    .btn-outline-secondary {
        color: #878a92;
        border-color: #878a92;
    }
    .btn-outline-secondary:hover {
        background-color: #878a92;
        color: #fff;
    }
    .btn-outline-info {
        color: #299cdb;
        border-color: #299cdb;
    }
    .btn-outline-info:hover {
        background-color: #299cdb;
        color: #fff;
    }
    .btn-outline-danger {
        color: #f46a6a;
        border-color: #f46a6a;
    }
    .btn-outline-danger:hover {
        background-color: #f46a6a;
        color: #fff;
    }
    
    /* Responsive adjustments */
    @media (max-width: 1399.98px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
    
    @media (max-width: 767.98px) {
        .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 1rem;
        }
        .text-muted {
            text-align: center;
            margin-bottom: 1rem;
        }
    }
    
    @media (max-width: 575.98px) {
        .table th, .table td {
            padding: 0.5rem;
            font-size: 0.875rem;
        }
        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        .modal-dialog {
            margin: 0.5rem;
        }
    }
</style>

@section('script')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize DataTable when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable for customers table
            if (document.getElementById('customersTable')) {
                $('#customersTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    order: [[0, 'desc']], // Sort by first column (MSISDN) descending
                    language: {
                        search: "Search customers:",
                        lengthMenu: "Show _MENU_ customers per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ customers",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        }
                    }
                });
            }
        });

        // Show success/error messages using SweetAlert2 without Blade conditionals inside JS
        if (window.flashSuccess) {
            const successMessage = window.flashSuccess;
            console.log('Success message detected:', successMessage);
            Swal.fire({ icon: 'success', title: 'Success!', text: successMessage, confirmButtonText: 'Ok' })
                .then(() => {
                    if (successMessage.toLowerCase().includes('assignments deleted')) {
                        const refreshBtn = document.getElementById('refreshAssignmentsBtn');
                        if (refreshBtn) {
                            refreshBtn.click();
                        }
                    }
                });
        }
        if (window.flashError) {
            console.log('Error message detected:', window.flashError);
            Swal.fire({ icon: 'error', title: 'Error!', text: window.flashError, confirmButtonText: 'Ok' });
        }

        // Handle form submissions with loading states
        const addCustomerForm = document.getElementById('addCustomerForm');
        if (addCustomerForm) {
            addCustomerForm.addEventListener('submit', function() {
                const hiddenField = document.getElementById('autoScheduleEnabledField');
                if (hiddenField) {
                    hiddenField.value = localStorage.getItem('autoScheduleEnabled') === 'true' ? 'true' : 'false';
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
                }
            });
        }

        // Reset form and button state when modal is hidden
        const addCustomerModal = document.getElementById('addCustomerModal');
        if (addCustomerModal) {
            addCustomerModal.addEventListener('hidden.bs.modal', function () {
                if (addCustomerForm) {
                    addCustomerForm.reset();
                }
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Add Customer';
                }
            });
        }

        // Function to confirm delete
        function confirmDelete(event, customerName) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: `Deleting ${customerName} will also remove any site assignments linked to them.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete customer and assignments'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>
@endsection
