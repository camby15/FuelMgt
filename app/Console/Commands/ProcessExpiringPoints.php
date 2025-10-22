<?php

namespace App\Console\Commands;

use App\Models\CustomerPoint;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;



class ProcessExpiringPoints extends Command
{
    protected $signature = 'loyalty:process-expiring-points';
    protected $description = 'Process points that are about to expire';

    public function handle()
    {
        // Find points expiring in the next 7 days
        $expiringPoints = CustomerPoint::where('expires_at', '<=', Carbon::now()->addDays(7))
            ->where('expires_at', '>', Carbon::now())
            ->where('points_balance', '>', 0)
            ->get();
            
        foreach ($expiringPoints as $points) {
            // Send expiration warning
            $customer = $points->customer;
            $program = $points->program;
            
            Mail::to($customer->email)->send(new PointsExpiring(
                $customer,
                $points->points_balance,
                $points->expires_at,
                $program
            ));
            
            $this->info("Sent expiration warning to {$customer->email}");
        }
        
        // Actually expire points that are past their expiration date
        $expiredPoints = CustomerPoint::where('expires_at', '<=', Carbon::now())
            ->where('points_balance', '>', 0)
            ->get();
            
        foreach ($expiredPoints as $points) {
            $this->info("Expiring {$points->points_balance} points for customer {$points->customer_id}");
            $points->points_balance = 0;
            $points->save();
        }
        
        $this->info('Finished processing expiring points');
        return 0;
    }
}