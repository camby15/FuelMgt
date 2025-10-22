<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_employment_bank_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_number')->nullable();
            $table->enum('account_type', ['savings', 'current', 'fixed', 'dollar', 'euro', 'business'])->nullable();
            $table->string('currency')->nullable();
            $table->string('ezwich_number')->nullable();
            $table->string('mobile_network')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('mobile_name')->nullable();
            $table->string('bank_statement')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('bank_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_employment_bank_infos');
    }
};