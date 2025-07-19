<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class ApiFacilityController extends Controller
{
    /**
     * عرض قائمة المنشآت
     */
    public function index(Request $request)
    {
        $query = Facility::with(['category', 'owner'])
            ->where('is_active', true)
            ->where('is_verified', true);

        // فلترة حسب الفئة
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // فلترة حسب التقييم
        if ($request->has('min_rating') && $request->min_rating) {
            $query->where('rating', '>=', $request->min_rating);
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

        // البحث
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        // الترتيب
        $sortBy = $request->get('sort', 'rating');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'products_count':
                $query->withCount('products')->orderBy('products_count', 'desc');
                break;
            case 'latest':
                $query->latest();
                break;
            default:
                $query->orderBy('rating', 'desc');
                break;
        }

        $perPage = $request->get('per_page', 15);
        $facilities = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $facilities,
            'message' => 'تم جلب المنشآت بنجاح'
        ]);
    }

    /**
     * عرض منشأة محددة
     */
    public function show(Facility $facility)
    {
        if (!$facility->is_active || !$facility->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'المنشأة غير متاحة'
            ], 404);
        }

        $facility->load(['category', 'owner', 'products', 'gallery']);

        return response()->json([
            'success' => true,
            'data' => $facility,
            'message' => 'تم جلب تفاصيل المنشأة بنجاح'
        ]);
    }

    /**
     * المنشآت المميزة
     */
    public function featured()
    {
        $facilities = Facility::with(['category', 'owner'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->where('is_featured', true)
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $facilities,
            'message' => 'تم جلب المنشآت المميزة بنجاح'
        ]);
    }

    /**
     * المنشآت حسب الفئة
     */
    public function byCategory(Category $category)
    {
        $facilities = Facility::with(['owner'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'facilities' => $facilities
            ],
            'message' => 'تم جلب المنشآت حسب الفئة بنجاح'
        ]);
    }

    /**
     * منتجات المنشأة
     */
    public function products(Facility $facility, Request $request)
    {
        if (!$facility->is_active || !$facility->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'المنشأة غير متاحة'
            ], 404);
        }

        $query = $facility->products()
            ->with(['category', 'status'])
            ->where('is_active', true);

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

        // الترتيب
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'facility' => $facility,
                'products' => $products
            ],
            'message' => 'تم جلب منتجات المنشأة بنجاح'
        ]);
    }

    /**
     * البحث في المنشآت
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2',
            'category_id' => 'nullable|exists:categories,id',
            'min_rating' => 'nullable|numeric|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Facility::with(['category', 'owner'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%')
                  ->orWhere('address', 'like', '%' . $request->q . '%');
            });

        // تطبيق الفلاتر الإضافية
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->min_rating) {
            $query->where('rating', '>=', $request->min_rating);
        }

        $facilities = $query->orderBy('rating', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $facilities,
            'message' => 'تم البحث بنجاح'
        ]);
    }

    /**
     * تقييم المنشأة
     */
    public function rate(Request $request, Facility $facility)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // التحقق من أن المستخدم لم يقيم المنشأة من قبل
        $existingRating = $facility->ratings()->where('user_id', $user->id)->first();

        if ($existingRating) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بتقييم هذه المنشأة من قبل'
            ], 400);
        }

        // إضافة التقييم
        $facility->ratings()->create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // تحديث متوسط التقييم للمنشأة
        $avgRating = $facility->ratings()->avg('rating');
        $facility->update(['rating' => round($avgRating, 1)]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة تقييمك بنجاح'
        ]);
    }

    /**
     * إضافة منشأة للمفضلة
     */
    public function addToFavorites(Request $request, Facility $facility)
    {
        $user = $request->user();

        if (!$user->facilities()->where('facility_id', $facility->id)->exists()) {
            $user->facilities()->attach($facility->id);
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المنشأة للمفضلة بنجاح'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'المنشأة موجودة بالفعل في المفضلة'
        ], 400);
    }

    /**
     * إزالة منشأة من المفضلة
     */
    public function removeFromFavorites(Request $request, Facility $facility)
    {
        $user = $request->user();
        $user->facilities()->detach($facility->id);

        return response()->json([
            'success' => true,
            'message' => 'تم إزالة المنشأة من المفضلة بنجاح'
        ]);
    }

    /**
     * المنشآت المفضلة للمستخدم
     */
    public function favorites(Request $request)
    {
        $user = $request->user();
        $favorites = $user->facilities()
            ->with(['category', 'owner'])
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $favorites,
            'message' => 'تم جلب المنشآت المفضلة بنجاح'
        ]);
    }

    /**
     * إحصائيات المنشآت
     */
    public function statistics()
    {
        $stats = [
            'total_facilities' => Facility::where('is_active', true)->where('is_verified', true)->count(),
            'featured_facilities' => Facility::where('is_active', true)->where('is_verified', true)->where('is_featured', true)->count(),
            'categories_count' => Category::count(),
            'top_rated_facilities' => Facility::where('is_active', true)
                ->where('is_verified', true)
                ->orderBy('rating', 'desc')
                ->take(10)
                ->get(['id', 'name', 'rating']),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'تم جلب الإحصائيات بنجاح'
        ]);
    }
}
