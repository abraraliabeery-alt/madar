<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'contract_id',
        'owner_id',
        'feature_id',
        'Number_of_floors',
        'Number_of_Apartments',
        'Office_ratio',
        'image',
        'video',
        'image_gallery',
        'latitude',
        'longitude',
        'google_maps_url',
        'facility_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'Number_of_floors' => 'integer',
        'Number_of_Apartments' => 'integer',
        'Office_ratio' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'image_gallery' => 'array',
    ];

    // العلاقات
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'product_feature');
    }

    public function translations()
    {
        return $this->hasMany(BuildingTranslation::class);
    }
}
