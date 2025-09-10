<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'contract_id',
        'payment_method', // 'cash', 'bank_transfer', 'credit_card', 'check', 'online'
        'amount',
        'currency',
        'payment_date',
        'reference_number',
        'bank_name',
        'check_number',
        'notes',
        'status', // 'pending', 'confirmed', 'failed', 'refunded'
        'facility_id',
        'created_by',
        'payment_reference', // مرجع الدفع
        'bank_reference', // مرجع البنك
        'transaction_id', // معرف المعاملة
        'processing_fee', // رسوم المعالجة
        'currency_rate', // سعر الصرف
        'payment_gateway', // بوابة الدفع
        'gateway_response', // استجابة البوابة (JSON)
        'installment_number', // رقم القسط
        'late_fee_paid', // رسوم التأخير المدفوعة
        'discount_applied', // الخصم المطبق
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'processing_fee' => 'decimal:2',
        'currency_rate' => 'decimal:6,4',
        'gateway_response' => 'array',
        'late_fee_paid' => 'decimal:2',
        'discount_applied' => 'decimal:2',
        'installment_number' => 'integer',
    ];

    // العلاقات
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

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
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    // Methods
    public function confirm()
    {
        $this->status = 'confirmed';
        $this->save();

        // تحديث الفاتورة
        if ($this->invoice) {
            $this->invoice->paid_amount += $this->amount;
            $this->invoice->calculateRemainingAmount();
            $this->invoice->save();

            // إذا تم دفع كامل المبلغ
            if ($this->invoice->remaining_amount <= 0) {
                $this->invoice->markAsPaid();
            }
        }
    }

    public function fail()
    {
        $this->status = 'failed';
        $this->save();
    }

    public function refund()
    {
        $this->status = 'refunded';
        $this->save();

        // تحديث الفاتورة
        if ($this->invoice) {
            $this->invoice->paid_amount -= $this->amount;
            $this->invoice->calculateRemainingAmount();
            $this->invoice->save();
        }
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isRefunded()
    {
        return $this->status === 'refunded';
    }

    /**
     * Create accounting entries for this payment
     */
    public function createAccountingEntries()
    {
        // تقليل الذمم
        AccountingEntry::createReceivableEntry(
            -$this->amount,
            "دفعة مستلمة - {$this->reference_number}",
            $this->contract_id,
            $this->facility_id,
            'payment',
            $this->id
        );

        // إضافة النقدية
        AccountingEntry::create([
            'entry_type' => 'debit',
            'account_type' => 'cash',
            'amount' => $this->amount,
            'description' => "نقدية - دفعة {$this->reference_number}",
            'contract_id' => $this->contract_id,
            'facility_id' => $this->facility_id,
            'reference_type' => 'payment',
            'reference_id' => $this->id,
            'entry_date' => $this->payment_date,
        ]);

        // إذا كان هناك رسوم معالجة
        if ($this->processing_fee > 0) {
            AccountingEntry::create([
                'entry_type' => 'debit',
                'account_type' => 'expense',
                'amount' => $this->processing_fee,
                'description' => "رسوم معالجة - {$this->reference_number}",
                'contract_id' => $this->contract_id,
                'facility_id' => $this->facility_id,
                'reference_type' => 'payment',
                'reference_id' => $this->id,
                'entry_date' => $this->payment_date,
            ]);
        }
    }

    /**
     * Reverse accounting entries for refund
     */
    public function reverseAccountingEntries()
    {
        // عكس تقليل الذمم
        AccountingEntry::createReceivableEntry(
            $this->amount,
            "استرداد - {$this->reference_number}",
            $this->contract_id,
            $this->facility_id,
            'refund',
            $this->id
        );

        // عكس إضافة النقدية
        AccountingEntry::create([
            'entry_type' => 'credit',
            'account_type' => 'cash',
            'amount' => $this->amount,
            'description' => "استرداد نقدية - {$this->reference_number}",
            'contract_id' => $this->contract_id,
            'facility_id' => $this->facility_id,
            'reference_type' => 'refund',
            'reference_id' => $this->id,
            'entry_date' => now()->toDateString(),
        ]);
    }

    /**
     * Get net amount after fees and discounts
     */
    public function getNetAmount()
    {
        return $this->amount + $this->processing_fee + $this->late_fee_paid - $this->discount_applied;
    }

    /**
     * Get formatted payment details
     */
    public function getFormattedDetails()
    {
        return [
            'reference' => $this->reference_number,
            'amount' => $this->formatted_amount,
            'method' => $this->payment_method,
            'date' => $this->payment_date->format('Y-m-d'),
            'status' => $this->status,
            'installment' => $this->installment_number ? "قسط رقم {$this->installment_number}" : null,
        ];
    }

    /**
     * Check if payment is for installment
     */
    public function isInstallmentPayment()
    {
        return $this->installment_number !== null;
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayName()
    {
        $methods = [
            'cash' => 'نقداً',
            'bank_transfer' => 'تحويل بنكي',
            'credit_card' => 'بطاقة ائتمان',
            'check' => 'شيك',
            'online' => 'دفع إلكتروني',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Generate payment reference
     */
    public function generatePaymentReference()
    {
        if (!$this->payment_reference) {
            $this->payment_reference = 'PAY-' . date('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
        }
        return $this;
    }

    /**
     * Update contract payment status
     */
    public function updateContractPaymentStatus()
    {
        if ($this->contract && $this->isConfirmed()) {
            $this->contract->markInstallmentAsPaid($this->installment_number, $this->amount);
        }
    }
}
