<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assignment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_assignment_id')->constrained('site_assignments')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Action details
            $table->string('action'); // created, updated, status_changed, issue_reported, issue_resolved, deleted
            $table->json('details')->nullable(); // JSON field to store changed data
            
            // Status tracking
            $table->string('status_before')->nullable();
            $table->string('status_after')->nullable();
            
            // Metadata
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assignment_histories');
    }
};
