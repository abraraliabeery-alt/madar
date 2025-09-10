@extends('facility.layouts.app')

@section('title', 'دليل الحسابات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">دليل الحسابات</h3>
                    <div>
                        <a href="{{ route('facility.accounting.chart-of-accounts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة حساب جديد
                        </a>
                        <a href="{{ route('facility.accounting.chart-of-accounts.create-default') }}" class="btn btn-success">
                            <i class="fas fa-magic"></i> إنشاء دليل افتراضي
                        </a>
                        <a href="{{ route('facility.accounting.chart-of-accounts.export') }}" class="btn btn-info">
                            <i class="fas fa-download"></i> تصدير
                        </a>
                    </div>
                </div>

                <!-- فلترة وبحث -->
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="البحث في الحسابات..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="account_type" class="form-select">
                                <option value="">جميع الأنواع</option>
                                @foreach($accountTypes as $key => $value)
                                    <option value="{{ $key }}" {{ request('account_type') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="account_category" class="form-select">
                                <option value="">جميع الفئات</option>
                                @foreach($accountCategories as $key => $value)
                                    <option value="{{ $key }}" {{ request('account_category') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="is_active" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('facility.accounting.chart-of-accounts.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> مسح
                            </a>
                        </div>
                    </form>

                    @if($accounts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>كود الحساب</th>
                                        <th>اسم الحساب</th>
                                        <th>النوع</th>
                                        <th>الفئة</th>
                                        <th>الرصيد الطبيعي</th>
                                        <th>الرصيد الحالي</th>
                                        <th>المستوى</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accounts as $account)
                                        <tr class="{{ $account->level > 1 ? 'table-light' : '' }}" style="padding-left: {{ ($account->level - 1) * 20 }}px;">
                                            <td>
                                                <strong>{{ $account->account_code }}</strong>
                                                @if($account->is_system)
                                                    <br><small class="text-muted">حساب نظام</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $account->account_name }}</strong>
                                                    @if($account->parentAccount)
                                                        <br><small class="text-muted">تحت: {{ $account->parentAccount->account_name }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $account->account_type === 'asset' ? 'primary' : ($account->account_type === 'liability' ? 'warning' : ($account->account_type === 'equity' ? 'info' : ($account->account_type === 'revenue' ? 'success' : 'danger'))) }}">
                                                    {{ $accountTypes[$account->account_type] ?? $account->account_type }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $accountCategories[$account->account_category] ?? $account->account_category }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $account->normal_balance === 'debit' ? 'primary' : 'success' }}">
                                                    {{ $account->normal_balance === 'debit' ? 'مدين' : 'دائن' }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="{{ $account->current_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $account->formatted_balance }}
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">المستوى {{ $account->level }}</span>
                                            </td>
                                            <td>
                                                @if($account->is_active)
                                                    <span class="badge bg-success">نشط</span>
                                                @else
                                                    <span class="badge bg-secondary">غير نشط</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('facility.accounting.chart-of-accounts.show', $account) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.accounting.chart-of-accounts.edit', $account) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($account->canBeDeleted())
                                                        <form method="POST" action="{{ route('facility.accounting.chart-of-accounts.destroy', $account) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الحساب؟')">
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
                            {{ $accounts->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد حسابات</h5>
                            <p class="text-muted">ابدأ بإنشاء دليل الحسابات</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('facility.accounting.chart-of-accounts.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> إضافة حساب جديد
                                </a>
                                <a href="{{ route('facility.accounting.chart-of-accounts.create-default') }}" class="btn btn-success">
                                    <i class="fas fa-magic"></i> إنشاء دليل افتراضي
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
    document.querySelectorAll('select[name="account_type"], select[name="account_category"], select[name="is_active"]').forEach(select => {
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

.table-light {
    background-color: #f8f9fa !important;
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
