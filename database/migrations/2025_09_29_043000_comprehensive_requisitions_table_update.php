<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add all missing columns to requisitions table
        Schema::table('requisitions', function (Blueprint $table) {
            // Add approved_by column first if it doesn't exist
            if (!Schema::hasColumn('requisitions', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('status');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // Add approved_at column if it doesn't exist
            if (!Schema::hasColumn('requisitions', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            
            // Add reorder fields if they don't exist
            if (!Schema::hasColumn('requisitions', 'is_reorder')) {
                $table->boolean('is_reorder')->default(false)->after('approved_at');
            }
            if (!Schema::hasColumn('requisitions', 'batch_number')) {
                $table->string('batch_number')->nullable()->after('is_reorder');
            }
        });
        
        // Update the status enum to include 'created'
        DB::statement("ALTER TABLE requisitions MODIFY COLUMN status ENUM('draft', 'created', 'pending', 'approved', 'rejected', 'issued') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('requisitions', 'approved_by')) {
                $table->dropForeign(['approved_by']);
            }
            // Then drop columns
            $table->dropColumn(['approved_by', 'approved_at', 'is_reorder', 'batch_number']);
        });
        
        // Revert status enum
        DB::statement("ALTER TABLE requisitions MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'rejected', 'issued') DEFAULT 'draft'");
    }
};
