@extends('facility.layouts.app')

@section('title', 'إدارة المدفوعات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إدارة المدفوعات</h3>
                    <div>
                        <a href="{{ route('facility.payments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة دفعة جديدة
                        </a>
                        <a href="{{ route('facility.payments.statistics') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> الإحصائيات
                        </a>
                    </div>
                </div>

                <!-- فلترة وبحث -->
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <select name="method" class="form-select">
                                <option value="">جميع الطرق</option>
                                <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>نقداً</option>
                                <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                <option value="credit_card" {{ request('method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                                <option value="check" {{ request('method') == 'check' ? 'selected' : '' }}>شيك</option>
                                <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>دفع إلكتروني</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>مسترد</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="البحث في المدفوعات..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">بحث</button>
                        </div>
                    </form>

                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>المرجع</th>
                                        <th>الفاتورة</th>
                                        <th>العقد</th>
                                        <th>المنتج</th>
                                        <th>الطريقة</th>
                                        <th>المبلغ</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <strong>{{ $payment->reference_number ?: 'PAY-' . $payment->id }}</strong>
                                                @if($payment->payment_reference)
                                                    <br><small class="text-muted">{{ $payment->payment_reference }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->invoice)
                                                    <a href="{{ route('facility.invoices.show', $payment->invoice) }}" class="text-decoration-none">
                                                        {{ $payment->invoice->invoice_number ?: 'INV-' . $payment->invoice->id }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->contract)
                                                    <a href="{{ route('facility.contracts.show', $payment->contract) }}" class="text-decoration-none">
                                                        {{ $payment->contract->contract_number ?: 'CON-' . $payment->contract->id }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->contract && $payment->contract->product)
                                                    <a href="{{ route('facility.products.show', $payment->contract->product) }}" class="text-decoration-none">
                                                        {{ $payment->contract->product->getTranslatedTitle() }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $payment->getPaymentMethodDisplayName() }}
                                                </span>
                                                @if($payment->bank_name)
                                                    <br><small class="text-muted">{{ $payment->bank_name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</strong>
                                                @if($payment->processing_fee > 0)
                                                    <br><small class="text-muted">رسوم: {{ number_format($payment->processing_fee, 2) }}</small>
                                                @endif
                                                @if($payment->installment_number)
                                                    <br><small class="text-info">قسط رقم {{ $payment->installment_number }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $payment->payment_date->format('Y-m-d') }}
                                                <br><small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                @switch($payment->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">معلق</span>
                                                        @break
                                                    @case('confirmed')
                                                        <span class="badge bg-success">مؤكد</span>
                                                        @break
                                                    @case('failed')
                                                        <span class="badge bg-danger">فشل</span>
                                                        @break
                                                    @case('refunded')
                                                        <span class="badge bg-secondary">مسترد</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('facility.payments.show', $payment) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.payments.edit', $payment) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($payment->status == 'pending')
                                                        <form method="POST" action="{{ route('facility.payments.confirm', $payment) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" title="تأكيد الدفعة">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('facility.payments.fail', $payment) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger" title="إلغاء الدفعة">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($payment->status == 'confirmed')
                                                        <form method="POST" action="{{ route('facility.payments.refund', $payment) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من استرداد هذه الدفعة؟')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning" title="استرداد الدفعة">
                                                                <i class="fas fa-undo"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form method="POST" action="{{ route('facility.payments.destroy', $payment) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الدفعة؟')">
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
                            {{ $payments->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد مدفوعات</h5>
                            <p class="text-muted">ابدأ بإضافة دفعة جديدة</p>
                            <a href="{{ route('facility.payments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة دفعة جديدة
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
    document.querySelectorAll('select[name="method"], select[name="status"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush
