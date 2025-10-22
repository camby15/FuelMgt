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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            
            // Relationship
            $table->foreignId('company_id')
                  ->constrained('company_profiles')
                  ->onDelete('cascade');
            
            // Company Profile Section
            $table->string('name')->nullable(); // Primary contact full name
            $table->string('company_name')->nullable();
            $table->string('email')->nullable(); // Corporate email
            $table->string('phone', 20)->nullable(); // Corporate phone
            $table->text('address')->nullable(); // Corporate address

            // Company Details
            $table->date('commencement_date')->nullable();
            $table->string('sector')->nullable();
            $table->integer('number_of_employees')->nullable(); 
            $table->string('corporate_telephone')->nullable();
            $table->string('corporate_email')->nullable();
            $table->text('headquarters_address')->nullable();

            // Primary Contact
            $table->string('primary_contact_name')->nullable();
            $table->string('primary_contact_position')->nullable();
            $table->string('primary_contact_number')->nullable();
            $table->string('primary_contact_email')->nullable();
            $table->text('primary_contact_address')->nullable();

            // Customer Management
            $table->enum('customer_category', ['Standard', 'VIP', 'HVC'])->nullable();
            $table->decimal('value', 10, 2)->nullable()->default(0); // Customer value
            $table->enum('status', ['Active', 'Inactive', 'Pending', 'Suspended'])->default('Pending');
            $table->string('source_of_acquisition')->nullable();
            $table->string('change_type')->nullable();
            $table->string('assigned_branch')->nullable();
            $table->string('channel')->nullable();
            $table->string('company_group_code')->nullable();
            $table->string('mode_of_communication')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Unique constraint for email within a company
            $table->unique(['company_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
