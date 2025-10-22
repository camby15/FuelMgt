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
        Schema::create('projects', function (Blueprint $table) {
            // Basic Project Information
            $table->id();
            
            // Company relationship - ADDED
            $table->unsignedBigInteger('company_id');
            
            $table->string('name');
            $table->string('project_code')->unique();
            $table->text('description')->nullable();
            
            // Project Type - UPDATED to use string instead of foreign key
            $table->string('project_type')->nullable();
            
            // Manager (from company_sub_users) - Keep ID but display name in views
            $table->foreignId('project_manager_id')
                  ->nullable()
                  ->constrained('company_sub_users')
                  ->nullOnDelete()
                  ->comment('References company_sub_users table - display name in views');
            
            // Project Timeline
            $table->date('start_date');
            $table->date('end_date');
            
            // Financial Information
            $table->decimal('budget', 15, 2);
            $table->decimal('actual_cost', 15, 2)->default(0);
            
            // Project Status
            $table->enum('status', [
                'not_started', 
                'in_progress', 
                'on_hold', 
                'cancelled', 
                'completed'
            ])->default('not_started');
            
            // Progress Tracking
            $table->integer('progress')->default(0);
            
            // Additional Information
            $table->text('notes')->nullable();
            $table->text('objectives')->nullable();
            $table->text('deliverables')->nullable();
            
            // Client Information (if applicable)
            $table->unsignedBigInteger('client_id')
                  ->nullable()
                  ->index()
                  ->comment('References clients table if it exists');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Key Constraints
            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
            
            // Indexes
            $table->index('project_code');
            $table->index('status');
            $table->index('project_manager_id');
            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'project_type']);
        });

        // Add foreign key constraint for client_id separately if needed
        // This can be done in a separate migration when the clients table is ready
        // Schema::table('projects', function (Blueprint $table) {
        //     $table->foreign('client_id')
        //           ->references('id')
        //           ->on('clients')
        //           ->nullOnDelete();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};