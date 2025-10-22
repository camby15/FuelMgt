<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ActivityTaskEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $activityName;
    public string $assignedTo;
    public ?string $assignedBy;
    public string $startTime;
    public ?string $endTime;
    public string $priority;
    public string $status;
    public string $description;
    public string $companyName;
    public string $supportEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $activityName,
        string $assignedTo,
        ?string $assignedBy,
        string $startTime,
        ?string $endTime,
        string $priority,
        string $status,
         $description,
        string $companyName,
        ?string $supportEmail = null 
    ) {
        $this->activityName = $activityName;
        $this->assignedTo = $assignedTo;
        $this->assignedBy = $assignedBy;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->priority = $priority;
        $this->status = $status;
        $this->description = is_string($description) ? $description : json_encode($description);
        $this->companyName = $companyName;
        $this->supportEmail = $supportEmail ?? config('mail.from.address', 'no-reply@example.com');
    }

    public function build()
    {
        try {
            Log::debug('Building ActivityTaskEmail', [
                'to' => $this->assignedTo,
                'activity' => $this->activityName
            ]);

            $fromAddress = config('mail.from.address', $this->supportEmail);
            $fromName = config('mail.from.name', $this->companyName);

            return $this->subject($this->activityName)
                ->from($fromAddress, $fromName)
                ->replyTo($fromAddress, $fromName)
                ->priority(1)
                ->view('emails.task-email')
                ->with([
                    'activityName' => $this->activityName,
                    'assignedTo' => $this->assignedTo,
                    'assignedBy' => $this->assignedBy,
                    'startTime' => $this->startTime,
                    'endTime' => $this->endTime,
                    'priority' => $this->priority,
                    'status' => $this->status,
                    'description' => $this->description,
                    'companyName' => $this->companyName,
                    'supportEmail' => $this->supportEmail
                ]);
        } catch (\Exception $e) {
            Log::error('Failed to build ActivityTaskEmail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}