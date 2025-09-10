<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartOfAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('facility.access');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $facility = Auth::user()->facility;
        
        $query = ChartOfAccount::where('facility_id', $facility->id)
            ->with(['parentAccount', 'childAccounts']);

        // فلترة حسب نوع الحساب
        if ($request->account_type) {
            $query->where('account_type', $request->account_type);
        }

        // فلترة حسب فئة الحساب
        if ($request->account_category) {
            $query->where('account_category', $request->account_category);
        }

        // فلترة حسب الحالة
        if ($request->is_active !== null) {
            $query->where('is_active', $request->is_active);
        }

        // البحث
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('account_code', 'like', '%' . $request->search . '%')
                  ->orWhere('account_name_ar', 'like', '%' . $request->search . '%')
                  ->orWhere('account_name_en', 'like', '%' . $request->search . '%');
            });
        }

        $accounts = $query->orderBy('account_code')->paginate(20);

        $accountTypes = ChartOfAccount::getAccountTypes();
        $accountCategories = ChartOfAccount::getAccountCategories();

        return view('facility.accounting.chart-of-accounts.index', compact(
            'accounts',
            'accountTypes',
            'accountCategories'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $facility = Auth::user()->facility;
        
        $parentAccounts = ChartOfAccount::where('facility_id', $facility->id)
            ->where('is_active', true)
            ->whereNull('parent_account_id')
            ->orderBy('account_code')
            ->get();

        $accountTypes = ChartOfAccount::getAccountTypes();
        $accountCategories = ChartOfAccount::getAccountCategories();

        return view('facility.accounting.chart-of-accounts.create', compact(
            'parentAccounts',
            'accountTypes',
            'accountCategories'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_code' => 'required|string|max:20|unique:chart_of_accounts,account_code',
            'account_name_ar' => 'required|string|max:255',
            'account_name_en' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense,cost_of_sales',
            'account_category' => 'required|string|max:255',
            'normal_balance' => 'required|in:debit,credit',
            'parent_account_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
        ]);

        $facility = Auth::user()->facility;

        // حساب المستوى
        $level = 1;
        if ($request->parent_account_id) {
            $parentAccount = ChartOfAccount::find($request->parent_account_id);
            $level = $parentAccount->level + 1;
        }

        ChartOfAccount::create([
            'account_code' => $request->account_code,
            'account_name_ar' => $request->account_name_ar,
            'account_name_en' => $request->account_name_en,
            'account_type' => $request->account_type,
            'account_category' => $request->account_category,
            'normal_balance' => $request->normal_balance,
            'parent_account_id' => $request->parent_account_id,
            'level' => $level,
            'description' => $request->description,
            'opening_balance' => $request->opening_balance ?? 0,
            'current_balance' => $request->opening_balance ?? 0,
            'facility_id' => $facility->id,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('facility.accounting.chart-of-accounts.index')
            ->with('success', 'تم إنشاء الحساب بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChartOfAccount $chartOfAccount)
    {
        $this->authorize('view', $chartOfAccount);
        
        $chartOfAccount->load(['parentAccount', 'childAccounts', 'accountingEntries']);
        
        // إحصائيات الحساب
        $stats = [
            'total_entries' => $chartOfAccount->accountingEntries()->count(),
            'debit_entries' => $chartOfAccount->accountingEntries()->debit()->count(),
            'credit_entries' => $chartOfAccount->accountingEntries()->credit()->count(),
            'total_debit' => $chartOfAccount->accountingEntries()->debit()->sum('amount'),
            'total_credit' => $chartOfAccount->accountingEntries()->credit()->sum('amount'),
        ];

        // آخر القيود
        $recentEntries = $chartOfAccount->accountingEntries()
            ->with(['createdBy', 'period'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('facility.accounting.chart-of-accounts.show', compact(
            'chartOfAccount',
            'stats',
            'recentEntries'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChartOfAccount $chartOfAccount)
    {
        $this->authorize('update', $chartOfAccount);
        
        $facility = Auth::user()->facility;
        
        $parentAccounts = ChartOfAccount::where('facility_id', $facility->id)
            ->where('is_active', true)
            ->where('id', '!=', $chartOfAccount->id)
            ->whereNull('parent_account_id')
            ->orderBy('account_code')
            ->get();

        $accountTypes = ChartOfAccount::getAccountTypes();
        $accountCategories = ChartOfAccount::getAccountCategories();

        return view('facility.accounting.chart-of-accounts.edit', compact(
            'chartOfAccount',
            'parentAccounts',
            'accountTypes',
            'accountCategories'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $this->authorize('update', $chartOfAccount);

        $request->validate([
            'account_code' => 'required|string|max:20|unique:chart_of_accounts,account_code,' . $chartOfAccount->id,
            'account_name_ar' => 'required|string|max:255',
            'account_name_en' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense,cost_of_sales',
            'account_category' => 'required|string|max:255',
            'normal_balance' => 'required|in:debit,credit',
            'parent_account_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // حساب المستوى
        $level = 1;
        if ($request->parent_account_id) {
            $parentAccount = ChartOfAccount::find($request->parent_account_id);
            $level = $parentAccount->level + 1;
        }

        $chartOfAccount->update([
            'account_code' => $request->account_code,
            'account_name_ar' => $request->account_name_ar,
            'account_name_en' => $request->account_name_en,
            'account_type' => $request->account_type,
            'account_category' => $request->account_category,
            'normal_balance' => $request->normal_balance,
            'parent_account_id' => $request->parent_account_id,
            'level' => $level,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('facility.accounting.chart-of-accounts.index')
            ->with('success', 'تم تحديث الحساب بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChartOfAccount $chartOfAccount)
    {
        $this->authorize('delete', $chartOfAccount);

        if (!$chartOfAccount->canBeDeleted()) {
            return back()->with('error', 'لا يمكن حذف هذا الحساب لأنه يحتوي على قيود محاسبية أو حسابات فرعية');
        }

        $chartOfAccount->delete();

        return redirect()->route('facility.accounting.chart-of-accounts.index')
            ->with('success', 'تم حذف الحساب بنجاح');
    }

    /**
     * تحديث الرصيد الافتتاحي
     */
    public function updateOpeningBalance(Request $request, ChartOfAccount $chartOfAccount)
    {
        $this->authorize('update', $chartOfAccount);

        $request->validate([
            'opening_balance' => 'required|numeric'
        ]);

        $chartOfAccount->update([
            'opening_balance' => $request->opening_balance,
            'current_balance' => $request->opening_balance,
        ]);

        return back()->with('success', 'تم تحديث الرصيد الافتتاحي بنجاح');
    }

    /**
     * تصدير دليل الحسابات
     */
    public function export(Request $request)
    {
        $facility = Auth::user()->facility;
        
        $query = ChartOfAccount::where('facility_id', $facility->id)
            ->with(['parentAccount']);

        // تطبيق نفس الفلاتر من index
        if ($request->account_type) {
            $query->where('account_type', $request->account_type);
        }
        if ($request->account_category) {
            $query->where('account_category', $request->account_category);
        }
        if ($request->is_active !== null) {
            $query->where('is_active', $request->is_active);
        }

        $accounts = $query->orderBy('account_code')->get();

        $filename = 'chart_of_accounts_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($accounts) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للدعم العربي
            fwrite($file, "\xEF\xBB\xBF");
            
            // رؤوس الأعمدة
            fputcsv($file, [
                'كود الحساب',
                'اسم الحساب (عربي)',
                'اسم الحساب (إنجليزي)',
                'نوع الحساب',
                'فئة الحساب',
                'الرصيد الطبيعي',
                'الحساب الأب',
                'المستوى',
                'الرصيد الافتتاحي',
                'الرصيد الحالي',
                'الحالة'
            ]);

            foreach ($accounts as $account) {
                fputcsv($file, [
                    $account->account_code,
                    $account->account_name_ar,
                    $account->account_name_en,
                    $account->account_type,
                    $account->account_category,
                    $account->normal_balance === 'debit' ? 'مدين' : 'دائن',
                    $account->parentAccount->account_name ?? '',
                    $account->level,
                    $account->opening_balance,
                    $account->current_balance,
                    $account->is_active ? 'نشط' : 'غير نشط'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * إنشاء دليل الحسابات الافتراضي
     */
    public function createDefault()
    {
        $facility = Auth::user()->facility;
        
        if (ChartOfAccount::where('facility_id', $facility->id)->exists()) {
            return back()->with('error', 'يوجد بالفعل دليل حسابات لهذه المنشأة');
        }

        try {
            ChartOfAccount::createDefaultAccounts($facility->id, Auth::id());
            
            return redirect()->route('facility.accounting.chart-of-accounts.index')
                ->with('success', 'تم إنشاء دليل الحسابات الافتراضي بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إنشاء دليل الحسابات: ' . $e->getMessage());
        }
    }
}