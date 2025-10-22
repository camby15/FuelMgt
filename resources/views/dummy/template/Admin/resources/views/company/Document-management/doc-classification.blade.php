@extends('layouts.vertical', ['page_title' => 'Document Classification'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
           'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
           'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
           'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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

        /* Action Button Styles */
        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        
        .action-btn i {
            font-size: 0.875rem;
        }

        /* Classification Specific Styles */
        .doc-type-card {
            border: 1px solid var(--ct-border-color);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background: var(--ct-card-bg);
            transition: all 0.3s ease;
        }

        .doc-type-card:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--ct-border-color);
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--ct-body-color);
            margin: 0;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .restriction-list {
            margin-top: 1rem;
        }

        .restriction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: var(--ct-card-bg);
            border: 1px solid var(--ct-border-color);
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .restriction-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .restriction-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--ct-primary);
            color: white;
        }

        .form-check-input {
            width: 2.5em;
            height: 1.25em;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDocTypeModal">
                            <i class="fa-solid fa-plus-circle me-1"></i> Add Document Type
                        </button>
                    </div>
                    <h4 class="page-title">Document Types</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="doc-types-container">
                            @if(isset($documentTypes) && count($documentTypes) > 0)
                                @foreach($documentTypes as $docType)
                                    <div class="doc-type-card">
                                        <div class="card-header">
                                            <h5 class="card-title">{{ $docType->name }}</h5>
                                            <div class="card-actions">
                                                <button type="button" class="btn btn-sm btn-info action-btn"
                                                        onclick="editDocType('{{ $docType->id }}')"
                                                        title="Edit Document Type">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger action-btn"
                                                        onclick="deleteDocType('{{ $docType->id }}')"
                                                        title="Delete Document Type">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="doc-type-details">
                                            <p class="mb-2">{{ $docType->description }}</p>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="form-check form-switch me-3">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="camera_required_{{ $docType->id }}"
                                                           {{ $docType->camera_required ? 'checked' : '' }}
                                                           onchange="toggleCamera('{{ $docType->id }}')">
                                                    <label class="form-check-label" for="camera_required_{{ $docType->id }}">
                                                        Camera Required
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="restriction-list">
                                                <h6 class="mb-2">Restrictions</h6>
                                                @foreach($docType->restrictions as $restriction)
                                                    <div class="restriction-item">
                                                        <div class="restriction-info">
                                                            <div class="restriction-icon">
                                                                <i class="fa-solid fa-shield-alt"></i>
                                                            </div>
                                                            <span>{{ $restriction->name }}</span>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger action-btn"
                                                                onclick="removeRestriction('{{ $docType->id }}', '{{ $restriction->id }}')"
                                                                title="Remove Restriction">
                                                            <i class="fa-solid fa-times"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                                <button type="button" class="btn btn-sm btn-success mt-2"
                                                        onclick="addRestriction('{{ $docType->id }}')"
                                                        title="Add Restriction">
                                                    <i class="fa-solid fa-plus me-1"></i> Add Restriction
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <p>No document types found. Create your first document type to get started.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Document Type Modal -->
    <div class="modal fade" id="docTypeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Document Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="docTypeForm"  method="POST"> // TODO: Update route
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="doc_type_name" name="name" placeholder=" " required>
                            <label for="doc_type_name">Document Type Name</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control" id="doc_type_description" name="description" placeholder=" "></textarea>
                            <label for="doc_type_description">Description</label>
                        </div>
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="camera_required" name="camera_required">
                            <label class="form-check-label" for="camera_required">Camera Required</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Document Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Restriction Modal -->
    <div class="modal fade" id="restrictionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Restriction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="restrictionForm"  method="POST">       <!-- ROUTE TO STORE INTO DATABASE HERE. HSW 03/01/25 -->
                    @csrf
                    <input type="hidden" id="doc_type_id" name="document_type_id">
                    <div class="modal-body">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="restriction_name" name="name" placeholder=" " required>
                            <label for="restriction_name">Restriction Name</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control" id="restriction_description" name="description" placeholder=" "></textarea>
                            <label for="restriction_description">Description</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Restriction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Handle document type form submission
            $('#docTypeForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#docTypeModal').modal('hide');
                            form[0].reset();
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Error creating document type');
                    }
                });
            });

            // Handle restriction form submission
            $('#restrictionForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#restrictionModal').modal('hide');
                            form[0].reset();
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Error adding restriction');
                    }
                });
            });
        });

        function editDocType(id) {
            $.get(`/document-types/${id}/edit`, function(data) {
                $('#docTypeForm').attr('action', `/document-types/${id}`);
                $('#docTypeForm').append('@method("PUT")');
                $('#doc_type_name').val(data.name);
                $('#doc_type_description').val(data.description);
                $('#camera_required').prop('checked', data.camera_required);
                $('.modal-title').text('Edit Document Type');
                $('#docTypeModal').modal('show');
            });
        }

        function deleteDocType(id) {
            if (confirm('Are you sure you want to delete this document type?')) {
                $.ajax({
                    url: `/document-types/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        }
                    },
                    error: function() {
                        alert('Error deleting document type');
                    }
                });
            }
        }

        function toggleCamera(id) {
            const isChecked = $(`#camera_required_${id}`).prop('checked');
            
            $.ajax({
                url: `/document-types/${id}/toggle-camera`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    camera_required: isChecked
                },
                error: function() {
                    alert('Error updating camera requirement');
                    $(`#camera_required_${id}`).prop('checked', !isChecked);
                }
            });
        }

        function addRestriction(docTypeId) {
            $('#doc_type_id').val(docTypeId);
            $('#restrictionForm')[0].reset();
            $('#restrictionModal').modal('show');
        }

        function removeRestriction(docTypeId, restrictionId) {
            if (confirm('Are you sure you want to remove this restriction?')) {
                $.ajax({
                    url: `/document-types/${docTypeId}/restrictions/${restrictionId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        }
                    },
                    error: function() {
                        alert('Error removing restriction');
                    }
                });
            }
        }
    </script>
@endsection
