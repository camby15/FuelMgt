<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Analytics Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4e73df;
            padding-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 15px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .header h1 {
            color: #4e73df;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .report-info {
            background: #f8f9fc;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #e3e6f0;
            background: #fff;
        }
        .stats-cell:first-child {
            border-left: none;
        }
        .stats-cell:last-child {
            border-right: none;
        }
        .stats-value {
            font-size: 24px;
            font-weight: bold;
            color: #4e73df;
            margin-bottom: 5px;
        }
        .stats-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #4e73df;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e3e6f0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th,
        .table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #e3e6f0;
        }
        .table th {
            background: #f8f9fc;
            font-weight: bold;
            color: #4e73df;
            font-size: 11px;
            text-transform: uppercase;
        }
        .table td {
            font-size: 11px;
        }
        .table tbody tr:nth-child(even) {
            background: #f8f9fc;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e3e6f0;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .page-break {
            page-break-before: always;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        @php
            $logoData = null;
            // Use GESL logo with fallbacks, prioritizing smaller files
            if (file_exists(public_path('images/gesl_logo_small.png'))) {
                $logoPath = public_path('images/gesl_logo_small.png');
            } elseif (file_exists(public_path('images/logo.png'))) {
                $logoPath = public_path('images/logo.png');
            } elseif (file_exists(public_path('images/logo_white_sm.png'))) {
                $logoPath = public_path('images/logo_white_sm.png');
            } elseif (file_exists(public_path('images/STACK LOGO-01.png'))) {
                $logoPath = public_path('images/STACK LOGO-01.png');
            }
            
            if (isset($logoPath) && file_exists($logoPath)) {
                $logoData = base64_encode(file_get_contents($logoPath));
                $mimeType = mime_content_type($logoPath);
            }
        @endphp
        
        @if($logoData)
            <img src="data:{{ $mimeType }};base64,{{ $logoData }}" alt="Company Logo" class="logo">
        @endif
        <h1>Inventory Analytics Report</h1>
        <p>Generated on: {{ date('F j, Y \a\t g:i A') }}</p>
        <p>Company: {{ $companyName ?? 'N/A' }}</p>
    </div>

    <div class="report-info">
        <p><strong>Report Period:</strong> {{ $reportPeriod ?? 'Current' }}</p>
        <p><strong>Report Type:</strong> {{ $reportType ?? 'Inventory Analytics' }}</p>
    </div>

    <!-- Key Statistics -->
    <div class="section">
        <div class="section-title">Key Statistics</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($data['total_inventory'] ?? 0) }}</div>
                    <div class="stats-label">Total Inventory</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">₵{{ number_format($data['total_inventory_value'] ?? 0, 2) }}</div>
                    <div class="stats-label">Total Value</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $data['pending_requisitions'] ?? 0 }}</div>
                    <div class="stats-label">Pending Requisitions</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $data['active_pos'] ?? 0 }}</div>
                    <div class="stats-label">Active POs</div>
                </div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">{{ $data['low_stock_count'] ?? 0 }}</div>
                    <div class="stats-label">Low Stock Items</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $data['inventory_change_percent'] ?? 0 }}%</div>
                    <div class="stats-label">Inventory Change</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $data['new_requisitions_today'] ?? 0 }}</div>
                    <div class="stats-label">New Today</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $data['pos_pending_approval'] ?? 0 }}</div>
                    <div class="stats-label">POs Pending</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Items -->
    @if(isset($data['low_stock_items']) && count($data['low_stock_items']) > 0)
    <div class="section">
        <div class="section-title">Low Stock Items</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Current Stock</th>
                    <th>Reorder Level</th>
                    <th>Unit Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['low_stock_items'] as $item)
                <tr>
                    <td>{{ $item['item_name'] ?? 'N/A' }}</td>
                    <td>{{ $item['category'] ?? 'N/A' }}</td>
                    <td class="text-center">{{ $item['current_stock'] ?? 0 }}</td>
                    <td class="text-center">{{ $item['reorder_level'] ?? 0 }}</td>
                    <td class="text-right">₵{{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                    <td class="text-center">
                        @if(($item['current_stock'] ?? 0) <= 0)
                            <span class="badge badge-danger">Out of Stock</span>
                        @elseif(($item['current_stock'] ?? 0) <= ($item['reorder_level'] ?? 0))
                            <span class="badge badge-warning">Low Stock</span>
                        @else
                            <span class="badge badge-success">In Stock</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Recent Activities -->
    @if(isset($data['recent_activities']) && count($data['recent_activities']) > 0)
    <div class="section">
        <div class="section-title">Recent Activities</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['recent_activities'] as $activity)
                <tr>
                    <td>{{ $activity['title'] ?? 'N/A' }}</td>
                    <td>{{ isset($activity['time']) ? date('M j, Y g:i A', strtotime($activity['time'])) : 'N/A' }}</td>
                    <td class="text-center">
                        @if(($activity['status'] ?? '') === 'completed')
                            <span class="badge badge-success">Completed</span>
                        @elseif(($activity['status'] ?? '') === 'warning')
                            <span class="badge badge-warning">Warning</span>
                        @elseif(($activity['status'] ?? '') === 'in_transit')
                            <span class="badge badge-success">In Transit</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Chart Data Summary -->
    @if(isset($data['chart_data']) && isset($data['chart_data']['labels']))
    <div class="section">
        <div class="section-title">Inventory Trends</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Inventory Value</th>
                    <th>Inventory Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['chart_data']['labels'] as $index => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="text-right">₵{{ number_format($data['chart_data']['inventory_values'][$index] ?? 0, 2) }}</td>
                    <td class="text-center">{{ number_format($data['chart_data']['inventory_counts'][$index] ?? 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the ERP System</p>
        <p>For questions or support, please contact your system administrator</p>
    </div>
</body>
</html>
