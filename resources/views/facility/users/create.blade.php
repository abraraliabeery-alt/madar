@extends('facility.layouts.app')

@section('title', 'إضافة مستخدم جديد')

@section('content')
<div class="w-full px-4 my-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">إضافة مستخدم جديد</h3>
                <a href="{{ route('facility.users.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
                </a>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('facility.users.store') }}" id="createUserForm">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- معلومات أساسية -->
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-user text-primary-600 mr-2"></i>
                            المعلومات الأساسية
                        </h5>

                        <!-- الاسم -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                الاسم الكامل <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                البريد الإلكتروني <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('email') border-red-500 @enderror" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الهاتف -->
                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                            <input type="tel" 
                                   name="phone" 
                                   id="phone" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('phone') border-red-500 @enderror" 
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- كلمة المرور -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                كلمة المرور <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('password') border-red-500 @enderror" 
                                   required>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- تأكيد كلمة المرور -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                تأكيد كلمة المرور <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" 
                                   required>
                        </div>
                    </div>

                    <!-- إعدادات إضافية -->
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-cog text-primary-600 mr-2"></i>
                            الإعدادات الإضافية
                        </h5>

                        <!-- الأدوار -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">الأدوار</label>
                            <div class="space-y-2">
                                @foreach($roles as $role)
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="roles[]" 
                                               value="{{ $role->id }}" 
                                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 @error('roles') border-red-500 @enderror"
                                               {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                        <span class="mr-2 text-sm text-gray-700">{{ $role->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('roles')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الحالة -->
                        <div class="mb-6">
                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                            <select name="is_active" id="is_active" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('is_active') border-red-500 @enderror">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>غير نشط</option>
                            </select>
                            @error('is_active')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الموقع -->
                        <div class="mb-6">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">الموقع</label>
                            <input type="text" 
                                   name="location" 
                                   id="location" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('location') border-red-500 @enderror" 
                                   value="{{ old('location') }}">
                            @error('location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- تاريخ الميلاد -->
                        <div class="mb-6">
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الميلاد</label>
                            <input type="date" 
                                   name="date_of_birth" 
                                   id="date_of_birth" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('date_of_birth') border-red-500 @enderror" 
                                   value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الجنس -->
                        <div class="mb-6">
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">الجنس</label>
                            <select name="gender" id="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('gender') border-red-500 @enderror">
                                <option value="">اختر الجنس</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الوصف -->
                        <div class="mb-6">
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                            <textarea name="bio" 
                                      id="bio" 
                                      rows="3" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('bio') border-red-500 @enderror">{{ old('bio') }}</textarea>
                            @error('bio')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- أزرار الإجراءات -->
                <div class="flex justify-end space-x-4 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('facility.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-arrow-right mr-2"></i>إلغاء
                    </a>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>حفظ المستخدم
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- إضافة مستخدم موجود -->
<div class="mt-8 bg-white rounded-lg shadow-lg">
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-t-lg">
        <h3 class="text-xl font-semibold">إضافة مستخدم موجود</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ route('facility.users.assign-existing') }}" id="assignUserForm">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="existing_user_id" class="block text-sm font-medium text-gray-700 mb-2">اختر المستخدم</label>
                    <select name="existing_user_id" id="existing_user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 @error('existing_user_id') border-red-500 @enderror">
                        <option value="">اختر مستخدم موجود</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('existing_user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الأدوار</label>
                    <div class="space-y-2">
                        @foreach($roles as $role)
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="existing_roles[]" 
                                       value="{{ $role->id }}" 
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <span class="mr-2 text-sm text-gray-700">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-4 space-x-reverse mt-6">
                <a href="{{ route('facility.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>إلغاء
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors" {{ $availableUsers->count() == 0 ? 'disabled' : '' }}>
                    <i class="fas fa-user-plus mr-2"></i>إضافة المستخدم
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // التحقق من تطابق كلمات المرور
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (password !== confirmPassword) {
            this.setCustomValidity('كلمات المرور غير متطابقة');
        } else {
            this.setCustomValidity('');
        }
    });

    // التحقق من صحة النموذج
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('كلمات المرور غير متطابقة');
            return false;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل');
            return false;
        }
    });
</script>
@endpush