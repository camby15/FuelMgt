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

        [data-bs-theme="dark"] .form-floating input.form-control:focus~label::before,
        [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown)~label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:focus~label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:not([value=""])~label::before,
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus~label::before,
        [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown)~label::before {
            background: #0dcaf0;
        }

        [data-bs-theme="dark"] select.form-select option {
            background-color: #212529;
            color: #e9ecef;
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
    </style>
@endsection

<div class="row">
    <div class="col-12">
        <!-- Support Tickets Section -->
        <div class="card crm-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="header-title mb-0">Support Tickets</h4>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTicketModal">
                            <i class="fas fa-plus me-1"></i> Create Ticket
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-hover dt-responsive nowrap w-100"
                        id="support-tickets-datatable">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Subject</th>
                                <th>description</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                    <nav aria-label="Page navigation example">
                        <ul class="pagination" id="support-pagination">

                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Knowledge Base Section -->
        <div class="card crm-card mt-4">
            <div class="card-body">
                <h4 class="header-title mb-4">Knowledge Base</h4>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="kb-card">
                            <div class="kb-card-icon bg-primary-subtle text-primary rounded-circle mb-3">
                                <i class="fas fa-book"></i>
                            </div>
                            <h5>Getting Started</h5>
                            <p class="text-muted">Basic guides and tutorials for new users</p>
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#userGuideModal">View Articles</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="kb-card">
                            <div class="kb-card-icon bg-success-subtle text-success rounded-circle mb-3">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <h5>Account Settings</h5>
                            <p class="text-muted">Learn how to manage your account settings</p>
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#accountSettingsModal">View Articles</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="kb-card">
                            <div class="kb-card-icon bg-info-subtle text-info rounded-circle mb-3">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <h5>FAQs</h5>
                            <p class="text-muted">Frequently asked questions and answers</p>
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#faqsModal">View Articles</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Ticket Modal -->
<div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="newTicketModalLabel">Create New Support Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createNewTicketForm" action="{{ route('company.support.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6 form-floating">
                            <input type="text" class="form-control" name="customer" id="customer"
                                placeholder="Customer" value="{{ Auth::user()->companyProfile->company_name }}"
                                readonly>
                            <label class="form-label" for="customer">Customer</label>
                        </div>
                        <div class="col-md-6 form-floating">

                            <select class="form-select" name="priority" id="priority" required>
                                <option value="">Select Priority</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                            <label class="form-label" for="priority">Priority</label>
                        </div>
                    </div>
                    <div class="mb-3 form-floating">
                        <input type="text" class="form-control" name="subject" id="subject"
                            placeholder="Subject" required>
                        <label class="form-label" for="subject">Subject</label>
                    </div>
                    <div class="mb-3 form-floating">
                        <input type="text" class="form-control" name="description" id="description"
                            placeholder="Description" required>
                        <label class="form-label" for="description">Description</label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-floating">

                            <select class="form-select " name="category" id="category" required>
                                <option value="">Select Category</option>
                                <option value="technical">Technical Issue</option>
                                <option value="billing">Billing</option>
                                <option value="feature">Feature Request</option>
                                <option value="general">General Inquiry</option>
                            </select>
                            <label class="form-label" for="category">Category</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="assignManually">
                            <label class="form-check-label" for="assignManually">Assign manually to an agent</label>
                        </div>

                        <div class="col-md-6 form-floating d-none" id="agentSelectContainer">
                            <select class="form-select" name="agent_id" id="agent_id">
                                <option value="">Select Agent</option>
                            </select>
                            <label class="form-label" for="agent_id">Assign To</label>
                        </div>

                    </div>
                    <div class="mb-3 form-floating">
                        <input type="file" class="form-control" name="attachments[]" id="attachments" multiple>
                        <label class="form-label" for="attachments">Attachments</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer text-center">
                <div class="text-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="createNewTicket">Create Ticket</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Guide Modal -->
<div class="modal fade" id="userGuideModal" tabindex="-1" aria-labelledby="userGuideModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="userGuideModalLabel">CRM System User Guide</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Navigation Sidebar -->
                    <div class="col-md-3">
                        <div class="list-group sticky-top" style="top: 20px;">
                            <a href="#gettingStarted" class="list-group-item list-group-item-action">
                                <i class="fas fa-rocket me-2"></i> Getting Started
                            </a>
                            <a href="#leadsGuide" class="list-group-item list-group-item-action">
                                <i class="fas fa-bullseye me-2"></i> Leads Management
                            </a>
                            <a href="#customersGuide" class="list-group-item list-group-item-action">
                                <i class="fas fa-users me-2"></i> Customer Management
                            </a>
                            <a href="#salesGuide" class="list-group-item list-group-item-action">
                                <i class="fas fa-chart-line me-2"></i> Sales & Opportunities
                            </a>
                            <a href="#marketingGuide" class="list-group-item list-group-item-action">
                                <i class="fas fa-envelope me-2"></i> Marketing
                            </a>
                            <a href="#reportsGuide" class="list-group-item list-group-item-action">
                                <i class="fas fa-chart-pie me-2"></i> Reports & Analytics
                            </a>
                            <a href="#supportGuide" class="list-group-item list-group-item-action">
                                <i class="fas fa-life-ring me-2"></i> Support & Tickets
                            </a>
                            <a href="#settingsGuide" class="list-group-item list-group-item-action">
                                <i class="fas fa-cog me-2"></i> System Settings
                            </a>
                            <a href="#bestPractices" class="list-group-item list-group-item-action">
                                <i class="fas fa-star me-2"></i> Best Practices
                            </a>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="col-md-9">
                        <!-- Getting Started -->
                        <div id="gettingStarted" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-rocket text-primary me-2"></i> Getting Started</h4>
                                <span class="badge bg-primary">Beginner</span>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> Welcome to our CRM system! Follow this guide to
                                maximize your productivity.
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-globe me-2"></i> System Overview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check-circle text-success"></i></span>Comprehensive
                                                    Customer Relationship Management</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check-circle text-success"></i></span>Integrated
                                                    Ticketing System</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check-circle text-success"></i></span>Advanced
                                                    Reporting & Analytics</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check-circle text-success"></i></span>Multi-user
                                                    Support with Role-based Access</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check-circle text-success"></i></span>Mobile
                                                    Responsive Interface</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check-circle text-success"></i></span>Third-party
                                                    Integrations</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-sign-in-alt me-2"></i> Accessing the System</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Login Process</h6>
                                            <ol>
                                                <li>Navigate to the login page</li>
                                                <li>Enter your email and password</li>
                                                <li>Select your company from the dropdown</li>
                                                <li>Click "Sign In"</li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Dashboard Overview</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-bar text-info"></i></span>Quick stats
                                                    widgets</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-bell text-warning"></i></span>Recent activity
                                                    feed</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-tasks text-primary"></i></span>Task
                                                    management</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-compass me-2"></i> Navigation Tips</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Main Navigation</h6>
                                            <div class="d-flex align-items-center mb-3">
                                                <span class="badge bg-primary me-2">1</span>
                                                <span>Use the left sidebar for primary modules</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <span class="badge bg-primary me-2">2</span>
                                                <span>Quick access menu at the top</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Quick Actions</h6>
                                            <div class="d-flex align-items-center mb-3">
                                                <span class="badge bg-success me-2">1</span>
                                                <span>Press <kbd>/</kbd> to focus search</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-success me-2">2</span>
                                                <span>Use <kbd>Ctrl+K</kbd> for command palette</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Leads Management -->
                        <div id="leadsGuide" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-bullseye text-primary me-2"></i> Leads Management</h4>
                                <span class="badge bg-success">Intermediate</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-plus-circle me-2"></i> Lead Creation</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Manual Creation</h6>
                                            <ol>
                                                <li>Click "New Lead" button</li>
                                                <li>Fill in contact details</li>
                                                <li>Assign lead source and status</li>
                                                <li>Save the record</li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Bulk Import</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-excel text-success"></i></span>Download
                                                    template</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-upload text-primary"></i></span>Upload
                                                    completed file</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check-double text-info"></i></span>Map fields
                                                    and import</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-tasks me-2"></i> Lead Tracking</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Status Management</h6>
                                            <div class="mb-3">
                                                <span class="badge bg-secondary">New</span>
                                                <span class="badge bg-info">Contacted</span>
                                                <span class="badge bg-warning">Qualified</span>
                                                <span class="badge bg-success">Converted</span>
                                            </div>
                                            <p>Update status as lead progresses through pipeline</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Follow-up System</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-bell text-warning"></i></span>Set reminders
                                                    for follow-ups</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-history text-info"></i></span>View
                                                    interaction history</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar-check text-success"></i></span>Schedule
                                                    meetings</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-exchange-alt me-2"></i> Lead Conversion</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Conversion Process</h6>
                                            <ol>
                                                <li>Open lead details</li>
                                                <li>Click "Convert to Customer"</li>
                                                <li>Verify information</li>
                                                <li>Confirm conversion</li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Post-Conversion</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-plus text-success"></i></span>Customer
                                                    record created</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-project-diagram text-info"></i></span>Opportunity
                                                    generated</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-line text-primary"></i></span>Added to
                                                    sales pipeline</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Management -->
                        <div id="customersGuide" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-users text-primary me-2"></i> Customer Management</h4>
                                <span class="badge bg-success">Intermediate</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-address-card me-2"></i> Customer Profiles</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Profile Management</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-edit text-info"></i></span>Complete
                                                    customer information</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-phone text-success"></i></span>Multiple
                                                    contact methods</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-tags text-primary"></i></span>Custom tags and
                                                    categories</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-history text-warning"></i></span>Interaction
                                                    history</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Profile Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-contract text-info"></i></span>Contract
                                                    management</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-line text-success"></i></span>Revenue
                                                    tracking</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-star text-warning"></i></span>Customer
                                                    satisfaction scores</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar-check text-primary"></i></span>Renewal
                                                    reminders</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-comments me-2"></i> Customer Interactions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Communication Tools</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-envelope text-info"></i></span>Email
                                                    integration</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-phone text-success"></i></span>Call logging
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-video text-warning"></i></span>Video
                                                    conference links</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-paperclip text-primary"></i></span>File
                                                    attachments</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Interaction Tracking</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-info"></i></span>Time-stamped
                                                    records</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-tag text-success"></i></span>User
                                                    attribution</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-search text-warning"></i></span>Full-text
                                                    search</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-filter text-primary"></i></span>Advanced
                                                    filtering</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-chart-bar me-2"></i> Customer Analytics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Key Metrics</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-users text-info"></i></span>Customer lifetime
                                                    value</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-arrow-up text-success"></i></span>Customer
                                                    growth rate</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-line text-warning"></i></span>Engagement
                                                    trends</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-star text-primary"></i></span>Satisfaction
                                                    scores</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Reporting Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-excel text-info"></i></span>Export to
                                                    Excel</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-pie text-success"></i></span>Custom
                                                    dashboards</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-warning"></i></span>Automated
                                                    reports</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-share-alt text-primary"></i></span>Shareable
                                                    dashboards</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sales & Opportunities -->
                        <div id="salesGuide" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-chart-line text-primary me-2"></i> Sales & Opportunities</h4>
                                <span class="badge bg-warning">Advanced</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-handshake me-2"></i> Opportunity Management</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Opportunity Stages</h6>
                                            <div class="mb-3">
                                                <span class="badge bg-secondary">Prospecting</span>
                                                <span class="badge bg-info">Qualification</span>
                                                <span class="badge bg-warning">Proposal</span>
                                                <span class="badge bg-success">Won</span>
                                                <span class="badge bg-danger">Lost</span>
                                            </div>
                                            <p>Track opportunities through their entire lifecycle</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Key Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-percentage text-info"></i></span>Win
                                                    probability tracking</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-dollar-sign text-success"></i></span>Revenue
                                                    forecasting</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar text-warning"></i></span>Deadline
                                                    management</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-group text-primary"></i></span>Team
                                                    collaboration</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-chart-line me-2"></i> Sales Forecasting</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Forecast Types</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar text-info"></i></span>Monthly
                                                    forecasts</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar-alt text-success"></i></span>Quarterly
                                                    forecasts</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar-times text-warning"></i></span>Annual
                                                    forecasts</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar-plus text-primary"></i></span>Custom
                                                    periods</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Forecast Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-bar text-info"></i></span>Visual charts
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calculator text-success"></i></span>Scenario
                                                    analysis</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-share-alt text-warning"></i></span>Team
                                                    sharing</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-export text-primary"></i></span>Export
                                                    options</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-users me-2"></i> Team Collaboration</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Team Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-plus text-info"></i></span>User
                                                    assignments</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-comments text-success"></i></span>Internal
                                                    notes</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-bell text-warning"></i></span>Activity
                                                    notifications</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-pie text-primary"></i></span>Team
                                                    performance</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Collaboration Tools</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-share text-info"></i></span>Share
                                                    opportunities</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-comments text-success"></i></span>Team
                                                    discussions</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-contract text-warning"></i></span>Shared
                                                    documents</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar-check text-primary"></i></span>Shared
                                                    calendar</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Marketing -->
                        <div id="marketingGuide" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-envelope text-primary me-2"></i> Marketing</h4>
                                <span class="badge bg-info">Intermediate</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-bullhorn me-2"></i> Campaign Management</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Campaign Types</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-envelope text-info"></i></span>Email
                                                    campaigns</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-ad text-success"></i></span>Ad campaigns</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-newspaper text-warning"></i></span>Content
                                                    campaigns</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-users text-primary"></i></span>Multi-channel
                                                    campaigns</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Campaign Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar text-info"></i></span>Scheduling
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-bar text-success"></i></span>Performance
                                                    tracking</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-tags text-warning"></i></span>Segmentation
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-share-alt text-primary"></i></span>Automation
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-chart-pie me-2"></i> Marketing Analytics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Key Metrics</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-eye text-info"></i></span>Open rates</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-link text-success"></i></span>Click-through
                                                    rates</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-check text-warning"></i></span>Conversion
                                                    rates</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-line text-primary"></i></span>ROI
                                                    tracking</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Analysis Tools</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-bar text-info"></i></span>Visual
                                                    reporting</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-compare text-success"></i></span>A/B testing
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-filter text-warning"></i></span>Segment
                                                    analysis</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-primary"></i></span>Time-based
                                                    analysis</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-envelope-open me-2"></i> Email Marketing</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Email Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-paint-brush text-info"></i></span>Template
                                                    builder</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-list text-success"></i></span>Segmentation
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-warning"></i></span>Scheduling
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-line text-primary"></i></span>Performance
                                                    tracking</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Advanced Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-code text-info"></i></span>Custom HTML</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-robot text-success"></i></span>Automation
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-pie text-warning"></i></span>Analytics
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-alt text-primary"></i></span>Compliance
                                                    tools</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reports & Analytics -->
                        <div id="reportsGuide" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-chart-pie text-primary me-2"></i> Reports & Analytics</h4>
                                <span class="badge bg-success">Advanced</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-chart-bar me-2"></i> Sales Reports</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Report Types</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar text-info"></i></span>Monthly
                                                    reports</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar-alt text-success"></i></span>Quarterly
                                                    reports</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar-times text-warning"></i></span>Annual
                                                    reports</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar-plus text-primary"></i></span>Custom
                                                    reports</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Report Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-excel text-info"></i></span>Excel export
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-pie text-success"></i></span>Visual
                                                    charts</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-share-alt text-warning"></i></span>Shareable
                                                    links</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-primary"></i></span>Scheduled
                                                    delivery</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-chart-line me-2"></i> Customer Analytics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Key Metrics</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-users text-info"></i></span>Customer growth
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-star text-success"></i></span>Satisfaction
                                                    scores</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-line text-warning"></i></span>Engagement
                                                    trends</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-money-bill text-primary"></i></span>Revenue
                                                    analysis</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Analysis Tools</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-bar text-info"></i></span>Segment
                                                    analysis</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-compare text-success"></i></span>Cohort
                                                    analysis</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-filter text-warning"></i></span>Custom
                                                    filtering</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-primary"></i></span>Time-based
                                                    analysis</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-chart-pie me-2"></i> Custom Reports</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Customization Options</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-cogs text-info"></i></span>Custom metrics
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-filter text-success"></i></span>Advanced
                                                    filtering</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-bar text-warning"></i></span>Custom
                                                    visualizations</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-share-alt text-primary"></i></span>Shared
                                                    templates</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Automation Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-info"></i></span>Scheduled updates
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-envelope text-success"></i></span>Email
                                                    delivery</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-export text-warning"></i></span>Automated
                                                    exports</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-share text-primary"></i></span>Team sharing
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Settings -->
                        <div id="settingsGuide" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-cog text-primary me-2"></i> System Settings</h4>
                                <span class="badge bg-info">Advanced</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-users me-2"></i> User Management</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>User Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-plus text-info"></i></span>User creation
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-key text-success"></i></span>Role management
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-lock text-warning"></i></span>Access control
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-alt text-primary"></i></span>Security
                                                    settings</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Team Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-group text-info"></i></span>Team
                                                    creation</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-line text-success"></i></span>Performance
                                                    tracking</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-share-alt text-warning"></i></span>Resource
                                                    sharing</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar-check text-primary"></i></span>Scheduling
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-building me-2"></i> Company Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Company Information</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-address-card text-info"></i></span>Company
                                                    profile</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-map-marker-alt text-success"></i></span>Location
                                                    settings</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-phone text-warning"></i></span>Contact
                                                    information</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-envelope text-primary"></i></span>Email
                                                    settings</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Customization</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-paint-brush text-info"></i></span>Branding
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-language text-success"></i></span>Language
                                                    settings</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-cog text-warning"></i></span>Custom fields
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-pie text-primary"></i></span>Dashboard
                                                    widgets</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-plug me-2"></i> Integration Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Available Integrations</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-envelope text-info"></i></span>Email systems
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar text-success"></i></span>Calendar
                                                    tools</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-excel text-warning"></i></span>Analytics
                                                    platforms</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-cloud text-primary"></i></span>Cloud storage
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>API Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-key text-info"></i></span>API keys</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-code text-success"></i></span>Documentation
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-alt text-warning"></i></span>Security
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-line text-primary"></i></span>Usage
                                                    tracking</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Best Practices -->
                        <div id="bestPractices" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-star text-primary me-2"></i> Best Practices</h4>
                                <span class="badge bg-success">All Levels</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-database me-2"></i> Data Management</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Data Quality</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check text-info"></i></span>Regular data
                                                    cleaning</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-sync text-success"></i></span>Data validation
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-lock text-warning"></i></span>Data security
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-trash text-primary"></i></span>Data archiving
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Data Organization</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-tags text-info"></i></span>Consistent tagging
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-folder text-success"></i></span>Folder
                                                    structure</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-search text-warning"></i></span>Search
                                                    optimization</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-bar text-primary"></i></span>Reporting
                                                    setup</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-tasks me-2"></i> Process Optimization</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Workflow Best Practices</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-line text-info"></i></span>Standard
                                                    processes</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-robot text-success"></i></span>Automation
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-warning"></i></span>Time tracking
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-pie text-primary"></i></span>Performance
                                                    metrics</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Team Collaboration</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-group text-info"></i></span>Clear roles
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-comments text-success"></i></span>Communication
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-share-alt text-warning"></i></span>Resource
                                                    sharing</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-line text-primary"></i></span>Progress
                                                    tracking</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-bullseye me-2"></i> Goal Setting</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>SMART Goals</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check text-info"></i></span>Specific</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-ruler text-success"></i></span>Measurable
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-warning"></i></span>Achievable
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar text-primary"></i></span>Time-bound
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Tracking & Analysis</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-bar text-info"></i></span>Progress
                                                    tracking</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-adjust text-success"></i></span>Performance
                                                    metrics</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-line text-warning"></i></span>Trend
                                                    analysis</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-chart-pie text-primary"></i></span>ROI
                                                    calculation</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Account Settings Modal -->
<div class="modal fade" id="accountSettingsModal" tabindex="-1" aria-labelledby="accountSettingsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="accountSettingsModalLabel">Account Settings Guide</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Navigation Sidebar -->
                    <div class="col-md-3">
                        <div class="list-group sticky-top" style="top: 20px;">
                            <a href="#profileSettings" class="list-group-item list-group-item-action active">
                                <i class="fas fa-user me-2"></i> Profile Settings
                            </a>
                            <a href="#securitySettings" class="list-group-item list-group-item-action">
                                <i class="fas fa-shield-alt me-2"></i> Security Settings
                            </a>
                            <a href="#notificationSettings" class="list-group-item list-group-item-action">
                                <i class="fas fa-bell me-2"></i> Notifications
                            </a>
                            <a href="#displaySettings" class="list-group-item list-group-item-action">
                                <i class="fas fa-paint-brush me-2"></i> Display Settings
                            </a>
                            <a href="#apiSettings" class="list-group-item list-group-item-action">
                                <i class="fas fa-code me-2"></i> API Settings
                            </a>
                            <a href="#backupSettings" class="list-group-item list-group-item-action">
                                <i class="fas fa-save me-2"></i> Backup & Export
                            </a>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="col-md-9">
                        <!-- Profile Settings -->
                        <div id="profileSettings" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-user text-success me-2"></i> Profile Settings</h4>
                                <span class="badge bg-success">Beginner</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-user-edit me-2"></i> Profile Management</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Basic Profile</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-id-card text-info"></i></span>Personal
                                                    information</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-envelope text-success"></i></span>Email
                                                    settings</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-phone text-warning"></i></span>Contact
                                                    numbers</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-map-marker-alt text-primary"></i></span>Location
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Advanced Profile</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-briefcase text-info"></i></span>Professional
                                                    details</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-link text-success"></i></span>Social media
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-birthday-cake text-warning"></i></span>Personal
                                                    preferences</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar text-primary"></i></span>Availability
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-key me-2"></i> Password Management</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Password Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-lock text-info"></i></span>Password
                                                    requirements</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-sync text-success"></i></span>Password reset
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-warning"></i></span>Password
                                                    history</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-alt text-primary"></i></span>Security
                                                    questions</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Best Practices</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check text-info"></i></span>Use strong
                                                    passwords</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-refresh text-success"></i></span>Regular
                                                    updates</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield text-warning"></i></span>Two-factor
                                                    auth</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-info-circle text-primary"></i></span>Password
                                                    managers</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Settings -->
                        <div id="securitySettings" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-shield-alt text-success me-2"></i> Security Settings</h4>
                                <span class="badge bg-warning">Intermediate</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-fingerprint me-2"></i> Authentication</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Two-Factor Auth</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-mobile-alt text-info"></i></span>Phone
                                                    verification</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-qrcode text-success"></i></span>Authenticator
                                                    apps</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-key text-warning"></i></span>Backup codes
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-alt text-primary"></i></span>Security
                                                    keys</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Session Management</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-info"></i></span>Session timeout
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-history text-success"></i></span>Session
                                                    history</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-map-marker-alt text-warning"></i></span>Location
                                                    tracking</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-mobile-alt text-primary"></i></span>Device
                                                    management</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-shield text-info"></i> Access Control</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>User Roles</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-shield text-info"></i></span>Role
                                                    management</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-cog text-success"></i></span>Permission
                                                    settings</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-users text-warning"></i></span>Team access
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-lock text-primary"></i></span>Access
                                                    logs</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Security Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-alt text-info"></i></span>IP
                                                    whitelisting</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-lock text-success"></i></span>API security
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-ban text-warning"></i></span>Rate limiting
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-virus text-primary"></i></span>Security
                                                    audits</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div id="notificationSettings" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-bell text-success me-2"></i> Notifications</h4>
                                <span class="badge bg-info">Beginner</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-envelope me-2"></i> Email Notifications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Email Settings</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-envelope text-info"></i></span>Email
                                                    templates</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-bell text-success"></i></span>Notification
                                                    types</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-warning"></i></span>Schedule
                                                    emails</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-filter text-primary"></i></span>Filter
                                                    preferences</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Best Practices</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check text-info"></i></span>Regular updates
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-bell-slash text-success"></i></span>Manage
                                                    subscriptions</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-envelope-open text-warning"></i></span>Test
                                                    notifications</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-user-shield text-primary"></i></span>Security
                                                    alerts</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-mobile-alt me-2"></i> Mobile Notifications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Mobile Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-mobile-alt text-info"></i></span>Push
                                                    notifications</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-bell text-success"></i></span>Alert
                                                    preferences</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-warning"></i></span>Schedule
                                                    alerts</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-battery-full text-primary"></i></span>Battery
                                                    optimization</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Best Practices</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check text-info"></i></span>Regular updates
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-bell-slash text-success"></i></span>Manage
                                                    subscriptions</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-mobile-alt text-warning"></i></span>Test
                                                    notifications</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-alt text-primary"></i></span>Security
                                                    alerts</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-paint-brush text-success me-2"></i> Display Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Theme Options</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-sun text-warning"></i></span>Light theme
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-moon text-info"></i></span>Dark theme</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-palette text-success"></i></span>Color
                                                    customization</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-cog text-primary"></i></span>Theme switching
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Layout Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-columns text-info"></i></span>Layout options
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-arrows-alt text-success"></i></span>Responsive
                                                    design</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-cog text-warning"></i></span>Custom widgets
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-desktop text-primary"></i></span>Screen
                                                    optimization</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-language text-info"></i> Language Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Language Options</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-globe text-info"></i></span>Language
                                                    selection</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-language text-success"></i></span>Regional
                                                    settings</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-warning"></i></span>Time zone
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar text-primary"></i></span>Date
                                                    formats</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Best Practices</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check text-info"></i></span>Set primary
                                                    language</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-language text-success"></i></span>Regional
                                                    preferences</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-warning"></i></span>Time zone
                                                    accuracy</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-calendar text-primary"></i></span>Date
                                                    format consistency</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-code text-success me-2"></i> API Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>API Keys</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-key text-info"></i></span>Generate keys</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-copy text-success"></i></span>Copy keys</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-trash text-warning"></i></span>Revoke keys
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-refresh text-primary"></i></span>Rotate keys
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Security Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-alt text-info"></i></span>Key
                                                    rotation</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-lock text-success"></i></span>Access control
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-ban text-warning"></i></span>Rate limiting
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-virus text-primary"></i></span>IP
                                                    whitelisting</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-code-branch text-info"></i> API Integration</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Integration Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-plug text-info"></i></span>Third-party apps
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-link text-success"></i></span>Webhooks</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-sync text-warning"></i></span>Sync settings
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-code text-primary"></i></span>Custom
                                                    endpoints</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Best Practices</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check text-info"></i></span>Secure
                                                    integration</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-sync text-success"></i></span>Regular
                                                    updates</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-alt text-warning"></i></span>Monitor
                                                    access</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-info-circle text-primary"></i></span>Documentation
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-save text-success me-2"></i> Backup & Export</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Backup Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-save text-info"></i></span>Automatic backups
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-success"></i></span>Schedule
                                                    backups</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-history text-warning"></i></span>Version
                                                    history</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-cloud text-primary"></i></span>Cloud storage
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Best Practices</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-check text-info"></i></span>Regular backups
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-history text-success"></i></span>Version
                                                    control</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-shield-alt text-warning"></i></span>Security
                                                    measures</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-cloud-upload-alt text-primary"></i></span>Cloud
                                                    sync</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-file-export text-info"></i> Data Export</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Export Formats</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-excel text-info"></i></span>Excel
                                                    export</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-pdf text-success"></i></span>PDF export
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-csv text-warning"></i></span>CSV export
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-file-archive text-primary"></i></span>ZIP
                                                    export</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Export Features</h6>
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i
                                                            class="fas fa-filter text-info"></i></span>Custom filters
                                                </li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-clock text-success"></i></span>Schedule
                                                    exports</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-history text-warning"></i></span>Export
                                                    history</li>
                                                <li><span class="fa-li"><i
                                                            class="fas fa-cloud-download-alt text-primary"></i></span>Cloud
                                                    download</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- FAQs Modal -->
<div class="modal fade" id="faqsModal" tabindex="-1" aria-labelledby="faqsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="faqsModalLabel">FAQs - Frequently Asked Questions</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Navigation Sidebar -->
                    <div class="col-md-3">
                        <div class="list-group sticky-top" style="top: 20px;">
                            <a href="#generalFAQs" class="list-group-item list-group-item-action active">
                                <i class="fas fa-question me-2"></i> General Questions
                            </a>
                            <a href="#accountFAQs" class="list-group-item list-group-item-action">
                                <i class="fas fa-user me-2"></i> Account & Profile
                            </a>
                            <a href="#usageFAQs" class="list-group-item list-group-item-action">
                                <i class="fas fa-cog me-2"></i> Usage & Features
                            </a>
                            <a href="#technicalFAQs" class="list-group-item list-group-item-action">
                                <i class="fas fa-tools me-2"></i> Technical Issues
                            </a>
                            <a href="#billingFAQs" class="list-group-item list-group-item-action">
                                <i class="fas fa-credit-card me-2"></i> Billing & Payments
                            </a>
                            <a href="#supportFAQs" class="list-group-item list-group-item-action">
                                <i class="fas fa-life-ring me-2"></i> Support & Help
                            </a>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="col-md-9">
                        <!-- General Questions -->
                        <div id="generalFAQs" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-question text-info me-2"></i> General Questions</h4>
                                <span class="badge bg-info">Beginner</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-info-circle me-2"></i> What is this system?</h5>
                                </div>
                                <div class="card-body">
                                    <p>This is a comprehensive CRM system designed to help you manage your customer
                                        relationships, sales pipeline, and support tickets.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-users me-2"></i> Who can use this system?</h5>
                                </div>
                                <div class="card-body">
                                    <p>The system is designed for businesses of all sizes, from small teams to large
                                        enterprises. It's particularly useful for sales teams, customer support teams,
                                        and business owners.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-clock me-2"></i> How long does it take to get started?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Most users can get started within 30 minutes. We also provide a comprehensive
                                        user guide and support system to help you along the way.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Account & Profile -->
                        <div id="accountFAQs" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-user text-info me-2"></i> Account & Profile</h4>
                                <span class="badge bg-info">Beginner</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-key me-2"></i> How do I change my password?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Go to Account Settings > Security > Password Management to change your password.
                                        You'll need to enter your current password for verification.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-user-edit me-2"></i> Can I customize my profile?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Yes, you can customize your profile picture, bio, and contact information in the
                                        Profile Settings section.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-users me-2"></i> How do I add team members?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Go to System Settings > User Management to add new team members and assign roles
                                        and permissions.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Usage & Features -->
                        <div id="usageFAQs" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-cog text-info me-2"></i> Usage & Features</h4>
                                <span class="badge bg-info">Intermediate</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-chart-line me-2"></i> How do I track sales?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Use the Sales Pipeline module to track opportunities, set up forecasts, and
                                        monitor your team's performance.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-envelope me-2"></i> Can I send emails from the system?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Yes, you can send emails directly from customer profiles and track email
                                        interactions in the customer timeline.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-mobile-alt me-2"></i> Is there a mobile app?</h5>
                                </div>
                                <div class="card-body">
                                    <p>The system is fully responsive and works well on mobile devices. We also offer
                                        native mobile apps for iOS and Android.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Technical Issues -->
                        <div id="technicalFAQs" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-tools text-info me-2"></i> Technical Issues</h4>
                                <span class="badge bg-warning">Advanced</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-bug me-2"></i> What if I encounter an error?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Take a screenshot of the error, note the steps that led to it, and contact
                                        support through the Support Tickets module.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-shield-alt me-2"></i> How secure is my data?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Your data is encrypted both at rest and in transit. We follow strict security
                                        protocols and perform regular security audits.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-cloud-upload-alt me-2"></i> Can I backup my data?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Yes, you can export your data in various formats through the System Settings >
                                        Backup & Export section.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Billing & Payments -->
                        <div id="billingFAQs" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-credit-card text-info me-2"></i> Billing & Payments</h4>
                                <span class="badge bg-info">Beginner</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-money-bill me-2"></i> How does billing work?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Billing is based on the number of users and features you need. You can manage
                                        your subscription in the Billing Settings section.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-exchange-alt me-2"></i> Can I change my plan?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Yes, you can upgrade or downgrade your plan at any time through the Billing
                                        Settings section.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-undo me-2"></i> What is your refund policy?</h5>
                                </div>
                                <div class="card-body">
                                    <p>We offer a 30-day money-back guarantee. If you're not satisfied, contact support
                                        within 30 days for a full refund.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Support & Help -->
                        <div id="supportFAQs" class="guide-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-life-ring text-info me-2"></i> Support & Help</h4>
                                <span class="badge bg-info">Beginner</span>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-ticket-alt me-2"></i> How do I get support?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Create a support ticket through the Support Tickets module or contact us through
                                        our website.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-clock me-2"></i> What are your support hours?</h5>
                                </div>
                                <div class="card-body">
                                    <p>We offer 24/7 support for premium customers and business hours support for
                                        standard customers.</p>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5><i class="fas fa-book me-2"></i> Where can I find more help?</h5>
                                </div>
                                <div class="card-body">
                                    <p>Check our knowledge base, user guides, and video tutorials available in the Help
                                        Center.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Ticket Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewTicketModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTicketModalLabel">Ticket Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ticket-header mb-4">
                    <div class="d-flex justify-content-between align-items-center" id="ticketDetails">
                        <h4>#<span class="ticket-id"></span></span></h4>
                        <span>Subject:<span class="ticket-subject"></span></span>
                        <span>Priority:<span class=" badge ticket-priority"></span></span>
                        <span>Status:<span class="  badge ticket-status"></span></span>
                    </div><br>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Description:<p class="ticket-description"></p></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Attachments:<p class="ticket-attachment"></p></span>

                    </div>
                    <br>

                    <div class="ticket-meta text-muted">
                        <span><i class="fas fa-user me-1"></i> <span class="ticket-customer"></span></span>
                        <span class="mx-2">|</span>
                        <span><i class="fas fa-calendar me-1"></i> <span class="ticket-created_at"></span></span>
                        <span class="mx-2">|</span>
                        <span><i class="fas fa-tag me-1"></i> <span class="ticket-category"></span></span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Add Knowledge Base Article Modal -->
<div class="modal fade" id="addKbArticleModal" tabindex="-1" aria-labelledby="addKbArticleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="addKbArticleModalLabel">Add Knowledge Base Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="kbArticleForm">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="getting_started">Getting Started</option>
                            <option value="account">Account Management</option>
                            <option value="billing">Billing & Subscription</option>
                            <option value="features">Features & Tutorials</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <div id="kbEditor" style="height: 300px;"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <input type="text" class="form-control" data-role="tagsinput">
                        <small class="text-muted">Separate tags with commas</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer text-center">
                <div class="text-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Publish Article</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Live Chat Feature -->
<style>
    /* Chat Popup Styles */
    .chat-popup {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 350px;
        height: 500px;
        background-color: var(--ct-card-bg);
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        z-index: 1050;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: all 0.3s ease;
    }

    .chat-popup.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .chat-popup-header {
        padding: 1rem;
        border-bottom: 1px solid var(--ct-border-color);
    }

    .chat-button {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 1000;
    }
</style>

<div class="chat-popup" id="chat-popup">
    <div class="chat-popup-header">
        <h5>Live Chat</h5>
        <button type="button" class="close" onclick="toggleChat()">&times;</button>
    </div>
    <div class="chat-popup-messages">
        <!-- Chat messages will go here -->
    </div>
    <div class="chat-popup-input">
        <input type="text" placeholder="Type a message..." />
        <button type="button">Send</button>
    </div>
</div>

<button class="chat-button" onclick="toggleChat()">
    <i class="ri-chat-3-line"></i>
</button>

<!-- Add custom styles -->
<style>
    .conversation-item {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .conversation-item.customer {
        background-color: #f8f9fa;
        margin-right: 2rem;
    }

    .conversation-item.support {
        background-color: #e9ecef;
        margin-left: 2rem;
    }

    .conversation-header {
        margin-bottom: 0.5rem;
    }

    .attachment-preview {
        background: #fff;
        padding: 0.5rem;
        border-radius: 0.25rem;
        margin-top: 0.5rem;
    }

    .kb-card {
        padding: 1.5rem;
        border-radius: 0.5rem;
        background: #fff;
        transition: transform 0.2s;
        border: 1px solid #e9ecef;
    }

    .kb-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .kb-card-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .ticket-meta span {
        font-size: 0.875rem;
    }

    .support-stats {
        background: #fff;
        padding: 1.25rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        transition: transform 0.2s;
    }

    .support-stats:hover {
        transform: translateY(-3px);
    }

    .modal-content {
        background-color: white;
        color: black;
    }

    .modal-header,
    .modal-footer {
        border-color: rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Add necessary JavaScript -->

<script>
    $(document).ready(function() {
        // Handle ticket creation form submission
        $('#createNewTicket').on('click', function() {
            const $button = $(this);
            const originalText = $button.html();
            const formData = new FormData($('#createNewTicketForm')[0]);

            // Start loading: disable button and show spinner
            $button.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...
    `);

            $.ajax({
                url: $('#createNewTicketForm').attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showSafeSweetAlert({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Ticket created successfully.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const modal = bootstrap.Modal.getInstance(document
                                    .getElementById('newTicketModal'));
                                if (modal) {
                                    modal.hide();
                                    $('#newTicketModal').on('hidden.bs.modal',
                                        function() {
                                            $('.modal-backdrop').remove();
                                            $(this).off('hidden.bs.modal');
                                        });
                                }
                                fetchSupportData();
                            }
                        });
                    } else {
                        showSafeSweetAlert({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to create ticket.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while creating the ticket.',
                        confirmButtonText: 'OK'
                    });
                    console.log("Error:", error);
                },
                complete: function() {
                    // Reset button state after success or error
                    $button.prop('disabled', false).html(originalText);
                }
            });
        });

        // Initialize Quill editors
        const editors = ['ticketEditor', 'replyEditor', 'kbEditor'].map(id => {
            return new Quill(`#${id}`, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        ['link', 'blockquote', 'code-block'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }]
                    ]
                }
            });
        });

        // Initialize DataTable
        $('#support-tickets-datatable').DataTable({
            pageLength: 10,
            order: [
                [0, 'desc']
            ],
            responsive: true
        });

        // Initialize Bootstrap Tags Input
        $('input[data-role="tagsinput"]').tagsinput();

        // View Ticket Handler
        $('.view-ticket').on('click', function() {
            $('#viewTicketModal').modal('show');
        });

        // Add KB Article Handler
        $('#addKbArticle').on('click', function() {
            $('#addKbArticleModal').modal('show');
        });

        // Ticket Status Change Handler
        $('.change-status').on('change', function() {
            // Handle status change
        });
    });

    document.getElementById('assignManually').addEventListener('change', function() {
        const agentSelect = document.getElementById('agentSelectContainer');
        if (this.checked) {
            agentSelect.classList.remove('d-none');

            $('#agent_id').html('<option value="">Select Agent</option>');

            $.ajax({
                url: '/api/agents',
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    if (response.success && response.agents) {
                        response.agents.forEach(function(agent) {
                            $('#agent_id').append(
                                $('<option>', {
                                    value: agent.id,
                                    text: agent.name + ' (' + agent.email + ')'
                                })
                            );
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching agents:', xhr.responseText);
                }
            });
        } else {
            agentSelect.classList.add('d-none');
            document.getElementById('agent_id').value = '';
        }
    });

    function showSafeSweetAlert(config) {
        return Swal.fire(config);
    }

    fetchSupportData();

    function fetchSupportData(page = 1) {
        let startDate = $('#startDate').val();
        let endDate = $('#endDate').val();
        let searchQuery = $('#searchTickets').val();

        $.ajax({
            url: `/company/support/all?page=${page}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Build the tickets table body
                let tableBody = '';
                if (response.tickets.length === 0) {
                    tableBody = `<tr><td colspan="7" class="text-center">No tickets created.</td></tr>`;
                }
                response.tickets.forEach(ticket => {
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

                    // Format created date
                    let createdDate = new Date(ticket.created_at).toLocaleDateString();

                    tableBody += `<tr>
                        <td>#${ticket.ticket_id}</td>
                        <td>${ticket.subject}</td>
                        <td>${ticket.description}</td>
                        <td><span class="badge ${priorityBadgeClass}">${ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1)}</span></td>
                        <td><span class="badge ${statusBadgeClass}">${ticket.status.replace('_', ' ')}</span></td>
                        <td>${createdDate}</td>
                      <td>
                          <div class="text-center">
                              <button type="button" class="btn btn-primary btn-sm view-ticket" data-id="${ticket.id}">
                                 <i class="fas fa-eye"></i>
                              </button>

                              ${
                                  ticket.status === 'closed'
                                     ? `<button type="button" class="btn btn-secondary btn-sm" disabled>
                                              <i class="fas fa-check-double"></i>
                                         </button>`
                                     : `<button type="button" class="btn btn-danger btn-sm close-ticket" data-id="${ticket.id}">
                                             <i class="fas fa-check"></i>
                                         </button>`
                          }
                         </div>
                     </td>

                    </tr>`;
                });

                $('#support-tickets-datatable tbody').html(tableBody);

                // Generate Pagination
                let paginationHtml = `<li class="page-item ${response.pagination.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="return supportChangePage(event, ${response.pagination.current_page - 1})">Previous</a>
                </li>`;

                for (let i = 1; i <= response.pagination.last_page; i++) {
                    paginationHtml += `<li class="page-item ${i === response.pagination.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="return supportChangePage(event, ${i})">${i}</a>
                    </li>`;
                }

                paginationHtml += `<li class="page-item ${response.pagination.current_page === response.pagination.last_page ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="return supportChangePage(event, ${response.pagination.current_page + 1})">Next</a>
                </li>`;

                $('#support-pagination').html(paginationHtml);

                // Initialize tooltips for action buttons
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            error: function(err) {
                console.log("Error:", err);
                // Show error message to user
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load tickets. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    // Prevent Page Refresh on Pagination Click
    function supportChangePage(event, page) {
        event.preventDefault();
        fetchSupportData(page);
        return false;
    }

    // Handle ticket actions
    $(document).on('click', '.view-ticket', function() {
        let ticketId = $(this).data('id');
        showTicket(ticketId);
        $("#viewModal").modal('show');
    });

    $(document).on('click', '.reply-ticket', function() {
        let ticketId = $(this).data('id');
        // Load and display reply form
    });

    $(document).on('click', '.close-ticket', function() {
        let ticketId = $(this).data('id');

        showSafeSweetAlert({
            icon: 'warning',
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonText: 'Yes, close it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                closeTicket(ticketId);
            }
        });
    });

    function showTicket(itemId) {
        $.ajax({
            url: `/company/support/show`,
            type: 'POST',
            data: {
                id: itemId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log("Response:", response, "id", itemId);

                const ticket = response.ticket;
                if (response.success && ticket) {

                    $('.ticket-id').text(ticket.ticket_id || '');
                    $('.ticket-subject').text(ticket.subject || '');
                    $('.ticket-status').text(ticket.status || '');
                    $('.ticket-customer').text(ticket.customer || '');
                    $('.ticket-created_at').text(ticket.created_at || '');
                    $('.ticket-category').text(ticket.category || '');
                    $('.ticket-description').html(ticket.description || '');
                    $('.ticket-priority').text(ticket.priority || '');

                    // Set priority badge class
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

                    // Clear previous attachments
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

                    $('#viewModal').modal('show');
                    // Update ticket details in modal
                    $('#ticketDetails').html(response.data);
                } else {
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to load ticket details.',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(error) {
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while loading the ticket.',
                    confirmButtonText: 'OK'
                });
                console.log("Error:", error, "id", itemId);
            }
        });
    }

    function closeTicket(ticketId) {
        $.ajax({
            url: `/company/support/close/${ticketId}`,
            type: 'PUT',
            data: {
                id: ticketId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showSafeSweetAlert({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Ticket closed successfully.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Refresh the tickets table
                            fetchSupportData();
                        }
                    });
                } else {
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to close ticket.',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while closing the ticket.',
                    confirmButtonText: 'OK'
                });
                console.log("Error:", error);
            }
        });
    }
</script>
