<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\AccountingPeriod;
use App\Models\AccountingEntry;
use App\Models\TaxRate;
use App\Models\Budget;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AccountingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('facility.access');
    }

    /**
     * عرض لوحة التحكم المحاسبية
     */
    public function dashboard()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }
        
        // إحصائيات سريعة
        $stats = [
            'total_accounts' => ChartOfAccount::where('facility_id', $facility->id)->count(),
            'active_periods' => AccountingPeriod::where('facility_id', $facility->id)->where('status', 'open')->count(),
            'total_entries' => AccountingEntry::where('facility_id', $facility->id)->count(),
            'pending_entries' => AccountingEntry::where('facility_id', $facility->id)->where('is_reversed', false)->count(),
        ];

        // الفترة المحاسبية الحالية
        $currentPeriod = AccountingPeriod::where('facility_id', $facility->id)
            ->where('is_current', true)
            ->first();

        // آخر القيود المحاسبية
        $recentEntries = AccountingEntry::where('facility_id', $facility->id)
            ->with(['account', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ملخص مالي للفترة الحالية
        $financialSummary = $this->getFinancialSummary($facility->id, $currentPeriod);

        // الميزانيات النشطة
        $activeBudgets = Budget::where('facility_id', $facility->id)
            ->where('status', 'active')
            ->get();

        return view('facility.accounting.dashboard', compact(
            'stats',
            'currentPeriod',
            'recentEntries',
            'financialSummary',
            'activeBudgets'
        ));
    }

    /**
     * إنشاء قيد محاسبي جديد
     */
    public function createEntry()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }
        
        $accounts = ChartOfAccount::where('facility_id', $facility->id)
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();

        $periods = AccountingPeriod::where('facility_id', $facility->id)
            ->where('status', 'open')
            ->orderBy('start_date', 'desc')
            ->get();

        $taxRates = TaxRate::where('facility_id', $facility->id)
            ->where('is_active', true)
            ->get();

        return view('facility.accounting.entries.create', compact('accounts', 'periods', 'taxRates'));
    }

    /**
     * حفظ القيد المحاسبي
     */
    public function storeEntry(Request $request)
    {
        $request->validate([
            'debit_account_id' => 'required|exists:chart_of_accounts,id',
            'credit_account_id' => 'required|exists:chart_of_accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'period_id' => 'required|exists:accounting_periods,id',
            'entry_date' => 'required|date',
            'tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        try {
            // إنشاء القيد المزدوج
            $entries = AccountingEntry::createDoubleEntry(
                $request->debit_account_id,
                $request->credit_account_id,
                $request->amount,
                $request->description,
                $facility->id,
                $request->period_id,
                'manual',
                null,
                null,
                $request->tax_rate_id,
                Auth::id()
            );

            // تحديث أرصدة الحسابات
            foreach ($entries as $entry) {
                $account = ChartOfAccount::find($entry->account_id);
                $account->updateBalance($entry->amount, $entry->entry_type);
            }

            return redirect()->route('facility.accounting.entries.index')
                ->with('success', 'تم إنشاء القيد المحاسبي بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إنشاء القيد: ' . $e->getMessage());
        }
    }

    /**
     * عرض قائمة القيود المحاسبية
     */
    public function entriesIndex(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }
        
        $query = AccountingEntry::where('facility_id', $facility->id)
            ->with(['account', 'period', 'createdBy']);

        // فلترة حسب الفترة
        if ($request->period_id) {
            $query->where('period_id', $request->period_id);
        }

        // فلترة حسب نوع الحساب
        if ($request->account_type) {
            $query->where('account_type', $request->account_type);
        }

        // فلترة حسب التاريخ
        if ($request->date_from) {
            $query->where('entry_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->where('entry_date', '<=', $request->date_to);
        }

        // فلترة حسب حالة الإلغاء
        if ($request->is_reversed !== null) {
            $query->where('is_reversed', $request->is_reversed);
        }

        $entries = $query->orderBy('created_at', 'desc')->paginate(20);

        $periods = AccountingPeriod::where('facility_id', $facility->id)
            ->orderBy('start_date', 'desc')
            ->get();

        $accountTypes = ChartOfAccount::getAccountTypes();

        return view('facility.accounting.entries.index', compact(
            'entries',
            'periods',
            'accountTypes'
        ));
    }

    /**
     * عرض تفاصيل القيد المحاسبي
     */
    public function showEntry(AccountingEntry $entry)
    {
        $this->authorize('view', $entry);
        
        $entry->load(['account', 'period', 'createdBy', 'reversedBy', 'taxRate']);
        
        return view('facility.accounting.entries.show', compact('entry'));
    }

    /**
     * إلغاء القيد المحاسبي
     */
    public function reverseEntry(Request $request, AccountingEntry $entry)
    {
        $this->authorize('reverse', $entry);

        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        if (!$entry->canBeReversed()) {
            return back()->with('error', 'لا يمكن إلغاء هذا القيد');
        }

        try {
            $entry->reverse(Auth::id(), $request->reason);
            
            return back()->with('success', 'تم إلغاء القيد المحاسبي بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إلغاء القيد: ' . $e->getMessage());
        }
    }

    /**
     * الحصول على ملخص مالي
     */
    private function getFinancialSummary($facilityId, $period = null)
    {
        $query = AccountingEntry::where('facility_id', $facilityId)
            ->where('is_reversed', false);

        if ($period) {
            $query->whereBetween('entry_date', [$period->start_date, $period->end_date]);
        }

        $entries = $query->get();

        $summary = [
            'total_revenue' => 0,
            'total_expenses' => 0,
            'total_assets' => 0,
            'total_liabilities' => 0,
            'total_equity' => 0,
        ];

        foreach ($entries as $entry) {
            $amount = $entry->amount;
            
            if ($entry->account_type === 'revenue') {
                if ($entry->entry_type === 'credit') {
                    $summary['total_revenue'] += $amount;
                }
            } elseif ($entry->account_type === 'expense') {
                if ($entry->entry_type === 'debit') {
                    $summary['total_expenses'] += $amount;
                }
            } elseif ($entry->account_type === 'asset') {
                if ($entry->entry_type === 'debit') {
                    $summary['total_assets'] += $amount;
                } else {
                    $summary['total_assets'] -= $amount;
                }
            } elseif ($entry->account_type === 'liability') {
                if ($entry->entry_type === 'credit') {
                    $summary['total_liabilities'] += $amount;
                } else {
                    $summary['total_liabilities'] -= $amount;
                }
            } elseif ($entry->account_type === 'equity') {
                if ($entry->entry_type === 'credit') {
                    $summary['total_equity'] += $amount;
                } else {
                    $summary['total_equity'] -= $amount;
                }
            }
        }

        $summary['net_income'] = $summary['total_revenue'] - $summary['total_expenses'];

        return $summary;
    }

    /**
     * تصدير القيود المحاسبية
     */
    public function exportEntries(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }
        
        $query = AccountingEntry::where('facility_id', $facility->id)
            ->with(['account', 'period', 'createdBy']);

        // تطبيق نفس الفلاتر من index
        if ($request->period_id) {
            $query->where('period_id', $request->period_id);
        }
        if ($request->account_type) {
            $query->where('account_type', $request->account_type);
        }
        if ($request->date_from) {
            $query->where('entry_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->where('entry_date', '<=', $request->date_to);
        }
        if ($request->is_reversed !== null) {
            $query->where('is_reversed', $request->is_reversed);
        }

        $entries = $query->orderBy('created_at', 'desc')->get();

        $filename = 'accounting_entries_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($entries) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للدعم العربي
            fwrite($file, "\xEF\xBB\xBF");
            
            // رؤوس الأعمدة
            fputcsv($file, [
                'التاريخ',
                'نوع القيد',
                'الحساب',
                'المبلغ',
                'الوصف',
                'الفترة',
                'منشئ القيد',
                'حالة الإلغاء'
            ]);

            foreach ($entries as $entry) {
                fputcsv($file, [
                    $entry->entry_date->format('Y-m-d'),
                    $entry->entry_type === 'debit' ? 'مدين' : 'دائن',
                    $entry->account->account_name,
                    $entry->formatted_amount,
                    $entry->description,
                    $entry->period->period_name ?? '',
                    $entry->createdBy->name,
                    $entry->is_reversed ? 'ملغي' : 'نشط'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * إعداد النظام المحاسبي للمنشأة
     */
    public function setup()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }
        
        // التحقق من وجود دليل الحسابات
        $hasAccounts = ChartOfAccount::where('facility_id', $facility->id)->exists();
        
        // التحقق من وجود الفترات المحاسبية
        $hasPeriods = AccountingPeriod::where('facility_id', $facility->id)->exists();
        
        // التحقق من وجود معدلات الضرائب
        $hasTaxRates = TaxRate::where('facility_id', $facility->id)->exists();

        return view('facility.accounting.setup', compact(
            'hasAccounts',
            'hasPeriods',
            'hasTaxRates'
        ));
    }

    /**
     * إنشاء الإعدادات الافتراضية
     */
    public function createDefaultSetup()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }
        
        try {
            // إنشاء دليل الحسابات الافتراضي
            if (!ChartOfAccount::where('facility_id', $facility->id)->exists()) {
                ChartOfAccount::createDefaultAccounts($facility->id, Auth::id());
            }

            // إنشاء الفترات المحاسبية للعام الحالي
            if (!AccountingPeriod::where('facility_id', $facility->id)->exists()) {
                AccountingPeriod::createMonthlyPeriods(
                    Carbon::now()->year,
                    $facility->id,
                    Auth::id()
                );
            }

            // إنشاء معدلات الضرائب الافتراضية
            if (!TaxRate::where('facility_id', $facility->id)->exists()) {
                TaxRate::getDefaultTaxRates($facility->id, Auth::id());
            }

            return redirect()->route('facility.accounting.dashboard')
                ->with('success', 'تم إنشاء الإعدادات الافتراضية بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إنشاء الإعدادات: ' . $e->getMessage());
        }
    }
}