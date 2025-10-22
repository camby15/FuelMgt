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
        if (!Schema::hasTable('hr_performances')) {
            Schema::create('hr_performances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('employee_id');
                $table->enum('type', ['self', 'manager', 'peer', '360']);
                $table->date('review_period_start');
                $table->date('review_period_end');
                $table->text('goals')->nullable();
                $table->text('achievements')->nullable();
                $table->text('areas_for_improvement')->nullable();
                $table->decimal('overall_score', 2, 1)->nullable(); // 1-5 scale
                $table->enum('overall_rating', ['excellent', 'good', 'satisfactory', 'needs_improvement', 'poor'])->nullable();
                $table->enum('status', ['draft', 'pending', 'completed', 'cancelled'])->default('draft');
                $table->unsignedBigInteger('reviewer_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('set null');

                // Indexes
                $table->index(['company_id', 'status']);
                $table->index(['company_id', 'type']);
                $table->index(['company_id', 'employee_id']);
                $table->index('review_period_start');
                $table->index('review_period_end');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_performances');
    }
};
