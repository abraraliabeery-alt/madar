<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AccountingEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_type', // 'debit', 'credit'
        'account_id', // ID from chart_of_accounts
        'account_type', // 'revenue', 'receivable', 'commission', 'liability', 'expense'
        'amount',
        'description',
        'reference_type', // 'invoice', 'payment', 'contract', 'commission'
        'reference_id',
        'contract_id',
        'facility_id',
        'period_id', // accounting period
        'tax_rate_id', // tax rate applied
        'tax_amount', // calculated tax amount
        'created_by',
        'entry_date',
        'is_reversed', // if this entry is reversed
        'reversed_by', // who reversed it
        'reversed_at', // when it was reversed
        'reversal_reason', // reason for reversal
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'entry_date' => 'date',
        'is_reversed' => 'boolean',
        'reversed_at' => 'datetime',
    ];

    // العلاقات
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function period()
    {
        return $this->belongsTo(AccountingPeriod::class, 'period_id');
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class, 'tax_rate_id');
    }

    public function reversedBy()
    {
        return $this->belongsTo(User::class, 'reversed_by');
    }

    // Scopes
    public function scopeDebit($query)
    {
        return $query->where('entry_type', 'debit');
    }

    public function scopeCredit($query)
    {
        return $query->where('entry_type', 'credit');
    }

    public function scopeByAccountType($query, $accountType)
    {
        return $query->where('account_type', $accountType);
    }

    public function scopeByAccount($query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    public function scopeByReference($query, $type, $id)
    {
        return $query->where('reference_type', $type)->where('reference_id', $id);
    }

    public function scopeByPeriod($query, $periodId)
    {
        return $query->where('period_id', $periodId);
    }

    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_reversed', false);
    }

    public function scopeReversed($query)
    {
        return $query->where('is_reversed', true);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('entry_date', [$startDate, $endDate]);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ريال';
    }

    public function getFormattedTaxAmountAttribute()
    {
        return number_format($this->tax_amount, 2) . ' ريال';
    }

    public function getTotalAmountAttribute()
    {
        return $this->amount + $this->tax_amount;
    }

    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 2) . ' ريال';
    }

    // Methods
    public function reverse($reversedBy, $reason = null)
    {
        if ($this->is_reversed) {
            return false;
        }

        $this->is_reversed = true;
        $this->reversed_by = $reversedBy;
        $this->reversed_at = Carbon::now();
        $this->reversal_reason = $reason;
        $this->save();

        // إنشاء قيد معكوس
        $reversalEntry = self::create([
            'entry_type' => $this->entry_type === 'debit' ? 'credit' : 'debit',
            'account_id' => $this->account_id,
            'account_type' => $this->account_type,
            'amount' => $this->amount,
            'description' => 'إلغاء: ' . $this->description,
            'reference_type' => 'reversal',
            'reference_id' => $this->id,
            'contract_id' => $this->contract_id,
            'facility_id' => $this->facility_id,
            'period_id' => $this->period_id,
            'tax_rate_id' => $this->tax_rate_id,
            'tax_amount' => $this->tax_amount,
            'created_by' => $reversedBy,
            'entry_date' => Carbon::now()->toDateString(),
        ]);

        return $reversalEntry;
    }

    public function canBeReversed()
    {
        return !$this->is_reversed && 
               $this->period && 
               $this->period->status !== 'locked';
    }

    // Static Methods
    public static function createDoubleEntry($debitAccountId, $creditAccountId, $amount, $description, $facilityId, $periodId, $referenceType = null, $referenceId = null, $contractId = null, $taxRateId = null, $createdBy = null)
    {
        $entries = [];

        // قيد مدين
        $entries[] = self::create([
            'entry_type' => 'debit',
            'account_id' => $debitAccountId,
            'account_type' => ChartOfAccount::find($debitAccountId)->account_type,
            'amount' => $amount,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'contract_id' => $contractId,
            'facility_id' => $facilityId,
            'period_id' => $periodId,
            'tax_rate_id' => $taxRateId,
            'created_by' => $createdBy,
            'entry_date' => Carbon::now()->toDateString(),
        ]);

        // قيد دائن
        $entries[] = self::create([
            'entry_type' => 'credit',
            'account_id' => $creditAccountId,
            'account_type' => ChartOfAccount::find($creditAccountId)->account_type,
            'amount' => $amount,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'contract_id' => $contractId,
            'facility_id' => $facilityId,
            'period_id' => $periodId,
            'tax_rate_id' => $taxRateId,
            'created_by' => $createdBy,
            'entry_date' => Carbon::now()->toDateString(),
        ]);

        return $entries;
    }

    public static function createRevenueEntry($amount, $description, $contractId, $facilityId, $periodId, $referenceType = null, $referenceId = null, $accountId = null, $createdBy = null)
    {
        // البحث عن حساب الإيرادات
        $revenueAccount = $accountId ? 
            ChartOfAccount::find($accountId) : 
            ChartOfAccount::where('facility_id', $facilityId)
                ->where('account_type', 'revenue')
                ->where('account_code', '4100')
                ->first();

        if (!$revenueAccount) {
            throw new \Exception('Revenue account not found');
        }

        return self::create([
            'entry_type' => 'credit',
            'account_id' => $revenueAccount->id,
            'account_type' => 'revenue',
            'amount' => $amount,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'contract_id' => $contractId,
            'facility_id' => $facilityId,
            'period_id' => $periodId,
            'created_by' => $createdBy,
            'entry_date' => Carbon::now()->toDateString(),
        ]);
    }

    public static function createReceivableEntry($amount, $description, $contractId, $facilityId, $periodId, $referenceType = null, $referenceId = null, $accountId = null, $createdBy = null)
    {
        // البحث عن حساب الذمم المدينة
        $receivableAccount = $accountId ? 
            ChartOfAccount::find($accountId) : 
            ChartOfAccount::where('facility_id', $facilityId)
                ->where('account_type', 'asset')
                ->where('account_code', '1300')
                ->first();

        if (!$receivableAccount) {
            throw new \Exception('Receivable account not found');
        }

        return self::create([
            'entry_type' => 'debit',
            'account_id' => $receivableAccount->id,
            'account_type' => 'asset',
            'amount' => $amount,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'contract_id' => $contractId,
            'facility_id' => $facilityId,
            'period_id' => $periodId,
            'created_by' => $createdBy,
            'entry_date' => Carbon::now()->toDateString(),
        ]);
    }

    public static function createCommissionEntry($amount, $description, $contractId, $facilityId, $periodId, $referenceType = null, $referenceId = null, $createdBy = null)
    {
        return self::create([
            'entry_type' => 'debit',
            'account_type' => 'expense',
            'amount' => $amount,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'contract_id' => $contractId,
            'facility_id' => $facilityId,
            'period_id' => $periodId,
            'created_by' => $createdBy,
            'entry_date' => Carbon::now()->toDateString(),
        ]);
    }

    public static function createLiabilityEntry($amount, $description, $contractId, $facilityId, $periodId, $referenceType = null, $referenceId = null, $accountId = null, $createdBy = null)
    {
        // البحث عن حساب الخصوم
        $liabilityAccount = $accountId ? 
            ChartOfAccount::find($accountId) : 
            ChartOfAccount::where('facility_id', $facilityId)
                ->where('account_type', 'liability')
                ->where('account_code', '2100')
                ->first();

        if (!$liabilityAccount) {
            throw new \Exception('Liability account not found');
        }

        return self::create([
            'entry_type' => 'credit',
            'account_id' => $liabilityAccount->id,
            'account_type' => 'liability',
            'amount' => $amount,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'contract_id' => $contractId,
            'facility_id' => $facilityId,
            'period_id' => $periodId,
            'created_by' => $createdBy,
            'entry_date' => Carbon::now()->toDateString(),
        ]);
    }

    public static function createCashEntry($amount, $description, $facilityId, $periodId, $referenceType = null, $referenceId = null, $createdBy = null)
    {
        // البحث عن حساب النقدية
        $cashAccount = ChartOfAccount::where('facility_id', $facilityId)
            ->where('account_type', 'asset')
            ->where('account_code', '1100')
            ->first();

        if (!$cashAccount) {
            throw new \Exception('Cash account not found');
        }

        return self::create([
            'entry_type' => 'debit',
            'account_id' => $cashAccount->id,
            'account_type' => 'asset',
            'amount' => $amount,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'facility_id' => $facilityId,
            'period_id' => $periodId,
            'created_by' => $createdBy,
            'entry_date' => Carbon::now()->toDateString(),
        ]);
    }
}
