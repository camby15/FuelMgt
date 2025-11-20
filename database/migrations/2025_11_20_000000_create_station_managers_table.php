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
        Schema::create('station_managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->cascadeOnDelete();
            $table->foreignId('fuel_station_id')->nullable()->constrained('fuel_stations')->nullOnDelete();
            $table->string('full_name', 255);
            $table->string('gender', 20);
            $table->date('date_of_birth');
            $table->string('phone', 30);
            $table->string('email', 255);
            $table->string('avatar_path')->nullable();
            $table->text('address');
            $table->string('location')->nullable();
            $table->date('assigned_at');
            $table->string('status', 32)->default('active');
            $table->text('termination_reason')->nullable();
            $table->timestamp('terminated_at')->nullable();
            $table->foreignId('terminated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'email']);
            $table->unique(['company_id', 'phone']);
            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'fuel_station_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station_managers');
    }
};
