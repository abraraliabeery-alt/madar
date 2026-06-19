<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanLot extends Model
{
    use HasFactory;

    protected $table = 'plan_lots';

    protected $fillable = [
        'plan_id',
        'lot_number',
        'excel_lot_number',
        'usage',
        'status',
        'area_m2',
        'price',
        'geometry',
        'centroid',
    ];

    protected $casts = [
        'geometry' => 'array',
        'centroid' => 'array',
        'area_m2' => 'float',
        'price' => 'int',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
