@extends('layouts.client')

@section('title', 'العروض المتاحة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">العروض المتاحة</h3>
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
                            <input type="number" name="min_price" class="form-control" placeholder="الحد الأدنى للسعر" value="{{ request('min_price') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="max_price" class="form-control" placeholder="الحد الأقصى للسعر" value="{{ request('max_price') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">بحث</button>
                        </div>
                    </form>

                    @if($offers->count() > 0)
                        <div class="row">
                            @foreach($offers as $offer)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 offer-card">
                                        @if($offer->product->image)
                                            <img src="{{ asset('storage/' . $offer->product->image) }}" 
                                                 class="card-img-top" height="200" style="object-fit: cover;" alt="صورة العقار">
                                        @endif
                                        
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title">{{ $offer->product->getTranslatedTitle() }}</h5>
                                                <span class="badge bg-{{ $offer->offer_type == 'sale' ? 'success' : 'info' }}">
                                                    @switch($offer->offer_type)
                                                        @case('sale') بيع @break
                                                        @case('rent_monthly') إيجار شهري @break
                                                        @case('rent_yearly') إيجار سنوي @break
                                                        @case('rent_daily') إيجار يومي @break
                                                    @endswitch
                                                </span>
                                            </div>
                                            
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-map-marker-alt"></i> {{ $offer->product->address }}
                                            </p>
                                            
                                            <div class="mb-3">
                                                <h4 class="text-primary mb-0">
                                                    {{ number_format($offer->price, 2) }} {{ $offer->currency }}
                                                </h4>
                                                @if($offer->deposit_amount)
                                                    <small class="text-muted">
                                                        العربون: {{ number_format($offer->deposit_amount, 2) }} {{ $offer->currency }}
                                                    </small>
                                                @endif
                                            </div>

                                            <div class="mt-auto">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="fas fa-building"></i> {{ $offer->facility->name ?? 'غير محدد' }}
                                                    </small>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('client.offers.show', $offer) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> عرض
                                                        </a>
                                                        @auth
                                                            <button class="btn btn-sm btn-outline-success add-to-favorites" 
                                                                    data-offer-id="{{ $offer->id }}">
                                                                <i class="fas fa-heart"></i>
                                                            </button>
                                                        @endauth
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $offers->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد عروض</h5>
                            <p class="text-muted">جرب تغيير معايير البحث</p>
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
    // إضافة للمفضلة
    document.querySelectorAll('.add-to-favorites').forEach(button => {
        button.addEventListener('click', function() {
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
                    this.innerHTML = '<i class="fas fa-heart text-danger"></i>';
                    this.classList.remove('btn-outline-success');
                    this.classList.add('btn-success');
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });

    // Auto-submit form on filter change
    document.querySelectorAll('select[name="type"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush
