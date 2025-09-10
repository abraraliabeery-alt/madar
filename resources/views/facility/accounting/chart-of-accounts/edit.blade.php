@extends('facility.layouts.app')

@section('title', 'تعديل الحساب - ' . $account->account_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تعديل الحساب: {{ $account->account_name }}</h3>
                    <div>
                        <a href="{{ route('facility.accounting.chart-of-accounts.show', $account) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> عرض
                        </a>
                        <a href="{{ route('facility.accounting.chart-of-accounts.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('facility.accounting.chart-of-accounts.update', $account) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- معلومات أساسية -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">المعلومات الأساسية</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="account_code" class="form-label">كود الحساب <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('account_code') is-invalid @enderror" 
                                                   id="account_code" name="account_code" value="{{ old('account_code', $account->account_code) }}" required>
                                            @error('account_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">يجب أن يكون فريداً وغير مكرر</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="account_name" class="form-label">اسم الحساب <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('account_name') is-invalid @enderror" 
                                                   id="account_name" name="account_name" value="{{ old('account_name', $account->account_name) }}" required>
                                            @error('account_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="account_type" class="form-label">نوع الحساب <span class="text-danger">*</span></label>
                                            <select class="form-select @error('account_type') is-invalid @enderror" 
                                                    id="account_type" name="account_type" required>
                                                <option value="">اختر نوع الحساب</option>
                                                @foreach($accountTypes as $key => $value)
                                                    <option value="{{ $key }}" {{ old('account_type', $account->account_type) == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('account_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="account_category" class="form-label">فئة الحساب</label>
                                            <select class="form-select @error('account_category') is-invalid @enderror" 
                                                    id="account_category" name="account_category">
                                                <option value="">اختر فئة الحساب</option>
                                                @foreach($accountCategories as $key => $value)
                                                    <option value="{{ $key }}" {{ old('account_category', $account->account_category) == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('account_category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="parent_id" class="form-label">الحساب الأب</label>
                                            <select class="form-select @error('parent_id') is-invalid @enderror" 
                                                    id="parent_id" name="parent_id">
                                                <option value="">بدون حساب أب</option>
                                                @foreach($parentAccounts as $parentAccount)
                                                    @if($parentAccount->id != $account->id)
                                                        <option value="{{ $parentAccount->id }}" {{ old('parent_id', $account->parent_id) == $parentAccount->id ? 'selected' : '' }}>
                                                            {{ $parentAccount->account_code }} - {{ $parentAccount->account_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- إعدادات إضافية -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">الإعدادات الإضافية</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="normal_balance" class="form-label">الرصيد الطبيعي <span class="text-danger">*</span></label>
                                            <select class="form-select @error('normal_balance') is-invalid @enderror" 
                                                    id="normal_balance" name="normal_balance" required>
                                                <option value="">اختر الرصيد الطبيعي</option>
                                                <option value="debit" {{ old('normal_balance', $account->normal_balance) == 'debit' ? 'selected' : '' }}>مدين</option>
                                                <option value="credit" {{ old('normal_balance', $account->normal_balance) == 'credit' ? 'selected' : '' }}>دائن</option>
                                            </select>
                                            @error('normal_balance')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="opening_balance" class="form-label">الرصيد الافتتاحي</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" class="form-control @error('opening_balance') is-invalid @enderror" 
                                                       id="opening_balance" name="opening_balance" value="{{ old('opening_balance', $account->opening_balance) }}">
                                                <span class="input-group-text">ر.س</span>
                                            </div>
                                            @error('opening_balance')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">تغيير الرصيد الافتتاحي سيؤثر على الرصيد الحالي</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">وصف الحساب</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="3">{{ old('description', $account->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                                       {{ old('is_active', $account->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    حساب نشط
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_system" name="is_system" value="1" 
                                                       {{ old('is_system', $account->is_system) ? 'checked' : '' }}
                                                       {{ $account->is_system ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="is_system">
                                                    حساب نظام (لا يمكن حذفه)
                                                </label>
                                                @if($account->is_system)
                                                    <div class="form-text text-muted">هذا حساب نظام ولا يمكن تغيير نوعه</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- تحذيرات -->
                        @if($account->entries_count > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-exclamation-triangle"></i> تحذير
                                        </h6>
                                        <p class="mb-0">
                                            هذا الحساب يحتوي على {{ $account->entries_count }} حركة محاسبية. 
                                            تغيير نوع الحساب أو الرصيد الطبيعي قد يؤثر على التقارير المالية.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($account->children_count > 0)
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-info-circle"></i> ملاحظة
                                        </h6>
                                        <p class="mb-0">
                                            هذا الحساب يحتوي على {{ $account->children_count }} حساب فرعي. 
                                            تغيير نوع الحساب قد يؤثر على الحسابات الفرعية.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- أزرار الإجراءات -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('facility.accounting.chart-of-accounts.show', $account) }}" class="btn btn-outline-secondary">
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
    // Auto-fill normal balance based on account type
    document.getElementById('account_type').addEventListener('change', function() {
        const accountType = this.value;
        const normalBalanceSelect = document.getElementById('normal_balance');
        
        // Clear previous selection
        normalBalanceSelect.value = '';
        
        // Set default normal balance based on account type
        switch(accountType) {
            case 'asset':
            case 'expense':
                normalBalanceSelect.value = 'debit';
                break;
            case 'liability':
            case 'equity':
            case 'revenue':
                normalBalanceSelect.value = 'credit';
                break;
        }
    });

    // Auto-generate account code based on parent and type
    document.getElementById('parent_id').addEventListener('change', function() {
        const parentId = this.value;
        const accountCodeInput = document.getElementById('account_code');
        
        if (parentId) {
            // If parent is selected, suggest a sub-account code
            const parentOption = this.options[this.selectedIndex];
            const parentCode = parentOption.text.split(' - ')[0];
            
            // Suggest next available sub-code
            accountCodeInput.placeholder = parentCode + '.XX';
        } else {
            accountCodeInput.placeholder = '';
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const accountCode = document.getElementById('account_code').value;
        const accountName = document.getElementById('account_name').value;
        const accountType = document.getElementById('account_type').value;
        const normalBalance = document.getElementById('normal_balance').value;
        
        if (!accountCode || !accountName || !accountType || !normalBalance) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
            return false;
        }

        // Check if account type is being changed and there are entries
        const originalAccountType = '{{ $account->account_type }}';
        if (accountType !== originalAccountType && {{ $account->entries_count }} > 0) {
            if (!confirm('تغيير نوع الحساب قد يؤثر على التقارير المالية. هل أنت متأكد من المتابعة؟')) {
                e.preventDefault();
                return false;
            }
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

.input-group-text {
    background-color: #e9ecef;
    border: 1px solid #ced4da;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
}

.alert {
    border-radius: 0.5rem;
}

.alert-heading {
    font-weight: 600;
    margin-bottom: 0.5rem;
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
