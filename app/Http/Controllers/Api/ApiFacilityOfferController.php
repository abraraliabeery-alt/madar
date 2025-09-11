<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\OfferService;
use App\Services\ContractService;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiFacilityOfferController extends Controller
{
    protected $offerService;
    protected $contractService;
    protected $reportService;

    public function __construct(
        OfferService $offerService,
        ContractService $contractService,
        FinancialReportService $reportService
    ) {
        $this->offerService = $offerService;
        $this->contractService = $contractService;
        $this->reportService = $reportService;
    }

    /**
     * عرض عروض المؤسسة
     * GET /api/facility/offers
     */
    public function index(Request $request)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            if (!$facilityId) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بالوصول'
                ], 403);
            }

            $query = Offer::where('facility_id', $facilityId)
                ->with(['product', 'translations']);

            // تطبيق الفلاتر
            if ($request->has('type')) {
                $query->byType($request->type);
            }

            if ($request->has('status')) {
                if ($request->status === 'active') {
                    $query->active();
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', false);
                }
            }

            if ($request->has('product_id')) {
                $query->where('product_id', $request->product_id);
            }

            $offers = $query->orderBy('created_at', 'desc')->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $offers,
                'message' => 'تم جلب العروض بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب العروض: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إنشاء عرض جديد
     * POST /api/facility/offers
     */
    public function store(Request $request)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            if (!$facilityId) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بالوصول'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'offer_type' => 'required|in:sale,rent_monthly,rent_yearly,rent_daily',
                'price' => 'required|numeric|min:0',
                'deposit_amount' => 'nullable|numeric|min:0',
                'commission_rate' => 'nullable|numeric|between:0,1',
                'commission_amount' => 'nullable|numeric|min:0',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
                'valid_from' => 'nullable|date',
                'valid_to' => 'nullable|date|after:valid_from',
                'terms_conditions' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            // التحقق من أن المنتج ينتمي للمؤسسة
            $product = Product::where('facility_id', $facilityId)
                ->findOrFail($request->product_id);

            $offerData = array_merge($request->all(), [
                'facility_id' => $facilityId,
                'created_by' => Auth::id(),
                'is_active' => $request->is_active ?? true,
                'is_featured' => $request->is_featured ?? false,
                'currency' => $request->currency ?? 'SAR',
            ]);

            $offer = $this->offerService->createOffer($offerData);

            return response()->json([
                'success' => true,
                'data' => $offer->load(['product', 'translations']),
                'message' => 'تم إنشاء العرض بنجاح'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إنشاء العرض: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض تفاصيل عرض محدد
     * GET /api/facility/offers/{id}
     */
    public function show($id)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $offer = Offer::where('facility_id', $facilityId)
                ->with(['product', 'translations', 'contracts'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $offer,
                'message' => 'تم جلب تفاصيل العرض بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'العرض غير موجود'
            ], 404);
        }
    }

    /**
     * تحديث عرض موجود
     * PUT /api/facility/offers/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $offer = Offer::where('facility_id', $facilityId)->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'offer_type' => 'sometimes|in:sale,rent_monthly,rent_yearly,rent_daily',
                'price' => 'sometimes|numeric|min:0',
                'currency' => 'sometimes|string|max:3',
                'deposit_amount' => 'nullable|numeric|min:0',
                'commission_rate' => 'nullable|numeric|between:0,1',
                'commission_amount' => 'nullable|numeric|min:0',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
                'valid_from' => 'nullable|date',
                'valid_to' => 'nullable|date|after:valid_from',
                'terms_conditions' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updatedOffer = $this->offerService->updateOffer($offer, $request->all());

            return response()->json([
                'success' => true,
                'data' => $updatedOffer->load(['product', 'translations']),
                'message' => 'تم تحديث العرض بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحديث العرض: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تفعيل/إلغاء تفعيل العرض
     * POST /api/facility/offers/{id}/toggle-status
     */
    public function toggleStatus($id)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $offer = Offer::where('facility_id', $facilityId)->findOrFail($id);
            
            $updatedOffer = $this->offerService->toggleOfferStatus($offer);

            return response()->json([
                'success' => true,
                'data' => $updatedOffer,
                'message' => $updatedOffer->is_active ? 'تم تفعيل العرض' : 'تم إلغاء تفعيل العرض'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحديث حالة العرض: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف عرض
     * DELETE /api/facility/offers/{id}
     */
    public function destroy($id)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $offer = Offer::where('facility_id', $facilityId)->findOrFail($id);
            
            // التحقق من عدم وجود عقود مرتبطة
            if ($offer->contracts()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف العرض - يوجد عقود مرتبطة به'
                ], 422);
            }

            $offer->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العرض بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في حذف العرض: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض العقود الخاصة بالمؤسسة
     * GET /api/facility/contracts
     */
    public function getContracts(Request $request)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $query = Contract::where('facility_id', $facilityId)
                ->with(['product', 'offer', 'user', 'owner', 'invoices', 'payments']);

            // تطبيق الفلاتر
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('type')) {
                $query->byType($request->type);
            }

            $contracts = $query->orderBy('created_at', 'desc')->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $contracts,
                'message' => 'تم جلب العقود بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب العقود: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * الموافقة على عقد
     * POST /api/facility/contracts/{id}/approve
     */
    public function approveContract($id)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $contract = Contract::where('facility_id', $facilityId)
                ->where('status', 'draft')
                ->findOrFail($id);

            $this->contractService->updateContractStatus($contract, 'active');

            return response()->json([
                'success' => true,
                'data' => $contract->load(['product', 'offer', 'user']),
                'message' => 'تم الموافقة على العقد بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في الموافقة على العقد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * رفض عقد
     * POST /api/facility/contracts/{id}/reject
     */
    public function rejectContract(Request $request, $id)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $contract = Contract::where('facility_id', $facilityId)
                ->where('status', 'draft')
                ->findOrFail($id);

            $reason = $request->input('reason', 'تم الرفض من قبل المؤسسة');
            
            $this->contractService->cancelContract($contract, $reason);

            return response()->json([
                'success' => true,
                'message' => 'تم رفض العقد'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في رفض العقد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تأكيد دفعة
     * POST /api/facility/payments/{id}/confirm
     */
    public function confirmPayment($id)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $payment = Payment::where('facility_id', $facilityId)
                ->where('status', 'pending')
                ->findOrFail($id);

            $payment->confirm();

            return response()->json([
                'success' => true,
                'data' => $payment->load(['invoice', 'contract']),
                'message' => 'تم تأكيد الدفعة بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تأكيد الدفعة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * رفض دفعة
     * POST /api/facility/payments/{id}/reject
     */
    public function rejectPayment(Request $request, $id)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $payment = Payment::where('facility_id', $facilityId)
                ->where('status', 'pending')
                ->findOrFail($id);

            $payment->fail();

            return response()->json([
                'success' => true,
                'message' => 'تم رفض الدفعة'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في رفض الدفعة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تقرير مالي للمؤسسة
     * GET /api/facility/financial-report
     */
    public function getFinancialReport(Request $request)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : null;
            $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : null;

            $report = $this->reportService->getFacilityFinancialSummary($facilityId, $startDate, $endDate);

            return response()->json([
                'success' => true,
                'data' => $report,
                'message' => 'تم جلب التقرير المالي بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب التقرير المالي: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إحصائيات العروض
     * GET /api/facility/offers/statistics
     */
    public function getOfferStatistics()
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $statistics = $this->offerService->getOfferStatistics($facilityId);

            return response()->json([
                'success' => true,
                'data' => $statistics,
                'message' => 'تم جلب إحصائيات العروض بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب الإحصائيات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * نسخ عرض لمنتج آخر
     * POST /api/facility/offers/{id}/copy
     */
    public function copyOffer(Request $request, $id)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $validator = Validator::make($request->all(), [
                'target_product_id' => 'required|exists:products,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $offer = Offer::where('facility_id', $facilityId)->findOrFail($id);
            
            // التحقق من أن المنتج المستهدف ينتمي للمؤسسة
            $targetProduct = Product::where('facility_id', $facilityId)
                ->findOrFail($request->target_product_id);

            $newOffer = $this->offerService->copyOffer($offer, $request->target_product_id);

            return response()->json([
                'success' => true,
                'data' => $newOffer->load(['product', 'translations']),
                'message' => 'تم نسخ العرض بنجاح'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في نسخ العرض: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث أسعار العروض
     * POST /api/facility/offers/bulk-update-prices
     */
    public function bulkUpdatePrices(Request $request)
    {
        try {
            $facilityId = Auth::user()->facility_id;
            
            $validator = Validator::make($request->all(), [
                'percentage' => 'required|numeric|between:1,100',
                'operation' => 'required|in:increase,decrease',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updatedOffers = $this->offerService->updateOfferPrices(
                $facilityId,
                $request->percentage,
                $request->operation
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'updated_count' => $updatedOffers->count(),
                    'operation' => $request->operation,
                    'percentage' => $request->percentage
                ],
                'message' => 'تم تحديث الأسعار بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحديث الأسعار: ' . $e->getMessage()
            ], 500);
        }
    }
}
