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
use App\Models\LoanRequest;
use App\Models\Bank;
use App\Models\FacilityDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

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
                ->with('error', 'يجب إنشاء منشأة أولاً');
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
        ];

        return view('facility.dashboard', compact('facility', 'stats'));
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
                ->with('error', 'يجب إنشاء منشأة أولاً');
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
                ->with('error', 'لديك منشأة بالفعل');
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
            ->with('success', 'تم إنشاء المنشأة بنجاح');
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
            ->with('success', 'تم إنشاء المنشأة بنجاح');
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
            ->with('success', 'تم تحديث بيانات المنشأة بنجاح');
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

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
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

        return redirect()->route('facility.profile')->with('success', 'تم تحديث الملف التعريفي');
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
     * قائمة بسيطة للبنوك (قراءة فقط)
     */
    public function banks()
    {
        $facility = Auth::user()->facilities()->first();
        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $banks = Bank::orderBy('name')->get();

        return view('facility.banks.index', compact('facility', 'banks'));
    }

    /**
     * لوحة طلبات التمويل للعقارات التابعة للمنشأة
     */
    public function loanRequests(Request $request)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        // طلبات التمويل المرتبطة بعقارات هذه المنشأة فقط
        $query = LoanRequest::with(['user', 'product', 'chosenOffer', 'advisor'])
            ->withCount('offers')
            ->whereHas('product', function ($q) use ($facility) {
                $q->where('facility_id', $facility->id);
            });

        // فلترة بسيطة حسب الحالة إن وجدت
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $loanRequests = $query
            ->orderByDesc('created_at')
            ->paginate(20)
            ->appends($request->query());

        // إحصائيات سريعة
        $stats = [
            'total' => (clone $query)->count(),
            'new' => (clone $query)->where('status', 'new')->count(),
            'dispatched' => (clone $query)->where('status', 'dispatched')->count(),
            'competing' => (clone $query)->where('status', 'competing')->count(),
            'offers_received' => (clone $query)->where('status', 'offers_received')->count(),
            'selected' => (clone $query)->where('status', 'selected')->count(),
            'advising' => (clone $query)->where('status', 'advising')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
        ];

        return view('facility.loans.requests', compact('facility', 'loanRequests', 'stats'));
    }

    /**
     * عرض تفاصيل طلب تمويل واحد من لوحة المنشأة
     */
    public function showLoanRequest(LoanRequest $loanRequest)
    {
        $user = Auth::user();
        $facility = $user->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create')
                ->with('error', 'يجب إنشاء منشأة أولاً');
        }

        // التأكد أن الطلب مرتبط بعقار تابع لهذه المنشأة
        if (!$loanRequest->product || $loanRequest->product->facility_id !== $facility->id) {
            abort(403, 'هذا الطلب لا يخص عقارات هذه المنشأة');
        }

        $loanRequest->load(['product', 'user', 'offers.banker', 'chosenOffer', 'advisor']);

        $contract = Contract::where('product_id', $loanRequest->product_id)
            ->where('user_id', $loanRequest->user_id)
            ->latest()
            ->first();

        return view('facility.loans.show', compact('facility', 'loanRequest', 'contract'));
    }

    /**
     * تعليم الإشعارات كمقروءة
     */
    public function markNotificationsRead(Request $request)
    {
        return redirect()->route('facility.notifications')->with('success', 'تم تعليم الإشعارات كمقروءة');
    }
}
