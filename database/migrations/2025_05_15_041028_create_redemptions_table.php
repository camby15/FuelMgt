<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('redemptions', function (Blueprint $table) {
            $table->id();
            // Foreign key to company_profiles table
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            // Foreign key to customers table
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            // Foreign key to rewards table (commented out until rewards table is created)
            // $table->foreignId('reward_id')->constrained('rewards')->onDelete('cascade');
            $table->unsignedBigInteger('reward_id')->nullable(); // Temporary placeholder
            // Foreign key to loyalty_programs table
            $table->foreignId('loyalty_program_id')->constrained('loyalty_programs')->onDelete('cascade');
            // Points used for redemption
            $table->integer('points_used');
            // Status with enum values
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            // Optional notes field
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('redemptions');
    }
};