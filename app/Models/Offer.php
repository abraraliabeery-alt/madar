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
        'currency',
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
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'commission_rate' => 'decimal:5,4', // نسبة العمولة (مثل 0.05 = 5%)
        'commission_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'valid_from' => 'date',
        'valid_to' => 'date',
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
        return number_format($this->price, 2) . ' ' . $this->currency;
    }

    public function getFormattedDepositAttribute()
    {
        return number_format($this->deposit_amount, 2) . ' ' . $this->currency;
    }

    public function getFormattedCommissionAttribute()
    {
        return number_format($this->commission_amount, 2) . ' ' . $this->currency;
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
}
