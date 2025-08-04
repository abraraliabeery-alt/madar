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
        'logo',
        'header',
        'latitude',
        'longitude',
        'google_maps_url',
        'rating',
        'rating_count',
        'products_count',
        'category_id',
        'owner_user_id',
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
    ];

    // العلاقات
    public function category()
    {
        return $this->belongsTo(Category::class);
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
        // Since facilities don't have image_gallery field, we'll return an empty collection
        // or you can add image_gallery field to facilities table if needed
        return collect();
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
        return $query->where('category_id', $categoryId);
    }

    // Accessors
    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1) . '/5';
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-facility.jpg');
    }

    public function getHeaderUrlAttribute()
    {
        return $this->header ? asset('storage/' . $this->header) : asset('images/default-header.jpg');
    }
}
