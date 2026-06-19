@extends('client.financial.layout')

@section('title', 'تفاصيل العقد - ' . $contract->contract_number)

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('client.financial.dashboard') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('client.financial.contracts') }}">عقودي</a></li>
                <li class="breadcrumb-item active">{{ $contract->contract_number }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- رأس الصفحة -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="mb-1">{{ $contract->contract_number }}</h3>
                        <h5 class="text-muted mb-2">{{ $contract->product->getTranslatedTitle() }}</h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-map-marker-alt ms-1"></i>
                            {{ $contract->product->address }}
                        </p>
                    </div>
                    <div class="text-end">
                        @switch($contract->status)
                            @case('draft')
                                <span class="badge bg-warning fs-5 mb-2">في الانتظار</span>
                                <br><small class="text-muted">يحتاج موافقة المؤسسة</small>
                                @break
                            @case('active')
                                <span class="badge bg-success fs-5 mb-2">نشط ومفعل</span>
                                <br><small class="text-success">العقد نشط</small>
                                @break
                            @case('completed')
                                <span class="badge bg-info fs-5 mb-2">مكتمل</span>
                                <br><small class="text-info">العقد مكتمل</small>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger fs-5 mb-2">ملغي</span>
                                <br><small class="text-danger">العقد ملغي</small>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- معلومات العقد الرئيسية -->
    <div class="col-lg-8 mb-4">
        <!-- ملخص المدفوعات -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie ms-2"></i>
                    ملخص المدفوعات
                </h5>
            </div>
            <div class="card-body">
                @php
                    $totalPaid = $contract->getTotalPaidAmount();
                    $remainingAmount = $contract->getRemainingAmount();
                    $paymentProgress = $contract->total_amount > 0 ? ($totalPaid / $contract->total_amount) * 100 : 0;
                @endphp
                
                <div class="row text-center mb-4">
                    <div class="col-md-4">
                        <div class="border-end">
                            <h4 class="text-primary">{{ number_format($contract->total_amount, 2) }}</h4>
                            <small class="text-muted">المبلغ الإجمالي (ر.س)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border-end">
                            <h4 class="text-success">{{ number_format($totalPaid, 2) }}</h4>
                            <small class="text-muted">المبلغ المدفوع (ر.س)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-{{ $remainingAmount > 0 ? 'warning' : 'success' }}">{{ number_format($remainingAmount, 2) }}</h4>
                        <small class="text-muted">المبلغ المتبقي (ر.س)</small>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">تقدم السداد</span>
                        <span class="fw-bold">{{ number_format($paymentProgress, 1) }}%</span>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar bg-gradient" style="width: {{ $paymentProgress }}%"></div>
                    </div>
                </div>

                @if($remainingAmount > 0 && $contract->status === 'active')
                <div class="text-center">
                    <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="fas fa-credit-card ms-2"></i>
                        دفع مستحقات ({{ number_format($remainingAmount, 2) }} ر.س)
                    </button>
                </div>
                @elseif($remainingAmount <= 0)
                <div class="alert alert-success text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h5>تم السداد بالكامل!</h5>
                    <p class="mb-0">تم إكمال جميع المدفوعات بنجاح</p>
                </div>
                @endif
            </div>
        </div>

        <!-- تفاصيل العقد -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle ms-2"></i>
                    تفاصيل العقد
                </h5>
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
                                <td class="fw-bold">تاريخ البداية:</td>
                                <td>{{ $contract->start_date ? $contract->start_date->format('Y/m/d') : 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">تاريخ النهاية:</td>
                                <td>{{ $contract->end_date ? $contract->end_date->format('Y/m/d') : 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">تاريخ الإنشاء:</td>
                                <td>{{ $contract->created_at->format('Y/m/d H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">المبلغ الإجمالي:</td>
                                <td class="text-primary fw-bold">{{ number_format($contract->total_amount, 2) }} ر.س</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">مبلغ العربون:</td>
                                <td>{{ number_format($contract->deposit_amount, 2) }} ر.س</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">نسبة العمولة:</td>
                                <td>{{ number_format($contract->commission_rate * 100, 2) }}%</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">مبلغ العمولة:</td>
                                <td>{{ number_format($contract->commission_amount, 2) }} ر.س</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">المؤسسة:</td>
                                <td>{{ $contract->facility->name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($contract->notes)
                <div class="mt-3">
                    <h6>ملاحظات:</h6>
                    <div class="alert alert-light">
                        {{ $contract->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- الفواتير -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice ms-2"></i>
                        الفواتير
                    </h5>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>لا توجد فواتير لهذا العقد بعد</p>
                </div>
                @endif
            </div>
        </div>

        <!-- المدفوعات -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card ms-2"></i>
                        المدفوعات
                    </h5>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-credit-card fa-3x mb-3"></i>
                    <p>لا توجد مدفوعات لهذا العقد بعد</p>
                    @if($contract->status === 'active' && $remainingAmount > 0)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        إجراء أول دفعة
                    </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- اللوحة الجانبية -->
    <div class="col-lg-4">
        <!-- معلومات المشروع -->
        <div class="card mb-4 sticky-top" style="top: 20px;">
            <div class="card-header">
                <h6 class="mb-0">معلومات المشروع</h6>
            </div>
            <div class="card-body">
                @if($contract->product->gallery && $contract->product->gallery->count() > 0)
                <img src="{{ $contract->product->gallery->first()->image_url }}" 
                     class="img-fluid rounded mb-3" alt="{{ $contract->product->getTranslatedTitle() }}">
                @endif
                
                <h6>{{ $contract->product->getTranslatedTitle() }}</h6>
                <p class="text-muted mb-2">
                    <i class="fas fa-map-marker-alt ms-1"></i>
                    {{ $contract->product->address }}
                </p>
                <p class="text-muted mb-2">
                    <i class="fas fa-tag ms-1"></i>
                    {{ $contract->product->category->getTranslatedName() ?? 'غير محدد' }}
                </p>

                @if($contract->product->description)
                <div class="mt-3">
                    <h6>الوصف:</h6>
                    <p class="text-muted small">{{ Str::limit($contract->product->description, 150) }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- معلومات المؤسسة -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">معلومات المؤسسة</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary rounded-circle p-3 text-white d-inline-flex">
                        <i class="fas fa-building fa-lg"></i>
                    </div>
                    <h6 class="mt-2 mb-0">{{ $contract->facility->name }}</h6>
                </div>

                @if($contract->facility->email)
                <p class="mb-2">
                    <i class="fas fa-envelope text-muted ms-2"></i>
                    <a href="mailto:{{ $contract->facility->email }}">{{ $contract->facility->email }}</a>
                </p>
                @endif

                @if($contract->facility->phone_number)
                <p class="mb-2">
                    <i class="fas fa-phone text-muted ms-2"></i>
                    <a href="tel:{{ $contract->facility->phone_number }}">{{ $contract->facility->phone_number }}</a>
                </p>
                @endif
            </div>
        </div>

        <!-- معلومات المالك -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">معلومات المالك</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-success rounded-circle p-3 text-white d-inline-flex">
                        <i class="fas fa-user-tie fa-lg"></i>
                    </div>
                    <h6 class="mt-2 mb-0">{{ $contract->owner->name }}</h6>
                </div>

                <p class="mb-2">
                    <i class="fas fa-envelope text-muted ms-2"></i>
                    {{ $contract->owner->email }}
                </p>

                @if($contract->owner->phone_number)
                <p class="mb-2">
                    <i class="fas fa-phone text-muted ms-2"></i>
                    {{ $contract->owner->phone_number }}
                </p>
                @endif
            </div>
        </div>

        <!-- إجراءات سريعة -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">إجراءات سريعة</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($contract->status === 'active' && $remainingAmount > 0)
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="fas fa-credit-card ms-1"></i>
                        دفع مستحقات
                    </button>
                    @endif

                    <a href="{{ route('client.financial.print-contract', $contract->id) }}" 
                       target="_blank" class="btn btn-outline-primary">
                        <i class="fas fa-print ms-1"></i>
                        طباعة العقد
                    </a>

                    <a href="{{ route('client.financial.invoices') }}?contract_id={{ $contract->id }}" 
                       class="btn btn-outline-info">
                        <i class="fas fa-file-invoice ms-1"></i>
                        عرض الفواتير
                    </a>

                    <a href="{{ route('client.financial.payments') }}?contract_id={{ $contract->id }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-history ms-1"></i>
                        تاريخ المدفوعات
                    </a>

                    @if($contract->status === 'draft')
                    <button type="button" class="btn btn-danger" onclick="cancelContract()">
                        <i class="fas fa-times ms-1"></i>
                        إلغاء العقد
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال الدفع -->
@if($contract->status === 'active' && $remainingAmount > 0)
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إجراء دفعة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('client.financial.make-payment') }}" id="paymentForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="contract_id" value="{{ $contract->id }}">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle ms-2"></i>
                        <strong>العقد:</strong> {{ $contract->contract_number }}
                        <br>
                        <strong>المبلغ المتبقي:</strong> {{ number_format($remainingAmount, 2) }} ر.س
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المبلغ المراد دفعه *</label>
                        <div class="input-group">
                            <input type="number" name="amount" class="form-control" 
                                   min="1" max="{{ $remainingAmount }}" step="0.01" required>
                            <span class="input-group-text">ر.س</span>
                        </div>
                        <small class="text-muted">الحد الأقصى: {{ number_format($remainingAmount, 2) }} ر.س</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">طريقة الدفع *</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">اختر طريقة الدفع</option>
                            <option value="cash">نقداً</option>
                            <option value="bank_transfer">تحويل بنكي</option>
                            <option value="credit_card">بطاقة ائتمان</option>
                            <option value="check">شيك</option>
                            <option value="online">دفع إلكتروني</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">رقم المرجع</label>
                        <input type="text" name="reference_number" class="form-control" 
                               placeholder="رقم التحويل أو الإيصال (اختياري)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="أي ملاحظات إضافية..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-credit-card ms-1"></i>
                        إرسال الدفعة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    function cancelContract() {
        if (confirm('هل أنت متأكد من إلغاء هذا العقد؟\n\nلن تتمكن من التراجع عن هذا الإجراء.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("client.financial.cancel-contract", $contract->id) }}';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = $('meta[name="csrf-token"]').attr('content');
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            
            showLoading();
            form.submit();
        }
    }

    $(document).ready(function() {
        // تحسين نموذج الدفع
        $('#paymentForm').on('submit', function() {
            showLoading();
        });

        // التحقق من صحة المبلغ
        $('input[name="amount"]').on('input', function() {
            const amount = parseFloat($(this).val());
            const maxAmount = parseFloat($(this).attr('max'));
            
            if (amount > maxAmount) {
                $(this).val(maxAmount);
                alert('المبلغ لا يمكن أن يكون أكبر من المبلغ المتبقي');
            }
        });

        // تفعيل tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // إضافة تأثيرات للبطاقات
        $('.card').addClass('animate-fade-in-up');
        $('.card').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });

        // Sticky sidebar
        const sidebar = $('.sticky-top');
        if (sidebar.length) {
            $(window).scroll(function() {
                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();
                const sidebarHeight = sidebar.height();
                
                if (scrollTop + windowHeight + sidebarHeight >= documentHeight) {
                    sidebar.css('top', documentHeight - scrollTop - sidebarHeight - 20);
                } else {
                    sidebar.css('top', '20px');
                }
            });
        }
    });
</script>
@endsection
