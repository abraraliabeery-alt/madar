@extends('facility.layouts.app')

@section('title', 'إدارة الفواتير')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إدارة الفواتير</h3>
                    <div>
                        <a href="{{ route('facility.invoices.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة فاتورة جديدة
                        </a>
                        <a href="{{ route('facility.invoices.generate') }}" class="btn btn-success">
                            <i class="fas fa-magic"></i> إنشاء فواتير تلقائية
                        </a>
                        <a href="{{ route('facility.invoices.statistics') }}" class="btn btn-info">
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
                                <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>إيجار</option>
                                <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>بيع</option>
                                <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>عربون</option>
                                <option value="commission" {{ request('type') == 'commission' ? 'selected' : '' }}>عمولة</option>
                                <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>استرداد</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>مرسل</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخر</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="البحث في الفواتير..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">بحث</button>
                        </div>
                    </form>

                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>رقم الفاتورة</th>
                                        <th>العقد</th>
                                        <th>المنتج</th>
                                        <th>النوع</th>
                                        <th>المبلغ</th>
                                        <th>المدفوع</th>
                                        <th>المتبقي</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr class="{{ $invoice->isOverdue() ? 'table-danger' : '' }}">
                                            <td>
                                                <strong>{{ $invoice->invoice_number ?: 'INV-' . $invoice->id }}</strong>
                                                @if($invoice->installment_number)
                                                    <br><small class="text-muted">قسط رقم {{ $invoice->installment_number }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('facility.contracts.show', $invoice->contract) }}" class="text-decoration-none">
                                                    {{ $invoice->contract->contract_number ?: 'CON-' . $invoice->contract->id }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('facility.products.show', $invoice->contract->product) }}" class="text-decoration-none">
                                                    {{ $invoice->contract->product->getTranslatedTitle() }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $invoice->invoice_type == 'sale' ? 'success' : ($invoice->invoice_type == 'rent' ? 'info' : 'warning') }}">
                                                    @switch($invoice->invoice_type)
                                                        @case('rent') إيجار @break
                                                        @case('sale') بيع @break
                                                        @case('deposit') عربون @break
                                                        @case('commission') عمولة @break
                                                        @case('refund') استرداد @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</strong>
                                                @if($invoice->tax_amount > 0)
                                                    <br><small class="text-muted">ضريبة: {{ number_format($invoice->tax_amount, 2) }}</small>
                                                @endif
                                                @if($invoice->late_fee_amount > 0)
                                                    <br><small class="text-danger">رسوم تأخير: {{ number_format($invoice->late_fee_amount, 2) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-success">{{ number_format($invoice->paid_amount, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="{{ $invoice->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($invoice->remaining_amount, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="{{ $invoice->isOverdue() ? 'text-danger' : '' }}">
                                                    {{ $invoice->due_date->format('Y-m-d') }}
                                                </span>
                                                @if($invoice->isOverdue())
                                                    <br><small class="text-danger">{{ $invoice->getDaysUntilDue() }} يوم متأخر</small>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($invoice->status)
                                                    @case('draft')
                                                        <span class="badge bg-secondary">مسودة</span>
                                                        @break
                                                    @case('sent')
                                                        <span class="badge bg-info">مرسل</span>
                                                        @break
                                                    @case('paid')
                                                        <span class="badge bg-success">مدفوع</span>
                                                        @break
                                                    @case('overdue')
                                                        <span class="badge bg-danger">متأخر</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-dark">ملغي</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('facility.invoices.show', $invoice) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.invoices.edit', $invoice) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($invoice->needsReminder())
                                                        <form method="POST" action="{{ route('facility.invoices.reminder', $invoice) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning" title="إرسال تذكير">
                                                                <i class="fas fa-bell"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form method="POST" action="{{ route('facility.invoices.destroy', $invoice) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفاتورة؟')">
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
                            {{ $invoices->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد فواتير</h5>
                            <p class="text-muted">ابدأ بإنشاء فاتورة جديدة</p>
                            <a href="{{ route('facility.invoices.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة فاتورة جديدة
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
