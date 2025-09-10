@extends('facility.layouts.app')

@section('title', 'إضافة مستخدم جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إضافة مستخدم جديد</h3>
                    <a href="{{ route('facility.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right"></i> العودة للقائمة
                    </a>
                </div>

                <div class="card-body">
                    <!-- تبويبات -->
                    <ul class="nav nav-tabs" id="userTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="new-user-tab" data-bs-toggle="tab" data-bs-target="#new-user" type="button" role="tab">
                                <i class="fas fa-user-plus"></i> إنشاء مستخدم جديد
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="existing-user-tab" data-bs-toggle="tab" data-bs-target="#existing-user" type="button" role="tab">
                                <i class="fas fa-user-check"></i> إضافة مستخدم موجود
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="userTabsContent">
                        <!-- إنشاء مستخدم جديد -->
                        <div class="tab-pane fade show active" id="new-user" role="tabpanel">
                            <form method="POST" action="{{ route('facility.users.store') }}" class="mt-4">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">المعلومات الشخصية</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                           id="name" name="name" value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                           id="email" name="email" value="{{ old('email') }}" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="phone_number" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                                           id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                                                    @error('phone_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                           id="password" name="password" required>
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" 
                                                           id="password_confirmation" name="password_confirmation" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">المعلومات الإضافية</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label for="role_id" class="form-label">الدور <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('role_id') is-invalid @enderror" 
                                                            id="role_id" name="role_id" required>
                                                        <option value="">اختر الدور</option>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                                {{ $role->getTranslatedName() }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('role_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="bank_account" class="form-label">رقم الحساب البنكي</label>
                                                    <input type="text" class="form-control @error('bank_account') is-invalid @enderror" 
                                                           id="bank_account" name="bank_account" value="{{ old('bank_account') }}">
                                                    @error('bank_account')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="bank_id" class="form-label">البنك</label>
                                                    <select class="form-select @error('bank_id') is-invalid @enderror" 
                                                            id="bank_id" name="bank_id">
                                                        <option value="">اختر البنك</option>
                                                        @foreach(\App\Models\Bank::all() as $bank)
                                                            <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                                                {{ $bank->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('bank_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('facility.users.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times"></i> إلغاء
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> إنشاء المستخدم
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- إضافة مستخدم موجود -->
                        <div class="tab-pane fade" id="existing-user" role="tabpanel">
                            <form method="POST" action="{{ route('facility.users.add-existing') }}" class="mt-4">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">اختيار المستخدم</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label for="user_id" class="form-label">المستخدم <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('user_id') is-invalid @enderror" 
                                                            id="user_id" name="user_id" required>
                                                        <option value="">اختر المستخدم</option>
                                                        @foreach($availableUsers as $user)
                                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                                {{ $user->name }} ({{ $user->email }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('user_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="role_id_existing" class="form-label">الدور <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('role_id') is-invalid @enderror" 
                                                            id="role_id_existing" name="role_id" required>
                                                        <option value="">اختر الدور</option>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                                {{ $role->getTranslatedName() }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('role_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">معلومات إضافية</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle"></i>
                                                    <strong>ملاحظة:</strong> سيتم إضافة المستخدم المحدد إلى المنشأة مع الدور المختار.
                                                </div>
                                                
                                                @if($availableUsers->count() == 0)
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        <strong>لا توجد مستخدمين متاحين</strong><br>
                                                        جميع المستخدمين المسجلين في النظام ينتمون بالفعل لهذه المنشأة.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('facility.users.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times"></i> إلغاء
                                            </a>
                                            <button type="submit" class="btn btn-primary" {{ $availableUsers->count() == 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-user-plus"></i> إضافة المستخدم
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('يرجى ملء جميع الحقول المطلوبة');
            }
        });
    });

    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        
        if (password !== confirmation) {
            this.setCustomValidity('كلمة المرور غير متطابقة');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endpush

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

.nav-tabs {
    border-bottom: 2px solid #e9ecef;
}

.nav-tabs .nav-link {
    border: none;
    border-radius: 0.375rem 0.375rem 0 0;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #495057;
    background-color: #fff;
    border-bottom: 2px solid #0d6efd;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    color: #495057;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control, .form-select {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
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
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush
