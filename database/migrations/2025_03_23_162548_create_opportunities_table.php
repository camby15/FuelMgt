<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            
            // User and Company References
            $table->unsignedBigInteger('user_id')->comment('User who created the opportunity');
            $table->unsignedBigInteger('company_id')->comment('Company associated with the opportunity');
            
            // Opportunity Details
            $table->string('name')->comment('Name of the opportunity');
            $table->string('account')->comment('Account or client name');
            $table->enum('stage', [
                'Prospecting', 
                'Qualification', 
                'Proposal', 
                'Negotiation', 
                'Closed Won', 
                'Closed Lost'
            ])->comment('Current stage of the opportunity');
            
            // Financial Details
            $table->decimal('amount', 15, 2)->comment('Total opportunity amount');
            $table->decimal('expected_revenue', 15, 2)->nullable()->comment('Expected revenue from the opportunity');
            $table->date('close_date')->nullable()->comment('Expected or actual close date');
            $table->float('probability', 5, 2)->nullable()->comment('Probability of closing (0-100%)');
            
            // Status and Additional Information
            $table->boolean('status')->default(true)->comment('Active or inactive opportunity');
            $table->text('description')->nullable()->comment('Additional details about the opportunity');
            
            // Foreign Key Constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
            
            // Metadata
            $table->timestamps();
            $table->softDeletes()->comment('Allow soft deletion of opportunities');
            
            // Indexes for performance
            $table->index(['company_id', 'stage']);
            $table->index(['close_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opportunities');
    }
}