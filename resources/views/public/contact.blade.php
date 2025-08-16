@extends('layouts.app')

@section('title', 'اتصل بنا')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">اتصل بنا</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    نحن هنا لمساعدتك. لا تتردد في التواصل معنا لأي استفسار أو مساعدة تحتاجها
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">أرسل لنا رسالة</h2>

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('public.contact.send') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                   required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div class="mb-6">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">الموضوع</label>
                        <select name="subject" id="subject"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                required>
                            <option value="">اختر الموضوع</option>
                            <option value="استفسار عام" {{ old('subject') == 'استفسار عام' ? 'selected' : '' }}>استفسار عام</option>
                            <option value="طلب عرض سعر" {{ old('subject') == 'طلب عرض سعر' ? 'selected' : '' }}>طلب عرض سعر</option>
                            <option value="شكوى" {{ old('subject') == 'شكوى' ? 'selected' : '' }}>شكوى</option>
                            <option value="اقتراح" {{ old('subject') == 'اقتراح' ? 'selected' : '' }}>اقتراح</option>
                            <option value="دعم فني" {{ old('subject') == 'دعم فني' ? 'selected' : '' }}>دعم فني</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">الرسالة</label>
                        <textarea name="message" id="message" rows="5"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="اكتب رسالتك هنا..." required>{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="w-full btn-primary text-white py-3 rounded-lg font-medium">
                        إرسال الرسالة
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="space-y-8">
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">معلومات التواصل</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4 space-x-reverse">
                            <div class="bg-primary-100 p-3 rounded-full">
                                <i class="fas fa-map-marker-alt text-primary-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">العنوان</h4>
                    <p class="text-gray-600">{{ \App\Models\Setting::getValue('contact_address', 'الرياض، المملكة العربية السعودية') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 space-x-reverse">
                            <div class="bg-primary-100 p-3 rounded-full">
                                <i class="fas fa-phone text-primary-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">الهاتف</h4>
                                <p class="text-gray-600">{{ \App\Models\Setting::getValue('contact_phone', '+966 50 123 4567') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 space-x-reverse">
                            <div class="bg-primary-100 p-3 rounded-full">
                                <i class="fas fa-envelope text-primary-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">البريد الإلكتروني</h4>
                                <p class="text-gray-600">{{ \App\Models\Setting::getValue('contact_email', 'info@aqar.com') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 space-x-reverse">
                            <div class="bg-primary-100 p-3 rounded-full">
                                <i class="fas fa-clock text-primary-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">ساعات العمل</h4>
                                <p class="text-gray-600">{{ \App\Models\Setting::getValue('working_hours', 'الأحد - الخميس: 8:00 ص - 6:00 م') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">تابعنا</h3>
                    <div class="flex space-x-4 space-x-reverse">
                        <a href="{{ \App\Models\Setting::getValue('facebook_url', '#') }}" class="bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="{{ \App\Models\Setting::getValue('twitter_url', '#') }}" class="bg-blue-400 text-white p-3 rounded-full hover:bg-blue-500 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="{{ \App\Models\Setting::getValue('instagram_url', '#') }}" class="bg-pink-600 text-white p-3 rounded-full hover:bg-pink-700 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="{{ \App\Models\Setting::getValue('linkedin_url', '#') }}" class="bg-blue-700 text-white p-3 rounded-full hover:bg-blue-800 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="bg-green-500 text-white p-3 rounded-full hover:bg-green-600 transition-colors">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">موقعنا</h2>
                <p class="text-lg text-gray-600">يمكنك زيارة مكتبنا أو التواصل معنا عبر الخريطة</p>
            </div>
            <div class="bg-gray-200 rounded-lg h-96" id="contact-map"></div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #contact-map { direction: ltr; }
        </style>
    @endpush
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            (function () {
                const el = document.getElementById('contact-map');
                if (!el || typeof L === 'undefined') return;

                // Default coordinates for Riyadh, Saudi Arabia
                const defaultLat = 24.7136;
                const defaultLng = 46.6753;
                const contactAddress = '{{ \App\Models\Setting::getValue("contact_address", "الرياض، المملكة العربية السعودية") }}';

                // Try to get coordinates from settings, fallback to default
                const lat = {{ \App\Models\Setting::getValue('contact_latitude', 24.7136) }};
                const lng = {{ \App\Models\Setting::getValue('contact_longitude', 46.6753) }};

                const map = L.map('contact-map', { scrollWheelZoom: false }).setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const marker = L.marker([lat, lng]).addTo(map);
                const popupContent = `<div class="text-sm"><div class="font-semibold mb-1">مكتبنا</div><div class="text-gray-600">${contactAddress}</div></div>`;
                marker.bindPopup(popupContent).openPopup();
            })();
        </script>
    @endpush

    <!-- FAQ Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">الأسئلة الشائعة</h2>
                <p class="text-lg text-gray-600">إجابات على أكثر الأسئلة شيوعاً</p>
            </div>
            <div class="space-y-6">
                @php
                    $faqs = \App\Models\Faq::getActiveFaqs(app()->getLocale());
                @endphp
                
                @forelse($faqs as $faq)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ $faq->question }}</h3>
                        <p class="text-gray-600">{{ $faq->answer }}</p>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">كيف يمكنني البحث عن عقار؟</h3>
                        <p class="text-gray-600">يمكنك استخدام صفحة البحث المتقدم أو تصفح العقارات المتاحة حسب الفئة أو المنطقة.</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">كيف يمكنني التواصل مع مالك العقار؟</h3>
                        <p class="text-gray-600">يمكنك إرسال رسالة مباشرة من صفحة العقار أو الاتصال بالرقم المرفق.</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">هل الخدمة مجانية؟</h3>
                        <p class="text-gray-600">نعم، خدمات البحث والتصفح مجانية. بعض الخدمات المتقدمة قد تتطلب اشتراك.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
