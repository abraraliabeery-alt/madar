@extends('facility.layouts.app')

@section('title', 'الفترات المحاسبية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">الفترات المحاسبية</h3>
                    <div>
                        <a href="{{ route('facility.accounting.accounting-periods.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة فترة جديدة
                        </a>
                        <a href="{{ route('facility.accounting.accounting-periods.create-year') }}" class="btn btn-success">
                            <i class="fas fa-calendar-plus"></i> إنشاء سنة مالية
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- فلترة وبحث -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="البحث في الفترات..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>مفتوحة</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
                                <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>مقفلة</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="start_date" class="form-control" placeholder="من تاريخ" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="end_date" class="form-control" placeholder="إلى تاريخ" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('facility.accounting.accounting-periods.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> مسح
                            </a>
                        </div>
                    </form>

                    @if($periods->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>اسم الفترة</th>
                                        <th>تاريخ البداية</th>
                                        <th>تاريخ النهاية</th>
                                        <th>المدة (أيام)</th>
                                        <th>الحالة</th>
                                        <th>عدد القيود</th>
                                        <th>إجمالي المبالغ</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($periods as $period)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $period->name }}</strong>
                                                    @if($period->is_current)
                                                        <br><small class="text-success">الفترة الحالية</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $period->start_date->format('Y-m-d') }}</td>
                                            <td>{{ $period->end_date->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $period->duration_days }} يوم</span>
                                            </td>
                                            <td>
                                                @if($period->status === 'open')
                                                    <span class="badge bg-success">مفتوحة</span>
                                                @elseif($period->status === 'closed')
                                                    <span class="badge bg-warning">مغلقة</span>
                                                @else
                                                    <span class="badge bg-danger">مقفلة</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-info">{{ $period->entries_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <strong class="{{ ($period->total_amount ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $period->formatted_total_amount ?? '0.00 ر.س' }}
                                                </strong>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('facility.accounting.accounting-periods.show', $period) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.accounting.accounting-periods.edit', $period) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($period->status === 'open')
                                                        <a href="{{ route('facility.accounting.accounting-periods.close', $period) }}" 
                                                           class="btn btn-sm btn-warning" 
                                                           onclick="return confirm('هل أنت متأكد من إغلاق هذه الفترة؟')">
                                                            <i class="fas fa-lock"></i>
                                                        </a>
                                                    @elseif($period->status === 'closed')
                                                        <a href="{{ route('facility.accounting.accounting-periods.lock', $period) }}" 
                                                           class="btn btn-sm btn-danger" 
                                                           onclick="return confirm('هل أنت متأكد من قفل هذه الفترة نهائياً؟')">
                                                            <i class="fas fa-lock"></i>
                                                        </a>
                                                    @endif
                                                    @if($period->canBeDeleted())
                                                        <form method="POST" action="{{ route('facility.accounting.accounting-periods.destroy', $period) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفترة؟')">
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
                            {{ $periods->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد فترات محاسبية</h5>
                            <p class="text-muted">ابدأ بإنشاء فترة محاسبية جديدة</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('facility.accounting.accounting-periods.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> إضافة فترة جديدة
                                </a>
                                <a href="{{ route('facility.accounting.accounting-periods.create-year') }}" class="btn btn-success">
                                    <i class="fas fa-calendar-plus"></i> إنشاء سنة مالية
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
    document.querySelectorAll('select[name="status"]').forEach(select => {
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
