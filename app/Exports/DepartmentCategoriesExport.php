<?php

namespace App\Exports;

use App\Models\DepartmentCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DepartmentCategoriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $companyId;
    protected $filters;

    public function __construct($companyId, $filters = [])
    {
        $this->companyId = $companyId;
        $this->filters = $filters;
    }

    /**
     * Return collection of departments to export.
     */
    public function collection()
    {
        $query = DepartmentCategory::where('company_id', $this->companyId)
            ->with(['creator:id,fullname', 'updater:id,fullname', 'creatorUser:id,name', 'updaterUser:id,name']);

        // Apply filters
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['search'])) {
            $query->search($this->filters['search']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query->orderBySortOrder()->get();
    }

    /**
     * Define the headings for the export.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Department Name',
            'Department Code',
            'Description',
            'Head of Department',
            'Sub Departments',
            'Sub Departments Count',
            'Status',
            'Color',
            'Sort Order',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * Map the data for each row.
     */
    public function map($department): array
    {
        // Get creator name
        $createdBy = 'System';
        if ($department->creator) {
            $createdBy = $department->creator->fullname;
        } elseif ($department->creatorUser) {
            $createdBy = $department->creatorUser->name;
        }

        // Get updater name
        $updatedBy = 'System';
        if ($department->updater) {
            $updatedBy = $department->updater->fullname;
        } elseif ($department->updaterUser) {
            $updatedBy = $department->updaterUser->name;
        }

        return [
            $department->id,
            $department->name,
            $department->code,
            $department->description ?? 'N/A',
            $department->head_name ?? 'N/A',
            $department->hasSubDepartments() ? implode(', ', $department->sub_departments) : 'None',
            $department->sub_departments_count,
            ucfirst($department->status),
            $department->color,
            $department->sort_order,
            $createdBy,
            $updatedBy,
            $department->created_at->format('Y-m-d H:i:s'),
            $department->updated_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Apply styles to the worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3B7DDD']
                ]
            ],
        ];
    }
}