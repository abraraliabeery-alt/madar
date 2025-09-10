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
    public function index()
    {
        $facility = Auth::user()->facility;
        
        $periods = AccountingPeriod::where('facility_id', $facility->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('facility.accounting.reports.index', compact('periods'));
    }

    /**
     * قائمة الدخل
     */
    public function incomeStatement(Request $request)
    {
        $facility = Auth::user()->facility;
        
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
        $facility = Auth::user()->facility;
        
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
}