<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignatureFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $signatureLink;
    public string $contractName;
    public string $pdfPath;

    public function __construct(string $signatureLink, string $contractName, string $pdfPath)
    {
        $this->signatureLink = $signatureLink;
        $this->contractName = $contractName;
        $this->pdfPath = $pdfPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Signature Form for ' . $this->contractName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.signatureMail',
            with: [
                'signatureLink' => $this->signatureLink,
                'contractName' => $this->contractName,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath(storage_path('app/' . $this->pdfPath))
                ->as('contract-details.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
