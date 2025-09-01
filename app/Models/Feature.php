<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
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
     * Get the active features
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get features ordered by order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
