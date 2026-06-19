<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', ''))</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    @if(app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <link href="{{ asset('theme.css') }}" rel="stylesheet">
    <script src="{{ asset('theme.js') }}" defer></script>

    @stack('styles')

    <style>
        body { font-family: 'Cairo', 'Segoe UI', Tahoma, Arial, sans-serif; }
        .dash-sidebar { width: 280px; }
        .dash-link i { width: 20px; text-align: center; }
        @media (max-width: 992px) {
            .dash-sidebar { width: 100%; }
        }

        .dash-shell {
            min-height: 100vh;
        }

        .dash-main {
            min-height: calc(100vh - 56px);
        }

        .dash-sidebar-inner {
            height: calc(100vh - 56px);
            position: sticky;
            top: 56px;
            overflow-y: auto;
        }

        .dash-shell[data-sidebar-collapsed="1"] .dash-sidebar {
            width: 80px;
        }

        .dash-shell[data-sidebar-collapsed="1"] .dash-sidebar .dash-label {
            display: none;
        }

        .dash-shell[data-sidebar-collapsed="1"] .dash-sidebar .dash-link {
            text-align: center;
        }

        .dash-shell[data-sidebar-collapsed="1"] .dash-sidebar .dash-link i {
            margin-inline-end: 0 !important;
        }

        .dash-shell[data-sidebar-collapsed="1"] .dash-sidebar .p-2 {
            padding: 0.25rem !important;
        }

        .dash-shell[data-sidebar-collapsed="1"] .dash-sidebar .list-group-item {
            padding-inline: 0.75rem;
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
    $u = auth()->user();
    $isRtl = app()->getLocale() === 'ar';

    $rolePrefix = null;
    if ($u && method_exists($u, 'hasRole')) {
        if ($u->hasRole('client')) $rolePrefix = 'client';
        elseif ($u->hasRole('facility')) $rolePrefix = 'facility';
        elseif ($u->hasRole('admin')) $rolePrefix = 'admin';
    }

    $profileRoute = null;
    if ($rolePrefix === 'client' && \Illuminate\Support\Facades\Route::has('client.profile')) $profileRoute = route('client.profile');
    elseif ($rolePrefix === 'facility' && \Illuminate\Support\Facades\Route::has('facility.profile')) $profileRoute = route('facility.profile');
    elseif ($rolePrefix === 'admin' && \Illuminate\Support\Facades\Route::has('admin.profile')) $profileRoute = route('admin.profile');

    $settingsRoute = null;
    if ($rolePrefix && \Illuminate\Support\Facades\Route::has($rolePrefix . '.settings')) $settingsRoute = route($rolePrefix . '.settings');

    $helpRoute = null;
    if ($rolePrefix === 'client' && \Illuminate\Support\Facades\Route::has('client.help')) $helpRoute = route('client.help');
    elseif ($rolePrefix === 'facility' && \Illuminate\Support\Facades\Route::has('facility.reports')) $helpRoute = route('facility.reports');
    elseif ($rolePrefix === 'admin' && \Illuminate\Support\Facades\Route::has('admin.reports')) $helpRoute = route('admin.reports');

    $notificationsRoute = null;
    if ($rolePrefix && \Illuminate\Support\Facades\Route::has($rolePrefix . '.notifications')) $notificationsRoute = route($rolePrefix . '.notifications');

    $unreadCount = 0;
    $latestNotifications = collect();
    if ($u && method_exists($u, 'unreadNotifications')) {
        $unreadCount = (int) $u->unreadNotifications()->count();
        $latestNotifications = $u->unreadNotifications()->latest()->limit(5)->get();
    }
@endphp

<nav class="navbar navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <button class="btn btn-outline-light d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#dashSidebar" aria-controls="dashSidebar">
            <i class="fas fa-bars"></i>
        </button>

        <a class="navbar-brand fw-bold" href="{{ \Illuminate\Support\Facades\Route::has('public.home') ? route('public.home') : '#' }}">{{ config('app.name', 'المنصة') }}</a>

        <div class="ms-auto d-flex align-items-center gap-2">
            @if(\Illuminate\Support\Facades\Route::has('public.home'))
                <a href="{{ route('public.home') }}" class="btn btn-sm btn-outline-light d-none d-md-inline-flex">
                    <i class="fas fa-globe me-2"></i>
                    <span class="d-none d-lg-inline">زيارة الموقع</span>
                </a>
            @endif

            <button class="btn btn-sm btn-outline-light" type="button" data-theme-toggle aria-label="Toggle theme">
                <i class="fas fa-moon"></i>
            </button>

            @if($helpRoute)
                <a href="{{ $helpRoute }}" class="btn btn-sm btn-outline-light d-none d-md-inline-flex">
                    <i class="fas fa-circle-question me-2"></i>
                    <span class="d-none d-lg-inline">المساعدة</span>
                </a>
            @endif

            @if($notificationsRoute)
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: #ffffff; color: var(--brand-brown); border: 1px solid rgba(var(--brand-brown-rgb), 0.35);">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 320px;">
                        <div class="px-3 py-2 border-bottom fw-bold">الإشعارات</div>
                        @if($latestNotifications->count() > 0)
                            @foreach($latestNotifications as $n)
                                <a class="dropdown-item py-2" href="{{ $notificationsRoute }}">
                                    <div class="small fw-semibold">{{ data_get($n->data, 'title', 'إشعار') }}</div>
                                    <div class="small text-muted">{{ data_get($n->data, 'message', '') }}</div>
                                </a>
                            @endforeach
                        @else
                            <div class="px-3 py-3 text-muted">لا توجد إشعارات جديدة</div>
                        @endif
                        <div class="border-top">
                            <a class="dropdown-item text-center py-2" href="{{ $notificationsRoute }}">عرض الكل</a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="dropdown">
                <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user me-2"></i>
                    <span class="d-none d-md-inline">{{ $u->name ?? '' }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    @if($profileRoute)
                        <a class="dropdown-item" href="{{ $profileRoute }}">
                            <i class="fas fa-id-card me-2"></i>
                            الملف الشخصي
                        </a>
                    @endif
                    @if($settingsRoute)
                        <a class="dropdown-item" href="{{ $settingsRoute }}">
                            <i class="fas fa-gear me-2"></i>
                            الإعدادات
                        </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="px-3 py-1">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary w-100">تسجيل الخروج</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid px-0 dash-shell" id="dashShell" data-sidebar-collapsed="0">
    <div class="row g-0 dash-main">
        <div class="col-lg-auto p-0 d-none d-lg-block {{ $isRtl ? 'order-lg-2' : 'order-lg-1' }}">
            <div class="bg-white {{ $isRtl ? 'border-start' : 'border-end' }} dash-sidebar dash-sidebar-inner">
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-center justify-content-center">
                        <button class="btn btn-outline-secondary btn-sm d-none d-lg-inline-flex align-items-center" type="button" id="sidebarCollapseToggle" aria-label="Toggle sidebar">
                            <i class="fas fa-bars" id="sidebarCollapseIcon"></i>
                        </button>
                    </div>
                    <div class="text-muted small mt-2 text-center">@yield('sidebar_subtitle')</div>
                </div>

                <div class="p-2">
                    <div class="list-group list-group-flush">
                        @if($u && $u->hasRole('client'))
                            <a href="{{ route('client.dashboard') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-home me-2"></i>
                                <span class="dash-label">لوحة العميل</span>
                            </a>
                            <a href="{{ route('client.projects.create') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('client.projects.*') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle me-2"></i>
                                <span class="dash-label">إنشاء مشروع</span>
                            </a>
                            <a href="{{ route('client.bookings.index') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('client.bookings.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check me-2"></i>
                                <span class="dash-label">الحجوزات</span>
                            </a>
                            <a href="{{ route('client.contracts.index') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('client.contracts.*') ? 'active' : '' }}">
                                <i class="fas fa-file-contract me-2"></i>
                                <span class="dash-label">العقود</span>
                            </a>
                            <a href="{{ route('client.favorites') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('client.favorites') ? 'active' : '' }}">
                                <i class="fas fa-heart me-2"></i>
                                <span class="dash-label">المفضلة</span>
                            </a>
                        @endif

                        @if($u && $u->hasRole('facility'))
                            <a href="{{ route('facility.dashboard') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('facility.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-home me-2"></i>
                                <span class="dash-label">لوحة المنشأة</span>
                            </a>
                            <a href="{{ route('facility.projects.index') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('facility.projects.*') ? 'active' : '' }}">
                                <i class="fas fa-diagram-project me-2"></i>
                                <span class="dash-label">المشاريع</span>
                            </a>
                            <a href="{{ route('facility.execution-requests.workspace') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('facility.execution-requests.*') ? 'active' : '' }}">
                                <i class="fas fa-gavel me-2"></i>
                                <span class="dash-label">طلبات التنفيذ</span>
                            </a>
                            <a href="{{ route('facility.tasks.index') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('facility.tasks.*') ? 'active' : '' }}">
                                <i class="fas fa-list-check me-2"></i>
                                <span class="dash-label">المهام</span>
                            </a>
                            <a href="{{ route('facility.financial.dashboard') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('facility.financial.*') ? 'active' : '' }}">
                                <i class="fas fa-coins me-2"></i>
                                <span class="dash-label">النظام المالي</span>
                            </a>
                        @endif

                        @if($u && $u->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-gauge-high me-2"></i>
                                <span class="dash-label">لوحة النظام</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="fas fa-users me-2"></i>
                                <span class="dash-label">المستخدمين</span>
                            </a>
                            <a href="{{ route('admin.facilities.index') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                                <i class="fas fa-building me-2"></i>
                                <span class="dash-label">المنشآت</span>
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <i class="fas fa-box me-2"></i>
                                <span class="dash-label">المشاريع</span>
                            </a>
                            <a href="{{ route('admin.bookings.index') }}" class="list-group-item list-group-item-action dash-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check me-2"></i>
                                <span class="dash-label">الحجوزات</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col py-4 px-3 {{ $isRtl ? 'order-lg-1' : 'order-lg-2' }}">
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

<div class="offcanvas {{ $isRtl ? 'offcanvas-end' : 'offcanvas-start' }}" tabindex="-1" id="dashSidebar" aria-labelledby="dashSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="dashSidebarLabel">@yield('sidebar_title', 'القائمة')</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="list-group list-group-flush">
            @if($u && $u->hasRole('client'))
                <a href="{{ route('client.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i>
                    لوحة العميل
                </a>
                <a href="{{ route('client.projects.create') }}" class="list-group-item list-group-item-action {{ request()->routeIs('client.projects.*') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle me-2"></i>
                    إنشاء مشروع
                </a>
                <a href="{{ route('client.bookings.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('client.bookings.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check me-2"></i>
                    الحجوزات
                </a>
                <a href="{{ route('client.contracts.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('client.contracts.*') ? 'active' : '' }}">
                    <i class="fas fa-file-contract me-2"></i>
                    العقود
                </a>
                <a href="{{ route('client.favorites') }}" class="list-group-item list-group-item-action {{ request()->routeIs('client.favorites') ? 'active' : '' }}">
                    <i class="fas fa-heart me-2"></i>
                    المفضلة
                </a>
            @endif

            @if($u && $u->hasRole('facility'))
                <a href="{{ route('facility.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i>
                    لوحة المنشأة
                </a>
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
                <a href="{{ route('facility.financial.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('facility.financial.*') ? 'active' : '' }}">
                    <i class="fas fa-coins me-2"></i>
                    النظام المالي
                </a>
            @endif

            @if($u && $u->hasRole('admin'))
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-gauge-high me-2"></i>
                    لوحة النظام
                </a>
                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>
                    المستخدمين
                </a>
                <a href="{{ route('admin.facilities.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                    <i class="fas fa-building me-2"></i>
                    المنشآت
                </a>
                <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box me-2"></i>
                    المشاريع
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check me-2"></i>
                    الحجوزات
                </a>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const shell = document.getElementById('dashShell');
        const btn = document.getElementById('sidebarCollapseToggle');
        const icon = document.getElementById('sidebarCollapseIcon');

        if (!shell || !btn || !icon) return;

        function applyCollapsed(collapsed) {
            shell.setAttribute('data-sidebar-collapsed', collapsed ? '1' : '0');
            try { localStorage.setItem('dashSidebarCollapsed', collapsed ? '1' : '0'); } catch (e) {}
        }

        let initial = false;
        try { initial = localStorage.getItem('dashSidebarCollapsed') === '1'; } catch (e) {}
        applyCollapsed(initial);

        btn.addEventListener('click', function () {
            const isCollapsed = shell.getAttribute('data-sidebar-collapsed') === '1';
            applyCollapsed(!isCollapsed);
        });
    })();
</script>
@stack('scripts')
</body>
</html>
