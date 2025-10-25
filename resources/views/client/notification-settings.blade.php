@extends('layouts.app')

@section('title', 'إعدادات الإشعارات')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">إعدادات الإشعارات</h1>
                    <p class="text-gray-600">تحكم في كيفية تلقي الإشعارات</p>
                </div>
                <a href="{{ route('client.notifications') }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-right ml-2"></i> العودة للإشعارات
                </a>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="POST" action="{{ route('client.notifications.settings.update') }}">
                @csrf
                
                <!-- Email Notifications -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">إشعارات البريد الإلكتروني</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700">إشعارات الحجوزات</label>
                                <p class="text-sm text-gray-500">تلقي إشعارات عند إنشاء أو تحديث الحجوزات</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notification_email" value="1" 
                                       class="sr-only peer" {{ $user->notification_email ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700">إشعارات العقود</label>
                                <p class="text-sm text-gray-500">تلقي إشعارات عند إنشاء أو تحديث العقود</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notification_email_contracts" value="1" 
                                       class="sr-only peer" {{ $user->notification_email ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SMS Notifications -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">إشعارات الرسائل النصية</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700">إشعارات الحجوزات</label>
                                <p class="text-sm text-gray-500">تلقي إشعارات نصية عند إنشاء أو تحديث الحجوزات</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notification_sms" value="1" 
                                       class="sr-only peer" {{ $user->notification_sms ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Push Notifications -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">الإشعارات الفورية</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700">الإشعارات الفورية</label>
                                <p class="text-sm text-gray-500">تلقي إشعارات فورية في المتصفح</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notification_push" value="1" 
                                       class="sr-only peer" {{ $user->notification_push ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Notification Frequency -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">تكرار الإشعارات</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">متى تريد تلقي الإشعارات؟</label>
                            <p class="text-sm text-gray-500">اختر كيف تريد تلقي الإشعارات</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="notification_frequency" value="immediate" 
                                       class="sr-only peer" {{ $user->notification_frequency === 'immediate' ? 'checked' : '' }}>
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-blue-600 peer-checked:bg-blue-600 mr-3"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">فوري</div>
                                    <div class="text-sm text-gray-500">تلقي الإشعارات فوراً</div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="notification_frequency" value="hourly" 
                                       class="sr-only peer" {{ $user->notification_frequency === 'hourly' ? 'checked' : '' }}>
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-blue-600 peer-checked:bg-blue-600 mr-3"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">كل ساعة</div>
                                    <div class="text-sm text-gray-500">تلقي ملخص كل ساعة</div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="notification_frequency" value="daily" 
                                       class="sr-only peer" {{ $user->notification_frequency === 'daily' ? 'checked' : '' }}>
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-blue-600 peer-checked:bg-blue-600 mr-3"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">يومي</div>
                                    <div class="text-sm text-gray-500">تلقي ملخص يومي</div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="notification_frequency" value="weekly" 
                                       class="sr-only peer" {{ $user->notification_frequency === 'weekly' ? 'checked' : '' }}>
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-blue-600 peer-checked:bg-blue-600 mr-3"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">أسبوعي</div>
                                    <div class="text-sm text-gray-500">تلقي ملخص أسبوعي</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save ml-2"></i> حفظ الإعدادات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Custom radio button styles */
input[type="radio"]:checked + div::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 6px;
    height: 6px;
    background-color: white;
    border-radius: 50%;
}

/* Custom toggle switch styles */
input[type="checkbox"]:checked + div {
    background-color: #3b82f6;
}

input[type="checkbox"]:checked + div::after {
    transform: translateX(20px);
}
</style>
@endpush