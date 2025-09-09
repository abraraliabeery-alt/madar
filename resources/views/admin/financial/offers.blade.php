@extends('admin.financial.layout')

@section('title', 'إدارة العروض - النظام المالي للأدمن')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tags ms-2"></i>إدارة العروض</h2>
    <div class="btn-group">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="fas fa-download"></i> تصدير
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i> تحديث
        </button>
    </div>
</div>

<!-- فلاتر البحث -->
<div class="filter-section">
    <form method="GET" action="{{ route('admin.financial.offers') }}" id="filterForm">
        <div class="row">
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

            <div class="col-lg-2 col-md-6 mb-3">
                <label class="form-label">نوع العرض</label>
                <select name="type" class="form-select">
                    <option value="">جميع الأنواع</option>
                    <option value="sale" {{ request('type') === 'sale' ? 'selected' : '' }}>للبيع</option>
                    <option value="rent_monthly" {{ request('type') === 'rent_monthly' ? 'selected' : '' }}>إيجار شهري</option>
                    <option value="rent_yearly" {{ request('type') === 'rent_yearly' ? 'selected' : '' }}>إيجار سنوي</option>
                    <option value="rent_daily" {{ request('type') === 'rent_daily' ? 'selected' : '' }}>إيجار يومي</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-6 mb-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <label class="form-label">البحث</label>
                <input type="text" name="search" class="form-control" placeholder="بحث في العروض..." 
                       value="{{ request('search') }}">
            </div>

            <div class="col-lg-2 col-md-6 mb-3 d-flex align-items-end">
                <div class="btn-group w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> بحث
                    </button>
                    <a href="{{ route('admin.financial.offers') }}" class="btn btn-outline-secondary">
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
                    <h6 class="text-muted mb-1">إجمالي العروض</h6>
                    <div class="number">{{ $offers->total() }}</div>
                </div>
                <div class="icon text-primary">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-2">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">العروض النشطة</h6>
                    <div class="number">{{ $offers->where('is_active', true)->count() }}</div>
                </div>
                <div class="icon text-success">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-2">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">القيمة الإجمالية</h6>
                    <div class="number">{{ number_format($offers->sum('price'), 0) }}</div>
                    <small class="text-muted">ر.س</small>
                </div>
                <div class="icon text-warning">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-2">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">المؤسسات</h6>
                    <div class="number">{{ $offers->pluck('facility_id')->unique()->count() }}</div>
                </div>
                <div class="icon text-info">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- جدول العروض -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list ms-2"></i>قائمة العروض
            <span class="badge bg-secondary me-2">{{ $offers->total() }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        @if($offers->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>المعرف</th>
                        <th>المنتج</th>
                        <th>المؤسسة</th>
                        <th>النوع</th>
                        <th>السعر</th>
                        <th>العمولة</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($offers as $offer)
                    <tr>
                        <td>
                            <span class="fw-bold">#{{ $offer->id }}</span>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $offer->product->getTranslatedTitle() }}</strong>
                                <br><small class="text-muted">{{ $offer->product->address }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $offer->facility->name }}</span>
                        </td>
                        <td>
                            @switch($offer->offer_type)
                                @case('sale')
                                    <span class="badge bg-success">للبيع</span>
                                    @break
                                @case('rent_monthly')
                                    <span class="badge bg-primary">إيجار شهري</span>
                                    @break
                                @case('rent_yearly')
                                    <span class="badge bg-warning">إيجار سنوي</span>
                                    @break
                                @case('rent_daily')
                                    <span class="badge bg-secondary">إيجار يومي</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="fw-bold">{{ number_format($offer->price, 2) }} ر.س</div>
                            @if($offer->deposit_amount > 0)
                            <small class="text-muted">عربون: {{ number_format($offer->deposit_amount, 2) }} ر.س</small>
                            @endif
                        </td>
                        <td>
                            <div>{{ number_format($offer->commission_amount, 2) }} ر.س</div>
                            <small class="text-muted">{{ number_format($offer->commission_rate * 100, 2) }}%</small>
                        </td>
                        <td>
                            @if($offer->is_active)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-secondary">غير نشط</span>
                            @endif
                            
                            @if($offer->is_featured)
                                <br><span class="badge bg-warning text-dark mt-1">مميز</span>
                            @endif
                        </td>
                        <td>
                            <span class="timestamp" data-timestamp="{{ $offer->created_at }}">
                                {{ $offer->created_at->format('Y/m/d') }}
                            </span>
                            <br><small class="text-muted">{{ $offer->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" 
                                        onclick="viewOffer({{ $offer->id }})" 
                                        data-bs-toggle="tooltip" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-outline-info" 
                                        onclick="viewContracts({{ $offer->id }})" 
                                        data-bs-toggle="tooltip" title="العقود المرتبطة">
                                    <i class="fas fa-file-contract"></i>
                                </button>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.products.show', $offer->product_id) }}">
                                                <i class="fas fa-home ms-2"></i>عرض المنتج
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.facilities.show', $offer->facility_id) }}">
                                                <i class="fas fa-building ms-2"></i>عرض المؤسسة
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item" onclick="toggleOfferStatus({{ $offer->id }})">
                                                <i class="fas fa-{{ $offer->is_active ? 'pause' : 'play' }} ms-2"></i>
                                                {{ $offer->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}
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
        @if($offers->hasPages())
        <div class="card-footer">
            {{ $offers->appends(request()->query())->links() }}
        </div>
        @endif
        
        @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h4>لا توجد عروض</h4>
            <p class="text-muted">لم يتم العثور على أي عروض تطابق معايير البحث المحددة.</p>
            <a href="{{ route('admin.financial.offers') }}" class="btn btn-primary">
                <i class="fas fa-refresh"></i> عرض جميع العروض
            </a>
        </div>
        @endif
    </div>
</div>

<!-- مودال تفاصيل العرض -->
<div class="modal fade" id="offerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل العرض</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="offerDetails">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال العقود المرتبطة -->
<div class="modal fade" id="contractsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">العقود المرتبطة بالعرض</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contractsContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال التصدير -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تصدير العروض</h5>
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
                                النتائج الحالية ({{ $offers->total() }} عرض)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="scope" value="all" id="all">
                            <label class="form-check-label" for="all">
                                جميع العروض
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-success" onclick="exportOffers()">
                    <i class="fas fa-download"></i> تصدير
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // عرض تفاصيل العرض
    function viewOffer(offerId) {
        $('#offerModal').modal('show');
        
        $.get(`/api/v1/admin/offers/${offerId}`)
            .done(function(response) {
                if (response.success) {
                    displayOfferDetails(response.data);
                } else {
                    $('#offerDetails').html('<div class="alert alert-danger">حدث خطأ في جلب البيانات</div>');
                }
            })
            .fail(function() {
                $('#offerDetails').html('<div class="alert alert-danger">حدث خطأ في الاتصال</div>');
            });
    }

    // عرض تفاصيل العرض في المودال
    function displayOfferDetails(offer) {
        const html = `
            <div class="row">
                <div class="col-md-6">
                    <h6>معلومات أساسية</h6>
                    <table class="table table-sm">
                        <tr><td><strong>المعرف:</strong></td><td>#${offer.id}</td></tr>
                        <tr><td><strong>النوع:</strong></td><td>${getOfferTypeText(offer.offer_type)}</td></tr>
                        <tr><td><strong>السعر:</strong></td><td>${formatCurrency(offer.price)}</td></tr>
                        <tr><td><strong>العربون:</strong></td><td>${formatCurrency(offer.deposit_amount || 0)}</td></tr>
                        <tr><td><strong>العمولة:</strong></td><td>${formatCurrency(offer.commission_amount)} (${(offer.commission_rate * 100).toFixed(2)}%)</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>معلومات إضافية</h6>
                    <table class="table table-sm">
                        <tr><td><strong>المنتج:</strong></td><td>${offer.product.title || 'غير متوفر'}</td></tr>
                        <tr><td><strong>المؤسسة:</strong></td><td>${offer.facility.name}</td></tr>
                        <tr><td><strong>نشط:</strong></td><td>${offer.is_active ? 'نعم' : 'لا'}</td></tr>
                        <tr><td><strong>مميز:</strong></td><td>${offer.is_featured ? 'نعم' : 'لا'}</td></tr>
                        <tr><td><strong>تاريخ الإنشاء:</strong></td><td>${formatDate(offer.created_at)}</td></tr>
                    </table>
                </div>
            </div>
            
            ${offer.terms_conditions ? `
                <div class="mt-3">
                    <h6>الشروط والأحكام</h6>
                    <div class="alert alert-info">${offer.terms_conditions}</div>
                </div>
            ` : ''}
        `;
        
        $('#offerDetails').html(html);
    }

    // عرض العقود المرتبطة
    function viewContracts(offerId) {
        $('#contractsModal').modal('show');
        
        $.get(`/api/v1/admin/offers/${offerId}/contracts`)
            .done(function(response) {
                if (response.success && response.data.length > 0) {
                    displayContracts(response.data);
                } else {
                    $('#contractsContent').html('<div class="text-center text-muted"><i class="fas fa-inbox fa-2x mb-2"></i><p>لا توجد عقود مرتبطة بهذا العرض</p></div>');
                }
            })
            .fail(function() {
                $('#contractsContent').html('<div class="alert alert-danger">حدث خطأ في جلب العقود</div>');
            });
    }

    // عرض العقود في المودال
    function displayContracts(contracts) {
        let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>رقم العقد</th><th>العميل</th><th>المبلغ</th><th>الحالة</th><th>التاريخ</th></tr></thead><tbody>';
        
        contracts.forEach(contract => {
            html += `
                <tr>
                    <td><strong>${contract.contract_number}</strong></td>
                    <td>${contract.user.name}</td>
                    <td>${formatCurrency(contract.total_amount)}</td>
                    <td><span class="badge bg-${getStatusColor(contract.status)}">${getStatusText(contract.status)}</span></td>
                    <td>${formatDate(contract.created_at)}</td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        $('#contractsContent').html(html);
    }

    // تغيير حالة العرض
    function toggleOfferStatus(offerId) {
        if (confirm('هل أنت متأكد من تغيير حالة العرض؟')) {
            showLoading();
            
            $.post(`/api/v1/admin/offers/${offerId}/toggle-status`)
                .done(function(response) {
                    hideLoading();
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('حدث خطأ في تغيير حالة العرض');
                    }
                })
                .fail(function() {
                    hideLoading();
                    alert('حدث خطأ في الاتصال');
                });
        }
    }

    // تصدير العروض
    function exportOffers() {
        const formData = new FormData(document.getElementById('exportForm'));
        const params = new URLSearchParams(formData);
        
        // إضافة معايير البحث الحالية
        const currentParams = new URLSearchParams(window.location.search);
        for (let [key, value] of currentParams) {
            params.append(key, value);
        }
        
        window.open(`/admin/financial/offers/export?${params.toString()}`, '_blank');
        $('#exportModal').modal('hide');
    }

    // دوال مساعدة
    function getOfferTypeText(type) {
        const types = {
            'sale': 'للبيع',
            'rent_monthly': 'إيجار شهري',
            'rent_yearly': 'إيجار سنوي',
            'rent_daily': 'إيجار يومي'
        };
        return types[type] || type;
    }

    function getStatusColor(status) {
        const colors = {
            'draft': 'warning',
            'active': 'success',
            'completed': 'info',
            'cancelled': 'danger'
        };
        return colors[status] || 'secondary';
    }

    function getStatusText(status) {
        const statuses = {
            'draft': 'مسودة',
            'active': 'نشط',
            'completed': 'مكتمل',
            'cancelled': 'ملغي'
        };
        return statuses[status] || status;
    }

    // تطبيق الفلاتر تلقائياً
    $(document).ready(function() {
        $('#filterForm select, #filterForm input').on('change', function() {
            // Auto-submit بعد تأخير قصير للإدخال النصي
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
