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
        Schema::table('requisitions', function (Blueprint $table) {
            // First, add the new department_id column
            $table->unsignedBigInteger('department_id')->nullable()->after('team_leader_id');
            
            // Add foreign key constraint
            $table->foreign('department_id')->references('id')->on('department_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['department_id']);
            
            // Drop the department_id column
            $table->dropColumn('department_id');
        });
    }
};
