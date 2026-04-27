@extends('layouts.app')

@section('title', 'طلبات التمويل - موظف البنك')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">طلبات التمويل</h1>
                <p class="text-gray-600 text-sm mt-1">عرض طلبات التمويل وإرسال عروض تمويل من حساب موظف البنك الحالي.</p>
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

        {{-- فلاتر الحالات لموظف البنك --}}
        <div class="mb-4 overflow-x-auto">
            <div class="flex items-center gap-2 text-xs">
                @foreach($statuses as $value => $label)
                    <a href="{{ route('bank.loans.requests', array_filter(['status' => $value ?: null])) }}"
                       class="px-3 py-1 rounded-full border transition text-nowrap
                       {{ $currentStatus === $value ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- قائمة الطلبات كبطاقات مع عروضي ونموذج عرض جديد --}}
        <div class="space-y-3">
            @if($requests->count())
                @foreach($requests as $loan)
                    @php
                        $colorClass = $statusColors[$loan->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-start justify-between gap-4">
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
                                    العميل: <span class="font-medium text-gray-800">{{ $loan->user->name ?? 'غير معروف' }}</span>
                                </div>
                                <div class="text-[11px] text-gray-400 mt-1">
                                    تم إنشاء الطلب في {{ $loan->created_at ? $loan->created_at->format('Y/m/d H:i') : '—' }}
                                </div>
                            </div>
                            <div class="w-56 text-xs">
                                <div class="mb-1 font-semibold text-gray-700">عروضي على هذا الطلب</div>
                                @if($loan->offers->count())
                                    <div class="space-y-1 text-[11px] text-gray-700 max-h-32 overflow-y-auto">
                                        @foreach($loan->offers as $offer)
                                            <div class="border border-gray-200 rounded px-2 py-1 bg-gray-50">
                                                <div>مبلغ: {{ number_format($offer->amount, 0) }} ر.س</div>
                                                <div>نسبة: {{ $offer->profit_rate }}% | مدة: {{ $offer->term_months }} شهر</div>
                                                <div>قسط تقريبي: {{ number_format($offer->monthly_payment, 0) }} ر.س</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-gray-400 text-[11px]">لا توجد عروض منك بعد</div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 border-t border-gray-100 pt-3">
                            <form action="{{ route('bank.loans.offers.store', $loan) }}" method="POST" class="space-y-2 text-[11px]">
                                @csrf
                                <div class="grid grid-cols-3 gap-2">
                                    <input type="number" name="amount" step="0.01" min="0" placeholder="المبلغ" class="border-gray-300 rounded px-2 py-1">
                                    <input type="number" name="profit_rate" step="0.01" min="0" placeholder="% الربح" class="border-gray-300 rounded px-2 py-1">
                                    <input type="number" name="term_months" min="1" placeholder="الأشهر" class="border-gray-300 rounded px-2 py-1">
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="fees" step="0.01" min="0" placeholder="الرسوم (اختياري)" class="border-gray-300 rounded px-2 py-1">
                                    <input type="text" name="notes" placeholder="ملاحظات (اختياري)" class="border-gray-300 rounded px-2 py-1">
                                </div>
                                <div class="text-left">
                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-[11px]">
                                        إرسال عرض تمويل
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $requests->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center text-sm text-gray-500">
                    لا توجد طلبات تمويل متاحة حاليًا.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
