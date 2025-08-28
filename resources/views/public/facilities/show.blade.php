<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $facility->name }} - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $facility->description ?? 'Premium real estate facility offering quality properties and professional services.' }}">
    <meta name="keywords" content="real estate, properties, {{ $facility->name }}, {{ $facility->category->name ?? 'facility' }}">
    <meta name="author" content="{{ $facility->name }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $facility->name }}">
    <meta property="og:description" content="{{ $facility->description ?? 'Premium real estate facility' }}">
    <meta property="og:image" content="{{ $facility->logo ?? asset('images/default-facility.jpg') }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $facility->name }}">
    <meta property="twitter:description" content="{{ $facility->description ?? 'Premium real estate facility' }}">
    <meta property="twitter:image" content="{{ $facility->logo ?? asset('images/default-facility.jpg') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        :root {
            @php
                $customization = $facility->customization;
                $primaryColor = $customization['colors']['primary'];
                $secondaryColor = $customization['colors']['secondary'];
                $accentColor = $customization['colors']['accent'];
                $backgroundColor = $customization['colors']['background'];
                $textColor = $customization['colors']['text'];
            @endphp
            
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
            --accent-color: {{ $accentColor }};
            --background-color: {{ $backgroundColor }};
            --text-color: {{ $textColor }};
            --font-family: {{ $facility->getFontFamilyValue($customization['typography']['font_family']) }};
            
            /* Generate color variations */
            --primary-50: {{ \App\Helpers\FacilityHelper::lightenColor($primaryColor, 90) }};
            --primary-100: {{ \App\Helpers\FacilityHelper::lightenColor($primaryColor, 80) }};
            --primary-200: {{ \App\Helpers\FacilityHelper::lightenColor($primaryColor, 60) }};
            --primary-300: {{ \App\Helpers\FacilityHelper::lightenColor($primaryColor, 40) }};
            --primary-400: {{ \App\Helpers\FacilityHelper::lightenColor($primaryColor, 20) }};
            --primary-500: {{ $primaryColor }};
            --primary-600: {{ $primaryColor }};
            --primary-700: {{ \App\Helpers\FacilityHelper::darkenColor($primaryColor, 20) }};
            --primary-800: {{ \App\Helpers\FacilityHelper::darkenColor($primaryColor, 40) }};
            --primary-900: {{ \App\Helpers\FacilityHelper::darkenColor($primaryColor, 60) }};
        }
        
        body {
            font-family: var(--font-family);
            color: var(--text-color);
            background-color: var(--background-color);
        }
        
        .primary-gradient {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }
        
        .btn-primary {
            background: var(--primary-color);
            transition: all 0.3s ease;
            @if($facility->button_style === 'square')
                border-radius: 0.375rem;
            @elseif($facility->button_style === 'pill')
                border-radius: 9999px;
            @else
                border-radius: 0.5rem;
            @endif
        }
        
        .btn-primary:hover {
            background: var(--primary-700);
            @if($facility->enable_animations ?? true)
                transform: translateY(-2px);
            @endif
        }
        
        @if($facility->enable_animations ?? true)
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .fade-in {
            animation: fadeIn 1s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @endif
        
        .text-primary-600 { color: var(--primary-color); }
        .text-primary-700 { color: var(--primary-700); }
        .bg-primary-600 { background-color: var(--primary-color); }
        .bg-primary-700 { background-color: var(--primary-700); }
        .bg-primary-800 { background-color: var(--primary-800); }
        .bg-primary-900 { background-color: var(--primary-900); }
        .border-primary-600 { border-color: var(--primary-color); }
        .bg-primary-50 { background-color: var(--primary-50); }
        .bg-primary-100 { background-color: var(--primary-100); }
        
        .hero-bg {
            {!! $facility->hero_background_style !!}
        }
        
        .hero-overlay {
            background-color: rgba(0, 0, 0, {{ ($facility->hero_overlay_opacity ?? 20) / 100 }});
        }
        
        /* Layout Style Variations */
        @if($facility->layout_style === 'minimal')
        .minimal-style {
            box-shadow: none !important;
            border: 1px solid #e5e7eb;
        }
        @elseif($facility->layout_style === 'corporate')
        .corporate-style {
            border-radius: 0.25rem !important;
        }
        @elseif($facility->layout_style === 'classic')
        .classic-style {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        @endif
        
        /* Logo Position */
        .logo-{{ $facility->logo_position ?? 'left' }} {
            @if(($facility->logo_position ?? 'left') === 'center')
                justify-content: center;
            @elseif(($facility->logo_position ?? 'left') === 'right')
                justify-content: flex-end;
            @else
                justify-content: flex-start;
            @endif
        }
        
        /* Custom CSS */
        @if($facility->custom_css)
            {!! $facility->custom_css !!}
        @endif
    </style>
    

    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/95 backdrop-blur-sm z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center logo-{{ $facility->logo_position ?? 'left' }}">
                    <img src="{{ $facility->logo ?? asset('images/logo.png') }}" alt="{{ $facility->name }}" class="h-8 w-auto">
                    <span class="ml-3 text-xl font-bold" style="color: var(--text-color);">{{ $facility->name }}</span>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#about" class="text-gray-700 hover:text-primary-600 transition-colors">{{ __('facilities.show.about') }}</a>
                    <a href="#properties" class="text-gray-700 hover:text-primary-600 transition-colors">{{ __('facilities.show.properties') }}</a>
                    <a href="#contact" class="text-gray-700 hover:text-primary-600 transition-colors">{{ __('facilities.show.contact') }}</a>
                    <a href="{{ route('public.facilities.appointment.form', $facility) }}" 
                       class="btn-primary text-white px-6 py-2 rounded-full font-semibold">
                        {{ __('facilities.show.book_now') }}
                    </a>
                </div>
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-gray-900" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#about" class="block px-3 py-2 text-gray-700 hover:text-primary-600">{{ __('facilities.show.about') }}</a>
                <a href="#properties" class="block px-3 py-2 text-gray-700 hover:text-primary-600">{{ __('facilities.show.properties') }}</a>
                <a href="#contact" class="block px-3 py-2 text-gray-700 hover:text-primary-600">{{ __('facilities.show.contact') }}</a>
                <a href="{{ route('public.facilities.appointment.form', $facility) }}" 
                   class="block px-3 py-2 btn-primary text-white rounded-lg text-center font-semibold">
                    {{ __('facilities.show.book_now') }}
                </a>
            </div>
        </div>
    </nav>

    <!-- Landing Page Hero Section -->
    <div class="relative overflow-hidden hero-bg min-h-screen flex items-center">
        <!-- Background with overlay -->
        <div class="absolute inset-0 hero-overlay"></div>
        
        <!-- Hero Content -->
        <div class="relative z-10 w-full">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="text-white fade-in">
                        <!-- Badge -->
                        <div class="inline-flex items-center space-x-2 space-x-reverse bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                            @if($facility->is_verified)
                                <div class="flex items-center">
                                    <i class="fas fa-shield-check text-green-400 ml-2"></i>
                                    <span class="text-sm">{{ __('facilities.facility_card.verified') }}</span>
                                </div>
                            @endif
                            @if($facility->is_featured)
                                <div class="flex items-center">
                                    <i class="fas fa-crown text-yellow-400 ml-2"></i>
                                    <span class="text-sm">{{ __('facilities.facility_card.featured') }}</span>
                                </div>
                            @endif
                        </div>

                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight">
                            {{ $facility->name }}
                        </h1>
                    
                    @if(\App\Helpers\FacilityHelper::isSingleMode())
                            <p class="text-xl lg:text-2xl text-gray-100 mb-8 leading-relaxed max-w-2xl">
                            {{ config('app.name') }} - {{ $facility->description ?? __('facilities.show.no_description') }}
                        </p>
                    @else
                            <p class="text-xl lg:text-2xl text-gray-100 mb-8 leading-relaxed max-w-2xl">
                            {{ $facility->description ?? __('facilities.show.no_description') }}
                        </p>
                    @endif

                        <!-- Rating and Stats -->
                        <div class="flex items-center space-x-8 space-x-reverse mb-10">
                            <div class="flex items-center">
                                <div class="flex text-yellow-400 mr-3">
                            @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-lg {{ $i <= ($facility->rating ?? 0) ? 'text-yellow-400' : 'text-gray-400' }}"></i>
                            @endfor
                                </div>
                                <span class="text-white text-lg font-semibold">{{ $facility->rating ?? 0 }}</span>
                                <span class="text-gray-300 mr-2">/5</span>
                        </div>
                            <div class="flex items-center text-gray-100">
                                <i class="fas fa-home ml-2"></i>
                                <span>{{ $facility->products_count ?? 0 }} {{ __('facilities.show.properties') }}</span>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('public.facilities.appointment.form', $facility) }}"
                               class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-600 font-bold text-lg rounded-full hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <i class="fas fa-calendar-plus ml-2"></i>
                                {{ __('facilities.show.book_appointment') }}
                            </a>
                            <a href="{{ route('public.facilities.quote.form', $facility) }}"
                               class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white font-bold text-lg rounded-full hover:bg-white hover:text-primary-600 transition-all duration-300">
                                <i class="fas fa-file-invoice ml-2"></i>
                                {{ __('facilities.show.request_quote') }}
                            </a>
                        </div>
                    </div>

                    <div class="relative lg:pl-8">
                        <!-- Main Image with decoration -->
                        <div class="relative {{ ($facility->enable_animations ?? true) ? 'floating-animation' : '' }}">
                            <div class="absolute -inset-4 bg-gradient-to-r from-yellow-400 to-pink-400 rounded-3xl blur opacity-30"></div>
                            <img src="{{ $facility->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                                 alt="{{ $facility->name }}" 
                                 class="relative rounded-2xl shadow-2xl w-full h-96 object-cover">
                        </div>
                        
                        <!-- Floating Stats Card -->
                        <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl p-6 shadow-2xl {{ ($facility->enable_animations ?? true) ? 'fade-in' : '' }}">
                            <div class="flex items-center space-x-4 space-x-reverse">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900">{{ $facility->products_count ?? 0 }}</div>
                                    <div class="text-sm text-gray-600">{{ __('facilities.show.properties') }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900">{{ $facility->rating ?? 0 }}</div>
                                    <div class="text-sm text-gray-600">{{ __('facilities.show.rating') }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ $facility->created_at ? $facility->created_at->format('Y') : '2024' }}
                                    </div>
                                    <div class="text-sm text-gray-600">{{ __('facilities.show.established') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Decorative Elements -->
        @if($facility->enable_animations ?? true)
            <div class="absolute top-20 left-10 w-32 h-32 bg-yellow-400 rounded-full opacity-10 floating-animation"></div>
            <div class="absolute bottom-20 right-10 w-48 h-48 bg-pink-400 rounded-full opacity-10 floating-animation" style="animation-delay: -3s;"></div>
        @endif
    </div>

    <!-- Why Choose Us Section -->
    <div id="about" class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">{{ __('facilities.show.why_choose_us') }}</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        {{ $facility->description ?? __('facilities.show.no_description') }}
                    </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center fade-in">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg transform hover:scale-110 transition-all duration-300">
                        <i class="fas fa-map-marker-alt text-2xl text-white"></i>
                            </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('facilities.show.prime_location') }}</h3>
                                <p class="text-gray-600">{{ $facility->address ?? __('facilities.show.not_specified') }}</p>
                            </div>

                <div class="text-center fade-in" style="animation-delay: 0.1s;">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg transform hover:scale-110 transition-all duration-300">
                        <i class="fas fa-phone text-2xl text-white"></i>
                        </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('facilities.show.24_7_support') }}</h3>
                                <p class="text-gray-600">{{ $facility->phone ?? __('facilities.show.not_specified') }}</p>
                            </div>

                <div class="text-center fade-in" style="animation-delay: 0.2s;">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg transform hover:scale-110 transition-all duration-300">
                        <i class="fas fa-envelope text-2xl text-white"></i>
                        </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('facilities.show.direct_contact') }}</h3>
                                <p class="text-gray-600">{{ $facility->email ?? __('facilities.show.not_specified') }}</p>
                            </div>

                <div class="text-center fade-in" style="animation-delay: 0.3s;">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg transform hover:scale-110 transition-all duration-300">
                        <i class="fas fa-globe text-2xl text-white"></i>
                        </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('facilities.show.online_presence') }}</h3>
                                <p class="text-gray-600">
                                    @if($facility->website)
                            <a href="{{ $facility->website }}" target="_blank" class="text-primary-600 hover:text-primary-700 transition-colors">
                                {{ __('facilities.show.visit_website') }}
                                        </a>
                                    @else
                                        {{ __('facilities.show.not_specified') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

    <!-- Properties Section -->
    <div id="properties" class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">{{ __('facilities.show.featured_properties') }}</h2>
                <p class="text-xl text-gray-600">{{ __('facilities.show.discover_properties') }}</p>
                    </div>

                    @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($products->take(6) as $product)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                            <div class="relative">
                                    <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                     alt="{{ $product->title }}" class="w-full h-64 object-cover">
                                <div class="absolute top-4 right-4 bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ number_format($product->price) }} {{ __('facilities.show.currency') }}
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                            <a href="{{ route('public.products.show', $product) }}" class="hover:text-primary-600 transition-colors">
                                                {{ $product->title }}
                                            </a>
                                        </h3>
                                <p class="text-gray-600 text-sm mb-4 flex items-center">
                                    <i class="fas fa-map-marker-alt ml-2"></i>
                                    {{ $product->address ?? __('facilities.facility_card.location_unknown') }}
                                </p>

                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    @foreach($product->card_attributes->take(3) as $attribute)
                                        <span class="flex items-center">
                                                    @if($attribute->icon)
                                                        <i class="{{ $attribute->icon }} ml-1"></i>
                                                    @else
                                                        <i class="fas fa-info-circle ml-1"></i>
                                                    @endif
                                                    {{ $attribute->pivot->value }}
                                                    @if($attribute->Symbol)
                                                        {{ $attribute->Symbol }}
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>

                                            <a href="{{ route('public.products.show', $product) }}"
                                   class="w-full btn-primary text-white py-3 rounded-lg font-semibold text-center block hover:bg-primary-700 transition-colors">
                                                {{ __('facilities.product.view_details') }}
                                            </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                <div class="text-center">
                    <a href="{{ route('public.products.by-facility', $facility) }}" 
                       class="inline-flex items-center px-8 py-4 border-2 border-primary-600 text-primary-600 font-bold text-lg rounded-full hover:bg-primary-600 hover:text-white transition-all duration-300">
                        {{ __('facilities.show.view_all_properties') }}
                        <i class="fas fa-arrow-right mr-2"></i>
                    </a>
                        </div>
                    @else
                <div class="text-center py-16">
                    <i class="fas fa-home text-6xl text-gray-400 mb-6"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('facilities.show.no_properties') }}</h3>
                    <p class="text-gray-600">{{ __('facilities.show.properties_coming_soon') }}</p>
                        </div>
                    @endif
                </div>
            </div>

    <!-- CTA Section -->
    <div class="hero-bg py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                {{ __('facilities.show.ready_to_start') }}
            </h2>
            <p class="text-xl text-gray-100 mb-10 max-w-2xl mx-auto">
                {{ __('facilities.show.contact_us_today') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.facilities.appointment.form', $facility) }}"
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-600 font-bold text-lg rounded-full hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-calendar-check ml-2"></i>
                    {{ __('facilities.show.schedule_consultation') }}
                </a>
                <a href="#contact"
                   class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white font-bold text-lg rounded-full hover:bg-white hover:text-primary-600 transition-all duration-300">
                    <i class="fas fa-phone ml-2"></i>
                    {{ __('facilities.show.call_now') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">{{ __('facilities.show.get_in_touch') }}</h2>
                <p class="text-xl text-gray-600">{{ __('facilities.show.contact_description') }}</p>
                    </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div class="space-y-8">
                    @if($facility->phone)
                        <div class="flex items-center p-6 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
                            <div class="bg-green-100 p-4 rounded-2xl mr-6">
                                <i class="fas fa-phone text-2xl text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('facilities.show.call_us') }}</h3>
                                <a href="tel:{{ $facility->phone }}" class="text-gray-600 hover:text-primary-600 transition-colors text-lg">
                                    {{ $facility->phone }}
                        </a>
                    </div>
                </div>
                    @endif

                    @if($facility->email)
                        <div class="flex items-center p-6 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
                            <div class="bg-blue-100 p-4 rounded-2xl mr-6">
                                <i class="fas fa-envelope text-2xl text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('facilities.show.email_us') }}</h3>
                                <a href="mailto:{{ $facility->email }}" class="text-gray-600 hover:text-primary-600 transition-colors text-lg">
                                    {{ $facility->email }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($facility->address)
                        <div class="flex items-center p-6 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
                            <div class="bg-purple-100 p-4 rounded-2xl mr-6">
                                <i class="fas fa-map-marker-alt text-2xl text-purple-600"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('facilities.show.visit_us') }}</h3>
                                <p class="text-gray-600 text-lg">{{ $facility->address }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Stats -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('facilities.show.our_numbers') }}</h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-primary-600 mb-2">{{ $facility->products_count ?? 0 }}</div>
                                <div class="text-gray-600">{{ __('facilities.show.properties') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-primary-600 mb-2">{{ $facility->rating ?? 0 }}/5</div>
                                <div class="text-gray-600">{{ __('facilities.show.rating') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-8 shadow-lg">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('facilities.show.quick_actions') }}</h3>
                        <div class="space-y-4">
                            <a href="{{ route('public.facilities.appointment.form', $facility) }}"
                               class="w-full btn-primary text-white py-4 rounded-xl font-bold text-lg text-center block hover:bg-primary-700 transition-colors">
                                <i class="fas fa-calendar-plus ml-2"></i>
                                {{ __('facilities.show.book_appointment') }}
                            </a>
                            <a href="{{ route('public.facilities.quote.form', $facility) }}"
                               class="w-full border-2 border-primary-600 text-primary-600 py-4 rounded-xl font-bold text-lg text-center block hover:bg-primary-600 hover:text-white transition-colors">
                                <i class="fas fa-calculator ml-2"></i>
                                {{ __('facilities.show.get_quote') }}
                            </a>
                            @if($facility->website)
                                <a href="{{ $facility->website }}" target="_blank"
                                   class="w-full border-2 border-gray-300 text-gray-700 py-4 rounded-xl font-bold text-lg text-center block hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-globe ml-2"></i>
                                    {{ __('facilities.show.visit_website') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Similar Facilities -->
                    @if(\App\Helpers\FacilityHelper::isMultiMode() && $similarFacilities->count() > 0)
                        <div class="bg-white rounded-2xl p-8 shadow-lg">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('facilities.show.similar_facilities') }}</h3>
                            <div class="space-y-4">
                                @foreach($similarFacilities->take(3) as $similar)
                                <a href="{{ route('public.facilities.show', $similar) }}"
                                       class="flex items-center p-4 hover:bg-gray-50 rounded-xl transition-colors">
                                    <img src="{{ $similar->logo ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80' }}"
                                             alt="{{ $similar->name }}" class="w-12 h-12 rounded-xl object-cover mr-4">
                                    <div>
                                            <h4 class="font-semibold text-gray-900">{{ $similar->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $similar->category->name ?? '' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <img src="{{ $facility->logo ?? asset('images/logo.png') }}" alt="{{ $facility->name }}" class="h-8 w-auto">
                        <span class="ml-3 text-xl font-bold">{{ $facility->name }}</span>
                    </div>
                    <p class="text-gray-400 mb-6">
                        {{ $facility->description ?? __('facilities.show.footer_description') }}
                    </p>
                    <div class="flex space-x-4">
                        @if($facility->website)
                            <a href="{{ $facility->website }}" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-globe text-xl"></i>
                            </a>
                        @endif
                        @if($facility->facebook_url)
                            <a href="{{ $facility->facebook_url }}" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-facebook-f text-xl"></i>
                            </a>
                        @endif
                        @if($facility->twitter_url)
                            <a href="{{ $facility->twitter_url }}" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                        @endif
                        @if($facility->instagram_url)
                            <a href="{{ $facility->instagram_url }}" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                        @endif
                        @if($facility->linkedin_url)
                            <a href="{{ $facility->linkedin_url }}" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-linkedin-in text-xl"></i>
                            </a>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('facilities.show.quick_links') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="#about" class="text-gray-400 hover:text-white transition-colors">{{ __('facilities.show.about') }}</a></li>
                        <li><a href="#properties" class="text-gray-400 hover:text-white transition-colors">{{ __('facilities.show.properties') }}</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-white transition-colors">{{ __('facilities.show.contact') }}</a></li>
                        <li><a href="{{ route('public.facilities.appointment.form', $facility) }}" class="text-gray-400 hover:text-white transition-colors">{{ __('facilities.show.appointments') }}</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('facilities.show.contact_info') }}</h3>
                    <ul class="space-y-2 text-gray-400">
                        @if($facility->phone)
                            <li class="flex items-center">
                                <i class="fas fa-phone ml-2"></i>
                                <span>{{ $facility->phone }}</span>
                            </li>
                        @endif
                        @if($facility->email)
                            <li class="flex items-center">
                                <i class="fas fa-envelope ml-2"></i>
                                <span>{{ $facility->email }}</span>
                            </li>
                        @endif
                        @if($facility->address)
                            <li class="flex items-center">
                                <i class="fas fa-map-marker-alt ml-2"></i>
                                <span>{{ $facility->address }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; {{ date('Y') }} {{ $facility->name }}. {{ __('facilities.show.all_rights_reserved') }}
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add animation on scroll
        function animateOnScroll() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        }

        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll(); // Run on page load

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = event.target.closest('button');
            
            if (!menu.contains(event.target) && !button) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>