@extends('facility.layouts.app')

@section('title', 'إدارة العقود')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إدارة العقود</h3>
                    <div>
                        <a href="{{ route('facility.contracts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة عقد جديد
                        </a>
                        <a href="{{ route('facility.contracts.statistics') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> الإحصائيات
                        </a>
                    </div>
                </div>

                <!-- فلترة وبحث -->
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="">جميع الأنواع</option>
                                <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>بيع</option>
                                <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>إيجار</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="البحث في العقود..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">بحث</button>
                        </div>
                    </form>

                    @if($contracts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>رقم العقد</th>
                                        <th>المنتج</th>
                                        <th>العميل</th>
                                        <th>النوع</th>
                                        <th>المبلغ الإجمالي</th>
                                        <th>المدة</th>
                                        <th>التقدم</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contracts as $contract)
                                        <tr>
                                            <td>
                                                <strong>{{ $contract->contract_number ?: 'CON-' . $contract->id }}</strong>
                                                @if($contract->is_verified)
                                                    <span class="badge bg-success">موثق</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('facility.products.show', $contract->product) }}" class="text-decoration-none">
                                                    {{ $contract->product->getTranslatedTitle() }}
                                                </a>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $contract->user->name }}</strong>
                                                    <br><small class="text-muted">{{ $contract->user->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $contract->contract_type == 'sale' ? 'success' : 'info' }}">
                                                    {{ $contract->contract_type == 'sale' ? 'بيع' : 'إيجار' }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($contract->total_amount, 2) }} {{ $contract->currency }}</strong>
                                                @if($contract->deposit_amount)
                                                    <br><small class="text-muted">عربون: {{ number_format($contract->deposit_amount, 2) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($contract->contract_duration_months)
                                                    {{ $contract->contract_duration_months }} شهر
                                                @elseif($contract->end_date)
                                                    {{ $contract->start_date->diffInMonths($contract->end_date) }} شهر
                                                @else
                                                    غير محدد
                                                @endif
                                            </td>
                                            <td>
                                                <div class="progress" style="width: 100px; height: 20px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $contract->getProgressPercentage() }}%">
                                                        {{ round($contract->getProgressPercentage()) }}%
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $contract->paid_installments }}/{{ $contract->total_installments }} قسط
                                                </small>
                                            </td>
                                            <td>
                                                @switch($contract->status)
                                                    @case('draft')
                                                        <span class="badge bg-secondary">مسودة</span>
                                                        @break
                                                    @case('active')
                                                        <span class="badge bg-success">نشط</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-primary">مكتمل</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">ملغي</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('facility.contracts.show', $contract) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.contracts.edit', $contract) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($contract->status == 'active' && $contract->canBeRenewed())
                                                        <form method="POST" action="{{ route('facility.contracts.renew', $contract) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" title="تجديد العقد">
                                                                <i class="fas fa-redo"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form method="POST" action="{{ route('facility.contracts.destroy', $contract) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العقد؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
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
                        <div class="d-flex justify-content-center">
                            {{ $contracts->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد عقود</h5>
                            <p class="text-muted">ابدأ بإنشاء عقد جديد</p>
                            <a href="{{ route('facility.contracts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة عقد جديد
                            </a>
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
    document.querySelectorAll('select[name="type"], select[name="status"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush