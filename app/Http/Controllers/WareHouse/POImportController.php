<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use App\Models\Wh_PurchaseOrder;
use App\Models\Wh_Supplier;
use App\Models\TaxConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class POImportController extends Controller
{
    public function downloadTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set title
            $sheet->setTitle('PO Import Template');
            
            // Get available suppliers for dropdown
            $companyId = Session::get('selected_company_id');
            $suppliers = Wh_Supplier::where('company_id', $companyId)
                ->select('id', 'company_name')
                ->get();
            $supplierList = $suppliers->pluck('company_name', 'id')->toArray();
            
            // Get tax configurations
            $taxConfigs = TaxConfiguration::where('is_active', true)->get();
            
            // Instructions and headers
            $row = 1;
            
            // Title
            $sheet->setCellValue('A' . $row, 'PURCHASE ORDER IMPORT TEMPLATE');
            $sheet->mergeCells('A' . $row . ':E' . $row);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row += 2;
            
            // Instructions
            $instructions = [
                'INSTRUCTIONS:',
                '1. Fill in the item details below (rows 5 onwards)',
                '2. Item Name: Enter the name of the item (e.g., "Office Chair")',
                '3. Description: Enter detailed description of the item',
                '4. Quantity: Enter the number of items needed',
                '5. Unit Price: Enter the price per unit in GHS',
                '6. Notes: Optional additional information',
                '7. Supplier and PO details are auto-generated from the import form',
                '8. Tax calculations are handled automatically based on your selection',
                '9. Do not modify the header row (row 4)',
                '10. Save this file and upload it through the import form'
            ];
            
            foreach ($instructions as $instruction) {
                $sheet->setCellValue('A' . $row, $instruction);
                $sheet->getStyle('A' . $row)->getFont()->setBold($row === 2);
                $row++;
            }
            $row++;
            
            // Headers
            $headers = [
                'A' => 'Item Name',
                'B' => 'Description',
                'C' => 'Quantity',
                'D' => 'Unit Price (GHS)',
                'E' => 'Notes (Optional)'
            ];
            
            foreach ($headers as $col => $header) {
                $sheet->setCellValue($col . $row, $header);
            }
            
            // Style headers
            $headerRange = 'A' . $row . ':E' . $row;
            $sheet->getStyle($headerRange)->getFont()->setBold(true);
            $sheet->getStyle($headerRange)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('4e73df');
            $sheet->getStyle($headerRange)->getFont()->getColor()->setRGB('ffffff');
            $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($headerRange)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
            
            // Sample data
            $sampleData = [
                ['Office Chair', 'Ergonomic office chairs with lumbar support', '5', '150.00', 'High-quality office furniture'],
                ['Desk Lamp', 'LED desk lamps with adjustable brightness', '10', '45.00', 'Energy efficient lighting'],
                ['Printer Paper', 'A4 white paper 80gsm', '20', '12.50', 'Office supplies']
            ];
            
            $sampleRow = $row + 1;
            foreach ($sampleData as $index => $data) {
                $currentRow = $sampleRow + $index;
                foreach ($data as $colIndex => $value) {
                    $col = chr(65 + $colIndex); // A, B, C, etc.
                    $sheet->setCellValue($col . $currentRow, $value);
                }
            }
            
            // Style sample data
            $dataRange = 'A' . $sampleRow . ':E' . ($sampleRow + count($sampleData) - 1);
            $sheet->getStyle($dataRange)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
            
            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(25); // Item Name
            $sheet->getColumnDimension('B')->setWidth(35); // Description
            $sheet->getColumnDimension('C')->setWidth(12); // Quantity
            $sheet->getColumnDimension('D')->setWidth(18); // Unit Price
            $sheet->getColumnDimension('E')->setWidth(25); // Notes
            
            // Data validation for quantity and unit price
            $validationRange = 'C' . $sampleRow . ':D' . ($sampleRow + 100); // Allow up to 100 rows
            $sheet->getDataValidation($validationRange)->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL);
            $sheet->getDataValidation($validationRange)->setOperator(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::OPERATOR_GREATERTHAN);
            $sheet->getDataValidation($validationRange)->setFormula1('0');
            
            // Freeze header row
            $sheet->freezePane('A' . ($row + 1));
            
            // Create writer and return file
            $writer = new Xlsx($spreadsheet);
            
            $filename = 'PO_Import_Template_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return response()->streamDownload(function() use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error generating PO import template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error generating template: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processImport(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
                'supplier_id' => 'required|exists:wh__suppliers,id',
                'po_number' => 'required|string|unique:wh__purchase_orders,po_number',
                'order_date' => 'required|date',
                'delivery_date' => 'nullable|date|after_or_equal:order_date',
                'tax_method' => 'required|in:select_config,manual_rate',
                'tax_configuration_id' => 'nullable|required_if:tax_method,select_config|exists:tax_configurations,id',
                'tax_rate' => 'nullable|required_if:tax_method,manual_rate|numeric|min:0|max:100',
                'is_tax_exempt' => 'nullable|boolean',
                'tax_exemption_reason' => 'nullable|string|max:500',
                'notes' => 'nullable|string|max:1000'
            ]);

            $companyId = Session::get('selected_company_id');
            $userId = auth()->id();

            // Load the Excel file
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            
            // Get the highest row (skip header row which is row 4)
            $highestRow = $sheet->getHighestRow();
            $importedCount = 0;
            $errors = [];
            
            // Get supplier
            $supplier = Wh_Supplier::findOrFail($request->supplier_id);
            
            // Prepare items array - same format as regular PO creation
            $itemsData = [];
            
            // Start from row 5 (after instructions and header)
            for ($row = 5; $row <= $highestRow; $row++) {
                $itemName = trim($sheet->getCell('A' . $row)->getValue());
                $itemDescription = trim($sheet->getCell('B' . $row)->getValue());
                $quantity = $sheet->getCell('C' . $row)->getValue();
                $unitPrice = $sheet->getCell('D' . $row)->getValue();
                $notes = trim($sheet->getCell('E' . $row)->getValue());
                
                // Skip empty rows
                if (empty($itemName) || empty($quantity) || empty($unitPrice)) {
                    continue;
                }
                
                // Validate numeric values
                if (!is_numeric($quantity) || !is_numeric($unitPrice)) {
                    $errors[] = "Row {$row}: Quantity and Unit Price must be numeric";
                    continue;
                }
                
                if ($quantity <= 0 || $unitPrice < 0) {
                    $errors[] = "Row {$row}: Quantity must be greater than 0 and Unit Price must not be negative";
                    continue;
                }
                
                $itemsData[] = [
                    'name' => $itemName,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'category' => 'general', // Default category for imported items
                    'description' => $itemDescription, // Additional field for import
                    'notes' => $notes // Additional field for import
                ];
                
                $importedCount++;
            }
            
            // Process items exactly like regular PO creation
            $items = collect($itemsData)->map(function($item) {
                return [
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'category' => $item['category'],
                    'description' => $item['description'], // Additional field for import
                    'notes' => $item['notes'] // Additional field for import
                ];
            });
            
            if (empty($items)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid items found in the Excel file'
                ], 400);
            }
            
            // Calculate totals - same as regular PO creation
            $subtotal = $items->sum('total_price');
            
            // Handle tax calculations
            $taxAmount = 0;
            $totalAmount = $subtotal;
            $taxRate = 0;
            $taxType = 'exempt';
            $taxConfigurationId = null;
            
            if (!$request->boolean('is_tax_exempt')) {
                if ($request->tax_method === 'select_config' && $request->tax_configuration_id) {
                    $taxConfig = TaxConfiguration::find($request->tax_configuration_id);
                    if ($taxConfig) {
                        $taxRate = $taxConfig->rate;
                        $taxType = $taxConfig->type;
                        $taxConfigurationId = $taxConfig->id;

                        $taxAmount = $subtotal * ($taxRate / 100);
                        $totalAmount = $subtotal + $taxAmount;
                    }
                } elseif ($request->tax_method === 'manual_rate' && $request->tax_rate) {
                    $taxRate = $request->tax_rate;
                    $taxType = 'custom';
                    
                    $taxAmount = $subtotal * ($taxRate / 100);
                    $totalAmount = $subtotal + $taxAmount;
                }
            }
            
            // Create PO record
            $po = Wh_PurchaseOrder::create([
                'company_id' => $companyId,
                'user_id' => $userId,
                'po_number' => $request->po_number,
                'supplier_id' => $supplier->id,
                'order_date' => $request->order_date,
                'delivery_date' => $request->delivery_date ?: now()->addDays(7)->format('Y-m-d'),
                'status' => 'created',
                'items' => $items, // Store as array, not JSON string
                'notes' => $request->notes,
                'created_by' => $userId,
                'total_items' => $items->count(),
                'total_value' => $totalAmount,
                
                // Tax fields
                'tax_configuration_id' => $taxConfigurationId,
                'tax_type' => $taxType,
                'tax_rate' => $taxRate,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'is_tax_exempt' => $request->boolean('is_tax_exempt'),
                'tax_exemption_reason' => $request->tax_exemption_reason,
                'tax_breakdown' => json_encode([
                    'subtotal' => $subtotal,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount
                ])
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Purchase Order {$request->po_number} created successfully with {$importedCount} items",
                'po_id' => $po->id,
                'po_number' => $po->po_number,
                'items_imported' => $importedCount,
                'total_amount' => $totalAmount,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error processing PO import: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing import: ' . $e->getMessage()
            ], 500);
        }
    }
}
