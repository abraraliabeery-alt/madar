@extends('layouts.app')

@section('title', 'إحصائيات المستخدم')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">إحصائيات المستخدم</li>
                    </ol>
                </div>
                <h4 class="page-title">إحصائيات المستخدم</h4>
            </div>
        </div>
    </div>

    <!-- Basic Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">عمر الحساب</p>
                            <h4 class="mb-2">{{ $stats['account_age'] }} يوم</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i>
                                </span>
                                منذ {{ $user->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-primary rounded">
                                <i class="mdi mdi-calendar-clock font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">آخر تسجيل دخول</p>
                            <h4 class="mb-2">{{ $stats['last_login'] }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-login"></i>
                                </span>
                                إجمالي {{ $stats['total_logins'] }} مرة
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-success rounded">
                                <i class="mdi mdi-login font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">اكتمال الملف الشخصي</p>
                            <h4 class="mb-2">{{ $stats['profile_completion'] }}%</h4>
                            <div class="progress progress-sm">
                                <div class="progress-bar" role="progressbar" style="width: {{ $stats['profile_completion'] }}%"></div>
                            </div>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-warning rounded">
                                <i class="mdi mdi-account-check font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">حالة الحساب</p>
                            <h4 class="mb-2">
                                @if($stats['email_verified'] && $stats['phone_verified'])
                                    <span class="badge bg-success">مفعل بالكامل</span>
                                @elseif($stats['email_verified'] || $stats['phone_verified'])
                                    <span class="badge bg-warning">مفعل جزئياً</span>
                                @else
                                    <span class="badge bg-danger">غير مفعل</span>
                                @endif
                            </h4>
                            <p class="text-muted mb-0">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-shield-check"></i>
                                </span>
                                @if($stats['two_factor_enabled'])
                                    المصادقة الثنائية مفعلة
                                @else
                                    المصادقة الثنائية غير مفعلة
                                @endif
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-info rounded">
                                <i class="mdi mdi-shield-check font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Statistics -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">نشاط المستخدم - آخر 12 شهر</h5>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">إحصائيات النشاط</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>إجمالي الأنشطة</h6>
                        <h4 class="text-primary">{{ number_format($activityStats['total_activities']) }}</h4>
                    </div>
                    
                    <div class="mb-3">
                        <h6>الأنشطة آخر 30 يوم</h6>
                        <h4 class="text-success">{{ number_format($activityStats['activities_last_30_days']) }}</h4>
                    </div>
                    
                    <div class="mb-3">
                        <h6>أكثر يوم نشاطاً</h6>
                        <h4 class="text-info">{{ $activityStats['most_active_day'] }}</h4>
                    </div>
                    
                    <div class="mb-3">
                        <h6>متوسط الأنشطة يومياً</h6>
                        <h4 class="text-warning">{{ $activityStats['average_activities_per_day'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Statistics -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">الإحصائيات المالية</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>إجمالي العقود</h6>
                                <h4 class="text-primary">{{ $financialStats['total_contracts'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>العقود النشطة</h6>
                                <h4 class="text-success">{{ $financialStats['active_contracts'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>إجمالي الفواتير</h6>
                                <h4 class="text-info">{{ $financialStats['total_invoices'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>الفواتير المدفوعة</h6>
                                <h4 class="text-success">{{ $financialStats['paid_invoices'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>إجمالي المدفوعات</h6>
                                <h4 class="text-warning">{{ $financialStats['total_payments'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>إجمالي المبلغ المدفوع</h6>
                                <h4 class="text-danger">{{ number_format($financialStats['total_amount_paid'], 2) }} ريال</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">إحصائيات التفاعل</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>إجمالي الإشعارات</h6>
                                <h4 class="text-primary">{{ $engagementStats['total_notifications'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>الإشعارات غير المقروءة</h6>
                                <h4 class="text-warning">{{ $engagementStats['unread_notifications'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>العقارات المفضلة</h6>
                                <h4 class="text-info">{{ $engagementStats['favorite_products'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>المرافق المفضلة</h6>
                                <h4 class="text-success">{{ $engagementStats['favorite_facilities'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>إجمالي الحجوزات</h6>
                                <h4 class="text-primary">{{ $engagementStats['total_bookings'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6>الحجوزات النشطة</h6>
                                <h4 class="text-success">{{ $engagementStats['active_bookings'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Actions and Recent Activity -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">أكثر الإجراءات</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>الإجراء</th>
                                    <th>العدد</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityStats['top_actions'] as $action)
                                <tr>
                                    <td>{{ $action['action'] }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $action['count'] }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">لا توجد أنشطة</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">النشاط الأخير</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>النشاط</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivity as $activity)
                                <tr>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">لا توجد أنشطة</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">سجل تسجيل الدخول</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>عنوان IP</th>
                                    <th>المتصفح</th>
                                    <th>نظام التشغيل</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginHistory as $login)
                                <tr>
                                    <td>{{ $login->ip_address }}</td>
                                    <td>{{ $login->user_agent ?? 'غير محدد' }}</td>
                                    <td>{{ $login->platform ?? 'غير محدد' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($login->created_at)->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">لا توجد سجلات</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export and Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">إجراءات إضافية</h5>
                        <div class="btn-group">
                            <a href="{{ route('user.statistics.export') }}" class="btn btn-primary">
                                <i class="mdi mdi-download"></i> تصدير الإحصائيات
                            </a>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#periodStatsModal">
                                <i class="mdi mdi-calendar-range"></i> إحصائيات فترة محددة
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Period Statistics Modal -->
<div class="modal fade" id="periodStatsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إحصائيات فترة محددة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="periodStatsForm">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">تاريخ البداية</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">تاريخ النهاية</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">عرض الإحصائيات</button>
                </form>
                
                <div id="periodStatsResult" class="mt-3" style="display: none;">
                    <h6>نتائج الإحصائيات:</h6>
                    <div id="periodStatsContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Activity Chart
    const ctx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($monthlyActivity['months']),
            datasets: [{
                label: 'الأنشطة',
                data: @json($monthlyActivity['activities']),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });

    // Period Statistics Form
    document.getElementById('periodStatsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("user.statistics.period") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('periodStatsContent').innerHTML = `
                <div class="row">
                    <div class="col-6">
                        <p><strong>الفترة:</strong> ${data.period.start_date} إلى ${data.period.end_date}</p>
                        <p><strong>عدد الأيام:</strong> ${data.period.days}</p>
                    </div>
                    <div class="col-6">
                        <p><strong>الأنشطة:</strong> ${data.activities}</p>
                        <p><strong>الإشعارات:</strong> ${data.notifications}</p>
                        <p><strong>الحجوزات:</strong> ${data.bookings}</p>
                        <p><strong>العقود:</strong> ${data.contracts}</p>
                        <p><strong>الفواتير:</strong> ${data.invoices}</p>
                        <p><strong>المدفوعات:</strong> ${data.payments}</p>
                    </div>
                </div>
            `;
            document.getElementById('periodStatsResult').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في جلب البيانات');
        });
    });
</script>
@endpush
