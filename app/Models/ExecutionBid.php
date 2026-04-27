<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutionBid extends Model
{
    use HasFactory;

    protected $fillable = [
        'execution_request_id',
        'executor_facility_id',
        'executor_user_id',
        'price_total',
        'currency',
        'duration_days',
        'warranty_months',
        'status',
        'score',
        'data',
    ];

    protected $casts = [
        'price_total' => 'decimal:2',
        'data' => 'array',
    ];

    public function request()
    {
        return $this->belongsTo(ExecutionRequest::class, 'execution_request_id');
    }

    public function executorFacility()
    {
        return $this->belongsTo(Facility::class, 'executor_facility_id');
    }

    public function executorUser()
    {
        return $this->belongsTo(User::class, 'executor_user_id');
    }

    public function translations()
    {
        return $this->hasMany(ExecutionBidTranslation::class);
    }
}
