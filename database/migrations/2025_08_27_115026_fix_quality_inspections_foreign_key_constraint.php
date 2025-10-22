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
        Schema::table('quality_inspections', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['company_id']);
            
            // Add the correct foreign key constraint
            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quality_inspections', function (Blueprint $table) {
            // Drop the corrected foreign key constraint
            $table->dropForeign(['company_id']);
            
            // Add back the old foreign key constraint (for rollback purposes)
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }
};