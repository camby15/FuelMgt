<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentralStore;
use App\Models\Requisition;
use App\Models\Wh_PurchaseOrder;
use App\Models\Wh_Supplier;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Rap2hpoutre\FastExcel\FastExcel;

class WarehouseReportController extends Controller
{
    /**
     * Get analytics data (extracted for reuse in reports)
     */
    private function getAnalyticsData($companyId, $startDate = null, $endDate = null)
    {
        try {
            Log::info('Starting getAnalyticsData', ['company_id' => $companyId, 'start_date' => $startDate, 'end_date' => $endDate]);

            // Apply date filters if provided
            $dateQuery = CentralStore::where('company_id', $companyId)
                ->where('status', 'completed');

            if ($startDate && $endDate) {
                $dateQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            // Total Inventory Count and Value
            $totalInventory = (clone $dateQuery)->sum('quantity');
            $totalInventoryValue = (clone $dateQuery)->sum('total_price');

            Log::info('Basic inventory data retrieved', [
                'total_inventory' => $totalInventory,
                'total_inventory_value' => $totalInventoryValue
            ]);

            // Total Inventory Last Month for comparison
            $totalInventoryLastMonth = CentralStore::where('company_id', $companyId)
                ->where('status', 'completed')
                ->where('created_at', '<', now()->startOfMonth())
                ->where('created_at', '>=', now()->subMonth()->startOfMonth())
                ->sum('quantity');

            // Calculate inventory change percentage
            $inventoryChangePercent = $totalInventoryLastMonth > 0 ?
                round((($totalInventory - $totalInventoryLastMonth) / $totalInventoryLastMonth) * 100, 1) : 0;

            // Pending Requisitions
            $pendingRequisitions = Requisition::where('company_id', $companyId)
                ->where('status', 'pending')
                ->count();

            // New requisitions today
            $newRequisitionsToday = Requisition::where('company_id', $companyId)
                ->where('status', 'pending')
                ->whereDate('created_at', today())
                ->count();

            // Apply date filters to POs if provided
            $poDateQuery = Wh_PurchaseOrder::where('company_id', $companyId);

            if ($startDate && $endDate) {
                $poDateQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            // Active POs (pending status)
            $activePOs = (clone $poDateQuery)->where('status', 'pending')->count();

            // POs pending approval (created status)
            $posPendingApproval = (clone $poDateQuery)->where('status', 'created')->count();

            // Approved requisitions this month
            $approvedRequisitions = Requisition::where('company_id', $companyId)
                ->where('status', 'approved')
                ->whereMonth('approved_at', now()->month)
                ->whereYear('approved_at', now()->year)
                ->count();

            // Total suppliers
            $totalSuppliers = Wh_Supplier::where('company_id', $companyId)->count();

            // Re-order POs (auto-generated)
            $reorderPOs = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('is_reorder', true)
                ->count();

            // Items received within date range (or this month if no filters)
            $itemsReceivedQuery = CentralStore::where('company_id', $companyId)
                ->where('status', 'completed');

            if ($startDate && $endDate) {
                $itemsReceivedQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            } else {
                $itemsReceivedQuery->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            }

            $itemsReceived = $itemsReceivedQuery->sum('quantity');

            // Items issued this month (from requisitions)
            $requisitionsForItems = Requisition::where('company_id', $companyId)
                ->where('status', 'approved')
                ->whereMonth('approved_at', now()->month)
                ->whereYear('approved_at', now()->year)
                ->get();

            $itemsIssued = 0;
            foreach ($requisitionsForItems as $req) {
                if ($req->items && is_array($req->items)) {
                    foreach ($req->items as $item) {
                        $itemsIssued += $item['quantity'] ?? 0;
                    }
                }
            }

            // Quality inspections count
            try {
                $qualityInspections = \App\Models\QualityInspection::where('company_id', $companyId)->count();
            } catch (\Exception $e) {
                $qualityInspections = 0;
            }

            // Team performance metrics
            try {
                $totalTeams = \App\Models\TeamParing::where('company_id', $companyId)->count();
                $activeTeams = \App\Models\TeamParing::where('company_id', $companyId)
                    ->where('team_status', 'active')->count();

                $totalAssignments = \App\Models\ProjectManagement\SiteAssignment::where('company_id', $companyId)->count();
                $completedAssignments = \App\Models\ProjectManagement\SiteAssignment::where('company_id', $companyId)
                    ->where('status', 'completed')->count();

                $assignmentsThisMonth = \App\Models\ProjectManagement\SiteAssignment::where('company_id', $companyId)
                    ->whereMonth('assigned_date', now()->month)
                    ->whereYear('assigned_date', now()->year)
                    ->count();

                // Top performing teams (by completed assignments)
                $topTeams = \App\Models\ProjectManagement\SiteAssignment::where('company_id', $companyId)
                    ->where('status', 'completed')
                    ->with('team')
                    ->selectRaw('team_id, COUNT(*) as completed_count')
                    ->groupBy('team_id')
                    ->orderBy('completed_count', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function($assignment) {
                        return [
                            'team_name' => $assignment->team ? $assignment->team->team_name : 'Unknown Team',
                            'completed_assignments' => $assignment->completed_count
                        ];
                    });

                $teamPerformance = [
                    'total_teams' => $totalTeams,
                    'active_teams' => $activeTeams,
                    'total_assignments' => $totalAssignments,
                    'completed_assignments' => $completedAssignments,
                    'assignments_this_month' => $assignmentsThisMonth,
                    'top_performing_teams' => $topTeams
                ];
            } catch (\Exception $e) {
                $teamPerformance = [
                    'total_teams' => 0,
                    'active_teams' => 0,
                    'total_assignments' => 0,
                    'completed_assignments' => 0,
                    'assignments_this_month' => 0,
                    'top_performing_teams' => []
                ];
            }

            // Total batches
            try {
                $totalBatches = CentralStore::where('company_id', $companyId)
                    ->whereNotNull('batch_number')
                    ->distinct('batch_number')
                    ->count('batch_number');
            } catch (\Exception $e) {
                $totalBatches = 0;
            }

            // Warehouse Operations Summary with date filtering
            $receivingQuery = \App\Models\POReceiving::where('company_id', $companyId);

            if ($startDate && $endDate) {
                $receivingQuery->whereBetween('receiving_date', [$startDate, $endDate]);
                $receivingToday = (clone $receivingQuery)->whereDate('receiving_date', today())->sum('total_received') ?? 0;
                $receivingWeek = (clone $receivingQuery)->whereBetween('receiving_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_received') ?? 0;
                $receivingMonth = $receivingQuery->sum('total_received') ?? 0;
            } else {
                $receivingToday = (clone $receivingQuery)->whereDate('receiving_date', today())->sum('total_received') ?? 0;
                $receivingWeek = (clone $receivingQuery)->whereBetween('receiving_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_received') ?? 0;
                $receivingMonth = (clone $receivingQuery)->whereMonth('receiving_date', now()->month)->whereYear('receiving_date', now()->year)->sum('total_received') ?? 0;
            }

            // Issuing Operations with date filtering
            $issuingQuery = \App\Models\OutboundOrder::where('company_id', $companyId);

            if ($startDate && $endDate) {
                $issuingQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                $issuingToday = (clone $issuingQuery)->whereDate('created_at', today())->get()->sum(function ($order) {
                    return collect($order->items)->sum('quantity');
                });
                $issuingWeek = (clone $issuingQuery)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->get()->sum(function ($order) {
                    return collect($order->items)->sum('quantity');
                });
                $issuingMonth = $issuingQuery->get()->sum(function ($order) {
                    return collect($order->items)->sum('quantity');
                });
            } else {
                $issuingToday = (clone $issuingQuery)->whereDate('created_at', today())->get()->sum(function ($order) {
                    return collect($order->items)->sum('quantity');
                });
                $issuingWeek = (clone $issuingQuery)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->get()->sum(function ($order) {
                    return collect($order->items)->sum('quantity');
                });
                $issuingMonth = (clone $issuingQuery)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->get()->sum(function ($order) {
                    return collect($order->items)->sum('quantity');
                });
            }

            // Quality Inspections Breakdown with date filtering
            $inspectionQuery = \App\Models\QualityInspection::where('company_id', $companyId);

            if ($startDate && $endDate) {
                $inspectionQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            $inspectionsPassed = (clone $inspectionQuery)->where('status', 'passed')->sum('quantity');
            $inspectionsFailed = (clone $inspectionQuery)->where('status', 'failed')->sum('quantity');
            $inspectionsPending = (clone $inspectionQuery)->where('status', 'pending')->sum('quantity');

            // Low Stock Items (assuming reorder level is 10 for now)
            $lowStockItems = CentralStore::where('company_id', $companyId)
                ->where('status', 'completed')
                ->where('quantity', '<=', 10)
                ->get();

            $lowStockCount = $lowStockItems->count();

            // Get low stock items details for table
            $lowStockItemsData = $lowStockItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'current_stock' => $item->quantity,
                    'reorder_level' => 10, // Default reorder level
                    'category' => $item->item_category,
                    'unit' => $item->unit ?? 'pcs',
                    'unit_price' => $item->unit_price
                ];
            });

            // Chart Data - Monthly inventory trends for the last 12 months (with date filtering if provided)
            $chartData = $this->getInventoryChartData($companyId, 'month', $startDate, $endDate);

            // Recent Activities (last 10 activities)
            $recentActivities = collect();

            // Recent requisition activities
            $recentRequisitions = Requisition::where('company_id', $companyId)
                ->where('status', 'approved')
                ->where('approved_at', '>=', now()->subDays(7))
                ->orderBy('approved_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($recentRequisitions as $req) {
                $recentActivities->push([
                    'type' => 'requisition_approved',
                    'title' => "Requisition #{$req->requisition_number} approved",
                    'time' => $req->approved_at,
                    'status' => 'completed',
                    'icon' => 'fas fa-check-circle text-success'
                ]);
            }

            // Recent low stock alerts
            $lowStockAlerts = CentralStore::where('company_id', $companyId)
                ->where('status', 'completed')
                ->where('quantity', '<=', 10)
                ->orderBy('updated_at', 'desc')
                ->limit(3)
                ->get();

            foreach ($lowStockAlerts as $item) {
                $recentActivities->push([
                    'type' => 'low_stock',
                    'title' => "Low stock alert: {$item->item_name} ({$item->quantity} left)",
                    'time' => $item->updated_at,
                    'status' => 'warning',
                    'icon' => 'fas fa-exclamation-triangle text-warning'
                ]);
            }

            // Recent PO activities
            $recentPOs = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('status', 'pending')
                ->where('approved_at', '>=', now()->subDays(7))
                ->orderBy('approved_at', 'desc')
                ->limit(3)
                ->get();

            foreach ($recentPOs as $po) {
                $recentActivities->push([
                    'type' => 'po_shipped',
                    'title' => "PO #{$po->po_number} shipped",
                    'time' => $po->approved_at,
                    'status' => 'in_transit',
                    'icon' => 'fas fa-truck text-info'
                ]);
            }

            // Sort activities by time and take the most recent 10
            $recentActivities = $recentActivities->sortByDesc('time')->take(10)->values();

            Log::info('Analytics data compilation completed successfully');

            return [
                'total_inventory' => $totalInventory,
                'total_inventory_value' => $totalInventoryValue,
                'inventory_change_percent' => $inventoryChangePercent,
                'pending_requisitions' => $pendingRequisitions,
                'new_requisitions_today' => $newRequisitionsToday,
                'active_pos' => $activePOs,
                'pos_pending_approval' => $posPendingApproval,
                'low_stock_count' => $lowStockCount,
                'low_stock_items' => $lowStockItemsData,
                'recent_activities' => $recentActivities,
                'chart_data' => $chartData,
                'approved_requisitions' => $approvedRequisitions,
                'total_suppliers' => $totalSuppliers,
                'reorder_pos' => $reorderPOs,
                'items_received' => $itemsReceived,
                'items_issued' => $itemsIssued,
                'quality_inspections' => $qualityInspections,
                'total_batches' => $totalBatches,
                'team_performance' => $teamPerformance,
                // Warehouse Operations Summary
                'warehouse_operations' => [
                    'receiving' => [
                        'today' => $receivingToday,
                        'week' => $receivingWeek,
                        'month' => $receivingMonth
                    ],
                    'issuing' => [
                        'today' => $issuingToday,
                        'week' => $issuingWeek,
                        'month' => $issuingMonth
                    ],
                    'inspections' => [
                        'passed' => $inspectionsPassed,
                        'failed' => $inspectionsFailed,
                        'pending' => $inspectionsPending
                    ]
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Error in getAnalyticsData method', [
                'company_id' => $companyId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return default data structure on error
            return [
                'total_inventory' => 0,
                'total_inventory_value' => 0,
                'inventory_change_percent' => 0,
                'pending_requisitions' => 0,
                'new_requisitions_today' => 0,
                'active_pos' => 0,
                'pos_pending_approval' => 0,
                'low_stock_count' => 0,
                'low_stock_items' => [],
                'recent_activities' => [],
                'chart_data' => ['labels' => [], 'inventory_values' => [], 'inventory_counts' => []],
                'approved_requisitions' => 0,
                'total_suppliers' => 0,
                'reorder_pos' => 0,
                'items_received' => 0,
                'items_issued' => 0,
                'quality_inspections' => 0,
                'total_batches' => 0,
                'team_performance' => [
                    'total_teams' => 0,
                    'active_teams' => 0,
                    'total_assignments' => 0,
                    'completed_assignments' => 0,
                    'assignments_this_month' => 0,
                    'top_performing_teams' => []
                ],
                'warehouse_operations' => [
                    'receiving' => ['today' => 0, 'week' => 0, 'month' => 0],
                    'issuing' => ['today' => 0, 'week' => 0, 'month' => 0],
                    'inspections' => ['passed' => 0, 'failed' => 0, 'pending' => 0]
                ]
            ];
        }
    }

    /**
     * Get inventory analytics dashboard data
     */
    public function getInventoryAnalytics(Request $request)
    {
        try {
            Log::info('Starting getInventoryAnalytics', [
                'session_company_id' => session('selected_company_id'),
                'user_id' => auth()->id(),
                'user_company_id' => auth()->user() ? auth()->user()->company_id : null
            ]);

            $companyId = session('selected_company_id');
            if (!$companyId) {
                // Try to get company_id from authenticated user
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    // Fallback to company_id = 1 for testing
                    $companyId = 1;
                }
            }

            Log::info('Using company_id', ['company_id' => $companyId]);

            // Get date filters from request
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Get analytics data
            $analyticsData = $this->getAnalyticsData($companyId, $startDate, $endDate);

            return response()->json([
                'success' => true,
                'data' => $analyticsData
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching inventory analytics: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to fetch analytics data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get inventory chart data for different time periods
     */
    private function getInventoryChartData($companyId, $period = 'month', $startDate = null, $endDate = null)
     {
         $chartData = [];
         $labels = [];
         $inventoryValues = [];
         $inventoryCounts = [];

        // Apply date filters if provided
        $baseQuery = CentralStore::where('company_id', $companyId)
            ->where('status', 'completed');

        if ($startDate && $endDate) {
            $baseQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($period === 'week') {
            // Get data for the last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dayStart = $date->copy()->startOfDay();
                $dayEnd = $date->copy()->endOfDay();

                $labels[] = $date->format('D');

                // Get inventory value for this day
                $dayValue = (clone $baseQuery)->whereBetween('created_at', [$dayStart, $dayEnd])->sum('total_price');

                $inventoryValues[] = round($dayValue, 2);

                // Get inventory count for this day
                $dayCount = (clone $baseQuery)->whereBetween('created_at', [$dayStart, $dayEnd])->sum('quantity');

                $inventoryCounts[] = $dayCount;
            }
        } elseif ($period === 'month') {
            // Get data for the last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthStart = $month->copy()->startOfMonth();
                $monthEnd = $month->copy()->endOfMonth();

                $labels[] = $month->format('M Y');

                // Get inventory value for this month
                $monthValue = (clone $baseQuery)->whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_price');

                $inventoryValues[] = round($monthValue, 2);

                // Get inventory count for this month
                $monthCount = (clone $baseQuery)->whereBetween('created_at', [$monthStart, $monthEnd])->sum('quantity');

                $inventoryCounts[] = $monthCount;
            }
        } elseif ($period === 'year') {
            // Get data for the last 5 years
            for ($i = 4; $i >= 0; $i--) {
                $year = now()->subYears($i);
                $yearStart = $year->copy()->startOfYear();
                $yearEnd = $year->copy()->endOfYear();

                $labels[] = $year->format('Y');

                // Get inventory value for this year
                $yearValue = (clone $baseQuery)->whereBetween('created_at', [$yearStart, $yearEnd])->sum('total_price');

                $inventoryValues[] = round($yearValue, 2);

                // Get inventory count for this year
                $yearCount = (clone $baseQuery)->whereBetween('created_at', [$yearStart, $yearEnd])->sum('quantity');

                $inventoryCounts[] = $yearCount;
            }
        }

        return [
             'labels' => $labels,
             'inventory_values' => $inventoryValues,
             'inventory_counts' => $inventoryCounts
         ];
    }

    /**
     * Get chart data for specific period
     */
    public function getChartData(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                // Try to get company_id from authenticated user
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    // Fallback to company_id = 1 for testing
                    $companyId = 1;
                }
            }

            $period = $request->input('period', 'month');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $chartData = $this->getInventoryChartData($companyId, $period, $startDate, $endDate);

            return response()->json([
                'success' => true,
                'data' => $chartData
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching chart data: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch chart data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate and download inventory report
     */
    public function generateReport(Request $request)
    {
        Log::info('generateReport method called', [
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl(),
            'request_params' => $request->all(),
            'user_id' => auth()->id(),
            'session_company_id' => session('selected_company_id')
        ]);

        try {
            Log::info('Starting generateReport', [
                'session_company_id' => session('selected_company_id'),
                'user_id' => auth()->id(),
                'user_company_id' => auth()->user() ? auth()->user()->company_id : null,
                'request_params' => $request->all()
            ]);

            $companyId = session('selected_company_id');
            if (!$companyId) {
                // Try to get company_id from authenticated user
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    // Fallback to company_id = 1 for testing
                    $companyId = 1;
                }
            }

            Log::info('Using company_id for report', ['company_id' => $companyId]);

            $reportType = $request->input('type', 'inventory');
            $format = $request->input('format', 'pdf');

            Log::info('Report parameters', ['type' => $reportType, 'format' => $format]);

            // Get date filters from request for filtered reports
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Get comprehensive data for the report by calling the analytics logic directly
            $reportData = $this->getAnalyticsData($companyId, $startDate, $endDate);

            Log::info('Report data retrieved', ['data_keys' => array_keys($reportData)]);

            // Debug: Log the report data structure
            Log::info('Report generation data', [
                'format' => $format,
                'data_keys' => array_keys($reportData),
                'has_total_inventory' => isset($reportData['total_inventory']),
                'total_inventory_value' => $reportData['total_inventory'] ?? 'NOT_SET'
            ]);

            // Generate report based on type and format
            if ($format === 'pdf') {
                return $this->generatePdfReport($reportData, $reportType);
            } elseif ($format === 'excel') {
                return $this->generateExcelReport($reportData, $reportType);
            } elseif ($format === 'csv') {
                return $this->generateCsvReport($reportData, $reportType);
            } else {
                return response()->json(['error' => 'Unsupported format'], 400);
            }

        } catch (\Exception $e) {
            Log::error('Error generating report: ' . $e->getMessage());
            Log::error('Report generation stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to generate report',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF report
     */
    private function generatePdfReport($data, $type)
    {
        try {
            // Get company name
            $companyName = 'Company Name'; // You can get this from session or database
            $reportPeriod = now()->format('F Y');
            $reportType = ucfirst($type) . ' Report';

            // Generate PDF using the facade
            $pdf = Pdf::loadView('reports.inventory-report', [
                'data' => $data,
                'companyName' => $companyName,
                'reportPeriod' => $reportPeriod,
                'reportType' => $reportType
            ]);

            // Set PDF options
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'isPhpEnabled' => true,
                'isJavascriptEnabled' => false
            ]);

            // Generate filename
            $filename = 'inventory-report-' . now()->format('Y-m-d-H-i-s') . '.pdf';

            // Return PDF download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error generating PDF report: ' . $e->getMessage());
            Log::error('PDF Error trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to generate PDF report',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Excel report
     */
    private function generateExcelReport($data, $type)
    {
        try {
            // Prepare data for Excel export
            $excelData = $this->prepareExcelData($data);

            // Generate filename
            $filename = 'inventory-report-' . now()->format('Y-m-d-H-i-s') . '.xlsx';

            // Create Excel file using FastExcel
            return (new FastExcel($excelData))->download($filename);

        } catch (\Exception $e) {
            Log::error('Error generating Excel report: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate Excel report',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate CSV report
     */
    private function generateCsvReport($data, $type)
    {
        try {
            // Prepare data for CSV export
            $csvData = $this->prepareExcelData($data);

            // Generate filename
            $filename = 'inventory-report-' . now()->format('Y-m-d-H-i-s') . '.csv';

            // Create CSV content
            $csvContent = '';
            if (!empty($csvData)) {
                // Add headers
                $headers = array_keys($csvData[0]);
                $csvContent .= implode(',', array_map(function($header) {
                    return '"' . str_replace('"', '""', $header) . '"';
                }, $headers)) . "\n";

                // Add data rows
                foreach ($csvData as $row) {
                    $csvContent .= implode(',', array_map(function($value) {
                        return '"' . str_replace('"', '""', $value) . '"';
                    }, array_values($row))) . "\n";
                }
            }

            // Return CSV download
            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            Log::error('Error generating CSV report: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate CSV report',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prepare data for Excel export
     */
    private function prepareExcelData($data)
    {
        try {
            // Ensure data is an array
            if (!is_array($data)) {
                Log::error('prepareExcelData received non-array data', ['data_type' => gettype($data)]);
                $data = [];
            }

            $excelData = [];

            // Add summary sheet
            $excelData[] = [
                'Metric' => 'Total Inventory',
                'Value' => $data['total_inventory'] ?? 0,
                'Description' => 'Total number of inventory items'
            ];
            $excelData[] = [
                'Metric' => 'Total Inventory Value',
                'Value' => '$' . number_format(($data['total_inventory_value'] ?? 0), 2),
                'Description' => 'Total monetary value of inventory'
            ];
            $excelData[] = [
                'Metric' => 'Pending Requisitions',
                'Value' => $data['pending_requisitions'] ?? 0,
                'Description' => 'Number of pending requisitions'
            ];
            $excelData[] = [
                'Metric' => 'Active Purchase Orders',
                'Value' => $data['active_pos'] ?? 0,
                'Description' => 'Number of active purchase orders'
            ];
            $excelData[] = [
                'Metric' => 'Low Stock Items',
                'Value' => $data['low_stock_count'] ?? 0,
                'Description' => 'Number of items below reorder level'
            ];
            $excelData[] = [
                'Metric' => 'Inventory Change %',
                'Value' => ($data['inventory_change_percent'] ?? 0) . '%',
                'Description' => 'Percentage change from last month'
            ];
            $excelData[] = [
                'Metric' => 'New Requisitions Today',
                'Value' => $data['new_requisitions_today'] ?? 0,
                'Description' => 'Number of new requisitions created today'
            ];
            $excelData[] = [
                'Metric' => 'POs Pending Approval',
                'Value' => $data['pos_pending_approval'] ?? 0,
                'Description' => 'Number of purchase orders pending approval'
            ];

            // Add warehouse operations summary
            $excelData[] = ['', '', ''];
            $excelData[] = ['WAREHOUSE OPERATIONS SUMMARY', '', ''];
            $excelData[] = ['Operation', 'Today', 'This Week', 'This Month'];

            if (isset($data['warehouse_operations'])) {
                $ops = $data['warehouse_operations'];

                $excelData[] = [
                    'Operation' => 'Items Received',
                    'Today' => $ops['receiving']['today'] ?? 0,
                    'This Week' => $ops['receiving']['week'] ?? 0,
                    'This Month' => $ops['receiving']['month'] ?? 0
                ];
                $excelData[] = [
                    'Operation' => 'Items Issued',
                    'Today' => $ops['issuing']['today'] ?? 0,
                    'This Week' => $ops['issuing']['week'] ?? 0,
                    'This Month' => $ops['issuing']['month'] ?? 0
                ];
                $excelData[] = [
                    'Operation' => 'Quality Inspections Passed',
                    'Today' => $ops['inspections']['passed'] ?? 0,
                    'This Week' => '',
                    'This Month' => ''
                ];
                $excelData[] = [
                    'Operation' => 'Quality Inspections Failed',
                    'Today' => $ops['inspections']['failed'] ?? 0,
                    'This Week' => '',
                    'This Month' => ''
                ];
                $excelData[] = [
                    'Operation' => 'Quality Inspections Pending',
                    'Today' => $ops['inspections']['pending'] ?? 0,
                    'This Week' => '',
                    'This Month' => ''
                ];
            }

            // Add empty row for separation
            $excelData[] = ['', '', '', ''];
            $excelData[] = ['LOW STOCK ITEMS', '', '', ''];
            $excelData[] = ['Item Name', 'Current Stock', 'Reorder Level', 'Unit Price', 'Status'];

            // Add low stock items
            if (isset($data['low_stock_items']) && is_array($data['low_stock_items'])) {
                foreach ($data['low_stock_items'] as $item) {
                    $status = 'In Stock';
                    if (($item['current_stock'] ?? 0) <= 0) {
                        $status = 'Out of Stock';
                    } elseif (($item['current_stock'] ?? 0) <= ($item['reorder_level'] ?? 0)) {
                        $status = 'Low Stock';
                    }

                    $excelData[] = [
                        'Item Name' => $item['item_name'] ?? 'N/A',
                        'Current Stock' => $item['current_stock'] ?? 0,
                        'Reorder Level' => $item['reorder_level'] ?? 0,
                        'Unit Price' => '$' . number_format(($item['unit_price'] ?? 0), 2),
                        'Status' => $status
                    ];
                }
            }

            // Add empty row for separation
            $excelData[] = ['', '', '', '', ''];
            $excelData[] = ['RECENT ACTIVITIES', '', '', '', ''];
            $excelData[] = ['Activity', 'Time', 'Status', '', ''];

            // Add recent activities
            if (isset($data['recent_activities']) && is_array($data['recent_activities'])) {
                foreach ($data['recent_activities'] as $activity) {
                    $excelData[] = [
                        'Activity' => $activity['title'] ?? 'N/A',
                        'Time' => isset($activity['time']) ? date('M j, Y g:i A', strtotime($activity['time'])) : 'N/A',
                        'Status' => ucfirst($activity['status'] ?? 'Unknown'),
                        '',
                        ''
                    ];
                }
            }

            // Add empty row for separation
            $excelData[] = ['', '', '', '', ''];
            $excelData[] = ['INVENTORY TRENDS', '', '', '', ''];
            $excelData[] = ['Period', 'Inventory Value', 'Inventory Count', '', ''];

            // Add chart data
            if (isset($data['chart_data']) && isset($data['chart_data']['labels'])) {
                foreach (($data['chart_data']['labels'] ?? []) as $index => $label) {
                    $excelData[] = [
                        'Period' => $label,
                        'Inventory Value' => '$' . number_format(($data['chart_data']['inventory_values'][$index] ?? 0), 2),
                        'Inventory Count' => $data['chart_data']['inventory_counts'][$index] ?? 0,
                        '',
                        ''
                    ];
                }
            }

            Log::info('Excel data prepared successfully', ['rows_count' => count($excelData)]);
            return $excelData;

        } catch (\Exception $e) {
            Log::error('Error preparing Excel data: ' . $e->getMessage());
            Log::error('Excel data preparation stack trace: ' . $e->getTraceAsString());

            // Return minimal data structure on error
            return [
                ['Error preparing report data', '', ''],
                ['Please try again or contact support', '', '']
            ];
        }
    }

    /**
     * Get detailed inventory report data
     */
    public function getInventoryDetails(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                // Try to get company_id from authenticated user
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    // Fallback to company_id = 1 for testing
                    $companyId = 1;
                }
            }

            // Apply date filters if provided
            $query = CentralStore::where('company_id', $companyId)
                ->where('status', 'completed');

            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            $inventory = $query->with(['qualityInspection.purchaseOrder.supplier'])
                ->select([
                    'id', 'item_name', 'item_category', 'batch_number', 'quantity',
                    'unit_price', 'total_price', 'location', 'status', 'created_at'
                ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    return [
                        'item_name' => $item->item_name,
                        'category' => $item->item_category,
                        'batch_number' => $item->batch_number,
                        'quantity' => $item->quantity,
                        'unit_price' => number_format($item->unit_price, 2),
                        'total_value' => number_format($item->total_price, 2),
                        'location' => $item->location ?? 'Warehouse',
                        'status' => $item->status
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $inventory
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting inventory details', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load inventory details'
            ], 500);
        }
    }

    /**
     * Get detailed procurement report data
     */
    public function getProcurementDetails(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                // Try to get company_id from authenticated user
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    // Fallback to company_id = 1 for testing
                    $companyId = 1;
                }
            }

            // Apply date filters if provided
            $query = Wh_PurchaseOrder::where('company_id', $companyId);

            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }

            $procurement = $query->with(['supplier', 'approvedBy'])
                ->select([
                    'id', 'po_number', 'supplier_id', 'order_date', 'total_amount',
                    'status', 'is_reorder', 'created_by', 'created_at'
                ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($po) {
                    return [
                        'po_number' => $po->po_number,
                        'supplier_name' => $po->supplier ? $po->supplier->company_name : 'N/A',
                        'order_date' => $po->order_date ? date('M j, Y', strtotime($po->order_date)) : 'N/A',
                        'total_amount' => number_format($po->total_amount, 2),
                        'status' => $po->status,
                        'is_reorder' => $po->is_reorder ?? false,
                        'created_by' => $po->approvedBy ? $po->approvedBy->name : 'System'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $procurement
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting procurement details', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load procurement details'
            ], 500);
        }
    }

    /**
     * Get detailed requisition report data
     */
    public function getRequisitionDetails(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                // Try to get company_id from authenticated user
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    // Fallback to company_id = 1 for testing
                    $companyId = 1;
                }
            }

            $requisitions = Requisition::where('company_id', $companyId)
                ->with(['requestor.personalInfo'])
                ->select([
                    'id', 'requisition_number', 'requester_id', 'department',
                    'created_at', 'status', 'priority', 'items'
                ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($req) {
                    $totalAmount = 0;
                    if ($req->items && is_array($req->items)) {
                        foreach ($req->items as $item) {
                            $totalAmount += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
                        }
                    }

                    return [
                        'requisition_number' => $req->requisition_number,
                        'requested_by' => $req->requestor && $req->requestor->personalInfo ?
                            trim(($req->requestor->personalInfo->first_name ?? '') . ' ' . ($req->requestor->personalInfo->last_name ?? '')) : 'N/A',
                        'department' => $req->department ?? 'N/A',
                        'date' => date('M j, Y', strtotime($req->created_at)),
                        'total_amount' => number_format($totalAmount, 2),
                        'status' => $req->status,
                        'priority' => $req->priority
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $requisitions
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting requisition details', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load requisition details'
            ], 500);
        }
    }

    /**
     * Get detailed batch tracking report data
     */
    public function getBatchDetails(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                // Try to get company_id from authenticated user
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    // Fallback to company_id = 1 for testing
                    $companyId = 1;
                }
            }

            // Get batch tracking data from quality inspections and central store
            $batches = CentralStore::where('company_id', $companyId)
                ->whereNotNull('batch_number')
                ->selectRaw('batch_number, ANY_VALUE(item_name) as item_name, SUM(quantity) as total_quantity, MIN(created_at) as first_created, COUNT(*) as reorders')
                ->groupBy('batch_number')
                ->orderBy('first_created', 'desc')
                ->get()
                ->map(function($batch) {
                    return [
                        'batch_number' => $batch->batch_number,
                        'item_name' => $batch->item_name,
                        'original_quantity' => $batch->total_quantity,
                        'current_quantity' => $batch->total_quantity, // This would need more complex logic
                        'reorders' => max(0, $batch->reorders - 1),
                        'total_added' => $batch->total_quantity,
                        'created_date' => date('M j, Y', strtotime($batch->first_created))
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $batches
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting batch details', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load batch details'
            ], 500);
        }
    }

    /**
     * Get detailed supplier performance report data
     */
    public function getSupplierDetails(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                // Try to get company_id from authenticated user
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    // Fallback to company_id = 1 for testing
                    $companyId = 1;
                }
            }

            $suppliers = Wh_Supplier::where('company_id', $companyId)
                ->with(['purchaseOrders'])
                ->select([
                    'id', 'company_name', 'created_at'
                ])
                ->get()
                ->map(function($supplier) {
                    $totalPOs = $supplier->purchaseOrders->count();
                    $totalSpend = $supplier->purchaseOrders->sum('total_amount');
                    $completedPOs = $supplier->purchaseOrders->where('status', 'completed')->count();
                    $onTimeDelivery = $totalPOs > 0 ? round(($completedPOs / $totalPOs) * 100, 1) : 0;

                    $lastOrder = $supplier->purchaseOrders->max('created_at');

                    return [
                        'name' => $supplier->company_name,
                        'total_pos' => $totalPOs,
                        'total_spend' => number_format($totalSpend, 2),
                        'on_time_delivery' => $onTimeDelivery,
                        'quality_score' => rand(3, 5), // Placeholder - would need actual quality ratings
                        'last_order' => $lastOrder ? date('M j, Y', strtotime($lastOrder)) : 'Never'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $suppliers
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting supplier details', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load supplier details'
            ], 500);
        }
    }

    /**
     * Get detailed financial analysis report data
     */
    public function getFinancialDetails(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                // Try to get company_id from authenticated user
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    // Fallback to company_id = 1 for testing
                    $companyId = 1;
                }
            }

            // Calculate financial metrics
            $totalSpend = Wh_PurchaseOrder::where('company_id', $companyId)
                ->whereIn('status', ['completed', 'approved'])
                ->sum('total_amount');

            $totalPOs = Wh_PurchaseOrder::where('company_id', $companyId)
                ->whereIn('status', ['completed', 'approved'])
                ->count();

            $avgPOValue = $totalPOs > 0 ? $totalSpend / $totalPOs : 0;

            // Calculate tax (simplified - would need actual tax calculations)
            $totalTax = $totalSpend * 0.15; // Assuming 15% tax rate

            $pendingPayments = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('status', 'approved')
                ->sum('total_amount');

            return response()->json([
                'success' => true,
                'data' => [
                    'total_spend' => number_format($totalSpend, 2),
                    'avg_po_value' => number_format($avgPOValue, 2),
                    'total_tax' => number_format($totalTax, 2),
                    'pending_payments' => number_format($pendingPayments, 2)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting financial details', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load financial details'
            ], 500);
        }
    }

    /**
     * Get pending approvals for dashboard
     */
    public function getPendingApprovals(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    $companyId = 1;
                }
            }

            // Get pending requisitions
            $pendingRequisitions = Requisition::where('company_id', $companyId)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($req) {
                    return [
                        'id' => $req->id,
                        'title' => "Requisition #{$req->requisition_number}",
                        'description' => $req->title ?? 'Pending requisition approval',
                        'created_at' => $req->created_at
                    ];
                });

            // Get pending POs
            $pendingPOs = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('status', 'created')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($po) {
                    return [
                        'id' => $po->id,
                        'title' => "PO #{$po->po_number}",
                        'description' => $po->supplier ? "Supplier: {$po->supplier->company_name}" : 'Pending PO approval',
                        'created_at' => $po->created_at
                    ];
                });

