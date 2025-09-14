@extends('facility.layouts.app')

@section('title', 'تعديل الفاتورة')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('facility.invoices.index') }}">الفواتير</a></li>
    <li class="breadcrumb-item active">تعديل الفاتورة</li>
@endsection

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-4 rounded-t-lg">
            <h3 class="text-xl font-semibold">تعديل الفاتورة - {{ $invoice->invoice_number ?: 'INV-' . $invoice->id }}</h3>
        </div>

        <form method="POST" action="{{ route('facility.invoices.update', $invoice) }}" id="invoiceForm" class="p-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- معلومات أساسية -->
                <div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                        المعلومات الأساسية
                    </h5>
                    
                    <!-- العقد -->
                    <div class="mb-6">
                        <label for="contract_id" class="block text-sm font-medium text-gray-700 mb-2">
                            العقد <span class="text-red-500">*</span>
                        </label>
                        <select name="contract_id" id="contract_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('contract_id') border-red-500 @enderror" required>
                            <option value="">اختر العقد</option>
                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}" 
                                        data-product="{{ $contract->product->getTranslatedTitle() }}"
                                        data-user="{{ $contract->user->name }}"
                                        data-total="{{ $contract->total_amount }}"
                                        data-remaining="{{ $contract->remaining_amount }}"
                                        {{ old('contract_id', $invoice->contract_id) == $contract->id ? 'selected' : '' }}>
                                    {{ $contract->contract_number ?: 'CON-' . $contract->id }} - 
                                    {{ $contract->product->getTranslatedTitle() }} - 
                                    {{ $contract->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('contract_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- نوع الفاتورة -->
                    <div class="mb-6">
                        <label for="invoice_type" class="block text-sm font-medium text-gray-700 mb-2">
                            نوع الفاتورة <span class="text-red-500">*</span>
                        </label>
                        <select name="invoice_type" id="invoice_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('invoice_type') border-red-500 @enderror" required>
                            <option value="">اختر نوع الفاتورة</option>
                            @foreach($invoiceTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('invoice_type', $invoice->invoice_type) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- المبلغ -->
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            المبلغ <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('amount', $invoice->amount) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-12 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('amount') border-red-500 @enderror" 
                                   required>
                            <span class="absolute left-3 top-2 text-gray-500" id="amount_currency">SAR</span>
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- تاريخ الاستحقاق -->
                    <div class="mb-6">
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                            تاريخ الاستحقاق <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="due_date" 
                               id="due_date" 
                               value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('due_date') border-red-500 @enderror" 
                               required>
                        @error('due_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- تفاصيل إضافية -->
                <div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-cog text-primary-600 mr-2"></i>
                        التفاصيل الإضافية
                    </h5>

                    <!-- رقم القسط -->
                    <div class="mb-6">
                        <label for="installment_number" class="block text-sm font-medium text-gray-700 mb-2">
                            رقم القسط
                        </label>
                        <input type="number" 
                               name="installment_number" 
                               id="installment_number" 
                               min="1" 
                               value="{{ old('installment_number', $invoice->installment_number) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('installment_number') border-red-500 @enderror">
                        @error('installment_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- مبلغ القسط -->
                    <div class="mb-6">
                        <label for="installment_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            مبلغ القسط
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="installment_amount" 
                                   id="installment_amount" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('installment_amount', $invoice->installment_amount) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-12 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('installment_amount') border-red-500 @enderror">
                            <span class="absolute left-3 top-2 text-gray-500" id="installment_currency">SAR</span>
                        </div>
                        @error('installment_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- رسوم التأخير -->
                    <div class="mb-6">
                        <label for="late_fee_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            رسوم التأخير
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="late_fee_amount" 
                                   id="late_fee_amount" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('late_fee_amount', $invoice->late_fee_amount) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-12 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('late_fee_amount') border-red-500 @enderror">
                            <span class="absolute left-3 top-2 text-gray-500" id="late_fee_currency">SAR</span>
                        </div>
                        @error('late_fee_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الخصم -->
                    <div class="mb-6">
                        <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            الخصم
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="discount_amount" 
                                   id="discount_amount" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('discount_amount', $invoice->discount_amount) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-12 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('discount_amount') border-red-500 @enderror">
                            <span class="absolute left-3 top-2 text-gray-500" id="discount_currency">SAR</span>
                        </div>
                        @error('discount_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- معدل الضريبة -->
                    <div class="mb-6">
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">
                            معدل الضريبة (%)
                        </label>
                        <input type="number" 
                               name="tax_rate" 
                               id="tax_rate" 
                               step="0.01" 
                               min="0" 
                               max="100" 
                               value="{{ old('tax_rate', $invoice->tax_rate) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('tax_rate') border-red-500 @enderror">
                        @error('tax_rate')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- شروط الدفع -->
                    <div class="mb-6">
                        <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">
                            شروط الدفع
                        </label>
                        <textarea name="payment_terms" 
                                  id="payment_terms" 
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('payment_terms') border-red-500 @enderror">{{ old('payment_terms', $invoice->payment_terms) }}</textarea>
                        @error('payment_terms')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ملاحظات -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('notes') border-red-500 @enderror">{{ old('notes', $invoice->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- أزرار التحكم -->
            <div class="flex justify-end space-x-4 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('facility.invoices.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                    إلغاء
                </a>
                <button type="submit" 
                        class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    تحديث الفاتورة
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill amount from contract
    const contractSelect = document.getElementById('contract_id');
    const amountInput = document.getElementById('amount');
    
    contractSelect.addEventListener('change', function() {
        const selectedOption = contractSelect.options[contractSelect.selectedIndex];
        if (selectedOption.value) {
            const remainingAmount = selectedOption.getAttribute('data-remaining');
            if (remainingAmount) {
                amountInput.value = remainingAmount;
            }
        }
    });
});
</script>
@endsection


