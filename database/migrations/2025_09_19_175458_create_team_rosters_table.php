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
        Schema::create('team_rosters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('team_paring')->onDelete('cascade');
            $table->string('roster_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('roster_period', ['weekly', 'monthly']);
            $table->json('working_days')->nullable(); // Array of working days
            $table->json('leave_days')->nullable(); // Array of leave days
            $table->time('work_start_time')->nullable();
            $table->time('work_end_time')->nullable();
            $table->enum('leave_type', ['vacation', 'sick', 'personal', 'holiday', 'training'])->nullable();
            $table->string('leave_reason')->nullable();
            $table->enum('roster_status', ['draft', 'active', 'inactive'])->default('draft');
            $table->integer('max_working_hours')->default(40);
            $table->text('roster_notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index(['company_id', 'roster_status']);
            $table->index(['team_id', 'roster_status']);
            $table->index(['start_date', 'end_date']);
            $table->index('roster_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_rosters');
    }
};