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
        Schema::table('wh__purchase_orders', function (Blueprint $table) {
            // Add tax fields if they don't exist
            if (!Schema::hasColumn('wh__purchase_orders', 'tax_configuration_id')) {
                $table->foreignId('tax_configuration_id')->nullable()->after('total_items')->constrained('tax_configurations')->onDelete('set null');
            }
            if (!Schema::hasColumn('wh__purchase_orders', 'tax_type')) {
                $table->enum('tax_type', ['standard', 'flat_rate', 'exempt', 'custom'])->default('standard')->after('tax_configuration_id');
            }
            if (!Schema::hasColumn('wh__purchase_orders', 'tax_rate')) {
                $table->decimal('tax_rate', 5, 2)->default(0.00)->after('tax_type');
            }
            if (!Schema::hasColumn('wh__purchase_orders', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->default(0.00)->after('tax_rate');
            }
            if (!Schema::hasColumn('wh__purchase_orders', 'tax_amount')) {
                $table->decimal('tax_amount', 15, 2)->default(0.00)->after('subtotal');
            }
            if (!Schema::hasColumn('wh__purchase_orders', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->default(0.00)->after('tax_amount');
            }
            if (!Schema::hasColumn('wh__purchase_orders', 'is_tax_exempt')) {
                $table->boolean('is_tax_exempt')->default(false)->after('total_amount');
            }
            if (!Schema::hasColumn('wh__purchase_orders', 'tax_exemption_reason')) {
                $table->text('tax_exemption_reason')->nullable()->after('is_tax_exempt');
            }
            if (!Schema::hasColumn('wh__purchase_orders', 'tax_breakdown')) {
                $table->json('tax_breakdown')->nullable()->after('tax_exemption_reason');
            }
            
            // Add reorder fields if they don't exist
            if (!Schema::hasColumn('wh__purchase_orders', 'is_reorder')) {
                $table->boolean('is_reorder')->default(false)->after('tax_breakdown');
            }
            if (!Schema::hasColumn('wh__purchase_orders', 'batch_number')) {
                $table->string('batch_number')->nullable()->after('is_reorder');
            }
            if (!Schema::hasColumn('wh__purchase_orders', 'reorder_reason')) {
                $table->text('reorder_reason')->nullable()->after('batch_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wh__purchase_orders', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('wh__purchase_orders', 'tax_configuration_id')) {
                $table->dropForeign(['tax_configuration_id']);
            }
            
            // Drop all added columns
            $columnsToDrop = [];
            if (Schema::hasColumn('wh__purchase_orders', 'tax_configuration_id')) {
                $columnsToDrop[] = 'tax_configuration_id';
            }
            if (Schema::hasColumn('wh__purchase_orders', 'tax_type')) {
                $columnsToDrop[] = 'tax_type';
            }
            if (Schema::hasColumn('wh__purchase_orders', 'tax_rate')) {
                $columnsToDrop[] = 'tax_rate';
            }
            if (Schema::hasColumn('wh__purchase_orders', 'subtotal')) {
                $columnsToDrop[] = 'subtotal';
            }
            if (Schema::hasColumn('wh__purchase_orders', 'tax_amount')) {
                $columnsToDrop[] = 'tax_amount';
            }
            if (Schema::hasColumn('wh__purchase_orders', 'total_amount')) {
                $columnsToDrop[] = 'total_amount';
            }
            if (Schema::hasColumn('wh__purchase_orders', 'is_tax_exempt')) {
                $columnsToDrop[] = 'is_tax_exempt';
            }
            if (Schema::hasColumn('wh__purchase_orders', 'tax_exemption_reason')) {
                $columnsToDrop[] = 'tax_exemption_reason';
            }
            if (Schema::hasColumn('wh__purchase_orders', 'tax_breakdown')) {
                $columnsToDrop[] = 'tax_breakdown';
            }
            if (Schema::hasColumn('wh__purchase_orders', 'is_reorder')) {
                $columnsToDrop[] = 'is_reorder';
            }
            if (Schema::hasColumn('wh__purchase_orders', 'batch_number')) {
                $columnsToDrop[] = 'batch_number';
            }
            if (Schema::hasColumn('wh__purchase_orders', 'reorder_reason')) {
                $columnsToDrop[] = 'reorder_reason';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};

