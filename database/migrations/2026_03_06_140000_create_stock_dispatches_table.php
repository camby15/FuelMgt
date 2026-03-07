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
        Schema::create('stock_dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('station_id')->constrained('fuel_stations')->onDelete('cascade');
            $table->date('dispatch_date')->comment('Date product was dispatched');
            $table->enum('product_type', ['AGO', 'PMS'])->default('AGO')->comment('Product type: AGO (Diesel) or PMS (Petrol)');
            $table->string('depot', 255)->comment('Loading depot name');
            $table->string('bdc', 255)->comment('BDC / loaded from');
            $table->decimal('quantity_dispatched', 12, 2)->default(0)->comment('Quantity dispatched in litres');
            $table->string('brv_number', 100)->comment('BRV number for tracking');
            $table->string('driver_name', 255)->comment('Driver full name');
            $table->string('driver_phone', 30)->nullable()->comment('Driver contact number');
            $table->string('inspected_by', 255)->comment('Liaison officer who inspected');
            $table->string('invoice_number', 100)->comment('Invoice number');
            $table->string('waybill_path', 500)->nullable()->comment('Stored waybill file path');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'station_id']);
            $table->index(['company_id', 'dispatch_date']);
            $table->index(['company_id', 'product_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_dispatches');
    }
};
