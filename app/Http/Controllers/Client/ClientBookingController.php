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

        $query = $user->bookings()->with(['product', 'facility']);

        // فلترة حسب الحالة
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
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
                $q->where('id', 'like', '%' . $request->search . '%')
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
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
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

        // حساب السعر الإجمالي
        $totalAmount = $request->total_amount ?? $product->price;

        $booking = Booking::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'status' => 'reserved', // pending status
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

        $booking->load(['product', 'facility']);
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

        $booking->update(['status' => 'cancelled']); // cancelled status

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
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
        ]);

        // التحقق من أن الحجز يمكن تعديله
        if ($booking->is_confirmed) {
            return redirect()->back()
                ->with('error', 'لا يمكن تعديل الحجز بعد تأكيده')
                ->withInput();
        }

        $booking->update([
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
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
            'pending_bookings' => $user->bookings()->where('status', 'reserved')->count(),
            'cancelled_bookings' => $user->bookings()->where('status', 'cancelled')->count(),
            'total_spent' => $user->bookings()->where('is_paid', true)->sum('total_amount'),
            'recent_bookings' => $user->bookings()->with(['product', 'facility'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('client.bookings.statistics', compact('stats'));
    }
}
