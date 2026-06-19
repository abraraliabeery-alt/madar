<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'منطقة العميل - النظام المالي')</title>
    
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('financial-bundle.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('client.financial.dashboard') }}">
                <i class="fas fa-home ms-2"></i>
                منطقة العميل
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.financial.dashboard') ? 'active' : '' }}" 
                           href="{{ route('client.financial.dashboard') }}">
                            <i class="fas fa-tachometer-alt ms-1"></i>
                            لوحة المعلومات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.financial.offers*') ? 'active' : '' }}" 
                           href="{{ route('client.financial.offers') }}">
                            <i class="fas fa-tags ms-1"></i>
                            العروض المتاحة
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.financial.contracts*') ? 'active' : '' }}" 
                           href="{{ route('client.financial.contracts') }}">
                            <i class="fas fa-file-contract ms-1"></i>
                            عقودي
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.financial.invoices') ? 'active' : '' }}" 
                           href="{{ route('client.financial.invoices') }}">
                            <i class="fas fa-file-invoice ms-1"></i>
                            فواتيري
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.financial.payments') ? 'active' : '' }}" 
                           href="{{ route('client.financial.payments') }}">
                            <i class="fas fa-credit-card ms-1"></i>
                            مدفوعاتي
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.financial.summary') ? 'active' : '' }}" 
                           href="{{ route('client.financial.summary') }}">
                            <i class="fas fa-chart-bar ms-1"></i>
                            ملخصي المالي
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user ms-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">الملف الشخصي</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">الصفحة الرئيسية</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">تسجيل الخروج</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- قائمة جانبية للموبايل -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">منطقة العميل</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('client.financial.dashboard') ? 'active' : '' }}" 
                   href="{{ route('client.financial.dashboard') }}">
                    <i class="fas fa-tachometer-alt ms-2"></i>
                    لوحة المعلومات
                </a>
                <a class="nav-link {{ request()->routeIs('client.financial.offers*') ? 'active' : '' }}" 
                   href="{{ route('client.financial.offers') }}">
                    <i class="fas fa-tags ms-2"></i>
                    العروض المتاحة
                </a>
                <a class="nav-link {{ request()->routeIs('client.financial.contracts*') ? 'active' : '' }}" 
                   href="{{ route('client.financial.contracts') }}">
                    <i class="fas fa-file-contract ms-2"></i>
                    عقودي
                </a>
                <a class="nav-link {{ request()->routeIs('client.financial.invoices') ? 'active' : '' }}" 
                   href="{{ route('client.financial.invoices') }}">
                    <i class="fas fa-file-invoice ms-2"></i>
                    فواتيري
                </a>
                <a class="nav-link {{ request()->routeIs('client.financial.payments') ? 'active' : '' }}" 
                   href="{{ route('client.financial.payments') }}">
                    <i class="fas fa-credit-card ms-2"></i>
                    مدفوعاتي
                </a>
                <a class="nav-link {{ request()->routeIs('client.financial.summary') ? 'active' : '' }}" 
                   href="{{ route('client.financial.summary') }}">
                    <i class="fas fa-chart-bar ms-2"></i>
                    ملخصي المالي
                </a>
            </nav>
        </div>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="main-content">
        <div class="container">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show animate-fade-in-up" role="alert">
                    <i class="fas fa-check-circle ms-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate-fade-in-up" role="alert">
                    <i class="fas fa-exclamation-circle ms-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show animate-fade-in-up" role="alert">
                    <i class="fas fa-exclamation-triangle ms-2"></i>
                    <strong>يرجى تصحيح الأخطاء التالية:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>منطقة العميل</h5>
                    <p class="mb-0">إدارة مشاريعك ومدفوعاتك بسهولة وأمان</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">جميع الحقوق محفوظة © {{ date('Y') }}</p>
                    <small class="text-muted">النظام المالي المتكامل</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">جاري التحميل...</span>
        </div>
        <p class="mt-3">جاري معالجة طلبك...</p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Common JS -->
    <script>
        // تهيئة CSRF token لـ AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // دالة لإظهار التحميل
        function showLoading() {
            $('#loadingOverlay').css('display', 'flex');
        }

        // دالة لإخفاء التحميل
        function hideLoading() {
            $('#loadingOverlay').hide();
        }

        // دالة لتنسيق الأرقام
        function formatNumber(num) {
            return new Intl.NumberFormat('ar-SA').format(num);
        }

        // دالة لتنسيق العملة
        function formatCurrency(amount, currency = 'SAR') {
            return new Intl.NumberFormat('ar-SA', {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: 2
            }).format(amount);
        }

        // دالة لتنسيق التاريخ
        function formatDate(dateString) {
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('ar-SA', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }).format(date);
        }

        // تأكيد العمليات
        function confirmAction(message = 'هل أنت متأكد؟') {
            return confirm(message);
        }

        // إخفاء التنبيهات تلقائياً
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
            
            // إضافة رسوم متحركة للعناصر
            $('.stats-card').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
                $(this).addClass('animate-fade-in-up');
            });
            
            hideLoading();
        });

        // تهيئة tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
    
    @yield('scripts')
</body>
</html>
