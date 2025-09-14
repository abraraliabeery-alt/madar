@extends('layouts.app')

@section('title', 'حجز موعد - ' . ($product->title ?? ''))

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                <i class="fas fa-calendar-check text-primary-600 ml-2"></i>
                حجز موعد للمعاينة
            </h1>
            <p class="text-lg text-gray-600">
                احجز موعدك لمعاينة العقار والتشاور مع فريقنا المتخصص
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">معلومات الحجز</h2>
                    
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                            <div class="flex">
                                <i class="fas fa-exclamation-circle text-red-400 ml-3"></i>
                                <div>
                                    <h3 class="text-sm font-medium text-red-800">يرجى تصحيح الأخطاء التالية:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('public.bookings.store') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Hidden Fields -->
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        @if($offer)
                            <input type="hidden" name="offer_id" value="{{ $offer->id }}">
                        @endif
                        @if($facility)
                            <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                        @endif

                        <!-- Personal Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    الاسم الكامل <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', Auth::user()->name ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror"
                                       required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    البريد الإلكتروني <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', Auth::user()->email ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-500 @enderror"
                                       required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    رقم الهاتف <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('phone') border-red-500 @enderror"
                                       placeholder="05xxxxxxxx"
                                       required>
                            </div>
                            
                            <div>
                                <label for="visit_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    نوع الزيارة <span class="text-red-500">*</span>
                                </label>
                                <select id="visit_type" 
                                        name="visit_type" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('visit_type') border-red-500 @enderror"
                                        required>
                                    <option value="">اختر نوع الزيارة</option>
                                    <option value="inspection" {{ old('visit_type') == 'inspection' ? 'selected' : '' }}>معاينة العقار</option>
                                    <option value="consultation" {{ old('visit_type') == 'consultation' ? 'selected' : '' }}>استشارة عقارية</option>
                                    <option value="meeting" {{ old('visit_type') == 'meeting' ? 'selected' : '' }}>اجتماع</option>
                                </select>
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="preferred_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    التاريخ المفضل <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="preferred_date" 
                                       name="preferred_date" 
                                       value="{{ old('preferred_date') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('preferred_date') border-red-500 @enderror"
                                       required>
                            </div>
                            
                            <div>
                                <label for="preferred_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    الوقت المفضل <span class="text-red-500">*</span>
                                </label>
                                <select id="preferred_time" 
                                        name="preferred_time" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('preferred_time') border-red-500 @enderror"
                                        required>
                                    <option value="">اختر الوقت</option>
                                    <option value="morning" {{ old('preferred_time') == 'morning' ? 'selected' : '' }}>صباحاً (9:00 - 12:00)</option>
                                    <option value="afternoon" {{ old('preferred_time') == 'afternoon' ? 'selected' : '' }}>بعد الظهر (12:00 - 17:00)</option>
                                    <option value="evening" {{ old('preferred_time') == 'evening' ? 'selected' : '' }}>مساءً (17:00 - 20:00)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                رسالة إضافية (اختياري)
                            </label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('message') border-red-500 @enderror"
                                      placeholder="اكتب أي ملاحظات أو متطلبات خاصة...">{{ old('message') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4 space-x-reverse">
                            <a href="{{ url()->previous() }}" 
                               class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                                إلغاء
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-200">
                                <i class="fas fa-calendar-check ml-2"></i>
                                إرسال طلب الحجز
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Product Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات العقار</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <img src="{{ $product->image_url }}" 
                                 alt="{{ $product->title }}" 
                                 class="w-16 h-16 rounded-lg object-cover">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $product->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $product->address }}</p>
                                @if($product->price)
                                    <p class="text-sm font-semibold text-primary-600">
                                        {{ number_format($product->price) }} ريال
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        @if($offer)
                            <div class="border-t pt-4">
                                <h5 class="font-medium text-gray-900 mb-2">تفاصيل العرض</h5>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p><strong>السعر:</strong> {{ number_format($offer->price) }} ريال</p>
                                    @if($offer->deposit_amount)
                                        <p><strong>العربون:</strong> {{ number_format($offer->deposit_amount) }} ريال</p>
                                    @endif
                                    <p><strong>نوع العرض:</strong> 
                                        @if($offer->offer_type == 'sale')
                                            بيع
                                        @elseif(str_starts_with($offer->offer_type, 'rent_'))
                                            إيجار
                                        @else
                                            {{ $offer->offer_type }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Facility Info -->
                @if($facility)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات المؤسسة</h3>
                        <div class="flex items-center space-x-3 space-x-reverse mb-4">
                            <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80' }}" 
                                 alt="{{ $facility->name }}" 
                                 class="w-12 h-12 rounded object-cover">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $facility->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $facility->category->name ?? '' }}</p>
                            </div>
                        </div>
                        @if($facility->address)
                            <p class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt ml-2"></i>
                                {{ $facility->address }}
                            </p>
                        @endif
                        @if($facility->phone)
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-phone ml-2"></i>
                                {{ $facility->phone }}
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Booking Tips -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">
                        <i class="fas fa-lightbulb ml-2"></i>
                        نصائح للحجز
                    </h3>
                    <ul class="text-sm text-blue-800 space-y-2">
                        <li>• اختر وقتاً مناسباً لك وللمعاينة</li>
                        <li>• تأكد من توفرك في التاريخ والوقت المحددين</li>
                        <li>• سنتواصل معك لتأكيد الموعد</li>
                        <li>• يمكنك إلغاء أو إعادة جدولة الحجز إذا لزم الأمر</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('preferred_date').min = tomorrow.toISOString().split('T')[0];
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const date = new Date(document.getElementById('preferred_date').value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (date <= today) {
            e.preventDefault();
            alert('يرجى اختيار تاريخ بعد اليوم');
            return false;
        }
    });
});
</script>
@endpush
