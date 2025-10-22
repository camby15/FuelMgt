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
        if (!Schema::hasTable('warehouse_logs')) {
            Schema::create('warehouse_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->unsignedBigInteger('purchase_order_id')->nullable();
                $table->string('model')->nullable(); // e.g. Wh_Supplier, Wh_PurchaseOrder
                $table->unsignedBigInteger('model_id')->nullable(); // ID of the related model
                $table->string('action'); // e.g. created, approved, inspected, add_rating
                $table->text('description')->nullable();
                $table->unsignedBigInteger('performed_by')->nullable();
                $table->timestamp('performed_at')->useCurrent();
                $table->timestamps();

                $table->foreign('purchase_order_id')->references('id')->on('wh__purchase_orders')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_logs');
    }
};
