@extends('facility.layouts.app')

@section('title', 'القيود المحاسبية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">القيود المحاسبية</h3>
                    <div>
                        <a href="{{ route('facility.accounting.entries.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> قيد محاسبي جديد
                        </a>
                        <a href="{{ route('facility.accounting.entries.export') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> تصدير
                        </a>
                    </div>
                </div>

                <!-- فلترة وبحث -->
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <select name="period_id" class="form-select">
                                <option value="">جميع الفترات</option>
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                                        {{ $period->period_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="account_type" class="form-select">
                                <option value="">جميع أنواع الحسابات</option>
                                @foreach($accountTypes as $key => $value)
                                    <option value="{{ $key }}" {{ request('account_type') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control" placeholder="من تاريخ" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control" placeholder="إلى تاريخ" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="is_reversed" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="0" {{ request('is_reversed') === '0' ? 'selected' : '' }}>نشط</option>
                                <option value="1" {{ request('is_reversed') === '1' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('facility.accounting.entries.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> مسح الفلاتر
                            </a>
                        </div>
                    </form>

                    @if($entries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>نوع القيد</th>
                                        <th>الحساب</th>
                                        <th>المبلغ</th>
                                        <th>الوصف</th>
                                        <th>الفترة</th>
                                        <th>منشئ القيد</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entries as $entry)
                                        <tr class="{{ $entry->is_reversed ? 'table-secondary' : '' }}">
                                            <td>{{ $entry->entry_date->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $entry->entry_type === 'debit' ? 'primary' : 'success' }}">
                                                    {{ $entry->entry_type === 'debit' ? 'مدين' : 'دائن' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $entry->account->account_name ?? 'غير محدد' }}</strong>
                                                    @if($entry->account)
                                                        <br><small class="text-muted">{{ $entry->account->account_code }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $entry->formatted_amount }}</strong>
                                                @if($entry->tax_amount > 0)
                                                    <br><small class="text-muted">ضريبة: {{ $entry->formatted_tax_amount }}</small>
                                                @endif
                                            </td>
                                            <td>{{ Str::limit($entry->description, 50) }}</td>
                                            <td>{{ $entry->period->period_name ?? 'غير محدد' }}</td>
                                            <td>{{ $entry->createdBy->name }}</td>
                                            <td>
                                                @if($entry->is_reversed)
                                                    <span class="badge bg-danger">ملغي</span>
                                                    @if($entry->reversed_at)
                                                        <br><small class="text-muted">{{ $entry->reversed_at->format('Y-m-d H:i') }}</small>
                                                    @endif
                                                @else
                                                    <span class="badge bg-success">نشط</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('facility.accounting.entries.show', $entry) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($entry->canBeReversed())
                                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#reverseModal{{ $entry->id }}">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
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
                            {{ $entries->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد قيود محاسبية</h5>
                            <p class="text-muted">ابدأ بإنشاء قيد محاسبي جديد</p>
                            <a href="{{ route('facility.accounting.entries.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إنشاء قيد جديد
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for reversing entries -->
@foreach($entries as $entry)
    @if($entry->canBeReversed())
    <div class="modal fade" id="reverseModal{{ $entry->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إلغاء القيد المحاسبي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('facility.accounting.entries.reverse', $entry) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            هل أنت متأكد من إلغاء هذا القيد المحاسبي؟ سيتم إنشاء قيد معكوس تلقائياً.
                        </div>
                        <div class="mb-3">
                            <label for="reason{{ $entry->id }}" class="form-label">سبب الإلغاء *</label>
                            <textarea class="form-control" id="reason{{ $entry->id }}" name="reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">تأكيد الإلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection

@push('scripts')
<script>
    // Auto-submit form on filter change
    document.querySelectorAll('select[name="period_id"], select[name="account_type"], select[name="is_reversed"]').forEach(select => {
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
