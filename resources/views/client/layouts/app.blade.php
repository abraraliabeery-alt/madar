@extends('layouts.dashboard')

@php
    $panelRoutes = [
        'notifications_index' => '#',
        'notifications_latest' => '#',
        'search_global' => null,
        'search_results' => null,
    ];
    $panelShowQuickActions = false;
    $panelEnableNotifications = false;
    $panelEnableGlobalSearch = false;
@endphp

@section('panel_title', '')

@section('sidebar')
    <aside class="sidebar sb-nav">
        @include('layouts.partials.sidebar-brand', ['icon' => 'fas fa-user', 'title' => auth()->user()?->name ?? __('client.navigation.dashboard'), 'badge' => __('client.navigation.dashboard')])

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

                    @include('layouts.partials.sidebar-group-open', ['key' => 'activity', 'label' => __('client.navigation.groups.activity'), 'icon' => 'fas fa-house-user'])
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
                        <a href="{{ route('client.contracts.index') }}" class="nav-link {{ request()->routeIs('client.contracts.*') ? 'active' : '' }}">
                            <i class="fas fa-file-contract"></i>
                            <span>{{ __('client.navigation.contracts') }}</span>
                        </a>
                    @endif
                    @include('layouts.partials.sidebar-group-close')

                    @include('layouts.partials.sidebar-group-open', ['key' => 'financial', 'label' => __('client.navigation.groups.financial'), 'icon' => 'fas fa-coins'])
                    <a href="{{ route('client.financial.invoices') }}" class="nav-link {{ request()->routeIs('client.financial.invoices*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i>
                        <span>{{ __('client.navigation.invoices') }}</span>
                    </a>

                    <a href="{{ route('client.financial.payments') }}" class="nav-link {{ request()->routeIs('client.financial.payments*') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>{{ __('client.navigation.payments') }}</span>
                    </a>
                    @include('layouts.partials.sidebar-group-close')
                @endif

                <div class="sidebar-foot">
                    @php($clientUnread = auth()->user()?->unreadNotifications()->count() ?? 0)
                    <a href="{{ route('client.notifications') }}" class="nav-link {{ request()->routeIs('client.notifications*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>{{ __('client.navigation.notifications') }}</span>
                        <span class="badge bg-danger ms-auto notif-count {{ $clientUnread > 0 ? '' : 'd-none' }}">{{ $clientUnread > 99 ? '99+' : $clientUnread }}</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start text-danger border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>{{ __('layout.user_menu.logout') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>
@endsection
