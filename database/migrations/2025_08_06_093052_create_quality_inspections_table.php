<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('quality_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_id')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('wh__suppliers')->onDelete('cascade');
            $table->foreignId('purchase_order_id')->constrained('wh__purchase_orders')->onDelete('cascade');
            $table->unsignedInteger('item_id')->nullable(); //->constrained('items')->onDelete('set null');

            // Item details (denormalized for easy reporting)
            $table->string('item_name');
            $table->string('item_category');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total_price', 12, 2);

            // Inspection details
            $table->string('batch_number');
            $table->date('inspection_date');
            $table->text('checklist_results')->nullable(); // JSON of checklist items
            $table->text('notes')->nullable();
                         $table->string('status')->default('processing'); // approved, processing, reject (inspection statuses only)
            $table->text('photos')->nullable(); // JSON array of photo paths

            // QC details
            $table->string('inspector_name');
            $table->text('inspection_result')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['company_id', 'status']);
            $table->index(['supplier_id', 'purchase_order_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('quality_inspections');
    }
};