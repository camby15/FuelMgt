<?php

namespace App\Imports;

use App\Models\Driver;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class DriversImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    private $rowCount = 0;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $companyId = Session::get('selected_company_id');
        
        foreach ($collection as $row) {
            try {
                // Skip empty rows
                if (empty($row['full_name']) && empty($row['license_number'])) {
                    continue;
                }

                // Convert license type to lowercase with hyphens
                $licenseType = $this->normalizeLicenseType($row['license_type'] ?? '');
                
                // Convert status to lowercase with hyphens
                $status = $this->normalizeStatus($row['status'] ?? 'available');

                Driver::create([
                    'company_id' => $companyId,
                    'full_name' => $row['full_name'],
                    'license_number' => $row['license_number'],
                    'license_type' => $licenseType,
                    'phone' => $row['phone'],
                    'experience_years' => $row['experience_years'] ?? null,
                    'license_expiry' => $row['license_expiry'],
                    'emergency_contact' => $row['emergency_contact'] ?? null,
                    'status' => $status,
                    'notes' => $row['notes'] ?? null,
                    'created_by' => Auth::id(),
                ]);

                $this->rowCount++;

            } catch (\Exception $e) {
                Log::error('Error importing driver row', [
                    'row_data' => $row->toArray(),
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'license_number' => 'required|string|max:255',
            'license_type' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'license_expiry' => 'required|date',
            'emergency_contact' => 'nullable|string|max:255',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Normalize license type to match enum values
     */
    private function normalizeLicenseType($licenseType)
    {
        if (empty($licenseType)) {
            return 'class-c'; // Default
        }

        $normalized = strtolower(trim($licenseType));
        
        return match($normalized) {
            'class a', 'class-a', 'a' => 'class-a',
            'class b', 'class-b', 'b' => 'class-b',
            'class c', 'class-c', 'c' => 'class-c',
            'motorcycle', 'motor' => 'motorcycle',
            default => 'class-c'
        };
    }

    /**
     * Normalize status to match enum values
     */
    private function normalizeStatus($status)
    {
        if (empty($status)) {
            return 'available'; // Default
        }

        $normalized = strtolower(trim($status));
        
        return match($normalized) {
            'available', 'active' => 'available',
            'assigned', 'busy' => 'assigned',
            'on leave', 'on-leave', 'leave' => 'on-leave',
            'inactive', 'disabled' => 'inactive',
            default => 'available'
        };
    }

    /**
     * Get the number of rows imported
     */
    public function getRowCount()
    {
        return $this->rowCount;
    }
}
