<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'locale',
        'name',
        'notes',
        'rules',
    ];

    // العلاقات
    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
