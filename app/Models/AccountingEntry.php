<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_type', // 'debit', 'credit'
        'account_type', // 'revenue', 'receivable', 'commission', 'liability', 'expense'
        'amount',
        'currency',
        'description',
        'reference_type', // 'invoice', 'payment', 'contract', 'commission'
        'reference_id',
        'contract_id',
        'facility_id',
        'created_by',
        'entry_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'entry_date' => 'date',
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

    public function scopeByReference($query, $type, $id)
    {
        return $query->where('reference_type', $type)->where('reference_id', $id);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    // Static Methods
    public static function createRevenueEntry($amount, $description, $contractId, $facilityId, $referenceType = null, $referenceId = null)
    {
        return self::create([
            'entry_type' => 'credit',
            'account_type' => 'revenue',
            'amount' => $amount,
            'description' => $description,
            'contract_id' => $contractId,
            'facility_id' => $facilityId,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'entry_date' => now()->toDateString(),
        ]);
    }

    public static function createReceivableEntry($amount, $description, $contractId, $facilityId, $referenceType = null, $referenceId = null)
    {
        return self::create([
            'entry_type' => 'debit',
            'account_type' => 'receivable',
            'amount' => $amount,
            'description' => $description,
            'contract_id' => $contractId,
            'facility_id' => $facilityId,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'entry_date' => now()->toDateString(),
        ]);
    }

    public static function createCommissionEntry($amount, $description, $contractId, $facilityId, $referenceType = null, $referenceId = null)
    {
        return self::create([
            'entry_type' => 'debit',
            'account_type' => 'commission',
            'amount' => $amount,
            'description' => $description,
            'contract_id' => $contractId,
            'facility_id' => $facilityId,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'entry_date' => now()->toDateString(),
        ]);
    }

    public static function createLiabilityEntry($amount, $description, $contractId, $facilityId, $referenceType = null, $referenceId = null)
    {
        return self::create([
            'entry_type' => 'credit',
            'account_type' => 'liability',
            'amount' => $amount,
            'description' => $description,
            'contract_id' => $contractId,
            'facility_id' => $facilityId,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'entry_date' => now()->toDateString(),
        ]);
    }
}
