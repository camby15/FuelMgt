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
        Schema::create('home_connection_team_rosters', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('site_assignment_id');
            $table->unsignedBigInteger('company_id');
            
            // Schedule Information
            $table->date('schedule_date');
            $table->string('shift_type')->default('full_day'); // full_day, morning, afternoon, night
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            
            // Status and Notes
            $table->enum('status', ['Scheduled', 'Pending', 'In Progress', 'Completed', 'Cancelled'])->default('Scheduled');
            $table->text('notes')->nullable();
            
            // Audit Fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Key Constraints
            $table->foreign('team_id')->references('id')->on('team_paring')->onDelete('cascade');
            $table->foreign('site_assignment_id')->references('id')->on('site_assignments')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes for performance
            $table->index('team_id');
            $table->index('site_assignment_id');
            $table->index('company_id');
            $table->index('schedule_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_connection_team_rosters');
    }
};
