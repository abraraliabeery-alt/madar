@extends('client.financial.layout')

@section('title', 'عقودي - منطقة العميل')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">عقودي</h2>
                <p class="text-muted mb-0">جميع عقودك ومتابعة حالتها</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                    <i class="fas fa-filter"></i> فلاتر
                </button>
                <a href="{{ route('client.financial.offers') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> عقد جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="icon primary">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="number">{{ $contracts->total() }}</div>
                    <div class="label">إجمالي العقود</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="stats-card warning">
            <div class="d-flex align-items-center">
                <div class="icon warning">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="number">{{ $contracts->where('status', 'draft')->count() }}</div>
                    <div class="label">عقود معلقة</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="stats-card success">
            <div class="d-flex align-items-center">
                <div class="icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="number">{{ $contracts->where('status', 'active')->count() }}</div>
                    <div class="label">عقود نشطة</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="icon info">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="number">{{ number_format($contracts->sum('total_amount'), 0) }}</div>
                    <div class="label">القيمة الإجمالية (ر.س)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- قسم الفلاتر -->
<div class="collapse mb-4" id="filtersCollapse">
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('client.financial.contracts') }}" id="filterForm">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">حالة العقد</label>
                        <select name="status" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>معلق</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">نوع العقد</label>
                        <select name="type" class="form-select">
                            <option value="">جميع الأنواع</option>
                            <option value="sale" {{ request('type') === 'sale' ? 'selected' : '' }}>بيع</option>
                            <option value="rent" {{ request('type') === 'rent' ? 'selected' : '' }}>إيجار</option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">المؤسسة</label>
                        <select name="facility_id" class="form-select">
                            <option value="">جميع المؤسسات</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3 d-flex align-items-end">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('client.financial.contracts') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- قائمة العقود -->
