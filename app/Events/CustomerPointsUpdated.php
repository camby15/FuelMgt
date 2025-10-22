<?php
namespace App\Events;

use App\Models\CustomerPoint;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class CustomerPointsUpdated
{
    use Dispatchable, SerializesModels;

    public $customerPoints;

    

    public function __construct(CustomerPoint $customerPoints)
    {
        $this->customerPoints = $customerPoints;
    }
}