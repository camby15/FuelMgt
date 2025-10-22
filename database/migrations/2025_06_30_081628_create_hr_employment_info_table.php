<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_employment_employment_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('staff_id')->unique();
            $table->date('join_date');
            $table->string('department');
            $table->string('position');
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern']);
            $table->enum('probation_status', ['not_started', 'in_progress', 'completed', 'extended']);
            $table->enum('employment_status', ['active', 'on_leave', 'suspended', 'resigned', 'terminated']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_employment_employment_infos');
    }
};