@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Card -->
    <div class="card shadow-sm mb-4" data-intro="{{ __('admin.tour.welcome_card_desc') }}" data-step="10">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1 fw-bold">{{ __('admin.dashboard.welcome_title') }}</h4>
                    <p class="text-muted mb-0">{{ __('admin.dashboard.welcome_subtitle') }}</p>
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    <a href="{{ route('admin.statistics') }}" class="dashboard-quick-link d-inline-flex align-items-center gap-2 text-decoration-none rounded-3 border bg-white px-3 py-2 shadow-sm">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary" style="width:36px;height:36px;">
                            <i class="fas fa-chart-bar"></i>
                        </span>
                        <span class="fw-semibold text-dark">{{ __('admin.dashboard.detailed_statistics') }}</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="dashboard-quick-link d-inline-flex align-items-center gap-2 text-decoration-none rounded-3 border bg-white px-3 py-2 shadow-sm">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success-subtle text-success" style="width:36px;height:36px;">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        <span class="fw-semibold text-dark">{{ __('admin.dashboard.reports') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @php
        $needsActionItems = collect([
            [
                'label' => __('admin.dashboard.new_product_requests'),
                'count' => (int) ($stats['pending_product_requests'] ?? 0),
                'url' => \Illuminate\Support\Facades\Route::has('admin.product-requests.index') ? route('admin.product-requests.index') : null,
                'icon' => 'fa-building',
            ],
            [
                'label' => __('admin.dashboard.new_marketing_requests'),
                'count' => (int) ($stats['pending_marketing_requests'] ?? 0),
                'url' => \Illuminate\Support\Facades\Route::has('admin.marketing-product-requests.index') ? route('admin.marketing-product-requests.index') : null,
                'icon' => 'fa-bullhorn',
            ],
            [
                'label' => __('admin.dashboard.unread_notifications'),
                'count' => (int) ($stats['unread_notifications'] ?? 0),
                'url' => \Illuminate\Support\Facades\Route::has('admin.notifications') ? route('admin.notifications') : null,
                'icon' => 'fa-bell',
            ],
        ])->filter(fn ($item) => $item['count'] > 0 && $item['url']);
    @endphp

    @if($needsActionItems->isNotEmpty())
    <div class="card shadow-sm mb-4 border-warning-subtle">
        <div class="card-body d-flex align-items-center flex-wrap gap-3 py-3">
            <div class="d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning-subtle text-warning" style="width:36px;height:36px;">
                    <i class="fas fa-exclamation"></i>
                </span>
                <h6 class="mb-0 fw-bold">{{ __('admin.dashboard.needs_action') }}</h6>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @foreach($needsActionItems as $item)
                    <a href="{{ $item['url'] }}" class="dashboard-quick-link d-inline-flex align-items-center gap-2 text-decoration-none rounded-3 border bg-white px-3 py-2 shadow-sm">
                        <i class="fas {{ $item['icon'] }} text-muted"></i>
                        <span class="fw-semibold text-dark small">{{ $item['label'] }}</span>
                        <span class="badge rounded-pill bg-danger">{{ $item['count'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="card shadow-sm mb-4 border-primary-subtle" id="admin-ai-assistant">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">{{ __('admin.dashboard.ai.title') }}</h5>
            <span class="badge bg-primary">AI</span>
        </div>
        <div class="card-body">
            <p class="text-muted small">{{ __('admin.dashboard.ai.subtitle') }}</p>
            <div class="input-group">
                <input type="text" class="form-control" id="admin-ai-question" placeholder="{{ __('admin.dashboard.ai.placeholder') }}">
                <button type="button" class="btn btn-primary" id="admin-ai-ask">{{ __('admin.dashboard.ai.ask') }}</button>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <button type="button" class="btn btn-sm btn-outline-secondary admin-ai-prompt" data-prompt="{{ __('admin.dashboard.ai.prompt_missing_products_q') }}">{{ __('admin.dashboard.ai.prompt_missing_products') }}</button>
                <button type="button" class="btn btn-sm btn-outline-secondary admin-ai-prompt" data-prompt="{{ __('admin.dashboard.ai.prompt_requests_q') }}">{{ __('admin.dashboard.ai.prompt_requests') }}</button>
                <button type="button" class="btn btn-sm btn-outline-secondary admin-ai-prompt" data-prompt="{{ __('admin.dashboard.ai.prompt_daily_plan_q') }}">{{ __('admin.dashboard.ai.prompt_daily_plan') }}</button>
            </div>
            <div id="admin-ai-answer" class="alert alert-light border mt-3 mb-0" style="display:none; white-space:pre-line;"></div>
            <div id="admin-ai-links" class="d-flex flex-wrap gap-2 mt-2"></div>
        </div>
    </div>

    <!-- Period filter -->
    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
        @foreach(['all' => 'period_all', 'today' => 'period_today', 'week' => 'period_week', 'month' => 'period_month'] as $key => $labelKey)
            <a href="{{ route('admin.dashboard', ['period' => $key]) }}" class="btn btn-sm {{ ($period ?? 'all') === $key ? 'btn-primary' : 'btn-outline-secondary' }}">{{ __('admin.dashboard.' . $labelKey) }}</a>
        @endforeach
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4" data-intro="{{ __('admin.tour.stats_cards_desc') }}" data-step="11">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('admin.users.title') }}</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="text-primary fs-4"><i class="fas fa-users"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index') }}" class="small text-decoration-none">{{ __('admin.actions.view_details') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('admin.facilities.title') }}</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_facilities'] }}</div>
                        </div>
                        <div class="text-success fs-4"><i class="fas fa-building"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.facilities.index') }}" class="small text-decoration-none">{{ __('admin.actions.view_details') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('admin.products.title') }}</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_products'] }}</div>
                        </div>
                        <div class="text-info fs-4"><i class="fas fa-box"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.products.index') }}" class="small text-decoration-none">{{ __('admin.actions.view_details') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('admin.bookings.title') }}</div>
                            <div class="h4 fw-bold mb-0">{{ $stats['total_bookings'] }}</div>
                        </div>
                        <div class="text-warning fs-4"><i class="fas fa-calendar-check"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.bookings.index') }}" class="small text-decoration-none">{{ __('admin.actions.view_details') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('admin.dashboard.this_month_revenue') }}</div>
                            <div class="h4 fw-bold mb-0">{{ number_format($stats['total_revenue_month'] ?? 0, 2) }}</div>
                        </div>
                        <div class="text-danger fs-4"><i class="fas fa-coins"></i></div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.bookings.index') }}" class="small text-decoration-none">{{ __('admin.dashboard.booking_details') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings & Revenue Chart -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="mb-0">{{ __('admin.dashboard.chart_title') }}</h6>
        </div>
        <div class="card-body">
            <div style="position:relative;height:280px;">
                <canvas id="bookingsRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- New User Management Features -->
    <div class="row g-4 mb-4" data-intro="{{ __('admin.tour.user_management_desc') }}" data-step="12">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('admin.dashboard.advanced_user_management') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-cog fa-2x text-primary mb-3"></i>
                                    <h6 class="card-title">{{ __('admin.dashboard.permission_management') }}</h6>
                                    <p class="card-text small text-muted">{{ __('admin.dashboard.permission_management_desc') }}</p>
                                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i>{{ __('admin.dashboard.permission_management') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-2x text-success mb-3"></i>
                                    <h6 class="card-title">{{ __('admin.dashboard.user_statistics') }}</h6>
                                    <p class="card-text small text-muted">{{ __('admin.dashboard.user_statistics_desc') }}</p>
                                    <a href="{{ route('admin.users.statistics') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i>{{ __('admin.dashboard.view_statistics') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-download fa-2x text-info mb-3"></i>
                                    <h6 class="card-title">{{ __('admin.dashboard.export_data') }}</h6>
                                    <p class="card-text small text-muted">{{ __('admin.dashboard.export_data_desc') }}</p>
                                    <a href="{{ route('admin.users.export') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i>{{ __('admin.dashboard.export_data') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-history fa-2x text-warning mb-3"></i>
                                    <h6 class="card-title">{{ __('admin.dashboard.activity_log') }}</h6>
                                    <p class="card-text small text-muted">{{ __('admin.dashboard.activity_log_desc') }}</p>
                                    <a href="{{ route('admin.users.activity-logs') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i>{{ __('admin.dashboard.view_log') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-4" data-intro="{{ __('admin.tour.recent_activities_desc') }}" data-step="13">
        <!-- Recent Users -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('admin.dashboard.recent_users') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($stats['recent_users'] as $user)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                @if($user->avatar)
                                    <img src="{{ asset($user->avatar) }}" alt="avatar" class="rounded-circle me-3" width="40">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-light ms-auto">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">
                        {{ __('admin.dashboard.view_all_users') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Facilities -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('admin.dashboard.recent_facilities') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($stats['recent_facilities'] as $facility)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                @if($facility->logo)
                                    <img src="{{ asset($facility->logo) }}" alt="logo" class="rounded me-3" width="40">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-building text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $facility->name }}</h6>
                                    <small class="text-muted">{{ __('admin.dashboard.products_count', ['count' => $facility->products_count]) }}</small>
                                </div>
                                <a href="{{ route('admin.facilities.show', $facility) }}" class="btn btn-sm btn-light ms-auto">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.facilities.index') }}" class="btn btn-sm btn-primary">
                        {{ __('admin.dashboard.view_all_facilities') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('admin.dashboard.recent_bookings') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($stats['recent_bookings'] as $booking)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-light rounded p-2">
                                        <i class="fas fa-calendar-check text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $booking->user?->name ?? '—' }}</h6>
                                    <small class="text-muted">{{ $booking->product?->name ?? '—' }}</small>
                                </div>
                                <div class="ms-auto text-end">
                                    @if($booking->status)
                                    <span class="badge bg-{{ $booking->status->color }}">{{ $booking->status->getTranslatedName('ar') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('admin.dashboard.no_status') }}</span>
                                @endif
                                    <div>
                                        <small class="text-muted">{{ $booking->created_at->format('Y-m-d') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-primary">
                        {{ __('admin.dashboard.view_all_bookings') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.dashboard-quick-link { transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease; }
.dashboard-quick-link:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important; border-color: var(--bs-primary) !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    var input = document.getElementById('admin-ai-question');
    var ask = document.getElementById('admin-ai-ask');
    var answer = document.getElementById('admin-ai-answer');
    var links = document.getElementById('admin-ai-links');
    if(!input || !ask || !answer || !links) return;

    function submitQuestion(){
        var question = (input.value || '').trim();
        if(question.length < 3) return;
        var original = ask.textContent;
        ask.disabled = true;
        ask.textContent = @json(__('admin.dashboard.ai.analyzing'));
        fetch(@json(route('admin.ai-assistant')), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({question: question})
        }).then(function(response){
            return response.json().then(function(data){ return {ok: response.ok, data: data}; });
        }).then(function(result){
            if(!result.ok || !result.data.success) throw new Error(result.data.message || @json(__('admin.dashboard.ai.run_error')));
            var data = result.data.data || {};
            answer.textContent = data.answer || @json(__('admin.dashboard.ai.no_answer'));
            answer.style.display = 'block';
            links.innerHTML = '';
            (data.quick_links || []).forEach(function(link){
                var anchor = document.createElement('a');
                anchor.href = link.url;
                anchor.className = 'btn btn-sm btn-outline-primary';
                anchor.textContent = link.label;
                links.appendChild(anchor);
            });
        }).catch(function(error){
            answer.textContent = error.message || @json(__('admin.dashboard.ai.error'));
            answer.className = 'alert alert-danger mt-3 mb-0';
            answer.style.display = 'block';
        }).finally(function(){
            ask.disabled = false;
            ask.textContent = original;
        });
    }

    ask.addEventListener('click', submitQuestion);
    input.addEventListener('keydown', function(e){ if(e.key === 'Enter') submitQuestion(); });
    document.querySelectorAll('.admin-ai-prompt').forEach(function(button){
        button.addEventListener('click', function(){
            input.value = button.getAttribute('data-prompt') || '';
            submitQuestion();
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('bookingsRevenueChart');
    if (!el || typeof Chart === 'undefined') return;

    var chartData = @json($chart ?? ['labels' => [], 'bookings' => [], 'revenue' => []]);
    var brandBrown = getComputedStyle(document.documentElement).getPropertyValue('--brand-brown').trim() || '#6f4e37';
    var brandMuted = getComputedStyle(document.documentElement).getPropertyValue('--brand-muted').trim() || '#6c757d';
    var brandFg = getComputedStyle(document.documentElement).getPropertyValue('--brand-fg').trim() || '#172524';
    var isRtl = document.documentElement.dir === 'rtl';

    function hexToRgba(hex, alpha) {
        var h = hex.replace('#', '');
        if (h.length === 3) h = h.split('').map(function(c){ return c + c; }).join('');
        var n = parseInt(h, 16);
        return 'rgba(' + ((n >> 16) & 255) + ',' + ((n >> 8) & 255) + ',' + (n & 255) + ',' + alpha + ')';
    }

    new Chart(el, {
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    type: 'bar',
                    label: @json(__('admin.dashboard.chart_bookings')),
                    data: chartData.bookings,
                    backgroundColor: hexToRgba(brandBrown, 0.25),
                    borderColor: brandBrown,
                    borderWidth: 1.5,
                    borderRadius: 6,
                    yAxisID: 'y'
                },
                {
                    type: 'line',
                    label: @json(__('admin.dashboard.chart_revenue')),
                    data: chartData.revenue,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25,135,84,0.12)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {mode: 'index', intersect: false},
            scales: {
                y: {beginAtZero: true, ticks: {precision: 0, color: brandMuted}, grid: {color: 'rgba(0,0,0,0.05)'}},
                y1: {beginAtZero: true, position: isRtl ? 'left' : 'right', grid: {drawOnChartArea: false}, ticks: {color: brandMuted}},
                x: {ticks: {color: brandMuted}, grid: {display: false}}
            },
            plugins: {
                legend: {rtl: isRtl, labels: {color: brandFg, usePointStyle: true}},
                tooltip: {rtl: isRtl}
            }
        }
    });
});
</script>
@endpush
@endsection
