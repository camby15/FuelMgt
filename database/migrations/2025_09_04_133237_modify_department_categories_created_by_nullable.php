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
            // Drop foreign key constraints first
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            // Modify columns to be nullable
            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();
            
            // Re-add foreign key constraints with nullable
            $table->foreign('created_by')->references('id')->on('company_sub_users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('company_sub_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('department_categories', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            // Modify columns back to not nullable
            $table->unsignedBigInteger('created_by')->nullable(false)->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();
            
            // Re-add foreign key constraints
            $table->foreign('created_by')->references('id')->on('company_sub_users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('company_sub_users')->onDelete('set null');
        });
    }
};