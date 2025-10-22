<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PointsEarned extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $points;
    public $program;
    public $transaction;

    public function __construct($customer, $points, $program, $transaction = null)
    {
        $this->customer = $customer;
        $this->points = $points;
        $this->program = $program;
        $this->transaction = $transaction;
    }

    public function build()
    {
        return $this->subject('You\'ve Earned Loyalty Points!')
            ->view('emails.points_earned')
            ->text('emails.points_earned_text');
    }
}