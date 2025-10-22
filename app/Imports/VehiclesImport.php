<?php

namespace App\Imports;

use App\Models\Vehicle;
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

class VehiclesImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
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
                if (empty($row['registration_number']) && empty($row['make'])) {
                    continue;
                }

                // Convert type to lowercase
                $type = $this->normalizeType($row['type'] ?? '');
                
                // Convert status to lowercase with hyphens
                $status = $this->normalizeStatus($row['status'] ?? 'available');

                // Find assigned driver if provided
                $assignedDriverId = null;
                if (!empty($row['assigned_driver'])) {
                    $driver = Driver::where('company_id', $companyId)
                        ->where('full_name', 'like', '%' . $row['assigned_driver'] . '%')
                        ->first();
                    if ($driver) {
                        $assignedDriverId = $driver->id;
                    }
                }

                Vehicle::create([
                    'company_id' => $companyId,
                    'registration_number' => $row['registration_number'],
                    'make' => $row['make'],
                    'model' => $row['model'],
                    'type' => $type,
                    'year' => $row['year'],
                    'color' => $row['color'] ?? null,
                    'fuel_type' => $row['fuel_type'] ?? null,
                    'insurance_expiry' => $row['insurance_expiry'],
                    'mileage' => $row['mileage'] ?? null,
                    'status' => $status,
                    'notes' => $row['notes'] ?? null,
                    'assigned_driver_id' => $assignedDriverId,
                    'created_by' => Auth::id(),
                ]);

                $this->rowCount++;

            } catch (\Exception $e) {
                Log::error('Error importing vehicle row', [
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
            'registration_number' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'type' => 'nullable|string',
            'year' => 'required|integer|min:1990|max:2025',
            'color' => 'nullable|string|max:100',
            'fuel_type' => 'nullable|string|max:100',
            'insurance_expiry' => 'required|date',
            'mileage' => 'nullable|integer|min:0',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
            'assigned_driver' => 'nullable|string',
        ];
    }

    /**
     * Normalize vehicle type to match enum values
     */
    private function normalizeType($type)
    {
        if (empty($type)) {
            return 'sedan'; // Default
        }

        $normalized = strtolower(trim($type));
        
        return match($normalized) {
            'sedan', 'car' => 'sedan',
            'suv', 'sport utility vehicle' => 'suv',
            'truck', 'pickup' => 'truck',
            'van', 'minivan' => 'van',
            'motorcycle', 'bike', 'motorbike' => 'motorcycle',
            default => 'sedan'
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
            'available', 'free' => 'available',
            'in use', 'in-use', 'busy', 'assigned' => 'in-use',
            'maintenance', 'repair', 'servicing' => 'maintenance',
            'inactive', 'disabled', 'retired' => 'inactive',
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
