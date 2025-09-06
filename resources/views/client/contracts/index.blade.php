@extends('layouts.client')

@section('title', 'عقودي')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">عقودي</h3>
                    <div>
                        <a href="{{ route('client.contracts.statistics') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> الإحصائيات
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
                                                    <a href="{{ route('client.contracts.show', $contract) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('client.contracts.financial-report', $contract) }}" 
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
                                                                <a class="dropdown-item" href="{{ route('client.contracts.invoices', $contract) }}">
                                                                    <i class="fas fa-file-invoice me-2"></i>الفواتير
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('client.contracts.payments', $contract) }}">
                                                                    <i class="fas fa-credit-card me-2"></i>المدفوعات
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('client.contracts.payment-page', $contract) }}">
                                                                    <i class="fas fa-credit-card me-2"></i>دفع فاتورة
                                                                </a>
                                                            </li>
                                                            @if($contract->status === 'draft')
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <button class="dropdown-item text-success confirm-contract" 
                                                                            data-contract-id="{{ $contract->id }}">
                                                                        <i class="fas fa-check me-2"></i>تأكيد العقد
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <button class="dropdown-item text-danger cancel-contract" 
                                                                            data-contract-id="{{ $contract->id }}">
                                                                        <i class="fas fa-times me-2"></i>إلغاء العقد
                                                                    </button>
                                                                </li>
                                                            @endif
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
                            <p class="text-muted">ابدأ بطلب عقد جديد</p>
                            <a href="{{ route('client.offers.index') }}" class="btn btn-primary">
                                <i class="fas fa-search"></i> تصفح العروض
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تأكيد العقد -->
<div class="modal fade" id="confirmContractModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد العقد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من تأكيد هذا العقد؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-success" id="confirmContractBtn">تأكيد</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal إلغاء العقد -->
<div class="modal fade" id="cancelContractModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إلغاء العقد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="cancelContractForm">
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">سبب الإلغاء <span class="text-danger">*</span></label>
                        <textarea name="reason" id="cancelReason" class="form-control" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="cancelContractBtn">إلغاء العقد</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentContractId = null;

    // تأكيد العقد
    document.querySelectorAll('.confirm-contract').forEach(button => {
        button.addEventListener('click', function() {
            currentContractId = this.dataset.contractId;
            new bootstrap.Modal(document.getElementById('confirmContractModal')).show();
        });
    });

    document.getElementById('confirmContractBtn').addEventListener('click', function() {
        if (currentContractId) {
            fetch(`/client/contracts/${currentContractId}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });

    // إلغاء العقد
    document.querySelectorAll('.cancel-contract').forEach(button => {
        button.addEventListener('click', function() {
            currentContractId = this.dataset.contractId;
            new bootstrap.Modal(document.getElementById('cancelContractModal')).show();
        });
    });

    document.getElementById('cancelContractBtn').addEventListener('click', function() {
        if (currentContractId) {
            const formData = new FormData(document.getElementById('cancelContractForm'));
            
            fetch(`/client/contracts/${currentContractId}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });

    // Auto-submit form on filter change
    document.querySelectorAll('select[name="type"], select[name="status"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush
