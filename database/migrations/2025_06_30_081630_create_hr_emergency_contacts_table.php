<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_employment_emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('primary_emergency_name')->nullable();
            $table->string('primary_emergency_relation')->nullable();
            $table->string('primary_emergency_phone')->nullable();
            $table->string('primary_emergency_email')->nullable();
            $table->string('primary_emergency_alt_phone')->nullable();
            $table->text('primary_emergency_address')->nullable();
            $table->string('secondary_emergency_name')->nullable();;
            $table->string('secondary_emergency_relation')->nullable();;
            $table->string('secondary_emergency_phone')->nullable();;
            $table->string('secondary_emergency_email')->nullable();
            $table->string('secondary_emergency_alt_phone')->nullable();
            $table->text('secondary_emergency_address')->nullable();;
            $table->string('blood_group')->nullable();
            $table->string('nhis_number')->nullable();
            $table->json('known_allergies')->nullable();
            $table->text('allergy_details')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('special_needs')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_employment_emergency_contacts');
    }
};