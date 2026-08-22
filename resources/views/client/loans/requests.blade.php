@extends('client.layouts.app')

@section('title', 'ط·ظ„ط¨ط§طھ ط§ظ„طھظ…ظˆظٹظ„')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">ط·ظ„ط¨ط§طھ ط§ظ„طھظ…ظˆظٹظ„</h1>
                <p class="text-gray-600 text-sm mt-1">ظ‚ط¯ظ… ط·ظ„ط¨ طھظ…ظˆظٹظ„ ظ„ظ…ط´ط±ظˆط¹ ظ…ط­ط¯ط¯ ط£ظˆ ط¹ط§ظ…طŒ ظˆطھط§ط¨ط¹ ط­ط§ظ„ط© ط·ظ„ط¨ط§طھظƒ.</p>
            </div>
        </div>

        {{-- ظپظˆط±ظ… ط·ظ„ط¨ طھظ…ظˆظٹظ„ ط¨ط³ظٹط· --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <form action="{{ route('client.loans.requests.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                @csrf
                <div>
                    <label for="product_id" class="block text-xs font-medium text-gray-700 mb-1">ط±ظ‚ظ… ط§ظ„ظ…ط´ط±ظˆط¹ (ط§ط®طھظٹط§ط±ظٹ)</label>
                    <input type="number" name="product_id" id="product_id"
                           class="w-full border-gray-300 rounded-lg text-sm" placeholder="ID ط§ظ„ظ…ط´ط±ظˆط¹ ط¥ظ† ظˆط¬ط¯"
                           value="{{ old('product_id') }}">
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="block text-xs font-medium text-gray-700 mb-1">ظ…ظ„ط§ط­ط¸ط§طھ ظ„ظ„طھظ…ظˆظٹظ„ (ط§ط®طھظٹط§ط±ظٹ)</label>
                    <input type="text" name="notes" id="notes"
                           class="w-full border-gray-300 rounded-lg text-sm" placeholder="ظ…ط«ط§ظ„: ط±ط§طھط¨ظٹ 10,000طŒ ط£ط¨ط­ط« ط¹ظ† طھظ…ظˆظٹظ„ 500,000"
                           value="{{ old('notes') }}">
                </div>
                <div class="md:col-span-3 text-left">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                        <i class="fas fa-paper-plane ml-2"></i>
                        ط¥ط±ط³ط§ظ„ ط·ظ„ط¨ طھظ…ظˆظٹظ„
                    </button>
                </div>
            </form>
        </div>

        {{-- ظپظ„ط§طھط± ط§ظ„ط­ط§ظ„ط§طھ (ط£ط³ظ„ظˆط¨ ظ…ط±ط³ظˆظ„) --}}
        @php
            $statuses = [
                '' => 'ط§ظ„ظƒظ„',
                'new' => 'ط¬ط¯ظٹط¯',
                'dispatched' => 'ظ‚ظٹط¯ ط§ظ„طھظˆط²ظٹط¹',
                'competing' => 'ظپظٹ ط§ظ„ظ…ظ†ط§ظپط³ط©',
                'offers_received' => 'ط¹ط±ظˆط¶ ظ…ط³طھظ„ظ…ط©',
                'selected' => 'طھظ… ط§ط®طھظٹط§ط± ط¹ط±ط¶',
                'advising' => 'ظ‚ظٹط¯ ط§ظ„ط§ط³طھط´ط§ط±ط©',
                'completed' => 'ظ…ظƒطھظ…ظ„',
            ];

            $statusColors = [
                'new' => 'bg-gray-100 text-gray-800 border-gray-200',
                'dispatched' => 'bg-blue-100 text-blue-800 border-blue-200',
                'competing' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'offers_received' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                'selected' => 'bg-green-100 text-green-800 border-green-200',
                'advising' => 'bg-purple-100 text-purple-800 border-purple-200',
                'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            ];

            $currentStatus = request('status', '');
        @endphp

        <div class="mb-4 overflow-x-auto">
            <div class="flex items-center gap-2 text-xs">
                @foreach($statuses as $value => $label)
                    <a href="{{ route('client.loans.requests', array_filter(['status' => $value ?: null])) }}"
                       class="px-3 py-1 rounded-full border transition text-nowrap
                       {{ $currentStatus === $value ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ظ‚ط§ط¦ظ…ط© ط·ظ„ط¨ط§طھ ط§ظ„طھظ…ظˆظٹظ„ ظƒط¨ط·ط§ظ‚ط§طھ --}}
        <div class="space-y-3">
            @if($requests->count())
                @foreach($requests as $loan)
                    @php
                        $colorClass = $statusColors[$loan->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs text-gray-400">#{{ $loan->id }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full border text-[11px] {{ $colorClass }}">
                                    {{ $loan->status }}
                                </span>
                            </div>
                            <div class="text-sm font-semibold text-gray-900 mb-1">
                                @if($loan->product)
                                    {{ $loan->product->title ?? $loan->product->address ?? 'ظ…ط´ط±ظˆط¹ ط¨ط¯ظˆظ† ط¹ظ†ظˆط§ظ†' }}
                                @else
                                    <span class="text-gray-500">ط·ظ„ط¨ طھظ…ظˆظٹظ„ ط¨ط¯ظˆظ† ظ…ط´ط±ظˆط¹ ظ…ط­ط¯ط¯</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-600 mb-1">
                                {{ $loan->notes ? Str::limit($loan->notes, 80) : 'ظ„ط§ طھظˆط¬ط¯ ظ…ظ„ط§ط­ط¸ط§طھ ظ…ط¶ط§ظپط©.' }}
                            </div>
                            <div class="flex items-center justify-between mt-2 text-[11px] text-gray-400">
                                <span>
                                    طھظ… ط§ظ„ط¥ظ†ط´ط§ط، ظپظٹ {{ $loan->created_at ? $loan->created_at->format('Y/m/d H:i') : 'â€”' }}
                                </span>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $loan->offers_count ?? 0 }} ط¹ط±ط¶ طھظ…ظˆظٹظ„
                                    </span>
                                    @if(($loan->offers_count ?? 0) > 0)
                                        <a href="{{ route('client.loans.requests.show', $loan) }}" class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                            ط¹ط±ط¶ ط§ظ„ط¹ط±ظˆط¶
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $requests->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center text-sm text-gray-500">
                    ظ„ط§ طھظˆط¬ط¯ ظ„ط¯ظٹظƒ ط·ظ„ط¨ط§طھ طھظ…ظˆظٹظ„ ط­طھظ‰ ط§ظ„ط¢ظ†.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

