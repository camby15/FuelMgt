<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FailedJobNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $failedJob;

    public function __construct($failedJob)
    {
        $this->failedJob = $failedJob;
    }

    public function build()
    {
        return $this->markdown('emails.failed-job')
                    ->subject('Queue Job Failed')
                    ->with([
                        'jobId' => $this->failedJob->id,
                        'exception' => $this->failedJob->exception,
                        'failedAt' => $this->failedJob->failed_at
                    ]);
    }
}
