@extends('facility.layouts.app')

@section('title', $lead->name)

@section('content')
<div class="container-fluid" style="max-width: 1000px;">
    <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">{{ $lead->name }}</h1>
            <a href="{{ route('facility.leads.index') }}" class="text-muted small">← العودة للعملاء المحتملين</a>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('facility.bookings.create', ['lead_id' => $lead->id]) }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-calendar-check me-1"></i> إنشاء معاينة
            </a>
            <a href="{{ route('facility.contracts.create', ['lead_id' => $lead->id]) }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-file-signature me-1"></i> إنشاء عقد
            </a>
            <a href="{{ route('facility.leads.edit', $lead) }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit me-1"></i> تعديل
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('facility.leads.update-stage', $lead) }}" class="d-flex gap-2 mb-3">
                        @csrf
                        @method('PATCH')
                        <select name="stage" class="form-select form-select-sm">
                            @foreach(\App\Models\Lead::STAGES as $key => $label)
                                <option value="{{ $key }}" @selected($lead->stage === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-primary text-nowrap">تحديث المرحلة</button>
                    </form>

                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr><td class="text-muted">الجوال</td><td dir="ltr" class="text-end">{{ $lead->phone ?? '—' }}</td></tr>
                            <tr><td class="text-muted">البريد</td><td dir="ltr" class="text-end">{{ $lead->email ?? '—' }}</td></tr>
                            <tr><td class="text-muted">المصدر</td><td class="text-end">{{ \App\Models\Lead::SOURCES[$lead->source] ?? '—' }}</td></tr>
                            <tr><td class="text-muted">الاهتمام</td><td class="text-end">{{ $lead->interest_type === 'buy' ? 'شراء' : ($lead->interest_type === 'rent' ? 'إيجار' : '—') }}</td></tr>
                            <tr><td class="text-muted">الميزانية</td><td class="text-end">{{ $lead->budget ? number_format($lead->budget, 2) : '—' }}</td></tr>
                            <tr><td class="text-muted">العقار</td><td class="text-end">
                                @if($lead->product)
                                    <a href="{{ route('facility.products.show', $lead->product) }}">{{ $lead->product->getTranslatedTitle() ?? '#'.$lead->product->id }}</a>
                                @else — @endif
                            </td></tr>
                            <tr><td class="text-muted">المسؤول</td><td class="text-end">{{ $lead->assignedTo->name ?? '—' }}</td></tr>
                            <tr><td class="text-muted">أضيف</td><td class="text-end">{{ $lead->created_at->format('Y-m-d') }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if($lead->stage === 'won')
                <div class="card shadow-sm border-success">
                    <div class="card-body text-center">
                        <div class="text-success fw-semibold mb-2"><i class="fas fa-trophy me-1"></i> عميل مغلق بنجاح</div>
                        <a href="{{ route('facility.contracts.create', ['lead_id' => $lead->id]) }}" class="btn btn-sm btn-success">إنشاء عقد</a>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light fw-semibold">تسجيل نشاط / متابعة</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('facility.leads.activities.store', $lead) }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="type" class="form-select form-select-sm">
                                    @foreach(\App\Models\LeadActivity::TYPES as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <input type="datetime-local" name="follow_up_at" class="form-control form-control-sm" title="موعد المتابعة القادم (اختياري)">
                            </div>
                            <div class="col-12">
                                <textarea name="body" class="form-control form-control-sm" rows="2" placeholder="ماذا حدث؟ ماذا قال العميل؟"></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i> تسجيل</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light fw-semibold">سجل النشاطات</div>
                <div class="card-body">
                    @forelse($lead->activities as $activity)
                        <div class="d-flex gap-2 mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0">
                                <span class="badge bg-{{ $activity->type === 'call' ? 'info' : ($activity->type === 'meeting' ? 'primary' : ($activity->type === 'follow_up' ? 'warning text-dark' : 'secondary')) }}">
                                    {{ $activity->typeLabel() }}
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small">{{ $activity->body }}</div>
                                <div class="text-muted" style="font-size:.7rem">
                                    {{ $activity->user->name ?? 'النظام' }} — {{ $activity->created_at->diffForHumans() }}
                                    @if($activity->follow_up_at)
                                        <span class="badge {{ $activity->follow_up_at->isPast() ? 'bg-danger' : 'bg-light text-dark border' }} ms-1">
                                            متابعة: {{ $activity->follow_up_at->format('Y-m-d H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted text-center py-3">لا نشاطات بعد — سجّل أول تواصل</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
