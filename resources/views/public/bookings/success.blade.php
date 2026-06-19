@extends('layouts.app')

@section('title', 'تم إرسال طلب الحجز بنجاح')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                <i class="fas fa-check text-green-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                تم إرسال طلب الحجز بنجاح!
            </h1>
            <p class="text-lg text-gray-600 mb-6">
                شكراً لك على اهتمامك. سنتواصل معك قريباً لتأكيد الموعد
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">تفاصيل الحجز</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">رقم الحجز</h3>
                            <p class="text-lg font-semibold text-gray-900">#{{ $booking->id }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">الحالة</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock ml-1"></i>
                                في انتظار التأكيد
                            </span>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">الاسم</h3>
                            <p class="text-gray-900">{{ $booking->name }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">البريد الإلكتروني</h3>
                            <p class="text-gray-900">{{ $booking->email }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">رقم الهاتف</h3>
                            <p class="text-gray-900">{{ $booking->phone }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">نوع الزيارة</h3>
                            <p class="text-gray-900">
                                @if($booking->visit_type == 'inspection')
                                    معاينة المشروع
                                @elseif($booking->visit_type == 'consultation')
                                    استشارة مشاريعية
                                @elseif($booking->visit_type == 'meeting')
                                    اجتماع
                                @else
                                    {{ $booking->visit_type }}
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">التاريخ المفضل</h3>
                            <p class="text-gray-900">{{ $booking->preferred_date->format('Y/m/d') }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">الوقت المفضل</h3>
                            <p class="text-gray-900">
                                @if($booking->preferred_time == 'morning')
                                    صباحاً (9:00 - 12:00)
                                @elseif($booking->preferred_time == 'afternoon')
                                    بعد الظهر (12:00 - 17:00)
                                @elseif($booking->preferred_time == 'evening')
                                    مساءً (17:00 - 20:00)
                                @else
                                    {{ $booking->preferred_time }}
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @if($booking->message)
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">الرسالة</h3>
                            <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $booking->message }}</p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">الإجراءات المتاحة</h2>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('public.bookings.show', $booking->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-200">
                            <i class="fas fa-eye ml-2"></i>
                            عرض تفاصيل الحجز
                        </a>
                        
                        <button onclick="rescheduleBooking({{ $booking->id }})" 
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition duration-200">
                            <i class="fas fa-calendar-alt ml-2"></i>
                            إعادة جدولة
                        </button>
                        
                        <button onclick="cancelBooking({{ $booking->id }})" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">
                            <i class="fas fa-times ml-2"></i>
                            إلغاء الحجز
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Product Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات المشروع</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <img src="{{ $booking->product->image_url }}" 
                                 alt="{{ $booking->product->title }}" 
                                 class="w-16 h-16 rounded-lg object-cover">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $booking->product->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $booking->product->address }}</p>
                                @if($booking->product->price)
                                    <p class="text-sm font-semibold text-primary-600">
                                        {{ number_format($booking->product->price) }} ريال
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <a href="{{ route('public.products.show', $booking->product) }}" 
                           class="inline-flex items-center text-primary-600 hover:text-primary-700 text-sm font-medium">
                            <i class="fas fa-external-link-alt ml-2"></i>
                            عرض المشروع
                        </a>
                    </div>
                </div>

                <!-- Offer Info -->
                @if($booking->offer)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل العرض</h3>
                        <div class="space-y-2">
                            <p class="text-sm text-gray-600">
                                <strong>السعر:</strong> {{ number_format($booking->offer->price) }} ريال
                            </p>
                            @if($booking->offer->deposit_amount)
                                <p class="text-sm text-gray-600">
                                    <strong>العربون:</strong> {{ number_format($booking->offer->deposit_amount) }} ريال
                                </p>
                            @endif
                            <p class="text-sm text-gray-600">
                                <strong>نوع العرض:</strong> 
                                @if($booking->offer->offer_type == 'sale')
                                    بيع
                                @elseif(str_starts_with($booking->offer->offer_type, 'rent_'))
                                    إيجار
                                @else
                                    {{ $booking->offer->offer_type }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Facility Info -->
                @if($booking->facility)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات المؤسسة</h3>
                        <div class="flex items-center space-x-3 space-x-reverse mb-4">
                            <img src="{{ $booking->facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80' }}" 
                                 alt="{{ $booking->facility->name }}" 
                                 class="w-12 h-12 rounded object-cover">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $booking->facility->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $booking->facility->category->name ?? '' }}</p>
                            </div>
                        </div>
                        @if($booking->facility->phone)
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-phone ml-2"></i>
                                {{ $booking->facility->phone }}
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Next Steps -->
                <div class="bg-green-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-green-900 mb-4">
                        <i class="fas fa-info-circle ml-2"></i>
                        الخطوات التالية
                    </h3>
                    <ul class="text-sm text-green-800 space-y-2">
                        <li>• سنراجع طلبك خلال 24 ساعة</li>
                        <li>• سنتواصل معك لتأكيد الموعد</li>
                        <li>• ستحصل على رسالة تأكيد بالتفاصيل</li>
                        <li>• يمكنك متابعة حالة الحجز من حسابك</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">إعادة جدولة الحجز</h3>
                <button onclick="closeRescheduleModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="rescheduleForm" class="mt-4">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">التاريخ الجديد</label>
                        <input type="date" name="preferred_date" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الوقت الجديد</label>
                        <select name="preferred_time" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">اختر الوقت</option>
                            <option value="morning">صباحاً (9:00 - 12:00)</option>
                            <option value="afternoon">بعد الظهر (12:00 - 17:00)</option>
                            <option value="evening">مساءً (17:00 - 20:00)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">سبب إعادة الجدولة</label>
                        <textarea name="reason" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="اكتب سبب إعادة الجدولة..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 space-x-reverse pt-4 border-t border-gray-200 mt-6">
                    <button type="button" onclick="closeRescheduleModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition duration-200">
                        إرسال طلب إعادة الجدولة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function rescheduleBooking(bookingId) {
    document.getElementById('rescheduleModal').classList.remove('hidden');
    
    // Set minimum date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.querySelector('input[name="preferred_date"]').min = tomorrow.toISOString().split('T')[0];
    
    // Handle form submission
    document.getElementById('rescheduleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitReschedule(bookingId, this);
    });
}

function closeRescheduleModal() {
    document.getElementById('rescheduleModal').classList.add('hidden');
}

function submitReschedule(bookingId, form) {
    const formData = new FormData(form);
    
    fetch(`/public/bookings/${bookingId}/reschedule`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إرسال طلب إعادة الجدولة بنجاح');
            closeRescheduleModal();
            location.reload();
        } else {
            alert('حدث خطأ في إرسال الطلب');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في إرسال الطلب');
    });
}

function cancelBooking(bookingId) {
    if (confirm('هل أنت متأكد من إلغاء هذا الحجز؟')) {
        fetch(`/public/bookings/${bookingId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إلغاء الحجز بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ في إلغاء الحجز');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في إلغاء الحجز');
        });
    }
}
</script>
@endpush
