<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\CustomerPoint;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomersImport implements ToCollection, WithHeadingRow
{
    private $companyId;
    private $userId;
    private $programId;
    private $rowCount = 0;
    private $successCount = 0;
    private $failedCount = 0;
    private $failures = [];

    public function __construct($companyId, $userId, $programId)
    {
        $this->companyId = $companyId;
        $this->userId = $userId;
        $this->programId = $programId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->rowCount++;
            
            // Convert the row to array and clean data
            $customerData = $row->toArray();
            
            // Clean and format data
            $customerData = array_map(function($value) {
                if (is_null($value)) {
                    return null;
                }
                
                // Convert numbers to strings for phone fields
                if (is_numeric($value)) {
                    $value = (string)$value;
                }
                
                return is_string($value) ? trim($value) : $value;
            }, $customerData);

            // Ensure phone numbers are strings and properly formatted
            $customerData['phone'] = $this->formatPhoneNumber($customerData['phone'] ?? '');
            if (isset($customerData['primary_contact_number'])) {
                $customerData['primary_contact_number'] = $this->formatPhoneNumber($customerData['primary_contact_number']);
            }

            // Validate customer data
            $validator = Validator::make($customerData, [
                'name' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email,NULL,id,company_id,'.$this->companyId,
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'customer_category' => 'required|in:Standard,VIP,HVC',
                'status' => 'required|in:Active,Inactive,Pending,VIP,Blacklisted,On Hold,Suspended,Regular,New',
                'value' => 'required|numeric|min:0',
                'sector' => 'nullable|string|max:100',
                'number_of_employees' => 'nullable|integer',
                'primary_contact_name' => 'nullable|string|max:255',
                'primary_contact_email' => 'nullable|email',
                'primary_contact_number' => 'nullable|string|max:20',
                'title' => 'nullable|string|max:50',
                'gender' => 'nullable|string|max:10',
                'date_of_birth' => 'nullable|date|date_format:Y-m-d',
                'occupation' => 'nullable|string|max:255',
                'initial_points' => 'nullable|integer|min:0'
            ]);

            if ($validator->fails()) {
                $this->failedCount++;
                $this->failures[] = [
                    'row' => $this->rowCount,
                    'data' => $customerData,
                    'errors' => $validator->errors()->all()
                ];
                continue;
            }

            DB::beginTransaction();
            try {
                // Format date_of_birth if present
                if (!empty($customerData['date_of_birth'])) {
                    $customerData['date_of_birth'] = \Carbon\Carbon::createFromFormat('Y-m-d', $customerData['date_of_birth'])->format('Y-m-d');
                }

                // Create or update customer
                $customer = Customer::updateOrCreate(
                    [
                        'email' => $customerData['email'],
                        'company_id' => $this->companyId
                    ],
                    [
                        'customer_type' => $customerData['customer_type'] ?? 'individual',
                        'name' => $customerData['name'],
                        'company_name' => $customerData['company_name'],
                        'phone' => $customerData['phone'],
                        'address' => $customerData['address'],
                        'sector' => $customerData['sector'] ?? null,
                        'number_of_employees' => $customerData['number_of_employees'] ?? null,
                        'primary_contact_name' => $customerData['primary_contact_name'] ?? null,
                        'primary_contact_email' => $customerData['primary_contact_email'] ?? null,
                        'primary_contact_number' => $customerData['primary_contact_number'] ?? null,
                        'customer_category' => $customerData['customer_category'],
                        'value' => $customerData['value'],
                        'status' => $customerData['status'],
                        'title' => $customerData['title'] ?? null,
                        'gender' => $customerData['gender'] ?? null,
                        'date_of_birth' => $customerData['date_of_birth'] ?? null,
                        'occupation' => $customerData['occupation'] ?? null,
                        'company_id' => $this->companyId,
                        'created_by' => $this->userId
                    ]
                );

                // Handle customer points if program exists
                if ($this->programId) {
                    CustomerPoint::updateOrCreate(
                        [
                            'company_id' => $this->companyId,
                            'customer_id' => $customer->id,
                            'loyalty_program_id' => $this->programId
                        ],
                        [
                            'points_balance' => $customerData['initial_points'] ?? 0,
                            'points_earned' => $customerData['initial_points'] ?? 0,
                            'last_activity' => now(),
                            'expires_at' => now()->addYear() // Set default 1 year expiration
                        ]
                    );
                }

                DB::commit();
                $this->successCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                $this->failedCount++;
                $this->failures[] = [
                    'row' => $this->rowCount,
                    'data' => $customerData,
                    'errors' => [$e->getMessage()]
                ];
                Log::error('Import failed for row '.$this->rowCount.': '.$e->getMessage());
                Log::error('Stack trace: '.$e->getTraceAsString());
            }
        }
    }

    /**
     * Format phone number by removing non-numeric characters
     */
    private function formatPhoneNumber($phone)
    {
        if (is_null($phone)) {
            return null;
        }
        
        // Convert to string if it's a number
        $phone = (string)$phone;
        
        // Remove all non-numeric characters
        return preg_replace('/[^0-9]/', '', $phone);
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getFailedCount()
    {
        return $this->failedCount;
    }

    public function getFailures()
    {
        return $this->failures;
    }
}