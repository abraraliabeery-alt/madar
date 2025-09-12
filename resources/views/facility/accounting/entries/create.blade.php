@extends('facility.layouts.app')

@section('title', 'إنشاء قيد محاسبي جديد')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <h5 class="text-lg font-semibold text-gray-800 mb-0 flex items-center">
                        <i class="fas fa-plus text-blue-500 mr-2"></i>
                        إنشاء قيد محاسبي جديد
                    </h5>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('facility.accounting.entries.store') }}" id="createEntryForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- الحساب المدين -->
                            <div class="mb-4">
                                <label for="debit_account_id" class="block text-sm font-medium text-gray-700 mb-2">الحساب المدين <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('debit_account_id') border-red-500 @enderror" 
                                        id="debit_account_id" name="debit_account_id" required>
                                    <option value="">اختر الحساب المدين</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('debit_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_code }} - {{ $account->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('debit_account_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- الحساب الدائن -->
                            <div class="mb-4">
                                <label for="credit_account_id" class="block text-sm font-medium text-gray-700 mb-2">الحساب الدائن <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('credit_account_id') border-red-500 @enderror" 
                                        id="credit_account_id" name="credit_account_id" required>
                                    <option value="">اختر الحساب الدائن</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('credit_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_code }} - {{ $account->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('credit_account_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- المبلغ -->
                            <div class="mb-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">المبلغ <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" class="w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" 
                                           min="0.01" step="0.01" required>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">ريال</span>
                                    </div>
                                </div>
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- تاريخ القيد -->
                            <div class="mb-4">
                                <label for="entry_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ القيد <span class="text-red-500">*</span></label>
                                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('entry_date') border-red-500 @enderror" 
                                       id="entry_date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required>
                                @error('entry_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- الفترة المحاسبية -->
                            <div class="mb-4">
                                <label for="period_id" class="block text-sm font-medium text-gray-700 mb-2">الفترة المحاسبية <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('period_id') border-red-500 @enderror" 
                                        id="period_id" name="period_id" required>
                                    <option value="">اختر الفترة المحاسبية</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ old('period_id') == $period->id ? 'selected' : '' }}>
                                            {{ $period->period_name }} ({{ $period->formatted_period }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('period_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- معدل الضريبة -->
                            <div class="mb-4">
                                <label for="tax_rate_id" class="block text-sm font-medium text-gray-700 mb-2">معدل الضريبة</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tax_rate_id') border-red-500 @enderror" 
                                        id="tax_rate_id" name="tax_rate_id">
                                    <option value="">بدون ضريبة</option>
                                    @foreach($taxRates as $taxRate)
                                        <option value="{{ $taxRate->id }}" {{ old('tax_rate_id') == $taxRate->id ? 'selected' : '' }}>
                                            {{ $taxRate->tax_name }} ({{ $taxRate->formatted_rate }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tax_rate_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- الوصف -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف القيد <span class="text-red-500">*</span></label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" 
                                      id="description" name="description" rows="3" required 
                                      placeholder="أدخل وصفاً مفصلاً للقيد المحاسبي">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ملخص القيد -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg mb-6">
                            <div class="bg-gray-100 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                <h6 class="text-lg font-semibold text-gray-800 mb-0 flex items-center">
                                    <i class="fas fa-calculator text-blue-500 mr-2"></i>
                                    ملخص القيد المحاسبي
                                </h6>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <span class="text-gray-600">الحساب المدين:</span>
                                            <span id="debitAccountName" class="font-semibold text-gray-900">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">المبلغ المدين:</span>
                                            <span id="debitAmount" class="font-semibold text-blue-600">0.00 ريال</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <span class="text-gray-600">الحساب الدائن:</span>
                                            <span id="creditAccountName" class="font-semibold text-gray-900">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">المبلغ الدائن:</span>
                                            <span id="creditAmount" class="font-semibold text-green-600">0.00 ريال</span>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div class="text-center">
                                    <div class="flex justify-between mb-2">
                                        <span class="text-gray-600">إجمالي القيد:</span>
                                        <span id="totalAmount" class="font-semibold text-blue-600">0.00 ريال</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">الضريبة:</span>
                                        <span id="taxAmount" class="font-semibold text-yellow-600">0.00 ريال</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الإجراءات -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg mt-6">
                            <div class="flex justify-end space-x-4 rtl:space-x-reverse">
                                <a href="{{ route('facility.accounting.entries.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-arrow-right"></i>
                                    <span>إلغاء</span>
                                </a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-save"></i>
                                    <span>حفظ القيد</span>
                                </button>
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

