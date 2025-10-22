<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployeeMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $bodyText;
    public $attachmentPath;
    public $employeeName;
    public $companyName;
    public $attachmentUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($subjectText, $bodyText, $attachmentPath = null, $employeeName = null, $companyName = null)
    {
        $this->subjectText = $subjectText;
        $this->bodyText = $bodyText;
        $this->attachmentPath = $attachmentPath;
        $this->employeeName = $employeeName;
        $this->companyName = $companyName ?? config('app.name');

        // Generate public asset URL for use in the email view
        $this->attachmentUrl = $attachmentPath ? asset('storage/' . $attachmentPath) : null;
    }

    /**
     * Build the message.
     */
    public function build()
{
    $email = $this->subject($this->subjectText)
                  ->view('emails.employee_message')
                  ->with([
                      'subject' => $this->subjectText,
                      'body' => $this->bodyText,
                      'companyName' => $this->companyName,
                      'attachmentUrl' => $this->attachmentPath ? asset('storage/' . $this->attachmentPath) : null,
                  ]);

    if ($this->attachmentPath) {
        $fullPath = storage_path('app/public/' . $this->attachmentPath);

        if (file_exists($fullPath)) {
            \Log::info('📎 Attaching file to email: ' . $fullPath);
            $email->attach($fullPath, [
                'as' => basename($fullPath),
                'mime' => mime_content_type($fullPath),
            ]);
        } else {
            \Log::error('❌ Attachment file not found at: ' . $fullPath);
        }
    }

    return $email;
}

}
