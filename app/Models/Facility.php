<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'license_number',
        'license_expiry',
        'is_active',
        'is_primary',
        'is_verified',
        'is_featured',
        'logo_path',
        'header',
        'latitude',
        'longitude',
        'google_maps_url',
        'rating',
        'rating_count',
        'products_count',
        'facility_category_id',
        'city_id',
        'owner_user_id',
        // Customization fields
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'text_color',
        'secondary_text_color',
        'font_family',
        'hero_background_type',
        'hero_background_value',
        'hero_overlay_opacity',
        'layout_style',
        'button_style',
        'logo_position',
        'enable_animations',
        'enable_parallax',
        'custom_css',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'meta_keywords',
        'meta_description',
        'customization_settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'rating' => 'float',
        'license_expiry' => 'date',
        // Customization casts
        'enable_animations' => 'boolean',
        'enable_parallax' => 'boolean',
        'customization_settings' => 'array',
    ];

    // العلاقات
    public function facilityCategory()
    {
        return $this->belongsTo(FacilityCategory::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'facility_user');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Product::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function tasks()
    {
        // Tasks are not directly related to facilities, so return empty query builder
        // This prevents errors while maintaining the interface
        return \App\Models\Task::whereRaw('1 = 0'); // Returns empty query builder
    }

    public function translations()
    {
        return $this->hasMany(FacilityTranslation::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function gallery()
    {
        // Return an empty collection since facilities don't have image_gallery field
        // This prevents eager loading issues
        return collect();
    }

    public function favoredByUsers()
    {
        return $this->morphToMany(User::class, 'favoritable', 'favorites', 'favoritable_id', 'user_id');
    }

    // Polymorphic relationship for statuses
    public function statuses()
    {
        return $this->morphToMany(Status::class, 'statusable', 'statusables');
    }

    // Accessor to get the current status
    public function getStatusAttribute()
    {
        return $this->statuses()->latest()->first();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('facility_category_id', $categoryId);
    }

    // Accessors
    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1) . '/5';
    }

    public function getLogoUrlAttribute()
    {
        $logoPath = $this->getRawOriginal('logo_path');
        return $logoPath ? asset('storage/' . $logoPath) : asset('images/default-facility.jpg');
    }

    public function getLogoPathAttribute()
    {
        return $this->getRawOriginal('logo_path');
    }

    public function getHeaderUrlAttribute()
    {
        return $this->header ? asset('storage/' . $this->header) : asset('images/default-header.jpg');
    }

    // Customization Helper Methods
    public function getCustomizationAttribute()
    {
        return [
            'colors' => [
                'primary' => $this->primary_color ?? '#2563eb',
                'secondary' => $this->secondary_color ?? '#1e40af',
                'accent' => $this->accent_color ?? '#f59e0b',
                'background' => $this->background_color ?? '#ffffff',
                'text' => $this->text_color ?? '#374151',
                'secondary_text' => $this->secondary_text_color ?? '#6b7280',
            ],
            'typography' => [
                'font_family' => $this->font_family ?? 'figtree',
            ],
            'hero' => [
                'background_type' => $this->hero_background_type ?? 'gradient',
                'background_value' => $this->hero_background_value,
                'overlay_opacity' => $this->hero_overlay_opacity ?? '20',
            ],
            'layout' => [
                'style' => $this->layout_style ?? 'modern',
                'button_style' => $this->button_style ?? 'rounded',
                'logo_position' => $this->logo_position ?? 'left',
            ],
            'effects' => [
                'animations' => $this->enable_animations ?? true,
                'parallax' => $this->enable_parallax ?? true,
            ],
            'social' => [
                'facebook' => $this->facebook_url,
                'twitter' => $this->twitter_url,
                'instagram' => $this->instagram_url,
                'linkedin' => $this->linkedin_url,
            ],
        ];
    }

    public function getCssVariablesAttribute()
    {
        $customization = $this->customization;
        
        return [
            '--primary-color' => $customization['colors']['primary'],
            '--secondary-color' => $customization['colors']['secondary'],
            '--accent-color' => $customization['colors']['accent'],
            '--background-color' => $customization['colors']['background'],
            '--text-color' => $customization['colors']['text'],
            '--secondary-text-color' => $customization['colors']['secondary_text'],
            '--font-family' => $this->getFontFamilyValue($customization['typography']['font_family']),
        ];
    }

    public function getHeroBackgroundStyleAttribute()
    {
        $hero = $this->customization['hero'];
        
        switch ($hero['background_type']) {
            case 'color':
                return "background-color: {$hero['background_value']};";
            case 'image':
                return "background-image: url('{$hero['background_value']}'); background-size: cover; background-position: center;";
            case 'gradient':
            default:
                $primary = $this->primary_color ?? '#2563eb';
                $secondary = $this->secondary_color ?? '#1e40af';
                return "background: linear-gradient(135deg, {$secondary}, {$primary});";
        }
    }

    public function getFontFamilyValue($fontFamily)
    {
        $fontMap = [
            'figtree' => "'Figtree', sans-serif",
            'inter' => "'Inter', sans-serif",
            'poppins' => "'Poppins', sans-serif", 
            'roboto' => "'Roboto', sans-serif",
            'open-sans' => "'Open Sans', sans-serif",
            'lato' => "'Lato', sans-serif",
        ];
        
        return $fontMap[$fontFamily] ?? $fontMap['figtree'];
    }

    public function hasCustomization()
    {
        return !empty($this->primary_color) || 
               !empty($this->secondary_color) ||
               !empty($this->custom_css) ||
               !empty($this->hero_background_value);
    }

    public function resetCustomization()
    {
        $this->update([
            'primary_color' => '#2563eb',
            'secondary_color' => '#1e40af',
            'accent_color' => '#f59e0b',
            'background_color' => '#ffffff',
            'text_color' => '#374151',
            'secondary_text_color' => '#6b7280',
            'font_family' => 'figtree',
            'hero_background_type' => 'gradient',
            'hero_background_value' => null,
            'hero_overlay_opacity' => '20',
            'layout_style' => 'modern',
            'button_style' => 'rounded',
            'logo_position' => 'left',
            'enable_animations' => true,
            'enable_parallax' => true,
            'custom_css' => null,
        ]);
    }
}
