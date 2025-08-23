<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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
     * Get all translations for this city
     */
    public function translations()
    {
        return $this->hasMany(CityTranslation::class);
    }

    /**
     * Get translation for a specific locale
     */
    public function translation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Get localized name
     */
    public function getLocalizedNameAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->name : $this->name;
    }

    /**
     * Get localized description
     */
    public function getLocalizedDescriptionAttribute()
    {
        $translation = $this->translation();
        return $translation ? $translation->description : $this->description;
    }

    /**
     * Get name in English (for backward compatibility)
     */
    public function getNameEnAttribute()
    {
        $translation = $this->translation('en');
        return $translation ? $translation->name : null;
    }

    /**
     * Get description in English (for backward compatibility)
     */
    public function getDescriptionEnAttribute()
    {
        $translation = $this->translation('en');
        return $translation ? $translation->description : null;
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
