<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmLeadsTable extends Migration
{
    public function up()
    {
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->enum('source', ['Website', 'Referral', 'Social Media', 'Direct', 'Other']);
            $table->text('notes')->nullable();
            $table->enum('status', ['New', 'Qualified', 'Unqualified', 'Converted'])->default('New');
            
            // Appointment details
            $table->date('appointment_date')->nullable();
            $table->time('appointment_time')->nullable();
            $table->enum('appointment_type', ['Call', 'Meeting', 'Video Conference'])->nullable();
            $table->text('appointment_notes')->nullable();

            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('crm_leads');
    }
}