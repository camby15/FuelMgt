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
        Schema::create('tax_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->string('name'); // e.g., "Ghana Standard Tax", "Ghana Flat Rate Tax"
            $table->string('code')->unique(); // e.g., "GH_STANDARD", "GH_FLAT", "GH_EXEMPT"
            $table->enum('type', ['standard', 'flat_rate', 'exempt', 'custom'])->default('standard');
            $table->decimal('rate', 5, 2)->default(0.00); // Tax rate as percentage (e.g., 21.90 for 21.9%)
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->json('applicable_items')->nullable(); // JSON array of item categories/types that this tax applies to
            $table->json('exempt_items')->nullable(); // JSON array of item categories/types that are exempt
            $table->json('conditions')->nullable(); // Additional conditions for tax application
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['company_id', 'is_active']);
            $table->index(['company_id', 'type']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_configurations');
    }
};
