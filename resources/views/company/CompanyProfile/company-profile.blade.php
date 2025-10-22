@extends('layouts.vertical', ['page_title' => 'Company Profile'])

@section('css')
    <link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .profile-header {
            background: var(--#{$prefix}card-bg);
            border-radius: 0.5rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--#{$prefix}border-color);
        }

        .company-logo-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 0.5rem;
            overflow: hidden;
            background: var(--#{$prefix}light);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--#{$prefix}border-color);
        }

        .company-logo {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .dropzone {
            border: 2px dashed var(--#{$prefix}border-color);
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            background: var(--#{$prefix}card-bg);
        }

        .dropzone:hover {
            border-color: var(--#{$prefix}primary);
        }

        .social-media-item {
            background: var(--#{$prefix}card-bg);
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .social-icon {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            margin-right: 1rem;
        }

        .document-item {
            background: var(--#{$prefix}card-bg);
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .document-icon {
            font-size: 2rem;
            margin-right: 1rem;
        }

        .location-card {
            background: var(--#{$prefix}card-bg);
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .custom-field {
            background: var(--#{$prefix}card-bg);
            border: 1px solid var(--#{$prefix}border-color);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .audit-log-item {
            border-bottom: 1px solid var(--#{$prefix}border-color);
            padding: 1rem 0;
        }

        .audit-log-item:last-child {
            border-bottom: none;
        }

        /* Dark mode specific styles */
        [data-bs-theme="dark"] {
            .profile-header,
            .social-media-item,
            .document-item,
            .location-card,
            .custom-field {
                background: var(--#{$prefix}gray-800);
                border-color: var(--#{$prefix}gray-700);
            }

            .company-logo-wrapper {
                background: var(--#{$prefix}gray-700);
                border-color: var(--#{$prefix}gray-600);
            }

            .dropzone {
                background: var(--#{$prefix}gray-800);
                border-color: var(--#{$prefix}gray-700);
            }

            .audit-log-item {
                border-color: var(--#{$prefix}gray-700);
            }
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
                        <button type="button" class="btn btn-primary" id="saveProfileChanges">
                            <i class="ri-save-line me-1"></i> Save Changes
                        </button>
                    </div>
                    <h4 class="page-title">Company Profile</h4>
                </div>
            </div>
        </div>

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="company-logo-wrapper">
                        <img src="{{ URL::asset('assets/images/logo-placeholder.png') }}" alt="Company Logo" class="company-logo" id="companyLogo">
                    </div>
                </div>
                <div class="col">
                    <h3 class="mb-1" id="displayCompanyName">Company Name</h3>
                    <p class="text-muted mb-2" id="displayIndustry">Industry Type</p>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success me-2">Active</span>
                        <span class="text-muted" id="displayEstablished">Established: January 2024</span>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#logoModal">
                        <i class="ri-camera-line me-1"></i> Change Logo
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Main Information -->
            <div class="col-xl-8">
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Basic Information</h5>
                        <form id="basicInfoForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" class="form-control" name="companyName">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Registration Number</label>
                                    <input type="text" class="form-control" name="regNumber">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tax ID (TIN)</label>
                                    <input type="text" class="form-control" name="taxId">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Industry</label>
                                    <select class="form-select" name="industry">
                                        <option>Technology</option>
                                        <option>Manufacturing</option>
                                        <option>Services</option>
                                        <option>Retail</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company Description</label>
                                <textarea class="form-control" rows="4" name="description"></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Contact Information</h5>
                        <form id="contactForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Primary Email</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Website</label>
                                <input type="url" class="form-control" name="website">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" rows="3" name="address"></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Business Hours</h5>
                        <div class="table-responsive">
                            <table class="table table-centered">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Opening Time</th>
                                        <th>Closing Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    <tr>
                                        <td>{{ $day }}</td>
                                        <td><input type="time" class="form-control" value="09:00"></td>
                                        <td><input type="time" class="form-control" value="17:00"></td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" checked>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Additional Information -->
            <div class="col-xl-4">
                <!-- Social Media Links -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Social Media</h5>
                        <div class="social-media-item d-flex align-items-center">
                            <div class="social-icon bg-primary-subtle text-primary">
                                <i class="ri-linkedin-fill"></i>
                            </div>
                            <input type="url" class="form-control" placeholder="LinkedIn URL">
                        </div>
                        <div class="social-media-item d-flex align-items-center">
                            <div class="social-icon bg-info-subtle text-info">
                                <i class="ri-twitter-fill"></i>
                            </div>
                            <input type="url" class="form-control" placeholder="Twitter URL">
                        </div>
                        <div class="social-media-item d-flex align-items-center">
                            <div class="social-icon bg-primary-subtle text-primary">
                                <i class="ri-facebook-fill"></i>
                            </div>
                            <input type="url" class="form-control" placeholder="Facebook URL">
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Documents</h5>
                        <div class="document-item">
                            <div class="d-flex align-items-center">
                                <i class="ri-file-pdf-line document-icon text-danger"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Business License</h6>
                                    <small class="text-muted">PDF, 2.3 MB</small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#"><i class="ri-download-line me-2"></i>Download</a>
                                        <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-light w-100" data-bs-toggle="modal" data-bs-target="#uploadDocModal">
                            <i class="ri-upload-line me-1"></i> Upload Document
                        </button>
                    </div>
                </div>

                <!-- Locations -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Locations</h5>
                        <div class="location-card">
                            <h6 class="mb-2">Headquarters</h6>
                            <p class="mb-1 text-muted">Akpor Foster lane.</p>
                            <small class="text-muted">East Legon, GH 00233</small>
                        </div>
                        <button type="button" class="btn btn-light w-100" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                            <i class="ri-map-pin-add-line me-1"></i> Add Location
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Log -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Audit Log</h5>
                        <div class="audit-log-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Profile Updated</h6>
                                    <small class="text-muted">Updated by Camby Omori on Jan 15, 2025</small>
                                </div>
                                <span class="badge bg-info-subtle text-info">Profile Change</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logo Upload Modal -->
    <div class="modal fade" id="logoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Company Logo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="dropzone" id="logoDropzone">
                        <div class="dz-message">
                            <i class="ri-upload-cloud-line display-4 mb-2"></i>
                            <h5>Drop files here or click to upload</h5>
                            <p class="text-muted">Supported formats: PNG, JPG, SVG (Max: 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Upload Modal -->
    <div class="modal fade" id="uploadDocModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Document Type</label>
                        <select class="form-select">
                            <option>Business License</option>
                            <option>Tax Document</option>
                            <option>Certificate</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="dropzone" id="documentDropzone">
                        <div class="dz-message">
                            <i class="ri-file-upload-line display-4 mb-2"></i>
                            <h5>Drop document here or click to upload</h5>
                            <p class="text-muted">Supported formats: PDF, DOC, DOCX (Max: 10MB)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Location Modal -->
    <div class="modal fade" id="addLocationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Location Name</label>
                            <input type="text" class="form-control" placeholder="e.g., Branch Office">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Location</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.form-select').select2({
                theme: 'bootstrap'
            });

            // Initialize Dropzone for logo
            var logoDropzone = new Dropzone("#logoDropzone", {
                url: "/upload/logo",
                maxFiles: 1,
                acceptedFiles: "image/*",
                maxFilesize: 2,
                createImageThumbnails: true
            });

            // Initialize Dropzone for documents
            var docDropzone = new Dropzone("#documentDropzone", {
                url: "/upload/document",
                maxFiles: 1,
                acceptedFiles: ".pdf,.doc,.docx",
                maxFilesize: 10
            });

            // Handle form submissions
            $('#saveProfileChanges').click(function() {
                // Implement save functionality
                alert('Changes saved successfully!');
            });

            // Handle document actions
            $('.document-action').click(function(e) {
                e.preventDefault();
                // Implement document actions
            });

            // Handle location management
            $('#addLocationForm').submit(function(e) {
                e.preventDefault();
                // Implement location addition
            });
        });
    </script>
@endsection
