<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Feature;
use App\Models\City;
use App\Models\Attribute;
use App\Services\PdfSettingsService;

class ProductController extends Controller
{
    /**
     * عرض قائمة المنتجات
     */
    public function index(Request $request)
    {
        $query = Product::with(['facility', 'category', 'offers'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->withActiveOffers();

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by facility
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city_id', $request->city);
        }

        // Filter by price range (active offers)
        if ($request->filled('min_price')) {
            $query->whereHas('activeOffers', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('activeOffers', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Search by keyword
        if ($request->filled('q')) {
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

        // Filter by features
        if ($request->filled('features')) {
            foreach ($request->input('features', []) as $featureId) {
                $query->whereHas('features', function ($q) use ($featureId) {
                    $q->where('features.id', $featureId);
                });
            }
        }

        // Attribute filters: text/select/textarea/date/time/datetime
        $attributeTextFilters = $request->input('attr', []);
        if (is_array($attributeTextFilters)) {
            foreach ($attributeTextFilters as $attributeId => $value) {
                if ($value === null || $value === '') {
                    continue;
                }
                $query->whereHas('attributes', function ($q) use ($attributeId, $value) {
                    $q->where('attributes.id', $attributeId)
                      ->where('product_attribute_values.value', 'like', "%{$value}%");
                });
            }
        }

        // Attribute filters: numeric range
        $attributeMinFilters = $request->input('attr_min', []);
        $attributeMaxFilters = $request->input('attr_max', []);
        if (is_array($attributeMinFilters) || is_array($attributeMaxFilters)) {
            $allNumericAttributeIds = array_unique(array_merge(
                array_keys(is_array($attributeMinFilters) ? $attributeMinFilters : []),
                array_keys(is_array($attributeMaxFilters) ? $attributeMaxFilters : [])
            ));
            foreach ($allNumericAttributeIds as $attributeId) {
                $min = $attributeMinFilters[$attributeId] ?? null;
                $max = $attributeMaxFilters[$attributeId] ?? null;
                if (($min === null || $min === '') && ($max === null || $max === '')) {
                    continue;
                }
                $query->whereHas('attributes', function ($q) use ($attributeId, $min, $max) {
                    $q->where('attributes.id', $attributeId);
                    if ($min !== null && $min !== '') {
                        $q->where('product_attribute_values.value', '>=', $min);
                    }
                    if ($max !== null && $max !== '') {
                        $q->where('product_attribute_values.value', '<=', $max);
                    }
                });
            }
        }

        // Attribute filters: boolean
        $attributeBoolFilters = $request->input('attr_bool', []);
        if (is_array($attributeBoolFilters)) {
            foreach ($attributeBoolFilters as $attributeId => $value) {
                if ($value === null || $value === '') {
                    continue;
                }
                $query->whereHas('attributes', function ($q) use ($attributeId, $value) {
                    $q->where('attributes.id', $attributeId)
                      ->where('product_attribute_values.value', (string) $value);
                });
            }
        }

        // Attribute filters: select (exact match)
        $attributeSelectFilters = $request->input('attr_select', []);
        if (is_array($attributeSelectFilters)) {
            foreach ($attributeSelectFilters as $attributeId => $value) {
                if ($value === null || $value === '') {
                    continue;
                }
                $query->whereHas('attributes', function ($q) use ($attributeId, $value) {
                    $q->where('attributes.id', $attributeId)
                      ->where('product_attribute_values.value', (string) $value);
                });
            }
        }

        // Sort results
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);

        $categories = Category::where('is_active', true)->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        $facilities = Facility::where('is_active', true)->where('is_verified', true)->get();

        return view('public.products.index', compact('products', 'categories', 'cities', 'facilities'));
    }

    /**
     * عرض منتج محدد
     */
    public function show(Product $product)
    {
        if (!$product->is_active || !$product->is_verified) {
            abort(404);
        }

        $product->load(['facility', 'category', 'statuses', 'features', 'attributes.translations', 'activeOffers']);

        // هل المنتج في المفضلة للمستخدم الحالي؟
        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = auth()->user()->favoriteProducts()->where('products.id', $product->id)->exists();
        }

        // المنتجات المشابهة
        $similarProducts = Product::with(['facility', 'category', 'offers'])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->withActiveOffers()
            ->take(6)
            ->get();

        // منتجات نفس المنشأة
        $facilityProducts = Product::with(['category', 'offers'])
            ->where('id', '!=', $product->id)
            ->where('facility_id', $product->facility_id)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->withActiveOffers()
            ->take(4)
            ->get();

        $pdfSettings = app(PdfSettingsService::class)->load();
        $categoryId = $product->subcategory_id ?? $product->category_id;

        $productAttributes = $product->attributes ?? collect();

        $categoryIds = collect([(int) ($product->category_id)]);
        if ($product->category?->parent_id) {
            $categoryIds->push((int) $product->category->parent_id);
        }

        if ($productAttributes->count()) {
            $productAttributes = $productAttributes
                ->filter(fn ($a) => is_null($a->category_id) || $categoryIds->contains((int) $a->category_id))
                ->values();

            $perCategoryOrder = is_array(($pdfSettings['attribute_order_by_category'] ?? null)) ? ($pdfSettings['attribute_order_by_category'] ?? []) : [];
            $attributeOrder = [];
            if ($categoryId && !empty($perCategoryOrder[$categoryId])) {
                $attributeOrder = $perCategoryOrder[$categoryId];
            } elseif ($product->category?->parent_id && !empty($perCategoryOrder[$product->category->parent_id])) {
                $attributeOrder = $perCategoryOrder[$product->category->parent_id];
            } else {
                $attributeOrder = is_array(($pdfSettings['attribute_order'] ?? null)) ? ($pdfSettings['attribute_order'] ?? []) : [];
            }

            if (!empty($attributeOrder)) {
                $orderMap = array_flip(array_map('intval', $attributeOrder));
                $productAttributes = $productAttributes
                    ->sortBy(fn ($a) => $orderMap[(int) $a->id] ?? 100000 + (int) $a->id)
                    ->values();
            }
        }

        $perCategoryGroups = is_array(($pdfSettings['attribute_groups'] ?? null)) ? ($pdfSettings['attribute_groups'] ?? []) : [];
        $attributeGroups = [];
        if ($categoryId && !empty($perCategoryGroups[$categoryId])) {
            $attributeGroups = $perCategoryGroups[$categoryId];
        } elseif ($product->category?->parent_id && !empty($perCategoryGroups[$product->category->parent_id])) {
            $attributeGroups = $perCategoryGroups[$product->category->parent_id];
        }

        $attributeSections = [];
        if (!empty($attributeGroups) && $productAttributes->count()) {
            foreach ($attributeGroups as $group) {
                $ids = array_map('intval', is_array(($group['attributes'] ?? null)) ? ($group['attributes'] ?? []) : []);
                $orderMap = array_flip($ids);
                $attrs = $productAttributes
                    ->filter(fn ($a) => in_array((int) $a->id, $ids, true))
                    ->sortBy(fn ($a) => $orderMap[(int) $a->id] ?? 100000 + (int) $a->id)
                    ->values();
                if ($attrs->count()) {
                    $attributeSections[] = ['name' => $group['name'] ?? '', 'attributes' => $attrs];
                }
            }
        }

        if (empty($attributeSections)) {
            $attributeSections[] = ['name' => '', 'attributes' => $productAttributes];
        }

        return view('public.products.show', compact('product', 'similarProducts', 'facilityProducts', 'isFavorited', 'attributeSections'));

    }

    /**
     * معرض صور المنتج
     */
    public function gallery(Product $product)
    {
        if (!$product->is_active || !$product->is_verified) {
            abort(404);
        }

        $main = $product->main_image ? asset($product->main_image) : null;

        $gallery = collect($product->image_gallery ?? [])
            ->filter()
            ->map(fn ($img) => asset($img))
            ->values();

        $images = collect([$main])
            ->merge($gallery)
            ->filter()
            ->unique()
            ->values()
            ->all();

        return view('public.products.gallery', [
            'product' => $product,
            'images'  => $images,
        ]);
    }

    /**
     * PDF preview للمنتج
     */
    public function pdf(\Illuminate\Http\Request $request, Product $product)
    {
        $format = (string) $request->query('format', 'presentation');
        if (! in_array($format, ['presentation', 'mobile'], true)) {
            $format = 'presentation';
        }

        $theme = (string) $request->query('theme', '');
        if (! in_array($theme, ['light', 'dark'], true)) {
            $theme = '';
        }

        $pdf = app(\App\Services\ProductPdfService::class)->render($product, [
            'format' => $format,
            'theme' => $theme !== '' ? $theme : null,
            'locale' => $this->pdfLocale($request),
        ]);

        return response($pdf['binary'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$pdf['filename'].'"',
        ]);
    }

    /**
     * HTML preview للمنتج (مفيد للمعاينة داخل iframe)
     */
    public function pdfHtml(Request $request, Product $product)
    {
        $format = (string) $request->query('format', 'presentation');
        if (! in_array($format, ['presentation', 'mobile'], true)) {
            $format = 'presentation';
        }

        $onlySlide = (string) $request->query('slide', '');
        $allowedSlides = ['cover', 'gallery', 'details', 'location', 'features', 'offers', 'cta'];
        if (! in_array($onlySlide, $allowedSlides, true)) {
            $onlySlide = '';
        }

        $theme = (string) $request->query('theme', '');
        if (! in_array($theme, ['light', 'dark'], true)) {
            $theme = '';
        }

        $html = app(\App\Services\ProductPdfService::class)->renderHtml($product, [
            'format' => $format,
            'only_slide' => $onlySlide !== '' ? $onlySlide : null,
            'theme' => $theme !== '' ? $theme : null,
            'locale' => $this->pdfLocale($request),
        ]);

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    /**
     * Allow the document language to be forced via ?lang=, otherwise inherit the
     * visitor's active locale.
     */
    private function pdfLocale(Request $request): ?string
    {
        $lang = (string) $request->query('lang', '');
        $supported = array_keys(app(\App\Services\LanguageService::class)->getAvailableLanguages());

        return in_array($lang, $supported, true) ? $lang : null;
    }

    /**
     * المنتجات حسب الفئة
     */
    public function byCategory(Request $request, Category $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $categoryIds = collect([$category->id]);
        $queue = collect([$category->id]);
        while ($queue->isNotEmpty()) {
            $children = Category::whereIn('parent_id', $queue)->pluck('id');
            $new = $children->diff($categoryIds);
            if ($new->isEmpty()) {
                break;
            }
            $categoryIds = $categoryIds->merge($new);
            $queue = $new;
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortBy = ['created_at', 'price', 'title'];
        if (!in_array($sortBy, $allowedSortBy, true)) {
            $sortBy = 'created_at';
        }
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';

        $products = Product::with(['facility', 'category', 'statuses', 'offers'])
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->orderBy($sortBy, $sortOrder)
            ->paginate(12);

        $categories = Category::where('is_active', true)->withCount('products')->get();
        $searchCategories = $categories;
        $searchFeatures = Feature::where('is_active', true)->with('translations')->get();

        $productIds = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->pluck('id');

        $facilityIds = Product::whereIn('id', $productIds)->pluck('facility_id')->filter()->unique();
        $stats = [
            'properties' => $productIds->count(),
            'developers' => Facility::whereIn('id', $facilityIds)->where('is_active', true)->where('is_verified', true)->count(),
            'offers' => \App\Models\Offer::whereIn('product_id', $productIds)->where('is_active', true)->count(),
        ];

        return view('public.products.by-category', compact('category', 'products', 'categories', 'searchCategories', 'searchFeatures', 'stats', 'sortBy', 'sortOrder'));
    }

    /**
     * المنتجات حسب المنشأة
     */
    public function byFacility(Facility $facility)
    {
        if (!$facility->is_active || !$facility->is_verified) {
            abort(404);
        }

        $products = $facility->products()
            ->with(['category', 'statuses'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->paginate(12);

        return view('public.products.by-facility', compact('facility', 'products'));
    }

    /**
     * المنتجات المميزة
     */
    public function featured()
    {
        $products = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->where('is_featured', true)
            ->latest()
            ->paginate(12);

        return view('public.products.featured', compact('products'));
    }

    /**
     * أحدث المنتجات
     */
    public function latest()
    {
        $products = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->latest()
            ->paginate(12);

        return view('public.products.latest', compact('products'));
    }

    /**
     * البحث في المنتجات
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $query = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%')
                  ->orWhere('address', 'like', '%' . $request->q . '%')
                  ->orWhereHas('facility', function ($facilityQuery) use ($request) {
                      $facilityQuery->where('name', 'like', '%' . $request->q . '%');
                  });
            });

        $products = $query->paginate(12);
        $searchTerm = $request->q;

        return view('public.products.search', compact('products', 'searchTerm'));
    }

    /**
     * خريطة المنتجات
     */
    public function map()
    {
        return redirect()->route('public.search.map', ['search_type' => 'projects']);
    }

    /**
     * إضافة تعليق على المنتج
     */
    public function addComment(Request $request, Product $product)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
            'rating' => 'required|integer|between:1,5',
        ]);

        $user = auth()->user();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'يجب تسجيل الدخول لإضافة تعليق');
        }

        // التحقق من أن المستخدم لم يعلق على هذا المنتج من قبل
        $existingComment = $product->comments()->where('user_id', $user->id)->first();

        if ($existingComment) {
            return redirect()->back()
                ->with('error', 'لقد علقت على هذا المنتج من قبل');
        }

        $product->comments()->create([
            'user_id' => $user->id,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return redirect()->back()
            ->with('success', 'تم إضافة تعليقك بنجاح');
    }

    /**
     * إضافة منتج للمفضلة
     */
    public function addToFavorites(Product $product)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'يجب تسجيل الدخول لإضافة المنتج للمفضلة');
        }

        if (!$user->favoriteProducts()->where('products.id', $product->id)->exists()) {
            $user->favoriteProducts()->attach($product->id);
            return redirect()->back()
                ->with('success', 'تم إضافة المنتج للمفضلة بنجاح');
        }

        return redirect()->back()
            ->with('error', 'المنتج موجود بالفعل في المفضلة');
    }

    /**
     * إزالة منتج من المفضلة
     */
    public function removeFromFavorites(Product $product)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'يجب تسجيل الدخول');
        }

        $user->favoriteProducts()->detach($product->id);

        return redirect()->back()
            ->with('success', 'تم إزالة المنتج من المفضلة بنجاح');
    }
}
