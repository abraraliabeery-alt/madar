<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'payment_method',
        'expires_at',
        'is_confirmed',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_confirmed' => 'boolean',
    ];

    // العلاقات
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
