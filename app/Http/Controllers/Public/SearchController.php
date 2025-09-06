<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Facility;
use App\Models\Category;
use App\Models\Status;
use App\Models\Feature;
use App\Models\Attribute;

class SearchController extends Controller
{
    /**
     * صفحة البحث الرئيسية
     */
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();
        $features = Feature::where('is_active', true)->get();
        $statuses = Status::where('is_active', true)->get();
        
        return view('public.search.index', compact('categories', 'features', 'statuses'));
    }
 
    /**
     * البحث في المنتجات
     */
    public function products(Request $request)
    {
        $query = Product::with(['facility', 'category', 'statuses', 'features'])
            ->where('is_active', true);

        // البحث النصي
        if ($request->has('q') && $request->q) {
            $searchTerm = $request->q;
            $locale = app()->getLocale();
            $query->where(function ($q) use ($searchTerm, $locale) {
                $q->whereHas('translations', function($translationQuery) use ($searchTerm, $locale) {
                    $translationQuery->where('locale', $locale)
                        ->where(function($tq) use ($searchTerm) {
                            $tq->where('title', 'like', '%' . $searchTerm . '%')
                               ->orWhere('description', 'like', '%' . $searchTerm . '%');
                        });
                })
                ->orWhere('address', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('facility', function ($facilityQuery) use ($searchTerm) {
                    $facilityQuery->where('name', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // فلترة حسب الفئة
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // فلترة حسب السعر
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // فلترة حسب عدد الغرف
        if ($request->has('bedrooms') && $request->bedrooms) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        // فلترة حسب عدد الحمامات
        if ($request->has('bathrooms') && $request->bathrooms) {
            $query->where('bathrooms', '>=', $request->bathrooms);
        }

        // فلترة حسب المساحة
        if ($request->has('min_area') && $request->min_area) {
            $query->where('area', '>=', $request->min_area);
        }
        if ($request->has('max_area') && $request->max_area) {
            $query->where('area', '<=', $request->max_area);
        }

        // فلترة حسب نوع العقار
        if ($request->has('property_type')) {
            switch ($request->property_type) {
                case 'sale':
                    $query->where('available_for_sale', true);
                    break;
                case 'rent':
                    $query->where('available_for_rent', true);
                    break;
            }
        }

        // فلترة حسب المميزات
        if ($request->has('features') && is_array($request->features)) {
            $query->whereHas('features', function ($featureQuery) use ($request) {
                $featureQuery->whereIn('features.id', $request->features);
            });
        }

        // فلترة حسب الموقع
        if ($request->has('latitude') && $request->has('longitude') && $request->has('radius')) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->radius; // بالكيلومترات

            $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?
            ", [$lat, $lng, $lat, $radius]);
        }

        // الترتيب
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'area_low':
                $query->orderBy('area', 'asc');
                break;
            case 'area_high':
                $query->orderBy('area', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::all();
        $features = Feature::all();

        return view('public.search.products', compact('products', 'categories', 'features'));
    }

    /**
     * البحث في المنشآت
     */
    public function facilities(Request $request)
    {
        $query = Facility::with(['facilityCategory', 'owner'])
            ->where('is_active', true)
            ->where('is_verified', true);

        // البحث النصي
        if ($request->has('q') && $request->q) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address', 'like', '%' . $searchTerm . '%');
            });
        }

        // فلترة حسب الفئة
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // فلترة حسب الحالة
        if ($request->has('status_id') && $request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        // فلترة حسب الموقع
        if ($request->has('latitude') && $request->has('longitude') && $request->has('radius')) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->radius;

            $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?
            ", [$lat, $lng, $lat, $radius]);
        }

        // فلترة حسب التحقق
        if ($request->has('verified')) {
            $query->where('is_verified', $request->verified);
        }

        // فلترة حسب المميزات
        if ($request->has('featured')) {
            $query->where('is_featured', $request->featured);
        }

        // الترتيب
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $facilities = $query->paginate(12);
        $categories = Category::all();
        $statuses = Status::all();

        return view('public.search.facilities', compact('facilities', 'categories', 'statuses'));
    }

    /**
     * البحث المتقدم
     */
    public function advanced(Request $request)
    {
        $categories = Category::all();
        $features = Feature::all();
        $attributes = Attribute::all();
        $statuses = Status::all();

        return view('public.search.advanced', compact('categories', 'features', 'attributes', 'statuses'));
    }

    /**
     * البحث بالخريطة
     */
    public function map(Request $request)
    {
        $searchType = $request->get('search_type', 'products');
        
        if ($searchType === 'facilities') {
            $query = Facility::with(['facilityCategory'])
                ->where('is_active', true)
                ->where('is_verified', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            // فلترة حسب الفئة
            if ($request->has('category_id') && $request->category_id) {
                $query->where('facility_category_id', $request->category_id);
            }

            $facilities = $query->get();

            $mapData = $facilities->map(function ($facility) {
                return [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'price' => null,
                    'address' => $facility->address,
                    'latitude' => $facility->latitude,
                    'longitude' => $facility->longitude,
                    'category' => $facility->facilityCategory->name ?? 'No Category',
                    'facility' => $facility->name,
                    'image' => $facility->logo,
                    'url' => route('public.facilities.show', $facility->id),
                    'type' => 'facility'
                ];
            });
        } else {
            $query = Product::with(['facility', 'category', 'translations'])
                ->where('is_active', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            // فلترة حسب الفئة
            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            // فلترة حسب السعر
            if ($request->has('min_price') && $request->min_price) {
                $query->where('price', '>=', $request->min_price);
            }
            if ($request->has('max_price') && $request->max_price) {
                $query->where('price', '<=', $request->max_price);
            }

            $products = $query->get();

            $mapData = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->translations->where('locale', app()->getLocale())->first()->title ?? $product->translations->first()->title ?? 'No Title',
                    'price' => $product->price,
                    'address' => $product->address,
                    'latitude' => $product->latitude,
                    'longitude' => $product->longitude,
                    'category' => $product->category->name ?? 'No Category',
                    'facility' => $product->facility->name ?? 'No Facility',
                    'image' => $product->image,
                    'url' => route('public.products.show', $product->id),
                    'type' => 'product'
                ];
            });
        }

        $categories = Category::all();

        return view('public.search.map', compact('mapData', 'categories'));
    }

    /**
     * البحث السريع (AJAX)
     */
    public function quickSearch(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'products');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        if ($type === 'facilities') {
            $results = Facility::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                      ->orWhere('address', 'like', '%' . $query . '%');
                })
                ->take(5)
                ->get(['id', 'name', 'address', 'logo'])
                ->map(function ($facility) {
                    return [
                        'id' => $facility->id,
                        'name' => $facility->name,
                        'address' => $facility->address,
                        'image' => $facility->logo,
                        'url' => route('facilities.show', $facility->id),
                        'type' => 'facility'
                    ];
                });
        } else {
            $locale = app()->getLocale();
            $results = Product::with(['translations'])
                ->where('is_active', true)
                ->where(function ($q) use ($query, $locale) {
                    $q->whereHas('translations', function($translationQuery) use ($query, $locale) {
                        $translationQuery->where('locale', $locale)
                            ->where('title', 'like', '%' . $query . '%');
                    })
                    ->orWhere('address', 'like', '%' . $query . '%');
                })
                ->take(5)
                ->get(['id', 'address', 'price', 'image'])
                ->map(function ($product) use ($locale) {
                    $title = $product->translations->where('locale', $locale)->first()->title ?? $product->translations->first()->title ?? 'No Title';
                    return [
                        'id' => $product->id,
                        'name' => $title,
                        'address' => $product->address,
                        'price' => $product->price,
                        'image' => $product->image,
                        'url' => route('products.show', $product->id),
                        'type' => 'product'
                    ];
                });
        }

        return response()->json($results);
    }

    /**
     * البحث في الفئات
     */
    public function searchByCategory(Category $category, Request $request)
    {
        $query = $category->products()
            ->with(['facility', 'statuses'])
            ->where('is_active', true);

        // تطبيق الفلاتر الإضافية
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->paginate(12);

        return view('public.search.category', compact('category', 'products'));
    }

    /**
     * البحث في المنطقة
     */
    public function searchByArea(Request $request)
    {
        $area = $request->get('area', '');

        $query = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->where('address', 'like', '%' . $area . '%');

        $products = $query->paginate(12);

        return view('public.search.area', compact('area', 'products'));
    }

    public function search(Request $request)
    {
        $query = Product::with(['facility', 'category', 'features'])
            ->where('is_active', true)
            ->where('is_verified', true);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by facility
        if ($request->has('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by property type
        if ($request->has('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        // Filter by rooms
        if ($request->has('rooms')) {
            $query->where('rooms', $request->rooms);
        }

        // Filter by area range
        if ($request->has('min_area')) {
            $query->where('area', '>=', $request->min_area);
        }

        if ($request->has('max_area')) {
            $query->where('area', '<=', $request->max_area);
        }

        // Search by keyword
        if ($request->has('q') && $request->q) {
            $locale = app()->getLocale();
            $query->where(function($q) use ($request, $locale) {
                $q->whereHas('translations', function($translationQuery) use ($request, $locale) {
                    $translationQuery->where('locale', $locale)
                        ->where(function($tq) use ($request) {
                            $tq->where('title', 'like', '%' . $request->q . '%')
                               ->orWhere('description', 'like', '%' . $request->q . '%');
                        });
                })
                ->orWhere('address', 'like', '%' . $request->q . '%');
            });
        }

        // Sort results
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);

        $categories = Category::where('is_active', true)->get();
        $facilities = Facility::where('is_active', true)->where('is_verified', true)->get();
        $features = Feature::where('is_active', true)->get();

        return view('public.search.results', compact('products', 'categories', 'facilities', 'features'));
    }
}
