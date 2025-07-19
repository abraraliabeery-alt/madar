<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiBookingController extends Controller
{
    /**
     * Display a listing of user bookings
     */
    public function index()
    {
        $user = Auth::user();
        $bookings = $user->bookings()->with(['product', 'facility'])->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Check for conflicts
        $conflictingBooking = Booking::where('product_id', $request->product_id)
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time)
            ->where('is_confirmed', true)
            ->first();

        if ($conflictingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الموعد محجوز مسبقاً'
            ], 400);
        }

        $booking = $user->bookings()->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الحجز بنجاح',
            'data' => $booking
        ]);
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        $booking->load(['product', 'facility']);

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    /**
     * Update the specified booking
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_date' => 'sometimes|date|after:today',
            'booking_time' => 'sometimes|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $booking->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الحجز بنجاح',
            'data' => $booking
        ]);
    }

    /**
     * Remove the specified booking
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الحجز بنجاح'
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancel(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الحجز بنجاح'
        ]);
    }

    /**
     * Reschedule booking
     */
    public function reschedule(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required|date_format:H:i',
        ]);

        $booking->update($request->only(['booking_date', 'booking_time']));

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة جدولة الحجز بنجاح'
        ]);
    }

    /**
     * Review booking
     */
    public function review(Request $request, Booking $booking)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        $booking->update([
            'rating' => $request->rating,
            'review' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة التقييم بنجاح'
        ]);
    }
}
