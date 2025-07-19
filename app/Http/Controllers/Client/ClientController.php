<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Contract;
use App\Models\Appointment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * عرض لوحة تحكم العميل
     */
    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'total_contracts' => $user->contracts()->count(),
            'total_appointments' => $user->appointments()->count(),
            'favorite_products' => $user->products()->count(),
            'recent_bookings' => $user->bookings()->with(['product', 'facility'])->latest()->take(5)->get(),
            'recent_appointments' => $user->appointments()->with(['facility'])->latest()->take(5)->get(),
            'pending_bookings' => $user->bookings()->where('status_id', 1)->count(), // pending status
            'active_contracts' => $user->contracts()->where('status_id', 2)->count(), // active status
        ];

        return view('client.dashboard', compact('stats'));
    }

    /**
     * عرض الملف الشخصي
     */
    public function profile()
    {
        $user = Auth::user();
        $user->load(['roles', 'facilities', 'bank']);

        return view('client.profile', compact('user'));
    }

    /**
     * تحديث الملف الشخصي
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:users,phone_number,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bank_id' => 'nullable|exists:banks,id',
            'bank_account' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        ]);

        $userData = $request->except(['avatar']);

        // معالجة الصورة الشخصية
        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $avatarPath;
        }

        $user->update($userData);

        return redirect()->back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * عرض المنتجات المفضلة
     */
    public function favorites()
    {
        $user = Auth::user();
        $favorites = $user->products()->with(['facility', 'category', 'status'])->paginate(12);

        return view('client.favorites', compact('favorites'));
    }

    /**
     * إضافة منتج للمفضلة
     */
    public function addToFavorites(Product $product)
    {
        $user = Auth::user();

        if (!$user->products()->where('product_id', $product->id)->exists()) {
            $user->products()->attach($product->id);
            return redirect()->back()->with('success', 'تم إضافة المنتج للمفضلة بنجاح');
        }

        return redirect()->back()->with('info', 'المنتج موجود بالفعل في المفضلة');
    }

    /**
     * إزالة منتج من المفضلة
     */
    public function removeFromFavorites(Product $product)
    {
        $user = Auth::user();
        $user->products()->detach($product->id);

        return redirect()->back()->with('success', 'تم إزالة المنتج من المفضلة بنجاح');
    }

    /**
     * عرض سجل النشاط
     */
    public function activity()
    {
        $user = Auth::user();

        $activities = collect();

        // إضافة الحجوزات
        $bookings = $user->bookings()->with(['product', 'facility'])->latest()->get();
        foreach ($bookings as $booking) {
            $activities->push([
                'type' => 'booking',
                'title' => 'حجز جديد',
                'description' => "تم حجز {$booking->product->name}",
                'date' => $booking->created_at,
                'data' => $booking
            ]);
        }

        // إضافة العقود
        $contracts = $user->contracts()->with(['product', 'facility'])->latest()->get();
        foreach ($contracts as $contract) {
            $activities->push([
                'type' => 'contract',
                'title' => 'عقد جديد',
                'description' => "تم توقيع عقد لـ {$contract->product->name}",
                'date' => $contract->created_at,
                'data' => $contract
            ]);
        }

        // إضافة المواعيد
        $appointments = $user->appointments()->with(['facility'])->latest()->get();
        foreach ($appointments as $appointment) {
            $activities->push([
                'type' => 'appointment',
                'title' => 'موعد جديد',
                'description' => "تم تحديد موعد مع {$appointment->facility->name}",
                'date' => $appointment->created_at,
                'data' => $appointment
            ]);
        }

        // ترتيب النشاطات حسب التاريخ
        $activities = $activities->sortByDesc('date')->paginate(15);

        return view('client.activity', compact('activities'));
    }

    /**
     * عرض الإعدادات
     */
    public function settings()
    {
        $user = Auth::user();

        return view('client.settings', compact('user'));
    }

    /**
     * تحديث الإعدادات
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
            'notification_push' => 'boolean',
            'language' => 'in:ar,en',
            'timezone' => 'string',
        ]);

        $user->update($request->all());

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }

    /**
     * عرض الإشعارات
     */
    public function notifications()
    {
        $user = Auth::user();
        // يمكن استخدام جدول notifications منفصل أو Laravel Notifications
        $notifications = collect(); // سيتم استبدالها بجدول الإشعارات الفعلي

        return view('client.notifications', compact('notifications'));
    }

    /**
     * تحديث حالة الإشعار
     */
    public function markNotificationAsRead($id)
    {
        // تحديث حالة الإشعار كمقروء
        return redirect()->back()->with('success', 'تم تحديث حالة الإشعار');
    }
}
