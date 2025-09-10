@extends('facility.layouts.app')

@section('title', 'إحصائيات المستخدمين')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إحصائيات المستخدمين</h3>
                    <a href="{{ route('facility.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right"></i> العودة للقائمة
                    </a>
                </div>

                <div class="card-body">
                    <!-- إحصائيات عامة -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                    <h4 class="text-primary">{{ $stats['total_users'] }}</h4>
                                    <p class="text-muted">إجمالي المستخدمين</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                                    <h4 class="text-success">{{ $stats['total_users'] - $stats['total_users'] + $stats['total_users'] }}</h4>
                                    <p class="text-muted">المستخدمين النشطين</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-user-clock fa-2x text-warning mb-2"></i>
                                    <h4 class="text-warning">0</h4>
                                    <p class="text-muted">المستخدمين غير النشطين</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-crown fa-2x text-info mb-2"></i>
                                    <h4 class="text-info">1</h4>
                                    <p class="text-muted">مالك المنشأة</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- توزيع المستخدمين حسب الأدوار -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">توزيع المستخدمين حسب الأدوار</h5>
                                </div>
                                <div class="card-body">
                                    @if($stats['users_by_role']->count() > 0)
                                        @foreach($stats['users_by_role'] as $roleData)
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <h6 class="mb-0">{{ $roleData->name }}</h6>
                                                    <small class="text-muted">دور في المنشأة</small>
                                                </div>
                                                <div class="text-end">
                                                    <h4 class="text-primary mb-0">{{ $roleData->count }}</h4>
                                                    <small class="text-muted">مستخدم</small>
                                                </div>
                                            </div>
                                            <div class="progress mb-3" style="height: 8px;">
                                                <div class="progress-bar bg-primary" 
                                                     style="width: {{ ($roleData->count / $stats['total_users']) * 100 }}%"></div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted text-center">لا توجد بيانات</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">المستخدمين الجدد</h5>
                                </div>
                                <div class="card-body">
                                    @if($stats['recent_users']->count() > 0)
                                        @foreach($stats['recent_users'] as $user)
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar-sm me-3">
                                                    @if($user->avatar)
                                                        <img src="{{ asset($user->avatar) }}" alt="Avatar" class="rounded-circle" width="40" height="40">
                                                    @else
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                                </div>
                                                <div class="text-end">
                                                    @foreach($user->roles as $role)
                                                        @if($role->facility_id == $facility->id)
                                                            <span class="badge bg-secondary">{{ $role->getTranslatedName() }}</span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted text-center">لا توجد مستخدمين جدد</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات تفصيلية -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">إحصائيات تفصيلية</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>المستخدم</th>
                                                    <th>الدور</th>
                                                    <th>الحجوزات</th>
                                                    <th>العقود</th>
                                                    <th>المنتجات</th>
                                                    <th>التعليقات</th>
                                                    <th>تاريخ الانضمام</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($stats['recent_users']->count() > 0)
                                                    @foreach($stats['recent_users'] as $user)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-sm me-2">
                                                                        @if($user->avatar)
                                                                            <img src="{{ asset($user->avatar) }}" alt="Avatar" class="rounded-circle" width="30" height="30">
                                                                        @else
                                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                                                {{ substr($user->name, 0, 1) }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                                        <small class="text-muted">{{ $user->email }}</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @foreach($user->roles as $role)
                                                                    @if($role->facility_id == $facility->id)
                                                                        <span class="badge bg-secondary">{{ $role->getTranslatedName() }}</span>
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                            <td>{{ $user->bookings()->count() }}</td>
                                                            <td>{{ $user->contracts()->count() }}</td>
                                                            <td>{{ $user->products()->count() }}</td>
                                                            <td>{{ $user->comments()->count() }}</td>
                                                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">لا توجد بيانات</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
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

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 10px 10px 0 0 !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

.avatar-sm img {
    object-fit: cover;
}

.progress {
    background-color: #e9ecef;
    border-radius: 0.375rem;
}

.progress-bar {
    border-radius: 0.375rem;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>
@endpush
