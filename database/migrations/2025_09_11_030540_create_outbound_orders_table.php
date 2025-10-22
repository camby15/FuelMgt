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
        Schema::create('outbound_orders', function (Blueprint $table) {
            $table->id();
            $table->string('outbound_number')->unique();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('requisition_id')->constrained('requisitions')->onDelete('cascade');
            
            // Order Details
            $table->string('department')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('users')->onDelete('set null');
            $table->json('items'); // Store items from requisition
            $table->decimal('total_value', 15, 2)->default(0);
            
            // Status and Processing
            $table->enum('status', ['pending', 'processing', 'picked', 'packed', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Processing Information
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('assigned_at')->nullable();
            $table->foreignId('picked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('picked_at')->nullable();
            $table->foreignId('packed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('packed_at')->nullable();
            $table->foreignId('shipped_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('shipped_at')->nullable();
            
            // Delivery Information
            $table->string('delivery_address')->nullable();
            $table->string('delivery_contact')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->timestamp('requested_delivery_date')->nullable();
            $table->timestamp('actual_delivery_date')->nullable();
            
            // Notes and Documentation
            $table->text('notes')->nullable();
            $table->text('special_instructions')->nullable();
            $table->json('attachments')->nullable();
            
            // Audit Trail
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['company_id', 'status']);
            $table->index(['requisition_id']);
            $table->index(['status', 'priority']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound_orders');
    }
};