<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HR\EmployeeController;

class TestStaffId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:staff-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the getAvailableStaffIds method';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Set session company ID
        Session::put('selected_company_id', 1);

        // Create controller instance
        $controller = new EmployeeController();

        // Call the method
        $response = $controller->getAvailableStaffIds();

        // Output the result
        $this->info('Response:');
        $this->line($response->getContent());

        return Command::SUCCESS;
    }
}
