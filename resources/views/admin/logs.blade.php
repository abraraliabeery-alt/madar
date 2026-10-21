@extends('admin.layouts.app')

@section('title', __('admin.logs'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('admin.logs') }}</h2>
</div>

<div class="card">
    <div class="card-body p-0">
        <pre class="m-0 p-3 small" style="max-height:70vh;overflow:auto;direction:ltr;text-align:left;">@forelse($logs as $line){{ $line }}
@empty{{ __('admin.no_data') }}@endforelse</pre>
    </div>
</div>
@endsection
