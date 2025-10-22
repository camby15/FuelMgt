<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\WareHouse\OutboundController;

try {
    echo "=== TESTING API RESPONSE ===\n\n";
    
    // Create a mock request
    $request = new Request();
    $request->merge(['page' => 1, 'per_page' => 10]);
    
    // Set up session
    session(['selected_company_id' => 1]);
    
    // Create controller instance and call method
    $controller = new OutboundController();
    $response = $controller->getApprovedRequisitions($request);
    
    // Get the response data
    $responseData = $response->getData(true);
    
    echo "API Response Status: " . ($responseData['success'] ? 'SUCCESS' : 'FAILED') . "\n";
    echo "Data Count: " . count($responseData['data']) . "\n\n";
    
    if (isset($responseData['data'][0])) {
        $firstItem = $responseData['data'][0];
        echo "=== FIRST ITEM DATA ===\n";
        echo "Requisition Number: " . ($firstItem['requisition_number'] ?? 'NULL') . "\n";
        echo "Title: " . ($firstItem['title'] ?? 'NULL') . "\n";
        echo "Requested Date: " . ($firstItem['requested_date'] ?? 'NULL') . "\n";
        echo "Total Amount: " . ($firstItem['total_amount'] ?? 'NULL') . "\n";
        
        echo "\n--- REQUESTOR DATA ---\n";
        if (isset($firstItem['requestor'])) {
            echo "Requestor ID: " . ($firstItem['requestor']['id'] ?? 'NULL') . "\n";
            echo "Requestor Name: " . ($firstItem['requestor']['name'] ?? 'NULL') . "\n";
            if (isset($firstItem['requestor']['personalInfo'])) {
                echo "PersonalInfo First Name: " . ($firstItem['requestor']['personalInfo']['first_name'] ?? 'NULL') . "\n";
                echo "PersonalInfo Last Name: " . ($firstItem['requestor']['personalInfo']['last_name'] ?? 'NULL') . "\n";
            }
        } else {
            echo "Requestor data: NOT SET\n";
        }
        
        echo "\n--- DEPARTMENT DATA ---\n";
        if (isset($firstItem['departmentCategory'])) {
            echo "Department ID: " . ($firstItem['departmentCategory']['id'] ?? 'NULL') . "\n";
            echo "Department Name: " . ($firstItem['departmentCategory']['name'] ?? 'NULL') . "\n";
        } else {
            echo "Department data: NOT SET\n";
        }
        
        echo "\n--- ITEMS DATA ---\n";
        if (isset($firstItem['items']) && is_array($firstItem['items'])) {
            echo "Items count: " . count($firstItem['items']) . "\n";
            foreach ($firstItem['items'] as $index => $item) {
                echo "  Item " . ($index + 1) . ": " . ($item['item_name'] ?? 'Unknown') . " - Qty: " . ($item['quantity'] ?? 0) . " - Price: " . ($item['unit_price'] ?? 0) . "\n";
            }
        } else {
            echo "Items: NULL or not array\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>