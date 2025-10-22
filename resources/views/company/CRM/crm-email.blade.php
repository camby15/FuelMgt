<!-- Email Interface -->
<div class="email-app crm-email">
    <!-- Email Sidebar -->
    <div class="email-sidebar">
        <div class="p-3">
            <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#composeEmailModal">
                <i class="fas fa-plus"></i> Compose
            </button>
        </div>

        <div class="email-nav">
            <div class="nav-item d-flex align-items-center">
                <a href="#" class="nav-link d-flex align-items-center">
                    <i class="fas fa-paper-plane text-primary me-2" style="color: #1a73e8 !important;"></i> Sent
                </a>
            </div>
            <div class="nav-item d-flex align-items-center">
                <a href="#" class="nav-link d-flex align-items-center">
                    <i class="fas fa-file me-2" style="color: #34a853 !important;"></i> Drafts
                </a>
            </div>
            <div class="nav-item d-flex align-items-center">
                <a href="#" class="nav-link d-flex align-items-center">
                    <i class="fas fa-trash me-2" style="color: #ea4335 !important;"></i> Trash
                </a>
            </div>

            <div class="email-nav-divider"></div>

            <div class="nav-label">Labels</div>
            <div class="nav-item d-flex align-items-center">
                <a href="#" class="nav-link d-flex align-items-center">
                    <i class="fas fa-circle text-primary me-2"></i> Work
                </a>
            </div>
            <div class="nav-item d-flex align-items-center">
                <a href="#" class="nav-link d-flex align-items-center">
                    <i class="fas fa-circle text-success me-2"></i> Personal
                </a>
            </div>
            <div class="nav-item d-flex align-items-center">
                <a href="#" class="nav-link d-flex align-items-center">
                    <i class="fas fa-circle text-warning me-2"></i> Important
                </a>
            </div>
        </div>
    </div>

    <!-- Email Content -->
    <div class="email-content">
        <!-- Email Toolbar -->
        
        <div class="email-toolbar p-1 d-flex align-items-center border-bottom">
            <div class="search-container">
                <div class="form-floating">
                    <input type="text" class="form-control" id="searchMail" name="searchMail" placeholder="Search" onkeyup="handleSearchInput(event)">
                    <label for="searchMail">Search mail</label>
                </div>
                <div id="searchSpinner" class="search-spinner d-none">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="noResults" class="no-results-message d-none">
                    No emails found
                </div>
            </div>
            <style>
                .search-container {
                    width: 300px;
                    margin-right: 1rem;
                    position: relative;
                }
                .form-floating {
                    position: relative;
                    margin-bottom: 1rem;
                }
                .form-floating input.form-control {
                    height: 50px;
                    border: 1px solid #2f2f2f;
                    border-radius: 10px;
                    background-color: transparent;
                    font-size: 1rem;
                    padding: 1rem 0.75rem;
                    transition: all 0.8s;
                    color: #2f2f2f;
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
                .form-floating input.form-control:not(:placeholder-shown) {
                    border-color: #033c42;
                    box-shadow: none;
                    padding-top: 1.625rem;
                    padding-bottom: 0.625rem;
                }
                .form-floating input.form-control:focus ~ label,
                .form-floating input.form-control:not(:placeholder-shown) ~ label {
                    height: auto;
                    padding: 0 0.5rem;
                    transform: translateY(-50%) translateX(0.5rem) scale(0.85);
                    color: white;
                    border-radius: 5px;
                    z-index: 5;
                }
                .form-floating input.form-control:focus ~ label::before,
                .form-floating input.form-control:not(:placeholder-shown) ~ label::before {
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
                .search-spinner {
                    position: absolute;
                    right: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                }
                .no-results-message {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    width: 100%;
                    padding: 8px;
                    background: #fff;
                    border: 1px solid #ced4da;
                    border-radius: 4px;
                    margin-top: 4px;
                    text-align: center;
                    color: #6c757d;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                .email-item.hidden {
                    display: none !important;
                }
            </style>
            <script>
                let searchTimeout;
                const searchDelay = 300; // milliseconds

                function handleSearchInput(event) {
                    const query = event.target.value;
                    clearTimeout(searchTimeout);
                    
                    if (query.length > 0) {
                        document.getElementById('searchSpinner').classList.remove('d-none');
                    }
                    
                    searchTimeout = setTimeout(() => {
                        searchEmails(query);
                    }, searchDelay);
                }

                function searchEmails(query) {
                    query = query.toLowerCase().trim();
                    const emailItems = document.querySelectorAll('.email-item');
                    const noResultsDiv = document.getElementById('noResults');
                    let hasResults = false;
                    
                    if (query === '') {
                        emailItems.forEach(item => item.classList.remove('hidden'));
                        document.getElementById('searchSpinner').classList.add('d-none');
                        noResultsDiv.classList.add('d-none');
                        return;
                    }

                    emailItems.forEach(item => {
                        const subject = (item.querySelector('.subject-cell')?.textContent || '').toLowerCase();
                        const recipient = (item.querySelector('.recipient-cell')?.textContent || '').toLowerCase();
                        const content = item.textContent.toLowerCase();
                        
                        if (subject.includes(query) || recipient.includes(query) || content.includes(query)) {
                            item.classList.remove('hidden');
                            hasResults = true;
                        } else {
                            item.classList.add('hidden');
                        }
                    });

                    document.getElementById('searchSpinner').classList.add('d-none');
                    noResultsDiv.classList.toggle('d-none', hasResults);
                }
            </script>
        </div>
        



        
        <!-- Email List Container -->
        <div class="email-list-container">
            <div class="email-list">
                <!-- Email list will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Compose Email Modal -->
<div class="modal fade" id="composeEmailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Compose New Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="composeForm" action="{{ route('company.email.send') }}" method="POST" novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 form-floating">
                        <input type="text" class="form-control" name="recipient" placeholder="Recipients" aria-required="true" required>
                        <label for="recipient">Recipients</label>
                        <div class="invalid-feedback">Please enter at least one recipient.</div>
                    </div>
                    <div class="mb-3 form-floating">
                        <input type="text" class="form-control" name="subject" placeholder="Subject" aria-required="true" required>
                        <label for="subject">Subject</label>
                        <div class="invalid-feedback">Subject is required.</div>
                    </div>
                    <div class="mb-3 form-floating">
                        <textarea class="form-control" name="message" placeholder="Message" style="height: 200px" aria-required="true" required></textarea>
                        <label for="message">Message</label>
                        <div class="invalid-feedback">Message content is required.</div>
                    </div>
         
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Discard</button>
                <button type="button" class="btn btn-info" onclick="saveDraft()">Save Draft</button>
            </div>
        </div>
    </div>
</div>

<script>
function saveDraft() {
    // Get form data
    const recipient = $('#composeForm input[name="recipient"]').val();
    const subject = $('#composeForm input[name="subject"]').val();
    const message = $('#composeForm textarea[name="message"]').val();

    $.ajax({
        url: '{{ route("company.email.draft.save") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            recipient: recipient,
            subject: subject,
            message: message
        },
        success: function(response) {
            if (response.success) {
                // Close compose modal and remove backdrop
                $('#composeEmailModal').modal('hide');
                $('.modal-backdrop').remove();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Draft Saved!',
                    text: 'Your email has been saved as draft.',
                    timer: 1500
                });
                
                // Clear form
                $('#composeForm')[0].reset();
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not save draft. Please try again.'
            });
        }
    });
}
</script>

