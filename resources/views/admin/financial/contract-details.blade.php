@extends('admin.financial.layout')

@section('title', 'تفاصيل العقد #' . $contract->contract_number . ' - النظام المالي للأدمن')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.financial.dashboard') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.financial.contracts') }}">العقود</a></li>
                <li class="breadcrumb-item active">{{ $contract->contract_number }}</li>
            </ol>
        </nav>
        <h2><i class="fas fa-file-contract ms-2"></i>تفاصيل العقد: {{ $contract->contract_number }}</h2>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-success" onclick="downloadContract()">
            <i class="fas fa-download"></i> تحميل العقد
        </button>
        <button type="button" class="btn btn-info" onclick="printContract()">
            <i class="fas fa-print"></i> طباعة
        </button>
        <div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-cog"></i> إجراءات
            </button>
            <ul class="dropdown-menu">
                @if($contract->status === 'draft')
                <li><a class="dropdown-item" href="#" onclick="updateStatus('active')">
                    <i class="fas fa-check text-success ms-2"></i>تفعيل العقد
                </a></li>
                <li><a class="dropdown-item" href="#" onclick="updateStatus('cancelled')">
                    <i class="fas fa-times text-danger ms-2"></i>إلغاء العقد
                </a></li>
                @endif
                @if($contract->status === 'active')
                <li><a class="dropdown-item" href="#" onclick="updateStatus('completed')">
                    <i class="fas fa-flag-checkered text-info ms-2"></i>إكمال العقد
                </a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                    <i class="fas fa-plus text-primary ms-2"></i>إضافة دفعة
                </a></li>
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addAccountingEntryModal">
                    <i class="fas fa-calculator text-secondary ms-2"></i>إضافة قيد محاسبي
                </a></li>
            </ul>
        </div>
    </div>
</div>

<!-- حالة العقد -->
<div class="alert alert-{{ $contract->status === 'active' ? 'success' : ($contract->status === 'draft' ? 'warning' : ($contract->status === 'completed' ? 'info' : 'danger')) }} d-flex justify-content-between align-items-center">
    <div>
        <h5 class="mb-0">
            <i class="fas fa-info-circle ms-2"></i>
            حالة العقد: 
            @switch($contract->status)
                @case('draft')
                    <span class="badge bg-warning">مسودة - في انتظار الموافقة</span>
                    @break
                @case('active')
                    <span class="badge bg-success">نشط ومفعل</span>
                    @break
                @case('completed')
                    <span class="badge bg-info">مكتمل</span>
                    @break
                @case('cancelled')
                    <span class="badge bg-danger">ملغي</span>
                    @break
            @endswitch
        </h5>
    </div>
    <div>
        <small class="text-muted">
            آخر تحديث: {{ $contract->updated_at->format('Y/m/d H:i') }}
        </small>
    </div>
</div>

