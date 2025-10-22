<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wh__purchase_orders', function (Blueprint $table) {
            $table->id();
             $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
              $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->string('po_number')->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->date('order_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('status')->default('pending');
            $table->json('items'); 
            $table->string('payment_terms')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supplier_id')->references('id')->on('wh__suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wh__purchase_orders');
    }
};
