<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\AccountingEntry;
use App\Models\Facility;
use App\Models\User;
use App\Services\ContractService;
use App\Services\OfferService;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ClientFinancialController extends Controller
{
    protected $contractService;
    protected $offerService;
    protected $reportService;

    public function __construct(
        ContractService $contractService,
        OfferService $offerService,
        FinancialReportService $reportService
    ) {
        $this->middleware('auth');
        $this->contractService = $contractService;
        $this->offerService = $offerService;
        $this->reportService = $reportService;
    }

    /**
     * لوحة معلومات العميل المالية
     */
    public function dashboard(Request $request)
    {
        try {
            $user = Auth::user();
            
            // إحصائيات العميل
            $totalContracts = Contract::where('user_id', $user->id)->count();
            $activeContracts = Contract::where('user_id', $user->id)->where('status', 'active')->count();
            $completedContracts = Contract::where('user_id', $user->id)->where('status', 'completed')->count();
            
            // المبالغ المالية
            $totalContractValue = Contract::where('user_id', $user->id)->sum('total_amount');
            $totalPaidAmount = Payment::whereHas('contract', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'confirmed')->sum('amount');
            $remainingAmount = $totalContractValue - $totalPaidAmount;
            
            // الفواتير
            $pendingInvoices = Invoice::whereHas('contract', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', '!=', 'paid')->count();
            
            $overdueInvoices = Invoice::whereHas('contract', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'overdue')->count();
            
            // أحدث العقود
            $recentContracts = Contract::with(['product', 'offer', 'facility'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            // أحدث المدفوعات
            $recentPayments = Payment::with(['contract.product'])
                ->whereHas('contract', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            // الفواتير القادمة
            $upcomingInvoices = Invoice::with(['contract.product'])
                ->whereHas('contract', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where('status', '!=', 'paid')
                ->where('due_date', '>', now())
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get();
            
            // رسم بياني للمدفوعات - آخر 6 أشهر
            $monthlyPayments = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $amount = Payment::whereHas('contract', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where('status', 'confirmed')
                ->whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month)
                ->sum('amount');
                
                $monthlyPayments[] = [
                    'month' => $month->format('M Y'),
                    'amount' => $amount
                ];
            }

            return view('client.financial.dashboard', compact(
                'totalContracts', 'activeContracts', 'completedContracts',
                'totalContractValue', 'totalPaidAmount', 'remainingAmount',
                'pendingInvoices', 'overdueInvoices', 'recentContracts',
                'recentPayments', 'upcomingInvoices', 'monthlyPayments'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل لوحة المعلومات: ' . $e->getMessage());
        }
    }

    /**
     * عرض العروض المتاحة
     */
    public function offers(Request $request)
    {
        try {
            $query = Offer::with(['product', 'facility'])
                ->active()
                ->whereHas('product', function($q) {
                    $q->where('is_active', true);
                });

            // تطبيق الفلاتر
            if ($request->has('facility_id') && $request->facility_id) {
                $query->where('facility_id', $request->facility_id);
            }

            if ($request->has('type') && $request->type) {
                $query->byType($request->type);
            }

            if ($request->has('min_price') && $request->min_price) {
                $query->where('price', '>=', $request->min_price);
            }

            if ($request->has('max_price') && $request->max_price) {
                $query->where('price', '<=', $request->max_price);
            }

            if ($request->has('search') && $request->search) {
                $query->whereHas('product', function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('address', 'like', '%' . $request->search . '%');
                });
            }

            // ترتيب النتائج
            $sortBy = $request->sort_by ?? 'created_at';
            $sortOrder = $request->sort_order ?? 'desc';
            
            if ($sortBy === 'price') {
                $query->orderBy('price', $sortOrder);
            } elseif ($sortBy === 'featured') {
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
            } else {
                $query->orderBy('created_at', $sortOrder);
            }

            $offers = $query->paginate(12);

            // المؤسسات للفلتر
            $facilities = Facility::where('is_active', true)->get();

            // إحصائيات سريعة
            $offerStats = [
                'total_offers' => Offer::active()->count(),
                'sale_offers' => Offer::active()->byType('sale')->count(),
                'rent_offers' => Offer::active()->whereIn('offer_type', ['rent_monthly', 'rent_yearly', 'rent_daily'])->count(),
                'avg_price' => Offer::active()->avg('price'),
                'min_price' => Offer::active()->min('price'),
                'max_price' => Offer::active()->max('price'),
            ];

            return view('client.financial.offers', compact('offers', 'facilities', 'offerStats'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب العروض: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل عرض محدد
     */
    public function offerDetails($id)
    {
        try {
            $offer = Offer::with([
                'product.category', 'product.features', 'product.gallery',
                'facility', 'translations'
            ])->findOrFail($id);

            // التحقق من أن العرض نشط
            if (!$offer->is_active || !$offer->product->is_active) {
                return redirect()->route('client.financial.offers')
                    ->with('error', 'هذا العرض غير متاح حالياً');
            }

            // عروض مشابهة
            $similarOffers = Offer::with(['product', 'facility'])
                ->active()
                ->where('id', '!=', $offer->id)
                ->where('offer_type', $offer->offer_type)
                ->whereHas('product', function($q) use ($offer) {
                    $q->where('category_id', $offer->product->category_id)
                      ->where('is_active', true);
                })
                ->limit(6)
                ->get();

            // التحقق من وجود عقد سابق لهذا المنتج
            $existingContract = Contract::where('user_id', Auth::id())
                ->where('product_id', $offer->product_id)
                ->whereIn('status', ['draft', 'active'])
                ->first();

            return view('client.financial.offer-details', compact(
                'offer', 'similarOffers', 'existingContract'
            ));

        } catch (\Exception $e) {
            return redirect()->route('client.financial.offers')
                ->with('error', 'العرض غير موجود');
        }
    }

    /**
     * طلب عقد جديد
     */
    public function requestContract(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'offer_id' => 'required|exists:offers,id',
                'notes' => 'nullable|string|max:1000',
                'preferred_start_date' => 'nullable|date|after:today',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $offer = Offer::findOrFail($request->offer_id);
            $user = Auth::user();

            // التحقق من أن العرض متاح
            if (!$offer->is_active || !$offer->product->is_active) {
                return redirect()->back()->with('error', 'هذا العرض غير متاح حالياً');
            }

            // التحقق من عدم وجود عقد سابق
            $existingContract = Contract::where('user_id', $user->id)
                ->where('product_id', $offer->product_id)
                ->whereIn('status', ['draft', 'active'])
                ->first();

            if ($existingContract) {
                return redirect()->back()->with('error', 'لديك عقد سابق لهذا العقار');
            }

            // إنشاء العقد
            $contractData = [
                'product_id' => $offer->product_id,
                'offer_id' => $offer->id,
                'user_id' => $user->id,
                'owner_id' => $offer->product->user_id,
                'facility_id' => $offer->facility_id,
                'contract_type' => $offer->offer_type === 'sale' ? 'sale' : 'rent',
                'total_amount' => $offer->price,
                'deposit_amount' => $offer->deposit_amount,
                'commission_rate' => $offer->commission_rate,
                'commission_amount' => $offer->commission_amount,
                'start_date' => $request->preferred_start_date ? Carbon::parse($request->preferred_start_date) : now(),
                'notes' => $request->notes,
                'status' => 'draft',
                'created_by' => $user->id,
            ];

            $contract = $this->contractService->createContract($contractData);

            return redirect()->route('client.financial.contract-details', $contract->id)
                ->with('success', 'تم إنشاء طلب العقد بنجاح. في انتظار موافقة المؤسسة.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في إنشاء العقد: ' . $e->getMessage());
        }
    }

    /**
     * عرض عقود العميل
     */
    public function contracts(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Contract::with(['product', 'offer', 'facility', 'invoices', 'payments'])
                ->where('user_id', $user->id);

            // تطبيق الفلاتر
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('type') && $request->type) {
                $query->byType($request->type);
            }

            if ($request->has('facility_id') && $request->facility_id) {
                $query->where('facility_id', $request->facility_id);
            }

            $contracts = $query->orderBy('created_at', 'desc')->paginate(10);

            // المؤسسات للفلتر
            $facilities = Facility::whereHas('contracts', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->get();

            return view('client.financial.contracts', compact('contracts', 'facilities'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب العقود: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل عقد محدد
     */
    public function contractDetails($id)
    {
        try {
            $contract = Contract::with([
                'product', 'offer', 'user', 'owner', 'facility',
                'invoices', 'payments', 'accountingEntries'
            ])->where('user_id', Auth::id())->findOrFail($id);

            return view('client.financial.contract-details', compact('contract'));

        } catch (\Exception $e) {
            return redirect()->route('client.financial.contracts')
                ->with('error', 'العقد غير موجود');
        }
    }

    /**
     * إلغاء عقد
     */
    public function cancelContract($id)
    {
        try {
            $contract = Contract::where('user_id', Auth::id())->findOrFail($id);

            if ($contract->status !== 'draft') {
                return redirect()->back()->with('error', 'لا يمكن إلغاء هذا العقد');
            }

            $this->contractService->cancelContract($contract, 'إلغاء من قبل العميل');

            return redirect()->route('client.financial.contracts')
                ->with('success', 'تم إلغاء العقد بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في إلغاء العقد: ' . $e->getMessage());
        }
    }

    /**
     * عرض فواتير العميل
     */
    public function invoices(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Invoice::with(['contract.product', 'facility'])
                ->whereHas('contract', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });

            // تطبيق الفلاتر
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('type') && $request->type) {
                $query->where('invoice_type', $request->type);
            }

            if ($request->has('contract_id') && $request->contract_id) {
                $query->where('contract_id', $request->contract_id);
            }

            $invoices = $query->orderBy('created_at', 'desc')->paginate(15);

            // عقود العميل للفلتر
            $contracts = Contract::where('user_id', $user->id)->get();

            return view('client.financial.invoices', compact('invoices', 'contracts'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب الفواتير: ' . $e->getMessage());
        }
    }

    /**
     * إجراء دفعة جديدة
     */
    public function makePayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'invoice_id' => 'nullable|exists:invoices,id',
                'contract_id' => 'required|exists:contracts,id',
                'amount' => 'required|numeric|min:1',
                'payment_method' => 'required|in:cash,bank_transfer,credit_card,check,online',
                'notes' => 'nullable|string|max:500',
                'reference_number' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = Auth::user();
            $contract = Contract::where('user_id', $user->id)->findOrFail($request->contract_id);

            // التحقق من المبلغ
            $remainingAmount = $contract->getRemainingAmount();
            if ($request->amount > $remainingAmount) {
                return redirect()->back()->with('error', 'المبلغ أكبر من المبلغ المتبقي');
            }

            // إنشاء الدفعة
            $paymentData = [
                'invoice_id' => $request->invoice_id,
                'contract_id' => $contract->id,
                'facility_id' => $contract->facility_id,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'currency' => 'SAR',
                'payment_date' => now(),
                'reference_number' => $request->reference_number ?? 'PAY-' . time(),
                'notes' => $request->notes,
                'status' => 'pending',
                'created_by' => $user->id,
            ];

            $payment = Payment::create($paymentData);

            return redirect()->route('client.financial.payments')
                ->with('success', 'تم إرسال الدفعة بنجاح. في انتظار التأكيد من المؤسسة.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في إجراء الدفعة: ' . $e->getMessage());
        }
    }

    /**
     * عرض مدفوعات العميل
     */
    public function payments(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Payment::with(['invoice', 'contract.product', 'facility'])
                ->whereHas('contract', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });

            // تطبيق الفلاتر
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('method') && $request->method) {
                $query->byMethod($request->method);
            }

            if ($request->has('contract_id') && $request->contract_id) {
                $query->where('contract_id', $request->contract_id);
            }

            $payments = $query->orderBy('created_at', 'desc')->paginate(15);

            // عقود العميل للفلتر
            $contracts = Contract::where('user_id', $user->id)->get();

            return view('client.financial.payments', compact('payments', 'contracts'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب المدفوعات: ' . $e->getMessage());
        }
    }

    /**
     * الملخص المالي للعميل
     */
    public function financialSummary(Request $request)
    {
        try {
            $user = Auth::user();
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfYear();
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfYear();

            // تقرير العميل
            $report = $this->reportService->getCustomerReport($user->id, null, $startDate, $endDate);

            // إحصائيات إضافية
            $totalContracts = Contract::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $totalPayments = Payment::whereHas('contract', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'confirmed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

            $pendingPayments = Payment::whereHas('contract', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'pending')
            ->sum('amount');

            // المدفوعات الشهرية
            $monthlyPayments = [];
            $currentDate = $startDate->copy();
            
            while ($currentDate <= $endDate) {
                $amount = Payment::whereHas('contract', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->where('status', 'confirmed')
                ->whereYear('payment_date', $currentDate->year)
                ->whereMonth('payment_date', $currentDate->month)
                ->sum('amount');

                $monthlyPayments[] = [
                    'month' => $currentDate->format('M Y'),
                    'amount' => $amount
                ];

                $currentDate->addMonth();
            }

            return view('client.financial.summary', compact(
                'report', 'totalContracts', 'totalPayments', 'pendingPayments',
                'monthlyPayments', 'startDate', 'endDate'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب الملخص المالي: ' . $e->getMessage());
        }
    }

    /**
     * تحميل فاتورة
     */
    public function downloadInvoice($id)
    {
        try {
            $invoice = Invoice::whereHas('contract', function($q) {
                $q->where('user_id', Auth::id());
            })->findOrFail($id);

            // هنا يمكن إضافة منطق تحميل الفاتورة
            // مثلاً تحويل إلى PDF وتحميل

            return response()->download($invoicePath ?? '');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل الفاتورة');
        }
    }

    /**
     * طباعة عقد
     */
    public function printContract($id)
    {
        try {
            $contract = Contract::with([
                'product', 'offer', 'user', 'owner', 'facility'
            ])->where('user_id', Auth::id())->findOrFail($id);

            return view('client.financial.contract-print', compact('contract'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في طباعة العقد');
        }
    }
}
