<!-- Gmail-like Draft Emails List -->
<style>
    .emails-container {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
        margin: 16px;
        max-width: 100%;
        overflow-x: auto;
    }
    .email-header, .email-item {
        display: grid;
        grid-template-columns: 40px 220px minmax(300px, 1fr) 40px 100px;
        gap: 4px;
        align-items: center;
        padding: 8px;
        font-family: 'Poppins';
        min-height: 32px;
        border-bottom: 1px solid #e0e0e0;
    }
    .email-item {
        color: #202124;
        cursor: pointer;
        transition: all 0.15s ease;
        height: 40px;
        position: relative;
    }
    .email-item:hover {
        background-color: #f5f5f5;
    }
    .email-item:nth-child(odd) {
        background-color: #fafafa;
    }
    .form-check-input {
        border-radius: 50%;
        border: 2px solid #757575;
        cursor: pointer;
        margin: 0;
        width: 16px;
        height: 16px;
    }
    .form-check-input:checked {
        background-color: #757575;
        border-color: #757575;
    }
    .recipient-cell {
        font-weight: 500;
        font-size: 0.875rem;
        color: #424242;
        padding: 0 8px;
    }
    .subject-cell {
        font-size: 0.875rem;
        color: #5f6368;
        padding: 0 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .attachment-cell {
        color: #5f6368;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .time-cell {
        font-size: 0.75rem;
        color: #5f6368;
        text-align: right;
        white-space: nowrap;
        padding-right: 16px;
        margin-left: auto;
    }
    .actions-cell {
        display: none;
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: inherit;
        padding: 0 8px;
    }
    .email-item:hover .actions-cell {
        display: flex;
        gap: 8px;
    }
    .action-btn {
        background: none !important;
        border: none !important;
        padding: 4px 8px;
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }
    .action-btn:hover {
        transform: translateY(-1px);
        background: none !important;
    }
    .action-btn.delete-btn,
    .action-btn.delete-btn i {
        color: #ea4335 !important;
    }
    .action-btn.view-btn,
    .action-btn.view-btn i {
        color: #34a853 !important;
    }
    .view-btn {
        color: #2196F3;
    }
    .view-btn:hover {
        background-color: rgba(33, 150, 243, 0.1);
    }
    .delete-btn {
        color: #fa5c7c;
    }
    .delete-btn:hover {
        background-color: rgba(250, 92, 124, 0.1);
    }
    .no-emails {
        text-align: center;
        padding: 48px;
        color: #757575;
        font-size: 0.875rem;
        background: #fafafa;
        border-radius: 16px;
    }

    /* View Email Modal Styles */
    .email-modal-content {
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        border-radius: 0.75rem;
        border: none;
    }
    .email-modal-header {
        border-bottom: 2px solid #727cf5;
        padding: 1.5rem;
    }
    .email-modal-header h5 {
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }
    .email-modal-body {
        padding: 1.5rem;
    }
    .email-info-group {
        margin-bottom: 1.5rem;
    }
    .email-info-group:last-child {
        margin-bottom: 0;
    }
    .email-label {
        color: #2c3e50;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .email-value {
        color: #727cf5;
        font-size: 0.9rem;
    }
    .email-message {
        background: #f8f9fa;
        border: 1px solid #eef2f7;
        border-radius: 0.5rem;
        padding: 1.5rem;
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.6;
        white-space: pre-wrap;
    }
    .email-modal-footer {
        background: #f8f9fa;
        border-top: 1px solid #eef2f7;
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        padding: 1rem 1.5rem;
    }
    .btn-modal {
        padding: 0.5rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }
    .btn-modal:hover {
        transform: translateY(-1px);
    }
    .btn-modal-cancel {
        color: #6c757d;
        background: #fff;
        border: 1px solid #eef2f7;
    }
    .btn-modal-cancel:hover {
        background: #f8f9fa;
        color: #2c3e50;
    }
    .btn-modal-delete {
        background: #fa5c7c;
        color: #fff;
        border: none;
    }
    .btn-modal-delete:hover {
        background: #e54e6b;
        box-shadow: 0 2px 6px rgba(250, 92, 124, 0.25);
    }
    .btn-modal i {
        font-size: 0.875rem;
        margin-right: 0.5rem;
    }
</style>

<div class="emails-container">
    @if(count($draftEmails) > 0)
        @foreach($draftEmails as $draft)
            <div class="email-item">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox">
                </div>
                <div class="text-cell recipient-cell">{{ $draft->recipient ?? 'No recipient' }}</div>
                <div class="text-cell subject-cell">{{ $draft->subject ?? 'No subject' }}</div>
                <div class="attachment-cell">
                    @if($draft->has_attachment)
                        <i class="fas fa-paperclip"></i>
                    @endif
                </div>
                <div class="time-cell">{{ $draft->updated_at->format('M d') }}</div>
                <div class="actions-cell">
                    <button class="btn btn-sm text-primary" onclick="editDraft({{ $draft->id }})" title="Edit Draft">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm text-danger" onclick="deleteDraft({{ $draft->id }})" title="Delete Draft">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        @endforeach
    @else
        <div class="no-emails">
            <p>No draft emails found</p>
        </div>
    @endif
</div>

<script>
function deleteDraft(draftId) {
    Swal.fire({
        title: 'Delete Draft?',
        text: "This draft will be permanently deleted",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fa5c7c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("company.email.draft.delete", ["id" => ":id"]) }}'.replace(':id', draftId),
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Draft has been deleted.',
                            timer: 1500
                        });
                        
                        // Refresh the drafts list
                        loadEmailList('{{ route("company.email.drafts") }}');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Could not delete the draft.'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Could not delete the draft.'
                    });
                }
            });
        }
    });
}

function editDraft(draftId) {
    $.ajax({
        url: '{{ route("company.email.draft.get", ["id" => ":id"]) }}'.replace(':id', draftId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Close drafts modal
                $('#draftEmailsModal').modal('hide');
                
                // Fill compose form with draft data
                $('#composeForm input[name="recipient"]').val(response.draft.recipient);
                $('#composeForm input[name="subject"]').val(response.draft.subject);
                $('#composeForm textarea[name="message"]').val(response.draft.message);
                
                // Show compose modal
                $('#composeEmailModal').modal('show');
            } else {
                Swal.fire(
                    'Error!',
                    'Could not load the draft.',
                    'error'
                );
            }
        },
        error: function(xhr) {
            Swal.fire(
                'Error!',
                'Could not load the draft.',
                'error'
            );
        }
    });
}
</script>
