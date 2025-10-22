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
        Schema::table('supplier_returns', function (Blueprint $table) {
            // Check if processed_at column doesn't exist before adding it
            if (!Schema::hasColumn('supplier_returns', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('status');
            }
            
            // Check if processed_by column doesn't exist before adding it
            if (!Schema::hasColumn('supplier_returns', 'processed_by')) {
                $table->foreignId('processed_by')->nullable()->constrained('users')->after('processed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_returns', function (Blueprint $table) {
            // Only drop if columns exist
            if (Schema::hasColumn('supplier_returns', 'processed_by')) {
                $table->dropForeign(['processed_by']);
            }
            
            if (Schema::hasColumn('supplier_returns', 'processed_at')) {
                $table->dropColumn(['processed_at', 'processed_by']);
            }
        });
    }
};
