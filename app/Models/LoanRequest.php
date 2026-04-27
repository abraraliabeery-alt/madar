<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'assigned_advisor_id',
        'chosen_offer_id',
        'sla_due_at',
        'notes',
    ];

    protected $casts = [
        'sla_due_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function advisor()
    {
        return $this->belongsTo(User::class, 'assigned_advisor_id');
    }

    public function chosenOffer()
    {
        return $this->belongsTo(LoanOffer::class, 'chosen_offer_id');
    }

    public function offers()
    {
        return $this->hasMany(LoanOffer::class);
    }

    public function claims()
    {
        return $this->hasMany(LoanClaim::class);
    }
}
