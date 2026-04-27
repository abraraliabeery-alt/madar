<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_request_id',
        'bank_user_id',
        'amount',
        'profit_rate',
        'term_months',
        'monthly_payment',
        'fees',
        'notes',
        'status',
    ];

    protected $casts = [
        'amount' => 'float',
        'profit_rate' => 'float',
        'monthly_payment' => 'float',
        'fees' => 'float',
    ];

    public function request()
    {
        return $this->belongsTo(LoanRequest::class, 'loan_request_id');
    }

    public function banker()
    {
        return $this->belongsTo(User::class, 'bank_user_id');
    }
}
