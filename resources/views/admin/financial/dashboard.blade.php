@extends('admin.financial.layout')

@section('title', 'لوحة المعلومات المالية - النظام المالي للأدمن')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt ms-2"></i>لوحة المعلومات المالية</h2>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary" onclick="refreshDashboard()">
            <i class="fas fa-sync-alt"></i> تحديث
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#periodModal">
            <i class="fas fa-calendar"></i> تغيير الفترة
        </button>
    </div>
</div>

<!-- فترة التقرير -->
<div class="alert alert-info d-flex justify-content-between align-items-center">
    <div>
        <i class="fas fa-calendar-alt ms-2"></i>
        <strong>فترة التقرير:</strong> 
        {{ $startDate->format('Y/m/d') }} - {{ $endDate->format('Y/m/d') }}
    </div>
    <small class="text-muted">آخر تحديث: {{ now()->format('H:i') }}</small>
</div>

<!-- الإحصائيات الرئيسية -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">إجمالي الإيرادات</h6>
                    <div class="number text-success">{{ number_format($totalRevenue, 2) }} ر.س</div>
                    <small class="text-muted">
                        <i class="fas fa-arrow-up text-success"></i>
                        الفترة: {{ number_format($periodRevenue, 2) }} ر.س
                    </small>
                </div>
                <div class="icon text-success">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">إجمالي العمولات</h6>
                    <div class="number text-warning">{{ number_format($totalCommissions, 2) }} ر.س</div>
                    <small class="text-muted">
                        نسبة العمولة: {{ $totalRevenue > 0 ? number_format(($totalCommissions / $totalRevenue) * 100, 1) : 0 }}%
                    </small>
                </div>
                <div class="icon text-warning">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">إجمالي العقود</h6>
                    <div class="number text-primary">{{ number_format($totalContracts) }}</div>
                    <small class="text-muted">
                        <i class="fas fa-plus text-success"></i>
                        الفترة: {{ number_format($periodContracts) }}
                    </small>
                </div>
                <div class="icon text-primary">
                    <i class="fas fa-file-contract"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">العروض النشطة</h6>
                    <div class="number text-success">{{ number_format($activeOffers) }}</div>
                    <small class="text-muted">
                        من أصل {{ number_format($totalOffers) }} عرض
                    </small>
                </div>
                <div class="icon text-success">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- التنبيهات والتحذيرات -->
@if($pendingContracts > 0 || $overdueInvoices > 0 || $pendingPayments > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle ms-2"></i>التنبيهات والمهام المعلقة</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($pendingContracts > 0)
                    <div class="col-md-4 mb-3">
                        <div class="alert alert-warning d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ number_format($pendingContracts) }}</strong> عقد معلق
                                <br><small>يحتاج للمراجعة والموافقة</small>
                            </div>
                            <a href="{{ route('admin.financial.contracts') }}?status=draft" class="btn btn-sm btn-warning">
                                مراجعة
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($overdueInvoices > 0)
                    <div class="col-md-4 mb-3">
                        <div class="alert alert-danger d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ number_format($overdueInvoices) }}</strong> فاتورة متأخرة
                                <br><small>{{ number_format($overdueAmount, 2) }} ر.س</small>
                            </div>
                            <a href="{{ route('admin.financial.payments') }}?status=overdue" class="btn btn-sm btn-danger">
                                متابعة
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($pendingPayments > 0)
                    <div class="col-md-4 mb-3">
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ number_format($pendingPayments) }}</strong> دفعة معلقة
                                <br><small>{{ number_format($pendingPaymentsAmount, 2) }} ر.س</small>
                            </div>
                            <a href="{{ route('admin.financial.payments') }}?status=pending" class="btn btn-sm btn-info">
                                تأكيد
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- الرسوم البيانية والتحليلات -->
<div class="row mb-4">
    <div class="col-lg-8 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line ms-2"></i>الإيرادات الشهرية (آخر 6 أشهر)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-building ms-2"></i>أفضل المؤسسات</h5>
            </div>
            <div class="card-body">
                @forelse($topFacilities as $facility)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong>{{ $facility->name }}</strong>
                        <br><small class="text-muted">{{ $facility->contracts_count }} عقد</small>
                    </div>
                    <span class="badge bg-primary">{{ $facility->contracts_count }}</span>
                </div>
                @empty
                <div class="text-center text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>لا توجد بيانات</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- الأنشطة الحديثة -->
