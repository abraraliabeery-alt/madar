@extends('facility.layouts.app')

@section('title', 'معدلات الضرائب')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">معدلات الضرائب</h3>
                    <div>
                        <a href="{{ route('facility.accounting.tax-rates.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة معدل جديد
                        </a>
                        <a href="{{ route('facility.accounting.tax-rates.create-default') }}" class="btn btn-success">
                            <i class="fas fa-magic"></i> إنشاء معدلات افتراضية
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- فلترة وبحث -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="البحث في معدلات الضرائب..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="is_active" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" name="min_rate" class="form-control" placeholder="أقل معدل" value="{{ request('min_rate') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" name="max_rate" class="form-control" placeholder="أعلى معدل" value="{{ request('max_rate') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('facility.accounting.tax-rates.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> مسح
                            </a>
                        </div>
                    </form>

                    @if($taxRates->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>اسم المعدل</th>
                                        <th>المعدل</th>
                                        <th>النسبة المئوية</th>
                                        <th>الوصف</th>
                                        <th>الحالة</th>
                                        <th>عدد الاستخدامات</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($taxRates as $taxRate)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $taxRate->name }}</strong>
                                                    @if($taxRate->is_default)
                                                        <br><small class="text-success">معدل افتراضي</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $taxRate->rate }}</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-{{ $taxRate->rate > 15 ? 'danger' : ($taxRate->rate > 10 ? 'warning' : 'success') }}" 
                                                         role="progressbar" style="width: {{ min(100, ($taxRate->rate / 20) * 100) }}%">
                                                        {{ $taxRate->rate }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $taxRate->description ?: 'لا يوجد وصف' }}</small>
                                            </td>
                                            <td>
                                                @if($taxRate->is_active)
                                                    <span class="badge bg-success">نشط</span>
                                                @else
                                                    <span class="badge bg-secondary">غير نشط</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-info">{{ $taxRate->usage_count ?? 0 }}</span>
                                            </td>
                                            <td>{{ $taxRate->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('facility.accounting.tax-rates.show', $taxRate) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.accounting.tax-rates.edit', $taxRate) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($taxRate->canBeDeleted())
                                                        <form method="POST" action="{{ route('facility.accounting.tax-rates.destroy', $taxRate) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المعدل؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i>
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

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $taxRates->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-percentage fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد معدلات ضرائب</h5>
                            <p class="text-muted">ابدأ بإنشاء معدلات الضرائب</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('facility.accounting.tax-rates.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> إضافة معدل جديد
                                </a>
                                <a href="{{ route('facility.accounting.tax-rates.create-default') }}" class="btn btn-success">
                                    <i class="fas fa-magic"></i> إنشاء معدلات افتراضية
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form on filter change
    document.querySelectorAll('select[name="is_active"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 10px 10px 0 0 !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 0.25rem;
}

.progress {
    background-color: #e9ecef;
    border-radius: 0.375rem;
}

.progress-bar {
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin: 0.125rem 0;
    }
}
</style>
@endpush
