<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionParty extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'user_id',
        'name',
        'role',
        'commission_type',
        'commission_value',
        'calculated_amount',
        'status',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
