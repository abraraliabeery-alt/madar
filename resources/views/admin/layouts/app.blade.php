@extends('layouts.dashboard')

@php
    $panelRoutes = [
        'notifications_index' => route('admin.notifications'),
        'notifications_latest' => route('admin.notifications.latest'),
        'search_global' => '/admin/search/global',
        'search_results' => '/admin/search/results',
    ];
    $panelShowQuickActions = true;
@endphp

@section('panel_title', __('admin.dashboard.title'))

@section('sidebar')
    <aside class="sidebar d-flex flex-column h-100" data-intro="{{ __('admin.tour.sidebar_desc') }}" data-step="1">
        <div class="sidebar-header">
            <h4 class="mb-0">{{ __('admin.navigation.brand') }}</h4>
            <button class="btn btn-light d-lg-none" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="nav-scroll flex-grow-1 overflow-auto" style="min-height:0;" data-intro="{{ __('admin.tour.navigation_desc') }}" data-step="3">
            <div class="nav flex-column">
                <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>{{ __('layout.user_menu.profile') }}</span>
                </a>

                @php($adminMenuConfigured = \App\Models\MenuItem::where('panel', 'admin')->exists())
                @php($adminMenu = \App\Services\MenuService::forPanel('admin'))
                @if($adminMenuConfigured)
                    @foreach($adminMenu as $item)
                        @continue(in_array($item['route_name'], ['admin.settings', 'admin.menus.index']))
                        @php($isActive = $item['route_name'] ? request()->routeIs($item['route_name']) : false)
                        <a href="{{ $item['href'] }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                            @if(!empty($item['icon']))
                                <i class="{{ $item['icon'] }}"></i>
                            @else
                                <i class="fas fa-circle"></i>
                            @endif
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                @else
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>{{ __('admin.dashboard.title') }}</span>
                    </a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                            <i class="fas fa-users"></i>
                            <span>{{ __('admin.users.title') }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-list me-2"></i>{{ __('admin.users.title') }}
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.users.statistics') }}">
                                <i class="fas fa-chart-line me-2"></i>{{ __('admin.actions.view_statistics') }}
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.users.export') }}">
                                <i class="fas fa-download me-2"></i>{{ __('admin.actions.export') }}
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.users.activity-logs') }}">
                                <i class="fas fa-history me-2"></i>{{ __('admin.actions.view_activity_logs') }}
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.permissions.index') }}">
                                <i class="fas fa-user-cog me-2"></i>{{ __('admin.permissions.title') }}
                            </a></li>
                        </ul>
                    </div>
                    <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>{{ __('admin.roles.title') }}</span>
                    </a>
                    <a href="{{ route('admin.facilities.index') }}" class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>{{ __('admin.facilities.title') }}</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        <span>{{ __('admin.categories.title') }}</span>
                    </a>
                    <a href="{{ route('admin.features.index') }}" class="nav-link {{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        <span>{{ __('admin.features.title') }}</span>
                    </a>
                    <a href="{{ route('admin.attributes.index') }}" class="nav-link {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>{{ __('admin.attributes.title') }}</span>
                    </a>
                    <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span>{{ __('admin.faqs.title') }}</span>
                    </a>
                    @if(\App\Helpers\PlatformModeHelper::allowsRealEstate())
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="fas fa-box"></i>
                            <span>{{ __('admin.products.title') }}</span>
                        </a>
                    @endif
                    <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>{{ __('admin.bookings.title') }}</span>
                    </a>
                    <a href="{{ route('admin.contracts.index') }}" class="nav-link {{ request()->routeIs('admin.contracts.*') ? 'active' : '' }}">
                        <i class="fas fa-file-contract"></i>
                        <span>{{ __('admin.contracts.title') }}</span>
                    </a>
                    <a href="{{ route('admin.notifications') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>{{ __('layout.notifications.title') }}</span>
                    </a>
                @endif

                <a href="{{ route('admin.menus.index') }}" class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>{{ __('admin.menus.title') }}</span>
                </a>
            </div>
        </div>

        <div class="p-3 border-top mt-auto">
            <div class="nav flex-column">
                <a href="{{ route('admin.settings') }}" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>{{ __('admin.settings.title') }}</span>
                </a>
                <a href="{{ route('admin.pdf.settings.edit') }}" class="nav-link">
                    <i class="fas fa-file-pdf"></i>
                    <span>{{ __('admin.pdf_settings.title') }}</span>
                </a>
                <a href="{{ route('admin.theme.settings.edit') }}" class="nav-link">
                    <i class="fas fa-palette"></i>
                    <span>إعدادات الهوية</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link text-danger" style="width:100%;background:transparent;border:0;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>{{ __('layout.user_menu.logout') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
@endsection
