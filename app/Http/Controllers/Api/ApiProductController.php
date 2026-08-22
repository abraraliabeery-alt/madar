<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Status;
use App\Models\Feature;
use App\Models\Attribute;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class ApiProductController extends Controller
{
    /**
     * عرض قائمة المنتجات
     */
    public function index(Request $request)
    {
        $query = Product::with(['facility', 'category', 'features', 'offers'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->withActiveOffers();

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by facility
        if ($request->has('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        // Filter by price range (active offers)
        if ($request->has('min_price')) {
            $query->whereHas('activeOffers', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->has('max_price')) {
            $query->whereHas('activeOffers', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Filter by rooms (bedrooms attribute)
        if ($request->has('rooms')) {
            $query->whereHas('attributes', function ($q) use ($request) {
                $q->where('key', 'bedrooms')->where('product_attribute_values.value', $request->rooms);
            });
        }

        // Filter by area range (area attribute)
        if ($request->has('min_area')) {
            $query->whereHas('attributes', function ($q) use ($request) {
                $q->where('key', 'area')->whereRaw('CAST(product_attribute_values.value AS DECIMAL(10,2)) >= ?', [$request->min_area]);
            });
        }

        if ($request->has('max_area')) {
            $query->whereHas('attributes', function ($q) use ($request) {
                $q->where('key', 'area')->whereRaw('CAST(product_attribute_values.value AS DECIMAL(10,2)) <= ?', [$request->max_area]);
            });
        }

        // Search by keyword
        if ($request->has('q') && $request->q) {
            $locale = Session::get('locale', config('app.locale'));
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

        // Sort results
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'تم جلب المنتجات بنجاح'
        ]);
    }

    /**
     * عرض منتج محدد
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير متاح'
            ], 404);
        }

        $product->load(['facility', 'category', 'statuses', 'features', 'attributes.translations']);

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'تم جلب تفاصيل المنتج بنجاح'
        ]);
    }

    /**
     * المنتجات المميزة
     */
    public function featured()
    {
        $products = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'تم جلب المنتجات المميزة بنجاح'
        ]);
    }

    /**
     * أحدث المنتجات
     */
    public function latest()
    {
        $products = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'تم جلب أحدث المنتجات بنجاح'
        ]);
    }

    /**
     * المنتجات حسب الفئة
     */
    public function byCategory(Category $category)
    {
        $products = $category->products()
            ->with(['facility', 'status'])
            ->where('is_active', true)
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'products' => $products
            ],
            'message' => 'تم جلب المنتجات حسب الفئة بنجاح'
        ]);
    }

    /**
     * المنتجات حسب المنشأة
     */
    public function byFacility($facilityId)
    {
        $products = Product::with(['category', 'status'])
            ->where('facility_id', $facilityId)
            ->where('is_active', true)
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'تم جلب منتجات المنشأة بنجاح'
        ]);
    }

    /**
     * البحث في المنتجات
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2',
            'category_id' => 'nullable|exists:categories,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'property_type' => 'nullable|in:sale,rent',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%')
                  ->orWhere('address', 'like', '%' . $request->q . '%')
                  ->orWhereHas('facility', function ($facilityQuery) use ($request) {
                      $facilityQuery->where('name', 'like', '%' . $request->q . '%');
                  });
            });

        // تطبيق الفلاتر الإضافية
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->min_price) {
            $query->whereHas('activeOffers', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }
        if ($request->max_price) {
            $query->whereHas('activeOffers', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }
        if ($request->property_type) {
            if ($request->property_type === 'sale') {
                $query->whereHas('saleOffers');
            } else {
                $query->whereHas('rentOffers');
            }
        }

        $products = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'تم البحث بنجاح'
        ]);
    }

    /**
     * إضافة منتج للمفضلة
     */
    public function addToFavorites(Request $request, Product $product)
    {
        $user = $request->user();

        if (!$user->products()->where('product_id', $product->id)->exists()) {
            $user->products()->attach($product->id);
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المنتج للمفضلة بنجاح'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'المنتج موجود بالفعل في المفضلة'
        ], 400);
    }

    /**
     * إزالة منتج من المفضلة
     */
    public function removeFromFavorites(Request $request, Product $product)
    {
        $user = $request->user();
        $user->products()->detach($product->id);

        return response()->json([
            'success' => true,
            'message' => 'تم إزالة المنتج من المفضلة بنجاح'
        ]);
    }

    /**
     * المنتجات المفضلة للمستخدم
     */
    public function favorites(Request $request)
    {
        $user = $request->user();
        $favorites = $user->products()
            ->with(['facility', 'category'])
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $favorites,
            'message' => 'تم جلب المنتجات المفضلة بنجاح'
        ]);
    }

    /**
     * إحصائيات المنتجات
     */
    public function statistics()
    {
        $stats = [
            'total_products' => Product::where('is_active', true)->count(),
            'featured_products' => Product::where('is_active', true)->where('is_featured', true)->count(),
            'verified_products' => Product::where('is_active', true)->where('is_verified', true)->count(),
            'categories_count' => Category::count(),
            'price_range' => [
                'min' => Product::where('is_active', true)->min('price'),
                'max' => Product::where('is_active', true)->max('price'),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'تم جلب الإحصائيات بنجاح'
        ]);
    }

    /**
     * Generate a product description using AI.
     */
    public function generateDescription(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:200',
            'category' => 'nullable|string|max:200',
            'city' => 'nullable|string|max:200',
            'neighborhood' => 'nullable|string|max:200',
            'street' => 'nullable|string|max:200',
            'address' => 'nullable|string|max:500',
            'price' => 'nullable|numeric',
            'offer_type' => 'nullable|string|max:100',
            'attributes' => 'nullable|array|max:100',
            'features' => 'nullable|array|max:100',
        ]);

        try {
            $description = app(\App\Services\AI\ProductAiService::class)->generateDescription($data);

            return response()->json([
                'success' => true,
                'description' => $description,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function generateMarketingContent(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:200',
            'category' => 'nullable|string|max:200',
            'city' => 'nullable|string|max:200',
            'neighborhood' => 'nullable|string|max:200',
            'street' => 'nullable|string|max:200',
            'address' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0',
            'offer_type' => 'nullable|string|max:50',
            'attributes' => 'nullable|array|max:100',
            'features' => 'nullable|array|max:100',
        ]);

        try {
            return response()->json([
                'success' => true,
                'content' => app(\App\Services\AI\ProductAiService::class)->generateMarketingContent($data),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'تعذر توليد المحتوى. تحقق من إعداد مزود الذكاء الاصطناعي.',
            ], 422);
        }
    }

    /**
     * Generate title and description from a property image.
     */
    public function generateFromImage(Request $request)
    {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            $result = app(\App\Services\AI\ProductAiService::class)->generateFromImage($data['image']);

            return response()->json([
                'success' => true,
                'title' => $result['title'],
                'description' => $result['description'],
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'تعذر تحليل الصورة. تأكد من جودتها أو تحقق من إعداد الذكاء الاصطناعي.',
            ], 422);
        }
    }
}
