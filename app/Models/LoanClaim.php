<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_request_id',
        'bank_user_id',
        'expires_at',
        'released_at',
        'status', // active, released, expired
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'released_at' => 'datetime',
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