@if($contracts->count() > 0)
<div class="row">
    @foreach($contracts as $contract)
    <div class="col-12 mb-4">
        <div class="card contract-card">
            <div class="card-body">
                <div class="row">
                    <!-- معلومات العقد الأساسية -->
                    <div class="col-lg-8">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">{{ $contract->contract_number }}</h5>
                                <h6 class="text-muted mb-0">{{ $contract->product->getTranslatedTitle() }}</h6>
                            </div>
                            <div class="text-end">
                                @switch($contract->status)
                                    @case('draft')
                                        <span class="badge bg-warning fs-6">معلق</span>
                                        @break
                                    @case('active')
                                        <span class="badge bg-success fs-6">نشط</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-info fs-6">مكتمل</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger fs-6">ملغي</span>
                                        @break
                                @endswitch
                                
                                @if($contract->contract_type === 'sale')
                                    <span class="badge bg-success ms-1">بيع</span>
                                @else
                                    <span class="badge bg-primary ms-1">إيجار</span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fas fa-map-marker-alt text-muted ms-2"></i>
                                    <strong>الموقع:</strong> {{ Str::limit($contract->product->address, 50) }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-building text-muted ms-2"></i>
                                    <strong>المؤسسة:</strong> {{ $contract->facility->name }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-calendar text-muted ms-2"></i>
                                    <strong>تاريخ الإنشاء:</strong> {{ $contract->created_at->format('Y/m/d') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fas fa-coins text-muted ms-2"></i>
                                    <strong>المبلغ الإجمالي:</strong> 
                                    <span class="text-primary fw-bold">{{ number_format($contract->total_amount, 2) }} ر.س</span>
                                </p>
                                @if($contract->deposit_amount > 0)
                                <p class="mb-2">
                                    <i class="fas fa-hand-holding-usd text-muted ms-2"></i>
                                    <strong>العربون:</strong> 
                                    <span class="text-warning">{{ number_format($contract->deposit_amount, 2) }} ر.س</span>
                                </p>
                                @endif
                                <p class="mb-2">
                                    <i class="fas fa-percentage text-muted ms-2"></i>
                                    <strong>العمولة:</strong> 
                                    <span class="text-info">{{ number_format($contract->commission_amount, 2) }} ر.س</span>
                                </p>
                            </div>
                        </div>

                        <!-- شريط تقدم المدفوعات -->
                        @php
                            $totalPaid = $contract->getTotalPaidAmount();
                            $paymentProgress = $contract->total_amount > 0 ? ($totalPaid / $contract->total_amount) * 100 : 0;
                        @endphp
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">حالة السداد</small>
                                <small class="text-muted">{{ number_format($paymentProgress, 1) }}%</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" style="width: {{ $paymentProgress }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-success">مدفوع: {{ number_format($totalPaid, 2) }} ر.س</small>
                                <small class="text-warning">متبقي: {{ number_format($contract->getRemainingAmount(), 2) }} ر.س</small>
                            </div>
                        </div>
                    </div>

                    <!-- الإجراءات -->
                    <div class="col-lg-4">
                        <div class="d-flex flex-column h-100 justify-content-between">
                            <!-- ملخص سريع -->
                            <div class="text-center mb-3">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center">
                                            <div class="text-primary fw-bold">{{ $contract->invoices->count() }}</div>
                                            <small class="text-muted">فاتورة</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center">
                                            <div class="text-success fw-bold">{{ $contract->payments->where('status', 'confirmed')->count() }}</div>
                                            <small class="text-muted">دفعة</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center">
                                            <div class="text-warning fw-bold">{{ $contract->payments->where('status', 'pending')->count() }}</div>
                                            <small class="text-muted">معلق</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- أزرار الإجراءات -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('client.financial.contract-details', $contract->id) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-eye ms-1"></i>
                                    عرض التفاصيل
                                </a>

                                @if($contract->status === 'active' && $contract->getRemainingAmount() > 0)
                                <button type="button" class="btn btn-success" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#paymentModal"
                                        onclick="openPaymentModal({{ $contract->id }}, '{{ $contract->contract_number }}', {{ $contract->getRemainingAmount() }})">
                                    <i class="fas fa-credit-card ms-1"></i>
                                    دفع مستحقات
                                </button>
                                @endif

                                @if($contract->status === 'draft')
                                <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="cancelContract({{ $contract->id }})"
                                        data-bs-toggle="tooltip" title="إلغاء العقد">
                                    <i class="fas fa-times ms-1"></i>
                                    إلغاء العقد
                                </button>
                                @endif

                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                                            onclick="printContract({{ $contract->id }})"
                                            data-bs-toggle="tooltip" title="طباعة العقد">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="shareContract({{ $contract->id }})"
                                            data-bs-toggle="tooltip" title="مشاركة العقد">
                                        <i class="fas fa-share"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
@if($contracts->hasPages())
<div class="d-flex justify-content-center">
    {{ $contracts->appends(request()->query())->links() }}
</div>
@endif

@else
<div class="empty-state">
    <i class="fas fa-file-contract"></i>
    <h4>لا توجد عقود</h4>
    <p>لم تقم بإنشاء أي عقود بعد.</p>
    <a href="{{ route('client.financial.offers') }}" class="btn btn-primary">
        <i class="fas fa-search"></i> تصفح العروض المتاحة
    </a>
</div>
@endif

<!-- مودال الدفع -->
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
                    <input type="hidden" name="contract_id" id="paymentContractId">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle ms-2"></i>
                        <strong>العقد:</strong> <span id="paymentContractNumber"></span>
                        <br>
                        <strong>المبلغ المتبقي:</strong> <span id="paymentRemainingAmount"></span> ر.س
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المبلغ المراد دفعه *</label>
                        <div class="input-group">
                            <input type="number" name="amount" class="form-control" id="paymentAmount" 
                                   min="1" step="0.01" required>
                            <span class="input-group-text">ر.س</span>
                        </div>
                        <small class="text-muted">يمكنك دفع أي مبلغ حتى المبلغ المتبقي</small>
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
@endsection

@section('scripts')
<script>
    // فتح مودال الدفع
    function openPaymentModal(contractId, contractNumber, remainingAmount) {
        document.getElementById('paymentContractId').value = contractId;
        document.getElementById('paymentContractNumber').textContent = contractNumber;
        document.getElementById('paymentRemainingAmount').textContent = formatNumber(remainingAmount);
        document.getElementById('paymentAmount').max = remainingAmount;
    }

    // إلغاء العقد
    function cancelContract(contractId) {
        if (confirm('هل أنت متأكد من إلغاء هذا العقد؟\n\nلن تتمكن من التراجع عن هذا الإجراء.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/client/financial/contracts/${contractId}/cancel`;
            
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

    // طباعة العقد
    function printContract(contractId) {
        window.open(`/client/financial/contracts/${contractId}/print`, '_blank');
    }

    // مشاركة العقد
    function shareContract(contractId) {
        const url = window.location.origin + `/client/financial/contracts/${contractId}`;
        if (navigator.share) {
            navigator.share({
                title: 'عقد المشروع',
                url: url
            });
        } else {
            // نسخ الرابط للحافظة
            navigator.clipboard.writeText(url).then(() => {
                alert('تم نسخ رابط العقد للحافظة');
            });
        }
    }

    $(document).ready(function() {
        // تطبيق الفلاتر تلقائياً
        $('#filterForm select').on('change', function() {
            $('#filterForm').submit();
        });

        // تهيئة tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // تحسين نموذج الدفع
        $('#paymentForm').on('submit', function() {
            showLoading();
        });

        // التحقق من صحة المبلغ
        $('#paymentAmount').on('input', function() {
            const amount = parseFloat($(this).val());
            const maxAmount = parseFloat($(this).attr('max'));
            
            if (amount > maxAmount) {
                $(this).val(maxAmount);
                alert('المبلغ لا يمكن أن يكون أكبر من المبلغ المتبقي');
            }
        });

        // إضافة رسوم متحركة للبطاقات
        $('.contract-card').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
            $(this).addClass('animate-fade-in-up');
        });

        // تحسين hover effects
        $('.contract-card').hover(
            function() {
                $(this).addClass('shadow-lg');
            },
            function() {
                $(this).removeClass('shadow-lg');
            }
        );

        // إظهار الفلاتر إذا كانت مطبقة
        const hasActiveFilters = {{ request()->hasAny(['status', 'type', 'facility_id']) ? 'true' : 'false' }};
        if (hasActiveFilters) {
            $('#filtersCollapse').addClass('show');
        }
    });
</script>
@endsection
