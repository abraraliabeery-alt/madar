@extends('facility.layouts.app')

@section('title', 'إحصائيات المستخدمين')

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">إحصائيات المستخدمين</h3>
            <a href="{{ route('facility.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                <i class="fas fa-arrow-right"></i>
                <span>العودة للقائمة</span>
            </a>
        </div>

        <!-- إحصائيات عامة -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">إجمالي المستخدمين</p>
                        <p class="text-3xl font-bold">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="bg-blue-400 rounded-full p-3">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">المستخدمين النشطين</p>
                        <p class="text-3xl font-bold">{{ $stats['active_users'] }}</p>
                    </div>
                    <div class="bg-green-400 rounded-full p-3">
                        <i class="fas fa-user-check text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">المستخدمين الجدد هذا الشهر</p>
                        <p class="text-3xl font-bold">{{ $stats['new_users_this_month'] }}</p>
                    </div>
                    <div class="bg-yellow-400 rounded-full p-3">
                        <i class="fas fa-user-plus text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">المستخدمين المعلقين</p>
                        <p class="text-3xl font-bold">{{ $stats['inactive_users'] }}</p>
                    </div>
                    <div class="bg-purple-400 rounded-full p-3">
                        <i class="fas fa-user-slash text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- توزيع الأدوار -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-6 py-4 rounded-t-lg">
                    <h5 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-user-shield mr-2"></i>
                        توزيع الأدوار
                    </h5>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($roleStats as $role)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-indigo-500 rounded-full mr-3"></div>
                                    <span class="text-gray-700 font-medium">{{ $role->name }}</span>
                                </div>
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <span class="text-2xl font-bold text-indigo-600">{{ $role->users_count }}</span>
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $role->percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $role->percentage }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- المستخدمين الجدد -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200">
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-t-lg">
                    <h5 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-chart-line mr-2"></i>
                        المستخدمين الجدد
                    </h5>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($recentUsers as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <img src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : asset('assets/images/default-avatar.png') }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-10 h-10 rounded-full mr-3">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">{{ $user->created_at->format('Y-m-d') }}</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- إحصائيات تفصيلية -->
        <div class="mt-8 bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-4 rounded-t-lg">
                <h5 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    إحصائيات تفصيلية
                </h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <h6 class="text-sm font-medium text-gray-600 mb-2">المستخدمين المؤكدين</h6>
                        <h4 class="text-3xl font-bold text-green-600">{{ $stats['verified_users'] }}</h4>
                        <p class="text-sm text-gray-500">{{ $stats['verified_percentage'] }}% من إجمالي المستخدمين</p>
                    </div>
                    <div class="text-center">
                        <h6 class="text-sm font-medium text-gray-600 mb-2">المستخدمين غير المؤكدين</h6>
                        <h4 class="text-3xl font-bold text-yellow-600">{{ $stats['unverified_users'] }}</h4>
                        <p class="text-sm text-gray-500">{{ $stats['unverified_percentage'] }}% من إجمالي المستخدمين</p>
                    </div>
                    <div class="text-center">
                        <h6 class="text-sm font-medium text-gray-600 mb-2">المستخدمين مع الصور الشخصية</h6>
                        <h4 class="text-3xl font-bold text-blue-600">{{ $stats['users_with_avatar'] }}</h4>
                        <p class="text-sm text-gray-500">{{ $stats['avatar_percentage'] }}% من إجمالي المستخدمين</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- توزيع حسب الجنس -->
        @if($genderStats)
        <div class="mt-8 bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="bg-gradient-to-r from-pink-500 to-pink-600 text-white px-6 py-4 rounded-t-lg">
                <h5 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-venus-mars mr-2"></i>
                    توزيع حسب الجنس
                </h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="text-center">
                        <h6 class="text-sm font-medium text-gray-600 mb-2">الذكور</h6>
                        <h4 class="text-3xl font-bold text-blue-600">{{ $genderStats['male'] ?? 0 }}</h4>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $genderStats['male_percentage'] ?? 0 }}%"></div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $genderStats['male_percentage'] ?? 0 }}%</p>
                    </div>
                    <div class="text-center">
                        <h6 class="text-sm font-medium text-gray-600 mb-2">الإناث</h6>
                        <h4 class="text-3xl font-bold text-pink-600">{{ $genderStats['female'] ?? 0 }}</h4>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-pink-500 h-2 rounded-full" style="width: {{ $genderStats['female_percentage'] ?? 0 }}%"></div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $genderStats['female_percentage'] ?? 0 }}%</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- النشاط الشهري -->
        <div class="mt-8 bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-6 py-4 rounded-t-lg">
                <h5 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    النشاط الشهري
                </h5>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($monthlyStats as $month)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h6 class="font-medium text-gray-900">{{ $month['month_name'] }}</h6>
                                <p class="text-sm text-gray-500">{{ $month['year'] }}</p>
                            </div>
                            <div class="flex items-center space-x-4 space-x-reverse">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-cyan-600">{{ $month['new_users'] }}</p>
                                    <p class="text-xs text-gray-500">مستخدم جديد</p>
                                </div>
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-cyan-500 h-2 rounded-full" style="width: {{ $month['percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection