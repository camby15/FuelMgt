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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description_agenda_notes')->nullable();
            $table->enum('type', ['task', 'call', 'meeting', 'event']);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
                
            $table->string('call_event_type')->nullable();  
            $table->longText('contact')->nullable();   
            $table->longText('participants')->nullable();
            $table->longText('related_to')->nullable(); // Changed from foreignId to string
            $table->string('related_to_type')->nullable(); // To store the type of related model (Customer, Lead, etc.)
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('duration')->nullable(); 
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->json('additional_data')->nullable(); // For storing type-specific data
            $table->boolean('all_day')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
