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
        Schema::table('crm_sales', function (Blueprint $table) {
            $table->decimal('sales_commission', 10, 2)->nullable()->after('deal_value');
            $table->foreignId('category_id')->nullable()->after('sales_commission')
                  ->constrained('crm_sales_category')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_sales', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['sales_commission', 'category_id']);
        });
    }
};
