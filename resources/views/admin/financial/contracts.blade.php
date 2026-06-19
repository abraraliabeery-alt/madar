@extends('admin.financial.layout')

@section('title', 'إدارة العقود - النظام المالي للأدمن')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-contract ms-2"></i>إدارة العقود</h2>
    <div class="btn-group">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="fas fa-download"></i> تصدير
        </button>
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
            <i class="fas fa-tasks"></i> إجراءات متعددة
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i> تحديث
        </button>
    </div>
</div>

<!-- فلاتر البحث -->
<div class="filter-section">
    <form method="GET" action="{{ route('admin.financial.contracts') }}" id="filterForm">
        <div class="row">
            <div class="col-lg-2 col-md-6 mb-3">
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

            <div class="col-lg-2 col-md-6 mb-3">
                <label class="form-label">حالة العقد</label>
                <select name="status" class="form-select">
                    <option value="">جميع الحالات</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-6 mb-3">
                <label class="form-label">نوع العقد</label>
                <select name="type" class="form-select">
                    <option value="">جميع الأنواع</option>
                    <option value="sale" {{ request('type') === 'sale' ? 'selected' : '' }}>بيع</option>
                    <option value="rent" {{ request('type') === 'rent' ? 'selected' : '' }}>إيجار</option>
                </select>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <label class="form-label">البحث</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="رقم العقد أو اسم العميل..." 
                       value="{{ request('search') }}">
            </div>

            <div class="col-lg-2 col-md-6 mb-3 d-flex align-items-end">
                <div class="btn-group w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> بحث
                    </button>
                    <a href="{{ route('admin.financial.contracts') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ملخص النتائج -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">إجمالي العقود</h6>
                    <div class="number">{{ $contracts->total() }}</div>
                </div>
                <div class="icon text-primary">
                    <i class="fas fa-file-contract"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-2">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">العقود المعلقة</h6>
                    <div class="number">{{ $contracts->where('status', 'draft')->count() }}</div>
                </div>
                <div class="icon text-warning">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-2">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">القيمة الإجمالية</h6>
                    <div class="number">{{ number_format($contracts->sum('total_amount'), 0) }}</div>
                    <small class="text-muted">ر.س</small>
                </div>
                <div class="icon text-success">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-2">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">إجمالي العمولات</h6>
                    <div class="number">{{ number_format($contracts->sum('commission_amount'), 0) }}</div>
                    <small class="text-muted">ر.س</small>
                </div>
                <div class="icon text-info">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- جدول العقود -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list ms-2"></i>قائمة العقود
                <span class="badge bg-secondary me-2">{{ $contracts->total() }}</span>
            </h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                <label class="form-check-label" for="selectAll">تحديد الكل</label>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($contracts->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" class="form-check-input" id="selectAllHeader" onchange="toggleSelectAll()">
                        </th>
                        <th>عقد المشروع</th>
                        <th>العميل</th>
                        <th>المالك</th>
                        <th>المشروع</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contracts as $contract)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input contract-checkbox" value="{{ $contract->id }}">
                        </td>
                        <td>
                            <div>
                                <strong>{{ $contract->contract_number }}</strong>
                                <br><small class="text-muted">{{ $contract->facility->name }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $contract->user->name }}</strong>
                                <br><small class="text-muted">{{ $contract->user->email }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $contract->owner->name }}</strong>
                                <br><small class="text-muted">{{ $contract->owner->phone_number ?? 'غير متوفر' }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $contract->product->getTranslatedTitle() }}</strong>
                                <br><small class="text-muted">{{ Str::limit($contract->product->address, 30) }}</small>
                            </div>
                        </td>
                        <td>
                            @if($contract->contract_type === 'sale')
                                <span class="badge bg-success">بيع</span>
                            @else
                                <span class="badge bg-primary">إيجار</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ number_format($contract->total_amount, 2) }} ر.س</div>
                            @if($contract->deposit_amount > 0)
                            <small class="text-muted">عربون: {{ number_format($contract->deposit_amount, 2) }} ر.س</small>
                            @endif
                            <br><small class="text-warning">عمولة: {{ number_format($contract->commission_amount, 2) }} ر.س</small>
                        </td>
                        <td>
                            @switch($contract->status)
                                @case('draft')
                                    <span class="badge bg-warning">مسودة</span>
                                    @break
                                @case('active')
                                    <span class="badge bg-success">نشط</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-info">مكتمل</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">ملغي</span>
                                    @break
                            @endswitch
                            
                            <!-- إشارة للمدفوعات -->
                            <br>
                            @php
                                $totalPaid = $contract->getTotalPaidAmount();
                                $paymentProgress = $contract->total_amount > 0 ? ($totalPaid / $contract->total_amount) * 100 : 0;
                            @endphp
                            <div class="progress mt-1" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: {{ $paymentProgress }}%"></div>
                            </div>
                            <small class="text-muted">مدفوع: {{ number_format($paymentProgress, 1) }}%</small>
                        </td>
                        <td>
                            <span class="timestamp" data-timestamp="{{ $contract->created_at }}">
                                {{ $contract->created_at->format('Y/m/d') }}
                            </span>
                            <br><small class="text-muted">{{ $contract->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.financial.contract-details', $contract->id) }}" 
                                   class="btn btn-outline-primary" 
                                   data-bs-toggle="tooltip" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-outline-info" 
                                        onclick="viewInvoices({{ $contract->id }})" 
                                        data-bs-toggle="tooltip" title="الفواتير">
                                    <i class="fas fa-file-invoice"></i>
                                </button>
                                <button type="button" class="btn btn-outline-success" 
                                        onclick="viewPayments({{ $contract->id }})" 
                                        data-bs-toggle="tooltip" title="المدفوعات">
                                    <i class="fas fa-credit-card"></i>
                                </button>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if($contract->status === 'draft')
                                        <li>
                                            <button class="dropdown-item" onclick="updateContractStatus({{ $contract->id }}, 'active')">
                                                <i class="fas fa-check ms-2 text-success"></i>تفعيل العقد
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item" onclick="updateContractStatus({{ $contract->id }}, 'cancelled')">
                                                <i class="fas fa-times ms-2 text-danger"></i>إلغاء العقد
                                            </button>
                                        </li>
                                        @endif
                                        @if($contract->status === 'active')
                                        <li>
                                            <button class="dropdown-item" onclick="updateContractStatus({{ $contract->id }}, 'completed')">
                                                <i class="fas fa-flag-checkered ms-2 text-info"></i>إكمال العقد
                                            </button>
                                        </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item" onclick="downloadContract({{ $contract->id }})">
                                                <i class="fas fa-download ms-2"></i>تحميل العقد
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item" onclick="viewAccountingEntries({{ $contract->id }})">
                                                <i class="fas fa-calculator ms-2"></i>القيود المحاسبية
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($contracts->hasPages())
        <div class="card-footer">
            {{ $contracts->appends(request()->query())->links() }}
        </div>
        @endif
        
        @else
        <div class="empty-state">
            <i class="fas fa-file-contract"></i>
            <h4>لا توجد عقود</h4>
            <p class="text-muted">لم يتم العثور على أي عقود تطابق معايير البحث المحددة.</p>
            <a href="{{ route('admin.financial.contracts') }}" class="btn btn-primary">
                <i class="fas fa-refresh"></i> عرض جميع العقود
            </a>
        </div>
        @endif
    </div>
</div>

<!-- مودال الفواتير -->
<div class="modal fade" id="invoicesModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">فواتير العقد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="invoicesContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال المدفوعات -->
<div class="modal fade" id="paymentsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">مدفوعات العقد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="paymentsContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال القيود المحاسبية -->
<div class="modal fade" id="accountingModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">القيود المحاسبية للعقد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="accountingContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال تحديث حالة العقد -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تحديث حالة العقد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <input type="hidden" name="contract_id" id="contractId">
                    <input type="hidden" name="new_status" id="newStatus">
                    
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

<!-- مودال الإجراءات المتعددة -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إجراءات متعددة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">اختر الإجراء</label>
                    <select id="bulkAction" class="form-select">
                        <option value="">-- اختر الإجراء --</option>
                        <option value="activate">تفعيل العقود المحددة</option>
                        <option value="cancel">إلغاء العقود المحددة</option>
                        <option value="export">تصدير العقود المحددة</option>
                        <option value="send_notifications">إرسال إشعارات</option>
                    </select>
                </div>
                
                <div id="selectedCount" class="alert alert-info">
                    لم يتم تحديد أي عقود
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">تنفيذ</button>
            </div>
        </div>
    </div>
</div>

<!-- مودال التصدير -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تصدير العقود</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="mb-3">
                        <label class="form-label">تنسيق التصدير</label>
                        <select name="format" class="form-select" required>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">نطاق التصدير</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="scope" value="current" id="current" checked>
                            <label class="form-check-label" for="current">
                                النتائج الحالية ({{ $contracts->total() }} عقد)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="scope" value="all" id="all">
                            <label class="form-check-label" for="all">
                                جميع العقود
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="scope" value="selected" id="selected">
                            <label class="form-check-label" for="selected">
                                العقود المحددة فقط (<span id="selectedCountExport">0</span>)
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">تضمين</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_invoices" id="includeInvoices">
                            <label class="form-check-label" for="includeInvoices">الفواتير</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_payments" id="includePayments">
                            <label class="form-check-label" for="includePayments">المدفوعات</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_accounting" id="includeAccounting">
                            <label class="form-check-label" for="includeAccounting">القيود المحاسبية</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-success" onclick="exportContracts()">
                    <i class="fas fa-download"></i> تصدير
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let selectedContracts = [];

    // تحديد/إلغاء تحديد جميع العقود
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll') || document.getElementById('selectAllHeader');
        const checkboxes = document.querySelectorAll('.contract-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        
        updateSelectedContracts();
    }

    // تحديث قائمة العقود المحددة
    function updateSelectedContracts() {
        selectedContracts = Array.from(document.querySelectorAll('.contract-checkbox:checked'))
            .map(checkbox => parseInt(checkbox.value));
        
        document.getElementById('selectedCount').innerHTML = 
            selectedContracts.length > 0 
                ? `تم تحديد ${selectedContracts.length} عقد` 
                : 'لم يتم تحديد أي عقود';
        
        document.getElementById('selectedCountExport').textContent = selectedContracts.length;
    }

    // عرض الفواتير
    function viewInvoices(contractId) {
        $('#invoicesModal').modal('show');
        
        $.get(`/api/v1/admin/contracts/${contractId}/invoices`)
            .done(function(response) {
                if (response.success && response.data.length > 0) {
                    displayInvoices(response.data);
                } else {
                    $('#invoicesContent').html('<div class="text-center text-muted"><i class="fas fa-inbox fa-2x mb-2"></i><p>لا توجد فواتير لهذا العقد</p></div>');
                }
            })
            .fail(function() {
                $('#invoicesContent').html('<div class="alert alert-danger">حدث خطأ في جلب الفواتير</div>');
            });
    }

    // عرض المدفوعات
    function viewPayments(contractId) {
        $('#paymentsModal').modal('show');
        
        $.get(`/api/v1/admin/contracts/${contractId}/payments`)
            .done(function(response) {
                if (response.success && response.data.length > 0) {
                    displayPayments(response.data);
                } else {
                    $('#paymentsContent').html('<div class="text-center text-muted"><i class="fas fa-inbox fa-2x mb-2"></i><p>لا توجد مدفوعات لهذا العقد</p></div>');
                }
            })
            .fail(function() {
                $('#paymentsContent').html('<div class="alert alert-danger">حدث خطأ في جلب المدفوعات</div>');
            });
    }

    // عرض القيود المحاسبية
    function viewAccountingEntries(contractId) {
        $('#accountingModal').modal('show');
        
        $.get(`/api/v1/admin/accounting-entries?contract_id=${contractId}`)
            .done(function(response) {
                if (response.success && response.data.data.length > 0) {
                    displayAccountingEntries(response.data.data);
                } else {
                    $('#accountingContent').html('<div class="text-center text-muted"><i class="fas fa-inbox fa-2x mb-2"></i><p>لا توجد قيود محاسبية لهذا العقد</p></div>');
                }
            })
            .fail(function() {
                $('#accountingContent').html('<div class="alert alert-danger">حدث خطأ في جلب القيود المحاسبية</div>');
            });
    }

    // عرض الفواتير في المودال
    function displayInvoices(invoices) {
        let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>رقم الفاتورة</th><th>النوع</th><th>المبلغ</th><th>المدفوع</th><th>المتبقي</th><th>تاريخ الاستحقاق</th><th>الحالة</th></tr></thead><tbody>';
        
        invoices.forEach(invoice => {
            html += `
                <tr>
                    <td><strong>${invoice.invoice_number}</strong></td>
                    <td><span class="badge bg-${getInvoiceTypeColor(invoice.invoice_type)}">${getInvoiceTypeText(invoice.invoice_type)}</span></td>
                    <td>${formatCurrency(invoice.amount)}</td>
                    <td>${formatCurrency(invoice.paid_amount)}</td>
                    <td>${formatCurrency(invoice.remaining_amount)}</td>
                    <td>${formatDate(invoice.due_date)}</td>
                    <td><span class="badge bg-${getInvoiceStatusColor(invoice.status)}">${getInvoiceStatusText(invoice.status)}</span></td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        $('#invoicesContent').html(html);
    }

    // عرض المدفوعات في المودال
    function displayPayments(payments) {
        let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>رقم المرجع</th><th>المبلغ</th><th>طريقة الدفع</th><th>تاريخ الدفع</th><th>الحالة</th><th>الإجراءات</th></tr></thead><tbody>';
        
        payments.forEach(payment => {
            html += `
                <tr>
                    <td><strong>${payment.reference_number}</strong></td>
                    <td>${formatCurrency(payment.amount)}</td>
                    <td>${getPaymentMethodText(payment.payment_method)}</td>
                    <td>${formatDate(payment.payment_date)}</td>
                    <td><span class="badge bg-${getPaymentStatusColor(payment.status)}">${getPaymentStatusText(payment.status)}</span></td>
                    <td>
                        ${payment.status === 'pending' ? `
                            <button class="btn btn-sm btn-success" onclick="confirmPayment(${payment.id})">تأكيد</button>
                            <button class="btn btn-sm btn-danger" onclick="rejectPayment(${payment.id})">رفض</button>
                        ` : ''}
                    </td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        $('#paymentsContent').html(html);
    }

    // عرض القيود المحاسبية في المودال
    function displayAccountingEntries(entries) {
        let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>تاريخ القيد</th><th>الوصف</th><th>نوع الحساب</th><th>مدين</th><th>دائن</th><th>المرجع</th></tr></thead><tbody>';
        
        entries.forEach(entry => {
            html += `
                <tr>
                    <td>${formatDate(entry.entry_date)}</td>
                    <td>${entry.description}</td>
                    <td><span class="badge bg-info">${getAccountTypeText(entry.account_type)}</span></td>
                    <td>${entry.entry_type === 'debit' ? formatCurrency(entry.amount) : ''}</td>
                    <td>${entry.entry_type === 'credit' ? formatCurrency(entry.amount) : ''}</td>
                    <td><small class="text-muted">${entry.reference_type || 'N/A'}</small></td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        $('#accountingContent').html(html);
    }

    // تحديث حالة العقد
    function updateContractStatus(contractId, newStatus) {
        document.getElementById('contractId').value = contractId;
        document.getElementById('newStatus').value = newStatus;
        $('#statusModal').modal('show');
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

    // تحميل العقد
    function downloadContract(contractId) {
        window.open(`/admin/financial/contracts/${contractId}/download`, '_blank');
    }

    // تنفيذ الإجراء المتعدد
    function executeBulkAction() {
        const action = document.getElementById('bulkAction').value;
        
        if (!action) {
            alert('يرجى اختيار الإجراء المطلوب');
            return;
        }
        
        if (selectedContracts.length === 0) {
            alert('يرجى تحديد عقد واحد على الأقل');
            return;
        }
        
        if (confirm(`هل أنت متأكد من تنفيذ هذا الإجراء على ${selectedContracts.length} عقد؟`)) {
            showLoading();
            
            $.post('/admin/financial/contracts/bulk-action', {
                action: action,
                contracts: selectedContracts
            })
            .done(function(response) {
                hideLoading();
                location.reload();
            })
            .fail(function() {
                hideLoading();
                alert('حدث خطأ في تنفيذ الإجراء');
            });
        }
    }

    // تصدير العقود
    function exportContracts() {
        const formData = new FormData(document.getElementById('exportForm'));
        const params = new URLSearchParams(formData);
        
        // إضافة العقود المحددة إذا كان النطاق محدد
        if (formData.get('scope') === 'selected') {
            selectedContracts.forEach(id => params.append('selected_contracts[]', id));
        }
        
        // إضافة معايير البحث الحالية
        const currentParams = new URLSearchParams(window.location.search);
        for (let [key, value] of currentParams) {
            params.append(key, value);
        }
        
        window.open(`/admin/financial/contracts/export?${params.toString()}`, '_blank');
        $('#exportModal').modal('hide');
    }

    // دوال مساعدة للحصول على النصوص والألوان
    function getInvoiceTypeText(type) {
        const types = {
            'rent': 'إيجار',
            'sale': 'بيع',
            'deposit': 'عربون',
            'commission': 'عمولة',
            'refund': 'استرداد'
        };
        return types[type] || type;
    }

    function getInvoiceTypeColor(type) {
        const colors = {
            'rent': 'primary',
            'sale': 'success',
            'deposit': 'warning',
            'commission': 'info',
            'refund': 'danger'
        };
        return colors[type] || 'secondary';
    }

    function getInvoiceStatusText(status) {
        const statuses = {
            'draft': 'مسودة',
            'sent': 'مرسل',
            'paid': 'مدفوع',
            'overdue': 'متأخر',
            'cancelled': 'ملغي'
        };
        return statuses[status] || status;
    }

    function getInvoiceStatusColor(status) {
        const colors = {
            'draft': 'secondary',
            'sent': 'primary',
            'paid': 'success',
            'overdue': 'danger',
            'cancelled': 'dark'
        };
        return colors[status] || 'secondary';
    }

    function getPaymentMethodText(method) {
        const methods = {
            'cash': 'نقداً',
            'bank_transfer': 'تحويل بنكي',
            'credit_card': 'بطاقة ائتمان',
            'check': 'شيك',
            'online': 'عبر الإنترنت'
        };
        return methods[method] || method;
    }

    function getPaymentStatusText(status) {
        const statuses = {
            'pending': 'معلق',
            'confirmed': 'مؤكد',
            'failed': 'فشل',
            'refunded': 'مسترد'
        };
        return statuses[status] || status;
    }

    function getPaymentStatusColor(status) {
        const colors = {
            'pending': 'warning',
            'confirmed': 'success',
            'failed': 'danger',
            'refunded': 'info'
        };
        return colors[status] || 'secondary';
    }

    function getAccountTypeText(type) {
        const types = {
            'revenue': 'إيرادات',
            'receivable': 'ذمم مدينة',
            'commission': 'عمولات',
            'liability': 'التزامات',
            'expense': 'مصروفات'
        };
        return types[type] || type;
    }

    // معالج نموذج تحديث الحالة
    $(document).ready(function() {
        $('#statusForm').on('submit', function(e) {
            e.preventDefault();
            showLoading();
            
            const contractId = $('#contractId').val();
            const formData = new FormData(this);
            
            $.ajax({
                url: `/admin/financial/contracts/${contractId}/status`,
                method: 'PUT',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    hideLoading();
                    $('#statusModal').modal('hide');
                    location.reload();
                },
                error: function() {
                    hideLoading();
                    alert('حدث خطأ في تحديث حالة العقد');
                }
            });
        });

        // تحديث العد عند تغيير التحديد
        $(document).on('change', '.contract-checkbox', updateSelectedContracts);
        
        // تطبيق الفلاتر تلقائياً
        $('#filterForm select, #filterForm input').on('change', function() {
            if ($(this).is('input[type="text"]')) {
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(() => {
                    $('#filterForm').submit();
                }, 1000);
            } else {
                $('#filterForm').submit();
            }
        });
    });
</script>
@endsection
