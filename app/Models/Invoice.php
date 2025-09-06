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
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
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
}
