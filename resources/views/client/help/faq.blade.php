@extends('client.layouts.app')

@section('title', __('client.faq'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('client.faq') }}</h1>
            <a href="{{ route('client.help') }}" class="text-sm text-blue-600 hover:underline">{{ __('client.back') }}</a>
        </div>

        <div class="space-y-4">
            @foreach([__('client.faq_q1') => __('client.faq_a1'), __('client.faq_q2') => __('client.faq_a2'), __('client.faq_q3') => __('client.faq_a3')] as $q => $a)
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <p class="font-semibold text-gray-900 mb-2">{{ $q }}</p>
                    <p class="text-sm text-gray-600">{{ $a }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
