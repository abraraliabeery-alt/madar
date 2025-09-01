<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - لوحة التحكم</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @stack('styles')

    <style>
        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }

        body.dark-mode .sidebar {
            background-color: #2d2d2d;
            border-right-color: #404040;
        }

        body.dark-mode .sidebar-header {
            background-color: #1f1f1f;
            border-bottom-color: #404040;
        }

        body.dark-mode .sidebar .nav-link {
            color: #e0e0e0;
        }

        body.dark-mode .sidebar .nav-link:hover,
        body.dark-mode .sidebar .nav-link.active {
            background-color: #404040;
            color: #ffffff;
        }

        body.dark-mode .main-header {
            background-color: #2d2d2d;
            border-bottom-color: #404040;
        }

        body.dark-mode .card {
            background-color: #2d2d2d;
            border-color: #404040;
        }

        body.dark-mode .card-header {
            background-color: #1f1f1f;
            border-bottom-color: #404040;
        }

        body.dark-mode .form-control {
            background-color: #404040;
            border-color: #555555;
            color: #e0e0e0;
        }

        body.dark-mode .form-control:focus {
            background-color: #404040;
            border-color: #007bff;
            color: #e0e0e0;
        }

        body.dark-mode .btn-light {
            background-color: #404040;
            border-color: #555555;
            color: #e0e0e0;
        }

        body.dark-mode .btn-light:hover {
            background-color: #555555;
            border-color: #666666;
            color: #ffffff;
        }

        body.dark-mode .dropdown-menu {
            background-color: #2d2d2d;
            border-color: #404040;
        }

        body.dark-mode .dropdown-item {
            color: #e0e0e0;
        }

        body.dark-mode .dropdown-item:hover {
            background-color: #404040;
            color: #ffffff;
        }

        body.dark-mode .table {
            color: #e0e0e0;
        }

        body.dark-mode .table th,
        body.dark-mode .table td {
            border-color: #404040;
        }

        body.dark-mode .alert {
            background-color: #2d2d2d;
            border-color: #404040;
        }

        body.dark-mode .text-muted {
            color: #b0b0b0 !important;
        }

        body.dark-mode .border-top,
        body.dark-mode .border-bottom {
            border-color: #404040 !important;
        }

        /* Dark mode transitions */
        body,
        .sidebar,
        .main-header,
        .card,
        .form-control,
        .btn-light,
        .dropdown-menu {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        /* Notification dropdown styles */
        .dropdown-item.notification-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.2s ease;
        }

        .dropdown-item.notification-item:last-child {
            border-bottom: none;
        }

        .dropdown-item.notification-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item.notification-item.unread {
            background-color: #f0f8ff;
        }

        .dropdown-item.notification-item.read {
            opacity: 0.7;
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f8f9fa;
        }

        .badge-sm {
            font-size: 0.75em;
            padding: 0.25em 0.5em;
        }

        /* Dark mode notification styles */
        body.dark-mode .dropdown-item.notification-item:hover {
            background-color: #404040;
        }

        body.dark-mode .dropdown-item.notification-item.unread {
            background-color: #1a2332;
        }

        body.dark-mode .notification-icon {
            background-color: #404040;
        }

        /* Search Box Styles */
        .search-box {
            position: relative;
            min-width: 400px;
            width: 450px;
        }

        .search-box .form-control {
            padding-right: 40px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .search-box .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #007bff;
        }

        .search-box .fas.fa-search {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1050;
            max-height: 500px;
            overflow: hidden;
        }

        .search-results-header {
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .search-results-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .search-results-footer {
            padding: 15px 20px;
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
            text-align: center;
        }

        .search-result-item {
            padding: 16px 20px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
            color: inherit;
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
            transform: translateX(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-result-content {
            flex: 1;
            min-width: 0;
            padding: 4px 0;
        }

        .search-result-title {
            font-weight: 600;
            margin-bottom: 4px;
            color: #212529;
            font-size: 0.95rem;
            line-height: 1.3;
        }

        .search-result-subtitle {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .search-result-type {
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            white-space: nowrap;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .search-loading {
            padding: 20px;
            text-align: center;
            color: #6c757d;
        }

        .search-no-results {
            padding: 20px;
            text-align: center;
            color: #6c757d;
        }

        /* Dark mode search styles */
        body.dark-mode .search-results {
            background: #2d2d2d;
            border-color: #404040;
        }

        body.dark-mode .search-results-header,
        body.dark-mode .search-results-footer {
            background-color: #1f1f1f;
            border-color: #404040;
        }

        body.dark-mode .search-result-item:hover {
            background-color: #404040;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .search-result-item {
            border-color: #404040;
        }

        body.dark-mode .search-result-title {
            color: #e0e0e0;
        }

        body.dark-mode .search-result-subtitle {
            color: #b0b0b0;
        }

        body.dark-mode .search-result-type {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            color: #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Responsive search */
        @media (max-width: 1200px) {
            .search-box {
                min-width: 350px;
                width: 400px;
            }
        }
        
        @media (max-width: 768px) {
            .search-box {
                min-width: 250px;
                width: 300px;
            }
            
            .search-results {
                left: -50px;
                right: -50px;
            }
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-spinner"></div>
    </div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0">{{ config('app.name') }}</h4>
            <button class="btn btn-light d-lg-none" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- User Profile -->
        <div class="p-3">
            <div class="d-flex align-items-center">
                <div class="avatar ms-2">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset(auth()->user()->avatar) }}" alt="avatar">
                    @else
                        <div class="avatar-placeholder">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="flex-fill">
                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                    <small class="text-muted">{{ auth()->user()->email }}</small>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="nav-scroll">
            <div class="nav flex-column">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>لوحة التحكم</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>المستخدمين</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i>
                    <span>الأدوار</span>
                </a>
                <a href="{{ route('admin.facilities.index') }}" class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>المنشآت</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>التصنيفات</span>
                </a>
                <a href="{{ route('admin.features.index') }}" class="nav-link {{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>المميزات</span>
                </a>
                <a href="{{ route('admin.attributes.index') }}" class="nav-link {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>الخصائص</span>
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>الأسئلة الشائعة</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>المنتجات</span>
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>الحجوزات</span>
                </a>
                <a href="{{ route('admin.contracts.index') }}" class="nav-link {{ request()->routeIs('admin.contracts.*') ? 'active' : '' }}">
                    <i class="fas fa-file-contract"></i>
                    <span>العقود</span>
                </a>
                <a href="{{ route('admin.notifications') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>الإشعارات</span>
                </a>
            </div>
        </div>

        <!-- Settings -->
        <div class="p-3 border-top">
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.settings') }}" class="btn btn-light w-100 text-start">
                    <i class="fas fa-cog ms-2"></i>الإعدادات
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-light w-100 text-start text-danger">
                        <i class="fas fa-sign-out-alt ms-2"></i>تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="main-header">
            <button class="btn btn-light d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="navbar-tools">
                <!-- Search -->
                <div class="search-box position-relative">
                    <input type="text" class="form-control" id="globalSearch" placeholder="بحث في المستخدمين، المنشآت، المنتجات..." autocomplete="off">
                    <i class="fas fa-search"></i>
                    
                    <!-- Search Results Dropdown -->
                    <div class="search-results" id="searchResults" style="display: none;">
                        <div class="search-results-header">
                            <h6 class="mb-0">نتائج البحث</h6>
                            <small class="text-muted" id="searchResultsCount">0 نتيجة</small>
                        </div>
                        <div class="search-results-body" id="searchResultsBody">
                            <!-- Results will be populated here -->
                        </div>
                        <div class="search-results-footer">
                            <a href="#" id="viewAllResults" class="text-decoration-none">
                                <i class="fas fa-search ms-1"></i>عرض كل النتائج
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="dropdown">
                    <button class="btn btn-light" data-bs-toggle="dropdown">
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">إضافة جديد</h6>
                        <a href="{{ route('admin.users.create') }}" class="dropdown-item">
                            <i class="fas fa-user-plus ms-2"></i>مستخدم جديد
                        </a>
                        <a href="{{ route('admin.attributes.create') }}" class="dropdown-item">
                            <i class="fas fa-tag ms-2"></i>خاصية جديدة
                        </a>
                        <a href="{{ route('admin.faqs.create') }}" class="dropdown-item">
                            <i class="fas fa-question-circle ms-2"></i>سؤال جديد
                        </a>
                        <a href="{{ route('admin.products.create') }}" class="dropdown-item">
                            <i class="fas fa-box-open ms-2"></i>منتج جديد
                        </a>
                        <a href="{{ route('admin.bookings.create') }}" class="dropdown-item">
                            <i class="fas fa-calendar-plus ms-2"></i>حجز جديد
                        </a>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn btn-light position-relative" data-bs-toggle="dropdown" id="notificationDropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationCount">
                            0
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" style="width: 350px;">
                        <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                            الإشعارات
                            <a href="{{ route('admin.notifications') }}" class="text-decoration-none small">عرض الكل</a>
                        </h6>
                        <div id="notificationsList">
                            <div class="text-center py-3">
                                <i class="fas fa-spinner fa-spin"></i>
                                <p class="mb-0 mt-2">جاري التحميل...</p>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.notifications') }}" class="dropdown-item text-center">عرض كل الإشعارات</a>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <button class="btn btn-light" id="themeToggle">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle ms-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle ms-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Content -->
        <div class="fade-in">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/ar.min.js"></script>

    @stack('scripts')

    <script>
    $(document).ready(function() {
        // تعيين اللغة العربية لـ moment.js
        moment.locale('ar');

        // إخفاء Preloader
        setTimeout(function() {
            $('.preloader').fadeOut(300);
        }, 500);

        // تهيئة Select2
        if ($('.form-select').length) {
            $('.form-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                dir: 'rtl',
                language: 'ar'
            });
        }

        // تهيئة Summernote
        if ($('.summernote').length) {
            $('.summernote').summernote({
                height: 200,
                lang: 'ar-AR',
                direction: 'rtl',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        let form = new FormData();
                        form.append('image', files[0]);
                        form.append('_token', '{{ csrf_token() }}');

                        $.ajax({
                            url: '{{ route("admin.upload.image") }}',
                            method: 'POST',
                            data: form,
                            processData: false,
                            contentType: false,
                            success: function(url) {
                                let image = $('<img>').attr('src', url);
                                $('.summernote').summernote('insertNode', image[0]);
                            }
                        });
                    }
                }
            });
        }

        // تهيئة DataTables
        if ($('.datatable').length && !$.fn.DataTable.isDataTable('.datatable')) {
            $('.datatable').each(function() {
                $(this).DataTable({
                    language: {
                        url: '{{ asset("js/datatables-ar.json") }}'
                    },
                    dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                         "<'row'<'col-sm-12'tr>>" +
                         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [
                        {
                            extend: 'collection',
                            text: '<i class="fas fa-download ms-1"></i>تصدير',
                            className: 'btn btn-primary dropdown-toggle mx-2',
                            buttons: [
                                {
                                    extend: 'excel',
                                    text: '<i class="fas fa-file-excel ms-1"></i>Excel',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: ':not(.no-export)'
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    text: '<i class="fas fa-file-pdf ms-1"></i>PDF',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: ':not(.no-export)'
                                    }
                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fas fa-print ms-1"></i>طباعة',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: ':not(.no-export)'
                                    }
                                }
                            ]
                        }
                    ],
                    lengthMenu: [[10, 25, 50, 100, -1], ['10', '25', '50', '100', 'الكل']],
                    pageLength: 25,
                    order: [],
                    responsive: true,
                    autoWidth: false,
                    searching: true,
                    ordering: true,
                    processing: true,
                    serverSide: false,
                    stateSave: true,
                    scrollX: true,
                    fixedHeader: true,
                    orderCellsTop: true,
                    initComplete: function(settings, json) {
                        // تحسين محاذاة العناصر للغة العربية
                        $('.dataTables_filter').css('text-align', 'left');
                        $('.dataTables_length').css('text-align', 'right');
                        $('.dt-buttons').css('float', 'right');

                        // إضافة الفلترة المتقدمة
                        this.api().columns().every(function() {
                            let column = this;
                            let header = $(column.header());

                            if (header.hasClass('filterable')) {
                                let input = $('<input type="text" class="form-control form-control-sm mt-2" placeholder="بحث...">')
                                    .appendTo(header)
                                    .on('keyup change', function() {
                                        if (column.search() !== this.value) {
                                            column.search(this.value).draw();
                                        }
                                    });
                            }
                        });
                    }
                });
            });
        }

        // تهيئة Sortable
        if (typeof Sortable !== 'undefined' && $('.sortable').length) {
            $('.sortable').each(function() {
                new Sortable(this, {
                    animation: 150,
                    ghostClass: 'sortable-ghost'
                });
            });
        }

        // تهيئة SweetAlert للحذف
        $(document).on('click', '.delete-confirm', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لا يمكن التراجع عن هذا الإجراء!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // معاينة الصور المرفقة
        $(document).on('change', 'input[type="file"]', function(e) {
            let file = e.target.files[0];
            let preview = $(this).siblings('.preview');

            if (file && preview.length) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    preview.html(`<img src="${e.target.result}" class="img-thumbnail">`);
                };
                reader.readAsDataURL(file);
            }
        });

        // تهيئة Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // إخفاء رسائل التنبيه تلقائياً
        $('.alert').delay(5000).fadeOut(500);

        // تبديل القائمة الجانبية
        $('#sidebarToggle').on('click', function() {
            $('.sidebar').addClass('show');
            $('.main-content').addClass('sidebar-open');
            $('.main-header').addClass('sidebar-open');
            $('body').addClass('sidebar-open');
            localStorage.setItem('adminSidebarOpen', 'true');
        });

        $('#sidebarClose').on('click', function() {
            $('.sidebar').removeClass('show');
            $('.main-content').removeClass('sidebar-open');
            $('.main-header').removeClass('sidebar-open');
            $('body').removeClass('sidebar-open');
            localStorage.setItem('adminSidebarOpen', 'false');
        });

        // إغلاق القائمة عند النقر خارجها على الموبايل
        $(document).on('click', function(e) {
            if ($('body').hasClass('sidebar-open') && !$(e.target).closest('.sidebar').length && !$(e.target).closest('#sidebarToggle').length) {
                $('.sidebar').removeClass('show');
                $('.main-content').removeClass('sidebar-open');
                $('.main-header').removeClass('sidebar-open');
                $('body').removeClass('sidebar-open');
                localStorage.setItem('adminSidebarOpen', 'false');
            }
        });

        // تبديل الوضع الليلي
        $('#themeToggle').on('click', function() {
            $('body').toggleClass('dark-mode');
            let icon = $(this).find('i');
            let isDarkMode = $('body').hasClass('dark-mode');
            
            // حفظ التفضيل في localStorage
            localStorage.setItem('adminDarkMode', isDarkMode);
            
            if (isDarkMode) {
                icon.removeClass('fa-moon').addClass('fa-sun');
            } else {
                icon.removeClass('fa-sun').addClass('fa-moon');
            }
        });

        // استعادة تفضيل الوضع الليلي عند تحميل الصفحة
        function restoreDarkMode() {
            const savedDarkMode = localStorage.getItem('adminDarkMode');
            if (savedDarkMode === 'true') {
                $('body').addClass('dark-mode');
                $('#themeToggle i').removeClass('fa-moon').addClass('fa-sun');
            }
        }

        // استعادة حالة القائمة الجانبية عند تحميل الصفحة
        function restoreSidebarState() {
            const savedSidebarState = localStorage.getItem('adminSidebarOpen');
            if (savedSidebarState === 'true') {
                $('.sidebar').addClass('show');
                $('.main-content').addClass('sidebar-open');
                $('.main-header').addClass('sidebar-open');
                $('body').addClass('sidebar-open');
            }
        }

        // استدعاء الدالة عند تحميل الصفحة
        restoreDarkMode();
        restoreSidebarState();

        // تحميل الإشعارات
        function loadNotifications() {
            $.ajax({
                url: '{{ route("admin.notifications.latest") }}',
                method: 'GET',
                success: function(response) {
                    updateNotificationCount(response.notifications.filter(n => !n.read_at).length);
                    updateNotificationsList(response.notifications);
                },
                error: function() {
                    $('#notificationsList').html(`
                        <div class="text-center py-3">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <p class="mb-0 mt-2">خطأ في تحميل الإشعارات</p>
                        </div>
                    `);
                }
            });
        }

        // تحديث عدد الإشعارات
        function updateNotificationCount(count) {
            const badge = $('#notificationCount');
            if (count > 0) {
                badge.text(count).show();
                if (count > 99) {
                    badge.text('99+');
                }
            } else {
                badge.hide();
            }
        }

        // تحديث قائمة الإشعارات
        function updateNotificationsList(notifications) {
            const container = $('#notificationsList');
            
            if (notifications.length === 0) {
                container.html(`
                    <div class="text-center py-3">
                        <i class="fas fa-bell-slash text-muted"></i>
                        <p class="mb-0 mt-2">لا توجد إشعارات جديدة</p>
                    </div>
                `);
                return;
            }

            let html = '';
            notifications.slice(0, 5).forEach(function(notification) {
                const isRead = notification.read_at;
                const timeAgo = moment(notification.created_at).fromNow();
                
                html += `
                    <a href="#" class="dropdown-item notification-item ${isRead ? 'read' : 'unread'}" data-notification-id="${notification.id}">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="notification-icon me-2">
                                    ${getNotificationIcon(notification.data?.type || '')}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 small">${notification.data?.message || 'إشعار جديد'}</p>
                                <small class="text-muted">${timeAgo}</small>
                            </div>
                            ${!isRead ? '<span class="badge bg-primary badge-sm">جديد</span>' : ''}
                        </div>
                    </a>
                `;
            });
            
            container.html(html);
        }

        // الحصول على أيقونة الإشعار
        function getNotificationIcon(type) {
            const icons = {
                'booking_created': '<i class="fas fa-calendar-check text-primary"></i>',
                'new_user': '<i class="fas fa-user-plus text-success"></i>',
                'new_product': '<i class="fas fa-box text-info"></i>',
                'new_facility': '<i class="fas fa-building text-warning"></i>',
                'default': '<i class="fas fa-bell text-secondary"></i>'
            };
            return icons[type] || icons.default;
        }

        // تحميل الإشعارات عند فتح القائمة
        $('#notificationDropdown').on('click', function() {
            loadNotifications();
        });

        // تحديث الإشعارات كل دقيقة
        setInterval(function() {
            if ($('#notificationDropdown').hasClass('show')) {
                loadNotifications();
            }
        }, 60000);

        // تحميل الإشعارات عند تحميل الصفحة
        loadNotifications();

        // Global search functionality
        let searchTimeout;
        
        // Search input event handler
        $('#globalSearch').on('input', function() {
            const query = $(this).val().trim();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                hideSearchResults();
                return;
            }

            // Debounce search requests
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 300);
        });
        
        function performSearch() {
            const query = $('#globalSearch').val().trim();
            
            if (query.length < 2) {
                hideSearchResults();
                return;
            }

            showSearchLoading();

            $.ajax({
                url: '/admin/search/global',
                method: 'GET',
                data: { q: query },
                success: function(response) {
                    if (response.results && response.results.length > 0) {
                        displaySearchResults(response.results, query);
                        // Update view all results link
                        $('#viewAllResults').attr('href', '/admin/search/results?q=' + encodeURIComponent(query));
                        
                        // Debug: log the URL
                        console.log('View all results URL:', '/admin/search/results?q=' + encodeURIComponent(query));
                    } else {
                        showSearchError('لا توجد نتائج');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Search error:', error);
                    console.error('Response:', xhr.responseText);
                    showSearchError('حدث خطأ في البحث');
                }
            });
        }

        // Display search results
        function displaySearchResults(results, query) {
            const container = $('#searchResultsBody');
            const countElement = $('#searchResultsCount');
            
            if (results.length === 0) {
                container.html(`
                    <div class="search-no-results">
                        <i class="fas fa-search fa-2x mb-2"></i>
                        <p>لا توجد نتائج لـ "${query}"</p>
                        <small>جرب كلمات بحث مختلفة</small>
                    </div>
                `);
                countElement.text('0 نتيجة');
            } else {
                let html = '';
                results.slice(0, 8).forEach(function(result) {
                    html += createSearchResultItem(result);
                });
                
                container.html(html);
                countElement.text(`${results.length} نتيجة`);
                
                // Update view all results link
                $('#viewAllResults').attr('href', '/admin/search/results?q=' + encodeURIComponent(query));
                
                // Debug: log the URL
                console.log('View all results URL:', '/admin/search/results?q=' + encodeURIComponent(query));
            }
            
            showSearchResults();
        }

        // Create search result item HTML
        function createSearchResultItem(result) {
            const icon = getSearchResultIcon(result.type);
            const typeLabel = getSearchResultTypeLabel(result.type);
            const url = getSearchResultUrl(result);
            
            return `
                <a href="${url}" class="search-result-item">
                    <div class="search-result-content">
                        <div class="search-result-title">${result.title}</div>
                        <div class="search-result-subtitle">${result.subtitle || ''}</div>
                    </div>
                    <span class="search-result-type">${typeLabel}</span>
                </a>
            `;
        }

        // Get search result icon
        function getSearchResultIcon(type) {
            const icons = {
                'user': '<i class="fas fa-user"></i>',
                'facility': '<i class="fas fa-building"></i>',
                'product': '<i class="fas fa-box"></i>',
                'booking': '<i class="fas fa-calendar-check"></i>',
                'contract': '<i class="fas fa-file-contract"></i>',
                'category': '<i class="fas fa-th-large"></i>',
                'feature': '<i class="fas fa-star"></i>',
                'attribute': '<i class="fas fa-tags"></i>'
            };
            return icons[type] || '<i class="fas fa-search"></i>';
        }

        // Get search result icon class
        function getSearchResultIconClass(type) {
            const classes = {
                'user': 'bg-primary text-white',
                'facility': 'bg-success text-white',
                'product': 'bg-info text-white',
                'booking': 'bg-warning text-white',
                'contract': 'bg-secondary text-white',
                'category': 'bg-primary text-white',
                'feature': 'bg-warning text-white',
                'attribute': 'bg-info text-white'
            };
            return classes[type] || 'bg-secondary text-white';
        }

        // Get search result type label
        function getSearchResultTypeLabel(type) {
            const labels = {
                'user': 'مستخدم',
                'facility': 'منشأة',
                'product': 'منتج',
                'booking': 'حجز',
                'contract': 'عقد',
                'category': 'تصنيف',
                'feature': 'ميزة',
                'attribute': 'خاصية'
            };
            return labels[type] || 'آخر';
        }

        // Get search result URL
        function getSearchResultUrl(result) {
            const baseUrls = {
                'user': '/admin/users/',
                'facility': '/admin/facilities/',
                'product': '/admin/products/',
                'booking': '/admin/bookings/',
                'contract': '/admin/contracts/',
                'category': '/admin/categories/',
                'feature': '/admin/features/',
                'attribute': '/admin/attributes/'
            };
            
            const baseUrl = baseUrls[result.type];
            return baseUrl ? baseUrl + result.id + '/edit' : '#';
        }

        // Show search loading
        function showSearchLoading() {
            $('#searchResultsBody').html(`
                <div class="search-loading">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                    <p>جاري البحث...</p>
                </div>
            `);
            showSearchResults();
        }

        // Show search error
        function showSearchError(message) {
            $('#searchResultsBody').html(`
                <div class="search-no-results">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                    <p>${message}</p>
                    <small>يرجى المحاولة مرة أخرى</small>
                </div>
            `);
            showSearchResults();
        }

        // Show search results
        function showSearchResults() {
            $('#searchResults').show();
        }

        // Hide search results
        function hideSearchResults() {
            $('#searchResults').hide();
        }

        // Hide search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-box').length) {
                hideSearchResults();
            }
        });

        // Hide search results when pressing Escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                hideSearchResults();
                $('#globalSearch').blur();
            }
        });

        // Search input focus
        $('#globalSearch').on('focus', function() {
            const query = $(this).val().trim();
            if (query.length >= 2 && searchResults.length > 0) {
                showSearchResults();
            }
        });

        // Handle view all results click
        $(document).on('click', '#viewAllResults', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            if (href && href !== '#') {
                window.location.href = href;
            }
        });
    });
    </script>
</body>
</html>