<!-- معلومات العقد الأساسية -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info ms-2"></i>معلومات العقد</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">رقم العقد:</td>
                                <td>{{ $contract->contract_number }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">نوع العقد:</td>
                                <td>
                                    @if($contract->contract_type === 'sale')
                                        <span class="badge bg-success">عقد بيع</span>
                                    @else
                                        <span class="badge bg-primary">عقد إيجار</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">المبلغ الإجمالي:</td>
                                <td class="fw-bold text-success">{{ number_format($contract->total_amount, 2) }} ر.س</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">مبلغ العربون:</td>
                                <td>{{ number_format($contract->deposit_amount, 2) }} ر.س</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">العمولة:</td>
                                <td>
                                    {{ number_format($contract->commission_amount, 2) }} ر.س
                                    <small class="text-muted">({{ number_format($contract->commission_rate * 100, 2) }}%)</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">تاريخ البداية:</td>
                                <td>{{ $contract->start_date ? $contract->start_date->format('Y/m/d') : 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">تاريخ النهاية:</td>
                                <td>{{ $contract->end_date ? $contract->end_date->format('Y/m/d') : 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">المؤسسة:</td>
                                <td>
                                    <a href="{{ route('admin.facilities.show', $contract->facility_id) }}" class="text-decoration-none">
                                        {{ $contract->facility->name }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">تاريخ الإنشاء:</td>
                                <td>{{ $contract->created_at->format('Y/m/d H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">منشئ بواسطة:</td>
                                <td>{{ $contract->createdBy->name ?? 'غير معروف' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie ms-2"></i>ملخص المدفوعات</h5>
            </div>
            <div class="card-body">
                @php
                    $totalPaid = $contract->getTotalPaidAmount();
                    $remainingAmount = $contract->getRemainingAmount();
                    $paymentProgress = $contract->total_amount > 0 ? ($totalPaid / $contract->total_amount) * 100 : 0;
                @endphp
                
                <div class="text-center mb-3">
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: {{ $paymentProgress }}%">
                            {{ number_format($paymentProgress, 1) }}%
                        </div>
                    </div>
                </div>
                
                <table class="table table-sm">
                    <tr>
                        <td>المبلغ الإجمالي:</td>
                        <td class="fw-bold">{{ number_format($contract->total_amount, 2) }} ر.س</td>
                    </tr>
                    <tr>
                        <td>المبلغ المدفوع:</td>
                        <td class="fw-bold text-success">{{ number_format($totalPaid, 2) }} ر.س</td>
                    </tr>
                    <tr>
                        <td>المبلغ المتبقي:</td>
                        <td class="fw-bold text-{{ $remainingAmount > 0 ? 'warning' : 'success' }}">
                            {{ number_format($remainingAmount, 2) }} ر.س
                        </td>
                    </tr>
                </table>
                
                @if($remainingAmount <= 0)
                    <div class="alert alert-success mt-2 mb-0">
                        <i class="fas fa-check-circle ms-2"></i>تم السداد بالكامل
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- معلومات الأطراف -->
<div class="row mb-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user ms-2"></i>العميل</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle p-3 text-white me-3">
                        <i class="fas fa-user fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $contract->user->name }}</h6>
                        <small class="text-muted">العميل</small>
                    </div>
                </div>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>البريد الإلكتروني:</td>
                        <td>{{ $contract->user->email }}</td>
                    </tr>
                    <tr>
                        <td>رقم الهاتف:</td>
                        <td>{{ $contract->user->phone_number ?? 'غير متوفر' }}</td>
                    </tr>
                </table>
                <a href="{{ route('admin.users.show', $contract->user_id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-eye"></i> عرض الملف
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-tie ms-2"></i>المالك</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success rounded-circle p-3 text-white me-3">
                        <i class="fas fa-user-tie fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $contract->owner->name }}</h6>
                        <small class="text-muted">المالك</small>
                    </div>
                </div>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>البريد الإلكتروني:</td>
                        <td>{{ $contract->owner->email }}</td>
                    </tr>
                    <tr>
                        <td>رقم الهاتف:</td>
                        <td>{{ $contract->owner->phone_number ?? 'غير متوفر' }}</td>
                    </tr>
                </table>
                <a href="{{ route('admin.users.show', $contract->owner_id) }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-eye"></i> عرض الملف
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-home ms-2"></i>المشروع</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-info rounded-circle p-3 text-white me-3">
                        <i class="fas fa-home fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $contract->product->getTranslatedTitle() }}</h6>
                        <small class="text-muted">المشروع</small>
                    </div>
                </div>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>العنوان:</td>
                        <td>{{ Str::limit($contract->product->address, 30) }}</td>
                    </tr>
                    <tr>
                        <td>الفئة:</td>
                        <td>{{ $contract->product->category->getTranslatedName() ?? 'غير محدد' }}</td>
                    </tr>
                </table>
                <a href="{{ route('admin.products.show', $contract->product_id) }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-eye"></i> عرض المشروع
                </a>
            </div>
        </div>
    </div>
</div>

<!-- الفواتير -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-invoice ms-2"></i>الفواتير</h5>
                    <span class="badge bg-secondary">{{ $contract->invoices->count() }}</span>
                </div>
            </div>
            <div class="card-body">
                @if($contract->invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
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
                            <tr>
                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                <td>
                                    @switch($invoice->invoice_type)
                                        @case('rent')
                                            <span class="badge bg-primary">إيجار</span>
                                            @break
                                        @case('sale')
                                            <span class="badge bg-success">بيع</span>
                                            @break
                                        @case('deposit')
                                            <span class="badge bg-warning">عربون</span>
                                            @break
                                        @case('commission')
                                            <span class="badge bg-info">عمولة</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $invoice->invoice_type }}</span>
                                    @endswitch
                                </td>
                                <td class="fw-bold">{{ number_format($invoice->amount, 2) }} ر.س</td>
                                <td class="text-success">{{ number_format($invoice->paid_amount, 2) }} ر.س</td>
                                <td class="text-{{ $invoice->remaining_amount > 0 ? 'warning' : 'success' }}">
                                    {{ number_format($invoice->remaining_amount, 2) }} ر.س
                                </td>
                                <td>
                                    {{ $invoice->due_date ? $invoice->due_date->format('Y/m/d') : 'غير محدد' }}
                                    @if($invoice->due_date && $invoice->due_date->isPast() && $invoice->remaining_amount > 0)
                                        <br><small class="text-danger">متأخر</small>
                                    @endif
                                </td>
                                <td>
                                    @switch($invoice->status)
                                        @case('draft')
                                            <span class="badge bg-secondary">مسودة</span>
                                            @break
                                        @case('sent')
                                            <span class="badge bg-primary">مرسل</span>
                                            @break
                                        @case('paid')
                                            <span class="badge bg-success">مدفوع</span>
                                            @break
                                        @case('overdue')
                                            <span class="badge bg-danger">متأخر</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $invoice->status }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" onclick="viewInvoiceDetails({{ $invoice->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-success" onclick="downloadInvoice({{ $invoice->id }})">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>لا توجد فواتير لهذا العقد</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- المدفوعات -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-credit-card ms-2"></i>المدفوعات</h5>
                    <span class="badge bg-secondary">{{ $contract->payments->count() }}</span>
                </div>
            </div>
            <div class="card-body">
                @if($contract->payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>رقم المرجع</th>
                                <th>المبلغ</th>
                                <th>طريقة الدفع</th>
                                <th>تاريخ الدفع</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contract->payments as $payment)
                            <tr>
                                <td><strong>{{ $payment->reference_number }}</strong></td>
                                <td class="fw-bold">{{ number_format($payment->amount, 2) }} ر.س</td>
                                <td>
                                    @switch($payment->payment_method)
                                        @case('cash')
                                            <span class="badge bg-success">نقداً</span>
                                            @break
                                        @case('bank_transfer')
                                            <span class="badge bg-primary">تحويل بنكي</span>
                                            @break
                                        @case('credit_card')
                                            <span class="badge bg-info">بطاقة ائتمان</span>
                                            @break
                                        @case('check')
                                            <span class="badge bg-warning">شيك</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $payment->payment_method }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $payment->payment_date->format('Y/m/d') }}</td>
                                <td>
                                    @switch($payment->status)
                                        @case('pending')
                                            <span class="badge bg-warning">معلق</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge bg-success">مؤكد</span>
                                            @break
                                        @case('failed')
                                            <span class="badge bg-danger">فشل</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $payment->status }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($payment->status === 'pending')
                                        <button class="btn btn-success" onclick="confirmPayment({{ $payment->id }})">
                                            <i class="fas fa-check"></i> تأكيد
                                        </button>
                                        <button class="btn btn-danger" onclick="rejectPayment({{ $payment->id }})">
                                            <i class="fas fa-times"></i> رفض
                                        </button>
                                        @endif
                                        <button class="btn btn-outline-info" onclick="viewPaymentDetails({{ $payment->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-credit-card fa-3x mb-3"></i>
                    <p>لا توجد مدفوعات لهذا العقد</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- القيود المحاسبية -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calculator ms-2"></i>القيود المحاسبية</h5>
                    <span class="badge bg-secondary">{{ $contract->accountingEntries->count() }}</span>
                </div>
            </div>
            <div class="card-body">
                @if($contract->accountingEntries->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>تاريخ القيد</th>
                                <th>الوصف</th>
                                <th>نوع الحساب</th>
                                <th>مدين</th>
                                <th>دائن</th>
                                <th>المرجع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contract->accountingEntries as $entry)
                            <tr>
                                <td>{{ $entry->entry_date ? Carbon\Carbon::parse($entry->entry_date)->format('Y/m/d') : 'غير محدد' }}</td>
                                <td>{{ $entry->description }}</td>
                                <td>
                                    @switch($entry->account_type)
                                        @case('revenue')
                                            <span class="badge bg-success">إيرادات</span>
                                            @break
                                        @case('receivable')
                                            <span class="badge bg-primary">ذمم مدينة</span>
                                            @break
                                        @case('commission')
                                            <span class="badge bg-info">عمولات</span>
                                            @break
                                        @case('liability')
                                            <span class="badge bg-warning">التزامات</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $entry->account_type }}</span>
                                    @endswitch
                                </td>
                                <td class="text-{{ $entry->entry_type === 'debit' ? 'danger' : 'muted' }}">
                                    {{ $entry->entry_type === 'debit' ? number_format($entry->amount, 2) . ' ر.س' : '' }}
                                </td>
                                <td class="text-{{ $entry->entry_type === 'credit' ? 'success' : 'muted' }}">
                                    {{ $entry->entry_type === 'credit' ? number_format($entry->amount, 2) . ' ر.س' : '' }}
                                </td>
                                <td>
                                    <small class="text-muted">{{ $entry->reference_type ?? 'N/A' }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="3">الإجمالي</td>
                                <td class="text-danger">
                                    {{ number_format($contract->accountingEntries->where('entry_type', 'debit')->sum('amount'), 2) }} ر.س
                                </td>
                                <td class="text-success">
                                    {{ number_format($contract->accountingEntries->where('entry_type', 'credit')->sum('amount'), 2) }} ر.س
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-calculator fa-3x mb-3"></i>
                    <p>لا توجد قيود محاسبية لهذا العقد</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- مودال تحديث الحالة -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تحديث حالة العقد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST" action="{{ route('admin.financial.contracts.update-status', $contract->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="status" id="newStatus">
                    
                    <div class="mb-3">
                        <label class="form-label">سبب التغيير</label>
                        <textarea name="reason" class="form-control" rows="3" 
                                  placeholder="اختياري - أدخل سبب تغيير حالة العقد"></textarea>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle ms-2"></i>
                        <strong>تنبيه:</strong> هذا الإجراء سيؤثر على القيود المحاسبية والفواتير المرتبطة بالعقد.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تأكيد التغيير</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // تحديث حالة العقد
    function updateStatus(newStatus) {
        document.getElementById('newStatus').value = newStatus;
        $('#statusModal').modal('show');
    }

    // تحميل العقد
    function downloadContract() {
        window.open(`/admin/financial/contracts/{{ $contract->id }}/download`, '_blank');
    }

    // طباعة العقد
    function printContract() {
        window.print();
    }

    // تأكيد الدفعة
    function confirmPayment(paymentId) {
        if (confirm('هل أنت متأكد من تأكيد هذه الدفعة؟')) {
            showLoading();
            
            $.post(`/admin/financial/payments/${paymentId}/confirm`)
                .done(function(response) {
                    hideLoading();
                    location.reload();
                })
                .fail(function() {
                    hideLoading();
                    alert('حدث خطأ في تأكيد الدفعة');
                });
        }
    }

    // رفض الدفعة
    function rejectPayment(paymentId) {
        if (confirm('هل أنت متأكد من رفض هذه الدفعة؟')) {
            showLoading();
            
            $.post(`/admin/financial/payments/${paymentId}/reject`)
                .done(function(response) {
                    hideLoading();
                    location.reload();
                })
                .fail(function() {
                    hideLoading();
                    alert('حدث خطأ في رفض الدفعة');
                });
        }
    }

    // عرض تفاصيل الفاتورة
    function viewInvoiceDetails(invoiceId) {
        // يمكن إضافة مودال لعرض تفاصيل الفاتورة
        alert(`عرض تفاصيل الفاتورة ${invoiceId}`);
    }

    // تحميل الفاتورة
    function downloadInvoice(invoiceId) {
        window.open(`/admin/financial/invoices/${invoiceId}/download`, '_blank');
    }

    // عرض تفاصيل الدفعة
    function viewPaymentDetails(paymentId) {
        // يمكن إضافة مودال لعرض تفاصيل الدفعة
        alert(`عرض تفاصيل الدفعة ${paymentId}`);
    }

    // CSS خاص للطباعة
    const printStyles = `
        <style>
            @media print {
                .btn, .dropdown, .breadcrumb, .alert {
                    display: none !important;
                }
                .card {
                    border: 1px solid #ddd !important;
                    page-break-inside: avoid;
                }
                .card-header {
                    background-color: #f8f9fa !important;
                    color: #000 !important;
                }
            }
        </style>
    `;
    
    document.head.insertAdjacentHTML('beforeend', printStyles);
</script>
@endsection
