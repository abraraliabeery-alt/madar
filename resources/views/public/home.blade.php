@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="hero-section text-center py-5 bg-primary text-white">
        <div class="container">
            <h1 class="display-4 mb-4">مرحباً بك في منصة العقار</h1>
            <p class="lead mb-4">اكتشف أفضل العقارات والمنشآت العقارية في منطقتك</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{ route('public.search') }}" method="GET" class="search-form">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" name="q" placeholder="ابحث عن عقار، منشأة، أو منطقة..." required>
                            <button class="btn btn-light" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Categories -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">الفئات المميزة</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-building fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">شقق</h5>
                            <p class="card-text">اكتشف أفضل الشقق السكنية للإيجار أو البيع</p>
                            <a href="{{ route('public.products.by-category', 'apartments') }}" class="btn btn-outline-primary">استعرض الشقق</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-home fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">فيلات</h5>
                            <p class="card-text">فيلات فاخرة ومميزة في أفضل المواقع</p>
                            <a href="{{ route('public.products.by-category', 'villas') }}" class="btn btn-outline-primary">استعرض الفيلات</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-briefcase fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">مكاتب</h5>
                            <p class="card-text">مكاتب تجارية للاستثمار أو الاستخدام</p>
                            <a href="{{ route('public.products.by-category', 'offices') }}" class="btn btn-outline-primary">استعرض المكاتب</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">أحدث العقارات</h2>
            <div class="row">
                @for ($i = 1; $i <= 6; $i++)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-img-top bg-secondary" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image fa-3x text-white-50"></i>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">عقار مميز {{ $i }}</h5>
                            <p class="card-text">وصف مختصر للعقار المميز مع التفاصيل الأساسية.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">${{ number_format(rand(100000, 500000)) }}</span>
                                <a href="#" class="btn btn-sm btn-primary">عرض التفاصيل</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('public.products.index') }}" class="btn btn-primary btn-lg">عرض جميع العقارات</a>
            </div>
        </div>
    </section>

    <!-- Featured Facilities -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">المنشآت المميزة</h2>
            <div class="row">
                @for ($i = 1; $i <= 3; $i++)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-img-top bg-secondary" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building fa-3x text-white-50"></i>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">منشأة مميزة {{ $i }}</h5>
                            <p class="card-text">وصف مختصر للمنشأة مع الخدمات المقدمة والموقع.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">متحقق</span>
                                <a href="#" class="btn btn-sm btn-primary">عرض التفاصيل</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('public.facilities.index') }}" class="btn btn-primary btn-lg">عرض جميع المنشآت</a>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white text-center">
        <div class="container">
            <h2 class="mb-4">هل تريد بيع أو إيجار عقارك؟</h2>
            <p class="lead mb-4">انضم إلينا واحصل على أفضل الخدمات العقارية</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">سجل الآن</a>
            <a href="{{ route('public.contact') }}" class="btn btn-outline-light btn-lg">تواصل معنا</a>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    min-height: 400px;
    display: flex;
    align-items: center;
}

.search-form .input-group {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
</style>
@endpush
