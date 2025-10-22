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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            
            // Company relationship
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            
            // Basic Information (matching the form fields from workforce-fleet.blade.php)
            $table->string('full_name'); // matches form field 'full_name'
            $table->string('license_number')->unique(); // matches form field 'license_number'
            $table->enum('license_type', ['class-a', 'class-b', 'class-c', 'motorcycle']); // matches form dropdown
            $table->string('phone'); // matches form field 'phone'
            $table->integer('experience_years')->nullable(); // matches form field 'experience_years'
            $table->date('license_expiry'); // matches form field 'license_expiry'
            $table->string('emergency_contact')->nullable(); // matches form field 'emergency_contact'
            $table->enum('status', ['available', 'assigned', 'on-leave', 'inactive']); // matches form dropdown
            $table->text('notes')->nullable(); // matches form field 'notes'
            
            // Audit Fields
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['company_id', 'status']);
            $table->index('license_number');
            $table->index('license_type');
            $table->index('license_expiry');
            $table->index('phone');
            $table->index('experience_years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
