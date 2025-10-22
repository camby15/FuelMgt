@extends('layouts.vertical', ['page_title' => 'Account Mapping'])

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
        .badge-mapped { background-color: var(--#{$prefix}info); color: #fff; }
        .badge-unmapped { background-color: var(--#{$prefix}warning); color: var(--#{$prefix}dark); }

        .mapping-card {
            border-radius: 10px;
            transition: all 0.3s;
            background-color: var(--#{$prefix}card-bg);
            border: 1px solid var(--#{$prefix}border-color);
        }
        .mapping-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(var(--#{$prefix}dark-rgb), 0.1);
        }

        .mapping-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.5rem;
            background-color: var(--#{$prefix}card-bg);
        }

        .mapping-item.mapped {
            border-left: 4px solid var(--#{$prefix}success);
        }

        .mapping-item.unmapped {
            border-left: 4px solid var(--#{$prefix}warning);
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

        /* Filter tags */
        .filter-tag {
            background-color: var(--#{$prefix}tertiary-bg);
            border: 1px solid var(--#{$prefix}border-color);
            color: var(--#{$prefix}body-color);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
            transition: all 0.3s;
        }

        .filter-tag:hover {
            background-color: var(--#{$prefix}primary);
            color: #fff;
            border-color: var(--#{$prefix}primary);
        }

        .filter-tag.active {
            background-color: var(--#{$prefix}primary);
            color: #fff;
            border-color: var(--#{$prefix}primary);
        }

        /* Connection lines */
        .mapping-connection {
            position: relative;
            padding-left: 30px;
        }

        .mapping-connection::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--#{$prefix}border-color);
        }

        /* Status placeholder styling */
        .status-placeholder {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 500;
            line-height: 1;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .status-placeholder::before {
            content: '';
            display: inline-block;
            width: 0.5rem;
            height: 0.5rem;
            margin-right: 0.5rem;
            border-radius: 50%;
        }

        .status-placeholder.active {
            background-color: rgba(var(--#{$prefix}success-rgb), 0.15);
            color: var(--#{$prefix}success);
        }

        .status-placeholder.active::before {
            background-color: var(--#{$prefix}success);
        }

        .status-placeholder.inactive {
            background-color: rgba(var(--#{$prefix}danger-rgb), 0.15);
            color: var(--#{$prefix}danger);
        }

        .status-placeholder.inactive::before {
            background-color: var(--#{$prefix}danger);
        }

        .status-placeholder.pending {
            background-color: rgba(var(--#{$prefix}warning-rgb), 0.15);
            color: var(--#{$prefix}warning);
        }

        .status-placeholder.pending::before {
            background-color: var(--#{$prefix}warning);
        }

        .status-placeholder.mapped {
            background-color: rgba(var(--#{$prefix}info-rgb), 0.15);
            color: var(--#{$prefix}info);
        }

        .status-placeholder.mapped::before {
            background-color: var(--#{$prefix}info);
        }

        .status-placeholder.unmapped {
            background-color: rgba(var(--#{$prefix}secondary-rgb), 0.15);
            color: var(--#{$prefix}secondary);
        }

        .status-placeholder.unmapped::before {
            background-color: var(--#{$prefix}secondary);
        }

        /* Status pill animation */
        @keyframes status-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(var(--#{$prefix}primary-rgb), 0.4);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(var(--#{$prefix}primary-rgb), 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(var(--#{$prefix}primary-rgb), 0);
            }
        }

        .status-placeholder::before {
            animation: status-pulse 2s infinite;
        }

        /* Hover effect */
        .status-placeholder:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
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
                            <li class="breadcrumb-item active">Account Mapping</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Account Mapping Management</h4>
                </div>
            </div>
        </div>

        <!-- Mapping Overview -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card mapping-card">
                    <div class="card-body">
                        <h5>Total Mappings</h5>
                        <h3>324</h3>
                        <p class="mb-0 text-muted">Active mappings</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mapping-card">
                    <div class="card-body">
                        <h5>Unmapped Sub-Accounts</h5>
                        <h3>12</h3>
                        <p class="mb-0 text-warning">Requires mapping</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mapping-card">
                    <div class="card-body">
                        <h5>Main Accounts</h5>
                        <h3>45</h3>
                        <p class="mb-0 text-primary">With mappings</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mapping-card">
                    <div class="card-body">
                        <h5>Invalid Mappings</h5>
                        <h3>3</h3>
                        <p class="mb-0 text-danger">Needs attention</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapping Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMappingModal">
                                <i class="ri-add-line"></i> Add Mapping
                            </button>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#batchMappingModal">
                                    <i class="ri-upload-2-line"></i> Batch Mapping
                                </button>
                                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#treeViewModal">
                                    <i class="ri-git-merge-line"></i> Tree View
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
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="searchMapping" placeholder=" ">
                                    <label for="searchMapping">Search Mappings</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="mainAccountFilter">
                                        <option value="">All Main Accounts</option>
                                        <!-- Main accounts will be populated dynamically -->
                                    </select>
                                    <label for="mainAccountFilter">Main Account</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="accountType">
                                        <option value="">All Types</option>
                                        <option value="assets">Assets</option>
                                        <option value="liabilities">Liabilities</option>
                                        <option value="equity">Equity</option>
                                        <option value="income">Income</option>
                                        <option value="expenses">Expenses</option>
                                    </select>
                                    <label for="accountType">Account Type</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="status">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="warning">Warning</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mappings List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-striped dt-responsive nowrap w-100" id="mappings-table">
                        <thead>
                            <tr>
                                <th>Main Account</th>
                                <th>Main Code</th>
                                <th>Sub Account</th>
                                <th>Sub Code</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Last Updated</th>
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

    <!-- Add/Edit Mapping Modal -->
    <div class="modal fade" id="addMappingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Account Mapping</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="mappingForm">
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="mainAccount">
                                    <option value="">Select Main Account</option>
                                    <!-- Main accounts will be populated dynamically -->
                                </select>
                                <label for="mainAccount">Main Account</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="subAccount" multiple>
                                    <!-- Sub accounts will be populated dynamically -->
                                </select>
                                <label for="subAccount">Sub Account(s)</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="mappingStatus" checked>
                                <label class="form-check-label" for="mappingStatus">Active</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Mapping</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Mapping Modal -->
    <div class="modal fade" id="batchMappingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batch Account Mapping</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <a href="#" class="btn btn-light btn-sm mb-3">
                            <i class="ri-download-2-line"></i> Download Template
                        </a>
                        <div class="dropzone" id="mappingDropzone">
                            <i class="ri-upload-cloud-2-line h1 text-muted"></i>
                            <h5>Drop files here or click to upload</h5>
                            <p class="text-muted">Supported formats: CSV, Excel</p>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="ri-information-line"></i>
                        The file should contain columns for Main Account Code and Sub Account Code
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Upload and Map</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tree View Modal -->
    <div class="modal fade" id="treeViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Account Hierarchy Tree</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="tree-view" id="accountTree">
                        <!-- Tree view will be populated dynamically -->
                        <div class="tree-item main">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Assets</span>
                                <span class="badge badge-active">Main Account</span>
                            </div>
                        </div>
                        <div class="tree-item sub">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Current Assets</span>
                                <span class="badge badge-active">Sub Account</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Export Tree</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables are loaded via main Vite bundle -->

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#mappings-table').DataTable({
                pageLength: 10,
                order: [[0, 'asc']],
                columns: [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    { orderable: false }
                ]
            });

            // Handle mapping form submission
            $('#mappingForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
            });

            // Handle main account change
            $('#mainAccount').on('change', function() {
                // Update sub account options based on selected main account
            });

            // Handle batch mapping file upload
            $('#mappingDropzone').on('drop', function(e) {
                e.preventDefault();
                // Handle file drop
            });

            // Initialize multiple select
            $('#subAccount').select2({
                placeholder: 'Select Sub Account(s)',
                allowClear: true
            });
        });
    </script>
@endsection
