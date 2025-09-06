<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FacilityFinancialReportController extends Controller
{
    protected $financialReportService;

    public function __construct(FinancialReportService $financialReportService)
    {
        $this->financialReportService = $financialReportService;
    }

    /**
     * عرض لوحة التقارير المالية
     */
    public function index()
    {
        $facility = Auth::user()->facilities()->first();
        
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $facilityId = $facility->id;
        $currentMonth = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        // تقرير الشهر الحالي
        $monthlyReport = $this->financialReportService->getFacilityFinancialSummary(
            $facilityId, 
            $currentMonth, 
            $currentMonthEnd
        );

        // إحصائيات سريعة
        $quickStats = [
            'total_contracts' => $facility->contracts()->count(),
            'active_contracts' => $facility->contracts()->active()->count(),
            'total_revenue' => $monthlyReport['summary']['total_revenue'],
            'total_payments' => $monthlyReport['summary']['total_payments'],
            'collection_rate' => $monthlyReport['summary']['collection_rate'],
        ];

        return view('facility.financial.index', compact('monthlyReport', 'quickStats'));
    }

    /**
     * تقرير الإيرادات
     */
    public function revenue(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        $facilityId = $facility->id;
        
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->financialReportService->getRevenueReport($facilityId, $startDate, $endDate);

        return view('facility.financial.revenue', compact('report', 'startDate', 'endDate'));
    }

    /**
     * تقرير الذمم المدينة
     */
    public function receivables()
    {
        $facility = Auth::user()->facilities()->first();
        $facilityId = $facility->id;

        $report = $this->financialReportService->getReceivablesReport($facilityId);

        return view('facility.financial.receivables', compact('report'));
    }

    /**
     * تقرير العمولات
     */
    public function commissions(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        $facilityId = $facility->id;
        
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->financialReportService->getCommissionReport($facilityId, $startDate, $endDate);

        return view('facility.financial.commissions', compact('report', 'startDate', 'endDate'));
    }

    /**
     * تقرير المدفوعات
     */
    public function payments(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        $facilityId = $facility->id;
        
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->financialReportService->getPaymentsReport($facilityId, $startDate, $endDate);

        return view('facility.financial.payments', compact('report', 'startDate', 'endDate'));
    }

    /**
     * تقرير الفواتير
     */
    public function invoices(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        $facilityId = $facility->id;
        
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->financialReportService->getInvoicesReport($facilityId, $startDate, $endDate);

        return view('facility.financial.invoices', compact('report', 'startDate', 'endDate'));
    }

    /**
     * تقرير العقود
     */
    public function contracts(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        $facilityId = $facility->id;
        
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->financialReportService->getContractsReport($facilityId, $startDate, $endDate);

        return view('facility.financial.contracts', compact('report', 'startDate', 'endDate'));
    }

    /**
     * تقرير العميل
     */
    public function customer(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
        ]);

        $facility = Auth::user()->facilities()->first();
        $facilityId = $facility->id;
        $customerId = $request->customer_id;

        $report = $this->financialReportService->getCustomerReport($customerId, $facilityId);

        return view('facility.financial.customer', compact('report'));
    }

    /**
     * تقرير المالك
     */
    public function owner(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:users,id',
        ]);

        $facility = Auth::user()->facilities()->first();
        $facilityId = $facility->id;
        $ownerId = $request->owner_id;

        $report = $this->financialReportService->getOwnerReport($ownerId, $facilityId);

        return view('facility.financial.owner', compact('report'));
    }

    /**
     * تقرير شهري
     */
    public function monthly(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $facility = Auth::user()->facilities()->first();
        $facilityId = $facility->id;
        $year = $request->year;
        $month = $request->month;

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $report = $this->financialReportService->getFacilityFinancialSummary($facilityId, $startDate, $endDate);

        return view('facility.financial.monthly', compact('report', 'year', 'month'));
    }

    /**
     * تقرير سنوي
     */
    public function yearly(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $facility = Auth::user()->facilities()->first();
        $facilityId = $facility->id;
        $year = $request->year;

        $startDate = Carbon::create($year, 1, 1)->startOfYear();
        $endDate = Carbon::create($year, 12, 31)->endOfYear();

        $report = $this->financialReportService->getFacilityFinancialSummary($facilityId, $startDate, $endDate);

        return view('facility.financial.yearly', compact('report', 'year'));
    }

    /**
     * تصدير التقرير
     */
    public function export(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        $facilityId = $facility->id;
        
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();
        $reportType = $request->get('type', 'summary');

        $report = $this->financialReportService->getFacilityFinancialSummary($facilityId, $startDate, $endDate);

        // إنشاء ملف Excel أو CSV حسب النوع
        $filename = "financial_report_{$reportType}_" . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($report) {
            $file = fopen('php://output', 'w');
            
            // رؤوس الأعمدة
            fputcsv($file, [
                'Period', 'Total Revenue', 'Total Payments', 'Total Commissions', 
                'Net Income', 'Collection Rate (%)'
            ]);

            // البيانات
            fputcsv($file, [
                $report['period']['start_date'] . ' - ' . $report['period']['end_date'],
                $report['summary']['total_revenue'],
                $report['summary']['total_payments'],
                $report['summary']['total_commissions'],
                $report['summary']['net_income'],
                $report['summary']['collection_rate']
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
