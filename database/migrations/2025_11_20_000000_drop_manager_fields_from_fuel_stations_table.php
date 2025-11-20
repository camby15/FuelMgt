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
        Schema::table('fuel_stations', function (Blueprint $table) {
            if (Schema::hasColumn('fuel_stations', 'manager_name')) {
                $table->dropIndex('fuel_stations_company_id_manager_name_index');
                $table->dropColumn(['manager_name', 'manager_phone']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_stations', function (Blueprint $table) {
            if (!Schema::hasColumn('fuel_stations', 'manager_name')) {
                $table->string('manager_name', 255)->nullable();
                $table->string('manager_phone', 30)->nullable();
                $table->index(['company_id', 'manager_name'], 'fuel_stations_company_id_manager_name_index');
            }
        });
    }
};
