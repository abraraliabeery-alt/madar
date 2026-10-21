@extends('client.layouts.app')

@section('title', __('client.help'))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('client.help') }}</h1>

        <div class="grid gap-4">
            <a href="{{ route('client.help.faq') }}" class="bg-white rounded-lg shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="font-semibold text-gray-900">{{ __('client.faq') }}</p>
                    <p class="text-sm text-gray-500">{{ __('client.faq_desc') }}</p>
                </div>
                <i class="fas fa-chevron-left text-gray-400"></i>
            </a>
            <a href="{{ route('client.help.contact') }}" class="bg-white rounded-lg shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="font-semibold text-gray-900">{{ __('client.contact_us') }}</p>
                    <p class="text-sm text-gray-500">{{ __('client.contact_desc') }}</p>
                </div>
                <i class="fas fa-chevron-left text-gray-400"></i>
            </a>
            <a href="{{ route('client.help.tickets') }}" class="bg-white rounded-lg shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="font-semibold text-gray-900">{{ __('client.my_tickets') }}</p>
                    <p class="text-sm text-gray-500">{{ __('client.tickets_desc') }}</p>
                </div>
                <i class="fas fa-chevron-left text-gray-400"></i>
            </a>
        </div>
    </div>
</div>
@endsection
