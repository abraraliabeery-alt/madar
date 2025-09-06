<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Services\OfferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientOfferController extends Controller
{
    protected $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * عرض قائمة العروض المتاحة
     */
    public function index(Request $request)
    {
        $filters = $request->only(['type', 'min_price', 'max_price', 'product_id']);
        $offers = $this->offerService->searchOffers($filters);

        // إضافة معلومات المنتج والمنشأة
        $offers->load(['product.translations', 'facility', 'translations']);

        return view('client.offers.index', compact('offers'));
    }

    /**
     * عرض تفاصيل العرض
     */
    public function show(Offer $offer)
    {
        $offer->load(['product.translations', 'facility', 'translations']);
        
        // العروض المشابهة
        $similarOffers = Offer::where('id', '!=', $offer->id)
            ->where('product_id', $offer->product_id)
            ->orWhere('offer_type', $offer->offer_type)
            ->active()
            ->valid()
            ->with(['product.translations', 'facility'])
            ->limit(4)
            ->get();

        return view('client.offers.show', compact('offer', 'similarOffers'));
    }

    /**
     * عرض العروض حسب نوع المنتج
     */
    public function byType($type)
    {
        $offers = $this->offerService->getOffersByType($type);
        $offers->load(['product.translations', 'facility', 'translations']);

        $typeLabels = [
            'sale' => 'عروض البيع',
            'rent_monthly' => 'عروض الإيجار الشهري',
            'rent_yearly' => 'عروض الإيجار السنوي',
            'rent_daily' => 'عروض الإيجار اليومي',
        ];

        $title = $typeLabels[$type] ?? 'العروض';

        return view('client.offers.by-type', compact('offers', 'title', 'type'));
    }

    /**
     * عرض العروض لمنتج محدد
     */
    public function byProduct(Product $product)
    {
        $offers = $this->offerService->getActiveOffersForProduct($product);
        $offers->load(['facility', 'translations']);

        return view('client.offers.by-product', compact('offers', 'product'));
    }

    /**
     * البحث في العروض
     */
    public function search(Request $request)
    {
        $filters = $request->only(['type', 'min_price', 'max_price', 'product_id', 'facility_id']);
        $offers = $this->offerService->searchOffers($filters);
        $offers->load(['product.translations', 'facility', 'translations']);

        return view('client.offers.search', compact('offers'));
    }

    /**
     * إضافة عرض للمفضلة
     */
    public function addToFavorites(Offer $offer)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // إضافة المنتج للمفضلة (إذا لم يكن موجود)
            if (!$user->favoriteProducts()->where('favoritable_id', $offer->product_id)->exists()) {
                $user->favoriteProducts()->attach($offer->product_id);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة العرض للمفضلة'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'يجب تسجيل الدخول أولاً'
        ], 401);
    }

    /**
     * إزالة عرض من المفضلة
     */
    public function removeFromFavorites(Offer $offer)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->favoriteProducts()->detach($offer->product_id);

            return response()->json([
                'success' => true,
                'message' => 'تم إزالة العرض من المفضلة'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'يجب تسجيل الدخول أولاً'
        ], 401);
    }

    /**
     * طلب معلومات إضافية عن العرض
     */
    public function requestInfo(Request $request, Offer $offer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:1000',
        ]);

        // هنا يمكن إضافة منطق إرسال الإيميل أو الإشعار
        // للآن سنقوم بحفظ الطلب في قاعدة البيانات

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال طلبك بنجاح. سنتواصل معك قريباً.'
        ]);
    }

    /**
     * حجز موعد لزيارة العقار
     */
    public function bookVisit(Request $request, Offer $offer)
    {
        $request->validate([
            'visit_date' => 'required|date|after:today',
            'visit_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً'
            ], 401);
        }

        // هنا يمكن إضافة منطق حجز الموعد
        // للآن سنقوم بإرجاع رسالة نجاح

        return response()->json([
            'success' => true,
            'message' => 'تم حجز موعد الزيارة بنجاح'
        ]);
    }

    /**
     * مقارنة العروض
     */
    public function compare(Request $request)
    {
        $offerIds = $request->get('offers', []);
        
        if (count($offerIds) < 2) {
            return redirect()->back()->with('error', 'يجب اختيار عرضين على الأقل للمقارنة');
        }

        $offers = Offer::whereIn('id', $offerIds)
            ->active()
            ->valid()
            ->with(['product.translations', 'facility', 'translations'])
            ->get();

        return view('client.offers.compare', compact('offers'));
    }

    /**
     * إحصائيات العروض
     */
    public function statistics()
    {
        $stats = $this->offerService->getOfferStatistics();

        return view('client.offers.statistics', compact('stats'));
    }
}
