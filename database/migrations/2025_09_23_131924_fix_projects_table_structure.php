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
        Schema::table('projects', function (Blueprint $table) {
            // Check if project_type_id column exists and drop it
            if (Schema::hasColumn('projects', 'project_type_id')) {
                // Drop foreign key constraint first
                $table->dropForeign(['project_type_id']);
                // Drop the column
                $table->dropColumn('project_type_id');
            }
            
            // Add project_type as string column if it doesn't exist
            if (!Schema::hasColumn('projects', 'project_type')) {
                $table->string('project_type')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop project_type column if it exists
            if (Schema::hasColumn('projects', 'project_type')) {
                $table->dropColumn('project_type');
            }
            
            // Add back project_type_id column
            $table->unsignedBigInteger('project_type_id')->nullable()->after('description');
            
            // Add back foreign key constraint (this will fail if project_types table doesn't exist)
            // $table->foreign('project_type_id')->references('id')->on('project_types')->onDelete('set null');
        });
    }
};
