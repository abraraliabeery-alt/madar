<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'content',
        'content_en',
        'type',
        'url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the active pages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get pages by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get pages ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('title', 'asc');
    }

    /**
     * Get the localized title
     */
    public function getLocalizedTitleAttribute()
    {
        return app()->getLocale() === 'en' && $this->title_en ? $this->title_en : $this->title;
    }

    /**
     * Get the localized content
     */
    public function getLocalizedContentAttribute()
    {
        return app()->getLocale() === 'en' && $this->content_en ? $this->content_en : $this->content;
    }
}
