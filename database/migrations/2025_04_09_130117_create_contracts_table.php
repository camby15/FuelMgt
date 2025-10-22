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
        // Main contracts table
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('customer_name')->nullable();
            $table->string('email')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', [
                'draft',
                'active',
                'pending_signature',
                'signed',
                'renewed',
                'expired',
                'cancelled'
            ])->default('draft');
            $table->string('file_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('sent_for_signature_at')->nullable();
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better query performance
            $table->index('status');
            $table->index('start_date');
            $table->index('end_date');
            $table->index('customer_name');
            $table->index('email');
        });

        // Contract audit trail table
        Schema::create('contract_audit_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')
                  ->constrained('contracts')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('action');
            $table->text('details')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        // Contract signatures table
        Schema::create('contract_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')
                  ->constrained('contracts')
                  ->cascadeOnDelete();
            $table->string('signer_name');
            $table->string('signer_email');
            $table->string('signature_image_path')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->enum('status', [
                'pending',
                'signed',
                'rejected',
                'expired'
            ])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index('signer_email');
            $table->index('status');
        });

        // Contract attachments table
        Schema::create('contract_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')
                  ->constrained('contracts')
                  ->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->bigInteger('file_size');
            $table->foreignId('uploaded_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('file_type');
        });

        // Contract comments table
        Schema::create('contract_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')
                  ->constrained('contracts')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->text('comment');
            $table->timestamps();
            $table->softDeletes();
        });

        // Contract reminders table
        Schema::create('contract_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')
                  ->constrained('contracts')
                  ->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('reminder_date');
            $table->enum('reminder_type', [
                'expiration',
                'renewal',
                'payment',
                'custom'
            ]);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sent_at')->nullable();
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('reminder_date');
            $table->index(['reminder_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to avoid foreign key constraint issues
        Schema::dropIfExists('contract_reminders');
        Schema::dropIfExists('contract_comments');
        Schema::dropIfExists('contract_attachments');
        Schema::dropIfExists('contract_signatures');
        Schema::dropIfExists('contract_audit_trails');
        Schema::dropIfExists('contracts');
    }
};