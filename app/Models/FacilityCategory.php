<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'icon',
        'image',
        'is_active',
        'is_featured',
        'order',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
        'sort_order' => 'integer',
    ];

    // العلاقات
    public function facilities()
    {
        return $this->hasMany(Facility::class, 'facility_category_id');
    }

    public function parent()
    {
        return $this->belongsTo(FacilityCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(FacilityCategory::class, 'parent_id');
    }

    public function translations()
    {
        return $this->hasMany(FacilityCategoryTranslation::class);
    }

    /**
     * Get translation for specific locale
     */
    public function getTranslation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Get name for specific locale
     */
    public function getTranslatedName($locale = null)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->name : '';
    }

    /**
     * Get description for specific locale
     */
    public function getTranslatedDescription($locale = null)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->description : '';
    }

    /**
     * Get the active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the featured categories
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get categories ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('order', 'asc');
    }
}
