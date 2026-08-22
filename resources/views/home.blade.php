@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('dashboard.title') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ is_array(session('status')) ? json_encode(session('status'), JSON_UNESCAPED_UNICODE) : session('status') }}
                        </div>
                    @endif

                    <p class="mb-4">{{ __('dashboard.logged_in_message') }}</p>

                    <div class="d-flex flex-wrap gap-3">
                        @if (auth()->check() && auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt me-2"></i>{{ __('Go to Admin Dashboard') }}
                            </a>
                        @endif

                        @if (auth()->check() && auth()->user()->hasRole('client'))
                            <a href="{{ route('client.dashboard') }}" class="btn btn-success">
                                <i class="fas fa-user me-2"></i>{{ __('Go to Client Dashboard') }}
                            </a>
                        @endif

                        @if (auth()->check() && auth()->user()->hasRole('facility'))
                            <a href="{{ route('facility.dashboard') }}" class="btn btn-info">
                                <i class="fas fa-building me-2"></i>{{ __('Go to Facility Dashboard') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
