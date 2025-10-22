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
            // Add project manager ID column
            if (!Schema::hasColumn('requisitions', 'project_manager_id')) {
                $table->unsignedBigInteger('project_manager_id')->nullable()->after('requester_id');
                $table->foreign('project_manager_id')->references('id')->on('company_sub_users')->onDelete('set null');
            }
            
            // Add team leader ID column  
            if (!Schema::hasColumn('requisitions', 'team_leader_id')) {
                $table->unsignedBigInteger('team_leader_id')->nullable()->after('project_manager_id');
                $table->foreign('team_leader_id')->references('id')->on('team_members')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('requisitions', 'project_manager_id')) {
                $table->dropForeign(['project_manager_id']);
            }
            if (Schema::hasColumn('requisitions', 'team_leader_id')) {
                $table->dropForeign(['team_leader_id']);
            }
            
            // Then drop columns
            $table->dropColumn(['project_manager_id', 'team_leader_id']);
        });
    }
};
