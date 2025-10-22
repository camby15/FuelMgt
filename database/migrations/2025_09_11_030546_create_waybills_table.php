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
        Schema::create('waybills', function (Blueprint $table) {
            $table->id();
            $table->string('waybill_number')->unique();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('outbound_order_id')->nullable()->constrained('outbound_orders')->onDelete('set null');
            $table->foreignId('requisition_id')->nullable()->constrained('requisitions')->onDelete('set null');
            
            // Shipment Information
            $table->string('shipment_type')->default('internal'); // internal, external, customer
            $table->decimal('total_weight', 10, 2)->nullable();
            $table->integer('total_packages')->default(1);
            $table->decimal('total_value', 15, 2)->default(0);
            
            // Origin Information
            $table->string('origin_name')->nullable();
            $table->text('origin_address')->nullable();
            $table->string('origin_contact')->nullable();
            $table->string('origin_phone')->nullable();
            
            // Destination Information
            $table->string('destination_name');
            $table->text('destination_address');
            $table->string('destination_contact')->nullable();
            $table->string('destination_phone')->nullable();
            
            // Items and Packaging
            $table->json('items'); // Items being shipped
            $table->json('packages')->nullable(); // Package details
            
            // Transportation Details
            $table->string('transport_mode')->nullable(); // vehicle, courier, pickup
            $table->string('vehicle_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('carrier_company')->nullable();
            $table->string('tracking_number')->nullable();
            
            // Status and Timeline
            $table->enum('status', ['pending', 'in_transit', 'out_for_delivery', 'delivered', 'returned', 'cancelled'])->default('pending');
            $table->timestamp('dispatch_date')->nullable();
            $table->timestamp('expected_delivery_date')->nullable();
            $table->timestamp('actual_delivery_date')->nullable();
            
            // Delivery Confirmation
            $table->string('delivered_to')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->string('proof_of_delivery')->nullable(); // File path for signature/photo
            $table->foreignId('delivered_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Special Instructions and Notes
            $table->text('special_instructions')->nullable();
            $table->text('handling_instructions')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('requires_signature')->default(false);
            $table->boolean('fragile')->default(false);
            $table->boolean('urgent')->default(false);
            
            // Financial
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('insurance_value', 15, 2)->default(0);
            
            // Audit Trail
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['company_id', 'status']);
            $table->index(['outbound_order_id']);
            $table->index(['requisition_id']);
            $table->index(['status']);
            $table->index(['dispatch_date']);
            $table->index(['tracking_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waybills');
    }
};