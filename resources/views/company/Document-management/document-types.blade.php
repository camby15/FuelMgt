@extends('layouts.vertical', ['page_title' => 'Document Types'])

@section('css')

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

        /* Other existing styles */
        /* Camera preview */
        .camera-preview {
            width: 100%;
            height: 200px;
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.2rem;
            background-color: var(--#{$prefix}gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        [data-bs-theme="dark"] .camera-preview {
            background-color: var(--#{$prefix}gray-800);
            border-color: var(--#{$prefix}gray-700);
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDocTypeModal">
                            <i class="ri-add-line me-1"></i> Add Document Type
                        </button>
                    </div>
                    <h4 class="page-title">Document Types</h4>
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
                                    <input type="text" class="form-control" placeholder="Search document types...">
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
                                    <label class="form-label">Category</label>
                                    <select class="form-select">
                                        <option value="">All Categories</option>
                                        <option>Financial</option>
                                        <option>Legal</option>
                                        <option>Personal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Camera Requirement</label>
                                    <select class="form-select">
                                        <option value="">All</option>
                                        <option>Required</option>
                                        <option>Not Required</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Types Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="doctypes-datatable">
                                <thead>
                                    <tr>
                                        <th>Document Type</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Camera Required</th>
                                        <th>Status</th>
                                        <th>Last Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>ID Proof</td>
                                        <td>Government issued identification documents</td>
                                        <td>
                                            <span class="category-tag">Personal</span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" checked>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-active">Active</span></td>
                                        <td>2025-01-15</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editDocTypeModal">
                                                <i class="ri-pencil-line"></i>
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

    <!-- Add Document Type Modal -->
    <div class="modal fade" id="addDocTypeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Document Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" placeholder=" " required>
                            <label for="name">Document Type Name</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" placeholder=" " 
                                      style="height: 100px"></textarea>
                            <label for="description">Description</label>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category[]" multiple>
                                <option value="Financial">Financial</option>
                                <option value="Legal">Legal</option>
                                <option value="Personal">Personal</option>
                            </select>
                            <label for="category">Category</label>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cameraRequired" name="camera_required">
                                <label class="form-check-label" for="cameraRequired">Require Camera</label>
                            </div>
                        </div>

                        <div id="cameraSettings" class="mb-3" style="display: none;">
                            <label class="form-label">Camera Settings</label>
                            <div class="camera-preview">
                                <i class="ri-camera-line fs-2"></i>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="resolution" name="resolution">
                                            <option value="1280x720">HD (1280x720)</option>
                                            <option value="1920x1080">Full HD (1920x1080)</option>
                                            <option value="3840x2160">4K (3840x2160)</option>
                                        </select>
                                        <label for="resolution">Resolution</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="maxFileSize" 
                                               name="max_file_size" value="10" placeholder=" ">
                                        <label for="maxFileSize">Max File Size (MB)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Document Type</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Document Type Modal -->
    <div class="modal fade" id="editDocTypeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Document Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editDocTypeForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="edit_name" name="name" placeholder=" " required>
                            <label for="edit_name">Document Type Name</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="edit_description" name="description" placeholder=" " 
                                      style="height: 100px"></textarea>
                            <label for="edit_description">Description</label>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="edit_category" name="category[]" multiple>
                                <option value="Financial">Financial</option>
                                <option value="Legal">Legal</option>
                                <option value="Personal">Personal</option>
                            </select>
                            <label for="edit_category">Category</label>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit_cameraRequired" name="camera_required">
                                <label class="form-check-label" for="edit_cameraRequired">Require Camera</label>
                            </div>
                        </div>

                        <div id="edit_cameraSettings" class="mb-3" style="display: none;">
                            <label class="form-label">Camera Settings</label>
                            <div class="camera-preview">
                                <i class="ri-camera-line fs-2"></i>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="edit_resolution" name="resolution">
                                            <option value="1280x720">HD (1280x720)</option>
                                            <option value="1920x1080">Full HD (1920x1080)</option>
                                            <option value="3840x2160">4K (3840x2160)</option>
                                        </select>
                                        <label for="edit_resolution">Resolution</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="edit_maxFileSize" 
                                               name="max_file_size" value="10" placeholder=" ">
                                        <label for="edit_maxFileSize">Max File Size (MB)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables are loaded via main Vite bundle -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#doctypes-datatable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: true,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            });

            // Toggle camera settings
            $('#cameraRequired').change(function() {
                $('#cameraSettings').toggle(this.checked);
            });

            // Initialize camera preview when required
            function initializeCamera() {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then(function(stream) {
                            // Handle camera stream
                        })
                        .catch(function(error) {
                            console.error("Camera error:", error);
                        });
                }
            }

            // Handle status toggle
            $('.form-switch .form-check-input').change(function() {
                var status = $(this).prop('checked') ? 'enabled' : 'disabled';
                // Handle status change
            });
        });
    </script>
@endsection
