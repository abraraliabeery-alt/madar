<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * عرض صفحة إنشاء حجز جديد
     */
    public function create(Request $request)
    {
        $offerId = $request->get('offer_id');
        $productId = $request->get('product_id');
        
        $offer = null;
        $product = null;
        $facility = null;
        
        if ($offerId) {
            $offer = Offer::with(['product', 'facility'])->find($offerId);
            if ($offer) {
                $product = $offer->product;
                $facility = $offer->facility;
            }
        } elseif ($productId) {
            $product = Product::with(['facility'])->find($productId);
            if ($product) {
                $facility = $product->facility;
            }
        }
        
        // إذا لم يتم العثور على منتج، إعادة توجيه
        if (!$product) {
            return redirect()->route('products.index')
                ->with('error', 'المنتج غير موجود');
        }
        
        return view('public.bookings.create', compact('offer', 'product', 'facility'));
    }
    
    /**
     * حفظ حجز جديد
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'offer_id' => 'nullable|exists:offers,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
            'message' => 'nullable|string|max:1000',
            'visit_type' => 'required|in:inspection,consultation,meeting',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $bookingData = [
                'product_id' => $request->product_id,
                'offer_id' => $request->offer_id,
                'facility_id' => $request->facility_id,
                'user_id' => Auth::id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'preferred_date' => Carbon::parse($request->preferred_date),
                'preferred_time' => $request->preferred_time,
                'message' => $request->message,
                'visit_type' => $request->visit_type,
                'status' => 'pending',
                'created_by' => Auth::id(),
            ];
            
            $booking = Booking::create($bookingData);
            
            // إرسال إشعار للمؤسسة
            // يمكن إضافة منطق الإشعارات هنا
            
            return redirect()->route('public.bookings.success', $booking->id)
                ->with('success', 'تم إرسال طلب الحجز بنجاح. سنتواصل معك قريباً لتأكيد الموعد.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ في إرسال طلب الحجز: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * صفحة نجاح الحجز
     */
    public function success($id)
    {
        $booking = Booking::with(['product', 'offer', 'facility'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('public.bookings.success', compact('booking'));
    }
    
    /**
     * عرض تفاصيل الحجز
     */
    public function show($id)
    {
        $booking = Booking::with(['product', 'offer', 'facility'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('public.bookings.show', compact('booking'));
    }
    
    /**
     * إلغاء الحجز
     */
    public function cancel($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->findOrFail($id);
            
        if ($booking->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'لا يمكن إلغاء هذا الحجز');
        }
        
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => 'إلغاء من قبل العميل'
        ]);
        
        return redirect()->back()
            ->with('success', 'تم إلغاء الحجز بنجاح');
    }
    
    /**
     * إعادة جدولة الحجز
     */
    public function reschedule(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
            'reason' => 'nullable|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }
        
        $booking = Booking::where('user_id', Auth::id())
            ->findOrFail($id);
            
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()->back()
                ->with('error', 'لا يمكن إعادة جدولة هذا الحجز');
        }
        
        $booking->update([
            'preferred_date' => Carbon::parse($request->preferred_date),
            'preferred_time' => $request->preferred_time,
            'reschedule_reason' => $request->reason,
            'status' => 'rescheduled',
            'rescheduled_at' => now(),
        ]);
        
        return redirect()->back()
            ->with('success', 'تم إرسال طلب إعادة الجدولة بنجاح');
    }
}
