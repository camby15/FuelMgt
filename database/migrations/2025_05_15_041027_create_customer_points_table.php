<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_points', function (Blueprint $table) {
            $table->id();
            // Foreign key to company_profiles table
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            // Foreign key to customers table
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            // Foreign key to loyalty_programs table
            $table->foreignId('loyalty_program_id')->constrained('loyalty_programs')->onDelete('cascade');
            // Points fields with default values
            $table->integer('points_balance')->default(0);
            $table->integer('points_earned')->default(0);
            $table->integer('points_redeemed')->default(0);
            // Nullable date fields
            $table->date('last_activity')->nullable();
            $table->date('expires_at')->nullable();
            // Soft deletes for logical deletion
            $table->softDeletes();
            // Timestamps for created_at and updated_at
            $table->timestamps();
            // Composite index for efficient queries
            $table->index(['company_id', 'customer_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_points');
    }
};