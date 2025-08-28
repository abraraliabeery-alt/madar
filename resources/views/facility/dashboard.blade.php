@extends('layouts.app')

@section('title', __('facility.dashboard.title'))

@section('content')
<div class="w-full px-4 my-10">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">{{ __('facility.dashboard.title') }}</h1>
    </div>

    <!-- {{ __('facility.dashboard.stats_title') }} -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg border-r-4 border-blue-500 p-4 h-full">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs font-bold text-blue-600 uppercase mb-1">
                        {{ __('facility.dashboard.total_products') }}
                    </div>
                    <div class="text-xl font-bold text-gray-800">{{ $stats['total_products'] }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-box text-3xl text-gray-300"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg border-r-4 border-green-500 p-4 h-full">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs font-bold text-green-600 uppercase mb-1">
                        {{ __('facility.dashboard.total_bookings') }}
                    </div>
                    <div class="text-xl font-bold text-gray-800">{{ $stats['total_bookings'] }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-check text-3xl text-gray-300"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg border-r-4 border-cyan-500 p-4 h-full">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs font-bold text-cyan-600 uppercase mb-1">
                        {{ __('facility.dashboard.pending_bookings') }}
                    </div>
                    <div class="text-xl font-bold text-gray-800">{{ $stats['pending_bookings'] }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-3xl text-gray-300"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg border-r-4 border-yellow-500 p-4 h-full">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs font-bold text-yellow-600 uppercase mb-1">
                        {{ __('facility.dashboard.total_tasks') }}
                    </div>
                    <div class="text-xl font-bold text-gray-800">{{ $stats['total_tasks'] }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-tasks text-3xl text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- {{ __('facility.dashboard.recent_activity') }} -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h6 class="text-lg font-semibold text-blue-600 m-0">{{ __('facility.dashboard.recent_bookings') }}</h6>
            </div>
            <div class="p-6">
                @if($stats['recent_bookings']->count() > 0)
                    @foreach($stats['recent_bookings'] as $booking)
                    <div class="flex items-center mb-4 last:mb-0">
                        <div class="flex-shrink-0">
                            <img class="w-10 h-10 rounded-full object-cover"
                                 src="{{ $booking->user->avatar ?? asset('images/default-avatar.png') }}"
                                 alt="{{ __('facility.dashboard.user_avatar') }}">
                        </div>
                        <div class="flex-1 mr-3">
                            <h6 class="font-semibold text-gray-800 mb-1">{{ $booking->user->name }}</h6>
                            <p class="text-sm text-gray-500 mb-1">{{ $booking->product->name ?? __('facility.dashboard.deleted_product') }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500">{{ __('facility.dashboard.no_recent_bookings') }}</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h6 class="text-lg font-semibold text-blue-600 m-0">{{ __('facility.dashboard.recent_tasks') }}</h6>
            </div>
            <div class="p-6">
                @if($stats['recent_tasks']->count() > 0)
                    @foreach($stats['recent_tasks'] as $task)
                    <div class="flex items-center mb-4 last:mb-0">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-tasks text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 mr-3">
                            <h6 class="font-semibold text-gray-800 mb-1">{{ $task->title }}</h6>
                            <p class="text-sm text-gray-500 mb-1">{{ __('facility.dashboard.assigned_to') }}: {{ $task->assignedTo->name ?? __('facility.dashboard.unassigned') }}</p>
                            <p class="text-xs text-gray-400">{{ $task->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500">{{ __('facility.dashboard.no_recent_tasks') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Landing Page Customization Preview -->
    <div class="w-full mb-6">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h6 class="text-lg font-semibold text-purple-600 m-0 flex items-center">
                    <i class="fas fa-palette mr-2"></i>
                    {{ __('facilities.dashboard.landing_customization') }}
                </h6>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                    <!-- Current Colors Preview -->
                    <div>
                        <h6 class="font-semibold text-gray-800 mb-3">{{ __('facilities.customization.current_colors') }}</h6>
                        <div class="flex items-center space-x-3">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-lg border border-gray-200 shadow-sm"
                                     style="background-color: {{ $facility->primary_color ?? '#2563eb' }}"></div>
                                <span class="text-xs text-gray-500 mt-1">{{ __('facilities.customization.primary') }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-lg border border-gray-200 shadow-sm"
                                     style="background-color: {{ $facility->secondary_color ?? '#1e40af' }}"></div>
                                <span class="text-xs text-gray-500 mt-1">{{ __('facilities.customization.secondary') }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-lg border border-gray-200 shadow-sm"
                                     style="background-color: {{ $facility->accent_color ?? '#f59e0b' }}"></div>
                                <span class="text-xs text-gray-500 mt-1">{{ __('facilities.customization.accent') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Current Settings -->
                    <div>
                        <h6 class="font-semibold text-gray-800 mb-3">{{ __('facilities.dashboard.current_settings') }}</h6>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('facilities.customization.font_family') }}:</span>
                                <span class="font-medium text-gray-800">{{ ucfirst($facility->font_family ?? 'figtree') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('facilities.customization.layout_style') }}:</span>
                                <span class="font-medium text-gray-800">{{ ucfirst($facility->layout_style ?? 'modern') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('facilities.customization.enable_animations') }}:</span>
                                <span class="font-medium {{ ($facility->enable_animations ?? true) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ($facility->enable_animations ?? true) ? __('general.yes') : __('general.no') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="text-left lg:text-right space-y-3">
                        <div class="space-y-2">
                            <a href="{{ route('facility.customization.edit', $facility) }}"
                               class="w-full lg:w-auto bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-palette mr-2"></i>
                                {{ __('facilities.dashboard.customize_now') }}
                            </a>
                            <a href="{{ route('public.facilities.show', $facility) }}"
                               target="_blank"
                               class="w-full lg:w-auto bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                {{ __('facilities.dashboard.view_landing') }}
                            </a>
                        </div>
                        @if($facility->hasCustomization())
                            <p class="text-xs text-green-600">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ __('facilities.dashboard.customized') }}
                            </p>
                        @else
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ __('facilities.dashboard.using_defaults') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- {{ __('facility.dashboard.facility_info') }} -->
    <div class="w-full">
        <div class="bg-white rounded-lg shadow-lg mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                <h6 class="text-lg font-semibold text-blue-600 m-0">{{ __('facility.dashboard.facility_info') }}</h6>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="text-xl font-semibold text-gray-800 mb-2">{{ $facility->name }}</h5>
                        <p class="text-gray-600 mb-3">{{ $facility->description }}</p>
                        <div class="space-y-2">
                            <p class="text-gray-700"><span class="font-semibold">{{ __('facility.form.address') }}:</span> {{ $facility->address }}</p>
                            <p class="text-gray-700"><span class="font-semibold">{{ __('facility.form.phone') }}:</span> {{ $facility->phone_number }}</p>
                            <p class="text-gray-700"><span class="font-semibold">{{ __('facility.form.email') }}:</span> {{ $facility->email }}</p>
                        </div>
                    </div>
                    <div class="text-left md:text-right">
                        @if($facility->logo)
                            <img src="{{ asset($facility->logo) }}"
                                 alt="{{ __('facility.form.logo') }}"
                                 class="max-h-24 w-auto mb-4 mx-auto md:mx-0 md:mr-0">
                        @endif
                        <div class="space-y-3 md:space-y-0 md:space-x-3 md:space-x-reverse flex flex-col md:flex-row md:justify-end">
                            <a href="{{ route('facility.edit') }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-edit ml-2"></i> {{ __('facility.dashboard.edit_facility') }}
                            </a>
                            <a href="{{ route('facility.products.index') }}"
                               class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-box ml-2"></i> {{ __('facility.dashboard.manage_products') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
