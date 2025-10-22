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
            // Add missing columns that the controller is trying to use
            if (!Schema::hasColumn('requisitions', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->default(0)->after('items');
            }
            
            if (!Schema::hasColumn('requisitions', 'management_approved_by')) {
                $table->unsignedBigInteger('management_approved_by')->nullable()->after('approved_at');
            }
            
            if (!Schema::hasColumn('requisitions', 'management_approved_at')) {
                $table->timestamp('management_approved_at')->nullable()->after('management_approved_by');
            }
            
            if (!Schema::hasColumn('requisitions', 'management_rejection_reason')) {
                $table->text('management_rejection_reason')->nullable()->after('management_approved_at');
            }
            
            if (!Schema::hasColumn('requisitions', 'team_allocations')) {
                $table->json('team_allocations')->nullable()->after('management_rejection_reason');
            }
            
            if (!Schema::hasColumn('requisitions', 'management_status')) {
                $table->enum('management_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            }
            
            if (!Schema::hasColumn('requisitions', 'management_notes')) {
                $table->text('management_notes')->nullable()->after('management_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $columns = [
                'total_amount',
                'management_approved_by',
                'management_approved_at', 
                'management_rejection_reason',
                'team_allocations',
                'management_status',
                'management_notes'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('requisitions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
