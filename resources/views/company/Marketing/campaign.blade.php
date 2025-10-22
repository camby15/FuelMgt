@extends('layouts.vertical', ['page_title' => 'Campaign Management'])

@section('css')

    <style>
        .form-floating {
            position: relative;
            margin-bottom: 1rem;
        }
        .form-floating input.form-control,
        .form-floating select.form-select,
        .form-floating textarea.form-control {
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            background-color: transparent;
            font-size: 1rem;
            padding: 1rem 0.75rem;
            transition: all 0.8s;
        }
        .form-floating input.form-control,
        .form-floating select.form-select {
            height: 50px;
        }
        .form-floating textarea.form-control {
            min-height: 100px;
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
            color: #000;
            background-color: #fff;
            border-radius: 5px;
            z-index: 5;
        }
        /* Add hover state for form controls */
        .form-floating input.form-control:hover ~ label,
        .form-floating select.form-select:hover ~ label,
        .form-floating textarea.form-control:hover ~ label {
            color: #000;
        }
        /* Ensure text is black when input has content */
        .form-floating input.form-control,
        .form-floating select.form-select,
        .form-floating textarea.form-control {
            color: #000;
        }
        /* Style placeholder text */
        .form-floating input.form-control::placeholder,
        .form-floating textarea.form-control::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }
        .form-floating input.form-control:hover::placeholder,
        .form-floating textarea.form-control:hover::placeholder {
            color: #000;
        }
        .status-badge {
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
        }
        .status-draft {
            background-color: #ffc35a;
            color: #fff;
        }
        .status-scheduled {
            background-color: #0acf97;
            color: #fff;
        }
        .status-sent {
            background-color: #727cf5;
            color: #fff;
        }
        .status-failed {
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
                            <li class="breadcrumb-item active">Campaign Management</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Campaign Management</h4>
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
                                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addCampaignModal">
                                    <i class="ri-add-line me-1"></i> Create Campaign
                                </button>
                            </div>
                            <div class="d-flex">
                                <div class="btn-group me-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="ri-filter-3-line me-1"></i> Filter
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">All Campaigns</a></li>
                                        <li><a class="dropdown-item" href="#">Draft</a></li>
                                        <li><a class="dropdown-item" href="#">Scheduled</a></li>
                                        <li><a class="dropdown-item" href="#">Sent</a></li>
                                        <li><a class="dropdown-item" href="#">Failed</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Campaigns Table -->
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="campaigns-datatable">
                                <thead>
                                    <tr>
                                        <th>Campaign Name</th>
                                        <th>Template</th>
                                        <th>Target Group</th>
                                        <th>Schedule</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
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

    <!-- Add Campaign Modal -->
    <div class="modal fade" id="addCampaignModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Campaign</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="campaignForm">
                        <!-- Campaign Information -->
                        <div class="mb-4">
                            <h6 class="fw-medium mb-3">Campaign Information</h6>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="campaignName" name="campaign_name" placeholder=" ">
                                <label for="campaignName">Campaign Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="description" name="description" placeholder=" "></textarea>
                                <label for="description">Campaign Description</label>
                            </div>
                        </div>

                        <!-- Template Selection -->
                        <div class="mb-4">
                            <h6 class="fw-medium mb-3">Email Template</h6>
                            <div class="form-floating mb-3">
                                <select class="form-select" id="template" name="template_id">
                                    <option value="">Select Template</option>
                                    @forelse($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @empty
                                        <option value="">No templates available</option>
                                    @endforelse
                                </select>
                                <label for="template">Select Template</label>
                            </div>
                        </div>

                        <!-- Target Group -->
                        <div class="mb-4">
                            <h6 class="fw-medium mb-3">Target Audience</h6>
                            <div class="form-floating mb-3">
                                <select class="form-select" id="targetGroup" name="group_id">
                                    <option value="">Select Group</option>
                                    @forelse($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @empty
                                        <option value="">No groups available</option>
                                    @endforelse
                                </select>
                                <label for="targetGroup">Target Group</label>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div class="mb-4">
                            <h6 class="fw-medium mb-3">Campaign Schedule</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="scheduleDate" name="schedule_date" placeholder=" ">
                                        <label for="scheduleDate">Schedule Date</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="time" class="form-control" id="scheduleTime" name="schedule_time" placeholder=" ">
                                        <label for="scheduleTime">Schedule Time</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="sendNow" name="send_now">
                                <label class="form-check-label" for="sendNow">Send Immediately</label>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Campaign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables loaded via main Vite bundle -->

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#campaigns-datatable').DataTable({
                pageLength: 10,
                order: [[5, 'desc']],
                columns: [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    { orderable: false }
                ]
            });

            // Handle send now checkbox
            $('#sendNow').change(function() {
                if($(this).is(':checked')) {
                    $('#scheduleDate, #scheduleTime').prop('disabled', true);
                } else {
                    $('#scheduleDate, #scheduleTime').prop('disabled', false);
                }
            });

            // Campaign form submission
            $('#campaignForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
                $(this).closest('.modal').modal('hide');
            });
        });
    </script>
@endsection
