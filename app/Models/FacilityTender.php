<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityTender extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'title',
        'reference',
        'issue_date',
        'cover_style',
        'status',
        'data',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'data' => 'array',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
