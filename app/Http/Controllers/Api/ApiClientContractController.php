<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\ContractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiClientContractController extends Controller
{
    protected $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * عرض العروض المتاحة للعميل
     * GET /api/client/offers
     */
    public function getAvailableOffers(Request $request)
    {
        try {
            $filters = $request->only(['type', 'min_price', 'max_price', 'city_id', 'category_id']);
            
            $query = Offer::active()->valid()->with(['product', 'facility']);
            
            // تطبيق الفلاتر
            if (isset($filters['type'])) {
                $query->byType($filters['type']);
            }
            
            if (isset($filters['min_price'])) {
                $query->where('price', '>=', $filters['min_price']);
            }
            
            if (isset($filters['max_price'])) {
                $query->where('price', '<=', $filters['max_price']);
            }
            
            if (isset($filters['city_id'])) {
                $query->whereHas('product', function($q) use ($filters) {
                    $q->where('city_id', $filters['city_id']);
                });
            }
            
            if (isset($filters['category_id'])) {
                $query->whereHas('product', function($q) use ($filters) {
                    $q->where('category_id', $filters['category_id']);
                });
            }
            
            $offers = $query->paginate(20);
            
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
     * عرض تفاصيل عرض محدد
     * GET /api/client/offers/{id}
     */
    public function getOfferDetails($id)
    {
        try {
            $offer = Offer::with(['product', 'facility'])
                ->active()
                ->valid()
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $offer,
                'message' => 'تم جلب تفاصيل العرض بنجاح'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'العرض غير موجود أو غير متاح'
            ], 404);
        }
    }

    /**
     * طلب شراء أو إيجار (إنشاء عقد)
     * POST /api/client/contracts
     */
    public function requestContract(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'offer_id' => 'required|exists:offers,id',
                'start_date' => 'nullable|date|after:today',
                'end_date' => 'nullable|date|after:start_date',
                'notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $offer = Offer::active()->valid()->findOrFail($request->offer_id);
            
            // التحقق من توفر العقار
            $existingActiveContract = Contract::where('product_id', $offer->product_id)
                ->whereIn('status', ['active', 'draft'])
                ->first();
                
            if ($existingActiveContract) {
                return response()->json([
                    'success' => false,
                    'message' => 'العقار غير متاح حالياً - يوجد عقد نشط'
                ], 409);
            }

            // إعداد بيانات العقد
            $contractData = [
                'product_id' => $offer->product_id,
                'offer_id' => $offer->id,
                'user_id' => Auth::id(),
                'owner_id' => $offer->product->owner_user_id,
                'contract_type' => $offer->offer_type === 'sale' ? 'sale' : 'rent',
                'total_amount' => $offer->price,
                'currency' => $offer->currency,
                'deposit_amount' => $offer->deposit_amount,
                'commission_rate' => $offer->commission_rate,
                'commission_amount' => $offer->commission_amount,
                'start_date' => $request->start_date ?: now(),
                'end_date' => $request->end_date,
                'status' => 'draft',
                'facility_id' => $offer->facility_id,
                'created_by' => Auth::id(),
            ];

            $contract = $this->contractService->createContract($contractData);

            return response()->json([
                'success' => true,
                'data' => $contract->load(['product', 'offer', 'invoices']),
                'message' => 'تم إنشاء طلب العقد بنجاح - في انتظار الموافقة'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إنشاء العقد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض عقود العميل
     * GET /api/client/contracts
     */
    public function getMyContracts(Request $request)
    {
        try {
            $contracts = Contract::where('user_id', Auth::id())
                ->with(['product', 'offer', 'invoices', 'payments'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

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
     * عرض تفاصيل عقد محدد
     * GET /api/client/contracts/{id}
     */
    public function getContractDetails($id)
    {
        try {
            $contract = Contract::where('user_id', Auth::id())
                ->with(['product', 'offer', 'invoices', 'payments', 'accountingEntries'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $contract,
                'message' => 'تم جلب تفاصيل العقد بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'العقد غير موجود'
            ], 404);
        }
    }

    /**
     * عرض فواتير العميل
     * GET /api/client/invoices
     */
    public function getMyInvoices(Request $request)
    {
        try {
            $invoices = Invoice::whereHas('contract', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->with(['contract.product', 'payments'])
                ->orderBy('due_date', 'asc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $invoices,
                'message' => 'تم جلب الفواتير بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب الفواتير: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تسجيل دفعة
     * POST /api/client/payments
     */
    public function makePayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'invoice_id' => 'required|exists:invoices,id',
                'amount' => 'required|numeric|min:1',
                'payment_method' => 'required|in:cash,bank_transfer,credit_card,check,online',
                'reference_number' => 'nullable|string|max:100',
                'bank_name' => 'nullable|string|max:100',
                'check_number' => 'nullable|string|max:50',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            // التحقق من أن الفاتورة تخص العميل
            $invoice = Invoice::whereHas('contract', function($query) {
                    $query->where('user_id', Auth::id());
                })->findOrFail($request->invoice_id);

            // التحقق من أن المبلغ لا يتجاوز المبلغ المتبقي
            if ($request->amount > $invoice->remaining_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'المبلغ يتجاوز المبلغ المتبقي للفاتورة'
                ], 422);
            }

            $paymentData = [
                'invoice_id' => $invoice->id,
                'contract_id' => $invoice->contract_id,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'currency' => $invoice->currency,
                'payment_date' => now(),
                'reference_number' => $request->reference_number ?: 'PAY-' . strtoupper(uniqid()),
                'bank_name' => $request->bank_name,
                'check_number' => $request->check_number,
                'notes' => $request->notes,
                'status' => 'pending', // في انتظار التأكيد
                'facility_id' => $invoice->facility_id,
                'created_by' => Auth::id(),
            ];

            $payment = Payment::create($paymentData);

            return response()->json([
                'success' => true,
                'data' => $payment->load(['invoice', 'contract']),
                'message' => 'تم تسجيل الدفعة بنجاح - في انتظار التأكيد'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تسجيل الدفعة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض مدفوعات العميل
     * GET /api/client/payments
     */
    public function getMyPayments(Request $request)
    {
        try {
            $payments = Payment::whereHas('contract', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->with(['invoice', 'contract.product'])
                ->orderBy('payment_date', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $payments,
                'message' => 'تم جلب المدفوعات بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب المدفوعات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إلغاء عقد (إذا كان في حالة مسودة)
     * DELETE /api/client/contracts/{id}
     */
    public function cancelContract($id)
    {
        try {
            $contract = Contract::where('user_id', Auth::id())
                ->where('status', 'draft')
                ->findOrFail($id);

            $this->contractService->cancelContract($contract, 'تم الإلغاء بواسطة العميل');

            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء العقد بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء هذا العقد'
            ], 422);
        }
    }

    /**
     * تقرير مالي للعميل
     * GET /api/client/financial-summary
     */
    public function getFinancialSummary()
    {
        try {
            $contracts = Contract::where('user_id', Auth::id())->get();
            
            $totalValue = $contracts->sum('total_amount');
            $totalPaid = $contracts->sum(function ($contract) {
                return $contract->getTotalPaidAmount();
            });
            
            $pendingInvoices = Invoice::whereHas('contract', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->whereIn('status', ['draft', 'sent'])
                ->sum('remaining_amount');

            $overdueInvoices = Invoice::whereHas('contract', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->where('status', 'overdue')
                ->sum('remaining_amount');

            return response()->json([
                'success' => true,
                'data' => [
                    'total_contracts' => $contracts->count(),
                    'total_value' => $totalValue,
                    'total_paid' => $totalPaid,
                    'remaining_amount' => $totalValue - $totalPaid,
                    'pending_invoices' => $pendingInvoices,
                    'overdue_invoices' => $overdueInvoices,
                    'payment_rate' => $totalValue > 0 ? ($totalPaid / $totalValue) * 100 : 0,
                ],
                'message' => 'تم جلب الملخص المالي بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب الملخص المالي: ' . $e->getMessage()
            ], 500);
        }
    }
}
