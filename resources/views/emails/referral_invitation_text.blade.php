Hi there,

{{ $customer->first_name }} has invited you to join {{ $program->name }} loyalty program!

Sign up now and you'll both earn {{ $referral->points_awarded }} bonus points!

Accept your invitation here:
{{ route('referral.process', $referral->token) }}

This link will expire in 7 days.

Thanks,
{{ $program->company->name ?? 'Our Team' }}