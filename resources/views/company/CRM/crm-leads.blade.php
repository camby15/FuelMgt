<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<style>
    .action-btn {
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        background-color: #0dcaf0 !important;  /* Bootstrap info color */
        border-color: #0dcaf0 !important;
        color: #fff !important;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .action-btn:hover {
        opacity: 0.9 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }
    
    .action-btn i {
        font-size: 14px;
        color: #fff !important;
    }

    /* Override any Bootstrap btn-info styles */
    .btn.btn-info.action-btn {
        background-color: #0dcaf0 !important;
        border-color: #0dcaf0 !important;
        color: #fff !important;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card crm-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="header-title mb-0">Lead Management</h4>
                    <div>
                        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addLeadModal">
                            <i class="fas fa-plus me-1"></i> Add New Lead
                        </button>
                        <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                            <i class="fa-solid fa-upload me-1"></i> Bulk Upload
                        </button>
                        <a href="{{ route('company.leads.download-template') }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-download me-1"></i> Download Template
                        </a>
                        <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#exportLeadsModal">
                            <i class="fa-solid fa-file-export me-1"></i> Export Leads
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-hover dt-responsive nowrap w-100" id="leads-datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Contact Person</th>
                                <th>Contact Person Email</th>
                                <th>Contact Person Phone</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                           
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation example">
                        <ul class="pagination" id="leadspagination">
                          
                        </ul>
                    </nav>


                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="mb-3">Scheduled Appointments</h5>
                            <div class="table-responsive">
                                <table class="table table-centered table-hover w-100" id="appointments-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Lead Name</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Type</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Appointment rows will be inserted here by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                 
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Lead Modal -->
<div class="modal fade" id="addLeadModal" tabindex="-1" aria-labelledby="addLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLeadModalLabel">Add New Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addLeadForm" action="{{ route('company.leads.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="name" id="leadName" placeholder=" " required>
                                <label for="leadName">Full Name *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="email" id="leadEmail" placeholder=" " required>
                                <label for="leadEmail">Email *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="phone" id="leadPhone" placeholder=" " required>
                                <label for="leadPhone">Phone *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="source" id="leadSource" required>
                                    <option value="" selected disabled>Select source</option>
                                    <option value="Website">Website</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Social Media">Social Media</option>
                                    <option value="Direct">Direct</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label for="leadSource">Source *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="contact_person" id="leadContactPerson" placeholder=" " required>
                                <label for="leadContactPerson">Contact Person *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="contact_person_email" id="leadContactPersonEmail" placeholder=" " required>
                                <label for="leadContactPersonEmail">Contact Person Email *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="contact_person_phone" id="leadContactPersonPhone" placeholder=" " required>
                                <label for="leadContactPersonPhone">Contact Person Phone *</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="leadNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="leadNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Appointment Modal -->
<div class="modal fade" id="scheduleAppointmentModal" tabindex="-1" aria-labelledby="scheduleAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="scheduleAppointmentForm" action="{{ route('company.leads.schedule-appointment') }}" method="POST">
                @csrf
                <input type="hidden" name="lead_id" id="appointmentLeadId">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleAppointmentModalLabel">Schedule Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="appointmentLeadName" class="form-label">Appointment Name (Lead)</label>
                        <input type="text" class="form-control" id="appointmentLeadName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_date" class="form-label">Date *</label>
                        <input type="date" class="form-control" name="appointment_date" id="appointment_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_time" class="form-label">Time *</label>
                        <input type="time" class="form-control" name="appointment_time" id="appointment_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_type" class="form-label">Type *</label>
                        <select class="form-select" name="appointment_type" id="appointment_type" required>
                            <option value="" disabled selected>Select type</option>
                            <option value="Call">Call</option>
                            <option value="Meeting">Meeting</option>
                            <option value="Video Conference">Video Conference</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_notes" class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" id="appointment_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Lead Modal -->
<div class="modal fade" id="editLeadModal" tabindex="-1" aria-labelledby="editLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLeadModalLabel">Edit Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLeadForm" action="{{ route('company.leads.update', ':lead_id') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="lead_id" id="editLeadId" value="">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="name" id="editName" placeholder=" " required>
                                <label for="editName">Full Name *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="email" id="editEmail" placeholder=" " required>
                                <label for="editEmail">Email *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="phone" id="editPhone" placeholder=" " required>
                                <label for="editPhone">Phone *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="source" id="editSource">
                                    <option value="" selected disabled>Select source</option>
                                    <option value="Website">Website</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Social Media">Social Media</option>
                                    <option value="Direct">Direct</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label for="editSource">Source *</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="contact_person" id="edit_contact_person" placeholder="Contact Person" required>
                            <label for="edit_contact_person">Contact Person *</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="contact_person_email" id="edit_contact_person_email" placeholder="Contact Person Email" required>
                            <label for="edit_contact_person_email">Contact Person Email *</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="contact_person_phone" id="edit_contact_person_phone" placeholder="Contact Person Phone" required>
                            <label for="edit_contact_person_phone">Contact Person Phone *</label>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="editNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="editNotes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Global flag to prevent multiple alerts
    window.isAlertShowing = false;
    function fetchLeadData(page = 1) {

    
    $.ajax({
        url: `/company/leads/all?page=${page}`, 
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            let tableBody = '';
            
            // Number of leads
            response.data.forEach((lead, index) => {
                // Calculate serial number based on pagination
                let serialNumber = (response.pagination.per_page * (response.pagination.current_page - 1)) + (index + 1);
                
                // Format created date
                let createdDate = new Date(lead.created_at);
                let formattedDate = createdDate.toLocaleDateString('en-US', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
                
                tableBody += `<tr>
                    <td>${serialNumber}</td>
                    <td>${lead.name}</td>
                    <td>${lead.email}</td>
                    <td>${lead.phone}</td>
                    <td>${lead.contact_person || '-'}</td>
                    <td>${lead.contact_person_email || '-'}</td>
                    <td>${lead.contact_person_phone || '-'}</td>
                    <td>${lead.source}</td>
                    <td>
                        <select class="form-select form-select-sm lead-status-select" data-id="${lead.id}">
                            <option value="New" ${lead.status === 'New' ? 'selected' : ''}>New</option>
                            <option value="Qualified" ${lead.status === 'Qualified' ? 'selected' : ''}>Qualified</option>
                            <option value="Unqualified" ${lead.status === 'Unqualified' ? 'selected' : ''}>Unqualified</option>
                            <option value="Converted" ${lead.status === 'Converted' ? 'selected' : ''}>Converted</option>
                        </select>
                    </td>
                    <td>${formattedDate}</td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" 
                                class="btn btn-sm btn-info action-btn me-1 edit-lead"
                                data-bs-toggle="modal" 
                                data-bs-target="#editLeadModal"
                                data-id="${lead.id}"
                                data-name="${lead.name}"
                                data-email="${lead.email}"
                                data-phone="${lead.phone}"
                                data-source="${lead.source}"
                                data-notes="${lead.notes}"
                                data-contact_person="${lead.contact_person || ''}"
                                data-contact_person_email="${lead.contact_person_email || ''}"
                                data-contact_person_phone="${lead.contact_person_phone || ''}"
                                title="Edit Lead">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button type="button" 
                                class="btn btn-sm btn-danger delete-lead" 
                                data-id="${lead.id}" 
                                data-name="${lead.name}"
                                data-action="{{ route('company.leads.destroy', '') }}/${lead.id}" 
                                title="Delete Lead">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <button type="button" 
                                class="btn btn-sm btn-success convert-lead"
                                data-id="${lead.id}"
                                data-name="${lead.name}"
                                data-bs-toggle="modal"
                                data-bs-target="#convertLeadModal"
                                title="Convert Lead">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            <button type="button" 
                                class="btn btn-sm btn-warning schedule-appointment" 
                                data-id="${lead.id}" 
                                title="Schedule Appointment">
                                <i class="fas fa-calendar-plus"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });

            $('#leads-datatable tbody').html(tableBody);

            // Generate Pagination
            let paginationHtml = '';
            
            // if (response.pagination.last_page > 1) {
                paginationHtml = `<li class="page-item ${response.pagination.prev_page_url ? '' : 'disabled'}">
                    <a class="page-link" href="#" onclick="return changePageLead(event, ${response.pagination.current_page - 1})">Previous</a>
                </li>`;

                for (let i = 1; i <= response.pagination.last_page; i++) {
                    paginationHtml += `<li class="page-item ${i === response.pagination.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="return changePageLead(event, ${i})">${i}</a>
                    </li>`;
                }

                paginationHtml += `<li class="page-item ${response.pagination.next_page_url ? '' : 'disabled'}">
                    <a class="page-link" href="#" onclick="return changePageLead(event, ${response.pagination.current_page + 1})">Next</a>
                </li>`;
            // }

            $('#leadspagination').html(paginationHtml);
            // console.log(response.pagination.last_page);
            
            // Initialize tooltips for action buttons
            $('[title]').tooltip();
        },
        error: function(xhr, status, error) {
            console.error("Error:", status, error, xhr.responseText);
            // Show error message to user
            alert('Failed to load leads data. Please try again.');
        }
    });
}

// Page change function
function changePageLead(event, page) {
    event.preventDefault();
    fetchLeadData(page);
    return false;
}


// Initial Call
fetchLeadData();

    // Wait for SweetAlert2 to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Check if SweetAlert is loaded
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert is not loaded correctly');
            return;
        }

        // Utility function to show SweetAlert safely
        function showSafeSweetAlert(options) {
            if (!window.isAlertShowing) {
                window.isAlertShowing = true;
                return Swal.fire(options).finally(() => {
                    window.isAlertShowing = false;
                });
            } 
        }

        // Handle lead deletion
        $(document).on('click', '.delete-lead', function(e) {
            e.preventDefault();

            const leadId = $(this).data('id');
            const leadName = $(this).data('name');
            const actionUrl = $(this).data('action');

            showSafeSweetAlert({
                title: 'Are you sure?',
                text: `You are about to move ${leadName} to trash. This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, move to trash',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: actionUrl,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                showSafeSweetAlert({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    }
                                });
                            } else {
                                showSafeSweetAlert({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX error:', { status, error, response: xhr.responseJSON });
                            
                            let errorMessage = 'Failed to move lead to trash. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            showSafeSweetAlert({
                                icon: 'error',
                                title: 'Error!',
                                html: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        // Handle lead addition
        $('#addLeadForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    // console.log('Server response:', response);
                    
                    if (response.success) {
                        // Show success message
                        showSafeSweetAlert({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Lead added successfully',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Close the modal
                                $('#addLeadModal').modal('hide');
                                // Clear the form
                                $('#addLeadForm')[0].reset();
                                // Refresh the page
                                window.location.reload();
                            }
                        });
                    } else {
                        // Show error message
                        showSafeSweetAlert({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to add lead. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', { status, error, response: xhr.responseJSON });
                    
                    let errorMessage = 'Failed to add lead. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                    }

                    // Show error message
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Initialize the edit modal
        $('#editLeadModal').on('show.bs.modal', function(e) {
            const button = $(e.relatedTarget);
            const leadId = button.data('id');
            const leadName = button.data('name');
            const leadEmail = button.data('email');
            const leadPhone = button.data('phone');
            const leadSource = button.data('source');
            const leadNotes = button.data('notes');
            const leadContactPerson = button.data('contact_person');
            const leadContactPersonEmail = button.data('contact_person_email');
            const leadContactPersonPhone = button.data('contact_person_phone');

            // Set the lead ID in the hidden field
            $('#editLeadId').val(leadId);

            // Populate the form fields
            $('#editName').val(leadName);
            $('#editEmail').val(leadEmail);
            $('#editPhone').val(leadPhone);
            $('#editSource').val(leadSource);
            $('#editNotes').val(leadNotes);
            $('#edit_contact_person').val(leadContactPerson);
            $('#edit_contact_person_email').val(leadContactPersonEmail);
            $('#edit_contact_person_phone').val(leadContactPersonPhone);

            console.log('Populated fields:', {
                name: leadName,
                email: leadEmail,
                phone: leadPhone,
                source: leadSource,
                notes: leadNotes,
                contact_person: leadContactPerson,
                contact_person_email: leadContactPersonEmail,
                contact_person_phone: leadContactPersonPhone
            });

            console.log('Showing modal with populated data');
        });

        // Handle edit form submission
        $('#editLeadForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const leadId = $('#editLeadId').val();

            // Log the form data being sent
            console.log('Form data being sent:', {
                leadId: leadId,
                formData: Object.fromEntries(formData)
            });

            // Construct the URL using the base URL from the form's action
            const baseUrl = $(this).attr('action');
            // Replace the placeholder with the actual lead ID
            const url = baseUrl.replace(':lead_id', leadId);

            console.log('Constructed URL:', url);

            $.ajax({
                url: url,
                type: 'POST', // Using POST with _method=PUT
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Server response:', response);
                    
                    if (response.success) {
                        // Show success message
                        showSafeSweetAlert({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Lead has been updated successfully.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Close the modal
                                $('#editLeadModal').modal('hide');
                                // Refresh the page
                                window.location.reload();
                            }
                        });
                    } else {
                        // Show error message
                        showSafeSweetAlert({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to update lead. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', { 
                        status: status,
                        error: error,
                        response: xhr.responseJSON,
                        request: xhr.request,
                        statusText: xhr.statusText
                    });
                    
                    let errorMessage = 'Failed to update lead. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                    }

                    // Show error message
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Handle Convert Lead to Opportunity (uniform style)
        $(document).on('click', '.convert-lead', function() {
            const button = $(this);
            const leadId = button.data('id');
            const leadName = button.data('name');

            if (!leadId) {
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Lead ID is missing. Please contact admin.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            $('#convertLeadId').val(leadId);
            $('#opportunityName').val(leadName);

            // Clear all other fields
            $('#account').val('');
            $('#stage').val('');
            $('#amount').val('');
            $('#expectedRevenue').val('');
            $('#opportunityCloseDate').val('');
            $('#probability').val('');
            $('#description').val('');
        });

        // AJAX submit for Convert Lead to Opportunity (uniform style)
        $('#convertLeadForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const formData = form.serialize();

            $('#submitConvertLead').prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#submitConvertLead').prop('disabled', false);
                    $('#convertLeadModal').modal('hide');
                    if (response.success) {
                        showSafeSweetAlert({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Lead has been converted to opportunity successfully.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        showSafeSweetAlert({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to convert lead. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    $('#submitConvertLead').prop('disabled', false);
                    let errorMessage = 'Failed to convert lead. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                    }
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Delegate event to handle status change for dynamically generated rows
        $('#leads-datatable').on('change', '.lead-status-select', function() {
            const leadId = $(this).data('id');
            const newStatus = $(this).val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: `/company/leads/${leadId}/status`,
                type: 'POST', // NOT PUT!
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    status: newStatus,
                    _method: 'PUT'
                },
                success: function(response) {
                    // Optionally, show a success message
                    showSafeSweetAlert({
                        icon: 'success',
                        title: 'Status Updated',
                        html: 'Lead status has been updated successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to update status.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Export Leads Handler
        $('#confirmExportLeads').on('click', function(e) {
            e.preventDefault();

            // SweetAlert2 confirmation before export
            showSafeSweetAlert({
                title: 'Export Leads',
                text: 'Do you want to export all leads as CSV?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, export',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading dialog
                    Swal.fire({
                        title: 'Exporting...',
                        html: 'Your download will start shortly.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Prepare and submit the form via AJAX to get the CSV
                    const form = $('#exportLeadsForm');
                    const formData = form.serialize();

                    $.ajax({
                        url: '{{ route("company.leads.export") }}',
                        type: 'POST',
                        data: formData,
                        xhrFields: {
                            responseType: 'blob'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(blob, status, xhr) {
                            Swal.close();
                            // Extract filename from headers
                            let filename = "leads_export.csv";
                            const disposition = xhr.getResponseHeader('Content-Disposition');
                            if (disposition && disposition.indexOf('attachment') !== -1) {
                                const matches = /filename="([^"]*)"/.exec(disposition);
                                if (matches != null && matches[1]) filename = matches[1];
                            }
                            // Download the file
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);

                            showSafeSweetAlert({
                                icon: 'success',
                                title: 'Exported!',
                                text: 'Leads exported successfully.',
                                confirmButtonText: 'OK'
                            });
                            $('#exportLeadsModal').modal('hide');
                        },
                        error: function(xhr) {
                            Swal.close();
                            let errorMessage = 'Failed to export leads. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            showSafeSweetAlert({
                                icon: 'error',
                                title: 'Export Failed!',
                                html: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        //Schedule Appointment
        $(document).on('click', '.schedule-appointment', function() {
            const leadId = $(this).data('id');
            const leadName = $(this).closest('tr').find('td').eq(1).text(); // Adjust index if needed

            $('#appointmentLeadId').val(leadId);
            $('#appointmentLeadName').val(leadName);

            // Clear previous values
            $('#appointment_date').val('');
            $('#appointment_time').val('');
            $('#appointment_type').val('');
            $('#appointment_notes').val('');

            $('#scheduleAppointmentModal').modal('show');
        });

        // Format date to a more readable format (e.g., "Sun, Aug 31, 2025")
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return 'Invalid Date';
            
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                weekday: 'short'
            });
        }

        // Format time to 12-hour format (e.g., "2:30 PM")
        function formatTime(timeString) {
            if (!timeString) return 'N/A';
            
            // If time is in format "HH:MM:SS"
            const timeParts = timeString.split(':');
            if (timeParts.length >= 2) {
                const hours = parseInt(timeParts[0]);
                const minutes = timeParts[1];
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const hours12 = hours % 12 || 12;
                return `${hours12}:${minutes} ${ampm}`;
            }
            return timeString; // Return as is if format is unexpected
        }

        // Function to fetch and display appointments
        function fetchAppointments() {
            $.ajax({
                url: '{{ route("company.leads.appointments.all") }}',
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Appointments response:', response);
                    let tableBody = '';
                    
                    if (response.data && response.data.length > 0) {
                        response.data.forEach((appointment, index) => {
                            tableBody += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${appointment.name || 'N/A'}</td>
                                    <td>${formatDate(appointment.appointment_date) || 'N/A'}</td>
                                    <td>${formatTime(appointment.appointment_time) || 'N/A'}</td>
                                    <td>${appointment.appointment_type || 'N/A'}</td>
                                    <td>${appointment.appointment_notes || 'N/A'}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tableBody = '<tr><td colspan="6" class="text-center">No scheduled appointments found</td></tr>';
                    }
                    
                    $('#appointments-table tbody').html(tableBody);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching appointments:', { status, error, response: xhr.responseText });
                    $('#appointments-table tbody').html('<tr><td colspan="6" class="text-center text-danger">Failed to load appointments. Please try again.</td></tr>');
                }
            });
        }

        // Call fetchAppointments when the page loads
        $(document).ready(function() {
            fetchAppointments();
            
            // Also fetch appointments after scheduling a new one
            $('#scheduleAppointmentForm').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const formData = form.serialize();
                
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Close the modal
                        $('#scheduleAppointmentModal').modal('hide');
                        // Reset the form
                        form[0].reset();
                        // Refresh the appointments table
                        fetchAppointments();
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Appointment scheduled successfully',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to schedule appointment';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    });
</script>

<!-- Delete Lead Confirmation Modal -->
<div class="modal fade" id="deleteLeadModal" tabindex="-1" aria-labelledby="deleteLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLeadModalLabel">Delete Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteLeadForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this lead? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Upload Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('company.leads.bulk-upload') }}" method="POST" enctype="multipart/form-data" id="bulkUploadLeadsForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle me-1"></i>
                        Download the template first and fill it with your lead data. Make sure to follow the format exactly.
                        <a href="{{ route('company.leads.download-template') }}" class="btn btn-sm btn-outline-primary ms-2">
                            <i class="fa-solid fa-download me-1"></i>Download Template
                        </a>
                    </div>
                    
                    <div class="mb-3">
                        <label for="bulk_upload_file" class="form-label">Choose CSV File</label>
                        <input type="file" class="form-control" id="bulk_upload_file" name="bulk_upload_file" accept=".csv,.txt" required>
                        <div class="form-text">
                            Accepted formats: .csv, .txt (max 2MB)
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Convert Lead to Opportunity Modal -->
<div class="modal fade" id="convertLeadModal" tabindex="-1" aria-labelledby="convertLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="convertLeadModalLabel">Convert Lead to Opportunity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="convertLeadForm" class="needs-validation" action="{{ route('company.leads.convert-to-opportunity') }}" method="POST" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <input type="hidden" name="lead_id" id="convertLeadId">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="opportunityName" name="name" placeholder=" " required>
                            <label for="opportunityName">Opportunity Name</label>
                            <div class="invalid-feedback">Please enter an opportunity name.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="account" name="account" placeholder=" " required>
                            <label for="account">Account</label>
                            <div class="invalid-feedback">Please enter an account name.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stage" name="stage" required>
                                <option value="">Select Stage</option>
                                <option value="Prospecting">Prospecting</option>
                                <option value="Qualification">Qualification</option>
                                <option value="Proposal">Proposal</option>
                                <option value="Negotiation">Negotiation</option>
                            </select>
                            <label for="stage">Opportunity Stage</label>
                            <div class="invalid-feedback">Please select a stage.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="amount" name="amount" placeholder=" " min="0" step="0.01" required>
                            <label for="amount">Amount</label>
                            <div class="invalid-feedback">Please enter a valid amount.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="expectedRevenue" name="expected_revenue" placeholder=" " min="0" step="0.01">
                            <label for="expectedRevenue">Expected Revenue (Optional)</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="opportunityCloseDate" name="close_date" placeholder=" " required>
                            <label for="opportunityCloseDate">Close Date</label>
                            <div class="invalid-feedback">Please select a close date.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="probability" name="probability" placeholder=" " min="0" max="100" step="1" required>
                            <label for="probability">Probability (%)</label>
                            <div class="invalid-feedback">Please enter a probability between 0 and 100.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="description" name="description" placeholder=" " style="height: 100px"></textarea>
                            <label for="description">Description (Optional)</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitConvertLead" class="btn btn-primary">Convert Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Export Leads Modal -->
<div class="modal fade" id="exportLeadsModal" tabindex="-1" aria-labelledby="exportLeadsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportLeadsModalLabel">Export Leads</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exportLeadsForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="exportFormat" class="form-label">Select Export Format</label>
                        <select class="form-select" id="exportFormat" name="format">
                            <option value="csv">CSV</option>
                            <!-- Add Excel if implemented -->
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" id="confirmExportLeads">Export</button>
                </form>
            </div>
        </div>
    </div>
</div>
@push('script-bottom')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        
        // Handle Add Lead Form Submission
        $('#addLeadForm').on('submit', function(e) {
            e.preventDefault();

            // Check if this is an AJAX form
            if (!$(this).data('ajax')) {
                return;
            }

            const formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Server response:', response);
                    
                    if (response && response.success) {
                        // Show success message using SweetAlert
                        showSafeSweetAlert({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Lead has been added successfully.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Close the modal
                                $('#addLeadModal').modal('hide');
                                // Refresh the page
                                window.location.reload();
                            }
                        });
                    } else {
                        // Show error message using SweetAlert
                        showSafeSweetAlert({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to add lead. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', { status, error, response: xhr.responseJSON });
                    
                    let errorMessage = 'Failed to add lead. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                    }

                    // Show error message using SweetAlert
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Handle Bulk Upload Form Submission
        $('#bulkUploadLeadsForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Show loading spinner
            const loadingAlert = showSafeSweetAlert({
                title: 'Uploading Leads...',
                html: 'Please wait while your leads are being processed...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Server response:', response);
                    
                    // Close the loading spinner
                    loadingAlert.close();
                    
                    if (response && response.success) {
                        // Show success message using SweetAlert
                        showSafeSweetAlert({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Leads have been uploaded successfully.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Close the modal
                                $('#bulkUploadModal').modal('hide');
                                // Refresh the page
                                window.location.reload();
                            }
                        });
                    } else {
                        // Show error message using SweetAlert
                        showSafeSweetAlert({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to upload leads. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', { status, error, response: xhr.responseJSON });
                    
                    // Close the loading spinner
                    loadingAlert.close();
                    
                    let errorMessage = 'Failed to upload leads. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                    }

                    // Show error message using SweetAlert
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endpush