@extends('facility.layouts.app')

@section('title', 'اللوحة التنفيذية')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">اللوحة التنفيذية</h1>
            <span class="text-muted small">صورة شاملة لأداء المنشأة العقارية</span>
        </div>
        <a href="{{ route('facility.reports.executive.pdf') }}" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-file-pdf me-1"></i> تصدير PDF
        </a>
    </div>

    {{-- مؤشرات رئيسية --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-4 h-100">
                <div class="text-muted small mb-1">نسبة الإشغال</div>
                <div class="h2 fw-bold mb-1 {{ $occupancyRate >= 70 ? 'text-success' : ($occupancyRate >= 40 ? 'text-warning' : 'text-danger') }}">{{ $occupancyRate }}%</div>
                <div class="text-muted" style="font-size:.75rem">{{ $occupiedUnits }} من {{ $totalUnits }} وحدة</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-4 h-100">
                <div class="text-muted small mb-1">نسبة التحصيل</div>
                <div class="h2 fw-bold mb-1 {{ $collectionRate >= 80 ? 'text-success' : ($collectionRate >= 50 ? 'text-warning' : 'text-danger') }}">{{ $collectionRate }}%</div>
                <div class="text-muted" style="font-size:.75rem">من إجمالي الفواتير</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-4 h-100">
                <div class="text-muted small mb-1">إجمالي التحصيل</div>
                <div class="h2 fw-bold mb-1 text-success">{{ number_format($totalCollected, 0) }}</div>
                <div class="text-muted" style="font-size:.75rem">ر.س</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center py-4 h-100">
                <div class="text-muted small mb-1">متأخرات</div>
                <div class="h2 fw-bold mb-1 {{ $totalArrears > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($totalArrears, 0) }}</div>
                <div class="text-muted" style="font-size:.75rem">
                    <a href="{{ route('facility.reports.rentals.overdue') }}">مركز المتأخرات</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- الإيراد الشهري --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">الإيراد — آخر 6 أشهر</div>
                <div class="card-body">
                    <canvas id="revenueChart" height="110"></canvas>
                </div>
            </div>
        </div>

        {{-- أعلى العقارات --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">أعلى العقارات تحصيلاً</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @forelse($topProducts as $row)
                                @php $prod = $topProductNames->get($row->product_id); @endphp
                                <tr>
                                    <td class="ps-3">
                                        @if($prod)
                                            <a href="{{ route('facility.products.manage', $prod) }}">
                                                {{ $prod->getTranslatedTitle() ?? '#'.$row->product_id }}
                                            </a>
                                        @else #{{ $row->product_id }} @endif
                                    </td>
                                    <td class="text-end pe-3 fw-semibold text-success">{{ number_format($row->total, 0) }}</td>
                                </tr>
                            @empty
                                <tr><td class="text-center text-muted py-3">لا بيانات</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- عقود تنتهي --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">
                    عقود تنتهي خلال 60 يوم ({{ $expiringContracts->count() }})
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr><th class="ps-3">العقد</th><th>العقار</th><th>المستأجر</th><th>ينتهي</th><th class="pe-3">المتبقي</th></tr>
                        </thead>
                        <tbody>
                            @forelse($expiringContracts as $contract)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('facility.contracts.show', $contract) }}">{{ $contract->contract_number ?? '#'.$contract->id }}</a>
                                    </td>
                                    <td class="small">{{ $contract->product?->getTranslatedTitle() ?? '—' }}</td>
                                    <td class="small">{{ $contract->tenant?->getTranslatedName() ?? $contract->user?->name ?? '—' }}</td>
                                    <td>{{ $contract->end_date?->format('Y-m-d') }}</td>
                                    <td class="pe-3"><span class="badge bg-warning text-dark">{{ now()->diffInDays($contract->end_date) }} يوم</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">لا عقود تنتهي قريباً</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- تنبيهات تشغيلية --}}
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">تنبيهات تشغيلية</div>
                <div class="card-body">
                    <a href="{{ route('facility.products.vacant') }}" class="d-flex justify-content-between align-items-center mb-3 text-decoration-none text-dark">
                        <span><i class="fas fa-door-open text-warning me-2"></i>الوحدات الشاغرة</span>
                        <span class="badge bg-warning text-dark">{{ $totalUnits - $occupiedUnits }}</span>
                    </a>
                    <a href="{{ route('facility.maintenance.index') }}" class="d-flex justify-content-between align-items-center mb-3 text-decoration-none text-dark">
                        <span><i class="fas fa-screwdriver-wrench text-info me-2"></i>صيانة مفتوحة</span>
                        <span class="badge bg-info">{{ $openMaintenance }}</span>
                    </a>
                    <a href="{{ route('facility.reports.rentals.overdue') }}" class="d-flex justify-content-between align-items-center text-decoration-none text-dark">
                        <span><i class="fas fa-clock text-danger me-2"></i>متأخرات التحصيل</span>
                        <span class="badge bg-danger">{{ number_format($totalArrears, 0) }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- رادار المخاطر --}}
    <div class="card shadow-sm border-danger mt-3">
        <div class="card-header bg-white fw-bold text-danger"><i class="fas fa-radar me-2"></i>رادار المخاطر</div>
        <div class="card-body">
            <div class="row g-3 text-center">
                <div class="col-md-4">
                    <div class="h4 fw-bold mb-1 {{ $vacantLong > 0 ? 'text-danger' : 'text-success' }}">{{ $vacantLong }}</div>
                    <div class="text-muted small">وحدات شاغرة أكثر من 60 يوم</div>
                </div>
                <div class="col-md-4">
                    <div class="h4 fw-bold mb-1 {{ $agingMaintenance > 0 ? 'text-warning' : 'text-success' }}">{{ $agingMaintenance }}</div>
                    <div class="text-muted small">طلبات صيانة مفتوحة +14 يوم</div>
                </div>
                <div class="col-md-4">
                    <div class="h4 fw-bold mb-1 {{ $criticalContracts > 0 ? 'text-danger' : 'text-success' }}">{{ $criticalContracts }}</div>
                    <div class="text-muted small">عقود متأخرة +15 يوم (معرّضة للتعثر)</div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: @json($months->pluck('label')),
        datasets: [{
            label: 'الإيراد (ر.س)',
            data: @json($months->pluck('revenue')),
            backgroundColor: '#198754',
            borderRadius: 6,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endsection
