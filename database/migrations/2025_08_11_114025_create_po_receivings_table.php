<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
            Schema::create('po_receivings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('purchase_order_id')->constrained('wh__purchase_orders');
            $table->string('receiving_number')->unique();
            $table->date('receiving_date');
            $table->string('delivery_note')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->text('notes')->nullable();
            $table->json('received_items'); // {item_id, name, ordered_qty, received_qty, rejected_qty, location, quality_check, etc.}
            $table->decimal('total_received', 10, 2)->default(0);
            $table->decimal('total_rejected', 10, 2)->default(0);
            $table->string('status')->default('pending'); // pending, partial, completed
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('po_receivings');
    }
};