@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إعدادات الهوية والألوان</h5>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.theme.settings.update') }}" method="POST">
                @csrf

                <ul class="nav nav-tabs mb-4" id="themeTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#light">الوضع الفاتح</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#dark">الوضع الداكن</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="light">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">اللون الأساسي (brand_brown)</label>
                                    <input type="color" class="form-control form-control-color" name="light[brand_brown]" value="{{ $light['brand_brown'] }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">خلفية الصفحة (brand_bg)</label>
                                    <input type="color" class="form-control form-control-color" name="light[brand_bg]" value="{{ $light['brand_bg'] }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">لون النص (brand_fg)</label>
                                    <input type="color" class="form-control form-control-color" name="light[brand_fg]" value="{{ $light['brand_fg'] }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">لون الحدود (brand_border)</label>
                                    <input type="text" class="form-control" name="light[brand_border]" value="{{ $light['brand_border'] }}" placeholder="rgba(...)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">لون النص المخفت (brand_muted)</label>
                                    <input type="color" class="form-control form-control-color" name="light[brand_muted]" value="{{ $light['brand_muted'] }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="dark">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">اللون الأساسي (brand_brown)</label>
                                    <input type="color" class="form-control form-control-color" name="dark[brand_brown]" value="{{ $dark['brand_brown'] }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">خلفية الصفحة (brand_bg)</label>
                                    <input type="color" class="form-control form-control-color" name="dark[brand_bg]" value="{{ $dark['brand_bg'] }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">لون النص (brand_fg)</label>
                                    <input type="color" class="form-control form-control-color" name="dark[brand_fg]" value="{{ $dark['brand_fg'] }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">لون الحدود (brand_border)</label>
                                    <input type="text" class="form-control" name="dark[brand_border]" value="{{ $dark['brand_border'] }}" placeholder="rgba(...)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">لون النص المخفت (brand_muted)</label>
                                    <input type="color" class="form-control form-control-color" name="dark[brand_muted]" value="{{ $dark['brand_muted'] }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>حفظ الإعدادات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
