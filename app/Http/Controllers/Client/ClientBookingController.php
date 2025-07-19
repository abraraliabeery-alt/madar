<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class ClientBookingController extends Controller
{
    /**
     * عرض قائمة حجوزات العميل
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $user->bookings()->with(['product', 'facility', 'status']);

        // فلترة حسب الحالة
        if ($request->has('status_id') && $request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('booking_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('product', function ($productQuery) use ($request) {
                      $productQuery->where('name', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('facility', function ($facilityQuery) use ($request) {
                      $facilityQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $bookings = $query->latest()->paginate(15);
        $statuses = Status::all();

        return view('client.bookings.index', compact('bookings', 'statuses'));
    }

    /**
     * عرض صفحة إنشاء حجز جديد
     */
    public function create()
    {
        $products = Product::where('is_active', true)
            ->where('is_verified', true)
            ->with(['facility', 'category'])
            ->get();
        $statuses = Status::all();

        return view('client.bookings.create', compact('products', 'statuses'));
    }

    /**
     * حفظ حجز جديد
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // التحقق من أن المنتج متاح
        $product = Product::where('is_active', true)
            ->where('is_verified', true)
            ->find($request->product_id);

        if (!$product) {
            return redirect()->back()
                ->with('error', 'المنتج غير متاح للحجز')
                ->withInput();
        }

        // التحقق من توفر الموعد
        $conflictingBooking = Booking::where('product_id', $request->product_id)
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time)
            ->where('is_confirmed', true)
            ->first();

        if ($conflictingBooking) {
            return redirect()->back()
                ->with('error', 'هذا الموعد محجوز مسبقاً')
                ->withInput();
        }

        // حساب السعر الإجمالي
        $totalAmount = $product->price * $request->duration;

        // إنشاء رقم الحجز
        $bookingNumber = 'BK-' . date('Ymd') . '-' . str_pad(Booking::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        $booking = Booking::create([
            'booking_number' => $bookingNumber,
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'facility_id' => $product->facility_id,
            'status_id' => 1, // pending status
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'duration' => $request->duration,
            'total_amount' => $totalAmount,
            'notes' => $request->notes,
            'is_confirmed' => false,
            'is_paid' => false,
        ]);

        return redirect()->route('client.bookings.index')
            ->with('success', 'تم إنشاء الحجز بنجاح. سنتواصل معك قريباً لتأكيد الحجز.');
    }

    /**
     * عرض تفاصيل الحجز
     */
    public function show(Booking $booking)
    {
        $user = Auth::user();

        if ($booking->user_id !== $user->id) {
            return redirect()->route('client.bookings.index')
                ->with('error', 'غير مصرح لك بعرض هذا الحجز');
        }

        $booking->load(['product', 'facility', 'status']);
        return view('client.bookings.show', compact('booking'));
    }

    /**
     * إلغاء الحجز
     */
    public function cancel(Booking $booking)
    {
        $user = Auth::user();

        if ($booking->user_id !== $user->id) {
            return redirect()->route('client.bookings.index')
                ->with('error', 'غير مصرح لك بإلغاء هذا الحجز');
        }

        if ($booking->is_confirmed) {
            return redirect()->back()
                ->with('error', 'لا يمكن إلغاء الحجز بعد تأكيده');
        }

        $booking->update(['status_id' => 4]); // cancelled status

        return redirect()->route('client.bookings.index')
            ->with('success', 'تم إلغاء الحجز بنجاح');
    }

    /**
     * إعادة جدولة الحجز
     */
    public function reschedule(Request $request, Booking $booking)
    {
        $user = Auth::user();

        if ($booking->user_id !== $user->id) {
            return redirect()->route('client.bookings.index')
                ->with('error', 'غير مصرح لك بتعديل هذا الحجز');
        }

        $request->validate([
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required|date_format:H:i',
        ]);

        // التحقق من توفر الموعد الجديد
        $conflictingBooking = Booking::where('product_id', $booking->product_id)
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time)
            ->where('id', '!=', $booking->id)
            ->where('is_confirmed', true)
            ->first();

        if ($conflictingBooking) {
            return redirect()->back()
                ->with('error', 'هذا الموعد محجوز مسبقاً')
                ->withInput();
        }

        $booking->update([
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'is_confirmed' => false, // إعادة الحجز إلى حالة الانتظار
        ]);

        return redirect()->route('client.bookings.index')
            ->with('success', 'تم إعادة جدولة الحجز بنجاح');
    }

    /**
     * تقييم الحجز
     */
    public function review(Request $request, Booking $booking)
    {
        $user = Auth::user();

        if ($booking->user_id !== $user->id) {
            return redirect()->route('client.bookings.index')
                ->with('error', 'غير مصرح لك بتقييم هذا الحجز');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
        ]);

        // هنا يمكن إضافة التقييم إلى جدول منفصل للتقارير
        // سيتم تنفيذ هذا لاحقاً

        return redirect()->back()->with('success', 'تم إرسال تقييمك بنجاح');
    }

    /**
     * إحصائيات الحجوزات
     */
    public function statistics()
    {
        $user = Auth::user();

        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'confirmed_bookings' => $user->bookings()->where('is_confirmed', true)->count(),
            'pending_bookings' => $user->bookings()->where('status_id', 1)->count(),
            'cancelled_bookings' => $user->bookings()->where('status_id', 4)->count(),
            'total_spent' => $user->bookings()->where('is_paid', true)->sum('total_amount'),
            'recent_bookings' => $user->bookings()->with(['product', 'facility'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('client.bookings.statistics', compact('stats'));
    }
}
