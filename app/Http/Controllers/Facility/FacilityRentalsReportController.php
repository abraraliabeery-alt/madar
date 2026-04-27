<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Building;
use App\Models\Contract;
use Carbon\Carbon;

class FacilityRentalsReportController extends Controller
{
    public function occupancy(Request $request)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        $projects = Project::where('facility_id', $facility->id)->get(['id']);
        $buildings = Building::where('facility_id', $facility->id)->get(['id']);

        $products = Product::query()
            ->where('facility_id', $facility->id)
            ->when($request->filled('project_id'), fn($q)=>$q->where('project_id', $request->project_id))
            ->when($request->filled('building_id'), fn($q)=>$q->where('building_id', $request->building_id))
            ->get(['id','project_id','building_id','available_for_rent']);

        $productIds = $products->pluck('id');

        $activeRentalOffers = Offer::query()
            ->whereIn('product_id', $productIds)
            ->whereIn('offer_type', ['rent_daily','rent_monthly','rent_yearly'])
            ->where('is_active', true)
            ->get(['id','product_id']);

        // Active rent contracts within date range (today inside start/end and active)
        $today = Carbon::today();
        $activeContractProductIds = Contract::query()
            ->where('facility_id', $facility->id)
            ->where('contract_type', 'rent')
            ->where('status', 'active')
            ->whereIn('product_id', $productIds)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->pluck('product_id')
            ->unique();

        // Fallback to active offers only for products that don't have active contracts
        $offerProductIds = $activeRentalOffers->pluck('product_id')->unique();
        $occupiedIds = $activeContractProductIds
            ->merge($offerProductIds->diff($activeContractProductIds))
            ->unique();

        $rentableCount = $products->count();
        $occupiedLikeCount = $occupiedIds->count();

        // Group by project/building for breakdown
        $byProject = $products->groupBy('project_id')->map(function($group) use ($occupiedIds){
            $ids = $group->pluck('id');
            $active = $ids->intersect($occupiedIds)->count();
            return [
                'total' => $group->count(),
                'active' => $active,
                'rate' => $group->count() ? round(($active/$group->count())*100, 1) : 0,
            ];
        });

        $byBuilding = $products->groupBy('building_id')->map(function($group) use ($occupiedIds){
            $ids = $group->pluck('id');
            $active = $ids->intersect($occupiedIds)->count();
            return [
                'total' => $group->count(),
                'active' => $active,
                'rate' => $group->count() ? round(($active/$group->count())*100, 1) : 0,
            ];
        });

        return view('facility.reports.rentals_occupancy', [
            'projects' => $projects,
            'buildings' => $buildings,
            'rentableCount' => $rentableCount,
            'occupiedLikeCount' => $occupiedLikeCount,
            'byProject' => $byProject,
            'byBuilding' => $byBuilding,
        ]);
    }

    public function collections(Request $request)
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : Carbon::now()->startOfMonth();
        $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : Carbon::now()->endOfDay();

        $invoicesQuery = Invoice::query()->where('facility_id', $facility->id)
            ->whereBetween('issue_date', [$dateFrom, $dateTo]);
        $paymentsQuery = Payment::query()->where('facility_id', $facility->id)
            ->whereBetween('paid_at', [$dateFrom, $dateTo]);

        $invoicesTotal = (clone $invoicesQuery)->sum('total');
        $paymentsTotal = (clone $paymentsQuery)->sum('amount');

        // Arrears: invoices due within range, unpaid or partial
        $arrearsQuery = Invoice::query()->where('facility_id', $facility->id)
            ->whereBetween('due_date', [$dateFrom, $dateTo])
            ->where(function($q){ $q->whereNull('paid_at')->orWhere('status','!=','paid'); });
        $arrearsCount = (clone $arrearsQuery)->count();
        $arrearsTotal = (clone $arrearsQuery)->sum('total');

        return view('facility.reports.rentals_collections', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'invoicesTotal' => $invoicesTotal,
            'paymentsTotal' => $paymentsTotal,
            'arrearsCount' => $arrearsCount,
            'arrearsTotal' => $arrearsTotal,
        ]);
    }
}
