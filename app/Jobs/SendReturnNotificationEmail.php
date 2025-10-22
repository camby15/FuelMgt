<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReturnNotificationMail;
use App\Models\SupplierReturn;
use App\Models\Wh_Supplier;

class SendReturnNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $return;
    public $supplier;
    public $email;
    public $companyName;
    public $itemsList;
    public $totalValue;

    /**
     * Create a new job instance.
     */
    public function __construct(SupplierReturn $return, $supplier, $email, $companyName, $itemsList, $totalValue)
    {
        $this->return = $return;
        $this->supplier = $supplier;
        $this->email = $email;
        $this->companyName = $companyName;
        $this->itemsList = $itemsList;
        $this->totalValue = $totalValue;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Send the email
            Mail::to($this->email)->send(new ReturnNotificationMail(
                $this->return,
                $this->supplier,
                $this->companyName,
                $this->itemsList,
                $this->totalValue
            ));

            \Log::info('Return notification email sent successfully to: ' . $this->email);
        } catch (\Exception $e) {
            \Log::error('Error sending return notification email: ' . $e->getMessage());
            throw $e; // Re-throw to mark job as failed
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        \Log::error('Return notification email job failed: ' . $exception->getMessage());
    }
}
