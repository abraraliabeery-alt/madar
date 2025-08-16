@props(['user', 'showAvatar' => true, 'showRole' => true, 'size' => 'sm'])

@php
    $sizeClasses = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-lg'
    ];

    $avatarSizes = [
        'xs' => 'w-6 h-6',
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12'
    ];

    $textSize = $sizeClasses[$size] ?? 'text-sm';
    $avatarSize = $avatarSizes[$size] ?? 'w-8 h-8';
@endphp

<div class="flex items-center space-x-2 space-x-reverse">
    @if($showAvatar && $user)
        <div class="flex-shrink-0">
            <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . $user->name . '&color=7C3AED&background=EBF4FF&size=80' }}"
                 alt="{{ $user->name }}"
                 class="{{ $avatarSize }} rounded-full object-cover border border-gray-200">
        </div>
    @endif

    <div class="min-w-0 flex-1">
        <div class="flex items-center space-x-2 space-x-reverse">
            @if($user)
                <a href="{{ route('profile.public', $user->id) }}"
                   class="{{ $textSize }} font-medium text-gray-900 hover:text-blue-600 transition-colors duration-200">
                    {{ $user->name }}
                </a>
            @else
                <span class="{{ $textSize }} font-medium text-gray-500">{{ __('components.profile.undefined_user') }}</span>
            @endif

            @if($showRole && $user && $user->primary_role)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                    {{ $user->primary_role === 'admin' ? 'bg-red-100 text-red-800' :
                       ($user->primary_role === 'facility' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                    {{ $user->primary_role === 'admin' ? __('components.user_roles.admin') :
                       ($user->primary_role === 'facility' ? __('components.user_roles.facility') : __('components.user_roles.customer')) }}
                </span>
            @endif
        </div>

        @if($user && $user->email_verified_at)
            <div class="flex items-center mt-1">
                <svg class="w-3 h-3 text-green-500 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs text-gray-500">{{ __('components.verification.verified_account') }}</span>
            </div>
        @endif
    </div>
</div>
