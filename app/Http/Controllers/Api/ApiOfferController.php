<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Services\OfferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiOfferController extends Controller
{
    protected $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * عرض قائمة العروض
     */
    public function index(Request $request)
    {
        $filters = $request->only(['type', 'facility_id', 'min_price', 'max_price', 'product_id']);
        $offers = $this->offerService->searchOffers($filters);

        return response()->json([
            'success' => true,
            'data' => $offers
        ]);
    }

    /**
     * إنشاء عرض جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'offer_type' => 'required|in:sale,rent_monthly,rent_yearly,rent_daily',
            'price' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:1',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after:valid_from',
            'terms_conditions' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['facility_id'] = Auth::user()->facilities()->first()?->id;
        $data['created_by'] = Auth::id();

        $offer = $this->offerService->createOffer($data);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء العرض بنجاح',
            'data' => $offer->load(['product', 'translations'])
        ], 201);
    }

    /**
     * عرض عرض محدد
     */
    public function show(Offer $offer)
    {
        $offer->load(['product', 'facility', 'translations']);
        
        return response()->json([
            'success' => true,
            'data' => $offer
        ]);
    }

    /**
     * تحديث عرض
     */
    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'offer_type' => 'sometimes|in:sale,rent_monthly,rent_yearly,rent_daily',
            'price' => 'sometimes|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:1',
            'is_active' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after:valid_from',
            'terms_conditions' => 'nullable|string',
        ]);

        $offer = $this->offerService->updateOffer($offer, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث العرض بنجاح',
            'data' => $offer->load(['product', 'translations'])
        ]);
    }

    /**
     * حذف عرض
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف العرض بنجاح'
        ]);
    }

    /**
     * تفعيل/إلغاء تفعيل عرض
     */
    public function toggleStatus(Offer $offer)
    {
        $offer = $this->offerService->toggleOfferStatus($offer);

        return response()->json([
            'success' => true,
            'message' => $offer->is_active ? 'تم تفعيل العرض' : 'تم إلغاء تفعيل العرض',
            'data' => $offer
        ]);
    }

    /**
     * الحصول على عروض منتج محدد
     */
    public function getProductOffers(Product $product)
    {
        $offers = $this->offerService->getActiveOffersForProduct($product);

        return response()->json([
            'success' => true,
            'data' => $offers
        ]);
    }

    /**
     * نسخ عرض
     */
    public function copy(Request $request, Offer $offer)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $newOffer = $this->offerService->copyOffer($offer, $request->product_id);

        return response()->json([
            'success' => true,
            'message' => 'تم نسخ العرض بنجاح',
            'data' => $newOffer->load(['product', 'translations'])
        ], 201);
    }

    /**
     * إحصائيات العروض
     */
    public function statistics(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $stats = $this->offerService->getOfferStatistics($facilityId);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * تصدير العروض
     */
    public function export(Request $request)
    {
        $filters = $request->only(['type', 'facility_id', 'min_price', 'max_price', 'product_id']);
        $data = $this->offerService->exportOffers($filters);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * عرض تفاصيل عرض مع العروض ذات الصلة
     */
    public function getOfferDetails(Offer $offer)
    {
        $relatedOffers = Offer::where('product_id', $offer->product_id)
            ->where('id', '!=', $offer->id)
            ->where('is_active', true)
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'offer' => $offer->load(['product', 'facility', 'translations']),
            'relatedOffers' => $relatedOffers->load(['translations'])
        ]);
    }

    /**
     * عرض جميع عروض منتج محدد
     */
    public function getAllProductOffers(Product $product)
    {
        $offers = $product->offers()
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('price', 'asc')
            ->get()
            ->load(['translations']);

        return response()->json([
            'success' => true,
            'offers' => $offers
        ]);
    }
}
