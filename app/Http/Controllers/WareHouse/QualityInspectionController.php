<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use App\Models\QualityInspection;
use App\Models\Wh_Supplier;
use App\Models\Wh_PurchaseOrder;
use App\Models\Category;
use App\Models\CentralStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QualityInspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = Session::get('selected_company_id');
        $inspections = QualityInspection::where('company_id', $companyId)
            ->with(['supplier', 'purchaseOrder'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

       return response()->json([
            'success' => true,
            'inspections' => $inspections->items(),
            'pagination' => [
                'current_page' => $inspections->currentPage(),
                'total' => $inspections->total(),
                'per_page' => $inspections->perPage(),
                'last_page' => $inspections->lastPage()
            ]
        ]);
    }



    public function generateBatchNumber(Request $request)
    {
        $companyId = Session::get('selected_company_id');
        
        // Get the current date components
        $date = Carbon::now()->format('Ymd');
        
        // Get the last batch number for this company
        $lastBatch = QualityInspection::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Extract the sequence number from the last batch or start from 1
        $sequence = 1;
        if ($lastBatch && preg_match('/BATCH-(\d{8})-(\d+)/', $lastBatch->batch_number, $matches)) {
            if ($matches[1] === $date) {
                $sequence = (int)$matches[2] + 1;
            }
        }
        
        // Format the sequence with leading zeros
        $sequenceFormatted = str_pad($sequence, 4, '0', STR_PAD_LEFT);
        
        // Generate the new batch number
        $batchNumber = "BATCH-{$date}-{$sequenceFormatted}";
        
        return response()->json([
            'success' => true,
            'batch_number' => $batchNumber
        ]);
    }




// public function getSuppliersWithPendingPOs()
// {
//     try {
//         $companyId = Session::get('selected_company_id');
        
//         if (!$companyId) {
//             return response()->json(['error' => 'Company not selected'], 400);
//         }

//         // Get suppliers with POs that have uninspected items
//         $suppliers = Wh_Supplier::select(
//                 'wh__suppliers.id',
//                 'wh__suppliers.company_name', 
//                 'wh__suppliers.email',
//                 'wh__suppliers.phone'
//             )
//             ->join('wh__purchase_orders', function($join) use ($companyId) {
//                 $join->on('wh__suppliers.id', '=', 'wh__purchase_orders.supplier_id')
//                      ->where('wh__purchase_orders.company_id', $companyId)
//                      ->whereIn('wh__purchase_orders.status', ['pending', 'processing']);
//             })
            
//             ->where('wh__suppliers.company_id', $companyId)
//             ->where(function($query) {
//                 $query->whereNotExists(function($subquery) {
//                     $subquery->select(DB::raw(1))
//                           ->from('quality_inspections')
//                           ->whereColumn('quality_inspections.purchase_order_id', 'wh__purchase_orders.id');
//                 })
//                 ->orWhereExists(function($subquery) {
//                     $subquery->select(DB::raw(1))
//                           ->from('quality_inspections')
//                           ->whereColumn('quality_inspections.purchase_order_id', 'wh__purchase_orders.id')
//                           ->groupBy('quality_inspections.purchase_order_id')
//                           ->havingRaw('COUNT(DISTINCT quality_inspections.item_name) < (
//                               SELECT COUNT(*) FROM JSON_TABLE(wh__purchase_orders.items, "$[*]" COLUMNS(
//                                   name VARCHAR(255) PATH "$.name"
//                               )) AS items
//                           )');
//                 });
//             })
//             ->groupBy(
//                 'wh__suppliers.id',
//                 'wh__suppliers.company_name',
//                 'wh__suppliers.email',
//                 'wh__suppliers.phone'
//             )
//             ->get();

//         if ($suppliers->isEmpty()) {
//             return response()->json(['message' => 'No suppliers with pending purchase orders containing uninspected items'], 200);
//         }

//         return response()->json($suppliers);

//     } catch (\Exception $e) {
//         \Log::error('Error in getSuppliersWithPendingPOs: '.$e->getMessage());
//         return response()->json(['error' => 'Server error'], 500);
//     }
// }

    /**
     * Get POs for a specific supplier
     */


     public function getSuppliersWithPendingPOs()
{
    try {
        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return response()->json(['error' => 'Company not selected'], 400);
        }

        // Get suppliers with POs that have uninspected items
        // Using a different approach that's compatible with MariaDB
        $suppliers = Wh_Supplier::select(
                'wh__suppliers.id',
                'wh__suppliers.company_name', 
                'wh__suppliers.email',
                'wh__suppliers.phone'
            )
            ->join('wh__purchase_orders', function($join) use ($companyId) {
                $join->on('wh__suppliers.id', '=', 'wh__purchase_orders.supplier_id')
                     ->where('wh__purchase_orders.company_id', $companyId)
                     ->whereIn('wh__purchase_orders.status', ['received', 'processing']);
            })
            ->where('wh__suppliers.company_id', $companyId)
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                      ->from('wh__purchase_orders as po')
                      ->whereColumn('po.supplier_id', 'wh__suppliers.id')
                      ->whereIn('po.status', ['received', 'processing'])
                      ->whereRaw('(
                          SELECT COUNT(DISTINCT qi.item_name) 
                          FROM quality_inspections qi 
                          WHERE qi.purchase_order_id = po.id 
                          AND qi.deleted_at IS NULL
                      ) < JSON_LENGTH(po.items)');
            })
            ->groupBy(
                'wh__suppliers.id',
                'wh__suppliers.company_name',
                'wh__suppliers.email',
                'wh__suppliers.phone'
            )
            ->get();

        if ($suppliers->isEmpty()) {
            return response()->json(['message' => 'No suppliers with purchase orders containing uninspected items'], 200);
        }
 
        // Add debugging information
        \Log::info('Suppliers found for inspection:', [
            'count' => $suppliers->count(),
            'suppliers' => $suppliers->pluck('company_name')->toArray()
        ]);
 
        return response()->json($suppliers);

    } catch (\Exception $e) {
        \Log::error('Error in getSuppliersWithPendingPOs: '.$e->getMessage());
        return response()->json(['error' => 'Server error'], 500);
    }
}

