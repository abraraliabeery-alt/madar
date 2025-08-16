@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تفاصيل العقد - {{ $contract->contract_number }}</h5>
            <div>
                <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <a href="{{ route('admin.contracts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Contract Status -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                @if($contract->status)
                                    <span class="badge bg-{{ $contract->status->color }} fs-5 px-4 py-2">{{ $contract->status->name }}</span>
                                @else
                                    <span class="badge bg-secondary fs-5 px-4 py-2">لا توجد حالة</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                @if($contract->is_active)
                                    <span class="badge bg-success fs-6">نشط</span>
                                @else
                                    <span class="badge bg-danger fs-6">غير نشط</span>
                                @endif

                                @if($contract->is_verified)
                                    <span class="badge bg-info fs-6">تم التحقق</span>
                                @else
                                    <span class="badge bg-warning fs-6">قيد التحقق</span>
                                @endif
                            </div>
                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.contracts.toggle-status', $contract) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $contract->is_active ? 'btn-danger' : 'btn-success' }} w-100">
                                        <i class="fas {{ $contract->is_active ? 'fa-ban me-2' : 'fa-check me-2' }}"></i>
                                        {{ $contract->is_active ? 'إلغاء تفعيل العقد' : 'تفعيل العقد' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.contracts.toggle-verification', $contract) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $contract->is_verified ? 'btn-warning' : 'btn-info' }} w-100">
                                        <i class="fas {{ $contract->is_verified ? 'fa-times me-2' : 'fa-shield-alt me-2' }}"></i>
                                        {{ $contract->is_verified ? 'إلغاء التحقق من العقد' : 'التحقق من العقد' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.contracts.destroy', $contract) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100 delete-confirm">
                                        <i class="fas fa-trash me-2"></i>حذف العقد
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contract Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات العقد</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">نوع العقد</label>
                                    <p class="fs-5">
                                        @switch($contract->contract_type)
                                            @case('sale')
                                                <span class="badge bg-success">بيع</span>
                                                @break
                                            @case('rent')
                                                <span class="badge bg-info">إيجار</span>
                                                @break
                                            @case('lease')
                                                <span class="badge bg-warning">تأجير</span>
                                                @break
                                        @endswitch
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">تاريخ إنشاء العقد</label>
                                    <p class="fs-5">{{ $contract->created_at->format('Y-m-d') }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">تاريخ البداية</label>
                                    <p class="fs-5">{{ $contract->start_date }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">تاريخ النهاية</label>
                                    <p class="fs-5">{{ $contract->end_date }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted">المبلغ الإجمالي</label>
                                    <p class="fs-5">{{ number_format($contract->total_amount, 2) }} ريال</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted">الدفعة المقدمة</label>
                                    <p class="fs-5">{{ number_format($contract->down_payment, 2) }} ريال</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted">القسط الشهري</label>
                                    <p class="fs-5">{{ number_format($contract->monthly_payment, 2) }} ريال</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات المستخدم</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if($contract->user->avatar)
                                    <img src="{{ asset($contract->user->avatar) }}" alt="avatar" class="rounded-circle me-3" width="60">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                        {{ substr($contract->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $contract->user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $contract->user->email }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label class="form-label text-muted">رقم الهاتف</label>
                                    <p>{{ $contract->user->phone_number }}</p>
                                </div>
                                <div class="col-12">
                                    <a href="{{ route('admin.users.show', $contract->user) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-user me-2"></i>عرض الملف الشخصي
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات المنتج</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if($contract->product->main_image)
                                    <img src="{{ asset($contract->product->main_image) }}" alt="product" class="rounded me-3" width="80">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-box fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $contract->product->name }}</h5>
                                    <p class="text-muted mb-0">{{ number_format($contract->product->price, 2) }} ريال</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label class="form-label text-muted">المنشأة</label>
                                    <p>{{ $contract->facility->name }}</p>
                                </div>
                                <div class="col-12">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.products.show', $contract->product) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-box me-2"></i>عرض المنتج
                                        </a>
                                        <a href="{{ route('admin.facilities.show', $contract->facility) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-building me-2"></i>عرض المنشأة
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($contract->contract_type === 'sale')
                <!-- Bank Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات القرض البنكي</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">البنك</label>
                                    <p>{{ $contract->bank->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">مبلغ القرض</label>
                                    <p>{{ $contract->loan_amount ? number_format($contract->loan_amount, 2) . ' ريال' : '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">نسبة الفائدة</label>
                                    <p>{{ $contract->interest_rate ? $contract->interest_rate . '%' : '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">مدة القرض</label>
                                    <p>{{ $contract->loan_term ? $contract->loan_term . ' شهر' : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Additional Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">معلومات إضافية</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">ملاحظات</label>
                                <div class="p-3 bg-light rounded">
                                    {!! $contract->notes !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
