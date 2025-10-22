<?php

namespace App\Exports;

use App\Models\Driver;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Session;

class DriversExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $companyId = Session::get('selected_company_id');
        
        return Driver::where('company_id', $companyId)
            ->with(['creator:id,fullname', 'updater:id,fullname'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Full Name',
            'License Number',
            'License Type',
            'Phone',
            'Experience Years',
            'License Expiry',
            'Emergency Contact',
            'Status',
            'Notes',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param Driver $driver
     * @return array
     */
    public function map($driver): array
    {
        return [
            $driver->id,
            $driver->full_name,
            $driver->license_number,
            $driver->license_type_formatted,
            $driver->phone,
            $driver->experience_years,
            $driver->license_expiry ? $driver->license_expiry->format('Y-m-d') : '',
            $driver->emergency_contact,
            $driver->status_formatted,
            $driver->notes,
            $driver->creator ? $driver->creator->fullname : '',
            $driver->updater ? $driver->updater->fullname : '',
            $driver->created_at->format('Y-m-d H:i:s'),
            $driver->updated_at->format('Y-m-d H:i:s'),
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
            'A' => 8,   // ID
            'B' => 20,  // Full Name
            'C' => 18,  // License Number
            'D' => 15,  // License Type
            'E' => 15,  // Phone
            'F' => 15,  // Experience Years
            'G' => 15,  // License Expiry
            'H' => 20,  // Emergency Contact
            'I' => 12,  // Status
            'J' => 30,  // Notes
            'K' => 15,  // Created By
            'L' => 15,  // Updated By
            'M' => 20,  // Created At
            'N' => 20,  // Updated At
        ];
    }
}
