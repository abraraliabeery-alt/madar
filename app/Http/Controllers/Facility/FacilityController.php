<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Product;
use App\Models\Booking;
use App\Models\Contract;
use App\Models\Task;
use App\Models\User;
use App\Models\Offer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ExecutionRequest;
use App\Models\ExecutionBid;
use App\Models\FacilityDocument;
use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Models\MaintenanceRequest;

class FacilityController extends Controller
{
    protected function createFacilityMinimalFromRequest(Request $request): Facility
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'facility_category_id' => 'required|exists:facility_categories,id',
        ]);

        $data['owner_user_id'] = Auth::id();

        $facility = Facility::create($data);
        $facility->users()->syncWithoutDetaching([Auth::id()]);

        $user = Auth::user();
        if ($user && method_exists($user, 'hasRole') && method_exists($user, 'assignRole') && !$user->hasRole('facility')) {
            $user->assignRole('facility');
        }

        return $facility;
    }

    protected function createFacilityFromRequest(Request $request): Facility
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'facility_category_id' => 'required|exists:facility_categories,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'snapchat' => 'nullable|string',
            'tiktok' => 'nullable|string',
            'pinterest' => 'nullable|url',
            'youtube' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'telegram' => 'nullable|string',
            'working_hours' => 'nullable|string',
        ]);

        $facilityData = $request->except(['logo', 'cover_image']);
        $facilityData['owner_user_id'] = Auth::id();

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('facilities/logos', 'public');
            $facilityData['logo'] = $logoPath;
        }

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('facilities/covers', 'public');
            $facilityData['cover_image'] = $coverPath;
        }

        $facility = Facility::create($facilityData);
        $facility->users()->syncWithoutDetaching([Auth::id()]);

        $user = Auth::user();
        if ($user && method_exists($user, 'hasRole') && method_exists($user, 'assignRole') && !$user->hasRole('facility')) {
            $user->assignRole('facility');
        }

        return $facility;
    }

    /**
     * عرض لوحة تحكم المنشأة
     */
    public function dashboard()
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        // Redirect to enhanced home-v2 when feature flag is enabled
        if (config('features.facility_home_v2')) {
            return redirect()->route('facility.home-v2');
        }

        $ownedExecutionRequestsQuery = ExecutionRequest::query()->where('facility_id', $facility->id);
        $ownedExecutionRequestIds = (clone $ownedExecutionRequestsQuery)->pluck('id');

        $stats = [
            // Execution / contracting KPIs
            'total_execution_requests' => (clone $ownedExecutionRequestsQuery)->count(),
            'open_execution_requests' => (clone $ownedExecutionRequestsQuery)->where('status', 'open')->count(),
            'total_execution_bids_received' => $ownedExecutionRequestIds->isEmpty()
                ? 0
                : ExecutionBid::whereIn('execution_request_id', $ownedExecutionRequestIds)->count(),
            'recent_execution_requests' => (clone $ownedExecutionRequestsQuery)
                ->with(['translations'])
                ->latest()
                ->take(5)
                ->get(),

            // Keep existing accounting/admin stats (still useful for facilities)
            'total_contracts' => $facility->contracts()->count(),
            'total_tasks' => $facility->tasks()->count(),
            'total_employees' => $facility->users()->count(),
            'total_users' => $facility->users()->count(),
            'total_invoices' => $facility->invoices()->count(),
            'total_payments' => $facility->payments()->count(),
            'recent_tasks' => $facility->tasks()->with(['assignedTo'])->latest()->take(5)->get(),
            'completed_tasks' => $facility->tasks()->where('status', 'completed')->count(),
            'unread_notifications' => $user->unreadNotifications()->count(),
        ];

        $category = $facility->facilityCategory;
        $isRealEstate = $category?->isRealEstate() ?? true;
        $isContracting = $category?->isContracting() ?? true;

        if ($isRealEstate) {
            $stats = array_merge($stats, $this->realEstateDashboardStats($facility));
        }

        return view('facility.dashboard', compact('facility', 'stats', 'isRealEstate', 'isContracting'));
    }

    private function realEstateDashboardStats(Facility $facility): array
    {
        $today = now();
        $confirmedPayments = fn () => $facility->payments()->where('status', 'confirmed');
        $openInvoices = fn () => $facility->invoices()->where('remaining_amount', '>', 0)->whereNotIn('status', ['paid', 'cancelled']);

        $revenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $today->copy()->subMonths($i);
            $revenueTrend[$month->format('Y-m')] = (float) $confirmedPayments()
                ->whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month)
                ->sum('amount');
        }

        $overdueInvoices = $openInvoices()->whereDate('due_date', '<', $today->toDateString());
        $overdueContracts = (clone $overdueInvoices)->whereNotNull('contract_id')->distinct()->count('contract_id');
        $overdueAmount = (float) (clone $overdueInvoices)->sum('remaining_amount');

        $totalProperties = $facility->products()->count();
        $rentedProperties = $facility->products()
            ->whereHas('contracts', fn ($q) => $q->where('status', 'active'))
            ->count();

        $leadIds = $facility->leads()->pluck('id');
        $followupsToday = LeadActivity::with('lead')
            ->whereIn('lead_id', $leadIds)
            ->whereBetween('follow_up_at', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->orderBy('follow_up_at');

        $suggestions = collect();
        if ($overdueAmount > 0) {
            $suggestions->push((object) [
                'icon' => 'fa-triangle-exclamation', 'color' => 'danger',
                'text' => 'لديك فواتير متأخرة بقيمة ' . number_format($overdueAmount, 0) . ' ر.س — تابع التحصيل',
                'url' => route('facility.invoices.index'), 'action' => 'عرض الفواتير', 'method' => 'GET',
            ]);
        }
        $vacant = $totalProperties - $rentedProperties;
        if ($vacant > 0) {
            $suggestions->push((object) [
                'icon' => 'fa-door-open', 'color' => 'warning',
                'text' => "{$vacant} عقار شاغر — أنشئ عرضاً لتسويقه",
                'url' => route('facility.offers.index'), 'action' => 'العروض', 'method' => 'GET',
            ]);
        }

        $recentActivity = collect()
            ->merge($confirmedPayments()->latest()->take(4)->get()->map(fn ($p) => (object) [
                'color' => 'success', 'icon' => 'fa-money-bill-wave',
                'text' => 'دفعة مؤكدة ' . number_format($p->amount, 0) . ' ر.س', 'time' => $p->created_at,
            ]))
            ->merge($facility->bookings()->latest()->take(4)->get()->map(fn ($b) => (object) [
                'color' => 'warning', 'icon' => 'fa-calendar-check',
                'text' => 'طلب معاينة من ' . ($b->name ?: 'زائر'), 'time' => $b->created_at,
            ]))
            ->merge($facility->leads()->latest()->take(4)->get()->map(fn ($l) => (object) [
                'color' => 'primary', 'icon' => 'fa-user-plus',
                'text' => 'عميل محتمل جديد: ' . $l->name, 'time' => $l->created_at,
            ]))
            ->sortByDesc('time')
            ->take(6)
            ->values();

        return [
            'collected_month' => $revenueTrend[$today->format('Y-m')],
            'collected_prev_month' => $revenueTrend[$today->copy()->subMonth()->format('Y-m')],
            'collection_target' => null,
            'receivables_due' => (float) $openInvoices()->sum('remaining_amount'),
            'overdue_contracts' => $overdueContracts,
            'expected_next_month' => (float) $openInvoices()
                ->whereYear('due_date', $today->year)
                ->whereMonth('due_date', $today->month)
                ->sum('remaining_amount'),
            'active_contracts' => $facility->contracts()->where('status', 'active')->count(),
            'occupancy' => ['total' => $totalProperties, 'rented' => $rentedProperties],
            'suggestions' => $suggestions,
            'total_properties' => $totalProperties,
            'total_tenants' => $facility->tenants()->count(),
            'total_bookings' => $facility->bookings()->count(),
            'new_leads' => $facility->leads()->where('stage', Lead::STAGE_NEW)->count(),
            'due_followups' => (clone $followupsToday)->count(),
            'today_viewings' => $facility->bookings()->whereDate('preferred_date', $today->toDateString())->count(),
            'today_viewings_list' => $facility->bookings()
                ->with(['user', 'product'])
                ->whereDate('preferred_date', $today->toDateString())
                ->orderBy('preferred_time')
                ->take(5)
                ->get(),
            'due_followups_list' => $followupsToday->take(5)->get(),
            'recent_contracts' => $facility->contracts()->with(['tenant.translations', 'user'])->latest()->take(5)->get(),
            'recent_activity' => $recentActivity,
            'revenue_trend' => $revenueTrend,
            'contracts_by_status' => $facility->contracts()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
        ];
    }

    /**
     * لوحة منزلية خفيفة اختيارية (وراء علم) مع مؤشرات قراءة فقط وكاش
     */
    public function homeV2(Request $request)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.onboarding.create')
                ->with('error', __('يجب إنشاء منشأة أولاً'));
        }

        // Optional UI v2: derive time range filters (non-breaking)
        $useUiV2 = (bool) config('features.facility_ui_v2');
        $range = $useUiV2 ? ($request->query('range', '30d')) : null;
        $from = null; $to = null;
        if ($useUiV2) {
            $today = now()->endOfDay();
            switch ($range) {
                case 'today':
                    $from = now()->startOfDay(); $to = $today; break;
                case '7d':
                    $from = now()->subDays(6)->startOfDay(); $to = $today; break;
                case '30d':
                    $from = now()->subDays(29)->startOfDay(); $to = $today; break;
                case 'month':
                    $from = now()->startOfMonth(); $to = $today; break;
                default:
                    // custom support via from/to query (Y-m-d)
                    $from = $request->query('from') ? now()->parse($request->query('from'))->startOfDay() : now()->subDays(29)->startOfDay();
                    $to = $request->query('to') ? now()->parse($request->query('to'))->endOfDay() : $today;
                    $range = 'custom';
            }
        }

        $cacheKey = 'facility_home_v2_stats_'.$facility->id.'_'.md5(($range ?? 'default').'_'.($from?->toDateString() ?? '').'_'.($to?->toDateString() ?? ''));
        $stats = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($facility, $useUiV2, $from, $to) {
            $ownedExecutionRequestsQuery = ExecutionRequest::query()->where('facility_id', $facility->id);
            $ownedExecutionRequestIds = (clone $ownedExecutionRequestsQuery)->pluck('id');

            $totalExecutionRequests = (int) (clone $ownedExecutionRequestsQuery)->count();
            $openExecutionRequests = (int) (clone $ownedExecutionRequestsQuery)->where('status', 'open')->count();
            $totalBidsReceived = (int) ($ownedExecutionRequestIds->isEmpty()
                ? 0
                : ExecutionBid::whereIn('execution_request_id', $ownedExecutionRequestIds)->count());

            $activeContracts = (int) $facility->contracts()->where('status', 'active')->count();
            $openTasks = (int) $facility->tasks()->whereIn('status', ['open','in_progress'])->count();

            // Recent execution requests (instead of top/bottom products)
            $recentExecutionRequests = (clone $ownedExecutionRequestsQuery)
                ->with(['translations'])
                ->latest('created_at')
                ->take(10)
                ->get(['id','status','type','budget_min','budget_max','due_date','created_at']);

            return [
                // execution-focused
                'total_execution_requests' => $totalExecutionRequests,
                'open_execution_requests' => $openExecutionRequests,
                'total_execution_bids_received' => $totalBidsReceived,
                'total_contracts' => (int) $facility->contracts()->count(),
                'total_invoices_month' => (int) ($useUiV2
                    ? $facility->invoices()->when($from && $to, function($q) use ($from,$to){ $q->whereBetween('created_at', [$from,$to]); })->count()
                    : $facility->invoices()->whereMonth('created_at', now()->month)->count()),
                'overdue_invoices' => (int) $facility->invoices()->where('status', 'overdue')->count(),
                'paid_amount_month' => (float) ($useUiV2
                    ? $facility->invoices()->where('status','paid')->when($from && $to, function($q) use ($from,$to){ $q->whereBetween('updated_at', [$from,$to]); })->sum('paid_amount')
                    : $facility->invoices()->where('status','paid')->whereMonth('updated_at', now()->month)->sum('paid_amount')),

                // new KPIs
                'active_contracts' => $activeContracts,
                'occupancy_percent' => $totalExecutionRequests > 0 ? round(($activeContracts / $totalExecutionRequests) * 100, 1) : 0,
                'open_tasks' => $openTasks,
                'appointments' => (function() use ($facility) {
                    try {
                        $today = now()->toDateString();
                        $upcomingTo = now()->addDays(7)->toDateString();
                        return [
                            'today' => (int) $facility->appointments()
                                ->whereDate('appointment_time', $today)
                                ->count(),
                            'upcoming' => (int) $facility->appointments()
                                ->where('status', 'scheduled')
                                ->whereDate('appointment_time', '>', $today)
                                ->whereDate('appointment_time', '<=', $upcomingTo)
                                ->count(),
                        ];
                    } catch (\Throwable $e) {
                        return ['today' => 0, 'upcoming' => 0];
                    }
                })(),

                // recent execution requests list
                'recent_execution_requests' => $recentExecutionRequests,

                // recent items (ids and small payload only)
                // Keep recent tasks/invoices only (bookings are real-estate specific)
                'recent_invoices' => $facility->invoices()
                    ->latest('invoices.created_at')
                    ->take(5)
                    ->get(['invoices.id','invoices.status','invoices.amount','invoices.created_at']),
                'recent_tasks' => $facility->tasks()->latest()->take(5)->get(['id','type','status','priority','created_at']),

                // lifecycle widgets (computed only)
                'lifecycle' => (function() use ($facility) {
                    if (!config('features.facility_lifecycle_widgets')) {
                        return null;
                    }
                    $data = [
                        'funnel_plus' => [
                            'views' => 0,
                            'favorites' => 0,
                            'bookings' => 0,
                            'offers' => 0,
                            'contracts' => 0,
                            'invoices' => 0,
                            'payments' => 0,
                        ],
                        'customer_stages' => [
                            'leads' => 0,
                            'negotiation' => 0,
                            'customers' => 0,
                            'active_payers' => 0,
                            'overdue_customers' => 0,
                        ],
                    ];

                    // Keep contracts/invoices/payments only; execution metrics are shown elsewhere
                    try { $data['funnel_plus']['contracts'] = (int) $facility->contracts()->count(); } catch (\Throwable $e) {}
                    try { $data['funnel_plus']['invoices'] = (int) $facility->invoices()->count(); } catch (\Throwable $e) {}
                    try { $data['funnel_plus']['payments'] = (int) $facility->payments()->count(); } catch (\Throwable $e) {}

                    // customer stages using distinct users across artifacts
                    // Facilities may not have bookings/offers in contracting mode; keep zeros
                    try {
                        $data['customer_stages']['customers'] = (int) $facility->contracts()->distinct('user_id')->count('user_id');
                    } catch (\Throwable $e) {}
                    try {
                        $data['customer_stages']['active_payers'] = (int) $facility->invoices()->where('status','paid')->distinct('user_id')->count('user_id');
                    } catch (\Throwable $e) {}
                    try {
                        $data['customer_stages']['overdue_customers'] = (int) $facility->invoices()->where('status','overdue')->distinct('user_id')->count('user_id');
                    } catch (\Throwable $e) {}

                    return $data;
                })(),

                // Next Best Action widget (counts only)
                'nba' => (function() use ($facility) {
                    if (!config('features.facility_nba_widget')) { return null; }
                    $today = now()->toDateString();
                    $data = [
                        'invoices_overdue' => 0,
                        'invoices_due_today' => 0,
                        'bookings_today' => 0,
                        'low_quality_products' => 0,
                        'stale_leads_7d' => 0,
                    ];
                    try { $data['invoices_overdue'] = (int) $facility->invoices()->where('status','overdue')->count(); } catch (\Throwable $e) {}
                    try { $data['invoices_due_today'] = (int) $facility->invoices()->whereDate('due_date', $today)->where('status','!=','paid')->count(); } catch (\Throwable $e) {}
                    return $data;
                })(),
            ];
        });

        $filters = $useUiV2 ? [
            'enabled' => true,
            'range' => $range,
            'from' => $from?->toDateString(),
            'to' => $to?->toDateString(),
        ] : ['enabled' => false];

        return view('facility.home-v2', compact('facility', 'stats', 'filters'));
    }

    /**
     * عرض صفحة إنشاء منشأة
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->facilities()->exists()) {
            return redirect()->route('facility.dashboard')
                ->with('error', __('لديك منشأة بالفعل'));
        }

        return redirect()->route('facility.onboarding.create');
    }

    public function onboardingCreate()
    {
        $user = Auth::user();

        if ($user->facilities()->exists()) {
            if (method_exists($user, 'hasRole') && method_exists($user, 'assignRole') && !$user->hasRole('facility')) {
                $user->assignRole('facility');
            }

            return redirect()->route('facility.dashboard');
        }

        $facilityCategories = \App\Models\FacilityCategory::where('is_active', true)->orderBy('order')->get();

        return view('auth.register-facility', compact('facilityCategories'));
    }

    /**
     * حفظ منشأة جديدة
     */
    public function store(Request $request)
    {
        $this->createFacilityMinimalFromRequest($request);

        return redirect()->route('facility.dashboard')
            ->with('success', __('تم إنشاء المنشأة بنجاح'));
    }

    public function onboardingStore(Request $request)
    {
        $user = Auth::user();

        if ($user->facilities()->exists()) {
            if (method_exists($user, 'hasRole') && method_exists($user, 'assignRole') && !$user->hasRole('facility')) {
                $user->assignRole('facility');
            }

            return redirect()->route('facility.dashboard');
        }

        $this->createFacilityMinimalFromRequest($request);

        return redirect()->route('facility.dashboard')
            ->with('success', __('تم إنشاء المنشأة بنجاح'));
    }

    /**
     * عرض صفحة تعديل المنشأة
     */
    public function edit()
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.onboarding.create');
        }

        return view('facility.edit', compact('facility'));
    }

    /**
     * تحديث بيانات المنشأة
     */
    public function update(Request $request)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'category_id' => 'required|exists:categories,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'snapchat' => 'nullable|string',
            'tiktok' => 'nullable|string',
            'pinterest' => 'nullable|url',
            'youtube' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'telegram' => 'nullable|string',
            'working_hours' => 'nullable|string',
        ]);

        $facilityData = $request->except(['logo', 'cover_image']);

        // معالجة الشعار
        if ($request->hasFile('logo')) {
            // حذف الشعار القديم
            if ($facility->logo) {
                Storage::disk('public')->delete($facility->logo);
            }
            $logoPath = $request->file('logo')->store('facilities/logos', 'public');
            $facilityData['logo'] = $logoPath;
        }

        // معالجة صورة الغلاف
        if ($request->hasFile('cover_image')) {
            // حذف صورة الغلاف القديمة
            if ($facility->cover_image) {
                Storage::disk('public')->delete($facility->cover_image);
            }
            $coverPath = $request->file('cover_image')->store('facilities/covers', 'public');
            $facilityData['cover_image'] = $coverPath;
        }

        $facility->update($facilityData);

        return redirect()->route('facility.dashboard')
            ->with('success', __('تم تحديث بيانات المنشأة بنجاح'));
    }

    /**
     * عرض إحصائيات المنشأة
     */
    public function statistics()
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $monthlyStats = [
            'products' => $facility->products()->whereMonth('created_at', now()->month)->count(),
            'bookings' => $facility->bookings()->whereMonth('created_at', now()->month)->count(),
            'contracts' => $facility->contracts()->whereMonth('created_at', now()->month)->count(),
            'revenue' => $facility->bookings()->whereMonth('created_at', now()->month)->sum('total_amount'),
        ];

        return view('facility.statistics', compact('facility', 'monthlyStats'));
    }

    /**
     * عرض إعدادات المنشأة
     */
    public function settings()
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        return view('facility.settings', compact('facility'));
    }

    /**
     * حفظ إعدادات المنشأة
     */
    public function updateSettings(Request $request)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $request->validate([
            'notification_email' => 'nullable|email',
            'auto_approve_bookings' => 'boolean',
            'booking_advance_days' => 'nullable|integer|min:1',
            'max_booking_duration' => 'nullable|integer|min:1',
        ]);

        $facility->update($request->all());

        return redirect()->back()->with('success', __('تم تحديث الإعدادات بنجاح'));
    }

    /**
     * صفحة الملف التعريفي للمنشأة
     */
    public function profile()
    {
        $facility = Auth::user()->facilities()->first();
        if (!$facility) {
            return redirect()->route('facility.create');
        }
        return view('facility.profile', compact('facility'));
    }

    /**
     * تحديث الملف التعريفي للمنشأة (حقول أساسية فقط مؤقتًا)
     */
    public function updateProfile(Request $request)
    {
        $facility = Auth::user()->facilities()->first();
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'qualification_commercial_register' => 'nullable|file|max:8192|mimes:pdf,jpg,jpeg,png',
        ]);

        $facility->update($request->only(['name','description','address','phone','email','website']));

        $settings = (array) ($facility->customization_settings ?? []);
        $qdocs = (array) ($settings['qualification_docs'] ?? []);

        if ($request->hasFile('qualification_commercial_register')) {
            $file = $request->file('qualification_commercial_register');
            $path = $file->store('facilities/qualification/' . $facility->id, 'public');
            $qdocs['commercial_register'] = [
                'disk' => 'public',
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
                'uploaded_at' => now()->toISOString(),
            ];

            FacilityDocument::updateOrCreate(
                [
                    'facility_id' => $facility->id,
                    'type' => 'commercial_register',
                    'status' => 'active',
                ],
                [
                    'disk' => 'public',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size_bytes' => $file->getSize(),
                ]
            );
        }

        $settings['qualification_docs'] = $qdocs;
        $facility->customization_settings = $settings;
        $facility->save();

        return redirect()->route('facility.profile')->with('success', __('تم تحديث الملف التعريفي'));
    }

    /**
     * صفحة الإشعارات والتواصل
     */
    public function notifications()
    {
        $facility = Auth::user()->facilities()->first();
        if (!$facility) {
            return redirect()->route('facility.create');
        }
        $notifications = [];
        return view('facility.notifications', compact('facility','notifications'));
    }

    /**
     * تعليم الإشعارات كمقروءة
     */
    public function markNotificationsRead(Request $request)
    {
        return redirect()->route('facility.notifications')->with('success', __('تم تعليم الإشعارات كمقروءة'));
    }

    /**
     * تغيير كلمة المرور
     */
    public function changePassword()
    {
        return view('facility.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('كلمة المرور الحالية غير صحيحة')]);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        return back()->with('success', __('تم تغيير كلمة المرور بنجاح'));
    }

    /**
     * نظرة عامة على مجموعة المنشآت التابعة للمستخدم
     */
    public function group()
    {
        $facilities = Auth::user()->facilities()->with('facilityCategory')->get();

        $rows = $facilities->map(function ($facility) {
            $contracts = Contract::where('facility_id', $facility->id);
            return [
                'facility' => $facility,
                'products' => Product::where('facility_id', $facility->id)->count(),
                'contracts' => (clone $contracts)->count(),
                'active_contracts' => (clone $contracts)->where('status', 'active')->count(),
                'execution_requests' => ExecutionRequest::where('facility_id', $facility->id)->count(),
                'open_maintenance' => MaintenanceRequest::where('facility_id', $facility->id)
                    ->whereNotIn('status', ['completed', 'cancelled'])->count(),
                'invoices_total' => Invoice::where('facility_id', $facility->id)->sum('amount'),
                'invoices_paid' => Payment::where('facility_id', $facility->id)->where('status', 'confirmed')->sum('amount'),
            ];
        });

        $totals = [
            'facilities' => $facilities->count(),
            'products' => $rows->sum('products'),
            'contracts' => $rows->sum('contracts'),
            'active_contracts' => $rows->sum('active_contracts'),
            'execution_requests' => $rows->sum('execution_requests'),
            'open_maintenance' => $rows->sum('open_maintenance'),
            'invoices_total' => $rows->sum('invoices_total'),
            'invoices_paid' => $rows->sum('invoices_paid'),
        ];

        return view('facility.group', compact('rows', 'totals'));
    }

    /**
     * مركز التقارير
     */
    public function reports()
    {
        return view('facility.reports.index');
    }

    public function bookingReports()
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        $bookings = $facility->bookings()->with(['product.translations', 'user'])->latest()->paginate(15);

        $stats = [
            'total' => $facility->bookings()->count(),
            'pending' => $facility->bookings()->where('status', 'pending')->count(),
            'confirmed' => $facility->bookings()->where('is_confirmed', true)->count(),
            'revenue' => $facility->bookings()->where('is_paid', true)->sum('total_amount'),
        ];

        return view('facility.reports.bookings', compact('bookings', 'stats'));
    }

    public function productReports()
    {
        $facility = Auth::user()->facilities()->firstOrFail();
        $products = $facility->products()->with('translations')
            ->withCount('bookings')
            ->latest()->paginate(15);

        $stats = [
            'total' => $facility->products()->count(),
            'active' => $facility->products()->where('is_active', true)->count(),
        ];

        return view('facility.reports.products', compact('products', 'stats'));
    }

    public function revenueReports()
    {
        $facility = Auth::user()->facilities()->firstOrFail();

        $monthly = Payment::where('facility_id', $facility->id)
            ->where('status', 'confirmed')
            ->selectRaw('YEAR(payment_date) as y, MONTH(payment_date) as m, COUNT(*) as cnt, SUM(amount) as total')
            ->groupBy('y', 'm')
            ->orderByDesc('y')->orderByDesc('m')
            ->limit(12)
            ->get();

        return view('facility.reports.revenue', [
            'total' => Contract::where('facility_id', $facility->id)->sum('total_amount'),
            'invoicesTotal' => Invoice::where('facility_id', $facility->id)->sum('amount'),
            'paymentsTotal' => Payment::where('facility_id', $facility->id)->where('status', 'confirmed')->sum('amount'),
            'monthly' => $monthly,
        ]);
    }

    /**
     * بحث عام داخل منشأة المستخدم — يرجع JSON للبحث السريع في الترويسة
     */
    public function globalSearch(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $facility = Auth::user()->facilities()->first();
        if (!$facility) {
            return response()->json(['results' => []]);
        }

        $results = collect();

        $products = $facility->products()
            ->where(function ($q) use ($query) {
                $q->where('address', 'like', "%{$query}%")
                    ->orWhere('booking_number', 'like', "%{$query}%")
                    ->orWhereHas('translations', fn ($t) => $t->where('title', 'like', "%{$query}%"));
            })
            ->take(4)->get()
            ->map(fn ($p) => [
                'type' => 'product',
                'title' => $p->getTranslatedTitle() ?: ('#' . $p->id),
                'subtitle' => 'عقار',
                'url' => route('facility.products.show', $p),
            ]);

        $bookings = $facility->bookings()
            ->where(fn ($q) => $q->where('id', $query)->orWhere('name', 'like', "%{$query}%"))
            ->take(3)->get()
            ->map(fn ($b) => [
                'type' => 'booking',
                'title' => 'حجز #' . $b->id,
                'subtitle' => $b->name ?? 'حجز',
                'url' => route('facility.bookings.show', $b),
            ]);

        $contracts = Contract::where('facility_id', $facility->id)
            ->where('contract_number', 'like', "%{$query}%")
            ->take(3)->get()
            ->map(fn ($c) => [
                'type' => 'contract',
                'title' => $c->contract_number ?? ('عقد #' . $c->id),
                'subtitle' => 'عقد',
                'url' => route('facility.contracts.show', $c),
            ]);

        return response()->json(['results' => $results->merge($products)->merge($bookings)->merge($contracts)->values()]);
    }
}
