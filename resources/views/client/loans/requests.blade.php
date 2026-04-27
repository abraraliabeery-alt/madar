@extends('layouts.app')

@section('title', 'طلبات التمويل')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">طلبات التمويل</h1>
                <p class="text-gray-600 text-sm mt-1">قدم طلب تمويل لعقار محدد أو عام، وتابع حالة طلباتك.</p>
            </div>
        </div>

        {{-- فورم طلب تمويل بسيط --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <form action="{{ route('client.loans.requests.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                @csrf
                <div>
                    <label for="product_id" class="block text-xs font-medium text-gray-700 mb-1">رقم العقار (اختياري)</label>
                    <input type="number" name="product_id" id="product_id"
                           class="w-full border-gray-300 rounded-lg text-sm" placeholder="ID العقار إن وجد"
                           value="{{ old('product_id') }}">
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="block text-xs font-medium text-gray-700 mb-1">ملاحظات للتمويل (اختياري)</label>
                    <input type="text" name="notes" id="notes"
                           class="w-full border-gray-300 rounded-lg text-sm" placeholder="مثال: راتبي 10,000، أبحث عن تمويل 500,000"
                           value="{{ old('notes') }}">
                </div>
                <div class="md:col-span-3 text-left">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                        <i class="fas fa-paper-plane ml-2"></i>
                        إرسال طلب تمويل
                    </button>
                </div>
            </form>
        </div>

        {{-- فلاتر الحالات (أسلوب مرسول) --}}
        @php
            $statuses = [
                '' => 'الكل',
                'new' => 'جديد',
                'dispatched' => 'قيد التوزيع',
                'competing' => 'في المنافسة',
                'offers_received' => 'عروض مستلمة',
                'selected' => 'تم اختيار عرض',
                'advising' => 'قيد الاستشارة',
                'completed' => 'مكتمل',
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

        {{-- قائمة طلبات التمويل كبطاقات --}}
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
                                    {{ $loan->product->title ?? $loan->product->address ?? 'عقار بدون عنوان' }}
                                @else
                                    <span class="text-gray-500">طلب تمويل بدون عقار محدد</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-600 mb-1">
                                {{ $loan->notes ? Str::limit($loan->notes, 80) : 'لا توجد ملاحظات مضافة.' }}
                            </div>
                            <div class="flex items-center justify-between mt-2 text-[11px] text-gray-400">
                                <span>
                                    تم الإنشاء في {{ $loan->created_at ? $loan->created_at->format('Y/m/d H:i') : '—' }}
                                </span>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $loan->offers_count ?? 0 }} عرض تمويل
                                    </span>
                                    @if(($loan->offers_count ?? 0) > 0)
                                        <a href="{{ route('client.loans.requests.show', $loan) }}" class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                            عرض العروض
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
                    لا توجد لديك طلبات تمويل حتى الآن.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
