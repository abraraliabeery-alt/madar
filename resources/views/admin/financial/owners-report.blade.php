@extends('admin.financial.layout')

@section('title', __('financial.owners_report'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.owners_report') }}</h2>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>{{ __('financial.owner') }}</th><th>{{ __('financial.total_contracts') }}</th><th>{{ __('financial.total_amount') }}</th><th>{{ __('financial.total_commissions') }}</th></tr></thead>
            <tbody>
                @forelse($ownersData as $row)
                    <tr>
                        <td>{{ $row['owner']->name }}</td>
                        <td>{{ $row['total_contracts'] }}</td>
                        <td>{{ number_format($row['total_value'], 2) }}</td>
                        <td>{{ number_format($row['total_commission'] ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted p-4">{{ __('financial.no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
