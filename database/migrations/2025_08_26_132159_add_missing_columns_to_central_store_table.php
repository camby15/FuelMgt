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
        Schema::table('central_store', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('item_category');
            $table->string('unit', 50)->default('pcs')->after('brand');
            $table->text('description')->nullable()->after('unit');
            $table->json('images')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('central_store', function (Blueprint $table) {
            $table->dropColumn(['brand', 'unit', 'description', 'images']);
        });
    }
};
