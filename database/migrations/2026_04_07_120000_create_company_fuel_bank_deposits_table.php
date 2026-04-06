<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_fuel_bank_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->cascadeOnDelete();
            $table->foreignId('fuel_station_id')->constrained('fuel_stations')->cascadeOnDelete();
            $table->date('transaction_date');
            $table->date('sales_date');
            $table->string('account_name', 255);
            $table->string('account_number', 191);
            $table->decimal('amount', 15, 2);
            $table->string('deposit_by', 255);
            $table->text('narration');
            $table->text('details')->nullable();
            $table->string('payment_mode', 32);
            $table->string('transaction_id', 191);
            $table->string('proof_path', 500)->nullable();
            $table->string('proof_original_name', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'transaction_date']);
            $table->index(['company_id', 'fuel_station_id']);
            $table->index(['company_id', 'transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_fuel_bank_deposits');
    }
};
