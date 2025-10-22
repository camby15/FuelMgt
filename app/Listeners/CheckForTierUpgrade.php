<?php
namespace App\Listeners;

use App\Events\CustomerPointsUpdated;
use App\Models\CustomerTier;
use App\Mail\TierUpgraded;
use Illuminate\Support\Facades\Mail;

class CheckForTierUpgrade
{
    public function handle(CustomerPointsUpdated $event)
    {
        $customerPoints = $event->customerPoints;
        
        // Get all tiers for the program, ordered by points required
        $tiers = CustomerTier::where('loyalty_program_id', $customerPoints->loyalty_program_id)
            ->orderBy('points_required')
            ->get();
            
        // Find the highest tier the customer qualifies for
        $newTier = null;
        foreach ($tiers as $tier) {
            if ($customerPoints->points_balance >= $tier->points_required) {
                $newTier = $tier;
            } else {
                break;
            }
        }
        
        // If customer has a new highest tier, send notification
        if ($newTier && (!$customerPoints->current_tier || $newTier->id !== $customerPoints->current_tier->id)) {
            $oldTier = $customerPoints->current_tier;
            $customerPoints->current_tier_id = $newTier->id;
            $customerPoints->save();
            
            // Send upgrade notification
            $program = $customerPoints->program;
            $customer = $customerPoints->customer;
            
            Mail::to($customer->email)->send(new TierUpgraded(
                $customer,
                $oldTier,
                $newTier,
                $program
            ));
        }
    }
}
