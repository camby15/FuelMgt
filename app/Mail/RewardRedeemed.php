<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RewardRedeemed extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $reward;
    public $redemption;

    public function __construct($customer, $reward, $redemption)
    {
        $this->customer = $customer;
        $this->reward = $reward;
        $this->redemption = $redemption;
    }

    public function build()
    {
        return $this->subject('Your Reward Redemption Confirmation')
            ->view('emails.reward_redeemed')
            ->text('emails.reward_redeemed');
    }
}