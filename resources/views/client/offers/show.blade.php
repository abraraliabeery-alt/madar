@extends('layouts.app')

@section('title', $offer->product->getTranslatedTitle())

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- المحتوى الرئيسي -->
            <div class="lg:col-span-2 space-y-6">
                <!-- صور المنتج -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    @if($offer->product->image)
                        <img src="{{ asset('storage/' . $offer->product->image) }}" 
                             class="w-full h-96 object-cover" alt="صورة العقار">
                    @else
                        <div class="flex items-center justify-center bg-gray-100 h-96">
                            <div class="text-center">
                                <i class="fas fa-image text-6xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">لا توجد صورة متاحة</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- تفاصيل العرض -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $offer->product->getTranslatedTitle() }}</h1>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $offer->offer_type == 'sale' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            @switch($offer->offer_type)
                                @case('sale') بيع @break
                                @case('rent_monthly') إيجار شهري @break
                                @case('rent_yearly') إيجار سنوي @break
                                @case('rent_daily') إيجار يومي @break
                            @endswitch
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل العقار</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">العنوان:</span>
                                    <span class="text-gray-900 text-right">{{ $offer->product->address }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">الوصف:</span>
                                    <span class="text-gray-900 text-right">{{ $offer->product->getTranslatedDescription() }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">المنشأة:</span>
                                    <span class="text-gray-900 text-right">{{ $offer->facility->name ?? 'غير محدد' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل العرض</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">السعر:</span>
                                    <div class="flex items-center text-blue-600 text-lg font-bold">
                                        {{ number_format($offer->price, 2) }}
                                        <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-5 h-5 mr-1">
                                    </div>
                                </div>
                                @if($offer->deposit_amount)
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="font-medium text-gray-700">العربون:</span>
                                        <div class="flex items-center text-gray-900">
                                            {{ number_format($offer->deposit_amount, 2) }}
                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                        </div>
                                    </div>
                                @endif
                                @if($offer->commission_amount)
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="font-medium text-gray-700">العمولة:</span>
                                        <div class="flex items-center text-gray-900">
                                            {{ number_format($offer->commission_amount, 2) }}
                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                        </div>
                                    </div>
                                @endif
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">صالح من:</span>
                                    <span class="text-gray-900">{{ $offer->valid_from ? $offer->valid_from->format('Y-m-d') : 'غير محدد' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">صالح حتى:</span>
                                    <span class="text-gray-900">{{ $offer->valid_to ? $offer->valid_to->format('Y-m-d') : 'غير محدد' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($offer->getTranslatedTerms())
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">الشروط والأحكام</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-gray-700 whitespace-pre-line">{!! nl2br(e($offer->getTranslatedTerms())) !!}</div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- العروض المشابهة -->
                @if($similarOffers->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">عروض مشابهة</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($similarOffers as $similarOffer)
                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                    @if($similarOffer->product->image)
                                        <img src="{{ asset('storage/' . $similarOffer->product->image) }}" 
                                             class="w-full h-32 object-cover" alt="صورة العقار">
                                    @endif
                                    <div class="p-4">
                                        <h4 class="font-semibold text-gray-900 mb-2">{{ $similarOffer->product->getTranslatedTitle() }}</h4>
                                        <p class="text-gray-500 text-sm mb-3">{{ $similarOffer->product->address }}</p>
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center text-blue-600 font-bold">
                                                {{ number_format($similarOffer->price, 2) }}
                                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                            </div>
                                            <a href="{{ route('client.offers.show', $similarOffer) }}" 
                                               class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                عرض
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- الشريط الجانبي -->
            <div class="space-y-6">
                <!-- معلومات الاتصال -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الاتصال</h3>
                    <div class="space-y-3">
                        <button class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" 
                                x-data @click="$dispatch('open-modal', 'contactModal')">
                            <i class="fas fa-phone ml-2"></i>
                            طلب معلومات
                        </button>
                        <button class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" 
                                x-data @click="$dispatch('open-modal', 'visitModal')">
                            <i class="fas fa-calendar ml-2"></i>
                            حجز موعد زيارة
                        </button>
                        @auth
                            <button class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors add-to-favorites" 
                                    data-offer-id="{{ $offer->id }}">
                                <i class="fas fa-heart ml-2"></i>
                                إضافة للمفضلة
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-heart ml-2"></i>
                                إضافة للمفضلة
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- معلومات المنشأة -->
                @if($offer->facility)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">المنشأة</h3>
                        <div class="text-center">
                            @if($offer->facility->logo)
                                <img src="{{ asset('storage/' . $offer->facility->logo) }}" 
                                     class="rounded-full mx-auto mb-4 w-20 h-20 object-cover" alt="شعار المنشأة">
                            @endif
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $offer->facility->name }}</h4>
                            <p class="text-gray-500 text-sm mb-4">{{ $offer->facility->description ?? 'لا يوجد وصف' }}</p>
                            <a href="{{ route('facilities.show', $offer->facility) }}" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                عرض المنشأة
                            </a>
                        </div>
                    </div>
                @endif

                <!-- إحصائيات العرض -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">إحصائيات العرض</h3>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="border-r border-gray-200">
                            <div class="text-2xl font-bold text-blue-600">{{ $offer->product->views_count ?? 0 }}</div>
                            <div class="text-sm text-gray-500">المشاهدات</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-yellow-600">{{ $offer->product->rating ?? 0 }}</div>
                            <div class="text-sm text-gray-500">التقييم</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal طلب معلومات -->
<div x-data="{ show: false }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" @open-modal.window="if ($event.detail === 'contactModal') show = true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-phone text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">طلب معلومات</h3>
                        <form id="contactForm">
                            <div class="space-y-4">
                                <div>
                                    <label for="contact_name" class="block text-sm font-medium text-gray-700">الاسم <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="contact_name" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" id="contact_email" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700">رقم الهاتف <span class="text-red-500">*</span></label>
                                    <input type="tel" name="phone" id="contact_phone" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="contact_message" class="block text-sm font-medium text-gray-700">الرسالة</label>
                                    <textarea name="message" id="contact_message" rows="3"
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="show = false; submitContact()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    إرسال
                </button>
                <button type="button" @click="show = false" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    إلغاء
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal حجز موعد زيارة -->
<div x-data="{ show: false }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" @open-modal.window="if ($event.detail === 'visitModal') show = true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-calendar text-green-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">حجز موعد زيارة</h3>
                        <form id="visitForm">
                            <div class="space-y-4">
                                <div>
                                    <label for="visit_date" class="block text-sm font-medium text-gray-700">تاريخ الزيارة <span class="text-red-500">*</span></label>
                                    <input type="date" name="visit_date" id="visit_date" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="visit_time" class="block text-sm font-medium text-gray-700">وقت الزيارة <span class="text-red-500">*</span></label>
                                    <input type="time" name="visit_time" id="visit_time" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="visit_notes" class="block text-sm font-medium text-gray-700">ملاحظات</label>
                                    <textarea name="notes" id="visit_notes" rows="3"
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="show = false; submitVisit()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    حجز الموعد
                </button>
                <button type="button" @click="show = false" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    إلغاء
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // إضافة للمفضلة
    document.querySelector('.add-to-favorites')?.addEventListener('click', function() {
        const offerId = this.dataset.offerId;
        
        fetch(`/client/offers/${offerId}/add-to-favorites`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.innerHTML = '<i class="fas fa-heart text-red-500 ml-2"></i> تمت الإضافة';
                this.classList.remove('bg-gray-100', 'text-gray-700');
                this.classList.add('bg-red-100', 'text-red-600');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    function submitContact() {
        const formData = new FormData(document.getElementById('contactForm'));
        
        fetch(`/client/offers/{{ $offer->id }}/request-info`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إرسال طلبك بنجاح. سنتواصل معك قريباً.');
                document.getElementById('contactForm').reset();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function submitVisit() {
        const formData = new FormData(document.getElementById('visitForm'));
        
        fetch(`/client/offers/{{ $offer->id }}/book-visit`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم حجز موعد الزيارة بنجاح');
                document.getElementById('visitForm').reset();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // تعيين التاريخ الحالي كافتراضي
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('visit_date').value = today;
    });
</script>
@endpush