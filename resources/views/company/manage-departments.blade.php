@extends('layouts.vertical', ['page_title' => 'Department Management'])

@section('css')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Status cell styling */
        .status-cell {
            width: 85px;
            text-align: center;
        }

        .form-check.form-switch {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-left: 0;
        }

        .form-switch .form-check-input {
            margin-left: 0;
            width: 2.5em;
        }

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

        /* Select Styles */
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

        [data-bs-theme="dark"] select.form-select option {
            background-color: #212529;
            color: #e9ecef;
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

        .modal-body {
            background: none;
            padding: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                            <i class="ri-add-line me-1"></i> Add New Department
                        </button>
                    </div>
                    <h4 class="page-title">Department Management</h4>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Search Department</label>
                                    <input type="text" class="form-control" id="searchDepartment" placeholder="Search by name, code, or manager...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="filterStatus">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Division</label>
                                    <select class="form-select" id="filterDivision">
                                        <option value="">All Divisions</option>
                                        <option>Corporate</option>
                                        <option>Operations</option>
                                        <option>Support</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="mb-3 w-100">
                                    <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                                    <button type="reset" class="btn btn-light">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departments Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="departments-datatable">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                            </div>
                                        </th>
                                        <th>Department</th>
                                        <th>Code</th>
                                        <th>Head/Manager</th>
                                        <th>Contact</th>
                                        <th>Employees</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="department1">
                                            </div>
                                        </td>
                                        <td>Human Resources</td>
                                        <td>HR-001</td>
                                        <td>
                                            <img src="/images/users/avatar-1.jpg" alt="user-img" class="rounded-circle me-2" height="24">
                                            <span>John Smith</span>
                                        </td>
                                        <td>
                                            <span class="d-block">+1 234 5678</span>
                                            <small class="text-muted">hr@example.com</small>
                                        </td>
                                        <td>15</td>
                                        <td class="status-cell">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" 
                                                       id="status_1" 
                                                       checked
                                                       onchange="updateStatus(1, this.checked)">
                                            </div>
                                        </td>
                                        <td>2025-01-01</td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewDepartmentModal">
                                                        <i class="ri-eye-line me-2"></i>View Details
                                                    </a>
                                                    <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editDepartmentModal">
                                                        <i class="ri-pencil-line me-2"></i>Edit
                                                    </a>
                                                    <a href="javascript:void(0);" class="dropdown-item text-danger">
                                                        <i class="ri-delete-bin-line me-2"></i>Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Department Modal -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDepartmentForm">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control @error('department_name') is-invalid @enderror" 
                                           id="department_name" name="department_name" placeholder=" " 
                                           value="{{ old('department_name') }}" required>
                                    <label for="department_name">Department Name</label>
                                    @error('department_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control @error('department_code') is-invalid @enderror" 
                                           id="department_code" name="department_code" placeholder=" " 
                                           value="{{ old('department_code') }}" required>
                                    <label for="department_code">Department Code</label>
                                    @error('department_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select @error('department_head') is-invalid @enderror" 
                                            id="department_head" name="department_head" required>
                                        <option value="">Select Department Head</option>
                                        <option value="1">John Smith</option>
                                        <option value="2">Jane Doe</option>
                                    </select>
                                    <label for="department_head">Department Head</label>
                                    @error('department_head')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select @error('division') is-invalid @enderror" 
                                            id="division" name="division" required>
                                        <option value="">Select Division</option>
                                        <option value="corporate">Corporate</option>
                                        <option value="operations">Operations</option>
                                        <option value="support">Support</option>
                                    </select>
                                    <label for="division">Division</label>
                                    @error('division')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                           id="phone_number" name="phone_number" placeholder=" " 
                                           value="{{ old('phone_number') }}" required>
                                    <label for="phone_number">Phone Number</label>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" placeholder=" " 
                                           value="{{ old('email') }}" required>
                                    <label for="email">Email Address</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" placeholder=" " 
                                              style="height: 100px">{{ old('description') }}</textarea>
                                    <label for="description">Description</label>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input @error('status') is-invalid @enderror" 
                                               type="checkbox" role="switch" id="status" name="status" 
                                               value="active" checked>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Department</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDepartmentForm">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control @error('department_name') is-invalid @enderror" 
                                           id="edit_department_name" name="department_name" placeholder=" " 
                                           value="{{ old('department_name', $department->name ?? '') }}" required>
                                    <label for="edit_department_name">Department Name</label>
                                    @error('department_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control @error('department_code') is-invalid @enderror" 
                                           id="edit_department_code" name="department_code" placeholder=" " 
                                           value="{{ old('department_code', $department->code ?? '') }}" required>
                                    <label for="edit_department_code">Department Code</label>
                                    @error('department_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select @error('department_head') is-invalid @enderror" 
                                            id="edit_department_head" name="department_head" required>
                                        <option value="1" selected>John Smith</option>
                                        <option value="2">Jane Doe</option>
                                    </select>
                                    <label for="edit_department_head">Department Head</label>
                                    @error('department_head')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select @error('division') is-invalid @enderror" 
                                            id="edit_division" name="division" required>
                                        <option value="corporate" selected>Corporate</option>
                                        <option value="operations">Operations</option>
                                        <option value="support">Support</option>
                                    </select>
                                    <label for="edit_division">Division</label>
                                    @error('division')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                           id="edit_phone_number" name="phone_number" placeholder=" " 
                                           value="{{ old('phone_number', $department->phone_number ?? '') }}" required>
                                    <label for="edit_phone_number">Phone Number</label>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="edit_email" name="email" placeholder=" " 
                                           value="{{ old('email', $department->email ?? '') }}" required>
                                    <label for="edit_email">Email Address</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="edit_description" name="description" placeholder=" " 
                                              style="height: 100px">{{ old('description', $department->description ?? '') }}</textarea>
                                    <label for="edit_description">Description</label>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input @error('status') is-invalid @enderror" 
                                               type="checkbox" role="switch" id="edit_status" name="status">
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Department Modal -->
    <div class="modal fade" id="viewDepartmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Department Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Department Name:</strong> Human Resources</p>
                            <p class="mb-2"><strong>Department Code:</strong> HR-001</p>
                            <p class="mb-2"><strong>Division:</strong> Corporate</p>
                            <p class="mb-2"><strong>Status:</strong> 
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="view_status" disabled>
                                </div>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Department Head:</strong> John Smith</p>
                            <p class="mb-2"><strong>Phone:</strong> +1 234 5678</p>
                            <p class="mb-2"><strong>Email:</strong> hr@example.com</p>
                            <p class="mb-2"><strong>Total Employees:</strong> 15</p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="mb-2"><strong>Description:</strong></p>
                            <p>Responsible for managing employee relations, recruitment, and workplace policies.</p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="mb-2"><strong>Documents:</strong></p>
                            <ul class="list-unstyled">
                                <li><i class="ri-file-text-line me-2"></i>Department Policy.pdf</li>
                                <li><i class="ri-file-text-line me-2"></i>Org Chart.pdf</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDepartmentModal">Edit Department</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['node_modules/datatables.net/js/jquery.dataTables.min.js', 'node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js'])
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#departments-datatable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: true,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            });

            // Handle select all checkbox
            $('#selectAll').on('change', function() {
                $('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
            });

            // File upload preview
            $('.file-upload-input').on('change', function() {
                var fileCount = this.files.length;
                var label = $(this).siblings('.file-upload-label');
                if (fileCount > 0) {
                    label.text(fileCount + ' file(s) selected');
                } else {
                    label.html('<i class="ri-upload-2-line me-1"></i>Drop files here or click to upload');
                }
            });
        });

        function updateStatus(id, isActive) {
            // Here you can add AJAX call to update status in backend
            // axios.post(`/departments/${id}/update-status`, { status: isActive ? 'active' : 'inactive' })
            //     .then(response => {
            //         // Handle success
            //     })
            //     .catch(error => {
            //         // Handle error
            //     });
        }
    </script>
@endsection
