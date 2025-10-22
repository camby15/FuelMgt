<!-- Sent Emails Table -->
@forelse($sentEmails as $email)
    <tr class="email-row" data-message="{{ $email->message }}">
        <td>
            @if($email->status === 'sent')
                <span class="badge bg-success">Sent</span>
            @else
                <span class="badge bg-danger">Failed</span>
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
@if($sentEmails->hasPages())
    <tr>
        <td colspan="4">
            {{ $sentEmails->links() }}
        </td>
    </tr>
@endif

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
</style>

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
