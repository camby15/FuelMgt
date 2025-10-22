@component('mail::message')
# Queue Job Failed

A queue job has failed with the following details:

**Job ID:** {{ $jobId }}

**Failed At:** {{ $failedAt }}

**Exception:**
```
{{ $exception }}
```

@component('mail::button', ['url' => url('/queue-monitor')])
View Queue Monitor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
