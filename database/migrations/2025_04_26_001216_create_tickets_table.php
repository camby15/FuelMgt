<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ticket_id')->unique();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->string('customer');
            $table->enum('priority', ['high', 'medium', 'low']);
            $table->string('subject');
            $table->enum('category', ['technical', 'billing', 'feature', 'general']);
            $table->foreignId('agent_id')->nullable()->constrained('support_agents')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
