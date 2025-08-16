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
        'total_amount',
        'payment_method',
        'expires_at',
        'is_confirmed',
        'is_paid',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_confirmed' => 'boolean',
        'is_paid' => 'boolean',
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

    public function facility()
    {
        return $this->hasOneThrough(Facility::class, Product::class, 'id', 'id', 'product_id', 'facility_id');
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
}
