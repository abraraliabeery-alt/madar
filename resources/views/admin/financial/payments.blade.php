@extends('admin.financial.layout')

@section('title', __('financial.payments'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('financial.payments') }}</h2>
</div>

@if(isset($report))
    {{-- Report shape --}}
    <div class="card p-3 mb-4"><small class="text-muted">{{ __('financial.total_payments') }}</small><h3>{{ number_format($report['total_payments'] ?? 0, 2) }}</h3></div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>#</th><th>{{ __('financial.contract') }}</th><th>{{ __('financial.amount') }}</th><th>{{ __('financial.method') }}</th><th>{{ __('financial.date') }}</th></tr></thead>
                <tbody>
                    @forelse(($report['payments'] ?? []) as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->contract_id }}</td>
                            <td>{{ number_format($p->amount ?? 0, 2) }}</td>
                            <td>{{ $p->payment_method }}</td>
                            <td>{{ $p->payment_date }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted p-4">{{ __('financial.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@else
    {{-- Management shape --}}
    <form method="GET" class="card p-3 mb-4 row g-2 flex-row">
        <div class="col-md-3"><select name="facility_id" class="form-select"><option value="">{{ __('financial.all_facilities') }}</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select></div>
        <div class="col-md-2"><select name="status" class="form-select"><option value="">{{ __('financial.all_statuses') }}</option><option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('financial.pending') }}</option><option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>{{ __('financial.confirmed') }}</option></select></div>
        <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="{{ __('financial.search') }}" value="{{ request('search') }}"></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">{{ __('financial.filter') }}</button></div>
        <div class="col-md-2"><a href="{{ route('admin.financial.payments.export') }}" class="btn btn-outline-secondary w-100">{{ __('financial.export') }}</a></div>
    </form>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>#</th><th>{{ __('financial.client') }}</th><th>{{ __('financial.amount') }}</th><th>{{ __('financial.method') }}</th><th>{{ __('financial.date') }}</th><th>{{ __('financial.status') }}</th><th></th></tr></thead>
                <tbody>
                    @forelse($payments as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ optional(optional($p->contract)->user)->name ?? '-' }}</td>
                            <td>{{ number_format($p->amount ?? 0, 2) }}</td>
                            <td>{{ $p->payment_method }}</td>
                            <td>{{ $p->payment_date }}</td>
                            <td><span class="badge bg-{{ $p->status === 'confirmed' ? 'success' : 'warning' }}">{{ $p->status }}</span></td>
                            <td>
                                @if($p->status !== 'confirmed')
                                    <form method="POST" action="{{ route('admin.financial.payments.confirm', $p->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success">{{ __('financial.confirm') }}</button></form>
                                    <form method="POST" action="{{ route('admin.financial.payments.reject', $p->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-danger">{{ __('financial.reject') }}</button></form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted p-4">{{ __('financial.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($payments, 'links'))<div class="card-footer">{{ $payments->links() }}</div>@endif
    </div>
@endif
@endsection
