@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة المميزات</h5>
            <a href="{{ route('admin.features.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة مميزة جديدة
            </a>
        </div>
        <div class="card-body">
            <!-- Features Table -->
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th class="d-none d-md-table-cell" width="50">#</th>
                            <th class="d-none d-md-table-cell" width="50">الأيقونة</th>
                            <th>الاسم</th>
                            <th class="d-none d-lg-table-cell">الوصف</th>
                            <th class="d-none d-md-table-cell">اللون</th>
                            <th class="d-none d-lg-table-cell">عدد المنتجات</th>
                            <th class="d-none d-md-table-cell">الترتيب</th>
                            <th class="d-none d-md-table-cell">الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="features-list">
                        @foreach($features as $feature)
                        <tr data-id="{{ $feature->id }}">
                            <td class="d-none d-md-table-cell">
                                <i class="fas fa-grip-vertical handle text-muted cursor-move"></i>
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($feature->icon)
                                    @if(\Illuminate\Support\Str::contains($feature->icon, 'fa-') || !\Illuminate\Support\Str::contains($feature->icon, '/'))
                                        <div class="avatar-placeholder rounded d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; background: transparent; box-shadow: none;">
                                            <i class="{{ $feature->icon }}"></i>
                                        </div>
                                    @else
                                        <img src="{{ asset($feature->icon) }}" alt="icon" width="30" class="rounded">
                                    @endif
                                @else
                                    <div class="avatar-placeholder rounded" style="width: 30px; height: 30px;">
                                        <i class="fas fa-star"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2 d-md-none">
                                        @if($feature->icon)
                                            @if(\Illuminate\Support\Str::contains($feature->icon, 'fa-') || !\Illuminate\Support\Str::contains($feature->icon, '/'))
                                                <div class="avatar-placeholder rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: transparent; box-shadow: none;">
                                                    <i class="{{ $feature->icon }}"></i>
                                                </div>
                                            @else
                                                <img src="{{ asset($feature->icon) }}" alt="icon" width="32" class="rounded">
                                            @endif
                                        @else
                                            <div class="avatar-placeholder rounded" style="width: 32px; height: 32px;">
                                                <i class="fas fa-star"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $feature->getTranslatedName('ar') }}</div>
                                        <div class="small text-muted d-md-none">
                                            {{ Str::limit($feature->getTranslatedDescription('ar'), 30) }}
                                        </div>
                                        <div class="small text-muted d-md-none">
                                            <span class="badge bg-info me-1">{{ $feature->products_count }} منتج</span>
                                            @if($feature->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger">غير نشط</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">{{ Str::limit($feature->getTranslatedDescription('ar'), 50) }}</td>
                            <td class="d-none d-md-table-cell">
                                @if($feature->color)
                                    <span class="badge" style="background-color: {{ $feature->color }}">{{ $feature->color }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge bg-info">{{ $feature->products_count }}</span>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge bg-secondary">{{ $feature->order ?? 0 }}</span>
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($feature->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 action-buttons">
                                    <!-- Primary Actions Row -->
                                    <div class="d-flex gap-1 mb-1">
                                        <a href="{{ route('admin.features.show', $feature) }}"
                                           class="btn btn-sm btn-outline-info"
                                           data-bs-toggle="tooltip"
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.features.edit', $feature) }}"
                                           class="btn btn-sm btn-outline-warning"
                                           data-bs-toggle="tooltip"
                                           title="تعديل المميزة">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger delete-confirm"
                                                data-bs-toggle="tooltip"
                                                title="حذف المميزة"
                                                data-feature-id="{{ $feature->id }}"
                                                data-feature-name="{{ $feature->getTranslatedName('ar') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Status Toggle Row -->
                                    <div class="d-flex gap-1 mb-1">
                                        <form action="{{ route('admin.features.toggle-status', $feature) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $feature->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $feature->is_active ? 'إلغاء التفعيل' : 'تفعيل المميزة' }}">
                                                <i class="fas {{ $feature->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
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
                {{ $features->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
<style>
.cursor-move {
    cursor: move;
}
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('.datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json',
        },
        order: [[6, 'asc']],
        columnDefs: [
            { orderable: false, targets: [0, 1, 8] }
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

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize Sortable
    let el = document.getElementById('features-list');
    let sortable = new Sortable(el, {
        handle: '.handle',
        animation: 150,
        onEnd: function(evt) {
            let features = [];
            $('.datatable tbody tr').each(function(index) {
                features.push({
                    id: $(this).data('id'),
                    order: index
                });
            });

            // Update sort order via AJAX
            $.ajax({
                url: '{{ route("admin.features.reorder") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    features: features
                },
                success: function(response) {
                    if (response.success) {
                        // Update displayed sort order
                        features.forEach(function(feature) {
                            $(`tr[data-id="${feature.id}"] td:nth-child(7) .badge`).text(feature.order);
                        });

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'تم!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
    });

    // Delete confirmation
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let featureId = $(this).data('feature-id');
        let featureName = $(this).data('feature-name');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: `سيتم حذف المميزة "${featureName}" نهائياً. لا يمكن التراجع عن هذا الإجراء!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف المميزة',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit delete form
                let form = $('<form>', {
                    'method': 'POST',
                    'action': `/admin/features/${featureId}`
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
