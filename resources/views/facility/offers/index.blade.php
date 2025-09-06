@extends('layouts.facility')

@section('title', 'إدارة العروض')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إدارة العروض</h3>
                    <div>
                        <a href="{{ route('facility.offers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة عرض جديد
                        </a>
                        <a href="{{ route('facility.offers.statistics') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> الإحصائيات
                        </a>
                        <a href="{{ route('facility.offers.export') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> تصدير
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
                                <option value="rent_monthly" {{ request('type') == 'rent_monthly' ? 'selected' : '' }}>إيجار شهري</option>
                                <option value="rent_yearly" {{ request('type') == 'rent_yearly' ? 'selected' : '' }}>إيجار سنوي</option>
                                <option value="rent_daily" {{ request('type') == 'rent_daily' ? 'selected' : '' }}>إيجار يومي</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="البحث في العروض..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">بحث</button>
                        </div>
                    </form>

                    @if($offers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>المنتج</th>
                                        <th>نوع العرض</th>
                                        <th>السعر</th>
                                        <th>العربون</th>
                                        <th>العمولة</th>
                                        <th>الحالة</th>
                                        <th>صالح حتى</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offers as $offer)
                                        <tr>
                                            <td>{{ $offer->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($offer->product->image)
                                                        <img src="{{ asset('storage/' . $offer->product->image) }}" 
                                                             class="rounded me-2" width="40" height="40" alt="صورة المنتج">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $offer->product->getTranslatedTitle() }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $offer->product->address }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $offer->offer_type == 'sale' ? 'success' : 'info' }}">
                                                    @switch($offer->offer_type)
                                                        @case('sale') بيع @break
                                                        @case('rent_monthly') إيجار شهري @break
                                                        @case('rent_yearly') إيجار سنوي @break
                                                        @case('rent_daily') إيجار يومي @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($offer->price, 2) }} {{ $offer->currency }}</strong>
                                            </td>
                                            <td>
                                                @if($offer->deposit_amount)
                                                    {{ number_format($offer->deposit_amount, 2) }} {{ $offer->currency }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($offer->commission_amount)
                                                    {{ number_format($offer->commission_amount, 2) }} {{ $offer->currency }}
                                                    <br>
                                                    <small class="text-muted">({{ ($offer->commission_rate * 100) }}%)</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($offer->isExpired())
                                                    <span class="badge bg-danger">منتهي الصلاحية</span>
                                                @elseif($offer->is_active)
                                                    <span class="badge bg-success">نشط</span>
                                                @else
                                                    <span class="badge bg-secondary">غير نشط</span>
                                                @endif
                                                
                                                @if($offer->is_featured)
                                                    <br><span class="badge bg-warning">مميز</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($offer->valid_to)
                                                    {{ $offer->valid_to->format('Y-m-d') }}
                                                @else
                                                    <span class="text-muted">غير محدد</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('facility.offers.show', $offer) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.offers.edit', $offer) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('facility.offers.toggle-status', $offer) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-{{ $offer->is_active ? 'secondary' : 'success' }}"
                                                                title="{{ $offer->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                            <i class="fas fa-{{ $offer->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('facility.offers.destroy', $offer) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
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
                            {{ $offers->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد عروض</h5>
                            <p class="text-muted">ابدأ بإنشاء عرض جديد لعقارك</p>
                            <a href="{{ route('facility.offers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة عرض جديد
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
