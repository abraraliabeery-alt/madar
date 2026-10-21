@extends('admin.layouts.app')

@section('title', __('admin.edit_offer'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('admin.edit_offer') }} #{{ $offer->id }}</h2>
    <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary">{{ __('admin.back') }}</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.offers.update', $offer) }}" class="card-body row g-3">
        @csrf @method('PUT')
        <div class="col-md-6">
            <label class="form-label">{{ __('admin.product') }} *</label>
            <select name="product_id" class="form-select" required>
                @foreach($products as $p)<option value="{{ $p->id }}" {{ old('product_id', $offer->product_id) == $p->id ? 'selected' : '' }}>{{ $p->getTranslatedTitle() }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('admin.facility') }} *</label>
            <select name="facility_id" class="form-select" required>
                @foreach($facilities as $f)<option value="{{ $f->id }}" {{ old('facility_id', $offer->facility_id) == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.type') }} *</label>
            <select name="offer_type" class="form-select" required>
                @foreach($offerTypes as $k => $label)<option value="{{ $k }}" {{ old('offer_type', $offer->offer_type) == $k ? 'selected' : '' }}>{{ $label }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.price') }} *</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $offer->price) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.deposit') }}</label>
            <input type="number" step="0.01" name="deposit_amount" class="form-control" value="{{ old('deposit_amount', $offer->deposit_amount) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.commission_rate') }}</label>
            <input type="number" step="0.01" min="0" max="1" name="commission_rate" class="form-control" value="{{ old('commission_rate', $offer->commission_rate) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.valid_from') }}</label>
            <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from', optional($offer->valid_from)->format('Y-m-d')) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.valid_to') }}</label>
            <input type="date" name="valid_to" class="form-control" value="{{ old('valid_to', optional($offer->valid_to)->format('Y-m-d')) }}">
        </div>
        <div class="col-12">
            <label class="form-label">{{ __('admin.terms_conditions') }}</label>
            <textarea name="terms_conditions" class="form-control" rows="3">{{ old('terms_conditions', $offer->terms_conditions) }}</textarea>
        </div>
        <div class="col-12 form-check ms-2">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ $offer->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">{{ __('admin.active') }}</label>
            <input type="checkbox" name="is_featured" value="1" class="form-check-input ms-3" id="is_featured" {{ $offer->is_featured ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">{{ __('admin.featured') }}</label>
        </div>
        <div class="col-12"><button class="btn btn-primary">{{ __('admin.save') }}</button></div>
    </form>
</div>
@endsection
