@extends('layouts.app')

@section('title', 'خدماتنا')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">خدماتنا</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    نقدم مجموعة شاملة من الخدمات العقارية المتطورة لتلبية جميع احتياجاتك
                </p>
            </div>
        </div>
    </div>

    <!-- Services Overview -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">خدماتنا المتكاملة</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                نقدم حلولاً عقارية مبتكرة ومتطورة تساعدك في العثور على العقار المناسب أو إدارة ممتلكاتك
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
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">خدمات مفصلة</h2>
                <p class="text-lg text-gray-600">تعرف على تفاصيل كل خدمة وكيف يمكننا مساعدتك</p>
            </div>

            <!-- Service 1: Property Search -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">البحث عن العقارات</h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        نقدم أدوات بحث متقدمة تساعدك في العثور على العقار المناسب بسهولة وسرعة. يمكنك البحث حسب الموقع، السعر، المساحة، والعديد من المعايير الأخرى.
                    </p>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-search text-primary-600 mt-1 ml-3"></i>
                            <span>بحث متقدم مع فلاتر متعددة</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-map text-primary-600 mt-1 ml-3"></i>
                            <span>خريطة تفاعلية للعقارات</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-bell text-primary-600 mt-1 ml-3"></i>
                            <span>تنبيهات فورية للعقارات الجديدة</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-heart text-primary-600 mt-1 ml-3"></i>
                            <span>حفظ العقارات المفضلة</span>
                        </li>
                    </ul>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="البحث عن العقارات" class="rounded-lg shadow-xl">
                </div>
            </div>

            <!-- Service 2: Property Management -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                <div class="order-2 lg:order-1">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="إدارة العقارات" class="rounded-lg shadow-xl">
                </div>
                <div class="order-1 lg:order-2">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">إدارة العقارات</h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        نقدم خدمات إدارة شاملة للعقارات تشمل الصيانة، التأجير، تحصيل الإيجارات، وحل النزاعات. نساعدك في تحقيق أقصى استفادة من ممتلكاتك.
                    </p>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-tools text-primary-600 mt-1 ml-3"></i>
                            <span>صيانة دورية وشاملة</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-file-contract text-primary-600 mt-1 ml-3"></i>
                            <span>إدارة عقود الإيجار</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-money-bill-wave text-primary-600 mt-1 ml-3"></i>
                            <span>تحصيل الإيجارات</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-balance-scale text-primary-600 mt-1 ml-3"></i>
                            <span>حل النزاعات القانونية</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Service 3: Real Estate Consulting -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">الاستشارات العقارية</h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        نقدم استشارات متخصصة في مجال العقارات من خلال فريق من الخبراء المؤهلين. نساعدك في اتخاذ القرارات الصحيحة بشأن الاستثمارات العقارية.
                    </p>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-chart-line text-primary-600 mt-1 ml-3"></i>
                            <span>تحليل السوق العقاري</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-calculator text-primary-600 mt-1 ml-3"></i>
                            <span>تقييم العقارات</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-lightbulb text-primary-600 mt-1 ml-3"></i>
                            <span>نصائح استثمارية</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-gavel text-primary-600 mt-1 ml-3"></i>
                            <span>استشارات قانونية</span>
                        </li>
                    </ul>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="الاستشارات العقارية" class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">مميزات منصتنا</h2>
                <p class="text-lg text-gray-600">ما يميزنا عن المنافسين</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-primary-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">آمنة وموثوقة</h3>
                    <p class="text-gray-600 text-sm">جميع المعاملات محمية ومؤمنة</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-primary-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">24/7 متاحة</h3>
                    <p class="text-gray-600 text-sm">خدمة متاحة على مدار الساعة</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-primary-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">دعم فني</h3>
                    <p class="text-gray-600 text-sm">فريق دعم متخصص ومتاح</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-primary-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">متوافقة مع الجوال</h3>
                    <p class="text-gray-600 text-sm">تصميم متجاوب لجميع الأجهزة</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-primary-600 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6">ابدأ رحلتك العقارية معنا</h2>
            <p class="text-xl text-primary-100 mb-8">
                انضم إلى آلاف العملاء الراضين واستفد من خدماتنا المتطورة
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.products.index') }}" class="bg-white text-primary-600 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                    تصفح العقارات
                </a>
                <a href="{{ route('public.contact') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-medium hover:bg-white hover:text-primary-600 transition-colors">
                    تواصل معنا
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
