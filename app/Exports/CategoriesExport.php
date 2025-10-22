<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $companyId;
    protected $filters;

    public function __construct($companyId, $filters = [])
    {
        $this->companyId = $companyId;
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Category::where('company_id', $this->companyId);

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

        return $query->orderBy('sort_order')->orderBy('name')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Category Name',
            'Code',
            'Description',
            'Head of Category',
            'Status',
            'Color',
            'Sub Categories',
            'Sort Order',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param Category $category
     * @return array
     */
    public function map($category): array
    {
        return [
            $category->id,
            $category->name,
            $category->code,
            $category->description,
            $category->head_name,
            ucfirst($category->status),
            $category->color,
            $category->sub_categories ? implode(', ', $category->sub_categories) : '',
            $category->sort_order,
            $category->created_at->format('Y-m-d H:i:s'),
            $category->updated_at->format('Y-m-d H:i:s')
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
            'B' => 25,  // Category Name
            'C' => 15,  // Code
            'D' => 40,  // Description
            'E' => 25,  // Head of Category
            'F' => 15,  // Status
            'G' => 15,  // Color
            'H' => 30,  // Sub Categories
            'I' => 15,  // Sort Order
            'J' => 20,  // Created At
            'K' => 20,  // Updated At
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:K1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FF3B7DDD',
                        ],
                    ],
                    'font' => [
                        'color' => [
                            'argb' => 'FFFFFFFF',
                        ],
                        'bold' => true,
                    ],
                ]);
            },
        ];
    }
}
