@extends('layouts.vertical', ['page_title' => 'Lead Management'])

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
        .lead-card {
            border-radius: 15px;
            margin-bottom: 1.5rem;
            transition: transform 0.3s;
        }
        .lead-card:hover {
            transform: translateY(-5px);
        }
        .status-badge {
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
        }
        .status-new {
            background-color: #0acf97;
            color: #fff;
        }
        .status-contacted {
            background-color: #727cf5;
            color: #fff;
        }
        .status-qualified {
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
                            <li class="breadcrumb-item">CRM</li>
                            <li class="breadcrumb-item active">Lead Management</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Lead Management</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Action Buttons -->
                        <div class="mb-3 d-flex gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLeadModal">
                                <i class="ri-add-line me-1"></i> Add New Lead
                            </button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#scheduleAppointmentModal">
                                <i class="ri-calendar-2-line me-1"></i> Schedule Appointment
                            </button>
                        </div>

                        <!-- Leads Table -->
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="leads-datatable">
                                <thead>
                                    <tr>
                                        <th>Lead Name</th>
                                        <th>Company</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Next Appointment</th>
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

    <!-- Add/Edit Lead Modal -->
    <div class="modal fade" id="addLeadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="leadForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="leadName" placeholder=" ">
                                    <label for="leadName">Lead Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="companyName" placeholder=" ">
                                    <label for="companyName">Company Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="leadEmail" placeholder=" ">
                                    <label for="leadEmail">Email Address</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control" id="leadPhone" placeholder=" ">
                                    <label for="leadPhone">Phone Number</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="leadStatus">
                                        <option value="">Select Status</option>
                                        <option value="new">New</option>
                                        <option value="contacted">Contacted</option>
                                        <option value="qualified">Qualified</option>
                                        <option value="proposal">Proposal</option>
                                        <option value="negotiation">Negotiation</option>
                                    </select>
                                    <label for="leadStatus">Lead Status</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="leadSource">
                                        <option value="">Select Source</option>
                                        <option value="website">Website</option>
                                        <option value="referral">Referral</option>
                                        <option value="social">Social Media</option>
                                        <option value="event">Event</option>
                                    </select>
                                    <label for="leadSource">Lead Source</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="leadNotes" style="height: 100px" placeholder=" "></textarea>
                            <label for="leadNotes">Notes</label>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Lead</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Appointment Modal -->
    <div class="modal fade" id="scheduleAppointmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="appointmentForm">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="appointmentLead">
                                <option value="">Select Lead</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                            <label for="appointmentLead">Select Lead</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="datetime-local" class="form-control" id="appointmentDateTime" placeholder=" ">
                            <label for="appointmentDateTime">Date & Time</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="appointmentType">
                                <option value="">Select Type</option>
                                <option value="call">Phone Call</option>
                                <option value="meeting">Meeting</option>
                                <option value="video">Video Call</option>
                            </select>
                            <label for="appointmentType">Appointment Type</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="appointmentNotes" style="height: 100px" placeholder=" "></textarea>
                            <label for="appointmentNotes">Notes</label>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="setReminder" checked>
                                <label class="form-check-label" for="setReminder">Set Reminder</label>
                            </div>
                        </div>
                        <div id="reminderOptions" class="mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="reminderTime">
                                    <option value="15">15 minutes before</option>
                                    <option value="30">30 minutes before</option>
                                    <option value="60">1 hour before</option>
                                    <option value="1440">1 day before</option>
                                </select>
                                <label for="reminderTime">Reminder Time</label>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Schedule</button>
                        </div>
                    </form>
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
            $('#leads-datatable').DataTable({
                pageLength: 10,
                order: [[0, 'asc']],
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

            // Toggle reminder options
            $('#setReminder').change(function() {
                $('#reminderOptions').toggle(this.checked);
            });

            // Form submissions
            $('#leadForm, #appointmentForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
                $(this).closest('.modal').modal('hide');
            });
        });
    </script>
@endsection
