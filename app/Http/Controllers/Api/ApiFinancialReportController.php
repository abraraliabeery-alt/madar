<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiFinancialReportController extends Controller
{
    protected $financialReportService;

    public function __construct(FinancialReportService $financialReportService)
    {
        $this->financialReportService = $financialReportService;
    }

    /**
     * تقرير الإيرادات
     */
    public function revenue(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : null;

        $report = $this->financialReportService->getRevenueReport($facilityId, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * تقرير الذمم المدينة
     */
    public function receivables(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $report = $this->financialReportService->getReceivablesReport($facilityId);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * تقرير العمولات
     */
    public function commissions(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : null;

        $report = $this->financialReportService->getCommissionReport($facilityId, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * تقرير المدفوعات
     */
    public function payments(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : null;

        $report = $this->financialReportService->getPaymentsReport($facilityId, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * تقرير الفواتير
     */
    public function invoices(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : null;

        $report = $this->financialReportService->getInvoicesReport($facilityId, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
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
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : null;

        $report = $this->financialReportService->getFacilityFinancialSummary($facilityId, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * تقرير العقود
     */
    public function contracts(Request $request)
    {
        $facilityId = $request->get('facility_id');
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : null;

        $report = $this->financialReportService->getContractsReport($facilityId, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * تقرير العميل
     */
    public function customer(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'facility_id' => 'nullable|exists:facilities,id',
        ]);

        $customerId = $request->customer_id;
        $facilityId = $request->get('facility_id');

        $report = $this->financialReportService->getCustomerReport($customerId, $facilityId);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * تقرير المالك
     */
    public function owner(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:users,id',
            'facility_id' => 'nullable|exists:facilities,id',
        ]);

        $ownerId = $request->owner_id;
        $facilityId = $request->get('facility_id');

        $report = $this->financialReportService->getOwnerReport($ownerId, $facilityId);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
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

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
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

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}
