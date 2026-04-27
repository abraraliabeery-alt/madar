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
        'deposit_amount',
        'commission_rate',
        'commission_amount',
        'status', // 'draft', 'active', 'completed', 'cancelled'
        'terms_conditions',
        'facility_id',
        'created_by',
        'is_active',
        'is_verified',
        'payment_plan', // خطة الدفع (JSON)
        'payment_frequency', // تكرار الدفع (monthly, quarterly, yearly, custom)
        'total_installments', // إجمالي الأقساط
        'paid_installments', // الأقساط المدفوعة
        'next_payment_date', // تاريخ الدفعة القادمة
        'late_fee_rate', // نسبة رسوم التأخير
        'late_fee_amount', // مبلغ رسوم التأخير
        'early_payment_discount', // خصم الدفع المبكر
        'contract_duration_months', // مدة العقد بالأشهر
        'renewal_terms', // شروط التجديد
        'termination_terms', // شروط الإنهاء
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_payment_date' => 'date',
        'total_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'commission_rate' => 'decimal:5,4',
        'commission_amount' => 'decimal:2',
        'late_fee_rate' => 'decimal:5,4',
        'late_fee_amount' => 'decimal:2',
        'early_payment_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'payment_plan' => 'array',
        'total_installments' => 'integer',
        'paid_installments' => 'integer',
        'contract_duration_months' => 'integer',
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

    public function commissions()
    {
        return $this->hasMany(CommissionParty::class);
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
        return number_format($this->total_amount, 2) . ' ريال';
    }

    public function getFormattedDepositAmountAttribute()
    {
        return number_format($this->deposit_amount, 2) . ' ريال';
    }

    public function getFormattedCommissionAmountAttribute()
    {
        return number_format($this->commission_amount, 2) . ' ريال';
    }

    public function getTotalCommissionsAmountAttribute()
    {
        return $this->commissions()->sum('calculated_amount');
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
     * Generate payment plan based on frequency
     */
    public function generatePaymentPlan()
    {
        if (!$this->total_amount || !$this->payment_frequency) {
            return [];
        }

        $installments = [];
        $installmentAmount = $this->total_amount / $this->total_installments;
        $currentDate = $this->start_date;

        for ($i = 0; $i < $this->total_installments; $i++) {
            $dueDate = $this->calculateNextPaymentDate($currentDate, $i);
            
            $installments[] = [
                'installment_number' => $i + 1,
                'amount' => $installmentAmount,
                'due_date' => $dueDate->toDateString(),
                'status' => 'pending',
                'paid_amount' => 0,
                'remaining_amount' => $installmentAmount,
            ];

            $currentDate = $dueDate;
        }

        $this->payment_plan = $installments;
        return $installments;
    }

    /**
     * Calculate next payment date based on frequency
     */
    private function calculateNextPaymentDate($startDate, $installmentNumber)
    {
        switch ($this->payment_frequency) {
            case 'monthly':
                return $startDate->addMonths($installmentNumber);
            case 'quarterly':
                return $startDate->addMonths($installmentNumber * 3);
            case 'yearly':
                return $startDate->addYears($installmentNumber);
            case 'custom':
                // For custom frequency, use the payment_plan array
                return $startDate->addDays($installmentNumber * 30); // Default to monthly
            default:
                return $startDate->addMonths($installmentNumber);
        }
    }

    /**
     * Get next unpaid installment
     */
    public function getNextUnpaidInstallment()
    {
        $plan = $this->payment_plan;
        foreach ($plan as $installment) {
            if ($installment['status'] === 'pending') {
                return $installment;
            }
        }
        return null;
    }

    /**
     * Mark installment as paid
     */
    public function markInstallmentAsPaid($installmentNumber, $paidAmount)
    {
        $plan = $this->payment_plan;
        if (isset($plan[$installmentNumber - 1])) {
            $plan[$installmentNumber - 1]['status'] = 'paid';
            $plan[$installmentNumber - 1]['paid_amount'] = $paidAmount;
            $plan[$installmentNumber - 1]['remaining_amount'] = 0;
            
            $this->payment_plan = $plan;
            $this->paid_installments = $this->paid_installments + 1;
            $this->save();
        }
    }

    /**
     * Calculate late fees
     */
    public function calculateLateFees()
    {
        $lateFees = 0;
        $plan = $this->payment_plan;
        
        foreach ($plan as $installment) {
            if ($installment['status'] === 'pending' && 
                now()->toDateString() > $installment['due_date']) {
                
                $daysLate = now()->diffInDays($installment['due_date']);
                $lateFee = $installment['amount'] * $this->late_fee_rate * $daysLate;
                $lateFees += $lateFee;
            }
        }
        
        return $lateFees;
    }

    /**
     * Check if contract is overdue
     */
    public function isOverdue()
    {
        return $this->next_payment_date && now()->toDateString() > $this->next_payment_date;
    }

    /**
     * Get contract progress percentage
     */
    public function getProgressPercentage()
    {
        if ($this->total_installments == 0) {
            return 0;
        }
        
        return ($this->paid_installments / $this->total_installments) * 100;
    }

    /**
     * Check if contract can be renewed
     */
    public function canBeRenewed()
    {
        return $this->isCompleted() && 
               $this->contract_type === 'rent' && 
               $this->renewal_terms;
    }

    /**
     * Renew contract
     */
    public function renewContract($newDurationMonths = null)
    {
        if (!$this->canBeRenewed()) {
            return false;
        }

        $duration = $newDurationMonths ?: $this->contract_duration_months;
        
        $this->end_date = $this->end_date->addMonths($duration);
        $this->contract_duration_months += $duration;
        $this->status = 'active';
        $this->save();

        return true;
    }
}