<!-- Sent Emails Modal -->
<div class="modal fade" id="sentEmailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sent Emails</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-centered table-striped">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Recipients</th>
                                <th>Date Sent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sent emails will be populated here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attachments Modal -->
<div class="modal fade" id="attachmentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Attachments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="file" class="form-control" multiple>
                <div class="mt-3">
                    <h6>Attached Files:</h6>
                    <ul id="attachmentList" class="list-group">
                        <!-- Dynamically populated list of attachments -->
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="uploadAttachments()">Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- CC/BCC Modal -->
<div class="modal fade" id="ccBccModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Cc/Bcc Recipients</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="ccRecipients" class="form-label">Cc</label>
                    <input type="text" class="form-control" id="ccRecipients" placeholder="Cc Recipients">
                </div>
                <div class="mb-3">
                    <label for="bccRecipients" class="form-label">Bcc</label>
                    <input type="text" class="form-control" id="bccRecipients" placeholder="Bcc Recipients">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addCcBcc()">Add</button>
            </div>
        </div>
    </div>
</div>

<!-- Attachments Modal -->
<div class="modal fade" id="attachmentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Attachments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="file" class="form-control" multiple>
                <div class="mt-3">
                    <h6>Attached Files:</h6>
                    <ul id="attachmentList" class="list-group">
                        <!-- Dynamically populated list of attachments -->
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="uploadAttachments()">Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- Sent Emails Modal -->
<div class="modal fade" id="sentEmailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sent Emails</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-centered table-striped">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Recipients</th>
                                <th>Date Sent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamically populated list of sent emails -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function saveDraft() {
    // Get form data
    const recipient = $('#composeForm input[name="recipient"]').val();
    const subject = $('#composeForm input[name="subject"]').val();
    const message = $('#composeForm textarea[name="message"]').val();

    $.ajax({
        url: '{{ route("company.email.draft.save") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            recipient: recipient,
            subject: subject,
            message: message
        },
        success: function(response) {
            if (response.success) {
                // Close compose modal and remove backdrop
                $('#composeEmailModal').modal('hide');
                $('.modal-backdrop').remove();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Draft Saved!',
                    text: 'Your email has been saved as draft.',
                    timer: 1500
                });
                
                // Clear form
                $('#composeForm')[0].reset();
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not save draft. Please try again.'
            });
        }
    });
}
</script>

