@extends('layouts.app')

@section('title', __('Pricing Plans'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('Pricing Plans') }}</h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    {{ __('Choose the perfect plan for your real estate needs') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Pricing Plans -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Basic Plan -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="p-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Basic') }}</h3>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-primary-600">0</span>
                        <span class="text-lg text-gray-600"> {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}/{{ __('month') }}</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-left">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('5 property listings') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Basic search filters') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Email support') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Standard templates') }}</span>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-times mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span>{{ __('Advanced analytics') }}</span>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-times mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span>{{ __('Priority support') }}</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" 
                       class="w-full bg-gray-100 text-gray-900 py-3 px-6 rounded-lg font-medium hover:bg-gray-200 transition-colors duration-200">
                        {{ __('Get Started') }}
                    </a>
                </div>
            </div>

            <!-- Professional Plan -->
            <div class="bg-white rounded-lg shadow-lg border-2 border-primary-500 relative transform scale-105">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-primary-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                        {{ __('Most Popular') }}
                    </span>
                </div>
                <div class="p-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Professional') }}</h3>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-primary-600">29</span>
                        <span class="text-lg text-gray-600"> {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}/{{ __('month') }}</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-left">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Unlimited property listings') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Advanced search filters') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Priority email support') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Premium templates') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Basic analytics') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Booking management') }}</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" 
                       class="w-full bg-primary-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-primary-700 transition-colors duration-200">
                        {{ __('Choose Plan') }}
                    </a>
                </div>
            </div>

            <!-- Enterprise Plan -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="p-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Enterprise') }}</h3>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-primary-600">99</span>
                        <span class="text-lg text-gray-600"> {!! \App\Helpers\LanguageHelper::getSaudiRiyalSymbol() !!}/{{ __('month') }}</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-left">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Everything in Professional') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Advanced analytics dashboard') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('24/7 phone support') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Custom branding') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('API access') }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span class="text-gray-700">{{ __('Dedicated account manager') }}</span>
                        </li>
                    </ul>
                    <a href="{{ route('public.contact') }}" 
                       class="w-full bg-gray-900 text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-800 transition-colors duration-200">
                        {{ __('Contact Sales') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- All Plans Include Section -->
        <div class="mt-16">
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('All Plans Include') }}</h2>
                    <p class="text-lg text-gray-600">{{ __('Every plan comes with these essential features') }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="flex items-start space-x-4 space-x-reverse">
                        <div class="bg-primary-100 p-3 rounded-full">
                            <i class="fas fa-shield-alt text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">{{ __('Secure hosting') }}</h4>
                            <p class="text-gray-600 text-sm">{{ __('Your data is protected with enterprise-grade security') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 space-x-reverse">
                        <div class="bg-primary-100 p-3 rounded-full">
                            <i class="fas fa-mobile-alt text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">{{ __('Mobile responsive') }}</h4>
                            <p class="text-gray-600 text-sm">{{ __('Perfect experience on all devices') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 space-x-reverse">
                        <div class="bg-primary-100 p-3 rounded-full">
                            <i class="fas fa-globe text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">{{ __('Multi-language support') }}</h4>
                            <p class="text-gray-600 text-sm">{{ __('Available in Arabic and English') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 space-x-reverse">
                        <div class="bg-primary-100 p-3 rounded-full">
                            <i class="fas fa-sync text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">{{ __('Regular updates') }}</h4>
                            <p class="text-gray-600 text-sm">{{ __('Always get the latest features and improvements') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 space-x-reverse">
                        <div class="bg-primary-100 p-3 rounded-full">
                            <i class="fas fa-database text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">{{ __('Data backup') }}</h4>
                            <p class="text-gray-600 text-sm">{{ __('Automatic daily backups of your data') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 space-x-reverse">
                        <div class="bg-primary-100 p-3 rounded-full">
                            <i class="fas fa-lock text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">{{ __('SSL security') }}</h4>
                            <p class="text-gray-600 text-sm">{{ __('256-bit encryption for all data transmission') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Frequently Asked Questions') }}</h2>
                <p class="text-lg text-gray-600">{{ __('Everything you need to know about our pricing') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="font-semibold text-gray-900 mb-3">{{ __('Can I change my plan later?') }}</h4>
                    <p class="text-gray-600 text-sm">{{ __('Yes, you can upgrade or downgrade your plan at any time. Changes will be reflected in your next billing cycle.') }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="font-semibold text-gray-900 mb-3">{{ __('Is there a free trial?') }}</h4>
                    <p class="text-gray-600 text-sm">{{ __('Yes, we offer a 14-day free trial for all paid plans. No credit card required to start.') }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="font-semibold text-gray-900 mb-3">{{ __('What payment methods do you accept?') }}</h4>
                    <p class="text-gray-600 text-sm">{{ __('We accept all major credit cards, PayPal, and bank transfers for annual plans.') }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="font-semibold text-gray-900 mb-3">{{ __('Do you offer refunds?') }}</h4>
                    <p class="text-gray-600 text-sm">{{ __('We offer a 30-day money-back guarantee. If you\'re not satisfied, we\'ll refund your payment.') }}</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="mt-16 text-center">
            <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-lg p-8 text-white">
                <h2 class="text-3xl font-bold mb-4">{{ __('Ready to Get Started?') }}</h2>
                <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
                    {{ __('Join thousands of real estate professionals who trust our platform') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" 
                       class="bg-white text-primary-600 py-3 px-8 rounded-lg font-medium hover:bg-gray-100 transition-colors duration-200">
                        {{ __('Start Free Trial') }}
                    </a>
                    <a href="{{ route('public.contact') }}" 
                       class="border border-white text-white py-3 px-8 rounded-lg font-medium hover:bg-white hover:text-primary-600 transition-colors duration-200">
                        {{ __('Contact Sales') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
