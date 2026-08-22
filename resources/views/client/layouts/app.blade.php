@extends('layouts.dashboard')

@php
    $panelRoutes = [
        'notifications_index' => '#',
        'notifications_latest' => '#',
        'search_global' => '#',
        'search_results' => '#',
    ];
    $panelShowQuickActions = false;
    $panelEnableNotifications = false;
    $panelEnableGlobalSearch = false;
@endphp

@section('panel_title', __('client.navigation.dashboard'))

@section('sidebar')
    <aside class="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0">مدار التفاوض</h4>
            <button class="btn btn-light d-lg-none" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="nav-scroll">
            <div class="nav flex-column">
                <a href="{{ route('client.profile') }}" class="nav-link {{ request()->routeIs('client.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>{{ __('layout.user_menu.profile') }}</span>
                </a>

                @php($clientMenuConfigured = \App\Models\MenuItem::where('panel', 'client')->exists())
                @php($clientMenu = \App\Services\MenuService::forPanel('client'))
                @if($clientMenuConfigured)
                    @foreach($clientMenu as $item)
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
                    <a href="{{ route('client.dashboard') }}" class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>{{ __('client.navigation.dashboard') }}</span>
                    </a>

                    @if(\App\Helpers\PlatformModeHelper::allowsRealEstate())
                        <a href="{{ route('client.bookings.index') }}" class="nav-link {{ request()->routeIs('client.bookings.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check"></i>
                            <span>{{ __('client.navigation.bookings') }}</span>
                        </a>

                        <a href="{{ route('client.appointments') }}" class="nav-link {{ request()->routeIs('client.appointments.*') ? 'active' : '' }}">
                            <i class="fas fa-clock"></i>
                            <span>{{ __('client.navigation.appointments') }}</span>
                        </a>

                        <a href="{{ route('client.favorites') }}" class="nav-link {{ request()->routeIs('client.favorites.*') ? 'active' : '' }}">
                            <i class="fas fa-heart"></i>
                            <span>{{ __('client.navigation.favorites') }}</span>
                        </a>
                    @endif

                    @if(\App\Helpers\PlatformModeHelper::allowsContracting())
                        <a href="{{ route('client.projects.create') }}" class="nav-link {{ request()->routeIs('client.projects.*') ? 'active' : '' }}">
                            <i class="fas fa-diagram-project"></i>
                            <span>{{ __('client.navigation.create_project') }}</span>
                        </a>

                        <a href="{{ route('client.contracts.index') }}" class="nav-link {{ request()->routeIs('client.contracts.*') ? 'active' : '' }}">
                            <i class="fas fa-file-contract"></i>
                            <span>{{ __('client.navigation.contracts') }}</span>
                        </a>
                    @endif
                @endif
            </div>
        </div>

        <div class="p-3 border-top">
            <div class="d-flex flex-column gap-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-light w-100 text-start text-danger">
                        <i class="fas fa-sign-out-alt ms-2"></i>{{ __('layout.user_menu.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </aside>
@endsection
