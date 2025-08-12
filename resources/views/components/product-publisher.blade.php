@props(['product', 'showAvatar' => true, 'showRole' => true, 'showVerified' => true])

@php
    $user = $product->owner ?? null;
@endphp

@if($user)
    <div class="flex items-center space-x-3 space-x-reverse p-3 bg-gray-50 rounded-lg">
        @if($showAvatar)
            <div class="flex-shrink-0">
                <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . $user->name . '&color=7C3AED&background=EBF4FF&size=60' }}"
                     alt="{{ $user->name }}"
                     class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
            </div>
        @endif

        <div class="min-w-0 flex-1">
            <div class="flex items-center space-x-2 space-x-reverse mb-1">
                <a href="{{ route('profile.public', $user->id) }}"
                   class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors duration-200">
                    {{ $user->name }}
                </a>

                @if($showRole && $user->primary_role)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $user->primary_role === 'admin' ? 'bg-red-100 text-red-800' :
                           ($user->primary_role === 'facility' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                        {{ $user->primary_role === 'admin' ? 'مدير' :
                           ($user->primary_role === 'facility' ? 'مرفق' : 'عميل') }}
                    </span>
                @endif
            </div>

            <div class="flex items-center space-x-3 space-x-reverse text-xs text-gray-500">
                @if($showVerified && $user->email_verified_at)
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-green-500 ml-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>حساب موثق</span>
                    </div>
                @endif

                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ $user->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <div class="flex-shrink-0">
            <a href="{{ route('profile.public', $user->id) }}"
               class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                </svg>
                عرض البروفايل
            </a>
        </div>
    </div>
@else
    <div class="flex items-center space-x-3 space-x-reverse p-3 bg-gray-50 rounded-lg">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <div class="min-w-0 flex-1">
            <div class="text-sm font-medium text-gray-500">مستخدم غير محدد</div>
            <div class="text-xs text-gray-400">معلومات الناشر غير متاحة</div>
        </div>
    </div>
@endif
