<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '') }} - @yield('title', 'لوحة تحكم المنشأة')</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    @if(app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <link href="{{ asset('theme.css') }}" rel="stylesheet">
    <script src="{{ asset('theme.js') }}" defer></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    @stack('styles')

    <style>
        body { font-family: 'Cairo', 'Segoe UI', Tahoma, Arial, sans-serif; }
        .sidebar {
            width: 280px;
        }
        @media (max-width: 992px) {
            .sidebar { width: 100%; }
        }

        :root {
            --brand-brown: #000000;

            --brand-brown-rgb: 0, 0, 0;

            --bs-body-bg: #ffffff;
            --bs-primary: var(--brand-brown);
            --bs-link-color: var(--brand-brown);
            --bs-link-hover-color: var(--brand-brown);

            --bs-body-color: var(--brand-brown);
            --bs-emphasis-color: var(--brand-brown);
            --bs-secondary-color: rgba(var(--brand-brown-rgb), 0.75);
            --bs-tertiary-color: rgba(var(--brand-brown-rgb), 0.55);
            --bs-border-color: rgba(var(--brand-brown-rgb), 0.2);

            --bs-box-shadow: 0 0.5rem 1rem rgba(var(--brand-brown-rgb), 0.08);
            --bs-box-shadow-sm: 0 .125rem .25rem rgba(var(--brand-brown-rgb), 0.08);

            --bs-dark: var(--brand-brown);
            --bs-dark-rgb: var(--brand-brown-rgb);

            --bs-backdrop-bg: var(--brand-brown);
            --bs-backdrop-opacity: 0.2;

            --bs-success: var(--brand-brown);
            --bs-info: var(--brand-brown);
            --bs-warning: var(--brand-brown);
            --bs-danger: var(--brand-brown);
            --bs-secondary: var(--brand-brown);
        }

        .btn-primary,
        .btn-success,
        .btn-info,
        .btn-warning,
        .btn-danger {
            --bs-btn-bg: var(--brand-brown);
            --bs-btn-border-color: var(--brand-brown);
            --bs-btn-hover-bg: var(--brand-brown);
            --bs-btn-hover-border-color: var(--brand-brown);
            --bs-btn-active-bg: var(--brand-brown);
            --bs-btn-active-border-color: var(--brand-brown);
        }

        .btn-outline-primary,
        .btn-outline-success,
        .btn-outline-info,
        .btn-outline-warning,
        .btn-outline-danger,
        .btn-outline-secondary {
            --bs-btn-color: var(--brand-brown);
            --bs-btn-border-color: var(--brand-brown);
            --bs-btn-hover-bg: var(--brand-brown);
            --bs-btn-hover-border-color: var(--brand-brown);
            --bs-btn-active-bg: var(--brand-brown);
            --bs-btn-active-border-color: var(--brand-brown);
        }

        .badge.bg-success,
        .badge.bg-info,
        .badge.bg-warning,
        .badge.bg-danger,
        .badge.bg-primary,
        .badge.bg-secondary {
            background-color: var(--brand-brown) !important;
        }

        .list-group-item.active {
            background-color: var(--brand-brown);
            border-color: var(--brand-brown);
        }

        .text-muted {
            color: rgba(var(--brand-brown-rgb), 0.7) !important;
        }

        .border,
        .border-top,
        .border-bottom,
        .border-start,
        .border-end {
            border-color: rgba(var(--brand-brown-rgb), 0.2) !important;
        }

        .navbar.bg-dark {
            background-color: var(--brand-brown) !important;
        }

        .card {
            box-shadow: var(--bs-box-shadow-sm);
        }

        a,
        a:hover,
        a:focus,
        a:active,
        a:visited {
            color: var(--brand-brown) !important;
        }

        .link-primary,
        .link-success,
        .link-info,
        .link-warning,
        .link-danger,
        .link-secondary,
        .text-primary,
        .text-success,
        .text-info,
        .text-warning,
        .text-danger,
        .text-secondary,
        .text-dark,
        .text-body {
            color: var(--brand-brown) !important;
        }
    </style>
</head>
<body class="bg-light">
@php
    $sidebarUser = auth()->user();
    $sidebarFacility = $sidebarUser?->facilities()?->first();
@endphp

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <button class="btn btn-outline-light d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#facilitySidebar" aria-controls="facilitySidebar">
            <i class="fas fa-bars"></i>
        </button>

        <a class="navbar-brand fw-bold" href="{{ route('facility.dashboard') }}">
            <i class="fas fa-building me-2"></i>
            منشأتي
        </a>

        <div class="ms-auto d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-outline-light" type="button" data-theme-toggle aria-label="Toggle theme">
                <i class="fas fa-moon"></i>
            </button>
            <span class="text-white-50 small d-none d-md-inline">{{ $sidebarUser->name ?? '' }}</span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">تسجيل الخروج</button>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-xl-2 p-0 d-none d-lg-block">
            <div class="bg-white border-end min-vh-100 sidebar">
                <div class="p-3 border-bottom">
                    <div class="fw-bold">القائمة</div>
                    <div class="text-muted small">{{ $sidebarFacility->name ?? '' }}</div>
                </div>
                <div class="p-2">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('facility.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home me-2"></i>
                            لوحة التحكم
                        </a>
                        @if(config('features.facility_home_v2'))
                            <a href="{{ route('facility.home-v2') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.home-v2') ? 'active' : '' }}">
                                <i class="fas fa-gauge-high me-2"></i>
                                لوحة متقدمة
                            </a>
                        @endif
                        <a href="{{ route('facility.projects.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.projects.*') ? 'active' : '' }}">
                            <i class="fas fa-diagram-project me-2"></i>
                            المشاريع
                        </a>
                        <a href="{{ route('facility.execution-requests.workspace') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.execution-requests.*') ? 'active' : '' }}">
                            <i class="fas fa-gavel me-2"></i>
                            طلبات التنفيذ
                        </a>
                        <a href="{{ route('facility.tasks.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.tasks.*') ? 'active' : '' }}">
                            <i class="fas fa-list-check me-2"></i>
                            المهام
                        </a>
                        <a href="{{ route('facility.accounting.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.accounting.*') ? 'active' : '' }}">
                            <i class="fas fa-calculator me-2"></i>
                            المحاسبة
                        </a>
                        <a href="{{ route('facility.financial.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.financial.*') ? 'active' : '' }}">
                            <i class="fas fa-coins me-2"></i>
                            النظام المالي
                        </a>
                        <a href="{{ route('facility.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.profile') ? 'active' : '' }}">
                            <i class="fas fa-id-card me-2"></i>
                            الملف التعريفي
                        </a>
                        <a href="{{ route('facility.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.edit') ? 'active' : '' }}">
                            <i class="fas fa-cog me-2"></i>
                            الإعدادات
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-xl-10 py-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="facilitySidebar" aria-labelledby="facilitySidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="facilitySidebarLabel">القائمة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="list-group list-group-flush">
            <a href="{{ route('facility.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home me-2"></i>
                لوحة التحكم
            </a>
            @if(config('features.facility_home_v2'))
                <a href="{{ route('facility.home-v2') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.home-v2') ? 'active' : '' }}">
                    <i class="fas fa-gauge-high me-2"></i>
                    لوحة متقدمة
                </a>
            @endif
            <a href="{{ route('facility.projects.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.projects.*') ? 'active' : '' }}">
                <i class="fas fa-diagram-project me-2"></i>
                المشاريع
            </a>
            <a href="{{ route('facility.execution-requests.workspace') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.execution-requests.*') ? 'active' : '' }}">
                <i class="fas fa-gavel me-2"></i>
                طلبات التنفيذ
            </a>
            <a href="{{ route('facility.tasks.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.tasks.*') ? 'active' : '' }}">
                <i class="fas fa-list-check me-2"></i>
                المهام
            </a>
            <a href="{{ route('facility.accounting.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.accounting.*') ? 'active' : '' }}">
                <i class="fas fa-calculator me-2"></i>
                المحاسبة
            </a>
            <a href="{{ route('facility.financial.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.financial.*') ? 'active' : '' }}">
                <i class="fas fa-coins me-2"></i>
                النظام المالي
            </a>
            <a href="{{ route('facility.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.profile') ? 'active' : '' }}">
                <i class="fas fa-id-card me-2"></i>
                الملف التعريفي
            </a>
            <a href="{{ route('facility.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.edit') ? 'active' : '' }}">
                <i class="fas fa-cog me-2"></i>
                الإعدادات
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@stack('scripts')
</body>
</html>
