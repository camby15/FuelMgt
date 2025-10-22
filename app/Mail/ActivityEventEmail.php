<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ActivityEventEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $eventTitle; 
    public string $timeUntilEvent;
    public string $eventDateTime;
    public string $eventHost;
    public string $companyName;
    public string $supportEmail;
    public ?string $timezone;
    public ?string $eventDescription;
    public ?string $eventLocation;
    public ?string $joinLink;
    public ?string $calendarLink;
    public ?string $manageRSVPLink;

    public function __construct(
        string $eventTitle,
        string $timeUntilEvent,
        string $eventDateTime,
        string $eventHost,
        string $companyName,
        ?string $supportEmail = null,
        ?string $timezone = null,
        ?string $eventDescription = null,
        ?string $eventLocation = null,
        ?string $joinLink = null,
        ?string $calendarLink = null,
        ?string $manageRSVPLink = null
    ) {
        $this->eventTitle = $eventTitle;
        $this->timeUntilEvent = $timeUntilEvent;
        $this->eventDateTime = $eventDateTime;
        $this->eventHost = $eventHost;
        $this->companyName = $companyName;
        $this->supportEmail = $supportEmail ?? config('mail.from.address', 'no-reply@example.com');
        $this->timezone = $timezone ?? config('app.timezone', 'UTC');
        $this->eventDescription = $eventDescription ?? 'No description provided';
        $this->eventLocation = $eventLocation ?? 'Location not specified';
        $this->joinLink = $joinLink;
        $this->calendarLink = $calendarLink;
        $this->manageRSVPLink = $manageRSVPLink;
    }

    public function build()
    {
        try {
            $viewName = 'emails.event-notification';
            
            if (!view()->exists($viewName)) {
                throw new \Exception("Email template {$viewName} not found");
            }

            $fromAddress = config('mail.from.address', $this->supportEmail);
            $fromName = config('mail.from.name', $this->companyName);

            return $this->subject("🎉 Event Reminder: {$this->eventTitle}")
                ->from($this->supportEmail, $this->companyName)
                ->replyTo($fromAddress, $fromName)
                ->priority(1)
                ->view($viewName)
                ->with([
                    'eventTitle' => $this->eventTitle,
                    'timeUntilEvent' => $this->timeUntilEvent,
                    'eventDateTime' => $this->eventDateTime,
                    'eventHost' => $this->eventHost,
                    'timezone' => $this->timezone,
                    'eventDescription' => $this->eventDescription,
                    'eventLocation' => $this->eventLocation,
                    'joinLink' => $this->joinLink,
                    'calendarLink' => $this->calendarLink,
                    'manageRSVPLink' => $this->manageRSVPLink
                ]);
                
        } catch (\Exception $e) {
            Log::error('Event email build failed', [
                'error' => $e->getMessage(),
                'context' => [
                    'event_title' => $this->eventTitle,
                    'event_time' => $this->eventDateTime,
                    'host' => $this->eventHost
                ]
            ]);
            throw $e;
        }
    }
}