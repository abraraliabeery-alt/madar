<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'plan_number',
        'center_lat',
        'center_lng',
        'area_km2',
        'boundary_geojson_path',
        'overlay_image_url',
        'bounds',
    ];

    protected $casts = [
        'bounds' => 'array',
        'center_lat' => 'float',
        'center_lng' => 'float',
        'area_km2' => 'float',
    ];

    public function lots(): HasMany
    {
        return $this->hasMany(PlanLot::class, 'plan_id');
    }
}
