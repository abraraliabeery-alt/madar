@extends('client.layouts.app')

@section('title', 'ط§ظ„ط¨ط­ط« ط§ظ„ظ…طھظ‚ط¯ظ…')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">ط§ظ„ط¨ط­ط« ط§ظ„ظ…طھظ‚ط¯ظ…</h1>
            <p class="text-gray-600">ط§ط¨ط­ط« ط¹ظ† ط§ظ„ظ…ط´ط§ط±ظٹط¹ ظˆط§ظ„ظ…ظ†ط´ط¢طھ ط¨ط³ظ‡ظˆظ„ط©</p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('client.offers.search') }}" class="space-y-6">
                <!-- Basic Search -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="keyword" class="block text-sm font-medium text-gray-700 mb-2">ظƒظ„ظ…ط© ط§ظ„ط¨ط­ط«</label>
                        <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" 
                               placeholder="ط§ط¨ط­ط« ط¹ظ† ظ…ط´ط±ظˆط¹ ط£ظˆ ظ…ظ†ط·ظ‚ط©..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">ظ†ظˆط¹ ط§ظ„ط¹ط±ط¶</label>
                        <select id="type" name="type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">ط¬ظ…ظٹط¹ ط§ظ„ط£ظ†ظˆط§ط¹</option>
                            <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>ظ„ظ„ط¨ظٹط¹</option>
                            <option value="rent_monthly" {{ request('type') == 'rent_monthly' ? 'selected' : '' }}>ط¥ظٹط¬ط§ط± ط´ظ‡ط±ظٹ</option>
                            <option value="rent_yearly" {{ request('type') == 'rent_yearly' ? 'selected' : '' }}>ط¥ظٹط¬ط§ط± ط³ظ†ظˆظٹ</option>
                            <option value="rent_daily" {{ request('type') == 'rent_daily' ? 'selected' : '' }}>ط¥ظٹط¬ط§ط± ظٹظˆظ…ظٹ</option>
                        </select>
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">ظپط¦ط© ط§ظ„ظ…ط´ط±ظˆط¹</label>
                        <select id="category" name="category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">ط¬ظ…ظٹط¹ ط§ظ„ظپط¦ط§طھ</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط­ط¯ ط§ظ„ط£ط¯ظ†ظ‰ ظ„ظ„ط³ط¹ط±</label>
                        <input type="number" id="min_price" name="min_price" value="{{ request('min_price') }}" 
                               placeholder="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط­ط¯ ط§ظ„ط£ظ‚طµظ‰ ظ„ظ„ط³ط¹ط±</label>
                        <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" 
                               placeholder="1000000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <!-- Location -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ظ…ط¯ظٹظ†ط©</label>
                        <select id="city" name="city" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">ط¬ظ…ظٹط¹ ط§ظ„ظ…ط¯ظ†</option>
                            @foreach(\App\Models\City::all() as $city)
                                <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط­ظٹ</label>
                        <input type="text" id="district" name="district" value="{{ request('district') }}" 
                               placeholder="ط§ط³ظ… ط§ظ„ط­ظٹ"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط¹ظ†ظˆط§ظ†</label>
                        <input type="text" id="address" name="address" value="{{ request('address') }}" 
                               placeholder="ط§ظ„ط¹ظ†ظˆط§ظ† ط§ظ„طھظپطµظٹظ„ظٹ"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <!-- Property Features -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">ظ…ظˆط§طµظپط§طھ ط§ظ„ظ…ط´ط±ظˆط¹</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-2">ط¹ط¯ط¯ ط§ظ„ط؛ط±ظپ</label>
                            <select id="bedrooms" name="bedrooms" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">ط£ظٹ ط¹ط¯ط¯</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-2">ط¹ط¯ط¯ ط§ظ„ط­ظ…ط§ظ…ط§طھ</label>
                            <select id="bathrooms" name="bathrooms" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">ط£ظٹ ط¹ط¯ط¯</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="area_min" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ظ…ط³ط§ط­ط© (ظ…آ²) - ظ…ظ†</label>
                            <input type="number" id="area_min" name="area_min" value="{{ request('area_min') }}" 
                                   placeholder="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="area_max" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ظ…ط³ط§ط­ط© (ظ…آ²) - ط¥ظ„ظ‰</label>
                            <input type="number" id="area_max" name="area_max" value="{{ request('area_max') }}" 
                                   placeholder="1000"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                </div>

                <!-- Additional Features -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">ظ…ظ…ظٹط²ط§طھ ط¥ط¶ط§ظپظٹط©</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="features[]" value="parking" 
                                   {{ in_array('parking', request('features', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="mr-2 text-sm text-gray-700">ظ…ظˆظ‚ظپ ط³ظٹط§ط±ط§طھ</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="features[]" value="garden" 
                                   {{ in_array('garden', request('features', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="mr-2 text-sm text-gray-700">ط­ط¯ظٹظ‚ط©</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="features[]" value="pool" 
                                   {{ in_array('pool', request('features', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="mr-2 text-sm text-gray-700">ظ…ط³ط¨ط­</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="features[]" value="elevator" 
                                   {{ in_array('elevator', request('features', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="mr-2 text-sm text-gray-700">ظ…طµط¹ط¯</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="features[]" value="balcony" 
                                   {{ in_array('balcony', request('features', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="mr-2 text-sm text-gray-700">ط´ط±ظپط©</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="features[]" value="furnished" 
                                   {{ in_array('furnished', request('features', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="mr-2 text-sm text-gray-700">ظ…ظپط±ظˆط´</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="features[]" value="air_conditioning" 
                                   {{ in_array('air_conditioning', request('features', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="mr-2 text-sm text-gray-700">طھظƒظٹظٹظپ</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="features[]" value="security" 
                                   {{ in_array('security', request('features', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="mr-2 text-sm text-gray-700">ط£ظ…ظ†</span>
                        </label>
                    </div>
                </div>

                <!-- Search Buttons -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <button type="button" onclick="resetForm()" 
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-undo ml-2"></i> ط¥ط¹ط§ط¯ط© طھط¹ظٹظٹظ†
                    </button>
                    <div class="flex space-x-3 space-x-reverse">
                        <button type="submit" 
                                class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors">
                            <i class="fas fa-search ml-2"></i> ط¨ط­ط«
                        </button>
                        <button type="button" onclick="saveSearch()" 
                                class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-save ml-2"></i> ط­ظپط¸ ط§ظ„ط¨ط­ط«
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        @if(request()->hasAny(['keyword', 'type', 'category', 'min_price', 'max_price', 'city', 'district', 'address', 'bedrooms', 'bathrooms', 'area_min', 'area_max', 'features']))
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">ظ†طھط§ط¦ط¬ ط§ظ„ط¨ط­ط«</h2>
                    <p class="text-sm text-gray-600">طھظ… ط§ظ„ط¹ط«ظˆط± ط¹ظ„ظ‰ {{ $offers->count() }} ظ†طھظٹط¬ط©</p>
                </div>
                
                <div class="p-6">
                    @if($offers->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($offers as $offer)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    @if($offer->product->image)
                                        <img src="{{ asset('storage/' . $offer->product->image) }}" 
                                             alt="{{ $offer->product->getTranslatedTitle() }}" 
                                             class="w-full h-48 object-cover rounded-lg mb-4">
                                    @endif
                                    
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-semibold text-gray-900">{{ $offer->product->getTranslatedTitle() }}</h3>
                                        <span class="badge bg-{{ $offer->offer_type == 'sale' ? 'success' : 'info' }}">
                                            @switch($offer->offer_type)
                                                @case('sale') ظ„ظ„ط¨ظٹط¹ @break
                                                @case('rent_monthly') ط¥ظٹط¬ط§ط± ط´ظ‡ط±ظٹ @break
                                                @case('rent_yearly') ط¥ظٹط¬ط§ط± ط³ظ†ظˆظٹ @break
                                                @case('rent_daily') ط¥ظٹط¬ط§ط± ظٹظˆظ…ظٹ @break
                                            @endswitch
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt ml-1"></i> {{ $offer->product->address }}
                                    </p>
                                    
                                    <div class="mb-3">
                                        <h4 class="text-lg font-bold text-primary-600">
                                            <span class="flex items-center">
                                                {{ number_format($offer->price, 2) }}
                                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                            </span>
                                        </h4>
                                        @if($offer->deposit_amount)
                                            <small class="text-muted">
                                                <span class="flex items-center">
                                                    ط§ظ„ط¹ط±ط¨ظˆظ†: {{ number_format($offer->deposit_amount, 2) }}
                                                    <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                                </span>
                                            </small>
                                        @endif
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('client.offers.show', $offer) }}" 
                                           class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                            ط¹ط±ط¶ ط§ظ„طھظپط§طµظٹظ„ <i class="fas fa-arrow-left"></i>
                                        </a>
                                        <button onclick="addToFavorites({{ $offer->id }})" 
                                                class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $offers->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">ظ„ط§ طھظˆط¬ط¯ ظ†طھط§ط¦ط¬</h3>
                            <p class="text-gray-600 mb-6">ط¬ط±ط¨ طھط؛ظٹظٹط± ظ…ط¹ط§ظٹظٹط± ط§ظ„ط¨ط­ط«</p>
                            <button onclick="resetForm()" 
                                    class="bg-primary-600 text-white px-6 py-3 rounded-md hover:bg-primary-700 transition-colors">
                                ط¥ط¹ط§ط¯ط© طھط¹ظٹظٹظ† ط§ظ„ط¨ط­ط«
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function resetForm() {
        document.querySelector('form').reset();
    }

    function saveSearch() {
        // Implementation for saving search criteria
        alert('طھظ… ط­ظپط¸ ظ…ط¹ط§ظٹظٹط± ط§ظ„ط¨ط­ط«');
    }

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
                alert('طھظ… ط¥ط¶ط§ظپط© ط§ظ„ط¹ط±ط¶ ظ„ظ„ظ…ظپط¶ظ„ط©');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
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

.focus\:ring-primary-500:focus {
    --tw-ring-color: #3b82f6;
}

.text-primary-600 {
    color: #3b82f6;
}

.hover\:text-primary-700:hover {
    color: #1d4ed8;
}
</style>
@endpush

