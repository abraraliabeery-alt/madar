@extends('facility.layouts.app')

@section('title', 'الميزانيات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">الميزانيات</h3>
                    <div>
                        <a href="{{ route('facility.accounting.budgets.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة ميزانية جديدة
                        </a>
                        <a href="{{ route('facility.accounting.budgets.create-year') }}" class="btn btn-success">
                            <i class="fas fa-calendar-plus"></i> إنشاء ميزانية سنوية
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- فلترة وبحث -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="البحث في الميزانيات..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشطة</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمدة</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
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
                            <a href="{{ route('facility.accounting.budgets.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> مسح
                            </a>
                        </div>
                    </form>

                    @if($budgets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>اسم الميزانية</th>
                                        <th>الفترة</th>
                                        <th>المبلغ المخصص</th>
                                        <th>المبلغ المنفق</th>
                                        <th>المتبقي</th>
                                        <th>نسبة الاستهلاك</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($budgets as $budget)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $budget->name }}</strong>
                                                    @if($budget->is_current)
                                                        <br><small class="text-success">ميزانية حالية</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="text-muted">{{ $budget->start_date->format('Y-m-d') }}</small>
                                                    <br>
                                                    <small class="text-muted">{{ $budget->end_date->format('Y-m-d') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <strong class="text-primary">{{ $budget->formatted_amount }}</strong>
                                            </td>
                                            <td>
                                                <strong class="text-warning">{{ $budget->formatted_spent_amount ?? '0.00 ر.س' }}</strong>
                                            </td>
                                            <td>
                                                <strong class="{{ ($budget->remaining_amount ?? $budget->amount) >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $budget->formatted_remaining_amount ?? $budget->formatted_amount }}
                                                </strong>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    @php
                                                        $usagePercentage = $budget->amount > 0 ? (($budget->spent_amount ?? 0) / $budget->amount) * 100 : 0;
                                                    @endphp
                                                    <div class="progress-bar bg-{{ $usagePercentage > 90 ? 'danger' : ($usagePercentage > 75 ? 'warning' : 'success') }}" 
                                                         role="progressbar" style="width: {{ min(100, $usagePercentage) }}%">
                                                        {{ number_format($usagePercentage, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($budget->status === 'pending')
                                                    <span class="badge bg-warning">معلقة</span>
                                                @elseif($budget->status === 'active')
                                                    <span class="badge bg-success">نشطة</span>
                                                @elseif($budget->status === 'approved')
                                                    <span class="badge bg-info">معتمدة</span>
                                                @else
                                                    <span class="badge bg-secondary">مكتملة</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('facility.accounting.budgets.show', $budget) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.accounting.budgets.edit', $budget) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($budget->status === 'pending')
                                                        <a href="{{ route('facility.accounting.budgets.approve', $budget) }}" 
                                                           class="btn btn-sm btn-success" 
                                                           onclick="return confirm('هل أنت متأكد من اعتماد هذه الميزانية؟')">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    @endif
                                                    @if($budget->canBeDeleted())
                                                        <form method="POST" action="{{ route('facility.accounting.budgets.destroy', $budget) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الميزانية؟')">
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
                            {{ $budgets->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد ميزانيات</h5>
                            <p class="text-muted">ابدأ بإنشاء ميزانية جديدة</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('facility.accounting.budgets.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> إضافة ميزانية جديدة
                                </a>
                                <a href="{{ route('facility.accounting.budgets.create-year') }}" class="btn btn-success">
                                    <i class="fas fa-calendar-plus"></i> إنشاء ميزانية سنوية
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
