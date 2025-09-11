@extends('facility.layouts.app')

@section('title', 'إضافة دفعة جديدة')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('facility.payments.index') }}">المدفوعات</a></li>
    <li class="breadcrumb-item active">إضافة دفعة جديدة</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة دفعة جديدة</h3>
                </div>

                <form method="POST" action="{{ route('facility.payments.store') }}" id="paymentForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- معلومات أساسية -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>المعلومات الأساسية
                                </h5>
                                
                                <!-- الفاتورة -->
                                <div class="mb-3">
                                    <label for="invoice_id" class="form-label">الفاتورة <span class="text-danger">*</span></label>
                                    <select name="invoice_id" id="invoice_id" class="form-select @error('invoice_id') is-invalid @enderror" required>
                                        <option value="">اختر الفاتورة</option>
                                        @foreach($invoices as $invoice)
                                            <option value="{{ $invoice->id }}" 
                                                    data-contract="{{ $invoice->contract_id }}"
                                                    data-amount="{{ $invoice->remaining_amount }}"
                                                    data-currency="{{ $invoice->currency }}"
                                                    {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                                {{ $invoice->invoice_number ?: 'INV-' . $invoice->id }} - 
                                                {{ $invoice->contract->product->getTranslatedTitle() }} - 
                                                {{ number_format($invoice->remaining_amount, 2) }} {{ $invoice->currency }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('invoice_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- العقد -->
                                <div class="mb-3">
                                    <label for="contract_id" class="form-label">العقد</label>
                                    <select name="contract_id" id="contract_id" class="form-select @error('contract_id') is-invalid @enderror">
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
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- طريقة الدفع -->
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                        <option value="">اختر طريقة الدفع</option>
                                        @foreach($paymentMethods as $key => $value)
                                            <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- المبلغ -->
                                <div class="mb-3">
                                    <label for="amount" class="form-label">المبلغ <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               name="amount" 
                                               id="amount" 
                                               class="form-control @error('amount') is-invalid @enderror" 
                                               value="{{ old('amount') }}" 
                                               step="0.01" 
                                               min="0" 
                                               required>
                                        <span class="input-group-text" id="currency_display">SAR</span>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- العملة -->
                                <div class="mb-3">
                                    <label for="currency" class="form-label">العملة <span class="text-danger">*</span></label>
                                    <select name="currency" id="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                        <option value="SAR" {{ old('currency') == 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                                        <option value="AED" {{ old('currency') == 'AED' ? 'selected' : '' }}>درهم إماراتي (AED)</option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- تاريخ الدفع -->
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">تاريخ الدفع <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           name="payment_date" 
                                           id="payment_date" 
                                           class="form-control @error('payment_date') is-invalid @enderror" 
                                           value="{{ old('payment_date', date('Y-m-d')) }}" 
                                           required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- تفاصيل إضافية -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-cog me-2"></i>التفاصيل الإضافية
                                </h5>

                                <!-- رقم المرجع -->
                                <div class="mb-3">
                                    <label for="reference_number" class="form-label">رقم المرجع</label>
                                    <input type="text" 
                                           name="reference_number" 
                                           id="reference_number" 
                                           class="form-control @error('reference_number') is-invalid @enderror" 
                                           value="{{ old('reference_number') }}" 
                                           placeholder="سيتم إنشاؤه تلقائياً إذا لم يتم إدخاله">
                                    @error('reference_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- مرجع الدفع -->
                                <div class="mb-3">
                                    <label for="payment_reference" class="form-label">مرجع الدفع</label>
                                    <input type="text" 
                                           name="payment_reference" 
                                           id="payment_reference" 
                                           class="form-control @error('payment_reference') is-invalid @enderror" 
                                           value="{{ old('payment_reference') }}" 
                                           placeholder="رقم المعاملة أو المرجع من البنك">
                                    @error('payment_reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- اسم البنك -->
                                <div class="mb-3" id="bank_name_field" style="display: none;">
                                    <label for="bank_name" class="form-label">اسم البنك</label>
                                    <input type="text" 
                                           name="bank_name" 
                                           id="bank_name" 
                                           class="form-control @error('bank_name') is-invalid @enderror" 
                                           value="{{ old('bank_name') }}">
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- رقم الشيك -->
                                <div class="mb-3" id="check_number_field" style="display: none;">
                                    <label for="check_number" class="form-label">رقم الشيك</label>
                                    <input type="text" 
                                           name="check_number" 
                                           id="check_number" 
                                           class="form-control @error('check_number') is-invalid @enderror" 
                                           value="{{ old('check_number') }}">
                                    @error('check_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- رقم القسط -->
                                <div class="mb-3">
                                    <label for="installment_number" class="form-label">رقم القسط</label>
                                    <input type="number" 
                                           name="installment_number" 
                                           id="installment_number" 
                                           class="form-control @error('installment_number') is-invalid @enderror" 
                                           value="{{ old('installment_number') }}" 
                                           min="1">
                                    @error('installment_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- رسوم المعالجة -->
                                <div class="mb-3">
                                    <label for="processing_fee" class="form-label">رسوم المعالجة</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               name="processing_fee" 
                                               id="processing_fee" 
                                               class="form-control @error('processing_fee') is-invalid @enderror" 
                                               value="{{ old('processing_fee', 0) }}" 
                                               step="0.01" 
                                               min="0">
                                        <span class="input-group-text" id="processing_fee_currency">SAR</span>
                                    </div>
                                    @error('processing_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- الخصم المطبق -->
                                <div class="mb-3">
                                    <label for="discount_applied" class="form-label">الخصم المطبق</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               name="discount_applied" 
                                               id="discount_applied" 
                                               class="form-control @error('discount_applied') is-invalid @enderror" 
                                               value="{{ old('discount_applied', 0) }}" 
                                               step="0.01" 
                                               min="0">
                                        <span class="input-group-text" id="discount_currency">SAR</span>
                                    </div>
                                    @error('discount_applied')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- رسوم التأخير -->
                                <div class="mb-3">
                                    <label for="late_fee_paid" class="form-label">رسوم التأخير المدفوعة</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               name="late_fee_paid" 
                                               id="late_fee_paid" 
                                               class="form-control @error('late_fee_paid') is-invalid @enderror" 
                                               value="{{ old('late_fee_paid', 0) }}" 
                                               step="0.01" 
                                               min="0">
                                        <span class="input-group-text" id="late_fee_currency">SAR</span>
                                    </div>
                                    @error('late_fee_paid')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- الملاحظات -->
                                <div class="mb-3">
                                    <label for="notes" class="form-label">ملاحظات</label>
                                    <textarea name="notes" 
                                              id="notes" 
                                              class="form-control @error('notes') is-invalid @enderror" 
                                              rows="3" 
                                              placeholder="أي ملاحظات إضافية حول الدفعة">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- ملخص الدفعة -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-calculator me-2"></i>ملخص الدفعة
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h6 class="text-muted">المبلغ الأساسي</h6>
                                                    <h4 class="text-primary" id="summary_amount">0.00 SAR</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h6 class="text-muted">رسوم المعالجة</h6>
                                                    <h4 class="text-warning" id="summary_processing_fee">0.00 SAR</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h6 class="text-muted">الخصم</h6>
                                                    <h4 class="text-success" id="summary_discount">0.00 SAR</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h6 class="text-muted">المبلغ الإجمالي</h6>
                                                    <h4 class="text-dark" id="summary_total">0.00 SAR</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('facility.payments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>حفظ الدفعة
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // تحديث العملة في جميع الحقول
    function updateCurrency() {
        const currency = $('#currency').val();
        $('#currency_display').text(currency);
        $('#processing_fee_currency').text(currency);
        $('#discount_currency').text(currency);
        $('#late_fee_currency').text(currency);
        updateSummary();
    }

    // تحديث ملخص الدفعة
    function updateSummary() {
        const amount = parseFloat($('#amount').val()) || 0;
        const processingFee = parseFloat($('#processing_fee').val()) || 0;
        const discount = parseFloat($('#discount_applied').val()) || 0;
        const lateFee = parseFloat($('#late_fee_paid').val()) || 0;
        const currency = $('#currency').val();

        const total = amount + processingFee + lateFee - discount;

        $('#summary_amount').text(amount.toFixed(2) + ' ' + currency);
        $('#summary_processing_fee').text(processingFee.toFixed(2) + ' ' + currency);
        $('#summary_discount').text(discount.toFixed(2) + ' ' + currency);
        $('#summary_total').text(total.toFixed(2) + ' ' + currency);
    }

    // تحديث العملة عند التغيير
    $('#currency').on('change', updateCurrency);

    // تحديث المبلغ عند تغيير الفاتورة
    $('#invoice_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const amount = selectedOption.data('amount');
        const currency = selectedOption.data('currency');
        const contractId = selectedOption.data('contract');

        if (amount) {
            $('#amount').val(amount);
            $('#currency').val(currency);
            updateCurrency();
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
