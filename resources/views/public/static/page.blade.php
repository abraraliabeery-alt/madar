@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ $page->localized_title }}
            </h1>
            
            @if($page->description)
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ $page->description }}
                </p>
            @endif
        </div>

        <!-- Page Content -->
        <div class="bg-white rounded-lg shadow-md p-8">
            @if($page->content)
                <div class="prose prose-lg max-w-none">
                    {!! $page->localized_content !!}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">
                        @if(app()->getLocale() === 'en')
                            Content Coming Soon
                        @else
                            المحتوى قيد الإعداد
                        @endif
                    </h3>
                    <p class="text-gray-500">
                        @if(app()->getLocale() === 'en')
                            This page content is being prepared. Please check back later.
                        @else
                            يتم إعداد محتوى هذه الصفحة. يرجى التحقق لاحقاً.
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="text-center mt-8">
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-arrow-right ml-2"></i>
                @if(app()->getLocale() === 'en')
                    Go Back
                @else
                    العودة
                @endif
            </a>
        </div>
    </div>
</div>
@endsection
