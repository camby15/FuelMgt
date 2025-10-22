<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TierUpgraded extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $oldTier;
    public $newTier;
    public $program;

    public function __construct($customer, $oldTier, $newTier, $program)
    {
        $this->customer = $customer;
        $this->oldTier = $oldTier;
        $this->newTier = $newTier;
        $this->program = $program;
    }

    public function build()
    {
        return $this->subject('Congratulations! You\'ve Been Upgraded')
            ->view('emails.tier_upgraded')
            ->text('emails.tier_upgraded_text');
    }
}