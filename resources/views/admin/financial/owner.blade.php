@extends('admin.financial.layout')

@section('title', __('financial.owner_report'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.owner_report') }}</h2>
</div>

<form method="GET" class="card p-3 mb-4 row g-2 flex-row">
    <div class="col-md-4"><input type="number" name="owner_id" class="form-control" placeholder="{{ __('financial.owner_id') }}" value="{{ request('owner_id') }}" required></div>
    <div class="col-md-4"><select name="facility_id" class="form-select"><option value="">{{ __('financial.all_facilities') }}</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><button class="btn btn-primary w-100">{{ __('financial.view_report') }}</button></div>
</form>

@isset($report)
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_contracts') }}</small><h4>{{ $report['total_contracts'] }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_amount') }}</small><h4>{{ number_format($report['total_value'], 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.total_commissions') }}</small><h4>{{ number_format($report['total_commission'], 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3"><small class="text-muted">{{ __('financial.net_income') }}</small><h4>{{ number_format($report['net_amount'], 2) }}</h4></div></div>
</div>

<div class="card">
    <div class="card-header">{{ __('financial.contracts') }}</div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>#</th><th>{{ __('financial.product') }}</th><th>{{ __('financial.client') }}</th><th>{{ __('financial.amount') }}</th></tr></thead>
            <tbody>
                @foreach($report['contracts'] as $c)
                    <tr><td>{{ $c->id }}</td><td>{{ optional($c->product)->getTranslatedTitle() ?: '-' }}</td><td>{{ optional($c->user)->name ?? '-' }}</td><td>{{ number_format($c->total_amount ?? 0, 2) }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endisset
@endsection
