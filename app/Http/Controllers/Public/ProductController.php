<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Feature;
use App\Models\City;

class ProductController extends Controller
{
    /**
     * عرض قائمة المنتجات
     */
    public function index(Request $request)
    {
        $query = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->where('is_verified', true);

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

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
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

        $product->load(['facility', 'category', 'statuses', 'features', 'attributes']);

        // هل المنتج في المفضلة للمستخدم الحالي؟
        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = auth()->user()->favoriteProducts()->where('products.id', $product->id)->exists();
        }

        // المنتجات المشابهة
        $similarProducts = Product::with(['facility', 'category'])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->take(6)
            ->get();

        // منتجات نفس المنشأة
        $facilityProducts = Product::with(['category'])
            ->where('id', '!=', $product->id)
            ->where('facility_id', $product->facility_id)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->take(4)
            ->get();

        return view('public.products.show', compact('product', 'similarProducts', 'facilityProducts', 'isFavorited'));
    }

    /**
     * المنتجات حسب الفئة
     */
    public function byCategory(Category $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $products = $category->products()
            ->with(['facility', 'statuses'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->paginate(12);

        return view('public.products.by-category', compact('category', 'products'));
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
        $products = Product::with(['facility', 'category'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('public.products.map', compact('products'));
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
