<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProductLifecycleController extends Controller
{
    /**
     * Return lifecycle/funnel metrics for the current facility (JSON)
     * Behind feature flag features.facility_lifecycle_widgets
     */
    public function metrics(Request $request)
    {
        abort_unless(config('features.facility_lifecycle_widgets'), 404);

        $user = Auth::user();
        $facility = $user?->facilities()->first();
        abort_unless($facility, 404);

        $cacheKey = 'facility_lifecycle_metrics_'.$facility->id;
        $data = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($facility) {
            $totalProducts = (int) $facility->products()->count();
            $activeContracts = (int) $facility->contracts()->where('status', 'active')->count();
            $openTasks = (int) $facility->tasks()->whereIn('status', ['open','in_progress'])->count();

            // Funnel basics
            $views = (int) ($facility->products()->sum('views_count') ?? 0);
            $favorites = 0;
            try {
                $favorites = (int) $facility->products()
                    ->join('favorites', 'favorites.favoritable_id', '=', 'products.id')
                    ->where('favorites.favoritable_type', '=', \App\Models\Product::class)
                    ->count();
            } catch (\Throwable $e) {}
            $bookings = (int) $facility->bookings()->count();

            // Quality alerts (light)
            $missingImage = (int) $facility->products()->where(function($q){
                $q->whereNull('main_image')->orWhere('main_image', '');
            })->count();
            $missingLocation = (int) $facility->products()->whereNull('latitude')->orWhereNull('longitude')->count();
            $missingPrice = (int) $facility->products()->where(function($q){
                $q->whereNull('price')->orWhere('price','<=',0);
            })->count();

            // Customer stages (best-effort)
            $leads = 0; $negotiation = 0; $customers = 0; $activePayers = 0; $overdueCustomers = 0;
            try { $leads = (int) $facility->bookings()->distinct('user_id')->count('user_id'); } catch (\Throwable $e) {}
            try { $negotiation = (int) $facility->offers()->distinct('user_id')->count('user_id'); } catch (\Throwable $e) {}
            try { $customers = (int) $facility->contracts()->distinct('user_id')->count('user_id'); } catch (\Throwable $e) {}
            try { $activePayers = (int) $facility->invoices()->where('status','paid')->distinct('user_id')->count('user_id'); } catch (\Throwable $e) {}
            try { $overdueCustomers = (int) $facility->invoices()->where('status','overdue')->distinct('user_id')->count('user_id'); } catch (\Throwable $e) {}

            return [
                'summary' => [
                    'total_products' => $totalProducts,
                    'active_contracts' => $activeContracts,
                    'open_tasks' => $openTasks,
                    'occupancy_percent' => $totalProducts > 0 ? round(($activeContracts / $totalProducts) * 100, 1) : 0,
                ],
                'funnel' => [
                    'views' => $views,
                    'favorites' => $favorites,
                    'bookings' => $bookings,
                ],
                'quality' => [
                    'missing_image' => $missingImage,
                    'missing_location' => $missingLocation,
                    'missing_price' => $missingPrice,
                ],
                'customer_stages' => [
                    'leads' => $leads,
                    'negotiation' => $negotiation,
                    'customers' => $customers,
                    'active_payers' => $activePayers,
                    'overdue_customers' => $overdueCustomers,
                ],
            ];
        });

        return response()->json($data);
    }
}
