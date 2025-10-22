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
            $table->unsignedBigInteger('management_approved_by')->nullable()->after('approved_at');
            $table->timestamp('management_approved_at')->nullable()->after('management_approved_by');
            $table->text('management_rejection_reason')->nullable()->after('management_approved_at');
            
            $table->foreign('management_approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropForeign(['management_approved_by']);
            $table->dropColumn(['management_approved_by', 'management_approved_at', 'management_rejection_reason']);
        });
    }
};
