<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Contract;
use Carbon\Carbon;

class FacilityRentalsReportController extends Controller
{
    public function occupancy(Request $request)
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        $all = Product::with('translations')->where('facility_id', $facility->id)->get(['id', 'parent_id']);
        $byId = $all->keyBy('id');
        $parentIds = $all->pluck('parent_id')->filter()->unique();

        $rootOf = function (Product $product) use ($byId) {
            $current = $product;
            $guard = 0;
            while ($current->parent_id && $byId->has($current->parent_id) && $guard++ < 20) {
                $current = $byId[$current->parent_id];
            }
            return $current->id === $product->id ? null : $current->id;
        };

        // الوحدات = العقارات التي ليس لها أبناء
        $units = $all->reject(fn ($p) => $parentIds->contains($p->id))
            ->map(fn ($p) => (object) ['id' => $p->id, 'parent_id' => $p->parent_id, 'root_id' => $rootOf($p)])
            ->when($request->filled('root_id'), fn ($c) => $c->where('root_id', (int) $request->root_id))
            ->when($request->filled('parent_id'), fn ($c) => $c->where('parent_id', (int) $request->parent_id))
            ->values();

        $today = Carbon::today()->toDateString();
        $occupiedIds = Contract::query()
            ->where('facility_id', $facility->id)
            ->where('contract_type', 'rent')
            ->where('status', 'active')
            ->whereIn('product_id', $units->pluck('id'))
            ->whereDate('start_date', '<=', $today)
            ->where(fn ($q) => $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today))
            ->pluck('product_id')
            ->unique();

        $breakdown = fn ($group) => [
            'total' => $group->count(),
            'active' => $group->pluck('id')->intersect($occupiedIds)->count(),
            'rate' => $group->count() ? round($group->pluck('id')->intersect($occupiedIds)->count() / $group->count() * 100, 1) : 0,
        ];

        $roots = $all->filter(fn ($p) => !$p->parent_id && $parentIds->contains($p->id))->values();
        $parents = $all->filter(fn ($p) => $parentIds->contains($p->id))->values();

        return view('facility.reports.rentals_occupancy', [
            'roots' => $roots,
            'parents' => $parents,
            'labels' => $parents->mapWithKeys(fn ($p) => [$p->id => $p->getTranslatedTitle() ?: '#' . $p->id]),
            'rentableCount' => $units->count(),
            'occupiedLikeCount' => $occupiedIds->count(),
            'byRoot' => $units->groupBy(fn ($u) => $u->root_id ?? 0)->map($breakdown),
            'byParent' => $units->groupBy(fn ($u) => $u->parent_id ?? 0)->map($breakdown),
        ]);
    }

    public function collections(Request $request)
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : Carbon::now()->startOfMonth();
        $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : Carbon::now()->endOfDay();

        $invoicesTotal = Invoice::query()->where('facility_id', $facility->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('amount');
        $paymentsTotal = Payment::query()->where('facility_id', $facility->id)
            ->where('status', 'confirmed')
            ->whereBetween('payment_date', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->sum('amount');

        // المتأخرات: فواتير مستحقة خلال الفترة ولم تُسدد بالكامل
        $arrearsQuery = Invoice::query()->where('facility_id', $facility->id)
            ->whereBetween('due_date', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->where('remaining_amount', '>', 0)
            ->whereNotIn('status', ['paid', 'cancelled']);

        return view('facility.reports.rentals_collections', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'invoicesTotal' => $invoicesTotal,
            'paymentsTotal' => $paymentsTotal,
            'arrearsCount' => (clone $arrearsQuery)->count(),
            'arrearsTotal' => (clone $arrearsQuery)->sum('remaining_amount'),
        ]);
    }

    /**
     * الوحدات الطرفية للمنشأة (منتجات ليس لها أبناء) مع جذرها
     */
    private function unitsWithRoots($facility)
    {
        $all = Product::with('translations')->where('facility_id', $facility->id)->get(['id', 'parent_id']);
        $byId = $all->keyBy('id');
        $parentIds = $all->pluck('parent_id')->filter()->unique();

        $units = $all->reject(fn ($p) => $parentIds->contains($p->id));
        $roots = $units->mapWithKeys(function ($p) use ($byId) {
            $current = $p;
            $guard = 0;
            while ($current->parent_id && $byId->has($current->parent_id) && $guard++ < 20) {
                $current = $byId[$current->parent_id];
            }
            return [$p->id => $current->id === $p->id ? null : $current->id];
        });

        return [$units->values(), $roots, $all];
    }

    private function activeRentContracts($facility, $unitIds)
    {
        $today = Carbon::today()->toDateString();
        return Contract::where('facility_id', $facility->id)
            ->where('contract_type', 'rent')
            ->where('status', 'active')
            ->whereIn('product_id', $unitIds)
            ->whereDate('start_date', '<=', $today)
            ->where(fn ($q) => $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today))
            ->with(['tenant', 'user', 'product.translations'])
            ->get();
    }

    private function buildExecutiveData($facility)
    {
        [$units, $roots] = $this->unitsWithRoots($facility);
        $unitIds = $units->pluck('id');

        $activeContracts = $this->activeRentContracts($facility, $unitIds);
        $occupiedIds = $activeContracts->pluck('product_id')->unique();

        $invoicesTotal = Invoice::where('facility_id', $facility->id)->sum('amount');
        $totalCollected = Payment::where('facility_id', $facility->id)->where('status', 'confirmed')->sum('amount');
        $totalArrears = Invoice::where('facility_id', $facility->id)
            ->where('due_date', '<', Carbon::today())
            ->where('remaining_amount', '>', 0)
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->sum('remaining_amount');

        $months = collect(range(5, 0))->map(function ($i) use ($facility) {
            $date = Carbon::now()->subMonths($i);
            return [
                'label' => $date->format('m/Y'),
                'revenue' => Payment::where('facility_id', $facility->id)
                    ->where('status', 'confirmed')
                    ->whereYear('payment_date', $date->year)
                    ->whereMonth('payment_date', $date->month)
                    ->sum('amount'),
            ];
        });

        $topProducts = Payment::where('payments.facility_id', $facility->id)
            ->where('payments.status', 'confirmed')
            ->join('contracts', 'payments.contract_id', '=', 'contracts.id')
            ->selectRaw('contracts.product_id, SUM(payments.amount) as total')
            ->groupBy('contracts.product_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $topProductNames = Product::with('translations')->whereIn('id', $topProducts->pluck('product_id'))->get()->keyBy('id');

        $openMaintenanceQuery = \App\Models\MaintenanceRequest::where('facility_id', $facility->id)
            ->whereNotIn('status', ['completed', 'cancelled']);

        return [
            'totalUnits' => $units->count(),
            'occupiedUnits' => $occupiedIds->count(),
            'occupancyRate' => $units->count() ? round($occupiedIds->count() / $units->count() * 100, 1) : 0,
            'collectionRate' => $invoicesTotal > 0 ? round($totalCollected / $invoicesTotal * 100, 1) : 0,
            'totalCollected' => $totalCollected,
            'totalArrears' => $totalArrears,
            'months' => $months,
            'topProducts' => $topProducts,
            'topProductNames' => $topProductNames,
            'expiringContracts' => Contract::where('facility_id', $facility->id)
                ->where('status', 'active')
                ->whereNotNull('end_date')
                ->whereDate('end_date', '>=', Carbon::today())
                ->whereDate('end_date', '<=', Carbon::today()->addDays(60))
                ->with(['tenant', 'user', 'product.translations'])
                ->orderBy('end_date')->get(),
            'openMaintenance' => (clone $openMaintenanceQuery)->count(),
            'agingMaintenance' => (clone $openMaintenanceQuery)->where('created_at', '<', Carbon::now()->subDays(30))->count(),
            'vacantLong' => $units->whereNotIn('id', $occupiedIds)->count(),
            'criticalContracts' => Contract::where('facility_id', $facility->id)
                ->where('status', 'active')
                ->whereNotNull('next_payment_date')
                ->whereDate('next_payment_date', '<', Carbon::today()->subDays(15))
                ->count(),
        ];
    }

    /**
     * التقرير التنفيذي
     */
    public function executive()
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        return view('facility.reports.executive', $this->buildExecutiveData($facility));
    }

    public function executivePdf()
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        return view('facility.reports.executive_pdf', $this->buildExecutiveData($facility) + ['facility' => $facility]);
    }

    /**
     * تقرير المحفظة: الأصول ووحداتها
     */
    private function buildPortfolioRows($facility)
    {
        [$units, $roots, $all] = $this->unitsWithRoots($facility);
        $unitIds = $units->pluck('id');
        $activeContracts = $this->activeRentContracts($facility, $unitIds);
        $occupiedIds = $activeContracts->pluck('product_id')->unique();
        $contractsByUnit = $activeContracts->groupBy('product_id');

        $contractIds = Contract::where('facility_id', $facility->id)->whereIn('product_id', $unitIds)->pluck('id');
        $collectedByContract = Payment::where('status', 'confirmed')->whereIn('contract_id', $contractIds)
            ->selectRaw('contract_id, SUM(amount) as total')->groupBy('contract_id')->pluck('total', 'contract_id');
        $arrearsByContract = Invoice::whereIn('contract_id', $contractIds)
            ->where('due_date', '<', Carbon::today())->where('remaining_amount', '>', 0)
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->selectRaw('contract_id, SUM(remaining_amount) as total')->groupBy('contract_id')->pluck('total', 'contract_id');
        $contractProduct = Contract::where('facility_id', $facility->id)->whereIn('id', $contractIds)->pluck('product_id', 'id');

        return $units->groupBy(fn ($u) => $roots[$u->id] ?? $u->id)->map(function ($group, $rootId) use ($all, $occupiedIds, $contractsByUnit, $contractIds, $collectedByContract, $arrearsByContract, $contractProduct) {
            $groupIds = $group->pluck('id');
            $groupContractIds = $contractIds->filter(fn ($cid) => $groupIds->contains($contractProduct[$cid]));
            $collected = $groupContractIds->sum(fn ($cid) => $collectedByContract[$cid] ?? 0);
            $arrears = $groupContractIds->sum(fn ($cid) => $arrearsByContract[$cid] ?? 0);
            $occupied = $group->pluck('id')->intersect($occupiedIds)->count();

            return (object) [
                'asset' => $all->firstWhere('id', $rootId) ?? $group->first(),
                'units' => $group->count(),
                'occupied' => $occupied,
                'occupancy' => $group->count() ? round($occupied / $group->count() * 100) : 0,
                'contracts' => $group->pluck('id')->map(fn ($id) => $contractsByUnit->get($id))->filter()->flatten()->count(),
                'collected' => $collected,
                'arrears' => $arrears,
                'profit' => $collected - $arrears,
            ];
        })->values();
    }

    public function portfolio()
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        return view('facility.reports.portfolio', ['rows' => $this->buildPortfolioRows($facility)]);
    }

    public function portfolioPdf()
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        return view('facility.reports.portfolio_pdf', ['rows' => $this->buildPortfolioRows($facility), 'facility' => $facility]);
    }

    /**
     * مركز المتأخرات
     */
    public function overdue()
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        $today = Carbon::today();

        $overdueInvoices = Invoice::where('facility_id', $facility->id)
            ->where('due_date', '<', $today)
            ->where('remaining_amount', '>', 0)
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->with(['contract.tenant', 'contract.user', 'contract.product.translations'])
            ->orderBy('due_date')
            ->get();

        $overdueContracts = Contract::where('facility_id', $facility->id)
            ->where('status', 'active')
            ->whereNotNull('next_payment_date')
            ->whereDate('next_payment_date', '<', $today)
            ->with(['tenant', 'user', 'product.translations'])
            ->orderBy('next_payment_date')
            ->get();

        return view('facility.reports.overdue_center', [
            'overdueInvoices' => $overdueInvoices,
            'overdueContracts' => $overdueContracts,
            'totalArrears' => $overdueInvoices->sum('remaining_amount'),
        ]);
    }
}
