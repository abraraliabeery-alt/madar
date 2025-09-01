@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة العقود</h5>
            <div>
                <a href="{{ route('admin.contracts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>إضافة عقد جديد
                </a>
                <a href="{{ route('admin.contracts.statistics') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar me-2"></i>الإحصائيات
                </a>
                <a href="{{ route('admin.contracts.export') }}" class="btn btn-success">
                    <i class="fas fa-file-export me-2"></i>تصدير
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <select class="form-select" id="statusFilter" name="status_id">
                        <option value="">كل الحالات</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <select class="form-select" id="userFilter" name="user_id">
                        <option value="">كل المستخدمين</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <select class="form-select" id="productFilter" name="product_id">
                        <option value="">كل المنتجات</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <select class="form-select" id="contractTypeFilter" name="contract_type">
                        <option value="">كل أنواع العقود</option>
                        <option value="sale" {{ request('contract_type') == 'sale' ? 'selected' : '' }}>بيع</option>
                        <option value="rent" {{ request('contract_type') == 'rent' ? 'selected' : '' }}>إيجار</option>
                        <option value="lease" {{ request('contract_type') == 'lease' ? 'selected' : '' }}>تأجير</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <input type="date" class="form-control" id="dateFrom" name="date_from" value="{{ request('date_from') }}" placeholder="من تاريخ">
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <input type="date" class="form-control" id="dateTo" name="date_to" value="{{ request('date_to') }}" placeholder="إلى تاريخ">
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" name="search" value="{{ request('search') }}" placeholder="بحث...">
                        <button class="btn btn-primary" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                        <button class="btn btn-secondary" id="resetFilters">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-3 mb-4 statistics-cards">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">إجمالي العقود</h6>
                                    <h3 class="mb-0">{{ $contracts->total() }}</h3>
                                </div>
                                <div class="fs-1 d-none d-sm-block">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">العقود النشطة</h6>
                                    <h3 class="mb-0">{{ $contracts->where('is_active', true)->count() }}</h3>
                                </div>
                                <div class="fs-1 d-none d-sm-block">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">العقود المتحقق منها</h6>
                                    <h3 class="mb-0">{{ $contracts->where('is_verified', true)->count() }}</h3>
                                </div>
                                <div class="fs-1 d-none d-sm-block">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">إجمالي القيمة</h6>
                                    <h3 class="mb-0">{{ number_format($contracts->sum('total_amount'), 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</h3>
                                </div>
                                <div class="fs-1 d-none d-sm-block">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contracts Table -->
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th class="d-none d-md-table-cell">رقم العقد</th>
                            <th>المستخدم</th>
                            <th class="d-none d-lg-table-cell">المنتج</th>
                            <th class="d-none d-lg-table-cell">المنشأة</th>
                            <th class="d-none d-md-table-cell">نوع العقد</th>
                            <th class="d-none d-md-table-cell">تاريخ البداية</th>
                            <th class="d-none d-lg-table-cell">تاريخ النهاية</th>
                            <th class="d-none d-md-table-cell">المبلغ الإجمالي</th>
                            <th class="d-none d-md-table-cell">الحالة</th>
                            <th class="d-none d-lg-table-cell">التفعيل</th>
                            <th class="d-none d-lg-table-cell">التحقق</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contracts as $contract)
                        <tr>
                            <td class="d-none d-md-table-cell">{{ $contract->contract_number }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2 d-none d-sm-inline-block">
                                        @if($contract->user->avatar)
                                            <img src="{{ asset($contract->user->avatar) }}" alt="avatar" class="rounded-circle" width="32">
                                        @else
                                            <div class="avatar-placeholder rounded-circle">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.users.show', $contract->user) }}" class="text-decoration-none">
                                            {{ $contract->user->name }}
                                        </a>
                                        <div class="small text-muted d-md-none">
                                            {{ $contract->product->name ?? 'منتج' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <a href="{{ route('admin.products.show', $contract->product) }}">
                                    {{ $contract->product->name }}
                                </a>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <a href="{{ route('admin.facilities.show', $contract->facility) }}">
                                    {{ $contract->facility->name }}
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell">
                                @switch($contract->contract_type)
                                    @case('sale')
                                        <span class="badge bg-success">بيع</span>
                                        @break
                                    @case('rent')
                                        <span class="badge bg-info">إيجار</span>
                                        @break
                                    @case('lease')
                                        <span class="badge bg-warning">تأجير</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="d-none d-md-table-cell">{{ $contract->start_date }}</td>
                            <td class="d-none d-lg-table-cell">{{ $contract->end_date }}</td>
                                                            <td class="d-none d-md-table-cell">{{ number_format($contract->total_amount, 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</td>
                            <td class="d-none d-md-table-cell">
                                @if($contract->status)
                                    <span class="badge bg-{{ $contract->status->color }}">
                                        {{ $contract->status->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">لا توجد حالة</span>
                                @endif
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @if($contract->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @if($contract->is_verified)
                                    <span class="badge bg-success">متحقق منه</span>
                                @else
                                    <span class="badge bg-warning">غير متحقق منه</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- Mobile View: Compact Horizontal Layout -->
                                    <div class="d-flex d-md-none gap-1 flex-wrap">
                                        <a href="{{ route('admin.contracts.show', $contract) }}" class="btn btn-sm btn-outline-info action-btn-mobile">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-sm btn-outline-warning action-btn-mobile">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.contracts.destroy', $contract) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger action-btn-mobile delete-confirm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.contracts.toggle-status', $contract) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $contract->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} action-btn-mobile">
                                                <i class="fas {{ $contract->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.contracts.toggle-verification', $contract) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $contract->is_verified ? 'btn-outline-warning' : 'btn-outline-info' }} action-btn-mobile">
                                                <i class="fas {{ $contract->is_verified ? 'fa-times' : 'fa-shield-alt' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <!-- Desktop View: Vertical Layout -->
                                    <div class="d-none d-md-flex flex-column gap-1">
                                        <!-- Primary Actions Row -->
                                        <div class="d-flex gap-1 mb-1">
                                            <a href="{{ route('admin.contracts.show', $contract) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.contracts.destroy', $contract) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger delete-confirm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!-- Status Toggle Row -->
                                        <div class="d-flex gap-1 mb-1">
                                            <form action="{{ route('admin.contracts.toggle-status', $contract) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $contract->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                    <i class="fas {{ $contract->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.contracts.toggle-verification', $contract) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $contract->is_verified ? 'btn-outline-warning' : 'btn-outline-info' }}">
                                                    <i class="fas {{ $contract->is_verified ? 'fa-times' : 'fa-shield-alt' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $contracts->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.datatable th, .datatable td {
    vertical-align: middle;
}

/* Action Buttons Styling */
.action-buttons .btn {
    transition: all 0.2s ease-in-out;
    border-width: 1.5px;
    font-size: 0.875rem;
    min-width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.action-buttons .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.action-buttons .btn:active {
    transform: translateY(0);
}

/* Primary Actions Row */
.action-buttons .btn-outline-info:hover {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: white;
}

.action-buttons .btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: black;
}

.action-buttons .btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

/* Status Toggle Row */
.action-buttons .btn-outline-success:hover {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.action-buttons .btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

/* Mobile Action Buttons */
.action-btn-mobile {
    min-width: 36px !important;
    height: 36px !important;
    padding: 0.375rem !important;
    font-size: 0.875rem !important;
    border-radius: 8px !important;
    margin: 1px !important;
}

.action-btn-mobile:hover {
    transform: scale(1.05) !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
}

/* Mobile action buttons container */
@media (max-width: 767px) {
    .action-buttons {
        min-width: auto;
        padding: 0.25rem;
    }
    
    .action-buttons .d-flex {
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.25rem !important;
    }
    
    /* Ensure buttons don't wrap awkwardly */
    .action-btn-mobile {
        flex-shrink: 0;
    }
}

/* Table cell padding for actions */
.datatable td:last-child {
    padding: 0.5rem;
    min-width: 120px;
}

@media (max-width: 767px) {
    .datatable td:last-child {
        min-width: auto;
        padding: 0.25rem;
        text-align: center;
    }
}

/* Avatar placeholder styling */
.avatar-placeholder {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.avatar-placeholder i {
    font-size: 14px;
}

/* Mobile-friendly table */
@media (max-width: 768px) {
    .datatable thead th,
    .datatable tbody td {
        padding: 0.5rem 0.25rem;
    }
    
    .datatable .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* Filter improvements for mobile */
@media (max-width: 576px) {
    .form-select,
    .form-control {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    .row.g-3 > [class*="col-"] {
        margin-bottom: 1rem;
    }
    
    .statistics-cards .card {
        margin-bottom: 1rem;
    }
    
    .card-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
    
    .card-header .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('.datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json',
        },
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [11] }
        ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        responsive: true,
        pageLength: window.innerWidth < 768 ? 10 : 15,
        scrollX: true,
        autoWidth: false
    });

    // Initialize Select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Handle filters
    function applyFilters() {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        // Update parameters
        params.set('status_id', $('#statusFilter').val() || '');
        params.set('user_id', $('#userFilter').val() || '');
        params.set('product_id', $('#productFilter').val() || '');
        params.set('contract_type', $('#contractTypeFilter').val() || '');
        params.set('date_from', $('#dateFrom').val() || '');
        params.set('date_to', $('#dateTo').val() || '');
        params.set('search', $('#searchInput').val() || '');

        // Redirect with new parameters
        window.location.href = `${url.pathname}?${params.toString()}`;
    }

    // Bind events
    $('#statusFilter, #userFilter, #productFilter, #contractTypeFilter').change(applyFilters);
    $('#dateFrom, #dateTo').change(applyFilters);
    $('#searchBtn').click(applyFilters);

    // Reset filters
    $('#resetFilters').click(function() {
        window.location.href = window.location.pathname;
    });

    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لا يمكن التراجع عن هذا الإجراء!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف العقد',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
