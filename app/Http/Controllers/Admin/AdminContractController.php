<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Facility;
use App\Models\User;
use App\Services\ContractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminContractController extends Controller
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

        // فلترة حسب المنشأة
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        // فلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('contract_type', $request->type);
        }

        // فلترة حسب الحالة
        if ($request->filled('status_id')) {
            $query->where('status', $request->status_id);
        }

        // فلترة حسب المستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // فلترة حسب المنتج
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // فلترة حسب نوع العقد
        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('start_date', '<=', $request->date_to);
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
                  })
                  ->orWhereHas('facility', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $contracts = $query->paginate(20);
        
        // Get data for filters
        $facilities = Facility::all();
        $users = User::where('primary_role', 'user')->get();
        $products = Product::with('translations')->get();
        
        // Create status objects for the dropdown
        $statuses = collect([
            (object)['id' => 'draft', 'name' => 'مسودة'],
            (object)['id' => 'active', 'name' => 'نشط'],
            (object)['id' => 'completed', 'name' => 'مكتمل'],
            (object)['id' => 'cancelled', 'name' => 'ملغي'],
        ]);

        return view('admin.contracts.index', compact('contracts', 'facilities', 'users', 'products', 'statuses'));
    }

    /**
     * عرض نموذج إنشاء عقد جديد
     */
    public function create()
    {
        $users = User::where('primary_role', 'client')->orderBy('name')->get();
        $products = Product::with('translations')->where('is_active', true)->get();
        $locales = config('locales.available');

        // حالات العقد (عمود status نصي)
        $statuses = collect([
            (object)['id' => 'draft', 'name' => 'مسودة'],
            (object)['id' => 'active', 'name' => 'نشط'],
            (object)['id' => 'completed', 'name' => 'مكتمل'],
            (object)['id' => 'cancelled', 'name' => 'ملغي'],
        ]);

        return view('admin.contracts.create', compact('users', 'products', 'statuses', 'locales'));
    }

    /**
     * حفظ عقد جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'facility_id' => 'required|exists:facilities,id',
            'contract_type' => 'required|in:sale,rent,lease',
            'status_id' => 'required|in:draft,active,completed,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'total_amount' => 'required|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'monthly_payment' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
        ]);

        $contract = $this->contractService->createContract([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'facility_id' => $request->facility_id,
            'contract_type' => $request->contract_type === 'lease' ? 'rent' : $request->contract_type,
            'status' => $request->status_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_amount' => $request->total_amount,
            'deposit_amount' => $request->down_payment,
            'terms_conditions' => $request->notes,
            'is_active' => $request->boolean('is_active'),
            'is_verified' => $request->boolean('is_verified'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.contracts.index')
            ->with('success', __('تم إنشاء العقد بنجاح'));
    }

    /**
     * إحصائيات العقود
     */
    public function statistics()
    {
        $stats = [
            'total_contracts' => Contract::count(),
            'verified_contracts' => Contract::where('is_verified', true)->count(),
            'total_value' => Contract::sum('total_amount'),
            'total_paid' => \App\Models\Payment::where('status', 'confirmed')->sum('amount'),
            'recent_contracts' => Contract::with(['user', 'product.translations', 'facility'])->latest()->take(10)->get(),
        ];

        return view('admin.contracts.statistics', compact('stats'));
    }

    /**
     * عرض تفاصيل العقد
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
            'accountingEntries',
            'translations'
        ]);
        
        return view('admin.contracts.show', compact('contract'));
    }

    /**
     * عرض نموذج تعديل العقد
     */
    public function edit(Contract $contract)
    {
        $facilities = Facility::all();
        $products = Product::with('translations')->get();
        $offers = Offer::active()->valid()->with('product')->get();
        $users = User::where('primary_role', 'user')->get();
        $owners = User::where('primary_role', 'owner')->get();
        $locales = config('locales.available');
        
        $contractTypes = [
            'sale' => 'بيع',
            'rent' => 'إيجار',
        ];

        $contract->load('translations');
        
        return view('admin.contracts.edit', compact('contract', 'facilities', 'products', 'offers', 'users', 'owners', 'contractTypes', 'locales'));
    }

    /**
     * تحديث العقد
     */
    public function update(Request $request, Contract $contract)
    {
        $availableLocales = array_keys(config('locales.available', []));
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'offer_id' => 'required|exists:offers,id',
            'user_id' => 'required|exists:users,id',
            'owner_id' => 'required|exists:users,id',
            'facility_id' => 'required|exists:facilities,id',
            'contract_type' => 'required|in:sale,rent',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'total_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:1',
            'status' => 'required|in:draft,active,completed,cancelled',
            'terms_conditions' => 'nullable|string',
            'terms_conditions_ar' => 'nullable|string',
            'translations' => 'nullable|array',
            'translations.*.locale' => 'required_with:translations|string|in:' . implode(',', $availableLocales) . '|distinct',
            'translations.*.title' => 'nullable|string|max:255',
            'translations.*.content' => 'nullable|string',
        ]);

        $contract->update($request->except(['translations']));
        
        if ($request->has('commission_rate')) {
            $contract->calculateCommission()->save();
        }

        $incomingTranslations = $request->input('translations');
        if (is_array($incomingTranslations) && count($incomingTranslations)) {
            $incomingLocales = [];
            foreach ($incomingTranslations as $translationData) {
                if (empty($translationData['locale'])) {
                    continue;
                }

                $incomingLocales[] = $translationData['locale'];

                $contract->translations()->updateOrCreate(
                    [
                        'locale' => $translationData['locale'],
                    ],
                    [
                        'title' => $translationData['title'] ?? null,
                        'content' => $translationData['content'] ?? null,
                    ]
                );
            }

            $contract->translations()->whereNotIn('locale', $incomingLocales)->delete();
        }

        return redirect()->route('admin.contracts.index')
            ->with('success', __('تم تحديث العقد بنجاح'));
    }

    /**
     * حذف العقد
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();

        return redirect()->route('admin.contracts.index')
            ->with('success', __('تم حذف العقد بنجاح'));
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

        return redirect()->back()->with('success', __('تم تحديث حالة العقد بنجاح'));
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

        return redirect()->back()->with('success', __('تم إلغاء العقد بنجاح'));
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

        return redirect()->back()->with('success', __('تم تسجيل الدفعة بنجاح'));
    }

    /**
     * عرض فواتير العقد
     */
    public function invoices(Contract $contract)
    {
        $invoices = $contract->invoices()->with(['payments'])->get();

        return view('admin.contracts.invoices', compact('contract', 'invoices'));
    }

    /**
     * عرض مدفوعات العقد
     */
    public function payments(Contract $contract)
    {
        $payments = $contract->payments()->with(['invoice'])->get();

        return view('admin.contracts.payments', compact('contract', 'payments'));
    }

    /**
     * عرض التقرير المالي للعقد
     */
    public function financialReport(Contract $contract)
    {
        $totalPaid = $contract->getTotalPaidAmount();
        $remaining = $contract->getRemainingAmount();
        $isFullyPaid = $contract->isFullyPaid();

        return view('admin.contracts.financial-report', compact(
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
        // هنا يمكن إضافة منطق إنشاء PDF للعقد
        return response()->json([
            'message' => __('سيتم إضافة ميزة تحميل العقد قريباً')
        ]);
    }

    /**
     * تبديل حالة التفعيل للعقد
     */
    public function toggleStatus(Contract $contract)
    {
        $contract->update([
            'is_active' => !$contract->is_active
        ]);

        $status = $contract->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        
        return redirect()->back()->with('success', $status . ' العقد بنجاح');
    }

    /**
     * تبديل حالة التحقق للعقد
     */
    public function toggleVerification(Contract $contract)
    {
        $contract->update([
            'is_verified' => !$contract->is_verified
        ]);

        $status = $contract->is_verified ? 'تم التحقق من' : 'تم إلغاء التحقق من';
        
        return redirect()->back()->with('success', $status . ' العقد بنجاح');
    }

    /**
     * تصدير العقود كملف CSV
     */
    public function export(Request $request)
    {
        $contracts = Contract::with(['product.translations', 'user', 'owner', 'facility'])
            ->latest()
            ->get();

        return response()->streamDownload(function () use ($contracts) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['رقم العقد', 'العميل', 'المالك', 'العقار', 'المنشأة', 'النوع', 'المبلغ', 'الحالة', 'أُنشئ في']);
            foreach ($contracts as $c) {
                fputcsv($out, [
                    $c->contract_number,
                    $c->user->name ?? '—',
                    $c->owner->name ?? '—',
                    $c->product?->getTranslatedTitle() ?: '—',
                    $c->facility->name ?? '—',
                    $c->contract_type,
                    $c->total_amount,
                    $c->status,
                    $c->created_at?->format('Y-m-d'),
                ]);
            }
            fclose($out);
        }, 'contracts-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}