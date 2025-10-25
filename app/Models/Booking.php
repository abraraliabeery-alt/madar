<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'offer_id',
        'facility_id',
        'user_id',
        'name',
        'email',
        'phone',
        'preferred_date',
        'preferred_time',
        'message',
        'visit_type',
        'status',
        'reschedule_reason',
        'cancellation_reason',
        'rescheduled_at',
        'cancelled_at',
        'created_by',
    ];

    protected $casts = [
        'preferred_date' => 'datetime',
        'rescheduled_at' => 'datetime',
        'cancelled_at' => 'datetime',
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

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
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
