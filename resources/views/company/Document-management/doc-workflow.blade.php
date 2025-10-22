@extends('layouts.vertical', ['page_title' => 'Document Workflow'])

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

        /* Status badges */
        .badge-active {
            background-color: var(--#{$prefix}success-bg-subtle);
            color: var(--#{$prefix}success);
        }

        .badge-inactive {
            background-color: var(--#{$prefix}danger-bg-subtle);
            color: var(--#{$prefix}danger);
        }

        /* Switch styling */
        .form-switch .form-check-input {
            background-color: var(--#{$prefix}gray-400);
            border-color: var(--#{$prefix}gray-400);
            cursor: pointer;
        }

        .form-switch .form-check-input:checked {
            background-color: var(--#{$prefix}success);
            border-color: var(--#{$prefix}success);
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWorkflowModal">
                            <i class="ri-add-line me-1"></i> Create Workflow
                        </button>
                    </div>
                    <h4 class="page-title">Document Workflows</h4>
                </div>
            </div>
        </div>

        <!-- Workflow List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Document Type</label>
                                    <select class="form-select" id="filterDocType">
                                        <option value="">All Types</option>
                                        <option>Invoice</option>
                                        <option>Purchase Order</option>
                                        <option>Leave Application</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="filterStatus">
                                        <option value="">All Status</option>
                                        <option>Active</option>
                                        <option>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Search</label>
                                    <input type="text" class="form-control" placeholder="Search workflows...">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-light">Reset Filters</button>
                            </div>
                        </div>

                        <!-- Workflows Table -->
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="workflows-datatable">
                                <thead>
                                    <tr>
                                        <th>Workflow Name</th>
                                        <th>Document Type</th>
                                        <th>Stages</th>
                                        <th>Status</th>
                                        <th>Last Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Invoice Approval</td>
                                        <td>Invoice</td>
                                        <td>4</td>
                                        <td><span class="badge badge-active">Active</span></td>
                                        <td>2025-01-15</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editWorkflowModal">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#viewWorkflowModal">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-line"></i>
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
    </div>

    <!-- Create Workflow Modal -->
    <div class="modal fade" id="createWorkflowModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Workflow</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="docType" required>
                                <option value="">Select Document Type</option>
                                <option value="invoice">Invoice</option>
                                <option value="purchase_order">Purchase Order</option>
                                <option value="leave_application">Leave Application</option>
                            </select>
                            <label for="docType">Document Type</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="workflowName" placeholder="Enter workflow name" required>
                            <label for="workflowName">Workflow Name</label>
                        </div>

                        <!-- Workflow Stages -->
                        <div class="mb-3">
                            <label class="form-label">Workflow Stages</label>
                            <div id="workflow-stages" class="workflow-timeline">
                                <div class="workflow-stage">
                                    <div class="timeline-point"></div>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="stageName" placeholder="Enter stage name">
                                                <label for="stageName">Stage Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <select class="form-select" id="stageRole">
                                                    <option value="">Select Role</option>
                                                    <option value="manager">Manager</option>
                                                    <option value="supervisor">Supervisor</option>
                                                </select>
                                                <label for="stageRole">Assign Role</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" id="stageDays" placeholder="Enter days">
                                                <label for="stageDays">Days</label>
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center">
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-light mt-2" id="add-stage">
                                <i class="ri-add-line"></i> Add Stage
                            </button>
                        </div>

                        <!-- Notifications -->
                        <div class="mb-3">
                            <label class="form-label">Notifications</label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="emailNotif">
                                <label class="form-check-label" for="emailNotif">Email Notifications</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="appNotif">
                                <label class="form-check-label" for="appNotif">In-App Notifications</label>
                            </div>
                        </div>

                        <!-- Template Attachment -->
                        <div class="form-floating mb-5">
                            <input type="file" class="form-control" id="docTemplate">
                            <label for="docTemplate">Document Template</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Create Workflow</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Workflow Modal -->
    <div class="modal fade" id="viewWorkflowModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Workflow Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Workflow Name:</strong> Invoice Approval</p>
                            <p class="mb-1"><strong>Document Type:</strong> Invoice</p>
                            <p class="mb-1"><strong>Status:</strong> <span class="badge badge-active">Active</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Created By:</strong> John Doe</p>
                            <p class="mb-1"><strong>Last Modified:</strong> 2025-01-15</p>
                            <p class="mb-1"><strong>Active Documents:</strong> 5</p>
                        </div>
                    </div>

                    <!-- Workflow Stages -->
                    <h6 class="mb-3">Workflow Stages</h6>
                    <div class="workflow-timeline">
                        <div class="workflow-stage">
                            <div class="timeline-point"></div>
                            <h6>Draft</h6>
                            <p class="mb-1">Role: Creator</p>
                            <p class="mb-0">Duration: 1 day</p>
                        </div>
                        <div class="workflow-stage">
                            <div class="timeline-point"></div>
                            <h6>Review</h6>
                            <p class="mb-1">Role: Supervisor</p>
                            <p class="mb-0">Duration: 2 days</p>
                        </div>
                        <div class="workflow-stage">
                            <div class="timeline-point"></div>
                            <h6>Approval</h6>
                            <p class="mb-1">Role: Manager</p>
                            <p class="mb-0">Duration: 1 day</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editWorkflowModal">Edit Workflow</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables are loaded via main Vite bundle -->
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/dragula/dragula.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#workflows-datatable').DataTable({
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    },
                    info: "Showing workflows _START_ to _END_ of _TOTAL_",
                    lengthMenu: "Display _MENU_ workflows"
                },
                pageLength: 10,
                columns: [
                    { orderable: true },
                    { orderable: true },
                    { orderable: true },
                    { orderable: true },
                    { orderable: true },
                    { orderable: false }
                ],
                order: [[1, 'asc']],
                drawCallback: function () {
                    $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                }
            });

            // Initialize Select2 for all select elements
            $('.form-select').each(function() {
                $(this).select2({
                    placeholder: $(this).find('option:first').text(),
                    allowClear: true,
                    dropdownParent: $(this).closest('.modal'),
                    width: '100%'
                });

                // Handle floating label behavior
                $(this).on('select2:open', function() {
                    $(this).parent().find('label').addClass('active');
                }).on('select2:close', function() {
                    if (!$(this).val()) {
                        $(this).parent().find('label').removeClass('active');
                    }
                });

                // Set initial state of floating label
                if ($(this).val()) {
                    $(this).parent().find('label').addClass('active');
                }
            });

            // Initialize Dragula for workflow stages
            dragula([document.getElementById('workflow-stages')]);

            // Add new stage
            $('#add-stage').click(function() {
                var stageHtml = `
                    <div class="workflow-stage">
                        <div class="timeline-point"></div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="stageName_${Date.now()}" placeholder="Enter stage name">
                                    <label for="stageName_${Date.now()}">Stage Name</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="stageRole_${Date.now()}">
                                        <option value="">Select Role</option>
                                        <option value="manager">Manager</option>
                                        <option value="supervisor">Supervisor</option>
                                    </select>
                                    <label for="stageRole_${Date.now()}">Assign Role</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="stageDays_${Date.now()}" placeholder="Enter days">
                                    <label for="stageDays_${Date.now()}">Days</label>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <button type="button" class="btn btn-danger btn-sm remove-stage">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#workflow-stages').append(stageHtml);

                // Initialize Select2 for the new select element
                var newSelect = $('#workflow-stages').find('.form-select').last();
                newSelect.select2({
                    placeholder: newSelect.find('option:first').text(),
                    allowClear: true,
                    dropdownParent: newSelect.closest('.modal'),
                    width: '100%'
                });

                // Handle floating label behavior for the new select
                newSelect.on('select2:open', function() {
                    $(this).parent().find('label').addClass('active');
                }).on('select2:close', function() {
                    if (!$(this).val()) {
                        $(this).parent().find('label').removeClass('active');
                    }
                });
            });

            // Remove stage
            $(document).on('click', '.remove-stage', function() {
                $(this).closest('.workflow-stage').remove();
            });
        });
    </script>
@endsection
