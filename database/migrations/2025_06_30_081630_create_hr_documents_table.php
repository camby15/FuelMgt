<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_employment_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('resume')->nullable();
            $table->string('cover_letter')->nullable();
            $table->string('educational_certificate')->nullable();
            $table->json('other_documents')->nullable();
            $table->text('document_notes')->nullable();
            $table->boolean('documents_complete')->default(false);
            $table->boolean('documents_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_employment_documents');
    }
};