@extends('layouts.vertical', ['page_title' => 'Account Types'])

@section('css')

    <style>
        .form-floating {
            position: relative;
            margin-bottom: 1rem;
        }
        .form-floating input.form-control,
        .form-floating select.form-select,
        .form-floating textarea.form-control {
            height: 50px;
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 10px;
            background-color: var(--#{$prefix}input-bg);
            color: var(--#{$prefix}body-color);
            font-size: 1rem;
            padding: 1rem 0.75rem;
            transition: all 0.8s;
        }
        .form-floating textarea.form-control {
            height: auto;
            min-height: 100px;
        }
        .form-floating label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            color: var(--#{$prefix}body-color);
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
            border-color: var(--#{$prefix}primary);
            box-shadow: 0 0 0 0.15rem rgba(var(--#{$prefix}primary-rgb), 0.25);
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
            color: var(--#{$prefix}primary);
            background-color: var(--#{$prefix}body-bg);
            border-radius: 5px;
            z-index: 5;
        }
        .badge-active { background-color: var(--#{$prefix}success); color: #fff; }
        .badge-inactive { background-color: var(--#{$prefix}danger); color: #fff; }
        .account-type-card {
            border-radius: 10px;
            transition: all 0.3s;
            background-color: var(--#{$prefix}card-bg);
            border: 1px solid var(--#{$prefix}border-color);
        }
        .account-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(var(--#{$prefix}dark-rgb), 0.1);
        }
        .hierarchy-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.5rem;
            background: var(--#{$prefix}card-bg);
            cursor: move;
        }
        .hierarchy-item.parent {
            border-left: 4px solid var(--#{$prefix}primary);
        }
        .hierarchy-item.child {
            margin-left: 2rem;
            border-left: 4px solid var(--#{$prefix}success);
        }
        .hierarchy-placeholder {
            border: 2px dashed var(--#{$prefix}primary);
            border-radius: 0.5rem;
            margin: 0.5rem 0;
            padding: 1rem;
            background: var(--#{$prefix}tertiary-bg);
        }
        .audit-timeline {
            position: relative;
            padding-left: 30px;
        }
        .audit-timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--#{$prefix}border-color);
        }
        .timeline-item {
            position: relative;
            padding-bottom: 1rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -21px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--#{$prefix}body-bg);
            border: 2px solid var(--#{$prefix}primary);
        }

        /* Custom switch styling */
        .form-check-input[type="checkbox"] {
            background-color: var(--#{$prefix}tertiary-bg);
            border-color: var(--#{$prefix}border-color);
        }

        .form-check-input:checked {
            background-color: var(--#{$prefix}primary);
            border-color: var(--#{$prefix}primary);
        }

        .form-switch .form-check-input {
            width: 2.5em;
            margin-left: -2.9em;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e");
            background-position: left center;
            border-radius: 2em;
            transition: background-position .15s ease-in-out;
        }

        .form-switch .form-check-input:checked {
            background-position: right center;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
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
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item">Finance</li>
                            <li class="breadcrumb-item active">Account Types</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Account Types Management</h4>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card account-type-card">
                    <div class="card-body">
                        <h5>Total Account Types</h5>
                        <h3>35</h3>
                        <p class="mb-0 text-muted">Active types</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card account-type-card">
                    <div class="card-body">
                        <h5>Asset Types</h5>
                        <h3>12</h3>
                        <p class="mb-0 text-primary">Including sub-types</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card account-type-card">
                    <div class="card-body">
                        <h5>Liability Types</h5>
                        <h3>8</h3>
                        <p class="mb-0 text-info">Including sub-types</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card account-type-card">
                    <div class="card-body">
                        <h5>Inactive Types</h5>
                        <h3>3</h3>
                        <p class="mb-0 text-warning">Requires review</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Type Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountTypeModal">
                                <i class="ri-add-line"></i> Add Account Type
                            </button>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#hierarchyModal">
                                    <i class="ri-list-check-2"></i> Manage Hierarchy
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Export
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">PDF</a></li>
                                        <li><a class="dropdown-item" href="#">Excel</a></li>
                                        <li><a class="dropdown-item" href="#">CSV</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Search and Filters -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="searchAccountType" placeholder=" ">
                                    <label for="searchAccountType">Search Account Types</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="categoryFilter">
                                        <option value="">All Categories</option>
                                        <option value="assets">Assets</option>
                                        <option value="liabilities">Liabilities</option>
                                        <option value="equity">Equity</option>
                                        <option value="revenue">Revenue</option>
                                        <option value="expenses">Expenses</option>
                                    </select>
                                    <label for="categoryFilter">Category</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    <label for="statusFilter">Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Types List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-striped dt-responsive nowrap w-100" id="account-types-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Category</th>
                                <th>Parent Type</th>
                                <th>Linked Accounts</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Account Type Modal -->
    <div class="modal fade" id="addAccountTypeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Account Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="accountTypeForm">
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="accountTypeName" placeholder=" ">
                                <label for="accountTypeName">Account Type Name</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="category">
                                        <option value="">Select Category</option>
                                        <option value="assets">Assets</option>
                                        <option value="liabilities">Liabilities</option>
                                        <option value="equity">Equity</option>
                                        <option value="revenue">Revenue</option>
                                        <option value="expenses">Expenses</option>
                                    </select>
                                    <label for="category">Category</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="accountCode" placeholder=" ">
                                    <label for="accountCode">Account Code</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="parentType">
                                    <option value="">No Parent (Top Level)</option>
                                    <!-- Parent types will be populated dynamically -->
                                </select>
                                <label for="parentType">Parent Account Type</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="initialBalance" placeholder=" ">
                                <label for="initialBalance">Initial Balance</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <textarea class="form-control" id="description" placeholder=" "></textarea>
                                <label for="description">Description</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="accountStatus" checked>
                                <label class="form-check-label" for="accountStatus">Active</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Account Type</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hierarchy Management Modal -->
    <div class="modal fade" id="hierarchyModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Account Type Hierarchy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="hierarchy-container">
                        <!-- Hierarchy items will be populated dynamically -->
                        <div class="hierarchy-item parent">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Assets</span>
                                <i class="ri-drag-move-fill text-muted"></i>
                            </div>
                        </div>
                        <div class="hierarchy-item child">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Current Assets</span>
                                <i class="ri-drag-move-fill text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Hierarchy</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Log Modal -->
    <div class="modal fade" id="auditLogModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Audit Log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="audit-timeline">
                        <!-- Audit log entries will be populated dynamically -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Export Log</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables are loaded via main Vite bundle -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#account-types-table').DataTable({
                pageLength: 10,
                order: [[0, 'asc']],
                columns: [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    { orderable: false }
                ]
            });

            // Initialize Sortable for hierarchy management
            new Sortable(document.getElementById('hierarchy-container'), {
                animation: 150,
                ghostClass: 'hierarchy-placeholder'
            });

            // Handle account type form submission
            $('#accountTypeForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
            });

            // Handle parent type change
            $('#category').on('change', function() {
                // Update parent type options based on selected category
            });

            // Handle view audit log
            $('.view-audit-log').on('click', function() {
                $('#auditLogModal').modal('show');
            });
        });
    </script>
@endsection
