@extends('client.financial.layout')

@section('title', 'مدفوعاتي - منطقة العميل')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-credit-card text-primary ms-2"></i>
                مدفوعاتي
            </h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#makePaymentModal">
                <i class="fas fa-plus ms-1"></i>
                إجراء دفعة جديدة
            </button>
        </div>
    </div>
</div>

<!-- فلتر المدفوعات -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('client.financial.payments') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">حالة الدفعة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكدة</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="method" class="form-label">طريقة الدفع</label>
                        <select class="form-select" id="method" name="method">
                            <option value="">جميع الطرق</option>
                            <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>نقداً</option>
                            <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                            <option value="credit_card" {{ request('method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                            <option value="check" {{ request('method') == 'check' ? 'selected' : '' }}>شيك</option>
                            <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>دفع إلكتروني</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="contract_id" class="form-label">العقد</label>
                        <select class="form-select" id="contract_id" name="contract_id">
                            <option value="">جميع العقود</option>
                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}" {{ request('contract_id') == $contract->id ? 'selected' : '' }}>
                                    #{{ $contract->id }} - {{ $contract->product->title ?? 'غير محدد' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search ms-1"></i>
                            تطبيق الفلتر
                        </button>
                        <a href="{{ route('client.financial.payments') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times ms-1"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- قائمة المدفوعات -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list text-primary ms-2"></i>
                    قائمة المدفوعات
                </h5>
            </div>
            <div class="card-body">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم الدفعة</th>
                                    <th>العقد</th>
                                    <th>المبلغ</th>
                                    <th>طريقة الدفع</th>
                                    <th>تاريخ الدفع</th>
                                    <th>الحالة</th>
                                    <th>المرجع</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark">#{{ $payment->id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">
                                                <a href="{{ route('client.financial.contract-details', $payment->contract_id) }}" 
                                                   class="text-decoration-none">
                                                    #{{ $payment->contract_id }}
                                                </a>
                                            </div>
                                            <small class="text-muted">{{ $payment->contract->product->title ?? 'غير محدد' }}</small>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-success">{{ number_format($payment->amount, 2) }} ر.س</td>
                                    <td>
                                        <span class="badge 
                                            @if($payment->payment_method == 'cash') bg-success
                                            @elseif($payment->payment_method == 'bank_transfer') bg-primary
                                            @elseif($payment->payment_method == 'credit_card') bg-info
                                            @elseif($payment->payment_method == 'check') bg-warning
                                            @else bg-secondary @endif">
                                            @if($payment->payment_method == 'cash') نقداً
                                            @elseif($payment->payment_method == 'bank_transfer') تحويل بنكي
                                            @elseif($payment->payment_method == 'credit_card') بطاقة ائتمان
                                            @elseif($payment->payment_method == 'check') شيك
                                            @else دفع إلكتروني @endif
                                        </span>
                                    </td>
                                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($payment->status == 'confirmed') bg-success
                                            @elseif($payment->status == 'rejected') bg-danger
                                            @else bg-warning @endif">
                                            @if($payment->status == 'confirmed') مؤكدة
                                            @elseif($payment->status == 'rejected') مرفوضة
                                            @else معلقة @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($payment->reference_number)
                                            <small class="text-muted">{{ $payment->reference_number }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewPaymentDetails({{ $payment->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($payment->status == 'confirmed')
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="downloadReceipt({{ $payment->id }})">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $payments->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-credit-card"></i>
                        <h4>لا توجد مدفوعات</h4>
                        <p>لم يتم العثور على أي مدفوعات تطابق المعايير المحددة</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#makePaymentModal">
                            <i class="fas fa-plus ms-1"></i>
                            إجراء دفعة جديدة
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- مودال إجراء دفعة جديدة -->
<div class="modal fade" id="makePaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle ms-2"></i>
                    إجراء دفعة جديدة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('client.financial.make-payment') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="contract_id" class="form-label">العقد <span class="text-danger">*</span></label>
                            <select class="form-select @error('contract_id') is-invalid @enderror" 
                                    id="contract_id" name="contract_id" required>
                                <option value="">اختر العقد</option>
                                @foreach($contracts as $contract)
                                    <option value="{{ $contract->id }}" 
                                            data-remaining="{{ $contract->getRemainingAmount() }}">
                                        #{{ $contract->id }} - {{ $contract->product->title ?? 'غير محدد' }}
                                        (المتبقي: {{ number_format($contract->getRemainingAmount(), 2) }} ر.س)
                                    </option>
                                @endforeach
                            </select>
                            @error('contract_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="amount" class="form-label">المبلغ <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" step="0.01" min="0.01" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">المبلغ المتبقي: <span id="remainingAmount">0.00</span> ر.س</div>
                        </div>
                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method" required>
                                <option value="">اختر طريقة الدفع</option>
                                <option value="cash">نقداً</option>
                                <option value="bank_transfer">تحويل بنكي</option>
                                <option value="credit_card">بطاقة ائتمان</option>
                                <option value="check">شيك</option>
                                <option value="online">دفع إلكتروني</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="reference_number" class="form-label">رقم المرجع</label>
                            <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                   id="reference_number" name="reference_number" 
                                   placeholder="رقم التحويل أو المرجع">
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="أي ملاحظات إضافية..."></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check ms-1"></i>
                        إرسال الدفعة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- مودال تفاصيل الدفعة -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle ms-2"></i>
                    تفاصيل الدفعة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="paymentDetailsContent">
                <!-- سيتم تحميل المحتوى هنا -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// تحديث المبلغ المتبقي عند تغيير العقد
document.getElementById('contract_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const remainingAmount = selectedOption.getAttribute('data-remaining') || '0.00';
    document.getElementById('remainingAmount').textContent = parseFloat(remainingAmount).toFixed(2);
    
    // تحديث الحد الأقصى للمبلغ
    const amountInput = document.getElementById('amount');
    amountInput.max = remainingAmount;
    amountInput.placeholder = `أقصى مبلغ: ${parseFloat(remainingAmount).toFixed(2)} ر.س`;
});

// عرض تفاصيل الدفعة
function viewPaymentDetails(paymentId) {
    // يمكن إضافة AJAX call هنا لجلب تفاصيل الدفعة
    const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
    document.getElementById('paymentDetailsContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <p class="mt-2">جاري تحميل تفاصيل الدفعة...</p>
        </div>
    `;
    modal.show();
    
    // محاكاة تحميل البيانات
    setTimeout(() => {
        document.getElementById('paymentDetailsContent').innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle ms-2"></i>
                سيتم إضافة تفاصيل الدفعة قريباً
            </div>
        `;
    }, 1000);
}

// تحميل إيصال الدفعة
function downloadReceipt(paymentId) {
    // يمكن إضافة منطق تحميل الإيصال هنا
    alert('سيتم إضافة ميزة تحميل الإيصال قريباً');
}

// تأكيد إرسال الدفعة
document.querySelector('form[action="{{ route("client.financial.make-payment") }}"]').addEventListener('submit', function(e) {
    const amount = parseFloat(document.getElementById('amount').value);
    const remaining = parseFloat(document.getElementById('remainingAmount').textContent);
    
    if (amount > remaining) {
        e.preventDefault();
        alert('المبلغ أكبر من المبلغ المتبقي في العقد');
        return false;
    }
    
    if (!confirm('هل أنت متأكد من إرسال هذه الدفعة؟')) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection
