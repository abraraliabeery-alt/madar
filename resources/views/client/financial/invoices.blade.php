@extends('client.financial.layout')

@section('title', 'فواتيري - منطقة العميل')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-file-invoice text-primary ms-2"></i>
                فواتيري
            </h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="printInvoices()">
                    <i class="fas fa-print ms-1"></i>
                    طباعة
                </button>
                <button class="btn btn-primary" onclick="exportInvoices()">
                    <i class="fas fa-download ms-1"></i>
                    تصدير
                </button>
            </div>
        </div>
    </div>
</div>

<!-- فلتر الفواتير -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('client.financial.invoices') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">حالة الفاتورة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخرة</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="type" class="form-label">نوع الفاتورة</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">جميع الأنواع</option>
                            <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>إيجار</option>
                            <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>بيع</option>
                            <option value="commission" {{ request('type') == 'commission' ? 'selected' : '' }}>عمولة</option>
                            <option value="penalty" {{ request('type') == 'penalty' ? 'selected' : '' }}>غرامة</option>
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
                        <a href="{{ route('client.financial.invoices') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times ms-1"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card primary">
            <div class="icon primary">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="number">{{ $invoices->total() }}</div>
            <div class="label">إجمالي الفواتير</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card success">
            <div class="icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="number">{{ $invoices->where('status', 'paid')->count() }}</div>
            <div class="label">مدفوعة</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card warning">
            <div class="icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="number">{{ $invoices->where('status', 'pending')->count() }}</div>
            <div class="label">معلقة</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card danger">
            <div class="icon danger">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="number">{{ $invoices->where('status', 'overdue')->count() }}</div>
            <div class="label">متأخرة</div>
        </div>
    </div>
</div>

