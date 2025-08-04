<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'address',
        'rooms',
        'bathrooms',
        'area',
        'floor',
        'floors_count',
        'parking_spaces',
        'is_active',
        'is_featured',
        'is_verified',
        'price',
        'image',
        'video',
        'image_gallery',
        'latitude',
        'longitude',
        'google_maps_url',
        'facility_id',
        'property_type',
        'owner_user_id',
        'seller_user_id',
        'category_id',
        'views_count',
        'rating',
        'rating_count',
        'booking_number',
        'available_from',
        'available_to',
        'contact_phone',
        'contact_email',
        'additional_info',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'price' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'image_gallery' => 'array',
        'area' => 'float',
        'rating' => 'float',
        'available_from' => 'date',
        'available_to' => 'date',
    ];

    // العلاقات
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute_values');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'product_feature');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Gallery accessor - since image_gallery is a JSON field, not a relationship
    public function getGalleryAttribute()
    {
        return collect($this->image_gallery ?? []);
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

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    public function scopeByAreaRange($query, $minArea, $maxArea)
    {
        return $query->whereBetween('area', [$minArea, $maxArea]);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' ريال';
    }

    public function getFormattedAreaAttribute()
    {
        return number_format($this->area, 2) . ' متر مربع';
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-product.jpg');
    }

    // Mutators
    public function setBookingNumberAttribute($value)
    {
        if (!$value) {
            $this->attributes['booking_number'] = 'BK' . strtoupper(uniqid());
        } else {
            $this->attributes['booking_number'] = $value;
        }
    }
}
