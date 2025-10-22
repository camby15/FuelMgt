@extends('layouts.vertical', ['page_title' => 'Pay In Management'])

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

        /* Textarea specific styling */
        .form-floating textarea.form-control {
            height: auto;
            min-height: 100px;
            resize: vertical;
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

        /* Dark mode label animation */
        [data-bs-theme="dark"] .form-floating input.form-control:focus ~ label,
        [data-bs-theme="dark"] .form-floating select.form-select:focus ~ label,
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus ~ label {
            background-color: var(--#{$prefix}dark);
            color: var(--#{$prefix}primary);
        }

        /* Stats card styling */
        .stats-card {
            border-radius: 10px;
            transition: all 0.3s ease;
            border: 1px solid var(--#{$prefix}border-color);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(var(--#{$prefix}dark-rgb), 0.1);
        }

        /* Dark mode stats card */
        [data-bs-theme="dark"] .stats-card {
            border-color: var(--#{$prefix}gray-700);
        }

        [data-bs-theme="dark"] .stats-card:hover {
            box-shadow: 0 5px 15px rgba(var(--#{$prefix}dark-rgb), 0.2);
        }

        /* Badge styling */
        .badge {
            padding: 0.5em 0.75em;
            border-radius: 0.25rem;
            font-weight: 500;
        }

        .badge-confirmed {
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

        /* Dark mode badges */
        [data-bs-theme="dark"] .badge-confirmed {
            background-color: rgba(var(--#{$prefix}success-rgb), 0.2);
        }

        [data-bs-theme="dark"] .badge-pending {
            background-color: rgba(var(--#{$prefix}warning-rgb), 0.2);
        }

        [data-bs-theme="dark"] .badge-failed {
            background-color: rgba(var(--#{$prefix}danger-rgb), 0.2);
        }

        /* File input styling */
        .form-control[type="file"] {
            padding: 0.375rem 0.75rem;
            line-height: 1.5;
        }

        [data-bs-theme="dark"] .form-control[type="file"] {
            background-color: var(--#{$prefix}dark);
            border-color: var(--#{$prefix}gray-700);
            color: var(--#{$prefix}gray-200);
        }

        /* Small text styling */
        .text-muted {
            color: var(--#{$prefix}gray-600) !important;
        }

        [data-bs-theme="dark"] .text-muted {
            color: var(--#{$prefix}gray-400) !important;
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
                            <li class="breadcrumb-item active">Pay In</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Pay In Management</h4>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card stats-card bg-primary">
                    <div class="card-body">
                        <h5 class="text-white">Total Received</h5>
                        <h3 class="text-white mt-2">$45,289.00</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-success">
                    <div class="card-body">
                        <h5 class="text-white">Confirmed Payments</h5>
                        <h3 class="text-white mt-2">152</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-warning">
                    <div class="card-body">
                        <h5 class="text-white">Pending Payments</h5>
                        <h3 class="text-white mt-2">8</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-danger">
                    <div class="card-body">
                        <h5 class="text-white">Failed Payments</h5>
                        <h3 class="text-white mt-2">3</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Record Payment Card -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Record Payment</h4>
                    </div>
                    <div class="card-body">
                        <form id="paymentForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="payerName" placeholder=" ">
                                        <label for="payerName">Payer Name/Organization</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="referenceNumber" placeholder=" ">
                                        <label for="referenceNumber">Reference/Invoice Number</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="paymentDate" placeholder=" ">
                                        <label for="paymentDate">Payment Date</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="amount" placeholder=" ">
                                        <label for="amount">Amount</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" id="paymentMethod">
                                            <option value="">Select Method</option>
                                            <option value="bank">Bank Transfer</option>
                                            <option value="cash">Cash</option>
                                            <option value="cheque">Cheque</option>
                                            <option value="card">Card</option>
                                            <option value="mobile">Mobile Money</option>
                                        </select>
                                        <label for="paymentMethod">Payment Method</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="description" placeholder=" "></textarea>
                                        <label for="description">Description/Notes</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Attachments</label>
                                        <input type="file" class="form-control" id="attachments" multiple>
                                    </div>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="reset" class="btn btn-light me-2">Clear</button>
                                    <button type="submit" class="btn btn-primary">Record Payment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments List Card -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Payment Records</h4>
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
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="filterStartDate" placeholder=" ">
                                    <label for="filterStartDate">Start Date</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="filterEndDate" placeholder=" ">
                                    <label for="filterEndDate">End Date</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="filterStatus">
                                        <option value="">All Status</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="pending">Pending</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                    <label for="filterStatus">Status</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" id="filterMethod">
                                        <option value="">All Methods</option>
                                        <option value="bank">Bank Transfer</option>
                                        <option value="cash">Cash</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="card">Card</option>
                                        <option value="mobile">Mobile Money</option>
                                    </select>
                                    <label for="filterMethod">Payment Method</label>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="payments-table">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Payer Name</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
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
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables loaded via main Vite bundle -->

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#payments-table').DataTable({
                pageLength: 10,
                order: [[2, 'desc']],
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

            // Handle payment form submission
            $('#paymentForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
            });

            // Handle filters
            $('.form-control, .form-select').on('change', function() {
                // Add your filter logic here
            });
        });
    </script>
@endsection
