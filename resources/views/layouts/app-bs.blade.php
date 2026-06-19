<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', ''))</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    @if(app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <link href="{{ asset('theme.css') }}" rel="stylesheet">
    <script src="{{ asset('theme.js') }}" defer></script>

    @stack('styles')

    <style>
        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Arial, sans-serif;
        }

        :root {
            --brand-brown: #000000;

            --brand-brown-rgb: 0, 0, 0;

            --bs-body-bg: #ffffff;
            --bs-body-color: var(--brand-brown);
            --bs-emphasis-color: var(--brand-brown);
            --bs-primary: var(--brand-brown);
            --bs-link-color: var(--brand-brown);
            --bs-link-hover-color: var(--brand-brown);

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
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('public.home') }}">{{ config('app.name', 'المنصة') }}</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('public.home') }}">{{ __('layout.navigation.home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('public.execution.marketplace') }}">{{ app()->getLocale() == 'ar' ? 'المشاريع' : 'Projects' }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('public.products.index') }}">{{ app()->getLocale() == 'ar' ? 'المنتجات' : 'Products' }}</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            @if(auth()->user()->hasRole('client'))
                                <li><a class="dropdown-item" href="{{ route('client.dashboard') }}">لوحة العميل</a></li>
                            @endif
                            @if(auth()->user()->hasRole('facility'))
                                <li><a class="dropdown-item" href="{{ route('facility.dashboard') }}">لوحة المنشأة</a></li>
                            @endif
                            @if(auth()->user()->hasRole('admin'))
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">لوحة الإدارة</a></li>
                            @endif
                            @if(!auth()->user()->hasRole('facility') && method_exists(auth()->user(), 'facilities') && !auth()->user()->facilities()->exists())
                                <li><a class="dropdown-item" href="{{ route('facility.onboarding.create') }}">تحويل الحساب إلى منشأة</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">تسجيل الخروج</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">تسجيل الدخول</a></li>
                @endauth
            </ul>

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-theme-toggle aria-label="Toggle theme">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
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
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
