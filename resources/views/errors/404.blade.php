<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Page Not Found') }} - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h1 class="text-9xl font-bold text-gray-300">404</h1>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    {{ __('Page Not Found') }}
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    {{ __('Sorry, the page you are looking for could not be found.') }}
                </p>
                
                @if(isset($originalPath))
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                        <p class="text-sm text-yellow-800">
                            <strong>{{ __('Requested Path:') }}</strong> {{ $originalPath }}
                        </p>
                    </div>
                @endif

                @if(isset($suggestedRoute))
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <p class="text-sm text-blue-800">
                            <strong>{{ __('Suggested Route:') }}</strong> {{ $suggestedRoute }}
                        </p>
                    </div>
                @endif

                @if(isset($suggestions) && !empty($suggestions))
                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-md">
                        <p class="text-sm text-green-800 mb-2">
                            <strong>{{ __('Similar Routes Found:') }}</strong>
                        </p>
                        <ul class="text-sm text-green-700 space-y-1">
                            @foreach($suggestions as $suggestion)
                                <li>• {{ $suggestion }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-8 space-y-4">
                    <a href="{{ route('public.home') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Go Home') }}
                    </a>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('public.search') }}" 
                           class="flex-1 flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Search') }}
                        </a>
                        
                        <a href="{{ route('public.contact') }}" 
                           class="flex-1 flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Contact Us') }}
                        </a>
                    </div>
                </div>

                @if(config('app.debug'))
                    <div class="mt-8 p-4 bg-gray-50 border border-gray-200 rounded-md">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">{{ __('Debug Information') }}</h3>
                        <div class="text-xs text-gray-600 space-y-1">
                            <p><strong>{{ __('URL:') }}</strong> {{ request()->fullUrl() }}</p>
                            <p><strong>{{ __('Method:') }}</strong> {{ request()->method() }}</p>
                            <p><strong>{{ __('User Agent:') }}</strong> {{ request()->userAgent() }}</p>
                            <p><strong>{{ __('IP:') }}</strong> {{ request()->ip() }}</p>
                            @if(isset($originalRoute))
                                <p><strong>{{ __('Missing Route:') }}</strong> {{ $originalRoute }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>