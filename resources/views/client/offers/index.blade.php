@extends('layouts.app')

@section('title', 'العروض المتاحة')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">العروض المتاحة</h1>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">جميع الأنواع</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>بيع</option>
                        <option value="rent_monthly" {{ request('type') == 'rent_monthly' ? 'selected' : '' }}>إيجار شهري</option>
                        <option value="rent_yearly" {{ request('type') == 'rent_yearly' ? 'selected' : '' }}>إيجار سنوي</option>
                        <option value="rent_daily" {{ request('type') == 'rent_daily' ? 'selected' : '' }}>إيجار يومي</option>
                    </select>
                </div>
                <div>
                    <input type="number" name="min_price" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="الحد الأدنى للسعر" value="{{ request('min_price') }}">
                </div>
                <div>
                    <input type="number" name="max_price" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="الحد الأقصى للسعر" value="{{ request('max_price') }}">
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        بحث
                    </button>
                </div>
            </form>
        </div>

        @if($offers->count() > 0)
            <!-- Offers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($offers as $offer)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        @if($offer->product->image)
                            <img src="{{ asset('storage/' . $offer->product->image) }}" 
                                 class="w-full h-48 object-cover" alt="صورة المشروع">
                        @endif
                        
                        <div class="p-6 flex flex-col h-full">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-semibold text-gray-900 flex-1">{{ $offer->product->getTranslatedTitle() }}</h3>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-2
                                    {{ $offer->offer_type == 'sale' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    @switch($offer->offer_type)
                                        @case('sale') بيع @break
                                        @case('rent_monthly') إيجار شهري @break
                                        @case('rent_yearly') إيجار سنوي @break
                                        @case('rent_daily') إيجار يومي @break
                                    @endswitch
                                </span>
                            </div>
                            
                            <p class="text-gray-500 text-sm mb-3 flex items-center">
                                <i class="fas fa-map-marker-alt ml-2"></i>
                                {{ $offer->product->address }}
                            </p>
                            
                            <div class="mb-4">
                                <div class="text-2xl font-bold text-blue-600 mb-1">
                                    <div class="flex items-center">
                                        {{ number_format($offer->price, 2) }}
                                        <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                    </div>
                                </div>
                                @if($offer->deposit_amount)
                                    <div class="text-sm text-gray-500">
                                        <div class="flex items-center">
                                            العربون: {{ number_format($offer->deposit_amount, 2) }}
                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-auto">
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-building ml-2"></i>
                                        {{ $offer->facility->name ?? 'غير محدد' }}
                                    </div>
                                    <div class="flex space-x-2 space-x-reverse">
                                        <a href="{{ route('client.offers.show', $offer) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-eye ml-2"></i>
                                            عرض
                                        </a>
                                        @auth
                                            <button class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors add-to-favorites" 
                                                    data-offer-id="{{ $offer->id }}">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $offers->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد عروض</h3>
                <p class="text-gray-500">جرب تغيير معايير البحث</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // إضافة للمفضلة
    document.querySelectorAll('.add-to-favorites').forEach(button => {
        button.addEventListener('click', function() {
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
                    this.innerHTML = '<i class="fas fa-heart text-red-500"></i>';
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
    });

    // Auto-submit form on filter change
    document.querySelectorAll('select[name="type"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush