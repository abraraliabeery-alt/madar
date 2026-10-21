@extends('facility.layouts.app')

@section('title', __('facility.sidebar.change_password'))

@section('content')
<div class="container-fluid" style="max-width: 560px;">
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-0">{{ __('facility.sidebar.change_password') }}</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('facility.change-password.update') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">كلمة المرور الحالية</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">كلمة المرور الجديدة</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-lock me-1"></i> تحديث كلمة المرور
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
