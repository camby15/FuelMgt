<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Add user_id
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->string('deal_name');
            $table->decimal('deal_value', 10, 2);
            $table->string('stage');
            $table->string('sales_manager_id')->nullable();
            $table->date('expected_close_date')->nullable();
            $table->integer('probability')->nullable();
            $table->text('description')->nullable();
            $table->boolean('deleted')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('crm_sales');
    }
};
