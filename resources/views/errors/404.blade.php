@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h1 class="display-1 text-muted">404</h1>
                    <h2 class="text-danger">الصفحة غير موجودة</h2>
                </div>
                <div class="card-body text-center">
                    <p class="lead">عذراً، الصفحة التي تبحث عنها غير موجودة.</p>
                    <p class="text-muted">ربما تم نقلها أو حذفها، أو أن الرابط غير صحيح.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary me-2">
                            <i class="fas fa-home me-2"></i>الصفحة الرئيسية
                        </a>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-2"></i>رجوع
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
