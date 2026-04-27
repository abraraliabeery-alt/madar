<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Services\OfferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityOfferController extends Controller
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
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $query = $facility->offers()->with(['product', 'translations']);

        // فلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('offer_type', $request->type);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active()->valid();
            } elseif ($request->status === 'expired') {
                $query->where('valid_to', '<', now());
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->whereHas('translations', function($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%");
                });
            });
        }

        $offers = $query->paginate(15);

        // قائمة المنتجات للاستخدام في إجراء النسخ السريع
        $productsList = $facility->products()->with('translations')->get(['id','facility_id']);

        return view('facility.offers.index', compact('offers','productsList'));
    }

    /**
     * عرض نموذج إنشاء عرض
     */
    public function create()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $products = $facility->products()->with('translations')->get();
        $offerTypes = [
            'sale' => 'بيع',
            'rent_monthly' => 'إيجار شهري',
            'rent_yearly' => 'إيجار سنوي',
            'rent_daily' => 'إيجار يومي',
        ];

        return view('facility.offers.create', compact('products', 'offerTypes'));
    }

    /**
     * حفظ عرض جديد
     */
    public function store(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'offer_type' => 'required|in:sale,rent_monthly,rent_yearly,rent_daily',
            'price' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after:valid_from',
            'terms_conditions' => 'nullable|string',
            'offer_title' => 'nullable|string|max:255',
            'offer_description' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'marketing_notes' => 'nullable|string',
            'priority' => 'nullable|integer|min:1|max:10',
            'auto_renew' => 'boolean',
            'min_contract_duration' => 'nullable|integer|min:1',
            'max_contract_duration' => 'nullable|integer|min:1|gte:min_contract_duration',
            'payment_plan' => 'nullable|array',
            'terms_conditions_ar' => 'nullable|string',
            'special_notes_ar' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['facility_id'] = $facility->id;
        $data['created_by'] = Auth::id();

        $offer = $this->offerService->createOffer($data);

        // حفظ الترجمات
        if ($request->filled('terms_conditions_ar')) {
            $offer->translations()->create([
                'locale' => 'ar',
                'terms_conditions' => $request->terms_conditions_ar,
                'special_notes' => $request->special_notes_ar,
            ]);
        }

        return redirect()->route('facility.offers.index')
            ->with('success', 'تم إنشاء العرض بنجاح');
    }

    /**
     * عرض تفاصيل العرض
     */
    public function show(Offer $offer)
    {
        $this->authorize('view', $offer);
        
        $offer->load(['product', 'translations']);
        
        return view('facility.offers.show', compact('offer'));
    }

    /**
     * عرض نموذج تعديل العرض
     */
    public function edit(Offer $offer)
    {
        $this->authorize('update', $offer);
        
        $facility = Auth::user()->facilities()->first();
        $products = $facility->products()->with('translations')->get();
        $offerTypes = [
            'sale' => 'بيع',
            'rent_monthly' => 'إيجار شهري',
            'rent_yearly' => 'إيجار سنوي',
            'rent_daily' => 'إيجار يومي',
        ];

        $offer->load('translations');
        
        return view('facility.offers.edit', compact('offer', 'products', 'offerTypes'));
    }

    /**
     * تحديث العرض
     */
    public function update(Request $request, Offer $offer)
    {
        $this->authorize('update', $offer);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'offer_type' => 'required|in:sale,rent_monthly,rent_yearly,rent_daily',
            'price' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after:valid_from',
            'terms_conditions' => 'nullable|string',
            'offer_title' => 'nullable|string|max:255',
            'offer_description' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'marketing_notes' => 'nullable|string',
            'priority' => 'nullable|integer|min:1|max:10',
            'auto_renew' => 'boolean',
            'min_contract_duration' => 'nullable|integer|min:1',
            'max_contract_duration' => 'nullable|integer|min:1|gte:min_contract_duration',
            'payment_plan' => 'nullable|array',
            'terms_conditions_ar' => 'nullable|string',
            'special_notes_ar' => 'nullable|string',
        ]);

        $offer = $this->offerService->updateOffer($offer, $request->all());

        // تحديث الترجمات
        $translation = $offer->translations()->where('locale', 'ar')->first();
        if ($translation) {
            $translation->update([
                'terms_conditions' => $request->terms_conditions_ar,
                'special_notes' => $request->special_notes_ar,
            ]);
        } else {
            $offer->translations()->create([
                'locale' => 'ar',
                'terms_conditions' => $request->terms_conditions_ar,
                'special_notes' => $request->special_notes_ar,
            ]);
        }

        return redirect()->route('facility.offers.index')
            ->with('success', 'تم تحديث العرض بنجاح');
    }

    /**
     * حذف العرض
     */
    public function destroy(Offer $offer)
    {
        $this->authorize('delete', $offer);
        
        $offer->delete();

        return redirect()->route('facility.offers.index')
            ->with('success', 'تم حذف العرض بنجاح');
    }

    /**
     * تفعيل/إلغاء تفعيل العرض
     */
    public function toggleStatus(Offer $offer)
    {
        $this->authorize('update', $offer);
        
        $offer = $this->offerService->toggleOfferStatus($offer);

        $message = $offer->is_active ? 'تم تفعيل العرض' : 'تم إلغاء تفعيل العرض';
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * نسخ عرض
     */
    public function copy(Request $request, Offer $offer)
    {
        $this->authorize('view', $offer);
        
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $newOffer = $this->offerService->copyOffer($offer, $request->product_id);

        return redirect()->route('facility.offers.index')
            ->with('success', 'تم نسخ العرض بنجاح');
    }

    /**
     * إحصائيات العروض
     */
    public function statistics()
    {
        $facility = Auth::user()->facilities()->first();
        $stats = $this->offerService->getOfferStatistics($facility->id);

        return view('facility.offers.statistics', compact('stats'));
    }

    /**
     * تصدير العروض
     */
    public function export(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        $filters = $request->only(['type', 'status']);
        $filters['facility_id'] = $facility->id;
        
        $data = $this->offerService->exportOffers($filters);

        // إنشاء ملف CSV
        $filename = 'offers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // رؤوس الأعمدة
            fputcsv($file, [
                'ID', 'Product Title', 'Offer Type', 'Price',
                'Deposit Amount', 'Commission Rate', 'Commission Amount',
                'Is Active', 'Valid From', 'Valid To', 'Created At'
            ]);

            // البيانات
            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
