<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('facility_management.financial_system')) - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    @if(app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @endif
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/facility-financial.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('facility.financial.dashboard') }}">
                <i class="bi bi-building"></i>
                {{ __('facility_management.facility_financial') }}
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('facility.financial.dashboard') ? 'active' : '' }}" 
                           href="{{ route('facility.financial.dashboard') }}">
                            <i class="bi bi-speedometer2"></i>
                            {{ __('facility_management.dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('facility.financial.offers*') ? 'active' : '' }}" 
                           href="{{ route('facility.financial.offers') }}">
                            <i class="bi bi-tags"></i>
                            {{ __('facility_management.offers') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('facility.financial.contracts*') ? 'active' : '' }}" 
                           href="{{ route('facility.financial.contracts') }}">
                            <i class="bi bi-file-earmark-text"></i>
                            {{ __('facility_management.contracts') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('facility.financial.payments*') ? 'active' : '' }}" 
                           href="{{ route('facility.financial.payments') }}">
                            <i class="bi bi-credit-card"></i>
                            {{ __('facility_management.payments') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('facility.financial.reports*') ? 'active' : '' }}" 
                           href="{{ route('facility.financial.reports') }}">
                            <i class="bi bi-graph-up"></i>
                            {{ __('facility_management.reports') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('facility.financial.accounting-entries*') ? 'active' : '' }}" 
                           href="{{ route('facility.financial.accounting-entries') }}">
                            <i class="bi bi-journal-text"></i>
                            {{ __('facility_management.accounting_entries') }}
                        </a>
                    </li>
                </ul>

                <!-- User Menu -->
                <ul class="navbar-nav">
                    <!-- Notifications -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" 
                           role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                            <li><h6 class="dropdown-header">{{ __('facility_management.notifications') }}</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon bg-primary">
                                            <i class="bi bi-file-earmark"></i>
                                        </div>
                                        <div class="notification-content">
                                            <div class="notification-title">{{ __('facility_management.new_contract') }}</div>
                                            <div class="notification-time">{{ __('facility_management.minutes_ago', ['count' => 5]) }}</div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon bg-success">
                                            <i class="bi bi-credit-card"></i>
                                        </div>
                                        <div class="notification-content">
                                            <div class="notification-title">{{ __('facility_management.payment_received') }}</div>
                                            <div class="notification-time">{{ __('facility_management.hours_ago', ['count' => 2]) }}</div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">{{ __('facility_management.view_all_notifications') }}</a></li>
                        </ul>
                    </li>

                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('facility.profile') }}">
                                <i class="bi bi-person"></i> {{ __('facility_management.profile') }}
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('facility.settings') }}">
                                <i class="bi bi-gear"></i> {{ __('facility_management.settings') }}
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> {{ __('facility_management.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header Section -->
        @hasSection('header')
            <div class="page-header">
                <div class="container-fluid">
                    @yield('header')
                </div>
            </div>
        @endif

        <!-- Content Section -->
        <div class="container-fluid">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
                    <i class="bi bi-exclamation-triangle"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert" id="warningAlert">
                    <i class="bi bi-exclamation-triangle"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert" id="infoAlert">
                    <i class="bi bi-info-circle"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('facility_management.all_rights_reserved') }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">{{ __('facility_management.version') }} 2.0.0</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay d-none">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">{{ __('facility_management.loading') }}</span>
            </div>
            <div class="loading-text">{{ __('facility_management.please_wait') }}</div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer">
        <!-- Toast messages will be added here by JavaScript -->
    </div>

    <!-- Modals Container -->
    <div id="modalsContainer">
        @stack('modals')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="{{ asset('js/facility-financial.js') }}"></script>
    
    @stack('scripts')

    <!-- Initialize -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });

        // Global JavaScript variables
        window.facilityFinancial = {
            baseUrl: '{{ url('/') }}',
            csrfToken: '{{ csrf_token() }}',
            locale: '{{ app()->getLocale() }}',
            rtl: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }},
            routes: {
                dashboard: '{{ route('facility.financial.dashboard') }}',
                offers: '{{ route('facility.financial.offers') }}',
                contracts: '{{ route('facility.financial.contracts') }}',
                payments: '{{ route('facility.financial.payments') }}',
                reports: '{{ route('facility.financial.reports') }}',
                accountingEntries: '{{ route('facility.financial.accounting-entries') }}'
            }
        };
    </script>
</body>
</html>
