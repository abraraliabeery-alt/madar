@extends('client.layouts.app')

@section('title', 'طھظپط§طµظٹظ„ ط·ظ„ط¨ ط§ظ„طھظ…ظˆظٹظ„')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">ط·ظ„ط¨ طھظ…ظˆظٹظ„ #{{ $loanRequest->id }}</h1>
                <p class="text-gray-600 text-sm mt-1">طھظپط§طµظٹظ„ ط§ظ„ط·ظ„ط¨ ظˆط§ظ„ط¹ط±ظˆط¶ ط§ظ„ظ…ظ‚ط¯ظ…ط© ظ…ظ† ظ…ظˆط¸ظپظٹ ط§ظ„ط¨ظ†ظˆظƒ.</p>
            </div>
            <a href="{{ route('client.loans.requests') }}" class="text-sm text-blue-600 hover:text-blue-700">ط¹ظˆط¯ط© ظ„ط·ظ„ط¨ط§طھ ط§ظ„طھظ…ظˆظٹظ„</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm font-semibold text-gray-900">
                    @if($loanRequest->product)
                        {{ $loanRequest->product->title ?? $loanRequest->product->address ?? 'ظ…ط´ط±ظˆط¹ ط¨ط¯ظˆظ† ط¹ظ†ظˆط§ظ†' }}
                    @else
                        ط·ظ„ط¨ طھظ…ظˆظٹظ„ ط¨ط¯ظˆظ† ظ…ط´ط±ظˆط¹ ظ…ط­ط¯ط¯
                    @endif
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 border border-gray-200 text-[11px]">
                    {{ $loanRequest->status }}
                </span>
            </div>
            <div class="text-xs text-gray-600 mb-1">
                ظ…ظ„ط§ط­ط¸ط§طھظƒ: {{ $loanRequest->notes ?: 'ظ„ط§ طھظˆط¬ط¯ ظ…ظ„ط§ط­ط¸ط§طھ ظ…ط¶ط§ظپط©.' }}
            </div>
            <div class="text-[11px] text-gray-400">
                طھظ… ط§ظ„ط¥ظ†ط´ط§ط، ظپظٹ {{ $loanRequest->created_at ? $loanRequest->created_at->format('Y/m/d H:i') : 'â€”' }}
            </div>
        </div>

        @if($contract)
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-4 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <div class="text-sm font-semibold text-gray-900 mb-1">ط§ظ„ط¹ظ‚ط¯ ط§ظ„ظ…ظˆظ„ط¯ ظ…ظ† ظ‡ط°ط§ ط§ظ„طھظ…ظˆظٹظ„</div>
                        <div class="text-xs text-gray-600">
                            ط±ظ‚ظ… ط§ظ„ط¹ظ‚ط¯: <span class="font-mono">{{ $contract->contract_number ?? 'ط³ظٹطھظ… طھظˆظ„ظٹط¯ظ‡' }}</span>
                        </div>
                        <div class="text-xs text-gray-600">
                            ظ‚ظٹظ…ط© ط§ظ„ط¹ظ‚ط¯: <span class="font-semibold">{{ number_format($contract->total_amount, 0) }} ط±.ط³</span>
                        </div>
                        <div class="text-[11px] text-gray-500 mt-1">
                            ط§ظ„ط­ط§ظ„ط© ط§ظ„ط­ط§ظ„ظٹط©: {{ $contract->status ?? 'draft' }}
                        </div>
                    </div>
                    <div class="text-xs text-right">
                        <a href="{{ route('client.contracts.show', $contract) }}" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded">
                            ط¹ط±ط¶ طھظپط§طµظٹظ„ ط§ظ„ط¹ظ‚ط¯
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">ط§ظ„ط¹ط±ظˆط¶ ط§ظ„ظ…ظ‚ط¯ظ…ط©</h2>

            @if($loanRequest->offers->count())
                <div class="space-y-3">
                    @foreach($loanRequest->offers as $offer)
                        <div class="border border-gray-200 rounded-lg p-3 flex items-start justify-between gap-4 @if($loanRequest->chosen_offer_id === $offer->id) bg-green-50 @else bg-gray-50 @endif">
                            <div class="flex-1 text-xs text-gray-800">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold">ظ…ط¨ظ„ط؛ ط§ظ„طھظ…ظˆظٹظ„:</span>
                                    <span>{{ number_format($offer->amount, 0) }} ط±.ط³</span>
                                </div>
                                <div class="text-gray-700 mb-1">
                                    ط§ظ„ظ†ط³ط¨ط©: {{ $offer->profit_rate }}% | ط§ظ„ظ…ط¯ط©: {{ $offer->term_months }} ط´ظ‡ط±
                                </div>
                                <div class="text-gray-700 mb-1">
                                    ظ‚ط³ط· طھظ‚ط±ظٹط¨ظٹ: {{ number_format($offer->monthly_payment, 0) }} ط±.ط³
                                </div>
                                @if($offer->fees)
                                    <div class="text-gray-700 mb-1">
                                        ط±ط³ظˆظ… ط¥ط¶ط§ظپظٹط©: {{ number_format($offer->fees, 0) }} ط±.ط³
                                    </div>
                                @endif
                                <div class="text-gray-500 mb-1">
                                    ظ…ظˆط¸ظپ ط§ظ„ط¨ظ†ظƒ: {{ $offer->banker->name ?? 'ط؛ظٹط± ظ…ط¹ط±ظˆظپ' }}
                                </div>
                                @if($offer->notes)
                                    <div class="text-gray-600">ظ…ظ„ط§ط­ط¸ط§طھ ط§ظ„ط¨ظ†ظƒ: {{ $offer->notes }}</div>
                                @endif
                            </div>
                            <div class="text-xs text-right min-w-[120px] flex flex-col items-end gap-2">
                                @if($loanRequest->chosen_offer_id === $offer->id)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-600 text-white text-[11px]">ط§ظ„ط¹ط±ط¶ ط§ظ„ظ…ط®طھط§ط±</span>
                                @else
                                    <form action="{{ route('client.loans.offers.choose', [$loanRequest, $offer]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-[11px]">
                                            ط§ط®طھظٹط§ط± ظ‡ط°ط§ ط§ظ„ط¹ط±ط¶
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">ظ„ظ… ظٹطھظ… طھظ‚ط¯ظٹظ… ط£ظٹ ط¹ط±ظˆط¶ طھظ…ظˆظٹظ„ ط¹ظ„ظ‰ ظ‡ط°ط§ ط§ظ„ط·ظ„ط¨ ط­طھظ‰ ط§ظ„ط¢ظ†.</p>
            @endif
        </div>
    </div>
</div>
@endsection

