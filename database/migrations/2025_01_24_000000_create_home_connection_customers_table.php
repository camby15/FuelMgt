<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table already exists
        if (Schema::hasTable('home_connection_customers')) {
            // Table exists, just add missing foreign key constraints
            Schema::table('home_connection_customers', function (Blueprint $table) {
                // Add foreign key for company_id (reference company_profiles table)
                $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
                
                // Add foreign key for created_by
                $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
                
                // Add foreign key for updated_by (nullable)
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
            });
        } else {
            // Table doesn't exist, create it with all constraints
            Schema::create('home_connection_customers', function (Blueprint $table) {
            $table->id();
            
            // Company relationship
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->enum('business_unit', ['GESL', 'LINFRA'])->default('GESL');
            
            // Customer Information (matching frontend form)
            $table->string('msisdn')->unique(); // Phone number with country code
            $table->string('customer_name');
            $table->string('email')->nullable();
            $table->string('contact_number'); // Secondary contact
            $table->enum('connection_type', ['Traditional', 'Quick ODN'])->default('Traditional');
            
            // Location Information
            $table->string('location'); // Area - City format
            $table->text('gps_address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Status
            $table->enum('status', ['Active', 'Inactive', 'Pending', 'Schedule'])->default('Pending');
            
            // Additional Information (optional fields for future use)
            $table->string('customer_id')->unique()->nullable();
            $table->string('secondary_phone')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            
            // Address Information (optional)
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Ghana');
            
            // Account Information (optional)
            $table->string('account_number')->nullable();
            $table->string('meter_number')->nullable();
            $table->string('tariff_type')->nullable();
            $table->string('service_type')->nullable();
            $table->enum('billing_type', ['prepaid', 'postpaid'])->default('prepaid');
            
            // Additional Information
            $table->text('notes')->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable();
            $table->string('occupation')->nullable();
            
            // Audit Fields
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['company_id', 'business_unit']);
            $table->index('msisdn');
            $table->index('customer_id');
            $table->index('contact_number');
            $table->index('status');
            $table->index('connection_type');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_connection_customers');
    }
};
