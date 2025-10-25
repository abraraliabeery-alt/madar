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
        'show_in_card',
    ];

    protected $casts = [
        'required' => 'boolean',
        'show_in_card' => 'boolean',
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

    /**
     * Scope to get attributes that should be shown in product cards
     */
    public function scopeShowInCard($query)
    {
        return $query->where('show_in_card', true);
    }

    /**
     * Scope to get attributes for a specific category that should be shown in cards
     */
    public function scopeShowInCardForCategory($query, $categoryId)
    {
        return $query->where('show_in_card', true)
                    ->where(function($q) use ($categoryId) {
                        $q->where('category_id', $categoryId)
                          ->orWhereNull('category_id');
                    });
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
     * Get symbol for specific locale
     */
    public function getTranslatedSymbol($locale = null)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->symbol : '';
    }
}
