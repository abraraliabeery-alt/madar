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
    $facility = Auth::user()?->mainFacility()?->load('facilityCategory');
    $facilityMode = $facility?->facilityCategory?->platform_mode;
    $facilityMenuConfigured = \App\Models\MenuItem::where('panel', 'facility')->exists();
    $facilityMenu = $facilityMenuConfigured
        ? \App\Services\MenuService::forPanel('facility', $facilityMode, $facility)
        : [];

    // التصنيفات الفرعية للعقارات (تظهر تحت عنصر "العقارات" في القائمة)
    $reRoot = \App\Models\Category::whereNull('parent_id')
        ->whereHas('translations', function ($q) {
            $q->where('name', 'Real Estate')->orWhere('name', 'like', '%عقار%');
        })
        ->with('children.translations')
        ->first();
    if (!$reRoot) {
        $reRoot = \App\Models\Category::whereNull('parent_id')
            ->whereHas('children')
            ->with('children.translations')
            ->first();
    }
    $realEstateSubCategories = $reRoot?->children ?? collect();
@endphp

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        const brand = {
            50: 'color-mix(in srgb, var(--brand-brown) 6%, var(--brand-bg))',
            100: 'color-mix(in srgb, var(--brand-brown) 12%, var(--brand-bg))',
            200: 'color-mix(in srgb, var(--brand-brown) 25%, var(--brand-bg))',
            300: 'color-mix(in srgb, var(--brand-brown) 40%, var(--brand-bg))',
            400: 'color-mix(in srgb, var(--brand-brown) 60%, var(--brand-bg))',
            500: 'color-mix(in srgb, var(--brand-brown) 85%, var(--brand-bg))',
            600: 'var(--brand-brown)',
            700: 'color-mix(in srgb, var(--brand-brown) 80%, #000)',
            800: 'color-mix(in srgb, var(--brand-brown) 65%, #000)',
            900: 'color-mix(in srgb, var(--brand-brown) 50%, #000)'
        };
        const gray = {
            50: 'color-mix(in srgb, var(--brand-fg) 3%, var(--brand-bg))',
            100: 'color-mix(in srgb, var(--brand-fg) 7%, var(--brand-bg))',
            200: 'color-mix(in srgb, var(--brand-fg) 15%, var(--brand-bg))',
            300: 'color-mix(in srgb, var(--brand-fg) 30%, var(--brand-bg))',
            400: 'color-mix(in srgb, var(--brand-fg) 45%, var(--brand-bg))',
            500: 'color-mix(in srgb, var(--brand-fg) 60%, var(--brand-bg))',
            600: 'color-mix(in srgb, var(--brand-fg) 75%, var(--brand-bg))',
            700: 'color-mix(in srgb, var(--brand-fg) 88%, var(--brand-bg))',
            800: 'color-mix(in srgb, var(--brand-fg) 95%, var(--brand-bg))',
            900: 'var(--brand-fg)'
        };
        tailwind.config = {
            corePlugins: { preflight: false },
            theme: {
                extend: {
                    colors: {
                        blue: brand, indigo: brand, purple: brand, sky: brand, teal: brand,
                        gray: gray, white: 'var(--brand-bg)', black: 'var(--brand-fg)'
                    }
                }
            }
        };
    </script>
@endpush

@section('panel_title', '')

