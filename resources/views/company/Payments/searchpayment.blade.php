@extends('layouts.vertical', ['page_title' => 'Payment Search'])

@section('css')

    <style>
        /* Form control base styling */
        .form-floating {
            position: relative;
            margin-bottom: 1rem;
        }

        /* Input and Select styling */
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
            transition: all 0.3s ease;
        }

        /* Dark mode specific input styling */
        [data-bs-theme="dark"] .form-floating input.form-control,
        [data-bs-theme="dark"] .form-floating select.form-select,
        [data-bs-theme="dark"] .form-floating textarea.form-control {
            background-color: var(--#{$prefix}dark);
            border-color: var(--#{$prefix}gray-700);
            color: var(--#{$prefix}gray-200);
        }

        /* Placeholder styling */
        .form-control::placeholder,
        .form-select::placeholder {
            color: var(--#{$prefix}gray-500);
            opacity: 0.7;
        }

        /* Dark mode placeholder styling */
        [data-bs-theme="dark"] .form-control::placeholder,
        [data-bs-theme="dark"] .form-select::placeholder {
            color: var(--#{$prefix}gray-400);
            opacity: 0.8;
        }

        /* Label styling */
        .form-floating label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            color: var(--#{$prefix}gray-600);
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 1;
        }

        /* Dark mode label styling */
        [data-bs-theme="dark"] .form-floating label {
            color: var(--#{$prefix}gray-400);
        }

        /* Focus and filled states */
        .form-floating input.form-control:focus,
        .form-floating input.form-control:not(:placeholder-shown),
        .form-floating select.form-select:focus,
        .form-floating select.form-select:not([value=""]),
        .form-floating textarea.form-control:focus,
        .form-floating textarea.form-control:not(:placeholder-shown) {
            border-color: var(--#{$prefix}primary);
            box-shadow: 0 0 0 0.15rem rgba(var(--#{$prefix}primary-rgb), 0.25);
        }

        /* Dark mode focus states */
        [data-bs-theme="dark"] .form-floating input.form-control:focus,
        [data-bs-theme="dark"] .form-floating select.form-select:focus,
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus {
            background-color: var(--#{$prefix}dark);
            border-color: var(--#{$prefix}primary);
        }

        /* Label animation for focus and filled states */
        .form-floating input.form-control:focus ~ label,
        .form-floating input.form-control:not(:placeholder-shown) ~ label,
        .form-floating select.form-select:focus ~ label,
        .form-floating select.form-select:not([value=""]) ~ label,
        .form-floating textarea.form-control:focus ~ label {
            height: auto;
            padding: 0 0.5rem;
            transform: translateY(-50%) translateX(0.5rem) scale(0.85);
            color: var(--#{$prefix}primary);
            background-color: var(--#{$prefix}body-bg);
            border-radius: 5px;
            z-index: 5;
        }

        /* Dark mode label animation */
        [data-bs-theme="dark"] .form-floating input.form-control:focus ~ label,
        [data-bs-theme="dark"] .form-floating select.form-select:focus ~ label,
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus ~ label {
            background-color: var(--#{$prefix}dark);
            color: var(--#{$prefix}primary);
        }

        /* Status badges */
        .badge {
            padding: 0.5em 0.75em;
            border-radius: 0.25rem;
            font-weight: 500;
        }

        .badge-completed {
            background-color: rgba(var(--#{$prefix}success-rgb), 0.1);
            color: var(--#{$prefix}success);
        }

        .badge-pending {
            background-color: rgba(var(--#{$prefix}warning-rgb), 0.1);
            color: var(--#{$prefix}warning);
        }

        .badge-failed {
            background-color: rgba(var(--#{$prefix}danger-rgb), 0.1);
            color: var(--#{$prefix}danger);
        }

        .badge-refunded {
            background-color: rgba(var(--#{$prefix}primary-rgb), 0.1);
            color: var(--#{$prefix}primary);
        }

        /* Dark mode badges */
        [data-bs-theme="dark"] .badge-completed {
            background-color: rgba(var(--#{$prefix}success-rgb), 0.2);
        }

        [data-bs-theme="dark"] .badge-pending {
            background-color: rgba(var(--#{$prefix}warning-rgb), 0.2);
        }

        [data-bs-theme="dark"] .badge-failed {
            background-color: rgba(var(--#{$prefix}danger-rgb), 0.2);
        }

        [data-bs-theme="dark"] .badge-refunded {
            background-color: rgba(var(--#{$prefix}primary-rgb), 0.2);
        }

        /* Recent search items */
        .recent-search-item {
            padding: 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            color: var(--#{$prefix}body-color);
            background-color: var(--#{$prefix}card-bg);
            border: 1px solid var(--#{$prefix}border-color);
            margin-bottom: 0.5rem;
        }

        .recent-search-item:hover {
            background-color: var(--#{$prefix}gray-100);
            transform: translateX(5px);
        }

        /* Dark mode recent search items */
        [data-bs-theme="dark"] .recent-search-item {
            background-color: var(--#{$prefix}dark);
            border-color: var(--#{$prefix}gray-700);
        }

        [data-bs-theme="dark"] .recent-search-item:hover {
            background-color: var(--#{$prefix}gray-800);
            border-color: var(--#{$prefix}primary);
        }

        /* Quick search input group */
        .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-right: none;
        }

        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            padding: 0.5rem 1rem;
        }

        /* Dark mode input group */
        [data-bs-theme="dark"] .input-group .form-control {
            background-color: var(--#{$prefix}dark);
            border-color: var(--#{$prefix}gray-700);
            color: var(--#{$prefix}gray-200);
        }

        [data-bs-theme="dark"] .input-group .btn-light {
            background-color: var(--#{$prefix}gray-700);
            border-color: var(--#{$prefix}gray-600);
            color: var(--#{$prefix}gray-200);
        }

        [data-bs-theme="dark"] .input-group .btn-light:hover {
            background-color: var(--#{$prefix}gray-600);
            border-color: var(--#{$prefix}gray-500);
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
                            <li class="breadcrumb-item active">Payment Search</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Payment Search</h4>
                </div>
            </div>
        </div>

        <!-- Quick Search and Recent Searches -->
        <div class="row mb-3">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form id="quickSearchForm">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Payment ID, Name, or Reference...">
                                <button class="btn btn-primary" type="submit">Search</button>
                                <button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearch">
                                    Advanced Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Searches</h5>
                        <div class="recent-searches">
                            <div class="recent-search-item">
                                <i class="ri-history-line me-1"></i>
                                Payment ID: #12345
                            </div>
                            <div class="recent-search-item">
                                <i class="ri-history-line me-1"></i>
                                John Doe - Last Week
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Search -->
        <div class="collapse mb-3" id="advancedSearch">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Advanced Search</h5>
                    <form id="advancedSearchForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="paymentId" placeholder=" ">
                                    <label for="paymentId">Payment ID/Reference</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="partyName" placeholder=" ">
                                    <label for="partyName">Payer/Payee Name</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="invoiceNumber" placeholder=" ">
                                    <label for="invoiceNumber">Invoice Number</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="paymentType">
                                        <option value="">All Types</option>
                                        <option value="payin">Pay In</option>
                                        <option value="payout">Pay Out</option>
                                    </select>
                                    <label for="paymentType">Payment Type</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="paymentMethod">
                                        <option value="">All Methods</option>
                                        <option value="bank">Bank Transfer</option>
                                        <option value="cash">Cash</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="card">Card</option>
                                        <option value="mobile">Mobile Money</option>
                                    </select>
                                    <label for="paymentMethod">Payment Method</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="status">
                                        <option value="">All Status</option>
                                        <option value="completed">Completed</option>
                                        <option value="pending">Pending</option>
                                        <option value="failed">Failed</option>
                                        <option value="refunded">Refunded</option>
                                    </select>
                                    <label for="status">Status</label>
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
                                    <input type="date" class="form-control" id="startDate" placeholder=" ">
                                    <label for="startDate">Start Date</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="endDate" placeholder=" ">
                                    <label for="endDate">End Date</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="minAmount" placeholder=" ">
                                    <label for="minAmount">Min Amount</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="maxAmount" placeholder=" ">
                                    <label for="maxAmount">Max Amount</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="reset" class="btn btn-light me-2">Clear</button>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Search Results</h5>
                <div class="d-flex gap-2">
                    <span class="text-muted">Found 150 results</span>
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
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-striped dt-responsive nowrap w-100" id="search-results-table">
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Payer/Payee</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Invoice</th>
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

    <!-- Payment Details Modal -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Payment Information</h6>
                            <p><strong>Payment ID:</strong> <span id="modal-payment-id"></span></p>
                            <p><strong>Date:</strong> <span id="modal-date"></span></p>
                            <p><strong>Amount:</strong> <span id="modal-amount"></span></p>
                            <p><strong>Method:</strong> <span id="modal-method"></span></p>
                            <p><strong>Status:</strong> <span id="modal-status"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Party Information</h6>
                            <p><strong>Name:</strong> <span id="modal-party-name"></span></p>
                            <p><strong>Contact:</strong> <span id="modal-contact"></span></p>
                            <p><strong>Reference:</strong> <span id="modal-reference"></span></p>
                        </div>
                        <div class="col-12 mt-3">
                            <h6>Linked Documents</h6>
                            <ul class="list-unstyled" id="modal-documents">
                                <!-- Documents will be listed here -->
                            </ul>
                        </div>
                        <div class="col-12 mt-3">
                            <h6>Transaction History</h6>
                            <div class="table-responsive">
                                <table class="table table-sm" id="modal-history">
                                    <!-- History will be listed here -->
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Download Receipt</button>
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
            $('#search-results-table').DataTable({
                pageLength: 10,
                order: [[1, 'desc']],
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

            // Handle quick search
            $('#quickSearchForm').on('submit', function(e) {
                e.preventDefault();
                // Add your quick search logic here
            });

            // Handle advanced search
            $('#advancedSearchForm').on('submit', function(e) {
                e.preventDefault();
                // Add your advanced search logic here
            });

            // Handle payment details modal
            $('.view-payment').on('click', function() {
                // Add your modal population logic here
                $('#paymentDetailsModal').modal('show');
            });

            // Handle recent search clicks
            $('.recent-search-item').on('click', function() {
                // Add your recent search logic here
            });
        });
    </script>
@endsection
