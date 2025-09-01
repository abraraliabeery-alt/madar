<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityCategoryTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_category_id',
        'locale',
        'name',
        'description',
    ];

    // العلاقات
    public function facilityCategory()
    {
        return $this->belongsTo(FacilityCategory::class);
    }
}
