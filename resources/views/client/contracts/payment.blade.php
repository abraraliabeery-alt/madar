@extends('layouts.client')

@section('title', 'دفع فاتورة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">دفع فاتورة - {{ $contract->contract_number }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('client.contracts.show', $contract) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للعقد
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- معلومات العقد -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">معلومات العقد</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>رقم العقد:</strong></td>
                                            <td>{{ $contract->contract_number ?? 'غير محدد' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>المنتج:</strong></td>
                                            <td>{{ $contract->product->getTranslatedTitle() }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>النوع:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $contract->contract_type == 'sale' ? 'success' : 'info' }}">
                                                    {{ $contract->contract_type == 'sale' ? 'بيع' : 'إيجار' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>المبلغ الإجمالي:</strong></td>
                                            <td class="flex items-center">
                                                {{ number_format($contract->total_amount, 2) }}
                                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>المدفوع:</strong></td>
                                            <td class="text-success flex items-center">
                                                {{ number_format($contract->getTotalPaidAmount(), 2) }}
                                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>المتبقي:</strong></td>
                                            <td class="text-warning flex items-center">
                                                {{ number_format($contract->getRemainingAmount(), 2) }}
                                                <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-4 h-4 mr-1">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- الفواتير المتاحة للدفع -->
                        <div class="col-md-8">
                            <h5>الفواتير المتاحة للدفع</h5>
                            
                            @if($contract->invoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>رقم الفاتورة</th>
                                                <th>النوع</th>
                                                <th>المبلغ</th>
                                                <th>المدفوع</th>
                                                <th>المتبقي</th>
                                                <th>تاريخ الاستحقاق</th>
                                                <th>الحالة</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($contract->invoices as $invoice)
                                                @if($invoice->remaining_amount > 0)
                                                    <tr>
                                                        <td>{{ $invoice->invoice_number }}</td>
                                                        <td>
                                                            @switch($invoice->invoice_type)
                                                                @case('rent') فاتورة إيجار @break
                                                                @case('sale') فاتورة بيع @break
                                                                @case('deposit') فاتورة العربون @break
                                                                @case('commission') فاتورة العمولة @break
                                                                @case('refund') فاتورة استرداد @break
                                                            @endswitch
                                                        </td>
                                                        <td class="flex items-center">
                                                            {{ number_format($invoice->amount, 2) }}
                                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                                        </td>
                                                        <td class="text-success flex items-center">
                                                            {{ number_format($invoice->paid_amount, 2) }}
                                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                                        </td>
                                                        <td class="text-warning flex items-center">
                                                            {{ number_format($invoice->remaining_amount, 2) }}
                                                            <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                                        </td>
                                                        <td>{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : 'غير محدد' }}</td>
                                                        <td>
                                                            @switch($invoice->status)
                                                                @case('draft') <span class="badge bg-secondary">مسودة</span> @break
                                                                @case('sent') <span class="badge bg-info">مرسل</span> @break
                                                                @case('paid') <span class="badge bg-success">مدفوع</span> @break
                                                                @case('overdue') <span class="badge bg-danger">متأخر</span> @break
                                                                @case('cancelled') <span class="badge bg-dark">ملغي</span> @break
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary pay-invoice-btn" 
                                                                    data-invoice-id="{{ $invoice->id }}"
                                                                    data-amount="{{ $invoice->remaining_amount }}"
                                                                    data-currency="SAR">
                                                                <i class="fas fa-credit-card"></i> دفع
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">لا توجد فواتير</h5>
                                    <p class="text-muted">لا توجد فواتير متاحة للدفع حالياً</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- سجل المدفوعات -->
                    @if($contract->payments->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>سجل المدفوعات</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>رقم المرجع</th>
                                                <th>طريقة الدفع</th>
                                                <th>المبلغ</th>
                                                <th>تاريخ الدفع</th>
                                                <th>الحالة</th>
                                                <th>ملاحظات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($contract->payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->reference_number ?? 'غير محدد' }}</td>
                                                    <td>
                                                        @switch($payment->payment_method)
                                                            @case('cash') نقداً @break
                                                            @case('bank_transfer') تحويل بنكي @break
                                                            @case('credit_card') بطاقة ائتمان @break
                                                            @case('check') شيك @break
                                                            @case('online') عبر الإنترنت @break
                                                        @endswitch
                                                    </td>
                                                    <td class="flex items-center">
                                                        {{ number_format($payment->amount, 2) }}
                                                        <img src="{{ asset('Saudi_Riyal_Symbol.svg') }}" alt="SAR" class="w-3 h-3 mr-1">
                                                    </td>
                                                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                                    <td>
                                                        @switch($payment->status)
                                                            @case('pending') <span class="badge bg-warning">معلق</span> @break
                                                            @case('confirmed') <span class="badge bg-success">مؤكد</span> @break
                                                            @case('failed') <span class="badge bg-danger">فشل</span> @break
                                                            @case('refunded') <span class="badge bg-info">مسترد</span> @break
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $payment->notes ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal الدفع -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">دفع فاتورة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="paymentForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="">اختر طريقة الدفع</option>
                                    <option value="cash">نقداً</option>
                                    <option value="bank_transfer">تحويل بنكي</option>
                                    <option value="credit_card">بطاقة ائتمان</option>
                                    <option value="check">شيك</option>
                                    <option value="online">عبر الإنترنت</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">المبلغ <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_date" class="form-label">تاريخ الدفع <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reference_number" class="form-label">رقم المرجع</label>
                                <input type="text" name="reference_number" id="reference_number" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bank_name" class="form-label">اسم البنك</label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="check_number" class="form-label">رقم الشيك</label>
                                <input type="text" name="check_number" id="check_number" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تسجيل الدفعة</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentInvoiceId = null;

    // فتح modal الدفع
    document.querySelectorAll('.pay-invoice-btn').forEach(button => {
        button.addEventListener('click', function() {
            currentInvoiceId = this.dataset.invoiceId;
            const amount = this.dataset.amount;
            
            document.getElementById('amount').value = amount;
            document.getElementById('amount').max = amount;
            
            new bootstrap.Modal(document.getElementById('paymentModal')).show();
        });
    });

    // تسجيل الدفعة
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!currentInvoiceId) {
            alert('خطأ: لم يتم تحديد الفاتورة');
            return;
        }

        const formData = new FormData(this);
        formData.append('invoice_id', currentInvoiceId);
        formData.append('currency', 'SAR');

        fetch(`/client/contracts/{{ $contract->id }}/pay-invoice`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم تسجيل الدفعة بنجاح');
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تسجيل الدفعة');
        });
    });

    // تعيين التاريخ الحالي كافتراضي
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('payment_date').value = today;
    });
</script>
@endpush
