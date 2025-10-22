<?php

namespace App\Imports;

use App\Models\BusinessSector;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BusinessSectorImport implements ToCollection, WithHeadingRow, WithValidation, WithChunkReading
{
    protected $companyId;
    protected $importedCount = 0;
    protected $errors = [];

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            try {
                // Skip empty rows
                if (empty($row['name'])) {
                    continue;
                }

                // Check if business sector already exists
                $existingSector = BusinessSector::where('name', $row['name'])
                    ->where('company_id', $this->companyId)
                    ->first();

                if ($existingSector) {
                    $this->errors[] = "Business sector '{$row['name']}' already exists. Skipping.";
                    continue;
                }

                // Prepare data
                $data = [
                    'name' => $row['name'],
                    'description' => $row['description'] ?? null,
                    'head_name' => $row['head_name'] ?? null,
                    'status' => $row['status'] ?? 'active',
                    'company_id' => $this->companyId,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ];

                // Handle sub-sectors
                if (!empty($row['sub_sectors'])) {
                    $subSectors = is_string($row['sub_sectors']) 
                        ? array_filter(array_map('trim', explode(',', $row['sub_sectors'])))
                        : (array) $row['sub_sectors'];
                    $data['sub_sectors'] = json_encode($subSectors);
                }

                // Set sort order
                $maxSortOrder = BusinessSector::where('company_id', $this->companyId)->max('sort_order') ?? 0;
                $data['sort_order'] = $row['sort_order'] ?? ($maxSortOrder + 1);

                // Create business sector
                BusinessSector::create($data);
                $this->importedCount++;

                Log::info('Business sector imported successfully', [
                    'name' => $data['name'],
                    'company_id' => $this->companyId
                ]);

            } catch (\Exception $e) {
                $this->errors[] = "Error importing business sector '{$row['name']}': " . $e->getMessage();
                Log::error('Error importing business sector', [
                    'row' => $row->toArray(),
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.description' => 'nullable|string|max:1000',
            '*.head_name' => 'nullable|string|max:255',
            '*.status' => 'nullable|in:active,inactive',
            '*.sub_sectors' => 'nullable|string',
            '*.sort_order' => 'nullable|integer|min:0'
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '*.name.required' => 'Business sector name is required.',
            '*.name.max' => 'Business sector name must not exceed 255 characters.',
            '*.description.max' => 'Description must not exceed 1000 characters.',
            '*.head_name.max' => 'Head name must not exceed 255 characters.',
            '*.status.in' => 'Status must be either active or inactive.',
            '*.sort_order.integer' => 'Sort order must be an integer.',
            '*.sort_order.min' => 'Sort order must be at least 0.'
        ];
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Get the number of imported records
     */
    public function getImportedCount()
    {
        return $this->importedCount;
    }

    /**
     * Get import errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
