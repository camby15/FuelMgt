@extends('layouts.vertical-admin', ['page_title' => 'Manage Users'])
@section('head')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (optional, for icons) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Custom styles for user table and modals */
        .user-profile-link img {
            border: 2px solid #dee2e6;
            transition: box-shadow 0.2s;
        }
        .user-profile-link:hover img {
            box-shadow: 0 0 0 2px #0d6efd;
        }
        .modal .form-floating > label {
            left: 0.75rem;
        }
        .modal .form-select {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }
        /* Toggle Switch Styles */
        .form-check-input:checked {
            background-color: #198754; /* Bootstrap success color */
            border-color: #198754;
        }

        /* For dark mode support */
        [data-bs-theme="dark"] .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }
    </style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Users Management</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($users) && $users->count() > 0)
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <a href="#" class="user-profile-link d-flex align-items-center" 
                                               data-bs-toggle="modal" 
                                               data-bs-target="#userProfileModal"
                                               data-name="{{ $user->name }}"
                                               data-email="{{ $user->email }}"
                                               data-role="{{ $user->role }}"
                                               data-status="{{ $user->status }}"
                                               data-avatar="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/users/avatar-1.jpg') }}"
                                               data-lastlogin="{{ $user->last_login_at }}">
                                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/users/avatar-1.jpg') }}" 
                                                     alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                                {{ $user->name }}
                                            </a>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <select class="form-select form-select-sm user-role-select" data-id="{{ $user->id }}">
                                                <option value="Admin" {{ $user->role === 'Admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="Manager" {{ $user->role === 'Manager' ? 'selected' : '' }}>Manager</option>
                                                <option value="User" {{ $user->role === 'User' ? 'selected' : '' }}>User</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input user-status-toggle {{ $user->status === 'Active' ? 'bg-success' : '' }}" 
                                                       type="checkbox" 
                                                       {{ $user->status === 'Active' ? 'checked' : '' }}
                                                       data-id="{{ $user->id }}">
                                                <label class="form-check-label">{{ $user->status }}</label>
                                            </div>
                                        </td>
                                        <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d') : 'Never' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info btn-edit-user" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editUserModal"
                                                    data-id="{{ $user->id }}">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                <span class="btn-text">Edit</span>
                                            </button>
                                            <button class="btn btn-sm btn-secondary btn-user-activity">Activity</button>
                                            <button class="btn btn-sm btn-danger btn-delete-user" data-id="{{ $user->id }}">Delete</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No users found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <!-- Add pagination container -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted" id="pagination-info">
                                Showing <span id="pagination-from">0</span> to <span id="pagination-to">0</span> of <span id="pagination-total">0</span> entries
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination mb-0" id="pagination-links">
                                    <!-- Pagination links will be inserted here by JavaScript -->
                                </ul>
                            </nav>
                    </div>
                    <div class="mt-3 d-flex flex-wrap gap-2">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
                        <button class="btn btn-outline-secondary btn-sm" id="inviteUserBtn">Invite User</button>
                        <button class="btn btn-secondary btn-sm" id="importUsersBtn">Import Users</button>
                        <button class="btn btn-success btn-sm" id="exportUsersBtn">Export Users</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="addUserErrors" class="alert alert-danger d-none"></div>
                <form id="addUserForm" method="POST" action="{{ route('superadmin.users.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="addUserName" name="name" placeholder="Name" required>
                        <label for="addUserName">Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="addUserEmail" name="email" placeholder="Email" required>
                        <label for="addUserEmail">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="addUserPassword" name="password" placeholder="Password" required>
                        <label for="addUserPassword">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="addUserPasswordConfirmation" name="password_confirmation" placeholder="Confirm Password" required>
                        <label for="addUserPasswordConfirmation">Confirm Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="addUserRole" name="role" required>
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Manager">Manager</option>
                            <option value="User">User</option>
                        </select>
                        <label for="addUserRole">Role</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="addUserStatus" name="status" required>
                            <option value="">Select Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <label for="addUserStatus">Status</label>
                    </div>
                    <div class="mb-3">
                        <label for="addUserAvatar" class="form-label">Avatar (Optional)</label>
                        <input class="form-control" type="file" id="addUserAvatar" name="avatar" accept="image/*">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="editUserErrors" class="alert alert-danger d-none"></div>
                <form id="editUserForm" method="POST" action="{{ route('superadmin.users.update', ['user' => '__USER_ID__']) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="edit_user_id">
                    
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="Admin">Admin</option>
                            <option value="Manager">Manager</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_avatar" class="form-label">Profile Picture</label>
                        <input class="form-control" type="file" id="edit_avatar" name="avatar" accept="image/*">
                        <small class="text-muted">Leave blank to keep current image</small>
                        <div class="mt-2">
                            <img id="edit_avatar_preview" src="" alt="Avatar Preview" class="img-thumbnail" style="max-width: 100px; display: none;">
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateUserBtn">
                            <span id="updateUserBtnText">Update User</span>
                            <span id="updateUserSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- User Profile Quick View Modal -->
<div class="modal fade" id="userProfileModal" tabindex="-1" aria-labelledby="userProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userProfileModalLabel">User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userProfileContent">
                <!-- Profile content will be injected via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Import Users Modal -->
<div class="modal fade" id="importUsersModal" tabindex="-1" aria-labelledby="importUsersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importUsersModalLabel">Import Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importUsersForm" action="{{ route('superadmin.users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="importFile" class="form-label">Choose CSV File</label>
                        <input class="form-control" type="file" id="importFile" name="import_file" accept=".csv" required>
                        <div class="form-text">Please upload a CSV file with user data. <a href="{{ route('superadmin.users.download-template') }}">Download template</a></div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> The CSV file should include the following columns: Name, Email, Role, Status (Active/Inactive)
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="confirmImportBtn">
                        <i class="fas fa-file-import me-1"></i> Import Users
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Users Modal -->
<div class="modal fade" id="exportUsersModal" tabindex="-1" aria-labelledby="exportUsersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="exportUsersForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportUsersModalLabel">Export Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exportFormat" class="form-label">Export Format</label>
                        <select class="form-select" id="exportFormat" name="format" required>
                            <option value="csv">CSV (Comma Separated Values)</option>
                            <option value="excel">Excel (.xlsx)</option>
                        </select>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="exportStartDate" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="exportStartDate" name="start_date">
                        </div>
                        <div class="col-md-6">
                            <label for="exportEndDate" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="exportEndDate" name="end_date">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="exportStatus" class="form-label">Status</label>
                        <select class="form-select" id="exportStatus" name="status">
                            <option value="">All Statuses</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    
                    <div id="exportError" class="alert alert-danger d-none mb-3"></div>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i> 
                        The export will include all users matching the selected criteria.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="confirmExportBtn">
                        <span class="spinner-border spinner-border-sm d-none me-1" id="exportSpinner" role="status" aria-hidden="true"></span>
                        <span id="exportBtnText"><i class="fas fa-file-export me-1"></i> Export</span>
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
    @parent
    <script>
        // Function to refresh the users table cb88
        function refreshUsersTable(page = 1) {
            fetch('{{ route("superadmin.users.index") }}?page=' + page, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Get the tbody element
                    const tbody = document.querySelector('#usersTable tbody');
                    
                    // Clear existing rows
                    tbody.innerHTML = '';
                    
                    // Add new rows
                    data.data.forEach(user => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>
                                <a href="#" class="user-profile-link d-flex align-items-center" 
                                data-bs-toggle="modal" 
                                data-bs-target="#userProfileModal"
                                data-name="${user.name}"
                                data-email="${user.email}"
                                data-role="${user.role}"
                                data-status="${user.status}"
                                data-avatar="${user.avatar ? '{{ asset('storage/') }}/' + user.avatar : '{{ asset('assets/images/users/avatar-1.jpg') }}'}"
                                data-lastlogin="${user.last_login_at}">
                                    <img src="${user.avatar ? '{{ asset('storage/') }}/' + user.avatar : '{{ asset('assets/images/users/avatar-1.jpg') }}'}" 
                                        alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                    ${user.name}
                                </a>
                            </td>
                            <td>${user.email}</td>
                            <td>
                                <select class="form-select form-select-sm user-role-select" data-id="${user.id}">
                                    <option value="Admin" ${user.role === 'Admin' ? 'selected' : ''}>Admin</option>
                                    <option value="Manager" ${user.role === 'Manager' ? 'selected' : ''}>Manager</option>
                                    <option value="User" ${user.role === 'User' || !user.role ? 'selected' : ''}>User</option>
                                </select>
                            </td>
                            <td>
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input user-status-toggle ${user.status === 'Active' ? 'bg-success' : ''}" 
                                        type="checkbox" 
                                        ${user.status === 'Active' ? 'checked' : ''}
                                        data-id="${user.id}">
                                    <label class="form-check-label">${user.status || 'Inactive'}</label>
                                </div>
                            </td>
                            <td>${user.last_login_at ? user.last_login_at : 'Never'}</td>
                            <td>
                                <button class="btn btn-sm btn-info btn-edit-user" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editUserModal"
                                        data-id="${user.id}">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <span class="btn-text">Edit</span>
                                </button>
                                <button class="btn btn-sm btn-secondary btn-user-activity">Activity</button>
                                <button class="btn btn-sm btn-danger btn-delete-user" data-id="${user.id}">Delete</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                    
                    // Reinitialize event listeners for new elements
                    initializeEventListeners();

                    // Update pagination info
                    const Pagination = data.pagination;
                    document.getElementById('pagination-from').textContent = Pagination.from || 0;
                    document.getElementById('pagination-to').textContent = Pagination.to || 0;
                    document.getElementById('pagination-total').textContent = Pagination.total || 0;

                    // Update pagination links
                    const paginationLinks = document.getElementById('pagination-links');
                    paginationLinks.innerHTML = '';
                    
                    // Previous button
                    if (Pagination.prev_page_url) {
                        const prevLi = document.createElement('li');
                        prevLi.className = 'page-item';
                        prevLi.innerHTML = `
                            <a class="page-link" href="#" data-page="${Pagination.current_page - 1}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        `;
                        paginationLinks.appendChild(prevLi);
                    } 

                    // Page numbers
                    for (let i = 1; i <= Pagination.last_page; i++) {
                        const li = document.createElement('li');
                        li.className = `page-item ${i === Pagination.current_page ? 'active' : ''}`;
                        li.innerHTML = `
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        `;
                        paginationLinks.appendChild(li);
                    }

                    // Next button
                    if (Pagination.next_page_url) {
                        const nextLi = document.createElement('li');
                        nextLi.className = 'page-item';
                        nextLi.innerHTML = `
                            <a class="page-link" href="#" data-page="${Pagination.current_page + 1}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        `;
                        paginationLinks.appendChild(nextLi);
                    }
                }
            })
            .catch(error => {
                console.error('Error refreshing table:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to refresh users list: ' + error.message
                });
            });
        }

        // Add the new code RIGHT HERE cb88
        document.addEventListener('click', function(e) {
            if (e.target.closest('.page-link')) {
                e.preventDefault();
                const page = e.target.closest('.page-link').getAttribute('data-page');
                if (page) {
                    refreshUsersTable(page);
                    // Scroll to top of the table
                    document.querySelector('.card').scrollIntoView({ behavior: 'smooth' });
                }
            }
        });

        // Initialize event listeners
        function initializeEventListeners() {
            // Status toggle
            document.querySelectorAll('.user-status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const label = this.closest('td').querySelector('.form-check-label');
                    const status = this.checked ? 'Active' : 'Inactive';
                    label.textContent = status;
                    
                    // Update status via API
                    const userId = this.dataset.id;
                    updateUserStatus(userId, { status: status });
                });
            });

            // Role change
            document.querySelectorAll('.user-role-select').forEach(select => {
                select.addEventListener('change', function() {
                    const userId = this.dataset.id;
                    updateUserRole(userId, { role: this.value });
                });
            });

            // Delete user
            document.querySelectorAll('.btn-delete-user').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const userId = this.dataset.id;
                    const currentPage = document.querySelector('.pagination .page-item.active')?.textContent || 1;
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete user!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteUser(userId, parseInt(currentPage));
                        }
                    });
                });
            });
        }

        

        // Helper function to update user status
        function updateUserStatus(userId, data) {
            const toggle = document.querySelector(`.user-status-toggle[data-id="${userId}"]`);
            const label = toggle.closest('td').querySelector('.form-check-label');
            const originalStatus = data.status === 'Active' ? 'Inactive' : 'Active';
            
            // Update UI immediately for better UX
            label.textContent = data.status;
            if (data.status === 'Active') {
                toggle.checked = true;
                toggle.classList.add('bg-success');
            } else {
                toggle.checked = false;
                toggle.classList.remove('bg-success');
            }

            fetch(`/superadmin/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(async response => {
                const responseData = await response.json();
                if (!response.ok) {
                    throw new Error(responseData.message || 'Failed to update status');
                }
                return responseData;
            })
            .catch(error => {
                console.error('Full error:', error);
                // Revert UI on error
                label.textContent = originalStatus;
                toggle.checked = !toggle.checked;
                if (originalStatus === 'Active') {
                    toggle.classList.add('bg-success');
                } else {
                    toggle.classList.remove('bg-success');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to update user status'
                });
            });
        }

        // Helper function to update user role
        function updateUserRole(userId, data) {
            fetch(`/superadmin/users/${userId}/update-role`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User role updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message || 'Failed to update user role');
                }
            })
            .catch(error => {
                console.error('Error updating user role:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to update user role'
                });
            });
        }

        // Helper function to delete user
        function deleteUser(userId, currentPage = 1) {
            fetch(`/superadmin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'The user has been deleted.', 'success');
                    
                    // Get the current pagination state
                    const totalItems = parseInt(document.getElementById('pagination-total').textContent) || 0;
                    const itemsPerPage = 10; // Adjust if your per-page value is different
                    const currentPage = document.querySelector('.pagination .page-item.active')?.textContent || 1;
                    const itemsOnPage = document.querySelectorAll('#usersTable tbody tr').length;
                    
                    // If this was the last item on the page and we're not on the first page, go to previous page
                    if (itemsOnPage <= 1 && currentPage > 1) {
                        refreshUsersTable(parseInt(currentPage) - 1);
                    } else {
                        // Otherwise refresh current page
                        refreshUsersTable(parseInt(currentPage));
                    }
                } else {
                    throw new Error(data.message || 'Failed to delete user');
                }
            })
            .catch(error => {
                console.error('Error deleting user:', error);
                Swal.fire('Error', error.message || 'Failed to delete user', 'error');
            });
        }

        // Add user form submission
        document.addEventListener('DOMContentLoaded', function() {
            const addUserForm = document.getElementById('addUserForm');
            if (!addUserForm) return;

            // Add helper function for cleanup
            function cleanupModal() {
                // Remove all modal backdrops
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                
                // Remove modal-open class and reset body styles
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }

            addUserForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const errorsDiv = document.getElementById('addUserErrors');
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...';
                
                // Clear previous errors
                if (errorsDiv) {
                    errorsDiv.classList.add('d-none');
                    errorsDiv.innerHTML = '';
                }

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw data;
                    }

                    if (data.success) {
                        // Close the modal first
                        if (modal) {
                            // Hide the modal
                            const modalElement = document.getElementById('addUserModal');
                            
                            // Add event listener for when the modal is fully hidden
                            const handleHidden = () => {
                                modalElement.removeEventListener('hidden.bs.modal', handleHidden);
                                cleanupModal();
                            };
                            
                            modalElement.addEventListener('hidden.bs.modal', handleHidden);
                            modal.hide();
                        } else {
                            cleanupModal();
                        }
                        
                        // Reset the form
                        addUserForm.reset();
                        
                        // Show success message
                        await Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'User created successfully',
                            confirmButtonText: 'OK'
                        });
                        
                        // Refresh the users table
                        refreshUsersTable();
                    } else {
                        throw new Error(data.message || 'Failed to create user');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    
                    // Handle validation errors
                    if (error.errors) {
                        let errorHtml = '<ul class="mb-0">';
                        Object.entries(error.errors).forEach(([field, messages]) => {
                            const fieldName = field.replace(/\.\d+\./g, '.'); // Handle array field names
                            const message = Array.isArray(messages) ? messages[0] : messages;
                            errorHtml += `<li>${fieldName}: ${message}</li>`;
                        });
                        errorHtml += '</ul>';
                        
                        if (errorsDiv) {
                            errorsDiv.innerHTML = errorHtml;
                            errorsDiv.classList.remove('d-none');
                            // Scroll to errors
                            errorsDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'An error occurred while creating the user'
                        });
                    }
                } finally {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });

            // Initialize the table on page load
            refreshUsersTable();
        });

        // Edit User Modal
        // Edit User cb88Modal
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-edit-user')) {
                e.preventDefault();
                const button = e.target.closest('.btn-edit-user');
                const userId = button.dataset.id;
                
                // Show loading state
                const spinner = button.querySelector('.spinner-border');
                const buttonText = button.querySelector('.btn-text');
                button.disabled = true;
                if (spinner) spinner.classList.remove('d-none');
                if (buttonText) buttonText.textContent = 'Loading...';
                
                // Fetch user data
                fetch(`/superadmin/users/${userId}/edit`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(response => {
                    if (!response.success) {
                        throw new Error(response.message || 'Failed to load user data');
                    }
                    
                    const user = response.data;
                    
                    // Populate the form
                    document.getElementById('edit_user_id').value = user.id;
                    document.getElementById('edit_name').value = user.name;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_role').value = user.role;
                    
                    // Set avatar preview cb88if exists
                    const avatarPreview = document.getElementById('edit_avatar_preview');
                    if (user.avatar) {
                        avatarPreview.src = user.avatar.startsWith('http') ? user.avatar : `/storage/${user.avatar}`;
                        avatarPreview.style.display = 'block';
                    } else {
                        avatarPreview.style.display = 'none';
                    }
                    
                    // Show the modal
                    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                    editModal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', error.message || 'Failed to load user data. Please try again.', 'error');
                })
                .finally(() => {
                    button.disabled = false;
                    if (spinner) spinner.classList.add('d-none');
                    if (buttonText) buttonText.textContent = 'Edit';
                });
            }
        });

        // Handle Edit Form Submission
        document.getElementById('editUserForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const userId = formData.get('user_id');
            const submitBtn = form.querySelector('button[type="submit"]');
            const spinner = form.querySelector('#updateUserSpinner');
            const btnText = form.querySelector('#updateUserBtnText');
            const errorDiv = document.getElementById('editUserErrors');
            const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
            
            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            btnText.textContent = 'Updating...';
            
            // Clear previous errors
            errorDiv.classList.add('d-none');
            errorDiv.innerHTML = '';
            
            // Add _method for Laravel to recognize as PUT
            formData.append('_method', 'PUT');
            
            // Submit the form
            fetch(`/superadmin/users/${userId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        console.error('Server error:', err);
                        throw err;
                    });
                }
                return response.json();
            })
            .then(async (data) => {
                if (data.success) {
                    // Close the modal first
                    if (modal) {
                        // Hide the modal
                        const modalElement = document.getElementById('editUserModal');
                        
                        // Add event listener for when the modal is fully hidden
                        const handleHidden = () => {
                            modalElement.removeEventListener('hidden.bs.modal', handleHidden);
                            cleanupModal();
                        };
                        
                        modalElement.addEventListener('hidden.bs.modal', handleHidden);
                        modal.hide();
                    } else {
                        cleanupModal();
                    }
                    
                    // Show success message
                    await Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'User updated successfully',
                        confirmButtonText: 'OK'
                    });
                    
                    // Refresh the users table
                    refreshUsersTable();
                } else {
                    throw new Error(data.message || 'Failed to update user');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Display validation errors if present
                if (error.errors) {
                    let errorHtml = '<ul class="mb-0">';
                    Object.entries(error.errors).forEach(([field, messages]) => {
                        const fieldName = field.replace(/\.\d+\./g, '.'); // Handle array field names
                        const message = Array.isArray(messages) ? messages[0] : messages;
                        errorHtml += `<li>${fieldName}: ${message}</li>`;
                    });
                    errorHtml += '</ul>';
                    
                    errorDiv.innerHTML = errorHtml;
                    errorDiv.classList.remove('d-none');
                    
                    // Scroll to errors
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    errorDiv.textContent = error.message || 'Failed to update user. Please try again.';
                    errorDiv.classList.remove('d-none');
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
                btnText.textContent = 'Update User';
            });
        });

        // Add this function if not already defined
        function cleanupModal() {
            // Remove all modal backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            
            // Remove modal-open class and reset body styles
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }


        // Preview avatar when a new image is selected
        document.getElementById('edit_avatar')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = document.getElementById('edit_avatar_preview');
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            }
        });



        // Inline Status Toggle  //
        document.querySelectorAll('.user-status-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const label = this.closest('td').querySelector('.form-check-label');
                label.textContent = this.checked ? 'Active' : 'Inactive';
            });
        });
        // Inline Role Change  //
        document.querySelectorAll('.user-role-select').forEach(function(select) {
            select.addEventListener('change', function() {
                alert('Role changed to: ' + this.value);
            });
        });
        // User Activity Button  ///
        document.querySelectorAll('.btn-user-activity').forEach(function(btn) {
            btn.addEventListener('click', function() {
                alert('Show user activity log (to be implemented)');
            });
        });
        // Invite/Import/Export /////
        document.getElementById('inviteUserBtn')?.addEventListener('click', function() {
            alert('Invite user dialog (to be implemented)');
        });
        

        // Import Users cb88Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const importBtn = document.getElementById('importUsersBtn');
            const importForm = document.getElementById('importUsersForm');
            const importFileInput = document.getElementById('importFile');
            const maxFileSize = 2 * 1024 * 1024; // 2MB in bytes
            // Show import modal
            importBtn?.addEventListener('click', function() {
                const importModal = new bootstrap.Modal(document.getElementById('importUsersModal'));
                importModal.show();
            });
            // File validation
            importFileInput?.addEventListener('change', function() {
                const file = this.files[0];
                const errorDiv = document.getElementById('importErrors');
                
                if (file) {
                    // Check file type
                    if (!file.name.endsWith('.csv')) {
                        showError('Please upload a valid CSV file');
                        this.value = '';
                        return;
                    }
                    
                    // Check file size
                    if (file.size > maxFileSize) {
                        showError('File size must be less than 2MB');
                        this.value = '';
                        return;
                    }
                    
                    // Clear any previous errors
                    if (errorDiv) {
                        errorDiv.classList.add('d-none');
                    }
                }
            });

            // Handle import form submission
            importForm?.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const form = this;
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                const errorDiv = document.getElementById('importErrors');
                
                // Reset previous errors
                if (errorDiv) {
                    errorDiv.classList.add('d-none');
                    errorDiv.innerHTML = '';
                }

                // Validate file is selected
                if (!importFileInput.files.length) {
                    showError('Please select a file to import');
                    return;
                }

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Importing...';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to import users');
                    }

                    if (data.success) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            html: data.message || `${data.stats?.imported || 0} users imported successfully`,
                            confirmButtonText: 'OK'
                        });

                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('importUsersModal'));
                        modal.hide();
                        
                        // Reset form
                        form.reset();
                        
                        // Refresh the users table
                        refreshUsersTable();
                    } else {
                        throw new Error(data.message || 'Failed to import users');
                    }
                } catch (error) {
                    console.error('Import Error:', error);
                    showError(error.message || 'An error occurred while importing users. Please try again.');
                } finally {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
            // Helper function to show errors
            function showError(message) {
                const errorDiv = document.getElementById('importErrors');
                if (errorDiv) {
                    errorDiv.innerHTML = message;
                    errorDiv.classList.remove('d-none');
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message,
                        confirmButtonText: 'OK'
                    });
                }
            }
        });



        // Handle export cb88button click
        document.getElementById('exportUsersBtn')?.addEventListener('click', function() {
            // Reset form and error state
            document.getElementById('exportUsersForm')?.reset();
            document.getElementById('exportError')?.classList.add('d-none');
            
            // Show export modal
            const exportModal = new bootstrap.Modal(document.getElementById('exportUsersModal'));
            exportModal.show();
        });
        // Handle export form submission
        document.getElementById('exportUsersForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const exportBtn = form.querySelector('#confirmExportBtn');
            const spinner = form.querySelector('#exportSpinner');
            const btnText = form.querySelector('#exportBtnText');
            const errorDiv = document.getElementById('exportError');
            
            // Show loading state
            exportBtn.disabled = true;
            spinner.classList.remove('d-none');
            btnText.textContent = 'Exporting...';
            
            try {
                // Show processing message
                const swalInstance = Swal.fire({
                    title: 'Preparing Export',
                    html: 'Please wait while we prepare your export file...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form via fetch
                const response = await fetch('{{ route("superadmin.users.export") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    let errorMessage = 'Failed to generate export';
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.error('Error parsing error response:', e);
                    }
                    throw new Error(errorMessage);
                }
                
                // Get the filename from content-disposition or use default
                const contentDisposition = response.headers.get('content-disposition');
                let filename = `users_export_${new Date().toISOString().split('T')[0]}.csv`;
                
                if (contentDisposition) {
                    const filenameMatch = contentDisposition.match(/filename\*?=['"]?(?:UTF-\d['"]*)?([^;\r\n"']*)['"]?;?/i);
                    if (filenameMatch && filenameMatch[1]) {
                        filename = decodeURIComponent(filenameMatch[1].trim());
                    }
                }
                
                // Create blob and download
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                
                // Cleanup
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                // Close modal
                const exportModal = bootstrap.Modal.getInstance(document.getElementById('exportUsersModal'));
                if (exportModal) {
                    exportModal.hide();
                }
                
                // Close loading and show success
                await swalInstance.close();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Export Complete',
                    text: 'Your export file has been downloaded.',
                    timer: 3000,
                    showConfirmButton: false
                });
                
            } catch (error) {
                console.error('Export error:', error);
                
                // Show error in form
                if (errorDiv) {
                    errorDiv.textContent = error.message || 'An error occurred while exporting. Please try again.';
                    errorDiv.classList.remove('d-none');
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                // Show error alert
                Swal.fire({
                    icon: 'error',
                    title: 'Export Failed',
                    text: error.message || 'An error occurred while exporting. Please try again.'
                });
                
            } finally {
                // Reset button state
                if (exportBtn) {
                    exportBtn.disabled = false;
                }
                if (spinner) {
                    spinner.classList.add('d-none');
                }
                if (btnText) {
                    btnText.innerHTML = '<i class="fas fa-file-export me-1"></i> Export';
                }
            }
        });



        // Delete User Button with SweetAlert2 Confirmation
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-delete-user')) {
                e.preventDefault();
                const button = e.target.closest('.btn-delete-user');
                const userId = button.dataset.id;
                const userName = button.dataset.name || 'this user';
                
                Swal.fire({
                    title: 'Delete User',
                    text: `Are you sure you want to delete ${userName}? This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the user',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Send delete request
                        fetch(`/superadmin/users/${userId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => { throw err; });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Remove the row from the table
                                const row = button.closest('tr');
                                if (row) {
                                    row.remove();
                                }
                                
                                Swal.fire(
                                    'Deleted!',
                                    data.message || 'User has been deleted.',
                                    'success'
                                );
                            } else {
                                throw new Error(data.message || 'Failed to delete user');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error!',
                                error.message || 'Failed to delete user. Please try again.',
                                'error'
                            );
                        });
                    }
                });
            }
        });
    </script>
@endsection
