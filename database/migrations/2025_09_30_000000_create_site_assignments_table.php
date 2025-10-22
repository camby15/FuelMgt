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
        Schema::create('site_assignments', function (Blueprint $table) {
            // Primary key
            $table->id();
            
            // Foreign keys
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('home_connection_customers')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('team_paring')->onDelete('cascade');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Assignment details
            $table->string('assignment_title')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            $table->string('priority')->default('medium'); // low, medium, high, critical
            
            // Location details
            $table->string('site_address')->nullable();
            $table->string('site_contact_person')->nullable();
            $table->string('site_contact_number')->nullable();
            
            // Dates
            $table->dateTime('assigned_date')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('completed_date')->nullable();
            
            // Issue reporting
            $table->boolean('has_issue')->default(false);
            $table->text('issue_description')->nullable();
            $table->string('issue_status')->nullable(); // reported, investigating, resolved
            $table->text('resolution_notes')->nullable();
            $table->dateTime('issue_reported_at')->nullable();
            $table->dateTime('issue_resolved_at')->nullable();
            
            // Tracking
            $table->integer('progress')->default(0);
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->decimal('actual_hours', 8, 2)->nullable();
            
            // Soft deletes for history
            $table->softDeletes();
            
            $table->timestamps();
        });
        
        // Create a separate table for assignment history
        Schema::create('site_assignment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_assignment_id')->constrained()->onDelete('cascade');
            $table->string('action'); // created, updated, status_changed, issue_reported, issue_resolved
            $table->text('description')->nullable();
            $table->json('changes')->nullable();
            $table->foreignId('performed_by')->constrained('users');
            $table->timestamps();
        });

        // Add indexes for better performance
        Schema::table('site_assignments', function (Blueprint $table) {
            $table->index('status');
            $table->index('priority');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_assignment_history');
        Schema::dropIfExists('site_assignments');
    }
};
