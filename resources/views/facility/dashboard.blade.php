@extends('layouts.dashboard-bs')

@section('title', __('facility.dashboard.title'))

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-0">{{ __('facility.dashboard.title') }}</h1>
    </div>

    <!-- {{ __('facility.dashboard.stats_title') }} -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">إجمالي طلبات التنفيذ</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_execution_requests'] ?? 0 }}</div>
                        </div>
                        <div class="text-primary fs-4"><i class="fas fa-gavel"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">إجمالي العروض المستلمة</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_execution_bids_received'] ?? 0 }}</div>
                        </div>
                        <div class="text-success fs-4"><i class="fas fa-clipboard-list"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">طلبات تنفيذ مفتوحة</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['open_execution_requests'] ?? 0 }}</div>
                        </div>
                        <div class="text-info fs-4"><i class="fas fa-clock"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('facility.dashboard.total_tasks') }}</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_tasks'] }}</div>
                        </div>
                        <div class="text-warning fs-4"><i class="fas fa-tasks"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">طلبات التمويل</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_loan_requests'] ?? 0 }}</div>
                        </div>
                        <div class="text-secondary fs-4"><i class="fas fa-building-columns"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- {{ __('facility.dashboard.recent_activity') }} -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <div class="fw-bold">طلبات تنفيذ حديثة</div>
                </div>
                <div class="card-body">
                @if(isset($stats['recent_execution_requests']) && $stats['recent_execution_requests'] && $stats['recent_execution_requests']->count() > 0)
                    @foreach($stats['recent_execution_requests'] as $req)
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="text-primary fs-5"><i class="fas fa-gavel"></i></div>
                        <div class="flex-grow-1">
                            @php
                                $t = $req->translations->firstWhere('locale', app()->getLocale());
                            @endphp
                            <div class="fw-semibold">{{ $t->title ?? ('طلب #' . $req->id) }}</div>
                            <div class="text-muted small">{{ $req->status }}</div>
                            <div class="text-muted small">{{ $req->created_at ? $req->created_at->diffForHumans() : 'غير محدد' }}</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-muted">لا توجد طلبات تنفيذ حديثة</div>
                @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <div class="fw-bold">{{ __('facility.dashboard.recent_tasks') }}</div>
                </div>
                <div class="card-body">
                @if(isset($stats['recent_tasks']) && $stats['recent_tasks'] && $stats['recent_tasks']->count() > 0)
                    @foreach($stats['recent_tasks'] as $task)
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="text-primary fs-5"><i class="fas fa-tasks"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $task->title }}</div>
                            <div class="text-muted small">{{ __('facility.dashboard.assigned_to') }}: {{ $task->assignedTo->name ?? __('facility.dashboard.unassigned') }}</div>
                            <div class="text-muted small">{{ $task->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-muted">{{ __('facility.dashboard.no_recent_tasks') }}</div>
                @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <div class="fw-bold">
                        <i class="fas fa-palette me-2"></i>
                        {{ __('facilities.dashboard.landing_customization') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('facility.customization.edit', $facility) }}" class="btn btn-primary btn-sm">
                            {{ __('facilities.dashboard.customize_now') }}
                        </a>
                        <a href="{{ route('public.facility.site.home', $facility->slug ?? $facility->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                            {{ __('facilities.dashboard.preview_site') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <div class="fw-bold">{{ __('facility.dashboard.facility_info') }}</div>
                </div>
                <div class="card-body">
                    <div class="fw-bold">{{ $facility->name }}</div>
                    <div class="text-muted small mb-2">{{ $facility->description }}</div>
                    <div class="small text-muted">{{ __('facility.form.address') }}: {{ $facility->address }}</div>
                    <div class="small text-muted">{{ __('facility.form.phone') }}: {{ $facility->phone }}</div>
                    <div class="small text-muted">{{ __('facility.form.email') }}: {{ $facility->email }}</div>
                    <div class="mt-3 d-flex gap-2 flex-wrap">
                        <a href="{{ route('facility.edit') }}" class="btn btn-outline-primary btn-sm">{{ __('facility.dashboard.edit_facility') }}</a>
                        <a href="{{ route('facility.execution-requests.index') }}" class="btn btn-outline-success btn-sm">إدارة طلبات التنفيذ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <div class="fw-bold">
                <i class="fas fa-cogs me-2"></i>
                إدارة الأنظمة
            </div>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12 col-md-6 col-lg-4">
                    <a class="btn btn-outline-primary w-100" href="{{ route('facility.execution-requests.workspace') }}">مساحة العمل</a>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <a class="btn btn-outline-success w-100" href="{{ route('facility.contracts.index') }}">العقود</a>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <a class="btn btn-outline-secondary w-100" href="{{ route('facility.invoices.index') }}">الفواتير</a>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <a class="btn btn-outline-warning w-100" href="{{ route('facility.payments.index') }}">المدفوعات</a>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <a class="btn btn-outline-info w-100" href="{{ route('facility.accounting.dashboard') }}">المحاسبة</a>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <a class="btn btn-outline-dark w-100" href="{{ route('facility.users.index') }}">المستخدمين</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <div class="fw-bold">
                <i class="fas fa-chart-bar me-2"></i>
                إحصائيات سريعة
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div class="h5 fw-bold mb-0">{{ $stats['total_offers'] ?? 0 }}</div>
                    <div class="text-muted small">إجمالي العروض</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="h5 fw-bold mb-0">{{ $stats['total_contracts'] ?? 0 }}</div>
                    <div class="text-muted small">إجمالي العقود</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="h5 fw-bold mb-0">{{ $stats['total_invoices'] ?? 0 }}</div>
                    <div class="text-muted small">إجمالي الفواتير</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="h5 fw-bold mb-0">{{ $stats['total_payments'] ?? 0 }}</div>
                    <div class="text-muted small">إجمالي المدفوعات</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
