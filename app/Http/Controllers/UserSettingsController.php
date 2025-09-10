<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Notification;
use App\Models\ActivityLog;
use Carbon\Carbon;

class UserSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user settings page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's notification settings
        $notificationSettings = $user->notification_settings ?? [];
        
        // Get user's privacy settings
        $privacySettings = $user->privacy_settings ?? [];
        
        // Get user's preferences
        $preferences = $user->preferences ?? [];
        
        // Get user's security settings
        $securitySettings = $user->security_settings ?? [];
        
        // Get user's activity log
        $activityLogs = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get user's login history
        $loginHistory = DB::table('login_history')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('user.settings.index', compact(
            'user',
            'notificationSettings',
            'privacySettings',
            'preferences',
            'securitySettings',
            'activityLogs',
            'loginHistory'
        ));
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'marketing_emails' => 'boolean',
            'property_updates' => 'boolean',
            'booking_updates' => 'boolean',
            'payment_updates' => 'boolean',
            'system_updates' => 'boolean',
            'notification_frequency' => 'in:immediate,daily,weekly,monthly',
            'quiet_hours_start' => 'nullable|date_format:H:i',
            'quiet_hours_end' => 'nullable|date_format:H:i',
        ]);
        
        $user->notification_settings = array_merge(
            $user->notification_settings ?? [],
            $validated
        );
        $user->save();
        
        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'notification_settings_updated',
            'description' => 'تم تحديث إعدادات الإشعارات',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'تم تحديث إعدادات الإشعارات بنجاح');
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'profile_visibility' => 'in:public,private,friends_only',
            'show_email' => 'boolean',
            'show_phone' => 'boolean',
            'show_location' => 'boolean',
            'show_activity' => 'boolean',
            'allow_messages' => 'boolean',
            'allow_friend_requests' => 'boolean',
            'search_visibility' => 'boolean',
            'data_sharing' => 'boolean',
            'analytics_tracking' => 'boolean',
        ]);
        
        $user->privacy_settings = array_merge(
            $user->privacy_settings ?? [],
            $validated
        );
        $user->save();
        
        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'privacy_settings_updated',
            'description' => 'تم تحديث إعدادات الخصوصية',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'تم تحديث إعدادات الخصوصية بنجاح');
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'language' => 'in:ar,en',
            'timezone' => 'string|max:255',
            'date_format' => 'in:Y-m-d,d-m-Y,m/d/Y',
            'time_format' => 'in:12,24',
            'currency' => 'string|max:3',
            'theme' => 'in:light,dark,auto',
            'dashboard_layout' => 'in:grid,list',
            'items_per_page' => 'integer|min:10|max:100',
            'default_view' => 'in:list,grid,map',
            'sort_preference' => 'in:newest,oldest,price_asc,price_desc',
        ]);
        
        $user->preferences = array_merge(
            $user->preferences ?? [],
            $validated
        );
        $user->save();
        
        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'preferences_updated',
            'description' => 'تم تحديث التفضيلات',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'تم تحديث التفضيلات بنجاح');
    }

    /**
     * Update security settings
     */
    public function updateSecurity(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'two_factor_enabled' => 'boolean',
            'login_notifications' => 'boolean',
            'password_change_notifications' => 'boolean',
            'suspicious_activity_alerts' => 'boolean',
            'session_timeout' => 'integer|min:15|max:480',
            'max_login_attempts' => 'integer|min:3|max:10',
            'lockout_duration' => 'integer|min:5|max:60',
        ]);
        
        $user->security_settings = array_merge(
            $user->security_settings ?? [],
            $validated
        );
        $user->save();
        
        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'security_settings_updated',
            'description' => 'تم تحديث إعدادات الأمان',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'تم تحديث إعدادات الأمان بنجاح');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ]);
        
        $user->password = Hash::make($validated['new_password']);
        $user->password_changed_at = now();
        $user->save();
        
        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'password_changed',
            'description' => 'تم تغيير كلمة المرور',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    /**
     * Update profile picture
     */
    public function updateProfilePicture(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Delete old profile picture
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        
        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile-pictures', 'public');
        $user->profile_picture = $path;
        $user->save();
        
        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'profile_picture_updated',
            'description' => 'تم تحديث صورة الملف الشخصي',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'تم تحديث صورة الملف الشخصي بنجاح');
    }

    /**
     * Delete profile picture
     */
    public function deleteProfilePicture()
    {
        $user = Auth::user();
        
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
            $user->profile_picture = null;
            $user->save();
            
            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'profile_picture_deleted',
                'description' => 'تم حذف صورة الملف الشخصي',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
        
        return redirect()->back()->with('success', 'تم حذف صورة الملف الشخصي بنجاح');
    }

    /**
     * Export user data
     */
    public function exportData()
    {
        $user = Auth::user();
        
        $data = [
            'user_info' => $user->toArray(),
            'notifications' => $user->notifications()->get()->toArray(),
            'activity_logs' => $user->activityLogs()->get()->toArray(),
            'exported_at' => now()->toISOString(),
        ];
        
        $filename = 'user_data_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'password' => 'required|current_password',
            'confirmation' => 'required|in:DELETE',
        ]);
        
        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'account_deleted',
            'description' => 'تم حذف الحساب',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        // Delete user account
        $user->delete();
        
        Auth::logout();
        
        return redirect()->route('login')->with('success', 'تم حذف الحساب بنجاح');
    }

    /**
     * Get user activity logs
     */
    public function getActivityLogs(Request $request)
    {
        $user = Auth::user();
        
        $query = ActivityLog::where('user_id', $user->id);
        
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json($logs);
    }

    /**
     * Clear activity logs
     */
    public function clearActivityLogs()
    {
        $user = Auth::user();
        
        ActivityLog::where('user_id', $user->id)->delete();
        
        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'activity_logs_cleared',
            'description' => 'تم مسح سجل النشاط',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'تم مسح سجل النشاط بنجاح');
    }
}