public function getPendingPOsForSupplier($supplierId)
{
    $companyId = Session::get('selected_company_id');
    
                   $pos = Wh_PurchaseOrder::where('supplier_id', $supplierId)
          ->where('company_id', $companyId)
          ->whereIn('status', ['received', 'processing'])
        
        ->withCount(['qualityInspections as inspected_items_count' => function($query) {
            $query->select(DB::raw('count(distinct item_name)'));
        }])
        ->get(['id', 'po_number', 'order_date', 'items', 'status']);

         // Process POs and filter out completed ones
     $filteredPos = $pos->filter(function($po) {
         $totalItems = count($po->items);
         
         // If all items inspected, don't show for new inspections
         if ($po->inspected_items_count >= $totalItems && $totalItems > 0) {
             return false; // Don't include in results
         }
         
         return true; // Keep in results
     });

         // Add debugging information
     \Log::info('POs found for supplier inspection:', [
         'supplier_id' => $supplierId,
         'total_pos' => $pos->count(),
         'filtered_pos' => $filteredPos->count(),
         'pos_details' => $filteredPos->map(function($po) {
             $totalItems = count($po->items);
             return [
                 'po_id' => $po->id,
                 'po_number' => $po->po_number,
                 'status' => $po->status,
                 'total_items' => $totalItems,
                 'inspected_items' => $po->inspected_items_count,
                 'inspection_progress' => $po->inspected_items_count.'/'.$totalItems,
             ];
         })->toArray()
     ]);
 
     return $filteredPos->map(function($po) {
         $totalItems = count($po->items);
         return [
             'id' => $po->id,
             'po_number' => $po->po_number,
             'order_date' => $po->order_date,
             'inspection_progress' => $po->inspected_items_count.'/'.$totalItems,
             'has_uninspected_items' => $po->inspected_items_count < $totalItems,
         ];
     });
}

    /**
     * Get sub-categories for inspection modal
     */
    public function getCategories()
    {
        try {
            $companyId = Session::get('selected_company_id', 1);
            
            \Log::info('Getting categories for company ID: ' . $companyId);
            
            $categories = Category::where('company_id', $companyId)
                ->where('status', 'active')
                ->whereNotNull('sub_categories')
                ->get(['id', 'name', 'code', 'description', 'sub_categories']);
                
            \Log::info('Found categories: ' . $categories->count());

            $subCategories = [];
            
            foreach ($categories as $category) {
                if (!empty($category->sub_categories) && is_array($category->sub_categories)) {
                    foreach ($category->sub_categories as $subCategory) {
                        $subCategories[] = [
                            'id' => $category->id . '_' . $subCategory,
                            'name' => $subCategory,
                            'parent_name' => $category->name,
                            'parent_code' => $category->code,
                            'description' => $category->description
                        ];
                    }
                }
            }

            // Sort sub-categories by name
            usort($subCategories, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });

            return response()->json([
                'success' => true,
                'data' => $subCategories
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching sub-categories for inspection: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load categories'
            ], 500);
        }
    }

    /**
     * Get uninspected items for a PO
     */
    public function getUninspectedItems($poId)
    {
        $po = Wh_PurchaseOrder::findOrFail($poId);
        $inspectedItems = QualityInspection::where('purchase_order_id', $poId)
            ->pluck('item_name')
            ->toArray();

             $uninspectedItems = collect($po->items)
         ->filter(function($item) use ($inspectedItems) {
             return !in_array($item['name'], $inspectedItems);
         })
         ->values();
 
     // Add debugging information
     \Log::info('Uninspected items found:', [
         'po_id' => $poId,
         'po_items' => $po->items,
         'inspected_items' => $inspectedItems,
         'uninspected_items' => $uninspectedItems->toArray(),
         'total_po_items' => count($po->items),
         'total_inspected' => count($inspectedItems),
         'total_uninspected' => $uninspectedItems->count()
     ]);
 
     return response()->json($uninspectedItems);
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
    $validated = $request->validate([
        'supplier_id' => 'required|exists:wh__suppliers,id',
        'purchase_order_id' => 'required|exists:wh__purchase_orders,id',
        'item_name' => 'required|string',
        'item_category' => 'required|string',
        'unit_price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:1',
        'inspection_date' => 'required|date',
        'checklist_results' => 'required|json',
                 'status' => 'required|in:approved,processing,reject',
        'inspection_result' => 'nullable|string',
        'notes' => 'nullable|string',
        'photos' => 'nullable|array',
        'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $companyId = Session::get('selected_company_id');
    
    // Handle file uploads
    $photoPaths = [];
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('quality-inspections', 'public');
            $photoPaths[] = $path;
        }
    }

    // Get the purchase order and its items first to check for re-order
    $purchaseOrder = Wh_PurchaseOrder::findOrFail($validated['purchase_order_id']);
    
    // Check if this item is a re-order
    $isReorder = false;
    $reorderBatchNumber = null;
    
    if ($purchaseOrder->items && is_array($purchaseOrder->items)) {
        foreach ($purchaseOrder->items as $poItem) {
            if (($poItem['name'] ?? '') === $validated['item_name']) {
                $isReorder = ($poItem['is_reorder'] ?? false) == 1 || ($poItem['is_reorder'] ?? false) === true;
                $reorderBatchNumber = $poItem['batch_number'] ?? null;
                break;
            }
        }
    }
    
    // If it's a re-order with a batch number, add quantity to existing batch
    if ($isReorder && $reorderBatchNumber) {
        \Log::info('Processing re-order for batch:', [
            'batch_number' => $reorderBatchNumber,
            'item_name' => $validated['item_name'],
            'new_quantity' => $validated['quantity']
        ]);
        
        // Find existing inspection with this batch number
        $existingInspection = QualityInspection::where('company_id', $companyId)
            ->where('batch_number', $reorderBatchNumber)
            ->first();
        
        if ($existingInspection) {
            // Add to existing batch quantity
            $existingInspection->quantity += $validated['quantity'];
            $existingInspection->total_price = $existingInspection->quantity * $existingInspection->unit_price;
            $existingInspection->save();
            
            \Log::info('Added quantity to existing batch:', [
                'batch_number' => $reorderBatchNumber,
                'old_quantity' => $existingInspection->quantity - $validated['quantity'],
                'added_quantity' => $validated['quantity'],
                'new_quantity' => $existingInspection->quantity
            ]);
            
            // ALSO UPDATE CENTRAL STORE QUANTITY for re-order
            $this->updateCentralStoreForReorder($reorderBatchNumber, $validated['item_name'], $validated['quantity'], $validated['unit_price'], $companyId);
            
            $inspection = $existingInspection;
        } else {
            // Batch not found, create new inspection but use the re-order batch number
            \Log::warning('Re-order batch not found, creating new inspection with batch:', [
                'batch_number' => $reorderBatchNumber
            ]);
            
            $inspection = QualityInspection::create([
                ...$validated,
                'company_id' => $companyId,
                'user_id' => Auth::id(),
                'inspector_name' => Auth::id(),
                'total_price' => $validated['unit_price'] * $validated['quantity'],
                'batch_number' => $reorderBatchNumber, // Use re-order batch number
                'checklist_results' => json_decode($validated['checklist_results'], true),
                'photos' => $photoPaths
            ]);
            
            // ALSO CREATE CENTRAL STORE ITEM for new re-order batch
            $this->updateCentralStoreForReorder($reorderBatchNumber, $validated['item_name'], $validated['quantity'], $validated['unit_price'], $companyId);
        }
    } else {
        // Regular inspection - create new with auto-generated batch number
    $inspection = QualityInspection::create([
        ...$validated,
        'company_id' => $companyId,
        'user_id' => Auth::id(),
        'inspector_name' => Auth::id(),
        'total_price' => $validated['unit_price'] * $validated['quantity'],
        'checklist_results' => json_decode($validated['checklist_results'], true),
        'photos' => $photoPaths
    ]);
    }
    $poItems = collect($purchaseOrder->items)->pluck('name')->toArray();
    
    // Get all inspected items for this PO
    $inspectedItems = QualityInspection::where('purchase_order_id', $purchaseOrder->id)
        ->pluck('item_name')
        ->unique()
        ->toArray();

    // Check if this was the last item to be inspected
    $allItemsInspected = count(array_diff($poItems, $inspectedItems)) === 0;

         // Update PO status accordingly
     if ($allItemsInspected) {
         $purchaseOrder->update(['status' => 'approved']);
     } else {
         // Update to 'processing' if it's still 'received'
         if ($purchaseOrder->status === 'received') {
             $purchaseOrder->update(['status' => 'processing']);
         }
     }

    return response()->json([
        'success' => true,
        'message' => 'Inspection created successfully',
        'data' => $inspection,
                 'po_status_updated' => $allItemsInspected ? 'approved' : 'processing'
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,processing,reject',
            'notes' => 'nullable|string',
        ]);

        $inspection = QualityInspection::findOrFail($id);
        $inspection->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Inspection status updated successfully',
            'data' => $inspection
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $inspection = QualityInspection::with(['supplier', 'purchaseOrder'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $inspection
        ]);
    }

    /**
 * Show the form for editing the specified resource.
 */
