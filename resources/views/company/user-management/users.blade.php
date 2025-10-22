@extends('layouts.vertical', ['page_title' => 'User Management'])
@section('css')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Floating Label Styles */
        .form-floating {
            position: relative;
            margin-bottom: 1rem;
        }
        .form-floating input.form-control,
        .form-floating select.form-select,
        .form-floating textarea.form-control {
            height: 50px;
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            background-color: transparent;
            font-size: 1rem;
            padding: 1rem 0.75rem;
            transition: all 0.8s;
        }
        .form-floating textarea.form-control {
            min-height: 100px;
            height: auto;
            padding-top: 1.625rem;
        }
        .form-floating label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            color: #2f2f2f;
            transition: all 0.8s;
            pointer-events: none;
            z-index: 1;
        }
        .form-floating input.form-control:focus,
        .form-floating input.form-control:not(:placeholder-shown),
        .form-floating select.form-select:focus,
        .form-floating select.form-select:not([value=""]),
        .form-floating textarea.form-control:focus,
        .form-floating textarea.form-control:not(:placeholder-shown) {
            border-color: #033c42;
            box-shadow: none;
        }
        .form-floating input.form-control:focus ~ label,
        .form-floating input.form-control:not(:placeholder-shown) ~ label,
        .form-floating select.form-select:focus ~ label,
        .form-floating select.form-select:not([value=""]) ~ label,
        .form-floating textarea.form-control:focus ~ label,
        .form-floating textarea.form-control:not(:placeholder-shown) ~ label {
            height: auto;
            padding: 0 0.5rem;
            transform: translateY(-50%) translateX(0.5rem) scale(0.85);
            color: white;
            border-radius: 5px;
            z-index: 5;
        }
        .form-floating input.form-control:focus ~ label::before,
        .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
        .form-floating select.form-select:focus ~ label::before,
        .form-floating select.form-select:not([value=""]) ~ label::before,
        .form-floating textarea.form-control:focus ~ label::before,
        .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: #033c42;
            border-radius: 5px;
            z-index: -1;
        }
        .form-floating input.form-control:focus::placeholder {
            color: transparent;
        }

        /* Dark mode styles */
        [data-bs-theme="dark"] .form-floating input.form-control,
        [data-bs-theme="dark"] .form-floating select.form-select,
        [data-bs-theme="dark"] .form-floating textarea.form-control {
            border-color: #6c757d;
            color: #e9ecef;
        }

        [data-bs-theme="dark"] .form-floating label {
            color: #adb5bd;
        }

        [data-bs-theme="dark"] .form-floating input.form-control:focus,
        [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown),
        [data-bs-theme="dark"] .form-floating select.form-select:focus,
        [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]),
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus,
        [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) {
            border-color: #0dcaf0;
        }

        [data-bs-theme="dark"] .form-floating input.form-control:focus ~ label::before,
        [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:focus ~ label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]) ~ label::before,
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus ~ label::before,
        [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
            background: #0dcaf0;
        }

        [data-bs-theme="dark"] select.form-select option {
            background-color: #212529;
            color: #e9ecef;
        }

        /* Reset and simplify select styles */
        .form-floating select.form-select {
            display: block;
            width: 100%;
            height: 50px;
            padding: 1rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #2f2f2f;
            background-color: transparent;
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            transition: all 0.8s;
            appearance: none;
            background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/></svg>") no-repeat right 0.75rem center/16px 12px;
        }

        [data-bs-theme="dark"] .form-floating select.form-select {
            background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path fill='none' stroke='%23adb5bd' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/></svg>") no-repeat right 0.75rem center/16px 12px;
            background-color: transparent;
        }

        .form-floating select.form-select:focus {
            border-color: #033c42;
            outline: 0;
            box-shadow: none;
        }

        .form-floating select.form-select ~ label {
            padding: 1rem 0.75rem;
        }

        .modal-body {
            background: none;
            padding: 1.5rem;
        }

        /* Action Button Styles */
        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .action-btn i {
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <div class="d-flex align-items-center">

                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fa-solid fa-plus-circle me-1"></i> Add New User
                            </button>
                            <button type="button" class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                                <i class="fa-solid fa-upload me-1"></i> Bulk Upload
                            </button>

                            <a href="{{ route('company-sub-users.download-template') }}" class="btn btn-outline-primary me-2">
                                <i class="fa-solid fa-download me-1"></i> Download Template
                            </a>
                        </div>
                    </div>
                    <h4 class="page-title">User Management</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-striped">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Pin</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($users) && count($users) > 0)
                                        @foreach($users as $user)
                                            <tr>
                                                <td>{{ $user->fullname }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone_number }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span id="pin-{{ $user->id }}">****</span>
                                                        <button type="button"
                                                                data-user-id="{{ $user->id }}"
                                                                onclick="togglePin(this)" 
                                                                class="btn btn-sm btn-link ms-2" 
                                                                title="Toggle PIN visibility">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $user->status ? 'Active' : 'Locked' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                            class="btn btn-sm btn-info action-btn me-1"
                                                            onclick="editUser('{{ $user->id }}', '{{ $user->fullname }}', '{{ $user->email }}', '{{ $user->phone_number }}', '{{ $user->role }}')"
                                                            title="Edit">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-primary action-btn me-1" 
                                                            title="Send SMS"
                                                            onclick="sendSms('{{ $user->id }}')"
                                                            data-bs-toggle="tooltip">
                                                        <i class="fa-solid fa-message"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-success action-btn me-1" 
                                                            title="Send Email"
                                                            onclick="sendEmail('{{ $user->id }}')"
                                                            data-bs-toggle="tooltip">
                                                        <i class="fa-solid fa-envelope"></i>
                                                    </button>

                                                    <form action="{{ route('company-sub-users.destroy', $user->id) }}" 
                                                          method="POST" 
                                                          class="d-inline delete-user-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger action-btn delete-user-btn" 
                                                                title="Delete">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>

                                                    <form action="{{ url('company/users/' . $user->id . '/reset-password') }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-primary action-btn me-1"
                                                                title="Reset PIN"
                                                                onclick="resetPassword('{{ $user->id }}'); return false;">
                                                            <i class="fa-solid fa-key"></i>
                                                        </button>
                                                    </form>

                                                    <form action="{{ url('company/users/' . $user->id . '/toggle-lock') }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" 
                                                                class="btn btn-sm {{ $user->status ? 'btn-warning' : 'btn-success' }} action-btn me-1"
                                                                title="{{ $user->status ? 'Lock' : 'Unlock' }}"
                                                                onclick="toggleLock('{{ $user->id }}'); return false;">
                                                            <i class="fa-solid {{ $user->status ? 'fa-lock' : 'fa-lock-open' }}"></i>
                                                        </button>
                                                    </form>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addUserForm" action="{{ route('company-sub-users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror" 
                                   id="fullname" name="fullname" placeholder=" " 
                                   value="{{ old('fullname') }}" required>
                            <label for="fullname">Full Name</label>
                            @error('fullname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" placeholder=" " 
                                   value="{{ old('email') }}" required>
                            <label for="email">Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" name="phone_number" placeholder=" " 
                                   value="{{ old('phone_number') }}" required>
                            <label for="phone_number">Phone Number</label>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="sub_user" {{ old('role') == 'sub_user' ? 'selected' : '' }}>Sub User</option>
                                <option value="limited_user" {{ old('role') == 'limited_user' ? 'selected' : '' }}>Limited User</option>
                            </select>
                            <label for="role">Role</label>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control @error('pin_code') is-invalid @enderror" 
                                   id="pin_code" name="pin_code" placeholder=" " 
                                   required minlength="4" maxlength="4">
                            <label for="pin_code">PIN Code</label>
                            <small class="text-muted d-block mt-1">4-digit PIN code</small>
                            @error('pin_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm" 
                @if(isset($user) && $user) 
                    action="{{ route('company-sub-users.update', $user->id) }}" 
                @else 
                    action="#" 
                @endif 
                method="POST">
              @csrf
              @method('PUT')
              <div class="modal-body">
                  @if (!isset($user) || !$user)
                      <div class="alert alert-warning">No user selected for editing</div>
                  @else
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              <ul>
                                  @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                              </ul>
                          </div>
                      @endif
                      <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="edit_fullname" name="fullname" 
                                 value="{{ $user->fullname }}" placeholder=" " required>
                          <label for="edit_fullname">Full Name</label>
                      </div>

                      <div class="form-floating mb-3">
                          <input type="email" class="form-control" id="edit_email" name="email" 
                                 value="{{ $user->email }}" placeholder=" " required>
                          <label for="edit_email">Email</label>
                      </div>

                      <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="edit_phone_number" name="phone_number" 
                                 value="{{ $user->phone_number }}" placeholder=" " required>
                          <label for="edit_phone_number">Phone Number</label>
                      </div>

                      <div class="form-floating mb-3">
                          <select class="form-select" id="edit_role" name="role" required>
                              <option value="sub_user" {{ $user->role == 'sub_user' ? 'selected' : '' }}>Sub User</option>
                              <option value="limited_user" {{ $user->role == 'limited_user' ? 'selected' : '' }}>Limited User</option>
                          </select>
                          <label for="edit_role">Role</label>
                      </div>
                  @endif
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" {{ !isset($user) || !$user ? 'disabled' : '' }}>Update User</button>
              </div>
          </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Show modal if there are validation errors
        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->any())
                var addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
                addUserModal.show();
            @endif
        });

        function togglePin(button) {
            const userId = button.getAttribute('data-user-id');
            const pinSpan = document.getElementById('pin-' + userId);
            const eyeIcon = button.querySelector('i');

            if (pinSpan.textContent === '****') {
                // Get PIN from server
                fetch(`${window.location.origin}/company/users/${userId}/get-pin`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    pinSpan.textContent = data.pin;
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to retrieve PIN'
                    });
                });
            } else {
                pinSpan.textContent = '****';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function sendSms(userId) {
            fetch(`${window.location.origin}/company/users/${userId}/send-sms`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'SMS sent successfully'
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to send SMS'
                });
            });
        }

        function sendEmail(userId) {
            fetch(`${window.location.origin}/company/users/${userId}/send-email`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message || 'Email sent successfully'
                    });
                } else {
                    throw new Error(data.message || 'Failed to send email');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to send email. Please try again.'
                });
            });
        }

        function editUser(id, fullname, email, phone_number, role) {
            document.getElementById('edit_fullname').value = fullname;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone_number').value = phone_number;
            document.getElementById('edit_role').value = role;

            const form = document.getElementById('editUserForm');
            form.action = `{{ route('company-sub-users.update', '') }}/${id}`;

            const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editModal.show();
        }

        function resetPassword(userId) {
            // Find the specific form for this user
            const form = document.querySelector(`form button[onclick="resetPassword('${userId}')"]`).closest('form');

            // Submit the form
            form.submit();
        }

        function toggleLock(userId) {
            // Find the specific form for this user
            const form = document.querySelector(`form button[onclick="toggleLock('${userId}')"]`).closest('form');

            // Submit the form
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-user-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    const form = this.closest('.delete-user-form');
                    const userName = form.closest('tr').querySelector('td:first-child').textContent.trim();

                    Swal.fire({
                        title: 'Are you sure?',
                        html: `Do you want to delete the user <strong>${userName}</strong>?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });

        // Show success/error messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ok'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Ok'
            });
        @endif
    </script>

         <!-- Bulk Upload Modal -->
    <div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Upload Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('company-sub-users.bulk-upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            Download the template first and fill it with your user data. Make sure to follow the format exactly.
                        </div>

                        <div class="mb-3">
                            <label for="bulk_upload_file" class="form-label">Choose CSV File</label>
                            <input type="file" class="form-control" id="bulk_upload_file" name="bulk_upload_file" accept=".csv,.txt" required>
                            <div class="form-text">
                                Accepted formats: .csv, .txt (max 2MB)
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
