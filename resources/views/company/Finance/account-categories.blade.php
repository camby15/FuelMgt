@extends('layouts.vertical', ['page_title' => 'Account Categories'])

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
        .badge-pending { background-color: var(--#{$prefix}warning); color: var(--#{$prefix}dark); }

        .category-card {
            border-radius: 10px;
            transition: all 0.3s;
            background-color: var(--#{$prefix}card-bg);
            border: 1px solid var(--#{$prefix}border-color);
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(var(--#{$prefix}dark-rgb), 0.1);
        }

        .category-tree-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.5rem;
            background-color: var(--#{$prefix}card-bg);
        }

        .category-tree-item.parent {
            border-left: 4px solid var(--#{$prefix}primary);
        }

        .category-tree-item.child {
            margin-left: 2rem;
            border-left: 4px solid var(--#{$prefix}success);
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
                            <li class="breadcrumb-item active">Account Categories</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Account Category Management</h4>
                </div>
            </div>
        </div>

        <!-- Category Overview -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card category-card">
                    <div class="card-body">
                        <h5>Total Categories</h5>
                        <h3>156</h3>
                        <p class="mb-0 text-muted">Active categories</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card category-card">
                    <div class="card-body">
                        <h5>Parent Categories</h5>
                        <h3>42</h3>
                        <p class="mb-0 text-primary">Top-level categories</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card category-card">
                    <div class="card-body">
                        <h5>Linked Accounts</h5>
                        <h3>324</h3>
                        <p class="mb-0 text-success">Associated accounts</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card category-card">
                    <div class="card-body">
                        <h5>Inactive Categories</h5>
                        <h3>8</h3>
                        <p class="mb-0 text-warning">Requires review</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="ri-add-line"></i> Add Category
                            </button>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                                    <i class="ri-upload-2-line"></i> Bulk Actions
                                </button>
                                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#hierarchyModal">
                                    <i class="ri-git-merge-line"></i> Hierarchy View
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
                                    <input type="text" class="form-control" id="searchCategory" placeholder=" ">
                                    <label for="searchCategory">Search Categories</label>
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
                                    <select class="form-select" id="parentCategory">
                                        <option value="">All Parent Categories</option>
                                        <!-- Parent categories will be populated dynamically -->
                                    </select>
                                    <label for="parentCategory">Parent Category</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="status">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-striped dt-responsive nowrap w-100" id="categories-table">
                        <thead>
                            <tr>
                                <th>Category Name</th>
                                <th>Code</th>
                                <th>Parent Category</th>
                                <th>Account Type</th>
                                <th>Linked Accounts</th>
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

    <!-- Add/Edit Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm">
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="categoryName" placeholder=" ">
                                <label for="categoryName">Category Name</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="categoryCode" placeholder=" ">
                                <label for="categoryCode">Category Code</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="parentCategorySelect">
                                    <option value="">No Parent (Top Level)</option>
                                    <!-- Parent categories will be populated dynamically -->
                                </select>
                                <label for="parentCategorySelect">Parent Category</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="defaultType">
                                    <option value="">Select Default Type</option>
                                    <option value="assets">Assets</option>
                                    <option value="liabilities">Liabilities</option>
                                    <option value="equity">Equity</option>
                                    <option value="income">Income</option>
                                    <option value="expenses">Expenses</option>
                                </select>
                                <label for="defaultType">Default Account Type</label>
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
                                <input class="form-check-input" type="checkbox" id="categoryStatus" checked>
                                <label class="form-check-label" for="categoryStatus">Active</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Category</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Category Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <a href="#" class="btn btn-light btn-sm mb-3">
                            <i class="ri-download-2-line"></i> Download Template
                        </a>
                        <div class="dropzone" id="categoryDropzone">
                            <i class="ri-upload-cloud-2-line h1 text-muted"></i>
                            <h5>Drop files here or click to upload</h5>
                            <p class="text-muted">Supported formats: CSV, Excel</p>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="ri-information-line"></i>
                        The file should contain columns for Category Name, Code, Parent Category, and Account Type
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Process Bulk Action</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hierarchy View Modal -->
    <div class="modal fade" id="hierarchyModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Category Hierarchy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="tree-view" id="categoryTree">
                        <!-- Tree view will be populated dynamically -->
                        <div class="tree-item parent">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Assets</span>
                                <span class="badge badge-active">Parent Category</span>
                            </div>
                        </div>
                        <div class="tree-item child">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Current Assets</span>
                                <span class="badge badge-active">Child Category</span>
                            </div>
                        </div>
                        <div class="tree-item grandchild">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Cash and Cash Equivalents</span>
                                <span class="badge badge-active">Grandchild Category</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Hierarchy</button>
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
            $('#categories-table').DataTable({
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

            // Initialize Sortable for hierarchy management
            new Sortable(document.getElementById('categoryTree'), {
                animation: 150,
                ghostClass: 'tree-placeholder'
            });

            // Handle category form submission
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
            });

            // Handle parent category change
            $('#parentCategorySelect').on('change', function() {
                // Update form based on selected parent category
            });

            // Handle file upload
            $('#categoryDropzone').on('drop', function(e) {
                e.preventDefault();
                // Handle file drop
            });
        });
    </script>
@endsection
