<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\AccountingEntry;
use App\Models\Facility;
use App\Models\User;
use App\Services\OfferService;
use App\Services\ContractService;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminFinancialController extends Controller
{
    protected $offerService;
    protected $contractService;
    protected $reportService;

    public function __construct(
        OfferService $offerService,
        ContractService $contractService,
        FinancialReportService $reportService
    ) {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->offerService = $offerService;
        $this->contractService = $contractService;
        $this->reportService = $reportService;
    }

    /**
     * لوحة معلومات الأدمن المالية
     */
    public function dashboard(Request $request)
    {
        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

            // إحصائيات عامة
            $totalOffers = Offer::count();
            $activeOffers = Offer::active()->count();
            $totalContracts = Contract::count();
            $activeContracts = Contract::active()->count();
            $totalRevenue = AccountingEntry::byAccountType('revenue')->sum('amount');
            $totalCommissions = AccountingEntry::byAccountType('commission')->sum('amount');

            // إحصائيات للفترة المحددة
            $periodRevenue = AccountingEntry::byAccountType('revenue')
                ->whereBetween('entry_date', [$startDate, $endDate])
                ->sum('amount');

            $periodPayments = Payment::confirmed()
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->sum('amount');

            $periodContracts = Contract::whereBetween('created_at', [$startDate, $endDate])->count();

            // أفضل المؤسسات
            $topFacilities = Facility::withCount(['contracts'])
                ->with(['contracts' => function($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }])
                ->orderBy('contracts_count', 'desc')
                ->limit(5)
                ->get();

            // العقود المعلقة
            $pendingContracts = Contract::where('status', 'draft')->count();
            
            // الفواتير المتأخرة
            $overdueInvoices = Invoice::where('status', 'overdue')->count();
            $overdueAmount = Invoice::where('status', 'overdue')->sum('remaining_amount');

            // المدفوعات المعلقة
            $pendingPayments = Payment::where('status', 'pending')->count();
            $pendingPaymentsAmount = Payment::where('status', 'pending')->sum('amount');

            // أحدث الأنشطة
            $recentContracts = Contract::with(['product', 'user', 'facility'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $recentPayments = Payment::with(['contract.product', 'contract.user'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // الرسوم البيانية - إيرادات آخر 6 أشهر
            $monthlyRevenue = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $revenue = AccountingEntry::byAccountType('revenue')
                    ->whereYear('entry_date', $month->year)
                    ->whereMonth('entry_date', $month->month)
                    ->sum('amount');
                $monthlyRevenue[] = [
                    'month' => $month->format('M Y'),
                    'revenue' => $revenue
                ];
            }

            return view('admin.financial.dashboard', compact(
                'totalOffers', 'activeOffers', 'totalContracts', 'activeContracts',
                'totalRevenue', 'totalCommissions', 'periodRevenue', 'periodPayments',
                'periodContracts', 'topFacilities', 'pendingContracts', 'overdueInvoices',
                'overdueAmount', 'pendingPayments', 'pendingPaymentsAmount',
                'recentContracts', 'recentPayments', 'monthlyRevenue',
                'startDate', 'endDate'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل لوحة المعلومات: ' . $e->getMessage());
        }
    }

    /**
     * إدارة العروض - عرض جميع العروض
     */
    public function offers(Request $request)
    {
        try {
            $query = Offer::with(['product', 'facility', 'translations']);

            // تطبيق الفلاتر
            if ($request->has('facility_id') && $request->facility_id) {
                $query->where('facility_id', $request->facility_id);
            }

            if ($request->has('type') && $request->type) {
                $query->byType($request->type);
            }

            if ($request->has('status')) {
                if ($request->status === 'active') {
                    $query->active();
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', false);
                }
            }

            if ($request->has('search') && $request->search) {
                $query->whereHas('product', function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%');
                });
            }

            $offers = $query->orderBy('created_at', 'desc')->paginate(20);

            // المؤسسات للفلتر
            $facilities = Facility::all();

            return view('admin.financial.offers', compact('offers', 'facilities'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب العروض: ' . $e->getMessage());
        }
    }

    /**
     * إدارة العقود - عرض جميع العقود
     */
    public function contracts(Request $request)
    {
        try {
            $query = Contract::with(['product', 'offer', 'user', 'owner', 'facility', 'invoices', 'payments']);

            // تطبيق الفلاتر
            if ($request->has('facility_id') && $request->facility_id) {
                $query->where('facility_id', $request->facility_id);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('type') && $request->type) {
                $query->byType($request->type);
            }

            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('contract_number', 'like', '%' . $request->search . '%')
                      ->orWhereHas('user', function($userQuery) use ($request) {
                          $userQuery->where('name', 'like', '%' . $request->search . '%');
                      });
                });
            }

            $contracts = $query->orderBy('created_at', 'desc')->paginate(20);

            // المؤسسات للفلتر
            $facilities = Facility::all();

            return view('admin.financial.contracts', compact('contracts', 'facilities'));

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
            ])->findOrFail($id);

            return view('admin.financial.contract-details', compact('contract'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'العقد غير موجود');
        }
    }

    /**
     * تحديث حالة العقد
     */
    public function updateContractStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:draft,active,completed,cancelled',
                'reason' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $contract = Contract::findOrFail($id);

            if ($request->status === 'cancelled') {
                $this->contractService->cancelContract($contract, $request->reason);
                $message = 'تم إلغاء العقد بنجاح';
            } else {
                $this->contractService->updateContractStatus($contract, $request->status);
                $message = 'تم تحديث حالة العقد بنجاح';
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحديث حالة العقد: ' . $e->getMessage());
        }
    }

    /**
     * إدارة المدفوعات - عرض جميع المدفوعات
     */
    public function payments(Request $request)
    {
        try {
            $query = Payment::with(['invoice', 'contract.product', 'contract.user', 'facility']);

            // تطبيق الفلاتر
            if ($request->has('facility_id') && $request->facility_id) {
                $query->where('facility_id', $request->facility_id);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('method') && $request->method) {
                $query->byMethod($request->method);
            }

            if ($request->has('start_date') && $request->start_date) {
                $query->where('payment_date', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date) {
                $query->where('payment_date', '<=', $request->end_date);
            }

            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('reference_number', 'like', '%' . $request->search . '%')
                      ->orWhereHas('contract.user', function($userQuery) use ($request) {
                          $userQuery->where('name', 'like', '%' . $request->search . '%');
                      });
                });
            }

            $payments = $query->orderBy('payment_date', 'desc')->paginate(20);

            // المؤسسات للفلتر
            $facilities = Facility::all();

            return view('admin.financial.payments', compact('payments', 'facilities'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب المدفوعات: ' . $e->getMessage());
        }
    }

    /**
     * تأكيد دفعة
     */
    public function confirmPayment($id)
    {
        try {
            $payment = Payment::with(['invoice', 'contract'])->findOrFail($id);

            if ($payment->status !== 'pending') {
                return redirect()->back()->with('error', 'الدفعة ليست في حالة معلق');
            }

            $payment->confirm();

            return redirect()->back()->with('success', 'تم تأكيد الدفعة بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تأكيد الدفعة: ' . $e->getMessage());
        }
    }

    /**
     * رفض دفعة
     */
    public function rejectPayment($id)
    {
        try {
            $payment = Payment::findOrFail($id);

            if ($payment->status !== 'pending') {
                return redirect()->back()->with('error', 'الدفعة ليست في حالة معلق');
            }

            $payment->fail();

            return redirect()->back()->with('success', 'تم رفض الدفعة');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في رفض الدفعة: ' . $e->getMessage());
        }
    }

    /**
     * تقارير مالية شاملة
     */
    public function reports(Request $request)
    {
        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();
            $facilityId = $request->facility_id;

            // التقارير المختلفة
            $revenueReport = $this->reportService->getRevenueReport($facilityId, $startDate, $endDate);
            $paymentsReport = $this->reportService->getPaymentsReport($facilityId, $startDate, $endDate);
            $invoicesReport = $this->reportService->getInvoicesReport($facilityId, $startDate, $endDate);
            $commissionReport = $this->reportService->getCommissionReport($facilityId, $startDate, $endDate);
            $contractsReport = $this->reportService->getContractsReport($facilityId, $startDate, $endDate);

            // المؤسسات للفلتر
            $facilities = Facility::all();

            $summary = [
                'total_revenue' => $revenueReport['total_revenue'],
                'total_payments' => $paymentsReport['total_payments'],
                'total_commissions' => $commissionReport['total_commission'],
                'net_income' => $revenueReport['total_revenue'] - $commissionReport['total_commission'],
                'collection_rate' => $revenueReport['total_revenue'] > 0 
                    ? ($paymentsReport['total_payments'] / $revenueReport['total_revenue']) * 100 
                    : 0,
            ];

            return view('admin.financial.reports', compact(
                'revenueReport', 'paymentsReport', 'invoicesReport', 
                'commissionReport', 'contractsReport', 'summary',
                'facilities', 'startDate', 'endDate', 'facilityId'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب التقرير: ' . $e->getMessage());
        }
    }

    /**
     * تقرير العملاء
     */
    public function customersReport(Request $request)
    {
        try {
            $query = User::whereHas('contracts')->with(['contracts']);

            if ($request->has('facility_id') && $request->facility_id) {
                $query->whereHas('contracts', function($q) use ($request) {
                    $q->where('facility_id', $request->facility_id);
                });
            }

            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $customers = $query->get();

            $customersData = $customers->map(function($customer) {
                $contracts = $customer->contracts;
                $totalValue = $contracts->sum('total_amount');
                $totalPaid = $contracts->sum(function($contract) {
                    return $contract->getTotalPaidAmount();
                });

                return [
                    'customer' => $customer,
                    'total_contracts' => $contracts->count(),
                    'total_value' => $totalValue,
                    'total_paid' => $totalPaid,
                    'remaining_amount' => $totalValue - $totalPaid,
                    'payment_rate' => $totalValue > 0 ? ($totalPaid / $totalValue) * 100 : 0,
                ];
            });

            // المؤسسات للفلتر
            $facilities = Facility::all();

            return view('admin.financial.customers-report', compact('customersData', 'facilities'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب تقرير العملاء: ' . $e->getMessage());
        }
    }

    /**
     * تقرير الملاك
     */
    public function ownersReport(Request $request)
    {
        try {
            $query = User::whereHas('ownedContracts')->with(['ownedContracts']);

            if ($request->has('facility_id') && $request->facility_id) {
                $query->whereHas('ownedContracts', function($q) use ($request) {
                    $q->where('facility_id', $request->facility_id);
                });
            }

            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $owners = $query->get();

            $ownersData = $owners->map(function($owner) {
                $contracts = $owner->ownedContracts;
                $totalValue = $contracts->sum('total_amount');
                $totalCommission = $contracts->sum('commission_amount');
                $netAmount = $totalValue - $totalCommission;

                return [
                    'owner' => $owner,
                    'total_contracts' => $contracts->count(),
                    'total_value' => $totalValue,
                    'total_commission' => $totalCommission,
                    'net_amount' => $netAmount,
                    'commission_rate' => $totalValue > 0 ? ($totalCommission / $totalValue) * 100 : 0,
                ];
            });

            // المؤسسات للفلتر
            $facilities = Facility::all();

            return view('admin.financial.owners-report', compact('ownersData', 'facilities'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب تقرير الملاك: ' . $e->getMessage());
        }
    }

    /**
     * تقرير المؤسسات
     */
    public function facilitiesReport(Request $request)
    {
        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : null;

            $facilities = Facility::with(['contracts', 'offers'])->get();

            $facilitiesData = $facilities->map(function($facility) use ($startDate, $endDate) {
                $contractsQuery = $facility->contracts();
                
                if ($startDate) {
                    $contractsQuery->where('created_at', '>=', $startDate);
                }
                
                if ($endDate) {
                    $contractsQuery->where('created_at', '<=', $endDate);
                }

                $contracts = $contractsQuery->get();
                $totalRevenue = $contracts->sum('total_amount');
                $totalCommissions = $contracts->sum('commission_amount');

                return [
                    'facility' => $facility,
                    'total_contracts' => $contracts->count(),
                    'total_offers' => $facility->offers->count(),
                    'active_offers' => $facility->offers->where('is_active', true)->count(),
                    'total_revenue' => $totalRevenue,
                    'total_commissions' => $totalCommissions,
                    'net_revenue' => $totalRevenue - $totalCommissions,
                ];
            });

            return view('admin.financial.facilities-report', compact('facilitiesData', 'startDate', 'endDate'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب تقرير المؤسسات: ' . $e->getMessage());
        }
    }

    /**
     * القيود المحاسبية
     */
    public function accountingEntries(Request $request)
    {
        try {
            $query = AccountingEntry::with(['contract', 'facility', 'createdBy']);

            // تطبيق الفلاتر
            if ($request->has('facility_id') && $request->facility_id) {
                $query->where('facility_id', $request->facility_id);
            }

            if ($request->has('account_type') && $request->account_type) {
                $query->byAccountType($request->account_type);
            }

            if ($request->has('entry_type') && $request->entry_type) {
                $query->where('entry_type', $request->entry_type);
            }

            if ($request->has('start_date') && $request->start_date) {
                $query->where('entry_date', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date) {
                $query->where('entry_date', '<=', $request->end_date);
            }

            $entries = $query->orderBy('entry_date', 'desc')->paginate(50);

            // المؤسسات للفلتر
            $facilities = Facility::all();

            return view('admin.financial.accounting-entries', compact('entries', 'facilities'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في جلب القيود المحاسبية: ' . $e->getMessage());
        }
    }

    /**
     * إنشاء قيد محاسبي يدوي
     */
    public function createAccountingEntry(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'entry_type' => 'required|in:debit,credit',
                'account_type' => 'required|in:revenue,receivable,commission,liability,expense',
                'amount' => 'required|numeric|min:0',
                'currency' => 'required|string|max:3',
                'description' => 'required|string|max:255',
                'contract_id' => 'nullable|exists:contracts,id',
                'facility_id' => 'nullable|exists:facilities,id',
                'entry_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $entry = AccountingEntry::create(array_merge($request->all(), [
                'created_by' => Auth::id(),
                'reference_type' => 'manual',
                'reference_id' => null,
            ]));

            return redirect()->back()->with('success', 'تم إنشاء القيد المحاسبي بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في إنشاء القيد المحاسبي: ' . $e->getMessage());
        }
    }
}
