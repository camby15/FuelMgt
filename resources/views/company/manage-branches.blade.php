@extends('layouts.vertical', ['page_title' => 'Branch Management'])

@section('css')

    <style>
        /* Form control styling */
        .form-control, .form-select {
            border: 1px solid var(--#{$prefix}border-color);
            padding: 0.45rem 0.9rem;
            font-size: .9rem;
            border-radius: 0.2rem;
            background-color: var(--#{$prefix}input-bg);
            color: var(--#{$prefix}body-color);
        }

        /* Dark mode input styling */
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: var(--#{$prefix}gray-800);
            border-color: var(--#{$prefix}border-color);
            color: var(--#{$prefix}gray-200);
        }

        /* Label styling */
        .form-label {
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--#{$prefix}gray-600);
        }

        /* Dark mode label */
        [data-bs-theme="dark"] .form-label {
            color: var(--#{$prefix}gray-400);
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
        /* Status badges */
        .badge-active {
            background-color: var(--#{$prefix}success-bg-subtle);
            color: var(--#{$prefix}success);
        }

        .badge-inactive {
            background-color: var(--#{$prefix}danger-bg-subtle);
            color: var(--#{$prefix}danger);
        }

        /* Form spacing */
        .mb-3 {
            margin-bottom: 1rem !important;
        }

        /* Table styling */
        .table {
            --#{$prefix}table-striped-bg: var(--#{$prefix}gray-100);
        }

        [data-bs-theme="dark"] .table {
            --#{$prefix}table-striped-bg: var(--#{$prefix}gray-800);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-branch-modal">
                            <i class="ri-add-line me-1"></i> Add New Branch
                        </button>
                    </div>
                    <h4 class="page-title">Branch Management</h4>
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
                                    <label class="form-label">Search Branch</label>
                                    <input type="text" class="form-control" id="searchBranch" placeholder="Search branches...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Filter by Region</label>
                                    <select class="form-select" id="filterRegion">
                                        <option value="">All Regions</option>
                                        <option>North Region</option>
                                        <option>South Region</option>
                                        <option>East Region</option>
                                        <option>West Region</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Filter by Status</label>
                                    <select class="form-select" id="filterStatus">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                                <button type="reset" class="btn btn-light">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branch List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                            </div>
                                        </th>
                                        <th>Branch Name</th>
                                        <th>Location</th>
                                        <th>Manager</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Date Added</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="branch1">
                                            </div>
                                        </td>
                                        <td>Main Branch</td>
                                        <td>123 Main St, City</td>
                                        <td>John Doe</td>
                                        <td>
                                            <span class="d-block">+1 234 5678</span>
                                            <small class="text-muted">john@example.com</small>
                                        </td>
                                        <td><span class="badge badge-active">Active</span></td>
                                        <td>2025-01-01</td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-fill"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#view-branch-modal">
                                                        <i class="ri-eye-line me-2"></i>View Details
                                                    </a>
                                                    <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit-branch-modal">
                                                        <i class="ri-pencil-line me-2"></i>Edit
                                                    </a>
                                                    <a href="javascript:void(0);" class="dropdown-item text-danger">
                                                        <i class="ri-delete-bin-line me-2"></i>Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Add more branch rows here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="row mt-3">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" role="status">
                                    Showing 1 to 10 of 50 entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Branch Modal -->
    <div class="modal fade" id="add-branch-modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="add-branch-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="branch-name" name="branch_name" 
                                           placeholder=" " required>
                                    <label for="branch-name">Branch Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="branch-code" name="branch_code" 
                                           placeholder=" ">
                                    <label for="branch-code">Branch Code</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="address" name="address" 
                                              placeholder=" " rows="3" required></textarea>
                                    <label for="address">Address</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="region" name="region" required>
                                        <option value="">Select Region</option>
                                        <option>North Region</option>
                                        <option>South Region</option>
                                        <option>East Region</option>
                                        <option>West Region</option>
                                    </select>
                                    <label for="region">Region</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="manager" name="manager">
                                        <option value="">Select Manager</option>
                                        <option>John Doe</option>
                                        <option>Jane Smith</option>
                                        <option value="new">+ Add New Manager</option>
                                    </select>
                                    <label for="manager">Branch Manager</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder=" " required>
                                    <label for="phone">Phone Number</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder=" " required>
                                    <label for="email">Email Address</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="notes" name="notes" 
                                              placeholder=" " rows="3"></textarea>
                                    <label for="notes">Additional Notes</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="branchStatus" checked>
                                    <label class="form-check-label" for="branchStatus">Branch Active</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Branch</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Branch Modal -->
    <div class="modal fade" id="edit-branch-modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-branch-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="edit-branch-name" name="branch_name" 
                                           placeholder=" " required>
                                    <label for="edit-branch-name">Branch Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="edit-branch-code" name="branch_code" 
                                           placeholder=" ">
                                    <label for="edit-branch-code">Branch Code</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="edit-address" name="address" 
                                              placeholder=" " rows="3" required></textarea>
                                    <label for="edit-address">Address</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="edit-region" name="region" required>
                                        <option value="">Select Region</option>
                                        <option>North Region</option>
                                        <option>South Region</option>
                                        <option>East Region</option>
                                        <option>West Region</option>
                                    </select>
                                    <label for="edit-region">Region</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="edit-manager" name="manager">
                                        <option value="">Select Manager</option>
                                        <option>John Doe</option>
                                        <option>Jane Smith</option>
                                        <option value="new">+ Add New Manager</option>
                                    </select>
                                    <label for="edit-manager">Branch Manager</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control" id="edit-phone" name="phone" 
                                           placeholder=" " required>
                                    <label for="edit-phone">Phone Number</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="edit-email" name="email" 
                                           placeholder=" " required>
                                    <label for="edit-email">Email Address</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="edit-notes" name="notes" 
                                              placeholder=" " rows="3"></textarea>
                                    <label for="edit-notes">Additional Notes</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit-branchStatus" checked>
                                    <label class="form-check-label" for="edit-branchStatus">Branch Active</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Branch Modal -->
    <div class="modal fade" id="view-branch-modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Branch Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Branch Name:</strong> Main Branch</p>
                            <p class="mb-2"><strong>Branch Code:</strong> MB001</p>
                            <p class="mb-2"><strong>Region:</strong> North Region</p>
                            <p class="mb-2"><strong>Status:</strong> <span class="badge badge-active">Active</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Manager:</strong> John Doe</p>
                            <p class="mb-2"><strong>Phone:</strong> +1 234 5678</p>
                            <p class="mb-2"><strong>Email:</strong> john@example.com</p>
                            <p class="mb-2"><strong>Date Added:</strong> 2025-01-01</p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="mb-2"><strong>Address:</strong></p>
                            <p>123 Main St, City, State, ZIP</p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="mb-2"><strong>Additional Notes:</strong></p>
                            <p>This is the main branch of our operations.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit-branch-modal">Edit Branch</button>
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
            $('.table').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: true,
                // Add more DataTable options as needed
            });

            // Handle select all checkbox
            $('#selectAll').on('change', function() {
                $('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
            });
        });
    </script>
@endsection
