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
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter" name="status_id">
                        <option value="">كل الحالات</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="userFilter" name="user_id">
                        <option value="">كل المستخدمين</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="productFilter" name="product_id">
                        <option value="">كل المنتجات</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="contractTypeFilter" name="contract_type">
                        <option value="">كل أنواع العقود</option>
                        <option value="sale" {{ request('contract_type') == 'sale' ? 'selected' : '' }}>بيع</option>
                        <option value="rent" {{ request('contract_type') == 'rent' ? 'selected' : '' }}>إيجار</option>
                        <option value="lease" {{ request('contract_type') == 'lease' ? 'selected' : '' }}>تأجير</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateFrom" name="date_from" value="{{ request('date_from') }}" placeholder="من تاريخ">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateTo" name="date_to" value="{{ request('date_to') }}" placeholder="إلى تاريخ">
                </div>
                <div class="col-md-4">
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
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">إجمالي العقود</h6>
                                    <h3 class="mb-0">{{ $contracts->total() }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">العقود النشطة</h6>
                                    <h3 class="mb-0">{{ $contracts->where('is_active', true)->count() }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">العقود المتحقق منها</h6>
                                    <h3 class="mb-0">{{ $contracts->where('is_verified', true)->count() }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">إجمالي القيمة</h6>
                                    <h3 class="mb-0">{{ number_format($contracts->sum('total_amount'), 2) }} ريال</h3>
                                </div>
                                <div class="fs-1">
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
                            <th>رقم العقد</th>
                            <th>المستخدم</th>
                            <th>المنتج</th>
                            <th>المنشأة</th>
                            <th>نوع العقد</th>
                            <th>تاريخ البداية</th>
                            <th>تاريخ النهاية</th>
                            <th>المبلغ الإجمالي</th>
                            <th>الحالة</th>
                            <th>التفعيل</th>
                            <th>التحقق</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contracts as $contract)
                        <tr>
                            <td>{{ $contract->contract_number }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $contract->user) }}">
                                    {{ $contract->user->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.products.show', $contract->product) }}">
                                    {{ $contract->product->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.facilities.show', $contract->facility) }}">
                                    {{ $contract->facility->name }}
                                </a>
                            </td>
                            <td>
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
                            <td>{{ $contract->start_date }}</td>
                            <td>{{ $contract->end_date }}</td>
                            <td>{{ number_format($contract->total_amount, 2) }} ريال</td>
                            <td>
                                <span class="badge bg-{{ $contract->status->color }}">
                                    {{ $contract->status->name }}
                                </span>
                            </td>
                            <td>
                                @if($contract->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                @if($contract->is_verified)
                                    <span class="badge bg-success">متحقق منه</span>
                                @else
                                    <span class="badge bg-warning">غير متحقق منه</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.contracts.show', $contract) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.contracts.destroy', $contract) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-confirm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.contracts.toggle-status', $contract) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $contract->is_active ? 'btn-danger' : 'btn-success' }}">
                                            <i class="fas {{ $contract->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.contracts.toggle-verification', $contract) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $contract->is_verified ? 'btn-warning' : 'btn-info' }}">
                                            <i class="fas {{ $contract->is_verified ? 'fa-times' : 'fa-shield-alt' }}"></i>
                                        </button>
                                    </form>
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
        ]
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
