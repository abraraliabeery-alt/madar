@extends('layouts.app')

@section('title', 'لوحة تحكم المنشأة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">لوحة تحكم المنشأة</h1>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-right-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي المنتجات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_products'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-right-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                إجمالي الحجوزات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-right-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                الحجوزات المعلقة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-right-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                إجمالي المهام</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_tasks'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الحجوزات الحديثة -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">أحدث الحجوزات</h6>
                </div>
                <div class="card-body">
                    @if($stats['recent_bookings']->count() > 0)
                        @foreach($stats['recent_bookings'] as $booking)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <img class="rounded-circle" src="{{ $booking->user->avatar ?? asset('images/default-avatar.png') }}" 
                                     alt="صورة المستخدم" width="40" height="40">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $booking->user->name }}</h6>
                                <small class="text-muted">{{ $booking->product->name ?? 'منتج محذوف' }}</small>
                                <br>
                                <small class="text-muted">{{ $booking->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">لا توجد حجوزات حديثة</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">أحدث المهام</h6>
                </div>
                <div class="card-body">
                    @if($stats['recent_tasks']->count() > 0)
                        @foreach($stats['recent_tasks'] as $task)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-tasks text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $task->title }}</h6>
                                <small class="text-muted">مُسند إلى: {{ $task->assignedTo->name ?? 'غير محدد' }}</small>
                                <br>
                                <small class="text-muted">{{ $task->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">لا توجد مهام حديثة</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات المنشأة -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات المنشأة</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ $facility->name }}</h5>
                            <p class="text-muted">{{ $facility->description }}</p>
                            <p><strong>العنوان:</strong> {{ $facility->address }}</p>
                            <p><strong>الهاتف:</strong> {{ $facility->phone_number }}</p>
                            <p><strong>البريد الإلكتروني:</strong> {{ $facility->email }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            @if($facility->logo)
                                <img src="{{ asset('storage/' . $facility->logo) }}" 
                                     alt="شعار المنشأة" class="img-fluid mb-3" style="max-height: 100px;">
                            @endif
                            <div class="mt-3">
                                <a href="{{ route('facility.edit') }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> تعديل المنشأة
                                </a>
                                <a href="{{ route('facility.products.index') }}" class="btn btn-success">
                                    <i class="fas fa-box"></i> إدارة المنتجات
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