            $pendingApprovals = $pendingRequisitions->concat($pendingPOs)->sortByDesc('created_at')->take(10)->values();

            return response()->json([
                'success' => true,
                'data' => $pendingApprovals
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting pending approvals', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load pending approvals'
            ], 500);
        }
    }

    /**
     * Get reorder purchase orders for dashboard
     */
    public function getReorderPOs(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    $companyId = 1;
                }
            }

            $reorderPOs = Wh_PurchaseOrder::where('company_id', $companyId)
                ->where('is_reorder', true)
                ->with(['supplier'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($po) {
                    // Get items from the PO (assuming items are stored in a JSON field or related table)
                    $items = $po->items ?? [];
                    $itemNames = is_array($items) ? collect($items)->pluck('name')->join(', ') : 'N/A';
                    $batchNumbers = is_array($items) ? collect($items)->pluck('batch_number')->filter()->join(', ') : 'N/A';
                    $totalQuantity = is_array($items) ? collect($items)->sum('quantity') : 0;

                    return [
                        'po_number' => $po->po_number,
                        'supplier' => $po->supplier ? $po->supplier->company_name : 'N/A',
                        'items' => $itemNames,
                        'batch_numbers' => $batchNumbers,
                        'quantity' => $totalQuantity,
                        'amount' => number_format($po->total_amount, 2),
                        'type' => 'Auto Reorder',
                        'status' => $po->status,
                        'date' => $po->created_at ? date('M j, Y', strtotime($po->created_at)) : 'N/A'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $reorderPOs
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting reorder POs', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load reorder POs'
            ], 500);
        }
    }

    /**
     * Create reorder purchase order for low stock item
     */
    public function reorderItem(Request $request, $itemId)
    {
        try {
            $companyId = session('selected_company_id');
            if (!$companyId) {
                $user = auth()->user();
                if ($user && $user->company_id) {
                    $companyId = $user->company_id;
                } else {
                    $companyId = 1;
                }
            }

            // Get the item details
            $item = CentralStore::where('company_id', $companyId)
                ->where('id', $itemId)
                ->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found'
                ], 404);
            }

            // Calculate reorder quantity (current stock + reorder level)
            $reorderQuantity = ($item->quantity <= 10) ? (10 - $item->quantity + 10) : 10; // Reorder to bring to reorder level + buffer

            // Get supplier from the item (assuming supplier_id exists)
            $supplierId = $item->supplier_id ?? 1; // Default to first supplier if not set

            // Find original PO to copy tax configuration
            $originalPO = null;
            $batchNumber = $item->batch_number;

            if ($batchNumber) {
                // First try to find in QualityInspection
                $inspection = \App\Models\QualityInspection::where('batch_number', $batchNumber)
                    ->where('company_id', $companyId)
                    ->first();

                if ($inspection && $inspection->purchase_order_id) {
                    $originalPO = Wh_PurchaseOrder::find($inspection->purchase_order_id);
                }

                // If not found in QualityInspection, try CentralStore
                if (!$originalPO) {
                    $centralStoreItem = CentralStore::where('batch_number', $batchNumber)
                        ->where('company_id', $companyId)
                        ->first();

                    if ($centralStoreItem && $centralStoreItem->purchase_order_id) {
                        $originalPO = Wh_PurchaseOrder::find($centralStoreItem->purchase_order_id);
                    }
                }
            }

            // Calculate amounts
            $subtotal = $reorderQuantity * $item->unit_price;
            $taxAmount = 0;
            $totalAmount = $subtotal;

            // Copy tax configuration from original PO if found
            $taxConfiguration = null;
            if ($originalPO) {
                $taxConfiguration = [
                    'tax_configuration_id' => $originalPO->tax_configuration_id,
                    'tax_type' => $originalPO->tax_type,
                    'tax_rate' => $originalPO->tax_rate,
                    'is_tax_exempt' => $originalPO->is_tax_exempt ?? false,
                    'tax_exemption_reason' => $originalPO->tax_exemption_reason,
                ];

                // Calculate tax if applicable
                if ($originalPO->tax_configuration_id && $originalPO->tax_rate > 0 && !($originalPO->is_tax_exempt ?? false)) {
                    $taxAmount = $subtotal * ($originalPO->tax_rate / 100);
                    $totalAmount = $subtotal + $taxAmount;
                }
            }

            // Create purchase order data
            $userId = auth()->id();
            if (!$userId) {
                Log::error('No authenticated user for reorder operation', ['company_id' => $companyId, 'item_id' => $itemId]);
                return response()->json([
                    'success' => false,
                    'message' => 'User authentication required'
                ], 401);
            }

            $poData = [
                'company_id' => $companyId,
                'user_id' => $userId,
                'supplier_id' => $supplierId,
                'po_number' => 'PO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
                'order_date' => now()->format('Y-m-d'),
                'status' => 'created', // Start as created, needs approval
                'is_reorder' => true,
                'created_by' => $userId,
                'items' => [
                    [
                        'name' => $item->item_name,
                        'quantity' => $reorderQuantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $subtotal,
                        'batch_number' => $item->batch_number,
                        'item_id' => $item->id
                    ]
                ],
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_value' => $totalAmount,
                'total_amount' => $totalAmount,
                'notes' => "Automatic reorder generated for low stock item. Current stock: {$item->quantity}",
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Add tax configuration if found
            if ($taxConfiguration) {
                $poData = array_merge($poData, $taxConfiguration);
            }

            // Create the purchase order
            $purchaseOrder = Wh_PurchaseOrder::create($poData);

            return response()->json([
                'success' => true,
                'message' => 'Reorder purchase order created successfully',
                'data' => [
                    'po_id' => $purchaseOrder->id,
                    'po_number' => $purchaseOrder->po_number
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating reorder purchase order', [
                'error' => $e->getMessage(),
                'item_id' => $itemId
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create reorder purchase order'
            ], 500);
        }
    }

}
