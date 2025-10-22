<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use App\Models\Wh_PurchaseOrder;
use App\Models\POReceiving;
use App\Models\SupplierReturn;
use App\Models\Wh_Supplier;
use App\Jobs\SendReturnNotificationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class POReceivingController extends Controller
{


    /**
     * Get pending POs for receiving
     */
    public function getPendingPOs(Request $request)
    {
        $companyId = Session::get('selected_company_id');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        
        // Debug: Log the company ID and check all POs
        \Log::info('Company ID from session: ' . $companyId);
        
        $query = Wh_PurchaseOrder::where('company_id', $companyId)
            ->whereIn('status', ['pending'])
            ->with(['supplier']);
            
        // Add search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', '%' . $search . '%')
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('company_name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $totalPOs = $query->count();
        $pendingPOs = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(function($po) {
                // Debug: Check if supplier exists
                if (!$po->supplier) {
                    \Log::error('PO ' . $po->id . ' has no supplier relationship');
                    return null;
                }
                
                // Calculate total items and value
                $totalItems = collect($po->items)->sum('quantity');
                $totalValue = collect($po->items)->sum(function($item) {
                    return ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
                });
                
                return [
                    'id' => $po->id,
                    'po_number' => $po->po_number,
                    'supplier' => $po->supplier->company_name,
                    'supplier_id' => $po->supplier->id,
                    'order_date' => $po->order_date,
                    'expected_date' => $po->expected_date,
                    'total_items' => $totalItems,
                    'total_value' => $totalValue,
                    'status' => $po->status,
                    'can_receive' => true
                ];
            })
            ->filter(fn($po) => $po !== null);
            
        \Log::info('Final pending POs count: ' . $pendingPOs->count());
        
        return response()->json([
            'success' => true,
            'pendingPOs' => $pendingPOs,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalPOs,
                'last_page' => ceil($totalPOs / $perPage),
                'from' => (($page - 1) * $perPage) + 1,
                'to' => min($page * $perPage, $totalPOs)
            ]
        ]);
    }

    /**
     * Get PO items for receiving
     */
    public function getPOItems($poId)
    {
        $companyId = Session::get('selected_company_id');
        
        $po = Wh_PurchaseOrder::where('company_id', $companyId)
            ->where('id', $poId)
            ->with(['supplier'])
            ->first();
            
        // Debug: Log the PO data
        \Log::info('PO found:', [
            'id' => $po->id,
            'po_number' => $po->po_number,
            'items_count' => count($po->items ?? []),
            'items' => $po->items,
            'items_type' => gettype($po->items),
            'items_json' => json_encode($po->items)
        ]);
            
        if (!$po) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase order not found'
            ], 404);
        }
        
                 // Calculate receiving and return quantities for each item
         $itemsWithReceiving = collect($po->items ?? [])->map(function($item) use ($poId) {
             \Log::info('Processing item:', $item);
             
             // Ensure item has required fields
             if (!isset($item['name']) || !isset($item['quantity'])) {
                 \Log::error('Invalid item structure:', $item);
                 return null;
             }
             
             $itemId = $item['id'] ?? Str::slug($item['name']);
             $orderedQty = $item['quantity'] ?? 0;
             
             // Get total received quantity
             $totalReceived = $this->getTotalReceivedQuantity($poId, $itemId);
             
             // Get total returned quantity
             $totalReturned = $this->getTotalReturnedQuantityForValidation($poId, $itemId);
             
             // Calculate remaining quantity to receive (ordered - received)
             $remainingQty = $orderedQty - $totalReceived;
             
             // Calculate available quantity for return (received - already returned)
             $availableForReturn = $totalReceived - $totalReturned;
             
             // Debug logging
             \Log::info("Item: {$item['name']}, Ordered: {$orderedQty}, Received: {$totalReceived}, Returned: {$totalReturned}, Remaining: {$remainingQty}, Available for Return: {$availableForReturn}");
             
             $processedItem = [
                 'id' => $itemId,
                 'name' => $item['name'],
                 'quantity' => $orderedQty,  // Frontend expects 'quantity'
                 'previously_received' => $totalReceived,  // Frontend expects 'previously_received'
                 'unit' => $item['unit'] ?? 'pcs',
                 'unit_price' => $item['unit_price'] ?? 0,
                 'description' => $item['description'] ?? '',
                 'location' => $item['location'] ?? 'Main Warehouse'
             ];
             
             \Log::info('Processed item:', $processedItem);
             
             return $processedItem;
         })->filter(fn($item) => $item !== null);
        
        return response()->json([
            'success' => true,
            'po' => [
                'id' => $po->id,
                'po_number' => $po->po_number,
                'supplier' => $po->supplier->company_name,
                'supplier_id' => $po->supplier->id,
                'order_date' => $po->order_date,
                'items' => $itemsWithReceiving,
                'status' => $po->status
            ]
        ]);
    }

    /**
     * Store a new receiving
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:wh__purchase_orders,id',
            'receiving_date' => 'required|date',
            'delivery_note' => 'nullable|string',
            'vehicle_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.item_id' => 'required',
            'items.*.received_qty' => 'required|integer|min:0',
            'items.*.rejected_qty' => 'required|integer|min:0',
            'items.*.location' => 'required|string',
            'items.*.quality_check' => 'nullable|string'
        ]);

        $companyId = Session::get('selected_company_id');
        $po = Wh_PurchaseOrder::findOrFail($validated['purchase_order_id']);
        
        // Generate receiving number
        $receivingNumber = 'REC-' . strtoupper(Str::random(8));
        
        // Debug logging
        \Log::info('Received items data:', $validated['items']);
        \Log::info('PO items for matching:', $po->items);
        \Log::info('PO items structure:', [
            'po_items_type' => gettype($po->items),
            'po_items_count' => is_array($po->items) ? count($po->items) : 'not array',
            'first_po_item' => is_array($po->items) && count($po->items) > 0 ? $po->items[0] : 'no items'
        ]);
        
        // Prepare received items data
        $receivedItems = collect($validated['items'])->map(function($item) use ($po) {
            // Get item details from PO if not provided
            $itemName = $item['name'] ?? '';
            $unitPrice = $item['unit_price'] ?? 0;
            
            \Log::info('Processing item in store:', [
                'item_id' => $item['item_id'] ?? 'N/A',
                'item_name' => $itemName,
                'unit_price' => $unitPrice
            ]);
            
            // Always try to get name and unit price from PO items to ensure we have the correct data
            $matchFound = false;
            foreach ($po->items as $poItem) {
                \Log::info('Comparing with PO item:', [
                    'po_item_id' => $poItem['id'] ?? 'N/A',
                    'po_item_name' => $poItem['name'] ?? 'N/A',
                    'po_item_unit_price' => $poItem['unit_price'] ?? 'N/A',
                    'item_id_match' => ($poItem['id'] ?? '') == ($item['item_id'] ?? ''),
                    'name_match' => ($poItem['name'] ?? '') == ($item['name'] ?? '')
                ]);
                
                // Match by item_id first, then by name if item_id doesn't match
                if (($poItem['id'] ?? '') == ($item['item_id'] ?? '') || 
                    (empty($item['item_id']) && ($poItem['name'] ?? '') == ($item['name'] ?? ''))) {
                    
                    // Always use PO item name and unit price to ensure consistency
                    $itemName = $poItem['name'] ?? '';
                    $unitPrice = $poItem['unit_price'] ?? 0;
                    
                    \Log::info('Match found! Updated values:', [
                        'item_name' => $itemName,
                        'unit_price' => $unitPrice
                    ]);
                    $matchFound = true;
                    break;
                }
            }
            
            // If no match found, try to find by slug or partial name match
            if (!$matchFound) {
                foreach ($po->items as $poItem) {
                    $poItemSlug = Str::slug($poItem['name'] ?? '');
                    $itemSlug = Str::slug($item['item_id'] ?? '');
                    
                    if ($poItemSlug === $itemSlug || 
                        str_contains(strtolower($poItem['name'] ?? ''), strtolower($item['item_id'] ?? ''))) {
                        
                        $itemName = $poItem['name'] ?? '';
                        $unitPrice = $poItem['unit_price'] ?? 0;
                        
                        \Log::info('Match found by slug/partial! Updated values:', [
                            'item_name' => $itemName,
                            'unit_price' => $unitPrice,
                            'po_item_slug' => $poItemSlug,
                            'item_slug' => $itemSlug
                        ]);
                        $matchFound = true;
                        break;
                    }
                }
            }
            
            // If still no match found, log it
            if (!$matchFound) {
                \Log::warning('No matching PO item found for:', [
                    'item_id' => $item['item_id'] ?? 'N/A',
                    'item_name' => $item['name'] ?? 'N/A',
                    'po_items_count' => count($po->items),
                    'po_items' => $po->items
                ]);
            }
            
            return [
                'item_id' => $item['item_id'],
                'name' => $itemName ?: 'Unknown Item',
                'ordered_qty' => $item['ordered_qty'] ?? 0,
                'received_qty' => $item['received_qty'],
                'rejected_qty' => $item['rejected_qty'],
                'unit_price' => $unitPrice,
                'location' => $item['location'],
                'quality_check' => $item['quality_check'] ?? 'Good',
                'notes' => $item['notes'] ?? ''
            ];
        });
        
        // Calculate totals
        $totalReceived = $receivedItems->sum(function($item) {
            return $item['received_qty'] * $item['unit_price'];
        });
        
        $totalRejected = $receivedItems->sum(function($item) {
            return $item['rejected_qty'] * $item['unit_price'];
        });
        
        // Create the receiving
        $receiving = POReceiving::create([
            'company_id' => $companyId,
            'user_id' => Auth::id(),
            'purchase_order_id' => $po->id,
            'receiving_number' => $receivingNumber,
            'receiving_date' => $validated['receiving_date'],
            'delivery_note' => $validated['delivery_note'],
            'vehicle_number' => $validated['vehicle_number'],
            'notes' => $validated['notes'],
            'received_items' => $receivedItems,
            'total_received' => $totalReceived,
            'total_rejected' => $totalRejected,
            'status' => 'completed'
        ]);
        
        // Update PO status based on received quantities
        $this->updatePOStatusAfterReceiving($po);
        
        return response()->json([
            'success' => true,
            'message' => 'Items received successfully',
            'data' => $receiving
        ]);
    }
    
    /**
     * Handle return goods
     */
    public function returnGoods(Request $request)
    {
        $validated = $request->validate([
            'po_id' => 'required|exists:wh__purchase_orders,id',
            'return_reason' => 'required|string',
            'return_date' => 'required|date',
            'return_description' => 'required|string',
            'return_items' => 'required|array',
            'return_items.*.item_id' => 'required',
            'return_items.*.quantity' => 'required|integer|min:1',
            'return_items.*.reason' => 'required|string'
        ]);
        
        $companyId = Session::get('selected_company_id');
        $po = Wh_PurchaseOrder::where('company_id', $companyId)
            ->where('id', $validated['po_id'])
            ->first();
            
        if (!$po) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase order not found'
            ], 404);
        }
        
                 // Validate return quantities against available quantities
         $validationErrors = [];
         foreach ($validated['return_items'] as $index => $returnItem) {
             $itemId = $returnItem['item_id'];
             $returnQty = $returnItem['quantity'];
             
             // Find the item in PO items
             $poItem = collect($po->items)->first(function($item) use ($itemId) {
                 return ($item['id'] ?? Str::slug($item['name'])) == $itemId;
             });
             
             if (!$poItem) {
                 $validationErrors[] = "Item with ID {$itemId} not found in purchase order";
                 continue;
             }
             
             // Get total received quantity for this item
             $totalReceived = $this->getTotalReceivedQuantity($po->id, $itemId);
             
             // Get total already returned quantity for this item
             $totalReturned = $this->getTotalReturnedQuantityForValidation($po->id, $itemId);
             
             // Available quantity for return = received - already returned
             $availableForReturn = $totalReceived - $totalReturned;
             
             // Debug logging
             \Log::info("Return validation - Item: {$poItem['name']}, Return Qty: {$returnQty}, Total Received: {$totalReceived}, Total Returned: {$totalReturned}, Available: {$availableForReturn}");
             
             if ($returnQty > $availableForReturn) {
                 $validationErrors[] = "Cannot return {$returnQty} units of '{$poItem['name']}'. Only {$availableForReturn} units are available for return.";
             }
         }
        
        if (!empty($validationErrors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validationErrors
            ], 422);
        }
        
        // Generate return number
        $returnNumber = 'RET-' . strtoupper(Str::random(8));
        
        // Create supplier return record
        $supplierReturn = SupplierReturn::create([
            'company_id' => $companyId,
            'user_id' => Auth::id(),
            'purchase_order_id' => $po->id,
            'return_number' => $returnNumber,
            'return_date' => $validated['return_date'],
            'return_reason' => $validated['return_reason'],
            'return_description' => $validated['return_description'],
            'return_items' => $validated['return_items'],
            'status' => 'pending'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Return goods submitted successfully',
            'data' => $supplierReturn
        ]);
    }
    
    /**
     * Update PO status
     */
    public function updatePOStatus(Request $request)
    {
        $validated = $request->validate([
            'po_id' => 'required|exists:wh__purchase_orders,id',
            'new_status' => 'required|string|in:pending,processing,received,completed'
        ]);
        
        $companyId = Session::get('selected_company_id');
        $po = Wh_PurchaseOrder::where('company_id', $companyId)
            ->where('id', $validated['po_id'])
            ->first();
            
        if (!$po) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase order not found'
            ], 404);
        }
        
        $po->update(['status' => $validated['new_status']]);
        
        return response()->json([
            'success' => true,
            'message' => 'PO status updated successfully',
            'data' => $po
        ]);
    }
    
    /**
     * Get goods receipts
     */
    public function getGoodsReceipts(Request $request)
    {
        $companyId = Session::get('selected_company_id');
        
        // Debug the raw request content
        \Log::info('Goods Receipts - Raw request content: ' . $request->getContent());
        \Log::info('Goods Receipts - Request headers: ' . json_encode($request->headers->all()));
        \Log::info('Goods Receipts - Request all data: ' . json_encode($request->all()));
        \Log::info('Goods Receipts - Request input: ' . json_encode($request->input()));
        
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        $dateFilter = $request->input('date_filter');
        
        // Debug pagination parameters
        \Log::info('Goods Receipts - Pagination Debug - Page: ' . $page . ', PerPage: ' . $perPage);
        \Log::info('Goods Receipts - Page type: ' . gettype($page) . ', PerPage type: ' . gettype($perPage));
        
        // Debug logging
        \Log::info('Getting goods receipts for company: ' . $companyId);
        \Log::info('Request method: ' . $request->method());
        \Log::info('Auth check: ' . (Auth::check() ? 'true' : 'false'));
        
        // If no company ID in session, try to get from user or use default
        if (!$companyId) {
            if (Auth::check()) {
                $user = Auth::user();
                \Log::info('User type: ' . get_class($user));
                if ($user instanceof \App\Models\User) {
                    $companyId = $user->company_id ?? 1;
                } elseif ($user instanceof \App\Models\CompanySubUser) {
                    $companyId = $user->company_id ?? 1;
                } else {
                    $companyId = 1; // Default fallback
                }
            } else {
                $companyId = 1; // Default fallback
            }
            \Log::info('Using fallback company ID for goods receipts: ' . $companyId);
        }
        
        $query = POReceiving::where('company_id', $companyId)
            ->with(['purchaseOrder.supplier', 'user']);
            
        if ($dateFilter) {
            $query->whereDate('receiving_date', $dateFilter);
        }
        
        // Add search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('receiving_number', 'like', '%' . $search . '%')
                  ->orWhereHas('purchaseOrder', function($pq) use ($search) {
                      $pq->where('po_number', 'like', '%' . $search . '%')
                         ->orWhereHas('supplier', function($sq) use ($search) {
                             $sq->where('company_name', 'like', '%' . $search . '%');
                         });
                  });
            });
        }
        
        $totalReceipts = $query->count();
        $receipts = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        
        \Log::info('Found ' . $receipts->count() . ' goods receipts for company: ' . $companyId);
        
        $formattedReceipts = $receipts->map(function($receiving) {
            try {
                // Debug logging for each receiving
                \Log::info('Processing receiving ID: ' . $receiving->id);
                \Log::info('Purchase Order ID: ' . $receiving->purchase_order_id);
                \Log::info('Purchase Order exists: ' . ($receiving->purchaseOrder ? 'Yes' : 'No'));
                
                if ($receiving->purchaseOrder) {
                    \Log::info('Supplier exists: ' . ($receiving->purchaseOrder->supplier ? 'Yes' : 'No'));
                }
                
                // Debug received items
                \Log::info('Received items data: ' . json_encode($receiving->received_items));
                \Log::info('Total received field: ' . ($receiving->total_received ? $receiving->total_received : 'null'));
                
                // Calculate total value from received items
                $totalValue = 0;
                $calculationMethod = 'none';
                
                if (is_array($receiving->received_items) && !empty($receiving->received_items)) {
                    \Log::info('Calculating from received_items array');
                    foreach ($receiving->received_items as $item) {
                        $quantity = $item['received_qty'] ?? 0;
                        $unitPrice = $item['unit_price'] ?? 0;
                        $itemTotal = $quantity * $unitPrice;
                        $totalValue += $itemTotal;
                        
                        $itemName = $item['name'] ?? 'unknown';
                        \Log::info("Item: {$itemName}, Qty: {$quantity}, Price: {$unitPrice}, Total: {$itemTotal}");
                    }
                    $calculationMethod = 'received_items';
                }
                
                // If no total value calculated from items, try to get from PO items
                if ($totalValue == 0 && $receiving->purchaseOrder && is_array($receiving->purchaseOrder->items)) {
                    \Log::info('Calculating from PO items');
                    foreach ($receiving->purchaseOrder->items as $poItem) {
                        $itemId = $poItem['id'] ?? Str::slug($poItem['name']);
                        $orderedQty = $poItem['quantity'] ?? 0;
                        $unitPrice = $poItem['unit_price'] ?? 0;
                        
                        // Find corresponding received item
                        if (is_array($receiving->received_items)) {
                            foreach ($receiving->received_items as $receivedItem) {
                                $receivedItemName = $receivedItem['name'] ?? '';
                                $receivedItemId = $receivedItem['item_id'] ?? Str::slug($receivedItemName);
                                if ($receivedItemId == $itemId || $receivedItem['name'] == $poItem['name']) {
                                    $receivedQty = $receivedItem['received_qty'] ?? 0;
                                    $itemTotal = $receivedQty * $unitPrice;
                                    $totalValue += $itemTotal;
                                    
                                    \Log::info("PO Item: {$poItem['name']}, Received: {$receivedQty}, Price: {$unitPrice}, Total: {$itemTotal}");
                                    break;
                                }
                            }
                        }
                    }
                    if ($totalValue > 0) {
                        $calculationMethod = 'po_items';
                    }
                }
                
                // Try to calculate from PO total if still 0
                if ($totalValue == 0 && $receiving->purchaseOrder) {
                    \Log::info('Trying PO total calculation');
                    $poTotal = 0;
                    if (is_array($receiving->purchaseOrder->items)) {
                        foreach ($receiving->purchaseOrder->items as $poItem) {
                            $orderedQty = $poItem['quantity'] ?? 0;
                            $unitPrice = $poItem['unit_price'] ?? 0;
                            $poTotal += $orderedQty * $unitPrice;
                        }
                    }
                    
                    // If PO has total, use a percentage based on received items count
                    if ($poTotal > 0) {
                        $receivedItemsCount = is_array($receiving->received_items) ? count($receiving->received_items) : 0;
                        $totalItemsCount = is_array($receiving->purchaseOrder->items) ? count($receiving->purchaseOrder->items) : 0;
                        
                        if ($totalItemsCount > 0) {
                            $percentage = $receivedItemsCount / $totalItemsCount;
                            $totalValue = $poTotal * $percentage;
                            $calculationMethod = 'po_percentage';
                            
                            \Log::info("PO Total: {$poTotal}, Received Items: {$receivedItemsCount}, Total Items: {$totalItemsCount}, Percentage: {$percentage}, Calculated Total: {$totalValue}");
                        }
                    }
                }
                
                // Fallback to total_received if still 0
                if ($totalValue == 0) {
                    $totalValue = $receiving->total_received ?? 0;
                    $calculationMethod = 'total_received';
                    \Log::info("Using fallback total_received: {$totalValue}");
                }
                
                \Log::info("Final total value: {$totalValue}, Calculation method: {$calculationMethod}");
                
            return [
                'id' => $receiving->id,
                    'grn_number' => $receiving->receiving_number,
                    'po_number' => $receiving->purchaseOrder ? $receiving->purchaseOrder->po_number : 'Unknown PO',
                    'supplier' => $receiving->purchaseOrder && $receiving->purchaseOrder->supplier ? $receiving->purchaseOrder->supplier->company_name : 'Unknown Supplier',
                    'received_date' => $receiving->receiving_date,
                    'received_by' => $this->getUserName($receiving->user),
                    'received_items' => is_array($receiving->received_items) ? count($receiving->received_items) : 0,
                    'total_items' => $receiving->purchaseOrder && is_array($receiving->purchaseOrder->items) ? count($receiving->purchaseOrder->items) : 0,
                'total_value' => $totalValue,
                'status' => $receiving->status
            ];
            } catch (\Exception $e) {
                \Log::error('Error formatting receiving ' . $receiving->id . ': ' . $e->getMessage());
                return [
                    'id' => $receiving->id,
                    'grn_number' => $receiving->receiving_number,
                    'po_number' => 'Error',
                    'supplier' => 'Error',
                    'received_date' => $receiving->receiving_date,
                    'received_by' => 'Error',
                    'received_items' => 0,
                    'total_items' => 0,
                    'total_value' => 0,
                    'status' => $receiving->status
                ];
            }
        });
        
        return response()->json([
            'success' => true,
            'receipts' => $formattedReceipts,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalReceipts,
                'last_page' => ceil($totalReceipts / $perPage),
                'from' => (($page - 1) * $perPage) + 1,
                'to' => min($page * $perPage, $totalReceipts)
            ],
            'debug' => [
                'company_id' => $companyId,
                'total_found' => $receipts->count()
            ]
        ]);
    }
    
    /**
     * Get user name from user object
     */
    private function getUserName($user)
    {
        if (!$user) {
            return 'Unknown User';
        }
        
        // Try different possible name fields
        if ($user instanceof \App\Models\User) {
            return $user->fullname ?? $user->name ?? $user->first_name ?? 'Unknown User';
        } elseif ($user instanceof \App\Models\CompanySubUser) {
            return $user->fullname ?? $user->name ?? $user->first_name ?? 'Unknown User';
        } else {
            // For any other user type, try common name fields
            return $user->fullname ?? $user->name ?? $user->first_name ?? $user->username ?? 'Unknown User';
        }
    }

    /**
     * Get company information for printing
     */
    public function getCompanyInfo(Request $request)
    {
        // Get the authenticated user
        if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        $user = Auth::user();
        
        // Get company information from the user
        $companyInfo = [
            'name' => $user->fullname ?? 'Company Name Not Available',
            'address' => $user->personal_email ?? 'Address Not Available', // Using email as address for now
            'phone' => $user->phone_number ?? 'Phone Not Available'
        ];
        
        return response()->json([
            'success' => true,
            'company' => $companyInfo
        ]);
    }

    /**
     * Get return details for viewing
     */
    public function getReturnDetails(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $return = SupplierReturn::where('id', $id)
                ->where('company_id', $companyId)
                ->first();
            
            if (!$return) {
                return response()->json([
                    'success' => false,
                    'message' => 'Return not found'
                ], 404);
            }
            
            // Get supplier information
            $supplier = null;
            if ($return->supplier_id) {
                $supplier = Wh_Supplier::find($return->supplier_id);
            }
            
            // Parse return items
            $returnItems = [];
            if ($return->return_items) {
                // Check if it's already an array (Laravel JSON casting)
                if (is_array($return->return_items)) {
                    $returnItems = $return->return_items;
                } else {
                    $returnItems = json_decode($return->return_items, true);
                }
            }
            
            $formattedData = [
                'id' => $return->id,
                'return_number' => $return->return_number,
                'return_date' => $return->return_date,
                'return_reason' => $return->return_reason,
                'description' => $return->return_description,
                'status' => $return->status,
                'total_value' => $return->total_value,
                'supplier' => $supplier ? $supplier->company_name : 'Unknown Supplier',
                'po_number' => $return->purchase_order_id ? 'PO-' . $return->purchase_order_id : 'N/A',
                'return_items' => $returnItems
            ];
            
            return response()->json([
                'success' => true,
                'return' => $formattedData
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting return details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load return details'
            ], 500);
        }
    }

    /**
     * Process supplier return
     */
    public function processReturn(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            $return = SupplierReturn::where('id', $id)
                ->where('company_id', $companyId)
                ->where('status', 'pending')
                ->first();
            
            if (!$return) {
                return response()->json([
                    'success' => false,
                    'message' => 'Return not found or already processed'
                ], 404);
            }
            
            // Parse return items
            $returnItems = [];
            if ($return->return_items) {
                // Check if it's already an array (Laravel JSON casting)
                if (is_array($return->return_items)) {
                    $returnItems = $return->return_items;
                } else {
                    $returnItems = json_decode($return->return_items, true);
                }
            }
            
            \Log::info("Return items to process:", $returnItems);
            
            if (empty($returnItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items found in return'
                ], 400);
            }
            
            // Get supplier information for email
            $supplier = null;
            $supplierEmail = 'supplier@example.com'; // Default email
            if ($return->supplier_id) {
                $supplier = Wh_Supplier::find($return->supplier_id);
                if ($supplier) {
                    $supplierEmail = $supplier->email ?? $supplier->contact_email ?? 'supplier@example.com';
                }
            }
            
            // Process return (don't modify original receipt data)
            $itemsProcessed = 0;
            $inventoryReduced = 0;
            
            foreach ($returnItems as $item) {
                // Count items processed (but don't modify receipt data)
                $itemsProcessed++;
                $inventoryReduced += $item['quantity'];
                
                \Log::info("Processing return for item: {$item['item_name']}, Quantity: {$item['quantity']}");
            }
            
            // Update return status to processed
            $return->status = 'processed';
            $return->processed_at = now();
            $return->processed_by = Auth::id();
            $return->save();
            
            // Send email to supplier
            $this->sendReturnNotificationEmail($return, $supplier, $supplierEmail);
            
            return response()->json([
                'success' => true,
                'message' => 'Return processed successfully',
                'supplier_email' => $supplierEmail,
                'items_updated' => $itemsProcessed,
                'inventory_reduced' => $inventoryReduced
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error processing return: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process return'
            ], 500);
        }
    }

    /**
     * Send return notification email to supplier
     */
    private function sendReturnNotificationEmail($return, $supplier, $email)
    {
        try {
            // Get company information
            $company = Auth::user();
            $companyName = $company->fullname ?? 'Your Company';
            
            // Parse return items for email
            $returnItems = [];
            if ($return->return_items) {
                // Check if it's already an array (Laravel JSON casting)
                if (is_array($return->return_items)) {
                    $returnItems = $return->return_items;
                } else {
                    $returnItems = json_decode($return->return_items, true);
                }
            }
            
            $itemsList = '';
            $totalValue = 0;
            
            foreach ($returnItems as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $totalValue += $itemTotal;
                $itemsList .= "
                    <tr>
                        <td style='padding: 8px; border: 1px solid #ddd;'>{$item['item_name']}</td>
                        <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>{$item['quantity']}</td>
                        <td style='padding: 8px; border: 1px solid #ddd; text-align: right;'>GH₵ " . number_format($item['unit_price'], 2) . "</td>
                        <td style='padding: 8px; border: 1px solid #ddd; text-align: right;'>GH₵ " . number_format($itemTotal, 2) . "</td>
                    </tr>";
            }
            
            $emailBody = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-radius: 5px; }
                    .content { padding: 20px; }
                    .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th { background-color: #f8f9fa; padding: 10px; border: 1px solid #ddd; text-align: left; }
                    .total { font-weight: bold; background-color: #f8f9fa; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Supplier Return Notification</h2>
                        <p>Return Number: {$return->return_number}</p>
                    </div>
                    
                    <div class='content'>
                        <p>Dear " . ($supplier ? $supplier->company_name : 'Supplier') . ",</p>
                        
                        <p>This email is to notify you that we have processed a return for the following items:</p>
                        
                        <table>
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity Returned</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {$itemsList}
                            </tbody>
                            <tfoot>
                                <tr class='total'>
                                    <td colspan='3' style='text-align: right; padding: 10px; border: 1px solid #ddd;'><strong>Total Value:</strong></td>
                                    <td style='text-align: right; padding: 10px; border: 1px solid #ddd;'><strong>GH₵ " . number_format($totalValue, 2) . "</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        
                        <p><strong>Return Details:</strong></p>
                        <ul>
                            <li><strong>Return Date:</strong> {$return->return_date}</li>
                            <li><strong>Reason:</strong> " . ucfirst($return->return_reason) . "</li>
                            <li><strong>Status:</strong> Processed</li>
                        </ul>
                        
                        " . ($return->return_description ? "<p><strong>Additional Notes:</strong> {$return->return_description}</p>" : "") . "
                        
                        <p>Please review this return and contact us if you have any questions or concerns.</p>
                        
                        <p>Best regards,<br>
                        <strong>{$companyName}</strong></p>
                    </div>
                    
                    <div class='footer'>
                        <p>This is an automated notification. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>";
            
            // Dispatch email job to queue
            \App\Jobs\SendReturnNotificationEmail::dispatch($return, $supplier, $email, $companyName, $itemsList, $totalValue);
            
            \Log::info('Return notification email job dispatched to queue for: ' . $email);
            
        } catch (\Exception $e) {
            \Log::error('Error sending return notification email: ' . $e->getMessage());
        }
    }

    /**
     * Get supplier returns
     */
    public function getSupplierReturns(Request $request)
    {
        $companyId = Session::get('selected_company_id');
        
        // Debug the raw request content
        \Log::info('Supplier Returns - Raw request content: ' . $request->getContent());
        \Log::info('Supplier Returns - Request headers: ' . json_encode($request->headers->all()));
        \Log::info('Supplier Returns - Request all data: ' . json_encode($request->all()));
        \Log::info('Supplier Returns - Request input: ' . json_encode($request->input()));
        
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        $dateFilter = $request->input('date_filter');
        
        // Debug pagination parameters
        \Log::info('Supplier Returns - Pagination Debug - Page: ' . $page . ', PerPage: ' . $perPage);
        \Log::info('Supplier Returns - Page type: ' . gettype($page) . ', PerPage type: ' . gettype($perPage));
        
        // Debug logging
        \Log::info('Getting supplier returns for company: ' . $companyId);
        \Log::info('Request method: ' . $request->method());
        \Log::info('Auth check: ' . (Auth::check() ? 'true' : 'false'));
        
        // If no company ID in session, try to get from user or use default
        if (!$companyId) {
            if (Auth::check()) {
                $user = Auth::user();
                \Log::info('User type: ' . get_class($user));
                if ($user instanceof \App\Models\User) {
                    $companyId = $user->company_id ?? 1;
                } elseif ($user instanceof \App\Models\CompanySubUser) {
                    $companyId = $user->company_id ?? 1;
                } else {
                    $companyId = 1; // Default fallback
                }
            } else {
                $companyId = 1; // Default fallback
            }
            \Log::info('Using fallback company ID for supplier returns: ' . $companyId);
        }
        
        $query = SupplierReturn::where('company_id', $companyId)
            ->with(['purchaseOrder.supplier']);
            
        if ($dateFilter) {
            $query->whereDate('return_date', $dateFilter);
        }
        
        // Add search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', '%' . $search . '%')
                  ->orWhereHas('purchaseOrder', function($pq) use ($search) {
                      $pq->where('po_number', 'like', '%' . $search . '%')
                         ->orWhereHas('supplier', function($sq) use ($search) {
                             $sq->where('company_name', 'like', '%' . $search . '%');
                         });
                  });
            });
        }
        
        $totalReturns = $query->count();
        $returns = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        
        \Log::info('Found ' . $returns->count() . ' supplier returns for company: ' . $companyId);
        
        $formattedReturns = $returns->map(function($return) {
            // Calculate total value
            $totalValue = 0;
            if (is_array($return->return_items)) {
                foreach ($return->return_items as $item) {
                    $totalValue += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
                }
            }
            
            return [
                'id' => $return->id,
                'return_number' => $return->return_number,
                'po_number' => $return->purchaseOrder ? $return->purchaseOrder->po_number : 'Unknown PO',
                'supplier' => $return->purchaseOrder && $return->purchaseOrder->supplier ? $return->purchaseOrder->supplier->company_name : 'Unknown Supplier',
                'return_date' => $return->return_date,
                'items_count' => is_array($return->return_items) ? count($return->return_items) : 0,
                'return_reason' => $return->return_reason,
                'total_value' => $totalValue,
                'status' => $return->status
            ];
        });
        
        return response()->json([
            'success' => true,
            'returns' => $formattedReturns,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalReturns,
                'last_page' => ceil($totalReturns / $perPage),
                'from' => (($page - 1) * $perPage) + 1,
                'to' => min($page * $perPage, $totalReturns)
            ],
            'debug' => [
                'company_id' => $companyId,
                'total_found' => $returns->count()
            ]
        ]);
    }

    /**
     * Get PO/Receive numbers for a supplier
     */
    public function getPOReceiveNumbers(Request $request)
    {
        try {
            $supplierId = $request->input('supplier_id');
            $companyId = Session::get('selected_company_id');

            // Get purchase orders for this supplier
            $purchaseOrders = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('supplier_id', $supplierId)
                ->where('status', 'completed')
                ->select('id', 'po_number as text', 'order_date')
                ->get()
                ->map(function($po) {
                    return [
                        'id' => 'PO-' . $po->id,
                        'text' => $po->text . ' (PO)',
                        'type' => 'PO',
                        'date' => $po->order_date
                    ];
                });

            // Get goods receipts for this supplier
            $goodsReceipts = POReceiving::whereHas('purchaseOrder', function($query) use ($supplierId) {
                    $query->where('supplier_id', $supplierId);
                })
                ->where('company_id', $companyId)
                ->where('status', 'completed')
                ->select('id', 'receiving_number as text', 'receiving_date')
                ->get()
                ->map(function($gr) {
                    return [
                        'id' => 'GRN-' . $gr->id,
                        'text' => $gr->text . ' (GRN)',
                        'type' => 'GRN',
                        'date' => $gr->receiving_date
                    ];
                });

            // Combine and sort by date
            $references = $purchaseOrders->concat($goodsReceipts)->sortByDesc('date');

            return response()->json([
                'success' => true,
                'references' => $references->values()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting PO/Receive numbers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting references: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get items for a specific reference (PO or GRN)
     */
    public function getReferenceItems(Request $request)
    {
        try {
            $referenceId = $request->input('reference_id');
            $referenceType = $request->input('reference_type');
            $companyId = Session::get('selected_company_id');

            $items = [];

            if ($referenceType === 'PO') {
                $poId = str_replace('PO-', '', $referenceId);
                $purchaseOrder = Wh_PurchaseOrder::where('company_id', $companyId)
                    ->where('id', $poId)
                    ->first();

                if ($purchaseOrder && $purchaseOrder->items) {
                    $items = collect($purchaseOrder->items)->map(function($item) {
                        return [
                            'id' => $item['item_id'] ?? '',
                            'text' => $item['name'] ?? 'Unknown Item',
                            'quantity' => $item['quantity'] ?? 0,
                            'unit_price' => $item['unit_price'] ?? 0,
                            'received_qty' => 0 // For PO, we show original quantity
                        ];
                    })->toArray();
                }
            } elseif ($referenceType === 'GRN') {
                $grnId = str_replace('GRN-', '', $referenceId);
                $receiving = POReceiving::where('company_id', $companyId)
                    ->where('id', $grnId)
                    ->with(['purchaseOrder'])
                    ->first();

                if ($receiving && $receiving->received_items) {
                    $items = collect($receiving->received_items)->map(function($item) use ($receiving) {
                        // Try to get the correct item name and unit price from PO items
                        $itemName = '';
                        $unitPrice = 0;
                        
                        if ($receiving->purchaseOrder && is_array($receiving->purchaseOrder->items)) {
                            foreach ($receiving->purchaseOrder->items as $poItem) {
                                // Convert GRN item_id to name format for comparison
                                $convertedItemId = ucwords(str_replace('-', ' ', $item['item_id'] ?? ''));
                                
                                // Match by item name or by converting item_id to name format
                                if (($poItem['name'] ?? '') == ($item['name'] ?? '') || 
                                    ($poItem['name'] ?? '') == $convertedItemId) {
                                    
                                    $itemName = $poItem['name'] ?? '';
                                    $unitPrice = $poItem['unit_price'] ?? 0;
                                    break;
                                }
                            }
                        }
                        
                        // If no match found, use the data from received_items as fallback
                        if (empty($itemName)) {
                            $itemName = $item['name'] ?? 'Unknown Item';
                        }
                        if ($unitPrice == 0) {
                            $unitPrice = $item['unit_price'] ?? 0;
                        }

                        return [
                            'id' => $item['item_id'] ?? '',
                            'text' => $itemName,
                            'quantity' => $item['received_qty'] ?? 0,
                            'unit_price' => $unitPrice,
                            'received_qty' => $item['received_qty'] ?? 0
                        ];
                    })->toArray();
                }
            }

            return response()->json([
                'success' => true,
                'items' => $items
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting reference items: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit a new supplier return
     */
    public function submitReturn(Request $request)
    {
        try {
            // Debug logging
            \Log::info('Submit return request received', [
                'request_data' => $request->all(),
                'content_type' => $request->header('Content-Type'),
                'method' => $request->method(),
                'json_data' => $request->getContent(),
                'input_data' => $request->input()
            ]);
            
            $companyId = Session::get('selected_company_id');
            
            // If no company ID in session, try to get from user or use default
            if (!$companyId) {
                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user instanceof \App\Models\User) {
                        $companyId = $user->company_id ?? 1;
                    } elseif ($user instanceof \App\Models\CompanySubUser) {
                        $companyId = $user->company_id ?? 1;
                    } else {
                        $companyId = 1; // Default fallback
                    }
                } else {
                    $companyId = 1; // Default fallback
                }
            }

            // Get the data - handle both JSON and form data
            $data = $request->all();
            
            // If the request is JSON, try to get the raw content
            if ($request->header('Content-Type') === 'application/json') {
                $jsonData = json_decode($request->getContent(), true);
                if ($jsonData) {
                    $data = $jsonData;
                }
            }
            
            \Log::info('Processed data for validation:', $data);

            // Create a custom validator to handle the processed data
            $validator = \Validator::make($data, [
                'supplier_id' => 'required|exists:wh__suppliers,id',
                'reference_id' => 'required|string',
                'reference_type' => 'required|in:PO,GRN',
                'return_date' => 'required|date',
                'reason' => 'required|string',
                'description' => 'required|string',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|string',
                'items.*.item_name' => 'required|string',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.reason' => 'required|string'
            ]);
            
            if ($validator->fails()) {
                \Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $validated = $validator->validated();

            // Generate return number
            $returnNumber = 'RET-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

            // Calculate total value
            $totalValue = collect($validated['items'])->sum(function($item) {
                return ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            });

            // Determine purchase_order_id based on reference_type
            $purchaseOrderId = null;
            \Log::info('Determining purchase_order_id:', [
                'reference_type' => $validated['reference_type'],
                'reference_id' => $validated['reference_id']
            ]);
            
            if ($validated['reference_type'] === 'PO') {
                $poId = str_replace('PO-', '', $validated['reference_id']);
                $purchaseOrderId = $poId;
                \Log::info('Using PO reference_id as purchase_order_id:', ['purchase_order_id' => $purchaseOrderId, 'stripped_id' => $poId]);
            } elseif ($validated['reference_type'] === 'GRN') {
                // Get the PO ID from the receiving record
                $grnId = str_replace('GRN-', '', $validated['reference_id']);
                $receiving = POReceiving::find($grnId);
                if ($receiving) {
                    $purchaseOrderId = $receiving->purchase_order_id;
                    \Log::info('Found receiving record, using purchase_order_id:', [
                        'receiving_id' => $receiving->id,
                        'purchase_order_id' => $purchaseOrderId
                    ]);
                } else {
                    \Log::error('Receiving record not found for ID:', ['reference_id' => $validated['reference_id'], 'stripped_id' => $grnId]);
                }
            }
            
            \Log::info('Final purchase_order_id:', ['purchase_order_id' => $purchaseOrderId]);
            
            // If purchase_order_id is still null, we need to handle this
            if (!$purchaseOrderId) {
                \Log::error('Purchase order ID is null, cannot create supplier return');
                \Log::error('Reference data:', [
                    'reference_type' => $validated['reference_type'] ?? 'not set',
                    'reference_id' => $validated['reference_id'] ?? 'not set'
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to determine purchase order ID from reference. Please ensure you have selected a valid reference.'
                ], 400);
            }

            // Create the supplier return
            \Log::info('Attempting to create supplier return with data:', [
                'company_id' => $companyId,
                'supplier_id' => $validated['supplier_id'],
                'purchase_order_id' => $purchaseOrderId,
                'return_number' => $returnNumber,
                'return_date' => $validated['return_date'],
                'return_reason' => $validated['reason'],
                'return_description' => $validated['description'],
                'return_items' => $validated['items'],
                'status' => 'pending',
                'user_id' => Auth::id(),
                'total_value' => $totalValue
            ]);
            
            try {
                $supplierReturn = SupplierReturn::create([
                    'company_id' => $companyId,
                    'supplier_id' => $validated['supplier_id'],
                    'purchase_order_id' => $purchaseOrderId,
                    'return_number' => $returnNumber,
                    'return_date' => $validated['return_date'],
                    'return_reason' => $validated['reason'],
                    'return_description' => $validated['description'],
                    'return_items' => $validated['items'],
                    'status' => 'pending',
                    'user_id' => Auth::id(),
                    'total_value' => $totalValue
                ]);
                
                \Log::info('Supplier return created successfully in database:', [
                    'return_id' => $supplierReturn->id,
                    'return_number' => $supplierReturn->return_number,
                    'created_at' => $supplierReturn->created_at
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create supplier return in database:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            \Log::info('Supplier return created successfully', [
                'return_id' => $supplierReturn->id,
                'return_number' => $returnNumber,
                'company_id' => $companyId,
                'items_count' => count($validated['items'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Supplier return submitted successfully',
                'data' => [
                    'id' => $supplierReturn->id,
                    'return_number' => $returnNumber
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error submitting supplier return: ' . $e->getMessage());
            \Log::error('Validation errors: ' . json_encode($e->errors()));
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error submitting supplier return: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit supplier return: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update PO status based on received quantities
     */
    protected function updatePOStatusAfterReceiving(Wh_PurchaseOrder $po)
    {
        // Update to received when receiving is completed
        $po->update(['status' => 'received']);
        
        // TODO: Implement proper receiving calculation when POReceiving model is fully set up
        // $totalOrdered = collect($po->items)->sum('quantity');
        // $totalReceived = $po->receivings->sum(function($receiving) {
        //     return collect($receiving->received_items)->sum('received_qty');
        // });
        // 
        // if ($totalReceived >= $totalOrdered) {
        //     $po->update(['status' => 'fully_received']);
        // } else if ($totalReceived > 0) {
        //     $po->update(['status' => 'partially_received']);
        // }
    }
    
         /**
      * Get total received quantity for a specific item in a PO
      */
     protected function getTotalReceivedQuantity($poId, $itemId)
     {
         $receivings = POReceiving::where('purchase_order_id', $poId)
             ->where('status', 'completed')
             ->get();
             
         \Log::info("Looking for item_id: {$itemId} in PO: {$poId}");
         \Log::info("Found " . $receivings->count() . " completed receivings");
         
         $totalReceived = 0;
         foreach ($receivings as $receiving) {
             \Log::info("Checking receiving ID: {$receiving->id}");
             if (is_array($receiving->received_items)) {
                 foreach ($receiving->received_items as $item) {
                     \Log::info("Received item: " . json_encode($item));
                     if (($item['item_id'] ?? null) == $itemId) {
                         $qty = $item['received_qty'] ?? 0;
                         $totalReceived += $qty;
                         \Log::info("Match found! Adding {$qty} to total. New total: {$totalReceived}");
                     }
                 }
             }
         }
         
         \Log::info("Final total received for item {$itemId}: {$totalReceived}");
         return $totalReceived;
     }
    
         /**
      * Get total returned quantity for a specific item in a PO
      */
     protected function getTotalReturnedQuantity($poId, $itemId)
     {
         $returns = SupplierReturn::where('purchase_order_id', $poId)
             ->where('status', 'processed') // Only count processed returns
             ->get();
             
         \Log::info("Looking for processed returns for item_id: {$itemId} in PO: {$poId}");
         \Log::info("Found " . $returns->count() . " processed returns");
         
         $totalReturned = 0;
         foreach ($returns as $return) {
             \Log::info("Checking return ID: {$return->id}");
             if (is_array($return->return_items)) {
                 foreach ($return->return_items as $item) {
                     \Log::info("Return item: " . json_encode($item));
                     if (($item['item_id'] ?? null) == $itemId) {
                         $qty = $item['quantity'] ?? 0;
                         $totalReturned += $qty;
                         \Log::info("Match found! Adding {$qty} to total. New total: {$totalReturned}");
                     }
                 }
             }
         }
         
         \Log::info("Final total returned for item {$itemId}: {$totalReturned}");
         return $totalReturned;
     }

    /**
     * Get total returned quantity for a specific item in a PO (for validation - includes all returns)
     */
    protected function getTotalReturnedQuantityForValidation($poId, $itemId)
    {
        $returns = SupplierReturn::where('purchase_order_id', $poId)
            ->whereIn('status', ['pending', 'processed']) // Count both pending and processed returns
            ->get();
            
        \Log::info("Looking for returns (validation) for item_id: {$itemId} in PO: {$poId}");
        \Log::info("Found " . $returns->count() . " returns (pending + processed)");
        
        $totalReturned = 0;
        foreach ($returns as $return) {
            \Log::info("Checking return ID: {$return->id}, Status: {$return->status}");
            if (is_array($return->return_items)) {
                foreach ($return->return_items as $item) {
                    \Log::info("Return item: " . json_encode($item));
                    if (($item['item_id'] ?? null) == $itemId) {
                        $qty = $item['quantity'] ?? 0;
                        $totalReturned += $qty;
                        \Log::info("Match found! Adding {$qty} to total. New total: {$totalReturned}");
                    }
                }
            }
        }
        
        \Log::info("Final total returned for validation for item {$itemId}: {$totalReturned}");
        return $totalReturned;
    }
     
     /**
      * Get purchase orders for table display
      */
    public function getPurchaseOrders(Request $request)
    {
        $companyId = Session::get('selected_company_id');
        
        // Debug the raw request content
        \Log::info('Raw request content: ' . $request->getContent());
        \Log::info('Request headers: ' . json_encode($request->headers->all()));
        \Log::info('Request all data: ' . json_encode($request->all()));
        \Log::info('Request input: ' . json_encode($request->input()));
        
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        
        // Debug pagination parameters
        \Log::info('Pagination Debug - Page: ' . $page . ', PerPage: ' . $perPage);
        \Log::info('Page type: ' . gettype($page) . ', PerPage type: ' . gettype($perPage));
        
        // Debug logging
        \Log::info('Getting purchase orders for company: ' . $companyId);
        \Log::info('Request method: ' . $request->method());
        \Log::info('Auth check: ' . (Auth::check() ? 'true' : 'false'));
        
        // If no company ID in session, try to get from user or use default
        if (!$companyId) {
            if (Auth::check()) {
                $user = Auth::user();
                \Log::info('User type: ' . get_class($user));
                if ($user instanceof \App\Models\User) {
                    $companyId = $user->company_id ?? 1;
                } elseif ($user instanceof \App\Models\CompanySubUser) {
                    $companyId = $user->company_id ?? 1;
                } else {
                    $companyId = 1; // Default fallback
                }
            } else {
                $companyId = 1; // Default fallback
            }
            \Log::info('Using fallback company ID: ' . $companyId);
        }
         
        $query = Wh_PurchaseOrder::where('company_id', $companyId)
            ->where('status', 'pending')  // Only show pending POs
            ->with(['supplier']);
            
        // Add search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', '%' . $search . '%')
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('company_name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Apply filters if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('supplier_id') && $request->supplier_id !== '') {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        $totalPOs = $query->count();
        $purchaseOrders = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        
        // Debug logging
        \Log::info('Found ' . $purchaseOrders->count() . ' purchase orders');
        
        $formattedPOs = $purchaseOrders->map(function($po) {
            // Calculate totals
            $totalItems = collect($po->items)->sum('quantity');
            $totalValue = collect($po->items)->sum(function($item) {
                return ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            });
            
            return [
                'id' => $po->id,
                'po_number' => $po->po_number,
                'supplier' => $po->supplier ? $po->supplier->company_name : 'Unknown Supplier',
                'order_date' => $po->order_date,
                'expected_date' => $po->delivery_date ?? $po->order_date,
                'status' => $po->status,
                'total_items' => $totalItems,
                'total_value' => $totalValue
            ];
        });
        
        return response()->json([
            'success' => true,
            'purchaseOrders' => $formattedPOs,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalPOs,
                'last_page' => ceil($totalPOs / $perPage),
                'from' => (($page - 1) * $perPage) + 1,
                'to' => min($page * $perPage, $totalPOs)
            ],
            'debug' => [
                'company_id' => $companyId,
                'total_found' => $purchaseOrders->count()
            ]
        ]);
    }
     
         /**
     * Get PO details for viewing
     */
    public function getPODetails($id)
    {
        $companyId = Session::get('selected_company_id');
        
        $po = Wh_PurchaseOrder::where('company_id', $companyId)
            ->where('id', $id)
            ->with(['supplier'])
            ->first();
            
        if (!$po) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase order not found'
            ], 404);
        }
        
        $formattedData = [
            'id' => $po->id,
            'po_number' => $po->po_number,
            'supplier' => $po->supplier ? $po->supplier->company_name : 'Unknown Supplier',
            'order_date' => $po->order_date,
            'expected_date' => $po->delivery_date ?? $po->order_date,
            'status' => $po->status,
            'items' => is_array($po->items) ? $po->items : [],
            'total_value' => collect($po->items)->sum(function($item) {
                return ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            })
        ];
        
        return response()->json([
            'success' => true,
            'po' => $formattedData
        ]);
    }
    
    /**
     * Get GRN details for viewing
     */
    public function getGRNDetails($id)
    {
        $companyId = Session::get('selected_company_id');
        
        $receiving = POReceiving::where('company_id', $companyId)
            ->where('id', $id)
            ->with(['purchaseOrder.supplier', 'user'])
            ->first();
            
        if (!$receiving) {
            return response()->json([
                'success' => false,
                'message' => 'GRN not found'
            ], 404);
        }
        
        // Process items to ensure they have proper names and calculate totals
        $processedItems = [];
        $totalValue = 0;
        
        // Debug logging
        \Log::info('Processing GRN items for getGRNDetails', [
            'grn_id' => $receiving->id,
            'received_items' => $receiving->received_items,
            'po_items' => $receiving->purchaseOrder ? $receiving->purchaseOrder->items : null,
            'po_id' => $receiving->purchase_order_id,
            'po_exists' => $receiving->purchaseOrder ? 'Yes' : 'No'
        ]);
        
        \Log::info('Raw received_items structure:', [
            'is_array' => is_array($receiving->received_items),
            'count' => is_array($receiving->received_items) ? count($receiving->received_items) : 'N/A',
            'first_item' => is_array($receiving->received_items) && count($receiving->received_items) > 0 ? $receiving->received_items[0] : 'N/A'
        ]);
        
        \Log::info('PO Items structure:', [
            'po_exists' => $receiving->purchaseOrder ? 'Yes' : 'No',
            'po_items_is_array' => $receiving->purchaseOrder ? is_array($receiving->purchaseOrder->items) : 'N/A',
            'po_items_count' => $receiving->purchaseOrder && is_array($receiving->purchaseOrder->items) ? count($receiving->purchaseOrder->items) : 'N/A',
            'po_first_item' => $receiving->purchaseOrder && is_array($receiving->purchaseOrder->items) && count($receiving->purchaseOrder->items) > 0 ? $receiving->purchaseOrder->items[0] : 'N/A'
        ]);

        if (is_array($receiving->received_items)) {
            foreach ($receiving->received_items as $item) {
                // Always try to get the correct item name and unit price from PO items
                $itemName = '';
                $unitPrice = 0;
                
                if ($receiving->purchaseOrder && is_array($receiving->purchaseOrder->items)) {
                    foreach ($receiving->purchaseOrder->items as $poItem) {
                        // Convert GRN item_id to name format for comparison
                        $convertedItemId = ucwords(str_replace('-', ' ', $item['item_id'] ?? ''));
                        
                        \Log::info('Comparing GRN item with PO item:', [
                            'grn_item_id' => $item['item_id'] ?? 'N/A',
                            'grn_item_name' => $item['name'] ?? 'N/A',
                            'converted_item_id' => $convertedItemId,
                            'po_item_name' => $poItem['name'] ?? 'N/A',
                            'po_item_unit_price' => $poItem['unit_price'] ?? 'N/A',
                            'name_match' => ($poItem['name'] ?? '') == ($item['name'] ?? ''),
                            'converted_match' => ($poItem['name'] ?? '') == $convertedItemId
                        ]);
                        
                        // Match by item name or by converting item_id to name format
                        if (($poItem['name'] ?? '') == ($item['name'] ?? '') || 
                            ($poItem['name'] ?? '') == $convertedItemId) {
                            
                            $itemName = $poItem['name'] ?? '';
                            $unitPrice = $poItem['unit_price'] ?? 0;
                            
                            \Log::info('Match found in getGRNDetails:', [
                                'item_name' => $itemName,
                                'unit_price' => $unitPrice,
                                'match_type' => ($poItem['name'] ?? '') == ($item['name'] ?? '') ? 'name_match' : 'converted_match'
                            ]);
                            break;
                        }
                    }
                }
                
                // If no match found, use the data from received_items as fallback
                if (empty($itemName)) {
                    $itemName = $item['name'] ?? 'Unknown Item';
                }
                if ($unitPrice == 0) {
                    $unitPrice = $item['unit_price'] ?? 0;
                }
                
                $receivedQty = $item['received_qty'] ?? 0;
                
                // Calculate effective quantity (original received - all returns)
                $effectiveQty = $receivedQty;
                if ($receiving->purchase_order_id) {
                    $totalReturned = $this->getTotalReturnedQuantityForValidation($receiving->purchase_order_id, $item['item_id']);
                    $effectiveQty = max(0, $receivedQty - $totalReturned);
                }
                
                $itemTotal = $receivedQty * $unitPrice; // Keep original total for display
                $totalValue += $itemTotal;
                
                $processedItems[] = [
                    'name' => $itemName,
                    'received_qty' => $receivedQty,
                    'effective_qty' => $effectiveQty, // Add effective quantity
                    'returned_qty' => $receivedQty - $effectiveQty, // Calculate returned quantity
                    'rejected_qty' => $item['rejected_qty'] ?? 0,
                    'location' => $item['location'] ?? 'N/A',
                    'unit_price' => $unitPrice,
                    'total' => $itemTotal
                ];
                
                // Debug logging for each item
                \Log::info('Processed item in getGRNDetails', [
                    'item_name' => $itemName,
                    'received_qty' => $receivedQty,
                    'unit_price' => $unitPrice,
                    'item_total' => $itemTotal
                ]);
            }
        }
        
        // Get user name properly
        $userName = 'Unknown User';
        if ($receiving->user) {
            if ($receiving->user instanceof \App\Models\User) {
                $userName = $receiving->user->fullname ?? $receiving->user->name ?? 'Unknown User';
            } elseif ($receiving->user instanceof \App\Models\CompanySubUser) {
                $userName = $receiving->user->fullname ?? $receiving->user->name ?? 'Unknown User';
            } else {
                $userName = $receiving->user->fullname ?? $receiving->user->name ?? $receiving->user->first_name ?? 'Unknown User';
            }
        }
        
                 // Debug logging for GRN status
         \Log::info('GRN Status debug', [
             'grn_id' => $receiving->id,
             'grn_number' => $receiving->receiving_number,
             'status' => $receiving->status,
             'status_type' => gettype($receiving->status)
         ]);
         
         $formattedData = [
             'id' => $receiving->id,
             'grn_number' => $receiving->receiving_number,
             'po_number' => $receiving->purchaseOrder ? $receiving->purchaseOrder->po_number : 'Unknown PO',
             'supplier' => $receiving->purchaseOrder && $receiving->purchaseOrder->supplier ? $receiving->purchaseOrder->supplier->company_name : 'Unknown Supplier',
             'received_date' => $receiving->receiving_date,
             'received_by' => $userName,
             'status' => $receiving->status,
             'notes' => $receiving->notes,
             'items' => $processedItems,
             'total_value' => $totalValue
         ];
        
        return response()->json([
            'success' => true,
            'data' => $formattedData
        ]);
    }
    
    /**
     * Print GRN
     */
    public function printGRN($id)
    {
        $companyId = Session::get('selected_company_id');
        
        $receiving = POReceiving::where('company_id', $companyId)
            ->where('id', $id)
            ->with(['purchaseOrder.supplier', 'user'])
            ->first();
            
        if (!$receiving) {
            abort(404, 'GRN not found');
        }
        
        // Process items to ensure they have proper names and calculate totals
        $processedItems = [];
        $totalValue = 0;
        
        if (is_array($receiving->received_items)) {
            foreach ($receiving->received_items as $item) {
                // Always try to get the correct item name and unit price from PO items
                $itemName = '';
                $unitPrice = 0;
                
                if ($receiving->purchaseOrder && is_array($receiving->purchaseOrder->items)) {
                    foreach ($receiving->purchaseOrder->items as $poItem) {
                        // Convert GRN item_id to name format for comparison
                        $convertedItemId = ucwords(str_replace('-', ' ', $item['item_id'] ?? ''));
                        
                        // Match by item name or by converting item_id to name format
                        if (($poItem['name'] ?? '') == ($item['name'] ?? '') || 
                            ($poItem['name'] ?? '') == $convertedItemId) {
                            
                            $itemName = $poItem['name'] ?? '';
                            $unitPrice = $poItem['unit_price'] ?? 0;
                            break;
                        }
                    }
                }
                
                // If no match found, use the data from received_items as fallback
                if (empty($itemName)) {
                    $itemName = $item['name'] ?? 'Unknown Item';
                }
                if ($unitPrice == 0) {
                    $unitPrice = $item['unit_price'] ?? 0;
                }
                
                $receivedQty = $item['received_qty'] ?? 0;
                $itemTotal = $receivedQty * $unitPrice;
                $totalValue += $itemTotal;
                
                $processedItems[] = [
                    'name' => $itemName,
                    'received_qty' => $receivedQty,
                    'rejected_qty' => $item['rejected_qty'] ?? 0,
                    'location' => $item['location'] ?? 'N/A',
                    'unit_price' => $unitPrice,
                    'total' => $itemTotal
                ];
            }
        }
        
        // Get user name properly
        $userName = 'Unknown User';
        if ($receiving->user) {
            if ($receiving->user instanceof \App\Models\User) {
                $userName = $receiving->user->fullname ?? $receiving->user->name ?? 'Unknown User';
            } elseif ($receiving->user instanceof \App\Models\CompanySubUser) {
                $userName = $receiving->user->fullname ?? $receiving->user->name ?? 'Unknown User';
            } else {
                $userName = $receiving->user->fullname ?? $receiving->user->name ?? $receiving->user->first_name ?? 'Unknown User';
            }
        }
        
        $data = [
            'receiving' => $receiving,
            'purchaseOrder' => $receiving->purchaseOrder,
            'supplier' => $receiving->purchaseOrder ? $receiving->purchaseOrder->supplier : null,
            'user' => $receiving->user,
            'userName' => $userName,
            'processedItems' => $processedItems,
            'totalValue' => $totalValue
        ];
        
        return view('company.InventoryManagement.WarehouseOps.print-grn', $data);
    }
    
    /**
     * Debug route to check GRN data
     */
    public function debugGRNData()
    {
        $companyId = Session::get('selected_company_id');
        
        $receivings = POReceiving::where('company_id', $companyId)
            ->with(['purchaseOrder.supplier', 'user'])
            ->get();
            
        $debugData = [];
        foreach ($receivings as $receiving) {
            $debugData[] = [
                'id' => $receiving->id,
                'grn_number' => $receiving->receiving_number,
                'po_id' => $receiving->purchase_order_id,
                'po_exists' => $receiving->purchaseOrder ? 'Yes' : 'No',
                'received_items' => $receiving->received_items,
                'po_items' => $receiving->purchaseOrder ? $receiving->purchaseOrder->items : null
            ];
        }
        
        return response()->json([
            'success' => true,
            'debug_data' => $debugData
        ]);
     }
     
     /**
      * Get current user information
      */
     public function getCurrentUser()
     {
         if (Auth::check()) {
             $user = Auth::user();
             
             // Debug logging
             \Log::info('Current user info', [
                 'user_id' => $user->id,
                 'user_class' => get_class($user),
                 'user_table' => $user->getTable(),
                 'user_attributes' => $user->getAttributes()
             ]);
             
             // Check if it's a main user or sub user
             if ($user instanceof \App\Models\User) {
                 // Main user - use fullname field
                 $userName = $user->fullname ?? 'Unknown User';
                 $userEmail = $user->personal_email ?? '';
             } elseif ($user instanceof \App\Models\CompanySubUser) {
                 // Sub user - use fullname field
                 $userName = $user->fullname ?? 'Unknown User';
                 $userEmail = $user->email ?? '';
             } else {
                 // Fallback - try to get name from any available field
                 $userName = $user->fullname ?? $user->name ?? $user->first_name ?? 'Unknown User';
                 $userEmail = $user->personal_email ?? $user->email ?? '';
             }
             
             \Log::info('Resolved user name', [
                 'user_name' => $userName,
                 'user_email' => $userEmail
             ]);
             
             return response()->json([
                 'success' => true,
                 'user' => [
                     'id' => $user->id,
                     'name' => $userName,
                     'email' => $userEmail
                 ]
             ]);
         }
         
         return response()->json([
             'success' => false,
             'message' => 'User not authenticated'
         ], 401);
     }

    /**
     * Get supplier references (POs and Receivings) for supplier return
     */
    public function getSupplierReferences(Request $request)
    {
        // Check authentication
        if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to continue'
            ], 401);
        }

        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return response()->json([
                'success' => false,
                'message' => 'Company session not found. Please login again.'
            ], 401);
        }
        $supplierId = $request->input('supplier_id');

        if (!$supplierId) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier ID is required'
            ], 400);
        }

        // Get Receivings for this supplier (only receiving numbers, not POs)
        $receivings = POReceiving::where('company_id', $companyId)
            ->whereHas('purchaseOrder', function($query) use ($supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->get()
            ->map(function($receiving) {
                return [
                    'id' => $receiving->id,
                    'reference_number' => $receiving->receiving_number,
                    'date' => $receiving->receiving_date,
                    'type' => 'receiving'
                ];
            });

        // Only return receiving references
        $references = $receivings->sortByDesc('date')->values();

        return response()->json([
            'success' => true,
            'references' => $references
        ]);
    }

    /**
     * Get all suppliers for return modal
     */
    public function getAllSuppliers(Request $request)
    {
        // Check authentication
        if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to continue'
            ], 401);
        }

        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return response()->json([
                'success' => false,
                'message' => 'Company session not found. Please login again.'
            ], 401);
        }

        try {
            $suppliers = Wh_Supplier::where('company_id', $companyId)
                ->select('id', 'company_name')
                ->orderBy('company_name')
                ->get();

            return response()->json([
                'success' => true,
                'suppliers' => $suppliers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load suppliers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get receiving items for supplier return
     */
    public function getReceivingItems($receivingId, Request $request)
    {
        // Check authentication
        if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to continue'
            ], 401);
        }

        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return response()->json([
                'success' => false,
                'message' => 'Company session not found. Please login again.'
            ], 401);
        }

        $receiving = POReceiving::where('company_id', $companyId)
            ->where('id', $receivingId)
            ->with('purchaseOrder') // Load the related PO
            ->first();

        if (!$receiving) {
            return response()->json([
                'success' => false,
                'message' => 'Receiving not found'
            ], 404);
        }

        // Debug: Log the receiving data structure
        \Log::info('Receiving data:', [
            'receiving_id' => $receiving->id,
            'po_id' => $receiving->purchase_order_id,
            'po_exists' => $receiving->purchaseOrder ? 'Yes' : 'No',
            'received_items' => $receiving->received_items,
            'received_items_type' => gettype($receiving->received_items),
            'received_items_count' => is_array($receiving->received_items) ? count($receiving->received_items) : 'not array'
        ]);

        // Get items from the receiving with enhanced name resolution
        $items = collect($receiving->received_items ?? [])->map(function($item) use ($receiving) {
            \Log::info('Processing item:', $item);
            
            // Try to get the correct item name from the original PO
            $itemName = $item['name'] ?? 'Unknown Item';
            $unitPrice = $item['unit_price'] ?? 0;
            
            // If the item name is "Unknown Item" and we have the PO, try to find the correct name
            if ($itemName === 'Unknown Item' && $receiving->purchaseOrder && $receiving->purchaseOrder->items) {
                foreach ($receiving->purchaseOrder->items as $poItem) {
                    // Try to match by item_id (slug)
                    $poItemSlug = Str::slug($poItem['name'] ?? '');
                    $itemSlug = Str::slug($item['item_id'] ?? '');
                    
                    if ($poItemSlug === $itemSlug) {
                        $itemName = $poItem['name'] ?? 'Unknown Item';
                        $unitPrice = $poItem['unit_price'] ?? 0;
                        
                        \Log::info('Found correct item name from PO:', [
                            'original_name' => $item['name'],
                            'corrected_name' => $itemName,
                            'po_item_slug' => $poItemSlug,
                            'item_slug' => $itemSlug
                        ]);
                        break;
                    }
                }
            }
            
            return [
                'id' => $item['item_id'] ?? Str::slug($itemName),
                'name' => $itemName,
                'received_qty' => $item['received_qty'] ?? $item['quantity'] ?? 0,
                'unit_price' => $unitPrice,
                'unit' => $item['unit'] ?? 'pcs',
                'description' => $item['description'] ?? ''
            ];
        });

        return response()->json([
            'success' => true,
            'items' => $items
        ]);
    }
}