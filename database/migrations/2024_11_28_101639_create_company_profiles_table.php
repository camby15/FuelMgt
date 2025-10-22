<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');              // Links to users
            $table->string('company_name');                                                       // Company name
            $table->string('company_email')->unique();                                            // Email for the company
            $table->string('company_phone');                                                      // Phone number for the company
            $table->string('primary_email');                                                      // Primary contact email
            $table->string('pin_code');                                                           // Encrypted PIN for the company
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
}