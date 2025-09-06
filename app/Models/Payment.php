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
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
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
}
