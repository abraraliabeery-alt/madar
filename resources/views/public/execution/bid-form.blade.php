@extends('layouts.app')

@section('title', 'تقديم عرض - طلب التنفيذ #' . $executionRequest->id)

@section('content')
    <header class="bg-slate-900 text-white py-3 shadow-md">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between">
            <a href="{{ route('public.execution.show', $executionRequest) }}" class="flex items-center gap-2 text-xs text-slate-200 hover:text-white">
                <i class="fas fa-arrow-right"></i>
                العودة لتفاصيل الطلب
            </a>
            <div class="text-xs text-slate-200">تقديم عرض</div>
        </div>
    </header>

    <main class="flex-1 bg-slate-50">
        <section class="max-w-6xl mx-auto px-4 py-6 space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                @php
                    $t = $executionRequest->translations->firstWhere('locale', app()->getLocale());
                @endphp
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <div class="text-[11px] text-slate-500">طلب التنفيذ #{{ $executionRequest->id }}</div>
                        <div class="text-lg font-bold text-slate-900">{{ $t->title ?? ('طلب تنفيذ #' . $executionRequest->id) }}</div>
                    </div>
                    <div class="text-[11px] text-slate-500">
                        <span>الحالة: {{ $executionRequest->status }}</span>
                        @if($executionRequest->due_date)
                            <span class="mx-2">|</span>
                            <span>حتى {{ $executionRequest->due_date->format('Y-m-d') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 items-start">
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                        @include('public.execution.partials.bid-form', ['executionRequest' => $executionRequest])
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
