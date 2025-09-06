<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Offer;
use App\Models\Product;
use App\Services\ContractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientContractController extends Controller
{
    protected $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * عرض قائمة عقود العميل
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->contracts()->with(['product', 'offer', 'owner', 'facility']);

        // فلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('contract_type', $request->type);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contracts = $query->paginate(10);

        return view('client.contracts.index', compact('contracts'));
    }

    /**
     * عرض تفاصيل العقد
     */
    public function show(Contract $contract)
    {
        // التأكد من أن العقد يخص العميل
        if ($contract->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذا العقد');
        }
        
        $contract->load([
            'product', 
            'offer', 
            'owner', 
            'facility',
            'invoices', 
            'payments',
            'translations'
        ]);
        
        return view('client.contracts.show', compact('contract'));
    }

    /**
     * عرض فواتير العقد
     */
    public function invoices(Contract $contract)
    {
        // التأكد من أن العقد يخص العميل
        if ($contract->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذا العقد');
        }
        
        $invoices = $contract->invoices()->with(['payments'])->get();

        return view('client.contracts.invoices', compact('contract', 'invoices'));
    }

    /**
     * عرض مدفوعات العقد
     */
    public function payments(Contract $contract)
    {
        // التأكد من أن العقد يخص العميل
        if ($contract->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذا العقد');
        }
        
        $payments = $contract->payments()->with(['invoice'])->get();

        return view('client.contracts.payments', compact('contract', 'payments'));
    }

    /**
     * عرض التقرير المالي للعقد
     */
    public function financialReport(Contract $contract)
    {
        // التأكد من أن العقد يخص العميل
        if ($contract->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذا العقد');
        }
        
        $totalPaid = $contract->getTotalPaidAmount();
        $remaining = $contract->getRemainingAmount();
        $isFullyPaid = $contract->isFullyPaid();

        return view('client.contracts.financial-report', compact(
            'contract', 
            'totalPaid', 
            'remaining', 
            'isFullyPaid'
        ));
    }

    /**
     * طلب عقد جديد
     */
    public function requestContract(Request $request, Offer $offer)
    {
        $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'nullable|date|after:start_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً'
            ], 401);
        }

        // إنشاء طلب عقد جديد
        $contractData = [
            'product_id' => $offer->product_id,
            'offer_id' => $offer->id,
            'user_id' => Auth::id(),
            'owner_id' => $offer->product->owner_user_id,
            'contract_type' => $offer->offer_type === 'sale' ? 'sale' : 'rent',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_amount' => $offer->price,
            'currency' => $offer->currency,
            'deposit_amount' => $offer->deposit_amount,
            'commission_rate' => $offer->commission_rate,
            'facility_id' => $offer->facility_id,
            'created_by' => Auth::id(),
            'status' => 'draft',
        ];

        $contract = $this->contractService->createContract($contractData);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء طلب العقد بنجاح',
            'contract_id' => $contract->id
        ]);
    }

    /**
     * تأكيد العقد
     */
    public function confirmContract(Contract $contract)
    {
        // التأكد من أن العقد يخص العميل
        if ($contract->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذا العقد');
        }

        if ($contract->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تأكيد هذا العقد'
            ], 400);
        }

        $contract->status = 'active';
        $contract->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تأكيد العقد بنجاح'
        ]);
    }

    /**
     * إلغاء العقد
     */
    public function cancelContract(Request $request, Contract $contract)
    {
        // التأكد من أن العقد يخص العميل
        if ($contract->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذا العقد');
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $contract = $this->contractService->cancelContract($contract, $request->reason);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء العقد بنجاح'
        ]);
    }

    /**
     * تحميل العقد
     */
    public function download(Contract $contract)
    {
        // التأكد من أن العقد يخص العميل
        if ($contract->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذا العقد');
        }

        // هنا يمكن إضافة منطق إنشاء PDF للعقد
        return response()->json([
            'message' => 'سيتم إضافة ميزة تحميل العقد قريباً'
        ]);
    }

    /**
     * دفع فاتورة
     */
    public function payInvoice(Request $request, Contract $contract)
    {
        // التأكد من أن العقد يخص العميل
        if ($contract->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذا العقد');
        }

        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,check,online',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string|max:500',
        ]);

        // التأكد من أن الفاتورة تخص العقد
        $invoice = $contract->invoices()->find($request->invoice_id);
        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'الفاتورة غير موجودة'
            ], 404);
        }

        $data = $request->all();
        $data['contract_id'] = $contract->id;
        $data['facility_id'] = $contract->facility_id;
        $data['created_by'] = Auth::id();

        $payment = $this->contractService->recordPayment($data);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدفعة بنجاح',
            'payment_id' => $payment->id
        ]);
    }

    /**
     * عرض صفحة الدفع
     */
    public function paymentPage(Contract $contract)
    {
        // التأكد من أن العقد يخص العميل
        if ($contract->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذا العقد');
        }

        $contract->load(['invoices', 'payments']);
        
        return view('client.contracts.payment', compact('contract'));
    }

    /**
     * إحصائيات عقود العميل
     */
    public function statistics()
    {
        $user = Auth::user();
        
        $stats = [
            'total_contracts' => $user->contracts()->count(),
            'active_contracts' => $user->contracts()->where('status', 'active')->count(),
            'completed_contracts' => $user->contracts()->where('status', 'completed')->count(),
            'total_value' => $user->contracts()->sum('total_amount'),
            'total_paid' => $user->contracts()->get()->sum(function($contract) {
                return $contract->getTotalPaidAmount();
            }),
        ];

        $stats['remaining_amount'] = $stats['total_value'] - $stats['total_paid'];

        return view('client.contracts.statistics', compact('stats'));
    }
}
