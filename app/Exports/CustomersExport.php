<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $companyId;
    protected $customerType;

    public function __construct($companyId, $customerType = null)
    {
        $this->companyId = $companyId;
        $this->customerType = $customerType;
    }

    public function collection()
    {
        $query = Customer::where('company_id', $this->companyId);

        if ($this->customerType) {
            $query->where('customer_type', $this->customerType);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'Email',
            'Primary Contact',
            'Postal Address',
            'Business Profession',
            'Customer Category',
            'Value',
            'Status',
            'Source of Acquisition',
            'Change Type',
            'Assign Branch',
            'Channel',
            'Mode of Communication',
            'Company Group Code',
            'Date of Birth',
            'Gender',
            'Marital Status',
            'Nearest Landmark'
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->name,
            $customer->email,
            $customer->phone,
            $customer->address,
            $customer->profession,
            $customer->category,
            $customer->value,
            $customer->status,
            $customer->source_of_acquisition,
            $customer->change_type,
            $customer->branch,
            $customer->channel,
            $customer->communication_mode,
            $customer->company_group_code,
            $customer->date_of_birth ? $customer->date_of_birth->format('Y-m-d') : null,
            $customer->gender,
            $customer->marital_status,
            $customer->nearest_landmark
        ];
    }
}
