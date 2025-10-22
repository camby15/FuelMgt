<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Contract;
use Illuminate\Support\Facades\Log;

class AutoRenewContracts extends Command
{
    protected $signature = 'contracts:auto-renew'; // <-- Corrected signature

    protected $description = 'Automatically renew contracts whose end date has passed and are marked for auto-renewal.';

    public function handle()
    {
        $today = now()->toDateString();

        $contractsToRenew = Contract::where('end_date', '<=', $today)
            ->where('auto_renewal', 1)
            ->get();

        foreach ($contractsToRenew as $contract) {
            $startDate = Carbon::parse($contract->start_date);
            $endDate = Carbon::parse($contract->end_date);
            $duration = $startDate->diff($endDate); // Same interval

            $contract->start_date = now();
            $contract->end_date = now()->add($duration);
            $contract->status = 'renewed';
            $contract->save();

            Log::info('Contract ID ' . $contract->id . ' auto-renewed.');
        }

        $this->info('Auto-renewed ' . $contractsToRenew->count() . ' contracts.');

        return Command::SUCCESS;
    }
}
