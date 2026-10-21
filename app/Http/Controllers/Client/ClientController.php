<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Contract;
use App\Models\Appointment;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\Facility;
use App\Models\Comment;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;

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
        $user->load(['roles', 'facilities']);

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

        return redirect()->back()->with('success', __('تم تحديث الملف الشخصي بنجاح'));
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
        $favoriteProducts = Auth::user()->favoriteProducts()->with(['facility', 'category', 'status'])->paginate(12);
        $favoriteFacilities = new LengthAwarePaginator([], 0, 12);

        return view('client.favorites', compact('favoriteProducts', 'favoriteFacilities'));
    }

    /**
     * عرض المنشآت المفضلة فقط
     */
    public function favoriteFacilities()
    {
        $favoriteFacilities = Auth::user()->favoriteFacilities()->with(['facilityCategory', 'owner'])->paginate(12);
        $favoriteProducts = new LengthAwarePaginator([], 0, 12);

        return view('client.favorites', compact('favoriteProducts', 'favoriteFacilities'));
    }

    /**
     * إضافة منتج للمفضلة
     */
    public function addToFavorites(Product $product)
    {
        $user = Auth::user();

        if (!$user->favoriteProducts()->where('products.id', $product->id)->exists()) {
            $user->favoriteProducts()->attach($product->id);
            return redirect()->back()->with('success', __('تم إضافة المنتج للمفضلة بنجاح'));
        }

        return redirect()->back()->with('info', __('المنتج موجود بالفعل في المفضلة'));
    }

    /**
     * إزالة منتج من المفضلة
     */
    public function removeFromFavorites(Product $product)
    {
        $user = Auth::user();
        $user->favoriteProducts()->detach($product->id);

        return redirect()->back()->with('success', __('تم إزالة المنتج من المفضلة بنجاح'));
    }

    /**
     * إضافة منشأة للمفضلة
     */
    public function addFacilityToFavorites(Facility $facility)
    {
        $user = Auth::user();

        if (!$user->favoriteFacilities()->where('facilities.id', $facility->id)->exists()) {
            $user->favoriteFacilities()->attach($facility->id);
            return redirect()->back()->with('success', __('تم إضافة المنشأة للمفضلة بنجاح'));
        }

        return redirect()->back()->with('info', __('المنشأة موجودة بالفعل في المفضلة'));
    }

    /**
     * إزالة منشأة من المفضلة
     */
    public function removeFacilityFromFavorites(Facility $facility)
    {
        $user = Auth::user();
        $user->favoriteFacilities()->detach($facility->id);

        return redirect()->back()->with('success', __('تم إزالة المنشأة من المفضلة بنجاح'));
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
                'description' => 'تم حجز ' . ($booking->product?->getTranslatedTitle() ?? '—'),
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
                'description' => 'تم توقيع عقد لـ ' . ($contract->product?->getTranslatedTitle() ?? '—'),
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
                'description' => 'تم تحديد موعد مع ' . ($appointment->facility?->name ?? '—'),
                'date' => $appointment->created_at,
                'data' => $appointment
            ]);
        }

        // ترتيب النشاطات حسب التاريخ (كمجموعة بسيطة بدون تقسيم صفحات حالياً)
        $activities = $activities->sortByDesc('date')->values();

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

        return redirect()->back()->with('success', __('تم تحديث الإعدادات بنجاح'));
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

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('تم تحديث حالة الإشعار')
            ]);
        }

        return redirect()->back()->with('success', __('تم تحديث حالة الإشعار'));
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllNotificationsRead(Request $request)
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('تم تحديد جميع الإشعارات كمقروءة')
            ]);
        }

        return redirect()->back()->with('success', __('تم تحديد جميع الإشعارات كمقروءة'));
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

        return redirect()->back()->with('success', __('تم تحديث إعدادات الإشعارات بنجاح'));
    }

    /**
     * عرض المواعيد
     */
    public function appointments()
    {
        $user = Auth::user();
        $appointments = $user->appointments()->with(['facility'])->latest()->paginate(15);

        return view('client.appointments.index', compact('appointments'));
    }

    /**
     * عرض نموذج إنشاء موعد جديد
     */
    public function createAppointment()
    {
        $user = Auth::user();
        $facilities = Facility::where('is_active', true)->get();

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
            'subject' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $appointment = $user->appointments()->create([
            'facility_id' => $request->facility_id,
            'appointment_time' => $request->appointment_time,
            'subject' => $request->subject,
            'status' => 'scheduled',
            'notes' => $request->notes,
        ]);

        return redirect()->route('client.appointments.show', $appointment)
            ->with('success', __('تم إنشاء الموعد بنجاح'));
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
            return redirect()->back()->with('error', __('الموعد ملغي بالفعل'));
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', __('تم إلغاء الموعد بنجاح'));
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

        return redirect()->back()->with('success', __('تم إعادة جدولة الموعد بنجاح'));
    }

    /**
     * تغيير كلمة المرور
     */
    public function changePassword()
    {
        return view('client.change-password');
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
     * التعليقات
     */
    public function comments()
    {
        $comments = Auth::user()->comments()->with(['product.translations', 'facility'])->latest()->paginate(15);
        return view('client.comments', compact('comments'));
    }

    public function productComments()
    {
        $comments = Auth::user()->comments()->whereNotNull('product_id')
            ->with(['product.translations'])->latest()->paginate(15);
        return view('client.comments', compact('comments'));
    }

    public function facilityComments()
    {
        $comments = Auth::user()->comments()->whereNotNull('facility_id')
            ->with(['facility'])->latest()->paginate(15);
        return view('client.comments', compact('comments'));
    }

    public function addProductComment(Request $request, Product $product)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return back()->with('success', __('تم إضافة تعليقك بنجاح'));
    }

    public function addFacilityComment(Request $request, Facility $facility)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'facility_id' => $facility->id,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return back()->with('success', __('تم إضافة تعليقك بنجاح'));
    }

    public function updateComment(Request $request, Comment $comment)
    {
        abort_unless($comment->user_id === Auth::id(), 403);

        $request->validate([
            'comment' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $comment->update($request->only(['comment', 'rating']));

        return back()->with('success', __('تم تحديث التعليق بنجاح'));
    }

    public function deleteComment(Comment $comment)
    {
        abort_unless($comment->user_id === Auth::id(), 403);
        $comment->delete();

        return back()->with('success', __('تم حذف التعليق بنجاح'));
    }

    /**
     * المعرض: المنتجات والمنشآت المفضلة
     */
    public function gallery()
    {
        $user = Auth::user();
        $products = $user->favoriteProducts()->with(['translations', 'facility'])->paginate(12);
        $facilities = $user->favoriteFacilities()->paginate(12);

        return view('client.gallery', compact('products', 'facilities'));
    }

    public function productGallery()
    {
        $products = Auth::user()->favoriteProducts()->with(['translations', 'facility'])->paginate(12);
        $facilities = collect();

        return view('client.gallery', compact('products', 'facilities'));
    }

    public function facilityGallery()
    {
        $facilities = Auth::user()->favoriteFacilities()->paginate(12);
        $products = collect();

        return view('client.gallery', compact('products', 'facilities'));
    }

    /**
     * نشاطات مفصلة حسب النوع
     */
    public function bookingActivity()
    {
        $activities = $this->buildActivityFeed()->where('type', 'booking')->values();
        return view('client.activity', compact('activities'));
    }

    public function viewActivity()
    {
        $activities = $this->buildActivityFeed()->where('type', 'view')->values();
        return view('client.activity', compact('activities'));
    }

    public function searchActivity()
    {
        $activities = $this->buildActivityFeed()->where('type', 'search')->values();
        return view('client.activity', compact('activities'));
    }

    private function buildActivityFeed()
    {
        $user = Auth::user();
        $activities = collect();

        foreach ($user->bookings()->with(['product', 'facility'])->latest()->get() as $booking) {
            $activities->push([
                'type' => 'booking',
                'title' => 'حجز جديد',
                'description' => 'تم حجز ' . ($booking->product?->getTranslatedTitle() ?? '—'),
                'date' => $booking->created_at,
                'data' => $booking,
            ]);
        }
        foreach ($user->contracts()->with(['product', 'facility'])->latest()->get() as $contract) {
            $activities->push([
                'type' => 'contract',
                'title' => 'عقد جديد',
                'description' => 'تم توقيع عقد لـ ' . ($contract->product?->getTranslatedTitle() ?? '—'),
                'date' => $contract->created_at,
                'data' => $contract,
            ]);
        }
        foreach ($user->appointments()->with(['facility'])->latest()->get() as $appointment) {
            $activities->push([
                'type' => 'appointment',
                'title' => 'موعد جديد',
                'description' => 'تم تحديد موعد مع ' . ($appointment->facility?->name ?? '—'),
                'date' => $appointment->created_at,
                'data' => $appointment,
            ]);
        }

        return $activities->sortByDesc('date')->values();
    }

    /**
     * التقارير
     */
    public function reports()
    {
        $user = Auth::user();
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'confirmed_bookings' => $user->bookings()->where('is_confirmed', true)->count(),
            'total_contracts' => $user->contracts()->count(),
            'total_spent' => $user->bookings()->where('is_paid', true)->sum('total_amount')
                + \App\Models\Payment::whereHas('contract', fn ($q) => $q->where('user_id', $user->id))
                    ->where('status', 'confirmed')->sum('amount'),
            'total_appointments' => $user->appointments()->count(),
        ];

        return view('client.reports.index', compact('stats'));
    }

    public function bookingReports()
    {
        $bookings = Auth::user()->bookings()->with(['product.translations', 'facility'])->latest()->paginate(15);
        return view('client.reports.bookings', compact('bookings'));
    }

    public function spendingReports()
    {
        $bookings = Auth::user()->bookings()->where('is_paid', true)
            ->with(['product.translations'])->latest()->paginate(15);
        $total = Auth::user()->bookings()->where('is_paid', true)->sum('total_amount');

        return view('client.reports.spending', compact('bookings', 'total'));
    }

    public function activityReports()
    {
        $user = Auth::user();
        $activities = $this->buildActivityFeed();

        $stats = [
            'total_views' => 0,
            'total_favorites' => $user->favoriteProducts()->count() + $user->favoriteFacilities()->count(),
            'total_searches' => 0,
            'total_reviews' => $user->comments()->count(),
            'search_categories' => [],
            'recent_activity' => $activities->take(10)->map(fn ($a) => [
                'icon' => match ($a['type']) {
                    'booking' => 'calendar-check',
                    'contract' => 'file-contract',
                    'appointment' => 'clock',
                    default => 'circle',
                },
                'description' => $a['title'] . ' — ' . $a['description'],
                'time' => $a['date']->diffForHumans(),
            ])->values(),
        ];

        return view('client.statistics', compact('stats'));
    }

    /**
     * إعدادات الخصوصية والأمان
     */
    public function privacySettings()
    {
        return view('client.settings.privacy');
    }

    public function updatePrivacySettings(Request $request)
    {
        $request->validate([
            'profile_visibility' => 'required|in:public,private',
        ]);

        $user = Auth::user();
        $settings = $user->privacy_settings ?? [];
        $settings['profile_visibility'] = $request->profile_visibility;
        $user->update(['privacy_settings' => $settings]);

        return back()->with('success', __('تم حفظ إعدادات الخصوصية'));
    }

    public function securitySettings()
    {
        return view('client.settings.security', ['user' => Auth::user()]);
    }

    public function updateSecuritySettings(Request $request)
    {
        $user = Auth::user();
        $settings = $user->security_settings ?? [];
        foreach (['two_factor_enabled', 'login_alerts'] as $key) {
            if ($request->has($key)) {
                $settings[$key] = $request->boolean($key);
            }
        }
        $user->update(['security_settings' => $settings]);

        return back()->with('success', __('تم حفظ إعدادات الأمان'));
    }

    /**
     * المساعدة والدعم
     */
    public function help()
    {
        return view('client.help.index');
    }

    public function faq()
    {
        return view('client.help.faq');
    }

    public function contact()
    {
        return view('client.help.contact');
    }

    public function sendContactMessage(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'category' => 'contact',
            'message' => $request->message,
            'status' => 'open',
            'priority' => 'normal',
        ]);

        return redirect()->route('client.help.tickets')->with('success', __('تم إرسال رسالتك بنجاح'));
    }

    public function tickets()
    {
        $tickets = Auth::user()->supportTickets()->withCount('replies')->latest()->paginate(15);
        return view('client.help.tickets', compact('tickets'));
    }

    public function createTicket()
    {
        return view('client.help.ticket-create');
    }

    public function storeTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:low,normal,high',
            'message' => 'required|string|max:2000',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'category' => 'support',
            'message' => $request->message,
            'status' => 'open',
            'priority' => $request->priority,
        ]);

        return redirect()->route('client.help.tickets.show', $ticket)->with('success', __('تم إنشاء التذكرة بنجاح'));
    }

    public function showTicket(SupportTicket $ticket)
    {
        abort_unless($ticket->user_id === Auth::id(), 403);
        $ticket->load('replies.user');

        return view('client.help.ticket-show', compact('ticket'));
    }

    public function replyTicket(Request $request, SupportTicket $ticket)
    {
        abort_unless($ticket->user_id === Auth::id(), 403);

        $request->validate(['message' => 'required|string|max:2000']);

        SupportTicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        if ($ticket->status !== 'open') {
            $ticket->update(['status' => 'open']);
        }

        return back()->with('success', __('تم إرسال الرد بنجاح'));
    }
}
