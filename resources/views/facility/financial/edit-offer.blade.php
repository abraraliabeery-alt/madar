@extends('facility.financial.layout')

@section('title', __('financial.edit_offer'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-0">{{ __('financial.edit_offer') }}</h1>
        <p class="text-muted mb-0">{{ $offer->product?->name ?? '' }}</p>
    </div>
    <a href="{{ route('facility.financial.offers') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right"></i> {{ __('financial.back') }}
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('facility.financial.update-offer', $offer->id) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('financial.offer_type') }} <span class="text-danger">*</span></label>
                    <select name="offer_type" class="form-select" required>
                        @foreach(['sale', 'monthly_rent', 'yearly_rent', 'daily_rent'] as $type)
                            <option value="{{ $type }}" {{ old('offer_type', $offer->offer_type) == $type ? 'selected' : '' }}>
                                {{ __('financial.offer_type_' . $type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('financial.status') }} <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', $offer->status) == 'active' ? 'selected' : '' }}>{{ __('financial.active') }}</option>
                        <option value="inactive" {{ old('status', $offer->status) == 'inactive' ? 'selected' : '' }}>{{ __('financial.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('financial.price') }} <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $offer->price) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('financial.deposit_amount') }}</label>
                    <input type="number" step="0.01" min="0" name="deposit_amount" class="form-control" value="{{ old('deposit_amount', $offer->deposit_amount) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('financial.commission_rate') }} (%) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" max="100" name="commission_rate" class="form-control" value="{{ old('commission_rate', $offer->commission_rate) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('financial.valid_from') }} <span class="text-danger">*</span></label>
                    <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from', $offer->valid_from ? \Carbon\Carbon::parse($offer->valid_from)->format('Y-m-d') : '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('financial.valid_to') }}</label>
                    <input type="date" name="valid_to" class="form-control" value="{{ old('valid_to', $offer->valid_to ? \Carbon\Carbon::parse($offer->valid_to)->format('Y-m-d') : '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('financial.terms_conditions') }}</label>
                    <textarea name="terms_conditions" rows="4" class="form-control">{{ old('terms_conditions', $offer->terms_conditions) }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check"></i> {{ __('financial.save') }}
                    </button>
                    <a href="{{ route('facility.financial.offers') }}" class="btn btn-outline-secondary">{{ __('financial.back') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
