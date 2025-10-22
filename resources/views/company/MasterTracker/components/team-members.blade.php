<!-- Team Members Tab Content -->
<div class="tab-pane fade show active" id="members" role="tabpanel" aria-labelledby="members-tab">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Team Members Management</h5>
        <div class="d-flex gap-2">

            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                <i class="fas fa-plus me-1"></i> Add Member
            </button>
            <button class="btn btn-secondary btn-sm" onclick="alert('Bulk upload feature coming soon!')">
                <i class="fas fa-upload me-1"></i> Bulk Upload
            </button>
            <button class="btn btn-success btn-sm" onclick="exportData('members')">
                <i class="fas fa-file-export me-1"></i> Export
            </button>
            <a href="{{ route('team-members.template.download') }}" class="btn btn-info btn-sm">
                <i class="fas fa-download me-1"></i> Template
            </a>
        </div>
    </div>

    <!-- Search and Filter for Members -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" id="searchMembers" class="form-control" placeholder="Search members..." style="height: 38px;">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="filterMemberStatus">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="on-leave">On Leave</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="filterMemberDepartment">
                <option value="">All Departments</option>
                @foreach($department_categories ?? [] as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-primary w-100" onclick="applyMemberFilters()">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-secondary w-100" onclick="clearMemberFilters()">
                <i class="fas fa-times me-1"></i> Clear
            </button>
        </div>
    </div>

    <!-- Members Table -->
    <div class="table-responsive">
        <table class="table table-centered table-hover dt-responsive nowrap w-100" id="members-datatable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Employee ID</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($team_members))
                    @forelse($team_members as $member)
                        <tr>
                            <td>{{ $member->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $member->full_name }}</h6>
                                        @if($member->employee_id)
                                            <small class="text-muted">{{ $member->employee_id }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $member->employee_id }}</td>
                            <td>{{ $member->position }}</td>
                            <td>{{ $member->department ? $member->department->name : 'N/A' }}</td>
                            <td>{{ $member->phone }}</td>
                            <td>{{ $member->email }}</td>
                            <td>
                                <span class="badge {{ $member->status === 'active' ? 'bg-success' : ($member->status === 'inactive' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ ucfirst(str_replace('-', ' ', $member->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-info action-btn" onclick="viewMember({{ $member->id }})" title="View" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary action-btn" onclick="editMember({{ $member->id }})" title="Edit" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger action-btn" onclick="deleteMember({{ $member->id }})" title="Delete" data-bs-toggle="tooltip">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No team members found</h5>
                                    <p class="text-muted mb-3">Get started by adding your first team member</p>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                        <i class="fas fa-plus me-1"></i> Add Team Member
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                @else
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Team members data not available</h5>
                                <p class="text-muted">Please refresh the page or contact support if the issue persists.</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
