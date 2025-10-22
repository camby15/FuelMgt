<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ComposeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $recipient;
    public $subject;
    public $message;
    public $companyName;

    public function __construct($recipient, $subject, $message, $companyName)
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->message = is_string($message) ? $message : json_encode($message);
        $this->companyName = $companyName;
    }

    public function build()
    {
        Log::info('Message type:', ['type' => gettype($this->message)]);
        Log::info('Message content:', ['content' => $this->message]);

        return $this->subject($this->subject)
            ->from(config('mail.from.address'), $this->companyName)
            ->replyTo(config('mail.from.address'), $this->companyName)
            ->priority(1)  // Set high priority
            ->view('emails.compose')
            ->with([
                'emailMessage' => $this->message,
                'subject' => $this->subject,
                'companyName' => $this->companyName
            ]);
    }
}
