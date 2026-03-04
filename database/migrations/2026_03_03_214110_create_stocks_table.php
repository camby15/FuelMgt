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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('station_id')->constrained('fuel_stations')->onDelete('cascade');
            $table->date('delivery_date')->comment('Date product was discharged at station');
            $table->string('brv_number', 100)->unique()->comment('BRV Number for tracking');
            $table->string('driver_name', 255)->comment('Name of the driver');
            $table->string('driver_phone', 30)->comment('Driver phone number');
            $table->string('invoice_number', 100)->comment('Invoice number for the delivery');
            $table->enum('product_type', ['AGO', 'PMS'])->default('AGO')->comment('Product type: AGO (Diesel) or PMS (Petrol)');
            $table->decimal('dispatched_quantity', 12, 2)->default(0)->comment('Quantity dispatched in litres');
            $table->decimal('received_quantity', 12, 2)->default(0)->comment('Quantity actually received in litres');
            $table->string('inspected_by', 255)->comment('Name of station manager who inspected');
            $table->decimal('running_balance', 12, 2)->default(0)->comment('Running balance after this transaction');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'brv_number'], 'unique_company_brv');
            $table->index(['company_id', 'station_id']);
            $table->index(['company_id', 'product_type']);
            $table->index(['company_id', 'delivery_date']);
            $table->index(['station_id', 'product_type', 'delivery_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
