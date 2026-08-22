@php
$currentSort = request('sort', 'created_at');
$currentDir = request('direction', 'desc') === 'asc' ? 'asc' : 'desc';
$arrow = function ($col) use ($currentSort, $currentDir) {
    if ($currentSort !== $col) {
        return '<span class="text-muted sort-icon">↕</span>';
    }
    return $currentDir === 'asc'
        ? '<span class="sort-icon">↑</span>'
        : '<span class="sort-icon">↓</span>';
};
@endphp

<div class="table-responsive" data-intro="{{ __('admin.tour.attributes_table_desc') }}" data-step="34">
    <table class="table table-striped" id="attributes-table">
        <thead>
            <tr>
                <th class="sortable" data-sort="id"># {!! $arrow('id') !!}</th>
                <th class="sortable" data-sort="name">الاسم {!! $arrow('name') !!}</th>
                <th class="sortable" data-sort="type">النوع {!! $arrow('type') !!}</th>
                <th>الفئة</th>
                <th class="sortable" data-sort="required">الحالة {!! $arrow('required') !!}</th>
                <th class="sortable" data-sort="is_active">التفعيل {!! $arrow('is_active') !!}</th>
                <th>الرمز</th>
                <th class="sortable" data-sort="products_count">عدد العقارات {!! $arrow('products_count') !!}</th>
                <th class="sortable" data-sort="created_at">تاريخ الإنشاء {!! $arrow('created_at') !!}</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attributes as $attribute)
            <tr>
                <td>{{ $attribute->id }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        @if($attribute->icon)
                            @if(\Illuminate\Support\Str::contains($attribute->icon, 'fa-') || !\Illuminate\Support\Str::contains($attribute->icon, '/'))
                                <span class="me-2"><i class="{{ $attribute->icon }}"></i></span>
                            @else
                                <img src="{{ asset($attribute->icon) }}" alt="{{ $attribute->getTranslatedName() ?? 'N/A' }}" class="me-2" style="width: 20px; height: 20px;">
                            @endif
                        @else
                            <div class="avatar-placeholder me-2" style="width: 20px; height: 20px;">
                                <i class="fas fa-tag"></i>
                            </div>
                        @endif
                        <span>{{ $attribute->getTranslatedName() ?? 'N/A' }}</span>
                    </div>
                </td>
                <td>
                    <span class="badge bg-info">
                        @switch($attribute->type)
                            @case('text') نص @break
                            @case('number') رقم @break
                            @case('boolean') نعم/لا @break
                            @case('select') قائمة @break
                            @default {{ $attribute->type }}
                        @endswitch
                    </span>
                </td>
                <td>
                    @if($attribute->category)
                        <span class="badge bg-secondary">{{ $attribute->category->name }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    @if($attribute->required)
                        <span class="badge bg-danger">إلزامية</span>
                    @else
                        <span class="badge bg-warning">اختيارية</span>
                    @endif
                </td>
                <td>
                    @if($attribute->is_active)
                        <span class="badge bg-success">مفعلة</span>
                    @else
                        <span class="badge bg-secondary">معطلة</span>
                    @endif
                </td>
                <td>{{ $attribute->Symbol ?? '-' }}</td>
                <td>
                    <span class="badge bg-primary">{{ $attribute->products_count }}</span>
                </td>
                <td>{{ $attribute->created_at->format('Y-m-d') }}</td>
                <td>
                    <div class="action-buttons">
                        <!-- Mobile View: Compact Horizontal Layout -->
                        <div class="d-flex d-md-none gap-1 flex-wrap">
                            <a href="{{ route('admin.attributes.show', $attribute) }}"
                               class="btn btn-sm btn-outline-info action-btn-mobile"
                               data-bs-toggle="tooltip"
                               title="عرض">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.attributes.edit', $attribute) }}"
                               class="btn btn-sm btn-outline-warning action-btn-mobile"
                               data-bs-toggle="tooltip"
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger action-btn-mobile delete-confirm"
                                    data-bs-toggle="tooltip"
                                    title="حذف"
                                    data-attribute-id="{{ $attribute->id }}"
                                    data-attribute-name="{{ $attribute->getTranslatedName() ?? 'N/A' }}">
                                <i class="fas fa-trash"></i>
                            </button>

                            <!-- Toggle Required & Status -->
                            <form method="POST" action="{{ route('admin.attributes.toggle-required', $attribute) }}" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm btn-outline-secondary action-btn-mobile"
                                        data-bs-toggle="tooltip"
                                        title="{{ $attribute->required ? 'اختيارية' : 'إلزامية' }}">
                                    <i class="fas fa-toggle-{{ $attribute->required ? 'on' : 'off' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.attributes.toggle-status', $attribute) }}" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm {{ $attribute->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} action-btn-mobile"
                                        data-bs-toggle="tooltip"
                                        title="{{ $attribute->is_active ? 'تعطيل' : 'تفعيل' }}">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Desktop View: Vertical Layout -->
                        <div class="d-none d-md-flex flex-column gap-1">
                            <!-- Primary Actions Row -->
                            <div class="d-flex gap-1 mb-1">
                                <a href="{{ route('admin.attributes.show', $attribute) }}"
                                   class="btn btn-sm btn-outline-info"
                                   data-bs-toggle="tooltip"
                                   title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.attributes.edit', $attribute) }}"
                                   class="btn btn-sm btn-outline-warning"
                                   data-bs-toggle="tooltip"
                                   title="تعديل الخاصية">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger delete-confirm"
                                        data-bs-toggle="tooltip"
                                        title="حذف الخاصية"
                                        data-attribute-id="{{ $attribute->id }}"
                                        data-attribute-name="{{ $attribute->getTranslatedName() ?? 'N/A' }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Toggle Required & Status Row -->
                            <div class="d-flex gap-1 mb-1">
                                <form method="POST" action="{{ route('admin.attributes.toggle-required', $attribute) }}" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="tooltip"
                                            title="{{ $attribute->required ? 'جعل اختيارية' : 'جعل إلزامية' }}">
                                        <i class="fas fa-toggle-{{ $attribute->required ? 'on' : 'off' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.attributes.toggle-status', $attribute) }}" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm {{ $attribute->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                            data-bs-toggle="tooltip"
                                            title="{{ $attribute->is_active ? 'تعطيل' : 'تفعيل' }}">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">لا توجد خصائص</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="pagination-container">
    {{ $attributes->links() }}
</div>
