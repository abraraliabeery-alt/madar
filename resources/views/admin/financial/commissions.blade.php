@extends('admin.financial.layout')

@section('title', __('financial.commissions_report'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.commissions_report') }}</h2>
</div>

<form method="GET" class="card p-3 mb-4 row g-2 flex-row">
    <div class="col-md-3"><select name="facility_id" class="form-select"><option value="">{{ __('financial.all_facilities') }}</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"></div>
    <div class="col-md-3"><input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"></div>
    <div class="col-md-3"><button class="btn btn-primary w-100">{{ __('financial.filter') }}</button></div>
</form>

<div class="card p-3 mb-4"><small class="text-muted">{{ __('financial.total_commissions') }}</small><h3>{{ number_format($report['total_commission'] ?? 0, 2) }}</h3></div>

<div class="card">
    <div class="card-header">{{ __('financial.by_contract') }}</div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>#</th><th>{{ __('financial.contract') }}</th><th>{{ __('financial.amount') }}</th></tr></thead>
            <tbody>
                @forelse(($report['by_contract'] ?? []) as $row)
                    <tr>
                        <td>{{ $row['contract_id'] }}</td>
                        <td>{{ optional($row['contract'])->contract_number ?? $row['contract_id'] }}</td>
                        <td>{{ number_format($row['amount'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted p-4">{{ __('financial.no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
