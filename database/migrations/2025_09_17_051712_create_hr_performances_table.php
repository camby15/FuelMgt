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
        Schema::create('hr_performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->enum('type', ['self', 'manager', 'peer', '360']);
            $table->date('review_period_start');
            $table->date('review_period_end');
            $table->text('goals');
            $table->text('achievements');
            $table->text('areas_for_improvement');
            $table->decimal('overall_score', 3, 1)->nullable(); // 1.0 to 5.0
            $table->enum('overall_rating', ['excellent', 'good', 'satisfactory', 'needs_improvement', 'poor'])->nullable();
            $table->enum('status', ['draft', 'pending', 'completed', 'cancelled'])->default('draft');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_performances');
    }
};