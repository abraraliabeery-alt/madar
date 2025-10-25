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
                <div class="col-12 col-md-6 col-lg-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">كل الحالات</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <select class="form-select" id="userFilter">
                        <option value="">كل المستخدمين</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <select class="form-select" id="productFilter">
                        <option value="">كل المنتجات</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <button class="btn btn-secondary w-100" id="resetFilters">
                        <i class="fas fa-redo me-2"></i>إعادة تعيين
                    </button>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <input type="date" class="form-control" id="dateFrom" placeholder="من تاريخ">
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <input type="date" class="form-control" id="dateTo" placeholder="إلى تاريخ">
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <select class="form-select" id="paymentFilter">
                        <option value="">حالة الدفع</option>
                        <option value="1">مدفوع</option>
                        <option value="0">غير مدفوع</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <select class="form-select" id="confirmationFilter">
                        <option value="">حالة التأكيد</option>
                        <option value="1">مؤكد</option>
                        <option value="0">غير مؤكد</option>
                    </select>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-3 mb-4 statistics-cards">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">إجمالي الحجوزات</h6>
                                    <h3 class="mb-0">{{ $bookings->total() }}</h3>
                                </div>
                                <div class="fs-1 d-none d-sm-block">
                                    <i class="fas fa-calendar"></i>
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
                                    <h6 class="mb-0">الحجوزات المؤكدة</h6>
                                    <h3 class="mb-0">{{ $bookings->where('is_confirmed', true)->count() }}</h3>
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
                                    <h6 class="mb-0">الحجوزات المدفوعة</h6>
                                    <h3 class="mb-0">{{ $bookings->where('is_paid', true)->count() }}</h3>
                                </div>
                                <div class="fs-1 d-none d-sm-block">
                                    <i class="fas fa-money-bill"></i>
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
                                    <h6 class="mb-0">قيد الانتظار</h6>
                                    <h3 class="mb-0">{{ $bookings->where('is_confirmed', false)->count() }}</h3>
                                </div>
                                <div class="fs-1 d-none d-sm-block">
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
                            <th class="d-none d-md-table-cell">رقم الحجز</th>
                            <th>المستخدم</th>
                            <th class="d-none d-lg-table-cell">المنتج</th>
                            <th class="d-none d-lg-table-cell">المنشأة</th>
                            <th class="d-none d-md-table-cell">التاريخ</th>
                            <th class="d-none d-md-table-cell">الوقت</th>
                            <th class="d-none d-lg-table-cell">المدة</th>
                            <th class="d-none d-md-table-cell">المبلغ</th>
                            <th class="d-none d-md-table-cell">الحالة</th>
                            <th class="d-none d-md-table-cell">الدفع</th>
                            <th class="d-none d-md-table-cell">التأكيد</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td class="d-none d-md-table-cell">{{ $booking->booking_number }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2 d-none d-sm-inline-block">
                                        @if($booking->user->avatar)
                                            <img src="{{ asset($booking->user->avatar) }}" alt="avatar" class="rounded-circle" width="32">
                                        @else
                                            <div class="avatar-placeholder rounded-circle">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.users.show', $booking->user) }}" class="text-decoration-none">
                                            {{ $booking->user->name }}
                                        </a>
                                        <div class="small text-muted d-md-none">
                                            {{ $booking->product->name ?? 'منتج' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <a href="{{ route('admin.products.show', $booking->product) }}">
                                    {{ $booking->product->name }}
                                </a>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <a href="{{ route('admin.facilities.show', $booking->facility) }}">
                                    {{ $booking->facility->name }}
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $booking->booking_date }}</td>
                            <td class="d-none d-md-table-cell">{{ $booking->booking_time }}</td>
                            <td class="d-none d-lg-table-cell">{{ $booking->duration }} ساعة</td>
                                                            <td class="d-none d-md-table-cell">{{ number_format($booking->total_amount, 2) }} {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}</td>
                            <td class="d-none d-md-table-cell">
                                @if($booking->status)
                                    <span class="badge bg-{{ $booking->status->color }}">
                                        {{ $booking->status->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">لا توجد حالة</span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($booking->is_paid)
                                    <span class="badge bg-success">مدفوع</span>
                                @else
                                    <span class="badge bg-danger">غير مدفوع</span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($booking->is_confirmed)
                                    <span class="badge bg-success">مؤكد</span>
                                @else
                                    <span class="badge bg-warning">قيد الانتظار</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- Mobile View: Compact Horizontal Layout -->
                                    <div class="d-flex d-md-none gap-1 flex-wrap">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" 
                                           class="btn btn-sm btn-outline-info action-btn-mobile" 
                                           data-bs-toggle="tooltip" 
                                           title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.bookings.edit', $booking) }}" 
                                           class="btn btn-sm btn-outline-warning action-btn-mobile" 
                                           data-bs-toggle="tooltip" 
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger action-btn-mobile delete-confirm" 
                                                data-bs-toggle="tooltip" 
                                                title="حذف"
                                                data-booking-id="{{ $booking->id }}"
                                                data-booking-number="{{ $booking->booking_number }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        <!-- Payment Status Toggle -->
                                        <form action="{{ route('admin.bookings.update-payment-status', $booking) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $booking->is_paid ? 'btn-outline-danger' : 'btn-outline-success' }} action-btn-mobile" 
                                                    data-bs-toggle="tooltip" 
                                                    title="{{ $booking->is_paid ? 'إلغاء الدفع' : 'دفع' }}">
                                                <i class="fas {{ $booking->is_paid ? 'fa-times' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <!-- Confirmation Toggle -->
                                        @if(!$booking->is_confirmed)
                                            <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-success action-btn-mobile" 
                                                        data-bs-toggle="tooltip" 
                                                        title="تأكيد">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.bookings.unconfirm', $booking) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-warning action-btn-mobile" 
                                                        data-bs-toggle="tooltip" 
                                                        title="إلغاء">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    
                                    <!-- Desktop View: Vertical Layout -->
                                    <div class="d-none d-md-flex flex-column gap-1">
                                        <!-- Primary Actions Row -->
                                        <div class="d-flex gap-1 mb-1">
                                            <a href="{{ route('admin.bookings.show', $booking) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               data-bs-toggle="tooltip" 
                                               title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.bookings.edit', $booking) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               data-bs-toggle="tooltip" 
                                               title="تعديل الحجز">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger delete-confirm" 
                                                    data-bs-toggle="tooltip" 
                                                    title="حذف الحجز"
                                                    data-booking-id="{{ $booking->id }}"
                                                    data-booking-number="{{ $booking->booking_number }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Status Toggle Row -->
                                        <div class="d-flex gap-1 mb-1">
                                            <form action="{{ route('admin.bookings.update-payment-status', $booking) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm {{ $booking->is_paid ? 'btn-outline-danger' : 'btn-outline-success' }}" 
                                                        data-bs-toggle="tooltip" 
                                                        title="{{ $booking->is_paid ? 'إلغاء الدفع' : 'تأكيد الدفع' }}">
                                                    <i class="fas {{ $booking->is_paid ? 'fa-times' : 'fa-check' }}"></i>
                                                </button>
                                            </form>
                                            
                                            @if(!$booking->is_confirmed)
                                                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-success" 
                                                            data-bs-toggle="tooltip" 
                                                            title="تأكيد الحجز">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.bookings.unconfirm', $booking) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-warning" 
                                                            data-bs-toggle="tooltip" 
                                                            title="إلغاء التأكيد">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
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
        ],
        responsive: true,
        pageLength: window.innerWidth < 768 ? 10 : 15,
        scrollX: true,
        autoWidth: false
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
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

    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let bookingId = $(this).data('booking-id');
        let bookingNumber = $(this).data('booking-number');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: `سيتم حذف الحجز رقم "${bookingNumber}" نهائياً. لا يمكن التراجع عن هذا الإجراء!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف الحجز',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit delete form
                let form = $('<form>', {
                    'method': 'POST',
                    'action': `/admin/bookings/${bookingId}`
                });
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': $('meta[name="csrf-token"]').attr('content')
                }));
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));
                
                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .action-buttons .d-flex {
        flex-direction: column !important;
    }
    
    .action-buttons .btn {
        min-width: 28px;
        height: 28px;
        font-size: 0.8rem;
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

@media (max-width: 576px) {
    .row.g-3 > [class*="col-"] {
        margin-bottom: 1rem;
    }
    
    .statistics-cards .card {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
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
}
</style>
@endpush
