<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TeamMember;
use App\Models\Vehicle;
use App\Models\Driver;

class TestTeamParingAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:team-paring-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Team Pairing API endpoints and data availability';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Team Pairing API Data...');
        $this->newLine();

        // Test Team Members
        $this->info('1. Testing Team Members:');
        $teamMembers = TeamMember::select('id', 'full_name', 'position', 'company_id', 'status')->get();
        $this->line("   Total Team Members: {$teamMembers->count()}");
        
        if ($teamMembers->count() > 0) {
            $this->line("   Sample Team Members:");
            foreach ($teamMembers->take(3) as $member) {
                $this->line("   - ID: {$member->id}, Name: {$member->full_name}, Position: {$member->position}, Company: {$member->company_id}, Status: {$member->status}");
            }
        } else {
            $this->error("   No team members found!");
        }
        $this->newLine();

        // Test Vehicles
        $this->info('2. Testing Vehicles:');
        $vehicles = Vehicle::select('id', 'registration_number', 'make', 'model', 'company_id', 'status')->get();
        $this->line("   Total Vehicles: {$vehicles->count()}");
        
        if ($vehicles->count() > 0) {
            $this->line("   Sample Vehicles:");
            foreach ($vehicles->take(3) as $vehicle) {
                $this->line("   - ID: {$vehicle->id}, Registration: {$vehicle->registration_number}, Make: {$vehicle->make}, Model: {$vehicle->model}, Company: {$vehicle->company_id}, Status: {$vehicle->status}");
            }
        } else {
            $this->error("   No vehicles found!");
        }
        $this->newLine();

        // Test Drivers
        $this->info('3. Testing Drivers:');
        $drivers = Driver::select('id', 'full_name', 'license_number', 'company_id', 'status')->get();
        $this->line("   Total Drivers: {$drivers->count()}");
        
        if ($drivers->count() > 0) {
            $this->line("   Sample Drivers:");
            foreach ($drivers->take(3) as $driver) {
                $this->line("   - ID: {$driver->id}, Name: {$driver->full_name}, License: {$driver->license_number}, Company: {$driver->company_id}, Status: {$driver->status}");
            }
        } else {
            $this->error("   No drivers found!");
        }
        $this->newLine();

        // Test Company Filtering
        $this->info('4. Testing Company Filtering:');
        $companyIds = TeamMember::distinct()->pluck('company_id')->filter()->values();
        $this->line("   Available Company IDs: " . $companyIds->implode(', '));
        
        if ($companyIds->count() > 0) {
            $testCompanyId = $companyIds->first();
            $this->line("   Testing with Company ID: {$testCompanyId}");
            
            $companyMembers = TeamMember::forCompany($testCompanyId)->active()->count();
            $companyVehicles = Vehicle::forCompany($testCompanyId)->count();
            $companyDrivers = Driver::forCompany($testCompanyId)->count();
            
            $this->line("   - Active Team Members: {$companyMembers}");
            $this->line("   - Vehicles: {$companyVehicles}");
            $this->line("   - Drivers: {$companyDrivers}");
        }
        $this->newLine();

        $this->info('Test completed!');
        
        return Command::SUCCESS;
    }
}