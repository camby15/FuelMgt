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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            
            // Company relationship
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
            
            // Task basic information
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('task_code')->unique();
            
            // Project relationship
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            
            // Team assignment (from team_pairing table)
            $table->unsignedBigInteger('assigned_team_id');
            $table->foreign('assigned_team_id')->references('id')->on('team_paring')->onDelete('cascade');
            
            // Task timeline
            $table->date('due_date');
            $table->date('start_date')->nullable();
            $table->date('completed_date')->nullable();
            
            // Task priority and status
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled', 'on_hold'])->default('pending');
            
            // Progress tracking
            $table->integer('progress')->default(0);
            
            // Additional information
            $table->text('notes')->nullable();
            $table->text('attachments')->nullable(); // JSON field for file paths
            
            // Soft deletes
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['company_id', 'status'], 'tasks_company_status_index');
            $table->index(['project_id', 'status'], 'tasks_project_status_index');
            $table->index(['assigned_team_id', 'status'], 'tasks_team_status_index');
            $table->index(['due_date'], 'tasks_due_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
