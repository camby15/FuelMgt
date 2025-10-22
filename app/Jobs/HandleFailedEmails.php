<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\FailedJobNotification;

class HandleFailedEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $exception;
    protected $failedJob;

    public function __construct($exception, $failedJob)
    {
        $this->exception = $exception;
        $this->failedJob = $failedJob;
    }

    public function handle()
    {
        // Log the failure
        Log::error('Email Job Failed', [
            'exception' => $this->exception,
            'job' => $this->failedJob
        ]);

        // You can add admin notification here if needed
        // Mail::to('admin@example.com')->send(new FailedJobNotification($this->failedJob));
    }
}
