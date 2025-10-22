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
        Schema::create('profile_menu_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('user_profiles')->onDelete('cascade');
            $table->string('menu_key')->comment('Unique identifier for the menu item');
            $table->string('menu_name');
            $table->string('menu_icon')->nullable();
            $table->string('menu_route')->nullable();
            $table->string('parent_menu')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['profile_id', 'menu_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_menu_access');
    }
};
