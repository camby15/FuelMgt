@extends('layouts.vertical-admin', ['page_title' => 'Roles & Permissions'])
@push('styles')
<style>

    .module-header {
    cursor: pointer;
    transition: background-color 0.2s;
    border-radius: 0.25rem;
}

.module-header:hover {
    background-color: rgba(0, 0, 0, 0.03);
}

.module-header .form-check {
    margin-bottom: 0;
}

.module-toggle {
    pointer-events: none;
}

.toggle-icon {
    transition: transform 0.2s;
}

.card-header[aria-expanded="true"] .toggle-icon {
    transform: rotate(180deg);
}
    /* Table styling */
    #rolesTable {
        width: 100%;
    }

    /* Table header */
    #rolesTable thead th {
        font-weight: 600 !important;
        text-transform: uppercase !important;
        font-size: 0.7rem !important;
        letter-spacing: 0.5px !important;
        padding: 0.75rem 1rem !important;
        background-color: #f8f9fa !important;
        border-bottom-width: 1px !important;
    }
    
    /* Table body rows */
    #rolesTable tbody tr {
        height: 60px;
        transition: all 0.2s;
    }

    /* Table cells */
    #rolesTable tbody td {
        padding: 0.75rem 1rem !important;
        vertical-align: middle !important;
        font-size: 0.85rem !important;
        color: #495057;
        border-top: 1px solid #f1f3f7;
    }

    /* Row number styling */
    #rolesTable tbody td:first-child {
        color: #6c757d;
        font-size: 0.85rem;
        font-weight: 500;
        text-align: center;
        width: 50px;
    }

    /* Hover effect */
    #rolesTable tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    /* Badges */
    .badge {
        padding: 0.35em 0.65em !important;
        font-size: 0.7em !important;
        font-weight: 500 !important;
        line-height: 1.2 !important;
        border-radius: 0.25rem !important;
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    /* Button styles to match CRM */
    .btn-soft-info {
        color: #299cdb;
        background-color: rgba(41, 156, 219, 0.1);
        border-color: transparent;
    }

    .btn-soft-info:hover {
        color: #fff;
        background-color: #299cdb;
        border-color: #299cdb;
    }

    .btn-soft-danger {
        color: #f46a6a;
        background-color: rgba(244, 106, 106, 0.1);
        border-color: transparent;
    }

    .btn-soft-danger:hover {
        color: #fff;
        background-color: #f46a6a;
        border-color: #f46a6a;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    /* Hover effect for buttons */
    .btn-soft-info:hover i,
    .btn-soft-danger:hover i {
        color: #fff !important;
    }

    /* Status badges */
    .badge-status {
        padding: 0.4rem 0.75rem !important;
        font-weight: 500 !important;
        border-radius: 50rem !important;
        text-transform: capitalize;
    }

    /* User count link */
    .user-count {
        font-weight: 500;
        color: #3b76e1 !important;
        text-decoration: none;
        transition: color 0.2s;
    }

    .user-count:hover {
        color: #2c5db1 !important;
    }

    /* Date text */
    .date-text {
        font-size: 0.75rem !important;
        color: #6c757d !important;
        line-height: 1.4 !important;
        margin-bottom: 0.15rem;
    }

    /* Empty state */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
    }

    /* Pagination */
    .pagination {
        margin: 1.5rem 0 0 0 !important;
    }

    .page-link {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.8rem !important;
        border-color: #e9ecef !important;
        color: #3b76e1;
    }

    .page-item.active .page-link {
        background-color: #3b76e1 !important;
        border-color: #3b76e1 !important;
    }

    .page-item.disabled .page-link {
        color: #6c757d !important;
    }

    /* Card styling */
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Module header styles for permission toggling */
    .module-header {
        cursor: pointer;
        transition: background-color 0.2s;
        padding: 0.75rem 1.25rem;
        margin: 0 -1.25rem;
    }

    .module-header:hover {
        background-color: rgba(0, 0, 0, 0.03) !important;
    }

    .module-header .form-check {
        margin-bottom: 0;
        display: flex;
        align-items: center;
    }

    .module-toggle {
        margin-right: 0.5rem;
        pointer-events: none; /* Prevents the toggle from interfering with the header click */
    }

    .module-header .form-check-label {
        font-weight: 600;
        color: #3b76e1;
        cursor: pointer;
    }

    /* Permission checkboxes */
    .permission-checkbox {
        margin-right: 0.5rem;
    }

    /* Search and filter */
    #roleSearch, #moduleFilter, #statusFilter {
        border-radius: 0.375rem;
        border: 1px solid #e9ecef;
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            border: none;
        }
        
        #rolesTable thead {
            display: none;
        }
        
        #rolesTable tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            height: auto;
        }
        
        #rolesTable tbody td {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem !important;
            border: none;
            border-bottom: 1px solid #f1f3f7;
        }
        
        #rolesTable tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            margin-right: 1rem;
            color: #495057;
            flex: 0 0 120px;
        }
        
        #rolesTable tbody td:last-child {
            border-bottom: none;
        }

        /* Adjust module headers for mobile */
        .module-header {
            padding: 0.5rem 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="header-title">Roles & Permissions</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                            <i class="ri-add-line align-middle me-1"></i> Add New Role
                        </button>
                    </div>
                    
                    <!-- Search & Filter Bar -->
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" id="roleSearch" class="form-control border-start-0" placeholder="Search roles by name or module...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="moduleFilter" class="form-select">
                                <option value="">All Modules</option>
                                <option value="superusers">Superusers</option>
                                <option value="roles">Roles</option>
                                <option value="dashboard">Dashboard</option>
                                <option value="audit">Audit</option>
                                <option value="agent">Agent</option>
                                <option value="settings">Settings</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="statusFilter" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover" id="rolesTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Role</th>
                                    <th>Description</th>
                                    <th>Module Access</th>
                                    <th>Status</th>
                                    <th>Users</th>
                                    <th>Created/Modified</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $index => $role)
                                <tr data-role-id="{{ $role->id }}">
                                    <td class="text-muted">{{ $index + $roles->firstItem() }}</td>
                                    <td data-label="Role">
                                        <h6 class="mb-0">{{ $role->display_name ?? $role->name }}</h6>
                                        @if($role->description)
                                        <small class="text-muted" data-bs-toggle="tooltip" title="{{ $role->description }}">
                                            <i class="ri-information-line"></i> Details
                                        </small>
                                        @endif
                                    </td>
                                    <td data-label="Description">
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                              title="{{ $role->description ?? 'No description' }}">
                                            {{ $role->description ?? 'No description' }}
                                        </span>
                                    </td>
                                    <td data-label="Module Access">
                                        <div class="d-flex flex-wrap">
                                            @if($role->permissions->count() > 0)
                                                @foreach($role->permissions->take(3) as $permission)
                                                    <span class="badge bg-{{ ['primary', 'info', 'warning', 'success'][$loop->index % 4] }}">
                                                        {{ $permission->display_name }}
                                                    </span>
                                                @endforeach
                                                @if($role->permissions->count() > 3)
                                                    <span class="badge bg-secondary">+{{ $role->permissions->count() - 3 }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted small">No permissions</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td data-label="Status">
                                        <span class="badge bg-{{ $role->is_active ? 'success' : 'danger' }} badge-status">
                                            <i class="ri-{{ $role->is_active ? 'check' : 'close' }}-circle-fill me-1"></i>
                                            {{ $role->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td data-label="Users">
                                        <a href="#" class="user-count">
                                            <i class="ri-user-line me-1"></i> {{ $role->users_count ?? 0 }}
                                        </a>
                                    </td>
                                    <td data-label="Created/Modified">
                                        <div class="d-flex flex-column">
                                            <small class="date-text">
                                                <i class="ri-calendar-line me-1"></i> {{ $role->created_at->format('M d, Y') }}
                                            </small>
                                            <small class="date-text">
                                                <i class="ri-edit-line me-1"></i> {{ $role->updated_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-info btn-edit" 
                                                    data-id="{{ $role->id }}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Edit Role">
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-delete-role" 
                                                    data-id="{{ $role->id }}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Delete Role">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                    <i class="ri-group-line fs-24"></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-2">No roles found</h5>
                                            <p class="text-muted mb-4">Create your first role to get started</p>
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                                <i class="ri-add-line align-middle me-1"></i> Add New Role
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        @if($roles->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing <span class="fw-semibold">{{ $roles->firstItem() }}</span> to 
                                <span class="fw-semibold">{{ $roles->lastItem() }}</span> of 
                                <span class="fw-semibold">{{ $roles->total() }}</span> entries
                            </div>
                            <nav aria-label="Roles pagination">
                                <ul class="pagination pagination-sm mb-0">
                                    @if ($roles->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="ri-arrow-left-s-line"></i> Previous
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $roles->previousPageUrl() }}" rel="prev">
                                                <i class="ri-arrow-left-s-line"></i> Previous
                                            </a>
                                        </li>
                                    @endif

                                    @foreach ($roles->getUrlRange(1, $roles->lastPage()) as $page => $url)
                                        @if ($page == $roles->currentPage())
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    @if ($roles->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $roles->nextPageUrl() }}" rel="next">
                                                Next <i class="ri-arrow-right-s-line"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">Next <i class="ri-arrow-right-s-line"></i></span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addRoleForm" method="POST" action="{{ route('superadmin.roles.store') }}">
                @csrf
                <div class="modal-body">
                    <h6 class="border-bottom pb-2 mb-3">Role Info</h6>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="role_name" name="role_name" 
                               placeholder="Role Name" required minlength="3" maxlength="50"
                               pattern="[A-Za-z0-9\s]+" title="Only letters, numbers, and spaces are allowed">
                        <label for="role_name">Role Name</label>
                        <div class="invalid-feedback">Please provide a valid role name (3-50 characters, letters, numbers, and spaces only).</div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="description" name="description" 
                                 placeholder="Description" style="min-height: 100px;"
                                 maxlength="255"></textarea>
                        <label for="description">Description (Optional)</label>
                        <div class="form-text text-muted text-end"><span id="descriptionCounter">0</span>/255 characters</div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Module Permissions</h6>
                    <div class="mb-3">
                        <div class="alert alert-info py-2 mb-3">
                            <i class="ri-information-line me-1"></i> Click on module headers to toggle all permissions
                        </div>
                        <div class="row g-3">
                            @php
                                $modules = [
                                    'superusers' => 'Superusers Management',
                                    'roles' => 'Roles & Permissions',
                                    'dashboard' => 'Dashboard',
                                    'audit' => 'Audit Logs',
                                    'agent' => 'Agent Management',
                                    'settings' => 'System Settings'
                                ];
                                $crud = ['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete'];
                            @endphp
                            
                            @foreach($modules as $module => $displayName)
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header p-0">
                                        <div class="module-header d-flex align-items-center p-3" data-module="{{ $module }}">
                                            <div class="form-check form-switch mb-0 flex-grow-1">
                                                <input class="form-check-input module-toggle" type="checkbox" 
                                                       id="module_{{ $module }}" data-module="{{ $module }}">
                                                <label class="form-check-label fw-semibold" for="module_{{ $module }}">
                                                    {{ $displayName }}
                                                </label>
                                            </div>
                                            <i class="ri-arrow-down-s-line toggle-icon"></i>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex flex-wrap gap-3">
                                            @foreach($crud as $action => $actionLabel)
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" 
                                                       type="checkbox" 
                                                       name="permissions[{{ $module }}][]" 
                                                       value="{{ $action }}" 
                                                       id="perm_{{ $module }}_{{ $action }}"
                                                       data-module="{{ $module }}">
                                                <label class="form-check-label" for="perm_{{ $module }}_{{ $action }}">
                                                    {{ $actionLabel }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Status</h6>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                   id="statusToggle" name="status" value="active" checked>
                            <label class="form-check-label" for="statusToggle">
                                <span class="status-text">Active</span>
                            </label>
                        </div>
                        <small class="text-muted">Inactive roles cannot be assigned to users</small>
                    </div>
                    
                    <div id="addRoleError" class="alert alert-danger d-none mt-3 mb-0"></div>
                    <div id="addRoleSuccess" class="alert alert-success d-none mt-3 mb-0"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="addRoleSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="addRoleSpinner"></span>
                        <span class="btn-text">Add Role</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoleForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="roleId" name="id">
                <div class="modal-body">
                    <h6 class="border-bottom pb-2 mb-3">Role Info</h6>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="editRoleName" name="name" 
                               placeholder="Role Name" required minlength="3" maxlength="50"
                               pattern="[A-Za-z0-9\s]+" title="Only letters, numbers, and spaces are allowed">
                        <label for="editRoleName">Role Name</label>
                        <div class="invalid-feedback">Please provide a valid role name (3-50 characters, letters, numbers, and spaces only).</div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="editRoleDesc" name="description" 
                                 placeholder="Description" style="min-height: 100px;"
                                 maxlength="255"></textarea>
                        <label for="editRoleDesc">Description (Optional)</label>
                        <div class="form-text text-muted text-end"><span id="editDescriptionCounter">0</span>/255 characters</div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Module Permissions</h6>
                    <div class="mb-3">
                        <div class="alert alert-info py-2 mb-3">
                            <i class="ri-information-line me-1"></i> Select the permissions for this role
                        </div>
                        <div class="row g-3">
                            @php
                                $modules = [
                                    'superusers' => 'Superusers Management',
                                    'roles' => 'Roles & Permissions',
                                    'dashboard' => 'Dashboard',
                                    'audit' => 'Audit Logs',
                                    'agent' => 'Agent Management',
                                    'settings' => 'System Settings'
                                ];
                                $crud = ['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete'];
                            @endphp
                            
                            @foreach($modules as $module => $displayName)
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-light p-2">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input module-toggle" type="checkbox" 
                                                   id="edit_module_{{ $module }}" data-module="{{ $module }}">
                                            <label class="form-check-label fw-semibold" for="edit_module_{{ $module }}">
                                                {{ $displayName }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex flex-wrap gap-3">
                                            @foreach($crud as $action => $actionLabel)
                                            @php
                                                $permission = \App\Models\SuperAdmin\SuperPermission::where('name', $action . '_' . $module)->first();
                                            @endphp
                                            @if($permission)
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" 
                                                       type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}" 
                                                       id="edit_perm_{{ $module }}_{{ $action }}"
                                                       data-module="{{ $module }}"
                                                       data-permission-id="{{ $permission->id }}">
                                                <label class="form-check-label" for="edit_perm_{{ $module }}_{{ $action }}">
                                                    {{ $actionLabel }}
                                                </label>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Status</h6>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                   id="editStatusToggle" name="is_active" value="1">
                            <label class="form-check-label" for="editStatusToggle">
                                <span class="badge bg-success">Active</span>
                            </label>
                        </div>
                    </div>
                    
                    <div id="editRoleError" class="alert alert-danger d-none mt-2"></div>
                    <div id="editRoleSuccess" class="alert alert-success d-none mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="editRoleSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="editRoleSpinner"></span>
                        <i class="ri-save-line me-1"></i> Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
 <!-- Bootstrap JS Bundle (includes Popper) -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
 <!-- SweetAlert2 for alerts -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <!-- jQuery -->
 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@parent
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Toggle all permissions when clicking on module headers in modals
    document.addEventListener('click', function(e) {
        // Check if the click is on a module header or its children
        const header = e.target.closest('.module-header');
        if (!header) return;
        
        // Only proceed if we're in a modal
        const modal = header.closest('.modal');
        if (!modal) return;
        
        // Don't toggle if clicking directly on the checkbox or its label
        if (e.target.closest('.form-check-input') || e.target.closest('.form-check-label')) {
            return;
        }
        
        const module = header.getAttribute('data-module');
        if (!module) return;
        
        // Find all checkboxes for this module within the current modal
        const checkboxes = modal.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
        if (checkboxes.length === 0) return;
        
        // Check if all checkboxes are already checked
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        
        // Toggle all checkboxes in this module
        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
            // Trigger change event to update any dependent UI
            const event = new Event('change', { bubbles: true });
            checkbox.dispatchEvent(event);
        });
        
        // Update the module toggle state
        const moduleToggle = header.querySelector('.module-toggle');
        if (moduleToggle) {
            moduleToggle.checked = !allChecked;
            moduleToggle.indeterminate = false;
        }
        
        // Prevent any default behavior
        e.preventDefault();
        e.stopPropagation();
    });

    // Update module toggle when individual checkboxes change
    document.addEventListener('change', function(e) {
        const checkbox = e.target;
        if (!checkbox.classList.contains('permission-checkbox')) return;
        
        const module = checkbox.getAttribute('data-module');
        if (!module) return;
        
        const modal = checkbox.closest('.modal');
        const moduleCheckboxes = modal.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
        const moduleToggle = modal.querySelector(`.module-toggle[data-module="${module}"]`);
        
        if (!moduleToggle) return;
        
        const checkedCount = modal.querySelectorAll(`.permission-checkbox[data-module="${module}"]:checked`).length;
        
        if (checkedCount === 0) {
            // None checked
            moduleToggle.checked = false;
            moduleToggle.indeterminate = false;
        } else if (checkedCount === moduleCheckboxes.length) {
            // All checked
            moduleToggle.checked = true;
            moduleToggle.indeterminate = false;
        } else {
            // Some checked
            moduleToggle.checked = false;
            moduleToggle.indeterminate = true;
        }
    });

    // Toggle all permissions when clicking the module toggle switch
    document.addEventListener('change', function(e) {
        const toggle = e.target;
        if (!toggle.classList.contains('module-toggle')) return;
        
        const module = toggle.getAttribute('data-module');
        if (!module) return;
        
        const modal = toggle.closest('.modal');
        const checkboxes = modal.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = toggle.checked;
        });
    });

    // Add cursor pointer to module headers
    document.querySelectorAll('.module-header').forEach(header => {
        header.style.cursor = 'pointer';
    });

    // Search & Filter functionality (front-end only)
    document.getElementById('roleSearch')?.addEventListener('input', function() {
        filterRoles();
    });
    document.getElementById('moduleFilter')?.addEventListener('change', function() {
        filterRoles();
    });
    document.getElementById('statusFilter')?.addEventListener('change', function() {
        filterRoles();
    });
    function filterRoles() {
        let search = document.getElementById('roleSearch').value.toLowerCase();
        let module = document.getElementById('moduleFilter').value;
        let status = document.getElementById('statusFilter').value;
        let rows = document.querySelectorAll('#rolesTable tbody tr');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            let show = true;
            if (search && !text.includes(search)) show = false;
            if (module && !text.includes(module.toLowerCase())) show = false;
            if (status && !text.includes(status.toLowerCase())) show = false;
            row.style.display = show ? '' : 'none';
        });
    }

    /**
     * Handles Add Role Form Submission with confirmation and feedback using SweetAlert.
     */
    document.getElementById('addRoleForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const btn = document.getElementById('addRoleSubmitBtn');
        const spinner = document.getElementById('addRoleSpinner');
        
        // Disable button and show spinner
        btn.disabled = true;
        spinner?.classList.remove('d-none');
        
        try {
            console.log('Form submission started');
            
            // Get form elements
            const roleName = document.getElementById('role_name')?.value.trim() || '';
            const description = document.getElementById('description')?.value.trim() || '';
            const statusToggle = document.getElementById('statusToggle');
            const isActive = statusToggle?.checked ? 1 : 0;
            
            // Get selected permissions
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox:checked');
            const permissions = Array.from(permissionCheckboxes).map(checkbox => {
                const module = checkbox.dataset.module;
                const action = checkbox.value;
                return `${module}.${action}`;
            });

            // Basic client-side validation
            if (!roleName) {
                throw new Error('Role name is required');
            }

            // Create FormData object
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
            formData.append('role_name', roleName);
            formData.append('display_name', roleName); // Required by some systems
            formData.append('description', description);
            formData.append('is_active', isActive);
            
            // Add permissions
            permissions.forEach(permission => {
                formData.append('permissions[]', permission);
            });

            console.log('Sending data to server:', {
                role_name: roleName,
                description,
                is_active: isActive,
                permissions
            });
            
            // Make API request
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const responseData = await response.json().catch(() => ({}));
            
            if (!response.ok) {
                // Handle validation errors
                let errorMessage = 'Failed to add role';
                if (responseData.errors) {
                    errorMessage = Object.entries(responseData.errors)
                        .map(([field, messages]) => {
                            // Format field name (e.g., "role_name" -> "Role name")
                            const formattedField = field.split('_')
                                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                                .join(' ');
                            return `<strong>${formattedField}:</strong> ${Array.isArray(messages) ? messages.join(' ') : messages}`;
                        })
                        .join('<br>');
                } else if (responseData.message) {
                    errorMessage = responseData.message;
                }
                throw new Error(errorMessage);
            }
            
            // Show success message
            await Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: responseData.message || 'Role added successfully!',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
            
            // Reset form and UI
            form.reset();
            const descriptionCounter = document.getElementById('descriptionCounter');
            if (descriptionCounter) descriptionCounter.textContent = '0';
            
            // Uncheck all permission checkboxes
            document.querySelectorAll('.permission-checkbox:checked').forEach(cb => cb.checked = false);
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addRoleModal'));
            if (modal) modal.hide();
            
            // Reload the roles table
            if (window.rolesTable && typeof window.rolesTable.ajax?.reload === 'function') {
                window.rolesTable.ajax.reload(null, false);
            } else {
                location.reload(); // Fallback to page reload
            }
            
        } catch (error) {
            console.error('Error in form submission:', error);
            
            // Show error message with better formatting
            await Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: `
                    <div style="text-align: left; max-height: 70vh; overflow-y: auto;">
                        <p>${error.message || 'An error occurred while adding the role.'}</p>
                        ${process.env.APP_DEBUG === 'true' ? `
                        <details style="margin-top: 10px;">
                            <summary style="cursor: pointer; color: #3085d6; font-size: 0.9em;">Show technical details</summary>
                            <pre style="background: #f8f9fa; padding: 10px; border-radius: 4px; margin-top: 5px; overflow-x: auto; font-size: 0.8em; max-height: 200px;">
    ${error.stack || JSON.stringify(error, null, 2)}
                            </pre>
                        </details>
                        ` : ''}
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
                allowOutsideClick: false,
                width: '600px'
            });
        } finally {
            // Re-enable button and hide spinner
            if (btn) btn.disabled = false;
            if (spinner) spinner.classList.add('d-none');
        }
    });

    // Character counter for description
    document.getElementById('description')?.addEventListener('input', function() {
        const counter = document.getElementById('descriptionCounter');
        if (counter) {
            counter.textContent = this.value.length;
        }
    });

    // Module toggle functionality
    document.querySelectorAll('.module-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const module = this.dataset.module;
            const checkboxes = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
            checkboxes.forEach(checkbox => {  // Fixed variable name from 'check' to 'checkboxes'
                checkbox.checked = this.checked;
                // Trigger change event to update parent state
                const event = new Event('change');
                checkbox.dispatchEvent(event);
            });
        });
    });

    // Individual permission checkbox handler
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckbox = document.querySelector(`#module_${module}`);
            if (!moduleCheckbox) return;
            
            const moduleCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
            const checkedCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]:checked`);
            
            // Update module checkbox state
            if (checkedCheckboxes.length === 0) {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = false;
            } else if (checkedCheckboxes.length === moduleCheckboxes.length) {
                moduleCheckbox.checked = true;
                moduleCheckbox.indeterminate = false;
            } else {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = true;
            }
        });
    });

    /**
     * Handles edit with confirmation and feedback using SweetAlert.
     */
    // Handle edit button click
    document.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.btn-edit');
        if (!editBtn) return;
        
        e.preventDefault();
        
        const roleId = editBtn.dataset.id;
        const roleRow = editBtn.closest('tr');
        
        if (!roleId || !roleRow) return;
        
        // Get role data from the row
        const roleName = roleRow.querySelector('h6').textContent.trim();
        const roleDesc = roleRow.querySelector('small[data-bs-toggle="tooltip"]')?.getAttribute('title') || '';
        const isActive = roleRow.querySelector('.badge.bg-success') !== null;
        
        // Get all permissions for this role from the data attribute
        const permissionsData = roleRow.getAttribute('data-permissions');
        const permissions = permissionsData ? JSON.parse(permissionsData) : [];
        
        // Set form values
        const editForm = document.getElementById('editRoleForm');
        editForm.setAttribute('data-role-id', roleId);
        
        // Set role name and description
        document.getElementById('editRoleName').value = roleName;
        document.getElementById('editRoleDesc').value = roleDesc;
        
        // Update the description counter
        const descCounter = document.getElementById('editDescriptionCounter');
        if (descCounter) {
            descCounter.textContent = roleDesc.length;
        }
        
        // Set status toggle
        const statusToggle = document.getElementById('editStatusToggle');
        if (statusToggle) {
            statusToggle.checked = isActive;
            // Update the status badge
            const statusBadge = statusToggle.nextElementSibling?.querySelector('.badge');
            if (statusBadge) {
                statusBadge.className = `badge bg-${isActive ? 'success' : 'danger'}`;
                statusBadge.textContent = isActive ? 'Active' : 'Inactive';
            }
        }
        
        // Reset all permission checkboxes
        document.querySelectorAll('#editRoleForm .permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
            checkbox.indeterminate = false;
        });
        
        // Reset all module toggles
        document.querySelectorAll('#editRoleForm .module-toggle').forEach(toggle => {
            toggle.checked = false;
            toggle.indeterminate = false;
        });
        
        // Check the appropriate permission checkboxes based on permission IDs
        const moduleStates = {};
        
        // Convert permission IDs to an array if it's a string
        const permissionIds = Array.isArray(permissions) ? permissions : [permissions];
        
        permissionIds.forEach(permissionId => {
            // Find the checkbox with this permission ID
            const checkbox = document.querySelector(`#editRoleForm .permission-checkbox[data-permission-id="${permissionId}"]`);
            if (checkbox) {
                checkbox.checked = true;
                
                // Get module from the checkbox's data attribute
                const module = checkbox.dataset.module;
                
                // Track which modules have permissions
                if (!moduleStates[module]) {
                    moduleStates[module] = [];
                }
                moduleStates[module].push(checkbox.value);
            }
        });
        
        // Update module toggles based on checked permissions
        Object.entries(moduleStates).forEach(([module, actions]) => {
            const moduleToggle = document.querySelector(`#editRoleForm #edit_module_${module}`);
            if (!moduleToggle) return;
            
            const allModulePermissions = document.querySelectorAll(`#editRoleForm .permission-checkbox[data-module="${module}"]`);
            const allModulePermissionActions = Array.from(allModulePermissions).map(cb => cb.value);
            
            // Check if all permissions for this module are selected
            const allSelected = allModulePermissionActions.every(action => 
                actions.includes(action)
            );
            
            if (allSelected) {
                moduleToggle.checked = true;
                moduleToggle.indeterminate = false;
            } else if (actions.length > 0) {
                moduleToggle.checked = false;
                moduleToggle.indeterminate = true;
            }
        });
        
        // Show the modal
        const editModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
        editModal.show();
    });

    // Helper function to setup modal reload on hide
    function setupModalReloadOnHide(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function () {
                // Small delay to ensure modal is fully hidden
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            });
        }
    }

    // Call this when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Other initialization code...
        setupModalReloadOnHide('editRoleModal');
    });

    // Handle Edit Form Submission
    document.getElementById('editRoleForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const roleId = form.getAttribute('data-role-id');
        const submitBtn = document.getElementById('editRoleSubmitBtn');
        const spinner = document.getElementById('editRoleSpinner');
        
        // Show loading state
        submitBtn.disabled = true;
        spinner?.classList.remove('d-none');
        
        try {
            // Collect form data
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('name', document.getElementById('editRoleName').value.trim());
            formData.append('description', document.getElementById('editRoleDesc').value.trim());
            formData.append('is_active', document.getElementById('editStatusToggle').checked ? '1' : '0');
            
            // Collect permission IDs from checked checkboxes
            const permissionCheckboxes = document.querySelectorAll('#editRoleForm .permission-checkbox:checked');
            permissionCheckboxes.forEach(checkbox => {
                const permissionId = checkbox.dataset.permissionId;
                if (permissionId) {
                    formData.append('permissions[]', permissionId);
                }
            });
            
            // Show loading state
            const swalInstance = Swal.fire({
                title: 'Updating Role...',
                text: 'Please wait while we update the role',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send the update request
            const response = await fetch(`/superadmin/roles/${roleId}`, {
                method: 'POST', // Using POST with _method=PUT for Laravel
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Failed to update role');
            }
            
            // Close loading state
            await swalInstance.close();
            
            // Show success message
            await Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Role updated successfully!',
                showConfirmButton: false,
                timer: 1500
            });
            
            
            // Update the role in the table
            const roleRow = document.querySelector(`tr[data-role-id="${roleId}"]`);
            if (roleRow) {
                // Update the role name
                const roleNameCell = roleRow.querySelector('h6');
                if (roleNameCell) {
                    roleNameCell.textContent = document.getElementById('editRoleName').value.trim();
                }
                
                // Update the description
                const descTooltip = roleRow.querySelector('small[data-bs-toggle="tooltip"]');
                if (descTooltip) {
                    descTooltip.setAttribute('title', document.getElementById('editRoleDesc').value.trim() || '');
                }
                
                // Update the status badge
                const statusBadge = roleRow.querySelector('.badge-status');
                if (statusBadge) {
                    const isActive = document.getElementById('editStatusToggle').checked;
                    statusBadge.className = `badge bg-${isActive ? 'success' : 'danger'}`;
                    statusBadge.innerHTML = `<i class="ri-${isActive ? 'check' : 'close'}-circle-fill me-1"></i>${isActive ? 'Active' : 'Inactive'}`;
                }
                
                // Update the permissions in the table
                const permissionsCell = roleRow.querySelector('td[data-label="Permissions"]');
                if (permissionsCell) {
                    const permissions = Array.from(permissionCheckboxes).map(checkbox => {
                        const module = checkbox.dataset.module;
                        const action = checkbox.value;
                        return `<span class="badge bg-soft-primary text-primary">${action} ${module.replace('_', ' ')}</span>`;
                    });
                    
                    permissionsCell.innerHTML = permissions.length > 0 ? permissions.join(' ') : '-';
                }
            }
            
            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editRoleModal'));
            if (modal) modal.hide();
            
        } catch (error) {
            console.error('Error updating role:', error);
            
            // Show error message
            await Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Failed to update role. Please try again.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b76e1'
            });
        } finally {
            // Reset button state
            submitBtn.disabled = false;
            spinner?.classList.add('d-none');
        }
    });

    console.log('Custom script loaded');
    console.log('Swal is', typeof Swal !== 'undefined' ? 'defined' : 'undefined');
    

    /**
     * Handles role deletion with confirmation and feedback using SweetAlert.
     */
    document.addEventListener('click', async function(e) {
        const deleteBtn = e.target.closest('.btn-delete-role');
        if (!deleteBtn) return;
        e.preventDefault();
        
        const roleId = deleteBtn.getAttribute('data-id');
        const row = deleteBtn.closest('tr');
        
        // Get the role name from the second column (index 1)
        const roleName = row.querySelector('td:nth-child(2)').textContent.trim();
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found');
            return;
        }
        
        try {
            // Confirm deletion with SweetAlert
            const result = await Swal.fire({
                title: 'Delete Role',
                html: `Are you sure you want to delete <strong>${roleName}</strong>?<br><small class="text-danger">This action cannot be undone.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading()
            });
            if (!result.isConfirmed) return;

            // Show loading state
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // Send AJAX request to delete role
            const response = await fetch(`/superadmin/roles/${roleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || 'Failed to delete role');
            }
            
            Swal.close();

            // Remove row from table and show empty state if needed
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    const tbody = document.querySelector('#rolesTable tbody');
                    if (tbody && tbody.children.length === 0) {
                        const emptyRow = `
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="avatar-lg mb-3">
                                            <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                <i class="ri-group-line fs-24"></i>
                                            </div>
                                        </div>
                                        <h5>No roles found</h5>
                                        <p class="text-muted">Create your first role to get started</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                            <i class="ri-add-line align-middle me-1"></i> Add New Role
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
                        tbody.innerHTML = emptyRow;
                    }
                }, 300);
            }

            // Success notification
            await Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: `Role "${roleName}" has been deleted successfully.`,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b76e1',
                timer: null
            });
        } catch (error) {
            console.error('Delete error:', error);
            await Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Failed to delete role. Please try again.',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b76e1',
                timer: null
            });
        }
    });
</script>
@endsection
