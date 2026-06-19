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
use App\Models\Plan;
use App\Models\PlanLot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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

    public function ajlanLotShow(Request $request, PlanLot $lot)
    {
        $plan = Plan::query()->where('slug', 'ajlan')->first();
        if (!$plan || (int) $lot->plan_id !== (int) $plan->id) {
            abort(404);
        }

        $centroid = null;
        try {
            $ring = is_array($lot->geometry) ? ($lot->geometry['coordinates'][0] ?? null) : null;
            if (is_array($ring) && count($ring) > 0) {
                $sumLng = 0.0;
                $sumLat = 0.0;
                $n = 0;
                foreach ($ring as $pt) {
                    if (is_array($pt) && count($pt) >= 2) {
                        $sumLng += (float) $pt[0];
                        $sumLat += (float) $pt[1];
                        $n++;
                    }
                }
                if ($n > 0) {
                    $centroid = ['lat' => $sumLat / $n, 'lng' => $sumLng / $n];
                }
            }
        } catch (\Throwable $e) {
            $centroid = null;
        }

        return view('public.plans.lot_show', [
            'plan' => $plan,
            'lot' => $lot,
            'centroid' => $centroid,
            'whatsappNumber' => $request->string('whatsapp')->toString(),
        ]);
    }
 
    /**
     * البحث في المنتجات
     */
    public function products(Request $request)
    {
        $query = Product::with(['facility', 'category', 'statuses', 'features', 'offers'])
            ->where('is_active', true)
            ->withActiveOffers();

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
        // المنتجات تستخدم category_id. نحافظ على التوافق: إذا تم إرسال facility_category_id نستخدمه أيضًا.
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        } elseif ($request->filled('facility_category_id')) {
            $query->where('facility_category_id', $request->facility_category_id);
        }

        // فلترة حسب العنوان/الحي (موجود في صفحة البحث المتقدم)
        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
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

    public function ajlanPlan(Request $request)
    {
        $fallbackCenterLat = 24.550964276;
        $fallbackCenterLng = 46.824846268;
        $fallbackPlanNumber = '2705/5';
        $fallbackPlanAreaKm2 = 3.88;

        $plan = Plan::query()->where('slug', 'ajlan')->with('lots')->first();

        if (!$plan || $plan->lots->count() === 0) {
            try {
                $viewPath = resource_path('views/public/plans/plans.blade.php');
                if (File::exists($viewPath)) {
                    $content = File::get($viewPath);
                    $marker = 'const parcelsWGS = ';
                    $start = strpos($content, $marker);
                    if ($start !== false) {
                        $start += strlen($marker);
                        $end = strpos($content, ';', $start);
                        if ($end !== false) {
                            $json = trim(substr($content, $start, $end - $start));
                            $payload = json_decode($json, true);
                            if (is_array($payload) && ($payload['type'] ?? null) === 'FeatureCollection' && is_array($payload['features'] ?? null)) {
                                $plan = Plan::query()->firstOrCreate(
                                    ['slug' => 'ajlan'],
                                    [
                                        'name' => 'عجلان',
                                        'plan_number' => $fallbackPlanNumber,
                                        'center_lat' => $fallbackCenterLat,
                                        'center_lng' => $fallbackCenterLng,
                                        'area_km2' => $fallbackPlanAreaKm2,
                                    ]
                                );

                                foreach ($payload['features'] as $feat) {
                                    if (!is_array($feat)) continue;
                                    $props = is_array($feat['properties'] ?? null) ? $feat['properties'] : [];
                                    $geom = is_array($feat['geometry'] ?? null) ? $feat['geometry'] : null;
                                    if (!$geom) continue;

                                    $lotNumber = (string) ($props['lot_number'] ?? $props['parcel_no'] ?? $props['id'] ?? '');
                                    if ($lotNumber === '') continue;

                                    $area = $props['area_m2'] ?? $props['area'] ?? null;

                                    PlanLot::query()->updateOrCreate(
                                        ['plan_id' => $plan->id, 'lot_number' => $lotNumber],
                                        [
                                            'usage' => $props['usage'] ?? null,
                                            'status' => $props['status'] ?? 'available',
                                            'area_m2' => is_numeric($area) ? (float) $area : null,
                                            'price' => isset($props['price']) && is_numeric($props['price']) ? (int) $props['price'] : null,
                                            'geometry' => $geom,
                                        ]
                                    );
                                }

                                $plan = Plan::query()->where('slug', 'ajlan')->with('lots')->first();
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        $centerLat = $plan?->center_lat ?? $fallbackCenterLat;
        $centerLng = $plan?->center_lng ?? $fallbackCenterLng;
        $planNumber = $plan?->plan_number ?? $fallbackPlanNumber;
        $planAreaKm2 = $plan?->area_km2 ?? $fallbackPlanAreaKm2;

        $planAreaM2 = $planAreaKm2 * 1000 * 1000;
        $planShadeRadiusMeters = sqrt($planAreaM2 / pi());

        $geoJson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        if ($plan && $plan->lots->count()) {
            $geoJson['features'] = $plan->lots->map(function ($lot) {
                return [
                    'type' => 'Feature',
                    'properties' => [
                        'db_id' => $lot->id,
                        'lot_number' => (string) $lot->lot_number,
                        'area' => $lot->area_m2,
                        'usage' => $lot->usage,
                        'status' => $lot->status,
                        'price' => $lot->price,
                    ],
                    'geometry' => $lot->geometry,
                ];
            })->values()->all();
        } else {
            // بيانات GeoJSON مؤقتة للتجربة فقط ويجب استبدالها لاحقًا ببيانات قاعدة البيانات.
            $geoJson['features'] = [
                [
                    'type' => 'Feature',
                    'properties' => [
                        'lot_number' => '101',
                        'area' => 540,
                        'usage' => 'سكني',
                        'status' => 'available',
                        'price' => 650000,
                    ],
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [[
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat + 0.00040],
                            [$fallbackCenterLng + 0.00170, $fallbackCenterLat + 0.00040],
                            [$fallbackCenterLng + 0.00170, $fallbackCenterLat + 0.00010],
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat + 0.00010],
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat + 0.00040],
                        ]],
                    ],
                ],
                [
                    'type' => 'Feature',
                    'properties' => [
                        'lot_number' => '102',
                        'area' => 600,
                        'usage' => 'تجاري',
                        'status' => 'reserved',
                        'price' => 880000,
                    ],
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [[
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat + 0.00005],
                            [$fallbackCenterLng + 0.00170, $fallbackCenterLat + 0.00005],
                            [$fallbackCenterLng + 0.00170, $fallbackCenterLat - 0.00025],
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat - 0.00025],
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat + 0.00005],
                        ]],
                    ],
                ],
                [
                    'type' => 'Feature',
                    'properties' => [
                        'lot_number' => '103',
                        'area' => 510,
                        'usage' => 'سكني',
                        'status' => 'sold',
                        'price' => 610000,
                    ],
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [[
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat + 0.00040],
                            [$fallbackCenterLng + 0.00225, $fallbackCenterLat + 0.00040],
                            [$fallbackCenterLng + 0.00225, $fallbackCenterLat + 0.00010],
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat + 0.00010],
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat + 0.00040],
                        ]],
                    ],
                ],
                [
                    'type' => 'Feature',
                    'properties' => [
                        'lot_number' => '104',
                        'area' => 720,
                        'usage' => 'خدمات',
                        'status' => 'available',
                        'price' => 990000,
                    ],
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [[
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat + 0.00005],
                            [$fallbackCenterLng + 0.00225, $fallbackCenterLat + 0.00005],
                            [$fallbackCenterLng + 0.00225, $fallbackCenterLat - 0.00025],
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat - 0.00025],
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat + 0.00005],
                        ]],
                    ],
                ],
            ];
        }

        return view('public.plans.ajlan', [
            'centerLat' => $centerLat,
            'centerLng' => $centerLng,
            'planNumber' => $planNumber,
            'planAreaKm2' => $planAreaKm2,
            'planShadeRadiusMeters' => $planShadeRadiusMeters,
            'geoJson' => $geoJson,
            'whatsappNumber' => $request->string('whatsapp')->toString(),
        ]);
    }

    public function ajlanOsmRoads(Request $request)
    {
        $south = 24.543627000069844;
        $west = 46.81368100000787;
        $north = 24.56727800007028;
        $east = 46.84426200000871;

        $query = "[out:json][timeout:25];(way[\"highway\"]({$south},{$west},{$north},{$east}););out geom;";

        try {
            $resp = Http::timeout(30)->asForm()->post('https://overpass-api.de/api/interpreter', [
                'data' => $query,
            ]);

            if (!$resp->ok()) {
                return response()->json([
                    'ok' => false,
                    'status' => $resp->status(),
                ], 502);
            }

            return response($resp->body(), 200)
                ->header('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            Log::warning('Overpass proxy failed', ['error' => $e->getMessage()]);
            return response()->json(['ok' => false], 502);
        }
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
                        'url' => route('public.facilities.show', $facility->id),
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
                        'url' => route('public.products.show', $product->id),
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
            ->with(['facility', 'statuses', 'offers'])
            ->where('is_active', true)
            ->withActiveOffers();

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

        $query = Product::with(['facility', 'category', 'offers'])
            ->where('is_active', true)
            ->where('address', 'like', '%' . $area . '%')
            ->withActiveOffers();

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
