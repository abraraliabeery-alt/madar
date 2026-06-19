@extends('client.financial.layout')

@section('title', 'العروض المتاحة - منطقة العميل')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">العروض المتاحة</h2>
                <p class="text-muted mb-0">اكتشف أفضل العروض المشاريعية المتاحة</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                    <i class="fas fa-filter"></i> فلاتر
                </button>
                <button type="button" class="btn btn-outline-primary" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i> تحديث
                </button>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="card text-center">
            <div class="card-body py-3">
                <h5 class="text-primary mb-1">{{ number_format($offerStats['total_offers']) }}</h5>
                <small class="text-muted">إجمالي العروض</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="card text-center">
            <div class="card-body py-3">
                <h5 class="text-success mb-1">{{ number_format($offerStats['sale_offers']) }}</h5>
                <small class="text-muted">عروض للبيع</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="card text-center">
            <div class="card-body py-3">
                <h5 class="text-info mb-1">{{ number_format($offerStats['rent_offers']) }}</h5>
                <small class="text-muted">عروض للإيجار</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="card text-center">
            <div class="card-body py-3">
                <h5 class="text-warning mb-1">{{ number_format($offerStats['avg_price'], 0) }}</h5>
                <small class="text-muted">متوسط السعر (ر.س)</small>
            </div>
        </div>
    </div>
</div>

