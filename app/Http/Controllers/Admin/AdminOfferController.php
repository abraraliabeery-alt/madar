<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Facility;
use App\Services\OfferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOfferController extends Controller
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
        $query = Offer::with(['product', 'facility', 'translations']);

        // فلترة حسب المنشأة
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

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
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($q2) use ($search) {
                    $q2->whereHas('translations', function($q3) use ($search) {
                        $q3->where('title', 'like', "%{$search}%");
                    });
                })
                ->orWhereHas('facility', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        $offers = $query->paginate(20);
        $facilities = Facility::all();

        return view('admin.offers.index', compact('offers', 'facilities'));
    }

    /**
     * عرض تفاصيل العرض
     */
    public function show(Offer $offer)
    {
        $offer->load(['product', 'facility', 'translations', 'contracts']);
        
        return view('admin.offers.show', compact('offer'));
    }

    /**
     * عرض نموذج تعديل العرض
     */
    public function edit(Offer $offer)
    {
        $facilities = Facility::all();
        $products = Product::with('translations')->get();
        $offerTypes = [
            'sale' => 'بيع',
            'rent_monthly' => 'إيجار شهري',
            'rent_yearly' => 'إيجار سنوي',
            'rent_daily' => 'إيجار يومي',
        ];

        $offer->load('translations');
        
        return view('admin.offers.edit', compact('offer', 'facilities', 'products', 'offerTypes'));
    }

    /**
     * تحديث العرض
     */
    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'facility_id' => 'required|exists:facilities,id',
            'offer_type' => 'required|in:sale,rent_monthly,rent_yearly,rent_daily',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'deposit_amount' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after:valid_from',
            'terms_conditions' => 'nullable|string',
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

        return redirect()->route('admin.offers.index')
            ->with('success', 'تم تحديث العرض بنجاح');
    }

    /**
     * حذف العرض
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')
            ->with('success', 'تم حذف العرض بنجاح');
    }

    /**
     * تفعيل/إلغاء تفعيل العرض
     */
    public function toggleStatus(Offer $offer)
    {
        $offer = $this->offerService->toggleOfferStatus($offer);

        $message = $offer->is_active ? 'تم تفعيل العرض' : 'تم إلغاء تفعيل العرض';
        
        return redirect()->back()->with('success', $message);
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

        return redirect()->route('admin.offers.index')
            ->with('success', 'تم نسخ العرض بنجاح');
    }

    /**
     * إحصائيات العروض
     */
    public function statistics()
    {
        $stats = $this->offerService->getOfferStatistics();
        $facilities = Facility::all();

        return view('admin.offers.statistics', compact('stats', 'facilities'));
    }

    /**
     * تصدير العروض
     */
    public function export(Request $request)
    {
        $filters = $request->only(['type', 'facility_id', 'status']);
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
                'ID', 'Product Title', 'Facility Name', 'Offer Type', 'Price', 'Currency',
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
