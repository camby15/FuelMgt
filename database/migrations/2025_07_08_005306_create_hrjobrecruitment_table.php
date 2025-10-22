<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hrjobrecruitment', function (Blueprint $table) {
            $table->id();
              $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
              $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->string('token')->unique();
            $table->string('title');
            $table->string('department');
            $table->string('location');
            $table->string('type');
            $table->string('status');
            $table->date('posted_date');
            $table->integer('applications')->default(0);
           $table->mediumText('description');
            $table->mediumText('requirements');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hrjobrecruitment');
    }
};