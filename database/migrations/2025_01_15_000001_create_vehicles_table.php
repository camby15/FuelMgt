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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            
            // Company relationship
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            
            // Basic Information (matching the form fields from workforce-fleet.blade.php)
            $table->string('registration_number')->unique(); // matches form field 'registration_number'
            $table->string('make'); // matches form field 'make'
            $table->string('model'); // matches form field 'model'
            $table->enum('type', ['sedan', 'suv', 'truck', 'van', 'motorcycle']); // matches form dropdown
            $table->integer('year'); // matches form field 'year'
            $table->string('color')->nullable(); // matches form field 'color'
            $table->string('fuel_type')->nullable(); // matches form field 'fuel_type'
            $table->date('insurance_expiry'); // matches form field 'insurance_expiry'
            $table->integer('mileage')->nullable(); // matches form field 'mileage'
            $table->enum('status', ['available', 'in-use', 'maintenance', 'inactive']); // matches form dropdown
            $table->text('notes')->nullable(); // matches form field 'notes'
            
            // Driver assignment (optional)
            $table->foreignId('assigned_driver_id')->nullable()->constrained('drivers')->onDelete('set null');
            
            // Audit Fields
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['company_id', 'status']);
            $table->index('registration_number');
            $table->index('type');
            $table->index('year');
            $table->index('insurance_expiry');
            $table->index('assigned_driver_id');
            $table->index('make');
            $table->index('model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
