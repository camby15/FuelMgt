<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use App\Models\Waybill;
use App\Models\OutboundOrder;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class WaybillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Check authentication and company session
     */
    private function checkAuth()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!Session::has('selected_company_id')) {
            return response()->json(['success' => false, 'message' => 'No company selected'], 400);
        }

        return null;
    }

    /**
     * Get all waybills for the current company
     */
    public function getWaybills(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            $query = Waybill::with(['requisition.requestor.personalInfo', 'requisition.departmentCategory'])
                ->where('company_id', $companyId);

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('waybill_number', 'like', "%{$search}%")
                      ->orWhere('tracking_number', 'like', "%{$search}%")
                      ->orWhere('destination_name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $waybills = $query->orderBy('created_at', 'desc')->paginate(15);

            $formattedWaybills = $waybills->map(function ($waybill) {
                $customerName = 'Unknown';
                $itemsCount = 0;
                $origin = 'Unknown';

                // Get data directly from waybill or its related requisition
                if ($waybill->requisition) {
                    $requisition = $waybill->requisition;
                    
                    // Get customer name from requisition
                    if ($requisition->requestor && $requisition->requestor->personalInfo) {
                        $firstName = $requisition->requestor->personalInfo->first_name ?? '';
                        $lastName = $requisition->requestor->personalInfo->last_name ?? '';
                        $customerName = trim($firstName . ' ' . $lastName) ?: 'Unknown';
                    }

                    // Get items count from waybill items or requisition items
                    if ($waybill->items && is_array($waybill->items)) {
                        $itemsCount = count($waybill->items);
                    } elseif ($requisition->items && is_array($requisition->items)) {
                        $itemsCount = count($requisition->items);
                    }

                    // Get origin from department
                    if ($requisition->departmentCategory) {
                        $origin = $requisition->departmentCategory->name;
                    } elseif ($requisition->department) {
                        $origin = $requisition->department;
                    }
                }
                
                // Fallback to waybill data
                if ($customerName === 'Unknown' && $waybill->destination_name) {
                    $customerName = $waybill->destination_name;
                }
                if ($origin === 'Unknown' && $waybill->origin_name) {
                    $origin = $waybill->origin_name;
                }

                return [
                    'id' => $waybill->id,
                    'waybill_number' => $waybill->waybill_number,
                    'tracking_number' => $waybill->tracking_number,
                    'customer_name' => $customerName,
                    'items_count' => $itemsCount . ' items',
                    'origin' => $origin,
                    'destination' => $waybill->destination_name ?? 'Unknown',
                    'status' => $waybill->status,
                    'created_at' => $waybill->created_at->format('M d, Y'),
                    'expected_delivery_date' => $waybill->expected_delivery_date ? $waybill->expected_delivery_date->format('M d, Y') : 'N/A',
                    'total_value' => $waybill->total_value ?? 0,
                    'waybill_data' => $waybill->toArray()
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedWaybills,
                'pagination' => [
                    'current_page' => $waybills->currentPage(),
                    'last_page' => $waybills->lastPage(),
                    'total' => $waybills->total(),
                    'per_page' => $waybills->perPage(),
                    'from' => $waybills->firstItem(),
                    'to' => $waybills->lastItem(),
                    'has_more_pages' => $waybills->hasMorePages()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching waybills: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching waybills'
            ], 500);
        }
    }

    /**
     * Create a new waybill
     */
    public function createWaybill(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            $validatedData = $request->validate([
                'outbound_order_id' => 'required|string',
                'waybill_number' => 'required|string|unique:waybills,waybill_number',
                'tracking_number' => 'required|string|unique:waybills,tracking_number',
                'carrier_id' => 'required|string',
                'estimated_delivery_date' => 'required|date',
                'status' => 'required|string|in:pending,in_transit,delivered,delayed'
            ]);

            // Find the requisition by requisition number (outbound_order_id is actually requisition_number)
            $requisition = Requisition::where('company_id', $companyId)
                ->where('requisition_number', $request->outbound_order_id)
                ->where('status', 'issued')
                ->first();

            if (!$requisition) {
                return response()->json([
                    'success' => false,
                    'message' => 'Issued requisition not found'
                ], 404);
            }

            DB::beginTransaction();

            // Get carrier name from ID
            $carrierNames = [
                '1' => 'DHL Express',
                '2' => 'FedEx', 
                '3' => 'UPS',
                '4' => 'Ghana Post'
            ];
            $carrierName = $carrierNames[$validatedData['carrier_id']] ?? 'Unknown Carrier';

            // Calculate totals from requisition items and get proper item details
            $totalValue = 0;
            $totalWeight = 0;
            $totalPackages = 0;
            $waybillItems = [];

            if ($requisition->items && is_array($requisition->items)) {
                \Log::info('Requisition items for waybill creation:', $requisition->items);
                
                foreach ($requisition->items as $index => $item) {
                    \Log::info("Processing requisition item {$index}:", $item);
                    
                    $quantity = (float) ($item['quantity'] ?? 0);
                    $unitPrice = (float) ($item['unit_price'] ?? $item['price'] ?? 0);
                    $totalValue += $quantity * $unitPrice;
                    
                    // Get item details from Central Store
                    $centralStoreItem = null;
                    if (isset($item['item_id'])) {
                        \Log::info("Looking up Central Store item with ID: " . $item['item_id']);
                        $centralStoreItem = \App\Models\CentralStore::find($item['item_id']);
                        if ($centralStoreItem) {
                            \Log::info("Central Store item found:", $centralStoreItem->toArray());
                        } else {
                            \Log::warning("Central Store item not found for ID: " . $item['item_id']);
                        }
                    } else {
                        \Log::warning("No item_id found in requisition item {$index}");
                    }
                    
                    // Create waybill item with proper details using available Central Store fields
                    $waybillItem = [
                        'item_id' => $item['item_id'] ?? null,
                        'sku' => $centralStoreItem->sku ?? $item['sku'] ?? $item['item_code'] ?? 'N/A',
                        'description' => $centralStoreItem->item_name ?? $item['item_name'] ?? 'N/A',
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'brand' => $centralStoreItem->brand ?? 'N/A',
                        'unit' => $centralStoreItem->unit ?? 'EA',
                        'barcode' => $centralStoreItem->barcode ?? 'N/A',
                        'location' => $centralStoreItem->location ?? 'N/A',
                        'category' => $centralStoreItem->item_category ?? 'N/A'
                    ];
                    
                    \Log::info("Created waybill item {$index}:", $waybillItem);
                    $waybillItems[] = $waybillItem;
                    
                    // Don't calculate total weight since Central Store doesn't have weight data
                    // $totalWeight += $quantity * (float) ($centralStoreItem->weight ?? 1);
                    $totalPackages += 1;
                }
                
                \Log::info('Final waybill items:', $waybillItems);
            }

            // Create waybill
            $waybill = Waybill::create([
                'company_id' => $companyId,
                'requisition_id' => $requisition->id,
                'waybill_number' => $validatedData['waybill_number'],
                'tracking_number' => $validatedData['tracking_number'],
                'carrier_company' => $carrierName,
                'expected_delivery_date' => $validatedData['estimated_delivery_date'],
                'status' => $validatedData['status'],
                'total_weight' => 0, // Set to 0 since Central Store doesn't have weight data
                'total_packages' => $totalPackages,
                'total_value' => $totalValue,
                'origin_name' => $requisition->departmentCategory->name ?? 'Warehouse',
                'origin_address' => 'Warehouse',
                'origin_contact' => 'Warehouse Manager',
                'origin_phone' => 'N/A',
                'destination_name' => $requisition->requestor->personalInfo->first_name . ' ' . $requisition->requestor->personalInfo->last_name ?? 'Unknown',
                'destination_address' => 'Department Address', // Default
                'destination_contact' => 'Department Contact',
                'destination_phone' => 'N/A',
                'items' => $waybillItems,
                'packages' => [], // Empty for now
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Waybill created successfully',
                'waybill' => $waybill
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating waybill: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating waybill'
            ], 500);
        }
    }

    /**
     * Get waybill details
     */
    public function getWaybillDetails(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            $waybill = Waybill::with(['requisition.requestor.personalInfo', 'requisition.departmentCategory'])
                ->where('company_id', $companyId)
                ->findOrFail($id);

            // Enhance items with Central Store data if needed
            if ($waybill->items && is_array($waybill->items)) {
                \Log::info('Original waybill items:', $waybill->items);
                $enhancedItems = [];
                foreach ($waybill->items as $index => $item) {
                    \Log::info("Processing item {$index}:", $item);
                    
                    // If item already has proper structure, keep it
                    if (isset($item['sku']) && isset($item['description']) && $item['sku'] !== 'N/A') {
                        \Log::info("Item {$index} already has proper structure, keeping as is");
                        $enhancedItems[] = $item;
                    } else {
                        \Log::info("Item {$index} needs enhancement, item_id: " . ($item['item_id'] ?? 'null'));
                        
                        // Try to get data from Central Store
                        $centralStoreItem = null;
                        if (isset($item['item_id'])) {
                            $centralStoreItem = \App\Models\CentralStore::find($item['item_id']);
                            \Log::info("Central Store item found: " . ($centralStoreItem ? 'Yes' : 'No'));
                            if ($centralStoreItem) {
                                \Log::info("Central Store item data:", $centralStoreItem->toArray());
                            }
                        }
                        
                        $enhancedItem = [
                            'item_id' => $item['item_id'] ?? null,
                            'sku' => $centralStoreItem->sku ?? $item['sku'] ?? $item['item_code'] ?? 'N/A',
                            'description' => $centralStoreItem->item_name ?? $item['description'] ?? $item['item_name'] ?? 'N/A',
                            'quantity' => $item['quantity'] ?? 0,
                            'unit_price' => $item['unit_price'] ?? $item['price'] ?? 0,
                            'brand' => $centralStoreItem->brand ?? 'N/A',
                            'unit' => $centralStoreItem->unit ?? $item['unit'] ?? 'EA',
                            'barcode' => $centralStoreItem->barcode ?? 'N/A',
                            'location' => $centralStoreItem->location ?? 'N/A',
                            'category' => $centralStoreItem->item_category ?? 'N/A'
                        ];
                        \Log::info("Enhanced item {$index}:", $enhancedItem);
                        $enhancedItems[] = $enhancedItem;
                    }
                }
                $waybill->items = $enhancedItems;
                \Log::info('Final enhanced items:', $waybill->items);
            }

            return response()->json([
                'success' => true,
                'data' => $waybill
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching waybill details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Waybill not found'
            ], 404);
        }
    }

    /**
     * Update waybill status
     */
    public function updateWaybillStatus(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            $validatedData = $request->validate([
                'status' => 'required|string|in:pending,in_transit,out_for_delivery,delivered,returned,cancelled',
                'delivered_to' => 'nullable|string',
                'delivery_notes' => 'nullable|string'
            ]);

            $waybill = Waybill::where('company_id', $companyId)->findOrFail($id);

            $updateData = [
                'status' => $validatedData['status'],
                'updated_by' => Auth::id(),
            ];

            if ($validatedData['status'] === 'delivered') {
                $updateData['actual_delivery_date'] = now();
                $updateData['delivered_to'] = $validatedData['delivered_to'];
                $updateData['delivery_notes'] = $validatedData['delivery_notes'];
                $updateData['delivered_by'] = Auth::id();
            }

            $waybill->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Waybill status updated successfully',
                'waybill' => $waybill
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating waybill status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating waybill status'
            ], 500);
        }
    }

    /**
     * Update waybill
     */
    public function updateWaybill(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            $waybill = Waybill::where('company_id', $companyId)->findOrFail($id);

            // Validate the request
            $request->validate([
                'carrier_id' => 'required|integer',
                'status' => 'required|string|in:pending,in_transit,delivered,delayed,cancelled',
                'estimated_delivery_date' => 'required|date'
            ]);

            // Update waybill
            $waybill->update([
                'carrier_id' => $request->carrier_id,
                'status' => $request->status,
                'expected_delivery_date' => $request->estimated_delivery_date
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Waybill updated successfully',
                'waybill' => $waybill->fresh()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating waybill: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating waybill'
            ], 500);
        }
    }

    /**
     * Delete waybill
     */
    public function deleteWaybill(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            $waybill = Waybill::where('company_id', $companyId)->findOrFail($id);

            // Only allow deletion of pending waybills
            if ($waybill->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending waybills can be deleted'
                ], 400);
            }

            $waybill->delete();

            return response()->json([
                'success' => true,
                'message' => 'Waybill deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting waybill: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting waybill'
            ], 500);
        }
    }

    /**
     * Add remaining items to existing waybill
     */
    public function addItemsToWaybill(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            $waybill = Waybill::where('company_id', $companyId)->findOrFail($id);
            $items = $request->input('items', []);
            
            if (empty($items)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items provided'
                ], 400);
            }
            
            DB::beginTransaction();
            
            // Get current items
            $currentItems = $waybill->items ?: [];
            if (!is_array($currentItems)) {
                $currentItems = json_decode($currentItems, true) ?: [];
            }
            
            // Add new items to existing items
            $updatedItems = array_merge($currentItems, $items);
            
            // Update waybill with combined items
            $waybill->update([
                'items' => $updatedItems,
                'total_packages' => count($updatedItems),
                'updated_by' => Auth::id()
            ]);
            
            // Recalculate total value
            $totalValue = 0;
            foreach ($updatedItems as $item) {
                $quantity = (float) ($item['quantity'] ?? 0);
                $unitPrice = (float) ($item['unit_price'] ?? 0);
                $totalValue += $quantity * $unitPrice;
            }
            
            $waybill->update(['total_value' => $totalValue]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Items added to waybill successfully',
                'data' => $waybill->fresh()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding items to waybill: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add items to waybill: ' . $e->getMessage()
            ], 500);
        }
    }
}
