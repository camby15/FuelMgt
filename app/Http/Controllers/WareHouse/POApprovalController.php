<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wh_PurchaseOrder;
use App\Models\Requisition;
use App\Models\POInvoice;
use App\Models\WarehouseLog;
use App\Models\QualityInspection;
use App\Models\TaxConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class POApprovalController extends Controller
{
    /**
     * Check authentication and company session
     */
    private function checkAuth()
    {
        if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
            return response()->json([
                'error' => true,
                'message' => 'Please login to continue',
                'redirect' => route('auth.login')
            ], 401);
        }

        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return response()->json([
                'error' => true,
                'message' => 'Company session expired. Please login again.',
                'redirect' => route('auth.login')
            ], 401);
        }

        return null;
    }

    /**
     * Get pending purchase orders for approval
     */
    public function getPendingApprovals(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        $approvalType = $request->get('approval_type', ''); // Get the approval type filter
        
        try {
            // Determine what data to fetch based on approval type
            if ($approvalType === 'requisition') {
                // Fetch only requisitions
                $data = $this->getPendingRequisitions($request, $companyId);
                $stats = $this->getRequisitionStats($companyId);
            } elseif ($approvalType === 'purchase_order') {
                // Fetch only purchase orders (existing logic)
                $data = $this->getPendingPurchaseOrders($request, $companyId);
                $stats = $this->getPurchaseOrderStats($companyId);
            } else {
                // Fetch both (default - "All Types")
                $poData = $this->getPendingPurchaseOrders($request, $companyId);
                $reqData = $this->getPendingRequisitions($request, $companyId);
                
                // Combine the data and add type field
                $combinedData = collect();
                
                foreach ($poData['data'] as $po) {
                    $po['type'] = 'purchase_order';
                    $combinedData->push($po);
                }
                
                foreach ($reqData['data'] as $req) {
                    $req['type'] = 'requisition';
                    $combinedData->push($req);
                }
                
                // Sort by created_at desc
                $combinedData = $combinedData->sortByDesc('created_at')->values();
                
                $data = [
                    'data' => $combinedData,
                    'total' => $poData['total'] + $reqData['total'],
                    'current_page' => max($poData['current_page'] ?? 1, $reqData['current_page'] ?? 1),
                    'last_page' => max($poData['last_page'] ?? 1, $reqData['last_page'] ?? 1),
                    'per_page' => $poData['per_page'] ?? 10
                ];
                
                $stats = [
                    'pending_count' => $poData['total'] + $reqData['total'],
                    'approved_today' => ($this->getPurchaseOrderStats($companyId)['approved_today'] ?? 0) + ($this->getRequisitionStats($companyId)['approved_today'] ?? 0),
                    'rejected_today' => ($this->getPurchaseOrderStats($companyId)['rejected_today'] ?? 0) + ($this->getRequisitionStats($companyId)['rejected_today'] ?? 0)
                ];
            }
            
            // Get pagination parameters from request
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            
            // If we have pagination data from the individual methods, use it
            if (isset($data['current_page'])) {
                $meta = [
                    'total' => $data['total'],
                    'per_page' => $data['per_page'],
                    'current_page' => $data['current_page'],
                    'last_page' => $data['last_page']
                ];
            } else {
                // Fallback for combined data (requisitions + POs)
                $meta = [
                    'total' => $data['total'],
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => ceil($data['total'] / $perPage)
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $data['data'],
                'meta' => $meta,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching pending approvals: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pending approvals: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get pending purchase orders
     */
    private function getPendingPurchaseOrders(Request $request, $companyId)
    {
            $query = Wh_PurchaseOrder::with(['supplier', 'createdBy'])
                ->where('company_id', $companyId)
                ->where('status', 'created');

            // Apply filters
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('po_number', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%")
                      ->orWhere('requested_by', 'like', "%{$search}%")
                      ->orWhereHas('supplier', function($sq) use ($search) {
                          $sq->where('company_name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('createdBy', function($sq) use ($search) {
                          $sq->where('fullname', 'like', "%{$search}%");
                      });
                });
            }

            // Apply date range filter
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Apply amount range filter
            if ($request->has('amount_min') && $request->amount_min) {
                $query->where('total_value', '>=', $request->amount_min);
            }

            if ($request->has('amount_max') && $request->amount_max) {
                $query->where('total_value', '<=', $request->amount_max);
            }

            // Get pagination parameters
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            
            $purchaseOrders = $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);

            return [
                'data' => $purchaseOrders->items(),
                'total' => $purchaseOrders->total(),
                'current_page' => $purchaseOrders->currentPage(),
                'last_page' => $purchaseOrders->lastPage(),
                'per_page' => $purchaseOrders->perPage()
            ];
    }
    
    /**
     * Get pending requisitions
     */
    private function getPendingRequisitions(Request $request, $companyId)
    {
        try {
            // Build query with relationships
            $query = Requisition::with(['requestor.personalInfo', 'departmentCategory'])
                ->where('company_id', $companyId)
                ->whereIn('status', ['pending', 'partially_approved']);

        // Apply filters
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('requisition_number', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('requestor', function($sq) use ($search) {
                      $sq->where('fullname', 'like', "%{$search}%");
                  });
            });
        }

        // Apply date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Note: Requisitions don't have amount filtering since they don't have a total_amount field
        // If needed in the future, you could calculate totals from the items JSON field

            // Get pagination parameters
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            
            $requisitions = $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
            
            // Calculate total amount and format requestor name for each requisition
            $requisitions->getCollection()->each(function ($requisition) {
                $totalAmount = 0;
                if ($requisition->items && is_array($requisition->items)) {
                    foreach ($requisition->items as $item) {
                        $quantity = (float) ($item['quantity'] ?? 0);
                        $unitPrice = (float) ($item['unit_price'] ?? 0);
                        $totalAmount += $quantity * $unitPrice;
                    }
                }
                $requisition->total_amount = $totalAmount;
                
                // Format requestor name properly
                if ($requisition->requestor) {
                    if ($requisition->requestor->personalInfo) {
                        $firstName = $requisition->requestor->personalInfo->first_name ?? '';
                        $lastName = $requisition->requestor->personalInfo->last_name ?? '';
                        $requestorName = trim($firstName . ' ' . $lastName);
                        
                        if (empty($requestorName)) {
                            $requestorName = 'Employee #' . $requisition->requestor->staff_id;
                        }
                    } else {
                        $requestorName = 'Employee #' . $requisition->requestor->staff_id;
                    }
                    
                    // Add formatted name to the requisition object
                    $requisition->requestor_name = $requestorName;
                } else {
                    $requisition->requestor_name = 'Unknown';
                }
            });

            return [
                'data' => $requisitions->items(),
                'total' => $requisitions->total(),
                'current_page' => $requisitions->currentPage(),
                'last_page' => $requisitions->lastPage(),
                'per_page' => $requisitions->perPage()
            ];
            
        } catch (\Exception $e) {
            Log::error('Error in getPendingRequisitions: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get purchase order statistics
     */
    private function getPurchaseOrderStats($companyId)
    {
        return [
                'pending_count' => Wh_PurchaseOrder::where('company_id', $companyId)
                    ->where('status', 'created')->count(),
                'approved_today' => Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('status', 'external_approval')
                ->whereDate('approved_at', today())->count(),
                'rejected_today' => Wh_PurchaseOrder::where('company_id', $companyId)
                    ->where('status', 'rejected')
                    ->whereDate('updated_at', today())->count(),
            ];
    }
    
    /**
     * Get requisition statistics
     */
    private function getRequisitionStats($companyId)
    {
        try {
            return [
                'pending_count' => Requisition::where('company_id', $companyId)
                    ->whereIn('status', ['pending', 'partially_approved'])->count(),
                'approved_today' => Requisition::where('company_id', $companyId)
                    ->where('status', 'approved')
                    ->whereDate('updated_at', today())->count(),
                'rejected_today' => Requisition::where('company_id', $companyId)
                    ->where('status', 'rejected')
                    ->whereDate('updated_at', today())->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting requisition stats: ' . $e->getMessage());
            return [
                'pending_count' => 0,
                'approved_today' => 0,
                'rejected_today' => 0,
            ];
        }
    }

    /**
     * Get approval details for a specific purchase order or requisition
     */
    public function getApprovalDetails(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        $type = $request->get('type', 'purchase_order'); // Default to PO for backward compatibility
        
        if ($type === 'requisition') {
            return $this->getRequisitionDetails($id, $companyId);
        } else {
            return $this->getPurchaseOrderDetails($id, $companyId);
        }
    }
    
    /**
     * Get purchase order details
     */
    private function getPurchaseOrderDetails($id, $companyId)
    {
        try {
        $purchaseOrder = Wh_PurchaseOrder::with([
            'supplier', 
            'createdBy', 
            'approvedBy', 
            'invoices.uploadedBy',
            'logs' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])
        ->where('company_id', $companyId)
        ->findOrFail($id);

        return response()->json([
            'success' => true,
                'data' => $purchaseOrder,
                'type' => 'purchase_order'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase order not found'
            ], 404);
        }
    }
    
    /**
     * Get requisition details
     */
    private function getRequisitionDetails($id, $companyId)
    {
        try {
            $requisition = Requisition::with([
                'requestor.personalInfo', 
                'departmentCategory',
                'approver'
            ])
            ->where('company_id', $companyId)
            ->findOrFail($id);
            
            // Calculate total amount if not already set
            if (!isset($requisition->total_amount)) {
                $totalAmount = 0;
                if ($requisition->items && is_array($requisition->items)) {
                    foreach ($requisition->items as $item) {
                        $quantity = (float) ($item['quantity'] ?? 0);
                        $unitPrice = (float) ($item['unit_price'] ?? 0);
                        $totalAmount += $quantity * $unitPrice;
                    }
                }
                $requisition->total_amount = $totalAmount;
            }
            
            // Format requestor name properly for the view modal
            if ($requisition->requestor) {
                if ($requisition->requestor->personalInfo) {
                    $firstName = $requisition->requestor->personalInfo->first_name ?? '';
                    $lastName = $requisition->requestor->personalInfo->last_name ?? '';
                    $requestorName = trim($firstName . ' ' . $lastName);
                    
                    if (empty($requestorName)) {
                        $requestorName = 'Employee #' . $requisition->requestor->staff_id;
                    }
                } else {
                    $requestorName = 'Employee #' . $requisition->requestor->staff_id;
                }
                
                // Add formatted name to the requisition object
                $requisition->requestor_name = $requestorName;
            } else {
                $requisition->requestor_name = 'Unknown';
            }

            return response()->json([
                'success' => true,
                'data' => $requisition,
                'type' => 'requisition'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Requisition not found'
            ], 404);
        }
    }

    /**
     * Approve a purchase order
     */
    public function approve(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $validated = $request->validate([
            'comments' => 'nullable|string|max:1000',
            'priority' => 'nullable|string|in:normal,high,urgent',
            'assign_to' => 'nullable|string',
            'type' => 'required|string|in:purchase_order,requisition',
        ]);

        $companyId = Session::get('selected_company_id');
        $type = $validated['type'];
        
        if ($type === 'requisition') {
            return $this->approveRequisition($request, $id, $validated, $companyId);
        } else {
            return $this->approvePurchaseOrder($request, $id, $validated, $companyId);
        }
    }
    
    /**
     * Approve a purchase order
     */
    private function approvePurchaseOrder(Request $request, $id, $validated, $companyId)
    {
        $purchaseOrder = Wh_PurchaseOrder::where('company_id', $companyId)
            ->where('status', 'created')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            // Update purchase order status
            $purchaseOrder->update([
                'status' => 'external_approval',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Debug logging removed

            // Create approval log
            WarehouseLog::create([
                'purchase_order_id' => $purchaseOrder->id,
                'action' => 'approve_po',
                'description' => 'Purchase order approved' . ($validated['comments'] ? ' - ' . $validated['comments'] : ''),
                'performed_by' => Auth::id(),
                'performed_at' => now(),
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase order approved successfully',
                'data' => $purchaseOrder->fresh(['supplier', 'createdBy', 'approvedBy'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve purchase order: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Approve a requisition
     */
    private function approveRequisition(Request $request, $id, $validated, $companyId)
    {
        $requisition = Requisition::where('company_id', $companyId)
            ->where('status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $itemsNeedingReorder = [];
            $itemsWithStock = [];
            $itemsToDeduct = [];
            
            // Check stock levels and categorize items
            $items = $requisition->items;
            if ($items && is_array($items)) {
                foreach ($items as $item) {
                    $centralStoreItem = \App\Models\CentralStore::find($item['item_id']);
                    if ($centralStoreItem) {
                        $requestedQuantity = (float) ($item['quantity'] ?? 0);
                        $availableQuantity = (float) $centralStoreItem->quantity;
                        
                        if ($requestedQuantity > $availableQuantity) {
                            // Item needs re-order
                            $shortfallQuantity = $requestedQuantity - $availableQuantity;
                            
                            \Log::info('Item needs re-order', [
                                'item_id' => $item['item_id'],
                                'item_name' => $centralStoreItem->item_name,
                                'requested_quantity' => $requestedQuantity,
                                'available_quantity' => $availableQuantity,
                                'shortfall_quantity' => $shortfallQuantity
                            ]);
                            
                            $itemsNeedingReorder[] = [
                                'item_id' => $item['item_id'],
                                'item_name' => $centralStoreItem->item_name,
                                'batch_number' => $centralStoreItem->batch_number,
                                'requested_quantity' => $requestedQuantity,
                                'available_quantity' => $availableQuantity,
                                'shortfall_quantity' => $shortfallQuantity,
                                'unit_price' => $centralStoreItem->unit_price,
                                'central_store_item' => $centralStoreItem
                            ];
                            
                            // If there's some available quantity, deduct it now
                            if ($availableQuantity > 0) {
                                $itemsToDeduct[] = [
                                    'item_id' => $item['item_id'],
                                    'quantity_to_deduct' => $availableQuantity,
                                    'central_store_item' => $centralStoreItem
                                ];
                                
                                $itemsWithStock[] = [
                                    'item_id' => $item['item_id'],
                                    'item_name' => $centralStoreItem->item_name,
                                    'requested_quantity' => $requestedQuantity,
                                    'available_quantity' => $availableQuantity,
                                    'shortfall_quantity' => $shortfallQuantity
                                ];
                            }
                        } else {
                            // Item has sufficient stock - approve immediately
                            \Log::info('Item has sufficient stock', [
                                'item_id' => $item['item_id'],
                                'item_name' => $centralStoreItem->item_name,
                                'requested_quantity' => $requestedQuantity,
                                'available_quantity' => $availableQuantity
                            ]);
                            $itemsToDeduct[] = [
                                'item_id' => $item['item_id'],
                                'quantity_to_deduct' => $requestedQuantity,
                                'central_store_item' => $centralStoreItem
                            ];
                            
                            $itemsWithStock[] = [
                                'item_id' => $item['item_id'],
                                'item_name' => $centralStoreItem->item_name,
                                'requested_quantity' => $requestedQuantity,
                                'available_quantity' => $availableQuantity,
                                'shortfall_quantity' => 0
                            ];
                        }
                    }
                }
            }
            
            // If items need re-order, auto-create PO but keep requisition pending
            $createdPO = null;
            if (!empty($itemsNeedingReorder)) {
                \Log::info('Attempting to create re-order PO', [
                    'items_count' => count($itemsNeedingReorder),
                    'company_id' => $companyId
                ]);
                
                $createdPO = $this->autoCreateReorderPO($itemsNeedingReorder, $companyId);
                
                \Log::info('Re-order PO creation result', [
                    'created_po' => $createdPO ? $createdPO->po_number : 'NULL',
                    'po_id' => $createdPO ? $createdPO->id : 'NULL'
                ]);
                
                // Add more debug info for frontend
                $debugMessage = 'Function called successfully';
                if (!$createdPO) {
                    $debugMessage = 'PO creation failed - Could not find original PO details for batch number. Please ensure the batch number exists in QualityInspection or CentralStore records.';
                }
                
                // Add debug info to response
                $debugInfo = [
                    'po_created' => $createdPO ? true : false,
                    'po_id' => $createdPO ? $createdPO->id : null,
                    'po_number' => $createdPO ? $createdPO->po_number : null,
                    'items_count' => count($itemsNeedingReorder),
                    'function_called' => true,
                    'debug_step' => $createdPO ? 'po_created_successfully' : 'po_creation_failed',
                    'function_entry_logged' => true,
                    'batch_number' => $itemsNeedingReorder[0]['batch_number'] ?? 'unknown',
                    'debug_message' => $debugMessage,
                    'batch_lookup_method' => 'batch_number_to_original_po',
                    'supplier_source' => $createdPO ? 'from_original_po' : 'not_found'
                ];
                
                // Keep requisition as pending since we need to wait for re-order PO
                DB::commit();
                
                // Handle partial approval response
                $approvedItemsCount = count($itemsWithStock);
                $reorderItemsCount = count($itemsNeedingReorder);
                
                \Log::info('Approval counts check', [
                    'approved_items_count' => $approvedItemsCount,
                    'reorder_items_count' => $reorderItemsCount,
                    'total_items' => count($items),
                    'requisition_id' => $id
                ]);
                
                if ($createdPO) {
                    if ($approvedItemsCount > 0 && $reorderItemsCount > 0) {
                        // Partial approval: some items approved, some need re-order
                        \Log::info('Setting status to partially_approved', [
                            'approved_items_count' => $approvedItemsCount,
                            'reorder_items_count' => $reorderItemsCount,
                            'requisition_id' => $id
                        ]);
                        $requisition->update([
                            'status' => 'partially_approved',
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);
                        
                        // Deduct inventory for approved items
                        foreach ($itemsToDeduct as $deductionItem) {
                            $centralStoreItem = $deductionItem['central_store_item'];
                            $quantityToDeduct = $deductionItem['quantity_to_deduct'];
                            $newQuantity = $centralStoreItem->quantity - $quantityToDeduct;
                            
                            $centralStoreItem->update(['quantity' => max(0, $newQuantity)]);
                            
                            Log::info('Inventory deducted on partial requisition approval', [
                                'item_id' => $deductionItem['item_id'],
                                'item_name' => $centralStoreItem->item_name,
                                'quantity_deducted' => $quantityToDeduct,
                                'new_quantity' => $newQuantity,
                                'requisition_id' => $id,
                                'approved_by' => Auth::id()
                            ]);
                        }
                        
                        \Log::info('Final decision: Partial approval', [
                            'requisition_id' => $id,
                            'final_status' => 'partially_approved',
                            'approved_items' => $approvedItemsCount,
                            'reorder_items' => $reorderItemsCount,
                            'po_created' => $createdPO->po_number
                        ]);
                        
                        return response()->json([
                            'success' => true,
                            'message' => "Requisition partially approved! {$approvedItemsCount} item(s) approved and inventory deducted. {$reorderItemsCount} item(s) need re-order - Purchase Order {$createdPO->po_number} created.",
                            'created_po' => $createdPO->po_number,
                            'items_approved' => $itemsWithStock,
                            'items_needing_reorder' => $itemsNeedingReorder,
                            'approval_type' => 'partial',
                            'debug_info' => $debugInfo
                        ]);
                    } else {
                        // All items need re-order - keep status as 'pending' (don't change status)
                        // Don't update requisition status - keep it as 'pending'
                        \Log::info('All items need re-order, keeping status as pending', [
                            'approved_items_count' => $approvedItemsCount,
                            'reorder_items_count' => $reorderItemsCount,
                            'requisition_id' => $id
                        ]);
                        
                        \Log::info('Final decision: All items need re-order', [
                            'requisition_id' => $id,
                            'final_status' => 'pending (no change)',
                            'po_created' => $createdPO->po_number
                        ]);
                        
                        return response()->json([
                            'success' => true,
                            'message' => 'Requisition requires re-order. A new Purchase Order has been created for the shortfall items. The requisition remains pending until the re-order is fulfilled.',
                            'created_po' => $createdPO->po_number,
                            'items_needing_reorder' => $itemsNeedingReorder,
                            'approval_type' => 'full_reorder',
                            'debug_info' => $debugInfo
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Requisition requires re-order but no Purchase Order could be created. Could not find original PO details for the batch number. Please ensure the items were properly received and inspected.',
                        'created_po' => null,
                        'items_needing_reorder' => $itemsNeedingReorder,
                        'debug_info' => $debugInfo
                    ]);
                }
            }
            
            // Full approval: all items have sufficient stock
            $requisition->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Deduct inventory quantities for all items
            foreach ($itemsToDeduct as $deductionItem) {
                $centralStoreItem = $deductionItem['central_store_item'];
                $quantityToDeduct = $deductionItem['quantity_to_deduct'];
                $newQuantity = $centralStoreItem->quantity - $quantityToDeduct;
                
                $centralStoreItem->update(['quantity' => max(0, $newQuantity)]);
                
                Log::info('Inventory deducted on full requisition approval', [
                    'item_id' => $deductionItem['item_id'],
                    'item_name' => $centralStoreItem->item_name,
                    'quantity_deducted' => $quantityToDeduct,
                    'new_quantity' => $newQuantity,
                    'requisition_id' => $id,
                    'approved_by' => Auth::id()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Requisition fully approved successfully! All items have sufficient stock.',
                'items_approved' => $itemsWithStock,
                'approval_type' => 'full',
                'data' => $requisition->fresh(['requestor.personalInfo', 'departmentCategory'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve requisition: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get any existing supplier for the company, or create a basic one if none exists
     */
    private function getAnySupplierForCompany($companyId)
    {
        // Try to find any existing supplier for this company
        $existingSupplier = \App\Models\Wh_Supplier::where('company_id', $companyId)->first();
        if ($existingSupplier) {
            \Log::info('Found existing supplier for company', [
                'supplier_id' => $existingSupplier->id,
                'supplier_name' => $existingSupplier->company_name,
                'company_id' => $companyId
            ]);
            return $existingSupplier;
        }
        
        \Log::warning('No suppliers found for company, attempting to create basic supplier', [
            'company_id' => $companyId
        ]);
        
        // Create a basic supplier with minimal required fields
        try {
            $basicSupplier = \App\Models\Wh_Supplier::create([
                'company_id' => $companyId,
                'company_name' => 'Auto-Generated Supplier',
                'primary_contact' => 'System',
                'email' => 'auto@supplier.local',
                'phone' => '000-000-0000',
                'status' => 'active',
                'user_id' => Auth::id()
            ]);
            
            \Log::info('Created basic supplier for company', [
                'supplier_id' => $basicSupplier->id,
                'company_id' => $companyId
            ]);
            
            return $basicSupplier;
        } catch (\Exception $e) {
            \Log::error('Failed to create basic supplier', [
                'error' => $e->getMessage(),
                'company_id' => $companyId
            ]);
            return null;
        }
    }

    /**
     * Auto-create re-order PO for items with insufficient stock
     */
    private function autoCreateReorderPO($itemsNeedingReorder, $companyId)
    {
        \Log::info('=== AUTO CREATE RE-ORDER PO CALLED ===', [
            'items_count' => count($itemsNeedingReorder),
            'company_id' => $companyId,
            'first_item' => $itemsNeedingReorder[0] ?? 'no items'
        ]);
        
        try {
            // Get the first item to find original PO details
            $firstItem = $itemsNeedingReorder[0];
            $batchNumber = $firstItem['batch_number'];
            
            // Find the QualityInspection record to get the original PO details
            \Log::info('Looking for QualityInspection record with batch number', [
                'batch_number' => $batchNumber,
                'company_id' => $companyId
            ]);
            
            // Debug: Show all available batch numbers in QualityInspection
            $allBatches = QualityInspection::where('company_id', $companyId)->pluck('batch_number')->toArray();
            \Log::info('All available batch numbers in QualityInspection', [
                'count' => count($allBatches),
                'batches' => $allBatches,
                'looking_for' => $batchNumber
            ]);
            
            // Also check Central Store batch numbers
            $centralStoreBatches = \App\Models\CentralStore::where('company_id', $companyId)->pluck('batch_number')->toArray();
            \Log::info('All available batch numbers in CentralStore', [
                'count' => count($centralStoreBatches),
                'batches' => $centralStoreBatches
            ]);
            
            // First try to find in QualityInspection (primary source)
            $inspection = QualityInspection::where('batch_number', $batchNumber)
                ->where('company_id', $companyId)
                ->first();
            
            $originalPO = null;
            $supplierId = null;
            $itemName = $firstItem['item_name'];
            
            if ($inspection && $inspection->purchase_order_id) {
                \Log::info('Found QualityInspection record, getting original PO', [
                    'batch_number' => $batchNumber,
                    'inspection_id' => $inspection->id,
                    'purchase_order_id' => $inspection->purchase_order_id
                ]);
                
                $originalPO = Wh_PurchaseOrder::find($inspection->purchase_order_id);
                if ($originalPO) {
                    $supplierId = $originalPO->supplier_id;
                    $itemName = $inspection->item_name;
                    
                    \Log::info('Found original PO from QualityInspection', [
                        'po_id' => $originalPO->id,
                        'po_number' => $originalPO->po_number,
                        'supplier_id' => $originalPO->supplier_id
                    ]);
                }
            }
            
            // If not found in QualityInspection, try CentralStore
            if (!$originalPO) {
                \Log::info('Not found in QualityInspection, trying CentralStore', [
                    'batch_number' => $batchNumber,
                    'company_id' => $companyId
                ]);
                
                $centralStoreItem = \App\Models\CentralStore::where('batch_number', $batchNumber)
                    ->where('company_id', $companyId)
                    ->first();
                
                if ($centralStoreItem && $centralStoreItem->purchase_order_id) {
                    \Log::info('Found CentralStore record, getting original PO', [
                        'batch_number' => $batchNumber,
                        'central_store_id' => $centralStoreItem->id,
                        'purchase_order_id' => $centralStoreItem->purchase_order_id
                    ]);
                    
                    $originalPO = Wh_PurchaseOrder::find($centralStoreItem->purchase_order_id);
                    if ($originalPO) {
                        $supplierId = $originalPO->supplier_id;
                        $itemName = $centralStoreItem->item_name;
                        
                        \Log::info('Found original PO from CentralStore', [
                            'po_id' => $originalPO->id,
                            'po_number' => $originalPO->po_number,
                            'supplier_id' => $originalPO->supplier_id
                        ]);
                    }
                }
            }
            
            // If still no original PO found, we can't proceed
            if (!$originalPO) {
                \Log::error('Could not find original PO for batch number', [
                    'batch_number' => $batchNumber,
                    'company_id' => $companyId,
                    'inspection_found' => $inspection ? 'yes' : 'no',
                    'central_store_found' => isset($centralStoreItem) ? 'yes' : 'no'
                ]);
                return null;
            }
            
            // Final check - if we still don't have a supplier, we can't create the PO
            if (!$supplierId) {
                \Log::error('Original PO found but no supplier_id', [
                    'po_id' => $originalPO->id,
                    'po_number' => $originalPO->po_number,
                    'batch_number' => $batchNumber
                ]);
                return null;
            }
            
            // Now we have the original PO and supplier, let's create the re-order PO
            
            
            // Generate new PO number
            $datePrefix = date('Ymd');
            $maxNumber = Wh_PurchaseOrder::withTrashed()
                ->where('company_id', $companyId)
                ->where('po_number', 'like', 'PO-'.$datePrefix.'-%')
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(po_number, '-', -1) AS UNSIGNED)) as max_num")
                ->value('max_num');
            $nextNumber = ($maxNumber ? $maxNumber + 1 : 1);
            $poNumber = 'PO-' . $datePrefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            
            // Prepare re-order items
            $reorderItems = collect($itemsNeedingReorder)->map(function($item) {
                return [
                    'name' => $item['item_name'],
                    'quantity' => $item['shortfall_quantity'], // Only order the shortfall
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['shortfall_quantity'] * $item['unit_price'],
                    'category' => $item['central_store_item']->item_category ?? 'general',
                    'is_reorder' => true,
                    'batch_number' => $item['batch_number'],
                    'reorder_reason' => 'Auto-reorder: Insufficient stock for requisition'
                ];
            });
            
            $subtotal = $reorderItems->sum('total_price');
            $totalAmount = $subtotal; // No tax for re-order PO
            
            \Log::info('About to create re-order PO', [
                'po_number' => $poNumber,
                'supplier_id' => $supplierId,
                'items_count' => $reorderItems->count(),
                'subtotal' => $subtotal
            ]);
            
            // Validate that we have a valid supplier before creating PO
            $supplier = \App\Models\Wh_Supplier::find($supplierId);
            if (!$supplier) {
                \Log::error('Supplier not found for re-order PO creation', [
                    'supplier_id' => $supplierId,
                    'company_id' => $companyId
                ]);
                return null;
            }
            
            // Create re-order PO using original PO details
            $reorderPO = Wh_PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $supplierId,
                'order_date' => now(),
                'delivery_date' => now()->addDays(14), // Default 14 days
                'status' => 'created',
                'payment_terms' => $originalPO->payment_terms ?? 'Net 30', // Use original PO payment terms
                'notes' => 'Auto-generated re-order PO due to insufficient stock (Based on: ' . $originalPO->po_number . ')',
                'requested_by' => Auth::user()->fullname ?? Auth::user()->name ?? 'System',
                'items' => $reorderItems->toArray(),
                'total_value' => $subtotal,
                'total_items' => $reorderItems->count(),
                'created_by' => Auth::id(),
                'company_id' => $companyId,
                'user_id' => Auth::id(),
                // Use original PO's tax configuration
                'tax_configuration_id' => $originalPO->tax_configuration_id,
                'tax_type' => $originalPO->tax_type,
                'tax_rate' => $originalPO->tax_rate,
                'subtotal' => $subtotal,
                'tax_amount' => $originalPO->tax_configuration_id ? ($subtotal * ($originalPO->tax_rate / 100)) : 0,
                'total_amount' => $originalPO->tax_configuration_id ? ($subtotal + ($subtotal * ($originalPO->tax_rate / 100))) : $subtotal,
                'is_tax_exempt' => $originalPO->is_tax_exempt ?? false,
                'tax_exemption_reason' => $originalPO->tax_exemption_reason,
            ]);
            
            \Log::info('Auto-created re-order PO', [
                'po_number' => $poNumber,
                'items_count' => $reorderItems->count(),
                'total_amount' => $totalAmount,
                'supplier_id' => $supplierId,
                'batch_number' => $batchNumber
            ]);
            
            return $reorderPO;
            
        } catch (\Exception $e) {
            \Log::error('Failed to auto-create re-order PO', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
    
    /**
     * Get all requisitions for the "All Requisitions" tab
     */
    public function getAllRequisitions(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                return response()->json(['error' => 'Company not selected'], 400);
            }

            // Build query with relationships
            $query = Requisition::with(['requestor.personalInfo', 'departmentCategory'])
                ->where('company_id', $companyId);

            // Apply filters
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('requisition_number', 'like', "%{$search}%")
                      ->orWhere('title', 'like', "%{$search}%")
                      ->orWhere('department', 'like', "%{$search}%");
                });
            }

            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Get pagination parameters
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            
            $requisitions = $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
            
            // Calculate total amount and format requestor name for each requisition
            $requisitions->each(function ($requisition) {
                $totalAmount = 0;
                if ($requisition->items && is_array($requisition->items)) {
                    foreach ($requisition->items as $item) {
                        $quantity = (float) ($item['quantity'] ?? 0);
                        $unitPrice = (float) ($item['unit_price'] ?? 0);
                        $totalAmount += $quantity * $unitPrice;
                    }
                }
                $requisition->total_amount = $totalAmount;
                
                // Format requestor name properly
                if ($requisition->requestor) {
                    if ($requisition->requestor->personalInfo) {
                        $firstName = $requisition->requestor->personalInfo->first_name ?? '';
                        $lastName = $requisition->requestor->personalInfo->last_name ?? '';
                        $requestorName = trim($firstName . ' ' . $lastName);
                        
                        if (empty($requestorName)) {
                            $requestorName = 'Employee #' . $requisition->requestor->staff_id;
                        }
                    } else {
                        $requestorName = 'Employee #' . $requisition->requestor->staff_id;
                    }
                    
                    // Add formatted name to the requisition object
                    $requisition->requestor_name = $requestorName;
                } else {
                    $requisition->requestor_name = 'Unknown';
                }
            });

            return response()->json([
                'data' => $requisitions->items(),
                'total' => $requisitions->total(),
                'per_page' => $requisitions->perPage(),
                'current_page' => $requisitions->currentPage(),
                'last_page' => $requisitions->lastPage(),
                'from' => $requisitions->firstItem(),
                'to' => $requisitions->lastItem(),
                'has_more_pages' => $requisitions->hasMorePages()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching all requisitions: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch requisitions'], 500);
        }
    }

    /**
     * Get procurement dashboard statistics
     */
    public function getProcurementStatistics(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                return response()->json(['error' => 'Company not selected'], 400);
            }

            // Get current month and last month dates
            $currentMonth = now()->startOfMonth();
            $lastMonth = now()->subMonth()->startOfMonth();
            $lastMonthEnd = now()->subMonth()->endOfMonth();

            // Pending Approvals (POs + Requisitions)
            $pendingPOs = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('status', 'created')
                ->count();
            
            $pendingRequisitions = Requisition::where('company_id', $companyId)
                ->where('status', 'pending')
                ->count();
            
            $pendingApprovals = $pendingPOs + $pendingRequisitions;

            // New pending items (last 7 days)
            $newPendingPOs = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('status', 'created')
                ->where('created_at', '>=', now()->subDays(7))
                ->count();
            
            $newPendingRequisitions = Requisition::where('company_id', $companyId)
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subDays(7))
                ->count();
            
            $newPending = $newPendingPOs + $newPendingRequisitions;

            // Approved This Month (POs + Requisitions)
            $approvedPOs = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('status', 'external_approval')
                ->where('approved_at', '>=', $currentMonth)
                ->count();
            
            $approvedRequisitions = Requisition::where('company_id', $companyId)
                ->where('status', 'approved')
                ->where('approved_at', '>=', $currentMonth)
                ->count();
            
            $approvedThisMonth = $approvedPOs + $approvedRequisitions;

            // Approved Last Month for comparison
            $approvedLastMonth = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('status', 'external_approval')
                ->whereBetween('approved_at', [$lastMonth, $lastMonthEnd])
                ->count() + 
                Requisition::where('company_id', $companyId)
                ->where('status', 'approved')
                ->whereBetween('approved_at', [$lastMonth, $lastMonthEnd])
                ->count();

            // Calculate percentage change
            $approvedChangePercent = $approvedLastMonth > 0 ? 
                round((($approvedThisMonth - $approvedLastMonth) / $approvedLastMonth) * 100, 1) : 0;

            // Total Spend This Month - Calculate from items
            $totalSpendThisMonth = 0;
            
            // PO spend this month
            $posThisMonth = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('status', 'external_approval')
                ->where('approved_at', '>=', $currentMonth)
                ->get();
            
            foreach ($posThisMonth as $po) {
                if ($po->items && is_array($po->items)) {
                    foreach ($po->items as $item) {
                        $quantity = (float) ($item['quantity'] ?? 0);
                        $unitPrice = (float) ($item['unit_price'] ?? 0);
                        $totalSpendThisMonth += $quantity * $unitPrice;
                    }
                }
            }
            
            // Requisition spend this month
            $requisitionsThisMonth = Requisition::where('company_id', $companyId)
                ->where('status', 'approved')
                ->where('approved_at', '>=', $currentMonth)
                ->get();
            
            foreach ($requisitionsThisMonth as $req) {
                if ($req->items && is_array($req->items)) {
                    foreach ($req->items as $item) {
                        $quantity = (float) ($item['quantity'] ?? 0);
                        $unitPrice = (float) ($item['unit_price'] ?? 0);
                        $totalSpendThisMonth += $quantity * $unitPrice;
                    }
                }
            }

            // Total Spend Last Month - Calculate from items
            $totalSpendLastMonth = 0;
            
            // PO spend last month
            $posLastMonth = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('status', 'external_approval')
                ->whereBetween('approved_at', [$lastMonth, $lastMonthEnd])
                ->get();
            
            foreach ($posLastMonth as $po) {
                if ($po->items && is_array($po->items)) {
                    foreach ($po->items as $item) {
                        $quantity = (float) ($item['quantity'] ?? 0);
                        $unitPrice = (float) ($item['unit_price'] ?? 0);
                        $totalSpendLastMonth += $quantity * $unitPrice;
                    }
                }
            }
            
            // Requisition spend last month
            $requisitionsLastMonth = Requisition::where('company_id', $companyId)
                ->where('status', 'approved')
                ->whereBetween('approved_at', [$lastMonth, $lastMonthEnd])
                ->get();
            
            foreach ($requisitionsLastMonth as $req) {
                if ($req->items && is_array($req->items)) {
                    foreach ($req->items as $item) {
                        $quantity = (float) ($item['quantity'] ?? 0);
                        $unitPrice = (float) ($item['unit_price'] ?? 0);
                        $totalSpendLastMonth += $quantity * $unitPrice;
                    }
                }
            }

            // Calculate spend change percentage
            $spendChangePercent = $totalSpendLastMonth > 0 ? 
                round((($totalSpendThisMonth - $totalSpendLastMonth) / $totalSpendLastMonth) * 100, 1) : 0;

            // Vendors count
            $vendors = \App\Models\Wh_Supplier::where('company_id', $companyId)->count();

            // New vendors (last 30 days)
            $newVendors = \App\Models\Wh_Supplier::where('company_id', $companyId)
                ->where('created_at', '>=', now()->subDays(30))
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'pending_approvals' => $pendingApprovals,
                    'pending_approvals_new' => $newPending,
                    'approved_this_month' => $approvedThisMonth,
                    'approved_change_percent' => $approvedChangePercent,
                    'total_spend' => $totalSpendThisMonth,
                    'spend_change_percent' => $spendChangePercent,
                    'vendors' => $vendors,
                    'vendors_new' => $newVendors
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching procurement statistics: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to fetch statistics',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Validate inventory availability for requisition approval
     */
    private function validateInventoryAvailability($requisition)
    {
        $items = $requisition->items;
        $errors = [];
        
        if (!$items || !is_array($items)) {
            return [
                'success' => false,
                'message' => 'No items found in requisition',
                'errors' => ['No items to validate']
            ];
        }
        
        foreach ($items as $item) {
            $itemId = $item['item_id'] ?? null;
            $requestedQuantity = (float) ($item['quantity'] ?? 0);
            $itemName = $item['item_name'] ?? 'Unknown Item';
            
            if (!$itemId || $requestedQuantity <= 0) {
                continue; // Skip invalid items
            }
            
            $centralStoreItem = \App\Models\CentralStore::find($itemId);
            
            if (!$centralStoreItem) {
                $errors[] = "Item '{$itemName}' not found in inventory";
                continue;
            }
            
            $availableQuantity = (float) $centralStoreItem->quantity;
            
            if ($availableQuantity <= 0) {
                $errors[] = "Item '{$itemName}' is out of stock (Available: 0)";
            } elseif ($requestedQuantity > $availableQuantity) {
                $errors[] = "Insufficient stock for '{$itemName}' (Requested: {$requestedQuantity}, Available: {$availableQuantity})";
            }
        }
        
        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => 'Cannot approve requisition due to inventory issues',
                'errors' => $errors
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Inventory validation passed'
        ];
    }

    /**
     * Reject a purchase order
     */
    public function reject(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $validated = $request->validate([
            'comments' => 'required|string|max:1000',
            'reason' => 'nullable|string|max:255',
            'type' => 'required|string|in:purchase_order,requisition',
        ]);

        $companyId = Session::get('selected_company_id');
        $type = $validated['type'];
        
        if ($type === 'requisition') {
            return $this->rejectRequisition($request, $id, $validated, $companyId);
        } else {
            return $this->rejectPurchaseOrder($request, $id, $validated, $companyId);
        }
    }
    
    /**
     * Reject a purchase order
     */
    private function rejectPurchaseOrder(Request $request, $id, $validated, $companyId)
    {
        $purchaseOrder = Wh_PurchaseOrder::where('company_id', $companyId)
            ->where('status', 'created')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            // Update purchase order status
            $purchaseOrder->update([
                'status' => 'rejected',
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $validated['reason'] ?? null,
            ]);

            // Create rejection log
            WarehouseLog::create([
                'purchase_order_id' => $purchaseOrder->id,
                'action' => 'reject_po',
                'description' => 'Purchase order rejected - ' . $validated['comments'],
                'performed_by' => Auth::id(),
                'performed_at' => now(),
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase order rejected successfully',
                'data' => $purchaseOrder->fresh(['supplier', 'createdBy'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject purchase order: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reject a requisition
     */
    private function rejectRequisition(Request $request, $id, $validated, $companyId)
    {
        $requisition = Requisition::where('company_id', $companyId)
            ->where('status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            // Update requisition status
            $requisition->update([
                'status' => 'rejected',
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $validated['reason'] ?? null,
            ]);

            // You can add logging here if you have a requisition log table

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Requisition rejected successfully',
                'data' => $requisition->fresh(['requestor.personalInfo', 'departmentCategory'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject requisition: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload invoice files for a purchase order
     */
    public function uploadInvoice(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        try {
            // Debug: Log all request data
            \Log::info('Invoice upload request data:', [
                'all_files' => $request->allFiles(),
                'files_key' => $request->file('files'),
                'has_files' => $request->hasFile('files'),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0
            ]);
            
            // Check if files are sent as files[] or files.*
            $files = $request->file('files');
            if (!$files || (is_array($files) && empty(array_filter($files)))) {
                \Log::error('No files provided in request');
                return response()->json([
                    'success' => false,
                    'message' => 'No files provided'
                ], 422);
            }

            $validated = $request->validate([
                'files' => 'required|array|min:1',
                'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
                'notes' => 'nullable|string|max:1000',
            ]);
            
            \Log::info('Validation passed, files count: ' . count($validated['files']));
        } catch (\Exception $e) {
            \Log::error('Validation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage()
            ], 422);
        }

        $companyId = Session::get('selected_company_id');
        
        $purchaseOrder = Wh_PurchaseOrder::where('company_id', $companyId)
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $uploadedFiles = [];

            foreach ($request->file('files') as $file) {
                // Generate unique filename
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Store file
                $filePath = $file->storeAs('po_invoices/' . $purchaseOrder->id, $fileName, 'public');
                
                // Create invoice record
                $invoice = POInvoice::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'file_name' => $fileName,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'notes' => $validated['notes'] ?? null,
                    'uploaded_by' => Auth::id(),
                    'company_id' => $companyId,
                ]);

                $uploadedFiles[] = $invoice;
            }

            // Create upload log
            WarehouseLog::create([
                'purchase_order_id' => $purchaseOrder->id,
                'action' => 'upload_invoice',
                'description' => 'Invoice files uploaded - ' . count($uploadedFiles) . ' files',
                'performed_by' => Auth::id(),
                'performed_at' => now(),
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice files uploaded successfully',
                'data' => $uploadedFiles
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload invoice files: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an invoice file
     */
    public function deleteInvoice($poId, $invoiceId)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        $invoice = POInvoice::where('company_id', $companyId)
            ->where('purchase_order_id', $poId)
            ->findOrFail($invoiceId);

        DB::beginTransaction();
        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($invoice->file_path)) {
                Storage::disk('public')->delete($invoice->file_path);
            }

            // Delete invoice record
            $invoice->delete();

            // Create deletion log
            WarehouseLog::create([
                'purchase_order_id' => $poId,
                'action' => 'delete_invoice',
                'description' => 'Invoice file deleted - ' . $invoice->original_name,
                'performed_by' => Auth::id(),
                'performed_at' => now(),
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice file deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete invoice file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test upload method for debugging
     */
    public function testUpload($id)
    {
        \Log::info('Test upload called for PO ID: ' . $id);
        
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        $purchaseOrder = Wh_PurchaseOrder::where('company_id', $companyId)
            ->find($id);

        if (!$purchaseOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Test upload endpoint working',
            'po_id' => $id,
            'company_id' => $companyId,
            'po_exists' => true
        ]);
    }

    /**
     * Download an invoice file
     */
    public function downloadInvoice($poId, $invoiceId)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $invoice = POInvoice::where('purchase_order_id', $poId)
                ->where('id', $invoiceId)
                ->where('company_id', $companyId)
                ->first();

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found'
                ], 404);
            }

            // Check if file exists in public disk
            if (Storage::disk('public')->exists($invoice->file_path)) {
                return Storage::disk('public')->download($invoice->file_path, $invoice->original_name);
            }
            
            // Fallback to local storage
            $filePath = storage_path('app/public/' . $invoice->file_path);
            if (file_exists($filePath)) {
                return response()->download($filePath, $invoice->original_name);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found on server'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batch check invoice status for multiple POs
     */
    public function batchInvoiceStatus(Request $request)
    {
        try {
            $poIds = $request->input('po_ids', []);
            
            if (empty($poIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No PO IDs provided'
                ], 400);
            }

            $companyId = Session::get('selected_company_id');
            $invoiceStatuses = [];
            
            foreach ($poIds as $poId) {
                $hasInvoices = POInvoice::where('purchase_order_id', $poId)
                    ->where('company_id', $companyId)
                    ->exists();
                
                $invoiceStatuses[$poId] = $hasInvoices;
            }

            return response()->json([
                'success' => true,
                'data' => $invoiceStatuses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check invoice status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk approve purchase orders
     */
    public function bulkApprove(Request $request)
    {
                    Log::info('Bulk approve request received', ['data' => $request->all()]);
            
            $companyId = Session::get('selected_company_id');
            Log::info('Company ID for bulk approve', ['company_id' => $companyId]);
        
        if ($response = $this->checkAuth()) {
            return $response;
        }

        try {
            $validated = $request->validate([
                'po_ids' => 'sometimes|array',
                'po_ids.*' => 'integer|exists:wh__purchase_orders,id',
                'requisition_ids' => 'sometimes|array',
                'requisition_ids.*' => 'integer|exists:requisitions,id',
            ]);
            
            // Ensure at least one type of ID is provided
            if (empty($validated['po_ids'] ?? []) && empty($validated['requisition_ids'] ?? [])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid items selected for approval'
                ], 400);
            }
            
            // Handle comments field separately since it might not be sent
            $comments = $request->input('comments', '');
            
            // Validate comments length if provided
            if ($comments && strlen($comments) > 1000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comments must be less than 1000 characters'
                ], 422);
            }

            $purchaseOrders = collect();
            $requisitions = collect();
            $results = [
                'purchase_orders' => ['approved' => 0, 'failed' => 0, 'errors' => []],
                'requisitions' => ['approved' => 0, 'failed' => 0, 'errors' => []]
            ];

            // Handle Purchase Orders
            if (!empty($validated['po_ids'])) {
                $purchaseOrders = Wh_PurchaseOrder::where('company_id', $companyId)
                    ->whereIn('id', $validated['po_ids'])
                    ->where('status', 'created')
                    ->get();
                    
                Log::info('Found purchase orders for bulk approve', [
                    'requested_ids' => $validated['po_ids'],
                    'found_count' => $purchaseOrders->count(),
                    'found_ids' => $purchaseOrders->pluck('id')->toArray()
                ]);
            }

            // Handle Requisitions
            if (!empty($validated['requisition_ids'])) {
                $requisitions = Requisition::where('company_id', $companyId)
                    ->whereIn('id', $validated['requisition_ids'])
                    ->where('status', 'pending')
                    ->get();
                    
                Log::info('Found requisitions for bulk approve', [
                    'requested_ids' => $validated['requisition_ids'],
                    'found_count' => $requisitions->count(),
                    'found_ids' => $requisitions->pluck('id')->toArray()
                ]);
            }

            if ($purchaseOrders->isEmpty() && $requisitions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid items found for approval'
                ], 400);
            }

            // Check if all POs have invoices uploaded (only for POs)
            if (!$purchaseOrders->isEmpty()) {
                $poIds = $purchaseOrders->pluck('id')->toArray();
                $poWithInvoices = POInvoice::whereIn('purchase_order_id', $poIds)
                    ->where('company_id', $companyId)
                    ->distinct()
                    ->pluck('purchase_order_id')
                    ->toArray();
                
                $poWithoutInvoices = array_diff($poIds, $poWithInvoices);
                
                if (!empty($poWithoutInvoices)) {
                    $missingPOs = Wh_PurchaseOrder::whereIn('id', $poWithoutInvoices)
                        ->pluck('po_number')
                        ->toArray();
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'The following purchase orders require invoice uploads before approval: ' . implode(', ', $missingPOs)
                    ], 400);
                }
            }

            // Validate inventory availability for requisitions
            if (!$requisitions->isEmpty()) {
                foreach ($requisitions as $requisition) {
                    $inventoryValidation = $this->validateInventoryAvailability($requisition);
                    if (!$inventoryValidation['success']) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Inventory validation failed for requisition ' . $requisition->requisition_number,
                            'validation_errors' => $inventoryValidation['errors']
                        ], 422);
                    }
                }
            }

            DB::beginTransaction();
            
            $totalApproved = 0;

            // Process Purchase Orders
            foreach ($purchaseOrders as $po) {
                Log::info('Processing PO for bulk approve', ['po_id' => $po->id, 'po_number' => $po->po_number]);
                
                try {
                    $po->update([
                        'status' => 'pending',
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                    ]);

                    // Create approval log
                    WarehouseLog::create([
                        'purchase_order_id' => $po->id,
                        'action' => 'bulk_approve_po',
                        'description' => 'Purchase order approved in bulk' . ($comments ? ' - ' . $comments : ''),
                        'performed_by' => Auth::id(),
                        'performed_at' => now(),
                        'company_id' => $companyId,
                        'user_id' => Auth::id()
                    ]);

                    $results['purchase_orders']['approved']++;
                    $totalApproved++;
                    Log::info('Successfully processed PO', ['po_id' => $po->id, 'approved_count' => $totalApproved]);
                } catch (\Exception $e) {
                    Log::error('Error processing individual PO in bulk approve', [
                        'po_id' => $po->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e; // Re-throw to trigger rollback
                }
            }

            // Process Requisitions
            foreach ($requisitions as $requisition) {
                Log::info('Processing Requisition for bulk approve', ['requisition_id' => $requisition->id, 'requisition_number' => $requisition->requisition_number]);
                
                try {
                    $requisition->update([
                        'status' => 'approved',
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                    ]);

                    // Deduct inventory quantities when requisition is approved
                    $items = $requisition->items;
                    if ($items && is_array($items)) {
                        foreach ($items as $item) {
                            $centralStoreItem = \App\Models\CentralStore::find($item['item_id']);
                            if ($centralStoreItem) {
                                $newQuantity = $centralStoreItem->quantity - ($item['quantity'] ?? 0);
                                $centralStoreItem->update(['quantity' => max(0, $newQuantity)]);
                                
                                Log::info('Inventory deducted on bulk requisition approval', [
                                    'item_id' => $item['item_id'],
                                    'item_name' => $centralStoreItem->item_name,
                                    'requested_quantity' => $item['quantity'],
                                    'old_quantity' => $centralStoreItem->quantity + ($item['quantity'] ?? 0),
                                    'new_quantity' => $newQuantity,
                                    'requisition_id' => $requisition->id,
                                    'approved_by' => Auth::id()
                                ]);
                            }
                        }
                    }

                    $results['requisitions']['approved']++;
                    $totalApproved++;
                    Log::info('Successfully processed Requisition', ['requisition_id' => $requisition->id, 'approved_count' => $totalApproved]);
                } catch (\Exception $e) {
                    Log::error('Error processing individual Requisition in bulk approve', [
                        'requisition_id' => $requisition->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    $results['requisitions']['failed']++;
                    $results['requisitions']['errors'][] = "Requisition {$requisition->requisition_number}: " . $e->getMessage();
                    throw $e; // Re-throw to trigger rollback
                }
            }

            DB::commit();

            // Build success message
            $message = [];
            if ($results['purchase_orders']['approved'] > 0) {
                $message[] = "{$results['purchase_orders']['approved']} purchase order(s)";
            }
            if ($results['requisitions']['approved'] > 0) {
                $message[] = "{$results['requisitions']['approved']} requisition(s)";
            }
            
            $successMessage = "Successfully approved " . implode(' and ', $message);
            
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'approved_count' => $totalApproved,
                'results' => $results
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Bulk approve validation error', ['error' => $e->getMessage(), 'errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Bulk approve error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk approve purchase orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export approval data
     */
    public function exportApprovals(Request $request)
    {
        Log::info('Export approvals request received', ['filters' => $request->all()]);
        
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            Log::error('Export approvals: No company ID found in session');
            return response()->json([
                'success' => false,
                'message' => 'Company session not found'
            ], 400);
        }
        
        $query = Wh_PurchaseOrder::with(['supplier', 'createdBy', 'approvedBy'])
            ->where('company_id', $companyId)
            ->where('status', 'created');

        // Apply filters if provided
        if ($request->filled('supplier')) {
            $query->whereHas('supplier', function($q) use ($request) {
                $q->where('company_name', 'like', '%' . $request->supplier . '%');
            });
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $purchaseOrders = $query->get();
        
        Log::info('Export approvals: Found purchase orders', [
            'count' => $purchaseOrders->count(),
            'po_numbers' => $purchaseOrders->pluck('po_number')->toArray()
        ]);

        // Create CSV content
        $csvData = [];
        $csvData[] = [
            'PO Number',
            'Supplier',
            'Requested By',
            'Total Amount',
            'Priority',
            'Status',
            'Created Date',
            'Invoice Status'
        ];

        foreach ($purchaseOrders as $po) {
            // Check invoice status
            $hasInvoices = POInvoice::where('purchase_order_id', $po->id)
                ->where('company_id', $companyId)
                ->exists();

            $csvData[] = [
                $po->po_number ?? 'N/A',
                $po->supplier ? $po->supplier->company_name : 'N/A',
                $po->createdBy ? $po->createdBy->fullname : 'N/A',
                number_format($po->total_amount ?? 0, 2),
                $po->priority ?? 'Normal',
                $po->status,
                $po->created_at ? $po->created_at->format('Y-m-d H:i:s') : 'N/A',
                $hasInvoices ? 'Uploaded' : 'Required'
            ];
        }

        // Generate CSV file
        $filename = 'po_approvals_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($csvData) {
            try {
                $file = fopen('php://output', 'w');
                if (!$file) {
                    throw new \Exception('Failed to open output stream');
                }
                
                foreach ($csvData as $row) {
                    if (fputcsv($file, $row) === false) {
                        throw new \Exception('Failed to write CSV row');
                    }
                }
                fclose($file);
            } catch (\Exception $e) {
                Log::error('Export CSV error', ['error' => $e->getMessage()]);
                throw $e;
            }
        };

        try {
            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Export stream error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to export data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revert a purchase order from external_approval back to created
     */
    public function revert(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $validated = $request->validate([
            'comments' => 'nullable|string|max:1000',
        ]);

        $companyId = Session::get('selected_company_id');
        
        $purchaseOrder = Wh_PurchaseOrder::where('company_id', $companyId)
            ->where('status', 'external_approval')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            // Update purchase order status back to created
            $purchaseOrder->update([
                'status' => 'created',
                'approved_by' => null,
                'approved_at' => null,
            ]);

            // Create revert log
            WarehouseLog::create([
                'purchase_order_id' => $purchaseOrder->id,
                'action' => 'revert_po',
                'description' => 'Purchase order reverted from external approval' . ($validated['comments'] ? ' - ' . $validated['comments'] : ''),
                'performed_by' => Auth::id(),
                'performed_at' => now(),
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase order reverted successfully',
                'data' => $purchaseOrder->fresh(['supplier', 'createdBy'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to revert purchase order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate average response time for PO approvals
     */
    private function calculateAverageResponseTime($companyId)
    {
        // Get POs that have been approved in the last 90 days (extended for more meaningful data)
        $approvedPOs = Wh_PurchaseOrder::where('company_id', $companyId)
            ->whereNotNull('approved_by')
            ->whereNotNull('approved_at')
            ->where('approved_at', '>=', now()->subDays(90))
            ->get();

        if ($approvedPOs->isEmpty()) {
            return 'N/A';
        }

        $totalResponseTime = 0;
        $validPOs = 0;

        foreach ($approvedPOs as $po) {
            $createdAt = \Carbon\Carbon::parse($po->created_at);
            $approvedAt = \Carbon\Carbon::parse($po->approved_at);
            
            // Calculate time difference in minutes for more precision
            $responseTimeMinutes = $createdAt->diffInMinutes($approvedAt);
            
            // Only count if there's actually a time difference
            if ($responseTimeMinutes > 0) {
                $totalResponseTime += $responseTimeMinutes;
                $validPOs++;
            }
        }

        if ($validPOs === 0) {
            return 'N/A';
        }

        $averageMinutes = $totalResponseTime / $validPOs;

        // Format the response time with better logic
        if ($averageMinutes < 60) {
            // Less than 1 hour, show in minutes
            return round($averageMinutes) . 'm';
        } elseif ($averageMinutes < 1440) {
            // Less than 24 hours, show in hours
            $hours = $averageMinutes / 60;
            return round($hours, 1) . 'h';
        } else {
            // More than 24 hours, show in days
            $days = $averageMinutes / 1440; // 1440 minutes = 24 hours
            return round($days, 1) . 'd';
        }
    }
}
