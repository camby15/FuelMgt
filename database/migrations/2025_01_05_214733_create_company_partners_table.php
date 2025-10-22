<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPartnersTable extends Migration
{
    public function up()
    {
        Schema::create('company_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('status')->default(true);
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->date('partnership_date')->nullable();
            $table->timestamps();

            // Add composite unique constraints
            $table->unique(['user_id', 'company_name']);
            $table->unique(['user_id', 'email']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_partners');
    }
}
