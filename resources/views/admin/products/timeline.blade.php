@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">الخط الزمني للمنتج</h5>
        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>رجوع
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="get" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">نوع الحدث</label>
                    <select name="type" class="form-select">
                        <option value="">الكل</option>
                        <option value="product_created" {{ request('type')=='product_created'?'selected':'' }}>إنشاء منتج</option>
                        <option value="product_updated" {{ request('type')=='product_updated'?'selected':'' }}>تحديث منتج</option>
                        <option value="status_changed" {{ request('type')=='status_changed'?'selected':'' }}>تغيير حالة</option>
                        <option value="offer_created" {{ request('type')=='offer_created'?'selected':'' }}>إنشاء عرض</option>
                        <option value="offer_updated" {{ request('type')=='offer_updated'?'selected':'' }}>تحديث عرض</option>
                        <option value="booking_created" {{ request('type')=='booking_created'?'selected':'' }}>إنشاء حجز</option>
                        <option value="booking_updated" {{ request('type')=='booking_updated'?'selected':'' }}>تحديث حجز</option>
                        <option value="contract_created" {{ request('type')=='contract_created'?'selected':'' }}>إنشاء عقد</option>
                        <option value="contract_updated" {{ request('type')=='contract_updated'?'selected':'' }}>تحديث عقد</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">المصدر</label>
                    <select name="source" class="form-select">
                        <option value="">الكل</option>
                        <option value="product" {{ request('source')=='product'?'selected':'' }}>منتج</option>
                        <option value="status" {{ request('source')=='status'?'selected':'' }}>حالة</option>
                        <option value="offer" {{ request('source')=='offer'?'selected':'' }}>عرض</option>
                        <option value="booking" {{ request('source')=='booking'?'selected':'' }}>حجز</option>
                        <option value="contract" {{ request('source')=='contract'?'selected':'' }}>عقد</option>
                        <option value="comment" {{ request('source')=='comment'?'selected':'' }}>تعليق</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control" />
                </div>
                <div class="col-md-2">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control" />
                </div>
                <div class="col-12 d-flex gap-2 mt-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-2"></i>تطبيق</button>
                    <a href="{{ route('admin.products.timeline', $product) }}" class="btn btn-light">إزالة الفلاتر</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if(empty($events))
                <div class="text-center text-muted py-5">لا توجد أحداث لعرضها</div>
            @else
                <div class="timeline">
                    @foreach($events as $event)
                        <div class="timeline-item d-flex align-items-start mb-4">
                            <div class="timeline-badge me-3">
                                @php
                                    $color = match($event['type']){
                                        'product_created' => 'primary',
                                        'product_updated' => 'info',
                                        'status_changed' => 'secondary',
                                        'offer_created' => 'success',
                                        'offer_updated' => 'success',
                                        'booking_created' => 'warning',
                                        'booking_updated' => 'warning',
                                        'contract_created' => 'dark',
                                        'contract_updated' => 'dark',
                                        default => 'light'
                                    };
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ $event['source'] }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">{{ $event['title'] }}</h6>
                                    <small class="text-muted">{{ optional($event['date'])->format('Y-m-d H:i') }}</small>
                                </div>
                                @if(!empty($event['description']))
                                    <div class="text-muted mb-2">{{ $event['description'] }}</div>
                                @endif
                                <div class="d-flex gap-2 align-items-center">
                                    @if(!empty($event['actor_name']))
                                        <span class="badge bg-light text-dark">{{ $event['actor_name'] }}</span>
                                    @endif
                                    @if(!empty($event['link']))
                                        <a href="{{ $event['link'] }}" class="btn btn-sm btn-outline-primary">تفاصيل</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline { position: relative; }
.timeline::before { content: ''; position: absolute; top: 0; bottom: 0; right: 11px; width: 2px; background: #e9ecef; }
.timeline-item { position: relative; }
.timeline-item::before { content: ''; position: absolute; right: 6px; top: 8px; width: 12px; height: 12px; background: #fff; border: 2px solid #0d6efd; border-radius: 50%; }
.timeline-badge { min-width: 72px; text-align: center; }
</style>
@endpush
