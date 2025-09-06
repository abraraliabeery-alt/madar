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
        'owner_user_id',
        'seller_user_id',
        'category_id',
        'city_id',
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

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute_values')
            ->withPivot('value')
            ->withTimestamps();
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

    public function favoredByUsers()
    {
        return $this->morphToMany(User::class, 'favoritable', 'favorites', 'favoritable_id', 'user_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function activeOffers()
    {
        return $this->offers()->active()->valid();
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

    /**
     * Get translation for specific locale
     */
    public function getTranslation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Get title for specific locale
     */
    public function getTranslatedTitle($locale = null)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->title : '';
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
     * Get title attribute (for backward compatibility)
     */
    public function getTitleAttribute()
    {
        return $this->getTranslatedTitle();
    }

    /**
     * Get description attribute (for backward compatibility)
     */
    public function getDescriptionAttribute()
    {
        return $this->getTranslatedDescription();
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



    // Accessors
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' ' . \App\Helpers\LanguageHelper::getSaudiRiyalSymbol();
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

    /**
     * Get attributes that should be displayed in product cards
     */
    public function getCardAttributesAttribute()
    {
        return $this->attributes()
            ->with('translations')
            ->where('show_in_card', true)
            ->where(function($query) {
                $query->where('category_id', $this->category_id)
                      ->orWhereNull('category_id');
            })
            ->get();
    }
}
