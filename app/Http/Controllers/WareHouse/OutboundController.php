<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\OutboundOrder;
use App\Models\Waybill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class OutboundController extends Controller
{
    /**
     * Check authentication and company session
     */
    private function checkAuth()
    {
        if (!Auth::check()) {
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
     * Get approved requisitions for outbound orders
     */
    public function getApprovedRequisitions(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);
            
            $requisitions = Requisition::with(['requestor.personalInfo', 'departmentCategory'])
                ->where('company_id', $companyId)
                ->whereIn('status', ['approved', 'issued'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            // Calculate total amount for each requisition from items and prepare data
            $requisitionsData = $requisitions->map(function ($requisition) {
                $totalAmount = 0;
                if ($requisition->items && is_array($requisition->items)) {
                    foreach ($requisition->items as $item) {
                        $quantity = (float) ($item['quantity'] ?? 0);
                        $price = (float) ($item['unit_price'] ?? $item['price'] ?? 0);
                        $totalAmount += $quantity * $price;
                    }
                }
                
                // Get requestor name
                $requestorName = 'Unknown';
                if ($requisition->requestor && $requisition->requestor->personalInfo) {
                    $firstName = $requisition->requestor->personalInfo->first_name ?? '';
                    $lastName = $requisition->requestor->personalInfo->last_name ?? '';
                    $requestorName = trim($firstName . ' ' . $lastName) ?: 'Unknown';
                } elseif ($requisition->requestor && $requisition->requestor->name) {
                    $requestorName = $requisition->requestor->name;
                }
                
                // Get department info (code for table, name for modal)
                $departmentCode = $requisition->department ?? 'Unknown'; // Use department field for table display
                $departmentName = 'Unknown';
                if ($requisition->departmentCategory) {
                    $departmentName = $requisition->departmentCategory->name;
                }
                
                return [
                    'id' => $requisition->id,
                    'requisition_number' => $requisition->requisition_number,
                    'title' => $requisition->title,
                    'status' => $requisition->status,
                    'priority' => $requisition->priority,
                    'requested_date' => $requisition->requisition_date ? $requisition->requisition_date->format('M d, Y') : 
                                       ($requisition->created_at ? $requisition->created_at->format('M d, Y') : null),
                    'issued_at' => $requisition->issued_at,
                    'items' => $requisition->items,
                    'total_amount' => $totalAmount,
                    'requestor' => [
                        'id' => $requisition->requestor ? $requisition->requestor->id : null,
                        'name' => $requestorName,
                        'personalInfo' => $requisition->requestor && $requisition->requestor->personalInfo ? [
                            'first_name' => $requisition->requestor->personalInfo->first_name,
                            'last_name' => $requisition->requestor->personalInfo->last_name,
                        ] : null
                    ],
                    'departmentCategory' => [
                        'code' => $departmentCode,  // For table display (WH2, aWH1, etc.)
                        'name' => $departmentName   // For modal display (Warehouse, etc.)
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $requisitionsData,
                'pagination' => [
                    'current_page' => $requisitions->currentPage(),
                    'last_page' => $requisitions->lastPage(),
                    'per_page' => $requisitions->perPage(),
                    'total' => $requisitions->total(),
                    'from' => $requisitions->firstItem(),
                    'to' => $requisitions->lastItem(),
                    'has_more_pages' => $requisitions->hasMorePages()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching approved requisitions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching approved requisitions'
            ], 500);
        }
    }

    /**
     * Issue a requisition (create outbound order)
     */
    public function issueRequisition(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            $requisition = Requisition::with(['requestor.personalInfo', 'departmentCategory'])
                ->where('company_id', $companyId)
                ->whereIn('status', ['approved', 'issued'])
                ->findOrFail($id);

            // For partial issues, we allow both approved and issued requisitions
            // The status check is handled by the business logic below

            DB::beginTransaction();

            // Get modified items from request (for partial issues)
            $modifiedItems = $request->input('modified_items', []);
            $itemsToIssue = [];
            $totalAmount = 0;
            $validationErrors = [];
            
            // Debug: Log the received modified items
            \Log::info('📦 Modified items received:', $modifiedItems);
            \Log::info('📦 Request data:', $request->all());
            \Log::info('📦 Requisition ID: ' . $id);
            
            if (!empty($modifiedItems)) {
                \Log::info('📦 Processing modified items for partial issue...');
                // Use modified items for partial issues
                foreach ($modifiedItems as $index => $modifiedItem) {
                    $itemName = $modifiedItem['item_name'] ?? '';
                    $issueQty = (float) ($modifiedItem['quantity'] ?? 0);
                    $unitPrice = (float) ($modifiedItem['unit_price'] ?? 0);
                    
                    \Log::info("📦 Processing item {$index}: {$itemName}, Qty: {$issueQty}, Price: {$unitPrice}");
                    
                    if ($issueQty > 0) {
                        // Find the original item to get unit price if not provided
                        $originalItem = null;
                        if ($requisition->items && is_array($requisition->items)) {
                            foreach ($requisition->items as $origItem) {
                                if (($origItem['item_name'] ?? '') === $itemName) {
                                    $originalItem = $origItem;
                                    break;
                                }
                            }
                        }
                        
                        $actualUnitPrice = $unitPrice > 0 ? $unitPrice : (float) ($originalItem['unit_price'] ?? $originalItem['price'] ?? 0);
                        $totalAmount += $issueQty * $actualUnitPrice;
                        
                        $itemsToIssue[] = [
                            'item_name' => $itemName,
                            'quantity' => $issueQty,
                            'unit' => $modifiedItem['unit'] ?? ($originalItem['unit'] ?? 'EA'),
                            'unit_price' => $actualUnitPrice,
                            'description' => $originalItem['description'] ?? ''
                        ];
                        
                        // Validate against remaining quantity
                        if ($requisition->status === 'issued') {
                            $alreadyIssued = \App\Models\OutboundOrder::where('requisition_id', $requisition->id)
                                ->whereJsonContains('items', [['item_name' => $itemName]])
                                ->get()
                                ->sum(function ($order) use ($itemName) {
                                    $items = is_array($order->items) ? $order->items : json_decode($order->items, true);
                                    return collect($items)->where('item_name', $itemName)->sum('quantity');
                                });
                            
                            $originalQty = (float) ($originalItem['quantity'] ?? 0);
                            $remainingQty = $originalQty - $alreadyIssued;
                            
                            if ($issueQty > $remainingQty) {
                                $validationErrors[] = "Cannot issue more than remaining quantity for '{$itemName}'. Requested: {$issueQty}, Remaining: {$remainingQty}";
                            }
                        }
                    }
                }
            } else {
                // Use original items for new issues
                if ($requisition->items && is_array($requisition->items)) {
                    foreach ($requisition->items as $item) {
                        $itemName = $item['item_name'] ?? '';
                        $requestedQty = (float) ($item['quantity'] ?? 0);
                        $unitPrice = (float) ($item['unit_price'] ?? $item['price'] ?? 0);
                        $totalAmount += $requestedQty * $unitPrice;
                        
                        $itemsToIssue[] = [
                            'item_name' => $itemName,
                            'quantity' => $requestedQty,
                            'unit' => $item['unit'] ?? 'EA',
                            'unit_price' => $unitPrice,
                            'description' => $item['description'] ?? ''
                        ];
                    }
                }
            }
            
            // Return error if validation issues found
            if (!empty($validationErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity validation failed',
                    'validation_errors' => $validationErrors
                ], 400);
            }

            // Debug: Log the final items to issue
            \Log::info('📦 Final items to issue:', $itemsToIssue);
            \Log::info('📦 Total amount calculated: ' . $totalAmount);
            
            // Create outbound order
            $outboundOrder = OutboundOrder::create([
                'company_id' => $companyId,
                'requisition_id' => $requisition->id,
                'department' => $requisition->departmentCategory ? $requisition->departmentCategory->name : $requisition->department,
                'requested_by' => $requisition->requester_id,
                'items' => $itemsToIssue, // Use the modified items instead of original
                'total_value' => $totalAmount,
                'priority' => $requisition->priority ?? 'medium',
                'status' => OutboundOrder::STATUS_PENDING,
                'created_by' => Auth::id(),
            ]);
            
            \Log::info('📦 Outbound order created with ID: ' . $outboundOrder->id);

            // Note: Inventory quantities were already deducted at the approval stage

            // Update requisition status to issued (only if it was approved)
            if ($requisition->status === 'approved') {
                $requisition->update([
                    'status' => 'issued',
                    'issued_by' => Auth::id(),
                    'issued_at' => now(),
                ]);
            }
            // For already issued requisitions (partial issues), we don't change the status

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Outbound order created successfully',
                'data' => [
                    'requisition' => $requisition->fresh(['requestor.personalInfo', 'departmentCategory']),
                    'outbound_order' => $outboundOrder->fresh(['requestedBy'])
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating outbound order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create outbound order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get issued requisitions without waybills for waybill creation
     */
    public function getIssuedRequisitionsForWaybill(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            \Log::info('Fetching issued requisitions for waybill', ['company_id' => $companyId]);
            
            // Get issued requisitions that don't have waybills yet
            $requisitions = Requisition::with(['requestor.personalInfo', 'departmentCategory'])
                ->where('company_id', $companyId)
                ->where('status', 'issued')
                ->orderBy('issued_at', 'desc')
                ->get(['id', 'requisition_number', 'title', 'issued_at', 'requester_id', 'department_id']);
                
            \Log::info('Found requisitions', ['count' => $requisitions->count()]);
            
            // Debug: Log first few requisitions
            if ($requisitions->count() > 0) {
                \Log::info('First requisition sample', [
                    'id' => $requisitions->first()->id,
                    'requisition_number' => $requisitions->first()->requisition_number,
                    'title' => $requisitions->first()->title,
                    'requester_id' => $requisitions->first()->requester_id,
                    'department_id' => $requisitions->first()->department_id
                ]);
            }

            $formattedRequisitions = $requisitions->map(function ($requisition) {
                $requestorName = 'Unknown';
                if ($requisition->requestor && $requisition->requestor->personalInfo) {
                    $firstName = $requisition->requestor->personalInfo->first_name ?? '';
                    $lastName = $requisition->requestor->personalInfo->last_name ?? '';
                    $requestorName = trim($firstName . ' ' . $lastName) ?: 'Unknown';
                }

                $departmentName = 'Unknown';
                if ($requisition->departmentCategory) {
                    $departmentName = $requisition->departmentCategory->name;
                }

                return [
                    'id' => $requisition->id,
                    'requisition_number' => $requisition->requisition_number,
                    'title' => $requisition->title,
                    'requested_by' => $requestorName,
                    'department' => $departmentName,
                    'issued_at' => $requisition->issued_at ? $requisition->issued_at->format('M d, Y') : null
                ];
            });

            \Log::info('Formatted requisitions', ['count' => $formattedRequisitions->count()]);
            if ($formattedRequisitions->count() > 0) {
                \Log::info('First formatted requisition', $formattedRequisitions->first());
            }

            return response()->json([
                'success' => true,
                'data' => $formattedRequisitions
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching issued requisitions for waybill: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'company_id' => $companyId
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error fetching issued requisitions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create waybill for issued requisition
     */
    public function createWaybill(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            $requisition = Requisition::with(['requestor.personalInfo', 'departmentCategory'])
                ->where('company_id', $companyId)
                ->where('status', 'completed')
                ->whereNotNull('issued_at')
                ->findOrFail($id);

            // Find the outbound order for this requisition
            $outboundOrder = OutboundOrder::where('requisition_id', $requisition->id)
                ->where('company_id', $companyId)
                ->first();

            DB::beginTransaction();

            // Calculate total amount and weight
            $totalAmount = 0;
            $itemCount = 0;
            if ($requisition->items && is_array($requisition->items)) {
                foreach ($requisition->items as $item) {
                    $quantity = (float) ($item['quantity'] ?? 0);
                    $price = (float) ($item['price'] ?? 0);
                    $totalAmount += $quantity * $price;
                    $itemCount += $quantity;
                }
            }

            // Create waybill
            $waybill = Waybill::create([
                'company_id' => $companyId,
                'outbound_order_id' => $outboundOrder ? $outboundOrder->id : null,
                'requisition_id' => $requisition->id,
                'shipment_type' => Waybill::TYPE_INTERNAL,
                'total_packages' => $itemCount > 0 ? max(1, ceil($itemCount / 10)) : 1, // Estimate packages
                'total_value' => $totalAmount,
                'origin_name' => 'Central Warehouse',
                'origin_address' => 'Company Warehouse Address', // You can get this from company profile
                'destination_name' => $requisition->departmentCategory ? $requisition->departmentCategory->name : $requisition->department,
                'destination_address' => 'Department Location', // You can enhance this
                'destination_contact' => $requisition->requestor && $requisition->requestor->personalInfo ? 
                    trim(($requisition->requestor->personalInfo->first_name ?? '') . ' ' . ($requisition->requestor->personalInfo->last_name ?? '')) : 
                    'Unknown',
                'items' => $requisition->items,
                'status' => Waybill::STATUS_PENDING,
                'urgent' => $requisition->priority === 'urgent',
                'created_by' => Auth::id(),
            ]);

            // Update outbound order status if exists
            if ($outboundOrder) {
                $outboundOrder->update([
                    'status' => OutboundOrder::STATUS_SHIPPED,
                    'shipped_by' => Auth::id(),
                    'shipped_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Waybill created successfully',
                'data' => $waybill->fresh(['outboundOrder', 'requisition'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating waybill: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create waybill: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get departments and sub-departments for waybill dropdown
     */
    public function getDepartmentsForWaybill(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                return response()->json(['error' => 'Company not selected'], 400);
            }

            // Get all active departments with their sub-departments
            $departments = \App\Models\DepartmentCategory::where('company_id', $companyId)
                ->where('status', 'active')
                ->orderBy('name', 'asc')
                ->get();

            $departmentsData = [];

            foreach ($departments as $department) {
                // Add main department
                $departmentsData[] = [
                    'id' => 'dept_' . $department->id,
                    'name' => $department->name,
                    'type' => 'department',
                    'head_name' => $department->head_name,
                    'code' => $department->code
                ];

                // Add sub-departments if they exist
                if ($department->sub_departments && is_array($department->sub_departments)) {
                    foreach ($department->sub_departments as $subDept) {
                        $departmentsData[] = [
                            'id' => 'subdept_' . $department->id . '_' . md5($subDept),
                            'name' => $subDept,
                            'type' => 'sub_department',
                            'parent_department' => $department->name,
                            'parent_id' => $department->id
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $departmentsData
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching departments for waybill: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch departments'], 500);
        }
    }

    /**
     * Get reference details by reference number and type
     */
    public function getReferenceDetails(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                return response()->json(['error' => 'Company not selected'], 400);
            }

            $referenceNumber = $request->input('reference_number');
            $referenceType = $request->input('reference_type');

            if (!$referenceNumber || !$referenceType) {
                return response()->json(['error' => 'Reference number and type are required'], 400);
            }

            $referenceData = null;

            switch ($referenceType) {
                case 'requisition':
                    $requisition = \App\Models\Requisition::where('company_id', $companyId)
                        ->where('requisition_number', $referenceNumber)
                        ->with(['requestor.personalInfo', 'departmentCategory'])
                        ->first();

                    if ($requisition) {
                        $requestorName = 'Unknown';
                        if ($requisition->requestor && $requisition->requestor->personalInfo) {
                            $firstName = $requisition->requestor->personalInfo->first_name ?? '';
                            $lastName = $requisition->requestor->personalInfo->last_name ?? '';
                            $requestorName = trim($firstName . ' ' . $lastName) ?: 'Unknown';
                        }

                        // Calculate remaining quantities for each item
                        $itemsWithRemaining = [];
                        $totalRemainingItems = 0;
                        
                        if ($requisition->items && is_array($requisition->items)) {
                            foreach ($requisition->items as $item) {
                                $itemName = $item['item_name'] ?? '';
                                $originalQty = (float) ($item['quantity'] ?? 0);
                                
                                // Get total already issued for this item
                                $alreadyIssued = \App\Models\OutboundOrder::where('requisition_id', $requisition->id)
                                    ->whereJsonContains('items', [['item_name' => $itemName]])
                                    ->get()
                                    ->sum(function ($order) use ($itemName) {
                                        $items = is_array($order->items) ? $order->items : json_decode($order->items, true);
                                        return collect($items)->where('item_name', $itemName)->sum('quantity');
                                    });
                                
                                $remainingQty = $originalQty - $alreadyIssued;
                                
                                $itemsWithRemaining[] = array_merge($item, [
                                    'original_quantity' => $originalQty,
                                    'already_issued' => $alreadyIssued,
                                    'remaining_quantity' => max(0, $remainingQty)
                                ]);
                                
                                if ($remainingQty > 0) {
                                    $totalRemainingItems++;
                                }
                            }
                        }

                        $referenceData = [
                            'found' => true,
                            'type' => 'requisition',
                            'requisition_id' => $requisition->id,
                            'reference_number' => $requisition->requisition_number,
                            'title' => $requisition->title,
                            'requested_by' => $requestorName,
                            'department' => $requisition->department ?? 'Unknown',
                            'status' => $requisition->status,
                            'priority' => $requisition->priority,
                            'requested_date' => $requisition->requisition_date ? $requisition->requisition_date->format('Y-m-d') : null,
                            'items' => $itemsWithRemaining,
                            'notes' => $requisition->notes,
                            'total_remaining_items' => $totalRemainingItems,
                            'has_remaining_quantity' => $totalRemainingItems > 0
                        ];
                    }
                    break;

                case 'sales_order':
                    // You can add sales order lookup here if you have a SalesOrder model
                    $referenceData = [
                        'found' => false,
                        'message' => 'Sales order lookup not implemented yet'
                    ];
                    break;

                case 'job':
                    // You can add job lookup here if you have a Job model
                    $referenceData = [
                        'found' => false,
                        'message' => 'Job lookup not implemented yet'
                    ];
                    break;

                default:
                    $referenceData = [
                        'found' => false,
                        'message' => 'Unknown reference type'
                    ];
            }

            if (!$referenceData || !$referenceData['found']) {
                return response()->json([
                    'success' => false,
                    'message' => $referenceData['message'] ?? 'Reference not found',
                    'data' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $referenceData
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching reference details: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch reference details'], 500);
        }
    }
}
