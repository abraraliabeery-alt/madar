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
                            <th width="50">#</th>
                            <th width="50">الأيقونة</th>
                            <th>الاسم</th>
                            <th>الوصف</th>
                            <th>اللون</th>
                            <th>عدد المنتجات</th>
                            <th>الترتيب</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="features-list">
                        @foreach($features as $feature)
                        <tr data-id="{{ $feature->id }}">
                            <td>
                                <i class="fas fa-grip-vertical handle text-muted cursor-move"></i>
                            </td>
                            <td>
                                @if($feature->icon)
                                    <img src="{{ Storage::url($feature->icon) }}" alt="icon" width="30" class="rounded">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        <i class="fas fa-star text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $feature->name }}</td>
                            <td>{{ Str::limit($feature->description, 50) }}</td>
                            <td>
                                @if($feature->color)
                                    <span class="badge" style="background-color: {{ $feature->color }}">{{ $feature->color }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $feature->products_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $feature->sort_order ?? 0 }}</span>
                            </td>
                            <td>
                                @if($feature->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.features.show', $feature) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.features.edit', $feature) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.features.destroy', $feature) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-confirm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.features.toggle-status', $feature) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $feature->is_active ? 'btn-danger' : 'btn-success' }}">
                                            <i class="fas {{ $feature->is_active ? 'fa-ban' : 'fa-check' }}"></i>
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
        ]
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
                    sort_order: index
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
                            $(`tr[data-id="${feature.id}"] td:nth-child(7) .badge`).text(feature.sort_order);
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
        let form = $(this).closest('form');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لا يمكن التراجع عن هذا الإجراء!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف المميزة',
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
