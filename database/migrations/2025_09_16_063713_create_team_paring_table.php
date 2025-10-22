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
        Schema::create('team_paring', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('team_name');
            $table->string('team_code')->unique();
            $table->string('team_location');
            $table->enum('team_status', ['active', 'inactive', 'deployed', 'maintenance'])->default('active');
            $table->text('team_allocation')->nullable();
            $table->unsignedBigInteger('team_lead')->nullable(); // References team_members table
            $table->unsignedBigInteger('primary_vehicle')->nullable(); // References vehicles table
            $table->unsignedBigInteger('primary_driver')->nullable(); // References drivers table
            $table->date('formation_date')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
            $table->foreign('team_lead')->references('id')->on('team_members')->onDelete('set null');
            $table->foreign('primary_vehicle')->references('id')->on('vehicles')->onDelete('set null');
            $table->foreign('primary_driver')->references('id')->on('drivers')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for better performance
            $table->index(['company_id', 'team_status']);
            $table->index('team_location');
            $table->index('team_code');
        });

        // Create pivot table for team members
        Schema::create('team_paring_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_paring_id');
            $table->unsignedBigInteger('team_member_id');
            $table->timestamps();

            $table->foreign('team_paring_id')->references('id')->on('team_paring')->onDelete('cascade');
            $table->foreign('team_member_id')->references('id')->on('team_members')->onDelete('cascade');
            
            // Ensure unique combination
            $table->unique(['team_paring_id', 'team_member_id']);
        });

        // Create pivot table for vehicles
        Schema::create('team_paring_vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_paring_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->timestamps();

            $table->foreign('team_paring_id')->references('id')->on('team_paring')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            
            // Ensure unique combination
            $table->unique(['team_paring_id', 'vehicle_id']);
        });

        // Create pivot table for drivers
        Schema::create('team_paring_drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_paring_id');
            $table->unsignedBigInteger('driver_id');
            $table->timestamps();

            $table->foreign('team_paring_id')->references('id')->on('team_paring')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            
            // Ensure unique combination
            $table->unique(['team_paring_id', 'driver_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_paring_drivers');
        Schema::dropIfExists('team_paring_vehicles');
        Schema::dropIfExists('team_paring_members');
        Schema::dropIfExists('team_paring');
    }
};