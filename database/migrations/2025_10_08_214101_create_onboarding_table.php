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
        Schema::create('onboarding', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('offer_accepted_date')->nullable();
            $table->enum('documents_uploaded_status', ['pending', 'completed', 'in_progress'])->default('pending');
            $table->date('documents_uploaded_date')->nullable();
            $table->enum('staff_id_assigned_status', ['pending', 'completed', 'in_progress'])->default('pending');
            $table->date('staff_id_assigned_date')->nullable();
            $table->enum('first_day_checklist_status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->date('first_day_checklist_date')->nullable();
            $table->date('start_date');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->enum('overall_status', ['not_started', 'in_progress', 'completed', 'on_hold'])->default('not_started');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding');
    }
};
