<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SupplierReturn;
use App\Models\Wh_Supplier;

class ReturnNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $return;
    public $supplier;
    public $companyName;
    public $itemsList;
    public $totalValue;

    /**
     * Create a new message instance.
     */
    public function __construct(SupplierReturn $return, $supplier, $companyName, $itemsList, $totalValue)
    {
        $this->return = $return;
        $this->supplier = $supplier;
        $this->companyName = $companyName;
        $this->itemsList = $itemsList;
        $this->totalValue = $totalValue;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Supplier Return Notification - ' . $this->return->return_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->generateEmailContent(),
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Generate the email HTML content
     */
    private function generateEmailContent()
    {
        $supplierName = $this->supplier ? $this->supplier->company_name : 'Supplier';
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-radius: 5px; }
                .content { padding: 20px; }
                .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th { background-color: #f8f9fa; padding: 10px; border: 1px solid #ddd; text-align: left; }
                td { padding: 10px; border: 1px solid #ddd; }
                .total { font-weight: bold; background-color: #f8f9fa; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Supplier Return Notification</h2>
                    <p>Return Number: {$this->return->return_number}</p>
                </div>
                
                <div class='content'>
                    <p>Dear {$supplierName},</p>
                    
                    <p>This email is to notify you that we have processed a return for the following items:</p>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity Returned</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$this->itemsList}
                        </tbody>
                        <tfoot>
                            <tr class='total'>
                                <td colspan='3' style='text-align: right; padding: 10px; border: 1px solid #ddd;'><strong>Total Value:</strong></td>
                                <td style='text-align: right; padding: 10px; border: 1px solid #ddd;'><strong>GH₵ " . number_format($this->totalValue, 2) . "</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <p><strong>Return Details:</strong></p>
                    <ul>
                        <li><strong>Return Date:</strong> {$this->return->return_date}</li>
                        <li><strong>Reason:</strong> " . ucfirst($this->return->return_reason) . "</li>
                        <li><strong>Status:</strong> Processed</li>
                    </ul>
                    
                    " . ($this->return->return_description ? "<p><strong>Additional Notes:</strong> {$this->return->return_description}</p>" : "") . "
                    
                    <p>Please review this return and contact us if you have any questions or concerns.</p>
                    
                    <p>Best regards,<br>
                    <strong>{$this->companyName}</strong></p>
                </div>
                
                <div class='footer'>
                    <p>This is an automated notification. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}
