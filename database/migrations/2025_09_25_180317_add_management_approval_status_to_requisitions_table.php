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
            // Add management approval status tracking
            $table->enum('management_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->text('management_notes')->nullable()->after('management_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn(['management_status', 'management_notes']);
        });
    }
};
