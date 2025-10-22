<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the status enum to include 'partially_approved'
        DB::statement("ALTER TABLE requisitions MODIFY COLUMN status ENUM('draft', 'created', 'pending', 'partially_approved', 'approved', 'rejected', 'issued') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status enum to remove 'partially_approved'
        DB::statement("ALTER TABLE requisitions MODIFY COLUMN status ENUM('draft', 'created', 'pending', 'approved', 'rejected', 'issued') DEFAULT 'draft'");
    }
};
