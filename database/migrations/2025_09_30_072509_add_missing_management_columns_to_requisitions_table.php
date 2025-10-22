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
            // Add management approval status tracking
            $table->enum('management_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->text('management_notes')->nullable()->after('management_status');
            
            // Add management approval columns (if they don't exist)
            if (!Schema::hasColumn('requisitions', 'management_approved_by')) {
                $table->unsignedBigInteger('management_approved_by')->nullable()->after('approved_at');
                $table->foreign('management_approved_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('requisitions', 'management_approved_at')) {
                $table->timestamp('management_approved_at')->nullable()->after('management_approved_by');
            }
            
            if (!Schema::hasColumn('requisitions', 'management_rejection_reason')) {
                $table->text('management_rejection_reason')->nullable()->after('management_approved_at');
            }
            
            // Add other missing columns that might be needed
            if (!Schema::hasColumn('requisitions', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_at');
                $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('requisitions', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }
            
            if (!Schema::hasColumn('requisitions', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
            
            if (!Schema::hasColumn('requisitions', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('team_leader_id');
                $table->foreign('department_id')->references('id')->on('department_categories')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('requisitions', 'requisition_date')) {
                $table->date('requisition_date')->nullable()->after('reference_code');
            }
            
            if (!Schema::hasColumn('requisitions', 'required_date')) {
                $table->date('required_date')->nullable()->after('requisition_date');
            }
            
            if (!Schema::hasColumn('requisitions', 'issued_by')) {
                $table->unsignedBigInteger('issued_by')->nullable()->after('rejection_reason');
                $table->foreign('issued_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('requisitions', 'issued_at')) {
                $table->timestamp('issued_at')->nullable()->after('issued_by');
            }
            
            if (!Schema::hasColumn('requisitions', 'team_allocations')) {
                $table->json('team_allocations')->nullable()->after('management_rejection_reason');
            }
            
            if (!Schema::hasColumn('requisitions', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->nullable()->after('items');
            }
            
            if (!Schema::hasColumn('requisitions', 'item_allocations')) {
                $table->json('item_allocations')->nullable()->after('team_allocations');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn(['management_status', 'management_notes']);
            
            // Only drop columns if they exist and we added them
            if (Schema::hasColumn('requisitions', 'rejected_by')) {
                $table->dropForeign(['rejected_by']);
                $table->dropColumn('rejected_by');
            }
            
            if (Schema::hasColumn('requisitions', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }
            
            if (Schema::hasColumn('requisitions', 'issued_by')) {
                $table->dropForeign(['issued_by']);
                $table->dropColumn('issued_by');
            }
            
            $table->dropColumn([
                'rejected_at', 'rejection_reason', 'requisition_date', 
                'required_date', 'issued_at', 'team_allocations', 
                'total_amount', 'item_allocations'
            ]);
        });
    }
};