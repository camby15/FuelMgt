<?php

namespace App\Exports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Session;

class VehiclesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $companyId = Session::get('selected_company_id');
        
        return Vehicle::where('company_id', $companyId)
            ->with(['creator:id,fullname', 'updater:id,fullname', 'assignedDriver:id,full_name'])
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
            'Registration Number',
            'Make',
            'Model',
            'Type',
            'Year',
            'Color',
            'Fuel Type',
            'Insurance Expiry',
            'Mileage',
            'Status',
            'Assigned Driver',
            'Notes',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param Vehicle $vehicle
     * @return array
     */
    public function map($vehicle): array
    {
        return [
            $vehicle->id,
            $vehicle->registration_number,
            $vehicle->make,
            $vehicle->model,
            $vehicle->type_formatted,
            $vehicle->year,
            $vehicle->color,
            $vehicle->fuel_type,
            $vehicle->insurance_expiry ? $vehicle->insurance_expiry->format('Y-m-d') : '',
            $vehicle->mileage,
            $vehicle->status_formatted,
            $vehicle->assignedDriver ? $vehicle->assignedDriver->full_name : 'Unassigned',
            $vehicle->notes,
            $vehicle->creator ? $vehicle->creator->fullname : '',
            $vehicle->updater ? $vehicle->updater->fullname : '',
            $vehicle->created_at->format('Y-m-d H:i:s'),
            $vehicle->updated_at->format('Y-m-d H:i:s'),
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
            'B' => 20,  // Registration Number
            'C' => 15,  // Make
            'D' => 15,  // Model
            'E' => 12,  // Type
            'F' => 8,   // Year
            'G' => 12,  // Color
            'H' => 15,  // Fuel Type
            'I' => 18,  // Insurance Expiry
            'J' => 12,  // Mileage
            'K' => 12,  // Status
            'L' => 20,  // Assigned Driver
            'M' => 30,  // Notes
            'N' => 15,  // Created By
            'O' => 15,  // Updated By
            'P' => 20,  // Created At
            'Q' => 20,  // Updated At
        ];
    }
}