public function edit($id)
{
    $inspection = QualityInspection::with(['supplier', 'purchaseOrder'])
        ->findOrFail($id);

    return response()->json([
        'success' => true,
        'data' => $inspection
    ]);
}

/**
 * Update the specified resource in storage.
 */
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'item_name' => 'required|string|max:255',
        'item_category' => 'required|string|max:255',
        'unit_price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:1',
        'inspection_date' => 'required|date',
        'checklist_results' => 'required|json',
        'status' => 'required|in:received,processing,reject',
        'notes' => 'nullable|string',
        'photos' => 'nullable|array',
        'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $inspection = QualityInspection::findOrFail($id);

    // Handle file uploads
    $photoPaths = $inspection->photos ?? [];
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('quality-inspections', 'public');
            $photoPaths[] = [
                'path' => $path,
                'uploaded_at' => now()->toDateTimeString()
            ];
        }
    }

    $inspection->update([
        'item_name' => $validated['item_name'],
        'item_category' => $validated['item_category'],
        'unit_price' => $validated['unit_price'],
        'quantity' => $validated['quantity'],
        'total_price' => $validated['unit_price'] * $validated['quantity'],
        'inspection_date' => $validated['inspection_date'],
        'checklist_results' => json_decode($validated['checklist_results'], true),
        'status' => $validated['status'],
        'notes' => $validated['notes'] ?? null,
        'photos' => $photoPaths
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Inspection updated successfully',
        'data' => $inspection
    ]);
}



