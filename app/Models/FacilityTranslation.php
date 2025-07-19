<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'facility_id',
        'info',
        'locale',
    ];

    // العلاقات
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
