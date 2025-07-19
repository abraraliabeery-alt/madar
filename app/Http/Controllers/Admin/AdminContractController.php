<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\User;
use App\Models\Product;
use App\Models\Status;
use App\Models\Bank;

class AdminContractController extends Controller
{
    /**
     * عرض قائمة العقود
     */
    public function index(Request $request)
    {
        $query = Contract::with(['user', 'product', 'facility', 'status', 'bank']);

        // فلترة حسب الحالة
        if ($request->has('status_id') && $request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        // فلترة حسب المستخدم
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // فلترة حسب المنتج
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // فلترة حسب نوع العقد
        if ($request->has('contract_type') && $request->contract_type) {
            $query->where('contract_type', $request->contract_type);
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('contract_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                               ->orWhere('email', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('product', function ($productQuery) use ($request) {
                      $productQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $contracts = $query->latest()->paginate(15);
        $statuses = Status::all();
        $users = User::all();
        $products = Product::all();

        return view('admin.contracts.index', compact('contracts', 'statuses', 'users', 'products'));
    }

    /**
     * عرض صفحة إنشاء عقد جديد
     */
    public function create()
    {
        $users = User::all();
        $products = Product::where('is_active', true)->get();
        $statuses = Status::all();
        $banks = Bank::all();

        return view('admin.contracts.create', compact('users', 'products', 'statuses', 'banks'));
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
            'status_id' => 'required|exists:statuses,id',
            'contract_type' => 'required|in:sale,rent,lease',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'total_amount' => 'required|numeric|min:0',
            'down_payment' => 'required|numeric|min:0',
            'monthly_payment' => 'required|numeric|min:0',
            'bank_id' => 'nullable|exists:banks,id',
            'loan_amount' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0',
            'loan_term' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
        ]);

        // إنشاء رقم العقد
        $contractNumber = 'CT-' . date('Ymd') . '-' . str_pad(Contract::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        $contract = Contract::create([
            'contract_number' => $contractNumber,
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'facility_id' => $request->facility_id,
            'status_id' => $request->status_id,
            'contract_type' => $request->contract_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_amount' => $request->total_amount,
            'down_payment' => $request->down_payment,
            'monthly_payment' => $request->monthly_payment,
            'bank_id' => $request->bank_id,
            'loan_amount' => $request->loan_amount,
            'interest_rate' => $request->interest_rate,
            'loan_term' => $request->loan_term,
            'notes' => $request->notes,
            'is_active' => $request->is_active ?? true,
            'is_verified' => $request->is_verified ?? false,
        ]);

        return redirect()->route('admin.contracts.index')
            ->with('success', 'تم إنشاء العقد بنجاح');
    }

    /**
     * عرض صفحة تعديل العقد
     */
    public function edit(Contract $contract)
    {
        $contract->load(['user', 'product', 'facility', 'status', 'bank']);
        $users = User::all();
        $products = Product::where('is_active', true)->get();
        $statuses = Status::all();
        $banks = Bank::all();

        return view('admin.contracts.edit', compact('contract', 'users', 'products', 'statuses', 'banks'));
    }

    /**
     * تحديث العقد
     */
    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'facility_id' => 'required|exists:facilities,id',
            'status_id' => 'required|exists:statuses,id',
            'contract_type' => 'required|in:sale,rent,lease',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_amount' => 'required|numeric|min:0',
            'down_payment' => 'required|numeric|min:0',
            'monthly_payment' => 'required|numeric|min:0',
            'bank_id' => 'nullable|exists:banks,id',
            'loan_amount' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0',
            'loan_term' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
        ]);

        $contract->update($request->all());

        return redirect()->route('admin.contracts.index')
            ->with('success', 'تم تحديث العقد بنجاح');
    }

    /**
     * حذف العقد
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();

        return redirect()->route('admin.contracts.index')
            ->with('success', 'تم حذف العقد بنجاح');
    }

    /**
     * تفعيل/إلغاء تفعيل العقد
     */
    public function toggleStatus(Contract $contract)
    {
        $contract->update(['is_active' => !$contract->is_active]);

        $status = $contract->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        return redirect()->back()->with('success', "تم {$status} العقد بنجاح");
    }

    /**
     * التحقق من العقد
     */
    public function toggleVerification(Contract $contract)
    {
        $contract->update(['is_verified' => !$contract->is_verified]);

        $status = $contract->is_verified ? 'التحقق من' : 'إلغاء التحقق من';
        return redirect()->back()->with('success', "تم {$status} العقد بنجاح");
    }

    /**
     * عرض تفاصيل العقد
     */
    public function show(Contract $contract)
    {
        $contract->load(['user', 'product', 'facility', 'status', 'bank']);
        return view('admin.contracts.show', compact('contract'));
    }

    /**
     * تصدير العقود
     */
    public function export(Request $request)
    {
        $query = Contract::with(['user', 'product', 'facility', 'status']);

        // تطبيق نفس الفلاتر
        if ($request->has('status_id') && $request->status_id) {
            $query->where('status_id', $request->status_id);
        }
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $contracts = $query->get();

        // هنا يمكن تصدير البيانات إلى Excel أو CSV
        // سيتم تنفيذ هذا لاحقاً

        return redirect()->back()->with('success', 'تم تصدير البيانات بنجاح');
    }

    /**
     * إحصائيات العقود
     */
    public function statistics()
    {
        $stats = [
            'total_contracts' => Contract::count(),
            'active_contracts' => Contract::where('is_active', true)->count(),
            'verified_contracts' => Contract::where('is_verified', true)->count(),
            'sale_contracts' => Contract::where('contract_type', 'sale')->count(),
            'rent_contracts' => Contract::where('contract_type', 'rent')->count(),
            'lease_contracts' => Contract::where('contract_type', 'lease')->count(),
            'total_value' => Contract::sum('total_amount'),
            'monthly_revenue' => Contract::where('is_active', true)
                ->whereMonth('created_at', now()->month)
                ->sum('monthly_payment'),
            'recent_contracts' => Contract::with(['user', 'product'])
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('admin.contracts.statistics', compact('stats'));
    }
}
