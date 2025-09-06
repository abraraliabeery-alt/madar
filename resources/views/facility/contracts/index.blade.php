@extends('layouts.facility')

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
                        <a href="{{ route('facility.financial.index') }}" class="btn btn-info">
                            <i class="fas fa-chart-line"></i> التقارير المالية
                        </a>
                    </div>
                </div>

                <!-- فلاتر البحث -->
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
                                        <th>#</th>
                                        <th>رقم العقد</th>
                                        <th>المنتج</th>
                                        <th>العميل</th>
                                        <th>المالك</th>
                                        <th>النوع</th>
                                        <th>المبلغ</th>
                                        <th>المدفوع</th>
                                        <th>المتبقي</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contracts as $contract)
                                        <tr>
                                            <td>{{ $contract->id }}</td>
                                            <td>
                                                <strong>{{ $contract->contract_number ?? 'غير محدد' }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($contract->product->image)
                                                        <img src="{{ asset('storage/' . $contract->product->image) }}" 
                                                             class="rounded me-2" width="40" height="40" alt="صورة المنتج">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $contract->product->getTranslatedTitle() }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $contract->product->address }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $contract->user->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $contract->user->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $contract->owner->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $contract->owner->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $contract->contract_type == 'sale' ? 'success' : 'info' }}">
                                                    {{ $contract->contract_type == 'sale' ? 'بيع' : 'إيجار' }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($contract->total_amount, 2) }} {{ $contract->currency }}</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $totalPaid = $contract->getTotalPaidAmount();
                                                @endphp
                                                <span class="text-success">{{ number_format($totalPaid, 2) }} {{ $contract->currency }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $remaining = $contract->getRemainingAmount();
                                                @endphp
                                                <span class="text-{{ $remaining > 0 ? 'warning' : 'success' }}">
                                                    {{ number_format($remaining, 2) }} {{ $contract->currency }}
                                                </span>
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
                                                    <a href="{{ route('facility.contracts.show', $contract) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.contracts.edit', $contract) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('facility.contracts.financial-report', $contract) }}" 
                                                       class="btn btn-sm btn-outline-info" title="التقرير المالي">
                                                        <i class="fas fa-chart-line"></i>
                                                    </a>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                                type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('facility.contracts.invoices', $contract) }}">
                                                                    <i class="fas fa-file-invoice me-2"></i>الفواتير
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('facility.contracts.payments', $contract) }}">
                                                                    <i class="fas fa-credit-card me-2"></i>المدفوعات
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('facility.contracts.destroy', $contract) }}" 
                                                                      method="POST" class="d-inline"
                                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا العقد؟')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="fas fa-trash me-2"></i>حذف
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
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
