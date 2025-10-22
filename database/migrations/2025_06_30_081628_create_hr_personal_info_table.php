<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_employment_personal_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('primary_phone');
            $table->string('secondary_phone')->nullable();
            $table->string('personal_email');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
            $table->string('nationality');
            $table->string('country');
            $table->string('region');
            $table->string('city');
            $table->string('id_type_1')->nullable();
            $table->string('id_number_1')->nullable();
            $table->string('id_type_2')->nullable();
            $table->string('id_number_2')->nullable();
            $table->text('id_notes')->nullable();
            $table->string('tin_number');
            $table->string('ssnit_number')->nullable();
            $table->enum('tax_status', ['resident', 'non-resident']);
            $table->enum('tax_exemption', ['none', 'disabled', 'dependent', 'other']);
            $table->text('tax_notes')->nullable();
            $table->string('profile_picture')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_employment_personal_infos');
    }
};