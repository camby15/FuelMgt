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
        // Drop foreign keys that reference requisitions table
        Schema::table('outbound_orders', function (Blueprint $table) {
            $table->dropForeign(['requisition_id']);
        });

        Schema::table('waybills', function (Blueprint $table) {
            $table->dropForeign(['requisition_id']);
        });

        // Drop and recreate the requisitions table with all necessary columns
        Schema::dropIfExists('requisitions');

        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('title');
            $table->unsignedBigInteger('requester_id');
            $table->string('department')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'issued'])->default('draft');
            $table->text('notes')->nullable();
            $table->json('items')->nullable();
            $table->json('attachments')->nullable();
            $table->string('requisition_number')->unique();
            $table->string('reference_code')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        // Add back the foreign keys
        Schema::table('outbound_orders', function (Blueprint $table) {
            $table->foreign('requisition_id')->references('id')->on('requisitions')->onDelete('cascade');
        });

        Schema::table('waybills', function (Blueprint $table) {
            $table->foreign('requisition_id')->references('id')->on('requisitions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys before dropping the table
        Schema::table('outbound_orders', function (Blueprint $table) {
            $table->dropForeign(['requisition_id']);
        });

        Schema::table('waybills', function (Blueprint $table) {
            $table->dropForeign(['requisition_id']);
        });

        Schema::dropIfExists('requisitions');
    }
};
