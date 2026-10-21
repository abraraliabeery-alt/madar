<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_id',
        'product_id',
        'land_id',
    ];

    // العلاقات
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

}
