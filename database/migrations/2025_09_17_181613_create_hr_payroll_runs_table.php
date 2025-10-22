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
        Schema::create('hr_payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('pay_period'); // monthly, bi-weekly, weekly
            $table->date('start_date');
            $table->date('end_date');
            $table->string('employee_selection'); // all, department, individual
            $table->string('department')->nullable();
            $table->json('selected_employees'); // Array of employee IDs
            $table->boolean('include_bonuses')->default(false);
            $table->boolean('include_deductions')->default(true);
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->unsignedBigInteger('created_by');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_payroll_runs');
    }
};