<!-- قسم الفلاتر -->
<div class="collapse mb-4" id="filtersCollapse">
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('client.financial.offers') }}" id="filterForm">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">البحث</label>
                        <input type="text" name="search" class="form-control" placeholder="اسم المشروع أو الموقع..." 
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-lg-2 col-md-6 mb-3">
                        <label class="form-label">المؤسسة</label>
                        <select name="facility_id" class="form-select">
                            <option value="">جميع المؤسسات</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-3">
                        <label class="form-label">نوع العرض</label>
                        <select name="type" class="form-select">
                            <option value="">جميع الأنواع</option>
                            <option value="sale" {{ request('type') === 'sale' ? 'selected' : '' }}>للبيع</option>
                            <option value="rent_monthly" {{ request('type') === 'rent_monthly' ? 'selected' : '' }}>إيجار شهري</option>
                            <option value="rent_yearly" {{ request('type') === 'rent_yearly' ? 'selected' : '' }}>إيجار سنوي</option>
                            <option value="rent_daily" {{ request('type') === 'rent_daily' ? 'selected' : '' }}>إيجار يومي</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-3">
                        <label class="form-label">السعر من</label>
                        <input type="number" name="min_price" class="form-control" placeholder="0" 
                               value="{{ request('min_price') }}">
                    </div>

                    <div class="col-lg-2 col-md-6 mb-3">
                        <label class="form-label">السعر إلى</label>
                        <input type="number" name="max_price" class="form-control" placeholder="1000000" 
                               value="{{ request('max_price') }}">
                    </div>

                    <div class="col-lg-1 col-md-6 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">ترتيب حسب</label>
                        <select name="sort_by" class="form-select">
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>الأحدث</option>
                            <option value="price" {{ request('sort_by') === 'price' ? 'selected' : '' }}>السعر</option>
                            <option value="featured" {{ request('sort_by') === 'featured' ? 'selected' : '' }}>المميز</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-3">
                        <label class="form-label">ترتيب</label>
                        <select name="sort_order" class="form-select">
                            <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>تنازلي</option>
                            <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>تصاعدي</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-3 d-flex align-items-end">
                        <a href="{{ route('client.financial.offers') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times"></i> مسح
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- قائمة العروض -->
@if($offers->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">النتائج ({{ $offers->total() }} عرض)</h5>
            <div class="btn-group btn-group-sm" role="group">
                <input type="radio" class="btn-check" name="view" id="gridView" checked>
                <label class="btn btn-outline-primary" for="gridView">
                    <i class="fas fa-th"></i>
                </label>
                <input type="radio" class="btn-check" name="view" id="listView">
                <label class="btn btn-outline-primary" for="listView">
                    <i class="fas fa-list"></i>
                </label>
            </div>
        </div>
    </div>
</div>

<div id="offersContainer">
    <div class="row" id="gridContainer">
        @foreach($offers as $offer)
        <div class="col-lg-4 col-md-6 mb-4 offer-card">
            <div class="card h-100 shadow-sm">
                @if($offer->product->gallery && $offer->product->gallery->count() > 0)
                <div class="position-relative">
                    <img src="{{ $offer->product->gallery->first()->image_url }}" 
                         class="card-img-top" alt="{{ $offer->product->getTranslatedTitle() }}"
                         style="height: 200px; object-fit: cover;">
                    
                    @if($offer->is_featured)
                    <span class="position-absolute top-0 start-0 badge bg-warning m-2">
                        <i class="fas fa-star"></i> مميز
                    </span>
                    @endif
                    
                    <span class="position-absolute top-0 end-0 m-2">
                        @switch($offer->offer_type)
                            @case('sale')
                                <span class="badge bg-success">للبيع</span>
                                @break
                            @case('rent_monthly')
                                <span class="badge bg-primary">إيجار شهري</span>
                                @break
                            @case('rent_yearly')
                                <span class="badge bg-info">إيجار سنوي</span>
                                @break
                            @case('rent_daily')
                                <span class="badge bg-secondary">إيجار يومي</span>
                                @break
                        @endswitch
                    </span>
                </div>
                @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="fas fa-home fa-3x text-muted"></i>
                </div>
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ $offer->product->getTranslatedTitle() }}</h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-map-marker-alt ms-1"></i>
                        {{ Str::limit($offer->product->address, 50) }}
                    </p>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">السعر:</span>
                            <strong class="text-primary h5 mb-0">{{ number_format($offer->price, 2) }} ر.س</strong>
                        </div>
                        
                        @if($offer->deposit_amount > 0)
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">العربون:</span>
                            <span class="text-warning">{{ number_format($offer->deposit_amount, 2) }} ر.س</span>
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-building ms-1"></i>
                            {{ $offer->facility->name }}
                        </small>
                        <br>
                        <small class="text-muted">
                            <i class="fas fa-clock ms-1"></i>
                            {{ $offer->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <div class="d-grid gap-2">
                        <a href="{{ route('client.financial.offer-details', $offer->id) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-eye ms-1"></i>
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- عرض القائمة (مخفي افتراضياً) -->
    <div class="d-none" id="listContainer">
        @foreach($offers as $offer)
        <div class="card mb-3 offer-card">
            <div class="row g-0">
                <div class="col-md-3">
                    @if($offer->product->gallery && $offer->product->gallery->count() > 0)
                    <img src="{{ $offer->product->gallery->first()->image_url }}" 
                         class="img-fluid rounded-start h-100" alt="{{ $offer->product->getTranslatedTitle() }}"
                         style="object-fit: cover; min-height: 200px;">
                    @else
                    <div class="bg-light d-flex align-items-center justify-content-center h-100 rounded-start">
                        <i class="fas fa-home fa-3x text-muted"></i>
                    </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">{{ $offer->product->getTranslatedTitle() }}</h5>
                                <p class="card-text">
                                    <i class="fas fa-map-marker-alt ms-1 text-muted"></i>
                                    {{ $offer->product->address }}
                                </p>
                            </div>
                            <div class="text-end">
                                @if($offer->is_featured)
                                <span class="badge bg-warning mb-2">
                                    <i class="fas fa-star"></i> مميز
                                </span>
                                <br>
                                @endif
                                @switch($offer->offer_type)
                                    @case('sale')
                                        <span class="badge bg-success">للبيع</span>
                                        @break
                                    @case('rent_monthly')
                                        <span class="badge bg-primary">إيجار شهري</span>
                                        @break
                                    @case('rent_yearly')
                                        <span class="badge bg-info">إيجار سنوي</span>
                                        @break
                                    @case('rent_daily')
                                        <span class="badge bg-secondary">إيجار يومي</span>
                                        @break
                                @endswitch
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong class="text-primary h5">{{ number_format($offer->price, 2) }} ر.س</strong>
                                </div>
                                @if($offer->deposit_amount > 0)
                                <div class="mb-2">
                                    <span class="text-muted">العربون: </span>
                                    <span class="text-warning">{{ number_format($offer->deposit_amount, 2) }} ر.س</span>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="fas fa-building ms-1"></i>
                                        {{ $offer->facility->name }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-clock ms-1"></i>
                                        {{ $offer->created_at->diffForHumans() }}
                                    </small>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('client.financial.offer-details', $offer->id) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-eye ms-1"></i>
                                عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Pagination -->
@if($offers->hasPages())
<div class="d-flex justify-content-center">
    {{ $offers->appends(request()->query())->links() }}
</div>
@endif

@else
<div class="empty-state">
    <i class="fas fa-search"></i>
    <h4>لا توجد عروض متاحة</h4>
    <p>لم يتم العثور على عروض تطابق معايير البحث المحددة.</p>
    <a href="{{ route('client.financial.offers') }}" class="btn btn-primary">
        <i class="fas fa-refresh"></i> عرض جميع العروض
    </a>
</div>
@endif
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // تبديل طريقة العرض بين الشبكة والقائمة
    $('input[name="view"]').on('change', function() {
        if ($(this).attr('id') === 'gridView') {
            $('#listContainer').addClass('d-none');
            $('#gridContainer').removeClass('d-none');
        } else {
            $('#gridContainer').addClass('d-none');
            $('#listContainer').removeClass('d-none');
        }
    });

    // تطبيق الفلاتر تلقائياً
    $('#filterForm select, #filterForm input[type="number"]').on('change', function() {
        $('#filterForm').submit();
    });

    // تأخير للبحث النصي
    let searchTimeout;
    $('#filterForm input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            $('#filterForm').submit();
        }, 1000);
    });

    // إضافة رسوم متحركة للبطاقات
    $('.offer-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('animate-fade-in-up');
    });

    // تحسين تجربة المستخدم - smooth scroll للفلاتر
    $('#filtersCollapse').on('show.bs.collapse', function() {
        setTimeout(() => {
            $('html, body').animate({
                scrollTop: $(this).offset().top - 100
            }, 500);
        }, 300);
    });

    // إظهار/إخفاء زر الفلاتر حسب الحاجة
    const hasActiveFilters = {{ request()->hasAny(['search', 'facility_id', 'type', 'min_price', 'max_price']) ? 'true' : 'false' }};
    if (hasActiveFilters) {
        $('#filtersCollapse').addClass('show');
    }

    // hover effects للبطاقات
    $('.offer-card .card').hover(
        function() {
            $(this).addClass('shadow-lg');
        },
        function() {
            $(this).removeClass('shadow-lg');
        }
    );
});
</script>
@endsection
