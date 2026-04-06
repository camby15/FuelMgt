<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_fuel_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->cascadeOnDelete();
            $table->string('account_type', 32)->comment('bank, cash, mobile_money');
            $table->string('account_code', 191);
            $table->string('account_name', 255);
            $table->string('description', 500)->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_account_no', 191)->nullable();
            $table->string('bank_branch', 255)->nullable();
            $table->string('mobile_money_provider', 191)->nullable();
            $table->string('mobile_money_number', 191)->nullable();
            $table->text('notes')->nullable();
            $table->date('last_reconciled_at')->nullable();
            $table->string('status', 32)->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'account_code']);
            $table->index(['company_id', 'account_type']);
            $table->index(['company_id', 'status']);
        });

        Schema::create('cf_account_station', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_fuel_account_id');
            $table->unsignedBigInteger('fuel_station_id');
            $table->timestamps();

            $table->unique(['company_fuel_account_id', 'fuel_station_id'], 'cf_acc_station_pair');

            $table->foreign('company_fuel_account_id', 'cf_acc_station_acc_fk')
                ->references('id')
                ->on('company_fuel_accounts')
                ->cascadeOnDelete();

            $table->foreign('fuel_station_id', 'cf_acc_station_fs_fk')
                ->references('id')
                ->on('fuel_stations')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cf_account_station');
        Schema::dropIfExists('company_fuel_accounts');
    }
};
