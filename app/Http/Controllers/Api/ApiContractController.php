<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Offer;
use App\Services\ContractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiContractController extends Controller
{
    protected $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * عرض قائمة العقود
     */
    public function index(Request $request)
    {
        $query = Contract::with(['product', 'offer', 'user', 'owner', 'facility']);

        if ($request->has('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('contract_type', $request->type);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }

        $contracts = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
    }

    /**
     * إنشاء عقد جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'offer_id' => 'required|exists:offers,id',
            'user_id' => 'required|exists:users,id',
            'owner_id' => 'required|exists:users,id',
            'contract_type' => 'required|in:sale,rent',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'total_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:1',
            'terms_conditions' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['facility_id'] = Auth::user()->facilities()->first()?->id;
        $data['created_by'] = Auth::id();

        $contract = $this->contractService->createContract($data);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء العقد بنجاح',
            'data' => $contract->load(['product', 'offer', 'user', 'owner', 'invoices'])
        ], 201);
    }

    /**
     * عرض عقد محدد
     */
    public function show(Contract $contract)
    {
        $contract->load([
            'product', 
            'offer', 
            'user', 
            'owner', 
            'facility', 
            'invoices', 
            'payments',
            'accountingEntries'
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $contract
        ]);
    }

    /**
     * تحديث عقد
     */
    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'contract_type' => 'sometimes|in:sale,rent',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after:start_date',
            'total_amount' => 'sometimes|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:1',
            'status' => 'sometimes|in:draft,active,completed,cancelled',
            'terms_conditions' => 'nullable|string',
        ]);

        $contract->update($request->all());
        
        if ($request->has('commission_rate')) {
            $contract->calculateCommission()->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث العقد بنجاح',
            'data' => $contract->load(['product', 'offer', 'user', 'owner'])
        ]);
    }

    /**
     * حذف عقد
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف العقد بنجاح'
        ]);
    }

    /**
     * تحديث حالة العقد
     */
    public function updateStatus(Request $request, Contract $contract)
    {
        $request->validate([
            'status' => 'required|in:draft,active,completed,cancelled'
        ]);

        $contract = $this->contractService->updateContractStatus($contract, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة العقد بنجاح',
            'data' => $contract
        ]);
    }

    /**
     * إلغاء العقد
     */
    public function cancel(Request $request, Contract $contract)
    {
        $request->validate([
            'reason' => 'nullable|string'
        ]);

        $contract = $this->contractService->cancelContract($contract, $request->reason);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء العقد بنجاح',
            'data' => $contract
        ]);
    }

    /**
     * تسجيل دفعة
     */
    public function recordPayment(Request $request, Contract $contract)
    {
        $request->validate([
            'invoice_id' => 'nullable|exists:invoices,id',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,check,online',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'check_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['contract_id'] = $contract->id;
        $data['facility_id'] = $contract->facility_id;
        $data['created_by'] = Auth::id();

        $payment = $this->contractService->recordPayment($data);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدفعة بنجاح',
            'data' => $payment->load(['invoice', 'contract'])
        ], 201);
    }

    /**
     * الحصول على فواتير العقد
     */
    public function getInvoices(Contract $contract)
    {
        $invoices = $contract->invoices()->with(['payments'])->get();

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }

    /**
     * الحصول على مدفوعات العقد
     */
    public function getPayments(Contract $contract)
    {
        $payments = $contract->payments()->with(['invoice'])->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * الحصول على القيود المحاسبية للعقد
     */
    public function getAccountingEntries(Contract $contract)
    {
        $entries = $contract->accountingEntries()->get();

        return response()->json([
            'success' => true,
            'data' => $entries
        ]);
    }

    /**
     * تقرير العقد المالي
     */
    public function getFinancialReport(Contract $contract)
    {
        $totalPaid = $contract->getTotalPaidAmount();
        $remaining = $contract->getRemainingAmount();
        $isFullyPaid = $contract->isFullyPaid();

        return response()->json([
            'success' => true,
            'data' => [
                'contract' => $contract,
                'total_amount' => $contract->total_amount,
                'total_paid' => $totalPaid,
                'remaining_amount' => $remaining,
                'is_fully_paid' => $isFullyPaid,
                'payment_percentage' => $contract->total_amount > 0 ? ($totalPaid / $contract->total_amount) * 100 : 0,
                'invoices' => $contract->invoices,
                'payments' => $contract->payments,
            ]
        ]);
    }
}