<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wh_Supplier;
use App\Models\Wh_PurchaseOrder;
use App\Models\WarehouseLog;
use App\Models\TaxConfiguration;
use App\Models\QualityInspection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class POController extends Controller 
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

    // ===== SUPPLIERS =====
    public function supplierIndex()
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        $suppliers = Wh_Supplier::where('company_id', $companyId)
            ->select('id', 'company_name', 'primary_contact', 'email', 'phone')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $suppliers
        ]);
    }

    public function supplierShow($id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        return Wh_Supplier::where('company_id', $companyId)
            ->findOrFail($id);
    }

    public function supplierStore(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'company' => 'nullable|string',
        ]);

        $companyId = Session::get('selected_company_id');

        $supplier = Wh_Supplier::create([
            ...$validated,
            'company_id' => $companyId,
            'user_id' => Auth::id()
        ]);

            WarehouseLog::create([
            'purchase_order_id' => $supplier->id,
            'action' => 'create_supplier',
            'description' => 'Supplier created',
            'performed_by' => Auth::id(),
            'performed_at' => now(),
            'company_id' => $companyId,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'data' => $supplier,
            'message' => 'Supplier created successfully'
        ]);
    }

    public function supplierUpdate(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        $supplier = Wh_Supplier::where('company_id', $companyId)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'company' => 'nullable|string',
        ]);

        $supplier->update($validated);

        WarehouseLog::create([
            'purchase_order_id' => $supplier->id,
            'action' => 'update_supplier',
            'description' => 'Supplier updated',
            'performed_by' => Auth::id(),
            'performed_at' => now(),
            'company_id' => $companyId,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'data' => $supplier,
            'message' => 'Supplier updated successfully'
        ]);
    }

    public function supplierDestroy($id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        $supplier = Wh_Supplier::where('company_id', $companyId)
            ->findOrFail($id);

        $supplier->delete();

        WarehouseLog::create([
            'operation' => 'delete_supplier',
            'model' => 'Wh_Supplier',
            'model_id' => $id,
            'performed_by' => Auth::id(),
            'details' => ['deleted_id' => $id],
            'company_id' => $companyId,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully'
        ]);
    }

    /**
 * Generate a unique PO number
 * Format: PO-YYYYMMDD-XXXX (where XXXX is an incrementing number)
 */
