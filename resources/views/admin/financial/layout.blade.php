<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'النظام المالي للأدمن')</title>
    
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <link href="{{ asset('financial-bundle.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.financial.dashboard') }}">
                <i class="fas fa-chart-line ms-2"></i>
                النظام المالي للأدمن
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt ms-1"></i>
                            لوحة الأدمن الرئيسية
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

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.financial.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.financial.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        لوحة المعلومات
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.financial.offers') ? 'active' : '' }}" 
                       href="{{ route('admin.financial.offers') }}">
                        <i class="fas fa-tags"></i>
                        إدارة العروض
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.financial.contracts*') ? 'active' : '' }}" 
                       href="{{ route('admin.financial.contracts') }}">
                        <i class="fas fa-file-contract"></i>
                        إدارة العقود
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.financial.payments') ? 'active' : '' }}" 
                       href="{{ route('admin.financial.payments') }}">
                        <i class="fas fa-credit-card"></i>
                        إدارة المدفوعات
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.financial.reports') ? 'active' : '' }}" 
                       href="{{ route('admin.financial.reports') }}">
                        <i class="fas fa-chart-bar"></i>
                        التقارير الشاملة
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.financial.customers-report') ? 'active' : '' }}" 
                       href="{{ route('admin.financial.customers-report') }}">
                        <i class="fas fa-users"></i>
                        تقرير العملاء
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.financial.owners-report') ? 'active' : '' }}" 
                       href="{{ route('admin.financial.owners-report') }}">
                        <i class="fas fa-user-tie"></i>
                        تقرير الملاك
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.financial.facilities-report') ? 'active' : '' }}" 
                       href="{{ route('admin.financial.facilities-report') }}">
                        <i class="fas fa-building"></i>
                        تقرير المؤسسات
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.financial.accounting-entries') ? 'active' : '' }}" 
                       href="{{ route('admin.financial.accounting-entries') }}">
                        <i class="fas fa-calculator"></i>
                        القيود المحاسبية
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 col-md-9 main-content">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle ms-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle ms-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">جاري التحميل...</span>
        </div>
        <p class="mt-2">جاري معالجة طلبك...</p>
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
            $('#loadingOverlay').show();
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

        // تأكيد الحذف
        function confirmDelete(message = 'هل أنت متأكد من الحذف؟') {
            return confirm(message);
        }

        // تحديث تلقائي للوقت
        function updateTimestamp() {
            $('.timestamp').each(function() {
                const timestamp = $(this).data('timestamp');
                if (timestamp) {
                    $(this).text(formatDate(timestamp));
                }
            });
        }

        // تشغيل التحديث كل دقيقة
        setInterval(updateTimestamp, 60000);

        // تهيئة tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // إخفاء التحميل عند تحميل الصفحة
        $(document).ready(function() {
            hideLoading();
        });
    </script>
    
    @yield('scripts')
</body>
</html>
