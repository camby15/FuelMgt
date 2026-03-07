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
        Schema::create('stock_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('station_id')->constrained('fuel_stations')->onDelete('cascade');
            $table->date('recon_date')->comment('Date of reconciliation entry');
            $table->string('tank', 100)->comment('Tank name/label e.g. PMS Tank 1');
            $table->decimal('opening_stock', 12, 2)->default(0)->comment('Opening stock in litres');
            $table->decimal('add_stock', 12, 2)->default(0)->comment('Stock added in litres');
            $table->decimal('total_stock', 12, 2)->default(0)->comment('Total stock (opening + add) in litres');
            $table->decimal('sales_volume', 12, 2)->default(0)->comment('Sales volume in litres');
            $table->decimal('closing_stock', 12, 2)->default(0)->comment('Closing stock in litres');
            $table->decimal('dipping_reading', 10, 2)->default(0)->comment('Dipping reading in millimetres');
            $table->decimal('variance', 12, 2)->default(0)->comment('Variance between expected and dipped value in litres');
            $table->text('notes')->nullable()->comment('Reconciliation notes or explanations');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'station_id']);
            $table->index(['company_id', 'recon_date']);
            $table->index(['company_id', 'station_id', 'recon_date']);
            $table->index(['company_id', 'station_id', 'tank']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_reconciliations');
    }
};

