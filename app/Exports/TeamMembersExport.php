<?php

namespace App\Exports;

use App\Models\TeamMember;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeamMembersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $companyId;

    public function __construct()
    {
        $this->companyId = Session::get('selected_company_id');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return TeamMember::where('company_id', $this->companyId)
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
            'Full Name',
            'Employee ID',
            'Position',
            'Department',
            'Phone',
            'Email',
            'Hire Date',
            'Status',
            'Notes',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param mixed $teamMember
     * @return array
     */
    public function map($teamMember): array
    {
        return [
            $teamMember->full_name,
            $teamMember->employee_id,
            $teamMember->position,
            ucfirst($teamMember->department),
            $teamMember->phone,
            $teamMember->email,
            $teamMember->hire_date ? $teamMember->hire_date->format('Y-m-d') : '',
            ucfirst(str_replace('-', ' ', $teamMember->status)),
            $teamMember->notes,
            $teamMember->creator ? $teamMember->creator->fullname : '',
            $teamMember->updater ? $teamMember->updater->fullname : '',
            $teamMember->created_at ? $teamMember->created_at->format('Y-m-d H:i:s') : '',
            $teamMember->updated_at ? $teamMember->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
