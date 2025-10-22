<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MonitorQueueJobs extends Command
{
    protected $signature = 'queue:monitor';
    protected $description = 'Monitor queue jobs and failed jobs';

    public function handle()
    {
        while (true) {
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();

            $this->info('Queue Status:');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Pending Jobs', $pendingJobs],
                    ['Failed Jobs', $failedJobs],
                ]
            );

            if ($failedJobs > 0) {
                $this->error("There are {$failedJobs} failed jobs!");
            }

            sleep(60); // Check every minute
        }
    }
}
