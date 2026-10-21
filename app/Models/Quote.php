<?php

namespace App\Models;

use App\Enums\QuoteStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'created_by',
        'quote_number',
        'title',
        'client_name',
        'client_email',
        'client_phone',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => QuoteStatus::class,
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class)->orderBy('sort_order');
    }

    public function terms(): HasMany
    {
        return $this->hasMany(QuoteTerm::class)->orderBy('sort_order');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(QuoteDiscount::class)->orderBy('sort_order');
    }

    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(fn (QuoteItem $item) => $item->line_total);
    }

    public function getDiscountsTotalAttribute(): float
    {
        $subtotal = $this->subtotal;

        return $this->discounts->sum(function (QuoteDiscount $discount) use ($subtotal) {
            return $discount->calculateAmount($subtotal);
        });
    }

    public function getTotalAttribute(): float
    {
        return max(0, $this->subtotal - $this->discounts_total);
    }
}
