@extends('facility.layouts.app')

@section('title', __('facility.dashboard.title'))

@push('styles')
<style>
    .dash-hero {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 55%, #084298 100%);
        border-radius: 1rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .dash-hero::before {
        content: '';
        position: absolute;
        inset-inline-end: -60px;
        top: -60px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }
    .dash-hero::after {
        content: '';
        position: absolute;
        inset-inline-end: 40px;
        bottom: -80px;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
    }
    .dash-hero .hero-num { font-size: 2.6rem; font-weight: 800; letter-spacing: -.5px; line-height: 1; }
    .dash-hero .hero-sub { font-size: .85rem; opacity: .8; }
    .dash-hero .delta { font-size: .8rem; font-weight: 700; padding: .25rem .6rem; border-radius: 999px; background: rgba(255,255,255,.18); }
    .kpi-mini {
        background: rgba(255,255,255,.1);
        border-radius: .75rem;
        padding: .8rem 1rem;
        backdrop-filter: blur(4px);
    }
    .kpi-mini .v { font-size: 1.15rem; font-weight: 800; }
    .kpi-mini .l { font-size: .72rem; opacity: .75; }
    .stat-card {
        border: none;
        border-radius: .9rem;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,.08); }
    .stat-card .icon-wrap {
        width: 42px; height: 42px; border-radius: .7rem;
        display: flex; align-items: center; justify-content: center; font-size: 1.05rem;
    }
    .stat-card .v { font-size: 1.4rem; font-weight: 800; line-height: 1.1; }
    .stat-card .l { font-size: .78rem; color: var(--bs-secondary-color); }
    .chart-box { position: relative; height: 240px; }
    .feed-item { display: flex; align-items: flex-start; gap: .7rem; padding: .55rem 0; }
    .feed-item + .feed-item { border-top: 1px solid var(--bs-border-color-translucent); }
    .feed-icon {
        width: 32px; height: 32px; border-radius: .55rem; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: .8rem;
    }
    .progress-thin { height: 10px; border-radius: 999px; }
    .agenda-item { display: flex; align-items: center; gap: .6rem; padding: .45rem 0; }
    .agenda-item + .agenda-item { border-top: 1px dashed var(--bs-border-color-translucent); }
    .agenda-time { min-width: 62px; text-align: center; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h1 class="h4 fw-bold mb-0">{{ __('facility.dashboard.title') }}</h1>
        @if($facility)
            <span class="badge bg-primary-subtle text-primary px-3 py-2 fs-6">{{ $facility->name }}</span>
        @endif
    </div>

    {{-- تنبيهات عاجلة --}}
    @php $receivables = $stats['receivables_due'] ?? 0; @endphp
    @if(($stats['overdue_contracts'] ?? 0) > 0 || $receivables > 0)
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 border-0 shadow-sm">
            <i class="fas fa-exclamation-circle fs-5"></i>
            <span>
                @if(($stats['overdue_contracts'] ?? 0) > 0)
                    لديك {{ $stats['overdue_contracts'] }} عقد بدفعات متأخرة
                @endif
                @if($receivables > 0)
                    — ذمم مستحقة بقيمة {{ number_format($receivables, 0) }} ر.س
                @endif
            </span>
            <a href="{{ route('facility.contracts.index') }}" class="ms-auto btn btn-sm btn-danger">عرض العقود</a>
        </div>
    @endif

    {{-- يحتاج إجراء --}}
    @php
        $facilityActions = [];
        if ($isRealEstate && ($stats['new_leads'] ?? 0) > 0) {
            $facilityActions[] = ['icon' => 'fa-user-plus', 'color' => 'primary', 'count' => $stats['new_leads'], 'label' => __('facility.sidebar.leads') . ' — جدد', 'url' => route('facility.leads.index')];
        }
        if ($isRealEstate && ($stats['due_followups'] ?? 0) > 0) {
            $facilityActions[] = ['icon' => 'fa-phone', 'color' => 'warning', 'count' => $stats['due_followups'], 'label' => 'متابعات مستحقة اليوم', 'url' => route('facility.leads.index')];
        }
        if ($isContracting && ($stats['open_execution_requests'] ?? 0) > 0) {
            $facilityActions[] = ['icon' => 'fa-gavel', 'color' => 'info', 'count' => $stats['open_execution_requests'], 'label' => __('facility.dashboard.open_execution_requests'), 'url' => route('facility.execution-requests.workspace')];
        }
        if (($stats['unread_notifications'] ?? 0) > 0) {
            $facilityActions[] = ['icon' => 'fa-bell', 'color' => 'danger', 'count' => $stats['unread_notifications'], 'label' => __('facility.sidebar.notifications') . ' غير مقروءة', 'url' => route('facility.notifications')];
        }
    @endphp
    @if(count($facilityActions))
        <div class="card border-0 shadow-sm mb-4" style="border-inline-start: 4px solid var(--bs-warning);">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-circle-exclamation text-warning"></i>
                    <span class="fw-bold">يحتاج إجراء</span>
                </div>
                <div class="d-flex flex-column gap-2">
                    @foreach($facilityActions as $item)
                        <a href="{{ $item['url'] }}" class="d-flex align-items-center gap-3 text-decoration-none text-dark">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-{{ $item['color'] }}-subtle text-{{ $item['color'] }}" style="width: 38px; height: 38px;">
                                <i class="fas {{ $item['icon'] }}"></i>
                            </span>
                            <span class="flex-grow-1">{{ $item['label'] }}</span>
                            <span class="badge text-bg-{{ $item['color'] }} rounded-pill">{{ $item['count'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ====== قسم العقارات ====== --}}
    @if($isRealEstate)
        @php
            $collected = $stats['collected_month'] ?? 0;
            $prev = $stats['collected_prev_month'] ?? 0;
            $delta = $prev > 0 ? round(($collected - $prev) / $prev * 100) : null;
            $target = $stats['collection_target'] ?? null;
            $pct = $target ? min(100, round($collected / max($target, 1) * 100)) : null;
        @endphp

        {{-- بطاقة البطل المالية --}}
        <div class="dash-hero p-4 mb-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <div class="hero-sub mb-2"><i class="fas fa-money-bill-wave me-1"></i>تحصيل {{ now()->translatedFormat('F Y') }}</div>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="hero-num">{{ number_format($collected, 0) }}</div>
                        <div>
                            <div class="hero-sub">ر.س</div>
                            @if($delta !== null)
                                <span class="delta">
                                    <i class="fas fa-arrow-{{ $delta >= 0 ? 'up' : 'down' }} me-1"></i>{{ abs($delta) }}% عن الشهر الماضي
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($target)
                        <div class="mt-3" style="max-width:320px">
                            <div class="d-flex justify-content-between hero-sub mb-1">
                                <span>هدف التحصيل</span>
                                <span>{{ number_format($collected,0) }} / {{ number_format($target,0) }}</span>
                            </div>
                            <div class="progress progress-thin bg-white bg-opacity-25">
                                <div class="progress-bar bg-warning" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-7">
                    <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('facility.invoices.index') }}" class="text-decoration-none text-white">
                                <div class="kpi-mini">
                                    <div class="l">ذمم مستحقة</div>
                                    <div class="v">{{ number_format($receivables, 0) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('facility.invoices.index') }}" class="text-decoration-none text-white">
                                <div class="kpi-mini">
                                    <div class="l">متوقع هذا الشهر</div>
                                    <div class="v">{{ number_format($stats['expected_next_month'] ?? 0, 0) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('facility.contracts.index') }}" class="text-decoration-none text-white">
                                <div class="kpi-mini">
                                    <div class="l">عقود نشطة</div>
                                    <div class="v">{{ $stats['active_contracts'] ?? 0 }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('facility.products.index') }}" class="text-decoration-none text-white">
                                <div class="kpi-mini">
                                    <div class="l">نسبة الإشغال</div>
                                    <div class="v">{{ round(($stats['occupancy']['rented'] ?? 0) / max($stats['occupancy']['total'] ?? 1, 1) * 100) }}%</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- الرسوم البيانية --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-7">
                <div class="card stat-card h-100">
                    <div class="card-header bg-transparent fw-bold d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-chart-line me-2 text-primary"></i>اتجاه التحصيل — آخر 6 أشهر</span>
                    </div>
                    <div class="card-body">
                        <div class="chart-box"><canvas id="revenueChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3 h-100">
                    <div class="col-12">
                        <div class="card stat-card h-100">
                            <div class="card-header bg-transparent fw-bold"><i class="fas fa-chart-pie me-2 text-success"></i>العقود حسب الحالة</div>
                            <div class="card-body">
                                <div class="chart-box" style="height:190px"><canvas id="contractsChart"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- اقتراحات ذكية --}}
        @if(!empty($stats['suggestions']) && $stats['suggestions']->count())
            <div class="card stat-card mb-4" style="border-inline-start:4px solid #6f42c1">
                <div class="card-header bg-transparent d-flex align-items-center gap-2">
                    <i class="fas fa-wand-magic-sparkles" style="color:#6f42c1"></i>
                    <span class="fw-bold">اقتراحات ذكية</span>
                    <span class="text-muted small">— إجراءات يحتاجها عملك الآن</span>
                </div>
                <div class="card-body pt-2">
                    @foreach($stats['suggestions'] as $s)
                        <div class="d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <i class="fas {{ $s->icon }} text-{{ $s->color }} fs-5"></i>
                            <span class="flex-grow-1 small">{{ $s->text }}</span>
                            @if($s->method === 'POST')
                                <form method="POST" action="{{ $s->url }}">@csrf
                                    <button class="btn btn-sm btn-outline-{{ $s->color }} text-nowrap">{{ $s->action }}</button>
                                </form>
                            @else
                                <a href="{{ $s->url }}" class="btn btn-sm btn-outline-{{ $s->color }} text-nowrap">{{ $s->action }}</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- KPIs تشغيلية --}}
        <div class="row g-3 mb-4">
            @php
                $kpis = [
                    ['l' => 'العقارات', 'v' => $stats['total_properties'] ?? 0, 'i' => 'fa-building', 'c' => 'primary', 'r' => route('facility.products.index')],
                    ['l' => 'المستأجرين', 'v' => $stats['total_tenants'] ?? 0, 'i' => 'fa-user-check', 'c' => 'info', 'r' => route('facility.tenants.index')],
                    ['l' => 'طلبات المعاينة', 'v' => $stats['total_bookings'] ?? 0, 'i' => 'fa-calendar-check', 'c' => 'warning', 'r' => route('facility.bookings.index')],
                    ['l' => 'عملاء محتملون جدد', 'v' => $stats['new_leads'] ?? 0, 'i' => 'fa-user-tag', 'c' => 'purple', 'r' => route('facility.leads.index')],
                    ['l' => 'متابعات مستحقة', 'v' => $stats['due_followups'] ?? 0, 'i' => 'fa-phone', 'c' => 'danger', 'r' => route('facility.leads.index')],
                    ['l' => 'معاينات اليوم', 'v' => $stats['today_viewings'] ?? 0, 'i' => 'fa-calendar-day', 'c' => 'success', 'r' => route('facility.bookings.calendar')],
                ];
            @endphp
            @foreach($kpis as $k)
                <div class="col-6 col-md-4 col-xl-2">
                    <a href="{{ $k['r'] }}" class="text-decoration-none">
                        <div class="card stat-card h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-wrap bg-{{ $k['c'] === 'purple' ? 'primary' : $k['c'] }}-subtle text-{{ $k['c'] === 'purple' ? 'primary' : $k['c'] }}" @if($k['c']==='purple') style="color:#6f42c1 !important;background:#6f42c120"@endif>
                                        <i class="fas {{ $k['i'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="v text-dark">{{ $k['v'] }}</div>
                                        <div class="l">{{ $k['l'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- أجندة اليوم + الأعلاف الموحّدة --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <div class="card stat-card h-100">
                    <div class="card-header bg-transparent fw-bold"><i class="fas fa-calendar-day me-2 text-info"></i>أجندة اليوم</div>
                    <div class="card-body">
                        <div class="text-muted small fw-semibold mb-2">معاينات اليوم</div>
                        @forelse($stats['today_viewings_list'] ?? [] as $v)
                            <a href="{{ route('facility.bookings.show', $v) }}" class="d-block text-decoration-none agenda-item">
                                <span class="badge bg-info agenda-time">{{ $v->preferred_time ?: '—' }}</span>
                                <div class="flex-grow-1">
                                    <div class="text-dark small fw-semibold">{{ $v->user->name ?? $v->name ?? 'زائر' }}</div>
                                    <div class="text-muted small text-truncate">{{ $v->product?->getTranslatedTitle() ?? '' }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="text-muted small mb-3">لا معاينات اليوم</div>
                        @endforelse

                        <div class="text-muted small fw-semibold mb-2 mt-3">متابعات مستحقة</div>
                        @forelse($stats['due_followups_list'] ?? [] as $f)
                            <a href="{{ route('facility.leads.show', $f->lead_id) }}" class="d-block text-decoration-none agenda-item">
                                <span class="badge bg-danger agenda-time">{{ $f->follow_up_at?->format('H:i') }}</span>
                                <div class="flex-grow-1">
                                    <div class="text-dark small fw-semibold">{{ $f->lead?->name }}</div>
                                    <div class="text-muted small" dir="ltr">{{ $f->lead?->phone }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="text-muted small">لا متابعات مستحقة</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card stat-card h-100">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-signature me-2 text-primary"></i>آخر العقود</span>
                        <a href="{{ route('facility.contracts.index') }}" class="text-primary small">الكل</a>
                    </div>
                    <div class="card-body">
                        @forelse($stats['recent_contracts'] ?? [] as $contract)
                            <a href="{{ route('facility.contracts.show', $contract) }}" class="d-block text-decoration-none feed-item">
                                <div class="feed-icon bg-primary-subtle text-primary"><i class="fas fa-file-signature"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold text-dark small">{{ $contract->contract_number ?? ('عقد #' . $contract->id) }}</div>
                                    <div class="text-muted small">
                                        {{ $contract->tenant?->getTranslatedName() ?? $contract->user?->name ?? '—' }}
                                        · {{ number_format($contract->total_amount ?? 0, 0) }} ر.س
                                    </div>
                                </div>
                                <span class="text-muted" style="font-size:.7rem">{{ $contract->created_at?->diffForHumans() }}</span>
                            </a>
                        @empty
                            <div class="text-muted small">لا توجد عقود بعد</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card stat-card h-100">
                    <div class="card-header bg-transparent fw-bold"><i class="fas fa-bolt me-2 text-warning"></i>آخر الأحداث</div>
                    <div class="card-body">
                        @forelse($stats['recent_activity'] ?? [] as $ev)
                            <div class="feed-item">
                                <div class="feed-icon bg-{{ $ev->color }}-subtle text-{{ $ev->color }}"><i class="fas {{ $ev->icon }}"></i></div>
                                <span class="small flex-grow-1">{{ $ev->text }}</span>
                                <span class="text-muted" style="font-size:.7rem">{{ $ev->time?->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="text-muted small">لا أحداث</div>
                        @endforelse

                        @if(isset($stats['recent_tasks']) && $stats['recent_tasks']->count())
                            <div class="text-muted small fw-semibold mt-3 mb-1 pt-2 border-top">{{ __('facility.dashboard.recent_tasks') }}</div>
                            @foreach($stats['recent_tasks']->take(3) as $task)
                                <div class="feed-item">
                                    <div class="feed-icon bg-secondary-subtle text-secondary"><i class="fas fa-tasks"></i></div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-semibold">{{ $task->title }}</div>
                                        <div class="text-muted small">{{ $task->assignedTo->name ?? __('facility.dashboard.unassigned') }}</div>
                                    </div>
                                    <span class="text-muted" style="font-size:.7rem">{{ $task->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- إجراءات سريعة --}}
        <div class="card stat-card mb-4">
            <div class="card-header bg-transparent fw-bold"><i class="fas fa-bolt me-2 text-warning"></i>إجراءات سريعة</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6 col-md-4 col-lg">
                        <a class="btn btn-outline-primary w-100" href="{{ route('facility.products.create') }}"><i class="fas fa-plus me-1"></i>إضافة عقار</a>
                    </div>
                    <div class="col-6 col-md-4 col-lg">
                        <a class="btn btn-outline-success w-100" href="{{ route('facility.tenants.create') }}"><i class="fas fa-user-plus me-1"></i>مستأجر جديد</a>
                    </div>
                    <div class="col-6 col-md-4 col-lg">
                        <a class="btn btn-outline-info w-100" href="{{ route('facility.contracts.create') }}"><i class="fas fa-file-signature me-1"></i>إنشاء عقد</a>
                    </div>
                    <div class="col-6 col-md-4 col-lg">
                        <a class="btn btn-outline-warning w-100" href="{{ route('facility.bookings.calendar') }}"><i class="fas fa-calendar-days me-1"></i>تقويم المعاينات</a>
                    </div>
                    <div class="col-6 col-md-4 col-lg">
                        <a class="btn btn-outline-dark w-100" href="{{ route('facility.leads.create') }}"><i class="fas fa-user-tag me-1"></i>عميل محتمل</a>
                    </div>
                    <div class="col-6 col-md-4 col-lg">
                        <a class="btn btn-outline-secondary w-100" href="{{ route('facility.invoices.create') }}"><i class="fas fa-file-invoice me-1"></i>فاتورة جديدة</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== قسم المقاولات ====== --}}
    @if($isContracting)
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-wrap bg-primary-subtle text-primary"><i class="fas fa-gavel"></i></div>
                        <div>
                            <div class="v">{{ $stats['total_execution_requests'] ?? 0 }}</div>
                            <div class="l">{{ __('facility.dashboard.total_execution_requests') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-wrap bg-success-subtle text-success"><i class="fas fa-clipboard-list"></i></div>
                        <div>
                            <div class="v">{{ $stats['total_execution_bids_received'] ?? 0 }}</div>
                            <div class="l">{{ __('facility.dashboard.total_bids_received') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-wrap bg-info-subtle text-info"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="v">{{ $stats['open_execution_requests'] ?? 0 }}</div>
                            <div class="l">{{ __('facility.dashboard.open_execution_requests') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="card stat-card h-100">
                    <div class="card-header bg-transparent fw-bold">{{ __('facility.dashboard.recent_execution_requests_title') }}</div>
                    <div class="card-body">
                        @forelse($stats['recent_execution_requests'] ?? [] as $req)
                            <div class="feed-item">
                                <div class="feed-icon bg-primary-subtle text-primary"><i class="fas fa-gavel"></i></div>
                                <div class="flex-grow-1">
                                    @php($t = $req->translations->firstWhere('locale', app()->getLocale()))
                                    <div class="fw-semibold small">{{ $t->title ?? ('#' . $req->id) }}</div>
                                    <div class="text-muted small">{{ $req->status }}</div>
                                </div>
                                <span class="text-muted" style="font-size:.7rem">{{ $req->created_at?->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="text-muted small">{{ __('facility.dashboard.no_recent_execution_requests') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card stat-card h-100">
                    <div class="card-header bg-transparent fw-bold"><i class="fas fa-bolt me-2 text-warning"></i>إجراءات سريعة</div>
                    <div class="card-body d-flex align-items-center">
                        <div class="row g-2 w-100">
                            <div class="col-12 col-md-4">
                                <a class="btn btn-outline-primary w-100" href="{{ route('facility.execution-requests.workspace') }}">{{ __('facility.dashboard.workspace') }}</a>
                            </div>
                            <div class="col-12 col-md-4">
                                <a class="btn btn-outline-success w-100" href="{{ route('facility.tenders.index') }}">المناقصات</a>
                            </div>
                            <div class="col-12 col-md-4">
                                <a class="btn btn-outline-info w-100" href="{{ route('facility.quotes.index') }}">عروض الأسعار</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
@if($isRealEstate)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
(function () {
    const trend = @json($stats['revenue_trend'] ?? []);
    const months = Object.keys(trend);
    const labels = months.map(m => {
        const [y, mo] = m.split('-');
        return new Date(y, mo - 1).toLocaleDateString('{{ app()->getLocale() }}', { month: 'short' });
    });

    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, 'rgba(13,110,253,.25)');
        gradient.addColorStop(1, 'rgba(13,110,253,0)');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    data: Object.values(trend),
                    borderColor: '#0d6efd',
                    backgroundColor: gradient,
                    fill: true,
                    tension: .4,
                    pointRadius: 4,
                    pointBackgroundColor: '#0d6efd',
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => new Intl.NumberFormat('{{ app()->getLocale() }}', { notation: 'compact' }).format(v) } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const cbs = @json($stats['contracts_by_status'] ?? []);
    const cctx = document.getElementById('contractsChart');
    if (cctx) {
        const colors = { draft: '#6c757d', active: '#198754', completed: '#0d6efd', cancelled: '#dc3545', expired: '#fd7e14', terminated: '#6610f2' };
        const statusLabels = { draft: 'مسودة', active: 'نشط', completed: 'مكتمل', cancelled: 'ملغي', expired: 'منتهي', terminated: 'مفسوخ' };
        new Chart(cctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(cbs).map(s => statusLabels[s] || s),
                datasets: [{
                    data: Object.values(cbs),
                    backgroundColor: Object.keys(cbs).map(s => colors[s] || '#adb5bd'),
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '65%',
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
            }
        });
    }
})();
</script>
@endif
@endpush