<!-- قائمة الفواتير -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list text-primary ms-2"></i>
                    قائمة الفواتير
                </h5>
            </div>
            <div class="card-body">
                @if($invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>العقد</th>
                                    <th>نوع الفاتورة</th>
                                    <th>المبلغ</th>
                                    <th>تاريخ الإصدار</th>
                                    <th>تاريخ الاستحقاق</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                <tr class="{{ $invoice->status == 'overdue' ? 'table-danger' : '' }}">
                                    <td>
                                        <span class="badge bg-light text-dark">#{{ $invoice->id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">
                                                <a href="{{ route('client.financial.contract-details', $invoice->contract_id) }}" 
                                                   class="text-decoration-none">
                                                    #{{ $invoice->contract_id }}
                                                </a>
                                            </div>
                                            <small class="text-muted">{{ $invoice->contract->product->title ?? 'غير محدد' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($invoice->invoice_type == 'rent') bg-info
                                            @elseif($invoice->invoice_type == 'sale') bg-success
                                            @elseif($invoice->invoice_type == 'commission') bg-warning
                                            @elseif($invoice->invoice_type == 'penalty') bg-danger
                                            @else bg-secondary @endif">
                                            @if($invoice->invoice_type == 'rent') إيجار
                                            @elseif($invoice->invoice_type == 'sale') بيع
                                            @elseif($invoice->invoice_type == 'commission') عمولة
                                            @elseif($invoice->invoice_type == 'penalty') غرامة
                                            @else {{ $invoice->invoice_type }} @endif
                                        </span>
                                    </td>
                                    <td class="fw-bold">{{ number_format($invoice->amount, 2) }} ر.س</td>
                                    <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="{{ $invoice->due_date < now() && $invoice->status != 'paid' ? 'text-danger fw-bold' : '' }}">
                                            {{ $invoice->due_date->format('Y-m-d') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($invoice->status == 'paid') bg-success
                                            @elseif($invoice->status == 'overdue') bg-danger
                                            @elseif($invoice->status == 'cancelled') bg-secondary
                                            @else bg-warning @endif">
                                            @if($invoice->status == 'paid') مدفوعة
                                            @elseif($invoice->status == 'overdue') متأخرة
                                            @elseif($invoice->status == 'cancelled') ملغية
                                            @else معلقة @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewInvoiceDetails({{ $invoice->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="downloadInvoice({{ $invoice->id }})">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            @if($invoice->status == 'pending' || $invoice->status == 'overdue')
                                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                                        onclick="makePaymentForInvoice({{ $invoice->id }})">
                                                    <i class="fas fa-credit-card"></i>
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
                        {{ $invoices->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-file-invoice"></i>
                        <h4>لا توجد فواتير</h4>
                        <p>لم يتم العثور على أي فواتير تطابق المعايير المحددة</p>
                        <a href="{{ route('client.financial.contracts') }}" class="btn btn-primary">
                            <i class="fas fa-file-contract ms-1"></i>
                            عرض العقود
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- مودال تفاصيل الفاتورة -->
<div class="modal fade" id="invoiceDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-invoice ms-2"></i>
                    تفاصيل الفاتورة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="invoiceDetailsContent">
                <!-- سيتم تحميل المحتوى هنا -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary" onclick="downloadCurrentInvoice()">
                    <i class="fas fa-download ms-1"></i>
                    تحميل الفاتورة
                </button>
            </div>
        </div>
    </div>
</div>

<!-- مودال الدفع للفاتورة -->
<div class="modal fade" id="makePaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-credit-card ms-2"></i>
                    دفع الفاتورة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('client.financial.make-payment') }}">
                @csrf
                <input type="hidden" id="payment_invoice_id" name="invoice_id">
                <input type="hidden" id="payment_contract_id" name="contract_id">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle ms-2"></i>
                        سيتم إرسال طلب الدفع للمؤسسة للمراجعة والتأكيد
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="payment_amount" class="form-label">المبلغ <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="payment_amount" name="amount" 
                                   step="0.01" min="0.01" required readonly>
                        </div>
                        <div class="col-12">
                            <label for="payment_method" class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">اختر طريقة الدفع</option>
                                <option value="cash">نقداً</option>
                                <option value="bank_transfer">تحويل بنكي</option>
                                <option value="credit_card">بطاقة ائتمان</option>
                                <option value="check">شيك</option>
                                <option value="online">دفع إلكتروني</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="payment_reference" class="form-label">رقم المرجع</label>
                            <input type="text" class="form-control" id="payment_reference" name="reference_number" 
                                   placeholder="رقم التحويل أو المرجع">
                        </div>
                        <div class="col-12">
                            <label for="payment_notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control" id="payment_notes" name="notes" rows="3" 
                                      placeholder="أي ملاحظات إضافية..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check ms-1"></i>
                        إرسال طلب الدفع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentInvoiceId = null;

// عرض تفاصيل الفاتورة
function viewInvoiceDetails(invoiceId) {
    currentInvoiceId = invoiceId;
    const modal = new bootstrap.Modal(document.getElementById('invoiceDetailsModal'));
    document.getElementById('invoiceDetailsContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <p class="mt-2">جاري تحميل تفاصيل الفاتورة...</p>
        </div>
    `;
    modal.show();
    
    // محاكاة تحميل البيانات
    setTimeout(() => {
        document.getElementById('invoiceDetailsContent').innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle ms-2"></i>
                سيتم إضافة تفاصيل الفاتورة قريباً
            </div>
        `;
    }, 1000);
}

// تحميل الفاتورة
function downloadInvoice(invoiceId) {
    // يمكن إضافة منطق تحميل الفاتورة هنا
    window.open(`/client/financial/invoices/${invoiceId}/download`, '_blank');
}

// تحميل الفاتورة الحالية
function downloadCurrentInvoice() {
    if (currentInvoiceId) {
        downloadInvoice(currentInvoiceId);
    }
}

// دفع الفاتورة
function makePaymentForInvoice(invoiceId) {
    // يمكن إضافة AJAX call هنا لجلب بيانات الفاتورة
    document.getElementById('payment_invoice_id').value = invoiceId;
    document.getElementById('payment_contract_id').value = 1; // يجب جلب هذا من البيانات
    document.getElementById('payment_amount').value = 1000; // يجب جلب هذا من البيانات
    
    const modal = new bootstrap.Modal(document.getElementById('makePaymentModal'));
    modal.show();
}

// طباعة الفواتير
function printInvoices() {
    window.print();
}

// تصدير الفواتير
function exportInvoices() {
    // يمكن إضافة منطق التصدير هنا
    alert('سيتم إضافة ميزة التصدير قريباً');
}

// تأكيد إرسال الدفع
document.querySelector('form[action="{{ route("client.financial.make-payment") }}"]').addEventListener('submit', function(e) {
    if (!confirm('هل أنت متأكد من إرسال طلب الدفع؟')) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection
