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
        // Use create() instead of table() since this is a new table
        Schema::create('crm_sales_category', function (Blueprint $table) {
            $table->id(); // This automatically creates an auto-incrementing primary key
            $table->string('name')->unique();
            $table->timestamps(); // Creates created_at and updated_at columns
        });

        $categories = [
            "Agriculture & Farming",
            "Information Technology",
            "Healthcare & Medical",
            "Education & Training",
            "Engineering",
            "Finance & Banking",
            "Manufacturing & Industrial",
            "Retail & E-commerce",
            "Construction & Real Estate",
            "Energy & Utilities",
            "Media & Entertainment",
            "Transportation & Logistics",
            "Consumer Goods & Services",
            "Hospitality & Tourism",
            "Telecommunications",
            "Mining & Natural Resources",
            "Professional Services",
            "Government & Public Sector",
            "Non-Profit & NGO"
        ];

        foreach ($categories as $category) {
            DB::table('crm_sales_category')->insert([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Simplified drop statement
        Schema::dropIfExists('crm_sales_category');
    }
};