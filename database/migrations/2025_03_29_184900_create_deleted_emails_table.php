<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deleted_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('sent_by_user_id')->constrained('users')->onDelete('cascade');
            $table->string('recipient');
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('deleted');
            $table->timestamp('deleted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deleted_emails');
    }
};
