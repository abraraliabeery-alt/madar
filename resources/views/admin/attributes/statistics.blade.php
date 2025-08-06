@extends('admin.layouts.app')

@section('title', 'إحصائيات الخصائص')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">إحصائيات الخصائص</h4>
                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> رجوع
                    </a>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0">{{ $totalAttributes }}</h3>
                                            <p class="mb-0">إجمالي الخصائص</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-tags fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0">{{ $requiredAttributes }}</h3>
                                            <p class="mb-0">الخصائص الإلزامية</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0">{{ $optionalAttributes }}</h3>
                                            <p class="mb-0">الخصائص الاختيارية</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0">{{ count($attributesByCategory) }}</h3>
                                            <p class="mb-0">الفئات المستخدمة</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-folder fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">توزيع الخصائص حسب الفئات</h6>
                                </div>
                                <div class="card-body">
                                    @if(count($attributesByCategory) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>الفئة</th>
                                                        <th>عدد الخصائص</th>
                                                        <th>النسبة</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($attributesByCategory as $categoryName => $attributes)
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-secondary">{{ $categoryName ?? 'غير محدد' }}</span>
                                                        </td>
                                                        <td>{{ $attributes->count() }}</td>
                                                        <td>
                                                            @php
                                                                $percentage = $totalAttributes > 0 ? round(($attributes->count() / $totalAttributes) * 100, 1) : 0;
                                                            @endphp
                                                            <div class="progress" style="height: 20px;">
                                                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%">
                                                                    {{ $percentage }}%
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-chart-pie fa-3x mb-3"></i>
                                            <p>لا توجد بيانات للعرض</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">توزيع أنواع الخصائص</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $typeStats = \App\Models\Attribute::selectRaw('type, COUNT(*) as count')
                                            ->groupBy('type')
                                            ->get();
                                    @endphp
                                    
                                    @if($typeStats->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>النوع</th>
                                                        <th>العدد</th>
                                                        <th>النسبة</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($typeStats as $stat)
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-info">{{ $stat->type }}</span>
                                                        </td>
                                                        <td>{{ $stat->count }}</td>
                                                        <td>
                                                            @php
                                                                $percentage = $totalAttributes > 0 ? round(($stat->count / $totalAttributes) * 100, 1) : 0;
                                                            @endphp
                                                            <div class="progress" style="height: 20px;">
                                                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percentage }}%">
                                                                    {{ $percentage }}%
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                            <p>لا توجد بيانات للعرض</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">أحدث الخصائص المضافة</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $recentAttributes = \App\Models\Attribute::with(['category', 'translations'])
                                            ->latest()
                                            ->take(10)
                                            ->get();
                                    @endphp
                                    
                                    @if($recentAttributes->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>الاسم</th>
                                                        <th>النوع</th>
                                                        <th>الفئة</th>
                                                        <th>الحالة</th>
                                                        <th>تاريخ الإضافة</th>
                                                        <th>الإجراءات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentAttributes as $attribute)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if($attribute->icon)
                                                                    <img src="{{ Storage::url($attribute->icon) }}" alt="Icon" class="me-2" style="width: 20px; height: 20px;">
                                                                @endif
                                                                <span>{{ $attribute->translations->first()->name ?? 'N/A' }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">{{ $attribute->type }}</span>
                                                        </td>
                                                        <td>
                                                            @if($attribute->category)
                                                                <span class="badge bg-secondary">{{ $attribute->category->name }}</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($attribute->required)
                                                                <span class="badge bg-danger">إلزامية</span>
                                                            @else
                                                                <span class="badge bg-warning">اختيارية</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $attribute->created_at->format('Y-m-d') }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.attributes.show', $attribute) }}" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-clock fa-3x mb-3"></i>
                                            <p>لا توجد خصائص حديثة</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 