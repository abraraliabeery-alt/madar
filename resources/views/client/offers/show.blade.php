@extends('client.layouts.app')

@section('title', $offer->product->getTranslatedTitle())

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- ط§ظ„ظ…ط­طھظˆظ‰ ط§ظ„ط±ط¦ظٹط³ظٹ -->
            <div class="lg:col-span-2 space-y-6">
                <!-- طµظˆط± ط§ظ„ظ…ظ†طھط¬ -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    @if($offer->product->image)
                        <img src="{{ asset('storage/' . $offer->product->image) }}" 
                             class="w-full h-96 object-cover" alt="طµظˆط±ط© ط§ظ„ظ…ط´ط±ظˆط¹">
                    @else
                        <div class="flex items-center justify-center bg-gray-100 h-96">
                            <div class="text-center">
                                <i class="fas fa-image text-6xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">ظ„ط§ طھظˆط¬ط¯ طµظˆط±ط© ظ…طھط§ط­ط©</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- طھظپط§طµظٹظ„ ط§ظ„ط¹ط±ط¶ -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $offer->product->getTranslatedTitle() }}</h1>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                            {{ $offer->offer_type == 'sale' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            @switch($offer->offer_type)
                                @case('sale') ط¨ظٹط¹ @break
                                @case('rent_monthly') ط¥ظٹط¬ط§ط± ط´ظ‡ط±ظٹ @break
                                @case('rent_yearly') ط¥ظٹط¬ط§ط± ط³ظ†ظˆظٹ @break
                                @case('rent_daily') ط¥ظٹط¬ط§ط± ظٹظˆظ…ظٹ @break
                            @endswitch
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">طھظپط§طµظٹظ„ ط§ظ„ظ…ط´ط±ظˆط¹</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">ط§ظ„ط¹ظ†ظˆط§ظ†:</span>
                                    <span class="text-gray-900 text-right">{{ $offer->product->address }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">ط§ظ„ظˆطµظپ:</span>
                                    <span class="text-gray-900 text-right">{{ $offer->product->getTranslatedDescription() }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">ط§ظ„ظ…ظ†ط´ط£ط©:</span>
                                    <span class="text-gray-900 text-right">{{ $offer->facility->name ?? 'ط؛ظٹط± ظ…ط­ط¯ط¯' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">طھظپط§طµظٹظ„ ط§ظ„ط¹ط±ط¶</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">ط§ظ„ط³ط¹ط±:</span>
                                    <div class="flex items-center text-blue-600 text-lg font-bold">
                                        {{ number_format($offer->price, 2) }}
                                        <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-5 h-5 mr-1">
                                    </div>
                                </div>
                                @if($offer->deposit_amount)
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="font-medium text-gray-700">ط§ظ„ط¹ط±ط¨ظˆظ†:</span>
                                        <div class="flex items-center text-gray-900">
                                            {{ number_format($offer->deposit_amount, 2) }}
                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                        </div>
                                    </div>
                                @endif
                                @if($offer->commission_amount)
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="font-medium text-gray-700">ط§ظ„ط¹ظ…ظˆظ„ط©:</span>
                                        <div class="flex items-center text-gray-900">
                                            {{ number_format($offer->commission_amount, 2) }}
                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                        </div>
                                    </div>
                                @endif
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">طµط§ظ„ط­ ظ…ظ†:</span>
                                    <span class="text-gray-900">{{ $offer->valid_from ? $offer->valid_from->format('Y-m-d') : 'ط؛ظٹط± ظ…ط­ط¯ط¯' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="font-medium text-gray-700">طµط§ظ„ط­ ط­طھظ‰:</span>
                                    <span class="text-gray-900">{{ $offer->valid_to ? $offer->valid_to->format('Y-m-d') : 'ط؛ظٹط± ظ…ط­ط¯ط¯' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($offer->getTranslatedTerms())
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">ط§ظ„ط´ط±ظˆط· ظˆط§ظ„ط£ط­ظƒط§ظ…</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-gray-700 whitespace-pre-line">{!! nl2br(e($offer->getTranslatedTerms())) !!}</div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- ط§ظ„ط¹ط±ظˆط¶ ط§ظ„ظ…ط´ط§ط¨ظ‡ط© -->
                @if($similarOffers->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">ط¹ط±ظˆط¶ ظ…ط´ط§ط¨ظ‡ط©</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($similarOffers as $similarOffer)
                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                    @if($similarOffer->product->image)
                                        <img src="{{ asset('storage/' . $similarOffer->product->image) }}" 
                                             class="w-full h-32 object-cover" alt="طµظˆط±ط© ط§ظ„ظ…ط´ط±ظˆط¹">
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
                                                ط¹ط±ط¶
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- ط§ظ„ط´ط±ظٹط· ط§ظ„ط¬ط§ظ†ط¨ظٹ -->
            <div class="space-y-6">
                <!-- ظ…ط¹ظ„ظˆظ…ط§طھ ط§ظ„ط§طھطµط§ظ„ -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ظ…ط¹ظ„ظˆظ…ط§طھ ط§ظ„ط§طھطµط§ظ„</h3>
                    <div class="space-y-3">
                        <button class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" 
                                x-data @click="$dispatch('open-modal', 'contactModal')">
                            <i class="fas fa-phone ml-2"></i>
                            ط·ظ„ط¨ ظ…ط¹ظ„ظˆظ…ط§طھ
                        </button>
                        <button class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" 
                                x-data @click="$dispatch('open-modal', 'visitModal')">
                            <i class="fas fa-calendar ml-2"></i>
                            ط­ط¬ط² ظ…ظˆط¹ط¯ ط²ظٹط§ط±ط©
                        </button>
                        @auth
                            <button class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors add-to-favorites" 
                                    data-offer-id="{{ $offer->id }}">
                                <i class="fas fa-heart ml-2"></i>
                                ط¥ط¶ط§ظپط© ظ„ظ„ظ…ظپط¶ظ„ط©
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-heart ml-2"></i>
                                ط¥ط¶ط§ظپط© ظ„ظ„ظ…ظپط¶ظ„ط©
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- ظ…ط¹ظ„ظˆظ…ط§طھ ط§ظ„ظ…ظ†ط´ط£ط© -->
                @if($offer->facility)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">ط§ظ„ظ…ظ†ط´ط£ط©</h3>
                        <div class="text-center">
                            @if($offer->facility->logo)
                                <img src="{{ asset('storage/' . $offer->facility->logo) }}" 
                                     class="rounded-full mx-auto mb-4 w-20 h-20 object-cover" alt="ط´ط¹ط§ط± ط§ظ„ظ…ظ†ط´ط£ط©">
                            @endif
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $offer->facility->name }}</h4>
                            <p class="text-gray-500 text-sm mb-4">{{ $offer->facility->description ?? 'ظ„ط§ ظٹظˆط¬ط¯ ظˆطµظپ' }}</p>
                            <a href="{{ route('facility.site.home', $offer->facility->slug ?? $offer->facility->id) }}" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                ط¹ط±ط¶ ط§ظ„ظ…ظ†ط´ط£ط©
                            </a>
                        </div>
                    </div>
                @endif

                <!-- ط¥ط­طµط§ط¦ظٹط§طھ ط§ظ„ط¹ط±ط¶ -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ط¥ط­طµط§ط¦ظٹط§طھ ط§ظ„ط¹ط±ط¶</h3>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="border-r border-gray-200">
                            <div class="text-2xl font-bold text-blue-600">{{ $offer->product->views_count ?? 0 }}</div>
                            <div class="text-sm text-gray-500">ط§ظ„ظ…ط´ط§ظ‡ط¯ط§طھ</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-yellow-600">{{ $offer->product->rating ?? 0 }}</div>
                            <div class="text-sm text-gray-500">ط§ظ„طھظ‚ظٹظٹظ…</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ط·ظ„ط¨ ظ…ط¹ظ„ظˆظ…ط§طھ -->
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
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">ط·ظ„ط¨ ظ…ط¹ظ„ظˆظ…ط§طھ</h3>
                        <form id="contactForm">
                            <div class="space-y-4">
                                <div>
                                    <label for="contact_name" class="block text-sm font-medium text-gray-700">ط§ظ„ط§ط³ظ… <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="contact_name" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700">ط§ظ„ط¨ط±ظٹط¯ ط§ظ„ط¥ظ„ظƒطھط±ظˆظ†ظٹ <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" id="contact_email" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700">ط±ظ‚ظ… ط§ظ„ظ‡ط§طھظپ <span class="text-red-500">*</span></label>
                                    <input type="tel" name="phone" id="contact_phone" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="contact_message" class="block text-sm font-medium text-gray-700">ط§ظ„ط±ط³ط§ظ„ط©</label>
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
                    ط¥ط±ط³ط§ظ„
                </button>
                <button type="button" @click="show = false" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    ط¥ظ„ط؛ط§ط،
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal ط­ط¬ط² ظ…ظˆط¹ط¯ ط²ظٹط§ط±ط© -->
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
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">ط­ط¬ط² ظ…ظˆط¹ط¯ ط²ظٹط§ط±ط©</h3>
                        <form id="visitForm">
                            <div class="space-y-4">
                                <div>
                                    <label for="visit_date" class="block text-sm font-medium text-gray-700">طھط§ط±ظٹط® ط§ظ„ط²ظٹط§ط±ط© <span class="text-red-500">*</span></label>
                                    <input type="date" name="visit_date" id="visit_date" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="visit_time" class="block text-sm font-medium text-gray-700">ظˆظ‚طھ ط§ظ„ط²ظٹط§ط±ط© <span class="text-red-500">*</span></label>
                                    <input type="time" name="visit_time" id="visit_time" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="visit_notes" class="block text-sm font-medium text-gray-700">ظ…ظ„ط§ط­ط¸ط§طھ</label>
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
                    ط­ط¬ط² ط§ظ„ظ…ظˆط¹ط¯
                </button>
                <button type="button" @click="show = false" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    ط¥ظ„ط؛ط§ط،
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ط¥ط¶ط§ظپط© ظ„ظ„ظ…ظپط¶ظ„ط©
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
                this.innerHTML = '<i class="fas fa-heart text-red-500 ml-2"></i> طھظ…طھ ط§ظ„ط¥ط¶ط§ظپط©';
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
                alert('طھظ… ط¥ط±ط³ط§ظ„ ط·ظ„ط¨ظƒ ط¨ظ†ط¬ط§ط­. ط³ظ†طھظˆط§طµظ„ ظ…ط¹ظƒ ظ‚ط±ظٹط¨ط§ظ‹.');
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
                alert('طھظ… ط­ط¬ط² ظ…ظˆط¹ط¯ ط§ظ„ط²ظٹط§ط±ط© ط¨ظ†ط¬ط§ط­');
                document.getElementById('visitForm').reset();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // طھط¹ظٹظٹظ† ط§ظ„طھط§ط±ظٹط® ط§ظ„ط­ط§ظ„ظٹ ظƒط§ظپطھط±ط§ط¶ظٹ
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('visit_date').value = today;
    });
</script>
@endpush
