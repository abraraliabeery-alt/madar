<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax_name',
        'tax_code',
        'rate',
        'tax_type',
        'calculation_method',
        'fixed_amount',
        'is_inclusive',
        'is_active',
        'effective_from',
        'effective_to',
        'description',
        'applicable_accounts',
        'facility_id',
        'created_by',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'fixed_amount' => 'decimal:2',
        'is_inclusive' => 'boolean',
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'applicable_accounts' => 'array',
    ];

    // العلاقات
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('tax_type', $type);
    }

    public function scopeEffective($query, $date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        
        return $query->where('effective_from', '<=', $date)
                    ->where(function($q) use ($date) {
                        $q->whereNull('effective_to')
                          ->orWhere('effective_to', '>=', $date);
                    });
    }

    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    // Accessors
    public function getFormattedRateAttribute()
    {
        if ($this->calculation_method === 'percentage') {
            return number_format($this->rate * 100, 2) . '%';
        } else {
            return number_format($this->fixed_amount, 2) . ' ' . 'SAR';
        }
    }

    public function getIsCurrentlyEffectiveAttribute()
    {
        $now = Carbon::now();
        return $this->is_active && 
               $this->effective_from <= $now && 
               ($this->effective_to === null || $this->effective_to >= $now);
    }

    // Methods
    public function calculateTax($amount)
    {
        if (!$this->is_currently_effective) {
            return 0;
        }

        if ($this->calculation_method === 'percentage') {
            return $amount * $this->rate;
        } else {
            return $this->fixed_amount;
        }
    }

    public function calculateAmountWithTax($amount)
    {
        if ($this->is_inclusive) {
            return $amount; // الضريبة شاملة
        } else {
            return $amount + $this->calculateTax($amount);
        }
    }

    public function calculateAmountWithoutTax($amount)
    {
        if ($this->is_inclusive) {
            return $amount / (1 + $this->rate);
        } else {
            return $amount;
        }
    }

    public function isApplicableToAccount($accountId)
    {
        if (empty($this->applicable_accounts)) {
            return true; // يطبق على جميع الحسابات
        }

        return in_array($accountId, $this->applicable_accounts);
    }

    public function canBeDeleted()
    {
        // يمكن حذف الضريبة إذا لم تكن مستخدمة في أي قيود محاسبية
        return !AccountingEntry::where('tax_rate_id', $this->id)->exists();
    }

    // Static Methods
    public static function getTaxTypes()
    {
        return [
            'vat' => 'ضريبة القيمة المضافة',
            'income_tax' => 'ضريبة الدخل',
            'withholding' => 'ضريبة الاستقطاع',
            'stamp_tax' => 'ضريبة الدمغة',
            'other' => 'أخرى',
        ];
    }

    public static function getCalculationMethods()
    {
        return [
            'percentage' => 'نسبة مئوية',
            'fixed_amount' => 'مبلغ ثابت',
        ];
    }

    public static function getDefaultTaxRates($facilityId, $createdBy)
    {
        $defaultRates = [
            [
                'tax_name' => 'ضريبة القيمة المضافة',
                'tax_code' => 'VAT',
                'rate' => 0.15,
                'tax_type' => 'vat',
                'calculation_method' => 'percentage',
                'is_inclusive' => false,
                'is_active' => true,
                'effective_from' => Carbon::now(),
                'description' => 'ضريبة القيمة المضافة 15%',
            ],
            [
                'tax_name' => 'ضريبة الاستقطاع',
                'tax_code' => 'WITHHOLDING',
                'rate' => 0.05,
                'tax_type' => 'withholding',
                'calculation_method' => 'percentage',
                'is_inclusive' => false,
                'is_active' => true,
                'effective_from' => Carbon::now(),
                'description' => 'ضريبة الاستقطاع 5%',
            ],
        ];

        foreach ($defaultRates as $rate) {
            self::create(array_merge($rate, [
                'facility_id' => $facilityId,
                'created_by' => $createdBy,
            ]));
        }
    }
}