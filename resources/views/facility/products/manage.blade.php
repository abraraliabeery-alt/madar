@extends('facility.layouts.app')

@section('title', 'إدارة: ' . ($product->getTranslatedTitle() ?? '#'.$product->id))

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="w-full max-w-7xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
            <a href="{{ route('facility.products.index') }}" class="hover:text-blue-600">المنتجات</a>
            @foreach($ancestors as $ancestor)
                <i class="fas fa-chevron-left text-[10px]"></i>
                <a href="{{ route('facility.products.manage', $ancestor) }}" class="hover:text-blue-600">
                    {{ $ancestor->getTranslatedTitle() ?? ('#'.$ancestor->id) }}
                </a>
            @endforeach
            <i class="fas fa-chevron-left text-[10px]"></i>
            <span class="text-gray-800 font-medium">{{ $product->getTranslatedTitle() ?? ('#'.$product->id) }}</span>
        </div>

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">{{ $product->getTranslatedTitle() ?? ('#'.$product->id) }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $product->category?->getTranslatedName() ?? 'بدون تصنيف' }}
                    @if($product->parent)
                        — يتبع: {{ $product->parent->getTranslatedTitle() ?? ('#'.$product->parent->id) }}
                    @endif
                </p>
            </div>
            <div class="flex gap-2 mt-4 sm:mt-0">
                <a href="{{ route('facility.products.show', $product) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    <i class="fas fa-eye ml-1"></i>صفحة المنتج
                </a>
                <a href="{{ route('facility.products.edit', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    <i class="fas fa-edit ml-1"></i>تعديل
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if($expiringContracts->isNotEmpty() || $overdueContracts->isNotEmpty())
            <div class="space-y-2 mb-6">
                @foreach($overdueContracts as $contract)
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3">
                        <i class="fas fa-exclamation-circle"></i>
                        <span class="text-sm">
                            دفعة متأخرة: عقد {{ $contract->contract_number ?? ('#'.$contract->id) }}
                            ({{ $contract->tenant?->getTranslatedName() ?? $contract->user?->name ?? '—' }})
                            — كان مستحقاً {{ $contract->next_payment_date->format('Y-m-d') }}
                        </span>
                        <a href="{{ route('facility.contracts.show', $contract) }}" class="mr-auto text-xs underline">عرض العقد</a>
                    </div>
                @endforeach
                @foreach($expiringContracts as $contract)
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg flex items-center gap-3">
                        <i class="fas fa-clock"></i>
                        <span class="text-sm">
                            عقد يقترب انتهاؤه: {{ $contract->contract_number ?? ('#'.$contract->id) }}
                            — ينتهي {{ $contract->end_date->format('Y-m-d') }} ({{ $contract->end_date->diffForHumans() }})
                        </span>
                        <a href="{{ route('facility.contracts.show', $contract) }}" class="mr-auto text-xs underline">عرض العقد</a>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $stats['children'] }}</div>
                <div class="text-xs text-gray-500 mt-1">عناصر مباشرة</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $stats['descendants'] }}</div>
                <div class="text-xs text-gray-500 mt-1">إجمالي التابعين</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold {{ $stats['occupancy'] >= 70 ? 'text-emerald-600' : ($stats['occupancy'] >= 40 ? 'text-yellow-600' : 'text-red-600') }}">{{ $stats['occupancy'] }}%</div>
                <div class="text-xs text-gray-500 mt-1">نسبة الإشغال</div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $stats['occupancy'] }}%"></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['active_contracts'] }}</div>
                <div class="text-xs text-gray-500 mt-1">عقود نشطة</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-gray-800">{{ number_format($stats['contracts_value'], 0) }}</div>
                <div class="text-xs text-gray-500 mt-1">قيمة العقود النشطة</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-emerald-600">{{ number_format($stats['collected'], 0) }}</div>
                <div class="text-xs text-gray-500 mt-1">إجمالي المحصّل</div>
            </div>
        </div>

        {{-- الربح والخسارة --}}
        <div class="bg-white rounded-lg shadow p-5 mb-6">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold text-gray-800 mb-0"><i class="fas fa-scale-balanced ml-1 text-gray-400"></i>الربح والخسارة</h4>
                <span class="text-xs text-gray-400">يشمل الأصل وكل التابعين</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-lg font-bold text-emerald-600">{{ number_format($stats['collected'], 2) }}</div>
                    <div class="text-xs text-gray-500">إيرادات (محصّل)</div>
                </div>
                <div>
                    <div class="text-lg font-bold text-red-500">-{{ number_format($stats['maintenance_cost'], 2) }}</div>
                    <div class="text-xs text-gray-500">مصاريف صيانة</div>
                </div>
                <div>
                    <div class="text-lg font-bold text-red-500">-{{ number_format($stats['commissions'], 2) }}</div>
                    <div class="text-xs text-gray-500">عمولات</div>
                </div>
                <div class="border-r-2 border-gray-100 pr-4">
                    <div class="text-xl font-bold {{ $stats['net_profit'] >= 0 ? 'text-emerald-700' : 'text-red-600' }}">{{ number_format($stats['net_profit'], 2) }}</div>
                    <div class="text-xs font-semibold {{ $stats['net_profit'] >= 0 ? 'text-emerald-600' : 'text-red-500' }}">صافي {{ $stats['net_profit'] >= 0 ? 'الربح' : 'الخسارة' }}</div>
                </div>
            </div>
        </div>

        @if($marketIndex)
            <div class="bg-gradient-to-l from-indigo-50 to-white rounded-lg shadow p-4 mb-6 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <div class="text-sm font-semibold text-indigo-800">
                        <i class="fas fa-chart-line ml-1"></i>مؤشر السوق — {{ $product->city?->getTranslatedName() ?? 'المدينة' }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        متوسط الإيجار السنوي: <b>{{ number_format($marketIndex->avg_rent_yearly, 0) }}</b> ريال
                        @if($marketIndex->avg_sale_sqm) — متوسط متر البيع: <b>{{ number_format($marketIndex->avg_sale_sqm, 0) }}</b> ريال @endif
                        <span class="text-gray-400">({{ $marketIndex->period }})</span>
                    </div>
                </div>
                @if($marketDiff !== null)
                    <div class="text-left">
                        <div class="text-xl font-bold {{ $marketDiff > 15 ? 'text-red-600' : ($marketDiff < -15 ? 'text-emerald-600' : 'text-gray-700') }}">
                            {{ $marketDiff > 0 ? '+' : '' }}{{ $marketDiff }}%
                        </div>
                        <div class="text-xs font-semibold {{ $marketDiff > 15 ? 'text-red-500' : ($marketDiff < -15 ? 'text-emerald-600' : 'text-gray-500') }}">
                            @if($marketDiff > 15) سعرك أعلى من السوق — قد يبطئ الإيجار
                            @elseif($marketDiff < -15) سعرك أقل من السوق — فرصة ربحية
                            @else ضمن متوسط السوق ✓
                            @endif
                        </div>
                    </div>
                @else
                    <span class="text-xs text-gray-400">أضف عرضاً بسعر لتفعيل المقارنة</span>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Children / Units --}}
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg flex justify-between items-center">
                    <h4 class="text-lg font-semibold text-gray-800 mb-0">العناصر التابعة</h4>
                    <a href="{{ route('facility.products.create', ['parent_id' => $product->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs transition-colors">
                        <i class="fas fa-plus ml-1"></i>إضافة عنصر
                    </a>
                </div>
                <div class="p-4">
                    @if($product->children->isNotEmpty())
                        <div class="divide-y divide-gray-100">
                            @foreach($product->children as $child)
                                @php($childContract = $childContracts->get($child->id))
                                <div class="flex items-center justify-between py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-cube text-gray-400"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('facility.products.manage', $child) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600">
                                                {{ $child->getTranslatedTitle() ?? ('#'.$child->id) }}
                                            </a>
                                            <div class="text-xs text-gray-500">{{ $child->category?->getTranslatedName() ?? 'بدون تصنيف' }}</div>
                                            @if($childContract)
                                                <div class="text-xs text-gray-500 mt-0.5">
                                                    <i class="fas fa-user ml-1"></i>{{ $childContract->tenant?->getTranslatedName() ?? $childContract->user?->name ?? '—' }}
                                                    <span class="mx-1">·</span>{{ number_format($childContract->total_amount ?? 0, 0) }} ر.س
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($occupiedProductIds->contains($child->id))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-emerald-50 text-emerald-700">مشغول</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-500">شاغر</span>
                                        @endif
                                        <a href="{{ route('facility.products.show', $child) }}" class="text-gray-400 hover:text-blue-600 text-sm" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <i class="fas fa-cubes text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500 text-sm">لا توجد عناصر تابعة بعد</p>
                            <a href="{{ route('facility.products.create', ['parent_id' => $product->id]) }}" class="inline-flex items-center mt-3 text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-plus ml-1"></i>أضف أول عنصر
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Contracts --}}
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg flex justify-between items-center">
                    <h4 class="text-lg font-semibold text-gray-800 mb-0">العقود</h4>
                    <a href="{{ route('facility.contracts.index') }}" class="text-blue-600 hover:text-blue-800 text-xs">كل العقود</a>
                </div>
                <div class="p-4">
                    @if($contracts->isNotEmpty())
                        <div class="divide-y divide-gray-100">
                            @foreach($contracts->take(10) as $contract)
                                <div class="flex items-center justify-between py-3">
                                    <div>
                                        <a href="{{ route('facility.contracts.show', $contract) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600">
                                            {{ $contract->contract_number ?? ('عقد #'.$contract->id) }}
                                        </a>
                                        <div class="text-xs text-gray-500">
                                            {{ $contract->tenant?->getTranslatedName() ?? $contract->user?->name ?? '—' }}
                                            — {{ $contract->product?->getTranslatedTitle() ?? '—' }}
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <div class="text-sm font-semibold text-gray-800">{{ number_format($contract->total_amount ?? 0, 0) }} ر.س</div>
                                        @php
                                            $statusColors = ['active' => 'bg-emerald-50 text-emerald-700', 'completed' => 'bg-blue-50 text-blue-700', 'cancelled' => 'bg-red-50 text-red-700', 'draft' => 'bg-gray-100 text-gray-600'];
                                            $statusLabels = ['active' => 'نشط', 'completed' => 'مكتمل', 'cancelled' => 'ملغي', 'draft' => 'مسودة'];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs {{ $statusColors[$contract->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $statusLabels[$contract->status] ?? $contract->status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <i class="fas fa-file-signature text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500 text-sm">لا توجد عقود مرتبطة بهذا الأصل أو وحداته</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Payments + Open Maintenance --}}
        @if($recentPayments->isNotEmpty() || $maintenanceRequests->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                @if($recentPayments->isNotEmpty())
                    <div class="bg-white rounded-lg shadow-lg">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                            <h4 class="text-lg font-semibold text-gray-800 mb-0">آخر التحصيلات</h4>
                        </div>
                        <div class="p-4">
                            <div class="divide-y divide-gray-100">
                                @foreach($recentPayments as $payment)
                                    <div class="flex items-center justify-between py-3">
                                        <div>
                                            <span class="text-sm font-medium text-gray-800">{{ number_format($payment->amount ?? 0, 0) }} ر.س</span>
                                            <div class="text-xs text-gray-500">
                                                {{ $payment->contract?->contract_number ?? ('عقد #'.$payment->contract_id) }}
                                                — {{ $payment->contract?->tenant?->getTranslatedName() ?? $payment->contract?->user?->name ?? '—' }}
                                            </div>
                                        </div>
                                        <div class="text-left">
                                            <div class="text-xs text-gray-500">{{ $payment->payment_date?->format('Y-m-d') ?? $payment->created_at?->format('Y-m-d') }}</div>
                                            @if($payment->payment_method)
                                                <span class="text-xs text-gray-400">{{ $payment->payment_method }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if($maintenanceRequests->isNotEmpty())
                    <div class="bg-white rounded-lg shadow-lg">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg flex justify-between items-center">
                            <h4 class="text-lg font-semibold text-gray-800 mb-0">طلبات الصيانة المفتوحة</h4>
                            <a href="{{ route('facility.maintenance.index', ['product_id' => $product->id]) }}" class="text-blue-600 hover:text-blue-800 text-xs">الكل</a>
                        </div>
                        <div class="p-4">
                            <div class="divide-y divide-gray-100">
                                @foreach($maintenanceRequests as $req)
                                    @php($priorityColors = ['low' => 'bg-gray-100 text-gray-600', 'medium' => 'bg-blue-50 text-blue-700', 'high' => 'bg-orange-50 text-orange-700', 'urgent' => 'bg-red-50 text-red-700'])
                                    <div class="flex items-center justify-between py-3">
                                        <div>
                                            <a href="{{ route('facility.maintenance.edit', $req) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600">{{ $req->title }}</a>
                                            <div class="text-xs text-gray-500">
                                                {{ $req->product?->getTranslatedTitle() ?? '—' }}
                                                · {{ __('facility.maintenance.statuses.' . $req->status) }}
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs {{ $priorityColors[$req->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ __('facility.maintenance.priorities.' . $req->priority) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