/**
 * Remove the specified resource from storage.
 */
public function destroy($id)
{
    DB::beginTransaction();
    try {
        $inspection = QualityInspection::with('purchaseOrder')->findOrFail($id);
        $purchaseOrder = $inspection->purchaseOrder;
        
        // First delete any associated photos from storage
        $this->deleteInspectionPhotos($inspection->photos ?? []);
        
        // Delete the inspection record
        $inspection->delete();
        
        // Only proceed with PO status update if the PO exists
        if ($purchaseOrder) {
            $this->updatePoStatusAfterDeletion($purchaseOrder);
        }
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Inspection deleted successfully'
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error deleting inspection: '.$e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete inspection: '.$e->getMessage()
        ], 500);
    }
}

/**
 * Update PO status after inspection deletion
 */
protected function updatePoStatusAfterDeletion(Wh_PurchaseOrder $purchaseOrder)
{
    // Get all item names from PO (normalized)
    $poItems = collect($purchaseOrder->items)->pluck('name')->map(function ($name) {
        return strtolower(trim($name));
    })->toArray();

    // Get remaining inspected items (normalized)
    $remainingInspectedItems = QualityInspection::where('purchase_order_id', $purchaseOrder->id)
        ->get()
        ->pluck('item_name')
        ->map(function ($name) {
            return strtolower(trim($name));
        })
        ->unique()
        ->toArray();

    // Count how many items are still inspected
    $inspectedCount = count($remainingInspectedItems);
    $totalItems = count($poItems);
    
         // Case 1: No inspections left - set to received (back to receiving stage)
     if ($inspectedCount === 0) {
         $purchaseOrder->update(['status' => 'received']);
         \Log::info("PO {$purchaseOrder->po_number} status set to received - no inspections remain");
     } 
     // Case 2: Some items inspected but not all - set to processing
     elseif ($inspectedCount > 0 && $inspectedCount < $totalItems) {
         $purchaseOrder->update(['status' => 'processing']);
         \Log::info("PO {$purchaseOrder->po_number} status set to processing - {$inspectedCount}/{$totalItems} items inspected");
     }
     // Case 3: All items still inspected - set to approved
     elseif ($inspectedCount === $totalItems) {
         $purchaseOrder->update(['status' => 'approved']);
         \Log::info("PO {$purchaseOrder->po_number} status set to approved - all items still inspected");
     }
}

