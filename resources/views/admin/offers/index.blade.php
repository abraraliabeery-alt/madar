@extends('admin.layouts.app')

@section('title', __('admin.offers'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('admin.offers') }}</h2>
    <div>
        <a href="{{ route('admin.offers.export') }}" class="btn btn-outline-secondary">{{ __('admin.export') }}</a>
        <a href="{{ route('admin.offers.create') }}" class="btn btn-primary">{{ __('admin.add_offer') }}</a>
    </div>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<form method="GET" class="card p-3 mb-4 row g-2 flex-row">
    <div class="col-md-3"><select name="facility_id" class="form-select"><option value="">{{ __('admin.all_facilities') }}</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select></div>
    <div class="col-md-2"><select name="type" class="form-select"><option value="">{{ __('admin.all_types') }}</option>@foreach(['sale','rent_monthly','rent_yearly','rent_daily'] as $t)<option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>@endforeach</select></div>
    <div class="col-md-2"><select name="status" class="form-select"><option value="">{{ __('admin.all_statuses') }}</option><option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option><option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option><option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>{{ __('admin.expired') }}</option></select></div>
    <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="{{ __('admin.search') }}" value="{{ request('search') }}"></div>
    <div class="col-md-2"><button class="btn btn-primary w-100">{{ __('admin.filter') }}</button></div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>#</th><th>{{ __('admin.product') }}</th><th>{{ __('admin.facility') }}</th><th>{{ __('admin.type') }}</th><th>{{ __('admin.price') }}</th><th>{{ __('admin.status_label') }}</th><th></th></tr></thead>
            <tbody>
                @forelse($offers as $offer)
                    <tr>
                        <td>{{ $offer->id }}</td>
                        <td>{{ optional($offer->product)->getTranslatedTitle() ?: '-' }}</td>
                        <td>{{ optional($offer->facility)->name ?? '-' }}</td>
                        <td>{{ $offer->offer_type }}</td>
                        <td>{{ number_format($offer->price ?? 0, 2) }}</td>
                        <td><span class="badge bg-{{ $offer->is_active ? 'success' : 'secondary' }}">{{ $offer->is_active ? __('admin.active') : __('admin.inactive') }}</span></td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.offers.show', $offer) }}" class="btn btn-sm btn-outline-primary">{{ __('admin.view') }}</a>
                            <a href="{{ route('admin.offers.edit', $offer) }}" class="btn btn-sm btn-outline-secondary">{{ __('admin.edit') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted p-4">{{ __('admin.no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($offers, 'links'))<div class="card-footer">{{ $offers->links() }}</div>@endif
</div>
@endsection
