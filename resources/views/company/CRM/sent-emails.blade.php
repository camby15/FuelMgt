@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Email Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('company.email.compose') }}" class="btn btn-primary btn-block mb-3">
                        <i class="fas fa-pen"></i> Compose
                    </a>

                    <div class="list-group">
                        <a href="{{ route('company.email.compose') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-inbox"></i> Inbox
                        </a>
                        <a href="{{ route('company.email.sent') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-paper-plane"></i> Sent Mail
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-star"></i> Starred
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-trash"></i> Trash
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sent Emails</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>To</th>
                                    <th>Subject</th>
                                    <th>Sent At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sentEmails as $email)
                                    <tr class="email-row" data-message="{{ $email->message }}">
                                        <td>
                                            @if($email->status === 'sent')
                                                <span class="badge badge-success">Sent</span>
                                            @else
                                                <span class="badge badge-danger">Failed</span>
                                            @endif
                                        </td>
                                        <td>{{ $email->recipient }}</td>
                                        <td>{{ $email->subject }}</td>
                                        <td>{{ $email->created_at->format('M d, Y h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No sent emails found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $sentEmails->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .email-row {
        cursor: pointer;
    }
    .email-row:hover {
        background-color: #f8f9fa;
    }
    .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.6em;
    }
    .list-group-item i {
        margin-right: 8px;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('.email-row').click(function() {
        const message = $(this).data('message');
        Swal.fire({
            title: $(this).find('td:eq(2)').text(), // Subject
            html: message.replace(/\n/g, '<br>'),
            width: '600px',
            showCloseButton: true,
            showConfirmButton: false
        });
    });
});
</script>
@endpush
@endsection
