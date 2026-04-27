@extends('facility.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h1 class="text-2xl font-semibold text-gray-800">مساحة عمل التنفيذ</h1>
                @if($facility->isExecutionEligible())
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i class="fas fa-shield-check ml-1 text-[10px]"></i>
                        منشأة منفِّذة
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] bg-amber-50 text-amber-700 border border-amber-200">
                        <i class="fas fa-circle-exclamation ml-1 text-[10px]"></i>
                        غير مفعَّلة كمنفِّذ
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500">كل ما يخص طلبات التنفيذ والعروض في صفحة واحدة، كأنها تطبيق مستقل داخل لوحة المنشأة.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('facility.execution-requests.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                <i class="fas fa-plus ml-2"></i>
                طلب تنفيذ تفصيلي
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white shadow-sm rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500 mb-1">إجمالي طلبات التنفيذ</div>
                <div class="text-2xl font-semibold text-gray-800">{{ $stats['total_requests'] ?? 0 }}</div>
            </div>
            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i class="fas fa-gavel"></i>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500 mb-1">طلبات مفتوحة</div>
                <div class="text-2xl font-semibold text-emerald-600">{{ $stats['open_requests'] ?? 0 }}</div>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500 mb-1">طلبات منتهية/مغلقة</div>
                <div class="text-2xl font-semibold text-gray-800">{{ $stats['closed_requests'] ?? 0 }}</div>
            </div>
            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500 mb-1">إجمالي العروض المقدَّمة</div>
                <div class="text-2xl font-semibold text-amber-600">{{ $stats['total_bids'] ?? 0 }}</div>
            </div>
            <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white shadow-sm rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-gavel text-indigo-600"></i>
                        آخر طلبات التنفيذ
                    </h2>
                </div>

                <div class="space-y-3">
                    @forelse($recentRequests as $request)
                        @php
                            $translation = $request->translations->firstWhere('locale', app()->getLocale());
                        @endphp
                        <div class="border border-gray-100 rounded-lg p-4 hover:border-indigo-200 hover:bg-indigo-50/40 transition flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="space-y-1">
                                <div class="text-sm font-medium text-gray-800">
                                    <a href="{{ route('facility.execution-requests.show', $request) }}" class="hover:text-indigo-700">
                                        {{ $translation->title ?? ('#'.$request->id) }}
                                    </a>
                                </div>
                                <div class="text-xs text-gray-500 flex flex-wrap items-center gap-2">
                                    @if($request->type)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-[11px]">
                                            <i class="fas fa-tag ml-1 text-[10px]"></i>{{ $request->type }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium
                                        @if($request->priority === 'high') bg-red-100 text-red-700
                                        @elseif($request->priority === 'low') bg-gray-100 text-gray-600
                                        @else bg-amber-100 text-amber-700 @endif">
                                        {{ $request->priority ?? 'normal' }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 text-[11px]">
                                        {{ $request->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                <div class="flex flex-col items-end">
                                    <span class="text-gray-500 mb-1">العروض</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[11px]">
                                        <i class="fas fa-users ml-1 text-[10px]"></i>
                                        {{ $request->bids_count }} عرض
                                    </span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-gray-500 mb-1">الحالة</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium
                                        @if($request->status === 'open') bg-emerald-50 text-emerald-700
                                        @elseif(in_array($request->status, ['completed','closed'])) bg-gray-100 text-gray-700
                                        @elseif($request->status === 'cancelled') bg-red-50 text-red-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ $request->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">لا توجد طلبات تنفيذ بعد.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-xl p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-user-helmet-safety text-indigo-600"></i>
                    عروضي كمنفِّذ
                </h2>
                <div class="space-y-3">
                    @forelse($myExecutorBids as $bid)
                        @php
                            $req = $bid->executionRequest;
                            $translation = $req?->translations->firstWhere('locale', app()->getLocale());
                        @endphp
                        <div class="border border-gray-100 rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="space-y-1 text-sm">
                                <div class="text-gray-800 font-medium">
                                    <a href="{{ $req ? route('facility.execution-requests.show', $req) : '#' }}" class="hover:text-indigo-700">
                                        {{ $translation->title ?? ('طلب #'.optional($req)->id) }}
                                    </a>
                                </div>
                                <div class="text-xs text-gray-500 flex flex-wrap items-center gap-2">
                                    <span>المبلغ: {{ $bid->price_total ? number_format($bid->price_total) . ' ' . ($bid->currency ?? 'SAR') : 'غير محدد' }}</span>
                                    <span>المدة: {{ $bid->duration_days ? $bid->duration_days.' يوم' : 'غير محددة' }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end text-xs">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium
                                    @if($bid->status === 'accepted') bg-emerald-100 text-emerald-700
                                    @elseif($bid->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ $bid->status }}
                                </span>
                                <span class="text-gray-400 mt-1">{{ $bid->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">لم تقم بتقديم أي عروض كمنفِّذ حتى الآن.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white shadow-sm rounded-xl p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-indigo-600"></i>
                    طلب تنفيذ سريع
                </h2>

                <form method="POST" action="{{ route('facility.execution-requests.store') }}" class="space-y-4">
                    @csrf

                    @php($locales = config('locales.available'))
                    <div>
                        <div class="border-b border-gray-200 mb-3 flex flex-wrap gap-2 text-xs">
                            @foreach($locales as $code => $locale)
                                <button type="button" data-locale-tab="quick-{{ $code }}" class="quick-locale-tab px-3 py-1.5 rounded-t-md border-b-2 transition-all
                                    {{ $loop->first ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                                    {{ $locale['native'] ?? strtoupper($code) }}
                                </button>
                            @endforeach
                        </div>

                        @foreach($locales as $code => $locale)
                            <div data-locale-panel="quick-{{ $code }}" class="quick-locale-panel {{ $loop->first ? '' : 'hidden' }}">
                                <input type="hidden" name="translations[{{ $loop->index }}][locale]" value="{{ $code }}">
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        العنوان ({{ $locale['native'] ?? strtoupper($code) }})
                                    </label>
                                    <input type="text" name="translations[{{ $loop->index }}][title]" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs" {{ $loop->first ? 'required' : '' }}>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        الوصف ({{ $locale['native'] ?? strtoupper($code) }})
                                    </label>
                                    <textarea name="translations[{{ $loop->index }}][description]" rows="3" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs" placeholder="وصف مختصر لما يجب على المنفِّذ تنفيذه في هذه اللغة"></textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">النوع (اختياري)</label>
                            <input type="text" name="type" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs" placeholder="مثال: مقاولات، صيانة، تصميم">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">الأولوية</label>
                            <select name="priority" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs">
                                <option value="normal" selected>عادية</option>
                                <option value="high">مرتفعة</option>
                                <option value="low">منخفضة</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">الميزانية الدنيا (اختياري)</label>
                            <input type="number" step="0.01" name="budget_min" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">الميزانية القصوى (اختياري)</label>
                            <input type="number" step="0.01" name="budget_max" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">تاريخ مستهدف (اختياري)</label>
                        <input type="date" name="due_date" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-xs">
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700">
                            <i class="fas fa-paper-plane ml-1 text-[11px]"></i>
                            نشر الطلب الآن
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm rounded-xl p-4 text-xs text-gray-500">
                <div class="flex items-start gap-2">
                    <i class="fas fa-circle-info mt-0.5 text-indigo-500"></i>
                    <p>
                        هذه الصفحة مصممة لتكون مساحة عمل متكاملة لنظام التنفيذ: يمكنك من هنا إنشاء طلبات جديدة، متابعة حالة الطلبات الحالية،
                        ومراقبة العروض المقدَّمة من المنفِّذين، وكل ذلك في واجهة واحدة.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quickTabs = document.querySelectorAll('.quick-locale-tab');
        const quickPanels = document.querySelectorAll('.quick-locale-panel');

        quickTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const code = tab.getAttribute('data-locale-tab');

                quickTabs.forEach(t => t.classList.remove('border-indigo-500', 'text-indigo-600', 'bg-indigo-50'));
                quickTabs.forEach(t => t.classList.add('border-transparent', 'text-gray-500'));

                tab.classList.add('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
                tab.classList.remove('border-transparent', 'text-gray-500');

                quickPanels.forEach(panel => {
                    panel.classList.toggle('hidden', panel.getAttribute('data-locale-panel') !== code);
                });
            });
        });
    });
</script>
@endpush
