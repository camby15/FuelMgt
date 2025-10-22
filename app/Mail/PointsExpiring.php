<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PointsExpiring extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $points;
    public $expiryDate;
    public $program;

    public function __construct($customer, $points, $expiryDate, $program)
    {
        $this->customer = $customer;
        $this->points = $points;
        $this->expiryDate = $expiryDate;
        $this->program = $program;
    }

    public function build()
    {
        return $this->subject('Your Points Are About to Expire')
            ->view('emails.points_expiring')
            ->text('emails.points_expiring_text');
    }
}