<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRN - {{ $receiving->receiving_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .grn-number {
            font-size: 16px;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #333;
        }
        .info-value {
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }
        .signature-box {
            border-top: 1px solid #333;
            padding-top: 10px;
            text-align: center;
        }
        .signature-label {
            font-weight: bold;
            margin-bottom: 30px;
        }
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">Print GRN</button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; cursor: pointer; margin-left: 10px;">Close</button>
    </div>

    <div class="header">
        <div class="company-name">{{ $supplier ? $supplier->company_name : 'COMPANY NAME' }}</div>
        <div class="document-title">GOODS RECEIPT NOTE (GRN)</div>
        <div class="grn-number">GRN Number: {{ $receiving->receiving_number }}</div>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <span class="info-label">PO Number:</span>
                    <span class="info-value">{{ $purchaseOrder ? $purchaseOrder->po_number : 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Supplier:</span>
                    <span class="info-value">{{ $supplier ? $supplier->company_name : 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Received Date:</span>
                    <span class="info-value">{{ $receiving->receiving_date }}</span>
                </div>
            </div>
            <div>
                <div class="info-item">
                    <span class="info-label">Received By:</span>
                    <span class="info-value">{{ $userName ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value">{{ ucfirst($receiving->status) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total Value:</span>
                    <span class="info-value">GH₵ {{ number_format($totalValue, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3>Items Received</h3>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Received Qty</th>
                    <th>Rejected Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($processedItems) && count($processedItems) > 0)
                    @foreach($processedItems as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['received_qty'] }}</td>
                            <td>{{ $item['rejected_qty'] }}</td>
                            <td>GH₵ {{ number_format($item['unit_price'], 2) }}</td>
                            <td>GH₵ {{ number_format($item['total'], 2) }}</td>
                            <td>{{ $item['location'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" style="text-align: center;">No items found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($receiving->notes)
    <div class="info-section">
        <h3>Notes</h3>
        <p>{{ $receiving->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <div class="signature-box">
            <div class="signature-label">Received By</div>
            <div style="margin-top: 40px;">_________________</div>
        </div>
        <div class="signature-box">
            <div class="signature-label">Checked By</div>
            <div style="margin-top: 40px;">_________________</div>
        </div>
        <div class="signature-box">
            <div class="signature-label">Authorized By</div>
            <div style="margin-top: 40px;">_________________</div>
        </div>
    </div>
</body>
</html>
