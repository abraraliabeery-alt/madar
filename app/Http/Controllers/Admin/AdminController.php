<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Facility;
use App\Models\Product;
use App\Models\Booking;
use App\Models\Contract;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Attribute;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * عرض لوحة تحكم الأدمن
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_facilities' => Facility::count(),
            'total_products' => Product::count(),
            'total_bookings' => Booking::count(),
            'total_contracts' => Contract::count(),
            'total_revenue_month' => Booking::whereYear('created_at', now()->year)
                                         ->whereMonth('created_at', now()->month)
                                         ->sum('total_amount'),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_facilities' => Facility::latest()->take(5)->get(),
            'recent_bookings' => Booking::with(['user', 'product'])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * عرض إحصائيات النظام
     */
    public function statistics()
    {
        // إحصائيات الشهر الحالي
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $monthlyStats = [
            'users' => User::whereYear('created_at', $currentYear)
                           ->whereMonth('created_at', $currentMonth)
                           ->count(),
            'facilities' => Facility::whereYear('created_at', $currentYear)
                                   ->whereMonth('created_at', $currentMonth)
                                   ->count(),
            'products' => Product::whereYear('created_at', $currentYear)
                                 ->whereMonth('created_at', $currentMonth)
                                 ->count(),
            'bookings' => Booking::whereYear('created_at', $currentYear)
                                 ->whereMonth('created_at', $currentMonth)
                                 ->count(),
        ];

        // إحصائيات الشهر السابق للمقارنة
        $lastMonth = now()->subMonth();
        $lastMonthStats = [
            'users' => User::whereYear('created_at', $lastMonth->year)
                           ->whereMonth('created_at', $lastMonth->month)
                           ->count(),
            'facilities' => Facility::whereYear('created_at', $lastMonth->year)
                                   ->whereMonth('created_at', $lastMonth->month)
                                   ->count(),
            'products' => Product::whereYear('created_at', $lastMonth->year)
                                 ->whereMonth('created_at', $lastMonth->month)
                                 ->count(),
            'bookings' => Booking::whereYear('created_at', $lastMonth->year)
                                 ->whereMonth('created_at', $lastMonth->month)
                                 ->count(),
        ];

        // حساب النسبة المئوية للتغيير
        $growthRates = [];
        foreach ($monthlyStats as $key => $current) {
            $last = $lastMonthStats[$key];
            if ($last > 0) {
                $growthRates[$key] = round((($current - $last) / $last) * 100, 1);
            } else {
                $growthRates[$key] = $current > 0 ? 100 : 0;
            }
        }

        // إحصائيات سنوية (آخر 12 شهر)
        $yearlyStats = $this->getYearlyStats();

        // إحصائيات الحجوزات والإيرادات
        $bookingStats = $this->getBookingStats();

        // أداء المنشآت
        $facilityPerformance = $this->getFacilityPerformance();

        // إحصائيات المستخدمين
        $userStats = $this->getUserStats();

        // إحصائيات المنتجات
        $productStats = $this->getProductStats();

        return view('admin.statistics', compact(
            'monthlyStats',
            'lastMonthStats',
            'growthRates',
            'yearlyStats',
            'bookingStats',
            'facilityPerformance',
            'userStats',
            'productStats'
        ));
    }

    /**
     * عرض إعدادات النظام
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * حفظ إعدادات النظام
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'working_hours' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string|regex:/^\+[1-9]\d{1,14}$/',
        ]);

        // حفظ الإعدادات في جدول settings
        $keys = [
            'site_name', 'site_url', 'site_description',
            'contact_email', 'contact_phone', 'contact_address', 'working_hours',
            'facebook_url', 'twitter_url', 'instagram_url', 'linkedin_url', 'whatsapp_number',
            'maintenance_mode', 'allow_registration', 'email_verification', 'auto_approve_facilities'
        ];

        foreach ($keys as $key) {
            $value = $request->has($key) ? $request->input($key) : null;
            if (in_array($key, ['maintenance_mode','allow_registration','email_verification','auto_approve_facilities'])) {
                $value = $request->has($key) ? '1' : '0';
            }
            Setting::setValue($key, $value);
        }

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }

    /**
     * عرض سجل النظام
     */
    public function logs()
    {
        // يمكن استخدام Laravel Log أو جدول منفصل للسجلات
        return view('admin.logs');
    }

    /**
     * عرض التقارير
     */
    public function reports()
    {
        $reports = [
            'user_growth' => $this->getUserGrowthReport(),
            'booking_revenue' => $this->getBookingRevenueReport(),
            'facility_performance' => $this->getFacilityPerformanceReport(),
        ];

        return view('admin.reports', compact('reports'));
    }

    /**
     * تقرير نمو المستخدمين
     */
    private function getUserGrowthReport()
    {
        return User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * تقرير إيرادات الحجوزات
     */
    private function getBookingRevenueReport()
    {
        return Booking::selectRaw('DATE(created_at) as date, SUM(COALESCE(total_amount, 0)) as revenue')
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * تقرير أداء المنشآت
     */
    private function getFacilityPerformanceReport()
    {
        return Facility::withCount(['products', 'bookings'])
            ->orderByDesc('bookings_count')
            ->take(10)
            ->get();
    }

    /**
     * الحصول على الإحصائيات السنوية
     */
    private function getYearlyStats()
    {
        $stats = [];
        $currentYear = now()->year;
        
        for ($month = 1; $month <= 12; $month++) {
            $stats['users'][$month] = User::whereYear('created_at', $currentYear)
                                          ->whereMonth('created_at', $month)
                                          ->count();
            
            $stats['facilities'][$month] = Facility::whereYear('created_at', $currentYear)
                                                  ->whereMonth('created_at', $month)
                                                  ->count();
            
            $stats['products'][$month] = Product::whereYear('created_at', $currentYear)
                                               ->whereMonth('created_at', $month)
                                               ->count();
            
            $stats['bookings'][$month] = Booking::whereYear('created_at', $currentYear)
                                               ->whereMonth('created_at', $month)
                                               ->count();
        }
        
        return $stats;
    }

    /**
     * الحصول على إحصائيات الحجوزات
     */
    private function getBookingStats()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        return [
            'total_revenue' => Booking::whereYear('created_at', $currentYear)
                                     ->whereMonth('created_at', $currentMonth)
                                     ->sum('total_amount'),
            'total_bookings' => Booking::whereYear('created_at', $currentYear)
                                      ->whereMonth('created_at', $currentMonth)
                                      ->count(),
            'pending_bookings' => Booking::whereYear('created_at', $currentYear)
                                        ->whereMonth('created_at', $currentMonth)
                                        ->where('status', 'pending')
                                        ->count(),
            'confirmed_bookings' => Booking::whereYear('created_at', $currentYear)
                                          ->whereMonth('created_at', $currentMonth)
                                          ->where('status', 'confirmed')
                                          ->count(),
            'cancelled_bookings' => Booking::whereYear('created_at', $currentYear)
                                          ->whereMonth('created_at', $currentMonth)
                                          ->where('status', 'cancelled')
                                          ->count(),
            'average_amount' => Booking::whereYear('created_at', $currentYear)
                                      ->whereMonth('created_at', $currentMonth)
                                      ->avg('total_amount'),
        ];
    }

    /**
     * الحصول على أداء المنشآت
     */
    private function getFacilityPerformance()
    {
        return Facility::withCount(['products', 'bookings'])
                      ->withSum('bookings', 'total_amount')
                      ->orderByDesc('bookings_count')
                      ->take(10)
                      ->get()
                      ->map(function ($facility) {
                          return [
                              'id' => $facility->id,
                              'name' => $facility->name,
                              'products_count' => $facility->products_count,
                              'bookings_count' => $facility->bookings_count,
                              'total_revenue' => $facility->bookings_sum_total_amount ?? 0,
                              'average_rating' => $facility->average_rating ?? 0,
                          ];
                      });
    }

    /**
     * الحصول على إحصائيات المستخدمين
     */
    private function getUserStats()
    {
        return [
            'total_users' => User::count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'users_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'users_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];
    }

    /**
     * الحصول على إحصائيات المنتجات
     */
    private function getProductStats()
    {
        return [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'inactive_products' => Product::where('is_active', false)->count(),
            'featured_products' => Product::where('is_featured', true)->count(),
            'products_this_week' => Product::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'products_this_month' => Product::whereMonth('created_at', now()->month)->count(),
            'average_price' => Product::avg('price') ?? 0,
            'total_value' => Product::sum('price') ?? 0,
        ];
    }

    /**
     * عرض إشعارات الأدمن
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);
        
        return view('admin.notifications', compact('notifications'));
    }

    /**
     * تحديث حالة الإشعار
     */
    public function markNotificationsRead(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'notification_id' => 'required|string'
        ]);

        $user->notifications()->where('id', $request->notification_id)->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'تم تحديث حالة الإشعار');
    }

    /**
     * تحديث حالة جميع الإشعارات
     */
    public function markAllNotificationsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'تم تحديث جميع الإشعارات');
    }

    /**
     * إعدادات الإشعارات
     */
    public function notificationSettings()
    {
        $user = Auth::user();
        
        return view('admin.notification-settings', compact('user'));
    }

    /**
     * تحديث إعدادات الإشعارات
     */
    public function updateNotificationSettings(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
            'notification_push' => 'boolean',
            'notification_frequency' => 'in:immediate,daily,weekly'
        ]);

        $user->update($request->all());

        return redirect()->back()->with('success', 'تم تحديث إعدادات الإشعارات بنجاح');
    }

    /**
     * الحصول على عدد الإشعارات غير المقروءة
     */
    public function getUnreadNotificationsCount()
    {
        $user = Auth::user();
        $count = $user->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * الحصول على آخر الإشعارات
     */
    public function getLatestNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->take(5)->get();
        
        return response()->json(['notifications' => $notifications]);
    }

    /**
     * البحث العام في النظام
     */
    public function globalSearch(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = collect();

        // البحث في المستخدمين
        $users = User::where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone_number', 'like', "%{$query}%")
                    ->take(3)
                    ->get()
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'type' => 'user',
                            'title' => $user->name,
                            'subtitle' => $user->email,
                            'url' => '/admin/users/' . $user->id . '/edit'
                        ];
                    });

        $results = $results->merge($users);

        // البحث في المنشآت
        $facilities = Facility::where('name', 'like', "%{$query}%")
                             ->orWhere('description', 'like', "%{$query}%")
                             ->take(3)
                             ->get()
                             ->map(function ($facility) {
                                 return [
                                     'id' => $facility->id,
                                     'type' => 'facility',
                                     'title' => $facility->name,
                                     'subtitle' => 'منشأة',
                                     'url' => '/admin/facilities/' . $facility->id . '/edit'
                                 ];
                             });

        $results = $results->merge($facilities);

        // البحث في المنتجات
        $products = Product::where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%")
                          ->orWhere('address', 'like', "%{$query}%")
                          ->take(3)
                          ->get()
                          ->map(function ($product) {
                              return [
                                  'id' => $product->id,
                                  'type' => 'product',
                                  'title' => $product->title,
                                  'subtitle' => $product->address ?? 'منتج',
                                  'url' => '/admin/products/' . $product->id . '/edit'
                              ];
                          });

        $results = $results->merge($products);

        // البحث في الحجوزات
        $bookings = Booking::where('id', 'like', "%{$query}%")
                          ->orWhere('status', 'like', "%{$query}%")
                          ->with('product') // Eager load the product relationship
                          ->take(2)
                          ->get()
                          ->map(function ($booking) {
                              return [
                                  'id' => $booking->id,
                                  'type' => 'booking',
                                  'title' => 'حجز #' . $booking->id,
                                  'subtitle' => $booking->product->title ?? 'حجز',
                                  'url' => '/admin/bookings/' . $booking->id . '/edit'
                              ];
                          });

        $results = $results->merge($bookings);

                // البحث في التصنيفات
        $categories = Category::whereHas('translations', function($q) use ($query) {
                            $q->where('name', 'like', "%{$query}%");
                        })
                        ->take(2)
                        ->get()
                        ->map(function ($category) {
                            return [
                                'id' => $category->id,
                                'type' => 'category',
                                'title' => $category->translations->first()->name ?? 'تصنيف',
                                'subtitle' => 'تصنيف',
                                'url' => '/admin/categories/' . $category->id . '/edit'
                            ];
                        });

        $results = $results->merge($categories);

        // البحث في المميزات
        $features = Feature::where('name', 'like', "%{$query}%")
                          ->take(2)
                          ->get()
                          ->map(function ($feature) {
                              return [
                                  'id' => $feature->id,
                                  'type' => 'feature',
                                  'title' => $feature->getTranslatedName('ar'),
                                  'subtitle' => 'ميزة',
                                  'url' => '/admin/features/' . $feature->id . '/edit'
                              ];
                          });

        $results = $results->merge($features);

        // البحث في الخصائص
        $attributes = Attribute::whereHas('translations', function($q) use ($query) {
                            $q->where('name', 'like', "%{$query}%");
                        })
                        ->take(2)
                        ->get()
                        ->map(function ($attribute) {
                            return [
                                'id' => $attribute->id,
                                'type' => 'attribute',
                                'title' => $attribute->translations->first()->name ?? 'خاصية',
                                'subtitle' => 'خاصية',
                                'url' => '/admin/attributes/' . $attribute->id . '/edit'
                            ];
                        });

        $results = $results->merge($attributes);

        // ترتيب النتائج حسب الأهمية
        $results = $results->take(8);

        return response()->json(['results' => $results]);
    }

    /**
     * صفحة نتائج البحث
     */
    public function searchResults(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return redirect()->route('admin.dashboard');
        }

        $results = [
            'users' => User::where('name', 'like', "%{$query}%")
                          ->orWhere('email', 'like', "%{$query}%")
                          ->orWhere('phone_number', 'like', "%{$query}%")
                          ->paginate(10),
            'facilities' => Facility::where('name', 'like', "%{$query}%")
                                  ->orWhere('description', 'like', "%{$query}%")
                                  ->paginate(10),
            'products' => Product::where('title', 'like', "%{$query}%")
                                ->orWhere('description', 'like', "%{$query}%")
                                ->orWhere('address', 'like', "%{$query}%")
                                ->paginate(10),
            'bookings' => Booking::where('id', 'like', "%{$query}%")
                                ->orWhere('status', 'like', "%{$query}%")
                                ->with('product') // Eager load the product relationship
                                ->paginate(10),
            'categories' => Category::whereHas('translations', function($q) use ($query) {
                                $q->where('name', 'like', "%{$query}%");
                            })->paginate(10),
            'features' => Feature::where('name', 'like', "%{$query}%")
                                ->paginate(10),
            'attributes' => Attribute::whereHas('translations', function($q) use ($query) {
                                $q->where('name', 'like', "%{$query}%");
                            })->paginate(10),
        ];

        return view('admin.search-results', compact('results', 'query'));
    }
}
