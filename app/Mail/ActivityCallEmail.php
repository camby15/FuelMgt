<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ActivityCallEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $callTitle;
    public string $timeUntilCall;
    public string $callDateTime;
    public string $participants;
    public string $companyName;
    public string $supportEmail;
    public ?string $timezone;
    public ?string $callPurpose;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $callTitle,
        string $timeUntilCall,
        string $callDateTime,
        string $participants,
        string $companyName,
        ?string $supportEmail = null,
        ?string $timezone = null,
        ?string $callPurpose = null
    ) {
        $this->callTitle = $callTitle;
        $this->timeUntilCall = $timeUntilCall; // Fixed property name
        $this->callDateTime = $callDateTime;
        $this->participants = $participants;
        $this->companyName = $companyName;
        $this->supportEmail = $supportEmail ?? config('mail.from.address', 'no-reply@example.com');
        $this->timezone = $timezone ?? config('app.timezone', 'UTC');
        $this->callPurpose = $callPurpose ?? 'Not specified';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        try {
            $viewName = 'emails.call-notification'; // Make sure this matches your actual view filename
            
            if (!view()->exists($viewName)) {
                throw new \Exception("Email template {$viewName} not found");
            }

            $fromAddress = config('mail.from.address', $this->supportEmail);
            $fromName = config('mail.from.name', $this->companyName);

            return $this->subject("📞 Call Reminder: {$this->callTitle}")
                ->from($this->supportEmail, $this->companyName)
                ->replyTo($fromAddress, $fromName)
                ->priority(1)
                ->view($viewName)
                ->with([
                    'callTitle' => $this->callTitle,
                    'timeUntilCall' => $this->timeUntilCall,
                    'callDateTime' => $this->callDateTime,
                    'participants' => $this->participants,
                    'timezone' => $this->timezone,
                    'callPurpose' => $this->callPurpose
                ]);
                
        } catch (\Exception $e) {
            Log::error('Email build failed: ' . $e->getMessage(), [
                'context' => [
                    'call_title' => $this->callTitle,
                    'recipients' => $this->participants,
                    'scheduled_time' => $this->callDateTime,
                    'error_trace' => $e->getTraceAsString()
                ]
            ]);
            throw $e;
        }
    }
}