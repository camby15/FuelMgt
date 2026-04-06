<?php

namespace App\Models\FuelManagement\AccountDeposits;

use App\Models\CompanyProfile;
use App\Models\FuelManagement\FuelStation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyFuelBankDeposit extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const PAYMENT_CASH = 'Cash';

    public const PAYMENT_CHEQUE = 'Cheque';

    public const PAYMENT_MOBILE_MONEY = 'Mobile Money';

    public const PAYMENT_BANK_TRANSFER = 'Bank Transfer';

    protected $table = 'company_fuel_bank_deposits';

    protected $fillable = [
        'company_id',
        'fuel_station_id',
        'transaction_date',
        'sales_date',
        'account_name',
        'account_number',
        'amount',
        'deposit_by',
        'narration',
        'details',
        'payment_mode',
        'transaction_id',
        'proof_path',
        'proof_original_name',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'fuel_station_id' => 'integer',
        'transaction_date' => 'date',
        'sales_date' => 'date',
        'amount' => 'decimal:2',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(FuelStation::class, 'fuel_station_id');
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
        return $companyId ? $query->where('company_fuel_bank_deposits.company_id', $companyId) : $query;
    }

    public function scopeForStation(Builder $query, ?int $stationId): Builder
    {
        return $stationId ? $query->where('company_fuel_bank_deposits.fuel_station_id', $stationId) : $query;
    }

    public function scopeTransactionDateBetween(Builder $query, ?string $from, ?string $to): Builder
    {
        if ($from) {
            $query->whereDate('company_fuel_bank_deposits.transaction_date', '>=', $from);
        }

        if ($to) {
            $query->whereDate('company_fuel_bank_deposits.transaction_date', '<=', $to);
        }

        return $query;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('company_fuel_bank_deposits.account_name', 'like', $like)
                ->orWhere('company_fuel_bank_deposits.account_number', 'like', $like)
                ->orWhere('company_fuel_bank_deposits.transaction_id', 'like', $like)
                ->orWhere('company_fuel_bank_deposits.deposit_by', 'like', $like)
                ->orWhere('company_fuel_bank_deposits.narration', 'like', $like)
                ->orWhere('company_fuel_bank_deposits.details', 'like', $like)
                ->orWhereHas('station', function (Builder $sq) use ($like) {
                    $sq->where('fuel_stations.name', 'like', $like)
                        ->orWhere('fuel_stations.code', 'like', $like);
                });
        });
    }

    public static function paymentModes(): array
    {
        return [
            self::PAYMENT_CASH,
            self::PAYMENT_CHEQUE,
            self::PAYMENT_MOBILE_MONEY,
            self::PAYMENT_BANK_TRANSFER,
        ];
    }
}
