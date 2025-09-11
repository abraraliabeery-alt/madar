<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Offer;
use App\Models\Product;
use App\Models\User;
use App\Services\ContractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityContractController extends Controller
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
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $query = $facility->contracts()->with(['product', 'offer', 'user', 'owner']);

        // فلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('contract_type', $request->type);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('owner', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $contracts = $query->paginate(15);

        return view('facility.contracts.index', compact('contracts'));
    }

    /**
     * عرض نموذج إنشاء عقد
     */
    public function create()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $products = $facility->products()->with('translations')->get();
        $offers = $facility->offers()->active()->valid()->with('product')->get();
        $users = User::where('primary_role', 'user')->get();
        $owners = User::where('primary_role', 'owner')->get();
        
        $contractTypes = [
            'sale' => 'بيع',
            'rent' => 'إيجار',
        ];

        return view('facility.contracts.create', compact('products', 'offers', 'users', 'owners', 'contractTypes'));
    }

    /**
     * حفظ عقد جديد
     */
    public function store(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

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
            'payment_frequency' => 'nullable|in:monthly,quarterly,yearly,custom',
            'total_installments' => 'nullable|integer|min:1',
            'late_fee_rate' => 'nullable|numeric|min:0|max:1',
            'early_payment_discount' => 'nullable|numeric|min:0',
            'contract_duration_months' => 'nullable|integer|min:1',
            'renewal_terms' => 'nullable|string',
            'termination_terms' => 'nullable|string',
            'terms_conditions_ar' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['facility_id'] = $facility->id;
        $data['created_by'] = Auth::id();

        $contract = $this->contractService->createContract($data);

        // حفظ الترجمات
        if ($request->filled('terms_conditions_ar')) {
            $contract->translations()->create([
                'locale' => 'ar',
                'terms_conditions' => $request->terms_conditions_ar,
            ]);
        }

        return redirect()->route('facility.contracts.index')
            ->with('success', 'تم إنشاء العقد بنجاح');
    }

    /**
     * عرض تفاصيل العقد
     */
    public function show(Contract $contract)
    {
        $this->authorize('view', $contract);
        
        $contract->load([
            'product', 
            'offer', 
            'user', 
            'owner', 
            'invoices', 
            'payments',
            'accountingEntries',
            'translations'
        ]);
        
        return view('facility.contracts.show', compact('contract'));
    }

    /**
     * عرض نموذج تعديل العقد
     */
    public function edit(Contract $contract)
    {
        $this->authorize('update', $contract);
        
        $facility = Auth::user()->facilities()->first();
        $products = $facility->products()->with('translations')->get();
        $offers = $facility->offers()->active()->valid()->with('product')->get();
        $users = User::where('primary_role', 'user')->get();
        $owners = User::where('primary_role', 'owner')->get();
        
        $contractTypes = [
            'sale' => 'بيع',
            'rent' => 'إيجار',
        ];

        $contract->load('translations');
        
        return view('facility.contracts.edit', compact('contract', 'products', 'offers', 'users', 'owners', 'contractTypes'));
    }

    /**
     * تحديث العقد
     */
    public function update(Request $request, Contract $contract)
    {
        $this->authorize('update', $contract);

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
            'status' => 'required|in:draft,active,completed,cancelled',
            'terms_conditions' => 'nullable|string',
            'terms_conditions_ar' => 'nullable|string',
        ]);

        $contract->update($request->all());
        
        if ($request->has('commission_rate')) {
            $contract->calculateCommission()->save();
        }

        // تحديث الترجمات
        $translation = $contract->translations()->where('locale', 'ar')->first();
        if ($translation) {
            $translation->update([
                'terms_conditions' => $request->terms_conditions_ar,
            ]);
        } else {
            $contract->translations()->create([
                'locale' => 'ar',
                'terms_conditions' => $request->terms_conditions_ar,
            ]);
        }

        return redirect()->route('facility.contracts.index')
            ->with('success', 'تم تحديث العقد بنجاح');
    }

    /**
     * حذف العقد
     */
    public function destroy(Contract $contract)
    {
        $this->authorize('delete', $contract);
        
        $contract->delete();

        return redirect()->route('facility.contracts.index')
            ->with('success', 'تم حذف العقد بنجاح');
    }

    /**
     * تحديث حالة العقد
     */
    public function updateStatus(Request $request, Contract $contract)
    {
        $this->authorize('update', $contract);
        
        $request->validate([
            'status' => 'required|in:draft,active,completed,cancelled'
        ]);

        $contract = $this->contractService->updateContractStatus($contract, $request->status);

        return redirect()->back()->with('success', 'تم تحديث حالة العقد بنجاح');
    }

    /**
     * إلغاء العقد
     */
    public function cancel(Request $request, Contract $contract)
    {
        $this->authorize('update', $contract);
        
        $request->validate([
            'reason' => 'nullable|string'
        ]);

        $contract = $this->contractService->cancelContract($contract, $request->reason);

        return redirect()->back()->with('success', 'تم إلغاء العقد بنجاح');
    }

    /**
     * تسجيل دفعة
     */
    public function recordPayment(Request $request, Contract $contract)
    {
        $this->authorize('update', $contract);
        
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

        return redirect()->back()->with('success', 'تم تسجيل الدفعة بنجاح');
    }

    /**
     * عرض فواتير العقد
     */
    public function invoices(Contract $contract)
    {
        $this->authorize('view', $contract);
        
        $invoices = $contract->invoices()->with(['payments'])->get();

        return view('facility.contracts.invoices', compact('contract', 'invoices'));
    }

    /**
     * عرض مدفوعات العقد
     */
    public function payments(Contract $contract)
    {
        $this->authorize('view', $contract);
        
        $payments = $contract->payments()->with(['invoice'])->get();

        return view('facility.contracts.payments', compact('contract', 'payments'));
    }

    /**
     * عرض التقرير المالي للعقد
     */
    public function financialReport(Contract $contract)
    {
        $this->authorize('view', $contract);
        
        $totalPaid = $contract->getTotalPaidAmount();
        $remaining = $contract->getRemainingAmount();
        $isFullyPaid = $contract->isFullyPaid();

        return view('facility.contracts.financial-report', compact(
            'contract', 
            'totalPaid', 
            'remaining', 
            'isFullyPaid'
        ));
    }

    /**
     * تحميل العقد
     */
    public function download(Contract $contract)
    {
        $this->authorize('view', $contract);
        
        // هنا يمكن إضافة منطق إنشاء PDF للعقد
        return response()->json([
            'message' => 'سيتم إضافة ميزة تحميل العقد قريباً'
        ]);
    }
}
