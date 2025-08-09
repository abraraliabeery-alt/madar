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
        $monthlyStats = [
            'users' => User::whereMonth('created_at', now()->month)->count(),
            'facilities' => Facility::whereMonth('created_at', now()->month)->count(),
            'products' => Product::whereMonth('created_at', now()->month)->count(),
            'bookings' => Booking::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.statistics', compact('monthlyStats'));
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
        ]);

        // حفظ الإعدادات في جدول settings
        $keys = [
            'site_name', 'site_url', 'site_description',
            'contact_email', 'contact_phone', 'contact_address', 'working_hours',
            'facebook_url', 'twitter_url', 'instagram_url', 'linkedin_url',
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
}
