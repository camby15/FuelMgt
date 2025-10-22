<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('crm_campaigns', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('name'); // Campaign Name
            $table->enum('type', ['email', 'social', 'ppc', 'content', 'referral', 'seo'])->default('email'); // Campaign Type
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('budget', 12, 2)->nullable(); // Budget with 2 decimal places
            $table->json('target_audience')->nullable(); // Store as JSON
            $table->enum('status', ['draft', 'active', 'paused', 'completed', 'cancelled'])->default('draft');

            // Foreign key references
            $table->unsignedBigInteger('company_id'); // Link to company_profiles table
            $table->unsignedBigInteger('created_by')->nullable(); // User who created the campaign
            $table->unsignedBigInteger('edited_by')->nullable(); // User who last edited
            $table->unsignedBigInteger('deleted_by')->nullable(); // User who deleted

            // Soft deletes and timestamps
            $table->boolean('deleted')->default(0); // Custom soft delete indicator
            $table->softDeletes(); // Adds `deleted_at` timestamp
            $table->timestamps(); // Adds `created_at` and `updated_at`

            // Foreign key constraints
            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('edited_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('crm_campaign_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id'); // Foreign key reference
            $table->json('assigned_users')->nullable(); // Key Performance Indicators
            $table->text('goals')->nullable(); // Campaign goals
            $table->json('notes')->nullable(); // Store multiple notes as JSON
            $table->json('documents')->nullable(); // Store document file paths as JSON
            $table->json('kpis')->nullable(); // Key Performance Indicators
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        
            // Foreign key constraints
            $table->foreign('campaign_id')->references('id')->on('crm_campaigns')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });        
    }

    public function down()
    {
        Schema::dropIfExists('crm_campaign_details');
        Schema::dropIfExists('crm_campaigns');
    }
};
