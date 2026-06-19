@extends('layouts.app')

@section('title', __('general.about.title'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('general.about.title') }}</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    {{ __('general.about.subtitle') }}
                </p>
            </div>
        </div>
    </div>

    <!-- About Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ __('general.about.story') }}</h2>
                <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                    {{ __('general.about.story_desc_1') }}
                </p>
                <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                    {{ __('general.about.story_desc_2') }}
                </p>
                <div class="grid grid-cols-2 gap-6 mt-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">1000+</div>
                        <div class="text-gray-600">{{ __('general.about.properties_available') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">500+</div>
                        <div class="text-gray-600">{{ __('general.about.satisfied_clients') }}</div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                     alt="مشروع" class="rounded-lg shadow-xl">
            </div>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="text-center">
                    <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bullseye text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('general.about.vision') }}</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('general.about.vision_desc') }}
                    </p>
                </div>
                <div class="text-center">
                    <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-flag text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('general.about.mission') }}</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ __('general.about.mission_desc') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Values -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('general.about.values') }}</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('general.about.values_subtitle') }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-handshake text-xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('general.about.trust') }}</h3>
                    <p class="text-gray-600">
                        {{ __('general.about.trust_desc') }}
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('general.about.excellence') }}</h3>
                    <p class="text-gray-600">
                        {{ __('general.about.excellence_desc') }}
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="bg-primary-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lightbulb text-xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-3">{{ __('general.about.innovation') }}</h3>
                    <p class="text-gray-600">
                        {{ __('general.about.innovation_desc') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('general.about.team') }}</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('general.about.team_subtitle') }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name=أحمد+محمد&size=120&background=667eea&color=fff"
                         alt="أحمد محمد" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">أحمد محمد</h3>
                    <p class="text-primary-600 mb-3">{{ __('general.about.ceo') }}</p>
                    <p class="text-gray-600 text-sm">
                        {{ __('general.about.ceo_desc') }}
                    </p>
                </div>
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name=فاطمة+علي&size=120&background=667eea&color=fff"
                         alt="فاطمة علي" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">فاطمة علي</h3>
                    <p class="text-primary-600 mb-3">{{ __('general.about.dev_manager') }}</p>
                    <p class="text-gray-600 text-sm">
                        {{ __('general.about.dev_manager_desc') }}
                    </p>
                </div>
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name=محمد+عبدالله&size=120&background=667eea&color=fff"
                         alt="محمد عبدالله" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">محمد عبدالله</h3>
                    <p class="text-primary-600 mb-3">{{ __('general.about.sales_manager') }}</p>
                    <p class="text-gray-600 text-sm">
                        {{ __('general.about.sales_manager_desc') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
