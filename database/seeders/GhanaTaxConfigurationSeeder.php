<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxConfiguration;
use App\Models\CompanyProfile;

class GhanaTaxConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all companies and create default tax configurations for each
        $companies = CompanyProfile::all();
        
        foreach ($companies as $company) {
            // Check if tax configurations already exist for this company
            $existingConfigs = TaxConfiguration::where('company_id', $company->id)->count();
            
            if ($existingConfigs === 0) {
                // Create default Ghana tax configurations
                TaxConfiguration::createDefaultGhanaTaxConfigurations($company->id, 1); // Using user ID 1 as default
            }
        }
    }
}
