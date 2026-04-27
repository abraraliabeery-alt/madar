@extends('facility.layouts.app')

@section('title', 'طلبات التمويل')
@php
    use App\Models\Contract;
@endphp

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">طلبات التمويل لعقارات المنشأة</h1>
        <p class="text-gray-600 mt-1 text-sm">عرض الطلبات التي قدّمها العملاء على العقارات التابعة لهذه المنشأة، مع حالة كل طلب وعدد العروض البنكية.</p>
    </div>

    {{-- إحصائيات سريعة --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">إجمالي الطلبات</div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow border border-blue-200 p-4">
            <div class="text-xs text-gray-500 mb-1">طلبات جديدة/موزعة</div>
            <div class="text-xs text-gray-500">جديدة: {{ $stats['new'] ?? 0 }} / موزعة: {{ $stats['dispatched'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow border border-yellow-200 p-4">
            <div class="text-xs text-gray-500 mb-1">في المنافسة / عروض مستلمة</div>
            <div class="text-xs text-gray-500">منافسة: {{ $stats['competing'] ?? 0 }} / عروض: {{ $stats['offers_received'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow border border-green-200 p-4">
            <div class="text-xs text-gray-500 mb-1">مختارة / مكتملة</div>
            <div class="text-xs text-gray-500">مختارة: {{ $stats['selected'] ?? 0 }} / مكتملة: {{ $stats['completed'] ?? 0 }}</div>
        </div>
    </div>

    @php
        $statuses = [
            '' => 'كل الطلبات',
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

    {{-- فلاتر الحالات (أسلوب مرسول) --}}
    <div class="mb-4 overflow-x-auto">
        <div class="flex items-center gap-2 text-xs">
            @foreach($statuses as $value => $label)
                <a href="{{ route('facility.loans.requests', array_filter(['status' => $value ?: null])) }}"
                   class="px-3 py-1 rounded-full border transition text-nowrap
                   {{ $currentStatus === $value ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- قائمة الطلبات كبطاقات لكل طلب --}}
    <div class="space-y-3">
        @if($loanRequests->count())
            @foreach($loanRequests as $request)
                @php
                    $colorClass = $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                @endphp
                <div class="bg-white rounded-lg shadow border border-gray-200 p-4 flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs text-gray-400">#{{ $request->id }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full border text-[11px] {{ $colorClass }}">
                                {{ $request->status }}
                            </span>
                        </div>
                        <div class="text-sm font-semibold text-gray-900 mb-1 flex items-center gap-2">
                            @if($request->product)
                                <a href="{{ route('facility.products.edit', $request->product) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $request->product->title ?? $request->product->address ?? 'عقار بدون عنوان' }}
                                </a>
                            @else
                                <span class="text-gray-500">طلب تمويل بدون عقار محدد</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-600 mb-1">
                            العميل: <span class="font-medium text-gray-800">{{ $request->user->name ?? 'غير محدد' }}</span>
                        </div>
                        <div class="text-xs text-gray-600 mb-1">
                            المستشار: <span class="font-medium">{{ $request->advisor ? $request->advisor->name : 'غير معين' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-gray-400 mt-1">
                            <span>
                                تم الإنشاء في {{ $request->created_at ? $request->created_at->format('Y/m/d H:i') : '—' }}
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $request->offers_count ?? 0 }} عرض تمويل
                                </span>
                                <a href="{{ route('facility.loans.requests.show', $request) }}" class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                    عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 text-xs text-right min-w-[140px] space-y-2">
                        <div>
                            <div class="mb-1 font-semibold text-gray-700">العرض المختار</div>
                            @if($request->chosenOffer)
                                <div class="text-green-700">{{ number_format($request->chosenOffer->amount, 0) }} ر.س</div>
                                <div class="text-[11px] text-gray-500">نسبة: {{ $request->chosenOffer->profit_rate }}% لمدة {{ $request->chosenOffer->term_months }} شهر</div>
                            @else
                                <div class="text-gray-400">لم يتم اختيار عرض بعد</div>
                            @endif
                        </div>

                        @php
                            $contract = $request->product_id
                                ? Contract::where('product_id', $request->product_id)
                                    ->where('user_id', $request->user_id)
                                    ->latest()
                                    ->first()
                                : null;
                        @endphp

                        <div class="pt-2 border-t border-gray-100">
                            @if($contract)
                                <div class="text-[11px] text-gray-700 mb-1">العقد الناتج عن هذا التمويل:</div>
                                <div class="text-[11px] text-gray-600 mb-1">
                                    رقم العقد: <span class="font-mono">{{ $contract->contract_number ?? '—' }}</span>
                                </div>
                                <a href="{{ route('facility.contracts.edit', $contract->id ?? $contract) }}" class="inline-flex items-center px-2 py-0.5 rounded bg-green-600 hover:bg-green-700 text-white text-[11px]">
                                    عرض / تعديل العقد
                                </a>
                            @else
                                <div class="text-[11px] text-gray-400">لا يوجد عقد منشأ بعد لهذا الطلب.</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-4">
                {{ $loanRequests->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6 text-center text-sm text-gray-500">
                لا توجد طلبات تمويل مرتبطة بعقارات هذه المنشأة حتى الآن.
            </div>
        @endif
    </div>
@endsection
