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

@section('panel_title', __('facility.dashboard.title'))

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
                <a href="{{ route('facility.profile') }}" class="nav-link {{ request()->routeIs('facility.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>{{ __('layout.user_menu.profile') }}</span>
                </a>

                @php($facilityMenuConfigured = \App\Models\MenuItem::where('panel', 'facility')->exists())
                @php($facilityMenu = \App\Services\MenuService::forPanel('facility'))
                @if($facilityMenuConfigured)
                    @foreach($facilityMenu as $item)
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
                    <a href="{{ route('facility.dashboard') }}" class="nav-link {{ request()->routeIs('facility.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>{{ __('facility.dashboard.title') }}</span>
                    </a>

                    @if(\App\Helpers\PlatformModeHelper::allowsContracting())
                        <a href="{{ route('facility.projects.index') }}" class="nav-link {{ request()->routeIs('facility.projects.*') ? 'active' : '' }}">
                            <i class="fas fa-diagram-project"></i>
                            <span>{{ __('facility.projects.title') }}</span>
                        </a>

                        <a href="{{ route('facility.execution-requests.workspace') }}" class="nav-link {{ request()->routeIs('facility.execution-requests.*') ? 'active' : '' }}">
                            <i class="fas fa-gavel"></i>
                            <span>{{ __('facility.execution_requests.title') }}</span>
                        </a>
                    @endif

                    <a href="{{ route('facility.tasks.index') }}" class="nav-link {{ request()->routeIs('facility.tasks.*') ? 'active' : '' }}">
                        <i class="fas fa-list-check"></i>
                        <span>{{ __('facility.tasks.title') }}</span>
                    </a>

                    <a href="{{ route('facility.accounting.dashboard') }}" class="nav-link {{ request()->routeIs('facility.accounting.*') ? 'active' : '' }}">
                        <i class="fas fa-calculator"></i>
                        <span>{{ __('facility.accounting.title') }}</span>
                    </a>

                    <a href="{{ route('facility.financial.dashboard') }}" class="nav-link {{ request()->routeIs('facility.financial.*') ? 'active' : '' }}">
                        <i class="fas fa-coins"></i>
                        <span>{{ __('facility.financial.title') }}</span>
                    </a>

                    <a href="{{ route('facility.users.index') }}" class="nav-link {{ request()->routeIs('facility.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>{{ __('facility.users.title') }}</span>
                    </a>

                    <a href="{{ route('facility.reports') }}" class="nav-link {{ request()->routeIs('facility.reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>{{ __('facility.reports.title') }}</span>
                    </a>

                    <a href="{{ route('facility.edit') }}" class="nav-link {{ request()->routeIs('facility.edit') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>{{ __('facility.settings.title') }}</span>
                    </a>
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
