<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Wh_PurchaseOrder;
use App\Models\Wh_Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderApprovalController extends Controller
{
    public function index()
    {
        try {
            if ($response = $this->checkAuth()) {
                return $response;
            }

            $companyId = Session::get('selected_company_id');
            
            // Get statistics for external approval POs
            $stats = [
                'external_approval' => Wh_PurchaseOrder::where('company_id', $companyId)
                    ->where('status', 'external_approval')
                    ->count(),
                'high_priority' => 0, // Priority column doesn't exist, set to 0
                'approved' => Wh_PurchaseOrder::where('company_id', $companyId)
                    ->where('status', 'approved')
                    ->count(),
                'rejected' => Wh_PurchaseOrder::where('company_id', $companyId)
                    ->where('status', 'rejected')
                    ->count(),
            ];

            // Check if requesting specific PO
            $poId = request()->get('po_id');
            if ($poId) {
                $purchaseOrders = Wh_PurchaseOrder::with(['supplier', 'user', 'invoices', 'taxConfiguration'])
                    ->where('company_id', $companyId)
                    ->where('id', $poId)
                    ->where('status', 'external_approval')
                    ->get();
            } else {
                // Get POs with external_approval status with pagination
                $perPage = request()->get('per_page', 10);
                $page = request()->get('page', 1);
                
                $purchaseOrders = Wh_PurchaseOrder::with(['supplier', 'user', 'invoices', 'taxConfiguration'])
                    ->where('company_id', $companyId)
                    ->where('status', 'external_approval')
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage, ['*'], 'page', $page);
            }

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $purchaseOrders,
                    'stats' => $stats
                ]);
            }

            return view('company.Management.purchase-order-approval', compact('stats', 'purchaseOrders'));
            
        } catch (\Exception $e) {
            \Log::error('Management PO Approval Error: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'An error occurred while loading data',
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return view('company.Management.purchase-order-approval', [
                'stats' => ['external_approval' => 0, 'high_priority' => 0, 'approved' => 0, 'rejected' => 0],
                'purchaseOrders' => []
            ]);
        }
    }

    public function approve(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        $po = Wh_PurchaseOrder::where('company_id', $companyId)
            ->where('id', $id)
            ->where('status', 'external_approval')
            ->firstOrFail();

        $po->update([
            'status' => 'pending',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $request->input('notes', '')
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Purchase Order approved successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Purchase Order approved successfully');
    }

    public function reject(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        $po = Wh_PurchaseOrder::where('company_id', $companyId)
            ->where('id', $id)
            ->where('status', 'external_approval')
            ->firstOrFail();

        $po->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
            'rejection_reason' => $request->input('reason', '')
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Purchase Order rejected successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Purchase Order rejected successfully');
    }

    public function bulkApprove(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        $poIds = $request->input('po_ids', []);
        $notes = $request->input('notes', '');

        if (empty($poIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No purchase orders selected'
            ], 400);
        }

        $updated = Wh_PurchaseOrder::where('company_id', $companyId)
            ->whereIn('id', $poIds)
            ->where('status', 'external_approval')
            ->update([
                'status' => 'pending',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'approval_notes' => $notes
            ]);

        return response()->json([
            'success' => true,
            'message' => "Successfully approved {$updated} purchase orders"
        ]);
    }

    private function checkAuth()
    {
        if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return null;
    }
}
