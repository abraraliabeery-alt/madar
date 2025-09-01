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
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->property_type) {
            if ($request->property_type === 'sale') {
                $query->where('available_for_sale', true);
            } else {
                $query->where('available_for_rent', true);
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
}
