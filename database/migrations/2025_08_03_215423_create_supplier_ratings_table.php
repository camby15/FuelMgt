<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('supplier_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('wh__suppliers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->decimal('rating', 3, 1); // Stores values like 4.5
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Adds deleted_at column
            
            // Composite unique index to prevent duplicate ratings
            $table->unique(['supplier_id', 'user_id', 'company_id']);

            // Additional indexes for performance
            $table->index(['company_id', 'supplier_id']);
            $table->index(['company_id', 'user_id']);
            $table->index(['deleted_at']); // Index for soft delete queries
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_ratings');
    }
};