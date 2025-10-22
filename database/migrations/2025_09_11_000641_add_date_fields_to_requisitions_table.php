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
        Schema::table('requisitions', function (Blueprint $table) {
            // Add requisition_date and required_date fields
            $table->date('requisition_date')->nullable()->after('requisition_number');
            $table->date('required_date')->nullable()->after('requisition_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            // Drop the date fields
            $table->dropColumn(['requisition_date', 'required_date']);
        });
    }
};
