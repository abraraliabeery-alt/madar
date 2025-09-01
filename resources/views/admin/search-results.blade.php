@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">نتائج البحث</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active">نتائج البحث</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>رجوع
            </a>
        </div>
    </div>

    <!-- Search Query Display -->
    <div class="alert alert-info">
        <i class="fas fa-search me-2"></i>
        <strong>نتائج البحث عن:</strong> "{{ $query }}"
    </div>

    <!-- Search Results Tabs -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="searchTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                        <i class="fas fa-users me-2"></i>المستخدمين ({{ $results['users']->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="facilities-tab" data-bs-toggle="tab" data-bs-target="#facilities" type="button" role="tab">
                        <i class="fas fa-building me-2"></i>المنشآت ({{ $results['facilities']->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">
                        <i class="fas fa-box me-2"></i>المنتجات ({{ $results['products']->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab">
                        <i class="fas fa-calendar-check me-2"></i>الحجوزات ({{ $results['bookings']->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab">
                        <i class="fas fa-th-large me-2"></i>التصنيفات ({{ $results['categories']->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button" role="tab">
                        <i class="fas fa-star me-2"></i>المميزات ({{ $results['features']->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="attributes-tab" data-bs-toggle="tab" data-bs-target="#attributes" type="button" role="tab">
                        <i class="fas fa-tags me-2"></i>الخصائص ({{ $results['attributes']->total() }})
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="searchTabsContent">
                <!-- Users Tab -->
                <div class="tab-pane fade show active" id="users" role="tabpanel">
                    @if($results['users']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>رقم الهاتف</th>
                                        <th>تاريخ التسجيل</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results['users'] as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-3">
                                                    @if($user->avatar)
                                                        <img src="{{ asset($user->avatar) }}" alt="avatar" class="rounded-circle" width="40">
                                                    @else
                                                        <div class="avatar-placeholder rounded-circle">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    <small class="text-muted">{{ $user->primary_role ?? 'مستخدم' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number ?? '-' }}</td>
                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $results['users']->appends(['q' => $query])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد نتائج للمستخدمين</h5>
                        </div>
                    @endif
                </div>

                <!-- Facilities Tab -->
                <div class="tab-pane fade" id="facilities" role="tabpanel">
                    @if($results['facilities']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>اسم المنشأة</th>
                                        <th>الوصف</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results['facilities'] as $facility)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $facility->name }}</h6>
                                        </td>
                                        <td>{{ Str::limit($facility->description, 100) ?? '-' }}</td>
                                        <td>{{ $facility->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="/admin/facilities/{{ $facility->id }}/edit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $results['facilities']->appends(['q' => $query])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد نتائج للمنشآت</h5>
                        </div>
                    @endif
                </div>

                <!-- Products Tab -->
                <div class="tab-pane fade" id="products" role="tabpanel">
                    @if($results['products']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>اسم المنتج</th>
                                        <th>العنوان</th>
                                        <th>السعر</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results['products'] as $product)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $product->title }}</h6>
                                        </td>
                                        <td>{{ $product->address ?? '-' }}</td>
                                        <td>{{ $product->price ? number_format($product->price) . ' ريال' : '-' }}</td>
                                        <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="/admin/products/{{ $product->id }}/edit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $results['products']->appends(['q' => $query])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد نتائج للمنتجات</h5>
                        </div>
                    @endif
                </div>

                <!-- Bookings Tab -->
                <div class="tab-pane fade" id="bookings" role="tabpanel">
                    @if($results['bookings']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم الحجز</th>
                                        <th>المنتج</th>
                                        <th>المستخدم</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results['bookings'] as $booking)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">#{{ $booking->id }}</h6>
                                            @if($booking->booking_number)
                                                <small class="text-muted">{{ $booking->booking_number }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $booking->product->title ?? '-' }}</td>
                                        <td>{{ $booking->user->name ?? '-' }}</td>
                                        <td>{{ $booking->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $results['bookings']->appends(['q' => $query])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد نتائج للحجوزات</h5>
                        </div>
                    @endif
                </div>

                <!-- Categories Tab -->
                <div class="tab-pane fade" id="categories" role="tabpanel">
                    @if($results['categories']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>اسم التصنيف</th>
                                        <th>الوصف</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results['categories'] as $category)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $category->name }}</h6>
                                        </td>
                                        <td>{{ Str::limit($category->description, 100) ?? '-' }}</td>
                                        <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $results['categories']->appends(['q' => $query])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-th-large fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد نتائج للتصنيفات</h5>
                        </div>
                    @endif
                </div>

                <!-- Features Tab -->
                <div class="tab-pane fade" id="features" role="tabpanel">
                    @if($results['features']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>اسم الميزة</th>
                                        <th>الوصف</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results['features'] as $feature)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $feature->getTranslatedName('ar') }}</h6>
                                        </td>
                                        <td>{{ Str::limit($feature->description, 100) ?? '-' }}</td>
                                        <td>{{ $feature->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.features.edit', $feature->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $results['features']->appends(['q' => $query])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد نتائج للمميزات</h5>
                        </div>
                    @endif
                </div>

                <!-- Attributes Tab -->
                <div class="tab-pane fade" id="attributes" role="tabpanel">
                    @if($results['attributes']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>اسم الخاصية</th>
                                        <th>الوصف</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results['attributes'] as $attribute)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $attribute->name }}</h6>
                                        </td>
                                        <td>{{ Str::limit($attribute->description, 100) ?? '-' }}</td>
                                        <td>{{ $attribute->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $results['attributes']->appends(['q' => $query])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد نتائج للخصائص</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-placeholder {
    width: 40px;
    height: 40px;
    background-color: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 2px solid transparent;
}

.nav-tabs .nav-link.active {
    color: #007bff;
    border-bottom-color: #007bff;
    background-color: transparent;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    color: #007bff;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Dark mode support */
body.dark-mode .table {
    color: #e0e0e0;
}

body.dark-mode .table th {
    color: #b0b0b0;
}

body.dark-mode .nav-tabs .nav-link {
    color: #b0b0b0;
}

body.dark-mode .nav-tabs .nav-link.active {
    color: #007bff;
}
</style>
@endsection
