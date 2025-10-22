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
        // Check if table already exists to avoid conflicts
        if (!Schema::hasTable('central_store')) {
            Schema::create('central_store', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('supplier_id');
                $table->unsignedBigInteger('purchase_order_id');
                $table->string('item_name');
                $table->string('item_category');
                $table->decimal('unit_price', 10, 2);
                $table->integer('quantity');
                $table->decimal('total_price', 10, 2);
                $table->string('batch_number');
                $table->string('location')->default('Central Store');
                $table->enum('status', ['pending', 'completed'])->default('pending');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by');
                $table->timestamp('transfer_date');
                $table->timestamp('completed_date')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
                // Foreign keys for warehouse tables will be added when those tables exist
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

                $table->index(['company_id', 'status']);
                $table->index(['purchase_order_id', 'status']);
            });
        } else {
            // Table exists, ensure all required columns are present
            Schema::table('central_store', function (Blueprint $table) {
                // Check and add missing columns if they don't exist
                if (!Schema::hasColumn('central_store', 'company_id')) {
                    $table->unsignedBigInteger('company_id');
                }
                if (!Schema::hasColumn('central_store', 'supplier_id')) {
                    $table->unsignedBigInteger('supplier_id');
                }
                if (!Schema::hasColumn('central_store', 'purchase_order_id')) {
                    $table->unsignedBigInteger('purchase_order_id');
                }
                if (!Schema::hasColumn('central_store', 'item_name')) {
                    $table->string('item_name');
                }
                if (!Schema::hasColumn('central_store', 'item_category')) {
                    $table->string('item_category');
                }
                if (!Schema::hasColumn('central_store', 'unit_price')) {
                    $table->decimal('unit_price', 10, 2);
                }
                if (!Schema::hasColumn('central_store', 'quantity')) {
                    $table->integer('quantity');
                }
                if (!Schema::hasColumn('central_store', 'total_price')) {
                    $table->decimal('total_price', 10, 2);
                }
                if (!Schema::hasColumn('central_store', 'batch_number')) {
                    $table->string('batch_number');
                }
                if (!Schema::hasColumn('central_store', 'location')) {
                    $table->string('location')->default('Central Store');
                }
                if (!Schema::hasColumn('central_store', 'status')) {
                    $table->enum('status', ['pending', 'completed'])->default('pending');
                }
                if (!Schema::hasColumn('central_store', 'notes')) {
                    $table->text('notes')->nullable();
                }
                if (!Schema::hasColumn('central_store', 'created_by')) {
                    $table->unsignedBigInteger('created_by');
                }
                if (!Schema::hasColumn('central_store', 'transfer_date')) {
                    $table->timestamp('transfer_date');
                }
                if (!Schema::hasColumn('central_store', 'completed_date')) {
                    $table->timestamp('completed_date')->nullable();
                }
                if (!Schema::hasColumn('central_store', 'deleted_at')) {
                    $table->softDeletes();
                }
                
                // Add foreign keys if they don't exist (check if table has the constraints)
                try {
                    // Only add foreign keys if columns exist and constraints don't exist
                    if (Schema::hasColumn('central_store', 'company_id')) {
                        $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
                    }
                } catch (\Exception $e) {
                    // Foreign key might already exist, ignore
                }
                
                // Skip warehouse table foreign keys - will be added when those tables exist
                
                try {
                    if (Schema::hasColumn('central_store', 'created_by')) {
                        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                    }
                } catch (\Exception $e) {
                    // Foreign key might already exist, ignore
                }

                // Add indexes if they don't exist
                try {
                    $table->index(['company_id', 'status']);
                } catch (\Exception $e) {
                    // Index might already exist, ignore
                }
                
                try {
                    $table->index(['purchase_order_id', 'status']);
                } catch (\Exception $e) {
                    // Index might already exist, ignore
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_store');
    }
};
