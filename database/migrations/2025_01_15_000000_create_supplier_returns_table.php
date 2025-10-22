<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if table already exists to avoid conflicts
        if (!Schema::hasTable('supplier_returns')) {
            Schema::create('supplier_returns', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('company_profiles');
                $table->foreignId('user_id')->constrained();
                            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('purchase_order_id');
                $table->string('return_number')->unique();
                $table->date('return_date');
                $table->string('return_reason');
                $table->text('return_description');
                $table->json('return_items'); // {item_id, quantity, reason, etc.}
                $table->decimal('total_value', 10, 2)->default(0);
                $table->string('status')->default('pending'); // pending, approved, processed, rejected
                $table->timestamp('processed_at')->nullable();
                $table->foreignId('processed_by')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            // Table exists, ensure all required columns are present
            Schema::table('supplier_returns', function (Blueprint $table) {
                // Check and add missing columns if they don't exist
                if (!Schema::hasColumn('supplier_returns', 'company_id')) {
                    $table->foreignId('company_id')->constrained('company_profiles');
                }
                if (!Schema::hasColumn('supplier_returns', 'user_id')) {
                    $table->foreignId('user_id')->constrained();
                }
                if (!Schema::hasColumn('supplier_returns', 'supplier_id')) {
                    $table->unsignedBigInteger('supplier_id')->nullable();
                }
                if (!Schema::hasColumn('supplier_returns', 'purchase_order_id')) {
                    $table->unsignedBigInteger('purchase_order_id');
                }
                if (!Schema::hasColumn('supplier_returns', 'return_number')) {
                    $table->string('return_number')->unique();
                }
                if (!Schema::hasColumn('supplier_returns', 'return_date')) {
                    $table->date('return_date');
                }
                if (!Schema::hasColumn('supplier_returns', 'return_reason')) {
                    $table->string('return_reason');
                }
                if (!Schema::hasColumn('supplier_returns', 'return_description')) {
                    $table->text('return_description');
                }
                if (!Schema::hasColumn('supplier_returns', 'return_items')) {
                    $table->json('return_items'); // {item_id, quantity, reason, etc.}
                }
                if (!Schema::hasColumn('supplier_returns', 'total_value')) {
                    $table->decimal('total_value', 10, 2)->default(0);
                }
                if (!Schema::hasColumn('supplier_returns', 'status')) {
                    $table->string('status')->default('pending'); // pending, approved, processed, rejected
                }
                if (!Schema::hasColumn('supplier_returns', 'processed_at')) {
                    $table->timestamp('processed_at')->nullable();
                }
                if (!Schema::hasColumn('supplier_returns', 'processed_by')) {
                    $table->foreignId('processed_by')->nullable()->constrained('users');
                }
                if (!Schema::hasColumn('supplier_returns', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('supplier_returns');
    }
};