@section('sidebar')
    @php
        $facilityGroupIcons = [
            'main' => 'fas fa-house',
            'real_estate' => 'fas fa-building',
            'contracting' => 'fas fa-helmet-safety',
            'financial' => 'fas fa-coins',
            'accounting' => 'fas fa-calculator',
            'reports' => 'fas fa-chart-line',
            'administration' => 'fas fa-users-gear',
            'hr' => 'fas fa-id-badge',
        ];
        $facilityGroup = fn (string $key) => [
            'key' => $key,
            'label' => __('facility.sidebar.groups.' . $key),
            'icon' => $facilityGroupIcons[$key] ?? null,
        ];
    @endphp
    <aside class="sidebar sb-nav">
        @include('layouts.partials.sidebar-brand', [
            'icon' => 'fas fa-building',
            'title' => $facility?->name ?? __('facility.dashboard.title'),
            'badge' => $facility?->facilityCategory?->display_name,
        ])

        <div class="nav-scroll">
            <div class="nav flex-column">
                @if($facilityMenuConfigured && count($facilityMenu))
                    @php($currentGroup = null)
                    @foreach($facilityMenu as $item)
                        @if(($item['group'] ?? null) !== $currentGroup)
                            @if($currentGroup !== null)
                                @include('layouts.partials.sidebar-group-close')
                            @endif
                            @php($currentGroup = $item['group'])
                            @if($currentGroup !== null)
                                @include('layouts.partials.sidebar-group-open', $facilityGroup($currentGroup))
                            @endif
                        @endif
                        @if($item['key'] === 'products' && $realEstateSubCategories->isNotEmpty())
                            @php($productsOpen = request()->routeIs('facility.products.*') || request()->routeIs('facility.categories.products'))
                            <div class="sb-collapsible sidebar-sub" data-sb-key="s:products">
                                <button type="button" class="nav-link {{ $productsOpen ? 'active' : '' }}" data-sb-toggle aria-expanded="false">
                                    <i class="{{ $item['icon'] ?? 'fas fa-box' }}"></i>
                                    <span>{{ $item['label'] }}</span>
                                    <i class="fas fa-chevron-down sidebar-group-chevron ms-auto"></i>
                                </button>
                                <div class="sidebar-group-items"><div>
                                    <a href="{{ route('facility.products.index') }}" class="nav-link nav-sub {{ request()->routeIs('facility.products.index') ? 'active' : '' }}">
                                        <i class="fas fa-list"></i>
                                        <span>{{ __('facility.sidebar.all_products') }}</span>
                                    </a>
                                    @foreach($realEstateSubCategories as $subCategory)
                                        <a href="{{ route('facility.categories.products', $subCategory) }}"
                                           class="nav-link nav-sub {{ request()->routeIs('facility.categories.products') && request()->route('category')?->id === $subCategory->id ? 'active' : '' }}">
                                            <i class="fas fa-angle-left"></i>
                                            <span>{{ $subCategory->getTranslatedName() }}</span>
                                        </a>
                                    @endforeach
                                </div></div>
                            </div>
                            @continue
                        @endif
                        @php($isActive = $item['route_name'] ? (request()->routeIs($item['route_name']) || (str_ends_with($item['route_name'], '.index') && request()->routeIs(substr($item['route_name'], 0, -6) . '.*'))) : false)
                        <a href="{{ $item['href'] }}" class="nav-link {{ $isActive ? 'active' : '' }} {{ !empty($item['sub']) ? 'nav-sub' : '' }}">
                            <i class="{{ $item['icon'] ?? 'fas fa-circle' }}"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                    @if($currentGroup !== null)
                        @include('layouts.partials.sidebar-group-close')
                    @endif
                @else
                <a href="{{ route('facility.dashboard') }}" class="nav-link {{ request()->routeIs('facility.dashboard') || request()->routeIs('facility.index') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>{{ __('facility.dashboard.title') }}</span>
                </a>

                <a href="{{ route('facility.group') }}" class="nav-link {{ request()->routeIs('facility.group') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>
                    <span>لوحة المجموعة</span>
                </a>

                <a href="{{ route('facility.profile') }}" class="nav-link {{ request()->routeIs('facility.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>{{ __('facility.sidebar.profile') }}</span>
                </a>

                @if($facility?->facilityCategory?->isRealEstate() ?? true)
                    @include('layouts.partials.sidebar-group-open', $facilityGroup('real_estate'))

                    <a href="{{ route('facility.products.index') }}" class="nav-link {{ request()->routeIs('facility.products.*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>{{ __('facility.sidebar.products') }}</span>
                    </a>

                    <a href="{{ route('facility.owners.index') }}" class="nav-link {{ request()->routeIs('facility.owners.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span>{{ __('facility.sidebar.owners') }}</span>
                    </a>

                    <a href="{{ route('facility.tenants.index') }}" class="nav-link {{ request()->routeIs('facility.tenants.*') ? 'active' : '' }}">
                        <i class="fas fa-user-check"></i>
                        <span>{{ __('facility.sidebar.tenants') }}</span>
                    </a>

                    <a href="{{ route('facility.maintenance.index') }}" class="nav-link {{ request()->routeIs('facility.maintenance.*') ? 'active' : '' }}">
                        <i class="fas fa-screwdriver-wrench"></i>
                        <span>{{ __('facility.sidebar.maintenance') }}</span>
                    </a>

                    <a href="{{ route('facility.leads.index') }}" class="nav-link {{ request()->routeIs('facility.leads.*') ? 'active' : '' }}">
                        <i class="fas fa-user-plus"></i>
                        <span>{{ __('facility.sidebar.leads') }}</span>
                    </a>

                    <a href="{{ route('facility.bookings.index') }}" class="nav-link {{ request()->routeIs('facility.bookings.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>{{ __('facility.sidebar.bookings') }}</span>
                    </a>

                    <a href="{{ route('facility.offers.index') }}" class="nav-link {{ request()->routeIs('facility.offers.*') ? 'active' : '' }}">
                        <i class="fas fa-tag"></i>
                        <span>{{ __('facility.sidebar.offers') }}</span>
                    </a>
                    @include('layouts.partials.sidebar-group-close')
                @endif

                @if($facility?->facilityCategory?->isContracting() ?? true)
                    @include('layouts.partials.sidebar-group-open', $facilityGroup('contracting'))

                    <a href="{{ route('facility.execution-requests.workspace') }}" class="nav-link {{ request()->routeIs('facility.execution-requests.*') ? 'active' : '' }}">
                        <i class="fas fa-gavel"></i>
                        <span>{{ __('facility.execution_requests.title') }}</span>
                    </a>

                    <a href="{{ route('facility.quotes.index') }}" class="nav-link {{ request()->routeIs('facility.quotes.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>{{ __('facility.quotes.title') }}</span>
                    </a>

                    <a href="{{ route('facility.tenders.index') }}" class="nav-link {{ request()->routeIs('facility.tenders.*') ? 'active' : '' }}">
                        <i class="fas fa-magnifying-glass-chart"></i>
                        <span>{{ __('facility.tenders.title') }}</span>
                    </a>
                    @include('layouts.partials.sidebar-group-close')
                @endif

                @include('layouts.partials.sidebar-group-open', $facilityGroup('financial'))

                <a href="{{ route('facility.financial.dashboard') }}" class="nav-link {{ request()->routeIs('facility.financial.*') ? 'active' : '' }}">
                    <i class="fas fa-coins"></i>
                    <span>{{ __('facility.financial.title') }}</span>
                </a>

                <a href="{{ route('facility.contracts.index') }}" class="nav-link {{ request()->routeIs('facility.contracts.*') ? 'active' : '' }}">
                    <i class="fas fa-file-signature"></i>
                    <span>{{ __('facility.sidebar.contracts') }}</span>
                </a>

                <a href="{{ route('facility.invoices.index') }}" class="nav-link {{ request()->routeIs('facility.invoices.*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice"></i>
                    <span>{{ __('facility.sidebar.invoices') }}</span>
                </a>

                <a href="{{ route('facility.payments.index') }}" class="nav-link {{ request()->routeIs('facility.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>{{ __('facility.sidebar.payments') }}</span>
                </a>

                <a href="{{ route('facility.accounting.dashboard') }}" class="nav-link {{ request()->routeIs('facility.accounting.*') ? 'active' : '' }}">
                    <i class="fas fa-calculator"></i>
                    <span>{{ __('facility.accounting.title') }}</span>
                </a>
                @include('layouts.partials.sidebar-group-close')

                @include('layouts.partials.sidebar-group-open', $facilityGroup('administration'))

                <a href="{{ route('facility.tasks.index') }}" class="nav-link {{ request()->routeIs('facility.tasks.*') ? 'active' : '' }}">
                    <i class="fas fa-list-check"></i>
                    <span>{{ __('facility.tasks.title') }}</span>
                </a>

                <a href="{{ route('facility.users.index') }}" class="nav-link {{ request()->routeIs('facility.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>{{ __('facility.users.title') }}</span>
                </a>

                <a href="{{ route('facility.appointments.index') }}" class="nav-link {{ request()->routeIs('facility.appointments.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar"></i>
                    <span>{{ __('facility.sidebar.appointments') }}</span>
                </a>

                <a href="{{ route('facility.reports') }}" class="nav-link {{ request()->routeIs('facility.reports*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>{{ __('facility.reports.title') }}</span>
                </a>

                <a href="{{ route('facility.statistics') }}" class="nav-link {{ request()->routeIs('facility.statistics') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>{{ __('facility.sidebar.statistics') }}</span>
                </a>

                <a href="{{ route('facility.smart-broker.index') }}" class="nav-link {{ request()->routeIs('facility.smart-broker.*') ? 'active' : '' }}">
                    <i class="fas fa-robot"></i>
                    <span>{{ __('facility.sidebar.smart_broker') }}</span>
                </a>

                <a href="{{ route('facility.customization.edit', $facility) }}" class="nav-link {{ request()->routeIs('facility.customization.*') ? 'active' : '' }}">
                    <i class="fas fa-palette"></i>
                    <span>{{ __('facility.sidebar.customization') }}</span>
                </a>

                <a href="{{ route('facility.settings') }}" class="nav-link {{ request()->routeIs('facility.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>{{ __('facility.settings.title') }}</span>
                </a>
                @include('layouts.partials.sidebar-group-close')
                @endif

                <div class="sidebar-foot">
                    @php($unreadNotifications = auth()->user()?->unreadNotifications()->count() ?? 0)
                    <a href="{{ route('facility.notifications') }}" class="nav-link {{ request()->routeIs('facility.notifications*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>{{ __('facility.sidebar.notifications') }}</span>
                        <span class="badge bg-danger ms-auto notif-count {{ $unreadNotifications > 0 ? '' : 'd-none' }}">{{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}</span>
                    </a>
                    <a href="{{ route('facility.change-password') }}" class="nav-link {{ request()->routeIs('facility.change-password') ? 'active' : '' }}">
                        <i class="fas fa-lock"></i>
                        <span>{{ __('facility.sidebar.change_password') }}</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start text-danger border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>{{ __('facility.sidebar.logout') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- بحث شامل Ctrl+K --}}
    <div id="globalSearchOverlay" class="d-none" style="position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1080">
        <div class="bg-white rounded shadow-lg mx-auto mt-5" style="max-width:560px">
            <div class="d-flex align-items-center gap-2 border-bottom px-3 py-2">
                <i class="fas fa-magnifying-glass text-muted"></i>
                <input id="globalSearchInput" type="text" class="form-control border-0 shadow-none"
                       placeholder="ابحث: عقار، عقد، مستأجر، مالك، عميل..." autocomplete="off">
                <kbd class="text-muted small">ESC</kbd>
            </div>
            <div id="globalSearchResults" class="p-2" style="max-height:340px;overflow-y:auto">
                <div class="text-muted small text-center py-4">اكتب حرفين على الأقل</div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const overlay = document.getElementById('globalSearchOverlay');
            const input = document.getElementById('globalSearchInput');
            const results = document.getElementById('globalSearchResults');
            const url = @json(route('facility.search'));
            let timer = null;

            function open() { overlay.classList.remove('d-none'); input.value = ''; render('اكتب حرفين على الأقل'); input.focus(); }
            function close() { overlay.classList.add('d-none'); }
            function render(html) { results.innerHTML = html; }

            document.getElementById('globalSearchBtn')?.addEventListener('click', open);
            overlay.addEventListener('click', e => { if (e.target === overlay) close(); });
            document.addEventListener('keydown', e => {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); overlay.classList.contains('d-none') ? open() : close(); }
                if (e.key === 'Escape') close();
            });

            input.addEventListener('input', () => {
                clearTimeout(timer);
                const q = input.value.trim();
                if (q.length < 2) { render('<div class="text-muted small text-center py-4">اكتب حرفين على الأقل</div>'); return; }
                timer = setTimeout(() => {
                    fetch(url + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(items => {
                            if (!items.length) { render('<div class="text-muted small text-center py-4">لا نتائج</div>'); return; }
                            render(items.map(i =>
                                `<a href="${i.url}" class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none text-dark"
                                     onmouseover="this.style.background='var(--bs-light)'" onmouseout="this.style.background=''">
                                    <i class="fas ${i.icon} text-${i.color}"></i>
                                    <span class="flex-grow-1">${i.label}</span>
                                    <span class="badge bg-light text-muted border">${i.type}</span>
                                 </a>`
                            ).join(''));
                        });
                }, 250);
            });
        })();
    </script>
@endsection
