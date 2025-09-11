<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Booking;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Comment;
use Carbon\Carbon;
use Excel;
use App\Exports\UsersExport;

class AdminUserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display user statistics dashboard
     */
    public function statistics()
    {
        // Get basic statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::whereNotNull('last_login_at')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'users_with_phone' => User::whereNotNull('phone')->count(),
            'users_with_profile_picture' => User::whereNotNull('profile_picture')->count(),
        ];

        // Get user activity statistics
        $activityStats = [
            'total_activities' => ActivityLog::count(),
            'activities_today' => ActivityLog::whereDate('created_at', today())->count(),
            'activities_this_month' => ActivityLog::whereMonth('created_at', now()->month)->count(),
            'most_active_users' => $this->getMostActiveUsers(),
            'top_actions' => $this->getTopActions(),
        ];

        // Get user engagement statistics
        $engagementStats = [
            'total_bookings' => Booking::count(),
            'total_contracts' => Contract::count(),
            'total_invoices' => Invoice::count(),
            'total_payments' => Payment::count(),
            'total_comments' => Comment::count(),
            'average_bookings_per_user' => User::count() > 0 ? round(Booking::count() / User::count(), 2) : 0,
        ];

        // Get monthly user registration chart data
        $monthlyRegistrations = $this->getMonthlyRegistrations();

        // Get user role distribution
        $roleDistribution = $this->getRoleDistribution();

        // Get recent activities
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.users.statistics', compact(
            'stats',
            'activityStats',
            'engagementStats',
            'monthlyRegistrations',
            'roleDistribution',
            'recentActivities'
        ));
    }

    /**
     * Display user export options
     */
    public function export()
    {
        $totalUsers = User::count();
        $exportFormats = [
            'excel' => 'Excel (.xlsx)',
            'csv' => 'CSV (.csv)',
            'json' => 'JSON (.json)',
            'pdf' => 'PDF (.pdf)',
        ];

        return view('admin.users.export', compact('totalUsers', 'exportFormats'));
    }

    /**
     * Export users data
     */
    public function exportData(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:excel,csv,json,pdf',
            'include_activities' => 'boolean',
            'include_bookings' => 'boolean',
            'include_contracts' => 'boolean',
            'include_invoices' => 'boolean',
            'include_payments' => 'boolean',
            'include_comments' => 'boolean',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after:date_from',
            'role_filter' => 'nullable|exists:roles,id',
            'status_filter' => 'nullable|in:active,inactive,verified,unverified',
        ]);

        $filename = 'users_export_' . now()->format('Y-m-d_H-i-s') . '.' . $validated['format'];

        switch ($validated['format']) {
            case 'excel':
                return Excel::download(new UsersExport($validated), $filename);
            case 'csv':
                return $this->exportCsv($validated, $filename);
            case 'json':
                return $this->exportJson($validated, $filename);
            case 'pdf':
                return $this->exportPdf($validated, $filename);
        }
    }

    /**
     * Display activity logs
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user');

        // Apply filters
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get filter options
        $users = User::select('id', 'name')->get();
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('admin.users.activity-logs', compact('activityLogs', 'users', 'actions'));
    }

    /**
     * Display user details with full information
     */
    public function userDetails(User $user)
    {
        $user->load(['roles', 'facilities', 'bookings', 'contracts', 'invoices', 'payments', 'comments']);

        // Get user statistics
        $userStats = [
            'total_bookings' => $user->bookings()->count(),
            'total_contracts' => $user->contracts()->count(),
            'total_invoices' => $user->invoices()->count(),
            'total_payments' => $user->payments()->count(),
            'total_comments' => $user->comments()->count(),
            'total_activities' => $user->activityLogs()->count(),
            'last_login' => $user->last_login_at,
            'account_age' => $user->created_at->diffInDays(now()),
        ];

        // Get recent activities
        $recentActivities = $user->activityLogs()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Get login history
        $loginHistory = DB::table('login_history')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.users.details', compact('user', 'userStats', 'recentActivities', 'loginHistory'));
    }

    /**
     * Bulk user actions
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete,assign_role,remove_role,export',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role_id' => 'required_if:action,assign_role,remove_role|exists:roles,id',
        ]);

        $userIds = $validated['user_ids'];
        $action = $validated['action'];

        switch ($action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['is_active' => true]);
                $message = 'تم تفعيل المستخدمين المحددين';
                break;

            case 'deactivate':
                User::whereIn('id', $userIds)->update(['is_active' => false]);
                $message = 'تم إلغاء تفعيل المستخدمين المحددين';
                break;

            case 'delete':
                User::whereIn('id', $userIds)->delete();
                $message = 'تم حذف المستخدمين المحددين';
                break;

            case 'assign_role':
                $role = Role::find($validated['role_id']);
                foreach ($userIds as $userId) {
                    $user = User::find($userId);
                    if ($user && !$user->hasRole($role->name)) {
                        $user->roles()->attach($role->id);
                    }
                }
                $message = 'تم تعيين الدور للمستخدمين المحددين';
                break;

            case 'remove_role':
                $role = Role::find($validated['role_id']);
                foreach ($userIds as $userId) {
                    $user = User::find($userId);
                    if ($user) {
                        $user->roles()->detach($role->id);
                    }
                }
                $message = 'تم إزالة الدور من المستخدمين المحددين';
                break;

            case 'export':
                // Handle export for selected users
                $exportData = User::whereIn('id', $userIds)->get();
                // Implementation for export
                $message = 'تم تصدير بيانات المستخدمين المحددين';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Get most active users
     */
    private function getMostActiveUsers()
    {
        return ActivityLog::select('user_id', DB::raw('COUNT(*) as activity_count'))
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderBy('activity_count', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get top actions
     */
    private function getTopActions()
    {
        return ActivityLog::select('action', DB::raw('COUNT(*) as count'))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get monthly registrations chart data
     */
    private function getMonthlyRegistrations()
    {
        $months = [];
        $registrations = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $months[] = $date->format('M Y');
            $registrations[] = User::whereBetween('created_at', [$monthStart, $monthEnd])->count();
        }

        return [
            'months' => $months,
            'registrations' => $registrations,
        ];
    }

    /**
     * Get role distribution
     */
    private function getRoleDistribution()
    {
        return Role::withCount('users')->get()->map(function ($role) {
            return [
                'name' => $role->name,
                'count' => $role->users_count,
            ];
        });
    }

    /**
     * Export CSV
     */
    private function exportCsv($options, $filename)
    {
        $users = $this->getFilteredUsers($options);
        
        $csv = "ID,Name,Email,Phone,Role,Created At,Last Login\n";
        
        foreach ($users as $user) {
            $csv .= $user->id . ",";
            $csv .= '"' . $user->name . '",';
            $csv .= '"' . $user->email . '",';
            $csv .= '"' . ($user->phone ?? '') . '",';
            $csv .= '"' . ($user->primary_role ?? '') . '",';
            $csv .= $user->created_at->format('Y-m-d H:i:s') . ",";
            $csv .= ($user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : '') . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export JSON
     */
    private function exportJson($options, $filename)
    {
        $users = $this->getFilteredUsers($options);
        
        $data = [
            'export_info' => [
                'exported_at' => now()->toISOString(),
                'total_users' => $users->count(),
                'options' => $options,
            ],
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'profile_picture' => $user->profile_picture,
                    'bio' => $user->bio,
                    'location' => $user->location,
                    'date_of_birth' => $user->date_of_birth,
                    'gender' => $user->gender,
                    'created_at' => $user->created_at->toISOString(),
                    'updated_at' => $user->updated_at->toISOString(),
                    'last_login_at' => $user->last_login_at ? $user->last_login_at->toISOString() : null,
                    'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->toISOString() : null,
                    'phone_verified_at' => $user->phone_verified_at ? $user->phone_verified_at->toISOString() : null,
                    'two_factor_enabled' => $user->two_factor_enabled ?? false,
                ];
            }),
        ];

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export PDF
     */
    private function exportPdf($options, $filename)
    {
        $users = $this->getFilteredUsers($options);
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.users.export-pdf', compact('users', 'options'));
        
        return $pdf->download($filename);
    }

    /**
     * Get filtered users based on options
     */
    private function getFilteredUsers($options)
    {
        $query = User::query();

        if (isset($options['date_from'])) {
            $query->where('created_at', '>=', $options['date_from']);
        }

        if (isset($options['date_to'])) {
            $query->where('created_at', '<=', $options['date_to']);
        }

        if (isset($options['role_filter'])) {
            $query->where('role_id', $options['role_filter']);
        }

        if (isset($options['status_filter'])) {
            switch ($options['status_filter']) {
                case 'active':
                    $query->whereNotNull('last_login_at');
                    break;
                case 'inactive':
                    $query->whereNull('last_login_at');
                    break;
                case 'verified':
                    $query->whereNotNull('email_verified_at');
                    break;
                case 'unverified':
                    $query->whereNull('email_verified_at');
                    break;
            }
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
