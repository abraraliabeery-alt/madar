<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\AccountingPeriod;
use App\Models\AccountingEntry;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('facility.access');
    }

    /**
     * عرض قائمة التقارير المالية
     */
    public function index(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }
        
        $periods = AccountingPeriod::where('facility_id', $facility->id)
            ->orderBy('start_date', 'desc')
            ->get();

        // حساب ملخص سريع
        $summary = $this->getSummaryData($facility->id, $request);

        return view('facility.accounting.reports.index', compact('periods', 'summary'));
    }

    /**
     * الحصول على بيانات الملخص السريع
     */
    private function getSummaryData($facilityId, $request)
    {
        $periodId = $request->period_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($periodId) {
            $period = AccountingPeriod::find($periodId);
            $startDate = $period->start_date;
            $endDate = $period->end_date;
        } elseif (!$startDate || !$endDate) {
            $currentPeriod = AccountingPeriod::where('facility_id', $facilityId)
                ->where('is_current', true)
                ->first();
            
            if ($currentPeriod) {
                $startDate = $currentPeriod->start_date;
                $endDate = $currentPeriod->end_date;
            } else {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }
        }

        $totalRevenue = $this->calculateAccountBalance('revenue', $facilityId, $startDate, $endDate);
        $totalExpenses = $this->calculateAccountBalance('expense', $facilityId, $startDate, $endDate);
        $netProfit = $totalRevenue - $totalExpenses;
        
        $assets = $this->getAssets($facilityId, $endDate);
        $totalAssets = $assets['total'];

        return [
            'total_revenue' => number_format($totalRevenue, 2),
            'total_expenses' => number_format($totalExpenses, 2),
            'net_profit' => number_format($netProfit, 2),
            'total_assets' => number_format($totalAssets, 2),
        ];
    }

    /**
     * قائمة الدخل
     */
    public function incomeStatement(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }
        
        $periodId = $request->period_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($periodId) {
            $period = AccountingPeriod::find($periodId);
            $startDate = $period->start_date;
            $endDate = $period->end_date;
        } elseif (!$startDate || !$endDate) {
            $currentPeriod = AccountingPeriod::where('facility_id', $facility->id)
                ->where('is_current', true)
                ->first();
            
            if ($currentPeriod) {
                $startDate = $currentPeriod->start_date;
                $endDate = $currentPeriod->end_date;
            } else {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }
        }

        // حساب الإيرادات
        $revenue = $this->calculateAccountBalance('revenue', $facility->id, $startDate, $endDate);
        
        // حساب المصروفات
        $expenses = $this->calculateAccountBalance('expense', $facility->id, $startDate, $endDate);
        
        // حساب تكلفة المبيعات
        $costOfSales = $this->calculateAccountBalance('cost_of_sales', $facility->id, $startDate, $endDate);

        // صافي الدخل
        $netIncome = $revenue - $expenses - $costOfSales;

        // تفاصيل الإيرادات
        $revenueDetails = $this->getAccountDetails('revenue', $facility->id, $startDate, $endDate);
        
        // تفاصيل المصروفات
        $expenseDetails = $this->getAccountDetails('expense', $facility->id, $startDate, $endDate);

        $periods = AccountingPeriod::where('facility_id', $facility->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('facility.accounting.reports.income-statement', compact(
            'revenue',
            'expenses',
            'costOfSales',
            'netIncome',
            'revenueDetails',
            'expenseDetails',
            'startDate',
            'endDate',
            'periods'
        ));
    }

    /**
     * الميزانية العمومية
     */
    public function balanceSheet(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }
        
        $periodId = $request->period_id;
        $asOfDate = $request->as_of_date;

        if ($periodId) {
            $period = AccountingPeriod::find($periodId);
            $asOfDate = $period->end_date;
        } elseif (!$asOfDate) {
            $asOfDate = Carbon::now()->toDateString();
        }

        // الأصول
        $assets = $this->getAssets($facility->id, $asOfDate);
        
        // الخصوم
        $liabilities = $this->getLiabilities($facility->id, $asOfDate);
        
        // حقوق الملكية
        $equity = $this->getEquity($facility->id, $asOfDate);

        $totalAssets = $assets['total'];
        $totalLiabilities = $liabilities['total'];
        $totalEquity = $equity['total'];

        $periods = AccountingPeriod::where('facility_id', $facility->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('facility.accounting.reports.balance-sheet', compact(
            'assets',
            'liabilities',
            'equity',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'asOfDate',
            'periods'
        ));
    }

    /**
     * حساب رصيد نوع حساب معين
     */
    private function calculateAccountBalance($accountType, $facilityId, $startDate, $endDate)
    {
        $entries = AccountingEntry::where('facility_id', $facilityId)
            ->where('account_type', $accountType)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->where('is_reversed', false)
            ->get();

        $balance = 0;
        foreach ($entries as $entry) {
            if ($entry->entry_type === 'debit') {
                $balance += $entry->amount;
            } else {
                $balance -= $entry->amount;
            }
        }

        return $balance;
    }

    /**
     * الحصول على تفاصيل الحسابات
     */
    private function getAccountDetails($accountType, $facilityId, $startDate, $endDate)
    {
        $accounts = ChartOfAccount::where('facility_id', $facilityId)
            ->where('account_type', $accountType)
            ->where('is_active', true)
            ->get();

        $details = [];
        foreach ($accounts as $account) {
            $balance = $account->getBalanceForPeriod($startDate, $endDate);
            if ($balance != 0) {
                $details[] = [
                    'account' => $account,
                    'balance' => $balance,
                ];
            }
        }

        return $details;
    }

    /**
     * الحصول على الأصول
     */
    private function getAssets($facilityId, $asOfDate)
    {
        $assetAccounts = ChartOfAccount::where('facility_id', $facilityId)
            ->where('account_type', 'asset')
            ->where('is_active', true)
            ->get();

        $assets = [
            'current' => [],
            'fixed' => [],
            'total' => 0
        ];

        foreach ($assetAccounts as $account) {
            $balance = $account->getBalanceForPeriod(
                $account->created_at->toDateString(),
                $asOfDate
            );

            if ($balance != 0) {
                $assetData = [
                    'account' => $account,
                    'balance' => $balance,
                ];

                if ($account->account_category === 'current_asset') {
                    $assets['current'][] = $assetData;
                } else {
                    $assets['fixed'][] = $assetData;
                }

                $assets['total'] += $balance;
            }
        }

        return $assets;
    }

    /**
     * الحصول على الخصوم
     */
    private function getLiabilities($facilityId, $asOfDate)
    {
        $liabilityAccounts = ChartOfAccount::where('facility_id', $facilityId)
            ->where('account_type', 'liability')
            ->where('is_active', true)
            ->get();

        $liabilities = [
            'current' => [],
            'long_term' => [],
            'total' => 0
        ];

        foreach ($liabilityAccounts as $account) {
            $balance = $account->getBalanceForPeriod(
                $account->created_at->toDateString(),
                $asOfDate
            );

            if ($balance != 0) {
                $liabilityData = [
                    'account' => $account,
                    'balance' => $balance,
                ];

                if ($account->account_category === 'current_liability') {
                    $liabilities['current'][] = $liabilityData;
                } else {
                    $liabilities['long_term'][] = $liabilityData;
                }

                $liabilities['total'] += $balance;
            }
        }

        return $liabilities;
    }

    /**
     * الحصول على حقوق الملكية
     */
    private function getEquity($facilityId, $asOfDate)
    {
        $equityAccounts = ChartOfAccount::where('facility_id', $facilityId)
            ->where('account_type', 'equity')
            ->where('is_active', true)
            ->get();

        $equity = [
            'accounts' => [],
            'total' => 0
        ];

        foreach ($equityAccounts as $account) {
            $balance = $account->getBalanceForPeriod(
                $account->created_at->toDateString(),
                $asOfDate
            );

            if ($balance != 0) {
                $equity['accounts'][] = [
                    'account' => $account,
                    'balance' => $balance,
                ];

                $equity['total'] += $balance;
            }
        }

        return $equity;
    }

    /**
     * تصدير جميع التقارير المالية
     */
    public function exportAll(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $periodId = $request->period_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($periodId) {
            $period = AccountingPeriod::find($periodId);
            $startDate = $period->start_date;
            $endDate = $period->end_date;
        } elseif (!$startDate || !$endDate) {
            $currentPeriod = AccountingPeriod::where('facility_id', $facility->id)
                ->where('is_current', true)
                ->first();
            
            if ($currentPeriod) {
                $startDate = $currentPeriod->start_date;
                $endDate = $currentPeriod->end_date;
            } else {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }
        }

        // جمع بيانات جميع التقارير
        $reportsData = [
            'facility' => $facility,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'income_statement' => $this->getIncomeStatementData($facility->id, $startDate, $endDate),
            'balance_sheet' => $this->getBalanceSheetData($facility->id, $endDate),
            'cash_flow' => $this->getCashFlowData($facility->id, $startDate, $endDate),
            'trial_balance' => $this->getTrialBalanceData($facility->id, $startDate, $endDate),
        ];

        // إنشاء ملف ZIP يحتوي على جميع التقارير
        $zipFileName = 'financial_reports_' . $facility->id . '_' . date('Y-m-d_H-i-s') . '.zip';
        
        // هنا يمكنك إضافة منطق إنشاء ملف ZIP وتصدير التقارير
        // يمكن استخدام مكتبة مثل ZipArchive أو Laravel Excel
        
        return response()->json([
            'message' => 'تم تجهيز التقارير للتصدير',
            'data' => $reportsData,
            'download_url' => route('facility.accounting.reports.download', ['file' => $zipFileName])
        ]);
    }

    /**
     * الحصول على بيانات قائمة الدخل
     */
    private function getIncomeStatementData($facilityId, $startDate, $endDate)
    {
        $revenue = $this->calculateAccountBalance('revenue', $facilityId, $startDate, $endDate);
        $expenses = $this->calculateAccountBalance('expense', $facilityId, $startDate, $endDate);
        $costOfSales = $this->calculateAccountBalance('cost_of_sales', $facilityId, $startDate, $endDate);
        $netIncome = $revenue - $expenses - $costOfSales;

        return [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'cost_of_sales' => $costOfSales,
            'net_income' => $netIncome,
            'revenue_details' => $this->getAccountDetails('revenue', $facilityId, $startDate, $endDate),
            'expense_details' => $this->getAccountDetails('expense', $facilityId, $startDate, $endDate),
        ];
    }

    /**
     * الحصول على بيانات الميزانية العمومية
     */
    private function getBalanceSheetData($facilityId, $asOfDate)
    {
        $assets = $this->getAssets($facilityId, $asOfDate);
        $liabilities = $this->getLiabilities($facilityId, $asOfDate);
        $equity = $this->getEquity($facilityId, $asOfDate);

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'total_assets' => $assets['total'],
            'total_liabilities' => $liabilities['total'],
            'total_equity' => $equity['total'],
        ];
    }

    /**
     * الحصول على بيانات التدفق النقدي
     */
    private function getCashFlowData($facilityId, $startDate, $endDate)
    {
        // حساب التدفقات النقدية من الأنشطة التشغيلية
        $operatingCashFlow = $this->calculateAccountBalance('revenue', $facilityId, $startDate, $endDate) 
                           - $this->calculateAccountBalance('expense', $facilityId, $startDate, $endDate);

        // حساب التدفقات النقدية من الأنشطة الاستثمارية
        $investingCashFlow = $this->calculateAccountBalance('fixed_asset', $facilityId, $startDate, $endDate);

        // حساب التدفقات النقدية من الأنشطة التمويلية
        $financingCashFlow = $this->calculateAccountBalance('equity', $facilityId, $startDate, $endDate)
                           + $this->calculateAccountBalance('liability', $facilityId, $startDate, $endDate);

        return [
            'operating_cash_flow' => $operatingCashFlow,
            'investing_cash_flow' => $investingCashFlow,
            'financing_cash_flow' => $financingCashFlow,
            'net_cash_flow' => $operatingCashFlow + $investingCashFlow + $financingCashFlow,
        ];
    }

    /**
     * الحصول على بيانات ميزان المراجعة
     */
    private function getTrialBalanceData($facilityId, $startDate, $endDate)
    {
        $accounts = ChartOfAccount::where('facility_id', $facilityId)
            ->where('is_active', true)
            ->get();

        $trialBalance = [];
        foreach ($accounts as $account) {
            $balance = $account->getBalanceForPeriod($startDate, $endDate);
            if ($balance != 0) {
                $trialBalance[] = [
                    'account_code' => $account->account_code,
                    'account_name' => $account->name,
                    'account_type' => $account->account_type,
                    'debit_balance' => $balance > 0 ? $balance : 0,
                    'credit_balance' => $balance < 0 ? abs($balance) : 0,
                ];
            }
        }

        return $trialBalance;
    }

    /**
     * تقرير الميزانية
     */
    public function budgetReport(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $periodId = $request->period_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($periodId) {
            $period = AccountingPeriod::find($periodId);
            $startDate = $period->start_date;
            $endDate = $period->end_date;
        } elseif (!$startDate || !$endDate) {
            $currentPeriod = AccountingPeriod::where('facility_id', $facility->id)
                ->where('is_current', true)
                ->first();
            
            if ($currentPeriod) {
                $startDate = $currentPeriod->start_date;
                $endDate = $currentPeriod->end_date;
            } else {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }
        }

        // الحصول على الميزانيات
        $budgets = Budget::where('facility_id', $facility->id)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orWhereBetween('end_date', [$startDate, $endDate])
            ->get();

        $periods = AccountingPeriod::where('facility_id', $facility->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('facility.accounting.reports.budget-report', compact(
            'budgets',
            'startDate',
            'endDate',
            'periods'
        ));
    }

    /**
     * التدفق النقدي
     */
    public function cashFlow(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $periodId = $request->period_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($periodId) {
            $period = AccountingPeriod::find($periodId);
            $startDate = $period->start_date;
            $endDate = $period->end_date;
        } elseif (!$startDate || !$endDate) {
            $currentPeriod = AccountingPeriod::where('facility_id', $facility->id)
                ->where('is_current', true)
                ->first();
            
            if ($currentPeriod) {
                $startDate = $currentPeriod->start_date;
                $endDate = $currentPeriod->end_date;
            } else {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }
        }

        $cashFlowData = $this->getCashFlowData($facility->id, $startDate, $endDate);

        $periods = AccountingPeriod::where('facility_id', $facility->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('facility.accounting.reports.cash-flow', compact(
            'cashFlowData',
            'startDate',
            'endDate',
            'periods'
        ));
    }

    /**
     * ميزان المراجعة
     */
    public function trialBalance(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $periodId = $request->period_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($periodId) {
            $period = AccountingPeriod::find($periodId);
            $startDate = $period->start_date;
            $endDate = $period->end_date;
        } elseif (!$startDate || !$endDate) {
            $currentPeriod = AccountingPeriod::where('facility_id', $facility->id)
                ->where('is_current', true)
                ->first();
            
            if ($currentPeriod) {
                $startDate = $currentPeriod->start_date;
                $endDate = $currentPeriod->end_date;
            } else {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }
        }

        $trialBalance = $this->getTrialBalanceData($facility->id, $startDate, $endDate);

        $periods = AccountingPeriod::where('facility_id', $facility->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('facility.accounting.reports.trial-balance', compact(
            'trialBalance',
            'startDate',
            'endDate',
            'periods'
        ));
    }

    /**
     * تفاصيل الحسابات
     */
    public function accountDetails(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $periodId = $request->period_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($periodId) {
            $period = AccountingPeriod::find($periodId);
            $startDate = $period->start_date;
            $endDate = $period->end_date;
        } elseif (!$startDate || !$endDate) {
            $currentPeriod = AccountingPeriod::where('facility_id', $facility->id)
                ->where('is_current', true)
                ->first();
            
            if ($currentPeriod) {
                $startDate = $currentPeriod->start_date;
                $endDate = $currentPeriod->end_date;
            } else {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }
        }

        $accounts = ChartOfAccount::where('facility_id', $facility->id)
            ->where('is_active', true)
            ->get();

        $accountDetails = [];
        foreach ($accounts as $account) {
            $balance = $account->getBalanceForPeriod($startDate, $endDate);
            $accountDetails[] = [
                'account' => $account,
                'balance' => $balance,
                'entries' => $account->entries()
                    ->whereBetween('entry_date', [$startDate, $endDate])
                    ->where('is_reversed', false)
                    ->get()
            ];
        }

        $periods = AccountingPeriod::where('facility_id', $facility->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('facility.accounting.reports.account-details', compact(
            'accountDetails',
            'startDate',
            'endDate',
            'periods'
        ));
    }

    /**
     * تقرير مخصص
     */
    public function customReport(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        $periods = AccountingPeriod::where('facility_id', $facility->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('facility.accounting.reports.custom', compact('periods'));
    }

    /**
     * تصدير قائمة الدخل
     */
    public function exportIncomeStatement(Request $request)
    {
        // Implementation for exporting income statement
        return response()->json(['message' => 'تصدير قائمة الدخل']);
    }

    /**
     * تصدير الميزانية العمومية
     */
    public function exportBalanceSheet(Request $request)
    {
        // Implementation for exporting balance sheet
        return response()->json(['message' => 'تصدير الميزانية العمومية']);
    }

    /**
     * تصدير التدفق النقدي
     */
    public function exportCashFlow(Request $request)
    {
        // Implementation for exporting cash flow
        return response()->json(['message' => 'تصدير التدفق النقدي']);
    }

    /**
     * تصدير تقرير الميزانية
     */
    public function exportBudgetReport(Request $request)
    {
        // Implementation for exporting budget report
        return response()->json(['message' => 'تصدير تقرير الميزانية']);
    }

    /**
     * تصدير ميزان المراجعة
     */
    public function exportTrialBalance(Request $request)
    {
        // Implementation for exporting trial balance
        return response()->json(['message' => 'تصدير ميزان المراجعة']);
    }
}