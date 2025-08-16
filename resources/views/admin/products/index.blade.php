@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة المنتجات</h5>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة منتج جديد
            </a>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter" name="status">
                        <option value="">كل الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>مميز</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>متحقق منه</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="categoryFilter" name="category_id">
                        <option value="">كل الفئات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="facilityFilter" name="facility_id">
                        <option value="">كل المنشآت</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" name="q" value="{{ request('q') }}" placeholder="بحث...">
                        <button class="btn btn-primary" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                        <button class="btn btn-secondary" id="resetFilters">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>المنشأة</th>
                            <th>الفئة</th>
                            <th>السعر</th>
                            <th>العنوان</th>
                            <th>الحالة</th>
                            <th>التفعيل</th>
                            <th>التحقق</th>
                            <th>مميز</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product->main_image)
                                    <img src="{{ asset($product->main_image) }}" alt="product" width="50" class="rounded">
                                @else
                                    <div class="avatar-placeholder rounded" style="width: 50px; height: 50px;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>
                                <a href="{{ route('admin.facilities.show', $product->facility) }}">
                                    {{ $product->facility->name }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $product->category->name }}</span>
                            </td>
                            <td>{{ number_format($product->price, 2) }} ريال</td>
                            <td>{{ Str::limit($product->address, 30) }}</td>
                            <td>
                                @if($product->status)
                                    <span class="badge bg-{{ $product->status->color }}">
                                        {{ $product->status->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">لا توجد حالة</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_verified)
                                    <span class="badge bg-success">متحقق منه</span>
                                @else
                                    <span class="badge bg-warning">غير متحقق منه</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_featured)
                                    <span class="badge bg-warning">مميز</span>
                                @else
                                    <span class="badge bg-secondary">غير مميز</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- Mobile View: Compact Horizontal Layout -->
                                    <div class="d-flex d-md-none gap-1 flex-wrap">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                           class="btn btn-sm btn-outline-info action-btn-mobile"
                                           data-bs-toggle="tooltip"
                                           title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="btn btn-sm btn-outline-warning action-btn-mobile"
                                           data-bs-toggle="tooltip"
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger action-btn-mobile delete-confirm"
                                                data-bs-toggle="tooltip"
                                                title="حذف"
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        <!-- Status Toggle -->
                                        <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $product->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} action-btn-mobile"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $product->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                <i class="fas {{ $product->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.products.toggle-verification', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $product->is_verified ? 'btn-outline-warning' : 'btn-outline-info' }} action-btn-mobile"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $product->is_verified ? 'إلغاء التحقق' : 'تحقق' }}">
                                                <i class="fas {{ $product->is_verified ? 'fa-times' : 'fa-shield-alt' }}"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $product->is_featured ? 'btn-outline-secondary' : 'btn-outline-warning' }} action-btn-mobile"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $product->is_featured ? 'إلغاء التمييز' : 'تمييز' }}">
                                                <i class="fas fa-star {{ $product->is_featured ? 'text-warning' : '' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <!-- Desktop View: Vertical Layout -->
                                    <div class="d-none d-md-flex flex-column gap-1">
                                        <!-- Primary Actions Row -->
                                        <div class="d-flex gap-1 mb-1">
                                            <a href="{{ route('admin.products.show', $product) }}"
                                               class="btn btn-sm btn-outline-info"
                                               data-bs-toggle="tooltip"
                                               title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               data-bs-toggle="tooltip"
                                               title="تعديل المنتج">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-confirm"
                                                    data-bs-toggle="tooltip"
                                                    title="حذف المنتج"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Status Toggle Row -->
                                        <div class="d-flex gap-1 mb-1">
                                            <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm {{ $product->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $product->is_active ? 'إلغاء التفعيل' : 'تفعيل المنتج' }}">
                                                    <i class="fas {{ $product->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.products.toggle-verification', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm {{ $product->is_verified ? 'btn-outline-warning' : 'btn-outline-info' }}"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $product->is_verified ? 'إلغاء التحقق' : 'التحقق من المنتج' }}">
                                                    <i class="fas {{ $product->is_verified ? 'fa-times' : 'fa-shield-alt' }}"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm {{ $product->is_featured ? 'btn-outline-secondary' : 'btn-outline-warning' }}"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $product->is_featured ? 'إلغاء التمييز' : 'تمييز المنتج' }}">
                                                    <i class="fas fa-star {{ $product->is_featured ? 'text-warning' : '' }}"></i>
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
                {{ $products->withQueryString()->links() }}
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

/* Featured Star Button */
.action-buttons .btn .fa-star.text-warning {
    color: #ffc107 !important;
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
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: [0, 10] }
        ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Initialize select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle filters
    function applyFilters() {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        // Update parameters
        params.set('status', $('#statusFilter').val() || '');
        params.set('category_id', $('#categoryFilter').val() || '');
        params.set('facility_id', $('#facilityFilter').val() || '');
        params.set('q', $('#searchInput').val() || '');

        // Redirect with new parameters
        window.location.href = `${url.pathname}?${params.toString()}`;
    }

    // Bind events
    $('#statusFilter, #categoryFilter, #facilityFilter').change(applyFilters);
    $('#searchBtn').click(applyFilters);

    // Reset filters
    $('#resetFilters').click(function() {
        window.location.href = window.location.pathname;
    });

    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let productId = $(this).data('product-id');
        let productName = $(this).data('product-name');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: `سيتم حذف المنتج "${productName}" نهائياً. لا يمكن التراجع عن هذا الإجراء!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف المنتج',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit delete form
                let form = $('<form>', {
                    'method': 'POST',
                    'action': `/admin/products/${productId}`
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