/**
 * Delete physical photo files from storage
 */
protected function deleteInspectionPhotos(array $photos)
{
    foreach ($photos as $photo) {
        $path = is_array($photo) ? $photo['path'] : $photo;
        if (\Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
        }
    }
}

/**
 * Update Central Store quantity for re-order items
 */
private function updateCentralStoreForReorder($batchNumber, $itemName, $quantity, $unitPrice, $companyId)
{
    try {
        // Find existing central store item with the same batch number and item name
        $existingCentralStoreItem = CentralStore::where('company_id', $companyId)
            ->where('batch_number', $batchNumber)
            ->where('item_name', $itemName)
            ->first();
        
        if ($existingCentralStoreItem) {
            // Add quantity to existing central store item
            $existingCentralStoreItem->quantity += $quantity;
            $existingCentralStoreItem->total_price = $existingCentralStoreItem->quantity * $existingCentralStoreItem->unit_price;
            $existingCentralStoreItem->save();
            
            \Log::info('Updated Central Store quantity for re-order:', [
                'batch_number' => $batchNumber,
                'item_name' => $itemName,
                'added_quantity' => $quantity,
                'new_quantity' => $existingCentralStoreItem->quantity,
                'new_total_price' => $existingCentralStoreItem->total_price
            ]);
        } else {
            // Create new central store item for re-order
            $centralStoreItem = CentralStore::create([
                'company_id' => $companyId,
                'item_name' => $itemName,
                'batch_number' => $batchNumber,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $quantity * $unitPrice,
                'status' => 'completed',
                'created_by' => Auth::id(),
                'transfer_date' => now()
            ]);
            
            \Log::info('Created new Central Store item for re-order:', [
                'batch_number' => $batchNumber,
                'item_name' => $itemName,
                'quantity' => $quantity,
                'total_price' => $centralStoreItem->total_price
            ]);
        }
    } catch (\Exception $e) {
        \Log::error('Error updating Central Store for re-order:', [
            'batch_number' => $batchNumber,
            'item_name' => $itemName,
            'quantity' => $quantity,
            'error' => $e->getMessage()
        ]);
    }
}

}