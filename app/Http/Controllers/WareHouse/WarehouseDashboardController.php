<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\CentralStore;
use App\Models\Wh_PurchaseOrder;
use App\Models\POReceiving;
use App\Models\Requisition;
use App\Models\OutboundOrder;
use App\Models\Waybill;
use App\Models\User;
use App\Models\CompanyProfile;
use Carbon\Carbon;

class WarehouseDashboardController extends Controller
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

    public function getStatistics(Request $request)
    {
        try {
            Log::info('WarehouseDashboardController::getStatistics called');
            
            if ($response = $this->checkAuth()) {
                Log::error('Authentication failed in getStatistics');
                return $response;
            }

            $companyId = Session::get('selected_company_id');
            Log::info('Company ID: ' . $companyId);

            // Get current month data
            $currentMonth = Carbon::now();
            $previousMonth = Carbon::now()->subMonth();
            
            // Calculate real month-over-month changes
            $totalItemsChange = $this->getMonthlyChange('total_items', $companyId, $currentMonth, $previousMonth);
            $inStockChange = $this->getMonthlyChange('in_stock', $companyId, $currentMonth, $previousMonth);
            $lowStockChange = $this->getMonthlyChange('low_stock', $companyId, $currentMonth, $previousMonth);
            $outOfStockChange = $this->getMonthlyChange('out_of_stock', $companyId, $currentMonth, $previousMonth);
            $totalValueChange = $this->getMonthlyChange('total_value', $companyId, $currentMonth, $previousMonth);
            
            $statistics = [
                'total_items' => $this->getTotalItems($companyId),
                'in_stock_items' => $this->getInStockItems($companyId),
                'low_stock_items' => $this->getLowStockItems($companyId),
                'out_of_stock_items' => $this->getOutOfStockItems($companyId),
                'total_value' => $this->getTotalValue($companyId),
                
                // Real calculated changes
                'total_items_change' => $totalItemsChange,
                'in_stock_change' => $inStockChange,
                'low_stock_change' => $lowStockChange,
                'out_of_stock_change' => $outOfStockChange,
                'total_value_change' => $totalValueChange,
            ];

            Log::info('Statistics calculated: ', $statistics);

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching warehouse statistics: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get total items in Central Store only
     */
    private function getTotalItems($companyId)
    {
        try {
            // Only Central Store completed items
            return CentralStore::where('company_id', $companyId)
                ->where('status', 'completed')
                ->count(); // Count distinct items, not total quantity
        } catch (\Exception $e) {
            Log::error('Error calculating total items: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get in-stock items (items with quantity > 0)
     */
    private function getInStockItems($companyId)
    {
        try {
            return CentralStore::where('company_id', $companyId)
                ->where('status', 'completed')
                ->where('quantity', '>', 0)
                ->count(); // Count items, not total quantity
        } catch (\Exception $e) {
            Log::error('Error calculating in-stock items: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get low stock items (items with quantity between 1-10)
     */
    private function getLowStockItems($companyId)
    {
        try {
            $lowStockThreshold = 10; // Items with quantity between 1-10 are low stock
            
            return CentralStore::where('company_id', $companyId)
                ->where('status', 'completed')
                ->where('quantity', '>', 0)
                ->where('quantity', '<=', $lowStockThreshold)
                ->count(); // Count items, not total quantity
        } catch (\Exception $e) {
            Log::error('Error calculating low stock items: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get out of stock items (quantity = 0)
     */
    private function getOutOfStockItems($companyId)
    {
        try {
            return CentralStore::where('company_id', $companyId)
                ->where('status', 'completed')
                ->where('quantity', '=', 0)
                ->count();
        } catch (\Exception $e) {
            Log::error('Error calculating out of stock items: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get total value of Central Store items only
     */
    private function getTotalValue($companyId)
    {
        try {
            // Only Central Store value
            return CentralStore::where('company_id', $companyId)
                ->where('status', 'completed')
                ->sum(DB::raw('quantity * unit_price'));
        } catch (\Exception $e) {
            Log::error('Error calculating total value: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calculate month-over-month percentage change
     */
    private function getMonthlyChange($metric, $companyId, $currentMonth, $previousMonth)
    {
        try {
            $currentValue = $this->getMetricForMonth($metric, $companyId, $currentMonth);
            $previousValue = $this->getMetricForMonth($metric, $companyId, $previousMonth);

            // If no previous data, return 0 (no change to show)
            if ($previousValue == 0) {
                return 0;
            }

            // If no current data but had previous data, return -100 (decrease)
            if ($currentValue == 0) {
                return -100;
            }

            return (($currentValue - $previousValue) / $previousValue) * 100;
        } catch (\Exception $e) {
            Log::error('Error calculating monthly change for ' . $metric . ': ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get metric value for a specific month
     */
    private function getMetricForMonth($metric, $companyId, $month)
    {
        try {
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            switch ($metric) {
                case 'total_items':
                    return $this->getTotalItemsForPeriod($companyId, $startOfMonth, $endOfMonth);
                
                case 'in_stock':
                    return $this->getInStockItemsForPeriod($companyId, $startOfMonth, $endOfMonth);
                
                case 'low_stock':
                    return $this->getLowStockItemsForPeriod($companyId, $startOfMonth, $endOfMonth);
                
                case 'out_of_stock':
                    return $this->getOutOfStockItemsForPeriod($companyId, $startOfMonth, $endOfMonth);
                
                case 'total_value':
                    return $this->getTotalValueForPeriod($companyId, $startOfMonth, $endOfMonth);
                
                default:
                    return 0;
            }
        } catch (\Exception $e) {
            Log::error('Error getting metric for month: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get total items for a specific period (Central Store only)
     */
    private function getTotalItemsForPeriod($companyId, $startDate, $endDate)
    {
        return CentralStore::where('company_id', $companyId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Get in-stock items for a specific period
     */
    private function getInStockItemsForPeriod($companyId, $startDate, $endDate)
    {
        return CentralStore::where('company_id', $companyId)
            ->where('status', 'completed')
            ->where('quantity', '>', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Get low stock items for a specific period
     */
    private function getLowStockItemsForPeriod($companyId, $startDate, $endDate)
    {
        $lowStockThreshold = 10;
        
        return CentralStore::where('company_id', $companyId)
            ->where('status', 'completed')
            ->where('quantity', '>', 0)
            ->where('quantity', '<=', $lowStockThreshold)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Get out of stock items for a specific period
     */
    private function getOutOfStockItemsForPeriod($companyId, $startDate, $endDate)
    {
        return CentralStore::where('company_id', $companyId)
            ->where('status', 'completed')
            ->where('quantity', '=', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Get total value for a specific period
     */
    private function getTotalValueForPeriod($companyId, $startDate, $endDate)
    {
        return CentralStore::where('company_id', $companyId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('quantity * unit_price'));
    }

    /**
     * Debug method for testing warehouse dashboard
     */
    public function debug(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            // Return debug information
            return response()->json([
                'success' => true,
                'debug' => true,
                'test_data' => true,
                'message' => 'Debug route working',
                'company_id' => $companyId,
                'user_id' => Auth::id(),
                'timestamp' => now()->toISOString(),
                'data' => [
                    'total_items' => 1247,
                    'approved_requisitions' => 89,
                    'outbound_orders' => 156,
                    'waybills' => 134,
                    'test_mode' => true
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Debug route error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'debug' => true,
                'error' => $e->getMessage(),
                'message' => 'Debug route error'
            ], 500);
        }
    }

    /**
     * Test method for basic connectivity
     */
    public function test(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        try {
            // Return basic test information
            return response()->json([
                'success' => true,
                'test' => true,
                'message' => 'Test route working',
                'company_id' => $companyId,
                'user_id' => Auth::id(),
                'timestamp' => now()->toISOString(),
                'connectivity' => 'OK'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Test route error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'test' => true,
                'error' => $e->getMessage(),
                'message' => 'Test route error'
            ], 500);
        }
    }
}