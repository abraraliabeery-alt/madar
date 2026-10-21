@extends('admin.layouts.app')

@section('title', __('admin.change_password'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4">{{ __('admin.change_password') }}</h2>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="card">
            <form method="POST" action="{{ route('admin.change-password.update') }}" class="card-body">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('admin.current_password') }}</label>
                    <input type="password" name="current_password" class="form-control" required>
                    @error('current_password')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('admin.new_password') }}</label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                    @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('admin.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button class="btn btn-primary">{{ __('admin.save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
