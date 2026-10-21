@extends('facility.layouts.app')

@section('title', 'الجدول الزمني للعقود')

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">الجدول الزمني للعقود</h1>
            <span class="text-muted small">توقّع الإشغال والفراغات للأشهر القادمة</span>
        </div>
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

    <div class="d-flex gap-3 mb-3 flex-wrap small">
        <span><span class="badge bg-success">&nbsp;</span> عقد نشط</span>
        <span><span class="badge bg-warning">&nbsp;</span> ينتهي خلال 60 يوم</span>
        <span><span class="badge" style="background:repeating-linear-gradient(45deg,#dc3545,#dc3545 6px,#f8d7da 6px,#f8d7da 12px)">&nbsp;</span> فراغ (بدون عقد)</span>
        <span class="text-muted">| النافذة: {{ $windowStart->format('m/Y') }} ← {{ $windowEnd->format('m/Y') }}</span>
    </div>

    @if($selected)
        <div class="card shadow-sm">
            <div class="card-body p-0">
                {{-- رأس الشهور --}}
                <div class="d-flex border-bottom small text-muted" dir="ltr">
                    <div style="width:160px;flex-shrink:0" class="ps-2 py-2"></div>
                    @php $m = $windowStart->copy(); @endphp
                    @while($m <= $windowEnd)
                        <div class="py-2 text-center" style="width:{{ 100/13 }}%">{{ $m->format('m/y') }}</div>
                        @php $m->addMonth(); @endphp
                    @endwhile
                </div>

                @forelse($rows as $row)
                    <div class="d-flex align-items-center border-bottom" dir="ltr">
                        <div style="width:160px;flex-shrink:0" class="ps-2 py-2" dir="rtl">
                            <a href="{{ route('facility.products.manage', $row->unit) }}" class="small text-decoration-none text-dark fw-semibold">
                                {{ $row->unit->getTranslatedTitle() ?? '#'.$row->unit->id }}
                            </a>
                        </div>
                        <div class="d-flex flex-grow-1" style="height:34px">
                            @foreach($row->segments as $seg)
                                @if($seg->type === 'contract')
                                    <div class="{{ $seg->expiring ? 'bg-warning' : 'bg-success' }} text-white d-flex align-items-center justify-content-center border-end"
                                         style="width:{{ $seg->width }}%;font-size:.65rem;overflow:hidden;white-space:nowrap"
                                         title="{{ ($seg->contract->tenant?->getTranslatedName() ?? $seg->contract->user?->name ?? '') }} — {{ $seg->contract->start_date->format('Y-m-d') }} ← {{ $seg->contract->end_date->format('Y-m-d') }}">
                                        @if($seg->width > 8)
                                            <span class="text-truncate px-1">{{ $seg->contract->tenant?->getTranslatedName() ?? $seg->contract->user?->name ?? 'عقد' }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div style="width:{{ $seg->width }}%;background:repeating-linear-gradient(45deg,#dc3545,#dc3545 6px,#f8d7da 6px,#f8d7da 12px);opacity:.6" title="فراغ"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-center py-5">هذا الأصل لا يحتوي وحدات</div>
                @endforelse
            </div>
        </div>
    @else
        <div class="alert alert-info">لا توجد أصول بوحدات بعد.</div>
    @endif
</div>
@endsection
