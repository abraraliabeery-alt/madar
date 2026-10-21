<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OwnerPayout extends Model
{
    protected $fillable = [
        'facility_id',
        'owner_id',
        'created_by',
        'amount',
        'paid_at',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'date',
        'amount' => 'decimal:2',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
