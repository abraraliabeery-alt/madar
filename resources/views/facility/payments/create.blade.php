@extends('facility.layouts.app')

@section('title', 'إضافة دفعة جديدة')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('facility.payments.index') }}">المدفوعات</a></li>
    <li class="breadcrumb-item active">إضافة دفعة جديدة</li>
@endsection

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-4 rounded-t-lg">
            <h3 class="text-xl font-semibold">إضافة دفعة جديدة</h3>
        </div>

        <form method="POST" action="{{ route('facility.payments.store') }}" id="paymentForm" class="p-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- معلومات أساسية -->
                <div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                        المعلومات الأساسية
                    </h5>
                    
                    <!-- الفاتورة -->
                    <div class="mb-6">
                        <label for="invoice_id" class="block text-sm font-medium text-gray-700 mb-2">
                            الفاتورة <span class="text-red-500">*</span>
                        </label>
                        <select name="invoice_id" id="invoice_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('invoice_id') border-red-500 @enderror" required>
                            <option value="">اختر الفاتورة</option>
                            @foreach($invoices as $invoice)
                                <option value="{{ $invoice->id }}" 
                                        data-contract="{{ $invoice->contract_id }}"
                                        data-amount="{{ $invoice->remaining_amount }}"
                                        data-currency="SAR"
                                        {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                    {{ $invoice->invoice_number ?: 'INV-' . $invoice->id }} - 
                                    {{ $invoice->contract->product->getTranslatedTitle() }} - 
                                    {{ number_format($invoice->remaining_amount, 2) }} SAR
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- العقد -->
                    <div class="mb-6">
                        <label for="contract_id" class="block text-sm font-medium text-gray-700 mb-2">العقد</label>
                        <select name="contract_id" id="contract_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('contract_id') border-red-500 @enderror">
                            <option value="">اختر العقد</option>
                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}" 
                                        data-product="{{ $contract->product->getTranslatedTitle() }}"
                                        {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                                    {{ $contract->contract_number ?: 'CON-' . $contract->id }} - 
                                    {{ $contract->product->getTranslatedTitle() }}
                                </option>
                            @endforeach
                        </select>
                        @error('contract_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- طريقة الدفع -->
                    <div class="mb-6">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            طريقة الدفع <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" id="payment_method" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('payment_method') border-red-500 @enderror" required>
                            <option value="">اختر طريقة الدفع</option>
                            @foreach($paymentMethods as $key => $value)
                                <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')
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
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('amount') border-red-500 @enderror" 
                                   value="{{ old('amount') }}" 
                                   step="0.01" 
                                   min="0" 
                                   required>
                            <span class="absolute left-3 top-2 text-gray-500 flex items-center" id="currency_display">
                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                SAR
                            </span>
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- تاريخ الدفع -->
                    <div class="mb-6">
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            تاريخ الدفع <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="payment_date" 
                               id="payment_date" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('payment_date') border-red-500 @enderror" 
                               value="{{ old('payment_date', date('Y-m-d')) }}" 
                               required>
                        @error('payment_date')
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

                    <!-- رقم المرجع -->
                    <div class="mb-6">
                        <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">رقم المرجع</label>
                        <input type="text" 
                               name="reference_number" 
                               id="reference_number" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('reference_number') border-red-500 @enderror" 
                               value="{{ old('reference_number') }}" 
                               placeholder="سيتم إنشاؤه تلقائياً إذا لم يتم إدخاله">
                        @error('reference_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- مرجع الدفع -->
                    <div class="mb-6">
                        <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-2">مرجع الدفع</label>
                        <input type="text" 
                               name="payment_reference" 
                               id="payment_reference" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('payment_reference') border-red-500 @enderror" 
                               value="{{ old('payment_reference') }}" 
                               placeholder="رقم المعاملة أو المرجع من البنك">
                        @error('payment_reference')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- اسم البنك -->
                    <div class="mb-6" id="bank_name_field" style="display: none;">
                        <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">اسم البنك</label>
                        <input type="text" 
                               name="bank_name" 
                               id="bank_name" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('bank_name') border-red-500 @enderror" 
                               value="{{ old('bank_name') }}">
                        @error('bank_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- رقم الشيك -->
                    <div class="mb-6" id="check_number_field" style="display: none;">
                        <label for="check_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الشيك</label>
                        <input type="text" 
                               name="check_number" 
                               id="check_number" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('check_number') border-red-500 @enderror" 
                               value="{{ old('check_number') }}">
                        @error('check_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- رقم القسط -->
                    <div class="mb-6">
                        <label for="installment_number" class="block text-sm font-medium text-gray-700 mb-2">رقم القسط</label>
                        <input type="number" 
                               name="installment_number" 
                               id="installment_number" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('installment_number') border-red-500 @enderror" 
                               value="{{ old('installment_number') }}" 
                               min="1">
                        @error('installment_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- رسوم المعالجة -->
                    <div class="mb-6">
                        <label for="processing_fee" class="block text-sm font-medium text-gray-700 mb-2">رسوم المعالجة</label>
                        <div class="relative">
                            <input type="number" 
                                   name="processing_fee" 
                                   id="processing_fee" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('processing_fee') border-red-500 @enderror" 
                                   value="{{ old('processing_fee', 0) }}" 
                                   step="0.01" 
                                   min="0">
                            <span class="absolute left-3 top-2 text-gray-500 flex items-center" id="processing_fee_currency">
                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                SAR
                            </span>
                        </div>
                        @error('processing_fee')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الخصم المطبق -->
                    <div class="mb-6">
                        <label for="discount_applied" class="block text-sm font-medium text-gray-700 mb-2">الخصم المطبق</label>
                        <div class="relative">
                            <input type="number" 
                                   name="discount_applied" 
                                   id="discount_applied" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('discount_applied') border-red-500 @enderror" 
                                   value="{{ old('discount_applied', 0) }}" 
                                   step="0.01" 
                                   min="0">
                            <span class="absolute left-3 top-2 text-gray-500 flex items-center" id="discount_currency">
                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                SAR
                            </span>
                        </div>
                        @error('discount_applied')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- رسوم التأخير -->
                    <div class="mb-6">
                        <label for="late_fee_paid" class="block text-sm font-medium text-gray-700 mb-2">رسوم التأخير المدفوعة</label>
                        <div class="relative">
                            <input type="number" 
                                   name="late_fee_paid" 
                                   id="late_fee_paid" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('late_fee_paid') border-red-500 @enderror" 
                                   value="{{ old('late_fee_paid', 0) }}" 
                                   step="0.01" 
                                   min="0">
                            <span class="absolute left-3 top-2 text-gray-500 flex items-center" id="late_fee_currency">
                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                SAR
                            </span>
                        </div>
                        @error('late_fee_paid')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الملاحظات -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                        <textarea name="notes" 
                                  id="notes" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('notes') border-red-500 @enderror" 
                                  rows="3" 
                                  placeholder="أي ملاحظات إضافية حول الدفعة">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ملخص الدفعة -->
            <div class="bg-gray-50 rounded-lg p-6 mt-8">
                <h6 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calculator text-primary-600 mr-2"></i>
                    ملخص الدفعة
                </h6>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <h6 class="text-sm text-gray-600 mb-2">المبلغ الأساسي</h6>
                        <h4 class="text-2xl font-bold text-primary-600" id="summary_amount">0.00 SAR</h4>
                    </div>
                    <div class="text-center">
                        <h6 class="text-sm text-gray-600 mb-2">رسوم المعالجة</h6>
                        <h4 class="text-2xl font-bold text-yellow-600" id="summary_processing_fee">0.00 SAR</h4>
                    </div>
                    <div class="text-center">
                        <h6 class="text-sm text-gray-600 mb-2">الخصم</h6>
                        <h4 class="text-2xl font-bold text-green-600" id="summary_discount">0.00 SAR</h4>
                    </div>
                    <div class="text-center">
                        <h6 class="text-sm text-gray-600 mb-2">المبلغ الإجمالي</h6>
                        <h4 class="text-2xl font-bold text-gray-800" id="summary_total">0.00 SAR</h4>
                    </div>
                </div>
            </div>

            <!-- أزرار الإجراءات -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('facility.payments.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-arrow-right"></i>
                    <span>إلغاء</span>
                </a>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                    <i class="fas fa-save"></i>
                    <span>حفظ الدفعة</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // تحديث ملخص الدفعة
    function updateSummary() {
        const amount = parseFloat($('#amount').val()) || 0;
        const processingFee = parseFloat($('#processing_fee').val()) || 0;
        const discount = parseFloat($('#discount_applied').val()) || 0;
        const lateFee = parseFloat($('#late_fee_paid').val()) || 0;

        const total = amount + processingFee + lateFee - discount;

        $('#summary_amount').text(amount.toFixed(2) + ' SAR');
        $('#summary_processing_fee').text(processingFee.toFixed(2) + ' SAR');
        $('#summary_discount').text(discount.toFixed(2) + ' SAR');
        $('#summary_total').text(total.toFixed(2) + ' SAR');
    }

    // تحديث المبلغ عند تغيير الفاتورة
    $('#invoice_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const amount = selectedOption.data('amount');
        const contractId = selectedOption.data('contract');

        if (amount) {
            $('#amount').val(amount);
            updateSummary();
        }

        if (contractId) {
            $('#contract_id').val(contractId);
        }
    });

    // إظهار/إخفاء الحقول حسب طريقة الدفع
    $('#payment_method').on('change', function() {
        const method = $(this).val();
        
        // إخفاء جميع الحقول الإضافية
        $('#bank_name_field, #check_number_field').hide();
        
        // إظهار الحقول المناسبة
        if (method === 'bank_transfer') {
            $('#bank_name_field').show();
        } else if (method === 'check') {
            $('#check_number_field').show();
        }
    });

    // تحديث الملخص عند تغيير أي قيمة
    $('#amount, #processing_fee, #discount_applied, #late_fee_paid').on('input', updateSummary);

    // إنشاء رقم المرجع تلقائياً
    $('#reference_number').on('blur', function() {
        if (!$(this).val()) {
            const today = new Date();
            const dateStr = today.getFullYear() + 
                          String(today.getMonth() + 1).padStart(2, '0') + 
                          String(today.getDate()).padStart(2, '0');
            const randomNum = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            $(this).val('PAY-' + dateStr + '-' + randomNum);
        }
    });

    // التحقق من صحة النموذج
    $('#paymentForm').on('submit', function(e) {
        const amount = parseFloat($('#amount').val()) || 0;
        const invoiceId = $('#invoice_id').val();
        
        if (invoiceId) {
            const selectedInvoice = $('#invoice_id option:selected');
            const invoiceAmount = selectedInvoice.data('amount') || 0;
            
            if (amount > invoiceAmount) {
                e.preventDefault();
                alert('المبلغ المدخل أكبر من المبلغ المتبقي في الفاتورة');
                return false;
            }
        }
        
        if (amount <= 0) {
            e.preventDefault();
            alert('يرجى إدخال مبلغ صحيح');
            return false;
        }
    });

    // تحديث أولي للملخص
    updateSummary();
});
</script>
@endpush