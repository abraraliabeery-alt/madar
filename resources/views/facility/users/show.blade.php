@extends('facility.layouts.app')

@section('title', 'عرض المستخدم - ' . $user->name)

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">عرض المستخدم: {{ $user->name }}</h3>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('facility.users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                        <i class="fas fa-edit"></i>
                        <span>تعديل</span>
                    </a>
                    <a href="{{ route('facility.users.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition-colors">
                        <i class="fas fa-arrow-right"></i>
                        <span>العودة للقائمة</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- معلومات المستخدم -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h5 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-user text-primary-600 mr-2"></i>
                            معلومات المستخدم
                        </h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل</label>
                                <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                                <p class="text-gray-900">{{ $user->email }}</p>
                                @if($user->email_verified_at)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 mt-1">مؤكد</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 mt-1">غير مؤكد</span>
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                                <p class="text-gray-900">{{ $user->phone ?? 'لا يوجد' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">الموقع</label>
                                <p class="text-gray-900">{{ $user->location ?? 'لا يوجد' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ الميلاد</label>
                                <p class="text-gray-900">{{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : 'لا يوجد' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">الجنس</label>
                                <p class="text-gray-900">
                                    @if($user->gender == 'male')
                                        ذكر
                                    @elseif($user->gender == 'female')
                                        أنثى
                                    @else
                                        غير محدد
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        @if($user->bio)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                                <p class="text-gray-900 bg-white p-4 rounded-lg border">{{ $user->bio }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- الأدوار والصلاحيات -->
                    <div class="mt-6 bg-gray-50 rounded-lg p-6">
                        <h5 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-user-shield text-primary-600 mr-2"></i>
                            الأدوار والصلاحيات
                        </h5>
                        
                        <div class="space-y-4">
                            @foreach($user->roles as $role)
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex justify-between items-center mb-3">
                                        <h6 class="font-semibold text-gray-800">{{ $role->name }}</h6>
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">دور</span>
                                    </div>
                                    @if($role->permissions->count() > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($role->permissions as $permission)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 text-sm">لا توجد صلاحيات محددة</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- معلومات إضافية -->
                <div>
                    <!-- صورة المستخدم -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-image text-primary-600 mr-2"></i>
                            الصورة الشخصية
                        </h5>
                        <div class="text-center">
                            <img src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : asset('assets/images/default-avatar.png') }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                            <p class="text-sm text-gray-600">{{ $user->name }}</p>
                        </div>
                    </div>

                    <!-- الحالة -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                            الحالة
                        </h5>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">الحالة</span>
                                @if($user->is_active)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">غير نشط</span>
                                @endif
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">تاريخ الانضمام</span>
                                <span class="text-sm text-gray-900">{{ $user->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">آخر تحديث</span>
                                <span class="text-sm text-gray-900">{{ $user->updated_at->format('Y-m-d') }}</span>
                            </div>
                            @if($user->last_login_at)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">آخر دخول</span>
                                    <span class="text-sm text-gray-900">{{ $user->last_login_at->format('Y-m-d H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- الإجراءات السريعة -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h5 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-bolt text-primary-600 mr-2"></i>
                            الإجراءات السريعة
                        </h5>
                        <div class="space-y-3">
                            <a href="{{ route('facility.users.edit', $user) }}" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 space-x-reverse transition-colors">
                                <i class="fas fa-edit"></i>
                                <span>تعديل المستخدم</span>
                            </a>
                            <button type="button" onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_active ? 'false' : 'true' }})" class="w-full {{ $user->is_active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 space-x-reverse transition-colors">
                                <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                <span>{{ $user->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}</span>
                            </button>
                            <button type="button" onclick="openRemoveModal({{ $user->id }}, '{{ $user->name }}')" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 space-x-reverse transition-colors">
                                <i class="fas fa-trash"></i>
                                <span>حذف المستخدم</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for removing user -->
<div id="removeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">حذف المستخدم</h3>
                <button onclick="closeRemoveModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-red-400 mr-3 mt-1"></i>
                        <div>
                            <h4 class="text-sm font-medium text-red-800">تحذير</h4>
                            <p class="text-sm text-red-700 mt-1">هل أنت متأكد من حذف المستخدم <span id="userName"></span>؟</p>
                        </div>
                    </div>
                </div>
            </div>
            <form id="removeForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeRemoveModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                        حذف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openRemoveModal(userId, userName) {
        document.getElementById('userName').textContent = userName;
        document.getElementById('removeForm').action = `/facility/users/${userId}`;
        document.getElementById('removeModal').classList.remove('hidden');
    }

    function closeRemoveModal() {
        document.getElementById('removeModal').classList.add('hidden');
    }

    function toggleUserStatus(userId, isActive) {
        if (confirm(`هل أنت متأكد من ${isActive ? 'تفعيل' : 'إلغاء تفعيل'} هذا المستخدم؟`)) {
            fetch(`/facility/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    is_active: isActive
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء تحديث حالة المستخدم');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء تحديث حالة المستخدم');
            });
        }
    }
</script>
@endpush