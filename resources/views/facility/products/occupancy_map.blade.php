@extends('facility.layouts.app')

@section('title', 'خريطة الإشغال')

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">خريطة الإشغال</h1>
            <span class="text-muted small">حالة كل وحدة بنظرة واحدة</span>
        </div>
        <a href="{{ route('facility.products.timeline', ['parent_id' => $selected?->id]) }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-chart-gantt me-1"></i> الجدول الزمني
        </a>
        <form method="GET" class="d-flex gap-2">
            <select name="parent_id" class="form-select form-select-sm" style="min-width:220px" onchange="this.form.submit()">
                @foreach($parents as $p)
                    <option value="{{ $p->id }}" @selected($selected && $selected->id === $p->id)>
                        {{ $p->getTranslatedTitle() ?? '#'.$p->id }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- مفتاح الألوان --}}
    <div class="d-flex gap-3 mb-3 flex-wrap small">
        <span><span class="badge bg-success">&nbsp;</span> مشغولة</span>
        <span><span class="badge bg-warning">&nbsp;</span> شاغرة</span>
        <span><span class="badge bg-danger">&nbsp;</span> دفعة متأخرة</span>
        <span><i class="fas fa-screwdriver-wrench text-warning"></i> صيانة مفتوحة</span>
    </div>

    @if($selected)
        @php
            $occ = $tiles->where('state', 'occupied')->count();
            $vac = $tiles->where('state', 'vacant')->count();
            $ovd = $tiles->where('state', 'overdue')->count();
        @endphp
        <div class="d-flex gap-3 mb-3 flex-wrap">
            <span class="badge bg-light text-dark border">{{ $selected->getTranslatedTitle() }}</span>
            <span class="text-muted small">{{ $tiles->count() }} وحدة — {{ $occ }} مشغولة، {{ $vac }} شاغرة، {{ $ovd }} متأخرة</span>
        </div>

        <div class="row g-2">
            @forelse($tiles as $tile)
                @php
                    $colors = [
                        'occupied' => ['bg' => 'bg-success', 'text' => 'text-white'],
                        'vacant' => ['bg' => 'bg-warning', 'text' => 'text-dark'],
                        'overdue' => ['bg' => 'bg-danger', 'text' => 'text-white'],
                    ][$tile->state];
                @endphp
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="{{ route('facility.products.manage', $tile->unit) }}" class="text-decoration-none">
                        <div class="card {{ $colors['bg'] }} {{ $colors['text'] }} shadow-sm h-100" style="min-height:110px">
                            <div class="card-body p-2 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="fw-semibold small">
                                        {{ $tile->unit->getTranslatedTitle() ?? '#'.$tile->unit->id }}
                                    </div>
                                    @if($tile->maintenance)
                                        <i class="fas fa-screwdriver-wrench" title="صيانة مفتوحة"></i>
                                    @endif
                                </div>
                                <div class="mt-auto pt-2" style="font-size:.72rem; opacity:.9">
                                    @if($tile->contract)
                                        <div>{{ $tile->contract->tenant?->getTranslatedName() ?? $tile->contract->user?->name ?? '—' }}</div>
                                        <div>{{ number_format($tile->contract->total_amount, 0) }} ر.س</div>
                                    @else
                                        <div>{{ $tile->unit->price ? number_format($tile->unit->price) . ' ر.س' : 'شاغرة' }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-muted text-center py-5">هذا الأصل لا يحتوي وحدات</div>
            @endforelse
        </div>
    @else
        <div class="alert alert-info">
            لا توجد أصول بوحدات بعد. أنشئ عقاراً (مشروع/عمارة) ثم أضف له وحدات كمنتجات تابعة.
        </div>
    @endif
</div>
@endsection
