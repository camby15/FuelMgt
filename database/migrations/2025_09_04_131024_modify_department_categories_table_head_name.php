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
        Schema::table('department_categories', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['head_of_department_id']);
            
            // Drop the old column
            $table->dropColumn('head_of_department_id');
            
            // Add new head_name column
            $table->string('head_name')->nullable()->after('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('department_categories', function (Blueprint $table) {
            // Drop the head_name column
            $table->dropColumn('head_name');
            
            // Add back the head_of_department_id column
            $table->unsignedBigInteger('head_of_department_id')->nullable()->after('company_id');
            
            // Restore foreign key constraint
            $table->foreign('head_of_department_id')->references('id')->on('company_sub_users')->onDelete('set null');
        });
    }
};