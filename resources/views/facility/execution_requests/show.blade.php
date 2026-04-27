@extends('facility.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            @php
                $translation = $executionRequest->translations->firstWhere('locale', app()->getLocale());
            @endphp
            <h1 class="text-2xl font-semibold text-gray-800 mb-1">
                {{ $translation->title ?? ('#'.$executionRequest->id) }}
            </h1>
            <p class="text-sm text-gray-500">إدارة العروض المقدَّمة على هذا الطلب.</p>
        </div>
        <a href="{{ route('facility.execution-requests.index') }}" class="text-sm text-gray-500 hover:text-gray-700">عودة لطلبات التنفيذ</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white shadow-sm rounded-lg p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">بيانات الطلب</h2>
                <div class="space-y-2 text-sm text-gray-700">
                    @if($translation && $translation->description)
                        <p class="whitespace-pre-line">{{ $translation->description }}</p>
                    @else
                        <p class="text-gray-400 text-xs">لا يوجد وصف مفصّل.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-gavel ml-2 text-indigo-600"></i>
                    العروض المقدَّمة
                </h2>

                <div class="space-y-3">
                    @forelse($executionRequest->bids as $bid)
                        <div class="border border-gray-100 rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="space-y-1 text-sm">
                                <div class="text-gray-800 font-medium">
                                    {{ optional($bid->executorUser)->name ?? 'منفِّذ مجهول' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    المبلغ الكلي: {{ $bid->price_total ? number_format($bid->price_total) . ' ' . ($bid->currency ?? 'SAR') : 'غير محدد' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    المدة: {{ $bid->duration_days ? $bid->duration_days . ' يوم' : 'غير محددة' }}
                                    • الضمان: {{ $bid->warranty_months ? $bid->warranty_months . ' شهر' : 'غير محدد' }}
                                </div>
                                @if(isset($bid->data['notes']) && $bid->data['notes'])
                                    <div class="text-xs text-gray-600 mt-1 whitespace-pre-line">{{ $bid->data['notes'] }}</div>
                                @endif
                            </div>
                            <div class="mt-3 md:mt-0 flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium
                                    @if($bid->status === 'accepted') bg-emerald-100 text-emerald-700
                                    @elseif($bid->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ $bid->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">لا توجد عروض حتى الآن.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white shadow-sm rounded-lg p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">إضافة عرض منفِّذ</h2>
                <form method="POST" action="{{ route('facility.execution-requests.bids.store', $executionRequest) }}" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">المبلغ الكلي (اختياري)</label>
                        <input type="number" step="0.01" name="price_total" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">المدة (أيام)</label>
                            <input type="number" name="duration_days" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">الضمان (أشهر)</label>
                            <input type="number" name="warranty_months" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">ملاحظات العرض (اختياري)</label>
                        <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="ملخص ما يشمله هذا العرض، شروط خاصة، مراحل التنفيذ..."></textarea>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700">
                            حفظ العرض
                        </button>
                    </div>
                </form>
                @error('execution')
                    <p class="mt-3 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>
@endsection
