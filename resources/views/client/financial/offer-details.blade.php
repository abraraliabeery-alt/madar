@extends('client.financial.layout')

@section('title', 'تفاصيل العرض - ' . $offer->product->getTranslatedTitle())

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('client.financial.dashboard') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('client.financial.offers') }}">العروض المتاحة</a></li>
                <li class="breadcrumb-item active">تفاصيل العرض</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- تفاصيل العرض الرئيسية -->
    <div class="col-lg-8 mb-4">
        <!-- صور العقار -->
        <div class="card mb-4">
            @if($offer->product->gallery && $offer->product->gallery->count() > 0)
            <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach($offer->product->gallery as $index => $image)
                    <button type="button" data-bs-target="#propertyCarousel" data-bs-slide-to="{{ $index }}" 
                            class="{{ $index === 0 ? 'active' : '' }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach($offer->product->gallery as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $image->image_url }}" class="d-block w-100" alt="صورة العقار"
                             style="height: 400px; object-fit: cover;">
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
            @else
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                <div class="text-center">
                    <i class="fas fa-home fa-5x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد صور متاحة</p>
                </div>
            </div>
            @endif
        </div>

        <!-- معلومات العقار -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">{{ $offer->product->getTranslatedTitle() }}</h4>
                <div class="d-flex gap-2 mt-2">
                    @if($offer->is_featured)
                    <span class="badge bg-warning">
                        <i class="fas fa-star"></i> مميز
                    </span>
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
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>معلومات أساسية</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>الموقع:</strong></td>
                                <td>{{ $offer->product->address }}</td>
                            </tr>
                            <tr>
                                <td><strong>الفئة:</strong></td>
                                <td>{{ $offer->product->category->getTranslatedName() ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <td><strong>المؤسسة:</strong></td>
                                <td>{{ $offer->facility->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>تاريخ النشر:</strong></td>
                                <td>{{ $offer->created_at->format('Y/m/d') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>معلومات مالية</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>السعر:</strong></td>
                                <td class="text-primary fw-bold">{{ number_format($offer->price, 2) }} ر.س</td>
                            </tr>
                            @if($offer->deposit_amount > 0)
                            <tr>
                                <td><strong>العربون:</strong></td>
                                <td class="text-warning">{{ number_format($offer->deposit_amount, 2) }} ر.س</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>العمولة:</strong></td>
                                <td>{{ number_format($offer->commission_rate * 100, 2) }}%</td>
                            </tr>
                            @if($offer->valid_from || $offer->valid_to)
                            <tr>
                                <td><strong>فترة العرض:</strong></td>
                                <td>
                                    @if($offer->valid_from)
                                        من {{ $offer->valid_from->format('Y/m/d') }}
                                    @endif
                                    @if($offer->valid_to)
                                        إلى {{ $offer->valid_to->format('Y/m/d') }}
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if($offer->product->description)
                <div class="mt-4">
                    <h6>الوصف</h6>
                    <p class="text-muted">{{ $offer->product->description }}</p>
                </div>
                @endif

                @if($offer->terms_conditions)
                <div class="mt-4">
                    <h6>الشروط والأحكام</h6>
                    <div class="alert alert-info">
                        {{ $offer->terms_conditions }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- المميزات والخصائص -->
        @if($offer->product->features && $offer->product->features->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">المميزات</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($offer->product->features as $feature)
                    <div class="col-md-6 mb-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success ms-2"></i>
                            <span>{{ $feature->getTranslatedName() }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- اللوحة الجانبية -->
    <div class="col-lg-4">
        <!-- بطاقة الطلب -->
        <div class="card mb-4 sticky-top" style="top: 20px;">
            <div class="card-header text-center">
                <h5 class="mb-0">طلب العقد</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h3 class="text-primary">{{ number_format($offer->price, 2) }} ر.س</h3>
                    @if($offer->deposit_amount > 0)
                    <p class="text-muted mb-0">+ عربون {{ number_format($offer->deposit_amount, 2) }} ر.س</p>
                    @endif
                </div>

                @if($existingContract)
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle ms-2"></i>
                    لديك عقد سابق لهذا العقار
                    <div class="mt-2">
                        <a href="{{ route('client.financial.contract-details', $existingContract->id) }}" 
                           class="btn btn-sm btn-outline-primary">
                            عرض العقد
                        </a>
                    </div>
                </div>
                @else
                <form method="POST" action="{{ route('client.financial.request-contract') }}" id="contractForm">
                    @csrf
                    <input type="hidden" name="offer_id" value="{{ $offer->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">تاريخ البداية المفضل</label>
                        <input type="date" name="preferred_start_date" class="form-control" 
                               min="{{ now()->addDay()->format('Y-m-d') }}">
                        <small class="text-muted">اختياري - اتركه فارغاً للبدء فوراً</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="أي ملاحظات أو طلبات خاصة..."></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" onclick="return confirmRequest()">
                            <i class="fas fa-file-contract ms-2"></i>
                            طلب العقد
                        </button>
                        <small class="text-muted text-center">
                            سيتم إرسال طلبك للمؤسسة للمراجعة
                        </small>
                    </div>
                </form>
                @endif
            </div>
        </div>

        <!-- معلومات المؤسسة -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">معلومات المؤسسة</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary rounded-circle p-3 text-white d-inline-flex">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                    <h6 class="mt-2 mb-0">{{ $offer->facility->name }}</h6>
                </div>
                
                @if($offer->facility->description)
                <p class="text-muted small">{{ Str::limit($offer->facility->description, 100) }}</p>
                @endif

                <div class="d-grid">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#contactModal">
                        <i class="fas fa-phone ms-2"></i>
                        تواصل مع المؤسسة
                    </button>
                </div>
            </div>
        </div>

        <!-- معلومات إضافية -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">معلومات مهمة</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-shield-alt text-success ms-2"></i>
                    <small>ضمان أمان المعاملة</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-file-contract text-info ms-2"></i>
                    <small>عقد موثق قانونياً</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-headset text-primary ms-2"></i>
                    <small>دعم فني على مدار الساعة</small>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-undo text-warning ms-2"></i>
                    <small>إمكانية الإلغاء قبل التأكيد</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- العروض المشابهة -->
@if($similarOffers->count() > 0)
<div class="row mt-5">
    <div class="col-12">
        <h4 class="mb-4">عروض مشابهة</h4>
        <div class="row">
            @foreach($similarOffers as $similarOffer)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    @if($similarOffer->product->gallery && $similarOffer->product->gallery->count() > 0)
                    <img src="{{ $similarOffer->product->gallery->first()->image_url }}" 
                         class="card-img-top" alt="{{ $similarOffer->product->getTranslatedTitle() }}"
                         style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-home fa-3x text-muted"></i>
                    </div>
                    @endif

                    <div class="card-body">
                        <h6 class="card-title">{{ Str::limit($similarOffer->product->getTranslatedTitle(), 50) }}</h6>
                        <p class="card-text text-muted small">
                            <i class="fas fa-map-marker-alt ms-1"></i>
                            {{ Str::limit($similarOffer->product->address, 40) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-primary">{{ number_format($similarOffer->price, 0) }} ر.س</strong>
                            @switch($similarOffer->offer_type)
                                @case('sale')
                                    <span class="badge bg-success">للبيع</span>
                                    @break
                                @case('rent_monthly')
                                    <span class="badge bg-primary">شهري</span>
                                    @break
                                @case('rent_yearly')
                                    <span class="badge bg-info">سنوي</span>
                                    @break
                                @case('rent_daily')
                                    <span class="badge bg-secondary">يومي</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('client.financial.offer-details', $similarOffer->id) }}" 
                           class="btn btn-outline-primary btn-sm w-100">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- مودال تواصل مع المؤسسة -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تواصل مع {{ $offer->facility->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>يمكنك التواصل مع المؤسسة للاستفسار عن هذا العرض:</p>
                
                <div class="mb-3">
                    <strong>اسم المؤسسة:</strong>
                    <p>{{ $offer->facility->name }}</p>
                </div>

                @if($offer->facility->email)
                <div class="mb-3">
                    <strong>البريد الإلكتروني:</strong>
                    <p>
                        <a href="mailto:{{ $offer->facility->email }}?subject=استفسار عن العرض {{ $offer->product->getTranslatedTitle() }}">
                            {{ $offer->facility->email }}
                        </a>
                    </p>
                </div>
                @endif

                @if($offer->facility->phone_number)
                <div class="mb-3">
                    <strong>رقم الهاتف:</strong>
                    <p>
                        <a href="tel:{{ $offer->facility->phone_number }}">
                            {{ $offer->facility->phone_number }}
                        </a>
                    </p>
                </div>
                @endif

                <div class="alert alert-info">
                    <i class="fas fa-info-circle ms-2"></i>
                    يمكنك أيضاً طلب العقد مباشرة وسيتم التواصل معك من قبل المؤسسة.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmRequest() {
        return confirm('هل أنت متأكد من طلب هذا العقد؟\n\nسيتم إرسال طلبك للمؤسسة للمراجعة والموافقة.');
    }

    $(document).ready(function() {
        // تفعيل tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // تحسين تجربة النموذج
        $('#contractForm').on('submit', function() {
            showLoading();
        });

        // sticky sidebar
        const sidebar = $('.sticky-top');
        if (sidebar.length) {
            $(window).scroll(function() {
                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();
                const sidebarHeight = sidebar.height();
                
                if (scrollTop + windowHeight + sidebarHeight >= documentHeight) {
                    sidebar.css('top', documentHeight - scrollTop - sidebarHeight - 20);
                } else {
                    sidebar.css('top', '20px');
                }
            });
        }

        // تحسين carousel
        $('.carousel').carousel({
            interval: 5000,
            ride: 'carousel'
        });

        // lazy loading للصور
        $('img').attr('loading', 'lazy');

        // إضافة رسوم متحركة
        $('.card').addClass('animate-fade-in-up');
        $('.card').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });
    });
</script>
@endsection
