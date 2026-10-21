@extends('admin.layouts.app')

@section('title', __('admin.offer') . ' #' . $offer->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('admin.offer') }} #{{ $offer->id }}</h2>
    <div>
        <a href="{{ route('admin.offers.edit', $offer) }}" class="btn btn-outline-secondary">{{ __('admin.edit') }}</a>
        <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary">{{ __('admin.back') }}</a>
    </div>
</div>

<div class="card p-4">
    <div class="row g-3">
        <div class="col-md-4"><small class="text-muted">{{ __('admin.product') }}</small><p class="fw-bold">{{ optional($offer->product)->getTranslatedTitle() ?: '-' }}</p></div>
        <div class="col-md-4"><small class="text-muted">{{ __('admin.facility') }}</small><p class="fw-bold">{{ optional($offer->facility)->name ?? '-' }}</p></div>
        <div class="col-md-4"><small class="text-muted">{{ __('admin.type') }}</small><p class="fw-bold">{{ $offer->offer_type }}</p></div>
        <div class="col-md-4"><small class="text-muted">{{ __('admin.price') }}</small><p class="fw-bold">{{ number_format($offer->price ?? 0, 2) }}</p></div>
        <div class="col-md-4"><small class="text-muted">{{ __('admin.deposit') }}</small><p class="fw-bold">{{ number_format($offer->deposit_amount ?? 0, 2) }}</p></div>
        <div class="col-md-4"><small class="text-muted">{{ __('admin.status_label') }}</small><p class="fw-bold">{{ $offer->is_active ? __('admin.active') : __('admin.inactive') }}</p></div>
        <div class="col-md-4"><small class="text-muted">{{ __('admin.valid_from') }}</small><p class="fw-bold">{{ $offer->valid_from }}</p></div>
        <div class="col-md-4"><small class="text-muted">{{ __('admin.valid_to') }}</small><p class="fw-bold">{{ $offer->valid_to }}</p></div>
    </div>
    @if($offer->offer_description)<hr><p>{{ $offer->offer_description }}</p>@endif
</div>
@endsection
