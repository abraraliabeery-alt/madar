<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Contract;
use App\Models\Appointment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Facility;

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
            'pending_bookings' => $user->bookings()->where('status', 'reserved')->count(), // pending status
            'active_contracts' => $user->contracts()->where('status', 'active')->count(), // active status
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
        $favoriteProducts = $user->favoriteProducts()->with(['facility', 'category', 'status'])->paginate(12);
        $favoriteFacilities = $user->favoriteFacilities()->with(['facilityCategory', 'owner'])->paginate(12);

        return view('client.favorites', compact('favoriteProducts', 'favoriteFacilities'));
    }

    /**
     * عرض المنتجات المفضلة فقط
     */
    public function favoriteProducts()
    {
        $user = Auth::user();
        $favorites = $user->favoriteProducts()->with(['facility', 'category', 'status'])->paginate(12);

        return view('client.favorites', compact('favorites', 'favoriteProducts', 'favoriteFacilities'));
    }

    /**
     * عرض المنشآت المفضلة فقط
     */
    public function favoriteFacilities()
    {
        $user = Auth::user();
        $favorites = $user->favoriteFacilities()->with(['facilityCategory', 'owner'])->paginate(12);

        return view('client.favorites', compact('favorites', 'favoriteProducts', 'favoriteFacilities'));
    }

    /**
     * إضافة منتج للمفضلة
     */
    public function addToFavorites(Product $product)
    {
        $user = Auth::user();

        if (!$user->favoriteProducts()->where('products.id', $product->id)->exists()) {
            $user->favoriteProducts()->attach($product->id);
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
        $user->favoriteProducts()->detach($product->id);

        return redirect()->back()->with('success', 'تم إزالة المنتج من المفضلة بنجاح');
    }

    /**
     * إضافة منشأة للمفضلة
     */
    public function addFacilityToFavorites(Facility $facility)
    {
        $user = Auth::user();

        if (!$user->favoriteFacilities()->where('facilities.id', $facility->id)->exists()) {
            $user->favoriteFacilities()->attach($facility->id);
            return redirect()->back()->with('success', 'تم إضافة المنشأة للمفضلة بنجاح');
        }

        return redirect()->back()->with('info', 'المنشأة موجودة بالفعل في المفضلة');
    }

    /**
     * إزالة منشأة من المفضلة
     */
    public function removeFacilityFromFavorites(Facility $facility)
    {
        $user = Auth::user();
        $user->favoriteFacilities()->detach($facility->id);

        return redirect()->back()->with('success', 'تم إزالة المنشأة من المفضلة بنجاح');
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
        $notifications = $user->notifications()->paginate(20);

        return view('client.notifications', compact('notifications'));
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

        $notification = $user->notifications()->findOrFail($request->notification_id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'تم تحديث حالة الإشعار');
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllNotificationsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة');
    }

    /**
     * إعدادات الإشعارات
     */
    public function notificationSettings()
    {
        $user = Auth::user();
        return view('client.notification-settings', compact('user'));
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
            'notification_frequency' => 'in:immediate,hourly,daily,weekly',
        ]);

        $user->update([
            'notification_email' => $request->has('notification_email'),
            'notification_sms' => $request->has('notification_sms'),
            'notification_push' => $request->has('notification_push'),
            'notification_frequency' => $request->notification_frequency ?? 'immediate',
        ]);

        return redirect()->back()->with('success', 'تم تحديث إعدادات الإشعارات بنجاح');
    }

    /**
     * عرض المواعيد
     */
    public function appointments()
    {
        $user = Auth::user();
        $appointments = $user->appointments()->with(['facility'])->latest()->paginate(15);

        return view('client.appointments', compact('appointments'));
    }

    /**
     * عرض نموذج إنشاء موعد جديد
     */
    public function createAppointment()
    {
        $user = Auth::user();
        $facilities = Facility::where('status', 'active')->get();

        return view('client.appointments.create', compact('facilities'));
    }

    /**
     * حفظ موعد جديد
     */
    public function storeAppointment(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'appointment_time' => 'required|date|after:now',
            'notes' => 'nullable|string|max:500',
        ]);

        $appointment = $user->appointments()->create([
            'facility_id' => $request->facility_id,
            'appointment_time' => $request->appointment_time,
            'status' => 'scheduled',
            'notes' => $request->notes,
        ]);

        return redirect()->route('client.appointments.show', $appointment)
            ->with('success', 'تم إنشاء الموعد بنجاح');
    }

    /**
     * عرض تفاصيل موعد
     */
    public function showAppointment(Appointment $appointment)
    {
        $user = Auth::user();
        
        // التأكد من أن الموعد يخص المستخدم الحالي
        if ($appointment->user_id !== $user->id) {
            abort(403, 'غير مصرح لك بعرض هذا الموعد');
        }

        $appointment->load(['facility', 'user']);

        return view('client.appointments.show', compact('appointment'));
    }

    /**
     * إلغاء موعد
     */
    public function cancelAppointment(Appointment $appointment)
    {
        $user = Auth::user();
        
        // التأكد من أن الموعد يخص المستخدم الحالي
        if ($appointment->user_id !== $user->id) {
            abort(403, 'غير مصرح لك بإلغاء هذا الموعد');
        }

        if ($appointment->status === 'cancelled') {
            return redirect()->back()->with('error', 'الموعد ملغي بالفعل');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'تم إلغاء الموعد بنجاح');
    }

    /**
     * إعادة جدولة موعد
     */
    public function rescheduleAppointment(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        
        // التأكد من أن الموعد يخص المستخدم الحالي
        if ($appointment->user_id !== $user->id) {
            abort(403, 'غير مصرح لك بإعادة جدولة هذا الموعد');
        }

        $request->validate([
            'appointment_time' => 'required|date|after:now',
            'notes' => 'nullable|string|max:500',
        ]);

        $appointment->update([
            'appointment_time' => $request->appointment_time,
            'status' => 'rescheduled',
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'تم إعادة جدولة الموعد بنجاح');
    }
}
