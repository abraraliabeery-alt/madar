<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'image',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    // العلاقات
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    /**
     * Get the active cities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the featured cities
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get cities ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Get products count for this city
     */
    public function getProductsCountAttribute()
    {
        return $this->products()->where('is_active', true)->count();
    }

    /**
     * Get facilities count for this city
     */
    public function getFacilitiesCountAttribute()
    {
        return $this->facilities()->where('is_active', true)->count();
    }
}
