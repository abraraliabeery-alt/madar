@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">تعديل الملف الشخصي</h1>
            <p class="text-gray-600">تحديث معلوماتك الشخصية وإعدادات الحساب</p>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 space-x-reverse" aria-label="Tabs">
                    <button onclick="showTab('basic')" id="basic-tab" 
                            class="tab-button active py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                        <i class="fas fa-user ml-2"></i>
                        المعلومات الأساسية
                    </button>
                    <button onclick="showTab('social')" id="social-tab" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm whitespace-nowrap text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-share-alt ml-2"></i>
                        روابط التواصل
                    </button>
                    <button onclick="showTab('location')" id="location-tab" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm whitespace-nowrap text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-map-marker-alt ml-2"></i>
                        الموقع
                    </button>
                    <button onclick="showTab('settings')" id="settings-tab" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm whitespace-nowrap text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-cog ml-2"></i>
                        الإعدادات
                    </button>
                    <button onclick="showTab('security')" id="security-tab" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm whitespace-nowrap text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-shield-alt ml-2"></i>
                        الأمان
                    </button>
                </nav>
            </div>
        </div>

        <!-- Basic Info Tab -->
        <div id="basic-content" class="tab-content">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">المعلومات الأساسية</h2>
                </div>
                
                <form method="POST" action="{{ route('user.profile.update', $user) }}" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('phone_number') border-red-500 @enderror">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank -->
                        <div>
                            <label for="bank_id" class="block text-sm font-medium text-gray-700 mb-2">البنك</label>
                            <select id="bank_id" name="bank_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('bank_id') border-red-500 @enderror">
                                <option value="">اختر البنك</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}" {{ old('bank_id', $user->bank_id) == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bank_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank Account -->
                        <div>
                            <label for="bank_account" class="block text-sm font-medium text-gray-700 mb-2">رقم الحساب البنكي</label>
                            <input type="text" id="bank_account" name="bank_account" value="{{ old('bank_account', $user->bank_account) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('bank_account') border-red-500 @enderror">
                            @error('bank_account')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Avatar -->
                        <div>
                            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">الصورة الشخصية</label>
                            <input type="file" id="avatar" name="avatar" accept="image/*" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('avatar') border-red-500 @enderror">
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors">
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Social Links Tab -->
        <div id="social-content" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">روابط التواصل الاجتماعي</h2>
                </div>
                
                <form method="POST" action="{{ route('user.profile.social-links', $user) }}" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">فيسبوك</label>
                            <input type="url" id="facebook" name="facebook" value="{{ old('facebook', $user->facebook) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="twitter" class="block text-sm font-medium text-gray-700 mb-2">تويتر</label>
                            <input type="url" id="twitter" name="twitter" value="{{ old('twitter', $user->twitter) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">إنستغرام</label>
                            <input type="url" id="instagram" name="instagram" value="{{ old('instagram', $user->instagram) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-2">لينكد إن</label>
                            <input type="url" id="linkedin" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="snapchat" class="block text-sm font-medium text-gray-700 mb-2">سناب شات</label>
                            <input type="text" id="snapchat" name="snapchat" value="{{ old('snapchat', $user->snapchat) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="tiktok" class="block text-sm font-medium text-gray-700 mb-2">تيك توك</label>
                            <input type="text" id="tiktok" name="tiktok" value="{{ old('tiktok', $user->tiktok) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="pinterest" class="block text-sm font-medium text-gray-700 mb-2">بينتيريست</label>
                            <input type="url" id="pinterest" name="pinterest" value="{{ old('pinterest', $user->pinterest) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="youtube" class="block text-sm font-medium text-gray-700 mb-2">يوتيوب</label>
                            <input type="url" id="youtube" name="youtube" value="{{ old('youtube', $user->youtube) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الواتساب</label>
                            <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="telegram" class="block text-sm font-medium text-gray-700 mb-2">تيليجرام</label>
                            <input type="text" id="telegram" name="telegram" value="{{ old('telegram', $user->telegram) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors">
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Location Tab -->
        <div id="location-content" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">الموقع</h2>
                </div>
                
                <form method="POST" action="{{ route('user.profile.location', $user) }}" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">خط العرض</label>
                            <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', $user->latitude) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">خط الطول</label>
                            <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', $user->longitude) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="google_maps_url" class="block text-sm font-medium text-gray-700 mb-2">رابط خرائط جوجل</label>
                            <input type="url" id="google_maps_url" name="google_maps_url" value="{{ old('google_maps_url', $user->google_maps_url) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors">
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Settings Tab -->
        <div id="settings-content" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">الإعدادات</h2>
                </div>
                
                <form method="POST" action="{{ route('user.profile.settings', $user) }}" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Notification Settings -->
                        <div>
                            <h3 class="text-md font-medium text-gray-900 mb-4">إعدادات الإشعارات</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">إشعارات البريد الإلكتروني</label>
                                        <p class="text-sm text-gray-500">تلقي إشعارات عبر البريد الإلكتروني</p>
                                    </div>
                                    <input type="checkbox" name="notification_email" value="1" 
                                           {{ old('notification_email', $user->notification_email) ? 'checked' : '' }}
                                           class="form-checkbox text-primary-600">
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">إشعارات الرسائل النصية</label>
                                        <p class="text-sm text-gray-500">تلقي إشعارات عبر الرسائل النصية</p>
                                    </div>
                                    <input type="checkbox" name="notification_sms" value="1" 
                                           {{ old('notification_sms', $user->notification_sms) ? 'checked' : '' }}
                                           class="form-checkbox text-primary-600">
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">الإشعارات الفورية</label>
                                        <p class="text-sm text-gray-500">تلقي إشعارات فورية في المتصفح</p>
                                    </div>
                                    <input type="checkbox" name="notification_push" value="1" 
                                           {{ old('notification_push', $user->notification_push) ? 'checked' : '' }}
                                           class="form-checkbox text-primary-600">
                                </div>
                                <div>
                                    <label for="notification_frequency" class="block text-sm font-medium text-gray-700 mb-2">تكرار الإشعارات</label>
                                    <select id="notification_frequency" name="notification_frequency" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                        <option value="immediate" {{ old('notification_frequency', $user->notification_frequency) == 'immediate' ? 'selected' : '' }}>فوري</option>
                                        <option value="hourly" {{ old('notification_frequency', $user->notification_frequency) == 'hourly' ? 'selected' : '' }}>كل ساعة</option>
                                        <option value="daily" {{ old('notification_frequency', $user->notification_frequency) == 'daily' ? 'selected' : '' }}>يومي</option>
                                        <option value="weekly" {{ old('notification_frequency', $user->notification_frequency) == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Language Settings -->
                        <div>
                            <h3 class="text-md font-medium text-gray-900 mb-4">إعدادات اللغة</h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">تفعيل اللغات المتعددة</label>
                                    <p class="text-sm text-gray-500">عرض المحتوى بلغات متعددة</p>
                                </div>
                                <input type="checkbox" name="is_multilanguage_enabled" value="1" 
                                       {{ old('is_multilanguage_enabled', $user->is_multilanguage_enabled) ? 'checked' : '' }}
                                       class="form-checkbox text-primary-600">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors">
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Tab -->
        <div id="security-content" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">الأمان</h2>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Change Password -->
                    <div>
                        <h3 class="text-md font-medium text-gray-900 mb-4">تغيير كلمة المرور</h3>
                        <form method="POST" action="{{ route('user.profile.change-password', $user) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الحالية</label>
                                    <input type="password" id="current_password" name="current_password" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('current_password') border-red-500 @enderror">
                                    @error('current_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة</label>
                                    <input type="password" id="password" name="password" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('password') border-red-500 @enderror">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors">
                                    تغيير كلمة المرور
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Delete Account -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-md font-medium text-gray-900 mb-4">حذف الحساب</h3>
                        <p class="text-sm text-gray-600 mb-4">حذف حسابك نهائياً. لا يمكن التراجع عن هذا الإجراء.</p>
                        
                        <form method="POST" action="{{ route('user.profile.destroy', $user) }}" 
                              onsubmit="return confirm('هل أنت متأكد من حذف الحساب؟ هذا الإجراء لا يمكن التراجع عنه.')">
                            @csrf
                            @method('DELETE')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور</label>
                                    <input type="password" id="password" name="password" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('password') border-red-500 @enderror">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="confirmation" class="block text-sm font-medium text-gray-700 mb-2">اكتب "DELETE" للتأكيد</label>
                                    <input type="text" id="confirmation" name="confirmation" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('confirmation') border-red-500 @enderror">
                                    @error('confirmation')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition-colors">
                                    حذف الحساب
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(tab => {
            tab.classList.remove('active', 'border-primary-500', 'text-primary-600');
            tab.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-content').classList.remove('hidden');
        
        // Add active class to selected tab
        const activeTab = document.getElementById(tabName + '-tab');
        activeTab.classList.add('active', 'border-primary-500', 'text-primary-600');
        activeTab.classList.remove('border-transparent', 'text-gray-500');
    }
</script>
@endpush

@push('styles')
<style>
.tab-button.active {
    border-color: #3b82f6;
    color: #3b82f6;
}

.bg-primary-600 {
    background-color: #2563eb;
}

.hover\:bg-primary-700:hover {
    background-color: #1d4ed8;
}

.focus\:ring-primary-500:focus {
    --tw-ring-color: #3b82f6;
}
</style>
@endpush
