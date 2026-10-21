@extends('admin.layouts.app')

@section('title', __('admin.add_offer'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('admin.add_offer') }}</h2>
    <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary">{{ __('admin.back') }}</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.offers.store') }}" class="card-body row g-3">
        @csrf
        <div class="col-md-6">
            <label class="form-label">{{ __('admin.product') }} *</label>
            <select name="product_id" class="form-select" required>
                <option value="">{{ __('admin.choose') }}</option>
                @foreach($products as $p)<option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>{{ $p->getTranslatedTitle() }}</option>@endforeach
            </select>
            @error('product_id')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('admin.facility') }} *</label>
            <select name="facility_id" class="form-select" required>
                <option value="">{{ __('admin.choose') }}</option>
                @foreach($facilities as $f)<option value="{{ $f->id }}" {{ old('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach
            </select>
            @error('facility_id')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.type') }} *</label>
            <select name="offer_type" class="form-select" required>
                @foreach($offerTypes as $k => $label)<option value="{{ $k }}" {{ old('offer_type') == $k ? 'selected' : '' }}>{{ $label }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.price') }} *</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
            @error('price')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.deposit') }}</label>
            <input type="number" step="0.01" name="deposit_amount" class="form-control" value="{{ old('deposit_amount') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.commission_rate') }}</label>
            <input type="number" step="0.01" min="0" max="1" name="commission_rate" class="form-control" value="{{ old('commission_rate') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.valid_from') }}</label>
            <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('admin.valid_to') }}</label>
            <input type="date" name="valid_to" class="form-control" value="{{ old('valid_to') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('admin.offer_title') }}</label>
            <input type="text" name="offer_title" class="form-control" value="{{ old('offer_title') }}">
        </div>
        <div class="col-12">
            <label class="form-label">{{ __('admin.description') }}</label>
            <textarea name="offer_description" class="form-control" rows="3">{{ old('offer_description') }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label">{{ __('admin.terms_conditions') }}</label>
            <textarea name="terms_conditions" class="form-control" rows="3">{{ old('terms_conditions') }}</textarea>
        </div>
        <div class="col-12 form-check ms-2">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
            <label class="form-check-label" for="is_active">{{ __('admin.active') }}</label>
            <input type="checkbox" name="is_featured" value="1" class="form-check-input ms-3" id="is_featured">
            <label class="form-check-label" for="is_featured">{{ __('admin.featured') }}</label>
        </div>
        <div class="col-12"><button class="btn btn-primary">{{ __('admin.save') }}</button></div>
    </form>
</div>
@endsection
