@extends('facility.layouts.app')

@section('title', 'تعديل المستخدم - ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تعديل المستخدم: {{ $user->name }}</h3>
                    <div>
                        <a href="{{ route('facility.users.show', $user) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> عرض
                        </a>
                        <a href="{{ route('facility.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('facility.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- المعلومات الشخصية -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">المعلومات الشخصية</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone_number" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                                   id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                                            <input type="password" class="form-control" 
                                                   id="password_confirmation" name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- المعلومات الإضافية -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">المعلومات الإضافية</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="bank_account" class="form-label">رقم الحساب البنكي</label>
                                            <input type="text" class="form-control @error('bank_account') is-invalid @enderror" 
                                                   id="bank_account" name="bank_account" value="{{ old('bank_account', $user->bank_account) }}">
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
                                                    <option value="{{ $bank->id }}" {{ old('bank_id', $user->bank_id) == $bank->id ? 'selected' : '' }}>
                                                        {{ $bank->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('bank_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">الحالة</label>
                                            <div class="form-control-plaintext">
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">مفعل</span>
                                                @else
                                                    <span class="badge bg-warning">غير مفعل</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($facility->owner_user_id == $user->id)
                                            <div class="alert alert-info">
                                                <i class="fas fa-crown"></i>
                                                <strong>مالك المنشأة</strong><br>
                                                <small>لا يمكن تعديل أدوار مالك المنشأة</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- إدارة الأدوار -->
                        @if($facility->owner_user_id != $user->id)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">إدارة الأدوار</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">الأدوار الحالية</label>
                                                <div class="row">
                                                    @foreach($roles as $role)
                                                        <div class="col-md-4">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       id="role_{{ $role->id }}" name="role_ids[]" 
                                                                       value="{{ $role->id }}"
                                                                       {{ in_array($role->id, $userRoles) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                                                    {{ $role->getTranslatedName() }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @error('role_ids')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- أزرار الإجراءات -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('facility.users.show', $user) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> حفظ التعديلات
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
@endsection

@push('scripts')
<script>
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        
        if (password && password !== passwordConfirmation) {
            e.preventDefault();
            alert('كلمة المرور غير متطابقة');
            return false;
        }

        // Check if at least one role is selected (if not owner)
        @if($facility->owner_user_id != $user->id)
            const roleCheckboxes = document.querySelectorAll('input[name="role_ids[]"]');
            const checkedRoles = Array.from(roleCheckboxes).some(checkbox => checkbox.checked);
            
            if (!checkedRoles) {
                e.preventDefault();
                alert('يرجى اختيار دور واحد على الأقل');
                return false;
            }
        @endif
    });

    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        
        if (password && password !== confirmation) {
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

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
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
