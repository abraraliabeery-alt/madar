<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme-default="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', ''))</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    @if(app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <link href="{{ asset('theme.css') }}?v={{ @filemtime(public_path('theme.css')) }}" rel="stylesheet">

    @vite(['resources/sass/app.scss'])

    @stack('styles')
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

        <div class="d-flex align-items-center gap-2">
            
            @if(\Illuminate\Support\Facades\Route::has('public.home'))
                <a href="{{ route('public.home') }}" class="btn btn-sm btn-outline-light d-none d-md-inline-flex">
                    <i class="fas fa-globe me-2"></i>
                    <span class="d-none d-lg-inline">{{ __('layout.dashboard_nav.visit_site') }}</span>
                </a>
            @endif

            @if(\Illuminate\Support\Facades\Route::has('language.switch'))
                @php
                    $languageSwitcher = \App\Helpers\LanguageHelper::getLanguageSwitcherData();
                    $currentLanguage = \App\Helpers\LanguageHelper::getCurrentLanguage();
                @endphp
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-language me-2"></i>
                        <span class="d-none d-md-inline">{{ strtoupper($currentLanguage) }}</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        @foreach($languageSwitcher as $lang)
                            <a class="dropdown-item {{ $lang['code'] === $currentLanguage ? 'active' : '' }}" href="{{ route('language.switch', ['language' => $lang['code']]) }}">
                                <span class="me-2">{{ $lang['flag'] ?? '' }}</span>
                                <span>{{ $lang['native'] ?? strtoupper($lang['code']) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <button class="btn btn-sm btn-outline-light" type="button" data-theme-toggle aria-label="Toggle theme">
                <i class="fas fa-moon"></i>
            </button>

            @if($helpRoute)
                <a href="{{ $helpRoute }}" class="btn btn-sm btn-outline-light d-none d-md-inline-flex">
                    <i class="fas fa-circle-question me-2"></i>
                    <span class="d-none d-lg-inline">{{ __('layout.dashboard_nav.help') }}</span>
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
                    <div class="dropdown-menu dropdown-menu-end" style="min-width: 340px;">
                        <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                            <strong>{{ __('layout.dashboard_nav.notifications') }}</strong>
                            @if($unreadCount > 0)
                                <form method="POST" action="{{ $notificationsRoute }}/mark-all-read" class="m-0">
                                    @csrf
                                    <button class="btn btn-sm btn-link p-0" type="submit">{{ __('layout.dashboard_nav.mark_all_read') }}</button>
                                </form>
                            @endif
                        </div>
                        <div class="list-group list-group-flush" style="max-height: 320px; overflow:auto;">
                            @forelse($latestNotifications as $n)
                                <a class="list-group-item list-group-item-action" href="#">
                                    <div class="small text-muted">{{ $n->created_at?->diffForHumans() }}</div>
                                    <div>{{ data_get($n->data, 'message', __('layout.dashboard_nav.new_notification')) }}</div>
                                </a>
                            @empty
                                <div class="px-3 py-3 text-muted">{{ __('layout.dashboard_nav.no_notifications') }}</div>
                            @endforelse
                        </div>
                        <div class="px-3 py-2 border-top">
                            <a href="{{ $notificationsRoute }}" class="btn btn-sm btn-outline-secondary w-100">{{ __('layout.dashboard_nav.view_all') }}</a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="dropdown">
                <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-2"></i>
                    <span class="d-none d-md-inline">{{ $u?->name ?? __('layout.dashboard_nav.account') }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    @if($profileRoute)
                        <a class="dropdown-item" href="{{ $profileRoute }}">
                            <i class="fas fa-user me-2"></i>{{ __('layout.user_menu.profile') }}
                        </a>
                    @endif
                    @if($settingsRoute)
                        <a class="dropdown-item" href="{{ $settingsRoute }}">
                            <i class="fas fa-gear me-2"></i>{{ __('layout.user_menu.settings') }}
                        </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="px-3 py-1">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary w-100">{{ __('client.navigation.logout') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <a class="navbar-brand fw-bold d-inline-flex align-items-center p-1" href="{{ \Illuminate\Support\Facades\Route::has('public.home') ? route('public.home') : '#' }}">
            <img src="{{ asset('images/madar-negotiation-icon.svg') }}" alt="مدار التفاوض" class="brand-logo" style="width:72px;height:72px">
        </a>
    </div>
</nav>

<div class="container-fluid px-0 dash-shell" id="dashShell" data-sidebar-collapsed="0">
    <div class="row g-0 dash-main">
        <div class="col-lg-auto p-0 d-none d-lg-block {{ $isRtl ? 'order-lg-2' : 'order-lg-1' }}">
            <div class="bg-white {{ $isRtl ? 'border-start' : 'border-end' }} dash-sidebar dash-sidebar-inner">
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-center {{ $isRtl ? 'justify-content-end' : 'justify-content-start' }}">
                        <button class="btn btn-outline-secondary btn-sm d-none d-lg-inline-flex align-items-center" type="button" id="sidebarCollapseToggle" aria-label="Toggle sidebar">
                            <i class="fas fa-bars" id="sidebarCollapseIcon"></i>
                        </button>
                    </div>
                    <div class="text-muted small mt-2 text-center">@yield('sidebar_subtitle')</div>
                </div>

                <div class="p-0">
            @yield('sidebar')
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
        <h5 class="offcanvas-title" id="dashSidebarLabel">@yield('panel_title', __('client.navigation.dashboard'))</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
            @yield('sidebar')
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('theme.js') }}?v={{ @filemtime(public_path('theme.js')) }}"></script>
@stack('scripts')
</body>
</html>
