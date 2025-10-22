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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            
            // Company relationship
            $table->foreignId('company_id')->constrained('company_profiles')->onDelete('cascade');
            
            // Basic Information (matching the form fields)
            $table->string('full_name'); // matches form field 'full_name'
            $table->string('employee_id'); // matches form field 'employee_id'
            $table->string('position'); // matches form field 'position'
            $table->foreignId('department_id')->nullable()->constrained('department_categories')->onDelete('set null'); // references department_categories table
            $table->string('phone'); // matches form field 'phone'
            $table->string('email'); // matches form field 'email'
            $table->date('hire_date')->nullable(); // matches form field 'hire_date'
            $table->enum('status', ['active', 'inactive', 'on-leave']); // matches form dropdown
            $table->text('notes')->nullable(); // matches form field 'notes'
            
            // Audit Fields
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['company_id', 'status']);
            $table->index('employee_id');
            $table->index('email');
            $table->index('department_id');
            $table->index('position');
            $table->index('hire_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};