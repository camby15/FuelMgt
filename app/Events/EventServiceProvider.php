<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\CustomerPointsUpdated;
use App\Listeners\CheckForTierUpgrade;
use App\Listeners\CheckForPointsExpiration;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CustomerPointsUpdated::class => [
            CheckForTierUpgrade::class,
            CheckForPointsExpiration::class,
        ],
    ];
}
