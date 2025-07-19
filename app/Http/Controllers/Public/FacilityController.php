<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Category;
use App\Models\Product;

class FacilityController extends Controller
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

        $facilities = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('public.facilities.index', compact('facilities', 'categories'));
    }

    /**
     * عرض منشأة محددة
     */
    public function show(Facility $facility)
    {
        if (!$facility->is_active || !$facility->is_verified) {
            abort(404);
        }

        $facility->load(['category', 'owner', 'gallery']);

        // منتجات المنشأة
        $products = $facility->products()
            ->with(['category', 'status'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->take(8)
            ->get();

        // المنشآت المشابهة
        $similarFacilities = Facility::with(['category'])
            ->where('id', '!=', $facility->id)
            ->where('category_id', $facility->category_id)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->take(4)
            ->get();

        return view('public.facilities.show', compact('facility', 'products', 'similarFacilities'));
    }

    /**
     * المنشآت حسب الفئة
     */
    public function byCategory(Category $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $facilities = Facility::with(['owner'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->paginate(12);

        return view('public.facilities.by-category', compact('category', 'facilities'));
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
            ->paginate(12);

        return view('public.facilities.featured', compact('facilities'));
    }

    /**
     * البحث في المنشآت
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $query = Facility::with(['category', 'owner'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%')
                  ->orWhere('address', 'like', '%' . $request->q . '%');
            });

        $facilities = $query->orderBy('rating', 'desc')->paginate(12);
        $searchTerm = $request->q;

        return view('public.facilities.search', compact('facilities', 'searchTerm'));
    }

    /**
     * خريطة المنشآت
     */
    public function map()
    {
        $facilities = Facility::with(['category'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('public.facilities.map', compact('facilities'));
    }

    /**
     * تقييم المنشأة
     */
    public function rate(Request $request, Facility $facility)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'يجب تسجيل الدخول لتقييم المنشأة');
        }

        // التحقق من أن المستخدم لم يقيم المنشأة من قبل
        $existingRating = $facility->ratings()->where('user_id', $user->id)->first();

        if ($existingRating) {
            return redirect()->back()
                ->with('error', 'لقد قمت بتقييم هذه المنشأة من قبل');
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

        return redirect()->back()
            ->with('success', 'تم إضافة تقييمك بنجاح');
    }

    /**
     * إضافة منشأة للمفضلة
     */
    public function addToFavorites(Facility $facility)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'يجب تسجيل الدخول لإضافة المنشأة للمفضلة');
        }

        if (!$user->facilities()->where('facility_id', $facility->id)->exists()) {
            $user->facilities()->attach($facility->id);
            return redirect()->back()
                ->with('success', 'تم إضافة المنشأة للمفضلة بنجاح');
        }

        return redirect()->back()
            ->with('error', 'المنشأة موجودة بالفعل في المفضلة');
    }

    /**
     * إزالة منشأة من المفضلة
     */
    public function removeFromFavorites(Facility $facility)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'يجب تسجيل الدخول');
        }

        $user->facilities()->detach($facility->id);

        return redirect()->back()
            ->with('success', 'تم إزالة المنشأة من المفضلة بنجاح');
    }

    /**
     * طلب عرض سعر
     */
    public function requestQuote(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
            'product_type' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:255',
        ]);

        // هنا يمكن إرسال طلب عرض السعر عبر البريد الإلكتروني
        // سيتم تنفيذ هذا لاحقاً

        return redirect()->back()
            ->with('success', 'تم إرسال طلب عرض السعر بنجاح. سنتواصل معك قريباً.');
    }

    /**
     * حجز موعد
     */
    public function bookAppointment(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'message' => 'nullable|string|max:500',
        ]);

        // هنا يمكن إنشاء موعد
        // سيتم تنفيذ هذا لاحقاً

        return redirect()->back()
            ->with('success', 'تم حجز الموعد بنجاح. سنتواصل معك قريباً لتأكيد الموعد.');
    }
}
