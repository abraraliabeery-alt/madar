@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة الحجوزات</h5>
            <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة حجز جديد
            </a>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">كل الحالات</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="userFilter">
                        <option value="">كل المستخدمين</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="productFilter">
                        <option value="">كل المنتجات</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-secondary w-100" id="resetFilters">
                        <i class="fas fa-redo me-2"></i>إعادة تعيين
                    </button>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateFrom" placeholder="من تاريخ">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateTo" placeholder="إلى تاريخ">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="paymentFilter">
                        <option value="">حالة الدفع</option>
                        <option value="1">مدفوع</option>
                        <option value="0">غير مدفوع</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="confirmationFilter">
                        <option value="">حالة التأكيد</option>
                        <option value="1">مؤكد</option>
                        <option value="0">غير مؤكد</option>
                    </select>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">إجمالي الحجوزات</h6>
                                    <h3 class="mb-0">{{ $bookings->total() }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-calendar"></i>
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
                                    <h6 class="mb-0">الحجوزات المؤكدة</h6>
                                    <h3 class="mb-0">{{ $bookings->where('is_confirmed', true)->count() }}</h3>
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
                                    <h6 class="mb-0">الحجوزات المدفوعة</h6>
                                    <h3 class="mb-0">{{ $bookings->where('is_paid', true)->count() }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-money-bill"></i>
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
                                    <h6 class="mb-0">قيد الانتظار</h6>
                                    <h3 class="mb-0">{{ $bookings->where('is_confirmed', false)->count() }}</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>رقم الحجز</th>
                            <th>المستخدم</th>
                            <th>المنتج</th>
                            <th>المنشأة</th>
                            <th>التاريخ</th>
                            <th>الوقت</th>
                            <th>المدة</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                            <th>الدفع</th>
                            <th>التأكيد</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_number }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $booking->user) }}">
                                    {{ $booking->user->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.products.show', $booking->product) }}">
                                    {{ $booking->product->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.facilities.show', $booking->facility) }}">
                                    {{ $booking->facility->name }}
                                </a>
                            </td>
                            <td>{{ $booking->booking_date }}</td>
                            <td>{{ $booking->booking_time }}</td>
                            <td>{{ $booking->duration }} ساعة</td>
                            <td>{{ number_format($booking->total_amount, 2) }} ريال</td>
                            <td>
                                <span class="badge bg-{{ $booking->status->color }}">
                                    {{ $booking->status->name }}
                                </span>
                            </td>
                            <td>
                                @if($booking->is_paid)
                                    <span class="badge bg-success">مدفوع</span>
                                @else
                                    <span class="badge bg-danger">غير مدفوع</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->is_confirmed)
                                    <span class="badge bg-success">مؤكد</span>
                                @else
                                    <span class="badge bg-warning">قيد الانتظار</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-confirm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.bookings.update-payment-status', $booking) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $booking->is_paid ? 'btn-danger' : 'btn-success' }}">
                                            <i class="fas {{ $booking->is_paid ? 'fa-times' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    @if(!$booking->is_confirmed)
                                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.bookings.unconfirm', $booking) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let table = $('.datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json',
        },
        order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Apply filters
    $('#statusFilter, #userFilter, #productFilter, #paymentFilter, #confirmationFilter').change(function() {
        let status = $('#statusFilter').val();
        let user = $('#userFilter').val();
        let product = $('#productFilter').val();
        let payment = $('#paymentFilter').val();
        let confirmation = $('#confirmationFilter').val();

        table.columns(8).search(status);
        table.columns(1).search(user);
        table.columns(2).search(product);
        table.columns(9).search(payment);
        table.columns(10).search(confirmation).draw();
    });

    // Date range filter
    $('#dateFrom, #dateTo').change(function() {
        let dateFrom = $('#dateFrom').val();
        let dateTo = $('#dateTo').val();

        if (dateFrom && dateTo) {
            table.columns(4).search(dateFrom + ' to ' + dateTo).draw();
        }
    });

    // Reset filters
    $('#resetFilters').click(function() {
        $('#statusFilter, #userFilter, #productFilter, #paymentFilter, #confirmationFilter').val('');
        $('#dateFrom, #dateTo').val('');
        table.columns().search('').draw();
    });

    // Initialize select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});
</script>
@endpush
