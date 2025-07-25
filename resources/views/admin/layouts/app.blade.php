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
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="avatar">
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
                <div class="search-box">
                    <input type="text" class="form-control" placeholder="بحث...">
                    <i class="fas fa-search"></i>
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
                    <button class="btn btn-light position-relative" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">الإشعارات</h6>
                        <a href="#" class="dropdown-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar">
                                        <div class="avatar-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-0">تم تسجيل مستخدم جديد</p>
                                    <small class="text-muted">منذ 5 دقائق</small>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-center">عرض كل الإشعارات</a>
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

    @stack('scripts')

    <script>
    $(document).ready(function() {
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
            $('body').addClass('sidebar-open');
        });

        $('#sidebarClose').on('click', function() {
            $('.sidebar').removeClass('show');
            $('body').removeClass('sidebar-open');
        });

        // إغلاق القائمة عند النقر خارجها على الموبايل
        $(document).on('click', function(e) {
            if ($('body').hasClass('sidebar-open') && !$(e.target).closest('.sidebar').length && !$(e.target).closest('#sidebarToggle').length) {
                $('.sidebar').removeClass('show');
                $('body').removeClass('sidebar-open');
            }
        });

        // تبديل الوضع الليلي
        $('#themeToggle').on('click', function() {
            $('body').toggleClass('dark-mode');
            let icon = $(this).find('i');
            if ($('body').hasClass('dark-mode')) {
                icon.removeClass('fa-moon').addClass('fa-sun');
            } else {
                icon.removeClass('fa-sun').addClass('fa-moon');
            }
        });
    });
    </script>
</body>
</html>
