<?php

namespace App\Http\Controllers\Api;

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

class ApiAdminFinancialController extends Controller
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
     * لوحة معلومات الأدمن المالية
     * GET /api/admin/financial/dashboard
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

            return response()->json([
                'success' => true,
                'data' => [
                    'overview' => [
                        'total_offers' => $totalOffers,
                        'active_offers' => $activeOffers,
                        'total_contracts' => $totalContracts,
                        'active_contracts' => $activeContracts,
                        'total_revenue' => $totalRevenue,
                        'total_commissions' => $totalCommissions,
                        'net_revenue' => $totalRevenue - $totalCommissions,
                    ],
                    'period_stats' => [
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                        'revenue' => $periodRevenue,
                        'payments' => $periodPayments,
                        'contracts' => $periodContracts,
                    ],
                    'alerts' => [
                        'pending_contracts' => $pendingContracts,
                        'overdue_invoices' => $overdueInvoices,
                        'overdue_amount' => $overdueAmount,
                        'pending_payments' => $pendingPayments,
                        'pending_payments_amount' => $pendingPaymentsAmount,
                    ],
                    'top_facilities' => $topFacilities,
                ],
                'message' => 'تم جلب بيانات لوحة المعلومات بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب بيانات لوحة المعلومات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إدارة العروض - عرض جميع العروض
     * GET /api/admin/offers
     */
    public function getAllOffers(Request $request)
    {
        try {
            $query = Offer::with(['product', 'facility', 'translations']);

            // تطبيق الفلاتر
            if ($request->has('facility_id')) {
                $query->where('facility_id', $request->facility_id);
            }

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

            if ($request->has('search')) {
                $query->whereHas('product', function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%');
                });
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
     * إدارة العقود - عرض جميع العقود
     * GET /api/admin/contracts
     */
    public function getAllContracts(Request $request)
    {
        try {
            $query = Contract::with(['product', 'offer', 'user', 'owner', 'facility', 'invoices', 'payments']);

            // تطبيق الفلاتر
            if ($request->has('facility_id')) {
                $query->where('facility_id', $request->facility_id);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('type')) {
                $query->byType($request->type);
            }

            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('owner_id')) {
                $query->where('owner_id', $request->owner_id);
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
     * إدارة المدفوعات - عرض جميع المدفوعات
     * GET /api/admin/payments
     */
    public function getAllPayments(Request $request)
    {
        try {
            $query = Payment::with(['invoice', 'contract.product', 'contract.user', 'facility']);

            // تطبيق الفلاتر
            if ($request->has('facility_id')) {
                $query->where('facility_id', $request->facility_id);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('method')) {
                $query->byMethod($request->method);
            }

            if ($request->has('contract_id')) {
                $query->where('contract_id', $request->contract_id);
            }

            if ($request->has('start_date')) {
                $query->where('payment_date', '>=', $request->start_date);
            }

            if ($request->has('end_date')) {
                $query->where('payment_date', '<=', $request->end_date);
            }

            $payments = $query->orderBy('payment_date', 'desc')->paginate(20);

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
     * تأكيد دفعة (للأدمن)
     * POST /api/admin/payments/{id}/confirm
     */
    public function confirmPayment($id)
    {
        try {
            $payment = Payment::with(['invoice', 'contract'])->findOrFail($id);

            if ($payment->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'الدفعة ليست في حالة معلق'
                ], 422);
            }

            $payment->confirm();

            return response()->json([
                'success' => true,
                'data' => $payment->fresh(),
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
     * تحديث حالة العقد (للأدمن)
     * PUT /api/admin/contracts/{id}/status
     */
    public function updateContractStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:draft,active,completed,cancelled',
                'reason' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contract = Contract::findOrFail($id);

            if ($request->status === 'cancelled') {
                $this->contractService->cancelContract($contract, $request->reason);
            } else {
                $this->contractService->updateContractStatus($contract, $request->status);
            }

            return response()->json([
                'success' => true,
                'data' => $contract->fresh(['product', 'offer', 'user', 'owner']),
                'message' => 'تم تحديث حالة العقد بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحديث حالة العقد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تقارير مالية شاملة
     * GET /api/admin/financial/reports
     */
    public function getComprehensiveReport(Request $request)
    {
        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();
            $facilityId = $request->facility_id;

            $revenueReport = $this->reportService->getRevenueReport($facilityId, $startDate, $endDate);
            $paymentsReport = $this->reportService->getPaymentsReport($facilityId, $startDate, $endDate);
            $invoicesReport = $this->reportService->getInvoicesReport($facilityId, $startDate, $endDate);
            $commissionReport = $this->reportService->getCommissionReport($facilityId, $startDate, $endDate);
            $contractsReport = $this->reportService->getContractsReport($facilityId, $startDate, $endDate);

            return response()->json([
                'success' => true,
                'data' => [
                    'period' => [
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                    ],
                    'facility_id' => $facilityId,
                    'revenue' => $revenueReport,
                    'payments' => $paymentsReport,
                    'invoices' => $invoicesReport,
                    'commissions' => $commissionReport,
                    'contracts' => $contractsReport,
                    'summary' => [
                        'total_revenue' => $revenueReport['total_revenue'],
                        'total_payments' => $paymentsReport['total_payments'],
                        'total_commissions' => $commissionReport['total_commission'],
                        'net_income' => $revenueReport['total_revenue'] - $commissionReport['total_commission'],
                        'collection_rate' => $revenueReport['total_revenue'] > 0 
                            ? ($paymentsReport['total_payments'] / $revenueReport['total_revenue']) * 100 
                            : 0,
                    ],
                ],
                'message' => 'تم جلب التقرير الشامل بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب التقرير: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تقرير العملاء
     * GET /api/admin/reports/customers
     */
    public function getCustomersReport(Request $request)
    {
        try {
            $query = User::whereHas('contracts')->with(['contracts']);

            if ($request->has('facility_id')) {
                $query->whereHas('contracts', function($q) use ($request) {
                    $q->where('facility_id', $request->facility_id);
                });
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

            return response()->json([
                'success' => true,
                'data' => $customersData,
                'message' => 'تم جلب تقرير العملاء بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب تقرير العملاء: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تقرير الملاك
     * GET /api/admin/reports/owners
     */
    public function getOwnersReport(Request $request)
    {
        try {
            $query = User::whereHas('ownedContracts')->with(['ownedContracts']);

            if ($request->has('facility_id')) {
                $query->whereHas('ownedContracts', function($q) use ($request) {
                    $q->where('facility_id', $request->facility_id);
                });
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

            return response()->json([
                'success' => true,
                'data' => $ownersData,
                'message' => 'تم جلب تقرير الملاك بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب تقرير الملاك: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تقرير المؤسسات
     * GET /api/admin/reports/facilities
     */
    public function getFacilitiesReport(Request $request)
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

            return response()->json([
                'success' => true,
                'data' => $facilitiesData,
                'message' => 'تم جلب تقرير المؤسسات بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب تقرير المؤسسات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * القيود المحاسبية
     * GET /api/admin/accounting-entries
     */
    public function getAccountingEntries(Request $request)
    {
        try {
            $query = AccountingEntry::with(['contract', 'facility', 'createdBy']);

            // تطبيق الفلاتر
            if ($request->has('facility_id')) {
                $query->where('facility_id', $request->facility_id);
            }

            if ($request->has('account_type')) {
                $query->byAccountType($request->account_type);
            }

            if ($request->has('entry_type')) {
                $query->where('entry_type', $request->entry_type);
            }

            if ($request->has('contract_id')) {
                $query->where('contract_id', $request->contract_id);
            }

            if ($request->has('start_date')) {
                $query->where('entry_date', '>=', $request->start_date);
            }

            if ($request->has('end_date')) {
                $query->where('entry_date', '<=', $request->end_date);
            }

            $entries = $query->orderBy('entry_date', 'desc')->paginate(50);

            return response()->json([
                'success' => true,
                'data' => $entries,
                'message' => 'تم جلب القيود المحاسبية بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب القيود المحاسبية: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إنشاء قيد محاسبي يدوي
     * POST /api/admin/accounting-entries
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
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $entry = AccountingEntry::create(array_merge($request->all(), [
                'created_by' => Auth::id(),
                'reference_type' => 'manual',
                'reference_id' => null,
            ]));

            return response()->json([
                'success' => true,
                'data' => $entry->load(['contract', 'facility', 'createdBy']),
                'message' => 'تم إنشاء القيد المحاسبي بنجاح'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إنشاء القيد المحاسبي: ' . $e->getMessage()
            ], 500);
        }
    }
}
