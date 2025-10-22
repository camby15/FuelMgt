@extends('layouts.vertical-admin', ['page_title' => 'Manage Agents'])
@section('css')

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

        .form-floating input.form-control:focus~label,
        .form-floating input.form-control:not(:placeholder-shown)~label,
        .form-floating select.form-select:focus~label,
        .form-floating select.form-select:not([value=""])~label,
        .form-floating textarea.form-control:focus~label,
        .form-floating textarea.form-control:not(:placeholder-shown)~label {
            height: auto;
            padding: 0 0.5rem;
            transform: translateY(-50%) translateX(0.5rem) scale(0.85);
            color: white;
            border-radius: 5px;
            z-index: 5;
        }

        .form-floating input.form-control:focus~label::before,
        .form-floating input.form-control:not(:placeholder-shown)~label::before,
        .form-floating select.form-select:focus~label::before,
        .form-floating select.form-select:not([value=""])~label::before,
        .form-floating textarea.form-control:focus~label::before,
        .form-floating textarea.form-control:not(:placeholder-shown)~label::before {
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

        /* Dark mode table styles */
        [data-bs-theme="dark"] .table {
            color: #e9ecef;
            border-color: #495057;
        }

        [data-bs-theme="dark"] .table-light {
            background-color: #343a40;
            color: #e9ecef;
        }

        [data-bs-theme="dark"] .table-striped>tbody>tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
            color: #e9ecef;
        }

        [data-bs-theme="dark"] .modal-content {
            background-color: #212529;
            color: #e9ecef;
        }

        [data-bs-theme="dark"] .modal-header {
            border-bottom-color: #495057;
        }

        [data-bs-theme="dark"] .modal-footer {
            border-top-color: #495057;
        }

        [data-bs-theme="dark"] .nav-tabs .nav-link {
            color: #adb5bd;
        }

        [data-bs-theme="dark"] .nav-tabs .nav-link.active {
            color: #e9ecef;
            background-color: #343a40;
            border-color: #495057;
        }

        [data-bs-theme="dark"] .card {
            background-color: #2c3034;
            border-color: #495057;
        }

        [data-bs-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .agent-profile-link img {
            border: 2px solid #dee2e6;
            transition: box-shadow 0.2s;
        }

        .agent-profile-link:hover img {
            box-shadow: 0 0 0 2px #0d6efd;
        }

        .modal .form-floating>label {
            left: 0.75rem;
        }

        .modal .form-select {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }

        .skill-badge {
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-bordered mb-3" id="managementTabs">
                            <li class="nav-item">
                                <a href="#agents-tab" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                    <i class="bi bi-people me-1"></i> Agents Management
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tickets-tab" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <i class="bi bi-ticket-detailed me-1"></i> Support Tickets
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane show active" id="agents-tab">
                                <h4 class="header-title mb-3">Agents Management</h4>
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap mb-0" id="agentsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Agent ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Total Ticket Assigned</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table><br>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination d-flex justify-content-center" id="agent-pagination">

                                        </ul>
                                    </nav>
                                </div>
                                <div class="mt-3 d-flex flex-wrap gap-2">
                                    <button class="btn btn-primary btn-sm" id="addAgentBtn" data-bs-toggle="modal"
                                        data-bs-target="#addAgentModal">Add Agent</button>
                                    <button class="btn btn-secondary btn-sm" id="importAgentsBtn" data-bs-toggle="modal"
                                        data-bs-target="#importAgentModal">
                                        Import Agents
                                    </button>

                                    <button class="btn btn-outline-secondary btn-sm" id="exportAgentsBtn">Export
                                        Agents</button>
                                </div>
                            </div>

                            <div class="tab-pane" id="tickets-tab">
                                <h4 class="header-title mb-3">Support Tickets</h4>
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap mb-0" id="ticketsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Ticket ID</th>
                                                <th>Subject</th>
                                                <th>Customer</th>
                                                <th>Priority</th>
                                                <th>Status</th>
                                                <th>Assigned To</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table><br>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination d-flex justify-content-center" id="ticket-pagination">

                                        </ul>
                                    </nav>

                                </div>
                                <div class="mt-3 d-flex flex-wrap gap-2">
                                    <button class="btn btn-primary btn-sm" id="refreshTicketsBtn">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Tickets
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" id="exportTicketsBtn">
                                        <i class="bi bi-download me-1"></i> Export Tickets
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Agent Modal -->
    <div class="modal fade" id="addAgentModal" tabindex="-1" aria-labelledby="addAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAgentModalLabel">Add New Agent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addAgentForm">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="addAgentName" placeholder="Name" required>
                            <label for="addAgentName">Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="addAgentEmail" placeholder="Email" required>
                            <label for="addAgentEmail">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="addAgentPhone" placeholder="Phone Number"
                                required>
                            <label for="addAgentPhone">Phone Number</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="addAgentStatus" required>
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>

                            </select>
                            <label for="addAgentStatus">Status</label>
                        </div>
                        <button type="submit" class="btn btn-success" id="addAgent">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Agent Modal -->
    <div class="modal fade" id="editAgentModal" tabindex="-1" aria-labelledby="editAgentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAgentModalLabel">Edit Agent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAgentForm">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="editAgentName" placeholder="Name"
                                value="" required>
                            <label for="editAgentName">Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="editAgentEmail" placeholder="Email" required>
                            <label for="editAgentEmail">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="phone" class="form-control" id="editAgentPhone" placeholder="Phone" required>
                            <label for="editAgentPhone">Phone</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="editAgentStatus" required>
                                <option value="available">Available</option>
                                <option value="unavailable">unavailable</option>
                            </select>
                            <label for="editAgentStatus">Status</label>
                        </div>
                        <button type="submit" class="btn btn-success" id="editBtnSubmit">Update</button>
                    </form>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agent Profile Quick View Modal -->
    <div class="modal fade" id="agentProfileModal" tabindex="-1" aria-labelledby="agentProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agentProfileModalLabel">Agent Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="agentProfileContent">
                    <!-- Profile content will be injected via JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket View Modal -->
    <div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="viewTicketModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-white text-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTicketModalLabel">Ticket Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ticket-header mb-4">
                        <div class="d-flex flex-column gap-2" id="ticketDetails">
                            <h4>Ticket ID: #<span class="ticket-id"></span></h4>
                            <p><strong>Subject:</strong> <span class="ticket-subject"></span></p>
                            <p><strong>Priority:</strong> <span class="badge ticket-priority"></span></p>
                            <p><strong>Status:</strong> <span class="badge ticket-status"></span></p>
                            <p><strong>Description:</strong><br> <span class="ticket-description"></span></p>
                            <p><strong>Attachment:</strong><br> <span class="ticket-attachment"></span></p>
                        </div>
                        <hr>
                        <div class="ticket-meta text-muted">
                            <p><i class="fas fa-user me-1"></i> Customer: <span class="ticket-customer"></span></p>
                            <p><i class="fas fa-user-tie me-1"></i> Agent: <span class="ticket-agent"></span></p>
                            <p><i class="fas fa-calendar me-1"></i> Created At: <span class="ticket-created_at"></span>
                            </p>
                            <p><i class="fas fa-tag me-1"></i> Category: <span class="ticket-category"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Agent Modal -->
    <div class="modal fade" id="importAgentModal" tabindex="-1" aria-labelledby="importAgentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importAgentModalLabel">Import Agent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="importAgentForm" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <input type="file" class="form-control" id="importAgentFile" name="importAgentFile"
                                accept=".csv, .xlsx, .xls" required>
                            <label for="importAgentFile">Select File (CSV or Excel)</label>
                        </div>
                        <button type="submit" class="btn btn-success" id="importaddAgent">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 for alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Load jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @parent
    <script>
        $(document).ready(function() {
            loadTickets();
        });

        function loadTickets(page = 1) {
            $.ajax({
                url: `/api/tickets?page=${page}`,
                method: "GET",
                success: function(response) {
                    console.log(response);
                    let ticketsTableBody = $('#ticketsTable tbody');

                    ticketsTableBody.empty();
                    // Clear existing rows
                    if (response.ticket.length === 0) {
                        ticketsTableBody.append(
                            '<tr><td colspan="7" class="text-center">No tickets found</td></tr>');
                        return;
                    }
                    response.ticket.forEach(ticket => {
                        // Determine priority badge class
                        let priorityBadgeClass = '';
                        if (ticket.priority === 'high') priorityBadgeClass = 'bg-danger';
                        else if (ticket.priority === 'medium') priorityBadgeClass = 'bg-info';
                        else priorityBadgeClass = 'bg-success';

                        // Determine status badge class
                        let statusBadgeClass = '';
                        if (ticket.status === 'open') statusBadgeClass = 'bg-success';
                        else if (ticket.status === 'in_progress') statusBadgeClass = 'bg-warning';
                        else statusBadgeClass = 'bg-secondary';
                        let row = `
                                    <tr>
                                        <td>#${ticket.ticket_id}</td>
                                        <td>${ticket.subject}</td>
                                        <td>${ticket.customer}</td>
                                        <td><span class="badge ${priorityBadgeClass}">${ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1)}</span></td>
                                        <td><span class="badge ${statusBadgeClass}">${ticket.status.replace('_', ' ')}</span></td>
                                       <td>${ticket.agent ? ticket.agent.name : 'N/A'}</td>
                                        <td>${ticket.created_at}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info view-ticket"  data-ticket-id="${ticket.id}">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-ticket" data-ticket-id="${ticket.id}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                        ticketsTableBody.append(row);
                    });

                    // Generate Pagination
                    let paginationHtml = '';

                    paginationHtml += `<li class="page-item ${response.pagination.prev_page_url ? '' : 'disabled'}">
                                <a class="page-link" href="#" data-page="${response.pagination.current_page - 1}">Previous</a>
                            </li>`;

                    for (let i = 1; i <= response.pagination.last_page; i++) {
                        paginationHtml += `<li class="page-item ${response.pagination.current_page === i ? 'active' : ''}">
                                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                                </li>`;
                    }

                    paginationHtml += `<li class="page-item ${response.pagination.next_page_url ? '' : 'disabled'}">
                                <a class="page-link" href="#" data-page="${response.pagination.current_page + 1}">Next</a>
                            </li>`;

                    $('#ticket-pagination').html(paginationHtml);

                    // Initialize tooltips for action buttons
                    $('[data-toggle="tooltip"]').tooltip();

                },
                error: function(xhr, status, error) {
                    console.error('Error loading Tickets', error);
                }
            });

        }
        loadTickets();

        $(document).on('click', ' #ticket-pagination .page-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page) {
                loadTickets(page);
            }
        });

        $(document).on('click', '.view-ticket', function() {
            const ticketId = $(this).data('ticket-id');
            showTickets(ticketId);
            $('#viewTicketModal').modal('show');

        });

        function showTickets($id) {
            $.ajax({
                url: `/api/tickets/${$id}`,
                method: "GET",
                success: function(response) {
                    const ticket = response.ticket;
                    if (response.success && ticket) {
                        console.log(ticket);

                        $('.ticket-id').text(ticket.ticket_id || '');
                        $('.ticket-subject').text(ticket.subject || '');
                        $('.ticket-status').text(ticket.status || '');
                        $('.ticket-customer').text(ticket.customer || '');
                        $('.ticket-created_at').text(ticket.created_at || '');
                        $('.ticket-category').text(ticket.category || '');
                        $('.ticket-description').html(ticket.description || '');
                        $('.ticket-priority').text(ticket.priority || '');
                        $('.ticket-agent').text(ticket.agent && ticket.agent.name ? ticket.agent.name : 'N/A');

                        $('.ticket-attachment').empty();
                        if (ticket.attachments && ticket.attachments.length > 0) {

                            ticket.attachments.forEach(attachment => {
                                const imageUrl = `/storage/${attachment.file_path}`;
                                $('.ticket-attachment').append(`
                                <a href="${imageUrl}" target="_blank">
                                    <img src="${imageUrl}" alt="Attachment" style="width:100px;height:auto;margin:5px;border-radius:4px;">
                                </a>
                            `);
                            });
                        } else {
                            $('.ticket-attachment').html('<p>No attachments.</p>');
                        }

                        let priorityBadgeClass = '';
                        if (ticket.priority === 'high') priorityBadgeClass = 'bg-danger';
                        else if (ticket.priority === 'medium') priorityBadgeClass = 'bg-info';
                        else priorityBadgeClass = 'bg-success';
                        $('.ticket-priority').addClass(priorityBadgeClass);

                        // Set status badge class
                        let statusBadgeClass = '';
                        $('.ticket-status').removeClass('bg-success bg-warning bg-secondary');
                        if (ticket.status === 'open') statusBadgeClass = 'bg-success';

                        else statusBadgeClass = 'bg-secondary';
                        $('.ticket-status').addClass(statusBadgeClass);

                        $('#viewTicketModal').modal('show');
                        // Update ticket details in modal
                        $('#ticketDetails').html(response.data);
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error loading Ticket details', error);
                }

            });
        }

        $(document).ready(function() {
            loadAgents();
        });

        function loadAgents(page = 1) {
            $.ajax({
                url: `/api/agents?page=${page}`,
                method: "GET",
                success: function(response) {
                    console.log(response);
                    let agentsTableBody = $('#agentsTable tbody');

                    agentsTableBody.empty();

                    if (response.agents.length === 0) {
                        agentsTableBody.append(
                            '<tr><td colspan="7" class="text-center">No agents found</td></tr>');
                        return;
                    }
                    response.agents.forEach(agent => {
                        let row = `
                                    <tr>
                                        <td>#${agent.id}</td>
                                        <td>${agent.name}</td>
                                        <td>${agent.email}</td>
                                        <td>${agent.phone}</td>
                                        <td>${agent.ticket_count}</td>
                                        <td>${agent.status}</td>

                                        <td>
                                            <button class="btn btn-sm btn-info edit-agent-btn" data-bs-toggle="modal" data-bs-target="#editAgentModal" data-agent-id="${agent.id}">Edit</button>
                                            <button class="btn btn-sm btn-danger btn-delete-agent">Delete</button>
                                        </td>
                                    </tr>
                                `;
                        agentsTableBody.append(row);
                    });

                    // Generate Pagination
                    let paginationHtml = '';

                    paginationHtml += `<li class="page-item ${response.pagination.prev_page_url ? '' : 'disabled'}">
                                <a class="page-link" href="#" data-page="${response.pagination.current_page - 1}">Previous</a>
                            </li>`;

                    for (let i = 1; i <= response.pagination.last_page; i++) {
                        paginationHtml += `<li class="page-item ${response.pagination.current_page === i ? 'active' : ''}">
                                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                                </li>`;
                    }

                    paginationHtml += `<li class="page-item ${response.pagination.next_page_url ? '' : 'disabled'}">
                                <a class="page-link" href="#" data-page="${response.pagination.current_page + 1}">Next</a>
                            </li>`;

                    $('#agent-pagination').html(paginationHtml);

                    // Initialize tooltips for action buttons
                    $('[data-toggle="tooltip"]').tooltip();

                },
                error: function(xhr, status, error) {
                    console.error('Error loading Agents', error);
                }

            });

        }
        loadAgents();

        $(document).on('click', '#agent-pagination .page-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page) {
                loadAgents(page);
            }

        });

        $(document).ready(function() {
            $(document).off('submit', '#addAgentForm').on('submit', '#addAgentForm', function(e) {
                e.preventDefault();

                let $submitBtn = $('#addAgentForm button[type="submit"]');
                let originalBtnHtml = $submitBtn.html();

                // Set loading state
                $submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...
        `);

                let formData = {
                    name: $('#addAgentName').val(),
                    email: $('#addAgentEmail').val().trim(),
                    phone: $('#addAgentPhone').val(),
                    status: $('#addAgentStatus').val()
                };

                $.ajax({
                    url: '/api/agents',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Agent Added',
                                text: response.message,
                            });

                            $('#addAgentModal').modal('hide');
                            $('#addAgentForm')[0].reset();
                            $('.modal-backdrop').remove();

                            loadAgents();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding agent:', xhr.responseText);

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            let messages = Object.values(errors).flat().join('\n');

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: messages
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while adding the agent.'
                            });
                        }
                    },
                    complete: function() {
                        // Restore button to original state
                        $submitBtn.prop('disabled', false).html(originalBtnHtml);
                    }
                });
            });
        });

        // refresh tickets
        $(document).on('click', '#refreshTicketsBtn', function() {
            loadTickets();
        });

        // export agents      
        $(document).ready(function() {
            $(document).off('click', '#exportAgentsBtn').on('click', '#exportAgentsBtn', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '/api/agents/export?format=xlsx',
                    method: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data, status, xhr) {
                        const blob = new Blob([data], {
                            type: xhr.getResponseHeader('Content-Type')
                        });

                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'agents.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);

                        Swal.fire({
                            icon: 'success',
                            title: 'Export Successful',
                            text: 'Agents have been exported successfully.'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Export Failed',
                            text: 'Could not export agents.'
                        });
                    }
                });
            });
        });

        // export tickets
        $(document).ready(function() {
            $(document).off('click', '#exportTicketsBtn').on('click', '#exportTicketsBtn', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/api/tickets/export',
                    method: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data, status, xhr) {
                        const blob = new Blob([data], {
                            type: xhr.getResponseHeader('Content-Type')
                        });

                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'tickets.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);

                        Swal.fire({
                            icon: 'success',
                            title: 'Export Successful',
                            text: 'Tickets have been exported successfully.'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Export Failed',
                            text: 'Could not export tickets.'
                        });
                    }
                });
            });
        });

        // delete ticket
        $(document).off('click', '.delete-ticket').on('click', '.delete-ticket', function() {
            const ticketId = $(this).data('ticket-id');
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
                        url: `/api/tickets/${ticketId}`,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                                loadTickets();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deleting ticket:', xhr.responseText);
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the ticket.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // delete agent
        $(document).off('click', '.btn-delete-agent').on('click', '.btn-delete-agent', function() {
            const agentId = $(this).closest('tr').find('td:first').text().substring(1);
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
                        url: `/api/agents/${agentId}`,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                                loadAgents();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deleting agent:', xhr.responseText);
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the agent.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $(document).off('click', '.edit-agent-btn').on('click', '.edit-agent-btn', function() {
            let agentId = $(this).data('agent-id');

            // Fetch the agent data first
            $.ajax({
                url: `/api/agents/${agentId}`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.agent) {
                        $('#editAgentName').val(response.agent.name);
                        $('#editAgentEmail').val(response.agent.email);
                        $('#editAgentPhone').val(response.agent.phone);
                        $('#editAgentStatus').val(response.agent.status);
                        $('#editAgentForm').data('agent-id', agentId);

                        // Show the modal
                        $('#editAgentModal').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching agent data:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while fetching the agent data.'
                    });
                }

            });
        });
        // edit agent
        $(document).off('submit', '#editAgentForm').on('submit', '#editAgentForm', function(e) {
            e.preventDefault();

            let $submitBtn = $('#editAgentForm button[type="submit"]');
            let originalBtnHtml = $submitBtn.html();

            // Set loading state
            $submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...
        `);

            let agentId = $(this).data('agent-id');
            let formData = {
                name: $('#editAgentName').val(),
                email: $('#editAgentEmail').val().trim(),
                phone: $('#editAgentPhone').val(),
                status: $('#editAgentStatus').val()
            };

            $.ajax({
                url: `/api/agents/${agentId}`,
                method: 'PUT',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Agent Updated',
                            text: response.message,
                        });

                        $('#editAgentModal').modal('hide');
                        $('#editAgentForm')[0].reset();
                        $('.modal-backdrop').remove();
                        loadAgents();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating agent:', xhr.responseText);

                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        let messages = Object.values(errors).flat().join('\n');

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: messages
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while updating the agent.'
                        });
                    }
                },
                complete: function() {
                    // Restore button to original state
                    $submitBtn.prop('disabled', false).html(originalBtnHtml);
                }
            });
        });

        // Import Agent
        $(document).ready(function() {
            $(document).off('submit', '#importAgentForm').on('submit', '#importAgentForm', function(e) {
                e.preventDefault();

                let $form = $(this);
                let $submitBtn = $form.find('button[type="submit"]');
                let originalBtnHtml = $submitBtn.html();

                // Disable button and show loading
                $submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Importing...
        `);

                let formData = new FormData(this);

                $.ajax({
                    url: '/api/agents/import',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            });

                            $('#importAgentModal').modal('hide');
                            $('#importAgentForm')[0].reset();
                            $('.modal-backdrop').remove();
                            loadAgents();

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error importing agents:', xhr.responseText);

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let messages = Object.values(xhr.responseJSON.errors).flat().join(
                                '\n');
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: messages,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while importing agents.',
                            });
                        }
                    },
                    complete: function() {
                        $submitBtn.prop('disabled', false).html(originalBtnHtml);
                    }
                });
            });

            $('#importAgentModal').on('hidden.bs.modal', function() {
                $('#importAgentForm')[0].reset();
            });
        });
    </script>
@endsection
