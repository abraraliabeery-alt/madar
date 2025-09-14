<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'offer_type', // 'sale', 'rent_monthly', 'rent_yearly', 'rent_daily'
        'price',
        'deposit_amount',
        'commission_rate',
        'commission_amount',
        'is_active',
        'is_featured',
        'valid_from',
        'valid_to',
        'terms_conditions',
        'facility_id',
        'created_by',
        'offer_title', // عنوان العرض
        'offer_description', // وصف العرض
        'payment_plan', // خطة الدفع (JSON)
        'special_conditions', // شروط خاصة
        'marketing_notes', // ملاحظات تسويقية
        'priority', // أولوية العرض (1-10)
        'auto_renew', // تجديد تلقائي
        'min_contract_duration', // مدة العقد الأدنى
        'max_contract_duration', // مدة العقد القصوى
    ];

    protected $casts = [
        'price' => 'float',
        'deposit_amount' => 'float',
        'commission_rate' => 'float', // نسبة العمولة (مثل 0.05 = 5%)
        'commission_amount' => 'float',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'payment_plan' => 'array',
        'auto_renew' => 'boolean',
        'min_contract_duration' => 'integer',
        'max_contract_duration' => 'integer',
        'priority' => 'integer',
    ];

    // العلاقات
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function translations()
    {
        return $this->hasMany(OfferTranslation::class);
    }

    // Polymorphic relationship for statuses
    public function statuses()
    {
        return $this->morphToMany(Status::class, 'statusable', 'statusables');
    }

    // Accessor to get the current status
    public function getStatusAttribute()
    {
        return $this->statuses()->latest()->first();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('offer_type', $type);
    }

    public function scopeValid($query)
    {
        $now = now()->toDateString();
        return $query->where(function($q) use ($now) {
            $q->where('valid_from', '<=', $now)
              ->where(function($q2) use ($now) {
                  $q2->whereNull('valid_to')
                     ->orWhere('valid_to', '>=', $now);
              });
        });
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' ريال';
    }

    public function getFormattedDepositAttribute()
    {
        return number_format($this->deposit_amount, 2) . ' ريال';
    }

    public function getFormattedCommissionAttribute()
    {
        return number_format($this->commission_amount, 2) . ' ريال';
    }

    // Methods
    public function calculateCommission()
    {
        if ($this->commission_rate) {
            $this->commission_amount = $this->price * $this->commission_rate;
        }
        return $this;
    }

    public function isExpired()
    {
        if (!$this->valid_to) {
            return false;
        }
        return now()->toDateString() > $this->valid_to;
    }

    public function isActive()
    {
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Get translation for specific locale
     */
    public function getTranslation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Get terms and conditions for specific locale
     */
    public function getTranslatedTerms($locale = null)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->terms_conditions : $this->terms_conditions;
    }

    /**
     * Get offer title for specific locale
     */
    public function getTranslatedTitle($locale = null)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->offer_title : $this->offer_title;
    }

    /**
     * Get offer description for specific locale
     */
    public function getTranslatedDescription($locale = null)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->offer_description : $this->offer_description;
    }

    /**
     * Get payment plan as formatted array
     */
    public function getPaymentPlanAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Set payment plan from array
     */
    public function setPaymentPlanAttribute($value)
    {
        $this->attributes['payment_plan'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Check if offer is for sale
     */
    public function isForSale()
    {
        return $this->offer_type === 'sale';
    }

    /**
     * Check if offer is for rent
     */
    public function isForRent()
    {
        return str_starts_with($this->offer_type, 'rent_');
    }

    /**
     * Get rent period in days
     */
    public function getRentPeriodInDays()
    {
        switch ($this->offer_type) {
            case 'rent_daily':
                return 1;
            case 'rent_monthly':
                return 30;
            case 'rent_yearly':
                return 365;
            default:
                return null;
        }
    }

    /**
     * Calculate total commission amount
     */
    public function calculateTotalCommission()
    {
        if ($this->commission_rate) {
            return $this->price * $this->commission_rate;
        }
        return $this->commission_amount ?? 0;
    }

    /**
     * Get net amount after commission
     */
    public function getNetAmount()
    {
        return $this->price - $this->calculateTotalCommission();
    }

    /**
     * Check if offer can be renewed
     */
    public function canBeRenewed()
    {
        return $this->auto_renew && $this->isForRent();
    }

    /**
     * Get formatted payment plan
     */
    public function getFormattedPaymentPlan()
    {
        $plan = $this->payment_plan;
        if (empty($plan)) {
            return 'دفعة واحدة';
        }

        $formatted = [];
        foreach ($plan as $installment) {
            $formatted[] = "{$installment['amount']} ريال - {$installment['due_date']}";
        }
        return implode(' | ', $formatted);
    }
}
