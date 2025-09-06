<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'offer_id',
        'user_id',
        'owner_id',
        'contract_type', // 'sale', 'rent'
        'contract_number',
        'start_date',
        'end_date',
        'total_amount',
        'currency',
        'deposit_amount',
        'commission_rate',
        'commission_amount',
        'status', // 'draft', 'active', 'completed', 'cancelled'
        'terms_conditions',
        'facility_id',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'commission_rate' => 'decimal:5,4',
        'commission_amount' => 'decimal:2',
    ];

    // العلاقات
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function translations()
    {
        return $this->hasMany(ContractTranslation::class);
    }

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function accountingEntries()
    {
        return $this->hasMany(AccountingEntry::class);
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
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('contract_type', $type);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 2) . ' ' . $this->currency;
    }

    public function getFormattedDepositAmountAttribute()
    {
        return number_format($this->deposit_amount, 2) . ' ' . $this->currency;
    }

    public function getFormattedCommissionAmountAttribute()
    {
        return number_format($this->commission_amount, 2) . ' ' . $this->currency;
    }

    // Methods
    public function calculateCommission()
    {
        if ($this->commission_rate) {
            $this->commission_amount = $this->total_amount * $this->commission_rate;
        }
        return $this;
    }

    public function generateContractNumber()
    {
        if (!$this->contract_number) {
            $prefix = $this->contract_type === 'sale' ? 'SALE' : 'RENT';
            $this->contract_number = $prefix . '-' . date('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
        }
        return $this;
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function getTotalPaidAmount()
    {
        return $this->payments()->confirmed()->sum('amount');
    }

    public function getRemainingAmount()
    {
        return $this->total_amount - $this->getTotalPaidAmount();
    }

    public function isFullyPaid()
    {
        return $this->getRemainingAmount() <= 0;
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
