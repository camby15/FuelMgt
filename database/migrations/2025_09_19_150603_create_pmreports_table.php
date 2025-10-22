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
        Schema::create('pmreports', function (Blueprint $table) {
            $table->id();
            
            // Report metadata
            $table->string('report_name')->nullable();
            $table->string('report_type')->default('analytics'); // analytics, summary, detailed
            $table->date('report_date');
            $table->string('period_type')->default('monthly'); // daily, weekly, monthly, quarterly, yearly
            
            // Project statistics
            $table->integer('total_projects')->default(0);
            $table->integer('active_projects')->default(0);
            $table->integer('completed_projects')->default(0);
            $table->integer('on_hold_projects')->default(0);
            $table->integer('cancelled_projects')->default(0);
            
            // Task statistics
            $table->integer('total_tasks')->default(0);
            $table->integer('active_tasks')->default(0);
            $table->integer('completed_tasks')->default(0);
            $table->integer('pending_tasks')->default(0);
            $table->integer('overdue_tasks')->default(0);
            
            // Task priority breakdown
            $table->integer('high_priority_tasks')->default(0);
            $table->integer('medium_priority_tasks')->default(0);
            $table->integer('low_priority_tasks')->default(0);
            
            // Budget information
            $table->decimal('total_budget', 15, 2)->default(0);
            $table->decimal('budget_spent', 15, 2)->default(0);
            $table->decimal('budget_remaining', 15, 2)->default(0);
            $table->decimal('budget_utilization_percentage', 5, 2)->default(0);
            
            // Team statistics
            $table->integer('total_team_members')->default(0);
            $table->integer('active_team_members')->default(0);
            $table->integer('team_members_on_leave')->default(0);
            $table->integer('team_members_available')->default(0);
            
            // Performance metrics
            $table->decimal('project_completion_rate', 5, 2)->default(0);
            $table->decimal('task_completion_rate', 5, 2)->default(0);
            $table->decimal('on_time_delivery_rate', 5, 2)->default(0);
            $table->integer('average_project_duration_days')->default(0);
            $table->integer('average_task_duration_days')->default(0);
            
            // Timeline data (JSON for storing monthly/weekly breakdowns)
            $table->json('timeline_data')->nullable();
            
            // Status distribution (JSON for storing status breakdowns)
            $table->json('status_distribution')->nullable();
            
            // Priority distribution (JSON for storing priority breakdowns)
            $table->json('priority_distribution')->nullable();
            
            // Additional metadata
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['report_date', 'period_type']);
            $table->index(['report_type', 'is_active']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmreports');
    }
};
