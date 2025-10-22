@extends('layouts.vertical', ['page_title' => 'Sub Account Management'])

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

        .subacc-card {
            border-radius: 10px;
            transition: all 0.3s;
            background-color: var(--#{$prefix}card-bg);
            border: 1px solid var(--#{$prefix}border-color);
        }
        .subacc-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(var(--#{$prefix}dark-rgb), 0.1);
        }

        .subacc-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.5rem;
            background-color: var(--#{$prefix}card-bg);
        }

        .subacc-item.parent {
            border-left: 4px solid var(--#{$prefix}primary);
        }

        .subacc-item.child {
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

        /* Balance indicators */
        .balance-positive {
            color: var(--#{$prefix}success);
        }

        .balance-negative {
            color: var(--#{$prefix}danger);
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

        /* Activity timeline */
        .activity-timeline {
            position: relative;
            padding-left: 30px;
        }

        .activity-timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--#{$prefix}border-color);
        }

        .activity-item {
            position: relative;
            padding-bottom: 1rem;
        }

        .activity-item::before {
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

        .activity-item.credit::before {
            border-color: var(--#{$prefix}success);
        }

        .activity-item.debit::before {
            border-color: var(--#{$prefix}danger);
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
                            <li class="breadcrumb-item active">Sub Accounts</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Sub Account Management</h4>
                </div>
            </div>
        </div>

        <!-- Sub Account Overview -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card subacc-card">
                    <div class="card-body">
                        <h5>Total Sub Accounts</h5>
                        <h3>248</h3>
                        <p class="mb-0 text-muted">Active sub accounts</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card subacc-card">
                    <div class="card-body">
                        <h5>Total Balance</h5>
                        <h3>$567,890</h3>
                        <p class="mb-0 text-success">Across sub accounts</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card subacc-card">
                    <div class="card-body">
                        <h5>Parent Accounts</h5>
                        <h3>42</h3>
                        <p class="mb-0 text-primary">Linked main accounts</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card subacc-card">
                    <div class="card-body">
                        <h5>Inactive Sub Accounts</h5>
                        <h3>15</h3>
                        <p class="mb-0 text-warning">Requires review</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sub Account Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubAccountModal">
                                <i class="ri-add-line"></i> Add Sub Account
                            </button>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#hierarchyModal">
                                    <i class="ri-list-check-2"></i> View Hierarchy
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
                                    <input type="text" class="form-control" id="searchSubAccount" placeholder=" ">
                                    <label for="searchSubAccount">Search Sub Accounts</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="parentAccount">
                                        <option value="">All Parent Accounts</option>
                                        <!-- Parent accounts will be populated dynamically -->
                                    </select>
                                    <label for="parentAccount">Parent Account</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="currency">
                                        <option value="">All Currencies</option>
                                        <option value="usd">USD</option>
                                        <option value="eur">EUR</option>
                                        <option value="gbp">GBP</option>
                                    </select>
                                    <label for="currency">Currency</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="status">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sub Accounts List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-striped dt-responsive nowrap w-100" id="subaccounts-table">
                        <thead>
                            <tr>
                                <th>Sub Account Name</th>
                                <th>Code</th>
                                <th>Parent Account</th>
                                <th>Type</th>
                                <th>Balance</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th>Last Modified</th>
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

    <!-- Add/Edit Sub Account Modal -->
    <div class="modal fade" id="addSubAccountModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Sub Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="subAccountForm">
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="subAccountName" placeholder=" ">
                                <label for="subAccountName">Sub Account Name</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subAccountCode" placeholder=" ">
                                    <label for="subAccountCode">Sub Account Code</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="parentAccountSelect">
                                        <option value="">Select Parent Account</option>
                                        <!-- Parent accounts will be populated dynamically -->
                                    </select>
                                    <label for="parentAccountSelect">Parent Account</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="subAccountType">
                                        <option value="">Select Type</option>
                                        <option value="assets">Assets</option>
                                        <option value="liabilities">Liabilities</option>
                                        <option value="equity">Equity</option>
                                        <option value="income">Income</option>
                                        <option value="expenses">Expenses</option>
                                    </select>
                                    <label for="subAccountType">Account Type</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="subAccountCurrency">
                                        <option value="inherit">Inherit from Parent</option>
                                        <option value="usd">USD</option>
                                        <option value="eur">EUR</option>
                                        <option value="gbp">GBP</option>
                                    </select>
                                    <label for="subAccountCurrency">Currency</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="openingBalance" placeholder=" " step="0.01">
                                <label for="openingBalance">Opening Balance</label>
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
                                <input class="form-check-input" type="checkbox" id="subAccountStatus" checked>
                                <label class="form-check-label" for="subAccountStatus">Active</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Sub Account</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hierarchy View Modal -->
    <div class="modal fade" id="hierarchyModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Account Hierarchy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="hierarchy-view" id="hierarchy-container">
                        <!-- Hierarchy items will be populated dynamically -->
                        <div class="hierarchy-item main">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Assets</span>
                                <span class="badge badge-active">Main Account</span>
                            </div>
                        </div>
                        <div class="hierarchy-item sub">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Current Assets</span>
                                <span class="badge badge-active">Sub Account</span>
                            </div>
                        </div>
                        <div class="hierarchy-item nested">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Cash in Bank</span>
                                <span class="badge badge-active">Nested Sub Account</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Export Hierarchy</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub Account Details Modal -->
    <div class="modal fade" id="subAccountDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sub Account Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Account Information</h6>
                            <p><strong>Name:</strong> <span id="modal-subacc-name"></span></p>
                            <p><strong>Code:</strong> <span id="modal-subacc-code"></span></p>
                            <p><strong>Parent Account:</strong> <span id="modal-parent-name"></span></p>
                            <p><strong>Type:</strong> <span id="modal-subacc-type"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Financial Information</h6>
                            <p><strong>Balance:</strong> <span id="modal-balance"></span></p>
                            <p><strong>Currency:</strong> <span id="modal-currency"></span></p>
                            <p><strong>Status:</strong> <span id="modal-status"></span></p>
                            <p><strong>Last Modified:</strong> <span id="modal-last-modified"></span></p>
                        </div>
                    </div>

                    <h6>Recent Transactions</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody id="modal-transactions">
                                <!-- Transactions will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <h6 class="mt-4">Audit Trail</h6>
                    <div class="audit-timeline" id="modal-audit-trail">
                        <!-- Audit trail will be populated dynamically -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Export Details</button>
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
            $('#subaccounts-table').DataTable({
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
                    null,
                    { orderable: false }
                ]
            });

            // Handle sub account form submission
            $('#subAccountForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
            });

            // Handle parent account change
            $('#parentAccountSelect').on('change', function() {
                // Update sub account type and currency based on parent account
            });

            // Handle view sub account details
            $('.view-subacc').on('click', function() {
                $('#subAccountDetailsModal').modal('show');
            });

            // Handle currency inheritance
            $('#subAccountCurrency').on('change', function() {
                if ($(this).val() === 'inherit') {
                    // Inherit currency from parent account
                }
            });
        });
    </script>
@endsection
