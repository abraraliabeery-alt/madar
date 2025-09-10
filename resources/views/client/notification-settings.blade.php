@extends('layouts.app')

@section('title', 'إعدادات الإشعارات')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">إعدادات الإشعارات</h1>
            <p class="text-gray-600">تحكم في كيفية ووقت تلقي الإشعارات</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Settings Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">تفضيلات الإشعارات</h2>
                    </div>
                    
                    <form method="POST" action="{{ route('client.notifications.settings.update') }}" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Email Notifications -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">إشعارات البريد الإلكتروني</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">حجوزات جديدة</h4>
                                        <p class="text-sm text-gray-600">تلقي إشعارات عند تأكيد أو إلغاء الحجوزات</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="email_bookings" value="1" 
                                               {{ old('email_bookings', auth()->user()->notification_email ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">عقود جديدة</h4>
                                        <p class="text-sm text-gray-600">تلقي إشعارات عند إنشاء أو تحديث العقود</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="email_contracts" value="1" 
                                               {{ old('email_contracts', auth()->user()->notification_email ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">مدفوعات</h4>
                                        <p class="text-sm text-gray-600">تلقي إشعارات عند تأكيد أو فشل المدفوعات</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="email_payments" value="1" 
                                               {{ old('email_payments', auth()->user()->notification_email ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">مواعيد</h4>
                                        <p class="text-sm text-gray-600">تلقي إشعارات حول المواعيد المجدولة</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="email_appointments" value="1" 
                                               {{ old('email_appointments', auth()->user()->notification_email ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- SMS Notifications -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">إشعارات الرسائل النصية</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">حجوزات مهمة</h4>
                                        <p class="text-sm text-gray-600">تلقي رسائل نصية للحجوزات العاجلة فقط</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="sms_urgent" value="1" 
                                               {{ old('sms_urgent', auth()->user()->notification_sms ?? false) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">تذكيرات المدفوعات</h4>
                                        <p class="text-sm text-gray-600">تلقي تذكيرات بمواعيد المدفوعات المستحقة</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="sms_payments" value="1" 
                                               {{ old('sms_payments', auth()->user()->notification_sms ?? false) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Push Notifications -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">الإشعارات الفورية</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">جميع الإشعارات</h4>
                                        <p class="text-sm text-gray-600">تلقي إشعارات فورية في المتصفح</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="push_all" value="1" 
                                               {{ old('push_all', auth()->user()->notification_push ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Frequency -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">تكرار الإشعارات</h3>
                            <div>
                                <label for="frequency" class="block text-sm font-medium text-gray-700 mb-2">كم مرة تريد تلقي الإشعارات؟</label>
                                <select id="frequency" name="frequency" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                    <option value="immediate" {{ old('frequency', auth()->user()->notification_frequency ?? 'immediate') == 'immediate' ? 'selected' : '' }}>فوري</option>
                                    <option value="hourly" {{ old('frequency', auth()->user()->notification_frequency ?? 'immediate') == 'hourly' ? 'selected' : '' }}>كل ساعة</option>
                                    <option value="daily" {{ old('frequency', auth()->user()->notification_frequency ?? 'immediate') == 'daily' ? 'selected' : '' }}>يومي</option>
                                    <option value="weekly" {{ old('frequency', auth()->user()->notification_frequency ?? 'immediate') == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors">
                                حفظ الإعدادات
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Current Settings -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">الإعدادات الحالية</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">البريد الإلكتروني</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->notification_email ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ auth()->user()->notification_email ? 'مفعل' : 'معطل' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">الرسائل النصية</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->notification_sms ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ auth()->user()->notification_sms ? 'مفعل' : 'معطل' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">الإشعارات الفورية</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->notification_push ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ auth()->user()->notification_push ? 'مفعل' : 'معطل' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">التكرار</span>
                            <span class="text-sm text-gray-900">{{ ucfirst(auth()->user()->notification_frequency ?? 'immediate') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Help -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">مساعدة</h3>
                    <div class="space-y-3">
                        <p class="text-sm text-gray-600">
                            يمكنك تغيير إعدادات الإشعارات في أي وقت. ستطبق التغييرات فوراً على حسابك.
                        </p>
                        <p class="text-sm text-gray-600">
                            للإشعارات الفورية، تأكد من السماح للموقع بإرسال الإشعارات في متصفحك.
                        </p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>
                    <div class="space-y-2">
                        <a href="{{ route('client.notifications') }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-bell ml-2"></i> عرض الإشعارات
                        </a>
                        <a href="{{ route('client.profile') }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-user ml-2"></i> الملف الشخصي
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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