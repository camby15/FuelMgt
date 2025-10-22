<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxConfiguration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'type',
        'rate',
        'is_active',
        'description',
        'applicable_items',
        'exempt_items',
        'conditions',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
        'applicable_items' => 'array',
        'exempt_items' => 'array',
        'conditions' => 'array'
    ];

    /**
     * Get the company that owns the tax configuration
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    /**
     * Get all purchase orders using this tax configuration
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(Wh_PurchaseOrder::class);
    }

    /**
     * Get the user who created this tax configuration
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this tax configuration
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Calculate tax amount for a given subtotal
     */
    public function calculateTaxAmount(float $subtotal): float
    {
        if ($this->type === 'exempt') {
            return 0.00;
        }

        return $subtotal * ($this->rate / 100);
    }


    /**
     * Calculate total amount including tax
     */
    public function calculateTotalAmount(float $subtotal): array
    {
        $taxAmount = $this->calculateTaxAmount($subtotal);
        $totalAmount = $subtotal + $taxAmount;

        return [
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'tax_rate' => $this->rate
        ];
    }

    /**
     * Check if an item is exempt from this tax configuration
     */
    public function isItemExempt(string $itemCategory = null): bool
    {
        if ($this->type === 'exempt') {
            return true;
        }

        if (!$itemCategory || !$this->exempt_items) {
            return false;
        }

        return in_array($itemCategory, $this->exempt_items);
    }

    /**
     * Check if an item is applicable for this tax configuration
     */
    public function isItemApplicable(string $itemCategory = null): bool
    {
        if ($this->type === 'exempt') {
            return false;
        }

        if (!$itemCategory || !$this->applicable_items) {
            return true; // If no specific items defined, apply to all
        }

        return in_array($itemCategory, $this->applicable_items);
    }

    /**
     * Get Ghana standard tax configuration
     */
    public static function getGhanaStandardTax(int $companyId): ?self
    {
        return self::where('company_id', $companyId)
            ->where('code', 'GH_STANDARD')
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get Ghana flat rate tax configuration
     */
    public static function getGhanaFlatRateTax(int $companyId): ?self
    {
        return self::where('company_id', $companyId)
            ->where('code', 'GH_FLAT')
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get exempt tax configuration
     */
    public static function getExemptTax(int $companyId): ?self
    {
        return self::where('company_id', $companyId)
            ->where('code', 'GH_EXEMPT')
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get all active tax configurations for a company
     */
    public static function getActiveConfigurations(int $companyId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Create default Ghana tax configurations for a company
     */
    public static function createDefaultGhanaTaxConfigurations(int $companyId, int $createdBy): void
    {
        $configurations = [
            [
                'name' => 'Ghana Standard Tax',
                'code' => 'GH_STANDARD',
                'type' => 'standard',
                'rate' => 21.90,
                'description' => 'Ghana standard VAT rate of 21.9%',
                'applicable_items' => ['general', 'services', 'goods', 'food', 'medicine', 'education', 'small_business'],
                'exempt_items' => []
            ],
            [
                'name' => 'Ghana Flat Rate Tax',
                'code' => 'GH_FLAT',
                'type' => 'flat_rate',
                'rate' => 4.00,
                'description' => 'Ghana flat rate tax of 4% for small businesses',
                'applicable_items' => ['general', 'services', 'goods', 'food', 'medicine', 'education', 'small_business'],
                'exempt_items' => []
            ],
            [
                'name' => 'Tax Exempt',
                'code' => 'GH_EXEMPT',
                'type' => 'exempt',
                'rate' => 0.00,
                'description' => 'Tax exempt items and services',
                'applicable_items' => ['exempt'],
                'exempt_items' => []
            ]
        ];

        foreach ($configurations as $config) {
            self::create([
                'company_id' => $companyId,
                'created_by' => $createdBy,
                ...$config
            ]);
        }
    }
}
