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
        Schema::table('central_store', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('item_category');
            $table->string('barcode')->nullable()->after('sku');
            
            // Add indexes for better performance
            $table->index(['company_id', 'sku']);
            $table->index(['company_id', 'barcode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('central_store', function (Blueprint $table) {
            $table->dropIndex(['company_id', 'sku']);
            $table->dropIndex(['company_id', 'barcode']);
            $table->dropColumn(['sku', 'barcode']);
        });
    }
};
