@extends('layouts.vertical', ['page_title' => 'Company Targets'])

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

        /* Status badges */
        .badge-active {
            background-color: var(--#{$prefix}success-bg-subtle);
            color: var(--#{$prefix}success);
        }

        .badge-inactive {
            background-color: var(--#{$prefix}danger-bg-subtle);
            color: var(--#{$prefix}danger);
        }

        /* Progress bars */
        .progress {
            background-color: var(--#{$prefix}gray-200);
            border-radius: 0.2rem;
            height: 0.5rem;
        }

        [data-bs-theme="dark"] .progress {
            background-color: var(--#{$prefix}gray-700);
        }

        .progress-bar {
            border-radius: 0.2rem;
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTargetModal">
                            <i class="ri-add-line me-1"></i> Add Target
                        </button>
                    </div>
                    <h4 class="page-title">Company Targets</h4>
                </div>
            </div>
        </div>

        <!-- Dashboard Overview -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Total Targets</h5>
                        <h2>24</h2>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 75%"></div>
                        </div>
                        <small class="text-muted">18 On Track, 6 Behind</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Revenue Target</h5>
                        <h2>$2.5M</h2>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: 60%"></div>
                        </div>
                        <small class="text-muted">60% Achieved</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Sales Target</h5>
                        <h2>850</h2>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 85%"></div>
                        </div>
                        <small class="text-muted">85% Achieved</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Customer Growth</h5>
                        <h2>+25%</h2>
                        <div class="progress">
                            <div class="progress-bar bg-danger" style="width: 40%"></div>
                        </div>
                        <small class="text-muted">40% Achieved</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Targets List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="targetType">
                                    <option value="">All Target Types</option>
                                    <option>Financial</option>
                                    <option>Sales</option>
                                    <option>Operational</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="department">
                                    <option value="">All Departments</option>
                                    <option>Sales</option>
                                    <option>Marketing</option>
                                    <option>Operations</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="status">
                                    <option value="">All Status</option>
                                    <option>On Track</option>
                                    <option>At Risk</option>
                                    <option>Behind</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="timeframe">
                                    <option value="">All Timeframes</option>
                                    <option>Monthly</option>
                                    <option>Quarterly</option>
                                    <option>Yearly</option>
                                </select>
                            </div>
                        </div>

                        <!-- Targets Table -->
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="targets-datatable">
                                <thead>
                                    <tr>
                                        <th>Target Name</th>
                                        <th>Type</th>
                                        <th>Department</th>
                                        <th>Target Value</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Deadline</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Q1 Revenue Target</td>
                                        <td>Financial</td>
                                        <td>Sales</td>
                                        <td>$500,000</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" style="width: 75%"></div>
                                            </div>
                                            <small>75% Complete</small>
                                        </td>
                                        <td>
                                            <span class="status-indicator status-on-track"></span>
                                            On Track
                                        </td>
                                        <td>2025-03-31</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editTargetModal">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#viewTargetModal">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Target Achievement Trends</h5>
                        <div class="chart-container">
                            <canvas id="trendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Department Performance</h5>
                        <div class="chart-container">
                            <canvas id="departmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Target Modal -->
    <div class="modal fade" id="addTargetModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Target</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="companyTargetForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="targetTitle" placeholder="Enter target title">
                                        <label for="targetTitle">Target Title</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="targetType">
                                            <option value="">Select target type</option>
                                            <option value="revenue">Revenue</option>
                                            <option value="growth">Growth</option>
                                            <option value="efficiency">Efficiency</option>
                                            <option value="customer">Customer</option>
                                        </select>
                                        <label for="targetType">Target Type</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="startDate" placeholder="Start Date">
                                        <label for="startDate">Start Date</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="endDate" placeholder="End Date">
                                        <label for="endDate">End Date</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="targetValue" placeholder="Enter target value">
                                        <label for="targetValue">Target Value</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="targetUnit">
                                            <option value="">Select unit</option>
                                            <option value="currency">Currency ($)</option>
                                            <option value="percentage">Percentage (%)</option>
                                            <option value="number">Number (#)</option>
                                        </select>
                                        <label for="targetUnit">Target Unit</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="departments" multiple>
                                    <option value="sales">Sales</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="operations">Operations</option>
                                    <option value="finance">Finance</option>
                                    <option value="hr">HR</option>
                                </select>
                                <label for="departments">Assigned Departments</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="description" placeholder="Enter description" style="height: 100px"></textarea>
                                <label for="description">Description</label>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="milestone1" placeholder="Enter milestone">
                                        <label for="milestone1">Milestone 1 Value</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="milestone1Date" placeholder="Milestone Date">
                                        <label for="milestone1Date">Milestone 1 Date</label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-light me-2">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Target</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Target Modal -->
    <div class="modal fade" id="viewTargetModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Target Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Q1 Revenue Target</h6>
                            <p class="text-muted mb-2">Financial Target</p>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" style="width: 75%"></div>
                            </div>
                            <p><strong>Target Value:</strong> $500,000</p>
                            <p><strong>Current Progress:</strong> $375,000</p>
                            <p><strong>Department:</strong> Sales</p>
                            <p><strong>Deadline:</strong> March 31, 2025</p>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-container">
                                <canvas id="targetProgressChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6>Milestones</h6>
                    <div class="target-timeline">
                        <div class="mb-3">
                            <div class="timeline-point"></div>
                            <h6>$200,000 Revenue</h6>
                            <p class="mb-0 text-success">Completed on January 31, 2025</p>
                        </div>
                        <div class="mb-3">
                            <div class="timeline-point"></div>
                            <h6>$350,000 Revenue</h6>
                            <p class="mb-0 text-success">Completed on February 28, 2025</p>
                        </div>
                        <div class="mb-3">
                            <div class="timeline-point"></div>
                            <h6>$500,000 Revenue</h6>
                            <p class="mb-0 text-warning">Due by March 31, 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['node_modules/datatables.net/js/jquery.dataTables.min.js', 'node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js'])
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
            var table = $('#targetsTable').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'colvis']
            });

            // Form submission
            $('#companyTargetForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
                console.log('Form submitted');
            });

            // Handle form reset
            $('.btn-light').on('click', function() {
                $('#companyTargetForm')[0].reset();
                $('.form-select').trigger('change');
            });

            // Date input validation
            $('#startDate, #endDate').on('change', function() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();

                if (startDate && endDate && startDate > endDate) {
                    alert('End date must be after start date');
                    $(this).val('');
                }
            });

            // Target value validation
            $('#targetValue').on('input', function() {
                if ($(this).val() < 0) {
                    $(this).val(0);
                }
            });
        });
    </script>
@endsection
