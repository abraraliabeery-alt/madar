@extends('layouts.app')

@section('title', __('Cookies Policy'))

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ __('Cookies Policy') }}
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ __('Learn about how we use cookies to enhance your browsing experience') }}
            </p>
        </div>

        <!-- Page Content -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-8 py-6">
                <div class="flex items-center justify-center">
                    <i class="fas fa-cookie-bite text-white text-3xl mr-4"></i>
                    <h2 class="text-2xl font-bold text-white">{{ __('Cookie Information') }}</h2>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- What are Cookies -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 rounded-full p-3 mr-4">
                            <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('What are Cookies?') }}</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        {{ __('Cookies are small text files that are placed on your device when you visit our website. They help us provide you with a better experience and allow certain features to work properly.') }}
                    </p>
                </div>

                <!-- How We Use Cookies -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-cogs text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('How We Use Cookies') }}</h3>
                    </div>
                    <p class="text-gray-700 mb-4">{{ __('We use cookies for several purposes:') }}</p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                                <span class="font-medium text-gray-900">{{ __('Essential Cookies') }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ __('Necessary for the website to function properly') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                                <span class="font-medium text-gray-900">{{ __('Analytics Cookies') }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ __('To understand how visitors use our website') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-user-cog text-purple-500 mr-2"></i>
                                <span class="font-medium text-gray-900">{{ __('Preference Cookies') }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ __('To remember your settings and choices') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-ad text-orange-500 mr-2"></i>
                                <span class="font-medium text-gray-900">{{ __('Marketing Cookies') }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ __('To provide you with relevant advertisements') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Managing Cookies -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 rounded-full p-3 mr-4">
                            <i class="fas fa-sliders-h text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Managing Cookies') }}</h3>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lightbulb text-blue-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-blue-800">
                                    {{ __('You can control and manage cookies through your browser settings. You can delete existing cookies and prevent new ones from being set. However, disabling certain cookies may affect the functionality of our website.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Third-Party Cookies -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 rounded-full p-3 mr-4">
                            <i class="fas fa-external-link-alt text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Third-Party Cookies') }}</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        {{ __('Some cookies on our website are set by third-party services, such as Google Analytics, social media platforms, and advertising networks. These third parties have their own privacy policies and cookie policies.') }}
                    </p>
                </div>

                <!-- Updates to Policy -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <i class="fas fa-sync-alt text-indigo-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Updates to This Policy') }}</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        {{ __('We may update this cookies policy from time to time. Any changes will be posted on this page with an updated revision date.') }}
                    </p>
                </div>

                <!-- Contact Us -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary-100 rounded-full p-3 mr-4">
                            <i class="fas fa-envelope text-primary-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Contact Us') }}</h3>
                    </div>
                    <p class="text-gray-700 mb-4">
                        {{ __('If you have any questions about our use of cookies, please contact us.') }}
                    </p>
                    <a href="{{ route('public.contact') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        {{ __('Contact Us') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-8">
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <i class="fas fa-arrow-right {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                {{ app()->getLocale() == 'ar' ? 'العودة' : 'Go Back' }}
            </a>
        </div>
    </div>
</div>
@endsection
