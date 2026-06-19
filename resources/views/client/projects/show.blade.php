@extends('layouts.dashboard-bs')

@section('title', 'تفاصيل المشروع')

@section('content')
    @php
        $t = $project->translations->firstWhere('locale', app()->getLocale())
            ?? $project->translations->first();
        $imageUrl = $project->image ? asset('storage/' . $project->image) : null;
    @endphp

    <x-bs.card title="تفاصيل المشروع">
        <x-slot name="actions">
            <a href="{{ route('client.dashboard') }}" class="btn btn-light btn-sm">رجوع</a>
        </x-slot>

        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">صورة المشروع</div>
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="صورة المشروع" class="img-fluid rounded border">
                        @else
                            <div class="text-muted">لا توجد صورة مرفوعة.</div>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">الموقع</div>
                        <div class="small text-muted">
                            <div>المدينة: {{ $project->city?->localized_name ?? '-' }}</div>
                            <div>الحي: {{ $project->neighborhood?->name ?? '-' }}</div>
                            <div>الشارع: {{ $project->street?->name ?? '-' }}</div>
                            <div>العنوان: {{ $project->address ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">اسم المشروع</div>
                        <div class="h5 mb-3">{{ $t->name ?? ('مشروع رقم ' . $project->id) }}</div>

                        @if(!empty($t?->description))
                            <div class="fw-semibold mb-1">وصف المشروع</div>
                            <div class="text-muted" style="white-space: pre-line;">{{ $t->description }}</div>
                            <hr>
                        @endif

                        <div class="row g-3 small">
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">حالة النشر</div>
                                <div class="text-muted">{{ $project->status ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">آخر موعد لاستلام العروض</div>
                                <div class="text-muted">{{ $project->bid_deadline?->format('Y-m-d') ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">الميزانية الدنيا</div>
                                <div class="text-muted">{{ $project->budget_min !== null ? number_format($project->budget_min) : '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">الميزانية القصوى</div>
                                <div class="text-muted">{{ $project->budget_max !== null ? number_format($project->budget_max) : '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">تاريخ البدء المتوقع</div>
                                <div class="text-muted">{{ $project->start_date?->format('Y-m-d') ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">مدة التنفيذ (بالأيام)</div>
                                <div class="text-muted">{{ $project->duration_days ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">مرفقات المشروع</div>

                        @if($project->attachmentsFiles->count())
                            <div class="list-group">
                                @foreach($project->attachmentsFiles as $file)
                                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                       href="{{ asset('storage/' . $file->path) }}" target="_blank" rel="noopener">
                                        <span>{{ $file->original_name ?? 'ملف مرفق' }}</span>
                                        <span class="text-muted small">تحميل</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted">لا توجد ملفات مرفوعة.</div>
                        @endif

                        @if(is_array($project->attachments) && count($project->attachments))
                            <hr>
                            <div class="fw-semibold mb-2">روابط/أسماء مرفقات (نصية)</div>
                            <ul class="mb-0">
                                @foreach($project->attachments as $item)
                                    @if(!empty($item))
                                        <li class="text-muted">{{ $item }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">المتطلبات</div>
                        @if($project->requirements)
                            <div class="text-muted" style="white-space: pre-line;">{{ $project->requirements }}</div>
                        @else
                            <div class="text-muted">لا توجد متطلبات مكتوبة.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-bs.card>
@endsection
