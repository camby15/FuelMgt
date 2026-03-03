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
        Schema::create('fuel_attendants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->cascadeOnDelete();
            $table->foreignId('fuel_station_id')->nullable()->constrained('fuel_stations')->nullOnDelete();
            $table->string('staff_id', 64);
            $table->string('first_name', 120);
            $table->string('other_names', 180)->nullable();
            $table->string('gender', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number_1', 30);
            $table->string('phone_number_2', 30)->nullable();
            $table->string('contact_name', 255)->nullable();
            $table->string('contact_relationship', 120)->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->text('contact_address')->nullable();
            $table->string('status', 32)->default('active');
            $table->string('shift', 120)->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'staff_id']);
            $table->index(['company_id', 'fuel_station_id']);
            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'phone_number_1']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_attendants');
    }
};
