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
        Schema::create('map_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');

            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();

            // Coordinates
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);

            // Status/Severity
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium');

            // Optional linking
            $table->unsignedBigInteger('home_connection_customer_id')->nullable();
            $table->foreign('home_connection_customer_id')->references('id')->on('home_connection_customers')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status', 'severity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_issues');
    }
};