<div class="row">
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock ms-2"></i>أحدث العقود</h5>
            </div>
            <div class="card-body">
                @forelse($recentContracts as $contract)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        <strong>{{ $contract->contract_number }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ $contract->user->name }} - {{ $contract->facility->name }}
                        </small>
                        <br>
                        <small class="timestamp" data-timestamp="{{ $contract->created_at }}">
                            {{ $contract->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">{{ number_format($contract->total_amount, 2) }} ر.س</div>
                        @if($contract->status === 'draft')
                            <span class="badge bg-warning">معلق</span>
                        @elseif($contract->status === 'active')
                            <span class="badge bg-success">نشط</span>
                        @else
                            <span class="badge bg-secondary">{{ $contract->status }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>لا توجد عقود حديثة</p>
                </div>
                @endforelse
                
                @if($recentContracts->count() > 0)
                <div class="text-center mt-3">
                    <a href="{{ route('admin.financial.contracts') }}" class="btn btn-outline-primary btn-sm">
                        عرض جميع العقود
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-credit-card ms-2"></i>أحدث المدفوعات</h5>
            </div>
            <div class="card-body">
                @forelse($recentPayments as $payment)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        <strong>{{ $payment->reference_number }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ $payment->contract->user->name }}
                        </small>
                        <br>
                        <small class="timestamp" data-timestamp="{{ $payment->created_at }}">
                            {{ $payment->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">{{ number_format($payment->amount, 2) }} ر.س</div>
                        @if($payment->status === 'pending')
                            <span class="badge bg-warning">معلق</span>
                        @elseif($payment->status === 'confirmed')
                            <span class="badge bg-success">مؤكد</span>
                        @else
                            <span class="badge bg-danger">{{ $payment->status }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>لا توجد مدفوعات حديثة</p>
                </div>
                @endforelse
                
                @if($recentPayments->count() > 0)
                <div class="text-center mt-3">
                    <a href="{{ route('admin.financial.payments') }}" class="btn btn-outline-primary btn-sm">
                        عرض جميع المدفوعات
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- مودال تغيير الفترة -->
<div class="modal fade" id="periodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تغيير فترة التقرير</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('admin.financial.dashboard') }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">من تاريخ</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">إلى تاريخ</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">فترات سريعة</label>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="setQuickPeriod('this_month')">
                                    هذا الشهر
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="setQuickPeriod('last_month')">
                                    الشهر الماضي
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="setQuickPeriod('this_year')">
                                    هذا العام
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تطبيق</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // رسم الإيرادات الشهرية
    const revenueData = @json($monthlyRevenue);
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => item.month),
            datasets: [{
                label: 'الإيرادات (ر.س)',
                data: revenueData.map(item => item.revenue),
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value) + ' ر.س';
                        }
                    }
                }
            },
            elements: {
                point: {
                    radius: 6,
                    hoverRadius: 8
                }
            }
        }
    });

    // دالة تحديث لوحة المعلومات
    function refreshDashboard() {
        showLoading();
        location.reload();
    }

    // دالة تعيين الفترات السريعة
    function setQuickPeriod(period) {
        const startInput = document.querySelector('input[name="start_date"]');
        const endInput = document.querySelector('input[name="end_date"]');
        const today = new Date();
        
        let startDate, endDate;
        
        switch(period) {
            case 'this_month':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
            case 'last_month':
                startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                break;
            case 'this_year':
                startDate = new Date(today.getFullYear(), 0, 1);
                endDate = new Date(today.getFullYear(), 11, 31);
                break;
        }
        
        startInput.value = startDate.toISOString().split('T')[0];
        endInput.value = endDate.toISOString().split('T')[0];
    }

    // تحديث الإحصائيات كل 5 دقائق
    setInterval(function() {
        // يمكن إضافة AJAX call لتحديث الإحصائيات بدون إعادة تحميل الصفحة
    }, 300000);
</script>
@endsection
