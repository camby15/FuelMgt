<?php

require_once 'vendor/autoload.php';

use App\Models\Wh_Supplier;
use App\Models\SupplierRating;
use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing supplier loading...\n";
    
    // Test basic supplier loading
    $supplier = Wh_Supplier::first();
    if ($supplier) {
        echo "Supplier found: " . $supplier->company_name . "\n";
        
        // Test ratings relationship
        $ratings = $supplier->ratings()->with('user:id,fullname')->get();
        echo "Ratings count: " . $ratings->count() . "\n";
        
        if ($ratings->count() > 0) {
            $rating = $ratings->first();
            echo "First rating: " . $rating->rating . " stars\n";
            if ($rating->user) {
                echo "User: " . $rating->user->fullname . "\n";
            } else {
                echo "No user found for rating\n";
            }
        }
    } else {
        echo "No suppliers found\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
