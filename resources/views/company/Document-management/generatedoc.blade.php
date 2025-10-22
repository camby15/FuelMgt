@extends('layouts.vertical', ['page_title' => 'Generate Documents'])

@section('css')

    <link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
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

        /* Editor styling */
        .ql-editor {
            min-height: 200px;
            background-color: var(--#{$prefix}input-bg);
            color: var(--#{$prefix}body-color);
        }

        [data-bs-theme="dark"] .ql-editor {
            background-color: var(--#{$prefix}gray-800);
            color: var(--#{$prefix}gray-200);
        }

        [data-bs-theme="dark"] .ql-toolbar {
            background-color: var(--#{$prefix}gray-700);
            border-color: var(--#{$prefix}border-color);
        }

        [data-bs-theme="dark"] .ql-picker-label {
            color: var(--#{$prefix}gray-200);
        }

        /* Template card */
        .template-card {
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.2rem;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .template-card:hover {
            border-color: var(--#{$prefix}primary);
            box-shadow: 0 0 0 0.2rem rgba(var(--#{$prefix}primary-rgb), 0.25);
        }

        [data-bs-theme="dark"] .template-card {
            background-color: var(--#{$prefix}gray-800);
            border-color: var(--#{$prefix}gray-700);
        }

        /* Preview panel */
        .preview-panel {
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.2rem;
            padding: 1rem;
            min-height: 500px;
            background-color: var(--#{$prefix}body-bg);
        }

        [data-bs-theme="dark"] .preview-panel {
            background-color: var(--#{$prefix}gray-800);
            border-color: var(--#{$prefix}gray-700);
        }

        /* Dynamic fields */
        .dynamic-field {
            background-color: var(--#{$prefix}light);
            border: 1px dashed var(--#{$prefix}primary);
            border-radius: 0.2rem;
            padding: 0.2rem 0.5rem;
            margin: 0 0.2rem;
            cursor: move;
            display: inline-block;
        }

        [data-bs-theme="dark"] .dynamic-field {
            background-color: var(--#{$prefix}gray-700);
            color: var(--#{$prefix}gray-200);
        }

        /* Version timeline */
        .version-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .version-timeline::before {
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

        /* Watermark preview */
        .watermark-preview {
            position: relative;
            min-height: 100px;
            border: 1px dashed var(--#{$prefix}border-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.5;
        }

        /* Collaboration indicator */
        .collaboration-indicator {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            background-color: var(--#{$prefix}success-bg-subtle);
            color: var(--#{$prefix}success);
            z-index: 1000;
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
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" id="saveDocument">
                                <i class="ri-save-line me-1"></i> Save
                            </button>
                            <button type="button" class="btn btn-success" id="generateDocument">
                                <i class="ri-file-download-line me-1"></i> Generate
                            </button>
                        </div>
                    </div>
                    <h4 class="page-title">Generate Document</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left sidebar -->
            <div class="col-xl-3 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <!-- Document Info -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="docTitle" placeholder="Enter title">
                            <label for="docTitle">Document Title</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select" id="docType">
                                <option value="">Select document type</option>
                                <option value="invoice">Invoice</option>
                                <option value="contract">Contract</option>
                                <option value="report">Report</option>
                                <option value="letter">Letter</option>
                            </select>
                            <label for="docType">Document Type</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="docDescription" placeholder="Enter description" rows="3"></textarea>
                            <label for="docDescription">Description</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select" id="docCategories" multiple>
                                <option value="financial">Financial</option>
                                <option value="legal">Legal</option>
                                <option value="hr">HR</option>
                                <option value="marketing">Marketing</option>
                            </select>
                            <label for="docCategories">Categories</label>
                        </div>

                        <!-- Templates -->
                        <h5 class="mt-4">Templates</h5>
                        <div class="template-list">
                            <div class="template-card">
                                <h6 class="mb-1">Basic Invoice</h6>
                                <p class="mb-0 text-muted">Standard invoice template</p>
                            </div>
                            <div class="template-card">
                                <h6 class="mb-1">Business Contract</h6>
                                <p class="mb-0 text-muted">Legal agreement template</p>
                            </div>
                        </div>

                        <!-- Dynamic Fields -->
                        <h5 class="mt-4">Dynamic Fields</h5>
                        <div class="dynamic-fields-list">
                            <span class="dynamic-field" draggable="true">{user.name}</span>
                            <span class="dynamic-field" draggable="true">{company.name}</span>
                            <span class="dynamic-field" draggable="true">{date}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content area -->
            <div class="col-xl-9 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Editor Toolbar -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light" id="previewToggle">
                                        <i class="ri-eye-line"></i> Preview
                                    </button>
                                    <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="ri-download-line"></i> Export As
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">PDF</a>
                                        <a class="dropdown-item" href="#">Word</a>
                                        <a class="dropdown-item" href="#">HTML</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#versionHistoryModal">
                                    <i class="ri-history-line"></i> History
                                </button>
                            </div>
                        </div>

                        <!-- Editor -->
                        <div id="editor"></div>

                        <!-- Preview Panel (initially hidden) -->
                        <div class="preview-panel" style="display: none;">
                            <div class="watermark-preview">
                                <span class="text-muted">DRAFT</span>
                            </div>
                            <div id="previewContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Version History Modal -->
    <div class="modal fade" id="versionHistoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Version History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="version-timeline">
                        <div class="mb-3">
                            <div class="timeline-point"></div>
                            <h6>Version 1.2</h6>
                            <p class="mb-1">Updated by: John Doe</p>
                            <p class="mb-0 text-muted">2025-01-15 3:30 PM</p>
                        </div>
                        <div class="mb-3">
                            <div class="timeline-point"></div>
                            <h6>Version 1.1</h6>
                            <p class="mb-1">Updated by: Jane Smith</p>
                            <p class="mb-0 text-muted">2025-01-15 2:15 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Collaboration Indicator -->
    <div class="collaboration-indicator">
        <i class="ri-group-line me-1"></i> 2 users editing
    </div>
@endsection

@section('script')
    @vite(['node_modules/datatables.net/js/jquery.dataTables.min.js', 'node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js'])
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/quill/quill.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/dropzone/dropzone.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.form-select').each(function() {
                $(this).select2({
                    placeholder: $(this).find('option:first').text(),
                    allowClear: true,
                    dropdownParent: $(this).closest('.modal').length ? $(this).closest('.modal') : $('body'),
                    width: '100%'
                });

                // Handle floating label behavior
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

            // Initialize Quill editor
            var quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            });

            // Handle drag and drop for dynamic fields
            $('.dynamic-field').on('dragstart', function(e) {
                e.originalEvent.dataTransfer.setData('text/plain', $(this).text());
            });

            // Preview toggle
            $('#previewToggle').click(function() {
                $('.preview-panel').toggle();
                $(this).find('i').toggleClass('ri-eye-line ri-eye-off-line');
            });

            // Save document
            $('#saveDocument').click(function() {
                // Add your save logic here
                console.log('Saving document...');
            });

            // Generate document
            $('#generateDocument').click(function() {
                // Add your generate logic here
                console.log('Generating document...');
            });
        });
    </script>
@endsection
