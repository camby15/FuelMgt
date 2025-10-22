<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HomeConnectionAssignmentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private Collection $assignments;
    private int $rowNumber = 0;

    public function __construct(Collection $assignments)
    {
        $this->assignments = $assignments;
    }

    public function collection(): Collection
    {
        return $this->assignments;
    }

    public function headings(): array
    {
        return [
            '#',
            'Customer Name',
            'Team',
            'Assignment Title',
            'Assignment Date',
            'Priority',
            'Status',
            'Location',
            'Connection Type',
            'Has Issue',
            'Issue Status',
        ];
    }

    public function map($assignment): array
    {
        $this->rowNumber++;

        $assignedDate = $assignment->assigned_date
            ? $assignment->assigned_date->format('Y-m-d H:i')
            : ($assignment->created_at ? $assignment->created_at->format('Y-m-d H:i') : 'N/A');

        return [
            $this->rowNumber,
            optional($assignment->customer)->customer_name ?? 'N/A',
            optional($assignment->team)->team_name ?? 'N/A',
            $assignment->assignment_title ?? 'N/A',
            $assignedDate,
            $assignment->priority ? ucfirst($assignment->priority) : 'N/A',
            $assignment->status ? ucfirst(str_replace('_', ' ', $assignment->status)) : 'N/A',
            optional($assignment->customer)->location ?? 'N/A',
            optional($assignment->customer)->connection_type ?? 'N/A',
            $assignment->has_issue ? 'Yes' : 'No',
            $assignment->issue_status
                ? ucfirst(str_replace('_', ' ', $assignment->issue_status))
                : ($assignment->has_issue ? 'Open' : 'N/A'),
        ];
    }
}
