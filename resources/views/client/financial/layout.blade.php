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
    <style>
        :root {
            --primary-color: #1e3d59;
            --primary-light: #2e5984;
            --secondary-color: #17a2b8;
            --secondary-light: #20c997;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --border-color: #e9ecef;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            --border-radius: 0.75rem;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* شريط التنقل العلوي */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .navbar-nav .nav-link:hover {
            color: white !important;
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white !important;
        }

        /* المحتوى الرئيسي */
        .main-content {
            padding: 2rem 0;
            min-height: calc(100vh - 160px);
        }

        /* بطاقات الإحصائيات */
        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            border: none;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1.5rem 4rem rgba(0, 0, 0, 0.15);
        }

        .stats-card.success::before {
            background: linear-gradient(90deg, var(--success-color) 0%, #20c997 100%);
        }

        .stats-card.warning::before {
            background: linear-gradient(90deg, var(--warning-color) 0%, #fd7e14 100%);
        }

        .stats-card.danger::before {
            background: linear-gradient(90deg, var(--danger-color) 0%, #e83e8c 100%);
        }

        .stats-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .stats-card .icon.primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .stats-card .icon.success {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
        }

        .stats-card .icon.warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #fd7e14 100%);
        }

        .stats-card .icon.danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #e83e8c 100%);
        }

        .stats-card .number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .stats-card .label {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* البطاقات العامة */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            padding: 1.5rem;
        }

        .card-header h5 {
            margin: 0;
            color: var(--text-dark);
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* الأزرار */
        .btn {
            border-radius: var(--border-radius);
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border: none;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #fd7e14 100%);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #e83e8c 100%);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* الجداول */
        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--light-bg) 0%, #e9ecef 100%);
            color: var(--text-dark);
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background-color: rgba(23, 162, 184, 0.05);
        }

        .table td {
            padding: 0.75rem 1rem;
            border-color: #f1f3f4;
        }

        /* الشارات */
        .badge {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
            border-radius: 2rem;
            font-weight: 500;
        }

        /* التنبيهات */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            border-right: 4px solid;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
            border-right-color: var(--success-color);
            color: #155724;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(253, 126, 20, 0.1) 100%);
            border-right-color: var(--warning-color);
            color: #856404;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(232, 62, 140, 0.1) 100%);
            border-right-color: var(--danger-color);
            color: #721c24;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
            border-right-color: var(--info-color);
            color: #0c5460;
        }

        /* قائمة التنقل الجانبية للموبايل */
        .offcanvas-menu {
            background: white;
            box-shadow: var(--shadow-lg);
        }

        .offcanvas-menu .nav-link {
            color: var(--text-dark);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .offcanvas-menu .nav-link:hover {
            background: var(--light-bg);
            color: var(--primary-color);
        }

        .offcanvas-menu .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        /* تحسينات للشاشات الصغيرة */
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem 0;
            }
            
            .stats-card {
                margin-bottom: 1rem;
                padding: 1.5rem;
            }
            
            .stats-card .number {
                font-size: 1.5rem;
            }
            
            .navbar-brand {
                font-size: 1.25rem;
            }
        }

        /* رسوم متحركة */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.4s ease-out;
        }

        /* شريط التقدم */
        .progress {
            height: 10px;
            border-radius: 10px;
            background-color: #e9ecef;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--success-color) 0%, #20c997 100%);
            transition: width 0.6s ease;
        }

        /* مودالات */
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-bottom: none;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        /* حالة فارغة */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h4 {
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        /* التحميل */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            flex-direction: column;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
        }

        /* footer */
        .footer {
            background: linear-gradient(135deg, var(--text-dark) 0%, var(--primary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer a:hover {
            color: white;
        }
    </style>
    
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
                    <p class="mb-0">إدارة عقاراتك ومدفوعاتك بسهولة وأمان</p>
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
