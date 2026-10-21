@extends('client.layouts.app')

@section('title', __('client.privacy_settings'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-xl mx-auto px-4">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('client.privacy_settings') }}</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 rounded-lg p-4 mb-6">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="POST" action="{{ route('client.settings.privacy.update') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('client.profile_visibility') }}</label>
                    <select name="profile_visibility" class="w-full rounded-md border-gray-300">
                        <option value="public">{{ __('client.visibility_public') }}</option>
                        <option value="private">{{ __('client.visibility_private') }}</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white rounded-md py-2 hover:bg-blue-700">{{ __('client.save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
