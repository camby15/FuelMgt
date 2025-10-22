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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->string('head_name')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('color', 7)->default('#3b7ddd');
            $table->json('sub_categories')->nullable();
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('company_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('company_sub_users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('company_sub_users')->onDelete('set null');

            // Indexes
            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'name']);
            $table->index(['company_id', 'code']);
            $table->index('sort_order');
            $table->index('created_at');
            $table->index('updated_at');

            // Unique constraints
            $table->unique(['company_id', 'name', 'deleted_at']);
            $table->unique(['company_id', 'code', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
