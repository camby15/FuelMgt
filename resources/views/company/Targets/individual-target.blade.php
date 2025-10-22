@extends('layouts.vertical', ['page_title' => 'Individual Targets'])

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
                    <h4 class="page-title">Individual Targets</h4>
                </div>
            </div>
        </div>

        <!-- Performance Overview -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="achievement-badge">
                                <i class="ri-target-line fs-2"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">Active Targets</h5>
                                <h3 class="mb-0">8</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="achievement-badge bg-success-subtle text-success">
                                <i class="ri-check-line fs-2"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">Completed</h5>
                                <h3 class="mb-0">12</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="achievement-badge bg-warning-subtle text-warning">
                                <i class="ri-time-line fs-2"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">Upcoming</h5>
                                <h3 class="mb-0">5</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="achievement-badge bg-info-subtle text-info">
                                <i class="ri-star-line fs-2"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">Achievement Rate</h5>
                                <h3 class="mb-0">85%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Target List and Progress -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">My Targets</h5>

                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="targetCategory">
                                    <option value="">All Categories</option>
                                    <option>Financial</option>
                                    <option>Performance</option>
                                    <option>Development</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="targetStatus">
                                    <option value="">All Status</option>
                                    <option>On Track</option>
                                    <option>At Risk</option>
                                    <option>Behind</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="timeframe">
                                    <option value="">All Timeframes</option>
                                    <option>This Week</option>
                                    <option>This Month</option>
                                    <option>This Quarter</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Search targets...">
                            </div>
                        </div>

                        <!-- Targets List -->
                        <div class="target-list">
                            <div class="target-card p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Sales Target Q1</h6>
                                    <span class="badge bg-success-subtle text-success">On Track</span>
                                </div>
                                <p class="text-muted mb-2">Achieve $50,000 in sales for Q1 2025</p>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" style="width: 75%"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">75% Complete</small>
                                    <small class="text-muted">Due: Mar 31, 2025</small>
                                </div>
                            </div>

                            <div class="target-card p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Customer Satisfaction Score</h6>
                                    <span class="badge bg-warning-subtle text-warning">At Risk</span>
                                </div>
                                <p class="text-muted mb-2">Maintain CSAT score above 4.5/5</p>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-warning" style="width: 60%"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Current: 4.2/5</small>
                                    <small class="text-muted">Due: Ongoing</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaderboard and Achievements -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Top Performers</h5>
                        <div class="leaderboard">
                            <div class="leaderboard-item">
                                <div class="leaderboard-rank">1</div>
                                <div>
                                    <h6 class="mb-0">John Doe</h6>
                                    <small class="text-muted">92% Achievement Rate</small>
                                </div>
                            </div>
                            <div class="leaderboard-item">
                                <div class="leaderboard-rank">2</div>
                                <div>
                                    <h6 class="mb-0">Jane Smith</h6>
                                    <small class="text-muted">88% Achievement Rate</small>
                                </div>
                            </div>
                            <div class="leaderboard-item">
                                <div class="leaderboard-rank">3</div>
                                <div>
                                    <h6 class="mb-0">Mike Johnson</h6>
                                    <small class="text-muted">85% Achievement Rate</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Achievements -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Recent Achievements</h5>
                        <div class="achievement-list">
                            <div class="d-flex align-items-center mb-3">
                                <div class="achievement-badge bg-success-subtle text-success">
                                    <i class="ri-trophy-line"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Sales Target Exceeded</h6>
                                    <small class="text-muted">Achieved 120% of Q4 target</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="achievement-badge bg-primary-subtle text-primary">
                                    <i class="ri-medal-line"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Perfect Attendance</h6>
                                    <small class="text-muted">3 months streak</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Analytics -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Performance Analytics</h5>
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
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
                    <form id="individualTargetForm">
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
                                            <option value="performance">Performance</option>
                                            <option value="learning">Learning & Development</option>
                                            <option value="project">Project</option>
                                            <option value="behavioral">Behavioral</option>
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
                                            <option value="tasks">Tasks</option>
                                            <option value="hours">Hours</option>
                                            <option value="percentage">Percentage (%)</option>
                                            <option value="score">Score</option>
                                        </select>
                                        <label for="targetUnit">Target Unit</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="supervisor" multiple>
                                    <option value="manager1">John Manager</option>
                                    <option value="manager2">Sarah Lead</option>
                                    <option value="manager3">Mike Supervisor</option>
                                </select>
                                <label for="supervisor">Supervisor</label>
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
                <div class="modal-footer">
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
            var table = $('#individualTargetsTable').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'colvis']
            });

            // Form submission
            $('#individualTargetForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
                console.log('Form submitted');
            });

            // Handle form reset
            $('.btn-light').on('click', function() {
                $('#individualTargetForm')[0].reset();
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
