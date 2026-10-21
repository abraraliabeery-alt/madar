@extends('admin.financial.layout')

@section('title', __('financial.facilities_report'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.facilities_report') }}</h2>
</div>

<form method="GET" class="card p-3 mb-4 row g-2 flex-row">
    <div class="col-md-4"><input type="date" name="start_date" class="form-control" value="{{ request('start_date', optional($startDate)->format('Y-m-d')) }}"></div>
    <div class="col-md-4"><input type="date" name="end_date" class="form-control" value="{{ request('end_date', optional($endDate)->format('Y-m-d')) }}"></div>
    <div class="col-md-4"><button class="btn btn-primary w-100">{{ __('financial.filter') }}</button></div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>{{ __('financial.facility') }}</th><th>{{ __('financial.total_contracts') }}</th><th>{{ __('financial.offers') }}</th><th>{{ __('financial.total_revenue') }}</th><th>{{ __('financial.total_commissions') }}</th><th>{{ __('financial.net_income') }}</th></tr></thead>
            <tbody>
                @forelse($facilitiesData as $row)
                    <tr>
                        <td>{{ $row['facility']->name }}</td>
                        <td>{{ $row['total_contracts'] }}</td>
                        <td>{{ $row['active_offers'] }}/{{ $row['total_offers'] }}</td>
                        <td>{{ number_format($row['total_revenue'], 2) }}</td>
                        <td>{{ number_format($row['total_commissions'], 2) }}</td>
                        <td>{{ number_format($row['net_revenue'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted p-4">{{ __('financial.no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
