<?php

namespace App\Models;

use App\Enums\DiscountMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'name',
        'method',
        'value',
        'condition',
        'sort_order',
    ];

    protected $casts = [
        'method' => DiscountMethod::class,
        'value' => 'float',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function calculateAmount(float $base): float
    {
        if ($this->method === DiscountMethod::Percentage) {
            return $base * ($this->value / 100);
        }

        return $this->value;
    }
}
