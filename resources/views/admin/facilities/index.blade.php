@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة المنشآت</h5>
            <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة منشأة جديدة
            </a>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <select class="form-select" id="categoryFilter" name="category_id">
                        <option value="">كل الفئات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
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

            <!-- Facilities Table -->
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>الشعار</th>
                            <th>الاسم</th>
                            <th>المالك</th>
                            <th>الفئة</th>
                            <th>العنوان</th>
                            <th>الحالة</th>
                            <th>التفعيل</th>
                            <th>التحقق</th>
                            <th>مميزة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facilities as $facility)
                        <tr>
                            <td>
                                @if($facility->logo)
                                    <img src="{{ asset($facility->logo) }}" alt="logo" width="40" class="rounded">
                                @else
                                    <div class="avatar-placeholder rounded" style="width: 40px; height: 40px;">
                                        <i class="fas fa-building"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-0">{{ $facility->name }}</h6>
                                    <small class="text-muted">{{ $facility->email }}</small>
                                </div>
                            </td>
                            <td>
                                @if($facility->owner)
                                    <a href="{{ route('admin.users.show', $facility->owner) }}">
                                        {{ $facility->owner->name }}
                                    </a>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                @if($facility->facilityCategory)
                                    <span class="badge bg-info">{{ $facility->facilityCategory->name }}</span>
                                @else
                                    <span class="badge bg-secondary">غير مصنفة</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($facility->address, 30) }}</td>
                            <td>
                                @if($facility->status)
                                    <span class="badge bg-{{ $facility->status->color }}">
                                        {{ $facility->status->getTranslatedName() }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">لا توجد حالة</span>
                                @endif
                            </td>
                            <td>
                                @if($facility->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                @if($facility->is_verified)
                                    <span class="badge bg-success">متحقق منه</span>
                                @else
                                    <span class="badge bg-warning">غير متحقق منه</span>
                                @endif
                            </td>
                            <td>
                                @if($facility->is_featured)
                                    <span class="badge bg-warning">مميزة</span>
                                @else
                                    <span class="badge bg-secondary">غير مميزة</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- Mobile View: Compact Horizontal Layout -->
                                    <div class="d-flex d-md-none gap-1 flex-wrap">
                                        <a href="{{ route('admin.facilities.show', $facility) }}"
                                           class="btn btn-sm btn-outline-info action-btn-mobile"
                                           data-bs-toggle="tooltip"
                                           title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.facilities.edit', $facility) }}"
                                           class="btn btn-sm btn-outline-warning action-btn-mobile"
                                           data-bs-toggle="tooltip"
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger action-btn-mobile delete-confirm"
                                                data-bs-toggle="tooltip"
                                                title="حذف"
                                                data-facility-id="{{ $facility->id }}"
                                                data-facility-name="{{ $facility->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        <!-- Status Toggle -->
                                        <form action="{{ route('admin.facilities.toggle-status', $facility) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $facility->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} action-btn-mobile"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $facility->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                <i class="fas {{ $facility->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.facilities.toggle-verification', $facility) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $facility->is_verified ? 'btn-outline-warning' : 'btn-outline-info' }} action-btn-mobile"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $facility->is_verified ? 'إلغاء التحقق' : 'تحقق' }}">
                                                <i class="fas {{ $facility->is_verified ? 'fa-times' : 'fa-shield-alt' }}"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.facilities.toggle-featured', $facility) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $facility->is_featured ? 'btn-outline-secondary' : 'btn-outline-warning' }} action-btn-mobile"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $facility->is_featured ? 'إلغاء التمييز' : 'تمييز' }}">
                                                <i class="fas fa-star {{ $facility->is_featured ? 'text-warning' : '' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <!-- Desktop View: Vertical Layout -->
                                    <div class="d-none d-md-flex flex-column gap-1">
                                        <!-- Primary Actions Row -->
                                        <div class="d-flex gap-1 mb-1">
                                            <a href="{{ route('admin.facilities.show', $facility) }}"
                                               class="btn btn-sm btn-outline-info"
                                               data-bs-toggle="tooltip"
                                               title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.facilities.edit', $facility) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               data-bs-toggle="tooltip"
                                               title="تعديل المنشأة">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-confirm"
                                                    data-bs-toggle="tooltip"
                                                    title="حذف المنشأة"
                                                    data-facility-id="{{ $facility->id }}"
                                                    data-facility-name="{{ $facility->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Status Toggle Row -->
                                        <div class="d-flex gap-1 mb-1">
                                            <form action="{{ route('admin.facilities.toggle-status', $facility) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm {{ $facility->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $facility->is_active ? 'إلغاء التفعيل' : 'تفعيل المنشأة' }}">
                                                    <i class="fas {{ $facility->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.facilities.toggle-verification', $facility) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm {{ $facility->is_verified ? 'btn-outline-danger' : 'btn-outline-info' }}"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $facility->is_verified ? 'إلغاء التحقق' : 'التحقق من المنشأة' }}">
                                                    <i class="fas {{ $facility->is_verified ? 'fa-times' : 'fa-shield-alt' }}"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.facilities.toggle-featured', $facility) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm {{ $facility->is_featured ? 'btn-outline-secondary' : 'btn-outline-warning' }}"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $facility->is_featured ? 'إلغاء التمييز' : 'تمييز المنشأة' }}">
                                                    <i class="fas fa-star {{ $facility->is_featured ? 'text-warning' : '' }}"></i>
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
            <div class="pagination-container">
                {{ $facilities->withQueryString()->links() }}
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

/* Avatar placeholder styling */
.avatar-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.avatar-placeholder i {
    font-size: 16px;
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
            { orderable: false, targets: [0, 9] }
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
        params.set('category_id', $('#categoryFilter').val() || '');
        params.set('search', $('#searchInput').val() || '');

        // Redirect with new parameters
        window.location.href = `${url.pathname}?${params.toString()}`;
    }

    // Bind events
    $('#categoryFilter').change(applyFilters);
    $('#searchBtn').click(applyFilters);

    // Reset filters
    $('#resetFilters').click(function() {
        window.location.href = window.location.pathname;
    });

    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let facilityId = $(this).data('facility-id');
        let facilityName = $(this).data('facility-name');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: `سيتم حذف المنشأة "${facilityName}" نهائياً. لا يمكن التراجع عن هذا الإجراء!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف المنشأة',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit delete form
                let form = $('<form>', {
                    'method': 'POST',
                    'action': `/admin/facilities/${facilityId}`
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
