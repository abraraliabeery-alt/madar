<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'type',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size_bytes',
        'status',
        'verified_at',
        'verified_by_user_id',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
