<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wh__suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Company Information
            $table->string('company_name');
            $table->string('business_type');
            $table->string('tin')->unique();
            $table->string('vat_number')->nullable();
            $table->string('ssnit_number')->nullable();
            $table->integer('year_established')->nullable();
            $table->string('registration_number')->nullable();
            $table->text('company_description')->nullable();
            $table->string('business_sector');
            $table->string('company_size');
            
            // Contact Information
            $table->string('primary_contact');
            $table->string('contact_position');
            $table->string('job_title')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('whatsapp_number')->nullable();
            $table->string('landline')->nullable();
            $table->string('website')->nullable();
            $table->string('social_media')->nullable();
            
            // Ghana Specific Details
            $table->string('gipc_registration')->nullable();
            $table->string('fdia_status')->nullable();
            $table->string('ghanapost_address')->nullable();
            $table->string('local_council')->nullable();
            
            // Address Information
            $table->string('street_address');
            $table->string('area');
            $table->string('city');
            $table->string('region');
            $table->string('gps_address')->nullable();
            $table->string('postal_code')->nullable();
            
            // Additional Information
            $table->string('payment_terms');
            $table->string('currency');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wh_suppliers');
    }
};