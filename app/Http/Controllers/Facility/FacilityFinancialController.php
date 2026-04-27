<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\AccountingEntry;
use App\Models\User;
use App\Services\ContractService;
use App\Services\OfferService;
use App\Services\FinancialReportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class FacilityFinancialController extends Controller
{
    protected $contractService;
    protected $offerService;
    protected $financialReportService;

    public function __construct(
        ContractService $contractService,
        OfferService $offerService,
        FinancialReportService $financialReportService
    ) {
        $this->contractService = $contractService;
        $this->offerService = $offerService;
        $this->financialReportService = $financialReportService;
    }

    /**
     * عرض لوحة المعلومات المالية
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', __('facility_management.create_facility_first'));
        }

        // الإحصائيات الأساسية
        $stats = [
            'total_offers' => $facility->offers()->count(),
            'active_offers' => $facility->offers()->where('status', 'active')->count(),
            'total_contracts' => $facility->contracts()->count(),
            'active_contracts' => $facility->contracts()->where('status', 'active')->count(),
            'pending_contracts' => $facility->contracts()->where('status', 'draft')->count(),
            'total_revenue' => $facility->contracts()
                ->where('status', 'active')
                ->sum(DB::raw('offer_price + COALESCE(deposit_amount, 0)')),
            'received_payments' => $facility->payments()
                ->where('status', 'confirmed')
                ->sum('amount'),
            'pending_payments' => $facility->payments()
                ->where('status', 'pending')
                ->sum('amount'),
        ];

        // الذمم المدينة والمتأخرات (اعتماداً على الفواتير الحالية)
        $invoicesQuery = $facility->invoices();
        $receivablesTotal = (float) $invoicesQuery->sum(DB::raw('COALESCE(amount,0) - COALESCE(paid_amount,0)'));
        $overdueInvoicesCount = (int) $facility->invoices()
            ->where('status', 'sent')
            ->where('due_date', '<', Carbon::now())
            ->count();
        $overdueAmount = (float) $facility->invoices()
            ->where('status', 'sent')
            ->where('due_date', '<', Carbon::now())
            ->sum(DB::raw('COALESCE(amount,0) - COALESCE(paid_amount,0)'));

        // أعمار الديون (aging) للفواتير المتأخرة
        $now = Carbon::now();
        $aging = [
            'd0_30' => (float) $facility->invoices()
                ->where('status', 'sent')
                ->whereBetween('due_date', [$now->copy()->subDays(30), $now])
                ->sum(DB::raw('COALESCE(amount,0) - COALESCE(paid_amount,0)')),
            'd31_60' => (float) $facility->invoices()
                ->where('status', 'sent')
                ->whereBetween('due_date', [$now->copy()->subDays(60), $now->copy()->subDays(31)])
                ->sum(DB::raw('COALESCE(amount,0) - COALESCE(paid_amount,0)')),
            'd61_90' => (float) $facility->invoices()
                ->where('status', 'sent')
                ->whereBetween('due_date', [$now->copy()->subDays(90), $now->copy()->subDays(61)])
                ->sum(DB::raw('COALESCE(amount,0) - COALESCE(paid_amount,0)')),
            'd90_plus' => (float) $facility->invoices()
                ->where('status', 'sent')
                ->where('due_date', '<', $now->copy()->subDays(90))
                ->sum(DB::raw('COALESCE(amount,0) - COALESCE(paid_amount,0)')),
        ];

        // معدل التحصيل = المدفوع المؤكد خلال آخر 30 يوم / إجمالي مستحقات آخر 30 يوم (افتراضيًا)
        $last30From = Carbon::now()->subDays(30);
        $collectedLast30 = (float) $facility->payments()
            ->where('status', 'confirmed')
            ->where('payment_date', '>=', $last30From)
            ->sum('amount');
        $dueLast30 = (float) $facility->invoices()
            ->where('created_at', '>=', $last30From)
            ->sum(DB::raw('COALESCE(amount,0)'));
        $collectionRate = $dueLast30 > 0 ? round(($collectedLast30 / $dueLast30) * 100, 1) : 0.0;

        // الرسوم البيانية - بيانات الإيرادات الشهرية لآخر 6 أشهر
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = $facility->payments()
                ->where('status', 'confirmed')
                ->whereYear('payment_date', $date->year)
                ->whereMonth('payment_date', $date->month)
                ->sum('amount');
            
            $monthlyRevenue[] = [
                'month' => $date->format('M'),
                'revenue' => $revenue
            ];
        }

        // أحدث العقود
        $recentContracts = $facility->contracts()
            ->with(['client', 'offer.product'])
            ->latest()
            ->take(5)
            ->get();

        // العقود المعلقة (تحتاج موافقة)
        $pendingContracts = $facility->contracts()
            ->with(['client', 'offer.product'])
            ->where('status', 'draft')
            ->latest()
            ->take(5)
            ->get();

        // المدفوعات الأخيرة
        $recentPayments = $facility->payments()
            ->with(['contract.client', 'contract.offer.product'])
            ->latest()
            ->take(5)
            ->get();

        // التنبيهات
        $alerts = [];

        // عقود معلقة
        if ($stats['pending_contracts'] > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => __('facility_management.pending_contracts_alert', ['count' => $stats['pending_contracts']]),
                'action_url' => route('facility.financial.contracts', ['status' => 'draft']),
                'action_text' => __('facility_management.review_contracts')
            ];
        }

        // مدفوعات معلقة
        if ($stats['pending_payments'] > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => __('facility_management.pending_payments_alert', ['amount' => number_format($stats['pending_payments'])]),
                'action_url' => route('facility.financial.payments', ['status' => 'pending']),
                'action_text' => __('facility_management.review_payments')
            ];
        }

        // عروض بدون عقود
        $offersWithoutContracts = $facility->offers()
            ->where('status', 'active')
            ->whereDoesntHave('contracts')
            ->count();

        if ($offersWithoutContracts > 5) {
            $alerts[] = [
                'type' => 'success',
                'message' => __('facility_management.active_offers_alert', ['count' => $offersWithoutContracts]),
                'action_url' => route('facility.financial.offers'),
                'action_text' => __('facility_management.view_offers')
            ];
        }

        // حساب معدل النجاح
        $successRate = $stats['total_offers'] > 0 
            ? round(($stats['active_contracts'] / $stats['total_offers']) * 100, 1)
            : 0;

        return view('facility.financial.dashboard', compact(
            'facility',
            'stats',
            'monthlyRevenue',
            'recentContracts',
            'pendingContracts', 
            'recentPayments',
            'alerts',
            'successRate',
            'receivablesTotal',
            'overdueInvoicesCount',
            'overdueAmount',
            'aging',
            'collectionRate'
        ));
    }

    /**
     * إدارة العروض
     */
    public function offers(Request $request)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        $query = $facility->offers()
            ->with(['product']);

        // الفلترة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('offer_type')) {
            $query->where('offer_type', $request->offer_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // الترتيب
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $offers = $query->paginate(12)->appends($request->query());

        // إحصائيات سريعة
        $stats = [
            'total' => $facility->offers()->count(),
            'active' => $facility->offers()->where('status', 'active')->count(),
            'inactive' => $facility->offers()->where('status', 'inactive')->count(),
            'sale_offers' => $facility->offers()->where('offer_type', 'sale')->count(),
            'rent_offers' => $facility->offers()->where('offer_type', 'rent')->count(),
            'avg_price' => $facility->offers()->avg('price'),
        ];

        return view('facility.financial.offers', compact('facility', 'offers', 'stats'));
    }

    /**
     * إنشاء عرض جديد
     */
    public function createOffer(Request $request)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'offer_type' => 'required|in:sale,monthly_rent,yearly_rent,daily_rent',
                'price' => 'required|numeric|min:0',
                'deposit_amount' => 'nullable|numeric|min:0',
                'commission_rate' => 'required|numeric|min:0|max:100',
                'valid_from' => 'required|date',
                'valid_to' => 'nullable|date|after:valid_from',
                'terms_conditions' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ]);

            try {
                $offer = $this->offerService->createOffer($validated, $facility->id);
                
                return redirect()
                    ->route('facility.financial.offers')
                    ->with('success', __('facility_management.offer_created_successfully'));
            } catch (\Exception $e) {
                return back()
                    ->withInput()
                    ->with('error', __('facility_management.offer_creation_failed') . ': ' . $e->getMessage());
            }
        }

        // الحصول على المنتجات المتاحة (بدون عروض نشطة)
        $products = $facility->products()
            ->whereDoesntHave('offers', function ($query) {
                $query->where('status', 'active');
            })
            ->get();

        return view('facility.financial.create-offer', compact('facility', 'products'));
    }

    /**
     * تحديث عرض
     */
    public function updateOffer(Request $request, $id)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();
        $offer = $facility->offers()->findOrFail($id);

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'offer_type' => 'required|in:sale,monthly_rent,yearly_rent,daily_rent',
                'price' => 'required|numeric|min:0',
                'deposit_amount' => 'nullable|numeric|min:0',
                'commission_rate' => 'required|numeric|min:0|max:100',
                'valid_from' => 'required|date',
                'valid_to' => 'nullable|date|after:valid_from',
                'terms_conditions' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ]);

            try {
                $this->offerService->updateOffer($offer->id, $validated);
                
                return redirect()
                    ->route('facility.financial.offers')
                    ->with('success', __('facility_management.offer_updated_successfully'));
            } catch (\Exception $e) {
                return back()
                    ->withInput()
                    ->with('error', __('facility_management.offer_update_failed') . ': ' . $e->getMessage());
            }
        }

        return view('facility.financial.edit-offer', compact('facility', 'offer'));
    }

    /**
     * تبديل حالة العرض
     */
    public function toggleOfferStatus($id)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();
        $offer = $facility->offers()->findOrFail($id);

        try {
            $this->offerService->toggleOfferStatus($offer->id);
            
            return response()->json([
                'success' => true,
                'message' => __('facility_management.offer_status_updated'),
                'new_status' => $offer->fresh()->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('facility_management.offer_status_update_failed') . ': ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * إدارة العقود
     */
    public function contracts(Request $request)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        $query = $facility->contracts()
            ->with(['client', 'offer.product']);

        // الفلترة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('contract_type')) {
            $query->whereHas('offer', function ($q) use ($request) {
                $q->where('offer_type', $request->contract_type);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($clientQuery) use ($search) {
                      $clientQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('offer.product', function ($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // الترتيب
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $contracts = $query->paginate(10)->appends($request->query());

        // إحصائيات سريعة
        $stats = [
            'total' => $facility->contracts()->count(),
            'draft' => $facility->contracts()->where('status', 'draft')->count(),
            'active' => $facility->contracts()->where('status', 'active')->count(),
            'completed' => $facility->contracts()->where('status', 'completed')->count(),
            'cancelled' => $facility->contracts()->where('status', 'cancelled')->count(),
            'total_value' => $facility->contracts()->where('status', 'active')->sum('total_amount'),
            'received_amount' => $facility->contracts()->where('status', 'active')->sum('paid_amount'),
        ];

        return view('facility.financial.contracts', compact('facility', 'contracts', 'stats'));
    }

    /**
     * تفاصيل العقد
     */
    public function contractDetails($id)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();
        $contract = $facility->contracts()
            ->with([
                'client', 
                'offer.product',
                'invoices' => function ($query) {
                    $query->orderBy('due_date', 'asc');
                },
                'payments' => function ($query) {
                    $query->orderBy('payment_date', 'desc');
                },
                'accountingEntries' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->findOrFail($id);

        // حساب الإحصائيات
        $paymentStats = [
            'total_amount' => $contract->total_amount,
            'paid_amount' => $contract->paid_amount,
            'remaining_amount' => $contract->remaining_amount,
            'payment_progress' => $contract->total_amount > 0 
                ? round(($contract->paid_amount / $contract->total_amount) * 100, 1) 
                : 0,
            'total_invoices' => $contract->invoices->count(),
            'paid_invoices' => $contract->invoices->where('status', 'paid')->count(),
            'overdue_invoices' => $contract->invoices
                ->where('status', 'sent')
                ->where('due_date', '<', Carbon::now())
                ->count(),
        ];

        return view('facility.financial.contract-details', compact(
            'facility', 
            'contract', 
            'paymentStats'
        ));
    }

    /**
     * تحديث حالة العقد
     */
    public function updateContractStatus(Request $request, $id)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();
        $contract = $facility->contracts()->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,active,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        try {
            if ($validated['status'] === 'active' && $contract->status === 'draft') {
                // تفعيل العقد وإنشاء الفواتير
                $this->contractService->activateContract($contract->id, $validated['notes'] ?? null);
            } elseif ($validated['status'] === 'cancelled') {
                // إلغاء العقد
                $this->contractService->cancelContract($contract->id, $validated['notes'] ?? null);
            } else {
                // تحديث عادي
                $contract->update([
                    'status' => $validated['status'],
                    'notes' => $validated['notes']
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => __('facility_management.contract_status_updated'),
                'new_status' => $contract->fresh()->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('facility_management.contract_status_update_failed') . ': ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * إدارة المدفوعات
     */
    public function payments(Request $request)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        $query = $facility->payments()
            ->with(['contract.client', 'contract.offer.product']);

        // الفلترة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('contract.client', function ($clientQuery) use ($search) {
                      $clientQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }

        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // الترتيب
        $sortBy = $request->get('sort_by', 'payment_date');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $payments = $query->paginate(15)->appends($request->query());

        // إحصائيات سريعة
        $stats = [
            'total' => $facility->payments()->count(),
            'pending' => $facility->payments()->where('status', 'pending')->count(),
            'confirmed' => $facility->payments()->where('status', 'confirmed')->count(),
            'failed' => $facility->payments()->where('status', 'failed')->count(),
            'total_amount' => $facility->payments()->where('status', 'confirmed')->sum('amount'),
            'pending_amount' => $facility->payments()->where('status', 'pending')->sum('amount'),
        ];

        return view('facility.financial.payments', compact('facility', 'payments', 'stats'));
    }

    /**
     * تأكيد دفعة
     */
    public function confirmPayment($id)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();
        $payment = $facility->payments()->findOrFail($id);

        try {
            DB::transaction(function () use ($payment) {
                // تحديث حالة الدفعة
                $payment->update(['status' => 'confirmed']);

                // تحديث العقد
                $contract = $payment->contract;
                $contract->increment('paid_amount', $payment->amount);

                // تحديث الفاتورة إن وجدت
                if ($payment->invoice_id) {
                    $invoice = $payment->invoice;
                    $invoice->increment('paid_amount', $payment->amount);
                    
                    // تحديث حالة الفاتورة إذا تم دفعها بالكامل
                    if ($invoice->paid_amount >= $invoice->amount) {
                        $invoice->update(['status' => 'paid']);
                    }
                }

                // إنشاء قيد محاسبي
                AccountingEntry::create([
                    'contract_id' => $contract->id,
                    'entry_type' => 'payment_received',
                    'account_type' => 'cash',
                    'amount' => $payment->amount,
                    'description' => "تأكيد دفعة رقم {$payment->id} للعقد {$contract->contract_number}",
                    'entry_date' => Carbon::now(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => __('facility_management.payment_confirmed_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('facility_management.payment_confirmation_failed') . ': ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * رفض دفعة
     */
    public function rejectPayment(Request $request, $id)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();
        $payment = $facility->payments()->findOrFail($id);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $payment->update([
                'status' => 'failed',
                'notes' => $validated['rejection_reason']
            ]);

            return response()->json([
                'success' => true,
                'message' => __('facility_management.payment_rejected_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('facility_management.payment_rejection_failed') . ': ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * التقارير المالية
     */
    public function reports(Request $request)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // إحصائيات عامة
        $stats = [
            'total_revenue' => $facility->payments()
                ->where('status', 'confirmed')
                ->whereBetween('payment_date', [$dateFrom, $dateTo])
                ->sum('amount'),
            'total_contracts' => $facility->contracts()
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count(),
            'active_contracts' => $facility->contracts()
                ->where('status', 'active')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count(),
            'commission_earned' => 0, // سيتم حسابها لاحقاً
        ];

        // حساب العمولات
        $stats['commission_earned'] = $facility->contracts()
            ->where('status', 'active')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get()
            ->sum(function ($contract) {
                return ($contract->total_amount * $contract->offer->commission_rate) / 100;
            });

        // بيانات الرسوم البيانية
        $monthlyData = $this->financialReportService->getFacilityMonthlyRevenue($facility->id, $dateFrom, $dateTo);
        $contractsByStatus = $facility->contracts()
            ->selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $topProducts = $facility->contracts()
            ->with('offer.product')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('offer_id, COUNT(*) as contract_count')
            ->groupBy('offer_id')
            ->orderBy('contract_count', 'desc')
            ->take(5)
            ->get();

        return view('facility.financial.reports', compact(
            'facility', 
            'stats', 
            'monthlyData',
            'contractsByStatus',
            'topProducts',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * تصدير التقارير
     */
    public function exportReports(Request $request)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        $format = $request->get('format', 'pdf');
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $data = $this->financialReportService->generateFacilityReport($facility->id, $dateFrom, $dateTo);

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('facility.financial.report-pdf', compact('facility', 'data', 'dateFrom', 'dateTo'));
            return $pdf->download("facility-report-{$dateFrom}-to-{$dateTo}.pdf");
        } elseif ($format === 'excel') {
            // تنفيذ تصدير Excel
            return $this->exportToExcel($data, $facility, $dateFrom, $dateTo);
        }

        return back()->with('error', __('facility_management.invalid_export_format'));
    }

    /**
     * تصدير لـ Excel
     */
    private function exportToExcel($data, $facility, $dateFrom, $dateTo)
    {
        $filename = "facility-report-{$dateFrom}-to-{$dateTo}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // العناوين
            fputcsv($file, [
                __('facility_management.contract_number'),
                __('facility_management.client_name'),
                __('facility_management.product_name'),
                __('facility_management.contract_type'),
                __('facility_management.total_amount'),
                __('facility_management.paid_amount'),
                __('facility_management.status'),
                __('facility_management.created_date'),
            ]);

            // البيانات
            foreach ($data['contracts'] as $contract) {
                fputcsv($file, [
                    $contract->contract_number,
                    $contract->client->name,
                    $contract->offer->product->name,
                    $contract->offer->offer_type,
                    $contract->total_amount,
                    $contract->paid_amount,
                    $contract->status,
                    $contract->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * القيود المحاسبية
     */
    public function accountingEntries(Request $request)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        $query = AccountingEntry::whereHas('contract', function ($q) use ($facility) {
            $q->where('facility_id', $facility->id);
        })->with(['contract.client', 'contract.offer.product']);

        // الفلترة
        if ($request->filled('entry_type')) {
            $query->where('entry_type', $request->entry_type);
        }

        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('entry_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('entry_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('contract', function ($contractQuery) use ($search) {
                      $contractQuery->where('contract_number', 'like', "%{$search}%");
                  });
            });
        }

        // الترتيب
        $sortBy = $request->get('sort_by', 'entry_date');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $entries = $query->paginate(20)->appends($request->query());

        // إحصائيات سريعة
        $stats = [
            'total_entries' => AccountingEntry::whereHas('contract', function ($q) use ($facility) {
                $q->where('facility_id', $facility->id);
            })->count(),
            'total_debits' => AccountingEntry::whereHas('contract', function ($q) use ($facility) {
                $q->where('facility_id', $facility->id);
            })->where('account_type', 'debit')->sum('amount'),
            'total_credits' => AccountingEntry::whereHas('contract', function ($q) use ($facility) {
                $q->where('facility_id', $facility->id);
            })->where('account_type', 'credit')->sum('amount'),
        ];

        return view('facility.financial.accounting-entries', compact('facility', 'entries', 'stats'));
    }
}
