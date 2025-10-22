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
        Schema::create('documentation_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('documentation_folders')->onDelete('cascade');
            $table->string('access_level')->default('private');
            $table->unsignedBigInteger('created_by');
            $table->string('created_by_type')->default('user');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['company_id', 'name']);
            $table->index(['company_id', 'parent_id']);
            $table->index(['created_by', 'created_by_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_folders');
    }
};