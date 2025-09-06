@extends('layouts.client')

@section('title', $offer->product->getTranslatedTitle())

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <!-- صور المنتج -->
            <div class="card mb-4">
                <div class="card-body p-0">
                    @if($offer->product->image)
                        <img src="{{ asset('storage/' . $offer->product->image) }}" 
                             class="img-fluid w-100" style="height: 400px; object-fit: cover;" 
                             alt="صورة العقار">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height: 400px;">
                            <div class="text-center">
                                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                <p class="text-muted">لا توجد صورة متاحة</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- تفاصيل العرض -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">{{ $offer->product->getTranslatedTitle() }}</h3>
                    <div class="card-tools">
                        <span class="badge bg-{{ $offer->offer_type == 'sale' ? 'success' : 'info' }} fs-6">
                            @switch($offer->offer_type)
                                @case('sale') بيع @break
                                @case('rent_monthly') إيجار شهري @break
                                @case('rent_yearly') إيجار سنوي @break
                                @case('rent_daily') إيجار يومي @break
                            @endswitch
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>تفاصيل العقار</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>العنوان:</strong></td>
                                    <td>{{ $offer->product->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>الوصف:</strong></td>
                                    <td>{{ $offer->product->getTranslatedDescription() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>المنشأة:</strong></td>
                                    <td>{{ $offer->facility->name ?? 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>تفاصيل العرض</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>السعر:</strong></td>
                                    <td class="text-primary fs-5">{{ number_format($offer->price, 2) }} {{ $offer->currency }}</td>
                                </tr>
                                @if($offer->deposit_amount)
                                    <tr>
                                        <td><strong>العربون:</strong></td>
                                        <td>{{ number_format($offer->deposit_amount, 2) }} {{ $offer->currency }}</td>
                                    </tr>
                                @endif
                                @if($offer->commission_amount)
                                    <tr>
                                        <td><strong>العمولة:</strong></td>
                                        <td>{{ number_format($offer->commission_amount, 2) }} {{ $offer->currency }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>صالح من:</strong></td>
                                    <td>{{ $offer->valid_from ? $offer->valid_from->format('Y-m-d') : 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>صالح حتى:</strong></td>
                                    <td>{{ $offer->valid_to ? $offer->valid_to->format('Y-m-d') : 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($offer->getTranslatedTerms())
                        <div class="mt-4">
                            <h5>الشروط والأحكام</h5>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($offer->getTranslatedTerms())) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- العروض المشابهة -->
            @if($similarOffers->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">عروض مشابهة</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($similarOffers as $similarOffer)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        @if($similarOffer->product->image)
                                            <img src="{{ asset('storage/' . $similarOffer->product->image) }}" 
                                                 class="card-img-top" height="150" style="object-fit: cover;" alt="صورة العقار">
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $similarOffer->product->getTranslatedTitle() }}</h6>
                                            <p class="card-text text-muted small">{{ $similarOffer->product->address }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-primary fw-bold">
                                                    {{ number_format($similarOffer->price, 2) }} {{ $similarOffer->currency }}
                                                </span>
                                                <a href="{{ route('client.offers.show', $similarOffer) }}" 
                                                   class="btn btn-sm btn-outline-primary">عرض</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- الشريط الجانبي -->
        <div class="col-md-4">
            <!-- معلومات الاتصال -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">معلومات الاتصال</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="fas fa-phone"></i> طلب معلومات
                        </button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#visitModal">
                            <i class="fas fa-calendar"></i> حجز موعد زيارة
                        </button>
                        @auth
                            <button class="btn btn-outline-danger add-to-favorites" data-offer-id="{{ $offer->id }}">
                                <i class="fas fa-heart"></i> إضافة للمفضلة
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-danger">
                                <i class="fas fa-heart"></i> إضافة للمفضلة
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- معلومات المنشأة -->
            @if($offer->facility)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">المنشأة</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            @if($offer->facility->logo)
                                <img src="{{ asset('storage/' . $offer->facility->logo) }}" 
                                     class="rounded-circle mb-3" width="80" height="80" alt="شعار المنشأة">
                            @endif
                            <h6>{{ $offer->facility->name }}</h6>
                            <p class="text-muted small">{{ $offer->facility->description ?? 'لا يوجد وصف' }}</p>
                            <a href="{{ route('facilities.show', $offer->facility) }}" 
                               class="btn btn-sm btn-outline-primary">عرض المنشأة</a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- إحصائيات العرض -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">إحصائيات العرض</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-muted">المشاهدات</h6>
                                <h4 class="text-primary">{{ $offer->product->views_count ?? 0 }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted">التقييم</h6>
                            <h4 class="text-warning">{{ $offer->product->rating ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal طلب معلومات -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">طلب معلومات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="contactForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="contact_name" class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="contact_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="contact_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" id="contact_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_message" class="form-label">الرسالة</label>
                        <textarea name="message" id="contact_message" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إرسال</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal حجز موعد زيارة -->
<div class="modal fade" id="visitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">حجز موعد زيارة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="visitForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="visit_date" class="form-label">تاريخ الزيارة <span class="text-danger">*</span></label>
                        <input type="date" name="visit_date" id="visit_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="visit_time" class="form-label">وقت الزيارة <span class="text-danger">*</span></label>
                        <input type="time" name="visit_time" id="visit_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="visit_notes" class="form-label">ملاحظات</label>
                        <textarea name="notes" id="visit_notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">حجز الموعد</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // إضافة للمفضلة
    document.querySelector('.add-to-favorites')?.addEventListener('click', function() {
        const offerId = this.dataset.offerId;
        
        fetch(`/client/offers/${offerId}/add-to-favorites`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.innerHTML = '<i class="fas fa-heart text-danger"></i> تمت الإضافة';
                this.classList.remove('btn-outline-danger');
                this.classList.add('btn-danger');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // طلب معلومات
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(`/client/offers/{{ $offer->id }}/request-info`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إرسال طلبك بنجاح. سنتواصل معك قريباً.');
                bootstrap.Modal.getInstance(document.getElementById('contactModal')).hide();
                this.reset();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // حجز موعد زيارة
    document.getElementById('visitForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(`/client/offers/{{ $offer->id }}/book-visit`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم حجز موعد الزيارة بنجاح');
                bootstrap.Modal.getInstance(document.getElementById('visitModal')).hide();
                this.reset();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // تعيين التاريخ الحالي كافتراضي
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('visit_date').value = today;
    });
</script>
@endpush
