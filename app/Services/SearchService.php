<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchService
{
    /**
     * Search products with filters
     *
     * @param Request $request
     * @return Builder
     */
    public function searchProducts(Request $request): Builder
    {
        $query = Product::where('is_active', true)
            ->where('is_verified', true)
            ->with(['facility', 'category', 'features', 'offers', 'attributes']);

        // Search by keyword
        if ($request->has('q') && $request->q) {
            $locale = app()->getLocale();
            $query->where(function($q) use ($request, $locale) {
                $q->whereHas('translations', function($translationQuery) use ($request, $locale) {
                    $translationQuery->where('locale', $locale)
                        ->where(function($tq) use ($request) {
                            $tq->where('title', 'like', "%{$request->q}%")
                               ->orWhere('description', 'like', "%{$request->q}%");
                        });
                })
                ->orWhere('address', 'like', "%{$request->q}%");
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by facility
        if ($request->has('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        // Filter by price range (using active offers)
        if ($request->has('min_price')) {
            $query->whereHas('activeOffers', function ($offerQuery) use ($request) {
                $offerQuery->where('price', '>=', $request->min_price);
            });
        }

        if ($request->has('max_price')) {
            $query->whereHas('activeOffers', function ($offerQuery) use ($request) {
                $offerQuery->where('price', '<=', $request->max_price);
            });
        }

        // Filter by rooms (bedrooms attribute)
        if ($request->has('rooms')) {
            $query->whereHas('attributes', function ($attributeQuery) use ($request) {
                $attributeQuery->where('key', 'bedrooms')
                    ->where('product_attribute_values.value', $request->rooms);
            });
        }

        // Filter by area range (area attribute)
        if ($request->has('min_area')) {
            $query->whereHas('attributes', function ($attributeQuery) use ($request) {
                $attributeQuery->where('key', 'area')
                    ->whereRaw('CAST(product_attribute_values.value AS DECIMAL(10,2)) >= ?', [$request->min_area]);
            });
        }

        if ($request->has('max_area')) {
            $query->whereHas('attributes', function ($attributeQuery) use ($request) {
                $attributeQuery->where('key', 'area')
                    ->whereRaw('CAST(product_attribute_values.value AS DECIMAL(10,2)) <= ?', [$request->max_area]);
            });
        }

        // Location search
        if ($request->has('latitude') && $request->has('longitude') && $request->has('radius')) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->radius;

            $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?
            ", [$lat, $lng, $lat, $radius]);
        }

        // Sort results
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'price') {
            $query->orderByRaw('(SELECT MIN(price) FROM offers WHERE offers.product_id = products.id) ' . $sortOrder);
        } elseif ($sortBy === 'area') {
            $query->orderByRaw('(SELECT CAST(value AS DECIMAL(10,2)) FROM product_attribute_values JOIN attributes ON attributes.id = product_attribute_values.attribute_id WHERE product_attribute_values.product_id = products.id AND attributes.key = "area" LIMIT 1) ' . $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query;
    }

    /**
     * Search facilities with filters
     *
     * @param Request $request
     * @return Builder
     */
    public function searchFacilities(Request $request): Builder
    {
        $query = Facility::where('is_active', true)
            ->where('is_verified', true)
            ->with(['category', 'products']);

        // Search by keyword
        if ($request->has('q') && $request->q) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%")
                  ->orWhere('address', 'like', "%{$request->q}%");
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by rating
        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Location search
        if ($request->has('latitude') && $request->has('longitude') && $request->has('radius')) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->radius;

            $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?
            ", [$lat, $lng, $lat, $radius]);
        }

        // Sort results
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query->orderBy($sortBy, $sortOrder);

        return $query;
    }
}
