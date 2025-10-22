<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferralInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $program;
    public $referral;

    public function __construct($customer, $program, $referral)
    {
        $this->customer = $customer;
        $this->program = $program;
        $this->referral = $referral;
    }

    public function build()
    {
        return $this->subject($this->customer->first_name . ' Invited You to Join ' . $this->program->name)
            ->view('emails.referral_invitation')
            ->text('emails.referral_invitation_text');
    }
}