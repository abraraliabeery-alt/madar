@extends('client.financial.layout')

@section('title', 'لوحة المعلومات - منطقة العميل')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">مرحباً، {{ Auth::user()->name }}</h2>
                <p class="text-muted mb-0">إليك نظرة عامة على حالتك المالية</p>
            </div>
            <div class="text-end">
                <small class="text-muted">آخر تحديث: {{ now()->format('H:i') }}</small>
                <br>
                <small class="text-muted">{{ now()->format('Y/m/d') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- الإحصائيات الرئيسية -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="icon primary">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="number">{{ number_format($totalContracts) }}</div>
                    <div class="label">إجمالي العقود</div>
                    <small class="text-success">
                        <i class="fas fa-arrow-up"></i>
                        {{ number_format($activeContracts) }} نشط
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card success">
            <div class="d-flex align-items-center">
                <div class="icon success">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="number">{{ number_format($totalContractValue, 0) }}</div>
                    <div class="label">القيمة الإجمالية (ر.س)</div>
                    <small class="text-muted">لجميع عقودك</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card warning">
            <div class="d-flex align-items-center">
                <div class="icon warning">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="number">{{ number_format($totalPaidAmount, 0) }}</div>
                    <div class="label">المبلغ المدفوع (ر.س)</div>
                    <small class="text-success">
                        نسبة السداد: {{ $totalContractValue > 0 ? number_format(($totalPaidAmount / $totalContractValue) * 100, 1) : 0 }}%
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card {{ $remainingAmount > 0 ? 'danger' : 'success' }}">
            <div class="d-flex align-items-center">
                <div class="icon {{ $remainingAmount > 0 ? 'danger' : 'success' }}">
                    <i class="fas fa-{{ $remainingAmount > 0 ? 'exclamation-triangle' : 'check-circle' }}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="number">{{ number_format($remainingAmount, 0) }}</div>
                    <div class="label">المبلغ المتبقي (ر.س)</div>
                    @if($remainingAmount <= 0)
                        <small class="text-success">تم السداد بالكامل</small>
                    @else
                        <small class="text-warning">مطلوب السداد</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- التنبيهات والمهام المعلقة -->
@if($pendingInvoices > 0 || $overdueInvoices > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bell text-warning ms-2"></i>
                    تنبيهات مهمة
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($pendingInvoices > 0)
                    <div class="col-md-6 mb-3">
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ number_format($pendingInvoices) }}</strong> فاتورة معلقة
                                <br><small>يرجى مراجعة فواتيرك والسداد</small>
                            </div>
                            <a href="{{ route('client.financial.invoices') }}?status=pending" class="btn btn-info btn-sm">
                                مراجعة
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($overdueInvoices > 0)
                    <div class="col-md-6 mb-3">
                        <div class="alert alert-danger d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ number_format($overdueInvoices) }}</strong> فاتورة متأخرة
                                <br><small>تحتاج لسداد عاجل</small>
                            </div>
                            <a href="{{ route('client.financial.invoices') }}?status=overdue" class="btn btn-danger btn-sm">
                                سداد فوري
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

<!-- الرسوم البيانية والأنشطة -->
<div class="row mb-4">
    <div class="col-lg-8 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line ms-2"></i>
                    مدفوعاتي الشهرية (آخر 6 أشهر)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="paymentsChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock ms-2"></i>
                    الفواتير القادمة
                </h5>
            </div>
            <div class="card-body">
                @forelse($upcomingInvoices as $invoice)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        <strong>{{ $invoice->invoice_number }}</strong>
                        <br>
                        <small class="text-muted">{{ $invoice->contract->product->getTranslatedTitle() }}</small>
                        <br>
                        <small class="text-info">
                            استحقاق: {{ $invoice->due_date ? $invoice->due_date->format('Y/m/d') : 'غير محدد' }}
                        </small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-warning">{{ number_format($invoice->remaining_amount, 2) }} ر.س</div>
                        <a href="{{ route('client.financial.invoices') }}" class="btn btn-outline-primary btn-sm">
                            عرض
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                    <p>لا توجد فواتير قادمة</p>
                    <small>جميع مدفوعاتك محدثة!</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- أحدث الأنشطة -->
<div class="row">
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-contract ms-2"></i>
                        أحدث عقودي
                    </h5>
                    <a href="{{ route('client.financial.contracts') }}" class="btn btn-outline-primary btn-sm">
                        عرض الكل
                    </a>
                </div>
            </div>
            <div class="card-body">
                @forelse($recentContracts as $contract)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        <strong>{{ $contract->contract_number }}</strong>
                        <br>
                        <small class="text-muted">{{ $contract->product->getTranslatedTitle() }}</small>
                        <br>
                        <small class="text-info">{{ $contract->facility->name }}</small>
                        <br>
                        <small class="text-muted">{{ $contract->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">{{ number_format($contract->total_amount, 2) }} ر.س</div>
                        @switch($contract->status)
                            @case('draft')
                                <span class="badge bg-warning">معلق</span>
                                @break
                            @case('active')
                                <span class="badge bg-success">نشط</span>
                                @break
                            @case('completed')
                                <span class="badge bg-info">مكتمل</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger">ملغي</span>
                                @break
                        @endswitch
                        <br>
                        <a href="{{ route('client.financial.contract-details', $contract->id) }}" class="btn btn-outline-primary btn-sm mt-1">
                            تفاصيل
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-file-contract"></i>
                    <h6>لا توجد عقود</h6>
                    <p>لم تقم بإنشاء أي عقود بعد</p>
                    <a href="{{ route('client.financial.offers') }}" class="btn btn-primary">
                        تصفح العروض
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card ms-2"></i>
                        أحدث مدفوعاتي
                    </h5>
                    <a href="{{ route('client.financial.payments') }}" class="btn btn-outline-primary btn-sm">
                        عرض الكل
                    </a>
                </div>
            </div>
            <div class="card-body">
                @forelse($recentPayments as $payment)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        <strong>{{ $payment->reference_number }}</strong>
                        <br>
                        <small class="text-muted">{{ $payment->contract->product->getTranslatedTitle() }}</small>
                        <br>
                        <small class="text-info">
                            @switch($payment->payment_method)
                                @case('cash')
                                    نقداً
                                    @break
                                @case('bank_transfer')
                                    تحويل بنكي
                                    @break
                                @case('credit_card')
                                    بطاقة ائتمان
                                    @break
                                @case('check')
                                    شيك
                                    @break
                                @default
                                    {{ $payment->payment_method }}
                            @endswitch
                        </small>
                        <br>
                        <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">{{ number_format($payment->amount, 2) }} ر.س</div>
                        @switch($payment->status)
                            @case('pending')
                                <span class="badge bg-warning">معلق</span>
                                @break
                            @case('confirmed')
                                <span class="badge bg-success">مؤكد</span>
                                @break
                            @case('failed')
                                <span class="badge bg-danger">فشل</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $payment->status }}</span>
                        @endswitch
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-credit-card"></i>
                    <h6>لا توجد مدفوعات</h6>
                    <p>لم تقم بأي مدفوعات بعد</p>
                    @if($totalContracts > 0)
                    <a href="{{ route('client.financial.contracts') }}" class="btn btn-primary">
                        دفع مستحقات
                    </a>
                    @endif
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- إجراءات سريعة -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt ms-2"></i>
                    إجراءات سريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('client.financial.offers') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                            <i class="fas fa-search fa-2x mb-2"></i>
                            <strong>تصفح العروض</strong>
                            <small class="text-muted">اكتشف المشاريع المتاحة</small>
                        </a>
                    </div>
                    
                    @if($pendingInvoices > 0)
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('client.financial.invoices') }}?status=pending" class="btn btn-outline-warning w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                            <i class="fas fa-file-invoice fa-2x mb-2"></i>
                            <strong>سداد الفواتير</strong>
                            <small class="text-muted">{{ $pendingInvoices }} فاتورة معلقة</small>
                        </a>
                    </div>
                    @endif
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('client.financial.summary') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <strong>ملخصي المالي</strong>
                            <small class="text-muted">تقرير شامل لأموالك</small>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                            <i class="fas fa-user-cog fa-2x mb-2"></i>
                            <strong>إعدادات الحساب</strong>
                            <small class="text-muted">تحديث بياناتك</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // رسم المدفوعات الشهرية
    const paymentsData = @json($monthlyPayments);
    const ctx = document.getElementById('paymentsChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: paymentsData.map(item => item.month),
            datasets: [{
                label: 'المدفوعات (ر.س)',
                data: paymentsData.map(item => item.amount),
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23, 162, 184, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#17a2b8',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
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
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            },
            elements: {
                point: {
                    hoverRadius: 8
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // تحديث الإحصائيات كل 5 دقائق
    setInterval(function() {
        // يمكن إضافة AJAX call لتحديث الإحصائيات
    }, 300000);

    // تحريك الأرقام عند التحميل
    $(document).ready(function() {
        $('.stats-card .number').each(function() {
            const $this = $(this);
            const countTo = parseInt($this.text().replace(/,/g, ''));
            
            $({ countNum: 0 }).animate({
                countNum: countTo
            }, {
                duration: 2000,
                easing: 'swing',
                step: function() {
                    $this.text(formatNumber(Math.floor(this.countNum)));
                },
                complete: function() {
                    $this.text(formatNumber(this.countNum));
                }
            });
        });
    });
</script>
@endsection
