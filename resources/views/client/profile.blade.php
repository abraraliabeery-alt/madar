@extends('client.layouts.app')

@section('title', 'ط§ظ„ظ…ظ„ظپ ط§ظ„ط´ط®طµظٹ')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">ط§ظ„ظ…ظ„ظپ ط§ظ„ط´ط®طµظٹ</h1>
            <p class="text-gray-600">ط¥ط¯ط§ط±ط© ظ…ط¹ظ„ظˆظ…ط§طھظƒ ط§ظ„ط´ط®طµظٹط© ظˆط¥ط¹ط¯ط§ط¯ط§طھ ط§ظ„ط­ط³ط§ط¨</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">ط§ظ„ظ…ط¹ظ„ظˆظ…ط§طھ ط§ظ„ط´ط®طµظٹط©</h2>
                    </div>
                    
                    <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط§ط³ظ… ط§ظ„ظƒط§ظ…ظ„</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط¨ط±ظٹط¯ ط§ظ„ط¥ظ„ظƒطھط±ظˆظ†ظٹ</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">ط±ظ‚ظ… ط§ظ„ظ‡ط§طھظپ</label>
                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('phone_number') border-red-500 @enderror">
                                @error('phone_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bank -->
                            <div>
                                <label for="bank_id" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط¨ظ†ظƒ</label>
                                <select id="bank_id" name="bank_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('bank_id') border-red-500 @enderror">
                                    <option value="">ط§ط®طھط± ط§ظ„ط¨ظ†ظƒ</option>
                                    @foreach(\App\Models\Bank::all() as $bank)
                                        <option value="{{ $bank->id }}" {{ old('bank_id', $user->bank_id) == $bank->id ? 'selected' : '' }}>
                                            {{ $bank->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bank Account -->
                            <div>
                                <label for="bank_account" class="block text-sm font-medium text-gray-700 mb-2">ط±ظ‚ظ… ط§ظ„ط­ط³ط§ط¨ ط§ظ„ط¨ظ†ظƒظٹ</label>
                                <input type="text" id="bank_account" name="bank_account" value="{{ old('bank_account', $user->bank_account) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('bank_account') border-red-500 @enderror">
                                @error('bank_account')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Avatar -->
                            <div>
                                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„طµظˆط±ط© ط§ظ„ط´ط®طµظٹط©</label>
                                <input type="file" id="avatar" name="avatar" accept="image/*" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 @error('avatar') border-red-500 @enderror">
                                @error('avatar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">ط±ظˆط§ط¨ط· ط§ظ„طھظˆط§طµظ„ ط§ظ„ط§ط¬طھظ…ط§ط¹ظٹ</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">ظپظٹط³ط¨ظˆظƒ</label>
                                    <input type="url" id="facebook" name="facebook" value="{{ old('facebook', $user->facebook) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label for="twitter" class="block text-sm font-medium text-gray-700 mb-2">طھظˆظٹطھط±</label>
                                    <input type="url" id="twitter" name="twitter" value="{{ old('twitter', $user->twitter) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">ط¥ظ†ط³طھط؛ط±ط§ظ…</label>
                                    <input type="url" id="instagram" name="instagram" value="{{ old('instagram', $user->instagram) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-2">ظ„ظٹظ†ظƒط¯ ط¥ظ†</label>
                                    <input type="url" id="linkedin" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">ط§ظ„ظ…ظˆظ‚ط¹</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">ط®ط· ط§ظ„ط¹ط±ط¶</label>
                                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', $user->latitude) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">ط®ط· ط§ظ„ط·ظˆظ„</label>
                                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', $user->longitude) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors">
                                ط­ظپط¸ ط§ظ„طھط؛ظٹظٹط±ط§طھ
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Profile Picture -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ط§ظ„طµظˆط±ط© ط§ظ„ط´ط®طµظٹط©</h3>
                    <div class="text-center">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Picture" 
                                 class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                        @else
                            <div class="w-24 h-24 bg-primary-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-user text-primary-600 text-2xl"></i>
                            </div>
                        @endif
                        <p class="text-sm text-gray-600">ظٹظ…ظƒظ†ظƒ طھط؛ظٹظٹط± ط§ظ„طµظˆط±ط© ظ…ظ† ط§ظ„ظ†ظ…ظˆط°ط¬</p>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ط­ط§ظ„ط© ط§ظ„ط­ط³ط§ط¨</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">ط§ظ„ط¨ط±ظٹط¯ ط§ظ„ط¥ظ„ظƒطھط±ظˆظ†ظٹ</span>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check ml-1"></i> ظ…ظپط¹ظ„
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle ml-1"></i> ط؛ظٹط± ظ…ظپط¹ظ„
                                </span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">طھط§ط±ظٹط® ط§ظ„ط§ظ†ط¶ظ…ط§ظ…</span>
                            <span class="text-sm text-gray-900">{{ $user->created_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">ط¢ط®ط± طھط­ط¯ظٹط«</span>
                            <span class="text-sm text-gray-900">{{ $user->updated_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ط¥ط¬ط±ط§ط،ط§طھ ط³ط±ظٹط¹ط©</h3>
                    <div class="space-y-2">
                        <a href="{{ route('client.change-password') }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-key ml-2"></i> طھط؛ظٹظٹط± ظƒظ„ظ…ط© ط§ظ„ظ…ط±ظˆط±
                        </a>
                        <a href="{{ route('client.notifications.settings') }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-bell ml-2"></i> ط¥ط¹ط¯ط§ط¯ط§طھ ط§ظ„ط¥ط´ط¹ط§ط±ط§طھ
                        </a>
                        <a href="{{ route('client.settings.privacy') }}" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-shield-alt ml-2"></i> ط¥ط¹ط¯ط§ط¯ط§طھ ط§ظ„ط®طµظˆطµظٹط©
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-primary-100 {
    background-color: #dbeafe;
}

.text-primary-600 {
    color: #2563eb;
}

.bg-primary-600 {
    background-color: #2563eb;
}

.hover\:bg-primary-700:hover {
    background-color: #1d4ed8;
}

.focus\:ring-primary-500:focus {
    --tw-ring-color: #3b82f6;
}

.border-primary-500 {
    border-color: #3b82f6;
}
</style>
@endpush

