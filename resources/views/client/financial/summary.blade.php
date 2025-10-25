@extends('client.financial.layout')

@section('title', 'ملخصي المالي - منطقة العميل')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-chart-bar text-primary ms-2"></i>
                ملخصي المالي
            </h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="printSummary()">
                    <i class="fas fa-print ms-1"></i>
                    طباعة
                </button>
                <button class="btn btn-primary" onclick="exportSummary()">
                    <i class="fas fa-download ms-1"></i>
                    تصدير
                </button>
            </div>
        </div>
    </div>
</div>

<!-- فلتر التاريخ -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('client.financial.summary') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">من تاريخ</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">إلى تاريخ</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search ms-1"></i>
                            تطبيق الفلتر
                        </button>
                        <a href="{{ route('client.financial.summary') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times ms-1"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- الإحصائيات الرئيسية -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card success">
            <div class="icon success">
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="number">{{ number_format($totalContracts) }}</div>
            <div class="label">إجمالي العقود</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card primary">
            <div class="icon primary">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="number">{{ number_format($totalPayments, 2) }} ر.س</div>
            <div class="label">إجمالي المدفوعات</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card warning">
            <div class="icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="number">{{ number_format($pendingPayments, 2) }} ر.س</div>
            <div class="label">مدفوعات معلقة</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card danger">
            <div class="icon danger">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="number">{{ number_format($report['total_contract_value'] ?? 0, 2) }} ر.س</div>
            <div class="label">قيمة العقود</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- الرسم البياني للمدفوعات الشهرية -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line text-primary ms-2"></i>
                    المدفوعات الشهرية
                </h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyPaymentsChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- ملخص سريع -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle text-info ms-2"></i>
                    ملخص سريع
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">المبلغ المدفوع</span>
                        <span class="fw-bold text-success">{{ number_format($report['total_paid_amount'] ?? 0, 2) }} ر.س</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ $report['payment_percentage'] ?? 0 }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">المبلغ المتبقي</span>
                        <span class="fw-bold text-warning">{{ number_format($report['remaining_amount'] ?? 0, 2) }} ر.س</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: {{ 100 - ($report['payment_percentage'] ?? 0) }}%"></div>
                    </div>
                </div>

                <hr>

                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <div class="h4 text-primary mb-1">{{ $report['active_contracts'] ?? 0 }}</div>
                            <small class="text-muted">عقود نشطة</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="h4 text-success mb-1">{{ $report['completed_contracts'] ?? 0 }}</div>
                        <small class="text-muted">عقود مكتملة</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- تفاصيل العقود -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list text-primary ms-2"></i>
                    تفاصيل العقود
                </h5>
            </div>
            <div class="card-body">
                @if(isset($report['contracts']) && count($report['contracts']) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم العقد</th>
                                    <th>المنتج</th>
                                    <th>نوع العقد</th>
                                    <th>القيمة الإجمالية</th>
                                    <th>المدفوع</th>
                                    <th>المتبقي</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report['contracts'] as $contract)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark">#{{ $contract['id'] }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $contract['product_title'] ?? 'غير محدد' }}</div>
                                            <small class="text-muted">{{ $contract['facility_name'] ?? 'غير محدد' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $contract['contract_type'] == 'sale' ? 'bg-success' : 'bg-info' }}">
                                            {{ $contract['contract_type'] == 'sale' ? 'بيع' : 'إيجار' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">{{ number_format($contract['total_amount'], 2) }} ر.س</td>
                                    <td class="text-success">{{ number_format($contract['paid_amount'], 2) }} ر.س</td>
                                    <td class="text-warning">{{ number_format($contract['remaining_amount'], 2) }} ر.س</td>
                                    <td>
                                        <span class="badge 
                                            @if($contract['status'] == 'active') bg-success
                                            @elseif($contract['status'] == 'completed') bg-primary
                                            @elseif($contract['status'] == 'cancelled') bg-danger
                                            @else bg-warning @endif">
                                            @if($contract['status'] == 'active') نشط
                                            @elseif($contract['status'] == 'completed') مكتمل
                                            @elseif($contract['status'] == 'cancelled') ملغي
                                            @else مسودة @endif
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('client.financial.contract-details', $contract['id']) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-file-contract"></i>
                        <h4>لا توجد عقود</h4>
                        <p>لم يتم العثور على أي عقود في الفترة المحددة</p>
                        <a href="{{ route('client.financial.offers') }}" class="btn btn-primary">
                            <i class="fas fa-plus ms-1"></i>
                            تصفح العروض
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // رسم بياني للمدفوعات الشهرية
    const ctx = document.getElementById('monthlyPaymentsChart').getContext('2d');
    const monthlyData = @json($monthlyPayments);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'المدفوعات (ر.س)',
                data: monthlyData.map(item => item.amount),
                borderColor: 'rgb(30, 61, 89)',
                backgroundColor: 'rgba(30, 61, 89, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(30, 61, 89)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgb(30, 61, 89)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return 'المبلغ: ' + formatCurrency(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            elements: {
                point: {
                    hoverBackgroundColor: 'rgb(30, 61, 89)'
                }
            }
        }
    });
});

// دالة طباعة الملخص
function printSummary() {
    window.print();
}

// دالة تصدير الملخص
function exportSummary() {
    // يمكن إضافة منطق التصدير هنا
    alert('سيتم إضافة ميزة التصدير قريباً');
}
</script>
@endsection
