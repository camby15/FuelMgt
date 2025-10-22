@extends('layouts.vertical', ['page_title' => 'Newsletter Templates'])

@section('css')

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Your existing CSS styles here */
        .form-floating {
            position: relative;
            margin-bottom: 1rem;
        }
        .form-floating input.form-control,
        .form-floating select.form-select {
            height: 50px;
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            background-color: transparent;
            font-size: 1rem; 
            padding: 1rem 0.75rem;
            transition: all 0.8s;
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
        .form-floating select.form-select:not([value=""]) {
            border-color: #033c42;
            box-shadow: none;
        }
        .form-floating input.form-control:focus ~ label,
        .form-floating input.form-control:not(:placeholder-shown) ~ label,
        .form-floating select.form-select:focus ~ label,
        .form-floating select.form-select:not([value=""]) ~ label {
            height: auto;
            padding: 0 0.5rem;
            transform: translateY(-50%) translateX(0.5rem) scale(0.85);
            color: #000;
            background-color: #fff;
            border-radius: 5px;
            z-index: 5;
        }
        .modal-body {
            background: none;
        }
        .modal-content {
            background: var(--bs-modal-bg);
        }
    </style>
@endsection

@section('content')

        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item">Digital Marketing</li>
                            <li class="breadcrumb-item active">Newsletter Templates</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Newsletter Templates</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Newsletter Templates</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                            <i class="ri-add-line align-middle me-1"></i>
                            Add New Template
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Templates Table -->
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="templates-datatable">
                                <thead>
                                    <tr>
                                        <th>Template Name</th>
                                        <th>Subject</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($templates as $template)
                                        <tr>
                                            <td>{{ $template->name }}</td>
                                            <td>{{ $template->subject }}</td>
                                            <td>{{ $template->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge {{ $template->template_status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $template->template_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" 
                                                            class="btn btn-warning btn-sm edit-template" 
                                                            data-template-id="{{ $template->id }}"
                                                            data-template-name="{{ $template->name }}"
                                                            data-template-subject="{{ $template->subject }}"
                                                            data-template-content="{{ $template->content }}"
                                                            data-template-status="{{ $template->template_status }}"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editTemplateModal">
                                                        <i class="ri-edit-line"></i>
                                                    </button>

                                                    <button type="button" 
                                                            class="btn btn-primary btn-sm preview-template" 
                                                            data-template-id="{{ $template->id }}"
                                                            data-template-name="{{ $template->name }}"
                                                            data-template-subject="{{ $template->subject }}"
                                                            data-template-content="{{ $template->content }}"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#previewActionModal">
                                                        <i class="ri-eye-line"></i>
                                                    </button>

                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm delete-template" 
                                                            data-template-id="{{ $template->id }}">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Preview Modal -->
                                        <div class="modal fade" id="previewTemplateModal{{ $template->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Preview: {{ $template->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h3>{{ $template->subject }}</h3>
                                                        <div>{!! $template->content !!}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No newsletter templates found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Template Modal -->
        <div class="modal fade" id="createTemplateModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Newsletter Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createTemplateForm" action="{{ route('company-newsletters.store') }}" method="POST" onsubmit="return false;">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="createTemplateName" name="name" placeholder="Template Name" required>
                                        <label for="createTemplateName">Template Name</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="createTemplateSubject" name="subject" placeholder="Email Subject" required>
                                        <label for="createTemplateSubject">Email Subject</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="createTemplateContent" class="form-label">Template Content</label>
                                    <textarea class="form-control" id="createTemplateContent" name="content" rows="10" required></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-floating">
                                        <select class="form-select" id="createTemplateStatus" name="template_status" required>
                                            <option value="draft">Draft</option>
                                            <option value="active">Active</option>
                                        </select>
                                        <label for="createTemplateStatus">Template Status</label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Create Template</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Template Modal -->
        <div class="modal fade" id="editTemplateModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Newsletter Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editTemplateForm" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editTemplateId" name="template_id">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="editTemplateName" name="name" placeholder="Template Name" required>
                                        <label for="editTemplateName">Template Name</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="editTemplateSubject" name="subject" placeholder="Email Subject" required>
                                        <label for="editTemplateSubject">Email Subject</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="editTemplateContent" class="form-label">Template Content</label>
                                    <textarea class="form-control" id="editTemplateContent" name="content" rows="10" required></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-floating">
                                        <select class="form-select" id="editTemplateStatus" name="template_status" required>
                                            <option value="draft">Draft</option>
                                            <option value="active">Active</option>
                                        </select>
                                        <label for="editTemplateStatus">Template Status</label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary preview-edit-template" data-bs-toggle="modal" data-bs-target="#previewEditModal">
                                    Preview
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Modal for Edit -->
        <div class="modal fade" id="previewEditModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Preview Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h3 id="previewEditSubject"></h3>
                        <div id="previewEditContent"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Modal for View Action -->
        <div class="modal fade" id="previewActionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Template Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h3 id="previewActionSubject"></h3>
                        <div id="previewActionContent"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <!-- Load jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables are loaded via main Vite bundle -->

    <!-- Load SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global variable for DataTable instance
        let templateTable;

        // Function to initialize DataTable
        function initializeDataTable() {
            // Destroy existing instance if it exists
            if ($.fn.DataTable.isDataTable('#templates-datatable')) {
                $('#templates-datatable').DataTable().destroy();
            }

            // Initialize new instance
            templateTable = $('#templates-datatable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: true,
                stateSave: true,
                drawCallback: function() {
                    $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                }
            });

            return templateTable;
        }

        $(document).ready(function() {
            // Initialize DataTable
            const table = initializeDataTable();

            // Create Template Form Handler
            $('#createTemplateForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');

                // Disable submit button to prevent double submission
                if (submitBtn.prop('disabled')) {
                    return false;
                }

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        submitBtn.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            // Close the modal and reset form
                            $('#createTemplateModal').modal('hide');
                            form[0].reset();

                            // Format the date
                            const date = new Date(response.data.created_at);
                            const formattedDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });

                            // Add the new row to DataTable
                            const newRow = [
                                response.data.name,
                                response.data.subject,
                                formattedDate,
                                `<span class="badge ${response.data.template_status === 'active' ? 'bg-success' : 'bg-secondary'}">${response.data.template_status}</span>`,
                                `<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-warning btn-sm edit-template" data-bs-toggle="modal" data-bs-target="#editTemplateModal" data-template-id="${response.data.id}" data-template-name="${response.data.name}" data-template-subject="${response.data.subject}" data-template-content="${response.data.content}" data-template-status="${response.data.template_status}">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm preview-template" data-template-id="${response.data.id}" data-template-name="${response.data.name}" data-template-subject="${response.data.subject}" data-template-content="${response.data.content}" data-bs-toggle="modal" data-bs-target="#previewActionModal">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm delete-template" data-template-id="${response.data.id}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>`
                            ];
                            table.row.add(newRow).draw();

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        if (response.errors) {
                            let errorMessage = '<ul class="list-unstyled">';
                            Object.values(response.errors).forEach(error => {
                                errorMessage += `<li>${error}</li>`;
                            });
                            errorMessage += '</ul>';

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: errorMessage
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response?.message || 'Something went wrong!'
                            });
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                    }
                });
            });

            // Preview Edit Template Handler
            $('.preview-edit-template').on('click', function() {
                const subject = $('#editTemplateSubject').val();
                const content = $('#editTemplateContent').val();

                $('#previewEditSubject').text(subject);
                $('#previewEditContent').html(content);
            });

            // Edit Template Button Handler
            $(document).on('click', '.edit-template', function() {
                const templateId = $(this).data('template-id');
                const templateName = $(this).data('template-name');
                const templateSubject = $(this).data('template-subject');
                const templateContent = $(this).data('template-content');
                const templateStatus = $(this).data('template-status');

                // Set form action
                $('#editTemplateForm').attr('action', `/company/newsletters/${templateId}`);

                // Populate form fields
                $('#editTemplateId').val(templateId);
                $('#editTemplateName').val(templateName);
                $('#editTemplateSubject').val(templateSubject);
                $('#editTemplateContent').val(templateContent);
                $('#editTemplateStatus').val(templateStatus);

                // Update preview data attributes
                $('.preview-edit-template').data({
                    'template-id': templateId,
                    'template-name': templateName,
                    'template-subject': templateSubject,
                    'template-content': templateContent
                });
            });

            // Edit Template Form Handler
            $('#editTemplateForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const templateId = form.find('input[name="template_id"]').val();
                const submitBtn = form.find('button[type="submit"]');

                // Disable submit button to prevent double submission
                if (submitBtn.prop('disabled')) {
                    return false;
                }

                $.ajax({
                    url: `/company/newsletters/${templateId}`,
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        submitBtn.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            // Close the modal and reset form
                            $('#editTemplateModal').modal('hide');
                            form[0].reset();

                            // Format the date
                            const date = new Date(response.data.created_at);
                            const formattedDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });

                            // Find and update the row in DataTable
                            const row = table.row($(`button[data-template-id="${templateId}"]`).closest('tr'));
                            const newData = [
                                response.data.name,
                                response.data.subject,
                                formattedDate,
                                `<span class="badge ${response.data.template_status === 'active' ? 'bg-success' : 'bg-secondary'}">${response.data.template_status}</span>`,
                                `<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-warning btn-sm edit-template" data-bs-toggle="modal" data-bs-target="#editTemplateModal" data-template-id="${response.data.id}" data-template-name="${response.data.name}" data-template-subject="${response.data.subject}" data-template-content="${response.data.content}" data-template-status="${response.data.template_status}">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm preview-template" data-template-id="${response.data.id}" data-template-name="${response.data.name}" data-template-subject="${response.data.subject}" data-template-content="${response.data.content}" data-bs-toggle="modal" data-bs-target="#previewActionModal">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm delete-template" data-template-id="${response.data.id}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>`
                            ];
                            row.data(newData).draw();

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        if (response.errors) {
                            let errorMessage = '<ul class="list-unstyled">';
                            Object.values(response.errors).forEach(error => {
                                errorMessage += `<li>${error}</li>`;
                            });
                            errorMessage += '</ul>';

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: errorMessage
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response?.message || 'Something went wrong!'
                            });
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                    }
                });
            });

            // Live Preview on Content Change
            $('#editTemplateSubject, #editTemplateContent').on('input', function() {
                if ($('#previewEditModal').is(':visible')) {
                    const subject = $('#editTemplateSubject').val();
                    const content = $('#editTemplateContent').val();

                    $('#previewEditSubject').text(subject);
                    $('#previewEditContent').html(content);
                }
            });

            // Delete Template Handler
            $(document).on('click', '.delete-template', function() {
                const templateId = $(this).data('template-id');
                const button = $(this);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/company/newsletters/${templateId}`,
                            type: 'POST',
                            data: {
                                '_method': 'DELETE',
                                '_token': '{{ csrf_token() }}'
                            },
                            beforeSend: function() {
                                button.prop('disabled', true);
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Remove row from DataTable
                                    table.row(button.closest('tr')).remove().draw();

                                    // Show success message
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    );
                                }
                            },
                            error: function(xhr) {
                                const response = xhr.responseJSON;
                                Swal.fire(
                                    'Error!',
                                    response?.message || 'Something went wrong!',
                                    'error'
                                );
                            },
                            complete: function() {
                                button.prop('disabled', false);
                            }
                        });
                    }
                });
            });

            // Preview Template Handler (Action Button)
            $(document).on('click', '.preview-template', function() {
                const templateSubject = $(this).data('template-subject');
                const templateContent = $(this).data('template-content');

                $('#previewActionSubject').text(templateSubject);
                $('#previewActionContent').html(templateContent);
            });
        });
    </script>
@endsection
