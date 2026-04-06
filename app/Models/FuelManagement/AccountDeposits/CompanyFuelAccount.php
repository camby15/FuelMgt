<?php

namespace App\Models\FuelManagement\AccountDeposits;

use App\Models\CompanyProfile;
use App\Models\FuelManagement\FuelStation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyFuelAccount extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TYPE_BANK = 'bank';

    public const TYPE_CASH = 'cash';

    public const TYPE_MOBILE_MONEY = 'mobile_money';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    protected $table = 'company_fuel_accounts';

    protected $fillable = [
        'company_id',
        'account_type',
        'account_code',
        'account_name',
        'description',
        'bank_name',
        'bank_account_no',
        'bank_branch',
        'mobile_money_provider',
        'mobile_money_number',
        'notes',
        'last_reconciled_at',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'last_reconciled_at' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function stations(): BelongsToMany
    {
        return $this->belongsToMany(
            FuelStation::class,
            'cf_account_station',
            'company_fuel_account_id',
            'fuel_station_id'
        )->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeForCompany(Builder $query, ?int $companyId): Builder
    {
        return $companyId ? $query->where('company_fuel_accounts.company_id', $companyId) : $query;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('company_fuel_accounts.status', self::STATUS_ACTIVE);
    }

    public function scopeOfType(Builder $query, ?string $type): Builder
    {
        return $type ? $query->where('company_fuel_accounts.account_type', $type) : $query;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('company_fuel_accounts.account_code', 'like', $like)
                ->orWhere('company_fuel_accounts.account_name', 'like', $like)
                ->orWhere('company_fuel_accounts.description', 'like', $like);
        });
    }

    public function typeLabel(): string
    {
        return match ($this->account_type) {
            self::TYPE_BANK => 'Bank',
            self::TYPE_CASH => 'Cash',
            self::TYPE_MOBILE_MONEY => 'Mobile Money',
            default => ucfirst((string) $this->account_type),
        };
    }
}
