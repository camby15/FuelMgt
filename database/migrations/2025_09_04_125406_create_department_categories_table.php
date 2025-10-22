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
        Schema::create('department_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('head_of_department_id')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('color', 7)->default('#3b7ddd');
            $table->json('sub_departments')->nullable(); // Store sub departments as JSON
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes(); // For soft deletion

            // Foreign key constraints
            $table->foreign('company_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('head_of_department_id')->references('id')->on('company_sub_users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('company_sub_users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('company_sub_users')->onDelete('set null');

            // Indexes for better performance
            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'name']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_categories');
    }
};