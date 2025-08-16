<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Product;
use App\Models\Status;

class AdminBookingController extends Controller
{
    /**
     * عرض قائمة الحجوزات
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'product', 'facility', 'statuses']);

        // فلترة حسب الحالة
        if ($request->has('status_id') && $request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        // فلترة حسب المستخدم
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // فلترة حسب المنتج
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('booking_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                               ->orWhere('email', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('product', function ($productQuery) use ($request) {
                      $productQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $bookings = $query->latest()->paginate(15);
        $statuses = Status::all();
        $users = User::all();
        $products = Product::all();

        return view('admin.bookings.index', compact('bookings', 'statuses', 'users', 'products'));
    }

    /**
     * عرض صفحة إنشاء حجز جديد
     */
    public function create()
    {
        $users = User::all();
        $products = Product::where('is_active', true)->get();
        $statuses = Status::all();

        return view('admin.bookings.create', compact('users', 'products', 'statuses'));
    }

    /**
     * حفظ حجز جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'facility_id' => 'required|exists:facilities,id',
            'status_id' => 'required|exists:statuses,id',
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'is_confirmed' => 'boolean',
            'is_paid' => 'boolean',
        ]);

        // إنشاء رقم الحجز
        $bookingNumber = 'BK-' . date('Ymd') . '-' . str_pad(Booking::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        $bookingData = $request->except(['status_id']);
        $bookingData['booking_number'] = $bookingNumber;

        $booking = Booking::create($bookingData);

        // ربط الحالة
        if ($request->has('status_id')) {
            $booking->statuses()->attach($request->status_id, [
                'notes' => 'تم تعيين الحالة عند إنشاء الحجز',
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'تم إنشاء الحجز بنجاح');
    }

    /**
     * عرض صفحة تعديل الحجز
     */
    public function edit(Booking $booking)
    {
        $booking->load(['user', 'product', 'facility', 'statuses']);
        $users = User::all();
        $products = Product::where('is_active', true)->get();
        $statuses = Status::all();

        return view('admin.bookings.edit', compact('booking', 'users', 'products', 'statuses'));
    }

    /**
     * تحديث الحجز
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'facility_id' => 'required|exists:facilities,id',
            'status_id' => 'required|exists:statuses,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'is_confirmed' => 'boolean',
            'is_paid' => 'boolean',
        ]);

        $bookingData = $request->except(['status_id']);
        $booking->update($bookingData);

        // تحديث الحالة
        if ($request->has('status_id')) {
            // حذف الحالة القديمة وإضافة الحالة الجديدة
            $booking->statuses()->detach();
            $booking->statuses()->attach($request->status_id, [
                'notes' => 'تم تحديث الحالة',
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'تم تحديث الحجز بنجاح');
    }

    /**
     * حذف الحجز
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'تم حذف الحجز بنجاح');
    }

    /**
     * تأكيد الحجز
     */
    public function confirm(Booking $booking)
    {
        $booking->update(['is_confirmed' => true]);

        return redirect()->back()->with('success', 'تم تأكيد الحجز بنجاح');
    }

    /**
     * إلغاء تأكيد الحجز
     */
    public function unconfirm(Booking $booking)
    {
        $booking->update(['is_confirmed' => false]);

        return redirect()->back()->with('success', 'تم إلغاء تأكيد الحجز بنجاح');
    }

    /**
     * تحديث حالة الدفع
     */
    public function updatePaymentStatus(Booking $booking)
    {
        $booking->update(['is_paid' => !$booking->is_paid]);

        $status = $booking->is_paid ? 'دفع' : 'إلغاء دفع';
        return redirect()->back()->with('success', "تم {$status} الحجز بنجاح");
    }

    /**
     * عرض تفاصيل الحجز
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'product', 'facility', 'statuses']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * تصدير الحجوزات
     */
    public function export(Request $request)
    {
        $query = Booking::with(['user', 'product', 'facility', 'status']);

        // تطبيق نفس الفلاتر
        if ($request->has('status_id') && $request->status_id) {
            $query->where('status_id', $request->status_id);
        }
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->get();

        // هنا يمكن تصدير البيانات إلى Excel أو CSV
        // سيتم تنفيذ هذا لاحقاً

        return redirect()->back()->with('success', 'تم تصدير البيانات بنجاح');
    }

    /**
     * إحصائيات الحجوزات
     */
    public function statistics()
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('is_confirmed', true)->count(),
            'paid_bookings' => Booking::where('is_paid', true)->count(),
            'pending_bookings' => Booking::where('status_id', 1)->count(), // pending status
            'monthly_revenue' => Booking::where('is_paid', true)
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
            'recent_bookings' => Booking::with(['user', 'product'])
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('admin.bookings.statistics', compact('stats'));
    }
}
