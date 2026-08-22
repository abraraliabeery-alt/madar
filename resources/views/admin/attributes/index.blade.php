@extends('admin.layouts.app')

@section('title', 'إدارة الخصائص')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" data-intro="{{ __('admin.tour.attributes_header_desc') }}" data-step="32">
                    <h4 class="mb-0">إدارة الخصائص</h4>
                    <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة خاصية جديدة
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3" data-intro="{{ __('admin.tour.attributes_filters_desc') }}" data-step="33">
                        <div class="col-md-12">
                            <div class="row g-3" id="attributes-filters">
                                <input type="hidden" id="sort" value="{{ request('sort', 'created_at') }}">
                                <input type="hidden" id="direction" value="{{ request('direction', 'desc') }}">

                                <div class="col-md-3">
                                    <select name="main_category_id" id="main_category_id" class="form-control">
                                        <option value="">اختر القطاع</option>
                                        @foreach($mainCategories as $mainCategory)
                                            <option value="{{ $mainCategory->id }}" {{ request('main_category_id') == $mainCategory->id ? 'selected' : '' }}>
                                                {{ $mainCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <select name="subcategory_id" id="subcategory_id" class="form-control">
                                        <option value="" data-parent-id="">اختر الفئة الفرعية</option>
                                        @foreach($subCategories as $subCategory)
                                            <option value="{{ $subCategory->id }}"
                                                    data-parent-id="{{ $subCategory->parent_id }}"
                                                    {{ request('subcategory_id') == $subCategory->id ? 'selected' : '' }}>
                                                {{ $subCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" name="q" id="q" class="form-control" placeholder="البحث في الخصائص..." value="{{ request('q') }}">
                                </div>

                                <div class="col-md-2">
                                    <select name="type" id="type" class="form-control">
                                        <option value="">جميع الأنواع</option>
                                        <option value="text" {{ request('type') == 'text' ? 'selected' : '' }}>نص</option>
                                        <option value="number" {{ request('type') == 'number' ? 'selected' : '' }}>رقم</option>
                                        <option value="boolean" {{ request('type') == 'boolean' ? 'selected' : '' }}>نعم/لا</option>
                                        <option value="select" {{ request('type') == 'select' ? 'selected' : '' }}>قائمة</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select name="required" id="required" class="form-control">
                                        <option value="">جميع الخصائص</option>
                                        <option value="1" {{ request('required') == '1' ? 'selected' : '' }}>إلزامية</option>
                                        <option value="0" {{ request('required') == '0' ? 'selected' : '' }}>اختيارية</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="button" id="clear-filters" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-times"></i> مسح
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attributes Table -->
                    <div id="attributes-table-container">
                        @include('admin.attributes._table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
    background-color: var(--brand-brown);
    border-color: var(--brand-brown);
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

/* Toggle Required Row */
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
.table td:last-child {
    padding: 0.5rem;
    min-width: 120px;
}

@media (max-width: 767px) {
    .table td:last-child {
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
    font-size: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.avatar-placeholder i {
    font-size: 12px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Delete confirmation
    $(document).on('click', '.delete-confirm', function(e) {
        e.preventDefault();
        let attributeId = $(this).data('attribute-id');
        let attributeName = $(this).data('attribute-name');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: `سيتم حذف الخاصية "${attributeName}" نهائياً. لا يمكن التراجع عن هذا الإجراء!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف الخاصية',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let $row = $(this).closest('tr');
                $.ajax({
                    url: `/admin/attributes/${attributeId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        $row.fadeOut(300, function() {
                            $(this).remove();
                            initTooltips();
                        });
                        Swal.fire({
                            title: 'تم الحذف!',
                            text: `تم حذف الخاصية "${attributeName}" بنجاح.`,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire('خطأ', 'حدث خطأ أثناء حذف الخاصية.', 'error');
                    }
                });
            }
        });
    });

    // AJAX filters, sorting and pagination
    function initTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    function updateSubcategories() {
        const mainId = $('#main_category_id').val();
        const subSelect = $('#subcategory_id');
        subSelect.find('option').each(function () {
            const parentId = $(this).data('parent-id');
            const option = $(this);
            if (!mainId || !parentId || parentId == mainId) {
                option.show();
            } else {
                option.hide();
                if (option.is(':selected')) {
                    subSelect.val('');
                }
            }
        });
    }

    function loadAttributes(page) {
        page = page || 1;
        const params = new URLSearchParams();
        params.set('partial', '1');

        const mainId = $('#main_category_id').val();
        if (mainId) params.set('main_category_id', mainId);

        const subId = $('#subcategory_id').val();
        if (subId) params.set('subcategory_id', subId);

        const q = $('#q').val();
        if (q) params.set('q', q);

        const type = $('#type').val();
        if (type) params.set('type', type);

        const required = $('#required').val();
        if (required !== '') params.set('required', required);

        params.set('sort', $('#sort').val());
        params.set('direction', $('#direction').val());
        params.set('page', page);

        const url = `{{ route('admin.attributes.index') }}?${params.toString()}`;

        $('#attributes-table-container').html('<div class="text-center p-5"><div class="spinner-border" role="status"></div></div>');

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            $('#attributes-table-container').html(html);
            initTooltips();
        });
    }

    $('#main_category_id').on('change', function () {
        updateSubcategories();
        loadAttributes(1);
    });

    $('#subcategory_id, #type, #required').on('change', function () {
        loadAttributes(1);
    });

    let qTimer;
    $('#q').on('input', function () {
        clearTimeout(qTimer);
        qTimer = setTimeout(() => loadAttributes(1), 300);
    });

    $(document).on('click', '.sortable', function (e) {
        e.preventDefault();
        const col = $(this).data('sort');
        const currentSort = $('#sort').val();
        const currentDir = $('#direction').val();
        const newDir = (currentSort === col && currentDir === 'asc') ? 'desc' : 'asc';
        $('#sort').val(col);
        $('#direction').val(newDir);
        loadAttributes(1);
    });

    $(document).on('click', '#attributes-table-container .pagination a', function (e) {
        e.preventDefault();
        const href = $(this).attr('href');
        if (!href) return;

        const url = new URL(href, window.location.href);
        const page = url.searchParams.get('page') || 1;
        loadAttributes(page);
    });

    $('#clear-filters').on('click', function () {
        $('#main_category_id, #subcategory_id, #q, #type, #required').val('');
        $('#subcategory_id option').show();
        $('#sort').val('created_at');
        $('#direction').val('desc');
        loadAttributes(1);
    });

    updateSubcategories();
});
</script>
@endpush
