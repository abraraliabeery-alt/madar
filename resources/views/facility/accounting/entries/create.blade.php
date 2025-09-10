@extends('facility.layouts.app')

@section('title', 'إنشاء قيد محاسبي جديد')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus text-primary"></i>
                        إنشاء قيد محاسبي جديد
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('facility.accounting.entries.store') }}" id="createEntryForm">
                        @csrf
                        
                        <div class="row">
                            <!-- الحساب المدين -->
                            <div class="col-md-6 mb-3">
                                <label for="debit_account_id" class="form-label required">الحساب المدين *</label>
                                <select class="form-select @error('debit_account_id') is-invalid @enderror" 
                                        id="debit_account_id" name="debit_account_id" required>
                                    <option value="">اختر الحساب المدين</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('debit_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_code }} - {{ $account->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('debit_account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الحساب الدائن -->
                            <div class="col-md-6 mb-3">
                                <label for="credit_account_id" class="form-label required">الحساب الدائن *</label>
                                <select class="form-select @error('credit_account_id') is-invalid @enderror" 
                                        id="credit_account_id" name="credit_account_id" required>
                                    <option value="">اختر الحساب الدائن</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('credit_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_code }} - {{ $account->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('credit_account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- المبلغ -->
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label required">المبلغ *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" 
                                           min="0.01" step="0.01" required>
                                    <span class="input-group-text">ريال</span>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- تاريخ القيد -->
                            <div class="col-md-6 mb-3">
                                <label for="entry_date" class="form-label required">تاريخ القيد *</label>
                                <input type="date" class="form-control @error('entry_date') is-invalid @enderror" 
                                       id="entry_date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required>
                                @error('entry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- الفترة المحاسبية -->
                            <div class="col-md-6 mb-3">
                                <label for="period_id" class="form-label required">الفترة المحاسبية *</label>
                                <select class="form-select @error('period_id') is-invalid @enderror" 
                                        id="period_id" name="period_id" required>
                                    <option value="">اختر الفترة المحاسبية</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ old('period_id') == $period->id ? 'selected' : '' }}>
                                            {{ $period->period_name }} ({{ $period->formatted_period }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('period_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- معدل الضريبة -->
                            <div class="col-md-6 mb-3">
                                <label for="tax_rate_id" class="form-label">معدل الضريبة</label>
                                <select class="form-select @error('tax_rate_id') is-invalid @enderror" 
                                        id="tax_rate_id" name="tax_rate_id">
                                    <option value="">بدون ضريبة</option>
                                    @foreach($taxRates as $taxRate)
                                        <option value="{{ $taxRate->id }}" {{ old('tax_rate_id') == $taxRate->id ? 'selected' : '' }}>
                                            {{ $taxRate->tax_name }} ({{ $taxRate->formatted_rate }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tax_rate_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- الوصف -->
                        <div class="mb-3">
                            <label for="description" class="form-label required">وصف القيد *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required 
                                      placeholder="أدخل وصفاً مفصلاً للقيد المحاسبي">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ملخص القيد -->
                        <div class="card bg-light mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-calculator text-info"></i>
                                    ملخص القيد المحاسبي
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span>الحساب المدين:</span>
                                            <span id="debitAccountName" class="fw-bold">-</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>المبلغ المدين:</span>
                                            <span id="debitAmount" class="fw-bold text-primary">0.00 ريال</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span>الحساب الدائن:</span>
                                            <span id="creditAccountName" class="fw-bold">-</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>المبلغ الدائن:</span>
                                            <span id="creditAmount" class="fw-bold text-success">0.00 ريال</span>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <div class="d-flex justify-content-between">
                                        <span>إجمالي القيد:</span>
                                        <span id="totalAmount" class="fw-bold text-info">0.00 ريال</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>الضريبة:</span>
                                        <span id="taxAmount" class="fw-bold text-warning">0.00 ريال</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الإجراءات -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('facility.accounting.entries.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-right"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ القيد
                            </button>
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
document.addEventListener('DOMContentLoaded', function() {
    const debitSelect = document.getElementById('debit_account_id');
    const creditSelect = document.getElementById('credit_account_id');
    const amountInput = document.getElementById('amount');
    const taxRateSelect = document.getElementById('tax_rate_id');
    
    const debitAccountName = document.getElementById('debitAccountName');
    const creditAccountName = document.getElementById('creditAccountName');
    const debitAmount = document.getElementById('debitAmount');
    const creditAmount = document.getElementById('creditAmount');
    const totalAmount = document.getElementById('totalAmount');
    const taxAmount = document.getElementById('taxAmount');
    
    // بيانات الحسابات
    const accounts = @json($accounts->keyBy('id'));
    const taxRates = @json($taxRates->keyBy('id'));
    
    function updateSummary() {
        const amount = parseFloat(amountInput.value) || 0;
        const selectedTaxRate = taxRates[taxRateSelect.value];
        
        // حساب الضريبة
        let tax = 0;
        if (selectedTaxRate) {
            if (selectedTaxRate.calculation_method === 'percentage') {
                tax = amount * selectedTaxRate.rate;
            } else {
                tax = selectedTaxRate.fixed_amount || 0;
            }
        }
        
        // تحديث أسماء الحسابات
        const debitAccount = accounts[debitSelect.value];
        const creditAccount = accounts[creditSelect.value];
        
        debitAccountName.textContent = debitAccount ? debitAccount.account_name : '-';
        creditAccountName.textContent = creditAccount ? creditAccount.account_name : '-';
        
        // تحديث المبالغ
        debitAmount.textContent = amount.toFixed(2) + ' ريال';
        creditAmount.textContent = amount.toFixed(2) + ' ريال';
        totalAmount.textContent = amount.toFixed(2) + ' ريال';
        taxAmount.textContent = tax.toFixed(2) + ' ريال';
    }
    
    // إضافة مستمعي الأحداث
    debitSelect.addEventListener('change', updateSummary);
    creditSelect.addEventListener('change', updateSummary);
    amountInput.addEventListener('input', updateSummary);
    taxRateSelect.addEventListener('change', updateSummary);
    
    // تحديث أولي
    updateSummary();
    
    // التحقق من صحة النموذج
    document.getElementById('createEntryForm').addEventListener('submit', function(e) {
        const debitAccountId = debitSelect.value;
        const creditAccountId = creditSelect.value;
        const amount = amountInput.value;
        const description = document.getElementById('description').value;
        const periodId = document.getElementById('period_id').value;
        const entryDate = document.getElementById('entry_date').value;
        
        if (!debitAccountId || !creditAccountId || !amount || !description || !periodId || !entryDate) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
            return false;
        }
        
        if (debitAccountId === creditAccountId) {
            e.preventDefault();
            alert('لا يمكن أن يكون الحساب المدين والحساب الدائن نفس الحساب');
            return false;
        }
        
        if (parseFloat(amount) <= 0) {
            e.preventDefault();
            alert('يجب أن يكون المبلغ أكبر من صفر');
            return false;
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.required::after {
    content: " *";
    color: #dc3545;
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

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

.input-group-text {
    background: #f8f9fa;
    border-color: #ced4da;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush
