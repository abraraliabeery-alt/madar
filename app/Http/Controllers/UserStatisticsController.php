<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Booking;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;

class UserStatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user statistics dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get basic statistics
        $stats = $this->getBasicStats($user);
        
        // Get activity statistics
        $activityStats = $this->getActivityStats($user);
        
        // Get financial statistics
        $financialStats = $this->getFinancialStats($user);
        
        // Get engagement statistics
        $engagementStats = $this->getEngagementStats($user);
        
        // Get recent activity
        $recentActivity = $this->getRecentActivity($user);
        
        // Get monthly activity chart data
        $monthlyActivity = $this->getMonthlyActivityChart($user);
        
        // Get login history
        $loginHistory = $this->getLoginHistory($user);
        
        return view('user.statistics.index', compact(
            'user',
            'stats',
            'activityStats',
            'financialStats',
            'engagementStats',
            'recentActivity',
            'monthlyActivity',
            'loginHistory'
        ));
    }

    /**
     * Get basic user statistics
     */
    private function getBasicStats($user)
    {
        return [
            'account_age' => $user->created_at->diffInDays(now()),
            'last_login' => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'لم يسجل دخول',
            'total_logins' => DB::table('login_history')->where('user_id', $user->id)->count(),
            'profile_completion' => $this->calculateProfileCompletion($user),
            'email_verified' => $user->email_verified_at ? true : false,
            'phone_verified' => $user->phone_verified_at ? true : false,
            'two_factor_enabled' => $user->two_factor_enabled ?? false,
        ];
    }

    /**
     * Get activity statistics
     */
    private function getActivityStats($user)
    {
        $thirtyDaysAgo = now()->subDays(30);
        
        return [
            'total_activities' => ActivityLog::where('user_id', $user->id)->count(),
            'activities_last_30_days' => ActivityLog::where('user_id', $user->id)
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->count(),
            'most_active_day' => $this->getMostActiveDay($user),
            'average_activities_per_day' => $this->getAverageActivitiesPerDay($user),
            'top_actions' => $this->getTopActions($user),
        ];
    }

    /**
     * Get financial statistics
     */
    private function getFinancialStats($user)
    {
        // Get user's contracts
        $contracts = Contract::where('user_id', $user->id)->get();
        
        // Get user's invoices
        $invoices = Invoice::where('user_id', $user->id)->get();
        
        // Get user's payments
        $payments = Payment::where('user_id', $user->id)->get();
        
        return [
            'total_contracts' => $contracts->count(),
            'active_contracts' => $contracts->where('status', 'active')->count(),
            'total_invoices' => $invoices->count(),
            'paid_invoices' => $invoices->where('status', 'paid')->count(),
            'pending_invoices' => $invoices->where('status', 'pending')->count(),
            'total_payments' => $payments->count(),
            'total_amount_paid' => $payments->sum('amount'),
            'average_payment' => $payments->count() > 0 ? $payments->avg('amount') : 0,
        ];
    }

    /**
     * Get engagement statistics
     */
    private function getEngagementStats($user)
    {
        $thirtyDaysAgo = now()->subDays(30);
        
        return [
            'total_notifications' => Notification::where('user_id', $user->id)->count(),
            'unread_notifications' => Notification::where('user_id', $user->id)
                ->where('read_at', null)
                ->count(),
            'notifications_last_30_days' => Notification::where('user_id', $user->id)
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->count(),
            'favorite_products' => $user->favoriteProducts()->count(),
            'favorite_facilities' => $user->favoriteFacilities()->count(),
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'active_bookings' => Booking::where('user_id', $user->id)
                ->where('status', 'active')
                ->count(),
        ];
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity($user)
    {
        return ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get monthly activity chart data
     */
    private function getMonthlyActivityChart($user)
    {
        $months = [];
        $activities = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $months[] = $date->format('M Y');
            $activities[] = ActivityLog::where('user_id', $user->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
        }
        
        return [
            'months' => $months,
            'activities' => $activities,
        ];
    }

    /**
     * Get login history
     */
    private function getLoginHistory($user)
    {
        return DB::table('login_history')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion($user)
    {
        $fields = [
            'name' => $user->name ? 1 : 0,
            'email' => $user->email ? 1 : 0,
            'phone' => $user->phone ? 1 : 0,
            'profile_picture' => $user->profile_picture ? 1 : 0,
            'bio' => $user->bio ? 1 : 0,
            'location' => $user->location ? 1 : 0,
            'date_of_birth' => $user->date_of_birth ? 1 : 0,
            'gender' => $user->gender ? 1 : 0,
        ];
        
        $completed = array_sum($fields);
        $total = count($fields);
        
        return round(($completed / $total) * 100);
    }

    /**
     * Get most active day of the week
     */
    private function getMostActiveDay($user)
    {
        $dayNames = [
            0 => 'الأحد',
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
        ];
        
        $dayCounts = ActivityLog::where('user_id', $user->id)
            ->selectRaw('DAYOFWEEK(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('count', 'desc')
            ->first();
        
        if ($dayCounts) {
            $dayNumber = $dayCounts->day - 1; // Convert to 0-based index
            return $dayNames[$dayNumber] ?? 'غير محدد';
        }
        
        return 'غير محدد';
    }

    /**
     * Get average activities per day
     */
    private function getAverageActivitiesPerDay($user)
    {
        $firstActivity = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->first();
        
        if (!$firstActivity) {
            return 0;
        }
        
        $days = $firstActivity->created_at->diffInDays(now()) + 1;
        $totalActivities = ActivityLog::where('user_id', $user->id)->count();
        
        return round($totalActivities / $days, 2);
    }

    /**
     * Get top actions performed by user
     */
    private function getTopActions($user)
    {
        return ActivityLog::where('user_id', $user->id)
            ->selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'action' => $this->translateAction($item->action),
                    'count' => $item->count,
                ];
            });
    }

    /**
     * Translate action names to Arabic
     */
    private function translateAction($action)
    {
        $translations = [
            'login' => 'تسجيل الدخول',
            'logout' => 'تسجيل الخروج',
            'profile_updated' => 'تحديث الملف الشخصي',
            'password_changed' => 'تغيير كلمة المرور',
            'settings_updated' => 'تحديث الإعدادات',
            'booking_created' => 'إنشاء حجز',
            'contract_signed' => 'توقيع عقد',
            'payment_made' => 'إجراء دفعة',
            'notification_read' => 'قراءة إشعار',
            'favorite_added' => 'إضافة للمفضلة',
            'search_performed' => 'إجراء بحث',
            'view_property' => 'عرض مشروع',
        ];
        
        return $translations[$action] ?? $action;
    }

    /**
     * Export user statistics
     */
    public function export()
    {
        $user = Auth::user();
        
        $data = [
            'user_info' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'last_login' => $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : null,
            ],
            'statistics' => [
                'basic' => $this->getBasicStats($user),
                'activity' => $this->getActivityStats($user),
                'financial' => $this->getFinancialStats($user),
                'engagement' => $this->getEngagementStats($user),
            ],
            'exported_at' => now()->format('Y-m-d H:i:s'),
        ];
        
        $filename = 'user_statistics_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * Get statistics for specific period
     */
    public function getPeriodStats(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        
        $stats = [
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'days' => $startDate->diffInDays($endDate) + 1,
            ],
            'activities' => ActivityLog::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'notifications' => Notification::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'bookings' => Booking::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'contracts' => Contract::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'invoices' => Invoice::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'payments' => Payment::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
        ];
        
        return response()->json($stats);
    }
}
