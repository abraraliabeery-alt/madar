<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Lead;
use App\Models\Product;
use App\Models\User;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class FacilityBookingController extends Controller
{
    /**
     * عرض قائمة الحجوزات
     */
    public function index(Request $request)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $query = $facility->bookings()->with(['user', 'product']);

        // فلترة حسب الحالة
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
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
                $q->where('id', 'like', '%' . $request->search . '%')
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
        $products = $facility->products()->where('is_active', true)->get();

        return view('facility.bookings.index', compact('bookings', 'statuses', 'products'));
    }

    /**
     * عرض صفحة إنشاء حجز جديد
     */
    public function create(Request $request)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $products = $facility->products()->where('is_active', true)->get();
        $statuses = collect();
        $users = User::whereHas('roles', fn ($q) => $q->where('name', 'client'))->orderBy('name')->get(['id', 'name']);
        $lead = $request->filled('lead_id') ? $facility->leads()->find($request->lead_id) : null;

        return view('facility.bookings.create', compact('products', 'statuses', 'users', 'lead'));
    }

    /**
     * حفظ حجز جديد
     */
    public function store(Request $request)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'lead_id' => 'nullable|integer',
            'product_id' => 'required|exists:products,id',
            'status' => 'required|in:pending,confirmed,cancelled,reserved',
            'total_amount' => 'required|numeric|min:0',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|date_format:H:i',
            'payment_method' => 'nullable|string',
            'message' => 'nullable|string',
            'is_confirmed' => 'boolean',
            'is_paid' => 'boolean',
        ]);

        // التحقق من أن المنتج ينتمي للمنشأة
        $product = $facility->products()->find($request->product_id);
        if (!$product) {
            return redirect()->back()
                ->with('error', __('المنتج غير موجود في منشأتك'))
                ->withInput();
        }

        $lead = $request->filled('lead_id') ? $facility->leads()->find($request->lead_id) : null;

        $booking = Booking::create([
            'facility_id' => $facility->id,
            'lead_id' => $lead?->id,
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'message' => $request->message,
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
            'is_confirmed' => $request->boolean('is_confirmed'),
            'is_paid' => $request->boolean('is_paid'),
        ]);

        if ($lead && in_array($lead->stage, [Lead::STAGE_NEW, Lead::STAGE_CONTACTED], true)) {
            $lead->update(['stage' => Lead::STAGE_VIEWING]);
        }

        return redirect()->route('facility.bookings.index')
            ->with('success', __('تم إنشاء الحجز بنجاح'));
    }

    /**
     * عرض صفحة تعديل الحجز
     */
    public function edit(Booking $booking)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $booking->product->facility_id !== $facility->id) {
            return redirect()->route('facility.bookings.index')
                ->with('error', __('غير مصرح لك بتعديل هذا الحجز'));
        }

        $booking->load(['user', 'product']);
        $products = $facility->products()->where('is_active', true)->get();
        $statuses = Status::all();

        return view('facility.bookings.edit', compact('booking', 'products', 'statuses'));
    }

    /**
     * تحديث الحجز
     */
    public function update(Request $request, Booking $booking)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $booking->product->facility_id !== $facility->id) {
            return redirect()->route('facility.bookings.index')
                ->with('error', __('غير مصرح لك بتعديل هذا الحجز'));
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'status' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_confirmed' => 'boolean',
            'is_paid' => 'boolean',
        ]);

        // التحقق من أن المنتج ينتمي للمنشأة
        $product = $facility->products()->find($request->product_id);
        if (!$product) {
            return redirect()->back()
                ->with('error', __('المنتج غير موجود في منشأتك'))
                ->withInput();
        }

        $booking->update($request->all());

        return redirect()->route('facility.bookings.index')
            ->with('success', __('تم تحديث الحجز بنجاح'));
    }

    /**
     * حذف الحجز
     */
    public function destroy(Booking $booking)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $booking->product->facility_id !== $facility->id) {
            return redirect()->route('facility.bookings.index')
                ->with('error', __('غير مصرح لك بحذف هذا الحجز'));
        }

        $booking->delete();

        return redirect()->route('facility.bookings.index')
            ->with('success', __('تم حذف الحجز بنجاح'));
    }

    /**
     * تأكيد الحجز
     */
    public function confirm(Booking $booking)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $booking->product->facility_id !== $facility->id) {
            return redirect()->route('facility.bookings.index')
                ->with('error', __('غير مصرح لك بتعديل هذا الحجز'));
        }

        $booking->update(['is_confirmed' => true]);

        return redirect()->back()->with('success', __('تم تأكيد الحجز بنجاح'));
    }

    /**
     * إلغاء تأكيد الحجز
     */
    public function unconfirm(Booking $booking)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $booking->product->facility_id !== $facility->id) {
            return redirect()->route('facility.bookings.index')
                ->with('error', __('غير مصرح لك بتعديل هذا الحجز'));
        }

        $booking->update(['is_confirmed' => false]);

        return redirect()->back()->with('success', __('تم إلغاء تأكيد الحجز بنجاح'));
    }

    /**
     * تحديث حالة الدفع
     */
    public function updatePaymentStatus(Booking $booking)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $booking->product->facility_id !== $facility->id) {
            return redirect()->route('facility.bookings.index')
                ->with('error', __('غير مصرح لك بتعديل هذا الحجز'));
        }

        $booking->update(['is_paid' => !$booking->is_paid]);

        $status = $booking->is_paid ? 'دفع' : 'إلغاء دفع';
        return redirect()->back()->with('success', "تم {$status} الحجز بنجاح");
    }

    /**
     * عرض تفاصيل الحجز
     */
    public function show(Booking $booking)
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility || $booking->product->facility_id !== $facility->id) {
            return redirect()->route('facility.bookings.index')
                ->with('error', __('غير مصرح لك بعرض هذا الحجز'));
        }

        $booking->load(['user', 'product']);
        return view('facility.bookings.show', compact('booking'));
    }

    /**
     * إحصائيات الحجوزات
     */
    public function statistics()
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $stats = [
            'total_bookings' => $facility->bookings()->count(),
            'confirmed_bookings' => $facility->bookings()->where('is_confirmed', true)->count(),
            'paid_bookings' => $facility->bookings()->where('is_paid', true)->count(),
            'pending_bookings' => $facility->bookings()->where('status', 'pending')->count(), // pending status
            'monthly_revenue' => $facility->bookings()->where('is_paid', true)
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
            'recent_bookings' => $facility->bookings()->with(['user', 'product'])
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('facility.bookings.statistics', compact('stats'));
    }

    /**
     * تقويم الحجوزات
     */
    public function calendar()
    {
        $facility = Auth::user()->facilities()->first();

        if (!$facility) {
            return redirect()->route('facility.create');
        }

        $bookings = $facility->bookings()
            ->with(['user', 'product'])
            ->whereNotNull('preferred_date')
            ->where('preferred_date', '>=', now()->startOfMonth())
            ->where('preferred_date', '<=', now()->endOfMonth())
            ->get()
            ->map(function ($booking) {
                $start = $booking->preferred_date->toDateString() . 'T' . ($booking->preferred_time ?? '09:00');
                return [
                    'id' => $booking->id,
                    'title' => ($booking->user?->name ?? $booking->name ?? '#') . ' - ' . ($booking->product?->getTranslatedTitle() ?? '—'),
                    'start' => $start,
                    'end' => $booking->preferred_date->toDateString() . 'T' . date('H:i', strtotime(($booking->preferred_time ?? '09:00') . ' +1 hour')),
                    'url' => route('facility.bookings.show', $booking->id),
                    'className' => $booking->is_confirmed ? 'confirmed-booking' : 'pending-booking',
                ];
            });

        return view('facility.bookings.calendar', compact('bookings'));
    }
}
