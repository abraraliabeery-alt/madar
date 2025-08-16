<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'order',
        'is_active',
        'locale'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Scope for active FAQs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Scope for locale
     */
    public function scopeLocale($query, $locale = null)
    {
        if ($locale) {
            return $query->where('locale', $locale);
        }
        return $query;
    }

    /**
     * Get all active FAQs ordered by order
     */
    public static function getActiveFaqs($locale = null)
    {
        return static::active()
            ->locale($locale)
            ->ordered()
            ->get();
    }
}
