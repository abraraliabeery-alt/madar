@extends('layouts.app')

@section('title', __('Terms of Service'))

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ __('Terms of Service') }}
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ __('Please read these terms carefully before using our services') }}
            </p>
        </div>

        <!-- Page Content -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-8 py-6">
                <div class="flex items-center justify-center">
                    <i class="fas fa-file-contract text-white text-3xl mr-4"></i>
                    <h2 class="text-2xl font-bold text-white">{{ __('Legal Terms') }}</h2>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Acceptance of Terms -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Acceptance of Terms') }}</h3>
                    </div>
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                        <p class="text-green-800">
                            {{ __('By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.') }}
                        </p>
                    </div>
                </div>

                <!-- Use License -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 rounded-full p-3 mr-4">
                            <i class="fas fa-key text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Use License') }}</h3>
                    </div>
                    <p class="text-gray-700 mb-4 leading-relaxed">
                        {{ __('Permission is granted to temporarily download one copy of the materials (information or software) on our website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:') }}
                    </p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                <span class="font-medium text-red-800">{{ __('Modify or copy materials') }}</span>
                            </div>
                        </div>
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                <span class="font-medium text-red-800">{{ __('Use for commercial purposes') }}</span>
                            </div>
                        </div>
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                <span class="font-medium text-red-800">{{ __('Reverse engineer software') }}</span>
                            </div>
                        </div>
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                <span class="font-medium text-red-800">{{ __('Remove copyright notices') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Responsibilities -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 rounded-full p-3 mr-4">
                            <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('User Responsibilities') }}</h3>
                    </div>
                    <p class="text-gray-700 mb-4">{{ __('As a user of our platform, you agree to:') }}</p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span class="font-medium text-gray-900">{{ __('Provide accurate information') }}</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span class="font-medium text-gray-900">{{ __('Respect other users\' rights') }}</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span class="font-medium text-gray-900">{{ __('Comply with laws') }}</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span class="font-medium text-gray-900">{{ __('No harmful activities') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Privacy and Data Protection -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <i class="fas fa-shield-alt text-indigo-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Privacy and Data Protection') }}</h3>
                    </div>
                    <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded-r-lg">
                        <p class="text-indigo-800">
                            {{ __('Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the website, to understand our practices.') }}
                        </p>
                    </div>
                </div>

                <!-- Limitation of Liability -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 rounded-full p-3 mr-4">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Limitation of Liability') }}</h3>
                    </div>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                        <p class="text-yellow-800">
                            {{ __('In no event shall we or our suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on our website.') }}
                        </p>
                    </div>
                </div>

                <!-- Governing Law -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-gray-100 rounded-full p-3 mr-4">
                            <i class="fas fa-balance-scale text-gray-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Governing Law') }}</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        {{ __('These terms and conditions are governed by and construed in accordance with the laws of the jurisdiction in which we operate.') }}
                    </p>
                </div>

                <!-- Changes to Terms -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-orange-100 rounded-full p-3 mr-4">
                            <i class="fas fa-edit text-orange-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Changes to Terms') }}</h3>
                    </div>
                    <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-r-lg">
                        <p class="text-orange-800">
                            {{ __('We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on the website. Your continued use of the website constitutes acceptance of the modified terms.') }}
                        </p>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary-100 rounded-full p-3 mr-4">
                            <i class="fas fa-envelope text-primary-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('Contact Information') }}</h3>
                    </div>
                    <p class="text-gray-700 mb-4">
                        {{ __('If you have any questions about these Terms of Service, please contact us.') }}
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
