<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_id',
        'invoice_number',
        'invoice_type', // 'rent', 'sale', 'deposit', 'commission', 'refund'
        'amount',
        'currency',
        'due_date',
        'paid_amount',
        'remaining_amount',
        'status', // 'draft', 'sent', 'paid', 'overdue', 'cancelled'
        'payment_terms',
        'notes',
        'facility_id',
        'created_by',
        'installment_number', // رقم القسط
        'installment_amount', // مبلغ القسط
        'late_fee_amount', // رسوم التأخير
        'discount_amount', // مبلغ الخصم
        'tax_rate', // نسبة الضريبة
        'tax_amount', // مبلغ الضريبة
        'net_amount', // المبلغ الصافي
        'payment_terms_days', // مدة السداد بالأيام
        'reminder_sent', // تم إرسال التذكير
        'reminder_count', // عدد التذكيرات
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
        'installment_amount' => 'decimal:2',
        'late_fee_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_rate' => 'decimal:5,4',
        'tax_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'payment_terms_days' => 'integer',
        'reminder_sent' => 'boolean',
        'reminder_count' => 'integer',
        'installment_number' => 'integer',
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function translations()
    {
        return $this->hasMany(InvoiceTranslation::class);
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
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'sent']);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('invoice_type', $type);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function getFormattedPaidAmountAttribute()
    {
        return number_format($this->paid_amount, 2) . ' ' . $this->currency;
    }

    public function getFormattedRemainingAmountAttribute()
    {
        return number_format($this->remaining_amount, 2) . ' ' . $this->currency;
    }

    // Methods
    public function calculateRemainingAmount()
    {
        $this->remaining_amount = $this->amount - $this->paid_amount;
        return $this;
    }

    public function isPaid()
    {
        return $this->status === 'paid' || $this->remaining_amount <= 0;
    }

    public function isOverdue()
    {
        return $this->status === 'overdue' || 
               ($this->due_date && now()->toDateString() > $this->due_date && !$this->isPaid());
    }

    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->remaining_amount = 0;
        $this->save();
    }

    public function markAsOverdue()
    {
        $this->status = 'overdue';
        $this->save();
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
     * Get notes for specific locale
     */
    public function getTranslatedNotes($locale = null)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->notes : $this->notes;
    }

    /**
     * Calculate tax amount
     */
    public function calculateTaxAmount()
    {
        if ($this->tax_rate) {
            $this->tax_amount = $this->amount * $this->tax_rate;
        }
        return $this;
    }

    /**
     * Calculate net amount
     */
    public function calculateNetAmount()
    {
        $this->net_amount = $this->amount + $this->tax_amount + $this->late_fee_amount - $this->discount_amount;
        return $this;
    }

    /**
     * Calculate late fees
     */
    public function calculateLateFees()
    {
        if ($this->isOverdue()) {
            $daysLate = now()->diffInDays($this->due_date);
            $this->late_fee_amount = $this->amount * 0.01 * $daysLate; // 1% per day
        }
        return $this;
    }

    /**
     * Generate invoice number
     */
    public function generateInvoiceNumber()
    {
        if (!$this->invoice_number) {
            $prefix = strtoupper($this->invoice_type);
            $this->invoice_number = $prefix . '-' . date('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
        }
        return $this;
    }

    /**
     * Create accounting entries for this invoice
     */
    public function createAccountingEntries()
    {
        // إيراد
        AccountingEntry::createRevenueEntry(
            $this->amount,
            "فاتورة {$this->invoice_type} - {$this->invoice_number}",
            $this->contract_id,
            $this->facility_id,
            'invoice',
            $this->id
        );

        // ذمم على العميل
        AccountingEntry::createReceivableEntry(
            $this->amount,
            "ذمم العميل - فاتورة {$this->invoice_number}",
            $this->contract_id,
            $this->facility_id,
            'invoice',
            $this->id
        );

        // إذا كان هناك عمولة
        if ($this->contract && $this->contract->commission_amount > 0) {
            AccountingEntry::createCommissionEntry(
                $this->contract->commission_amount,
                "عمولة المنصة - فاتورة {$this->invoice_number}",
                $this->contract_id,
                $this->facility_id,
                'invoice',
                $this->id
            );
        }
    }

    /**
     * Update accounting entries when payment is received
     */
    public function updateAccountingEntriesOnPayment($paymentAmount)
    {
        // تقليل الذمم
        AccountingEntry::createReceivableEntry(
            -$paymentAmount,
            "دفعة مستلمة - فاتورة {$this->invoice_number}",
            $this->contract_id,
            $this->facility_id,
            'payment',
            $this->id
        );

        // إضافة النقدية
        AccountingEntry::create([
            'entry_type' => 'debit',
            'account_type' => 'cash',
            'amount' => $paymentAmount,
            'description' => "نقدية - دفعة فاتورة {$this->invoice_number}",
            'contract_id' => $this->contract_id,
            'facility_id' => $this->facility_id,
            'reference_type' => 'payment',
            'reference_id' => $this->id,
            'entry_date' => now()->toDateString(),
        ]);
    }

    /**
     * Send payment reminder
     */
    public function sendPaymentReminder()
    {
        if (!$this->isOverdue() || $this->isPaid()) {
            return false;
        }

        // إرسال التذكير (يمكن تطوير هذا لاحقاً)
        $this->reminder_sent = true;
        $this->reminder_count = $this->reminder_count + 1;
        $this->save();

        return true;
    }

    /**
     * Get formatted invoice details
     */
    public function getFormattedDetails()
    {
        return [
            'invoice_number' => $this->invoice_number,
            'amount' => $this->formatted_amount,
            'due_date' => $this->due_date->format('Y-m-d'),
            'status' => $this->status,
            'installment' => $this->installment_number ? "قسط رقم {$this->installment_number}" : null,
            'late_fees' => $this->late_fee_amount > 0 ? $this->late_fee_amount : null,
        ];
    }

    /**
     * Check if invoice needs reminder
     */
    public function needsReminder()
    {
        return $this->isOverdue() && 
               !$this->isPaid() && 
               $this->reminder_count < 3;
    }

    /**
     * Get days until due
     */
    public function getDaysUntilDue()
    {
        if (!$this->due_date) {
            return null;
        }
        
        return now()->diffInDays($this->due_date, false);
    }
}
