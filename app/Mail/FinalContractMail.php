<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class FinalContractMail extends Mailable implements ShouldQueue
{
   use Queueable, SerializesModels;

    public string $customerName;
    public string $contractName;
    public string $pdfPath;

    public function __construct(string $customerName, string $contractName, string $pdfPath)
    {
        $this->customerName = $customerName;
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
            view: 'email.final-contract',
            with: [
                'customer_name' => $this->customerName,
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
