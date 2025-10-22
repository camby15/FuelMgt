@extends('layouts.vertical', ['page_title' => 'Currency Management'])

@section('css')

    <link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
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

        /* Select2 Integration with Floating Labels */
        .form-floating .select2-container {
            width: 100% !important;
        }

        .form-floating .select2-container .select2-selection--single,
        .form-floating .select2-container .select2-selection--multiple {
            height: 50px;
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            background-color: transparent;
            padding: 1rem 0.75rem;
        }

        .form-floating .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding: 0;
        }

        .form-floating .select2-container .select2-selection__rendered {
            color: inherit;
            line-height: 1.5;
            padding-left: 0;
        }

        .form-floating .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px;
        }

        .form-floating .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #033c42;
            border: none;
            color: white;
            border-radius: 5px;
            padding: 2px 8px;
            margin: 2px;
        }

        [data-bs-theme="dark"] .form-floating .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0dcaf0;
        }

        .form-floating .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }

        .form-floating .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #f8f9fa;
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

        /* Currency specific styles */
        .currency-symbol {
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--#{$prefix}primary);
        }

        .currency-rate {
            font-family: monospace;
            font-size: 1rem;
        }

        .exchange-rate-card {
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .exchange-rate-card:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transform: translateY(-2px);
        }

        [data-bs-theme="dark"] .exchange-rate-card {
            background-color: var(--#{$prefix}gray-800);
            border-color: var(--#{$prefix}gray-700);
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
                            <li class="breadcrumb-item">Settings</li>
                            <li class="breadcrumb-item active">Currency Management</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Currency Management</h4>
                </div>
            </div>
        </div>

        <!-- Currency Overview -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card currency-card">
                    <div class="card-body">
                        <h5>Total Currencies</h5>
                        <h3>25</h3>
                        <p class="mb-0 text-muted">Active currencies</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card currency-card">
                    <div class="card-body">
                        <h5>Base Currency</h5>
                        <h3>USD ($)</h3>
                        <p class="mb-0 text-primary">Reference currency</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card currency-card">
                    <div class="card-body">
                        <h5>Last Update</h5>
                        <h3>2 hrs ago</h3>
                        <p class="mb-0 text-success">Rates up to date</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card currency-card">
                    <div class="card-body">
                        <h5>API Status</h5>
                        <h3>Connected</h3>
                        <p class="mb-0 text-success">Auto-sync enabled</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCurrencyModal">
                                <i class="ri-add-line"></i> Add Currency
                            </button>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light" id="syncRates">
                                    <i class="ri-refresh-line"></i> Sync Rates
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
                                    <input type="text" class="form-control" id="searchCurrency" placeholder=" ">
                                    <label for="searchCurrency">Search Currency</label>
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
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="updateFilter">
                                        <option value="">All Updates</option>
                                        <option value="today">Today</option>
                                        <option value="week">This Week</option>
                                        <option value="month">This Month</option>
                                    </select>
                                    <label for="updateFilter">Last Updated</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-striped dt-responsive nowrap w-100" id="currency-table">
                        <thead>
                            <tr>
                                <th>Currency Name</th>
                                <th>Code</th>
                                <th>Symbol</th>
                                <th>Exchange Rate</th>
                                <th>Last Updated</th>
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

    <!-- Add/Edit Currency Modal -->
    <div class="modal fade" id="addCurrencyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Currency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="currencyForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="currencyCode" placeholder="Select currency">
                                        <option value="">Select currency</option>
                                        <option value="USD">USD - US Dollar</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="GBP">GBP - British Pound</option>
                                        <option value="JPY">JPY - Japanese Yen</option>
                                        <option value="AUD">AUD - Australian Dollar</option>
                                    </select>
                                    <label for="currencyCode">Currency</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="exchangeRate" placeholder="Enter exchange rate" step="0.0001">
                                    <label for="exchangeRate">Exchange Rate</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="currencySymbol" placeholder="Enter symbol">
                                    <label for="currencySymbol">Currency Symbol</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="currencyFormat">
                                        <option value="">Select format</option>
                                        <option value="before">Symbol before amount</option>
                                        <option value="after">Symbol after amount</option>
                                    </select>
                                    <label for="currencyFormat">Symbol Format</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="decimalPlaces">
                                        <option value="">Select decimal places</option>
                                        <option value="0">0 (1)</option>
                                        <option value="1">1 (1.0)</option>
                                        <option value="2">2 (1.00)</option>
                                        <option value="3">3 (1.000)</option>
                                        <option value="4">4 (1.0000)</option>
                                    </select>
                                    <label for="decimalPlaces">Decimal Places</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="thousandsSeparator">
                                        <option value="">Select separator</option>
                                        <option value="comma">Comma (1,000)</option>
                                        <option value="dot">Dot (1.000)</option>
                                        <option value="space">Space (1 000)</option>
                                        <option value="none">None (1000)</option>
                                    </select>
                                    <label for="thousandsSeparator">Thousands Separator</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="currencyNotes" placeholder="Enter notes" style="height: 100px"></textarea>
                            <label for="currencyNotes">Notes</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="isActive" checked>
                            <label class="form-check-label" for="isActive">Active Currency</label>
                        </div>

                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-light me-2">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Currency</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Currency</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rate History Modal -->
    <div class="modal fade" id="rateHistoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Exchange Rate History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">EUR - Euro</h6>
                        <span class="text-muted">Base: USD</span>
                    </div>
                    <div class="rate-history">
                        <!-- Rate history will be populated dynamically -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Export History</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables are loaded via main Vite bundle -->
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for all select elements
            $('.form-select').each(function() {
                $(this).select2({
                    placeholder: $(this).find('option:first').text(),
                    allowClear: true,
                    dropdownParent: $(this).closest('.modal').length ? $(this).closest('.modal') : $('body'),
                    width: '100%'
                });

                // Handle floating label behavior for Select2
                $(this).on('select2:open', function() {
                    $(this).parent().find('label').addClass('active');
                }).on('select2:close', function() {
                    if (!$(this).val() || (Array.isArray($(this).val()) && $(this).val().length === 0)) {
                        $(this).parent().find('label').removeClass('active');
                    }
                });

                // Set initial state of floating label
                if ($(this).val() && (!Array.isArray($(this).val()) || $(this).val().length > 0)) {
                    $(this).parent().find('label').addClass('active');
                }
            });

            // Initialize DataTable
            var table = $('#currencyTable').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'colvis']
            });

            // Form submission
            $('#currencyForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
                console.log('Form submitted');
            });

            // Handle form reset
            $('.btn-light').on('click', function() {
                $('#currencyForm')[0].reset();
                $('.form-select').trigger('change');
            });

            // Exchange rate validation
            $('#exchangeRate').on('input', function() {
                var value = parseFloat($(this).val());
                if (value <= 0) {
                    $(this).val('');
                    alert('Exchange rate must be greater than 0');
                }
            });

            // Currency code validation
            $('#currencyCode').on('change', function() {
                var code = $(this).val();
                if (code) {
                    // You can add API call here to fetch exchange rate
                    console.log('Fetching exchange rate for ' + code);
                }
            });

            // Format preview
            function updateFormatPreview() {
                var symbol = $('#currencySymbol').val() || '$';
                var format = $('#currencyFormat').val();
                var decimals = $('#decimalPlaces').val() || '2';
                var separator = $('#thousandsSeparator').val();
                var amount = 1234.56;

                var formattedAmount = amount.toFixed(decimals);
                if (separator === 'comma') {
                    formattedAmount = formattedAmount.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                } else if (separator === 'dot') {
                    formattedAmount = formattedAmount.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                } else if (separator === 'space') {
                    formattedAmount = formattedAmount.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                }

                var preview = format === 'before' ? symbol + formattedAmount : formattedAmount + symbol;
                $('#formatPreview').text(preview);
            }

            // Update preview on change
            $('#currencySymbol, #currencyFormat, #decimalPlaces, #thousandsSeparator').on('change input', updateFormatPreview);
        });
    </script>
@endsection
