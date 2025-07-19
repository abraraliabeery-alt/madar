<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiNotificationController extends Controller
{
    /**
     * Display a listing of user notifications
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $user->notifications()->where('id', $request->notification_id)->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الإشعار'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث جميع الإشعارات'
        ]);
    }

    /**
     * Get notification settings
     */
    public function settings()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'email_notifications' => true,
                'sms_notifications' => false,
                'push_notifications' => true,
            ]
        ]);
    }

    /**
     * Update notification settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
        ]);

        // Update notification settings

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث إعدادات الإشعارات'
        ]);
    }
}
