@extends('layouts.vertical', ['page_title' => 'Document Classifications'])

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

        /* Classification badges */
        .badge-confidential {
            background-color: var(--#{$prefix}danger-bg-subtle);
            color: var(--#{$prefix}danger);
        }

        .badge-restricted {
            background-color: var(--#{$prefix}warning-bg-subtle);
            color: var(--#{$prefix}warning);
        }

        .badge-internal {
            background-color: var(--#{$prefix}info-bg-subtle);
            color: var(--#{$prefix}info);
        }

        .badge-public {
            background-color: var(--#{$prefix}success-bg-subtle);
            color: var(--#{$prefix}success);
        }

        /* Restriction card */
        .restriction-card {
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.2rem;
            margin-bottom: 1rem;
        }

        [data-bs-theme="dark"] .restriction-card {
            background-color: var(--#{$prefix}gray-800);
            border-color: var(--#{$prefix}gray-700);
        }

        /* Action icons */
        .action-icon {
            width: 2rem;
            height: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.2rem;
            background-color: var(--#{$prefix}light);
            color: var(--#{$prefix}body-color);
            margin-right: 0.5rem;
        }

        [data-bs-theme="dark"] .action-icon {
            background-color: var(--#{$prefix}gray-700);
            color: var(--#{$prefix}gray-200);
        }

        /* Audit trail timeline */
        .audit-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .audit-timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: var(--#{$prefix}border-color);
        }

        .timeline-point {
            position: absolute;
            left: 0;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background-color: var(--#{$prefix}primary);
            transform: translateX(-0.4rem);
        }

        /* Permission matrix */
        .permission-matrix {
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.2rem;
            padding: 1rem;
        }

        [data-bs-theme="dark"] .permission-matrix {
            background-color: var(--#{$prefix}gray-800);
            border-color: var(--#{$prefix}gray-700);
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRestrictionModal">
                            <i class="ri-add-line me-1"></i> Add Classification
                        </button>
                    </div>
                    <h4 class="page-title">Document Classifications</h4>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Search</label>
                                    <input type="text" class="form-control" placeholder="Search classifications...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select">
                                        <option value="">All Status</option>
                                        <option>Active</option>
                                        <option>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Access Level</label>
                                    <select class="form-select">
                                        <option value="">All Levels</option>
                                        <option>Confidential</option>
                                        <option>Restricted</option>
                                        <option>Internal</option>
                                        <option>Public</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Action Restriction</label>
                                    <select class="form-select">
                                        <option value="">All Actions</option>
                                        <option>No Printing</option>
                                        <option>No Sharing</option>
                                        <option>No Download</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classifications Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="classifications-datatable">
                                <thead>
                                    <tr>
                                        <th>Classification</th>
                                        <th>Description</th>
                                        <th>Access Level</th>
                                        <th>Restrictions</th>
                                        <th>Status</th>
                                        <th>Last Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="badge badge-confidential">Confidential</span>
                                        </td>
                                        <td>Highly sensitive information, internal use only</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="action-icon">
                                                    <i class="ri-user-settings-line"></i>
                                                </span>
                                                Admin, Manager
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <span class="action-icon" title="No Printing">
                                                    <i class="ri-printer-line text-danger"></i>
                                                </span>
                                                <span class="action-icon" title="No Sharing">
                                                    <i class="ri-share-line text-danger"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-success-subtle">Active</span></td>
                                        <td>2025-01-15</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editRestrictionModal">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#viewUsageModal">
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

    <!-- Add Classification Modal -->
    <div class="modal fade" id="addRestrictionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Classification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="classificationName" name="name" placeholder=" " required>
                            <label for="classificationName">Classification Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="description" name="description" placeholder=" " style="height: 100px" required></textarea>
                            <label for="description">Description</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select" id="accessLevel" name="access_level" required>
                                <option value="">Select Access Level</option>
                                <option value="Confidential">Confidential</option>
                                <option value="Restricted">Restricted</option>
                                <option value="Internal">Internal</option>
                                <option value="Public">Public</option>
                            </select>
                            <label for="accessLevel">Access Level</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select select2" id="allowedRoles" name="allowed_roles[]" multiple>
                                <option value="Administrator">Administrator</option>
                                <option value="Manager">Manager</option>
                                <option value="Team Lead">Team Lead</option>
                                <option value="Employee">Employee</option>
                            </select>
                            <label for="allowedRoles">Allowed Roles</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Action Restrictions</label>
                            <div class="permission-matrix">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="noPrinting" name="no_printing">
                                    <label class="form-check-label" for="noPrinting">Disable Printing</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="noSharing" name="no_sharing">
                                    <label class="form-check-label" for="noSharing">Disable External Sharing</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="noDownload" name="no_download">
                                    <label class="form-check-label" for="noDownload">Disable Downloads</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="noEdit" name="no_edit">
                                    <label class="form-check-label" for="noEdit">Disable Editing</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Classification</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Usage Modal -->
    <div class="modal fade" id="viewUsageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Classification Usage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="audit-timeline">
                        <div class="mb-3">
                            <div class="timeline-point"></div>
                            <h6>Document: Project Proposal.pdf</h6>
                            <p class="mb-1">Applied by: John Doe</p>
                            <p class="mb-0 text-muted">2025-01-15 10:30 AM</p>
                        </div>
                        <div class="mb-3">
                            <div class="timeline-point"></div>
                            <h6>Document: Financial Report Q4.xlsx</h6>
                            <p class="mb-1">Applied by: Jane Smith</p>
                            <p class="mb-0 text-muted">2025-01-14 3:45 PM</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
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
            // Initialize DataTable
            $('#classifications-datatable').DataTable({
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    },
                    info: "Showing classifications _START_ to _END_ of _TOTAL_",
                    lengthMenu: "Display _MENU_ classifications"
                },
                pageLength: 10,
                columns: [
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

            // Initialize Select2 for multiple select
            $('#allowedRoles').select2({
                placeholder: "Select Roles",
                allowClear: true,
                dropdownParent: $('#addRestrictionModal'),
                width: '100%'
            });

            // Handle floating label behavior for select2
            $('#allowedRoles').on('select2:open', function() {
                $(this).parent().find('label').addClass('active');
            }).on('select2:close', function() {
                if (!$(this).val() || $(this).val().length === 0) {
                    $(this).parent().find('label').removeClass('active');
                }
            });
        });
    </script>
@endsection
