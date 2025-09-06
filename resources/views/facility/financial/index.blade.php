@extends('layouts.facility')

@section('title', 'التقارير المالية')

@section('content')
<div class="container-fluid">
    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ number_format($quickStats['total_revenue'], 2) }}</h4>
                            <p class="mb-0">إجمالي الإيرادات</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ number_format($quickStats['total_payments'], 2) }}</h4>
                            <p class="mb-0">إجمالي المدفوعات</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-credit-card fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $quickStats['total_contracts'] }}</h4>
                            <p class="mb-0">إجمالي العقود</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-file-contract fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ number_format($quickStats['collection_rate'], 1) }}%</h4>
                            <p class="mb-0">معدل التحصيل</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تقرير الشهر الحالي -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تقرير الشهر الحالي</h3>
                    <div>
                        <a href="{{ route('facility.financial.export') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> تصدير التقرير
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>ملخص الإيرادات</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td>إجمالي الإيرادات:</td>
                                    <td class="text-end"><strong>{{ number_format($monthlyReport['summary']['total_revenue'], 2) }} ريال</strong></td>
                                </tr>
                                <tr>
                                    <td>إجمالي المدفوعات:</td>
                                    <td class="text-end"><strong>{{ number_format($monthlyReport['summary']['total_payments'], 2) }} ريال</strong></td>
                                </tr>
                                <tr>
                                    <td>إجمالي العمولات:</td>
                                    <td class="text-end"><strong>{{ number_format($monthlyReport['summary']['total_commissions'], 2) }} ريال</strong></td>
                                </tr>
                                <tr class="table-success">
                                    <td>صافي الدخل:</td>
                                    <td class="text-end"><strong>{{ number_format($monthlyReport['summary']['net_income'], 2) }} ريال</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>معدلات الأداء</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td>معدل التحصيل:</td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $monthlyReport['summary']['collection_rate'] >= 80 ? 'success' : ($monthlyReport['summary']['collection_rate'] >= 60 ? 'warning' : 'danger') }}">
                                            {{ number_format($monthlyReport['summary']['collection_rate'], 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>العقود النشطة:</td>
                                    <td class="text-end"><strong>{{ $quickStats['active_contracts'] }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- روابط التقارير التفصيلية -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                    <h5>تقرير الإيرادات</h5>
                    <p class="text-muted">تفاصيل الإيرادات حسب النوع والشهر</p>
                    <a href="{{ route('facility.financial.revenue') }}" class="btn btn-primary">عرض التقرير</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>
                    <h5>تقرير المدفوعات</h5>
                    <p class="text-muted">تفاصيل المدفوعات وطرق الدفع</p>
                    <a href="{{ route('facility.financial.payments') }}" class="btn btn-success">عرض التقرير</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-file-invoice fa-3x text-info mb-3"></i>
                    <h5>تقرير الفواتير</h5>
                    <p class="text-muted">حالة الفواتير والمبالغ المستحقة</p>
                    <a href="{{ route('facility.financial.invoices') }}" class="btn btn-info">عرض التقرير</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-file-contract fa-3x text-warning mb-3"></i>
                    <h5>تقرير العقود</h5>
                    <p class="text-muted">تفاصيل العقود والأداء</p>
                    <a href="{{ route('facility.financial.contracts') }}" class="btn btn-warning">عرض التقرير</a>
                </div>
            </div>
        </div>
    </div>

    <!-- تقارير إضافية -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-secondary mb-3"></i>
                    <h5>تقرير العملاء</h5>
                    <p class="text-muted">أداء العملاء والمستحقات</p>
                    <form action="{{ route('facility.financial.customer') }}" method="GET" class="d-inline">
                        <select name="customer_id" class="form-select mb-2" required>
                            <option value="">اختر العميل</option>
                            @foreach(\App\Models\User::where('primary_role', 'user')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-secondary">عرض التقرير</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-user-tie fa-3x text-dark mb-3"></i>
                    <h5>تقرير الملاك</h5>
                    <p class="text-muted">أداء الملاك والأرباح</p>
                    <form action="{{ route('facility.financial.owner') }}" method="GET" class="d-inline">
                        <select name="owner_id" class="form-select mb-2" required>
                            <option value="">اختر المالك</option>
                            @foreach(\App\Models\User::where('primary_role', 'owner')->get() as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-dark">عرض التقرير</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                    <h5>التقارير الزمنية</h5>
                    <p class="text-muted">تقارير شهرية وسنوية</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('facility.financial.monthly') }}" class="btn btn-outline-primary">تقرير شهري</a>
                        <a href="{{ route('facility.financial.yearly') }}" class="btn btn-outline-primary">تقرير سنوي</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
