<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'address',
        'is_featured',
        'is_verified',
        'main_image',
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
        'status_id',
        'building_id',
        'project_id',
        'package_id',
        'bedrooms',
        'bathrooms',
        'area',
        'floor_number',
        'total_floors',
        'parking_spaces',
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
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'area' => 'float',
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

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
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

    public function saleOffers()
    {
        return $this->offers()->where('offer_type', 'sale');
    }

    public function rentOffers()
    {
        return $this->offers()->where('offer_type', 'like', 'rent_%');
    }

    public function getActiveSaleOffers()
    {
        return $this->saleOffers()->active()->valid()->get();
    }

    public function getActiveRentOffers()
    {
        return $this->rentOffers()->active()->valid()->get();
    }

    /**
     * Get the lowest price from active offers
     */
    public function getLowestPrice()
    {
        $lowestOffer = $this->activeOffers()->orderBy('price', 'asc')->first();
        return $lowestOffer ? $lowestOffer->price : null;
    }

    /**
     * Get the highest price from active offers
     */
    public function getHighestPrice()
    {
        $highestOffer = $this->activeOffers()->orderBy('price', 'desc')->first();
        return $highestOffer ? $highestOffer->price : null;
    }

    /**
     * Get price range from active offers
     */
    public function getPriceRange()
    {
        $offers = $this->activeOffers()->get();
        if ($offers->isEmpty()) {
            return null;
        }

        $minPrice = $offers->min('price');
        $maxPrice = $offers->max('price');

        if ($minPrice == $maxPrice) {
            return $minPrice;
        }

        return [
            'min' => $minPrice,
            'max' => $maxPrice
        ];
    }

    /**
     * Get the primary offer (sale offer or first active offer)
     */
    public function getPrimaryOffer()
    {
        // First try to get a sale offer
        $saleOffer = $this->saleOffers()->active()->valid()->first();
        if ($saleOffer) {
            return $saleOffer;
        }

        // If no sale offer, get the first active offer
        return $this->activeOffers()->first();
    }

    /**
     * Get formatted price display
     */
    public function getFormattedPrice()
    {
        $primaryOffer = $this->getPrimaryOffer();
        if (!$primaryOffer) {
            return null;
        }

        $price = number_format($primaryOffer->price);
        
        // Add period indicator for rent offers
        if ($primaryOffer->isForRent()) {
            $period = $this->getRentPeriodText($primaryOffer->offer_type);
            return "{$price} SAR / {$period}";
        }

        return "{$price} SAR";
    }

    /**
     * Get rent period text
     */
    private function getRentPeriodText($offerType)
    {
        switch ($offerType) {
            case 'rent_daily':
                return __('products.rent_periods.daily');
            case 'rent_monthly':
                return __('products.rent_periods.monthly');
            case 'rent_yearly':
                return __('products.rent_periods.yearly');
            default:
                return '';
        }
    }

    /**
     * Check if product has active offers
     */
    public function hasActiveOffers()
    {
        return $this->activeOffers()->exists();
    }

    /**
     * Scope to filter products that have active offers
     */
    public function scopeWithActiveOffers($query)
    {
        return $query->whereHas('offers', function($q) {
            $q->where('is_active', true)
              ->where(function($dateQuery) {
                  $dateQuery->whereNull('valid_from')
                           ->orWhere('valid_from', '<=', now()->toDateString());
              })
              ->where(function($dateQuery) {
                  $dateQuery->whereNull('valid_to')
                           ->orWhere('valid_to', '>=', now()->toDateString());
              });
        });
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



    // Accessors - Updated to use offers-based pricing
    public function getFormattedPriceAttribute()
    {
        return $this->getFormattedPrice();
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
