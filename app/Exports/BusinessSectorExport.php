<?php

namespace App\Exports;

use App\Models\BusinessSector;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BusinessSectorExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $companyId;

    public function __construct($companyId = null)
    {
        $this->companyId = $companyId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = BusinessSector::with(['creator:id,fullname', 'updater:id,fullname']);
        
        if ($this->companyId) {
            $query->where('company_id', $this->companyId);
        }
        
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Head Name',
            'Status',
            'Sub Sectors',
            'Sort Order',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param mixed $businessSector
     * @return array
     */
    public function map($businessSector): array
    {
        return [
            $businessSector->id,
            $businessSector->name,
            $businessSector->description ?? '',
            $businessSector->head_name ?? '',
            $businessSector->status,
            $businessSector->sub_sectors ? implode(', ', json_decode($businessSector->sub_sectors, true)) : '',
            $businessSector->sort_order,
            $businessSector->creator ? $businessSector->creator->fullname : 'System',
            $businessSector->updater ? $businessSector->updater->fullname : 'System',
            $businessSector->created_at->format('Y-m-d H:i:s'),
            $businessSector->updated_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 25,  // Name
            'C' => 40,  // Description
            'D' => 20,  // Head Name
            'E' => 15,  // Status
            'F' => 30,  // Sub Sectors
            'G' => 15,  // Sort Order
            'H' => 20,  // Created By
            'I' => 20,  // Updated By
            'J' => 20,  // Created At
            'K' => 20,  // Updated At
        ];
    }
}
