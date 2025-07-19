<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
    ];

    // العلاقات
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_feature');
    }

    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'product_feature');
    }

    public function translations()
    {
        return $this->hasMany(FeatureTranslation::class);
    }
}
