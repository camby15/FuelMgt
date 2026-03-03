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
        Schema::create('rosters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('station_id')->constrained('fuel_stations')->onDelete('cascade');
            $table->foreignId('attendant_id')->constrained('fuel_attendants')->onDelete('cascade');
            $table->date('week_start_date')->comment('ISO week start date (Monday)');
            $table->tinyInteger('day_of_week')->comment('1=Monday, 2=Tuesday, ..., 7=Sunday');
            $table->enum('shift_type', ['morning', 'evening', 'off'])->default('morning');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['attendant_id', 'week_start_date', 'day_of_week'], 'unique_attendant_week_day');
            $table->index(['company_id', 'week_start_date']);
            $table->index(['station_id', 'week_start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rosters');
    }
};
