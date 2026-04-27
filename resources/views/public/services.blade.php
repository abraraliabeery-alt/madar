@extends('layouts.app')

@section('title', 'خدماتنا')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-black">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">خدماتنا</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    نقدم مجموعة شاملة من خدمات المشاريع والمنافسات لتلبية احتياجات الجهات والمقاولين
                </p>
            </div>
        </div>
    </div>

    <!-- Services Overview -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">خدماتنا المتكاملة</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                منصة موحّدة لطرح المشاريع وإدارة المنافسات واستقبال العروض والتأهيل والترسية والعقود
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services ?? [] as $service)
                <div class="bg-white rounded-lg shadow-md p-6 text-center card-hover">
                    <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="{{ $service['icon'] }} text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ $service['title'] }}</h3>
                    <p class="text-gray-600 mb-6">{{ $service['description'] }}</p>
                    <ul class="text-sm text-gray-600 space-y-2 text-right">
                        @foreach($service['features'] as $feature)
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 ml-2"></i>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Detailed Services -->
    <div class="bg-white dark:bg-black py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">خدمات مفصلة</h2>
                <p class="text-lg text-gray-600">تعرف على تفاصيل كل خدمة وكيف يمكننا مساعدتك</p>
            </div>

            <!-- Service 1: Projects & Tenders -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">طرح المشاريع والمنافسات</h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        أنشئ منافستك وحدد نطاق الأعمال والميزانية والمدة، ثم استقبل عروض المقاولين وقارنها بوضوح لاتخاذ قرار سريع.
                    </p>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-search text-primary-600 mt-1 ml-3"></i>
                            <span>بحث وتصفية للمشاريع والمنافسات</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-map text-primary-600 mt-1 ml-3"></i>
                            <span>استعراض حسب المدينة والمنطقة</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-bell text-primary-600 mt-1 ml-3"></i>
                            <span>تنبيهات فورية للمنافسات الجديدة</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-heart text-primary-600 mt-1 ml-3"></i>
                            <span>حفظ المنافسات والمتابعة</span>
                        </li>
                    </ul>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="طرح المشاريع والمنافسات" class="rounded-lg shadow-xl">
                </div>
            </div>

            <!-- Service 2: Qualification & Accreditation -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                <div class="order-2 lg:order-1">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="التأهيل والاعتماد" class="rounded-lg shadow-xl">
                </div>
                <div class="order-1 lg:order-2">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">التأهيل والاعتماد</h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        اعرض بيانات المقاولين، المتطلبات، والخبرات والاعتمادات، ثم فعّل التأهيل المبدئي قبل فتح المنافسة والترسية.
                    </p>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-tools text-primary-600 mt-1 ml-3"></i>
                            <span>متطلبات تأهيل واضحة وقابلة للتحديث</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-file-contract text-primary-600 mt-1 ml-3"></i>
                            <span>نماذج ومستندات اعتماد</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-money-bill-wave text-primary-600 mt-1 ml-3"></i>
                            <span>مقارنة العروض والأسعار والمدة</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-balance-scale text-primary-600 mt-1 ml-3"></i>
                            <span>سجل قرارات الترسية وتتبعها</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Service 3: Contracts & Follow-up -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">العقود والمتابعة</h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        بعد الترسية، تابع تنفيذ المشروع من خلال محاضر واستلامات وجدولة زمنية ونقاط تواصل واضحة بين الجهة والمقاول.
                    </p>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-chart-line text-primary-600 mt-1 ml-3"></i>
                            <span>لوحات متابعة لمراحل التنفيذ</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-calculator text-primary-600 mt-1 ml-3"></i>
                            <span>تحليل وتقدير التكلفة</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-lightbulb text-primary-600 mt-1 ml-3"></i>
                            <span>إدارة تغييرات نطاق العمل</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-gavel text-primary-600 mt-1 ml-3"></i>
                            <span>بنود وملاحق العقود</span>
                        </li>
                    </ul>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="العقود والمتابعة" class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-gray-50 dark:bg-black py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">مميزات منصتنا</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300">ما يميزنا عن المنافسين</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-secondary-900 border border-gray-100 dark:border-secondary-800 p-6 rounded-xl shadow-sm text-center">
                    <div class="bg-primary-100 dark:bg-secondary-800 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-gavel text-primary-700"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">طرح منافسات بسرعة</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">انشئ منافستك وحدد النطاق والميزانية والمدة خلال دقائق.</p>
                </div>
                <div class="bg-white dark:bg-secondary-900 border border-gray-100 dark:border-secondary-800 p-6 rounded-xl shadow-sm text-center">
                    <div class="bg-primary-100 dark:bg-secondary-800 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-scale-balanced text-primary-700"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">مقارنة عروض واضحة</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">قارن السعر والمدة والضمان والمرفقات في شاشة واحدة.</p>
                </div>
                <div class="bg-white dark:bg-secondary-900 border border-gray-100 dark:border-secondary-800 p-6 rounded-xl shadow-sm text-center">
                    <div class="bg-primary-100 dark:bg-secondary-800 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-check text-primary-700"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">تأهيل واعتماد</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">متطلبات تأهيل قابلة للتخصيص قبل الترسية لضمان الجاهزية.</p>
                </div>
                <div class="bg-white dark:bg-secondary-900 border border-gray-100 dark:border-secondary-800 p-6 rounded-xl shadow-sm text-center">
                    <div class="bg-primary-100 dark:bg-secondary-800 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-contract text-primary-700"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">ترسية وعقود ومتابعة</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">توثيق قرار الترسية ثم متابعة التنفيذ عبر مراحل ومحاضر.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-primary-600 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6">ابدأ منافستك اليوم</h2>
            <p class="text-xl text-primary-100 mb-8">
                اطرح مشروعك، استقبل عروض المقاولين، وابدأ الترسية والمتابعة عبر منصة واحدة
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.products.index') }}" class="bg-white text-primary-600 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                    تصفح المشاريع
                </a>
                <a href="{{ route('public.contact') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-medium hover:bg-white hover:text-primary-600 transition-colors">
                    تواصل معنا
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
