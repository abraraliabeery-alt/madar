@extends('client.layouts.app')

@section('title', 'ط§ظ„ط¹ط±ظˆط¶ ط§ظ„ظ…طھط§ط­ط©')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">ط§ظ„ط¹ط±ظˆط¶ ط§ظ„ظ…طھط§ط­ط©</h1>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">ط¬ظ…ظٹط¹ ط§ظ„ط£ظ†ظˆط§ط¹</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>ط¨ظٹط¹</option>
                        <option value="rent_monthly" {{ request('type') == 'rent_monthly' ? 'selected' : '' }}>ط¥ظٹط¬ط§ط± ط´ظ‡ط±ظٹ</option>
                        <option value="rent_yearly" {{ request('type') == 'rent_yearly' ? 'selected' : '' }}>ط¥ظٹط¬ط§ط± ط³ظ†ظˆظٹ</option>
                        <option value="rent_daily" {{ request('type') == 'rent_daily' ? 'selected' : '' }}>ط¥ظٹط¬ط§ط± ظٹظˆظ…ظٹ</option>
                    </select>
                </div>
                <div>
                    <input type="number" name="min_price" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="ط§ظ„ط­ط¯ ط§ظ„ط£ط¯ظ†ظ‰ ظ„ظ„ط³ط¹ط±" value="{{ request('min_price') }}">
                </div>
                <div>
                    <input type="number" name="max_price" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="ط§ظ„ط­ط¯ ط§ظ„ط£ظ‚طµظ‰ ظ„ظ„ط³ط¹ط±" value="{{ request('max_price') }}">
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        ط¨ط­ط«
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
                                 class="w-full h-48 object-cover" alt="طµظˆط±ط© ط§ظ„ظ…ط´ط±ظˆط¹">
                        @endif
                        
                        <div class="p-6 flex flex-col h-full">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-semibold text-gray-900 flex-1">{{ $offer->product->getTranslatedTitle() }}</h3>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-2
                                    {{ $offer->offer_type == 'sale' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    @switch($offer->offer_type)
                                        @case('sale') ط¨ظٹط¹ @break
                                        @case('rent_monthly') ط¥ظٹط¬ط§ط± ط´ظ‡ط±ظٹ @break
                                        @case('rent_yearly') ط¥ظٹط¬ط§ط± ط³ظ†ظˆظٹ @break
                                        @case('rent_daily') ط¥ظٹط¬ط§ط± ظٹظˆظ…ظٹ @break
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
                                            ط§ظ„ط¹ط±ط¨ظˆظ†: {{ number_format($offer->deposit_amount, 2) }}
                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-auto">
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-building ml-2"></i>
                                        {{ $offer->facility->name ?? 'ط؛ظٹط± ظ…ط­ط¯ط¯' }}
                                    </div>
                                    <div class="flex space-x-2 space-x-reverse">
                                        <a href="{{ route('client.offers.show', $offer) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-eye ml-2"></i>
                                            ط¹ط±ط¶
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
                <h3 class="text-lg font-medium text-gray-900 mb-2">ظ„ط§ طھظˆط¬ط¯ ط¹ط±ظˆط¶</h3>
                <p class="text-gray-500">ط¬ط±ط¨ طھط؛ظٹظٹط± ظ…ط¹ط§ظٹظٹط± ط§ظ„ط¨ط­ط«</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ط¥ط¶ط§ظپط© ظ„ظ„ظ…ظپط¶ظ„ط©
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
