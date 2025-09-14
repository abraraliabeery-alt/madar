@extends('layouts.app')

@section('title', 'مقارنة العروض')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">مقارنة العروض</h1>
            <p class="text-gray-600">قارن بين العروض المختلفة لاختيار الأنسب لك</p>
        </div>

        @if($offers->count() > 0)
            <!-- Comparison Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المعيار</th>
                                @foreach($offers as $offer)
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex flex-col items-center">
                                            @if($offer->product->image)
                                                <img src="{{ asset('storage/' . $offer->product->image) }}" 
                                                     alt="{{ $offer->product->getTranslatedTitle() }}" 
                                                     class="w-16 h-16 object-cover rounded-lg mb-2">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 rounded-lg mb-2 flex items-center justify-center">
                                                    <i class="fas fa-home text-gray-400"></i>
                                                </div>
                                            @endif
                                            <span class="text-sm font-medium text-gray-900">{{ $offer->product->getTranslatedTitle() }}</span>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Price -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">السعر</td>
                                @foreach($offers as $offer)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-lg font-bold text-primary-600">
                                            <span class="flex items-center">
                                                {{ number_format($offer->price, 2) }}
                                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                            </span>
                                        </div>
                                        @if($offer->deposit_amount)
                                            <div class="text-sm text-gray-600">
                                                <span class="flex items-center">
                                                    العربون: {{ number_format($offer->deposit_amount, 2) }}
                                                    <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Type -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">نوع العرض</td>
                                @foreach($offers as $offer)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $offer->offer_type == 'sale' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            @switch($offer->offer_type)
                                                @case('sale') للبيع @break
                                                @case('rent_monthly') إيجار شهري @break
                                                @case('rent_yearly') إيجار سنوي @break
                                                @case('rent_daily') إيجار يومي @break
                                            @endswitch
                                        </span>
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Location -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">الموقع</td>
                                @foreach($offers as $offer)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900">{{ $offer->product->address }}</div>
                                        @if($offer->product->city)
                                            <div class="text-sm text-gray-600">{{ $offer->product->city->name }}</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Facility -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">المنشأة</td>
                                @foreach($offers as $offer)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900">{{ $offer->facility->name ?? 'غير محدد' }}</div>
                                        @if($offer->facility)
                                            <div class="text-sm text-gray-600">{{ $offer->facility->phone ?? '' }}</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Area -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">المساحة</td>
                                @foreach($offers as $offer)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900">
                                            {{ $offer->product->area ?? 'غير محدد' }} م²
                                        </div>
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Bedrooms -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">عدد الغرف</td>
                                @foreach($offers as $offer)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900">
                                            {{ $offer->product->bedrooms ?? 'غير محدد' }}
                                        </div>
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Bathrooms -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">عدد الحمامات</td>
                                @foreach($offers as $offer)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900">
                                            {{ $offer->product->bathrooms ?? 'غير محدد' }}
                                        </div>
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Features -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">المميزات</td>
                                @foreach($offers as $offer)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex flex-wrap justify-center gap-1">
                                            @if($offer->product->features)
                                                @foreach(json_decode($offer->product->features, true) as $feature)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $feature }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-sm text-gray-500">لا توجد مميزات</span>
                                            @endif
                                        </div>
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">الوصف</td>
                                @foreach($offers as $offer)
                                    <td class="px-6 py-4 text-center">
                                        <div class="text-sm text-gray-900 max-w-xs">
                                            {{ Str::limit($offer->product->description, 100) }}
                                        </div>
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Actions -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">الإجراءات</td>
                                @foreach($offers as $offer)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex flex-col space-y-2">
                                            <a href="{{ route('client.offers.show', $offer) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                                                <i class="fas fa-eye ml-1"></i> عرض التفاصيل
                                            </a>
                                            <button onclick="addToFavorites({{ $offer->id }})" 
                                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                <i class="fas fa-heart ml-1"></i> إضافة للمفضلة
                                            </button>
                                            <button onclick="removeFromComparison({{ $offer->id }})" 
                                                    class="inline-flex items-center px-3 py-2 border border-red-300 text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                                <i class="fas fa-times ml-1"></i> إزالة من المقارنة
                                            </button>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Price Comparison -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">مقارنة الأسعار</h3>
                    <div class="space-y-2">
                        @php
                            $prices = $offers->pluck('price')->sort();
                            $minPrice = $prices->first();
                            $maxPrice = $prices->last();
                        @endphp
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">أقل سعر:</span>
                            <span class="text-sm font-medium text-green-600">{{ number_format($minPrice, 2) }} ريال</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">أعلى سعر:</span>
                            <span class="text-sm font-medium text-red-600">{{ number_format($maxPrice, 2) }} ريال</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">الفرق:</span>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($maxPrice - $minPrice, 2) }} ريال</span>
                        </div>
                    </div>
                </div>

                <!-- Features Comparison -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">المميزات المشتركة</h3>
                    <div class="space-y-2">
                        @php
                            $allFeatures = collect();
                            foreach($offers as $offer) {
                                if($offer->product->features) {
                                    $allFeatures = $allFeatures->merge(json_decode($offer->product->features, true));
                                }
                            }
                            $commonFeatures = $allFeatures->countBy()->filter(function($count) use ($offers) {
                                return $count == $offers->count();
                            })->keys();
                        @endphp
                        @if($commonFeatures->count() > 0)
                            @foreach($commonFeatures as $feature)
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 ml-2"></i>
                                    <span class="text-sm text-gray-700">{{ $feature }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">لا توجد مميزات مشتركة</p>
                        @endif
                    </div>
                </div>

                <!-- Recommendations -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">التوصيات</h3>
                    <div class="space-y-3">
                        @php
                            $bestValue = $offers->sortBy('price')->first();
                            $largestArea = $offers->sortByDesc(function($offer) {
                                return $offer->product->area ?? 0;
                            })->first();
                        @endphp
                        <div class="p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-star text-green-500 ml-2"></i>
                                <span class="text-sm font-medium text-green-800">أفضل قيمة</span>
                            </div>
                            <p class="text-sm text-green-700 mt-1">{{ $bestValue->product->getTranslatedTitle() }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-expand text-blue-500 ml-2"></i>
                                <span class="text-sm font-medium text-blue-800">أكبر مساحة</span>
                            </div>
                            <p class="text-sm text-blue-700 mt-1">{{ $largestArea->product->getTranslatedTitle() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-balance-scale text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد عروض للمقارنة</h3>
                <p class="text-gray-600 mb-6">أضف عروض للمقارنة من صفحة العروض</p>
                <a href="{{ route('client.offers.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                    تصفح العروض
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function addToFavorites(offerId) {
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
                alert('تم إضافة العرض للمفضلة');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function removeFromComparison(offerId) {
        if (confirm('هل أنت متأكد من إزالة هذا العرض من المقارنة؟')) {
            fetch(`/client/offers/${offerId}/remove-from-comparison`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
</script>
@endpush

@push('styles')
<style>
.bg-primary-600 {
    background-color: #2563eb;
}

.hover\:bg-primary-700:hover {
    background-color: #1d4ed8;
}

.text-primary-600 {
    color: #3b82f6;
}
</style>
@endpush
