<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FinancialReportService;
use App\Models\Facility;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminFinancialReportController extends Controller
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
        $facilities = Facility::all();
        $currentMonth = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        // تقرير شامل لجميع المنشآت
        $overallStats = [
            'total_facilities' => $facilities->count(),
            'total_revenue' => 0,
            'total_payments' => 0,
            'total_commissions' => 0,
        ];

        foreach ($facilities as $facility) {
            $report = $this->financialReportService->getFacilityFinancialSummary(
                $facility->id, 
                $currentMonth, 
                $currentMonthEnd
            );
            
            $overallStats['total_revenue'] += $report['summary']['total_revenue'];
            $overallStats['total_payments'] += $report['summary']['total_payments'];
            $overallStats['total_commissions'] += $report['summary']['total_commissions'];
        }

        $overallStats['net_income'] = $overallStats['total_revenue'] - $overallStats['total_commissions'];
        $overallStats['collection_rate'] = $overallStats['total_revenue'] > 0 
            ? ($overallStats['total_payments'] / $overallStats['total_revenue']) * 100 
            : 0;

        return view('admin.financial.index', compact('overallStats', 'facilities'));
    }

    /**
     * تقرير الإيرادات
     */
    public function revenue(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->financialReportService->getRevenueReport($facilityId, $startDate, $endDate);
        $facilities = Facility::all();

        return view('admin.financial.revenue', compact('report', 'startDate', 'endDate', 'facilities'));
    }

    /**
     * تقرير الذمم المدينة
     */
    public function receivables(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $report = $this->financialReportService->getReceivablesReport($facilityId);
        $facilities = Facility::all();

        return view('admin.financial.receivables', compact('report', 'facilities'));
    }

    /**
     * تقرير العمولات
     */
    public function commissions(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->financialReportService->getCommissionReport($facilityId, $startDate, $endDate);
        $facilities = Facility::all();

        return view('admin.financial.commissions', compact('report', 'startDate', 'endDate', 'facilities'));
    }

    /**
     * تقرير المدفوعات
     */
    public function payments(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->financialReportService->getPaymentsReport($facilityId, $startDate, $endDate);
        $facilities = Facility::all();

        return view('admin.financial.payments', compact('report', 'startDate', 'endDate', 'facilities'));
    }

    /**
     * تقرير الفواتير
     */
    public function invoices(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->financialReportService->getInvoicesReport($facilityId, $startDate, $endDate);
        $facilities = Facility::all();

        return view('admin.financial.invoices', compact('report', 'startDate', 'endDate', 'facilities'));
    }

    /**
     * تقرير العقود
     */
    public function contracts(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->financialReportService->getContractsReport($facilityId, $startDate, $endDate);
        $facilities = Facility::all();

        return view('admin.financial.contracts', compact('report', 'startDate', 'endDate', 'facilities'));
    }

    /**
     * تقرير العميل
     */
    public function customer(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
        ]);

        $facilityId = $request->get('facility_id');
        $customerId = $request->customer_id;

        $report = $this->financialReportService->getCustomerReport($customerId, $facilityId);
        $facilities = Facility::all();

        return view('admin.financial.customer', compact('report', 'facilities'));
    }

    /**
     * تقرير المالك
     */
    public function owner(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:users,id',
        ]);

        $facilityId = $request->get('facility_id');
        $ownerId = $request->owner_id;

        $report = $this->financialReportService->getOwnerReport($ownerId, $facilityId);
        $facilities = Facility::all();

        return view('admin.financial.owner', compact('report', 'facilities'));
    }

    /**
     * تقرير شهري
     */
    public function monthly(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $facilityId = $request->facility_id;
        $year = $request->year;
        $month = $request->month;

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $report = $this->financialReportService->getFacilityFinancialSummary($facilityId, $startDate, $endDate);
        $facilities = Facility::all();

        return view('admin.financial.monthly', compact('report', 'year', 'month', 'facilities'));
    }

    /**
     * تقرير سنوي
     */
    public function yearly(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $facilityId = $request->facility_id;
        $year = $request->year;

        $startDate = Carbon::create($year, 1, 1)->startOfYear();
        $endDate = Carbon::create($year, 12, 31)->endOfYear();

        $report = $this->financialReportService->getFacilityFinancialSummary($facilityId, $startDate, $endDate);
        $facilities = Facility::all();

        return view('admin.financial.yearly', compact('report', 'year', 'facilities'));
    }

    /**
     * تقرير شامل للمنشأة
     */
    public function facilitySummary(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $facilityId = $request->facility_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = $this->financialReportService->getFacilityFinancialSummary($facilityId, $startDate, $endDate);
        $facilities = Facility::all();

        return view('admin.financial.facility-summary', compact('report', 'facilities'));
    }

    /**
     * تصدير التقرير
     */
    public function export(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();
        $reportType = $request->get('type', 'summary');

        if ($facilityId) {
            $report = $this->financialReportService->getFacilityFinancialSummary($facilityId, $startDate, $endDate);
        } else {
            // تقرير شامل لجميع المنشآت
            $facilities = Facility::all();
            $report = [
                'period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                ],
                'summary' => [
                    'total_revenue' => 0,
                    'total_payments' => 0,
                    'total_commissions' => 0,
                    'net_income' => 0,
                    'collection_rate' => 0,
                ]
            ];

            foreach ($facilities as $facility) {
                $facilityReport = $this->financialReportService->getFacilityFinancialSummary($facility->id, $startDate, $endDate);
                $report['summary']['total_revenue'] += $facilityReport['summary']['total_revenue'];
                $report['summary']['total_payments'] += $facilityReport['summary']['total_payments'];
                $report['summary']['total_commissions'] += $facilityReport['summary']['total_commissions'];
            }

            $report['summary']['net_income'] = $report['summary']['total_revenue'] - $report['summary']['total_commissions'];
            $report['summary']['collection_rate'] = $report['summary']['total_revenue'] > 0 
                ? ($report['summary']['total_payments'] / $report['summary']['total_revenue']) * 100 
                : 0;
        }

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
