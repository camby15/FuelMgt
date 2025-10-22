<?php
namespace App\Listeners;

use App\Events\CustomerPointsUpdated;
use Carbon\Carbon;
use App\Mail\PointsExpiring;
use Illuminate\Support\Facades\Mail;

class CheckForPointsExpiration
{
    public function handle(CustomerPointsUpdated $event)
    {
        $customerPoints = $event->customerPoints;
        
        // Check if points are expiring soon (within 30 days)
        if ($customerPoints->expires_at && $customerPoints->expires_at->diffInDays(Carbon::now()) <= 30) {
            $program = $customerPoints->program;
            $customer = $customerPoints->customer;
            
            Mail::to($customer->email)->send(new PointsExpiring(
                $customer,
                $customerPoints->points_balance,
                $customerPoints->expires_at,
                $program
            ));
        }
    }
}