<script>
    $(document).ready(function() {
        // Load sent emails by default
        loadEmailList('{{ route("company.email.sent") }}');

        // Handle sent emails, drafts and trash tab clicks
        $('.nav-link').on('click', function(e) {
            e.preventDefault();
            const $link = $(this);
            
            // Remove active class from all nav items and add to clicked one's parent
            $('.nav-item').removeClass('active');
            $link.closest('.nav-item').addClass('active');
            
            // Determine which list to load based on the icon
            if ($link.find('i').hasClass('fa-paper-plane')) {
                loadEmailList('{{ route("company.email.sent") }}');
            } else if ($link.find('i').hasClass('fa-file')) {
                loadEmailList('{{ route("company.email.drafts") }}');
            } else if ($link.find('i').hasClass('fa-trash')) {
                loadEmailList('{{ route("company.email.deleted") }}');
            }
        });

        // Save draft functionality
        $('#saveDraftBtn').on('click', function() {
            var formData = new FormData($('#composeForm')[0]);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ route("company.email.draft.save") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Close the compose modal and remove backdrop
                                const modal = bootstrap.Modal.getInstance(document.getElementById('composeEmailModal'));
                                modal.hide();
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
                                
                                // Clear the form
                                $('#composeForm')[0].reset();
                                // Refresh the drafts list
                                loadEmailList('{{ route("company.email.drafts") }}');
                            }
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.error || 'Could not save draft!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });

    function loadEmailList(url) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                $('.email-list').html(response.html);
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load emails'
                });
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        // Load sent emails by default
        loadEmailList('{{ route("company.email.sent") }}');

        // Auto-save draft every 30 seconds if there's content
        let autoSaveTimer;
        function startAutoSave() {
            if (autoSaveTimer) clearInterval(autoSaveTimer);
            
            autoSaveTimer = setInterval(function() {
                const recipient = $('#composeForm input[name="recipient"]').val();
                const subject = $('#composeForm input[name="subject"]').val();
                const message = $('#composeForm textarea[name="message"]').val();
                
                if (recipient || subject || message) {
                    saveDraft();
                }
            }, 30000); // 30 seconds
        }

        // Start auto-save when the compose modal is shown
        $('#composeEmailModal').on('shown.bs.modal', function() {
            startAutoSave();
        });

        // Stop auto-save when the compose modal is hidden
        $('#composeEmailModal').on('hidden.bs.modal', function() {
            if (autoSaveTimer) clearInterval(autoSaveTimer);
        });

        // Save draft button click handler
        $('#saveDraftBtn').on('click', function() {
            saveDraft();
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Load sent emails by default
        loadEmailList('{{ route("company.email.sent") }}');

        // Handle Save Draft button click
        $('.btn-info[data-bs-target="#draftEmailsModal"]').on('click', function(e) {
            e.preventDefault();
            saveDraft();
        });

        // Function to save draft
        function saveDraft() {
            let formData = new FormData($('#composeForm')[0]);
            
            $.ajax({
                url: '{{ route("company.email.draft.save") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        // Clear the form
                        $('#composeForm')[0].reset();
                        // Close the compose modal
                        $('#composeEmailModal').modal('hide');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred while saving the draft.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        // Function to load draft emails
        function loadDraftEmails() {
            $.ajax({
                url: '{{ route("company.email.drafts") }}',
                type: 'GET',
                success: function(response) {
                    let tbody = $('#draftEmailsModal tbody');
                    tbody.empty();
                    
                    response.drafts.forEach(function(draft) {
                        let row = `
                            <tr>
                                <td>${draft.subject}</td>
                                <td>${draft.recipient}</td>
                                <td>${new Date(draft.updated_at).toLocaleString()}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-draft" data-draft-id="${draft.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-draft" data-draft-id="${draft.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                }
            });
        }

        // Load draft emails when draft modal is opened
        $('#draftEmailsModal').on('show.bs.modal', function() {
            loadDraftEmails();
        });
    });
</script>

<style>
  
    body {
        font-family: 'Arial', sans-serif;
        font-size: 14px;
    }
    .modal-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }
    .btn {
        font-size: 16px;
        font-weight: 600;
    }
    .email-nav .nav-link {
        font-size: 14px;
        color: #555;
    }
    .email-app {
        display: flex;
        height: calc(100vh - 150px);
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .email-sidebar {
        width: 250px;
        border-right: 1px solid #e9ecef;
        background: #f8f9fa;
        flex-shrink: 0;
    }

    .email-content {
        flex-grow: 1;
        overflow: auto;
    }

    .email-nav {
        padding: 1rem;
    }

    .email-nav .nav-item {
        padding: 0.5rem 0;
    }

    .email-nav .nav-link {
        color: #495057;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        text-decoration: none;
        width: 100%;
    }

    .email-nav .nav-item.active .nav-link {
        background: #e9ecef;
        color: #0d6efd;
    }

    .email-nav-divider {
        height: 1px;
        background: #dee2e6;
        margin: 1rem 0;
    }

    .nav-label {
        font-size: 0.875rem;
        color: #6c757d;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    .email-list {
        background: #fff;
    }

    .email-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
    }

    .email-item:hover {
        background: #f8f9fa;
    }

    .email-item.unread {
        background: #f0f4ff;
    }

    .email-star {
        padding: 0 1rem;
        color: #ffc107;
    }

    .email-sender {
        width: 200px;
        font-weight: 500;
    }

    .email-subject {
        flex-grow: 1;
    }

    .email-attachment {
        padding: 0 1rem;
        color: #6c757d;
    }

    .email-time {
        width: 100px;
        text-align: right;
        color: #6c757d;
    }

    .email-editor-toolbar {
        border-bottom: none !important;
    }

    @media (max-width: 768px) {
        .email-sidebar {
            width: 100%;
            position: fixed;
            left: -100%;
            top: 0;
            bottom: 0;
            z-index: 1000;
            transition: 0.3s;
        }

        .email-sidebar.show {
            left: 0;
        }

        .email-content {
            width: 100%;
        }

        .email-sender {
            width: 150px;
        }
    }

    /* Additional Modal Styles */
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-title {
        font-weight: bold;
        color: #333;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .table th {
        background-color: #f1f1f1;
    }

    .table tbody tr:hover {
        background-color: #f9f9f9;
    }

    .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-group-item i {
        margin-right: 10px;
    }
    
    /* Enhanced font styling for compose email modal and other relevant sections */
    .modal-body {
        font-family: 'Arial', sans-serif;
        font-size: 14px;
    }
    .form-control {
        font-size: 16px;
    }
    .email-editor-toolbar {
        font-size: 16px;
    }
    .btn-sm {
        font-size: 14px;
    }
    /* Float label style */
    .crm-email .form-floating {
        position: relative;
    }
    .crm-email .form-floating input, .crm-email .form-floating select, .crm-email .form-floating textarea {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }
    .crm-email .form-floating label {
        position: absolute;
        top: 0;
        left: 0;
        padding: 0.625rem 0.75rem;
        margin-bottom: 0;
        cursor: text;
        border-radius: 0.25rem 0.25rem 0 0;
        transition: all 0.3s ease-in-out;
        z-index: 3;
        line-height: 1.5;
    }
    .crm-email .form-floating input::-webkit-input-placeholder, .crm-email .form-floating select::-webkit-input-placeholder, .crm-email .form-floating textarea::-webkit-input-placeholder {
        visibility: hidden;
    }
    .crm-email .form-floating input::-moz-placeholder, .crm-email .form-floating select::-moz-placeholder, .crm-email .form-floating textarea::-moz-placeholder {
        visibility: hidden;
    }
    .crm-email .form-floating input:-ms-input-placeholder, .crm-email .form-floating select:-ms-input-placeholder, .crm-email .form-floating textarea:-ms-input-placeholder {
        visibility: hidden;
    }
    .crm-email .form-floating input::-ms-input-placeholder, .crm-email .form-floating select::-ms-input-placeholder, .crm-email .form-floating textarea::-ms-input-placeholder {
        visibility: hidden;
    }
    .crm-email .form-floating input:placeholder-shown, .crm-email .form-floating select:placeholder-shown, .crm-email .form-floating textarea:placeholder-shown {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }
    .crm-email .form-floating input:placeholder-shown ~ label, .crm-email .form-floating select:placeholder-shown ~ label, .crm-email .form-floating textarea:placeholder-shown ~ label {
        opacity: 0;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }
    .crm-email .form-floating input:focus, .crm-email .form-floating select:focus, .crm-email .form-floating textarea:focus {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }
    .crm-email .form-floating input:focus ~ label, .crm-email .form-floating select:focus ~ label, .crm-email .form-floating textarea:focus ~ label {
        opacity: 1;
        transform: scale(1) translateY(-0.5rem) translateX(0.15rem);
    }
</style>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        // Select all functionality
        $('#selectAll').on('change', function() {
            $('.email-item .form-check-input').prop('checked', $(this).prop('checked'));
        });

        // Star functionality
        $('.email-star').on('click', function(e) {
            e.stopPropagation();
            const star = $(this).find('i');
            star.toggleClass('far fas');
        });

        // Email item click
        $('.email-item').on('click', function() {
            // Handle email view
        });
    });

    // Add JavaScript functions to handle editor actions
    function boldText() {
        // Implement bold text functionality
    }

    function italicText() {
        // Implement italic text functionality
    }

    function underlineText() {
        // Implement underline text functionality
    }
</script>
@endpush

<script>
    $(document).ready(function() {
        // Handle email form submission
        $('#composeForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Close the compose modal and remove backdrop
                        const modal = bootstrap.Modal.getInstance(document.getElementById('composeEmailModal'));
                        modal.hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Email sent successfully!'
                        });
                        
                        // Clear the form
                        $('#composeForm')[0].reset();
                        // Refresh the sent emails list
                        loadEmailList('{{ route("company.email.sent") }}');
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Could not send email. Please try again.'
                    });
                }
            });
        });
    });
</script>