public function generatePONumber()
{
    if ($response = $this->checkAuth()) {
        return $response;
    }

    $companyId = Session::get('selected_company_id');
    $datePrefix = date('Ymd');

    // Get the MAX sequence number used (regardless of deletion status)
    $maxNumber = Wh_PurchaseOrder::withTrashed()
        ->where('company_id', $companyId)
        ->where('po_number', 'like', 'PO-'.$datePrefix.'-%')
        ->selectRaw("MAX(CAST(SUBSTRING_INDEX(po_number, '-', -1) AS UNSIGNED)) as max_num")
        ->value('max_num');

    // Get count of existing non-deleted POs for today
    $existingTodayCount = Wh_PurchaseOrder::where('company_id', $companyId)
        ->where('po_number', 'like', 'PO-'.$datePrefix.'-%')
        ->whereNull('deleted_at')
        ->count();

    // Determine next number - use either max+1 or count+1 (whichever is higher)
    $nextNumber = max(($maxNumber ? $maxNumber + 1 : 1), ($existingTodayCount + 1));

    // Format the PO number
    $poNumber = 'PO-' . $datePrefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    // Double-check for uniqueness (race condition protection)
    $exists = Wh_PurchaseOrder::withTrashed()
        ->where('po_number', $poNumber)
        ->exists();

    if ($exists) {
        // If somehow exists (extremely rare), increment and try again
        $nextNumber++;
        $poNumber = 'PO-' . $datePrefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    return response()->json([
        'success' => true,
        'po_number' => $poNumber
    ]);
}

/**
 * Validate batch number and return item name
 */
public function validateBatchNumber(Request $request)
{
    if ($response = $this->checkAuth()) {
        return $response;
    }

    $request->validate([
        'batch_number' => 'required|string'
    ]);

    $companyId = Session::get('selected_company_id');
    $batchNumber = $request->batch_number;

    // Find the batch number in quality inspections
    $inspection = QualityInspection::where('company_id', $companyId)
        ->where('batch_number', $batchNumber)
        ->first();

    if (!$inspection) {
        return response()->json([
            'success' => false,
            'message' => 'Batch number not found. Please verify the batch number.',
            'exists' => false
        ]);
    }

    return response()->json([
        'success' => true,
        'exists' => true,
        'item_name' => $inspection->item_name,
        'item_id' => $inspection->item_id,
        'batch_number' => $inspection->batch_number
    ]);
}

    // ===== PURCHASE ORDERS =====
//    public function index(Request $request)
//  {
//     if ($response = $this->checkAuth()) {
//         return $response;
//     }

//     $companyId = Session::get('selected_company_id');
    
//     $query = Wh_PurchaseOrder::with('supplier')
//         ->where('company_id', $companyId)
//         ->where('status', ['pending', 'draft']) 
//         ->orderBy('created_at', 'desc');

//     // Search functionality
//     if ($request->has('search') && !empty($request->search)) {
//         $search = $request->search;
//         $query->where(function($q) use ($search) {
//             $q->where('po_number', 'like', "%$search%")
//               ->orWhere('status', 'like', "%$search%")
//               ->orWhereHas('supplier', function($q) use ($search) {
//                   $q->where('name', 'like', "%$search%");
//               });
//         });
//     }

//     // Date filtering
//     if ($request->has('start_date') && !empty($request->start_date)) {
//         $query->where('order_date', '>=', $request->start_date);
//     }
    
//     if ($request->has('end_date') && !empty($request->end_date)) {
//         $query->where('order_date', '<=', $request->end_date);
//     }

//     return $query->paginate(10);
// }

// public function index(Request $request)
// {
//     if ($response = $this->checkAuth()) {
//         return $response;
//     }

//     $companyId = Session::get('selected_company_id');
    
//     $query = Wh_PurchaseOrder::with('supplier')
//         ->where('company_id', $companyId)
//         ->orderBy('created_at', 'desc');

//     // Search functionality - enhanced with more fields
//     if ($request->has('search') && !empty($request->search)) {
//         $search = $request->search;
//         $query->where(function($q) use ($search) {
//             $q->where('po_number', 'like', "%$search%")
//               ->orWhere('status', 'like', "%$search%")
//               ->orWhere('notes', 'like', "%$search%")
//               ->orWhereHas('supplier', function($q) use ($search) {
//                   $q->where('company_name', 'like', "%$search%")
//                     ->orWhere('primary_contact', 'like', "%$search%")
//                     ->orWhere('email', 'like', "%$search%")
//                     ->orWhere('phone', 'like', "%$search%");
//               });
//         });
//     }

//     // Date filtering - improved with validation
//     if ($request->has('start_date') && !empty($request->start_date)) {
//         try {
//             $startDate = Carbon::parse($request->start_date)->startOfDay();
//             $query->where('order_date', '>=', $startDate);
//         } catch (\Exception $e) {
//             // Log error if date parsing fails
//             \Log::error("Invalid start date format: " . $request->start_date);
//         }
//     }
    
//     if ($request->has('end_date') && !empty($request->end_date)) {
//         try {
//             $endDate = Carbon::parse($request->end_date)->endOfDay();
//             $query->where('order_date', '<=', $endDate);
//         } catch (\Exception $e) {
//             // Log error if date parsing fails
//             \Log::error("Invalid end date format: " . $request->end_date);
//         }
//     }

//     // Status filtering - added to match your frontend tabs
//     if ($request->has('status') && !empty($request->status)) {
//         $status = $request->status;
//         if (str_contains($status, ',')) {
//         $statuses = explode(',', $status);
//         $query->whereIn('status', $statuses);
//         } else {
//             $query->where('status', $status);
//         }
//     }

//     // Pagination with configurable per page
//     $perPage = $request->has('per_page') ? min(max($request->per_page, 5), 100) : 10;
    
//     return $query->paginate($perPage);

    
// }

public function index(Request $request)
{
    if ($response = $this->checkAuth()) {
        return $response;
    }

    $companyId = Session::get('selected_company_id');
    
    // Base query for getting the paginated results
    $query = Wh_PurchaseOrder::with('supplier')
        ->where('company_id', $companyId)
        ->orderBy('created_at', 'desc');

    // Search functionality
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('po_number', 'like', "%$search%")
              ->orWhere('status', 'like', "%$search%")
              ->orWhere('notes', 'like', "%$search%")
              ->orWhereHas('supplier', function($q) use ($search) {
                  $q->where('company_name', 'like', "%$search%")
                    ->orWhere('primary_contact', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
              });
        });
    }

    // Date filtering
    if ($request->has('start_date') && !empty($request->start_date)) {
        try {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->where('order_date', '>=', $startDate);
        } catch (\Exception $e) {
            \Log::error("Invalid start date format: " . $request->start_date);
        }
    }
    
    if ($request->has('end_date') && !empty($request->end_date)) {
        try {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->where('order_date', '<=', $endDate);
        } catch (\Exception $e) {
            \Log::error("Invalid end date format: " . $request->end_date);
        }
    }

    // Status filtering
    if ($request->has('status') && !empty($request->status)) {
        $status = $request->status;
        \Log::info("PO Controller - Status filtering: " . json_encode([
            'requested_status' => $status,
            'status_type' => gettype($status),
            'status_empty' => empty($status),
            'status_length' => strlen($status)
        ]));
        
        if (str_contains($status, ',')) {
            $statuses = explode(',', $status);
            $query->whereIn('status', $statuses);
            \Log::info("PO Controller - Multiple statuses: " . json_encode($statuses));
        } else {
            $query->where('status', $status);
            \Log::info("PO Controller - Single status filter applied: " . $status);
        }
    } else {
        \Log::info("PO Controller - No status filtering applied");
    }

    // Get status counts from a separate unfiltered query
    $statusCounts = Wh_PurchaseOrder::where('company_id', $companyId)
        ->selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();

    // Pagination
    $perPage = $request->has('per_page') ? min(max($request->per_page, 5), 100) : 10;
    $paginatedResults = $query->paginate($perPage);
    
    // Debug the results
    \Log::info("PO Controller - Query results: " . json_encode([
        'total_results' => $paginatedResults->total(),
        'current_page' => $paginatedResults->currentPage(),
        'per_page' => $paginatedResults->perPage(),
        'results_count' => $paginatedResults->count(),
        'statuses_in_results' => $paginatedResults->items() ? collect($paginatedResults->items())->pluck('status')->unique()->toArray() : []
    ]));

            // Return the response with additional meta data
        return response()->json([
            'data' => $paginatedResults->items(),
            'meta' => [
                'total' => $paginatedResults->total(),
                'current_page' => $paginatedResults->currentPage(),
                'last_page' => $paginatedResults->lastPage(),
                'per_page' => $paginatedResults->perPage(),
                'draft_count' => $statusCounts['draft'] ?? 0,
                'created_count' => $statusCounts['created'] ?? 0, // Map created to created status
                'external_approval_count' => $statusCounts['external_approval'] ?? 0,
                'pending_count' => $statusCounts['pending'] ?? 0,
                'approved_count' => $statusCounts['approved'] ?? 0, // Map approved to approved status
                'completed_count' => $statusCounts['completed'] ?? 0,
                'rejected_count' => $statusCounts['rejected'] ?? 0,
            ],
            'links' => [
                'first' => $paginatedResults->url(1),
                'last' => $paginatedResults->url($paginatedResults->lastPage()),
                'prev' => $paginatedResults->previousPageUrl(),
                'next' => $paginatedResults->nextPageUrl(),
            ]
        ]);
}

    public function show($id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        $po = Wh_PurchaseOrder::with(['supplier', 'taxConfiguration'])
            ->where('company_id', $companyId)
            ->findOrFail($id);
        
        
        return $po;
    }

    public function store(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }


        $validated = $request->validate([
            'po_number' => 'required|unique:wh__purchase_orders',
            'supplier_id' => 'required|exists:wh__suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery' => 'nullable|date',
            'status' => 'required|string|in:draft,created',
            'payment_terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'requested_by' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.category' => 'nullable|string',
            'items.*.is_reorder' => 'nullable',
            'items.*.batch_number' => 'nullable|string',
            'items.*.reorder_reason' => 'nullable|string',
            'remarks' => 'nullable|string',
            // Tax configuration fields
            'tax_configuration_id' => 'nullable|exists:tax_configurations,id',
            'tax_type' => 'nullable|string|in:standard,flat_rate,exempt,custom',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'is_tax_exempt' => 'nullable|in:0,1,true,false',
            'tax_exemption_reason' => 'nullable|string',
        ]);

        $companyId = Session::get('selected_company_id');

        // Check if items are provided and not empty
        if (empty($validated['items']) || count($validated['items']) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'At least one item is required to create a purchase order.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Calculate totals
            $items = collect($validated['items'])->map(function($item) {
                // Convert is_reorder to boolean
                $isReorder = filter_var($item['is_reorder'] ?? false, FILTER_VALIDATE_BOOLEAN);
                
                return [
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'category' => $item['category'] ?? 'general',
                    'is_reorder' => $isReorder,
                    'batch_number' => $isReorder ? ($item['batch_number'] ?? null) : null,
                    'reorder_reason' => $isReorder ? ($item['reorder_reason'] ?? null) : null,
                ];
            });

            $subtotal = $items->sum('total_price');

            // Handle tax calculations
            $taxConfiguration = null;
            $taxAmount = 0;
            $totalAmount = $subtotal;
            $taxType = 'standard';
            $taxRate = 0;

            if (isset($validated['tax_configuration_id']) && $validated['tax_configuration_id']) {
                $taxConfiguration = TaxConfiguration::where('company_id', $companyId)
                    ->where('id', $validated['tax_configuration_id'])
                    ->where('is_active', true)
                    ->first();
            }

            // Convert is_tax_exempt to boolean
            $isTaxExempt = filter_var($validated['is_tax_exempt'] ?? false, FILTER_VALIDATE_BOOLEAN);
            
            if ($taxConfiguration && !$isTaxExempt) {
                $taxCalculations = $taxConfiguration->calculateTotalAmount($subtotal);
                $taxAmount = $taxCalculations['tax_amount'];
                $totalAmount = $taxCalculations['total_amount'];
                $taxType = $taxConfiguration->type;
                $taxRate = $taxConfiguration->rate;
            } elseif (isset($validated['tax_rate']) && $validated['tax_rate'] > 0 && !$isTaxExempt) {
                $taxAmount = $subtotal * ($validated['tax_rate'] / 100);
                $totalAmount = $subtotal + $taxAmount;
                $taxType = $validated['tax_type'] ?? 'custom';
                $taxRate = $validated['tax_rate'];
            }

            // Create tax breakdown for storage
            $taxBreakdown = [
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'tax_rate' => $taxRate,
                'tax_type' => $taxType,
                'calculation_method' => $taxConfiguration ? 'configuration' : 'manual',
                'items_breakdown' => $items->map(function($item) use ($taxConfiguration) {
                    $itemTaxAmount = 0;
                    if ($taxConfiguration) {
                        $itemTaxAmount = $item['total_price'] * ($taxConfiguration->rate / 100);
                    }
                    return [
                        'name' => $item['name'],
                        'category' => $item['category'],
                        'subtotal' => $item['total_price'],
                        'tax_amount' => $itemTaxAmount,
                        'is_exempt' => false
                    ];
                })
            ];

            // Prepare the base data for PO creation
            $poData = [
                'po_number' => $validated['po_number'],
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'delivery_date' => $validated['expected_delivery'],
                'status' => $validated['status'],
                'payment_terms' => $validated['payment_terms'],
                'notes' => $validated['notes'],
                'requested_by' => $validated['requested_by'] ?? Auth::user()->name ?? Auth::user()->fullname ?? 'Unknown',
                'items' => $items,
                'total_value' => $subtotal,
                'total_items' => $items->count(),
                'created_by' => Auth::id(),
                'company_id' => $companyId,
                'user_id' => Auth::id(),
            ];

            // Add tax fields only if they exist in the database
            $taxFields = [
                'tax_configuration_id' => $taxConfiguration ? $taxConfiguration->id : null,
                'tax_type' => $taxType,
                'tax_rate' => $taxRate,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'is_tax_exempt' => $isTaxExempt,
                'tax_exemption_reason' => $validated['tax_exemption_reason'] ?? null,
                'tax_breakdown' => $taxBreakdown,
            ];

            // Add tax fields to PO data
            foreach ($taxFields as $field => $value) {
                $poData[$field] = $value;
            }

            $po = Wh_PurchaseOrder::create($poData);

            WarehouseLog::create([
                'purchase_order_id' => $po->id,
                'action' => 'create_po',
                'description' => 'Purchase order created',
                'performed_by' => Auth::id(),
                'performed_at' => now(),
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $po,
                'message' => 'Purchase order created successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PO Creation Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create purchase order: ' . $e->getMessage(),
                'debug' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }



//     public function update(Request $request, $po_number)
// {
//     // Authentication check
//     if ($response = $this->checkAuth()) {
//         return $response;
//     }

//     $companyId = Session::get('selected_company_id');
    
//     // Find the purchase order by po_number and company
//     $po = Wh_PurchaseOrder::where('company_id', $companyId)
//         ->where('po_number', $po_number)
//         ->firstOrFail();

//     // Validate request data
//     $validated = $request->validate([
//         'po_number' => 'sometimes|required|unique:wh__purchase_orders,po_number,'.$po->id,
//         'supplier_id' => 'sometimes|required|exists:wh__suppliers,id',
//         'order_date' => 'sometimes|required|date',  // Changed from po_date to match your table
//         'delivery_date' => 'nullable|date',
//         'status' => 'sometimes|required|string|in:created,approved,delivered,cancelled',
//         'items' => 'sometimes|required|array|min:1',
//         'items.*.name' => 'required|string',
//         'items.*.quantity' => 'required|numeric|min:1',
//         'items.*.unit_price' => 'required|numeric|min:0',
//         'remarks' => 'nullable|string',
//     ]);

//     DB::beginTransaction();
//     try {
//         // Prepare update data
//         $updateData = [
//             'po_number' => $validated['po_number'] ?? $po->po_number,
//             'supplier_id' => $validated['supplier_id'] ?? $po->supplier_id,
//             'order_date' => $validated['order_date'] ?? $po->order_date,  // Changed from po_date
//             'delivery_date' => $validated['delivery_date'] ?? $po->delivery_date,
//             'status' => $validated['status'] ?? $po->status,
//             'remarks' => $validated['remarks'] ?? $po->remarks,
//         ];

//         // Process items if provided
//         if (isset($validated['items'])) {
//             $items = collect($validated['items'])->map(function($item) {
//                 return [
//                     'name' => $item['name'],
//                     'quantity' => $item['quantity'],
//                     'unit_price' => $item['unit_price'],
//                     'total_price' => $item['quantity'] * $item['unit_price'],
//                 ];
//             });

//             $updateData['items'] = $items;
//             $updateData['total_value'] = $items->sum('total_price');
//             $updateData['total_items'] = $items->count();
//         }

//         // Update the purchase order
//         $po->update($updateData);

//         // Create audit log
//         WarehouseLog::create([
//             'purchase_order_id' => $po->id,
//             'action' => 'update_po',
//             'description' => 'Purchase order updated',
//             'performed_by' => Auth::user()->name,
//             'performed_at' => now(),
//             'company_id' => $companyId,
//             'user_id' => Auth::id()
//         ]);

//         DB::commit();

//         return response()->json([
//             'success' => true,
//             'data' => $po,
//             'message' => 'Purchase order updated successfully'
//         ]);

//     } catch (\Exception $e) {
//         DB::rollBack();
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to update purchase order: ' . $e->getMessage()
//         ], 500);
//     }
// }
    
    

    public function update(Request $request, $id)
    {
        // Authentication check
        if ($response = $this->checkAuth()) {
            return $response;
        }

        // Debug logging
        \Log::info('PO Update Request Data:', $request->all());

        $companyId = Session::get('selected_company_id');
    
    // Find the purchase order by ID and company
    $po = Wh_PurchaseOrder::where('company_id', $companyId)
        ->where('id', $id)
        ->firstOrFail();

    // Validate request data - updated to match all fields from store
    try {
        $validated = $request->validate([
        'po_number' => 'sometimes|required|unique:wh__purchase_orders,po_number,'.$po->id,
        'supplier_id' => 'sometimes|required|exists:wh__suppliers,id',
        'order_date' => 'sometimes|required|date',
        'expected_delivery_date' => 'nullable|date',
        'expected_delivery' => 'nullable|date', // Alternative field name
        'payment_terms' => 'nullable|string',
        'notes' => 'nullable|string',
        'status' => 'sometimes|required|string|in:created,draft,pending,approved,delivered,cancelled',
        'items' => 'sometimes|required|array|min:1',
        'items.*.name' => 'required|string',
        'items.*.quantity' => 'required|numeric|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
        'items.*.is_reorder' => 'nullable',
        'items.*.batch_number' => 'nullable|string',
        'items.*.reorder_reason' => 'nullable|string',
        
        // Tax configuration validation
        'tax_method' => 'nullable|string|in:config,manual',
        'tax_configuration_id' => 'nullable|exists:tax_configurations,id',
        'tax_type' => 'nullable|string|in:standard,flat_rate,exempt,custom',
        'tax_rate' => 'nullable|numeric|min:0|max:100',
        'is_tax_exempt' => 'nullable|in:0,1,true,false',
        'tax_exemption_reason' => 'nullable|string|max:500',
    ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('PO Update Validation Error:', [
            'errors' => $e->errors(),
            'request_data' => $request->all()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    }

    DB::beginTransaction();
    try {
        // Prepare update data with all fields
        $updateData = [
            'po_number' => $validated['po_number'] ?? $po->po_number,
            'supplier_id' => $validated['supplier_id'] ?? $po->supplier_id,
            'order_date' => $validated['order_date'] ?? $po->order_date,
            'delivery_date' => $validated['expected_delivery_date'] ?? $validated['expected_delivery'] ?? $po->delivery_date,
            'payment_terms' => $validated['payment_terms'] ?? $po->payment_terms,
            'notes' => $validated['notes'] ?? $po->notes,
            'status' => $validated['status'] ?? $po->status,
        ];

        // Process items if provided
        if (isset($validated['items'])) {
            $items = collect($validated['items'])->map(function($item) {
                // Convert is_reorder to boolean
                $isReorder = filter_var($item['is_reorder'] ?? false, FILTER_VALIDATE_BOOLEAN);
                
                return [
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'is_reorder' => $isReorder,
                    'batch_number' => $isReorder ? ($item['batch_number'] ?? null) : null,
                    'reorder_reason' => $isReorder ? ($item['reorder_reason'] ?? null) : null,
                ];
            });

            $updateData['items'] = $items;
            $updateData['total_value'] = $items->sum('total_price');
            $updateData['total_items'] = $items->count();
            
            // Calculate subtotal
            $subtotal = $items->sum('total_price');
            
            // Process tax configuration if provided
            if (isset($validated['tax_method']) || isset($validated['tax_configuration_id']) || isset($validated['tax_rate'])) {
                $isTaxExempt = filter_var($validated['is_tax_exempt'] ?? false, FILTER_VALIDATE_BOOLEAN);
                
                if ($isTaxExempt) {
                    // Tax exempt
                    $updateData['tax_configuration_id'] = null;
                    $updateData['tax_type'] = 'exempt';
                    $updateData['tax_rate'] = 0;
                    $updateData['subtotal'] = $subtotal;
                    $updateData['tax_amount'] = 0;
                    $updateData['total_amount'] = $subtotal;
                    $updateData['is_tax_exempt'] = true;
                    $updateData['tax_exemption_reason'] = $validated['tax_exemption_reason'] ?? null;
                    $updateData['tax_breakdown'] = [
                        'subtotal' => $subtotal,
                        'tax_rate' => 0,
                        'tax_amount' => 0,
                        'total_amount' => $subtotal,
                        'is_exempt' => true
                    ];
                } else {
                    // Calculate tax
                    $taxConfiguration = null;
                    $taxRate = 0;
                    $taxType = 'custom';
                    
                    if ($validated['tax_method'] === 'config' && $validated['tax_configuration_id']) {
                        $taxConfiguration = TaxConfiguration::find($validated['tax_configuration_id']);
                        if ($taxConfiguration) {
                            $taxRate = $taxConfiguration->rate;
                            $taxType = $taxConfiguration->type;
                            $updateData['tax_configuration_id'] = $taxConfiguration->id;
                        }
                    } elseif ($validated['tax_method'] === 'manual' && $validated['tax_rate']) {
                        $taxRate = $validated['tax_rate'];
                        $taxType = 'custom';
                        $updateData['tax_configuration_id'] = null;
                    }
                    
                    $updateData['tax_type'] = $taxType;
                    $updateData['tax_rate'] = $taxRate;
                    $updateData['subtotal'] = $subtotal;
                    
                    // Calculate tax amounts
                    $taxAmount = $subtotal * ($taxRate / 100);
                    $totalAmount = $subtotal + $taxAmount;
                    
                    $updateData['tax_amount'] = $taxAmount;
                    $updateData['total_amount'] = $totalAmount;
                    $updateData['is_tax_exempt'] = false;
                    $updateData['tax_exemption_reason'] = null;
                    $updateData['tax_breakdown'] = [
                        'subtotal' => $subtotal,
                        'tax_rate' => $taxRate,
                        'tax_amount' => $taxAmount,
                        'total_amount' => $totalAmount,
                        'is_exempt' => false
                    ];
                }
            }
        }

        // Update the purchase order
        $po->update($updateData);

        // Create audit log
        WarehouseLog::create([
            'purchase_order_id' => $po->id,
            'action' => 'update_po',
            'description' => 'Purchase order updated',
            'performed_by' => Auth::id(),
            'performed_at' => now(),
            'company_id' => $companyId,
            'user_id' => Auth::id()
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $po,
            'message' => 'Purchase order updated successfully'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Failed to update purchase order: ' . $e->getMessage()
        ], 500);
    }
}
    
    
    public function destroy($id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        $po = Wh_PurchaseOrder::where('company_id', $companyId)
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $po->delete();

            WarehouseLog::create([
                'purchase_order_id' => $po->id,
                'action' => 'delete_po',
                'description' => 'Purchase order deleted',
                'performed_by' => Auth::id(),
                'performed_at' => now(),
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase order deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete purchase order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tax configurations for the company
     */
    public function getTaxConfigurations()
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        // Create default Ghana tax configurations if none exist
        $existingConfigs = TaxConfiguration::where('company_id', $companyId)->count();
        if ($existingConfigs === 0) {
            TaxConfiguration::createDefaultGhanaTaxConfigurations($companyId, Auth::id());
        }

        $configurations = TaxConfiguration::getActiveConfigurations($companyId);

        return response()->json([
            'success' => true,
            'data' => $configurations
        ]);
    }

    /**
     * Calculate tax for a given subtotal and tax configuration
     */
    public function calculateTax(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $validated = $request->validate([
            'subtotal' => 'required|numeric|min:0',
            'tax_configuration_id' => 'nullable|exists:tax_configurations,id',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'tax_type' => 'nullable|string|in:standard,flat_rate,exempt,custom',
            'items' => 'nullable|array',
            'items.*.category' => 'nullable|string'
        ]);

        $companyId = Session::get('selected_company_id');
        $subtotal = $validated['subtotal'];

        if (isset($validated['tax_configuration_id']) && $validated['tax_configuration_id']) {
            $taxConfiguration = TaxConfiguration::where('company_id', $companyId)
                ->where('id', $validated['tax_configuration_id'])
                ->where('is_active', true)
                ->first();

            if ($taxConfiguration) {
                // Calculate tax on full subtotal (no item-specific exemptions)
                $taxAmount = $subtotal * ($taxConfiguration->rate / 100);
                $totalAmount = $subtotal + $taxAmount;

                return response()->json([
                    'success' => true,
                    'data' => [
                        'subtotal' => $subtotal,
                        'tax_amount' => $taxAmount,
                        'total_amount' => $totalAmount,
                        'tax_rate' => $taxConfiguration->rate,
                        'tax_type' => $taxConfiguration->type,
                        'item_exemptions' => []
                    ]
                ]);
            }
        }

        // Manual tax calculation
        if (isset($validated['tax_rate']) && $validated['tax_rate'] > 0) {
            $taxAmount = $subtotal * ($validated['tax_rate'] / 100);
            $totalAmount = $subtotal + $taxAmount;

            return response()->json([
                'success' => true,
                'data' => [
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'tax_rate' => $validated['tax_rate'],
                    'tax_type' => $validated['tax_type'] ?? 'custom'
                ]
            ]);
        }

        // No tax
        return response()->json([
            'success' => true,
            'data' => [
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'total_amount' => $subtotal,
                'tax_rate' => 0,
                'tax_type' => 'exempt'
            ]
        ]);
    }
}