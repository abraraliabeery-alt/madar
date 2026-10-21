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

@section('panel_title', '')

@section('sidebar')
    @php
        $sidebarGroupIcons = [
            'management' => 'fas fa-users-gear',
            'content' => 'fas fa-layer-group',
            'operations' => 'fas fa-briefcase',
            'location' => 'fas fa-map-location-dot',
            'system' => 'fas fa-sliders',
        ];
        $sidebarGroupLabel = function (string $group) {
            $label = __('admin.sidebar.groups.' . $group);
            return $label === 'admin.sidebar.groups.' . $group ? ucfirst($group) : $label;
        };
    @endphp
    <aside class="sidebar sb-nav d-flex flex-column h-100" data-intro="{{ __('admin.tour.sidebar_desc') }}" data-step="1">
        @include('layouts.partials.sidebar-brand', ['icon' => 'fas fa-shield-halved', 'title' => __('admin.dashboard.title'), 'badge' => auth()->user()?->name])
        <div class="nav-scroll overflow-auto" style="min-height:0;" data-intro="{{ __('admin.tour.navigation_desc') }}" data-step="3">
            <div class="nav flex-column">
                <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>{{ __('layout.user_menu.profile') }}</span>
                </a>

                @php($adminMenuConfigured = \App\Models\MenuItem::where('panel', 'admin')->exists())
                @php($adminMenu = \App\Services\MenuService::forPanel('admin'))
                @php($sidebarBadges = [
                    'admin.notifications' => (int) auth()->user()?->unreadNotifications()->count(),
                    'admin.product-requests.index' => (int) \App\Models\ProductRequest::where('status', \App\Models\ProductRequest::STATUS_NEW)->count(),
                ])
                @if($adminMenuConfigured)
                    @php($currentSidebarGroup = null)
                    @foreach($adminMenu as $item)
                        @continue(in_array($item['route_name'], ['admin.settings', 'admin.menus.index']))
                        @if(($item['group'] ?? null) !== $currentSidebarGroup)
                            @if($currentSidebarGroup)
                                @include('layouts.partials.sidebar-group-close')
                            @endif
                            @php($currentSidebarGroup = $item['group'])
                            @if($currentSidebarGroup)
                                @include('layouts.partials.sidebar-group-open', ['key' => $currentSidebarGroup, 'label' => $sidebarGroupLabel($currentSidebarGroup), 'icon' => $sidebarGroupIcons[$currentSidebarGroup] ?? null])
                            @endif
                        @endif
                        @php($isActive = $item['route_name'] ? request()->routeIs($item['route_name']) : false)
                        <a href="{{ $item['href'] }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                            @if(!empty($item['icon']))
                                <i class="{{ $item['icon'] }}"></i>
                            @else
                                <i class="fas fa-circle"></i>
                            @endif
                            <span>{{ $item['label'] }}</span>
                            @if(($sidebarBadges[$item['route_name']] ?? 0) > 0 || $item['route_name'] === 'admin.notifications')
                                <span class="badge rounded-pill bg-danger ms-auto {{ $item['route_name'] === 'admin.notifications' ? 'notif-count' : '' }} {{ ($sidebarBadges[$item['route_name']] ?? 0) > 0 ? '' : 'd-none' }}">{{ $sidebarBadges[$item['route_name']] ?? 0 }}</span>
                            @endif
                        </a>
                    @endforeach
                    @if($currentSidebarGroup)
                        @include('layouts.partials.sidebar-group-close')
                    @endif
                @else
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>{{ __('admin.dashboard.title') }}</span>
                    </a>

                    @include('layouts.partials.sidebar-group-open', ['key' => 'management', 'label' => $sidebarGroupLabel('management'), 'icon' => $sidebarGroupIcons['management'] ?? null])
                        <div class="sb-collapsible sidebar-sub" data-sb-key="s:users">
                            <button type="button" class="nav-link {{ request()->routeIs('admin.users.*', 'admin.permissions.*') ? 'active' : '' }}" data-sb-toggle aria-expanded="false">
                                <i class="fas fa-users"></i>
                                <span>{{ __('admin.users.title') }}</span>
                                <i class="fas fa-chevron-down sidebar-group-chevron ms-auto"></i>
                            </button>
                            <div class="sidebar-group-items"><div>
                                <a href="{{ route('admin.users.index') }}" class="nav-link nav-sub {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                    <i class="fas fa-list"></i><span>{{ __('admin.users.title') }}</span>
                                </a>
                                <a href="{{ route('admin.users.statistics') }}" class="nav-link nav-sub {{ request()->routeIs('admin.users.statistics') ? 'active' : '' }}">
                                    <i class="fas fa-chart-line"></i><span>{{ __('admin.actions.view_statistics') }}</span>
                                </a>
                                <a href="{{ route('admin.users.export') }}" class="nav-link nav-sub">
                                    <i class="fas fa-download"></i><span>{{ __('admin.actions.export') }}</span>
                                </a>
                                <a href="{{ route('admin.users.activity-logs') }}" class="nav-link nav-sub {{ request()->routeIs('admin.users.activity-logs') ? 'active' : '' }}">
                                    <i class="fas fa-history"></i><span>{{ __('admin.actions.view_activity_logs') }}</span>
                                </a>
                                <a href="{{ route('admin.permissions.index') }}" class="nav-link nav-sub {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-cog"></i><span>{{ __('admin.permissions.title') }}</span>
                                </a>
                            </div></div>
                        </div>
                        <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                            <i class="fas fa-user-shield"></i>
                            <span>{{ __('admin.roles.title') }}</span>
                        </a>
                        <a href="{{ route('admin.facilities.index') }}" class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>{{ __('admin.facilities.title') }}</span>
                        </a>
                    @include('layouts.partials.sidebar-group-close')

                    @include('layouts.partials.sidebar-group-open', ['key' => 'content', 'label' => $sidebarGroupLabel('content'), 'icon' => $sidebarGroupIcons['content'] ?? null])
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
                    @include('layouts.partials.sidebar-group-close')

                    @include('layouts.partials.sidebar-group-open', ['key' => 'operations', 'label' => $sidebarGroupLabel('operations'), 'icon' => $sidebarGroupIcons['operations'] ?? null])
                        @if(\App\Helpers\PlatformModeHelper::allowsRealEstate())
                            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <i class="fas fa-box"></i>
                                <span>{{ __('admin.products.title') }}</span>
                            </a>
                            <div class="sb-collapsible sidebar-sub" data-sb-key="s:requests">
                                <button type="button" class="nav-link {{ request()->routeIs('admin.product-requests.*', 'admin.marketing-product-requests.*') ? 'active' : '' }}" data-sb-toggle aria-expanded="false">
                                    <i class="fas fa-clipboard-list"></i>
                                    <span>{{ __('admin.sidebar.requests') }}</span>
                                    @if(($sidebarBadges['admin.product-requests.index'] ?? 0) > 0)
                                        <span class="badge rounded-pill bg-danger ms-auto">{{ $sidebarBadges['admin.product-requests.index'] }}</span>
                                    @endif
                                    <i class="fas fa-chevron-down sidebar-group-chevron {{ ($sidebarBadges['admin.product-requests.index'] ?? 0) > 0 ? '' : 'ms-auto' }}"></i>
                                </button>
                                <div class="sidebar-group-items"><div>
                                    <a href="{{ route('admin.product-requests.index') }}" class="nav-link nav-sub {{ request()->routeIs('admin.product-requests.*') ? 'active' : '' }}">
                                        <i class="fas fa-building"></i><span>{{ __('admin.sidebar.product_requests') }}</span>
                                    </a>
                                    <a href="{{ route('admin.marketing-product-requests.index') }}" class="nav-link nav-sub {{ request()->routeIs('admin.marketing-product-requests.*') ? 'active' : '' }}">
                                        <i class="fas fa-bullhorn"></i><span>{{ __('admin.sidebar.marketing_requests') }}</span>
                                    </a>
                                </div></div>
                            </div>
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
                            <span class="badge rounded-pill bg-danger ms-auto notif-count {{ ($sidebarBadges['admin.notifications'] ?? 0) > 0 ? '' : 'd-none' }}">{{ $sidebarBadges['admin.notifications'] ?? 0 }}</span>
                        </a>
                    @include('layouts.partials.sidebar-group-close')
                @endif

                @include('layouts.partials.sidebar-group-open', ['key' => 'location', 'label' => $sidebarGroupLabel('location'), 'icon' => $sidebarGroupIcons['location'] ?? null])
                    <a href="{{ route('admin.cities.index') }}" class="nav-link {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                        <i class="fas fa-city"></i>
                        <span>{{ __('admin.cities.title') }}</span>
                    </a>
                    <a href="{{ route('admin.neighborhoods.index') }}" class="nav-link {{ request()->routeIs('admin.neighborhoods.*') ? 'active' : '' }}">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ __('admin.neighborhoods.title') }}</span>
                    </a>
                    <a href="{{ route('admin.streets.index') }}" class="nav-link {{ request()->routeIs('admin.streets.*') ? 'active' : '' }}">
                        <i class="fas fa-road"></i>
                        <span>{{ __('admin.streets.title') }}</span>
                    </a>
                @include('layouts.partials.sidebar-group-close')

                @include('layouts.partials.sidebar-group-open', ['key' => 'system', 'label' => $sidebarGroupLabel('system'), 'icon' => $sidebarGroupIcons['system'] ?? null])
                    <a href="{{ route('admin.menus.index') }}" class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        <span>{{ __('admin.menus.title') }}</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>{{ __('admin.settings.title') }}</span>
                    </a>
                    <a href="{{ route('admin.pdf.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.pdf.*') ? 'active' : '' }}">
                        <i class="fas fa-file-pdf"></i>
                        <span>{{ __('admin.pdf_settings.title') }}</span>
                    </a>
                    <a href="{{ route('admin.theme.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.theme.*') ? 'active' : '' }}">
                        <i class="fas fa-palette"></i>
                        <span>{{ __('admin.theme_settings.title') }}</span>
                    </a>
                @include('layouts.partials.sidebar-group-close')

                <form action="{{ route('logout') }}" method="POST" class="sidebar-foot">
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
