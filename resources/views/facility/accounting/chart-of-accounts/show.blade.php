@extends('facility.layouts.app')

@section('title', 'عرض الحساب - ' . $account->account_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">عرض الحساب: {{ $account->account_name }}</h3>
                    <div>
                        <a href="{{ route('facility.accounting.chart-of-accounts.edit', $account) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('facility.accounting.chart-of-accounts.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- معلومات الحساب -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">معلومات الحساب</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">كود الحساب:</label>
                                                <p class="form-control-plaintext">{{ $account->account_code }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">اسم الحساب:</label>
                                                <p class="form-control-plaintext">{{ $account->account_name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">نوع الحساب:</label>
                                                <p class="form-control-plaintext">
                                                    <span class="badge bg-{{ $account->account_type === 'asset' ? 'primary' : ($account->account_type === 'liability' ? 'warning' : ($account->account_type === 'equity' ? 'info' : ($account->account_type === 'revenue' ? 'success' : 'danger'))) }}">
                                                        {{ $accountTypes[$account->account_type] ?? $account->account_type }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">فئة الحساب:</label>
                                                <p class="form-control-plaintext">{{ $accountCategories[$account->account_category] ?? $account->account_category }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">الرصيد الطبيعي:</label>
                                                <p class="form-control-plaintext">
                                                    <span class="badge bg-{{ $account->normal_balance === 'debit' ? 'primary' : 'success' }}">
                                                        {{ $account->normal_balance === 'debit' ? 'مدين' : 'دائن' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">المستوى:</label>
                                                <p class="form-control-plaintext">
                                                    <span class="badge bg-secondary">المستوى {{ $account->level }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($account->parentAccount)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">الحساب الأب:</label>
                                                    <p class="form-control-plaintext">
                                                        <a href="{{ route('facility.accounting.chart-of-accounts.show', $account->parentAccount) }}" class="text-decoration-none">
                                                            {{ $account->parentAccount->account_code }} - {{ $account->parentAccount->account_name }}
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($account->description)
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">الوصف:</label>
                                                    <p class="form-control-plaintext">{{ $account->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">الحالة:</label>
                                                <p class="form-control-plaintext">
                                                    @if($account->is_active)
                                                        <span class="badge bg-success">نشط</span>
                                                    @else
                                                        <span class="badge bg-secondary">غير نشط</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">نوع الحساب:</label>
                                                <p class="form-control-plaintext">
                                                    @if($account->is_system)
                                                        <span class="badge bg-info">حساب نظام</span>
                                                    @else
                                                        <span class="badge bg-light text-dark">حساب عادي</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ملخص مالي -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">الملخص المالي</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">الرصيد الافتتاحي:</label>
                                        <p class="form-control-plaintext h5">
                                            <span class="{{ $account->opening_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $account->formatted_opening_balance }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">الرصيد الحالي:</label>
                                        <p class="form-control-plaintext h4">
                                            <span class="{{ $account->current_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $account->formatted_balance }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">إجمالي الحركات:</label>
                                        <p class="form-control-plaintext">
                                            <span class="text-info">{{ $account->entries_count }} حركة</span>
                                        </p>
                                    </div>

                                    @if($account->children_count > 0)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">الحسابات الفرعية:</label>
                                            <p class="form-control-plaintext">
                                                <span class="text-info">{{ $account->children_count }} حساب فرعي</span>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الحسابات الفرعية -->
                    @if($account->children->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">الحسابات الفرعية</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>كود الحساب</th>
                                                        <th>اسم الحساب</th>
                                                        <th>النوع</th>
                                                        <th>الرصيد الحالي</th>
                                                        <th>الحالة</th>
                                                        <th>الإجراءات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($account->children as $child)
                                                        <tr>
                                                            <td><strong>{{ $child->account_code }}</strong></td>
                                                            <td>{{ $child->account_name }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $child->account_type === 'asset' ? 'primary' : ($child->account_type === 'liability' ? 'warning' : ($child->account_type === 'equity' ? 'info' : ($child->account_type === 'revenue' ? 'success' : 'danger'))) }}">
                                                                    {{ $accountTypes[$child->account_type] ?? $child->account_type }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="{{ $child->current_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                                                    {{ $child->formatted_balance }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @if($child->is_active)
                                                                    <span class="badge bg-success">نشط</span>
                                                                @else
                                                                    <span class="badge bg-secondary">غير نشط</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('facility.accounting.chart-of-accounts.show', $child) }}" class="btn btn-sm btn-info">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- آخر الحركات -->
                    @if($account->entries->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">آخر الحركات</h5>
                                        <a href="{{ route('facility.accounting.entries.index', ['account_id' => $account->id]) }}" class="btn btn-sm btn-outline-primary">
                                            عرض جميع الحركات
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>التاريخ</th>
                                                        <th>الوصف</th>
                                                        <th>نوع القيد</th>
                                                        <th>المبلغ</th>
                                                        <th>الرصيد</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($account->entries()->latest()->limit(10)->get() as $entry)
                                                        <tr>
                                                            <td>{{ $entry->entry_date->format('Y-m-d') }}</td>
                                                            <td>{{ $entry->description }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $entry->entry_type === 'debit' ? 'primary' : 'success' }}">
                                                                    {{ $entry->entry_type === 'debit' ? 'مدين' : 'دائن' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="{{ $entry->entry_type === 'debit' ? 'text-primary' : 'text-success' }}">
                                                                    {{ $entry->formatted_amount }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="{{ $entry->running_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                                                    {{ $entry->formatted_running_balance }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control-plaintext {
    padding: 0.375rem 0;
    margin-bottom: 0;
    line-height: 1.5;
    color: #212529;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
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

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>
@endpush
