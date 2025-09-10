@extends('facility.layouts.app')

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
                    </div>
                </div>

                <!-- فلترة وبحث -->
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
                                        <th>العرض</th>
                                        <th>المنتج</th>
                                        <th>النوع</th>
                                        <th>السعر</th>
                                        <th>العمولة</th>
                                        <th>الحالة</th>
                                        <th>الأولوية</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offers as $offer)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $offer->offer_title ?: 'عرض ' . $offer->offer_type }}</strong>
                                                    @if($offer->is_featured)
                                                        <span class="badge bg-warning">مميز</span>
                                                    @endif
                                                </div>
                                                @if($offer->offer_description)
                                                    <small class="text-muted">{{ Str::limit($offer->offer_description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('facility.products.show', $offer->product) }}" class="text-decoration-none">
                                                    {{ $offer->product->getTranslatedTitle() }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
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
                                                @if($offer->deposit_amount)
                                                    <br><small class="text-muted">عربون: {{ number_format($offer->deposit_amount, 2) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($offer->commission_rate)
                                                    {{ number_format($offer->commission_rate * 100, 2) }}%
                                                @elseif($offer->commission_amount)
                                                    {{ number_format($offer->commission_amount, 2) }} {{ $offer->currency }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($offer->isActive())
                                                    <span class="badge bg-success">نشط</span>
                                                @elseif($offer->isExpired())
                                                    <span class="badge bg-danger">منتهي</span>
                                                @else
                                                    <span class="badge bg-secondary">غير نشط</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="progress" style="width: 60px; height: 20px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $offer->priority * 10 }}%">
                                                        {{ $offer->priority }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('facility.offers.show', $offer) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facility.offers.edit', $offer) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('facility.offers.toggle-status', $offer) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm {{ $offer->is_active ? 'btn-secondary' : 'btn-success' }}">
                                                            <i class="fas fa-{{ $offer->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('facility.offers.destroy', $offer) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟')">
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
                            {{ $offers->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-gift fa-3x text-muted mb-3"></i>
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