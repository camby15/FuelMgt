<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Allows linking a company sub user (e.g. station manager) to a single fuel station.
     */
    public function up(): void
    {
        Schema::table('company_sub_users', function (Blueprint $table) {
            $table->foreignId('fuel_station_id')
                ->nullable()
                ->after('profile_id')
                ->constrained('fuel_stations')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_sub_users', function (Blueprint $table) {
            $table->dropForeign(['fuel_station_id']);
        });
    }
};
