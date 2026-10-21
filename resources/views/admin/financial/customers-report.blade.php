@extends('admin.financial.layout')

@section('title', __('financial.customers_report'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.customers_report') }}</h2>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>{{ __('financial.client') }}</th><th>{{ __('financial.total_contracts') }}</th><th>{{ __('financial.total_amount') }}</th><th>{{ __('financial.paid_amount') }}</th><th>{{ __('financial.remaining_amount') }}</th><th>{{ __('financial.collection_rate') }}</th></tr></thead>
            <tbody>
                @forelse($customersData as $row)
                    <tr>
                        <td>{{ $row['customer']->name }}</td>
                        <td>{{ $row['total_contracts'] }}</td>
                        <td>{{ number_format($row['total_value'], 2) }}</td>
                        <td>{{ number_format($row['total_paid'], 2) }}</td>
                        <td>{{ number_format($row['remaining_amount'], 2) }}</td>
                        <td>{{ number_format($row['payment_rate'], 1) }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted p-4">{{ __('financial.no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
