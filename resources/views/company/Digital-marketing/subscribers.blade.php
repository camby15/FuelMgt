@extends('layouts.vertical', ['page_title' => 'Subscribers'])

@section('css')

    <style>
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
        /* Add hover state for form controls */
        .form-floating input.form-control:hover ~ label,
        .form-floating select.form-select:hover ~ label {
            color: #000;
        }
        /* Ensure text is black when input has content */
        .form-floating input.form-control,
        .form-floating select.form-select {
            color: #000;
        }
        /* Style placeholder text */
        .form-floating input.form-control::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }
        .form-floating input.form-control:hover::placeholder {
            color: #000;
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
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item">Digital Marketing</li>
                            <li class="breadcrumb-item active">Subscribers</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Subscribers</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Action Buttons -->
                        <div class="mb-3 d-flex gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubscriberModal">
                                <i class="ri-add-line me-1"></i> Add Subscriber
                            </button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#sendEmailModal">
                                <i class="ri-mail-send-line me-1"></i> Send Email
                            </button>
                        </div>

                        <!-- Subscribers Table -->
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="subscribers-datatable">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                            </div>
                                        </th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Subscription Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Table content will be dynamically populated -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Subscriber Modal -->
    <div class="modal fade" id="addSubscriberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Subscriber</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSubscriberForm">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="subscriberName" placeholder=" ">
                            <label for="subscriberName">Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="subscriberEmail" placeholder=" ">
                            <label for="subscriberEmail">Email</label>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Subscriber</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Email Modal -->
    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Email to Subscribers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="sendEmailForm">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="emailSubject" placeholder=" ">
                            <label for="emailSubject">Subject</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Template</label>
                            <select class="form-select" id="emailTemplate">
                                <option value="">Choose a template...</option>
                                <!-- Templates will be populated dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" id="emailContent" rows="5"></textarea>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Send Email</button>
                        </div>
                    </form>
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
            $('#subscribers-datatable').DataTable({
                pageLength: 10,
                order: [[3, 'desc']], // Sort by subscription date by default
                columns: [
                    { orderable: false }, // Checkbox column
                    null,
                    null,
                    null,
                    null,
                    { orderable: false } // Actions column
                ]
            });

            // Handle "Select All" checkbox
            $('#selectAll').change(function() {
                $('.subscriber-checkbox').prop('checked', $(this).prop('checked'));
            });
        });
    </script>
@endsection
