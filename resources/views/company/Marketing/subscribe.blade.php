@extends('layouts.vertical', ['page_title' => 'Subscriber Management'])

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
        .subscriber-card {
            border-radius: 15px;
            margin-bottom: 1.5rem;
            transition: transform 0.3s;
        }
        .subscriber-card:hover {
            transform: translateY(-5px);
        }
        .status-badge {
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
        }
        .status-active {
            background-color: #0acf97;
            color: #fff;
        }
        .status-unsubscribed {
            background-color: #fa5c7c;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item">Digital Marketing</li>
                            <li class="breadcrumb-item active">Subscriber Management</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Subscriber Management</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Action Buttons -->
                        <div class="mb-3 d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn btn-danger me-2" id="unsubscribeSelected" disabled>
                                    <i class="ri-user-unfollow-line me-1"></i> Unsubscribe Selected
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="ri-filter-3-line me-1"></i> Filter
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">All Subscribers</a></li>
                                        <li><a class="dropdown-item" href="#">Active Subscribers</a></li>
                                        <li><a class="dropdown-item" href="#">Unsubscribed</a></li>
                                    </ul>
                                </div>
                            </div>
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subscription Date</th>
                                        <th>Last Campaign</th>
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

    <!-- Unsubscribe Confirmation Modal -->
    <div class="modal fade" id="unsubscribeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Unsubscribe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to unsubscribe the selected subscriber(s)? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmUnsubscribe">Unsubscribe</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['node_modules/datatables.net/js/jquery.dataTables.min.js', 'node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js'])

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#subscribers-datatable').DataTable({
                pageLength: 10,
                order: [[3, 'desc']],
                columns: [
                    { orderable: false },
                    null,
                    null,
                    null,
                    null,
                    null,
                    { orderable: false }
                ]
            });

            // Handle "Select All" checkbox
            $('#selectAll').change(function() {
                var checked = $(this).prop('checked');
                $('.subscriber-checkbox').prop('checked', checked);
                updateUnsubscribeButton();
            });

            // Handle individual checkboxes
            $(document).on('change', '.subscriber-checkbox', function() {
                updateUnsubscribeButton();

                // Update "Select All" checkbox
                var allChecked = $('.subscriber-checkbox:checked').length === $('.subscriber-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
            });

            // Update Unsubscribe button state
            function updateUnsubscribeButton() {
                var checkedCount = $('.subscriber-checkbox:checked').length;
                $('#unsubscribeSelected').prop('disabled', checkedCount === 0);
            }

            // Handle Unsubscribe button click
            $('#unsubscribeSelected').click(function() {
                $('#unsubscribeModal').modal('show');
            });

            // Handle Confirm Unsubscribe
            $('#confirmUnsubscribe').click(function() {
                // Add your unsubscribe logic here

                // Close modal after action
                $('#unsubscribeModal').modal('hide');

                // Refresh table or update status
                table.draw();
            });
        });
    </script>
@endsection
