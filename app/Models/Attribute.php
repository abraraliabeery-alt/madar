<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'required',
        'category_id',
        'icon',
        'Symbol',
    ];

    protected $casts = [
        'required' => 'boolean',
    ];

    // العلاقات
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute_values');
    }

    public function translations()
    {
        return $this->hasMany(AttributeTranslation::class);
    }

    /**
     * Get the products count for this attribute
     */
    public function getProductsCountAttribute()
    {
        return $this->products()->count();
    }
}
