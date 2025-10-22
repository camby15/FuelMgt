<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ActivityMeetingEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $meetingTitle;
    public string $timeUntilMeeting;
    public string $meetingDateTime;
    public string $attendees;
    public string $companyName;
    public string $supportEmail;
    public ?string $timezone;
    public ?string $meetingAgenda;
    public ?string $meetingLocation;
    public ?string $joinLink;
    public ?string $rescheduleLink;
    public ?string $calendarLink;

    public function __construct(
        string $meetingTitle,
        string $timeUntilMeeting,
        string $meetingDateTime,
        string $attendees,
        string $companyName,
        ?string $supportEmail = null,
        ?string $timezone = null,
        ?string $meetingAgenda = null,
        ?string $meetingLocation = null,
        ?string $joinLink = null,
        ?string $rescheduleLink = null,
        ?string $calendarLink = null
    ) {
        $this->meetingTitle = $meetingTitle;
        $this->timeUntilMeeting = $timeUntilMeeting;
        $this->meetingDateTime = $meetingDateTime;
        $this->attendees = $attendees;
        $this->companyName = $companyName;
        $this->supportEmail = $supportEmail ?? config('mail.from.address', 'no-reply@example.com');
        $this->timezone = $timezone ?? config('app.timezone', 'UTC');
        $this->meetingAgenda = $meetingAgenda ?? 'No agenda provided';
        $this->meetingLocation = $meetingLocation ?? 'Location not specified';
        $this->joinLink = $joinLink;
        $this->rescheduleLink = $rescheduleLink;
        $this->calendarLink = $calendarLink;
    }

    public function build()
    {
        try {
            $viewName = 'emails.meeting-notification';
            
            if (!view()->exists($viewName)) {
                throw new \Exception("Email template {$viewName} not found");
            }

            $fromAddress = config('mail.from.address', $this->supportEmail);
            $fromName = config('mail.from.name', $this->companyName);

            return $this->subject("📅 Meeting Reminder: {$this->meetingTitle}")
                ->from($this->supportEmail, $this->companyName)
                ->replyTo($fromAddress, $fromName)
                ->priority(1)
                ->view($viewName)
                ->with([
                    'meetingTitle' => $this->meetingTitle,
                    'timeUntilMeeting' => $this->timeUntilMeeting,
                    'meetingDateTime' => $this->meetingDateTime,
                    'attendees' => $this->attendees,
                    'timezone' => $this->timezone,
                    'meetingAgenda' => $this->meetingAgenda,
                    'meetingLocation' => $this->meetingLocation,
                    'joinLink' => $this->joinLink,
                    'rescheduleLink' => $this->rescheduleLink,
                    'calendarLink' => $this->calendarLink
                ]);
                
        } catch (\Exception $e) {
            Log::error('Meeting email build failed', [
                'error' => $e->getMessage(),
                'context' => [
                    'meeting_title' => $this->meetingTitle,
                    'attendees' => $this->attendees,
                    'scheduled_time' => $this->meetingDateTime
                ]
            ]);
            throw $e;
        }
    }
}