<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('fuel_stations') || Schema::hasColumn('fuel_stations', 'products')) {
            return;
        }

        Schema::table('fuel_stations', function (Blueprint $table) {
            $table->json('products')->nullable()->after('product');
        });

        DB::table('fuel_stations')
            ->select(['id', 'product'])
            ->chunkById(200, function ($stations) {
                foreach ($stations as $station) {
                    $product = $station->product;
                    $products = $product ? [strtoupper($product)] : [];

                    DB::table('fuel_stations')
                        ->where('id', $station->id)
                        ->update(['products' => json_encode($products)]);
                }
            });
    }

    public function down(): void
    {
        if (!Schema::hasTable('fuel_stations') || !Schema::hasColumn('fuel_stations', 'products')) {
            return;
        }

        DB::table('fuel_stations')
            ->select(['id', 'products'])
            ->chunkById(200, function ($stations) {
                foreach ($stations as $station) {
                    $products = json_decode($station->products ?? '[]', true) ?: [];
                    $primaryProduct = $products[0] ?? null;

                    DB::table('fuel_stations')
                        ->where('id', $station->id)
                        ->update(['product' => $primaryProduct]);
                }
            });

        Schema::table('fuel_stations', function (Blueprint $table) {
            $table->dropColumn('products');
        });
    }
};
