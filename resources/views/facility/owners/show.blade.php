@extends('facility.layouts.app')

@section('title', 'كشف حساب: ' . $owner->name)

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">{{ $owner->name }}</h1>
                <div class="text-sm text-gray-500 mt-1">
                    {{ $owner->is_company ? 'شركة' : 'فرد' }}
                    @if($owner->phone) — {{ $owner->phone }}@endif
                    @if($owner->email) — {{ $owner->email }}@endif
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('facility.owners.statement-pdf', $owner) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-file-pdf"></i> تصدير PDF
                </a>
                <button type="button" onclick="navigator.clipboard.writeText(@js(URL::signedRoute('owner-portal.show', ['owner' => $owner->id]))).then(() => this.innerHTML = '<i class=\'fas fa-check\'></i> تم النسخ!')"
                        class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-lg text-sm" title="رابط موقّع يفتح كشف حساب المالك بدون تسجيل دخول">
                    <i class="fas fa-link"></i> رابط المالك
                </button>
                <a href="{{ route('facility.owners.edit', $owner) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">تعديل</a>
                <a href="{{ route('facility.owners.index') }}" class="border border-gray-300 px-4 py-2 rounded-lg text-sm text-gray-700">رجوع</a>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-xs text-gray-500">العقارات</div>
                <div class="text-xl font-bold">{{ $totals['properties'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-xs text-gray-500">العقود</div>
                <div class="text-xl font-bold">{{ $totals['contracts'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-xs text-gray-500">عقود نشطة</div>
                <div class="text-xl font-bold text-blue-600">{{ $totals['active_contracts'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-xs text-gray-500">قيمة العقود</div>
                <div class="text-xl font-bold">{{ number_format($totals['contracts_value'], 2) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-xs text-gray-500">المحصّل</div>
                <div class="text-xl font-bold text-emerald-600">{{ number_format($totals['collected'], 2) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center {{ $totals['owner_due'] >= 0 ? 'ring-1 ring-emerald-200' : 'ring-1 ring-red-200' }}">
                <div class="text-xs text-gray-500">مستحق المالك (بعد عمولة {{ $owner->commission_rate !== null ? $owner->commission_rate.'%' : 'المنشأة' }})</div>
                <div class="text-xl font-bold {{ $totals['owner_due'] >= 0 ? 'text-emerald-700' : 'text-red-600' }}">{{ number_format($totals['owner_due'], 2) }}</div>
            </div>
        </div>

        {{-- تسوية جديدة للمالك --}}
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="bg-gray-50 px-4 py-3 border-b font-semibold text-gray-800 flex justify-between items-center">
                <span>تسجيل تسوية / دفعة للمالك</span>
                <span class="text-xs text-gray-500">سُدّد له سابقاً: {{ number_format($totals['paid_to_owner'], 2) }}</span>
            </div>
            <form method="POST" action="{{ route('facility.owners.payouts.store', $owner) }}" class="p-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">المبلغ *</label>
                        <input type="number" name="amount" step="0.01" min="0.01" required
                               max="{{ max($totals['owner_due'], 0) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="{{ number_format(max($totals['owner_due'],0), 2) }}">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">تاريخ السداد *</label>
                        <input type="date" name="paid_at" required value="{{ now()->toDateString() }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">طريقة الدفع</label>
                        <input type="text" name="payment_method" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="تحويل / نقد / شيك">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">ملاحظات</label>
                        <input type="text" name="notes" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-hand-holding-dollar me-1"></i> تسجيل التسوية
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow">
                <div class="bg-gray-50 px-4 py-3 border-b font-semibold text-gray-800">عقاراته ({{ $owner->products->count() }})</div>
                <div class="divide-y">
                    @forelse($owner->products as $product)
                        <a href="{{ route('facility.products.manage', $product) }}" class="flex justify-between items-center px-4 py-3 hover:bg-gray-50">
                            <span class="text-sm text-gray-800">{{ $product->getTranslatedTitle() ?? '#'.$product->id }}</span>
                            <span class="text-xs text-gray-500">{{ $product->category?->getTranslatedName() ?? '' }}</span>
                        </a>
                    @empty
                        <div class="px-4 py-6 text-center text-gray-500 text-sm">لا عقارات مرتبطة</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-lg shadow lg:col-span-2">
                <div class="bg-gray-50 px-4 py-3 border-b font-semibold text-gray-800">العقود ({{ $contracts->count() }})</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-right text-xs text-gray-500">العقد</th>
                                <th class="px-4 py-2 text-right text-xs text-gray-500">العقار</th>
                                <th class="px-4 py-2 text-right text-xs text-gray-500">المستأجر/العميل</th>
                                <th class="px-4 py-2 text-right text-xs text-gray-500">القيمة</th>
                                <th class="px-4 py-2 text-right text-xs text-gray-500">المحصّل</th>
                                <th class="px-4 py-2 text-right text-xs text-gray-500">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($contracts as $contract)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('facility.contracts.show', $contract) }}" class="text-blue-600 hover:underline">
                                            {{ $contract->contract_number ?? '#'.$contract->id }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $contract->product?->getTranslatedTitle() ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $contract->tenant?->getTranslatedName() ?? $contract->user?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ number_format($contract->total_amount, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-emerald-700">{{ number_format($contract->payments_sum_amount ?? 0, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs {{ $contract->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $contract->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500 text-sm">لا عقود</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- سجل التسويات --}}
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="bg-gray-50 px-4 py-3 border-b font-semibold text-gray-800">سجل التسويات ({{ $payouts->count() }})</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">التاريخ</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">المبلغ</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">الطريقة</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">ملاحظات</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">سجّله</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($payouts as $payout)
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $payout->paid_at?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-emerald-700">{{ number_format($payout->amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm">{{ $payout->payment_method ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $payout->notes ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $payout->creator?->name ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500 text-sm">لا تسويات مسجلة</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
