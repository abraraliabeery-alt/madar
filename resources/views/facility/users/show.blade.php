@extends('facility.layouts.app')

@section('title', 'تفاصيل المستخدم - ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تفاصيل المستخدم: {{ $user->name }}</h3>
                    <div>
                        <a href="{{ route('facility.users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('facility.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- معلومات المستخدم -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">معلومات المستخدم</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">الاسم الكامل:</label>
                                                <p class="form-control-plaintext">{{ $user->name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">البريد الإلكتروني:</label>
                                                <p class="form-control-plaintext">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">رقم الهاتف:</label>
                                                <p class="form-control-plaintext">{{ $user->phone_number }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">الحالة:</label>
                                                <p class="form-control-plaintext">
                                                    @if($user->email_verified_at)
                                                        <span class="badge bg-success">مفعل</span>
                                                    @else
                                                        <span class="badge bg-warning">غير مفعل</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($user->bank_account)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">رقم الحساب البنكي:</label>
                                                    <p class="form-control-plaintext">{{ $user->bank_account }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">البنك:</label>
                                                    <p class="form-control-plaintext">{{ $user->bank->name ?? 'غير محدد' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">تاريخ الانضمام:</label>
                                                <p class="form-control-plaintext">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">آخر تحديث:</label>
                                                <p class="form-control-plaintext">{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- صورة المستخدم والأدوار -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">الصورة والأدوار</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($user->avatar)
                                        <img src="{{ asset($user->avatar) }}" alt="Avatar" class="rounded-circle mb-3" width="100" height="100">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2rem;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    
                                    @if($facility->owner_user_id == $user->id)
                                        <div class="alert alert-success">
                                            <i class="fas fa-crown"></i>
                                            <strong>مالك المنشأة</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">الأدوار في المنشأة</h5>
                                </div>
                                <div class="card-body">
                                    @if($userRoles->count() > 0)
                                        @foreach($userRoles as $role)
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>{{ $role->getTranslatedName() }}</span>
                                                @if($facility->owner_user_id != $user->id)
                                                    <form method="POST" action="{{ route('facility.users.remove-role', ['user' => $user, 'role' => $role]) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('هل أنت متأكد من إلغاء هذا الدور؟')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">لا توجد أدوار</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إضافة دور جديد -->
                    @if($facility->owner_user_id != $user->id)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">إضافة دور جديد</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('facility.users.assign-role', $user) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <select name="role_id" class="form-select" required>
                                                        <option value="">اختر الدور</option>
                                                        @foreach($facility->roles as $role)
                                                            @if(!$userRoles->contains('id', $role->id))
                                                                <option value="{{ $role->id }}">{{ $role->getTranslatedName() }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> إضافة الدور
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- إحصائيات المستخدم -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">إحصائيات المستخدم</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-primary">{{ $user->bookings()->count() }}</h4>
                                                <p class="text-muted">إجمالي الحجوزات</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-success">{{ $user->contracts()->count() }}</h4>
                                                <p class="text-muted">إجمالي العقود</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-info">{{ $user->products()->count() }}</h4>
                                                <p class="text-muted">إجمالي المنتجات</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-warning">{{ $user->comments()->count() }}</h4>
                                                <p class="text-muted">إجمالي التعليقات</p>
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

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control-plaintext {
    padding: 0.375rem 0;
    margin-bottom: 0;
    line-height: 1.5;
    color: #212529;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
}

.alert {
    border-radius: 0.5rem;
    border: none;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
}
</style>
@endpush
