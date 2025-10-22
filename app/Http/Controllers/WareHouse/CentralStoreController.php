<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Wh_Supplier;
use App\Models\Wh_PurchaseOrder;
use App\Models\QualityInspection;
use App\Models\CentralStore;

class CentralStoreController extends Controller
{
    /**
     * Display the central store page
     */
    public function index()
    {
        return view('company.InventoryManagement.WarehouseOps.centralStores');
    }

    /**
     * Get central store page data (POST method)
     */
    public function getPageData()
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            // Get statistics
            $stats = CentralStore::where('company_id', $companyId)
                ->whereNull('deleted_at')
                ->selectRaw('
                    COUNT(*) as total_items,
                    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_items,
                    SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_items,
                    SUM(total_price) as total_value,
                    SUM(CASE WHEN status = "pending" THEN total_price ELSE 0 END) as pending_value,
                    SUM(CASE WHEN status = "completed" THEN total_price ELSE 0 END) as completed_value
                ')
                ->first();

            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting central store page data', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to load page data'], 500);
        }
    }

    /**
     * Get suppliers with approved POs that can be transferred to central store
     */
    public function getSuppliersWithApprovedPOs()
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            // Get suppliers with POs that have all items inspected and approved
            $suppliers = Wh_Supplier::select(
                    'wh__suppliers.id',
                    'wh__suppliers.company_name',
                    'wh__suppliers.email',
                    'wh__suppliers.phone'
                )
                ->join('wh__purchase_orders', function($join) use ($companyId) {
                    $join->on('wh__suppliers.id', '=', 'wh__purchase_orders.supplier_id')
                         ->where('wh__purchase_orders.company_id', $companyId)
                         ->where('wh__purchase_orders.status', 'approved');
                })
                ->where('wh__suppliers.company_id', $companyId)
                ->whereExists(function($query) {
                    $query->select(DB::raw(1))
                          ->from('wh__purchase_orders as po')
                          ->whereColumn('po.supplier_id', 'wh__suppliers.id')
                          ->where('po.status', 'approved')
                          ->whereRaw('(
                              SELECT COUNT(DISTINCT qi.item_name)
                              FROM quality_inspections qi
                              WHERE qi.purchase_order_id = po.id
                              AND qi.deleted_at IS NULL
                              AND qi.status = "approved"
                          ) = JSON_LENGTH(po.items)');
                })
                ->groupBy(
                    'wh__suppliers.id',
                    'wh__suppliers.company_name',
                    'wh__suppliers.email',
                    'wh__suppliers.phone'
                )
                ->get();

            Log::info('Suppliers with approved POs found', ['count' => $suppliers->count()]);

            return response()->json($suppliers);

        } catch (\Exception $e) {
            Log::error('Error getting suppliers with approved POs', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to load suppliers'], 500);
        }
    }

    /**
     * Get approved POs for a supplier that can be transferred to central store
     */
    public function getApprovedPOsForSupplier($supplierId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            // Get POs that are approved and have all items inspected
            $allPos = Wh_PurchaseOrder::where('supplier_id', $supplierId)
                ->where('company_id', $companyId)
                ->get();
                
            Log::info('All POs for supplier', [
                'supplier_id' => $supplierId,
                'total_pos' => $allPos->count(),
                'pos_statuses' => $allPos->pluck('status')->toArray()
            ]);
            
            $approvedPos = $allPos->where('status', 'approved');
            Log::info('Approved POs for supplier', [
                'approved_count' => $approvedPos->count(),
                'approved_pos' => $approvedPos->pluck('po_number')->toArray()
            ]);
            
            $pos = $approvedPos->filter(function($po) {
                    // Check if all items are inspected and approved
                    $items = is_string($po->items) ? json_decode($po->items, true) : $po->items;
                    $totalItems = is_array($items) ? count($items) : 0;
                    $inspectedItems = QualityInspection::where('purchase_order_id', $po->id)
                        ->where('status', 'approved')
                        ->whereNull('deleted_at')
                        ->count();
                    
                    Log::info('PO inspection check', [
                        'po_id' => $po->id,
                        'po_number' => $po->po_number,
                        'total_items' => $totalItems,
                        'inspected_items' => $inspectedItems,
                        'all_inspected' => $totalItems === $inspectedItems
                    ]);
                    
                    return $totalItems === $inspectedItems;
                })
                ->map(function($po) {
                    $items = is_string($po->items) ? json_decode($po->items, true) : $po->items;
                    $totalItems = is_array($items) ? count($items) : 0;
                    $inspectedItems = QualityInspection::where('purchase_order_id', $po->id)
                        ->where('status', 'approved')
                        ->whereNull('deleted_at')
                        ->count();
                    
                    // Check if already transferred to central store
                    $transferredItems = CentralStore::where('purchase_order_id', $po->id)
                        ->whereNull('deleted_at')
                        ->count();
                    
                    $remainingItems = $totalItems - $transferredItems;
                    
                    return [
                        'id' => $po->id,
                        'po_number' => $po->po_number,
                        'total_items' => $totalItems,
                        'inspected_items' => $inspectedItems,
                        'transferred_items' => $transferredItems,
                        'remaining_items' => $remainingItems,
                        'can_transfer' => $remainingItems > 0
                    ];
                })
                ->filter(function($po) {
                    Log::info('PO transfer check', [
                        'po_id' => $po['id'],
                        'po_number' => $po['po_number'],
                        'remaining_items' => $po['remaining_items'],
                        'can_transfer' => $po['can_transfer']
                    ]);
                    return $po['can_transfer'];
                })
                ->values();

            Log::info('Final approved POs found for supplier', [
                'supplier_id' => $supplierId,
                'pos_count' => $pos->count(),
                'pos_details' => $pos->toArray()
            ]);

            return response()->json($pos);

        } catch (\Exception $e) {
            Log::error('Error getting approved POs for supplier', [
                'supplier_id' => $supplierId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to load POs'], 500);
        }
    }

    /**
     * Get approved items from a PO that can be transferred to central store
     */
    public function getApprovedItemsFromPO($poId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            // Get the PO
            $po = Wh_PurchaseOrder::where('id', $poId)
                ->where('company_id', $companyId)
                ->first();

            if (!$po) {
                return response()->json(['success' => false, 'message' => 'Purchase order not found'], 404);
            }

            // Get PO items
            $poItems = is_string($po->items) ? json_decode($po->items, true) : $po->items;
            
            // Get already transferred items
            $transferredItems = CentralStore::where('purchase_order_id', $poId)
                ->whereNull('deleted_at')
                ->pluck('item_name')
                ->toArray();

            // Get approved inspections for this PO
            $approvedInspections = QualityInspection::where('purchase_order_id', $poId)
                ->where('status', 'approved')
                ->whereNull('deleted_at')
                ->get()
                ->keyBy('item_name');

            // Filter items that are approved but not yet transferred
            $availableItems = collect($poItems)->filter(function($item) use ($transferredItems, $approvedInspections) {
                return !in_array($item['name'], $transferredItems) && 
                       $approvedInspections->has($item['name']);
            })->map(function($item) use ($approvedInspections) {
                $inspection = $approvedInspections->get($item['name']);
                return [
                    'id' => $inspection->id, // Use inspection ID as unique identifier
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'batch_number' => $inspection->batch_number,
                    'category' => $inspection->item_category,
                    'inspection_date' => $inspection->inspection_date
                ];
            })->values();

            Log::info('Available items for transfer found', [
                'po_id' => $poId,
                'items_count' => $availableItems->count()
            ]);

            return response()->json($availableItems);

        } catch (\Exception $e) {
            Log::error('Error getting approved items from PO', [
                'po_id' => $poId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to load items'], 500);
        }
    }

    /**
     * Transfer items to central store
     */
    public function transferToCentralStore(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $userId = Auth::id();
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            $request->validate([
                'supplier_id' => 'required|exists:wh__suppliers,id',
                'purchase_order_id' => 'required|exists:wh__purchase_orders,id',
                'items' => 'required|array',
                'items.*.name' => 'required|string',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.batch_number' => 'required|string',
                'location' => 'required|string|max:255',
                'brand' => 'nullable|string|max:255',
                'unit' => 'required|string|max:50',
                'description' => 'nullable|string',
                'notes' => 'nullable|string'
            ]);

            DB::beginTransaction();

            $transferredItems = [];
            $totalValue = 0;

            foreach ($request->items as $item) {
                // Check if item is already transferred
                $existingTransfer = CentralStore::where('purchase_order_id', $request->purchase_order_id)
                    ->where('item_name', $item['name'])
                    ->whereNull('deleted_at')
                    ->first();

                if ($existingTransfer) {
                    continue; // Skip if already transferred
                }

                // Get category from inspection data
                $inspection = QualityInspection::where('purchase_order_id', $request->purchase_order_id)
                    ->where('item_name', $item['name'])
                    ->where('status', 'approved')
                    ->whereNull('deleted_at')
                    ->first();

                // Check if this item is a re-order from the PO
                $purchaseOrder = Wh_PurchaseOrder::findOrFail($request->purchase_order_id);
                $isReorder = false;
                $reorderBatchNumber = null;
                
                if ($purchaseOrder->items && is_array($purchaseOrder->items)) {
                    foreach ($purchaseOrder->items as $poItem) {
                        if (($poItem['name'] ?? '') === $item['name']) {
                            $isReorder = ($poItem['is_reorder'] ?? false) == 1 || ($poItem['is_reorder'] ?? false) === true;
                            $reorderBatchNumber = $poItem['batch_number'] ?? null;
                            break;
                        }
                    }
                }
                
                // If it's a re-order, add to existing batch in central store
                if ($isReorder && $reorderBatchNumber) {
                    $existingBatch = CentralStore::where('company_id', $companyId)
                        ->where('batch_number', $reorderBatchNumber)
                        ->first();
                    
                    if ($existingBatch) {
                        // Add quantity to existing batch
                        $existingBatch->quantity += $item['quantity'];
                        $existingBatch->total_price = $existingBatch->quantity * $existingBatch->unit_price;
                        $existingBatch->save();
                        
                        \Log::info('Added re-order quantity to existing batch in central store:', [
                            'batch_number' => $reorderBatchNumber,
                            'added_quantity' => $item['quantity'],
                            'new_total_quantity' => $existingBatch->quantity
                        ]);
                        
                        $centralStoreItem = $existingBatch;
                    } else {
                        // Create new central store record with re-order batch number
                        $centralStoreItem = CentralStore::create([
                            'company_id' => $companyId,
                            'supplier_id' => $request->supplier_id,
                            'purchase_order_id' => $request->purchase_order_id,
                            'item_name' => $item['name'],
                            'item_category' => $inspection ? $inspection->item_category : 'Uncategorized',
                            'brand' => $request->brand ?? null,
                            'unit' => $request->unit ?? 'pcs',
                            'description' => $request->description ?? null,
                            'unit_price' => $item['unit_price'],
                            'quantity' => $item['quantity'],
                            'total_price' => $item['quantity'] * $item['unit_price'],
                            'batch_number' => $reorderBatchNumber,
                            'location' => $request->location,
                            'status' => 'pending',
                            'notes' => $request->notes,
                            'created_by' => $userId,
                            'transfer_date' => now()
                        ]);
                    }
                } else {
                    // Regular item - create new central store record
                $centralStoreItem = CentralStore::create([
                    'company_id' => $companyId,
                    'supplier_id' => $request->supplier_id,
                    'purchase_order_id' => $request->purchase_order_id,
                    'item_name' => $item['name'],
                    'item_category' => $inspection ? $inspection->item_category : 'Uncategorized',
                    'brand' => $request->brand ?? null,
                    'unit' => $request->unit ?? 'pcs',
                    'description' => $request->description ?? null,
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'batch_number' => $item['batch_number'],
                    'location' => $request->location,
                    'status' => 'pending',
                    'notes' => $request->notes,
                    'created_by' => $userId,
                    'transfer_date' => now()
                ]);
                }

                $transferredItems[] = $centralStoreItem;
                $totalValue += $centralStoreItem->total_price;
            }

            // Check if all items from PO are now transferred
            $po = Wh_PurchaseOrder::find($request->purchase_order_id);
            $poItems = is_string($po->items) ? json_decode($po->items, true) : $po->items;
            $totalPoItems = is_array($poItems) ? count($poItems) : 0;
            
            $transferredCount = CentralStore::where('purchase_order_id', $request->purchase_order_id)
                ->whereNull('deleted_at')
                ->count();

            // If all items transferred, mark PO as completed
            if ($transferredCount >= $totalPoItems) {
                $po->update(['status' => 'completed']);
                Log::info('PO marked as completed', [
                    'po_id' => $request->purchase_order_id,
                    'po_number' => $po->po_number
                ]);
            }

            DB::commit();

            Log::info('Items transferred to central store', [
                'po_id' => $request->purchase_order_id,
                'items_count' => count($transferredItems),
                'total_value' => $totalValue
            ]);

            return response()->json([
                'success' => true,
                'message' => count($transferredItems) . ' items transferred to central store successfully',
                'transferred_items' => count($transferredItems),
                'total_value' => $totalValue,
                'po_completed' => $transferredCount >= $totalPoItems
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error transferring items to central store', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to transfer items'], 500);
        }
    }

    /**
     * Get all central store items with pagination
     */
    public function getAllItems(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $query = CentralStore::with(['supplier:id,company_name', 'purchaseOrder:id,po_number'])
                ->where('company_id', $companyId)
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc');

            // Apply filters if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('item_name', 'like', "%{$search}%")
                      ->orWhere('batch_number', 'like', "%{$search}%")
                      ->orWhere('item_category', 'like', "%{$search}%");
                });
            }

            $items = $query->paginate($perPage, ['*'], 'page', $page);

            // Transform data for frontend
            $transformedItems = $items->getCollection()->map(function($item) {
                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'item_category' => $item->item_category,
                    'brand' => $item->brand,
                    'unit' => $item->unit,
                    'description' => $item->description,
                    'images' => $item->images,
                    'sku' => $item->sku,
                    'barcode' => $item->barcode,
                    'supplier' => [
                        'id' => $item->supplier->id,
                        'company_name' => $item->supplier->company_name
                    ],
                    'purchase_order' => [
                        'id' => $item->purchaseOrder->id,
                        'po_number' => $item->purchaseOrder->po_number
                    ],
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                    'batch_number' => $item->batch_number,
                    'location' => $item->location,
                    'status' => $item->status,
                    'notes' => $item->notes,
                    'transfer_date' => $item->transfer_date,
                    'completed_date' => $item->completed_date,
                    'created_at' => $item->created_at
                ];
            });

            return response()->json([
                'success' => true,
                'items' => $transformedItems,
                'pagination' => [
                    'current_page' => $items->currentPage(),
                    'per_page' => $items->perPage(),
                    'total' => $items->total(),
                    'last_page' => $items->lastPage(),
                    'from' => $items->firstItem(),
                    'to' => $items->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting central store items', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to load items'], 500);
        }
    }

    /**
     * Mark item as completed in central store
     */
    public function markItemCompleted(Request $request, $itemId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            $item = CentralStore::where('id', $itemId)
                ->where('company_id', $companyId)
                ->whereNull('deleted_at')
                ->first();

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Item not found'], 404);
            }

            if ($item->status === 'completed') {
                return response()->json(['success' => false, 'message' => 'Item is already completed'], 400);
            }

            $item->update([
                'status' => 'completed',
                'completed_date' => now()
            ]);

            Log::info('Central store item marked as completed', [
                'item_id' => $itemId,
                'item_name' => $item->item_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item marked as completed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking item as completed', [
                'item_id' => $itemId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to mark item as completed'], 500);
        }
    }

    /**
     * Update a central store item
     */
    public function updateItem(Request $request, $itemId)
    {
        try {
            // Log the request data for debugging
            Log::info('Update item request data', [
                'item_id' => $itemId,
                'request_data' => $request->all(),
                'files' => $request->hasFile('images') ? 'Has images' : 'No images',
                'method' => $request->method(),
                'url' => $request->url(),
                'auth_check' => auth()->check(),
                'user_id' => auth()->user() ? auth()->user()->id : null,
                'company_id' => session('selected_company_id')
            ]);
            
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            $request->validate([
                'item_name' => 'required|string|max:255',
                'brand' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'quantity' => 'required|integer|min:0',
                'unit_price' => 'required|numeric|min:0',
                'batch_number' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'notes' => 'nullable|string',
                'sku' => 'required|string|max:255|unique:central_store,sku,' . $itemId . ',id,company_id,' . $companyId . ',deleted_at,NULL',
                'barcode' => 'nullable|string|max:255|unique:central_store,barcode,' . $itemId . ',id,company_id,' . $companyId . ',deleted_at,NULL',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $item = CentralStore::where('id', $itemId)
                ->where('company_id', $companyId)
                ->whereNull('deleted_at')
                ->first();

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Item not found'], 404);
            }

            // Calculate total price
            $totalPrice = $request->quantity * $request->unit_price;

            // Handle file uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $path = $image->storeAs('central-store-images', $filename, 'public');
                    $imagePaths[] = $path;
                }
            }

            // Get existing images if any
            $existingImages = $item->images ?? [];
            
            // Merge with new images if any uploaded
            if (!empty($imagePaths)) {
                $existingImages = array_merge($existingImages, $imagePaths);
            }

            $item->update([
                'item_name' => $request->item_name,
                'brand' => $request->brand ?? null,
                'description' => $request->description ?? null,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_price' => $totalPrice,
                'batch_number' => $request->batch_number,
                'location' => $request->location,
                'notes' => $request->notes ?? null,
                'sku' => $request->sku,
                'barcode' => $request->barcode ?? null,
                'images' => !empty($existingImages) ? $existingImages : null
            ]);

            Log::info('Central store item updated', [
                'item_id' => $itemId,
                'item_name' => $item->item_name,
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully',
                'item' => $item
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating central store item', [
                'item_id' => $itemId,
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating central store item', [
                'item_id' => $itemId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to update item: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Add a new item directly to central store
     */
    public function addNewItem(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            // Debug: Log all incoming request data
            \Log::info('=== ADD NEW ITEM DEBUG ===');
            \Log::info('Company ID: ' . $companyId);
            \Log::info('Auth User ID: ' . (auth()->id() ?? 'NULL'));
            \Log::info('Session ID: ' . session()->getId());
            \Log::info('All Session Data: ' . json_encode(session()->all()));
            \Log::info('Request data: ' . json_encode($request->all()));
            \Log::info('Request keys: ' . json_encode(array_keys($request->all())));
            \Log::info('Request method: ' . $request->method());
            \Log::info('Content type: ' . $request->header('Content-Type'));
            \Log::info('Request input: ' . json_encode($request->input()));
            
            // Check each required field individually
            $requiredFields = [
                'item_name', 'supplier_id', 'purchase_order_id', 'quantity', 
                'unit_price', 'batch_number', 'location', 'unit'
            ];
            
            foreach ($requiredFields as $field) {
                $value = $request->input($field);
                \Log::info("Field '{$field}': " . ($value === null ? 'NULL' : ($value === '' ? 'EMPTY STRING' : $value)) . " (type: " . gettype($value) . ")");
            }

            // Minimal validation - only check essential fields
            $request->validate([
                'item_name' => 'required|string|max:255',
                'supplier_id' => 'required',
                'purchase_order_id' => 'required',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric|min:0',
                'batch_number' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'unit' => 'required|string|max:50'
            ]);

            // Calculate total price
            $totalPrice = $request->quantity * $request->unit_price;

            // Handle file uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $path = $image->storeAs('central-store-images', $filename, 'public');
                    $imagePaths[] = $path;
                }
            }

            // Get category from inspection data if available
            $inspection = QualityInspection::where('purchase_order_id', $request->purchase_order_id)
                ->where('item_name', $request->item_name)
                ->where('status', 'approved')
                ->whereNull('deleted_at')
                ->first();

            // Check if this item is a re-order
            $purchaseOrder = Wh_PurchaseOrder::findOrFail($request->purchase_order_id);
            $isReorder = false;
            $reorderBatchNumber = null;
            
            if ($purchaseOrder->items && is_array($purchaseOrder->items)) {
                foreach ($purchaseOrder->items as $poItem) {
                    if (($poItem['name'] ?? '') === $request->item_name) {
                        $isReorder = ($poItem['is_reorder'] ?? false) == 1 || ($poItem['is_reorder'] ?? false) === true;
                        $reorderBatchNumber = $poItem['batch_number'] ?? null;
                        break;
                    }
                }
            }

            // If it's a re-order, add to existing batch
            if ($isReorder && $reorderBatchNumber) {
                $existingBatch = CentralStore::where('company_id', $companyId)
                    ->where('batch_number', $reorderBatchNumber)
                    ->first();
                
                if ($existingBatch) {
                    // Add to existing batch
                    $existingBatch->quantity += $request->quantity;
                    $existingBatch->total_price = $existingBatch->quantity * $existingBatch->unit_price;
                    $existingBatch->save();
                    
                    \Log::info('Re-order: Added quantity to existing batch:', [
                        'batch_number' => $reorderBatchNumber,
                        'added_quantity' => $request->quantity,
                        'new_total' => $existingBatch->quantity
                    ]);
                    
                    $centralStoreItem = $existingBatch;
                } else {
                    // Batch not found, create new with re-order batch number
                    $centralStoreItem = CentralStore::create([
                        'company_id' => $companyId,
                        'supplier_id' => $request->supplier_id,
                        'purchase_order_id' => $request->purchase_order_id,
                        'item_name' => $request->item_name,
                        'item_category' => $inspection ? $inspection->item_category : 'Uncategorized',
                        'brand' => $request->brand ?? null,
                        'unit' => $request->unit ?? 'pcs',
                        'description' => $request->description ?? null,
                        'sku' => $request->sku,
                        'barcode' => $request->barcode,
                        'unit_price' => $request->unit_price,
                        'quantity' => $request->quantity,
                        'total_price' => $totalPrice,
                        'batch_number' => $reorderBatchNumber,
                        'location' => $request->location,
                        'status' => 'completed',
                        'notes' => $request->notes,
                        'images' => !empty($imagePaths) ? $imagePaths : null,
                        'created_by' => Auth::id(),
                        'transfer_date' => now()
                    ]);
                }
            } else {
                // Regular item - create new central store item
            $centralStoreItem = CentralStore::create([
                'company_id' => $companyId,
                'supplier_id' => $request->supplier_id,
                'purchase_order_id' => $request->purchase_order_id,
                'item_name' => $request->item_name,
                'item_category' => $inspection ? $inspection->item_category : 'Uncategorized',
                'brand' => $request->brand ?? null,
                'unit' => $request->unit ?? 'pcs',
                'description' => $request->description ?? null,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'unit_price' => $request->unit_price,
                'quantity' => $request->quantity,
                'total_price' => $totalPrice,
                'batch_number' => $request->batch_number,
                'location' => $request->location,
                'status' => 'completed',
                'notes' => $request->notes,
                'images' => !empty($imagePaths) ? $imagePaths : null,
                'created_by' => Auth::id(),
                'transfer_date' => now()
            ]);
            }

            Log::info('New item added to central store', [
                'item_name' => $request->item_name,
                'supplier_id' => $request->supplier_id,
                'po_id' => $request->purchase_order_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item added to central store successfully',
                'item' => $centralStoreItem
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in addNewItem: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false, 
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error adding new item to central store: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Failed to add item to central store: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get central store statistics
     */
    public function getStatistics()
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            $stats = CentralStore::where('company_id', $companyId)
                ->whereNull('deleted_at')
                ->selectRaw('
                    COUNT(*) as total_items,
                    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_items,
                    SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_items,
                    SUM(total_price) as total_value,
                    SUM(CASE WHEN status = "pending" THEN total_price ELSE 0 END) as pending_value,
                    SUM(CASE WHEN status = "completed" THEN total_price ELSE 0 END) as completed_value
                ')
                ->first();

            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting central store statistics', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to load statistics'], 500);
        }
    }

    /**
     * Check if SKU is unique
     */
    public function checkSkuUniqueness(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            $request->validate([
                'sku' => 'required|string|max:255'
            ]);

            $sku = $request->sku;
            
            // Check if SKU exists in central store
            $exists = CentralStore::where('company_id', $companyId)
                ->where('sku', $sku)
                ->whereNull('deleted_at')
                ->exists();

            return response()->json([
                'success' => true,
                'is_unique' => !$exists,
                'sku' => $sku
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking SKU uniqueness', [
                'sku' => $request->sku ?? 'not provided',
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to check SKU uniqueness'], 500);
        }
    }

    /**
     * Check if barcode is unique
     */
    public function checkBarcodeUniqueness(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json(['success' => false, 'message' => 'Company not selected'], 400);
            }

            $request->validate([
                'barcode' => 'required|string|max:255'
            ]);

            $barcode = $request->barcode;
            
            // Check if barcode exists in central store
            $exists = CentralStore::where('company_id', $companyId)
                ->where('barcode', $barcode)
                ->whereNull('deleted_at')
                ->exists();

            return response()->json([
                'success' => true,
                'is_unique' => !$exists,
                'barcode' => $barcode
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking barcode uniqueness', [
                'barcode' => $request->barcode ?? 'not provided',
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to check barcode uniqueness'], 500);
        }
    }

    /**
     * Get available items for requisition from central store
     */
    public function getAvailableItemsForRequisition(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // If no company_id in session, try to use company_id = 1 as fallback
            if (!$companyId) {
                $companyId = 1; // Fallback for testing
                \Log::warning('No company_id in session for getAvailableItemsForRequisition, using fallback company_id = 1');
            }

            \Log::info('Fetching available items for requisition for company_id: ' . $companyId);

            // Get items that are completed and have quantity > 0
            $query = CentralStore::where('company_id', $companyId)
                ->where('status', 'completed')
                ->where('quantity', '>', 0)
                ->whereNull('deleted_at');

            // Apply search filter if provided
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('item_name', 'LIKE', "%{$search}%")
                      ->orWhere('item_category', 'LIKE', "%{$search}%")
                      ->orWhere('brand', 'LIKE', "%{$search}%")
                      ->orWhere('sku', 'LIKE', "%{$search}%");
                });
            }

            // Apply category filter if provided
            if ($request->has('category') && !empty($request->category)) {
                $query->where('item_category', $request->category);
            }

            $items = $query->get([
                'id',
                'item_name as name',
                'item_category as category',
                'brand',
                'sku',
                'barcode',
                'quantity as quantity_available',
                'unit',
                'unit_price',
                'total_price',
                'batch_number',
                'location',
                'description',
                'transfer_date'
            ]);

            \Log::info('Found ' . $items->count() . ' available items for requisition');

            return response()->json([
                'success' => true,
                'data' => $items,
                'debug_info' => [
                    'company_id' => $companyId,
                    'count' => $items->count()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting available items for requisition', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to load available items: ' . $e->getMessage()], 500);
        }
    }
}
