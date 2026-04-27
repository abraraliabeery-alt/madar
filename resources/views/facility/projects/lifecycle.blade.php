@extends('facility.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 mb-1">
                {{ optional($project->translations->firstWhere('locale', app()->getLocale()))->name ?? ('#'.$project->id) }}
            </h1>
            <p class="text-sm text-gray-500">{{ __('facility.project_lifecycle') }}</p>
        </div>
        <a href="{{ route('facility.projects.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            {{ __('facility.back_to_projects') }}
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">عدد العقارات في هذا المشروع</p>
            <p class="text-2xl font-semibold text-gray-800">{{ $projectsStats['total_products'] ?? 0 }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">إجمالي العروض</p>
            <p class="text-xl font-semibold text-indigo-600">{{ $projectsStats['total_offers'] ?? 0 }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">إجمالي الحجوزات</p>
            <p class="text-xl font-semibold text-emerald-600">{{ $projectsStats['total_bookings'] ?? 0 }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">إجمالي العقود</p>
            <p class="text-xl font-semibold text-amber-600">{{ $projectsStats['total_contracts'] ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <ol class="relative border-s border-gray-200 dark:border-gray-700">
            @foreach($project->stages as $stage)
                @php
                    $isCompleted = $stage->status === 'completed';
                    $isCurrent = $stage->status === 'in_progress';
                    $translation = $stage->translations->firstWhere('locale', app()->getLocale());
                    $aiStatus = session('ai_'.$stage->key.'_status');
                    $aiContent = session('ai_'.$stage->key.'_content');
                @endphp
                <li class="mb-10 ms-6">
                    <span class="absolute flex items-center justify-center w-6 h-6 rounded-full -start-3 ring-8 ring-white {{ $isCompleted ? 'bg-emerald-500' : ($isCurrent ? 'bg-indigo-500' : 'bg-gray-300') }}">
                        @if($isCompleted)
                            <span class="text-white text-xs">✓</span>
                        @elseif($isCurrent)
                            <span class="text-white text-xs">•</span>
                        @else
                            <span class="text-white text-xs"></span>
                        @endif
                    </span>
                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">
                        {{ $translation->name ?? ucfirst(str_replace('_', ' ', $stage->key)) }}
                        @if($isCurrent)
                            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium ms-3 px-2.5 py-0.5 rounded">{{ __('facility.current_stage') }}</span>
                        @elseif($isCompleted)
                            <span class="bg-emerald-100 text-emerald-800 text-xs font-medium ms-3 px-2.5 py-0.5 rounded">{{ __('facility.completed_stage') }}</span>
                        @endif
                    </h3>
                    @if($translation && $translation->description)
                        <p class="mb-2 text-sm text-gray-500">{{ $translation->description }}</p>
                    @endif

                    <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">{{ __('facility.stage_status') }}</p>
                            <p class="text-sm font-medium text-gray-800">{{ $stage->status }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">{{ __('facility.stage_started_at') }}</p>
                            <p class="text-sm text-gray-700">{{ optional($stage->started_at)->format('Y-m-d H:i') ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">{{ __('facility.stage_completed_at') }}</p>
                            <p class="text-sm text-gray-700">{{ optional($stage->completed_at)->format('Y-m-d H:i') ?? '—' }}</p>
                        </div>
                    </div>

                    @if(in_array($stage->key, ['feasibility','design']))
                        <div class="mt-4 bg-gray-50 rounded-lg p-4 border border-dashed border-gray-200">
                            <form action="{{ route('facility.projects.lifecycle.ai', $project) }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="stage_key" value="{{ $stage->key }}">
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    ملاحظاتك أو أسئلتك لهذه المرحلة (اختياري)
                                </label>
                                <textarea name="notes" rows="3" class="w-full text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="اكتب مثلاً: أحتاج تحليل جدوى سريع بناءً على نوع المشروع والميزانية المتوقعة..."></textarea>
                                <div class="flex items-center justify-between">
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-wand-magic-sparkles ml-1 text-xs"></i>
                                        توليد توصيات ذكية لهذه المرحلة
                                    </button>
                                    @if($aiStatus)
                                        <span class="text-[11px] text-gray-400">
                                            حالة الذكاء الاصطناعي: {{ $aiStatus }}
                                        </span>
                                    @endif
                                </div>
                            </form>

                            @if($aiContent)
                                <div class="mt-3 text-sm leading-relaxed text-gray-800 whitespace-pre-line">
                                    {{ $aiContent }}
                                </div>
                            @endif
                        </div>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</div>
@endsection
