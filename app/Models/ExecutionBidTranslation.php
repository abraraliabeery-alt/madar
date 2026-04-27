<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutionBidTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'execution_bid_id',
        'locale',
        'title',
        'notes',
    ];

    public function bid()
    {
        return $this->belongsTo(ExecutionBid::class, 'execution_bid_id');
    }
}
